<?php

namespace App\Events;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CursorMoved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $documentId;
    public $userName;
    public $position;
    public $userId;
    public $color;

    public function __construct($documentId, $userName, $position)
    {
        $this->documentId = $documentId;
        $this->userName = $userName;
        $this->position = $position;
        $this->userId = auth()->id();
        $this->color = '#ef4444';
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('document.' . $this->documentId),
        ];
    }

    public function broadcastAs()
    {
        return 'CursorMoved';
    }
}