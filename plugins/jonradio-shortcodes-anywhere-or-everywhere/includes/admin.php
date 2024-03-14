<?php

/*	Exit if .php file accessed directly
*/
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'jr_saoe_admin_menu' );

/**
* Add Admin Menu item for plugin
* 
* Plugin needs its own Page in the Settings section of the Admin menu.
*
*/
function jr_saoe_admin_menu() {
	/*  Add Settings Page for this Plugin
	*/
	global $jr_saoe_plugin_data;
	add_options_page( $jr_saoe_plugin_data['Name'], 'Shortcodes Anywhere or Everywhere', 'manage_options', 'jr_saoe_settings', 'jr_saoe_settings_page' );
}

/**
 * Settings page for plugin
 * 
 * Display and Process Settings page for this plugin.
 *
 */
function jr_saoe_settings_page() {
	global $jr_saoe_plugin_data;
	
	add_thickbox();
	echo '<div class="wrap">';
	echo '<h2>' . $jr_saoe_plugin_data['Name'] . '</h2>';
	echo '<form action="options.php" method="POST">';
	
	//	Plugin Settings are displayed and entered here:
	settings_fields( 'jr_saoe_settings' );
	do_settings_sections( 'jr_saoe_settings_page' );
	?>
	<p>
	<input name="save" type="submit" value="Save Changes" class="button-primary" />
	</p>
	</form>
	<hr />
	<h3>
	Need Help?
	</h3>
	<p>
	Need help with this plugin?
Support has moved to the ZATZLabs site and is no longer provided on the WordPress.org forums. Please visit the new <A HREF=“http://zatzlabs.com/forums/“>ZATZLab Forums</a>. If you need a timely reply from the developer, please <a href=“http://zatzlabs.com/submit-ticket/“>open a ticket</a>.
	</p>
	<p>
	Looking for a Shortcode that provides an always updated four digit value for the Current Year?
	Or a Shortcode for the &copy; Copyright symbol?
	Check out the <a href="http://wordpress.org/plugins/jonradio-current-year-and-copyright-shortcodes/">Current Year and Copyright Shortcodes</a>
	plugin.
	</p>
	<p>
	For information on other jonradio plugins,
	including Contact and Donation information,
	<a href="http://zatzlabs.com/plugins/">click here</a>.
	</p>
	<h3>
	Want to Help?
	</h3>
	<p>
	As well as <a href="http://zatzlabs.com/plugins/">Donations</a>,
	you can also help by 
	<a href="http://wordpress.org/support/view/plugin-reviews/jonradio-shortcodes-anywhere-or-everywhere">Reviewing this plugin</a> 
	for the WordPress Plugin Directory,
	and telling other people that it works for your particular combination of Plugin version and WordPress version
	in the Compability section of the
	<a href="http://wordpress.org/plugins/jonradio-shortcodes-anywhere-or-everywhere/">WordPress Directory entry for this plugin</a>.
	</p>
	<?php
}

add_action( 'admin_init', 'jr_saoe_admin_init' );

/**
 * Register and define the settings
 * 
 * Everything to be stored and/or can be set by the user
 *
 */
function jr_saoe_admin_init() {
	register_setting( 'jr_saoe_settings', 'jr_saoe_settings', 'jr_saoe_validate_settings' );
	add_settings_section( 
		'jr_saoe_overview_section', 
		'Overview', 
		'jr_saoe_overview_expl', 
		'jr_saoe_settings_page' 
	);
	add_settings_section( 
		'jr_saoe_where_section', 
		'Where?', 
		'jr_saoe_where_expl', 
		'jr_saoe_settings_page' 
	);
	global $jr_saoe_filters;
	$first_where = 'Where?</th><td><b>Priority</b>|<b>Select</b></td></tr><tr><th scope="row">';
	foreach ( $jr_saoe_filters as $one_filter ) {
		if ( isset( $one_filter['disabled'] ) ) {
			$field = $one_filter['disabled'];
		} else {
			$field = $one_filter['filter'];
		}
		add_settings_field( 
			$field, 
			$first_where . $one_filter['where'], 
			'jr_saoe_echo_where', 
			'jr_saoe_settings_page', 
			'jr_saoe_where_section',
			$one_filter
		);
		$first_where = '';
	}
	add_settings_section( 
		'jr_saoe_priority_section', 
		'Priority', 
		'jr_saoe_priority_expl', 
		'jr_saoe_settings_page' 
	);
	add_settings_section( 
		'jr_saoe_warnings_section', 
		'Warnings', 
		'jr_saoe_warnings_expl', 
		'jr_saoe_settings_page' 
	);
	add_settings_field( 
		'warn_nothing', 
		'Warn if Disabled', 
		'jr_saoe_echo_warn_nothing', 
		'jr_saoe_settings_page', 
		'jr_saoe_warnings_section' 
	);	
}

