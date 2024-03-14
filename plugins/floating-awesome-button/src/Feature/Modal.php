<?php

namespace Fab\Feature;

! defined( 'WPINC ' ) or die;

/**
 * Initiate plugins
 *
 * @package    Fab
 * @subpackage Fab\Includes
 */

class Modal extends Feature {

    /** FAB Modal Theme */
    public static $layout = array(
        array(
            'id'   => 'background_image',
            'text' => 'Background Image',
        ),
        array(
            'text' => 'Grid',
            'children' => array(
                array(
                    'id'   => 'grid-left',
                    'text' => 'Grid Left',
                ),
                array(
                    'id'   => 'grid-right',
                    'text' => 'Grid Right',
                ),
            ),
        ),
        array(
            'id'   => 'overflow',
            'text' => 'Overflow',
        ),
        array(
            'id'   => 'stacked',
            'text' => 'Stacked',
        ),
    );

	/** FAB Modal Theme */
	public static $theme = array(
		array(
			'id'   => 'blank',
			'text' => 'Blank',
		),
		array(
			'id'   => 'window-light',
			'text' => 'Window Light',
		),
		array(
			'id'   => 'window-dark',
			'text' => 'Window Dark',
		),
	);

    /**
     * Feature construect
     *
     * @return void
     * @var    object   $plugin     Feature configuration
     * @pattern prototype
     */
    public function __construct( $plugin ) {
        $this->WP          = $plugin->getWP();
        $this->key         = 'core_modal';
        $this->name        = 'Modal';
        $this->description = 'Handles plugin core modal';
    }

}
