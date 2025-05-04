<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketUpdateModel extends Model
{
    protected $table = 'ticket_updates';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'ticket_id', 'user_id', 'user_type', 'comment', 'is_private'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
    
    // Get updates for a ticket
    public function getTicketUpdates($ticketId, $includePrivate = true)
    {
        $query = $this->where('ticket_id', $ticketId);
        
        if (!$includePrivate) {
            $query->where('is_private', 0);
        }
        
        return $query->orderBy('created_at', 'ASC')->findAll();
    }
}