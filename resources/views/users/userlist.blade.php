@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
    <h3>欢迎{{$name}}来到：</h3>
                     <h4 style="margin-left: 100px">个人中心</h4>
    我的积分：{{$integral}} 积分  （1分=1积分）
@endsection
@section('footer')
@endsection

