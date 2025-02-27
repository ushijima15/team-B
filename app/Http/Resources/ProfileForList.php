<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GorillerForList as GorillerForListResource;


class ProfileForList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'goriller_id' => isset($this->goriller) ? $this->goriller->id : '',
            // 'goriller_id' => $this->goriller_id,
            // 'goriller_id' => isset($this->goriller) ? $this->goriller->full_name : '',

        ];
    }
}
