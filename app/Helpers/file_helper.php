<?php

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
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
        } elseif (strpos($mimeType, 'application/msword') !== false || strpos($mimeType, 'application/vnd.openxmlformats-officedocument.wordprocessingml') !== false) {
            $icon = 'fas fa-file-word';
        } elseif (strpos($mimeType, 'application/vnd.ms-excel') !== false || strpos($mimeType, 'application/vnd.openxmlformats-officedocument.spreadsheetml') !== false) {
            $icon = 'fas fa-file-excel';
        } elseif (strpos($mimeType, 'application/vnd.ms-powerpoint') !== false || strpos($mimeType, 'application/vnd.openxmlformats-officedocument.presentationml') !== false) {
            $icon = 'fas fa-file-powerpoint';
        } elseif (strpos($mimeType, 'application/zip') !== false || strpos($mimeType, 'application/x-rar') !== false) {
            $icon = 'fas fa-file-archive';
        }
        
        return $icon;
    }
}