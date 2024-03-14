<?php
/*
 Plugin Name: Web Fonts - Fonts.com
 Plugin URI: http://webfonts.fonts.com
 Description: A plugin for the Web Fonts plugin produced by Fonts.com and Monotype Imaging. This built in plugin adds Fonts.com web fonts support to the Web Fonts plugin.
 Version: 1.1.6
 Author: Nick Ohrn of Plugin-Developer.com
 Author URI: http://plugin-developer.com/
 */

if(!class_exists('Fonts_Com_Plugin')) {
	class Fonts_Com_Plugin {

		/// KEYS

		//// API
		const APPLICATION_KEY = 'fce41f18-1aa1-48d8-a5ab-ea50fb6361981128835';

		//// VERSION
		const VERSION = '1.1.6';

		//// KEYS
		const SETTINGS_KEY = '_fonts_com_web_fonts_settings';

		const SELECTOR_DATA_KEY = '_fonts_com_selector_data';
		const ACTIVE_PROJECT_KEY = '_fonts_com_active_project';

		/// DATA STORAGE
		public static $admin_page_hooks = array();
		private static $default_settings = array('embed-method' => 'javascript');

		public static function init() {
			self::add_actions();
			self::add_filters();

			if(function_exists('register_web_fonts_provider')) {
				register_web_fonts_provider('Fonts_Com_Provider');
			}
		}

		private static function add_actions() {
			if(is_admin()) {
				add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_administrative_resources'));
				add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_frontend_resources'));
				add_action('web_fonts_manage_stylesheet_fonts', array(__CLASS__, 'handle_stylesheet_fonts'));
				add_action('web_fonts_manage_stylesheet_selectors', array(__CLASS__, 'handle_stylesheet_selectors'));
			} else {
				add_action('wp_head', array(__CLASS__, 'enqueue_frontend_resources'), 1);
				add_action('wp_head', array(__CLASS__, 'display_fallbacks'), 11);
			}

			add_action('wp_ajax_web_fonts_fonts_com_clear_active_project', array(__CLASS__, 'ajax_container'));
			add_action('wp_ajax_web_fonts_fonts_com_clear_key', array(__CLASS__, 'ajax_container'));
			add_action('wp_ajax_web_fonts_fonts_com_create_account', array(__CLASS__, 'ajax_container'));
			add_action('wp_ajax_web_fonts_fonts_com_get_fonts', array(__CLASS__, 'ajax_container'));
			add_action('wp_ajax_web_fonts_fonts_com_get_project_data', array(__CLASS__, 'ajax_container'));
			add_action('wp_ajax_web_fonts_fonts_com_save_project_settings', array(__CLASS__, 'ajax_container'));
			add_action('wp_ajax_web_fonts_fonts_com_set_embed_method', array(__CLASS__, 'ajax_container'));
			add_action('wp_ajax_web_fonts_fonts_com_set_font_status', array(__CLASS__, 'ajax_container'));
			add_action('wp_ajax_web_fonts_fonts_com_validate_email_password', array(__CLASS__, 'ajax_container'));
			add_action('wp_ajax_web_fonts_fonts_com_validate_key', array(__CLASS__, 'ajax_container'));
		}

		private static function add_filters() {
			add_filter('web_fonts_manage_stylesheet_fonts_and_selectors', array(__CLASS__, 'add_stylesheet_fonts_and_selectors'));
		}

		/// AJAX CALLBACKS

		public static function ajax_container() {
			$data = self::trim_r(stripslashes_deep($_REQUEST));
			$action = str_replace('web_fonts_fonts_com_', '', $data['action']);
			$method_name = "ajax_{$action}";

			if(isset($data['nonce']) && wp_verify_nonce($data['nonce'], 'fonts-com-action') && method_exists(__CLASS__, $method_name)) {
				$results = self::$method_name($data);
			} else {
				$results = self::get_response(array(), __('Something went wrong. Please refresh the page and try again.'), true);
			}

			header('Content-Type: application/json');
			echo json_encode($results);
			exit;
		}

		public static function ajax_clear_active_project($data) {
			self::set_active_project(null);

			return self::get_response(array(), __('The active project was cleared succesfully.'));
		}

		public static function ajax_clear_key($data) {
			$settings = array();
			self::set_settings($settings);

			return self::get_response(array(), __('The authentication key was successfully cleared.'));
		}

		public static function ajax_create_account($data) {
			$first_name = $data['first_name'];
			$last_name = $data['last_name'];
			$email_address = $data['email_address'];

			if(empty($first_name)) {
				$results = self::get_response(null, __('Please provide a non-empty first name for the new account.'), true);
			} else if(empty($last_name)) {
				$results = self::get_response(null, __('Please provide a non-empty last name for the new account.'), true);
			} else if(empty($email_address)) {
				$results = self::get_response(null, __('Please provide a non-empty email address for the new account.'), true);
			} else if(!is_email($email_address)) {
				$results = self::get_response(null, __('Please provide a validly formed email address for the new account.'), true);
			} else {
				$service = self::require_library();
				$new_account_result = $service->create_account($first_name, $last_name, $email_address);

				if('Success' == $new_account_result->Message) {
					$results = self::get_response(null, __('A new account has been created for you. Check the email you registered with to activate your account and then <a class="fonts-com-setup-new-cancel" href="#">click here</a> to register your authentication key with the plugin.'));
				} else {
					$results = self::get_response(null, __('A new account could not be created with those credentials. Please try again.'), true);
				}
			}

			return $results;
		}

		public static function ajax_get_fonts($data) {
			$settings = self::get_settings();
			$service = self::require_library($settings['public-key'], $settings['private-key']);

			$page_limit = 12;
			$page_number = isset($data['page_number']) ? ($data['page_number'] - 1) : 0;
			$offset_number = $page_limit * $page_number;

			$service->set_pagination_parameters($offset_number, $page_limit);

			$response_data = array();
			$get_fonts_response = $service->list_fonts($data['Keywords'], $data['FreeOrPaid'], $data['Classification'], $data['Designer'], $data['Foundry'], $data['Language'], $data['Alpha']);
			if('Success' == $get_fonts_response->Message) {
				$response_data['fonts'] = $get_fonts_response->Font;
				foreach($response_data['fonts'] as $single_response_font) {
					$single_response_font->FontSizeDisplayed = sprintf('%.1f K', ($single_response_font->FontSize / 1024));
				}

				ob_start();
				foreach($response_data['fonts'] as $font) {
					include('views/backend/css/font-face-preview.php');
				}
				$response_data['css'] = ob_get_clean();

				$number_records = $get_fonts_response->TotalRecords;
				$query_args = $data;
				unset($query_args['nonce']);
				unset($query_args['page_number']);

				foreach($query_args as $key => $value) {
					$query_args[$key] = urlencode($value);
				}

				$pagination_args = array(
					'base' => admin_url('admin-ajax.php') . '%_%', // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
					'format' => '?page_number=%#%', // ?page=%#% : %#% is replaced by the page number
					'total' => ceil($number_records / $page_limit),
					'current' => ($page_number + 1),
					'show_all' => false,
					'prev_next' => true,
					'prev_text' => __('&laquo; Previous'),
					'next_text' => __('Next &raquo;'),
					'end_size' => 2,
					'mid_size' => 3,
					'type' => 'plain',
					'add_args' => $query_args, // array of query args to add
					'add_fragment' => ''
				);

				$response_data['pagination_links'] = paginate_links($pagination_args);

				$available_filters = array();
				$filter_values_response = $service->list_all_filter_values($data['FreeOrPaid'], $data['Classification'], $data['Designer'], $data['Foundry'], $data['Language'], $data['Alpha']);
				if('Success' == $filter_values_response->Message) {
					foreach($filter_values_response->FilterValue as $filter_value_data) {
						if(!is_array($available_filters[$filter_value_data->FilterType])) {
							$available_filters[$filter_value_data->FilterType] = array();
						}

						$available_filters[$filter_value_data->FilterType][$filter_value_data->ValueID] = $filter_value_data->ValueName;
					}
				}
				$response_data['filters'] = $available_filters;

				$response_data = self::add_project_data($response_data, $data['project_id'], $service);

				$results = self::get_response($response_data);
			} else {
				$results = self::get_response(null, __('There was an issue retrieving the appropriate fonts. Please try again.'), true);
			}

			return $results;
		}

		public static function ajax_get_project_data($data) {
			$project_id = $data['project_id'];

			$project_data = array('project_name' => '', 'project_domains' => array());
			if(empty($project_id)) {
				$project_data['project_domains'][''] = '';
				$results = self::get_response($project_data);
			} else {
				$settings = self::get_settings();

				$service = self::require_library($settings['public-key'], $settings['private-key']);
				$projects_response = $service->list_projects();

				if('Success' == $projects_response->Message) {
					foreach($projects_response->Project as $single_project) {
						if($project_id == $single_project->ProjectKey) {
							$project_data['project_name'] = $single_project->ProjectName;
							break;
						}
					}

					$domains_response = $service->list_domains($project_id);
					if('Success' == $domains_response->Message) {
						foreach($domains_response->Domain as $single_domain) {
							$project_data['project_domains'][$single_domain->DomainName] = $single_domain->DomainID;
						}
					}

					$results = self::get_response($project_data, null);
				} else {
					$results = self::get_response(null, __('That project ID was not recognized. Please select a different project to try again.'), true);
				}
			}

			return $results;
		}

		public static function ajax_set_embed_method($data) {
			$settings = self::get_settings();

			$settings['embed-method'] = $data['embed-method'] == 'javascript' ? 'javascript' : 'css';

			self::set_settings($settings);

			return self::get_response($settings, __('The embed method has been set successfully.'));
		}

		public static function ajax_set_font_status($data) {
			$project_id = $data['project_id'];
			$font_id = $data['font_id'];
			$enabled = $data['enabled'] == 1;

			if(empty($project_id)) {
				$results = self::get_response(null, __('Please select a project that you wish to enable this font for.'), true);
			} else if(empty($font_id)) {
				$results = self::get_response(null, __('Please select a font to enable for the selected project.'), true);
			} else {
				$settings = self::get_settings();
				$service = self::require_library($settings['public-key'], $settings['private-key']);

				$font_status_response = ($enabled ? $service->add_font($project_id, $font_id) : $service->delete_font($project_id, $font_id));

				if('Success' == $font_status_response->Message) {
					$response_data = array('font_id' => $font_id, 'enabled' => $enabled);

					$response_data = self::add_project_data($response_data, $project_id, $service);

					$results = self::get_response($response_data, $enabled ? __('You have successfully enabled the selected font.') : __('You have successfully disabled the selected font.'));
				} else {
					$results = self::get_response(null, __('The operation you requested could not be performed unfortunately.'), true);
				}
			}

			return $results;
		}

		public static function ajax_save_project_settings($data) {
			set_time_limit(0);

			$project_id = $data['project_id'];
			$project_name = $data['project_name'];
			$project_domains = (array)$data['project_domains'];

			if(empty($project_name)) {
				$results = self::get_response(null, __('Please provide a non-empty project name.'), true);
			} else {
				$settings = self::get_settings();
				$service = self::require_library($settings['public-key'], $settings['private-key']);

				$response_data = array();
				$response_error = false;
				$response_message = '';

				$existing_domains = array();

				if(empty($project_id)) { // NEW PROJECT
					$project_add_response = $service->add_project($project_name);

					if('Success' == $project_add_response->Message) {
						$result_message = __('Your project was added successfully.');

						$projects_list = array();
						foreach($project_add_response->Project as $single_project) {
							if($single_project->ProjectName == $project_name) {
								$project_id = $single_project->ProjectKey;
							}

							$projects_list[$single_project->ProjectName] = $single_project->ProjectKey;
						}

						$response_data['projects'] = $projects_list;
					} else {
						$response_error = true;
						$response_message = __('There was a problem adding your project. Please make sure you aren\'t using an existing project name and try again.');
					}

				} else { // EDIT PROJECT
					$project_edit_response = $service->edit_project($project_id, $project_name);

					if('Success' == $project_edit_response->Message) {
						$response_message = __('Your project was edited successfully.');

						$projects_list = array();
						foreach($project_edit_response->Project as $single_project) {
							$projects_list[$single_project->ProjectName] = $single_project->ProjectKey;
						}

						$response_data['projects'] = $projects_list;

						$list_domains_response = $service->list_domains($project_id);

						$existing_domains = array();
						if('Success' == $list_domains_response->Message) {
							foreach($list_domains_response->Domain as $single_domain) {
								$existing_domains[$single_domain->DomainName] = $single_domain->DomainID;
							}
						}
					} else {
						$response_error = true;
						$response_message = __('There was a problem editing your project. Please make sure you aren\'t using an existing project name and try again.');
					}

				}

				if(!$response_error) {
					$old_active_project = self::get_active_project();

					$response_data['project_id'] = $project_id;
					self::set_active_project($project_id);

					if(!empty($old_active_project)) {
						self::swap_data_from_projects($old_active_project, $project_id);
					}

					$deleted_domains = array_diff($existing_domains, $project_domains);
					foreach($deleted_domains as $deleted_domain => $deleted_domain_key) {
						$delete_domain_response = $service->delete_domain($project_id, $deleted_domain_key);
					}

					$existing_domains_flipped = array_flip($existing_domains);
					foreach($project_domains as $project_domain => $project_domain_key) {
						if(!empty($project_domain_key) && isset($existing_domains_flipped[$project_domain_key]) && $existing_domains_flipped[$project_domain_key] != $project_domain) {
							$service->edit_domain($project_id, $project_domain_key, $project_domain);
						} else {
							$service->add_domain($project_id, $project_domain);
						}
					}
				}

				$list_projects_response = $service->list_projects();

				$the_projects = array();
				if('Success' == $list_projects_response->Message) {
					foreach($list_projects_response->Project as $single_project) {
						$the_projects[$single_project->ProjectName] = $single_project->ProjectKey;
					}
				}
				$response_data['projects'] = $the_projects;

				$list_domains_response = $service->list_domains($project_id);

				$the_domains = array();
				if('Success' == $list_domains_response->Message) {
					foreach($list_domains_response->Domain as $single_domain) {
						$the_domains[$single_domain->DomainName] = $single_domain->DomainID;
					}
				}
				$response_data['project_domains'] = $the_domains;

				$service->publish();

				$results = self::get_response($response_data, $response_message, $response_error);
			}

			return $results;
		}

		public static function ajax_validate_email_password($data) {
			$email = $data['email_address'];
			$password = $data['password'];

			if(empty($email)) {
				$results = self::get_response(null, __('Please provide a non-empty email address to validate.'), true);
			} else if(!is_email($email)) {
				$results = self::get_response(null, __('Please provide a validly formed email address to validate.'), true);
			} else if(empty($password)) {
				$results = self::get_response(null, __('Please provide a non-empty password to validate.'), true);
			} else {
				$service = self::require_library();
				$authentication_key_response = $service->get_token($email, $password);

				if('Success' == $authentication_key_response->Message) {
					$settings = array('authentication-key' => $authentication_key_response->Account->AuthorizationKey);
					self::set_settings($settings);

					$results = self::get_response($settings, __('The email and password combination you provided was verified and your authentication key has been saved.'));
				} else {
					$results = self::get_response(null, __('The email and password combination you provided is invalid. Please provide a valid email and password combination.'), true);
				}
			}

			return $results;
		}

		public static function ajax_validate_key($data) {
			$key = $data['key'];

			if(empty($key)) {
				$results = self::get_response(null, __('Please provide a non-empty authentication key to validate.'), true);
			} else {
				list($public, $private) = explode('--', $key);

				$service = self::require_library($public, $private);
				$projects = $service->list_projects();

				if('Success' == $projects->Message) {
					$settings = array('authentication-key' => $key);
					self::set_settings($settings);

					$results = self::get_response($settings, __('Your authentication key was successfully validated and has been saved.'));
				} else {
					$results = self::get_response(null, __('Your authentication key could not be validated. Please visit your account page by clicking the link below and copy and paste your key exactly.'), true);
				}
			}

			return $results;
		}

		/// CALLBACKS

		public static function add_stylesheet_fonts_and_selectors($data) {
			$active_project = self::get_active_project();
			$settings = self::get_settings();
			$service = self::require_library($settings['public-key'], $settings['private-key']);
			$project_data = self::add_project_data(array(), $active_project, $service);

			$font_selector_map = array();
			foreach($project_data['project_selectors'] as $selector_data) {
				$data['selectors'][] = $prepared_selector = web_fonts_prepare_selector_item('fonts-com', $selector_data->SelectorID, $selector_data->SelectorTag, $selector_data->SelectorFallback, 'fonts-com-' . $selector_data->SelectorFontID);

				if(!empty($selector_data->SelectorFontID) && 0 < $selector_data->SelectorFontID) {
					if(!is_array($font_selector_map[$selector_data->SelectorFontID])) {
						$font_selector_map[$selector_data->SelectorFontID] = array();
					}
					$font_selector_map[$selector_data->SelectorFontID][] = $prepared_selector;
				}

			}

			foreach($project_data['project_fonts'] as $font_data) {
				$data['fonts'][] = web_fonts_prepare_font_item('fonts-com', $font_data->FontID, $font_data->FontName, $font_data->FontCSSName, $font_data->FontPreviewTextLong, $font_selector_map[$font_data->FontID]);
			}

			return $data;
		}

		public static function detect_submissions() {
			$data = stripslashes_deep($_REQUEST);
		}

		public static function enqueue_administrative_resources($hook) {
			if(!in_array($hook, self::$admin_page_hooks)) { return; }

			wp_enqueue_script('fonts-com-backend', plugins_url('resources/backend/fonts-com.js', __FILE__), array('jquery', 'jquery-form', 'thickbox'), self::VERSION);
			wp_enqueue_style('fonts-com-backend', plugins_url('resources/backend/fonts-com.css', __FILE__), array('thickbox'), self::VERSION);

			$strings = array(
				'request_in_progress_message' => __('There is already a request in progress. Please wait until the request has completed before trying another action.'),
				'dirty_project_confirm' => __('You have made changes to this project that are not yet saved. Are you sure you wish to work on another project?'),
				'assign_fonts_title' => __('Assign Fonts'),
			);

			wp_localize_script('fonts-com-backend', 'Fonts_Com_Config', $strings);
		}

		public static function enqueue_frontend_resources() {
			$active_project = self::get_active_project();

			if(!empty($active_project)) {
				$settings = self::get_settings();

				$query_args = array('v' => web_fonts_get_last_saved_stylesheet_time());
				if('javascript' == $settings['embed-method']) {
					$url = add_query_arg($query_args, 'http://fast.fonts.com/jsapi/' . $active_project . '.js');
					wp_enqueue_script('web-fonts-fonts-com', $url);
				} else {
					$url = add_query_arg($query_args, 'http://fast.fonts.com/cssapi/' . $active_project . '.css');
					wp_enqueue_style('web-fonts-fonts-com', $url);
				}
			}
		}

		public static function handle_stylesheet_fonts($fonts) {
			$settings = self::get_settings();
			$service = self::require_library($settings['public-key'], $settings['private-key']);
			$service->set_pagination_parameters(0, 1000);

			$project_id = self::get_active_project();

			// Delete all existing selectors so we start with a clean slate
			$existing_selectors = $service->list_selectors($project_id);
			if('Success' == $existing_selectors->Message) {
				foreach($existing_selectors->Selector as $existing_selector) {
					$service->delete_selector($project_id, $existing_selector->SelectorID);
				}
			}

			$selector_to_fallback_map = array();
			$font_ids = array();
			$selector_ids = array();
			foreach($fonts as $font) {
				if('fonts-com' == $font->provider) {
					foreach($font->selectors as $selector) {
						$selector_tag = $selector->tag;

						$add_selector_response = $service->add_selector($project_id, $selector_tag);
						if('Success' == $add_selector_response->Message) {
							$selector_to_fallback_map[$selector_tag] = array('family' => $font->family, 'fallback' => $selector->fallback);

							foreach($add_selector_response->Selector as $selector_data) {
								if($selector_data->SelectorTag == $selector_tag) {
									$font_ids[] = $font->id;
									$selector_ids[] = $selector_data->SelectorID;
								}
							}
						}
					}
				}
			}

			if(!empty($font_ids) && !empty($selector_ids)) {
				$service->assign_to_selector($project_id, $font_ids, $selector_ids);
				self::set_selector_data($selector_to_fallback_map);
			}

			$service->publish();
		}

		public static function handle_stylesheet_selectors($selectors) {
			$settings = self::get_settings();
			$service = self::require_library($settings['public-key'], $settings['private-key']);
			$service->set_pagination_parameters(0, 1000);

			$project_id = self::get_active_project();

			// Delete all existing selectors so we start with a clean slate
			$existing_selectors = $service->list_selectors($project_id);
			if('Success' == $existing_selectors->Message) {
				foreach($existing_selectors->Selector as $existing_selector) {
					$service->delete_selector($project_id, $existing_selector->SelectorID);
				}
			}

			$selector_to_fallback_map = array();
			$font_ids = array();
			$selector_ids = array();
			foreach($selectors as $selector) {
				if(is_object($selector->font) && 0 === strpos($selector->font->id, 'fonts-com-')) {
					$font_id = str_replace('fonts-com-', '', $selector->font->id);
					$selector_tag = $selector->tag;

					$add_selector_response = $service->add_selector($project_id, $selector_tag);
					if('Success' == $add_selector_response->Message) {
						$selector_to_fallback_map[$selector_tag] = array('family' => $selector->font->family, 'fallback' => $selector->fallback);

						foreach($add_selector_response->Selector as $selector_data) {
							if($selector_data->SelectorTag == $selector_tag) {
								$font_ids[] = $font_id;
								$selector_ids[] = $selector_data->SelectorID;
							}
						}
					}
				}
			}

			if(!empty($font_ids) && !empty($selector_ids)) {
				$service->assign_to_selector($project_id, $font_ids, $selector_ids);
				self::set_selector_data($selector_to_fallback_map);
			}

			$service->publish();
		}

		/// DISPLAY CALLBACKS

		public static function display_fallbacks() {
			$active_project = self::get_active_project();
			$selector_data = self::get_selector_data();

			if(!empty($active_project) && !empty($selector_data)) {
				echo "\n<!-- Fonts.com Fallbacks -->\n";
				echo '<style type="text/css">';
				foreach($selector_data as $selector => $data) {
					printf('%s{font-family: "%s"%s;}', $selector, esc_attr($data['family']), empty($data['fallback']) ? '' : ',' . esc_attr($data['fallback']));
				}
				echo '</style>';
				echo "\n<!-- End Fonts.com Fallbacks -->\n";
			}
		}

		public static function display_settings_page() {
			$data = stripslashes_deep($_REQUEST);

			$settings = self::get_settings();
			$is_setup = self::is_setup();
			$active_project = self::get_active_project();

			$base_url = add_query_arg(array('page' => $data['page']), admin_url('admin.php'));
			$valid_tabs = $is_setup ? array('setup', 'projects', 'fonts') : array('setup');
			$current_tab = in_array($data['tab'], $valid_tabs) ? $data['tab'] : ($is_setup ? (empty($active_project) ? 'projects' : 'fonts') : 'setup');

			if('fonts' == $current_tab && empty($active_project)) {
				add_settings_error('fonts-com-current-tab', 'fonts-com-current-tab', __('Please select an active project before attempting to manage fonts.'), 'error');

				$current_tab = 'projects';
			}

			include('views/backend/settings/_inc/nav.php');

			// Make this dynamic
			switch($current_tab) {
				case 'setup':
					include('views/backend/settings/setup.php');
					break;
				case 'projects':
					$service = self::require_library($settings['public-key'], $settings['private-key']);
					$projects_response = $service->list_projects();

					if('Success' == $projects_response->Message) {
						$projects = (array)$projects_response->Project;
					} else {
						$projects = array();
					}

					$active_project = self::get_active_project();

					include('views/backend/settings/projects.php');
					break;
				case 'fonts':
					$service = self::require_library($settings['public-key'], $settings['private-key']);
					$projects_response = $service->list_projects();

					if('Success' == $projects_response->Message) {
						$projects = (array)$projects_response->Project;
					} else {
						$projects = array();
					}

					$available_filters = array();
					$filter_values_response = $service->list_all_filter_values();
					if('Success' == $filter_values_response->Message) {
						foreach($filter_values_response->FilterValue as $filter_value_data) {
							if(!is_array($available_filters[$filter_value_data->FilterType])) {
								$available_filters[$filter_value_data->FilterType] = array();
							}

							$available_filters[$filter_value_data->FilterType][$filter_value_data->ValueID] = $filter_value_data->ValueName;
						}
					}
					$active_project = self::get_active_project();

					include('views/backend/settings/fonts.php');
					break;
			}
		}

		/// SELECTORS

		private static function get_active_project() {
			$active_project = wp_cache_get(self::ACTIVE_PROJECT_KEY);

			if(empty($active_project)) {
				$active_project = get_option(self::ACTIVE_PROJECT_KEY);
				wp_cache_set(self::ACTIVE_PROJECT_KEY, $active_project, null, time() + CACHE_PERIOD);
			}

			return $active_project;
		}

		private static function set_active_project($active_project) {
			if(!empty($active_project)) {
				update_option(self::ACTIVE_PROJECT_KEY, $active_project);
				wp_cache_set(self::ACTIVE_PROJECT_KEY, $active_project, null, time() + CACHE_PERIOD);
			} else {
				delete_option(self::ACTIVE_PROJECT_KEY);
			}
		}

		/// ACTIVE PROJECT

		private static function get_selector_data() {
			$selector_data = wp_cache_get(self::SELECTOR_DATA_KEY);

			if(!is_array($selector_data)) {
				$selector_data = get_option(self::SELECTOR_DATA_KEY);
				wp_cache_set(self::SELECTOR_DATA_KEY, $selector_data, null, time() + CACHE_PERIOD);
			}

			return $selector_data;
		}

		private static function set_selector_data($selector_data) {
			if(is_array($selector_data)) {
				update_option(self::SELECTOR_DATA_KEY, $selector_data);
				wp_cache_set(self::SELECTOR_DATA_KEY, $selector_data, null, time() + CACHE_PERIOD);
			}
		}

		/// SETTINGS

		private static function get_settings() {
			$settings = wp_cache_get(self::SETTINGS_KEY);

			if(!is_array($settings)) {
				$settings = wp_parse_args(get_option(self::SETTINGS_KEY, self::$default_settings), self::$default_settings);
				wp_cache_set(self::SETTINGS_KEY, $settings, null, time() + CACHE_PERIOD);
			}

			return $settings;
		}

		private static function set_settings($settings) {
			if(is_array($settings)) {
				if(!empty($settings['authentication-key'])) {
					list($settings['public-key'], $settings['private-key']) = explode('--', $settings['authentication-key']);
				}

				$settings = wp_parse_args($settings, self::$default_settings);
				update_option(self::SETTINGS_KEY, $settings);
				wp_cache_set(self::SETTINGS_KEY, $settings, null, time() + CACHE_PERIOD);
			}
		}

		/// UTILITY

		private static function add_project_data($response_data, $project_id, $service) {
			$service->set_pagination_parameters(0, 150);

			$project_fonts_response = $service->list_project_fonts($project_id);
			$response_data['project_fonts'] = array();
			if('Success' == $project_fonts_response->Message) {
				$response_data['project_fonts'] = array_filter((array)$project_fonts_response->Font);
				usort($response_data['project_fonts'], array(__CLASS__, 'usort_sort_by_font_name'));

				$response_data['project_font_ids'] = array();
				foreach($response_data['project_fonts'] as $font) {
					$response_data['project_font_ids'][] = $font->FontID;
				}
			}

			$project_selectors_response = $service->list_selectors($project_id);
			$response_data['project_selectors'] = array();
			if('Success' == $project_selectors_response->Message) {
				$response_data['project_selectors'] = array_filter((array)$project_selectors_response->Selector);

				$selector_data = self::get_selector_data();
				foreach($response_data['project_selectors'] as $project_selector) {
					$project_selector->SelectorFallback = $selector_data[$project_selector->SelectorTag]['fallback'];
				}
			}

			return $response_data;
		}

		private static function get_nice_filter_type_text($filter_type) {
			$filtered = array('Alpha' => __('a Beginning Character'), 'FreeOrPaid' => __('Free or All Fonts'));

			if(isset($filtered[$filter_type])) {
				$filter_type = $filtered[$filter_type];
			} else {
				$filter_type = "a {$filter_type}";
			}

			return $filter_type;
		}

		private static function get_response($data = array(), $message = null, $error = false) {
			return array_merge(array('error' => (bool)$error, 'message' => $message), (array)$data);
		}

		private static function is_setup() {
			$settings = self::get_settings();

			return !empty($settings['authentication-key']);
		}

		/**
		 * @return WP_Web_Fonts_Service
		 */
		private static function require_library($public_key = null, $private_key = null) {
			require_once('lib/wp-web-fonts-service.php');

			$service = new WP_Web_Fonts_Service(self::APPLICATION_KEY);
			$service->set_credentials($public_key, $private_key);
			$service->set_logging_enabled(true);

			return $service;
		}

		private static function swap_data_from_projects($old_project_id, $new_project_id) {
			if($old_project_id != $new_project_id) {
				$settings = self::get_settings();

				$service = self::require_library($settings['public-key'], $settings['private-key']);
				$service->set_pagination_parameters(0, 1000);

				$old_selectors = $service->list_selectors($old_project_id);
				if('Success' == $old_selectors->Message) {
					$font_ids = array();
					$selector_ids = array();

					foreach($old_selectors->Selector as $selector) {
						$delete_selector_response = $service->delete_selector($old_project_id, $selector->SelectorID);
						$add_selector_response = $service->add_selector($new_project_id, $selector->SelectorTag);

						if('Success' == $add_selector_response->Message) {
							if($selector->SelectorFontID > 0) {
								$font_ids[] = $selector->SelectorFontID;

								foreach($add_selector_response->Selector as $added_selector) {
									if($selector->SelectorTag == $added_selector->SelectorTag) {
										$selector_ids[] = $added_selector->SelectorID;
									}
								}
							}
						}
					}
				}

				$old_fonts = $service->list_project_fonts($old_project_id);
				if('Success' == $old_fonts->Message) {
					foreach($old_fonts->Font as $font) {
						$service->delete_font($old_project_id, $font->FontID);
						$service->add_font($new_project_id, $font->FontID);
					}
				}

				$service->assign_to_selector($new_project_id, $font_ids, $selector_ids);

				$service->publish();
			}
		}

		private static function trim_r($data) {
			if(is_array($data)) {
				$trimmed = array();
				foreach($data as $key => $value) {
					$trimmed[$key] = self::trim_r($value);
				}
				return $trimmed;
			} else {
				return trim($data);
			}
		}

		public static function usort_sort_by_font_name($a, $b) {
			return strcmp($b->FontName, $a->FontName);
		}
	}

	require_once('lib/provider.php');
	Fonts_Com_Plugin::init();
}