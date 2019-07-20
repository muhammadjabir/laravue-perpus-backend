<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Peminjaman extends JsonResource
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
            'id'=>$this->id,
            'kode_peminjaman' => $this->kode_peminjaman,
            'status' => $this->status,
            'tanggal_pinjam' => $this->tanggal_pinjam,
            'batas_pinjam'=>$this->batas_pinjam,
            'kode_anggota'=>$this->anggota->kode_anggota,
            'kode_buku'=>$this->book->kode_buku,
            'foto_anggota'=>asset('storage/'.$this->anggota->foto),
            'nama_anggota'=>$this->anggota->nama_anggota
        ];
    }
}
