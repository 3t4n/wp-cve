<?php

/**
 * Handle all funcitons needed for page inserts
 *
 */

class TWL_Page_IN_Page_Page extends TWL_Page_In_Page {

	private static $instance = null;

	private static $pages = array();

	private static $facebookSDK = null;
	
	private static $twitterSDK = null;

	private $args = array();

	protected function __construct() {
		// register short codes
		add_shortcode('twl_page_in',    array('TWL_Page_IN_Page_Page', 'shortcode_wordpress'));
		add_shortcode('twl_page_in_wp', array('TWL_Page_IN_Page_Page', 'shortcode_wordpress'));
		add_shortcode('twl_page_in_fb', array('TWL_Page_IN_Page_Page', 'shortcode_facebook'));
		add_shortcode('twl_page_in_tw', array('TWL_Page_IN_Page_Page', 'shortcode_twitter'));
	}

	/**
	 * @return TWL_Page_IN_Page_Page
	 */
	public static function get_instance() {
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function init() {
		return self::get_instance();
	}

	public function configure($args) {
		if (!is_array($args)) {
			return false;
		}
		$this->args = $args;
		return $this;
	}

	public function display($page_id, $return = false) {
		if (!$this->args) {
			return;
		}

		$page = $this->page_info($page_id);
		if (!$page) {
			if ($page_id) {
				$notfound = 'Page with ID ' . $page_id . ' not found.';
				if ($return) return $notfound;
				echo $notfound;
			}
			 return;
		}

		// if widget title is present, show it. but if show_post_title is set, show post title instead.
		$title = isset($this->args['title']) ? $this->args['title'] : '';
		if (!empty($this->args['show_page_title'])) {
			$title = $page->post_title;
		}

		$content = apply_filters('the_content', $page->post_content);
		$image = wp_get_attachment_url(get_post_thumbnail_id($page->ID));
		$link = get_permalink($page->ID);
		$HTMLtemplate = !empty($this->args['output_template']) ? $this->args['output_template'] : $this->template();

		$HTML = $this->replace($HTMLtemplate, array(
			'page_title' => $title,
			'page_content' => $content,
			'page_link' => $link,
			'page_image' => $image,
		));

		if ($return) {
			return $HTML;
		}
		echo $HTML;
	}

	/**
	 * Get page info from wordpress DB
	 *
	 * @param int $page_id
	 * @return mixed Returns FALSE on faileur or WP_Post object on success
	 */
	private function page_info($page_id) {
		$page_id = (int) $page_id;
		if (!$page_id) {
			return false;
		}

		if (!isset(self::$pages[$page_id])) {
			if (($page = get_post($page_id))) {
				self::$pages[$page_id] = $page;
			} else {
				return false;
			}
		}

		return self::$pages[$page_id];
	}

	/**
	 * Get HTML template to display page based on args
	 * If title and content templates are set from the outside then use them
	 * If it is called from a widget, add widget's before/after title and before/after widget tags
	 * 
	 * @return string $template
	 */
	private function template() {
		$_title_template = $_image_template = $_content_template = "";
		$title_tag = !empty($this->args['is_widget']) ? 'span' : 'h3';

		if (!empty($this->args['show_page_title'])) {
			$_title_template = '<'.$title_tag.' class="twl-page-in-page-title">${page_title}</'.$title_tag.'>';
		}

		if (!empty($this->args['show_title_as_link'])) {
			$_title_template = '<'.$title_tag.' class="twl-page-in-page-title"><a href="${page_link}" title="${page_title}">${page_title}</a><'.$title_tag.'>';
		}

		if (!empty($this->args['show_featured_image'])) {
			$_image_template = '<div class="twl-page-in-page-image"><img src="${page_image}" alt="${page_title}" /></div>';
		}

		if (!empty($this->args['show_featured_image_as_link'])) {
			$_image_template = '<div class="twl-page-in-page-image"><a href="${page_link}" title="${page_title}"><img src="${page_image}" alt="${page_title}" /></a></div>';
		}

		if (!empty($this->args['show_page_content'])) {
			$_content_template = '<div class="twl-page-in-page-text">${page_content}</div>';
		}

		if (!empty($this->args['is_widget'])) {
			if (!empty($this->args['before_title'])) {
				$_title_template = $this->args['before_title'] . $_title_template . $this->args['after_title'];
			}
		}

		$template = '';
		$template .= '<div class="twl-page-in-page">';
		$template .=	$_title_template;
		$template .=	'<div class="twl-page-in-page-content">';
		$template .=		$_image_template;
		$template .=		$_content_template;
		$template .=	'</div>';
		$template .= '</div>';

		if (!empty($this->args['is_widget'])) {
			if (!empty($this->args['before_widget'])) {
				$template = $this->args['before_widget'] . $template . $this->args['after_widget'];
			}
		}

		return $template;
	}

	private function replace($template, $values) {
		foreach ($values as $key => $value) {
			$template = str_replace('${'.$key.'}', $value, $template);
		}
		return $template;
	}

	public static function shortcode_wordpress($params, $template = "") {
		$config = shortcode_atts(array(
			'id' => 0,
			'show_page_title' => 1,
			'show_page_content' => 1,
			'show_title_as_link' => 0,
			'show_featured_image' => 0,
			'show_featured_image_as_link' => 0,
		), $params);

		if ($template) {
			$config['output_template'] = self::decode($template);
		}

		return self::get_instance()->configure($config)->display($config['id'], true);
	}

	public static function shortcode_facebook($params) {
		$page_vars = array();
		$facebook = self::facebookSDK();
		$facebook_config = self::facebookConfig();

		if (empty($params['id'])) {
			$params['id'] = $facebook_config['page_id'];
		}

		try {

			if (!($feeds = self::cacheData('facebook_feed_data', 'facebook_feed_fetch_time'))) {
				$feeds = $facebook->api($params['id'] . '/posts');
				if (empty($feeds['data'])) {
					return false;
				} else {
					TWL_PIP_Config::add('facebook_feed_fetch_time', time());
					TWL_PIP_Config::add('facebook_feed_data', $feeds);
				}
			}

			$page_vars['feeds'] = $feeds['data'];
			$page_vars['page_id'] = $params['id'];
			$page_vars['fb_feed_item_template'] = $facebook_config['item_template'];

			ob_start();
			include $facebook_config['container_template'];
			$contents = ob_get_contents();
			ob_end_clean();

			return $contents;
		} catch (FacebookApiException $e) {
			if (!empty($facebook_config['alert_sdk_errors'])) {
				return 'Facebook SDK: ' . $e->getMessage();
			}
			twl_pip_log($e, 'FacebookApiException ');
			return false;
		}
	}

	public static function shortcode_twitter() {
		$page_vars = array();
		$twitter = self::twitterSDK();
		$twitter_config = self::twitterConfig();

		try {

			if (!($feeds = self::cacheData('twitter_feed_data', 'twitter_feed_fetch_time'))) {
				$twitter->setToken($twitter_config['access_token'], $twitter_config['access_token_secret']);
				$feeds = $twitter->statuses_userTimeline(array('screen_name' => $twitter_config['screen_name']));

				if (!$feeds) return false;
				if (!empty($feeds['errors'])) {
					$error = $feeds['errors'][0];
					throw new Exception($error['message'], $error['code']);
				} else {
					TWL_PIP_Config::add('twitter_feed_fetch_time', time());
					TWL_PIP_Config::add('twitter_feed_data', $feeds);
				}
			}

			$page_vars['feeds'] = $feeds;
			$page_vars['twitter_feed_item_template'] = $twitter_config['item_template'];

			ob_start();
			include $twitter_config['container_template'];
			$contents = ob_get_contents();
			ob_end_clean();

			return $contents;
		} catch (Exception $e) {
			if (!empty($twitter_config['alert_sdk_errors'])) {
				return 'Twitter SDK: ' . $e->getMessage();
			}
			twl_pip_log($e, 'Twitter Exception - Codebird ');
			return false;
		}
	}

	private static function decode($entities) {
		return html_entity_decode($entities, ENT_NOQUOTES, 'UTF-8');
	}

	private static function facebookSDK() {
		if (self::$facebookSDK === null) {
			$facebook_config = self::facebookConfig();
			require $facebook_config['sdk'];

			self::$facebookSDK = new Facebook(array(
				'appId' => $facebook_config['app_id'],
				'secret' => $facebook_config['app_secret'],
			));
		}

		return self::$facebookSDK;
	}

	private static function twitterSDK() {
		if (self::$twitterSDK === null) {
			$twitter_config = self::twitterConfig();
			require $twitter_config['sdk'];

			Codebird::setConsumerKey($twitter_config['customer_key'], $twitter_config['customer_secret']);
			self::$twitterSDK = Codebird::getInstance();
			self::$twitterSDK->setReturnFormat(CODEBIRD_RETURNFORMAT_ARRAY);
		}

		return self::$twitterSDK;
	}

	private static function facebookConfig() {
		return array_merge(
			TWL_PIP_Config::get('facebook'),
			TWL_PIP_Config::option('facebook-settings', array())
		);
	}

	private static function twitterConfig() {
		return array_merge(
			TWL_PIP_Config::get('twitter'),
			TWL_PIP_Config::option('twitter-settings', array())
		);
	}

	private static function cacheData($data_key, $cache_time_key) {
		$cache_time = (int) TWL_PIP_Config::option('cache_feeds');
		if (!$cache_time) {
			return false;
		} else {
			// convert to seconds (from minutes)
			$cache_time = $cache_time * 60;
		}

		$last_saved = (int) TWL_PIP_Config::option($cache_time_key);
		if (!$last_saved || (time() - $last_saved) > $cache_time) {
			return false;
		}

		return TWL_PIP_Config::option($data_key);
	}
}