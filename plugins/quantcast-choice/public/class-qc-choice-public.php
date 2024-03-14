<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.quantcast.com
 * @since      1.0.0
 *
 * @package    QC_Choice
 * @subpackage QC_Choice/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    QC_Choice
 * @subpackage QC_Choice/public
 * @author     Ryan Baron <rbaron@quantcast.com>
 */
class QC_Choice_Public {

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
	 * Quantcast.com account Universal Tag ID.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      array    $qc_choice_utid    A string value that should contain a valid quantcast.com utid.
	 */
	private $qc_choice_cmp_utid;

	/**
	 * How to display the Chioce CCPA msg '', 'auto', 'manual'.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      array    $qc_choice_cmp_ccpa_wp_footer    A string value to indicate the CCPA footer display style.
	 */
	private $qc_choice_cmp_ccpa_wp_footer;

	/**
	 * Enable automatic Data Layer push.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $qc_choice_cmp_datalayer_push    Enable automatic push of consent signals to the data layer.
	 */
	private $qc_choice_cmp_datalayer_push;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->qc_choice_cmp_utid = esc_attr( get_option( 'qc_choice_cmp_utid' ) );
		$this->qc_choice_cmp_ccpa_wp_footer = esc_attr( get_option( 'qc_choice_cmp_ccpa_wp_footer' ) );
		$this->qc_choice_cmp_datalayer_push = esc_attr( get_option( 'qc_choice_cmp_datalayer_push' ) );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		if( ! empty( $this->qc_choice_cmp_utid ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/style.min.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/script.min.js', '', $this->version, false );

		$choice_cmp_config = array(
			'utid' => $this->qc_choice_cmp_utid,
			'ccpa' => $this->qc_choice_cmp_ccpa_wp_footer,
			'datalayer' => $this->qc_choice_cmp_datalayer_push,
		);

		wp_localize_script( $this->plugin_name, 'choice_cmp_config', $choice_cmp_config );

	}

	/**
	 * Javascript async attribute filter.
	 *
	 * @since    1.0.0
	 */
	public function add_async_attribute($tag, $handle) {

		// Add scripts to the array to make the async
		$scripts_to_async = array(
			$this->plugin_name,
		);

		foreach($scripts_to_async as $async_script) {
			if ($async_script === $handle) {
				return str_replace(' src', ' async="async" src', $tag);
			}
		}

		return $tag;
	}


	/**
	 * Add a container to the footer with the id 'choice-footer-msg' where choice
	 *   automatically add the CCPA message.
	 *
	 * @since    2.0.0
	 */
	public function add_footer_message_container() {
		// Only add the footer message container if the admin has enabled auto adding.
		if( 'auto' === $this->qc_choice_cmp_ccpa_wp_footer ) {
			echo '<div class="container container-choice-footer-msg"><div class="container-inside"><div id="choice-footer-msg" class="choice-footer-msg"></div></div></div>';
		}
	}
}
