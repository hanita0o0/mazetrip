<?php

namespace App\Http\Middleware\v1;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        /**
         * a custome way and unsecure way to handle log in requests
         */
//        if (empty($request->input('api_token')) or empty($request->input('password'))){
//            return response()->json('check api_token or password');
//        }
//        $user = User::where('email',$request->email)->first();
//        if ($user->password != $request->password){
//        }
        //////////////////////////////////////////////////////////////////////////////
        ///
            $token = $request->header('token');
//        $password = $request->header('password');

        $respond = User::where('api_token',$token)->first();

        return response()->json($respond);



        return $next($request);
    }
}
