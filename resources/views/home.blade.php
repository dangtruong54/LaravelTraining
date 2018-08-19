@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">List User</div>
                    You are logged in!

                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">PassWord</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <th scope="row">{{$user->id}}</th>
                                <td><a href="{{route('user.getEdit',$user->id)}}">{{$user->username}}</a></td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->password}}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ route('user.getEdit', $user->id) }}">
                                            <button type="button" class="btn btn-warning">Edit</button>
                                        </a>&nbsp;
                                        <form action="{{route('user.postDelete', $user->id)}}" method="post">
                                            {!! csrf_field() !!}
                                            <input type="submit" class="btn btn-danger" value="Delete"/>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-right" colspan="6"><button class="btn btn-success"><a href="/register">Add</a></button></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
