@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
   <input type="hidden" value="{{$code_url}}" id="code_url">
   <input type="hidden" value="{{$order_id}}" id="order_id">
   <div id="code" align="center"></div>
@endsection
@section('footer')
@endsection
<script src="{{URL::asset('/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{URL::asset('/bootstrap/js/jquery.qrcode.min.js')}}"></script>
<script>
   $(function(){
       var code_url=$('#code_url').val()
       var order_id=$('#order_id').val()
      console.log(code_url)
      $("#code").qrcode({
         render: "canvas", //table方式
         width: 200, //宽度
         height:200, //高度
         text:code_url //任意内容
      });
      $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
      var ifsuccess=function(){
         $.post(
                 "/weixin/pay/ifsuccess",
                 {order_id:order_id},
                 function(msg){
                    if(msg==1){
                       location.href='/weixin/pay/success'
                    }
                 }
         )
      }
      var s= setInterval(function(){
         ifsuccess();
      }, 1000*3)
   })

</script>
