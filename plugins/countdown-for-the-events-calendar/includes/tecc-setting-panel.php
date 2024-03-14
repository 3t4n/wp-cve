<?php

add_action( 'admin_menu', 'tecc_add_admin_menu', 50 );
add_action( 'admin_init', 'tecc_settings_init' );
add_action( 'admin_head', 'tecc_enqueue_color_picker' );

function tecc_enqueue_color_picker() {
	$screen = get_current_screen();
	if ( $screen->id != 'events-addons_page_countdown_for_the_events_calendar' ) {
		return;
	}
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'tecc-b-color-picker-script', TECC_JS_DIR . '/jquery-custom.js', array( 'wp-color-picker' ), false, true );
	wp_enqueue_script( 'setting-panel-js', TECC_JS_DIR . '/settings-panel.js', array( 'jquery' ), false, true );

}


function tecc_add_admin_menu() {
	add_submenu_page( 'cool-plugins-events-addon', 'Countdown for the events calendar', 'Event Countdown', 'manage_options', 'countdown_for_the_events_calendar', 'tecc_options_page', 50 );
}


function tecc_settings_init() {

	register_setting( 'pluginPage', 'tecc_settings' );
	add_settings_section(
		'tecc_pluginPage_section',
		__( 'Create Shortcode for the Event countdown using below mentioned settings', 'tecc1' ),
		'tecc_settings_section_callback',
		'pluginPage'
	);
	add_settings_field(
		'autostart-next-countdown',
		__( 'Autostart countdown of next upcoming event', 'tecc1' ),
		'tecc_select_field_8_render',
		'pluginPage',
		'tecc_pluginPage_section',
		array( 'class' => 'tecc-autostart' )
	);

	add_settings_field(
		'autostart-future-countdown',
		__( 'Autostart countdown of next future event', 'tecc1' ),
		'tecc_select_field_11_render',
		'pluginPage',
		'tecc_pluginPage_section',
		array( 'class' => 'tecc-autostart-future' )
	);

	add_settings_field(
		'future-events-list',
		__( 'Select Events for autostart Countdown', 'tecc1' ),
		'tecc_select_field_7_render',
		'pluginPage',
		'tecc_pluginPage_section',
		array( 'class' => 'tecc-events-list' )
	);

	add_settings_field(
		'event_id',
		__( 'Select an Event', 'tecc1' ),
		'tecc_select_field_0_render',
		'pluginPage',
		'tecc_pluginPage_section',
		array( 'class' => 'tecc-single-event' )
	);

	add_settings_field(
		'backgroundcolor',
		__( 'Countdown Background Color', 'tecc1' ),
		'tecc_text_field_1_render',
		'pluginPage',
		'tecc_pluginPage_section'
	);

	add_settings_field(
		'font-color',
		__( 'Countdown Font Color', 'tecc1' ),
		'tecc_text_field_2_render',
		'pluginPage',
		'tecc_pluginPage_section'
	);

	add_settings_field(
		'show-seconds',
		__( 'Show Seconds in Countdown', 'tecc1' ),
		'tecc_select_field_3_render',
		'pluginPage',
		'tecc_pluginPage_section'
	);
	add_settings_field(
		'show-image',
		__( 'Show Image in Countdown', 'tecc1' ),
		'tecc_select_field_12_render',
		'pluginPage',
		'tecc_pluginPage_section'
	);

	add_settings_field(
		'size',
		__( 'Select Countdown Size', 'tecc1' ),
		'tecc_select_field_4_render',
		'pluginPage',
		'tecc_pluginPage_section'
	);

	add_settings_field(
		'event-start',
		__( 'Display Text When Event Starts', 'tecc1' ),
		'tecc_text_field_5_render',
		'pluginPage',
		'tecc_pluginPage_section',
		array( 'class' => 'tecc-start-text' )
	);

	add_settings_field(
		'event-end',
		__( 'Display Text When Event Ends', 'tecc1' ),
		'tecc_text_field_6_render',
		'pluginPage',
		'tecc_pluginPage_section',
		array( 'class' => 'tecc-end-text' )
	);

	add_settings_field(
		'autostart-text',
		__( 'Display Text When Event Starts (Default is "Event Starts refresh page to see next upcoming event") ', 'tecc1' ),
		'tecc_text_field_10_render',
		'pluginPage',
		'tecc_pluginPage_section',
		array( 'class' => 'tecc-autostart-text' )
	);

	add_settings_field(
		'main-title',
		__( 'Main Title (Default is "Next Upcoming Event")', 'tecc1' ),
		'tecc_text_field_9_render',
		'pluginPage',
		'tecc_pluginPage_section'
	);
	add_settings_field(
		'main-title',
		__( 'Main Title (Default is "Next Upcoming Event")', 'tecc' ),
		'tecc_text_field_9_render',
		'pluginPage',
		'tecc_pluginPage_section'
	);

}


