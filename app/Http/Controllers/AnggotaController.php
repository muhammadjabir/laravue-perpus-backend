<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Anggota;
use DB;
use App\Http\Resources\AnggotaCollection as AnggotaResourceCollection;
use App\Http\Resources\AnggotaResource as AnggotaResource;
class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        // $anggota=Anggota::select(DB::raw("DATE_FORMAT(updated_at, '%Y') year"))->groupBy('year')->get();
        // return $anggota;
        $anggota=\App\Anggota::paginate(10);
        return new AnggotaResourceCollection($anggota);
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
        "name"=>"required|max:40|regex:/(^[A-Za-z ]+$)+/",
        "alamat"=>"required|string|max:150",
        'tgl_lahir'=>'required',
        'foto'=>'image|required|max:3000'
        ]
        )->validate();
        $kodeanggota=Anggota::max('id') + 33001;

        $user = new \App\User;
        $user->username=$kodeanggota;
        $user->password=\Hash::make($kodeanggota);
        $user->role='ANGGOTA';
        if ($user->save()) {
            $anggota=new Anggota;
            $anggota->kode_anggota=$kodeanggota;
            $anggota->nama_anggota=$request->name;
            $anggota->alamat=$request->alamat;
            $anggota->tanggal_lahir=$request->tgl_lahir;
            $anggota->id_user=$user->id;
            $file=$request->file('foto')->store('foto_anggota','public');
            $anggota->foto=$file;
            if ($anggota->save()) {
                return response()->json([
                    'status'=>'Success',
                    'message'=> 'Anggota Berhasil ditambah'
                    ],200);
            }
        }


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
        $anggota = Anggota::findOrFail($id);
        return new AnggotaResource($anggota);
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
        $validation=\Validator::make($request->all(),[
        "name"=>"required|max:40|regex:/(^[A-Za-z ]+$)+/",
        "alamat"=>"required|string|max:150",
        'tgl_lahir'=>'required',
        'foto'=>'image|max:3000'
        ]
        )->validate();
        $anggota=Anggota::findOrFail($id);
        $anggota->nama_anggota=$request->name;
        $anggota->alamat=$request->alamat;
        $anggota->tanggal_lahir=$request->tgl_lahir;
         if ($request->file('foto')) {
            if($anggota->foto and file_exists(storage_path('app/public/'.$anggota->foto))){
                \Storage::delete('public/'.$anggota->foto);
                }
            $file=$request->file('foto')->store('foto_anggota','public');
            $anggota->foto=$file;
        }
        if ($anggota->save()) {
            return response()->json([
            'status'=>'Success',
            'message'=> 'Data Berhasil diubah'
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
        $anggota=Anggota::findOrFail($id);
        $user=\App\User::findOrFail($anggota->id_user);
        if ($user->delete()) {
             return response()->json([
            'status'=>'Success',
            'message'=> 'Data Berhasil dihapus'
            ],200);
        }
    }
}
