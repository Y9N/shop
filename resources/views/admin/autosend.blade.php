@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
    <form action="/admin/fasong">
        <input type="text" name="text">
        <input type="submit" value="点击发送">
    </form>
@endsection
@section('footer')
@endsection