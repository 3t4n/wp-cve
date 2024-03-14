<?php
/*
Plugin Name: FP LinkedIn Profile
Plugin URI: http://flourishpixel.com/
Description: Bring your LinkedIn profiles to your site to help users discover common professional connections. This plugin embed Profile summary card directly on your webpage.
Author: Moshiur Rahman Mehedi
Version: 1.0.1
Author URI: http://www.flourishpixel.com/
*/

//Widget Code 

class LinkedinProfileWidget extends WP_Widget
{
  function LinkedinProfileWidget()
  {
    $widget_ops = array('classname' => 'LinkedinProfileWidget', 'description' => 'This widget embed Profile summary card directly on your webpage.' );
    $this->WP_Widget('LinkedinProfileWidget', 'FP Linkedin Profile', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => 'LinkedIn Profile','profile_name' => 'Moshiur Rahman','profile_link' =>'http://www.linkedin.com/in/digontobd','show_connections'=>'true', 'data_format'=>'inline', 'only_icon'=>'false') );
	$title = $instance['title'];
	$profile_name = $instance['profile_name'];
	$profile_link = $instance['profile_link'];
	$show_connections = $instance['show_connections'];
	$data_format = $instance['data_format'];
	$only_icon = $instance['only_icon'];	
?>
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>">Title:
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('profile_name'); ?>">Title:
    <input class="widefat" id="<?php echo $this->get_field_id('profile_name'); ?>" name="<?php echo $this->get_field_name('profile_name'); ?>" type="text" value="<?php echo attribute_escape($profile_name); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('profile_link'); ?>">Profile Link:
    <input class="widefat" id="<?php echo $this->get_field_id('profile_link'); ?>" name="<?php echo $this->get_field_name('profile_link'); ?>" type="text" value="<?php echo attribute_escape($profile_link); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('show_connections'); ?>">Show Connections:
    <select name="<?php echo $this->get_field_name('show_connections'); ?>" id="<?php echo $this->get_field_id('show_connections'); ?>">
      <option value="true" <?php if(attribute_escape($show_connections) == 'true'){echo 'selected';}?>>True</option>
      <option value="false" <?php if(attribute_escape($show_connections) == 'false'){echo 'selected';}?>>False</option>
    </select>
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('data_format'); ?>">Display Format:
    <select name="<?php echo $this->get_field_name('data_format'); ?>" id="<?php echo $this->get_field_id('data_format'); ?>">
      <option value="click" <?php if(attribute_escape($data_format) == 'click'){echo 'selected';}?>>Click</option>
      <option value="hover" <?php if(attribute_escape($data_format) == 'hover'){echo 'selected';}?>>Hover</option>
      <option value="inline" <?php if(attribute_escape($data_format) == 'inline'){echo 'selected';}?>>Inline</option>
    </select>
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('only_icon'); ?>">Only Icon:
    <select name="<?php echo $this->get_field_name('only_icon'); ?>" id="<?php echo $this->get_field_id('only_icon'); ?>">
      <option value="true" <?php if(attribute_escape($only_icon) == 'true'){echo 'selected';}?>>True</option>
      <option value="false" <?php if(attribute_escape($only_icon) == 'false'){echo 'selected';}?>>False</option>
    </select>
  </label>
</p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['profile_name'] = $new_instance['profile_name'];
	$instance['profile_link'] = $new_instance['profile_link'];
	$instance['show_connections'] = $new_instance['show_connections'];
	$instance['data_format'] = $new_instance['data_format'];
	$instance['only_icon'] = $new_instance['only_icon'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
	$profile_name = empty($instance['profile_name']) ? ' ' : apply_filters('widget_profile_name', $instance['profile_name']);
	$profile_link = empty($instance['profile_link']) ? ' ' : apply_filters('widget_profile_link', $instance['profile_link']);
	$show_connections = empty($instance['show_connections']) ? ' ' : apply_filters('widget_show_connections', $instance['show_connections']);
	$data_format = empty($instance['data_format']) ? ' ' : apply_filters('widget_data_format', $instance['data_format']);
	$only_icon = empty($instance['only_icon']) ? ' ' : apply_filters('widget_only_icon', $instance['only_icon']);
	
	if (!empty($title))
      echo $before_title . $title . $after_title;
?>
<!-- WIDGET CODE GOES HERE -->
<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
<script type="IN/MemberProfile" data-id="<?php echo $profile_link; ?>" data-format="<?php echo $data_format; ?>" <?php if($only_icon=='false') { ?> data-text="<?php echo $profile_name; ?>" <?php } ?> data-related="<?php echo $show_connections; ?>"></script>
<?php
    echo $after_widget;
  }
}
add_action( 'widgets_init', create_function('', 'return register_widget("LinkedinProfileWidget");') );

?>
