<?php
/**
 * Custom Scrollbar
 *
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno
 *
 */

/**
 * Loads scripts.
 *
 * @since       1.3.0
 */
class CustomScrollbar_ResourceLoader__Script extends CustomScrollbar_PluginUtility {

    private $___aScrollbars = array();

    /**
     * Sets up properties and hooks
     */
    public function __construct( array $aScrollbars ) {

        $this->___aScrollbars = $aScrollbars;
        add_action( 'wp_enqueue_scripts', array( $this, 'replyToLoad' ) );

    }

    /**
     * @callback    action      wp_enqueue_scripts
     * @return void
     */
    public function replyToLoad() {

        $_oOption    = CustomScrollbar_Option::getInstance();
        $_iDebugMode = ( integer ) $_oOption->isDebugMode();
        $_aFiles     = array(
            0 => 'asset/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css',
            1 => 'asset/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css',
        );
        $_sPath       = $_aFiles[ $_iDebugMode ];
        wp_enqueue_style(
            'malihu-custom-scrollbar-css',     // handle id
            CustomScrollbar_Registry::getPluginURL( $_sPath ) // file url
        );

        $_sPath       = 'asset/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js';
        wp_enqueue_script(
            'malihu-custom-scrollbar',     // handle id
            CustomScrollbar_Registry::getPluginURL( $_sPath ),  // file url
            array( 'jquery' ),   // dependencies
            '',     // version
            true    // in footer? yes
        );

        $_aFiles     = array(
            0 => 'asset/js/custom-scrollbar-enabler.min.js',
            1 => 'asset/js/custom-scrollbar-enabler.js',
        );
        $_sPath       = $_aFiles[ $_iDebugMode ];
        wp_enqueue_script(
            'custom_scrollbar_enabler',     // handle id
            CustomScrollbar_Registry::getPluginURL( $_sPath ), // script url
            array( 'malihu-custom-scrollbar' ),   // dependencies
            '',     // version
            true    // in footer? yes
        );

        $_aData = array(
            'scrollbars' => $this->___aScrollbars,
            'debugMode'  => $_iDebugMode,
            'pluginName' => CustomScrollbar_Registry::NAME . ' ' . CustomScrollbar_Registry::VERSION ,
        ) + $_oOption->get();
        unset(
            $_aData[ 'css' ],
            $_aData[ 'reset' ],
            $_aData[ 'delete' ],
            $_aData[ 'pro' ]
        );

        wp_localize_script(
            'custom_scrollbar_enabler',  // handle id - the above used enqueue handle id
            'customScrollbarEnabler',  // name of the data loaded in the script
            $_aData // translation array
        );

    }

}