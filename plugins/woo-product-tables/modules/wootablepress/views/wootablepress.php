<?php

class WootablepressViewWtbp extends ViewWtbp {
	public $orderColumns     = array();
	public $columnNiceNames  = array();
	public $loopProductType  = '';
	private $loopButtonTitle = '';

	/**
	 * Adnin view table preview mod current tab
	 *
	 * @var string
	 */
	public $prewiewTab = '';

	public function getTabContent() {
		FrameWtbp::_()->addStyle( 'wtbp.admin.css', $this->getModule()->getModPath() . 'css/admin.tables.css' );
		FrameWtbp::_()->addScript( 'wtbp.admin.list.js', $this->getModule()->getModPath() . 'js/admin.list.js' );
		FrameWtbp::_()->addJSVar( 'wtbp.admin.list.js', 'wtbpTblDataUrl', UriWtbp::mod( 'wootablepress', 'getListForTbl', array( 'reqType' => 'ajax' ) ) );
		FrameWtbp::_()->addJSVar( 'wtbp.admin.list.js', 'url', admin_url( 'admin-ajax.php' ) );
		FrameWtbp::_()->addScript( 'adminCreateTableWtbp', $this->getModule()->getModPath() . 'js/create-table.js' );
		FrameWtbp::_()->addScript( 'wtbp.dataTables.js', $this->getModule()->getModPath() . 'js/dt/jquery.dataTables.min.js' );
		FrameWtbp::_()->addStyle( 'wtbp.dataTables.css', $this->getModule()->getModPath() . 'css/dt/jquery.dataTables.min.css' );
		FrameWtbp::_()->addScript( 'wtbp.buttons', $this->getModule()->getModPath() . 'js/dt/dataTables.buttons.min.js' );

		FrameWtbp::_()->getModule( 'templates' )->loadJqGrid();
		FrameWtbp::_()->getModule( 'templates' )->loadFontAwesome();
		FrameWtbp::_()->getModule( 'templates' )->loadBootstrap();

		$this->assign( 'addNewLink', FrameWtbp::_()->getModule( 'options' )->getTabUrl( 'wootablepress#wtbpadd' ) );
		$this->assign( 'is_pro', FrameWtbp::_()->isPro() );

		return parent::getContent( 'wootablepressAdmin' );
	}

	public function getEditTabContent( $idIn ) {
		$isWooCommercePluginActivated = $this->getModule()->isWooCommercePluginActivated();
		if ( ! $isWooCommercePluginActivated ) {
			return;
		}

		FrameWtbp::_()->getModule( 'templates' )->loadBootstrap();
		FrameWtbp::_()->getModule( 'templates' )->loadJqueryUi();
		FrameWtbp::_()->getModule( 'templates' )->loadCodemirror();
		FrameWtbp::_()->getModule( 'templates' )->loadSlimscroll();

		$this->loadAssets();

		FrameWtbp::_()->addScript( 'wtbp.admin.tables.js', $this->getModule()->getModPath() . 'js/tables.admin.js' );
		FrameWtbp::_()->addStyle( 'wtbp.admin.tables.css', $this->getModule()->getModPath() . 'css/admin.tables.css' );
		FrameWtbp::_()->addStyle( 'wtbp.frontend.tables.css', $this->getModule()->getModPath() . 'css/frontend.tables.css' );
		FrameWtbp::_()->addScript( 'adminCreateTableWtbp', $this->getModule()->getModPath() . 'js/create-table.js' );

		DispatcherWtbp::doAction( 'addScriptsContent', true );

		$idIn         = isset( $idIn ) ? (int) $idIn : 0;
		$table        = $this->getModel( 'wootablepress' )->getById( $idIn );
		$tableColumns = $this->getModel( 'columns' )->getFullColumnList();
		$settings     = $this->getModule()->unserialize( $table['setting_data'] );
		$link         = FrameWtbp::_()->getModule( 'options' )->getTabUrl( $this->getCode() );
		$languages    = FrameWtbp::_()->getModule( 'wootablepress' )->getModel( 'languages' )->getLanguageBackend();

		$this->assign( 'languages', $languages );
		$this->assign( 'link', $link );
		$this->assign( 'settings', $settings );
		$this->assign( 'table', $table );
		$this->assign( 'table_columns', $tableColumns );
		$this->assign( 'authors_html', $this->getAuthorsHtml() );
		$this->assign( 'categories_html', $this->getTaxonomyHierarchyHtml() );
		$this->assign( 'products_has_variations_html', $this->getProductsWithVariationsHtml() );
		$this->assign( 'tags_html', $this->getTaxonomyHierarchyHtml( 0, '', 'product_tag' ) );
		$this->assign( 'attributes_html', $this->getAttributesHierarchy() );
		$this->assign( 'search_table', $this->getLeerSearchTable() );
		$this->assign( 'is_pro', FrameWtbp::_()->isPro() );

		return parent::getContent( 'wootablepressEditAdmin' );
	}

	public function renderHtml( $params ) {
		$isWooCommercePluginActivated = $this->getModule()->isWooCommercePluginActivated();
		if ( ! $isWooCommercePluginActivated ) {
			return;
		}

		$this->loadAssets();

		FrameWtbp::_()->addScript( 'wtpb.frontend.tables.js', $this->getModule()->getModPath() . 'js/tables.frontend.js' );
		FrameWtbp::_()->addStyle( 'wtpb.frontend.tables.css', $this->getModule()->getModPath() . 'css/frontend.tables.css' );
		FrameWtbp::_()->addJSVar( 'wtpb.frontend.tables.js', 'url', admin_url( 'admin-ajax.php' ) );
		FrameWtbp::_()->getModule( 'templates' )->loadCoreJs();
		FrameWtbp::_()->addScript( 'wtpb-lightbox-js', $this->getModule()->getModPath() . 'js/lightbox.js' );
		FrameWtbp::_()->addStyle( 'wtpb-lightbox-css', $this->getModule()->getModPath() . 'css/lightbox.css' );

		DispatcherWtbp::doAction( 'addScriptsContent', false );

		$id    = isset( $params['id'] ) ? (int) $params['id'] : 0;
		$table = $this->getModel( 'wootablepress' )->getById( $id );
		if ( ! $id || ! $table ) {
			return false;
		}
		$tableSettings             = $this->getModule()->unserialize( $table['setting_data'] );
		$settings                  = $this->getTableSetting( $tableSettings, 'settings', array() );
		$tableSettings['settings'] = $this->setVendorId( $settings );
		$html                      = $this->getProductContentFrontend( $id, $tableSettings );
		$filter                    = DispatcherWtbp::applyFilters( 'getTableFilters', '', $id, $tableSettings );

		$tableSettings['settings']['order'] = json_encode( $this->orderColumns );

		if ( ! empty( $tableSettings['settings']['custom_js'] ) ) {
			$tableSettings['settings']['custom_js'] = stripslashes( base64_decode( $tableSettings['settings']['custom_js'] ) );
		}

		$phrases = array(
			'empty_table' => esc_html__( 'There\'re no products in the table', 'woo-product-tables' ),
			'table_info' => esc_html__( 'Showing _START_ to _END_ of _TOTAL_ entries', 'woo-product-tables' ),
			'table_info_empty' => esc_html__( 'Showing 0 to 0 of 0 entries', 'woo-product-tables' ),
			'filtered_info_text' => esc_html__( '(filtered from _MAX_ total entries)', 'woo-product-tables' ),
			'length_text' => esc_html__( 'Show: _MENU_', 'woo-product-tables' ),
			'search_label' => esc_html__( 'Search:', 'woo-product-tables' ),
			'processing_text' => esc_html__( 'Processing...', 'woo-product-tables' ),
			'zero_records' => esc_html__( 'No matching records are found', 'woo-product-tables' ),
			'lang_previous' => esc_html__( 'Previous', 'woo-product-tables' ),
			'lang_next' => esc_html__( 'Next', 'woo-product-tables' ),
			'print_text' => esc_html__( 'Print', 'woo-product-tables' )
		);

		foreach ( $phrases as $phrase => $str) {
			$value = empty($tableSettings['settings'][ $phrase ]) ? '' : $tableSettings['settings'][ $phrase ];
			$tableSettings['settings'][ $phrase ] = empty($value) ? $str : __( $value, 'woo-product-tables' );
		}
		$translates = array('caption_text', 'description_text');
		foreach ($translates as $option) {
			if (!empty($tableSettings['settings'][$option])) {
				$tableSettings['settings'][$option] = __( $tableSettings['settings'][$option], 'woo-product-tables' );
			}
		}
		
		$viewId = $id . '_' . mt_rand( 0, 999999 );
		$this->assign( 'tableId', $id );
		$this->assign( 'viewId', $viewId );
		$this->assign( 'html', $html );
		$this->assign( 'filter', $filter );
		$this->assign( 'settings', $tableSettings );
		$this->assign( 'custom_css', $this->getCustomCss( $tableSettings, 'wtbp-table-' . $viewId ) );
		$this->assign( 'loader', $this->getLoaderHtml( $tableSettings ) );
		$this->assign( 'pre_table', DispatcherWtbp::applyFilters( 'renderPreTableHtml', '', $tableSettings['settings'] ) );

		return parent::getContent( 'wootablepressHtml' );
	}

	public function loadAssets() {
		FrameWtbp::_()->addScript( 'wtbp.dataTables.js', $this->getModule()->getModPath() . 'js/dt/jquery.dataTables.min.js' );
		FrameWtbp::_()->addScript( 'wtbp.buttons', $this->getModule()->getModPath() . 'js/dt/dataTables.buttons.min.js' );
		FrameWtbp::_()->addScript( 'wtbp.colReorder', $this->getModule()->getModPath() . 'js/dt/dataTables.colReorder.min.js' );
		FrameWtbp::_()->addScript( 'wtbp.fixedColumns', $this->getModule()->getModPath() . 'js/dt/dataTables.fixedColumns.min.js' );
		FrameWtbp::_()->addScript( 'wtbp.print', $this->getModule()->getModPath() . 'js/dt/buttons.print.min.js' );
		FrameWtbp::_()->addScript( 'wtbp.fixedHeader', $this->getModule()->getModPath() . 'js/dt/dataTables.fixedHeader.min.js' );
		FrameWtbp::_()->addScript( 'wtbp.scroller', $this->getModule()->getModPath() . 'js/dt/dataTables.scroller.min.js' );
		FrameWtbp::_()->addScript( 'wtbp.responsive', $this->getModule()->getModPath() . 'js/dt/dataTables.responsive.min.js' );
		FrameWtbp::_()->addStyle( 'wtbp.responsive', $this->getModule()->getModPath() . 'css/dt/responsive.dataTables.min.css' );
		FrameWtbp::_()->addStyle( 'wtbp.dataTables.css', $this->getModule()->getModPath() . 'css/dt/jquery.dataTables.min.css' );
		FrameWtbp::_()->addStyle( 'wtbp.fixedHeader.css', $this->getModule()->getModPath() . 'css/dt/fixedHeader.dataTables.min.css' );
		FrameWtbp::_()->addScript( 'wtbp.core.tables.js', $this->getModule()->getModPath() . 'js/core.tables.js' );
		FrameWtbp::_()->addJSVar( 'wtbp.core.tables.js', 'url', admin_url( 'admin-ajax.php' ) );
		FrameWtbp::_()->addStyle( 'wtbp.loaders.css', $this->getModule()->getModPath() . 'css/loaders.css' );
		FrameWtbp::_()->addScript( 'wtbp.notify.js', WTBP_JS_PATH . 'notify.js', array(), false, true );
		$options = FrameWtbp::_()->getModule( 'options' )->getModel( 'options' )->getAll();
		if ( isset( $options['accent_neutralise'] ) && isset( $options['accent_neutralise']['value'] ) && ! empty( $options['accent_neutralise']['value'] ) ) {
			FrameWtbp::_()->addScript( 'wtbp.removeAccents', $this->getModule()->getModPath() . 'js/dt/dataTables.removeAccents.min.js' );
		}
		if ( ! empty( $options['google_api_map_key']['value'] ) ) {
			FrameWtbp::_()->addScript( 'wtbp.google.map', 'https://maps.googleapis.com/maps/api/js?key=' . esc_html( $options['google_api_map_key']['value'] ) . '&callback=wtbpInitMap' );
		}
	}

	public function getCustomCss( &$tableSettings, $viewId, $raw = true ) {
		if ( isset( $tableSettings['settings']['custom_css'] ) && ! empty( $tableSettings['settings']['custom_css'] ) ) {
			$customCss = $raw ? base64_decode( $tableSettings['settings']['custom_css'] ) : $tableSettings['settings']['custom_css'];
			unset( $tableSettings['settings']['custom_css'] );
		} else {
			$customCss = '';
		}

		return DispatcherWtbp::applyFilters( 'getCustomStyles', $customCss, $viewId, $tableSettings['settings'] );
	}

	public function getLoaderHtml( $settings ) {
		$html = '';
		if ( ! $this->getTableSetting( $settings['settings'], 'hide_table_loader', false ) ) {
			$html = '<div class="woobewoo-table-loader wtbpLogoLoader"></div>';
			$html = DispatcherWtbp::applyFilters( 'getLoaderHtml', $html, $settings['settings'] );
			$html = '<div class="wtbpLoader">' . $html . '</div>';
		}

		return $html;
	}

	public function getSearchProductsFilters( $args, $params ) {
		$filterAuthor    = isset( $params['filter_author'] ) ? $params['filter_author'] : 0;
		$filterCategory  = isset( $params['filter_category'] ) ? $params['filter_category'] : 0;
		$filterTag       = isset( $params['filter_tag'] ) ? $params['filter_tag'] : 0;
		$filterAttribute = isset( $params['filter_attribute'] ) ? $params['filter_attribute'] : 0;
		$filterStock = isset( $params['filter_stock'] ) ? $params['filter_stock'] : 0;

		if ( ! empty( $filterAuthor ) ) {
			$args['author'] = $filterAuthor;
		}

		if ( ! empty( $filterCategory ) ) {
			$args['tax_query'][] = array(
				'taxonomy'         => 'product_cat',
				'field'            => 'id',
				'terms'            => $filterCategory,
				'include_children' => true
			);
		}

		if ( ! empty( $filterTag ) ) {
			$args['tax_query'][] = array(
				'taxonomy'         => 'product_tag',
				'field'            => 'id',
				'terms'            => $filterTag,
				'include_children' => true
			);
		}

		if ( ! empty( $filterAttribute ) ) {
			if ( empty( wc_get_attribute( $filterAttribute )->slug ) ) {
				$term                = get_term( $filterAttribute );
				$taxonomy            = $term->taxonomy;
				$args['tax_query'][] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'id',
					'terms'    => $filterAttribute,
					'operator' => 'IN'
				);
			} else {
				$term                = get_term( $filterAttribute );
				$taxonomy            = $term->taxonomy;
				$args['tax_query'][] = array(
					'taxonomy' => wc_get_attribute( $filterAttribute )->slug,
					'operator' => 'EXISTS',
				);
			}
		}

