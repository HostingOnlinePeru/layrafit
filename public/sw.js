const CACHE_NAME = 'layrafit-pwa-v2';
const ASSETS = [
    '/',
    '/css/app.css',
    '/manifest.webmanifest',
    '/favicon.png',
    '/images/icon-192.png',
    '/images/icon-512.png'
];

// Instalación del Service Worker
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS);
        })
    );
    self.skipWaiting();
});

// Activación y limpieza de cache antiguo
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.map((key) => {
                    if (key !== CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Intercepción de peticiones HTTP
self.addEventListener('fetch', (event) => {
    // Red / Cache fallback
    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request))
    );
});
