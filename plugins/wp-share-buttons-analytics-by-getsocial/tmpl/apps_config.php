<?php

    $plan_is_free = get_option('gs-pro') ? false : true;

    $plans = array(
        'Free Plan' => 'zero',
        'Tools Plan' => 'one'
    );
    $categories = array(
        'Sharing Apps' => 'sharing',
        'Tracking & Engagement Tools' => 'tracking',
        'Follow Apps' => 'follow',
        'Integrations' => 'integrations'
    );
    $apps = array(
        'Floating Sharing Bar' => array(
            'file' => 'floating-bar',
            'category' => 'sharing',
            'new' => false,
            'plan' => 'zero',
            'active' => $GS->is_active('floating_bar') || $GS->is_active('floating_bar_big_counter'),
            'only_activate' => false,
            'href' => $GS->gs_account() . '/sites/gs-wordpress/floating-bar/new?api_key=' . $GS->api_key . '&amp;source=wordpress' . $GS->utms('floating-bar'),
            "desc" => "Use one of our templates or design your own floating sharing bar. Customize size, shape & placement and pick from 15 social networks."
        ),
        'Horizontal Sharing Bar' => array(
            'file' => 'sharing-bar',
            'category' => 'sharing',
            'new' => false,
            'plan' => 'zero',
            'active' => $GS->is_active('sharing_bar') || $GS->is_active('social_bar_big_counter'),
            'only_activate' => false,
            'href' => $GS->gs_account() . '/sites/gs-wordpress/groups/new?api_key=' . $GS->api_key . '&amp;source=wordpress' . $GS->utms('sharing-bar'),
            'desc' => "Use one of our templates or design your own social sharing bar. Customize size, shape & colour and pick from 15 social networks."
        ),
        'Mobile Sharing Bar' => array(
            'file' => 'mobile-bar',
            'category' => 'sharing',
            'new' => false,
            'plan' => 'one',
            'active' => $GS->is_active('mobile_bar'),
            'only_activate' => false,
            'href' => $GS->gs_account() . '/sites/gs-wordpress/mobile-bar/new?api_key=' . $GS->api_key . '&amp;source=wordpress' . $GS->utms('mobile-bar'),
            "desc" => "Mobile Web is one of the fastest growing platform both in traffic and shares. Don't miss out on the opportunity to boost your traffic with our slick mobile web sharing interface. No code needed."
        ),
        'Image Sharing' => array(
            'file' => 'image-sharing',
            'category' => 'sharing',
            'new' => false,
            'plan' => 'one',
            'active' => $GS->is_active('image_sharing'),
            'only_activate' => false,
            'href' => $GS->gs_account() . '/sites/gs-wordpress/image-sharing/new?api_key=' . $GS->api_key . '&amp;source=wordpress' . $GS->utms('hello_buddies'),
            "desc" => "Increase shares on images on your website. Great for media-based websites."
        ),
        'Reaction Buttons' => array(
            'file' => 'reaction_buttons',
            'category' => 'sharing',
            'new' => false,
            'plan' => 'zero',
            'active' => $GS->is_active('reaction_buttons'),
            'only_activate' => false,
            'href' => $GS->gs_account() . '/sites/gs-wordpress/reaction_buttons/new?api_key=' . $GS->api_key . '&amp;source=wordpress' . $GS->utms('image-sharing'),
            "desc" => "Let your users show how they feel!"
        ),
        'Hello Buddy' => array(
            'file' => 'hello_buddy',
            'category' => 'tracking',
            'new' => false,
            'plan' => 'zero',
            'active' => $GS->is_active('hello_buddy'),
            'only_activate' => false,
            'href' => $GS->gs_account() . '/sites/gs-wordpress/hello-buddies/new?api_key=' . $GS->api_key . '&amp;source=wordpress' . $GS->utms('image-sharing'),
            "desc" => "The intelligent pop-up. The right message at the right time! Great to boost shares & subscribers!"
        ),
        'Copy Paste Share Tracking' => array(
            'file' => 'address-tracker',
            'category' => 'tracking',
            'new' => false,
            'plan' => 'one',
            'active' => $GS->is_active('address_tracking'),
            'only_activate' => true,
            'href' => $GS->api_url('sites/activate/' . get_option('gs-api-key') . '/address-tracker'),
            "desc" => "Don't lose track of shares made through copying and pasting an URL on the address bar to social networks, email or other platforms."
        ),
        'Native Sharing Bar' => array(
            'file' => 'native-bar',
            'category' => 'sharing',
            'new' => false,
            'plan' => 'zero',
            'active' => $GS->is_active('native_bar'),
            'only_activate' => false,
            'href' => $GS->gs_account() . '/sites/gs-wordpress/native-bar/new?api_key=' . $GS->api_key . '&amp;source=wordpress' . $GS->utms('native-bar'),
            'desc' => "It doesn't get much more classic than this. Your native sharing buttons with tracking abilities. Great for those who want to keep it real."
        ),
        'Horizontal Follow Bar' => array(
            'file' => 'follow-bar',
            'category' => 'follow',
            'new' => false,
            'plan' => 'zero',
            'active' => $GS->is_active('follow_bar'),
            'only_activate' => false,
            'href' => $GS->gs_account() . '/sites/gs-wordpress/follow-bar/new?api_key=' . $GS->api_key . '&amp;source=wordpress' . $GS->utms('follow-bar'),
            'desc' => "Grow your follower base in Facebook, Twitter, Pinterest and more with these beautiful free follow buttons."
        ),
        'Floating Follow Bar' => array(
            'file' => 'floating-follow-bar',
            'category' => 'one',
            'new' => false,
            'plan' => 'one',
            'active' => $GS->is_active('floating_follow_bar'),
            'only_activate' => false,
            'href' => $GS->gs_account() . '/sites/gs-wordpress/follow-floating-bar/new?api_key=' . $GS->api_key . '&amp;source=wordpress' . $GS->utms('floating-follow-bar'),
            'desc' => "Grow your follower base in Facebook, Twitter, Pinterest and more with these beautiful free follow buttons."
        ),
        'Mobile Follow Bar' => array(
            'file' => 'mobile-follow-bar',
            'category' => 'follow',
            'new' => false,
            'plan' => 'one',
            'active' => $GS->is_active('mobile_follow_bar'),
            'only_activate' => false,
            'href' => $GS->gs_account() . '/sites/gs-wordpress/follow-mobile-bar/new?api_key=' . $GS->api_key . '&amp;source=wordpress' . $GS->utms('mobile-follow-bar'),
            'desc' => "Don't miss out on the opportunity to convert mobile visitors into brand followers with our mobile follow buttons."
        ),
        'Welcome Bar' => array(
            'file' => 'welcome-bar',
            'category' => 'tracking',
            'new' => false,
            'plan' => 'zero',
            'active' => $GS->is_active('welcome_bar'),
            'only_activate' => false,
            'href' => $GS->gs_account() . '/sites/gs-wordpress/welcome-bars/new?api_key=' . $GS->api_key . '&amp;source=wordpress' . $GS->utms('welcome-bar'),
            "desc" => "Easily lead your visitors to a specific link. Great to generate conversions, engage with promotions and increase traffic. No code needed."
        ),
        'Subscriber Bar' => array(
            'file' => 'subscriber-bar',
            'category' => 'tracking',
            'new' => false,
            'plan' => 'one',
            'active' => $GS->is_active('subscriber_bar'),
            'only_activate' => false,
            'href' => $GS->gs_account() . '/sites/gs-wordpress/subscribe-bars/new?api_key=' . $GS->api_key . '&amp;source=wordpress' . $GS->utms('subscriber-bar'),
            "desc" => "Easily capture emails from your visitors by providing them with an engaging top bar. Export data to your favorite CRM or e-Mail marketing software."
        ),
        'Google Analytics' => array(
            'file' => 'ga_integration',
            'category' => 'integrations',
            'new' => false,
            'plan' => 'one',
            'active' => $GS->is_active('ga_integration'),
            'only_activate' => true,
            'href' => $GS->api_url('sites/activate/' . get_option('gs-api-key') . '/ga_integration'),
            'desc' => "Integrate your GetSocial sharing activity with Google Analytics and have all of your analytics in one place. No code required"
        ),
        'MailChimp' => array(
            'file' => 'mailchimp',
            'category' => 'integrations',
            'new' => false,
            'plan' => 'one',
            'active' => $GS->is_active('mailchimp'),
            'only_activate' => true,
            'href' => $GS->gs_account() . '/auth/mailchimp',
            'desc' => "Automatically connect your Subscriber Bar with your Mailchimp account"
        )
    );

    $plan_class = array_values($plans);
    $plan_name = array_keys($plans);
    $plan_categories = array_values($categories);
    $plan_categories_name = array_keys($categories);

    function plan_class($plan_id) {
        global $plan_class;
        global $plans;
        return array_search($plan_id, $plans);
    }

    function plan_name($plan_id) {
        global $plan_name;
        global $plans;
        return array_search($plan_id, $plans);
    }

    function category_name($category_key) {
        global $categories;
        return array_search($category_key, $categories);
    }
?>
