<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HealthController extends Controller
{
    public function index()
    {
        $diskTotal = disk_total_space(base_path());
        $diskFree = disk_free_space(base_path());
        $diskUsed = $diskTotal - $diskFree;
        
        $stats = [
            'disk_usage' => round(($diskUsed / $diskTotal) * 100, 1),
            'disk_free' => round($diskFree / (1024 * 1024 * 1024), 2), // GB
            'db_connection' => DB::connection()->getDatabaseName() ? 'Connected' : 'Error',
            'env' => config('app.env'),
            'debug' => config('app.debug') ? 'Enabled' : 'Disabled',
            'laravel_version' => app()->version(),
        ];

        return view('admin.health.index', compact('stats'));
    }
}
