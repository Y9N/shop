@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
    <h1 align="center">商品详情</h1>
    <form action="/cart/add2" method="post" class="form-inline">
        {{csrf_field()}}
    商品名称：{{$goods_name}}<br>
    商品价格：{{$goods_price}}<br>
    商品库存：{{$score}}<br>
    输入购买数量 <input type="text" required name="buy_num" placeholder="1-{{$score}}">
    <input type="hidden" value="{{$goods_id}}" name="goods_id">
    <input type="submit" value="加入购物车">
    </form>
    <a role="button" href="/cart2" class="btn btn-primary btn-xs">查看购物车</a>
@endsection
@section('footer')
@endsection

