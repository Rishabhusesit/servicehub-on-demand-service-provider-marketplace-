<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlace implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $order_place;
    public $chanelName;
    public $eventName;
    public $status_message;

    public function __construct($order_place, $eventName,$status_message)
    {       
            $this->order_place    = $order_place;
            $this->chanelName     = "order_place-".$order_place->area_id;
            $this->eventName      = $eventName;
            $this->status_message  = $status_message;

    }

    public function broadcastOn()
    {
        return new PrivateChannel($this->chanelName);
    }

    public function broadcastAs()
    {
        return $this->eventName;
    }
}
