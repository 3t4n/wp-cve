<?php 
/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/includes
 * @author     Designinvento <developers@designinvento.net>
 */
add_action('after_setup_theme', 'widget_include');
function widget_include(){
	include_once DIRECTORYPRESS_PATH . 'includes/core/widgets/directorypress_widget.php';
	include_once DIRECTORYPRESS_PATH . 'includes/core/widgets/directorypress_categories.php';
	include_once DIRECTORYPRESS_PATH . 'includes/core/widgets/directorypress_locations.php';
	include_once DIRECTORYPRESS_PATH . 'includes/core/widgets/directorypress_adverts.php';
	include_once DIRECTORYPRESS_PATH . 'includes/core/widgets/directorypress_search.php';
	include_once DIRECTORYPRESS_PATH . 'includes/core/widgets/directorypress-widgets-author.php';
	include_once DIRECTORYPRESS_PATH . 'includes/core/widgets/directorypress-widgets-price.php';
	include_once DIRECTORYPRESS_PATH . 'includes/core/widgets/directorypress_general_widgets.php';
}
add_action('widgets_init', 'directorypress_widget_include');
function directorypress_widget_include(){
	
	register_widget("Directorypress_Widget_Author");
	register_widget("Directorypress_Widget_Price");
}