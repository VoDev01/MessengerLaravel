import $ from 'jquery';

const chatName = $('#chat-name').val();

$('#join-form').on('submit', function(e){
    e.preventDefault();

    $.ajax({
        method: 'POST',
        url: '/chat/' + chatName + '/join',
        dataType: 'json',
        data: $('#join-form').serialize(),
        error: function(e){
            console.log(e);
        },
        success: function(data)
        {
            $('#chat-form').prop('hidden', false);
            $('#join-form').prop('hidden', true);
        }
    });
});