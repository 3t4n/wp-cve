<?php

namespace WPT_Ultimate_Divi_Carousel\WooProductCarousel;

/**
 * .
 */
class Fields
{
    protected  $container ;
    protected  $module ;
    /**
     * Constructor.
     */
    public function __construct( $container )
    {
        $this->container = $container;
    }
    
    /**
     * Set the module instance.
     */
    public function set_module( $module )
    {
        $this->module = $module;
    }
    
    /**
     * Get selector
     */
    public function get_selector( $key )
    {
        $selectors = $this->get_selectors();
        return $selectors[$key]['selector'];
    }
    
    /**
     * List of selectors
     */
    public function get_selectors()
    {
        $selectors = [
            'price_wrapper'  => [
            'selector' => "{$this->module->main_css_element} .swiper-container .wpt-image-card-price",
            'label'    => __( 'Price Wrapper', 'ultimate-carousel-for-divi' ),
        ],
            'final_price'    => [
            'selector' => "{$this->module->main_css_element} .swiper-container .wpt-image-card-price ins",
            'label'    => __( 'Final Price', 'ultimate-carousel-for-divi' ),
        ],
            'sale_price'     => [
            'selector' => "{$this->module->main_css_element} .swiper-container .wpt-image-card-price del",
            'label'    => __( 'Retail Price', 'ultimate-carousel-for-divi' ),
        ],
            'rating_wrapper' => [
            'selector' => "{$this->module->main_css_element} .swiper-container .wpt-image-card-rating.woocommerce .woocommerce-product-rating",
            'label'    => __( 'Rating Wrapper', 'ultimate-carousel-for-divi' ),
        ],
            'rating_text'    => [
            'selector' => "{$this->module->main_css_element} .swiper-container .wpt-image-card-rating.woocommerce .woocommerce-product-rating .total-rating",
            'label'    => __( 'Rating Text', 'ultimate-carousel-for-divi' ),
        ],
            'empty_stars'    => [
            'selector' => "{$this->module->main_css_element} .swiper-container .wpt-image-card-rating.woocommerce .star-rating::before",
            'label'    => __( 'Empty Stars', 'ultimate-carousel-for-divi' ),
        ],
            'filled_stars'   => [
            'selector' => "{$this->module->main_css_element} .swiper-container .wpt-image-card-rating.woocommerce .star-rating span::before",
            'label'    => __( 'Filled Stars', 'ultimate-carousel-for-divi' ),
        ],
            'sales_badge'    => [
            'selector' => "{$this->module->main_css_element} .swiper-container span.onsale",
            'label'    => __( 'Sales Badge', 'ultimate-carousel-for-divi' ),
        ],
        ];
        $selectors = $this->container['swiper_divi']->get_selectors( $this->module ) + $selectors;
        return $selectors;
    }
    
    /**
     * Get default for given keys
     */
    public function get_default( $key )
    {
        $defaults = $this->get_defaults();
        return ( isset( $defaults[$key] ) ? $defaults[$key] : '' );
    }
    
    /**
     * Get defaults
     */
    public function get_defaults()
    {
        $defaults = [
            'criteria'                   => 'featured',
            'categories'                 => '',
            'tags'                       => '',
            'orderby'                    => 'name',
            'order'                      => 'ASC',
            'post__in'                   => '',
            'post__not_in'               => '',
            'posts_per_page'             => '12',
            'show_image'                 => 'on',
            'show_title'                 => 'on',
            'show_content'               => 'off',
            'show_rating'                => 'on',
            'show_price'                 => 'on',
            'show_badge'                 => 'on',
            'show_button'                => 'on',
            'show_badge'                 => 'on',
            'show_disc_text_in_badge'    => 'on',
            'badge_text'                 => 'Sale',
            'show_price'                 => 'on',
            'show_ratings'               => 'on',
            'button_text'                => __( 'View Product', 'ultimate-carousel-for-divi' ),
            'star_alignment'             => 'center',
            'star_fill_color'            => '#FEB507',
            'star_empty_color'           => '#cccccc',
            'badge_orientation'          => 'top-right',
            'sales_badge_custom_margin'  => '14px|14px|0|0|false|false',
            'sales_badge_custom_padding' => '14px|14px|14px|14px|true|true',
            'open_url'                   => 'on',
            'card_url_new_window'        => 'off',
        ];
        $defaults += $this->container['swiper_divi']->get_defaults();
        return $defaults;
    }
    
