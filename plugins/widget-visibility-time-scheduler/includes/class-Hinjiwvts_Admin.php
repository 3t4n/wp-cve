<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://wordpress.org/plugins/widget-visibility-time-scheduler
 * @since      1.0.0
 *
 * @package    Hinjiwvts
 * @subpackage Hinjiwvts/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Hinjiwvts
 * @subpackage Hinjiwvts/admin
 * @author     Kybernetik Services <wordpress@kybernetik.com.de>
 */
class Hinjiwvts_Admin {

	/**
	 * The slug of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_slug    The slug of this plugin.
	 */
	private $plugin_slug;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_version    The current version of this plugin.
	 */
	private $plugin_version;

	/**
	 * Actions of widget
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      array    $modes    actions of widget
	 */
	private $modes;

	/**
	 * Current day on server
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $current_dd    current day
	 */
	private $current_dd;

	/**
	 * Current month on server
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $current_mm    current month
	 */
	private $current_mm;

	/**
	 * Current year on server
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $current_yy    current year
	 */
	private $current_yy;

	/**
	 * Current hour on server
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $current_hh    current hour
	 */
	private $current_hh;

	/**
	 * Current minute on server
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $current_mn    current minute
	 */
	private $current_mn;

	/**
	 * Current second on server
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $current_ss    current second
	 */
	private $current_ss;

	/**
	 * Start and end names for widget
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $names_widget_boundaries    Start and end names for widget
	 */
	private $names_widget_boundaries;

	/**
	 * Values and names for the weekdays
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $weekdays    Values and names for the weekdays
	 */
	private $weekdays;

	/**
	 * Form field ids
	 *
	 * @since    2.0
	 * @access   private
	 * @var      array    $field_ids   distinct ids of form field elements
	 */
	private $field_ids;

