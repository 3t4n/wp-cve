<?php
/**
 * Add generic dynamic css for each block.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
if ( !class_exists( 'Wpmagazine_Modules_Lite_Dynamic_AllCss' ) ) :

    class Wpmagazine_Modules_Lite_Dynamic_AllCss {
        /**
         * 
         * Option name
         *
         * @access private
         */
        public $option_name = "wpmagazine_modules_lite_category_options";

        /**
         * Collect css.
         * 
         * @return "string"
         */
        public function category_parsed_css() {
            $categories_option = get_theme_mod( $this->option_name, $this->get_defaults() );
            $css = '';
            $categories = get_categories();
            foreach( $categories as $category ) {
                $cat_id     = $category->cat_ID;
                $cat_slug   = $category->slug;
                $background_color = isset( $categories_option[ "cvmm_category_".esc_html( $cat_slug )."_background_color" ] ) ? $categories_option[ "cvmm_category_".esc_html( $cat_slug )."_background_color" ] : '';
                $background_hover_color = isset( $categories_option[ "cvmm_category_".esc_html( $cat_slug )."_background_hover_color" ] ) ? $categories_option[ "cvmm_category_".esc_html( $cat_slug )."_background_hover_color" ] : '';
                $background_color = isset( $categories_option[ "cvmm_category_".esc_html( $cat_slug )."_background_color" ] ) ? $categories_option[ "cvmm_category_".esc_html( $cat_slug )."_background_color" ] : '';
                $background_hover_color = isset( $categories_option[ "cvmm_category_".esc_html( $cat_slug )."_background_hover_color" ] ) ? $categories_option[ "cvmm_category_".esc_html( $cat_slug )."_background_hover_color" ] : '';

                if ( !empty( $background_color ) ) {
                    $css .= ".cvmm-cats-wrapper .cvmm-cat-count.cvmm-cat-" .esc_attr( $cat_id ). " { background: ".esc_attr( $background_color )."}\n";

                    $css .= ".cvmm-block-post-grid--layout-one .cvmm-post-cats-wrap .cvmm-post-cat.cvmm-cat-" .esc_attr( $cat_id ). " a{ background: ".esc_attr( $background_color )."}\n";
   
                    $css .= ".cvmm-post-tiles-block-main-content-wrap .cvmm-post-cat.cvmm-cat-" .esc_attr( $cat_id ). " a{ background: ".esc_attr( $background_color )."}\n";

                    $css .= ".cvmm-block-post-carousel--layout-one .cvmm-cat-" .esc_attr( $cat_id ). " a{ background: ".esc_attr( $background_color )."}\n";
                    
                    $css .= ".cvmm-block-post-block--layout-one .cvmm-cat-" .esc_attr( $cat_id ). " a{ background: ".esc_attr( $background_color )."}\n";

                    $css .= ".cvmm-block-post-block--layout-two .cvmm-post-cat.cvmm-cat-" .esc_attr( $cat_id ). ":before{ background: ".esc_attr( $background_color )."}\n";

                    $css .= ".cvmm-block-post-filter--layout-one .cvmm-cat-" .esc_attr( $cat_id ). " a{ background: ".esc_attr( $background_color )."}\n";
                }
            
                if ( !empty( $background_hover_color ) ) {
                    $css .= ".cvmm-block-post-grid--layout-one .cvmm-post-cat.cvmm-cat-" .esc_attr( $cat_id ). " a:hover{ background: ".esc_attr( $background_hover_color )."}\n";

                    $css .= ".cvmm-post-tiles-block-main-content-wrap .cvmm-post-cat.cvmm-cat-" .esc_attr( $cat_id ). " a:hover{ background: ".esc_attr( $background_hover_color )."}\n";

                    $css .= ".cvmm-block-post-carousel--layout-one .cvmm-post-cat.cvmm-cat-" .esc_attr( $cat_id ). " a:hover{ background: ".esc_attr( $background_hover_color )."}\n";

                    $css .= ".cvmm-block-post-block--layout-one .cvmm-post-cat.cvmm-cat-" .esc_attr( $cat_id ). " a:hover{ background: ".esc_attr( $background_hover_color )."}\n";

                    $css .= ".cvmm-block-post-block--layout-two .cvmm-post-cat.cvmm-cat-" .esc_attr( $cat_id ). " a:hover{ color: ".esc_attr( $background_color )."}\n";

                    $css .= ".cvmm-block-post-filter--layout-one .cvmm-post-cat.cvmm-cat-" .esc_attr( $cat_id ). " a:hover{ background: ".esc_attr( $background_hover_color )."}\n";
                }
            }
            $refine_output_css = $this->css_strip_whitespace( $css );
            return apply_filters( 'wpmagazine_modules_lite_category_refined_css', $refine_output_css );
        }

        /**
         * Default options
         * 
         */
        public function get_defaults() {
            $defaults = array();
            $categories = get_categories();
            foreach( $categories as $category ) {
                $defaults[ "cvmm_category_".esc_html( $category->slug )."_background_color" ] = '';
                $defaults[ "cvmm_category_".esc_html( $category->slug )."_background_hover_color" ] = '';
            }
            return apply_filters( 'wpmagazine_modules_lite_category_defaults', $defaults );
        }


        /**
         * Get minified css and removed space
         *
         * @since 1.0.0
         */
        public function css_strip_whitespace( $css ) {
            $replace = array(
                "#/\*.*?\*/#s" => "",  // Strip C style comments.
                "#\s\s+#"      => " ", // Strip excess whitespace.
            );
            $search = array_keys( $replace );
            $css = preg_replace( $search, $replace, $css );

            $replace = array(
                ": "  => ":",
                "; "  => ";",
                " {"  => "{",
                " }"  => "}",
                ", "  => ",",
                "{ "  => "{",
                ";}"  => "}", // Strip optional semicolons.
                ",\n" => ",", // Don't wrap multiple selectors.
                "\n}" => "}", // Don't wrap closing braces.
                "} "  => "}\n", // Put each rule on it's own line.
            );
            $search = array_keys( $replace );
            $css = str_replace( $search, $replace, $css );

            return trim( $css );
        }
    }
    
endif;