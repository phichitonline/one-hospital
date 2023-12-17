<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'hn' => $this->hn,
            'pname' => $this->pname,
            'fname' => $this->fname,
            'lname' => $this->lname,
            'cid' => $this->cid,
            'birthday' => $this->birthday,
            'fathername' => $this->fathername,
        ];

    }
}
