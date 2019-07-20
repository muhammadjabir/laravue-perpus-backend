<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table ='anggota';
    protected $dates = ['tanggal_lahir'];
    
    public function foto(){
    	 return asset('public/'.$this->attributes['foto']);
    }
    public function user(){
    	return $this->belongsTo('App\User', 'id_user', 'id');
	}
	public function peminjaman(){
    return $this->hasMany('App\Peminjaman', 'id_anggota', 'id');
	}
}
