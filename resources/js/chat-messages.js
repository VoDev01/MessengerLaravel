export const chatName = $('#chat-name').val();
export const currentUserId = $('#sender-id').val();

$(window).on('load', function () {

    let foreignMessages = $('.foreign-message[data-message-status="SENT"]').get();
    let messages = [];
    foreignMessages.forEach(message => {
        messages.push({ 'id': message.getAttribute('data-message-id'), 'created_at': message.getAttribute('data-message-timestamp') });
    });
    if (messages.length != 0) {
        $.ajax({
            url: '/chat/' + chatName + '/delivered',
            method: 'POST',
            dataType: 'json',
            data: {
                messages: JSON.stringify(messages)
            },
            error: function (e) {
                console.log(e);
            }
        });
    }

    let errorInstantiated = false;
    $('textarea').on('keyup', function (e) {
        if (this.value.length < 1000) {
            this.style.height = "";
            this.style.height = this.scrollHeight + 'px';
            if (errorInstantiated) {
                document.getElementById('text-error').remove();
                errorInstantiated = false;
            }
        }
        else {
            this.value = this.value.substring(0, 1000);
            this.style.height = "";
            this.style.height = this.scrollHeight + 'px';

            if (!errorInstantiated) {
                let error = document.createElement("span");
                error.id = 'text-error';
                error.classList.add('text-danger');
                error.innerHTML = "Длина сообщения не должна превышать 1000 символов";
                error.style.fontSize = '12px';
                error.style.marginTop = '5px';
                this.after(error);
                errorInstantiated = true;
            }
        }
    });
    $('textarea').each(function () {
        this.style.height = "";
        this.style.height = this.scrollHeight + 'px';
    });
});

function dateTimeToTime(dateTime) {
    return new Date(dateTime).toLocaleTimeString('ru-RU', { timeZone: 'Europe/Moscow', hour: '2-digit', minute: '2-digit' });
}

function messageSent(data) {
    if (currentUserId == data.message.sender_id) {
        $('.self-message:last').attr('data-message-id', data.message.id);
        $('.self-message:last').attr('data-message-status', 'SENT');
        $('.self-message:last p:last').find('i').remove();
        $('.self-message:last :last-child').html($('.self-message:last :last-child').html() + ' <i class="bi bi-check2"></i>');
    }
    else {
        $('#message-box').append(`
                <div class="foreign-message" data-message-timestamp="${data.message.created_at}" data-message-id="${data.message.id}" 
                data-message-status="${data.message.status}">
                    <p> 
                        ${data.message.sender_name} 
                    </p>
                    <p> 
                        ${data.message.text}
                    </p>
                    <p> 
                        ${dateTimeToTime(data.message.created_at)} 
                    </p>
                </div>
            `);
        $('html,body').scrollTop($('#message-box').children().last().position().top);
        $.ajax({
            url: '/chat/' + chatName + '/delivered',
            method: 'POST',
            dataType: 'json',
            data: {
                message: { 'id': data.message.id, 'created_at': data.message.created_at }
            },
            error: function (e) {
                console.log(e);
            }
        });
    }
}

function messageDelivered(data) {
    $(`.self-message[data-message-id="${data.id}"] p:last`).find('i').remove();
    $(`.self-message[data-message-id="${data.id}"] p:last`).html(
        dateTimeToTime(data.created_at) + ' <i class="bi bi-check2-all"></i>'
    );
    $(`.self-message[data-message-id="${data.id}"]`).attr('data-message-status', 'DELIVERED');
}

function messageSeen(data) {
    $(`.self-message[data-message-id="${data.id}"] p:last`).find('i').remove();
    $(`.self-message[data-message-id="${data.id}"] p:last`).html(
        dateTimeToTime(data.created_at) + ' <i class="bi bi-check2-all text-primary"></i>'
    );
    $(`.self-message[data-message-id="${data.id}"]`).attr('data-message-status', 'SEEN');
}

