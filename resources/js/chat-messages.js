import $ from 'jquery';

const chatName = $('#chat-name').val();
let allMessagesLoaded = false;

$(window).on('scroll', function () {
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
                        let time = new Date(message.created_at).getHours() + ':' + new Date(message.created_at).getMinutes();
                        if (message.sender.id == data.currentUserId) {
                            $('.self-message').last().after('<div class=\"self-message\" data-message-timestamp=\"' +
                                message.created_at +
                                '\"><p>' +
                                message.sender.name +
                                '</p><p>' +
                                message.text +
                                '</p><p>' +
                                time +
                                '</p></div>');
                        }
                        else {
                            $('.foreign-message').last().after('<div class=\"foreign-message\" data-message-timestamp=\"' +
                                message.created_at +
                                '\"><p>' +
                                message.sender.name +
                                '</p><p>' +
                                message.text +
                                '</p><p>' +
                                time +
                                '</p></div>');
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

    $('#message-box').append('<div class=\"self-message\" data-message-timestamp=\"' +
        new Date().toISOString().slice(0, 19).replace('T', ' ') +
        '\"><p>' +
        messageForm[2]['value'] +
        '</p><p>' +
        messageForm[1]['value'] +
        '</p><p>' +
        new Date().toLocaleTimeString('ru-RU', { timeZone: 'UTC', hour: '2-digit', minute: '2-digit' }) +
        '</p></div>');

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

if ($('#chat-visibility').val() === 'PUBLIC') {
    window.Echo.private('chat.' + chatName)
        .listen('.message.sent', (data) => {
            let time = new Date(data.message.created_at).toTimeString();
            $('#message-box').append('<div class=\"foreign-message\" data-message-timestamp=\"' + data.message.created_at + '\"><p>' +
                data.message.sender.name +
                '</p><p>' +
                data.message.text +
                '</p><p>' +
                time +
                '</p></div>');
        });
} else if ($('#chat-visibility').val() === 'PRIVATE') {
    window.Echo.private('chat.private.' + chatName)
        .listen('.private.message.sent', (data) => {
            let time = new Date(data.message.created_at).toTimeString();
            $('#message-box').append('<div class=\"foreign-message\" data-message-timestamp=\"' + data.message.created_at + '\"><p>' +
                data.message.sender.name +
                '</p><p>' +
                data.message.text +
                '</p><p>' +
                time +
                '</p></div>');
        });
}