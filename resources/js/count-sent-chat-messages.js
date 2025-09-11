export function listenChatForSentMessages(channelName, chatName) {
    window.Echo.private(channelName + chatName)
        .listen('.message.sent', (data) => {
            let unreadMessagesElement = $('.chat[data-chat-link-name="' + chatName + '"]').find('.unread-messages-count').get(0);
            if(unreadMessagesElement)
            {
                unreadMessagesElement.innerHTML = data.unreadMessagesCount.toString();
                unreadMessagesElement.animate({
                    backgroundColor: ['black', 'white'],
                    color: ['white', 'black']
                }, 400);
            }
            else
            {
                console.log('No unread messages!');
            }
        });
}