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
            <td style="padding: 5px">{{$v['add_time']}}</td>
            <td style="padding: 5px">{{$v['nickname']}}</td>
            <td style="padding: 5px">{{$v['sex']}}</td>
            <td style="padding: 5px"><img src="{{$v['headimgurl']}}" width="100"></td>
            <td style="padding: 5px">{{$v['subscribe_time']}}</td>
        </tr>
@endforeach
</table>