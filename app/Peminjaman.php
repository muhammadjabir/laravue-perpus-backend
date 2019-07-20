<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table='slip_peminjaman';
    protected $dates=['tanggal_pinjam','batas_pinjam'];
    protected $dateFormat = 'Y-m-d H:i:s';

    public function book(){
    	return $this->belongsTo('App\Book', 'id_book', 'id');
	}

	public function anggota(){
    return $this->belongsTo('App\Anggota', 'id_anggota', 'id');
	}
}
