import * as cm from './chat-messages';

if ($('#chat-visibility').val() === 'PUBLIC') {
    cm.listenChat('chat.');
} else if ($('#chat-visibility').val() === 'PRIVATE') {
    cm.listenPrivateChat('chat.private.');
}

cm.loadSeenMessages();

cm.loadMessages();

cm.submitMessage();