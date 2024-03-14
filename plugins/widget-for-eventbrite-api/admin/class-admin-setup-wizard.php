<?php


namespace WidgetForEventbriteAPI\Admin;


/**
 * Class Settings
 * @package WidgetForEventbriteAPI\Admin
 */
class Admin_Setup_Wizard {

	public $block_table_obj;
	protected $settings_page;  // toplevel appearance etc  followed by slug


	// for the block report
	protected $settings_page_id = 'settings_page_widget-for-eventbrite-api-setup-wizard';

	//
	protected $settings_title;

	protected $plugin_name;
	protected $version;
	protected $freemius;


	public function __construct() {
		add_action( 'wp_ajax_update_api_option', array( $this, 'update_api_option' ) );
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
				__( 'Save Options', 'widget-for-eventbrite-api' ),            /* Title */
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
		if ( $hook_suffix == $this->settings_page_id ) {
			wp_enqueue_script( 'common' );
			wp_enqueue_script( 'wp-lists' );
			wp_enqueue_script( 'postbox' );
		}
	}

	public function footer_scripts() {
		$page_hook_id = $this->settings_page_id;
		$confmsg      = __( 'Are you sure want to do this?', 'widget-for-eventbrite-api' );
		?>
        <script type="text/javascript">
            //<![CDATA[
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
            //]]>
        </script>
		<?php
	}

