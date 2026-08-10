<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelActivity
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $action;
    public $entity;
    public $entity_name;
    public $message;
    public $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct($user, string $action, string $entity, string $entity_name, string $message, $timestamp = null)
    {
        $this->user = $user ?? (object)['name' => 'System'];
        $this->action = $action;
        $this->entity = $entity;
        $this->entity_name = $entity_name;
        $this->message = $message;
        $this->timestamp = $timestamp ?? now()->toDateTimeString();
    }
}
