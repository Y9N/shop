
<table border="1" align="center" width="1200" height="100">
    <tr>
        <td>id</td>
        <td>姓名</td>
        <td>身份证号</td>
        <td>接口用途</td>
        <td>申请次数</td>
        <td>是否通过</td>
        <td>操作</td>
    </tr>
@foreach($data as $k=>$v)
        <tr>
            <td>{{$v['id']}}</td>
            <td>{{$v['name']}}</td>
            <td>{{$v['number']}}</td>
            <td>{{$v['yongtu']}}</td>
            <td>{{$v['reg_num']}}</td>
            <td>
                @if($v['is_pass']==1)
                待审核
                @elseif($v['is_pass']==2)
                通过
                @elseif($v['is_pass']==3)
                未通过
                @endif
            </td>
            <td><a href="pass?id={{$v['id']}}">通过</a>||<a href="nopass?id={{$v['id']}}">不通过</a></td>
        </tr>
@endforeach
</table>