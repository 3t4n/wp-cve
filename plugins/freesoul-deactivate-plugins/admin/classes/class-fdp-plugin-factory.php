<?php
/**
 * Class for creating new plugins.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.


class FDP_Plugin_Factory extends Eos_Fdp_Plugins_Manager_Page {

	/**
	 * Saving button CSS class.
	 *
	 * @since 2.1.9
	 * @var string
	 */
	public $button_class;

    /**
     * Custom before section.
     * 
     * @since 2.1.9
     *
     */
	public function before_section( $page_slug ) {
		$this->button_class = 'button';
		?>
		<style id="fdp-<?php echo esc_attr( $page_slug ); ?>">#fdp-edit-new-plugin[href="#"]{display:none}</style>
		<?php
	}

	/**
     * Main section.
     * 
     * @since 2.1.9
     *
     */
	public function section( $page_slug ) {
		$user  = wp_get_current_user();
		wp_nonce_field( 'fdp_create_plugin', 'fdp_create_plugin' );
		?>
		<section id="fdp-create-plugin-wrp">
			<?php if( ! is_writable( WP_PLUGIN_DIR ) ) { ?>
			<div class="eos-dp-notice notice notice-warning" style="padding:10px;font-size:20px"><?php esc_html_e( 'It looks you have no rights to write in the plugins folder. It will not be possible to create any plugin until the plugins folder is writable.', 'freesoul-deactivate-plugins' ); ?></div>
			<?php } ?>
			<h2><?php esc_html_e( 'Create custom plugin.', 'eos-dp-pro' ); ?></h2>
				<label for="fdp-create-plugin-name"><?php esc_html_e( 'Plugin name', 'freesoul-deactivate-plugins' ); ?></label>
				<p><input id="fdp-create-plugin-name" type="text" class="regular-text" placeholder="<?php esc_attr_e( 'My custom plugin', 'freesoul-deactivate-plugins' ); ?>" /></p>
				<label for="fdp-create-plugin-author"><?php esc_html_e( 'Plugin author', 'freesoul-deactivate-plugins' ); ?></label>
				<p><input id="fdp-create-plugin-author" type="text" class="regular-text" placeholder="<?php echo esc_attr( ucfirst( $user->user_login ) ); ?>" /></p>
				<label for="fdp-create-plugin-author_uri"><?php esc_html_e( 'Author URI', 'freesoul-deactivate-plugins' ); ?></label>
				<p><input id="fdp-create-plugin-author_uri" type="url" class="regular-text" placeholder="<?php echo esc_url( $user->user_url ); ?>" /></p>
				<label for="fdp-create-plugin-description"><?php esc_html_e( 'Plugin description', 'freesoul-deactivate-plugins' ); ?></label>
				<p><input id="fdp-create-plugin-description" type="text" style="width:100%;max-width:600px" placeholder="<?php esc_attr_e( 'My custom code.', 'freesoul-deactivate-plugins' ); ?>" /></p>
				<iframe id="fdp-code-editor" class="eos-hidden" src="<?php echo esc_attr( EOS_DP_PLUGIN_URL . '/inc/fdp-creating-plugin.html' ); ?>" style="width:100%;border:none !important;min-height:700px !important;margin-left:-20px;margin-right:-20px !important"></iframe>
		</section>
		<?php
	}
    /**
     * Table body.
     *
     * @param string $page_slug
     * 
     * @since 2.1.9
     *
     */
	public function tableBody( $page_slug ) {

	}

	/**
     * Empty section legend.
     * 
     * @since 2.1.9
     *
     */	
	public function section_legend() {

	}

    /**
     * Empty bulk selection.
     * 
     * @since 2.1.9
     *
     */	
	public function bulk_selection() {

	}

	/**
     * Get nonces map.
     * 
     * @since 2.1.9
     *
     */	
	public function get_nonces_map() {

	}

	/**
	 * Page footer.
	 *
	 * @since  2.1.9
	 * 
	 */
	public function footer( $button_class ) {
		require_once EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-footer.php';
		if ( current_user_can( 'activate_plugins' ) ) {
		?>
		<div id="fdp-create-plugins-instructions">
			<p style="margin-top:32px"><span id="fdp-create-plugin" class="button<?php echo  ! is_writable( WP_PLUGIN_DIR ) ? ' eos-no-events' : ''; ?>"><span class="dashicons dashicons-admin-plugins" style="margin:4px 0"></span><?php esc_html_e( 'Create plugin', 'freesoul-deactivate-plugins' ); ?></span></p>
			<p><?php esc_html_e( 'Pushing the button FDP will create an empty new plugin for your custom code.', 'freesoul-deactivate-plugins' ); ?></p>
			<p><?php esc_html_e( 'You will need to activate the new plugin as you do for other plugins.', 'freesoul-deactivate-plugins' ); ?></p>
		</div>
		<div id="fdp-success" class="eos-dp-notice notice notice-success eos-dp-opts-msg_success eos-hidden" style="padding:10px"><?php esc_html_e( 'Plugin created successfully!', 'freesoul-deactivate-plugins' ); ?>
			<div class="eos-dp-margin-top-15">
				<a id="fdp-edit-new-plugin" class="button" href="#" target="_blank"><?php esc_html_e( 'Open Code Editor.', 'freesoul-deactivate-plugins' ); ?></a>
				<a id="fdp-activate-new-plugin" class="button" href="#" target="_blank"><?php esc_html_e( 'Activate your new plugin.', 'freesoul-deactivate-plugins' ); ?></a>
			</div>
		</div>
		<div id="fdp-fail" class="eos-dp-notice notice notice-error eos-hidden" style="padding:10px" data-default_msg="<?php esc_attr_e( 'Something went wrong!', 'eos-dp-pro' ); ?>"></div>
		<?php
		}
	}	
}
