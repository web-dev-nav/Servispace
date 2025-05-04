<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketPartModel extends Model
{
    protected $table = 'ticket_parts';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'ticket_id', 'part_number', 'description', 'quantity', 
        'status', 'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    public function getPartsForTicket($ticketId)
    {
        return $this->where('ticket_id', $ticketId)->findAll();
    }
}