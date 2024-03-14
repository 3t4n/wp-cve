<?php
/*
 * Plugin Name: Smart Countdown FX
 * Text Domain: smart-countdown
 * Domain Path: /languages
 * Plugin URI: http://wp.smartcalc.org
 * Description: Display and configure multiple Smart Countdown FX animated timers using a shortcode or sidebar widget.
 * Version: 1.5.5
 * Author: Alex Polonski
 * Author URI: http://wp.smartcalc.org
 * License: GPLv2 or later
 */
defined ( 'ABSPATH' ) or die ();

define ( 'SCD_MINIMUM_REQUIRED_WP_VERSION', '4.0' );

require_once (dirname ( __FILE__ ) . '/includes/helper.php');
class SmartCountdown_Widget extends WP_Widget {
	private static $defaults = array (
			'units' => array (
					'years' => 1,
					'months' => 1,
					'weeks' => 1,
					'days' => 1,
					'hours' => 1,
					'minutes' => 1,
					'seconds' => 1 
			),
			'paddings' => array (
					'years' => 1,
					'months' => 2,
					'weeks' => 1,
					'days' => 2,
					'hours' => 2,
					'minutes' => 2,
					'seconds' => 2 
			),
			
			'hide_highest_zeros' => 1,
			'allow_all_zeros' => 0,
			
			'countdown_limit' => - 1,
			'countup_limit' => - 1,
			'hide_countup_counter' => 0,
			
			'title_before_down' => '',
			'title_before_up' => '',
			'title_after_down' => '',
			'title_after_up' => '',
			
			'show_title' => 1, // not implemented as a setting, deafult - 1
			'layout' => 'vert',
			'event_text_pos' => 'vert',
			'labels_pos' => 'right',
			'labels_vert_align' => 'middle',
			'digits_size' => 36,
			'labels_size' => 10,
			'title_before_size' => 24,
			'title_after_size' => 18,
			'title_before_style' => '',
			'title_after_style' => '',
			'digits_style' => '',
			'labels_style' => '',
			'base_font_size' => SCD_BASE_FONT_SIZE,
			
			'widget_style' => '',
			'redirect_url' => '',
			'click_url' => '',
			
			'fx_preset' => 'Sliding_text_fade.xml',
			'layout_preset' => 'sidebar.xml',
			'import_config' => '' 
	);
	public function __construct() {
		$widget_ops = array (
				'classname' => 'widget_smartcountdown',
				'description' => __ ( 'Responsive Countdown / countup widget.' ) 
		);
		parent::__construct ( 'smartcountdown', __ ( 'Smart Countdown FX' ), $widget_ops );
		
		add_action ( 'admin_enqueue_scripts', array (
				$this,
				'admin_scripts' 
		) );
		add_action ( 'wp_enqueue_scripts', array (
				$this,
				'counter_scripts' 
		) );
		
		add_action ( 'wp_ajax_nopriv_scd_query_next_event', 'SmartCountdown_Widget::queryNextEvent' );
		add_action ( 'wp_ajax_scd_query_next_event', 'SmartCountdown_Widget::queryNextEvent' );
		
		load_plugin_textdomain ( 'smart-countdown', false, dirname ( plugin_basename ( __FILE__ ) ) . '/languages/' );
	}
	public static function smartcountdown_widget_init() {
		register_widget ( 'SmartCountdown_Widget' );
	}
	public function widget($args, $instance) {
		/*
		 * This will replace all nested arrays from defaults with instance values
		 * recursive array merge gives a strange error with presets (layout and animation) WHY?!!!
		 */
		$instance = array_merge ( self::$defaults, $instance );
		
		// Overload instance settings using a configuration file (if found)
		$instance = SmartCountdown_Helper::getCounterConfig ( $instance );
		
		$instance ['id'] = $args ['widget_id'];
		
		// set hide_lower_units option basing on display configuration. For lowest units
		// not included into display configuration we set "hide unit" flag and
		// reactivate unit in display config. *** Document this better
		$hide_lower_units = array ();
		if ($instance ['allow_all_zeros'] == 0) {
			foreach ( array_reverse ( $instance ['units'], true ) as $asset => $display ) {
				if ($display == 0) {
					$hide_lower_units [] = $asset;
					$instance ['units'] [$asset] = 1;
				} else {
					// first unit set as displayed, break the loop
					break;
				}
			}
		}
		$instance ['hide_lower_units'] = $hide_lower_units;
		
		$instance ['animations'] = SmartCountdown_Helper::getAnimations ( $instance );
		if ($instance ['animations'] === false) {
			// log error here!!!
			// echo '<h3>FX profile invalid!</h3>';
			return;
		}
		
		if (function_exists ( 'is_customize_preview' ) && is_customize_preview ()) {
			// in customize preview other smartcountdown widget may exist on the page.
			// We have to set 'customize_preview' in instance options only if this instance,
			// is being customized actually
			$customized = json_decode ( wp_unslash ( $_POST ['customized'] ), true );
			
			if (! empty ( $customized )) {
				$scd_widgets_ids = array_keys ( $customized );
				// array keys in customized are formed as 'widget_<widget_name>[<widget_id>]' -
				// convert them to <widget_name>-<widget_id> form, for correct comparison with
				// $instance['id']
				foreach ( $scd_widgets_ids as &$id ) {
					$id = str_replace ( array (
							'[',
							']' 
					), array (
							'-',
							'' 
					), substr ( $id, strlen ( 'widget_' ) ) );
				}
				
				// check if we are handling the instance being customized
				if (in_array ( $instance ['id'], $scd_widgets_ids )) {
					$id = substr ( $instance ['id'], strrpos ( $instance ['id'], '-' ) + 1 );
					// get new settings
					$new_instance = unserialize ( base64_decode ( $customized ['widget_smartcountdown[' . $id . ']'] ['encoded_serialized_instance'], true ) );
					
					// we have to add customized settings responsable for "query next event" AJAX requests
					// and also indicate that we are in preview mode
					// Client script will later handle these options back to server AJAX controller in
					// order to get correct events in response.
					$instance ['customize_preview'] = 1;
					$instance ['deadline'] = $new_instance ['deadline'];
					$instance ['import_config'] = $new_instance ['import_config'];
					$instance ['countdown_limit'] = $new_instance ['countdown_limit'];
					$instance ['countup_limit'] = $new_instance ['countup_limit'];
				} else {
					// normal view, not a preview
					$instance ['customize_preview'] = 0;
				}
			}
		}
		
		/*
		 * *** TODO: use apply_filters to sanitize / convert other widget output!
		 */
		$title = apply_filters ( 'widget_title', empty ( $instance ['title'] ) ? '' : $instance ['title'], $instance, $this->id_base );
		
		/*
		 * We are using standard before/after-widget and before/after-title properties here... Check if they make sense
		 * in our scenario - we already define event title before/after counter for both down and up modes
		 */
		
		// If the widget is rendered using a shortcode we must wrap it into a container DIV:
		// 1. Have a reliable wrapper to conditionally show/hide the widget
		// 2. Apply responsive feature changing font-size of the container
		if (! empty ( $instance ['shortcode'] )) {
			?>
<div id="<?php echo $instance['id']; ?>" style="font-size:<?php echo SCD_BASE_FONT_SIZE ?>px">
			<?php
		}
		
		echo $args ['before_widget'];
		if (! empty ( $title ) && $instance ['show_title']) {
			echo $args ['before_title'] . $title . $args ['after_title'];
		}
		$widget_style = ! empty ( $instance ['widget_style'] ) ? ' style="' . $instance ['widget_style'] . '"' : '';
		?>
			<div class="textwidget" <?php echo $widget_style; ?>><?php echo SmartCountdown_Helper::getCounterHtml($instance); ?></div>
		<?php
		echo $args ['after_widget'];
		
		?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					scds_container.add(<?php echo json_encode($instance); ?>);
				});
			</script>
		<?php
		
		// Close the wrapper div if the widget is rendered using a shortcode
		if (! empty ( $instance ['shortcode'] )) {
			?>
			</div>
<?php
		}
	}
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		// widget title
		$instance ['title'] = strip_tags ( $new_instance ['title'] );
		
		// event texts
		$allowed_tags = wp_kses_allowed_html ( 'post' );
		$instance ['title_before_down'] = stripslashes ( wp_kses ( $new_instance ['title_before_down'], $allowed_tags ) );
		$instance ['title_after_down'] = stripslashes ( wp_kses ( $new_instance ['title_after_down'], $allowed_tags ) );
		$instance ['title_before_up'] = stripslashes ( wp_kses ( $new_instance ['title_before_up'], $allowed_tags ) );
		$instance ['title_after_up'] = stripslashes ( wp_kses ( $new_instance ['title_after_up'], $allowed_tags ) );
		
		// font sizes
		$instance ['title_before_size'] = ( int ) $new_instance ['title_before_size'];
		$instance ['title_after_size'] = ( int ) $new_instance ['title_after_size'];
		$instance ['digits_size'] = ( int ) $new_instance ['digits_size'];
		$instance ['labels_size'] = ( int ) $new_instance ['labels_size'];
		
		// elements styles
		$instance ['widget_style'] = esc_html ( $new_instance ['widget_style'] );
		
		$instance ['title_before_style'] = esc_html ( $new_instance ['title_before_style'] );
		$instance ['title_after_style'] = esc_html ( $new_instance ['title_after_style'] );
		
		$instance ['digits_style'] = esc_html ( $new_instance ['digits_style'] );
		$instance ['labels_style'] = esc_html ( $new_instance ['labels_style'] );
		
		$instance ['redirect_url'] = strip_tags ( $new_instance ['redirect_url'] );
		$instance ['click_url'] = strip_tags ( $new_instance ['click_url'] );
		
		// deadline
		if (! empty ( $new_instance ['deadline'] )) {
			try {
				$deadline = new DateTime ( $new_instance ['deadline'] );
				$instance ['deadline'] = $deadline->format ( 'Y-m-d H:i:s' );
			} catch ( Exception $e ) {
				$instance ['deadline'] = '';
			}
		} else {
			$instance ['deadline'] = '';
		}
		
		$instance ['fx_preset'] = esc_html ( $new_instance ['fx_preset'] );
		$instance ['layout_preset'] = esc_html ( $new_instance ['layout_preset'] );
		
		foreach ( array_keys ( self::$defaults ['units'] ) as $unit ) {
			$instance ['units'] [$unit] = ! empty ( $new_instance ['units_' . $unit] ) ? 1 : 0;
		}
		
		$instance ['hide_countup_counter'] = empty ( $new_instance ['hide_countup_counter'] ) ? 0 : 1;
		
		$instance ['import_config'] = strip_tags ( $new_instance ['import_config'] );
		
		list ( $instance ['countdown_limit'], $instance ['countup_limit'] ) = explode ( ':', $new_instance ['counter_modes'] );
		
		// do not allow uplimit -2 (countdown-to-end mode) if current import config is empty
		if (empty ( $instance ['import_config'] ) && $instance ['countup_limit'] == - 2) {
			$instance ['countup_limit'] = - 1;
		}
		// if special "-2" is set as countup limit, we set "countdown_to_end" option and reset the limit
		if ($instance ['countup_limit'] == - 2 && SmartCountdown_Helper::importPluginsEnabled ()) {
			$instance ['countup_limit'] = 0;
			$instance ['countdown_to_end'] = 1;
		} else {
			$instance ['countdown_to_end'] = 0;
		}
		
		return $instance;
	}
	public function form($instance) {
		/*
		 * *** TODO: use apply_filters to sanitize / convert other widget output and form field values!
		 */
		$instance = wp_parse_args ( ( array ) $instance, array (
				'title' => 'Countdown',
				'title_before_down' => '',
				'title_after_down' => '',
				'title_before_up' => '',
				'title_after_up' => '',
				'digits_size' => 36,
				'labels_size' => 10,
				'title_before_size' => 24,
				'title_after_size' => 14,
				'title_before_style' => '',
				'title_after_style' => '',
				'digits_style' => '',
				'labels_style' => '',
				'widget_style' => '',
				'deadline' => '',
				'fx_preset' => 'Sliding_text_fade.xml',
				'layout_preset' => 'sidebar.xml',
				'units' => self::$defaults ['units'],
				'hide_countup_counter' => 0,
				'countdown_limit' => - 1,
				'countup_limit' => - 1,
				'redirect_url' => '',
				'click_url' => '',
				'import_config' => '' 
		) );
		
		$title = strip_tags ( $instance ['title'] );
		
		$allowed_tags = wp_kses_allowed_html ( 'post' );
		
		$title_before_down = wp_kses ( $instance ['title_before_down'], $allowed_tags );
		$title_after_down = wp_kses ( $instance ['title_after_down'], $allowed_tags );
		$title_before_up = wp_kses ( $instance ['title_before_up'], $allowed_tags );
		$title_after_up = wp_kses ( $instance ['title_after_up'], $allowed_tags );
		
		$digits_size = ( int ) $instance ['digits_size'];
		$labels_size = ( int ) $instance ['labels_size'];
		
		$title_before_size = ( int ) $instance ['title_before_size'];
		$title_after_size = ( int ) $instance ['title_after_size'];
		
		$digits_style = strip_tags ( $instance ['digits_style'] );
		$labels_style = strip_tags ( $instance ['labels_style'] );
		
		$title_before_style = strip_tags ( $instance ['title_before_style'] );
		$title_after_style = strip_tags ( $instance ['title_after_style'] );
		
		$widget_style = strip_tags ( $instance ['widget_style'] );
		
		$deadline = ! empty ( $instance ['deadline'] ) ? $instance ['deadline'] : '';
		
		$fx_preset = strip_tags ( $instance ['fx_preset'] );
		$layout_preset = strip_tags ( $instance ['layout_preset'] );
		
		$redirect_url = strip_tags ( $instance ['redirect_url'] );
		$click_url = strip_tags ( $instance ['click_url'] );
		
		$hide_countup_counter = ( int ) $instance ['hide_countup_counter'];
		// if "countdown-to-end" mode enabled and there are import plugins installed we manually select the option here
		$counter_modes = empty ( $instance ['countdown_to_end'] ) || ! SmartCountdown_Helper::importPluginsEnabled () ? ( int ) $instance ['countdown_limit'] . ':' . ( int ) $instance ['countup_limit'] : '-1:-2';
		$import_config = strip_tags ( $instance ['import_config'] );
		
		// In customize preview we must disable datepicker and fall back to a simple text field
		if (is_customize_preview ()) {
			$date_picker_class = 'widefat';
		} else {
			$date_picker_class = 't-datepicker widefat';
		}
		
		?>
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
		name="<?php echo $this->get_field_name('title'); ?>" type="text"
		value="<?php echo esc_attr($title); ?>" />
</p>
<p>
	<label><?php _e('Event date and time:', 'smart-countdown'); ?></label>
	<input id="<?php echo $this->get_field_id('deadline'); ?>"
		name="<?php echo $this->get_field_name('deadline'); ?>" type="text"
		value="<?php echo $deadline; ?>"
		class="<?php echo $date_picker_class; ?>" />
</p>
<?php echo SmartCountdown_Helper::enabledImportConfigs( $this->get_field_id( 'import_config' ), $this->get_field_name( 'import_config' ), $import_config ); ?>
<p>
	<label for="<?php echo $this->get_field_id( 'counter_modes' ); ?>"><?php _e( 'Counter display mode:', 'smart-countdown' ); ?></label>
			<?php
		$mode_options = array (
				'-1:-1' => __ ( 'Auto - both countdown and count up', 'smart-countdown' ),
				// '-1:60' => 'Quick up limit test',
				__ ( 'Only before event (countdown)', 'smart-countdown' ) => array (
						'-1:0' => __ ( 'Countdown - no limit', 'smart-countdown' ),
						'3600:0' => sprintf ( __ ( 'Show counter %s before event', 'smart-countdown' ), sprintf ( _n ( '%d hour', '%d hours', 1, 'smart-countdown' ), 1 ) ),
						'7200:0' => sprintf ( __ ( 'Show counter %s before event', 'smart-countdown' ), sprintf ( _n ( '%d hour', '%d hours', 2, 'smart-countdown' ), 2 ) ),
						'86400:0' => sprintf ( __ ( 'Show counter %s before event', 'smart-countdown' ), sprintf ( _n ( '%d hour', '%d hours', 24, 'smart-countdown' ), 24 ) ),
						'259200:0' => sprintf ( __ ( 'Show counter %s before event', 'smart-countdown' ), sprintf ( _n ( '%d day', '%d days', 3, 'smart-countdown' ), 3 ) ),
						'604800:0' => sprintf ( __ ( 'Show counter %s before event', 'smart-countdown' ), sprintf ( _n ( '%d week', '%d weeks', 1, 'smart-countdown' ), 1 ) ) 
				),
				__ ( 'Only after event (count up)', 'smart-countdown' ) => array (
						'0:-1' => __ ( 'Count up - no limit', 'smart-countdown' ),
						'0:60' => sprintf ( __ ( 'Hide counter %s after event', 'smart-countdown' ), sprintf ( _n ( '%d minute', '%d minutes', 1, 'smart-countdown' ), 1 ) ),
						'0:3600' => sprintf ( __ ( 'Hide counter %s after event', 'smart-countdown' ), sprintf ( _n ( '%d hour', '%d hours', 1, 'smart-countdown' ), 1 ) ),
						'0:86400' => sprintf ( __ ( 'Hide counter %s after event', 'smart-countdown' ), sprintf ( _n ( '%d hour', '%d hours', 24, 'smart-countdown' ), 24 ) ),
						'0:604800' => sprintf ( __ ( 'Hide counter %s after event', 'smart-countdown' ), sprintf ( _n ( '%d week', '%d weeks', 1, 'smart-countdown' ), 1 ) ) 
				) 
		);
		// add "countdown-to-end" option if at least one event import plugin is enabled
		if (SmartCountdown_Helper::importPluginsEnabled ()) {
			$mode_options ['-1:-2'] = __ ( 'Auto + countdown to event end while in progress', 'smart-countdown' );
		}
		
		echo SmartCountdown_Helper::selectInput ( $this->get_field_id ( 'counter_modes' ), $this->get_field_name ( 'counter_modes' ), $counter_modes, array (
				'type' => 'optgroups',
				'options' => $mode_options 
		) );
		?></p>
<?php // title texts are already sanitized by wp_kses ?>
<p>
	<label for="<?php echo $this->get_field_id( 'title_before_down' ); ?>"><?php _e( 'Title before counter for countdown mode', 'smart-countdown' ); ?></label>
	<textarea class="widefat"
		id="<?php echo $this->get_field_id('title_before_down'); ?>"
		name="<?php echo $this->get_field_name( 'title_before_down' ); ?>"><?php echo $title_before_down; ?></textarea>
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'title_after_down' ); ?>"><?php _e( 'Title after counter for countdown mode', 'smart-countdown' ); ?></label>
	<textarea class="widefat"
		id="<?php echo $this->get_field_id( 'title_after_down' ); ?>"
		name="<?php echo $this->get_field_name( 'title_after_down' ); ?>"><?php echo $title_after_down; ?></textarea>
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'title_before_up' ); ?>"><?php _e('Title before counter for count up mode', 'smart-countdown'); ?></label>
	<textarea class="widefat"
		id="<?php echo $this->get_field_id( 'title_before_up' ); ?>"
		name="<?php echo $this->get_field_name('title_before_up'); ?>"><?php echo $title_before_up; ?></textarea>
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'title_after_up' ); ?>"><?php _e( 'Title after counter for count up mode', 'smart-countdown' ); ?></label>
	<textarea class="widefat"
		id="<?php echo $this->get_field_id( 'title_after_up' ); ?>"
		name="<?php echo $this->get_field_name( 'title_after_up' ); ?>"><?php echo $title_after_up; ?></textarea>
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'fx_preset' ); ?>"><?php _e( 'Counter animation profile:', 'smart-countdown' ); ?></label>
			<?php
		
		echo SmartCountdown_Helper::selectInput ( $this->get_field_id ( 'fx_preset' ), $this->get_field_name ( 'fx_preset' ), $fx_preset, array (
				'type' => 'filelist',
				'extension' => 'xml',
				// we must also provide an alternative folder for added profiles, because they have to be stored outside of plugin
				// directory to avoid deletion on plugin update
				'folder' => array (
						dirname ( __FILE__ ) . '/includes/animations',
						dirname ( __FILE__ ) . '/../smart-countdown-animations' 
				) 
		) );
		?></p>
