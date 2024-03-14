<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

//Dashboard
//=================================================================================================

//Remove Welcome Panel
if (array_key_exists( 'remove_welcome_panel', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_dashboard_welcome_panel() {
		$wpui_dashboard_welcome_panel_option = get_option("wpui_dashboard_option_name");
		if ( ! empty ( $wpui_dashboard_welcome_panel_option ) ) {
			foreach ($wpui_dashboard_welcome_panel_option as $key => $wpui_dashboard_welcome_panel_value)
				$options[$key] = $wpui_dashboard_welcome_panel_value;
			if (isset($wpui_dashboard_welcome_panel_option['wpui_dashboard_welcome_panel'])) {
				return $wpui_dashboard_welcome_panel_option['wpui_dashboard_welcome_panel'];
			}
		}
	};

	if (wpui_dashboard_welcome_panel() =='1') {
		remove_action( 'welcome_panel', 'wp_welcome_panel' );
	}
}

//Display Dashboard widgets single column
if (array_key_exists( 'display_single_column', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_dashboard_widgets_single_column() {
		$wpui_dashboard_widgets_single_column_option = get_option("wpui_dashboard_option_name");
		if ( ! empty ( $wpui_dashboard_widgets_single_column_option ) ) {
			foreach ($wpui_dashboard_widgets_single_column_option as $key => $wpui_dashboard_widgets_single_column_value)
				$options[$key] = $wpui_dashboard_widgets_single_column_value;
			if (isset($wpui_dashboard_widgets_single_column_option['wpui_dashboard_single_column'])) {
				return $wpui_dashboard_widgets_single_column_option['wpui_dashboard_single_column'];
			}
		}
	};

	if (wpui_dashboard_widgets_single_column() =='1') {
		function wpui_dashboard_single_column($columns) {
		    $columns['dashboard'] = 1;
		    return $columns;
		}
		add_filter( 'screen_layout_columns', 'wpui_dashboard_single_column' );
		
		function wpui_dashboard_single_column_one() {
			return 1;
		}
		add_filter( 'get_user_option_screen_layout_dashboard', 'wpui_dashboard_single_column_one' );
	}
}

//Listing dashboard widgets
if (array_key_exists( 'listing_widgets', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_dashboard_get_all_widgets() {
		$wpui_dashboard_get_all_widgets_option = get_option("wpui_dashboard_option_name");
		if ( ! empty ( $wpui_dashboard_get_all_widgets_option ) ) {
			foreach ($wpui_dashboard_get_all_widgets_option as $key => $wpui_dashboard_get_all_widgets_value)
				$options[$key] = $wpui_dashboard_get_all_widgets_value;
			 if (isset($wpui_dashboard_get_all_widgets_option['wpui_dashboard_metaboxe_all'])) { 
			 	return $wpui_dashboard_get_all_widgets_option['wpui_dashboard_metaboxe_all'];
			 }
		}
	};
}

//Remove Dashboard widgets
if (array_key_exists( 'listing_widgets', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_dashboard_remove_widgets() {
		global $wp_meta_boxes;

		if (wpui_dashboard_get_all_widgets() != '') {
			$wpui_dashboard_contexts = array('normal','advanced','side');
        	$wpui_dashboard_priorities = array('high','core','default','low');
        	$wpui_dashboard_get_all_widgets = wpui_dashboard_get_all_widgets();

        	foreach ($wpui_dashboard_get_all_widgets as $wpui_dashboard_get_all_widgets_value) {
        		foreach ($wpui_dashboard_contexts as $wpui_dashboard_contexts_value) {
        			foreach ($wpui_dashboard_priorities as $wpui_dashboard_priorities_value) {
						unset($wp_meta_boxes['dashboard'][$wpui_dashboard_contexts_value][$wpui_dashboard_priorities_value][$wpui_dashboard_get_all_widgets_value]);
					}		
        		}
        	}
		}
	}
	add_action('wp_dashboard_setup', 'wpui_dashboard_remove_widgets', 9999 );
}

//Remove Drad'n'drop mouvement
if (array_key_exists( 'remove_widgets_drag_and_drop', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_dashboard_widgets_drag_and_drop() {
		$wpui_dashboard_widgets_drag_and_drop_option = get_option("wpui_dashboard_option_name");
		if ( ! empty ( $wpui_dashboard_widgets_drag_and_drop_option ) ) {
			foreach ($wpui_dashboard_widgets_drag_and_drop_option as $key => $wpui_dashboard_widgets_drag_and_drop_value)
				$options[$key] = $wpui_dashboard_widgets_drag_and_drop_value;
			if (isset($wpui_dashboard_widgets_drag_and_drop_option['wpui_dashboard_widgets_drag_and_drop'])) {
				return $wpui_dashboard_widgets_drag_and_drop_option['wpui_dashboard_widgets_drag_and_drop'];
			}
		}
	};

	if (wpui_dashboard_widgets_drag_and_drop() =='1') {
		function wpui_dashboard_disable_drag_and_drop_widgets() {
		    wp_deregister_script('postbox');
		}
		add_action( 'wp_dashboard_setup', 'wpui_dashboard_disable_drag_and_drop_widgets', 999 );
	}
}

//Display Custom Post Types in At a glance widget
if (array_key_exists( 'at_a_glance_cpt', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_dashboard_at_a_glance_cpt() {
		$wpui_dashboard_at_a_glance_cpt_option = get_option("wpui_dashboard_option_name");
		if ( ! empty ( $wpui_dashboard_at_a_glance_cpt_option ) ) {
			foreach ($wpui_dashboard_at_a_glance_cpt_option as $key => $wpui_dashboard_at_a_glance_cpt_value)
				$options[$key] = $wpui_dashboard_at_a_glance_cpt_value;
			if (isset($wpui_dashboard_at_a_glance_cpt_option['wpui_dashboard_at_a_glance_cpt'])) {
				return $wpui_dashboard_at_a_glance_cpt_option['wpui_dashboard_at_a_glance_cpt'];
			}
		}
	};

	if (wpui_dashboard_at_a_glance_cpt() =='1') {
		function wpui_dashboard_at_a_glance_cpt_widget() {
		    $args = array(
		        '_builtin' => false
		    );
		    $output = 'object';
		    $operator = 'and';

		    $post_types = get_post_types( $args, $output, $operator );
		    
		    foreach ( $post_types as $post_type ) {
		        $num_posts = wp_count_posts( $post_type->name );
		        $num = number_format_i18n( $num_posts->publish );
		        $text = _n( $post_type->labels->singular_name, $post_type->labels->menu_name, intval( $num_posts->publish ) );
		        if ( current_user_can( 'edit_posts' ) ) {
		            $output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . ' ' . $text . '</a>';
		            echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
		        }
		    }
		}
		add_action( 'dashboard_glance_items', 'wpui_dashboard_at_a_glance_cpt_widget', 999 );
	}
}

//Display Users in At a glance widget
if (array_key_exists( 'at_a_glance_users', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_dashboard_users_at_a_glance() {
		$wpui_dashboard_users_at_a_glance_option = get_option("wpui_dashboard_option_name");
		if ( ! empty ( $wpui_dashboard_users_at_a_glance_option ) ) {
			foreach ($wpui_dashboard_users_at_a_glance_option as $key => $wpui_dashboard_users_at_a_glance_value)
				$options[$key] = $wpui_dashboard_users_at_a_glance_value;
			if (isset($wpui_dashboard_users_at_a_glance_option['wpui_dashboard_users_at_a_glance'])) {
				return $wpui_dashboard_users_at_a_glance_option['wpui_dashboard_users_at_a_glance'];
			}
		}
	};

	if (wpui_dashboard_users_at_a_glance() =='1') {
		function wpui_get_number_users() {
		    global $wpdb;
		    $wpui_get_users = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
			?>
			<li class="users-count">
				<a href="<?php echo admin_url( 'users.php'); ?>"><?php echo $wpui_get_users; ?> <?php _e('Users','wp-admin-ui'); ?></a>
			</li>
			<?php 
		}
		add_action( 'dashboard_glance_items', 'wpui_get_number_users', 999);
	}
}

//Display Custom widget
if (array_key_exists( 'custom_widget', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_dashboard_custom_widget() {
		$wpui_dashboard_custom_widget_option = get_option("wpui_dashboard_option_name");
		if ( ! empty ( $wpui_dashboard_custom_widget_option ) ) {
			foreach ($wpui_dashboard_custom_widget_option as $key => $wpui_dashboard_custom_widget_value)
				$options[$key] = $wpui_dashboard_custom_widget_value;
			if (isset($wpui_dashboard_custom_widget_option['wpui_dashboard_custom_widget'])) {
				return $wpui_dashboard_custom_widget_option['wpui_dashboard_custom_widget'];
			}
		}
	};

	function wpui_dashboard_custom_widget_title() {
		$wpui_dashboard_custom_widget_title_option = get_option("wpui_dashboard_option_name");
		if ( ! empty ( $wpui_dashboard_custom_widget_title_option ) ) {
			foreach ($wpui_dashboard_custom_widget_title_option as $key => $wpui_dashboard_custom_widget_title_value)
				$options[$key] = $wpui_dashboard_custom_widget_title_value;
			if (isset($wpui_dashboard_custom_widget_title_option['wpui_dashboard_custom_widget_title'])) {
				return $wpui_dashboard_custom_widget_title_option['wpui_dashboard_custom_widget_title'];
			}
		}
	};

	if (wpui_dashboard_custom_widget()) {
		function wpui_dashboard_widget_function() {
			echo wpui_dashboard_custom_widget();
		}
		function wpui_add_dashboard_widgets() {
			if (wpui_dashboard_custom_widget_title()) {
				$wpui_dashboard_custom_widget_title = wpui_dashboard_custom_widget_title();
			} else {
				$wpui_dashboard_custom_widget_title = __('My custom widget','wp-admin-ui');
			}
			wp_add_dashboard_widget('wpui_dashboard_widget', $wpui_dashboard_custom_widget_title, 'wpui_dashboard_widget_function');
		}
		add_action('wp_dashboard_setup', 'wpui_add_dashboard_widgets');
	}
}
