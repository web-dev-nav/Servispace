<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class AuthController extends BaseController
{
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/admin/dashboard');
        }
        
        return view('admin/login');
    }
    
    public function attemptLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $adminModel = new AdminModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        // Check if input is email or username
        $admin = $adminModel->where('username', $username)
                            ->orWhere('email', $username)
                            ->first();
        
        if ($admin && password_verify($password, $admin['password'])) {
            // Update last login time
            $adminModel->update($admin['id'], ['last_login' => date('Y-m-d H:i:s')]);
            
            // Set session variables
            session()->set([
                'admin_id' => $admin['id'],
                'admin_username' => $admin['username'],
                'admin_name' => $admin['full_name'],
                'admin_logged_in' => true
            ]);
            
            return redirect()->to('/admin/dashboard');
        }
        
        return redirect()->back()->withInput()->with('error', 'Invalid login credentials');
    }
        
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login');
    }
}