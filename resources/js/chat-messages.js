export const chatName = $('#chat-name').val();

function messageSent(data) {
    let time = new Date(data.message.created_at).toLocaleTimeString('ru-RU', { timeZone: 'Europe/Moscow', hour: '2-digit', minute: '2-digit' });
    $('#message-box').append(`
                <div class="foreign-message" data-message-timestamp="${data.message.created_at}" data-message-id="${data.message.id}" 
                data-message-status="${data.message.status}">
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
}

function messageDelivered(message) {
    $(`.self-message[data-message-id="${message.messageId}"][data-message-status="SENT"] p:last`).find('i').remove();
    $(`.self-message[data-message-id="${message.messageId}"][data-message-status="SENT"] p:last`).html(
        message.messageCreatedAt + ' <i class="bi bi-check2-all"></i>'
    );
    $(`.self-message[data-message-id="${message.messageId}"][data-message-status="SENT"]:last`).attr('data-message-status', 'DELIVERED');
}

function messageSeen(message) {
    $(`.self-message[data-message-id="${message.messageId}"][data-message-status="DELIVERED"] p:last`).find('i').remove();
    $(`.self-message[data-message-id="${message.messageId}"][data-message-status="DELIVERED"] p:last`).html(
        message.messageCreatedAt + ' <i class="bi bi-check2-all text-primary"></i>'
    );
    $(`.self-message[data-message-id="${message.messageId}"][data-message-status="SENT"]:last`).attr('data-message-status', 'SEEN');
}

export function listenChat(channelName) {
    window.Echo.private(channelName + chatName)
        .listen('.message.sent', (data) => {
            messageSent(data);
        })
        .listen('.message.delivered', (data) => {
            messageDelivered(data);
        })
        .listen('.message.seen', (data) => {
            data.messagea.forEach(message => {
                messageSeen(message);
            });
        });
}

export function listenPrivateChat(channelName) {
    window.Echo.private(channelName + chatName)
        .listen('.private.message.sent', (data) => {
            messageSent(data);
        })
        .listen('.private.message.delivered', (data) => {
            messageDelivered(data);
        })
        .listen('.message.seen', (data) => {
            data.messages.forEach(message => {
                messageSeen(message);
            });
        });
}

export function loadSeenMessages() {

    const targetNode = $('#message-box').get(0);
    const config = { subtree: true, attributes: true };

    const observer = new MutationObserver(mutations => {
        mutations.forEach((mutation) => {
            if (mutation.type == 'attributes' && mutation.attributeName == 'data-message-status') {
                if ($('.self-message[data-message-status="DELIVERED"]').get().length != 0) {
                    let elementsOnPage = [];
                    let windowHeight = $(window).height();
                    let scrollTop = $(window).scrollTop();

                    $('.self-message[data-message-status="DELIVERED"]').each(function () {
                        let element = $(this);
                        let offset = element.offset();
                        let elementHeight = element.outerHeight();

                        if (offset.top >= scrollTop && (offset.top + elementHeight) <= (scrollTop + windowHeight)) {
                            elementsOnPage.push({'id': $(element).attr('data-message-id'), 'createdAt': $(element).attr('data-message-timestamp')});
                        }
                    });

                    elementsOnPage = JSON.stringify(elementsOnPage);

                    $.ajax({
                        method: 'POST',
                        url: '/chat/' + chatName + '/seen',
                        dataType: 'json',
                        data: {
                            messages: elementsOnPage
                        },
                        error: function (e) {
                            console.log(e);
                        }
                    });
                }
            }
        })
    });
    observer.observe(targetNode, config);
}

function createMessage(message, currentUserId) {
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
    if (message.sender.id == currentUserId) {
        $('#message-box').children().first().before(`
                            <div class="self-message" data-message-timestamp="${message.created_at}" data-message-id="${message.id}" data-message-status="${message.status}">
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
        $('#message-box').children().first().before(`
                            <div class="foreign-message" data-message-timestamp="${message.created_at}" data-message-id="${message.id}" 
                            data-message-status="${message.status}">
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

}

export function loadMessages() {
    let allMessagesLoaded = false;
    let previosY = 0;

    $(window).on('load', function(){
        $(window).scrollTop($(document).height());
    });

    $(window).on('scroll', function () {
        if (window.scrollY < previosY && window.scrollY == 0) {
            if (!allMessagesLoaded) {
                $.ajax({
                    method: 'GET',
                    url: '/chat/' + chatName,
                    dataType: 'json',
                    data: {
                        lastMessageTime: $('#message-box').children().first().attr('data-message-timestamp')
                    },
                    error: function (e) {
                        console.log(e);
                    },
                    success: function (data) {
                        if (data.messages == null) {
                            allMessagesLoaded = true;
                            return;
                        }
                        else if (data.messages.length == 1) {
                            allMessagesLoaded = true;
                            let message = data.messages[0];
                            createMessage(message, data.currentUserId);
                            return;
                        }
                        data.messages.forEach(message => {
                            createMessage(message, data.currentUserId);
                        });
                    }
                });
            }
        }

        previosY = window.scrollY;
    });
}

export function submitMessage() {
    $('#chat-form').on('submit', function (e) {
        e.preventDefault();

        let messageForm = $('#chat-form').serializeArray();

        $('#message-box').append(`
        <div class="self-message" data-message-timestamp="${new Date().toISOString().slice(0, 19).replace('T', ' ')}" data-message-status="PROCESSING">
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
                $('.self-message:last').attr('data-message-id', data.messageId);
                $('.self-message:last').attr('data-message-status', 'SENT');
                $('.self-message:last p:last').find('i').remove();
                $('.self-message:last :last-child').html($('.self-message:last :last-child').html() + ' <i class="bi bi-check2"></i>');
                $('html,body').scrollTop($('#message-box').children().last().position().top);
            }
        })
    });
}