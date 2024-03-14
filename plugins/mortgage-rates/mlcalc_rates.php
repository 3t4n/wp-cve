<?php

// Mortgage Rates Widget
//
// Copyright (c) 2008-2021 MLCALC.COM
// https://www.mlcalc.com/free-widgets/mortgage-rates/wordpress.htm
//
// This is an add-on for WordPress
// http://wordpress.org/
//
// **********************************************************************
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// *****************************************************************
/*
Plugin Name: Mortgage Rates
Plugin URI: https://www.mlcalc.com/free-widgets/mortgage-rates/wordpress.htm
Description: Mortgage rates widget for your blog.
Author: mlcalc.com
Version: 1.3.11
Author URI: https://www.mlcalc.com/mortgage-rates/
*/

/* Function: display_mlcalc_rates_widget
	** This function does the actual display of the widget in the sidebar
	**
	** args: $args (environment variables handled automatically by the hook)
	** returns: nothing
*/

define('MLCALC_RATES_URL', get_option('siteurl') . '/wp-content/plugins/mortgage-rates');
$mlcalc_rates_states = array ( 'XX' => array ( 'alias' => 'XX', 'name' => 'US Average', 'url' => 'https://www.mlcalc.com/mortgage-rates/', ), 'AL' => array ( 'alias' => 'AL', 'name' => 'Alabama', 'url' => 'https://www.mlcalc.com/mortgage-rates/alabama/', ), 'AK' => array ( 'alias' => 'AK', 'name' => 'Alaska', 'url' => 'https://www.mlcalc.com/mortgage-rates/alaska/', ), 'AZ' => array ( 'alias' => 'AZ', 'name' => 'Arizona', 'url' => 'https://www.mlcalc.com/mortgage-rates/arizona/', ), 'AR' => array ( 'alias' => 'AR', 'name' => 'Arkansas', 'url' => 'https://www.mlcalc.com/mortgage-rates/arkansas/', ), 'CA' => array ( 'alias' => 'CA', 'name' => 'California', 'url' => 'https://www.mlcalc.com/mortgage-rates/california/', ), 'CO' => array ( 'alias' => 'CO', 'name' => 'Colorado', 'url' => 'https://www.mlcalc.com/mortgage-rates/colorado/', ), 'CT' => array ( 'alias' => 'CT', 'name' => 'Connecticut', 'url' => 'https://www.mlcalc.com/mortgage-rates/connecticut/', ), 'DE' => array ( 'alias' => 'DE', 'name' => 'Delaware', 'url' => 'https://www.mlcalc.com/mortgage-rates/delaware/', ), 'DC' => array ( 'alias' => 'DC', 'name' => 'District of Columbia', 'url' => 'https://www.mlcalc.com/mortgage-rates/district-of-columbia/', ), 'FL' => array ( 'alias' => 'FL', 'name' => 'Florida', 'url' => 'https://www.mlcalc.com/mortgage-rates/florida/', ), 'GA' => array ( 'alias' => 'GA', 'name' => 'Georgia', 'url' => 'https://www.mlcalc.com/mortgage-rates/georgia/', ), 'HI' => array ( 'alias' => 'HI', 'name' => 'Hawaii', 'url' => 'https://www.mlcalc.com/mortgage-rates/hawaii/', ), 'ID' => array ( 'alias' => 'ID', 'name' => 'Idaho', 'url' => 'https://www.mlcalc.com/mortgage-rates/idaho/', ), 'IL' => array ( 'alias' => 'IL', 'name' => 'Illinois', 'url' => 'https://www.mlcalc.com/mortgage-rates/illinois/', ), 'IN' => array ( 'alias' => 'IN', 'name' => 'Indiana', 'url' => 'https://www.mlcalc.com/mortgage-rates/indiana/', ), 'IA' => array ( 'alias' => 'IA', 'name' => 'Iowa', 'url' => 'https://www.mlcalc.com/mortgage-rates/iowa/', ), 'KS' => array ( 'alias' => 'KS', 'name' => 'Kansas', 'url' => 'https://www.mlcalc.com/mortgage-rates/kansas/', ), 'KY' => array ( 'alias' => 'KY', 'name' => 'Kentucky', 'url' => 'https://www.mlcalc.com/mortgage-rates/kentucky/', ), 'LA' => array ( 'alias' => 'LA', 'name' => 'Louisiana', 'url' => 'https://www.mlcalc.com/mortgage-rates/louisiana/', ), 'ME' => array ( 'alias' => 'ME', 'name' => 'Maine', 'url' => 'https://www.mlcalc.com/mortgage-rates/maine/', ), 'MD' => array ( 'alias' => 'MD', 'name' => 'Maryland', 'url' => 'https://www.mlcalc.com/mortgage-rates/maryland/', ), 'MA' => array ( 'alias' => 'MA', 'name' => 'Massachusetts', 'url' => 'https://www.mlcalc.com/mortgage-rates/massachusetts/', ), 'MI' => array ( 'alias' => 'MI', 'name' => 'Michigan', 'url' => 'https://www.mlcalc.com/mortgage-rates/michigan/', ), 'MN' => array ( 'alias' => 'MN', 'name' => 'Minnesota', 'url' => 'https://www.mlcalc.com/mortgage-rates/minnesota/', ), 'MS' => array ( 'alias' => 'MS', 'name' => 'Mississippi', 'url' => 'https://www.mlcalc.com/mortgage-rates/mississippi/', ), 'MO' => array ( 'alias' => 'MO', 'name' => 'Missouri', 'url' => 'https://www.mlcalc.com/mortgage-rates/missouri/', ), 'MT' => array ( 'alias' => 'MT', 'name' => 'Montana', 'url' => 'https://www.mlcalc.com/mortgage-rates/montana/', ), 'NE' => array ( 'alias' => 'NE', 'name' => 'Nebraska', 'url' => 'https://www.mlcalc.com/mortgage-rates/nebraska/', ), 'NV' => array ( 'alias' => 'NV', 'name' => 'Nevada', 'url' => 'https://www.mlcalc.com/mortgage-rates/nevada/', ), 'NH' => array ( 'alias' => 'NH', 'name' => 'New Hampshire', 'url' => 'https://www.mlcalc.com/mortgage-rates/new-hampshire/', ), 'NJ' => array ( 'alias' => 'NJ', 'name' => 'New Jersey', 'url' => 'https://www.mlcalc.com/mortgage-rates/new-jersey/', ), 'NM' => array ( 'alias' => 'NM', 'name' => 'New Mexico', 'url' => 'https://www.mlcalc.com/mortgage-rates/new-mexico/', ), 'NY' => array ( 'alias' => 'NY', 'name' => 'New York', 'url' => 'https://www.mlcalc.com/mortgage-rates/new-york/', ), 'NC' => array ( 'alias' => 'NC', 'name' => 'North Carolina', 'url' => 'https://www.mlcalc.com/mortgage-rates/north-carolina/', ), 'ND' => array ( 'alias' => 'ND', 'name' => 'North Dakota', 'url' => 'https://www.mlcalc.com/mortgage-rates/north-dakota/', ), 'OH' => array ( 'alias' => 'OH', 'name' => 'Ohio', 'url' => 'https://www.mlcalc.com/mortgage-rates/ohio/', ), 'OK' => array ( 'alias' => 'OK', 'name' => 'Oklahoma', 'url' => 'https://www.mlcalc.com/mortgage-rates/oklahoma/', ), 'OR' => array ( 'alias' => 'OR', 'name' => 'Oregon', 'url' => 'https://www.mlcalc.com/mortgage-rates/oregon/', ), 'PA' => array ( 'alias' => 'PA', 'name' => 'Pennsylvania', 'url' => 'https://www.mlcalc.com/mortgage-rates/pennsylvania/', ), 'RI' => array ( 'alias' => 'RI', 'name' => 'Rhode Island', 'url' => 'https://www.mlcalc.com/mortgage-rates/rhode-island/', ), 'SC' => array ( 'alias' => 'SC', 'name' => 'South Carolina', 'url' => 'https://www.mlcalc.com/mortgage-rates/south-carolina/', ), 'SD' => array ( 'alias' => 'SD', 'name' => 'South Dakota', 'url' => 'https://www.mlcalc.com/mortgage-rates/south-dakota/', ), 'TN' => array ( 'alias' => 'TN', 'name' => 'Tennessee', 'url' => 'https://www.mlcalc.com/mortgage-rates/tennessee/', ), 'TX' => array ( 'alias' => 'TX', 'name' => 'Texas', 'url' => 'https://www.mlcalc.com/mortgage-rates/texas/', ), 'UT' => array ( 'alias' => 'UT', 'name' => 'Utah', 'url' => 'https://www.mlcalc.com/mortgage-rates/utah/', ), 'VT' => array ( 'alias' => 'VT', 'name' => 'Vermont', 'url' => 'https://www.mlcalc.com/mortgage-rates/vermont/', ), 'VA' => array ( 'alias' => 'VA', 'name' => 'Virginia', 'url' => 'https://www.mlcalc.com/mortgage-rates/virginia/', ), 'WA' => array ( 'alias' => 'WA', 'name' => 'Washington', 'url' => 'https://www.mlcalc.com/mortgage-rates/washington/', ), 'WV' => array ( 'alias' => 'WV', 'name' => 'West Virginia', 'url' => 'https://www.mlcalc.com/mortgage-rates/west-virginia/', ), 'WI' => array ( 'alias' => 'WI', 'name' => 'Wisconsin', 'url' => 'https://www.mlcalc.com/mortgage-rates/wisconsin/', ), 'WY' => array ( 'alias' => 'WY', 'name' => 'Wyoming', 'url' => 'https://www.mlcalc.com/mortgage-rates/wyoming/', ),);

