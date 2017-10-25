<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Title</title>
    <link rel="stylesheet" href="/static/css/chat.css">
    <link rel="stylesheet" href="/static/css/s.css">
    <link rel="stylesheet" href="/static/css/OwO.css">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/static/js/OwO.js"></script>
    <script src="/static/js/chat.js"></script>
    <script src="/static/js/Reconnect.js"></script>
</head>
<body>
<header id="chat-header">
    <h2>标题</h2>
</header>
<ul id="chat-list">
    <!--
    <li class="chat chat-type-tips">
        <span>系统消息: FUCK进入房间</span>
    </li>
    <li class="chat chat-type-system">
        <span>10:21</span>
    </li>
    <li class="chat chat-ta chat-type-message">
        <img class="chat-img" src="http://api.flxxyz.com/qq/icon/2s83ge1?size=40" alt="">
        <div class="chat-info">
            <span class="chat-s s-1">大王</span> <span class="chat-name">FUCK</span> <span class="chat-sex male">♂</span>
        </div>
        <p class="chat-message">这是他的一条消息</p>
    </li>
    -->
</ul>
<footer id="chat-footer">
    <div class="chat-input">
        <textarea class="message" type="text"></textarea>
        <button class="btn n" type="button">发送</button>
    </div>
    <div class="chat-more">
        <div class="OwO"></div>
        <script>
            new OwO({
                logo: '表情',
                container: $('.OwO').get(0),
                target: $('.chat-input>.message').get(0),
                api: '/static/js/OwO.json',
                position: 'down',
                width: '100%',
                maxHeight: '250px'
            });
        </script>
    </div>
</footer>

<script>
    var user = {
        id: '<?= $sess['id'] ?>',
        name: '<?= $sess['name'] ?>',
        sex: '<?= $sess['sex'] ?>',
        icon: '<?= $sess['icon'] ?>',
    };
</script>
</body>
</html>