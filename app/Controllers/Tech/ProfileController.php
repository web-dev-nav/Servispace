<?php

namespace App\Controllers\Tech;

use App\Controllers\BaseController;
use App\Models\TechnicianModel;

class ProfileController extends BaseController
{
    protected $technicianModel;
    
    public function __construct()
    {
        $this->technicianModel = new TechnicianModel();
    }
    
    public function index()
    {
        $techId = session()->get('tech_id');
        $technician = $this->technicianModel->find($techId);
        
        if (!$technician) {
            return redirect()->to('/tech/dashboard')
                ->with('error', 'Technician profile not found');
        }
        
        $data = [
            'title' => 'My Profile',
            'technician' => $technician
        ];
        
        return view('tech/profile/index', $data);
    }
    
    public function update()
    {
        $techId = session()->get('tech_id');
        $technician = $this->technicianModel->find($techId);
        
        if (!$technician) {
            return redirect()->to('/tech/dashboard')
                ->with('error', 'Technician profile not found');
        }
        
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'phone' => 'permit_empty|min_length[10]|max_length[20]'
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
            'phone' => $this->request->getPost('phone')
        ];
        
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
        
        $this->technicianModel->update($techId, $data);
        
        // Update session name if it was changed
        if ($technician['name'] != $data['name']) {
            session()->set('tech_name', $data['name']);
        }
        
        // Update session email if it was changed
        if ($technician['email'] != $data['email']) {
            session()->set('tech_email', $data['email']);
        }
        
        return redirect()->to('/tech/profile')
            ->with('message', 'Profile updated successfully');
    }
    
    public function changePassword()
    {
        $techId = session()->get('tech_id');
        $technician = $this->technicianModel->find($techId);
        
        if (!$technician) {
            return redirect()->to('/tech/dashboard')
                ->with('error', 'Technician profile not found');
        }
        
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        
        // Verify current password
        if (!password_verify($currentPassword, $technician['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect');
        }
        
        // Update password
        $this->technicianModel->update($techId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
        
        return redirect()->to('/tech/profile')
            ->with('message', 'Password changed successfully');
    }
}