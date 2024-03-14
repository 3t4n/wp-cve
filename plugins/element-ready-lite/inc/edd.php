<?php

/**
 * EDD CUSTOM SETUP
 *
 * Learn more: http://docs.easydigitaldownloads.com/
 *
 */

/*---------------------------------
    ADD POST FORMAT FOR EDD
----------------------------------*/
if ( !function_exists('element_ready_edd_setup') ) {
    function element_ready_edd_setup(){

        add_post_type_support( 'download', 'post-formats' );
        add_theme_support( 'post-formats',array(
            'link',
            'audio',
            'image',
            'gallery',
            'video',
        ));
    }
}

add_action( 'after_setup_theme','element_ready_edd_setup' );

/*----------------------------------
    EDD SCRIPTS LOAD.
-----------------------------------*/
function element_ready_edd_scripts() {
    wp_register_style( 'lity', ELEMENT_READY_ROOT_CSS . 'lity.min.css' );
    wp_register_style( 'plyr', ELEMENT_READY_ROOT_CSS . 'plyr.css' );
    wp_register_style( 'edd-active', ELEMENT_READY_ROOT_CSS . 'edd-active.css' );
    wp_register_script( 'lity', ELEMENT_READY_ROOT_JS . 'lity.min.js', array('jquery'), '', true );
    wp_register_script( 'plyr', ELEMENT_READY_ROOT_JS . 'plyr.min.js', array('jquery'), '', true );
}
add_action( 'wp_enqueue_scripts', 'element_ready_edd_scripts' );

if ( class_exists( 'Easy_Digital_Downloads' ) ) :

    /*----------------------------------
        AUTHOR URL
    -----------------------------------*/
    function element_ready_fes_author_url( $author = null, $vendor_custom_url = null ) {
        global $post;
        $author_id = $post->post_author;
        if ( ! $author ) {
            $author = wp_get_current_user();
        }else{
            $author = new WP_User( $author );
        }

        if ( class_exists( 'EDD_Front_End_Submissions' ) && $vendor_custom_url == true ) {
            return EDD_FES()->vendors->get_vendor_store_url( $author->ID );
        }else{
            return add_query_arg( 'author_downloads', 'true', get_author_posts_url( get_the_author_meta('ID',$author_id)) );
        }
    }

    /*--------------------------------------------
        EDD PRODUCT STAR RATING
    ---------------------------------------------*/
    function element_ready_edd_rating( $ratting_count = false, $ratting_text = '' ) { 

        if ( ! function_exists( 'edd_reviews' ) ){
            return;
        }
        ?>
        <?php
            global $wp_query;
            global $post;
            $postID     = $post->ID;
            $starRating = null;
            if( class_exists( 'EDD_Reviews' ) ) {
                $starRating = edd_reviews()->average_rating(false);
                $starRating = (round($starRating*2))*10;
            }
        ?>
        <div title="<?php echo esc_attr($starRating/20);?><?php esc_attr_e('out of 5','element-readey');?>" class="download__star__rating">
            <div class="rating">
                <i class="fa fa-star" data-vote="1"></i>
                <i class="fa fa-star" data-vote="2"></i>
                <i class="fa fa-star" data-vote="3"></i>
                <i class="fa fa-star" data-vote="4"></i>
                <i class="fa fa-star" data-vote="5"></i>
            </div>
            <div class="rated" style="width:<?php echo esc_attr($starRating);?>%">
                <i class="fa fa-star" data-vote="1"></i>
                <i class="fa fa-star" data-vote="2"></i>
                <i class="fa fa-star" data-vote="3"></i>
                <i class="fa fa-star" data-vote="4"></i>
                <i class="fa fa-star" data-vote="5"></i>
            </div>
        </div>
        <?php if( $ratting_count == true ) : ?>
            <div class="count__ratting">
                ( <?php echo esc_html( edd_reviews()->count_reviews() );?><?php if(!empty($ratting_text)){echo esc_html( $ratting_text );} ?> )
            </div>
        <?php endif; ?>
    <?php
    }

    /*-------------------------------------------
        AVARAGE RATING
    --------------------------------------------*/
    function element_ready_avarage_rating() {
        if ( ! function_exists( 'edd_reviews' ) ){
            return;
        }
        
        /*-------------------------------------------
            GET THE AVERAGE RATING FOR THIS DOWNLOAD
        --------------------------------------------*/
        $average_rating = edd_reviews()->average_rating( false );    
        $rating         = $average_rating;
        $ratingclass    = (int) edd_reviews()->average_rating( false );
        ob_start(); ?>
            <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating">
                <div class="edd_reviews_rating_box <?php if ($rating==4.5){  ?>four-half-rating<?php }?> <?php echo esc_attr__( 'stars', 'element-readey' ).$ratingclass; ?>" role="img">
                    <div class="edd_star_rating" aria-label="<?php echo esc_attr( $rating )  . ' ' . esc_attr__( 'stars', 'element-readey' ); ?>">
                        <span class="rating-stars"></span>
                        <span class="rating-stars"></span>
                        <span class="rating-stars"></span>
                        <span class="rating-stars"></span>
                        <span class="rating-stars-last"></span>
                        <p>(<?php echo esc_html( edd_reviews()->count_reviews() );?>)</p>
                    </div>
                </div>
                <div style="display:none" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                    <meta itemprop="worstRating" content="1"/>
                    <span itemprop="ratingValue"><?php echo esc_attr( $rating ); ?></span>
                    <span itemprop="bestRating">5</span>
                </div>
            </div>
        <?php
        $rating_html = ob_get_clean();
        return $rating_html;
    }

    /*--------------------------------------------
        EDD SINGLE TOTAL REVIEW COUNT
    ----------------------------------------------*/
    if( class_exists( 'EDD_Reviews' ) ) {
        function element_ready_edd_count_review() {
            echo wp_kses_post( edd_reviews()->count_reviews() );  
        }
    }

    /*--------------------------------------------
        EDD TOTAL REVIEW COUNT
    ----------------------------------------------*/
    if( class_exists( 'EDD_Reviews' ) ) {
        function element_ready_edd_count_total_review() {
            echo wp_kses_post(edd_reviews()->average_rating(false));  
        }
    }

    /*--------------------------------------------
        EDD VENDOR TOTAL REVIEW COUNT
    ----------------------------------------------*/
    if( class_exists( 'EDD_Reviews' ) ) {
        function element_ready_edd_count_vendor_review() {
            echo wp_kses_post( edd_reviews()->count_reviews() );  
        }
    }

