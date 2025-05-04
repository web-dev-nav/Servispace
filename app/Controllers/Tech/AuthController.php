<?php

namespace App\Controllers\Tech;

use App\Controllers\BaseController;
use App\Models\TechnicianModel;

class AuthController extends BaseController
{
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('tech_logged_in')) {
            return redirect()->to('/tech/dashboard');
        }
        
        return view('tech/auth/login');
    }
    
    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $technicianModel = new TechnicianModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $technician = $technicianModel->where('email', $email)->first();
        
        if ($technician && password_verify($password, $technician['password'])) {
            // Check if technician is active
            if (!$technician['is_active']) {
                return redirect()->back()->withInput()->with('error', 'Your account is inactive. Please contact administrator.');
            }
            
            // Update last login time
            $technicianModel->update($technician['id'], ['last_login_at' => date('Y-m-d H:i:s')]);
            
            // Set session variables
            session()->set([
                'tech_id' => $technician['id'],
                'tech_name' => $technician['name'],
                'tech_email' => $technician['email'],
                'tech_logged_in' => true
            ]);
            
            return redirect()->to('/tech/dashboard');
        }
        
        return redirect()->back()->withInput()->with('error', 'Invalid login credentials');
    }
    
    public function logout()
    {
        session()->remove(['tech_id', 'tech_name', 'tech_email', 'tech_logged_in']);
        return redirect()->to('/tech/login');
    }
    
    public function forgotPassword()
    {
        return view('tech/auth/forgot_password');
    }
    
    public function sendResetLink()
    {
        $rules = [
            'email' => 'required|valid_email'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $email = $this->request->getPost('email');
        $technicianModel = new TechnicianModel();
        $technician = $technicianModel->where('email', $email)->first();
        
        if (!$technician) {
            return redirect()->back()->withInput()->with('error', 'Email not found');
        }
        
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $technicianModel->update($technician['id'], [
            'password_reset_token' => $token,
            'password_reset_expires_at' => $expiry
        ]);
        
        // Create reset link
        $resetLink = site_url("tech/reset-password/{$token}");
        
        // Send email with reset link
        $email = \Config\Services::email();
        $email->setTo($technician['email']);
        $email->setSubject('Reset Your Servispace Password');
        
        $message = view('emails/tech_reset_password', [
            'technician' => $technician,
            'resetLink' => $resetLink
        ]);
        
        $email->setMessage($message);
        
        if ($email->send()) {
            return redirect()->back()->with('message', 'Password reset link has been sent to your email address.');
        } else {
            // Add more detailed error logging
            $debugInfo = $email->printDebugger(['headers', 'subject', 'body']);
            log_message('error', 'Email sending failed: ' . print_r($debugInfo, true));
            
            // For development, you might want to see the error
            return redirect()->back()->withInput()
                ->with('error', 'Failed to send reset email: ' . json_encode($debugInfo));
        }
    }
    
    public function resetPassword($token)
    {
        $technicianModel = new TechnicianModel();
        $technician = $technicianModel->where('password_reset_token', $token)
            ->where('password_reset_expires_at >', date('Y-m-d H:i:s'))
            ->first();
        
        if (!$technician) {
            return redirect()->to('/tech/forgot-password')
                ->with('error', 'Invalid or expired password reset token');
        }
        
        $data = [
            'token' => $token
        ];
        
        return view('tech/auth/reset_password', $data);
    }
    
    public function updatePassword()
    {
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }
        
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        
        $technicianModel = new TechnicianModel();
        $technician = $technicianModel->where('password_reset_token', $token)
            ->where('password_reset_expires_at >', date('Y-m-d H:i:s'))
            ->first();
        
        if (!$technician) {
            return redirect()->to('/tech/forgot-password')
                ->with('error', 'Invalid or expired password reset token');
        }
        
        $technicianModel->update($technician['id'], [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'password_reset_token' => null,
            'password_reset_expires_at' => null
        ]);
        
        return redirect()->to('/tech/login')
            ->with('message', 'Password has been reset successfully. You can now login with your new password.');
    }
}