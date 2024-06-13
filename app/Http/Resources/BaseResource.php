<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    public $status;
    public $message;
    public $resource;

    /**
     * @param $status
     * @param $message
     * @param $resource
     */
    public function __construct($status, $message, $resource)
    {
        $this->status = $status;
        $this->message = $message;
        Parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
          'success' => $this->status,
          'message' => $this->message,
          'data' => $this->resource
        ];
    }
}
