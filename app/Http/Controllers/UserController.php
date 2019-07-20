<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserCollection as UserResourceCollection;
use App\Http\Resources\User as UserResource;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=\App\User::paginate(10);
        return new UserResourceCollection($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            "username"=>"required|unique:users,username|max:12",
            "password"=>"required|string|min:6",
            'role'=>'required'
            ]

           )->validate();
        $user=new \App\User;
        $user->username=$request->get('username');
        $user->password=\Hash::make($request->get('password'));
        $user->role=$request->get('role');
        if ($user->save()) {
            return response()->json([
          'status'=> 'success',
          'message'=> 'Data Berhasil disimpan'
            ],200);
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
        $user=\App\User::findOrFail($id);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function ubah(Request $request){
        $user=\App\User::findOrFail($request->get('id'));
        $user->username=$request->get('username');
        $user->password=\Hash::make($request->get('password'));
        $user->role=$request->get('role');

        if ($user->save()) {
        return response()->json([
          'status'=> 'success',
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
        $user=\App\User::findOrFail($id);
        if ($user->delete()) {
           return response()->json([
                          'status'=> 'success',
                          'message'=> 'Data Berhasil dihapus'
            ],200);
        }

    }
}
