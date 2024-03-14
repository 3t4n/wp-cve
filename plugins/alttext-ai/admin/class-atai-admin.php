<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://alttext.ai
 * @since      1.0.0
 *
 * @package    ATAI
 * @subpackage ATAI/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    ATAI
 * @subpackage ATAI/admin
 * @author     AltText.ai <info@alttext.ai>
 */
class ATAI_Admin {
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
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'wp-i18n', 'jquery' ), $this->version, true );
    wp_localize_script( $this->plugin_name, 'wp_atai', array(
      'ajax_url'                                => admin_url( 'admin-ajax.php' ),
      'security_insufficient_credits_notice'    => wp_create_nonce( 'atai_insufficient_credits_notice' ),
      'security_single_generate'                => wp_create_nonce( 'atai_single_generate' ),
      'security_bulk_generate'                  => wp_create_nonce( 'atai_bulk_generate' ),
      'security_enrich_post_content'            => wp_create_nonce( 'atai_enrich_post_content' ),
      'security_enrich_post_content_transient'  => wp_create_nonce( 'atai_enrich_post_content_transient' ),
      'security_update_toggle'                  => wp_create_nonce( 'atai_update_toggle' ),
      'security_check_attachment_eligibility'   => wp_create_nonce( 'atai_check_attachment_eligibility' ),
      'can_user_upload_files'                   => current_user_can( 'upload_files' ),
      'should_update_title'                     => get_option( 'atai_update_title' ),
      'should_update_caption'                   => get_option( 'atai_update_caption' ),
      'should_update_description'               => get_option( 'atai_update_description' ),
      'icon_button_generate'                    => plugin_dir_url( ATAI_PLUGIN_FILE ) . 'admin/img/icon-button-generate.png',
      'has_api_key'                             => ATAI_Utility::get_api_key() ? true : false,
      'settings_page_url'                       => admin_url( 'admin.php?page=atai' ),
    ) );
    wp_set_script_translations( $this->plugin_name, 'alttext-ai' );
	}

  /**
   * Filters the array of row meta for each/specific plugin in the Plugins list table.
   * Appends additional links below each/specific plugin on the plugins page.
   *
   * @access  public
   * @param   array       $links_array            An array of the plugin's metadata
   * @param   string      $plugin_file_name       Path to the plugin file
   * @param   array       $plugin_data            An array of plugin data
   * @param   string      $status                 Status of the plugin
   * @return  array       $links_array
   */
  public function modify_plugin_row_meta( $links_array, $plugin_file_name, $plugin_data, $status ) {
    if ( strpos( $plugin_file_name, basename(ATAI_PLUGIN_FILE) ) ) {
      $links_array[] = '<a href="https://alttext.ai/docs/integrations/wordpress/" target="_blank" rel="noopener noreferrer">' .
       __('Documentation', 'alttext-ai') .
       '</a>';
    }

    return $links_array;
  }
}
