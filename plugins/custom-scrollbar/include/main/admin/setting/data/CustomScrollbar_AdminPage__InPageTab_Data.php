<?php
/**
 * Custom Scrollbar
 *
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno; Licensed GPLv2
 *
 */

/**
 * Adds the `Manage Options` tab in the `Setting` page.
 *
 * @since        1.3.0
 */
class CustomScrollbar_AdminPage__InPageTab_Data extends CustomScrollbar_AdminPage__InPageTab_Base {

    protected function _getArguments( $oFactory ) {
        return array(
            'tab_slug'  => 'data',
            'title'     => __( 'Manage Options', 'custom-scrollbar' ),
        );
    }

    protected function _load( $oFactory ) {
        new CustomScrollbar_AdminPage__FormSection_Transport( $oFactory, $this->_sPageSlug, $this->_sTabSlug );
        new CustomScrollbar_AdminPage__FormSection_Reset( $oFactory, $this->_sPageSlug, $this->_sTabSlug );
    }

}
