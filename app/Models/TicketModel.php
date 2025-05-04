<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 'description', 'organization_id', 'technician_id', 
        'status', 'priority', 'created_by', 'resolved_at', 'closed_at',
        'customer_id', 'assigned_at','appointment_date', 'appointment_time', 'appointment_notes', 'estimated_duration',
        'service_started_at', 'service_completed_at', 'completion_code', 'customer_signature'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Get ticket with organization and technician details
    public function getTicketWithDetails($id)
    {
        $builder = $this->db->table('tickets t');
        $builder->select('t.*, o.name as organization_name, c.name as customer_name, tech.name as technician_name');
        $builder->join('organizations o', 'o.id = t.organization_id', 'left');
        $builder->join('customers c', 'c.id = t.customer_id', 'left');
        $builder->join('technicians tech', 'tech.id = t.technician_id', 'left');
        $builder->where('t.id', $id);
        
        $query = $builder->get();
        
        return $query->getRowArray();
    }
    // Get all tickets with organization and technician details
    public function getAllTicketsWithDetails()
    {
        return $this->select('tickets.*, organizations.name as organization_name, technicians.name as technician_name')
            ->join('organizations', 'organizations.id = tickets.organization_id', 'left')
            ->join('technicians', 'technicians.id = tickets.technician_id', 'left')
            ->orderBy('tickets.created_at', 'DESC')
            ->findAll();
    }
    
    // Get tickets by status
    public function getTicketsByStatus($status)
    {
        return $this->select('tickets.*, organizations.name as organization_name, technicians.name as technician_name')
            ->join('organizations', 'organizations.id = tickets.organization_id', 'left')
            ->join('technicians', 'technicians.id = tickets.technician_id', 'left')
            ->where('tickets.status', $status)
            ->orderBy('tickets.created_at', 'DESC')
            ->findAll();
    }
    
    // Get tickets by organization
    public function getTicketsByOrganization($organizationId)
    {
        return $this->select('tickets.*, organizations.name as organization_name, technicians.name as technician_name')
            ->join('organizations', 'organizations.id = tickets.organization_id', 'left')
            ->join('technicians', 'technicians.id = tickets.technician_id', 'left')
            ->where('tickets.organization_id', $organizationId)
            ->orderBy('tickets.created_at', 'DESC')
            ->findAll();
    }
    
    // Get tickets by technician
    public function getTicketsByTechnician($technicianId)
    {
        return $this->select('tickets.*, organizations.name as organization_name, technicians.name as technician_name')
            ->join('organizations', 'organizations.id = tickets.organization_id', 'left')
            ->join('technicians', 'technicians.id = tickets.technician_id', 'left')
            ->where('tickets.technician_id', $technicianId)
            ->orderBy('tickets.created_at', 'DESC')
            ->findAll();
    }
    
    // Get unassigned tickets
    public function getUnassignedTickets()
    {
        return $this->select('tickets.*, organizations.name as organization_name')
            ->join('organizations', 'organizations.id = tickets.organization_id', 'left')
            ->where('tickets.technician_id IS NULL')
            ->where('tickets.status', 'open')
            ->orderBy('tickets.created_at', 'ASC')
            ->findAll();
    }
    
    // Dashboard statistics
    public function getTicketStats()
    {
        $db = \Config\Database::connect();
        
        // Count tickets by status
        $statusCounts = $db->table('tickets')
            ->select('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->getResultArray();
        
        // Format into an easy-to-use array
        $stats = [
            'total' => 0,
            'open' => 0,
            'assigned' => 0,
            'in_progress' => 0,
            'resolved' => 0,
            'closed' => 0,
            'cancelled' => 0
        ];
        
        foreach ($statusCounts as $row) {
            $stats[$row['status']] = $row['count'];
            $stats['total'] += $row['count'];
        }
        
        return $stats;
    }
}