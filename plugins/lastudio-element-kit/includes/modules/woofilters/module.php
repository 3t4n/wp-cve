<?php
namespace LaStudioKitThemeBuilder\Modules\Woofilters;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Module extends \Elementor\Core\Base\Module {

    public static function is_active() {
        return class_exists( 'woocommerce', false);
    }

    public function get_name() {
        return 'woofilters';
    }

    public function add_shortcode( $attr ){
        $source = $type = $attribute = $inline = $show_count = $price = $query_type = $content = '';
        extract(wp_parse_args( $attr, [
            'source' => '',
            'type' => '',
            'attribute' => '',
            'inline' => '',
            'show_count' => '',
            'price' => '',
            'query_type' => '',
        ] ));
        if(empty($source)){
            return false;
        }

        $show_count = wc_string_to_bool($show_count);
        $inline = wc_string_to_bool($inline);

        switch ( $source ) {
            case 'cat_list':
                $content = $this->render_tax_list('product_cat', $show_count, $inline);
                break;
            case 'tag_list':
                $content = $this->render_tax_list('product_tag', $show_count, $inline);
                break;
            case 'cat_dropdown':
                $content = $this->render_tax_dropdown('product_cat', $show_count);
                break;
            case 'tag_dropdown':
                $content = $this->render_tax_dropdown('product_tag', $show_count);
                break;
            case 'result_count':
                $content = $this->render_result_count();
                break;
            case 'sort_by_dropdown':
                $content = $this->render_sort_by_dropdown();
                break;
            case 'sort_by_list':
                $content = $this->render_sort_by_list();
                break;
            case 'rating':
                $content = $this->render_rating();
                break;
            case 'price_range':
                $content = $this->render_price_range();
                break;
            case 'price_list':
                $content = $this->render_price_list($price);
                break;
            case 'product_attribute':
                $content = $this->render_product_attribute($attribute, $type, $query_type, $show_count, $inline);
                break;
            case 'active_filters':
                $content = $this->render_activate_filters();
                break;
        }
        return $content;
    }

    public function display_shortcode( $content ){
        global $shortcode_tags;

        // Back up current registered shortcodes and clear them all out.
        $orig_shortcode_tags = $shortcode_tags;
        remove_all_shortcodes();

        add_shortcode( 'lakit_woofilter_item', [$this, 'add_shortcode'] );

        // Do the shortcode (only the [lakit_woofilter_item] one is registered).
        $content = do_shortcode( $content, true );

        // Put the original shortcodes back.
        $shortcode_tags = $orig_shortcode_tags;

        return $content;
    }

    public function display_shortcode_in_edit_mode( $content ){
        if(lastudio_kit()->elementor()->editor->is_edit_mode()){
            $content = $this->display_shortcode($content);
        }
        return $content;
    }

    public function repair_wc_query_in_edit_mode( $widget ){
        if( $widget->get_name() == 'wp-widget-woocommerce_layered_nav' || $widget->get_name() == 'lakit-woofilters' ){
            if(empty(WC()->query->get_main_query())){
                $query = $GLOBALS['wp_query'];
                WC()->query->product_query($query);
            }
        }
    }

    public function frontend_enqueue(){
        if( !empty($_GET['elementor-preview']) || lastudio_kit()->elementor()->editor->is_edit_mode() ) {
            wp_enqueue_script('wc-price-slider');
        }
    }

    public function __construct() {
        parent::__construct();
//        add_filter('the_content', [ $this, 'display_shortcode' ], 1001 );
        add_filter('elementor/frontend/the_content', [ $this, 'display_shortcode' ], 1001 );
        add_filter('elementor/widget/render_content', [ $this, 'display_shortcode_in_edit_mode' ], 1001 );
        add_action('elementor/widget/before_render_content', [ $this, 'repair_wc_query_in_edit_mode' ]);
        add_action('wp_enqueue_scripts', [$this, 'frontend_enqueue'], 99 );
    }