function display_mlcalc_rates_widget( $args ) {
	extract( $args );
	$options = get_option( 'widget_mlcalc_rates' );
	$title   = $options['title'];

	echo $before_widget;
	echo $before_title
		 . $title
		 . $after_title;

	display_mlcalc_rates($options);

	echo $after_widget;
}

/* Function: display_mlcalc_rates
	** This function does the actual display of the common part of the widget
	**
	** args: $options - to skip duplicate get_options calls
	** returns: nothing
*/
function display_mlcalc_rates($options = array(), $content = null, $code = "") {
	global $mlcalc_ratesURL, $mlcalc_rates_states;
	if(!empty($code) || (!empty($options) && ($options[0] == 'mlrates'))){
		$shortcode = true;

		// $atts    ::= array of attributes
		// examples: [mlrates]
		//           [mlrates state='NY' size='narrow']
		extract( shortcode_atts( array(
			// default parameters
			'state' => 'XX',
			'size' => 'wide', // wide|narrow
		), $options ) );
		$form_size = strtolower($size);
		$state     = strtoupper($state);
	} else {
		$options   = get_option( 'widget_mlcalc_rates' );
		$form_size = empty( $options['form_size'] ) ? 'wide' : $options['form_size'];
		$state     = empty( $options['state'] ) ? 'XX' : $options['state'];
	}
	
	if($form_size == 'small') $form_size = 'narrow';
	if($form_size == 'big') $form_size = 'wide';
	
	$SECTION = $mlcalc_rates_states[$state];

	// LOAD SCRIPTS
	wp_enqueue_script( 'mlrates-widget-script', str_replace('http://www.', '//www.', $SECTION['url'])."wordpress.js", array('jquery') );
	
	// LOAD STYLES
	wp_register_style( 'mlrates-form-small-style', plugins_url('rates-widget-form-small.css', __FILE__) );
	wp_register_style( 'mlrates-form-style', plugins_url('rates-widget-form.css', __FILE__) );
	if($form_size == 'narrow'){
		wp_enqueue_style( 'mlrates-form-small-style' );
	} else {
		wp_enqueue_style( 'mlrates-form-style' );
	}

	if($shortcode) ob_start();

	echo "<!-- MLCALC RATES BEGIN -->\r\n";
	echo "<script type=\"text/javascript\">\r\n";
	echo "var _mlcalc_preload_img = new Image(312,44);\r\n";
	echo "_mlcalc_preload_img.src='".plugins_url('images/ajax-loader.gif', __FILE__)."';\r\n";
	echo "</script>\r\n";

	if($form_size == 'narrow'){
		include('form-narrow.inc.php');
	} else {
		include('form-wide.inc.php');
	}
	echo "<!-- MLCALC RATES END -->\r\n";

	if($shortcode){
		$result = ob_get_contents();
		ob_end_clean();
		if(is_null($content)){
			return $result;
		} else {
			return $content . $result;
		}
	}
}

