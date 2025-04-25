<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PhotographyResource extends JsonResource
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
                'schedule_id' => $this->schedule_id,
                'foto' => $this->foto,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
        ];
    }

    public function with($request)
    {
        return [
            'meta' => [
                'api_version' => '1.0',
                'timestamp' => now(),
            ],
        ];
    }
}
