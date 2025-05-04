<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TicketModel;
use App\Models\TicketUpdateModel;
use App\Models\TicketAttachmentModel;
use App\Models\TicketPartModel;
use App\Models\OrganizationModel;
use App\Models\TechnicianModel;
use App\Models\CustomerModel;

class TicketController extends BaseController
{
    protected $ticketModel;
    protected $updateModel;
    protected $attachmentModel;
    protected $partModel;
    protected $organizationModel;
    protected $technicianModel;
    protected $customerModel;
    
    public function __construct()
    {
        $this->ticketModel = new TicketModel();
        $this->updateModel = new TicketUpdateModel();
        $this->attachmentModel = new TicketAttachmentModel();
        $this->partModel = new TicketPartModel();
        $this->organizationModel = new OrganizationModel();
        $this->technicianModel = new TechnicianModel();
        $this->customerModel = new CustomerModel();

        helper(['ticket']);
    }
    
    public function index()
    {
        $filter = $this->request->getGet('filter');
        $organizationId = $this->request->getGet('organization');
        $technicianId = $this->request->getGet('technician');
        
        $tickets = [];
        $filterTitle = 'All Tickets';
        
        if ($filter === 'unassigned') {
            $tickets = $this->ticketModel->getUnassignedTickets();
            $filterTitle = 'Unassigned Tickets';
        } elseif ($filter && in_array($filter, ['open', 'assigned', 'in_progress', 'resolved', 'closed', 'cancelled'])) {
            $tickets = $this->ticketModel->getTicketsByStatus($filter);
            $filterTitle = ucfirst(str_replace('_', ' ', $filter)) . ' Tickets';
        } elseif ($organizationId) {
            $organization = $this->organizationModel->find($organizationId);
            if ($organization) {
                $tickets = $this->ticketModel->getTicketsByOrganization($organizationId);
                $filterTitle = 'Tickets for ' . $organization['name'];
            }
        } elseif ($technicianId) {
            $technician = $this->technicianModel->find($technicianId);
            if ($technician) {
                $tickets = $this->ticketModel->getTicketsByTechnician($technicianId);
                $filterTitle = 'Tickets Assigned to ' . $technician['name'];
            }
        } else {
            $tickets = $this->ticketModel->getAllTicketsWithDetails();
        }
        
        $data = [
            'title' => $filterTitle,
            'tickets' => $tickets,
            'filter' => $filter,
            'organizationId' => $organizationId,
            'technicianId' => $technicianId
        ];
        
        return view('admin/tickets/index', $data);
    }
    
    public function create()
    {
        $data = [
            'title' => 'Create New Ticket',
            'organizations' => $this->organizationModel->findAll(),
            'technicians' => $this->technicianModel->getActiveTechnicians(),
            'customers' => []
        ];
        
        return view('admin/tickets/create', $data);
    }
    
