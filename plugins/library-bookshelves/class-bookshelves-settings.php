<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Bookshelves_Settings {

	private static $instance = null;
	public $parent = null;
	public $base = '';
	public $settings = array();

	public function __construct( $parent ) {
		$this->parent = $parent;
		$this->base = 'lbs_';

		// Initialize settings.
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register settings.
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add Settings page link.
		add_action( 'admin_menu', array( $this, 'add_menu_item' ) );
		
		// display default admin notice
		add_action('admin_notices', array( $this, 'add_settings_errors' ) );

		// Add settings link to the plugin menu.
		add_filter( 'plugin_action_links_' . plugin_basename( $parent->file ), array( $this, 'add_settings_link' ) );
	}

	public function init_settings() {
		$this->settings = $this->settings_fields();
	}

	public function add_settings_errors() {
		settings_errors();
	}

	public function add_menu_item() {
		$page = add_submenu_page(
			'edit.php?post_type=bookshelves',
			'Bookshelf Settings',
			'Settings',
			'manage_options',
			'bookshelf-settings-page',
			array( $this, 'settings_page' )
		);
	}

	public function add_settings_link( $links ) {
		$settings_link = '<a href="edit.php?post_type=bookshelves&page=bookshelf-settings-page">Settings</a>';
		array_push( $links, $settings_link );
		return $links;
	}

	//==================================================
	// Register plugin settings
	//==================================================
	public function register_settings() {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab.
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}

			foreach ( $this->settings as $section => $data ) {
				if ( $current_section && $current_section !== $section ) {
					continue;
				}

				// Add section to page.
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->token . '_settings' );

				foreach ( $data['fields'] as $field ) {
					// Validation callback for field.
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field.
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->token . '_settings', $option_name, $validation );

					// Add field to page.
					add_settings_field(
						$field['id'],
						$field['label'],
						'lbs_display_setting_field',
						$this->parent->token . '_settings',
						$section,
						array(
							'field'  => $field,
							'prefix' => $this->base,
						)
					);
				}
				if ( ! $current_section ) { break; }
			}
		}
	}

	//==================================================
	// Delete plugin settings
	//==================================================
	public function unregister_settings() {
		foreach ( $this->settings as $section => $data ) {
			foreach ( $data['fields'] as $field ) {
				// Unregister field.
				$option_name = $this->base . $field['id'];
				unregister_setting( $this->parent->token . '_settings', $option_name, $validation );
			}
		}
	}

	//==================================================
	// Write settings section title and description
	//==================================================
	public function settings_section( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}

	//==================================================
	// Assemble settings page
	//==================================================
	public function settings_page() {
		$html = '<div class="wrap" id="' . $this->parent->token . '_settings">' . "\n";
		$html .= '<h2>' . esc_html__( 'Library Bookshelves Plugin Settings', 'library-bookshelves' ) . "</h2>\n";
		$tab = '';

		if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
			$tab .= $_GET['tab'];
		}

		// Assemble page tabs.
		if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

			$html .= '<h2 class="nav-tab-wrapper">' . "\n";
			$c = 0;

			foreach ( $this->settings as $section => $data ) {

				// Set tab class.
				$class = 'nav-tab';
				if ( ! isset( $_GET['tab'] ) ) {
					if ( 0 === $c ) {
						$class .= ' nav-tab-active';
					}
				} else {
					if ( isset( $_GET['tab'] ) && $section === $_GET['tab'] ) {
						$class .= ' nav-tab-active';
					}
				}

				// Set tab link.
				$tab_link = add_query_arg( array( 'tab' => $section ) );
				
				if ( isset( $_GET['settings-updated'] ) ) {
					$tab_link = remove_query_arg( 'settings-updated', $tab_link );
				}

				// Write tab.
				$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html__( $data['title'], 'library-bookshelves' ) . '</a>' . "\n";

				++$c;
			}

			$html .= '</h2>' . "\n";
		}
		
		// Assemble settings form.
		$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

		// Assemble settings fields.
		ob_start();
		settings_fields( $this->parent->token . '_settings' );
		do_settings_sections( $this->parent->token . '_settings' );
		$html .= ob_get_clean();
		if ( $tab == 'slick' ) {
			$html .= '<p class="reset">' . "\n";
			$html .= '<input name="ResetSlick" type="button" class="button-primary" value="' . esc_html__( 'Reset Slick Defaults', 'library-bookshelves' ) . '" onclick="resetSlickDefaults()" />' . "\n";
			$html .= '</p>' . "\n";
		}
		$html .= '<p class="submit">' . "\n";
		$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
		$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_html__( 'Save Settings', 'library-bookshelves' ) . '" />' . "\n";
		$html .= '</p>' . "\n";
		$html .= '</form>' . "\n";
		$html .= '</div>' . "\n";
		echo $html;

		// Modify settings options based on user selections
		?>
		<script>
			function resetSlickDefaults() {
				jQuery( "#slick_accessibility, #slick_autoplay, #slick_draggable, #slick_infinite, #slick_pauseOnFocus, #slick_swipe, #slick_swipeToSlide, #slick_touchMove, #slick_useCSS, #slick_waitForAnimate" ).prop( "checked", true );
				jQuery( "#slick_adaptiveHeight, #slick_rtl, #slick_arrows, #slick_captions, #slick_captions_overlay, #slick_centerMode, #slick_dots, #slick_fade, #slick_focusOnChange, #slick_focusOnSelect, #slick_mobileFirst, #slick_pauseOnHover, #slick_pauseOnDotsHover, #slick_useTransform, #slick_variableWidth, #slick_vertical, #slick_verticalSwiping, #slick_rtl" ).prop( "checked", false );
				jQuery( "#slick_autoplaySpeed" ).val( "3000" );
				jQuery( "#slick_centerPadding" ).val( "50px" );
				jQuery( "#slick_lazyLoad" ).val( "ondemand" );
				jQuery( "#slick_respondTo" ).val( "slider" );
				jQuery( "#slick_responsive" ).val( '[ { breakpoint: 1025, settings: { slidesToShow: 6, slidesToScroll: 1 } }, { breakpoint: 769, settings: { slidesToShow: 4, slidesToScroll: 1 } }, { breakpoint: 481, settings: { slidesToShow: 2, slidesToScroll: 2 } } ]' );
				jQuery( "#slick_rows, #slick_slidesPerRow, #slick_slidesToScroll" ).val( "1" );
				jQuery( "#slick_slidesToShow" ).val( "6" );
				jQuery( "#slick_speed" ).val( "300" );
				jQuery( "#slick_touchThreshold" ).val( "5" );
				jQuery( "#slick_zIndex" ).val( "1000" );
			}

			jQuery( "#cat_System" ).attr( "onchange", "service(this.value)" );
			jQuery( "#cat_CDN" ).attr( "onchange", "cdn(this.value)" );
			cat_sys = jQuery( "#cat_System" ).val();
			cat_CDN = jQuery( "#cat_CDN" ).val();
			service( cat_sys );
			cdn( cat_CDN );

			function service( cat_sys ) {
				enableCatDomain();
				enableEbooks();
				enableCatProfile();

				switch ( cat_sys ) {
					case 'alexandria':
					case 'aspen':
					case 'bibliocommons':
					case 'dbtextworks':
					case 'encore':
					case 'koha':
					case 'pika':
					case 'sirsi_horizon':
					case 'spydus':
					case 'surpass':
					case 'tlc':
					case 'vega':
					case 'worldcatds':
						disableCustomCatalog();
						disableCatProfile();
						break;
					case 'atriuum':
						disableCustomCatalog();
						jQuery( "#cat_Profile + label" ).html( "<?php esc_html_e( 'Enter your Atriuum library ID found in your OPAC URL (.../opac/[ID]/index.html...).', 'library-bookshelves' ); ?> " );
						break;
					case 'calibre':
						disableCustomCatalog();
						jQuery( "#cat_Profile + label" ).html( "<?php esc_html_e( 'Enter your Calibre library ID.', 'library-bookshelves' ); ?> " );
						jQuery( "#cat_CDN" ).val( "calibre" ).change();
						disableEbooks();
						break;
					case 'cops':
						disableCustomCatalog();
						jQuery( "#cat_Profile + label" ).html( "<?php esc_html_e( 'If your COPS installation is located in a subdirectory of your domain, enter the directory name here.', 'library-bookshelves' ); ?> " );
						jQuery( "#cat_CDN" ).val( "cops" ).change();
						disableEbooks();
						break;
					case 'ebsco_eds':
						jQuery( "#cat_Profile + label" ).html( "<?php esc_html_e( 'Enter your EBSCOHost customer ID.', 'library-bookshelves' ); ?> " );
						jQuery( "#cat_CDN" ).val( "ebsco" ).change();
						disableCatDomain();
						disableCustomCatalog();
						disableEbooks();
						break;
					case 'evergreen-record':
						disableCustomCatalog();
						jQuery( "#cat_Profile + label" ).html( "<?php esc_html_e( 'Enter your library location ID for your Bookshelf links to show catalog customizations for your library. Leave blank for the default catalog skin.', 'library-bookshelves' ); ?> " );
						jQuery( "#cat_CDN" ).val( "evergreen-record" ).change();
						disableEbooks();
						break;
					case 'primo':
						disableCustomCatalog();
						jQuery( "#cat_Profile + label" ).html( "<?php esc_html_e( 'Enter the identifier from the VID parameter of your catalog URL.', 'library-bookshelves' ); ?> " );
						break;
					case 'evergreen':
					case 'insignia':
					case 'polaris':
					case 'polaris63':
					case 'sirsi_ent':
					case 'tlc_ls1':
					case 'webpac':
						disableCustomCatalog();
						jQuery( "#cat_Profile + label" ).html( "<br><?php esc_html_e('Enter your library location ID for your Bookshelf links to show catalog customizations for, or to limit item information to, your library. Leave blank for the default catalog skin and/or to show item information for all locations.', 'library-bookshelves' ); ?> " );
						break;
					case 'hoopla':
					case 'opac_sbn':
					case 'openlibrary':
					case 'worldcat':
						disableCatDomain();
						disableCustomCatalog();
						disableCatProfile();
						break;
					case 'cloudlibrary':
						disableCustomCatalog();
						disableCatProfile();
						disableEbooks();
						break;
					case 'overdrive':
						enableCatDomain();
						disableCustomCatalog();
						disableCatProfile();
						disableEbooks();
						break;
					case 'other':
						enableCustomCatalog();
						disableCatDomain();
						disableCatProfile();
						disableEbooks();
						break;
				}
			}
			
			function disableCatDomain() {
				jQuery( "#cat_Protocol" ).parent().parent().hide();
				jQuery( "#cat_DomainName" ).parent().parent().hide();
			}

			function enableCatDomain() {
				jQuery( "#cat_Protocol" ).parent().parent().show();
				jQuery( "#cat_DomainName" ).parent().parent().show();

			}
			
			function disableCatProfile() {
				jQuery( "#cat_Profile" ).parent().parent().hide();
			}
			
			function enableCatProfile() {
				jQuery( "#cat_Profile" ).parent().parent().show();
			}
			
			function disableEbooks() {
				jQuery( "#cat_cloudlibrary" ).parent().parent().hide();
				jQuery( "#cat_overdrive" ).parent().parent().hide();
			}

			function enableEbooks() {
				jQuery( "#cat_cloudlibrary" ).parent().parent().show();
				jQuery( "#cat_overdrive" ).parent().parent().show();
			}

			function disableCustomCatalog() {
				jQuery( "#cat_URL" ).parent().parent().hide();
			}

			function enableCustomCatalog() {
				jQuery( "#cat_URL" ).parent().parent().show();
			}

			function cdn( cat_CDN ) {
				switch ( cat_CDN ) {
					case 'amazon':
					case 'aspen':
					case 'calibre':
					case 'cops':
					case 'chilifresh':
					case 'ebsco':
					case 'evergreen':
					case 'evergreen-record':
					case 'opac_sbn':
					case 'openlibrary':
					case 'pika':
						jQuery( "#cat_CDN_URL" ).parent().parent().hide();
						jQuery( "#cat_CDN_id" ).parent().parent().hide();
						jQuery( "#cat_CDN_pass" ).parent().parent().hide();
						jQuery( "#cat_placeholder" ).parent().parent().parent().show();
						break;
					case 'contentcafe':
						jQuery( "#cat_CDN_URL" ).parent().parent().hide();
						jQuery( "#cat_CDN_id + label" ).html( "<?php esc_html_e( 'Baker & Taylor ContentCafe may require a username and password.', 'library-bookshelves' ); ?> " );
						jQuery( "#cat_CDN_id" ).parent().parent().show();
						jQuery( "#cat_CDN_pass" ).parent().parent().show();
						jQuery( "#cat_placeholder" ).parent().parent().parent().hide();
						break;
					case 'encore':
						jQuery( "#cat_CDN_URL" ).parent().parent().hide();
						jQuery( "#cat_CDN_id" ).parent().parent().hide();
						jQuery( "#cat_CDN_pass" ).parent().parent().hide();
						jQuery( "#cat_placeholder" ).parent().parent().parent().hide();
						break;
					case 'syndetics':
						jQuery( "#cat_CDN_URL" ).parent().parent().hide();
						jQuery( "#cat_CDN_id + label" ).html( "<?php esc_html_e( 'Syndetics may require a customer ID.', 'library-bookshelves' ); ?>" );
						jQuery( "#cat_CDN_id" ).parent().parent().show();
						jQuery( "#cat_placeholder" ).parent().parent().parent().show();
						break;
					case 'tlc':
						jQuery( "#cat_CDN_URL" ).parent().parent().hide();
						jQuery( "#cat_CDN_id + label" ).html( "<?php esc_html_e( 'TLC requires a customer ID.', 'library-bookshelves' ); ?>" );
						jQuery( "#cat_CDN_id" ).parent().parent().show();
						jQuery( "#cat_CDN_pass" ).parent().parent().hide();
						jQuery( "#cat_placeholder" ).parent().parent().parent().show();
						break;
					case 'other':
						jQuery( "#cat_CDN_URL" ).parent().parent().show();
						jQuery( "#cat_CDN_id" ).parent().parent().hide();
						jQuery( "#cat_CDN_pass" ).parent().parent().hide();
						jQuery( "#cat_placeholder" ).parent().parent().parent().show();
						break;
				}
			}
		</script>
		<?php
	}

	//==================================================
	// Build settings object
	//==================================================
	private function settings_fields() {
		$settings = lbs_settings_obj();
		return $settings;
	}

	public static function instance( $parent ) {
		null === self::$instance && self::$instance = new self( $parent );
		return self::$instance;
	}
}
