@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
    <form action="sendmsg" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="text" name="aaa"><br>
        <input type="file" name="media"><br>
        <input type="submit" value="SUBMIT">
    </form>
@endsection
@section('footer')
@endsection