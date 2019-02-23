<script src="{{URL::asset('/js/jquery-3.2.1.min.js')}}"></script>
<img src="{{$head}}" width="100px">
<div>
    <table>
        <thead class="head">
        @foreach($array as $v)
        <tr>
            <td>{{date('Y-m-d h:i:s',$v['add_time'])}}</td>
            <td></td>
        </tr>
        <tr>
            <td>{{$name}}：</td>
            <td>{{$v['massage']}}</td>
        </tr>
        @endforeach
        </thead>
    </table>
</div>
<div style="float: right" id="kefu">

</div>
<div style="padding-top: 240px">
<form action="touser" method="post">
        {{csrf_field()}}
        <textarea name="text"  cols="200" rows="5" id="text"></textarea>
        <input type="hidden" value="{{$openid}}" name="openid" id="openid">
        <input type="button" value="发送" id="sub">
    </form>
</div>
<script>
    //setTimeout("window.location.reload()",1000);
    //setTimeout(function(){t()}, 3000);
    $(function(){
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        $('#sub').click(function(){
            var text=$('#text').val();
            var openid=$('#openid').val();
            $.post(
                    "touser",
                    {text:text,openid:openid},
                    function(msg){
                        if(msg=='发送成功'){
                            $('#kefu').append('<p>'+text+'：客服</p>');
                            $('#text').val('');
                        }else{
                            alert('msg');
                        }
                    }
            )
        })
        var newmsg=function(){
            var openid=$('#openid').val();
            var _tr=""
            $.get(
                    "usermsg?openid="+openid,
                    function(msg){
                        //console.log(msg)
                        for(var i in msg['array']) {
                            _tr +="<tr>"+
                                    "<td>"+msg['array'][i]['add_time']+"</td>" +
                                    "<td></td>" +
                                    "</tr><tr>"+"<td>"+msg['name']+"：</td>" +
                                    "<td>"+msg['array'][i]['massage']+"</td>" +
                                    "</tr>"
                        }
                        $('.head').html(_tr)
                    },'json'
            )
        }
        var s= setInterval(function(){
            newmsg();
        }, 1000*3)
    })
</script>
