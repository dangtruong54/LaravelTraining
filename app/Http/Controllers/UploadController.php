<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Storage;

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
        $listImage = DB::table('upload')->paginate('3');
        return view('upload.index', ['listImage' => $listImage]);
    }

    public function postUpload(Request $request)
    {
        dd($request->all());
        DB::beginTransaction();
        try {
        if ($request->hasFile('filename')) {
            $data = $request->all();
            $validator = $this->validator->checkValidator($data, $this->validator->addImage());
            $errors = $validator->errors();

            if ($errors->messages()) {
                return redirect()->route('get.getUpload')->with(['errors' => $errors]);
            } else {
                foreach ($data['filename'] as $key => $itemImage) {
                    $image = new Upload();
                    $fileName = time() . $itemImage->getClientOriginalName();
                    $image->filename = $fileName;
                    $image->save();
                    $content = $itemImage;
                    Storage::putFileAs('images/upload/', $content, $fileName);
                    DB::commit();
                }
                return  redirect()->route('get.getUpload');
            }
        }
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
