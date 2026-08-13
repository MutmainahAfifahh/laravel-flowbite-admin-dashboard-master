<?php

namespace Tests\Feature;

use App\Events\ModelActivity;
use App\Listeners\LogModelActivity;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ActivityLoggingTest extends TestCase
{
    public function test_console_runs_do_not_write_activity_log_file(): void
    {
        $filePath = public_path('data/userActivities.json');

        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        $listener = new LogModelActivity();
        $listener->handle(new ModelActivity(
            User::factory()->make(),
            'create',
            'Category',
            'Test Category',
            'Test message',
            now(),
        ));

        $this->assertFalse(File::exists($filePath));
    }
}