<p>
	<label for="<?php echo $this->get_field_id( 'layout_preset' ); ?>"><?php _e( 'Widget layout preset:', 'smart-countdown' ); ?></label>
			<?php
		
		echo SmartCountdown_Helper::selectInput ( $this->get_field_id ( 'layout_preset' ), $this->get_field_name ( 'layout_preset' ), $layout_preset, array (
				'type' => 'filelist',
				'extension' => 'xml',
				// we must also provide an alternative folder for added layouts, because they have to be stored outside of plugin
				// directory to avoid deletion on plugin update
				'folder' => array (
						dirname ( __FILE__ ) . '/includes/layouts',
						dirname ( __FILE__ ) . '/../smart-countdown-custom-layouts'
				)
		) );
		?></p>
<p>
	<input class="checkbox"
		id="<?php echo $this->get_field_id( 'hide_countup_counter' ); ?>"
		name="<?php echo $this->get_field_name( 'hide_countup_counter' ); ?>"
		type="checkbox"
		<?php echo ($hide_countup_counter ? ' checked' : ''); ?> /> <label
		for="<?php echo $this->get_field_id( 'hide_countup_counter' ); ?>"><?php _e( 'Use titles for count up mode as "Time has arrived" message', 'smart-countdown' ); ?></label>
</p>
<?php echo SmartCountdown_Helper::checkboxesInput( $this, $instance['units'], array( 'legend' => __( 'Display counter units:', 'smart-countdown' ) ) ); ?>
<p>
	<label for="<?php echo $this->get_field_id( 'title_before_size' ); ?>"><?php _e( 'Text before counter font size:', 'smart-countdown' ); ?></label>
			<?php
		
		echo SmartCountdown_Helper::selectInput ( $this->get_field_id ( 'title_before_size' ), $this->get_field_name ( 'title_before_size' ), $title_before_size, array (
				'type' => 'integer',
				'start' => 12,
				'end' => 50,
				'step' => 2 
		) );
		?></p>
