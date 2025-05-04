<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'organization_id', 'name', 'email', 'phone', 'address'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    public function getCustomersForOrganization($organizationId)
    {
        return $this->where('organization_id', $organizationId)->findAll();
    }
}