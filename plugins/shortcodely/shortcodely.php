<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://swapnilpatil.in
 * @since             1.0.1
 * @wordpress-plugin
 * Plugin Name:       shortcodely
 * Plugin URI:        https://github.com/patilswapnilv/shortcodely/
 * Version:           1.0.1
 * Author:            Swapnil V. Patil
 * Author URI:        https://swapnilpatil.in
 * Description:       Include any widget in a page/post for any theme.
 * License:           GPL-3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain:       shortcodely
 * Domain Path:       /languages
 */

/**
*
*    @category Core
*    @package  Shortcodely
*    @author   Swapnil V. Patil <patilswapnilv@gmail.com>
*    @license  GPL-3.0+ https://www.gnu.org/licenses/gpl-3.0.en.html
*    @version  1.0.1
*    @link     https://github.com/patilswapnilv/shortcodely
*/

add_action( 'in_widget_form', 'shortcodely_spice_get_widget_id' );
function shortcodely_spice_get_widget_id( $widget_instance ) {
	  /*
      * Main function to get widget id
      *
      */
	echo '<p><strong>To use as shortcode with id:</strong> ';
	if ( '__i__' == $widget_instance->number ) {
		echo 'Save the widget first!</p>';
	} else {
		echo '[do_widget id=' . $widget_instance->id . ']</p>';
	}
}

/**
 * @return callable
 */
