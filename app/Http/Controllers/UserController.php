<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

     /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return response()->json([
                'message' => 'All fields are required.'
            ], 200);       
        }
   
        $input = $request->all();
        $input['password'] =  Hash::make($request->password);
        $user = User::create($input);
       
   
        return response()->json([
            'message' => 'User register successfully.'
        ], 200);
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            //$user = Auth::user(); 
          
            return response()->json([
                'message' => 'User login successfully.'
            ], 200);
            
        } 
        else{ 
            return response()->json([
                'message' => 'Unauthorised'
            ], 200);
        } 
    }


}