<p>
	<label for="<?php echo $this->get_field_id( 'title_after_size' ); ?>"><?php _e( 'Text after counter font size:', 'smart-countdown' ); ?></label>
			<?php
		
		echo SmartCountdown_Helper::selectInput ( $this->get_field_id ( 'title_after_size' ), $this->get_field_name ( 'title_after_size' ), $title_after_size, array (
				'type' => 'integer',
				'start' => 12,
				'end' => 50,
				'step' => 2 
		) );
		?></p>
<p>
	<label for="<?php echo $this->get_field_id( 'digits_size' ); ?>"><?php _e( 'Counter digits size:', 'smart-countdown' ); ?></label>
			<?php
		
		echo SmartCountdown_Helper::selectInput ( $this->get_field_id ( 'digits_size' ), $this->get_field_name ( 'digits_size' ), $digits_size, array (
				'type' => 'integer',
				'start' => 10,
				'end' => 80,
				'step' => 2 
		) );
		?></p>
<p>
	<label for="<?php echo $this->get_field_id( 'labels_size' ); ?>"><?php _e( 'Counter labels size:', 'smart-countdown' ); ?></label>
			<?php
		
		echo SmartCountdown_Helper::selectInput ( $this->get_field_id ( 'labels_size' ), $this->get_field_name ( 'labels_size' ), $labels_size, array (
				'type' => 'integer',
				'start' => 8,
				'end' => 40,
				'step' => 2 
		) );
		?></p>
