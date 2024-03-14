<?php
/**
 * Custom VC Element
 */
if ( class_exists( 'WPBakeryShortcode' ) ) {
	class VC_WPS_Store_Customizations extends WPBakeryShortcode {
		function __construct() {
			// New Elements Mapping
			add_action('init', array($this, 'vc_wps_stores_mapping'));

			// New Elements HTML
			add_shortcode('vc_wps_store', array($this, 'vc_wps_store_html'));
		}

		// Elements Mapping
		public function vc_wps_stores_mapping() {
			if (!defined('WPB_VC_VERSION')) { return; }

			// Map the block with vc_map()
			vc_map(
				array(
					'name' => __('WC Pickup Store', 'wc-pickup-store'),
					'base' => 'vc_wps_store',
					'description' => __('Show stores in page content', 'wc-pickup-store'),
					'category' => __('Content', 'wc-pickup-store'),
					'icon' => WPS_PLUGIN_DIR_URL . 'assets/images/wps_placeholder.png',
					'params' => array(
						array(
							'type' => 'textfield',
							'heading' => __('Stores to show', 'wc-pickup-store'),
							'param_name' => 'show',
							'save_always' => true,
							'group' => __('Settings', 'wc-pickup-store'),
							'description' => __('Set -1 to show all stores.', 'wc-pickup-store'),
						),
						array(
							'type' => 'textfield',
							'heading' => __('Set posts IDs', 'wc-pickup-store'),
							'param_name' => 'post_ids',
							'save_always' => true,
							'group' => __('Settings', 'wc-pickup-store'),
							'description' => __('Add post IDs to show followed by a comma. Ex. 01,05.', 'wc-pickup-store'),
						),
						array(
							'type' => 'checkbox',
							'heading' => __('Show as grid?', 'wc-pickup-store'),
							'param_name' => 'stores_layout',
							'save_always' => true,
							'group' => __('Settings', 'wc-pickup-store'),
							'description' => __('Otherwise it\'ll appear as listing.', 'wc-pickup-store'),
						),
						array(
							'type' => 'dropdown',
							'heading' => __('Items per row', 'wc-pickup-store'),
							'param_name' => 'stores_per_row',
							'value' => array(
								'Disable' => '',
								'2' => '2',
								'3' => '3',
								'4' => '4',
							),
							'save_always' => true,
							'group' => __('Settings', 'wc-pickup-store'),
							'description' => __('Available in grid style.', 'wc-pickup-store'),
						),
						array(
							'type' => 'checkbox',
							'heading' => __('Show image?', 'wc-pickup-store'),
							'param_name' => 'store_image',
							'save_always' => true,
							'group' => __('Settings', 'wc-pickup-store')
						),
						array(
							'type' => 'textfield',
							'heading' => __('Image size', 'wc-pickup-store'),
							'param_name' => 'store_image_size',
							'save_always' => true,
							'value' => 'medium',
							'group' => __('Settings', 'wc-pickup-store'),
							'description' => 'thumbnail, medium, full, 100x100',
						),
						array(
							'type' => 'checkbox',
							'heading' => __('Show store name?', 'wc-pickup-store'),
							'param_name' => 'store_name',
							'save_always' => true,
							'group' => __('Fields', 'wc-pickup-store')
						),
						array(
							'type' => 'checkbox',
							'heading' => __('Show direction?', 'wc-pickup-store'),
							'param_name' => 'store_direction',
							'save_always' => true,
							'group' => __('Fields', 'wc-pickup-store')
						),
						array(
							'type' => 'checkbox',
							'heading' => __('Show phone?', 'wc-pickup-store'),
							'param_name' => 'store_phone',
							'save_always' => true,
							'group' => __('Fields', 'wc-pickup-store')
						),
						array(
							'type' => 'checkbox',
							'heading' => __('Show description?', 'wc-pickup-store'),
							'param_name' => 'store_description',
							'save_always' => true,
							'group' => __('Fields', 'wc-pickup-store')
						),
						array(
							'type' => 'checkbox',
							'heading' => __('Show waze link?', 'wc-pickup-store'),
							'param_name' => 'store_waze_link',
							'save_always' => true,
							'group' => __('Fields', 'wc-pickup-store')
						),
						array(
							'type' => 'colorpicker',
							'class' => 'store_icon_background',
							'heading' => __('Icon background', 'wc-pickup-store'),
							'param_name' => 'store_icon_background',
							'description' => __('Icon background', 'wc-pickup-store'),
							'group' => esc_html__( 'Icon color', 'wc-pickup-store' ),
						),
						array(
							'type' => 'colorpicker',
							'class' => 'store_icon_color',
							'heading' => __('Icon color', 'wc-pickup-store'),
							'param_name' => 'store_icon_color',
							'description' => __('Icon color', 'wc-pickup-store'),
							'group' => esc_html__( 'Icon color', 'wc-pickup-store' ),
						)
					)
				)
			);
		}

		/**
		 * WPBakery component for stores
		 * 
		 * @version 1.8.6
		 * @since 1.x
		 */
		public function vc_wps_store_html( $atts ) {
			// Params extraction
			extract( $atts );
			$layout = 'layout-list';
			$icon_background = !empty( $atts['store_icon_background'] ) ? 'style="background: ' . $atts['store_icon_background'] . '"' : '';
			$icon_color = !empty( $atts['store_icon_color'] ) ? 'style="color: ' . $atts['store_icon_color'] . '"' : '';

			if ( !empty( $atts['stores_layout'] ) && !empty( $atts['stores_per_row'] ) ) {
				$layout = 'layout-grid col-layout-' . $atts['stores_per_row'];
			} elseif ( !empty( $atts['stores_layout'] ) ) {
				$layout = 'layout-grid';
			}

			$image_size = explode( 'x', strtolower( $atts['store_image_size'] ) );
			if ( !empty( $image_size[1] ) ) {
				$store_image_size = $image_size;
			} else {
				$store_image_size = $image_size[0];
			}

			$query_args = array(
				'post_type' => 'store',
				'posts_per_page' => $atts['show'],
				'order' => 'DESC',
				'orderby' => 'date',
			);

			if ( !empty( $atts['post_ids'] ) ) {
				$query_args['orderby'] = 'post__in';
				$query_args['post__in'] = explode( ',', $atts['post_ids'] );
			}

			$template_file = wps_locate_template( 'wrapper-vc_stores.php' );
			ob_start();
			$query = new WP_Query( apply_filters( 'wps_store_widget_query_args', $query_args ) );
			if ( $query->have_posts() && $template_file ) {
				?>
				<div class="stores-container <?= $layout ?>">
					<?php
						while ( $query->have_posts() ) : $query->the_post();
							// global $post;
							$store_id = get_the_ID();
							$store_city = sanitize_text_field( wps_get_post_meta( $store_id, 'city' ) );
							$store_direction = !empty( $atts['store_direction'] ) ? wp_kses_post( wps_get_post_meta( $store_id, 'address' ) ) : '';
							$store_phone = !empty( $atts['store_phone'] ) ? sanitize_text_field( wps_get_post_meta( $store_id, 'phone' ) ) : '';
							$store_description = !empty( $atts['store_description'] ) ? wp_kses_post( wps_get_post_meta( $store_id, 'description' ) ) : '';
							$store_waze_link = !empty( $atts['store_waze_link'] ) ? esc_url( wps_get_post_meta( $store_id, 'waze' ) ) : '';
					
							include $template_file;

						endwhile;
						wp_reset_postdata();
					?>
				</div>
				<?php
			}

			$html = ob_get_clean();
			return $html;
		}
	}
	new VC_WPS_Store_Customizations();
}
