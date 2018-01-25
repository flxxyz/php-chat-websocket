# php-chat-websocket

这是一个基础聊天室，使用session区分各个用户，使用identicon来做随机头像

ps:不清楚identicon的可以看看[identicon wiki][1]

## Init
依次执行下面命令
```shell
git clone https://github.com/flxxyz/php-chat-websocket.git
cd php-chat-websocket
composer update --no-dev
```

## Usage
1. 修改 `config/config.php` 4行
   ```php
   'url' => 'http://domain/',  //保留最后的斜杠
   ```

2. 修改`public/static/js/chat.js` **137行**为websocket链接🔗
   ```javascript
   var socket = new ReconnectingWebSocket('ws://你的域名:9501', null, {debug: false, reconnectInterval: 2000, timeoutInterval: 3000});
   ```

3. 自定义ip端口号
   ```php
   Chat::run('自定义ip', '自定义端口号');
   ```

4. 开启websocket服务器
   ```shell
   php run.php  // 同步检查控制台输出信息
   ```
   
   如需要后台挂起服务看这里↓
   ```shell
   chmod +x run.sh
   ./run.sh
   ```

5. 伪静态设置
   nginx和apache需要设置伪静态规则，在这里只提供nginx的规则，apache不熟
   ```
   location / {
       try_files $uri $uri/ /index.php$is_args$query_string;
   }
   ```


## Route
| 路径 | 说明 |
|:---  |:---   |
| /   | 首页界面 |
| /login | 登陆界面 |
| /logout | 注销当前登陆用户  |
| /room | 房间界面  |



[1]: http://en.wikipedia.org/wiki/Identicon