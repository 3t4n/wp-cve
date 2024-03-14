<?php

/*

Plugin Name: Content Snippet Manager
Plugin URI: https://www.themeinprogress.com/content-snippet-manager/
Description: Content Snippet Manager plugin allows you to create and manage unlimited numbers of CSS, HTML, Javascript, Tracking codes, Banners and WordPress shortcodes.
Version: 1.1.2
Text Domain: content-snippet-manager
Author: ThemeinProgress
Author URI: https://www.themeinprogress.com
License: GPL3
Domain Path: /languages/

Copyright 2023  ThemeinProgress  (email : support@wpinprogress.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

WC requires at least: 3.0.0
WC tested up to: 8.4

*/

define( 'CSM_VERSION', '1.1.2' );
define( 'CSM_UPGRADE_LINK', 'https://www.themeinprogress.com/content-snippet-manager' );
define( 'CSM_CONVERSION_SNIPPETS', 'https://demo.themeinprogress.com/content-snippet-manager-pro/conversion-shortcodes/' );

if( !class_exists( 'csm_init' ) ) {

	class csm_init {

		/**
		* Constructor
		*/

		public function __construct() {

			add_action('admin_init', array(&$this, 'disable_plugins') );
			add_action('wp_head', array(&$this, 'headerScripts') );
			add_action('wp_footer', array(&$this, 'footerScripts') );
			add_action('woocommerce_thankyou', array(&$this, 'woocommerceScripts'));
			add_filter('get_the_excerpt', array(&$this, 'excerptScripts') );
			add_filter('the_content', array(&$this, 'contentScripts') );
			add_action('plugins_loaded', array(&$this,'plugin_setup'));
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_action_links' ), 10, 2 );

			if ( function_exists('wp_body_open') )
				add_action('wp_body_open', array(&$this, 'bodyScripts'));

		}

		/**
		* Disable Premi
		*/

		public function disable_plugins() {

			if (is_plugin_active('content-snippet-manager-pro/init.php'))
				deactivate_plugins('content-snippet-manager-pro/init.php');

		}

		/**
		* CURRENT PAGE
		*/

		private function get_current_page() {

			if ( is_home() ) {
				$current = 'home';
			} elseif ( is_single() ) {
				$current = 'single';
			} elseif ( is_page() ) {
				$current = 'page';
			} elseif ( csm_is_woocommerce_active('is_shop')) {
				$current = 'shop';
			} elseif ( is_category() ) {
				$current = 'category';
			} elseif ( is_tag() ) {
				$current = 'tag';
			} elseif ( is_tax() ) {
				$current = 'tax';
			} elseif ( is_search() ) {
				$current = 'search';
			}

			return isset($current) ? $current : false;

		}

		/**
		* CHECK SCRIPT
		*/

		private function isLoadableScript( $ID, $type, $settings, $function, $control ) {

			$loadScript = FALSE;

			if ( call_user_func($function, $type) ) :

				if ( in_array('-1', $settings) && $control == 'in_array') {

					$loadScript = TRUE;

				} elseif ( is_array($settings) ) {

					switch ( $control ) {

						case 'in_array':
							if ( in_array($ID, $settings) )
								$loadScript = TRUE;
						break;

						case 'not_in_array':
							if ( !in_array($ID, $settings) && !in_array('-1', $settings) )
								$loadScript = TRUE;
						break;

					}

				}

			endif;

			return $loadScript;

		}

		/**
		* SPLIT BY CHARS
		*/

		function splitbyChars($string, $snippets ) {

			$counter = 0;
			$chars = strlen($string);

			foreach ( $snippets as $snippet ) {

				$found = false;

				if ( $snippet['position'] > $chars )
					$snippet['position'] = '-1';

				if ($snippet['position'] == '0' || $snippet['position'] == '-1' ) {

					if (isset($buffer[$snippet['position']])) {

						$buffer[$snippet['position']] .= $snippet['code'];

					} else {

						$buffer[$snippet['position']]  = $snippet['code'];

					}

				} else {

					for ($counter = 0; $counter <= $chars-1; $counter++) {

						if($string[$counter] === ' ' && $snippet['position'] <= $counter && $found == false) {

							$found = true;

							if (isset($buffer[$counter])) {

								$buffer[$counter] .= $snippet['code'];

							} else {

								$buffer[$counter]  = $snippet['code'];

							}

						} elseif( $chars-1 == $counter && $found == false) {

							if (isset($buffer[-1])) {

								$buffer[-1] .= $snippet['code'];

							} else {

								$buffer[-1]  = $snippet['code'];

							}

						}

					}

				}

			}

			$temp = str_split($string);

			foreach ( $buffer as $k => $v ) {

				if (array_key_exists($k, $temp) && $temp[$k] === ' ') {

					$temp[$k] = $temp[$k] . $v;

				} elseif (isset($temp[0]) && $k == '0' ) {

					$temp[0] = $v . $temp[0];

				} elseif (isset($temp[$chars-1]) && $k == '-1' ) {

					$temp[$chars-1] = $temp[$chars-1] . $v;

				} else {

					$temp[0] = $temp[0] . $v;

				}

			}

			$content = implode('', $temp);
			return $content;

		}

		/**
		* SPLIT BY PARAGRAPHS
		*/

		function splitbyParagraphs($string, $snippets ) {

			$counter = 0;

			foreach ( $snippets as $snippet ) {

				if (isset($buffer[$snippet['position']])) {

					$buffer[$snippet['position']] .= $snippet['code'];

				} else {

					$buffer[$snippet['position']]  = $snippet['code'];

				}

			}

			$temp = explode('</p>', $string);

			$counter = count($temp)-1;

			foreach ( $buffer as $k => $v ) {

				if ($k === 0) {

					$temp[0] = $v . $temp[0];

				} elseif (array_key_exists($k, $temp))  {

					$temp[$k] = $v . $temp[$k] ;

				} elseif (!array_key_exists($k, $temp) || $k === -1){

					$temp[$counter] = $temp[$counter] . $v;

				}

			}

			$content = implode('</p>', $temp);
			return $content;

		}

		/**
		* HEADER SCRIPTS
		*/

		public function headerScripts() {

			ob_start();
			$this->loadScripts('scriptOnHeader');
			$output = ob_get_contents();
			ob_end_clean();

			echo (!empty($output)) ? '<!-- HEADER SNIPPET -->' . $output . '<!-- /CONTENT SNIPPET MANAGER BY THEMEINPROGRESS.COM -->' : '';

		}

		/**
		* BODY SCRIPTS
		*/

		public function bodyScripts() {

			global $globalSnippet;

			if (isset($globalSnippet) && is_array($globalSnippet)) {

				foreach ($globalSnippet as $k) {

					if (isset($k['scriptOnBody'])) {

						$output[] = $k['scriptOnBody']['code'];

					}

				}

				echo (isset($output) && is_array($output)) ? '<!-- BODY SNIPPET -->' . implode('', $output) . '<!-- /CONTENT SNIPPET MANAGER BY THEMEINPROGRESS.COM -->' : '';

			}

		}

		/**
		* FOOTER SCRIPTS
		*/

		public function footerScripts() {

			global $conversionScript;

			ob_start();
			$this->loadScripts('scriptOnFooter');
			$output = ob_get_contents();
			ob_end_clean();

			if (
				isset($conversionScript) &&
				is_array($conversionScript)
			) {

				echo '<!-- WOOCOMMERCE CONVERSION SNIPPET -->' . do_shortcode(implode(' ', $conversionScript)) . '<!-- /CONTENT SNIPPET MANAGER BY THEMEINPROGRESS.COM -->';

			}

			if (!empty($output))  {

				echo '<!-- FOOTER SNIPPET -->' . $output . '<!-- /CONTENT SNIPPET MANAGER BY THEMEINPROGRESS.COM -->';

			}

		}

		/**
		* EXCERPT SCRIPTS
		*/

		public function excerptScripts($excerpt) {

			global $globalSnippet;

			$result = $excerpt;

			if (isset($globalSnippet) && is_array($globalSnippet)) {

				foreach ($globalSnippet as $k) {

					if (isset($k['scriptOnExcerpt'])) {

						$snippet[] = array(
							'position' => $k['scriptOnExcerpt']['limit'],
							'code' => '<!-- EXCERPT SCRIPT -->' . $k['scriptOnExcerpt']['code'] . '<!-- /CONTENT SNIPPET MANAGER BY THEMEINPROGRESS.COM -->'
						);

					}

				}

				if (isset ($snippet) && is_array($snippet))
					$result = $this->splitbyChars($excerpt, $snippet);

			}

			return do_shortcode($result);

		}

		/**
		* CONTENT SCRIPTS
		*/

		public function contentScripts($content) {

			global $globalSnippet, $wp_current_filter;

			$result = $content;

			if (isset($globalSnippet) && is_array($globalSnippet) && !in_array('get_the_excerpt', $wp_current_filter)) {

				foreach ($globalSnippet as $k ) {

					if (isset($k['scriptOnContent'])) {

						$snippet[] = array(
							'position' => $k['scriptOnContent']['limit'],
							'code' => '<!-- CONTENT SCRIPT -->' . $k['scriptOnContent']['code'] . '<!-- /CONTENT SNIPPET MANAGER BY THEMEINPROGRESS.COM -->'
						);

					}

				}

				if (isset ($snippet) && is_array($snippet))
					$result = $this->splitbyParagraphs($content, $snippet);

			}

			return do_shortcode($result);

		}

		/**
		* WOOCOMMERCE SCRIPTS
		*/

		public function woocommerceScripts($orderId) {

			global $post, $conversionScript;

			$csm_setting = csm_setting('csm_snippets');

			if ( is_array($csm_setting) ) {

				$order=new WC_Order($orderId);
				$items=$order->get_items();

				foreach($items as $k=>$v) {
					$itemList[] = $v['name'];
				}

				foreach ( $csm_setting as $script ) {

					$loadScript = FALSE;

					if ( isset($script['position']) && $script['position'] === 'woocommerceConversion' ) {

							switch(true) {

								case (isset($script['include_product']) && $script['product_matchValue'] == 'include') :

									foreach($items as $k=>$v) {

										$k=intval($v['product_id']);

										if($k>0) {
											$loadScript = $this->isLoadableScript( $v['product_id'], FALSE, $script['include_product'], 'is_page', 'in_array' );
										}

									}

								break;

								case (isset($script['exclude_product']) && $script['product_matchValue'] == 'exclude') :

									foreach($items as $k=>$v) {

										$k=intval($v['product_id']);

										if($k>0) {
											$loadScript = $this->isLoadableScript( $v['product_id'], FALSE, $script['exclude_product'], 'is_page', 'not_in_array' );
										}

									}

								break;

							}

					}

					if ( $loadScript == TRUE ) {

						$conversionScript[] = stripslashes($script['code']);

					}

				}

			}

		}

		/**
		* LOAD SCRIPTS
		*/

		public function loadScripts($position) {

			global $post, $globalSnippet;

			$csm_setting = csm_setting('csm_snippets');

			if ( is_array($csm_setting) ) {

				$count = 0;

				foreach ( $csm_setting as $script ) {

					$count++;

					if ( isset($script['position']) && $script['position'] !== 'woocommerceConversion' ) {

						$loadScript = FALSE;

						switch ( $this->get_current_page() ) {

							case 'home' :

								switch(true) {

									case (isset($script['include_home']) && $script['include_home'] == 'on') :

										$loadScript = TRUE;

									break;

									default:

										$loadScript = FALSE;

								}

							break;

							case 'search' :

								switch(true) {

									case (isset($script['include_search']) && $script['include_search'] == 'on') :

										$loadScript = TRUE;

									break;

									default:

										$loadScript = FALSE;

								}

							break;

							case 'single':
							case 'page':

								if ( isset($script['include_whole_website']) && $script['include_whole_website'] == 'on' ) :

									$loadScript = TRUE;

								else :

									$postID = $post->ID;
									$postType = get_post_type();

									switch(true) {

										case (isset($script[$postType . '_matchValue']) && isset($script['include_' . $postType]) && $script[$postType . '_matchValue'] == 'include') :

											if ( is_singular($postType) ) :
												$loadScript = $this->isLoadableScript( $postID, $postType, $script['include_' . $postType], 'is_singular', 'in_array' );
											endif;

										break;

										case (isset($script[$postType . '_matchValue']) && isset($script['exclude_' . $postType]) && $script[$postType . '_matchValue'] == 'exclude') :

											if ( is_singular($postType) ) :
												$loadScript = $this->isLoadableScript( $postID, $postType, $script['exclude_' . $postType], 'is_singular', 'not_in_array' );
											endif;

										break;

										default:

											$loadScript = FALSE;

									}

								endif;

							break;

							case 'shop':

								if ( isset($script['include_whole_website']) && $script['include_whole_website'] == 'on' ) {

									$loadScript = TRUE;

								} else {

									$shopID = wc_get_page_id('shop');

									switch(true) {

										case (isset($script['page_matchValue']) && isset($script['include_page']) && $script['page_matchValue'] == 'include') :

											$loadScript = $this->isLoadableScript( $shopID, FALSE, $script['include_page'], 'is_shop', 'in_array' );
											break;

										case (isset($script['page_matchValue']) && isset($script['exclude_page']) && $script['page_matchValue'] == 'exclude') :

											$loadScript = $this->isLoadableScript( $shopID, FALSE, $script['exclude_page'], 'is_shop', 'not_in_array' );
											break;

										default:

											$loadScript = FALSE;

									}

								}

							break;

							case 'category':
							case 'tag':

								if ( isset($script['include_whole_website']) && $script['include_whole_website'] == 'on' ) :

									$loadScript = TRUE;

								else :

									$catID = get_queried_object()->term_id;
									$catSlug = get_queried_object()->taxonomy;
									$catType = str_replace('post_', '', get_queried_object()->taxonomy);

									switch(true) {

										case (isset($script[$catSlug . '_matchValue']) && isset($script['include_' . $catSlug])  && $script[$catSlug . '_matchValue'] == 'include') :

											$loadScript = $this->isLoadableScript( $catID, FALSE, $script['include_' . $catSlug], 'is_' . $catType, 'in_array' );
											break;

										case (isset($script[$catSlug . '_matchValue']) && isset($script['exclude_' . $catSlug])  && $script[$catSlug . '_matchValue'] == 'exclude') :

											$loadScript = $this->isLoadableScript( $catID, FALSE, $script['exclude_' . $catSlug], 'is_' . $catType, 'not_in_array' );
											break;

										default:

											$loadScript = FALSE;

									}

								endif;

							break;

							case 'tax':

								if ( isset($script['include_whole_website']) && $script['include_whole_website'] == 'on' ) :

									$loadScript = TRUE;

								else :

									$taxID = get_queried_object()->term_id;
									$taxType = get_queried_object()->taxonomy;

									switch(true) {

										case (isset($script[$taxType . '_matchValue']) && isset($script['include_' . $taxType])  && $script[$taxType . '_matchValue'] == 'include') :

											$loadScript = $this->isLoadableScript( $taxID, $taxType, $script['include_' . $taxType], 'is_tax', 'in_array' );
											break;

										case (isset($script[$taxType . '_matchValue']) && isset($script['exclude_' . $taxType])  && $script[$taxType . '_matchValue'] == 'exclude') :

											$loadScript = $this->isLoadableScript( $taxID, $taxType, $script['exclude_' . $taxType], 'is_tax', 'not_in_array' );
											break;

										default:

											$loadScript = FALSE;

									}

								endif;

							break;

						}

						if ($loadScript == TRUE && isset($script['position']) ) :

							if ( $script['position'] === 'scriptOnExcerpt' ) {

								$globalSnippet[] =

								array( 'scriptOnExcerpt' =>

									array(
										'limit' => intval($script['excerptLimit']),
										'position' => esc_attr($script['position']),
										'code' => stripslashes($script['code'])
									)

								);

							} elseif ( $script['position'] === 'scriptOnContent' ) {

								$globalSnippet[] =

								array( 'scriptOnContent' =>

									array(
										'limit' => intval($script['contentLimit']),
										'position' => esc_attr($script['position']),
										'code' => stripslashes($script['code'])
									)

								);

							} elseif ( $script['position'] === 'scriptOnBody' ) {

								$globalSnippet[] =

								array(
									'scriptOnBody' =>
									array(
										'code' => do_shortcode(stripslashes($script['code']))
									)
								);

							} elseif ( $position == $script['position'] ) {

								echo do_shortcode(stripslashes($script['code']));

							}

						endif;

					}

				}

			}

		}

		/**
		* Plugin settings link
		*/

		public function plugin_action_links( $links ) {
			$links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=csm_panel') ) .'">' . esc_html__('Settings','content-snippet-manager') . '</a>';
			$links[] = '<a target="_blank" href="'. esc_url( CSM_UPGRADE_LINK . '/?ref=2&campaign=plugin_section' ).'">' . esc_html__('Upgrade to Premium','content-snippet-manager') . '</a>';
			return $links;

		}

		/**
		* Plugin setup
		*/

		public function plugin_setup() {

			load_plugin_textdomain( 'content-snippet-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

			require_once dirname(__FILE__) . '/core/functions/csm_functions.php';
			require_once dirname(__FILE__) . '/core/includes/class-form.php';
			require_once dirname(__FILE__) . '/core/includes/class-panel.php';
			require_once dirname(__FILE__) . '/core/includes/class-notice.php';
			require_once dirname(__FILE__) . '/core/shortcodes/analytics.php';
			require_once dirname(__FILE__) . '/core/shortcodes/fb.php';

			if ( is_admin() == 1 )
				require_once dirname(__FILE__) . '/core/admin/panel.php';

		}

	}

	new csm_init();

}

?>
