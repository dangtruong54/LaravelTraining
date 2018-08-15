<?php

/**
 * Created by PhpStorm.
 * User: truon
 * Date: 8/7/2018
 * Time: 9:03 AM
 */
?>
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
    <h3><a href="<?php echo url('/passports'); ?>">Home</a></h3>
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
            <th>Name</th>
            <th>Image</th>
            <th>Date</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Passport Office</th>
            <th colspan="2">Action</th>
        </tr>
        </thead>
        <tbody>

        @foreach($passports as $passport)
            @php
                $date=date('Y-m-d', $passport['date']);
            @endphp
            <tr>
                <td>{{$passport['id']}}</td>
                <td>{{$passport['name']}}</td>
                <td>
                    <img class="img-fluid" src="{{URL::asset('/images') . '/' . $passport->filename}}"/>
                </td>
                <td>{{$date}}</td>
                <td>{{$passport['email']}}</td>
                <td>{{$passport['number']}}</td>
                <td>{{$passport['office']}}</td>

                <td><a href="{{action('PassportController@create')}}" class="btn btn-success">Add</a></td>
                <td><a href="{{action('PassportController@edit', $passport['id'])}}" class="btn btn-warning">Edit</a>
                </td>
                <td>
                    <form action="{{action('PassportController@destroy', $passport['id'])}}" method="post">
                        @csrf
                        <input name="_method" type="hidden" value="DELETE">
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <nav aria-label="Page navigation">
        {{ $passports->links() }}
    </nav>
</div>
</body>
</html>