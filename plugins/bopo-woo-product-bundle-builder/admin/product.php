<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VI_WOO_BOPO_BUNDLE_Product' ) ) {
	class VI_WOO_BOPO_BUNDLE_Product {
		protected $settings;
		protected static $_instance = null;
		protected static $_types = array(
			'bundle',
			'bopobb',
			'composite',
			'grouped',
			'external'
		);

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		function __construct() {
			$this->settings = VI_WOO_BOPO_BUNDLE_DATA::get_instance();
			// Enqueue backend scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'bopobb_admin_enqueue_scripts' ) );
			add_action( 'admin_print_scripts', array( $this, 'bopobb_custom_script' ) );

			// Rule search
			add_action( 'wp_ajax_bopobb_search_cat', array( $this, 'bopobb_search_cat' ) );
			add_action( 'wp_ajax_bopobb_search_tag', array( $this, 'bopobb_search_tag' ) );
			add_action( 'wp_ajax_bopobb_search_product', array( $this, 'bopobb_search_product' ) );
			add_action( 'wp_ajax_bopobb_default_product', array( $this, 'bopobb_default_product' ) );

			// Add to selector
			add_filter( 'product_type_selector', array( $this, 'bopobb_product_type_selector' ) );

			// Product data tabs
			add_filter( 'woocommerce_product_data_tabs', array( $this, 'bopobb_product_data_tabs' ), 10, 1 );

			// Product filters
			add_filter( 'woocommerce_product_filters', array( $this, 'bopobb_product_filters' ) );

			// Product data panels
			add_action( 'woocommerce_product_data_panels', array( $this, 'bopobb_product_data_panels' ) );
			add_action( 'woocommerce_process_product_meta', array( $this, 'bopobb_delete_option_fields' ) );
			add_action( 'woocommerce_process_product_meta_bopobb', array( $this, 'bopobb_save_option_fields' ) );

			// Product type
			add_filter( 'woocommerce_product_class', array( $this, 'bopobb_product_class' ), 10, 2 );

			add_action( 'edit_form_after_title', array( $this, 'bopobb_shortcode_after_title_detail_filter_menu' ) );
		}

		public function bopobb_product_class( $classname, $product_type ) {
			if ( $product_type == 'bopobb' ) {
				$classname = 'VI_WOO_BOPO_Type';
			}

			return $classname;
		}

		public function bopobb_search_cat() {
			check_ajax_referer( 'bopobb_nonce', 'nonce' );
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			ob_start();

			$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );
			if ( ! $keyword ) {
				$keyword = filter_input( INPUT_POST, 'keyword', FILTER_SANITIZE_STRING );
			}
			if ( empty( $keyword ) ) {
				die();
			}
			$categories = get_terms(
				array(
					'taxonomy' => 'product_cat',
					'orderby'  => 'name',
					'order'    => 'ASC',
					'search'   => $keyword,
					'number'   => 100
				)
			);
			$items      = array();
			if ( count( $categories ) ) {
				foreach ( $categories as $category ) {
					$item    = array(
						'id'   => $category->term_id,
						'text' => $category->name
					);
					$items[] = $item;
				}
			}
			wp_send_json( $items );
		}

		public function bopobb_search_tag() {
			check_ajax_referer( 'bopobb_nonce', 'nonce' );
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			ob_start();

			$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );
			if ( ! $keyword ) {
				$keyword = filter_input( INPUT_POST, 'keyword', FILTER_SANITIZE_STRING );
			}
			if ( empty( $keyword ) ) {
				die();
			}
			$categories = get_terms(
				array(
					'taxonomy' => 'product_tag',
					'orderby'  => 'name',
					'order'    => 'ASC',
					'search'   => $keyword,
					'number'   => 100
				)
			);
			$items      = array();
			if ( count( $categories ) ) {
				foreach ( $categories as $category ) {
					$item    = array(
						'id'   => $category->term_id,
						'text' => $category->name
					);
					$items[] = $item;
				}
			}
			wp_send_json( $items );
		}

		function bopobb_search_product() {
			check_ajax_referer( 'bopobb_nonce', 'nonce' );
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			ob_start();

			$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );

			if ( empty( $keyword ) ) {
				die();
			}
			$arg            = array(
				'post_status'    => 'publish',
				'post_type'      => 'product',
				'posts_per_page' => 50,
				's'              => $keyword

			);
			$the_query      = new WP_Query( $arg );
			$found_products = array();
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$product_id    = get_the_ID();
					$product_title = get_the_title();

					$the_product  = wc_get_product( $product_id );
					$product_type = $the_product->get_type();
					if ( ! in_array( $product_type, $this->settings->get_params( 'bopobb_type_include' ) ) ) {
						continue;
					}
					$product_prefix = ' (' . $product_type . ') ';
					if ( ! $the_product->is_in_stock() ) {
						continue;
					}
					$product          = array(
						'id'   => $product_id,
						'text' => '#' . $product_id . $product_prefix . $product_title
					);
					$found_products[] = $product;

				}
