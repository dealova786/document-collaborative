<?php

namespace App\Events;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class DocumentUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $documentId;
    public $title;
    public $content;

    public function __construct($documentId, $title, $content)
    {
        $this->documentId = $documentId;
        $this->title = $title;
        $this->content = $content;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('document.' . $this->documentId),
        ];
    }

    public function broadcastAs()
    {
        return 'DocumentUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'documentId' => $this->documentId,
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}
