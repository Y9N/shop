@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
    <h1 align="center">购物车展示</h1>
    <table class="table table-bordered" style="margin-left: 280px;width:700px">
        <tr>
            <td>id</td>
            <td>订单编号</td>
            <td>订单金额</td>
            <td>添加时间</td>
            <td>操作</td>
        </tr>
        @foreach($orderdata as $v)
        <tr>
            <td>{{$v['order_id']}}</td>
            <td>{{$v['order_number']}}</td>
            <td>￥{{$v['order_amount']}}</td>
            <td>{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
            <td>
                <a   role="button" href="#" class="btn btn-success btn-xs">去支付</a>
                <a   role="button" href="/orderdel/{{$v['order_id']}}" class="btn btn-danger btn-xs">取消订单</a>
            </td>
        </tr>
        @endforeach
    </table>
@endsection
@section('footer')
@endsection
