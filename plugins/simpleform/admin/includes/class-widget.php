<?php

 /**
 * Add SimpleForm widget
 *
 * @since    1.10
 * @version  2.1.2
 */

class SimpleForm_Widget extends WP_Widget {

	/**
	 * Widget constructor
	 *
	 * @since  1.10
	 */

	// Add compatibility with the legacy widget block for versions of WordPress prior to 5.8
	public $show_instance_in_rest = true;

	public function __construct() {

	    if ( version_compare( $GLOBALS['wp_version'], '5.8', '<' ) ) {
		   $widget_options = array ('classname' => __FUNCTION__, 'description' => __( 'Display a contact form.', 'simpleform' ) );
	    }

	    else {
		   $widget_options = array ('classname' => __FUNCTION__, 'description' => __( 'Display a contact form.', 'simpleform' ), 'show_instance_in_rest' => true );
	    }

		parent::__construct('sform_widget', __( 'SimpleForm', 'simpleform'), $widget_options );

 	    // Add/Remove form usage data when updating widgets
    	add_filter ('pre_update_option_sidebars_widgets',array($this, 'update_forms'), 10, 1);
   	    // Delete the form after a widget has been marked for deletion
   	    add_action('delete_widget', array($this, 'cleanup_sform_shortcodes'), 10, 3 );
    	// Get the form ID used in the widget
	    add_filter('get_sform_shortcode_id', array($this, 'get_form_id') );
	    // Hide the widget from the legacy widget block
	    add_filter('widget_types_to_hide_from_legacy_widget_block', array( $this, 'hide_sform_widget' ));
 	    // Edit form usage data when updating block widgets
   	    add_action('update_option_widget_block', array($this, 'form_widgets') );
   	    // Clean up the widget areas of non-existent and redundant form
    	add_action('rest_api_init', array($this, 'widgets_cleaning'), 0 );

	}

	/**
	 * Output the widget admin form
	 *
	 * @since  1.10
	 */

