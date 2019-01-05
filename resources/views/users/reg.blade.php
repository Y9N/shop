@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
    <h1 align="center">USER REG</h1>
    <form class="form-horizontal" action="/userreg" method="post" style="margin-left: 280px;width:500px">
        {{csrf_field()}}
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" required name="name"  placeholder="Name">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" required name="password"  placeholder="***">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Repassword</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" required name="repassword"  placeholder="***">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" required name="email"  placeholder="@">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Age</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" required name="age"  placeholder="1-100">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Sign in</button>
            </div>
        </div>
    </form>
@endsection
@section('footer')
@endsection

