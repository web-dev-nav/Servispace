<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TechnicianModel;

class TechnicianController extends BaseController
{
    protected $technicianModel;
    
    public function __construct()
    {
        $this->technicianModel = new TechnicianModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Technicians',
            'technicians' => $this->technicianModel->findAll()
        ];
        
        return view('admin/technicians/index', $data);
    }
    
    public function create()
    {
        $data = [
            'title' => 'Add Technician'
        ];
        
        return view('admin/technicians/create', $data);
    }
    
    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[technicians.email]',
            'phone' => 'permit_empty|min_length[10]|max_length[20]',
            'tech_id' => 'permit_empty|max_length[20]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Handle file upload if a photo was submitted
        $photo = '';
        $file = $this->request->getFile('photo');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/technicians', $newName);
            $photo = 'uploads/technicians/' . $newName;
        }
        
        // Generate random password if none provided
        $password = $this->request->getPost('password');
        if (empty($password)) {
            $password = bin2hex(random_bytes(4)); // 8 character random password
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'tech_id' => $this->request->getPost('tech_id'),
            'photo' => $photo,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];
        
        $this->technicianModel->insert($data);
        
        $message = 'Technician added successfully';
        if (empty($this->request->getPost('password'))) {
            $message .= '. Generated password: ' . $password . ' (Please share this with the technician)';
        }
        
        return redirect()->to('/admin/technicians')
            ->with('message', $message);
    }
    
    public function edit($id)
    {
        $technician = $this->technicianModel->find($id);
        
        if (!$technician) {
            return redirect()->to('/admin/technicians')
                ->with('error', 'Technician not found');
        }
        
        $data = [
            'title' => 'Edit Technician',
            'technician' => $technician
        ];
        
        return view('admin/technicians/edit', $data);
    }
    
    public function update($id)
    {
        $technician = $this->technicianModel->find($id);
        
        if (!$technician) {
            return redirect()->to('/admin/technicians')
                ->with('error', 'Technician not found');
        }
        
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'phone' => 'permit_empty|min_length[10]|max_length[20]',
            'tech_id' => 'permit_empty|max_length[20]'
        ];
        
        // Only check email uniqueness if it's changed
        if ($technician['email'] != $this->request->getPost('email')) {
            $rules['email'] .= '|is_unique[technicians.email]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'tech_id' => $this->request->getPost('tech_id'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];
        
        // Explicitly handle password
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        // Handle file upload if a new photo was submitted
        $file = $this->request->getFile('photo');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old photo if exists
            if (!empty($technician['photo']) && file_exists(WRITEPATH . $technician['photo'])) {
                unlink(WRITEPATH . $technician['photo']);
            }
            
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/technicians', $newName);
            $data['photo'] = 'uploads/technicians/' . $newName;
        }
        
        // Debug what's being sent to the database
        log_message('debug', 'Updating technician with data: ' . json_encode($data));
        
        $this->technicianModel->update($id, $data);
        
        return redirect()->to('/admin/technicians')
            ->with('message', 'Technician updated successfully');
    }
    
    public function delete($id)
    {
        $technician = $this->technicianModel->find($id);
        
        if (!$technician) {
            return redirect()->to('/admin/technicians')
                ->with('error', 'Technician not found');
        }
        
        // Delete photo if exists
        if (!empty($technician['photo']) && file_exists(WRITEPATH . $technician['photo'])) {
            unlink(WRITEPATH . $technician['photo']);
        }
        
        $this->technicianModel->delete($id);
        
        return redirect()->to('/admin/technicians')
            ->with('message', 'Technician deleted successfully');
    }
    
    public function profile($id)
    {
        $technician = $this->technicianModel->find($id);
        
        if (!$technician) {
            return redirect()->to('/admin/technicians')
                ->with('error', 'Technician not found');
        }
        
        $data = [
            'title' => 'Technician Profile',
            'technician' => $technician
        ];
        
        return view('admin/technicians/profile', $data);
    }

  
}