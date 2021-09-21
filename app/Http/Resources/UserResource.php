<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Const Message
     */
    const MESSAGE = "success";
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        // $datas = array();
        // foreach ($this->resource as $data) {
        //     $datas[] = array(
        //         'id' => $data->id,
        //         'name' => $data->name,
        //         'email' => $data->email,
        //     );
        // }
        return [
            'data' => $this->resource,
            'message' => self::MESSAGE,
            'code' => '200',
        ];
    }
}
