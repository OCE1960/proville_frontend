<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;

class DashBoardController extends Controller
{
    public function  dashboard() {
        $post = DB::table('page_content')->
                select('menu', 'title', 'body','date_updated','id')
                      ->where('menu','about')->limit(20)
                      ->orderBy('date_updated','Desc')->get();
          $carousel = DB::table('advert')->
                      select('image_caption', 'menu', 'image_path','status','id')
                      -> where('menu','home')
                      ->orderBy('timestamp','Desc')->limit(6)->get();
        return Response() -> json([
                                'data' => $post,
                                'carousel' => $carousel,
        ]);
    }

    public function dashboardEdit($id){
        $post = DB::table('page_content')->
                        select('menu', 'title', 'body','date_updated','picture')
                        ->where('id', $id)-> limit(1) -> get();
        return Response() -> json([
            'data' => $post
            ]);
    }

    public function serviceNextEdit($id){
        $post = DB::table('service_next_sunday')->
                        select('service_next', 'status')
                        ->where('id', $id)-> limit(1) -> get();
        return Response() -> json([
            'service_next' => $post
            ]);

    }

    public function editDiscovery($id){
        $post = DB::table('discovery')->
                        select('service', 'date_of_service', 'topic','notice')
                        ->where('id', $id)-> limit(1) -> get();
        return Response() -> json([
            'data' => $post
            ]);

    }
    public function editEvent($id){
        $post = DB::table('advert')->
                        select('image_caption', 'menu', 'image_path','status')
                        ->where('id', $id)-> limit(1) -> get();
        return Response() -> json([
            'event' => $post
            ]);

    }
    public function editAudio($id){
        $post = DB::table('audio')->
                        select('title', 'url','status')
                        ->where('id', $id)-> limit(1) -> get();
        return Response() -> json([
            'audio' => $post
            ]);

    }

    public function activate($id){
        $post = DB::table('users')->
                        select('activation_code')
                        ->where('activation_code', $id)-> limit(1) -> get();
        return Response() -> json([
            'activation_code' => $post
            ]);

    }

    public function menuContent($menu) {
        $post = DB::table('page_content')->
                select('menu', 'title', 'body','date_updated','id', 'picture')
                ->where('menu',$menu )
                ->orderBy('date_updated','Desc')->get();
        return Response() -> json([
                    'post' => $post
                    ]);
            }

    public function discoveryList() {
        $post = DB::table('discovery')->
        select('service', 'date_of_service', 'topic','notice','id')
        ->orderBy('date','Desc')->get();
            return Response() -> json([
            'post' => $post
            ]);
    }

    public function galleryList() {
        $post = DB::table('advert')->
        select('image_caption', 'menu', 'image_path','status','id')
        ->orderBy('timestamp','Desc')->get();
            return Response() -> json([
            'post' => $post
            ]);
    }


}