	public function form( $instance ) {

		$title   = ! empty( $instance['sform_widget_title'] ) ? $instance['sform_widget_title'] : '';
        $audience = ! empty( $instance['sform_widget_audience'] ) ? $instance['sform_widget_audience'] : 'all';
        $role = ! empty( $instance['sform_widget_role'] ) ? $instance['sform_widget_role'] : 'any';
        $shortcode_id = ! empty( $instance['shortcode_id'] ) ? $instance['shortcode_id'] : apply_filters( 'get_sform_shortcode_id', $this->number );
		$widget_id = ! empty( $instance['sform_widget_id'] ) ? $instance['sform_widget_id'] : '';
		$class = ! empty( $instance['sform_widget_class'] ) ? $instance['sform_widget_class'] : '';
        $visibility = ! empty( $instance['sform_widget_visibility'] ) ? $instance['sform_widget_visibility'] : 'all';
        $hidden_pages = ! empty( $instance['sform_widget_hidden_pages'] ) ? $instance['sform_widget_hidden_pages'] : '';
        $visible_pages = ! empty( $instance['sform_widget_visible_pages'] ) ? $instance['sform_widget_visible_pages'] : '';
        $hidden_pages_array = ! empty($hidden_pages) ? explode(',',$hidden_pages) : array();
        $visible_pages_array = ! empty($visible_pages) ? explode(',',$visible_pages) : array();
        $settings = get_option('sform_settings');
        $color = ! empty( $settings['admin_color'] ) ? esc_attr($settings['admin_color']) : 'default';
   	    ?>

		<p><label for="<?php echo $this->get_field_id( 'sform_widget_title' ); ?>"><?php _e( 'Title:', 'simpleform' ); ?></label><input type="text" name="<?php echo $this->get_field_name( 'sform_widget_title' ); ?>" id="<?php echo $this->get_field_id('sform_widget_title') ?>" class="widefat" value="<?php echo esc_attr( $title ); ?>"></p>

        <p><label for="<?php echo $this->get_field_id( 'sform_widget_audience' ); ?>"><?php _e( 'Show for:', 'simpleform' ) ?></label><select name="<?php echo $this->get_field_name( 'sform_widget_audience' ); ?>" id="<?php echo $this->get_field_id('sform_widget_audience') ?>" class="widefat sform-target" field="<?php echo $this->number;?>" ><option value="all" <?php selected( $audience, 'all') ?>><?php _e( 'Everyone', 'simpleform' ) ?></option><option value="out" <?php echo selected( $audience, 'out' ) ?>><?php _e( 'Logged-out users', 'simpleform' ) ?></option><option value="in" <?php echo selected( $audience, 'in' ) ?>><?php _e( 'Logged-in users', 'simpleform' ) ?></option></select></p>

        <p id="usertype" class="role-<?php echo $this->number; if ( $audience !='in' ) {echo ' unseen'; } ?>"><label for="<?php echo $this->get_field_id( 'sform_widget_role' ); ?>"><?php _e( 'Role', 'simpleform' ) ?>:</label><select name="<?php echo $this->get_field_name( 'sform_widget_role' ); ?>" id="<?php echo $this->get_field_id( 'sform_widget_role' ); ?>" class="widefat"><option value="any" <?php selected( $role, 'any') ?>><?php _e('Any','simpleform') ?></option><?php $roles = ''; $wp_roles = wp_roles()->roles; foreach ( $wp_roles as $wp_role => $details ) { $name = translate_user_role( $details['name'] ); if ( $role === $wp_role ) { $roles .= "\n\t<option selected='selected' value='" . esc_attr( $wp_role ) . "'>$name</option>"; } else { $roles .= "\n\t<option value='" . esc_attr( $wp_role ) . "'>$name</option>"; } } echo $roles; ?></select></p>

        <p id="visibility" class="<?php if ( $visibility =='all' ) {echo 'visibility'; } ?>"><label for="<?php echo $this->get_field_id( 'sform_widget_visibility' ) ?>"><?php _e( 'Show/Hide on:', 'simpleform' ) ?></label><select name="<?php echo $this->get_field_name( 'sform_widget_visibility' ) ?>" id="<?php echo $this->get_field_id( 'sform_widget_visibility' ) ?>" class="widefat sfwidget" box="visibility-<?php echo $this->number; ?>"><option value="all" <?php selected( $visibility, 'all') ?>><?php _e( 'Show anywhere', 'simpleform' ) ?></option><option value="hidden" <?php selected( $visibility, 'hidden') ?>><?php _e( 'Hide on selected', 'simpleform' ) ?></option><option value="visible" <?php selected( $visibility, 'visible') ?>><?php _e( 'Show on selected', 'simpleform' ) ?></option></select></p>

		<div id="sform-widget-hidden-pages" class="widget-pages visibility-<?php echo $this->number; if ( $visibility !='hidden' ) {echo ' unseen';}?>"><p class="first"><label for="<?php echo $this->get_field_id( 'sform_widget_hidden_pages' ) ?>"><?php _e( 'Selected pages', 'simpleform' ) ?>:</label><input class="widefat" id="<?php echo $this->get_field_id( 'sform_widget_hidden_pages' ); ?>" name="<?php echo $this->get_field_name( 'sform_widget_hidden_pages' ) ?>" type="text" value="<?php echo $hidden_pages; ?>" placeholder=""></p><p class="sform-widget-description"><?php _e( 'Use a comma-separated list of IDs for more than one page', 'simpleform' ) ?></p></div>

		<div id="sform-widget-visible-pages" class="widget-pages visibility-<?php echo $this->number; if ( $visibility !='visible' ) {echo ' unseen';}?>"><p class="first"><label for="<?php echo $this->get_field_id( 'sform_widget_visible_pages' ); ?>"><?php _e( 'Selected pages', 'simpleform' ) ?>:</label><input type="text" name="<?php echo $this->get_field_name('sform_widget_visible_pages') ?>" id="<?php echo $this->get_field_id( 'sform_widget_visible_pages' ) ?>" class="widefat" value="<?php echo $visible_pages ?>" placeholder=""></p><p class="sform-widget-description"><?php _e( 'Use a comma-separated list of IDs for more than one page', 'simpleform' ) ?></p></div>

        <div class="sform-widget-boxes">
        <p><b style="font-size: 13px"><?php _e( 'Add CSS selectors to customize the widget:', 'simpleform' ) ?></b></p>

        <p><label for="<?php echo $this->get_field_id( 'sform_widget_id' ) ?>"><?php _e( 'Custom ID:', 'simpleform' ); ?></label><input type="text" name="<?php echo $this->get_field_name( 'sform_widget_id' ) ?>" id="<?php echo $this->get_field_id( 'sform_widget_id' ) ?>" class="widefat" value="<?php echo esc_attr( $widget_id ) ?>"></p>

		<p class="last"><label for="<?php echo $this->get_field_id( 'sform_widget_class' ) ?>"><?php _e( 'Custom Class:', 'simpleform' ) ?></label><input type="text" name="<?php echo $this->get_field_name( 'sform_widget_class' ) ?>" id="<?php echo $this->get_field_id( 'sform_widget_class' ) ?>"   class="widefat" value="<?php echo esc_attr( $class ) ?>"></p><p class="sform-widget-notes"><?php _e( 'Separate each class with a space', 'simpleform' ) ?></p></div>

		<?php if ( $shortcode_id) { ?>
	  	<div class="sform-widget-boxes buttons"><p><b style="font-size: 13px"><?php _e( 'Change how the contact form is displayed and works:', 'simpleform' ) ?></b></p><p id="widget-buttons"><a href="<?php echo admin_url('admin.php?page=sform-editor') . '&form='. $shortcode_id; ?>" target="_blank"><span id="widget-button-editor" class="wp-core-ui button <?php echo $color ?>"><?php _e( 'Open Editor', 'simpleform' ) ?></span></a><a href="<?php echo admin_url('admin.php?page=sform-settings') . '&form='. $shortcode_id; ?>" target="_blank"><span id="widget-button-settings" class="wp-core-ui button <?php echo $color ?>"><?php _e( 'Open Settings', 'simpleform' ) ?></span></a></p></div>
	  	<?php }

	}

