import * as cm from './chat-messages';

$('.user-online').map(function () {
    window.Echo.private('user.' + this.getAttribute('data-user-link'))
        .listen('.user.status.changed', function (data) {
            $('.user-online').html(data.online ? 'В сети' : 'Не в сети')
        })
});