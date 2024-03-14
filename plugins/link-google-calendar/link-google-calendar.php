<?php
/** Link Google Calendar
Plugin Name: Link Google Calendar
Description: A plugin that allows administrator to set Google Calendar embedded link in admin backend and use shortcode
to place on a page, post or sidebar.
Version: 2.0.0
Author: Darren Ladner
Author URI: https://www.hyperdrivedesigns.com
Requires at least: 4.0
Text Domain: link-google-calendar
Domain Path: /languages
*/

class LinkGoogleCalendar {

	public function __construct() {

		if (is_admin()) {
			add_action( 'admin_menu', array($this, 'link_google_calendar_menu' ));
			add_action( 'admin_init', array($this, 'link_google_calendar_register_settings' ));

			add_action( 'admin_notices', array($this, 'link_google_calendar_admin_notice' ));
			add_action( 'admin_init', array($this, 'link_google_calendar_nag_ignore' ));
		}
		else
		{
			add_shortcode( 'lgc', array( $this,'link_google_calendar_section' ) );
			add_shortcode( 'lgc_1', array( $this,'link_google_calendar_section_one' ) );
			add_shortcode( 'lgc_2', array( $this,'link_google_calendar_section_two' ) );
			add_shortcode( 'lgc_3', array( $this,'link_google_calendar_section_three' ) );
			add_shortcode( 'lgc_4', array( $this,'link_google_calendar_section_four' ) );
			add_shortcode( 'lgc_5', array( $this,'link_google_calendar_section_five' ) );
		}
	}

