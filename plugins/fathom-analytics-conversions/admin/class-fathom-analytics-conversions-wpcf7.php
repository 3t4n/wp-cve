<?php

/**
 * The CF7-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.0
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/wpcf7
 */

/**
 * The cf7-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the wpcf7-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/wpcf7
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.com.au>
 */
class Fathom_Analytics_Conversions_WPCF7 {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Add js to track the form submission.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}

	/**
	 * Adds a meta box to CF7 form admin
	 *
	 * @since    1.0.0
	 * @param      array    $panels       The panels of this form.
	 */
	public function fac_cf7_meta_box($panels) {
		global $fac4wp_options;
		$new_page = [];
		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WPCF7 ] ) {
			$new_page = array(
				'fac-cf7' => array(
					'title' => __( 'Fathom Analytics', 'fathom-analytics-conversions' ),
					'callback' => [$this, 'fac_cf7_box']
				)
			);
		}

		return array_merge($panels, $new_page);
	}

	/**
	 * Define the Fathom Analytics content
	 *
	 * @since    1.0.0
	 * @param      object    $args       The form data.
	 */
	public function fac_cf7_box($args) {
		$cf7_id = $args->id();
		$fac_cf7_defaults = array();
		$fac_cf7 = get_option( 'fac_cf7_'.$cf7_id, $fac_cf7_defaults );
		$fac_cf7_event_id = isset($fac_cf7['event_id']) ? $fac_cf7['event_id'] : '';
		echo '<div class="cf7-fac-box">';
		?>
		<fieldset>
			<table class="form-table">
				<tbody>
				<tr>
					<th scope="row">
						<label for="fac_cf7_event_id">
							<?php echo esc_html__('Event ID', 'fathom-analytics-conversions'); ?>
						</label>
					</th>
					<td>
						<input type="text" id="fac_cf7_event_id" name="fac_cf7[event_id]" class="" value="<?php echo esc_attr($fac_cf7_event_id);?>" readonly>

						<p class="note">This event id is created for you automatically, and maintained by the Fathom Analytics Conversions plugin. You can refer to it in your Fathom Analytics settings.</p>

					</td>
				</tr>
				</tbody>
			</table>
		</fieldset>
		<?php
		echo '</div>';
	}

	/**
	 * Save FAC CF7 options
	 *
	 * @since    1.0.0
	 * @param      object    $args       The form data.
	 */
	public function fac_cf7_save_options($args) {
		if ( ! empty( $_POST ) && isset( $_POST['fac_cf7'] ) ){

			$default = array () ;
			//$fac_cf7 = get_option( 'fac_cf7'.$args->id(), $default );

			$fac_cf7_val = fac_array_map_recursive( 'esc_attr', $_POST['fac_cf7'] );

			update_option( 'fac_cf7_' . $args->id(), $fac_cf7_val );
		}
	}

	/**
	 * Check to add/update event id to new cf7 form
	 *
	 * @since    1.0.0
	 * @param      object    $args       The form data.
	 */
	public function fac_wpcf7_after_save($args) {
		$form_id = $args->id();
		$title = wp_slash( $args->title() );

		$fac_cf7 = get_option( 'fac_cf7_'.$form_id, [] );
		$fac_cf7_event_id = isset($fac_cf7['event_id']) ? $fac_cf7['event_id'] : '';
		if(empty($fac_cf7_event_id)) {
			fa_add_event_id_to_cf7($form_id, $title);
		}
		else {
			// check if event id exist
			$event = fac_get_fathom_event($fac_cf7_event_id);
			if( $event['code'] !== 200 ) {
				fa_add_event_id_to_cf7($form_id, $title);
			}
			else fac_update_fathom_event($fac_cf7_event_id, $title);
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $fac4wp_options, $fac4wp_plugin_url;

		if ( $fac4wp_options[ FAC4WP_OPTION_INTEGRATE_WPCF7 ] && ( $fac4wp_options['fac_fathom_analytics_is_active'] || ! empty( $fac4wp_options[ FAC_OPTION_INSTALLED_TC ] ) ) ) {
			if ( ! ( empty( $fac4wp_options[ FAC_FATHOM_TRACK_ADMIN ] ) && current_user_can( 'manage_options' ) ) ) { // track visits by administrators!

				$in_footer = apply_filters( 'fac4wp_' . FAC4WP_OPTION_INTEGRATE_WPCF7, true );
				wp_enqueue_script( 'fac-contact-form-7-tracker', $fac4wp_plugin_url . 'public/js/fac-contact-form-7-tracker.js', array(), FATHOM_ANALYTICS_CONVERSIONS_VERSION, $in_footer );
			}
		}
	}

}