    protected function render_tax_list( $tax = '', $show_count = false, $inline = false ){

        $key = $tax . '_kitfilter';

        $output = wp_list_categories([
            'pad_counts' => 1,
            'show_count' => $show_count,
            'hide_empty' => 0,
            'echo' => 0,
            'taxonomy'   => $tax,
            'title_li'   => '',
            'hierarchical'   => !$inline,
            'current_category'   => !empty($_GET[$key]) ? absint($_GET[$key]) : '',
        ]);

        if($show_count){
            $pattern = '/(<\/a>) (\(\d+\))/i';
            $replacement = '${1}<span class="count">${2}</span>';
            $output = preg_replace($pattern, $replacement, $output);
            $output = str_replace(['(', ')'], '', $output);
        }

        $output = str_replace(['current-cat-parent', 'current-cat'], 'active', $output);

        if(!empty($output)){
            $output = '<ul class="lakit-woofilters-ul" data-filter="'.$key.'">'.$output.'</ul>';
        }
        return $output;
    }

    protected function render_tax_dropdown( $tax = '', $show_count = false ){
        $key = $tax . '_kitfilter';
        $output = '<form class="woocommerce-frm-tax" method="get">';
        $output .= wp_dropdown_categories([
            'pad_counts'         => 1,
            'show_count'         => $show_count,
            'hierarchical'       => 1,
            'hide_empty'         => 0,
            'show_uncategorized' => 1,
            'orderby'            => 'name',
            'selected'           => !empty($_GET[$key]) ? absint($_GET[$key]) : '',
            'show_option_none'   => esc_html__( 'Select a category', 'woocommerce' ),
            'option_none_value'  => '',
            'value_field'        => 'id',
            'taxonomy'           => $tax,
            'name'               => $key,
            'echo'               => 0,
            'class'              => 'dropdown_product_tax',
        ]);
        $output .= '<input type="hidden" name="paged" value="1" />';
        $output .= wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page', 'mode_view', 'la_doing_ajax' ), '', true );
        $output .= '</form>';
        return $output;
    }

    protected function render_result_count(){
        ob_start();
        $main_query = \WC_Query::get_main_query();
        if(!empty($main_query)){
            $paginated = ! $main_query->get( 'no_found_rows' );
            $args = array(
                'total'    => $main_query->found_posts,
                'per_page' => $main_query->get('posts_per_page'),
                'current'  => $paginated ? (int) max( 1, $main_query->get( 'paged', 1 ) ) : 1,
            );
            wc_get_template( 'loop/result-count.php', $args );
        }
        return ob_get_clean();
    }

    protected function render_sort_by_dropdown(){
        ob_start();
        $show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
        $catalog_orderby_options = apply_filters(
            'woocommerce_catalog_orderby',
            array(
                'menu_order' => esc_html__( 'Default sorting', 'woocommerce' ),
                'popularity' => esc_html__( 'Sort by popularity', 'woocommerce' ),
                'rating'     => esc_html__( 'Sort by average rating', 'woocommerce' ),
                'date'       => esc_html__( 'Sort by latest', 'woocommerce' ),
                'price'      => esc_html__( 'Sort by price: low to high', 'woocommerce' ),
                'price-desc' => esc_html__( 'Sort by price: high to low', 'woocommerce' ),
            )
        );

        $default_orderby = is_search() ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
        // phpcs:disable WordPress.Security.NonceVerification.Recommended
        $orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby;
        // phpcs:enable WordPress.Security.NonceVerification.Recommended

        if ( is_search() ) {
            $catalog_orderby_options = array_merge( array( 'relevance' => esc_html__( 'Relevance', 'woocommerce' ) ), $catalog_orderby_options );
            unset( $catalog_orderby_options['menu_order'] );
        }

        if ( ! $show_default_orderby ) {
            unset( $catalog_orderby_options['menu_order'] );
        }

        if ( ! wc_review_ratings_enabled() ) {
            unset( $catalog_orderby_options['rating'] );
        }

        if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
            $orderby = current( array_keys( $catalog_orderby_options ) );
        }

