# php-chat-websocket

这是一个基础聊天室，使用session区分各个用户，使用identicon来做随机头像

ps:不清楚identicon的可以看看[identicon wiki][1]

## Init
```shell
cd ci
composer update
```

## Usage
修改```ci/application/config/config.php```为:
```
$config['base_url'] = "你的DOMAIN";
```

## Run
```shell
php websocket.php  // 开启websocket服务器
```

[1]: http://en.wikipedia.org/wiki/Identicon