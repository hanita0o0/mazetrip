<?php

namespace App\Http\Controllers\v1;
use App\User;
use App\Type;
use App\Event;
use App\Services\v1\HomeService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiHomeController extends Controller
{
    //
    protected $home;

    public function __construct(HomeService $chatService)
    {
        $this->home = $chatService;
        $this->middleware('auth:api')->except('showTypes','suggest');
    }

    public function hello()
    {

            $userPostSkip = 0;
            $userPostLimit = 20;
            $clubPostLimit = 20;
            $clubPostSkip = 0;
            if (\request()->input()) {
                if (\request()->input('userPostSkip')) {
                    $userPostSkip = \request()->input('userPostSkip');
                }
                if (\request()->input('userPostLimit')) {
                    $userPostLimit = \request()->input('userPostLimit');
                }
                if (\request()->input('clubPostLimit')) {
                    $clubPostLimit = \request()->input('clubPostLimit');
                }
                if (\request()->input('clubPostSkip')) {
                    $clubPostSkip = \request()->input('clubPostSkip');
                }
            }
      $data=$this->home->home($userPostLimit, $userPostSkip, $clubPostLimit, $clubPostSkip);

            return response()->json($data);
       }








    public function suggest(Request $request){
        $clubSkip=0;
        $clubSuggest=20;
        if(\Request()->input()){
            if(\Request()->input('clubSkip')){
                $clubSkip=\Request()->input('clubSkip');
            }
            if(\Request()->input('clubSuggest')){
                $clubSuggest=\Request()->input('clubSuggest');
            }
        }
        $user=Auth::user();
        $state=$user->state_id;
        $city=$user->city_id;
        if($request->types) {
            $dd = $request->types;
            $clubs = $this->home->getClubs($dd, $state, $city, $clubSkip, $clubSuggest);
        }
        return response()->json($clubs);


    }
    public function showTypes(){
        $types=$this->home->getTypes();
     return response()->json($types);

    }
}
