<?php

namespace App\Controllers\Tech;

use App\Controllers\BaseController;
use App\Models\TicketModel;
use App\Models\TicketUpdateModel;
use App\Models\TicketPartModel;
use App\Models\TicketAttachmentModel;
use App\Models\CustomerModel;

class TicketController extends BaseController
{
    protected $ticketModel;
    protected $updateModel;
    protected $partModel;
    protected $attachmentModel;
    protected $customerModel;
    
    public function __construct()
    {
        $this->ticketModel = new TicketModel();
        $this->updateModel = new TicketUpdateModel();
        $this->partModel = new TicketPartModel();
        $this->attachmentModel = new TicketAttachmentModel();
        $this->customerModel = new CustomerModel();

        helper('ticket');
    }
    
    public function index()
    {
        $techId = session()->get('tech_id');
        $filter = $this->request->getGet('filter');
        
        // Base query - get all tickets assigned to this technician
        $builder = $this->ticketModel->builder()
            ->select('tickets.*, organizations.name as organization_name, customers.name as customer_name')
            ->join('organizations', 'organizations.id = tickets.organization_id', 'left')
            ->join('customers', 'customers.id = tickets.customer_id', 'left')
            ->where('tickets.technician_id', $techId);
        
        // Apply filters if any
        if ($filter == 'pending') {
            $builder->where('tickets.status', 'assigned')
                ->where('tickets.appointment_date IS NULL');
        } elseif ($filter == 'scheduled') {
            $builder->where('tickets.appointment_date IS NOT NULL')
                ->where('tickets.status', 'scheduled');
        } elseif ($filter == 'in_progress') {
            $builder->where('tickets.status', 'in_progress');
        } elseif ($filter == 'completed') {
            $builder->whereIn('tickets.status', ['resolved', 'closed']);
        }
        
        // Get tickets ordered by priority and date
        $tickets = $builder->orderBy('FIELD(tickets.priority, "urgent", "high", "medium", "low")')
                        ->orderBy('tickets.created_at', 'DESC')
                        ->get()
                        ->getResultArray();
        
        // Fetch parts for each ticket
        foreach ($tickets as &$ticket) {
            // Get parts for this ticket
            $parts = $this->partModel->where('ticket_id', $ticket['id'])->findAll();
            // Store part details as JSON string
            $ticket['part_details'] = !empty($parts) ? json_encode($parts) : null;
        }
        
        $data = [
            'title' => 'My Tickets',
            'tickets' => $tickets,
            'filter' => $filter
        ];
        
        return view('tech/tickets/index', $data);
    }
    
    public function view($id)
    {
        $techId = session()->get('tech_id');
        
        // Get ticket with details, ensure it belongs to this technician
        $ticket = $this->ticketModel->builder()
            ->select('tickets.*, organizations.name as organization_name')
            ->join('organizations', 'organizations.id = tickets.organization_id', 'left')
            ->where('tickets.id', $id)
            ->where('tickets.technician_id', $techId)
            ->get()
            ->getRowArray();

        //log_message('debug', 'Ticket Query Result: ' . json_encode($ticket));
        
        if (!$ticket) {
            return redirect()->to('/tech/tickets')
                ->with('error', 'Ticket not found or not assigned to you');
        }
        
        // Get customer info - Handle case where no customer is assigned
        $customer = null;
        if (!empty($ticket['customer_id'])) {
            $customer = $this->customerModel->where('id', $ticket['customer_id'])->first();
        }
        
        // If no customer is found, create a default empty customer object
        if (!$customer) {
            $customer = [
                'id' => null,
                'name' => 'No customer assigned',
                'email' => '',
                'phone' => '',
                'address' => ''
            ];
        }
        
        // Get parts
        $parts = $this->partModel->where('ticket_id', $id)->findAll();
        
        // Get updates
        $updates = $this->updateModel->where('ticket_id', $id)
                                    ->orderBy('created_at', 'DESC')
                                    ->findAll();
        
        // Get attachments for each update
        foreach ($updates as &$update) {
            $update['attachments'] = $this->attachmentModel
                ->where('ticket_id', $id)
                ->where('update_id', $update['id'])
                ->findAll();
        }
        
        $data = [
            'title' => 'Ticket #' . $id,
            'ticket' => $ticket,
            'customer' => $customer,
            'parts' => $parts,
            'updates' => $updates
        ];
        
        return view('tech/tickets/view', $data);
    }
    
