<?php

namespace App\Http\Controllers\v2;
use App\Services\v2\HomeService2;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Location;
use App\LocatePhoto;
class ApiHomeController extends Controller
{
    protected $homes;

    public function __construct(HomeService2 $homeService)
    {
        $this->homes = $homeService;

    }

    /**
     * @param Request $request
     * @return string
     */
    public function addLocation(Request $request)
    {
      if(! ($request->latitude and $request->longitude and $request->about and
      $request->photo and  $request->writer and $request->location and $request->address)){
         return "not enough inputs";
      } else {
          $response=$this->homes->addLocation($request);
         return response()->json($response);
     }
    }
     public function searchLocationMap(Request $request){
     if(!($request->latRange and $request->longRange)){
        return "not enough input";
     }
     $response=$this->homes->searchLocationMap($request);
     return response()->json($response);

    }
    public function searchLocationName(Request $request)
    {
      if(!$request->locateName)
      {
          return "not enough input";
      }
        $response=$this->homes->searchLocationName($request);
        return response()->json($response);
    }
    public function showLocation($lid)
    {
            $response=$this->homes->showLocation($lid);
            return response()->json($response);
    }
    public function addCommentLocation(Request $request,$lid)
    {

      $locate=Location::where('id',$lid)->first();
      if(empty($locate)){
          return "no location";
      }
      if(!($request->body and $request->author and $request->star)){
        return "not enough input";
      }
      $response=$this->homes->addCommentLocation($request,$locate);
      return response()->json($response);
    }




}
