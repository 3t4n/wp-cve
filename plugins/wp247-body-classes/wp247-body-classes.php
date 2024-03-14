<?php
/*
	Plugin Name: WP247 Body Classes
	Version: 2.1.1
	Description: Add unique classes to the body tag for easy styling based on post attributes (post type, slug, and ID) and various wordpress "is" functions and mobile_detect script:
					wp_is_mobile()
					is_home()
					is_front_page()
					is_blog()
					is_admin()
					is_admin_bar_showing()
					is_404()
					is_super_admin()
					is_user_logged_in()
					is_search()
					is_archive()
					is_author()
					is_category()
					is_tag()
					is_tax()
					is_date()
					is_year()
					is_month()
					is_day()
					is_time()
					is_single()
					is_sticky()
					$post->post_type
					$post->name
					$post->ID
					wp_get_post_categories()
					wp_get_post_tags()
					$user->nicename
					$user->id
					$user->roles
					$user->allcaps
					$archive->slug
					$archive->id

	Tags: user capabilities, user roles, scroll, mobile, post type, post category, post categories, post tag, post tags, individual post body classes, body, class, custom CSS, CSS, custom Body Classes, wp_is_mobile, is_home, is_front_page, is_blog, is_admin, is_admin_bar_showing, is_404, is_super_admin, is_user_logged_in, is_search, is_archive, is_author, is_category, is_tag, is_tax, is_date, is_year, is_month, is_day, is_time, is_single, is_sticky, is-mobile, is-tablet, is-phone, mobile_detect
	Author: wp247
	Author URI: http://wp247.net/
	Text domain: wp247-body-classes
	Uses: weDevs Settings API wrapper class from http://tareq.weDevs.com Tareq's Planet
*/

// Don't allow direct execution
defined( 'ABSPATH' ) or die ( 'Forbidden' );

// Set to true to get debug array listed at the bottom of the html body
defined( 'WP247_BODY_CLASSES_DEBUG' ) or define( 'WP247_BODY_CLASSES_DEBUG', false );

