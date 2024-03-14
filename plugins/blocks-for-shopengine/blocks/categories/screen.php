<?php
defined('ABSPATH') || exit;
$product = \ShopEngine\Widgets\Products::instance()->get_product(get_post_type());

?>

<div class="shopengine shopengine-widget">
	<div class="shopengine-categories">

		<?php
		global $wp_query, $post;

		$title = isset($settings['shopengine_product_categories_title']['desktop']) ? $settings['shopengine_product_categories_title']['desktop'] : 'Product categories';
		$orderby = isset($settings['shopengine_product_categories_orderby']['desktop']) ? $settings['shopengine_product_categories_orderby']['desktop'] : 'name';
		$dropdown = isset($settings['shopengine_product_categories_dropdown']['desktop']) ? $settings['shopengine_product_categories_dropdown']['desktop'] : '';
		$count = isset($settings['shopengine_product_categories_count']['desktop']) ? $settings['shopengine_product_categories_count']['desktop'] : '';
		$hierarchical = isset($settings['shopengine_product_categories_hierarchical']['desktop']) ? $settings['shopengine_product_categories_hierarchical']['desktop'] : true;
		$show_parent_only = isset($settings['shopengine_product_categories_show_parent_only']['desktop']) ? $settings['shopengine_product_categories_show_parent_only']['desktop'] : '';
		$hide_empty = isset($settings['shopengine_product_categories_hide_empty']['desktop']) ? $settings['shopengine_product_categories_hide_empty']['desktop'] : false;
		$depth = isset($settings['shopengine_product_categories_max_depth']['desktop']) ? $settings['shopengine_product_categories_max_depth']['desktop'] : 0;

		if ($title) {
			printf('<h2>%s</h2>', esc_html($title));
		}

		$dropdown_args = [
			'hide_empty' => $hide_empty,
		];
		$list_args = [
			'show_count'   => $count,
			'hierarchical' => $hierarchical,
			'taxonomy'     => 'product_cat',
			'hide_empty'   => $hide_empty,
		];
		$max_depth = absint($depth);

		$list_args['menu_order'] = false;
		$dropdown_args['depth'] = $max_depth;
		$list_args['depth'] = $max_depth;

		if ('order' === $orderby) {
			$list_args['orderby'] = 'meta_value_num';
			$dropdown_args['orderby'] = 'meta_value_num';
			$list_args['meta_key'] = 'order';
			$dropdown_args['meta_key'] = 'order';
		}

		$list_args['title_li'] = '';
		$dropdown_args['title_li'] = '';

		$current_cat = false;
		$cat_ancestors = [];

		if (is_tax('product_cat')) {
			$current_cat = $wp_query->queried_object;
			$cat_ancestors = get_ancestors($current_cat->term_id, 'product_cat');
		} elseif (get_post_type($post->ID === 'product')) {
			$terms = wc_get_product_terms(
				$post->ID,
				'product_cat',
				apply_filters(
					'woocommerce_product_categories_widget_product_terms_args',
					[
						'orderby' => 'parent',
						'order'   => 'DESC',
					]
				)
			);

			if ($terms) {
				$main_term = apply_filters('woocommerce_product_categories_widget_main_term', $terms[0], $terms);
				$current_cat = $main_term;
				$cat_ancestors = get_ancestors($main_term->term_id, 'product_cat');
			}
		}

		// Show Siblings and Children Only.
		if ($show_parent_only && $current_cat) {
			if ($hierarchical) {
				$include = array_merge(
					$cat_ancestors,
					[$current_cat->term_id],
					get_terms(
						'product_cat',
						[
							'fields'       => 'ids',
							'parent'       => 0,
							'hierarchical' => true,
							'hide_empty'   => false,
						]
					),
					get_terms(
						'product_cat',
						[
							'fields'       => 'ids',
							'parent'       => $current_cat->term_id,
							'hierarchical' => true,
							'hide_empty'   => false,
						]
					)
				);
				// Gather siblings of ancestors.
				if ($cat_ancestors) {
					foreach ($cat_ancestors as $ancestor) {
						$include = array_merge(
							$include,
							get_terms(
								'product_cat',
								[
									'fields'       => 'ids',
									'parent'       => $ancestor,
									'hierarchical' => false,
									'hide_empty'   => false,
								]
							)
						);
					}
				}
			} else {
				// Direct children.
				$include = get_terms(
					'product_cat',
					[
						'fields'       => 'ids',
						'parent'       => $current_cat->term_id,
						'hierarchical' => true,
						'hide_empty'   => false,
					]
				);
			}

			$list_args['include'] = implode(',', $include);
			$dropdown_args['include'] = $list_args['include'];

			if (empty($include)) {
				return;
			}
		} elseif ($show_parent_only) {
			$dropdown_args['depth'] = 1;
			$dropdown_args['child_of'] = 0;
			$dropdown_args['hierarchical'] = 1;
			$list_args['depth'] = 1;
			$list_args['child_of'] = 0;
			$list_args['hierarchical'] = 1;
		}

		if ($dropdown) {
			wc_product_dropdown_categories(
				apply_filters(
					'woocommerce_product_categories_widget_dropdown_args',
					wp_parse_args(
						$dropdown_args,
						[
							'show_count'         => $count,
							'hierarchical'       => $hierarchical,
							'show_uncategorized' => 0,
							'selected'           => $current_cat ? $current_cat->slug : '',
						]
					)
				)
			);

			wp_enqueue_script('selectWoo');
			wp_enqueue_style('select2');

			wc_enqueue_js(
				"
				jQuery( '.dropdown_product_cat' ).change( function() {
					if ( jQuery(this).val() != '' ) {
						var this_page = '';
						var home_url  = '" . esc_js(home_url('/')) . "';
						if ( home_url.indexOf( '?' ) > 0 ) {
							this_page = home_url + '&product_cat=' + jQuery(this).val();
						} else {
							this_page = home_url + '?product_cat=' + jQuery(this).val();
						}
						location.href = this_page;
					} else {
						location.href = '" . esc_js(wc_get_page_permalink('shop')) . "';
					}
				});

				if ( jQuery().selectWoo ) {
					var wc_product_cat_select = function() {
						var dropdownCat = jQuery( '.dropdown_product_cat' ),
							widgetId = dropdownCat.parents().find('.gutenova-block').attr('id');
						dropdownCat.selectWoo( {
							placeholder: '" . esc_js(esc_html__('Select a category', 'shopengine-gutenberg-addon')) . "',
							minimumResultsForSearch: 5,
							width: '100%',
							allowClear: true,
							dropdownCssClass: widgetId,
							language: {
								noResults: function() {
									return '" . esc_js(_x('No matches found', 'enhanced select', 'shopengine-gutenberg-addon')) . "';
								}
							}
						} );
					};
					wc_product_cat_select();
				}
			"
			);
		} else {
			include_once WC()->plugin_path() . '/includes/walkers/class-wc-product-cat-list-walker.php';

			$list_args['walker'] = new WC_Product_Cat_List_Walker();
			$list_args['title_li'] = '';
			$list_args['pad_counts'] = 1;
			$list_args['show_option_none'] = esc_html__('No product categories exist.', 'shopengine-gutenberg-addon');
			$list_args['current_category'] = ($current_cat) ? $current_cat->term_id : '';
			$list_args['current_category_ancestors'] = $cat_ancestors;
			$list_args['max_depth'] = $max_depth;

			echo '<ul class="product-categories">';

			wp_list_categories(apply_filters('woocommerce_product_categories_widget_args', $list_args));

			echo '</ul>';
		}
		?>

	</div>
</div>