<?php

if (!function_exists('getStatusColor')) {
    function getStatusColor($status)
    {
        return match (strtolower($status)) {
            'completed' => 'success',
            'planning' => 'warning',
            // 'cancelled' => 'danger',
            'active' => 'primary',
            // 'in progress' => 'info',
            default => 'secondary',
        };
    }
}
