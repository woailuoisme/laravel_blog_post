<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'avatar'=> $this->avatar||'',
            'avatar_url'=>$this->when($this->avatar,$this->avatarUrl()),
            'name' => $this->user->name,
            'email' =>$this->user->email,
        ];
    }
}
