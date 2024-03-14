<?php

class Youzify_Widgets {

	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	/**
	 * Return the instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	function __construct() {

		// Include WIdgets
		$this->include_widgets();

	 	// Filters.
	 	add_filter( 'youzify_display_profile_widget_title', array( $this, 'display_widgets_title_for_profile_owner' ), 10, 2 );

	}

	/**
	 * Include Widgets
	 */
	function include_widgets() {

        // Include Widgets
        require_once YOUZIFY_CORE . 'widgets/youzify-widgets/class-youzify-infos-boxes.php';

	}

	/**
	 * Get All Widgets
	 */
	function get_all_widgets() {
		return apply_filters( 'youzify_profile_widgets_args', array( 'post', 'link', 'quote', 'video', 'flickr', 'skills', 'groups', 'friends', 'project', 'reviews', 'about_me', 'services', 'user_tags', 'portfolio', 'slideshow', 'instagram', 'wall_media', 'user_badges', 'user_balance', 'gamipress_user_badges', 'gamipress_user_balance', 'recent_posts', 'phone', 'email', 'social_networks', 'website', 'address', 'login' ) );
	}

	/**
	 * Widget Core
	 */
	function check_widget_content( $widget_name, $function_options ) {

		ob_start();

		// Get Widget Content.
		$this->$widget_name->widget( $function_options );

		ob_flush();

		$content = ob_get_contents();

		ob_end_clean();

		return $content;

	}

	/**
	 * Widget Class Name
	 */
	function youzify_widget_class_name( $args ) {

		// Create Empty Array.
		$widget_class = array( 'youzify-' . $args['id'] );

		// Prepare Class Name
	    $load_effect = isset( $args['load_effect'] ) ? $args['load_effect'] : null;
	    $widget_class[] = $this->get_loading_effect( $load_effect, 'class' );

	    // Add title class.
		if ( isset( $args['display_title'] ) && $args['display_title'] == 'off' ) {
			$widget_class[] = "without-title";
		}

	    // Add background class.
	    if ( ! in_array( $args['id'], array( 'youzify_widget_infos_box', 'ad' ) ) ) {
	    	$widget_class[] = 'youzify-white-bg';
	    }

		// Get AD class.
	    if ( 'ad' == $args['id'] ) {
			if ( 'true' == $this->is_sponsored_ad( $args['function_options'] ) ) {
				$widget_class[] = 'youzify-white-bg';
			} else {
				$widget_class[] = 'youzify-no-bg';
			}
	    }

	    // Title Icon Style.
	    if ( 'on' == youzify_option( 'youzify_use_wg_title_icon_bg', 'on' ) ) {
			$widget_class[] = 'youzify-wg-title-icon-bg';
	    }

		// Return Widget Class Name
		return youzify_generate_class( $widget_class );
	}

	/**
	 * Get Loading Effect
	 */
	function get_loading_effect( $load_effect, $data_type = 'data' ) {

		// Check if it's allowed to use loading effects.
		if ( 'on' != youzify_option( 'youzify_use_effects', 'off' )) {
			return false;
		}

		// Use effect class.
		if ( 'class' == $data_type || 'navbar' == $data_type ) {
			return 'youzify_effect';
		} elseif ( $data_type == 'data' ) {
			// Get effects data value.
			if ( ! empty( $load_effect ) ) {
				return "data-effect='$load_effect'";
			} else {
				return 'data-effect="fadeIn"';
			}
		}

	}

	/**
	 * Get Widgets Without Front-end Settings
	 */
	function settings_widgets() {
		return apply_filters( 'youzify_settings_widgets', array( 'about_me', 'skills', 'portfolio', 'slideshow', 'services', 'project', 'quote', 'link', 'video', 'post', 'instagram', 'flickr' ) );
	}

	/**
	 * Get Settings Widgets
	 */
	function get_settings_widgets() {

		$widgets = array();

		// All Widgets.
		$all_widgets = $this->settings_widgets();

		// if user have no posts don't show the post form.
		if ( ! current_user_can( 'edit_posts') ) {
			if ( ( $key = array_search( 'post', $all_widgets ) ) !== false ) {
			    unset( $all_widgets[ $key ] );
			}
		}

		// Get All Widgets.
		$default_widgets = youzify_profile_widgets();

		// Unset Invisible Widgets.
		$hidden_widgets = youzify_get_profile_hidden_widgets();

		foreach ( $all_widgets as $widget_name ) {

			if ( in_array( $widget_name, $hidden_widgets ) ) {
				continue;
			}

			$widgets[ $widget_name ] = youzify_get_profile_widget_args( $widget_name );

		}

		// Sort array numerically.
		if ( ! empty( $widgets ) ) {
			usort( $widgets, 'youzify_sortByMenuOrder' );
		}

		return $widgets;
	}

	/**
	 * Get AD Class
	 */
	function is_sponsored_ad( $ad_name ) {
		$get_ads = youzify_option( 'youzify_ads' );
		$is_sponsored = $get_ads[ $ad_name ]['is_sponsored'];
		return $is_sponsored;
	}

