<?php
/**
 * The options handling functionality of the plugin.
 *
 * @link       https://alttext.ai
 * @since      1.0.0
 *
 * @package    ATAI
 * @subpackage ATAI/admin
 */

/**
 * Options page functionality of the plugin.
 *
 * Renders the Options page, sanitizes, stores and fetches the options.
 *
 * @package    ATAI
 * @subpackage ATAI/admin
 * @author     AltText.ai <info@alttext.ai>
 */
class ATAI_Settings {
  /**
	 * The account information returned by the API.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array/boolean    $account    The account information.
	 */
	private $account;

  /**
   * Load account information from API.
   *
   * @since 1.0.0
   * @access private
   */
  private function load_account() {
    $api_key = ATAI_Utility::get_api_key();
    $this->account = array(
      'plan'          => '',
      'expires_at'    => '',
      'usage'         => '',
      'quota'         => '',
      'available'     => '',
      'whitelabel'    => false,
    );

    if ( empty( $api_key ) ) {
      return;
    }

    $api = new ATAI_API( $api_key );
    $this->account = $api->get_account();
  }

  /**
   * Register the settings pages for the plugin.
   *
   * @since    1.0.0
	 * @access   public
   */
	public function register_settings_pages() {
    // Main page
		add_menu_page(
			__( 'AltText.ai WordPress Settings', 'alttext-ai' ),
			__( 'AltText.ai', 'alttext-ai' ),
			'manage_options',
      'atai',
			array( $this, 'render_settings_page' ),
      'dashicons-format-image'
		);

    add_submenu_page(
      'atai',
      __( 'AltText.ai WordPress Settings', 'alttext-ai' ),
      __( 'Settings', 'alttext-ai' ),
      'manage_options',
      'atai'
    );

    // Bulk Generate Page
    if ( ATAI_Utility::get_api_key() ) {
      add_submenu_page(
        'atai',
        __( 'Bulk Generate', 'alttext-ai' ),
        __( 'Bulk Generate', 'alttext-ai' ),
        'manage_options',
        'atai-bulk-generate',
        array( $this, 'render_bulk_generate_page' )
      );
    }

    // CSV Import Page
    add_submenu_page(
      'atai',
      __( 'Sync Library', 'alttext-ai' ),
      __( 'Sync Library', 'alttext-ai' ),
      'manage_options',
      'atai-csv-import',
      array( $this, 'render_csv_import_page' )
    );
	}

  /**
   * Render the settings page.
   *
   * @since    1.0.0
	 * @access   public
   */
  public function render_settings_page() {
    $this->load_account();
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/settings.php';
  }

  /**
   * Render the bulk generate page.
   *
   * @since    1.0.0
	 * @access   public
   */
  public function render_bulk_generate_page() {
    $this->load_account();
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/bulk-generate.php';
  }

  /**
   * Render the CSV import page.
   *
   * @since    1.1.0
	 * @access   public
   */
  public function render_csv_import_page() {
    $this->load_account();
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/csv-import.php';
  }

