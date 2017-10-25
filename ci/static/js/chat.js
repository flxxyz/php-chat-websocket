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
        let s768 = {lo: '85%', fo: '10%', le: '61%', fe: '34%'};
        let s768lt = {lo: '83%', fo: '11%', le: '46%', fe: '48%'};
        let s375lt = {lo: '79%', fo: '13%', le: '36%', fe: '56%'};

        if($(window).width() >= 768) {
            unfolded(s768);
        }else if($(window).width() < 375) {
            unfolded(s375lt);
        }else if($(window).width() < 768) {
            unfolded(s768lt);
        }
    }

    function selfMessage(id) {
        if (data.id == parseInt(id)) {
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
        let $data = {
            message: message,
            type: type,
        };
        $('.message').val('');
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

    let text;
    $('.message').bind('input propertychange', function() {
        let message = $('.message');
        let val = message.val();
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
        let message = $('.chat-input>.message');
        let text = message.val();
        message.val(text);

        let btn = $('.btn');
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
            let message = $('.chat-input>.message');
            let text = message.val();
            message.val(text);

            if(message.val().length > 0) {
                send(message.val()+$(this).html());
            }else {
                send($(this).html());
            }
        })
    }, 600)

    const socket = new WebSocket('ws://192.168.10.10:9501');

    socket.addEventListener('open', function (event) {
        if(socket.readyState === 3) {
            alert('连接服务器失败，请联系管理员开启~');
            return;
        }
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
    });

    socket.addEventListener('message', function (event) {
        let $data = JSON.parse(event.data);
        let $message = $data.message;
        if ($data.type === 'tips') {
            let span = $('<span>').text($message);
            let li = $('<li>').append(span).addClass('chat chat-type-tips');
            $('#chat-list').append(li);
        } else if ($data.type === 'message') {
            let p = $('<p>').html($message).addClass('chat-message');
            let img = $('<img>').attr({'src': $data.icon, 'class': 'chat-img'});
            let name = $('<span>').text($data.name).addClass('chat-name');
            let sexInfo = getSexInfo($data.sex);
            let sex = $('<span>').text(sexInfo.val).addClass('chat-sex ' + sexInfo.class);
            let div = $('<div>').append(name, sex).addClass('chat-info');

            let li = $('<li>').append(img, div, p).addClass('chat chat-type-message ' + selfMessage($data.id));
            $('#chat-list').append(li);
        } else {
            console.log('非法操作');
        }
    })

    socket.addEventListener('close', function (event) {
        //console.log(event);
        if(socket.readyState === 3) {
            alert('服务器关闭，断开连接辣~');
            return '';
        }
    })

    socket.addEventListener('error', function (event) {
        //console.log(event);
        if(socket.readyState === 3) {
            alert('服务器开小差了，刷新试试咩~');
            return false;
        }
    })
})