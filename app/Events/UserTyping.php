<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $documentId;
    public $userName;

    public function __construct($documentId, $userName)
    {
        $this->documentId = $documentId;
        $this->userName = $userName;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('document.' . $this->documentId),
        ];
    }

    public function broadcastAs()
    {
        return 'UserTyping';
    }
}