endif;

/*-------------------------------
    REMOVE PREFIX FROM ARCHIVE
---------------------------------*/
function element_ready_remove_archive_prefix($title){
    if ( is_category() ){
        $title = single_cat_title( '', false );
    }elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    }elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>' ;
    }elseif ( is_tax() ) {
        $title = sprintf( __( '%1$s','element-readey' ), single_term_title( '', false ) );
    }elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    }
    return $title;
}
add_filter( 'get_the_archive_title', 'element_ready_remove_archive_prefix' );

/*----------------------------------------------
    Add wrapper class to EDD [download] shortcode
------------------------------------------------*/
function element_ready_edd_download_wrap( $class, $atts ){
    return 'download__default__wrapper download__wrapper ' . $class;
}
add_filter( 'edd_downloads_list_wrapper_class', 'element_ready_edd_download_wrap', 10, 2 );

/*----------------------------------------------
    REMOVE WRAPER SCHIMA
-----------------------------------------------*/
remove_action( 'loop_end', 'edd_microdata_wrapper_close', 10 );
remove_action( 'loop_start', 'edd_microdata_wrapper_open', 10 );

/*-----------------------------------------------
    EDD Single Item Class
-------------------------------------------------*/
if ( !function_exists('element_ready_download_post_class') ) {
   function element_ready_download_post_class( $classes ) {
    
        global $post;
        if ( 'download' === get_post_type() ) {
            if ( is_single() ) {
                $classes[] = 'single__grid__download__item';
            }else{
                $classes[] = 'grid__download__item mb30';
            }
        }
        return $classes;
    }
}
add_filter( 'post_class', 'element_ready_download_post_class' );

