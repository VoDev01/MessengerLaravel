import $ from 'jquery';

const chatName = $('#chat-name').val();

$('#join-form').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
        method: 'POST',
        url: '/chat/' + chatName + '/join',
        dataType: 'json',
        data: $('#join-form').serialize(),
        error: function (e) {
            console.log(e);
        },
        success: function (data) {
            $('#chat-form').prop('hidden', false);
            $('#join-form').prop('hidden', true);
            let userCount = $('#user-count').html().split(' ');
            $('#user-count').html((parseInt(userCount[0]) + 1) + ' ' + userCount[1]);
            document.title = data.chatName;

            if (data.messages.length === 0) {
                $('#restricted').after('<span class="mb-3">В этой группе пока что нет сообщений</span>');
                $('#restricted').remove();
                return;
            }

            $('#restricted').after('<div id="message-box"></div>');
            $('#restricted').remove();
            data.messages.forEach(message => {
                let time = new Date(message.created_at).toLocaleTimeString('ru-RU', { timeZone: 'Europe/Moscow', hour: '2-digit', minute: '2-digit' });

                if (message.sender.id == data.currentUserId) {
                    $('#message-box').append('<div class=\"self-message\" data-message-timestamp=\"' + data.message.created_at + '\"><p>' +
                        data.message.sender.name +
                        '</p><p>' +
                        data.message.text +
                        '</p><p>' +
                        time +
                        '</p></div>')
                }
                else {
                    $('#message-box').append('<div class=\"foreign-message\" data-message-timestamp=\"' + data.message.created_at + '\"><p>' +
                        data.message.sender.name +
                        '</p><p>' +
                        data.message.text +
                        '</p><p>' +
                        time +
                        '</p></div>')
                }
            });
        }
    });
});