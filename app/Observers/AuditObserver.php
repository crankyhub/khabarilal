<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    public function created($model)
    {
        $this->logAction($model, 'created');
    }

    public function updated($model)
    {
        $this->logAction($model, 'updated');
    }

    public function deleted($model)
    {
        $this->logAction($model, 'deleted');
    }

    protected function logAction($model, $action)
    {
        if (!Auth::check()) return;

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'payload' => $model->getChanges(),
            'ip_address' => request()->ip(),
        ]);
    }
}