	public function register_settings() {

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
		if ( $screen == $this->settings_page_id ) {
			$columns[ $this->settings_page_id ] = 1;
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

			?>
            <div class="wfea-wrap">

                <div id="wfea-wizard">
                    <div class="container">

                        <div class="block">
                            <div class="logo">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1244.25 214.02" width="300">
                                    <g id="Layer_2" data-name="Layer 2">
                                        <g id="Layer_1-2" data-name="Layer 1">
                                            <path d="M448.21,192.85v11.66h-5.14V173.76h12q5.09,0,8,2.63a9.75,9.75,0,0,1,0,13.86q-2.88,2.59-8,2.6Zm0-4.12h6.88a5.61,5.61,0,0,0,4.29-1.52,5.38,5.38,0,0,0,1.44-3.86,5.19,5.19,0,0,0-5.73-5.47h-6.88Z"
                                                  style="fill:#253c5a"/>
                                            <path d="M543.15,200.39h14.13v4.12H538V173.76h5.13Z" style="fill:#253c5a"/>
                                            <path d="M651.78,173.76v20.61q0,5.12-3.33,7.85a13.37,13.37,0,0,1-8.73,2.74,12.85,12.85,0,0,1-8.55-2.75q-3.27-2.75-3.27-7.84V173.76H633v20.6a6.17,6.17,0,0,0,1.81,4.85,7,7,0,0,0,4.88,1.63,7.42,7.42,0,0,0,5-1.63,6.07,6.07,0,0,0,1.88-4.85v-20.6Z"
                                                  style="fill:#253c5a"/>
                                            <path d="M747.83,200.35a10.72,10.72,0,0,1-4,3.18,15.8,15.8,0,0,1-7.19,1.43,12.53,12.53,0,0,1-9.14-3.52,12.15,12.15,0,0,1-3.6-9.09v-6.42a12.58,12.58,0,0,1,3.39-9.1,11.64,11.64,0,0,1,8.76-3.51q5.43,0,8.44,2.67a9.07,9.07,0,0,1,3.07,7l0,.12h-4.86a5.94,5.94,0,0,0-1.81-4.08,6.61,6.61,0,0,0-4.74-1.56,6.33,6.33,0,0,0-5.14,2.39,9.26,9.26,0,0,0-2,6.06v6.45a8.83,8.83,0,0,0,2.12,6.11,7,7,0,0,0,5.5,2.4,11.45,11.45,0,0,0,3.91-.56,5.31,5.31,0,0,0,2.17-1.32v-6.23h-6.1v-3.85h11.24Z"
                                                  style="fill:#253c5a"/>
                                            <path d="M826.55,204.51h-5.13V173.76h5.13Z" style="fill:#253c5a"/>
                                            <path d="M924.58,204.51h-5.11l-13.65-22.45-.12,0v22.41h-5.13V173.76h5.13l13.64,22.43.13,0V173.76h5.11Z"
                                                  style="fill:#253c5a"/>
                                            <path d="M1015,196.59a3.92,3.92,0,0,0-1.42-3.13,13.22,13.22,0,0,0-5-2.19,21.17,21.17,0,0,1-7.93-3.58,7.14,7.14,0,0,1-2.78-5.84,7.47,7.47,0,0,1,3.08-6.13,12.49,12.49,0,0,1,7.93-2.4,11.8,11.8,0,0,1,8.14,2.73,8.17,8.17,0,0,1,3,6.56l0,.13H1015a5,5,0,0,0-1.6-3.84,8,8,0,0,0-8.9-.24,3.87,3.87,0,0,0-1.52,3.16,3.48,3.48,0,0,0,1.64,2.91,18.28,18.28,0,0,0,5.42,2.19,18.12,18.12,0,0,1,7.54,3.67,7.74,7.74,0,0,1,2.58,6,7.21,7.21,0,0,1-3.08,6.11A13.23,13.23,0,0,1,1009,205a14.25,14.25,0,0,1-8.44-2.57,7.85,7.85,0,0,1-3.53-7l0-.13H1002a4.79,4.79,0,0,0,1.95,4.19,8.44,8.44,0,0,0,5,1.39,7.42,7.42,0,0,0,4.44-1.15A3.62,3.62,0,0,0,1015,196.59Z"
                                                  style="fill:#253c5a"/>
                                            <polygon
                                                    points="163.44 5.32 64.08 5.32 64.06 46.19 78.78 31.48 128.34 31.48 143.97 47.09 143.97 64.11 101.73 64.11 101.73 79.72 134.98 79.72 134.98 112.35 101.73 112.35 101.73 144.75 183.28 144.75 183.28 25.24 163.44 5.32"
                                                    style="fill:#253c5a"/>
                                            <polygon
                                                    points="42.74 123.53 42.74 139.15 75.99 139.15 75.99 171.78 42.74 171.78 42.74 203.79 19.79 203.79 4.17 189.31 4.17 106.52 19.79 90.91 69.36 90.91 84.98 106.52 84.98 123.53 42.74 123.53"
                                                    style="fill:#253c5a"/>
                                            <polygon
                                                    points="525.15 109.67 525.15 144.86 456.92 144.86 440.5 128.46 440.5 41.51 456.92 25.12 481.15 25.12 481.15 109.67 525.15 109.67"
                                                    style="fill:#253c5a"/>
                                            <polygon
                                                    points="624.18 109.43 624.18 144.86 555.95 144.86 539.55 128.46 539.55 41.51 555.95 25.12 580.19 25.12 580.19 109.43 624.18 109.43"
                                                    style="fill:#253c5a"/>
                                            <polygon
                                                    points="782.55 41.44 756.36 144.86 716.32 144.86 704.91 108.93 693.84 144.86 653.73 144.86 627.55 41.44 643.93 25.06 665.78 25.06 675.25 71.22 691.04 25.06 720 25.06 735.15 71.09 745.11 25.06 766.17 25.06 782.55 41.44"
                                                    style="fill:#253c5a"/>
                                            <path d="M885.93,41.45C875.84,30.59,862,25,844.7,25s-31.22,5.55-41.27,16.49-15,25.47-15,43.53,5,32.54,15,43.35c10.05,11,23.92,16.59,41.27,16.59s31.14-5.5,41.23-16.36S901,103.27,901,85.06s-5-32.89-15.05-43.61m-26.5,54.83a26.59,26.59,0,0,1-4,8.25,11.7,11.7,0,0,1-4.69,4,13.82,13.82,0,0,1-6,1.36,14.46,14.46,0,0,1-5.9-1.26,12.21,12.21,0,0,1-4.77-4,25.38,25.38,0,0,1-4-8.2,49.27,49.27,0,0,1-1.57-13.61,33,33,0,0,1,1.71-11.49,25.1,25.1,0,0,1,3.83-8.2,13.54,13.54,0,0,1,4.86-4,14.71,14.71,0,0,1,5.81-1.13,13.91,13.91,0,0,1,5.86,1.26,12.52,12.52,0,0,1,4.78,3.92,25.62,25.62,0,0,1,3.92,8.29,33.94,33.94,0,0,1,1.62,11.31,50.68,50.68,0,0,1-1.49,13.56"
                                                  style="fill:#253c5a"/>
                                            <path d="M994.31,94.85a44.06,44.06,0,0,0,9.8-9.81c4.54-6.23,6.83-14.25,6.83-23.82,0-7-1.47-13-4.35-17.79a35.15,35.15,0,0,0-11.27-11.5,40,40,0,0,0-14.67-5.54,96.56,96.56,0,0,0-16.49-1.24H931l-16.4,16.4v86.93L931,144.85h24.11v-35l28.36,35H1021V128.57Zm-24-25.34a6.37,6.37,0,0,1-2,2.47l-.14.14a8.69,8.69,0,0,1-4,1.65,41.15,41.15,0,0,1-8.38.69h-.65V57.27h2.16a66.27,66.27,0,0,1,6.92.28,15.09,15.09,0,0,1,4,1,3.66,3.66,0,0,1,2,1.56,9,9,0,0,1,.82,4.22,13.38,13.38,0,0,1-.73,5.22"
                                                  style="fill:#253c5a"/>
                                            <polygon
                                                    points="1133.64 128.65 1133.64 144.86 1095.66 144.86 1072.53 110.16 1072.53 144.86 1048.32 144.86 1031.93 128.48 1031.93 41.66 1048.32 25.29 1072.53 25.29 1072.53 57.33 1095.59 25.29 1115.45 25.29 1131.69 41.54 1100.97 81.51 1133.64 128.65"
                                                    style="fill:#253c5a"/>
                                            <path d="M1239.43,104.94c0,12.13-4.84,22-14.37,29.42-9.14,7.06-21.46,10.65-36.58,10.65a92.51,92.51,0,0,1-22.56-2.5,114.59,114.59,0,0,1-17.84-6.09l-1-.44V109.71h19.77A52.58,52.58,0,0,0,1172,112a47.42,47.42,0,0,0,16.67,3.27,44.21,44.21,0,0,0,5.28-.44,15.33,15.33,0,0,0,4.43-1.11l.14-.06a7.92,7.92,0,0,0,2.82-2,3,3,0,0,0,.58-2.2,2.62,2.62,0,0,0-1.12-2.31,14.73,14.73,0,0,0-5.47-2.62c-3.49-.91-7.27-1.82-11.24-2.63A99.83,99.83,0,0,1,1172,98.54c-9.18-3.24-15.92-7.77-20.07-13.48s-6.33-12.94-6.33-21.35c0-11.59,4.94-21.07,14.68-28.19,9.22-6.76,20.8-10.19,34.37-10.19a99.29,99.29,0,0,1,19.77,2,86.47,86.47,0,0,1,17.71,5.52l.83.45,0,24.9h-21.37l-.2-.09A40.15,40.15,0,0,0,1196,55.06a32.78,32.78,0,0,0-5.29.39,19.58,19.58,0,0,0-4.51,1.51,6.67,6.67,0,0,0-2.41,1.9,2.49,2.49,0,0,0-.63,1.67,2.63,2.63,0,0,0,1,2.34c.44.35,2.41,1.68,8.9,3.19,3.53.8,6.9,1.59,10.19,2.35a96.81,96.81,0,0,1,11.1,3.27c8.22,3,14.5,7.12,18.67,12.32,4.29,5.39,6.47,12.44,6.47,20.94"
                                                  style="fill:#253c5a"/>
                                            <path d="M424.48,41.8V97.66c0,15-4.51,26.77-13.39,35.08s-21.49,12.35-37.87,12.35c-15.9,0-28.49-4.07-37.4-12.07C326.67,124.8,322,112.94,322,97.73V41.8L338.4,25.44h24.22V94.76c0,7.1,1.46,10.47,2.69,12.07.49.66,2,2.63,7.91,2.63s7.46-2,8-2.61c1-1.21,2.66-4.38,2.66-12.09V25.44H408.1Z"
                                                  style="fill:#253c5a"/>
                                            <polygon
                                                    points="263.69 59.56 263.69 75.9 298.48 75.9 298.48 110.04 263.69 110.04 263.69 144.74 239.68 144.74 223.33 128.39 223.33 41.75 239.68 25.42 291.54 25.42 307.88 41.75 307.88 59.56 263.69 59.56"
                                                    style="fill:#253c5a"/>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <h1>
								<?php esc_html_e( 'Welcome to Display Eventbrite Events!', 'widget-for-eventbrite-api' ); ?>
                            </h1>
                            <p><?php esc_html_e( 'Thank you for choosing Display Eventbrite Events - the premier WordPress plugin for displaying Eventbrite events.', 'widget-for-eventbrite-api' ); ?></p>
                            <p><?php esc_html_e( 'So let\'s get started', 'widget-for-eventbrite-api' ); ?></p>

                            <ol>
                                <li>
									<?php esc_html_e( 'Get your API Key:', 'widget-for-eventbite-api' ); ?>
									<?php esc_html_e( 'Vist this page to get your private token', 'widget-for-eventbrite-api' ) ?>
                                    <a class="wfea-link"
                                       href="https://www.eventbrite.com/platform/api-keys" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                             height="24" class="components-external-link__icon css-bqq7t3 etxm6pv0"
                                             role="img" aria-hidden="true" focusable="false">
                                            <path d="M18.2 17c0 .7-.6 1.2-1.2 1.2H7c-.7 0-1.2-.6-1.2-1.2V7c0-.7.6-1.2 1.2-1.2h3.2V4.2H7C5.5 4.2 4.2 5.5 4.2 7v10c0 1.5 1.2 2.8 2.8 2.8h10c1.5 0 2.8-1.2 2.8-2.8v-3.6h-1.5V17zM14.9 3v1.5h3.7l-6.4 6.4 1.1 1.1 6.4-6.4v3.7h1.5V3h-6.3z"></path>
                                        </svg>
                                    </a>
                                </li>
                                <li>
									<?php esc_html_e( 'Paste or Input the API key into the box below', 'widget-for-eventbite-api' ); ?>
                                </li>
                            </ol>
                            <div class="api-key">
                                <input type="text"
                                       data-nonce="<?php echo esc_attr( wp_create_nonce( "wfea_api_key" ) ); ?>"
                                       id="widget-for-eventbrite-api-setup-api-key" name="api_key"
                                       value=""
                                       placeholder="<?php esc_attr_e( 'Your Eventbrite private token', 'widget-for-eventbrite-api' ); ?>"
                                       required>
                                <p class="api-key-result"></p>
                            </div>
                            <div class="result"></div>
                            <div class="settings-link"><a
                                        href="<?php echo esc_url( admin_url( 'options-general.php?page=widget-for-eventbrite-api-settings' ) ); ?>"><?php esc_html_e( 'go to settings', 'widget-for-eventbrite-api' ); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .wrap -->
			<?php
		}

	}

