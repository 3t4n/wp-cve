<?php
// register widget
function z_companion_royal_shop_post_slide_widget(){
register_widget( 'Z_COMPANION_Royal_Shop_Slide_Post' );
}
add_action('widgets_init','z_companion_royal_shop_post_slide_widget');


class Z_COMPANION_Royal_Shop_Slide_Post extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'royal-shop-slide-post',
            'description' => 'Display post along with description');
        parent::__construct('WpZita-customizer-section-four', __('Royal Shop : Post Slide widget','z-companion'), $widget_ops);
    }

    function widget($args, $instance) {
        extract($args);
        // widget content
        echo $before_widget;
        $query = array();
        $title = isset($instance['title'])?$instance['title']:__('writing your description','z-companion');
        $query['cate']  = isset($instance['cate']) ? absint($instance['cate']) : 0;
        $query['count']  = isset($instance['count']) ? absint($instance['count']) : 3;
        $query['orderby'] = isset($instance['orderby']) ?$instance['orderby'] : 'post_date';
        $query['thumbnail'] = false;
        $query['sticky'] = true;
        $latest_posts = z_companion_post_query($query);
        $catelink = get_category_link( $query['cate'] ); 
?>
<div class="post-slide-widget <?php echo $widget_id; ?>"> 
<h2 class="widget-title slide-widget-title "><span><?php echo $title; ?></span></h2> 
   
<div id="<?php echo $widget_id; ?>" class="slide-post owl-carousel">   
          <?php if ( $latest_posts->have_posts() ) { ?>
             <?php  while($latest_posts->have_posts()): $latest_posts->the_post(); 
             ?>
                <div class="post-item">
                    <div class="post-thumb">
                        <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { 
                        the_post_thumbnail();
                         }
                        ?>
                    </div>
                    <div class="entry-body">
                        <div class="post-item-content">
                            <a href="<?php the_permalink(); ?>"><span class="title"><?php the_title(); ?></span></a>
                            <div class="entry-meta">
                                <span class="entry-date"><?php the_time( get_option('date_format') ); ?></span>
                            </div>
                           
                                <?php royal_shop_the_excerpt(); ?>
                           
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php } wp_reset_postdata(); ?>
            </div>
        </div>

<script>
 ///-----------------------///
// product slide script
///-----------------------///
jQuery(document).ready(function(){
var wdgetid = '<?php echo $widget_id; ?>'; 

jQuery('#'+wdgetid+'.owl-carousel').owlCarousel({  
    items:1,
    loop:false,
    nav: true,
    margin:0,
    autoplay:false,
    autoplaySpeed:500,
    autoplayTimeout:2000,
    smartSpeed:500,
    fluidSpeed:true,
    responsiveClass:true,
    dots: false,  
    navText: ["<i class='slick-nav fa fa-angle-left'></i>",
       "<i class='slick-nav fa fa-angle-right'></i>"],
  });
});
</script>
<?php
        echo $after_widget;

    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance["cate"] = absint($new_instance["cate"]);
        $instance['count'] = strip_tags( $new_instance['count'] );
       
       
        $instance["orderby"] = $new_instance["orderby"];
        
        return $instance;
    }

    function form($instance) {
            $widgetInput = New Z_COMPANION_WidgetHtml();
        $title = isset($instance['title']) ? esc_attr($instance['title']) : __('Latest News','z-companion');
        $cate = isset($instance['cate']) ? absint($instance['cate']) : 0;
        $count = isset($instance['count']) ? absint($instance['count']) : 3;
       


$termarr = array('child_of'   => 0);
$terms = get_terms('category' ,$termarr);
$foption = '<option value="0">All Post Show</option>';
foreach($terms as $cat) {
    $term_id = $cat->term_id;
    $selected1 = ($cate==$term_id)?'selected':'';
$foption .= '<option value="'.$term_id.'" '.$selected1.'>'.$cat->name.'</option>';
}
    ?>

        <div class="clearfix"></div>
        
    <p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Latest News Title','z-companion'); ?></label>
    <input name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>"  class="widefat" value="<?php echo $title; ?>" >
    </p>
     <p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Add Number of Post Show','z-companion'); ?></label>
            <input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" size="3" /></p>
        <p>
    <p>
    <label for="<?php echo $this->get_field_id('cate'); ?>"><?php _e('Select Specific Option To Display Post','z-companion'); ?></label>
        <select name="<?php echo $this->get_field_name('cate'); ?>" ><?php echo $foption; ?></select>
    </p>
    <?php 
      $arr2 = array('id'=>'orderby',
          'label'=> __('Show Post Orderby ','z-companion'),
          'default' => 'post_date',
          'option' => array('post_date'=>__('Recent Posts','z-companion'),
                            'rand'=>__('Random Post','z-companion'),
                            'comment_count' =>__('Popular Posts','z-companion'))
          );
        $widgetInput->selectBox($this,$instance,$arr2);
        ?>

            <?php 
        $arr1 = array('id'=>'exclude',
         'h5'=> __('Unique Post Option','z-companion'),
          'label'=> __('(Post displaying in this section will be excluded from all bottom sections. You can use this option to stop repeated post)','z-companion'),
          'span' => __('Check here to display unique post','z-companion')
          );
         $widgetInput->radioBox($this,$instance,$arr1);?>
        <?php
    }
}
?>