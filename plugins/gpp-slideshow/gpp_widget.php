<?php

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */

add_action( 'widgets_init', 'gpp_gallery_load_widgets' );

/**
 * Register our widget.
 * 'GPP_Gallery_Widget' is the widget class used below.
 *
 * @since 0.1
 */

function gpp_gallery_load_widgets() {
    register_widget( 'GPP_Gallery_Widget' );
}

/**
 * Example Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */

class GPP_Gallery_Widget extends WP_Widget {

    /**
     * Widget setup.
     */
    function GPP_Gallery_Widget() {
        /* Widget settings. */
        $widget_ops = array( 'classname' => 'Gallery Slideshow', 'description' => __('A widget that displays a specific slideshow gallery. Only add this to widgetized regions that span the full width of the page.  Adding this to the Sidebar and Footer area is not recommended.', 'gpp_gallery') );

        /* Widget control settings. */
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'gpp-gallery-widget' );

        /* Create the widget. */
        $this->WP_Widget( 'gpp-gallery-widget', __('Gallery Slideshow', 'gpp_gallery'), $widget_ops, $control_ops );
    }

    /**
     * How to display the widget on the screen.
     */
     function widget($args, $instance) {
    extract($args);
    global $post;

    $gallery_id  = ( $instance['gallery_id'] != '' ) ? esc_attr($instance['gallery_id']) : 'Test';

        /* Our variables from the widget settings. */
        $show_gallery_title = isset( $instance['show_gallery_title'] ) ? $instance['show_gallery_title'] : false;

        /* Before widget (defined by themes). */
        echo $before_widget;

    // Output the query to find the custom post

    $args = array( 'post_type' => 'any', 'p' => $gallery_id );
    $the_query = new WP_Query( $args );

      while ( $the_query->have_posts() ) : $the_query->the_post();

        if ( $show_gallery_title )
          echo the_title($before_title, $after_title); // This is the line that displays the title

        echo gpp_gallery_images('large');

        echo '<p class="gpp-gallery-description">', get_post_meta($post->ID, 'gpp_gallery_description', true), '</p>';
        endwhile;
    wp_reset_postdata();


    // Output $after_widget
    echo $after_widget;
  }

    /**
     * Update the widget settings.
     */
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['gallery_id'] = strip_tags($new_instance['gallery_id']);
    $instance['show_gallery_title'] = $new_instance['show_gallery_title'];

    return $instance;
  }

    /**
     * Displays the widget settings controls on the widget panel.
     * Make use of the get_field_id() and get_field_name() function
     * when creating your form elements. This handles the confusing stuff.
     */
  function form($instance) {
    $gallery_id = isset($instance['gallery_id']) ? esc_attr($instance['gallery_id']) : 0;
        $show_gallery_title  = isset($instance['show_gallery_title ']) ? $instance['show_gallery_title '] : true;

    ?>

        <p id="typepost">
            <label for="<?php echo $this->get_field_id( 'gallery_id' ); ?>"><?php echo __('Gallery to display:') ?>
                <select class="widefat" id="<?php echo $this->get_field_id('gallery_id'); ?>" name="<?php echo $this->get_field_name('gallery_id'); ?>">
                <?php
                global $post;
                $widgetExtraTitle = '';
                $args = array('post_type'=>'gallery','numberposts'=>-1,'order'=>'DESC','orderby'=>'ID');
                $mygalleries = get_posts($args);
                foreach( $mygalleries as $post ) :  setup_postdata($post);
                        $currentID = get_the_ID();
                        if($currentID == $gallery_id)
                          $extra = 'selected' and
                          $widgetExtraTitle = get_the_title();
                        else
                          $extra = '';
                      echo '<option value="'.$currentID.'" '.$extra.'>Gallery - '.get_the_title().'</option>';
                endforeach;
                $args = array('post_type'=>'post','numberposts'=>-1,'order'=>'DESC','orderby'=>'ID');
                $myposts = get_posts($args);
                foreach( $myposts as $post ) :  setup_postdata($post);
                        if ( stripos($post->post_content, '[gallery') !== false){
                            $currentID = get_the_ID();
                            if($currentID == $gallery_id){
                                $extra = 'selected' and
                                $widgetExtraTitle = get_the_title();
                            }else{
                                $extra = '';
                            }
                            echo '<option value="'.$currentID.'" '.$extra.'>Post - '.get_the_title().'</option>';
                        }
                endforeach;
                $args = array('post_type'=>'page','numberposts'=>-1,'order'=>'DESC','orderby'=>'ID');
                $mypages = get_posts($args);

                foreach( $mypages as $post ) :  setup_postdata($post);
                        if ( stripos($post->post_content, '[gallery') !== false){
                            $currentID = get_the_ID();
                            if($currentID == $gallery_id){
                                $extra = 'selected' and
                                $widgetExtraTitle = get_the_title();
                            }else{
                                $extra = '';
                            }
                            echo '<option value="'.$currentID.'" '.$extra.'>Page - '.get_the_title().'</option>';
                        }
                endforeach;
                ?>
                </select>
            </label>
        </p>

     <input type="hidden" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $widgetExtraTitle; ?>" />
     <?php wp_reset_query(); ?>

      <p>
        <input class="checkbox" type="checkbox" value="1" <?php if (isset($instance['show_gallery_title'])) checked( '1', $instance['show_gallery_title']); ?> id="<?php echo $this->get_field_id( 'show_gallery_title' ); ?>" name="<?php echo $this->get_field_name( 'show_gallery_title' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'show_gallery_title' ); ?>"><?php echo __('Show Post Title') ?></label>
      </p>

      <?php
  }
}