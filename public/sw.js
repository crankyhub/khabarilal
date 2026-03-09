const CACHE_NAME = 'khabarilal-v1';
const ASSETS = [
    '/',
    '/css/app.css',
    'https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(ASSETS))
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(response => response || fetch(event.request))
    );
});

self.addEventListener('push', function (event) {
    if (event.data) {
        const payload = event.data.json();
        event.waitUntil(
            self.registration.showNotification(payload.title, {
                body: payload.body,
                icon: '/favicon.ico',
                data: {
                    url: payload.url
                }
            })
        );
    }
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});
