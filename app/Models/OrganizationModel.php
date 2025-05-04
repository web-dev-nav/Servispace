<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganizationModel extends Model
{
    protected $table = 'organizations';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'contact_name', 'contact_email', 'contact_phone', 
        'address', 'support_email', 'support_phone', 'description', 'is_active'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Method to get active organizations
    public function getActiveOrganizations()
    {
        return $this->where('is_active', 1)->findAll();
    }
}