	/**
	 * Update the widget settings
	 *
	 * @since  1.10
	 */

	public function update( $new_instance, $old_instance ) {

        $instance = array();
        $instance['sform_widget_title'] = isset($new_instance['sform_widget_title']) ? sanitize_text_field($new_instance['sform_widget_title']) : '';
        $instance['sform_widget_audience'] = isset($new_instance['sform_widget_audience']) && in_array($new_instance['sform_widget_audience'], array('all', 'out', 'in')) ? $new_instance['sform_widget_audience'] : 'all';
        global $wp_roles;
	    $roles = $wp_roles->roles;
	    $all_roles = array('any');
	    $role_values = array_merge(array_keys($roles),$all_roles);
        $instance['sform_widget_role'] = $instance['sform_widget_audience'] == 'in' && isset($new_instance['sform_widget_role']) && in_array($new_instance['sform_widget_role'], $role_values) ? $new_instance['sform_widget_role'] : 'any';
        $settings = get_option('sform_settings');
        $instance['sform_widget_visibility'] = isset($new_instance['sform_widget_visibility']) && in_array($new_instance['sform_widget_visibility'], array('all', 'hidden', 'visible')) ? $new_instance['sform_widget_visibility'] : 'all';
        $checked_hidden_pages = preg_match('/^[0-9, ]+$/', $new_instance['sform_widget_hidden_pages']) ? str_replace(' ', '', $new_instance['sform_widget_hidden_pages']) : '';
        if ( ! empty($checked_hidden_pages) ) {
        // Remove first and last comma, empty and inexistent id
        $hidden_pages_array = explode(',',$checked_hidden_pages);
          foreach ($hidden_pages_array as $key => $post) {
	        if ( empty($post) || get_post_status($post) === FALSE ) {
            unset($hidden_pages_array[$key]);
		    }
		  }
 		$instance_hidden_pages = ! empty($hidden_pages_array) ? implode(",", array_unique($hidden_pages_array)) : '';
       }
        else {
        $instance_hidden_pages = '';
        }
        $instance['sform_widget_hidden_pages'] = $instance_hidden_pages;
        $checked_visible_pages = preg_match('/^[0-9, ]+$/', $new_instance['sform_widget_visible_pages']) ? str_replace(' ', '', $new_instance['sform_widget_visible_pages']) : '';
        if ( ! empty($checked_visible_pages) ) {
        $visible_pages_array = explode(',',$checked_visible_pages);
        foreach ($visible_pages_array as $key => $post) {
	      if ( empty($post) || get_post_status($post) === FALSE ) {
            unset($visible_pages_array[$key]);
		    }
		 }
		 $instance_visible_pages = ! empty($visible_pages_array) ? implode(",", array_unique($visible_pages_array)) : '';
		}
        else {
          $instance_visible_pages = '';
        }
        $instance['sform_widget_visible_pages'] = $instance_visible_pages;
        $instance['sform_widget_id'] = isset( $new_instance['sform_widget_id'] ) ? sanitize_text_field($new_instance['sform_widget_id']) : '';
        $instance['sform_widget_class'] = isset( $new_instance['sform_widget_class'] ) ? sanitize_text_field($new_instance['sform_widget_class']) : '';

		return $instance;

	}