	/**
	 * Get Widget Content.
	 */
	function get_widget_content( $widgets ) {

		// Filter
		$widgets = apply_filters( 'youzify_get_widgets_content', $widgets );

		// Display Widgets
		$default_widgets = youzify_profile_widgets();

		foreach ( $widgets as $widget_name => $visibility ) {

			$class = false;

			if ( 'visible' != $visibility ) {
				continue;
			}

			$class = $this->get_widget_class( $widget_name, $default_widgets );

			$this->youzify_widget_core( $widget_name, $class );

		}
	}

	/**
	 * Get Widget Class
	 */
	function get_widget_class( $widget_name, $default_widgets = null) {

		// Init Vars
		$default_widgets = ! empty( $default_widgets ) ? $default_widgets : youzify_profile_widgets();

		if ( isset( $default_widgets[ $widget_name ] ) ) {
			if ( isset( $default_widgets[ $widget_name ]['file'] ) ) {
    			include YOUZIFY_CORE . 'widgets/youzify-widgets/class-youzify-' . $default_widgets[ $widget_name ]['file'] . '.php';
			}
    		$class = new $default_widgets[ $widget_name ]['class']();
		} else {
			if ( youzify_is_custom_widget( $widget_name ) ) {
				require_once YOUZIFY_CORE . 'widgets/youzify-widgets/class-youzify-custom-widgets.php';
				$class = new Youzify_Profile_Custom_Widget( $widget_name );
			} elseif ( youzify_is_ad_widget( $widget_name ) ) {
    			require_once YOUZIFY_CORE . 'widgets/youzify-widgets/class-youzify-ads.php';
				$class = new Youzify_Profile_Ads_Widget( $widget_name );
			}

		}

		return $class;
	}

	/**
	 * Widget Core
	 */
	function youzify_widget_core( $widget_name, $class, $args = null ) {

		$args = ! empty( $args ) ? $args : youzify_get_profile_widget_args( $widget_name );

		// Init variables.
	 	$widget_name = $args['id'];
		$function_options = isset( $args['function_options'] ) ? $args['function_options'] : null;

		// Check Content Existence.
		ob_start();
		$class->widget( $function_options );
		$widget_content = ob_get_contents();
		ob_end_clean();

		// if there's no content exit.
		if ( empty( $widget_content ) ) {
			return false;
		}

		// Get Loading Effects.
		$args['load_effect'] = youzify_option( 'youzify_' . $widget_name . '_load_effect', 'fadeIn' );

		// Get Widget Data.
		$display_title = isset( $args['display_title'] ) ?  $args['display_title'] : youzify_option( 'youzify_wg_' . $widget_name . '_display_title', 'on' );

		// Display tilte if value equal true also.
		if ( empty( $display_title ) || 'true' == $display_title ) {
			$display_title = 'on';
		}

		?>

		<div class="youzify-widget <?php echo $this->youzify_widget_class_name( $args ); ?>" <?php echo $this->get_loading_effect( $args['load_effect'] ); ?>>

			<div class="youzify-widget-main-content">

				<?php if ( 'on' == apply_filters( 'youzify_display_profile_widget_title', $display_title, $widget_name ) ) : ?>
				<div class="youzify-widget-head">
					<h2 class="youzify-widget-title">
						<?php if ( 'on' == youzify_option( 'youzify_display_wg_title_icon', 'on' ) ) : ?>
							<?php echo apply_filters( 'youzify_profile_widget_title_icon', '<i class="' . $args['icon'] . '"></i>', $widget_name ); ?>
						<?php endif; ?>
						<?php echo $args['name']; ?>
					</h2>
					<?php if ( bp_core_can_edit_settings() && ( in_array( $widget_name, $this->settings_widgets() ) || 'custom_infos' == $widget_name  ) ) :?>
					<a href="<?php echo apply_filters( 'youzify_profile_widgets_edit_link', youzify_get_widgets_settings_url( $widget_name ), $widget_name ); ?>"><i class="far fa-edit youzify-edit-widget"></i></a>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<div class="youzify-widget-content">
					<?php do_action( 'youzify_before_widget_content', $widget_name ); ?>
					<?php echo $widget_content; ?>
					<?php do_action( 'youzify_after_widget_content', $widget_name ); ?>
				</div>

			</div>

		</div>

		<?php

	}

	/**
	 * Display Widgets Title For Profile Owner.
	 */
	function display_widgets_title_for_profile_owner( $show, $widget_name ) {

		if ( ! bp_core_can_edit_settings() ) {
			return $show;
		}

		$widgets = array( 'quote', 'link', 'post', 'project' );

		if ( in_array( $widget_name, $widgets ) ) {
			return 'on';
		}

		return $show;
	}

}


/**
 * Get a unique instance of Youzify Widgets.
 */
function youzify_widgets() {
	return Youzify_Widgets::get_instance();
}

/**
 * Launch Youzify Users!
 */
youzify_widgets();