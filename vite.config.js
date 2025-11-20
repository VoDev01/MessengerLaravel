import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/chat-join.js',
                'resources/js/chat-load-chats.js',
                'resources/js/chat-messages.js',
                'resources/js/direct-messages.js',
                'resources/js/group-messages.js',
                'resources/js/user-status.js',
                'resources/js/logout-user.js',
                'resources/js/listen-chats-sent-messages.js',
                'resources/js/count-sent-chat-messages.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '$': 'jquery'
        }
    }
});
