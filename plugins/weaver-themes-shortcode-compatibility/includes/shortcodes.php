<?php
/*
 Weaver II Shortcodes

*/

// same as Weaver II Pro shortcodes - except add_shortcode mapped to just add_shortcode here





// ===============  [weaver_vimeo id=videoid sd=0 w=0 h=0 color=#hex autoplay=0 loop=0 portrait=1 title=1 byline=1] ======================
function wvr_compat_sc_vimeo($args = '') {
    $share = '';
    if ( isset ( $args[0] ) )
        $share = trim($args[0]);

    extract(shortcode_atts(array(
        'id' => '',
        'sd' => false,
        'color' => '',
        'autoplay' => false,
        'loop' => false,
        'portrait' => true,
        'title' => true,
        'byline' => true,
        'w' => 0,
        'h' => 0,
        'ratio' => false,
        'percent' => 100,
        'center' => '1'
    ), $args));

    if (!$share && !$id) return '<strong>No share or id values provided for weaver_vimeo shortcode.</strong>';

    if ( $w == 0 ) {
        $w = $sd ? 640 : 1280;
    }
    if ( $h == 0 ) {
        $h = $sd ? 480 : 720;
    }
    if ( $ratio )
        $h = $w * $ratio;

    $GLOBALS['wvr_videos_count'] = true;


    if ($share)	{	// let the share override any id
        $share = str_replace('http://vimeo.com/','',$share);
        if ($share) $id = $share;
    }

    $opts = $id . '##';

    $opts = wvr_compat_add_url_opt($opts, $autoplay, 'autoplay=1');
    $opts = wvr_compat_add_url_opt($opts, $loop, 'loop=1');
    $opts = wvr_compat_add_url_opt($opts, $color, 'color=' . $color);
    $opts = wvr_compat_add_url_opt($opts, !$portrait, 'portrait=0');
    $opts = wvr_compat_add_url_opt($opts, !$title, 'title=0');
    $opts = wvr_compat_add_url_opt($opts, !$byline, 'byline=0');

    $url = 'http://player.vimeo.com/video/';

    $opts = str_replace('##+','##?', $opts);
    $opts = str_replace('##','', $opts);
    $opts = str_replace('+','&amp;', $opts);

    $url .= $opts;


    // $vert = $sd ? 0.75 : 0.5625;
    // if ($ratio) $vert = $ratio;
    $cntr1 = $center ? '<div class="wvrc-fitvids" style="margin:0 auto;max-width:' . $percent. '%;">'
                     : '<div class="wvrc-fitvids" style="max-width:' . $percent. '%;">';
    $cntr2 = '</div>';

    return "\n" . $cntr1 . '<iframe src="' . $url
     . '" width="'. $w . '" height="' . $h . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>'
     . $cntr2 . "\n";
}
add_shortcode('weaver_vimeo', 'wvr_compat_sc_vimeo');

// ===== video utils =====

function wvr_compat_add_url_opt($opts, $add, $add_val) {
    if ($add) {
        $opts = $opts . '+' . $add_val;
    }
    return $opts;
}



