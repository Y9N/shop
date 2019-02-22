{{--@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')--}}
<img src="{{$head}}">
    <form action="touser" method="post">
        {{csrf_field()}}
        <input type="text" name="text">
        <input type="hidden" value="{{$openid}}" name="openid">
        <input type="submit" value="发送">
    </form>
{{--
@endsection
@section('footer')
@endsection--}}
