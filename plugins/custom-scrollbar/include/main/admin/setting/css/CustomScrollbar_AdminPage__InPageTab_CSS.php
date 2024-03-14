<?php
/**
 * Custom Scrollbar
 *
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno; Licensed GPLv2
 *
 */

/**
 * Adds the `CSS` tab in the `Setting` page.
 *
 * @since        1.3.0
 */
class CustomScrollbar_AdminPage__InPageTab_CSS extends CustomScrollbar_AdminPage__InPageTab_Base {

    protected function _getArguments( $oFactory ) {
        return array(
            'tab_slug'  => 'css',
            'title'     => __( 'Custom CSS', 'custom-scrollbar' ),
        );
    }

    protected function _load( $oFactory ) {
        new CustomScrollbar_AdminPage__FormSection_CSS( $oFactory, $this->_sPageSlug, $this->_sTabSlug );
    }

    protected function _do( $oFactory ) {
        echo "<div class='right-submit-button'>"
            . get_submit_button()
            . "</div>";
    }

}