function shortcodely_remove_widget_class( $params ) {
	/*
    * Remove the widget classes
    */
	if ( ! empty( $params[0]['before_widget'] ) ) {
		$params[0]['before_widget'] =
			str_replace( '"widget ', '"', $params[0]['before_widget'] );
	}

	if ( ! empty( $params[0]['before_title'] ) ) {
		$params[0]['before_title']
			= $params[0]['before_title'] = str_replace( 'widget-title', '', $params[0]['before_title'] );
	}

	return $params;
}
/*-----------------------------------*/
function shortcodely_do_widget_area( $atts ) {
	global $wp_registered_widgets, $_wp_sidebars_widgets, $wp_registered_sidebars;

	extract(
		shortcode_atts(
			array(
						'widget_area' => 'widgets_for_shortcodes',
						'class' => 'shortcodely-widget-area', /* the widget class is picked up automatically.	If we want to add an additional class at the wrap level to try to match a theme, use this */
						'widget_area_class' => '', /* option to disassociate from themes widget styling use =none*/
						'widget_classes' => '', /* option to disassociate from themes widget styling */

						), $atts
		)
	);

	if ( ! empty( $atts ) ) {
		if ( ('widgets_for_shortcodes' == $widget_area) and ! empty( $atts[0] ) ) {
			$widget_area = $atts[0];
		}
	}

	if ( empty( $wp_registered_sidebars[ $widget_area ] ) ) {
		echo '<br/>Widget area "' . $widget_area . '" not found. Registered widget areas (sidebars) are: <br/>';
		foreach ( $wp_registered_sidebars as $area => $sidebar ) {
			echo $area . '<br />';
		}
	}
	//if (isset($_REQUEST['do_widget_debug']) and current_user_can('administrator')) var_dump( $wp_registered_sidebars); /**/

	if ( 'none' == $widget_area_class ) {
		$class = '';
	} else {
		if ( ! empty( $widget_area_class ) ) {    //2014 08
			$class .= 'class="' . $class . ' ' . $widget_area_class . '"';
		} else {
			$class = 'class="' . $class . '"';
		}
	}

	if ( ! empty( $widget_classes ) and ('none' == $widget_classes) ) {
		add_filter( 'dynamic_sidebar_params', 'shortcodely_remove_widget_class' );
	}

	ob_start(); /* catch the echo output, so we can control where it appears in the text	*/
	dynamic_sidebar( $widget_area );
	$output = ob_get_clean();
	remove_filter( 'dynamic_sidebar_params', 'shortcodely_remove_widget_class' );

	$output = PHP_EOL . '<div id="' . $widget_area . '" ' . $class . '>'
				. $output
				. '</div>' . PHP_EOL;

	return $output;
}
/*-----------------------------------*/
function shortcodely_do_widget( $atts ) {
	global $wp_registered_widgets, $_wp_sidebars_widgets, $wp_registered_sidebars;

	/* check if the widget is in	the shortcode x sidebar	if not , just use generic,
    if it is in, then get the instance	data and use that */

	if ( is_admin() ) {
		return '';
	}    // eg in case someone decides to apply content filters when apost is saved, and not all widget stuff is there.

	extract(
		shortcode_atts(
			array(
						'sidebar' => 'Widgets for Shortcodely', //default
						'id' => '',
						'name' => '',
						'title' => '', /* do the default title unless they ask us not to - use string here not boolean */
						'class' => 'shortcodely_widget', /* the widget class is picked up automatically.	If we want to add an additional class at the wrap level to try to match a theme, use this */
						'wrap' => '', /* wrap the whole thing - title plus widget in a div - maybe the themes use a div, maybe not, maybe we want that styling, maybe not */
						'widget_classes' => '', /* option to disassociate from themes widget styling */
						), $atts
		)
	);

	if ( isset( $_wp_sidebars_widgets ) ) {
		shortcodely_show_widget_debug( 'which one', $name, $id, $sidebar ); //check for debug prompt and show widgets in shortcode sidebar if requested and logged in etc
	} else {
		$output = '<br />No widgets defined at all in any sidebar!';

		return $output;
	}

	/* compatibility check - if the name is not entered, then the first parameter is the name */
	if ( empty( $name ) and ! empty( $atts[0] ) ) {
		$name = $atts[0];
	}

	/* the widget need not be specified, [do_widget widgetname] is adequate */
	if ( ! empty( $name ) ) {    // we have a name
		$widget = $name;

		foreach ( $wp_registered_widgets as $i => $w ) {/* get the official internal name or id that the widget was registered with	*/
			if ( strtolower( $widget ) == (strtolower( $w ['name'] )) ) {
				$widget_ids[] = $i;
			}
			//if ($debug) {echo '<br /> Check: '.$w['name'];}
		}

		if ( ! ($sidebarid = shortcodely_get_sidebar_id( $sidebar )) ) {
			$sidebarid = $sidebar; /* get the official sidebar id for this widget area - will take the first one */
		}
	} else { /* check for id if we do not have a name */

		if ( ! empty( $id ) ) {        /* if a specific id has been specified */
			foreach ( $wp_registered_widgets as $i => $w ) { /* get the official internal name or id that the widget was registered with	*/
				if ( $id == $w['id'] ) {
					$widget_ids[] = $id;
				}
			}
			//echo '<h2>We have an id: '.$id.'</h2>'; 	if (!empty($widget_ids)) var_dump($widget_ids);
		} else {
			$output = '<br />No valid widget name or id given in shortcode parameters';

			return $output;
		}
			// if we have id, get the sidebar for it
			$sidebarid = shortcodely_get_widgets_sidebar( $id );
		if ( ! $sidebarid ) {
			$output = '<br />Widget not in any sidebars<br />';

			return $output;
		}
	}

	if ( empty( $widget ) ) {
		$widget = '';
	}
	if ( empty( $id ) ) {
		$id = '';
	}

	if ( empty( $widget_ids ) ) {
		$output = '<br />Error: Your Requested widget "' . $widget . ' ' . $id . '" is not in the widget list.<br />';
		$output .= shortcodely_show_widget_debug( 'empty', $name, $id, $sidebar );

		return $output;
	}

	if ( empty( $widget ) ) {
		$widget = '';
	}

	//$content = '';
	/* if the widget is in our chosen sidebar, then use the options stored for that */

	if ( ( ! isset( $_wp_sidebars_widgets[ $sidebarid ] )) or (empty( $_wp_sidebars_widgets[ $sidebarid ] )) ) { // try upgrade
		shortcodely_upgrade_sidebar();
	}

	//if we have a specific sidebar selected, use that
	if ( (isset( $_wp_sidebars_widgets[ $sidebarid ] )) and ( ! empty( $_wp_sidebars_widgets[ $sidebarid ] )) ) {
		/* get the intersect of the 2 widget setups so we just get the widget we want	*/

		$wid = array_intersect( $_wp_sidebars_widgets[ $sidebarid ], $widget_ids );
	} else { /* the sidebar is not defined or selected - should not happen */
		if ( isset( $debug ) ) {    // only do this in debug mode
			if ( ! isset( $_wp_sidebars_widgets[ $sidebarid ] ) ) {
				$output = '<p>Error: Sidebar "' . $sidebar . '" with sidebarid "' . $sidebarid . '" is not defined.</p>';
			} // shouldnt happen - maybe someone running content filters on save
			else {
				$output = '<p>Error: Sidebar "' . $sidebar . '" with sidebarid "' . $sidebarid . '" is empty (no widgets)</p>';
			}
		}
	}

	$output = '';
	if ( empty( $wid ) or ( ! is_array( $wid )) or (count( $wid ) < 1) ) {
		$output = '<p>Error: Your requested Widget "' . $widget . '" is not in the "' . $sidebar . '" sidebar</p>';
		$output .= shortcodely_show_widget_debug( 'empty', $name, $id, $sidebar );

		unset( $sidebar );
		unset( $sidebarid );
	} else {
		/*	There may only be one but if we have two in our chosen widget then it will do both */
		$output = '';
		foreach ( $wid as $i => $widget_instance ) {
			ob_start(); /* catch the echo output, so we can control where it appears in the text	*/
			shortcodely_shortcode_sidebar( $widget_instance, $sidebar, $title, $class, $wrap, $widget_classes );
			$output .= ob_get_clean();
		}
	}

	return $output;
}
/* -------------------------------------------------------------------------*/
function shortcodely_shortcode_sidebar( $widget_id,
	$name = 'widgets_for_shortcode',
	$title = true,
	$class = '',
	$wrap = '',
	$widget_classes = ''
) {
	/* This is basically the wordpress code, slightly modified	*/
	global $wp_registered_sidebars, $wp_registered_widgets;

	$debug = shortcodely_check_if_widget_debug();

	$sidebarid = shortcodely_get_sidebar_id( $name );

	$sidebars_widgets = wp_get_sidebars_widgets();

	$sidebar = $wp_registered_sidebars[ $sidebarid ]; // has the params etc

	$did_one = false;

	/* lifted from wordpress code, keep as similar as possible for now */

	if ( ! isset( $wp_registered_widgets[ $widget_id ] ) ) {
		return;
	} // wp had c o n t i n u e

				$params = array_merge(
					array(
								array_merge(
									$sidebar,
									array(
									'widget_id' => $widget_id,
										'widget_name' => $wp_registered_widgets[ $widget_id ]['name'],
									)
								),
					),
					(array) $wp_registered_widgets[ $widget_id ]['params']
				);

	$validtitletags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'header', 'strong', 'em' );
	$validwraptags = array( 'div', 'p', 'main', 'aside', 'section' );

	if ( ! empty( $wrap ) ) { /* then folks want to 'wrap' with their own html tag, or wrap = yes	*/
		if ( ( ! in_array( $wrap, $validwraptags )) ) {
			$wrap = '';
		}
		/* To match a variety of themes, allow for a variety of html tags. */
		/* May not need if our sidebar match attempt has worked */
	}

	if ( ! empty( $wrap ) ) {
		$params[0]['before_widget'] = '<' . $wrap . ' id="%1$s" class="%2$s">';
		$params[0]['after_widget'] = '</' . $wrap . '>';
	}

				// wp code to get classname
				$classname_ = '';
				//foreach ( (array) $wp_registered_widgets[$widget_id]['classname'] as $cn ) {
						$cn = $wp_registered_widgets[ $widget_id ]['classname'];
	if ( is_string( $cn ) ) {
		$classname_ .= '_' . $cn;
	} elseif ( is_object( $cn ) ) {
		$classname_ .= '_' . get_class( $cn );
	}
				//}
				$classname_ = ltrim( $classname_, '_' );

				// add MKM and others requested class in to the wp classname string
				// if no class specfied, then class will = shortcodelywidget.	These classes are so can reverse out unwanted widget styling.

				// $classname_ .= ' widget '; // wordpress seems to almost always adds the widget class

				$classname_ .= ' ' . $class;

				// we are picking up the defaults from the	thems sidebar ad they have registered heir sidebar to issue widget classes?

				// Substitute HTML id and class attributes into before_widget
	if ( ! empty( $params[0]['before_widget'] ) ) {
		$params[0]['before_widget'] = sprintf( $params[0]['before_widget'], $widget_id, $classname_ );
	} else {
		$params[0]['before_widget'] = '';
	}

	if ( empty( $params[0]['before_widget'] ) ) {
		$params[0]['after_widget'] = '';
	}

	$params = apply_filters( 'dynamic_sidebar_params', $params );
				// allow, any pne usingmust ensure they apply to the correct sidebars

	if ( ! empty( $title ) ) {
		if ( 'false' == $title ) { /* shortcodely switch off the title html, still need to get rid of title separately */
			$params[0]['before_title'] = '<span style="display: none">';
			$params[0]['after_title'] = '</span>';
		} else {
			if ( in_array( $title, $validtitletags ) ) {
				$class = ' class="widget-title" ';

				$params[0]['before_title'] = '<' . $title . ' ' . $class . ' >';
				$params[0]['after_title'] = '</' . $title . '>';
			}
		}
	}

	if ( ! empty( $widget_classes ) and ('none' == $widget_classes) ) {
		$params = shortcodely_remove_widget_class( $params ); // also called in widget area shortcode
	}

	$callback = $wp_registered_widgets[ $widget_id ]['callback'];
	if ( is_callable( $callback ) ) {
		call_user_func_array( $callback, $params );
		$did_one = true;
	}
	//	}
	return $did_one;
}
/* ---------------------------------------------------------------------------*/
function shortcodely_reg_sidebar() {
	// this is fired late, so hopefully any theme sidebars will have been registered already.

	global $wp_registered_widgets, $_wp_sidebars_widgets, $wp_registered_sidebars;

	if ( function_exists( 'register_sidebar' ) ) {    // maybe later, get the first main sidebar and copy it's before/after etc
		$args = array(
		'name' => 'Widgets for Shortcodely',
		'id' => 'widgets_for_shortcodes', // hope to avoid losing widgets
		'description' => __( 'Sidebar to hold widgets and their settings. These widgets will be used in a shortcode.	This sidebars widgets should be saved with your theme settings now.', 'shortcodely-shortcode-any-widget' ),
		'before_widget' => '<aside' . ' id="%1$s" class="%2$s ">', // 201402 to match twentyfourteen theme
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title" >', // 201402 maybe dont use widget class - we are in content here not in a widget area but others want the widget styling. ?
		'after_title' => '</h1>',
		);

		if ( ! empty( $wp_registered_sidebars ) ) {    // we got some sidebars already.
				$main_sidebar = reset( $wp_registered_sidebars ); // Grab the first sidebar and use that as defaults for the widgets
				$args['before_widget'] = $main_sidebar['before_widget'];
			$args['after_widget'] = $main_sidebar['after_widget'];
			$args['before_title'] = $main_sidebar['before_title'];
			$args['after_title'] = $main_sidebar['after_title'];
		}

		register_sidebar( $args );
	}

	//else {	echo '<h1>CANNOT REGISTER widgets_for_shortcodes SIDEBAR</h1>';}
}
/*-----------------------------------*/
require 'shortcodely-admin-form-html.php';
require 'shortcodely-utilities.php';

