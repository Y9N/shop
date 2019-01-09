@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
    <h1 align="center">购物车展示</h1>
    <table class="table-bordered" style="margin-left: 280px;width:800px">
        <tr>
            <td>id</td>
            <td>商品名称</td>
            <td>购买数量</td>
            <td>添加时间</td>
            <td>操作</td>
        </tr>
        @foreach($array as $v)
        <tr>
            <td>{{$v['goods_id']}}</td>
            <td>{{$v['goods_name']}}</td>
            <td>{{$v['buy_num']}}</td>
            <td>{{$v['add_time']}}</td>
            <td><a href="/cart/del2/{{$v['goods_id']}}">删除</a></td>
        </tr>
        @endforeach
    </table>
@endsection
@section('footer')
@endsection
