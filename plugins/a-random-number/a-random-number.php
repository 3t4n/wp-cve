<?php
/**
 * Plugin Name: A Random Number
 * Plugin URI: https://www.macardam.com/a-random-number/
 * Description: Outputs a random number via shortcode.  It's magic.
 * Version: 1.1
 * Author: Macardam
 * Author URI: https://www.macardam.com/
 * License: GPL2
 */

/* Random Number Function */
function randomNumber( $atts ){
	$atts = shortcode_atts( array(
		'min' => '1',
		'max' => '100',
        'comma' => 'yes'
		), $atts, 'randomnumber' );
	$arandomnumber = mt_rand( $atts['min'], $atts['max'] );
	$prettynumber = number_format($arandomnumber);

    if($atts['comma'] == "no"){
        return $arandomnumber;
    } 
    else {
        return $prettynumber;
    }
}

/* Assigning Shortcode */
add_shortcode('arandomnumber', 'randomNumber');

/* Adding Button to Editor */
add_action('admin_head', 'a_random_number_button');

function a_random_number_button() {
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
    return;
    }
    // verify the post type
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return;
    // check if WYSIWYG is enabled
    if ( get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "arandomnumber_add_tinymce_plugin");
        add_filter('mce_buttons', 'arandomnumber_register_my_tc_button');
    }
}

function arandomnumber_add_tinymce_plugin($plugin_array) {
    $plugin_array['arandomnumber_tc_button'] = plugins_url( '/arandomnumber-button.js', __FILE__ ); 
    return $plugin_array;
}

function arandomnumber_register_my_tc_button($buttons) {
   array_push($buttons, "arandomnumber_tc_button");
   return $buttons;
}

/* Adding QuickTag Button */
function arn_quicktags() {

	if ( wp_script_is( 'quicktags' ) ) {
	?>
	<script type="text/javascript">
	QTags.addButton( 'arandomnumber', 'A Random Number', '[arandomnumber min=1 max=100]'  );
	</script>
	<?php
	}

}
add_action( 'admin_print_footer_scripts', 'arn_quicktags' );

?>