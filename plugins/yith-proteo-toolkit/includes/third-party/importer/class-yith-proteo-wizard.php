<?php
/**
 * YITH Proteo Wizard
 *
 * The following code is a derivative work from the
 * Envato WordPress Theme Setup Wizard by David Baker and Merlin WP by Rich Tabor.
 *
 * @package YITH_Proteo_tookit
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * YITH_Proteo_Wizard.
 */
class YITH_Proteo_Wizard {
	/**
	 * Current theme.
	 *
	 * @var object WP_Theme
	 */
	protected $theme;

	/**
	 * Current step.
	 *
	 * @var string
	 */
	protected $step = '';

	/**
	 * Steps.
	 *
	 * @var    array
	 */
	protected $steps = array();

	/**
	 * TGMPA instance.
	 *
	 * @var    object
	 */
	protected $tgmpa;

	/**
	 * Importer.
	 *
	 * @var    array
	 */
	protected $importer;

	/**
	 * WP Hook class.
	 *
	 * @var YITH_Proteo_Wizard_Hooks
	 */
	protected $hooks;

	/**
	 * Holds the verified import files.
	 *
	 * @var array
	 */
	public $import_files;

	/**
	 * The base import file name.
	 *
	 * @var string
	 */
	public $import_file_base_name;

	/**
	 * Helper.
	 *
	 * @var    array
	 */
	protected $helper;

	/**
	 * Updater.
	 *
	 * @var    array
	 */
	protected $updater;

	/**
	 * The text string array.
	 *
	 * @var array $strings
	 */
	protected $strings = null;

	/**
	 * The base path where YITH_Proteo_Wizard is located.
	 *
	 * @var array $strings
	 */
	protected $base_path = null;

	/**
	 * The base url where YITH_Proteo_Wizard is located.
	 *
	 * @var array $strings
	 */
	protected $base_url = null;

	/**
	 * The location where YITH_Proteo_Wizard is located within the theme or plugin.
	 *
	 * @var string $directory
	 */
	protected $directory = null;

	/**
	 * Top level admin page.
	 *
	 * @var string $wizard_url
	 */
	protected $wizard_url = null;

	/**
	 * The wp-admin parent page slug for the admin menu item.
	 *
	 * @var string $parent_slug
	 */
	protected $parent_slug = null;

	/**
	 * The capability required for this menu to be displayed to the user.
	 *
	 * @var string $capability
	 */
	protected $capability = null;

	/**
	 * The URL for the "Learn more about child themes" link.
	 *
	 * @var string $child_action_btn_url
	 */
	protected $child_action_btn_url = null;

	/**
	 * Turn on dev mode if you're developing.
	 *
	 * @var string $dev_mode
	 */
	protected $dev_mode = false;

	/**
	 * Ignore.
	 *
	 * @var string $ignore
	 */
	public $ignore = null;

	/**
	 * The object with logging functionality.
	 *
	 * @var Logger $logger
	 */
	public $logger;

	/**
	 * Class Constructor.
	 *
	 * @param array $config Package-specific configuration args.
	 * @param array $strings Text for the different elements.
	 */
	public function __construct( $config = array(), $strings = array() ) {

		$config = wp_parse_args(
			$config,
			array(
				'base_path'            => get_parent_theme_file_path(),
				'base_url'             => get_parent_theme_file_uri(),
				'directory'            => 'importer',
				'wizard_url'           => 'setup-wizard',
				'parent_slug'          => 'themes.php',
				'capability'           => 'manage_options',
				'child_action_btn_url' => '',
				'dev_mode'             => '',
				'ready_big_button_url' => home_url( '/' ),
			)
		);

		// Set config arguments.
		$this->base_path            = $config['base_path'];
		$this->base_url             = $config['base_url'];
		$this->directory            = $config['directory'];
		$this->wizard_url           = $config['wizard_url'];
		$this->parent_slug          = $config['parent_slug'];
		$this->capability           = $config['capability'];
		$this->child_action_btn_url = $config['child_action_btn_url'];
		$this->dev_mode             = $config['dev_mode'];
		$this->ready_big_button_url = $config['ready_big_button_url'];

		// Strings passed in from the config file.
		$this->strings = $strings;

		// Retrieve a WP_Theme object.
		$this->theme = wp_get_theme();
		$this->slug  = strtolower( preg_replace( '#[^a-zA-Z]#', '', $this->theme->template ) );

		// Set the ignore option.
		$this->ignore = $this->slug . '_ignore';

		// Is Dev Mode turned on?
		if ( true !== $this->dev_mode ) {

			// Has this theme been setup yet?
			$already_setup = get_option( 'yith_proteo_wizard_' . $this->slug . '_completed' );

			// Return if YITH_Proteo_Wizard has already completed it's setup.
			if ( $already_setup ) {
				return;
			}
		}

		// Get the logger object, so it can be used in the whole class.
		require_once trailingslashit( $this->base_path ) . $this->directory . '/includes/class-yith-proteo-wizard-logger.php';
		$this->logger = YITH_Proteo_Wizard_Logger::get_instance();

		// Get TGMPA.
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			$this->tgmpa = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();
		}