function tecc_select_field_0_render() {

	$options = get_option( 'tecc_settings' );
	$events  = tribe_get_events(
		array(
			'posts_per_page' => -1,
			'post_type'      => 'tribe_events',
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => '_EventStartDate',
					'value'   => current_time( 'Y-m-d H:i:s' ),
					'compare' => '>',
					'type'    => 'DATETIME',
				),
			),
		)
	);
	?>
	<select name='tecc_settings[event_id]'>    
		<?php
		$saved_event = isset( $options['event_id'] ) ? $options['event_id'] : '';
		if ( is_array( $events ) && array_filter( $events ) != null ) {
			foreach ( $events as $event ) {
				?>
				 <option value="<?php echo esc_attr( $event->ID ); ?>"<?php selected( $saved_event, $event->ID ); ?>><?php echo esc_html( $event->post_title ); ?></option>
				<?php
			}
		} else {
			?>
			<option value="0"><?php esc_html_e( 'No Future Event found.', 'tecc1' ); ?></option>
			<?php
		}
		?>
		 
	</select>
	<?php
}


function tecc_text_field_1_render() {

	$options = get_option( 'tecc_settings' );
	?>
	<input type='text' name='tecc_settings[backgroundcolor]' value="<?php echo isset( $options['backgroundcolor'] ) ? esc_html( $options['backgroundcolor'] ) : '#2a86f7'; ?>" class="wp-color-picker-field" data-default-color ="#4395cb">
	<?php
}


function tecc_text_field_2_render() {

	$options = get_option( 'tecc_settings' );
	?>
	<input type='text' name='tecc_settings[font-color]' value="<?php echo isset( $options['font-color'] ) ? esc_html( $options['font-color'] ) : '#ffffff'; ?>" class="wp-color-picker-field" data-default-color ="#ffffff">
	<?php

}


function tecc_select_field_3_render() {

	$options       = get_option( 'tecc_settings' );
	 $show_seconds = isset( $options['show-seconds'] ) ? $options['show-seconds'] : 'yes';
	?>
	<select name='tecc_settings[show-seconds]'>
	
		<option value="yes" <?php selected( $show_seconds, 'yes' ); ?> >Yes</option>
		<option value="no" <?php selected( $show_seconds, 'no' ); ?> >No</option>
	</select>
	<?php

}

function tecc_select_field_12_render() {

	$options       = get_option( 'tecc_settings' );
	 $show_image = isset( $options['show-image'] ) ? $options['show-image'] : 'no';
	?>
	<select name='tecc_settings[show-image]'>
	
		<option value="yes" <?php selected( $show_image, 'yes' ); ?> >Yes</option>
		<option value="no" <?php selected( $show_image, 'no' ); ?> >No</option>
	</select>
	<?php

}


function tecc_select_field_4_render() {

	$options = get_option( 'tecc_settings' );
	$size    = isset( $options['size'] ) ? $options['size'] : 'medium';
	?>
	<select name='tecc_settings[size]'>
		<option value="large" <?php selected( $size, 'large' ); ?>>Large</option>
		<option value="medium" <?php selected( $size, 'medium' ); ?>>Medium</option>
		<option value="small" <?php selected( $size, 'small' ); ?>>Small</option>
	</select>
	<?php

}


function tecc_text_field_5_render() {

	$options = get_option( 'tecc_settings' );
	printf(
		'<input type="text" name="tecc_settings[event-start]" value="%s" />',
		isset( $options['event-start'] ) ? esc_attr( $options['event-start'] ) : ''
	);
}

function tecc_text_field_6_render() {
	$options = get_option( 'tecc_settings' );
	printf(
		'<input type="text" name="tecc_settings[event-end]" value="%s" />',
		isset( $options['event-end'] ) ? esc_attr( $options['event-end'] ) : ''
	);
}

function tecc_select_field_8_render() {

	$options   = get_option( 'tecc_settings' );
	$autostart = isset( $options['autostart-next-countdown'] ) ? $options['autostart-next-countdown'] : 'no';
	?>
	<select name='tecc_settings[autostart-next-countdown]'>
		<option value="no" <?php selected( $autostart, 'no' ); ?>>No</option>
		<option value="yes"  <?php selected( $autostart, 'yes' ); ?>>Yes</option>
	</select>
	<?php

}

function tecc_select_field_11_render() {

	$options   = get_option( 'tecc_settings' );
	$autostart = isset( $options['autostart-future-countdown'] ) ? $options['autostart-future-countdown'] : 'no';
	?>
	<select name='tecc_settings[autostart-future-countdown]'>
		<option value="no" <?php selected( $autostart, 'no' ); ?>>No</option>
		<option value="yes"  <?php selected( $autostart, 'yes' ); ?>>Yes</option>
	</select>
	<?php

}

function autostart() {
	$options   = get_option( 'tecc_settings' );
	$autostart = isset( $options['autostart-next-countdown'] ) ? $options['autostart-next-countdown'] : 'no';
	return $autostart;
}

