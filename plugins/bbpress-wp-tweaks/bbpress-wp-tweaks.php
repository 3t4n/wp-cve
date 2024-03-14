<?php
/*

 * *************************************************************************

  Plugin Name:  bbPress WP Tweaks
  Plugin URI:   https://veppa.com/bbpress-wp-tweaks/
  Description:  Adds bbPress forum specific sidebar, wrapper, widgets, user columns, login links and other tweaks.
  Version:      1.4.4
  Author:       veppa
  Author URI:   https://veppa.com/
  Text Domain:	bbpress-wp-tweaks
  Domain Path: /languages
  License:      GPL2

 * *************************************************************************

  Copyright (C) 2012 veppa

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

 * ************************************************************************ */

// Exit if accessed directly
if (!defined('ABSPATH'))
{
	exit;
}

// Seems some themes bundle this plugin and then users install it,
// resulting in double output. This should be avoided, so use a global.
if (!isset($bbpress_wp_tweaks))
{
	$bbpress_wp_tweaks = false;
}

if (!class_exists('BbpressWpTweaks')) :

	class BbpressWpTweaks
	{

		public $version = '1.4.4';
		public $sidebar_id = 'sidebar-bbpress';
		public $bbp_templates = array('index.php', 'page.php', 'single.php');
		public $bbp_templates_extra = array('index.php', 'page.php', 'single.php', 'home.php', 'front-page.php', 'archive.php', 'bbpress.php');
		public $default_login_btn_template;
		private static $is_bbp;

		const ID = 'bbpress-wp-tweaks';

		/**
		 * Holds the values to be used in the fields callbacks
		 */
		private static $options;

		// Class initialization
		function __construct()
		{
			if (!function_exists('add_action'))
			{
				return;
			}
			// Load up the localization file if we're using WordPress in a different language
			// Place it in this plugin's "languages" folder and name it "car-[value in wp-config].mo"
			load_plugin_textdomain('bbpress-wp-tweaks', false, '/bbpress-wp-tweaks/languages');

			// add menu and link to settings
			add_action('admin_menu', array(&$this, 'AddAdminMenu'));
			add_filter('plugin_action_links', array(&$this, 'filter_plugin_actions'), 10, 2);


			// check if we have bbpress activated 
			if (!$this->is_bbp_active())
			{
				return;
			}

			// add sidebar
			add_action('wp_head', array($this, 'action_set_customizer_sidebar'));
			add_action('widgets_init', array(&$this, 'action_register_sidebar'));


			add_filter('sidebars_widgets', array(&$this, 'filter_sidebars_widgets'));
			add_filter('is_active_sidebar', array(&$this, 'filter_is_active_sidebar'), 10, 2);

			// display selected forum wrapper
			if (function_exists('bbp_get_query_template'))
			{
				// this is fix to work in bbpress vrestion >= 2.1 
				add_filter('bbp_get_bbpress_template', array(&$this, 'filter_bbp_get_theme_compat_templates'));
			}
			else
			{
				// this will work in bbpress version 2
				add_filter('bbp_get_theme_compat_templates', array(&$this, 'filter_bbp_get_theme_compat_templates'));
			}



			// defaine defaults
			$this->default_login_btn_template = '<p><a class="button" href="{login_url}">{login_text}</a> / <a class="button" href="{register_url}">{register_text}</a></p>';


			$this->do_tweaks();
		}

		function _description()
		{
			_e('Adds bbPress forum specific sidebar, wrapper, widgets, user columns, login links and other tweaks.', 'bbpress-wp-tweaks');
		}

		static public function setup()
		{
			global $BbpressWpTweaks;
			$BbpressWpTweaks = new BbpressWpTweaks();
		}

		/**
		 * Update database values for this plugin if upgrading from version 1.3.1
		 * if not upgrading then no need to set defaults. default values will be used without saving when requested by plugin 
		 */
		function update_plugin_DB()
		{
			// Move "default_wrapper_file" option to "bbpress-wp-tweaks['wrapper']" if it has old stored value in DB			
			$old_default_wrapper_file = get_option('default_wrapper_file');
			if (strlen($old_default_wrapper_file))
			{
				// also set other defaults here 
				$arr_defaults = array(
					'wrapper'					 => $old_default_wrapper_file,
					'wrapper_custom'			 => '',
					'sidebar_action'			 => 'replace',
					'sidebar_target'			 => $this->get_option_sidebar_target(),
					'login_btn_show'			 => 1,
					'login_btn_template'		 => $this->default_login_btn_template,
					'show_description'			 => '',
					'show_description_parents'	 => '',
					'show_description_topic'	 => '',
					'show_description_reply'	 => '',
					'author_redirect'			 => 1,
					'user_columns'				 => 1,
					'widget_login'				 => 1,
					'widget_users'				 => 1
				);

				// Save defaults with old wrapper file 
				update_option(self::ID, $arr_defaults);

				// delete old option 
				delete_option('default_wrapper_file');
			}
		}

		/**
		 * Perform tweaks based on plugin settings
		 */
		function do_tweaks()
		{

			add_action('wp_enqueue_scripts', array(&$this, 'callback_for_setting_up_scripts'));
			add_action('admin_enqueue_scripts', array(&$this, 'callback_for_setting_up_scripts'));


			/* define actions if selected in settings */
			if (self::get_option('login_btn_show', 1))
			{
				// bbp_template_after_single_topic
				add_action('bbp_template_after_single_topic', array(&$this, 'action_display_login_buttons'));
				add_action('bbp_template_after_single_forum', array(&$this, 'action_display_login_buttons'));
			}

			if (self::get_option('show_description_parents'))
			{
				//filter to add description after forums titles on forum index
				add_action('bbp_template_before_single_forum', array(&$this, 'action_show_description_parents'));
			}
			if (self::get_option('show_description'))
			{
				//filter to add description after forums titles on forum index
				add_action('bbp_template_before_single_forum', array(&$this, 'action_show_description'));
			}



			if (self::get_option('show_description_topic'))
			{
				//filter to add description after forums titles on forum index
				add_action('bbp_template_before_single_topic', array(&$this, 'action_show_description_topic'));
			}

			if (self::get_option('show_description_reply'))
			{
				//filter to add description after forums titles on forum index
				add_action('bbp_template_before_single_reply', array(&$this, 'action_show_description_reply'));
			}


			if (self::get_option('author_redirect', 1))
			{
				add_action('template_redirect', array(&$this, 'action_author_redirect'));
			}


			// setup admin side user columns with sorting 
			if (self::get_option('user_columns', 1))
			{
				// ADMIN COLUMN - HEADERS
				add_filter('manage_users_columns', array(&$this, 'user_columns_add'));

				// ADMIN COLUMN - CONTENT
				add_action('manage_users_custom_column', array(&$this, 'user_columns_manage'), 10, 3);

				// ADMIN COLUMN - SORTING - MAKE HEADERS SORTABLE	 * https://gist.github.com/906872
				add_filter("manage_users_sortable_columns", array(&$this, 'user_columns_sort'));

				// Perform sort action using related meta values 
				add_action('pre_user_query', array(&$this, 'user_columns_sort_action'));
			}


			// add widgets
			if (self::get_option('widget_login', 1))
			{
				add_action('widgets_init', array('BbpressWpTweaks_Login_Links_Widget', 'register_widget'));
			}

			if (self::get_option('widget_users', 1))
			{
				add_action('widgets_init', array('BbpressWpTweaks_Users', 'register_widget'));
			}

			// track last activity time
			//add_action('plugins_loaded', array(&$this, 'action_last_activity'));
			$this->action_lastlogin();
		}

		/**
		 * Update active user lastlogin time if it is older than 15 minutes
		 */
		function action_lastlogin()
		{
			if (is_user_logged_in())
			{
				$timeframe_minut = 15;
				$user = wp_get_current_user();
				//var_dump($user);
				$user_id = $user->ID;
				$time = time();
				$last_login = get_user_meta($user_id, 'last_login', true);
				if ($last_login < $time - ($timeframe_minut * MINUTE_IN_SECONDS))
				{
					// update value 
					update_user_meta($user_id, 'last_login', $time);
				}
			}
		}

		/**
		 * Display last login time
		 *
		 */
		function lastlogin($user_id = null)
		{
			if (is_null($user_id))
			{
				$author = get_the_author();
				$user_id = $author->ID;
			}

			if ($user_id)
			{
				$last_login = get_the_author_meta('last_login', $user_id);
				if ($last_login)
				{
					$the_login_date = human_time_diff($last_login);
					return sprintf(__('%s ago', 'bbpress-wp-tweaks'), $the_login_date);
				}
			}

			return;
		}

		function user_columns_add($columns)
		{
			$columns['topic'] = __('Topics', 'bbpress-wp-tweaks');
			$columns['reply'] = __('Replies', 'bbpress-wp-tweaks');
			$columns['reg_date'] = __('Registration date', 'bbpress-wp-tweaks');
			$columns['login_date'] = __('Last active date', 'bbpress-wp-tweaks');
			return $columns;
		}

		function user_columns_manage($val, $column_name, $id)
		{
			//echo '[$column_name:'.$column_name.',$id:'.$id.']';
			//global $post;
			switch ($column_name)
			{
				case 'topic':
					//$num = count_user_posts($id, 'topic', true);
					$num = bbp_get_user_topic_count($id, true);
					if ($num)
					{
						return '<a href="' . esc_url(bbp_get_user_topics_created_url($id)) . '">' . $num . '</a>';
					}
					break;
				case 'reply':
					//$num = count_user_posts($id, 'reply', true);
					$num = bbp_get_user_reply_count($id, true);
					if ($num)
					{
						return '<a href="' . esc_url(bbp_get_user_replies_created_url($id)) . '">' . $num . '</a>';
					}
					break;
				case 'reg_date':
					//return date('Y-m-d H:i:s', strtotime(get_userdata($id)->user_registered));
					return get_date_from_gmt(get_userdata($id)->user_registered);
					break;
				case 'login_date':
					return $this->lastlogin($id);
					break;
				default:
					break;
			} // end switch

			return $val;
		}

		function user_columns_sort($columns)
		{

			$custom = array(
				'topic'		 => '_bbp_topic_count',
				'reply'		 => '_bbp_reply_count',
				'reg_date'	 => 'user_registered',
				'login_date' => 'last_login'
			);
			return wp_parse_args($custom, $columns);
			/* or this way
			  $columns['concertdate'] = 'concertdate';
			  $columns['city'] = 'city';
			  return $columns;
			 */
		}

		function user_columns_sort_action($userquery)
		{
			global $wpdb;

			$orderby = $userquery->query_vars['orderby'];
			$order = ($userquery->query_vars["order"] == "ASC" ? "ASC " : "DESC ");

			switch ($orderby)
			{
				case '_bbp_topic_count':
					$userquery->query_from .= " LEFT OUTER JOIN $wpdb->usermeta AS alias ON ($wpdb->users.ID = alias.user_id) "; //note use of alias
					$userquery->query_where .= " AND alias.meta_key = 'wp__bbp_topic_count' "; //which meta are we sorting with?
					$userquery->query_orderby = " ORDER BY alias.meta_value +0 " . $order; //set sort order
					break;
				case '_bbp_reply_count':
					$userquery->query_from .= " LEFT OUTER JOIN $wpdb->usermeta AS alias ON ($wpdb->users.ID = alias.user_id) "; //note use of alias
					$userquery->query_where .= " AND alias.meta_key = 'wp__bbp_reply_count' "; //which meta are we sorting with?
					$userquery->query_orderby = " ORDER BY alias.meta_value +0 " . $order; //set sort order
					break;
				case 'last_login':
					$userquery->query_from .= " LEFT OUTER JOIN $wpdb->usermeta AS alias ON ($wpdb->users.ID = alias.user_id) "; //note use of alias
					$userquery->query_where .= " AND alias.meta_key = 'last_login' "; //which meta are we sorting with?
					$userquery->query_orderby = " ORDER BY alias.meta_value " . $order; //set sort order
					break;
			}
		}

		/**
		 * Show forum description 
		 */
		function action_show_description()
		{
			$this->_action_show_description(bbp_get_forum_id());
		}

		/**
		 * Show parent forum descriptions
		 */
		function action_show_description_parents()
		{
			// get an array of all the parent pages in order from most distant to closest.
			$parent = $this->_get_post_ancestors_reversed();

			foreach ($parent as $parent_id)
			{
				$this->_action_show_description($parent_id);
			}
		}

		/**
		 * Show forum description in topic page 
		 */
		function action_show_description_topic()
		{
			// get an array of all the parent pages in order from most distant to closest.
			$parent = $this->_get_post_ancestors_reversed();

			foreach ($parent as $parent_id)
			{
				$this->_action_show_description($parent_id);
				if (!self::get_option('show_description_parents'))
				{
					// show only one parent forum description 
					break;
				}
			}
		}

		/**
		 * Show topic name and parent forum description 
		 */
		function action_show_description_reply()
		{
			// get an array of all the parent pages in order from most distant to closest.
			$parent = $this->_get_post_ancestors_reversed();

			foreach ($parent as $parent_id)
			{
				if (get_post_type($parent_id) === 'topic')
				{
					// show topic with link to it 
					echo '<div class="bbwptw-topic">'
					. sprintf(__('View topic: <a href="%1$s">%2$s</a>', 'bbpress-wp-tweaks'), get_the_permalink($parent_id), get_the_title($parent_id))
					. '</div>';
				}
				else
				{
					$this->_action_show_description($parent_id);
					if (!self::get_option('show_description_parents'))
					{
						// show only one parent forum description 
						break;
					}
				}
			}
		}

		/**
		 * Format and show forum description 
		 * 
		 * @param int $forum_id
		 */
		function _action_show_description($forum_id)
		{
			$description = bbp_get_forum_content($forum_id);
			if (strlen($description))
			{
				echo '<div class="bbwptw-description">' . $description . '</div>';
			}
		}

		/**
		 * Get an array of all the parent pages in order from most distant to closest.
		 * 
		 * @return array
		 */
		function _get_post_ancestors_reversed()
		{
			$parent = array();

			$post = get_post();
			if ($post)
			{
				$parent = array_reverse(get_post_ancestors($post->ID));
			}

			return $parent;
		}

		function action_author_redirect()
		{
			global $wp_query;
			if ($wp_query->is_author())
			{
				$user = $wp_query->get_queried_object();

				$user_post_count = count_user_posts($user->ID, 'post', true);
				if (!$user_post_count)
				{
					// no posts check for topis 
					$user_post_count_forum = count_user_posts($user->ID, array('topic', 'reply'), true);
					if ($user_post_count_forum)
					{
						// redirect to user profile in forum 
						wp_redirect(bbp_get_user_profile_url($user->ID));
					}
				}
			}
		}

		/**
		 * setup plugin css and js 
		 */
		function callback_for_setting_up_scripts()
		{
			wp_register_style(self::ID, plugins_url('style.css', __FILE__));
			wp_enqueue_style(self::ID);
			//wp_register_script(self::ID, plugins_url('your_script.js', __FILE__));
			//wp_enqueue_script(self::ID);
		}

		/**
		 * Display login button for not logged in user to add new topic or send reply
		 */
		function action_display_login_buttons()
		{
			// display login buttons if required 
			if ((!bbp_current_user_can_access_create_reply_form() || !bbp_current_user_can_access_create_topic_form()) && !is_user_logged_in())
			{
				// get curretn url to redirect back 
				$redirect_to = esc_url($_SERVER["REQUEST_URI"]);

				$login_btn_template = self::get_option('login_btn_template', $this->default_login_btn_template);

				$arr_replace = array(
					'{login_url}'		 => wp_login_url($redirect_to),
					'{login_text}'		 => __('Log in', 'bbpress-wp-tweaks'),
					'{register_url}'	 => wp_registration_url(),
					'{register_text}'	 => __('Register', 'bbpress-wp-tweaks'),
				);


				echo '<div class="bbwptw-login-register-links">' . str_replace(array_keys($arr_replace), array_values($arr_replace), $login_btn_template) . '</div>';
			}
		}

		/**
		 * Register sidebar for forum
		 * 
		 * @uses register_sidebar()
		 */
		function action_register_sidebar()
		{

			global $wp_registered_sidebars;

			// check if it replaces any sidebar 
			$sidebar_target = $this->get_option_sidebar_target();

			if ($sidebar_target !== 'none')
			{
				$sidebar_title = $sidebar_target;
				if (isset($wp_registered_sidebars[$sidebar_target]))
				{
					$sidebar_title = $wp_registered_sidebars[$sidebar_target]['name'];
				}

				$sidebar_action_name = self::sidebar_action_names(self::get_option('sidebar_action', 'replace'));

				register_sidebar(array(
					'name'			 => __('bbPress sidebar', 'bbpress-wp-tweaks'),
					'id'			 => $this->sidebar_id,
					'description'	 => sprintf(__('The sidebar for bbPress forum (%1$s - "%2$s" on forum pages) ', 'bbpress-wp-tweaks'), esc_html($sidebar_action_name), esc_html($sidebar_title)),
					'before_widget'	 => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'	 => '</aside>',
					'before_title'	 => '<h3 class="widget-title">',
					'after_title'	 => '</h3>',
				));
			}
		}

		static public function sidebar_action_names($key = null)
		{
			$return = array(
				'replace'	 => __('Replace', 'bbpress-wp-tweaks'),
				'prepend'	 => __('Prepend', 'bbpress-wp-tweaks'),
				'append'	 => __('Append', 'bbpress-wp-tweaks')
			);

			if (!is_null($key))
			{
				if (isset($return[$key]))
				{
					return $return[$key];
				}
				else
				{
					return $key;
				}
			}

			return $return;
		}

		/**
		 * If set to use bbpress sidebar then show it on bbpress pages
		 * 
		 * @param type $data
		 * @return type
		 */
		function filter_sidebars_widgets($data)
		{
			//echo '[filter_sidebars_widgets]';
			//print_r($data);
			// if bbpress enabled and main sidebar requested
			// check if current page is bbpress page
			$sidebar_target = $this->get_option_sidebar_target();
			$sidebar_action = self::get_option('sidebar_action', 'replace');

			if (self::is_bbp_active() && is_bbpress() && $sidebar_target !== 'none' && isset($data[$sidebar_target]) && isset($data[$this->sidebar_id]))
			{
				switch ($sidebar_action)
				{
					case 'prepend':
						$data[$sidebar_target] = array_merge($data[$this->sidebar_id], $data[$sidebar_target]);
						break;
					case 'append':
						$data[$sidebar_target] = array_merge($data[$sidebar_target], $data[$this->sidebar_id]);
						break;
					case 'replace':
					default:
						$data[$sidebar_target] = $data[$this->sidebar_id];
				}
			}
			// return modified widgets array
			return $data;
		}

		/**
		 * true if it is bbpress page. used when rendering customizer sidebars
		 * 
		 * @param type $is_active_sidebar
		 * @param type $index
		 * @return boolean
		 */
		function filter_is_active_sidebar($is_active_sidebar, $index)
		{
			// echo 'filter_is_active_sidebar:' . $is_active_sidebar . ':' . $index;
			if ($index === $this->sidebar_id)
			{

				if (is_bbpress())
				{
					$is_active_sidebar = true;
				}
				else
				{
					$is_active_sidebar = false;
				}
			}

			return $is_active_sidebar;
		}

		/**
		 * reorder priority of template files to use as forum wrapper. move saved forum wrapper to the beginning of array
		 *
		 */
		function filter_bbp_get_theme_compat_templates($templates)
		{

			/*
			  $templates = array(
			  'bbpress.php',
			  'forum.php',
			  'page.php',
			  'single.php',
			  'index.php'
			  );
			 */



			// searhced for bbpress compatible theme files . then bbpress page is requested
			$this->bbp_templates = $templates;

			//return $templates;
			$return = array();

			$wrapper = self::get_option('wrapper');
			if (strlen($wrapper))
			{

				if ($wrapper === 'custom_wrapper')
				{
					$wrapper_custom = self::get_option('wrapper_custom');
					if (strlen($wrapper_custom))
					{
						$return[] = $wrapper_custom;
					}
				}
				else
				{
					$return[] = $wrapper;
				}
			}


			foreach ($templates as $t)
			{
				if (!in_array($t, $return))
				{
					$return[] = $t;
				}
			}
			// echo '[filter_bbp_get_theme_compat_templates]';
			//print_r($return);
			return $return;
		}

		/**
		 *  add settings link to plugin listing
		 */
		function filter_plugin_actions($links, $file)
		{
			//Static so we don't call plugin_basename on every plugin row.
			static $this_plugin = null;
			if (is_null($this_plugin))
			{
				$this_plugin = plugin_basename(__FILE__);
			}
			if ($file == $this_plugin)
			{
				$settings_link = '<a href="options-general.php?page=bbpress-wp-tweaks">' . __('Settings', 'bbpress-wp-tweaks') . '</a>';
				array_unshift($links, $settings_link); // before other links
			}
			return $links;
		}

		/**
		 *  Register the admin menu
		 */
		function AddAdminMenu()
		{
			add_options_page(__('bbPress WP Tweaks', 'bbpress-wp-tweaks'), __('bbPress WP Tweaks', 'bbpress-wp-tweaks'), 'manage_options', 'bbpress-wp-tweaks', array(&$this, 'options_page'));

			//call register settings function

			add_action('admin_init', array(&$this, 'page_init'));
		}

		/**
		 * Check if bbpress plugin installed and activated 
		 */
		static public function is_bbp_active()
		{
			if (is_null(self::$is_bbp))
			{
				self::$is_bbp = function_exists('bbp_logout_link') && function_exists('bbp_get_theme_compat_templates');
			}
			return self::$is_bbp;
		}

		/**
		 * The options page for this plugin
		 */
		function options_page()
		{
			// update old options with new array variable
			$this->update_plugin_DB();

			// Set class property
			?>
			<div class="wrap">
				<h1><?php _e('bbPress WP Tweaks', 'bbpress-wp-tweaks'); ?></h1>
				<?php
				if (!self::is_bbp_active())
				{
					// no bbpress detected. 
					echo '<div id="message" class="error notice"><p>'
					. __('bbPress installation is not detected. This plugin works with bbPress. <a href="plugins.php">Please install and activate bbPress plugin first.</a>', 'bbpress-wp-tweaks')
					. '</p></div>';
					// close wrap div
					echo '</div>';
					return;
				}
				?>


				<div class="bbwptw-main">
					<form method="post" action="options.php">
						<?php
						// This prints out all hidden setting fields
						settings_fields('bbpress-wp-tweaks');
						do_settings_sections('bbpress-wp-tweaks-admin');
						submit_button();
						?>
					</form>
				</div>
				<div class="bbwptw-side">
					<div class="bbwptw-box">
						<h3><?php _e('Docs and Help', 'bbpress-wp-tweaks'); ?></h3>
						<ul>
							<li><a href="http://veppa.com/bbpress-wp-tweaks/?utm_source=wp&utm_medium=plugin&utm_campaign=options#doc" target="_blank"><?php _e('Documentation', 'bbpress-wp-tweaks'); ?></a></li>
							<li><a href="https://wordpress.org/support/plugin/bbpress-wp-tweaks" target="_blank"><?php _e('Support', 'bbpress-wp-tweaks'); ?></a></li>
						</ul>

						<h3><?php _e('Do you like bbPress WP tweaks?', 'bbpress-wp-tweaks'); ?></h3>
						<p><?php _e('If you\'re happy with plugin, there\'s a few things you can do to let others know:', 'bbpress-wp-tweaks'); ?></p>
						<ul>							
							<li><a href="http://veppa.com/bbpress-wp-tweaks/?utm_source=wp&utm_medium=plugin&utm_campaign=options#comments" target="_blank"><?php _e('Leave a Comment', 'bbpress-wp-tweaks'); ?></a></li>
							<li><a href="https://wordpress.org/support/plugin/bbpress-wp-tweaks/reviews/#new-post" target="_blank"><?php _e('Give a good rating on WordPress.org', 'bbpress-wp-tweaks'); ?></a></li>							
						</ul>

					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * get plugin option by name 
		 * 
		 * @param type $name
		 * @param type $default
		 * @return string
		 */
		static public function get_option($name, $default = null)
		{
			if (is_null(self::$options))
			{
				self::$options = get_option(self::ID, array());
			}

			if (!isset(self::$options[$name]))
			{
				if (is_null($default))
				{
					return '';
				}
				else
				{
					return $default;
				}
			}

			return self::$options[$name];
		}

		/**
		 * return forum input field name
		 * 
		 * @param type $name
		 * @return type
		 */
		static public function get_name($name)
		{
			return self::ID . '[' . $name . ']';
		}

		/**
		 * Register and add settings
		 */
		public function page_init()
		{
			register_setting(
					'bbpress-wp-tweaks', // Option group  
	 'bbpress-wp-tweaks', // Option name  
	 array($this, 'sanitize') // Sanitize 
			);


			/* SECTION wrapper_sidebar */
			add_settings_section(
					'wrapper_sidebar', // ID
	 __('bbPress wrapper and sidebar settings', 'bbpress-wp-tweaks'), // Title
	 array($this, 'print_info_wrapper_sidebar'), // Callback
	 'bbpress-wp-tweaks-admin' // Page
			);

			add_settings_field(
					'wrapper', // ID
	 __('Default forum wrapper', 'bbpress-wp-tweaks'), // Title 
	 array($this, 'callback_wrapper'), // Callback
	 'bbpress-wp-tweaks-admin', // Page
	 'wrapper_sidebar' // Section
			);

			add_settings_field(
					'sidebar', // ID
	 __('bbPress sidebar', 'bbpress-wp-tweaks'), // Title 
	 array($this, 'callback_sidebar'), // Callback
	 'bbpress-wp-tweaks-admin', // Page
	 'wrapper_sidebar'// Section
			);

			/* SECTION tweaks */
			add_settings_section(
					'tweaks', // ID
	 __('Forum tweaks', 'bbpress-wp-tweaks'), // Title
	 null, // Callback
	 'bbpress-wp-tweaks-admin' // Page
			);

			add_settings_field(
					'login_btn_show', // ID
	 __('Login/register buttons', 'bbpress-wp-tweaks'), // Title 
	 array($this, 'callback_login_btn_show'), // Callback
	 'bbpress-wp-tweaks-admin', // Page
	 'tweaks' // Section           
			);

			add_settings_field(
					'login_btn_template', // ID
	 __('Login/register button template', 'bbpress-wp-tweaks'), // Title 
	 array($this, 'callback_login_btn_template'), // Callback
	 'bbpress-wp-tweaks-admin', // Page
	 'tweaks' // Section           
			);


			add_settings_field(
					'show_description', // ID
	 __('Show forum description', 'bbpress-wp-tweaks'), // Title 
	 array($this, 'callback_show_description'), // Callback
	 'bbpress-wp-tweaks-admin', // Page
	 'tweaks' // Section           
			);


			add_settings_field(
					'author_redirect', // ID
	 __('Author redirect', 'bbpress-wp-tweaks'), // Title 
	 array($this, 'callback_author_redirect'), // Callback
	 'bbpress-wp-tweaks-admin', // Page
	 'tweaks' // Section           
			);

			add_settings_field(
					'user_columns', // ID
	 __('Forum columns in users', 'bbpress-wp-tweaks'), // Title 
	 array($this, 'callback_user_columns'), // Callback
	 'bbpress-wp-tweaks-admin', // Page
	 'tweaks' // Section           
			);

			add_settings_field(
					'widgets', // ID
	 __('Widgets', 'bbpress-wp-tweaks'), // Title 
	 array($this, 'callback_widgets'), // Callback
	 'bbpress-wp-tweaks-admin', // Page
	 'tweaks' // Section           
			);
		}

		function sanitize($input)
		{
			$arr_allowed_fields = array(
				'wrapper'					 => 1,
				'wrapper_custom'			 => 1,
				'sidebar_action'			 => 1,
				'sidebar_target'			 => 1,
				'login_btn_show'			 => 1,
				'login_btn_template'		 => 1,
				'show_description'			 => 1,
				'show_description_parents'	 => 1,
				'show_description_topic'	 => 1,
				'show_description_reply'	 => 1,
				'author_redirect'			 => 1,
				'user_columns'				 => 1,
				'widget_login'				 => 1,
				'widget_users'				 => 1
			);

			$new_input = array();
			foreach ($arr_allowed_fields as $k => $v)
			{
				if (isset($input[$k]))
				{
					switch ($k)
					{
						case 'wrapper_custom':
							// custom wrapper template defined, use only base name of file 
							$new_input[$k] = sanitize_file_name($input[$k]);
							break;
						default:
							$new_input[$k] = $input[$k];
					}
				}
				else
				{
					// save not set fields as 0. these are checkboxes
					$new_input[$k] = 0;
				}
			}

			return $new_input;
		}

		/**
		 * Print the Section text
		 */
		public function print_info_wrapper_sidebar()
		{
			_e('Select template file that you prefer bbPress rendered in. Make sure template file is present in your theme directory. If sidebar is not displaying make sure you put some widgets to "bbPress sidebar" in <a href="widgets.php">widgets</a> page then try different forum wrapper from this list.', 'bbpress-wp-tweaks');
		}

		/**
		 * Get the settings option array and print one of its values
		 */
		public function callback_wrapper()
		{
			/* printf(
			  '<input type="text" id="id_number" name="my_option_name[id_number]" value="%s" />', isset(self::get_option('id_number')) ? esc_attr(self::get_option('id_number')) : ''
			  ); */


			$wrapper = self::get_option('wrapper');
			$wrapper_custom = self::get_option('wrapper_custom');


			// get settings from bbpress plugin
			$_default_wrapper_file = bbp_get_theme_compat_templates();


			// make array of possible template wrappers 
			foreach ($this->bbp_templates as $t)
			{
				$file_exists = locate_template($t, false, false);
				if ($file_exists || $t === $wrapper || $t === 'bbpress.php')
				{
					// add only if template file exists or a selected value 
					$arr_possible_wrapper[$t] = $file_exists;
				}
			}

			// add extra possible wrappers 
			foreach ($this->bbp_templates_extra as $t)
			{
				if (isset($arr_possible_wrapper[$t]))
				{
					continue;
				}

				$file_exists = locate_template($t, false, false);
				if ($file_exists || $t === $wrapper)
				{
					// add only if template file exists or a selected value 
					$arr_possible_wrapper[$t] = $file_exists;
				}
			}

			foreach ($arr_possible_wrapper as $t => $file_exists):
				?>
				<label style="font-weight: <?php echo ($file_exists ? 'bold' : 'normal'); ?>;"><input name="<?php echo self::get_name('wrapper'); ?>" type="radio" value="<?php echo esc_attr($t); ?>" <?php checked($t, $wrapper); ?> /> <?php echo esc_html($t); ?></label><br />
			<?php endforeach; ?>


			<label><input name="<?php echo self::get_name('wrapper'); ?>" type="radio" value="wrapper_custom" <?php checked('wrapper_custom', $wrapper); ?> /> <?php echo esc_html(__('Custom template file')); ?></label>
			<input type="text" name="<?php echo self::get_name('wrapper_custom'); ?>" id="wrapper_custom" value="<?php echo esc_attr($wrapper_custom); ?>" />
			<?php
			// chow if custom file exists
			if (strlen($wrapper_custom))
			{
				echo (locate_template($wrapper_custom, false, false) ?
						'<b>' . __('exists', 'bbpress-wp-tweaks') . '</b>' :
						__('not-found', 'bbpress-wp-tweaks'));
			}

			echo '<p>' . __('Files with <b>Bold text</b> exist.', 'bbpress-wp-tweaks') . '</p>';
		}

		/**
		 * Get the settings option array and print one of its values
		 */
		public function callback_sidebar()
		{
			/* printf(
			  '<input type="text" id="id_number" name="my_option_name[id_number]" value="%s" />', isset(self::get_option('id_number')) ? esc_attr(self::get_option('id_number')) : ''
			  ); */
			global $wp_registered_sidebars;


			$sidebar_target = $this->get_option_sidebar_target();
			$sidebar_action = self::get_option('sidebar_action', 'replace');
			?>

			<p>
				<label for="sidebar_action"><?php _e('Action:', 'bbpress-wp-tweaks'); ?></label> 
				<select name="bbpress-wp-tweaks[sidebar_action]" id="sidebar_action">
					<option <?php selected($sidebar_action, 'replace'); ?> value="replace"><?php echo __('Replace'); ?></option>
					<option <?php selected($sidebar_action, 'prepend'); ?> value="prepend"><?php echo __('Prepend'); ?></option>
					<option <?php selected($sidebar_action, 'append'); ?> value="append"><?php echo __('Append'); ?></option>
				</select>
			</p>
			<p>
				<label for="sidebar_target"><?php _e('Target:', 'bbpress-wp-tweaks'); ?></label> 
				<select name="bbpress-wp-tweaks[sidebar_target]" id="sidebar_target">
					<option <?php selected($sidebar_target, 'none'); ?> value="none"><?php _e('none', 'bbpress-wp-tweaks') ?></option>
					<?php
					if ($wp_registered_sidebars)
					{
						foreach ($wp_registered_sidebars as $key => $val)
						{
							if ($key !== $this->sidebar_id && strpos($key, 'inactive') === false)
							{
								$name = strlen($val['name']) ? $val['name'] : $key;
								echo '<option ' . selected($sidebar_target, $key) . ' value="' . esc_attr($key) . '">' . esc_html($name) . '</option>';
							}
						}
					}
					?>
				</select>
			</p>
			<p><?php _e('Which sidebar to replace/prepend/apppend on all forum pages?', 'bbpress-wp-tweaks'); ?></p>
			<?php
		}

		/**
		 * Get the settings option array and print one of its values
		 */
		public function callback_widgets()
		{

			echo '<input name="' . self::get_name('widget_login') . '" id="widget_login" type="checkbox" value="1" '
			. checked(self::get_option('widget_login', 1), true, false) . '>
			  <label for="widget_login">' . sprintf(__('Enable widget "%s".', 'bbpress-wp-tweaks'), __('(bbwptw) bbPress Login Links', 'bbpress-wp-tweaks')) . ' </label><br>';

			echo '<input name="' . self::get_name('widget_users') . '" id="widget_users" type="checkbox" value="1" '
			. checked(self::get_option('widget_users', 1), true, false) . '>
			  <label for="widget_users">' . sprintf(__('Enable widget "%s".', 'bbpress-wp-tweaks'), __('(bbwptw) Users', 'bbpress-wp-tweaks')) . ' </label><br>';
		}

		/**
		 * Get the settings option array and print one of its values
		 */
		public function callback_login_btn_show()
		{

			echo '<input name="' . self::get_name('login_btn_show') . '" id="login_btn_show" type="checkbox" value="1" ' . checked(self::get_option('login_btn_show', 1), true, false) . '>
			  <label for="login_btn_show">' . __('Add login and register links to the bottom of forum for adding new topic or replying to existing topics.', 'bbpress-wp-tweaks') . ' </label>';
		}

		/**
		 * Get the settings option array and print one of its values
		 */
		public function callback_login_btn_template()
		{

			echo '<textarea name="' . self::get_name('login_btn_template') . '" id="login_btn_template" rows="4" cols="50" class="large-text code">' . esc_html(self::get_option('login_btn_template', $this->default_login_btn_template)) . '</textarea>
			  <p>' . __('Default:', 'bbpress-wp-tweaks') . ' <code>' . esc_html($this->default_login_btn_template) . '</code></p>';
		}

		/**
		 * Get the settings option array and print one of its values
		 */
		public function callback_show_description()
		{

			echo '<input name="' . self::get_name('show_description') . '" id="show_description" type="checkbox" value="1" '
			. checked(self::get_option('show_description'), true, false) . '>
			  <label for="show_description">' . __('Show in forum page.', 'bbpress-wp-tweaks') . ' </label><br>';
			echo '<input name="' . self::get_name('show_description_parents') . '" id="show_description_parents" type="checkbox" value="1" '
			. checked(self::get_option('show_description_parents'), true, false) . '>
			  <label for="show_description_parents">' . __('Show descriptions of parent forums.', 'bbpress-wp-tweaks') . ' </label><br>';
			echo '<input name="' . self::get_name('show_description_topic') . '" id="show_description_topic" type="checkbox" value="1" '
			. checked(self::get_option('show_description_topic'), true, false) . '>
			  <label for="show_description_topic">' . __('Show in topics.', 'bbpress-wp-tweaks') . ' </label><br>';
			echo '<input name="' . self::get_name('show_description_reply') . '" id="show_description_reply" type="checkbox" value="1" '
			. checked(self::get_option('show_description_reply'), true, false) . '>
			  <label for="show_description_reply">' . __('Show in replies.', 'bbpress-wp-tweaks') . ' </label>';
		}

		/**
		 * redirect to forum profile if author has no blog posts. it is linked from admin when clicked on view user link. 
		 */
		public function callback_author_redirect()
		{

			echo '<input name="' . self::get_name('author_redirect') . '" id="author_redirect" type="checkbox" value="1" ' . checked(self::get_option('author_redirect', 1), true, false) . '>
			  <label for="author_redirect">' . __('Redirect to forum profile if author has no blog posts.', 'bbpress-wp-tweaks') . ' </label>';
		}

		/**
		 * add forum related columns to users page in admin 
		 */
		public function callback_user_columns()
		{

			echo '<input name="' . self::get_name('user_columns') . '" id="user_columns" type="checkbox" value="1" ' . checked(self::get_option('user_columns', 1), true, false) . '>
			  <label for="user_columns">' . __('Add forum related columns (number of topics, number of replies, registration date, last active date) to Users page in admin.', 'bbpress-wp-tweaks') . ' </label>';
		}

		/**
		 * Add bbpress sidebar to customizer if it is forum page 
		 * 
		 * @global type $wp_customize
		 */
		function action_set_customizer_sidebar()
		{
			global $wp_customize;
			if (!empty($wp_customize))
			{
				// check if bbpress page 
				if (is_bbpress())
				{
					is_active_sidebar($this->sidebar_id);
				}
			}
		}

		/**
		 * Read stored option value, if not present then guess main sidebar of the theme
		 * 
		 * @global type $wp_registered_sidebars
		 * @return string
		 */
		function get_option_sidebar_target()
		{
			global $wp_registered_sidebars;

			$return = self::get_option('sidebar_target');

			if (!strlen($return))
			{
				// option not found then get sidebar-1 or first available sidebar id
				if (isset($wp_registered_sidebars['sidebar-1']))
				{
					$return = 'sidebar-1';
				}
				else
				{
					// get first registered sidebar 
					if ($wp_registered_sidebars)
					{
						foreach ($wp_registered_sidebars as $key => $val)
						{
							$return = $key;
							break;
						}
					}
				}
			}

			if (!strlen($return))
			{
				$return = 'none';
			}

			return $return;
		}

		static public function uninstall()
		{
			// delete last_login custom field for all users from usermeta table 			
			delete_metadata('user', get_current_user_id(), 'last_login', null, true);

			// delete plugin options 
			delete_option(self::ID);
		}

	}

	/**
	 * BbpressWpTweaks Login Links Widget
	 *
	 * Adds a widget which displays the login, register, logout links
	 *
	 * @uses WP_Widget
	 */
	class BbpressWpTweaks_Login_Links_Widget extends WP_Widget
	{

		/**
		 * Register the widget
		 *
		 * @uses register_widget()
		 */
		public static function register_widget()
		{
			// do not resgister widget if bbpress is not active 
			if (BbpressWpTweaks::is_bbp_active())
			{
				register_widget('BbpressWpTweaks_Login_Links_Widget');
			}
		}

		/**
		 * BbpressWpTweaks Login Links Widget
		 *
		 * Registers the login widget
		 *
		 * @uses apply_filters() Calls 'bbpresswptweaks_login_links_widget_options' with the
		 *                        widget options
		 */
		function __construct()
		{
			$widget_ops = apply_filters('bbpresswptweaks_login_links_widget_options', array(
				'classname'		 => 'bbpresswptweaks_login_links_widget',
				'description'	 => __('The login links widget. Displays login, register, logout links.', 'bbpress-wp-tweaks')
			));

			parent::__construct(false, __('(bbwptw) bbPress Login Links', 'bbpress-wp-tweaks'), $widget_ops);
		}

		/**
		 * Displays the output, the login form
		 *
		 * @param mixed $args Arguments
		 * @param array $instance Instance
		 * @uses function_exists() to check if bbpress installed
		 * @uses apply_filters() Calls 'bbp_login_widget_title' with the title
		 * @uses get_template_part() To get the login/logged in form
		 */
		function widget($args, $instance)
		{
			if (!function_exists('bbp_logout_link'))
			{
				// no bbress detected then forum login widget is not required
				return false;
			}

			$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

			$css_class_logout = isset($instance['css_class_logout']) ? $instance['css_class_logout'] : '';
			$css_class_login = isset($instance['css_class_login']) ? $instance['css_class_login'] : 'bbp-template-notice';
			$show_reg_closed = isset($instance['show_reg_closed']) ? intval($instance['show_reg_closed']) : 0;

			echo $args['before_widget'];

			if (!empty($title))
			{
				echo $args['before_title'] . $title . $args['after_title'];
			}

			// get curretn url to redirect back 
			$redirect_to = esc_url($_SERVER["REQUEST_URI"]);

			if (!is_user_logged_in())
			{
				// get login url				
				$login_url = wp_login_url($redirect_to);
				wp_register('', '', false);

				$login_link = '<a href="' . $login_url . '" rel="nofollow" class="button login-link">' . __('Log in', 'bbpress-wp-tweaks') . '</a>';
				if (get_option('users_can_register') || $show_reg_closed)
				{
					// registration is open show register link
					$login_link .= '  / <a href="' . wp_registration_url() . '" rel="nofollow" class="button register-link">' . __('Register', 'bbpress-wp-tweaks') . '</a>';
				}

				echo '<div class="' . $css_class_login . '">' . $login_link . '</div>';
			}
			else
			{
				?>
				<div class="bbp-logged-in<?php echo (strlen($css_class_logout) ? ' ' . $css_class_logout : ''); ?>">
					<a href="<?php bbp_user_profile_url(bbp_get_current_user_id()); ?>" class="submit user-submit"><?php echo get_avatar(bbp_get_current_user_id(), '40'); ?></a>
					<h4><?php bbp_user_profile_link(bbp_get_current_user_id()); ?></h4>
					<?php bbp_logout_link($redirect_to); ?>
				</div>
				<?php
			}

			echo $args['after_widget'];
		}

		/**
		 * Update the login widget options
		 *
		 * @param array $new_instance The new instance options
		 * @param array $old_instance The old instance options
		 */
		function update($new_instance, $old_instance)
		{
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['css_class_login'] = strip_tags($new_instance['css_class_login']);
			$instance['css_class_logout'] = strip_tags($new_instance['css_class_logout']);
			$instance['show_reg_closed'] = isset($new_instance['show_reg_closed']) ? 1 : 0;

			return $instance;
		}

		/**
		 * Output the login links widget options form
		 *
		 * @param $instance Instance
		 * @uses WP_Widget::get_field_id() To output the field id
		 * @uses WP_Widget::get_field_name() To output the field name
		 */
		function form($instance)
		{
			// Form values						
			$title = isset($instance['title']) ? $instance['title'] : __('Forum Login', 'bbpress-wp-tweaks');
			$css_class_logout = isset($instance['css_class_logout']) ? $instance['css_class_logout'] : '';
			$css_class_login = isset($instance['css_class_login']) ? $instance['css_class_login'] : 'bbp-template-notice';
			$show_reg_closed = isset($instance['show_reg_closed']) ? intval($instance['show_reg_closed']) : 0;
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'bbpress-wp-tweaks'); ?>
					<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('css_class_login'); ?>"><?php _e('CSS class login:', 'bbpress-wp-tweaks'); ?>
					<input class="widefat" id="<?php echo $this->get_field_id('css_class_login'); ?>" name="<?php echo $this->get_field_name('css_class_login'); ?>" type="text" value="<?php echo esc_attr($css_class_login); ?>" /></label>
				<?php echo __('default:', 'bbpress-wp-tweaks') . ' bbp-template-notice'; ?>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('css_class_logout'); ?>"><?php _e('CSS class logout:', 'bbpress-wp-tweaks'); ?>
					<input class="widefat" id="<?php echo $this->get_field_id('css_class_logout'); ?>" name="<?php echo $this->get_field_name('css_class_logout'); ?>" type="text" value="<?php echo esc_attr($css_class_logout); ?>" /></label>
			</p>
			<?php
			if (!get_option('users_can_register'))
			{
				// display register link even if registration is closed checkbox 
				?>
				<p><input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('show_reg_closed'); ?>" name="<?php echo $this->get_field_name('show_reg_closed'); ?>" <?php checked($show_reg_closed); ?> value="1">
					<label for="<?php echo $this->get_field_id('show_reg_closed'); ?>"><?php echo esc_attr(__('Display registration link even when registration is closed.', 'bbpress-wp-tweaks')); ?></label>
				</p>
				<?php
			}
			else
			{
				echo 'users_can_register: ' . get_option('users_can_register');
			}
		}

	}

	/**
	 * BbpressWpTweaks Login Links Widget
	 *
	 * Adds a widget which displays the login, register, logout links
	 *
	 * @uses WP_Widget
	 */
	class BbpressWpTweaks_Users extends WP_Widget
	{

		/**
		 * Register the widget
		 *
		 * @uses register_widget()
		 */
		public static function register_widget()
		{
			// do not resgister widget if bbpress is not active 
			if (BbpressWpTweaks::is_bbp_active())
			{
				register_widget('BbpressWpTweaks_Users');
			}
		}

		/**
		 * BbpressWpTweaks Login Links Widget
		 *
		 * Registers the login widget
		 *
		 * @uses apply_filters() Calls 'bbpresswptweaks_users_options' with the
		 *                        widget options
		 */
		function __construct()
		{
			$widget_ops = apply_filters('bbpresswptweaks_users_options', array(
				'classname'		 => 'bbpresswptweaks_users',
				'description'	 => __('List active, with most topics, with most replies bbpress users.', 'bbpress-wp-tweaks')
			));

			parent::__construct(false, __('(bbwptw) Users', 'bbpress-wp-tweaks'), $widget_ops);
		}

		/**
		 * Displays the output, the login form
		 *
		 * @param mixed $args Arguments
		 * @param array $instance Instance
		 * @uses function_exists() to check if bbpress installed
		 * @uses apply_filters() Calls 'bbp_login_widget_title' with the title
		 * @uses get_template_part() To get the login/logged in form
		 */
		function widget($args, $instance)
		{
			if (!BbpressWpTweaks::is_bbp_active())
			{
				// no bbress detected then forum login widget is not required
				return false;
			}

			$return = '';



			$limit = isset($instance['limit']) ? $instance['limit'] : 10;
			$order_by = isset($instance['order_by']) ? $instance['order_by'] : 'topics';


			// get users 
			switch ($order_by)
			{

				case 'replies':
					$user_query = new WP_User_Query(array(
						'orderby'	 => 'meta_value_num',
						'meta_key'	 => 'wp__bbp_reply_count',
						'order'		 => 'DESC',
						'number'	 => $limit
					));
					$meta_key = 'wp__bbp_reply_count';
					$meta_format = __('%d replies', 'bbpress-wp-tweaks');
					$title_suggested = __('Users with most replies', 'bbpress-wp-tweaks');
					break;
				case 'active':
					$user_query = new WP_User_Query(array(
						'orderby'	 => 'meta_value_num',
						'meta_key'	 => 'last_login',
						'order'		 => 'DESC',
						'number'	 => $limit
					));
					$meta_key = 'last_login';
					$title_suggested = __('Active users', 'bbpress-wp-tweaks');
					break;
				case 'online':
					$user_query = new WP_User_Query(array(
						'orderby'		 => 'meta_value_num',
						'meta_key'		 => 'last_login',
						'meta_value'	 => time() - 30 * MINUTE_IN_SECONDS,
						'meta_compare'	 => '>',
						'order'			 => 'DESC',
						'number'		 => $limit
					));
					$meta_key = 'last_login';
					$title_suggested = __('Online users', 'bbpress-wp-tweaks');
					break;
				case 'new':
					$user_query = new WP_User_Query(array(
						'orderby'	 => 'user_registered',
						'order'		 => 'DESC',
						'number'	 => $limit
					));
					$meta_key = 'user_registered';
					$title_suggested = __('New users', 'bbpress-wp-tweaks');
					break;
				case 'old':
					$user_query = new WP_User_Query(array(
						'orderby'	 => 'user_registered',
						'order'		 => 'ASC',
						'number'	 => $limit
					));
					$meta_key = 'user_registered';
					$title_suggested = __('Old users', 'bbpress-wp-tweaks');
					break;
				case 'topics':
				default:
					$user_query = new WP_User_Query(array(
						'orderby'	 => 'meta_value_num',
						'meta_key'	 => 'wp__bbp_topic_count',
						'order'		 => 'DESC',
						'number'	 => $limit
					));
					$meta_key = 'wp__bbp_topic_count';
					$meta_format = __('%d topics', 'bbpress-wp-tweaks');
					$title_suggested = __('Users with most topics', 'bbpress-wp-tweaks');
					break;
			}


			// User Loop
			if ($user_query->get_results())
			{
				$return = '<ul class="bbwptw-users">';
				foreach ($user_query->get_results() as $user)
				{
					// format extra info
					switch ($meta_key)
					{
						case 'last_login':
							$the_login_date = human_time_diff($user->$meta_key);
							$count = sprintf(__('%s ago', 'bbpress-wp-tweaks'), $the_login_date);
							break;
						case 'user_registered':
							$the_reg_date = human_time_diff(strtotime($user->$meta_key));
							$count = sprintf(__('%s ago', 'bbpress-wp-tweaks'), $the_reg_date);
							break;
						default:
							$count = sprintf($meta_format, $user->$meta_key);
					}

					$return .= '<li>'
							. bbp_get_user_profile_link($user->ID)
							. '<span class="bbwptw-users-count">' . $count . '</span>'
							. '</li>';
				}
				$return .= '</ul>';
			}
			else
			{
				//echo 'No users found.';
				return false;
			}


			// has users then display widget 
			echo $args['before_widget'];

			$title = apply_filters('widget_title', empty($instance['title']) ? $title_suggested : $instance['title'], $instance, $this->id_base);


			if (!empty($title))
			{
				echo $args['before_title'] . $title . $args['after_title'];
			}
			echo $return;
			echo $args['after_widget'];
		}

		/**
		 * Update the login widget options
		 *
		 * @param array $new_instance The new instance options
		 * @param array $old_instance The old instance options
		 */
		function update($new_instance, $old_instance)
		{
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['limit'] = strip_tags($new_instance['limit']);
			$instance['order_by'] = strip_tags($new_instance['order_by']);

			return $instance;
		}

		/**
		 * Output users widget options form
		 *
		 * @param $instance Instance
		 * @uses WP_Widget::get_field_id() To output the field id
		 * @uses WP_Widget::get_field_name() To output the field name
		 */
		function form($instance)
		{
			// Form values						
			$title = isset($instance['title']) ? $instance['title'] : '';
			$limit = isset($instance['limit']) ? $instance['limit'] : 10;
			$order_by = isset($instance['order_by']) ? $instance['order_by'] : 'popular';
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'bbpress-wp-tweaks'); ?>
					<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('order_by'); ?>"><?php _e('Order by:', 'bbpress-wp-tweaks'); ?>
					<select class="widefat" name="<?php echo $this->get_field_name('order_by'); ?>" id="<?php echo $this->get_field_id('order_by'); ?>">
						<option value="topics" <?php selected('topics', $order_by) ?>><?php _e('Most topics', 'bbpress-wp-tweaks'); ?></option>
						<option value="replies" <?php selected('replies', $order_by) ?>><?php _e('Most replies', 'bbpress-wp-tweaks'); ?></option>
						<option value="active" <?php selected('active', $order_by) ?>><?php _e('Active', 'bbpress-wp-tweaks'); ?></option>
						<option value="online" <?php selected('online', $order_by) ?>><?php _e('Online', 'bbpress-wp-tweaks'); ?></option>
						<option value="new" <?php selected('new', $order_by) ?>><?php _e('New', 'bbpress-wp-tweaks'); ?></option>
						<option value="old" <?php selected('old', $order_by) ?>><?php _e('Old', 'bbpress-wp-tweaks'); ?></option>
					</select>	
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:', 'bbpress-wp-tweaks'); ?>
					<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" />

				</label>

			</p>
			<?php
		}

	}

	// Start this plugin once all other plugins are fully loaded
	//add_action('after_setup_theme', create_function('', 'global $BbpressWpTweaks; $BbpressWpTweaks = new BbpressWpTweaks();'));
	add_action('after_setup_theme', array('BbpressWpTweaks', 'setup'));


	// plugin uninstallation
	register_uninstall_hook(__FILE__, array('BbpressWpTweaks', 'uninstall'));

  
endif; // class_exists