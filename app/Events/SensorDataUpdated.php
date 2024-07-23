<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SensorDataUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sensorId;
    public $data;

    /**
     * Create a new event instance.
     *
     * @param string $sensorId
     * @param array $data
     */
    public function __construct($sensorId, $data)
    {
        $this->sensorId = $sensorId;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('sensor-data');
    }
}
