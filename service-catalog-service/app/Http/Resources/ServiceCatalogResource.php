<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceCatalogResource extends JsonResource
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
            'data' => $this->resource,
        ];
    }
}
