<? ob_start();
/*
Plugin Name: Sidebar Menu Widget
Plugin URI: http://webloungeinc.com
Description: With this plugin you can display menu in sidebar according to nev menu.
Author: Weblounge inc.
Version: 1.0
Author URI: http://webloungeinc.com
*/
define( 'PLUGINS_PATH', plugin_dir_url(__FILE__) );
wp_register_style( 'style', PLUGINS_PATH . 'style.css' );
wp_enqueue_style ( 'style' );

function _getSidebarMenu($menu_id='',$post)
{
	$menuItems = wp_get_nav_menu_items($menu_id);
	
	foreach($menuItems as $menuItem)
	{
		
		if($menuItem->object_id == $post->ID && $menuItem->object == $post->post_type)
		{
			$parentMenuId = $menuItem->menu_item_parent;
			$currentMenuId = $menuItem->ID;
		}
	}
	$menuItems = (array) $menuItems;
	
	$_cnt = 0;
	foreach($menuItems as $_counter)
	{
		
		if($currentMenuId==$_counter->menu_item_parent)
		{
			$_cnt++;
		}
	}
	
	foreach($menuItems as $kmi=>$vmi)
	{
		
		$vmi = (array) $vmi;
		if($currentMenuId <> "")
		{
			if(in_array($currentMenuId,$vmi))
			{
				if($currentMenuId == $vmi[ID])
				{
					if($_cnt <> 0)
					{
						echo $content = '<li class="m_title">' . $vmi[title] . '</li>';
					}
					else
					{
						foreach($menuItems as $sis_menu)
						{
							// Parent
							$sis_menu = (array) $sis_menu;
							
							if($sis_menu[ID] == $parentMenuId)
							{
									echo $content = '<li class="m_title">' . $sis_menu[title] . '</li>';
							}
							
							if($sis_menu[menu_item_parent] == $parentMenuId)
							{
									$_other_cls = implode(" ",$sis_menu[classes]);
									
									if($sis_menu[ID] == $currentMenuId)
									{
										$_cur_class = 'current_m_item';
									}
									else
									{
										$_cur_class = "";
									}
									echo $content = '<li><a href="' . $sis_menu[url]. '" class="'.$_other_cls." ".$_cur_class.'" rel="'.$sis_menu[xfn].'" target="'.$sis_menu[target].'">&raquo;&nbsp;' . $sis_menu[title] . '</a></li>';
							}
						}
					}
				}
				else
				{
					$_other_cls = implode(" ",$vmi[classes]);
					echo $content = '<li><a href="' . $vmi[url]. '" class="'.$_other_cls." ".$_cur_class.'" rel="'.$vmi[xfn].'" target="'.$vmi[target].'">&raquo;&nbsp;' . $vmi[title] . '</a></li>';
				}	
			}
		}
		else
		{
			if($vmi[menu_item_parent]==0)
			{
				$_other_cls = implode(" ",$vmi[classes]);
				echo $content = '<li><a href="' . $vmi[url]. '" class="'.$_other_cls." ".$_cur_class.'" rel="'.$vmi[xfn].'" target="'.$vmi[target].'">&raquo;&nbsp;' . $vmi[title] . '</a></li>';
			}
		}
				
	}
	
}
   

/* Custom Product Specials Widget */
class sidebar_menu_widget extends WP_Widget {
	function sidebar_menu_widget() {
		// widget actual processes
		parent::WP_Widget(false, $name = 'Sidebar Menu', array(
			'description' => 'Displays a Sidebar Menu'
		));
	}
	function widget($args, $instance) {
		global $post;
		extract($args);
		
		echo $before_widget;
		
			$instance['title'] = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
			if ( !empty($instance['title']) )
			echo $before_title . $instance['title'] . $after_title;
			echo '<ul class="menu ' . $instance['class'] . '">';
				_getSidebarMenu($instance['nav_menu'],$post);
			echo '</ul>';
				
		echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
	function form($instance) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$class = isset( $instance['class'] ) ? $instance['class'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
		// Get menus
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		// If no menus exists, direct the user to go and create some.
		if ( !$menus ) {
			echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
			return;
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('class'); ?>"><?php _e('Class:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('class'); ?>" name="<?php echo $this->get_field_name('class'); ?>" value="<?php echo $class; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:'); ?></label>
			<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
		<?php
			foreach ( $menus as $menu ) {
				$selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
				echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
			}
		?>
			</select>
		</p>
		
		<?php
	}
}
add_action('widgets_init', 'register_sidebar_menu_widget');
function register_sidebar_menu_widget() {
	register_widget('sidebar_menu_widget');
}