if ( is_admin() ) {
	$shortcodely_saw_plugin_admin = new shortcodely_saw_plugin_admin();
}

add_action( 'widgets_init', 'shortcodely_reg_sidebar', 98 ); // register late so it appears last

add_action( 'switch_theme', 'shortcodely_save_shortcodes_sidebar' );
add_action( 'after_switch_theme', 'shortcodely_restore_shortcodes_sidebar' );

add_shortcode( 'do_widget', 'shortcodely_do_widget' );
add_shortcode( 'do_widget_area', 'shortcodely_do_widget_area' ); // just dump the whole widget area - to get same styling

//require_once(ABSPATH . 'wp-includes/widgets.php');	 // *** do we really need this here?
function shortcodely_saw_load_text() {
	// wp (see l10n.php) will check wp-content/languages/plugins if nothing found in plugin dir
	$result = load_plugin_textdomain(
		'shortcodely-shortcode-any-widget', false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages/'
	);
}

add_action( 'plugins_loaded', 'shortcodely_saw_load_text' );

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'shortcodely_add_action_links' );

function shortcodely_add_action_links( $links ) {
	$mylinks[] =
	'<a title="Haven\'t read the instructions? Need your hand held?" href="' . admin_url( 'options-general.php?page=shortcodely_saw' ) . '">Settings</a>';
	$mylinks[] =
	'<a title="Yes I know it is the same link, but some people ...." href="' . admin_url( 'options-general.php?page=shortcodely_saw' ) . '">HELP</a>';

	return array_merge( $links, $mylinks );
}