		if ( ! empty( $params['search']['value'] ) ) {
			if ( FrameWtbp::_()->isPro() ) {
				global $wpdb;
				$sku     = '%' . $wpdb->esc_like( $params['search']['value'] ) . '%';
				$postIds = $wpdb->get_col( $wpdb->prepare( "SELECT p.ID FROM $wpdb->posts as p INNER JOIN $wpdb->postmeta as pm ON p.ID = pm.post_id
						   WHERE p.post_title LIKE %s OR p.post_content LIKE %s OR p.post_excerpt LIKE %s OR (pm.meta_key = '_sku' AND pm.meta_value LIKE %s)", $sku, $sku, $sku, $sku ) );

				if ( ! empty( $postIds ) ) {
					$args['post__in'] = $postIds;
				} else {
					$args['s'] = $params['search']['value'];
				}
			} else {
				$args['s'] = $params['search']['value'];
			}
		}
		if ( isset( $params['filter_private'] ) && 1 == $params['filter_private'] ) {
			$args['post_status'] = array( 'publish', 'private' );
		}
		if ( isset( $params['show_variations'] ) && 1 == $params['show_variations'] ) {
			$args['post_type'] = array( 'product', 'product_variation' );
			if ( ! empty( $filterCategory ) ) {
				$parents    = new WP_Query( array(
					'posts_per_page'   => - 1,
					'post_type'        => 'product',
					'suppress_filters' => true,
					'post_status'      => array( 'publish' ),
					'fields'           => 'ids',
					'tax_query'        => array(
						array(
							'taxonomy'         => 'product_cat',
							'field'            => 'id',
							'terms'            => $filterCategory,
							'include_children' => true
						)
					)
				) );
				$existsPost = $parents->have_posts();
				if ( ! empty( $existsPost ) ) {
					$list                     = implode( ',', $parents->posts );
					$args['suppress_filters'] = false;
					add_filter( 'posts_where', function ( $where, $query ) use ( $filterCategory, $list ) {
						remove_filter( current_filter(), __FUNCTION__ );
						global $wpdb;
						$old   = $wpdb->prefix . 'term_relationships.term_taxonomy_id IN (' . $filterCategory . ')';
						$new   = '(' . $wpdb->prefix . 'term_relationships.term_taxonomy_id IN (' . $filterCategory . ') OR ' . $wpdb->prefix . 'posts.post_parent IN (' . $list . '))';
						$where = str_replace( $old, $new, $where );

						return $where;
					}, 10, 2 );
				}
			}
		}

		return $args;
	}

	public function getSearchProducts( $params ) {
		$dataArr       = array();
		$args          = array(
			'posts_per_page'   => 10,
			'post_type'        => 'product',
			'order'            => 'DESC',
			'suppress_filters' => true,
			'post_status'      => array( 'publish' ),
			'offset'           => ! empty( $params['start'] ) ? $params['start'] : '0'
		);
		$filterInTable = isset( $params['filter_in_table'] ) ? $params['filter_in_table'] : '';
		$ids           = isset( $params['productids'] ) ? explode( ',', $params['productids'] ) : array();
		if ( count( $ids ) > 0 && ! empty( $filterInTable ) ) {
			$args[ 'no' == $filterInTable ? 'post__not_in' : 'post__in' ] = $ids;
		}
		$args = $this->getSearchProductsFilters( $args, $params );

		if ( ! empty( $params['order']['0']['column'] ) && ! empty( $params['order']['0']['dir'] ) ) {
			switch ( $params['order']['0']['column'] ) {
				//3 - title column
				case 3:
					$args['orderby'] = 'title';
					break;
				//6 - sku
				case 6:
					$args['meta_key'] = '_sku';
					$args['orderby']  = 'meta_value';
					break;
				//7 - stock column
				case 7:
					$args['meta_key'] = '_stock_status';
					$args['orderby']  = 'meta_value';
					break;
				//8 - price column
				case 8:
					$args['meta_key'] = '_price';
					$args['orderby']  = 'meta_value_num';
					break;
				//9 - date column
				case 9:
					$args['orderby'] = 'date';
					break;
			}
			$args['order'] = $params['order']['0']['dir'];
		}
		$stockNames = wc_get_product_stock_status_options();

		$products = new WP_Query( $args );

		$filterAttribute        = isset( $params['filter_attribute'] ) ? $params['filter_attribute'] : 0;
		$filterAttributeExactly = isset( $params['filter_attribute_exactly'] ) ? $params['filter_attribute_exactly'] : '';

		if ( empty( $filterAttribute ) ) {
			$filterAttributeExactly = '';
		} else {
			$slug = wc_get_attribute( $filterAttribute )->slug;
			if ( empty( $slug ) ) {
				$term          = get_term( $filterAttribute );
				$attributeSlug = $term->taxonomy;
			} else {
				$attributeExactlyParent = true;
			}
		}

		$filtered = false;

		foreach ( $products->posts as $product ) {
			$id           = $product->ID;
			$thumbnailSrc = get_the_post_thumbnail( $id, array( 50, 50 ) );
			$continue     = true;
			$_product     = wc_get_product( $id );
			if ( ! empty( $filterAttributeExactly ) ) {
				$continue       = true;
				$attributesList = $_product->get_attributes();
				foreach ( $attributesList as $attribute ) {
					if ( ( $attribute['name'] == $attributeSlug ) && ( count( $attribute['options'] ) > 1 ) ) {
						$continue = false;
					}
				}
				if ( ( ! $continue ) || ( ! empty( $attributeExactlyParent ) && $attributeExactlyParent && count( $attributesList ) > 1 ) ) {
					$filtered = true;
					continue;
				}
			}

			$attributes      = '';
			$attributesList2 = $_product->get_attributes();
			foreach ( $attributesList2 as $attribute ) {
				if ( ! empty( $attribute['id'] ) ) {
					$attr  = wc_get_attribute( $attribute['id'] );
					$title = is_null( $attr ) ? $attribute['name'] : $attr->name;
					$terms = is_null( $attr ) ? $attribute->get_options() : wc_get_product_terms( $id, $attribute['name'], array( 'fields' => 'names' ) );
					if ( is_array( $terms ) && count( $terms ) > 0 ) {
						$title .= ' : ';
						foreach ( $terms as $key => $term ) {
							$title .= $term;
							if ( ! empty( $terms[ $key + 1 ] ) ) {
								$title .= ', ';
							}
						}
						$attributes .= $title;
						$attributes .= '<br>';
					}
				}
			}

			$price = $_product->get_price_html();
			$date  = $product->post_date;
			if ( 'product_variation' == $_product->post_type ) {
				$existVariations = true;
				$parentId        = $_product->get_parent_id();
				if ( ! isset( $parents[ $parentId ] ) ) {
					$parents[ $parentId ] = array(
						'thumbnail'  => get_the_post_thumbnail( $parentId, array( 50, 50 ) ),
						'categories' => get_the_term_list( $parentId, 'product_cat', '', ', ', '' )
					);
				}
				if ( empty( $thumbnailSrc ) ) {
					$thumbnailSrc = $parents[ $parentId ]['thumbnail'];
				}
				$categories = $parents[ $parentId ]['categories'];
				$variation  = implode( ', ', $_product->get_attributes() );
			} else {
				$categories = get_the_term_list( $id, 'product_cat', '', ', ', '' );
				$variation  = '';
			}
			$categories = is_admin() ? str_ireplace( '<a', '<a target="_blank"', $categories ) : $categories;

			$dataArr[] = array(
				'id'            => $id,
				'in_table'      => in_array( $id, $ids ),
				'product_title' => $product->post_title,
				'thumbnail'     => $thumbnailSrc,
				'categories'    => $categories,
				'sku'           => $_product->get_sku(),
				'stock'         => $stockNames[ $_product->get_stock_status() ],
				'price'         => $price,
				'date'          => $date,
				'variation'     => $variation,
				'attributes'    => $attributes,
			);
		}

		$filtered = $filtered ? count( $dataArr ) : $products->found_posts;

		$data   = $this->generateTableSearchData( $dataArr );
		$return = array(
			'draw'            => 0,
			'recordsTotal'    => $products->found_posts,
			'recordsFiltered' => $filtered,
			'data'            => $data
		);

		return $return;
	}

	public function setOrderColumns( $orders, $backend = true ) {
		$columns = array();

		if ( ! $backend ) {
			//If we use stripslashes, then we remove the special characters of the encoding - as a result,
			//sometimes on frontend we get incorrect text for json_decode (and as a result, an incorrect column name send to frontend data-settings) if we use Cyrillic or diacritical symbols,
			//therefore we need to make json_decode without stripslashes and take the correct column name from there
			$ordersPrepare = json_decode( $orders, true );
			$nameList      = array();
			if ( ! empty( $ordersPrepare ) ) {
				foreach ( $ordersPrepare as $key => $ord ) {
					$nameList[ $key ]['display_name']  = ! empty( $ord['display_name'] ) ? $ord['display_name'] : '';
					$nameList[ $key ]['original_name'] = ! empty( $ord['original_name'] ) ? $ord['original_name'] : '';
				};
			}
		}

		if ( false !== $orders && ! empty( $orders ) ) {
			$orders         = json_decode( stripslashes( $orders ), true );
			$enabledColumns = $this->getModel( 'columns' )->enabledColumns;
			foreach ( $orders as $key => $column ) {
				$fullSlug = $column['slug'];
				$subDelim = strpos( $fullSlug, '-' );
				if ( ! $backend && ! empty( $nameList[ $key ] ) ) {
					//Insert the correct column name
					$column['display_name']  = $nameList[ $key ]['display_name'];
					$column['original_name'] = $nameList[ $key ]['original_name'];
				}
				if ( $subDelim > 0 ) {
					$column['main_slug'] = substr( $fullSlug, 0, $subDelim );
					$sub_slug            = substr( $fullSlug, $subDelim + 1 );
					$column['sub_slug']  = 'attribute' == $column['main_slug'] ? wc_attribute_taxonomy_name_by_id( (int) $sub_slug ) : $sub_slug;
				} else {
					$column['main_slug'] = $fullSlug;
				}
				if ( in_array( $column['main_slug'], $enabledColumns ) ) {
					$columns[] = $column;
				}
			}
		}
		$this->orderColumns = $columns;
	}

	public function addHiddenFilterQuery( $query ) {
		$hidden_term = get_term_by( 'name', 'exclude-from-catalog', 'product_visibility' );
		if ( $hidden_term ) {
			$query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => array( $hidden_term->term_taxonomy_id ),
				'operator' => 'NOT IN'
			);
		}

		return $query;
	}

	/**
	 * Retrieve product content for a frontend table view.
	 *
	 * @param int $id
	 * @param array $tableSettings
	 *
	 * @return string
	 */
	public function getProductContentFrontend( $id, $tableSettings ) {
		if ( empty( $id ) ) {
			return false;
		}

		$settings = $this->getTableSetting( $tableSettings, 'settings', array() );

		$order = DispatcherWtbp::applyFilters( 'addHiddenColumns', $this->getTableSetting( $settings, 'order', false ), $settings );
		$this->setOrderColumns( $order, false );

		$dataArr = array();
		$isSsp   = FrameWtbp::_()->isPro() && $this->getTableSetting( $settings, 'pagination', false ) && $this->getTableSetting( $settings, 'pagination_ssp', false );
		if ( ! $isSsp ) {
			if ( $this->getTableSetting( $settings, 'user_products', false ) ) {
				$productIds = $this->getUserProducts();
			} else {
				if ( ( $this->getTableSetting( $settings, 'auto_categories_enable', false ) && 'all' === $this->getTableSetting( $settings, 'auto_categories_list', '' ) ) || ( $this->getTableSetting( $settings, 'auto_variations_enable', false ) && 'all' === $this->getTableSetting( $settings, 'auto_variations_list', '' ) ) ) {
					$productIds = false;
				} else {
					$productIds = $this->getTableSetting( $settings, 'productids', false );
					$productIds = explode( ',', $productIds );
					if ( ! empty( $productIds ) && ! is_array( $productIds ) ) {
						$productIds = array( $productIds );
					}
				}
			}
			$dataArr = $this->getProductContent( array( 'in' => $productIds, 'not' => false ), $tableSettings, true );

			wp_reset_postdata();
		}
		$html = $this->generateTableHtml( $dataArr, true, $settings );

		return $html;
	}

	/**
	 * Retrieve product content for a backend table view.
	 *
	 * @param array $params
	 * @param bool $preview
	 * @param bool|array $settings
	 *
	 * @return array
	 */
	public function getProductContentBackend( $params, $preview = false, $settings = false ) {
		$this->prewiewTab = ! empty( $params['prewiewTab'] ) ? $params['prewiewTab'] : '';
		$productIds       = $this->calcProductIds( $params );
		if ( empty( $params['tableid'] ) || empty( $productIds ) ) {
			return false;
		}
		$tableId = $params['tableid'];

		if ( false == $settings ) {
			$table         = $this->getModel( 'wootablepress' )->getById( $tableId );
			$tableSettings = $this->getModule()->unserialize( $table['setting_data'] );
		} else {
			$settings['settings']['productids'] = $productIds['in'];
			$tableSettings                      = $settings;
		}
		$tableSettings['start']  = isset( $params['start'] ) ? $params['start'] : 0;
		$tableSettings['length'] = isset( $params['length'] ) ? $params['length'] : - 1;
		$returnIds               = isset( $params['returnIds'] ) && 1 == $params['returnIds'];

		$settings = $this->getTableSetting( $tableSettings, 'settings', array() );
		$order    = isset( $params['order'] ) ? $params['order'] : false;
		if ( $preview ) {
			$order = DispatcherWtbp::applyFilters( 'addHiddenColumns', $order, $settings );
		}

		$this->setOrderColumns( $order, true );
		$tableSettings['settings']['order'] = json_encode( $this->orderColumns );

		if ( isset( $params['sortCustom'] ) ) {
			$tableSettings['settings']['sorting_custom'] = $params['sortCustom'];
		}

		$isSsp = FrameWtbp::_()->isPro() && $this->getTableSetting( $settings, 'pagination', false ) && $this->getTableSetting( $settings, 'pagination_ssp', false );
		if ( ! $preview || ! $isSsp ) {
			if ( $preview && ( ( $this->getTableSetting( $settings, 'auto_categories_enable', false ) && 'all' === $this->getTableSetting( $settings, 'auto_categories_list', '' ) ) || ( $this->getTableSetting( $settings, 'auto_variations_enable', false ) && 'all' === $this->getTableSetting( $settings, 'auto_variations_list', '' ) ) ) ) {
				$productIds = array( 'in' => false, 'not' => false );
			}
			$dataArr = $this->getProductContent( $productIds, $tableSettings, $preview );
		} else {
			$dataArr = array();
		}

		$total    = isset( $dataArr['total'] ) ? $dataArr['total'] : 0;
		$idsExist = isset( $dataArr['idsExist'] ) && ! empty( $dataArr['idsExist'] ) ? $dataArr['idsExist'] : '';
		unset( $dataArr['total'] );
		unset( $dataArr['idsExist'] );
		$html = $this->generateTableHtml( $dataArr, $preview, $settings );

		$return             = array();
		$return['html']     = $html;
		$return['filter']   = DispatcherWtbp::applyFilters( 'getTableFilters', '', $tableId, $tableSettings );
		$return['settings'] = $tableSettings;
		$return['css']      = $preview ? $this->getCustomCss( $tableSettings, 'wtbpPreviewTable', false ) : '';
		if ( $returnIds ) {
			$return['ids'] = $idsExist;
		}
		$return['total'] = $total;

		if ( $total > 1000 && ! $isSsp ) {
			$return['notices'][] = '<div class="error notice">' . esc_html__( 'You need to enable pagination and server-side processing options or reduce the number of selected products, because the products table may not work at the front!', 'woo-product-tables' ) . '</div>';
		}

		return $return;
	}

