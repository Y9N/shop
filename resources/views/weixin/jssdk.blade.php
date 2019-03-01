@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
    <button id="btn">上传图片</button>
    <button id="sys">微信扫一扫</button>
@endsection
@section('footer')
@endsection

<script src="{{URL::asset('/js/jquery-3.2.1.min.js')}}"></script>
<script src="https://res2.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
    wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: "{{$jssdk['appid']}}", // 必填，公众号的唯一标识
        timestamp: {{$jssdk['timestamp']}}, // 必填，生成签名的时间戳
        nonceStr: "{{$jssdk['noncestr']}}", // 必填，生成签名的随机串
        signature: "{{$jssdk['sign']}}",// 必填，签名
        jsApiList: ['chooseImage','uploadImage','getLocalImgData','startRecord','scanQRCode','updateAppMessageShareData'] // 必填，需要使用的JS接口列表
    });
    wx.ready(function(){
        $('#btn').click(function(){
            wx.chooseImage({
                count: 1, // 默认9
                sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                success: function (res) {
                    var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                }
            });
        })
        $('#sys').click(function(){
            wx.scanQRCode({
                needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                success: function (res) {
                    var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                }
            });
        })
        $('#share').click(function(){
            wx.updateAppMessageShareData({
                title: '李香玉吃屁', // 分享标题
                desc: '李香玉吃屁吃屁', // 分享描述
                link: 'https://yc.qianqianya.xyz', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'http://img3.imgtn.bdimg.com/it/u=599861680,1215723695&fm=11&gp=0.jpg', // 分享图标
                success: function () {
                    // 设置成功
                }
            })
        })
    })
</script>

