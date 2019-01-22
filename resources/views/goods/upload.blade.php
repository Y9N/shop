@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
    <form action="/PDF" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="file" name="pdf">
        <input type="submit" value="提交">
    </form>
@endsection
@section('footer')
@endsection

