<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
if(get_theme_mod('royal_shop_disable_category_slide_sec',false) == true){
    return;
  }
?>

<section class="wzta-category-slide-section">
      <?php z_companion_display_customizer_shortcut( 'royal_shop_cat_slide_section' );
       ?>
  <div class="wzta-heading">
    <h4 class="wzta-title">
    <span class="title"><?php echo esc_html(get_theme_mod('royal_shop_cat_slider_heading','Product Categories Section'));?></span>
   </h4>
</div>
<div class="content-wrap">
<?php if(get_theme_mod('royal_shop_cat_slide_layout','cat-layout-1')=='cat-layout-1'):?>
<div class="wzta-slide wzta-cat-slide owl-carousel">
<?php   
  if( taxonomy_exists( 'product_cat' ) ){
      $term_id = get_theme_mod('royal_shop_category_slide_list',0); 
     if ($term_id!= '') {
      // category filter  
      $args = array(
            
            'orderby'    => 'title',
            'order'      => 'ASC',
            'hide_empty' => 1,
            'slug'    => $term_id
        );
    }
    else{
      $args = array(
            
            'orderby'    => 'title',
            'order'      => 'ASC',
            'hide_empty' => 1,
        );
    }


      $product_categories = get_terms( 'product_cat', $args );

      $count = count($product_categories);

      $category_list = $cate_product = '';
      if ( $count > 0 ){
      foreach ( $product_categories as $product_category ){
              //global $product; 
              $category_product = array();
  $term_link = get_term_link( $product_category, 'product_cat' );
  $thumbnail_id = get_term_meta( $product_category->term_id, 'thumbnail_id', true ); // Get Category Thumbnail
  $image = wp_get_attachment_url( $thumbnail_id ); 
  $no_catimg = '';
  if ($image=='') {
   $no_catimg = 'no-image';
  }
  
  
              $current_class = '';
             
$category_list .='<div class="wzta-category">
                            <div class="wzta-cat-box '.$no_catimg.'">
                              <a href="'.$term_link.'">
                                 <img src="' . $image . '" alt="" />
                              </a>
                              </div>
                              <div class="wzta-cat-text">
                                   <div class="wzta-cat-title">
                                     <a href="'.$term_link.'"><span class="title">'.$product_category->name. '</span></a>
                                     </div>
                              </div>
                                 
                  </div>';
          }
          echo $category_list;
       }
    } 

  ?>
  </div>
<?php elseif(get_theme_mod('royal_shop_cat_slide_layout')=='cat-layout-2'):?>
<div class="cat-wrap cat-layout-2">
  <div class="cat-content">
<?php   
  if( taxonomy_exists( 'product_cat' ) ){
      $term_id = get_theme_mod('royal_shop_category_slide_list',0); 
      // category filter  
      $args = array(
            
            'orderby'    => 'title',
            'order'      => 'ASC',
            'hide_empty' => 1,
            'slug'    => $term_id
        );

      $product_categories = get_terms( 'product_cat', $args );

      $count = count($product_categories);

      $category_list = $cate_product = '';
      if ( $count > 0 ){
      foreach ( $product_categories as $product_category ){
              //global $product; 
              $category_product = array();
  $term_link = get_term_link( $product_category, 'product_cat' );
  $thumbnail_id = get_term_meta( $product_category->term_id, 'thumbnail_id', true ); // Get Category Thumbnail
  $image = wp_get_attachment_url( $thumbnail_id ); 
  $current_class = '';           
  $category_list .='<div class="cat-list">
                   <a href="'.esc_url($term_link).'">
                      
                      <span><i class="fa fa-tags" aria-hidden="true"></i>'.esc_html($product_category->name).'</span>
                    </a>
                  </div>';       
          }
          echo $category_list;
       }
    } 
  ?>
   </div>
</div>
<?php elseif(get_theme_mod('royal_shop_cat_slide_layout')=='cat-layout-3'):?>
<div class="cat-wrap cat-layout-3">
  <div class="cat-content-3">
      
     
      
<?php   
  if( taxonomy_exists( 'product_cat' ) ){
      $term_id = get_theme_mod('royal_shop_category_slide_list',0); 
      // category filter  
      $args = array(
            
            'orderby'    => 'title',
            'order'      => 'ASC',
            'hide_empty' => 1,
            'slug'    => $term_id
        );

      $product_categories = get_terms( 'product_cat', $args );

      $count = count($product_categories);

      $category_list = $cate_product = '';
      if ( $count > 0 ){  $i=1;
      foreach ( $product_categories as $product_category ){
              //global $product; 
              $category_product = array();
  $term_link = get_term_link( $product_category, 'product_cat' );
  $thumbnail_id = get_term_meta( $product_category->term_id, 'thumbnail_id', true ); // Get Category Thumbnail
  $image = wp_get_attachment_url( $thumbnail_id ); 
  $current_class = '';   ?>  
  <?php if ($i==1) { ?>
        <div class="cat-flex-first">
 <?php }      ?>
 <?php if ($i==3) { ?>
        <div class="cat-flex-second">
 <?php }      ?>
 <?php if ($i==4) { ?>
        <div class="cat-flex-third">
 <?php }      ?>
 <?php if ($i==6) { ?>
        <div class="cat-flex-fourth">
 <?php }      ?>
 

  <div class="cat-col">
      <div class="cat-col-wrap">
        <img src="<?php echo esc_url($image); ?>">
        <div class="hover-area">
           <span class="cat-title">
            <?php echo esc_html($product_category->name); ?>
           </span>
            <div class="prd-total-number"><span class="item">
              <?php echo $product_category->count; 
              echo esc_html('Product','z-companion'); ?></span></div>
              <a href="<?php esc_url($term_link); ?>"> </a>
        </div>
      </div>
  </div>
  <?php if ($i==2) { ?>
        </div>
 <?php }      ?>

 <?php if ($i==3) { ?>
        </div>
 <?php }      ?>

 <?php if ($i==5) { ?>
       </div>
 <?php }      ?>

<?php  if( !next( $product_categories ) ) { ?>
       </div>
   <?php }

  $i++;  }
       }
    } 
  ?>
  </div>
</div>
<?php endif;?>
</div>
</section>