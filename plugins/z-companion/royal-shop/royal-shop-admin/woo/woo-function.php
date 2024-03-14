<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 

if(!function_exists('z_companion_product_query')){
    function z_companion_product_query($term_id,$prdct_optn){
    $limit_product = get_theme_mod('z_companion_prd_shw_no','20');
    // product filter
    $args = array('limit' => $limit_product, 'visibility' => 'catalog');
    if($term_id){
        $term_args = array('hide_empty' => 1,'slug'    => $term_id);
        $product_categories = get_terms( 'product_cat', $term_args);
    $product_cat_slug =  $product_categories[0]->slug;
    $args['category'] = $product_cat_slug;
    }
    if($prdct_optn=='random'){
      $args['orderby'] = 'rand';
    }elseif($prdct_optn=='featured'){
          $args['featured'] = true;
    }
    if(get_option('woocommerce_hide_out_of_stock_items')=='yes'){ 
            $args['stock_status'] = 'instock';
    }
    return $args;
    }
}
/********************************/
//product cat filter loop
/********************************/
function z_companion_product_cat_filter_default_loop($term_id,$prdct_optn){
$args = z_companion_product_query($term_id,$prdct_optn);
    $products = wc_get_products( $args );
    if (!empty($products)) {
    foreach ($products as $product) {
      $pid =  $product->get_id();
      $cate = wc_get_product_category_list($pid);
      $cate = implode(" ",array_slice(explode(",",$cate),0,1));
      ?>
        <div <?php post_class('product'); ?>>
          <div class="wzta-product-wrap">
          <div class="wzta-product">
          <span class="wzta-categories-prod">
            <?php 
           echo wp_kses($cate,array(  'a' => array( 'href' => array(),'title' => array(),'target' => array() ) ));
            ?>
         </span>
            <h2 class="woocommerce-loop-product__title">
                <a href="<?php echo get_permalink($pid); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><?php echo $product->get_title(); ?></a>
              </h2>
               <div class="wzta-product-image">
                <a href="<?php echo get_permalink($pid); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                <?php $sale = get_post_meta( $pid, '_sale_price', true);
                    if( $sale) {
                      // Get product prices
                        $regular_price = (float) $product->get_regular_price(); // Regular price
                        $sale_price = (float) $product->get_price(); // Sale price
                        $saving_price = wc_price( $regular_price - $sale_price );
                        echo $sale = '<span class="onsale">-'.$saving_price.'</span>';
                    }?>
                 <?php 
                      echo get_the_post_thumbnail( $pid, 'large' );
                      $hover_style = get_theme_mod( 'royal_shop_woo_product_animation' );
                         // the_post_thumbnail();
                        if ( 'swap' === $hover_style ){
                                $attachment_ids = $product->get_gallery_image_ids($pid);
                                if(!empty($attachment_ids)){
                             
                                 $glr = wp_get_attachment_image($attachment_ids[0], 'shop_catalog', false, array( 'class' => 'show-on-hover' ));
                                   echo $category_product['glr'] = $glr;

                                 }
                               
                           }
                           if ( 'slide' === $hover_style ){
                                $attachment_ids = $product->get_gallery_image_ids($pid);
                                if(!empty($attachment_ids)){
                             
                                 $glr = wp_get_attachment_image($attachment_ids[0], 'shop_catalog', false, array( 'class' => 'show-on-slide' ));
                                   echo $category_product['glr'] = $glr;

                                 }
                               
                           }
                  ?>
                  </a><div class="wzta-icons-wrap">
                  <?php 
                    if(get_theme_mod( 'royal_shop_woo_quickview_enable', true )){

                  ?>
        <div class="wzta-quik"><a href="<?php echo get_permalink($pid); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                   </a>
                   <div class="wzta-quickview">
                               <span class="quik-view">
                                   <a href="#" class="opn-quick-view-text" data-product_id="<?php echo esc_attr($pid); ?>">
                                      <span>
                                      <?php  echo __( 'Quick view', 'z-companion' );  ?>
                                      </span>
                                   </a>
                                </span>
                        </div>
                      </div>
                      <?php }
                       ?>
                   </div>
                   </div>
            <div class="wzta-product-content">
                      <?php 
                        $rat_product = wc_get_product($pid);
                        $rating_count =  $rat_product->get_rating_count();
                        $average =  $rat_product->get_average_rating();
                        echo $rating_count = wc_get_rating_html( $average, $rating_count );
                       ?>
                  <div class="price"><?php echo $product->get_price_html(); ?></div>
            </div>
                  
            <div class="wzta-product-hover">     
                    <?php 
                      echo royal_shop_add_to_cart_url($product);
                      echo royal_shop_whish_list($pid);
                      echo royal_shop_add_to_compare_fltr($pid);
                    ?>
            </div>
          </div>
        </div>
        </div>
   <?php }
    } else {
      echo __( 'No products found','z-companion' );
    }
    wp_reset_postdata();
}

