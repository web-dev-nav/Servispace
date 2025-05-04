<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganizationDocumentModel extends Model
{
    protected $table = 'organization_documents';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'organization_id', 'file_name', 'file_path', 'file_type', 
        'file_size', 'description', 'uploaded_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;
    
    // Get documents for an organization
    public function getOrganizationDocuments($organizationId)
    {
        return $this->where('organization_id', $organizationId)->findAll();
    }
}