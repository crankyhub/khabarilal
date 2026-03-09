const PUSH_VAPID_PUBLIC_KEY = 'BCK7v6p5L5zW3q9N2Z8Q'; // Dummy VAPID Key

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

async function subscribeUserToPush() {
    try {
        const registration = await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(PUSH_VAPID_PUBLIC_KEY)
        });

        const response = await fetch('/push/subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(subscription)
        });

        if (response.ok) {
            console.log('Push subscription successful');
            localStorage.setItem('push_subscribed', 'true');
        }
    } catch (error) {
        console.error('Push subscription failed:', error);
    }
}

// Check for permission and subscribe if not already done
if ('serviceWorker' in navigator && 'PushManager' in window) {
    window.addEventListener('load', () => {
        if (Notification.permission === 'default' && !localStorage.getItem('push_subscribed')) {
            // Show custom UI prompt here or just ask
            setTimeout(() => {
                if (confirm('Enable push notifications for breaking news?')) {
                    Notification.requestPermission().then(permission => {
                        if (permission === 'granted') {
                            subscribeUserToPush();
                        }
                    });
                }
            }, 3000);
        }
    });
}