function jr_saoe_overview_expl() {
	?>
	<p>
	The <b>jonradio Shortcodes Anywhere or Everywhere</b> plugin
	allows Shortcodes to be used
	<i>everywhere</i>
	on a WordPress web site.
	At least,
	that is the long term goal.
	</p>
	<p>
	How far have we come?
	The <b>Where?</b> section below shows you what the current version of the Plugin provides,
	in terms of where you can place Shortcodes.
	</p>
	<?php	
}

function jr_saoe_where_expl() {
	?>
	<p>
	WordPress automatically supports Shortcodes in Pages or Posts,
	but not in Page or Post Titles, or anywhere else.
	Decide where you want to use Shortcodes by checking the boxes below.
	</p>
	<p>
	<b>
	Note
	</b>
	-
	Shortcodes are displayed by name ("[y]"), not by their value ("2014"), in the Admin panels.
	This is standard WordPress behaviour.
	</p>
	<p>
	<b>
	Recommendations
	</b>
	(deciding which Settings to choose when more than one is listed below for the same "Where?" place):
	<ol>
	<li>
	<b>
	Post Excerpts
	</b>
	-
	choose both,
	just in case both Manual and Automatic Post Excerpts are being used in the future on your site 
	</li>
	<li>
	<b>
	Titles
	</b>
	- 
	choose two -
	the first one and the "recommended method";
	if that does not work, deselect the "recommended method" and select the "alternate method";
	the first Title entry should always be selected if you use Shortcodes in Page or Post Titles
	</li>
	</ol>
	</p>
	<p>
	<b>
	Priority 
	</b>
	-
	an Advanced Setting described in the next Section.
	</p>
	<?php	
}

function jr_saoe_echo_where( $one_filter ) {
	if ( isset( $one_filter['disabled'] ) ) {
		echo '<input type="text" size="2" maxlength="2" id="'
			. $one_filter['disabled']
			. '" name="jr_saoe_settings[priority]['
			. $one_filter['disabled']
			. ']" value="10" disabled="disabled" /> '
			. '<input type="checkbox" id="' 
			. $one_filter['disabled']
			. '" name="jr_saoe_settings['
			. $one_filter['disabled']
			. ']" value="true" checked="checked" disabled="disabled" /> '
			. $one_filter['description'];
	} else {
		$settings = get_option( 'jr_saoe_settings' );
		echo '<input type="text" size="2" maxlength="2" id="'
			. $one_filter['filter']
			. '" name="jr_saoe_settings[priority]['
			. $one_filter['filter']
			. ']" value="'
			. $settings['priority'][ $one_filter['filter'] ]
			. '" /> '
			. '<input type="checkbox" id="'
			. $one_filter['filter']
			. '" name="jr_saoe_settings['
			. $one_filter['filter']
			. ']" value="true"'
			. checked( TRUE, $settings[ $one_filter['filter'] ], FALSE ) . ' /> '
			. $one_filter['description']
			. ' <small>("'
			. $one_filter['filter']
			. '" filter)</small>';
	}
}

function jr_saoe_priority_expl() {
	global $jr_saoe_plugin_data;
	?>
	<p>
	Priority,
	specified in the "Where?" section just above,
	is an Advanced Setting that should normally be left at its default setting of 10.
	It is only useful if another plugin or theme is using one of the WordPress Filters
	selected above
	(Filter names are shown in parentheses at the end of each Description in the "Where?" section above)
	and when there appears to be a conflict between the plugin or theme, and this 
	<?php
	echo $jr_saoe_plugin_data['Name'];
	?>
	plugin.
	</p>
	<p>
	Priority can have an integer value from 1 to 99, with 10 the default value:
	<ol>
	<li>
	A Priority of 1 will substitute all Shortcodes before other Filter actions occur at the normal Priority of 10.
	</li>
	<li>
	A Priority of 99 will substitute all Shortcodes after other Filter actions occur at the normal Priority of 10.
	</li>
	</ol>
	</p>
	<p>
	Priority is worth experimenting with if this
	<?php
	echo $jr_saoe_plugin_data['Name'];
	?>
	plugin
	is doing anything more than its intended purpose:
	enabling Shortcodes in the places specified.
	For example, if the appearance of any part of the WordPress site changes when this
	<?php
	echo $jr_saoe_plugin_data['Name'];
	?>
	plugin
	is activated and one or more of the Where? settings selected,
	and goes back to normal when this plugin is deactivated.
	</p>
	<p>
	Priority has no effect when its Where? setting is <b>not</b> selected.
	</p>
	<?php
}

function jr_saoe_warnings_expl() {
	?>
	<p>
	Should there be a Warning message whenever this plugin is effectively disabled
	because no Areas are selected where it should enable Shortcodes to be placed?
	</p>
	<?php
}

function jr_saoe_echo_warn_nothing() {
	$settings = get_option( 'jr_saoe_settings' );
	echo '<input type="checkbox" id="warn_nothing" name="jr_saoe_settings[warn_nothing]" value="true"'
		. checked( TRUE, $settings['warn_nothing'], FALSE ) . ' />';
	echo ' Warning on Every Admin Panel when No <b>Where?</b> areas selected above';	
}

