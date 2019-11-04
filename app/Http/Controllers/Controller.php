<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Intervention\Image\Facades\Image as Image;
use Auth;

class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    public function uploadFile($request, $fileName, $path) {
        $image = $request->file($fileName);
        //dd($_FILES);
        $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path($path);
        //
        $thumb_img = Image::make($image->getRealPath())->resize(745, 550);
        // $thumb_img->save($destinationPath . '/cropped/' . 'thu-' . $input['imagename'], 80);
        $image->move($destinationPath, $input['imagename']);
        return $path . '/' . $input['imagename'];
    }

//    public function success($data = [], $code = 200) {
//        return response()->json(['status' => true, 'code' => $code, 'data' => (object) $data], $code);
//    }
//
//    public function error($message = [], $code = 500) {
//        return response()->json(['status' => false, 'code' => $code, 'message' => $message], $code);
//    }

}
