<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrganizationModel;
use App\Models\TechnicianModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'total_tickets' => 0,
            'open_tickets' => 0,
            'assigned_tickets' => 0,
            'in_progress_tickets' => 0,
            'resolved_tickets' => 0,
            'completed_tickets' => 0,
            'total_organizations' => 0,
            'total_technicians' => 0,
            'recent_tickets' => []
        ];
        
        // Get organization counts if table exists
        try {
            $orgModel = new OrganizationModel();
            $data['total_organizations'] = $orgModel->countAllResults();
        } catch (\Exception $e) {
            // Table doesn't exist or other error
            $data['total_organizations'] = 0;
        }
        
        // Get technician counts if table exists
        try {
            $techModel = new TechnicianModel();
            $data['total_technicians'] = $techModel->countAllResults();
        } catch (\Exception $e) {
            // Table doesn't exist or other error
            $data['total_technicians'] = 0;
        }
        
        // Get ticket counts if table exists
        try {
            if (class_exists('\App\Models\TicketModel')) {
                $ticketModel = new \App\Models\TicketModel();
                
                // Check if the table exists first
                $db = \Config\Database::connect();
                $tables = $db->listTables();
                
                if (in_array('tickets', $tables)) {
                    $data['total_tickets'] = $ticketModel->countAllResults();
                    $data['open_tickets'] = $ticketModel->where('status', 'open')->countAllResults();
                    $data['assigned_tickets'] = $ticketModel->where('status', 'assigned')->countAllResults();
                    $data['in_progress_tickets'] = $ticketModel->where('status', 'in_progress')->countAllResults();
                    $data['resolved_tickets'] = $ticketModel->where('status', 'resolved')->countAllResults();
                    
                    // Get recent tickets
                    $data['recent_tickets'] = $ticketModel->select('tickets.*, organizations.name as organization_name, technicians.name as technician_name')
                        ->join('organizations', 'organizations.id = tickets.organization_id', 'left')
                        ->join('technicians', 'technicians.id = tickets.technician_id', 'left')
                        ->orderBy('tickets.created_at', 'DESC')
                        ->limit(5)
                        ->find();
                }
            }
        } catch (\Exception $e) {
            // Table doesn't exist or other error, keep default values
        }
        
        return view('admin/dashboard', $data);
    }
}