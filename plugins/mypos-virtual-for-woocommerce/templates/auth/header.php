<?php
/**
 * Auth header
 */

defined('ABSPATH') || exit;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="robots" content="noindex, nofollow"/>
    <title><?php esc_html_e('Application authentication request', 'woocommerce'); ?></title>

    <?php
        /**
         * Enqueue scripts and styles for the login page.
         *
         * @since 3.1.0
         */
        do_action( 'login_enqueue_scripts' );

        /**
         * Fires in the login page header after scripts are enqueued.
         *
         * @since 2.1.0
         */
        do_action( 'login_head' );
    ?>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <?php wp_admin_css('install', true); ?>
    <link rel="stylesheet"
          href="<?php echo esc_url(str_replace(array('http:', 'https:'), '', MyPOS()->plugin_url()) . '/assets/css/auth.css'); ?>"
          type="text/css"/>
</head>
<body class="wc-auth wp-core-ui">
<script type="text/javascript">
    document.body.className = document.body.className.replace('no-js','js');
</script>
    <?php
    /**
     * Fires in the login page header after the body tag is opened.
     *
     * @since 4.6.0
     */
    do_action( 'login_header' );

    ?>
    <div class="wc-auth-content">
