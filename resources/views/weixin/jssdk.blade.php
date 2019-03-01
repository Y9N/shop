jssdk
<script src="https://res2.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
    wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: "{{$jssdk['appid']}}", // 必填，公众号的唯一标识
        timestamp: {{$jssdk['timestamp']}}, // 必填，生成签名的时间戳
        nonceStr: "{{$jssdk['noncestr']}}", // 必填，生成签名的随机串
        signature: "{{$jssdk['sign']}}",// 必填，签名
        jsApiList: ['chooseImage','uploadImage','getLocalImgData','startRecord'] // 必填，需要使用的JS接口列表
    });
</script>