function z_companion_product_filter_loop($args){  
     $products = wc_get_products( $args );
    if (!empty($products)) {
    foreach ($products as $product) {
      $pid =  $product->get_id();
      $cate = wc_get_product_category_list($pid);
      $cate = implode(" ",array_slice(explode(",",$cate),0,1));

       $hover_style = get_theme_mod( 'royal_shop_woo_product_animation' );
        if('swap' === $hover_style){
            global $product;
      $attachment_ids = $product->get_gallery_image_ids();
      if(count($attachment_ids) > '0'){
                $swapclasses='royal-shop-swap-item-hover';
        }
       }elseif('slide' === $hover_style){
            global $product;
      $attachment_ids = $product->get_gallery_image_ids();
      if(count($attachment_ids) > '0'){
                $swapclasses='royal-shop-slide-item-hover';
        }
       } else{
        $swapclasses='';
       }
      ?>
        <div <?php post_class($swapclasses, $pid); ?>>
          <div class="wzta-product-wrap">
         <div class="wzta-product-wrap">
          <div class="wzta-product">
            <span class="wzta-categories-prod">
            <?php 
           echo wp_kses($cate,array(  'a' => array( 'href' => array(),'title' => array(),'target' => array() ) ));
            ?>
            </span>
            <h2 class="woocommerce-loop-product__title">
                <a href="<?php echo get_permalink($pid); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><?php echo $product->get_title(); ?></a>
              </h2>
               <div class="wzta-product-image">
                <a href="<?php echo get_permalink($pid); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                <?php $sale = get_post_meta( $pid, '_sale_price', true);
                    if( $sale) {
                      // Get product prices
                        $regular_price = (float) $product->get_regular_price(); // Regular price
                        $sale_price = (float) $product->get_price(); // Sale price
                        $saving_price = wc_price( $regular_price - $sale_price );
                        echo $sale = '<span class="onsale">-'.$saving_price.'</span>';
                    }?>
                 <?php 
                      echo get_the_post_thumbnail( $pid, 'large' );
                      $hover_style = get_theme_mod( 'royal_shop_woo_product_animation' );
                         // the_post_thumbnail();
                        if ( 'swap' === $hover_style ){
                                $attachment_ids = $product->get_gallery_image_ids($pid);
                                if(!empty($attachment_ids)){
                             
                                 $glr = wp_get_attachment_image($attachment_ids[0], 'shop_catalog', false, array( 'class' => 'show-on-hover' ));
                                   echo $category_product['glr'] = $glr;

                                 }
                               
                           }
                           if ( 'slide' === $hover_style ){
                                $attachment_ids = $product->get_gallery_image_ids($pid);
                                if(!empty($attachment_ids)){
                             
                                 $glr = wp_get_attachment_image($attachment_ids[0], 'shop_catalog', false, array( 'class' => 'show-on-slide' ));
                                   echo $category_product['glr'] = $glr;

                                 }
                               
                           }
                  ?>
                   <?php 
                        $rat_product = wc_get_product($pid);
                        $rating_count =  $rat_product->get_rating_count();
                        $average =  $rat_product->get_average_rating();
                        echo $rating_count = wc_get_rating_html( $average, $rating_count );
                       ?>
                  </a><div class="wzta-icons-wrap">
                  <?php 
                    if(get_theme_mod( 'royal_shop_woo_quickview_enable', true )){

                  ?>
        <div class="wzta-quik"><a href="<?php echo get_permalink($pid); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                   </a>
                   <div class="wzta-quickview">
                               <span class="quik-view">
                                   <a href="#" class="opn-quick-view-text" data-product_id="<?php echo esc_attr($pid); ?>">
                                      <span>
                                      <?php  echo __( 'Quick view', 'z-companion' );  ?>
                                      </span>
                                   </a>
                                </span>
                        </div>
                      </div>
                      <?php }
                       ?>
                   </div>
                   </div>
            <div class="wzta-product-content">
                  <div class="price"><?php echo $product->get_price_html(); ?></div>
            </div>
                  
            <div class="wzta-product-hover">     
                    <?php 
                      echo royal_shop_add_to_cart_url($product);
                      echo royal_shop_whish_list($pid);
                      echo royal_shop_add_to_compare_fltr($pid);
                    ?>
            </div>
          </div>
        </div>
        </div>
      </div>
   <?php }
    } else {
      echo __( 'No products found','z-companion' );
    }
    wp_reset_postdata();
}
/*********************/
// Product for list view
/********************/
function z_companion_product_list_filter_loop($args){  
    $products = wc_get_products( $args );
    if (!empty($products)) {
    foreach ($products as $product) {
      $pid =  $product->get_id();
      $cate = wc_get_product_category_list($pid);
      $cate = implode(" ",array_slice(explode(",",$cate),0,1));
      ?>
        <div <?php post_class('product'); ?>>
          <div class="wzta-list">
               <div class="wzta-product-image">
                <a href="<?php echo get_permalink($pid); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                 <?php echo get_the_post_thumbnail( $pid, 'thumbnail' ); ?>
                  </a>
               </div>
               <div class="wzta-product-content">
                <span class="wzta-categories-prod">
                  <?php 
           echo wp_kses($cate,array(  'a' => array( 'href' => array(),'title' => array(),'target' => array() ) ));
            ?>
               </span>
                  <a href="<?php echo get_permalink($pid); ?>" class="woocommerce-LoopProduct-title woocommerce-loop-product__link"><?php echo $product->get_title(); ?></a>
                  <?php 
                        $rat_product = wc_get_product($pid);
                        $rating_count =  $rat_product->get_rating_count();
                        $average =  $rat_product->get_average_rating();
                        echo $rating_count = wc_get_rating_html( $average, $rating_count );
                       ?>
                  <div class="price"><?php echo $product->get_price_html(); ?></div>
               </div>
          </div>
       </div>
   <?php }
    } else {
      echo __( 'No products found','z-companion' );
    }
    wp_reset_postdata();
}
/*****************************/
// Product show function
/****************************/
function  z_companion_widget_product_query($query){
$productType = $query['prd-orderby'];
$count = $query['count'];
$cat_slug = $query['cate'];
global $th_cat_slug;
$th_cat_slug = $cat_slug;
        $args = array(
            'hide_empty' => 1,
            'posts_per_page' => $count,        
            'post_type' => 'product',
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => array(
                              array(
                                  'key' => '_stock_status',
                                  'value' => 'instock'
                              ),
                              array(
                                  'key' => '_backorders',
                                  'value' => 'no'
                              ),
                          )
        );
       if($productType == 'featured'){
        $taxquery = array(
           
                          array(
                              'taxonomy' => 'product_cat',
                              'field' => 'slug',
                              'terms' =>  $cat_slug,
                          ),
                          array(
                                'taxonomy'  => 'product_visibility',
                                'terms'     => array( 'exclude-from-catalog' ),
                                'field'     => 'name',
                                'operator'  => 'NOT IN',
                            )
          );
        $args = array(
                      'tax_query' => $taxquery,
                      'post_type' => 'product',
                      'post__in'  => wc_get_featured_product_ids(),
              );
        }
        elseif($productType == 'random'){
            //random product
          $args['orderby'] = 'rand';
        }
        elseif($productType == 'sale') {
          //sale product
        $args['meta_query']     = array(
        'relation' => 'OR',
        array( // Simple products type
            'key'           => '_sale_price',
            'value'         => 0,
            'compare'       => '>',
            'type'          => 'numeric'
        ),
        array( // Variable products type
            'key'           => '_min_variation_sale_price',
            'value'         => 0,
            'compare'       => '>',
            'type'          => 'numeric'
        )
    );
}
$args['meta_key'] = '_thumbnail_id';
if($cat_slug != '0'){
           
                $args['tax_query'] = array(
                  'relation' => 'AND',
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'term_id',
                                'terms' => $cat_slug,
                            ),
                           array(
                                'taxonomy'  => 'product_visibility',
                                'terms'     => array( 'exclude-from-catalog' ),
                                'field'     => 'name',
                                'operator'  => 'NOT IN',
                            )
                         );
     }