		add_action( 'admin_init', array( $this, 'required_classes' ) );
		add_action( 'admin_init', array( $this, 'redirect' ), 30 );
		add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
		add_action( 'admin_init', array( $this, 'steps' ), 30, 0 );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_page' ), 30, 0 );
		add_action( 'admin_init', array( $this, 'ignore' ), 5 );
		add_action( 'admin_footer', array( $this, 'svg_sprite' ) );
		add_filter( 'tgmpa_load', array( $this, 'load_tgmpa' ), 10, 1 );
		add_action( 'wp_ajax_wizard_content', array( $this, 'ajax_content' ), 10, 0 );
		add_action( 'wp_ajax_wizard_get_total_content_import_items', array( $this, 'ajax_get_total_content_import_items' ), 10, 0 );
		add_action( 'wp_ajax_wizard_plugins', array( $this, 'ajax_plugins' ), 10, 0 );
		add_action( 'wp_ajax_wizard_child_theme', array( $this, 'generate_child' ), 10, 0 );
		add_action( 'wp_ajax_wizard_update_selected_import_data_info', array( $this, 'update_selected_import_data_info' ), 10, 0 );
		add_action( 'wp_ajax_wizard_import_finished', array( $this, 'import_finished' ), 10, 0 );
		add_filter( 'pt-importer/new_ajax_request_response_data', array( $this, 'pt_importer_new_ajax_request_response_data' ) );
		add_action( 'import_end', array( $this, 'after_content_import_setup' ) );
		add_action( 'import_start', array( $this, 'before_content_import_setup' ) );
		add_action( 'admin_init', array( $this, 'register_import_files' ) );
	}

	/**
	 * Get access to the wp_filesystem global
	 */
	public static function get_filesystem() {
		global $wp_filesystem;
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();
		return $wp_filesystem;
	}

	/**
	 * Require necessary classes.
	 */
	public function required_classes() {
		if ( ! class_exists( '\WP_Importer' ) ) {
			require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
		}

		require_once trailingslashit( $this->base_path ) . $this->directory . '/includes/class-yith-proteo-wizard-downloader.php';

		$this->importer = new AwesomeMotive\WPContentImporter2\Importer( array( 'fetch_attachments' => true ), $this->logger );

		require_once trailingslashit( $this->base_path ) . $this->directory . '/includes/class-yith-proteo-wizard-widget-importer.php';

		if ( ! class_exists( 'WP_Customize_Setting' ) ) {
			require_once ABSPATH . 'wp-includes/class-wp-customize-setting.php';
		}

		require_once trailingslashit( $this->base_path ) . $this->directory . '/includes/class-yith-proteo-wizard-customizer-option.php';
		require_once trailingslashit( $this->base_path ) . $this->directory . '/includes/class-yith-proteo-wizard-customizer-importer.php';
		require_once trailingslashit( $this->base_path ) . $this->directory . '/includes/class-yith-proteo-wizard-hooks.php';

		$this->hooks = new YITH_Proteo_Wizard_Hooks();
	}

	/**
	 * Set redirection transient on theme switch.
	 */
	public function switch_theme() {
		if ( ! is_child_theme() ) {
			set_transient( $this->theme->template . '_wizard_redirect', 1 );
		}
	}

	/**
	 * Redirection transient.
	 */
	public function redirect() {

		if ( ! get_transient( $this->theme->template . '_wizard_redirect' ) ) {
			return;
		}

		delete_transient( $this->theme->template . '_wizard_redirect' );

		wp_safe_redirect( menu_page_url( $this->wizard_url ) );

		exit;
	}

	/**
	 * Give the user the ability to ignore YITH_Proteo_Wizard.
	 */
	public function ignore() {

		// Bail out if not on correct page.
		if ( ! isset( $_GET['_wpnonce'] ) || ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'wizard-ignore-nounce' ) || ! is_admin() || ! isset( $_GET[ $this->ignore ] ) || ! current_user_can( 'manage_options' ) ) ) {
			return;
		}

		update_option( 'yith_proteo_wizard_' . $this->slug . '_completed', 'ignored' );
	}

	/**
	 * Conditionally load TGMPA
	 *
	 * @param string $status User's manage capabilities.
	 */
	public function load_tgmpa( $status ) {
		return is_admin() || current_user_can( 'install_themes' );
	}

	/**
	 * Determine if the user already has theme content installed.
	 * This can happen if swapping from a previous theme or updated the current theme.
	 * We change the UI a bit when updating / swapping to a new theme.
	 *
	 * @access public
	 */
	protected function is_possible_upgrade() {
		return false;
	}

	/**
	 * Add the admin menu item, under Appearance.
	 */
	public function add_admin_menu() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		$this->hook_suffix = add_submenu_page(
			esc_html( $this->parent_slug ),
			esc_html( $strings['admin-menu'] ),
			esc_html( $strings['admin-menu'] ),
			sanitize_key( $this->capability ),
			sanitize_key( $this->wizard_url ),
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Add the admin page.
	 */
	public function admin_page() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Do not proceed, if we're not on the right page.
		if ( empty( $_GET['page'] ) || $this->wizard_url !== $_GET['page'] ) { // phpcs:ignore
			return;
		}

		if ( ob_get_length() ) {
			ob_end_clean();
		}

		$this->step = isset( $_GET['step'] ) ? sanitize_key( wp_unslash( $_GET['step'] ) ) : current( array_keys( $this->steps ) ); // phpcs:ignore

		wp_enqueue_media();

		// Use minified libraries if dev mode is turned on.
		$suffix = ( ( true === $this->dev_mode ) ) ? '' : '.min';

		// Enqueue styles.
		wp_enqueue_style( 'proteo-wizard', trailingslashit( $this->base_url ) . $this->directory . '/assets/css/wizard' . $suffix . '.css', array( 'wp-admin' ), YITH_PROTEO_TOOLKIT_VERSION );

		// Enqueue javascript.
		wp_enqueue_script( 'proteo-wizard', trailingslashit( $this->base_url ) . $this->directory . '/assets/js/wizard' . $suffix . '.js', array( 'jquery-core' ), YITH_PROTEO_TOOLKIT_VERSION, true );

		$texts = array(
			'something_went_wrong' => esc_html__( 'Something went wrong. Please refresh the page and try again!', 'yith-proteo-toolkit' ),
		);

		// Localize the javascript.
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			// Check first if TMGPA is included.
			wp_localize_script(
				'proteo-wizard',
				'wizard_params',
				array(
					'tgm_plugin_nonce' => array(
						'update'  => wp_create_nonce( 'tgmpa-update' ),
						'install' => wp_create_nonce( 'tgmpa-install' ),
					),
					'tgm_bulk_url'     => $this->tgmpa->get_tgmpa_url(),
					'ajaxurl'          => admin_url( 'admin-ajax.php' ),
					'wpnonce'          => wp_create_nonce( 'wizard_nonce' ),
					'texts'            => $texts,
				)
			);
		} else {
			// If TMGPA is not included.
			wp_localize_script(
				'wizard',
				'wizard_params',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'wpnonce' => wp_create_nonce( 'wizard_nonce' ),
					'texts'   => $texts,
				)
			);
		}

		ob_start();

		/**
		 * Start the actual page content.
		 */
		$this->header(); ?>

		<div class="wizard__wrapper">

			<div class="wizard__content wizard__content--<?php echo esc_attr( strtolower( $this->steps[ $this->step ]['name'] ) ); ?>">

				<?php
				// Content Handlers.
				$show_content = true;

				if ( ! empty( $_REQUEST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) { // phpcs:ignore
					$show_content = call_user_func( $this->steps[ $this->step ]['handler'] );
				}

				if ( $show_content ) {
					$this->body();
				}
				?>

			<?php $this->step_output(); ?>

			</div>

			<?php echo sprintf( '<a class="return-to-dashboard" href="%s">%s</>', esc_url( admin_url( '/' ) ), esc_html( $strings['return-to-dashboard'] ) ); ?>

			<?php $ignore_url = wp_nonce_url( admin_url( '?' . $this->ignore . '=true' ), 'wizard-ignore-nounce' ); ?>

			<?php echo sprintf( '<a class="return-to-dashboard ignore" href="%s">%s</a>', esc_url( $ignore_url ), esc_html( $strings['ignore'] ) ); ?>

		</div>

		<?php $this->footer(); ?>

		<?php
		exit;
	}

	/**
	 * Output the header.
	 */
	protected function header() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Get the current step.
		$current_step = strtolower( $this->steps[ $this->step ]['name'] );
		?>

		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width"/>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<?php printf( esc_html( $strings['title%s%s%s%s'] ), '<ti', 'tle>', esc_html( $this->theme->name ), '</title>' ); ?>
			<?php do_action( 'admin_print_styles' ); ?>
			<?php do_action( 'admin_print_scripts' ); ?>
			<?php set_current_screen(); ?>
			<?php do_action( 'admin_head' ); ?>
		</head>
		<body class="wizard__body wizard__body--<?php echo esc_attr( $current_step ); ?>">
		<?php
	}

	/**
	 * Output the content for the current step.
	 */
	protected function body() {
		isset( $this->steps[ $this->step ] ) ? call_user_func( $this->steps[ $this->step ]['view'] ) : false;
	}

	/**
	 * Output the footer.
	 */
	protected function footer() {
		?>
		</body>
		<?php do_action( 'admin_footer' ); ?>
		<?php do_action( 'admin_print_footer_scripts' ); ?>
		</html>
		<?php
	}

	/**
	 * SVG
	 */
	public function svg_sprite() {

		// Define SVG sprite file.
		$svg = trailingslashit( $this->base_path ) . $this->directory . '/assets/images/sprite.svg';

		// If it exists, include it.
		if ( file_exists( $svg ) ) {
			require_once apply_filters( 'wizard_svg_sprite', $svg );
		}
	}

	/**
	 * Return SVG markup.
	 *
	 * @param array $args {
	 *     Parameters needed to display an SVG.
	 *
	 *     @type string $icon  Required SVG icon filename.
	 *     @type string $title Optional SVG title.
	 *     @type string $desc  Optional SVG description.
	 * }
	 * @return string SVG markup.
	 */
	public function svg( $args = array() ) {

		// Make sure $args are an array.
		if ( empty( $args ) ) {
			return __( 'Please define default parameters in the form of an array.', 'yith-proteo-toolkit' );
		}

		// Define an icon.
		if ( false === array_key_exists( 'icon', $args ) ) {
			return __( 'Please define an SVG icon filename.', 'yith-proteo-toolkit' );
		}

		// Set defaults.
		$defaults = array(
			'icon'        => '',
			'title'       => '',
			'desc'        => '',
			'aria_hidden' => true, // Hide from screen readers.
			'fallback'    => false,
		);

		// Parse args.
		$args = wp_parse_args( $args, $defaults );

		// Set aria hidden.
		$aria_hidden = '';

		if ( true === $args['aria_hidden'] ) {
			$aria_hidden = ' aria-hidden="true"';
		}

		// Set ARIA.
		$aria_labelledby = '';

		if ( $args['title'] && $args['desc'] ) {
			$aria_labelledby = ' aria-labelledby="title desc"';
		}

		// Begin SVG markup.
		$svg = '<svg class="icon icon--' . esc_attr( $args['icon'] ) . '"' . $aria_hidden . $aria_labelledby . ' role="img">';

		// If there is a title, display it.
		if ( $args['title'] ) {
			$svg .= '<title>' . esc_html( $args['title'] ) . '</title>';
		}

		// If there is a description, display it.
		if ( $args['desc'] ) {
			$svg .= '<desc>' . esc_html( $args['desc'] ) . '</desc>';
		}

		$svg .= '<use xlink:href="#icon-' . esc_html( $args['icon'] ) . '"></use>';

		// Add some markup to use as a fallback for browsers that do not support SVGs.
		if ( $args['fallback'] ) {
			$svg .= '<span class="svg-fallback icon--' . esc_attr( $args['icon'] ) . '"></span>';
		}

		$svg .= '</svg>';

		return $svg;
	}

	/**
	 * Allowed HTML for sprites.
	 */
	public function svg_allowed_html() {

		$array = array(
			'svg' => array(
				'class'       => array(),
				'aria-hidden' => array(),
				'role'        => array(),
			),
			'use' => array(
				'xlink:href' => array(),
			),
		);

		return apply_filters( 'wizard_svg_allowed_html', $array );
	}

	/**
	 * Loading wizard-spinner.
	 */
	public function loading_spinner() {

		// Define the spinner file.
		$spinner = $this->directory . '/assets/images/spinner';

		// Retrieve the spinner.
		get_template_part( apply_filters( 'wizard_loading_spinner', $spinner ) );
	}

	/**
	 * Allowed HTML for the loading spinner.
	 */
	public function loading_spinner_allowed_html() {

		$array = array(
			'span' => array(
				'class' => array(),
			),
			'cite' => array(
				'class' => array(),
			),
		);

		return apply_filters( 'wizard_loading_spinner_allowed_html', $array );
	}

	/**
	 * Setup steps.
	 */
	public function steps() {

		$this->steps = array(
			'welcome' => array(
				'name'    => esc_html( 'Welcome' ),
				'view'    => array( $this, 'welcome' ),
				'handler' => array( $this, 'welcome_handler' ),
			),
		);

		$this->steps['child'] = array(
			'name' => esc_html( 'Child' ),
			'view' => array( $this, 'child' ),
		);

		$this->steps['skin'] = array(
			'name' => esc_html( 'Skin' ),
			'view' => array( $this, 'skin' ),
		);

		// Show the plugin importer, only if TGMPA is included.
		if ( class_exists( 'TGM_Plugin_Activation' ) ) {
			$this->steps['plugins'] = array(
				'name' => esc_html( 'Plugins' ),
				'view' => array( $this, 'plugins' ),
			);
		}

		// Show the content importer, only if there's demo content added.
		if ( ! empty( $this->import_files ) ) {
			$this->steps['content'] = array(
				'name' => esc_html( 'Content' ),
				'view' => array( $this, 'content' ),
			);
		}

		$this->steps['ready'] = array(
			'name' => esc_html( 'Ready' ),
			'view' => array( $this, 'ready' ),
		);

		$this->steps = apply_filters( $this->theme->template . '_wizard_steps', $this->steps );
	}

	/**
	 * Output the steps
	 */
	protected function step_output() {
		$ouput_steps  = $this->steps;
		$array_keys   = array_keys( $this->steps );
		$current_step = array_search( $this->step, $array_keys, true );

		array_shift( $ouput_steps );
		?>

		<ol class="dots">

			<?php
			foreach ( $ouput_steps as $step_key => $step ) :

				$class_attr = '';
				$show_link  = false;

				if ( $step_key === $this->step ) {
					$class_attr = 'active';
				} elseif ( $current_step > array_search( $step_key, $array_keys, true ) ) {
					$class_attr = 'done';
					$show_link  = true;
				}
				?>

				<li class="<?php echo esc_attr( $class_attr ); ?>">
					<a href="<?php echo esc_url( $this->step_link( $step_key ) ); ?>" title="<?php echo esc_attr( $step['name'] ); ?>"></a>
				</li>

			<?php endforeach; ?>

		</ol>

		<?php
	}

	/**
	 * Get the step URL.
	 *
	 * @param string $step Name of the step, appended to the URL.
	 */
	protected function step_link( $step ) {
		return add_query_arg( 'step', $step );
	}

	/**
	 * Get the next step link.
	 */
	protected function step_next_link() {
		$keys = array_keys( $this->steps );
		$step = array_search( $this->step, $keys, true ) + 1;

		return add_query_arg( 'step', $keys[ $step ] );
	}

	/**
	 * Introduction step
	 */
	protected function welcome() {

		// Has this theme been setup yet? Compare this to the option set when you get to the last panel.
		$already_setup = get_option( 'yith_proteo_wizard_' . $this->slug . '_completed' );

		// Theme Name.
		$theme = ucfirst( $this->theme );

		// Remove "Child" from the current theme name, if it's installed.
		$theme = str_replace( ' Child', '', $theme );

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$header    = ! $already_setup ? $strings['welcome-header%s'] : $strings['welcome-header-success%s'];
		$paragraph = ! $already_setup ? $strings['welcome%s'] : $strings['welcome-success%s'];
		$start     = $strings['btn-start'];
		?>

		<div class="wizard__content--transition">

			<img class="yith-proteo-toolkit-wizard-step-img" src="<?php echo esc_url( YITH_PROTEO_TOOLKIT_URL ); ?>/assets/img/proteo-logo.png">

			<h1><?php echo esc_html( sprintf( $header, $theme ) ); ?></h1>

			<p><?php echo esc_html( sprintf( $paragraph, $theme ) ); ?></p>

		</div>

		<footer class="wizard__content__footer">
			<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="wizard__button wizard__button--next wizard__button--proceed wizard__button--colorchange"><?php echo esc_html( $start ); ?></a>
			<?php wp_nonce_field( 'wizard' ); ?>
		</footer>

		<?php
		$this->logger->debug( __( 'The welcome step has been displayed', 'yith-proteo-toolkit' ) );
	}

	/**
	 * Handles save button from welcome page.
	 * This is to perform tasks when the setup wizard has already been run.
	 */
	protected function welcome_handler() {

		check_admin_referer( 'wizard' );

		return false;
	}

	/**
	 * Child theme generator.
	 */
	protected function child() {

		// Variables.
		$is_child_theme     = is_child_theme();
		$child_theme_option = get_option( 'yith_proteo_wizard_' . $this->slug . '_child' );
		$theme              = $child_theme_option ? wp_get_theme( $child_theme_option )->name : $this->theme . ' Child';
		$action_url         = $this->child_action_btn_url;

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$header    = ! $is_child_theme ? $strings['child-header'] : $strings['child-header-success'];
		$action    = $strings['child-action-link'];
		$skip      = $strings['btn-skip'];
		$next      = $strings['btn-next'];
		$paragraph = ! $is_child_theme ? $strings['child'] : $strings['child-success%s'];
		$install   = $strings['btn-child-install'];
		?>

		<div class="wizard__content--transition">

			<?php yith_proteo_toolkit_wizard_step_icon( 'child' ); ?>

			<svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
				<circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
			</svg>

			<h1><?php echo esc_html( $header ); ?></h1>

			<p id="child-theme-text"><?php echo esc_html( sprintf( $paragraph, $theme ) ); ?></p>

			<a class="wizard__button wizard__button--knockout wizard__button--no-chevron wizard__button--external" href="<?php echo esc_url( $action_url ); ?>" target="_blank"><?php echo esc_html( $action ); ?></a>

		</div>

		<footer class="wizard__content__footer">

			<?php if ( ! $is_child_theme ) : ?>

				<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="wizard__button wizard__button--skip wizard__button--proceed"><?php echo esc_html( $skip ); ?></a>

				<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="wizard__button wizard__button--next button-next" data-callback="install_child">
					<span class="wizard__button--loading__text"><?php echo esc_html( $install ); ?></span>
					<?php echo wp_kses( $this->loading_spinner(), $this->loading_spinner_allowed_html() ); ?>
				</a>

			<?php else : ?>
				<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="wizard__button wizard__button--next wizard__button--proceed wizard__button--colorchange"><?php echo esc_html( $next ); ?></a>
			<?php endif; ?>
			<?php wp_nonce_field( 'wizard' ); ?>
		</footer>
		<?php
		$this->logger->debug( __( 'The child theme installation step has been displayed', 'yith-proteo-toolkit' ) );
	}

	/**
	 * Skin chooser.
	 */
	protected function skin() {
		// Strings passed in from the config file.
		$strings = $this->strings;
		$next    = $strings['btn-next'];
		$header  = $strings['skin-header'];
		?>

		<div class="wizard__content--transition">

			<?php yith_proteo_toolkit_wizard_step_icon( 'skin' ); ?>

			<svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
				<circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
			</svg>

			<h1><?php echo esc_html( $header ); ?></h1>

			<?php if ( 1 < count( $this->import_files ) ) : ?>
				<div class="skin-categories-chooser">
					<ul class="skin-categories">
						<li class="gutenberg active"><a href="#">Gutenberg</a></li>
						<li class="elementor"><a href="#">Elementor</a></li>
					</ul>
				</div>
				<ul id="demo-content-list">
				<?php foreach ( $this->import_files as $import_file ) : ?>
					<li class="demo-content <?php echo esc_attr( $import_file['state'] ); ?>" data-demo="<?php echo esc_attr( $import_file['slug'] ); ?>" data-category="<?php echo esc_attr( $import_file['category'] ); ?>">
						<img src="<?php echo esc_url( $import_file['import_preview_image_url'] ); ?>" width="250">
						<?php echo esc_html( $import_file['import_file_name'] ); ?>
						<a href="<?php echo esc_url( $import_file['preview_url'] ); ?>" target="_blank" rel="nofollow noopener" class="preview-link" title="<?php esc_html_e( 'Preview', 'yith-proteo-toolkit' ); ?>"><span class="dashicons dashicons-external"></span></a>
					</li>
				<?php endforeach; ?>
				</ul>

				<div class="wizard__select-control-wrapper">

					<select class="wizard__select-control js-wizard-demo-import-select">
						<?php foreach ( $this->import_files as $import_file ) : ?>
							<option value="<?php echo esc_attr( $import_file['slug'] ); ?>"><?php echo esc_html( $import_file['import_file_name'] ); ?></option>
						<?php endforeach; ?>
					</select>

					<div class="wizard__select-control-help">
						<span class="hint--top" aria-label="<?php echo esc_attr__( 'Select skin', 'yith-proteo-toolkit' ); ?>">
							<?php echo wp_kses( $this->svg( array( 'icon' => 'downarrow' ) ), $this->svg_allowed_html() ); ?>
						</span>
					</div>
				</div>
			<?php endif; ?>

		</div>

		<footer class="wizard__content__footer">
			<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="wizard__button wizard__button--next wizard__button--proceed wizard__button--colorchange"><?php echo esc_html( $next ); ?></a>
			<?php wp_nonce_field( 'wizard' ); ?>
		</footer>
		<?php
		$this->logger->debug( __( 'The skin chooser step has been displayed', 'yith-proteo-toolkit' ) );
	}

	/**
	 * Theme plugins
	 */
	protected function plugins() {

		// Variables.
		$url    = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'wizard' );
		$method = '';

		tgmpa_load_bulk_installer();

		if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'wizard' ) ) {
			$fields = array_keys( $_POST );
			$creds  = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, $fields );

			if ( false === $creds ) {
				return true;
			}

			if ( ! WP_Filesystem( $creds ) ) {
				request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );
				return true;
			}
		}

		$selected_skin = isset( $_GET['selected_skin'] ) ? sanitize_text_field( wp_unslash( $_GET['selected_skin'] ) ) : null;
		// Are there plugins that need installing/activating?
		$plugins             = $this->get_tgmpa_plugins( $selected_skin );
		$recommended_plugins = array();
		$required_plugins    = array();
		$count               = count( $plugins['all'] );
		$class               = $count ? null : 'no-plugins';

		// Split the plugins into required and recommended.
		foreach ( $plugins['all'] as $slug => $plugin ) {
			if ( ! empty( $plugin['required'] ) ) {
				$required_plugins[ $slug ] = $plugin;
			} else {
				$recommended_plugins[ $slug ] = $plugin;
			}
		}

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$header    = $count ? $strings['plugins-header'] : $strings['plugins-header-success'];
		$paragraph = $count ? $strings['plugins'] : $strings['plugins-success%s'];
		$action    = $strings['plugins-action-link'];
		$skip      = $strings['btn-skip'];
		$next      = $strings['btn-next'];
		$install   = $strings['btn-plugins-install'];
		?>

		<div class="wizard__content--transition">

			<?php yith_proteo_toolkit_wizard_step_icon( 'plugins' ); ?>

			<svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
				<circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
			</svg>

			<h1><?php echo esc_html( $header ); ?></h1>

			<p><?php echo esc_html( $paragraph ); ?></p>

			<?php if ( $count ) { ?>
				<a id="wizard__drawer-trigger" class="wizard__button wizard__button--knockout"><span><?php echo esc_html( $action ); ?></span><span class="chevron"></span></a>
			<?php } ?>

		</div>

		<form action="" method="post">

			<?php if ( $count ) : ?>

				<ul class="wizard__drawer wizard__drawer--install-plugins">

				<?php if ( ! empty( $required_plugins ) ) : ?>
					<?php foreach ( $required_plugins as $slug => $plugin ) : ?>
						<li data-slug="<?php echo esc_attr( $slug ); ?>">
							<input type="checkbox" name="default_plugins[<?php echo esc_attr( $slug ); ?>]" class="checkbox" id="default_plugins_<?php echo esc_attr( $slug ); ?>" value="1" checked>

							<label for="default_plugins_<?php echo esc_attr( $slug ); ?>">
								<i></i>

								<span><?php echo esc_html( $plugin['name'] ); ?></span>

								<span class="badge">
									<span class="hint--top" aria-label="<?php esc_html_e( 'Required', 'yith-proteo-toolkit' ); ?>">
										<?php esc_html_e( 'req', 'yith-proteo-toolkit' ); ?>
									</span>
								</span>
								<em></em>
							</label>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>

				<?php if ( ! empty( $recommended_plugins ) ) : ?>
					<?php foreach ( $recommended_plugins as $slug => $plugin ) : ?>
						<li data-slug="<?php echo esc_attr( $slug ); ?>">
							<input type="checkbox" name="default_plugins[<?php echo esc_attr( $slug ); ?>]" class="checkbox" id="default_plugins_<?php echo esc_attr( $slug ); ?>" value="1" checked>

							<label for="default_plugins_<?php echo esc_attr( $slug ); ?>">
								<i></i><span><?php echo esc_html( $plugin['name'] ); ?></span>
								<em></em>
							</label>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>

				</ul>

			<?php endif; ?>

			<footer class="wizard__content__footer <?php echo esc_attr( $class ); ?>">
				<?php if ( $count ) : ?>
					<a id="close" href="<?php echo esc_url( $this->step_next_link() ); ?>" class="wizard__button wizard__button--skip wizard__button--closer wizard__button--proceed"><?php echo esc_html( $skip ); ?></a>
					<a id="skip" href="<?php echo esc_url( $this->step_next_link() ); ?>" class="wizard__button wizard__button--skip wizard__button--proceed"><?php echo esc_html( $skip ); ?></a>
					<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="wizard__button wizard__button--next button-next" data-callback="install_plugins">
						<span class="wizard__button--loading__text"><?php echo esc_html( $install ); ?></span>
						<?php echo wp_kses( $this->loading_spinner(), $this->loading_spinner_allowed_html() ); ?>
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="wizard__button wizard__button--next wizard__button--proceed wizard__button--colorchange"><?php echo esc_html( $next ); ?></a>
				<?php endif; ?>
				<?php wp_nonce_field( 'wizard' ); ?>
			</footer>
		</form>

		<?php
		$this->logger->debug( __( 'The plugin installation step has been displayed', 'yith-proteo-toolkit' ) );
	}

	/**
	 * Page setup
	 */
	protected function content() {
		$import_info = $this->get_import_data_info();

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$header    = $strings['import-header'];
		$paragraph = $strings['import'];
		$action    = $strings['import-action-link'];
		$skip      = $strings['btn-skip'];
		$next      = $strings['btn-next'];
		$import    = $strings['btn-import'];

		$multi_import = ( 1 < count( $this->import_files ) ) ? 'is-multi-import' : null;
		?>

		<div class="wizard__content--transition">

		<?php yith_proteo_toolkit_wizard_step_icon( 'content' ); ?>

			<svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
				<circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
			</svg>

			<h1><?php echo esc_html( $header ); ?></h1>

			<p><?php echo esc_html( $paragraph ); ?></p>

			<?php if ( 1 < count( $this->import_files ) ) : ?>
				<ul id="demo-content-list" style="display: none;">
				<?php foreach ( $this->import_files as $import_file ) : ?>
					<li class="demo-content <?php echo esc_attr( $import_file['state'] ); ?>" data-demo="<?php echo esc_attr( $import_file['slug'] ); ?>">
						<img src="<?php echo esc_url( $import_file['import_preview_image_url'] ); ?>" width="250">
						<?php echo esc_html( $import_file['import_file_name'] ); ?>
						<a href="<?php echo esc_url( $import_file['preview_url'] ); ?>" target="_blank" rel="nofollow noopener" class="preview-link" title="<?php esc_html_e( 'Preview', 'yith-proteo-toolkit' ); ?>"><span class="dashicons dashicons-external"></span></a>
					</li>
				<?php endforeach; ?>
				</ul>

				<div class="wizard__select-control-wrapper">

					<select class="wizard__select-control js-wizard-demo-import-select">
						<?php foreach ( $this->import_files as $import_file ) : ?>
							<option value="<?php echo esc_attr( $import_file['slug'] ); ?>"><?php echo esc_html( $import_file['import_file_name'] ); ?></option>
						<?php endforeach; ?>
					</select>

					<div class="wizard__select-control-help">
						<span class="hint--top" aria-label="<?php echo esc_attr__( 'Select Demo', 'yith-proteo-toolkit' ); ?>">
							<?php echo wp_kses( $this->svg( array( 'icon' => 'downarrow' ) ), $this->svg_allowed_html() ); ?>
						</span>
					</div>
				</div>
			<?php endif; ?>

			<a id="wizard__drawer-trigger" class="wizard__button wizard__button--knockout"><span><?php echo esc_html( $action ); ?></span><span class="chevron"></span></a>

		</div>

		<form action="" method="post" class="<?php echo esc_attr( $multi_import ); ?>">
			<ul class="wizard__drawer wizard__drawer--import-content js-wizard-drawer-import-content">
				<?php echo $this->get_import_steps_html( $import_info ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</ul>

			<footer class="wizard__content__footer">

				<a id="close" href="<?php echo esc_url( $this->step_next_link() ); ?>" class="wizard__button wizard__button--skip wizard__button--closer wizard__button--proceed"><?php echo esc_html( $skip ); ?></a>

				<a id="skip" href="<?php echo esc_url( $this->step_next_link() ); ?>" class="wizard__button wizard__button--skip wizard__button--proceed"><?php echo esc_html( $skip ); ?></a>

				<a href="<?php echo esc_url( $this->step_next_link() ); ?>" class="wizard__button wizard__button--next button-next" data-callback="install_content">
					<span class="wizard__button--loading__text"><?php echo esc_html( $import ); ?></span>

					<div class="wizard__progress-bar">
						<span class="js-wizard-progress-bar"></span>
					</div>

					<span class="js-wizard-progress-bar-percentage">0%</span>
				</a>

				<?php wp_nonce_field( 'wizard' ); ?>
			</footer>
		</form>

		<?php
		$this->logger->debug( __( 'The content import step has been displayed', 'yith-proteo-toolkit' ) );
	}


	/**
	 * Final step
	 */
	protected function ready() {

		// Author name.
		$author = $this->theme->author;

		// Theme Name.
		$theme = ucfirst( $this->theme );

		// Remove "Child" from the current theme name, if it's installed.
		$theme = str_replace( ' Child', '', $theme );

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$header    = $strings['ready-header'];
		$paragraph = $strings['ready%s'];
		$action    = $strings['ready-action-link'];
		$skip      = $strings['btn-skip'];
		$next      = $strings['btn-next'];
		$big_btn   = $strings['ready-big-button'];

		// Links.
		$links = array();

		for ( $i = 1; $i < 4; $i++ ) {
			if ( ! empty( $strings[ "ready-link-$i" ] ) ) {
				$links[] = $strings[ "ready-link-$i" ];
			}
		}

		$links_class = empty( $links ) ? 'wizard__content__footer--nolinks' : null;

		$allowed_html_array = array(
			'a' => array(
				'href'   => array(),
				'title'  => array(),
				'target' => array(),
			),
		);

		update_option( 'yith_proteo_wizard_' . $this->slug . '_completed', time() );
		?>

		<div class="wizard__content--transition">

			<?php yith_proteo_toolkit_wizard_step_icon( 'done' ); ?>

			<h1><?php echo esc_html( sprintf( $header, $theme ) ); ?></h1>

			<p><?php echo wp_kses( sprintf( $paragraph, $author ), $allowed_html_array ); ?></p>

		</div>

		<footer class="wizard__content__footer wizard__content__footer--fullwidth <?php echo esc_attr( $links_class ); ?>">

			<a href="<?php echo esc_url( $this->ready_big_button_url ); ?>" class="wizard__button wizard__button--blue wizard__button--fullwidth wizard__button--popin"><?php echo esc_html( $big_btn ); ?></a>

			<?php if ( ! empty( $links ) ) : ?>
				<a id="wizard__drawer-trigger" class="wizard__button wizard__button--knockout"><span><?php echo esc_html( $action ); ?></span><span class="chevron"></span></a>

				<ul class="wizard__drawer wizard__drawer--extras">

					<?php foreach ( $links as $link ) : ?>
						<li><?php echo wp_kses( $link, $allowed_html_array ); ?></li>
					<?php endforeach; ?>

				</ul>
			<?php endif; ?>

		</footer>

		<?php
		$this->logger->debug( __( 'The final step has been displayed', 'yith-proteo-toolkit' ) );
	}

	/**
	 * Get registered TGMPA plugins
	 *
	 * @param string|null $skin The selected skin to import.
	 *
	 * @return    array
	 */
	protected function get_tgmpa_plugins( $skin = null ) {
		$plugins = array(
			'all'      => array(), // Meaning: all plugins which still have open actions.
			'install'  => array(),
			'update'   => array(),
			'activate' => array(),
		);

		foreach ( $this->tgmpa->plugins as $slug => $plugin ) {
			$plugin_skins = isset( $plugin['skin'] ) ? (array) $plugin['skin'] : array();

			if ( $skin && $plugin_skins && ! in_array( $skin, $plugin_skins, true ) ) {
				continue;
			}

			if ( $this->tgmpa->is_plugin_active( $slug ) && false === $this->tgmpa->does_plugin_have_update( $slug ) ) {
				continue;
			} else {
				$plugins['all'][ $slug ] = $plugin;
				if ( ! $this->tgmpa->is_plugin_installed( $slug ) ) {
					$plugins['install'][ $slug ] = $plugin;
				} else {
					if ( false !== $this->tgmpa->does_plugin_have_update( $slug ) ) {
						$plugins['update'][ $slug ] = $plugin;
					}
					if ( $this->tgmpa->can_plugin_activate( $slug ) ) {
						$plugins['activate'][ $slug ] = $plugin;
					}
				}
			}
		}

		return $plugins;
	}

	/**
	 * Generate the child theme via AJAX.
	 */
	public function generate_child() {

		// Strings passed in from the config file.
		$strings = $this->strings;

		// Text strings.
		$success = $strings['child-json-success%s'];
		$already = $strings['child-json-already%s'];

		$name = $this->theme . ' Child';
		$slug = sanitize_title( $name );

		$path = get_theme_root() . '/' . $slug;

		if ( ! file_exists( $path ) ) {

			self::get_filesystem()->mkdir( $path );
			self::get_filesystem()->put_contents( $path . '/style.css', $this->generate_child_style_css( $this->theme->template, $this->theme->name, $this->theme->author, YITH_PROTEO_TOOLKIT_VERSION ) );
			self::get_filesystem()->put_contents( $path . '/functions.php', $this->generate_child_functions_php( $this->theme->template ) );

			$this->generate_child_screenshot( $path );

			$allowed_themes          = get_option( 'allowedthemes' );
			$allowed_themes[ $slug ] = true;
			update_option( 'allowedthemes', $allowed_themes );

		} else {

			if ( $this->theme->template !== $slug ) :
				update_option( 'yith_proteo_wizard_' . $this->slug . '_child', $name );
				switch_theme( $slug );
			endif;

			$this->logger->debug( __( 'The existing child theme was activated', 'yith-proteo-toolkit' ) );

			wp_send_json(
				array(
					'done'    => 1,
					'message' => sprintf(
						esc_html( $success ),
						$slug
					),
				)
			);
		}

		if ( $this->theme->template !== $slug ) :
			update_option( 'yith_proteo_wizard_' . $this->slug . '_child', $name );
			switch_theme( $slug );
		endif;

		$this->logger->debug( __( 'The newly generated child theme was activated', 'yith-proteo-toolkit' ) );

		wp_send_json(
			array(
				'done'    => 1,
				'message' => sprintf(
					esc_html( $already ),
					$name
				),
			)
		);
	}

	/**
	 * Content template for the child theme functions.php file.
	 *
	 * @link https://gist.github.com/richtabor/688327dd103b1aa826ebae47e99a0fbe
	 *
	 * @param string $slug Parent theme slug.
	 */
	public function generate_child_functions_php( $slug ) {

		$slug_no_hyphens = strtolower( preg_replace( '#[^a-zA-Z]#', '', $slug ) );

		$output = "
			<?php
			/**
			 * Theme functions and definitions.
			 * This child theme was generated by YITH Proteo.
			 *
			 * @link https://developer.wordpress.org/themes/basics/theme-functions/
			 */

			/*
			 * If your child theme has more than one .css file (eg. ie.css, style.css, main.css) then
			 * you will have to make sure to maintain all of the parent theme dependencies.
			 *
			 * Make sure you're using the correct handle for loading the parent theme's styles.
			 * Failure to use the proper tag will result in a CSS file needlessly being loaded twice.
			 * This will usually not affect the site appearance, but it's inefficient and extends your page's loading time.
			 *
			 * @link https://codex.wordpress.org/Child_Themes
			 */
			function {$slug_no_hyphens}_child_enqueue_styles() {
			    wp_enqueue_style( '{$slug}-style', get_template_directory_uri() . '/style.css', array('select2'), YITH_PROTEO_VERSION );
			    wp_enqueue_style( '{$slug}-child-style',
			        get_stylesheet_directory_uri() . '/style.css',
			        array( '{$slug}-style' ),
			        wp_get_theme()->get('Version')
			    );
			}

			add_action(  'wp_enqueue_scripts', '{$slug_no_hyphens}_child_enqueue_styles' );\n
		";

		// Let's remove the tabs so that it displays nicely.
		$output = trim( preg_replace( '/\t+/', '', $output ) );

		$this->logger->debug( __( 'The child theme functions.php content was generated', 'yith-proteo-toolkit' ) );

		// Filterable return.
		return apply_filters( 'wizard_generate_child_functions_php', $output, $slug );
	}

	/**
	 * Content template for the child theme functions.php file.
	 *
	 * @link https://gist.github.com/richtabor/7d88d279706fc3093911e958fd1fd791
	 *
	 * @param string $slug    Parent theme slug.
	 * @param string $parent  Parent theme name.
	 * @param string $author  Parent theme author.
	 * @param string $version Parent theme version.
	 */
	public function generate_child_style_css( $slug, $parent, $author, $version ) {

		$output = "
			/**
			* Theme Name: {$parent} Child
			* Description: This is a child theme of {$parent}, generated by YITH_Proteo_Wizard.
			* Author: {$author}
			* Template: {$slug}
			* Version: {$version}
			*/\n
		";

		// Let's remove the tabs so that it displays nicely.
		$output = trim( preg_replace( '/\t+/', '', $output ) );

		$this->logger->debug( __( 'The child theme style.css content was generated', 'yith-proteo-toolkit' ) );

		return apply_filters( 'wizard_generate_child_style_css', $output, $slug, $parent, $version );
	}

	/**
	 * Generate child theme screenshot file.
	 *
	 * @param string $path    Child theme path.
	 */
	public function generate_child_screenshot( $path ) {

		$screenshot = apply_filters( 'wizard_generate_child_screenshot', '' );

		if ( ! empty( $screenshot ) ) {
			// Get custom screenshot file extension.
			if ( '.png' === substr( $screenshot, -4 ) ) {
				$screenshot_ext = 'png';
			} else {
				$screenshot_ext = 'jpg';
			}
		} else {
			if ( file_exists( trailingslashit( $this->base_path ) . $this->directory . '/assets/images/screenshot.png' ) ) {
				$screenshot     = trailingslashit( $this->base_path ) . $this->directory . '/assets/images/screenshot.png';
				$screenshot_ext = 'png';
			} elseif ( file_exists( trailingslashit( $this->base_path ) . $this->directory . '/assets/images/screenshot.jpg' ) ) {
				$screenshot     = trailingslashit( $this->base_path ) . $this->directory . '/assets/images/screenshot.jpg';
				$screenshot_ext = 'jpg';
			}
		}

		if ( ! empty( $screenshot ) && file_exists( $screenshot ) ) {
			$copied = copy( $screenshot, $path . '/screenshot.' . $screenshot_ext );

			$this->logger->debug( __( 'The child theme screenshot was copied to the child theme, with the following result', 'yith-proteo-toolkit' ), array( 'copied' => $copied ) );
		} else {
			$this->logger->debug( __( 'The child theme screenshot was not generated, because of these results', 'yith-proteo-toolkit' ), array( 'screenshot' => $screenshot ) );
		}
	}

	/**
	 * Do plugins' AJAX
	 *
	 * @internal    Used as a callback.
	 */
	public function ajax_plugins() {

		if ( ! check_ajax_referer( 'wizard_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
			exit( 0 );
		}

		$json      = array();
		$tgmpa_url = $this->tgmpa->get_tgmpa_url();
		$plugins   = $this->get_tgmpa_plugins();

		foreach ( $plugins['activate'] as $slug => $plugin ) {
			if ( $_POST['slug'] === $slug ) {
				$json = array(
					'url'           => $tgmpa_url,
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa->menu,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-activate',
					'action2'       => - 1,
					'message'       => esc_html( 'Activating' ),
				);
				break;
			}
		}

		foreach ( $plugins['update'] as $slug => $plugin ) {
			if ( $_POST['slug'] === $slug ) {
				$json = array(
					'url'           => $tgmpa_url,
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa->menu,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-update',
					'action2'       => - 1,
					'message'       => esc_html( 'Updating' ),
				);
				break;
			}
		}

		foreach ( $plugins['install'] as $slug => $plugin ) {
			if ( $_POST['slug'] === $slug ) {
				$json = array(
					'url'           => $tgmpa_url,
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa->menu,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-install',
					'action2'       => - 1,
					'message'       => esc_html( 'Installing' ),
				);
				break;
			}
		}

		if ( $json ) {
			$this->logger->debug(
				__( 'A plugin with the following data will be processed', 'yith-proteo-toolkit' ),
				array(
					'plugin_slug' => sanitize_text_field( wp_unslash( $_POST['slug'] ) ),
					'message'     => $json['message'],
				)
			);

			$json['hash']    = md5( wp_json_encode( $json ) );
			$json['message'] = esc_html( 'Installing' );
			wp_send_json( $json );
		} else {
			$this->logger->debug(
				__( 'A plugin with the following data was processed', 'yith-proteo-toolkit' ),
				array(
					'plugin_slug' => sanitize_text_field( wp_unslash( $_POST['slug'] ) ),
				)
			);

			wp_send_json(
				array(
					'done'    => 1,
					'message' => esc_html( 'Success' ),
				)
			);
		}

		exit;
	}

	/**
	 * Do content's AJAX
	 *
	 * @internal    Used as a callback.
	 */
	public function ajax_content() {
		static $content = null;

		$selected_import = isset( $_POST['selected_index'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_index'] ) ) : 0;

		if ( null === $content ) {
			$content = $this->get_import_data( $selected_import );
		}

		if ( ! check_ajax_referer( 'wizard_nonce', 'wpnonce' ) || empty( $_POST['content'] ) && isset( $content[ $_POST['content'] ] ) ) {
			$this->logger->error( __( 'The content importer AJAX call failed to start, because of incorrect data', 'yith-proteo-toolkit' ) );

			wp_send_json_error(
				array(
					'error'   => 1,
					'message' => esc_html__( 'Invalid content!', 'yith-proteo-toolkit' ),
				)
			);
		}

		$json         = false;
		$this_content = $content[ sanitize_text_field( wp_unslash( $_POST['content'] ) ) ];

		if ( isset( $_POST['proceed'] ) ) {
			if ( is_callable( $this_content['install_callback'] ) ) {
				$this->logger->info(
					__( 'The content import AJAX call will be executed with this import data', 'yith-proteo-toolkit' ),
					array(
						'title' => $this_content['title'],
						'data'  => $this_content['data'],
					)
				);

				$logs = call_user_func( $this_content['install_callback'], $this_content['data'] );
				if ( $logs ) {
					$json = array(
						'done'    => 1,
						'message' => $this_content['success'],
						'debug'   => '',
						'logs'    => $logs,
						'errors'  => '',
					);

					// The content import ended, so we should mark that all posts were imported.
					if ( 'content' === $_POST['content'] ) {
						$json['num_of_imported_posts'] = 'all';
					}
				}
			}
		} else {
			$json = array(
				'url'            => admin_url( 'admin-ajax.php' ),
				'action'         => 'wizard_content',
				'proceed'        => 'true',
				'content'        => sanitize_text_field( wp_unslash( $_POST['content'] ) ),
				'_wpnonce'       => wp_create_nonce( 'wizard_nonce' ),
				'selected_index' => $selected_import,
				'message'        => $this_content['installing'],
				'logs'           => '',
				'errors'         => '',
			);
		}

		if ( $json ) {
			$json['hash'] = md5( wp_json_encode( $json ) );
			wp_send_json( $json );
		} else {
			$this->logger->error(
				__( 'The content import AJAX call failed with this passed data', 'yith-proteo-toolkit' ),
				array(
					'selected_content_index' => $selected_import,
					'importing_content'      => sanitize_text_field( wp_unslash( $_POST['content'] ) ),
					'importing_data'         => $this_content['data'],
				)
			);

			wp_send_json(
				array(
					'error'   => 1,
					'message' => esc_html( 'Error' ),
					'logs'    => '',
					'errors'  => '',
				)
			);
		}
	}


	/**
	 * AJAX call to retrieve total items (posts, pages, CPT, attachments) for the content import.
	 */
	public function ajax_get_total_content_import_items() {
		if ( ! check_ajax_referer( 'wizard_nonce', 'wpnonce' ) && empty( $_POST['selected_index'] ) ) {
			$this->logger->error( __( 'The content importer AJAX call for retrieving total content import items failed to start, because of incorrect data.', 'yith-proteo-toolkit' ) );

			wp_send_json_error(
				array(
					'error'   => 1,
					'message' => esc_html__( 'Invalid data!', 'yith-proteo-toolkit' ),
				)
			);
		}

		$selected_import = sanitize_text_field( wp_unslash( $_POST['selected_index'] ) );
		$import_files    = $this->get_import_files_paths( $selected_import );

		wp_send_json_success( $this->importer->get_number_of_posts_to_import( $import_files['content'] ) );
	}


	/**
	 * Get import data from the selected import.
	 * Which data does the selected import have for the import.
	 *
	 * @param int $selected_import_index The index of the predefined demo import.
	 *
	 * @return bool|array
	 */
	public function get_import_data_info( $selected_import_index = false ) {

		if ( ! $selected_import_index ) {
			$selected_import_index = array_keys( $this->import_files )[0];
		}

		$import_data = array(
			'content'      => false,
			'widgets'      => false,
			'options'      => false,
			'after_import' => false,
		);

		if ( empty( $this->import_files[ $selected_import_index ] ) ) {
			return false;
		}

		if (
			! empty( $this->import_files[ $selected_import_index ]['import_file_url'] ) ||
			! empty( $this->import_files[ $selected_import_index ]['local_import_file'] )
		) {
			$import_data['content'] = true;
		}

		if (
			! empty( $this->import_files[ $selected_import_index ]['import_widget_file_url'] ) ||
			! empty( $this->import_files[ $selected_import_index ]['local_import_widget_file'] )
		) {
			$import_data['widgets'] = true;
		}

		if (
			! empty( $this->import_files[ $selected_import_index ]['import_customizer_file_url'] ) ||
			! empty( $this->import_files[ $selected_import_index ]['local_import_customizer_file'] )
		) {
			$import_data['options'] = true;
		}

		if ( false !== has_action( 'wizard_after_all_import' ) ) {
			$import_data['after_import'] = true;
		}

		return $import_data;
	}


	/**
	 * Get the import files/data.
	 *
	 * @param int $selected_import_index The index of the predefined demo import.
	 *
	 * @return    array
	 */
	protected function get_import_data( $selected_import_index = 0 ) {
		$content = array();

		$import_files = $this->get_import_files_paths( $selected_import_index );

		if ( ! empty( $import_files['content'] ) ) {
			$content['content'] = array(
				'title'            => esc_html__( 'Content', 'yith-proteo-toolkit' ),
				'description'      => esc_html__( 'Demo content data.', 'yith-proteo-toolkit' ),
				'pending'          => esc_html( 'Pending' ),
				'installing'       => esc_html( 'Installing' ),
				'success'          => esc_html( 'Success' ),
				'checked'          => $this->is_possible_upgrade() ? 0 : 1,
				'install_callback' => array( $this->importer, 'import' ),
				'data'             => $import_files['content'],
			);
		}

		if ( ! empty( $import_files['widgets'] ) ) {
			$content['widgets'] = array(
				'title'            => esc_html__( 'Widgets', 'yith-proteo-toolkit' ),
				'description'      => esc_html__( 'Sample widgets data.', 'yith-proteo-toolkit' ),
				'pending'          => esc_html( 'Pending' ),
				'installing'       => esc_html( 'Installing' ),
				'success'          => esc_html( 'Success' ),
				'install_callback' => array( 'YITH_Proteo_Wizard_Widget_Importer', 'import' ),
				'checked'          => $this->is_possible_upgrade() ? 0 : 1,
				'data'             => $import_files['widgets'],
			);
		}

		if ( ! empty( $import_files['options'] ) ) {
			$content['options'] = array(
				'title'            => esc_html__( 'Options', 'yith-proteo-toolkit' ),
				'description'      => esc_html__( 'Sample theme options data.', 'yith-proteo-toolkit' ),
				'pending'          => esc_html( 'Pending' ),
				'installing'       => esc_html( 'Installing' ),
				'success'          => esc_html( 'Success' ),
				'install_callback' => array( 'YITH_Proteo_Wizard_Customizer_Importer', 'import' ),
				'checked'          => $this->is_possible_upgrade() ? 0 : 1,
				'data'             => $import_files['options'],
			);
		}

		if ( false !== has_action( 'wizard_after_all_import' ) ) {
			$content['after_import'] = array(
				'title'            => esc_html__( 'After import setup', 'yith-proteo-toolkit' ),
				'description'      => esc_html__( 'After import setup.', 'yith-proteo-toolkit' ),
				'pending'          => esc_html( 'Pending' ),
				'installing'       => esc_html( 'Installing' ),
				'success'          => esc_html( 'Success' ),
				'install_callback' => array( $this->hooks, 'after_all_import_action' ),
				'checked'          => $this->is_possible_upgrade() ? 0 : 1,
				'data'             => $selected_import_index,
			);
		}

		$content = apply_filters( 'wizard_get_base_content', $content, $this );

		return $content;
	}

	/**
	 * Change the new AJAX request response data.
	 *
	 * @param array $data The default data.
	 *
	 * @return array The updated data.
	 */
	public function pt_importer_new_ajax_request_response_data( $data ) {
		$data['url']      = admin_url( 'admin-ajax.php' );
		$data['message']  = esc_html( 'Installing' );
		$data['proceed']  = 'true';
		$data['action']   = 'wizard_content';
		$data['content']  = 'content';
		$data['_wpnonce'] = wp_create_nonce( 'wizard_nonce' );
		$data['hash']     = md5( wp_rand() ); // Has to be unique (check JS code catching this AJAX response).

		return $data;
	}

	/**
	 * After content import setup code.
	 */
	public function after_content_import_setup() {
		// Set static homepage.
		$homepage = get_page_by_title( apply_filters( 'wizard_content_home_page_title', 'Home' ) );

		if ( $homepage ) {
			update_option( 'page_on_front', $homepage->ID );
			update_option( 'show_on_front', 'page' );

			$this->logger->debug( __( 'The home page was set', 'yith-proteo-toolkit' ), array( 'homepage_id' => $homepage ) );
		}

		// Set static blog page.
		$blogpage = get_page_by_title( apply_filters( 'wizard_content_blog_page_title', 'Blog' ) );

		if ( $blogpage ) {
			update_option( 'page_for_posts', $blogpage->ID );
			update_option( 'show_on_front', 'page' );

			$this->logger->debug( __( 'The blog page was set', 'yith-proteo-toolkit' ), array( 'blog_page_id' => $blogpage ) );
		}
	}

	/**
	 * Before content import setup code.
	 */
	public function before_content_import_setup() {
		// Update the Hello World! post by making it a draft.
		$hello_world = get_page_by_title( 'Hello World!', OBJECT, 'post' );

		if ( ! empty( $hello_world ) ) {
			$hello_world->post_status = 'draft';
			wp_update_post( $hello_world );

			$this->logger->debug( __( 'The Hello world post status was set to draft', 'yith-proteo-toolkit' ) );
		}
	}

	/**
	 * Register the import files via the `yith_proteo_skins_array` filter.
	 */
	public function register_import_files() {
		$this->import_files = $this->validate_import_file_info( apply_filters( 'wizard_import_files', array() ) );
	}

	/**
	 * Filter through the array of import files and get rid of those who do not comply.
	 *
	 * @param  array $import_files list of arrays with import file details.
	 * @return array list of filtered arrays.
	 */
	public function validate_import_file_info( $import_files ) {
		$filtered_import_file_info = array();

		foreach ( $import_files as $skin => $import_file ) {
			if ( ! empty( $import_file['import_file_name'] ) ) {
				$filtered_import_file_info[ $skin ] = $import_file;
			} else {
				$this->logger->warning( __( 'This predefined demo import does not have the name parameter: import_file_name', 'yith-proteo-toolkit' ), $import_file );
			}
		}

		return $filtered_import_file_info;
	}

	/**
	 * Set the import file base name.
	 * Check if an existing base name is available (saved in a transient).
	 */
	public function set_import_file_base_name() {
		$existing_name = get_transient( 'wizard_import_file_base_name' );

		if ( ! empty( $existing_name ) ) {
			$this->import_file_base_name = $existing_name;
		} else {
			$this->import_file_base_name = gmdate( 'Y-m-d__H-i-s' );
		}

		set_transient( 'wizard_import_file_base_name', $this->import_file_base_name, MINUTE_IN_SECONDS );
	}

	/**
	 * Get the import file paths.
	 * Grab the defined local paths, download the files or reuse existing files.
	 *
	 * @param int $selected_import_index The index of the selected import.
	 *
	 * @return array
	 */
	public function get_import_files_paths( $selected_import_index ) {
		$selected_import_data = empty( $this->import_files[ $selected_import_index ] ) ? false : $this->import_files[ $selected_import_index ];

		if ( empty( $selected_import_data ) ) {
			return array();
		}

		// Set the base name for the import files.
		$this->set_import_file_base_name();

		$base_file_name = $this->import_file_base_name;
		$import_files   = array(
			'content' => '',
			'widgets' => '',
			'options' => '',
		);

		$downloader = new YITH_Proteo_Wizard_Downloader();

		// Check if 'import_file_url' is not defined. That would mean a local file.
		if ( empty( $selected_import_data['import_file_url'] ) ) {
			if ( ! empty( $selected_import_data['local_import_file'] ) && file_exists( $selected_import_data['local_import_file'] ) ) {
				$import_files['content'] = $selected_import_data['local_import_file'];
			}
		} else {
			// Set the filename string for content import file.
			$content_filename = 'content-' . $base_file_name . '.xml';

			// Retrieve the content import file.
			$import_files['content'] = $downloader->fetch_existing_file( $content_filename );

			// Download the file, if it's missing.
			if ( empty( $import_files['content'] ) ) {
				$import_files['content'] = $downloader->download_file( $selected_import_data['import_file_url'], $content_filename );
			}

			// Reset the variable, if there was an error.
			if ( is_wp_error( $import_files['content'] ) ) {
				$import_files['content'] = '';
			}
		}

		// Get widgets file as well. If defined!
		if ( ! empty( $selected_import_data['import_widget_file_url'] ) ) {
			// Set the filename string for widgets import file.
			$widget_filename = 'widgets-' . $base_file_name . '.json';

			// Retrieve the content import file.
			$import_files['widgets'] = $downloader->fetch_existing_file( $widget_filename );

			// Download the file, if it's missing.
			if ( empty( $import_files['widgets'] ) ) {
				$import_files['widgets'] = $downloader->download_file( $selected_import_data['import_widget_file_url'], $widget_filename );
			}

			// Reset the variable, if there was an error.
			if ( is_wp_error( $import_files['widgets'] ) ) {
				$import_files['widgets'] = '';
			}
		} elseif ( ! empty( $selected_import_data['local_import_widget_file'] ) ) {
			if ( file_exists( $selected_import_data['local_import_widget_file'] ) ) {
				$import_files['widgets'] = $selected_import_data['local_import_widget_file'];
			}
		}

		// Get customizer import file as well. If defined!
		if ( ! empty( $selected_import_data['import_customizer_file_url'] ) ) {
			// Setup filename path to save the customizer content.
			$customizer_filename = 'options-' . $base_file_name . '.dat';

			// Retrieve the content import file.
			$import_files['options'] = $downloader->fetch_existing_file( $customizer_filename );

			// Download the file, if it's missing.
			if ( empty( $import_files['options'] ) ) {
				$import_files['options'] = $downloader->download_file( $selected_import_data['import_customizer_file_url'], $customizer_filename );
			}

			// Reset the variable, if there was an error.
			if ( is_wp_error( $import_files['options'] ) ) {
				$import_files['options'] = '';
			}
		} elseif ( ! empty( $selected_import_data['local_import_customizer_file'] ) ) {
			if ( file_exists( $selected_import_data['local_import_customizer_file'] ) ) {
				$import_files['options'] = $selected_import_data['local_import_customizer_file'];
			}
		}

		return $import_files;
	}

	/**
	 * AJAX callback for the 'wizard_update_selected_import_data_info' action.
	 */
	public function update_selected_import_data_info() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'wizard' ) ) {
			wp_send_json_error();
		}
		$selected_index = ! isset( $_POST['selected_index'] ) ? false : sanitize_text_field( wp_unslash( $_POST['selected_index'] ) );

		if ( false === $selected_index ) {
			wp_send_json_error();
		}

		$import_info      = $this->get_import_data_info( $selected_index );
		$import_info_html = $this->get_import_steps_html( $import_info );

		wp_send_json_success( $import_info_html );
	}

	/**
	 * Get the import steps HTML output.
	 *
	 * @param array $import_info The import info to prepare the HTML for.
	 *
	 * @return string
	 */
	public function get_import_steps_html( $import_info ) {
		ob_start();
		?>
			<?php foreach ( $import_info as $slug => $available ) : ?>
				<?php
				if ( ! $available ) {
					continue;
				}
				?>

				<li class="wizard__drawer--import-content__list-item status status--Pending" data-content="<?php echo esc_attr( $slug ); ?>">
					<input type="checkbox" name="default_content[<?php echo esc_attr( $slug ); ?>]" class="checkbox checkbox-<?php echo esc_attr( $slug ); ?>" id="default_content_<?php echo esc_attr( $slug ); ?>" value="1" checked>
					<label for="default_content_<?php echo esc_attr( $slug ); ?>">
					<?php
					if ( 'after_import' === $slug ) :
						?>
						<i></i><span><?php echo esc_html__( 'Configure pages, menus and sidebars', 'yith-proteo-toolkit' ); ?></span>
					<?php else : ?>
						<i></i><span><?php echo esc_html( ucfirst( str_replace( '_', ' ', $slug ) ) ); ?></span>
						<?php
					endif;
					?>
					<em></em>
					</label>
				</li>

			<?php endforeach; ?>
		<?php

		return ob_get_clean();
	}


	/**
	 * AJAX call for cleanup after the importing steps are done -> import finished.
	 */
	public function import_finished() {
		delete_transient( 'wizard_import_file_base_name' );
		wp_send_json_success();
	}
}
