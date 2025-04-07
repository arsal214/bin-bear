<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ActionsTrait
{

    public function statusBadge(string $status): string
    {
        $color = match ($status) {
            'Active', 'Approved' => 'success',
            'DeActive', 'Rejected' => 'dark',
            'cancelled','Cancelled' => 'danger',
            'InComplete' => 'warning',
            'Pending' => 'info',
        };
        return "<span class='badge bg-{$color} text-capitalize'>{$status}</span>";
    }

    public function dateFormat($date)
    {
        return $date ? $date->format('d M Y') : 'N/A';
    }

}