    /**
     * Get module fields
     */
    public function get_fields()
    {
        $fields = $this->container['swiper_divi']->get_fields( $this->module );
        $fields += $this->get_woo_product_fields();
        $fields['admin_label'] = [
            'label'       => __( 'Admin Label', 'ultimate-carousel-for-divi' ),
            'type'        => 'text',
            'description' => __( 'This will change the label of the module in the builder for easy identification.', 'ultimate-carousel-for-divi' ),
        ];
        return $fields;
    }
    
    /**
     * Get fields related to taxonomy
     */
    public function get_woo_product_fields()
    {
        $fields = [];
        $fields['criteria'] = [
            'label'       => esc_html__( 'Listing Criteria', 'ultimate-carousel-for-divi' ),
            'type'        => 'select',
            'options'     => [
            'recent'        => 'Recent Products',
            'featured'      => 'Featured Products',
            'sale'          => 'Sale Products',
            'best_selling'  => 'Best Selling Products',
            'top_rated'     => 'Top Rated Products',
            'custom_filter' => 'Custom Product Filter',
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'wc_products',
            'description' => esc_html__( 'Select the criteria/filter for the product list.', 'ultimate-carousel-for-divi' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'criteria' ),
        ];
        $fields['categories'] = [
            'label'         => esc_html__( 'Product Categories', 'ultimate-carousel-for-divi' ),
            'type'          => 'categories',
            'taxonomy_name' => 'product_cat',
            'post_type'     => 'product',
            'tab_slug'      => 'general',
            'toggle_slug'   => 'wc_products',
            'description'   => esc_html__( 'Select one or more categories.', 'ultimate-carousel-for-divi' ),
            'show_if'       => [
            'criteria' => 'custom_filter',
        ],
            'default'       => $this->get_default( 'categories' ),
        ];
        $fields['tags'] = [
            'label'         => esc_html__( 'Product Tags', 'ultimate-carousel-for-divi' ),
            'type'          => 'categories',
            'taxonomy_name' => 'product_tag',
            'post_type'     => 'product',
            'tab_slug'      => 'general',
            'toggle_slug'   => 'wc_products',
            'description'   => esc_html__( 'Select one or more product tags.', 'ultimate-carousel-for-divi' ),
            'show_if'       => [
            'criteria' => 'custom_filter',
        ],
            'default'       => $this->get_default( 'tags' ),
        ];
        $fields['orderby'] = [
            'label'       => esc_html__( 'Order By', 'ultimate-carousel-for-divi' ),
            'type'        => 'select',
            'options'     => [
            'none'       => 'No Order',
            'ID'         => 'Product ID',
            'author'     => 'Author',
            'title'      => 'Title',
            'name'       => 'Slug',
            'date'       => 'Date',
            'modified'   => 'Last Modified Date',
            'rand'       => 'Random',
            'menu_order' => 'Menu Order',
            'post__in'   => 'ID Order In "Include Products"',
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'wc_products',
            'description' => esc_html__( 'Select ordering criteria', 'ultimate-carousel-for-divi' ),
            'show_if'     => [
            'criteria' => 'custom_filter',
        ],
            'default'     => $this->get_default( 'orderby' ),
        ];
        $fields['order'] = [
            'label'       => esc_html__( 'Order Direction', 'ultimate-carousel-for-divi' ),
            'type'        => 'select',
            'options'     => [
            'ASC'  => 'Ascending',
            'DESC' => 'Descending',
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'wc_products',
            'description' => esc_html__( 'Select either ascending or descending order.', 'ultimate-carousel-for-divi' ),
            'show_if'     => [
            'criteria' => 'custom_filter',
        ],
            'default'     => $this->get_default( 'order' ),
        ];
        $fields['post__in'] = [
            'label'       => esc_html__( 'Include Products By ID', 'ultimate-carousel-for-divi' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'wc_products',
            'description' => esc_html__( 'Enter comma-separated product IDs to include', 'ultimate-carousel-for-divi' ),
            'show_if'     => [
            'criteria' => 'custom_filter',
        ],
            'default'     => $this->get_default( 'post__in' ),
        ];
        $fields['post__not_in'] = [
            'label'       => esc_html__( 'Exclude Products By ID', 'ultimate-carousel-for-divi' ),
            'type'        => 'text',
            'tab_slug'    => 'general',
            'toggle_slug' => 'wc_products',
            'description' => esc_html__( 'Enter comma-separated product IDs to exclude', 'ultimate-carousel-for-divi' ),
            'show_if'     => [
            'criteria' => 'custom_filter',
        ],
            'default'     => $this->get_default( 'post__not_in' ),
        ];
        $fields['posts_per_page'] = [
            'label'          => esc_html__( 'Total Products In Carousel', 'ultimate-carousel-for-divi' ),
            'type'           => 'range',
            'range_settings' => [
            'min'  => -1,
            'max'  => 300,
            'step' => 1,
        ],
            'tab_slug'       => 'general',
            'toggle_slug'    => 'wc_products',
            'description'    => esc_html__( 'Set to "-1" to show all the products. Or any positive integer to show fixed number of products.', 'ultimate-carousel-for-divi' ),
            'validate_unit'  => false,
            'allowed_units'  => [ '' ],
            'default_unit'   => '',
            'default'        => $this->get_default( 'posts_per_page' ),
        ];
        return $fields;
    }
    
    /**
     * Get css fields
     */
    public function get_css_fields()
    {
        $selectors = [];
        foreach ( $selectors as $key => $selector ) {
            $selectors[$key]['selector'] = "html body div#page-container " . $selector['selector'];
        }
        return $selectors;
    }
    
    public function set_advanced_toggles( &$toggles )
    {
        $selectors = $this->get_selectors();
    }
    
    /**
     * Advanced font definition
     */
    public function get_advanced_font_definition( $key )
    {
        return [
            'css' => [
            'main'      => $this->get_selector( $key ),
            'important' => 'all',
        ],
        ];
    }
    
    public function set_advanced_font_definition( &$config, $key )
    {
        $config['fonts'][$key] = $this->get_advanced_font_definition( $key );
    }
    
    /**
     * Get the products based on settings.
     */
    public function get_products( $props )
    {
        $criteria = ( isset( $props['criteria'] ) && $props['criteria'] ? $props['criteria'] : $this->get_default( 'criteria' ) );
        $posts_per_page = ( isset( $props['posts_per_page'] ) && $props['posts_per_page'] ? $props['posts_per_page'] : $this->get_default( 'posts_per_page' ) );
        $order = ( isset( $props['order'] ) && $props['order'] ? $props['order'] : $this->get_default( 'order' ) );
        $orderby = ( isset( $props['orderby'] ) && $props['orderby'] ? $props['orderby'] : $this->get_default( 'orderby' ) );
        $categories = ( isset( $props['categories'] ) && $props['categories'] ? $props['categories'] : $this->get_default( 'categories' ) );
        $products = [];
        switch ( $criteria ) {
            case 'featured':
                $products = $this->container['woo_featured_products']->get( $posts_per_page, $props );
                break;
            case 'recent':
                $products = $this->container['woo_recent_products']->get( $posts_per_page, $props );
                break;
            case 'sale':
                $products = $this->container['woo_sale_products']->get( $posts_per_page, $props );
                break;
            case 'best_selling':
                $products = $this->container['woo_best_selling_products']->get( $posts_per_page, $props );
                break;
            case 'top_rated':
                $products = $this->container['woo_top_rated_products']->get( $posts_per_page, $props );
                break;
            case 'custom_filter':
            default:
                $products = $this->container['woo_custom_filter_products']->get(
                    $posts_per_page,
                    $orderby,
                    $order,
                    $props
                );
                break;
        }
        return $products;
    }

}