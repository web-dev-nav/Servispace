<?php

namespace App\Controllers\Tech;

use App\Controllers\BaseController;
use App\Models\TicketModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $techId = session()->get('tech_id');
        $ticketModel = new TicketModel();
        
        // Try to get ticket counts by status for this technician
        try {
            $tickets = $ticketModel->where('technician_id', $techId)->findAll();
            
            // Initialize counters
            $openCount = 0;
            $inProgressCount = 0;
            $resolvedCount = 0;
            $closedCount = 0;
            $totalCount = count($tickets);
            
            // Count tickets by status
            foreach ($tickets as $ticket) {
                switch ($ticket['status']) {
                    case 'open':
                    case 'assigned':
                        $openCount++;
                        break;
                    case 'in_progress':
                        $inProgressCount++;
                        break;
                    case 'resolved':
                        $resolvedCount++;
                        break;
                    case 'closed':
                        $closedCount++;
                        break;
                }
            }
            
            // Get recent tickets
            $recentTickets = $ticketModel->select('tickets.*, organizations.name as organization_name')
                ->join('organizations', 'organizations.id = tickets.organization_id', 'left')
                ->where('tickets.technician_id', $techId)
                ->orderBy('tickets.updated_at', 'DESC')
                ->limit(5)
                ->find();
        } catch (\Exception $e) {
            // If there's an error (e.g., tickets table doesn't exist yet)
            $openCount = 0;
            $inProgressCount = 0;
            $resolvedCount = 0;
            $closedCount = 0;
            $totalCount = 0;
            $recentTickets = [];
        }
        
        $data = [
            'title' => 'Dashboard',
            'total_tickets' => $totalCount,
            'open_tickets' => $openCount,
            'in_progress_tickets' => $inProgressCount,
            'resolved_tickets' => $resolvedCount,
            'closed_tickets' => $closedCount,
            'recent_tickets' => $recentTickets
        ];
        
        return view('tech/dashboard', $data);
    }
}