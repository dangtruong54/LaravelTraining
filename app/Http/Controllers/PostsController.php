<?php

namespace App\Http\Controllers;

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
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $data = $request->all();
        if($request->hasfile('filename'))
        {
            $file = $request->file('filename');
            $name=time().$file->getClientOriginalName();
            $file->move(public_path().'/images/', $name);
        }
        $post= new Post();
        $post->user_id = Auth::id();
        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->filename = $name;
        $date=date_create($data['date']);
        $format = date_format($date,"Y-m-d");
        $post->created_at = strtotime($format);
        $post->save();
        return redirect()->intended(route('post.getAllPost'))->with('success','Information has been Save');
    }

    public function getEditPost($id)
    {
        $post = Post::find($id);
        return view('post.create', ['post' => $post]);
    }

    public function postEditPost(Request$request, $id)
    {
        $data = $request->all();
        if($request->hasfile('filename'))
        {
            $file = $request->file('filename');
            $name=time().$file->getClientOriginalName();
            $file->move(public_path().'/images/', $name);
        }
        $post= Post::find($id);
        $post->title = $data['title'];
        $post->content = $data['content'];
        if(!empty($name)) $post->filename = $name;
        $date=date_create($data['date']);
        $format = date_format($date,"Y-m-d");
        $post->created_at = strtotime($format);
        $post->save();
        return redirect()->intended(route('post.getAllPost'))->with('success','Information has been Save');
    }

    public function postDeletePost($id)
    {
        Post::find($id)->delete();
        return redirect()->intended(route('post.getAllPost'))->with('success','Information has been  deleted');
    }

}
