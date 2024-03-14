<?php
/*
Plugin Name: PrestaShop Integration
Plugin URI: http://www.aytechnet.fr/blog/plugin-wordpress/prestashop-integration
Description: Add integration using plugins and shortcodes from a PrestaShop e-commerce to your blog
Version: 0.9.15
Author: François Pons
Author URI: https://www.aytechnet.fr/
*/

define( 'PRESTASHOP_INTEGRATION_VERSION', '0.9.15' );

/*  Copyright 2010-2023  François Pons  (email : fpons@aytechnet.fr)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( !class_exists( 'PrestaShopIntegration' ) ) {
	class PrestaShopIntegration {
		var $pspath;
		var $css_import;
		var $js_import;
		var $favicon_import;
		var $wordpress_homepage;
		var $use_prestashop_frontpage;

		var $psabspath;
		var $psdb;
		var $controller;
		var $id_lang;
		var $stores;

		var $hooks_names;
		var $hooks_descriptions;

		function __construct() {
			load_plugin_textdomain( 'prestashop_integration', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

			if (function_exists('register_activation_hook'))
                                register_activation_hook(__FILE__, array(&$this, 'activate'));
                        if (function_exists('register_uninstall_hook'))
                                register_uninstall_hook(__FILE__, array('PrestaShopIntegration', 'uninstall'));

			$this->getOptions();

			add_filter( 'do_parse_request', array( &$this, 'dispatchPrestaShop' ), 1 );

			if ( is_admin() ) {
				add_action( 'admin_init', array( &$this, 'registerOptions' ) );
				add_action( 'admin_footer', array( &$this, 'checkOptions' ) );
				add_action( 'admin_menu', array( &$this, 'addAdminPages' ) );
				add_action( 'admin_enqueue_scripts', array( &$this, 'addAdminScripts' ) );

				add_action( 'wp_ajax_prestashop_integration_list_product', array( &$this, 'listPostProduct' ) );
				add_action( 'wp_ajax_prestashop_integration_add_product', array( &$this, 'addPostProduct' ) );
				add_action( 'wp_ajax_prestashop_integration_del_product', array( &$this, 'delPostProduct' ) );
			}
			add_action( 'widgets_init', array( &$this, 'registerWidgets' ) );
			add_action( 'template_redirect', array( &$this, 'initPrestaShop' ) );
			add_action( 'wp_head', array( &$this, 'addPrestaShopHeaders' ), 1 );

			add_shortcode('ps_module', array( &$this, 'shortcode_ps_module' ) );
			add_shortcode('ps_hook', array( &$this, 'shortcode_ps_hook' ) );
			add_shortcode('ps_template_vars', array( &$this, 'shortcode_ps_template_vars' ) );
			add_shortcode('ps_product_image', array( &$this, 'shortcode_ps_product_image' ) );
			add_shortcode('ps_new_products', array( &$this, 'shortcode_ps_new_products' ) );
			add_shortcode('ps_product_list', array( &$this, 'shortcode_ps_product_list' ) );
		}

		function activate() {
			$options = get_option( 'prestashop_integration_options' );

			if ($options == false) {
				$options = array(
					'pspath' => '',
					'css_import' => true,
					'js_import' => true,
					'favicon_import' => false,
					'wordpress_homepage' => false,
					'use_prestashop_frontpage' => false,
				);

				add_option( 'prestashop_integration_options', $options );
			} else {
				$options = $this->validateOptions( $options );

				update_option( 'prestashop_integration_options', $options );
			}
		}

		function uninstall() {
			delete_option( 'prestashop_integration_options' );
		}

		function getOptions() {
			$options = get_option( 'prestashop_integration_options' );

			$this->pspath = $options[ 'pspath' ];
			if ( preg_match( '#^/#', $this->pspath ) )
				$this->psabspath = $this->pspath;
			else
				$this->psabspath = ABSPATH . $this->pspath;
			if ( !preg_match( '#/$#', $this->psabspath ) )
				$this->psabspath .= '/';

			$this->css_import = $options[ 'css_import' ];
			$this->js_import = $options[ 'js_import' ];
			$this->favicon_import = $options[ 'favicon_import' ];
			$this->wordpress_homepage = $options[ 'wordpress_homepage' ];
			$this->use_prestashop_frontpage = $options[ 'use_prestashop_frontpage' ];

			if ( file_exists( $this->psabspath . 'app/config/parameters.php' ) ) {
				try {
					$ps17 = ( include( $this->psabspath . 'app/config/parameters.php' ) );

					if ( (
					    DB_HOST == $ps17['parameters']['database_host'] ||
					    DB_HOST == 'localhost' && $ps17['parameters']['database_host'] == '127.0.0.1' )
					    &&
					    DB_NAME == $ps17['parameters']['database_name'] &&
					    DB_USER == $ps17['parameters']['database_user'] &&
					    DB_PASSWORD == $ps17['parameters']['database_password'] ) {
						global $wpdb;
						$this->psdb = $wpdb;
					} else {
						$this->psdb = new wpdb( $ps17['parameters']['database_user'], $ps17['parameters']['database_password'], $ps17['parameters']['database_name'], $ps17['parameters']['database_host'] );
					}
					$this->psprefix = $ps17['parameters']['database_prefix'];
				} catch (Exception $e) {
					error_log("PrestaShop Integration: exception: ".$e->getMessage());
				}
			} elseif ( file_exists( $this->psabspath . 'config/settings.inc.php' ) ) {
				try {
					require_once( $this->psabspath . 'config/settings.inc.php' );

					if ( DB_HOST == _DB_SERVER_ &&
					     DB_NAME == _DB_NAME_ &&
					     DB_USER == _DB_USER_ &&
					     DB_PASSWORD == _DB_PASSWD_ ) {
						global $wpdb;
						$this->psdb = $wpdb;
					} else {
						$this->psdb = new wpdb( _DB_USER_, _DB_PASSWD_, _DB_NAME_, _DB_SERVER_ );
					}
					$this->psprefix = _DB_PREFIX_;
				} catch (Exception $e) {
					error_log("PrestaShop Integration: exception: ".$e->getMessage());
				}
			}
		}

		function needDispatcher() {
			$needDispatcher = false;
			try {
				require_once( dirname(__FILE__) . '/class/prestashop-integration-dispatcher.php' );
				$needDispatcher = class_exists( 'PrestaShopIntegration_Dispatcher' );
			} catch (Exception $e) {
				error_log("PrestaShop Integration: exception: ".$e->getMessage());
			}

			return $needDispatcher;
		}

		function dispatchPrestaShop() {
			if ( isset($_GET['page_id']) || isset($_GET['mailpoet_page']) || isset($_GET['mailpoet_router']) || isset($_GET['wordfence_lh']) || isset($_GET['rest_route']) )
				return true;

			try {
				require_once( dirname(__FILE__) . '/class/prestashop-integration-dispatcher.php' );
				if ( class_exists( 'PrestaShopIntegration_Dispatcher' ) ) {
					if ($controller = PrestaShopIntegration_Dispatcher::getInstance()->dispatch())
						return $this->dispatchedToPrestaShop($controller);
				}
			} catch (Exception $e) {
				error_log("PrestaShop Integration: exception: ".$e->getMessage());
			}

			return true;
		}

		function dispatchedToPrestaShop($controller) {
			// make sure Cookie are saved
			if (Context::getContext()->cookie)
				Context::getContext()->cookie->write();

			// return false to disable WordPress output
			// FIXME: returning false should allow WordPress to exit gracefully, it is not the case sometimes...
			exit(0);
		}

		function registerOptions() {
			register_setting( 'prestashop_integration_group', 'prestashop_integration_options', array( &$this, 'validateOptions' ) );
		}

		function validateOptions( $input ) {
			$input[ 'pspath' ] = $input[ 'pspath' ]; #TODO check directory ?
			$input[ 'css_import' ] = $input[ 'css_import' ] ? true : false;
			$input[ 'js_import' ] = $input[ 'js_import' ] ? true : false;
			$input[ 'favicon_import' ] = $input[ 'favicon_import' ] ? true : false;

			return $input;
		}

		function checkOptions() {
			if ( ! $this->psdb ) {
				echo '<div class="error"><p><strong><a href="options-general.php?page=prestashop_integration_plugin">';
				echo '<a href="options-general.php?page=prestashop_integration_plugin">'.__( 'Configure PrestaShop Integration', 'prestashop_integration' ).'</a> : ';
				echo __( 'Please check your installation or this plugin will not work !', 'prestashop_integration' );
				echo '</strong></p></div>';
			}
		}

		function addAdminPages() {
			add_options_page( __( 'Configure PrestaShop Integration', 'prestashop_integration' ), __( 'PrestaShop Integration', 'prestashop_integration' ), 'manage_options', 'prestashop_integration_plugin', array( &$this, 'adminOptionPage' ) );
			add_meta_box( 'prestashop_integration', __( 'PrestaShop Integration', 'prestashop_integration' ), array( &$this, 'adminProductPage' ), 'post', 'normal', 'high' );
		}

		function addAdminScripts( $hook ) {
			if ( $hook == 'post.php' ) {
				wp_enqueue_script( 'prestashop_integration', plugins_url( 'js/script.js', __FILE__ ) );

				$protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
				$params = array(
					'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
				);

				wp_localize_script( 'prestashop_integration', 'prestashop_integration', $params );
			}
		}

		function adminOptionPage() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'You do not have rights to access this page.', 'prestashop_integration' ));
			}
?>
<div class="wrap">
	<h2><?php _e( 'PrestaShop Integration' ); ?></h2>
	<form method="post" action="options.php">
		<?php settings_fields( 'prestashop_integration_group' ); ?>
		<?php _e( 'Define how to access the PrestaShop code and database: you need to give the absolute (or relative path from WordPress root installation) to PrestaShop root installation', 'prestashop_integration' ); ?>
		<table class="form-table">
			<tr align="top">
				<th scope="row"><?php _e( 'PrestaShop path:', 'prestashop_integration' ); ?></th>
				<td><input name="prestashop_integration_options[pspath]" value="<?php echo $this->pspath; ?>" /><?php
			if ( $this->psdb ) {
				echo '<br><span class="description">';
				printf( esc_attr__( 'The actual value "%s" refers to a PrestaShop installation version %s named "%s"', 'prestashop_integration' ),
				        $this->pspath, $this->psConfiguration( 'PS_VERSION_DB' ), $this->psConfiguration( 'PS_SHOP_NAME' ) );
				echo '</span>';
			} elseif ( $this->pspath ) {
				echo '<br/><span class="description error-message">';
				printf( esc_attr__( 'The actual value "%s" does not refer to a PrestaShop installation !', 'prestashop_integration'), $this->pspath );
				echo '<br/>'.esc_attr__( 'Please check your installation or this plugin will not work !', 'prestashop_integration' );
				echo '</span>';
			} else {
				echo '<br/><span class="description error-message">';
				echo esc_attr__( 'You need to enter here the relative path to your PrestaShop installation', 'prestashop_integration');
				echo '</span>';
			} ?></td>
			</tr>
			<tr align="top">
				<th scope="row"><?php _e( 'PrestaShop CSS import:', 'prestashop_integration' ); ?></th>
				<td><input type="checkbox" name="prestashop_integration_options[css_import]" value="true" <?php echo $this->css_import ? 'checked' : ''; ?>/></td>
			</tr>
			<tr align="top">
				<th scope="row"><?php _e( 'PrestaShop JS import:', 'prestashop_integration' ); ?></th>
				<td><input type="checkbox" name="prestashop_integration_options[js_import]" value="true" <?php echo $this->js_import ? 'checked' : ''; ?>/></td>
			</tr>
			<tr align="top">
				<th scope="row"><?php _e( 'PrestaShop favicon import:', 'prestashop_integration' ); ?></th>
				<td><input type="checkbox" name="prestashop_integration_options[favicon_import]" value="true" <?php echo $this->favicon_import ? 'checked' : ''; ?>/></td>
			</tr><?php if ( $this->needDispatcher() ) { ?>
			<tr align="top">
				<th scope="row"><?php _e( 'WordPress manages homepage:', 'prestashop_integration' ); ?></th>
				<td><input type="checkbox" name="prestashop_integration_options[wordpress_homepage]" value="true" <?php echo $this->wordpress_homepage ? 'checked' : ''; ?>/></td>
			</tr><?php } else { ?>
			<tr align="top">
				<th scope="row"><?php _e( 'Use PrestaShop frontpage for WordPress frontpage:', 'prestashop_integration' ); ?></th>
				<td><input type="checkbox" name="prestashop_integration_options[use_prestashop_frontpage]" value="true" <?php echo $this->use_prestashop_frontpage ? 'checked' : ''; ?>/></td>
			</tr><?php } ?>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" />
		</p>
	</form>
</div><?php
		}

		function adminProductPage( $p ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				echo '<p><b>'.__( 'Cheatin&#8217; uh?' ).'</b></p>';
				return;
			}
?>
<div class="ajaxps hide-if-no-js">
	<p>
		<input id="prestashop_integration_product_id" autocomplete="off" size="50" name="prestashop_integration_product_id" />
		<input id="prestashop_integration_add_product_button" class="button" type="button" value="<?php _e( 'Add' ); ?>" />
		<?php wp_nonce_field( 'prestashop_integration_product', 'prestashop_integration_nonce' ); ?>
	</p>
	<p class="howto"><?php _e( 'Products id separated by commas' ); ?></p>
</div>
<div id="prestashop_integration_products" class="hide-if-no-js tagchecklist">
</div>
<?php
		}

		function listPostProduct() {
			$response = new WP_Ajax_Response;
			$post_id = (int)$_REQUEST['post_id'];

			if ( $post_id && current_user_can( 'edit_posts' ) ) {
				$product_id = preg_replace( array('/^[\s,]+/', '/[\s,]+/', '/[\s,]+$/'), array('', ',', ''), get_post_meta( $post_id, '_prestashop_integration_products', true ) );

				if ( file_exists( $this->psabspath . 'config/config.inc.php' ) ) {
					try {
						require_once( $this->psabspath . 'config/config.inc.php' );

						$products = $this->getProducts( $this->psLang(), $product_id, false );
						$json_products = array();

						$json_products = '';
						foreach ($products AS $product) {
							$json_products .= ($json_products == '' ? '' : ',').'{ "id": '.$product['id_product'].', "name": "'.$product['name'].'" }';
						}

						$response->add( array(
							'data' => 'success',
							'supplemental' => array(
								'json_products' => '{ "products": [ '.$json_products.' ] }',
							),
						) );
					} catch (Exception $e) {
						error_log("PrestaShop Integration: exception: ".$e->getMessage());
						$response->add( array(
							'data' => 'error',
						) );
					}
				}
			} else {
				$response->add( array(
					'data' => 'error',
				) );
			}
			$response->send();
			exit();
		}

		function addPostProduct() {
			$response = new WP_Ajax_Response;
			$post_id = $_REQUEST['post_id'];
			$add_product_id = $_REQUEST['product_id'];
			$nonce = $_REQUEST['nonce'];

			if ( current_user_can( 'edit_posts' ) && wp_verify_nonce( $nonce, 'prestashop_integration_product' ) ) {
				if ( file_exists( $this->psabspath . 'config/config.inc.php' ) ) {
					try {
						require_once( $this->psabspath . 'config/config.inc.php' );

						$product_id = explode( ',', preg_replace( array('/^[\s,]+/', '/[\s,]+/', '/[\s,]+$/'), array('', ',', ''), get_post_meta( $post_id, '_prestashop_integration_products', true ) ) );
						$products = $this->getProducts( $this->psLang(), $add_product_id, false );

						$json_products = '';
						foreach ($products AS $product) {
							if ( !in_array( $product['id_product'], $product_id ) ) {
								$json_products .= ($json_products == '' ? '' : ',').'{ "id": '.$product['id_product'].', "name": "'.$product['name'].'" } ';
								$product_id[] = (int)$product['id_product'];
							}
						}

						update_post_meta( $post_id, '_prestashop_integration_products', implode( ',', $product_id ) );

						$response->add( array(
							'data' => 'success',
							'supplemental' => array(
								'json_products' => '{ "products": [ '.$json_products.' ] }',
							),
						) );
					} catch (Exception $e) {
						error_log("PrestaShop Integration: exception: ".$e->getMessage());
						$response->add( array(
							'data' => 'error',
						) );
					}
				}
			} else {
				$response->add( array(
					'data' => 'error',
				) );
			}
			$response->send();
			exit();
		}
		function delPostProduct() {
			$response = new WP_Ajax_Response;
			$post_id = $_REQUEST['post_id'];
			$del_product_id = $_REQUEST['product_id'];
			$nonce = $_REQUEST['nonce'];

			if ( current_user_can( 'edit_posts' ) && wp_verify_nonce( $nonce, 'prestashop_integration_product' ) ) {
				$product_id = explode( ',', preg_replace( array('/^[\s,]+/', '/[\s,]+/', '/[\s,]+$/'), array('', ',', ''), get_post_meta( $post_id, '_prestashop_integration_products', true ) ) );
				$products = explode( ',', $del_product_id );

				$product_id = array_merge( array_diff( $product_id, $products ) );

				update_post_meta( $post_id, '_prestashop_integration_products', implode( ',', $product_id ) );

				$response->add( array(
					'data' => 'success',
				) );
			} else {
				$response->add( array(
					'data' => 'error',
				) );
			}
			$response->send();
			exit();
		}

		function registerWidgets() {
			require_once( dirname(__FILE__) . '/widgets/prestashop-integration-hook.php' );
			require_once( dirname(__FILE__) . '/widgets/prestashop-integration-module.php' );
			require_once( dirname(__FILE__) . '/widgets/prestashop-integration-products.php' );
			require_once( dirname(__FILE__) . '/widgets/prestashop-integration-template.php' );

			register_widget( 'PrestaShopIntegrationHook_Widget' );
			register_widget( 'PrestaShopIntegrationModule_Widget' );
			register_widget( 'PrestaShopIntegrationProducts_Widget' );
			register_widget( 'PrestaShopIntegrationTemplate_Widget' );
		}

		function disableWidgetDisplayAccordingToOnlyIfProducts($only_if_products) {
			global $post;

			if ( isset( $post ) && isset( $post->ID ) )
				$products = preg_replace( array('/^[\s,]+/', '/[\s,]+/', '/[\s,]+$/'), array('', ',', ''), get_post_meta( $post->ID, '_prestashop_integration_products', true ) );
			else
				$products = '';

			switch ( $only_if_products ) {
			case 1: // display only if product is defined
				return empty( $products );

			case 2: // display only if no product is defined
				return !empty( $products );

			default:
				return false;
			}
		}

		function selectOnlyIfProducts($module, $only_if_products) {
			return '<label for="'.$module->get_field_id('only_if_products').'">'.__('Display:').'</label> 
			<select class="widefat" id="'.$module->get_field_id('only_if_products').'" name="'.$module->get_field_name('only_if_products').'">
				<option value="0"'.($only_if_products == 0 ? ' selected="selected"' : '').'>'.__('Always').'</option>
				<option value="1"'.($only_if_products == 1 ? ' selected="selected"' : '').'>'.__('Only if products are defined').'</option>
				<option value="2"'.($only_if_products == 2 ? ' selected="selected"' : '').'>'.__('Only if no product is defined').'</option>
			</select>';
		}

		function initPrestaShop() {
			if ( !$this->controller ) {
				try {
					require_once( dirname(__FILE__) . '/class/prestashop-integration-controller.php' );
					if ( $this->use_prestashop_frontpage && ( is_front_page() || $_SERVER['REQUEST_URI'] == '/') ) {
						if ( class_exists( 'PrestaShopIntegration_IndexController' ) ) {
							$this->controller = new PrestaShopIntegration_IndexController;

							$this->controller->run();

							return $this->dispatchedToPrestaShop($controller);
						}
					} else {
						if ( class_exists( 'PrestaShopIntegration_FrontController' ) ) {
							$this->controller = new PrestaShopIntegration_FrontController;

							$this->controller->init();
						}
					}
				} catch (Exception $e) {
					error_log("PrestaShop Integration: exception: ".$e->getMessage());
				}
			}
                }

		function addPrestaShopHeaders() {
			if ( $this->controller ) {
				try {
					$this->controller->displayHeader();
				} catch (Exception $e) {
					error_log("PrestaShop Integration: exception: ".$e->getMessage());
				}
			}
                }

		function psValid() {
			return $this->psdb && $this->controller;
		}

		function psConfiguration( $name ) {
			if ( $name == 'PS_VERSION_DB' && defined( '__PS_VERSION__' ) )
				return __PS_VERSION__;

			if ($this->psdb)
				return $this->psdb->get_var( '
					SELECT `value`
					FROM `'.$this->psprefix.'configuration`
					WHERE `name` = "'.$name.'"' );
			else
				return false;
		}

		function psLang() {
			if ( $this->id_lang )
				return $this->id_lang;

			if ( defined( 'ICL_LANGUAGE_CODE' ) )
				$iso_code = ICL_LANGUAGE_CODE;
			elseif ( function_exists( 'pll_current_language' ) )
				$iso_code = pll_current_language();
			elseif ( $lang = get_bloginfo('language') )
				$iso_code = substr( $lang, 0, 2 );

			if ( $iso_code ) {
				if ($this->psdb) {
					$id_lang = $this->psdb->get_var( '
						SELECT `id_lang`
						FROM `'.$this->psprefix.'lang`
						WHERE `active` = 1 AND (`iso_code` = "'.$iso_code.'" OR `iso_code` = "'.$lang.'")' );
					if ( $id_lang > 0 )
						return ( $this->id_lang = $id_lang );
				}
			}

			return ( $this->id_lang = $this->psConfiguration( 'PS_LANG_DEFAULT' ) );
		}

		function psModuleId( $name ) {
			if ($this->psdb && $name != '')
				return $this->psdb->get_var( '
					SELECT `id_module`
					FROM `'.$this->psprefix.'module`
					WHERE `name` = "'.$name.'"' );
			else
				return false;
		}

		function psModules() {
			if ($this->psdb)
				return $this->psdb->get_col( '
					SELECT DISTINCT m.`name`
					FROM `'.$this->psprefix.'module` m
					LEFT JOIN `'.$this->psprefix.'hook_module` hm ON hm.`id_module` = m.`id_module`
					LEFT JOIN `'.$this->psprefix.'hook` h ON h.`id_hook` = hm.`id_hook`
					WHERE m.`active` = 1 AND h.name IN ("'.join( '", "', array_values( $this->psHooksNames() ) ).'")' );
			else
				return false;
		}

		function psHooksNames( $hook = null ) {
			if ( !$this->hooks_names )
				$this->hooks_names = ( version_compare(_PS_VERSION_, '1.5', '>=') ? array(
					'HOOK_TOP' => 'displayTop',
					'HOOK_LEFT_COLUMN' => 'displayLeftColumn',
					'HOOK_RIGHT_COLUMN' => 'displayRightColumn',
					'HOOK_FOOTER' => 'displayFooter'
				) : array(
					'HOOK_TOP' => 'top',
					'HOOK_LEFT_COLUMN' => 'leftColumn',
					'HOOK_RIGHT_COLUMN' => 'rightColumn',
					'HOOK_FOOTER' => 'footer'
				) );
			if ( $hook )
				return $this->hooks_names[$hook];
			else
				return $this->hooks_names;
		}

		function psHooksDescriptions( $hook = null ) {
			if ( !$this->hooks_descriptions )
				$this->hooks_descriptions = array(
					'HOOK_TOP' => __( 'Top of Pages', 'prestashop_integration' ),
					'HOOK_LEFT_COLUMN' => __( 'Left Column Blocks', 'prestashop_integration' ),
					'HOOK_RIGHT_COLUMN' => __( 'right Column Blocks', 'prestashop_integration' ),
					'HOOK_FOOTER' => __( 'Footer', 'prestashop_integration' )
				);
			if ( $hook )
				return $this->hooks_descriptions[$hook];
			else
				return $this->hooks_descriptions;
		}

		// [ps_module module=blocknewsletter hook=displayRightColumn]
		function shortcode_ps_module($atts) {
			extract(shortcode_atts(array(
				'module' => '',
				'class' => '',
				'style' => '',
				'hook' => version_compare(_PS_VERSION_, '1.5', '>=') ? 'displayRightColumn' : 'rightColumn',
			), $atts));

			if ( $this->psValid() && $module != '' ) {
				if ( $id_module = $this->psModuleId( $module ) ) {
					if ( version_compare(_PS_VERSION_, '1.5', '>=') )
						$result = Hook::exec( $hook, array(), $id_module );
					else
						$result = Module::hookExec( $hook, array(), $id_module );
				}
				if ( $result != '' )
					return '<div'.( $class ? ' class="'.$class.'"' : '').( $style ? ' style="'.$style.'"' : '').'>'.$result.'</div>';
			}
		}

		// [ps_hook hook=displayLeftColumn]
		function shortcode_ps_hook($atts) {
			extract(shortcode_atts(array(
				'class' => '',
				'style' => '',
				'hook' => version_compare(_PS_VERSION_, '1.5', '>=') ? 'displayLeftColumn' : 'leftColumn',
			), $atts));

			if ( $this->psValid() && $hook != '' )
				if ( version_compare(_PS_VERSION_, '1.5', '>=') )
					$result = Hook::exec( $hook );
				else
					$result = Module::hookExec( $hook );
			if ( $result != '' )
				return ( $class || $style ? '<div'.( $class ? ' class="'.$class.'"' : '').( $style ? ' style="'.$style.'"' : '').'>' : '').$result.( $class || $style ? '</div>' : '' );
		}

		// [ps_template_vars name=displayLeftColumn]
		function shortcode_ps_template_vars($atts) {
			extract(shortcode_atts(array(
				'class' => '',
				'style' => '',
				'name' => version_compare(_PS_VERSION_, '1.5', '>=') ? 'displayLeftColumn' : 'leftColumn',
			), $atts));

			if ( $this->psValid() && $name != '' )
				$result = $this->getTemplateVars( $name );
			if ( $result != '' )
				return ( $class || $style ? '<div'.( $class ? ' class="'.$class.'"' : '').( $style ? ' style="'.$style.'"' : '').'>' : '').$result.( $class || $style ? '</div>' : '' );
		}

		// [ps_product_image class="img-responsive" id_product=100]
		function shortcode_ps_product_image($atts) {
			extract(shortcode_atts(array(
				'id_product' => '',
				'id_image' => '',
				'id_lang' => '',
				'link_rewrite' => '-',
				'type' => 'home_default',
				'class' => '10',
			), $atts));

			if ( $this->psValid() ) {
				if ( !$id_lang )
					$id_lang = $this->psLang();
				if ( !$id_image ) {
					$cover = Product::getCover( $id_product );
					$id_image = $cover['id_image'];
				}
				if ( isset($id_image) && $id_image )
					$ids = $id_product.'-'.$id_image;
				else
					$ids = Language::getIsoById((int)$id_lang).'-default';

				global $link;
				return '<img '.( $class ? 'class="'.$class.'" ' : '' ).'src="'.$link->getImageLink($link_rewrite, $ids, $type).'" />';
			}
		}

		// [ps_new_products p=1 n=10 tpl=product-list.tpl]
		function shortcode_ps_new_products($atts) {
			extract(shortcode_atts(array(
				'p' => '1',
				'n' => '10',
				'tpl' => 'product-list.tpl',
			), $atts));

			if ( $this->psValid() ) {
				$this->setTemplateVars( array(
					'products' => Product::getNewProducts($this->psLang(), $p - 1, $n),
					'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
					'categorySize' => Image::getSize('category'),
					'mediumSize' => Image::getSize('medium'),
					'homeSize' => Image::getSize('home') ));
				return $this->fetchTemplate(_PS_THEME_DIR_.$tpl);
			}
		}

		// [ps_product_list id_product= p=1 n=10 tpl=product-list.tpl]
		// [ps_product_list id_category=1 p=1 n=10 tpl=product-list.tpl]
		function shortcode_ps_product_list($atts) {
			extract(shortcode_atts(array(
				'id_product' => '',
				'id_category' => ( version_compare(_PS_VERSION_, '1.5', '>=') ? '2' : '1' ),
				'id_shop' => null,
				'id_currency' => null,
				'id_country' => null,
				'p' => '1',
				'n' => '10',
				'tpl' => 'product-list.tpl',
				'class' => '',
				'label' => '',
			), $atts));

			if ( $this->psValid() ) {
				if ( (int)$id_product > 0 ) {
					$products = $this->getProducts($this->psLang(), $id_product);
				} elseif ( (int)$id_category > 0 ) {
error_log("seek category=$id_category, lang=".$this->psLang());
					$category = new Category((int)$id_category, $this->psLang(), $id_shop);
					if ($id_shop)
					{
						$context = Context::getContext();
						$current_shop = $context->shop;
						$current_currency = $context->currency;
						$current_country = $context->country;

						Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
						$context->shop = new Shop((int)$id_shop);
						if ($id_currency)
							$context->currency = Currency::getCurrencyInstance((int)$id_currency);
						if ($id_country)
							$context->country = new Country((int)$id_country);
						$this->setTemplateVars( array(
							'currency' => $context->currency ));
					}
					$products = $category->getProducts($this->psLang(), $p, $n);
				}
			}

			$result = '';
			if ($products) {
			    if ( version_compare(_PS_VERSION_, '1.7', '>=') ) {
				$context = Context::getContext();
        $assembler = new ProductAssembler($context);

        $presenterFactory = new ProductPresenterFactory($context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
	    new PrestaShop\PrestaShop\Adapter\Image\ImageRetriever(
                $context->link
            ),
            $context->link,
	    new PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
	    new PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
            $context->getTranslator()
        );

        foreach ($products as &$p) {
            $p = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($p),
                $context->language
            );
        }
/*
				$retriever = new PrestaShop\PrestaShop\Adapter\Image\ImageRetriever(
				    $context->link
				);

				// FIXME: hacked and dirty update of some fields and need reworking
				foreach ($products as &$p) {
				    $o = new Product($p['id_product'], true, $this->psLang(), $context->shop->id);

				    $p['canonical_url'] = $p['url'] = $context->link->getProductLink((int)$p['id_product'], $p['link_rewrite'], $p['category'], $p['ean13']);
				    $p['cover'] = $retriever->getImage($o, $p['id_image']);
				    $p['price'] = Tools::displayPrice($p['price']);
				}
*/
			    }

			    $listing = array(
				'result' => $this,
				'label' => $label,
				'products' => $products,
				'sort_orders' => '',
				'sort_selected' => '',
				'pagination' => array(
					'items_shown_from' => 1,
					'items_shown_to' => count($products),
					'total_items' => count($products),
					'should_be_displayed' => false,
				),
				'rendered_facets' => '',
				'rendered_active_filters' => '',
				'js_enabled' => 1,
				'current_url' => '',
				'q' => '',
			    );

			    $this->setTemplateVars( array(
				'items_shown_from' => 1,
				'listing' => $listing,
				'products' => $products,
				'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
				'categorySize' => Image::getSize('category'),
				'mediumSize' => Image::getSize('medium'),
					'homeSize' => Image::getSize('home') ));
				$result = ($class ? '<div class="'.$class.'">' : '').$this->fetchTemplate(_PS_THEME_DIR_.$tpl).($class ? '</div>' : '');
			}
			if (isset($current_shop)&&$current_shop)
			{
				$context->shop = $current_shop;
				if ($current_currency)
					$context->currency = $current_currency;
				if ($current_country)
					$context->country = $current_country;
				Shop::setContext(Shop::CONTEXT_SHOP, $current_shop->id);
			}

			return $result;
		}

		function getProducts( $id_lang, $ids, $get_products_properties = true, $id_shop = 0 ) {
			if ( class_exists( 'Shop' ) ) {
				$context = Context::getContext();
				if ( $id_shop == 0 )
					$id_shop = $context->shop->id;
			}

			$sql = '
			SELECT p.*, '.( $id_shop > 0 ? 'product_shop.*, ' : '').'pa.`id_product_attribute`, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`, il.`legend`, m.`name` AS manufacturer_name, tl.`name` AS tax_name, t.`rate`, cl.`name` AS category_default, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new, (p.`price` * IF(t.`rate`,((100 + (t.`rate`))/100),1)) AS orderprice
			FROM `'._DB_PREFIX_.'product` p
			'.( $id_shop > 0 ? Shop::addSqlAssociation('product', 'p') : '').'
			LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product` AND default_on = 1)
			LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.(int)($id_lang).( $id_shop > 0 ? ' AND cl.`id_shop` = '.$id_shop : '').')
			LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).( $id_shop > 0 ? ' AND pl.`id_shop` = '.$id_shop : '').')
			LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
			LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)($id_lang).')
			LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
			                                           AND tr.`id_country` = '.(int)Country::getDefaultCountryId().'
			                                           AND tr.`id_state` = 0)
			LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
			LEFT JOIN `'._DB_PREFIX_.'tax_lang` tl ON (t.`id_tax` = tl.`id_tax` AND tl.`id_lang` = '.(int)($id_lang).')
			LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
			WHERE p.`active` = 1 AND p.`id_product` IN ('.$ids.')'.( $id_shop > 0 ? ' AND product_shop.`id_shop` = '.$id_shop : '');

			$products = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
			if ($get_products_properties)
				$products = Product::getProductsProperties((int)($id_lang), $products);

			return $products;
		}

		function getStore( $id_store, $id_lang ) {
			$stores = $this->getStores( $id_lang, $id_store );

			foreach ($stores as $store) {
				if ($store['id_store'] == $id_store)
					return $store;
			}

			return false;
		}

		function getStores( $id_lang, $id_store = false ) {
			if ($this->stores)
				return $this->stores;

			$sql = 'SELECT s.*, cl.name country, st.iso_code state
				FROM '._DB_PREFIX_.'store s
				'.( class_exists( 'Shop' ) ? Shop::addSqlAssociation('store', 's') : '').'
				LEFT JOIN '._DB_PREFIX_.'country_lang cl ON (cl.id_country = s.id_country)
				LEFT JOIN '._DB_PREFIX_.'state st ON (st.id_state = s.id_state)
				WHERE '.($id_store ? 's.id_store = '.$id_store : 's.active = 1').' AND cl.id_lang = '.$id_lang.'
				ORDER BY s.name ASC';

			$stores = $this->psdb->get_results( $sql, ARRAY_A );
			foreach ($stores as &$store)
			{
				$store['has_picture'] = file_exists(_PS_STORE_IMG_DIR_.(int)$store['id_store'].'.jpg');
				$store['hours'] = @unserialize($store['hours']);

			}

			if ($id_store)
				return $stores;

			return $this->stores = $stores;
		}

		function getTemplateVars( $name, $sub = null ) {
			global $smarty;
			$_smarty = isset( $this->controller->context) ? $this->controller->context->smarty : $smarty;

			if ( method_exists( $_smarty, 'get_template_vars' ) )
				$result = $_smarty->get_template_vars( $name );
			elseif ( method_exists( $_smarty, 'getTemplateVars' ) )
				$result = $_smarty->getTemplateVars( $name );
			else
				$result = NULL;

			if ( isset( $sub ) )
			    $result = $result[$sub];

			return $result;
		}

		function setTemplateVars( $tpl_var, $value = NULL, $nocache = false ) {
			global $smarty;
			$_smarty = isset( $this->controller->context) ? $this->controller->context->smarty : $smarty;

			return $_smarty->assign( $tpl_var, $value, $nocache );
		}

		function displayTemplate( $template = null, $cache_id = null, $compile_id = null, $parent = null) {
			global $smarty;
			$_smarty = isset( $this->controller->context) ? $this->controller->context->smarty : $smarty;

			return $_smarty->display( $template, $cache_id, $compile_id, $parent );
		}

		function fetchTemplate( $template = null, $cache_id = null, $compile_id = null, $parent = null) {
			global $smarty;
			$_smarty = isset( $this->controller->context) ? $this->controller->context->smarty : $smarty;

			return $_smarty->fetch( $template, $cache_id, $compile_id, $parent );
		}

		function getTranslation( $array ) {
			global $smarty;
			$_smarty = isset( $this->controller->context) ? $this->controller->context->smarty : $smarty;

			if ( class_exists ( 'Smarty_Internal_Template' ) && isset( $array['tpl'] ) )
				$smarty_tpl = new Smarty_Internal_Template( _PS_THEME_DIR_.$array['tpl'], $_smarty );
			return smartyTranslate( $array, $smarty_tpl );
		}

		function getHook ( $name ) {
			global $smarty;
			$_smarty = isset( $this->controller->context) ? $this->controller->context->smarty : $smarty;

			return $_smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0][0]->smartyHook(array('h'=>$name),$_smarty);
		}
	}

	global $prestashop_integration;
	$prestashop_integration = new PrestaShopIntegration();

	function ps_l( $array ) {
		global $prestashop_integration;
		echo $prestashop_integration->getTranslation( $array );
	}

	function ps_include( $tpl ) {
		global $prestashop_integration;
		echo $prestashop_integration->displayTemplate( _PS_THEME_DIR_.$tpl );
	}

	function ps_template_vars( $name, $sub = null ) {
		global $prestashop_integration;
		echo $prestashop_integration->getTemplateVars( $name, $sub );
	}

	function ps_get_template_vars( $name, $sub = null ) {
		global $prestashop_integration;
		return $prestashop_integration->getTemplateVars( $name, $sub );
	}

	function ps_set_template_vars( $name, $value = null, $nocache = false ) {
		global $prestashop_integration;
		return $prestashop_integration->setTemplateVars( $name, $value, $nocache );
	}

	/* function ps_body_classes is an helper function for PS 1.7 and above */
	function ps_body_classes( $body_classes = '', $del_classes = '' ) {
		global $prestashop_integration;
		if ( $classes = $prestashop_integration->getTemplateVars( 'page', 'body_classes' ) ) {
		    if ( !is_array( $del_classes ) )
			$del_classes = explode( ' ', $del_classes );
		    foreach( $classes as $name => $val ) {
			if ( isset($val) && $val && !in_array($name) )
			    $body_classes .= ( $body_classes ? ' ' : '' ).strtolower( $name );
		    }
		}
		return $body_classes;
	}

	function ps_page_link ( $filename, $ssl = false, $id_lang = null ) {
		global $link;
		echo $link->getPageLink( $filename, $ssl, $id_lang );
	}

	function ps_hook ( $name ) {
		global $prestashop_integration;
		echo $prestashop_integration->getHook( $name );
	}

	function ps_get_hook ( $name ) {
		global $prestashop_integration;
		return $prestashop_integration->getHook( $name );
	}

	function ps_get_store( $id_store, $id_lang = null ) {
		global $prestashop_integration;
		if ( !$id_lang ) $id_lang = $prestashop_integration->psLang();
		return $prestashop_integration->getStore( $id_store, $id_lang );
	}

	function ps_get_stores( $id_lang = null ) {
		global $prestashop_integration;
		if ( !$id_lang ) $id_lang = $prestashop_integration->psLang();
		return $prestashop_integration->getStores( $id_lang );
	}
}
