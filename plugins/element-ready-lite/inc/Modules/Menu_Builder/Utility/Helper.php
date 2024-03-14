<?php

/*
 Elementor Mega menu Edit Link
*/
function element_ready_mega_menu_el_edit_link($post_id = 0){
  return add_query_arg( [ 'post' => $post_id, 'action' => 'elementor' ], admin_url( 'post.php' ) );
}

if(!function_exists('element_ready_get_product_category_name_from_id')){

  function element_ready_get_product_category_name_from_id( $category_id ) {
      $term = get_term_by( 'id', $category_id, 'product_cat', 'ARRAY_A' );
      return $term['name'];
  }

}

if(!function_exists('element_ready_get_blog_category_name_from_id')){

    function element_ready_get_blog_category_name_from_id( $category_id ) {
        $term = get_term_by( 'id', $category_id, 'category', 'ARRAY_A' );
        return $term['name'];
    }
  
  }

if( !function_exists('element_ready_get_post_cat') ){

  function element_ready_get_post_cat($tax = 'product_cat') {

      static $clist = [];

      if( !count( $clist ) ) {
      $categories = get_terms( $tax, array(
          'orderby'       => 'name', 
          'order'         => 'DESC',
          'hide_empty'    => false,
          'number'        => 600
          
      ) );
  
      foreach( $categories as $category ) {
        
           if(isset($category->name)){
            $clist[$category->term_id] = esc_html($category->name);
           }
         
      }
      
      }
  
      return $clist;
  }
}

  /**
     * Get all elementor page templates
     *
     * @param  null  $type
     *
     * @return array
     */
    if(!function_exists('element_ready_get_elementor_templates')){
      function element_ready_get_elementor_templates($type = null){
          $options = [];

          if ($type) {
              $args = [
                  'post_type' => 'elementor_library',
                  'posts_per_page' => -1,
              ];
              $args['tax_query'] = [
                  [
                      'taxonomy' => 'elementor_library_type',
                      'field' => 'slug',
                      'terms' => sanitize_text_field($type),
                  ],
              ];

              $page_templates = get_posts($args);

              if (!empty($page_templates) && !is_wp_error($page_templates)) {
                  foreach ($page_templates as $post) {
                      $options[$post->ID] = esc_html($post->post_title);
                  }
              }
          } else {
              $options = element_ready_get_query_post_list('elementor_library');
          }

          return $options;
      }
  }


  if(!function_exists('element_ready_get_query_post_list')){

      function element_ready_get_query_post_list($post_type = 'any', $limit = -1, $search = ''){
          global $wpdb;
          $where = '';
          $data = [];

          if (-1 == $limit) {
              $limit = '';
          } elseif (0 == $limit) {
              $limit = "limit 0,1";
          } else {
              $limit = $wpdb->prepare(" limit 0,%d", esc_sql($limit));
          }

          if ('any' === $post_type) {
              $in_search_post_types = get_post_types(['exclude_from_search' => false]);
              if (empty($in_search_post_types)) {
                  $where .= ' AND 1=0 ';
              } else {
                  $where .= " AND {$wpdb->posts}.post_type IN ('" . join("', '",
                      array_map('esc_sql', $in_search_post_types)) . "')";
              }
          } elseif (!empty($post_type)) {
              $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_type = %s", esc_sql($post_type));
          }

          if (!empty($search)) {
              $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s", '%' . esc_sql($search) . '%');
          }

          $query = "select post_title,ID  from $wpdb->posts where post_status = 'publish' $where $limit";
          $results = $wpdb->get_results($query);
          if (!empty($results)) {
              foreach ($results as $row) {
                  $data[$row->ID] = esc_html($row->post_title);
              }
          }
          return $data;
      }
      
  }

add_filter( 'woocommerce_add_to_cart_fragments', 'element_ready_woocommerce_header_add_to_cart_fragment' );
/*
 Woocommerce Cart fragemnt
*/
function element_ready_woocommerce_header_add_to_cart_fragment( $fragments ) {
  
	ob_start();
	?>
	<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_html_e( 'View your shopping cart' ); ?>"><?php echo sprintf (_n( '%d item', '%d items', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?> - <?php echo WC()->cart->get_cart_total(); ?></a> 
	<?php
	
	$fragments['a.cart-contents'] = ob_get_clean();
	
	return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'element_ready_menu_woocommerce_header_add_to_cart_fragment' );

function element_ready_menu_woocommerce_header_add_to_cart_fragment( $fragments ) {
  ob_start();
 	?>
      <div class="cart-area element-ready-cart-content">
        <a class="cart-btn" href="<?php echo wc_get_cart_url(); ?>">
           <?php \Elementor\Icons_Manager::render_icon( get_option('element_ready_wc_count_icon') , [ 'aria-hidden' => 'true' ] ); ?>
          <span><?php echo wp_kses_post( sprintf (_n( '%d', '%d', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ) ); ?></span></a>
        <div class="er-wc-product-price">
                <?php if(isset(WC()->cart)): ?>
                    <?php echo wp_kses_post( wc_price( WC()->cart->total) ); ?> 
                <?php else: ?>
                    <?php wc_price(0); ?>
                <?php endif; ?>
        </div>
      </div>
  <?php
 	$fragments['.element-ready-cart-content'] = ob_get_clean();
	return $fragments;
}
/*
  Menu Tags Validate
*/
function element_ready_menu_html_tag_validate($option ='',$option2 = ''){
    
    if($option ==''){
        return false;
    }

    $option_tag  = false;
    $option_tag2 = $option2;
    $option_tag  = str_replace(['<','>','</'],[''],$option);
    if($option2 == ''){
        $option_tag2 = '</'.$option_tag.'>';
    }
    return ['start'=>$option,'end'=>$option_tag2];

 }

 

