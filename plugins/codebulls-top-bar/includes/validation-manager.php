<?php 
/**
 * Method that validate the values per the options
 */
function cb_top_bar_validate_top_bar_options($input){
	$input=$input;
	return $input;
}

function cb_top_bar_validate_top_bar_options_content($input){
	$input=$input;
	$input['left-content-top-bar-plugin']= htmlentities($input['left-content-top-bar-plugin']);
	$input['center-content-top-bar-plugin']= htmlentities($input['center-content-top-bar-plugin']);
	$input['right-content-top-bar-plugin']= htmlentities($input['right-content-top-bar-plugin']);
	return $input;
}
?>