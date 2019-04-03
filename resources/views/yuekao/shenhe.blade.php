<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
提交成功，待审核中。。。。
</body>
</html>
<script src="/js/jquery-3.2.1.min.js"></script>
<script>
    $(function(){
        var newmsg=function(){
            var uid={{$id}}
            $.post(
                    'http://shop.com/reg_do',
                    {id:uid},
                    function(msg){
                        //console.log(msg)
                        if(msg.error==0){
                            alert("app_key:"+msg.app_key+"&&app_secret:"+msg.app_secret)
                        }else if(msg.error==400){
                            alert("审核未通过原因："+msg.nopass)
                        }
                    },
                    'json'
            )
        }
        var s= setInterval(function(){
            newmsg();
        }, 1000*3)
    })
</script>