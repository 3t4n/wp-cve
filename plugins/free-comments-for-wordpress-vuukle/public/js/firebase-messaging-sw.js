if (typeof importScripts === 'function') {
    self.addEventListener('notificationclick', function (event) {
        const url = event.notification.data.FCM_MSG.notification.click_action;
        event.notification.close();
        const appUrl = `${url}`;
        event.waitUntil(self.clients.openWindow(appUrl));
    });
    
    // Scripts for firebase and firebase messaging
    importScripts(
        'https://www.gstatic.com/firebasejs/9.21.0/firebase-app-compat.js',
    );
    importScripts(
        'https://www.gstatic.com/firebasejs/9.21.0/firebase-messaging-compat.js',
    );
    
    // Initialize the Firebase app in the service worker by passing the generated config
    const firebaseConfig = {
        apiKey: 'AIzaSyAntpQydRNSCd17c0dxdyKPOMcHR4R5M8U',
        authDomain: 'vuukle-push-notifications.firebaseapp.com',
        projectId: 'vuukle-push-notifications',
        storageBucket: 'vuukle-push-notifications.appspot.com',
        messagingSenderId: '994174897627',
        appId: '1:994174897627:web:95b04960af61be1a4dabfe',
        measurementId: 'G-8ZJKS6SK9Y',
    };
    
    firebase.initializeApp(firebaseConfig);
    
    // Retrieve firebase messaging
    const messaging = firebase.messaging();
    
    messaging.onBackgroundMessage(function (payload) {
        console.log('background message', payload);
    });
}
