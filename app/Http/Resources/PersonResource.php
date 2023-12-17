<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
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
            'pid' => $this->person_id,
            'pname' => $this->pname,
            'fname' => $this->fname,
            'lname' => $this->lname,
            'cid' => $this->cid,
            'birthdate' => $this->birthdate,
            'father_name' => $this->father_name,
        ];
    }
}