	/**
	 * Retrieve product content for individual product pages of pagination and lazy load.
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public function getProductPage( $params ) {
		if ( empty( $params['id'] ) ) {
			return false;
		}
		$tableId  = $params['id'];
		$frontend = empty( $params['admin'] );

		$settings = false;
		if ( ! empty( $params['settings'] ) ) {
			parse_str( $params['settings'], $settings );
			unset( $params['settings'] );
		}
		if ( false == $settings ) {
			$table         = $this->getModel( 'wootablepress' )->getById( $tableId );
			$tableSettings = $this->getModule()->unserialize( $table['setting_data'] );
		} else {
			$tableSettings = $settings;
		}
		$settings = $this->getTableSetting( $tableSettings, 'settings', array() );

		$order      = isset( $params['orders'] ) ? $params['orders'] : $this->getTableSetting( $settings, 'order', false );
		$isSsp      = FrameWtbp::_()->isPro() && $this->getTableSetting( $settings, 'pagination', false ) && $this->getTableSetting( $settings, 'pagination_ssp', false );
		$isLazyLoad = $this->getTableSetting( $settings, 'lazy_load', false );

		if ( $isLazyLoad && ! $isSsp ) {
			$order = DispatcherWtbp::applyFilters( 'addHiddenColumns', $order, $settings );
		}

		$this->setOrderColumns( $order, ! $frontend );

		if ( ! $frontend ) {
			$orders = $this->orderColumns;
			if ( ! empty( $params['sortCol'] ) && ! empty( $params['order']['0']['dir'] ) ) {
				$slug = $params['sortCol'];
				foreach ( $orders as $column ) {
					if ( $column['slug'] == $slug ) {
						$params['sortCol'] = $column;
						break;
					}
				}
			}
		}
		if ( $this->getTableSetting( $settings, 'user_products', false ) ) {
			$productIds = $this->getUserProducts();
		} else {
			if ( ( $this->getTableSetting( $settings, 'auto_categories_enable', false ) && 'all' === $this->getTableSetting( $settings, 'auto_categories_list', '' ) ) || ( $this->getTableSetting( $settings, 'auto_variations_enable', false ) && 'all' === $this->getTableSetting( $settings, 'auto_variations_list', '' ) ) ) {
				$productIds = false;
			} else {
				$productIds = isset( $params['productids'] ) ? $params['productids'] : $this->getTableSetting( $settings, 'productids', false );
				$productIds = explode( ',', $productIds );
				if ( ! empty( $productIds ) && ! is_array( $productIds ) ) {
					$productIds = array( $productIds );
				}
			}
		}

		if ( isset( $params['sortCustom'] ) ) {
			$tableSettings['settings']['sorting_custom'] = $params['sortCustom'];
		}

		$dataArr = $this->getProductContent( array( 'in' => $productIds, 'not' => false ), $tableSettings, $frontend, $params );
		$jscript = '';
		if ( isset( $dataArr['jscript'] ) ) {
			$jscript = $dataArr['jscript'];
			unset($dataArr['jscript']);
		}
		
		$html = $this->generateTableHtml( $dataArr, $frontend, $settings, false );

		$result = array( 'html' => $html, 'total' => $params['total'], 'filtered' => $params['filtered'], 'jscript' => $jscript );
		if ( isset( $params['idsExist'] ) ) {
			$result['ids'] = $params['idsExist'];
		}
		
		return $result;
	}

	public function calcProductIds( $params, $getList = false ) {
		$productIdsExits = ! empty( $params['productIdExist'] ) ? $params['productIdExist'] : array();
		if ( is_string( $productIdsExits ) ) {
			$productIdsExits = explode( ',', $productIdsExits );
		}
		$productIdsSelected = ! empty( $params['productIdSelected'] ) ? $params['productIdSelected'] : array();
		$productIdExcluded  = ! empty( $params['productIdExcluded'] ) ? $params['productIdExcluded'] : array();
		$productFilters     = ! empty( $params['filters'] ) ? $params['filters'] : array();

		$filter = $this->getSearchProductsFilters( array(), $productFilters );
		$isAll  = 'all' == $productIdsSelected;

		$productIds = array();
		if ( count( $filter ) > 0 ) {
			$args = array_merge( array(
				'post_type'           => 'product',
				'ignore_sticky_posts' => true,
				'post_status'         => array( 'publish' ),
				'posts_per_page'      => - 1
			), $filter );

			if ( $isAll ) {
				if ( count( $productIdExcluded ) > 0 ) {
					$args['post__not_in'] = $productIdExcluded;
				}
			} else {
				$args['post__in'] = $productIdsSelected;
			}
			$postExist = new WP_Query( $args );

			$products = $productIdsExits;
			foreach ( $postExist->posts as $post ) {
				$products[] = $post->ID;
			}
			$productIds = array( 'in' => array_unique( $products ), 'not' => false );
		} else {
			if ( $isAll ) {
				$filtered   = array_filter( $productIdExcluded,
					function ( $value ) use ( $productIdsExits ) {
						return ! in_array( $value, $productIdsExits );
					}
				);
				$productIds = array( 'in' => false, 'not' => $filtered );
			} else {
				$productIdsExits = DispatcherWtbp::applyFilters( 'filterProductIds', $productIdsExits, $params );
				$productIds      = array( 'in' => array_unique( array_merge( $productIdsExits, $productIdsSelected ) ), 'not' => false );
			}
		}
		if ( $getList ) {
			if ( false == $productIds['not'] ) {
				$ids = $productIds['in'];
			} else {
				$args = array(
					'post_type'           => 'product',
					'ignore_sticky_posts' => true,
					'post_status'         => array( 'publish' ),
					'posts_per_page'      => - 1,
					'post__not_in'        => $productIds['not'],
					'fields'              => 'ids',
				);

				if ( is_array( $productIds['in'] ) ) {
					$args['post__in'] = $productIds['in'];
				}
				$result = new WP_Query( $args );
				$ids    = $result->posts;
			}
			wp_reset_postdata();

			return is_array( $ids ) ? implode( ',', $ids ) : '';
		}

		return $productIds;
	}

	/**
	 * Get product thumbnail link
	 *
	 * @param int $id
	 * @param string $imgSize
	 * @param string $add
	 *
	 * @return string
	 */
	public function getProductThumbnailLink( $id, $imgSize, $add = 'class="wtbpMainImage"', $useProductLink = false ) {
		$link            = '';
		$postThumbnailId = get_post_thumbnail_id( $id );
		if ( $postThumbnailId ) {
			$link = $this->getThumbnailLinkHtml( $id, $postThumbnailId, $imgSize, $add, $useProductLink );
		}

		return $link;
	}

	/**
	 * Get product second thumbnail with link wrapper from gallery first image
	 *
	 * @param object $_product
	 * @param string $imgSize
	 * @param string $add
	 *
	 * @return string
	 */
	public function getProductSecondThumbnailLink( $product, $imgSize, $add = 'class="wtbpMainImage"', $useProductLink = false ) {
		$link = '';

		$gallaryFirstImgId = $this->getProductFirstGalleryImageId( $product );

		if ( $gallaryFirstImgId ) {
			$link = $this->getThumbnailLinkHtml( $product->get_id(), $gallaryFirstImgId, $imgSize, $add, $useProductLink );
		}

		return $link;
	}


	/**
	 * Get product all thumbnail with link wrapper from gallery
	 *
	 * @param object $product
	 * @param string $imgSize
	 * @param string $add
	 *
	 * @return string
	 */
	public function getProductAllThumbnailLink( $product, $imgSize, $add = 'class="wtbpMainImage"', $useProductLink = false ) {
		$link = '';

		$attachment_ids = $product->get_gallery_image_ids();

		if ( $attachment_ids && $product->get_image_id() ) {
			foreach ( $attachment_ids as $attachment_id ) {
				$link .= $this->getThumbnailLinkHtml( $product->get_id(), $attachment_id, $imgSize, $add, $useProductLink );
			}
		}

		return $link;
	}

	/**
	 * Get product second thumbnail with link wrapper from gallery first image
	 *
	 * @param object $_product
	 * @param string $imgSize
	 * @param string $mobileStyles
	 *
	 * @return string
	 */
	public function getProductSecondThumbnail( $product, $imgSize, $mobileStyles ) {
		$thumbnail = '';

		$gallaryFirstImgId = $this->getProductFirstGalleryImageId( $product );

		if ( $gallaryFirstImgId ) {
			$thumbnail = wp_get_attachment_image( $gallaryFirstImgId, $imgSize, false, $mobileStyles );
		}

		return $thumbnail;
	}

	/**
	 * Get gellary first image id
	 *
	 * @param object $product
	 *
	 * @return int|false
	 */
	public function getProductFirstGalleryImageId( $product ) {
		$firstImageId = false;
		$gallaryIds   = $product->get_gallery_image_ids();

		if ( ! empty( $gallaryIds[0] ) ) {
			$firstImageId = $gallaryIds[0];
		}

		return $firstImageId;
	}

	/**
	 * Get thumbnail link html
	 *
	 * @param int $postId
	 * @param int $imgId
	 * @param string $imgSize
	 * @param string $add
	 *
	 * @return string
	 */
	public function getThumbnailLinkHtml( $postId, $imgId, $imgSize, $add, $useProductLink = false ) {
		$link = '';

		$postImg = wp_get_attachment_image( $imgId, $imgSize );
		if ( $useProductLink ) {
			$postImgSrc   = array(
				get_permalink( $postId )
			);
			$dataLightbox = '';
		} else {
			$postImgSrc   = wp_get_attachment_image_src( $imgId, 'full' );
			$dataLightbox = 'data-lightbox="' . esc_attr( $postId ) . '" ';
		}

		if ( ! empty( $postImg ) && ! empty( $postImgSrc[0] ) ) {
			$link = '<a href="' . esc_url( $postImgSrc[0] ) . '" ' . $dataLightbox . $add . '>' . $postImg . '</a>';
		}

		return $link;
	}

