<table>
    <tr>
        <td>openid</td>
        <td>添加时间</td>
        <td>用户昵称</td>
        <td>性别</td>
        <td>头像</td>
        <td>关注时间</td>
    </tr>
@foreach($userinfo as $v)
        <tr>
            <td>{{$v['openid']}}</td>
            <td>{{$v['add_time']}}</td>
            <td>{{$v['nickname']}}</td>
            <td>{{$v['sex']}}</td>
            <td><img src="{{$v['headimgurl']}}" width="100"></td>
            <td>{{$v['subscribe_time']}}</td>
        </tr>
@endforeach
</table>