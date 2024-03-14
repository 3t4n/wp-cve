<?php
/*
Plugin Name: Excerpt Editor
Plugin URI: http://www.laptoptips.ca/projects/wordpress-excerpt-editor/
Description: Add or edit excerpts for Posts and Pages.
Version: 1.4
Author: Andrew Ozz
Author URI: http://www.laptoptips.ca/

Released under the GPL version 2, http://www.gnu.org/copyleft/gpl.html

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
*/

$pgee_load_txtdomain = true;
function pgee_txtdomain() {
	global $pgee_load_txtdomain;

	if ( $pgee_load_txtdomain ) {
		load_plugin_textdomain('excerpt-editor', 'wp-content/plugins/excerpt-editor/languages');
		$pgee_load_txtdomain = false;
	}
}

add_action( 'wp_head', 'pgee_append_css' );
function pgee_append_css() {
	global $wp_query;

	if ( empty($wp_query->is_single) && empty($wp_query->is_page) )
		return;

	$appendopt = get_option('pgee_append_options', array());
	if ( !empty($appendopt['append_to_posts']) || !empty($appendopt['append_to_pages']) || !empty($appendopt['append_subpages']) ) {

// ***************************************************************
// Edit below to change the appearance of the appended excerpts
?>

<style type="text/css">
.pgee-exc-before {
	font-size: 1.3em;
	margin: 20px 0 10px;
}
.pgee-exc-title {
	font-size: 1.1em;
	margin: 15px 0 5px;
}
.pgee-exc-text {
	font-size: 0.9em;
}
.pgee-read-more {
	font-size: 0.9em;
}
</style>

<?php
	}
}

function pgee_make($str, $editing = false) {
	$autoopt = get_option('pgee_auto_options', array());

	$html_tags = empty($autoopt['pgee_tags']) ? false : $autoopt['pgee_tags'];

	$cut = true;
	if ( strpos($str, '<!--more') && !empty($autoopt['pgee_more']) ) {
		$str = substr( $str, 0, strpos($str, '<!--more') );
		$cut = false;
	}

	$str = preg_replace('|<style[^>]*>.*?</style>|si', '', $str); // remove style
	$str = preg_replace('|<script[^>]*>.*?</script>|si', '', $str); //remove js
	$str = preg_replace('|<![\s\S]*?--[ \t\n\r]*>|', '', $str); // remove CDATA, html comments

	if ( $html_tags )
		$str = strip_tags($str, $html_tags);
	else
		$str = strip_tags($str);

	if ( !empty($autoopt['do_shortcodes']) )
		$str = strip_shortcodes($str);

	if ( $cut ) {
		$words = preg_split('/[ \t\r]+/', $str, $autoopt['pgee_length'] + 1);
		if ( count($words) > $autoopt['pgee_length'] )
			array_pop($words);

		$str = implode(' ', $words);

		if ( $html_tags ) {
			if ( strpos($str, '<') !== false && ( ! strpos($str, '>') || strrpos($str, '<') > strrpos($str, '>') ) )
				$str = substr( $str, 0, strrpos($str, '<') );
		}

		$str = trim($str);
		$str = rtrim($str, '.,:;');
	}

	if ( $html_tags )
		$str = balanceTags($str, true);

	if ( !$editing && empty($autoopt['do_shortcodes']) )
		$str = do_shortcode($str);

	return $str;
}

function pgee_auto_generate($the_post = '') {
	global $wp_query;

	if ( ! is_object($the_post) )
		return '';

	if ( '' == trim($the_post->post_excerpt) && '' == trim($the_post->post_content) )
		return '';

	pgee_txtdomain();

	if ( ! empty($the_post->post_password) ) { // if there's a password (from WP)
		if ( $_COOKIE['wp-postpass_'.COOKIEHASH] != $the_post->post_password ) // and it doesn't match the cookie
			return __('Protected post.', 'excerpt-editor');
	}

	$autoopt = get_option('pgee_auto_options', array());
	$replaceopt = get_option('pgee_replace_options', array());

	$excerpt = trim($the_post->post_excerpt);
	if ( empty($excerpt) ) {
		$generated = true;
		$pl = strlen($the_post->post_content);
		if ( $pl <= $autoopt['pgee_length'] || ($pl-5) <= strlen($excerpt) ) {
			$autoopt['more_link'] = 0;
			$excerpt = $the_post->post_content;
		} else {
			$excerpt = pgee_make($the_post->post_content);
		}
	}

	if ( $wp_query->is_feed ) {
		if ( !empty($autoopt['more_link']) ) {
			$more_text = sprintf( stripslashes($autoopt['more_text']), $the_post->post_title );
			$excerpt .= ' <a href="' . get_permalink($the_post->ID) . '">' . $more_text . '</a>';
		}
		$excerpt = ent2ncr($excerpt);
		$excerpt = convert_chars($excerpt);
		return $excerpt;
	}

	$excerpt = wptexturize($excerpt);
	$excerpt = convert_smilies($excerpt);
	$excerpt = convert_chars($excerpt);
	$excerpt = wpautop($excerpt);

	$p = false;
	$excerpt = trim($excerpt);
	if ( substr( $excerpt, -4 ) == '</p>' ) {
		$excerpt = substr( $excerpt, 0, -4 );
		$p = true;
	}

	if ( $generated && substr( $excerpt, -1 ) != '>' ) $excerpt .= '&#8230; ';

	if ( !empty($autoopt['more_link']) ) {
		if ( substr( $excerpt, -1 ) != '>' ) $tg = 'span';
		else $tg = 'div';

		$more_text = sprintf( stripslashes($autoopt['more_text']), $the_post->post_title );

		$excerpt .= ' <' . $tg . ' class="pgee-read-more"><a href="' . get_permalink($the_post->ID) . '">' . $more_text . '</a>';
		if ( !empty($autoopt['more_link_cc']) && empty($replaceopt['no_cmnt_count']) ) {
			if ( $the_post->comment_count > 1 )
				$excerpt .= ' | <a href="' . get_permalink($the_post->ID) . '#comments">' . $the_post->comment_count . ' ' . __('Comments', 'excerpt-editor') . '</a>';
			if ( $the_post->comment_count == 1 )
				$excerpt .= ' | <a href="' . get_permalink($the_post->ID) . '#comments"> 1 ' . __('Comment', 'excerpt-editor') . '</a>';
		}
		$excerpt .= '</' . $tg . '>';
	}
	if ( $p ) $excerpt .= '</p>';
	return $excerpt;
}


