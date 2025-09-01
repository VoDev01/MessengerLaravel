import * as cm from './chat-messages';

if ($('#chat-visibility').val() === 'PUBLIC') {
    cm.listenChat('chat.');
} else if ($('#chat-visibility').val() === 'PRIVATE') {
    cm.listenChat('chat.private.');
}

cm.readMessages();

cm.loadMessages();

cm.submitMessage();