<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function alobaidi_loading_bar_add_setting(){

	add_settings_section('alobaidi_loading_bar_section', 'Loading Bar', 'alobaidi_loading_bar_text', 'general');

	add_settings_field( "alob_loading_bar_c", '<label for="alob_loading_bar_c">Color Code</label>', "alobaidi_loading_bar_custom_color_setting", "general", "alobaidi_loading_bar_section" );

	register_setting( 'general', 'alob_loading_bar_c' );

}
add_action( 'admin_init', 'alobaidi_loading_bar_add_setting' );


function alobaidi_loading_bar_text(){
	echo '<p>Custom loading bar color.</p>';
}


function alobaidi_loading_bar_custom_color_setting(){
	?>
    	<input id="alob_loading_bar_c" name="alob_loading_bar_c" type="text" value="<?php echo esc_attr( get_option('alob_loading_bar_c') ); ?>">
    	<p class="description">Enter loading bar color, for example #35aca8, default is red #f00000.</p>
    <?php
}

?>