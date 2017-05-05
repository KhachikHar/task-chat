<?php

namespace App\Http\Controllers;

use App\UserPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Response;

class PhotosController extends Controller
{
    public function index(Request $request){
        $photos = UserPhoto::where('user_id', Auth::user()->id)->orderBy('id', 'desk')->get()->toArray();
        return view('viewPhotos', ['photos' => $photos]);
    }
    public function changeMainPhoto(Request $request){
        if ($request->ajax()) {
            if(!empty($request->input('id'))) {
                $id = $request->input('id');
                if( !empty(UserPhoto::where(['user_id' => Auth::user()->id, 'id' => $id])->get()->toArray()) ){
                    UserPhoto::where(['user_id' => Auth::user()->id, 'main' => 1])->update(['main' => 0]);
                    UserPhoto::where('id', $id)->update(['main' => 1]);
                    return Response::json(['success' => true, 'error' => '']);
                } else {
                    return Response::json(['success' => false, 'error' => 'error']);
                }
            } else {
                return Response::json(['success' => false, 'error' => 'error']);
            }
        } else {
            throw new MethodNotAllowedException(['POST'], 'Method is not allowed');
        }
    }
    public function deletePhoto(Request $request){
        if ($request->ajax()) {
            if(!empty($request->input('id'))){
                $id = $request->input('id');
                $thisPhoto = UserPhoto::where(['user_id' => Auth::user()->id, 'id' => $id])->get()->toArray();
                $main = $thisPhoto[0]['main'];
                if( !empty($thisPhoto) ){
                    $photo = UserPhoto::where('id', $id)->select('photo')->get()->toArray();
                    if(!empty($photo[0]['photo'])){
                        $unlink = unlink(public_path($photo[0]['photo']));
                        if($unlink){
                            $delete = UserPhoto::where('id', $id)->delete();
                            if($delete){
                                if($main == 1){
                                    $last_id = UserPhoto::where(['user_id' => Auth::user()->id])->latest('id')->select('id')->first()->toArray();
                                    $last_id = $last_id['id'];
                                    UserPhoto::where('id', $last_id)->update(['main' => 1]);
                                    return Response::json(['success' => true, 'error' => '', $last_id]);
                                } else {
                                    return Response::json(['success' => true, 'error' => '']);
                                }
                            } else {
                                return Response::json(['success' => false, 'error' => 'error']);
                            }
                        } else {
                            return Response::json(['success' => false, 'error' => 'error']);
                        }
                    } else {
                        return Response::json(['success' => false, 'error' => 'error']);
                    }
                } else {
                    return Response::json(['success' => false, 'error' => 'error']);
                }
            } else {
                return Response::json(['success' => false, 'error' => 'error']);
            }
        } else {
            throw new MethodNotAllowedException(['POST'], 'Method is not allowed');
        }
    }


}
