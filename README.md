# php-chat-websocket

这是一个基础聊天室，使用session区分各个用户，使用identicon来做随机头像

ps:不清楚identicon的可以看看[identicon wiki][1]

## Init
依次执行下面命令
```shell
cd php-chat-websocket
composer update --no-dev
cd public
composer update --no-dev
```

## Usage
1. 修改 `ci/application/config/config.php` 26行
   ```php
   $config['base_url'] = "你的域名";
   ```

2. 修改`ci/static/js/chat.js` **137行**为websocket链接🔗
   ```javascript
   var socket = new ReconnectingWebSocket('ws://你的域名:9501', null, {debug: false, reconnectInterval: 2000, timeoutInterval: 3000});
   ```

3. 自定义ip端口号
   ```php
    Chat::run('自定义ip', '自定义端口号');
   ```

## Run
```shell
php run.php  // 开启websocket服务器
```

[1]: http://en.wikipedia.org/wiki/Identicon