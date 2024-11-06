// Lắng nghe sự kiện install để cache các tài nguyên
self.addEventListener("install", event => {
    event.waitUntil(
        caches.open("v1").then(cache => {
            return cache.addAll([
                "/",
                "/index.html",
                "/styles.css",
                "/main.js",
                "/images/icon-192x192.png",
                "/images/icon-512x512.png"
            ]);
        })
    );
});

// Lắng nghe sự kiện activate để xóa các cache cũ
self.addEventListener("activate", event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => {
                        return cacheName.startsWith("v") && cacheName !== "v1";
                    })
                    .map(cacheName => {
                        return caches.delete(cacheName);
                    })
            );
        })
    );
});

// Lắng nghe sự kiện push để trả tạo ra thông báo đẩy trên điện thoại
self.addEventListener('push', function (event) {
    console.log('Received a push event', event);

    const title = 'Tiêu đề của Push notification';
    const options = {
        body: 'Đây là nội dung của push notification',
        icon: 'path/to/icon.png',
        badge: 'path/to/badge.png'
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

// Lắng nghe sự kiện fetch để trả về các tài nguyên từ cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request);
        })
    );
});