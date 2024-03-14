<?php

class Powerfolio_Gutenberg {

    public function __construct() {
        add_action( 'init', array( $this, 'create_block_my_first_block_block_init' ) );
        add_filter( 'register_block_type_args', array( $this, 'portfolio_elementor_update_block_registration' ), 10, 2 );
        add_action( 'enqueue_block_editor_assets', array( $this, 'powerfolio_enqueue_block_editor_assets' ) );

        add_action('rest_api_init', array($this, 'register_rest_routes'));
    }

    public static function pv_validation() {      
       return pe_fs()->can_use_premium_code__premium_only();       
    }

    public static function create_block_my_first_block_block_init() {
        register_block_type( __DIR__ . '/../build/portfolio-block');
        register_block_type( __DIR__ . '/../build/image-gallery-block');
    }

    public static function portfolio_elementor_update_block_registration($args, $block_type) {
        if ('powerfolio/portfolio-block' === $block_type) {
            $args['render_callback'] = 'Powerfolio_Gutenberg::portfolio_block_render';
        }
        else if  ('powerfolio/image-gallery-block' === $block_type) {
            $args['render_callback'] = 'Powerfolio_Gutenberg::image_gallery_block_render';
        }

        return $args;
    }

    // Portfolio Block
    public static function image_gallery_block_render($attributes) {

        $attributes['element_id'] = Powerfolio_Common_Settings::generate_element_id();
        
        $css = self::generate_css_for_block($attributes, $attributes['element_id']); 
        
        return Powerfolio_Image_Gallery::get_image_gallery_template_for_gutenberg($attributes, $css);
    }
    

    // Portfolio Block
    public static function portfolio_block_render($attributes) {

        $element_id = Powerfolio_Common_Settings::generate_element_id();

        $hover = isset($attributes['hover']) ? $attributes['hover'] : 'hover1';
        $columns = isset($attributes['columns']) ? $attributes['columns'] : '3';
        $postsperpage = isset($attributes['postsperpage']) ? $attributes['postsperpage'] : '12';
        $type = $attributes['type'];
        $showfilter = $attributes['showfilter'] ? 'true' : 'false';
        $showallbtn = $attributes['showallbtn'] ? 'true' : 'false';
        $tax_text = isset($attributes['tax_text']) ? $attributes['tax_text'] : '';
        $style = isset($attributes['style']) ? $attributes['style'] : 'box';
        $margin = $attributes['margin'] ? 'true' : 'false';
        $linkto = isset($attributes['linkto']) ? $attributes['linkto'] : 'lightbox';
        $post_type = isset($attributes['post_type']) ? $attributes['post_type'] : 'elemenfolio';
        $taxonomy = isset($attributes['taxonomy']) ? $attributes['taxonomy'] : '';

        // Generate the CSS
        $css = self::generate_css_for_block($attributes, $element_id);               

        foreach ( $taxonomy as $key => $term) {
            $term = get_term_by('slug', $term, 'elemenfoliocategory');
            $taxonomy[$key] = $term->term_id;
        }

        $taxonomy = implode(",", $taxonomy);
    
        return $css.do_shortcode('[powerfolio element_id="'.$element_id.'" hover="' . $hover . '" columns="' . $columns . '" postsperpage="' . $postsperpage . '" type="' . $type . '" showfilter="' . $showfilter . '" showallbtn="' . $showallbtn . '" tax_text="' . $tax_text . '" style="' . $style . '" margin="' . $margin . '" linkto="' . $linkto . '" post_type="' . $post_type . '" taxonomy="' . $taxonomy . '"]');

    }