    public function schedule($id)
    {
        $techId = session()->get('tech_id');
        
        log_message('debug', 'Schedule method called for ticket #' . $id . ' by tech ID: ' . $techId);
        
        // Verify ticket belongs to this technician
        $ticket = $this->ticketModel->builder()
            ->where('id', $id)
            ->where('technician_id', $techId)
            ->get()
            ->getRowArray();
        
        log_message('debug', 'Ticket query result: ' . json_encode($ticket));
        
        if (!$ticket) {
            log_message('warning', 'Ticket not found or not assigned to tech: ' . $techId);
            return redirect()->to('/tech/tickets')
                ->with('error', 'Ticket not found or not assigned to you');
        }
        
        // Get customer info - Handle case where no customer is assigned
        $customer = null;
        if (!empty($ticket['customer_id'])) {
            $customer = $this->customerModel->where('id', $ticket['customer_id'])->first();
            log_message('debug', 'Customer found: ' . json_encode($customer));
        } else {
            log_message('info', 'No customer_id found for ticket #' . $id);
        }
        
        // If no customer is found, create a default empty customer object
        if (!$customer) {
            log_message('info', 'Creating default customer object for ticket #' . $id);
            $customer = [
                'id' => null,
                'name' => 'No customer assigned',
                'email' => '',
                'phone' => '',
                'address' => ''
            ];
        }
        
        $data = [
            'title' => 'Schedule Appointment',
            'ticket' => $ticket,
            'customer' => $customer
        ];
        
        log_message('debug', 'Preparing to render schedule view with data: ' . json_encode(array_keys($data)));
        
        try {
            return view('tech/tickets/schedule', $data);
        } catch (\Throwable $e) {
            log_message('critical', 'Error rendering schedule view: ' . $e->getMessage());
            log_message('critical', 'Error trace: ' . $e->getTraceAsString());
            
            // Fallback to a simple error page
            return $this->response->setStatusCode(500)
                ->setBody('An error occurred loading the scheduling page. Please check logs for details.');
        }
    }
    
