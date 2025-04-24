<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataAbsensiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'name' => $this->users->name,
            ],
            'tanggal' => $this->tanggal,
            'jam_masuk' => $this->jam_masuk,
            'jam_pulang' => $this->jam_pulang,
            'lokasi_masuk' => $this->lokasi_masuk,
            'lokasi_pulang' => $this->lokasi_pulang,
            'foto_masuk' => $this->foto_masuk,
            'foto_pulang' => $this->foto_pulang,
            'lama_kerja' => $this->lama_kerja,
            'foto_masuk_asset' => $this->foto_masuk ? asset('foto/'.$this->foto_masuk) : null,
            'foto_pulang_asset' => $this->foto_pulang ? asset('foto/'.$this->foto_pulang) : null,
        ];
    }
}
