<?php
/**
 * Plugin Name: WP Twitter Timeline
 * Plugin URI: http://blog.ppfeufer.de/wordpress-plugin-wp-twitter-timeline/
 * Description: Add a sidebarwidget with your twitter timeline to your WordPress-Blog.
 * Version: 1.2
 * Author: H.-Peter Pfeufer
 * Author URI: http://ppfeufer.de
 */

class Wp_Twitter_Timeline extends WP_Widget {
	/**
	 * Einige notwendige Variablen definieren
	 */
	protected $var_sPluginDir = '../wp-content/plugins/wp-twitter-timeline/';

	/**
	 * Constructor
	 */
	function Wp_Twitter_Timeline() {
		if(function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain('wp-twitter-timeline', PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/l10n', dirname(plugin_basename(__FILE__)) . '/l10n');
		}

		add_action('wp_head', array(
			$this,
			'wp_twitter_timeline_head'
		));

		add_action('in_plugin_update_message-' . plugin_basename(__FILE__), array(
			$this,
			'wp_twitter_timeline_update_notice'
		));

		$widget_ops = array(
			'classname' => 'wp_twitter_timeline',
			'description' => __('Adds a twitter timeline to your blog', 'wp-twitter-timeline')
		);

		$control_ops = array(
			'width' => 200
		);

		$this->WP_Widget('wp_twitter_timeline', __('WP Twitter Timeline', 'wp-twitter-timeline'), $widget_ops, $control_ops);
	}

	/**
	 * Widgetformular erstellen
	 * @param $instance
	 */
	function form($instance) {
		$instance = wp_parse_args((array) $instance, array(
			'title' => '',
			'twittername' => '',
			'number-of-tweets' => '5',
			'height' => '300',
			'own-css' => '.twtr-widget h3, .twtr-widget h4 {clear:none;}'
		));

//		$title = strip_tags($instance['title']);
		$poll_results = ($instance['poll-results'] == 'true') ? 'checked="checked"' : '';
		$scrollbar = ($instance['scrollbar'] == 'true') ? 'checked="checked"' : '';
		$avatars = ($instance['avatars'] == 'true') ? 'checked="checked"' : '';
		$timestamps = ($instance['timestamps'] == 'true') ? 'checked="checked"' : '';
		$hashtags = ($instance['hashtags'] == 'true') ? 'checked="checked"' : '';
		$timed_interval = ($instance['behavior'] == 'default') ? ' checked="checked"' : '';
		$repeat_tweets = ($instance['repeat-tweets'] == 'true') ? ' checked="checked"' : '';
		$load_all_tweets = ($instance['behavior'] == 'all') ? ' checked="checked"' : '';
		$height_auto = ($instance['height-auto'] == 'true') ? ' checked="checked"' : '';

		// Title
		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Title:', 'wp-twitter-timeline') . '</strong></p>';
		echo '<p><input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . strip_tags($instance['title']) . '" /></p>';
		echo '<p style="clear:both;"></p>';

		// User-Settings
		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('User-Settings:', 'wp-twitter-timeline') . '</strong></p>';
		echo '<p><span style="display:inline-block;">' . __('Twittername <em>(without @)</em>', 'wp-twitter-timeline') . ': </span><input id="' . $this->get_field_id('twittername') . '" name="' . $this->get_field_name('twittername') . '" type="text" value="' . $instance['twittername'] . '" /></p>';
		echo '<p><span style="display:inline-block;">' . __('Number of tweets', 'wp-twitter-timeline') . ': </span><input id="' . $this->get_field_id('number-of-tweets') . '" name="' . $this->get_field_name('number-of-tweets') . '" type="text" value="' . $instance['number-of-tweets'] . '" /></p>';
		echo '<p style="clear:both;"></p>';

		// Preferences
		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Preferences:', 'wp-twitter-timeline') . '</strong></p>';
		echo '<p><input class="checkbox" type="checkbox" ' . $poll_results . ' id="' . $this->get_field_id('poll-results') . '" name="' . $this->get_field_name('poll-results') . '" /> <span style="display:inline-block;">' . __('Poll for new results?', 'wp-twitter-timeline') . '</span></p>';
		echo '<p><input class="checkbox" type="radio" ' . $timed_interval . ' value="default" id="' . $this->get_field_id('behavior') . '" name="' . $this->get_field_name('behavior') . '" group="behaviour" onchange="wpWidgets.save(jQuery(this).closest(\'div.widget\'),0,1,0);" /> <span style="display:inline-block;">' . __('Timed Interval?', 'wp-twitter-timeline') . '</span></p>';

		if($timed_interval != '') {
			echo '<p><input class="checkbox" type="checkbox" ' . $repeat_tweets . ' id="' . $this->get_field_id('repeat-tweets') . '" name="' . $this->get_field_name('repeat-tweets') . '" /> <span style="display:inline-block;">' . __('Repeat Tweets?', 'wp-twitter-timeline') . '</span></p>';
			echo '<p><span style="display:inline-block;">' . __('Tweet Interval <em>(Seconds)</em>', 'wp-twitter-timeline') . ': </span><input id="' . $this->get_field_id('tweet-interval') . '" name="' . $this->get_field_name('tweet-interval') . '" type="text" value="' . $instance['tweet-interval'] . '" /></p>';
		}

		echo '<p><input class="checkbox" type="radio" ' . $load_all_tweets . ' value="all" id="' . $this->get_field_id('behavior') . '" name="' . $this->get_field_name('behavior') . '" group="behaviour" onchange="wpWidgets.save(jQuery(this).closest(\'div.widget\'),0,1,0);" /> <span style="display:inline-block;">' . __('Load all tweets?', 'wp-twitter-timeline') . '</span></p>';
		echo '<p><input class="checkbox" type="checkbox" ' . $scrollbar . ' id="' . $this->get_field_id('scrollbar') . '" name="' . $this->get_field_name('scrollbar') . '" /> <span style="display:inline-block;">' . __('Include scrollbar?', 'wp-twitter-timeline') . '</span></p>';
		echo '<p><input class="checkbox" type="checkbox" ' . $avatars . ' id="' . $this->get_field_id('avatars') . '" name="' . $this->get_field_name('avatars') . '" /> <span style="display:inline-block;">' . __('Show Avatars?', 'wp-twitter-timeline') . '</span></p>';
		echo '<p><input class="checkbox" type="checkbox" ' . $timestamps . ' id="' . $this->get_field_id('timestamps') . '" name="' . $this->get_field_name('timestamps') . '" /> <span style="display:inline-block;">' . __('Show Timestamps?', 'wp-twitter-timeline') . '</span></p>';
		echo '<p><input class="checkbox" type="checkbox" ' . $hashtags . ' id="' . $this->get_field_id('hashtags') . '" name="' . $this->get_field_name('hashtags') . '" /> <span style="display:inline-block;">' . __('Show Hashtags?', 'wp-twitter-timeline') . '</span></p>';
		echo '<p style="clear:both;"></p>';

		// Widget-Settings
		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Dimension:', 'wp-twitter-timeline') . '</strong></p>';
		echo '<p><span style="display:inline-block;">' . __('Height <em>(in pixel)</em>', 'wp-twitter-timeline') . ': </span><input id="' . $this->get_field_id('height') . '" name="' . $this->get_field_name('height') . '" type="text" value="' . $instance['height'] . '" /></p>';
		echo '<p style="clear:both;"></p>';

		// #Colors
		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>#' . __('Colors:', 'wp-twitter-timeline') . '</strong></p>';
		echo '<p><span style="display:inline-block;">' . __('Backgroundcolor (Head/Foot)', 'wp-twitter-timeline') . ': </span><input id="' . $this->get_field_id('shellbgcolor') . '" name="' . $this->get_field_name('shellbgcolor') . '" type="text" value="' . $instance['shellbgcolor'] . '" /></p>';
		echo '<p><span style="display:inline-block;">' . __('Textcolor (Head/Foot)', 'wp-twitter-timeline') . ': </span><input id="' . $this->get_field_id('shelltextcolor') . '" name="' . $this->get_field_name('shelltextcolor') . '" type="text" value="' . $instance['shelltextcolor'] . '" /></p>';
		echo '<p><span style="display:inline-block;">' . __('Backgroundcolor (Tweets)', 'wp-twitter-timeline') . ': </span><input id="' . $this->get_field_id('bgcolor') . '" name="' . $this->get_field_name('bgcolor') . '" type="text" value="' . $instance['bgcolor'] . '" /></p>';
		echo '<p><span style="display:inline-block;">' . __('Textcolor (Tweets)', 'wp-twitter-timeline') . ': </span><input id="' . $this->get_field_id('textcolor') . '" name="' . $this->get_field_name('textcolor') . '" type="text" value="' . $instance['textcolor'] . '" /></p>';
		echo '<p><span style="display:inline-block;">' . __('Linkcolor (Tweets)', 'wp-twitter-timeline') . ': </span><input id="' . $this->get_field_id('linkcolor') . '" name="' . $this->get_field_name('linkcolor') . '" type="text" value="' . $instance['linkcolor'] . '" /></p>';
		echo '<p style="clear:both;"></p>';

		// Own CSS
		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Custom CSS:', 'wp-twitter-timeline') . '</strong></p>';
		echo '<p><span style="display:inline-block;">' . __('Write your CSS here ...', 'wp-twitter-timeline') . ': </span><textarea id="' . $this->get_field_id('own-css') . '" rows="10" name="' . $this->get_field_name('own-css') . '">' . $instance['own-css'] . '</textarea></p>';
		echo '<p style="clear:both;"></p>';

		// Flattr
		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Like this Plugin? Support the developer.', 'wp-twitter-timeline') . '</strong></p>';
		echo '<p><a href="http://flattr.com/thing/113632/WordPress-Plugin-WP-Twitter-Timeline" target="_blank"><img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a></p>';
		echo '<p style="clear:both;"></p>';
	}

	/**
	 * Widget erstellen
	 * @param unknown_type $args
	 * @param unknown_type $instance
	 */
	function widget($args, $instance) {
		extract($args);

		echo $before_widget;

		$title = (empty($instance['title'])) ? '' : apply_filters('widget_title', $instance['title']);

		if(!empty($title)) {
			echo $before_title . $title . $after_title;
		}

		echo $this->wp_twitter_timeline_output($instance, 'widget');
		echo $after_widget;
	}

	/**
	 * Optionen updaten
	 * @param $new_instance
	 * @param $old_instance
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$new_instance = wp_parse_args((array) $new_instance, array(
			'title' => '',
			'twittername' => '',
			'number-of-tweets' => '5',
			'height' => '300',
			'own-css' => '.twtr-widget h3, .twtr-widget h4 {clear:none;}'
		));

		$instance['plugin-version'] = $new_instance['plugin-version'];
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['twittername'] = strip_tags($new_instance['twittername']);
		$instance['number-of-tweets'] = strip_tags($new_instance['number-of-tweets']);
		$instance['height'] = strip_tags($new_instance['height']);
		$instance['textcolor'] = strip_tags($new_instance['textcolor']);
		$instance['bgcolor'] = strip_tags($new_instance['bgcolor']);
		$instance['shelltextcolor'] = strip_tags($new_instance['shelltextcolor']);
		$instance['shellbgcolor'] = strip_tags($new_instance['shellbgcolor']);
		$instance['linkcolor'] = strip_tags($new_instance['linkcolor']);
		$instance['poll-results'] = $new_instance['poll-results'] ? 'true' : 'false';
		$instance['liveupdate'] = $new_instance['liveupdate'] ? 'true' : 'false';
		$instance['scrollbar'] = $new_instance['scrollbar'] ? 'true' : 'false';
		$instance['avatars'] = $new_instance['avatars'] ? 'true' : 'false';
		$instance['timestamps'] = $new_instance['timestamps'] ? 'true' : 'false';
		$instance['hashtags'] = $new_instance['hashtags'] ? 'true' : 'false';
		$instance['repeat-tweets'] = $new_instance['repeat-tweets'] ? 'true' : 'false';
		$instance['tweet-interval'] = strip_tags($new_instance['tweet-interval']);
		$instance['behavior'] = $new_instance['behavior'];
		$instance['own-css'] = $new_instance['own-css'];

		return $instance;
	}

	function wp_twitter_timeline_output($args = array(), $position) {
		?>
		<script src="http://widgets.twimg.com/j/2/widget.js"></script>
		<script>
		new TWTR.Widget({
			version: 2,
			type: 'profile',
			<?php if ($args['footer-text'] != '') : ?>
				footer: "Follow me",
			<?php endif; ?>
			rpp: <?php echo $args['number-of-tweets'] ?>,
			interval: <?php echo $args['tweet-interval'] ?>000,
			width: 'auto',
			height: <?php echo $args['height'] ?>,
			theme: {
				shell: {
					<?php if ($args['shellbgcolor'] == '') : ?>
						background: 'none',
					<?php else : ?>
						background: '<?php echo $args['shellbgcolor'] ?>',
					<?php endif; ?>
					color: '<?php echo $args['shelltextcolor'] ?>'
				},
				tweets: {
					<?php if ($args['bgcolor'] == '') : ?>
						background: 'none',
					<?php else : ?>
						background: '<?php echo $args['bgcolor'] ?>',
					<?php endif; ?>
					color: '<?php echo $args['textcolor'] ?>',
					links: '<?php echo $args['linkcolor'] ?>'
				}
			},
			features: {
				scrollbar: <?php echo $args['scrollbar'] ?>,
				loop: <?php echo $args['repeat-tweets'] ?>,
				live: <?php echo $args['poll-results'] ?>,
				hashtags: <?php echo $args['hashtags'] ?>,
				timestamp: <?php echo $args['timestamps'] ?>,
				avatars: <?php echo $args['avatars'] ?>,
				behavior: '<?php echo $args['behavior'] ?>'
			}
		}).render().setUser('<?php echo $args['twittername']; ?>').start();
		</script>
		<?php
	}

	function wp_twitter_timeline_head() {
		$array_widgetOptions = get_option('widget_wp_twitter_timeline');
		$array_widgetOptions = $array_widgetOptions[$this->number];

		// CSS suchen
		foreach($array_widgetOptions as $key => $value) {
			if($key == 'own-css') {
				$var_sOwnCSS = $value;
				break;
			}
		}

		if($var_sOwnCSS != '') {
			// CSS in den Header einfügen
			echo "\n" . '<!-- CSS for WP Twitter Timeline by H.-Peter Pfeufer [http://ppfeufer.de | http://blog.ppfeufer.de] -->' . "\n" . '<style type="text/css">' . "\n" . $var_sOwnCSS . "\n" . '</style>' . "\n" . '<!-- END of CSS for WP Twitter Timeline -->' . "\n\n";
		}
	}

	function wp_twitter_timeline_update_notice() {
		$array_WPTT_Data = get_plugin_data(__FILE__);
		$var_sUserAgent = 'Mozilla/5.0 (X11; Linux x86_64; rv:5.0) Gecko/20100101 Firefox/5.0 WorPress Plugin WP Twitter Timeline (Version: ' . $array_WPTT_Data['Version'] . ') running on: ' . get_bloginfo('url');
		$url_readme = 'http://plugins.trac.wordpress.org/browser/wp-twitter-timeline/trunk/readme.txt?format=txt';
// 		$data = '';
		$data = wp_remote_retrieve_body(wp_remote_get($url_readme, array('user-agent' => $var_sUserAgent)));

// 		if(ini_get('allow_url_fopen')) {
// 			$data = file_get_contents($url_readme);
// 		} else {
// 			if(function_exists('curl_init')) {
// 				$cUrl_Channel = curl_init();
// 				curl_setopt($cUrl_Channel, CURLOPT_URL, $url_readme);
// 				curl_setopt($cUrl_Channel, CURLOPT_RETURNTRANSFER, 1);
// 				curl_setopt($cUrl_Channel, CURLOPT_USERAGENT, $var_sUserAgent);
// 				$data = curl_exec($cUrl_Channel);
// 				curl_close($cUrl_Channel);
// 			} // END if(function_exists('curl_init'))
// 		} // END if(ini_get('allow_url_fopen'))

		if($data) {
			$matches = null;
			$regexp = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote($array_WPTT_Data['Version']) . '\s*=|$)~Uis';

			if(preg_match($regexp, $data, $matches)) {
				$changelog = (array) preg_split('~[\r\n]+~', trim($matches[1]));

				echo '</div><div class="update-message" style="font-weight: normal;"><strong>What\'s new:</strong>';
				$ul = false;
				$version = 99;

				foreach($changelog as $index => $line) {
					if(version_compare($version, $array_WPTT_Data['Version'], ">")) {
						if(preg_match('~^\s*\*\s*~', $line)) {
							if(!$ul) {
								echo '<ul style="list-style: disc; margin-left: 20px;">';
								$ul = true;
							} // END if(!$ul)

							$line = preg_replace('~^\s*\*\s*~', '', $line);
							echo '<li>' . $line . '</li>';
						} else {
							if($ul) {
								echo '</ul>';
								$ul = false;
							} // END if($ul)

							$version = trim($line, " =");
							echo '<p style="margin: 5px 0;">' . htmlspecialchars($line) . '</p>';
						} // END if(preg_match('~^\s*\*\s*~', $line))
					} // END if(version_compare($version, $array_WPTT_Data['Version'], ">"))
				} // END foreach($changelog as $index => $line)

				if($ul) {
					echo '</ul><div style="clear: left;"></div>';
				} // END if($ul)


				echo '</div>';
			} // END if(preg_match($regexp, $data, $matches))
		} else {
			/**
			 * Returning if we can't use file_get_contents or cURL
			 */
			return;
		} // END if($data)
	} // END function twoclick_buttons_update_notice()
}

add_action('widgets_init', create_function('', 'return register_widget("Wp_Twitter_Timeline");'));
?>