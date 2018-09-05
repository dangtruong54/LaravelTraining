<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use \Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Post as Post;
use App\Upload\Upload;
use App\Validations\ImageValidator;

class PostsController extends Controller
{
    protected $dataValidator;
    //
    public function __construct(ImageValidator $imageValidator)
    {
        $this->dataValidator = $imageValidator;

    }

    public function getAllPost()
    {
        $user = auth('web')->user();
        $userName = $user->username;
        //$listPosts = (new Post())->where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate('4');

        $listPosts = (new Post())
                        ->where('user_id', $user->id)
                        ->with('user')
                        ->orderBy('created_at', 'DESC')
                        ->paginate('2');
        return view('post.index', ['listPosts' => $listPosts, 'userName' => $userName]);
    }

    public function getCreatePost(Request $request)
    {
        return view('post.create');
    }

    public function postCreatePost(Request $request)
    {

        DB::beginTransaction();
        try {

            $data = $request->all();

            $validated = $this->dataValidator->checkValidator($data, $this->dataValidator->addImage());
            if (!$validated->fails()) {
                if ($request->hasFile('filename')) {
                    $originalImage = $data['filename'];
                    $originalImageName = time() . $originalImage->getClientOriginalName();
                    new Upload($request, $originalImageName, 'origin');
                    new Upload($request, $originalImageName, 'thumbnail');
                }
                $post = new Post();
                $post->user_id = Auth::id();
                $post->title = $data['title'];
                $post->content = $data['content'];
                $post->filename = $originalImageName;
                $date = date_create($data['date']);
                $format = date_format($date, "Y-m-d");
                $post->created_at = strtotime($format);
                $post->save();
                DB::commit();
                return redirect()->intended(route('post.getAllPost'))->with('success', 'Information has been Save');
            }else {
                $errors = $validated->errors();
                return view('post.create',  ['errors' => $errors]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->hasFile('filename'))
            {
                Storage::delete('/images/originals/'. $request->get('filename'));
                Storage::delete('/images/thumbnails/'. $request->get('filename'));
            }
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
        $post = Post::find($id);

        DB::beginTransaction();
        try {
            $post->title = $data['title'];
            $post->content = $data['content'];
            if ($request->hasFile('filename')) {
                $originalImage = $data['filename'];
                $originalImageName = time() . $originalImage->getClientOriginalName();
                new Upload($request, $originalImageName, 'origin');
                new Upload($request, $originalImageName, 'thumbnail');

                Storage::delete('/images/originals/'. $post->filename);
                Storage::delete('/images/thumbnails/'. $post->filename);
                $post->filename = $originalImageName;
            }
            $date = date_create($data['date']);
            $format = date_format($date, "Y-m-d");
            $post->created_at = strtotime($format);
            $post->save();
            DB::commit();
            return redirect()->intended(route('post.getAllPost'))->with('success', 'Information has been Save');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->hasFile('filename')) {
                Storage::delete('/images/originals/' . $request->get('filename'));
                Storage::delete('/images/thumbnails/' . $request->get('filename'));
            }
        }
    }

    public function postDeletePost($id)
    {
        DB::beginTransaction();
        try {
            Post::find($id)->delete();
            DB::commit();
            return redirect()->intended(route('post.getAllPost'))->with('success', 'Information has been  deleted');
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}