//				wp_reset_postdata();
			}
			wp_send_json( $found_products );
		}

		function bopobb_default_product() {
			check_ajax_referer( 'bopobb_nonce', 'nonce' );
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			ob_start();

			$keyword        = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );
			$cat_search     = filter_input( INPUT_GET, 'cat', FILTER_DEFAULT, FILTER_FORCE_ARRAY );
			$ex_cat_search  = filter_input( INPUT_GET, 'ex_cat', FILTER_DEFAULT, FILTER_FORCE_ARRAY );
			$tag_search     = filter_input( INPUT_GET, 'tag', FILTER_DEFAULT, FILTER_FORCE_ARRAY );
			$ex_tag_search  = filter_input( INPUT_GET, 'ex_tag', FILTER_DEFAULT, FILTER_FORCE_ARRAY );
			$prod_search    = filter_input( INPUT_GET, 'prod', FILTER_DEFAULT, FILTER_FORCE_ARRAY );
			$ex_prod_search = filter_input( INPUT_GET, 'ex_prod', FILTER_DEFAULT, FILTER_FORCE_ARRAY );

			if ( empty( $keyword ) ) {
				die();
			}
			$arg     = array(
				'post_status'    => 'publish',
				'post_type'      => 'product',
				'posts_per_page' => 10,
				's'              => $keyword
			);
			$tax_arr = [];
			if ( ! empty( $cat_search ) ) {
				$tax_arr[] = [
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $cat_search,
				];
			}
			if ( ! empty( $ex_cat_search ) ) {
				$tax_arr[] = [
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $ex_cat_search,
					'operator' => 'NOT IN',
				];
			}
			if ( ! empty( $tag_search ) ) {
				$tax_arr[] = [
					'taxonomy' => 'product_tag',
					'field'    => 'term_id',
					'terms'    => $tag_search,
				];
			}
			if ( ! empty( $ex_tag_search ) ) {
				$tax_arr[] = [
					'taxonomy' => 'product_tag',
					'field'    => 'term_id',
					'terms'    => $ex_tag_search,
					'operator' => 'NOT IN',
				];
			}
			if ( ! empty( $tax_arr ) ) {
				$arg['tax_query'] = $tax_arr;
			}
			if ( ! empty( $prod_search ) ) {
				$arg['post__in'] = $prod_search;
			}
			$the_query      = new WP_Query( $arg );
			$found_products = array();
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$prd = wc_get_product( get_the_ID() );

					if ( ! empty( $ex_prod_search ) ) {
						if ( in_array( strval( $prd->get_id() ), $ex_prod_search, true ) ) {
							continue;
						}
					}

					if ( in_array( $prd->get_type(), self::$_types, true ) ) {
						continue;
					}

					if ( $prd->has_child() && $prd->is_type( 'variable' ) ) {

						$product_children = $prd->get_children();
						$variation_arr    = [];

						if ( count( $product_children ) ) {
							foreach ( $product_children as $product_child ) {
								$child_wc       = wc_get_product( $product_child );
								$product_type   = $child_wc->get_type();
								$product_prefix = ' (' . $product_type . ') ';
								$variation_any  = false;
								$get_atts       = $child_wc->get_variation_attributes();

								if ( $child_wc->get_manage_stock() ) {
									if ( ! $child_wc->is_in_stock() ) {
										continue;
									} else {
										$child_wc_stock = '';
									}
								} else {
									$child_wc_stock = '';
								};

								if ( ! $child_wc->is_in_stock() ) {
									continue;
								} else {
									$child_wc_stock = '';
								}
								foreach ( $get_atts as $attr_k => $attr_v ) {
									if ( empty( $attr_v ) ) {
										$variation_any = true;
									}
								}
								if ( $variation_any ) {
									$attr_name      = '';
									$any_variations = VI_WOO_BOPO_BUNDLE_Helper::bopobb_get_variations( $prd, $product_child, $get_atts, $variation_arr );

									if ( ! empty( $any_variations ) ) {
										$any_to_all    = VI_WOO_BOPO_BUNDLE_Helper::bopobb_set_array( $any_variations, $product_child );
										$variation_arr = array_merge( $variation_arr, $any_to_all );
										foreach ( $any_variations as $any_v ) {
											$key_arr = array_keys( $get_atts );
											if ( is_array( $any_v ) || is_object( $any_v ) ) {
												if ( count( $key_arr ) == count( $any_v ) ) {
													$set_arr           = array_combine( $key_arr, $any_v );
													$product_variation = array(
														'id'   => $product_child . '/' . VI_WOO_BOPO_BUNDLE_Helper::bopobb_build_variations( $set_arr ),
														'text' => '#' . $product_child . $product_prefix . get_the_title() . VI_WOO_BOPO_BUNDLE_Helper::bopobb_build_title( $set_arr ) . $child_wc_stock
													);
												}
											} else {
												$any_v = [ $any_v ];
												if ( count( $key_arr ) == count( $any_v ) ) {
													$set_arr           = array_combine( $key_arr, $any_v );
													$product_variation = array(
														'id'   => $product_child . '/' . VI_WOO_BOPO_BUNDLE_Helper::bopobb_build_variations( $set_arr ),
														'text' => '#' . $product_child . $product_prefix . get_the_title() . VI_WOO_BOPO_BUNDLE_Helper::bopobb_build_title( $set_arr ) . $child_wc_stock
													);
												}
											}
											$found_products[] = $product_variation;
										}
									}
								} else {
									$attr_name          = '';
									$achieve_arr        = [];
									$achieve_arr['id']  = $product_child;
									$is_variation_valid = VI_WOO_BOPO_BUNDLE_Helper::bopobb_is_variation_allow( $prd, $product_child, $get_atts, $variation_arr );
									if ( ! $is_variation_valid ) {
										continue;
									}
									foreach ( $get_atts as $att_k => $att_v ) {
										$cur_key       = substr( $att_k, 10 );
										$cur_term      = get_term_by( 'slug', $att_v, $cur_key );
										$achieve_arr[] = $att_v;
										if ( ! empty( $cur_term ) ) {
											$attr_name .= ' - ' . $cur_term->name;
										}
									}
									$variation_arr     = array_merge( $variation_arr, [ $achieve_arr ] );
									$product_variation = array(
										'id'   => $product_child . '/' . VI_WOO_BOPO_BUNDLE_Helper::bopobb_build_variations( $get_atts ),
										'text' => '#' . $product_child . $product_prefix . get_the_title() . $attr_name . $child_wc_stock
									);
									$found_products[]  = $product_variation;
								}
							}
						}
						continue;
					}
					$product_id     = get_the_ID();
					$product_title  = get_the_title();
					$the_product    = wc_get_product( $product_id );
					$product_type   = $the_product->get_type();
					$product_prefix = ' (' . $product_type . ') ';
					if ( ! $the_product->is_in_stock() ) {
						continue;
					}
					$product_name     = array(
						'id'   => $product_id,
						'text' => '#' . $product_id . $product_prefix . $product_title
					);
					$found_products[] = $product_name;
				}
			}
			wp_send_json( $found_products );
		}

		function bopobb_product_type_selector( $types ) {
			$types['bopobb'] = esc_html__( 'Bopo bundle', 'woo-bopo-bundle' );

			return $types;
		}

		function bopobb_product_data_tabs( $tabs ) {
			$tabs['bopobb'] = array(
				'label'  => esc_html__( 'Bopo Bundle', 'woo-bopo-bundle' ),
				'target' => 'bopobb-settings',
				'class'  => array( 'show_if_bopobb' ),
			);

			return $tabs;
		}

		function bopobb_product_filters( $filters ) {
			$filters = str_replace( 'Bopobb', esc_html__( 'Bopo bundle', 'woo-bopo-bundle' ), $filters );

			return $filters;
		}

		function bopobb_product_data_panels() {
			global $post;
			$post_id       = $post->ID;
			$ids           = '';
			$meta_count    = 0;
			$meta_title    = '';
			$meta_shipping = '';
			$meta_items    = [];

			if ( get_post_meta( $post_id, 'bopobb_title', true ) ) {
				$meta_title = get_post_meta( $post_id, 'bopobb_title', true );
			}
			if ( get_post_meta( $post_id, 'bopobb_shipping_fee', true ) ) {
				$meta_shipping = get_post_meta( $post_id, 'bopobb_shipping_fee', true );
			}

			if ( get_post_meta( $post_id, 'bopobb_count', true ) ) {
				$meta_count = get_post_meta( $post_id, 'bopobb_count', true );
				for ( $index = 0; $index < $meta_count; $index ++ ) {
					if ( get_post_meta( $post_id, 'bopobb_item_' . $index, true ) ) {
						$meta_item = get_post_meta( $post_id, 'bopobb_item_' . $index, true );
						array_push( $meta_items, $meta_item );
					}
				}
			}
			?>
            <div id='bopobb-settings' class='panel woocommerce_options_panel wc-metaboxes-wrapper bopobb-product-panel'>
                <div class="bopobb-bundle-settings">
                    <div class="bopobb-title-contain">
                        <div class="bopobb-title-label">
							<?php
							esc_html_e( 'Bundle title:', 'woo-bopo-bundle' );
							?>
                        </div>
                        <div class="bopobb-title">
                            <input aria-label="<?php esc_attr_e( 'Custom title', 'woo-bopo-bundle' ); ?>" type="text"
                                   id="bopobb_title" name="bopobb_title"
                                   placeholder="<?php esc_attr_e( 'A bundle by bopo', 'woo-bopo-bundle' ); ?>"
                                   value="<?php echo esc_attr( $meta_title ); ?>">
                        </div>
                        <input type="text" id="bopobb_count" name="bopobb_count" value="2" readonly
                               placeholder="Add item">
                    </div>
                    <div class="bopobb-shipping-contain">
                        <div class="bopobb-shipping-label">
							<?php
							esc_html_e( 'Shipping fee:', 'woo-bopo-bundle' );
							?>
                        </div>
                        <div class="bopobb-shipping">
                            <select class="bopobb-shipping-fee" id="bopobb_shipping_fee" name="bopobb_shipping_fee">
                                <option <?php if ( ! empty( $meta_shipping ) && $meta_shipping == 'each' )
									echo esc_attr( 'selected' ) ?>
                                        value="each"><?php esc_html_e( 'Apply to each bundled product', 'woo-bopo-bundle' ) ?></option>
                                <option <?php if ( ! empty( $meta_shipping ) && $meta_shipping == 'both' )
									echo esc_attr( 'selected' ) ?>
                                        value="both"><?php esc_html_e( 'Apply to the whole bundle', 'woo-bopo-bundle' ) ?></option>
                            </select>
                        </div>
                        <input type="text" id="bopobb_count" name="bopobb_count" value="2" readonly
                               placeholder="Add item">
                    </div>
                    <div class="bopobb-pbi-contain wc-metaboxes ui-sortable">
						<?php
						if ( $meta_count !== 0 ) {
							$this->bopobb_load_item( $meta_items, $meta_count );
						} else {
							$this->bopobb_load_item( '' );
						}
						?>
                    </div>
                </div>
            </div>
			<?php
		}

		function bopobb_load_item( $data = '', $count = 2 ) {
			for ( $index = 0; $index < $count; $index ++ ) {
				?>
                <div class="bopobb-pbi wc-metabox closed ui-sortable-handle">
                    <h3 class="bopobb-pbi-anchor">
                        <strong class="bopobb-pbi-item-title"><?php esc_html_e( 'Bundle item ' . ( $index + 1 ), 'woo-bopo-bundle' ); ?></strong>
                        <div class="bopobb-pbi-slide-arrow dashicons dashicons-arrow-down-alt2"
                             title="Click to toggle"></div>
                        <input type="text" class="bopobb-pbi-index" id="bopobb_index_<?php echo esc_attr( $index ) ?>"
                               name="bopobb_index_<?php echo esc_attr( $index ) ?>"
                               value="<?php echo esc_attr( $index ) ?>" placeholder="Item index">
                    </h3>
                    <div class="bopobb-pbi-data wc-metabox-content" style="">
                        <table>
                            <tbody>
                            <tr class="bopobb-pbi-tab-label">
                                <th><?php esc_html_e( 'Rule', 'woo-bopo-bundle' ); ?></th>
                                <td></td>
                            </tr>
                            <tr class="">
                                <th><?php esc_html_e( 'Categories', 'woo-bopo-bundle' ); ?></th>
                                <td>
                                    <div class="bopobb-pbi-input">
                                        <select class="bopobb-pbi-category bopobb-pbi-category-search"
                                                multiple="multiple"
                                                name="bopobb_pbi_category_<?php echo esc_attr( $index ) ?>[]"
                                                id="bopobb_pbi_category_<?php echo esc_attr( $index ) ?>"
                                                data-placeholder="<?php esc_html_e( 'Please Fill In Your Category', 'woo-bopo-bundle' ) ?>">
											<?php
											if ( ! empty( $data ) && ! empty( $data[ $index ]['bopobb_pbi_category'] ) ) {
												$cat_ids = $data[ $index ]['bopobb_pbi_category'];
												if ( count( $cat_ids ) ) {
													foreach ( $cat_ids as $ps ) {
														$term = get_term_by( 'id', $ps, 'product_cat', 'ARRAY_A' );
														if ( $term ) {
															?>
                                                            <option selected
                                                                    value="<?php echo esc_attr( $ps ) ?>"><?php echo esc_attr( $term['name'] ) ?></option>
															<?php
														}
													}
												}
											}
											?>
                                        </select>
                                    </div>
                                    <span class="woocommerce-help-tip" data-tip="<?php
									esc_attr_e( 'Product with categories will be applied to this bundle item.', 'woo-bopo-bundle' ) ?>">
                                                    </span>
                                </td>
                            </tr>
                            <tr class="">
                                <th><?php esc_html_e( 'Exclude categories', 'woo-bopo-bundle' ); ?></th>
                                <td>
                                    <div class="bopobb-pbi-input">
                                        <select class="bopobb-pbi-category-exclude bopobb-pbi-category-search"
                                                multiple="multiple"
                                                name="bopobb_pbi_category_exclude_<?php echo esc_attr( $index ) ?>[]"
                                                id="bopobb_pbi_category_exclude_<?php echo esc_attr( $index ) ?>"
                                                data-placeholder="<?php esc_html_e( 'Please Fill In Your Category', 'woo-bopo-bundle' ) ?>">
											<?php
											if ( ! empty( $data ) && ! empty( $data[ $index ]['bopobb_pbi_category_exclude'] ) ) {
												$cat_ids = $data[ $index ]['bopobb_pbi_category_exclude'];
												if ( count( $cat_ids ) ) {
													foreach ( $cat_ids as $ps ) {
														$term = get_term_by( 'id', $ps, 'product_cat', 'ARRAY_A' );
														if ( $term ) {
															?>
                                                            <option selected
                                                                    value="<?php echo esc_attr( $ps ) ?>"><?php echo esc_attr( $term['name'] ) ?></option>
															<?php
														}
													}
												}
											}
											?>
                                        </select>
                                    </div>
                                    <span class="woocommerce-help-tip" data-tip="<?php
									esc_attr_e( 'Product with categories will not be applied to this bundle item.', 'woo-bopo-bundle' ) ?>">
                                                    </span>
                                </td>
                            </tr>
                            <tr class="">
                                <th><?php esc_html_e( 'Tags', 'woo-bopo-bundle' ); ?></th>
                                <td>
                                    <div class="bopobb-pbi-input">
                                        <select class="bopobb-pbi-tag bopobb-pbi-tag-search" multiple="multiple"
                                                name="bopobb_pbi_tag_<?php echo esc_attr( $index ) ?>[]"
                                                id="bopobb_pbi_tag_<?php echo esc_attr( $index ) ?>"
                                                data-placeholder="<?php esc_html_e( 'Please Fill In Your Tag', 'woo-bopo-bundle' ) ?>">
											<?php
											if ( ! empty( $data ) && ! empty( $data[ $index ]['bopobb_pbi_tag'] ) ) {
												$tag_ids = $data[ $index ]['bopobb_pbi_tag'];
												if ( count( $tag_ids ) ) {
													foreach ( $tag_ids as $ps ) {
														$term = get_term_by( 'id', $ps, 'product_tag', 'ARRAY_A' );
														if ( $term ) {
															?>
                                                            <option selected
                                                                    value="<?php echo esc_attr( $ps ) ?>"><?php echo esc_attr( $term['name'] ) ?></option>
															<?php
														}
													}
												}
											}
											?>
                                        </select>
                                    </div>
                                    <span class="woocommerce-help-tip" data-tip="<?php
									esc_attr_e( 'Product with tags will be applied to this bundle item.', 'woo-bopo-bundle' ) ?>">
                                                    </span>
                                </td>
                            </tr>
                            <tr class="">
                                <th><?php esc_html_e( 'Exclude tags', 'woo-bopo-bundle' ); ?></th>
                                <td>
                                    <div class="bopobb-pbi-input">
                                        <select class="bopobb-pbi-tag-exclude bopobb-pbi-tag-search" multiple="multiple"
                                                name="bopobb_pbi_tag_exclude_<?php echo esc_attr( $index ) ?>[]"
                                                id="bopobb_pbi_tag_exclude_<?php echo esc_attr( $index ) ?>"
                                                data-placeholder="<?php esc_html_e( 'Please Fill In Your Tag', 'woo-bopo-bundle' ) ?>">
											<?php
											if ( ! empty( $data ) && ! empty( $data[ $index ]['bopobb_pbi_tag_exclude'] ) ) {
												$tag_ids = $data[ $index ]['bopobb_pbi_tag_exclude'];
												if ( count( $tag_ids ) ) {
													foreach ( $tag_ids as $ps ) {
														$term = get_term_by( 'id', $ps, 'product_tag', 'ARRAY_A' );
														if ( $term ) {
															?>
                                                            <option selected
                                                                    value="<?php echo esc_attr( $ps ) ?>"><?php echo esc_attr( $term['name'] ) ?></option>
															<?php
														}
													}
												}
											}
											?>
                                        </select>
                                    </div>
                                    <span class="woocommerce-help-tip" data-tip="<?php
									esc_attr_e( 'Product with tags will not be applied to this bundle item.', 'woo-bopo-bundle' ) ?>">
                                                    </span>
                                </td>
                            </tr>
                            <tr class="">
                                <th><?php esc_html_e( 'Product', 'woo-bopo-bundle' ); ?></th>
                                <td>
                                    <div class="bopobb-pbi-input">
                                        <select class="bopobb-pbi-title bopobb-pbi-product-search" multiple="multiple"
                                                name="bopobb_pbi_title_<?php echo esc_attr( $index ) ?>[]"
                                                id="bopobb_pbi_title_<?php echo esc_attr( $index ) ?>"
                                                data-placeholder="<?php esc_html_e( 'Please Fill In Your Product Title', 'woo-bopo-bundle' ) ?>">
											<?php
											if ( ! empty( $data ) && ! empty( $data[ $index ]['bopobb_pbi_title'] ) ) {
												$product_ids = $data[ $index ]['bopobb_pbi_title'];
												if ( count( $product_ids ) ) {
													foreach ( $product_ids as $ps ) {
														$product_in = wc_get_product( $ps );
														if ( ! $product_in->is_purchasable() || 'publish' !== $product_in->get_status() ) {
															continue;
														}
														$product_prefix   = ' (' . $product_in->get_type() . ') ';
														$product_in_title = '#' . $ps . $product_prefix . $product_in->get_title();
														if ( $product_in ) {
															?>
                                                            <option selected
                                                                    value="<?php echo esc_attr( $ps ) ?>"><?php echo esc_attr( $product_in_title ) ?></option>
															<?php
														}
													}
												}
											}
											?>
                                        </select>
                                    </div>
                                    <span class="woocommerce-help-tip" data-tip="<?php
									esc_attr_e( 'Product with title will be applied to this bundle item.', 'woo-bopo-bundle' ) ?>">
                                                    </span>
                                </td>
                            </tr>
                            <tr class="">
                                <th><?php esc_html_e( 'Exclude product', 'woo-bopo-bundle' ); ?></th>
                                <td>
                                    <div class="bopobb-pbi-input">
                                        <select class="bopobb-pbi-title-exclude bopobb-pbi-product-search"
                                                multiple="multiple"
                                                name="bopobb_pbi_title_exclude_<?php echo esc_attr( $index ) ?>[]"
                                                id="bopobb_pbi_title_exclude_<?php echo esc_attr( $index ) ?>"
                                                data-placeholder="<?php esc_html_e( 'Please Fill In Your Product Title', 'woo-bopo-bundle' ) ?>">
											<?php
											if ( ! empty( $data ) && ! empty( $data[ $index ]['bopobb_pbi_title_exclude'] ) ) {
												$product_ids = $data[ $index ]['bopobb_pbi_title_exclude'];
												if ( count( $product_ids ) ) {
													foreach ( $product_ids as $ps ) {
														$product_ex = wc_get_product( $ps );
														if ( ! $product_ex->is_purchasable() || 'publish' !== $product_ex->get_status() ) {
															continue;
														}
														$product_prefix   = ' (' . $product_ex->get_type() . ') ';
														$product_ex_title = '#' . $ps . $product_prefix . $product_ex->get_title();
														if ( $product_ex ) {
															?>
                                                            <option selected
                                                                    value="<?php echo esc_attr( $ps ) ?>"><?php echo esc_attr( $product_ex_title ) ?></option>
															<?php
														}
													}
												}
											}
											?>
                                        </select>
                                    </div>
                                    <span class="woocommerce-help-tip" data-tip="<?php
									esc_attr_e( 'Product with title will not be applied to this bundle item.', 'woo-bopo-bundle' ) ?>">
                                                    </span>
                                </td>
                            </tr>
                            <tr class="bopobb-pbi-tab-label">
                                <th><?php esc_html_e( 'Display', 'woo-bopo-bundle' ); ?></th>
                                <td></td>
                            </tr>
                            <tr class="">
                                <th><?php esc_html_e( 'Sort', 'woo-bopo-bundle' ); ?></th>
                                <td>
                                    <div class="bopobb-pbi-input">
                                        <select class="vi-ui fluid dropdown bopo-pbi-input-part"
                                                id="bopobb_bpi_sort_<?php echo esc_attr( $index ) ?>"
                                                name="bopobb_bpi_sort_<?php echo esc_attr( $index ) ?>">
                                            <option <?php if ( ! empty( $data ) && $data[ $index ]['bopobb_bpi_sort'] == 'price' )
												echo esc_attr( 'selected' ) ?>
                                                    value="price"><?php esc_html_e( 'Price', 'woo-bopo-bundle' ) ?></option>
                                            <option <?php if ( ! empty( $data ) && $data[ $index ]['bopobb_bpi_sort'] == 'title' )
												echo esc_attr( 'selected' ) ?>
                                                    value="title"><?php esc_html_e( 'Title', 'woo-bopo-bundle' ) ?></option>
                                            <option <?php if ( ! empty( $data ) && $data[ $index ]['bopobb_bpi_sort'] == 'ratting' )
												echo esc_attr( 'selected' ) ?>
                                                    value="ratting"><?php esc_html_e( 'Rating', 'woo-bopo-bundle' ) ?></option>
                                        </select>
                                        <select class="vi-ui fluid dropdown bopo-pbi-input-part"
                                                id="bopobb_bpi_order_<?php echo esc_attr( $index ) ?>"
                                                name="bopobb_bpi_order_<?php echo esc_attr( $index ) ?>">
                                            <option <?php if ( ! empty( $data ) && $data[ $index ]['bopobb_bpi_order'] == 'ASC' )
												echo esc_attr( 'selected' ) ?>
                                                    value="ASC"><?php esc_html_e( 'ASC', 'woo-bopo-bundle' ) ?></option>
                                            <option <?php if ( ! empty( $data ) && $data[ $index ]['bopobb_bpi_order'] == 'DESC' )
												echo esc_attr( 'selected' ) ?>
                                                    value="DESC"><?php esc_html_e( 'DESC', 'woo-bopo-bundle' ) ?></option>
                                        </select>
                                    </div>
                                    <span class="woocommerce-help-tip" data-tip="<?php
									esc_attr_e( 'This field allows you to set the order of product in bundle select popup', 'woo-bopo-bundle' ) ?>">
                                                    </span>
                                </td>
                            </tr>
                            <tr class="">
                                <th><?php esc_html_e( 'Default', 'woo-bopo-bundle' ); ?></th>
                                <td>
                                    <div class="bopobb-pbi-input">
                                        <div class="bopo-pbi-input-checkbox">
                                            <input class="checkbox"
                                                   id="bopobb_bpi_set_default_<?php echo esc_attr( $index ) ?>"
                                                   name="bopobb_bpi_set_default_<?php echo esc_attr( $index ) ?>"
                                                   type="checkbox" <?php if ( ! empty( $data ) && $data[ $index ]['bopobb_bpi_set_default'] )
												echo esc_attr( 'checked=checked' ) ?>>
                                            <span class="woocommerce-help-tip" data-tip="<?php
											esc_attr_e( 'Check this field to set the default product of current item', 'woo-bopo-bundle' ) ?>">
                                                    </span>
                                        </div>
                                        <div class="bopobb-bpi-default-product-wrap">
                                            <select class="bopobb-bpi-default-product bopobb-pbi-default-search"
                                                    id="bopobb_bpi_default_product_<?php echo esc_attr( $index ) ?>"
                                                    name="bopobb_bpi_default_product_<?php echo esc_attr( $index ) ?>"
                                                    data-placeholder="<?php esc_html_e( 'Please Fill In Your Title', 'woo-bopo-bundle' ) ?>">
												<?php
												if ( ! empty( $data ) && ! empty( $data[ $index ]['bopobb_bpi_default_product'] ) ) {
													$variation_title = '';
													$id_array        = explode( '/', $data[ $index ]['bopobb_bpi_default_product'] );
													if ( count( $id_array ) > 1 ) {
														$variation_title .= VI_WOO_BOPO_BUNDLE_Helper::bopobb_decode_variations( $id_array[1], 1 );
													}
													$product_def    = wc_get_product( $id_array[0] );
													$product_prefix = ' (' . $product_def->get_type() . ') ';
													if ( ! $product_def->is_purchasable() || 'publish' !== $product_def->get_status() ) {
														$product_def = '';
													} else {
														$product_title = '#' . $id_array[0] . $product_prefix . $product_def->get_title() . $variation_title;
													}
													if ( $product_def ) {
														?>
                                                        <option selected
                                                                value="<?php echo esc_attr( $data[ $index ]['bopobb_bpi_default_product'] ) ?>"><?php echo esc_attr( $product_title ) ?></option>
														<?php
													}
												}
												?>
                                            </select>
                                        </div>
                                    </div>
                                    <span class="woocommerce-help-tip" data-tip="<?php
									esc_attr_e( 'Search product to set the default product of current item, if this item not set and checkbox checked, default is the first product in list', 'woo-bopo-bundle' ) ?>">
                                                    </span>
                                </td>
                            </tr>
                            <tr class="">
                                <th><?php esc_html_e( 'Quantity', 'woo-bopo-bundle' ); ?></th>
                                <td>
                                    <div class="bopobb-pbi-input">
                                        <input type="number" id="bopobb_bpi_quantity_<?php echo esc_attr( $index ) ?>"
                                               min="1"
                                               name="bopobb_bpi_quantity_<?php echo esc_attr( $index ) ?>"
                                               value="<?php echo esc_attr( isset( $data[ $index ]['bopobb_bpi_quantity'] ) ? $data[ $index ]['bopobb_bpi_quantity'] : 1 ) ?>">
                                    </div>
                                    <span class="woocommerce-help-tip" data-tip="<?php
									esc_attr_e( 'This field allows you to set the quantity of product to current item', 'woo-bopo-bundle' ) ?>">
                                                    </span>
                                </td>
                            </tr>
                            <tr class="bopobb-pbi-tab-label">
                                <th><?php esc_html_e( 'Discount', 'woo-bopo-bundle' ); ?></th>
                                <td>
                                    <div class="bopobb-pbi-input">
                                        <select class="vi-ui fluid dropdown bopo-pbi-input-part"
                                                id="bopobb_bpi_discount_<?php echo esc_attr( $index ) ?>"
                                                name="bopobb_bpi_discount_<?php echo esc_attr( $index ) ?>">
                                            <option <?php if ( ! empty( $data ) && $data[ $index ]['bopobb_bpi_discount'] == '0' )
												echo esc_attr( 'selected' ) ?>
                                                    value="0"><?php esc_html_e( 'Percent of total (%)', 'woo-bopo-bundle' ) ?></option>
                                            <option <?php if ( ! empty( $data ) && $data[ $index ]['bopobb_bpi_discount'] == '1' )
												echo esc_attr( 'selected' ) ?>
                                                    value="1"><?php esc_html_e( 'Fixed price (' . get_woocommerce_currency_symbol() . ')', 'woo-bopo-bundle' ) ?></option>
                                        </select>
                                        <input type="number" class="bopo-pbi-input-part"
                                               id="bopobb_bpi_discount_number_<?php echo esc_attr( $index ) ?>" min="0"
                                               value="<?php if ( ! empty( $data ) && isset( $data[ $index ]['bopobb_bpi_discount_number'] ) )
											       echo esc_attr( $data[ $index ]['bopobb_bpi_discount_number'] ) ?>"
                                               name="bopobb_bpi_discount_number_<?php echo esc_attr( $index ) ?>">
                                    </div>
                                    <span class="woocommerce-help-tip" data-tip="<?php
									esc_attr_e( 'This field allows you to set the discount of current item', 'woo-bopo-bundle' ) ?>">
                                                    </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
				<?php
			}
		}

		function bopobb_save_option_fields( $post_id ) {
			if ( ! current_user_can( "edit_post", $post_id ) ) {
				return $post_id;
			}
			if ( isset( $_POST['product-type'] ) && ( $_POST['product-type'] == 'bopobb' ) ) {
				if ( isset( $_POST['bopobb_count'] ) && ! empty( $_POST['bopobb_count'] ) ) {
					update_post_meta( $post_id, 'bopobb_count', ( absint( $_POST['bopobb_count'] ) ) );
					if ( isset( $_POST['bopobb_title'] ) && ! empty( $_POST['bopobb_title'] ) ) {
						update_post_meta( $post_id, 'bopobb_title', ( sanitize_text_field( $_POST['bopobb_title'] ) ) );
					} else {
						update_post_meta( $post_id, 'bopobb_title', '' );
					}
					if ( isset( $_POST['bopobb_shipping_fee'] ) && ! empty( $_POST['bopobb_shipping_fee'] ) ) {
						update_post_meta( $post_id, 'bopobb_shipping_fee', ( sanitize_text_field( $_POST['bopobb_shipping_fee'] ) ) );
					}
					for ( $i = 0; $i < absint( $_POST['bopobb_count'] ); $i ++ ) {
						$item_bundle = [];
						if ( isset( $_POST[ 'bopobb_index_' . $i ] ) ) {
							$item_bundle['bopobb_index'] = absint( $_POST[ 'bopobb_index_' . $i ] );
						}
						if ( isset( $_POST[ 'bopobb_pbi_category_' . $i ] ) && ! empty( $_POST[ 'bopobb_pbi_category_' . $i ] ) ) {
							$item_bundle['bopobb_pbi_category'] = array_map( 'esc_attr', $_POST[ 'bopobb_pbi_category_' . $i ] );
						}
						if ( isset( $_POST[ 'bopobb_pbi_category_exclude_' . $i ] ) && ! empty( $_POST[ 'bopobb_pbi_category_exclude_' . $i ] ) ) {
							$item_bundle['bopobb_pbi_category_exclude'] = array_map( 'esc_attr', $_POST[ 'bopobb_pbi_category_exclude_' . $i ] );
						}
						if ( isset( $_POST[ 'bopobb_pbi_tag_' . $i ] ) && ! empty( $_POST[ 'bopobb_pbi_tag_' . $i ] ) ) {
							$item_bundle['bopobb_pbi_tag'] = array_map( 'esc_attr', $_POST[ 'bopobb_pbi_tag_' . $i ] );
						}
						if ( isset( $_POST[ 'bopobb_pbi_tag_exclude_' . $i ] ) && ! empty( $_POST[ 'bopobb_pbi_tag_exclude_' . $i ] ) ) {
							$item_bundle['bopobb_pbi_tag_exclude'] = array_map( 'esc_attr', $_POST[ 'bopobb_pbi_tag_exclude_' . $i ] );
						}
						if ( isset( $_POST[ 'bopobb_pbi_title_' . $i ] ) && ! empty( $_POST[ 'bopobb_pbi_title_' . $i ] ) ) {
							$item_bundle['bopobb_pbi_title'] = array_map( 'esc_attr', $_POST[ 'bopobb_pbi_title_' . $i ] );
						}
						if ( isset( $_POST[ 'bopobb_pbi_title_exclude_' . $i ] ) && ! empty( $_POST[ 'bopobb_pbi_title_exclude_' . $i ] ) ) {
							$item_bundle['bopobb_pbi_title_exclude'] = array_map( 'esc_attr', $_POST[ 'bopobb_pbi_title_exclude_' . $i ] );
						}
						if ( isset( $_POST[ 'bopobb_bpi_sort_' . $i ] ) && ! empty( $_POST[ 'bopobb_bpi_sort_' . $i ] ) ) {
							$item_bundle['bopobb_bpi_sort'] = sanitize_text_field( $_POST[ 'bopobb_bpi_sort_' . $i ] );
						}
						if ( isset( $_POST[ 'bopobb_bpi_order_' . $i ] ) && ! empty( $_POST[ 'bopobb_bpi_order_' . $i ] ) ) {
							$item_bundle['bopobb_bpi_order'] = sanitize_text_field( $_POST[ 'bopobb_bpi_order_' . $i ] );
						}
						if ( isset( $_POST[ 'bopobb_bpi_set_default_' . $i ] ) ) {
							$item_bundle['bopobb_bpi_set_default'] = 1;
						} else {
							$item_bundle['bopobb_bpi_set_default'] = 0;
						}
						if ( isset( $_POST[ 'bopobb_bpi_default_product_' . $i ] ) && ! empty( $_POST[ 'bopobb_bpi_default_product_' . $i ] ) ) {
							$item_bundle['bopobb_bpi_default_product'] = sanitize_text_field( $_POST[ 'bopobb_bpi_default_product_' . $i ] );
						}
						if ( isset( $_POST[ 'bopobb_bpi_quantity_' . $i ] ) && ! empty( $_POST[ 'bopobb_bpi_quantity_' . $i ] ) ) {
							$item_bundle['bopobb_bpi_quantity'] = absint( $_POST[ 'bopobb_bpi_quantity_' . $i ] );
						} else {
							$item_bundle['bopobb_bpi_quantity'] = absint( 1 );
						}
						if ( isset( $_POST[ 'bopobb_bpi_discount_' . $i ] ) ) {
							$item_bundle['bopobb_bpi_discount'] = absint( $_POST[ 'bopobb_bpi_discount_' . $i ] );
						}
						if ( isset( $_POST[ 'bopobb_bpi_discount_number_' . $i ] ) && ! empty( $_POST[ 'bopobb_bpi_discount_number_' . $i ] ) ) {
							$item_bundle['bopobb_bpi_discount_number'] = floatval( $_POST[ 'bopobb_bpi_discount_number_' . $i ] );
						} else {
							$item_bundle['bopobb_bpi_discount_number'] = 0;
						}
						update_post_meta( $post_id, 'bopobb_item_' . absint( $_POST[ 'bopobb_index_' . $i ] ), ( $item_bundle ) );
					}
				}
			}
		}

		function bopobb_delete_option_fields( $post_id ) {

			if ( isset( $_POST['product-type'] ) && ( $_POST['product-type'] !== 'bopobb' ) ) {
				$ib_count = get_post_meta( $post_id, 'bopobb_count' );
				if ( ! empty( $ib_count ) && isset( $ib_count ) ) {
					for ( $i = 0; $i <= intval( $ib_count ); $i ++ ) {
						delete_post_meta( $post_id, 'bopobb_item_' . $i );
					}
					delete_post_meta( $post_id, 'bopobb_count' );
					delete_post_meta( $post_id, 'bopobb_title' );
					delete_post_meta( $post_id, 'bopobb_shipping_fee' );
				}
			}
		}

		public function bopobb_shortcode_after_title_detail_filter_menu() {
			global $post;
			$post_id  = $post->ID;
			$_product = wc_get_product( $post_id );
			if ( $post->post_type != 'product' ) {
				return;
			}
			if ( ! $_product->is_type( 'bopobb' ) ) {
				return;
			}

			?>
            <div class="inside">

                <div class="bopobb-after-title-shortcode" id="bopobb-after-title-shortcode">
                    <div class="vi-ui left labeled icon input fluid">
                        <label class="vi-ui label"
                               for="bopobb_shortcode_show"><?php esc_html_e( 'Shortcode:', 'woo-bopo-bundle' ); ?></label>
                        <input type="text" id="bopobb_shortcode_show" class="bopobb_shortcode_show" readonly
                               value="[bopobb_bundle id=<?php echo "'" . esc_attr( $post->ID ) . "'"; ?>]">
                        <i class="copy icon"></i>
                        <span class="bopobb_copy_tooltip"
                              style=""><?php esc_html_e( 'Copied', 'woo-bopo-bundle' ); ?></span>
                    </div>
                </div>
            </div>
			<?php
		}

		function bopobb_admin_enqueue_scripts() {
			$screen = get_current_screen();
			if ( get_post_type() == 'product' && $screen->id == 'product' ) {
				wp_enqueue_style( 'dashicons' );
				wp_enqueue_style( 'woo-bopo-bundle-select2-css', VI_WOO_BOPO_BUNDLE_CSS . 'select2.min.css' );
				wp_enqueue_script( 'jquery-ui-sortable' );
				if ( WP_DEBUG ) {
					wp_enqueue_style( 'woo-bopo-bundle-backend-css', VI_WOO_BOPO_BUNDLE_CSS . 'bopo-settings.css' );
					wp_enqueue_script( 'woo-bopo-bundle-product-js', VI_WOO_BOPO_BUNDLE_JS . 'bopo-product.js', array( 'jquery' ), VI_WOO_BOPO_BUNDLE_JS );
				} else {
					wp_enqueue_style( 'woo-bopo-bundle-backend-css', VI_WOO_BOPO_BUNDLE_CSS . 'bopo-settings.min.css' );
					wp_enqueue_script( 'woo-bopo-bundle-product-js', VI_WOO_BOPO_BUNDLE_JS . 'bopo-product.min.js', array( 'jquery' ), VI_WOO_BOPO_BUNDLE_JS );
				}
				if ( is_plugin_active( 'hub-core/landing-hub-core.php' ) ) {
					if ( wp_script_is('select2-js') ) {
						wp_deregister_script( 'select2-js' );
						wp_dequeue_script('select2-js');
						wp_dequeue_style('select2-js');
					}
				}
				wp_enqueue_script( 'woo-bopo-bundle-select2-js', VI_WOO_BOPO_BUNDLE_JS . 'select2.js', array( 'jquery' ) );
				wp_localize_script( 'woo-bopo-bundle-product-js', 'bopobbProductVars', array(
						'bopobb_nonce'                 => wp_create_nonce( 'bopobb_nonce' ),
					)
				);
			}
		}

		function bopobb_custom_script() {
			$screen = get_current_screen();

			if ( ! $screen ) {
				return;
			}
			switch ( $screen->id ) {
				case 'product':
					$this->bopobb_create_product_tutorial();
					break;
			}
		}

		public function bopobb_create_product_tutorial() {
			if ( isset( $_GET['product_type'] ) || ! current_user_can( 'manage_options' ) ) {
				$script = 'var bopobb_get_type = "' . sanitize_text_field( $_GET['product_type'] ) . '"'; ?>
                <script type="text/javascript" data-cfasync="false">
					<?php echo $script; ?>
                </script>
				<?php
			}

			return;
		}
	}
}