<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class ImageManipulationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "created-at" => $this->created_at,
            "type" => $this->type,
            "original_name" => $this->original_name,
            "original_path" => URL::to($this->original_path),
            "output_name" => $this->output_name,
            "output_path" => URL::to($this->output_path),
            "id" => $this->id
        ];
    }
}
