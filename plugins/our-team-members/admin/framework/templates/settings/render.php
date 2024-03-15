<?php
$theme = isset ( $class->settings ['style'] ) ? $class->settings ['style'] : 'modern';
$is_ajax = isset ( $class->settings ['ajax_save'] ) ? $class->settings ['ajax_save'] : false;
$framework_title = isset ( $class->settings ['framework_title'] ) ? $class->settings ['framework_title'] : 'WPSF';
$is_single_page = ($class->is_single_page() === true) ? 'yes' : 'no';

$class->load_template ( 'settings/global/header.php', array (
		'theme' => $theme,
        'is_single_page' => $is_single_page,
		'settings_fields' => $class->get_settings_fields () 
) );

if ($theme == 'modern') {
	$class->load_template ( 'settings/modern/header.php', array (
			'is_ajax' => $is_ajax,
			'title' => $framework_title,
			'class' => &$class 
	) );
	
	$class->load_template ( 'settings/modern/body.php', array (
			'is_ajax' => $is_ajax,
			'title' => $framework_title,
			'class' => &$class 
	) );
    
    $class->load_template ( 'settings/modern/footer.php');
} else {
    $class->load_template ( 'settings/simple/header.php', array (
			'is_ajax' => $is_ajax,
			'title' => $framework_title,
			'class' => &$class 
	) );
	
	$class->load_template ( 'settings/simple/body.php', array (
			'is_ajax' => $is_ajax,
			'title' => $framework_title,
			'class' => &$class 
	) );
    
    $class->load_template ( 'settings/simple/footer.php',array('is_ajax' => $is_ajax,'class' => &$class));
    
    
}
$class->load_template ( 'settings/global/footer.php', array (
		'theme' => $theme 
) );
