<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\User;
class AuthController extends Controller
{
    public function login(Request $request){
    	$user=User::where('username','=',$request->username)->first();
    	$status='error';
    	$code=401;
    	$data=null;
    	$message="";

    	if($user){//cek user
    		if(Hash::check($request->password,$user->password)) {
    			$user->generateToken();
    			$status= 'success';
    			$message='Login Success';
    			// tampilkan data user menggunakan method toArray
    			$data = $user->toArray();
    			$code = 200;
    		}
    		else{
    			$message='Login Gagal, password salah';
    		}
    		//akhhir cek password
    	}
    	else{
    		$message='Login Gagal, password dan username salah';
    	}
    	//akhir cek user

    	return response()->json([
    		'status' => $status,
    		'message' => $message,
    		'data' => $data
    		], $code);
    }
    
     public function logout(Request $request){
        $user = \Auth::user();
        if ($user) {
            $user->api_token = null;
            $user->save();
        }
        return response()->json([
        'status' => 'success',
        'message' => 'logout berhasil',
        'data' => null
        ], 200);
    }
}