/*--------------------------------
    BECOME VENDOR PAGE CLASS
----------------------------------*/
function element_ready_add_body_class($classes){ 
    $query_object = get_queried_object();
     
    if ( !(is_home() || is_front_page()) && 'page' == get_post_type() && isset($query_object->post_content) && '[fes_vendor_dashboard]' == $query_object->post_content ) {
        return array_merge( $classes, array( 'edd__become__vendor' ) );
    }else{
        return $classes; 
    }
}
add_filter( 'body_class','element_ready_add_body_class' );

/*------------------------------------------------
    CHANGE CHECKOUT PAGE IMAGE SIZE
--------------------------------------------------*/
function element_ready_edd_checkout_image_size( $array ) {
    return array( 120, 80 );
}
add_filter( 'edd_checkout_image_size', 'element_ready_edd_checkout_image_size', 10, 1 );

/*-------------------------------------------------
    EDD WISHT LIST
---------------------------------------------------*/
if ( class_exists( 'EDD_Wish_Lists' ) ) {

    function element_ready_remove_favorites() {
        /*remove from default location*/
        remove_action( 'edd_purchase_link_end', 'edd_favorites_load_link' );
        remove_action( 'edd_purchase_link_top', 'edd_favorites_load_link' );
    }
    add_action( 'template_redirect', 'element_ready_remove_favorites' );

    /**
     * Remove standard favorite links
     * @return [type] [description]
     */
    function element_ready_wisthlist_load() {
        /*remove standard add to wish list link*/
        remove_action( 'edd_purchase_link_top', 'edd_favorites_load_link' );

        /*adding new link*/
        add_action( 'edd_purchase_link_top', 'edd_wl_load_wish_list_link' );
    }
    add_action( 'template_redirect', 'element_ready_wisthlist_load' );
}


/*------------------------------------------------
    EDD SALE COUNT
--------------------------------------------------*/
if ( !function_exists( 'element_ready_edd_sale_count' ) ) {
    function element_ready_edd_sale_count(){
        global $post;
        $download_id           = get_the_ID();

        $single_sales_count    = edd_get_download_sales_stats( get_the_ID() );
        $total_sales_count     = $edd_logs->get_log_count('*', 'file_sale');
        $single_sales_count    = $single_sales_count > 1 ? $single_sales_count .  __( ' Sales', 'element-ready-lite' )  : $single_sales_count . __( ' Sale', 'element-ready-lite' );
    }
}

/*----------------------------------------------------
    EDD VARIABLE PRICE DROPDOWN
----------------------------------------------------*/
function element_ready_edd_purchase_variable_pricing( $download_id ) {
    $variable_pricing = edd_has_variable_prices( $download_id );

    if ( ! $variable_pricing )
        return;

    $prices = apply_filters( 'edd_purchase_variable_prices', edd_get_variable_prices( $download_id ), $download_id );
    $type   = edd_single_price_option_mode( $download_id ) ? 'checkbox' : 'radio';

    do_action( 'edd_before_price_options', $download_id );

    echo '<div class="edd_price_options">';
        if ( $prices ) {
            echo '<select>';
            foreach ( $prices as $key => $price ) {
                $price_option = sanitize_text_field($_GET['price_option']);
                echo wp_kses_post( sprintf(
                    '<option id="%3$s" class="%4$s" value="%5$s" %7$s> %6$s</option>',
                    checked( 0, $key, false ),
                    $type,
                    esc_attr( 'edd_price_option_' . $download_id . '_' . $key ),
                    esc_attr( 'edd_price_option_' . $download_id ),
                    esc_attr( $key ),
                    esc_html( $price['name'] . ' - ' . edd_currency_filter( edd_format_amount( $price[ 'amount' ] ) ) ),
                    selected( isset( $price_option ), $key, false )
                ) );
                do_action( 'edd_after_price_option', $key, $price, $download_id );
            }
            echo '</select>';
        }
        do_action( 'edd_after_price_options_list', $download_id, $prices, $type );

    echo '</div>';
    do_action( 'edd_after_price_options', $download_id );
}
add_action( 'edd_purchase_link_top', 'element_ready_edd_purchase_variable_pricing', 10, 1 );

