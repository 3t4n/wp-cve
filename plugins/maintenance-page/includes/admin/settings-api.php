<?php
/**
 * Admin Options Page
 *
 * @package     Maintenance Page
 * @subpackage  Admin Options Page
 * @copyright   Copyright (c) 2014, ThemeGrill
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
*/
if ( ! class_exists( 'MP_Settings' ) ) :
	class MP_Settings {

		private $settings_api;

		function __construct() {
			$this->settings_api = new MP_Settings_API();

			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'mp_before_form_closing', array( $this, 'admin_options_sidebar' ) );
			add_action( 'mp_subscribe_settings', array( $this, 'download_csv_button' ) );
			add_filter( 'plugin_action_links_' . mp()->basename, array( $this, 'add_settings_link' ) );
		}

		function admin_init() {

			//set the settings
			$this->settings_api->set_sections( $this->get_settings_sections() );
			$this->settings_api->set_fields( $this->get_settings_fields() );

			//initialize settings
			$this->settings_api->admin_init();
		}

		function admin_menu() {
			$hook = add_options_page( 'Maintenance Page', 'Maintenance Page', 'manage_options', 'mp_settings_api', array( $this, 'plugin_page' ) );
			add_action( "admin_print_styles-$hook", array( $this, 'enqueue_admin_styles' ) );
		}

		function get_settings_sections() {
			$sections = array(
				array(
					'id'    => 'mp_basics',
					'title' => __( 'General', 'maintenance-page' ),
				),
				array(
					'id'    => 'mp_social',
					'title' => __( 'Social Icons', 'maintenance-page' ),
				),
				array(
					'id'    => 'mp_subscribe',
					'title' => __( 'Subscribe', 'maintenance-page' ),
				),
			);
			return $sections;
		}

		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		function get_settings_fields() {
			$settings_fields = array(
				'mp_basics'    => array(
					array(
						'name'  => 'mp_activate',
						'label' => __( 'Activate', 'maintenance-page' ),
						'desc'  => __( 'Activate the Maintenance Page', 'maintenance-page' ),
						'type'  => 'checkbox',
					),
					array(
						'name'  => 'mp_header_status',
						'label' => __( '503', 'maintenance-page' ),
						'desc'  => __( 'Search engines will be notified site is unavailable', 'maintenance-page' ),
						'type'  => 'checkbox',
					),
					array(
						'name'              => 'mp_logo',
						'label'             => __( 'Logo', 'maintenance-page' ),
						'desc'              => __( 'Upload Logo', 'maintenance-page' ),
						'type'              => 'file',
						'default'           => '',
						'sanitize_callback' => 'esc_url_raw',
					),
					array(
						'name'              => 'mp_heading',
						'label'             => __( 'Heading Title', 'maintenance-page' ),
						'desc'              => __( 'Main Heading for the Maintenance Page', 'maintenance-page' ),
						'type'              => 'text',
						'default'           => 'MAINTENANCE PAGE',
						'sanitize_callback' => 'sanitize_text_field',
					),
					array(
						'name'              => 'mp_message',
						'label'             => __( 'Text Message', 'maintenance-page' ),
						'desc'              => __( 'Message For your Viewer', 'maintenance-page' ),
						'type'              => 'wysiwyg',
						'sanitize_callback' => 'mp_filter_kses',
					),
					array(
						'name'              => 'mp_background_image',
						'label'             => __( 'Background Image', 'maintenance-page' ),
						'desc'              => __( 'Upload background Image', 'maintenance-page' ),
						'type'              => 'file',
						'default'           => '',
						'sanitize_callback' => 'esc_url_raw',
					),
					array(
						'name'  => 'mp_background_image_deactivate',
						'label' => __( 'Deactivate', 'maintenance-page' ),
						'desc'  => __( 'Deactivate the Background Image', 'maintenance-page' ),
						'type'  => 'checkbox',
					),
					array(
						'name'              => 'mp_background_color',
						'label'             => __( 'Background Color', 'maintenance-page' ),
						'desc'              => __( 'Choose Background Color', 'maintenance-page' ),
						'type'              => 'color',
						'default'           => '#ffffff',
						'sanitize_callback' => 'sanitize_hex_color',
					),
					array(
						'name'              => 'mp_font_color',
						'label'             => __( 'Font Color', 'maintenance-page' ),
						'desc'              => __( 'Choose font color', 'maintenance-page' ),
						'type'              => 'color',
						'default'           => '#ffffff',
						'sanitize_callback' => 'sanitize_hex_color',
					),
					array(
						'name'              => 'mp_link_color',
						'label'             => __( 'Link Color', 'maintenance-page' ),
						'desc'              => __( 'Choose Link color', 'maintenance-page' ),
						'type'              => 'color',
						'default'           => '#1BBC9B',
						'sanitize_callback' => 'sanitize_hex_color',
					),
					array(
						'name'              => 'mp_css',
						'label'             => __( 'Custom CSS', 'maintenance-page' ),
						'desc'              => __( 'Enter Your Custom CSS here', 'maintenance-page' ),
						'type'              => 'textarea',
						'sanitize_callback' => 'wp_filter_kses',
					),
					array(
						'name'              => 'mp_exclude',
						'label'             => __( 'Exclude', 'maintenance-page' ),
						'desc'              => __( 'Enter the page/post slug. One slug per line', 'maintenance-page' ),
						'type'              => 'textarea',
						'sanitize_callback' => 'wp_filter_kses',
					),
					array(
						'name'    => 'mp_footer_radio',
						'label'   => __( 'Powered by ThemeGrill', 'maintenance-page' ),
						'desc'    => __( 'How about a footer credit for our hardwork', 'maintenance-page' ),
						'type'    => 'radio',
						'options' => array(
							'yes' => __( 'Yes - we love you', 'maintenance-page' ),
							'no'  => __( 'Nope', 'maintenance-page' ),
						),
						'default' => 'no',
					),
				),
				'mp_social'    => array(
					array(
						'name'              => 'mp_facebook',
						'label'             => __( 'Facebook', 'maintenance-page' ),
						'desc'              => __( 'Enter Facebook url', 'maintenance-page' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'esc_url_raw',
					),
					array(
						'name'              => 'mp_twitter',
						'label'             => __( 'Twitter', 'maintenance-page' ),
						'desc'              => __( 'Enter Twitter url', 'maintenance-page' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'esc_url_raw',
					),
					array(
						'name'              => 'mp_pinterest',
						'label'             => __( 'Pinterest', 'maintenance-page' ),
						'desc'              => __( 'Enter Pinterest url', 'maintenance-page' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'esc_url_raw',
					),
					array(
						'name'              => 'mp_google',
						'label'             => __( 'GooglePlus', 'maintenance-page' ),
						'desc'              => __( 'Enter GooglePlus url', 'maintenance-page' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'esc_url_raw',
					),
					array(
						'name'              => 'mp_youtube',
						'label'             => __( 'Youtube', 'maintenance-page' ),
						'desc'              => __( 'Enter Youtube url', 'maintenance-page' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'esc_url_raw',
					),
					array(
						'name'              => 'mp_linkedin',
						'label'             => __( 'LinkedIn', 'maintenance-page' ),
						'desc'              => __( 'Enter LinkedIn url', 'maintenance-page' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'esc_url_raw',
					),
				),
				'mp_subscribe' => array(
					array(
						'name'  => 'mp_subscribe_activate',
						'label' => __( 'Activate', 'maintenance-page' ),
						'desc'  => __( 'Activate subscribe feature', 'maintenance-page' ),
						'type'  => 'checkbox',
					),
					array(
						'name'              => 'mp_subscribe_title',
						'label'             => __( 'Subscribe Info Title', 'maintenance-page' ),
						'desc'              => __( 'Title For the Subscribe information', 'maintenance-page' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					),
					array(
						'name'              => 'mp_subscribe_message',
						'label'             => __( 'Subscribe Message', 'maintenance-page' ),
						'desc'              => __( 'Message for subscribe text', 'maintenance-page' ),
						'type'              => 'wysiwyg',
						'sanitize_callback' => 'wp_kses_post',
					),
					array(
						'name'  => 'mp_subscribe_settings',
						'label' => __( 'Download', 'maintenance-page' ),
						'desc'  => '',
						'type'  => 'hook',
					),
				),
			);

			return $settings_fields;
		}

		function plugin_page() {
			echo '<div class="wrap">';

			$this->settings_api->show_navigation();
			$this->settings_api->show_forms();

			echo '</div>';
		}

		/**
		 * Get all the pages
		 *
		 * @return array page names with key value pairs
		 */
		function get_pages() {
			$pages         = get_pages();
			$pages_options = array();
			if ( $pages ) {
				foreach ( $pages as $page ) {
					$pages_options[ $page->ID ] = $page->post_title;
				}
			}

			return $pages_options;
		}

		/**
		 * Get the value of a settings field
		 *
		 * @param string $option settings field name
		 * @param string $section the section name this field belongs to
		 * @param string $default default text if it's not found
		 * @return mixed
		 */
		function get_option( $option, $section, $default = '' ) {

			$options = get_option( $section );

			if ( isset( $options[ $option ] ) ) {
				return $options[ $option ];
			}

			return $default;
		}

		/**
		 * Add the settings link to the plugins page
		 *
		 * @param string $links
		 * @return array
		 */
		function add_settings_link( $links ) {
			$settings_link = '<a href="options-general.php?page=mp_settings_api">' . __( 'Settings', 'maintenance-page' ) . '</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}

		/**
		 * Sends ajax request to download subscriber list.
		 */
		function download_csv_button() {
			global $wpdb;
			$count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}maintenance_page" );
			if ( $count == 0 ) {
				_e( 'No subscribers yet', 'maintenance-page' );
			} else {
				$url = admin_url() . 'admin-ajax.php?action=subscribe_ajax';
				echo '<a class="csv-download button" href="' . wp_nonce_url( $url, 'subscribe_download' ) . '">' . __( 'Download Subscriber CSV list', 'maintenance-page' ) . '</a>';
			}
		}

		/**
		 * Load CSS files
		 */
		public function enqueue_admin_styles() {
			wp_enqueue_style( 'mp-admin-styles', mp()->plugin_url . 'includes/admin/css/admin.css' );
		}


		/**
		 * Adds the sidebar section on the admin page
		 * @return void
		 */
		function admin_options_sidebar() {
			?>
		<div id="options-sidebar">
			<div class="metabox-holder">
				<div class="postbox">
					<h3><?php esc_attr_e( 'Plugin Info', 'spacious' ); ?></h3>
					<div class="inside">
						<div class="option-btn">
							<a href="<?php echo esc_url( 'http://themegrill.com/plugins/maintenance-page-pro/' ); ?>" target="_blank" rel="nofollow">
								<img src="<?php echo mp()->plugin_url . 'includes/admin/images/maintenance-page-pro.jpg'; ?>" alt="<?php _e( 'maintenance-page-pro', 'maintenance-page' ); ?>">
							</a>
						</div>
						<div class="option-btn"><a class="btn upgrade" target="_blank" href="<?php echo esc_url( 'http://themegrill.com/plugins/maintenance-page-pro/' ); ?>"><?php esc_attr_e( 'Upgrade', 'maintenance-page' ); ?></a></div>
						<div class="option-btn"><a class="btn support" target="_blank" href="<?php echo esc_url( 'http://themegrill.com/support-forum/' ); ?>"><?php esc_attr_e( 'Free Support', 'maintenance-page' ); ?></a></div>
						<div class="option-btn"><a class="btn demo" target="_blank" href="<?php echo esc_url( 'http://themegrill.com/plugins/maintenance-page/' ); ?>"><?php esc_attr_e( 'View Demo', 'maintenance-page' ); ?></a></div>
						<div align="center" style="padding:5px; background-color:#fafafa;border: 1px solid #CCC;margin-bottom: 10px;">
						<strong><?php esc_attr_e( 'If you like our work. Buy us a beer.', 'esteem' ); ?></strong>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="8AHDCA8CDGAJG">
							<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal">
							<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
						</form>
					</div>
					</div><!-- inside -->
				</div><!-- .postbox -->
			</div><!-- .metabox-holder -->
		</div><!-- #options-sidebar -->
			<?php
		}
	}
endif;
