<?php

/**
 * Remove About Yourself section
 * - Biographical Info 
 */
add_action('admin_head', 'profile_admin_buffer_start');
add_action('admin_footer', 'profile_admin_buffer_end');
function remove_plain_bio($buffer){
	$titles = array('#<h2>'.__('About Yourself').'</h2>#','#<h2>'.__('About the user').'</h2>#');
	$buffer=preg_replace($titles,'<h2>'.__('Password').'</h2>',$buffer,1);
	$biotable='#<h2>'.__('Password').'</h2>.+?<table.+?/tr>#s';
	$buffer=preg_replace($biotable,'<h2>'.__('Password').'</h2> <table class="form-table">',$buffer,1);
	return $buffer;
}
function profile_admin_buffer_start() {ob_start('remove_plain_bio');}
function profile_admin_buffer_end() {ob_end_flush();}
