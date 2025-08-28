import * as cm from './chat-messages';

cm.listenPrivateChat('chat.direct.');

cm.loadSeenMessages();

cm.loadMessages();

cm.submitMessage();