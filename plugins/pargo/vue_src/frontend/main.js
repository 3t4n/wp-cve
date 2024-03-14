import { createApp } from 'vue';
import { shouldTransformRef } from 'vue/compiler-sfc';
import App from './App.vue';




jQuery(document).ready(function($) {
    function mountPargoApp(type) {
        const app = createApp(App, {type});
        
        let container = document.getElementById('pargo-modal');
        if (type !== 'modal') {
            container = document.getElementById('pargo-after-cart');
        }
        container.innerHTML = '';
        let vueContainer = document.createElement('div');
        container.appendChild(vueContainer);
        app.mount(vueContainer);
    }
    window.mountPargoApp = mountPargoApp;

    // Work around to reload the page to remove the shipping fields with a Pargo Pickup Point selected
    let isRefreshPage = false;
    $('form.checkout').on('change','input[name^="shipping_method"]',function() {
        const val = $( this ).val();
        if (val == 'wp_pargo' || val == 'wp_pargo_home') {
            isRefreshPage = true;
        }
    });
    const shipping_method = $('input[name^="shipping_method"]');
    for (let i = 0; i < shipping_method.length; i++) {
        if (shipping_method[i].checked === true &&  shipping_method[i].value === 'wp_pargo') {
            $('#ship-to-different-address').hide();
        }
    }
    $('body').on('updated_checkout', function(){
        if (isRefreshPage) {
            location.reload();
        }
    });
});