  /**
   * Register setting group.
   *
   * @since    1.0.0
	 * @access   public
   */
  public function register_settings() {
    register_setting(
			'atai-settings',
			'atai_api_key',
      array(
        'default'           => '',
      )
		);

    register_setting(
			'atai-settings',
      'atai_lang',
      array(
        'default'           => 'en',
      )
    );

    register_setting(
			'atai-settings',
      'atai_update_title',
      array(
        'sanitize_callback' => array( $this, 'sanitize_yes_no_checkbox' ),
        'default'           => 'no',
      )
    );

    register_setting(
			'atai-settings',
      'atai_update_caption',
      array(
        'sanitize_callback' => array( $this, 'sanitize_yes_no_checkbox' ),
        'default'           => 'no',
      )
    );

    register_setting(
			'atai-settings',
      'atai_update_description',
      array(
        'sanitize_callback' => array( $this, 'sanitize_yes_no_checkbox' ),
        'default'           => 'no',
      )
    );

    register_setting(
			'atai-settings',
      'atai_enabled',
      array(
        'sanitize_callback' => array( $this, 'sanitize_yes_no_checkbox' ),
        'default'           => 'yes',
      )
    );

    register_setting(
			'atai-settings',
      'atai_keywords',
      array(
        'sanitize_callback' => array( $this, 'sanitize_yes_no_checkbox' ),
        'default'           => 'yes',
      )
    );

    register_setting(
			'atai-settings',
      'atai_keywords_title',
      array(
        'sanitize_callback' => array( $this, 'sanitize_yes_no_checkbox' ),
        'default'           => 'no',
      )
    );

    register_setting(
			'atai-settings',
      'atai_ecomm',
      array(
        'sanitize_callback' => array( $this, 'sanitize_yes_no_checkbox' ),
        'default'           => 'yes',
      )
    );

    register_setting(
			'atai-settings',
      'atai_public',
      array(
        'sanitize_callback' => array( $this, 'sanitize_yes_no_checkbox' ),
        'default'           => 'no',
      )
    );

    register_setting(
			'atai-settings',
      'atai_alt_prefix',
      array(
        'default'           => '',
      )
    );

    register_setting(
			'atai-settings',
      'atai_alt_suffix',
      array(
        'default'           => '',
      )
    );

    register_setting(
			'atai-settings',
      'atai_gpt_prompt',
      array(
        'sanitize_callback' => array( $this, 'sanitize_gpt_prompt' ),
        'default'           => '',
      )
    );

    register_setting(
			'atai-settings',
      'atai_type_extensions',
      array(
        'sanitize_callback' => array( $this, 'sanitize_file_extension_list' ),
        'default'           => '',
      )
    );

    register_setting(
			'atai-settings',
      'atai_no_credit_warning',
      array(
        'sanitize_callback' => array( $this, 'sanitize_yes_no_checkbox' ),
        'default'           => 'no',
      )
    );
  }

  /**
   * Sanitizes a checkbox input to ensure it is either 'yes' or 'no'.
   *
   * This function is designed to handle checkbox inputs where the value
   * represents a binary choice like 'yes' or 'no'. If the input is 'yes',
   * it returns 'yes', otherwise it defaults to 'no'.
   *
   * @since 1.0.41
   * @access public
   *
   * @param string $input The checkbox input value.
   *
   * @return string Returns 'yes' if input is 'yes', otherwise returns 'no'.
   */
  public function sanitize_yes_no_checkbox( $input ) {
    return $input === 'yes' ? 'yes' : 'no';
  }

  /**
   * Sanitizes a file extension list to ensure it does not contain leading dots.
   *
   * @since 1.0.43
   * @access public
   *
   * @param string $input The file extension list string. Example: "jpg, .webp"
   *
   * @return string Returns the string with dots removed.
   */
  public function sanitize_file_extension_list( $input ) {
    return str_replace( '.', '', strtolower( $input ) );
  }

  /**
   * Sanitizes a custom ChatGPT prompt to ensure it contains the {{AltText} macro and isn't too long.
   *
   * @since 1.2.4
   * @access public
   *
   * @param string $input The text of the GPT prompt.
   *
   * @return string Returns the prompt string if valid, otherwise an empty string.
   */
  public function sanitize_gpt_prompt( $input ) {
    if ( strlen($input) > 512 || strpos($input, "{{AltText}}") === false ) {
      return '';
    }
    else {
      return $input;
    }
  }

