@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
    <h1 align="center">商品展示</h1>
    <div align="center">
        <form action="/goodspage" method="post">
            {{csrf_field()}}
            <input type="text" name="goods_name">
            <input type="submit" value="搜索">
        </form>
    </div>
    <table class="table table-bordered" style="margin-left: 220px;width:800px">
        <tr>
            <td>id</td>
            <td>名称</td>
            <td>库存</td>
            <td>操作</td>
        </tr>
        @foreach($data['data'] as $v)
        <tr>
            <td>{{$v['goods_id']}}</td>
            <td>{{$v['goods_name']}}</td>
            <td>{{$v['score']}}</td>
            <td><a role="button" href="/cart/goodslist/{{$v['goods_id']}}" class="btn btn-primary btn-xs">查看</a></td>
        </tr>
        @endforeach
    </table>
    {{$data->links()}}
@endsection
@section('footer')
@endsection

