<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table='book';
    public function peminjaman(){
    return $this->hasMany('App\Peminjaman', 'id_book', 'id');
	}
}
