<?php
class WootablepressWtbp extends ModuleWtbp {
	public function init() {
		if (is_admin()) {
			add_action('admin_notices', array($this, 'showAdminErrors'));
		}
		DispatcherWtbp::addFilter('mainAdminTabs', array($this, 'addAdminTab'));
		add_shortcode(WTBP_SHORTCODE, array($this, 'render'));
	}
	public function addAdminTab( $tabs ) {
		$tabs[ $this->getCode() . '#wtbpadd' ] = array(
			'label' => esc_html__('Add New Table', 'woo-product-tables'), 'callback' => array($this, 'getTabContent'), 'fa_icon' => 'fa-plus-circle', 'sort_order' => 10, 'add_bread' => $this->getCode(),
		);
		$tabs[ $this->getCode() . '_edit' ] = array(
			'label' => esc_html__('Edit', 'woo-product-tables'), 'callback' => array($this, 'getEditTabContent'), 'sort_order' => 20, 'child_of' => $this->getCode(), 'hidden' => 1, 'add_bread' => $this->getCode(),
		);
		$tabs[ $this->getCode() ] = array(
			'label' => esc_html__('Show All Tables', 'woo-product-tables'), 'callback' => array($this, 'getTabContent'), 'fa_icon' => 'fa-table', 'sort_order' => 20,
		);
		return $tabs;
	}
	public function getTabContent() {
		return $this->getView()->getTabContent();
	}
	public function getEditTabContent() {
		$id = ReqWtbp::getVar('id', 'get');
		return $this->getView()->getEditTabContent( $id );
	}
	public function getEditLink( $id, $tableTab = '' ) {
		$link = FrameWtbp::_()->getModule('options')->getTabUrl( $this->getCode() . '_edit' );
		$link .= '&id=' . $id;
		if (!empty($tableTab)) {
			$link .= '#' . $tableTab;
		}
		return $link;
	}
	public function render( $params ) {
		return $this->getView()->renderHtml($params);
	}
	public function showAdminErrors() {
		// check WooCommerce is installed and activated
		if (!$this->isWooCommercePluginActivated()) {
			// WooCommerce install url
			$wooCommerceInstallUrl = add_query_arg(
				array(
					's' => 'WooCommerce',
					'tab' => 'search',
					'type' => 'term',
				),
				admin_url( 'plugin-install.php' )
			);
			$tableView = $this->getView();
			/* translators: %s: module name */
			$error = sprintf(esc_html__('For work with "%s" plugin, You need to install and activate', 'woo-product-tables'), WTBP_WP_PLUGIN_NAME) .
				' <a target="_blank" href="' . esc_url($wooCommerceInstallUrl) . '">WooCommerce</a> ' . esc_html__('plugin', 'woo-product-tables');

			$tableView->assign('errorMsg', $error);
			// check current module
			if (isset($_GET['page']) && WTBP_SHORTCODE == $_GET['page']) {
				// show message
				HtmlWtbp::echoEscapedHtml($tableView->getContent('showAdminNotice'));
			}
		}
	}
	public function isWooCommercePluginActivated() {
		return class_exists('WooCommerce');
	}
	public function isWcmfPluginActivated() {
		return class_exists('WCFM');
	}

	public function unserialize( $data, $isReplaceCallback = true ) {
		if ($isReplaceCallback) {
			$data = preg_replace_callback ( '!s:(\d+):"(.*?)";!', function( $match ) {
				return ( strlen($match[2]) == $match[1] ) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
			}, $data );
		}

		if ( @unserialize(base64_decode($data)) !== false ) {
			return unserialize(base64_decode($data));
		} else {
			return unserialize($data);
		}
	}
	
	public function beforeMainQueryCwgCheckTerms( $args = array() ) {
		$tax_query = array();
		$user_id = is_user_logged_in() ? get_current_user_id() : 0;
		
		$visibility_obj = new CWG_Product_Visibility_API($user_id);
		$get_role = $visibility_obj->get_user_role_from_user_id();
		$check = $visibility_obj->get_rule_status($get_role);
		
		$get_role = $check ? $get_role : 'allusers';
		$get_settings = $visibility_obj->get_data_from_settings($get_role);
		
		$visibility = $get_settings['visibility'];
		
		if ('categories' == $get_settings['type'] || 'both' == $get_settings['type'] ) {
			
			$product_type = $get_settings['producttype'];
			$category_type = $get_settings['categorytype'];
			
			if (( 'both' == $get_settings['type']  && 'selected' == $product_type ) || 'categories' == $get_settings['type']) {
				if ('selected' == $category_type) {
					$array_of_category_ids = array_filter((array) $get_settings['categoryids']);
					
					if ('yes' == $get_settings['childcategory'] && $array_of_category_ids) {
						$get_child_terms = $visibility_obj->get_child_term_ids($array_of_category_ids);
						$array_of_category_ids = array_unique(array_filter(array_merge($array_of_category_ids, $get_child_terms)));
					}
					
					if (!empty($array_of_category_ids)) {
						$get_ids = $visibility_obj->get_translated_ids_from_wpml($array_of_category_ids, 'product_cat');
						$merge_data = array_unique(array_filter(array_merge($array_of_category_ids, $get_ids)));
						
						
						if ('include' == $visibility) {
							$tax_query[] = array(
								'taxonomy' => 'product_cat',
								'field' => 'term_id',
								'terms' => $merge_data,
								'operator' => 'IN'
							);
						} else {
							$tax_query[] = array(
								'taxonomy' => 'product_cat',
								'field' => 'term_id',
								'terms' => $merge_data,
								'operator' => 'NOT IN'
							);
						}
					}
				} else {
					$get_ids = $visibility_obj->get_all_term_ids();
					if ($get_ids) {
						$merge_data = (array) array_unique(array_filter($get_ids));
						
						if ('exclude' == $visibility) {
							$tax_query[] = array(
								'taxonomy' => 'product_cat',
								'field' => 'term_id',
								'terms' => $merge_data,
								'operator' => 'NOT IN'
							);
						}
					}
				}
			} else {
				if ('exclude' == $visibility) {
					$get_ids = $visibility_obj->get_all_term_ids();
					if ($get_ids) {
						$merge_data = (array) array_unique(array_filter($get_ids));
						$tax_query[] = array(
							'taxonomy' => 'product_cat',
							'field' => 'term_id',
							'terms' => $merge_data,
							'operator' => 'NOT IN'
						);
					}
				}
			}
		} else {
			if ('products' == $get_settings['type'] && 'all' == $get_settings['producttype']) {
				if ('exclude' == $visibility) {
					$get_ids = $visibility_obj->get_all_term_ids();
					if ($get_ids) {
						$merge_data = $get_ids;
						if ($merge_data) {
							$tax_query[] = array(
								'taxonomy' => 'product_cat',
								'field' => 'term_id',
								'terms' => $merge_data,
								'operator' => 'NOT IN'
							);
						}
					}
				}
			}
		}
		
		if (!empty($tax_query)) {
			$tmps = array(
				'post_type'           => 'product',
				'ignore_sticky_posts' => true,
				'post_status'         => 'publish',
				'posts_per_page'      => -1,
				'fields'              => 'ids',
				'tax_query'           => $tax_query
			);
			$tmp = new WP_Query($tmps);
			if (!empty($tmp->posts)) {
				$args['post__in'] = $tmp->posts;
			}
		}
		
		return $args;
	}
}
