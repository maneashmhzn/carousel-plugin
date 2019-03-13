<?php

namespace Plugins\Carousel\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Plugins\Carousel\Entities\Carousel;
use App\Http\Resources\GlobalResource as Resource;
use Image;



class AdminCarouselController extends Controller
{

  public $images_dir = 'public/images/';
  public $destination = 'public/images/carousel/';

  public function __construct(){
    if(!is_dir($this->images_dir)){
      mkdir($this->images_dir);
    }
    if(!is_dir($this->destination)){
      mkdir($this->destination);
    }
  }


    public function store(Request $request)
    {
      $slides = $request->data;
      $data = [];
      $count = 0;
      foreach ($slides as $slide) {
        $image = $slide['base64'];
        $name = md5(rand(0,99999));
        $name = $this->destination.$name . ".jpg";
        Image::make($image)->save($name,80);
        $data[$count] = [
          'image' => $name,
          'caption'=> $slide['caption'],
          'text'=> $slide['text']
        ];
        $count++;
      }
        $carousel = Carousel::create(['content'=>json_encode($data)]);
      if($carousel ){
        return new Resource($carousel);
      }
      else{
        return 'error';
      }
    }

}
