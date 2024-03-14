<?php

class WDIViewWidget {
 
  private $model;
  
  public function __construct($model) {
    $this->model = $model;
  }  

  public function display() {
  }

  function widget($args, $instance) {
    extract($args);
    $title = (isset($instance['title']) ? $instance['title'] : "");
    $feed_id = (isset($instance['feed_id']) ? $instance['feed_id'] : 0);
    $img_number = (isset($instance['img_number']) ? $instance['img_number'] : 4);
    $show_description = (isset($instance['show_description']) ? $instance['show_description'] : 0);
    $show_likes_comments = (isset($instance['show_likes_comments']) ? $instance['show_likes_comments'] : 0);
    $number_of_columns = (isset($instance['number_of_columns']) ? $instance['number_of_columns'] : 1);
    $enable_loading_buttons = (isset($instance['enable_loading_buttons']) ? $instance['enable_loading_buttons'] : 0);
    // Format an array of allowed HTML tags and attributes.
    $allowed_html = array(
      'div' => array(
        'class'  => true
      ),
      'h2' => array(
        'class'  => true
      )
    );
    // Before widget.
    echo wp_kses($before_widget, $allowed_html);
    // Title of widget.
    if ($title) {
      echo wp_kses($before_title, $allowed_html) . esc_html($title) . wp_kses($after_title, $allowed_html);
    }
    // Widget output.
    $widget_params = array(
      'widget' => true,
      'widget_image_num' => $img_number,
      'widget_show_description' => $show_description,
      'widget_show_likes_and_comments' => $show_likes_comments,
      'number_of_columns'=>$number_of_columns,
      'enable_loading_buttons' => $enable_loading_buttons,
      );

    // "wdi_feed" is the function of displaying the news feed, it describes the whole operation. All variables in the function are esc.
    // Prints feed data.
	  /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */
    echo wdi_feed( array('id'=>$feed_id), $widget_params );

    // After widget.
    echo wp_kses($after_widget, $allowed_html);
  }
  
  // Widget Control Panel.
  function form($instance, 
                  $id_title, $name_title,
                  $id_feed_id, $name_feed_id,
                  $id_img_number, $name_img_number,
                  $id_show_description, $name_show_description,
                  $id_show_likes_comments, $name_show_likes_comments,
                  $id_number_of_columns,$name_number_of_columns,
                  $id_enable_loading_buttons, $name_enable_loading_buttons) {

    $defaults = array(
			'title' => 'Instagram Feed',
      'feed_id' => 1,
      'img_number' => 4,
      'show_description' => 0,
      'show_likes_comments' => 0,
      'number_of_columns' => 1,
      'enable_loading_buttons' => 0
		);
    require_once(WDI_DIR . '/framework/WDILibrary.php');
    $feeds = WDILibrary::objectToArray($this->model->get_feeds());
    $instance = wp_parse_args((array) $instance, $defaults);
    ?>

    <p>
      <label for="<?php echo esc_attr($id_title); ?>"><?php _e("Title", 'wd-instagram-feed'); ?></label>
      <input class="widefat" id="<?php echo esc_attr($id_title); ?>" name="<?php echo esc_attr($name_title); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>"/>
    </p>
    <p>
      <label for="<?php echo esc_attr($id_feed_id); ?>"><?php _e("Feed", 'wd-instagram-feed'); ?></label>
      <select onchange="wdi_toggle(jQuery(this));" class="widefat" id="<?php echo esc_attr($id_feed_id); ?>" name="<?php echo esc_attr($name_feed_id); ?>" >
        <?php foreach ($feeds as $feed) {
          ?>
          <option <?php if($instance['feed_id'] == $feed['id']) echo 'selected'?> value="<?php echo esc_attr($feed['id']);?>"><?php echo esc_html($feed['feed_name']);?></option>
          <?php
        }?>
      </select>
    </p>
    
    <p class="wdi_number_of_columns">
      <label for="<?php echo esc_attr($id_number_of_columns); ?>"><?php _e("Number of columns", 'wd-instagram-feed'); ?></label>
      <select class="widefat" id="<?php echo esc_attr($id_number_of_columns); ?>" name="<?php echo esc_attr($name_number_of_columns); ?>" >
        <?php for ($k = 1 ;$k <= 10; $k++) {
          ?>
          <option <?php if($instance['number_of_columns'] == $k) echo 'selected'?> value="<?php echo esc_attr($k) ?>"><?php echo esc_html($k); ?></option>
          <?php
        }?>
      </select>
    </p>


    <p>
      <label for="<?php echo esc_attr($id_img_number); ?>"><?php _e("Number of images to show", 'wd-instagram-feed'); ?></label>
      <input class="widefat" id="<?php echo esc_attr($id_img_number); ?>" name="<?php echo esc_attr($name_img_number); ?>" type="text" value="<?php echo esc_attr($instance['img_number']); ?>"/>
    </p>
    <p>
      <input <?php if($instance['show_description']=='1') echo "checked"?> class="widefat" id="<?php echo esc_attr($id_show_description); ?>" name="<?php echo esc_attr($name_show_description); ?>" type="checkbox" value="<?php echo esc_attr($instance['show_description']); ?>"/>
      <label for="<?php echo esc_attr($id_show_description); ?>"><?php _e("Show Description", 'wd-instagram-feed'); ?></label>
    </p>
    <p>
      <input <?php if($instance['show_likes_comments']=='1') echo "checked"?> class="widefat" id="<?php echo esc_attr($id_show_likes_comments); ?>" name="<?php echo esc_attr($name_show_likes_comments); ?>" type="checkbox" value="<?php echo esc_attr($instance['show_likes_comments']); ?>"/>
      <label for="<?php echo esc_attr($id_show_likes_comments); ?>"><?php _e("Show likes and comments", 'wd-instagram-feed'); ?></label>
    </p>
    <p>
      <input <?php if($instance['enable_loading_buttons']=='1') echo "checked"?> class="widefat" id="<?php echo esc_attr($id_enable_loading_buttons); ?>" name="<?php echo esc_attr($name_enable_loading_buttons); ?>" type="checkbox" value="<?php echo esc_attr($instance['enable_loading_buttons']); ?>"/>
      <label for="<?php echo esc_attr($id_enable_loading_buttons); ?>"><?php _e("Enable loading new images", 'wd-instagram-feed'); ?></label>
    </p>
    <script>
    jQuery(document).ready(function(){
      wdi_toggle(jQuery('#<?php echo esc_attr($id_feed_id); ?>'));
    });

    function wdi_toggle(select){
      var feed_list = <?php echo wp_json_encode($feeds);?>;
      var id = select.val();
      for(var i = 0 ; i < feed_list.length; i++){
        if(feed_list[i]['id'] == id){
          if(feed_list[i]['feed_type'] == 'blog_style'){
            select.parent().parent().find('.wdi_number_of_columns').css('display','none');
          }else{
            select.parent().parent().find('.wdi_number_of_columns').css('display','block');
          }
        }
      }
    }
    </script>
    <?php
  }
}