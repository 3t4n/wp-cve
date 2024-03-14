<?php
add_action( 'admin_init', 'qc_lpp_register_setting');
function qc_lpp_register_setting(){
	register_setting( 'qc_lpp_settings_options', 'qc_lpp_selected_post_types' );
}