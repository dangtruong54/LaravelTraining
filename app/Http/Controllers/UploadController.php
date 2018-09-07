<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Storage;
use App\Http\Controllers\File;

use Illuminate\Http\Request;
use App\Validations\UploadValidator;
use App\upload;

class UploadController extends Controller
{
    protected $validator;

    /**
     * UploadController constructor.
     * @param UploadValidator $uploadFile
     */
    public function __construct(
        UploadValidator $uploadFile
    )
    {
        $this->validator = $uploadFile;
    }

    public function getUpload()
    {
        $listImage = DB::table('upload')->orderBy('created_at', 'desc')->paginate('3');
        return view('upload.index', ['listImage' => $listImage]);
    }

    public function postUpload(Request $request)
    {
//        dd($request->all());
        $arrImages = $request->get('image');
        $errors = [];
        $typeValidate = ["gif", "jpeg", "png", "jpg"];
        DB::beginTransaction();
        try {
            if (isset($arrImages) && count($arrImages) > 0)
            {
                foreach ($arrImages as $key => $itemImage) {
                    $sizeImage = (int) (strlen(rtrim($itemImage, '=')) * 3 / 4);
                    $typeImage = str_replace(';', '', substr($itemImage, 11, 4));
                    $fileName = time() . str_random(10) . '.' . $typeImage;
                    if (in_array($typeImage, $typeValidate) > 0) {
                        if ($sizeImage > 1048576) {
                            array_push($errors, 'File ' . $key . ' is larger than 1MB!');
                        } else {
                            $image = new Upload();
                            $image->filename = $fileName;
                            $image->save();
                            Storage::put($fileName, file_get_contents($itemImage));
                            DB::commit();
                        }
                    } else {
                        array_push($errors, 'File ' . $key . ' is not file image!');
                    }
                }
            }
            return redirect()->route('get.getUpload');
        } catch (\Exception $e) {
            Storage::delete($fileName);
            DB::rollBack();
        }
    }
}
