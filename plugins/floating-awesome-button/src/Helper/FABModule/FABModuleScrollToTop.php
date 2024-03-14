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

class FABModuleScrollToTop extends FABModule {

    /** Constructor Method */
    public function __construct() {
        parent::__construct();
        $this->key         = 'module_scrolltotop';
        $this->name        = 'Scroll to Top';
        $this->description = 'Scroll to Top Button Module Configuration';

        /** Initialize Options */
        $this->options = array(
            'offset' => array(
                'text' => 'Offset',
                'type' => 'number',
                'value' => 400,
                'info' => 'Number of pixels to be scrolled before the button appears'
            ),
            'duration' => array(
                'text' => 'Duration',
                'type' => 'number',
                'value' => 400,
                'info' => 'Window scroll duration in miliseconds'
            ),
            'animation' => array(
                'text' => 'Animation',
                'info' => 'To see animation reference you can go to <code><a href="https://daneden.github.io/animate.css/" target="_blank">Animate.css</a></code>.',
                'children' => array(
                    'in' => array(
                        'text' => 'In',
                        'type' => 'select',
                        'options' => array(),
                        'class' => array( 'input' => 'select2 field_option_animation_element' ),
                        'value' => 'rotateIn',
                    ),
                    'out' => array(
                        'text' => 'Out',
                        'type' => 'select',
                        'options' => array(),
                        'class' => array( 'input' => 'select2 field_option_animation_element' ),
                        'value' => 'rotateOut',
                    ),
                    'duration' => array(
                        'text' => 'Duration',
                        'type' => 'number',
                        'value' => 1000,
                        'info' => 'Animation duration in milliseconds'
                    ),
                )
            )
        );
        $options = $this->WP->get_option( sprintf('fab_%s', $this->key) );
        $this->options = (is_array($options)) ? $this->Helper->ArrayMergeRecursive($this->options, $options) : $this->options;
    }

}