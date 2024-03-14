<?php
/**
 * Custom Scrollbar Pro
 *
 * http://en.michaeluno.jp/custom-scrollbar/custom-scrollbar-pro
 * Copyright (c) 2015-2016 Michael Uno; Licensed GPLv2
 *
 */

/**
 * Adds the `Pro` tab in the `Setting` page.
 *
 * @since        1.0.0
 */
class CustomScrollbar_AdminPage__InPageTab_Pro extends CustomScrollbar_AdminPage__InPageTab_Base {

    protected function _getArguments( $oFactory ) {
        return array(
            'tab_slug'  => 'pro',
            'title'     => __( 'Get Pro', 'custom-scrollbar' ),
            'url'       => 'http://en.michaeluno.jp/custom-scrollbar/custom-scrollbar-pro',
        );
    }

}
