<?php
function cpln_content_settings_desc() {
    echo '<p>What do you want the pop up to say?</p>';
} // end cpln_content_settings_desc
 
function cpln_title_content_output($args) {
    $options = get_option('cpln_content_settings');
	
	$html = '<input type="text" id="cpln_title_content" name="cpln_content_settings[cpln_title_content]" value="'. $options['cpln_title_content'] .'" size="50" required/>';
    $html .= '<label for="cpln_title_content"> '  . $args[0] . '</label>'; 
    echo $html;
} // end cpln_title_content_output
 
function cpln_warning_body_output($args) {
    $options = get_option('cpln_content_settings');
	
	$settings = array(
		'media_buttons'	=>	true,
		'wpautop'		=>	true,
		'textarea_name'	=>	'cpln_content_settings[cpln_body_content]'
	);
	
	$html =  wp_editor($options['cpln_body_content'], 'cpln_body_content', $settings);
    $html .= '<label for="cpln_body_content"> '  . $args[0] . '</label>'; 
     
    echo $html;
     
} // end cpln_warning_body_output