        wc_get_template(
            'loop/orderby-kit.php',
            array(
                'catalog_orderby_options' => $catalog_orderby_options,
                'orderby'                 => $orderby,
                'show_default_orderby'    => $show_default_orderby,
            )
        );
        return ob_get_clean();
    }

    protected function render_sort_by_list(){
        $show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
        $catalog_orderby_options = apply_filters(
            'woocommerce_catalog_orderby',
            array(
                'menu_order' => esc_html__( 'Default sorting', 'woocommerce' ),
                'popularity' => esc_html__( 'Sort by popularity', 'woocommerce' ),
                'rating'     => esc_html__( 'Sort by average rating', 'woocommerce' ),
                'date'       => esc_html__( 'Sort by latest', 'woocommerce' ),
                'price'      => esc_html__( 'Sort by price: low to high', 'woocommerce' ),
                'price-desc' => esc_html__( 'Sort by price: high to low', 'woocommerce' ),
            )
        );

        $default_orderby = is_search() ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
        // phpcs:disable WordPress.Security.NonceVerification.Recommended
        $orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby;
        // phpcs:enable WordPress.Security.NonceVerification.Recommended

        if ( is_search() ) {
            $catalog_orderby_options = array_merge( array( 'relevance' => esc_html__( 'Relevance', 'woocommerce' ) ), $catalog_orderby_options );
            unset( $catalog_orderby_options['menu_order'] );
        }

        if ( ! $show_default_orderby ) {
            unset( $catalog_orderby_options['menu_order'] );
        }

        if ( ! wc_review_ratings_enabled() ) {
            unset( $catalog_orderby_options['rating'] );
        }

        if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
            $orderby = current( array_keys( $catalog_orderby_options ) );
        }

        $current_url = $this->get_current_url();

        $output = '<ul class="lakit-woofilters-ul" data-filter="orderby">';
        foreach ($catalog_orderby_options as $id => $name){
            $output .= sprintf(
                '<li%3$s><a href="%1$s">%2$s</a></li>',
                esc_url(add_query_arg('orderby',$id,$current_url)),
                $name,
                $orderby == $id ? ' class="active"' : ''
            );
        }
        $output .= '</ul>';
        return $output;
    }

    protected function render_rating(){
        $found         = false;
        $rating_filter = isset( $_GET['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['rating_filter'] ) ) ) ) : array(); // WPCS: input var ok, CSRF ok, sanitization ok.
        $base_link     = $this->get_current_url();

        $output = '<ul class="lakit-woofilters-ul">';

        for ( $rating = 5; $rating >= 1; $rating-- ) {
            $count = $this->get_filtered_product_count( $rating );
            if ( empty( $count ) ) {
                continue;
            }
            $found = true;
            $link  = $base_link;

            if ( in_array( $rating, $rating_filter, true ) ) {
                $link_ratings = implode( ',', array_diff( $rating_filter, array( $rating ) ) );
            } else {
                $link_ratings = implode( ',', array_merge( $rating_filter, array( $rating ) ) );
            }

            $class       = in_array( $rating, $rating_filter, true ) ? 'active' : '';
            $link        = apply_filters( 'woocommerce_rating_filter_link', $link_ratings ? add_query_arg( 'rating_filter', $link_ratings, $link ) : remove_query_arg( 'rating_filter' ) );
            $rating_html = wc_get_star_rating_html( $rating );
            $count_html  = wp_kses(
                apply_filters( 'woocommerce_rating_filter_count', "{$count}", $count, $rating ),
                array(
                    'em'     => array(),
                    'span'   => array(),
                    'strong' => array(),
                )
            );

            $output .= sprintf( '<li class="%s"><a href="%s"><span class="star-rating">%s</span><span class="count">%s</span></a></li>', esc_attr( $class ), esc_url( $link ), $rating_html, $count_html ); // WPCS: XSS ok.
        }

        $output .= '</ul>';

        if(!$found){
            return '';
        }
        else{
            return $output;
        }
    }

    protected function render_price_range(){
        // If there are not posts and we're not filtering, hide the widget.
        if ( ! WC()->query->get_main_query()->post_count && ! isset( $_GET['min_price'] ) && ! isset( $_GET['max_price'] ) ) { // WPCS: input var ok, CSRF ok.
            return '';
        }

        global $wp;

        wp_enqueue_script( 'wc-price-slider' );

        // Round values to nearest 10 by default.
        $step = max( apply_filters( 'woocommerce_price_filter_widget_step', 10 ), 1 );

        // Find min and max price in current result set.
        $prices    = $this->get_filtered_price();
        $min_price = $prices->min_price;
        $max_price = $prices->max_price;

        // Check to see if we should add taxes to the prices if store are excl tax but display incl.
        $tax_display_mode = get_option( 'woocommerce_tax_display_shop' );

        if ( wc_tax_enabled() && ! wc_prices_include_tax() && 'incl' === $tax_display_mode ) {
            $tax_class = apply_filters( 'woocommerce_price_filter_widget_tax_class', '' ); // Uses standard tax class.
            $tax_rates = \WC_Tax::get_rates( $tax_class );

            if ( $tax_rates ) {
                $min_price += \WC_Tax::get_tax_total( \WC_Tax::calc_exclusive_tax( $min_price, $tax_rates ) );
                $max_price += \WC_Tax::get_tax_total( \WC_Tax::calc_exclusive_tax( $max_price, $tax_rates ) );
            }
        }

        $min_price = apply_filters( 'woocommerce_price_filter_widget_min_amount', floor( $min_price / $step ) * $step );
        $max_price = apply_filters( 'woocommerce_price_filter_widget_max_amount', ceil( $max_price / $step ) * $step );

        // If both min and max are equal, we don't need a slider.
        if ( $min_price === $max_price ) {
            return '';
        }

        $current_min_price = isset( $_GET['min_price'] ) ? floor( floatval( wp_unslash( $_GET['min_price'] ) ) / $step ) * $step : $min_price; // WPCS: input var ok, CSRF ok.
        $current_max_price = isset( $_GET['max_price'] ) ? ceil( floatval( wp_unslash( $_GET['max_price'] ) ) / $step ) * $step : $max_price; // WPCS: input var ok, CSRF ok.

        if ( '' === get_option( 'permalink_structure' ) ) {
            $form_action = remove_query_arg( array( 'page', 'paged', 'product-page', 'la_doing_ajax' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
        } else {
            $form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
        }

        ob_start();

        wc_get_template(
            'content-widget-price-filter.php',
            array(
                'form_action'       => $form_action,
                'step'              => $step,
                'min_price'         => $min_price,
                'max_price'         => $max_price,
                'current_min_price' => $current_min_price,
                'current_max_price' => $current_max_price,
            )
        );
        return ob_get_clean();
    }

    protected function render_price_list( $price_list = '' ){
        if(empty($price_list)){
            return '';
        }
        $price_list = explode( ";", $price_list );
        $min_price_activated = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
        $base_shop_url          = $this->get_current_url();
        $prices = $this->get_filtered_price();
        $min = floor( $prices->min_price );
        $max = ceil( $prices->max_price );
        $params = array();
        foreach ( $_GET as $key => $val ) {
            if ( 'min_price' === $key || 'max_price' === $key || 'la_doing_ajax' === $key ) {
                continue;
            }
            $params[$key] = $val;
        }

        $output = '<ul class="lakit-woofilters-ul">';

        foreach ( $price_list as $price ) {
            $price_attr = explode( "|", $price );

            if ( $price_attr ) {
                $min_price  = isset( $price_attr[0] ) ? $price_attr[0] : '';
                $max_price  = isset( $price_attr[1] ) ? $price_attr[1] : '';
                $skip_max = false;
                $text_price = $min_price ? wc_price( $min_price ) : wc_price( 0 );
                if ( $max_price ) {
                    $text_price .= '<span> - </span>' . wc_price( $max_price );
                }
                else {
                    $text_price .= '<span> + </span>';
                    $skip_max = true;
                }
                if ( $min_price === '' ) {
                    continue;
                }
                $max_price = $max_price ? $max_price : $max;
                if ( $min != $max ) {
                    if ( $min_price > $max ) {
                        continue;
                    }
                } else {
                    if ( $min_price > $max ) {
                        continue;
                    }
                }
                if ( $max_price < $min ) {
                    continue;
                }
                $css_class = '';
                if ( $min_price == $min_price_activated ) {
                    $css_class = 'active';
                }
                $link = $base_shop_url;
                if ( $min_price == $min_price_activated ) {
                    if ( !empty($params) ) {
                        $link = add_query_arg($params, $link);
                    }
                }
                else {
                    $link = add_query_arg(array(
                        'min_price' => $min_price
                    ), $link);

                    if(!$skip_max){
                        $link = add_query_arg(array(
                            'max_price' => $max_price
                        ), $link);
                    }
                    else{
                        $link = remove_query_arg('max_price', $link);
                    }
                    if ( !empty($params) ) {
                        $link = add_query_arg($params, $link);
                    }
                }
                $output .= sprintf( '<li class="%s"><a href="%s">%s</a></li>', esc_attr( $css_class ), esc_url( $link ), $text_price );
            }
        }

        $output .= '</ul>';
        return $output;
    }

    protected function render_product_attribute( $attribute = '', $type = '', $query_type = '', $show_count = false, $inline = false ){
        if(empty($attribute)){
            return '';
        }
        if(empty($query_type)){
            $query_type = 'and';
        }

        $_chosen_attributes = \WC_Query::get_layered_nav_chosen_attributes();
        $taxonomy           = wc_attribute_taxonomy_name($attribute);

        if ( ! taxonomy_exists( $taxonomy ) ) {
            return '';
        }

        $terms = get_terms( $taxonomy, array( 'hide_empty' => '1' ) );

        if ( 0 === count( $terms ) ) {
            return '';
        }

        ob_start();

        $found = false;

        if($type == 'list'){
            $found = $this->layered_nav_list( $terms, $taxonomy, $query_type, $show_count );
        }
        elseif($type == 'swatch'){
            $found = $this->layered_nav_swatches( $terms, $taxonomy, $query_type, $show_count );
        }

        // Force found when option is selected - do not force found on taxonomy attributes.
        if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
            $found = true;
        }

        if(!$found){
            return '';
        }
        return ob_get_clean();
    }

    protected function render_activate_filters(){

        ob_start();

        $_chosen_attributes = \WC_Query::get_layered_nav_chosen_attributes();
        $min_price          = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : 0; // WPCS: input var ok, CSRF ok.
        $max_price          = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : 0; // WPCS: input var ok, CSRF ok.
        $rating_filter      = isset( $_GET['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['rating_filter'] ) ) ) ) : array(); // WPCS: sanitization ok, input var ok, CSRF ok.
        $base_link          = $this->get_current_url();

        if(!empty($_GET['elementor-preview']) || lastudio_kit()->elementor()->editor->is_edit_mode()){
            $min_price = 10;
            $max_price = 20;
        }

        if ( 0 < count( $_chosen_attributes ) || 0 < $min_price || 0 < $max_price || ! empty( $rating_filter ) ) {

            echo '<ul class="lakit-woofilters-ul">';

            // Attributes.
            if ( ! empty( $_chosen_attributes ) ) {
                foreach ( $_chosen_attributes as $taxonomy => $data ) {
                    foreach ( $data['terms'] as $term_slug ) {
                        $term = get_term_by( 'slug', $term_slug, $taxonomy );
                        if ( ! $term ) {
                            continue;
                        }

                        $filter_name    = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );
                        $current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array(); // WPCS: input var ok, CSRF ok.
                        $current_filter = array_map( 'sanitize_title', $current_filter );
                        $new_filter     = array_diff( $current_filter, array( $term_slug ) );

                        $link = remove_query_arg( array( 'add-to-cart', $filter_name ), $base_link );

                        if ( count( $new_filter ) > 0 ) {
                            $link = add_query_arg( $filter_name, implode( ',', $new_filter ), $link );
                        }

                        echo '<li><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'woocommerce' ) . '" href="' . esc_url( $link ) . '"><span>' . esc_html( $term->name ) . '</span><i class="lastudioicon-e-remove"></i></a></li>';
                    }
                }
            }

            if ( $min_price ) {
                $link = remove_query_arg( 'min_price', $base_link );
                /* translators: %s: minimum price */
                echo '<li><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'woocommerce' ) . '" href="' . esc_url( $link ) . '"><span>' . sprintf( esc_html__( 'Min %s', 'woocommerce' ), wc_price( $min_price ) ) . '</span><i class="lastudioicon-e-remove"></i></a></li>'; // WPCS: XSS ok.
            }

            if ( $max_price ) {
                $link = remove_query_arg( 'max_price', $base_link );
                /* translators: %s: maximum price */
                echo '<li><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'woocommerce' ) . '" href="' . esc_url( $link ) . '"><span>' . sprintf( esc_html__( 'Max %s', 'woocommerce' ), wc_price( $max_price ) ) . '</span><i class="lastudioicon-e-remove"></i></a></li>'; // WPCS: XSS ok.
            }

            if ( ! empty( $rating_filter ) ) {
                foreach ( $rating_filter as $rating ) {
                    $link_ratings = implode( ',', array_diff( $rating_filter, array( $rating ) ) );
                    $link         = $link_ratings ? add_query_arg( 'rating_filter', $link_ratings ) : remove_query_arg( 'rating_filter', $base_link );

                    /* translators: %s: rating */
                    echo '<li><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'woocommerce' ) . '" href="' . esc_url( $link ) . '"><span>' . sprintf( esc_html__( 'Rated %s out of 5', 'woocommerce' ), esc_html( $rating ) ) . '</span><i class="lastudioicon-e-remove"></i></a></li>';
                }
            }

            echo '</ul>';
        }
        return ob_get_clean();
    }

    protected function get_filtered_product_count( $rating ) {
        global $wpdb;

        $tax_query  = \WC_Query::get_main_tax_query();
        $meta_query = \WC_Query::get_main_meta_query();

        // Unset current rating filter.
        foreach ( $tax_query as $key => $query ) {
            if ( ! empty( $query['rating_filter'] ) ) {
                unset( $tax_query[ $key ] );
                break;
            }
        }

        // Set new rating filter.
        $product_visibility_terms = wc_get_product_visibility_term_ids();
        $tax_query[]              = array(
            'taxonomy'      => 'product_visibility',
            'field'         => 'term_taxonomy_id',
            'terms'         => $product_visibility_terms[ 'rated-' . $rating ],
            'operator'      => 'IN',
            'rating_filter' => true,
        );

        $meta_query     = new \WP_Meta_Query( $meta_query );
        $tax_query      = new \WP_Tax_Query( $tax_query );
        $meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
        $tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

        $sql  = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) FROM {$wpdb->posts} ";
        $sql .= $tax_query_sql['join'] . $meta_query_sql['join'];
        $sql .= " WHERE {$wpdb->posts}.post_type = 'product' AND {$wpdb->posts}.post_status = 'publish' ";
        $sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

        $search = \WC_Query::get_main_search_query_sql();
        if ( $search ) {
            $sql .= ' AND ' . $search;
        }

        return absint( $wpdb->get_var( $sql ) ); // WPCS: unprepared SQL ok.
    }

    protected function get_filtered_price() {
        global $wpdb;

        $args       = \WC()->query->get_main_query()->query_vars;
        $tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
        $meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

        if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
            $tax_query[] = \WC()->query->get_main_tax_query();
        }

        foreach ( $meta_query + $tax_query as $key => $query ) {
            if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
                unset( $meta_query[ $key ] );
            }
        }

        $meta_query = new \WP_Meta_Query( $meta_query );
        $tax_query  = new \WP_Tax_Query( $tax_query );
        $search     = \WC_Query::get_main_search_query_sql();

        $meta_query_sql   = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
        $tax_query_sql    = $tax_query->get_sql( $wpdb->posts, 'ID' );
        $search_query_sql = $search ? ' AND ' . $search : '';

        $sql = "
			SELECT min( min_price ) as min_price, MAX( max_price ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}
			WHERE product_id IN (
				SELECT ID FROM {$wpdb->posts}
				" . $tax_query_sql['join'] . $meta_query_sql['join'] . "
				WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . $search_query_sql . '
			)';

        $sql = apply_filters( 'woocommerce_price_filter_sql', $sql, $meta_query_sql, $tax_query_sql );

        return $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
    }

    protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
        return wc_get_container()->get( \Automattic\WooCommerce\Internal\ProductAttributesLookup\Filterer::class )->get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type );
    }

    /**
     * Return the currently viewed term ID.
     *
     * @return int
     */
    protected function get_current_term_id() {
        return absint( is_tax() ? get_queried_object()->term_id : 0 );
    }

    /**
     * Return the currently viewed term slug.
     *
     * @return int
     */
    protected function get_current_term_slug() {
        return absint( is_tax() ? get_queried_object()->slug : 0 );
    }

    protected function layered_nav_list( $terms, $taxonomy, $query_type, $show_count ) {
        // List display.

        echo '<ul class="lakit-woofilters-ul">';

        $term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
        $_chosen_attributes = \WC_Query::get_layered_nav_chosen_attributes();
        $found              = false;
        $base_link          = $this->get_current_url();


        foreach ( $terms as $term ) {
            $current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
            $option_is_set  = in_array( $term->slug, $current_values, true );
            $count          = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

            // Skip the term for the current archive.
            if ( $this->get_current_term_id() === $term->term_id ) {
                continue;
            }

            // Only show options with count > 0.
            if ( 0 < $count ) {
                $found = true;
            } elseif ( 0 === $count && ! $option_is_set ) {
                continue;
            }

            $filter_name = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array();
            $current_filter = array_map( 'sanitize_title', $current_filter );

            if ( ! in_array( $term->slug, $current_filter, true ) ) {
                $current_filter[] = $term->slug;
            }

            $link = remove_query_arg( $filter_name, $base_link );

            // Add current filters to URL.
            foreach ( $current_filter as $key => $value ) {
                // Exclude query arg for current term archive term.
                if ( $value === $this->get_current_term_slug() ) {
                    unset( $current_filter[ $key ] );
                }

                // Exclude self so filter can be unset on click.
                if ( $option_is_set && $value === $term->slug ) {
                    unset( $current_filter[ $key ] );
                }
            }

            if ( ! empty( $current_filter ) ) {
                asort( $current_filter );
                $link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

                // Add Query type Arg to URL.
                if ( 'or' === $query_type && ! ( 1 === count( $current_filter ) && $option_is_set ) ) {
                    $link = add_query_arg( 'query_type_' . wc_attribute_taxonomy_slug( $taxonomy ), 'or', $link );
                }
                $link = str_replace( '%2C', ',', $link );
            }

            if ( $count > 0 || $option_is_set ) {
                $link      = apply_filters( 'woocommerce_layered_nav_link', $link, $term, $taxonomy );
                $term_html = '<a rel="nofollow" href="' . esc_url( $link ) . '">' . esc_html( $term->name ) . '</a>';
            } else {
                $link      = false;
                $term_html = '<span>' . esc_html( $term->name ) . '</span>';
            }

            if($show_count){
                $term_html .= ' ' . apply_filters( 'woocommerce_layered_nav_count', '<span class="count">' . absint( $count ) . '</span>', $count, $term );
            }

            echo '<li class="' . ( $option_is_set ? 'active' : '' ) . '">';
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.EscapeOutput.OutputNotEscaped
            echo apply_filters( 'woocommerce_layered_nav_term_html', $term_html, $term, $link, $count );
            echo '</li>';
        }

        echo '</ul>';

        return $found;
    }

    protected function layered_nav_swatches( $terms, $taxonomy, $query_type, $show_count ) {

        if( ! lastudio_kit()->get_theme_support('lakit-swatches') ){
            return false;
        }

        // List display.

        echo '<ul class="lakit-woofilters-ul">';

        $term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
        $_chosen_attributes = \WC_Query::get_layered_nav_chosen_attributes();
        $found              = false;
        $base_link          = $this->get_current_url();

        foreach ( $terms as $term ) {
            $current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
            $option_is_set  = in_array( $term->slug, $current_values, true );
            $count          = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

            // Skip the term for the current archive.
            if ( $this->get_current_term_id() === $term->term_id ) {
                continue;
            }

            // Only show options with count > 0.
            if ( 0 < $count ) {
                $found = true;
            } elseif ( 0 === $count && ! $option_is_set ) {
                continue;
            }

            $filter_name = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array();
            $current_filter = array_map( 'sanitize_title', $current_filter );

            if ( ! in_array( $term->slug, $current_filter, true ) ) {
                $current_filter[] = $term->slug;
            }

            $link = remove_query_arg( $filter_name, $base_link );

            // Add current filters to URL.
            foreach ( $current_filter as $key => $value ) {
                // Exclude query arg for current term archive term.
                if ( $value === $this->get_current_term_slug() ) {
                    unset( $current_filter[ $key ] );
                }

                // Exclude self so filter can be unset on click.
                if ( $option_is_set && $value === $term->slug ) {
                    unset( $current_filter[ $key ] );
                }
            }

            if ( ! empty( $current_filter ) ) {
                asort( $current_filter );
                $link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

                // Add Query type Arg to URL.
                if ( 'or' === $query_type && ! ( 1 === count( $current_filter ) && $option_is_set ) ) {
                    $link = add_query_arg( 'query_type_' . wc_attribute_taxonomy_slug( $taxonomy ), 'or', $link );
                }
                $link = str_replace( '%2C', ',', $link );
            }

            if ( $count > 0 || $option_is_set ) {
                $link      = apply_filters( 'woocommerce_layered_nav_link', $link, $term, $taxonomy );
                $term_html = '<a rel="nofollow" href="' . esc_url( $link ) . '">';
                $swatch_term = new \LaStudioKitExtensions\Swatches\Classes\Swatch_Term( null, $term->term_id, $taxonomy, false );
                $term_html .= $swatch_term->get_output();
                $term_html .= '</a>';
            } else {
                $link      = false;
                $term_html = '<span>' . esc_html( $term->name ) . '</span>';
            }

            if($show_count){
                $term_html .= ' ' . apply_filters( 'woocommerce_layered_nav_count', '<span class="count">' . absint( $count ) . '</span>', $count, $term );
            }

            echo '<li class="' . ( $option_is_set ? 'active' : '' ) . '">';
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.EscapeOutput.OutputNotEscaped
            echo apply_filters( 'woocommerce_layered_nav_term_html', $term_html, $term, $link, $count );
            echo '</li>';
        }

        echo '</ul>';

        return $found;
    }

    public function get_current_url(){
        $base_shop_url = add_query_arg(null, null);
        $base_shop_url = remove_query_arg(array('page', 'paged', 'mode_view', 'la_doing_ajax'), $base_shop_url);
        return preg_replace( '/\/page\/\d+/', '', $base_shop_url );
    }
}
