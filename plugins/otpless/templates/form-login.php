<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
 $otpless_options = get_option('otpless_option_name');
 $origin = filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL);
 $url =  $otpless_options['authlink'] . '?redirectUri=' . urlencode('https://'.$origin . $otpless_options['redirect_uri']);

?>
<div id="otpless">
    <a href="<?php echo esc_url($url); ?>" target="_blank"><img src="assets/img/otpless_button.svg"
            style="width:200px" /></a>
</div>
<!-- From wp-login.php line 285-305 -->
<p id="backtoblog">
    <?php
    $html_link = sprintf(
        '<a href="%s">%s</a>',
        esc_url( home_url( '/' ) ),
        sprintf(
            /* translators: %s: Site title. */
            _x( '&larr; Go to %s', 'site' ),
            get_bloginfo( 'title', 'display' )
        )
    );
    /**
     * Filter the "Go to site" link displayed in the login page footer.
     *
     * @since 5.7.0
     *
     * @param string $link HTML link to the home URL of the current site.
     */
    echo apply_filters( 'login_site_html_link', $html_link );
    ?>
</p>