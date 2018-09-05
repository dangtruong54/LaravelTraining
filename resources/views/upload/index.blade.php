<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Uploading</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Styles -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('css/prettyPhoto.css')}}" type="text/css" media="screen" charset="utf-8" />
    <script src="{{asset('js/jquery.prettyPhoto.js')}}" type="text/javascript" charset="utf-8"></script>
    <style>

        .container {
            margin-top: 2%;
        }

        #list-image img {
            max-width: 300px;
            display: inline-block;
            padding: 10px;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="row">

        <div class="col-md-2"><img src="{{'/images/32114.svg'}}" width="80"/></div>

        <div class="col-md-8"><h2>Upload MultiFile</h2>

        </div>

    </div>

    <br>

    <div class="row">

        <div class="col-md-12">
            @if (count($errors) > 0)

                <div class="alert alert-danger">

                    <ul>

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif
        </div>

        <div class="col-md-4">

            <form class="form_upload" method="post" action="{{route('post.postUpload')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <label for="Product Name">Multi photos (can attach more than one):</label>

                <br/>

                <input type="file" class="form-control file_upload" name="filename[]" multiple required accept="image/png, image/jpeg, image/gif, image/jpg"/>
                <br/><br/>
                <input type="submit" class="btn btn-primary" value="Upload" />

            </form>
            <div id="list-image">

            </div>

        </div>
        <div class="col-md-8">
            @if(isset($listImage) && count($listImage) > 0)
                <div class="image-wrap">
                    @foreach($listImage as $item)
                        <a href="{{('http://img.domain/app/images/upload') . '/' . $item->filename}}" rel="prettyPhoto" title="This is the description">
                            <img style="max-width: 200px" class="img-fluid" src="{{('http://img.domain/app/images/upload') . '/' . $item->filename}}"/>
                        </a>
                    @endforeach
                </div>
                <nav aria-label="Page navigation">
                    {{ $listImage->links() }}
                </nav>
            @endif
        </div>

    </div>

</div>

</body>
<script>
    $('document').ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function viewImageFromClient(input) {
            var element;
            var ValidImageTypes = ["image/gif", "image/jpeg", "image/png","image/jpg"];
            var arrFiles = Array.from(input.files);
            $('#list-image').html('');

            if(input.files.length <= 5) {
                for(element = 0; element < input.files.length; element++) {
                    if ($.inArray(input.files[element].type, ValidImageTypes) > 0) {

                        if (input.files[element].size > 1048576) {
                            // arrFiles.splice(element, 1);
                            $('#list-image').append('<span  style="color: red">File ' + element + ' is larger than 1MB!<br></span>');
                        } else {
                            var reader = new FileReader();
                            reader.onload = function(e){
                                var output = document.createElement("img");
                                var wrap = document.getElementById("list-image");
                                wrap.appendChild(output).setAttribute('src', e.target.result);
                                console.log(e.target);
                                $('<input>').attr({
                                    type: 'hidden',
                                    name: 'image' + $.now(),
                                    val: e.target.result
                                }).appendTo('form');

                            };

                            reader.readAsDataURL(input.files[element]);

                        }
                    }else {
                        $('#list-image').append('<span style="color: red">File ' + element + ' is not file image!</span><br>');
                        // arrFiles.splice(element, 1);
                    }
                }

            } else $('#list-image').append('<span style="color: red">Please choose less than 6 items</span><br>');

            // uploadFile(arrFiles);

            // console.log(document.getElementsByClassName("file_upload"));
            console.log(arrFiles);
        }

        function uploadFile(arrFiles) {

            $(".btn-primary").click(function (e) {
                e.preventDefault();

                var formData = new FormData();
                for (var i = 0; i < arrFiles.length; i++) {
                    formData.append("files", arrFiles[i]);
                }
                // console.log(arrFiles);
                console.log(formData.getAll('files'));
                $.ajax({
                    url: "{{route('post.postUpload')}}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: "POST",
                    success: function (data) {
                        console.log(data)
                    },
                    error: function (data) {
                        alert("ERROR - " + data.responseText);
                    }
                });
            });
        };

        $(":file").change(function() {
            viewImageFromClient(this);
        });
    })
</script>

</html>