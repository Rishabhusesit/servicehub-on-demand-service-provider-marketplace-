<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatus implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $order_status;
    public $chanelName;
    public $eventName;
    public $status_message;

    public function __construct($order_status, $eventName,$status_message)
    {

        if($eventName == "user-message"){
            $this->chanelName     = "order_status-".$order_status->user_id;
            $this->eventName      = $eventName;
            $this->status_message = $status_message;

        }elseif($eventName == "provider-message"){
            $this->chanelName     = "order_status-".$order_status->provider_id;
            $this->eventName      = $eventName;
            $this->status_message = $status_message;

        }

     
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
