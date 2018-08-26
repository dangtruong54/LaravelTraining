<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Laravel 5.6 CRUD Tutorial With Example  </title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
  </head>
  <body>

    <div class="container">
      <h2>@if(!isset($post))
            Add New Post
          @else
            Edit Post
          @endif
      </h2><br/>
      <h3><a href="{{route('post.getAllPost')}}">Back</a></h3>
      <form method="post" action="{{(isset($post['id']) && $post['id'] !== "") ? route('post.editPost', $post['id']) :route('post.createPost')}}" enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="form-group col-md-12 {{ $errors->has('title') ? 'has-error' : '' }}">
        <label for="Name">Title:</label>
        <input type="text" class="form-control" name="title" value="{{(isset($post['title']) && $post['title'] !== "") ? $post['title'] : ""}}">
        <span class="text-danger">{{ $errors->first('title') }}</span>
    </div>
</div>
<div class="row">
  <div class="form-group col-md-12 {{ $errors->has('content') ? 'has-error' : '' }}">
      <label for="Name">Content:</label>
      <textarea class="form-control" name="content" id="summary-ckeditor">{{( isset($post['content']) && $post['content'] !== "") ? $post['content'] : ""}}</textarea>
      <span class="text-danger">{{ $errors->first('content') }}</span>
  </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <input type="file" name="filename">
        @if((isset($post) && $post['filename'] !== ""))
            <a href="{{('http://img.domain/app/images/originals') . '/' . $post->filename}}" rel="prettyPhoto" title="This is the description">
                <img class="img-fluid" src="{{('http://img.domain/app/images/thumbnails') . '/' . $post->filename}}"/>
            </a>
        @endif
        <span class="text-danger">{{ $errors->first('filename') }}</span>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <strong>Date Create: </strong>
        <input class="date form-control"  type="text" id="datepicker" name="date" value="{{( isset($post['created_at']) && $post['created_at'] !== "") ? $post['created_at'] : ""}}">
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4" style="margin-top:60px">
        <button type="submit" class="btn btn-success">Submit</button>
    </div>
</div>
</form>
</div>
<script type="text/javascript">
    $('#datepicker').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
</script>
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace( 'summary-ckeditor' );
    </script>
</body>
</html>