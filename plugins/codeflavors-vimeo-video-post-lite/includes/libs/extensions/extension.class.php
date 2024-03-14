<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\extensions;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Extension
 * @package Vimeotheque\extensions
 * @ignore
 */
class Extension extends Extension_Abstract implements Extension_Interface {

	public function __construct( $slug, $name, $description ){
		parent::set_slug( $slug );
		parent::set_name( $name );
		parent::set_description( $description );
	}
}