function tecc_select_field_7_render() {

	$options = get_option( 'tecc_settings' );
	$events  = tribe_get_events(
		array(
			'posts_per_page' => -1,
			'post_type'      => 'tribe_events',
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => '_EventStartDate',
					'value'   => current_time( 'Y-m-d H:i:s' ),
					'compare' => '>',
					'type'    => 'DATETIME',
				),
			),
		)
	);

	$saved_event = isset( $options['event_id'] ) ? $options['event_id'] : '';
	if ( is_array( $events ) && array_filter( $events ) != null ) {
		?>
		<ul class="main">
			<li><input type="checkbox" id="select_all" /> Select/Deselect all</li>
			<ul>		
				<?php
				foreach ( $events as $event ) {
					$checked         = '';
					$selected_events = isset( $options['future-events-list'] ) && ! empty( $options['future-events-list'] ) ? $options['future-events-list'] : '';
					if ( is_array( $selected_events ) ) {
						if ( in_array( $event->ID, $selected_events ) ) {
							$checked = 'checked';
						}
					}
					?>
					<li>
						<input class="tecc-checkbox" type='checkbox' name='tecc_settings[future-events-list][]' value="<?php echo esc_attr( $event->ID ); ?>" <?php echo esc_html( $checked ); ?> ><label><?php echo esc_html( $event->post_title ); ?></label>
					</li>
					<?php
				}
				?>
			</ul>
		</ul>	
		<?php
	} else {
		?>
		<option value="0"><?php esc_html_e( 'No Future Event found.', 'tecc1' ); ?></option>
		<?php
	}
	?>
	 
	<?php
}

function tecc_text_field_9_render() {
	$options = get_option( 'tecc_settings' );
	printf(
		'<input type="text" name="tecc_settings[main-title]" value="%s" />',
		isset( $options['main-title'] ) ? esc_attr( $options['main-title'] ) : ''
	);
}

function tecc_text_field_10_render() {
	$options = get_option( 'tecc_settings' );
	printf(
		'<input type="text" name="tecc_settings[autostart-text]" value="%s" />',
		isset( $options['autostart-text'] ) ? esc_attr( $options['autostart-text'] ) : ''
	);
}


function tecc_settings_section_callback() {
	echo '<h3>' . esc_html__( 'Countdown Settings', 'tecc1' ) . '</h3>';
}


function tecc_options_page() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages
	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'wporg_messages', 'wporg_message', __( 'Shortcode generated', 'wporg' ), 'updated' );
		// show error/update messages
		settings_errors( 'wporg_messages' );
	}
	?>

	 <div class="wrap tecc-from-wrapper">
		 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>        
		<form action='options.php' method='post' class="tecc-form">
			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button( 'Generate Shortcode' );
			?>
		</form>
		<div class="tecc-shortcode-wrapper">
		<?php
		if ( isset( $_GET['settings-updated'] ) ) {
			$options = get_option( 'tecc_settings' );
			$b       = 0;
			$k       = isset( $options['future-events-list'] ) && ! empty( $options['future-events-list'] ) ? $options['future-events-list'] : '';
			if ( $k ) {
				foreach ( $k as $eventID ) {
					if ( $b <= 0 ) {
						$k = $eventID;
					} else {
						$k .= ',' . $eventID;

					}
					$b++;
				}
			}
			if ( isset( $options['event_id'] ) && ! empty( $options['event_id'] ) && $options['event_id'] != 0 ) {
				$dynamic_attr  = '';
				$dynamic_attr .= "[events-calendar-countdown id=\"{$options['event_id']}\" backgroundcolor=\"{$options['backgroundcolor']}\" font-color=\"{$options['font-color']}\" show-seconds=\"{$options['show-seconds']}\" show-image=\"{$options['show-image']}\" size=\"{$options['size']}\" event-start=\"{$options['event-start']}\" event-end=\"{$options['event-end']}\" autostart-next-countdown=\"{$options['autostart-next-countdown']}\" autostart-text=\"{$options['autostart-text']}\" autostart-future-countdown=\"{$options['autostart-future-countdown']}\" future-events-list=\"{$k}\" main-title=\"{$options['main-title']}\"";
				$dynamic_attr .= ']';

				echo '<h3>' . esc_html__( 'Shortcode Preview', 'tecc1' ) . '</h3>';
				echo do_shortcode( $dynamic_attr );
				$prefix = '_tec_';
				echo '<h2>' . esc_html__( 'Countdown for the events calendar Shortcode :', 'tecc1' ) . '</h2>';
				echo ' <p style="font-size:18px">Paste this shortcode anywhere in page where you want to display Event Countdown
	            </p>';
				echo '<code>';
				  echo wp_kses_post( htmlentities( $dynamic_attr ) );
				echo '</code>';

			} else {
				echo '<h3 style="color:red">' . esc_html__( 'There is no upcoming event. Please add atleast one upcoming event to generate countdown.', 'tecc1' ) . '</h3>';
			}
		}
		?>
		</div>
	</div>

	
	<?php
}

