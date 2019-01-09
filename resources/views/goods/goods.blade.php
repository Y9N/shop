@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
    <h1 align="center">商品展示</h1>
    <table class="table-bordered" style="margin-left: 280px;width:800px">
        <tr>
            <td>id</td>
            <td>名称</td>
            <td>库存</td>
            <td>操作</td>
        </tr>
        @foreach($array as $v)
        <tr>
            <td>{{$v['goods_id']}}</td>
            <td>{{$v['goods_name']}}</td>
            <td>{{$v['score']}}</td>
            <td><a href="/cart/goodslist/{{$v['goods_id']}}">查看</a></td>
        </tr>
        @endforeach
    </table>
@endsection
@section('footer')
@endsection

