<?php
/*
Plugin Name: Page Excerpts
Plugin URI: http://xplus3.net/2010/09/15/excerpts-for-wordpress-pages/
Description: Adds an excerpt field to pages, and provides a widget for displaying page excerpts
Author: Flightless
Contributors: jbrinley
Author URI: http://flightless.us/
Version: 1.0.2
*/

function page_excerpts_init() {
  add_post_type_support('page', array('excerpt'));
}
add_action('init', 'page_excerpts_init');

function page_excerpts_widgets_init() {
  register_widget('Page_Excerpts');
}
add_action( 'widgets_init', 'page_excerpts_widgets_init' );

class Page_Excerpts extends WP_Widget {
  function __construct() {
    $widget_ops = array(
      'classname' => 'page-excerpts-widget',
      'description' => 'Displays the title and excerpt of the selected page',
    );
    $control_ops = array(
      'width' => 300,
      'height' => 150,
      'id_base' => 'page-excerpts-widget',
    );
    parent::__construct('page-excerpts-widget', 'Page Excerpt', $widget_ops, $control_ops);
  }
  
  function widget( $args, $instance ) {
    extract($args);
    
    $query = new WP_Query( array(
      'page_id' => (int)$instance['page_id'],
    ) );
    if ( $query->have_posts() ) {
      $query->the_post();
      $title = $instance['title'];
      if ( $title == '' ) {
        $title = get_the_title();
      } elseif ( $title == '<none>' ) {
        $title = '';
      }
      $title = apply_filters('widget_title', $title);
      echo $before_widget;
      echo '<div class="post post-'.get_the_ID().'">';
      if ( $title ) {
        echo $before_title.'<a href="'.get_permalink().'">'.$title.'</a>'.$after_title;
      }
      echo '<div class="entry">';
      the_excerpt();
      echo '</div>';
      echo '</div>';
      echo $after_widget;
      rewind_posts();
    }
  }
  
  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['page_id'] = (int)strip_tags($new_instance['page_id']);
    return $instance;
  }
  
  function form( $instance ) {
    $defaults = array('title' => '', 'page_id' => 0);
    $instance = wp_parse_args( (array)$instance, $defaults );
    ?><p><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
      <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($instance['title']); ?>" style="width: 98%" />
      <br /><small>Leave blank to use page title. Use <code>&lt;none&gt;</code> for no title.</small></p>
    <p><label for="<?php echo $this->get_field_id('page_id'); ?>">Page:</label>
      <?php wp_dropdown_pages(array(
        'selected' => $instance['page_id'],
        'name' => $this->get_field_name('page_id'),
      )); ?></p>
    <?php
  }
}