	/**
	 * Display the widget on the site
	 *
	 * @since  1.10
	 */

	public function widget( $args, $instance ) {

 	    $current_user = wp_get_current_user();
        $widget_audience = isset( $instance['sform_widget_audience']) ? $instance['sform_widget_audience'] : 'all';
        $role = isset( $instance['sform_widget_role'] ) ? $instance['sform_widget_role'] : 'any';
        $widget_visibility = isset( $instance['sform_widget_visibility']) ? $instance['sform_widget_visibility'] : 'all';
        $hidden_pages = isset( $instance['sform_widget_hidden_pages'] ) ? explode(',',$instance['sform_widget_hidden_pages']) : array();
        $visible_pages = isset( $instance['sform_widget_visible_pages'] ) ? explode(',',$instance['sform_widget_visible_pages']) : array();
        $title = isset( $instance['sform_widget_title'] ) ? $instance['sform_widget_title'] : '';
        $id = isset( $instance['sform_widget_id'] ) ? $instance['sform_widget_id'] : '';
        $class = isset( $instance['sform_widget_class'] ) ? $instance['sform_widget_class'] : '';
        $shortcode_id = isset( $instance['shortcode_id'] ) ? $instance['shortcode_id'] : apply_filters( 'get_sform_shortcode_id', $this->number );
        $settings = ! empty($shortcode_id) && get_option("sform_{$shortcode_id}_settings") != false ? get_option("sform_{$shortcode_id}_settings") : get_option("sform_settings");
        $cssfile = ! empty( $settings['stylesheet_file'] ) ? esc_attr($settings['stylesheet_file']) : 'false';
        $form_template = ! empty( $settings['form_template'] ) ? esc_attr($settings['form_template']) : 'default';
        $stylesheet = ! empty( $settings['stylesheet'] ) ? esc_attr($settings['stylesheet']) : 'false';
        $ajax = ! empty( $settings['ajax_submission'] ) ? esc_attr($settings['ajax_submission']) : 'false';
        $javascript = ! empty( $settings['javascript'] ) ? esc_attr($settings['javascript']) : 'false';
        $ajax_error = ! empty( $settings['ajax_error'] ) ? stripslashes(esc_attr($settings['ajax_error'])) : __( 'Error occurred during AJAX request. Please contact support!', 'simpleform' );
        $outside_error = ! empty( $settings['outside_error'] ) ? esc_attr($settings['outside_error']) : 'bottom';
        $outside = $outside_error == 'top' || $outside_error == 'bottom' ? 'true' : 'false';
        $frontend_notice = ! empty( $settings['frontend_notice'] ) ? esc_attr($settings['frontend_notice']) : 'true';
        $is_gb_editor = defined( 'REST_REQUEST' ) && REST_REQUEST;
        global $post;
        if ( $widget_audience == 'out' ) { $form_user = '<b>' . __( 'logged-out users','simpleform') . '</b>'; $for_role = ''; }
        elseif ( $widget_audience == 'in' ) { $form_user = '<b>' . __( 'logged-in users','simpleform') . '</b>'; $for_role = $role; }
        else { $form_user = __( 'everyone','simpleform'); $for_role = ''; }
        $form_user_role = !empty($for_role) ? ' ' . __( 'with the role of','simpleform') . ' <b>' . translate_user_role(ucfirst($role)) . '</b>' : '' ;
        $role_message = '<div id="sform-admin-message" style="font-size: 0.8em; border: 1px solid; margin-top: 20px; padding: 20px 15px; height: -webkit-fit-content; height: -moz-fit-content; height: fit-content;"><p class="heading" style="font-weight: 600; margin-bottom: 10px;">'. __('SimpleForm Admin Notice', 'simpleform') . '</p>'. __('The form is visible only for ', 'simpleform') . $form_user . $form_user_role . '. ' . __( 'Your role does not allow you to see it!','simpleform') .'</div>';
        $page_message = '<div id="sform-admin-message" style="font-size: 0.8em; border: 1px solid; margin-top: 20px; padding: 20px 15px; height: -webkit-fit-content; height: -moz-fit-content; height: fit-content;"><p class="heading" style="font-weight: 600; margin-bottom: 10px;">'. __('SimpleForm Admin Notice', 'simpleform') . '</p>'. __('The form cannot be viewed in this page due to visibility settings setted!', 'simpleform') .'</div>';

        if ( ( $widget_audience == 'out' && is_user_logged_in() ) || ( $widget_audience == 'in' && ! is_user_logged_in() ) || ( $widget_audience == 'in' && is_user_logged_in() && $role != 'any' && ! in_array( $role, (array) $current_user->roles ) ) || ( $widget_visibility == 'hidden' && $post && in_array($post->ID,$hidden_pages) ) || ( $widget_visibility == 'visible' && $post && ! in_array($post->ID,$visible_pages) ) )
	      // Check if current request is inside the Customizer preview, if true show a notice when option enabled
          if ( is_customize_preview() && $frontend_notice == 'true' ) {
	        echo $args['before_widget'] . $role_message . $args['after_widget'];
            return;
          }
          else {
	        if ( ! $is_gb_editor ) { return; }
          }

	    echo $args['before_widget'] . '<div id="'.$id.'" class="sforms-widget '.$class.'">';
	    if ( $title ) { echo $args['before_title'] .  $title . $args['after_title']; }
	    echo do_shortcode( '[simpleform id="'.$shortcode_id.'"]' );
	    echo '</div>' . $args['after_widget'];

        if ( $stylesheet == 'false' ) { wp_enqueue_style( 'sform-public-style' ); }
        else { if( $cssfile == 'true' ) { wp_enqueue_style( 'sform-custom-style' ); } }
        wp_localize_script('sform_public_script', 'ajax_sform_processing', array('ajaxurl' => admin_url('admin-ajax.php'), 'ajax_error' => $ajax_error, 'outside' => $outside ));
        wp_enqueue_script( 'sform_form_script');
        if( $ajax == 'true' ) { wp_enqueue_script( 'sform_public_script'); }
        if ( $javascript == 'true' ) {
          if (is_child_theme() ) { wp_enqueue_script( 'sform-custom-script',  get_stylesheet_directory_uri() . '/simpleform/custom-script.js',  array( 'jquery' ), '', true ); }
          else { wp_enqueue_script( 'sform-custom-script',  get_template_directory_uri() . '/simpleform/custom-script.js',  array( 'jquery' ), '', true ); }
	    }

	}

