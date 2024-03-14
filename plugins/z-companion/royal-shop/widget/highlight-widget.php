<?php
/*
 *  Multi-Highlight Widget
 *
 */

// register widget
add_action('widgets_init', 'z_companion_royal_shop_highlights_column_widget');
function z_companion_royal_shop_highlights_column_widget() {
    register_widget( 'Z_COMPANION_Highlights_Column' );
}

// royal_shop_highlights_column widget class
class Z_COMPANION_Highlights_Column extends WP_Widget {
    
    function __construct() {
        $widget_ops = array('classname' => 'royalshop-highlights-column',
            'description' => 'Show your highlights provided');
        parent::__construct('royalshop-highlights-column-widget', __('Royal Shop : Highlight Widget','z-companion'), $widget_ops);
    }

    function widget($args, $instance) {
        extract($args);

        // widget content
        echo $before_widget;
      $widgettitle = isset($instance['widgettitle'])?$instance['widgettitle']:'Best offers';
        $text1 = isset($instance['text1'])?$instance['text1']:'30 days refund';
        $title1 = isset($instance['title1'])?$instance['title1']:'Join Risk Free';
        $fontaws1 = isset($instance['fontaws1'])?$instance['fontaws1']:'fa fa-diamond';

        $text2 = isset($instance['text2'])?$instance['text2']:'Secure Shopping';
        $title2 = isset($instance['title2'])?$instance['title2']:'100% Safe';
        $fontaws2 = isset($instance['fontaws2'])?$instance['fontaws2']:'fa fa-anchor';
        
        $text3 = isset($instance['text3'])?$instance['text3']:'Online 24 hours';
        $title3 = isset($instance['title3'])?$instance['title3']:'24x7 Support';
        $fontaws3 = isset($instance['fontaws3'])?$instance['fontaws3']:'fa fa-coffee';

        $text4 = isset($instance['text4'])?$instance['text4']:'Grab Now';
        $title4 = isset($instance['title4'])?$instance['title4']:'Best Offers';
        $fontaws4 = isset($instance['fontaws4'])?$instance['fontaws4']:'fa fa-shopping-basket';

        $text5 = isset($instance['text5'])?$instance['text5']:'On all order over';
        $title5 = isset($instance['title5'])?$instance['title5']:'Free Shiping';
        $fontaws5 = isset($instance['fontaws5'])?$instance['fontaws5']:'fa fa-plane';
?>
        <div class="wzta-hlight-wrapper">
          <h2 class="widget-title"><span>
          <?php  echo esc_html($widgettitle); ?>
        </span></h2>
        <div class="wzta-hlight-box">
        <div class="wzta-hlight">
            <div class="wzta-hlight-icon">        
                <i  class="<?php echo esc_attr($fontaws1); ?>"></i>
            </div>
            
            <div class="wzta-hlight-text-wrap">
              <h2 class="wzta-hlight-title">
                <?php echo esc_html($title1); ?>
              </h2>
              <p class="wzta-hlight-content"><?php echo esc_html($text1); ?></p>
            </div>
        </div>

        <div class="wzta-hlight">
            <div class="wzta-hlight-icon">        
                <i  class="<?php echo esc_attr($fontaws2); ?>"></i>
            </div>
            <div class="wzta-hlight-text-wrap">
              <h2 class="wzta-hlight-title">
                <?php echo esc_html($title2); ?>
              </h2>
              <p class="wzta-hlight-content"><?php echo esc_html($text2); ?></p>
            </div>
        </div>


      <div class="wzta-hlight">
            <div class="wzta-hlight-icon">        
                <i  class="<?php echo esc_attr($fontaws3); ?>"></i>
            </div>
            <div class="wzta-hlight-text-wrap">
              <h2 class="wzta-hlight-title">
                <?php echo esc_html($title3); ?>
              </h2>
              <p class="wzta-hlight-content"><?php echo esc_html($text3); ?></p>
            </div>
        </div>


      <div class="wzta-hlight">
            <div class="wzta-hlight-icon">        
                <i  class="<?php echo esc_attr($fontaws4); ?>"></i>
            </div>
            <div class="wzta-hlight-text-wrap">
              <h2 class="wzta-hlight-title">
                <?php echo esc_html($title4); ?>
              </h2>
             <p class="wzta-hlight-content"><?php echo esc_html($text4); ?></p>
            </div>
        </div>

         <div class="wzta-hlight">
            <div class="wzta-hlight-icon">        
                <i  class="<?php echo esc_attr($fontaws5); ?>"></i>
            </div>
            <div class="wzta-hlight-text-wrap">
              <h2 class="wzta-hlight-title">
                <?php echo esc_html($title5); ?>
              </h2>
              <p class="wzta-hlight-content"><?php echo esc_html($text5); ?></p>
            </div>
        </div>
      </div>
      </div>


<?php
        echo $after_widget;

    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['widgettitle'] = strip_tags( $new_instance['widgettitle'] );
        $instance['fontaws1'] = $new_instance['fontaws1'];
        $instance['title1'] = strip_tags( $new_instance['title1'] );
        $instance['text1'] = $new_instance['text1'];
        $instance['fontaws2'] = $new_instance['fontaws2'];
        $instance['title2'] = strip_tags( $new_instance['title2'] );
        $instance['text2'] = $new_instance['text2'];
        $instance['fontaws3'] = $new_instance['fontaws3'];
        $instance['title3'] = strip_tags( $new_instance['title3'] );
        $instance['text3'] = $new_instance['text3'];
        $instance['fontaws4'] = $new_instance['fontaws4'];
        $instance['title4'] = strip_tags( $new_instance['title4'] );
        $instance['text4'] = $new_instance['text4'];
        $instance['fontaws5'] = $new_instance['fontaws5'];
        $instance['title5'] = strip_tags( $new_instance['title5'] );
        $instance['text5'] = $new_instance['text5'];
        return $instance;
    }

