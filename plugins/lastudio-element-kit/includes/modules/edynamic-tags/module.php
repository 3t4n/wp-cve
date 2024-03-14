<?php

namespace LaStudioKitThemeBuilder\Modules\EdynamicTags;

class Module extends \Elementor\Core\Base\Module {

	public function __construct() {
		parent::__construct();

		add_action( 'elementor/dynamic_tags/register', [ $this, 'register_new_dynamic_tags' ] );

	}

	public function get_name() {
		return 'edynamic-tags';
	}

	/**
	 * Register new Elementor dynamic tags.
	 *
	 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
	 * @return void
	 */
	public function register_new_dynamic_tags( $dynamic_tags_manager ){
		$dynamic_tags_manager->register( new Tags\Archive_Image() );
		$dynamic_tags_manager->register( new Tags\Post_Background_Image() );
		$dynamic_tags_manager->register( new Tags\Post_Custom_Field() );
	}
}