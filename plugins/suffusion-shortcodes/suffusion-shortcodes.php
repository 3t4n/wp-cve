<?php
/**
 * Plugin Name: Suffusion Shortcodes
 * Plugin URI: http://aquoid.com/news/plugins/suffusion-shortcodes/
 * Description: This plugin is an add-on to the Suffusion WordPress Theme. Suffusion v4.4.7 and below offered you a lot of shortcodes, but if you move away from Suffusion or to newer versions of the theme, your content might not display properly due to the missing shortcodes. This plugin will ensure that the shortcodes are always available.
 * Version: 1.05
 * Author: Sayontan Sinha
 * Author URI: http://mynethome.net/blog
 * License: GNU General Public License (GPL), v3 (or newer)
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * Copyright (c) 2009 - 2016 Sayontan Sinha. All rights reserved.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

include_once(plugin_dir_path(__FILE__).'/suffusion-integration-pack.php');

class Suffusion_Shortcodes extends Suffusion_Integration_Pack {
	function __construct() {
		if (!defined('SUFFUSION_SHORTCODES_VERSION')) {
			define('SUFFUSION_SHORTCODES_VERSION', '1.05');
		}

		parent::__construct('Suffusion Shortcodes', 'Suffusion Shortcodes', 'suffusion-shortcodes', SUFFUSION_SHORTCODES_VERSION);

		add_action('admin_init', array(&$this, 'admin_init'));

		add_shortcode('suffusion-categories', array(&$this, 'list_categories'));
		add_shortcode('suffusion-the-year', array(&$this, 'the_year'));
		add_shortcode('suffusion-site-link', array(&$this, 'site_link'));
		add_shortcode('suffusion-the-author', array(&$this, 'the_author'));
		add_shortcode('suffusion-the-post', array(&$this, 'the_post'));
		add_shortcode('suffusion-login-url', array(&$this, 'login_url'));
		add_shortcode('suffusion-logout-url', array(&$this, 'logout_url'));
		add_shortcode('suffusion-loginout', array(&$this, 'loginout'));
		add_shortcode('suffusion-register', array(&$this, 'register'));
		add_shortcode('suffusion-adsense', array(&$this, 'ad'));
		add_shortcode('suffusion-tag-cloud', array(&$this, 'tag_cloud'));
		add_shortcode('suffusion-widgets', array(&$this, 'widget_area'));
		add_shortcode('suffusion-multic', array(&$this, 'multi_column'));
		add_shortcode('suffusion-column', array(&$this, 'column'));
		add_shortcode('suffusion-flickr', array(&$this, 'flickr'));

		global $suffusion_shortcode_options;
		$suffusion_shortcode_options = get_option('suffusion_shortcode_options');
		if (!isset($suffusion_shortcode_options) || !is_array($suffusion_shortcode_options)) {
			$suffusion_shortcode_options = array();
		}
		$suffusion_shortcode_options = array_merge(
			array(
				'enable_audio_shortcode' => false,
				'adhoc_wareas' => 5,
				'adhoc_column_counts' => array(
					1 => 1,
					2 => 1,
					3 => 1,
					4 => 1,
					5 => 1,
				)
			),
			$suffusion_shortcode_options
		);

		$adhoc_count = apply_filters('suffusion_adhoc_count', $suffusion_shortcode_options['adhoc_wareas']);
		for ($i = 1; $i <= $adhoc_count; $i++) {
			if ($adhoc_count > 5 && $i > 5) {
				$sidebar_id = 'suf-adhoc-widgets-'.$i;
			}
			$adhoc_columns = "suf_adhoc{$i}_columns";
			global $$adhoc_columns;
			register_sidebar(array(
				"name" => "Ad Hoc Widgets $i",
				'id' => ($adhoc_count > 5 && $i > 5) ? 'suf-adhoc-widgets-'.$i : 'sidebar-'.(12 + $i),
				"description" => "This is an ad-hoc widget area that can be invoked with the short code [suffusion-widgets id='$i'].",
				"before_widget" => '<!-- widget start --><aside id="%1$s" class="%2$s suf-widget suf-widget-'.$$adhoc_columns.'c">',
				"after_widget" => '</aside><!-- widget end -->',
				"before_title" => '<h3>',
				"after_title" => '</h3>'
			));
		}

		// Check for the JetPack [audio] shortcode and the WP Audio Player plugin. If neither exist AND the audio shortcode is enabled, only then define our audio shortcode.
		if (!function_exists('audio_shortcode') && !class_exists('AudioPlayer') && $suffusion_shortcode_options['enable_audio_shortcode'] == 'on') {
			add_shortcode('audio', array(&$this, 'audio'));
		}

//		remove_filter('the_content', 'wpautop');
//		add_filter('the_content', 'wpautop' , 12);
	}

	function add_admin_scripts($hook) {
		if ($hook == $this->option_page) {
			if (is_admin()) {
				wp_enqueue_style('suffusion-shortcodes-admin', plugins_url('include/css/admin.css', __FILE__), array(), $this->version);
				wp_enqueue_style('suffusion-shortcodes-admin-dosis', 'http://fonts.googleapis.com/css?family=Dosis', array(), $this->version);
			}
		}
	}

	function render_options() {
		global $suffusion_shortcode_options;
		?>
	<div class="suf-ip-wrapper">
		<h1>Welcome to Suffusion&apos;s Shortcodes</h1>
		<?php
		if (isset($_REQUEST['settings-updated'])) {
			?>
			<div id="sip-return-message" class="updated">Your Settings have been saved.</div>
			<?php
		}
		?>
		<p>
			This plugin makes available all the shortcodes from the Suffusion theme. If you decide to stop using Suffusion
			in the future, this plugin will ensure that your content is not broken. The following are the shortcodes provided:
		</p>
		<ol>
			<li><code>[suffusion-categories]</code></li>
			<li><code>[suffusion-the-year]</code></li>
			<li><code>[suffusion-site-link]</code></li>
			<li><code>[suffusion-the-author]</code></li>
			<li><code>[suffusion-the-post]</code></li>
			<li><code>[suffusion-login-url]</code></li>
			<li><code>[suffusion-logout-url]</code></li>
			<li><code>[suffusion-loginout]</code></li>
			<li><code>[suffusion-register]</code></li>
			<li><code>[suffusion-adsense]</code></li>
			<li><code>[suffusion-tag-cloud]</code></li>
			<li><code>[suffusion-widgets]</code></li>
			<li><code>[suffusion-multic]</code> and <code>[suffusion-column]</code></li>
			<li><code>[suffusion-flickr]</code></li>
			<li><code>[audio]</code></li>
		</ol>
		<p>
			For usage information see <a href="http://aquoid.com/news/themes/suffusion/shortcodes-to-enhance-functionality/">here</a>.
		</p>

		<fieldset>
			<legend>Shortcode Settings</legend>
			<form method="post" name="shortcode_settings_form" id="shortcode_settings_form" action="options.php">
				<h3>Audio</h3>
				<label><input type="checkbox" name="suffusion_shortcode_options[enable_audio_shortcode]" <?php checked($suffusion_shortcode_options['enable_audio_shortcode'], 'on'); ?>/> Enable Audio Shortcode</label><br/>
				<em>You might have JetPack or another plugin to handle the audio shortcode. If so, keep this switched off.</em>
				<h3>Ad Hoc Widgets</h3>
				<p>
					Suffusion lets you insert ad hoc widgets into your content using the <code>[suffusion-widgets]</code> shortcode.
					By default this comes with 5 ad hoc widget areas.
				</p>
				<?php
				$adhoc_count = apply_filters('suffusion_adhoc_count', 5);
				if ($this->check_integer($suffusion_shortcode_options['adhoc_wareas'])) {
					$adhoc_count = (int)$suffusion_shortcode_options['adhoc_wareas'];
				}
				?>
				<p>
					<label>
						Number of ad hoc widget areas
						<input type="text" name="suffusion_shortcode_options[adhoc_wareas]" value="<?php echo $adhoc_count; ?>" />
					</label>
				</p>
				<?php
				for ($i = 1; $i <= $adhoc_count; $i++) {
					$columns = 1;
					if (is_array($suffusion_shortcode_options['adhoc_column_counts']) && isset($suffusion_shortcode_options['adhoc_column_counts'][$i]) &&
						$this->check_integer($suffusion_shortcode_options['adhoc_column_counts'][$i])) {
						$columns = $suffusion_shortcode_options['adhoc_column_counts'][$i];
					}
					if ($columns < 1) {
						$columns = 1;
					}
					else if ($columns > 5) {
						$columns = 5;
					}
					?>
				<p>
					<label>
						Number of columns in ad hoc widget area <?php echo $i; ?>
						<input type="text" name="suffusion_shortcode_options[adhoc_column_counts][<?php echo $i; ?>]" value="<?php echo $columns; ?>" />
					</label>
				</p>
					<?php
				}

				settings_fields('suffusion_shortcode_options');
				?>
				<input type="submit" name="Submit" class="button" value="Save" />
			</form>
		</fieldset>

		<?php $this->other_plugins(); ?>
	</div>
	<?php
	}

	function add_scripts() {
		if (!is_admin()) {
			global $suffusion_shortcode_options;
			if ($suffusion_shortcode_options['enable_audio_shortcode'] == 'on') {
				wp_enqueue_script('suffusion-audioplayer', plugins_url('include/scripts/audio-player.js', __FILE__), array('jquery'), SUFFUSION_SHORTCODES_VERSION);
			}

			if (!defined('SUFFUSION_THEME_VERSION')) {
				wp_enqueue_style('suffusion-shortcodes', plugins_url('include/css/suffusion-shortcodes.css', __FILE__), array(), SUFFUSION_SHORTCODES_VERSION);
			}
		}
	}

	function direct_scripts() {
		global $suffusion_shortcode_options;
		if ($suffusion_shortcode_options['enable_audio_shortcode'] == 'on') { ?>
		<!-- Include AudioPlayer via Suffusion Shortcodes -->
		<script type="text/javascript">
			/* <![CDATA[ */
			if (typeof AudioPlayer != 'undefined') {
				AudioPlayer.setup("<?php echo plugins_url('include/scripts/player.swf', __FILE__); ?>", {
					width: 500,
					initialvolume: 100,
					transparentpagebg: "yes",
					left: "000000",
					lefticon: "FFFFFF"
				});
			}
			/* ]]> */
		</script>
		<!-- /AudioPlayer -->
		<?php
		}
	}

	function admin_init() {
		register_setting('suffusion_shortcode_options', 'suffusion_shortcode_options', array(&$this, 'validate_options'));
	}

	function check_integer($val) {
		if (substr($val, 0, 1) == '-') {
			$val = substr($val, 1);
		}
		return (preg_match('/^\d*$/', $val) == 1);
	}

	/**
	 * Validation function for the Settings API.
	 *
	 * @param $options
	 * @return array
	 */
	function validate_options($options) {
		foreach ($options as $option => $option_value) {
			if (!is_array($option_value)) {
				$options[$option] = esc_attr($option_value);
			}
			else {
				foreach ($option_value as $inner_option => $inner_option_value) {
					$options[$option][$inner_option] = esc_attr($inner_option_value);
				}
			}
		}
		return $options;
	}

	function list_categories($attr) {
		if (isset($attr['title_li'])) {
			$attr['title_li'] = $this->shortcode_string_to_bool($attr['title_li']);
		}
		if (isset($attr['hierarchical'])) {
			$attr['hierarchical'] = $this->shortcode_string_to_bool($attr['hierarchical']);
		}
		if (isset($attr['use_desc_for_title'])) {
			$attr['use_desc_for_title'] = $this->shortcode_string_to_bool($attr['use_desc_for_title']);
		}
		if (isset($attr['hide_empty'])) {
			$attr['hide_empty'] = $this->shortcode_string_to_bool($attr['hide_empty']);
		}
		if (isset($attr['show_count'])) {
			$attr['show_count'] = $this->shortcode_string_to_bool($attr['show_count']);
		}
		if (isset($attr['show_last_update'])) {
			$attr['show_last_update'] = $this->shortcode_string_to_bool($attr['show_last_update']);
		}
		if (isset($attr['child_of'])) {
			$attr['child_of'] = (int)$attr['child_of'];
		}
		if (isset($attr['depth'])) {
			$attr['depth'] = (int)$attr['depth'];
		}
		$attr['echo'] = false;

		$output = wp_list_categories($attr);

		return $output;
	}

	function the_year() {
		return date('Y');
	}

	function site_link() {
		return '<a class="site-link" href="'.get_bloginfo('url').'" title="'.esc_attr(get_bloginfo('name')).'" rel="home">'.get_bloginfo('name').'</a>';
	}

	function the_author($attr) {
		global $suffusion_social_networks;
		if (!isset($suffusion_social_networks)) {
			$suffusion_social_networks = array('twitter' => 'Twitter',
				'facebook' => 'Facebook',
				'technorati' => 'Technorati',
				'linkedin' => "LinkedIn",
				'flickr' => 'Flickr',
				'delicious' => 'Delicious',
				'digg' => 'Digg',
				'stumbleupon' => 'StumbleUpon',
				'reddit' => "Reddit"
			);
		}
		$id = get_the_author_meta('ID');
		if ($id) {
			if (isset($attr['display'])) {
				$display = $attr['display'];
				switch ($display) {
					case 'author':
						return get_the_author();
					case 'modified-author':
						return get_the_modified_author();
					case 'description':
						return get_the_author_meta('description', $id);
					case 'login':
						return get_the_author_meta('user_login', $id);
					case 'first-name':
						return get_the_author_meta('first_name', $id);
					case 'last-name':
						return get_the_author_meta('last_name', $id);
					case 'nickname':
						return get_the_author_meta('nickname', $id);
					case 'id':
						return $id;
					case 'url':
						return get_the_author_meta('user_url', $id);
					case 'email':
						return get_the_author_meta('user_email', $id);
					case 'link':
						if (get_the_author_meta('user_url', $id)) {
							return '<a href="'.get_the_author_meta('user_url', $id).'" title="'.esc_attr(get_the_author()).'" rel="external">'.get_the_author().'</a>';
						}
						else {
							return get_the_author();
						}
					case 'aim':
						return get_the_author_meta('aim', $id);
					case 'yim':
						return get_the_author_meta('yim', $id);
					case 'posts':
						return get_the_author_posts();
					case 'posts-url':
						return get_author_posts_url(get_the_author_meta('ID'));
				}
				if (isset($suffusion_social_networks) && isset($suffusion_social_networks[$display]) && $suffusion_social_networks[$display]) {
					return get_the_author_meta($display, $id);
				}
			}
			else {
				return get_the_author();
			}
		}
		return "";
	}

	function the_post($attr) {
		global $post;
		$id = $post->ID;
		if (isset($attr['display'])) {
			$display = $attr['display'];
			if ($id) {
				switch ($display) {
					case 'id':
						return $id;
					case 'title':
						return get_the_title($id);
					case 'permalink':
						return get_permalink($id);
					default:
						return get_the_title($id);
				}
			}
		}
		else {
			return get_the_title($id);
		}
		return "";
	}

	function login_url($attr) {
		return wp_login_url($attr['redirect']);
	}

	function logout_url($attr) {
		return wp_logout_url($attr['redirect']);
	}

	function loginout($attr) {
		if (!is_user_logged_in())
			$link = '<a href="'.esc_url(wp_login_url($attr['redirect'])).'">'.__('Log in', 'suffusion').'</a>';
		else
			$link = '<a href="'.esc_url(wp_logout_url($attr['redirect'])).'">'.__('Log out', 'suffusion').'</a>';

		$filtered = apply_filters('loginout', $link);
		return $filtered;
	}

	function register($attr) {
		$before = $attr['before'] ? $attr['before'] : '<li>';
		$after = $attr['after'] ? $attr['after'] : '</li>';
		if (!is_user_logged_in()) {
			if (get_option('users_can_register'))
				$link = $before.'<a href="'.site_url('wp-login.php?action=register', 'login').'">'.__('Register', 'suffusion').'</a>'.$after;
			else
				$link = '';
		}
		else {
			$link = $before.'<a href="'.admin_url().'">'.__('Site Admin', 'suffusion').'</a>'.$after;
		}

		$filtered = apply_filters('register', $link);
		return $filtered;
	}

	function ad($attr) {
		$params = array('client', 'slot', 'width', 'height');
		$provider = 'google';
		$provider_type = 'syndication';
		$service = 'ad';
		$service_type = 'page';
		$ret = "<div id='".$service."sense'>\n<script type='text/javascript'><!--\n";
		foreach ($params as $var) {
			$ret .= "\t".$provider."_".$service."_$var = '".$attr[$var]."';\n";
		}
		$ret .= "//-->\n</script>\n";
		$service_url = "http://".$service_type.$service."2.$provider$provider_type.com/$service_type$service/show_{$service}s.js";
		$ret .= "<script type='text/javascript' src='$service_url'></script>\n";
		$ret .= "</div>\n";
		return $ret;
	}

	function tag_cloud($attr) {
		if (isset($attr['smallest'])) $attr['smallest'] = (int)$attr['smallest'];
		if (isset($attr['largest'])) $attr['largest'] = (int)$attr['largest'];
		if (isset($attr['number'])) $attr['number'] = (int)$attr['number'];
		$attr['echo'] = false;
		return wp_tag_cloud($attr);
	}

	/**
	 * Creates an ad hoc widget area based on parameters passed to it. To use this feature you have to add widgets to the corresponding
	 * Ad Hoc widget areas in your administration panel. The syntax for this short code is [suffusion-widgets id='2' container='false' class='some-class'].
	 * The 'id' refers to the index of the ad hoc widget area and can be anything from 1 to 5.
	 * The 'container' parameter, if set to false, will not put the widgets in a container. Otherwise the container will have the id "ad-hoc-[id]", where [id] is the id that you passed.
	 * The 'container-class' parameter assigns the passed class to the container. If the 'container' parameter is false then this is ignored.
	 *
	 * @param  $attr
	 * @return string
	 */
	function widget_area($attr) {
		$id = 1;
		if (isset($attr['id'])) {
			$id = (int)$attr['id'];
		}
		$container = isset($attr['container']) ? (bool)$attr['container'] : true;
		$sidebar_class = isset($attr['container_class']) ? $attr['container_class'] : "";
		ob_start(); // Output buffering is needed here otherwise there is no way to get the dynamic_sidebar output added to existing text
		if ($container) echo "<div id='ad-hoc-$id' class='$sidebar_class warea'>\n";
		dynamic_sidebar("Ad Hoc Widgets $id");
		if ($container) echo "</div>\n";
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	/**
	 * Creates the container for multi-column content, corresponding to the short code [suffusion-multic].
	 * No attributes are required for the short code. This should be used in conjunction with the [suffusion-column] short code.
	 *
	 * @param  $attr
	 * @param  $content
	 * @return string
	 */
	function multi_column($attr, $content = null) {
		$content = do_shortcode($content);
		return "<div class='suf-multic'>".$content."</div>";
	}

	/**
	 * Creates a column within the multi-column layout, corresponding to the short code [suffusion-column].
	 * This is to be invoked inside the [suffusion-multic] short code for best results.
	 * This short code takes a parameter called "width" and an optional parameter called "class".
	 * The "width" parameter can have the values 1, 1/2, 1/3, 1/4, 2/3, 3/4, 100, 050, 033, 025, 066 and 075. The default is 1.
	 * You can have a layout such as this:
	 * [suffusion-multic]
	 *      [suffusion-column width='1/3']Some content in one-third the width[/suffusion-column]
	 *      [suffusion-column width='2/3']Some content in two-third the width[/suffusion-column]
	 * [/suffusion-multic]
	 * Or:
	 * [suffusion-multic]
	 *      [suffusion-column width='1/4']Some content in one-fourth the width[/suffusion-column]
	 *      [suffusion-column width='1/2']Some more content in half the width[/suffusion-column]
	 *      [suffusion-column width='1/4']Yet some more content in one-fourth the width[/suffusion-column]
	 * [/suffusion-multic]
	 * You are responsible for balancing the widths - the theme will not do that automatically for you.
	 *
	 * @param  $attr
	 * @param  $content
	 * @return string
	 */
	function column($attr, $content = null) {
		$content = do_shortcode($content);
		$width = isset($attr['width']) ? $attr['width'] : "1";
		$class = isset($attr['class']) ? $attr['class'] : "";
		$base_class = "suf-mc-100";
		switch ($width) {
			case "1/4":
			case "025":
				$base_class = "suf-mc-col-025";
				break;

			case "1/3":
			case "033":
				$base_class = "suf-mc-col-033";
				break;

			case "1/2":
			case "050":
				$base_class = "suf-mc-col-050";
				break;

			case "2/3":
			case "066":
				$base_class = "suf-mc-col-066";
				break;

			case "3/4":
			case "075":
				$base_class = "suf-mc-col-075";
				break;

			case "1":
			case "100":
			default:
				$base_class = "suf-mc-col-100";
				break;
		}
		return "<div class='suf-mc-col $base_class $class'>".$content."</div>";
	}

	/**
	 * Prints a Flickr stream. The short code takes the following arguments:
	 *  - id: Mandatory, can be obtained from http://idgettr.com using your photo stream's URL
	 *  - type: Mandatory. Legitimate values: user, group.
	 *  - size: Optional. Values: s (square), t (thumbnail), m (mid-size). Default: s
	 *  - number: Optional. Default: 10
	 *  - order: Optional. Values: latest, random. Default: latest
	 *  - layout: Optional. Values: h (horizontal), v (vertical), x (no layout - user-styled). Default: x
	 *
	 * @param  $attr
	 * @return string
	 */
	function flickr($attr) {
		if (!isset($attr['id']) || !isset($attr['type'])) {
			return "";
		}
		$id = $attr['id'];
		$type = $attr['type'];
		$size = isset($attr['size']) ? $attr['size'] : 's';
		$number = isset($attr['number']) ? $attr['number'] : 10;
		$order = isset($attr['order']) ? $attr['order'] : 'latest';
		$layout = isset($attr['layout']) ? $attr['layout'] : 'x';

		return "<div class='suf-flickr-stream'><script type=\"text/javascript\" src=\"http://www.flickr.com/badge_code_v2.gne?count=$number&amp;display=$order&amp;size=$size&amp;layout=$layout&amp;source=$type&amp;$type=$id\"></script></div>";
	}

	function audio($atts) {
		return suffusion_sc_audio($atts);
	}

	function shortcode_string_to_bool($value) {
		if ($value == true || $value == 'true' || $value == 'TRUE' || $value == '1') {
			return true;
		}
		else if ($value == false || $value == 'false' || $value == 'FALSE' || $value == '0') {
			return false;
		}
		else {
			return $value;
		}
	}

}

add_action('init', 'init_suffusion_shortcodes');
function init_suffusion_shortcodes() {
	global $Suffusion_Shortcodes;
	$Suffusion_Shortcodes = new Suffusion_Shortcodes();
}

if (!function_exists('suffusion_sc_audio')) {
	function suffusion_sc_audio($atts) {
		global $suffusion_audio_instance;
		if (!isset($suffusion_audio_instance)) {
			$suffusion_audio_instance = 1;
		}
		else {
			$suffusion_audio_instance++;
		}

		if (!isset($atts[0]))
			return '';

		if (count($atts))
			$atts[0] = join(' ', $atts);

		$src = rtrim($atts[0], '=');
		$src = trim($src, ' "');
		$data = preg_split("/[\|]/", $src);

		$sound_file = $data[0];

		return "<p id=\"audioplayer_$suffusion_audio_instance\"></p><script type=\"text/javascript\">AudioPlayer.embed(\"audioplayer_$suffusion_audio_instance\", {soundFile: \"$sound_file\", width: 300, initialvolume: 100, transparentpagebg: 'yes', left: '000000', lefticon: 'FFFFFF' });</script>";
	}
}