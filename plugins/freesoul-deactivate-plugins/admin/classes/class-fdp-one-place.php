<?php
/**
 * Class for the "One Place Plugin" functionality.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'Eos_Fdp_Plugins_Manager_Page' ) ) {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/abstracts/class-eos-fdp-plugins-manager-page.php';
}
if ( ! class_exists( 'Fdp_One_Column_Page' ) ) {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-one-column-page.php';
}

class FDP_One_Place extends Eos_Fdp_One_Column_Page {
	
	/**
	 * Value of the saved options.
	 *
	 * @var string $options_value
	 * @since  2.5.0
	 */		
	private $options;
	
	/**
	 * Plugins that were set.
	 *
	 * @var array $plugins_set
	 * @since  2.5.0
	 */		
	private $plugins_set;

    /**
     * Custom before section.
     * 
     * @since 2.1.5
     *
     */
	public function custom_before_section() {
		$options = eos_dp_get_option( $this->page_slug );
		$this->options = $options ? stripslashes( str_replace( get_home_url(), '[home]', sanitize_text_field( $options ) ) ) : '';
		$this->plugins_set = ! empty( $this->options ) ? array_keys( json_decode( $this->options, true ) ) : array();
		?>
		<style id="fdp-one-place">
		td.eos-dp-one-place-chk-col.fdp-one-col-td{text-align:<?php echo is_rtl() ? 'left' : 'right'; ?>;border:none !important}
		#fdp-one-place-popup{padding:10px;position:fixed;width:600px;height:370px;background:#fff;top:50%;left:50%;margin:-300px -175px;z-index:9999;border:1px solid}
		#fdp-one-place-popup textarea{width:100%;height:230px;padding:10px;box-sizing:border-box;overflow-y:auto;}
		#fdp-one-place-plugin-in-popup{font-weight:bold}
		#eos-dp-setts .fdp-edited-row .eos-dp-name-td{font-weight:bold;background-color:#D3C4B8}
		</style>
		<?php
		add_filter( 'fdp_save_button_wrapper_css_class', array( $this, 'button_wrapper_css_class' ) );
	}
    /**
     * Table body.
     *
     * @param string $page_slug
     * 
     * @since 2.1.5
     *
     */
	public function tableBody( $page_slug ) {
		$n              = 0;
		$plugins        = $this->get_plugins();
		$page_slug_dash = str_replace( '_', '-', $page_slug );
		$options = json_decode( stripslashes( str_replace( '[home]', get_home_url(), $this->options ) ), true );
		foreach ( $this->active_plugins as $p ) {
			$row_class = $options && is_array( $options ) && array_key_exists( $p, $options ) && $options[ $p ] && is_array( $options[ $p ] ) && ! empty( array_filter( $options[ $p ] ) ) ? 'fdp-one-place-row fdp-edited-row' : 'fdp-one-place-row';
			if ( isset( $plugins[ $p ] ) ) {
				$plugin_name = eos_dp_get_plugin_name_by_slug( $p );
				$details_url = add_query_arg(
					array(
						'tab'         => 'plugin-information',
						'plugin'      => dirname( $p ),
						'TB_iframe'   => true,
						'eos_dp'      => $p,
						'eos_dp_info' => 'true',
					),
					admin_url( 'plugin-install.php' )
				);
				?>
		<tr class="<?php echo esc_attr( $row_class ); ?>">
		  <td class="<?php echo esc_attr( $page_slug_dash ); ?>-chk-col fdp-one-col-td">
		  	<span class="fdp-edit-one-place-plugin dashicons dashicons-edit hover"data-plugin="<?php echo esc_attr( $p ); ?>"></span>
		  </td>
		  <td class="eos-dp-name-td">
			<span class="dashicons dashicons-admin-plugins"></span>
			<a title="<?php esc_attr_e( 'View details', 'freesoul-deactivate-plugins' ); ?>" target="_blank" class="eos-dp-no-decoration" href="<?php echo esc_url( $details_url ); ?>">
				<?php echo esc_html( $plugin_name ); ?>
			</a>
		  </td>
		</tr>
				<?php
				++$n;
			}
		}
	}

    /**
     * Return saving button wrapper CSS class.
     * 
     * @since 2.1.5
     *
     */	
	public function button_wrapper_css_class() {
		return 'fdp-save-one-place-btn-wrp fdp-btn-wrp';
	}

    /**
     * After section.
     * 
     * @since 2.1.5
	 * @param  string $page_slug The page slug.
     *
     */	
	public function after_section( $page_slug ) {
		?>
		<div id="fdp-one-place-popup" style="display:none">
			<p><?php echo wp_kses_post( sprintf( apply_filters( 'fdp_one_place_popup_title', __( 'Write the only URLs where %s has to be active. Separate them by a return line.', 'freesoul-deactivate-plugins' ) ), '<span id="fdp-one-place-plugin-in-popup"></span>' ) ); ?></p>
			<p><?php echo esc_html( sprintf( __( 'Use the star * to replace any groups of characters. E.g. %s', 'freesoul-deactivate-plugins' ), '*about/' ) ); ?></p>
			<textarea id="fdp-one-place-textarea"></textarea>
			<div class="right" style="position:absolute;bottom:0;left:0;right:0;padding:10px">
				<button id="fdp-one-place-close-popup" class="button"><?php esc_html_e( 'Close' ); ?></button>
				<button id="fdp-one-place-save-popup" class="button"><?php esc_html_e( 'Save' ); ?></button>
			</div>
		</div>
		<input type="hidden" id="fdp-one-place-options" value="<?php echo esc_attr( stripslashes( $this->options ) ); ?>" />
		<?php
		wp_enqueue_script( 'fdp-one-place', EOS_DP_PLUGIN_URL . '/admin/assets/js/fdp-one-place.js' );
		wp_localize_script( 
			'fdp-one-place',
			'fdp_one_place_params',
			array( 
				'nonce' => wp_create_nonce( sanitize_key( $this->page_slug ) . '_nonce', sanitize_key( $this->page_slug ) . '_nonce' ),
				'options' => $this->options && ! empty( $this->options ) ? wp_json_encode( $this->options ) : '',
				'ajaxurl' => esc_url( admin_url( 'admin-ajax.php') ),
				'action' => 'eos_dp_one_place_save'
			) 
		);
	}

	/**
     * Empty section legend.
     * 
     * @since 2.1.5
     *
     */	
	public function section_legend() {

	}

    /**
     * Empty bulk selection.
     * 
     * @since 2.1.5
     *
     */	
	public function bulk_selection() {

	}
}