function jr_saoe_validate_settings( $input ) {
	$valid = array();
	if ( isset( $input['warn_nothing'] ) && ( 'true' === $input['warn_nothing'] ) ) {
		$valid['warn_nothing'] = TRUE;
	} else {
		$valid['warn_nothing'] = FALSE;
	}
	
	global $jr_saoe_filters;
	foreach ( $jr_saoe_filters as $one_filter ) {
		if ( !isset( $one_filter['disabled'] ) ) {
			if ( isset( $input[ $one_filter['filter'] ] ) && ( 'true' === $input[ $one_filter['filter'] ] ) ) {
				$valid[ $one_filter['filter'] ] = TRUE;
			} else {
				$valid[ $one_filter['filter'] ] = FALSE;
			}
			$priority_input = trim( $input['priority'][ $one_filter['filter'] ] );
			if ( '' === $priority_input ) {
				$priority = 10;
			} else {
				if ( is_numeric( $priority_input ) ) {
					$priority = (int) $priority_input;
					if ( $priority == $priority_input ) {
						if ( $priority < 1 ) {
							$priority = 10;
							if ( $priority < 0 ) {
								/*	negative integer error message
								*/
								add_settings_error(
									'jr_saoe_settings',
									'jr_saoe_priorityerror1',
									'Error in Priority Value: "'
										. $priority_input
										. '". Must be a positive integer from 1 to 99. Negative value entered.',
									'error'
								);
							} else {
								/*	zero integer error message
								*/
								add_settings_error(
									'jr_saoe_settings',
									'jr_saoe_priorityerror1',
									'Error in Priority Value: "'
										. $priority_input
										. '". Must be a positive integer from 1 to 99. Zero value entered.',
									'error'
								);
							}
						}
					} else {
						$priority = 10;
						/*	floating point error message
						*/
						add_settings_error(
							'jr_saoe_settings',
							'jr_saoe_priorityerror2',
							'Error in Priority Value: "'
								. $priority_input
								. '". Must be a positive integer from 1 to 99. Value with decimal point entered.',
							'error'
						);
					}
				} else {
					$priority = 10;
					/*	non-numeric error message
					*/
					add_settings_error(
						'jr_saoe_settings',
						'jr_saoe_priorityerror3',
						'Error in Priority Value: "'
							. $priority_input
							. '". Must be a positive integer from 1 to 99. Non-numeric value entered.',
						'error'
					);
				}
			}
			$valid['priority'][ $one_filter['filter'] ] = $priority;
		}
	}
	return $valid;
}

/*	Warn when Plugin is effectively doing nothing,
	i.e. - when none of the Where? settings have checkmarks.
*/
$settings = get_option( 'jr_saoe_settings' );
if ( $settings['warn_nothing'] ) {
	global $jr_saoe_filters;
	$checkmarks = FALSE;
	/*	Using OR means any Field will a Checkmark
		will set $checkmarks to TRUE
	*/
	foreach ( $jr_saoe_filters as $one_filter ) {
		if ( !isset( $one_filter['disabled'] ) ) {
			$checkmarks = $checkmarks || $settings[ $one_filter['filter'] ];
		}
	}
	if ( !$checkmarks ) {
		add_action( 'all_admin_notices', 'jr_saoe_warn_nothing' );
		/**
		* Warn that the Plugin is effectively doing nothing
		* 
		* Put Warning on top of every Admin page (visible to Admins only)
		* until Admin sets one of the Where? Settings or deselects Warn.
		*
		*/
		function jr_saoe_warn_nothing() {
			global $jr_saoe_plugin_data;
			if ( current_user_can( 'manage_options' ) ) {
				echo '<div class="updated"><p><b>The <i>' . $jr_saoe_plugin_data['Name'] 
					. '</i> plugin is not Set to do anything as it has no Areas selected <i>where</i> Shortcodes will be enabled.  Check its <a href="'
					. admin_url( 'options-general.php?page=jr_saoe_settings' )
					. '">Settings page</a>.</b></p></div>';
			}
		}
	}
}

	
/*	Add Link to the plugin's entry on the Admin "Plugins" Page, for easy access
*/
add_filter( 'plugin_action_links_' . jr_saoe_plugin_basename(), 'jr_saoe_plugin_action_links', 10, 1 );

/**
* Creates Settings entry right on the Plugins Page entry.
*
* Helps the user understand where to go immediately upon Activation of the Plugin
* by creating entries on the Plugins page, right beside Deactivate and Edit.
*
* @param	array	$links	Existing links for our Plugin, supplied by WordPress
* @param	string	$file	Name of Plugin currently being processed
* @return	string	$links	Updated set of links for our Plugin
*/
function jr_saoe_plugin_action_links( $links ) {
	/*	The "page=" query string value must be equal to the slug
		of the Settings admin page.
	*/
	array_unshift( $links, '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=jr_saoe_settings' . '">Settings</a>' );
	return $links;
}	
	
?>