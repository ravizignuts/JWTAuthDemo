<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
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
        $token = auth()->attempt($validator->validate());
        dd($token);
        // if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        //     /** @var \App\Model\User $user */
        //     $user = Auth::user();
        //     $success['token'] = $user->createToken('MyAPP')->accessToken;
        //     return response()->json([
        //         'success' => true,
        //         'user'    => $user['name'],
        //         'token'   => $success
        //     ]);
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => "User Credentials are Invalid"
        //     ]);
        // }
    }
    /**
     * API FOR User Profile
     * @param Request $request
     * @return json Data
     */
    public function profile(){

    }
    /**
     * API FOR User Token Refresh
     * @param Request $request
     * @return json Data
     */
    public function tokenRefresh(){

    }
    /**
     * API FOR Logout User
     * @param Request $request
     * @return json Data
     */
    public function logout(){
        /** @var \App\Model\User $user */
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json([
            'user'    => $user,
            'message' => 'User Logout'
        ]);
    }
}
