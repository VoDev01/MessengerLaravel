import $ from 'jquery';

const chatName = $('#chat-name').val();
let allMessagesLoaded = false;


if ($('#chat-visibility').val() === 'PUBLIC') {

    window.Echo.private('chat.' + chatName)
        .listen('.message.sent', (data) => {
            let time = new Date(data.message.created_at).toLocaleTimeString('ru-RU', { timeZone: 'Europe/Moscow', hour: '2-digit', minute: '2-digit' });
            $('#message-box').append(`
                <div class="foreign-message" data-message-timestamp="${data.message.created_at}" data-message-id="${data.message.id}">
                    <p> 
                        ${data.message.sender.name} 
                    </p>
                    <p> 
                        ${data.message.text}
                    </p>
                    <p> 
                        ${time} 
                    </p>
                </div>
            `);
        })
        .listen('.message.delivered', (data) => {
            $(`.self-message[data-message-id="${data.messageId}"] p:last`).find('i').remove();
            $(`.self-message[data-message-id="${data.messageId}"] p:last`).html($('.self-message:last p:last').html() + ' <i class="bi bi-check2-all"></i>');
        })
        .listen('.message.seen', (data) => {
            data.messageIds.forEach(messageId => {
                $(`.self-message[data-message-id="${messageId}"] p:last`).find('i').remove();
                $(`.self-message[data-message-id="${messageId}"] p:last`).html($('.self-message:last p:last').html() +
                    ' <i class="bi bi-check2-all text-primary"></i>');
            });
        });

} else if ($('#chat-visibility').val() === 'PRIVATE') {
    window.Echo.private('chat.private.' + chatName)
        .listen('.private.message.sent', (data) => {
            let time = new Date(data.message.created_at).toLocaleTimeString('ru-RU', { timeZone: 'Europe/Moscow', hour: '2-digit', minute: '2-digit' });
            $('.self-message:last i').remove();
            $('.self-message:last :last-child').after('<i class="bi bi-check2-all"></i>');
            $('#message-box').append(`
                <div class="foreign-message" data-message-timestamp="${data.message.created_at}" data-message-id="${data.message.id}">
                    <p> 
                        ${data.message.sender.name} 
                    </p>
                    <p> 
                        ${data.message.text}
                    </p>
                    <p> 
                        ${time} 
                    </p>
                </div>
            `);
        })
        .listen('.private.message.delivered', (data) => {
            $(`.self-message[data-message-id="${data.messageId}"] p:last i`).remove();
            $(`.self-message[data-message-id="${data.messageId}"] :last-child`).html($('.self-message:last :last-child').html() + ' <i class="bi bi-check2-all"></i>');
        });
}

$(window).on('load', function () {
    if ($('.foreign-message').length != 0) {
        let elementsOnPage = [];
        let windowHeight = $(window).height();
        let scrollTop = $(window).scrollTop();
        $('.foreign-message').each(function () {
            let element = $(this);
            let offset = element.offset();
            let elementHeight = element.outerHeight();

            if (offset.top >= scrollTop && (offset.top + elementHeight) <= (scrollTop + windowHeight)) {
                elementsOnPage.push($(element).data('message-id'));
            }
        });
        elementsOnPage = JSON.stringify(elementsOnPage);
        $.ajax({
            method: 'POST',
            url: '/chat/' + chatName + '/seen',
            dataType: 'json',
            data: {
                messageIds: elementsOnPage
            },
            error: function (e) {
                console.log(e);
            }
        })
    }
});