if ( !defined( 'WP247_BODY_CLASSES_VERSION' ) )
{

	define( 'WP247_BODY_CLASSES_VERSION', '2.1' );
	define( 'WP247_BODY_CLASSES_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
	define( 'WP247_BODY_CLASSES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	define( 'WP247_BODY_CLASSES_PLUGIN_NAME', 'WP247 Body Classes' );
	define( 'WP247_BODY_CLASSES_PLUGIN_ID', basename( dirname( __FILE__ ) ) );
	define( 'WP247_BODY_CLASSES_PLUGIN_NONCE_ID', 'wp247_body_classes_nonce' );
	define( 'WP247_BODY_CLASSES_POST_META_KEY', 'wp247_body_classes_post_body_classes' );
	define( 'WP247_BODY_CLASSES_POST_META_NAME', 'wp247-body-classes-post-body-classes' );
	define( 'WP247_BODY_CLASSES_COREQUISITE_NOTICE', false );

	global $wp247_body_classes_debug;
	$wp247_body_classes_debug = array();
	global $wp247_mobile_detect;
	$wp247_mobile_detect = null;

	if ( WP247_BODY_CLASSES_DEBUG ) add_action( 'wp_footer', 'wp247_body_classes_do_action_wp_footer', 99999 );

	add_action( 'wp_loaded','wp247_body_classes_do_action_wp_loaded');
	add_action( 'wp_head', 'wp247_body_classes_do_action_wp_head', 99999 );
	add_filter( 'body_class', 'wp247_body_classes_do_action_body_class' );
	add_filter( 'wp247xns_client_extension_poll_plugin_'.WP247_BODY_CLASSES_PLUGIN_ID, 'wp247_body_classes_do_filter_wp247xns_client_extension_poll' );

	/*
	 * Tell WP247 Extension Notification Client about us
	 */
	function wp247_body_classes_do_filter_wp247xns_client_extension_poll( $extensions )
	{
		return array(
					 'id'			=> WP247_BODY_CLASSES_PLUGIN_ID
					,'version'		=> WP247_BODY_CLASSES_VERSION
					,'name'			=> 'WP247 Body Classes'
					,'type'			=> 'plugin'
					,'server_url'	=> 'http://wp247.net/wp-admin/admin-ajax.php'
					,'frequency'	=> '1 day'
				);
	}

	/*
	 * Load Admin Settings if this user can manage options
	 */
	function wp247_body_classes_do_action_wp_loaded()
	{
		if ( current_user_can( 'edit_posts' ) )
		{
			add_action( 'load-post.php', 'wp247_body_classes_do_action_load_post' );
			add_action( 'load-post-new.php', 'wp247_body_classes_do_action_load_post' );
		}
		if ( current_user_can( 'manage_options' ) and is_admin() )
		{
			require_once WP247_BODY_CLASSES_PLUGIN_PATH . 'admin/wp247-body-classes-admin.php';
		}
	}
	
	/*
	 * Setup Individual Post Body Classes meta box
	*/
	function wp247_body_classes_do_action_load_post()
	{
		add_action( 'add_meta_boxes', 'wp247_body_classes_do_action_add_meta_boxes' );
		add_action( 'save_post', 'wp247_body_classes_do_action_save_post', 10, 2 );
	}
	
	/* Tell WordPress we have a Post Body Classes meta box. */
	function wp247_body_classes_do_action_add_meta_boxes()
	{
		add_meta_box(
			WP247_BODY_CLASSES_POST_META_KEY
		   ,_( 'Body Classes' )
		   ,'wp247_body_classes_meta_boxes_callback'
		   ,get_post_types()
		   ,'side'
		   ,'default'
		   ,null
		);
	}
	
	/* Create the Post Body Classes meta box. */
	function wp247_body_classes_meta_boxes_callback( $post )
	{
?>
  <?php wp_nonce_field( basename( __FILE__ ), WP247_BODY_CLASSES_PLUGIN_NONCE_ID ); ?>
  <p>
    <label for="wp247-body-classes-post-body-classes"><?php _e( 'Add one or more custom CSS classes to the body tag for this WordPress post.' ); ?></label>
    <br /><br />
    <input class="widefat" type="text" name="<?php echo WP247_BODY_CLASSES_POST_META_NAME; ?>" id="<?php echo WP247_BODY_CLASSES_POST_META_NAME; ?>" value="<?php echo get_post_meta( $post->ID, WP247_BODY_CLASSES_POST_META_KEY, true ); ?>" size="30" />
    <br /><br />
	<?php _e( 'Seperate each class name by a space.' ); ?>
  </p>
<?php
	}
	
	/* Save the meta box's post metadata. */
	function wp247_body_classes_do_action_save_post( $post_id, $post )
	{
		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST[ WP247_BODY_CLASSES_PLUGIN_NONCE_ID ] )
		  or !wp_verify_nonce( $_POST[ WP247_BODY_CLASSES_PLUGIN_NONCE_ID ], basename( __FILE__ ) ) )
		{
			return $post_id;
		}

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		{
			return $post_id;
		}

		/* Get the posted data and sanitize it for use as an HTML class. */
		$new_meta_value = '';
		foreach ( explode( ' ', $_POST[ WP247_BODY_CLASSES_POST_META_NAME ] ) as $class ) {
			$new_meta_value .= ' ' . sanitize_html_class( $class );
		}
		$new_meta_value = preg_replace( '/\s+/', ' ', trim( $new_meta_value ) );

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, WP247_BODY_CLASSES_POST_META_KEY, true );

		/* If a new meta value was added and there was no previous value, add it. */
		if ( $new_meta_value and '' == $meta_value )
		{
			add_post_meta( $post_id, WP247_BODY_CLASSES_POST_META_KEY, $new_meta_value, true );
		}

		/* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value and $new_meta_value != $meta_value )
		{
			update_post_meta( $post_id, WP247_BODY_CLASSES_POST_META_KEY, $new_meta_value );
		}

		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( '' == $new_meta_value and $meta_value )
		{
			delete_post_meta( $post_id, WP247_BODY_CLASSES_POST_META_KEY, $meta_value );
		}
	}

	function wp247_body_classes_do_action_wp_head()
	{
		$css = get_option( 'wp247_body_classes_css', '' );
		if ( isset( $css[ 'custom-css' ] ) and !empty( $css[ 'custom-css' ] ) ) echo "\n<style type=\"text/css\">\n/* wp247-body-classes Custom CSS */\n".$css[ 'custom-css' ]."\n</style>\n";

		$scr = get_option( 'wp247_body_classes_scroll', '' );

		$sbg_is			= isset( $scr[ 'scroll-general' ][ 'is-scroll' ] );
		$sbg_isnot		= isset( $scr[ 'scroll-general' ][ 'is-not-scroll' ] );
		$sbg_istop		= isset( $scr[ 'scroll-general' ][ 'is-scroll-top' ] );
		$sbg_isnottop	= isset( $scr[ 'scroll-general' ][ 'is-not-scroll-top' ] );

		$sbpx_top = isset( $scr[ 'scroll-by-pixel' ][ 'is-scroll-top-px' ] );
		$sbpx_mid = isset( $scr[ 'scroll-by-pixel' ][ 'is-scroll-mid-px' ] );
		$sbpx_n   = isset( $scr[ 'scroll-by-pixel' ][ 'is-scroll-n-px' ] );
		$sbpx_max = isset( $scr[ 'scroll-by-pixel' ][ 'is-scroll-max-px' ] );
		$sbpx_inc = isset( $scr[ 'scroll-by-pixel-increment' ] ) ? wp247_body_classes_number_default_if_empty( $scr[ 'scroll-by-pixel-increment' ], 5 ) : 5;
		$sbpx_st = isset( $scr[ 'scroll-by-pixel-start' ] ) ? wp247_body_classes_number_default_if_empty( $scr[ 'scroll-by-pixel-start' ], 0 ) : 0;
		$sbpx_lim = isset( $scr[ 'scroll-by-pixel-limit' ] ) ? wp247_body_classes_number_default_if_empty( $scr[ 'scroll-by-pixel-limit' ], 0 ) : 0;

		$sbvh_top = isset( $scr[ 'scroll-by-viewport' ][ 'is-scroll-top-vh' ] );
		$sbvh_mid = isset( $scr[ 'scroll-by-viewport' ][ 'is-scroll-mid-vh' ] );
		$sbvh_n   = isset( $scr[ 'scroll-by-viewport' ][ 'is-scroll-n-vh' ] );
		$sbvh_max = isset( $scr[ 'scroll-by-viewport' ][ 'is-scroll-max-vh' ] );
		$sbvh_inc = isset( $scr[ 'scroll-by-viewport-increment' ] ) ? wp247_body_classes_number_default_if_empty( $scr[ 'scroll-by-viewport-increment' ], 5 ) : 5;
		$sbvh_st = isset( $scr[ 'scroll-by-viewport-start' ] ) ? wp247_body_classes_number_default_if_empty( $scr[ 'scroll-by-viewport-start' ], 0 ) : 0;
		$sbvh_lim = isset( $scr[ 'scroll-by-viewport-limit' ] ) ? wp247_body_classes_number_default_if_empty( $scr[ 'scroll-by-viewport-limit' ], 0 ) : 0;

		$sbdh_top = isset( $scr[ 'scroll-by-document' ][ 'is-scroll-top-ph' ] );
		$sbdh_mid = isset( $scr[ 'scroll-by-document' ][ 'is-scroll-mid-ph' ] );
		$sbdh_n   = isset( $scr[ 'scroll-by-document' ][ 'is-scroll-n-ph' ] );
		$sbdh_max = isset( $scr[ 'scroll-by-document' ][ 'is-scroll-max-ph' ] );
		$sbdh_inc = isset( $scr[ 'scroll-by-document-increment' ] ) ? wp247_body_classes_number_default_if_empty( $scr[ 'scroll-by-document-increment' ], 5 ) : 5;
		$sbdh_st = isset( $scr[ 'scroll-by-document-start' ] ) ? wp247_body_classes_number_default_if_empty( $scr[ 'scroll-by-document-start' ], 0 ) : 0;
		$sbdh_lim = isset( $scr[ 'scroll-by-document-limit' ] ) ? wp247_body_classes_number_default_if_empty( $scr[ 'scroll-by-document-limit' ], 0 ) : 0;

		$sbg  = ( $sbg_is or $sbg_isnot or $sbg_istop or $sbg_isnottop );
		$sbpx  = ( $sbpx_top or $sbpx_mid or $sbpx_n or $sbpx_max );
		$sbvh  = ( $sbvh_top or $sbvh_mid or $sbvh_n or $sbvh_max );
		$sbdh  = ( $sbdh_top or $sbdh_mid or $sbdh_n or $sbdh_max );

		if ( $sbg or $sbpx or $sbvh or $sbdh )
		{
			echo sprintf( "\n<script type='text/javascript'>\n/* wp247-body-classes Scroll options */\nvar wp247_body_classes_scroll_options = {"
							." general_options : { scroll : '%s', scroll_top : '%s', not_scroll : '%s', not_scroll_top : '%s', prev_offset : 0 },"
							." pixel_options : { active : '%s', increment : %s, start : %s, limit : %s, type : 'abs', suffix : 'px', do_top : '%s', do_mid : '%s', do_n : '%s', do_max : '%s', prev_offset : 0, prev_class : '' },"
							." view_options : { active : '%s', increment : %s, start : %s,  limit : %s, type : 'pct', suffix : 'vh', do_top : '%s', do_mid : '%s', do_n : '%s', do_max : '%s', prev_offset : 0, prev_class : '' },"
							." doc_options : { active : '%s', increment : %s, start : %s,  limit : %s, type : 'pct', suffix : 'dh', do_top : '%s', do_mid : '%s', do_n : '%s', do_max : '%s', prev_offset : 0, prev_class : '' }};\n</script>"
						, wp247_body_classes_on_if_true( $sbg_is ), wp247_body_classes_on_if_true( $sbg_istop ), wp247_body_classes_on_if_true( $sbg_isnot ), wp247_body_classes_on_if_true( $sbg_isnottop )
						, wp247_body_classes_on_if_true( $sbpx ), $sbpx_inc, $sbpx_st, $sbpx_lim, wp247_body_classes_on_if_true( $sbpx_top ), wp247_body_classes_on_if_true( $sbpx_mid ), wp247_body_classes_on_if_true( $sbpx_n ), wp247_body_classes_on_if_true( $sbpx_max )
						, wp247_body_classes_on_if_true( $sbvh ), $sbvh_inc, $sbvh_st, $sbvh_lim, wp247_body_classes_on_if_true( $sbvh_top ), wp247_body_classes_on_if_true( $sbvh_mid ), wp247_body_classes_on_if_true( $sbvh_n ), wp247_body_classes_on_if_true( $sbvh_max )
						, wp247_body_classes_on_if_true( $sbdh ), $sbdh_inc, $sbdh_st, $sbdh_lim, wp247_body_classes_on_if_true( $sbdh_top ), wp247_body_classes_on_if_true( $sbdh_mid ), wp247_body_classes_on_if_true( $sbdh_n ), wp247_body_classes_on_if_true( $sbdh_max )
						);
			echo "\n<script type='text/javascript' src='".plugins_url( 'js/wp247-body-classes-scroll-manager.js', __FILE__ )."'></script>\n";
		}
	}

	function wp247_body_classes_do_action_body_class( $classes )
	{
		global $post;
		global $wp247_body_classes_debug;
		global $wp247_mobile_detect;

		$option_groups = array(
			'wp247_body_classes_mobile'			=> false,
			'wp247_body_classes_environment'	=> false,
			'wp247_body_classes_user'			=> false,
			'wp247_body_classes_archive'		=> false,
			'wp247_body_classes_post'			=> false,
			'wp247_body_classes_scroll'			=> false,
			);
		$options = array();
		foreach( $option_groups as $optname => $optvalue )
		{
			$option = get_option( $optname );
			if ( 'wp247_body_classes_mobile' == $optname )
			{
				wp247_body_classes_set_mobile_detect_version( $option );
				if ( isset( $option[ 'mobile-detect-version' ] ) )
					unset( $option[ 'mobile-detect-version' ] );
			}
			if ( is_array( $option ) and !empty( $option ) ) {
				$options = array_merge( $options, $option );
			}
			$option_groups[ $optname ] = !empty( $option );
		}

		$class_driver = array();

		// Environment Classes
		if ( $option_groups [ 'wp247_body_classes_environment' ] ) {
			$class_driver = array_merge( $class_driver
										,array(
											  array( 'wp-mobile', wp_is_mobile() )
											, array( 'home', is_home() )
											, array( 'front-page', is_front_page() )
											, array( 'blog', ( is_front_page() and is_home() ) )
											, array( 'admin', ( is_admin() or is_super_admin() ) )
											, array( 'admin-bar-showing', is_admin_bar_showing() )
											, array( '404', is_404() )
										)
									);
		}	// End of environment body classes

		// User Classes
		if ( $option_groups [ 'wp247_body_classes_user' ] ) {

			$user = wp_get_current_user();
			$user_extra   = array( 'slug' => $user->user_nicename, 'id' => $user->ID );

			if ( isset( $options[ 'indv-user-roles' ] ) ) {
				$user_roles = array_flip( $user->roles );
				$set_roles = array();
				foreach ( $options[ 'indv-user-roles' ] as $role ) {
					$class = sanitize_title( strtolower( preg_replace( '/^is-(not-)?role-/i', '', $role ) ) );
					$options[ 'role-' . $class ][ $role ] = $role;
					if ( !in_array( $class, $set_roles ) ) {
						$set_roles[] = $class;
						$rolecap_options[] = array( 'role-' . $class, isset( $user_roles[ $class ] ) );
					}
				}
			}

			if ( isset( $options[ 'indv-user-caps' ] ) ) {
				$set_caps = array();
				foreach ( $options[ 'indv-user-caps' ] as $cap ) {
					$class = sanitize_title( strtolower( preg_replace( '/^is-(not-)?cap-/i', '', $cap ) ) );
					$options[ 'cap-' . $class ][ $cap ] = $cap;
					if ( !in_array( $class, $set_caps ) ) {
						$set_caps[] = $class;
						$rolecap_options[] = array( 'cap-' . $class, isset( $user->allcaps[ $class ] ) );
					}
				}
			}

			$class_driver = array_merge( $class_driver
										,array(
											  array( 'super-admin', is_super_admin() )
											, array( 'user-logged-in', is_user_logged_in(), $user_extra, 'user' )
										)
										,$rolecap_options
									);

			if ( isset( $options[ 'all-user-roles' ] ) ) {
				foreach ( $user->roles as $r ) $classes[] = 'is-userrole-' . sanitize_title( $r );
			}
		
			if ( isset( $options[ 'all-user-caps' ] ) ) {
				foreach ( $user->allcaps as $c => $v ) $classes[] = 'is-usercap-' . sanitize_title( $c );
			}

		}	// End of user body classes

		// Archive Classes
		if ( $option_groups [ 'wp247_body_classes_archive' ] ) {
		
			if ( have_posts() )
			{
				$date_extra		= array( 'year-month-day' => get_the_date( 'Y-m-d' ), 'year-month' => get_the_date( 'Y-m' ), 'year' => 'is-year-' . get_the_date( 'Y' ), 'month' => 'is-month-' . get_the_date( 'm' ), 'day' => 'is-day-' . get_the_date( 'd' ) );
				$year_extra		= array( 'year' => get_the_date( 'Y' ) );
				$month_extra	= array( 'month' => get_the_date( 'm' ) );
				$day_extra		= array( 'day' => get_the_date( 'd' ) );
				$time_extra		= array( 'year-month-day-time' => get_the_time( 'Y-m-d-G-i-s' ), 'year-month-day' => 'is-date-' . get_the_time( 'Y-m-d' ), 'year-month' => 'is-date-' . get_the_time( 'Y-m' ), 'year' => 'is-year-' . get_the_date( 'Y' ), 'month' => 'is-month-' . get_the_date( 'm' ), 'day' => 'is-day-' . get_the_date( 'd' ) );
			}
			else $date_extra = $month_extra = $day_extra = $time_extra = $year_extra = NULL;

			$author_extra	= array();
			$category_extra = array();
			$tag_extra = array();
			$tax_extra = array();

			$query = get_queried_object();

			if ( !is_null( $query ) )
			{
				if ( is_author() )
				{
					$author_extra[ 'slug' ]	= $query->user_nicename;
					$author_extra[ 'id' ]	= $query->ID;
				}
				if ( is_category() )
				{
					if ( isset( $options[ 'category' ][ 'category-slug' ] ) )
						$category_extra[ 'slug' ] = $query->slug;
					if ( isset( $options[ 'category' ][ 'category-id' ] ) )
						$category_extra[ 'id' ] = $query->cat_ID;
				}
				if ( is_tag() )
				{
					if ( isset( $options[ 'tag' ][ 'tag-slug'] ) )
						$tag_extra[ 'slug' ] = $query->slug;
					if ( isset( $options[ 'tag' ][ 'tag-id'] ) )
						$tag_extra[ 'id' ] = $query->term_taxonomy_id;
				}
				if ( is_tax() )
				{
					if ( isset( $options[ 'tax' ][ 'tax-slug' ] ) )
						$tax_extra[ 'slug' ] = $query->slug;
					if ( isset( $options[ 'tax' ][ 'tax-id' ] ) )
						$tax_extra[ 'id' ] = $query->term_taxonomy_id;
				}
			}

			$class_driver = array_merge( $class_driver
										,array(
											  array( 'archive', is_archive() )
											, array( 'search', is_search() )
											, array( 'single', is_single() )
											, array( 'sticky', is_sticky() )
											, array( 'author', is_author(), $author_extra )
											, array( 'category', is_category(), $category_extra )
											, array( 'tag', is_tag(), $tag_extra )
											, array( 'tax', is_tax(), $tax_extra )
											, array( 'date', is_date(), $date_extra )
											, array( 'year', is_year(), $year_extra )
											, array( 'month', is_month(), $month_extra )
											, array( 'day', is_day(), $day_extra )
											, array( 'time', is_time(), $time_extra )
										)
									);
		}	// End of archive body classes

		// Post Classes
		if ( $option_groups [ 'wp247_body_classes_post' ] ) {
			if ( is_singular() and isset( $post ) )
			{
				$post_type = $post->post_type;
				$post_name = $post->post_name;
				$post_id   = $post->ID;
				
				// If Page or Post, do Categories and Tags if required
				if ( 'page' == $post_type or 'post' == $post_type ) {
					if ( isset( $options [ $post_type ][ $post_type.'cat-slug' ] )
					  or isset( $options [ $post_type ][ $post_type.'cat-id' ] )
					) {
						$postcats = wp_get_post_categories( $post_id, array( 'fields' => 'all' ) );
						if ( is_array( $postcats ) )
						{
							foreach( $postcats as $cat )
							{
								if ( isset( $options [ $post_type ][ $post_type.'cat-slug' ] ) )
									$classes[] = 'is-'.$post_type.'cat-'.$cat->slug;
								if ( isset( $options [ $post_type ][ $post_type.'cat-id' ] ) )
									$classes[] = 'is-'.$post_type.'cat-'.$cat->term_id;
							}
						}
					}
					if ( isset( $options [ $post_type ][ $post_type.'tag-slug' ] ) ) {
						$posttags = wp_get_post_tags( $post_id );
						if ( is_array( $posttags ) )
						{
							foreach( $posttags as $tag )
							{
								if ( isset( $options [ $post_type ][ $post_type.'tag-slug' ] ) )
									$classes[] = 'is-'.$post_type.'tag-'.$tag->slug;
								if ( isset( $options [ $post_type ][ $post_type.'tag-id' ] ) )
									$classes[] = 'is-'.$post_type.'tag-'.$tag->term_id;
							}
						}
					}
				}
			}
			else $post_type = $post_name = $post_id = '';
			$post_types = get_post_types();
			foreach ( $post_types as $pt )
			{
				$class_driver[] = array( str_replace( '_', '-', $pt ), $pt == $post_type, array( 'slug' => $post_name, 'id' => $post_id ) );
			}
		}	// End of post body classes

		// Mobile Device Class Drivers ( only if mobile body classes are requested )
		if ( $option_groups [ 'wp247_body_classes_mobile' ] ) {

			if ( is_null( $wp247_mobile_detect ) )	// Load Mobile_Detect if not already loaded
				wp247_body_classes_load_mobile_detect();

			$mobile_driver_set = array();
			$mobile_classes = array();
			foreach ( array( 'device', 'os', 'browser', 'phone', 'tablet' ) as $type )
			{
				if ( isset( $options[ $type ] ) and is_array( $options[ $type ] ) ) $mobile_classes = array_merge( $mobile_classes, $options[ $type ] );
			}
			foreach ( $mobile_classes as $class => $test )
			{
				$category = preg_replace( '/^is-(not-)?/i', '', $class );
				if ( !isset( $options[ $category ] ) ) $options[ $category ] = array( $class => $class );
				else $options[ $category ][ $class ] = $class;
				if ( !isset( $mobile_driver_set[ $category ] ) )
				{
					if ( 'mobile' == $category ) $class_driver[] = array( 'mobile', $wp247_mobile_detect->isMobile() );
					elseif ( 'tablet' == $category ) $class_driver[] = array( 'tablet', $wp247_mobile_detect->isTablet() );
					elseif ( 'phone' == $category ) $class_driver[] = array( 'phone', ( $wp247_mobile_detect->isMobile() and !$wp247_mobile_detect->isTablet() ) );
					else $class_driver[] = array( $category, $wp247_mobile_detect->is( $test ) );
					$mobile_driver_set[ $category ] = $category;
				}
			}

			$wp247_mobile_detect = null;	// No longer need Mobile_Detect
		}	// End of mobile body classes


		foreach ( $class_driver as $cd )
		{
			$option = $cd[ 0 ];
			$is_value = $cd[ 1 ];
			$is_extras = ( isset( $cd[ 2 ] ) ? $cd[ 2 ] : array() );
			$is_extra_base = ( isset( $cd[ 3 ] ) ? $cd[ 3 ] : $option );
			
			if ( !is_array( $is_extras ) ) $is_extras = array( $is_extras );

			if ( $is_value )
			{
				if ( isset( $options[ $option ][ 'is-' . $option ] ) ) $classes[] = 'is-' . $option;
				foreach( $is_extras as $key => $value )
				{
					if ( !is_null( $value ) and isset( $options[ $option ][ $is_extra_base.'-'.$key ] ) )
					{
						if ( 'is-' == substr( $value, 0, 3 ) ) $classes[] = $value;
						else $classes[] = 'is-' . $is_extra_base . '-' . $value;
					}
				}
			}
			elseif ( isset( $options[ $option ][ 'is-not-' . $option ] ) ) $classes[] = 'is-not-' . $option;
		}

		// Get POST Body Classes
		if ( is_singular() and isset( $post ) )
		{
			$post_meta = get_post_meta( $post->ID, WP247_BODY_CLASSES_POST_META_KEY, true );
			if ( !empty( $post_meta ) ) $classes = array_merge( $classes, explode( ' ', preg_replace( '/\s+/', ' ', $post_meta ) ) );
		}

		$custom = get_option( 'wp247_body_classes_custom' );
		if ( isset( $custom[ 'custom-classes' ] ) and !empty( $custom[ 'custom-classes' ] ) ) @eval( $custom[ 'custom-classes' ] );

		return $classes;
	}

	// Dump debug array into footer
	function wp247_body_classes_do_action_wp_footer()
	{
		global $wp247_body_classes_debug;
		if ( !empty( $wp247_body_classes_debug ) ) {
			echo "\n\n<!-- -------------------- wp247_body_classes_debug --------------------\n";
			var_dump( $wp247_body_classes_debug );
			echo "\n\n -------------------- wp247_body_classes_debug -------------------- -->\n\n";
		}
	}

	/*
	 * Get / Set Mobile-Detect Version
	 */
	function wp247_body_classes_get_mobile_detect_version()
	{
		return wp247_body_classes_set_mobile_detect_version();
	}
	function wp247_body_classes_set_mobile_detect_version( $option = null )
	{
		if ( !defined( 'WP247_BODY_CLASSES_MOBILE_DETECT_VERSION' ) )
		{

			if ( is_null( $option ) or !is_array( $option ) )
			{
				$option = get_option( 'wp247_body_classes_mobile', array() );
				if ( !is_array( $option ) ) $option = array( $option );
			}

			if ( !isset( $option[ 'mobile-detect-version' ] ) or !file_exists( WP247_BODY_CLASSES_PLUGIN_PATH . $option[ 'mobile-detect-version' ] . '/Mobile_Detect.php' ) )
			{
				$mdvers = wp247_body_classes_get_available_mobile_detect_versions();
				$option[ 'mobile-detect-version' ] = current( array_keys( $mdvers ) );	// Get first entry (lowest version)
				update_option( 'wp247_body_classes_mobile', $option );
			}

			define( 'WP247_BODY_CLASSES_MOBILE_DETECT_VERSION', $option[ 'mobile-detect-version' ] );
		}

		return WP247_BODY_CLASSES_MOBILE_DETECT_VERSION;
	}
	function wp247_body_classes_get_available_mobile_detect_versions()
	{

		$files = scandir( plugin_dir_path( __FILE__ ) );
		$mdvers = array();
		foreach ( $files as $file )
		{
			if ( 'mobile-detect-' == substr( $file, 0, 14 ) )
				$mdvers[ $file ] = 'Mobile-Detect ' . substr( $file, 14 );
		}
		return $mdvers;
	}

	/*
	 * Defer loading Mobile_Detect until required
	 */
	function wp247_body_classes_load_mobile_detect()
	{
		require_once WP247_BODY_CLASSES_PLUGIN_PATH . wp247_body_classes_get_mobile_detect_version() . '/Mobile_Detect.php';
		global $wp247_mobile_detect;
		$wp247_mobile_detect = new WP247_Mobile_Detect();
	}

	function wp247_body_classes_default_if_empty( $value, $default )
	{
		return empty( $value ) ? $default : $value;
	}

	function wp247_body_classes_number_default_if_empty( $value, $default )
	{
		return ( empty( $value ) or !is_numeric( $value ) ) ? $default : $value;
	}

	function wp247_body_classes_on_if_not_empty( $value )
	{
		return empty( $value ) ? $value : 'on';
	}

	function wp247_body_classes_on_if_true( $value )
	{
		return $value == true ? 'on' : '';
	}

}