	public function getProductContent( $productIds, $tableSettings, $preview = true, &$page = array() ) {
		set_time_limit( 300 );
		$frontend = ! is_admin() || $preview;
		$orders   = $this->orderColumns;
		$settings = isset( $tableSettings['settings'] ) ? $tableSettings['settings'] : array();
		$settings = $this->setVendorId( $settings, $page );
		$isPage   = ! empty( $page );
		$isPro    = FrameWtbp::_()->isPro();

		$postStatuses = array( 'publish' );
		if ( $this->getTableSetting( $settings, 'show_private', false ) || ! $frontend ) {
			$postStatuses[] = 'private';
		}
		$productIds = DispatcherWtbp::applyFilters( 'dynamicProductsFiltering', $productIds, $settings );
		$productIds = DispatcherWtbp::applyFilters( 'filteringProductIds', $productIds );

		$postTypes = array( 'product' );
		if ( false !== $productIds['in'] || false !== $productIds['not'] ) {
			$postTypes[] = 'product_variation';
		} else {
			if ( $this->getTableSetting( $settings, 'auto_variations_enable', false ) && $this->getTableSetting( $settings, 'auto_variations_list', '' ) == 'all' ) {
				if ( $this->getTableSetting( $settings, 'auto_categories_enable', false ) && $this->getTableSetting( $settings, 'auto_categories_list', '' ) == 'all' ) {
					$postTypes[] = 'product_variation';
				} else {
					$postTypes = array( 'product_variation' );
				}
			}
		}
		$args = array(
			'post_type'           => $postTypes,
			'ignore_sticky_posts' => true,
			'post_status'         => $postStatuses,
			'posts_per_page'      => - 1,
			'tax_query'           => array()
		);

		$showProductsByVendor = $this->getTableSetting( $settings, 'show_products_by_vendor', false );
		$productsVendor       = $this->getTableSetting( $settings, 'products_vendor', 0 );
		if ( $isPro && $showProductsByVendor && $productsVendor ) {
			$args['post_type'] = 'product';
			$args['author']    = $productsVendor;
		} elseif ( is_array( $productIds['in'] ) ) {
			$args['post__in'] = $productIds['in'];
		} elseif ( is_array( $productIds['not'] ) ) {
			$args['post__not_in'] = $productIds['not'];
			$args['post_type']    = 'product';
			$args['post_status']  = 'publish';
		}

		if ( ! empty( $settings['sorting_custom'] ) ) {
			remove_all_filters( 'posts_orderby' );
			if ( empty( $settings['pre_sorting'] ) ) {
				$args['orderby'] = 'post__in';
			} else {
				$desc = empty( $settings['pre_sorting_desc'] ) ? 'ASC' : 'DESC';
				switch ( $settings['pre_sorting'] ) {
					case 'title':
						$args['orderby'] = 'title';
						$args['order']   = $desc;
						break;
					case 'rand':
						$args['orderby'] = 'rand';
						break;
					case 'date':
						$args['orderby'] = 'date ID';
						$args['order']   = $desc;
						break;
					case 'price':
						$args['meta_key'] = '_price';
						$args['orderby']  = 'meta_value_num';
						$args['order']    = $desc;
						break;
					case 'popularity':
						$args['meta_key'] = 'total_sales';
						$args['orderby']  = 'meta_value_num';
						$args['order']    = $desc;
						break;
					case 'rating':
						$args['meta_key'] = '_wc_average_rating';
						$args['orderby']  = array(
							'meta_value_num' => $desc,
							'ID'             => 'ASC',
						);
						break;
					case 'menu_order':
						$args['orderby'] = 'menu_order';
						$args['order']   = $desc;
						break;
				}
			}
		}

		$multyAddToCart     = $this->getTableSetting( $settings, 'multiple_add_cart', false );
		$multyAddPosition   = $this->getTableSetting( $settings, 'multiple_add_cart_position', 'first' );
		$showVarImages      = $this->getTableSetting( $settings, 'show_variation_image', false );
		$showVarDescription = $this->getTableSetting( $settings, 'show_variation_description', false );

		if ( ! $isPage && ! $frontend ) {
			$args['fields'] = 'ids';
			$idsExist       = new WP_Query( $args );
			$idsTmp         = ! empty( $idsExist->posts ) ? implode( ',', $idsExist->posts ) : '';
		}

		if ( $isPro ) {
			$args = DispatcherWtbp::applyFilters( 'setLazyLoadQueryFilters', $args, $settings, $page );
		}

		if ( $isPage ) {
			if ( $isPro ) {
				$args = DispatcherWtbp::applyFilters( 'setSSPQueryFilters', $settings, $args, $page );
			} else {
				$args = $this->setAdminSSPQueryFilters( $settings, $args, $page );
			}
			if ( ! empty( $page['returnIds'] ) ) {
				$l = $args['posts_per_page'];
				$o = $args['offset'];
				unset( $args['posts_per_page'], $args['offset'] );
				$args['fields']         = 'ids';
				$args['posts_per_page'] = - 1;
				$args['offset']         = 0;
				$idsExist               = new WP_Query( $args );
				$page['idsExist']       = ! empty( $idsExist->posts ) ? implode( ',', $idsExist->posts ) : '';
				unset( $idsExist );
				$args['posts_per_page'] = $l;
				$args['offset']         = $o;
			}
			list($jscript, $args) = DispatcherWtbp::applyFilters( 'renderJSforWPF', array('', $args), $page );
		} elseif ( ! $frontend && isset( $tableSettings['start'] ) && isset( $tableSettings['length'] ) ) {
			$args['posts_per_page'] = $tableSettings['length'];
			$args['offset']         = $tableSettings['start'];
		}
		$args['fields'] = 'all';
		if ( ! empty( $settings['hide_out_of_stock'] ) ) {
			$args['meta_query'][] = array(
				'key'     => '_stock_status',
				'value'   => 'outofstock',
				'compare' => 'NOT LIKE'
			);
		}

		if ( class_exists( 'CWG_Product_Visibility_Main' ) && !is_array($productIds['in']) ) {
			$args = $this->getModule()->beforeMainQueryCwgCheckTerms( $args );
		}

		$dataExist = new WP_Query( $args );
		DispatcherWtbp::doAction( 'removeSSPQueryFilters' );
				
		$postExist = $dataExist->posts;
		$imgSize   = ! empty( $settings['thumbnail_size'] ) ? $settings['thumbnail_size'] : 'thumbnail';

		if ( $frontend ) {
			if ( 'set_size' == $imgSize ) {
				$imgSize = array(
					( ! empty( $settings['thumbnail_width'] ) ? $settings['thumbnail_width'] : 0 ),
					( ! empty( $settings['thumbnail_height'] ) ? $settings['thumbnail_height'] : 0 )
				);
			}
			DispatcherWtbp::applyFilters( 'customizeCartButton', $settings );
		}

		$hideQuantityInput = ! empty( $settings['hide_quantity_input'] ) ? $settings['hide_quantity_input'] : false;

		$stockNames                 = wc_get_product_stock_status_options();
		$replacingTheTextOutOfStock = esc_html__( $this->getTableSetting( $settings, 'replacing_the_text_out_of_stock', false ) );
		if ( $replacingTheTextOutOfStock ) {
			$stockNames['outofstock'] = $replacingTheTextOutOfStock;
		}
		$dataArr = array();
		if ( ! $isPage && ! $frontend ) {
			$dataArr['total']    = $dataExist->found_posts;
			$dataArr['idsExist'] = $idsTmp;
		}
		if ($isPage && !empty($jscript)) {
			$dataArr['jscript'] = $jscript;
		}
		$b2bPrice = false;

		foreach ( $orders as $i => $column ) {
			switch ( $column['main_slug'] ) {
				case 'thumbnail':
					$mobileStyles = '';
					if ( isset( $settings['responsive_mode'] ) && 'disable' !== $settings['responsive_mode'] ) {
						if ( wp_is_mobile() || ! empty( $this->prewiewTab ) && 'mobile' == $this->prewiewTab ) {
							$mobileThubmnailWidth  = $this->getTableSetting( $column, 'mobile_thumbnail_size_width', 0 );
							$mobileThubmnailHeight = $this->getTableSetting( $column, 'mobile_thumbnail_size_height', 0 );

							if ( ! empty( $mobileThubmnailWidth ) || ! empty( $mobileThubmnailHeight ) ) {
								if ( empty( $mobileThubmnailWidth ) ) {
									$mobileThubmnailWidth = $mobileThubmnailHeight;
								} elseif ( empty( $mobileThubmnailHeight ) ) {
									$mobileThubmnailHeight = $mobileThubmnailWidth;
								}

								$mobileStyles = array( 'class' => esc_attr( 'attachment-' . $mobileThubmnailWidth . 'x' . $mobileThubmnailHeight ) );
								$imgSize      = array(
									$mobileThubmnailWidth,
									$mobileThubmnailHeight
								);
							}
						}
					}

					if ( $isPro ) {
						$thumbnailCartButton   = $this->getTableSetting( $column, 'add_cart_button', false );
						$secondthumbnailActive = $this->getTableSetting( $column, 'display_secont_thumbnail', false );
						$useProductLink        = $this->getTableSetting( $column, 'use_product_link', false );
					}
					break;
				case 'product_title':
					$prodTitleLink                    = ! isset( $column['product_title_link'] ) || ! empty( $column['product_title_link'] );
					$prodTitleLinkBlank               = $this->getTableSetting( $column, 'product_title_link_blank', false );
					$prodTitleQuickView               = $prodTitleLink && $isPro && $this->getTableSetting( $column, 'product_title_link_to', '' ) == 'quick';
					$isStripTitle                     = isset( $column['cut_product_title_text'] ) ? $column['cut_product_title_text'] : true;
					$stripTitleSize                   = ! empty( $column['cut_product_title_text_size'] ) ? $column['cut_product_title_text_size'] : 100;
					$isOnlyParentTitle                = $this->getTableSetting( $column, 'show_only_parent_title_text', false );
					$isShowShortDescriptionBelowTitle = $isPro && $this->getTableSetting( $column, 'product_title_show_short_description', false );
					$favorites                        = $this->getFavorites();
					$isFavorites                      = ( $this->getTableSetting( $column, 'product_favorites', false ) && 0 !== get_current_user_id() );
					break;
				case 'categories':
					$prodCategoryLink      = ! isset( $column['product_category_link'] ) || ! empty( $column['product_category_link'] );
					$prodCategoryLinkBlank = $this->getTableSetting( $column, 'product_category_link_blank', false );
					$categoriesSeparator   = $this->getTableSetting( $column, 'product_category_new_line', false ) ? '<br />' : ', ';
					$isCategoryInnerFilter = $isPro && $this->getTableSetting( $settings, 'filter_category', false ) && $this->getTableSetting( $settings, 'filter_category_inner_table', false );
					$prodCategoryExclude   = $frontend && $isPro && ! $isPage ? $this->getTableSetting( $column, 'product_category_exclude', false ) : false;
					if ( ! empty( $prodCategoryExclude ) ) {
						$prodCategoryExcludeList = explode( ',', $prodCategoryExclude );
						$prodCategoryExclude     = empty( ! $prodCategoryExcludeList );
					}
					$isFilterCategory    = $frontend && $isPro && $this->getTableSetting( $settings, 'filter_category', false );
					$isFilterCatChildren = $isFilterCategory && $this->getTableSetting( $settings, 'filter_category_children', false );
					if ( $isFilterCatChildren ) {
						$catParents = array();
					}
					break;
				case 'product_link':
					$prodLinkUserString = esc_html( ( isset( $column['product_link_text'] ) && '' !== $column['product_link_text'] ) ? $column['product_link_text'] : 'More' );
					$target             = ( isset( $column['target_self'] ) && 1 === $column['target_self'] ) ? '_self' : '_blank';
					break;
				case 'stock':
					$stockMaxQuantity               = $isPro ? $this->getTableSetting( $column, 'stock_max_quantity', false, true ) : false;
					$countSmallQuantity             = $isPro ? $this->getTableSetting( $column, 'count_small_quantity', false, true ) : false;
					$showIcons                      = $this->getTableSetting( $column, 'stock_show_icons', false );
					$showText                       = esc_html( $this->getTableSetting( $column, 'stock_show_text', false ) );
					$showQuantity                   = $this->getTableSetting( $column, 'stock_item_counts', false );
					$showVariationQuantity          = $isPro && $this->getTableSetting( $column, 'stock_item_variation_counts', false );
					$showVariationQuantityAttrNames = $isPro && $this->getTableSetting( $column, 'stock_item_variation_attr_names', false );
					if ( $showQuantity || $showVariationQuantity ) {
						$stockQuantityText = esc_html( $this->getTableSetting( $settings, 'stock_quantity_text', false ) );
					}
					if ( $showIcons ) {
						$stockIcons = array( 'instock' => 'smile-o', 'outofstock' => 'frown-o', 'onbackorder' => 'meh-o' );
					}
					if ( ! $showIcons && ! $showQuantity && ! $showVariationQuantity ) {
						$showText = true;
					}
					$isFilterStock    = $frontend && $isPro && $this->getTableSetting( $settings, 'filter_stock', false );
					
					break;
				case 'description':
					$stripDescription        = isset( $column['cut_description_text'] ) ? $column['cut_description_text'] : true;
					$stripDescriptionSize    = ! empty( $column['cut_description_text_size'] ) ? $column['cut_description_text_size'] : 100;
					$displayDescriptionPopup = $isPro ? $this->getTableSetting( $column, 'description_popup', false ) : false;
					break;
				case 'short_description':
					$stripDescriptionShort        = isset( $column['cut_short_description_text'] ) ? $column['cut_short_description_text'] : false;
					$stripSizeShort               = ! empty( $column['cut_short_description_text_size'] ) ? $column['cut_short_description_text_size'] : 100;
					$displayShortDescriptionPopup = $isPro ? $this->getTableSetting( $column, 'short_description_popup', false ) : false;
					$isDoShortcodes               = $this->getTableSetting( $column, 'is_do_shortcodes', false ) ? true : false;
					break;
				case 'downloads':
					$prodDownloadsLinkBlank = $this->getTableSetting( $column, 'product_downloads_link_blank', false );
					break;
				case 'add_to_cart':
					$hideVariationAttr                 = $this->getTableSetting( $column, 'add_to_cart_hide_variation_attribute', false );
					$buttonForVariation                = $isPro && $this->getTableSetting( $column, 'add_to_cart_variation_buttons', false );
					$isPopupForVariation               = $isPro && ! $buttonForVariation && $this->getTableSetting( $column, 'add_to_cart_popup', false );
					$popupForVariationBtnText          = $isPopupForVariation ? $this->getTableSetting( $column, 'add_to_cart_popup_btn_text', __( 'Select options', 'woocommerce' ) ) : __( 'Select options', 'woocommerce' );
					$popupForVariationShortDescription = $this->getTableSetting( $column, 'add_to_cart_popup_short_description', '0' );
					$naturalOrder                      = $this->getTableSetting( $column, 'natural_order', false );
					$customOrder                       = $this->getTableSetting( $column, 'custom_order', false );
					$withNote                          = $this->getTableSetting( $column, 'add_to_cart_note', false );
					if ($customOrder) {
						$termsCustomOrders = array();
					}
					$barnQuantity = class_exists('Barn2\Plugin\WC_Quantity_Manager\Util\Quantity');
					$isOutstockNotify = class_exists( 'WC_BIS_Product' );
					if ( $isOutstockNotify ) {
						$outstockNotify = new WC_BIS_Product();
						WC_BIS()->templates->enqueue_scripts();
					}
					break;
				case 'sku':
					$changeSkuForVariation = $isPro && $this->getTableSetting( $column, 'change_sku_for_variation', false ) ? true : false;
					break;
				case 'price':
					$isFilterPrices = $frontend && $isPro && $this->getTableSetting( $settings, 'filter_price', false );
					/**
					 * Plugin compatibility
					 *
					 * @link https://woocommerce.com/products/b2b-for-woocommerce/
					 */
					if ( in_array( 'b2b/addify_b2b.php', get_option( 'active_plugins' ), true ) ) {
						include WP_PLUGIN_DIR . '/b2b/additional_classes/class_afb2b_role_based_pricing_front.php';
						$b2bPrice = new Front_Class_Addify_Customer_And_Role_Pricing();
						if ( method_exists( $b2bPrice, 'csp_load' ) ) {
							$b2bPrice->csp_load();
						}
					}
					// compatibility with plugin Germanized for WooCommerce
					$isGzd = function_exists('wc_gzd_get_product');
					// need global variable for product to ensure compatibility with the WooCommerce Currency Switcher by WBW
					global $wtbpProduct;
					$wtbpProduct = null;
					break;
				case 'sale_dates':
					$dateFormat = $this->getTableSetting( $settings, 'date_formats', 'Y-d-m' );
					break;
				default:
			}
		}

		$varPriceColumn = $this->getTableSetting( $settings, 'var_price_column', false );
		if ( $varPriceColumn ) {
			$priceFound = false;
			foreach ( $orders as $column ) {
				if ( 'price' == $column['main_slug'] ) {
					$priceFound = true;
					break;
				}
			}
			if ( ! $priceFound ) {
				$varPriceColumn = false;
			}
		}

		$parents    = array();
		$taxonomies = array();
		DispatcherWtbp::doAction('beforeProductsTableLoop');

		foreach ( $postExist as $post ) {
			if ( 'product' != $post->post_type && 'product_variation' != $post->post_type ) {
				continue;
			}

			$id                    = $post->ID;
			$postContent           = $post->post_content;
			$postDate              = $post->post_date;
			$_product              = wc_get_product( $id );
			DispatcherWtbp::doAction('beforeProductPrint', $_product);
			$productType           = $_product->get_type();
			$isVariable            = 'variable' == $productType || 'variable-subscription' == $productType;
			$this->loopProductType = $productType;
			$isVariation           = 'product_variation' == $_product->post_type;
			$parentId              = $isVariation ? $_product->get_parent_id() : 0;
			if ( ! empty( $parentId ) ) {
				$parents[ $parentId ] = array();
				if ( ! empty( $isOnlyParentTitle ) && $isOnlyParentTitle ) {
					$productParent = wc_get_product( $_product->get_parent_id() );
					$postTitleFull = $productParent->get_title();
				} else {
					$postTitleFull = $post->post_title;
				}
			} else {
				$postTitleFull = $post->post_title;
			}

			$mainId = empty( $parentId ) ? $id : $parentId;

			$sku  = $_product->get_sku();
			$data = array( 'id' => $id );

			if ( ! empty( $settings['responcive_child_hide'] ) && ! empty( $settings['responsive_mode'] ) ) {
				if (
					'responsive' == $settings['responsive_mode'] &&
					'add_column' == $settings['responcive_child_hide'] ||
					'disable' == $settings['responcive_child_hide'] ) {
					$data['hide_responcive'] = '';
				}
			}

			foreach ( $orders as $column ) {
				switch ( $column['main_slug'] ) {
					case 'thumbnail':
						$useProductLink = $isPro && $useProductLink;
						$value          = $frontend ? $this->getProductThumbnailLink( $id, $imgSize, 'class="wtbpMainImage"', $useProductLink ) : get_the_post_thumbnail( $id, 'thumbnail', $mobileStyles );

						// case when variation does not has image than replace with product parent image
						if ( $isVariation && empty( $value ) ) {
							$value               = $frontend ? $this->getProductThumbnailLink( $parentId, $imgSize, 'class="wtbpMainImage"', $useProductLink ) : get_the_post_thumbnail( $parentId, 'thumbnail', $mobileStyles );
							$parent_image_active = true;
						}

						if ( $isPro ) {
							if ( $secondthumbnailActive ) {
								// case when variation does not has image than add second image to parent product image
								if ( $isVariation && ! empty( $parent_image_active ) ) {
									$parent_product = wc_get_product( $parentId );
									$value         .= $frontend ? $this->getProductSecondThumbnailLink( $parent_product, $imgSize, 'class="wtbpMainImage"', $useProductLink ) : $this->getProductSecondThumbnail( $parent_product, 'thumbnail', $mobileStyles );
									// all cases exept variation with image
								} else {
									$value .= $frontend ? $this->getProductSecondThumbnailLink( $_product, $imgSize, 'class="wtbpMainImage"', $useProductLink ) : $this->getProductSecondThumbnail( $_product, 'thumbnail', $mobileStyles );
								}
								$value = '<div class="wtbpTableThumbnailWrapper">' . $value . '</div>';
							}

							if ( $thumbnailCartButton ) {
								if ( $_product->get_stock_status() == 'outofstock' ) {
									if ( $frontend && $multyAddToCart ) {
										$data['check_multy'] = '';
									}
									$value .= '<div class="wtbpOutOfStockCart">' . $stockNames['outofstock'] . '</div>';
								} else {
									if ( $multyAddToCart ) {
										$data['check_multy'] =
											'<input type="checkbox" ' .
											' class="wtbpAddMulty" ' .
											' value="' . esc_attr( $id ) .
											'" data-quantity="1" ' .
											' data-variation_id="0" ' .
											' data-position="' . esc_attr( $multyAddPosition ) . '" ' .
											( 'grouped' !== $productType && 'external' !== $productType ? '' : ' disabled' ) .
											'>';
									}
									$addToCartButton = do_shortcode( '[add_to_cart id="' . $id . '" class="" style="" show_price="false" sku ="' . $sku . '"]' );
									$value          .= '<div class="wtbpAddToCartWrapper">' . $addToCartButton . '</div>';
								}
							}
						}

						if ( $frontend ) {
							$allThumbnail = $this->getProductAllThumbnailLink( $_product, $imgSize, 'class="wtbpMainImage wtbpHidden"', $useProductLink );
							if ( $allThumbnail ) {
								$value = '<div>' . $value . '<div class="wtbpGalleryImage">' . $allThumbnail . '</div></div>';
							}
						}

						$data['thumbnail'] = $value;
						break;
					case 'product_title':
						$postTitleFullNotag = strip_tags( $postTitleFull );
						$postTitle          = ! $isStripTitle ? $postTitleFullNotag
							: $this->truncateWordwrap( $postTitleFullNotag, $stripTitleSize, '...' );

						if ( $prodTitleLink ) {
							if ( $prodTitleQuickView ) {
								$data['product_title'] = '<a href="#" class="yith-wcqv-button" data-product_id="' . $id . '">' . $postTitle . '</a>';
							} else {
								$url                   = get_permalink( $id );
								$data['product_title'] =
									'<a ' .
									( $isStripTitle ? ' class="woobewoo-tooltip" title="' . esc_attr( $postTitleFullNotag ) . '" ' : '' )
									. ' href="' . esc_url( $url ) . '"'
									. ( ! $frontend || $prodTitleLinkBlank ? ' target="_blank"' : '' ) .
									'>' . esc_html( $postTitle ) . '</a>';
							}
						} else {
							if ( $isStripTitle ) {
								$data['product_title'] =
									'<span class="woobewoo-tooltip" title="'
									. esc_attr( $postTitleFullNotag ) . '">'
									. $postTitle . '</span>';
							} else {
								$data['product_title'] = $postTitle;
							}
						}

						$_tmpl                 = str_replace(
							$postTitleFullNotag,
							'%s',
							$postTitleFull
						);
						$data['product_title'] = sprintf( $_tmpl, $data['product_title'] );

						if ( $isShowShortDescriptionBelowTitle ) {
							$postTitleShortDescr    = $_product->get_short_description();
							$postTitleShortDescr    = apply_filters( 'the_content', $postTitleShortDescr );
							$data['product_title'] .= '<div class="wtbpProductTitleDescription">' . $postTitleShortDescr . '</div>';
						}
						if ( $isFavorites ) {
							$active                 = ( key_exists( $id, $favorites ) && '-1' !== $favorites[ $id ]['from_order'] ) ? 'active' : '';
							$data['product_title'] .= "<div class=\"wtbp_favorites {$active}\" data-product-id=\"{$id}\"></div>";
						}
						break;
					case 'featured':
						$featured = '';
						if ( $_product->get_featured() ) {
							$showAs = $isPro ? $this->getTableSetting( $column, 'featured_show_as', 'text' ) : 'text';
							if ( 'icon' == $showAs ) {
								$featured = '<i class="fa fa-fw fa-star"></i>';
							} elseif ( 'image' == $showAs ) {
								$featured = '<img class="wtbpFeaturedImage" src="' . esc_url( $this->getTableSetting( $column, 'featured_image_path', WTBP_IMG_PATH . 'default.png' ) ) . '">';
							} else {
								$featured = esc_html__( 'Featured', 'woocommerce' );
							}
						}
						$data['featured'] = $featured;
						break;
					case 'sku':
						$variations = $isVariable && $changeSkuForVariation ? $_product->get_available_variations() : array();
						$skuHtml    = '';
						if ( ! empty( $variations ) ) {
							$skuHtml = '<span data-default>' . $sku . '</span>';
							foreach ( $variations as $variationIterator => $variation ) {
								$variationObj = new WC_Product_variation( $variation['variation_id'] );
								$skuHtml     .= '<span class="wtbpHidden" data-variation-id="' . esc_attr( $variation['variation_id'] ) . '">' . esc_attr( $variationObj->get_sku() ) . '</span>';
							}
						} else {
							$skuHtml = $sku;
						}
						$data['sku'] = $skuHtml;
						break;
					case 'categories':
						$terms = false;
						if ( $prodCategoryLink && ! $prodCategoryExclude && ! $isCategoryInnerFilter ) {
							$categories = get_the_term_list( $mainId, 'product_cat', '', $categoriesSeparator, '' );
							if ( ! $frontend || $prodCategoryLinkBlank ) {
								$categories = str_ireplace( '<a', '<a target="_blank"', $categories );
							}
						} else {
							$terms      = get_the_terms( $mainId, 'product_cat' );
							$categories = '';
							if ( ! empty( $terms ) ) {
								$first = true;
								foreach ( $terms as $term ) {
									if ( $prodCategoryExclude && in_array( $term->term_id, $prodCategoryExcludeList ) ) {
										continue;
									}
									if ( $first ) {
										$first = false;
									} else {
										$categories .= $categoriesSeparator;
									}

									if ( $prodCategoryLink || $isCategoryInnerFilter ) {
										$categories .=
											'<a href="' .
											get_category_link( $term->term_id ) .
											'" data-cat-id="' . $term->term_id . '" ' .
											( $isCategoryInnerFilter ? ' data-filter-in-table="1" ' : '' ) .
											'>' .
											$term->name .
											'</a>';
									} else {
										$categories .= $term->name;
									}
								}
							}
						}
						if ( $prodCategoryLink && ( ! $frontend || $prodCategoryLinkBlank ) ) {
							$categories = str_ireplace( '<a', '<a target="_blank"', $categories );
						}
						if ( $isFilterCategory ) {
							if ( false === $terms ) {
								$terms = get_the_terms( $mainId, 'product_cat' );
							}

							$list = array();
							if ( ! empty( $terms ) ) {
								if ( $isFilterCatChildren ) {
									foreach ( $terms as $term ) {
										$termId = $term->term_id;
										if ( ! isset( $catParents[ $termId ] ) ) {
											$catParents[ $termId ]   = get_ancestors( $termId, 'product_cat' );
											$catParents[ $termId ][] = $termId;
										}
										$list = array_merge( $list, $catParents[ $termId ] );
									}
									$list = array_unique( $list );
								} else {
									foreach ( $terms as $term ) {
										$list[] = $term->term_id;
									}
								}
							}
							$data['categories'] = array( $categories, 2 => implode( ',', $list ) );

						} else {
							$data['categories'] = $categories;
						}
						break;
					case 'description':
						if ( $isVariation ) {
							$varDescription = $_product->get_description();
							if ( ! empty( $varDescription ) ) {
								$postContent = $varDescription;
							}
						}

						if ( $displayDescriptionPopup && ( $frontend || $preview ) ) {
							$popupContent = '<div class="wtbpModalContentFull">' . $postContent . '</div>';
						}
						if ( $stripDescription ) {
							$postContent = strip_tags( $this->truncateWordwrap( $postContent, $stripDescriptionSize, '...' ) );
						}
						if ( $displayDescriptionPopup && ( $frontend || $preview ) ) {
							$postContent = '<div class="wtbpOpenModal">' . $postContent . $popupContent . '</div>';
						}

						$data['description'] = $postContent;
						break;
					case 'short_description':
						$postShortDescr = $_product->get_short_description();
						if ( isset( $isDoShortcodes ) && $isDoShortcodes ) {
							$postShortDescr = apply_filters( 'the_content', $postShortDescr );
						}
						if ( $displayShortDescriptionPopup && ( $frontend || $preview ) ) {
							$popupContent = '<div class="wtbpModalContentFull">' . $postShortDescr . '</div>';
						}
						if ( $stripDescriptionShort ) {
							$postShortDescr = strip_tags( $this->truncateWordwrap( $postShortDescr, $stripSizeShort, '...' ) );
						}
						if ( $displayShortDescriptionPopup && ( $frontend || $preview ) ) {
							$postShortDescr = '<div class="wtbpOpenModal">' . $postShortDescr . $popupContent . '</div>';
						}
						$data['short_description'] = $postShortDescr;
						break;
					case 'product_link':
						$url = get_permalink( $id );
						if ( $prodLinkUserString ) {
							$productLinkStr       = '<div class="product woocommerce"><a class="product-details-button button btn single-product-link" href="' . esc_url( $url ) . '" target="' . $target . '">' . $prodLinkUserString . '</a></div>';
							$data['product_link'] = $productLinkStr;
						}
						break;
					case 'reviews':
						$reviews = '';
						$average = $_product->get_average_rating();
						if ( $average ) {
							/* translators: %s: average rating */
							$reviews .= '<div class="star-rating" title="' . esc_attr( sprintf( __( 'Rated %s out of 5', 'woocommerce' ), $average ) ) . '"><span class="star-rating-width" data-width="' . esc_attr( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">' . $average . '</strong> ' . esc_html__( 'out of 5', 'woocommerce' ) . '</span></div>';
						}
						$data['reviews'] = $reviews;
						break;
					case 'stock':
						$value   = '';
						$status  = $_product->get_stock_status();
						$name    = $stockNames[ $status ];
						$colored = $showIcons && isset( $stockIcons[ $status ] );
						if ( $colored ) {
							$value = '<span class="wtbp-stock-' . esc_attr( $status ) . '"><i class="fa fa-' . $stockIcons[ $status ] . ' wtbp-stock-icon" aria-hidden="true" title="' . esc_attr( $name ) . '"></i>';
						}
						if ( $showText ) {
							$value .= $name;
						}
						if ( $showQuantity ) {
							$variations = $isVariable ? $_product->get_available_variations() : array();
							if ( $isVariable && $showVariationQuantity && ! empty( $variations ) ) {
								foreach ( $variations as $variationIterator => $variation ) {
									$variationObj = new WC_Product_variation( $variation['variation_id'] );
									$quantity     = $variationObj->get_stock_quantity();
									if ( $quantity < 0 ) {
										$quantity = 0;
									}
									if ( $quantity ) {
										$variationName = array();
										foreach ( $variation['attributes'] as $attrName => $attrValue ) {
											if ( empty( $attrValue ) ) {
												continue;
											}
											if ( $showVariationQuantityAttrNames ) {
												$taxonomy = str_replace( 'attribute_', '', $attrName );
												if ( taxonomy_exists( $taxonomy ) ) {
													if ( ! isset( $taxonomies[ $taxonomy ] ) ) {
														$terms                            = get_terms( $taxonomy );
														$taxonomies[ $taxonomy ]['terms'] = array();
														foreach ( $terms as $term ) {
															$taxonomies[ $taxonomy ]['terms'][ $term->slug ] = $term->name;
														}
														$taxonomies[ $taxonomy ]['label'] = get_taxonomy( $taxonomy )->labels->singular_name;
													}
													$attrName  = $taxonomies[ $taxonomy ]['label'];
													$attrValue = $this->getTableSetting( $taxonomies[ $taxonomy ]['terms'], $attrValue, $attrValue );
												} else {
													$attrName = $taxonomy;
												}
												$attrName = $attrName . ': ';
											} else {
												$attrName = '';
											}
											array_push( $variationName, strtoupper( $attrName . $attrValue ) );
										}
										if ( $stockMaxQuantity && $quantity > $stockMaxQuantity ) {
											$quantity = $stockMaxQuantity . '+';
										}
										$attribute   = current( $variation['attributes'] );
										$quantityTxt = $stockQuantityText
											? sprintf( '<span class="stock-count" data-attribute="%5$s" data-quantity="%1$d">%2$s - %3$s</span> %4$s',
												$quantity, implode( ', ', $variationName ), $quantity, $stockQuantityText, esc_attr($attribute) )
											: sprintf( '<span class="stock-count" data-attribute="%4$s" data-quantity="%1$d">%2$s - %3$s</span> ' . esc_html__( 'item(s)', 'woo-product-tables' ),
												$quantity, implode( ', ', $variationName ), $quantity, esc_attr($attribute) );
									} else {
										$quantityTxt = $stockQuantityText
											? sprintf( '<span class="stock-count"></span> %1$s', $stockQuantityText )
											: '<span class="stock-count"></span> ' . esc_html__( 'item(s)', 'woo-product-tables' );
									}

									$quantityClass = ( $quantity && $countSmallQuantity && $quantity < $countSmallQuantity ) ? ' count-small-quantity' : '';
									$value        .= '<span class="stock-item-counts' . ( $quantity ? $quantityClass
											: ' wtbpHidden' ) . '">' . $quantityTxt . '</span>';
								}
							} else {
								$quantity = $_product->get_stock_quantity();
								if ( $quantity ) {
									if ( $stockMaxQuantity && $quantity > $stockMaxQuantity ) {
										$quantity = $stockMaxQuantity . '+';
									}
									$quantityTxt = $stockQuantityText
										? sprintf( '<span class="stock-count" data-quantity="%1$d">%2$s</span> %3$s',
											$quantity, $quantity, $stockQuantityText )
										: sprintf( '<span class="stock-count" data-quantity="%1$d">%2$s</span> ' . esc_html__( 'item(s)', 'woo-product-tables' ),
											$quantity, $quantity );
								} else {
									$quantityTxt = $stockQuantityText
										? sprintf( '<span class="stock-count"></span> %1$s', $stockQuantityText )
										: '<span class="stock-count"></span> ' . esc_html__( 'item(s)', 'woo-product-tables' );
								}
								$value .= '<span class="stock-item-counts' . ( $quantity ? ''
										: ' wtbpHidden' ) . '">' . $quantityTxt . '</span>';

								if ( $quantity && $countSmallQuantity && $quantity < $countSmallQuantity ) {
									if ( false !== strpos( $value, 'wtbp-stock-instock' ) ) {
										$value = str_replace( 'wtbp-stock-instock', 'wtbp-stock-instock count-small-quantity', $value );
									} else {
										$value = '<span class="count-small-quantity">' . $value . '</span>';
									}
								}
							}
						}
						if ( $colored ) {
							$value .= '</span>';
						}
						if ($isFilterStock) {
							$data['stock'] = array( $value, 2 => $status );
						} else {
							$data['stock'] = $value;
						}
						break;
					case 'date':
						$data['date'] = $postDate;
						break;
					case 'sale_dates':
						$period = '';
						if ( $_product->is_on_sale() ) {
							$saleFrom = get_post_meta( $id, '_sale_price_dates_from', true );
							$saleTo   = get_post_meta( $id, '_sale_price_dates_to', true );
							if ( ! empty( $saleFrom ) ) {
								$period = '<span class="wtbpSaleDates">' . gmdate( $dateFormat, $saleFrom ) . '</span>';
							}
							if ( ! empty( $saleTo ) ) {
								$period .= ( empty( $period ) ? '' : ' ' ) . '<span class="wtbpSaleDates">' . gmdate( $dateFormat, $saleTo ) . '</span>';
							}
						}
						$data['sale_dates'] = $period;
						break;
					case 'downloads':
						$downloads = '';
						$files     = $_product->get_downloads();
						if ( count( $files ) > 0 ) {
							$showAs = $this->getTableSetting( $column, 'downloads_show_as', 'icon' );
							foreach ( $files as $download ) {
								$file = $download->get_file();
								preg_match( '/^\s*\[.*?\]\s*$/', $file, $matches );
								$path = ( count( $matches ) > 0 ) ? $path = do_shortcode( $file ) : esc_url( $file );
								$name = esc_html( $download->get_name() );

								if ( 'audio' == $showAs ) {
									$downloads .= '<audio controls class="wtbpDownloadsControl"><source src="' . $path . '">Your browser does not support the <code>audio</code> element.</audio>';
								} elseif ( 'video' == $showAs ) {
									$downloads .= '<video controls class="wtbpDownloadsControl"><source src="' . $path . '">Sorry, your browser does not support embedded videos.</video>';
								} else {
									$downloads .= '<a' . ( 'button' == $showAs ? ' class="button wtbpDownloadsButton"' : '' ) . ' href="' . $path . '"' . ( ! $frontend || $prodDownloadsLinkBlank ? ' target="_blank"' : '' ) . '>';
									if ( 'icon' == $showAs ) {
										$downloads .= '<i class="fa fa-fw fa-download"></i>';
									} else {
										$downloads .= $name;
									}
									$downloads .= '</a>';
								}
							}
						}
						$data['downloads'] = $downloads;
						break;
					case 'price':
						$wtbpProduct = $_product;
						$price       = $_product->get_price_html();
						if ($isGzd) {
							//$price = wc_gzd_get_product( $_product )->get_unit_price_html();
							$priceUnit = wc_gzd_get_product( $_product )->get_unit_price_html();
							if (!empty($priceUnit)) {
								$price = $priceUnit;
							}
                        }
						if ( $b2bPrice ) {
							$price = $b2bPrice->af_csp_custom_price_html( $price, $_product );
						}

						if ( $varPriceColumn ) {
							$price = '<span class="wtbpPrice">' . $price . '</span>';
						}
						$rawPrice = apply_filters( 'raw_woocommerce_price', $_product->get_price() );
						$prices   = array( $price, $rawPrice );
						if ( $isFilterPrices ) {
							if ( $isVariable ) {
								$varPrices = $_product->get_variation_prices();
								if ( isset( $varPrices['price'] ) && is_array( $varPrices['price'] ) ) {
									$rawPrice = implode( ',', $varPrices['price'] );
								}
							}
							$prices[] = $rawPrice;
						}
						$data['price'] = $prices;
						break;
					case 'add_to_cart':
						$varInStock = '';

						if ( $_product->get_stock_status() == 'outofstock' ) {
							if ( $frontend && $multyAddToCart ) {
								$data['check_multy'] = '';
							}
							if ($isOutstockNotify) {
								ob_start();
								$outstockNotify->display_form( $_product );
								$addToCartHtml = ob_get_clean();
							} else {
								$addToCartHtml = $stockNames['outofstock'];
							}
							$data['add_to_cart'] = '<div class="wtbpOutOfStockCart">' . $addToCartHtml . '</div>';
							//$data['add_to_cart'] = '<div class="wtbpOutOfStockCart">' . $stockNames['outofstock'] . '</div>';
						} else {
							if ( $frontend ) {
								$variablesHtml       = '';
								$varPricesHtml       = '';
								$varDescriptionsHtml = '';
								$varImagesHtml       = $this->getProductThumbnailLink( $id, 'large', 'class="wtbpVarImageForPopup wtbpHidden" data-variation_id="' . esc_attr( $id ) . '"' );

								$view = FrameWtbp::_()->getModule( 'wootablepress' )->getView();

								if ( $isVariable && ! $hideVariationAttr ) {
									$variations = array();
									$attributes = array();

									$defaultId = 0;

									$predefindAttributes = array();

									foreach ( $_product->get_available_variations( 'objects' ) as $variation ) {
										if ( $variation->variation_is_visible() ) {
											$varId   = $variation->get_id();
											$inStock = $variation->is_in_stock();

											if ( $inStock ) {
												$varInStock = $varId;
											}
											$varAttributes = array();

											foreach ( $variation->get_variation_attributes() as $key => $value ) {
												$taxonomy = str_replace( 'attribute_', '', $key );
												if ( taxonomy_exists( $taxonomy ) ) {
													if ( ! isset( $taxonomies[ $taxonomy ] ) ) {
														$terms = get_terms( $taxonomy );
														foreach ( $terms as $term ) {
															$taxonomies[ $taxonomy ]['terms'][ $term->slug ] = $term->name;
														}
														$taxonomies[ $taxonomy ]['label'] = get_taxonomy( $taxonomy )->labels->singular_name;
													}
												} else {
													if ( ! isset( $taxonomies[ $taxonomy ] ) ) {
														$taxonomies[ $taxonomy ] = array( 'label' => ucfirst( $taxonomy ), 'terms' => array() );
													}
													if ( ! empty( $value ) ) {
														$taxonomies[ $taxonomy ]['terms'][ $value ] = $value;
													}
												}
												if ( ! isset( $taxonomies[ $taxonomy ] ) ) {
													break;
												}
												if ( empty( $value ) || ! isset( $taxonomies[ $taxonomy ]['terms'][ $value ] ) ) {
													if ( ! isset( $predefindAttributes[ $taxonomy ] ) ) {
														$predefindAttributes[ $taxonomy ] = array();
														$attributeList                    = $_product->get_attributes();
														foreach ( $attributeList as $attributeObject ) {
															$attrData = $attributeObject->get_data();
															if ( $taxonomy == $attrData['name'] ) {
																if ( ! empty( $attrData['options'] ) ) {
																	foreach ( $attrData['options'] as $termId ) {
																		$term = get_term_by( 'id', $termId, $taxonomy );
																		if ( ! empty( $term->slug ) && ! empty( $term->name ) ) {
																			$predefindAttributes[ $taxonomy ][ $term->slug ] = $term->name;
																		}
																	}
																}
															}
														}
													}
													$attributes[ $taxonomy ]    = empty( $predefindAttributes[ $taxonomy ] ) ? $taxonomies[ $taxonomy ]['terms'] : $predefindAttributes[ $taxonomy ];
													$varAttributes[ $taxonomy ] = '';
												} else {
													$attributes[ $taxonomy ][ $value ] = $taxonomies[ $taxonomy ]['terms'][ $value ];
													$varAttributes[ $taxonomy ]        = $value;
												}
											}

											$variations[ $varId ] = $varAttributes;
											if ( ! $buttonForVariation ) {
												$maxQty = $variation->get_max_purchase_quantity();
												if ( empty( $maxQty ) || 0 >= $maxQty ) {
													$maxQty = '';
												}
												$minQty = $variation->get_min_purchase_quantity();
												$stepQty = 1;

												if ($barnQuantity) {
													$qtyBarn = Barn2\Plugin\WC_Quantity_Manager\Util\Quantity::get_calculated_quantity_restrictions($variation);
													if (!empty($qtyBarn) && is_array($qtyBarn)) {
														if (!empty($qtyBarn['min']) && $qtyBarn['min'] > 0) {
															$minQty = $qtyBarn['min'];
														}
														if (!empty($qtyBarn['max']) && $qtyBarn['max'] > 0) {
															$maxQty = $qtyBarn['max'];
														}
														if (!empty($qtyBarn['step']) && $qtyBarn['step'] > 0) {
															$stepQty = $qtyBarn['step'];
														}
													}
												}

												$variationQuantity = ' data-quantity="' . esc_attr( $maxQty ) . '"';

												$varPricesHtml .=
													'<div class="wtbpVarPrice wtbpHidden" data-variation_id="' . esc_attr( $varId ) .
													'" data-instock="' . ( $inStock ? '1' : '0' ) . '"' . $variationQuantity .
													'" data-max-qty="' . $maxQty . '"' .
													'" data-min-qty="' . $minQty . '"' .
													'" data-step="' . $stepQty . '"' .
													'>' . $variation->get_price_html() .
													( $inStock ? '' : '<div class="wtbpVarOutofstock">' . esc_html( $stockNames['outofstock'] ) . '</div>' ) .
													'</div>';

												if ( $showVarDescription ) {
													$varDescriptionsHtml .=
														'<div class="wtbpVarDescription wtbpHidden" data-variation_id="' . esc_attr( $varId ) . '">' .
														$variation->get_description() .
														'</div>';
												}


												if ( $showVarImages ) {
													$varImagesHtml .= $this->getProductThumbnailLink( $varId, $imgSize, 'class="wtbpVarImage wtbpHidden" data-variation_id="' . esc_attr( $varId ) . '"' );
												}
												if ( $isPopupForVariation ) {
													$varImagesHtml .= $this->getProductThumbnailLink( $varId, 'large', 'class="wtbpVarImageForPopup wtbpHidden" data-variation_id="' . esc_attr( $varId ) . '"' );
												}
												if ( empty( $defaultId ) ) {
													$defaultId = $varId;
												}
											}
										}
									}

									if ( ! empty( $varPricesHtml ) ) {
										$varPricesHtml = '<div class="wtbpVarPrices' . ( $varPriceColumn ? ' wtbpHidden' : '' ) . '">' . $varPricesHtml . '</div>';
									}
									if ( ! empty( $varImagesHtml ) ) {
										$varImagesHtml = '<div class="wtbpVarImages">' . $varImagesHtml . '</div>';
									}
									if ( count( $attributes ) > 0 && ! $buttonForVariation ) {
										$variablesHtml = '<div class="wtbpVarAttributes' . esc_attr( $isPopupForVariation ? ' wtbpHidden' : '' ) . '" data-default-id="' . esc_attr( $defaultId ) . '" data-variations="' . htmlspecialchars( json_encode( $variations ), ENT_QUOTES, 'UTF-8' ) . '">';

										foreach ( $attributes as $taxonomy => $terms ) {
											$variablesHtml .=
												'<select class="wtbpVarAttribute" data-attribute="' . esc_attr( $taxonomy ) . '">' .
												'<option value="">' .
												esc_html( $taxonomies[ $taxonomy ]['label'] ) .
												'</option>';
											// case when attribute values set by string with | as separator
											if ( ! $terms ) {
												$metaAttributes = get_post_meta( $id, '_product_attributes' );
												if ( isset( $metaAttributes[0][ $taxonomy ] ) ) {
													$terms = explode( '|', $metaAttributes[0][ $taxonomy ]['value'] );
													$terms = array_map( 'trim', $terms );
												}
												foreach ( $terms as $key => $value ) {
													unset( $terms[ $key ] );
													$terms[ $value ] = $value;
												}
											}

											if ( 1 === $naturalOrder ) {
												natsort( $terms );
											} else if ( 1 === $customOrder ) {
												if (!isset($termsCustomOrders[$taxonomy])) {
													$termsCustomOrder = get_terms($taxonomy, array('orderby' => 'menu_order', 'fields' => 'slugs'));
													$termsCustomOrders[$taxonomy] = is_array($termsCustomOrder) ? $termsCustomOrder : array();
												}
												if (!empty($termsCustomOrders[$taxonomy])) {
													$newTerms = array();
													foreach ( $termsCustomOrders[$taxonomy] as $slug ) {
														if (isset($terms[$slug])) {
															$newTerms[$slug] = $terms[$slug];
														}
													}
													if (count($newTerms) == count($terms)) {
														$terms = $newTerms;
													}
												}
											}

											foreach ( $terms as $slug => $value ) {
												$variablesHtml .= '<option value="' . esc_attr( $slug ) . '">' . esc_html( $value ) . '</option>';
											}
											$variablesHtml .= '</select>';
										}
										$variablesHtml .= '</div>';
									}
								}

								if ( $isVariable && $isPopupForVariation ) {
									$variablesHtml  = empty( $variablesHtml ) ? '' : $variablesHtml;
									$variablesHtml .= '<div class="wtbpProductName wtbpHidden"><div class="wtbpName">' . $_product->get_name() . '</div></div>';
									if ( 'yes' === get_option( 'woocommerce_enable_review_rating' ) ) {
										$variablesHtml .= '<div class="wtbpProductRating wtbpHidden"><div class="wtbpRate">' . wc_get_rating_html( $_product->get_average_rating(), $_product->get_rating_count() ) . '</div></div>';
									}

									$description = ( $popupForVariationShortDescription ) ? $_product->get_short_description() : $_product->get_description();

									$variablesHtml .= '<div class="wtbpProductDescription wtbpHidden">' . $description . '</div>';
								}

								if ( $isVariable ) {
									$quantityHtml = ! $hideQuantityInput && ! $hideVariationAttr ? woocommerce_quantity_input( array(), $_product, false ) : '';
								} else {
									$quantityHtml = ! $hideQuantityInput ? woocommerce_quantity_input( array(), $_product, false ) : '';
								}

								$cartUrl      = wc_get_cart_url();
								$addToCartUrl = do_shortcode( '[add_to_cart_url id="' . $id . '"]' ); // dont delete this row!
								if ( $multyAddToCart ) {
									$data['check_multy'] =
										'<input type="checkbox" class="wtbpAddMulty" value="' . esc_attr( $id ) .
										'" data-quantity="1" data-variation_id="0" data-position="' . esc_attr( $multyAddPosition ) . '"' .
										( empty( $variablesHtml ) && 'grouped' !== $productType && 'external' !== $productType ? '' : ' disabled' ) .
										'>';
								}

								if ( $isVariable && $hideVariationAttr ) {
									$data['check_multy'] = '';
								}
								$prId                 = ! empty( $varInStock ) ? $varInStock : $id;
								$_wc_price_calculator = get_post_meta( $id, '_wc_price_calculator', false );
								$_wc_price_calculator = ! empty( $_wc_price_calculator ) && isset( $_wc_price_calculator[0]['calculator_type'] ) ? $_wc_price_calculator[0]['calculator_type'] : false;
								if ( $_wc_price_calculator ) {
									ob_start();
									woocommerce_template_single_add_to_cart();
									$data['add_to_cart'] = ob_get_contents();
									ob_end_clean();
									$hideQuantityClass = $hideQuantityInput ? ' wpfHideQuantityInput' : '';
									if ( ! $isVariable ) {
										$shortcode =
											'<div class="mpc_add_to_cart_shortcode' . esc_attr( $hideQuantityClass ) . '">' .
											do_shortcode( '[add_to_cart id="' . $id . '" style="" class="product_button_mpc" show_price="false" sku ="' . $sku . '"]' ) .
											'</div>';
									} else {
										$shortcode = '';
									}
									$shortcode = DispatcherWtbp::applyFilters( 'customizeCartButtonMPC', $shortcode );

									$addToCartClass = 'add_to_cart_button ajax_add_to_cart product_mpc';
									$shortcode      = str_replace( 'add_to_cart_button', $addToCartClass, $shortcode );
									if ( $isVariable ) {
										$data['add_to_cart'] = str_replace( 'single_add_to_cart_button', 'single_add_to_cart_button ' . $addToCartClass, $data['add_to_cart'] );
										$data['add_to_cart'] = str_replace( '<button type="submit"', '<button type="button" data-product_id="' . $id . '" data-product_sku="' . $sku . '"', $data['add_to_cart'] );
									}
									$data['add_to_cart'] = str_replace( '<form class="cart"', '<form class="cart form_product_mpc' . esc_attr( $hideQuantityClass ) . '"', $data['add_to_cart'] );
									$data['add_to_cart'] = str_replace( '<form class="variations_form cart"', '<form class="variations_form cart form_product_mpc_variations' . esc_attr( $hideQuantityClass ) . '"', $data['add_to_cart'] );
									$data['add_to_cart'] = str_replace( '</form>', $shortcode . '</form>', $data['add_to_cart'] );
									$data['add_to_cart'] = str_replace( '<table', '<div', $data['add_to_cart'] );
									$data['add_to_cart'] = str_replace( '</table>', '</div>', $data['add_to_cart'] );
									$data['add_to_cart'] = str_replace( '<tbody>', '<div>', $data['add_to_cart'] );
									$data['add_to_cart'] = str_replace( '</tbody>', '</div>', $data['add_to_cart'] );
									$data['add_to_cart'] = str_replace( '<tr', '<div', $data['add_to_cart'] );
									$data['add_to_cart'] = str_replace( '</tr>', '</div>', $data['add_to_cart'] );
									$data['add_to_cart'] = str_replace( '<td', '<div', $data['add_to_cart'] );
									$data['add_to_cart'] = str_replace( '</td>', '</div>', $data['add_to_cart'] );
								} else {
									if ( $isVariable ) {
										add_filter( 'add_to_cart_text', array( $this, 'replaceButtonTextVariableProduct' ), 30, 1 );
										add_filter( 'woocommerce_product_add_to_cart_text', array(
											$this,
											'replaceButtonTextVariableProduct'
										), 30, 1 );
									} else {
										add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'replaceAddToCartText' ), 30, 1 );
									}
									// button for every variation option
									if ( $isVariable && ! $hideVariationAttr && count( $variations ) > 0 && $buttonForVariation ) {
										$buttons = '<div class="wtbpAddToCartWrapper wtpbVariableButtons" data-product_id="' . $id . '">' . $quantityHtml;
										foreach ( $variations as $varId => $attrs ) {
											$attrs                 = array_filter( $attrs );
											$this->loopButtonTitle = implode( '-', $attrs );
											$buttons              .= do_shortcode( '[add_to_cart id="' . $varId . '" class="" style="" show_price="false" sku ="' . $sku . '"]' );
										}
										$data['add_to_cart'] = $buttons . '</div>';
									} else {
										$data['add_to_cart'] = $variablesHtml;
										if ( $isVariable && $isPopupForVariation && ! empty( $variablesHtml ) ) {
											$this->loopButtonTitle = $popupForVariationBtnText;
										} else if ($withNote && 'grouped' !== $productType) {
											$data['add_to_cart'] .=
												'<div class="wtbpPropuctNoteContent">' .
													'<label>' . esc_html__('Product note (optional)', 'woo-product-tables') . '</label>' .
													'<textarea class="wtbpProductNote" placeholder="' . esc_html__('Add your note here, please…', 'woo-product-tables') . '"></textarea>' .
												'</div>';
										}
										$data['add_to_cart'] .=
											'<div class="wtbpAddToCartWrapper' . ( empty( $variablesHtml ) ? '' : ' wtbpDisabledLink' ) .
											( $isVariable && $isPopupForVariation ? ' wtbpHasPopupVariations' : '' ) .
											'" data-product_id="' . $id .
											'" data-cart-text="' . $_product->add_to_cart_text() .
											'" data-default-cart-text="' . esc_attr( $popupForVariationBtnText ) .
											'">' .
											
											$quantityHtml .
											do_shortcode( '[add_to_cart id="' . $id . '" class="" style="" show_price="false" sku ="' . $sku . '"]' ) .
											'</div>' .
											$varPricesHtml .
											'<div class="wtbpVarDescriptions wtbpHidden" data-show-var-description="' . ( ( $showVarDescription ) ? '1' : '0' ) . '">' . $varDescriptionsHtml .
											'</div>' .
											$varImagesHtml;
									}
									if ( $isVariable ) {
										remove_filter( 'add_to_cart_text', array( $this, 'replaceButtonTextVariableProduct' ), 30 );
										remove_filter( 'woocommerce_product_add_to_cart_text', array(
											$this,
											'replaceButtonTextVariableProduct'
										), 30 );
									} else {
										remove_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'replaceAddToCartText' ), 30 );
									}
								}
								$varId = '';
							} else {
								$data['add_to_cart'] = do_shortcode( '[add_to_cart id="' . $id . '" class="" style="" show_price="false" sku ="' . $sku . '"]' );
							}

							/**
							 * Plugin compatibility
							 *
							 * @link https://woocommerce.com/products/b2b-for-woocommerce/
							 */
							if ( in_array( 'b2b/addify_b2b.php', get_option( 'active_plugins' ) ) ) {
								$addifyRequestForQuoteFront = false;
								$fileName                   = WP_PLUGIN_DIR . '/b2b/additional_classes/class_afb2b_rfq_front.php';
								if ( file_exists( $fileName ) ) {
									include $fileName;
								}
								if ( class_exists( 'Addify_Request_For_Quote_Front' ) ) {
									$addifyRequestForQuoteFront = new Addify_Request_For_Quote_Front();
								}

								if ( !$addifyRequestForQuoteFront || ! method_exists( $addifyRequestForQuoteFront, 'afrfq_replace_loop_add_to_cart_link' ) ) {
									$fileName = WP_PLUGIN_DIR . '/b2b/woocommerce-request-a-quote/front/class-af-r-f-q-front.php';
									if ( file_exists( $fileName ) ) {
										include $fileName;
									}
									if ( class_exists( 'AF_R_F_Q_Front' ) ) {
										$addifyRequestForQuoteFront = new AF_R_F_Q_Front();
									}
								}

								if ( !$addifyRequestForQuoteFront || ! method_exists( $addifyRequestForQuoteFront, 'afrfq_replace_loop_add_to_cart_link' ) ) {
									$fileName = WP_PLUGIN_DIR . '/b2b/woocommerce-request-a-quote/includes/class-af-r-f-q-main.php';
									if ( file_exists( $fileName ) ) {
										include $fileName;
									}
									if ( class_exists( 'AF_R_F_Q_Main' ) ) {
										$addifyRequestForQuoteFront              = new AF_R_F_Q_Main();
										$addifyRequestForQuoteFront->quote_rules = $addifyRequestForQuoteFront->afrfq_get_quote_rules();
									}
								}

								if ( $addifyRequestForQuoteFront && method_exists( $addifyRequestForQuoteFront, 'afrfq_replace_loop_add_to_cart_link' ) ) {
									$data['add_to_cart'] = $addifyRequestForQuoteFront->afrfq_replace_loop_add_to_cart_link( $data['add_to_cart'], $_product );
								}
							}
						}
						break;
					default:
						$data = DispatcherWtbp::applyFilters(
							'getColumnContent',
							$data,
							array(
								'column'     => $column,
								'product'    => $_product,
								'frontend'   => $frontend,
								'settings'   => $settings,
								'stockNames' => $stockNames,
								'imgSize'    => $imgSize,
								'mainId'     => $mainId,
							)
						);
						break;
				}
			}

			if ( ReqWtbp::getVar( '_fs_blog_admin', 'get' ) ) {
				foreach ( $data as $key => $value ) {
					if ( is_string( $value ) && strpos( $value, 'waveplayer' ) !== false ) {
						$data[ $key ] = 'WavePlayer does not start in preview mode';
					}
				}
			}

			$dataArr[] = $data;
		}
		DispatcherWtbp::doAction('afterProductsTableLoop');
		if ( $isPage || ! $frontend ) {
			$page['total']    = $dataExist->found_posts;
			$page['filtered'] = count( $dataArr );
		}

		return $dataArr;
	}

	public function replaceAddToCartText( $text ) {
		$modifyText = __( $text, 'woo-product-tables' );

		return $modifyText;
	}

	/**
	 * Replace button text for variable product.
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function replaceButtonTextVariableProduct( $text ) {
		if ( $this->loopButtonTitle ) {
			$modifyText = $this->loopButtonTitle;
		} elseif ( __( 'Select options', 'woocommerce' ) === $text ) {
			$modifyText = __( 'Add to cart', 'woocommerce' );
		} else {
			$modifyText = $text;
		}

		return $modifyText;
	}

	public function setAdminSSPQueryFilters( $settings, $args, $page ) {
		$args['posts_per_page'] = $page['length'];
		$args['offset']         = $page['start'];
		if ( ! empty( $page['search']['value'] ) ) {
			$args['s'] = $page['search']['value'];
		}

		return $args;
	}

	/**
	 * Trancate string to predefind length
	 *
	 * @param string $initStr Initial string
	 * @param int $len Result string length
	 * @param string $etc Append to result string
	 *
	 * @return string
	 */
	public function truncateWordwrap( $initStr, $len, $etc = '.. . ' ) {
		$existMB = function_exists( 'mb_strimwidth' );
		// need for exeptional case when mb_strimwidth return inappropriate result
		$withEtcLength      = $len + strlen( $etc );
		$isMbExeptionalCase = strlen( $initStr ) != $withEtcLength ? true : false;

		if ( $existMB && $isMbExeptionalCase ) {
			$result = mb_strimwidth( $initStr, 0, $len + strlen( $etc ), $etc );
		} else {
			if ( strlen( $initStr ) <= $len ) {
				return $initStr;
			}
			$cut    = substr( $initStr, 0, $len );
			$result = $cut . $etc;
		}

		return $result;
	}

	public function getColumnNiceName( $slug ) {
		if ( empty( $this->columnNiceNames ) ) {
			$orders = $this->orderColumns;
			$names  = array();
			if ( empty( $orders ) ) {
				$tableColumns = $this->getModel( 'columns' )->getFromTbl();
				foreach ( $tableColumns as $columns ) {
					$names[ $columns['columns_name'] ] = $columns['columns_nice_name'];
				}
			} else {
				foreach ( $orders as $order ) {
					$name                    = ( ! empty( $order['show_display_name'] ) && '1' === $order['show_display_name'] ) ? $order['display_name'] : $order['original_name'];
					$names[ $order['slug'] ] = $name;
				}
			}
			$this->columnNiceNames = $names;
		}

		return array_key_exists( $slug, $this->columnNiceNames ) ? $this->columnNiceNames[ $slug ] : $slug;
	}

	public function sortProductColumns() {
		$orders    = $this->orderColumns;
		$sortArray = array();
		if ( ! empty( $orders ) ) {
			foreach ( $orders as $order ) {
				$sortArray[] = $order['slug'];
			}
		} else {
			$orders = array( 'product_title', 'thumbnail', 'categories', 'price', 'date' );
			foreach ( $orders as $order ) {
				$sortArray[] = $order;
			}
		}

		return $sortArray;
	}

	public function generateTableHtml( $listPost, $frontend, $settings, $withHeader = true ) {
		$dateAndTimeFormat = $this->getDateTimeFormat( $settings );
		$columns           = $this->sortProductColumns();
		if ( $frontend && $this->getTableSetting( $settings, 'multiple_add_cart', false ) ) {
			$mode             = $this->getTableSetting( $settings, 'responsive_mode', '' );
			$multyAddPosition = $this->getTableSetting( $settings, 'multiple_add_cart_position', 'first' );
			if ( 'first' == $multyAddPosition || 'responsive' == $mode || 'hiding' == $mode ) {
				array_unshift( $columns, 'check_multy' );
			} else {
				array_push( $columns, 'check_multy' );
			}
		}

		if ( 'responsive' == $this->getTableSetting( $settings, 'responsive_mode' ) ) {
			$responciveChildHide = $this->getTableSetting( $settings, 'responcive_child_hide' );

			if ( 'add_column' == $responciveChildHide ) {
				array_unshift( $columns, '' );
			} elseif ( 'disable' == $responciveChildHide ) {
				array_unshift( $columns, 'hide_responcive' );
			}
		}

		if ( $withHeader ) {
			$noSortColumns         = array(
				'thumbnail',
				'add_to_cart',
				'description',
				'short_description',
				'attribute',
				'sale_dates',
				'check_multy'
			);
			$this->columnNiceNames = array();
			$tableHeader           = '<tr>';
			if ( ! $frontend ) {
				$tableHeader .= '<th class="no-sort"><input class="wtbpCheckAll" type="checkbox"/></th>';
			}
			foreach ( $columns as $key ) {
				$isColumnDisableSorting = $this->getTableSetting( $key, 'disable_sorting', false );

				$column                 = $this->getModel( 'settings' )->getColumnInOrder( $key, $this->orderColumns );
				$isColumnDisableSorting = $this->getTableSetting( $column, 'disable_sorting', false );

				$noSort       = in_array( $key, $noSortColumns ) || $isColumnDisableSorting ? ' class="no-sort"' : '';
				$tableHeader .= '<th data-key="' . esc_attr( $key ) . '"' . $noSort . '>' . ( 'check_multy' == $key ? '<input type="checkbox" class="wtbpAddMultyAll" data-position="' . esc_attr( $multyAddPosition ) . '">' : esc_html__( $this->getColumnNiceName( $key ), 'woo-product-tables' ) ) . '</th>';
			}
			$tableHeader .= '</tr>';
		}
		$tableBody = '';
		for ( $i = 0; $i < count( $listPost ); $i ++ ) {
			$class = empty($listPost[$i]['tr_class']) ? '' : ' class="' . $listPost[$i]['tr_class'] . '"';
			$tableBody .= $frontend ? '<tr' . $class . '>' : '<tr><td><input type="checkbox" data-id="' . esc_attr( $listPost[ $i ]['id'] ) . '"></td>';
			$product    = $listPost[ $i ];
			foreach ( $columns as $key ) {
				$data = isset( $product[ $key ] ) ? $product[ $key ] : '';

				if ( empty( $data ) ) {
					$data = '';
				}
				$order = '';
				if ( is_array( $data ) ) {
					if ( isset( $data[1] ) ) {
						$order = ' data-order="' . esc_attr( $data[1] ) . '" data-search="' . esc_attr( $data[1] ) . '"';
					}
					if ( isset( $data[2] ) ) {
						$order .= ' data-custom-filter="' . esc_attr( $data[2] ) . '"';
					}
					$data = $data[0];
				}

				if ( 'date' === $key && $dateAndTimeFormat ) {
					$date          = $data;
					$dateTimestamp = strtotime( $date );
					$outputDate    = gmdate( $dateAndTimeFormat, $dateTimestamp );

					$tableBody .= '<td' . ( $frontend ? ' data-order="' . esc_attr( $dateTimestamp ) . '"' : '' ) . ' class="' . esc_attr( $key ) . '"><div class="wtbpNoBreak">' . $outputDate . '</div></td>';
				} elseif ( 'product_title' === $key ) {
					$tableBody .= '<td class="' . esc_attr( $key ) . '">' . $data . '</td>';
				} else {
					$tableBody .= '<td class="' . esc_attr( $key ) . '"' . ( $frontend ? $order : '' ) . '>' . $data . '</td>';
				}
			}
			$tableBody .= '</tr>';
		}

		$table = '';
		if ( $withHeader ) {
			$table = '<thead>' . $tableHeader . '</thead>';
			if ( $this->getTableSetting( $settings, 'footer_show', false ) ) {
				$table .= '<tfoot>' . $tableHeader . '</tfoot>';
			}
		}
		$table .= '<tbody>' . $tableBody . '</tbody>';

		$isPro = FrameWtbp::_()->isPro();
		if ( $isPro ) {
			foreach ( $this->orderColumns as $column ) {
				if ( array_key_exists( 'description_popup', $column ) || array_key_exists( 'short_description_popup', $column ) ) {
					$table .= ViewWtbp::getContent( 'wootablepressDescriptionPopup' );
				}
				if ( array_key_exists( 'add_to_cart_popup', $column ) ) {
					$variationToCart = $this->getTableSetting( $settings, 'variation_to_cart', __( 'Add for', 'woo-product-tables' ) );
					$table          .= ViewWtbp::getContentWithParams( 'wootablepressVariablesPopup', array( 'variationToCart' => $variationToCart, 'withNote' => $this->getTableSetting( $column, 'add_to_cart_note', false ) ) );
				}
			}
		}

		return $table;
	}

	public function generateTableSearchData( $listPost ) {
		$table = array();
		$yes   = esc_html__( 'yes', 'woo-product-tables' );
		$no    = esc_html__( 'no', 'woo-product-tables' );
		foreach ( $listPost as $post ) {
			$table[] = array(
				'0'  => '<input type="checkbox" data-id="' . $post['id'] . '">',
				'1'  => ( $post['in_table'] ? '<label class="wtbpPropuctInTable">' . $yes . '</label>' : $no ),
				'2'  => $post['thumbnail'],
				'3'  => $post['product_title'],
				'4'  => $post['variation'],
				'5'  => $post['categories'],
				'6'  => $post['sku'],
				'7'  => $post['stock'],
				'8'  => $post['price'],
				'9'  => $post['date'],
				'10' => $post['attributes'],
			);
		}

		return $table;
	}

	public function getDateTimeFormat( $settings ) {

		$dateFormat        = $this->getTableSetting( $settings, 'date_formats', false );
		$timeFormat        = $this->getTableSetting( $settings, 'time_formats', false );
		$dateAndTimeFormat = false;
		if ( $timeFormat && $dateFormat ) {
			$dateAndTimeFormat = $dateFormat . ' ' . $timeFormat;
		} elseif ( $dateFormat ) {
			$dateAndTimeFormat = $dateFormat;
		} elseif ( $timeFormat ) {
			$dateAndTimeFormat = $timeFormat;
		}

		return $dateAndTimeFormat;
	}

	public function getTaxonomyHierarchyHtml( $parent = 0, $pre = '', $tax = 'product_cat' ) {
		$args    = array(
			'hide_empty' => true,
			'parent'     => $parent
		);
		$terms   = get_terms( $tax, $args );
		$options = '';
		foreach ( $terms as $term ) {
			if ( ! empty( $term->term_id ) ) {
				$options .= '<option data-parent="' . esc_attr( $parent ) . '" value="' . esc_attr( $term->term_id ) . '">' . $pre . esc_html( $term->name ) . '</option>';
				$options .= $this->getTaxonomyHierarchyHtml( $term->term_id, $pre . '&nbsp;&nbsp;&nbsp;', $tax );
			}
		}

		return $options;
	}

	public function getProductsWithVariationsHtml() {
		$args     = array(
			'post_type'           => 'product',
			'posts_per_page'      => - 1,
			'fields'              => array( 'ID', 'post_title' ),
			'ignore_sticky_posts' => true,
			'tax_query'           => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'variable',
				),
			),
		);
		$products = new WP_Query( $args );
		$options  = '';
		foreach ( $products->posts as $product ) {
			if ( ! empty( $product->ID ) ) {
				$options .= '<option value="' . esc_attr( $product->ID ) . '">' . esc_html( $product->post_title ) . '</option>';
			}
		}

		return $options;
	}

	public function getAuthorsHtml() {
		$options = '';
		foreach ( get_users() as $user ) {
			$options .= '<option value="' . esc_attr( $user->ID ) . '">' . esc_html( $user->display_name ) . '</option>';
		}

		return $options;
	}

	public function getChildrenAttributesHierarchy( $parent = 0, $slugname = '', $pre = '' ) {
		$terms   = get_terms( $slugname, array(
			'hide_empty' => true,
			'parent'     => 0
		) );
		$options = '';
		foreach ( $terms as $term ) {
			if ( ! empty( $term->term_id ) ) {
				$options .= '<option data-parent="' . esc_attr( $parent ) . '" value="' . esc_attr( $term->term_id ) . '">' . $pre . esc_html( $term->name ) . '</option>';
			}
		}

		return $options;
	}

	public function getAttributesHierarchy( $parent = 0, $pre = '' ) {
		$attributesListArray = array();
		$options             = '';

		$attributes = wc_get_attribute_taxonomies();
		if ( ! empty( $attributes ) ) {
			foreach ( $attributes as $attribute ) {
				$attributesListArray[ $attribute->attribute_id ] = array(
					'name'  => 'pa_' . $attribute->attribute_name,
					'label' => $attribute->attribute_label
				);
			}
		}

		foreach ( $attributesListArray as $attributeId => $attribute ) {
			$options .= '<option data-parent="' . esc_attr( $parent ) . '" value="' . esc_attr( $attributeId ) . '">' . esc_html( $attribute['label'] ) . '</option>';
			$options .= self::getChildrenAttributesHierarchy( $attributeId, $attribute['name'], '&nbsp;&nbsp;&nbsp;' );
		}

		return $options;
	}

	public function getLeerSearchTable() {
		$th = '<th class="no-sort"><input class="wtbpCheckAll" type="checkbox"/></th>' .
			  '<th class="no-sort">' . esc_html__( 'In table', 'woo-product-tables' ) . '</th>' .
			  '<th class="no-sort">' . esc_html__( 'Thumbnail', 'woo-product-tables' ) . '</th>' .
			  '<th>' . esc_html__( 'Name', 'woo-product-tables' ) . '</th>' .
			  '<th class="no-sort">' . esc_html__( 'Variation', 'woo-product-tables' ) . '</th>' .
			  '<th class="no-sort">' . esc_html__( 'Categories', 'woo-product-tables' ) . '</th>' .
			  '<th>' . esc_html__( 'SKU', 'woo-product-tables' ) . '</th>' .
			  '<th>' . esc_html__( 'Stock status', 'woo-product-tables' ) . '</th>' .
			  '<th>' . esc_html__( 'Price', 'woo-product-tables' ) . '</th>' .
			  '<th>' . esc_html__( 'Date', 'woo-product-tables' ) . '</th>' .
			  '<th>' . esc_html__( 'Attributes', 'woo-product-tables' ) . '</th>';

		return '<thead><tr>' . $th . '</tr></thead>';
	}

	public function setVendorId( $settings, $params = array() ) {
		if ( ! FrameWtbp::_()->isPro() ) {
			return $settings;
		}

		$showByVendor = $this->getTableSetting( $settings, 'show_products_by_vendor', false );
		if ( $showByVendor && $this->getModule()->isWcmfPluginActivated() && wcfm_is_store_page() ) {
			$author_id                   = get_query_var( 'author' );
			$settings['products_vendor'] = $author_id && wcfm_is_vendor( $author_id ) ? $author_id : 0;
		} elseif ( isset( $params['products_vendor'] ) && $params['products_vendor'] ) {
			$settings['products_vendor'] = $params['products_vendor'];
		}

		return $settings;
	}

	public function getUserProducts( $sync = true ) {

		$args = [
			'customer_id' => get_current_user_id()
		];

		$orders = wc_get_orders( $args );

		$productIds = array();

		foreach ( $orders as $order ) {
			$products = $order->get_items();

			foreach ( $products as $product ) {
				$productIds[] = $product->get_product_id();
			}
		}

		if ( $sync ) {
			$favorites = $this->getFavorites( false );

			if ( ! empty( $favorites ) ) {
				foreach ( $productIds as $key => $product_id ) {
					if ( key_exists( $product_id, $favorites ) && '-1' === $favorites[ $product_id ]['from_order'] ) {
						unset( $productIds[ $key ] );
						unset( $favorites[ $product_id ] );
					}
				}

				if ( ! empty( $favorites ) ) {
					foreach ( $favorites as $products ) {
						$productIds[] = $products['product_id'];
					}
				}
			}
		}

		return ( ! empty( $productIds ) ) ? array_unique( $productIds ) : array( 0 );
	}

	public function getFavorites( $sync = true ) {
		$user_id = get_current_user_id();
		if ( '0' === $user_id ) {
			return array();
		}
		$where        = array(
			'user_id' => $user_id,
		);
		$favorites    = array();
		$favoritesRaw = $this->getModel( 'favorites' )->addWhere( $where )->getFromTbl();

		if ( ! empty( $favoritesRaw ) ) {
			foreach ( $favoritesRaw as $product ) {
				$favorites[ $product['product_id'] ] = $product;
			}
		}

		if ( $sync ) {
			$productIds = $this->getUserProducts( false );

			if ( ! empty( $productIds ) ) {

				foreach ( $productIds as $product_id ) {

					if ( ! key_exists( $product_id, $favorites ) ) {
						$data = array(
							'user_id'    => $user_id,
							'product_id' => $product_id,
							'from_order' => 1,
						);
						$this->getModel( 'favorites' )->insert( $data );
						$favorites[ $product_id ] = $data;
					}

				}

			}
		}

		return $favorites;
	}

}
