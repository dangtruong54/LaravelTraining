
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Index Page</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script src="{{asset('js/jquery.js')}}" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="{{asset('css/prettyPhoto.css')}}" type="text/css" media="screen" charset="utf-8" />
    <script src="{{asset('js/jquery.prettyPhoto.js')}}" type="text/javascript" charset="utf-8"></script>
</head>
<body>
<div class="container">
    <h3><a href="{{route('post.getAllPost')}}">Home</a></h3>
    <br />
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success') }}</p>
        </div><br />
    @endif
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Image</th>
            <th>Date Create</th>
            {{--<th>Content</th>--}}
            <th colspan="2">Action</th>
        </tr>
        </thead>
        <tbody>

        @foreach($listPosts as $item_post)
            @php
                $date=$item_post['created_at'];
            @endphp
            <tr>
                <td>{{$item_post['id']}}</td>
                <td><a href="{{route('get.editPost', $item_post['id'])}}">{{$item_post['title']}}</a></td>
                <td width="200px">
                    <a href="{{('http://img.domain/app/images/originals') . '/' . $item_post->filename}}" rel="prettyPhoto" title="This is the description">
                        <img class="img-fluid" src="{{('http://img.domain/app/images/thumbnails') . '/' . $item_post->filename}}"/>
                    </a>
                </td>
                <td>{{$date}}</td>
                {{--<td> {!!$item_post['content']!!}</td>--}}

                <td><a href="{{route('get.createPost')}}" class="btn btn-success">Add</a></td>
                <td><a href="{{route('get.editPost', $item_post['id'])}}" class="btn btn-warning">Edit</a></td>
                <td>
                    <form action="{{route('post.deletePost', $item_post['id'])}}" method="post">
                        @csrf
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <nav aria-label="Page navigation">
        {{ $listPosts->links() }}
    </nav>
</div>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function(){
        $("a[rel^='prettyPhoto']").prettyPhoto();
    });
</script>
</body>
</html>