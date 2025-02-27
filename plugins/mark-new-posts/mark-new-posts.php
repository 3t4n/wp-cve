<?php
/*
Plugin Name: Mark New Posts
Plugin URI: https://wordpress.org/plugins/mark-new-posts/
Description: Highlight unread posts on your blog.
Version: 7.5.1
Author: i.lychkov
Author URI: https://profiles.wordpress.org/ilychkov/
Text Domain: mark-new-posts
License: MIT

Copyright 2023 Ivan Lychkov (email: ivanlychkov@gmail.com )

Permission is hereby granted, free of charge, to any person obtaining a 
copy of this software and associated documentation files (the 
"Software"), to deal in the Software without restriction, including 
without limitation the rights to use, copy, modify, merge, publish, 
distribute, sublicense, and/or sell copies of the Software, and to 
permit persons to whom the Software is furnished to do so, subject to 
the following conditions: 

The above copyright notice and this permission notice shall be included 
in all copies or substantial portions of the Software. 

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS 
OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY 
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, 
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

require_once('includes/class.options.php');

class MarkNewPosts {
	const PLUGIN_NAME = 'Mark New Posts';
	const COOKIE_EXP_DAYS = 365;
	const COOKIE_ID = 'mnp_';
	const COOKIE_DELIMITER = 'n';
	const OPTION_NAME = 'mark_new_posts';
	const TEXT_DOMAIN = 'mark-new-posts';

	private $cookie_name;
	private $posts_marked;
	private $options;
	private $last_visit;
	private $new_posts_displayed;

	function __construct() {
		$this->is_test = strpos($_SERVER['REQUEST_URI'], 'mnp-test');
		$this->cookie_name = self::COOKIE_ID . substr(md5(get_bloginfo('name')), 0, 8);
		$this->load_options();

		add_action('init', array(&$this, 'init'));
		if ($this->options->mark_after !== MarkNewPosts_MarkAfter::OPENING_BLOG) {
			add_filter('the_posts', array(&$this, 'mark_posts_as_read'), 10, 2);
		}
		add_filter('the_title', array(&$this, 'mark_title'), 10, 2);

		if ($this->options->use_js) {
			add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
			add_filter('wp_footer', array(&$this, 'marker_template'), 10, 2);
		}
		if ($this->options->marker_type !== MarkNewPosts_MarkerType::NONE) {
			add_action('wp_enqueue_scripts', array(&$this, 'enqueue_styles'));
		}

		add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
		add_action('admin_menu', array(&$this, 'admin_menu'));
		add_filter('plugin_action_links', array(&$this, 'add_action_links'), 10, 2);
		add_action('wp_ajax_mark_new_posts_save_options', array(&$this, 'save_options'));
	}

	private function load_options() {
		$options = get_option(self::OPTION_NAME);
		if (!$options instanceof MarkNewPosts_Options) {
			$options = new MarkNewPosts_Options();
		}
		// migrate to 5.6.10
		if (isset($options->set_read_after_opening)) $options->open_to_read = $options->set_read_after_opening;
		if (isset($options->custom_image_url)) $options->image_url = $options->custom_image_url;
		// migrate to 6.9.28
		if (isset($options->open_to_read)) {
			$options->mark_after = $options->open_to_read ? MarkNewPosts_MarkAfter::OPENING_POST : MarkNewPosts_MarkAfter::OPENING_LIST;
		}
		// migrate to 7.2.0
		if (isset($options->check_markup)) {
			$options->use_js = $options->check_markup;
			$options->mark_title_bg = false;
		}
		// migrate to 7.3.0
		if ($options->marker_type === 4) {
			$options->marker_type = MarkNewPosts_MarkerType::IMAGE_DEFAULT;
		}
		// migrate to 7.3.1
		if ($options->marker_type === 7) {
			$options->marker_type = MarkNewPosts_MarkerType::TEXT_NEW;
		}
		$this->options = $options;
	}

	public function init() {
		load_plugin_textdomain(self::TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');
		$this->set_current_time_cookie();
	}

	private function set_current_time_cookie() {
		if (is_admin() || is_404() || is_preview()) return;
		$name = $this->cookie_name;
		$cookie = isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
		$pos = strpos($cookie, self::COOKIE_DELIMITER);
		$time = $pos ? substr($cookie, 0, $pos) : $cookie;
		$reset_time = $this->options->mark_after === MarkNewPosts_MarkAfter::OPENING_BLOG;
		if ($time === null) {
			$time = $this->get_cookie_timestamp(false);
			if (!$reset_time) {
				$this->set_cookie($time);
			}
		}
		if ($reset_time) {
			$this->set_cookie($this->get_cookie_timestamp(true));
		}
		$this->last_visit = $time;
	}

	private function get_cookie_timestamp($real) {
		$t = $this->options->all_new_for_new_visitor && !$real
			? 0
			: current_time('timestamp');
		$h = gmdate('H', $t);
		$i = gmdate('i', $t);
		$s = gmdate('s', $t);
		$m = gmdate('m', $t);
		$d = gmdate('d', $t);
		$y = gmdate('Y', $t);
		return gmdate('U', mktime($h, $i, $s, $m, $d, $y));
		// $current_timestamp = gmdate( 'U', $current_time );
		// you'd think this would work, but it doesn't. *sigh*. Instead we use this
		// ugly workaround to make sure the time is the same as the post's time would be if you posted right now
		// ^ this is a historical original comment from kb-new-posts
	}

	private function set_cookie($value) {
		$exp_time = time() + self::COOKIE_EXP_DAYS * 86400;
		setcookie($this->cookie_name, $value, $exp_time, COOKIEPATH, COOKIE_DOMAIN);
	}

	public function mark_posts_as_read($posts, $query) {
		$headers = function_exists('getallheaders') ? getallheaders() : $this->get_headers();
		if ($headers && isset($headers['X-Moz']) && $headers['X-Moz'] === 'prefetch') return $posts;
		if ($this->posts_marked || is_admin() || is_404() || is_preview() || !$query->is_main_query()) return $posts;
		$this->posts_marked = true;
		$read_posts_ids = $this->get_read_posts_ids();
		$update_cookie = false;
		foreach ($posts as $post) {
			$post_id = $post->ID;
			if ($this->is_after_cookie_time($post) && !in_array($post_id, $read_posts_ids) || $this->is_test) {
				$this->new_posts_displayed = true;
				if ($this->options->mark_after !== MarkNewPosts_MarkAfter::OPENING_POST || is_single()) {
					$read_posts_ids[] = $post_id;
					$update_cookie = true;
				}
			}
		}
		if (!$update_cookie) return $posts;
		array_unshift($read_posts_ids, $this->last_visit);
		$this->set_cookie(join(self::COOKIE_DELIMITER, $read_posts_ids));
		return $posts;
	}

	private function get_headers() {
		$headers = false;
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) === 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}

	private function get_read_posts_ids() {
		$name = $this->cookie_name;
		$cookie = isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
		return $cookie ? array_slice(explode(self::COOKIE_DELIMITER, $cookie), 1) : array();
	}

	private function is_after_cookie_time($post) {
		return get_post_time('U', false, $post) >= $this->get_time_to();
	}

	private function get_time_to() {
		$result = $this->last_visit;
		if ($this->options->post_stays_new_days) {
			$diff_ms = $this->options->post_stays_new_days * 24 * 60 * 60;
			$current_time_diff = current_time('timestamp') - $diff_ms;
			$result = max($result, $current_time_diff);
		}
		return $result;
	}

	public function enqueue_scripts() {
		if ($this->new_posts_displayed || $this->options->allow_outside_the_loop) {
			wp_enqueue_script('mark_new_posts_js', plugins_url('js/mark-new-posts.js', __FILE__));
		}
	}

	public function enqueue_styles() {
		if ($this->new_posts_displayed || $this->options->allow_outside_the_loop) {
			wp_enqueue_style('mark_new_posts_style', plugins_url('css/style.css', __FILE__));
		}
	}

	public function mark_title($title, $post_id = null) {
		if (!(in_the_loop() || $this->options->allow_outside_the_loop)
			|| is_admin() || is_preview()
			|| !$this->is_new_post($post_id)) return $title;
		return $this->options->use_js ? '<mnp-mark>' . $title . '</mnp-mark>' : $this->wrap_title($title);
	}

	private function wrap_title($title) {
		$placement = $this->options->marker_placement;
		if ($this->options->marker_type !== MarkNewPosts_MarkerType::NONE) {
			$title = '<span class="mnp-title-text">' . $title . '</span>';
			$both = $placement === MarkNewPosts_MarkerPlacement::TITLE_BOTH;
			if ($placement === MarkNewPosts_MarkerPlacement::TITLE_BEFORE || $both) {
				$title = $this->get_marker(false) . $title;
			}
			if ($placement === MarkNewPosts_MarkerPlacement::TITLE_AFTER || $both) {
				$title .= $this->get_marker(true);
			}
		}
		if ($this->options->mark_title_bg) {
			return '<div style="background-color: ' . $this->options->mark_bg_color . '" class="mnp-unread">' . $title . '</div>';
		}
		return '<span class="mnp-unread">' . $title . '</span>';
	}

	private function get_marker($after) {
		$suffix = $after ? '-after' : '-before';
		switch ($this->options->marker_type) {
			case MarkNewPosts_MarkerType::CIRCLE:
				return '<span class="mnp-circle' . $suffix . '"></span>';
			case MarkNewPosts_MarkerType::TEXT:
				return '<span class="mnp-text-old">New</span>';
			case MarkNewPosts_MarkerType::TEXT_NEW:
				return '<span class="mnp-text mnp-text-' . get_stylesheet() . '">New</span>';
			case MarkNewPosts_MarkerType::IMAGE_DEFAULT:
				return '<img src="' . plugins_url('images/label-new-blue.png', __FILE__) . '"
					width="48" height="48" class="mnp-image' . $suffix . '"/>';
			case MarkNewPosts_MarkerType::FLAG:
				return '<img src="' . plugins_url('images/flag.png', __FILE__) . '"
					width="32" height="32" class="mnp-image' . $suffix . '"/>';
		}
		throw new Exception('Unknown marker type');
	}

	public function marker_template() {
		if (!$this->new_posts_displayed) return;
		echo $this->wrap_title('{title}');
	}

	public function admin_enqueue_scripts($hook) {
		if ($hook !== 'settings_page_mark-new-posts') return;
		wp_enqueue_script('mark_new_posts_admin_script', plugins_url('js/admin.js', __FILE__));
		wp_enqueue_style('mark_new_posts_admin_style', plugins_url('css/admin.css', __FILE__));
	}

	public function admin_menu() {
		add_options_page(self::PLUGIN_NAME, self::PLUGIN_NAME, 'administrator', basename(__FILE__), array(&$this, 'display_options_page'));
	}

	public function display_options_page() {
		require('includes/options-page.php');
	}

	public function save_options() {
		if (!preg_match('/^\#[0-9a-f]{3,6}$/i', $_POST['markBgColor'])) {
			$this->return_msg(false, __('Incorrect colour. Expected format: #fff or #ffffff', self::TEXT_DOMAIN));
			return;
		}
		$options = new MarkNewPosts_Options();
		$options->marker_placement = intval($_POST['markerPlacement']);
		$options->marker_type = intval($_POST['markerType']);
		$options->mark_title_bg = $_POST['markTitleBg'] === 'true';
		$options->mark_bg_color = $_POST['markBgColor'];
		$options->mark_after = intval($_POST['markAfter']);
		$options->post_stays_new_days = intval($_POST['postStaysNewDays']);
		$options->all_new_for_new_visitor = $_POST['allNewForNewVisitor'] === 'true';
		$options->disable_for_custom_posts = $_POST['disableForCustomPosts'] === 'true';
		$options->allow_outside_the_loop = $_POST['allowOutsideTheLoop'] === 'true';
		$options->use_js = $_POST['useJs'] === 'true';
		update_option(self::OPTION_NAME, $options);
		$this->return_msg(true, __('Settings saved', self::TEXT_DOMAIN));
	}

	private function return_msg($success, $message) {
		header('Content-Type: application/json');
		echo json_encode(array(
			'success' => $success,
			'message' => $message
		));
		die();
	}

	private function echo_option($option, $value, $label) {
		$selected_attribute = $option === $value ? ' selected' : '';
		echo '<option value="' . $value . '"' . $selected_attribute . '>' . $label . '</option>';
	}

	private function echo_mark_after_option($value, $label) {
		$selected_attribute = $this->options->mark_after === $value ? ' checked=""' : '';
		echo '<div><input type="radio" name="mnp-mark-after" id="mnp-mark-after-' . $value . '" value="' . $value . '"' . $selected_attribute
			. '/ ><label for="mnp-mark-after-' . $value . '">' . $label . '</label></div>';
	}

	public function add_action_links($all_links, $current_file) {
		$current_file = basename($current_file);
		if (basename(__FILE__) == $current_file) {
			$link_text = __('Settings', self::TEXT_DOMAIN);
			$link = '<a href="' . admin_url('options-general.php?page=' . $current_file) . '">' . $link_text . '</a>';
			array_unshift($all_links, $link);
		}
		return $all_links;
	}

	public function is_new_post($post) {
		if ($this->is_test) return true;
		if (get_post_type($post) !== 'post' && $this->options->disable_for_custom_posts) return false;
		return $this->is_after_cookie_time($post)
			&& !in_array($this->get_post_id($post), $this->get_read_posts_ids());
	}

	private function get_post_id($post) {
		if (empty($post)) return get_the_ID();
		if (is_int($post)) return $post;
		if ($post instanceof WP_Post) return $post->ID;
		return null;
	}

	public function new_posts_count($query) {
		$q = new WP_Query();
		$q->parse_query($query);
		$q->set('post__not_in', $this->get_read_posts_ids());
		$q->set('date_query', array(
			'after' => date('r', $this->get_time_to())
		));
		$q->set('nopaging', true);
		$q->set('fields', 'ids');
		$q->set('no_found_rows', true);
		return count($q->get_posts());
	}
}

$mark_new_posts = new MarkNewPosts();

/**
 * @param int|WP_Post   $post   WP_Post object or ID (optional)
 */ 
function mnp_is_new_post($post = null) {
	global $mark_new_posts;
	return $mark_new_posts->is_new_post($post);
}

/**
 * @param string|WP_Query   $query   Posts filter (optional)
 */ 
function mnp_new_posts_count($query = null) {
	global $mark_new_posts;
	return $mark_new_posts->new_posts_count($query);
}
?>