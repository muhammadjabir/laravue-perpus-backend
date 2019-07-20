<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PeminjamanCollection as PeminjamanResourceCollection;
use App\Http\Resources\Peminjaman as PeminjamanResource;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $peminjaman=\App\Peminjaman::paginate(5);
        return new PeminjamanResourceCollection($peminjaman);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation=\Validator::make($request->all(),[
                    "kode_anggota"=>"required|exists:anggota,kode_anggota",
                    "kode"=>"exists:book,kode_buku"
                    ],['kode_anggota.exists'=>"Kode Anggota salah atau tidak ada",
                        'kode.exists'=>"Kode Buku Salah atau tidak ada",]

                   )->validate();
        $status='';
        $code=200;
        $pesan='';
        $cari_anggota=\App\Anggota::where('kode_anggota','=',$request->kode_anggota)->first();
        $cari_buku=\App\Book::where('kode_buku','=',$request->kode)->first();
        $cek_peminjaman=\App\Peminjaman::where('id_anggota',$cari_anggota->id)->where('status',0)->first();

        if ($cek_peminjaman) {
            $status='Gagal';
            $pesan='Siswa belum mengembalikan buku atau buku sedang dipinjam';
            $code=400;
        }
        else{
            $tanggal=\Carbon\Carbon::now();
            $peminjaman=new \App\Peminjaman;
            $peminjaman->kode_peminjaman='300344' + \App\Peminjaman::max('id');
            $peminjaman->tanggal_pinjam=$tanggal->format('Y-m-d');
            $peminjaman->batas_pinjam=$tanggal->addDays(7)->format('Y-m-d');
            $peminjaman->id_anggota=$cari_anggota->id;
            $peminjaman->id_book=$cari_buku->id;
            $peminjaman->save();
            $cari_buku->status=0;
            if ($cari_buku->save()) {
                $status='Success';
                $pesan='Berhasil Melakukan Peminjaman';
            }
        }
        
   
        
        return response()->json([
        'status'=>$status,
        'message'=> $pesan
        ],$code);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $peminjaman=\App\Peminjaman::findOrFail($id);
        $book=\App\Book::findOrFail($peminjaman->id_book);
        $book->status=1;
        if ($book->save()) {
            $peminjaman->delete();
        }
        
    }
}