/* Function: mlcalc_rates_control
**
** This function draws the controls form on the widget page and
** saves the settings when the "Save" button is clicked
**
** args: nothing
** returns: nothing
*/

function mlcalc_rates_control() {
	global $mlcalc_ratesURL, $mlcalc_rates_states;
	$options = $newoptions = get_option('widget_mlcalc_rates');

	if ( !empty($_POST['mlcalc_rates-submit'] )) {
		$newoptions['title']     = strip_tags(stripslashes($_POST['mlcalc_rates-title']));
		$newoptions['state']     = strip_tags(stripslashes($_POST['mlcalc_rates-state']));
		$newoptions['form_size'] = strip_tags(stripslashes($_POST['mlcalc_rates-form_size']));
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_mlcalc_rates', $options);
	}

	$title = esc_attr($options['title']);
	$state = esc_attr($options['state']);
	$form_size = esc_attr($options['form_size']);
?>
		<p><label for="mlcalc_rates-title"><?php _e('Title:'); ?> <input class="widefat" id="mlcalc_rates-title" name="mlcalc_rates-title" type="text" value="<?php echo $title; ?>" /></label></p>
		<p>
			<label for="mlcalc_rates-state"><?php _e( 'State:' ); ?>
				<select name="mlcalc_rates-state" id="mlcalc_rates-state" class="widefat">
					<?php
						foreach($mlcalc_rates_states as $SECTION) {
							echo '<option value="' . $SECTION['alias'] .'"' . ( $options['state'] == $SECTION['alias'] ? ' selected="selected"' : '' ). '>' . $SECTION['name'] . '</option>';
						}
					?>
				</select>
			</label>
		</p>
		<p>
			<label for="mlcalc_rates-form_size"><?php _e( 'Form size:' ); ?>
				<select name="mlcalc_rates-form_size" id="mlcalc_rates-form_size" class="widefat">
					<option value="narrow"<?php selected( $options['form_size'], 'narrow' ); ?>><?php _e('Narrow (width = 150px)'); ?></option>
					<option value="wide"<?php selected( $options['form_size'], 'wide' ); ?>><?php _e('Wide (width = 300px)'); ?></option>
				</select>
			</label>
		</p>
		<input type="hidden" id="mlcalc_rates-submit" name="mlcalc_rates-submit" value="1" />
<?php
}

/* Function: mlcalc_rates_register
**
** Registers the MLCALC widget with the widget page
**
** args: none
** returns: nothing
*/

function mlcalc_rates_register() {
	$widget_ops = array('classname' => 'widget_mlcalc_rates', 'description' => __('Mortgage rates widget for your blog'));
	$name = __('Mortgage Rates');

	wp_register_sidebar_widget( 'mlcalc_rates', $name, 'display_mlcalc_rates_widget', $widget_ops );
	wp_register_widget_control( 'mlcalc_rates', $name, 'mlcalc_rates_control' );
}

// This is important
add_action( 'widgets_init', 'mlcalc_rates_register' );
add_shortcode( 'mlrates', 'display_mlcalc_rates' );


?>