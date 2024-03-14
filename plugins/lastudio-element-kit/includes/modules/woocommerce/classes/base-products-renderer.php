<?php
namespace LaStudioKitThemeBuilder\Modules\Woocommerce\Classes;

use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Base_Products_Renderer extends \WC_Shortcode_Products {

    protected $settings = [];

    private static $has_init = false;

	const DEFAULT_COLUMNS_AND_ROWS = 4;

	protected function get_limit(){
		$settings = $this->settings;
		$rows = ! empty( $settings['rows'] ) ? $settings['rows'] : self::DEFAULT_COLUMNS_AND_ROWS;
		$columns = ! empty( $settings['columns'] ) ? $settings['columns'] : self::DEFAULT_COLUMNS_AND_ROWS;

		return intval( $columns * $rows );
	}

	/**
	 * Override original `get_content` that returns an HTML wrapper even if no results found.
	 *
	 * @return string Products HTML
	 */
	public function get_content() {

		$layout = !empty($this->settings['layout']) ? $this->settings['layout'] : 'grid';
		$preset = !empty($this->settings[ $layout.'_style']) ? $this->settings[ $layout.'_style'] : '1';

		$classes = [
            'products',
            'ul_products',
            'lakit-products__list',
            'products-' . $layout,
            'products-' . $layout . '-' . $preset
        ];
        if( lastudio_kit()->get_theme_support('elementor::product-grid-v2') ){
            $classes[] = 'ul_products_v2';
        }
        if(!empty($this->settings['enable_carousel']) && filter_var($this->settings['enable_carousel'], FILTER_VALIDATE_BOOLEAN)){
            $classes[] = 'swiper-wrapper';
        }
        else{
            $classes[] = 'col-row';
        }

		$content = parent::get_content();

        $content = str_replace( '<ul class="products', '<ul class="'.esc_attr(join(' ', $classes)) , $content );

		return $content;
	}

    /**
     * Get wrapper classes.
     *
     * @since  3.2.0
     * @param  int $columns Number of columns.
     * @return array
     */
    protected function get_wrapper_classes( $columns ) {
        $classes = array( 'woocommerce' );

        $classes[] = $this->attributes['class'];

        if(!empty($this->settings['unique_id'])){
            $classes[] = 'lakit_wc_widget_' . $this->settings['unique_id'];
        }
        if( $this->type === 'current_query' || ( isset($this->settings['is_filter_container']) && wc_string_to_bool( $this->settings['is_filter_container'] ) === true ) ){
            $classes[] = 'lakit_wc_widget_current_query';
        }

        return $classes;
    }

    protected function override_hook_to_init(){
        add_action('lastudio-kit/products/before_render', [ $this, 'override_hook' ] );
        add_action( "woocommerce_shortcode_before_{$this->type}_loop", [ $this, 'setup_before_loop' ]);
    }

    public function get_widget_setting( $setting_name, $default = '' ){
        return $this->settings[$setting_name] ?? $default;
    }

    public function setup_before_loop(){
        $layout = !empty($this->settings['layout']) ? $this->settings['layout'] : 'grid';
        $preset = !empty($this->settings[ $layout.'_style']) ? $this->settings[ $layout.'_style'] : '1';

        $allow_extra_filters = false;

        if( !lastudio_kit()->get_theme_support('elementor::product-grid-v2') ) {
            if (!empty($this->settings['allow_order']) && !empty($this->settings['show_result_count']) && $this->settings['allow_order'] === 'yes' && $this->settings['show_result_count'] === 'yes') {
                $allow_extra_filters = true;
            }
        }
        \wc_set_loop_prop('lakit_loop_allow_extra_filters', $allow_extra_filters );

        $enable_carousel = false;
        if(!empty($this->settings['enable_carousel']) && filter_var($this->settings['enable_carousel'], FILTER_VALIDATE_BOOLEAN) && empty($this->settings['lakit_extra_settings']['masonry_settings'])){
            $enable_carousel = true;
        }

        $before = '';

        if(!empty($this->settings['heading'])){
            $html_tag = !empty($this->settings['html_tag']) ? $this->settings['html_tag'] : 'div';
            $html_tag = lastudio_kit_helper()->validate_html_tag($html_tag);
            $before .= sprintf('<div class="clear"></div><%1$s class="lakit-heading"><span>%2$s</span></%1$s>', $html_tag, $this->settings['heading']);
        }

        $container_attributes = [];
        $container_classes = ['lakit-products'];
        $wrapper_classes = ['lakit-products__list_wrapper'];
        $loop_item_classes = [];
        $loop_item_classes[] = 'lakit-product';
        $loop_item_classes[] = 'product_item';

        $carousel_id = '';

        if($enable_carousel){
            $container_classes[] = 'lakit-carousel';
			$enable_swiper_item_auto_width = !empty($this->settings['enable_swiper_item_auto_width']) ? $this->settings['enable_swiper_item_auto_width'] : false;
	        if(filter_var($enable_swiper_item_auto_width, FILTER_VALIDATE_BOOLEAN)){
		        $container_classes[] = 'e-swiper--variablewidth';
	        }
            $carousel_settings = [];
            if(!empty($this->settings['lakit_extra_settings']['carousel_settings'])){
                $carousel_settings = $this->settings['lakit_extra_settings']['carousel_settings'];
            }
            if(!empty($carousel_settings['uniqueID'])){
                $carousel_id = ' id="'.$carousel_settings['uniqueID'].'"';
            }
            $container_attributes[] = 'data-slider_options="'. esc_attr( json_encode($carousel_settings) ) .'"';
            $container_attributes[] = 'dir="'. (is_rtl() ? 'rtl' : 'ltr') .'"';
            $loop_item_classes[] = 'swiper-slide';
        }
        elseif(!empty($this->settings['lakit_extra_settings']['masonry_settings'])){
            $container_classes[] = 'lakit-masonry-wrapper';
            $container_attributes[] = $this->settings['lakit_extra_settings']['masonry_settings'];
        }
        if(!$enable_carousel){
            $loop_item_classes[] = lastudio_kit_helper()->col_new_classes('columns', $this->settings);
        }

        if( $this->type === 'current_query' || ( isset($this->settings['is_filter_container']) && wc_string_to_bool( $this->settings['is_filter_container'] ) === true ) ){
            $container_attributes[] = 'data-widget_current_query="yes"';
            $container_attributes[] = 'data-item_selector="li.product"';
        }

        $before .= '<div class="'.esc_attr( join(' ', $container_classes) ).'" '. join(' ', $container_attributes) .'>';

        if($enable_carousel){
            $before .= '<div class="lakit-carousel-inner">';
            $wrapper_classes[] = 'swiper-container';
        }

        \wc_set_loop_prop('lakit_loop_item_classes', $loop_item_classes );

        $has_masonry_filter = false;

        if(!empty($this->settings['lakit_extra_settings']['masonry_filter'])){
            $before .= $this->settings['lakit_extra_settings']['masonry_filter'];

            $has_masonry_filter = true;
        }

        $before .= '<div class="'.esc_attr(join(' ', $wrapper_classes)).'"'. $carousel_id .'>';

        \wc_set_loop_prop('lakit_loop_before', $before );
        \wc_set_loop_prop('lakit_has_masonry_filter', $has_masonry_filter );

        $after = '</div>';
        if($enable_carousel){
            $after .= '</div>';
            if(!empty($this->settings['lakit_extra_settings']['carousel_dot_html'])){
                $after .= $this->settings['lakit_extra_settings']['carousel_dot_html'];
            }
            if(!empty($this->settings['lakit_extra_settings']['carousel_arrow_html'])){
                $after .= $this->settings['lakit_extra_settings']['carousel_arrow_html'];
            }
            if(!empty($this->settings['lakit_extra_settings']['carousel_scrollbar_html'])){
                $after .= $this->settings['lakit_extra_settings']['carousel_scrollbar_html'];
            }
        }
        $after .= '</div>';

        \wc_set_loop_prop('lakit_loop_after', $after );

        \wc_set_loop_prop('lakit_layout', $layout);
        \wc_set_loop_prop('lakit_preset', $preset);
        \wc_set_loop_prop('lakit_type', $this->type );
        \wc_set_loop_prop('lakit_enable_carousel', $enable_carousel );

        $item_html_tag = !empty($this->settings['item_html_tag']) ? $this->settings['item_html_tag'] : 'h2';
        \wc_set_loop_prop('lakit_item_html_tag', $item_html_tag );

        $image_size = 'woocommerce_thumbnail';
        $enable_alt_image = false;
        $alt_image_as_slide = false;
        $enable_custom_image_size = !empty($this->settings['enable_custom_image_size']) && filter_var($this->settings['enable_custom_image_size'], FILTER_VALIDATE_BOOLEAN);

        if($enable_custom_image_size && !empty($this->settings['image_size'])){
            $image_size = $this->settings['image_size'];
        }
        if(!empty($this->settings['enable_alt_image']) && filter_var( $this->settings['enable_alt_image'], FILTER_VALIDATE_BOOLEAN )){
            $enable_alt_image = true;
            if( isset($this->settings['alt_image_as_slide']) && filter_var( $this->settings['alt_image_as_slide'], FILTER_VALIDATE_BOOLEAN ) ){
                $alt_image_as_slide = true;
            }
        }

        \wc_set_loop_prop('lakit_enable_alt_image', $enable_alt_image );
        \wc_set_loop_prop('lakit_alt_image_as_slide', $alt_image_as_slide );
        \wc_set_loop_prop('lakit_image_size', $image_size );

        if( lastudio_kit()->get_theme_support('elementor::product-grid-v2') ) {
            \wc_set_loop_prop('lakit_v2_settings', $this->settings['lakit_v2_settings'] );
        }

        \wc_set_loop_prop('lakit_unique_id', $this->settings['unique_id']);
        \wc_set_loop_prop('lakit_paginate', $this->settings['paginate']);

        if( !lastudio_kit()->get_theme_support('elementor::product-grid-v2') ) {
            \wc_set_loop_prop('lakit_allow_order', $this->settings['allow_order']);
            \wc_set_loop_prop('lakit_show_result_count', $this->settings['show_result_count']);
        }
        \wc_set_loop_prop('lakit_paginate_as_loadmore', $this->settings['paginate_as_loadmore']);
        \wc_set_loop_prop('lakit_loadmore_text', $this->settings['loadmore_text']);
        \wc_set_loop_prop('lakit_paginate_infinite', isset($this->settings['paginate_infinite']) ? $this->settings['paginate_infinite'] : '');

//        \wc_set_loop_prop('is_filtered', \is_filtered());
    }

    public function override_hook(){
        if( !self::$has_init ){
            self::$has_init = true;
            $this->override_loop_hook();
        }
    }

    private function override_loop_hook_v1(){
        add_action('lastudio-kit/products/action/shop_loop_item_action_top', 'woocommerce_template_loop_add_to_cart', 10);
        add_action('lastudio-kit/products/action/shop_loop_item_action', 'woocommerce_template_loop_add_to_cart', 10);

        remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open');
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
        remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

        add_action('woocommerce_before_shop_loop_item', [$this, 'loop_item_open'], -1001);
        add_action('woocommerce_after_shop_loop_item', [$this, 'loop_item_close'], 1001);

        add_action('woocommerce_before_shop_loop_item_title', [$this, 'loop_item_thumbnail_open'], -1001);
        add_action('woocommerce_before_shop_loop_item_title', [$this, 'loop_item_thumbnail_close'], 1001);

        add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', -101);
        add_action('woocommerce_before_shop_loop_item_title', [$this, 'add_product_thumbnails_to_loop'], 15);
        add_action('woocommerce_before_shop_loop_item_title', [$this, 'loop_item_thumbnail_overlay'], 100);
        add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 101);

        add_action('woocommerce_shop_loop_item_title', [$this, 'loop_item_info_open'], -101);
        add_action('woocommerce_shop_loop_item_title', [$this, 'loop_item_add_product_title'], 10);
        add_action('woocommerce_after_shop_loop_item', [$this, 'loop_item_info_close'], 101);

        remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
        add_action('woocommerce_after_shop_loop', [$this, 'override_wc_pagination'], 10);
    }

    private function override_loop_hook_v2(){
        remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open');
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
        remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price');
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);


        add_action('woocommerce_before_shop_loop_item', [$this, 'loop_item_open'], -1001);
        add_action('woocommerce_after_shop_loop_item', [$this, 'loop_item_close'], 1001);

        add_action('woocommerce_before_shop_loop_item_title', [$this, 'loop_item_thumbnail_open'], -1001);
        add_action('woocommerce_before_shop_loop_item_title', [$this, 'loop_item_thumbnail_close'], 1001);

        add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', -101);
        add_action('woocommerce_before_shop_loop_item_title', [$this, 'add_product_thumbnails_to_loop'], 15);
        add_action('woocommerce_before_shop_loop_item_title', [$this, 'loop_item_thumbnail_overlay'], 100);
        add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 101);

        add_action('woocommerce_shop_loop_item_title', [$this, 'loop_item_info_open'], -101);
        add_action('woocommerce_shop_loop_item_title', [$this, 'add_zone_content'], 10);
        add_action('woocommerce_after_shop_loop_item', [$this, 'loop_item_info_close'], 101);

        remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
        add_action('woocommerce_after_shop_loop', [$this, 'override_wc_pagination'], 10);
    }

    private function override_loop_hook(){
        if( ! lastudio_kit()->get_theme_support('lastudio-kit-woo::product-loop') ){
            if( lastudio_kit()->get_theme_support('elementor::product-grid-v2') ) {
                $this->override_loop_hook_v2();
            }
            else{
                $this->override_loop_hook_v1();
            }
        }
    }

    public function loop_item_open(){
        echo '<div class="product_item--inner">';
    }
    public function loop_item_close(){
        echo '</div>';
    }
    public function loop_item_thumbnail_open(){
        echo '<div class="product_item--thumbnail">';
            echo '<div class="product_item--thumbnail-holder">';
    }
    public function loop_item_thumbnail_close(){
            echo '</div>';
            if( !lastudio_kit()->get_theme_support('elementor::product-grid-v2') ) {
                echo '<div class="product_item_thumbnail_action product_item--action">';
                    echo '<div class="wrap-addto">';
                    do_action('lastudio-kit/products/action/shop_loop_item_action_top');
                    echo '</div>';
                echo '</div>';
            }
            else{
                $this->add_zone_image();
            }
        echo '</div>';
    }

    public function loop_item_thumbnail_overlay(){
        echo '<span class="item--overlay"></span>';
    }

    public function loop_item_info_open(){
        echo '<div class="product_item--info">';
        if( !lastudio_kit()->get_theme_support('elementor::product-grid-v2') ) {
            echo '<div class="product_item--info-inner">';
        }
    }
    public function loop_item_info_close(){
        if( !lastudio_kit()->get_theme_support('elementor::product-grid-v2') ) {
        echo '</div>';
            echo '<div class="product_item--info-action product_item--action">';
                echo '<div class="wrap-addto">';
                do_action('lastudio-kit/products/action/shop_loop_item_action');
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
    public function loop_item_add_product_title(){
        $html_tag = wc_get_loop_prop('lakit_item_html_tag', 'h2');
        $html_tag = lastudio_kit_helper()->validate_html_tag($html_tag);
        the_title( sprintf( '<%2$s class="product_item--title"><a href="%1s">', esc_url( get_the_permalink() ), $html_tag ), sprintf('</a></%1$s>', $html_tag) );
    }

    public function add_product_thumbnails_to_loop(){

        if( isset($_GET['render_mode']) && $_GET['render_mode'] == 'screenshot' ){
            echo '<div class="lakit-embla__slide figure__object_fit p_img-first"></div>';
            return '';
        }

        $image_size = wc_get_loop_prop('lakit_image_size', 'woocommerce_thumbnail');
        $enable_alt_image = wc_get_loop_prop('lakit_enable_alt_image', false);
        $alt_image_as_slide = wc_get_loop_prop('lakit_alt_image_as_slide', false);

        global $product;

        $output = '<div class="lakit-embla__slide figure__object_fit p_img-first">'.\woocommerce_get_product_thumbnail( $image_size ).'</div>';
        if($enable_alt_image){
            $gallery_image_ids = $product->get_gallery_image_ids();
            if($alt_image_as_slide){
                $main_image_url = wp_get_attachment_image_url( $product->get_image_id(), $image_size);
                if(!$main_image_url){
                    $main_image_url = wc_placeholder_img($image_size);
                }
                $thumb_output = sprintf('<div class="lakit-embla__slide thumbitem"><span style="background-image: url(\'%1$s\')"></span></div>', esc_url( $main_image_url ));
                foreach ($gallery_image_ids as $image_id){
                    $image_url = wp_get_attachment_image_url($image_id, $image_size);
                    $output .= sprintf('<div class="lakit-embla__slide figure__object_fit p_img-second-gl"><div style="background-image: url(\'%1$s\')"></div></div>', esc_url( $image_url ));
                    $thumb_output .= sprintf('<div class="lakit-embla__slide thumbitem"><span style="background-image: url(\'%1$s\')"></span></div>', esc_url( $image_url ));
                }
                $arrows = '<span class="lakit-embla__arrow lakit-embla__arrow-prev"><i class="lastudioicon lastudioicon-arrow-left"></i></span><span class="lakit-embla__arrow lakit-embla__arrow-next"><i class="lastudioicon lastudioicon-arrow-right"></i></span>';
                $output = sprintf('<div class="lakit-embla_wrap lakit-embla_wrap--products"><div class="lakit-embla"><div class="lakit-embla__viewport"><div class="lakit-embla__container">%1$s</div></div></div><div class="lakit-embla-thumb"><div class="lakit-embla__viewport"><div class="lakit-embla__container">%2$s</div></div></div>%3$s</div>', $output, $thumb_output, $arrows);
            }
            else{
                if(!empty($gallery_image_ids[0])){
                    $image_url = wp_get_attachment_image_url($gallery_image_ids[0], $image_size);
                    $output .= '<div class="figure__object_fit p_img-second">'. sprintf('<div style="background-image: url(\'%1$s\')"></div>', esc_url( $image_url )) .'</div>';
                }
            }
        }
        echo $output;
    }

    public function override_wc_pagination_args( $args ){

        $type = \wc_get_loop_prop('lakit_type');
        $unique_id = \wc_get_loop_prop('lakit_unique_id');

        if( $type == 'products' && !empty($unique_id) ){
            $page_key = 'product-page-' . $unique_id;
            $args['base'] = esc_url_raw( add_query_arg( $page_key, '%#%', false ) );
            $args['format'] = '?'.$page_key.'=%#%';
        }

        return $args;
    }

    public function override_wc_pagination(){
        add_filter( 'woocommerce_pagination_args', [ $this, 'override_wc_pagination_args' ], 1001  );

        $paginate_as_loadmore = filter_var(wc_get_loop_prop('lakit_paginate_as_loadmore'), FILTER_VALIDATE_BOOLEAN);
        $paginate_infinite = filter_var(wc_get_loop_prop('lakit_paginate_infinite'), FILTER_VALIDATE_BOOLEAN);
        $loadmore_text = wc_get_loop_prop('lakit_loadmore_text');

        ob_start();
        \woocommerce_pagination();
        $output = ob_get_clean();
		if(empty($output)){
			$output .= '<nav class="woocommerce-pagination"></nav>';
		}

        $loadmore_html = '<div class="lakit-ajax-loading-outer"><span class="lakit-css-loader"></span></div>';
        $class_replaced = 'woocommerce-pagination lakit-pagination clearfix lakit-ajax-pagination';
        if($paginate_as_loadmore){
            if( !empty($loadmore_text) ) {
                $load_more_text = $loadmore_text;
            }
            else{
                $load_more_text = esc_html__('Load More', 'lastudio-kit');
            }

            $current_url = add_query_arg( null,null);
            $current_url = remove_query_arg(['_', 'lakitpagedkey', 'lakit-ajax', '_nonce', 'actions'], $current_url);

            $class_replaced .= ' active-loadmore';

            $total_pages= wc_get_loop_prop( 'total_pages' );
            $total_item = wc_get_loop_prop( 'total' );
            $current    = wc_get_loop_prop( 'current_page' );
            $per_page   = wc_get_loop_prop( 'per_page' );
            $last       =  min( $total_item, $per_page * $current );

            $result_count_html = sprintf( _nx( 'Showing %1$d of %2$d result', 'Showing %1$d of %2$d results', $total_item, 'with last result', 'lastudio-kit' ), $last, $total_item );
            $result_count_html = sprintf('<div class="lakit-ajax-result-count">%1$s</div>', $result_count_html);

            $loadmore_html = $result_count_html . '<div class="lakit-ajax-loading-outer"><span class="lakit-css-loader"></span></div><div class="lakit-product__loadmore_ajax lakit-pagination_ajax_loadmore"><a rel="nofollow" href="'.esc_url($current_url).'"><span>'.$load_more_text.'</span></a></div>';

            if($paginate_infinite){
                $class_replaced .= ' active-infinite-loading';
            }

            if($current >= $total_pages){
                $class_replaced .= ' nothingtoshow';
            }

        }

        $output = str_replace('<ul', $loadmore_html . '<ul', $output);
        $output = str_replace('woocommerce-pagination', $class_replaced, $output);
	    $output = str_replace('/page/1/', '/', $output);
        echo $output;
    }

    private function get_setting_zone_content( $zone_name ){
        $loop_props = wc_get_loop_prop('lakit_v2_settings', []);
        return isset( $loop_props[$zone_name] ) ? $loop_props[$zone_name] : [];
    }

    public function add_zone_image(){

        $unique_id = !empty($this->settings['widget_id']) ? $this->settings['widget_id'] : '';

        $zone_1 = $this->get_setting_zone_content('product_image_zone_1');
        $zone_2 = $this->get_setting_zone_content('product_image_zone_2');
        $zone_3 = $this->get_setting_zone_content('product_image_zone_3');

        $zone_1_hide_on = !empty($this->get_setting_zone_content('zone_1_hide_on')) ? ' elementor-hidden-' . join(' elementor-hidden-', $this->get_setting_zone_content('zone_1_hide_on')) : '';
        $zone_2_hide_on = !empty($this->get_setting_zone_content('zone_2_hide_on')) ? ' elementor-hidden-' . join(' elementor-hidden-', $this->get_setting_zone_content('zone_2_hide_on')) : '';
        $zone_3_hide_on = !empty($this->get_setting_zone_content('zone_3_hide_on')) ? ' elementor-hidden-' . join(' elementor-hidden-', $this->get_setting_zone_content('zone_3_hide_on')) : '';

        echo $this->render_button_actions($zone_1, 'lakitp-zone lakitp-zone-a'. $zone_1_hide_on, 'lakit-tooltip-zone-a--id-' . $unique_id );
        echo $this->render_button_actions($zone_2, 'lakitp-zone lakitp-zone-b'. $zone_2_hide_on, 'lakit-tooltip-zone-b--id-' . $unique_id );
        echo $this->render_zone_content($zone_3, 'lakitp-zone lakitp-zone-c'. $zone_3_hide_on, 'lakit-tooltip-zone-c--id-' . $unique_id );
    }

    public function add_zone_content(){
        $unique_id = !empty($this->settings['widget_id']) ? $this->settings['widget_id'] : '';
        $zone_content = $this->get_setting_zone_content('product_content_zone');
        $zone_4_hide_on = !empty($this->get_setting_zone_content('zone_4_hide_on')) ? ' elementor-hidden-' . join(' elementor-hidden-', $this->get_setting_zone_content('zone_4_hide_on')) : '';
        echo $this->render_zone_content($zone_content, 'lakitp-zone lakitp-zone-d'. $zone_4_hide_on, 'lakit-tooltip-zone-d--id-' . $unique_id);
    }

    private function render_button_actions( $zoneSettings, $wrapperClass, $zoneID = '' ){
        $html = '';

        $zoneSettings = apply_filters('lastudio-kit/products/zoneSettings', $zoneSettings);

        foreach ($zoneSettings as $setting){
            $el_class = sprintf('lakitp-zone-item elementor-repeater-item-%1$s %2$s', $setting['_id'], filter_var($setting['only_icon'], FILTER_VALIDATE_BOOLEAN) ? 'only-icon' : '');
            $args = [
                'text' => !empty($setting['item_label']) ? $setting['item_label'] : '',
                'text2' => !empty($setting['item_label2']) ? $setting['item_label2'] : '',
                'icon' => $this->_get_icon_setting($setting['item_icon']),
                'el_class' => $el_class,
                'tip_class' => $zoneID,
            ];
            switch ($setting['item_type']){
                case 'addcart':
                    $html .= $this->v2_loop_add_to_cart($args);
                    break;

                case 'quickview':
                    $html .= $this->v2_loop_quickview($args);
                    break;

                case 'wishlist':
                    $html .= $this->v2_loop_wishlist($args);
                    break;

                case 'compare':
                    $html .= $this->v2_loop_compare($args);
                    break;

                case 'button-toggle':
                    $html .= $this->v2_loop_button_toggle($args);
                    break;
                default:
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', '', $el_class, $setting);
            }
        }
        if(!empty($html)){
            $html = sprintf('<div class="%2$s">%1$s</div>', $html, $wrapperClass);
        }
        return $html;
    }

    private function render_zone_content( $zoneSettings, $wrapperClass, $zoneID = '' ){

        $zoneSettings = apply_filters('lastudio-kit/products/zoneSettings', $zoneSettings);

        $html = '';
        $is_opening = false;
        foreach ($zoneSettings as $setting){
            $el_class = 'elementor-repeater-item-' . $setting['_id'];

            switch ($setting['item_type']){
                case 'row':
                    if($is_opening){
                        $html .= '</div>';
                    }
                    $is_opening = true;
                    $html .= sprintf('<div class="%1$s lakitp-zone-item lakitp-zone-item--row">', $el_class);
                    break;

                case 'product_title':
                    $item_html = $this->v2_loop_title($el_class);
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', $item_html, $el_class, $setting);
                    break;

                case 'product_price':
                    $item_html = $this->v2_loop_price($el_class);
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', $item_html, $el_class, $setting);
                    break;

                case 'product_rating':
                    $item_html = $this->v2_loop_rating($el_class);
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', $item_html, $el_class, $setting);
                    break;

                case 'product_stock':
                    $is_stock_progress = !empty($setting['is_stock_progress']) && filter_var($setting['is_stock_progress'], FILTER_VALIDATE_BOOLEAN) ? 'bar' : 'label';
                    $stock_progress_label = $setting['stock_progress_label'];
                    $item_html = $this->v2_loop_stock($el_class, $this->_get_icon_setting($setting['item_icon'], '<span class="lakitp-zone-item--icon">%s</span>'), $is_stock_progress, $stock_progress_label);
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', $item_html, $el_class, $setting);
                    break;

                case 'product_short_description':
                    $item_html = $this->v2_loop_short_description($el_class);
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', $item_html, $el_class, $setting);
                    break;

                case 'product_tag':
                    $item_html = $this->v2_loop_tags($el_class, $this->_get_icon_setting($setting['item_icon'], '<span class="lakitp-zone-item--icon">%s</span>'));
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', $item_html, $el_class, $setting);
                    break;

                case 'product_category':
                    $item_html = $this->v2_loop_category($el_class, $this->_get_icon_setting($setting['item_icon'], '<span class="lakitp-zone-item--icon">%s</span>'));
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', $item_html, $el_class, $setting);
                    break;

                case 'product_attribute':
                    $item_html = $this->v2_loop_attribute($el_class);
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', $item_html, $el_class, $setting);
                    break;

                case 'product_countdown':
                    $item_html = $this->v2_loop_countdown($el_class, [
                        'days' => $this->get_widget_setting('countdown_label_day'),
                        'hours' => $this->get_widget_setting('countdown_label_hour'),
                        'minutes' => $this->get_widget_setting('countdown_label_minute'),
                        'seconds' => $this->get_widget_setting('countdown_label_second'),
                    ]);
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', $item_html, $el_class, $setting);
                    break;

                case 'custom_field':
                    $item_html = $this->v2_loop_custom_field($el_class, $this->_get_icon_setting($setting['item_icon'], '<span class="lakitp-zone-item--icon">%s</span>'), $setting['item_fname']);
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', $item_html, $el_class, $setting);
                    break;

                case 'product_action':
                    $item_html = $this->v2_loop_actions( $el_class, $zoneID );
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', $item_html, $el_class, $setting);
                    break;

                case 'product_author':
                    $item_html = $this->v2_loop_author($el_class, $this->_get_icon_setting($setting['item_icon'], '<span class="lakitp-zone-item--icon">%s</span>'));
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', $item_html, $el_class, $setting);
                    break;

                case 'shipping_class':
                    $item_html = $this->v2_loop_shipping_class($el_class, $this->_get_icon_setting($setting['item_icon'], '<span class="lakitp-zone-item--icon">%s</span>'));
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', $item_html, $el_class, $setting);
                    break;

                default:
                    $html .= apply_filters('lastudio-kit/products/zone_item_html', '', $el_class, $setting);
            }
        }
        if($is_opening){
            $html .= '</div>';
            $is_opening = false;
        }
        if(!empty($html)){
            $html = sprintf('<div class="%2$s">%1$s</div>', $html, $wrapperClass);
        }
        return $html;
    }

    private function _get_icon_setting($setting = null, $format = '%s', $icon_class = '', $echo = false) {
        $icon_html = '';

        $attr = array('aria-hidden' => 'true');

        if (!empty($icon_class)) {
            $attr['class'] = $icon_class;
        }

        if (!empty($setting)) {
            ob_start();
            Icons_Manager::render_icon($setting, $attr);
            $icon_html = ob_get_clean();
        }

        if (empty($icon_html)) {
            return '';
        }

        if (!$echo) {
            return sprintf($format, $icon_html);
        }

        printf($format, $icon_html);

    }

    private function v2_loop_button_toggle( $args = [] ){
        global $product;
        $button_text = !empty( $args['text'] ) ? $args['text'] : '';
        $button_icon = !empty( $args['icon'] ) ? sprintf('<span class="lakit-btn--icon">%1$s</span>', $args['icon']) : '';
        $button_class = !empty( $args['el_class'] ) ? $args['el_class'] : '';
        $button_class .= ' lakit-btn button btn-toggle';

        if(!empty($button_icon)){
            $button_class .= ' lakit--hint';
        }

        return apply_filters('lastudio-kit/products/loop/toggle-button', sprintf(
            '<a class="%1$s" href="%2$s" data-hint="%3$s" rel="nofollow">%4$s</a>',
            esc_attr($button_class),
            esc_url( $product->get_permalink() ),
            esc_attr($button_text),
            $button_icon
        ), $product, $args);
    }

    private function v2_loop_add_to_cart( $args = [] ){
        global $product;
        if ( !$product ) {
            return '';
        }
        $defaults = array(
            'quantity'   => 1,
            'class'      => implode(
                ' ',
                array_filter(
                    array(
                        'button',
                        'product_type_' . $product->get_type(),
                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                        $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
                    )
                )
            ),
            'attributes' => array(
                'data-product_id'  => $product->get_id(),
                'data-product_sku' => $product->get_sku(),
                'aria-label'       => $product->add_to_cart_description(),
                'rel'              => 'nofollow',
                'data-tip-class'   => $args['tip_class'],
            ),
        );

        $args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );

        if ( isset( $args['attributes']['aria-label'] ) ) {
            $args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
        }

        $button_text = !empty( $args['text'] ) ? $args['text'] : $product->add_to_cart_text();
        $button_icon = !empty( $args['icon'] ) ? sprintf('<span class="lakit-btn--icon">%1$s</span>', $args['icon']) : '';
        $button_class = !empty( $args['el_class'] ) ? $args['el_class'] : '';
        $button_class .= !empty( $args['class'] ) ? ' '.$args['class'] : '';

        if(!empty($button_icon)){
            $button_class .= ' lakit--hint';
        }
        $button_class = apply_filters('lastudio-kit/products/loop/addcart-button/class', $button_class);

        return apply_filters( 'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
            sprintf(
                '<a href="%1$s" data-quantity="%2$s" class="lakit-btn la-addcart %3$s" %4$s data-hint="%5$s">%7$s<span class="lakit-btn--text">%6$s</span></a>',
                esc_url( $product->add_to_cart_url() ),
                esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                esc_attr( $button_class ),
                isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                esc_attr($button_text),
                esc_html($button_text),
                $button_icon
            ),
            $product, $args );
    }

    private function v2_loop_wishlist( $args = [] ){
        global $product;

        $button_text = !empty( $args['text'] ) ? $args['text'] : esc_html_x('Add to wishlist', 'button text', 'lastudio-kit');
        $button_icon = !empty( $args['icon'] ) ? sprintf('<span class="lakit-btn--icon">%1$s</span>', $args['icon']) : '';
        $button_class = !empty( $args['el_class'] ) ? $args['el_class'] : '';
        $button_class .= ' lakit-btn add_wishlist button la-core-wishlist';

        if(!empty($button_icon)){
            $button_class .= ' lakit--hint';
        }
        $button_class = apply_filters('lastudio-kit/products/loop/wishlist-button/class', $button_class);

        return apply_filters('lastudio-kit/products/loop/wishlist-button', sprintf(
            '<a class="%1$s" href="%2$s" data-hint="%3$s" rel="nofollow" data-product_title="%4$s" data-product_id="%5$s" data-tip-class="%8$s">%7$s<span class="lakit-btn--text">%6$s</span></a>',
            esc_attr($button_class),
            esc_url( $product->get_permalink() ),
            esc_attr($button_text),
            esc_attr($product->get_title()),
            esc_attr($product->get_id()),
            esc_attr($button_text),
            $button_icon,
            $args['tip_class']
        ), $product, $args);
    }

    private function v2_loop_compare( $args = [] ){
        global $product;
        $button_text = !empty( $args['text'] ) ? $args['text'] : esc_html_x('Add to compare', 'button text', 'lastudio-kit');
        $button_icon = !empty( $args['icon'] ) ? sprintf('<span class="lakit-btn--icon">%1$s</span>', $args['icon']) : '';
        $button_class = !empty( $args['el_class'] ) ? $args['el_class'] : '';
        $button_class .= ' lakit-btn add_compare button la-core-compare';
        if(!empty($button_icon)){
            $button_class .= ' lakit--hint';
        }
        $button_class = apply_filters('lastudio-kit/products/loop/compare-button/class', $button_class);

        return apply_filters('lastudio-kit/products/loop/compare-button', sprintf(
            '<a class="%1$s" href="%2$s" data-hint="%3$s" rel="nofollow" data-product_title="%4$s" data-product_id="%5$s" data-tip-class="%8$s">%7$s<span class="lakit-btn--text">%6$s</span></a>',
            esc_attr($button_class),
            esc_url( $product->get_permalink() ),
            esc_attr($button_text),
            esc_attr($product->get_title()),
            esc_attr($product->get_id()),
            esc_attr($button_text),
            $button_icon,
            $args['tip_class']
        ), $product, $args);
    }

    private function v2_loop_quickview( $args = [] ){
        global $product;
        $button_text = !empty( $args['text'] ) ? $args['text'] : esc_html_x('Quickview', 'button text', 'lastudio-kit');
        $button_icon = !empty( $args['icon'] ) ? sprintf('<span class="lakit-btn--icon">%1$s</span>', $args['icon']) : '';
        $button_class = !empty( $args['el_class'] ) ? $args['el_class'] : '';
        $button_class .= ' lakit-btn quickview button la-quickview-button';

        if(!empty($button_icon)){
            $button_class .= ' lakit--hint';
        }
        $button_class = apply_filters('lastudio-kit/products/loop/quickview-button/class', $button_class);

        return apply_filters('lastudio-kit/products/loop/quickview-button', sprintf(
            '<a class="%1$s" href="%2$s" data-href="%8$s" data-hint="%3$s" rel="nofollow" data-product_title="%4$s" data-product_id="%5$s" data-tip-class="%9$s">%7$s<span class="lakit-btn--text">%6$s</span></a>',
            esc_attr($button_class),
            esc_url( $product->get_permalink() ),
            esc_attr($button_text),
            esc_attr($product->get_title()),
            esc_attr($product->get_id()),
            esc_attr($button_text),
            $button_icon,
            esc_url(add_query_arg('product_quickview', $product->get_id(), $product->get_permalink())),
            $args['tip_class']
        ), $product, $args);
    }

    private function v2_loop_title( $elClass = '' ){
        global $product;
        $html_tag = wc_get_loop_prop('lakit_item_html_tag', 'h2');
        $html_tag = lastudio_kit_helper()->validate_html_tag($html_tag);
        return sprintf(
            '<%1$s class="%4$s lakitp-zone-item product_item--title"><a href="%2$s">%3$s</a></%1$s>',
            $html_tag,
            esc_url($product->get_permalink()),
            esc_html($product->get_title()),
            esc_attr($elClass)
        );
    }

    private function v2_loop_price( $elClass = '' ){
        global $product;
        if ( $price_html = $product->get_price_html() ){
            return sprintf(
                '<div class="%2$s lakitp-zone-item product_item--price price">%1$s</div>',
                $price_html,
                esc_attr($elClass)
            );
        }
        else{
            return '';
        }
    }

    private function v2_loop_rating( $elClass = '' ){
        global $product;
        if($rating_html = wc_get_rating_html( $product->get_average_rating() )){
            return sprintf(
                '<div class="%2$s lakitp-zone-item product_item--rating">%1$s</div>',
                $rating_html,
                esc_attr($elClass)
            );
        }
        else{
            return '';
        }
    }

    private function v2_loop_stock( $elClass = '', $icon = '', $type = 'label', $label = ''){
        global $product;

        $label = str_replace(['%1$s', '%2$s'], ['[sold]', '[total]'], $label);

        if($type == 'bar'){
            if(empty($label)){
                $label = __('[sold] sold/ [total] total', 'lastudio-kit');
            }
            $stock_available = ($stock = $product->get_stock_quantity()) ? $stock : 0;
            $stock_sold = ($total_sales = $product->get_total_sales()) ? $total_sales : 0;
            $percentage = ($stock_available > 0 ? round($stock_sold / $stock_available * 100) : 0);
            if($stock_available > 0){
                $bar_label = str_replace(['[sold]', '[total]'], [$stock_sold, $stock_available], $label);
                $stock_bar = sprintf(
                    '<div class="stock_bar"><span class="stock_bar--label">%1$s</span><span class="stock_bar--progress"><span class="stock_bar--progress-val" style="width: %2$s" role="progressbar" aria-valuenow="%3$d" aria-valuemin="0" aria-valuemax="100"></span></span></div>',
                    $bar_label,
                    $percentage . '%',
                    $percentage
                );
                return sprintf(
                    '<div class="%2$s lakitp-zone-item product_item--stock product_item--stock-bar">%1$s</div>',
                    $stock_bar,
                    esc_attr($elClass)
                );
            }
            return '';
        }
        else{
            $stock_html = wc_get_stock_html( $product );
            return sprintf(
                '<div class="%2$s lakitp-zone-item product_item--stock">%1$s</div>',
                $icon . $stock_html,
                esc_attr($elClass)
            );
        }
    }

    private function v2_loop_short_description( $elClass = '' ){
        global $post;
        $short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );
        if ( ! $short_description ) {
            return '';
        }
        return sprintf(
            '<div class="%2$s lakitp-zone-item product_item--short_description">%1$s</div>',
            $short_description,
            esc_attr($elClass)
        );
    }

    private function v2_loop_tags( $elClass = '', $icon = ''){
        global $product;
        $before_html = sprintf('<div class="%1$s lakitp-zone-item product_item--tags">%2$s<span class="zone-term-list">', $elClass, $icon);
        return wc_get_product_tag_list( $product->get_id(), '<span class="cspr">, </span>', $before_html, '</span></div>' );
    }

    private function v2_loop_category( $elClass = '', $icon = ''){
        global $product;
        $before_html = sprintf('<div class="%1$s lakitp-zone-item product_item--category">%2$s<span class="zone-term-list">', $elClass, $icon);
        return wc_get_product_category_list( $product->get_id(), '<span class="cspr">, </span>', $before_html, '</span></div>' );
    }

    private function v2_loop_attribute( $elClass = '' ){
        global $product;
        return apply_filters('lastudio-kit/products/loop/product-attribute', '', $elClass, $product);
    }

    private function v2_loop_countdown( $elClass = '', $labels = [] ){
        global $product;
        $html = '';
        if( $product->is_on_sale() ){
            $sale_price_dates_to = $product->get_date_on_sale_to() && ( $date = $product->get_date_on_sale_to()->getOffsetTimestamp() ) ? $date : '';
            if(!empty($sale_price_dates_to)){
                $now = current_time('timestamp');
                $digit_placeholder = '<span class="lakit-countdown-timer__digit">0</span>';
                $tpl = '<div class="lakit-countdown-timer__item item-%1$s"><div class="lakit-countdown-timer__item-value" data-value="%1$s">%2$s</div><div class="lakit-countdown-timer__item-label">%3$s</div></div>';
                $day = '';
                if($sale_price_dates_to - $now > 86400){
                    $day = sprintf($tpl, 'days', $digit_placeholder, !empty($labels['days']) ? $labels['days'] : esc_html__('Days', 'lastudio-kit'));
                }
                $hour = sprintf($tpl, 'hours', $digit_placeholder, !empty($labels['hours']) ? $labels['hours'] : esc_html__('Hrs', 'lastudio-kit'));
                $min = sprintf($tpl, 'minutes', $digit_placeholder, !empty($labels['minutes']) ? $labels['minutes'] : esc_html__('Mins', 'lastudio-kit'));
                $sec = sprintf($tpl, 'seconds', $digit_placeholder, !empty($labels['seconds']) ? $labels['seconds'] : esc_html__('Secs', 'lastudio-kit'));

                $html = sprintf(
                    '<div class="%1$s lakitp-zone-item product_item--countdown"><div class="lakit-countdown-timer" data-due-date="%2$s" data-show-days="%4$s">%3$s</div></div>',
                    $elClass,
                    $sale_price_dates_to,
                    ($day . $hour . $min . $sec),
                    !empty($day) ? 'yes' : 'no'
                );
            }
        }
        return $html;
    }

    private function v2_loop_custom_field( $elClass = '', $icon = '', $fname = '' ){
        global $product;
        if(empty($fname)){
            return '';
        }
        $f_value = get_post_meta( $product->get_id(), $fname, true );
        if(!empty($f_value)){
            return sprintf(
                '<div class="%2$s lakitp-zone-item product_item--cfield">%1$s</div>',
                $icon . $f_value,
                esc_attr($elClass)
            );
        }
        return '';
    }

    private function v2_loop_actions( $elClass = '', $zoneID = '' ){
        $zone_action = $this->get_setting_zone_content('product_content_buttons');
        return $this->render_button_actions($zone_action, 'lakitp-zone-item product_item--buttons ' . $elClass, $zoneID);
    }

    private function v2_loop_author( $elClass = '', $icon = '' ){
        global $post;
        $author_html = get_the_author();
        return sprintf(
            '<div class="%2$s lakitp-zone-item product_item--author">%1$s</div>',
            $icon . $author_html,
            esc_attr($elClass)
        );
    }

    private function v2_loop_shipping_class( $elClass = '', $icon = '' ){
        global $product;
        $shipping_class = $product->get_shipping_class();
        if( ! empty($shipping_class) ) {
            $term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
            if($term){
                return sprintf(
                    '<div class="%2$s lakitp-zone-item product_item--shipping_class">%1$s</div>',
                    $icon . $term->name,
                    esc_attr($elClass)
                );
            }
        }
        return '';
    }
}
