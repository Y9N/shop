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
{{--@foreach($userinfo as $v)--}}
        <tr>
            <td><input type="checkbox" value="{{$openid}}"></td>
            <td>{{$openid}}</td>
            <td style="padding: 5px">{{$add_time}}</td>
            <td style="padding: 5px">{{$nickname}}</td>
            <td style="padding: 5px">{{$sex}}</td>
            <td style="padding: 5px"><img src="{{$headimgurl}}" width="100"></td>
            <td style="padding: 5px">{{$subscribe_time}}</td>
        </tr>
{{--@endforeach--}}
</table>
    @foreach($sign as $v)
        <input type="button" class="btn" value="{{$v['id']}}">
    @endforeach
</form>
<script src="{{URL::asset('/js/jquery-3.2.1.min.js')}}"></script>
<script>
    $(function(){
        $('.btn').click(function(){
            var _this=$(this)
            var id=_this.val()
            var openid={{$openid}}
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
            $.post(
                    "sign",
                    {id:id,openid:openid},
                    function(msg){
                        console.log(msg)
                    }
            )
        })
    })
</script>