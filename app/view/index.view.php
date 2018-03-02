<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>首页 | php-chat-websockt</title>
</head>
<body>
<form action="/login" method="get">
    <div>
        <label for="">名字: </label>
        <input type="text" name="username">
        <span>注: 3-12位</span>
    </div>
    <div>
        <label for="">性别: </label>
        <select name="sex">
            <option value="0">女</option>
            <option value="1">男</option>
            <option value="2">双性</option>
        </select>
    </div>
    <div>
        <button type="submit">保存</button>
    </div>
</form>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script>
    $('form').submit(function () {
        var username = $('input[name=username]').val().length;
        if (username <= 2 || username > 12) {
            alert('请输入名字！');
            return false;
        }

        return true;
    });
</script>
</body>
</html>