<p>
	<label for="<?php echo $this->get_field_id( 'click_url' ); ?>"><?php _e( 'Goto URL on widget click (leave this field empty to disable this feature):', 'smart-countdown' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id('click_url'); ?>"
		name="<?php echo $this->get_field_name('click_url'); ?>" type="text"
		value="<?php echo esc_attr($click_url); ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'redirect_url' ); ?>"><?php _e( 'Redirect to URL on contdown zero (leave this field empty to disable automatic redirection):', 'smart-countdown' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'redirect_url' ); ?>"
		name="<?php echo $this->get_field_name( 'redirect_url' ); ?>"
		type="text" value="<?php echo esc_attr( $redirect_url ); ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'widget_style' ); ?>"><?php _e( 'Widget style - CSS rules separated and ended with a semicolon:', 'smart-countdown' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'widget_style' ); ?>"
		name="<?php echo $this->get_field_name( 'widget_style' ); ?>"
		type="text" value="<?php echo esc_attr( $widget_style ); ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'title_before_style' ); ?>"><?php _e( 'Text before counter style - CSS rules separated and ended with a semicolon:', 'smart-countdown' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'title_before_style' ); ?>"
		name="<?php echo $this->get_field_name( 'title_before_style' ); ?>"
		type="text" value="<?php echo esc_attr( $title_before_style ); ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'title_after_style' ); ?>"><?php _e( 'Text after counter style - CSS rules separated and ended with a semicolon:', 'smart-countdown' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'title_after_style' ); ?>"
		name="<?php echo $this->get_field_name( 'title_after_style' ); ?>"
		type="text" value="<?php echo esc_attr( $title_after_style ); ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'digits_style' ); ?>"><?php _e( 'Counter digits style - CSS rules separated and ended with a semicolon:', 'smart-countdown' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'digits_style' ); ?>"
		name="<?php echo $this->get_field_name( 'digits_style' ); ?>"
		type="text" value="<?php echo esc_attr( $digits_style ); ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'labels_style' ); ?>"><?php _e( 'Counter labels style - CSS rules separated and ended with a semicolon:', 'smart-countdown' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'labels_style' ); ?>"
		name="<?php echo $this->get_field_name( 'labels_style' ); ?>"
		type="text" value="<?php echo esc_attr( $labels_style ); ?>" />
</p>
<?php
	}
	public static function admin_scripts($hook) {
		if ($hook == 'widgets.php') {
			wp_enqueue_script ( 'jquery-ui-datepicker' );
			wp_enqueue_script ( 'jquery-ui-slider' );
			
			$plugin_url = plugins_url () . '/' . dirname ( plugin_basename ( __FILE__ ) );
			
			wp_register_script ( 'jquery-ui-timepicker-addon', $plugin_url . '/js/vendor/jquery-ui-timepicker-addon.min.js', array (
					'jquery' 
			) );
			wp_enqueue_script ( 'jquery-ui-timepicker-addon' );
			
			wp_register_script ( 'smartcountdown-admin-script', $plugin_url . '/js/vendor/timepicker.collapse.js', array (
					'jquery',
					'jquery-ui-datepicker',
					'jquery-ui-slider',
					'jquery-ui-timepicker-addon' 
			) );
			wp_enqueue_script ( 'smartcountdown-admin-script' );
			
			wp_register_style ( 'collapse-admin-css', $plugin_url . '/admin/collapse-style.css' );
			wp_enqueue_style ( 'collapse-admin-css' );
			
			wp_register_style ( 'jquery-ui-css', $plugin_url . '/admin/jquery-ui.css' );
			wp_enqueue_style ( 'jquery-ui-css' );
			
			// at the moment we do not add counter script in backend. Later we can implement
			// animations preview in backend.
			// self::counter_scripts();
		}
	}
	public static function counter_scripts() {
		$plugin_url = plugins_url () . '/' . dirname ( plugin_basename ( __FILE__ ) );
		
		/* only required by jQuery animations
		// this script is required for extended animation easing
		wp_register_script ( 'easing-script', $plugin_url . '/js/vendor/jquery-ui-easing.min.js', array (
				'jquery' 
		) );
		wp_enqueue_script ( 'easing-script' );
		*/
		
		// optimized animation library
		wp_register_script ( 'velocity-script', $plugin_url . '/js/vendor/velocity.min.js', array (
				'jquery'
		) );
		wp_enqueue_script ( 'velocity-script' );
		
		wp_register_script ( 'smartcountdown-counter-script', $plugin_url . '/js/smartcountdown.js', array (
				'jquery' 
		) );
		wp_enqueue_script ( 'smartcountdown-counter-script' );
		
		wp_register_style ( 'smartcountdown-counter-style', $plugin_url . '/css/smartcountdown.css' );
		wp_enqueue_style ( 'smartcountdown-counter-style' );
		
		if (is_rtl()) {
			// load rtl styles overloads
			wp_register_style ( 'smartcountdown-counter-style-rtl', $plugin_url . '/css/smartcountdown_rtl.css' );
			wp_enqueue_style ( 'smartcountdown-counter-style-rtl' );
		}
		
		wp_localize_script ( 'smartcountdown-counter-script', 'smartcountdownajax', array (
				'url' => admin_url ( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce ( 'scd_query_next_event' ) 
		) );
		
		$lang_tag = get_locale ();
		$plural_js_file = '/js/plural_js/plural_' . strtolower ( $lang_tag ) . '.js';
		if (file_exists ( dirname ( __FILE__ ) . $plural_js_file )) {
			$plural_js_file = $plugin_url . $plural_js_file;
		} else {
			$plural_js_file = $plugin_url . '/js/plural_js/plural.js';
		}
		
		wp_register_script ( 'smartcountdown-plural-strings', $plural_js_file );
		wp_enqueue_script ( 'smartcountdown-plural-strings' );
		
		// include plural forms localization, we support custom file for certain languages
		$plurals_php_file = '/includes/plural_php/plurals_' . strtolower ( $lang_tag ) . '.php';
		if (file_exists ( dirname ( __FILE__ ) . $plurals_php_file )) {
			include_once ( dirname ( __FILE__ ) . $plurals_php_file );
		} else {
			// use generic file, suitable for most languages
			include_once ( dirname ( __FILE__ ) . '/includes/plural_php/plurals.php' );
		}
	}
	
	/**
	 * Query next event for Smart Countdown.
	 */
	public static function queryNextEvent() {
		$response = array (
				'err_code' => 0,
				'err_msg' => '' 
		);
		
		if (! check_ajax_referer ( 'scd_query_next_event', 'smartcountdown_nonce', false )) {
			$response ['err_code'] = 100;
			$response ['err_msg'] = 'Invalid Token!';
		} else {
			// get current UNIX timestamp to include it to response
			$now_ts_millis = microtime ( true );
			// integer in seconds for event import plugins
			$now_ts = round ( $now_ts_millis );
			
			if (! empty ( $_REQUEST ['id'] )) {
				// this is a widget and we need an ID to find correct settings in the database
				$id = sanitize_key ( $_REQUEST ['id'] );
				if (strpos ( $id, 'smartcountdown-' ) !== 0) {
					$response ['err_code'] = 101;
					$response ['err_msg'] = 'Invalid Request!';
				}
				
				$id = ( int ) substr ( $id, strlen ( 'smartcountdown-' ) );
				
				$widgets = get_option ( 'widget_smartcountdown' );
				// normally the widget is already saved and its data can be retrieved from
				// WP database. The only exception is "customize preview" when a new widget is
				// added. We allow both cases - check if the widget is already saved OR customize
				// preview mode is active
				if (isset ( $widgets [$id] ) || ! empty ( $_REQUEST ['customize_preview'] )) {
					// if widget data is not set we are dealing with a new widget added via
					// customize preview. Just initialize instance to an empty array, later we
					// add all settings required for next event query. The rest of widget options
					// (formats, texts, etc.) will be handled by WP customize preview engine
					$instance = isset ( $widgets [$id] ) ? $widgets [$id] : array ();
					
					// customize preview is a special case: along with widget id we have
					// to interpret some customized setting which cannot be handled by WP
					// customizer - when read from widget database using the given id we
					// get last save settings, not customized ones. Here we replace some
					// settings required for next event query.
					if (! empty ( $_REQUEST ['customize_preview'] )) {
						$instance ['deadline'] = esc_attr ( $_REQUEST ['deadline'] );
						$instance ['import_config'] = esc_attr ( $_REQUEST ['import_config'] );
						$instance ['countdown_to_end'] = ( int ) $_REQUEST ['countdown_to_end'];
						$instance ['countdown_limit'] = ( int ) $_REQUEST ['countdown_limit'];
						$instance ['countup_limit'] = ( int ) $_REQUEST ['countup_limit'];
					}
					
					if (! empty ( $instance ['import_config'] )) {
						// try calling event import plugins
						$instance = apply_filters ( 'smartcountdownfx_get_event', $instance, $now_ts );
						
						if (isset ( $instance ['imported'] )) {
							// at least one import plugin enabled
							if (empty ( $instance ['imported'] )) {
								// no current or future events
								$instance ['deadline'] = '';
							} else {
								$instance = SmartCountdown_Helper::processImportedEvents ( $instance, $now_ts );
							}
						} else {
							// import plugins were desactivated or uninstalled
							$instance ['deadline'] = '';
						}
					} else {
						// no import plugins enabled, get the deadline from widget settings
						$instance = SmartCountdown_Helper::updateDeadlineUTC ( $instance );
						
						// if the deadline in widget settings is too far in past (more than
						// countup_limit difference from now, if countup_limit is set) we
						// have to definitely disable the counter sending empty string as
						// the new deadline
						$deadline = new DateTime ( $instance ['deadline'] );
						if ($instance ['countup_limit'] >= 0 && $now_ts - $deadline->getTimestamp () >= $instance ['countup_limit']) {
							$instance ['deadline'] = '';
						}
					}
					// add instance to response
					$response ['options'] = $instance;
				} else {
					$response ['err_code'] = 101;
					$response ['err_msg'] = 'Invalid Request!';
				}
			} else {
				// this is a call from shortcode counter
				
				$import_config = esc_attr ( $_REQUEST ['import_config'] );
				// for event import to work we need countup limit from request
				$countup_limit = ( int ) $_REQUEST ['countup_limit'];
				if (empty ( $import_config )) {
					try {
						$deadline = esc_attr ( $_REQUEST ['deadline'] );
						
						$deadlineUTC = SmartCountdown_Helper::localDateToUTC ( $deadline );
						if ($countup_limit >= 0 && $now_ts - $deadlineUTC->getTimestamp () >= $countup_limit) {
							// too late to show the counter
							$response ['options'] ['deadline'] = '';
						} else {
							$response ['options'] ['deadline'] = $deadlineUTC->format ( 'c' );
							$response ['options'] ['countup_limit'] = $countup_limit;
						}
					} catch ( Exception $e ) {
						// invalid date in request. TODO: Log error
						$response ['options'] ['deadline'] = '';
					}
				} else {
					$countdown_to_end = ( int ) $_REQUEST ['countdown_to_end'];
					$options ['import_config'] = $import_config;
					$options ['countup_limit'] = $countup_limit;
					$options ['countdown_to_end'] = $countdown_to_end;
					
					$options = apply_filters ( 'smartcountdownfx_get_event', $options, $now_ts );
					if (isset ( $options ['imported'] )) {
						// at least one import plugin enabled
						if (empty ( $options ['imported'] )) {
							// no current or future events
							$options ['deadline'] = '';
						} else {
							$options = SmartCountdown_Helper::processImportedEvents ( $options, $now_ts );
						}
					} else {
						// import plugins were desactivated or uninstalled
						$options ['deadline'] = '';
					}
					
					$response ['options'] = $options;
				}
			}
			// add current now in milis to response
			$response ['options'] ['now'] = round ( $now_ts_millis, 3 ) * 1000;
		}
		
		// clear output buffer to suppress warning and notices
		while ( ob_get_clean () )
			;
		
		echo json_encode ( $response );
		wp_die ();
	}
	public static function short_code($atts, $content = null) {
		$atts = shortcode_atts ( array (
				'id' => '',
				'deadline' => '',
				'before_widget' => '', // *** not sure we need it
				'after_widget' => '', // *** not sure we need it
				'title_before_down' => '',
				'title_before_up' => '',
				'title_after_down' => '',
				'title_after_up' => '',
				'fx_preset' => 'Sliding_text_fade.xml',
				'layout_preset' => 'shortcode_compact.xml',
				'digits_size' => 40,
				'labels_size' => 10,
				'title_before_size' => 20,
				'title_after_size' => 16,
				'units' => '*',
				'mode' => 'auto',
				'hide_countup_counter' => '0',
				'redirect_url' => '',
				'click_url' => '',
				'title_before_style' => '',
				'title_after_style' => '',
				'import_config' => '',
				'digits_style' => '',
				'labels_style' => '',
				
				'widget_style' => '' 
		), $atts, 'smartcountdown' );
		
		/*
		 * Important! Document this. If there is a shortcode with closing tag and content, all other tags on the same page
		 * (at least, preceding ones) MUST be auto-closed or explicilty closed.
		 *
		 * This is a wordpress do_shortcode() limitation (preg_match stuff) that cannot be changed.
		 * We must be aware of this effect.
		 */
		if ($content != '') {
			$allowed_tags = wp_kses_allowed_html ( 'post' );
			
			$title_before_down = array ();
			preg_match ( '/\[title_before_down\]([\s\S]*)\[\/title_before_down\]/', $content, $title_before_down );
			$title_after_down = array ();
			preg_match ( '/\[title_after_down\]([\s\S]*)\[\/title_after_down\]/', $content, $title_after_down );
			$title_before_up = array ();
			preg_match ( '/\[title_before_up\]([\s\S]*)\[\/title_before_up\]/', $content, $title_before_up );
			$title_after_up = array ();
			preg_match ( '/\[title_after_up\]([\s\S]*)\[\/title_after_up\]/', $content, $title_after_up );
			
			if (! empty ( $title_before_down [1] )) {
				$atts ['title_before_down'] = wp_kses ( htmlspecialchars_decode ( $title_before_down [1] ), $allowed_tags );
			}
			if (! empty ( $title_after_down [1] )) {
				$atts ['title_after_down'] = wp_kses ( htmlspecialchars_decode ( $title_after_down [1] ), $allowed_tags );
			}
			if (! empty ( $title_before_up [1] )) {
				$atts ['title_before_up'] = wp_kses ( htmlspecialchars_decode ( $title_before_up [1] ), $allowed_tags );
			}
			if (! empty ( $title_after_up [1] )) {
				$atts ['title_after_up'] = wp_kses ( htmlspecialchars_decode ( $title_after_up [1] ), $allowed_tags );
			}
		}
		
		if (empty ( $atts ['id'] )) {
			// make sure we do not have ids collision
			$rnd = rand ( 1000, 9999 );
			$widgets = get_option ( 'widget_smartcountdown' );
			while ( isset ( $widgets [$rnd] ) ) {
				$rnd = rand ( 1000, 9999 );
			}
			$id = 'smartcountdown-' . $rnd;
			$atts ['id'] = $id;
		}
		// for manually set ids site admin is responsible for ids security
		
		// convert units list to an array
		if (trim ( $atts ['units'] ) == '*') {
			// default units set
			$units = self::$defaults ['units'];
		} elseif (strpos ( $atts ['units'], '-' ) === 0) {
			// negative list (hide units)
			$units_hidden = array_map ( 'trim', explode ( ',', substr ( $atts ['units'], 1 ) ) );
			$units = array (
					'years' => 1,
					'months' => 1,
					'weeks' => 1,
					'days' => 1,
					'hours' => 1,
					'minutes' => 1,
					'seconds' => 1 
			);
			foreach ( $units_hidden as $unit ) {
				// ignore unknown units
				if (isset ( $units [$unit] )) {
					$units [$unit] = 0;
				}
			}
		} else {
			// positive list (show units)
			$units_selected = array_map ( 'trim', explode ( ',', $atts ['units'] ) );
			$units = array (
					'years' => 0,
					'months' => 0,
					'weeks' => 0,
					'days' => 0,
					'hours' => 0,
					'minutes' => 0,
					'seconds' => 0 
			);
			foreach ( $units_selected as $unit ) {
				// ignore unknown units
				if (isset ( $units [$unit] )) {
					$units [$unit] = 1;
				}
			}
		}
		$atts ['units'] = $units;
		
		// By default we activate both countdown and count up without time limits
		$atts ['countdown_limit'] = $atts ['countup_limit'] = - 1;
		$atts ['countdown_to_end'] = 0;
		
		// check direct modes
		if ($atts ['mode'] == 'countdown') {
			// contdown only
			$atts ['countup_limit'] = 0;
		} elseif ($atts ['mode'] == 'countup') {
			// conunt up only
			$atts ['countdown_limit'] = 0;
		} elseif ($atts ['mode'] == 'countdown_to_end') {
			// "contdown to end" mode
			$atts ['countup_limit'] = 0;
			$atts ['countdown_to_end'] = 1;
		} else {
			// fully-qualified limits. Must be written in format: mode="countdown:NNN,countup:MMM", where
			// NNN = seconds to show countdown before event, MMM = seconds elapsed after event when count up
			// is hidden. No spaces are allowed. If the format is not correct, default "auto" mode will
			// take effect. It is possible to use "-1" for no limit
			$matches = array ();
			if (preg_match ( '/countdown:(-?\d+),countup:(-?\d+)/', $atts ['mode'], $matches )) {
				$atts ['countdown_limit'] = $matches [1];
				$atts ['countup_limit'] = $matches [2];
			}
		}
		if ($atts ['countup_limit'] == - 2) {
			// "contdown to end" mode
			$atts ['countup_limit'] = 0;
			$atts ['countdown_to_end'] = 1;
			// we leave custom countdown_limit as is
		}
		
		// We allow "free text" animation profile name. Important: animation preset file
		// name must start from a capital letter!
		if (! empty ( $atts ['fx_preset'] )) {
			$atts ['fx_preset'] = str_replace ( ' ', '_', $atts ['fx_preset'] );
			if (substr ( $atts ['fx_preset'], - 4 ) != '.xml') {
				$atts ['fx_preset'] = $atts ['fx_preset'] . '.xml';
				$atts ['fx_preset'] = ucfirst ( strtolower ( $atts ['fx_preset'] ) );
			}
		}
		
		// *** not sure that a simple settings replication from shortcode to args
		// is the right way...
		$args = array (
				'widget_id' => $atts ['id'],
				'before_widget' => $atts ['before_widget'],
				'after_widget' => $atts ['after_widget'] 
		);
		
		$atts ['shortcode'] = 1;
		$tmp = new SmartCountdown_Widget ( $atts ['id'], __ ( 'Smart Countdown FX' ) );
		
		ob_start ();
		echo $tmp->widget ( $args, $atts );
		$html = ob_get_clean ();
		
		return $html;
	}
}

add_action ( 'widgets_init', 'SmartCountdown_Widget::smartcountdown_widget_init' );
add_shortcode ( 'smartcountdown', 'SmartCountdown_Widget::short_code' );
function smartcountdown_activation_check() {
	if (version_compare ( $GLOBALS ['wp_version'], SCD_MINIMUM_REQUIRED_WP_VERSION, '<' )) {
		load_plugin_textdomain ( 'smart-countdown' );
		
		$message = '<strong>' . sprintf ( esc_html__ ( 'Smart Countdown FX requires WordPress %s or higher.', 'smart-countdown' ), SCD_MINIMUM_REQUIRED_WP_VERSION ) . '</strong>';
		echo $message;
		
		exit ();
	}
}
function smartcountdown_uninstall() {
	delete_option ( 'widget_smartcountdown' );
	delete_site_option ( 'widget_smartcountdown' );
}
register_activation_hook ( __FILE__, 'smartcountdown_activation_check' );
register_uninstall_hook ( __FILE__, 'smartcountdown_uninstall' );
