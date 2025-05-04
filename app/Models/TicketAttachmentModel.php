<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketAttachmentModel extends Model
{
    protected $table = 'ticket_attachments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'ticket_id', 'update_id', 'file_name', 'file_path', 
        'file_type', 'file_size', 'uploaded_by', 'uploaded_by_type'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
    
    // Get ticket attachments
    public function getTicketAttachments($ticketId)
    {
        return $this->where('ticket_id', $ticketId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
    
    // Get attachments for a specific update
    public function getUpdateAttachments($updateId)
    {
        return $this->where('update_id', $updateId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}