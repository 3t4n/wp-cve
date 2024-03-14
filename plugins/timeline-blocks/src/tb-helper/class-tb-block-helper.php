<?php

/**
 * TB Block Helper.
 *
 * @package TB
 */
if (!class_exists('TB_Block_Helper')) {

    /**
     * Class TB_Block_Helper.
     */
    class TB_Block_Helper {

        /**
         * Get Timeline Block CSS
         *
         * @since 1.4.0
         * @param array  $attr The block attributes.
         * @param string $id The selector ID.
         * @return array The Widget List.
         */
        public static function get_post_grid_css($attr, $id) {    // @codingStandardsIgnoreStart
            $defaults = TB_Helper::$block_list['timeline-blocks/tb-timeline-blocks']['attributes'];

            $attr = array_merge($defaults, (array) $attr);

            $selectors = self::get_post_selectors($attr);

            // @codingStandardsIgnoreEnd

            $desktop = TB_Helper::generate_css($selectors, '#tb_post_layouts-' . $id);

            return $desktop;
        }

        /**
         * Get Post Block Selectors CSS
         *
         * @param array $attr The block attributes.
         * @since 1.4.0
         */
        public static function get_post_selectors($attr) {    // @codingStandardsIgnoreStart
            return array(
                " .tb-content-wrap" => array(
                    'padding-left' => $attr['innerSpace'] . "px",
                    'padding-right' => $attr['innerSpace'] . "px",
                    'margin-bottom' => $attr['innerSpace'] . "px",
                ),      
                " .tb-blogpost-2-text" => array(
                    "background" => $attr['designtwoboxbgColor'] . " !important",
                    "padding-left" => $attr['innerSpace'] . "px",
                    "padding-right" => $attr['innerSpace'] . "px",
                ),
                " .tb-timeline-title-wrap" => array(
                    "padding-bottom" => $attr['belowTitleSpace'] . "px",
                ),
                " .tb-is-grid .tb-text, .tb-blogpost-2-text, .tb-is-list .tb-blogpost-byline" => array(
                    "padding-left" => $attr['innerSpace'] . "px",
                    "padding-right" => $attr['innerSpace'] . "px",
                ),
                " .tb-image" => array(
                    "padding-bottom" => $attr['belowImageSpace'] . "px",
                ),
                " .tb-timeline-excerpt a.tb-blogpost-link, div.tb-text-only " => array(
                    "font-size" => $attr['postctafontSize'] . "px",
                    "font-family" => $attr['ctaFontFamily'],
                    "font-weight" => $attr['ctafontWeight'],
                    "color" => $attr['postctaColor'] . " !important",
                    "margin-bottom" => $attr['belowctaSpace'] . "px",
                ),
                " .tb-blogpost-excerpt a.tb-button, .tb-button-view a.tb-button" => array(
                    "border-radius" => '5px',
                    "padding" => '10px 20px',
                    "background-color" => $attr['readmoreBgColor'] . " !important",
                    "font-size" => $attr['postctafontSize'] . "px",
                    "font-family" => $attr['ctaFontFamily'],
                    "font-weight" => $attr['ctafontWeight'],
                    "color" => $attr['postctaColor'] . " !important",
                    "margin-bottom" => $attr['belowctaSpace'] . "px",
                ),
                " .tb-button-view " => array(
                    "margin-bottom" => $attr['belowctaSpace'] . "px",
                ),
                " .tb-timeline-bototm-wrap " => array(
                    "margin-top" => "10px",
                ),
                ".tb-timeline-template1 .tb-timeline-item .tb-timeline-content:before" => array(
                    "border-left-color" => $attr['boxbgColor'] . " !important",
                ),
                ".tb-timeline-template1 .tb-timeline-item:nth-child(even) .tb-timeline-content:before" => array(
                    "border-right-color" => $attr['boxbgColor'] . " !important",
                ),
                ".tb-timeline-template1:before " => array(
                    "background" => $attr['timelineBgColor'] . " !important",
                ),
                " a.tb-layout-1" => array(
                    "background" => $attr['boxbgColor'] . " !important",
                ),

                " .tb-timeline-excerpt p" => array(
                    "font-family" => $attr['excerptFontFamily'],
                    "font-weight" => $attr['excerptFontWeight'],
                    "color" => $attr['postexcerptColor'] . " !important",
                    "font-size" => $attr['postexcerptfontSize'] . "px",
                    "margin-bottom" => $attr['belowexerptSpace'] . "px",
                ),
                " .tb-timeline-title-wrap a" => array(
                    "font-family" => $attr['titleFontFamily'],
                    "font-weight" => $attr['titleFontWeight'],
                    "color" => $attr['titleColor'] . " !important",
                    "font-size" => $attr['titlefontSize'] . "px",

                ),
                " .tb-blogpost-author a, .tb-timeline-post-tags a, .tb-timeline-category-link a, .mdate, .post-comments" => array(
                    "font-family" => $attr['metaFontFamily'] . " !important",
                    "font-weight" => $attr['metafontWeight'] . " !important",
                    "color" => $attr['postmetaColor'] . " !important",
                    "font-size" => $attr['postmetafontSize'] . "px !important",
                    //"text-transform" => "uppercase",
                ),
                " .mdate i, .post-comments i, .post-author i" => array(
                    "font-weight" => $attr['metafontWeight'] . " !important",
                    "color" => $attr['postmetaColor'] . " !important",
                    "font-size" => $attr['postmetafontSize'] . "px !important",
                ),
                " .tb-blogpost-byline div" => array(
                    "font-family" => $attr['metaFontFamily'],
                    "font-weight" => $attr['metafontWeight'],
                    "color" => $attr['postmetaColor'] . " !important",
                    "font-size" => $attr['postmetafontSize'] . "px",
                ),
                " .tb-timeline-social-wrap .social-share-data a" => array(
                    "padding" => '0px 5px',
                    "display" => "table-cell",
                    "vertical-align" => "middle",
                    "color" => $attr['socialShareColor'] . " !important",
                    "font-size" => $attr['socialSharefontSize'] . "px",
                ),
                " .tb-timeline-template2 .tb-timeline-item .timeline-icon, .tb-timeline-template2 .tb-timeline-item .timeline-icon, .tb-timeline-template2:before " => array(
                    "background" => $attr['timelineBgColor'] . " !important",
                ),
                " .timeline-icon path" => array(
                    "fill" => $attr['timelineFgColor'] . " !important",
                ),
                " .tb-svg-icon" => array(
                    "fill" => $attr['timelineFgColor'] . " !important",
                ),
            );
            // @codingStandardsIgnoreEnd
        }

    }

}