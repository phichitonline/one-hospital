<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IpdvisitResource extends JsonResource
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
            'ptm_ipd_hn' => $this->ptm_ipd_hn,
            'ptm_ipd_an' => $this->ptm_ipd_an,
            'pt_ipd_today' => $this->pt_ipd_today,
            'ptm_ipd_vn_last' => $this->ptm_ipd_vn_last,
            'ipt_admit' => $this->ipt_admit,
            'empty_bed' => $this->empty_bed,
            'bed_count' => $this->bed_count,
        ];
    }
}
