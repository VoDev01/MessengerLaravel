import $ from 'jquery';

let allChatsLoaded = false;

$(window).on('scroll', function () {
    if ($(window).scrollTop() + $(window).height() == $(document).height()) {
        if (!allChatsLoaded) {
            $.ajax({
                method: 'GET',
                url: '/user/chats',
                dataType: 'json',
                data: {
                    lastChatId: $('.chat').last().data('chat-id')
                },
                error: function (e) {
                    console.log(e);
                },
                success: function (data) {
                    if (data.chats == null) {
                        allChatsLoaded = true;
                        return;
                    }
                    data.chats.forEach(chat => {
                        $('.chat').last().append(`
                        <a class="mb-3 d-flex justify-content-start chat" href="/chat/${chat.link_name}" data-chat-id="${chat.id}">
                        <div>
                            <img src="${chat.logo}" alt="Лого группы" style="border-radius: 50%;" />
                        </div>
                        <div class="mx-3">
                            <p>${chat.name}</p>
                            <p>${chat.users.length} пользователей</p>
                        </div>
                        </a>`);
                    });
                }
            });
        }
    }
});