remove_action( 'edd_purchase_link_top', 'edd_purchase_variable_pricing', 10, 1 );

/*---------------------------------------------------
    REMOVE PRICE FROM EDD BUY NOW BUTTON
-----------------------------------------------------*/
function element_ready_remove_price_purchase_button( $options ){
    $options['price'] = false;
    return $options;
}
add_filter( 'edd_purchase_link_defaults', 'element_ready_remove_price_purchase_button' );


/*------------------------------------------------
    CUSTOMIZE DEFAULT SEARCH QUERY
-------------------------------------------------*/
function element_ready_category_search_query( $query ) {
    if( !isset($query->query['post_type']) ){
        return;
    }
    if ( $query->query['post_type'] != 'download' ) {
        return;
    }
    $query_var = (isset( $_GET['download_cats'] )) ? $_GET['download_cats'] : null;

    if ( $query_var == 'all' ) {
        return;
    }

    if ( $query->is_search() && $query->is_main_query() && isset( $_GET['download_cats'] ) ) {
        $taxquery = array(
            array(
                'taxonomy' => 'download_category',
                'field' => 'name',
                'terms' => array( $query_var ),
            )
        );
        $query->set( 'tax_query', $taxquery );
    }
}
add_action( 'pre_get_posts', 'element_ready_category_search_query' );

/*------------------------------------------------
    Remove Microdata From Html
-------------------------------------------------*/
add_filter( 'edd_add_schema_microdata', '__return_false' );


/*--------------------------------
    GET VIMEO VIDEO ID
----------------------------------*/
function element_ready_vimeo_url_id( $url = '' ) {
    $get_id = array();
    $id = '';
    if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $get_id)) {
        $id = $get_id[3];
    }
    return $id;
}

/*--------------------------------
    GET YOUTUBE VIDEO ID
----------------------------------*/
function element_ready_youtube_url_id( $url = '' ) {
    $get_id = array();
    $id = '';
    if (preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $get_id )) {
        $id = $get_id[0];
    }
    return $id;
}

/*--------------------------------
    Default thumbnail change
----------------------------------*/

if( !function_exists( 'element_ready_edd_image_path_att' ) ){

    function element_ready_edd_image_path_att( $data, $id ){

        if(is_admin()){
        return $data;
        }

        global $post; 
        if( isset( $data[ 'file' ] ) && isset($data[ 'sizes' ]['thumbnail']['file'] )){
                if(isset($post->post_type) && $post->post_type =='download') {

                $original_file =  $data[ 'file' ];
                $original_file = explode('/', $data[ 'file' ]);
                $data[ 'sizes' ]['thumbnail']['file'] = end($original_file);
            }
        }
        return $data;
    }
}
  
add_filter( 'wp_get_attachment_metadata', 'element_ready_edd_image_path_att',10,2 );
   
if( !function_exists( 'element_ready_edd_thumbnail_html' ) ){

    function element_ready_edd_thumbnail_html($html, $post_id, $post_thumbnail_id, $size, $attr) {

        if( 'download' !== get_post_type($post_id) ){
            return $html;
        }
        $id    = get_post_thumbnail_id();
        $src   = wp_get_attachment_image_src($id, null);
        $class = isset($attr['class'])?$attr['class'].' element-ready-edd-download er-edd-attachment':'element-edd-download er-edd-attachment';
        $alt   = esc_attr(get_the_title($post_id));
        
        if ( isset($attr['class']) && strpos($class, 'retina') !== false) {
            $html = sprintf('<img src="%s" alt="" data-src="%s" data-alt="%s" class="%s" />', esc_url($src[0]) , esc_url($src[0]) , esc_attr($alt) , esc_attr($class));
        } else {
            $html = sprintf('<img src="%s" alt="%s" class="%s" />' , esc_url($src[0]) , esc_attr($alt) , esc_attr($class) );
        }
        return wp_kses_post( $html );
    }
}
  
add_filter('post_thumbnail_html', 'element_ready_edd_thumbnail_html', 99, 5);