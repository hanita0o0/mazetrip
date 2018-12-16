<?php
namespace App\Services\v2;
use App\LocateComment;
use App\Services\v1\HomeService;
use Illuminate\Support\Facades\DB;
use App\LocatePhoto;
Use App\Location;
use Image;

class HomeService2 extends  HomeService
{
 public function addLocation($data)
 {

  //return $_FILES['photo'];
//     return $data->file('photo');
  $locate=new Location();
  $locate->location=$data->location;
  $locate->about=$data->about;
  $locate->writer=$data->writer;
  $locate->latitude=$data->latitude;
  $locate->longitude=$data->longitude;
  $locate->address=$data->address;
  if($locate->save()) {
     $this->photoHandle( $locate->id);
     }
  return "create location";
 }
    protected function photoHandle($id)
    {
        for($i=0;count($_FILES['photo']['name'])>$i;$i++) {
            $file_name = time() . '.' . $_FILES['photo']['name'][$i];

            $path_original = storage_path($this->getImageFoldersName('photos/locations/original/') . $file_name);
            $path_thumbnail = storage_path($this->getImageFoldersName('photos/locations/thumbnail/') . $file_name);
            $path_medium = storage_path($this->getImageFoldersName('photos/locations//medium/') . $file_name);
            Image::make($_FILES['photo']['tmp_name'][$i])->fit(150)->save($path_thumbnail);
           Image::make($_FILES['photo']['tmp_name'][$i])->fit(300)->save($path_medium);
           Image::make($_FILES['photo']['tmp_name'][$i])->save($path_original);
             LocatePhoto::create(['path' => $file_name,'location_id'=>$id]);
        }
    }
    public function searchLocationMap($input)
    {
        $lat=explode(",",$input->latRange);
        $lat1=$lat[0];
        $lat2=$lat[1];
        $long=explode(",",$input->longRange);
        $long1=$long[0];
        $long2=$long[1];
        $data=[];
        $location=DB::select("select * from locations where latitude between $lat1 and $lat2 and  longitude between $long1 and $long2");
//
        return $location;
    }
    public function searchLocationName($request)
    {
      $location=Location::where('location','LIKE','%'.$request->locateName.'%')->get();
      return $location;
    }
    public function showLocation($lid)
    {
        $data=[];
        $location=Location::where('id',$lid)->first();
        if(empty($location)){
            return "no location";
        }else {
            $id=$location->id;
            $photo=LocatePhoto::where('location_id',$id)->first();
          // $count= DB::select("select count(*) as commentNumber from locatecomments where location_id=$id");
           $avg=DB::select("select AVG(star) as avgStar from locatecomments ");

            $data=[
                'location'=>$location->location,
                'writer'=>$location->writer,
                'about'=>$location->about,
                'address'=>$location->address,
                'photo'=>$photo->id?$this->getImageFoldersName(null,$photo->created_at).$photo->path:null,
                'created_date'=>$location->created_at,
                'updated_date'=>$location->updated_at,
               'starAverage'=>$avg

            ];
            return $data;
        }
    }
    public function addCommentLocation($request,$locate)
    {
        $star=$request->star;
        if (!($star >= 1 and $star <= 5))
        {

            return "star not between 1 and 5";
        }
        $comment=new LocateComment();
        $comment->author=$request->author;
        $comment->body=$request->body;
        $comment->star=$star;
        $comment->location_id=$locate->id;
        if($comment->save())
        {
            return "comment inserted";
        }

    }

}