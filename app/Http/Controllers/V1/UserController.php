<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth:api',['except' => ['login','register']]);
    // }//Second Method to apply middleware

    /**
     * API FOR Register User
     * @param Request $request
     * @return json Data
     */
    public function register(Request $request){
        $this->validate($request, [
            'name'                  => 'required|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|max:12|confirmed',
            'password_confirmation' => 'required|min:8|max:12'
        ]);
        // $request['password'] = Hash::make($request->password);
        $request['password'] = bcrypt($request->password);
        $user = User::create($request->only('name', 'email', 'password'));
        return response()->json([
            'success' => $user['name'],
            'message' => 'User Registered Succesfully'
        ]);
    }
    /**
     * API FOR Login User
     * @param Request $request
     * @return json Data
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email'    => 'required|email',
            'password' => 'required|min:8|max:20'
        ]);
        if($validator->fails()){
            return response()->json([
                'message' => 'Validation Error',
                'Error'   => $validator->errors()
            ]);
        }
        if(! $token = auth()->attempt($validator->validate())){
            return response()->json([
                'error' => 'Invalid Credentials'
            ]);
        }
        return $this->respondWithToken($token);
    }
    protected function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL()*60
        ]);
    }
    /**
     * API FOR User Profile
     * @return json Data
     */
    public function profile(){
        $user = auth()->user();
        if($user){
            return response()->json([
                auth()->user()
            ]);
        }else{
            return response()->json([
                'message' => 'User Unauthorized'
            ]);
        }
    }
    /**
     * API FOR User Token Refresh
     * @return json Data
     */
    public function tokenRefresh(){
        return $this->respondWithToken(auth()->refresh());
    }
    /**
     * API FOR Logout User
     * @param Request $request
     * @return json Data
     */
    public function logout(){
        $user = auth()->user();
        if($user){
            auth()->logout();
            return response()->json([
                'user'    =>  $user,
                'message' => 'User Successfully Logout'
            ]);
        }else{
            return response()->json([
                'message' => 'Unauthorized'
            ]);
        }

    }
}