    public function store()
    {
        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'description' => 'required|min_length[10]',
            'organization_id' => 'required|numeric|is_not_unique[organizations.id]',
            'priority' => 'required|in_list[low,medium,high,urgent]',
            'customer_id' => 'permit_empty|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Start database transaction
        $db = \Config\Database::connect();
        $db->transBegin();
        
        try {
            // Handle customer information
            $customerId = $this->request->getPost('customer_id');
            
            // If creating a new customer
            if (empty($customerId) && $this->request->getPost('new_customer') == '1') {
                $customerData = [
                    'organization_id' => $this->request->getPost('organization_id'),
                    'name' => $this->request->getPost('customer_name'),
                    'email' => $this->request->getPost('customer_email'),
                    'phone' => $this->request->getPost('customer_phone'),
                    'address' => $this->request->getPost('customer_address')
                ];
                
                $this->customerModel->insert($customerData);
                $customerId = $this->customerModel->getInsertID();
            }
            
            $ticketData = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'organization_id' => $this->request->getPost('organization_id'),
                'customer_id' => $customerId,
                'priority' => $this->request->getPost('priority'),
                'status' => 'open',
                'created_by' => session()->get('admin_id')
            ];
            
            // If technician is assigned immediately
            $technicianId = $this->request->getPost('technician_id');
            if (!empty($technicianId)) {
                $ticketData['technician_id'] = $technicianId;
                $ticketData['status'] = 'assigned';
                $ticketData['assigned_at'] = date('Y-m-d H:i:s');
            }
            
            // Insert ticket
            $this->ticketModel->insert($ticketData);
            $ticketId = $this->ticketModel->getInsertID();
            
            // Handle parts
            $partNumbers = $this->request->getPost('part_number');
            $partDescriptions = $this->request->getPost('part_description');
            $partQuantities = $this->request->getPost('part_quantity');
            
            if ($partNumbers) {
                foreach ($partNumbers as $i => $partNumber) {
                    if (!empty($partNumber)) {
                        $partData = [
                            'ticket_id' => $ticketId,
                            'part_number' => $partNumber,
                            'description' => $partDescriptions[$i] ?? '',
                            'quantity' => $partQuantities[$i] ?? 1,
                            'status' => 'unused'
                        ];
                        
                        $this->partModel->insert($partData);
                    }
                }
            }
            
            // Handle file attachments
            $files = $this->request->getFileMultiple('attachments');
            
            if ($files && is_array($files)) {
                foreach ($files as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move(WRITEPATH . 'uploads/tickets', $newName);
                        
                        $attachmentData = [
                            'ticket_id' => $ticketId,
                            'file_name' => $file->getClientName(),
                            'file_path' => 'uploads/tickets/' . $newName,
                            'file_type' => $file->getClientMimeType(),
                            'file_size' => $file->getSize(),
                            'uploaded_by' => session()->get('admin_id'),
                            'uploaded_by_type' => 'admin'
                        ];
                        
                        $this->attachmentModel->insert($attachmentData);
                    }
                }
            }
            
            // Add initial ticket creation note
            $updateData = [
                'ticket_id' => $ticketId,
                'user_id' => session()->get('admin_id'),
                'user_type' => 'admin',
                'comment' => 'Ticket created',
                'is_private' => 1
            ];
            
            $this->updateModel->insert($updateData);
            
            // If technician was assigned, add a note about that
            if (!empty($technicianId)) {
                $technician = $this->technicianModel->find($technicianId);
                
                $assignmentNote = [
                    'ticket_id' => $ticketId,
                    'user_id' => session()->get('admin_id'),
                    'user_type' => 'admin',
                    'comment' => 'Ticket assigned to ' . $technician['name'],
                    'is_private' => 1
                ];
                
                $this->updateModel->insert($assignmentNote);
            }
            
            $db->transCommit();
            
