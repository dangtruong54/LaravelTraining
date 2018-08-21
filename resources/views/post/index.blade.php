
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Index Page</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
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
                    <img class="img-fluid" src="{{URL::asset('/images') . '/' . $item_post->filename}}"/>
                </td>
                <td>{{$date}}</td>
                {{--<td> {!!$item_post['content']!!}</td>--}}

                <td><a href="{{route('get.createPost')}}" class="btn btn-success">Add</a></td>
                <td><a href="{{route('get.editPost', $item_post['id'])}}" class="btn btn-warning">Edit</a>
                </td>
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
</body>
</html>