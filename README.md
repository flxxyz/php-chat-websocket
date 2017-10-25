# php-chat-websocket

这是一个基础聊天室，使用session区分各个用户，使用identicon来做随机头像

ps:不清楚identicon的可以看看[identicon wiki][1]

## Init
```shell
cd ci
composer update
```

## Usage
1. 修改 `ci/application/config/config.php` 26行
   ```php
   $config['base_url'] = "你的域名";
   ```

2. 修改`ci/static/js/chat.js` **129行**为websocket链接🔗
   ```javascript
   const socket = new WebSocket('ws://你的域名:9501');
   ```


## Run
```shell
php websocket.php  // 开启websocket服务器
```

[1]: http://en.wikipedia.org/wiki/Identicon