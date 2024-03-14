<?php
/**
 * Scripts & Styles file
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main plugin class.
 */
class Blockons_Notices {
	/**
	 * Constructor funtion
	 */
	public function __construct() {
		add_action( 'admin_init', array($this, 'blockons_dismiss_notice' ), 0);
		add_action( 'admin_notices', array($this, 'blockons_add_update_notice' ));
	} // End __construct ()

	/**
	 * Add notices
	 */
	public function blockons_add_update_notice() {
		global $pagenow;
		global $current_user;
        $user_id = $current_user->ID;
		$blockons_page = isset( $_GET['page'] ) ? $pagenow . '?page=' . sanitize_text_field($_GET['page']) . '&' : sanitize_text_field($pagenow) . '?';

		$notices = $this->blockons_notices();

		$allowed_html = array(
			'b' => array('style' => array()),
		);

		if ( $pagenow == 'index.php' || $pagenow == 'plugins.php' || $pagenow == 'options-general.php' ) :

			if ( $notices ) :
				// Loop over all notices
				foreach ($notices as $notice) :

					if ( current_user_can( 'manage_options' ) && !get_user_meta( $user_id, 'blockons_notice_' . $notice['id'] . '_dismissed', true ) ) : ?>
						<div class="blockons-admin-notice notice notice-<?php echo isset($notice['type']) ? sanitize_html_class($notice['type']) : 'info'; ?>">
							<a href="<?php echo esc_url(admin_url($blockons_page . 'blockons_dismiss_notice&blockons-notice-id=' . $notice['id'])); ?>" class="notice-dismiss"></a>

							<div class="blockons-notice <?php echo isset($notice['inline']) ? esc_attr( 'inline' ) : ''; ?>">
								<?php if (isset($notice['title'])) : ?>
									<h4 class="blockons-notice-title"><?php echo wp_kses($notice['title'] ,$allowed_html); ?></h4>
								<?php endif; ?>

								<?php if (isset($notice['text'])) : ?>
									<p class="blockons-notice-text"><?php echo wp_kses($notice['text'] ,$allowed_html); ?></p>
								<?php endif; ?>

								<?php if (isset($notice['link']) && isset($notice['link_text'])) : ?>
									<a href="<?php echo esc_url($notice['link']); ?>" class="blockons-notice-btn">
										<?php esc_html_e($notice['link_text']); ?>
									</a>
								<?php endif; ?>
							</div>
						</div><?php
					endif;

				endforeach;
			endif;
			
		endif;
	}
	// Make Notice Dismissable
	public function blockons_dismiss_notice() {
		global $current_user;
		$user_id = $current_user->ID;

		if ( isset( $_GET['blockons_dismiss_notice'] ) ) {
			$blockons_notice_id = sanitize_text_field( $_GET['blockons-notice-id'] );
			add_user_meta( $user_id, 'blockons_notice_' .$blockons_notice_id. '_dismissed', 'true', true );
		}
    }

	/**
	 * Build Notices Array
	 */
	private function blockons_notices() {
		if ( !is_admin() )
			return;

		$settings = array();
		
		$settings['new_blocks_added'] = array(
			'id'    => 'newblocks_005', // Increment this when adding new blocks
			'type'  => 'info', // info | error | warning | success
			'title' => __( 'A New Tabs block has been added to the Blockons plugin', 'blockons' ),
			'text'  => __( 'To enable the new blocks and start using them in the WordPress editor:', 'blockons' ),
			'link'  => admin_url( 'options-general.php?page=blockons-settings' ),
			'link_text' => __( 'Go to the Blockons settings', 'blockons' ),
			'inline' => true, // To display the link & text inline
		);

		// $settings['new_settings'] = array(
		// 	'id'    => '01',
		// 	'type'  => 'info',
		// 	'title' => __( 'Blockons, manually added notice', 'blockons' ),
		// 	'text'  => __( 'Other notices can be added simply by adding then here in the code', 'blockons' ),
		// 	// 'link'  => admin_url( 'options-general.php?page=blockons-settings' ),
		// 	// 'link_text' => __( 'Go to Settings', 'blockons' ),
		// 	// 'inline' => true,
		// );

		return $settings;
	}
}
new Blockons_Notices();
