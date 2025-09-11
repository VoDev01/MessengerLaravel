import * as cscm from './count-sent-chat-messages';

$('.chat').get().forEach(chat => {
    let chatName = chat.getAttribute('data-chat-link-name');
    if (chat.getAttribute('data-chat-type') === 'DIRECT') {
        cscm.listenChatForSentMessages('chat.direct.', chatName);
    }
    else {
        if (chat.getAttribute('data-chat-visibility') === 'PUBLIC')
            cscm.listenChatForSentMessages('chat.', chatName);
        else
            cscm.listenChatForSentMessages('chat.private.',chatName);
    }
});