<?php

/*
Plugin Name: Day Of Week
Plugin URI: https://peachysoftware.com/dayofweek
Description: Show different content for each day of the week.
Version: 1.7.0
Author: Peachy Software LLC
Author URI: https://peachysoftware.com
License: GPLV2

Copyright 2021 Peachy Software LLC (email : support@peachysoftware.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/





$dir = plugin_dir_path( __FILE__ );

//include($dir.'options.php');

function bwdow_options_page_html()
{
	// check user capabilities
	if (!current_user_can('edit_posts')) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?= esc_html(get_admin_page_title()); ?></h1>
		<form action="options.php" method="post">
			<?php
			// output security fields for the registered setting "wporg_options"
			settings_fields('bwdow_options');
			$bwdow_options = get_option('bwdow_options');
			if ( ! isset ( $bwdow_options['monday'] ) ) {
			    $bwdow_options['monday'] = '';
			}
			if ( ! isset ( $bwdow_options['tuesday'] ) ) {
			    $bwdow_options['tuesday'] = '';
			}
			if ( ! isset ( $bwdow_options['wednesday'] ) ) {
			    $bwdow_options['wednesday'] = '';
			}
			if ( ! isset ( $bwdow_options['thursday'] ) ) {
			    $bwdow_options['thursday'] = '';
			}
			if ( ! isset ( $bwdow_options['friday'] ) ) {
			    $bwdow_options['friday'] = '';
			}
			if ( ! isset ( $bwdow_options['saturday'] ) ) {
			    $bwdow_options['saturday'] = '';
			}
			if ( ! isset ( $bwdow_options['sunday'] ) ) {
			    $bwdow_options['sunday'] = '';
			}
			// output setting sections and their fields
			// (sections are registered for "wporg", each field is registered to a specific section)
			do_settings_sections('bwdow');
            $bwdow_settings = array( 'media_buttons' => true, 'textarea_name' => 'bwdow_options[monday]',
                               'textarea_rows' => '10', 'wpautop' => false);
            $bwdow_filter_option = array ( '','','' );
            if ( !isset( $bwdow_options['filter'] ) ) {
                if ( isset($bwdow_options['nested'] ) ) {
                    $bwdow_filter_option[1] = 'selected';
                    $bwdow_options['filter'] = 'dow_shortcode';
                }
            } else {
                switch ( $bwdow_options['filter'] ) {
		            case 'dow_none':
			            $bwdow_filter_option[0] = 'selected';
			            break;
		            case 'dow_shortcode':
			            $bwdow_filter_option[1] = 'selected';
			            break;
		            case 'dow_full':
			            $bwdow_filter_option[2] = 'selected';
			            break;
	            }
            }
            $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
            $serverTimeZone = date_default_timezone_get();
            if (!isset($bwdow_options['timezone'])){
                $bwdow_options['timezone'] = 'UTC';
            }
			?>
            <table>
                <tr>
                    <td valign="top"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/peach100.png?'?>" alt="Image showing Peachy Software logo"></td>
                    <td>
                        <h2>Instructions</h2>
                        <ol>
                            <li>Please ensure the correct timezone is set below. </li>
                            <li>Enter text for each day below</li>
                            <li>In any post or page, use the shortcode <strong><em>[showday]</em></strong> You can now use other plugins shortcodes in each day. Check "Allow Nested Shortcodes" below to enable.</li>
                            <li>You can show any day of the week all the time. Example - <strong><em>[showday day="Mon"]</em></strong>. Please use the three letter abbreviation of the day. Tue Thu etc.</li>
                            <li><strong>NEW </strong>You can now show content for tomorrow (Or yesterday!) Example - <strong><em>[showday day="tomorrow"] [showday day="yesterday"]</em></strong>. You can use "tom" or "tomorrow"</li>
                        </ol>
                        <h3>Widget Support</h3>
                        <ol>
                            <li>To use a widget, look in Widgets and you will see <strong>Day Of Week</strong>. </li>
                            <li>You can add a Title to it and this will be shown above the days content.</li>
                        </ol>
                        <h3>Support & Feedback</h3>
                        <ol>
                            <li>A help guide can be found here. <a href="https://peachysoftware.com/docs/day-of-week-free-version/" target="_blank">Help Documentation</a> </li>
                            <li>If you require technical support please email <a href="mailto:support@peachysoftwarewidgets.com">support@peachysoftware.com</a></li>
                            <li>We would love your feedback, <a href="https://peachysoftware.com/day-of-week-feedback">Day Of Week Feedback/Comments</a></li>
                        </ol>
                        <h3>Day of Week Pro is now available</h3>
                        <p>Offering multiple entries, the ability to use specific posts or pages and premium support.</p>
                        <p><a href="https://peachysoftware.com/dayofweekpro" target="_blank"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/dayofweekpro.png?'?>" alt="Image showing Day of Week Pro now available."></a></p>
                        <p><a href="https://peachysoftware.com/dayofweekpro" target="_blank">For more details</a> on the PRO version.</p>
                        <br/>
                    </td>
                </tr>
                <tr>
                    <td valign="top"><b>Time Zone</b></td>
                    <td>
                        Your server is currently defaulting to <?php echo $serverTimeZone ?>.<br>
                        If you need to change for this plugin, please select below: <br>

                        <select name="bwdow_options[timezone]">
                        <?php
                        foreach ($tzlist as $tz) {
                            $selected = '';
                            if ($bwdow_options['timezone']==$tz){
                                $selected = ' selected';
                            }

                            echo '<option value="'.$tz.'"'.$selected.'>'.$tz.'</option>';
                        }
                        ?>
                        </select>

                    </td>
                </tr>
                <tr>
                    <td><b>Allow Nested Shortcodes</b></td>
                    <td>
                        <p><select name="bwdow_options[filter]">
                                <option value="dow_none" <?php echo $bwdow_filter_option[0]; ?>>None, leave clean</option>
                                <option value="dow_shortcode" <?php echo $bwdow_filter_option[1]; ?>>Nested ShortCodes Only</option>
                                <option value="dow_full" <?php echo $bwdow_filter_option[2]; ?>>Full Filtering</option>
                            </select>
                        Used to process any nested shortcodes or other items such as [embed] etc. <a href="https://peachysoftware.com/docs/day-of-week-free-version#nested"
                                                                                                     target="_blank">Further help.</a>
                        </p>
                        <br/>
                    </td>
                </tr>
            </table>
            <div class="dow_day">
                <h3>Monday</h3>
				<?php echo wp_editor(html_entity_decode(stripcslashes($bwdow_options['monday'])),'bwdowmonday', $bwdow_settings); ?>
            </div>
            <br/>
            <?php $bwdow_settings['textarea_name'] = 'bwdow_options[tuesday]'; ?>
            <div class="dow_day">
                <h3>Tuesday</h3>
				<?php echo wp_editor(html_entity_decode(stripcslashes($bwdow_options['tuesday'])),'bwdowtuesday', $bwdow_settings); ?>
            </div>
            <br/>
            <?php $bwdow_settings['textarea_name'] = 'bwdow_options[wednesday]'; ?>
            <div class="dow_day">
                <h3>Wednesday</h3>
				<?php echo wp_editor(html_entity_decode(stripcslashes($bwdow_options['wednesday'])),'bwdowwednesday', $bwdow_settings); ?>
            </div>
            <br/>
            <?php $bwdow_settings['textarea_name'] = 'bwdow_options[thursday]'; ?>
            <div class="dow_day">
                <h3>Thursday</h3>
				<?php echo wp_editor(html_entity_decode(stripcslashes($bwdow_options['thursday'])),'bwdowthursday', $bwdow_settings); ?>
            </div>
            <br/>
            <?php $bwdow_settings['textarea_name'] = 'bwdow_options[friday]'; ?>
            <div class="dow_day">
                <h3>Friday</h3>
				<?php echo wp_editor(html_entity_decode(stripcslashes($bwdow_options['friday'])),'bwdowfriday', $bwdow_settings); ?>
            </div>
            <br/>
            <?php $bwdow_settings['textarea_name'] = 'bwdow_options[saturday]'; ?>
            <div class="dow_day">
                <h3>Saturday</h3>
				<?php echo wp_editor(html_entity_decode(stripcslashes($bwdow_options['saturday'])),'bwdowsaturday', $bwdow_settings); ?>
            </div>
            <br/>
            <?php $bwdow_settings['textarea_name'] = 'bwdow_options[sunday]'; ?>
            <div class="dow_day">
                <h3>Sunday</h3>
				<?php echo wp_editor(html_entity_decode(stripcslashes($bwdow_options['sunday'])),'bwdowsunday', $bwdow_settings); ?>
            </div>
			<?php
			// output save settings button
			submit_button('Save Entry');
			echo '<p><a href="https://peachysoftware.com/dayofweek" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'images/peachytext.png?' . '" alt="PeachySoftware LLC Logo"></a>'
			?>
            <p>Version 1.7.0</p>

        </form>
	</div>
	<?php
}

function sked_options_page()
{
	add_menu_page(
		'Day of Week',
		'Day of Week',
		'edit_posts',
		'bwdow',
		'bwdow_options_page_html',
		'dashicons-calendar-alt',
		20
	);
}

function showDayShows($bwdow_att)
{
	$bwdow_att = array_change_key_case( (array) $bwdow_att, CASE_LOWER );
	$bwdow_day = false;
    $bwdow_alternate = false;
	if ( array_key_exists('day', $bwdow_att ) ) {
		$bwdow_day  = strtolower( substr( $bwdow_att['day'], 0, 3 ) );
    }
    if ( 'tom' === $bwdow_day || 'yes' === $bwdow_day) {
        $bwdow_alternate = $bwdow_day;
        $bwdow_day = false;
    }
	$bwdow_options = get_option('bwdow_options');
    if ($bwdow_options['timezone'] != '') {
        $bwdow_szTimezone = new DateTimeZone($bwdow_options['timezone']);
    } else {
        $bwdow_szTimezone = new DateTimeZone('America/New_York');
    }
    // Add on for tomorrow or yesterday 10/21/21
    if ( $bwdow_day ) {
	    switch ( $bwdow_day ) {
		    case 'sun':
			    $bwdow = $bwdow_options['sunday'];
			    break;
		    case 'mon':
			    $bwdow = $bwdow_options['monday'];
			    break;
		    case 'tue':
			    $bwdow = $bwdow_options['tuesday'];
			    break;
		    case 'wed':
			    $bwdow = $bwdow_options['wednesday'];
			    break;
		    case 'thu':
			    $bwdow = $bwdow_options['thursday'];
			    break;
		    case 'fri':
			    $bwdow = $bwdow_options['friday'];
			    break;
		    case 'sat':
			    $bwdow = $bwdow_options['saturday'];
			    break;
	    }
    } else {
	    $bwdow_DateTime = new DateTime('now', $bwdow_szTimezone);
	    $bwdow_daycode = $bwdow_DateTime->format('w');
        if ( $bwdow_alternate ) {
            if ( 'tom' === $bwdow_alternate ) {
                $bwdow_daycode += 1;
                if ( 7 === $bwdow_daycode ) {
                    $bwdow_daycode = 0;
                }
            }
            if ( 'yes' === $bwdow_alternate ) {
                $bwdow_daycode -= 1;
                if ( -1 === $bwdow_daycode ) {
                    $bwdow_daycode = 6;
                }
            }
        }
	    switch ($bwdow_daycode) {
		    case 0:
			    $bwdow = $bwdow_options['sunday'];
			    break;
		    case 1:
			    $bwdow = $bwdow_options['monday'];
			    break;
		    case 2:
			    $bwdow = $bwdow_options['tuesday'];
			    break;
		    case 3:
			    $bwdow = $bwdow_options['wednesday'];
			    break;
		    case 4:
			    $bwdow = $bwdow_options['thursday'];
			    break;
		    case 5:
			    $bwdow = $bwdow_options['friday'];
			    break;
		    case 6:
			    $bwdow = $bwdow_options['saturday'];
			    break;
	    }
    }
    $bwdow_processed = '';
    switch ( $bwdow_options['filter'] ) {
        case 'dow_none':
            $bwdow_processed = $bwdow;
            break;
        case 'dow_shortcode':
            $bwdow_processed = do_shortcode( $bwdow );
            break;
        case 'dow_full':
            $bwdow_processed = apply_filters( 'the_content', $bwdow );
    }
    return $bwdow_processed;
}

function bwdow_register_settings()
{
    register_setting('bwdow_options','bwdow_options','bwdow_sanitize');
}

function bwdow_sanitize ($input) {
    $input['monday'] = wp_kses_post($input['monday']);
    $input['tuesday'] = wp_kses_post($input['tuesday']);
    $input['wednesday'] = wp_kses_post($input['wednesday']);
    $input['thursday'] = wp_kses_post($input['thursday']);
    $input['friday'] = wp_kses_post($input['friday']);
    $input['saturday'] = wp_kses_post($input['saturday']);
    $input['sunday'] = wp_kses_post($input['sunday']);
    return $input;

}

function bwdow_load_extra_cssjs() {
	wp_enqueue_style( 'dayofweek', plugin_dir_url( __FILE__ ) . 'css/ps_dayofweek.css' );
}


class dayOfWeek_Widget extends WP_Widget {
    public function __construct()
    {
        parent::__construct('dayofweek_widget', 'Day Of Week',
                            array('description' => 'Shows content from the Day Of Week plugin')
            );
    }

    public $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );

    public function widget( $args, $instance ) {

        $bwdow_widget_att = array();
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        echo '<div class="textwidget">';

        echo showDayShows($bwdow_widget_att);

        echo '</div>';

        echo $args['after_widget'];

    }

    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = '';
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }
}


add_action('admin_menu', 'sked_options_page');
add_shortcode('showday','showDayShows');
add_action('admin_init', 'bwdow_register_settings');
add_action( 'widgets_init', function() { register_widget( 'dayOfWeek_Widget' ); } );
add_action( 'admin_enqueue_scripts', 'bwdow_load_extra_cssjs' );

?>
