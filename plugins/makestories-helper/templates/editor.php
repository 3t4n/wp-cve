<noscript>You need to enable JavaScript to run this app.</noscript>
<div id="root"></div>
<script>
    <?php
    $options = ms_get_options();
    $baseUrl = get_site_url();
    if (!empty($options) && isset($options['post_slug'])) {
        $baseUrl = trailingslashit($baseUrl) . trailingslashit($options['post_slug']);
    }
    $user = wp_get_current_user();
    ?>
    const msWPConfig = {
        wpBaseUrl: '<?php echo esc_html(get_site_url("")); ?>',
        currentPage: "<?php echo esc_html($subpage); ?>",
        wpAdminBaseURL: '<?php echo esc_html(MS_WP_ADMIN_BASE_URL); ?>',
        adminAjaxUrl: '<?php echo admin_url('admin-ajax.php') ?>',
        cpt: "<?php echo esc_html(MS_POST_TYPE); ?>",
        wpStoriesBaseURL: '<?php echo esc_html($baseUrl); ?>',
        wpNonce: '<?php echo wp_create_nonce(MS_NONCE_REFERRER) ?>',
        wpUser: '<?php echo esc_html($user->ID); ?>',
        wpEmail: '<?php echo esc_html($user->user_email); ?>',
        wpUsername: '<?php echo esc_html($user->first_name." ".$user->last_name); ?>',
        isCategoriesEnabled: <?php echo ms_is_categories_enabled() ? "true" : "false"; ?>,
        adminPublishPost: '<?php echo admin_url( 'edit.php?post_type=' . MS_POST_TYPE ); ?>',
        adminSchedulePost: '<?php echo admin_url( 'edit.php?post_status=draft&post_type=' . MS_POST_TYPE ); ?>',
    };
    window.msWPConfig = msWPConfig;
    const isLodash = () => {
        let isLodash = false;

        // If _ is defined and the function _.forEach exists then we know underscore OR lodash are in place
        if ( 'undefined' != typeof( _ ) && 'function' == typeof( _.forEach ) ) {

            // A small sample of some of the functions that exist in lodash but not underscore
            const funcs = [ 'get', 'set', 'at', 'cloneDeep' ];

            // Simplest if assume exists to start
            isLodash  = true;

            funcs.forEach( function ( func ) {
                // If just one of the functions do not exist, then not lodash
                isLodash = ( 'function' != typeof( _[ func ] ) ) ? false : isLodash;
            } );
        }

        if ( isLodash ) {
            // We know that lodash is loaded in the _ variable
            return true;
        } else {
            // We know that lodash is NOT loaded
            return false;
        }
    };
    
    $(document).ready(function(){
        if ( isLodash() ) {
            _.noConflict();
        }       
    });
</script>
