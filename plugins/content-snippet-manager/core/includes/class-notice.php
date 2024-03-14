<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if( !class_exists( 'csm_admin_notice' ) ) {

	class csm_admin_notice {

		/**
		 * Constructor
		 */

		public function __construct( $fields = array() ) {

			if ( !get_user_meta( get_current_user_id(), 'csm_notice_userid_' . get_current_user_id() , TRUE ) ) {

				add_action( 'admin_notices', array(&$this, 'admin_notice') );
				add_action( 'admin_head', array( $this, 'dismiss' ) );

			}

		}

		/**
		 * Dismiss notice.
		 */

		public function dismiss() {

			if ( isset( $_GET['csm-dismiss'] ) ) {

				update_user_meta( get_current_user_id(), 'csm_notice_userid_' . get_current_user_id() , intval($_GET['csm-dismiss']) );
				remove_action( 'admin_notices', array(&$this, 'admin_notice') );

			}

		}

		/**
		 * Admin notice.
		 */

		public function admin_notice() {

			global $pagenow;
			$redirect = ( 'admin.php' == $pagenow ) ? '?page=csm_panel&csm-dismiss=1' : '?csm-dismiss=1';

		?>

            <div class="update-nag notice csm-notice">

            	<div class="csm-noticedescription">
					<strong><?php _e( 'To enable all features, like header, footer and WooCommerce conversion snippets, please upgrade to the premium version.', 'content-snippet-manager' ); ?></strong><br/>
					<?php printf( '<a href="%1$s" class="dismiss-notice">'. __( 'Dismiss this notice', 'content-snippet-manager' ) .'</a>', esc_url($redirect) ); ?>
                </div>

                <a target="_blank" href="<?php echo esc_url( CSM_UPGRADE_LINK . '/?ref=2&campaign=csm-notice' ); ?>" class="button"><?php _e( 'Upgrade to Content Snippet Manager Pro', 'content-snippet-manager' ); ?></a>
                <div class="clear"></div>

            </div>

		<?php

		}

	}

}

new csm_admin_notice();

?>
