<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

if(!class_exists('WPBForWPbakery_Product_Archive')){
  class WPBForWPbakery_Product_Archive{
      function __construct() {

          // creating shortcode addon
          add_shortcode( 'wpbforwpbakery_product_archive', array( $this, 'render_shortcode' ) );

          // We safely integrate with VC with this hook
          add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );
      }

      public function wpbforwpbakery_custom_product_limit( $limit = 3 ) {
          $limit = ( $columns * $rows );
          return $limit;
      }

      public function render_shortcode( $atts, $content = null ) {
          extract(shortcode_atts(array(
              'columns' => '4', 
              'rows' => '3', 
              'paginate' => 'yes', 
              'show_result_count' => 'yes', 
              'allow_order' => 'yes', 
              'orderby' => 'yes', 
              'order' => '',
              'el_class' => '', 
              'wrapper_css' => '' 
          ),$atts));

          $attributes = array(
            'columns' => $columns,
            'rows'  => $rows,
            'paginate' => $paginate,
          );

          $archive_obj = get_queried_object();

          // category
          if(is_tax('product_cat') && is_product_category()){
            $attributes['category'] = $archive_obj->name;
          }

          // tag
          if(is_tax('product_tag') && is_product_tag()){
            $attributes['tag'] = $archive_obj->name;
          }

          // order
          if($allow_order == 'yes'){
            $attributes['orderby'] = $orderby;
            $attributes['order'] = $order;
          }

          $attributes['allow_order'] = $allow_order;
          $attributes['show_result_count'] = $show_result_count;
          $attributes['query_post_type'] = '';
          $attributes['editor_mode'] = '';

          $shortcode = new WPBForWPbakery_Archive_Products_Render( $attributes );

          ob_start();
          $output = '';

          if ( WC()->session ) {
              wc_print_notices();
          }

          if ( ! isset( $GLOBALS['post'] ) ) {
              $GLOBALS['post'] = null;
          }

          $unique_class = uniqid('wpbforwpbakery_archive_product wpbforwpbakery_archive_product_');


          echo '<div class="'. esc_attr($el_class . ' ' . $unique_class) .wpbforwpbakery_get_vc_custom_class($wrapper_css, ' ') .'">';

          $theme = wp_get_theme();
          $current_theme = $theme->get('Name');

          if($current_theme == 'Flone'){
            ?>
            <div class="shop-top-bar">

              <?php if($allow_order == 'yes'): ?>
              <div style="margin-bottom: 20px;" class="select-shoing-wrap">
                <?php woocommerce_catalog_ordering(); ?>
              </div>
              <?php endif; ?>
  
          <?php if($show_result_count == 'yes'): ?>
              <!-- Nav tabs -->
              <div style="margin-bottom: 10px;" class="shop-tab nav">
                <?php woocommerce_result_count(); ?>
              </div>
              <?php endif; ?>

            </div>
            <?php 
          }

          $content = $shortcode->get_content();
          if ( $content ) {
              echo $content;
          } else{
              echo '<div class="products-not-found">' . esc_html__( 'Product Not Available','wpbforwpbakery' ) . '</div>';
          }

          echo '</div> <!-- .wpbforwpbakery_archive_product -->';

          return $output .= ob_get_clean();
      }


      public function integrateWithVC() {
      
          /*
          Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

          More info: http://kb.wpbakery.com/index.php?title=Vc_map
          */
          vc_map( array(
              "name" => __("WCB: Product Archive", 'wpbforwpbakery'),
              "base" => "wpbforwpbakery_product_archive",
              "class" => "",
              "controls" => "full",
              "icon" => 'wpbforwpbakery_product_archive_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
              "category" => __('WC Builder', 'wpbforwpbakery'),
              "params" => array(
                  array(
                      'param_name' => 'columns',
                      'heading' => __( 'Columns', 'wpbforwpbakery' ),
                      "type" => "dropdown",
                      "std" => '4',
                      'value' => [
                          __( '1', 'wpbforwpbakery' )  =>  '1',
                          __( '2', 'wpbforwpbakery' )  =>  '2',
                          __( '3', 'wpbforwpbakery' )  =>  '3',
                          __( '4', 'wpbforwpbakery' )  =>  '4',
                          __( '5', 'wpbforwpbakery' )  =>  '5'
                      ],
                  ),
                  array(
                      'param_name' => 'rows',
                      'heading' => __( 'Rows', 'wpbforwpbakery' ),
                      "type" => "dropdown",
                      "std" => '3',
                      'value' => [
                          __( '1', 'wpbforwpbakery' )  =>  '1',
                          __( '2', 'wpbforwpbakery' )  =>  '2',
                          __( '3', 'wpbforwpbakery' )  =>  '3',
                          __( '4', 'wpbforwpbakery' )  =>  '4',
                          __( '5', 'wpbforwpbakery' )  =>  '5',
                          __( '6', 'wpbforwpbakery' )  =>  '6'
                      ],
                  ),
                  // paginate
                  array(
                      'param_name' => 'paginate',
                      'heading' => __( 'Paginate', 'wpbforwpbakery' ),
                      "type" => "dropdown",
                      "std" => 'yes',
                      'value' => [
                          __( 'Yes', 'wpbforwpbakery' )  =>  'yes',
                          __( 'No', 'wpbforwpbakery' )  =>  'no'
                      ],
                  ),
                  // allow_order
                  array(
                    "param_name" => "allow_order",
                    "heading" => __("Allow Order", 'wpbforwpbakery'),
                    "type" => "dropdown",
                    "std" => 'yes',
                    'value' => [
                        __( 'Yes', 'wpbforwpbakery' )  =>  'yes',
                        __( 'No', 'wpbforwpbakery' )  =>  'no'
                    ],
                  ),
                  // show_result_count
                  array(
                    "param_name" => "show_result_count",
                    "heading" => __("Show Result Count", 'wpbforwpbakery'),
                    "type" => "dropdown",
                    "std" => 'yes',
                    'value' => [
                        __( 'Yes', 'wpbforwpbakery' )  =>  'yes',
                        __( 'No', 'wpbforwpbakery' )  =>  'no'
                    ],
                  ),
                  // orderby
                  array(
                    "param_name" => "orderby",
                    "heading" => __("Order By", 'wpbforwpbakery'),
                    "type" => "dropdown",
                    "std" => 'date',
                    'value' => [
                         __( 'Date', 'wpbforwpbakery' ) => 'date',
                         __( 'Title', 'wpbforwpbakery' ) => 'title',
                         __( 'Price', 'wpbforwpbakery' ) => 'price',
                         __( 'Popularity', 'wpbforwpbakery' ) => 'popularity',
                         __( 'Rating', 'wpbforwpbakery' ) => 'rating',
                         __( 'Random', 'wpbforwpbakery' ) => 'rand',
                         __( 'Menu Order', 'wpbforwpbakery' ) => 'menu_order'
                    ],
                    'dependency' =>[
                        'element' => 'allow_order',
                        'value' => array( 'yes' )
                    ],
                  ),
                  // order
                  array(
                    "param_name" => "order",
                    "heading" => __("Order", 'wpbforwpbakery'),
                    "type" => "dropdown",
                    "std" => 'desc',
                    'value' => [
                         __( 'ASC', 'wpbforwpbakery' ) => 'asc',
                         __( 'DESC', 'wpbforwpbakery' ) => 'desc'
                    ],
                    'dependency' =>[
                        'element' => 'allow_order',
                        'value' => array( 'yes' )
                    ],
                  ),
                  array(
                      'param_name' => 'el_class',
                      'heading' => __( 'Extra class name', 'wpbforwpbakery' ),
                      'type' => 'textfield',
                      'description' => __( 'Style this element differently - add a class name and refer to it in custom CSS.', 'wpbforwpbakery' ),
                  ),
                  array(
                    "param_name" => "wrapper_css",
                    "heading" => __( "Wrapper Styling", "wpbforwpbakery" ),
                    "type" => "css_editor",
                    'group'  => __( 'Wrapper Styling', 'wpbforwpbakery' )
                ),
              )
          ) );
      }
  }
  new WPBForWPbakery_Product_Archive();
}