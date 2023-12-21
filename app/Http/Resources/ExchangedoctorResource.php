<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExchangedoctorResource extends JsonResource
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
            'doctorcode' => $this->code,
            'fullname' => $this->name,
            'licence' => $this->licenseno,
            'cid' => $this->cid,
            'pname' => $this->pname,
            'fname' => $this->fname,
            'lname' => $this->lname,
            'birth_date' => $this->birth_date,
            'position' => $this->position,
        ];
    }
}
