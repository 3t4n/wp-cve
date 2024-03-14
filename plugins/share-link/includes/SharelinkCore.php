<?php 

class SharelinkCore {
    public static function isInstalled() {
        return SharelinkOptions::getLicenseIsActivated();
    }

    public static function setLicense($license) {
        $neatLicense = addslashes($license);
        $api = new SharelinkApi();

        update_option('sharelink-license', $neatLicense);
        update_option('sharelink-license-activated', 0);

        $response = $api->get($license);

        switch ($response) {
            case 404:
                header("Location: ".admin_url('admin.php?page=sharelink-error-404'));
                exit();
                break;
            case 403:
                header("Location: ".admin_url('admin.php?page=sharelink-error-403'));
                exit();
                break;
            case 200:
                update_option('sharelink-license-activated', 1);
                header("Location: ".admin_url('admin.php?page=sharelink'));
                exit();
                break;
        }
    }

    public static function frontEndJs() {
        wp_enqueue_script('sharelink-frontend-js', SHARELINK_WIDGET_JS);
    }

    public static function prefix_enqueue($posts) {
        if ( empty($posts) || is_admin() )
            return $posts;$shortcodeFound = false;$jsFound = false;

        foreach ($posts as $post) {
            if ( has_shortcode($post->post_content, 'sharelink') ){
                $shortcodeFound = true;
                break;
            }

            if (strpos($post->post_content, '<script src="'.SHARELINK_WIDGET_JS.'" crossorigin="anonymous" defer></script>') !== false) {
                $jsFound = true;
                break;
            }
        }

        if ($shortcodeFound || !$jsFound) {
            add_action('wp_enqueue_scripts', ['SharelinkCore', 'frontEndJs']);
        }

        return $posts;
    }

    public static function addAddAttributesForFrontendJs($tag, $handle) {
        if ( 'sharelink-frontend-js' !== $handle )
            return $tag;
    
        return str_replace( ' src', ' defer="" crossorigin="anonymous" src', $tag );
    }

    public static function initiateShortcode($atts) {
        $string = '<iframe width="100%" frameborder="0" class="sharelink" scrolling="no" style="width: 1px;min-width: 100%;" src="'. SHARELINK_WIDGET_BASE_URL .'/'. $atts[0] . '" loading="lazy"></iframe>';

        return $string;
    }
}
