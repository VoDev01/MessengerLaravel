import $ from 'jquery';
import * as cm from './chat-messages';

cm.listenPrivateChat('user.direct.');

cm.loadMessages();

cm.submitMessage();