$return = new WP_Query($args);
return $return;
}
/*****************************/
// Product show function
/****************************/
function z_companion_post_query($query){

       $args = array(
            'orderby' => $query['orderby'],
            'order' => 'DESC',
            'ignore_sticky_posts' => $query['sticky'],
            'post_type' => 'post',
            'posts_per_page' => $query['count'], 
            'cat' => $query['cate'],
            'meta_key'     => '_thumbnail_id',
           
        );

       if($query['thumbnail']){
          $args['meta_key'] = '_thumbnail_id';
       }

            $return = new WP_Query($args);

            return $return;
}
/**********************************************
//Funtion Category list show
 **********************************************/   
function z_companion_category_tab_list( $term_id ){
  if( taxonomy_exists( 'product_cat' ) ){ 
      // category filter  
      $args = array(
            'orderby'    => 'title',
            'order'      => 'ASC',
            'hide_empty' => 1,
            'slug'    => sanitize_meta('slug',$term_id,'terms'),
        );
      $product_categories = get_terms( 'product_cat', $args );
      $count = count($product_categories);
      $cat_list = $cate_product = '';
      $cat_list_drop = '';
      $i=1;
      $dl=0;
?>
<?php
//Detect special conditions devices
$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

//do something with this information
if( $iPod || $iPhone ){
  $device_cat =  '2';
    //browser reported as an iPhone/iPod touch -- do something here
}else if($iPad){
  $device_cat =  '2';
    //browser reported as an iPad -- do something here
}else if($Android){
  $device_cat =  '2';
    //browser reported as an Android device -- do something here
}else if($webOS){
   $device_cat =  '4';
    //browser reported as a webOS device -- do something here
}else{
    $device_cat =  '5';
}
     if ( $count > 0 ){
      foreach ( $product_categories as $product_category ){
              //global $product; 
              $category_product = array();
              $current_class = '';
              $cat_list .='
                  <li>
                  <a data-filter="' .esc_attr($product_category->slug) .'" data-animate="fadeInUp"  href="#"  data-term-id='.esc_attr($product_category->term_id) .' product_count="'.esc_attr($product_category->count).'">
                     '.esc_html($product_category->name).'</a>
                  </li>';
          if ($i++ == $device_cat) break;
          }
          if($count > $device_cat){
          foreach ( $product_categories as $product_category ){
              //global $product; 
              $dl++;
              if($dl <= $device_cat) continue;
              $category_product = array();
              $current_class = '';
              $cat_list_drop .='
                  <li>
                  <a data-filter="' .esc_attr($product_category->slug) .'" data-animate="fadeInUp"  href="#"  data-term-id='.esc_attr($product_category->term_id) .' product_count="'.esc_attr($product_category->count).'">
                     '.esc_html($product_category->name).'</a>
                  </li>';
          }
        }
          $return = '<div class="tab-head" catlist="'.esc_attr($i).'" >
          <div class="tab-link-wrap">
          <ul class="tab-link">';
 $return .=  $cat_list;
 $return .= '</ul>';
 if($count > $device_cat){
  $return .= '<div class="header__cat__item dropdown"><a href="#" class="more-cat" title="More categories...">'.__('•••','z-companion').'</a><ul class="dropdown-link">';
 $return .=  $cat_list_drop;
 $return .= '</ul></div>';
}
  $return .= '</div></div>';

 echo $return;
       }
    } 
}

