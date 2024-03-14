<?php
/**
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Plugins CK - C�dric KEIFLIN - http://www.wp-pluginsck.com
 */
 
require_once(ACCORDEONMENUCK_PATH . '/helpers/walker-nav-menu.php');
require_once(ACCORDEONMENUCK_PATH . '/helpers/helper.php');
class Accordeonmenuck_Widget extends WP_Widget {

	private $inst, $settings;

	function __construct() {
		$widget_opts = array( 'description' => __('A customizable accordion menu.', 'accordeon-menu-ck') );
		parent::__construct( 'accordeon-menu-ck', 'Accordeon Menu CK', $widget_opts );
	}

	function getInst($name, $default = '') {
		$settings = $this->getSettings();
		$default = isset($settings[$name]) ? $settings[$name] : $default;
		return isset($this->inst[$name]) ? $this->inst[$name] : $default;
	}

	function getSettings() {
		return \Accordeonmenuck\Helper::getSettings();
	}

	function widget($args, $instance) {
		$this->inst = $instance;
		
		$menuID = 'accordeconck' . $this->number;

		// Set the vars to create the menu
		$walker = new Accordeonmenuck_Walker_Nav_Menu;
		// $strict_sub = $instance['only_related'] == 3 ? 1 : 0;
		// $only_related = $instance['only_related'] == 2 || $instance['only_related'] == 3 ? 1 : 0;
		$depth = $this->getInst('depth');
		$style = $this->getInst('style');
		// $container = $this->getInst('container');
		// $container_id = $this->getInst('container_id');
		$menu_class = $this->getInst('menu_class');
		$showactive = $this->getInst('showactive');
		$showactivesubmenu = $this->getInst('showactivesubmenu');
		// $transition = $this->getInst('menu_class');
		// $before = $this->getInst('before');
		// $after = $this->getInst('after');
		// $link_before = $this->getInst('link_before');
		// $link_after = $this->getInst('link_after');
		// $filter = ! empty( $instance['filter'] ) ? $instance['filter'] : 0;
		// $filter_selection = $instance['filter_selection'] ? $instance['filter_selection'] : 0;
		// $include_parent = ! empty( $instance['include_parent'] ) ? 1 : 0;
		// $post_parent = ! empty( $instance['post_parent'] ) ? 1 : 0;
		$description = ! empty( $instance['description'] ) ? 1 : 0;
		$hide_title = ! empty( $instance['hide_title'] ) ? 1 : 0;
		// effect settings
		$transition = $this->getInst('transition');
		$duration = $this->getInst('duration');
		$eventtype = $this->getInst('eventtype');

		wp_enqueue_script('jquery');
		wp_enqueue_script('accordeonmenuck_easing', ACCORDEONMENUCK_MEDIA_URL . '/assets/jquery.easing.1.3.js');
		wp_enqueue_script('accordeonmenuck', ACCORDEONMENUCK_MEDIA_URL . '/assets/accordeonmenuck.js');
		// wp_enqueue_style('accordeonmenuck' . $menuID, plugins_url() . '/accordeon-menu-ck/themes/default/accordeonmenuck_css.php?cssid=' . $menuID);

		// load the styles
		$css = '';
		if ((int) $style > 0) {
			global $wpdb;
			$css = $wpdb->get_var('SELECT layoutcss FROM ' . $wpdb->prefix . 'accordeonmenuck_styles WHERE id=' . (int) $style);
			$css = str_replace('|ID|', '#' . $menuID, $css);
			$css = str_replace('|qq|', '"', $css);
		}

		// Get the menu
		$menu = wp_get_nav_menu_object( $instance['nav_menu'] );

		if ( ! $menu || is_wp_error($menu) )
			return;

		$menu_args = array(
			'echo' => false,
			'items_wrap' => '<ul id="' . $menuID . '" class="%2$s">%3$s</ul>',
			'fallback_cb' => '',
			'menu' => $menu,
			'walker' => $walker, // this is the main part to render the menu html
			'depth' => $depth,
			// 'only_related' => $only_related,
			// 'strict_sub' => $strict_sub,
			// 'filter_selection' => $filter_selection,
			'container' => false,
			// 'container_id' => $container_id,
			'menu_class' => $menu_class,
			'showactive' => $showactive,
			'showactivesubmenu' => $showactivesubmenu,
			// 'before' => $before, // before the <a> tag
			// 'after' => $after,
			// 'link_before' => $link_before, // inside the <a> tag, before the text
			// 'link_after' => $link_after,
			// 'filter' => $filter,
			// 'include_parent' => $include_parent,
			// 'post_parent' => $post_parent,
			'description' => $description
		);

		$nav_menu = wp_nav_menu($menu_args );

		if ( !$nav_menu && $hide_title )
			return;

		// inject the css style
		if ($css) {
		?>
		<style type="text/css">
			<?php echo $css; ?>
		</style>
		<?php
		}

		echo $args['before_widget'];

		$instance['title'] = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		if ( $nav_menu ) {
			static $menu_id_slugs = array();

			// Attributes
			if ( ! empty( $menu_id ) ) {
				$wrap_id = $menu_id;
			} else {
				$wrap_id = 'menu-' . $menu->slug;
				while ( in_array( $wrap_id, $menu_id_slugs ) ) {
					if ( preg_match( '#-(\d+)$#', $wrap_id, $matches ) )
						$wrap_id = preg_replace('#-(\d+)$#', '-' . ++$matches[1], $wrap_id );
					else
						$wrap_id = $wrap_id . '-1';
				}
			}
			$menu_id_slugs[] = $wrap_id;

			$wrap_class = $menu_class ? $menu_class : '';

			// outputs the menu html
			echo $nav_menu;

			$js = "
			jQuery(document).ready(function(){
				jQuery('#" . $menuID . "').accordeonmenuck({"
				. "eventtype : '" . $eventtype . "',"
				. "transition : '" . $transition . "',"
				. "showactive : '" . $showactive . "',"
				. "showactivesubmenu : '" . $showactivesubmenu . "',"
				// . "defaultopenedid : '" . $params->get('defaultopenedid') . "',"
				// . "activeeffect : '" . (bool) $params->get('activeeffect') . "',"
				. "duree : " . (int)$duration
				. "});
			}); "
		?>
		<script type="text/javascript"> <!--
		<?php echo $js; ?>
		//--> </script>
		<?php
		}

		echo $args['after_widget'];
	}

	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		$instance['depth'] = (int) $new_instance['depth'];
		$instance['style'] = (int) $new_instance['style'];
		// $instance['only_related'] = ! $new_instance['filter_selection'] ? (int) $new_instance['only_related'] : 0;
		// $instance['filter_selection'] = (int) $new_instance['filter_selection'];
		// $instance['container'] = $new_instance['container'];
		// $instance['container_id'] = $new_instance['container_id'];
		$instance['menu_class'] = $new_instance['menu_class'];
		$instance['showactive'] = $new_instance['showactive'];
		$instance['showactivesubmenu'] = $new_instance['showactivesubmenu'];
		// $instance['before'] = $new_instance['before'];
		// $instance['after'] = $new_instance['after'];
		// $instance['link_before'] = $new_instance['link_before'];
		// $instance['link_after'] = $new_instance['link_after'];
		// $instance['filter'] = ! empty( $new_instance['filter'] ) ? (int) $new_instance['filter'] : 0;
		// $instance['include_parent'] = ! empty( $new_instance['include_parent'] ) ? 1 : 0;
		// $instance['post_parent'] = ! empty( $new_instance['post_parent'] ) ? 1 : 0;
		$instance['description'] = ! empty( $new_instance['description'] ) ? 1 : 0;
		$instance['hide_title'] = ! empty( $new_instance['hide_title'] ) ? 1 : 0;
		$instance['transition'] = $new_instance['transition'];
		$instance['duration'] = (int) $new_instance['duration'];
		$instance['eventtype'] = $new_instance['eventtype'];

		// if ( $instance['filter'] == 1 ) {
			// $instance['only_related'] = 3;
		// }

		return $instance;
	}

	function form( $instance ) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('accordeonmenuck_edit_widget', plugins_url() . '/accordeon-menu-ck/assets/edit.widget.js');
		wp_enqueue_script('accordeonmenuck_ckbox', plugins_url() . '/accordeon-menu-ck/assets/ckbox.js');
		wp_enqueue_style('accordeonmenuck_ckbox', plugins_url() . '/accordeon-menu-ck/assets/ckbox.css');
		wp_localize_script('accordeonmenuck_edit_widget', 'accordeonmenuck_urls', array( 'siteurl' => site_url(), 'adminurl' => admin_url() ));

		// initialize the instance
		$this->inst = $instance;

		$title = $this->getInst('title');
		$nav_menu = $this->getInst('nav_menu');
		// $only_related = $this->getInst('only_related');
		$depth = $this->getInst('depth');
		// $container = $this->getInst('container');
		// $container_id = $this->getInst('container_id');
		$menu_class = $this->getInst('menu_class');
		$showactive = $this->getInst('showactive');
		$showactivesubmenu = $this->getInst('showactivesubmenu');
		// $before = $this->getInst('before');
		// $after = $this->getInst('after');
		// $link_before = $this->getInst('link_before');
		// $link_after = $this->getInst('link_after');
		// $filter_selection = $this->getInst('filter_selection');
		// $filter = isset($instance['filter']) ? absint($instance['filter']) : 0;
		$style = $this->getInst('style');
		$transition = $this->getInst('transition');
		$duration = $this->getInst('duration');
		$eventtype = $this->getInst('eventtype');

		// Get menus
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

		// If no menus exists, direct the user to go and create some.
		if ( !$menus ) {
			echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.', 'accordeon-menu-ck'), admin_url('nav-menus.php') ) .'</p>';
			return;
		}

		// Get styles
		global $wpdb;
		// -- Preparing the query -- 
		$query = "SELECT id,name FROM " . $wpdb->prefix . "accordeonmenuck_styles ORDER BY name ASC";
		$styles =  $wpdb->get_results($query, OBJECT);
		?>
		<style>
		fieldset.ck {
			border: 1px dashed #aaa;
			padding: 5px;
			margin: 5px 0;
		}
		fieldset.ck > legend {
			color: #aaa;
			padding: 5px;
		}
		fieldset.ck > p:first-child {
			margin-top: 0;
		}
		fieldset.ck label {
			display: inline-block;
			margin: 0;
			min-width: 100px;
			padding: 0;
		}
		</style>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<p><input id="<?php echo $this->get_field_id('hide_title'); ?>" name="<?php echo $this->get_field_name('hide_title'); ?>" type="checkbox" <?php checked(isset($instance['hide_title']) ? $instance['hide_title'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('hide_title'); ?>"><?php _e('Hide title if menu is empty', 'accordeon-menu-ck'); ?></label>
		</p>

		<fieldset class="ck">
			<legend><?php _e( 'Menu', 'accordeon-menu-ck' ); ?></legend>
		<p>
			<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu', 'accordeon-menu-ck'); ?> : </label>
			<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
				<?php
				foreach ( $menus as $menu ) {
					$selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
					echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
				}
				?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('depth'); ?>"><?php _e('How many levels to display', 'accordeon-menu-ck'); ?></label>
			<select name="<?php echo $this->get_field_name('depth'); ?>" id="<?php echo $this->get_field_id('depth'); ?>" class="widefat">
				<option value="0"<?php selected( $depth, 0 ); ?>><?php _e('Unlimited depth', 'accordeon-menu-ck'); ?></option>
				<option value="1"<?php selected( $depth, 1 ); ?>><?php _e( '1 level deep', 'accordeon-menu-ck' ); ?></option>
				<option value="2"<?php selected( $depth, 2 ); ?>><?php _e( '2 levels deep', 'accordeon-menu-ck' ); ?></option>
				<option value="3"<?php selected( $depth, 3 ); ?>><?php _e( '3 levels deep', 'accordeon-menu-ck' ); ?></option>
				<option value="4"<?php selected( $depth, 4 ); ?>><?php _e( '4 levels deep', 'accordeon-menu-ck' ); ?></option>
				<option value="5"<?php selected( $depth, 5 ); ?>><?php _e( '5 levels deep', 'accordeon-menu-ck' ); ?></option>
			</select>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="checkbox" <?php checked(isset($instance['description']) ? $instance['description'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Include descriptions', 'accordeon-menu-ck'); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('menu_class'); ?>"><?php _e('Menu Class', 'accordeon-menu-ck') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('menu_class'); ?>" name="<?php echo $this->get_field_name('menu_class'); ?>" value="<?php echo $menu_class; ?>" />
			<small><?php _e( 'CSS class to use for the ul element which forms the menu.', 'accordeon-menu-ck' ); ?></small>
		</p>
		</fieldset>

		<fieldset class="ck">
			<legend><?php _e( 'Styles', 'accordeon-menu-ck' ); ?></legend>
			<p>
				<label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Select Style', 'accordeon-menu-ck'); ?> : </label>
				<select id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>">
					<?php
					echo '<option' . selected( $style, 0 ) . ' value="-1">'. __('No style', 'accordeon-menu-ck') .'</option>';
					// echo '<option' . selected( $style, -1 ) . ' value="0">'. __('New style', 'accordeon-menu-ck') .'</option>';
					foreach ( $styles as $styl ) {
						$selected = $style == $styl->id ? ' selected="selected"' : '';
						echo '<option'. $selected .' value="'. $styl->id .'">'. $styl->name .'</option>';
					}
					?>
				</select>
				<span class="button" onclick="ckEditStyleWidget(this)"><img src="<?php echo CEIKAY_MEDIA_URL ?>/images/pencil.png" /><?php _e('Edit the style', 'accordeon-menu-ck') ?></span>
				<br/><a href="admin.php?page=accordeonmenuck_general"><small><?php _e( 'You can create a new style in the Accordeon Menu CK plugin', 'accordeon-menu-ck' ); ?></small></a>
			</p>
		</fieldset>
		<fieldset class="ck">
			<legend><?php _e( 'Effect', 'accordeon-menu-ck' ); ?></legend>
			<p>
				<label for="<?php echo $this->get_field_id('transition'); ?>"><?php _e('Transition', 'accordeon-menu-ck'); ?></label>
				<select name="<?php echo $this->get_field_name('transition'); ?>" id="<?php echo $this->get_field_id('transition'); ?>" class="">
					<option value="linear"<?php selected( $transition, 'linear' ); ?>>Linear</option>
					<option value="swing"<?php selected( $transition, 'swing' ); ?>>swing</option>
					<option value="easeInQuad"<?php selected( $transition, 'easeInQuad' ); ?>>easeInQuad</option>
					<option value="easeOutQuad"<?php selected( $transition, 'easeOutQuad' ); ?>>easeOutQuad</option>
					<option value="easeInOutQuad"<?php selected( $transition, 'easeInOutQuad' ); ?>>easeInOutQuad</option>
					<option value="easeInCubic"<?php selected( $transition, 'easeInCubic' ); ?>>easeInCubic</option>
					<option value="easeOutCubic"<?php selected( $transition, 'easeOutCubic' ); ?>>easeOutCubic</option>
					<option value="easeInOutCubic"<?php selected( $transition, 'easeInOutCubic' ); ?>>easeInOutCubic</option>
					<option value="easeInQuart"<?php selected( $transition, 'easeInQuart' ); ?>>easeInQuart</option>
					<option value="easeOutQuart"<?php selected( $transition, 'easeOutQuart' ); ?>>easeOutQuart</option>
					<option value="easeInOutQuart"<?php selected( $transition, 'easeInOutQuart' ); ?>>easeInOutQuart</option>
					<option value="easeInSine"<?php selected( $transition, 'easeInSine' ); ?>>easeInSine</option>
					<option value="easeOutSine"<?php selected( $transition, 'easeOutSine' ); ?>>easeOutSine</option>
					<option value="easeInOutSine"<?php selected( $transition, 'easeInOutSine' ); ?>>easeInOutSine</option>
					<option value="easeInExpo"<?php selected( $transition, 'easeInExpo' ); ?>>easeInExpo</option>
					<option value="easeOutExpo"<?php selected( $transition, 'easeOutExpo' ); ?>>easeOutExpo</option>
					<option value="easeInOutExpo"<?php selected( $transition, 'easeInOutExpo' ); ?>>easeInOutExpo</option>
					<option value="easeInQuint"<?php selected( $transition, 'easeInQuint' ); ?>>easeInQuint</option>
					<option value="easeOutQuint"<?php selected( $transition, 'easeOutQuint' ); ?>>easeOutQuint</option>
					<option value="easeInOutQuint"<?php selected( $transition, 'easeInOutQuint' ); ?>>easeInOutQuint</option>
					<option value="easeInCirc"<?php selected( $transition, 'easeInCirc' ); ?>>easeInCirc</option>
					<option value="easeOutCirc"<?php selected( $transition, 'easeOutCirc' ); ?>>easeOutCirc</option>
					<option value="easeInOutCirc"<?php selected( $transition, 'easeInOutCirc' ); ?>>easeInOutCirc</option>
					<option value="easeInElastic"<?php selected( $transition, 'easeInElastic' ); ?>>easeInElastic</option>
					<option value="easeOutElastic"<?php selected( $transition, 'easeOutElastic' ); ?>>easeOutElastic</option>
					<option value="easeInOutElastic"<?php selected( $transition, 'easeInOutElastic' ); ?>>easeInOutElastic</option>
					<option value="easeInBack"<?php selected( $transition, 'easeInBack' ); ?>>easeInBack</option>
					<option value="easeOutBack"<?php selected( $transition, 'easeOutBack' ); ?>>easeOutBack</option>
					<option value="easeInOutBack"<?php selected( $transition, 'easeInOutBack' ); ?>>easeInOutBack</option>
					<option value="easeInBounce"<?php selected( $transition, 'easeInBounce' ); ?>>easeInBounce</option>
					<option value="easeOutBounce"<?php selected( $transition, 'easeOutBounce' ); ?>>easeOutBounce</option>
					<option value="easeInOutBounce"<?php selected( $transition, 'easeInOutBounce' ); ?>>easeInOutBounce</option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('duration'); ?>"><?php _e('Duration', 'accordeon-menu-ck') ?></label>
				<input type="text" class="" id="<?php echo $this->get_field_id('duration'); ?>" name="<?php echo $this->get_field_name('duration'); ?>" value="<?php echo $duration; ?>" /> ms
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('eventtype'); ?>"><?php _e('Open on', 'accordeon-menu-ck'); ?></label>
				<select name="<?php echo $this->get_field_name('eventtype'); ?>" id="<?php echo $this->get_field_id('eventtype'); ?>" class="">
					<option value="click"<?php selected( $eventtype, 'click' ); ?>><?php _e('Click', 'accordeon-menu-ck'); ?></option>
					<option value="mouseover"<?php selected( $eventtype, 'mouseover' ); ?>><?php _e('Mouseover', 'accordeon-menu-ck'); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('showactive'); ?>"><?php _e('Show active item', 'accordeon-menu-ck'); ?></label>
				<select name="<?php echo $this->get_field_name('showactive'); ?>" id="<?php echo $this->get_field_id('showactive'); ?>" class="">
					<option value="1"<?php selected( $showactive, '1' ); ?>><?php _e('Yes', 'accordeon-menu-ck'); ?></option>
					<option value="0"<?php selected( $showactive, '0' ); ?>><?php _e('No', 'accordeon-menu-ck'); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('showactivesubmenu'); ?>"><?php _e('Show active submenu', 'accordeon-menu-ck'); ?></label>
				<select name="<?php echo $this->get_field_name('showactivesubmenu'); ?>" id="<?php echo $this->get_field_id('showactivesubmenu'); ?>" class="">
					<option value="1"<?php selected( $showactivesubmenu, '1' ); ?>><?php _e('Yes', 'accordeon-menu-ck'); ?></option>
					<option value="0"<?php selected( $showactivesubmenu, '0' ); ?>><?php _e('No', 'accordeon-menu-ck'); ?></option>
				</select>
			</p>
		</fieldset>
		<?php
	}
}