<?php


namespace WidgetForEventbriteAPI\Admin;


/**
 * Class Settings
 * @package WidgetForEventbriteAPI\Admin
 */
class Admin_Pages {

	public $block_table_obj;
	protected $settings_page;  // toplevel appearance etc  followed by slug


	// for the block report
	protected $settings_page_id = 'toplevel_page_plugin_name';

	//
	protected $settings_title;

	protected $plugin_name;
	protected $version;
	protected $freemius;


	public function __construct() {


	}

	public function add_meta_boxes() {
		// in extended class
	}

	public function add_required_meta_boxes() {
		global $hook_suffix;

		if ( $this->settings_page_id == $hook_suffix ) {

			$this->add_meta_boxes();

			add_meta_box(
				'submitdiv',               /* Meta Box ID */
				esc_html__( 'Save Options', 'widget-for-eventbrite-api' ),            /* Title */
				array( $this, 'submit_meta_box' ),  /* Function Callback */
				$this->settings_page_id,                /* Screen: Our Settings Page */
				'side',                    /* Context */
				'high'                     /* Priority */
			);
		}
	}

	public function delete_options() {
		// for extended class to manage
	}

	public function enqueue_scripts( $hook_suffix ) {
		$page_hook_id = $this->settings_page_id;
		if ( $hook_suffix == $page_hook_id ) {
			wp_enqueue_script( 'common' );
			wp_enqueue_script( 'wp-lists' );
			wp_enqueue_script( 'postbox' );
			$confmsg = esc_html__( 'Are you sure want to do this?', 'widget-for-eventbrite-api' );
			$script  = <<<JS
          jQuery(document).ready(function ($) {
                // toggle
                $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
                postboxes.add_postbox_toggles('<?php echo esc_attr( $page_hook_id ); ?>');
                // display spinner
                $('#fx-smb-form').submit(function () {
                    $('#publishing-action .spinner').css('visibility', 'visible');
                });
                // confirm before reset
                $('#delete-action input').on('click', function () {
                    return confirm('<?php echo esc_html( $confmsg ); ?>');
                });
            });
JS;
			wp_add_inline_script( 'common', $script );
		}
	}

	public function register_settings() {
		// overide in extended class
	}

	public function reset_sanitize( $settings ) {
		/* Add Update Notice */

		if ( ! empty( $settings ) ) {
			add_settings_error( $this->option_group, '', esc_html__( 'Settings reset to defaults.', 'widget-for-eventbrite-api' ), 'updated' );
			/* Delete Option */
			$this->delete_options();

		}

		return $settings;
	}

	public function screen_layout_column( $columns, $screen ) {
		$page_hook_id = $this->settings_page_id;
		if ( $screen == $page_hook_id ) {
			$columns[ $page_hook_id ] = 2;
		}

		return $columns;
	}

	/**
	 *
	 */
	public function settings_page() {

		/* global vars */
		global $hook_suffix;
		if ( $this->settings_page_id == $hook_suffix ) {


			/* enable add_meta_boxes function in this page. */
			do_action( $this->settings_page_id . '_settings_page_boxes', $hook_suffix );
			?>

            <div class="wrap">

                <h2><?php echo esc_html( $this->settings_title ); ?></h2>

				<?php
				global $pagenow;
				if ( $pagenow !== "options-general.php" ) {
					settings_errors();
				} ?>

                <div class="fs-settings-meta-box-wrap">

                    <form id="fs-smb-form" method="post" action="options.php">

						<?php settings_fields( $this->option_group ); // options group
						?>
						<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
						<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>

                        <div id="poststuff">

                            <div id="post-body"
                                 class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">

                                <div id="postbox-container-1" class="postbox-container">

									<?php do_meta_boxes( $hook_suffix, 'side', null ); ?>
                                    <!-- #side-sortables -->

                                </div><!-- #postbox-container-1 -->

                                <div id="postbox-container-2" class="postbox-container">

									<?php do_meta_boxes( $hook_suffix, 'normal', null ); ?>
                                    <!-- #normal-sortables -->

									<?php do_meta_boxes( $hook_suffix, 'advanced', null ); ?>
                                    <!-- #advanced-sortables -->

                                </div><!-- #postbox-container-2 -->

                            </div><!-- #post-body -->

                            <br class="clear">

                        </div><!-- #poststuff -->

                    </form>

                </div><!-- .fs-settings-meta-box-wrap -->

            </div><!-- .wrap -->
			<?php
		}

	}

	public function settings_setup() {

		/* top level
				add_menu_page(
					$this->settings_title, // Page Title
					$this->settings_title,                       // Menu Title
					'manage_options',                 // Capability
					'plugin-name',                         // Page Slug
					array( $this, 'settings_page' ),           // Settings Page Function Callback
					'dashicons-shield',           // Menu Icon
					70                                // Menu Position
				);
		*/
		/* Add settings menu page */
		add_submenu_page(
			'options-general.php',
			$this->settings_title, /* Page Title */
			$this->settings_title,                       /* Menu Title */
			'manage_options',                 /* Capability */
			'widget-for-eventbrite-api-settings',                         /* Page Slug */
			array( $this, 'settings_page' )          /* Settings Page Function Callback */
		);

		$this->register_settings();


		/* Vars */
		$page_hook_id = $this->settings_page_id;

		/* Do stuff in settings page, such as adding scripts, etc. */
		if ( ! empty( $this->settings_page ) ) {
			/* Load the JavaScript needed for the settings screen. */
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			/* Set number of column available. */
			add_filter( 'screen_layout_columns', array( $this, 'screen_layout_column' ), 10, 2 );
			add_action( $this->settings_page_id . '_settings_page_boxes', array( $this, 'add_required_meta_boxes' ) );

		}
	}

	public function submit_meta_box() {

		?>
        <div id="submitpost" class="submitbox">

            <div id="major-publishing-actions">

                <div id="delete-action">
                    <input type="submit" name="<?php echo esc_attr( "{$this->option_group}-reset" ); ?>"
                           id="<?php echo esc_attr( "{$this->option_group}-reset" ); ?>"
                           class="button"
                           value="Reset Settings">
                </div><!-- #delete-action -->

                <div id="publishing-action">
                    <span class="spinner"></span>
					<?php submit_button( esc_attr__( 'Save', 'widget-for-eventbrite-api' ), 'primary', 'submit', false ); ?>
                </div>

                <div class="clear"></div>

            </div><!-- #major-publishing-actions -->

        </div><!-- #submitpost -->

		<?php
	}


}