/********************************/
//product slider loop
/********************************/
function z_companion_product_slide_list_loop($term_id,$prdct_optn){  
$args = z_companion_product_query($term_id,$prdct_optn);
    $products = wc_get_products( $args );
    if (!empty($products)) {
    foreach ($products as $product) {
      $pid =  $product->get_id();
      $cate = wc_get_product_category_list($pid);
      $cate = implode(" ",array_slice(explode(",",$cate),0,1));
      ?>
        <div <?php post_class('product'); ?>>
          <div class="wzta-list">
               <div class="wzta-product-image">
                <a href="<?php echo get_permalink($pid); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                 <?php echo get_the_post_thumbnail( $pid, 'thumbnail' ); ?>
                  </a>
               </div>
               <div class="wzta-product-content">
                <span class="wzta-categories-prod">
                  <?php 
           echo wp_kses($cate,array(  'a' => array( 'href' => array(),'title' => array(),'target' => array() ) ));
            ?>
               </span>
                  <a href="<?php echo get_permalink($pid); ?>" class="woocommerce-LoopProduct-title woocommerce-loop-product__link"><?php echo $product->get_title(); ?></a>
                  <?php 
                        $rat_product = wc_get_product($pid);
                        $rating_count =  $rat_product->get_rating_count();
                        $average =  $rat_product->get_average_rating();
                        echo $rating_count = wc_get_rating_html( $average, $rating_count );
                       ?>
                  <div class="price"><?php echo $product->get_price_html(); ?></div>
               </div>
          </div>
       </div>
   <?php }
    } else {
      echo __( 'No products found','z-companion' );
    }
    wp_reset_postdata();
}