	public function link_google_calendar_admin_notice() {
		global $current_user ;
	  $user_id = $current_user->ID;
		global $pagenow;
    if ( $pagenow == 'plugins.php' )
		{
		  /* Check that the user hasn't already clicked to ignore the message */
			if ( ! get_user_meta($user_id, 'link_google_calendar_ignore_notice') )
			{
		        echo '<div class="updated"><p>';
		        printf(__('You have updated the Link Google Calendar plugin. We made some changes with the new features. Be sure to read over the
						new documentation located <a href="https://hyperdrivedesigns.com/link-google-calendar-update-documentation/">here</a> as there is
						a new settings page and new savings options. | <a href="%1$s">Hide Notice</a>'), '?link_google_calendar_nag_ignore=0');
		        echo "</p></div>";
			}
		}
	}

	public function link_google_calendar_nag_ignore() {
		global $current_user;
	  $user_id = $current_user->ID;
	  /* If user clicks to ignore the notice, add that to their user meta */
	  if ( isset($_GET['link_google_calendar_nag_ignore']) && '0' == $_GET['link_google_calendar_nag_ignore'] )
		{
	  		add_user_meta($user_id, 'link_google_calendar_ignore_notice', 'true', true);
		}
	}

	public function link_google_calendar_section() {
			$output = '';
			$output .= '<div align="center">';
		    $output .= get_option('link_google_calendar_textarea');
		    $output .= '</div>';
		    return $output;
	}

	public function link_google_calendar_section_one() {
			$output = '';
			$output .= '<div align="center">';
		    $output .= get_option('link_google_calendar_textarea_1');
		    $output .= '</div>';
		    return $output;
	}

	public function link_google_calendar_section_two() {
			$output = '';
			$output .= '<div align="center">';
		    $output .= get_option('link_google_calendar_textarea_2');
		    $output .= '</div>';
		    return $output;
	}

	public function link_google_calendar_section_three() {
			$output = '';
			$output .= '<div align="center">';
		    $output .= get_option('link_google_calendar_textarea_3');
		    $output .= '</div>';
		    return $output;
	}

	public function link_google_calendar_section_four() {
			$output = '';
			$output .= '<div align="center">';
		    $output .= get_option('link_google_calendar_textarea_4');
		    $output .= '</div>';
		    return $output;
	}

	public function link_google_calendar_section_five() {
			$output = '';
			$output .= '<div align="center">';
		    $output .= get_option('link_google_calendar_textarea_5');
		    $output .= '</div>';
		    return $output;
	}

	public function link_google_calendar_menu() {
		add_menu_page('Link Google Calendar Options', 'Link Google Calendar Options', 'manage_options', 'link-google-calendar-options', array($this, 'link_google_calendar_page'), 'dashicons-calendar', 90);

		add_submenu_page('link-google-calendar-options', 'Number of Calendars', 'Number of Calendars', 'manage_options', 'link-google-number-of-calendar-options' , array($this, 'link_google_number_of_calendars_page'));

	}

	public function link_google_calendar_register_settings() {

		register_setting('link-google-calendar-settings-group','link_google_calendar_textarea_1');
		register_setting('link-google-calendar-settings-group','link_google_calendar_textarea_2');
		register_setting('link-google-calendar-settings-group','link_google_calendar_textarea_3');
		register_setting('link-google-calendar-settings-group','link_google_calendar_textarea_4');
		register_setting('link-google-calendar-settings-group','link_google_calendar_textarea_5');

		register_setting('link-google-calendar-settings-group','num_of_calendars');
	}

	public function link_google_calendar_page() {
		if('POST' == $_SERVER['REQUEST_METHOD'])
		{
			if (!empty($_POST['calendar-submit']))
			{
				$link_google_calendar_textarea_1 = $_POST['link_google_calendar_textarea_1'];
				$link_google_calendar_textarea_2 = $_POST['link_google_calendar_textarea_2'];
				$link_google_calendar_textarea_3 = $_POST['link_google_calendar_textarea_3'];
				$link_google_calendar_textarea_4 = $_POST['link_google_calendar_textarea_4'];
				$link_google_calendar_textarea_5 = $_POST['link_google_calendar_textarea_5'];
				$num_of_calendars = $_POST['num_of_calendars'];


    			update_option('link_google_calendar_textarea_1', $link_google_calendar_textarea_1);
    			update_option('link_google_calendar_textarea_2', $link_google_calendar_textarea_2);
    			update_option('link_google_calendar_textarea_3', $link_google_calendar_textarea_3);
    			update_option('link_google_calendar_textarea_4', $link_google_calendar_textarea_4);
    			update_option('link_google_calendar_textarea_5', $link_google_calendar_textarea_5);
    			update_option('num_of_calendars', $num_of_calendars);
    		}

    		if (!empty($_POST['reset-submit']))
    		{

    			update_option('link_google_calendar_textarea_1', '');
    			update_option('link_google_calendar_textarea_2', '');
    			update_option('link_google_calendar_textarea_3', '');
    			update_option('link_google_calendar_textarea_4', '');
    			update_option('link_google_calendar_textarea_5', '');
    			update_option('num_of_calendars', '');

    		}
		}
		wp_nonce_field('link_google_calendar_options_nonce', 'link_google_calendar_nonce_field');
	  	?>
		<div class="gcl-admin-section">
			<div class="gcl-logo-section" style="background: #0074a2;color: #fff;border: 2px solid #fff;padding: 2em 0">
				<h1 style="color:#fff;padding-left: 10px;">Link Google Calendar Settings</h1>
			</div>
			<style>
			.notice-success {
				width: 25%;
			}
			</style>
			<div class="gcl-admin-body-section">
				<form id="optionsForm" method="post" action="options.php">
	    		<?php settings_fields('link-google-calendar-settings-group');
				?>
	    		<table class="form-table">
						<tr valign="top">
	    				<th>
								<span class="boldText">Link Google Calendar</span>
							</th>
	    			 	<th>
								<h5>Number of Calendars: <?php echo get_option( 'num_of_calendars' ); ?></h5>
						 </th>
					 </tr>
	    			<?php

	    			$num_of_calendars = get_option('num_of_calendars');
	    			for( $calendars = 1; $calendars <= $num_of_calendars; $calendars++)
	    			{

	    			?>
					    <tr valign="top">
					       <td>
					       <h5>Calendar Shortcode:</h5>
					       <div class="notice notice-success">
					       	[lgc_<?php echo $calendars; ?>]
					       </div>
					       </td>
					    </tr>
					    <tr>
					    	<td>
					        <textarea type="text" id="link_google_calendar_textarea_<?php echo $calendars; ?>" rows="10" cols="80" name="link_google_calendar_textarea_<?php echo $calendars; ?>">
							<?php echo esc_html( get_option( "link_google_calendar_textarea_".$calendars ) ); ?>
					        </textarea>
					        <input type="hidden" id="num_of_calendars" name="num_of_calendars" value="<?php echo $num_of_calendars; ?>" />
					       </td>

					    </tr>
					<?php
					}
					?>
				    <tr valign="top">
				     	<td>
				       		<p>Options: Copy your Google Calendar embedded link from your Google Calendars account and paste into the textarea.
				       			Then use the shortcode for that calendar to place the calendar in a page or post. Use the shortcode that is
				       			displayed above the particular calendar input settings box. For example, the first calendar will use the shortcode [lgc_1].
				       		</p>
									<?php
									$num_of_calendars = get_option('num_of_calendars');
									if ($num_of_calendars == '')
									{
									?>
									<p class="notice notice-error">If you are seeing this message and not seeing any input textareas, then you have not set the
										number of calendars yet. Go to the Number of Calendars link and set the number of calendars you would like to use on your
										website and then come back to this page and you should see the Number of Calendars input textareas.
									</p>
									<?php
									}
									?>
				        </td>
				    </tr>
				    <tr valign="top">
				       <td>
				        <p class="submit">
				         <input type="submit" class="button-primary" name="calendar-submit" value="<?php _e('Save Changes'); ?>" />
				        </p>
				       </td>
				    </tr>
	    		</table>
	   			</form>
	   			<hr>
	   			<div class="notice notice-error">
					<p>WARNING: The Reset Calendars button will reset all calendars.</p>
				</div>
	   			<form id="resetOptionsForm" method="post" action="options.php">
	    		<?php settings_fields('link-google-calendar-settings-group');
				?>
				<p class="submit">
		        	<input type="submit" class="button-primary" name="reset-submit" value="<?php _e('Reset Calendars'); ?>" />
		        </p>
				</form>
			</div>
		</div>
	 <?php
	}

	function link_google_number_of_calendars_page() {
		if('POST' == $_SERVER['REQUEST_METHOD'])
		{
			$num_of_calendars = $_POST['num_of_calendars'];

    		update_option('num_of_calendars', $num_of_calendars);
		}
	  	?>
		<div class="gcl-admin-section">
			<div class="gcl-logo-section" style="background: #0074a2;color: #fff;border: 2px solid #fff;padding: 2em 0">
				<h1 style="color:#fff;padding-left: 10px;">Link Google Calendar Settings</h1>
			</div>
			<style>
			.notice-error {
				width: 40%;
			}
			</style>
			<div class="gcl-admin-body-section">
				<form id="optionsForm" method="post" action="options.php">
	    		<?php settings_fields('link-google-calendar-settings-group');
				?>
	    		<table class="form-table">
	    			<tr valign="top">
	    				<th scope="row"><span class="boldText">Link Google Calendar Link</span></th>
	    			</tr>
	    			<tr>
	    				<td>
	    					<div class="notice notice-error">
	    						<p>WARNING: If you change the number of calendars, it will reset all calendars.</p>
	    					</div>
	    				</td>
	    			</tr>
	    			<tr>
	    				<td>
              	<select id="num_of_calendars" name="num_of_calendars" class="form-control">
              		<option value="<?php echo get_option('num_of_calendars'); ?>"><?php echo get_option('num_of_calendars'); ?></option>
              		<option value="1">One</option>
		    					<option value="2">Two</option>
		    					<option value="3">Three</option>
		    					<option value="4">Four</option>
		    					<option value="5">Five</option>
                </select>
	    				</td>
	    			</tr>
				    <tr valign="top">
				     	<td>
				       		<p>Options: Select the number of calendars you would like to use.
				       		</p>
				        </td>
				    </tr>
				    <tr valign="top">
				       <td>
				        <p class="submit">
				         <input type="submit" class="button-primary" name="Submit" value="<?php _e('Save Changes'); ?>" />
				        </p>
				       </td>
				    </tr>
	    		</table>
	   			</form>
			</div>
		</div>
	 <?php
	}
}

$LinkGoogleCalendar = new LinkGoogleCalendar;
