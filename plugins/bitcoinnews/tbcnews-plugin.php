<?php
	/*
	  Plugin Name: BitcoinNews
	  Plugin URI: http://TheBitcoinNews.com/
	  Description: Add Bitcoin News to your website or blog. Choose keywords, number of news articles and other settings, put the Bitcoin News wherever you want using widgets or shortcodes, and watch the fresh relevant more than 350 Bitcoin News headlines per week appear on your pages.
	  Author: TheBitcoinNews.com
	  Version: 1.0.6
	  Author URI: TheBitcoinNews.com
	 */

	// Prevent from being run directly.
	defined('ABSPATH') or die("No chance!");

	// Include the fetch_feed functionality (to be replaced eventually).
	include_once( ABSPATH . WPINC . '/feed.php' );

	require_once( plugin_dir_path(__FILE__) . 'tbcnews-plugin-widget.php' );
	require_once( plugin_dir_path(__FILE__) . 'tbcnews-plugin-utils.php' );

  /*
  add_filter( 'the_permalink_rss', 'tbcnews_change_feed_item_url' );  
  function tbcnews_change_feed_item_url( $url ) {
    $parts = parse_url( $url );
    return $parts['scheme'] . '://' . $parts['host'] . $parts['path'] . '/#!view';
  }
  */
  
	class TBCNews_Plugin {

		function __construct() {
			// Widgets.
			add_action('widgets_init', array($this, 'widgets_init'));
			add_action('admin_init', array($this, 'admin_init'));
			add_action('admin_menu', array($this, 'admin_menu'));
			add_action('admin_init', array(&$this, 'register_help_section'));
			add_action('admin_init', array(&$this, 'register_shortcode_section'));
			//add_action('admin_init', array(&$this, 'register_style_section'));
			add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));
			add_action('wp_enqueue_scripts', array($this, 'register_styles'));

			add_action('admin_init', array($this, 'refresh_plugin_version'));

			register_deactivation_hook(__FILE__, array($this, 'tbcnews_deactivation'));
		}

		function tbcnews_deactivation() {
			
		}

		function refresh_plugin_version() {
			if (function_exists('get_plugin_data')) {
				$xtime = get_option('tbcnews_plugin_version_taken');
				$mtime = filemtime(plugin_dir_path(__FILE__) . 'tbcnews-plugin.php');
				if ($mtime > $xtime) {
					TBCNews_Plugin_Utils::np_version_hard();
				}
			}
		}

		/**
		 * Register the plugin widget, widget areas and widget shorcodes.
		 */
		function widgets_init() {
			register_widget('TBCNews_Plugin_Widget');
			for ($area = 1; $area <= 4; $area++) {
				register_sidebar(array(
					'name' => "TheBitcoinNews Widget Area {$area}",
					'id' => "tbcnewsplugin_widgets_{$area}",
					'description' => "Use the [tbcnewsplugin_widgets&nbsp;area={$area}] shortcode to show the Bitcoin News anywhere you want.",
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget' => '</div>'
				));
			}
			add_shortcode('tbcnewsplugin_widgets', array($this, 'widget_area_shortcode'));
			add_shortcode('tbcnewsplugin_feed', array($this, 'feed_shortcode'));
		}

		/**
		 * Process the widget area shortcode.
		 */
		function widget_area_shortcode($attrs) {
			$a = shortcode_atts(array('area' => '1'), $attrs);
			$sidebar = "tbcnewsplugin_widgets_{$a['area']}";
			ob_start();
			if (is_active_sidebar($sidebar)) {
				echo '<div class="tbcnewsplugin_widget_area">';
				dynamic_sidebar($sidebar);
				echo '</div>';
			}
			return ob_get_clean();
		}

		//[feed_shortcode title="" keywords="News" count="" age="" search_mode="" search_type="" link_type="" show_date="" show_abstract=""]

		/**
		 * Process the newsfeed shortcode.
		 */
		function feed_shortcode($attrs) {
			$attrs = shortcode_atts(array(
				'id' => '',
				'title' => 'Bitcoin News',
				'partner_id' => '',
				'count' => '',
				'age' => '',
				'search_type' => '',
				'link_open_mode' => '',
				'link_follow' => '',
				'link_type' => '',
				'show_date' => '',
				'show_abstract' => '',
				'show_premium_only' => '',
				'show_image' => '',
				'wp_uid' => ''
				), $attrs);
			$wid = new TBCNews_Plugin_Widget();
			$a = $wid->update($attrs, array());
			$a['id'] = $attrs['id'];
			ob_start();
			the_widget('TBCNews_Plugin_Widget', $a, array());
			return ob_get_clean();
		}

		/**
		 * Register the plugin CSS style.
		 */
		function register_styles() {
			wp_register_style('tbcnews-plugin', plugins_url('thebitcoinnews/assets/css/tbcnews-plugin.css'), array(), "0.1");
			wp_enqueue_style('tbcnews-plugin');
		}

		function register_admin_scripts() {
			$assets_path = plugins_url('thebitcoinnews/assets/');
			wp_enqueue_style('tbcnews-plugin', $assets_path . 'css/tbcnews-plugin.css');
		}

		/**
		 * Register the plugin options.
		 */
		function admin_init() {
			add_settings_section(
				'default', NULL, NULL, 'tbcnews-plugin-settings'
			);
		}

		/**
		 * Register the plugin menu.
		 */
		function admin_menu() {
			add_menu_page(
				__('TheBitcoinNews Settings', 'tbcnews_plugin'), __('TheBitcoinNews', 'tbcnews_plugin'), 'manage_options', 'tbcnews-plugin-settings', array($this, 'tbcnewsplugin_options_page'), 'dashicons-rss', '3'
			);
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_action_links'));
		}

		/*
		 * For easier overriding I declared the keys
		 * here as well as our tabs array which is populated
		 * when registering settings
		 */

		private $status_settings_key = 'newsplugin_status_settings';
		private $feed_settings_key = 'tbcnewsplugin_feed_settings';
		//private $style_settings_key = 'newsplugin_style_settings';
		private $activation_settings_key = 'newsplugin_activation_settings';
		private $shortcode_settings_key = 'newsplugin_shortcode_settings';
		private $help_settings_key = 'newsplugin_help_settings';
		private $plugin_options_key = 'tbcnews-plugin-settings';
		private $plugin_settings_tabs = array();

		function register_shortcode_section() {
			$this->plugin_settings_tabs[$this->shortcode_settings_key] = 'Generate Shortcode';
		}

		function register_help_section() {
			$this->plugin_settings_tabs[$this->help_settings_key] = 'Instructions';
		}

		function get_with_default($arr, $a, $b, $def) {
			if (!is_array($arr)) {
				return $def;
			}
			if (!isset($arr[$a])) {
				return $def;
			}
			if (!isset($arr[$a][$b])) {
				return $def;
			}
			return $arr[$a][$b];
		}

		/*
		 * Plugin Options page
		 */

		function tbcnewsplugin_options_page() {
			$tab = isset($_GET['tab']) ? $_GET['tab'] : $this->help_settings_key;
			?>
			<div class="wrap">
				<h2>The Bitcoin News Settings</h2>
				<?php $this->tbcnewsplugin_options_tabs($tab); ?>
				<?php
				$key = 'uE6DXfQFbDXY4QE1BKQNoaxCdPq_GDOA';
				if ($tab === $this->activation_settings_key) {
					?>
					<form method="post" action="options.php">
						<?php wp_nonce_field('update-options'); ?>
						<?php settings_fields($this->plugin_options_key); ?>
						<?php do_settings_sections($this->plugin_options_key); ?>
					<?php submit_button(); ?>
					</form>
			<?php } else if ($tab === $this->shortcode_settings_key && !empty($key)) { ?>
					<table id="shortcodeTable" class="form-table">
						<tr>
							<th scope="row">
								<label for="newsplugin_title">Feed Title: </label>
							</th>
							<td>
								<input type="text" id="newsplugin_title" name="newsplugin_title" value="Bitcoin News" class="regular-text" onclick="validationFocus('newsplugin_title')" onfocus="validationFocus('newsplugin_title')">
								<p class="description">Give your feed a good name. For example: Bitcoin News or Cryptocurrency News</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Premium content only :</th>
							<td>
								<fieldset>
									<label for="newsplugin_more_premium"><input type="checkbox" checked id="newsplugin_more_premium" name="newsplugin_more_premium"></label>
									<br>
									
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="newsplugin_url">Partner ID: </label>
							</th>
							<td>
								<input type="text" id="newsplugin_partner_id" name="newsplugin_partner_id" value="" class="regular-text">
								<p class="description">Add your Partner ID - Earn Cash with Premium News ( Sign up here: <a href="https://thebitcoinnews.com/affiliates/" target="_blank">https://thebitcoinnews.com/affiliates/</a> ).</p>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="newsplugin_articles">Number of Articles: </label>
							</th>
							<td>
								<input type="text" id="newsplugin_articles" name="newsplugin_articles" value="10" class="regular-text" onclick="validationFocus('newsplugin_articles')" onfocus="validationFocus('newsplugin_articles')">
								<p class="description">Set how many headlines to show in your feed. Example: 10</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Information to show:</th>
							<td>
								<fieldset>
									<label for="newsplugin_more_dates"><input type="checkbox" checked id="newsplugin_more_dates" name="newsplugin_more_dates">Show Dates</label>
									<br>
									<label for="newsplugin_more_abstracts"><input type="checkbox" checked id="newsplugin_more_abstracts" name="newsplugin_more_abstracts">Show Abstracts</label>
									<br>
									<p class="description">By default, the feed displays headlines, dates and content</p>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row">Image Showing:</th>
							<td>
								<fieldset>
									<label for="newsplugin_more_image"><input type="checkbox" checked id="newsplugin_more_image" name="newsplugin_more_image">Show Image</label>
									<br>
									<p class="description">By default, the feed displays image</p>
								</fieldset>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<label for="newsplugin_link_open">Link-Open Mode: </label>
							</th>
							<td>
								<select id="newsplugin_link_open" name="newsplugin_link_open">
									<option value="_blank">New Tab</option>
									<option value="_self">Same Window</option>
								</select>
								<p class="description">Open link in same window or new tab. Default is new tab.</p>
							</td>
						</tr>
					</table>
					<p class="submit">
				<?php add_thickbox(); ?>
					<div id="shortcode-generated" style="display:none;"></div>
					<input type="button" id="shortcode_button" value="Generate Shortcode" class="button button-primary" onclick="validateShortcode()">
					</p>
					<script type="text/javascript">
				    function validationFocus(id) {
				      document.getElementById(id).style.border = "1px solid #ddd";
				      document.getElementById(id).style.boxShadow = "0 1px 2px rgba(0, 0, 0, 0.07) inset";
				    }
				    function validateKeyword()
				    {
				      var newsplugin_keywords = document.getElementById('newsplugin_keywords');
				      var newsplugin_keywords_value = document.getElementById('newsplugin_keywords').value.toLowerCase();
				      var keyword_suggestion = document.getElementById('keyword_suggestion');
				      var or = newsplugin_keywords_value.indexOf(" or ");
				      var and = newsplugin_keywords_value.indexOf(" and ");
				      var comma = newsplugin_keywords_value.indexOf(",");
				      var suggestion = '';
				      if (or > 0 || and > 0 || comma > 0) {
				        newsplugin_keywords.style.border = "1px solid #ff0000";
				        newsplugin_keywords.style.boxShadow = "0 1px 2px rgba(255, 0, 0, 0.07) inset";
				        suggestion = "<span style='color:red;'>You are using an invalid syntax.<br>Please consider using the suggestion below:</span><br>";
				        var text = newsplugin_keywords_value.replace(/ or /g, " | ");
				        text = text.replace(/ and /g, " & ");
				        text = text.replace(/,/g, " | ");
				        suggestion += "<span style='color:#000;font-weight:bold;font-style:normal;'>" + text + "</span>";
				        suggestion += "<br><br><p style='font-style:normal;font-weight:bold;margin-top:10px'>Keyword Tips:</p>";
				        suggestion += "<ul style='margin-top:5px;list-style: inside none disc;'><li><strong>Symbol | stands for OR</strong><br>Using the | symbol gives you articles for every keyword in your search string.</li><li><strong>Symbol &amp; stands for AND</strong><br>Using the &amp; symbol gives you only those articles that contain all keywords in your search string.</li><li><strong>Quotation marks</strong><br>Using quotation marks ' ' limits your search for exact phrases.</li><li><strong>Asterisk sign</strong><br>Using an asterisk sign * gives you variations of the root keyword. You cannot use it in phrases.</li><li><strong>Parenthesis</strong><br>You can use parenthesis ( ) to adjust the priority of your search phrase evaluation (as common math/boolean expressions).</li></ul><br><br>";
				      }
				      keyword_suggestion.innerHTML = suggestion;
				    }
				    function validateShortcode() {
				      var newsplugin_title = document.getElementById('newsplugin_title');
				      var newsplugin_keywords = document.getElementById('newsplugin_keywords');
				      var newsplugin_articles = document.getElementById('newsplugin_articles');
				      if (newsplugin_title.value === "" || /^\s*$/.test(newsplugin_title.value) || newsplugin_articles.value === "" || /^\s*$/.test(newsplugin_articles.value) || isNaN(newsplugin_articles.value) || parseInt(newsplugin_articles.value) <= 0) {
				        if (newsplugin_title.value === "" || /^\s*$/.test(newsplugin_title.value)) {
				          newsplugin_title.style.border = "1px solid #ff0000";
				          newsplugin_title.style.boxShadow = "0 1px 2px rgba(255, 0, 0, 0.07) inset";
				        }
				        /*
				         if (newsplugin_keywords.value == "" || /^\s*$/.test(newsplugin_keywords.value)) {
				         newsplugin_keywords.style.border = "1px solid #ff0000";
				         newsplugin_keywords.style.boxShadow = "0 1px 2px rgba(255, 0, 0, 0.07) inset";
				         }
				         */
				        if (newsplugin_articles.value === "" || /^\s*$/.test(newsplugin_articles.value) || isNaN(newsplugin_articles.value) || parseInt(newsplugin_articles.value) <= 0) {
				          newsplugin_articles.style.border = "1px solid #ff0000";
				          newsplugin_articles.style.boxShadow = "0 1px 2px rgba(255, 0, 0, 0.07) inset";
				        }
				        window.scrollTo(0, 0);
				        if (!jQuery(".error").length) {
				          jQuery("<div class='error'><p>Fill the required fields properly.</p></div>").insertBefore("#shortcodeTable");
				        }
				      } else {
				        window.scrollTo(0, 0);
				        generateShortcode();
				        jQuery(".error").hide();
				      }
				    }
				    function generateShortcode() {
				      var shortcode_params = "";
				      var owns = Object.prototype.hasOwnProperty;
				      var key;
					  var bool_opts1 = new Object({newsplugin_more_premium: 'show_premium_only'});
				      for (key in bool_opts1) {
				        if (owns.call(bool_opts1, key)) {
				          var value = document.getElementById(key).checked;
				          if (value) {
				             shortcode_params += " " + bool_opts1[key] + "='true'";
				          }
						  else
						  {
							  shortcode_params += " " + bool_opts1[key] + "='false'";
						  }
				        }
				      }
				      var str_opts = new Object({newsplugin_title: 'title', newsplugin_partner_id: 'partner_id',  newsplugin_link_open: 'link_open_mode'});
				      for (key in str_opts) {
				        if (owns.call(str_opts, key)) {
				          var value = document.getElementById(key).value;
				          if (value !== "") {
				            shortcode_params += " " + str_opts[key] + "='" + value + "'";
				          }
				        }
				      }
				      var bool_opts = new Object({newsplugin_more_dates: 'show_date', newsplugin_more_abstracts: 'show_abstract',newsplugin_more_image: 'show_image'});
				      for (key in bool_opts) {
				        if (owns.call(bool_opts, key)) {
				          var value = document.getElementById(key).checked;
				          if (value) {
				            shortcode_params += " " + bool_opts[key] + "='true'";
				          }
						  else
						  {
							  shortcode_params += " " + bool_opts[key] + "='false'";
						  }
				        }
				      }
				      var newsplugin_articles = Math.abs(parseInt(document.getElementById('newsplugin_articles').value));
				      if (newsplugin_articles !== "" && !isNaN(newsplugin_articles)) {
				        shortcode_params += " count='" + newsplugin_articles + "'";
				      }
				<?php /*
				  var newsplugin_age = Math.abs(parseInt(document.getElementById('newsplugin_age').value));
				  if (newsplugin_age != "" && !isNaN(newsplugin_age)) {
				  shortcode_params += " age='" + newsplugin_age + "'";
				  }
				 * 
				 */ ?>
				      shortcode_params += " wp_uid='<?php echo get_current_user_id(); ?>'";
				      var html = "<p>Press Ctrl+C to copy to clipboard and paste it in your posts or pages.</p>";
				      html += "<p><textarea id='shortcode-field' onfocus='this.select()' onclick='this.select()' readonly='readonly' style='width:400px; height:200px; max-width:400px; max-height:200px; min-width:400px; min-height:200px;'>[tbcnewsplugin_feed id='" + new Date().valueOf() + "'" + shortcode_params + "]</textarea></p>";
				      document.getElementById('shortcode-generated').innerHTML = html;
				      tb_show("TheBitcoinNews Plugin Shortcode Generated", "#TB_inline?width=410&height=305&inlineId=shortcode-generated");
				      document.getElementById('shortcode-field').focus();
				      return false;
				    }
					</script>
			<?php } else if ($tab === $this->help_settings_key) { ?>
					<h3>Instructions</h3>
					<p>Please read the instructions below carefully to easily setup and use the TheBitcoinNews.com Plugin.</p>
					<p><strong>Create TheBitcoinNews feeds:</strong><br>Create your Bitcoin News by generating a shortcode from <a href="<?php echo admin_url('admin.php?page=tbcnews-plugin-settings&tab=' . $this->shortcode_settings_key) ?>">Generate Shortcode</a> tab. Put that shortcode in posts or pages where you want to display your newsfeed.<br>OR<br>create your TheBitcoinNews feed from <a href="<?php echo admin_url('widgets.php') ?>">Appearance &gt; Widgets</a>. From the widgets panel drag the "TheBitcoinNews Plugin" widget to the desired sidebar or widget area where you want to show your newsfeed. Edit the widget features to create/edit the newsfeed. <br><br>Partner ID - Earn Cash with Premium News ( Sign up here: <a href="https://thebitcoinnews.com/affiliates/" target="_blank">https://thebitcoinnews.com/affiliates/</a> ).</p>
					<?php }
				?>
			</div>
			<?php
		}

		function tbcnewsplugin_options_tabs($current_tab) {
			echo '<h2 class="nav-tab-wrapper">';
			foreach ($this->plugin_settings_tabs as $tab_key => $tab_caption) {
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
			}
			echo '</h2>';
		}

		function add_action_links($default_links) {
			$links = array(
				'<a href="' . admin_url('admin.php?page=tbcnews-plugin-settings') . '">Settings</a>',
			);
			return array_merge($links, $default_links);
		}

	}

	new TBCNews_Plugin();
?>
