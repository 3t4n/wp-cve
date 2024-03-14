<?php
/**
 * Custom Scrollbar
 * 
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno; Licensed GPLv2
 * 
 */

/**
 * Adds the `Settings` page.
 * 
 * @since        1.3.0
 */
class CustomScrollbar_AdminPage__Page_Setting extends CustomScrollbar_AdminPage__Page_Base {

    protected function _getArguments( $oFactory ) {
        return array(
            'page_slug'     => CustomScrollbar_Registry::$aAdminPages[ 'setting' ],
            'title'         => __( 'Scrollbars', 'custom-scrollbar' ),
            // 'screen_icon'   => CustomScrollbar_Registry::getPluginURL( "asset/image/screen_icon_32x32.png" ),
        );
    }

    protected function _load( $oFactory ) {

        new CustomScrollbar_AdminPage__InPageTab_Scrollbar( $oFactory, $this->_sPageSlug );
        new CustomScrollbar_AdminPage__InPageTab_CSS( $oFactory, $this->_sPageSlug );
        new CustomScrollbar_AdminPage__InPageTab_General( $oFactory, $this->_sPageSlug );
        new CustomScrollbar_AdminPage__InPageTab_Data( $oFactory, $this->_sPageSlug );
        new CustomScrollbar_AdminPage__InPageTab_Pro( $oFactory, $this->_sPageSlug );

    }
        
}