	/**
	 * Add/Remove form usage data when updating widgets
	 *
	 * @since  2.1.2
	 */

	public function update_forms($sidebars_widgets) {

	   global $wp_registered_sidebars, $wpdb;
       $sform_widget = get_option('widget_sform_widget');

       foreach ( $sidebars_widgets as $sidebar => $widgets ) {
	     if ( is_array( $widgets ) && $sidebar !== 'wp_inactive_widgets' ) {
		   foreach ( $widgets as $key => $value ) {
		 	 if ( strpos($value, 'sform_widget-' ) !== false ) {
                $id =  explode("sform_widget-", $value)[1];
		        if ( ! isset( $sform_widget[$id]['shortcode_id'] ) ) {
	               $widget_area = isset($wp_registered_sidebars[$sidebar]['name']) ? $wp_registered_sidebars[$sidebar]['name'] : '';
                   $form_id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}sform_shortcodes WHERE widget = %d", $id ) );
                   if ( ! $form_id ) {
                    $rows = $wpdb->get_row("SHOW TABLE STATUS LIKE '{$wpdb->prefix}sform_shortcodes'");
                    $shortcode_id = $rows->Auto_increment;
                    $shortcode_name = 'simpleform id="'.$shortcode_id.'"';
 	                $search_name = '%'. __( 'Contact Form','simpleform') . '%';
	                $names = $wpdb->get_col( $wpdb->prepare("SELECT name FROM {$wpdb->prefix}sform_shortcodes WHERE name LIKE %s", $search_name) );
 	                $number =array();
	                if ($names) {
		            foreach ( $names as $name ) {
			         $suffix = filter_var($name, FILTER_SANITIZE_NUMBER_INT);
			         if ($suffix) { array_push($number,$suffix); } else { array_push($number,'0'); }
			        }
			        }
			        $new_suffix = !empty($number)? max($number) + 1 : '';
			        $name_suffix = $new_suffix == 1 ? '2' : $new_suffix;
			        $form_name = __( 'Contact Form','simpleform') . ' ' . $name_suffix;
                    $wpdb->insert($wpdb->prefix . 'sform_shortcodes', array('shortcode' => $shortcode_name, 'area' => $widget_area, 'name' => $form_name, 'form_widgets' => $value, 'widget' => $id, 'status' => 'published', 'target' => 'all' ));
                    $sform_widget[$id]['shortcode_id'] = $shortcode_id;
                    update_option('widget_sform_widget', $sform_widget);
                    $default_attributes = get_option("sform_attributes");
		            $default_attributes['form_name'] = $form_name;
		            $default_attributes['label_position'] = 'top';
                    add_option("sform_{$shortcode_id}_attributes", $default_attributes);
                    $default_settings = get_option("sform_settings");
                    add_option("sform_{$shortcode_id}_settings", $default_settings);
                 }
                }
             }
	       }
	     }
	   }

