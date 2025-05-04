<?php

namespace App\Models;

use CodeIgniter\Model;

class TechnicianModel extends Model
{
    protected $table = 'technicians';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'email', 'phone', 'tech_id', 'photo', 'is_active',
        'password', 'remember_token', 'last_login_at',
        'password_reset_token', 'password_reset_expires_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Method to get active technicians
    public function getActiveTechnicians()
    {
        return $this->where('is_active', 1)->findAll();
    }
    
    // Hash password before insert
    protected function beforeInsert(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        
        return $data;
    }
    
    // Hash password before update
    protected function beforeUpdate(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            // Only hash if it's not already hashed
            if (!password_get_info($data['data']['password'])['algo']) {
                $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            }
        }
        
        return $data;
    }
}