<?php

namespace Fab\Feature;

! defined( 'WPINC ' ) or die;

/**
 * Initiate plugins
 *
 * @package    Fab
 * @subpackage Fab\Includes
 */

class Design extends Feature {

    /** FAB Button Layout */
    public static $layout = array(
        'position' => array(
            array(
                'id'   => 'left',
                'text' => 'Left',
            ),
            array(
                'id'   => 'center',
                'text' => 'Center',
            ),
            array(
                'id'   => 'right',
                'text' => 'Right',
            ),
        )
    );

	/** FAB Default Size Type */
	public static $size = array(
		'type' => array(
            array(
                'id'   => 'xsmall',
                'text' => 'XSmall',
            ),
            array(
                'id'   => 'small',
                'text' => 'Small',
            ),
            array(
                'id'   => 'medium',
                'text' => 'Medium',
            ),
            array(
                'id'   => 'large',
                'text' => 'Large',
            ),
            array(
                'id'   => 'xlarge',
                'text' => 'XLarge',
            ),
            array(
                'id'   => 'custom',
                'text' => 'Custom',
            ),
        ),
        'sizing' => array(
            array(
                'id' => 'px',
                'text' => 'PX'
            ),
            array(
                'id' => 'em',
                'text' => 'EM'
            ),
            array(
                'id' => '%',
                'text' => '%'
            ),
            array(
                'id' => 'rem',
                'text' => 'REM'
            ),
            array(
                'id' => 'vw',
                'text' => 'VW'
            ),
            array(
                'id' => 'vh',
                'text' => 'VH'
            ),
        )
	);

    /** FAB Template */
    public static $template = array(
        'name' => array(
            array(
                'id'   => 'hidden',
                'text' => 'Hidden',
            ),
            array(
                'id'   => 'classic',
                'text' => 'Classic',
            ),
            array(
                'id'   => 'shape',
                'text' => 'Shape',
            ),
        ),
        'shape' => array(
            array(
                'id'   => 'none',
                'text' => 'None',
            ),
            array(
                'id'   => 'bevel',
                'text' => 'Bevel',
            ),
            array(
                'id'   => 'circle',
                'text' => 'Circle',
            ),
            array(
                'id'   => 'message',
                'text' => 'Message',
            ),
            array(
                'id'   => 'octagon',
                'text' => 'Octagon',
            ),
            array(
                'id'   => 'pentagon',
                'text' => 'Pentagon',
            ),
            array(
                'id'   => 'rebbet',
                'text' => 'Rebbet',
            ),
            array(
                'id'   => 'rhombus',
                'text' => 'Rhombus',
            ),
            array(
                'id'   => 'star',
                'text' => 'Star',
            ),
            array(
                'id'   => 'square',
                'text' => 'Square',
            ),
            array(
                'id'   => 'triangle',
                'text' => 'Triangle',
            ),
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
		$this->key         = 'core_design';
		$this->name        = 'Design';
		$this->description = 'Floating Awesome Button Design';
	}

	/**
	 * Sanitize input
	 */
	public function sanitize() {
		/** Grab Data */
		$this->params = $_POST;
		$this->params = $this->params['fab_design'];

		/** Sanitize Text Field */
		$this->params = (object) $this->WP->sanitizeTextField( $this->params );
	}

	/**
	 * Transform data before save
	 */
	public function transform() {
        /** Revalidate */
        $plugin   = \Fab\Plugin::getInstance();
        if($this->params->template['name']==='classic'){ $this->params->template['shape'] = 'none'; }
        $this->params->tooltip = $plugin->getHelper()->transformBooleanValue( $this->params->tooltip );

        /** Transform */
		$this->params->template = json_decode( json_encode( $this->params->template ) );
		$this->params->tooltip = json_decode( json_encode( $this->params->tooltip ) );
		$this->params->layout = json_decode( json_encode( $this->params->layout ) );

        /** Merge */
		$this->options      = (object) array_merge(
			(array) $this->options,
			(array) $this->params
		);
		return $this->options;
	}

}
