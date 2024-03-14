<?php
add_action('admin_notices', 'bdfe_sponsored_notice');
function bdfe_sponsored_notice(){
?>
  <div class="welcome-message notice notice-info">
            <div class="notice-wrapper">
                <div class="notice-text">
                	<p><?php _e('Blog Designer Plugin Is Very Much Comportable with the <strong>Blog Starter Pro</strong> WordPress Theme.', 'bdfe'); ?></p>
                	<a target="_blank" href="<?php echo esc_url('https://theimran.com/themes/wordpress-theme/blog-starter-pro-personal-blog-wordpress-theme/');?>"><img src="<?php echo BDFE_PLUGIN_URL . 'assets/admin/img/advertisement.jpg'?>" alt="<?php esc_attr_e('Blog Starter Pro WordPress Theme', 'bdfe');?>"></a>
                    <p class="dismiss-link"><strong><a href="?bdfe-update-notice=1"><?php esc_html_e( 'Dismiss','bdfe' ); ?></a></strong></p>
                </div>
            </div>
        </div>
<?php
}

if( ! function_exists( 'bdfe_ignore_admin_notice' ) ) :
/**
 * Adding Getting Started Page in admin menu
 */
function bdfe_ignore_admin_notice() {

    /* If user clicks to ignore the notice, add that to their user meta */
    if ( isset( $_GET['bdfe-update-notice'] ) && $_GET['bdfe-update-notice'] = '1' ) {

        update_option( 'bdfe-update-notice', true );
    }
}
endif;
add_action( 'admin_init', 'bdfe_ignore_admin_notice' );
