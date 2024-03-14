<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

Class WPIMBXSlider extends WPIMCore {

	private static $setting_types = [
		'bxslideshow_mode'                 => [ 'mode', 'string' ],
		'bxslideshow_speed'                => [ 'speed', 'int' ],
		'bxslideshow_duration'             => [ 'duration', 'int' ],
		'bxslideshow_auto'                 => [ 'auto', 'bool' ],
		'bxslideshow_autohover'            => [ 'autoHover', 'bool' ],
		'bxslideshow_autocontrols'         => [ 'autoControls', 'bool' ],
		'bxslideshow_captions'             => [ 'captions', 'bool' ],
		'bxslideshow_custompager'          => FALSE,
		'bxslideshow_adaptiveheight'       => [ 'adaptiveHeight', 'bool' ],
		'bxslideshow_adaptiveheight_speed' => [ 'adaptiveHeightSpeed', 'init' ],
		'bxslideshow_width'                => FALSE,
		'bxslideshow_random'               => [ 'randomStart', 'bool' ],
		'bxslideshow_infinite'             => [ 'infiniteLoop', 'bool' ],
		'bxslideshow_hidecontrols'         => [ 'hideControlOnEnd', 'bool' ],
	];

	private $slideshow_settings = [];

	/**
	 * First call.  Continues only if Inventory Manager is Installed
	 */
	public function __construct() {
		parent::__construct();
		self::$url = self::get_plugin_url( '', __FILE__ );

		add_action( 'init', [ $this, 'init' ] );
		add_action( 'wpim_edit_settings_media', [ $this, 'wpim_edit_settings_media' ] );
		add_action( 'wpim_footer', [ $this, 'wpim_footer' ] );

		// Only set up the front-end hooks if we're not in the admin dashboard
		if ( ! is_admin() ) {
			add_action( 'init', [ $this, 'init' ] );
		}
	}

	/**
	 * WordPress init action.
	 * Hooks into the breadcrumb actions / filters, if they exist.
	 */
	public function init() {
		$this->load_settings();
	}

	/**
	 * Displays the WPIM Admin Settings (selecting the slideshow options)
	 */
	public function wpim_edit_settings_media() {

		$this->load_settings();

		$mode_options = [
			''           => $this->__( 'None / Off' ),
			'horizontal' => $this->__( 'Horizontal' ),
			'vertical'   => $this->__( 'Vertical' ),
			'fade'       => $this->__( 'Fade' )
		];

		echo '<tr class="subtab">';
		echo '<th colspan="2"><h4 data-tab="slideshow_settings">' . self::__( 'Slideshow Settings' ) . '</h4></th>';
		echo '</tr>';
		echo '<tr>';
		echo '<th for="bxslideshow_mode">' . self::__( 'Slide Mode' ) . '</th>';
		echo '<td>';
		echo WPIMCore::dropdown_array( 'bxslideshow_mode', $this->slideshow_settings['bxslideshow_mode'], $mode_options );
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>' . self::__( 'Speed' ) . '</th>';
		echo '<td>';
		echo '<input id="bxslideshow_speed" name="bxslideshow_speed" type="text" value="' . $this->slideshow_settings['bxslideshow_speed'] . '" placeholder="Ex. 2000">';
		echo '<p class="description">' . $this->__( 'Defaults to 2000 if left blank (milliseconds).' ) . '</p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>' . self::__( 'Duration' ) . '</th>';
		echo '<td>';
		echo '<input id="bxslideshow_duration" name="bxslideshow_duration" type="text" value="' . $this->slideshow_settings['bxslideshow_duration'] . '" placeholder="Ex. 500">';
		echo '<p class="decription">' . self::__( 'Defaults to 500 if left blank (milliseconds).' ) . '</p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>' . self::__('Auto') . '</th>';
		echo '<td>';
		echo WPIMCore::dropdown_yesno( 'bxslideshow_auto', $this->slideshow_settings['bxslideshow_auto'] );
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>' . self::__( 'Auto Hover' ) . '</th>';
		echo '<td>';
		echo WPIMCore::dropdown_yesno( 'bxslideshow_autohover', $this->slideshow_settings['bxslideshow_autohover'] );
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>' . self::__( 'Auto Controls' ) . '</th>';
		echo '<td>';
		echo WPIMCore::dropdown_yesno( 'bxslideshow_autocontrols', $this->slideshow_settings['bxslideshow_autocontrols'] );
		echo '<p class="description">' . self::__( '"Auto" above must be set to yes for this setting to work' ) . '</p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>' . self::__( 'Use Captions' ) . '</th>';
		echo '<td>';
		echo WPIMCore::dropdown_yesno( 'bxslideshow_captions', $this->slideshow_settings['bxslideshow_captions'] );
		echo '<p class="description">' . self::__( 'This uses the "title" attribute of the image.  You can edit this in your media or set it when uploading a new image.' ) . '</p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>' . self::__( 'Use Thumbnails as pager' ) . '</th>';
		echo '<td>';
		echo WPIMCore::dropdown_yesno( 'bxslideshow_custompager', $this->slideshow_settings['bxslideshow_custompager'] );
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>' . self::__( 'Width' ) . '</th>';
		echo '<td>';
		echo '<input id="bxslideshow_width" name="bxslideshow_width" type="text"  value="' . $this->slideshow_settings['bxslideshow_width'] . '" placeholder="Ex. 400">';
		echo '<p class="description">' . self::__( 'Default is 100% - enter any dimension you like (for example, 500px)' ) . '</p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>' . self::__( 'Random start' ) . '</th>';
		echo '<td>';
		echo WPIMCore::dropdown_yesno( 'bxslideshow_random', $this->slideshow_settings['bxslideshow_random'] );
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>' . self::__( 'Infinite loop' ) . '</th>';
		echo '<td>';
		echo WPIMCore::dropdown_yesno( 'bxslideshow_infinite', $this->slideshow_settings['bxslideshow_infinite'] );
		echo '<p class="description">' . $this->__( 'Set this option for the show to go back to the first slide at the end and vice versa.' ) . '</p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>' . self::__( 'Hide controls on end' ) . '</th>';
		echo '<td>';
		echo WPIMCore::dropdown_yesno( 'bxslideshow_hidecontrols', $this->slideshow_settings['bxslideshow_hidecontrols'] );
		echo '<p class="description">' . $this->__( 'Set this option to take away the next button at the end of the show and vice versa. This requires that infinite loop above not be set.' ) . '</p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>' . self::__( 'Use adaptive height' ) . '</th>';
		echo '<td>';
		echo WPIMCore::dropdown_yesno( 'bxslideshow_adaptiveheight', $this->slideshow_settings['bxslideshow_adaptiveheight'] );
		echo '<p class="description">' . $this->__( 'This will adjust the height of the slideshow to each image.  Useful if your images are different sizes.' ) . '</p>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th>' . self::__( 'Adaptive height speed' ) . '</th>';
		echo '<td>';
		echo '<input id="bxslideshow_adaptiveheight_speed" name="bxslideshow_adaptiveheight_speed" type="text" value="' . $this->slideshow_settings['bxslideshow_adaptiveheight_speed'] . '" placeholder="Ex. 500">';
		echo '<p class="description">' . self::__( 'This is measured in milliseconds.  It require that "Adaptive Height" above be set to "Yes".  If left blank it defaults to 500.' ) . '</p>';
		echo '</td>';
		echo '</tr>';
	}

	/**
	 * Load the configuration settings (field to display in breadcrumb trail)
	 */
	private function load_settings() {
		$config   = WPIMConfig::getInstance();
		$settings = $config->get_all();

		foreach ( $settings AS $key => $value ) {
			if ( 0 === stripos( $key, 'bxslideshow' ) ) {
				$this->slideshow_settings[ $key ] = ( isset( $settings[ $key ] ) ) ? $settings[ $key ] : $value;
			}
		}
	}

	public function wpim_footer() {
		if ( ! wpinventory_is_single() ) {
			return;
		}

		if ( ! wpinventory_get_config( 'bxslideshow_mode' ) ) {
			return;
		}

		echo '<!-- Scripts added by WP Inventory Slideshow Settings.  Turn off in the dashboard if desired. -->' . PHP_EOL;

		$json = [];
		foreach ( $this->slideshow_settings AS $key => $value ) {
			$value = $this->parse_value( $key, $value );
			$key   = ( isset( self::$setting_types[ $key ] ) ) ? self::$setting_types[ $key ] : FALSE;
			if ( $key !== FALSE ) {
				$json[ $key[0] ] = $value;
			}
		}

		if ( $this->slideshow_settings['bxslideshow_custompager'] ) {
			$json['pagerCustom'] = '#bx-pager';
		}

		$width = $this->slideshow_settings['bxslideshow_width'];

		if ( $width != preg_replace( '/[^0-9.]/', '', $width ) ) {
			// We have units, keep them
		} else {
			// If it's unitless, infer pixels
			$width .= 'px';
		}

		$json['width'] = $width;

		?>
        <script>wpim_bxslideshow        = <?php echo json_encode( $json ); ?>;
          wpim_bxslideshow.onAfterSlide = function () {
            jQuery( ".inventory_images li:eq(currentSlide) img" ).animate( { opacity: 0.4 } );
          };

          jQuery( function ( $ ) {
            if ( $( '.inventory_images img' ).length <= 0 ) {
              return;
            }
			  <?php if ($this->slideshow_settings['bxslideshow_custompager']) { ?>
            var $thumbs = $( '<ul id="bx-pager"></ul>' );
            var count   = 0;
            $( '.inventory_images img' ).each( function () {
              $thumbs.append( '<li><a href="" data-slide-index="' + count++ + '"><img src="' + $( this ).attr( 'src' ) + '" style="height: 50px; width: auto;"></a></li>'
              );
            } );
            $thumbs.wrap( '<div id="bxslider_pager_wrapper"></div>' );
            $( '.inventory_images' ).after( $thumbs );
			  <?php } ?>
            $( ".inventory_images" ).wrap( '<div class="bxslideshow_sizer"></div>' );
            $( '.bxslideshow_sizer' ).width( wpim_bxslideshow.width );
            $( ".inventory_images .wpinventory_label" ).remove();
            $( ".inventory_images" ).bxSlider( wpim_bxslideshow );
          } );
        </script>
		<?php wp_print_scripts( 'wpinventory_bxslideshow' );
	}

	private function parse_value( $key, $value ) {
		self::load_settings();
		$type = ( ! empty( self::$setting_types[ $key ] ) ) ? self::$setting_types[ $key ] : NULL;
		if ( $type === FALSE ) {
			return NULL;
		}

		$type = ( isset( $type[1] ) ) ? $type[1] : 'string';
		if ( $type == 'bool' ) {
			return (bool) $value;
		}

		if ( $type == 'int' ) {
			return (int) $value;
		}

		return $value;
	}
}

add_action( 'wpim_load_add_ons', 'wpim_core_launch_slideshow' );

function wpim_core_launch_slideshow() {
	new WPIMBXSlider();
}
