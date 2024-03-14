<?php
/**
 * The settings functionality of the plugin.
 *
 * @link       www.theritesites.com
 * @since      1.0.0
 *
 * @package    Enhanced_Ajax_Add_To_Cart_Wc
 * @subpackage Enhanced_Ajax_Add_To_Cart_Wc/src
 * @author     TheRiteSites <contact@theritesites.com>
 */

namespace TRS\EAA2C;

if ( ! class_exists( 'TRS\EAA2C\Settings' ) ) {
	class Settings {

		protected $settings_page;

		public function register_menu_item() {
			$this->settings_page = add_submenu_page(
				'woocommerce',
				__( 'Add to Cart Button', EAA2C_NAME ),
				__( 'Add to Cart Button', EAA2C_NAME ),
				'manage_woocommerce',
				'a2cp-page',
				array( $this, 'a2cp_settings_page_callback' )
			);
			add_settings_section(
				'a2cp_settings',
				// __( 'General Settings', EAA2C_NAME ),
				'',
				array( $this, 'display_all_settings_callback' ),
				$this->settings_page
				// 'a2cp_settings'
			);
		}

		public function register_settings() {
			register_setting(
				'a2cp_settings',
				'a2cp_button_blocking',
				array(
					'type' => 'boolean',
					'description' => '',
					// 'sanitize_callback' => array( $this, '' ),
					'show_in_rest' => true
					// 'default' => false
				)
			);
			register_setting(
				'a2cp_settings',
				'a2cp_debug',
				array(
					'type' => 'boolean',
					'description' => '',
					// 'sanitize_callback' => array( $this, '' ),
					'show_in_rest' => true
					// 'default' => false
				)
			);
			register_setting(
				'a2cp_settings',
				'a2cp_dom_check',
				array(
					'type' => 'boolean',
					'description' => '',
					// 'sanitize_callback' => array( $this, '' ),
					'show_in_rest' => true
					// 'default' => false
				)
			);
			register_setting(
				'a2cp_settings',
				'a2cp_stop_refresh_frags',
				array(
					'type' => 'boolean',
					'description' => '',
					// 'sanitize_callback' => array( $this, '' ),
					'show_in_rest' => true
					// 'default' => false
				)
			);
			register_setting(
				'a2cp_settings',
				'a2cp_delete_on_deactivation',
				array(
					'type' => 'boolean',
					'description' => '',
					// 'sanitize_callback' => array( $this, '' ),
					'show_in_rest' => true
					// 'default' => false
				)
			);
			register_setting(
				'a2cp_settings',
				'a2cp_custom_class',
				array(
					'type' => 'text',
					'description' => '',
					// 'sanitize_callback' => array( $this, '' ),
					'show_in_rest' => true
					// 'default' => false
				)
			);
			register_setting(
				'a2cp_settings',
				'a2cp_default_text',
				array(
					'type' => 'text',
					'description' => '',
					// 'sanitize_callback' => array( $this, '' ),
					'show_in_rest' => true
					// 'default' => false
				)
			);
			register_setting(
				'a2cp_settings',
				'a2cp_after_add_text',
				array(
					'type' => 'text',
					'description' => '',
					// 'sanitize_callback' => array( $this, '' ),
					'show_in_rest' => true
					// 'default' => false
				)
			);
			register_setting(
				'a2cp_settings',
				'a2cp_after_add_url',
				array(
					'type' => 'text',
					'description' => '',
					// 'sanitize_callback' => array( $this, '' ),
					'show_in_rest' => true
					// 'default' => false
				)
			);
			register_setting(
				'a2cp_settings',
				'a2cp_out_of_stock',
				array(
					'type' => 'boolean',
					'description' => '',
					// 'sanitize_callback' => array( $this, '' ),
					'show_in_rest' => true
					// 'default' => false
				)
			);
			register_setting(
				'a2cp_settings',
				'a2cp_image_field',
				array(
					'type' => 'boolean',
					'description' => '',
					'show_in_rest' => true,
				)
			);
			register_setting(
				'a2cp_settings',
				'a2cp_custom_field',
				array(
					'type' => 'boolean',
					'description' => '',
					'show_in_rest' => true,
				)
			);
			register_setting(
				'a2cp_settings',
				'a2cp_short_description',
				array(
					'type' => 'boolean',
					'description' => '',
					'show_in_rest' => true,
				)
			);
			register_setting(
				'a2cp_settings',
				'a2cp_delete_on_deactivation',
				array(
					'type' => 'boolean',
					'description' => '',
					// 'sanitize_callback' => array( $this, '' ),
					'show_in_rest' => true
					// 'default' => false
				)
			);
			add_settings_section(
				'a2cp_settings',
				// __( 'General Settings', EAA2C_NAME ),
				'',
				array( $this, 'display_all_settings_callback' ),
				$this->settings_page
				// 'a2cp_settings'
			);
		}

		public function display_all_settings_callback( $args ) {
			$this->render_display_options();
			$this->render_general_settings();
			$this->render_element_options();
		}

		public function render_display_options() {

			add_settings_field(
				'a2cp_display_subheading',
				// __( 'Display Options', EAA2C_NAME ),
				'',
				array( $this, 'subheading' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'title' => __( 'Display Options', EAA2C_NAME )
				)
			);
			add_settings_field(
				'a2cp_custom_class',
				__( 'Custom class on all parent elements of this plugin?', EAA2C_NAME ),
				array( $this, 'text_input' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'name' => 'a2cp_custom_class',
					'type' => 'text',
					'value' => get_option( 'a2cp_custom_class' )
				)
			);
			add_settings_field(
				'a2cp_default_text',
				__( 'Change default "Add to Cart" text?', EAA2C_NAME ),
				array( $this, 'text_input' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'name' => 'a2cp_default_text',
					'type' => 'text',
					'value' => get_option( 'a2cp_default_text' ),
				)
			);
			add_settings_field(
				'a2cp_after_add_text',
				__( 'Change default "View cart" button/link text?', EAA2C_NAME ),
				array( $this, 'text_input' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'name' => 'a2cp_after_add_text',
					'type' => 'text',
					'value' => get_option( 'a2cp_after_add_text' ),
				)
			);
			add_settings_field(
				'a2cp_after_add_url',
				__( 'Change default url for the "View cart" (or custom text) button/link?', EAA2C_NAME ),
				array( $this, 'text_input' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'name' => 'a2cp_after_add_url',
					'type' => 'text',
					'value' => get_option( 'a2cp_after_add_url' ),
				)
			);
		}

		public function render_element_options() {
			add_settings_field(
				'a2cp_element_subheading',
				'',
				array( $this, 'subheading' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'title' => __( 'Element Options', EAA2C_NAME ),
				)
			);
			// $image_field = empty( get_option( 'a2cp_image_field' ) ) ? 0 : get_option( 'a2cp_image_field' );
			add_settings_field(
				'a2cp_image_field',
				__( 'Allow images to be used on shortcodes and blocks?', EAA2C_NAME ),
				array( $this, 'toggle_button' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'name' => 'a2cp_image_field',
					'type' => 'checkbox',
					// 'value' => $image_field,
					'desc' => $this->get_premium_description_link(),
					'class' => 'disabled',
					'disabled' => true
				)
			);
			// $custom_field = empty( get_option( 'a2cp_custom_field' ) ) ? 0 : get_option( 'a2cp_custom_field' );
			add_settings_field(
				'a2cp_custom_field',
				__( 'Allow custom text input to be used on blocks?', EAA2C_NAME ),
				array( $this, 'toggle_button' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'name' => 'a2cp_custom_field',
					'type' => 'checkbox',
					// 'value' => $custom_field,
					'desc' => $this->get_premium_description_link(),
					'class' => 'disabled',
					'disabled' => true
				)
			);
			// $short_description = empty( get_option( 'a2cp_short_description' ) ) ? 0 : get_option( 'a2cp_short_description' );
			add_settings_field(
				'a2cp_short_description',
				__( 'Allow WC Product Short Description to be used on blocks?', EAA2C_NAME ),
				array( $this, 'toggle_button' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'name' => 'a2cp_short_description',
					'type' => 'checkbox',
					// 'value' => $short_description,
					'desc' => $this->get_premium_description_link(),
					'class' => 'disabled',
					'disabled' => true
				)
			);
		}

		public function render_general_settings() {
			
			add_settings_field(
				'a2cp_general_subheading',
				'',
				array( $this, 'subheading' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'title' => __( 'General Settings', EAA2C_NAME )
				)
			);
			$blocking = empty( get_option( 'a2cp_button_blocking' ) ) ? 0 : get_option( 'a2cp_button_blocking' );
			add_settings_field(
				'a2cp_button_blocking',
				__( 'Block buttons per request?', EAA2C_NAME ),
				array( $this, 'toggle_button' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'name' => 'a2cp_button_blocking',
					'type' => 'checkbox',
					'value' => $blocking
				)
			);
			$stop_rf = empty( get_option( 'a2cp_stop_refresh_frags' ) ) ? 0 : get_option( 'a2cp_stop_refresh_frags' );
			add_settings_field(
				'a2cp_stop_refresh_frags',
				__( 'Disable internal "Refresh Cart Fragments" during add to cart request?', EAA2C_NAME ),
				array( $this, 'toggle_button' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'name' => 'a2cp_stop_refresh_frags',
					'type' => 'checkbox',
					'value' => $stop_rf
				)
			);
			$out_of_stock = empty( get_option( 'a2cp_out_of_stock') ) ? 0 : get_option( 'a2cp_out_of_stock' );
			add_settings_field(
				'a2cp_out_of_stock',
				__( 'Disable out of stock check?', EAA2C_NAME ),
				array( $this, 'toggle_button' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'name' => 'a2cp_out_of_stock',
					'type' => 'checkbox',
					'value' => $out_of_stock
				)
			);
			$debug = empty( get_option( 'a2cp_debug' ) ) ? 0 : get_option( 'a2cp_debug' );
			add_settings_field(
				'a2cp_debug',
				__( 'Enable debug mode?', EAA2C_NAME ),
				array( $this, 'toggle_button' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'name' => 'a2cp_debug',
					'type' => 'checkbox',
					'value' => $debug
				)
			);
			$dom_check = empty( get_option( 'a2cp_dom_check' ) ) ? 0 : get_option( 'a2cp_dom_check' );
			add_settings_field(
				'a2cp_dom_check',
				__( 'Check if DOM was updated per request?', EAA2C_NAME ),
				array( $this, 'toggle_button' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'name' => 'a2cp_dom_check',
					'type' => 'checkbox',
					'desc' => __( 'This is for customized javascript frontend implementations. If you are having issues with an updated quantity not adding to cart, try this setting.', EAA2C_NAME ),
					'value' => $dom_check,
					// 'class' => 'disabled',
					// 'disabled' => true
				)
			);
			$full_uninstall = empty( get_option( 'a2cp_delete_on_deactivation' ) ) ? 0 : get_option( 'a2cp_delete_on_deactivation' );
			add_settings_field(
				'a2cp_delete_on_deactivation',
				__( 'Delete data on uninstall?', EAA2C_NAME ),
				array( $this, 'toggle_button' ),
				$this->settings_page,
				'a2cp_settings',
				array(
					'name' => 'a2cp_delete_on_deactivation',
					'type' => 'checkbox',
					'value' => $full_uninstall,
					// 'class' => 'disabled',
					// 'disabled' => true
				)
			);
		}

		public function toggle_button( $args ) {
			$checked = empty( $args['value'] ) || $args['value'] === 0 ? '' : ' checked';
			$disabled = isset( $args['disabled'] ) && $args['disabled'] === true ? ' disabled ' : '';
			echo '<input type="' . esc_attr($args['type'] ) . '" name="' . esc_attr($args['name'] ) . '"' . esc_attr( $checked . $disabled ) . '/>';
			if ( ! empty( $args['desc'] ) ) {
				echo '<p class="description">' . $args['desc'] . '</p>';
			}
		}

		public function text_input( $args ) {
			$disabled = isset( $args['disabled'] ) && $args['disabled'] === true ? ' disabled ' : '';
			echo '<input name="' . esc_attr($args['name'] ) . '" type="' . esc_attr($args['type'] ) . '" value="' . esc_attr( $args['value'] ) . '"' . esc_attr( $disabled ) . ' />';
			if ( ! empty( $args['desc'] ) ) {
				echo '<p class="description">' . $args['desc'] . '</p>';
			}
		}

		public function subheading( $args ) {
			echo '<h3>' . $args['title'] . '</h3>';
			if ( ! empty( $args['desc'] ) ) {
				echo '<p class="description">' . $args['desc'] . '</p>';
			}
		}

		public function a2cp_settings_page_callback() {
			?>
			<form action="options.php" method="post">
				<div class="a2cp-settings">
					<h1>Enhanced AJAX Add to Cart Settings</h1>
				</div>
				<?php
				settings_fields( 'a2cp_settings' );
				do_settings_sections( $this->settings_page );
				submit_button();
				?>
			</form>
			<?php
		}

		public function get_premium_description_link() {
			$link = sprintf( wp_kses( __( 'To use this setting, get <a href="%s">premium</a>!', EAA2C_NAME ), array( 'a' => array( 'href' => array() ) ) ), esc_url( 'https://www.theritesites.com/plugins/enhanced-ajax-add-to-cart-woocommerce' ) );
			return $link;
		}
	}
}