  /**
   * Add or delete API key.
   *
   * @since 1.0.0
   * @access public
   */
  public function save_api_key( $api_key, $old_api_key ) {
    $delete = is_null( $api_key );

    if ( $delete ) {
      delete_option( 'atai_api_key' );
    }

    if ( empty( $api_key ) ) {
      return $api_key;
    }

    if ( $api_key === '*********' ) {
      return $old_api_key;
    }

    $api = new ATAI_API( $api_key );

    if ( ! $api->get_account() ) {
      add_settings_error( 'invalid-api-key', '', esc_html__( 'Your API key is not valid.', 'alttext-ai' ) );
      return false;
    }

    // Register webhook
    if ( get_option( 'atai_public' ) === 'yes' ) {
      $api->send_webhook_url();
    }

    // Add custom success message
    $message = __( 'API Key saved. Pro tip: Add alt text to all your existing images with our <a href="%s" class="font-medium text-indigo-600 hover:text-indigo-500">Bulk Generate</a> feature!', 'alttext-ai' );
    $message = sprintf( $message, admin_url( 'admin.php?page=atai-bulk-generate' ) );
    add_settings_error( 'atai_api_key_updated', '', $message, 'updated' );

    return $api_key;
  }

  /**
   * Clear error logs on load
   *
   * @since 1.0.0
   * @access public
   */
  public function clear_error_logs() {
    if ( ! isset( $_GET['atai_action'] ) ) {
      return;
    }

    if ( $_GET['atai_action'] !== 'clear-error-logs' ) {
      return;
    }

    delete_option( 'atai_error_logs' );
    wp_safe_redirect( add_query_arg( 'atai_action', false ) );
  }

  /**
   * Display a notice to the user if they have insufficient credits.
   *
   * If the "atai_insufficient_credits" transient is set, display a notice to the user that
   * they are out of credits and provide a link to upgrade their plan.
   *
   * @since 1.0.20
   */
  public function display_insufficient_credits_notice() {
    // Bail early if notice transient is not set
    if ( ! get_transient( 'atai_insufficient_credits' ) ) {
      return;
    }

    echo '<div class="notice notice--atai notice-error is-dismissible"><p>';

    printf(
      wp_kses(
        __( '[AltText.ai] You have no more credits available. <a href="%s" target="_blank">Manage your account</a> to get more credits.', 'alttext-ai' ),
        array( 'a' => array( 'href' => array(), 'target' => array() ) )
      ),
      esc_url( 'https://alttext.ai/subscriptions?utm_source=wp&utm_medium=dl' )
    );

    echo '</p></div>';
  }

  /**
   * Delete the "atai_insufficient_credits" transient to expire the notice.
   *
   * @since 1.0.20
   */
  public function expire_insufficient_credits_notice() {
    check_ajax_referer( 'atai_insufficient_credits_notice', 'security' );
    delete_transient( 'atai_insufficient_credits' );

    wp_send_json( array(
      'status'    => 'success',
      'message'   => __( 'Notice expired.', 'alttext-ai' ),
    ) );
  }

  /**
   * Display a notice if no API key is added.
   *
   * @since 1.2.1
   */
  public function display_api_key_missing_notice() {
    if ( ! isset( $_GET['api_key_missing'] ) ) {
      return;
    }

    $api_key = ATAI_Utility::get_api_key();

    if ( ! empty( $api_key ) ) {
      return;
    }

    echo '<div class="notice notice--atai notice-warning"><p>';

    printf(
      wp_kses(
        __( '[AltText.ai] Please <strong>add your API key</strong> to generate alt text.', 'alttext-ai' ),
        array( 'strong' => array() )
      ),
      admin_url( 'admin.php?page=atai' )
    );

    echo '</p></div>';
  }

  /**
   * Remove the "api_key_missing" query arg from the URL.
   *
   * @since 1.2.1
   */
  public function remove_api_key_missing_param() {
    if ( ! isset( $_GET['api_key_missing'] ) ) {
      return;
    }

    $api_key = ATAI_Utility::get_api_key();

    if ( empty( $api_key ) ) {
      return;
    }

    $current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $updated_url = remove_query_arg( 'api_key_missing', $current_url );

    if ( $current_url !== $updated_url ) {
      wp_redirect( $updated_url );
      exit;
    }
  }
}
