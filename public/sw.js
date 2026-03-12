const CACHE_NAME = 'khabarilal-v2';
const ASSETS = [
    '/css/app.css',
    'https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(ASSETS))
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(
                keys.filter(key => key !== CACHE_NAME)
                    .map(key => caches.delete(key))
            );
        })
    );
});

self.addEventListener('fetch', event => {
    // Network First strategy for navigation and HTML
    if (event.request.mode === 'navigate' || event.request.headers.get('accept').includes('text/html')) {
        event.respondWith(
            fetch(event.request).catch(() => caches.match(event.request))
        );
        return;
    }

    // Cache First for other assets
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
