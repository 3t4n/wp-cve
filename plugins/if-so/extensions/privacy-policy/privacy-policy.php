<?php

function privacy_on_admin_init(){

    if( function_exists('wp_add_privacy_policy_content') ) {
        wp_add_privacy_policy_content(
            __('If-So Dynamic Content', 'if-so'),
            __("While it is your responsibility to publish a privacy policy abiding by all relevant data protection laws, at If-So we’re happy to provide you with some guidance on adjustments to make, upon installing our dynamic content. <br /> <br />
            We recommend you to adjust your privacy policy, for added transparency, to reflect that:<br /> <br />
            We collect connectivity, technical and aggregated usage data as part of the provision of our Services. This includes data such as IP addresses, non-identifying data regarding a device, operating system, browser version, locale and language settings, the cookies and pixels installed on such device, session logging, referring or exit pages, and date/time stamps, and the activity (clicks, browsing, and other interactions).
            <br /><br /> We collect such data in order to provide you with our services and to further develop, customize and improve the services.
            <br /><br /> We use cookies to process your users’ connectivity, including data such as pages visited within the website, IP addresses, content on the website that has been interacted with by the user and the number of times pages have been visited. <br />
            ",'if-so'));
    }
}

