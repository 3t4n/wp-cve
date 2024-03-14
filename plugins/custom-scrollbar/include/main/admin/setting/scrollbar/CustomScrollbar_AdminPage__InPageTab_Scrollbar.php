<?php
/**
 * Custom Scrollbar
 *
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno; Licensed GPLv2
 *
 */

/**
 * Adds the `Scrollbars` tab in the `Setting` page.
 *
 * @since        1.3.0
 */
class CustomScrollbar_AdminPage__InPageTab_Scrollbar extends CustomScrollbar_AdminPage__InPageTab_Base {

    protected function _getArguments( $oFactory ) {
        return array(
            'tab_slug'  => 'scrollbars',
            'title'     => __( 'Scrollbars', 'custom-scrollbar' ),
            'style'     => '.custom-scrollbar-field.custom-scrollbar-field.custom-scrollbar-field-text > .custom-scrollbar-input-label-container.custom-scrollbar-field-text-multiple-labels {
    display: inline-block;
    width: auto;
    padding-right: 1em;
}            
.custom-scrollbar-field-no_ui_slider input {
    max-width: 100px;
}
.custom-scrollbar-field-no_ui_slider .no-ui-sliders {
    margin: 1em 1em 2em;
}
            ',
        );
    }

    protected function _load( $oFactory ) {
        new CustomScrollbar_AdminPage__FormSection_Scrollbar( $oFactory, $this->_sPageSlug, $this->_sTabSlug );
    }

    protected function _do( $oFactory ) {

        $_sImageURL = CustomScrollbar_Registry::getPluginURL( 'asset/image/scrollbar_parts_names.png' );
        echo "<img class='scroll-bar-parts' src='{$_sImageURL}' alt='Scrollbar Parts'/>";
        echo "<div class='right-submit-button'>"
            . get_submit_button()
            . "</div>";
    }

}
