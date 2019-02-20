@extends('layout.mama'){{--文件夹名.文件名--}}

@section('title'){{$title}}@endsection

@section('header')
    @parent
    <p style="color: #98e1b7">This is children top...</p>
@endsection

@section('content')
    <i>This is children center...</i>
    <table border="1">
        <tr>
            <td>id</td><td>name</td><td>age</td>
        </tr>
        @foreach($list as $v)
        <tr>
            <td>{{$v['id']}}</td>
            <td>{{$v['name']}}</td>
            <td>{{$v['age']}}</td>
        </tr>
        @endforeach
    </table>
@endsection


@section('footer')
    <p style="color: #98e1b7">This is chldren footer...</p>
    @parent
@endsection