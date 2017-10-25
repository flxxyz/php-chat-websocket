<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>首页</title>
</head>
<body>
<form action="<?= $action ?>" method="get">
    <div>
        <input type="hidden" name="c" value="home">
        <input type="hidden" name="m" value="login">
        <label for="">名字: </label>
        <input type="text" name="username">
    </div>
    <div>
        <label for="">性别: </label>
        <select name="sex">
            <option value="1">男</option>
            <option value="0">女</option>
            <option value="2">双性</option>
        </select>
    </div>
    <div>
        <button type="submit">保存</button>
    </div>
</form>
</body>
</html>