    function form($instance) {
         if( $instance) {
        $widgettitle = esc_attr($instance['widgettitle']);
        $title1 = esc_attr($instance['title1']);
        $fontaws1 = esc_attr($instance['fontaws1']);
        $text1 = esc_attr($instance['text1']);

        $title2 = esc_attr($instance['title2']);
        $fontaws2 = esc_attr($instance['fontaws2']);
        $text2 = esc_attr($instance['text2']);

        $title3 = esc_attr($instance['title3']);
        $fontaws3 = esc_attr($instance['fontaws3']);
        $text3 = esc_attr($instance['text3']);

        $title4 = esc_attr($instance['title4']);
        $fontaws4 = esc_attr($instance['fontaws4']);
        $text4 = esc_attr($instance['text4']);

        $title5 = esc_attr($instance['title5']);
        $fontaws5 = esc_attr($instance['fontaws5']);
        $text5 = esc_attr($instance['text5']);

    } else{
      $widgettitle = 'Best offers';
      $title1 = 'Join Risk Free';
      $fontaws1 = 'fa fa-diamond';
      $text1 = '30 days refund';

      $title2 = '100% Safe';
      $fontaws2 = 'fa fa-anchor';
      $text2 = 'Secure Shopping';

      $title3 = '24x7 Support';
      $fontaws3 = 'fa fa-coffee';
      $text3 = 'Online 24 hours';

      $title4 = 'Best Offers';
      $fontaws4 = 'fa fa-shopping-basket';
      $text4 = 'Grab Now';

      $title5 = 'Free Shiping';
      $fontaws5 = 'fa fa-plane';
      $text5 = 'On all order over';
    }
    ?>
        <div class="clearfix"></div>
        <p> 
        <label for="<?php echo $this->get_field_id('widgettitle'); ?>"><?php _e('Widget Title ( Will be display in mobile view )','z-companion'); ?></label> 
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('widgettitle'); ?>" id="<?php echo $this->get_field_id('widgettitle'); ?>" value="<?php  echo $widgettitle; ?>" style="margin-top:5px;">
     </p>
      <p>
        <label for="<?php echo $this->get_field_id('title1'); ?>"><?php _e('Title','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('title1'); ?>" id="<?php echo $this->get_field_id('title1'); ?>" value="<?php  echo $title1; ?>" style="margin-top:5px;">
     </p>
     <p>
        <label for="<?php echo $this->get_field_id('text1'); ?>"><?php _e('Description','z-companion'); ?></label>
        <textarea  name="<?php echo $this->get_field_name('text1'); ?>" id="<?php echo $this->get_field_id('text1'); ?>"  class="widefat" ><?php echo $text1; ?></textarea>
     </p> 
     <p>
        <label for="<?php echo $this->get_field_id('fontaws1'); ?>"><?php _e('Font Awesome Icon','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('fontaws1'); ?>" id="<?php echo $this->get_field_id('fontaws1'); ?>" value="<?php  echo $fontaws1; ?>" style="margin-top:5px;">
     </p>

     <p>
        <label for="<?php echo $this->get_field_id('title2'); ?>"><?php _e('Title','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('title2'); ?>" id="<?php echo $this->get_field_id('title2'); ?>" value="<?php  echo $title2; ?>" style="margin-top:5px;">
     </p>
     <p>
        <label for="<?php echo $this->get_field_id('text2'); ?>"><?php _e('Description','z-companion'); ?></label>
        <textarea  name="<?php echo $this->get_field_name('text2'); ?>" id="<?php echo $this->get_field_id('text2'); ?>"  class="widefat" ><?php echo $text2; ?></textarea>
     </p> 
     <p>
        <label for="<?php echo $this->get_field_id('fontaws2'); ?>"><?php _e('Font Awesome Icon','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('fontaws2'); ?>" id="<?php echo $this->get_field_id('fontaws2'); ?>" value="<?php  echo $fontaws2; ?>" style="margin-top:5px;">
     </p>

     <p>
        <label for="<?php echo $this->get_field_id('title3'); ?>"><?php _e('Title','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('title3'); ?>" id="<?php echo $this->get_field_id('title3'); ?>" value="<?php  echo $title3; ?>" style="margin-top:5px;">
     </p>
     <p>
        <label for="<?php echo $this->get_field_id('text3'); ?>"><?php _e('Description','z-companion'); ?></label>
        <textarea  name="<?php echo $this->get_field_name('text3'); ?>" id="<?php echo $this->get_field_id('text3'); ?>"  class="widefat" ><?php echo $text3; ?></textarea>
     </p> 
     <p>
        <label for="<?php echo $this->get_field_id('fontaws3'); ?>"><?php _e('Font Awesome Icon','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('fontaws3'); ?>" id="<?php echo $this->get_field_id('fontaws3'); ?>" value="<?php  echo $fontaws3; ?>" style="margin-top:5px;">
     </p>

     <p>
        <label for="<?php echo $this->get_field_id('title4'); ?>"><?php _e('Title','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('title4'); ?>" id="<?php echo $this->get_field_id('title4'); ?>" value="<?php  echo $title4; ?>" style="margin-top:5px;">
     </p>
     <p>
        <label for="<?php echo $this->get_field_id('text4'); ?>"><?php _e('Description','z-companion'); ?></label>
        <textarea  name="<?php echo $this->get_field_name('text4'); ?>" id="<?php echo $this->get_field_id('text4'); ?>"  class="widefat" ><?php echo $text4; ?></textarea>
     </p> 
     <p>
        <label for="<?php echo $this->get_field_id('fontaws4'); ?>"><?php _e('Font Awesome Icon','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('fontaws4'); ?>" id="<?php echo $this->get_field_id('fontaws4'); ?>" value="<?php  echo $fontaws4; ?>" style="margin-top:5px;">
     </p>

     <p>
        <label for="<?php echo $this->get_field_id('title5'); ?>"><?php _e('Title','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('title5'); ?>" id="<?php echo $this->get_field_id('title5'); ?>" value="<?php  echo $title5; ?>" style="margin-top:5px;">
     </p>
     <p>
        <label for="<?php echo $this->get_field_id('text5'); ?>"><?php _e('Description','z-companion'); ?></label>
        <textarea  name="<?php echo $this->get_field_name('text5'); ?>" id="<?php echo $this->get_field_id('text5'); ?>"  class="widefat" ><?php echo $text5; ?></textarea>
     </p> 
     <p>
        <label for="<?php echo $this->get_field_id('fontaws5'); ?>"><?php _e('Font Awesome Icon','z-companion'); ?></label>
        <input type="text" class="widefat" name="<?php echo $this->get_field_name('fontaws5'); ?>" id="<?php echo $this->get_field_id('fontaws5'); ?>" value="<?php  echo $fontaws5; ?>" style="margin-top:5px;">
     </p>      

        <?php
    }
}