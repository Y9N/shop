{{--@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')--}}
    <form action="/admin/fasong" method="post">
        {{csrf_field()}}
        <input type="text" name="text">
        <input type="submit" value="点击发送">
    </form>
{{--
@endsection
@section('footer')
@endsection--}}
