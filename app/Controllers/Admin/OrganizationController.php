<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrganizationModel;
use App\Models\OrganizationDocumentModel;

class OrganizationController extends BaseController
{
    protected $organizationModel;
    protected $documentModel;
    
    public function __construct()
    {
        $this->organizationModel = new OrganizationModel();
        $this->documentModel = new OrganizationDocumentModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Organizations',
            'organizations' => $this->organizationModel->findAll()
        ];
        
        return view('admin/organizations/index', $data);
    }
    
    public function create()
    {
        $data = [
            'title' => 'Create Organization'
        ];
        
        return view('admin/organizations/create', $data);
    }
    
    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'contact_email' => 'permit_empty|valid_email',
            'support_email' => 'permit_empty|valid_email',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'contact_name' => $this->request->getPost('contact_name'),
            'contact_email' => $this->request->getPost('contact_email'),
            'contact_phone' => $this->request->getPost('contact_phone'),
            'address' => $this->request->getPost('address'),
            'support_email' => $this->request->getPost('support_email'),
            'support_phone' => $this->request->getPost('support_phone'),
            'description' => $this->request->getPost('description'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];
        
        $this->organizationModel->insert($data);
        
        return redirect()->to('/admin/organizations')
            ->with('message', 'Organization created successfully');
    }
    
    public function edit($id)
    {
        $organization = $this->organizationModel->find($id);
        
        if (!$organization) {
            return redirect()->to('/admin/organizations')
                ->with('error', 'Organization not found');
        }
        
        $data = [
            'title' => 'Edit Organization',
            'organization' => $organization,
            'documents' => $this->documentModel->getOrganizationDocuments($id)
        ];
        
        return view('admin/organizations/edit', $data);
    }
    
    public function update($id)
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'contact_email' => 'permit_empty|valid_email',
            'support_email' => 'permit_empty|valid_email',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'contact_name' => $this->request->getPost('contact_name'),
            'contact_email' => $this->request->getPost('contact_email'),
            'contact_phone' => $this->request->getPost('contact_phone'),
            'address' => $this->request->getPost('address'),
            'support_email' => $this->request->getPost('support_email'),
            'support_phone' => $this->request->getPost('support_phone'),
            'description' => $this->request->getPost('description'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];
        
        $this->organizationModel->update($id, $data);
        
        return redirect()->to('/admin/organizations')
            ->with('message', 'Organization updated successfully');
    }
    
    public function delete($id)
    {
        $organization = $this->organizationModel->find($id);
        
        if (!$organization) {
            return redirect()->to('/admin/organizations')
                ->with('error', 'Organization not found');
        }
        
        $this->organizationModel->delete($id);
        
        return redirect()->to('/admin/organizations')
            ->with('message', 'Organization deleted successfully');
    }
    
    public function uploadDocument($id)
    {
        $organization = $this->organizationModel->find($id);
        
        if (!$organization) {
            return redirect()->to('/admin/organizations')
                ->with('error', 'Organization not found');
        }
        
        // Check if a file was uploaded
        if (!$this->request->getFile('document')) {
            return redirect()->back()->with('error', 'No file selected');
        }
        
        $file = $this->request->getFile('document');
        
        if (!$file->isValid() || $file->hasMoved()) {
            return redirect()->back()->with('error', 'Invalid file upload');
        }
        
        // Create directory if it doesn't exist
        $uploadPath = WRITEPATH . 'uploads/documents';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        try {
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            
            // Use direct query builder for more control
            $db = \Config\Database::connect();
            $builder = $db->table('organization_documents');
            
            $insertData = [
                'organization_id' => $id,
                'file_name' => $file->getClientName(),
                'file_path' => 'uploads/documents/' . $newName,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'description' => $this->request->getPost('description') ?? '',
                'uploaded_by' => session()->get('admin_id') ?? 1,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Explicitly list all columns to avoid SQL syntax errors
            $builder->set($insertData);
            $result = $builder->insert();
            
            if ($result) {
                return redirect()->to('/admin/organizations/edit/' . $id)
                    ->with('message', 'Document uploaded successfully');
            } else {
                throw new \Exception('Failed to insert document record');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function deleteDocument($id, $docId)
    {
        $document = $this->documentModel->find($docId);
        
        if (!$document || $document['organization_id'] != $id) {
            return redirect()->back()->with('error', 'Document not found');
        }
        
        // Delete the file
        if (file_exists(WRITEPATH . $document['file_path'])) {
            unlink(WRITEPATH . $document['file_path']);
        }
        
        $this->documentModel->delete($docId);
        
        return redirect()->to('/admin/organizations/edit/' . $id)
            ->with('message', 'Document deleted successfully');
    }

    public function viewDocument($id, $docId)
    {
        $document = $this->documentModel->find($docId);
        
        if (!$document || $document['organization_id'] != $id) {
            return redirect()->back()->with('error', 'Document not found');
        }
        
        $filePath = WRITEPATH . $document['file_path'];
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found on server');
        }
        
        $mimeType = $document['file_type'];
        $fileName = $document['file_name'];
        
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
}