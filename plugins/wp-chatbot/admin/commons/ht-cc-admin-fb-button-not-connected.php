<?php

/**
 *  View of Facebook button area when not connected.
 * 
 * @uses at class-htcc-admin.php
 * @param $path String
 * @param $options Array
 */

if (!defined('ABSPATH')) exit;

?>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId            : '2015199145383303',
            autoLogAppEvents : true,
            xfbml            : true,
            version          : 'v3.1'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<div class="mobilemonkey-settings">
    <h5>Welcome to WP-Chatbot!</h5>
    <p class="get_start">To get started, connect your Facebook page</p>
    <div class="get-mm-free-button">
        <div id="get-mm-free-button__iframe-container" class="fb-send-to-messenger" messenger_app_id="2015199145383303" page_id="1754274684887439" data-color="blue" data-size="xlarge" data-ref="2799f93cb1488fae43ae0b5aac5f898e993cffecf2f4f7d3d7"></div>

        <a id="get-mm-free-button__link" class="connect-button margin-top button-lazy-load get-mm-free-button__fallback" href='<?php echo $path; ?>'>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 216 216" class="_5h0m" color="#FFFFFF"><path fill="#FFFFFF" d="
            M204.1 0H11.9C5.3 0 0 5.3 0 11.9v192.2c0 6.6 5.3 11.9 11.9
            11.9h103.5v-83.6H87.2V99.8h28.1v-24c0-27.9 17-43.1 41.9-43.1
            11.9 0 22.2.9 25.2 1.3v29.2h-17.3c-13.5 0-16.2 6.4-16.2
            15.9v20.8h32.3l-4.2 32.6h-28V216h55c6.6 0 11.9-5.3
            11.9-11.9V11.9C216 5.3 210.7 0 204.1 0z"></path></svg>
            <span>Connect Facebook Page</span></a>
        <div class="lazyload"></div>
    </div>
    <div class="mobilemonkey-terms">
        <p>By connecting with Facebook you are agreeing to the<a href="https://mobilemonkey.com/master-service-agreement" target="_blank"> WP Chatbot Terms of Service</a></p>
    </div>
</div>
