<?php

namespace Fab\Feature;

! defined( 'WPINC ' ) || die;

/**
 * Initiate plugins
 *
 * @package    Fab
 * @subpackage Fab\Includes
 */

use Fab\View;
use Fab\Wordpress\Hook\Action;

class Miscellaneous extends Feature {

    /**
     * Feature construect
     *
     * @return void
     * @var    object   $plugin     Feature configuration
     * @pattern prototype
     */
    public function __construct( $plugin ) {
        parent::__construct(\Fab\Plugin::getInstance());
        $this->key         = 'core_miscellaneous';
        $this->name        = 'Miscellaneous';
        $this->description = 'Plugin extra options and configuration';

        /** Initialize Options */
        $this->options = array(
            'captcha' => array(
                'text' => 'Captcha',
                'children' => array(
                    'detectlocation' => array(
                        'text' => 'Auto Detect location',
                        'label' => array( 'text' => 'Enable/Disable' ),
                        'type' => 'switch',
                        'value' => '',
                        'info' => 'Auto Show/Hide google reCaptcha v3 based on form location'
                    ),
                )
            )
        );
        $options = $this->WP->get_option( sprintf('fab_%s', $this->key) );
        $this->options = (is_array($options)) ? $this->Helper->ArrayMergeRecursive($this->options, $options) : $this->options;

        /** Load Hooks */
        $this->loadHooks();
    }

    /** Load Module Hooks */
    public function loadHooks(){
        $plugin   = \Fab\Plugin::getInstance();

        /** @backend - Handle plugin upgrade */
        if($this->options['captcha']['children']['detectlocation']['value']){
            $action = new Action();
            $action->setComponent( $this );
            $action->setHook( 'wp_footer' );
            $action->setCallback( 'recaptchaAutoDetectInactive' );
            $action->setAcceptedArgs( 0 );
            $action->setMandatory( true );
            $action->setDescription( 'Auto Detect v3 Recaptcha Active or Not' );
            $this->hooks[] = $action;
        }
    }

    /** Google Recaptcha v3 Inactive */
    public function recaptchaAutoDetectInactive(){
        View::RenderStatic( 'Frontend/Miscellaneous/Captcha/AutoDetectInactive' );
    }

    /**
     * Sanitize input
     */
    public function sanitize() {
        /** Grab Data */
        $this->params = $_POST;
        $this->params = $this->params['fab_core_miscellaneous'];

        /** Sanitize Text Field */
        $this->params = (object) $this->WP->sanitizeTextField( $this->params );
    }

    /**
     * Transform data before save
     */
    public function transform() {
        /** Revalidate */
        $plugin   = \Fab\Plugin::getInstance();
        $this->params->captcha = $plugin->getHelper()->transformBooleanValue( $this->params->captcha );
        return $this->params;
    }

}