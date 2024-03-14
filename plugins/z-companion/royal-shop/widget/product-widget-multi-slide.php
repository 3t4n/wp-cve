<?php
/*
 *  PRODUCT-WIDGET-MULTISITE
 */
// register widget
function z_companion_royal_shop_show_product_multi_slide_widget(){
register_widget( 'Z_COMPANION_Royal_Shop_Show_Multi_Slide' );
}
add_action('widgets_init','z_companion_royal_shop_show_product_multi_slide_widget');
//  widget class
class Z_COMPANION_Royal_Shop_Show_Multi_Slide extends WP_Widget{
function __construct(){
    $widget_ops = array('classname' => 'Z_COMPANION_Royal_Shop_Show_Multi_Slide','description' => 'Show your Multi Product');
        parent::__construct('royal-shop-show-multi-slide', __('Royal Shop : Multi Product slider widget','royal-shop'), $widget_ops);
    }
    function widget($args, $instance){
    extract($args);
    echo $before_widget;
    //widget content
    $query = array();
    $title = isset($instance['title'])?$instance['title']:__('Latest','royal-shop');
    $ato_ply = isset($instance['ato_ply']) ? $instance['ato_ply']:'false';
    $query['prd-orderby'] = isset($instance['prd-orderby']) ?$instance['prd-orderby'] : 'recent';
    $query['cate'] = isset($instance['ofcate']) ? absint($instance['ofcate']) : 0;
    $query['count'] = isset($instance['ofcount']) ? absint($instance['ofcount']) : 8;
    $query['thumbnail'] = true;
    $products = z_companion_widget_product_query($query);
    $count = $products->post_count;
    $catelink = get_category_link( $query['cate'] ); 
    ?>
<div class="product-slide-widget multi-product-slide-widget <?php echo $widget_id; ?>">  
<input type='hidden' class="autoplay-<?php echo $widget_id; ?>" value="<?php echo $ato_ply; ?>" /> 
<?php if($title!==''){ ?>
<h2 class="widget-title slide-widget-title "><span><?php echo esc_html($title); ?></span></h2> 
<?php } ?>  
<div id="<?php echo $widget_id;?>" class="slide-two-product featured-grid owl-carousel">
<?php 
$i = 1; 
while ($products->have_posts() ) : $products->the_post();
global $product;
global $th_cat_slug;
$pid =  $product->get_id();
$cate = wc_get_product_category_list($pid);
$cate = implode(" ",array_slice(explode(",",$cate),0,1));
    if ( $i % 3 == 1) { ?>
      <div class="wzta-3row-slide-wrap">
<?php    }
?>
        <div <?php post_class(); ?>>
            <div class="wzta-list">
               <div class="wzta-product-image">
                <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                 <?php the_post_thumbnail(); ?>
                  </a>
               </div>
               <div class="wzta-product-content">
                 <span class="wzta-categories-prod">
                  <?php echo $cate; ?>
                  </span>
                  <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-title woocommerce-loop-product__link">
                    <span class="product-title"><?php the_title(); ?></span></a>
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
<?php
    if ( $i % 3 == 0 || $i == $count ) { ?>
      </div>
<?php    } $i++; ?>
        
<?php  
endwhile;
wp_reset_query();
?>
</div>
</div>

<script>
 ///-----------------------///
// product slide script
///-----------------------///
jQuery(document).ready(function(){
var wdgetid = '<?php echo $widget_id; ?>'; 
var auto = jQuery(".autoplay-"+wdgetid).val();
if(auto=='true'){
jQuery('#'+wdgetid+'.owl-carousel').owlCarousel({  
     items:1,
    loop:true,
     nav: true,
    margin:0,
    autoplay:true,
    autoplaySpeed:4000,
     autoplayTimeout: 9000,
    smartSpeed: 2000,
    fluidSpeed:true,
    responsiveClass:true,
    dots: false,  
    navText: ["<i class='slick-nav fa fa-angle-left'></i>",
       "<i class='slick-nav fa fa-angle-right'></i>"],
  })
}else{ 
    jQuery('#'+wdgetid+'.owl-carousel').owlCarousel({
    items:1,
    loop:true,
     nav: true,
    margin:0,
    autoplay:false,
    smartSpeed: 2000,
    fluidSpeed:true,
    responsiveClass:true,
    dots: false,
    navText: ["<i class='slick-nav fa fa-angle-left'></i>",
       "<i class='slick-nav fa fa-angle-right'></i>"],
    })
  }
});
</script>
<?php
echo $after_widget;
}
function update($new_instance, $old_instance){
        $instance = $old_instance;
        $query = array();
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance["ato_ply"] = $new_instance["ato_ply"];
        $instance["prd-orderby"] = $new_instance["prd-orderby"];
        $instance["ofcate"] = absint($new_instance["ofcate"]);

        $instance['ofcount'] = strip_tags( $new_instance['ofcount'] );
      

        return $instance;
    }
    function form($instance){
        $widgetInput = New Z_COMPANION_WidgetHtml();
        $title = isset($instance['title']) ? esc_attr($instance['title']) : __('Latest','royal-shop');
        $ato_ply = isset($instance['ato_ply']) ? $instance['ato_ply'] :"false";
        $ofcate = isset($instance['ofcate']) ? absint($instance['ofcate']) : 0;

        $ofcount = isset($instance['ofcount']) ? absint($instance['ofcount']) : 5;
       
 //******************************//       
// fetch product category
//******************************//
if ( taxonomy_exists ( 'product_cat' )){  
$termarr = array(
    'child_of'   => 0,
    'orderby' => 'count', 
    'include' => ',' ,
    'order' => 'DESC'
);
$terms = get_terms('product_cat' ,$termarr);
$foption = '<option value="0">All</option>';
foreach($terms as $cat) {
    $term_id = $cat->term_id;
    $selected1 = ($ofcate==$term_id)?'selected':'';
$foption .= '<option value="'.$term_id.'" '.$selected1.'>'.$cat->name.'</option>';
}
}
//******************************//  
?>
<div class="clearfix"></div>
    <p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title','royal-shop'); ?></label>
    <input name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>"  class="widefat" value="<?php echo $title; ?>" >
    </p>
    <?php 
      $arr2 = array('id'=>'prd-orderby',
          'label'=> __('Choose Product Type ','royal-shop'),
          'default' => 'recent',
          'option' => array('recent'=>__('Recent','royal-shop'),
                            'featured'=>__('Featured','royal-shop'),
                            'random' =>__('Random','royal-shop'),
                            'sale' =>__('Sale','royal-shop'))
          );
        $widgetInput->selectBox($this,$instance,$arr2);
        ?>
    <p>
    <label for="<?php echo $this->get_field_id('ofcate'); ?>"><?php _e('Choose Category to Show Product','royal-shop'); ?></label>
        <select name="<?php echo $this->get_field_name('ofcate'); ?>" ><?php echo $foption; ?></select>
    </p>

    <p><label for="<?php echo $this->get_field_id('ofcount'); ?>"><?php _e('Add Number of Product to fetch','royal-shop'); ?></label>
            <input id="<?php echo $this->get_field_id('ofcount'); ?>" name="<?php echo $this->get_field_name('ofcount'); ?>" type="text" value="<?php echo $ofcount; ?>" size="3" />
    </p>  

<p>
<label for="<?php echo $this->get_field_id('ato_ply'); ?>"><?php _e('Autoplay Slider','royal-shop'); ?></label>
<input 
style="margin-right:5px;margin-left:5px;" type="radio" id="role_info" class="widefat" name="<?php echo $this->get_field_name('ato_ply'); ?>"  value="true" <?php checked( $ato_ply, 'true' ); ?> >ON
<br/>
<br/>
<input style="margin-right:5px;margin-left:5px;" type="radio" id="role_info" class="widefat" name="<?php echo $this->get_field_name('ato_ply'); ?>"  value="false" <?php checked( $ato_ply, 'false' ); ?> >OFF
</p>
        <?php
    }
 }
?>