export function listenChat(channelName) {
    window.Echo.private(channelName + chatName)
        .listen('message.sent', (data) => {
            messageSent(data);
        })
        .listen('message.delivered', (data) => {
            data.messages = Array.from(data.messages);
            data.messages.forEach(message => {
                messageDelivered(message);
            });
        })
        .listen('message.seen', (data) => {
            data.messages = Array.from(data.messages);
            data.messages.forEach(message => {
                messageSeen(message);
            });
        });
}

function changeMessageToSeenStatus(messageSelector) {

    if ($(messageSelector).get().length != 0) {
        let elementsOnPage = [];
        let windowHeight = $(window).height();
        let scrollTop = $(window).scrollTop();

        $(messageSelector).each(function () {
            let element = $(this);
            let offset = element.offset();
            let elementHeight = element.outerHeight();

            if (offset.top >= scrollTop && (offset.top + elementHeight) <= (scrollTop + windowHeight)) {
                elementsOnPage.push({ 'id': $(element).attr('data-message-id'), 'created_at': $(element).attr('data-message-timestamp') });
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

export function readMessages() {

    const targetNode = $('#message-box').get(0);
    const config = { subtree: true, attributes: true };

    const observer = new MutationObserver(mutations => {
        mutations.forEach((mutation) => {
            if (mutation.type == 'attributes' && mutation.attributeName == 'data-message-status') {
                if ($('.self-message[data-message-status="DELIVERED"]').get().length != 0) {
                    changeMessageToSeenStatus('.self-message[data-message-status="DELIVERED"]');
                }
            }
        })
    });
    observer.observe(targetNode, config);
    $(window).on('load', function () {
        if ($('.self-message[data-message-status="DELIVERED"]').get().length != 0) {
            changeMessageToSeenStatus('.self-message[data-message-status="DELIVERED"]');
        }
    });
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
                            <div class="self-message" 
                            data-message-timestamp="${message.created_at}" 
                            data-message-id="${message.id}" 
                            data-message-status="${message.status}">
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
                            <div class="foreign-message" 
                            data-message-timestamp="${message.created_at}" 
                            data-message-id="${message.id}" 
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

function sendLoadMessagesRequest(messagesLoaded, lastMessage) {
    if (!messagesLoaded) {
        $.ajax({
            method: 'GET',
            url: '/chat/' + chatName,
            dataType: 'json',
            data: {
                lastMessageTime: lastMessage.attr('data-message-timestamp')
            },
            error: function (e) {
                console.log(e);
            },
            success: function (data) {
                if (data.messages == null) {
                    messagesLoaded = true;
                }
                else if (data.messages.length == 1) {
                    messagesLoaded = true;
                    let message = data.messages[0];
                    createMessage(message, data.currentUserId);
                }
                data.messages.forEach(message => {
                    createMessage(message, data.currentUserId);
                });
            }
        });
    }
    return messagesLoaded;
}

export function loadMessages() {
    let allMessagesLoaded = false;

    $(window).on('load', function () {
        $(window).scrollTop($(document).height());
    });

    $(window).on('scroll', function () {
        if ($(window).scrollTop() + $(window).height() == $(document).height) {
            allMessagesLoaded = sendLoadMessagesRequest(allMessagesLoaded, $('#message-box').children().last())
        }
        else if (window.scrollY == 0) {
            allMessagesLoaded = sendLoadMessagesRequest(allMessagesLoaded, $('#message-box').children().first())
        }
    });
}

export function submitMessage() {
    $('#chat-form').on('submit', function (e) {
        e.preventDefault();

        let messageForm = $('#chat-form').serializeArray();

        if ($('#empty-messages').get() !== undefined) {
            $('#empty-messages').detach();
        }

        $('#message-box').append(`
        <div class="self-message" data-message-timestamp="${(new Date()).toISOString().slice(0, 19).replace('T', ' ')}" data-message-status="PROCESSING">
            <p>
                ${messageForm[2]['value']} 
            </p>
            <p> 
                ${messageForm[1]['value']} 
            </p>
            <p> 
                ${(new Date()).toLocaleTimeString('ru-RU', { timeZone: 'Europe/Moscow', hour: '2-digit', minute: '2-digit' })} <i class="bi bi-three-dots"></i>
            </p>
        </div>
    `);
        $('html,body').scrollTop($('#message-box').children().last().position().top);

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
            }
        })
    });
}