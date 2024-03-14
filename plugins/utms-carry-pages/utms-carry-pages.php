<?php
/**
* Plugin Name: UTMs Carry Pages
* Description: Simple plugin that will carry UTMs between pages.
* Version: 1.0
* Author: Riccardo Bertolini
**/

$fields = array('utm_source', 'utm_medium', 'utm_term', 'utm_content', 'utm_campaign');
$fieldsFoundUTMS = array();

// checking php side if the fields are available
foreach ($fields as $field){
	if (isset($_GET[$field]) && $_GET[$field] != '') {
        array_push($fieldsFoundUTMS, ($field . '=' . $_GET[$field]));
    }
}

// inject script only if there is at least one UTM
if(count($fieldsFoundUTMS) > 0) {
    function utms_carry_pages() {
		wp_enqueue_script( 'utms-carry', plugins_url( './utms-carry.js' , __FILE__ ));
    }

	add_action( 'wp_enqueue_scripts', 'utms_carry_pages' );
}