    public static function generate_css_for_block($attributes, $element_id) {
        
        $css = '';

        $css .= '<style>';

            // BG Color
            if (!empty($attributes['bgColor'])) {
                $css .= '.'.$element_id.' .portfolio-item-infos-wrapper { background-color: '.$attributes['bgColor'].' !important; }';
            }

            // Margin Size
            if (!empty($attributes['margin_size'])) {
                $margin_size = intval($attributes['margin_size']);
                $css .= '.'.$element_id.' .elpt-portfolio-content .portfolio-item-wrapper {';
                    $css .= 'padding-right: calc(5px + '.$margin_size.'px);';
                    $css .= 'padding-left: calc(5px + '.$margin_size.'px);';
                    $css .= 'padding-bottom: calc((5px + '.$margin_size.'px) * 2);';
                $css .= '}';
            }

            // Box Height
            if (!empty($attributes['box_height']) && ($attributes['style'] == 'box' || $attributes['style'] == 'specialgrid5' || $attributes['style'] == 'specialgrid6')) {
                $box_height = intval($attributes['box_height']);
                $css .= '.'.$element_id.' .elpt-portfolio-content.elpt-portfolio-style-box .portfolio-item,';
                $css .= '.'.$element_id.' .elpt-portfolio-content.elpt-portfolio-special-grid-5 .portfolio-item-wrapper,';
                $css .= '.'.$element_id.' .elpt-portfolio-content.elpt-portfolio-special-grid-5 .portfolio-item,';
                $css .= '.'.$element_id.' .elpt-portfolio-content.elpt-portfolio-special-grid-6 .portfolio-item-wrapper,';
                $css .= '.'.$element_id.' .elpt-portfolio-content.elpt-portfolio-special-grid-6 .portfolio-item {';
                    $css .= 'height: '.$box_height.'px;';
                $css .= '}';
            }

            // Text Transform
            if (!empty($attributes['text_transform'])) {
                $css .= '.' . $element_id . ' .portfolio-item-infos-wrapper { text-transform: ' . $attributes['text_transform'] . '; }';
            }

            // Text Align
            if (!empty($attributes['text_align'])) {
                $css .= '.' . $element_id . ' .elpt-portfolio-content .portfolio-item-infos-wrapper { text-align: ' . $attributes['text_align'] . '; }';
            }

            // Border Radius
            if (isset($attributes['borderRadius'])) {
                $border_radius = intval($attributes['borderRadius']);
                $css .= '.' . $element_id . ' .elpt-portfolio-content .portfolio-item { border-radius: ' . $border_radius . '%; }';
            }

            // Border Size
            if (!empty($attributes['border_size'])) {
                $css .= '.' . $element_id . ' .elpt-portfolio-content .portfolio-item { border: ' . $attributes['border_size'] . 'px solid ' . $attributes['item_bordercolor'] . '; }';
            }

            // Border Color
            if (!empty($attributes['item_bordercolor'])) {
                $css .= '.' . $element_id . ' .elpt-portfolio-content .portfolio-item { border-color: ' . $attributes['item_bordercolor'] . ' !important; }';
            }

            // Filter: Background color
            if (!empty($attributes['filter_bgcolor'])) {
                $css .= '.' . $element_id . ' .elpt-portfolio-filter .portfolio-filter-item { background-color: ' . $attributes['filter_bgcolor'] . '; }';
            }

            // Filter: Background color (active item)
            if (!empty($attributes['filter_bgcolor_active'])) {
                $css .= '.' . $element_id . ' .elpt-portfolio-filter .portfolio-filter-item.item-active { background-color: ' . $attributes['filter_bgcolor_active'] . '; }';
            }

            // Filter: Text Transform
            if (!empty($attributes['filter_text_transform'])) {
                $css .= '.' . $element_id . ' .elpt-portfolio-filter .portfolio-filter-item { text-transform: ' . $attributes['filter_text_transform'] . '; }';
            }

            // Filter: Border Radius
            if (!empty($attributes['filter_border_radius'])) {
                $css .= '.' . $element_id . ' .elpt-portfolio-filter .portfolio-filter-item { border-radius: ' . $attributes['filter_border_radius'] . '%; }';
            }

        $css .= '</style>';


        return $css;
    }

    public static function powerfolio_enqueue_block_editor_assets() {

        // Enqueue general scripts for editor
        Powerfolio_Portfolio::enqueue_scripts();

        // Custom JS for Gutenberg editor screen
		wp_enqueue_script( 'elpt-portfoliojs-gutenberg', plugin_dir_url( __DIR__ ).'assets/js/custom-portfolio-gutenberg.js', array('jquery'), '1', true );
    

        // Enqueue your block script
        wp_enqueue_script(
            'portfolio-block-js',
            plugins_url( '../build/portfolio-block/index.js', __FILE__ ),
            array( 'wp-blocks', 'wp-element', 'wp-editor' ),
            filemtime( plugin_dir_path( __FILE__ ) . '../build/portfolio-block/index.js' ),
            true
        );
    
        // Localize your block script
        $hover_options = Powerfolio_Common_Settings::get_hover_options();
        $column_options = Powerfolio_Common_Settings::get_column_options();
        $column_mobile_options = Powerfolio_Common_Settings::get_column_mobile_options();
        $style_options = Powerfolio_Common_Settings::get_grid_options();
        $link_to_options = Powerfolio_Common_Settings::get_lightbox_options();
    
        wp_localize_script(
            'portfolio-block-js',
            'powerfolioBlockData',
            array(
                'hoverOptions' => $hover_options,
                'columnOptions' => $column_options,
                'columnMobileOptions' => $column_mobile_options,
                'styleOptions' => $style_options,
                'linkToOptions' => $link_to_options,
                'isProVersion' => Powerfolio_Gutenberg::pv_validation(),
                'upgradeMessage' => Powerfolio_Common_Settings::get_upgrade_message(),
            )
        );
    }

    public function register_rest_routes() {

        register_rest_route('powerfolio/v1', '/get-post-types', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_post_types_callback'),
            'permission_callback' => '__return_true', // Add this line
        ));

        register_rest_route("powerfolio/v1", "/get-portfolio-taxonomy-terms", array(
            "methods" => "GET",
            "callback" => array($this, "get_portfolio_taxonomy_terms"),
            "permission_callback" => function () {
              return current_user_can("edit_posts");
            },
        ));

    }

    public function get_post_types_callback() {
        $post_types = Powerfolio_Common_Settings::get_post_types();        

        $response = [];
    
        // convert to array
        foreach ($post_types as $key => $post_type) {

            $response[] = [
                'name' => $key,
                'label' => $post_type,
            ];            
        }
    
        return new WP_REST_Response($response, 200);
    }  
    
    public function get_portfolio_taxonomy_terms() {
        $terms = Powerfolio_Common_Settings::get_portfolio_taxonomy_terms();
    
        $response = [];
    
        // Convert terms to an array with 'name' and 'label' keys
        foreach ($terms as $term_slug) {
            $term = get_term_by('slug', $term_slug, 'elemenfoliocategory');
            $response[] = [
                'name' => $term->slug,
                'label' => $term->slug,
            ];
        }
    
        return new WP_REST_Response($response, 200);
    }

}

// Instantiate the class
new Powerfolio_Gutenberg();