	public function settings_setup() {


		/* Add settings menu page */
		add_submenu_page(
			'options-general.php',
			esc_html__( 'Display Eventbrite Setup Wizard', 'widget-for-eventbrite-api' ),
			esc_html__( 'Display Eventbrite Setup Wizard', 'widget-for-eventbrite-api' ),
			'manage_options',                 /* Capability */
			'widget-for-eventbrite-api-setup-wizard',                         /* Page Slug */
			array( $this, 'settings_page' )          /* Settings Page Function Callback */
		);

		$this->register_settings();


		/* Vars */
		$page_hook_id = $this->settings_page_id;

		/* Do stuff in settings page, such as adding scripts, etc. */
		if ( ! empty( $this->settings_page ) ) {
			/* Load the JavaScript needed for the settings screen. */
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( "admin_footer-{$page_hook_id}", array( $this, 'footer_scripts' ) );
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

	public function update_api_option() {
		if ( ! wp_doing_ajax() ) {
			die;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			die;
		}
		//check nonce ajax
		check_ajax_referer( 'wfea_api_key', 'nonce' );
		$options        = get_option( 'widget-for-eventbrite-api-settings' );
		$options['key'] = ( isset( $_POST['apikey'] ) ) ? sanitize_text_field( wp_unslash( $_POST['apikey'] ) ) : '';
		update_option( 'widget-for-eventbrite-api-settings', $options );
		echo json_encode( array( 'result' => true ) );
		die();

	}


}
