<?php
// Don't allow direct execution
defined( 'ABSPATH' ) or die ( 'Forbidden' );

function wp247_body_classes_admin_fields() {
	global $wp247_mobile_detect;

	if ( is_null( $wp247_mobile_detect ) )	// Load Mobile_Detect if not already loaded
		wp247_body_classes_load_mobile_detect();

	$md_options = array( 'os' => array_keys( $wp247_mobile_detect->getOperatingSystems() )
						,'browser' => array_keys( $wp247_mobile_detect->getBrowsers() )
						,'phone' => array_keys( $wp247_mobile_detect->getPhoneDevices() )
						,'tablet' => array_keys( $wp247_mobile_detect->getTabletDevices() )
//						,'utility' => array_keys( $wp247_mobile_detect->getUtilities() )
						);

	$wp247_mobile_detect = null;	// No longer need Mobile_Detect

	foreach ( $md_options as $key => $vals )
	{
		$opt = array();
		$sufx = $key;
		$sufxlen = strlen( $sufx );
		natcasesort( $vals );
		foreach ( $vals as $val )
		{
			$class = strtolower( $val );
			if ( $sufxlen > 0 and $sufx != substr( $class, $sufxlen * -1 ) ) $class = $class . $sufx;
			$opt[ 'is-' . $class . '/' . $val ] = '<span class="wp247sapi">is-' . $class . '</span><span>mobile_detect->is("'.$val.'")</span>';
			$opt[ 'is-not-' . $class . '/' . $val ] = '<span class="wp247sapi-indent">is-not-' . $class . '</span>';
		}
		$md_options[ $key ] = $opt;
	}

	$mdvers = wp247_body_classes_get_available_mobile_detect_versions();

	$user = wp_get_current_user();

	global $wp_roles;
	$role_options = array();
    foreach ( $wp_roles->roles as $key => $role ) {
		$name = $key;
		$class = sanitize_title( strtolower( $name ) );
		$role_options[ 'is-role-' . $class . '/' . 'is-role-' . $class ] = '<span class="wp247sapi">is-role-' . $class . '</span>';
		$role_options[ 'is-not-role-' . $class . '/' . 'is-not-role-' . $class ] = '<span class="wp247sapi-indent">is-not-role-' . $class . '</span>';
	}

	$cap_options = array();
	$all_caps = array_keys( $user->allcaps );
	sort( $all_caps );
    foreach ( $all_caps as $cap ) {
		$class = sanitize_title( strtolower( $cap ) );
		$cap_options[ 'is-cap-' . $class . '/' . 'is-cap-' . $class ] = '<span class="wp247sapi">is-cap-' . $class . '</span>';
		$cap_options[ 'is-not-cap-' . $class . '/' . 'is-not-cap-' . $class ] = '<span class="wp247sapi-indent">is-not-cap-' . $class . '</span>';
	}

	$cats = get_categories();
	if ( isset( $cats[0] ) )
	{
		$cat_slug = $cats[0]->slug;
		$cat_ID   = $cats[0]->cat_ID;
	}
	else { $cat_slug = $cat_ID = '?'; }
/*
	$tags = get_tags();
	$tag  = isset( $tags[0] ) ? $tags[0] : '';
	$taxs = get_taxonomies();
	$tax  = isset( $taxs[0] ) ? $taxs[0] : '';
*/
	$settings_fields = array(
		'wp247_body_classes_mobile' => array(
			array(
				'name' => 'mobile-detect-version',
				'label' => __( 'Mobile-Detect Version', 'wp247-body-classes' ),
				'desc' => __( 'The Mobile-Detect version to be used.', 'wp247-body-classes' ),
				'type' => 'select',
				'options' => $mdvers,
			),
			array(
				'name' => 'device',
				'label' => __( 'Device', 'wp247-body-classes' ),
				'intro' => __( 'These classes indicate if the user is visiting from a mobile device, and if so, which type of mobile device.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-mobile' => '<span class="wp247sapi">is-mobile</span><span>mobile_detect->isMobile()</span>',
					'is-not-mobile' => '<span class="wp247sapi-indent">is-not-mobile</span>',
					'is-phone' => '<span class="wp247sapi">is-phone</span><span>mobile_detect->isMobile() and !mobile_detect->isTablet()</span>',
					'is-not-phone' => '<span class="wp247sapi-indent">is-not-phone</span>',
					'is-tablet' => '<span class="wp247sapi">is-tablet</span><span>mobile_detect->isTablet()</span>',
					'is-not-tablet' => '<span class="wp247sapi-indent">is-not-tablet</span>',
				),
			),
			array(
				'name' => 'os',
				'label' => __( 'Operating System', 'wp247-body-classes' ),
				'intro' => __( 'These classes indicate the Operating System of the mobile device the user is visiting from.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => $md_options[ 'os' ],
			),
			array(
				'name' => 'browser',
				'label' => __( 'Browser', 'wp247-body-classes' ),
				'intro' => __( 'These classes indicate the browser of the mobile device the user is visiting from.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => $md_options[ 'browser' ],
			),
			array(
				'name' => 'tablet',
				'label' => __( 'Tablet', 'wp247-body-classes' ),
				'intro' => __( 'These classes indicate which type of tablet is the mobile device the user is visiting from.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => $md_options[ 'tablet' ],
			),
			array(
				'name' => 'phone',
				'label' => __( 'Phone', 'wp247-body-classes' ),
				'intro' => __( 'These classes indicate which type of phone is the mobile device the user is visiting from.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => $md_options[ 'phone' ],
			),
/*
			array(
				'name' => 'util',
				'label' => __( 'Utility', 'wp247-body-classes' ),
				'intro' => __( 'These classes indicate which utility functions are available on the mobile device the user is visiting from.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => $md_options[ 'util' ],
			),
*/
		),
		'wp247_body_classes_environment' => array(
			array(
				'name' => 'wp-mobile',
				'label' => __( 'Mobile', 'wp247-body-classes' ),
				'intro' => __( '<b>wp_is_mobile()</b><br />This class indicates if the user is visiting using a mobile device.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-wp-mobile' => 'is-wp-mobile',
					'is-not-wp-mobile' => 'is-not-wp-mobile',
				),
			),
			array(
				'name' => 'home',
				'label' => __( 'Home', 'wp247-body-classes' ),
				'intro' => __( '<b>is_home()</b><br />This class indicates if the blog posts index page is being displayed.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-home' => 'is-home',
					'is-not-home' => 'is-not-home',
				),
			),
			array(
				'name' => 'front-page',
				'label' => __( 'Front Page', 'wp247-body-classes' ),
				'intro' => __( '<b>is_front_page()</b><br />This class indicates if the main page is a posts or a Page. It returns TRUE (is-front-page) when the main blog page is being displayed and the Settings->Reading->Front page displays is set to "Your latest posts", or when is set to "A static page" and the "Front Page" value is the current Page being displayed.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-front-page' => 'is-front-page',
					'is-not-front-page' => 'is-not-front-page',
				),
			),
			array(
				'name' => 'blog',
				'label' => __( 'Blog', 'wp247-body-classes' ),
				'intro' => __( '<b>is_front_page() and is_home()</b>', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-blog' => 'is-blog',
					'is-not-blog' => 'is-not-blog',
				),
			),
			array(
				'name' => 'admin',
				'label' => __( 'Admin', 'wp247-body-classes' ),
				'intro' => __( '<b>is_admin()</b><br/>This class indicates if the Dashboard or the administration panel is attempting to be displayed.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-admin' => 'is-admin',
					'is-not-admin' => 'is-not-admin',
				),
			),
			array(
				'name' => 'admin-bar-showing',
				'label' => __( 'Admin Bar Showing', 'wp247-body-classes' ),
				'intro' => __( '<b>is_admin_bar_showing()</b><br />This calss indicates if the WordPress Toolbar is being displayed.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-admin-bar-showing' => 'is-admin-bar-showing',
					'is-not-admin-bar-showing' => 'is-not-admin-bar-showing',
				),
			),
			array(
				'name' => '404',
				'label' => __( '404', 'wp247-body-classes' ),
				'intro' => __( '<b>is_404()</b><br />This calss indicates if 404 error is being displayed (after an "HTTP 404: Not Found" error occurs).', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-404' => 'is-404',
					'is-not-404' => 'is-not-404',
				),
			),
		),
		'wp247_body_classes_user' => array(
			array(
				'name' => 'super-admin',
				'label' => __( 'Super Admin', 'wp247-body-classes' ),
				'intro' => __( '<b>is_super_admin()</b><br/>This class indicates if user is a network (super) admin. It will also check if the user is admin if network mode is disabled.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-super-admin' => 'is-super-admin',
					'is-not-super-admin' => 'is-not-super-admin',
				),
			),
			array(
				'name' => 'user-logged-in',
				'label' => __( 'User Logged In', 'wp247-body-classes' ),
				'intro' => __( '<b>is_user_logged_in()</b><br/>This class indicates if the current visitor is logged in. If the current visitor is logged in, additional classes that indicate which user is logged in may optionally be included.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-user-logged-in' => 'is-user-logged-in',
					'user-slug' => '<span class="wp247sapi-indent">is-user-(nicename)</span><span>(example: is-user-'.$user->user_nicename.')</span>',
					'user-id' => '<span class="wp247sapi-indent">is-user-(id)</span><span>(example: is-user-'.$user->ID.')</span>',
					'is-not-user-logged-in' => 'is-not-user-logged-in',
				),
			),
			array(
				'name' => 'all-user-roles',
				'label' => __( 'All User Roles', 'wp247-body-classes' ),
				'intro' => __( '<b>$user->roles</b><br/>This class identifies all roles that are assigned to the currently logged-in user.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'all-user-roles' => '<span class="wp247sapi">is-userrole-(roleslug)</span><span>(example: is-userrole-'.$user->roles[0].')</span>',
				),
			),
			array(
				'name' => 'indv-user-roles',
				'label' => __( 'Individual User Roles', 'wp247-body-classes' ),
				'intro' => __( '<b>$user->roles</b><br/>This class identifies individual roles that are or are not assigned to the currently logged-in user.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => $role_options,
			),
			array(
				'name' => 'all-user-caps',
				'label' => __( 'All User Capabilities', 'wp247-body-classes' ),
				'intro' => __( '<b>$user->allcaps</b><br/>This class identifies all capabilities that are assigned to the currently logged-in user.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'all-user-caps' => '<span class="wp247sapi">is-usercap-(capslug)</span><span>(example: is-usercap-'.array_keys( $user->allcaps )[0].')</span>',
				),
			),
			array(
				'name' => 'indv-user-caps',
				'label' => __( 'Individual User Capabilities', 'wp247-body-classes' ),
				'intro' => __( '<b>$user->allcaps</b><br/>This class identifies which capabilities are or are not assigned to the currently logged-in user.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => $cap_options,
			),
		),
		'wp247_body_classes_archive' => array(
			array(
				'name' => 'search',
				'label' => __( 'Search', 'wp247-body-classes' ),
				'intro' => __( '<b>is_search()</b><br/>This class indicates if a search result page archive is being displayed.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-search' => 'is-search',
					'is-not-search' => 'is-not-search',
				),
			),
			array(
				'name' => 'archive',
				'label' => __( 'Archive', 'wp247-body-classes' ),
				'intro' => __( '<b>is_archive()</b><br/>This class indicates if any type of Archive page is being displayed. An Archive is a Category, Tag, Author or Date based pages.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-archive' => 'is-archive',
					'is-not-archive' => 'is-not-archive',
				),
			),
			array(
				'name' => 'author',
				'label' => __( 'Author', 'wp247-body-classes' ),
				'intro' => __( '<b>is_author()</b><br/>This class indicates if an Author archive page is being displayed. If an Author archive page is being displayed, additional classes that indicate which author is being displayed may optionally be included.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-author' => 'is-author',
					'author-slug' => '<span class="wp247sapi-indent">is-author-(nicename)</span><span>(example: is-author-'.$user->user_nicename.')</span>',
					'author-id' => '<span class="wp247sapi-indent">is-author-(id)</span><span>(example: is-author-'.$user->ID.')</span>',
					'is-not-author' => 'is-not-author',
				),
			),
			array(
				'name' => 'category',
				'label' => __( 'Category', 'wp247-body-classes' ),
				'intro' => __( '<b>is_category()</b><br/>This class indicates if a Category archive page is being displayed. If a Category archive page is being displayed, additional classes that indicate which category is being displayed may optionally be included.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-category' => 'is-category',
					'category-slug' => '<span class="wp247sapi-indent">is-category-(slug)</span><span>(example: is-category-'.$cat_slug.')</span>',
					'category-id' => '<span class="wp247sapi-indent">is-category-(id)</span><span>(example: is-category-'.$cat_ID.')</span>',
					'is-not-category' => 'is-not-category',
				),
			),
			array(
				'name' => 'tag',
				'label' => __( 'Tag', 'wp247-body-classes' ),
				'intro' => __( '<b>is_tag()</b><br/>This class indicates if a Tag archive page is being displayed. If a Tag archive page is being displayed, additional classes that indicate which tag is being displayed may optionally be included.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-tag' => 'is-tag',
					'tag-slug' => '<span class="wp247sapi-indent">is-tag-(slug)</span><span>(example: is-tag-xyz</span>',
					'tag-id' => '<span class="wp247sapi-indent">is-tag-(id)</span><span>(example: is-tag-123)</span>',
					'is-not-tag' => 'is-not-tag',
				),
			),
			array(
				'name' => 'tax',
				'label' => __( 'Taxonomy', 'wp247-body-classes' ),
				'intro' => __( '<b>is_tax()</b><br/>This class indicates if a custom taxonomy archive page is being displayed.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-tax' => 'is-tax',
					'tax-slug' => '<span class="wp247sapi-indent">is-tax-(slug)</span><span>(example: is-tax-xyz)</span>',
					'tax-id' => '<span class="wp247sapi-indent">is-tax-(id)</span><span>(example: is-tax-123)</span>',
					'is-not-tax' => 'is-not-tax',
				),
			),
			array(
				'name' => 'date',
				'label' => __( 'Date', 'wp247-body-classes' ),
				'intro' => __( '<b>is_date()</b><br/>This class indicates if the page is a Date based archive page. If a Date archive page is being displayed, additional classes that indicate which Date is being displayed may optionally be included.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-date' => 'is-date',
					'date-year-month-day' => '<span class="wp247sapi-indent">is-date-(year-month-day)</span><span>(example: is-date-'.date('Y-m-d').')</span>',
					'date-year-month' => '<span class="wp247sapi-indent">is-date-(year-month)</span><span>(example: is-date-'.date('Y-m').')</span>',
					'date-year' => '<span class="wp247sapi-indent">is-year-(year)</span><span>(example: is-year-'.date('Y').')</span>',
					'date-month' => '<span class="wp247sapi-indent">is-month-(month)</span><span>(example: is-month-'.date('m').')</span>',
					'date-day' => '<span class="wp247sapi-indent">is-day-(day)</span><span>(example: is-day-'.date('d').')</span>',
					'is-not-date' => 'is-not-date',
				),
			),
			array(
				'name' => 'year',
				'label' => __( 'Year', 'wp247-body-classes' ),
				'intro' => __( '<b>is_year()</b><br/>This class indicates if the page is a Year based archive page. If a Year archive page is being displayed, additional classes that indicate which year is being displayed may optionally be included.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-year' => 'is-year',
					'year-year' => '<span class="wp247sapi-indent">is-year-(year)</span><span>(example: is-year-'.date('Y').')</span>',
					'is-not-year' => 'is-not-year',
				),
			),
			array(
				'name' => 'month',
				'label' => __( 'Month', 'wp247-body-classes' ),
				'intro' => __( '<b>is_month()</b><br/>This class indicates if the page is a Month based archive page. If a Month archive page is being displayed, additional classes that indicate which month is being displayed may optionally be included.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-month' => 'is-month',
					'month-month' => '<span class="wp247sapi-indent">is-month-(month)</span><span>(example: is-month-'.date('m').')</span>',
					'is-not-month' => 'is-not-month',
				),
			),
			array(
				'name' => 'day',
				'label' => __( 'Day', 'wp247-body-classes' ),
				'intro' => __( '<b>is_day()</b><br/>This class indicates if the page is a Day based archive page. If a Day archive page is being displayed, additional classes that indicate which day is being displayed may optionally be included.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-day' => 'is-day',
					'day-day' => '<span class="wp247sapi-indent">is-day-(day)</span><span>(example: is-day-'.date('d').')</span>',
					'is-not-day' => 'is-not-day',
				),
			),
			array(
				'name' => 'time',
				'label' => __( 'Time', 'wp247-body-classes' ),
				'intro' => __( '<b>is_time()</b><br/>This class indicates if the page is a Time based archive page. If a Time archive page is being displayed, additional classes that indicate which time is being displayed may optionally be included.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-time' => 'is-time',
					'time-year-month-day-time' => '<span class="wp247sapi-indent">is-time-(date-time)</span><span>(example: is-time-'.date('Y-m-d-G-i-s').')</span>',
					'time-year-month-day' => '<span class="wp247sapi-indent">is-date-(year-month-day)</span><span>(example: is-date-'.date('Y-m-d').')</span>',
					'time-year-month' => '<span class="wp247sapi-indent">is-date-(year-month)</span><span>(example: is-date-'.date('Y-m').')</span>',
					'time-year' => '<span class="wp247sapi-indent">is-year-(year)</span><span>(example: is-year-'.date('Y').')</span>',
					'time-month' => '<span class="wp247sapi-indent">is-month-(month)</span><span>(example: is-month-'.date('m').')</span>',
					'time-day' => '<span class="wp247sapi-indent">is-day-(day)</span><span>(example: is-day-'.date('d').')</span>',
					'time-time' => '<span class="wp247sapi-indent">is-time-(time)</span><span>(example: is-time-'.date('G-i-s').')</span>',
					'is-not-time' => 'is-not-time',
				),
			),
		),
		'wp247_body_classes_post' => array(
			array(
				'name' => 'single',
				'label' => __( 'Single', 'wp247-body-classes' ),
				'intro' => __( '<b>is_single()</b><br/>This class indicates if a single post of any post type (except attachment and page post types) is being displayed.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-single' => 'is-single',
					'is-not-single' => 'is-not-single',
				),
			),
			array(
				'name' => 'sticky',
				'label' => __( 'Sticky', 'wp247-body-classes' ),
				'intro' => __( '<b>is_sticky()</b><br/>This class indicates if the current post is a Sticky Post meaning the "Stick this post to the front page" check box has been checked for the post.', 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => array(
					'is-sticky' => 'is-sticky',
					'is-not-sticky' => 'is-not-sticky',
				),
			),
		),
		'wp247_body_classes_scroll' => array(
			array(
				'name' => 'scroll-general',
				'label' => __( 'General Scrolling', 'wp247-body-classes' ),
				'intro' => __( 'General scrolling options.' ),
				'type' => 'multicheck',
				'options' => array(
					'is-scroll-top' => 'is-scroll-top',
					'is-not-scroll-top' => 'is-not-scroll-top',
					'is-scroll' => 'is-scroll (synonym for is-not-scroll-top)',
					'is-not-scroll' => 'is-not-scroll (synonym for is-scroll-top)',
				),
			),
			array(
				'name' => 'scroll-by-pixel',
				'label' => __( 'Scroll by Pixels', 'wp247-body-classes' ),
				'intro' => __( 'Select which <b>Scroll by Pixels</b> Body Classes you want to be set.' ),
				'type' => 'multicheck',
				'options' => array(
					'is-scroll-top-px' => 'is-scroll-top-px',
					'is-scroll-mid-px' => 'is-scroll-mid-px',
					'is-scroll-n-px' => 'is-scroll-##-px',
					'is-scroll-max-px' => 'is-scroll-max-px',
				),
			),
			array(
				'name' => 'scroll-by-pixel-increment',
				'desc' => __( 'Scroll Increment (in pixels)' ),
				'intro' => __( 'Identifies the increment value when Scroll by Pixels is activaed. Example: a value of "5" would result in is-scroll-5-px, is-scroll-10-px, is-scroll-15-px, etc. being set as the user scrolls down the page until is-scroll-max-px is reached.' ),
				'type' => 'text',
				'size' => 'small',
				'default' => '5',
			),
			array(
				'name' => 'scroll-by-pixel-start',
				'desc' => __( 'Scroll Start (in pixels)' ),
				'intro' => __( 'Identifies the start (mainimum value) point (in pixels) when Scroll by Pixels no longer reports is-scroll-top-px. Leave empty for no starting value (i.e. starts at Scroll Increment value). Example: a value of "20" would result in is-scroll-min-px and is-scroll-##-px being set when the viewer has scrolled 20 or more pixels down the page.' ),
				'type' => 'text',
				'size' => 'small',
			),
			array(
				'name' => 'scroll-by-pixel-limit',
				'desc' => __( 'Scroll Limit (in pixels)' ),
				'intro' => __( 'Identifies the limit (maximum value) to be measured when Scroll by Pixels is activated. Leave empty for no limit. Example: a value of "50" would result in is-scroll-max-px being set when the viewer has scrolled 50 or more pixels down the page.' ),
				'type' => 'text',
				'size' => 'small',
			),
			array(
				'name' => 'scroll-by-viewport',
				'label' => __( 'Scroll by Viewport Height', 'wp247-body-classes' ),
				'intro' => __( 'Select which <b>Scroll by Viewport Height</b> Body Classes you want to be set.' ),
				'type' => 'multicheck',
				'options' => array(
					'is-scroll-top-vh' => 'is-scroll-top-vh',
					'is-scroll-mid-vh' => 'is-scroll-mid-vh',
					'is-scroll-n-vh' => 'is-scroll-##-vh',
					'is-scroll-max-vh' => 'is-scroll-max-vh',
				),
			),
			array(
				'name' => 'scroll-by-viewport-increment',
				'desc' => __( 'Scroll Increment (in percentage of viewport height)' ),
				'intro' => __( 'Identifies the increment value when Scroll by Viewport Height is activaed. Example: a value of "5" would result in is-scroll-5-vh, is-scroll-10-vh, is-scroll-15-vh, etc. being set as the user scrolls down the page until is-scroll-max-vh is reached.' ),
				'type' => 'text',
				'size' => 'small',
				'default' => '5',
			),
			array(
				'name' => 'scroll-by-viewport-start',
				'desc' => __( 'Scroll Start (in percent of Viewport Height)' ),
				'intro' => __( 'Identifies the start (mainimum value) point (in percent of Viewport Height) when Scroll by Viewport Height no longer reports is-scroll-top-vh. Leave empty for no starting value (i.e. starts at Scroll Increment value). Example: a value of "20" would result in is-scroll-min-vh and is-scroll-##-vh being set when the viewer has scrolled 20% or more of the Viewport Height down the page.' ),
				'type' => 'text',
				'size' => 'small',
			),
			array(
				'name' => 'scroll-by-viewport-limit',
				'desc' => __( 'Scroll Limit (in percentage of viewport height) - should be blank or an integer greater than 0.' ),
				'intro' => __( 'Identifies the limit (maximum value) to be measured when Scroll by Viewport Height is activated. Leave empty for no limit. Example: a value of "50" would result in is-scroll-max-vh being set when the viewer has scrolled 50 or more percentage of the Viewport Height down the page.' ),
				'type' => 'text',
				'size' => 'small',
			),
			array(
				'name' => 'scroll-by-document',
				'label' => __( 'Scroll by Document Height', 'wp247-body-classes' ),
				'intro' => __( 'Select which <b>Scroll by Document Height</b> Body Classes you want to be set.' ),
				'type' => 'multicheck',
				'options' => array(
					'is-scroll-top-dh' => 'is-scroll-top-dh',
					'is-scroll-mid-dh' => 'is-scroll-mid-dh',
					'is-scroll-n-dh' => 'is-scroll-##-dh',
					'is-scroll-max-dh' => 'is-scroll-max-dh',
				),
			),
			array(
				'name' => 'scroll-by-document-increment',
				'desc' => __( 'Scroll Increment (in percentage of document height)' ),
				'intro' => __( 'Identifies the increment value when Scroll by Document Height is activaed. Example: a value of "5" would result in is-scroll-5-dh, is-scroll-10-dh, is-scroll-15-dh, etc. being set as the user scrolls down the page until is-scroll-max-dh is reached.' ),
				'type' => 'text',
				'size' => 'small',
				'default' => '5',
			),
			array(
				'name' => 'scroll-by-document-start',
				'desc' => __( 'Scroll Start (in percent of Document Height)' ),
				'intro' => __( 'Identifies the start (mainimum value) point (in percent of Document Height) when Scroll by Document Height no longer reports is-scroll-top-dh. Leave empty for no starting value (i.e. starts at Scroll Increment value). Example: a value of "20" would result in is-scroll-min-dh and is-scroll-##-dh being set when the viewer has scrolled 20% or more of the Document Height down the page.' ),
				'type' => 'text',
				'size' => 'small',
			),
			array(
				'name' => 'scroll-by-document-limit',
				'desc' => __( 'Scroll Limit (in percentage of document height) - should be blank or an integer greater than 0 and less than 100.' ),
				'intro' => __( 'Identifies the limit (maximum value) to be measured when Scroll by Document Height is activated. Leave empty for no limit. Example: a value of "50" would result in is-scroll-max-dh being set when the viewer has scrolled 50 or more percentage of the Document Height down the page.' ),
				'type' => 'text',
				'size' => 'small',
			),
		),
		'wp247_body_classes_custom' => array(
			array(
				'name' => 'custom-classes',
				'label' => __( 'Custom Body Classes', 'wp247-body-classes' ),
				'intro' => __( 'Add the PHP code necessary to create your own custom Body Classes by setting any new class you want in the <b>$classes</b> array.<br/><br/>Here\'s an example to get you started. Not sure why we would want to do it, but suppose we want to do some custom styling when the page is being displayed to someone that can manage WordPress options. We might enter something like:<br/><br/><span style="padding-left: 24px; font-style: normal; font-weight: 600;">if ( current_user_can( \'manage_options\' ) ) $classes[] = \'user-can-manage-options\';</span><br/><br/>Then we can use the <b>body.user-can-manage-options</b> qualifier in our CSS styling.<br/>', 'wp247-body-classes' ),
				'desc' => __('Custom Body Classes PHP code.' ),
				'type' => 'textarea',
				'rows' => 12,
				'cols' => 72,
			),
		),
		'wp247_body_classes_css' => array(
			array(
				'name' => 'custom-css',
				'label' => __( 'Custom CSS', 'wp247-body-classes' ),
				'desc' => __( 'Custom CSS that will be included on all web pages.', 'wp247-body-classes' ),
				'type' => 'textarea',
				'rows' => 12,
				'cols' => 72,
			),
		),
	);

	$ptypes = get_post_types();
	foreach ( $ptypes as $pt )
	{
		$ucwpt = ucwords( $pt );
		$cleanpt = str_replace( '_', '-', $pt );
		$xintro = '';
		$opts = array(
						'is-'.$cleanpt		=> 'is-'.$cleanpt,
						'is-not-'.$cleanpt	=> 'is-not-'.$cleanpt,
					);
		if ( 'page' == $pt or 'post' == $pt )
		{
			$xintro = ' If a '.$ucwpt.' post type is being displayed, additional classes that indicate which <b>'.$ucwpt.'</b> post is being displayed may optionally be included.';
			$opts = array(
							'is-'.$pt => 'is-'.$pt,
							$cleanpt.'-slug' => '<span class="wp247sapi-indent">is-'.$cleanpt.'-(slug)</span><span>(example: is-'.$cleanpt.'-sample-'.$cleanpt.')</span>',
							$cleanpt.'-id' => '<span class="wp247sapi-indent">is-'.$cleanpt.'-(id)</span><span>(example: is-'.$cleanpt.'-1)</span>',
							$cleanpt.'cat-slug' => '<span class="wp247sapi-indent">is-'.$cleanpt.'cat-(slug)</span><span>('.$cleanpt.' category slug for each '.$cleanpt.' catagory)</span>',
							$cleanpt.'cat-id' => '<span class="wp247sapi-indent">is-'.$cleanpt.'cat-(id)</span><span>('.$cleanpt.' category id for each '.$cleanpt.' category)</span>',
							$cleanpt.'tag-slug' => '<span class="wp247sapi-indent">is-'.$cleanpt.'tag-(slug)</span><span>('.$cleanpt.' tag slug for each '.$cleanpt.' tag)</span>',
							'is-not-'.$cleanpt => 'is-not-'.$cleanpt,
						);
		}
		$settings_fields[ 'wp247_body_classes_post' ][] = array(
				'name' => $pt,
				'label' => __( $ucwpt, 'wp247-body-classes' ),
				'intro' => __( 'This class indicates if the <b>'.$ucwpt.'</b> post type is being displayed.'.$xintro, 'wp247-body-classes' ),
				'type' => 'multicheck',
				'options' => $opts,
			);
	}

	return $settings_fields;
}
?>