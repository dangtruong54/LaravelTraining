<?php
namespace App\Upload;

use Image;
use \Illuminate\Support\Facades\Storage;

class Upload
{
    /**
     * Upload constructor.
     */
    public function __construct($request, $originalImageName, $check)
    {
        $this->uploadImage($request, $originalImageName, $check);
    }

    public function uploadImage($request, $originalImageName, $check = 'origin')
    {

        if($check == 'origin')
        {
            $content = $request->file('filename');
            $pathOrigin = Storage::putFileAs('images/originals', $content, $originalImageName);
            return $pathOrigin;
        }else {
            $originalImage = $request->file('filename');

            $sizeImageOrigin = getimagesize($originalImage);
            $widthImageOrigin = $sizeImageOrigin[0];
            $heightImageOrigin = $sizeImageOrigin[1];
            $ratio = $widthImageOrigin/$heightImageOrigin;
            $thumbnailImage = Image::make($originalImage);

            if($ratio >= 1)
            {
                $thumbnailImage->resize(150, 150 * 1/$ratio);
            }else {
                $thumbnailImage->resize(150 * $ratio,150);
            }
            $thumbnailImage->save(storage_path('app/images/thumbnails/'. $originalImageName));
            $pathThumbnail = storage_path('app/images/thumbnails/'. $originalImageName);
            return $pathThumbnail;
        }
    }
}