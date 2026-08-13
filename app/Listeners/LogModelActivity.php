<?php

namespace App\Listeners;

use App\Events\ModelActivity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;

class LogModelActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ModelActivity $event): void
    {
        try {
            if (app()->runningInConsole()) {
                return;
            }

            $userName = $event->user ? ($event->user->name ?? 'User') : 'Guest';
            $activity = [
                'user_id' => $userName,
                'action' => $event->action,
                'entity' => $event->entity,
                'entity_name' => $event->entity_name ?? '-',
                'message' => $event->message ?? '-',
                'timestamp' => $event->timestamp ?? now()->toIso8601String(),
            ];

            $dirPath = public_path('data');
            if (!File::exists($dirPath)) {
                File::makeDirectory($dirPath, 0755, true, true);
            }

            $filePath = public_path('data/userActivities.json');
            $activities = [];

            if (File::exists($filePath)) {
                $activities = json_decode(File::get($filePath), true);
                if (!is_array($activities)) {
                    $activities = [];
                }
            }

            $activities[] = $activity;

            File::put($filePath, json_encode($activities, JSON_PRETTY_PRINT));
        } catch (\Throwable $e) {
            // Silently ignore activity log failures so main DB operations never fail
        }
    }
}
