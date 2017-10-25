$(function() {
    function unfolded(rsize) {
        if ($('.OwO').hasClass('OwO-open')) {
            $('#chat-list').css('height', rsize.le);
            $('#chat-footer').css('height', rsize.fe);
        } else {
            $('#chat-list').css('height', rsize.lo);
            $('#chat-footer').css('height', rsize.fo);
        }
    }
    function resize() {
        var s768 = {lo: '85%', fo: '10%', le: '61%', fe: '34%'};
        var s768lt = {lo: '83%', fo: '11%', le: '46%', fe: '48%'};
        var s375lt = {lo: '79%', fo: '13%', le: '36%', fe: '56%'};

        if($(window).width() >= 768) {
            unfolded(s768);
        }else if($(window).width() < 375) {
            unfolded(s375lt);
        }else if($(window).width() < 768) {
            unfolded(s768lt);
        }
    }

    function selfMessage(id) {
        if (user.id == parseInt(id)) {
            return 'chat-me';
        }
        return 'chat-ta';
    }

    function getSexInfo(n) {
        switch (parseInt(n)) {
            case 0:
                return {
                    class: 'female',
                    val: '♀',
                };
            case 1:
                return {
                    class: 'male',
                    val: '♂',
                };
            case 2:
                return {
                    class: 'maf',
                    val: '⚥',
                };
        }
    }

    function send(message, type = 'message') {
        if(message != '') {
            $('.message').val('');
            $('.btn').attr('class', 'btn n');
        }else {
            $('.message').focus();
            return;
        }

        var $data = {
            message: message,
            type: type,
        };

        if(socket.readyState === 3) {
            alert('连接服务器失败，请联系管理员开启~');
            return;
        }
        socket.send(JSON.stringify($data));
    }

    if($(window).width() < 375) {
        $('#chat-list').css('height', '79%');
        $('#chat-footer').css('height','13%');
    }else if($(window).width() < 768) {
        $('#chat-list').css('height', '83%');
        $('#chat-footer').css('height','11%');
    }

    $(".OwO").click(function () {
        resize()
    })

    $(window).resize(function() {
        resize()
    })

    var text;
    $('.message').bind('input propertychange', function() {
        var message = $('.message');
        var val = message.val();
        console.log(val.length);

        (message.val().length > 0) ? $('.btn').attr('class', 'btn y'):$('.btn').attr('class', 'btn n');

        if (message.val().length >= 130) {
            message.val(text);
            alert('输入文字过长');
            return;
        }

        text = val;
    })

    $('.message').focus(function () {
        var message = $('.chat-input>.message');
        var text = message.val();
        message.val(text);

        var btn = $('.btn');
        if(message.val().length > 0) {
            btn.attr('class', 'btn y')
        }else {
            btn.attr('class', 'btn n');
        }
    })

    $('.btn').click(function () {
        send($('.message').val());
    })

    setTimeout(function() {
        $('.OwO-items-image > .OwO-item').click(function(e) {
            var message = $('.chat-input>.message');
            var text = message.val();
            message.val(text);

            if(message.val().length > 0) {
                send(message.val()+$(this).html());
            }else {
                send($(this).html());
            }
        })
    }, 600)

    var socket = new ReconnectingWebSocket('ws://127.0.0.1:9501', null, {debug: true, reconnectInterval: 2000, timeoutInterval: 3000});

    socket.addEventListener('open', function (event) {
        if(!user.id) {
            return;
        }
        if(!user.name) {
            return;
        }
        if(!user.sex) {
            return;
        }
        if(!user.icon) {
            return;
        }
        user.type = 'init';
        socket.send(JSON.stringify(user));
    })

    socket.addEventListener('message', function (event) {
        if(socket.readyState === 1 && lowConnect) {
            //alert('欢迎回来👏');
        }

        var $data = JSON.parse(event.data);
        var $message = $data.message;
        if ($data.type === 'tips') {
            var span = $('<span>').text($message);
            var li = $('<li>').append(span).addClass('chat chat-type-tips');
            $('#chat-list').append(li);
        } else if ($data.type === 'message') {
            var p = $('<p>').html($message).addClass('chat-message');
            var img = $('<img>').attr({'src': $data.icon, 'class': 'chat-img'});
            var name = $('<span>').text($data.name).addClass('chat-name');
            var sexInfo = getSexInfo($data.sex);
            var sex = $('<span>').text(sexInfo.val).addClass('chat-sex ' + sexInfo.class);
            var div = $('<div>').append(name, sex).addClass('chat-info');

            var li = $('<li>').append(img, div, p).addClass('chat chat-type-message ' + selfMessage($data.id));
            $('#chat-list').append(li);
        } else {
            console.log('非法操作');
        }
    })

    var lowConnect = false;
    socket.addEventListener('close', function (event) {
        if(socket.readyState === 0 && lowConnect) {
            alert('连接服务器失败，请联系管理员开启~');
        }else if(socket.readyState === 0) {
            alert('服务器关闭，断开连接辣~');
        }
    })

    socket.addEventListener('error', function (event) {
        lowConnect = true;
    })
})