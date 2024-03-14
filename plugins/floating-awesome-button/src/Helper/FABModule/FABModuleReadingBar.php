<?php

namespace Fab\Module;

! defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 * setComponent
 *
 * @package    Fab
 * @subpackage Fab/Controller
 */

class FABModuleReadingBar extends FABModule {

    /** Constructor Method */
    public function __construct() {
        parent::__construct();
        $this->key         = 'module_readingbar';
        $this->name        = 'Reading Bar';
        $this->description = 'Reading Bar Module Configuration';

        /** Initialize Options */
        $this->options = array(
            'target' => array(
                'text' => 'Target Element',
                'type' => 'text',
                'value' => 'body',
                'info' => 'Target element class/id to stick the bar'
            ),
            'template' => array(
                'text' => 'Template',
                'children' => array(
                    'background_color' => array(
                        'text' => 'Background Color',
                        'type' => 'text',
                        'class' => array( 'input' => 'colorpicker' ),
                        'value' => '#e5e7eb',
                    ),
                    'foreground_color' => array(
                        'text' => 'Foreground Color',
                        'type' => 'text',
                        'class' => array( 'input' => 'colorpicker' ),
                        'value' => '#4f46e5',
                    ),
                    'height' => array(
                        'text' => 'Height',
                        'type' => 'text',
                        'value' => '.25rem',
                        'info' => 'Use any sizing values px, rem, em, vh, vw, etc. Default is set to .25rem'
                    ),
                    'transition' => array(
                        'text' => 'Transition',
                        'type' => 'text',
                        'value' => '.25s',
                        'info' => 'Default is set to .25s'
                    ),
                )
            )
        );
        $options = $this->WP->get_option( sprintf('fab_%s', $this->key) );
        $this->options = (is_array($options)) ? $this->Helper->ArrayMergeRecursive($this->options, $options) : $this->options;
    }

}