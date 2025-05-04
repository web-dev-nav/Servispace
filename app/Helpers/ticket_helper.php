<?php

if (!function_exists('getStatusBadgeClass')) {
    function getStatusBadgeClass($status) {
        switch($status) {
            case 'open':
                return 'warning';
            case 'assigned':
                return 'info';
            case 'scheduled':
                return 'primary';
            case 'in_progress':
                return 'info';
            case 'resolved':
                return 'success';
            case 'closed':
                return 'secondary';
            case 'cancelled':
                return 'danger';
            case 'partially_completed':
                return 'warning';
            default:
                return 'secondary';
        }
    }
}

if (!function_exists('getPriorityBadgeClass')) {
    function getPriorityBadgeClass($priority) {
        switch($priority) {
            case 'low':
                return 'secondary';
            case 'medium':
                return 'info';
            case 'high':
                return 'warning';
            case 'urgent':
                return 'danger';
            default:
                return 'secondary';
        }
    }
}

if (!function_exists('formatStatus')) {
    function formatStatus($status) {
        return ucfirst(str_replace('_', ' ', $status));
    }
}

if (!function_exists('getPartStatusClass')) {
    function getPartStatusClass($status) {
        switch($status) {
            case 'unused':
                return 'secondary';
            case 'installed':
                return 'success';
            case 'defective':
                return 'danger';
            case 'wrong_part':
                return 'warning';
            case 'returned':
                return 'info';
            default:
                return 'secondary';
        }
    }
}

if (!function_exists('formatPartStatus')) {
    function formatPartStatus($status) {
        return ucfirst(str_replace('_', ' ', $status));
    }
}

if (!function_exists('getFileIcon')) {
    function getFileIcon($mimeType) {
        $icon = 'fas fa-file';
        
        if (strpos($mimeType, 'image/') !== false) {
            $icon = 'fas fa-file-image';
        } elseif (strpos($mimeType, 'text/') !== false) {
            $icon = 'fas fa-file-alt';
        } elseif (strpos($mimeType, 'application/pdf') !== false) {
            $icon = 'fas fa-file-pdf';
        } elseif (strpos($mimeType, 'application/msword') !== false || 
                 strpos($mimeType, 'application/vnd.openxmlformats-officedocument.wordprocessingml') !== false) {
            $icon = 'fas fa-file-word';
        } elseif (strpos($mimeType, 'application/vnd.ms-excel') !== false || 
                 strpos($mimeType, 'application/vnd.openxmlformats-officedocument.spreadsheetml') !== false) {
            $icon = 'fas fa-file-excel';
        } elseif (strpos($mimeType, 'application/vnd.ms-powerpoint') !== false || 
                 strpos($mimeType, 'application/vnd.openxmlformats-officedocument.presentationml') !== false) {
            $icon = 'fas fa-file-powerpoint';
        } elseif (strpos($mimeType, 'application/zip') !== false || strpos($mimeType, 'application/x-rar') !== false) {
            $icon = 'fas fa-file-archive';
        }
        
        return $icon;
    }
}

// Format part status
if (!function_exists('formatPartStatus')) {
    function formatPartStatus($status) {
        $statuses = [
            'unused' => 'Unused',
            'installed' => 'Installed',
            'defective' => 'Defective',
            'wrong_part' => 'Wrong Part',
            'returned' => 'Returned'
        ];
        
        return $statuses[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}
if (!function_exists('getPartStatusBadgeClass')) {
    function getPartStatusBadgeClass($status)
    {
        switch ($status) {
            case 'unused':
                return 'secondary';
            case 'used':
                return 'success';
            case 'ordered':
                return 'info';
            case 'backordered':
                return 'warning';
            default:
                return 'secondary';
        }
    }
}
