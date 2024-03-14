<?php
/**
 * Unregister some the default WordPress widgets on startup and register our
 * slightly modified versions
 *
 * @since 3.1.0
 */
function membee_wpac_widgets_init()
{
	if ( ! is_blog_installed() ) {
		return;
	}

	unregister_widget( 'WP_Nav_Menu_Widget' );

	register_widget( 'Membee_Nav_Menu_Widget' );
}
add_action( 'widgets_init', 'membee_wpac_widgets_init', 1 );

/**
 * Navigation Menu widget class
 *
 * @since 3.1.0
 */
 class Membee_Nav_Menu_Widget extends WP_Nav_Menu_Widget
 {
	public function __construct()
	{
		$widget_ops = array( 'description' => __('Use this widget to add one of your custom menus as a widget.') );
		parent::__construct( 'nav_menu', __('Custom Menu'), $widget_ops );
	}

	function widget( $args, $instance )
	{
		if ( isset( $instance['wpac_visible_by_members'] ) && $instance['wpac_visible_by_members'] && ! is_user_logged_in() ) {
			return;
		}
		
		if ( isset( $instance['wpac_visible_by_nonmembers'] ) && $instance['wpac_visible_by_nonmembers'] && is_user_logged_in() ) {
			return;
		}
		
		// Get menu
		$nav_menu = wp_get_nav_menu_object( $instance['nav_menu'] );

		if ( !$nav_menu )
			return;

		echo $args['before_widget'];

		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu ) );

		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		$instance['wpac_visible_by_members'] = 0;
		$instance['wpac_visible_by_nonmembers'] = 0;
		
		if ( isset( $new_instance['wpac_visible_by_members'] ) ) {
			$instance['wpac_visible_by_members'] = 1;
		}
		
		if ( isset( $new_instance['wpac_visible_by_nonmembers'] ) ) {
			$instance['wpac_visible_by_nonmembers'] = 1;
		}
		
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
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
			<label><?php _e('Member Access Control:') ?></label><br />
			<label><input type="checkbox" <?php checked( $instance['wpac_visible_by_members'], true ); ?> name="<?php echo $this->get_field_name('wpac_visible_by_members'); ?>" id="<?php echo $this->get_field_id('wpac_visible_by_members'); ?>" value="yes" /> Only visible by members? </label><br />
			<label><input type="checkbox" <?php checked( $instance['wpac_visible_by_nonmembers'], true ); ?> name="<?php echo $this->get_field_name('wpac_visible_by_nonmembers'); ?>" id="<?php echo $this->get_field_id('wpac_visible_by_nonmembers'); ?>" value="yes" /> Only visible by non-members? </label>
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