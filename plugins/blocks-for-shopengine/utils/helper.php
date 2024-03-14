<?php

namespace Shopengine_Gutenberg_Addon\Utils;


defined('ABSPATH') || exit;

/**
 * Global helper class.
 *
 * @since 1.0.0
 */
class Helper {

	public static function is_elementor_active() {

		return did_action('elementor/loaded');
	}

	public static function is_gutenberg_active() {

		return !did_action('elementor/loaded');
	}

	public static function is_elementor_editor_mode() {

		if(self::is_elementor_active()) {

			return \Elementor\Plugin::$instance->editor->is_edit_mode();
		}

		return false;
	}


	public static function get_elementor_css_uri($pid) {

		global $blog_id;

		$wp_upload_dir = wp_upload_dir(null, false);

		$base = $wp_upload_dir['baseurl'] . '/elementor/css/';

		return set_url_scheme($base . 'post-' . $pid . '.css');
	}

	public static function add_to_url($url, $param) {
		$info  = parse_url($url);
		$query = [];

		if(isset($info['query'])) {
			parse_str($info['query'], $query);
		}

		return $info['scheme'] . '://' . $info['host'] . ($info['path'] ?? '') . '?' . http_build_query($query ? array_merge($query, $param) : $param);
	}


	/**
	 * Auto generate classname from path.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function make_classname($dirname) {
		$dirname    = pathinfo($dirname, PATHINFO_FILENAME);
		$class_name = explode('-', $dirname);
		$class_name = array_map('ucfirst', $class_name);
		$class_name = implode('_', $class_name);

		return $class_name;
	}

	public static function kses($raw, $only_array = false) {

		$allowed_tags = [
			'a'                             => [
				'class' => [],
				'href'  => [],
				'rel'   => [],
				'title' => [],
			],
			'abbr'                          => [
				'title' => [],
			],
			'b'                             => [],
			'blockquote'                    => [
				'cite' => [],
			],
			'cite'                          => [
				'title' => [],
			],
			'code'                          => [],
			'del'                           => [
				'datetime' => [],
				'title'    => [],
			],
			'dd'                            => [],
			'div'                           => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'dl'                            => [],
			'dt'                            => [],
			'em'                            => [],
			'h1'                            => [
				'class' => [],
			],
			'h2'                            => [
				'class' => [],
			],
			'h3'                            => [
				'class' => [],
			],
			'h4'                            => [
				'class' => [],
			],
			'h5'                            => [
				'class' => [],
			],
			'h6'                            => [
				'class' => [],
			],
			'i'                             => [
				'class' => [],
			],
			'img'                           => [
				'alt'    => [],
				'class'  => [],
				'height' => [],
				'src'    => [],
				'width'  => [],
			],
			'li'                            => [
				'class' => [],
			],
			'ol'                            => [
				'class' => [],
			],
			'p'                             => [
				'class' => [],
			],
			'q'                             => [
				'cite'  => [],
				'title' => [],
			],
			'span'                          => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'iframe'                        => [
				'width'       => [],
				'height'      => [],
				'scrolling'   => [],
				'frameborder' => [],
				'allow'       => [],
				'src'         => [],
			],
			'strike'                        => [],
			'br'                            => [],
			'strong'                        => [],
			'data-wow-duration'             => [],
			'data-wow-delay'                => [],
			'data-wallpaper-options'        => [],
			'data-stellar-background-ratio' => [],
			'ul'                            => [
				'class' => [],
			],
		];

			return $raw;
	}

	public static function raw_render($data){
		//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $data;
	}


	public static function kses_array($raw) {

		$allowed_tags = [
			'a'                             => [
				'class' => [],
				'href'  => [],
				'rel'   => [],
				'title' => [],
			],
			'abbr'                          => [
				'title' => [],
			],
			'b'                             => [],
			'blockquote'                    => [
				'cite' => [],
			],
			'cite'                          => [
				'title' => [],
			],
			'code'                          => [],
			'del'                           => [
				'datetime' => [],
				'title'    => [],
			],
			'dd'                            => [],
			'div'                           => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'dl'                            => [],
			'dt'                            => [],
			'em'                            => [],
			'h1'                            => [
				'class' => [],
			],
			'h2'                            => [
				'class' => [],
			],
			'h3'                            => [
				'class' => [],
			],
			'h4'                            => [
				'class' => [],
			],
			'h5'                            => [
				'class' => [],
			],
			'h6'                            => [
				'class' => [],
			],
			'i'                             => [
				'class' => [],
			],
			'img'                           => [
				'alt'    => [],
				'class'  => [],
				'height' => [],
				'src'    => [],
				'width'  => [],
			],
			'li'                            => [
				'class' => [],
			],
			'ol'                            => [
				'class' => [],
			],
			'p'                             => [
				'class' => [],
			],
			'q'                             => [
				'cite'  => [],
				'title' => [],
			],
			'span'                          => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'iframe'                        => [
				'width'       => [],
				'height'      => [],
				'scrolling'   => [],
				'frameborder' => [],
				'allow'       => [],
				'src'         => [],
			],
			'strike'                        => [],
			'br'                            => [],
			'strong'                        => [],
			'data-wow-duration'             => [],
			'data-wow-delay'                => [],
			'data-wallpaper-options'        => [],
			'data-stellar-background-ratio' => [],
			'ul'                            => [
				'class' => [],
			],
		];

		if(function_exists('wp_kses')) { // WP is here
			return wp_kses($raw, $allowed_tags);
		} else {
			return $raw;
		}
	}

	public static function kspan($text) {
		return str_replace(['{', '}'], ['<span>', '</span>'], self::kses($text));
	}

	public static function category_list_by_taxonomy($taxonomy = '') {
		$query_args = [
			'orderby'    => 'ID',
			'order'      => 'DESC',
			'hide_empty' => 1,
			'taxonomy'   => $taxonomy,
		];

		$categories = get_categories($query_args);

		return $categories;
	}

	public static function trim_words($text, $num_words) {
		return wp_trim_words($text, $num_words, '');
	}

	public static function array_push_assoc($array, $key, $value) {
		$array[$key] = $value;

		return $array;
	}

	public static function render($content) {
		if(stripos($content, "shopengine-has-lisence") !== false) {
			return null;
		}

		return $content;
	}

	public static function render_elementor_content($content_id) {
		$elementor_instance = \Elementor\Plugin::instance();

		return $elementor_instance->frontend->get_builder_content_for_display($content_id);
	}

	public static function esc_options($str, $options = [], $default = '') {
		if(!in_array($str, $options)) {
			return $default;
		}

		return $str;
	}

	public static function img_meta($id) {
		$attachment = get_post($id);
		if($attachment == null || $attachment->post_type != 'attachment') {
			return null;
		}

		return [
			'alt'         => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
			'caption'     => $attachment->post_excerpt,
			'description' => $attachment->post_content,
			'href'        => get_permalink($attachment->ID),
			'src'         => $attachment->guid,
			'title'       => $attachment->post_title,
		];
	}

	public static function _product_tag_sale_badge($settings = null) {
		global $product;
		$terms = get_the_terms(get_the_ID(), 'product_tag');

		$badge_position = (isset($settings['badge_position']['desktop']) && !empty($settings['badge_position']['desktop'])) ? esc_attr($settings['badge_position']['desktop']) : 'top-right';
		$badge_align    = (isset($settings['badge_align']['desktop']) && !empty($settings['badge_align']['desktop'])) ? esc_attr($settings['badge_align']['desktop']) : 'horizontal';

		if($product->is_on_sale() || !empty($terms)) : ?>
            <div class="product-tag-sale-badge position-<?php echo esc_attr($badge_position); ?> align-<?php echo esc_attr($badge_align); ?>">
                <ul>
					<?php if(!empty($terms)) : $term = $terms[0];
						$bg = get_term_meta($term->term_id, 'shopengine_tag_bg_color', true);
						?>
						<?php if(!empty(self::_discount_percentage())) : ?>
                            <li class="badge no-link off"><?php echo '-' . esc_html(self::_discount_percentage()) . '%'; ?></li>
						<?php endif; ?>
                        <li class="badge tag">
                            <a <?php if(!empty($bg)) : ?>style="background-color:<?php echo esc_attr($bg); ?>" <?php endif; ?>
                               href="<?php echo esc_url(get_term_link($term->term_id)); ?>"><?php echo esc_html($term->name); ?></a>
                        </li>
					<?php endif;

					if($product->is_on_sale()) {
						echo "<li class='badge no-link sale'>" . esc_html__('Sale!', 'shopengine-gutenberg-addon') . "</li>";
					}
					?>
                </ul>
            </div>
		<?php
		endif;
	}


	public static function _product_image($settings = null) {
		//var_dump('arnob');

		global $product;
		?>
        <div class='product-thumb'>
            <a href="<?php echo esc_url(get_the_permalink()); ?>">
				<?php shopengine_content_render( woocommerce_get_product_thumbnail($product->get_id()) ); ?>
            </a>

            <!-- end sale date -->
			<?php
			if(!empty($settings['counter_position']['desktop']) && $settings['counter_position']['desktop'] == 'image') {
				self::_product_sale_end_date($settings);
			}
			?>
            <!-- tag and sale badge -->
			<?php self::_product_tag_sale_badge($settings); ?>

            <!-- show group buttons -->
			<?php
			if(!empty($settings['shopengine_group_btns']['desktop']) && $settings['shopengine_group_btns']['desktop'] == true) {

				$data_attr = apply_filters('shopengine/group_btns/optional_tooltip_data_attr', '');

				?>
                <div class="loop-product--btns" <?php echo esc_attr($data_attr) ?>>
                    <div class="loop-product--btns-inner">
						<?php woocommerce_template_loop_add_to_cart(); ?>
                    </div>
                </div>
				<?php
			}
			?>

        </div>
		<?php
	}

	public static function _product_sale_end_date($settings) {
		$date       = get_post_meta(get_the_id(), '_sale_price_dates_to', true);
		if(!empty($date)) :
			$formatted_date = date("Y-m-d", $date);
			$config = [
				'days'    => esc_html__('Days', 'shopengine-gutenberg-addon'),
				'hours'   => esc_html__('Hours', 'shopengine-gutenberg-addon'),
				'minutes' => esc_html__('Minutes', 'shopengine-gutenberg-addon'),
				'seconds' => esc_html__('Seconds', 'shopengine-gutenberg-addon'),
			];

			?>
            <div data-prefix="<?php echo !empty($settings['counter_prefix']) ? esc_attr($settings['counter_prefix']) : ''; ?>"
                 class="product-end-sale-timer <?php echo !empty($settings['counter_position']) ? 'counter-position-' . esc_attr($settings['counter_position']) : ''; ?>"
                 data-config='<?php echo esc_attr(wp_json_encode($config)); ?>'
                 data-date="<?php echo esc_attr($formatted_date); ?>"></div>
		<?php
		endif;
	}

	public static function _product_category($settings = null) {
		global $product;

		$terms       = get_the_terms($product->get_id(), 'product_cat');

		if (!empty($terms)) {
			echo "<div class='product-category'><ul>";
			foreach ($terms as $key => $term) {
				$separator = $key !== (count($terms) - 1) ? ',' : '';
				echo "<li><a href='" . esc_url(get_term_link($term->term_id)) . "'>" . esc_html($term->name . $separator) . "</a></li>";
			}
			echo "</ul></div>";
		}
	}

	public static function _discount_percentage($settings = null) {
		global $product;

		$product_data = $product->get_data();
		$show_tag     = (isset($settings['show_tag']) && !empty($settings['show_tag'])) ? esc_attr($settings['show_tag']) : 'yes';
		$output       = '';
		if($show_tag == 'yes' && !empty($product_data['regular_price']) && $product_data['sale_price']) {
			$percentage = round((($product_data['regular_price'] - $product_data['sale_price']) / $product_data['regular_price']) * 100);

			return $percentage;
		}

		return '';
	}


	public static function _product_title($settings = null) {
		global $product;
		?>
        <h3 class='product-title'>
            <a href="<?php echo esc_url(get_the_permalink($product->get_id())); ?>"><?php echo esc_html(get_the_title($product->get_id())); ?></a>
        </h3>
		<?php
	}

	public static function _product_rating($settings = null) {

		global $product;
		?>
        <div class="product-rating">
			<?php
			if($product->get_rating_count() > 0) {
				woocommerce_template_loop_rating();
			} else {
				echo sprintf('<div class="star-rating">%1$s</div>', wp_kses_post(wc_get_star_rating_html(0, 0)));
			}

			// review count
			echo sprintf('<span class="rating-count">(%1$s)</span>', esc_html($product->get_review_count()));
			?>
        </div>
		<?php
	}


	public static function _product_price($settings = null) {
		?>
        <div class="product-price">
			<?php woocommerce_template_single_price(); ?>
        </div>
		<?php
	}

	public static function _product_description($settings = null) {
		global $product;
		$product_data = $product->get_data($product->get_id());
		?>
        <div class="prodcut-description">
			<?php echo wp_kses_post(apply_filters('shopengine_product_short_description', $product_data['description'])); ?>
        </div>

		<?php
	}

	public static function _product_buttons($settings = null , $product = null) {
		if(!empty($settings['shopengine_group_btns']['desktop']) && $settings['shopengine_group_btns']['desktop'] === true) : ?>
            <div class="add-to-cart-bt">
				<?php 
				
				if ( $product ) {
					$defaults = array(
						'quantity'   => 1,
						'class'      => implode(
							' ',
							array_filter(
								array(
									'button',
									'product_type_' . $product->get_type(),
									$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
									$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
								)
							)
						),
						'attributes' => array(
							'data-product_id'  => $product->get_id(),
							'data-product_sku' => $product->get_sku(),
							'aria-label'       => $product->add_to_cart_description(),
							'rel'              => 'nofollow',
						),
					);
		
					$args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( [], $defaults ), $product );
		
					if ( isset( $args['attributes']['aria-label'] ) ) {
						$args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
					}
		
					wc_get_template( 'loop/add-to-cart.php', $args );
				}

				?>
            </div>
		<?php endif;
	}


	/**
	 * todo - check the keys for refund and cancelled [wc-cancelled, wc-refunded ]
	 *
	 * @param $product_id
	 * @param int $variation_id
	 * @return int
	 */
	public static function get_total_sale_count($product_id, $variation_id = 0) {

		global $wpdb;

		$result = $wpdb->get_row($wpdb->prepare('SELECT sum(lookup.product_qty) as total FROM `' . $wpdb->prefix . 'wc_order_product_lookup` as lookup LEFT JOIN ' . $wpdb->prefix . 'wc_order_stats AS stat on lookup.order_id = stat.order_id WHERE `product_id` = %d and variation_id = %d and stat.status NOT IN (\'wc-cancelled\', \'wc-refunded\') ;', intval($product_id), intval($variation_id) ));
		$total  = is_object($result) ? $result->total : 0;

		return intval($total);
	}


	public static function is_guest_checkout_allowed() {

		return 'yes' === get_option('woocommerce_enable_guest_checkout');
	}

	public static function is_login_allowed_during_checkout() {

		return 'yes' === get_option('woocommerce_enable_checkout_login_reminder');
	}

	// Todo: will remove this method
	private static function generate_products_meta() {
		global $wpdb;
		$post_type = 'product';
		
		$meta_keys = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT($wpdb->postmeta.meta_key) FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id WHERE $wpdb->posts.post_type = %s AND $wpdb->postmeta.meta_key != '' AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)' AND $wpdb->postmeta.meta_key NOT RegExp '(^[0-9]+$)'", $post_type));

		//set_transient( 'shopengine-all-products_meta_keys', $meta_keys, 60 * 60 * 0.01 );

		return $meta_keys;
	}

	// Todo: will remove this method
	public static function get_products_meta_keys() {
		//$cache = get_transient( 'shopengine-all-products_meta_keys' );
		return static::generate_products_meta();
	}


}
