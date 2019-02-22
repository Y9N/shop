{{--@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')--}}{{--
<div align="center" style="padding-right: 20px;"><br><b>{{$name}}</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}<img src="{{$head}}" width="100px">
<div style="padding-top: 240px">
<form action="touser" method="post">
        {{csrf_field()}}
        <textarea name="text" id="" cols="200" rows="5"></textarea>
        <input type="hidden" value="{{$openid}}" name="openid">
        <input type="submit" value="发送">
    </form>
</div>
{{--</div>--}}
{{--
@endsection
@section('footer')
@endsection--}}
