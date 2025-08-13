import $ from 'jquery';

const chatName = $('#chat-name').val();

if ($('#chat-visibility').val() === 'PUBLIC') {
    window.Echo.private('chat.' + chatName)
        .listen('.message.sent', (data) => {
            $('.message').last().after('<div class=\"message\"><p>' +
                data.message.sender.name +
                '</p><p>' +
                data.message.text +
                '</p><p>' +
                data.message.created_at +
                '</p></div>');
        });
} else if ($('#chat-visibility').val() === 'PRIVATE') {
    window.Echo.private('chat.private.' + chatName)
        .listen('.private.message.sent', (data) => {
            $('.message').last().after('<div class=\"message\"><p>' +
                data.message.sender.name +
                '</p><p>' +
                data.message.text +
                '</p><p>' +
                data.message.created_at +
                '</p></div>');
        });
}

$('#chat-form').on('submit', function (e) {
    e.preventDefault();

    let messageForm = $('#chat-form').serializeArray();

    $('.message').last().after('<div class=\"message\"><p>' +
        messageForm[2]['value'] +
        '</p><p>' +
        messageForm[1]['value'] +
        '</p><p>' +
        new Date() +
        '</p></div>');

    $.ajax({
        method: 'POST',
        url: '/chat/' + chatName + '/store',
        data: $('#chat-form').serialize(),
        dataType: 'json',
        headers: {
            "X-Socket-Id": String(window.Echo.socketId())
        },
        error: function (data) {
            console.log(data);
        }
    })
});