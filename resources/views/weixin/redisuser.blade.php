<form action="">
<table>
    <tr>
        <td></td>
        <td>openid</td>
        <td style="padding: 5px">添加时间</td>
        <td style="padding: 5px">用户昵称</td>
        <td style="padding: 5px">性别</td>
        <td style="padding: 5px">头像</td>
        <td style="padding: 5px">关注时间</td>
    </tr>
@foreach($userinfo as $v)
        <tr>
            <td><input type="checkbox" value="{{$v['openid']}}"></td>
            <td>{{$v['openid']}}</td>
            <td style="padding: 5px">{{$v['add_time']}}</td>
            <td style="padding: 5px">{{$v['nickname']}}</td>
            <td style="padding: 5px">{{$v['sex']}}</td>
            <td style="padding: 5px"><img src="{{$v['headimgurl']}}" width="100"></td>
            <td style="padding: 5px">{{$v['subscribe_time']}}</td>
        </tr>
@endforeach
</table>
    @foreach($sign as $v)
        <input type="button" class="btn" value="{{$v['name']}}">
    @endforeach
</form>
<script src="{{URL::asset('/js/jquery-3.2.1.min.js')}}"></script>
<script>
    $(function(){
        $('.btn').click(function(){
            console.log(111)
        })
    })
</script>