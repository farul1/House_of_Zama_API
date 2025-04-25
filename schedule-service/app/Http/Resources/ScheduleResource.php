<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    protected $status;
    protected $message;

    public function __construct($resource, $status = 'Success', $message = '')
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
    }

    public function toArray($request)
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => [
                'id' => $this->id,
                'order_id' => $this->order_id,
                'client_id' => $this->client_id,
                'fotografer' => $this->fotografer,
                'tempat' => $this->tempat,
                'waktu' => $this->waktu,
                'client_data' => $this->client_data ?? null,
            ]
        ];
    }

    public function with($request)
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
        ];
    }
}