    public function saveSchedule($id)
    {
        $techId = session()->get('tech_id');
        log_message('debug', 'saveSchedule called for ticket ID: ' . $id . ' by tech ID: ' . $techId);
        
        // Verify ticket belongs to this technician
        $ticket = $this->ticketModel->where('id', $id)
                                  ->where('technician_id', $techId)
                                  ->first();
        
        if (!$ticket) {
            log_message('warning', 'Ticket not found or not assigned to tech ID: ' . $techId);
            return redirect()->to('/tech/tickets')
                ->with('error', 'Ticket not found or not assigned to you');
        }
        
        // Get form data
        $appointmentDate = $this->request->getPost('appointment_date');
        $appointmentTime = $this->request->getPost('appointment_time');
        $currentDate = date('Y-m-d');
        
        // Debug the date comparison
        log_message('debug', 'Comparing dates - Appointment: ' . $appointmentDate . ', Current: ' . $currentDate);
        log_message('debug', 'strtotime comparison: ' . (strtotime($appointmentDate) < strtotime($currentDate) ? 'Past date' : 'Future date'));
        
        // Explicitly validate that the appointment date is not in the past
        if (strtotime($appointmentDate) < strtotime($currentDate)) {
            log_message('warning', 'Past date detected: ' . $appointmentDate . ' is before ' . $currentDate);
            
            // Use session flash data for error message
            session()->setFlashdata('error', 'Cannot schedule appointments in the past. Please select today or a future date.');
            
            // Return with all input
            return redirect()->back()->withInput();
        }
        
        // If today's date, check if the time is in the past
        if ($appointmentDate == $currentDate) {
            $currentTime = date('H:i');
            log_message('debug', 'Same day appointment - Comparing times - Appointment: ' . $appointmentTime . ', Current: ' . $currentTime);
            
            if (strtotime($appointmentTime) <= strtotime($currentTime)) {
                log_message('warning', 'Past time detected: ' . $appointmentTime . ' is before or equal to ' . $currentTime);
                
                session()->setFlashdata('error', 'Cannot schedule appointments in the past. Please select a future time.');
                return redirect()->back()->withInput();
            }
        }
        
        // Proceed with basic validation
        $rules = [
            'appointment_date' => 'required|valid_date',
            'appointment_time' => 'required',
        ];
        
        if (!$this->validate($rules)) {
            log_message('warning', 'Form validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()
                ->with('error', 'Invalid appointment details')
                ->withInput();
        }
        
        // If we reach here, validation has passed
        log_message('debug', 'Appointment validation passed for date: ' . $appointmentDate);
        
        // Update the ticket with appointment details
        $data = [
            'appointment_date' => $appointmentDate,
            'appointment_time' => $appointmentTime,
            'estimated_duration' => $this->request->getPost('estimated_duration') ?? 60,
            'appointment_notes' => $this->request->getPost('appointment_notes'),
            'status' => 'scheduled'
        ];
        
        
        log_message('debug', 'Attempting to update ticket with data: ' . json_encode($data));
        
        try {
            $result = $this->ticketModel->update($id, $data);
            log_message('debug', 'Update result: ' . ($result ? 'Success' : 'Failed') . 
                                ', Affected rows: ' . $this->ticketModel->db->affectedRows() . 
                                ', Last error: ' . json_encode($this->ticketModel->errors()));
            
            if (!$result) {
                return redirect()->back()
                    ->with('error', 'Failed to update ticket: ' . json_encode($this->ticketModel->errors()))
                    ->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during ticket update: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Database error: ' . $e->getMessage())
                ->withInput();
        }
        
        // Add an update to the ticket
        $updateData = [
            'ticket_id' => $id,
            'user_id' => $techId,
            'user_type' => 'technician',
            'comment' => 'Appointment scheduled for ' . date('F j, Y \a\t g:i A', strtotime($data['appointment_date'] . ' ' . $data['appointment_time']))
        ];
        
        if (!empty($data['appointment_notes'])) {
            $updateData['comment'] .= "\n\nNotes: " . $data['appointment_notes'];
        }
        
        try {
            $this->updateModel->insert($updateData);
            log_message('debug', 'Ticket update record created successfully');
        } catch (\Exception $e) {
            log_message('error', 'Error creating ticket update record: ' . $e->getMessage());
            // Continue anyway since the main ticket update was successful
        }
        
        return redirect()->to('/tech/tickets/view/' . $id)
            ->with('message', 'Appointment scheduled successfully');
    }
    
    public function start($id)
    {
        $techId = session()->get('tech_id');
        
        // Verify ticket belongs to this technician
        $ticket = $this->ticketModel->where('id', $id)
                                  ->where('technician_id', $techId)
                                  ->first();
        
        if (!$ticket) {
            return redirect()->to('/tech/tickets')
                ->with('error', 'Ticket not found or not assigned to you');
        }
        
        // Check if ticket is in a valid state to start
        if (!in_array($ticket['status'], ['assigned', 'scheduled'])) {
            return redirect()->to('/tech/tickets/view/' . $id)
                ->with('error', 'This ticket cannot be started because it is already ' . $ticket['status']);
        }
        
        // Start the service
        $this->ticketModel->update($id, [
            'status' => 'in_progress',
            'service_started_at' => date('Y-m-d H:i:s')
        ]);
        
        // Add update comment
        $updateData = [
            'ticket_id' => $id,
            'user_id' => $techId,
            'user_type' => 'technician',
            'comment' => 'Service started'
        ];
        
        $notes = $this->request->getPost('start_notes');
        if (!empty($notes)) {
            $updateData['comment'] .= ":\n" . $notes;
        }
        
        $this->updateModel->insert($updateData);
        
        return redirect()->to('/tech/tickets/view/' . $id)
            ->with('message', 'Service started successfully');
    }
    
    public function complete($id)
    {
        $techId = session()->get('tech_id');
        
        // Verify ticket belongs to this technician
        $ticket = $this->ticketModel->where('id', $id)
                                  ->where('technician_id', $techId)
                                  ->first();
        
        if (!$ticket) {
            return redirect()->to('/tech/tickets')
                ->with('error', 'Ticket not found or not assigned to you');
        }
        
        // Check if ticket is in progress
        if ($ticket['status'] != 'in_progress') {
            return redirect()->to('/tech/tickets/view/' . $id)
                ->with('error', 'This ticket cannot be completed because it is not in progress');
        }
        
        $rules = [
            'completion_code' => 'required',
            'completion_notes' => 'required',
            'customer_signature' => 'required',
            'part_ids.*' => 'permit_empty|numeric',
            'part_status.*' => 'permit_empty'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('error', 'Please fill in all required fields')
                ->withInput();
        }
        
        // Start a database transaction
        $db = \Config\Database::connect();
        $db->transBegin();
        
        try {
            // Update ticket status
            $completionCode = $this->request->getPost('completion_code');
            $completionNotes = $this->request->getPost('completion_notes');
            
            // Determine the new status based on completion code
            $newStatus = 'resolved';
            if (in_array($completionCode, ['PARTS_MISSING', 'PARTS_DEFECTIVE', 'WRONG_PARTS', 'NO_ACCESS', 'CUSTOMER_CANCEL', 'PARTIAL'])) {
                $newStatus = 'partially_completed';
            }
            
            $this->ticketModel->update($id, [
                'status' => $newStatus,
                'completion_code' => $completionCode,
                'service_completed_at' => date('Y-m-d H:i:s'),
                'customer_signature' => $this->request->getPost('customer_signature')
            ]);
            
            // Update parts status
            $partIds = $this->request->getPost('part_ids');
            $partStatuses = $this->request->getPost('part_status');
            $partNotes = $this->request->getPost('part_notes');
            
            if ($partIds && $partStatuses) {
                foreach ($partIds as $index => $partId) {
                    $this->partModel->update($partId, [
                        'status' => $partStatuses[$index],
                        'notes' => $partNotes[$index] ?? null
                    ]);
                }
            }
            
            // Add update comment
            $updateData = [
                'ticket_id' => $id,
                'user_id' => $techId,
                'user_type' => 'technician',
                'comment' => "Service completed with code: {$completionCode}\n\n{$completionNotes}"
            ];
            
            $this->updateModel->insert($updateData);
            $updateId = $this->updateModel->getInsertID();
            
            // Handle file attachments
            $files = $this->request->getFileMultiple('attachments');
            
            if ($files) {
                foreach ($files as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move(WRITEPATH . 'uploads/tickets', $newName);
                        
                        $attachmentData = [
                            'ticket_id' => $id,
                            'update_id' => $updateId,
                            'file_name' => $file->getClientName(),
                            'file_path' => 'uploads/tickets/' . $newName,
                            'file_type' => $file->getClientMimeType(),
                            'file_size' => $file->getSize(),
                            'uploaded_by' => $techId,
                            'uploaded_by_type' => 'technician'
                        ];
                        
                        $this->attachmentModel->insert($attachmentData);
                    }
                }
            }
            
            // Commit the transaction
            $db->transCommit();
            
            return redirect()->to('/tech/tickets/view/' . $id)
                ->with('message', 'Service completed successfully');
                
        } catch (\Exception $e) {
            // Roll back the transaction in case of error
            $db->transRollback();
            
            return redirect()->back()
                ->with('error', 'Error completing service: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function updatePart($id)
    {
        $techId = session()->get('tech_id');
        
        // Verify ticket belongs to this technician
        $ticket = $this->ticketModel->where('id', $id)
                                  ->where('technician_id', $techId)
                                  ->first();
        
        if (!$ticket) {
            return redirect()->to('/tech/tickets')
                ->with('error', 'Ticket not found or not assigned to you');
        }
        
        $partId = $this->request->getPost('part_id');
        $part = $this->partModel->where('id', $partId)
                              ->where('ticket_id', $id)
                              ->first();
        
        if (!$part) {
            return redirect()->to('/tech/tickets/view/' . $id)
                ->with('error', 'Part not found');
        }
        
        $status = $this->request->getPost('part_status');
        $notes = $this->request->getPost('part_notes');
        
        $this->partModel->update($partId, [
            'status' => $status,
            'notes' => $notes
        ]);
        
        // Add update about part status change
        $this->updateModel->insert([
            'ticket_id' => $id,
            'user_id' => $techId,
            'user_type' => 'technician',
            'comment' => "Updated part {$part['part_number']} status to " . ucfirst($status) . 
                        (!empty($notes) ? "\n\nNotes: {$notes}" : "")
        ]);
        
        return redirect()->to('/tech/tickets/view/' . $id)
            ->with('message', 'Part status updated successfully');
    }
    
    public function addUpdate($id)
    {
        $techId = session()->get('tech_id');
        
        // Verify ticket belongs to this technician
        $ticket = $this->ticketModel->where('id', $id)
                                  ->where('technician_id', $techId)
                                  ->first();
        
        if (!$ticket) {
            return redirect()->to('/tech/tickets')
                ->with('error', 'Ticket not found or not assigned to you');
        }
        
        $rules = [
            'comment' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('error', 'Please enter a comment');
        }
        
        $comment = $this->request->getPost('comment');
        
        // Start a database transaction
        $db = \Config\Database::connect();
        $db->transBegin();
        
        try {
            // Add update
            $updateData = [
                'ticket_id' => $id,
                'user_id' => $techId,
                'user_type' => 'technician',
                'comment' => $comment
            ];
            
            $this->updateModel->insert($updateData);
            $updateId = $this->updateModel->getInsertID();
            
            // Handle file attachments
            $files = $this->request->getFileMultiple('attachments');
            
            if ($files) {
                foreach ($files as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move(WRITEPATH . 'uploads/tickets', $newName);
                        
                        $attachmentData = [
                            'ticket_id' => $id,
                            'update_id' => $updateId,
                            'file_name' => $file->getClientName(),
                            'file_path' => 'uploads/tickets/' . $newName,
                            'file_type' => $file->getClientMimeType(),
                            'file_size' => $file->getSize(),
                            'uploaded_by' => $techId,
                            'uploaded_by_type' => 'technician'
                        ];
                        
                        $this->attachmentModel->insert($attachmentData);
                    }
                }
            }
            
            // Commit the transaction
            $db->transCommit();
            
            return redirect()->to('/tech/tickets/view/' . $id)
                ->with('message', 'Update added successfully');
                
        } catch (\Exception $e) {
            // Roll back the transaction in case of error
            $db->transRollback();
            
            return redirect()->back()
                ->with('error', 'Error adding update: ' . $e->getMessage());
        }
    }
    
    public function attachment($id)
    {
        $techId = session()->get('tech_id');
        
        // Get the attachment
        $attachment = $this->attachmentModel->find($id);
        
        if (!$attachment) {
            return redirect()->back()
                ->with('error', 'Attachment not found');
        }
        
        // Verify the tech has access to this attachment's ticket
        $ticket = $this->ticketModel->where('id', $attachment['ticket_id'])
                                  ->where('technician_id', $techId)
                                  ->first();
        
        if (!$ticket) {
            return redirect()->to('/tech/tickets')
                ->with('error', 'You do not have permission to access this file');
        }
        
        $filePath = WRITEPATH . $attachment['file_path'];
        
        if (!file_exists($filePath)) {
            return redirect()->back()
                ->with('error', 'File not found on server');
        }
        
        $mimeType = $attachment['file_type'] ?? 'application/octet-stream';
        $fileName = $attachment['file_name'];
        
        // Set appropriate headers
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: inline; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: public, max-age=0');
        
        readfile($filePath);
        exit;
    }
}