<?php

namespace App\Traits;

use App\Events\ModelActivity;

trait LogsActivity
{
    /**
     * Boot the trait and register Eloquent event listeners.
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logActivity($model, 'create', 'created');
        });

        static::updated(function ($model) {
            static::logActivity($model, 'update', 'updated');
        });

        static::deleted(function ($model) {
            static::logActivity($model, 'delete', 'deleted');
        });
    }

    /**
     * Log the activity for the given model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $action The action identifier (e.g., 'create', 'update', 'delete').
     * @param string $actionPastTense The human-readable action (e.g., 'created', 'updated', 'deleted').
     */
    protected static function logActivity($model, $action, $actionPastTense)
    {
        try {
            if (auth()->check()) {
                $entity = class_basename($model);
                
                // Try to derive a meaningful name for the entity
                $entityName = $model->name ?? $model->title ?? $model->id ?? 'Unknown';
                
                // For StockTransaction, the 'name' might be the product name or tracking code
                if ($model instanceof \App\Models\StockTransaction) {
                    $entityName = 'Transaction #' . $model->id . ' (' . ($model->product->name ?? 'Product') . ')';
                }
                
                $message = "{$entity} '{$entityName}' has been {$actionPastTense} successfully.";

                event(new ModelActivity(
                    auth()->user(),
                    $action,
                    $entity,
                    (string) $entityName,
                    $message,
                    now()
                ));
            }
        } catch (\Throwable $e) {
            // Silently fail if logging throws an error so main DB transaction completes.
        }
    }
}