add_action( 'edit_page_form', 'pgee_page_excerpt' );
function pgee_page_excerpt() {
	global $post;
?>
	<input type="hidden" name="excerpt" id="excerpt" value="<?php echo $post->post_excerpt; ?>" />
<?php
}


add_filter( 'the_content', 'pgee_append_excerpts', 65 );
function pgee_append_excerpts($content) {
	global $post, $wp_query, $wpdb;

	if ( ( empty($wp_query->is_single) && empty($wp_query->is_page) ) || $wp_query->is_feed )
		return $content;

	$appendopt = get_option('pgee_append_options', array());
	if ( empty($appendopt['append_to_posts']) && empty($appendopt['append_to_pages']) && empty($appendopt['append_subpages']) )
		return $content;

	if ( !empty($appendopt['dont_append']) && in_array($post->ID, (array) $appendopt['dont_append']) )
		return $content;

	if ( empty($appendopt['append_exclude']) || !is_array($appendopt['append_exclude']) )
		$appendopt['append_exclude'] = array();

	$appendopt['append_exclude'][] = $post->ID;
	$output = '';

	if ( !empty($appendopt['append_include']) && $post->ID != $appendopt['append_include'] ) {
		$included = get_post($appendopt['append_include']);
		$appendopt['append_exclude'][] = $appendopt['append_include'];
	}

	sort($appendopt['append_exclude']);
	$excluded = implode( ',', $appendopt['append_exclude'] );

	if ( ( ( !empty($appendopt['append_to_posts']) && $wp_query->is_single )
	|| ( !empty($appendopt['append_to_pages']) && $wp_query->is_page ) )
	&& !empty($appendopt['append_number_posts']) ) {
		$getposts_args = array( 'numberposts' => $appendopt['append_number_posts'], 'exclude' => $excluded );
		$getposts = get_posts( $getposts_args );
	} else {
		$getposts = '';
	}

	$before_appended = empty($appendopt['before_appended']) ? '' : stripslashes($appendopt['before_appended']);

	if ( $post->post_type == 'post' && !empty($appendopt['append_to_posts']) ) {

		if ( !empty($appendopt['append_newest_post']) && empty($getposts) ) {
			$newest = get_posts( array('numberposts' => '1') );
			$newest_post = $newest[0];
			if ( $newest_post->ID != $post->ID ) {
				$exc = pgee_auto_generate($newest_post);
				$output .= '<div class="pgee-exc-title"><a href="' . get_permalink($newest_post->ID) . '">' . $newest_post->post_title . '</a></div>' . "\n";
				$output .= '<div class="pgee-exc-text">' . $exc . '</div>' . "\n\n";
			}
		}

		if ( !empty($included) ) {
			$exc = pgee_auto_generate($included);
			$output .= '<div class="pgee-exc-title"><a href="' . get_permalink($included->ID) . '">' . $included->post_title . '</a></div>' . "\n";
			$output .= '<div class="pgee-exc-text">' . $exc . '</div>' . "\n\n";
		}

		if ( !empty($getposts) ) {
			foreach( $getposts as $the_post ) {
				$exc = pgee_auto_generate($the_post);
				$output .= '<div class="pgee-exc-title"><a href="' . get_permalink($the_post->ID) . '">' . $the_post->post_title . '</a></div>' . "\n";
				$output .= '<div class="pgee-exc-text">' . $exc . '</div>' . "\n\n";
			}
		}

		if ( $output && !empty($before_appended) )
			$output = '<div class="pgee-exc-before">' . $before_appended . '</div>' . "\n" . $output;

		return $content . $output;
	}

	if ( $post->post_type == 'page' && ( !empty($appendopt['append_to_pages']) || !empty($appendopt['append_subpages']) ) ) {

		if ( !empty($appendopt['append_newest_post']) ) {
			if ( !empty($getposts) ) {
				$newest_post = $getposts[0];
				unset($getposts[0]);
			} else {
				$newest = get_posts( array('numberposts' => '1') );
				$newest_post = $newest[0];
			}

			$exc = pgee_auto_generate($newest_post);
			$output .= '<div class="pgee-exc-title"><a href="' . get_permalink($newest_post->ID) . '">' . $newest_post->post_title . '</a></div>' . "\n";
			$output .= '<div class="pgee-exc-text">' . $exc . '</div>' . "\n\n";
		}

		if ( ! empty($included) ) {
			$exc = pgee_auto_generate($included);
			$output .= '<div class="pgee-exc-title"><a href="' . get_permalink($included->ID) . '">' . $included->post_title . '</a></div>' . "\n";
			$output .= '<div class="pgee-exc-text">' . $exc . '</div>' . "\n\n";
		}

		if ( !empty($appendopt['append_subpages']) && !empty($appendopt['append_number_pages']) ) {

			$order_by = 'menu_order'; // order of page children
			$limit = (int) $appendopt['append_number_pages'];
			$getpages = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_parent = %d AND post_type = 'page' AND post_status = 'publish' AND ID NOT IN (%s) ORDER BY %s DESC LIMIT %d", $post->ID, $excluded, $order_by, $limit) );

			if ( $getpages ) {
				foreach( $getpages as $the_page ) {
					$exc = pgee_auto_generate($the_page);
					$output .= '<div class="pgee-exc-title"><a href="' . get_permalink($the_page->ID) . '">' . $the_page->post_title . '</a></div>' . "\n";
					$output .= '<div class="pgee-exc-text">' . $exc . '</div>' . "\n\n";
				}
			}
		}

		if ( !empty($appendopt['append_to_pages']) && ! empty($getposts) ) {
			foreach( $getposts as $the_post ) {
				$exc = pgee_auto_generate($the_post);
				$output .= '<div class="pgee-exc-title"><a href="' . get_permalink($the_post->ID) . '">' . $the_post->post_title . '</a></div>' . "\n";
				$output .= '<div class="pgee-exc-text">' . $exc . '</div>' . "\n\n";
			}
		}

		if ( $output && ! empty($before_appended) ) $output = '<div class="pgee-exc-before">' . $before_appended . '</div>' . "\n" . $output;

		return $content . $output;
	}
	return $content;
}


