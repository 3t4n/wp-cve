<?php
/**
 * Import options.
 *
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 */
class Theme_Blvd_Import_Options {

	public $id = '';
	public $args = array();
	private $error = '';

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id A unique ID for this exporter
	 */
	public function __construct( $id, $args = array() ) {

		$this->id = $id;

		$defaults = array(
			'redirect'	=> admin_url() 	// Where to redirect after import is finished
		);
		$this->args = wp_parse_args( $args, $defaults );

		// Add Importer page
		add_action( 'admin_menu', array( $this, 'add_page' ) );

		// Process import form
		add_action( 'admin_init', array( $this, 'import' ) );

		// Check if we're loading after a successful import
		add_action( 'admin_init', array( $this, 'success' ) );
	}

	/**
	 * Add the hidden admin page to WordPress.
	 *
	 * @since 1.0.0
	 */
	public function add_page() {

		global $submenu;

		// Add admin page
		add_submenu_page( null, null, __('Theme Options Import', 'theme-blvd-importer'), themeblvd_admin_module_cap('options'), $this->id.'-import-options', array( $this, 'admin_page' ) );

	}

	/**
	 * Display the hidden admin page.
	 *
	 * @since 1.0.0
	 */
	public function admin_page() {

		$title = __('Import Options', 'theme-blvd-importer');

		if ( get_template() == $this->id ) {
			$theme = wp_get_theme();
			$title = sprintf( __('Import %s Options', 'theme-blvd-importer'), $theme->get('Name') );
		}

		?>
		<h2><?php echo esc_html($title); ?></h2>

		<p><?php esc_html_e('Upload an XML file previously exported from your options page.', 'theme-blvd-importer'); ?></p>

		<p><strong><?php esc_html_e('Warning: This will override any currently saved options.', 'theme-blvd-importer'); ?></strong></p>

		<form enctype="multipart/form-data" id="import-upload-form" method="post" class="wp-upload-form" action="admin.php?page=<?php echo $this->id; ?>-import-options&amp;themeblvd_import=true">
			<p>
				<label for="upload"><?php esc_html_e('Choose a file from your computer:', 'theme-blvd-importer'); ?></label><br />
				<input type="file" id="upload" name="import" size="25" />
				<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'themeblvd_import_'.$this->id ); ?>" />
				<input type="hidden" name="max_file_size" value="33554432" />
			</p>
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button" value="<?php esc_attr_e('Upload file and import', 'theme-blvd-importer'); ?>" disabled="" />
			</p>
		</form>
		<?php
	}

	/**
	 * Process the uploaded file and import the data.
	 *
	 * @since 1.0.0
	 */
	public function import() {

		if ( empty( $_GET['themeblvd_import'] ) ) {
			return;
		}

		// Check security nonce
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'themeblvd_import_'.$this->id ) ) {
			return;
		}

		$import = '';
		$file = '';

		if ( isset( $_FILES['import'] ) ) {

			// Needs to be an XML file
			if ( $_FILES['import']['type'] != 'text/xml' ) {
				$this->error = __('Error. You must upload an XML file.', 'theme-blvd-importer');
				add_action( 'admin_notices', array( $this, 'fail' ) );
				return;
			}

			$file = $_FILES['import']['tmp_name'];
		}

		// Parse the file
		if ( file_exists( $file ) && function_exists( 'simplexml_load_file' ) ) {
			$internal_errors = libxml_use_internal_errors(true);
			$import = simplexml_load_file( $file );
		}

		if ( ! $import ) {
			$this->error = __('Error. The XML file could not be read.', 'theme-blvd-importer');
			add_action( 'admin_notices', array( $this, 'fail' ) );
			return;
		}

		$atts = $import->attributes();

		if ( $atts->themeblvd != 'options' ) {
			$this->error = __('Error. The XML file is not formatted properly for importing Theme Blvd options.', 'theme-blvd-importer');
			add_action( 'admin_notices', array( $this, 'fail' ) );
			return;
		}

		if ( $atts->template != get_template() ) {
			$theme = wp_get_theme( get_template() );
			$this->error = sprintf( __('Error. The XML file was not exported from the current theme, %s.', 'theme-blvd-importer'), $theme->get('Name') );
			add_action( 'admin_notices', array( $this, 'fail' ) );
			return;
		}

		$settings = array();
		$imported = $import->setting;

		if ( $imported ) {
			foreach ( $imported as $setting ) {
				$id = (string)$setting->id;
				$value = (string)$setting->value;
				$settings[$id] = maybe_unserialize(base64_decode($value));
			}
		}

		if ( $settings ) {
			update_option( $this->id, $settings );
			wp_redirect( $this->args['redirect'].'&settings-updated=themeblvd_import_success' );
			exit;
		}

	}

	/**
	 * Get the URL of our hidden admin page.
	 *
	 * @since 1.0.0
	 */
	public function get_url() {
		return esc_url( add_query_arg( array('page' => $this->id.'-import-options'), admin_url('admin.php') ) );
	}

	/**
	 * Success notice.
	 *
	 * @since 1.0.0
	 */
	public function success() {
		if ( ! empty( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'themeblvd_import_success' ) {
			add_settings_error( $this->id, 'export-success', __('Options imported successfully.', 'theme-blvd-importer'), 'themeblvd-updated updated' );
		}
	}

	/**
	 * Fail notice.
	 *
	 * @since 1.0.0
	 */
	public function fail() {
		?>
		<div class="themeblvd-updated error settings-error" style="margin-left: 0;">
			<p><strong><?php echo esc_html($this->error); ?></strong></p>
		</div>
		<?php
	}
}
