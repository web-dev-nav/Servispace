<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if admin is logged in
        if (!$session->has('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }
        
        // Additional permission checks can be added here
        // For example, restrict access to certain controllers based on admin roles
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here
    }
}