<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if( !class_exists( 'csm_panel' ) ) {

    class csm_panel {
		
        public $panel_fields;
		public $plugin_slug;
		public $plugin_optionname;

        /**
		 * Constructor
		 */

		public function __construct( $fields = array() ) {

			$this->panel_fields = $fields;
			$this->plugin_slug = 'csm_panel_';
			$this->plugin_optionname = 'csm_settings';

			add_action('admin_menu', array(&$this, 'admin_menu') ,11);
			add_action('admin_init', array(&$this, 'add_script') ,11);
			add_action('admin_init', array(&$this, 'save_option') ,11);

		}

		/**
		 * Create option panel menu
		 */

		public function admin_menu() {

			global $admin_page_hooks;

			if ( !isset( $admin_page_hooks['tip_plugins_panel']) ) :

				add_menu_page(
					esc_html__('TIP Plugins', 'content-snippet-manager'),
					esc_html__('TIP Plugins', 'content-snippet-manager'),
					'manage_options',
					'tip_plugins_panel',
					NULL,
					plugins_url('/assets/images/tip-icon.png', dirname(__FILE__)),
					64
				);

			endif;

			add_submenu_page(
				'tip_plugins_panel',
				esc_html__('Content Snippet Manager', 'content-snippet-manager'),
				esc_html__('Content Snippet Manager', 'content-snippet-manager'),
				'manage_options',
				'csm_panel',
				array(&$this, 'csm_panel')
			);

			if ( isset( $admin_page_hooks['tip_plugins_panel'] ) )
				remove_submenu_page( 'tip_plugins_panel', 'tip_plugins_panel' );

		}

		/**
		 * Loads the plugin scripts and styles
		 */

		public function add_script() {

			 global $wp_version, $pagenow;

			 $file_dir = plugins_url('/assets/', dirname(__FILE__));
			 wp_enqueue_style ( 'csm_notice', $file_dir.'css/notice.css' );

			 if ( $pagenow == 'admin.php' ) {

				wp_enqueue_style ( 'csm_panel', $file_dir.'css/panel.css' );
				wp_enqueue_style ( 'csm_panel_freepro', $file_dir.'css/freepro.css' );
				wp_enqueue_style ( 'csm_panel_on_off', $file_dir.'css/on_off.css' );
				wp_enqueue_style ( 'csm_panel_googlefonts', '//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i');
				wp_enqueue_style ( 'csm_panel_select2', $file_dir.'css/select2.min.css' );

				wp_enqueue_script( 'jquery');
				wp_enqueue_script( 'jquery-ui-core', array('jquery'));
				wp_enqueue_script( 'jquery-ui-tabs', array('jquery'));
			 	wp_enqueue_script( 'jquery-ui-sortable', array('jquery'));

				wp_enqueue_script( 'csm_panel_on_off', $file_dir.'js/on_off.js','3.5', '', TRUE);
				wp_enqueue_script( 'csm_panel_select2', $file_dir.'js/select2.min.js','3.5', '', TRUE);
				wp_enqueue_script( 'csm_panel_ace', $file_dir.'js/ace/ace.js','1.2.8', '', TRUE);
				wp_enqueue_script( 'csm_panel_theme_tomorrow', $file_dir.'js/ace/theme-tomorrow.js','1.2.8', '', TRUE);
				wp_enqueue_script( 'csm_panel_mode_html', $file_dir.'js/ace/mode-html.js','1.2.8', '', TRUE);
				wp_enqueue_script( 'csm_panel', $file_dir.'js/panel.js',array('jquery','thickbox'),'1.0',TRUE );

			 }

		}

		/**
		 * Message after the options saving
		 */

		public function save_message () {

			global $csm_message;
			$plugin_slug = $this->plugin_slug;

			if (isset($csm_message))
				echo '<div id="message" class="updated fade message_save ' . $plugin_slug . 'message"><p><strong> ' . $csm_message . '</strong></p></div>';

		}

		/**
		 * Sanitize icon function
		 */

		public function sanitize_script_position_function($k) {

			$allowedOptions = array(
				'scriptOnHeader',
				'scriptOnBody',
				'scriptOnFooter',
				'woocommerceConversion',
				'scriptOnExcerpt',
				'scriptOnContent'
			);

			if ( in_array($k, $allowedOptions)) {

				return $k;

			} else {

				return 'scriptOnContent';

			}

		}

		/**
		 * Sanitize boolean function
		 */

		public function sanitize_bool_function($k) {

			return ( $k == 'on' ) ? 'on' : 'off';

		}

		/**
		 * Sanitize matchValue function
		 */

		public function sanitize_matchValue_function($k) {

			return ( $k == 'include' ) ? 'include' : 'exclude';

		}

		/**
		 * Sanitize postID function
		 */

		public function sanitize_postID_function($k) {

			if ( is_array($k) ) :

				if (in_array('-1', $k)) {

					return array('-1');

				} else {

					foreach ($k as $v) {

						$postTitle = get_the_title($v);

						if ( true == post_exists($postTitle) ) {

							$error = false;

						} elseif ( false == post_exists($postTitle) ) {

							$error = true;

						}

					}

					return ($error == false ) ? $k : array('-1');

				}

			else:

				return array('-1');

			endif;

		}

		/**
		 * Sanitize taxonomies function
		 */

		public function sanitize_taxID_function($k) {

			if ( is_array($k) ) :

				if (in_array('-1', $k)) {

					return array('-1');

				} else {

					foreach ($k as $v) {

		    		$term = get_term($v);

						if ( true == term_exists($term->name) ) {
	
							$error = false;
	
						} elseif ( false == term_exists($term->name) ) {
	
							$error = true;
	
						}

					}

					return ($error == false ) ? $k : array('-1');

				}

			else:

				return array('-1');

			endif;

		}

		/*-----------------------------------------------------------------------------------*/
		/* SANITIZE CODE */
		/*-----------------------------------------------------------------------------------*/

		public function sanitize_code( $content = '' ) {

		  $replace = array (
		    '/(<(script|noscript|style)\b[^>]*>).*?(<\/\2>)/is',
		    '/(&lt;(script|noscript|style)\b[^>]*&gt;).*?(&lt;\/\2&gt;)/is',
		  );

		  $content = preg_replace ($replace, '', $content);

		  $allowed_atts = array(
		    'align'      => array(),
		    'class'      => array(),
		    'type'       => array(),
		    'id'         => array(),
		    'dir'        => array(),
		    'lang'       => array(),
		    'style'      => array(),
		    'xml:lang'   => array(),
		    'src'        => array(),
		    'alt'        => array(),
		    'href'       => array(),
		    'rel'        => array(),
		    'rev'        => array(),
		    'target'     => array(),
		    'novalidate' => array(),
		    'type'       => array(),
		    'value'      => array(),
		    'name'       => array(),
		    'tabindex'   => array(),
		    'action'     => array(),
		    'method'     => array(),
		    'for'        => array(),
		    'width'      => array(),
		    'height'     => array(),
		    'data'       => array(),
		    'title'      => array(),
		    'style'      => array(),
		    'scrolling'  => array(),
		    'border'     => array(),
		    'frameborder'     => array(),
		    'marginwidth' => array(),
		  );
		  $allowedposttags['form']     = $allowed_atts;
		  $allowedposttags['label']    = $allowed_atts;
		  $allowedposttags['input']    = $allowed_atts;
		  $allowedposttags['textarea'] = $allowed_atts;
		  $allowedposttags['iframe']   = $allowed_atts;
		  $allowedposttags['strong']   = $allowed_atts;
		  $allowedposttags['small']    = $allowed_atts;
		  $allowedposttags['table']    = $allowed_atts;
		  $allowedposttags['span']     = $allowed_atts;
		  $allowedposttags['abbr']     = $allowed_atts;
		  $allowedposttags['code']     = $allowed_atts;
		  $allowedposttags['pre']      = $allowed_atts;
		  $allowedposttags['div']      = $allowed_atts;
		  $allowedposttags['img']      = $allowed_atts;
		  $allowedposttags['h1']       = $allowed_atts;
		  $allowedposttags['h2']       = $allowed_atts;
		  $allowedposttags['h3']       = $allowed_atts;
		  $allowedposttags['h4']       = $allowed_atts;
		  $allowedposttags['h5']       = $allowed_atts;
		  $allowedposttags['h6']       = $allowed_atts;
		  $allowedposttags['ol']       = $allowed_atts;
		  $allowedposttags['ul']       = $allowed_atts;
		  $allowedposttags['li']       = $allowed_atts;
		  $allowedposttags['em']       = $allowed_atts;
		  $allowedposttags['hr']       = $allowed_atts;
		  $allowedposttags['br']       = $allowed_atts;
		  $allowedposttags['tr']       = $allowed_atts;
		  $allowedposttags['td']       = $allowed_atts;
		  $allowedposttags['p']        = $allowed_atts;
		  $allowedposttags['a']        = $allowed_atts;
		  $allowedposttags['b']        = $allowed_atts;
		  $allowedposttags['i']        = $allowed_atts;

		  return wp_kses($content, $allowedposttags);

		}

		/**
		* Multidimensional Array sanitize function
		*/

		public function array_sanitize_function($array, $snippetID) {

			$tosave = array();

			$tosave['name'] = sanitize_text_field($array['slot' . $snippetID]['name']);
			$tosave['position'] = $this->sanitize_script_position_function($array['slot' . $snippetID]['position']);
			$tosave['excerptLimit'] = sanitize_text_field($array['slot' . $snippetID]['excerptLimit']);
			$tosave['contentLimit'] = sanitize_text_field($array['slot' . $snippetID]['contentLimit']);
			$tosave['include_home'] = $this->sanitize_bool_function($array['slot' . $snippetID]['include_home']);
			$tosave['include_search'] = $this->sanitize_bool_function($array['slot' . $snippetID]['include_search']);
			$tosave['include_whole_website'] = $this->sanitize_bool_function($array['slot' . $snippetID]['include_whole_website']);

			foreach (csm_get_custom_post_list() as $v ) {
				
                $tosave[$v . '_matchValue'] = $this->sanitize_matchValue_function($array['slot' . $snippetID][$v . '_matchValue']);
                
                if (isset($array['slot' . $snippetID]['include_' . $v])) :
                    $tosave['include_' . $v] = $this->sanitize_postID_function($array['slot' . $snippetID]['include_' . $v]);
                endif;
                
                if (isset($array['slot' . $snippetID]['exclude_' . $v])) :
                    $tosave['exclude_' . $v] = $this->sanitize_postID_function($array['slot' . $snippetID]['exclude_' . $v]);
                endif;
                
            }

			foreach (csm_get_taxonomies_list() as $v ) {
				
                $tosave[$v . '_matchValue'] = $this->sanitize_matchValue_function($array['slot' . $snippetID][$v . '_matchValue']);
				
                if (isset($array['slot' . $snippetID]['include_' . $v])) :
                    $tosave['include_' . $v] = $this->sanitize_taxID_function($array['slot' . $snippetID]['include_' . $v]);
                endif;
                
                if (isset($array['slot' . $snippetID]['exclude_' . $v])) :
                    $tosave['exclude_' . $v] = $this->sanitize_taxID_function($array['slot' . $snippetID]['exclude_' . $v]);
                endif;
			
            }

			$tosave['code'] = $this->sanitize_code($array['slot' . $snippetID]['code']);

			return $tosave;

		}

		/**
		 * Save options function
		 */

		public function save_option() {

			global $csm_message;

			$csm_setting = get_option($this->plugin_optionname);

			if ( $csm_setting != false ) :

				$csm_setting = maybe_unserialize( get_option( $this->plugin_optionname ) );

			else :

				$csm_setting = array();

			endif;

			if (isset($_GET['action']) && ($_GET['action'] == 'csm_backup_download')) {

				header("Cache-Control: public, must-revalidate");
				header("Pragma: hack");
				header("Content-Type: text/plain");
				header('Content-Disposition: attachment; filename="csm_backup.dat"');
				echo serialize($this->get_options());
				exit;

			}

			if (isset($_GET['action']) && ($_GET['action'] == 'csm_backup_reset')) {

				update_option( $this->plugin_optionname,'');
				wp_redirect(admin_url('admin.php?page=csm_panel&tab=Import_Export'));
				exit;

			}

			if (isset($_POST['csm_upload_backup']) && check_admin_referer('csm_restore_options', 'csm_restore_options')) {

				if ($_FILES["csm_upload_file"]["error"] <= 0) {

					$options = unserialize(file_get_contents($_FILES["csm_upload_file"]["tmp_name"]));

					if ($options) {

						foreach ($options as $option) {
							update_option( $this->plugin_optionname, unserialize($option->option_value));

						}

					}

				}

				wp_redirect(admin_url('admin.php?page=csm_panel&tab=Import_Export'));
				exit;

			}

			if ( $this->csm_request('csm_new_snippet_action') !== null ) {

				if (isset($csm_setting['csm_snippets']) && is_array($csm_setting['csm_snippets'])) {
					$getlastSlot = key(array_slice($csm_setting['csm_snippets'], -1, 1, true));
				} else {
					$getlastSlot = 'slot0';
				}

				$lastSlot = str_replace('slot', '', $getlastSlot);
				$newSlot = intval($lastSlot) + 1;
				
				$csm_setting['csm_snippets']['slot' . $newSlot] = array('name' => 'Undefined snippet ' . $newSlot);
				update_option($this->plugin_optionname, $csm_setting);
				wp_redirect(admin_url('admin.php?page=csm_panel&tab=Snippet_Generator&snippetID=' . $newSlot));

			}

			if ( $this->csm_request('csm_delete_snippet_action') !== null ) {

				unset($csm_setting['csm_snippets']['slot' . $_POST['csm_snippet_id']]);
				update_option($this->plugin_optionname, $csm_setting);

			}

			if ( $this->csm_request('csm_save_snippet_action') !== null ) {

				foreach ( $this->panel_fields as $element ) {

					if ( isset($element['tab']) && $element['tab'] == $_GET['tab'] ) {

						foreach ($element as $value ) {

							if ( isset($value['id']) && ( $value['id'] == 'csm_snippets' ) ) {

								if ( isset($_POST[$value["id"]]) ) {

									$snippetID = absint($_POST['csm_snippet_id']);
									$csm_setting['csm_snippets']['slot'. $snippetID] = $this->array_sanitize_function($_POST[$value["id"]], $snippetID);
									update_option($this->plugin_optionname, $csm_setting );

								} else {

									unset($csm_setting['csm_snippets']);
									update_option( $this->plugin_optionname, $csm_setting );

								}

							}

							$csm_message = esc_html__('Options saved successfully.', 'content-snippet-manager' );

						}

					}

				}

			}

		}

		/**
		 * Get options
		 */

		public function get_options() {

			global $wpdb;
			return $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name = '".$this->plugin_optionname."'");

		}

		/**
		 * Request function
		 */

		public function csm_request($id) {

			if (isset($_REQUEST[$id]))
				return esc_html($_REQUEST[$id]);

		}

		/**
		 * Option panel
		 */

		public function csm_panel() {

			global $csm_message;

			$csmForm = new csm_form();
			$plugin_slug =  $this->plugin_slug;

			if (!isset($_GET['tab']))
				$_GET['tab'] = "Snippet_Generator";

			foreach ( $this->panel_fields as $element) {

				if (isset($element['type'])) :

					switch ( $element['type'] ) {

						case 'navigation':

							echo $csmForm->elementStart('div', $plugin_slug . 'tabs', FALSE );

								echo $csmForm->elementStart('div', $plugin_slug . 'header', FALSE );

									echo $csmForm->elementStart('div', FALSE, 'left plugin_description' );

										echo $csmForm->element('h2', FALSE, 'maintitle', esc_html__( 'Content Snippet Manager','content-snippet-manager'));
										echo $csmForm->element('span', FALSE, FALSE, esc_html__( 'Version: ','content-snippet-manager') . CSM_VERSION);
										echo $csmForm->link('https://www.themeinprogress.com', FALSE, FALSE, '_blank', FALSE, esc_html__( 'by ThemeinProgress','content-snippet-manager') );
										echo $csmForm->link('demo.themeinprogress.com/content-snippet-manager-pro/', FALSE, FALSE, '_blank', FALSE, esc_html__( ' - Documentation','content-snippet-manager') );

										echo $csmForm->link('https://wordpress.org/support/plugin/content-snippet-manager/', FALSE, FALSE, '_blank', FALSE, esc_html__( ' - Support','content-snippet-manager') );
										echo $csmForm->link('https://wordpress.org/support/plugin/content-snippet-manager/reviews/', FALSE, FALSE, '_blank', FALSE, esc_html__( ' - Rate this plugin on WordPress.org','content-snippet-manager') );

									echo $csmForm->elementEnd('div');

									echo $csmForm->element('div', FALSE, 'clear', FALSE);

								echo $csmForm->elementEnd('div');

								$this->save_message();

								echo $csmForm->htmlList('ul', FALSE, $plugin_slug . 'navigation', $element['item'], esc_attr($_GET['tab']));

						break;

						case 'end-tab':

								echo $csmForm->element('div', FALSE, 'clear', FALSE);

							echo $csmForm->elementEnd('div');

						break;

					}

				endif;

			if (isset($element['tab'])) :

				switch ( $element['tab'] ) {

					case esc_attr($_GET['tab']):

						foreach ($element as $value) {

							if (isset($value['type'])) :

								switch ( $value['type'] ) {

								case 'start-form':

									echo $csmForm->elementStart('div', str_replace(' ', '', $value['name']), FALSE );

										echo $csmForm->formStart('post', '?page=csm_panel&tab=' . esc_attr($_GET['tab']) );

								break;

								case 'end-form':

										echo $csmForm->formEnd();

									echo $csmForm->elementEnd('div');

								break;

								case 'start-container':

									$class = ( 'Save' == $this->csm_request('csm_action') && $value['val'] == $this->csm_request('element-opened') ) ? ' inactive' : '';

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'container' );

										echo $csmForm->element('h5', $value['val'], 'element ' . $class, $value['name'] );

										echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'mainbox' );

								break;

								case 'start-open-container':

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'container' );

										echo $csmForm->element('h5', FALSE, 'element-open', $value['name'] );

										echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'mainbox csm_openbox' );

								break;

								case 'end-container':

										echo $csmForm->elementEnd('div');

									echo $csmForm->elementEnd('div');

								break;

								case 'checkbox':

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box');

										echo $csmForm->elementStart('div', FALSE, 'input-left' );

											echo $csmForm->label($value['id'], $value['name']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->elementStart('div', FALSE, 'input-right' );

											echo $csmForm->checkbox($value['id'], $value['options'], csm_setting($value['id']));
											echo $csmForm->element('p', FALSE, FALSE, $value['desc']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->element('div', FALSE, 'clear', FALSE);

									echo $csmForm->elementEnd('div');

								break;

								case 'text':

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box' );

										echo $csmForm->elementStart('div', FALSE, 'input-left' );

											echo $csmForm->label($value['id'], $value['name']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->elementStart('div', FALSE, 'input-right' );

											echo $csmForm->input($value['id'], $value['id'], FALSE, $value['type'], csm_setting($value['id'], $value['std']));
											echo $csmForm->element('p', FALSE, FALSE, $value['desc']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->element('div', FALSE, 'clear', FALSE);

									echo $csmForm->elementEnd('div');

								break;

								case 'license_key':

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box' );

										echo $csmForm->elementStart('div', FALSE, 'input-left' );

											echo $csmForm->label($value['id'], $value['name']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->elementStart('div', FALSE, 'input-right' );

											echo $csmForm->input($value['id'], $value['id'], FALSE, 'text', get_option($value['id']));
											echo $csmForm->element('p', FALSE, FALSE, $value['desc']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->element('div', FALSE, 'clear', FALSE);

									echo $csmForm->elementEnd('div');

								break;

								case "upload":

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box');

										echo $csmForm->elementStart('div', FALSE, 'input-left' );

											echo $csmForm->label($value['id'], $value['name']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->elementStart('div', FALSE, 'input-right' );

											echo $csmForm->input($value['id'], $value['id'], 'upload_attachment', 'text', csm_setting($value['id'], $value['std']));
											echo $csmForm->element('p', FALSE, FALSE, $value['desc']);
											echo $csmForm->input(FALSE, FALSE, 'button upload_button', 'button', esc_attr__('Upload', 'content-snippet-manager'));

										echo $csmForm->elementEnd('div');

										echo $csmForm->element('div', FALSE, 'clear', FALSE);

									echo $csmForm->elementEnd('div');

								break;

								case 'textarea':

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box');

										echo $csmForm->elementStart('div', FALSE, 'input-left' );

											echo $csmForm->label($value['id'], $value['name']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->elementStart('div', FALSE, 'input-right' );

											echo $csmForm->textarea($value['id'], $value['id'], FALSE, csm_setting($value['id'], $value['std']), FALSE);
											echo $csmForm->element('p', FALSE, FALSE, $value['desc']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->element('div', FALSE, 'clear', FALSE);

									echo $csmForm->elementEnd('div');

								break;

								case 'on-off':

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box');

										echo $csmForm->elementStart('div', FALSE, 'input-left' );

											echo $csmForm->label($value['id'], $value['name']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->elementStart('div', FALSE, 'input-right' );

											echo $csmForm->elementStart('div', FALSE,  $plugin_slug . 'slider ' . csm_setting($value['id'], $value['std']) );

												echo $csmForm->elementStart('div', FALSE, 'inset' );
												echo $csmForm->element('div', FALSE, 'control', FALSE);
												echo $csmForm->elementEnd('div');

												echo $csmForm->input($value['id'], $value['id'], 'on-off', 'hidden', csm_setting($value['id'], $value['std']));

											echo $csmForm->elementEnd('div');

											echo $csmForm->element('div', FALSE, 'clear', FALSE);
											echo $csmForm->element('p', FALSE, FALSE, $value['desc'] );

										echo $csmForm->elementEnd('div');

										echo $csmForm->element('div', FALSE, 'clear', FALSE);

									echo $csmForm->elementEnd('div');

								break;

								case 'select':

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box');

										echo $csmForm->elementStart('div', FALSE, 'input-left' );

											echo $csmForm->label($value['id'], $value['name']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->elementStart('div', FALSE, 'input-right' );

											echo $csmForm->select($value['id'], $value['id'], FALSE, $value['options'], csm_setting($value['id'], $value['std']), FALSE);
											echo $csmForm->element('p', FALSE, FALSE, $value['desc']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->element('div', FALSE, 'clear', FALSE);

									echo $csmForm->elementEnd('div');

								break;

								case 'AjaxSelect2':

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box');

										echo $csmForm->elementStart('div', FALSE, 'input-left' );

											echo $csmForm->label($value['id'], $value['name']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->elementStart('div', FALSE, 'input-right' );

											echo $csmForm->ajaxSelect($value['id'], $value['id'], $value['class'], csm_setting($value['id']), $value['dataType']);
											echo $csmForm->element('p', FALSE, FALSE, $value['desc']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->element('div', FALSE, 'clear', FALSE);

									echo $csmForm->elementEnd('div');

								break;

								case "save-button":

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box WIP_plugin_save_box');

										echo $csmForm->input($value['action'], FALSE, 'button', 'submit', $value['value']);

									echo $csmForm->elementEnd('div');

								break;

								case "activation-button":

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box WIP_plugin_save_box');

										echo $csmForm->input( CSMP_ITEM_SLUG . '_activate_license', FALSE, 'button', 'submit', 'Activate');
										echo $csmForm->input( CSMP_ITEM_SLUG . '_deactivate_license', FALSE, 'button', 'submit', 'Deactivate');

									echo $csmForm->elementEnd('div');

								break;

								case 'color':

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box' );

										echo $csmForm->elementStart('div', FALSE, 'input-left' );

											echo $csmForm->label($value['id'], $value['name']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->elementStart('div', FALSE, 'input-right' );

											echo $csmForm->color($value['id'], $value['id'], $plugin_slug . 'color', 'text', csm_setting($value['id'], $value['std']));
											echo $csmForm->element('p', FALSE, FALSE, $value['desc']);

										echo $csmForm->elementEnd('div');

										echo $csmForm->element('div', FALSE, 'clear', FALSE);

									echo $csmForm->elementEnd('div');

								break;

								case 'import_export':

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box' );

										echo $csmForm->elementStart('div', FALSE, 'input-left' );

											echo $csmForm->label(FALSE, esc_html__('Current plugin settings','content-snippet-manager'));

										echo $csmForm->elementEnd('div');

										echo $csmForm->elementStart('div', FALSE, 'input-right' );

											echo $csmForm->textarea(FALSE, FALSE, 'widefat code', serialize($this->get_options()), TRUE);

											$exportURL = esc_url('?page=csm_panel&tab=Import_Export&action=csm_backup_download');
											echo $csmForm->link($exportURL, FALSE, 'button button-secondary', '_self', FALSE, esc_html__( 'Download current plugin settings','content-snippet-manager') );

										echo $csmForm->elementEnd('div');

										echo $csmForm->element('div', FALSE, 'clear', FALSE);

									echo $csmForm->elementEnd('div');

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box' );

										echo $csmForm->elementStart('div', FALSE, 'input-left' );

											echo $csmForm->label(FALSE, esc_html__('Reset plugin settings','content-snippet-manager'));

										echo $csmForm->elementEnd('div');

										echo $csmForm->elementStart('div', FALSE, 'input-right' );

											$resetURL = esc_url('?page=csm_panel&tab=Import_Export&action=csm_backup_reset');
											echo $csmForm->link($resetURL, FALSE, 'button-secondary csm_restore_settings', '_self', FALSE, esc_html__( 'Reset plugin settings','content-snippet-manager') );

											echo $csmForm->element('p', FALSE, FALSE, esc_html__( 'If you click the button above, the plugin options return to its default values','content-snippet-manager'));

										echo $csmForm->elementEnd('div');

										echo $csmForm->element('div', FALSE, 'clear', FALSE);

									echo $csmForm->elementEnd('div');

									echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box' );

										echo $csmForm->elementStart('div', FALSE, 'input-left' );

											echo $csmForm->label(FALSE, esc_html__('Import plugin settings','content-snippet-manager'));

										echo $csmForm->elementEnd('div');

										echo $csmForm->elementStart('div', FALSE, 'input-right' );

											echo $csmForm->input('csm_upload_file', FALSE, FALSE, 'file', FALSE);
											echo $csmForm->input('csm_upload_backup', 'csm_upload_backup', 'button-primary', 'submit', esc_html__( 'Import plugin settings','content-snippet-manager'));
											function_exists('wp_nonce_field') ? wp_nonce_field('csm_restore_options', 'csm_restore_options') : '' ;

										echo $csmForm->elementEnd('div');

										echo $csmForm->element('div', FALSE, 'clear', FALSE);

									echo $csmForm->elementEnd('div');

								break;

								case 'free_vs_pro':

								echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box' );

									echo $csmForm->tableStart(FALSE, $plugin_slug . ' card table free-pro', 0, 0 );

									echo $csmForm->tableElementStart('tbody', FALSE, 'table-body');

										echo $csmForm->tableElementStart('tr', FALSE, 'table-head');

											echo $csmForm->tableElement('th', FALSE, 'large');

											echo $csmForm->tableElementStart('th', FALSE, 'indicator');
												echo esc_html__('Free', 'content-snippet-manager');
											echo $csmForm->tableElementEnd('th');

											echo $csmForm->tableElementStart('th', FALSE, 'indicator');
												echo esc_html__('Premium', 'content-snippet-manager');
											echo $csmForm->tableElementEnd('th');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Snippet on Post Excerpt', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Snippet on Post Content', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Snippet on Header', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');
										
										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Snippet on Body', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Snippet on Footer', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Snippet on WooCommerce conversion page', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Add the snippet on whole website', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Add the snippet on specific content', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Advanced combinations and exclusions', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Shortcodes support', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Unlimited snippets', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Backup section', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Javascript and CSS code on Header', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

													echo $csmForm->elementStart('div', FALSE, 'feature-inline-row' );

														echo $csmForm->element('span', FALSE, 'info-icon dashicon dashicons dashicons-info', FALSE );

														echo $csmForm->elementStart('span', FALSE, 'feature-description' );

															echo esc_html__('You can insert your own Javascript and CSS code on the wp_head hook.', 'content-snippet-manager');

														echo $csmForm->elementEnd('span');

													echo $csmForm->elementEnd('div');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-no-alt', FALSE);

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Javascript and CSS code on Body', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

													echo $csmForm->elementStart('div', FALSE, 'feature-inline-row' );

														echo $csmForm->element('span', FALSE, 'info-icon dashicon dashicons dashicons-info', FALSE );

														echo $csmForm->elementStart('span', FALSE, 'feature-description' );

															echo esc_html__('You can insert your own Javascript and CSS code on the wp_body_open hook.', 'content-snippet-manager');

														echo $csmForm->elementEnd('span');

													echo $csmForm->elementEnd('div');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-no-alt', FALSE);

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Javascript and CSS code on Footer', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

													echo $csmForm->elementStart('div', FALSE, 'feature-inline-row' );

														echo $csmForm->element('span', FALSE, 'info-icon dashicon dashicons dashicons-info', FALSE );

														echo $csmForm->elementStart('span', FALSE, 'feature-description' );

															echo esc_html__('You can insert your own Javascript and CSS code on the wp_footer hook.', 'content-snippet-manager');

														echo $csmForm->elementEnd('span');

													echo $csmForm->elementEnd('div');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-no-alt', FALSE);

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Javascript and CSS code on WooCommerce conversion page', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

													echo $csmForm->elementStart('div', FALSE, 'feature-inline-row' );

														echo $csmForm->element('span', FALSE, 'info-icon dashicon dashicons dashicons-info', FALSE );

														echo $csmForm->elementStart('span', FALSE, 'feature-description' );

															echo esc_html__('You can insert your own Javascript and CSS code inside the final thank you page of WooCommerce.', 'content-snippet-manager');

														echo $csmForm->elementEnd('span');

													echo $csmForm->elementEnd('div');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-no-alt', FALSE);

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Device selection', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

													echo $csmForm->elementStart('div', FALSE, 'feature-inline-row' );

														echo $csmForm->element('span', FALSE, 'info-icon dashicon dashicons dashicons-info', FALSE );

														echo $csmForm->elementStart('span', FALSE, 'feature-description' );

															echo esc_html__('Select one or more devices where you can load the script.', 'content-snippet-manager');

														echo $csmForm->elementEnd('span');

													echo $csmForm->elementEnd('div');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-no-alt', FALSE);

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Dynamic conversion values', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

													echo $csmForm->elementStart('div', FALSE, 'feature-inline-row' );

														echo $csmForm->element('span', FALSE, 'info-icon dashicon dashicons dashicons-info', FALSE );

														echo $csmForm->elementStart('span', FALSE, 'feature-description' );

															echo esc_html__('The dynamic conversion values allow you to use specific parameters of a WooCommerce order inside your tracking codes, when a user has been redirected to the final thank you page.', 'content-snippet-manager');

														echo $csmForm->elementEnd('span');

													echo $csmForm->elementEnd('div');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-no-alt', FALSE);

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Add the snippet on custom posts types', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

													echo $csmForm->elementStart('div', FALSE, 'feature-inline-row' );

														echo $csmForm->element('span', FALSE, 'info-icon dashicon dashicons dashicons-info', FALSE );

														echo $csmForm->elementStart('span', FALSE, 'feature-description' );

															echo esc_html__('You can include a script inside all available custom post types available on WordPress, instead of only the WordPress posts, pages and WooCommerce products.', 'content-snippet-manager');

														echo $csmForm->elementEnd('span');

													echo $csmForm->elementEnd('div');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-no-alt', FALSE);

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Add the snippet on custom taxonomies', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

													echo $csmForm->elementStart('div', FALSE, 'feature-inline-row' );

														echo $csmForm->element('span', FALSE, 'info-icon dashicon dashicons dashicons-info', FALSE );

														echo $csmForm->elementStart('span', FALSE, 'feature-description' );

															echo esc_html__('You can include a script inside all available custom taxonomies available on WordPress, instead of only the WordPress post categories, tags and WooCommerce categories.', 'content-snippet-manager');

														echo $csmForm->elementEnd('span');

													echo $csmForm->elementEnd('div');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-no-alt', FALSE);

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $csmForm->tableElementStart('td', FALSE, 'large');

												echo $csmForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $csmForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('User role selection', 'content-snippet-manager');

													echo $csmForm->elementEnd('h4');

													echo $csmForm->elementStart('div', FALSE, 'feature-inline-row' );

														echo $csmForm->element('span', FALSE, 'info-icon dashicon dashicons dashicons-info', FALSE );

														echo $csmForm->elementStart('span', FALSE, 'feature-description' );

															echo esc_html__('If needed, you can hide each script for specific user roles, like the administrator.', 'content-snippet-manager');

														echo $csmForm->elementEnd('span');

													echo $csmForm->elementEnd('div');

												echo $csmForm->elementEnd('div');

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-no-alt', FALSE);

											echo $csmForm->tableElementEnd('td');

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

										echo $csmForm->tableElementStart('tr', FALSE, 'upsell-row');

											echo $csmForm->tableElement('td', FALSE, FALSE);
											echo $csmForm->tableElement('td', FALSE, FALSE);

											echo $csmForm->tableElementStart('td', FALSE, 'indicator');

												echo $csmForm->link(esc_url(CSM_UPGRADE_LINK . '/?ref=2&campaign=csm-freepro'), FALSE, 'button button-primary', '_blank', FALSE, esc_html__( 'Upgrade to Premium','content-snippet-manager') );

											echo $csmForm->tableElementEnd('td');

										echo $csmForm->tableElementEnd('tr');

									echo $csmForm->tableElementEnd('tbody');

									echo $csmForm->tableEnd();

								echo $csmForm->elementEnd('div');

								break;

								case 'scriptGenerator':

									$csm_snippets = csm_setting($value['id']);

									echo $csmForm->elementStart('div', FALSE, 'csm_scriptGenerator' );

										echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'snippet_order' );

										if (!isset($_REQUEST['snippetID'])) {

											if ($csm_snippets) {

												foreach ( $csm_snippets as $k => $v) {

													echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'container' );

														echo $csmForm->element('h5', FALSE, 'element linkable-element', '<a href="?page=csm_panel&tab=' . esc_attr($_GET['tab']) .'&snippetID='.str_replace('slot','',$k).'">'.$v['name'].'</a>' );

													echo $csmForm->elementEnd('div');

												}

											}

											echo $csmForm->input('csm_new_snippet_action', FALSE, 'button csm-new-snippet', 'submit', esc_html__( 'New snippet','content-snippet-manager'));

										} else {

											$snippetID = $_REQUEST['snippetID'];

											if (!array_key_exists('slot' . $snippetID, $csm_snippets)) {
												wp_redirect(admin_url('admin.php?page=csm_panel&tab=Snippet_Generator'));
											}

											echo $csmForm->input('csm_snippet_id', FALSE, FALSE, 'hidden', $snippetID);

												echo $csmForm->elementStart('div', 'slot' . $snippetID, $plugin_slug . 'container' );

													echo $csmForm->element('h5', FALSE, 'element', ($csm_snippets['slot' . $snippetID]['name']) ? sanitize_text_field($csm_snippets['slot' . $snippetID]['name']) : esc_html__('Undefined snippet.', 'content-snippet-manager' ) );

													echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'mainbox-open' );

														echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box' );

															echo $csmForm->elementStart('div', FALSE, 'input-left' );

																echo $csmForm->label(FALSE, esc_html__( 'Snippet name.', 'content-snippet-manager'));

															echo $csmForm->elementEnd('div');

															echo $csmForm->elementStart('div', FALSE, 'input-right' );

																echo $csmForm->input($value['id'].'[slot' . $snippetID . '][name]', FALSE, FALSE, 'text',  ($csm_snippets['slot' . $snippetID]['name']) ? sanitize_text_field($csm_snippets['slot' . $snippetID]['name']) : esc_html__('Undefined snippet.', 'content-snippet-manager' ));
																echo $csmForm->element('p', FALSE, FALSE, esc_html__('Add the name of this script.','content-snippet-manager'));

															echo $csmForm->elementEnd('div');

															echo $csmForm->element('div', FALSE, 'clear', FALSE);

														echo $csmForm->elementEnd('div');

														echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box' );

															echo $csmForm->elementStart('div', FALSE, 'input-left' );

																echo $csmForm->label(FALSE, esc_html__( 'Snippet position.', 'content-snippet-manager'));

															echo $csmForm->elementEnd('div');

															echo $csmForm->elementStart('div', FALSE, 'input-right' );
																$defaultPosition = (isset($csm_snippets['slot' . $snippetID]['position'])) ? $this->sanitize_script_position_function($csm_snippets['slot' . $snippetID]['position']) : 'scriptOnContent';

																$options = array(
																	'scriptOnHeader' => esc_html__('Header snippet','content-snippet-manager'),
																	'scriptOnFooter' => esc_html__('Footer snippet','content-snippet-manager'),
																	'woocommerceConversion' => esc_html__('WooCommerce conversion snippet','content-snippet-manager'),
																	'scriptOnExcerpt' => esc_html__('Post Excerpt','content-snippet-manager'),
																	'scriptOnContent' => esc_html__('Post Content','content-snippet-manager'),
																);

																if ( function_exists('wp_body_open'))
																	$options = array_slice($options, 0, 1, true) +  array('scriptOnBody' => esc_html__('Body snippet','content-snippet-manager')) + array_slice($options, 1, count($options) - 1, true) ;

																if ( !csm_is_woocommerce_active())
																	unset($options['woocommerceConversion']);

																echo $csmForm->select($value['id'].'[slot' .$snippetID . '][position]', FALSE, 'selectValue', $options, $defaultPosition, 'data-type="position"');

																echo $csmForm->element('p', FALSE, FALSE, esc_html__('Select a position for this script','content-snippet-manager'));

															echo $csmForm->elementEnd('div');

															echo $csmForm->element('div', FALSE, 'clear', FALSE);

														echo $csmForm->elementEnd('div');

														echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box excerptLimit' );

															echo $csmForm->elementStart('div', FALSE, 'input-left' );

																echo $csmForm->label(FALSE, esc_html__( 'After how many characters do you want to load this snippet?', 'content-snippet-manager'));

															echo $csmForm->elementEnd('div');

															echo $csmForm->elementStart('div', FALSE, 'input-right' );

																echo $csmForm->input($value['id'] . '[slot' . $snippetID . '][excerptLimit]', FALSE, FALSE, 'number', (isset($csm_snippets['slot' . $snippetID]['excerptLimit'])) ? sanitize_text_field($csm_snippets['slot' . $snippetID]['excerptLimit']) : '0', '-1');

																echo $csmForm->element('p', FALSE, FALSE, esc_html__('Type 0 to load the snippet before the post excerpt, -1 to load the snippet after the post excerpt or any value, our system avoid to break the post excerpt.','content-snippet-manager'));

															echo $csmForm->elementEnd('div');

															echo $csmForm->element('div', FALSE, 'clear', FALSE);

														echo $csmForm->elementEnd('div');

														echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box contentLimit' );

															echo $csmForm->elementStart('div', FALSE, 'input-left' );

																echo $csmForm->label(FALSE, esc_html__( 'After how many paragraphs do you want to load this snippet?', 'content-snippet-manager'));

															echo $csmForm->elementEnd('div');

															echo $csmForm->elementStart('div', FALSE, 'input-right' );

																echo $csmForm->input($value['id'] . '[slot' . $snippetID . '][contentLimit]', FALSE, FALSE, 'number', (isset($csm_snippets['slot' . $snippetID]['contentLimit'])) ? sanitize_text_field($csm_snippets['slot' . $snippetID]['contentLimit']) :  '0', '-1');
																echo $csmForm->element('p', FALSE, FALSE, esc_html__('Type 0 to load the snippet before the post content, -1 to load the snippet after the post content or any value, our system avoid to break the post content.','content-snippet-manager'));

															echo $csmForm->elementEnd('div');

															echo $csmForm->element('div', FALSE, 'clear', FALSE);

														echo $csmForm->elementEnd('div');

														echo $csmForm->elementStart('div', FALSE, 'switchSection');

															$includeHome = (isset($csm_snippets['slot' . $snippetID]['include_home']) && $csm_snippets['slot' . $snippetID]['include_home'] == 'on') ? 'on' : 'off';

															echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box');

																echo $csmForm->elementStart('div', FALSE, 'input-left' );

																	echo $csmForm->label('Homepage.', esc_html__( 'Homepage', 'content-snippet-manager'));

																echo $csmForm->elementEnd('div');

																echo $csmForm->elementStart('div', FALSE, 'input-right' );

																	echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'slider ' . $includeHome );

																		echo $csmForm->elementStart('div', FALSE, 'inset' );
																		echo $csmForm->element('div', FALSE, 'control', FALSE);
																		echo $csmForm->elementEnd('div');

																		echo $csmForm->input($value['id'].'[slot' .$snippetID . '][include_home]', FALSE, 'on-off', 'hidden', $includeHome );

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->element('div', FALSE, 'clear', FALSE);
																	echo $csmForm->element('p', FALSE, FALSE, esc_html__('Do you want to load this script on the homepage?','content-snippet-manager'));

																echo $csmForm->elementEnd('div');

																echo $csmForm->element('div', FALSE, 'clear', FALSE);

															echo $csmForm->elementEnd('div');

															$includeSearch = (isset($csm_snippets['slot' . $snippetID]['include_search']) && $csm_snippets['slot' . $snippetID]['include_search'] == 'on') ? 'on' : 'off';

															echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box');

																echo $csmForm->elementStart('div', FALSE, 'input-left' );

																	echo $csmForm->label('Search result pages.', esc_html__( 'Search', 'content-snippet-manager'));

																echo $csmForm->elementEnd('div');

																echo $csmForm->elementStart('div', FALSE, 'input-right' );

																	echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'slider ' . $includeSearch );

																		echo $csmForm->elementStart('div', FALSE, 'inset' );
																		echo $csmForm->element('div', FALSE, 'control', FALSE);
																		echo $csmForm->elementEnd('div');

																		echo $csmForm->input($value['id'].'[slot' . $snippetID . '][include_search]', FALSE, 'on-off', 'hidden', $includeSearch );

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->element('div', FALSE, 'clear', FALSE);
																	echo $csmForm->element('p', FALSE, FALSE, esc_html__('Do you want to load this script on the search result pages?','content-snippet-manager'));

																echo $csmForm->elementEnd('div');

																echo $csmForm->element('div', FALSE, 'clear', FALSE);

															echo $csmForm->elementEnd('div');

															$includeWholeWebsite = (isset($csm_snippets['slot' . $snippetID]['include_whole_website']) && $csm_snippets['slot' . $snippetID]['include_whole_website'] == 'on') ? 'on' : 'off';

															echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box');

																echo $csmForm->elementStart('div', FALSE, 'input-left' );

																	echo $csmForm->label('wholeWebsite', esc_html__( 'Whole website (posts, pages, taxonomies)', 'content-snippet-manager'));

																echo $csmForm->elementEnd('div');

																echo $csmForm->elementStart('div', FALSE, 'input-right' );

																	echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'slider wholeWebsite ' . $includeWholeWebsite );

																		echo $csmForm->elementStart('div', FALSE, 'inset' );
																		echo $csmForm->element('div', FALSE, 'control', FALSE);
																		echo $csmForm->elementEnd('div');

																		echo $csmForm->input($value['id'].'[slot' . $snippetID . '][include_whole_website]', FALSE, 'on-off', 'hidden', $includeWholeWebsite );

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->element('div', FALSE, 'clear', FALSE);
																	echo $csmForm->element('p', FALSE, FALSE, esc_html__('Do you want to load this script on whole website?','content-snippet-manager'));

																echo $csmForm->elementEnd('div');

																echo $csmForm->element('div', FALSE, 'clear', FALSE);

															echo $csmForm->elementEnd('div');

														echo $csmForm->elementEnd('div');

														echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box MatchValueBox' );

															echo $csmForm->elementStart('div', FALSE, 'input-right' );

																echo $csmForm->element('strong', FALSE, FALSE, esc_html__('Custom post types.','content-snippet-manager'));
																echo $csmForm->element('p', FALSE, FALSE, esc_html__('Below each available custom post type.','content-snippet-manager'));

															echo $csmForm->elementEnd('div');

															echo $csmForm->element('div', FALSE, 'clear', FALSE);

														echo $csmForm->elementEnd('div');

														foreach ( csm_get_custom_post_list() as $cpt ) {

															echo $csmForm->elementStart('div', FALSE, 'MatchValueBox ' . $cpt . 'Type');

																echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box ' . $cpt . 'Cpt MatchValue');

																	echo $csmForm->elementStart('div', FALSE, 'input-left' );

																		echo $csmForm->label(FALSE, ucfirst($cpt) . esc_html__( ' match value.', 'content-snippet-manager'));

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->elementStart('div', FALSE, 'input-right' );

																		$postMatch = isset($csm_snippets['slot' . $snippetID][$cpt . '_matchValue']) ? $this->sanitize_matchValue_function($csm_snippets['slot' . $snippetID][$cpt . '_matchValue']) : array();

																		$options = array(
																			'include' => esc_html__('Include','content-snippet-manager'),
																			'exclude' => esc_html__('Exclude','content-snippet-manager')

																		);

																		echo $csmForm->select($value['id'].'[slot'.$snippetID.']['.$cpt.'_matchValue]', FALSE, 'selectValue', $options, $postMatch, 'data-type="'.$cpt.'"');

																		echo $csmForm->element('p', FALSE, FALSE, esc_html__('Do you want to include or exclude this custom post type?','content-snippet-manager'));

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->element('div', FALSE, 'clear', FALSE);

																echo $csmForm->elementEnd('div');

																echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box include ' . $cpt . 'cpt');

																	echo $csmForm->elementStart('div', FALSE, 'input-left' );

																		echo $csmForm->label('Include', esc_html__( 'Include these items.', 'content-snippet-manager'));

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->elementStart('div', FALSE, 'input-right' );

																		$includeValues = isset($csm_snippets['slot' . $snippetID]['include_' . $cpt]) ? $this->sanitize_postID_function($csm_snippets['slot' . $snippetID]['include_' . $cpt]) : array();

																		echo $csmForm->ajaxSelect($value['id'].'[slot'.$snippetID.'][include_'.$cpt.']', FALSE, 'csmAjaxSelect2', $includeValues, $cpt);

																		echo $csmForm->element('p', FALSE, FALSE, esc_html__('Type [All] to include all items.','content-snippet-manager'));

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->element('div', FALSE, 'clear', FALSE);

																echo $csmForm->elementEnd('div');

																echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box exclude ' . $cpt . 'cpt');

																	echo $csmForm->elementStart('div', FALSE, 'input-left' );

																		echo $csmForm->label('Exclude', esc_html__( 'Exclude these items.', 'content-snippet-manager'));

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->elementStart('div', FALSE, 'input-right' );

																		$excludeValues = isset($csm_snippets['slot' . $snippetID]['exclude_' . $cpt]) ? $this->sanitize_postID_function($csm_snippets['slot' . $snippetID]['exclude_' . $cpt]) : array();

																		echo $csmForm->ajaxSelect($value['id'].'[slot'.$snippetID.'][exclude_'.$cpt.']', FALSE, 'csmAjaxSelect2', $excludeValues, $cpt);

																		echo $csmForm->element('p', FALSE, FALSE, esc_html__('Type [All] to exclude all items.','content-snippet-manager'));

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->element('div', FALSE, 'clear', FALSE);

																echo $csmForm->elementEnd('div');

															echo $csmForm->elementEnd('div');

														}

														echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box MatchValueBox' );

															echo $csmForm->elementStart('div', FALSE, 'input-right' );

																echo $csmForm->element('strong', FALSE, FALSE, esc_html__('Custom taxonomies.','content-snippet-manager'));
																echo $csmForm->element('p', FALSE, FALSE, esc_html__('Below each available Custom taxonomy.','content-snippet-manager'));

															echo $csmForm->elementEnd('div');

															echo $csmForm->element('div', FALSE, 'clear', FALSE);

														echo $csmForm->elementEnd('div');

														foreach ( csm_get_taxonomies_list() as $tax ) {

															echo $csmForm->elementStart('div', FALSE, 'MatchValueBox ' . $tax . 'Type');

																echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box ' . $tax . 'Cpt MatchValue');

																	echo $csmForm->elementStart('div', FALSE, 'input-left' );

																		echo $csmForm->label(FALSE, ucfirst($tax) . esc_html__( ' match value.', 'content-snippet-manager'));

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->elementStart('div', FALSE, 'input-right' );

																		$taxMatch = isset($csm_snippets['slot' . $snippetID][$tax . '_matchValue']) ? $this->sanitize_matchValue_function($csm_snippets['slot' . $snippetID][$tax . '_matchValue']) : array();

																		$current = array(
																			'include' => esc_html__('Include','content-snippet-manager'),
																			'exclude' => esc_html__('Exclude','content-snippet-manager')
																		);

																		echo $csmForm->select($value['id'].'[slot'.$snippetID.']['.$tax.'_matchValue]', FALSE, 'selectValue', $current, $taxMatch, 'data-type="'.$tax.'"');

																		echo $csmForm->element('p', FALSE, FALSE, esc_html__('Do you want to include or exclude this custom taxonomy?','content-snippet-manager'));

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->element('div', FALSE, 'clear', FALSE);

																echo $csmForm->elementEnd('div');

																echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box include ' . $tax . 'cpt');

																	echo $csmForm->elementStart('div', FALSE, 'input-left' );

																		echo $csmForm->label('Include', esc_html__( 'Include these items. ', 'content-snippet-manager'));

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->elementStart('div', FALSE, 'input-right' );

																		$includeValues = isset($csm_snippets['slot' . $snippetID]['include_' . $tax]) ? $this->sanitize_taxID_function($csm_snippets['slot' . $snippetID]['include_' . $tax]) : array();

																		echo $csmForm->ajaxSelect($value['id'].'[slot'.$snippetID.'][include_'.$tax.']', FALSE, 'csmAjaxSelect2Tax', $includeValues, $tax);

																		echo $csmForm->element('p', FALSE, FALSE, esc_html__('Type [All] to include all items.','content-snippet-manager'));

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->element('div', FALSE, 'clear', FALSE);

																echo $csmForm->elementEnd('div');

																echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box exclude ' . $tax . 'cpt');

																	echo $csmForm->elementStart('div', FALSE, 'input-left' );

																		echo $csmForm->label('Exclude', esc_html__( 'Exclude these items. ', 'content-snippet-manager'));

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->elementStart('div', FALSE, 'input-right' );

																		$excludeValues = isset($csm_snippets['slot' . $snippetID]['exclude_' . $tax]) ? $this->sanitize_taxID_function($csm_snippets['slot' . $snippetID]['exclude_' . $tax]) : array();

																		echo $csmForm->ajaxSelect($value['id'].'[slot'.$snippetID.'][exclude_'.$tax.']', FALSE, 'csmAjaxSelect2Tax', $excludeValues, $tax);

																		echo $csmForm->element('p', FALSE, FALSE, esc_html__('Type [All] to exclude all items.','content-snippet-manager'));

																	echo $csmForm->elementEnd('div');

																	echo $csmForm->element('div', FALSE, 'clear', FALSE);

																echo $csmForm->elementEnd('div');

															echo $csmForm->elementEnd('div');

														}

														echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box' );

															echo $csmForm->elementStart('div', FALSE, 'input-left' );

																echo $csmForm->label(FALSE, esc_html__( 'Content snippet', 'content-snippet-manager'));

															echo $csmForm->elementEnd('div');

															echo $csmForm->elementStart('div', FALSE, 'input-right aceEditorWrapper' );
															
																echo $csmForm->element('p', FALSE, FALSE, __('Put here your HTML or WordPress shortcode.','content-snippet-manager'));
																echo $csmForm->element('p', FALSE, FALSE, __('<strong>Important</strong> Javascript code, conversion script or custom css codes are not allowed. To configure an header, body, footer or conversion snippet, please follow <a target="_blank" href="'.CSM_CONVERSION_SNIPPETS.'">our documentation</a>','content-snippet-manager'));
																
																echo $csmForm->textarea($value['id'].'[slot'.$snippetID.'][code]', FALSE, 'aceEditor', (isset($csm_snippets['slot' . $snippetID]['code'])) ? $csm_snippets['slot' . $snippetID]['code'] : '', FALSE);
																

															echo $csmForm->elementEnd('div');

															echo $csmForm->element('div', FALSE, 'clear', FALSE);

														echo $csmForm->elementEnd('div');

														echo $csmForm->elementStart('div', FALSE, $plugin_slug . 'box deleteSlot' );

															echo $csmForm->input('csm_save_snippet_action', FALSE, 'button csm_save_snippet', 'submit', esc_html__('Save snippet', 'content-snippet-manager' ));
															echo $csmForm->input('csm_delete_snippet_action', FALSE, 'button csm_delete_snippet', 'submit', esc_html__('Delete snippet', 'content-snippet-manager' ));

															echo $csmForm->element('div', FALSE, 'clear', FALSE);

														echo $csmForm->elementEnd('div');

													echo $csmForm->elementEnd('div');

												echo $csmForm->elementEnd('div');

										}


										echo $csmForm->elementEnd('div');

									echo $csmForm->elementEnd('div');

								break;

								}

							endif;

						}

					}

				endif;

			}

		}

	}

}

?>
