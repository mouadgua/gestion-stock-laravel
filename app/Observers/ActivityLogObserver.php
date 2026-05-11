<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogObserver
{
    public function created($model): void
    {
        $this->log('created', $model);
    }

    public function updated($model): void
    {
        $this->log('updated', $model);
    }

    public function deleted($model): void
    {
        $this->log('deleted', $model);
    }

    private function log(string $action, $model): void
    {
        try {
            $user = Auth::user();
            $modelName = class_basename($model);
            
            ActivityLog::create([
                'user_id' => $user?->id,
                'action' => $action,
                'model_type' => $modelName,
                'model_id' => $model->getKey(),
                'description' => $this->getDescription($action, $modelName, $model),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Don't break the app if logging fails
        }
    }

    private function getDescription(string $action, string $modelName, $model): string
    {
        $name = '';
        if (method_exists($model, 'getDescriptionForLog')) {
            $name = $model->getDescriptionForLog();
        } elseif (isset($model->name)) {
            $name = $model->name;
        } elseif (isset($model->nom_produit)) {
            $name = $model->nom_produit;
        } elseif (isset($model->id_commande)) {
            $name = "#{$model->id_commande}";
        } else {
            $name = "#{$model->getKey()}";
        }

        $actionLabels = [
            'created' => 'création',
            'updated' => 'modification',
            'deleted' => 'suppression',
        ];

        return "{$actionLabels[$action]} de {$modelName} : {$name}";
    }
}