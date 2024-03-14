<?php
/**
 * @package: Remove_Footer_Links
 * @author: plugindeveloper
 * @version: 1.0.0
 * @author_uri: https://profiles.wordpress.org/plugindeveloper/
 * @since 1.0.0
 */
namespace Remove_Footer_Links\Inc\Hooks;
class Notices{

    public function __construct(){
    	$this->hooks();
    }

    public function hooks(){
        add_action( 'admin_notices', array( $this, 'welcome_notice' ) );
    }

    function welcome_notice(){

        global $pagenow;
        $notice = isset($_GET['notice']) ? sanitize_text_field($_GET['notice']) : '';
        $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        
        if ( $pagenow == 'options-general.php' && $page=='remove-footer-links' && $notice=='welcome' ) {
            ?>
            <div class="notice notice-success is-dismissible">
            <p><strong><?php esc_html_e( 'You are successfully installed the remove footer links.', 'remove-footer-links' ); ?></strong></p>
            </div>
            <?php
        }
    }

}
