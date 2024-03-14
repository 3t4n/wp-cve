<?php
/*
Plugin Name: Breadcrumb based on URL
Plugin URI: https://store.devilhunter.net/wordpress-plugin/bread-crumb/
Description:  This Plugin will automatically match your Theme's style. Go to Appearance > Widgets, and drag 'Plugin' in sidebar or footer or into any widgetized area. Insert into page or post by Page Builder. There is no need to use any short-code or to edit settings. Theme must be non-block Theme. 
Version: 1.0
Author: Tawhidur Rahman Dear
Author URI: https://www.tawhidurrahmandear.com/
Text Domain: tawhidurrahmandearseven
License: GPLv2 or later 
 
 */
 
// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}
// 


class tawhidurrahmandearsevenWidget extends WP_Widget {
  function tawhidurrahmandearsevenWidget() {
    $widget_ops = array('classname' => 'tawhidurrahmandearsevenWidget', 'description' => 'Drag the Plugin in sidebar or footer. Insert into page or post by Page Builder' );
    $this->WP_Widget('tawhidurrahmandearsevenWidget', 'Breadcrumb based on URL', $widget_ops);
  }
 
  function form($instance) {
    $instance = wp_parse_args((array) $instance, array( 'title' => '' ));
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title (optional) :<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance) {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
 ?>

<script>
var path = "";
var href = document.location.href;
var s = href.split("/");
for (var i=2;i<(s.length-1);i++) {
path+="<A HREF=\""+href.substring(0,href.indexOf("/"+s[i])+s[i].length+1)+"/\">"+s[i]+"</A> / ";
}
i=s.length-1;
path+="<A HREF=\""+href.substring(0,href.indexOf(s[i])+s[i].length)+"\">"+s[i]+"</A>";
var url = window.location.protocol + "//" + path;
document.writeln(url);
</script>

<?php
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("tawhidurrahmandearsevenWidget");') );?>