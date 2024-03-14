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
class CustomScrollbar_ResourceLoader__Style extends CustomScrollbar_PluginUtility {

    private $___aScrollbars = array();

    /**
     * Sets up properties and hooks
     */
    public function __construct( array $aScrollbars ) {

        $this->___aScrollbars = $aScrollbars;
        add_action( 'wp_print_styles', array( $this, 'replyToLoad' ) );

    }

    /**
     * @callback    action      wp_print_styles
     * @return void
     */
    public function replyToLoad() {

        $_oOption    = CustomScrollbar_Option::getInstance();
        $_sCSSRules  = $_oOption->get( 'css', 'custom_css' );
        $_sCSSRules .= $this->___getScrollbarsCSSRules( $this->___aScrollbars );
        $_sCSSRules  = trim( $_sCSSRules );
        if ( ! $_sCSSRules ) {
            return;
        }
        $this->___printCSSRules( $_sCSSRules );

    }

        /**
         * @return      string
         * @since       1.3.0
         */
        private function ___getScrollbarsCSSRules( array $aScrollbars ) {

            $_sDelimiter = $this->isDebugMode() ? PHP_EOL : ' ';
            $_aCSSRules  = array();
            foreach( $aScrollbars as $_iIndex => $_aScrollbar ) {
                $_aCSSRules[ $_iIndex ] = $this->___getScrollbarCSSRules( $_aScrollbar, $_sDelimiter );
            }
            return $_sDelimiter . implode( $_sDelimiter, $_aCSSRules );

        }

            /**
             * @since   1.3.0
             * @param   $_aScrollbar
             * @param   $_sDelimiter
             * @return  string
             */
            private function ___getScrollbarCSSRules( $_aScrollbar, $_sDelimiter ) {

                $_bIsResponsive       = $this->getElement( $_aScrollbar, array( 'responsive', 'enable' ), false );
                $_sEachScrollbarRules = $this->___getRulesForEach( $_aScrollbar, $_sDelimiter );
                if ( ! $_bIsResponsive ) {
                    return $_sEachScrollbarRules;
                }
                return $this->___getRulesByRanges( $_aScrollbar, $_sEachScrollbarRules, $_sDelimiter );

            }
                /**
                 * @since   1.3.0
                 * @return  string
                 */
                private function ___getRulesByRanges( $_aScrollbar, $_sEachScrollbarRules, $_sDelimiter ) {

                    $_aCSSRulesByRange = array();
                    $_aRanges          = $this->getElementAsArray( $_aScrollbar, array( 'responsive', 'screen_width_range' ) );
                    foreach( $_aRanges as $_aRange ) {

                        $_aRange = $this->getAsArray( $_aRange ) + array( 1, 0 );

                        // A case that the min is not set but the max is set.
                        if ( ! $_aRange[ 0 ] && $_aRange[ 1 ] ) {
                            $_aCSSRulesByRange[] = " @media only screen and (max-width: {$_aRange[ 1 ]}px) { " . $_sDelimiter
                                    . $_sEachScrollbarRules . $_sDelimiter
                                . " }";
                            continue;
                        }

                        // A case that the both min and max are set.
                        if ( $_aRange[ 0 ] && $_aRange[ 1 ] ) {
                            $_aCSSRulesByRange[] = " @media only screen and (min-width: {$_aRange[ 0 ]}px) and (max-width: {$_aRange[ 1 ]}px) { " . $_sDelimiter
                                    . $_sEachScrollbarRules . $_sDelimiter
                                . " }";
                            continue;
                        }

                        // A case that the min is set but the max is not set.
                        if ( $_aRange[ 0 ] && ! $_aRange[ 1 ] ) {
                            $_aCSSRulesByRange[] = " @media only screen and (min-width: {$_aRange[ 0 ]}px) { " . $_sDelimiter
                                    . $_sEachScrollbarRules . $_sDelimiter
                                . " }";
                        }

                    }
                    return implode( $_sDelimiter, $_aCSSRulesByRange );

                }

                /**
                 * @param   array   $_aScrollbar
                 * @return  string
                 */
                private function ___getRulesForEach( $_aScrollbar, $_sDelimiter ) {

                    $_aEach    = array(
                        "{$_aScrollbar[ 'selector' ]} {"
                    );

                    // y (height)
                    if ( $_aScrollbar[ 'height' ][ 'size' ] ) {
                        $_aEach[] = "max-height: {$_aScrollbar[ 'height' ][ 'size' ]}{$_aScrollbar[ 'height' ][ 'unit' ]};";
                        $_aEach[] = "overflow-y: auto;";
                    } else {
                        $_aEach[] = "overflow-y: hidden;";
                    }

                    // x (width)
                    if ( $_aScrollbar[ 'width' ][ 'size' ] ) {
                        $_aEach[] = "max-width: {$_aScrollbar[ 'width' ][ 'size' ]}{$_aScrollbar[ 'width' ][ 'unit' ]};";
                        $_aEach[] = "white-space: pre-wrap;";
                        $_aEach[] = "overflow-x: auto;";
                    } else {
                        $_aEach[] = "overflow-x: hidden;";
                    }

                    return implode( $_sDelimiter, $_aEach ) . "}";

                }

        /**
         * @return      void
         * @since       1.3.0
         */
        private function ___printCSSRules( $sCSSRules ) {
            $sCSSRules = $this->isDebugMode()
                ? $sCSSRules
                : $this->getCSSMinified( $sCSSRules );
            echo "<style class='custom-scrollbar' type='text/css'>"
                    . $sCSSRules
                 . "</style>";
        }

}