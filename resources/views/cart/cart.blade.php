@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
    <h1 align="center">购物车展示</h1>
    <table class="table-bordered" style="margin-left: 280px;width:700px">
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
        <tr>
            <td colspan="5">
                <form action="/order" method="post">
                    {{csrf_field()}}
                    <input type="submit" style="float: right;" class="btn btn-info" value="结算">
                </form>
            </td>
        </tr>
    </table>
@endsection
@section('footer')
@endsection