       return $sidebars_widgets;

	}

	/**
	 * Delete the form after a widget has been marked for deletion
	 *
	 * @since  1.10
	 */

	public function cleanup_sform_shortcodes( $widget_id, $sidebar_id, $id_base ) {

      if ( $id_base == 'sform_widget' ) {
         $id =  explode("sform_widget-", $widget_id)[1];
	     global $wpdb;
         $form_id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}sform_shortcodes WHERE widget = %d", $id ) );
         $wpdb->delete($wpdb->prefix . 'sform_shortcodes', array('widget' => $id ));
         $wpdb->delete( $wpdb->prefix . 'sform_submissions', array('form' => $form_id ) );
         $pattern1 = 'sform_'.$form_id.'_%';
         $pattern2 = 'sform_last_'.$form_id.'_message';
         $wpdb->query( $wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%s' OR option_name = '%s'", $pattern1, $pattern2) );
         $sform_widget = get_option('widget_sform_widget');
         unset($sform_widget[$id]);
         update_option('widget_sform_widget', $sform_widget);
      }

    }

	/**
	 * Get the form ID used in the widget
	 *
	 * @since  2.1.2
	 */

 	public function get_form_id($widget_id) {

      $id = is_int($widget_id) && (int)$widget_id > 0 ? $widget_id : '';
      $shortcode_id = '';

      if ( ! empty($id) ) {
        global $wpdb;
        $formID = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}sform_shortcodes WHERE widget = %d", $id ) );
        if ( isset($formID) ) {
	      $sform_widget = get_option('widget_sform_widget');
          $sform_widget[$widget_id]['shortcode_id'] = $formID;
          update_option('widget_sform_widget', $sform_widget);
          $shortcode_id = $formID;
        }
      }
      else {
        global $wpdb;
        $rows = $wpdb->get_row("SHOW TABLE STATUS LIKE '{$wpdb->prefix}sform_shortcodes'");
        $shortcode_id = $rows->Auto_increment;
      }

      return $shortcode_id;

    }

	/**
	 * Hide the widget from the legacy widget block dropdown and from the block inserter when the widgets block editor is enabled
	 *
	 * @since  2.0.3
	 */

	public function hide_sform_widget( $widget_types ) {

       $widget_types[] = 'sform_widget';
       $widget_types[] = 'sform_widget-';

       return $widget_types;

    }

	/**
	 * Clean up the widget areas of any non-existent and redundant form
	 *
	 * @since  2.1.3
	 */

    public function widgets_cleaning() {
	    
      $sidebars_widgets = get_option("sidebars_widgets") != false ? get_option("sidebars_widgets") : array();
	  $widget_block = get_option("widget_block") != false ? get_option("widget_block") : array();
      $sform_widget = get_option('widget_sform_widget') != false ? get_option("widget_sform_widget") : array();
      global $wpdb;
      $used_ids = array();
      $shortcodes = array();

 	  if ( ! empty($widget_block) ) {
         $util = new SimpleForm_Util();
         $form_ids = $util->sform_ids();
	     foreach ( $sidebars_widgets as $sidebar => $widgets ) {
	  	   if ( is_array( $widgets ) && $sidebar === 'wp_inactive_widgets' ) {
		     foreach ( $widgets as $key => $value ) {
 	           if ( strpos($value, 'block-' ) !== false ) {
		          $block_id = substr($value, 6);
		          foreach ($widget_block as $block_key => $block_value ) {
			        if ( $block_key == $block_id ) {
                      $string = implode('',$block_value);
  				      if ( strpos($string, 'wp:simpleform/form-selector' ) !== false || strpos($string,'[simpleform') !== false ) {
	                          unset( $sidebars_widgets[$sidebar][$key] );
	                          update_option('sidebars_widgets', $sidebars_widgets);
	                          unset( $widget_block[$block_key] );
	                          update_option('widget_block', $widget_block);
                      }
                    }
		          }
		       }
             }
           }
	       if ( is_array( $widgets ) && $sidebar !== 'wp_inactive_widgets' ) {
		     foreach ( $widgets as $key => $value ) {
 	           if ( strpos($value, 'block-' ) !== false ) {
		          $block_id = substr($value, 6);
		          foreach ($widget_block as $block_key => $block_value ) {
			        if ( $block_key == $block_id ) {
                      $string = implode('',$block_value);
  				      if ( strpos($string, 'wp:simpleform/form-selector' ) !== false ) {
                 	    $split_id = ! empty($string) ? explode('formId":"', $string) : '';
	                    $form_id = isset($split_id[1]) ? explode('"', $split_id[1])[0] : '';
                        if (in_array($form_id, $used_ids) || ! in_array($form_id, $form_ids) ) {
	                        // Prioritize the use of the block
	                        $prioritize = '';
	                        if ( !empty($shortcodes) ) {
		                      foreach ( $shortcodes as $shortcode ) {
			                    $split_shortcode = explode(';',$shortcode);
			                    $form = isset($split_shortcode[2]) ? $split_shortcode[2] : '';
                                if ( $form_id == $form ) {
                                  $prioritize .= $shortcode;
                                }
	                          }
	                        }
	                        if ( $prioritize ) {
			                  $split_shortcode = explode(';',$shortcode);
			                  $key_shortcode = isset($split_shortcode[2]) ? $split_shortcode[0] : '';
			                  $block_key_shortcode = isset($split_shortcode[2]) ? $split_shortcode[1] : '';
			                  if ( !empty($key) && !empty($block_key) ) {
	                            unset( $sidebars_widgets[$sidebar][$key_shortcode] );
	                            update_option('sidebars_widgets', $sidebars_widgets);
	                            unset( $widget_block[$block_key_shortcode] );
	                            update_option('widget_block', $widget_block);
	                          }
	                        }
	                        else {
	                          unset( $sidebars_widgets[$sidebar][$key] );
	                          update_option('sidebars_widgets', $sidebars_widgets);
	                          unset( $widget_block[$block_key] );
	                          update_option('widget_block', $widget_block);
	                        }
	                    }
	                    else { $used_ids[] = $form_id; }
                      }
                      if ( strpos($string,'[simpleform') !== false ) {
	                    $split_shortcode = ! empty($string) ? explode('[simpleform', $string) : '';
	                    $split_id = isset($split_shortcode[1]) ? explode(']', $split_shortcode[1])[0] : '';
	                    $form_id = empty($split_id) ? '1' : filter_var($split_id, FILTER_SANITIZE_NUMBER_INT);
                        if (in_array($form_id, $used_ids) || ! in_array($form_id, $form_ids) ) {
	                      unset( $sidebars_widgets[$sidebar][$key] );
	                      update_option('sidebars_widgets', $sidebars_widgets);
	                      unset( $widget_block[$block_key] );
	                      update_option('widget_block', $widget_block);
	                    }
	                    else { 
		                  $used_ids[] = $form_id; 
                          $shortcodes[] = $key.';'.$block_key.';'.$form_id; 
		                }
 		              }
                    }
		          }
		       }
		       // Prioritize the use of the block in case of legacy widget
		 	   if ( strpos($value, 'sform_widget-' ) !== false ) {
                  $id =  explode("sform_widget-", $value)[1];
		          if ( isset( $sform_widget[$id]['shortcode_id'] ) ) { $form_id = $sform_widget[$id]['shortcode_id']; }
			      else { $form_id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}sform_shortcodes WHERE widget = %d", $id ) ); }
	              if (in_array($form_id, $used_ids) ) {
		              unset( $sidebars_widgets[$sidebar][$key] );
	                  update_option('sidebars_widgets', $sidebars_widgets);
                      unset($sform_widget[$id]);
                      update_option('widget_sform_widget', $sform_widget);
		          }
               }
              }
              
           }
	       
         }
	  }

    }
    
	/**
	 * Edit form usage data when updating block widgets
	 *
	 * @since  2.1.7
	 */

    public function form_widgets() {

      $widget_block = get_option("widget_block") != false ? get_option("widget_block") : array();
      
      if ( $widget_block ) {
  
        // Clear all form pages
        global $wpdb;
        $wpdb->query("UPDATE {$wpdb->prefix}sform_shortcodes SET form_widgets = ''");
        
        $pattern_block = '/<!-- wp:simpleform(.*)\/-->/';
        $pattern_shortcode = '/\[simpleform(.*?)\]/';
        
        foreach ( $widget_block as $key => $value ) {
	      $blockId = 'block-' . $key;
	      $blockId_array = array($blockId); 
          if ( isset($value['content']) && preg_match($pattern_block,$value['content']) ) { 
            $split_id = explode('formId":"', $value['content']);
	        $form = isset($split_id[1]) ? explode('"', $split_id[1])[0] : '';
            if( $form ) {
              $form_widgets = $wpdb->get_var( $wpdb->prepare( "SELECT form_widgets FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form ) );
              $form_widgets_array = ! empty($form_widgets) ? explode(',',$form_widgets) : array();
              if ( ! in_array($blockId,$form_widgets_array) ) {
	            $new_form_widgets = implode(",", array_unique(array_merge($blockId_array,$form_widgets_array)));
                $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_shortcodes SET form_widgets = %s, status = 'published' WHERE id = %d", $new_form_widgets, $form) );
              }
            }
	      }
          if ( isset($value['content']) && preg_match($pattern_shortcode,$value['content']) ) { 
            $split_id = explode('[simpleform', $value['content']);
	        $form_id = isset($split_id[1]) ? explode(']', $split_id[1])[0] : '';
            $form = empty($form_id) ? '1' : filter_var($form_id, FILTER_SANITIZE_NUMBER_INT);
            $form_widgets = $wpdb->get_var( $wpdb->prepare( "SELECT form_widgets FROM {$wpdb->prefix}sform_shortcodes WHERE id = %d", $form ) );
            $form_widgets_array = ! empty($form_widgets) ? explode(',',$form_widgets) : array();
            if ( ! in_array($blockId,$form_widgets_array) ) {
	            $new_form_widgets = implode(",", array_unique(array_merge($blockId_array,$form_widgets_array)));
                $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->prefix}sform_shortcodes SET form_widgets = %s, status = 'published' WHERE id = %d", $new_form_widgets, $form) );
            }
	      }
        }
        
	  }

    }
           
}

 /**
 * Register and load the widget
 *
 * @since  1.10
 */

function register_sform_widget() {
  register_widget( 'SimpleForm_Widget' );
}

add_action( 'widgets_init', 'register_sform_widget' );