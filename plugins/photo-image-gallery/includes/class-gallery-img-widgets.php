<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Class UXGallery_Widgets
 */
class UXGallery_Widgets{

	/**
	 * Register UX Gallery Widget
	 */
	public static function init(){
		register_widget( 'UXGallery_Widget' );
	}
}