add_action( 'deactivate_excerpt-editor/excerpt-editor.php', 'pgee_deactiv' );
function pgee_deactiv() {
	delete_option('pgee_options');
	delete_option('pgee_auto_options');
	delete_option('pgee_append_options');
	delete_option('pgee_replace_options');
}


add_filter( 'the_excerpt', 'pgee_insert_excerpt' );
add_filter( 'the_excerpt_rss', 'pgee_insert_excerpt' );
function pgee_insert_excerpt($excerpt) {
	global $post, $wp_query;
	$autoopt = get_option('pgee_auto_options', array());

	if ( $wp_query->is_feed && !empty($autoopt['in_rss']) )
		return pgee_auto_generate($post);

	if ( ( !$wp_query->is_feed || $wp_query->is_search ) && !empty($autoopt['on_site']) )
		return pgee_auto_generate($post);

	return $excerpt;
}


add_filter( 'the_content', 'pgee_replace_posts', 60 );
function pgee_replace_posts($content) {
	global $post, $wp_query;

	if ( ( empty($wp_query->is_home) && empty($wp_query->is_archive) && empty($wp_query->is_tag) ) || $wp_query->is_feed )
		return $content;

	$replaceopt = get_option('pgee_replace_options', array());

	if ( !empty($replaceopt['keep_latest']) ) {
		$latest = get_posts( array('numberposts' => '1') );
		if ( $latest[0]->ID == $post->ID )
			return $content;
	}

	if ( ( $wp_query->is_home && !empty($replaceopt['replace_home']) ) ||
		( $wp_query->is_archive && !empty($replaceopt['replace_archives']) ) ||
		( $wp_query->is_search && !empty($replaceopt['replace_archives']) ) ||
		( $wp_query->is_tag && !empty($replaceopt['replace_tags']) ) )
			$content = pgee_auto_generate($post);

	return $content;
}


function pgee_adminpage() {
	include_once( dirname(__FILE__) . '/pgee_admin.php' );
}


function pgee_adminpage_head() {
?>
<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL; ?>/excerpt-editor/excerpt-editor.css" type="text/css" />
<?php
}


add_action( 'admin_menu', 'pgee_menu' );
function pgee_menu() {
	if ( function_exists('add_management_page') ) {
		pgee_txtdomain();
		$page = add_management_page( __('Excerpt Editor', 'excerpt-editor'), __('Excerpt Editor', 'excerpt-editor'), 8,  'excerpt-editor', 'pgee_adminpage' );
		add_action("admin_print_scripts-$page", 'pgee_adminpage_head');
	}
}

