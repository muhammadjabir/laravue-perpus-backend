<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BookCollection as BookResourceCollection;
use App\Http\Resources\Book as BookResource;
use App\Book;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->get('filter')) {
             $books=Book::where('status',1)->get();
        }
        else{
            $books=Book::paginate(4);
        }
        
        return new BookResourceCollection($books);
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
        $kode_buku=Book::max('id') + 9000;
        $book=new Book;
        $book=new \App\Book;
        $book->kode_buku=$kode_buku;
        $book->judul_buku=$request->get('judul');
        $book->deskripsi=$request->get('deskripsi');
        $book->penulis=$request->get('penulis');
        $book->penerbit=$request->get('penerbit');
        $book->tahun_terbit=$request->get('tahun');
        $file=$request->file('foto')->store('foto_buku','public');
        $book->foto=$file;
        if ($book->save()) {
            return response()->json([
            'status'=>'Success',
            'message'=> 'Berhasil Tambah data buku'
            ],200);
        };
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book=Book::findOrFail($id);
        return new BookResource($book);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book=Book::findOrFail($id);
        return new BookResource($book);
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
        $book=Book::findOrFail($id);
        $book->judul_buku=$request->get('judul');
        $book->deskripsi=$request->get('deskripsi');
        $book->penulis=$request->get('penulis');
        $book->penerbit=$request->get('penerbit');
        $book->tahun_terbit=$request->get('tahun');
        if ($request->file('foto')) {
             if($book->foto and file_exists(storage_path('app/public/'.$book->foto))){
                \Storage::delete('public/'.$book->foto);
                }
            $file=$request->file('foto')->store('foto_buku','public');
            $book->foto=$file;
        }
        
        if ($book->save()) {
            return response()->json([
            'status'=>'Success',
            'message'=> 'Berhasil Edit Buku'
            ],200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book=Book::findOrFail($id);
        if ($book->delete()) {
             return response()->json([
            'status'=>'Success',
            'message'=> 'Data Buku Berhasil dihapus'
            ],200);
        }
    }
}