            return redirect()->to('/admin/tickets/view/' . $ticketId)
                ->with('message', 'Ticket created successfully');
        } catch (\Exception $e) {
            $db->transRollback();
            
            return redirect()->back()->withInput()
                ->with('error', 'Error creating ticket: ' . $e->getMessage());
        }
    }
    public function view($id)
    {
        
        try {
            $ticket = $this->ticketModel->getTicketWithDetails($id);
            
            if (!$ticket) {
                return redirect()->to('/admin/tickets')
                    ->with('error', 'Ticket not found');
            }
            
            // Get ticket parts - safely
            $parts = [];
            try {
                $parts = $this->partModel->where('ticket_id', $id)->findAll();
            } catch (\Exception $e) {
                log_message('error', 'Error fetching parts: ' . $e->getMessage());
            }
            
            // Get customer details if available - safely
            $customer = null;
            try {
                if (isset($ticket['customer_id']) && !empty($ticket['customer_id'])) {
                    $customer = $this->customerModel->find($ticket['customer_id']);
                }
            } catch (\Exception $e) {
                log_message('error', 'Error fetching customer: ' . $e->getMessage());
            }
            
            $data = [
                'title' => 'Ticket #' . $id,
                'ticket' => $ticket,
                'updates' => $this->updateModel->getTicketUpdates($id),
                'attachments' => $this->attachmentModel->getTicketAttachments($id),
                'organizations' => $this->organizationModel->findAll(),
                'technicians' => $this->technicianModel->getActiveTechnicians(),
                'parts' => $parts,
                'customer' => $customer
            ];
            
            return view('admin/tickets/view', $data);
        } catch (\Exception $e) {
            log_message('error', 'View method error: ' . $e->getMessage());
            log_message('error', $e->getTraceAsString());
            
            // Simple fallback without the problematic data
            $ticket = $this->ticketModel->find($id);
            $data = [
                'title' => 'Ticket #' . $id,
                'ticket' => $ticket,
                'updates' => [],
                'attachments' => [],
                'organizations' => [],
                'technicians' => [],
                'parts' => [],
                'customer' => null
            ];
            
            return view('admin/tickets/view', $data);
        }
    }
        
    
    public function edit($id)
{
    try {
        // Get the ticket
        $ticketModel = new \App\Models\TicketModel();
        $ticket = $ticketModel->find($id);
        
        if (!$ticket) {
            return redirect()->to('/admin/tickets')
                ->with('error', 'Ticket not found');
        }
        
        // Get organizations for the dropdown
        $organizationModel = new \App\Models\OrganizationModel();
        $organizations = $organizationModel->findAll();
        
        // Get technicians for the dropdown
        $technicianModel = new \App\Models\TechnicianModel();
        $technicians = $technicianModel->getActiveTechnicians();
        
        $data = [
            'title' => 'Edit Ticket #' . $id,
            'ticket' => $ticket,
            'organizations' => $organizations,
            'technicians' => $technicians
        ];
        
        return view('admin/tickets/edit_simple', $data);
    } catch (\Exception $e) {
        log_message('error', 'Error in edit ticket: ' . $e->getMessage());
        return redirect()->to('/admin/tickets')
            ->with('error', 'An error occurred while loading the ticket edit form');
    }
}

    
    public function update($id)
    {
        $ticket = $this->ticketModel->find($id);
        
        if (!$ticket) {
            return redirect()->to('/admin/tickets')
                ->with('error', 'Ticket not found');
        }
        
        $rules = [
            'title' => 'required|min_length[5]|max_length[255]',
            'description' => 'required|min_length[10]',
            'organization_id' => 'required|numeric|is_not_unique[organizations.id]',
            'priority' => 'required|in_list[low,medium,high,urgent]',
            'customer_id' => 'permit_empty|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Start database transaction
        $db = \Config\Database::connect();
        $db->transBegin();
        
        try {
            // Handle customer information
            $customerId = $this->request->getPost('customer_id');
            
            // If creating a new customer
            if (empty($customerId) && $this->request->getPost('new_customer') == '1') {
                $customerData = [
                    'organization_id' => $this->request->getPost('organization_id'),
                    'name' => $this->request->getPost('customer_name'),
                    'email' => $this->request->getPost('customer_email'),
                    'phone' => $this->request->getPost('customer_phone'),
                    'address' => $this->request->getPost('customer_address')
                ];
                
                $this->customerModel->insert($customerData);
                $customerId = $this->customerModel->getInsertID();
            }
            
            $ticketData = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'organization_id' => $this->request->getPost('organization_id'),
                'customer_id' => $customerId,
                'priority' => $this->request->getPost('priority')
            ];
            
            // Handle technician assignment
            $technicianId = $this->request->getPost('technician_id');
            $addAssignmentNote = false;
            
            if (!empty($technicianId) && $ticket['technician_id'] != $technicianId) {
                $ticketData['technician_id'] = $technicianId;
                
                // Only update status to assigned if it's currently open
                if ($ticket['status'] == 'open') {
                    $ticketData['status'] = 'assigned';
                }
                
                $ticketData['assigned_at'] = date('Y-m-d H:i:s');
                $addAssignmentNote = true;
            }
            
            // Update ticket
            $this->ticketModel->update($id, $ticketData);
            
            // Handle parts updates
            $partIds = $this->request->getPost('existing_part_id');
            $partNumbers = $this->request->getPost('existing_part_number');
            $partDescriptions = $this->request->getPost('existing_part_description');
            $partQuantities = $this->request->getPost('existing_part_quantity');
            
            // Update existing parts
            if ($partIds) {
                foreach ($partIds as $i => $partId) {
                    $this->partModel->update($partId, [
                        'part_number' => $partNumbers[$i],
                        'description' => $partDescriptions[$i],
                        'quantity' => $partQuantities[$i]
                    ]);
                }
            }
            
            // Add new parts
            $newPartNumbers = $this->request->getPost('part_number');
            $newPartDescriptions = $this->request->getPost('part_description');
            $newPartQuantities = $this->request->getPost('part_quantity');
            
            if ($newPartNumbers) {
                foreach ($newPartNumbers as $i => $partNumber) {
                    if (!empty($partNumber)) {
                        $partData = [
                            'ticket_id' => $id,
                            'part_number' => $partNumber,
                            'description' => $newPartDescriptions[$i] ?? '',
                            'quantity' => $newPartQuantities[$i] ?? 1,
                            'status' => 'unused'
                        ];
                        
                        $this->partModel->insert($partData);
                    }
                }
            }
            
            // If technician was assigned, add a note about that
            if ($addAssignmentNote) {
                $technician = $this->technicianModel->find($technicianId);
                
                $assignmentNote = [
                    'ticket_id' => $id,
                    'user_id' => session()->get('admin_id'),
                    'user_type' => 'admin',
                    'comment' => 'Ticket reassigned to ' . $technician['name'],
                    'is_private' => 1
                ];
                
                $this->updateModel->insert($assignmentNote);
            }
            
            $db->transCommit();
            
            return redirect()->to('/admin/tickets/view/' . $id)
                ->with('message', 'Ticket updated successfully');
        } catch (\Exception $e) {
            $db->transRollback();
            
            return redirect()->back()->withInput()
                ->with('error', 'Error updating ticket: ' . $e->getMessage());
        }
    }
    
      // Add method to get customers by organization via AJAX
      public function getCustomersByOrganization()
      {
          $organizationId = $this->request->getGet('organization_id');
          
          if (!$organizationId) {
              return $this->response->setJSON(['error' => 'No organization ID provided']);
          }
          
          $customers = $this->customerModel->where('organization_id', $organizationId)->findAll();
          
          return $this->response->setJSON(['customers' => $customers]);
      }
      
      // Method to delete a part
      public function deletePart($ticketId, $partId)
      {
          $part = $this->partModel->find($partId);
          
          if (!$part || $part['ticket_id'] != $ticketId) {
              return $this->response->setJSON(['error' => 'Part not found']);
          }
          
          $this->partModel->delete($partId);
          
          return $this->response->setJSON(['success' => true]);
      }
    
    public function assign($id)
    {
        $ticket = $this->ticketModel->find($id);
        
        if (!$ticket) {
            return redirect()->to('/admin/tickets')
                ->with('error', 'Ticket not found');
        }
        
        $technicianId = $this->request->getPost('technician_id');
        
        if (empty($technicianId)) {
            return redirect()->back()
                ->with('error', 'Please select a technician');
        }
        
        $technician = $this->technicianModel->find($technicianId);
        
        if (!$technician) {
            return redirect()->back()
                ->with('error', 'Selected technician not found');
        }
        
        $db = \Config\Database::connect();
        $db->transBegin();
        
        try {
            // Update ticket
            $ticketData = [
                'technician_id' => $technicianId,
                'status' => 'assigned'
            ];
            
            $this->ticketModel->update($id, $ticketData);
            
            // Add an update note
            $updateData = [
                'ticket_id' => $id,
                'user_id' => session()->get('admin_id'),
                'user_type' => 'admin',
                'comment' => 'Ticket assigned to ' . $technician['name'],
                'is_private' => 1
            ];
            
            $this->updateModel->insert($updateData);
            
            $db->transCommit();
            
            return redirect()->to('/admin/tickets/view/' . $id)
                ->with('message', 'Ticket assigned successfully');
        } catch (\Exception $e) {
            $db->transRollback();
            
            return redirect()->back()
                ->with('error', 'Error assigning ticket: ' . $e->getMessage());
        }
    }
    
    public function unassign($id)
    {
        $ticket = $this->ticketModel->find($id);
        
        if (!$ticket) {
            return redirect()->to('/admin/tickets')
                ->with('error', 'Ticket not found');
        }
        
        if (empty($ticket['technician_id'])) {
            return redirect()->back()
                ->with('error', 'Ticket is not currently assigned');
        }
        
        $db = \Config\Database::connect();
        $db->transBegin();
        
        try {
            // Update ticket
            $ticketData = [
                'technician_id' => null,
                'status' => 'open'
            ];
            
            $this->ticketModel->update($id, $ticketData);
            
            // Add an update note
            $updateData = [
                'ticket_id' => $id,
                'user_id' => session()->get('admin_id'),
                'user_type' => 'admin',
                'comment' => 'Ticket unassigned and returned to open status',
                'is_private' => 1
            ];
            
            $this->updateModel->insert($updateData);
            
            $db->transCommit();
            
            return redirect()->to('/admin/tickets/view/' . $id)
                ->with('message', 'Ticket unassigned successfully');
        } catch (\Exception $e) {
            $db->transRollback();
            
            return redirect()->back()
                ->with('error', 'Error unassigning ticket: ' . $e->getMessage());
        }
    }
    
    public function delete($id)
    {
        $ticket = $this->ticketModel->find($id);
        
        if (!$ticket) {
            return redirect()->to('/admin/tickets')
                ->with('error', 'Ticket not found');
        }
        
        // Get all attachments first to delete files
        $attachments = $this->attachmentModel->getTicketAttachments($id);
        
        // Start transaction
        $db = \Config\Database::connect();
        $db->transBegin();
        
        try {
            // Delete all files
            foreach ($attachments as $attachment) {
                $filePath = WRITEPATH . $attachment['file_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            // Delete ticket and related records (updates and attachments will be deleted via cascading)
            $this->ticketModel->delete($id);
            
            $db->transCommit();
            
            return redirect()->to('/admin/tickets')
                ->with('message', 'Ticket deleted successfully');
        } catch (\Exception $e) {
            $db->transRollback();
            
            return redirect()->back()
                ->with('error', 'Error deleting ticket: ' . $e->getMessage());
        }
    }
    
    // Method to view/download attachment
    public function viewAttachment($id)
    {
        $attachment = $this->attachmentModel->find($id);
        
        if (!$attachment) {
            return redirect()->back()->with('error', 'Attachment not found');
        }
        
        $filePath = WRITEPATH . $attachment['file_path'];
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found on server');
        }
        
        $mimeType = $attachment['file_type'] ?? 'application/octet-stream';
        $fileName = $attachment['file_name'];
        
        // Set headers based on file type
        if (in_array($mimeType, ['application/pdf'])) {
            // For PDFs, display in browser
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: inline; filename="' . $fileName . '"');
        } elseif (in_array($mimeType, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ])) {
            // For Office documents, try to use browser plugins or download
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: inline; filename="' . $fileName . '"');
        } else {
            // For other types, force download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
        }
        
        header('Cache-Control: public, max-age=0');
        header('Content-Length: ' . filesize($filePath));
        
        readfile($filePath);
        exit;
    }
    
    // Method to delete an attachment
    public function deleteAttachment($id)
    {
        $attachment = $this->attachmentModel->find($id);
        
        if (!$attachment) {
            return redirect()->back()->with('error', 'Attachment not found');
        }
        
        $ticketId = $attachment['ticket_id'];
        $filePath = WRITEPATH . $attachment['file_path'];
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $this->attachmentModel->delete($id);
        
        return redirect()->to('/admin/tickets/view/' . $ticketId)
            ->with('message', 'Attachment deleted successfully');
    }
}