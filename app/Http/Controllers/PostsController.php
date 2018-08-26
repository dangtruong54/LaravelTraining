<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use \Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Post as Post;
use App\Upload\Upload;

class PostsController extends Controller
{
    //
    public function getAllPost()
    {
        $user = auth('web')->user();
        $listPosts = (new Post())->where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate('4');
        return view('post.index', ['listPosts' => $listPosts]);
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

            $validated = $this->validateRule($request);

            if ($validated == null) {
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
            $pathImageOrigin = null;
            $pathImageThumbnail = null;
            $post->title = $data['title'];
            $post->content = $data['content'];
            if ($request->hasFile('filename')) {
                $originalImage = $data['filename'];
                $validated = $this->validateRule($request);
                if ($validated == null) {
                    $originalImageName = time() . $originalImage->getClientOriginalName();
                    new Upload($request, $originalImageName, 'origin');
                    new Upload($request, $originalImageName, 'thumbnail');

                    Storage::delete('/images/originals/'. $post->filename);
                    Storage::delete('/images/thumbnails/'. $post->filename);
                    $post->filename = $originalImageName;
                }
            }

            $date = date_create($data['date']);
            $format = date_format($date, "Y-m-d");
            $post->created_at = strtotime($format);
            $post->save();
            DB::commit();
            return redirect()->intended(route('post.getAllPost'))->with('success', 'Information has been Save');
        } catch (\Exception $e) {
            DB::rollBack();
            Storage::delete('/images/originals/'. $request->get('filename'));
            Storage::delete('/images/thumbnails/'. $request->get('filename'));
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

    public function validateRule($request)
    {
        $data = [
            'title' => 'required',
            'content' => 'required',
            'filename' => 'file|mimes:jpeg,png,jpg,gif|max:2048'
        ];
        $messages = [
            'max'    => 'The :attribute size is larger than 2M',
        ];
        $this->validate($request, $data, $messages);
    }
}


