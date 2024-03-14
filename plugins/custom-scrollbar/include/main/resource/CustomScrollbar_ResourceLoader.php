<?php
/**
 * Custom Scrollbar
 *
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2016 Michael Uno
 *
 */

/**
 * Handles loading resources.
 *
 * @since       1.3.0
 */
class CustomScrollbar_ResourceLoader extends CustomScrollbar_PluginUtility {

    /**
     * Sets up properties and hooks
     */
    public function __construct() {

        if ( ! $this->___shouldProceed() ) {
            return;
        }

        $_aScrollbars   = $this->___getScrollbars();
        new CustomScrollbar_ResourceLoader__Script( $_aScrollbars );
        new CustomScrollbar_ResourceLoader__Style( $_aScrollbars );

    }
        /**
         * @return      boolean
         * @since       1.3.0
         */
        private function ___shouldProceed() {
            if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
                return false;
            }
            if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
                return false;
            }
            return true;
        }
        /**
         * @return      array
         * @since       1.3.0
         */
        private function ___getScrollbars() {

            $_oOption     = CustomScrollbar_Option::getInstance();
            $_aScrollbars = $this->getAsArray( $_oOption->get( 'scrollbars' ) );
            $_aScrollbars = $this->___getActivatedScrollbars( $_aScrollbars );
            if ( ! count( $_aScrollbars ) ) {
                return array();
            }
            /**
             * Let third-parties modify processing scrollbar options.
             * If somebody wants to add more advanced options, use this filter hook.
             * @since       1.3.0
             */
            $_aScrollbars = apply_filters(
                CustomScrollbar_Registry::HOOK_SLUG . '_filter_activated_scrollbars',
                $_aScrollbars
            );

            return $_aScrollbars;

        }
            /**
             * @remark      Drops items with the Off status.
             * @return      array
             */
            private function ___getActivatedScrollbars( array $aScrollbars ) {

                $_oOption               = CustomScrollbar_Option::getInstance();
                $_bInitializeOnAjaxLoad = ( boolean ) $_oOption->get( 'load', 'ajax_initialization' );  // backward compatibility
                $_aDefault              = $this->getElementAsArray(
                    CustomScrollbar_Registry::$aOptions,
                    array( 'custom-scrollbar', 'scrollbars', 0 )
                );

                foreach( $aScrollbars as $_iIndex => &$_aScrollbar ) {

                    $_aScrollbar = $_aScrollbar + $_aDefault;

                    if ( ! $this->___isValidScrollbar( $_aScrollbar ) ) {
                        unset( $aScrollbars[ $_iIndex ] );
                        continue;
                    }

                    // Some options need to be formatted individually.
                    // Note that the variable types are important as boolean and integer values are directly evaluated in JavaScript.
                    $_aScrollbar[ 'status' ]                  = ( boolean ) $_aScrollbar[ 'status' ];
                    $_aScrollbar[ 'mouseWheel' ]              = $this->___getScrollElementOptionsFormatted( $_aScrollbar[ 'mouseWheel' ] );
                    $_aScrollbar[ 'keyboard' ]                = $this->___getScrollElementOptionsFormatted( $_aScrollbar[ 'keyboard' ] );
                    $_aScrollbar[ 'scrollButtons' ]           = $this->___getScrollElementOptionsFormatted( $_aScrollbar[ 'scrollButtons' ] );
                    $_aScrollbar[ 'initialize_on_ajax_load' ] = ( boolean ) $this->getElement(
                        $_aScrollbar,
                        array( 'initialize_on_ajax_load' ),
                        $_bInitializeOnAjaxLoad
                    );
                    $_aScrollbar[ 'responsive' ]              = $this->___getResponsiveOptionsFormatted( $_aScrollbar[ 'responsive' ] );

                }
                return $aScrollbars;

            }
                /**
                 * Checks whether the scrollbar of the given options is processable.
                 * @param array $aScrollbar
                 * @return bool
                 */
                private function ___isValidScrollbar( array $aScrollbar ) {
                    if ( ! $aScrollbar[ 'status' ] ) {
                        return false;
                    }
                    if ( ! trim( $aScrollbar[ 'selector' ] ) ) {
                        return false;
                    }
                    return true;
                }
                /**
                 * Formats scroll element options such as `mouseWheel`, `keyboard`, and `ScrollButtons`.
                 * @param array $aScrollElement
                 * @return array
                 * @since 1.3.0
                 */
                private function ___getScrollElementOptionsFormatted( array $aScrollElement ) {

                    $aScrollElement[ 'enable' ] = ( boolean ) $aScrollElement[ 'enable' ];
                    $_isMouseWheelScrollAmount = $this->getElement(
                        $aScrollElement,
                        array( 'scrollAmount' )
                    );
                    $aScrollElement[ 'scrollAmount' ] = $_isMouseWheelScrollAmount
                        ? ( integer ) $_isMouseWheelScrollAmount
                        : 'auto';
                    return $aScrollElement;

                }

                /**
                 * @param array $aResponsive
                 * @return array
                 * @since 1.3.0
                 */
                private function ___getResponsiveOptionsFormatted( array $aResponsive ) {
                    $aResponsive [ 'enable' ] = ( boolean ) $aResponsive [ 'enable' ];
                    foreach( $aResponsive[ 'screen_width_range' ] as &$_aRange ) {
                        $_aRange[ 0 ] = ( integer ) $_aRange[ 0 ];
                        $_aRange[ 1 ] = ( integer ) $_aRange[ 1 ];
                    }
                    return $aResponsive;
                }

}