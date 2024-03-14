<?php

namespace Fab\Controller;

! defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 *setComponent
 *
 * @package    Fab
 * @subpackage Fab/Controller
 */

use Fab\Wordpress\Hook\Shortcode;
use Fab\Module\FABModuleSearch;

class Shortcodes extends Base {

	/**
	 * Admin constructor
	 *
	 * @return void
	 * @var    object   $plugin     Plugin configuration
	 * @pattern prototype
	 */
	public function __construct( $plugin ) {
		parent::__construct( $plugin );

		/** @frontend */
		$shortcode = new Shortcode();
		$shortcode->setComponent( $this );
		$shortcode->setHook( 'fab_search' );
		$shortcode->setCallback( 'fab_search' );
		$shortcode->setAcceptedArgs( 1 );
		$shortcode->setMandatory( false );
		$shortcode->setDescription( '[fab_search] shortcode Floating Awesome Button - Search' );
		$shortcode->setFeature( $plugin->getFeatures()['core_backend'] );
		$this->hooks[] = $shortcode;
	}

    /**
     * Set View for [fab_search] shortcode
     *
     * @return      string              Html template string from view View/Frontend/search.php
     */
    public function fab_search() {
        $module = new FABModuleSearch();
        $module->render();
    }

}