$(window).on('scroll', function () {
    if ($('.foreign-message').length != 0) {
        let elementsOnPage = [];
        let windowHeight = $(window).height();
        let scrollTop = $(window).scrollTop();
        $('.foreign-message').each(function () {
            let element = $(this);
            let offset = element.offset();
            let elementHeight = element.outerHeight();

            if (offset.top >= scrollTop && (offset.top + elementHeight) <= (scrollTop + windowHeight)) {
                elementsOnPage.push($(element).data('message-id'));
            }
        });
        elementsOnPage = JSON.stringify(elementsOnPage);
        $.ajax({
            method: 'POST',
            url: '/chat/' + chatName + '/seen',
            dataType: 'json',
            data: {
                messageIds: elementsOnPage
            },
            error: function (e) {
                console.log(e);
            }
        })
    }
    if ($(window).scrollTop() + $(window).height() == $(document).height()) {
        if (!allMessagesLoaded) {
            $.ajax({
                method: 'GET',
                url: '/chat/' + chatName,
                dataType: 'json',
                data: {
                    lastMessageTime: $('#message-box div:last-child').data('message-timestamp')
                },
                error: function (e) {
                    console.log(e);
                },
                success: function (data) {
                    if (data.messages == null) {
                        allMessagesLoaded = true;
                        return;
                    }
                    data.messages.forEach(message => {
                        let time = new Date(message.created_at).toLocaleTimeString('ru-RU', { timeZone: 'Europe/Moscow', hour: '2-digit', minute: '2-digit' });
                        let messageStatus = '<i class="bi bi-three-dots"></i>';
                        switch (message.status) {

                            case 'PROCESSING':
                                messageStatus = '<i class="bi bi-three-dots"></i>';
                                break;
                            case 'SENT':
                                messageStatus = '<i class="bi bi-check2"></i>';
                                break;
                            case 'DELIVERED':
                                messageStatus = '<i class="bi bi-check2-all"></i>';
                                break;
                            case 'SEEN':
                                messageStatus = '<i class="bi bi-check2-all text-primary"></i>';
                                break;
                            case 'NOT_SENT':
                                messageStatus = '<i class="bi bi-exclamation-circle"></i>';
                                break;
                        }
                        if (message.sender.id == data.currentUserId) {
                            $('.self-message').last().after(`
                            <div class="self-message" data-message-timestamp="${message.created_at}" data-message-id="${message.id}">
                                <p> 
                                    ${message.sender.name} 
                                </p>
                                <p> 
                                    ${message.text}
                                </p>
                                <p> 
                                    ${time} ${messageStatus}
                                </p>
                            </div>
                            `);
                        }
                        else {
                            $('.foreign-message').last().after(`
                            <div class="foreign-message" data-message-timestamp="${message.created_at}" data-message-id="${message.id}">
                                <p> 
                                    ${message.sender.name} 
                                </p>
                                <p> 
                                    ${message.text}
                                </p>
                                <p> 
                                    ${time} 
                                </p>
                            </div>
                            `);
                        }
                    });
                }
            });
        }
    }
})

$('#chat-form').on('submit', function (e) {
    e.preventDefault();

    let messageForm = $('#chat-form').serializeArray();

    $('#message-box').append(`
        <div class="self-message" data-message-timestamp="${new Date().toISOString().slice(0, 19).replace('T', ' ')}">
            <p>
                ${messageForm[2]['value']} 
            </p>
            <p> 
                ${messageForm[1]['value']} 
            </p>
            <p> 
                ${new Date().toLocaleTimeString('ru-RU', { timeZone: 'Europe/Moscow', hour: '2-digit', minute: '2-digit' })} <i class="bi bi-three-dots"></i>
            </p>
        </div>
    `);

    $.ajax({
        method: 'POST',
        url: '/chat/' + chatName + '/store',
        data: $('#chat-form').serialize(),
        dataType: 'json',
        headers: {
            "X-Socket-ID": String(window.Echo.socketId())
        },
        error: function (data) {
            console.log(data);
        },
        success: function (data) {
            $('.self-message').last().attr('data-message-id', data.messageId);
            $('.self-message:last p:last').find('i').remove();
            $('.self-message:last :last-child').html($('.self-message:last :last-child').html() + ' <i class="bi bi-check2"></i>');
        }
    })
});