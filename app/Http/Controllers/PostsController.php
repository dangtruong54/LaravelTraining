<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Image;
use \Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Post as Post;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    //
    public function getAllPost()
    {
        $user = auth('web')->user();
        $listPosts = (new Post())->where('user_id', $user->id)->orderBy('created_at','DESC')->paginate('2');
        return view('post.index', ['listPosts' => $listPosts]);
    }

    public function getCreatePost(Request $request)
    {
        return view('post.create');
    }

    public function postCreatePost(Request $request)
    {

        $data = $request->all();
//        dd($data);
        $validated = $this->validateRule($request);

        if($validated == null){
            $originalImage = $data['filename'];
            $originalImageName = time() . $originalImage->getClientOriginalName();
            $this->uploadImage($request, $originalImageName);

            $post= new Post();
            $post->user_id = Auth::id();
            $post->title = $data['title'];
            $post->content = $data['content'];
            $post->filename = $originalImageName;
            $date=date_create($data['date']);
            $format = date_format($date,"Y-m-d");
            $post->created_at = strtotime($format);
            $post->save();
            return redirect()->intended(route('post.getAllPost'))->with('success','Information has been Save');
        }
    }

    public function getEditPost($id)
    {
        $post = Post::find($id);
        return view('post.create', ['post' => $post]);
    }

    public function postEditPost(Request $request, $id)
    {

        $data = $request->all();
//        dd($data);
        $post = Post::find($id);
        $post->title = $data['title'];
        $post->content = $data['content'];
        $path = [];
        if($request->hasFile('filename')){
            $originalImage = $data['filename'];
            $validated = $this->validateRule($request);
            if(Post::created($validated) == null) {
                $originalImageName = time() . $originalImage->getClientOriginalName();
                $path = $this->uploadImage($request, $originalImageName);
                $post->filename = $originalImageName;
            }
        }
        DB::beginTransaction();
        try {
            $date = date_create($data['date']);
            $format = date_format($date,"Y-m-d");
            $post->created_at = strtotime($format);
            $post->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Storage::delete($path);
        }

        return redirect()->intended(route('post.getAllPost'))->with('success','Information has been Save');

    }

    public function postDeletePost($id)
    {
        Post::find($id)->delete();
        return redirect()->intended(route('post.getAllPost'))->with('success','Information has been  deleted');
    }

    public function validateRule($request)
    {

        $data = [
            'filename' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048'
        ];
        $messages = [
            'max'    => 'The :attribute size is larger than 2M',
        ];

        $this->validate($request, $data, $messages);
    }



    public function uploadImage($request, $originalImageName){

        //========== Save image origin ========//
        $content = file_get_contents($request->file('filename'));

        $request->file('filename')->storeAs('originals', $originalImageName );
        Storage::delete($originalImageName);

        //========== Save image thumbnail ========//
        $originalImage = $request->file('filename');
        $this->createImageThumbnail($originalImage, $originalImageName);
        return ['originals/' .$originalImageName, 'dsadsadsa'];
    }

    public function createImageThumbnail($originalImage, $originalImageName)
    {
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
        $destinationPath = public_path('images/thumbnail');
        $thumbnailImage->save($destinationPath . '/' . $originalImageName);

    }

}
