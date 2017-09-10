<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Title</title>
    <link rel="stylesheet" href="css/chat.css">
    <link rel="stylesheet" href="css/s.css">
    <link rel="stylesheet" href="http://chat.0x1.ren/css/OwO.css">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/OwO.js"></script>
</head>
<body>
<header id="chat-header">
    <h2>标题</h2>
</header>
<ul id="chat-list">
    <!--<li class="chat chat-type-tips">
        <span>系统消息: FUCK进入房间</span>
    </li>
    <li class="chat chat-ta chat-type-message">
        <img class="chat-img" src="http://api.flxxyz.com/qq/icon/2s83ge1?size=40" alt="">
        <div class="chat-info">
            <span class="chat-s s-1">大王</span> <span class="chat-name">FUCK</span> <span class="chat-sex male">♂</span>
        </div>
        <p class="chat-message">这是他的一条消息</p>
    </li>
    <li class="chat chat-type-system">
        <span>10:21</span>
    </li>-->
</ul>
<footer id="chat-footer">
    <div class="chat-input">
        <textarea class="message" type="text"></textarea>
        <button class="btn" type="button">发送</button>
    </div>
    <div class="chat-more">
        <div class="OwO"></div>
    </div>
</footer>

<script>
new OwO({
    logo: '表情',
        container: $('.OwO').get(0),
        target: $('.chat-input>.message').get(0),
        api: 'js/OwO.json',
        position: 'down',
        width: '100%',
        maxHeight: '250px'
    });

    $(".OwO").click(function () {
        if($('.OwO').hasClass('OwO-open')) {
            $('#chat-list').css('height', '46%');
            $('#chat-footer').css('height', '55.55555%');
        }else {
            $('#chat-list').css('height', '92%');
            $('#chat-footer').css('height', '12%');
        }
    })


    $('.message').get(0).addEventListener('propertychange', function () {
        let message = $('.message');
        console.log(message.val().length);
        if(message.val().length >= 130) {
            alert('输入文字过长');
            return;
        }
    })

    var val;
    $('.message').get(0).addEventListener('input', function () {
        let message = $('.message');
        console.log(message.val().length);
        if(message.val().length >= 130) {
            message.val(val);
            alert('输入文字过长');
            return;
        }

        val = message.val();
    })


<?php
$_SESSION['id'] = 2142;
$_SESSION['sex'] = 1;
$_SESSION['name'] = 'jack';
$_SESSION['icon'] = 'http://api.flxxyz.com/qq/icon/2s83ge1?size=40';
?>
    const socket = new WebSocket('ws://192.168.10.10:9501');
    const data = {
        type: 'init',
        id: '<?= $_SESSION['id'] ?>',
        name: '<?= $_SESSION['name'] ?>',
        sex: <?= $_SESSION['sex'] ?>,
        icon: '<?= $_SESSION['icon'] ?>',
    };

    socket.addEventListener('open', function (event) {
        socket.send(JSON.stringify(data));
    });

    socket.addEventListener('message', function (event) {
        let $data = JSON.parse(event.data);
        if($data.type === 'tips') {
            let span = $('<span>').text($data.message);
            let li = $('<li>').append(span).addClass('chat chat-type-tips');
            $('#chat-list').append(li);
        }else if($data.type === 'message') {
            console.log('消息');
        }else {
            console.log('非法操作');
        }

    })

    socket.addEventListener('close', function (event) {
        console.log('服务器关闭，断开连接辣~');
        console.log(event);
    })

    socket.addEventListener('error', function (event) {
        console.log('服务器开小差了，刷新试试咩~');
        console.log(event);
    })

    function send(data, type='message') {
        data.type = type;
        console.log(data);
        socket.send(JSON.stringify(data));
    }

    $('.btn').click(function() {
        let $data = {};
        $data.message = $('.message').val();
        send($data);
    })
</script>
</body>
</html>