// ===============  [weaver_info] ======================
function wvr_compat_sc_info() {
    global $current_user;
    $out = '<strong>' . WEAVERII_THEMEVERSION . ' Info</strong><hr />';

    get_currentuserinfo();
    if (isset($current_user->display_name)) {
        $out .= '<em>User:</em> ' . $current_user->display_name . '<br />';
    }
    $out .= '&nbsp;&nbsp;' . wp_register('','<br />',false);
    $out .= '&nbsp;&nbsp;' . wp_loginout('',false) . '<br />';

    global $wvr_compat_mobile;
    $device = $wvr_compat_mobile;
    $out .= '<em>Browser:</em> ' . ($device ? $device['browser'] . '/' . $device['type'] .'/'.$device['os'] : 'Non-Mobile Browser') . '<br />';
    $agent = 'Not Available';
    if (isset($_SERVER["HTTP_USER_AGENT"]) )
        $agent = $_SERVER['HTTP_USER_AGENT'];
    $out .= '<em>User Agent</em>: <small>' . $agent . '</small>';
    $out .= '<div id="example"></div>
<script type="text/javascript">
var txt = "";
var myWidth;
if( typeof( window.innerWidth ) == "number" ) {
//Non-IE
myWidth = window.innerWidth;
} else if( document.documentElement &&
( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
//IE 6+ in "standards compliant mode"
myWidth = document.documentElement.clientWidth;
} else if ( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
//IE 4 compatible
myWidth = document.body.clientWidth;
}
txt+= "<em>Browser Width: </em>" + myWidth + " px<br>";
document.getElementById("example").innerHTML=txt;
</script>';

    $out .= '<br /><em>Feed title:</em> ' . get_bloginfo_rss('name') . get_wp_title_rss();

    $out .= '<br /><em>You are using</em> WordPress ' . $GLOBALS['wp_version'] . '<br /><em>PHP Version:</em> ' . phpversion();
    $out .= '<br /><em>Memory:</em> ' . round(memory_get_usage()/1024/1024,2) . 'M of ' .  (int)ini_get('memory_limit') . 'M <hr />';
    return $out;
}

add_shortcode('weaver_info', 'wvr_compat_sc_info');

// ---------------- included functions formerly part of Weaver ii

function wvr_compat_breadcrumb($echo = true, $wrap = 'breadcrumbs') {
	$bc = '';

	$containerBefore = '<span id="' . $wrap . '">';
	$containerAfter = '</span>';
	$containerCrumb = '<span class="crumbs">';
	$containerCrumbEnd = '</span>';
	$delimiter = '&rarr;'; //' &raquo; ';
	$blogname =  __('Blog','weaver-ii'); //text for the 'Blog' link
	$baseLink = '';
	$hierarchy = '';
	$currentLocation = '';
	$currentBefore = '<span class="bcur-page">';
	$currentAfter = '</span>';
	$currentLocationLink = '';
	$crumbPagination = '';

	global $post;


    $name =  __('Home','weaver-ii'); //text for the 'Home' link


	$bc = '';
	// Output the Base Link
	if (is_front_page() ) {
		$bc .= $currentBefore . $name . $currentAfter;
	} else {
		$home = home_url('/');
		$baseLink =  '<a href="' . $home . '">' . $name . '</a>';
		$bc .= $baseLink;
	}
	// If static Page as Front Page, and on Blog Posts Index
	if ( is_home() && ( 'page' == get_option( 'show_on_front' ) ) ) {
		$bc .= $delimiter . $currentBefore . $blogname . $currentAfter;
	}
	// Weaver II mod: check 'page_for_posts' when using PwP without setting blog host page
	// If static Page as Front Page, and on Blog, output Blog link
	if ( ! is_home() && ! is_page() && ! is_front_page() && ( 'page' == get_option( 'show_on_front' ) ) && get_option( 'page_for_posts' ) ) {
		$blogpageid = get_option( 'page_for_posts' );
		$bloglink = '<a href="' . get_permalink( $blogpageid ) . '">' .  $blogname . '</a>';
		$bc .= $delimiter . $bloglink;
	}

	// Define Category Hierarchy Crumbs for Category Archive
	if ( is_category() ) {
		global $wp_query;
		if (is_object($wp_query->get_queried_object())) {
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0) {
				$hierarchy = ( $delimiter . __( 'Categories','weaver-ii') . ' ' . get_category_parents( $parentCat, TRUE, $delimiter ) );
			} else {
				$hierarchy = $delimiter . __( 'Categories','weaver-ii') . ' ';
			}
		} else {
			$hierarchy = '';
		}
		// Set $currentLocation to the current category
		$currentLocation = single_cat_title( '' , FALSE );

	}
	// Define Crumbs for Day/Year/Month Date-based Archives
	elseif ( is_date() ) {
		// Define Year/Month Hierarchy Crumbs for Day Archive
		if  ( is_day() ) {
			$date_string = '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ' . '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ';
			$date_string .= $delimiter . ' ';
			$currentLocation = get_the_time('d');
		}
		// Define Year Hierarchy Crumb for Month Archive
		elseif ( is_month() ) {
			$date_string = '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ';
			$date_string .= $delimiter . ' ';
			$currentLocation = get_the_time('F');
		}
		// Set CurrentLocation for Year Archive
		elseif ( is_year() ) {
			$date_string = '';
			$currentLocation = get_the_time('Y');
		}
		$hierarchy = $delimiter . __( 'Published','weaver-ii') . ' ' . $date_string ;
	}
	// Define Category Hierarchy Crumbs for Single Posts
	elseif ( is_single() && !is_attachment() ) {
		$cats = get_the_category();
		if ($cats)
			$cur_cat = $cats[0];
		else
			$cur_cat = '';
		foreach ($cats as $cat) {
			$children = get_categories( array ('parent' => $cat->term_id ));
			if (count($children) == 0) {
				$cur_cat = $cat;
				break;
			}
		}
		if ($cur_cat) {
			$hierarchy = $delimiter . get_category_parents( $cur_cat, TRUE, $delimiter );
		} else {
			$hierarchy = $delimiter . '';
		}
			// Note: get_the_title() is filtered to output a
			// default title if none is specified
			$currentLocation = get_the_title();

	}
		// Define Category and Parent Post Crumbs for Post Attachments
	elseif ( is_attachment() ) {
		$parent = get_post($post->post_parent);
		$cat_parents = '';
		if ( get_the_category($parent->ID) ) {
			$cat = get_the_category($parent->ID);
			$cat = $cat ? $cat[0] : '';
			$cat_parents = get_category_parents( $cat, TRUE, $delimiter );
		}
		$hierarchy = $delimiter . $cat_parents . '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter;
		// Note: Titles are forced for attachments; the
		// filename will be used if none is specified
		$currentLocation = get_the_title();
	}
	// Define Current Location for Parent Pages
	elseif ( ! is_front_page() && is_page() && ! $post->post_parent ) {
		$hierarchy = $delimiter;
		// Note: get_the_title() is filtered to output a
		// default title if none is specified
		$currentLocation = get_the_title();
	}
	// Define Parent Page Hierarchy Crumbs for Child Pages
	elseif ( ! is_front_page() && is_page() && $post->post_parent ) {
		$parent_id  = $post->post_parent;
		$breadcrumbs = array();
		while ($parent_id) {
			$page = get_page($parent_id);
			$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
			$parent_id  = $page->post_parent;
		}
		$breadcrumbs = array_reverse($breadcrumbs);
		foreach ($breadcrumbs as $crumb) {
			$hierarchy = $hierarchy . $delimiter . $crumb;
		}
		$hierarchy = $hierarchy . $delimiter;
		// Note: get_the_title() is filtered to output a
		// default title if none is specified
		$currentLocation = get_the_title();
	}
		// Define current location for Search Results page
	elseif ( is_search() ) {
		$hierarchy = $delimiter . __('Search Results','weaver-ii') . ' ';
		$currentLocation = get_search_query();
	}
		// Define current location for Tag Archives
	elseif ( is_tag() ) {
		$hierarchy = $delimiter . __( 'Tags','weaver-ii') . ' ';
		$currentLocation = single_tag_title( '' , FALSE );
	}
		// Define current location for Author Archives
	elseif ( is_author() ) {
		$hierarchy = $delimiter . __( 'Author','weaver-ii') . ' ';
		$currentLocation = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
	}
		// Define current location for 404 Error page
	elseif ( is_404() ) {
		$hierarchy = $delimiter . __( '404','weaver-ii') . ' ';
		$currentLocation = __( 'Page not found','weaver-ii');
	}
		// Define current location for Post Format Archives
	elseif ( get_post_format() && ! is_home() ) {
		$hierarchy = $delimiter . __( 'Post Formats','weaver-ii') . ' ';
		$currentLocation = get_post_format_string( get_post_format() ) . 's';
	}

// Build the Current Location Link markup
	$currentLocationLink = $currentBefore . $currentLocation . $currentAfter;

// Define breadcrumb pagination

// Define pagination for paged Archive pages
	if ( get_query_var('paged') && ! function_exists( 'wp_paginate' ) ) {
	  $crumbPagination = ' - ' . __('Page','weaver-ii') . ' ' . get_query_var('paged');
	}

 // Define pagination for Paged Posts and Pages
	if ( get_query_var('page') ) {
	  $crumbPagination = ' - ' . __('Page','weaver-ii') . ' ' . get_query_var('page') . ' ';
	}

// Output the resulting Breadcrumbs

	$bc .= $hierarchy; // Output Hierarchy
	$bc .= $currentLocationLink; // Output Current Location
	$bc .= $crumbPagination; // Output page number, if Post or Page is paginated

	if (is_rtl()) {
		$list = explode($delimiter,$bc);        // split on the arrow
		$list = array_reverse($list);
		$larrow = '&larr;';
		$bc = implode($larrow,$list);
	}
	// Wrap crumbs
	$bc = $containerBefore . $containerCrumb . $bc . $containerCrumbEnd . $containerAfter;

	if ($echo) echo $bc;
	else return $bc;
	return '';
}

?>
