<?php
/*
Plugin Name: Pro Categories Widget
Plugin URI: http://wordpress.org/extend/plugins/pro-categories-widget/
Description: Pro Categories Widget plugin.You have choice to specific categories exclude.
Version: 1.3
Author: Shambhu Prasad Patnaik
Author URI:http://socialcms.wordpress.com/
*/
class Pro_Categories_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_categories', 'description' => __( "A list or dropdown of categories" ) );
		parent::__construct('pro_categories_widget', __('Pro Categories Widget'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Categories' ) : $instance['title'], $instance, $this->id_base);
		$exclude = empty( $instance['exclude'] ) ? '' : $instance['exclude'];
		
		$c = ! empty( $instance['count'] ) ? '1' : '0';
		$h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
		$d = ! empty( $instance['dropdown'] ) ? '1' : '0';
		$hc = ! empty( $instance['hide_category'] ) ? '1' : '0';

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		$cat_args = array('orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h,'exclude' => $exclude,'hide_empty'=>$hc,'id'=>'cat_'.$this->number);

		if ( $d ) {
			$cat_args['show_option_none'] = __('Select Category');
			wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
?>

<script type='text/javascript'>
/* <![CDATA[ */
	var dropdown_<?php echo $this->number;?> = document.getElementById("cat_<?php echo $this->number;?>");
	function onCatChange_<?php echo $this->number;?>() {
		if ( dropdown_<?php echo $this->number;?>.options[dropdown_<?php echo $this->number;?>.selectedIndex].value > 0 ) {
			location.href = "<?php echo home_url(); ?>/?cat="+dropdown_<?php echo $this->number;?>.options[dropdown_<?php echo $this->number;?>.selectedIndex].value;
		}
	}
	dropdown_<?php echo $this->number;?>.onchange = onCatChange_<?php echo $this->number;?>;
/* ]]> */
</script>

<?php
		} else {
?>
		<ul>
<?php
		$cat_args['title_li'] = '';
        global $wp_version;
		if ( $wp_version >= 4.2 ) 
		$cat_args['exclude']=explode(',',$exclude);
		wp_list_categories(apply_filters('widget_categories_args', $cat_args));
?>
		</ul>
<?php
		}

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['exclude'] = strip_tags($new_instance['exclude']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
		$instance['hide_category'] = !empty($new_instance['hide_category']) ? 1 : 0;

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '','exclude' => '') );
		$title = esc_attr( $instance['title'] );
		$exclude = esc_attr( $instance['exclude'] );
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		$hide_category = isset( $instance['hide_category'] ) ? (bool) $instance['hide_category'] : true;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e( 'Exclude :' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" type="text" value="<?php echo $exclude; ?>" />
		
		<br>Enter a comma seperated category ID.<br>ex : <code>2,3</code> &nbsp;&nbsp;(This widget will display all of your categories except these categories).</p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
		<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
		<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label><br />
		
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hide_category'); ?>" name="<?php echo $this->get_field_name('hide_category'); ?>"<?php checked( $hide_category ); ?> />
		<label for="<?php echo $this->get_field_id('hide_category'); ?>"><?php _e( 'Hide Category with no posts.' ); ?></label>

		</p>
<?php
	}

} // class Pro_Categories_Widget

// register Pro_Categories_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "Pro_Categories_Widget" );' ) );
register_deactivation_hook(__FILE__, 'pro_categories_widget_deactivate');

function pro_categories_widget_deactivate ()
{
 unregister_widget('Pro_Categories_Widget');
}
?>