	/**
	 * Current widget time settings
	 *
	 * @since    2.0
	 * @access   private
	 * @var      array    $scheduler   scheduler settings for the current widget
	 */
	private $scheduler;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_slug       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_slug, $version ) {

		$this->plugin_slug = $plugin_slug;
		$this->plugin_version = $version;
		$this->modes = array( 'Show', 'Hide' );
		$this->names_widget_boundaries = array( 'start', 'end' );
		$this->weekdays = array(
			'Monday'	=> 1,
			'Tuesday'	=> 2,
			'Wednesday'	=> 3,
			'Thursday'	=> 4,
			'Friday'	=> 5,
			'Saturday'	=> 6,
			'Sunday'	=> 7 
		);

		// set current date and time vars
		$timestamp = current_time( 'timestamp' ); // get current local blog timestamp
		$this->current_yy = idate( 'Y', $timestamp ); // get year as integer, 4 digits
		$this->current_mm = idate( 'm', $timestamp ); // get month number as integer
		$this->current_dd = idate( 'd', $timestamp ); // get day number as integer
		$this->current_hh = idate( 'H', $timestamp ); // get hour as integer, 24 hour format
		$this->current_mn = idate( 'i', $timestamp ); // get minute as integer
		$this->current_ss = 0; // set seconds to zero
		
		// not in use, just for the po-editor to display the translation on the plugins overview list
		$foo = esc_html__( 'Control the visibility of each widget based on date, time and weekday easily.', 'hinjiwvts' );

	}

	/**
	 * Register the stylesheets for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook_suffix ) {
		// load only if we are on the Widgets page
        if ( 'widgets.php' != $hook_suffix ) {
            return;
        }

        wp_enqueue_style( $this->plugin_slug, WVTS_URL . 'admin/css/hinjiwvts-admin'. ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), $this->plugin_version, 'all' );

	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    5.0
	 */
	public function enqueue_scripts( $hook_suffix ) {

		// load only if we are on the Widgets page
		if ( 'widgets.php' == $hook_suffix ) {
			// scripts for the admin pages
			wp_enqueue_script( $this->plugin_slug, WVTS_URL . 'admin/js/hinjiwvts-admin.min.js', array( 'jquery' ), $this->plugin_version, false );

			// translations in scripts
			$translations = array(
				'open_scheduler' => __( 'Open scheduler', 'hinjiwvts' ),
				'close_scheduler' => __( 'Close scheduler', 'hinjiwvts' ),
			);
			wp_localize_script( $this->plugin_slug, 'wvts_i18n', $translations );

		}
		
	}

	/**
	 * Print a message about the location of the plugin in the WP backend
	 * 
	 * @since    1.0.0
	 */
	public function display_activation_message () {
		
		$text_1 = 'Appearance';
		$text_2 = 'Widgets';
		
		if ( is_rtl() ) {
			$sep = '&lsaquo;';
			// set link #1
			$link_1 = sprintf(
				'<a href="%s">%s %s %s</a>',
				esc_url( admin_url( 'widgets.php' ) ),
				esc_html__( $text_2 ),
				$sep,
				esc_html__( $text_1 )
			);
		} else {
			$sep = '&rsaquo;';
			// set link #1
			$link_1 = sprintf(
				'<a href="%s">%s %s %s</a>',
				esc_url( admin_url( 'widgets.php' ) ),
				esc_html__( $text_1 ),
				$sep,
				esc_html__( $text_2 )
			);
		}
		
		// set whole message
		printf(
			'<div class="updated notice is-dismissible"><p>%s</p></div>',
			sprintf( 
				esc_html__( 'Welcome to Widget Visibility Time Scheduler! You can set the time based visibility in each widget on the page %s.', 'hinjiwvts' ),
				$link_1
			)
		);

	}

    /**
     * Print a message about the block based widgets in since WP 5.8
     *
     * @since 5.3.12
     */
    public function display_wp58_message() {

        printf(
            '<div class="notice-warning notice"><p>%s</p></div>',
            sprintf(
                __( '<b>Important:</b> You are using WordPress 5.8 or higher. Widget Visibility Time Scheduler is currently not compatible with the newly introduced block based widgets. To continue using Widget Visibility Time Scheduler, please install and activate the plug-in %s. It brings back the usual widgets and Widget Visibility Time Scheduler is working as expected.', 'periodical-widget-visibility' ),
                '<a href="https://wordpress.org/plugins/classic-widgets/" target="_blank">Classic Widgets</a>'
            )
        );

    }

    /**
	 * Add the widget conditions to each widget in the admin.
	 *
	 * @param $widget unused.
	 * @param $return unused.
	 * @param array $widget_settings The widget settings.
	 */
	public function display_time_fields( $widget, $return, $widget_settings ) {
		
		$this->field_ids = array();
		$this->scheduler = array();

		// prepare html elements ids for widget start and end time
		foreach( array( 'yy', 'mm', 'dd', 'hh', 'mn', 'ss' ) as $field_name ) {
			foreach( $this->names_widget_boundaries as $boundary ) {
				$name = $boundary . '-' . $field_name;
				$this->field_ids[ $name ] = $widget->get_field_id( $name );
			}
		}

		// check and sanitize stored settings; if not set: set them to current time
		if ( isset( $widget_settings[ $this->plugin_slug ] ) ) {
			$this->scheduler = $widget_settings[ $this->plugin_slug ];
		}

		/* deprecated since v4.0:
		// scheduler status
		if ( isset( $this->scheduler[ 'is_active' ] ) ) {
			$this->scheduler[ 'is_active' ] = 1;
		} else {
			$this->scheduler[ 'is_active' ] = 0;
		}
		*/

		/* deprecated since v4.0:
		// action status
		if ( isset( $this->scheduler[ 'is_opposite' ] ) ) {
			$this->scheduler[ 'is_opposite' ] = 1;
		} else {
			$this->scheduler[ 'is_opposite' ] = 0;
		}
		*/
		
		/* deprecated since v4.0:
		// infinite end
		if ( isset( $this->scheduler[ 'end_infinite' ] ) ) {
			$this->scheduler[ 'end_infinite' ] = 1;
		} else {
			$this->scheduler[ 'end_infinite' ] = 0;
		}
		*/
		
		// modes
		if ( isset( $this->scheduler[ 'mode' ] ) and in_array( $this->scheduler[ 'mode' ], $this->modes ) ) {
			// pass
		} else {
			$this->scheduler[ 'mode' ] = '';
		}
		// convert from plugin version < 4.0
		if ( isset( $this->scheduler[ 'is_active' ] ) ) {
			$this->scheduler[ 'mode' ] = $this->modes[ 0 ]; // mode = show
			unset( $this->scheduler[ 'is_active' ] );
			if ( isset( $this->scheduler[ 'is_opposite' ] ) ) {
				$this->scheduler[ 'mode' ] = $this->modes[ 1 ]; // mode = hide
				unset( $this->scheduler[ 'is_opposite' ] );
			}
		}

		// start and end times
		if ( isset( $this->scheduler[ 'timestamps' ] ) ) {
			foreach( $this->names_widget_boundaries as $boundary ) {
				if ( isset( $this->scheduler[ 'timestamps' ][ $boundary ] ) ) {
					$timestamp = (int) $this->scheduler[ 'timestamps' ][ $boundary ]; // get stored Unix timestamp
				} else {
					$timestamp = current_time( 'timestamp' ); // get current local blog timestamp
				}
				$this->scheduler[ $boundary . '-yy' ] = idate( 'Y', $timestamp ); // get year as integer, 4 digits
				$this->scheduler[ $boundary . '-mm' ] = idate( 'm', $timestamp ); // get month number as integer
				$this->scheduler[ $boundary . '-dd' ] = idate( 'd', $timestamp ); // get day number as integer
				$this->scheduler[ $boundary . '-hh' ] = idate( 'H', $timestamp ); // get hour as integer, 24 hour format
				$this->scheduler[ $boundary . '-mn' ] = idate( 'i', $timestamp ); // get minute as integer
			}
		} else {
			$timestamp = current_time( 'timestamp' ); // get current local blog timestamp
			foreach( $this->names_widget_boundaries as $boundary ) {
				$this->scheduler[ $boundary . '-yy' ] = idate( 'Y', $timestamp ); // get year as integer, 4 digits
				$this->scheduler[ $boundary . '-mm' ] = idate( 'm', $timestamp ); // get month number as integer
				$this->scheduler[ $boundary . '-dd' ] = idate( 'd', $timestamp ); // get day number as integer
				$this->scheduler[ $boundary . '-hh' ] = idate( 'H', $timestamp ); // get hour as integer, 24 hour format
				$this->scheduler[ $boundary . '-mn' ] = idate( 'i', $timestamp ); // get minute as integer
			}
		}
		
		// weekdays
		if ( isset( $this->scheduler[ 'daysofweek' ] ) ) {
			$sanitized_daysofweek = array_map( 'absint', $this->scheduler[ 'daysofweek' ] ); // convert values from string to positive integers
			foreach ( range( 1, 7 ) as $dayofweek ) {
				if ( in_array( $dayofweek, $sanitized_daysofweek ) ) {
					$this->scheduler[ 'daysofweek' ][] = $dayofweek;
				}
			}
		} else {
			// default: all checked
			$this->scheduler[ 'daysofweek' ] = range( 1, 7 );
		}

		// print additional input fields in widget
		include WVTS_ROOT . 'admin/partials/hinjiwvts-fieldsets.php';
		
		// return null because new fields are added
		return null;
	}

	/**
	 * Print out HTML form date elements for editing widget publish date.
	 *
	 * Borrowed from WP-own function touch_current_time( 'timestamp' ) in /wp-admin/includes/template.php
	 *
	 * @since 1.0.0
	 *
	 * @param string $boundary
	 */
	private function touch_time( $boundary ) {
		global $wp_locale;
		
		// check and sanitize stored settings

		//  month
		$label = 'Month';
		$name = $boundary . '-mm';
		$var = isset( $this->scheduler[ $name ] ) ? absint( $this->scheduler[ $name ] ) : $this->current_mm;
		$values[ $name ] = ( 1 <= $var and $var <= 12 ) ? zeroise( $var, 2 ) : zeroise( $this->current_mm, 2 );
		$month = sprintf(
			'<label for="%s" class="screen-reader-text">%s</label><select id="%s" name="%s[%s]">',
			$this->field_ids[ $name ],
			esc_html__( $label ),
			$this->field_ids[ $name ],
			$this->plugin_slug,
			$name 
		);
		$label = '%1$s-%2$s';
		for ( $i = 1; $i < 13; $i = $i +1 ) {
			$monthnum = zeroise($i, 2); // add leading zero for values < 10
			$month .= sprintf(
				'<option value="%s" %s>',
				$monthnum,
				selected( $monthnum, $values[ $name ], false ) 
			);
			/* translators: 1: month number (01, 02, etc.), 2: month abbreviation */
			$month .= esc_html( sprintf( __( $label ), $monthnum, $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) ) ) . '</option>';
		}
		$month .= '</select>';

		//  year
		$label = 'Year';
		$name = $boundary . '-yy';
		$var = isset( $this->scheduler[ $name ] ) ? absint( $this->scheduler[ $name ] ) : $this->current_yy;
		$values[ $name ] = ( 1970 <= $var and $var <= 2037 ) ? strval( $var ) : zeroise( $this->current_yy, 2 );
		$year   = sprintf(
			'<label for="%s" class="screen-reader-text">%s</label><input type="text" id="%s" name="%s[%s]" value="%s" size="4" maxlength="4" autocomplete="off" />',
			$this->field_ids[ $name ],
			esc_html__( $label ),
			$this->field_ids[ $name ],
			$this->plugin_slug,
			$name,
			$values[ $name ] 
		);

		//  day
		$label = 'Day';
		$name = $boundary . '-dd';
		$var = isset( $this->scheduler[ $name ] ) ? absint( $this->scheduler[ $name ] ) : $this->current_dd;
		$values[ $name ] = ( 1 <= $var and $var <= 31 ) ? zeroise( $var, 2 ) : zeroise( $this->current_dd, 2 );
		$day = sprintf(
			'<label for="%s" class="screen-reader-text">%s</label><input type="text" id="%s" name="%s[%s]" value="%s" size="2" maxlength="2" autocomplete="off" />',
			$this->field_ids[ $name ],
			esc_html__( $label ),
			$this->field_ids[ $name ],
			$this->plugin_slug,
			$name,
			$values[ $name ] 
		);

		//  hour
		$label = 'Hour';
		$name = $boundary . '-hh';
		$var = isset( $this->scheduler[ $name ] ) ? absint( $this->scheduler[ $name ] ) : $this->current_hh;
		$values[ $name ] = ( 0 <= $var and $var <= 23 ) ? zeroise( $var, 2 ) : zeroise( $this->current_hh, 2 );
		$hour = sprintf(
			'<label for="%s" class="screen-reader-text">%s</label><input type="text" id="%s" name="%s[%s]" value="%s" size="2" maxlength="2" autocomplete="off" />',
			$this->field_ids[ $name ],
			esc_html__( $label ),
			$this->field_ids[ $name ],
			$this->plugin_slug,
			$name,
			$values[ $name ] 
		);

		//  minute
		$label = 'Minute';
		$name = $boundary . '-mn';
		$var = isset( $this->scheduler[ $name ] ) ? absint( $this->scheduler[ $name ] ) : $this->current_mn;
		$values[ $name ] = ( 0 <= $var and $var <= 59 ) ? zeroise( $var, 2 ) : zeroise( $this->current_mn, 2 );
		$minute = sprintf(
			'<label for="%s" class="screen-reader-text">%s</label><input type="text" id="%s" name="%s[%s]" value="%s" size="2" maxlength="2" autocomplete="off" />',
			$this->field_ids[ $name ],
			esc_html__( $label ),
			$this->field_ids[ $name ],
			$this->plugin_slug,
			$name,
			$values[ $name ] 
		);

		/* translators: 1: month, 2: day, 3: year, 4: hour, 5: minute */
		$label = '%1$s %2$s, %3$s at %4$s:%5$s';
		printf( esc_html__( $label ), $month, $day, $year, $hour, $minute ) . "\n";

		//  seconds
		$name = $boundary . '-ss';
		printf( 
			'<input type="hidden" id="%s" name="%s[%s]" value="00" maxlength="2" />',
			$this->field_ids[ $name ],
			$this->plugin_slug,
			$name 
		) . "\n";

	}
	
	/**
	 * On an AJAX update of the widget settings, sanitize and return the display conditions.
	 *
	 * @param	array	$new_widget_settings	New settings for this instance as input by the user.
	 * @param	array	$old_widget_settings	Old settings for this instance.
	 * @return	array	$widget_settings		Processed settings.
	 */
	public static function widget_update( $widget_settings, $new_widget_settings, $old_widget_settings ) {

		$datetime = array();
		$scheduler = array();
		$plugin_slug = 'hinjiwvts';
		
		// sanitize user input

		// if neither activated nor weekday checked, save time and quit now without settings
		/* deprecated since v4.0:
		if ( ! isset( $_POST[ $plugin_slug ][ 'is_active' ] ) or ! isset( $_POST[ $plugin_slug ][ 'daysofweek' ] ) ) {
		*/
		if ( empty( $_POST[ $plugin_slug ][ 'mode' ] ) ) {
			// if former settings are in the widget_settings: delete them
			if ( isset( $widget_settings[ $plugin_slug ] ) ) {
				unset( $widget_settings[ $plugin_slug ] );
			}
			return $widget_settings;
		}
		
		// get weekdays values
		$sanitized_daysofweek = array_map( 'absint', $_POST[ $plugin_slug ][ 'daysofweek' ] ); // convert values from string to positive integers
		$scheduler[ 'daysofweek' ] = array();
		foreach ( range( 1, 7 ) as $dayofweek ) {
			if ( in_array( $dayofweek, $sanitized_daysofweek ) ) {
				$scheduler[ 'daysofweek' ][] = $dayofweek;
			}
		}
		// if no valid weekday given, save time and quit now without settings
		if ( empty( $scheduler[ 'daysofweek' ]) ) {
			if ( isset( $widget_settings[ $plugin_slug ] ) ) {
				unset( $widget_settings[ $plugin_slug ] );
			}
			return $widget_settings;
		}

		/* deprecated since v4.0:
		// set active status
		$scheduler[ 'is_active' ] = 1;
		*/
		
		// set widget action: show / hide ?
		/* deprecated since v4.0:
		if ( isset( $_POST[ $plugin_slug ][ 'is_opposite' ] ) ) {
			$scheduler[ 'is_opposite' ] = 1;
		}
		*/
		if ( isset( $_POST[ $plugin_slug ][ 'mode' ] ) and in_array( $_POST[ $plugin_slug ][ 'mode' ], array( 'Show', 'Hide' ) ) ) {
			$scheduler[ 'mode' ] = $_POST[ $plugin_slug ][ 'mode' ];
		}

		/* deprecated since v4.0:
		// if neither activated nor weekday checked, save time and quit now without settings
		if ( isset( $_POST[ $plugin_slug ][ 'end_infinite' ] ) ) {
			$scheduler[ 'end_infinite' ] = 1;
		}
		*/

		// set current date and time vars
		// (neccessary to write it once more instead of re-use $this->xx because we are here in a non-object context)
		$timestamp = current_time( 'timestamp' ); // get current local blog timestamp
		$current_yy = idate( 'Y', $timestamp ); // get year as integer, 4 digits
		$current_mm = idate( 'm', $timestamp ); // get month number as integer
		$current_dd = idate( 'd', $timestamp ); // get day number as integer
		$current_hh = idate( 'H', $timestamp ); // get hour as integer, 24 hour format
		$current_mn = idate( 'i', $timestamp ); // get minute as integer
		$current_ss = 0; // set seconds to zero

		// set timestamps of widget start and end
		foreach( array( 'start', 'end' ) as $boundary ) {
			// year
			$name = $boundary . '-yy';
			$var = isset( $_POST[ $plugin_slug ][ $name ] ) ? absint( $_POST[ $plugin_slug ][ $name ] ) : $current_yy;
			$datetime[ $name ] = ( 1970 <= $var and $var <= 2037 ) ? $var : $current_yy;
			// month
			$name = $boundary . '-mm';
			$var = isset( $_POST[ $plugin_slug ][ $name ] ) ? absint( $_POST[ $plugin_slug ][ $name ] ) : $current_mm;
			$datetime[ $name ] = ( 1 <= $var and $var <= 12 ) ? $var : $current_mm;
			// day
			$name = $boundary . '-dd';
			$var = isset( $_POST[ $plugin_slug ][ $name ] ) ? absint( $_POST[ $plugin_slug ][ $name ] ) : $current_dd;
			$datetime[ $name ] = ( 1 <= $var and $var <= 31 ) ? $var : $current_dd;
			// hour
			$name = $boundary . '-hh';
			$var = isset( $_POST[ $plugin_slug ][ $name ] ) ? absint( $_POST[ $plugin_slug ][ $name ] ) : $current_hh;
			$datetime[ $name ] = ( 0 <= $var and $var <= 23 ) ? $var : $current_hh;
			// minute
			$name = $boundary . '-mn';
			$var = isset( $_POST[ $plugin_slug ][ $name ] ) ? absint( $_POST[ $plugin_slug ][ $name ] ) : $current_mn;
			$datetime[ $name ] = ( 0 <= $var and $var <= 59 ) ? $var : $current_mn;
			// second
			$name = $boundary . '-ss';
			$datetime[ $name ] = 0;
			
			$scheduler[ 'timestamps' ][ $boundary ] = mktime(
				$datetime[ $boundary . '-hh' ],
				$datetime[ $boundary . '-mn' ],
				$datetime[ $boundary . '-ss' ],
				$datetime[ $boundary . '-mm' ],
				$datetime[ $boundary . '-dd' ],
				$datetime[ $boundary . '-yy' ]
			);
		}

		// if too high year set the highest possible values for the end date and time
		$name = 'end-yy';
		$var = isset( $_POST[ $plugin_slug ][ $name ] ) ? absint( $_POST[ $plugin_slug ][ $name ] ) : $current_yy;
		if ( 2037 < $var ) {
			$scheduler[ 'timestamps' ][ 'end' ] = mktime( 23, 59, 59, 12, 31, 2037 );
			// store the flag into the db to trigger the display of a message after activation
			set_transient( $plugin_slug, '1', 60 );
		}

		// return sanitized user settings
		$widget_settings[ $plugin_slug ] = $scheduler;
		return $widget_settings;
	}

}
