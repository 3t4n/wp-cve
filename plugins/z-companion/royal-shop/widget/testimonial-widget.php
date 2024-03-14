<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
 *  Testimonial widget
 *  
 *
 */
// add admin scripts
function z_companion_royal_shop_testimonial_widget_enqueue(){
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'z_companion_royal_shop_testimonial_widget_enqueue');
// register widget
function z_companion_royal_shop_testimonial_widget(){
register_widget( 'Z_COMPANION_Testimonial' );
}
add_action('widgets_init','z_companion_royal_shop_testimonial_widget');
class Z_COMPANION_Testimonial extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'wzta-testimonial',
            'description' => 'Displays Testimonial');
        parent::__construct('wzta-testimonial-widget', __('Royal Shop : Testimonial Widget','z-companion'), $widget_ops);
    }

    function widget($args, $instance) {
        extract($args);
        // widget content
        echo $before_widget;
        $widgettitle = isset($instance['widgettitle'])?$instance['widgettitle']:'';
        $title1 = isset($instance['title1'])?$instance['title1']:'';
        $title2 = isset($instance['title2'])?$instance['title2']:'';
        $title3 = isset($instance['title3'])?$instance['title3']:'';
        $title4 = isset($instance['title4'])?$instance['title4']:'';
        $title5 = isset($instance['title5'])?$instance['title5']:'';
        $text1 = isset($instance['text1'])?$instance['text1']:'';
        $text2 = isset($instance['text2'])?$instance['text2']:'';
        $text3 = isset($instance['text3'])?$instance['text3']:'';
        $text4 = isset($instance['text4'])?$instance['text4']:'';
        $text5 = isset($instance['text5'])?$instance['text5']:'';
        $author_img_uri1 = isset($instance['author_img_uri1'])?$instance['author_img_uri1']:'';
        $author_img_uri2 = isset($instance['author_img_uri2'])?$instance['author_img_uri2']:'';
        $author_img_uri3 = isset($instance['author_img_uri3'])?$instance['author_img_uri3']:'';   
        $author_img_uri4 = isset($instance['author_img_uri4'])?$instance['author_img_uri4']:'';
        $author_img_uri5 = isset($instance['author_img_uri5'])?$instance['author_img_uri5']:'';  
        ?>
            <div class="wzta-testimonial-container">
                <h2 class="widget-title"><span>
                <?php echo esc_html($widgettitle); ?>
                </span></h2>
                <div id="<?php echo $widget_id; ?>" class="wzta-testimonial-wrapper owl-carousel">
                <?php if($author_img_uri1!=='' || $title1!=='' ){?>    
                <div class="wzta-testimonial-list">
                    <div class="wzta-testimonial-img">
                    <img src="<?php echo esc_url($author_img_uri1); ?>" />
                    </div>
                    <h4 class="wzta-testimonial-title">
                     <?php echo esc_html($title1); ?>
                    </h4>
                    <p class="wzta-testimonial-content">
                            <?php echo esc_html($text1); ?>
                    </p>
                </div>
              <?php } if($author_img_uri2!=='' || $title2!=='' ){?>
                <div class="wzta-testimonial-list">
                    <div class="wzta-testimonial-img">
                    <img src="<?php echo esc_url($author_img_uri2); ?>" />
                    </div>
                    <h4 class="wzta-testimonial-title">
                     <?php echo esc_html($title2); ?>
                    </h4>
                    <p class="wzta-testimonial-content">
                     <?php echo esc_html($text2); ?>
                    </p>
                </div>
            <?php } if($author_img_uri3!=='' || $title3!=='' ){?>
                <div class="wzta-testimonial-list">
                    <div class="wzta-testimonial-img">
                    <img src="<?php echo esc_url($author_img_uri3); ?>" />
                    </div>
                    <h4 class="wzta-testimonial-title">
                     <?php echo esc_html($title3); ?>
                    </h4>
                    <p class="wzta-testimonial-content">
                        <?php echo esc_html($text3); ?>
                    </p>
                </div>
            <?php }?>
            <?php if($author_img_uri4!=='' || $title4!=='' ){?>    
                <div class="wzta-testimonial-list">
                    <div class="wzta-testimonial-img">
                    <img src="<?php echo esc_url($author_img_uri4); ?>" />
                    </div>
                    <h4 class="wzta-testimonial-title">
                     <?php echo esc_html($title4); ?>
                    </h4>
                    <p class="wzta-testimonial-content">
                            <?php echo esc_html($text4); ?>
                    </p>
                </div>
              <?php } ?>
              <?php if($author_img_uri5!=='' || $title5!=='' ){?>    
                <div class="wzta-testimonial-list">
                    <div class="wzta-testimonial-img">
                    <img src="<?php echo esc_url($author_img_uri5); ?>" />
                    </div>
                    <h4 class="wzta-testimonial-title">
                     <?php echo esc_html($title5); ?>
                    </h4>
                    <p class="wzta-testimonial-content">
                            <?php echo esc_html($text5); ?>
                    </p>
                </div>
              <?php } ?>
            </div>
            </div>
<script>
 ///-----------------------///
// Testimonial script
///-----------------------///
jQuery(document).ready(function(){
var wdgetid = '<?php echo $widget_id; ?>'; 

jQuery('#'+wdgetid+'.owl-carousel').owlCarousel({  
    items:1,
    loop:true,
    nav: true,
    margin:0,
    autoplay:false,
    autoplaySpeed:500,
    autoplayTimeout:2000,
    autoplayHoverPause: true,
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
        $instance['widgettitle'] = $new_instance['widgettitle'];
        $instance['text1'] = $new_instance['text1'];
        $instance['title1'] = strip_tags( $new_instance['title1'] );
        $instance['author_img_uri1'] = strip_tags( $new_instance['author_img_uri1'] );
        $instance['text2'] = $new_instance['text2'];
        $instance['title2'] = strip_tags( $new_instance['title2'] );
        $instance['author_img_uri2'] = strip_tags( $new_instance['author_img_uri2'] );
        $instance['text3'] = $new_instance['text3'];
        $instance['title3'] = strip_tags( $new_instance['title3'] );
        $instance['author_img_uri3'] = strip_tags( $new_instance['author_img_uri3'] );
        $instance['text4'] = $new_instance['text4'];
        $instance['title4'] = strip_tags( $new_instance['title4'] );
        $instance['author_img_uri4'] = strip_tags( $new_instance['author_img_uri4'] );
        $instance['text5'] = $new_instance['text5'];
        $instance['title5'] = strip_tags( $new_instance['title5'] );
        $instance['author_img_uri5'] = strip_tags( $new_instance['author_img_uri5'] );
        
        return $instance;
    }

    function form($instance) {
    if( $instance) {
        $widgettitle = esc_attr($instance['widgettitle']);
        $title1 = esc_attr($instance['title1']);
        $text1 = esc_attr($instance['text1']);
        $author_img_uri1 = esc_attr($instance['author_img_uri1']);
        $title2 = esc_attr($instance['title2']);
        $text2 = esc_attr($instance['text2']);
        $author_img_uri2 = esc_attr($instance['author_img_uri2']);
        $title3 = esc_attr($instance['title3']);
        $text3 = esc_attr($instance['text3']);
        $author_img_uri3 = esc_attr($instance['author_img_uri3']);
        $title4 = esc_attr($instance['title4']);
        $text4 = esc_attr($instance['text4']);
        $author_img_uri4 = esc_attr($instance['author_img_uri4']);
        $title5 = esc_attr($instance['title5']);
        $text5 = esc_attr($instance['text5']);
        $author_img_uri5 = esc_attr($instance['author_img_uri5']);

        
        
    } else {
        $widgettitle = 'Testimonial';
        $title1 = '';
        $text1 = '';
        $author_img_uri1 = '';
        $title2 = '';
        $text2 = '';
        $author_img_uri2 = '';
        $title3 = '';
        $text3 = '';
        $author_img_uri3 = '';
        $title4 = '';
        $text4 = '';
        $author_img_uri4 = '';
        $title5 = '';
        $text5 = '';
        $author_img_uri5 = '';
        
    }


    ?>
<div class="clearfix"></div>
    <p> 
        <label for="<?php echo $this->get_field_id('widgettitle'); ?>"><?php _e('Widget Title','z-companion'); ?></label> 
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('widgettitle'); ?>" id="<?php echo $this->get_field_id('widgettitle'); ?>" value="<?php  echo $widgettitle; ?>" style="margin-top:5px;">
     </p>
    
    <p>
        <label for="<?php echo $this->get_field_id('author_img_uri1'); ?>"><?php _e('Image 1','z-companion'); ?></label>
        <?php
            if ( isset($instance['author_img_uri1']) && $instance['author_img_uri1'] != '' ) :
                echo '<img id="'.$this->get_field_id('author_img_uri1').'" class="custom_media_image" src="' . $instance['author_img_uri1'] . '" style="margin:0;padding:0;max-width:100px;float:left;display:inline-block" /><br />';
            endif;
        ?>
        <input type="text" class="widefat custom_media_url" name="<?php echo $this->get_field_name('author_img_uri1'); ?>" id="<?php echo $this->get_field_id('author_img_uri1'); ?>" value="<?php if(isset($instance["author_img_uri1"])){ echo $instance['author_img_uri1']; } ?>" style="margin-top:5px;">
        <input type="button" class="button button-primary custom_media_button" id="<?php echo $this->get_field_id('author_img_uri1'); ?>" name="<?php echo $this->get_field_name('author_img_uri1'); ?>" value="Upload Image" style="margin-top:5px;" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('title1'); ?>"><?php _e('Name','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('title1'); ?>" id="<?php echo $this->get_field_id('title1'); ?>" value="<?php  if(isset($instance["title1"])){ echo $instance['title1']; } ?>" style="margin-top:5px;">
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('text1'); ?>"><?php _e('Description','z-companion'); ?></label>
        <textarea  name="<?php echo $this->get_field_name('text1'); ?>" id="<?php echo $this->get_field_id('text1'); ?>"  class="widefat" >
        <?php if(isset($instance["text1"])){ echo $instance['text1']; } ?></textarea>
    </p>


    
<p>
        <label for="<?php echo $this->get_field_id('author_img_uri2'); ?>"><?php _e('Image 2','z-companion'); ?></label>
        <?php
            if ( isset($instance['author_img_uri2']) && $instance['author_img_uri2'] != '' ) :
                echo '<img id="'.$this->get_field_id('author_img_uri2').'" class="custom_media_image" src="' . $instance['author_img_uri2'] . '" style="margin:0;padding:0;max-width:100px;float:left;display:inline-block" /><br />';
            endif;
        ?>
        <input type="text" class="widefat custom_media_url" name="<?php echo $this->get_field_name('author_img_uri2'); ?>" id="<?php echo $this->get_field_id('author_img_uri2'); ?>" value="<?php if(isset($instance["author_img_uri2"])){ echo $instance['author_img_uri2']; } ?>" style="margin-top:5px;">
        <input type="button" class="button button-primary custom_media_button" id="<?php echo $this->get_field_id('author_img_uri2'); ?>" name="<?php echo $this->get_field_name('author_img_uri2'); ?>" value="Upload Image" style="margin-top:5px;" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('title2'); ?>"><?php _e('Name','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('title2'); ?>" id="<?php echo $this->get_field_id('title2'); ?>" value="<?php  if(isset($instance["title2"])){ echo $instance['title2']; } ?>" style="margin-top:5px;">
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('text2'); ?>"><?php _e('Description','z-companion'); ?></label>
        <textarea  name="<?php echo $this->get_field_name('text2'); ?>" id="<?php echo $this->get_field_id('text2'); ?>"  class="widefat" >
        <?php if(isset($instance["text2"])){ echo $instance['text2']; } ?></textarea>
    </p>
    
    <p>
        <label for="<?php echo $this->get_field_id('author_img_uri3'); ?>"><?php _e('Image 3','z-companion'); ?></label>
        <?php
            if ( isset($instance['author_img_uri3']) && $instance['author_img_uri3'] != '' ) :
                echo '<img id="'.$this->get_field_id('author_img_uri3').'" class="custom_media_image" src="' . $instance['author_img_uri3'] . '" style="margin:0;padding:0;max-width:100px;float:left;display:inline-block" /><br />';
            endif;
        ?>
        <input type="text" class="widefat custom_media_url" name="<?php echo $this->get_field_name('author_img_uri3'); ?>" id="<?php echo $this->get_field_id('author_img_uri3'); ?>" value="<?php if(isset($instance["author_img_uri3"])){ echo $instance['author_img_uri3']; } ?>" style="margin-top:5px;">
        <input type="button" class="button button-primary custom_media_button" id="<?php echo $this->get_field_id('author_img_uri3'); ?>" name="<?php echo $this->get_field_name('author_img_uri3'); ?>" value="Upload Image" style="margin-top:5px;" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('title3'); ?>"><?php _e('Name','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('title3'); ?>" id="<?php echo $this->get_field_id('title3'); ?>" value="<?php  if(isset($instance["title3"])){ echo $instance['title3']; } ?>" style="margin-top:5px;">
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('text3'); ?>"><?php _e('Description','z-companion'); ?></label>
        <textarea  name="<?php echo $this->get_field_name('text3'); ?>" id="<?php echo $this->get_field_id('text3'); ?>"  class="widefat" >
        <?php if(isset($instance["text3"])){ echo $instance['text3']; } ?></textarea>
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('author_img_uri4'); ?>"><?php _e('Image 4','z-companion'); ?></label>
        <?php
            if ( isset($instance['author_img_uri4']) && $instance['author_img_uri4'] != '' ) :
                echo '<img id="'.$this->get_field_id('author_img_uri4').'" class="custom_media_image" src="' . $instance['author_img_uri4'] . '" style="margin:0;padding:0;max-width:100px;float:left;display:inline-block" /><br />';
            endif;
        ?>
        <input type="text" class="widefat custom_media_url" name="<?php echo $this->get_field_name('author_img_uri4'); ?>" id="<?php echo $this->get_field_id('author_img_uri4'); ?>" value="<?php if(isset($instance["author_img_uri4"])){ echo $instance['author_img_uri4']; } ?>" style="margin-top:5px;">
        <input type="button" class="button button-primary custom_media_button" id="<?php echo $this->get_field_id('author_img_uri4'); ?>" name="<?php echo $this->get_field_name('author_img_uri4'); ?>" value="Upload Image" style="margin-top:5px;" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('title4'); ?>"><?php _e('Name','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('title4'); ?>" id="<?php echo $this->get_field_id('title4'); ?>" value="<?php  if(isset($instance["title4"])){ echo $instance['title4']; } ?>" style="margin-top:5px;">
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('text4'); ?>"><?php _e('Description','z-companion'); ?></label>
        <textarea  name="<?php echo $this->get_field_name('text4'); ?>" id="<?php echo $this->get_field_id('text4'); ?>"  class="widefat" >
        <?php if(isset($instance["text4"])){ echo $instance['text4']; } ?></textarea>
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('author_img_uri5'); ?>"><?php _e('Image 5','z-companion'); ?></label>
        <?php
            if ( isset($instance['author_img_uri5']) && $instance['author_img_uri5'] != '' ) :
                echo '<img id="'.$this->get_field_id('author_img_uri5').'" class="custom_media_image" src="' . $instance['author_img_uri5'] . '" style="margin:0;padding:0;max-width:100px;float:left;display:inline-block" /><br />';
            endif;
        ?>
        <input type="text" class="widefat custom_media_url" name="<?php echo $this->get_field_name('author_img_uri5'); ?>" id="<?php echo $this->get_field_id('author_img_uri5'); ?>" value="<?php if(isset($instance["author_img_uri5"])){ echo $instance['author_img_uri5']; } ?>" style="margin-top:5px;">
        <input type="button" class="button button-primary custom_media_button" id="<?php echo $this->get_field_id('author_img_uri5'); ?>" name="<?php echo $this->get_field_name('author_img_uri5'); ?>" value="Upload Image" style="margin-top:5px;" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('title5'); ?>"><?php _e('Name','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('title5'); ?>" id="<?php echo $this->get_field_id('title5'); ?>" value="<?php  if(isset($instance["title5"])){ echo $instance['title5']; } ?>" style="margin-top:5px;">
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('text5'); ?>"><?php _e('Description','z-companion'); ?></label>
        <textarea  name="<?php echo $this->get_field_name('text5'); ?>" id="<?php echo $this->get_field_id('text5'); ?>"  class="widefat" >
        <?php if(isset($instance["text5"])){ echo $instance['text5']; } ?></textarea>
    </p>
<?php
    }
}