<?php
namespace Skt_Addons_Elementor\Elementor;

defined( 'ABSPATH' ) || die();

class Ajax_Handler {

	private static $instance = null;

	public static function instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
			// self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Initialization
	 *
	 * @return void
	 */

    public static function init() {
    	add_action('wp_ajax_skt_addons_elementor_smart_post_list_action', [__CLASS__, 'smart_post_list_ajax']);
		add_action('wp_ajax_nopriv_skt_addons_elementor_smart_post_list_action', [__CLASS__, 'smart_post_list_ajax']);

		add_action('wp_ajax_sktaddonselementorextra_post_grid_ajax', [__CLASS__, 'post_grid_ajax']);
		add_action('wp_ajax_nopriv_sktaddonselementorextra_post_grid_ajax', [__CLASS__, 'post_grid_ajax']);

		add_action('wp_ajax_skt_addons_elementor_post_tab_action', [ __CLASS__, 'post_tab' ]);
		add_action('wp_ajax_nopriv_skt_addons_elementor_post_tab_action', [ __CLASS__, 'post_tab' ]);

		add_action('wp_ajax_skt_addons_elementor_mailchimp_ajax', [__CLASS__, 'mailchimp_prepare_ajax']);
        add_action('wp_ajax_nopriv_skt_addons_elementor_mailchimp_ajax', [__CLASS__, 'mailchimp_prepare_ajax']);
		
		add_action( 'wp_ajax_skt_show_edd_product_quick_view', [ __CLASS__, 'edd_show_product_quick_view' ] );
		add_action( 'wp_ajax_nopriv_skt_show_edd_product_quick_view', [ __CLASS__, 'edd_show_product_quick_view' ] );
		
		add_action('wp_ajax_skt_edd_ajax_add_to_cart_link', [__CLASS__, 'skt_edd_ajax_add_to_cart_link']);
		add_action('wp_ajax_nopriv_skt_edd_ajax_add_to_cart_link', [__CLASS__, 'skt_edd_ajax_add_to_cart_link']);
    }

	public static function skt_edd_ajax_add_to_cart_link(){
		$security = check_ajax_referer('skt_addons_elementor_addons_nonce', 'security');

		if (true == $security && isset($_POST['download_id'])) {
			if ( ! function_exists( 'EDD' ) ) {
				return;
			}
			$download_id = isset($_POST['download_id']) ? $_POST['download_id']: '';

			edd_add_to_cart( $download_id );

			wp_send_json_success();

			die();
		}
	}

    /**
	 * Smart Post List Ajax Handler
	 */
	public static function smart_post_list_ajax() {

		$security = check_ajax_referer('skt_addons_elementor_addons_pro_nonce', 'security');

		if (true == $security && isset($_POST['querySettings'])) :

			$settings = sanitize_text_field($_POST['querySettings']);

			$list_column = $settings['list_column'];
			$class_array = [];
			if ('yes' === $settings['make_featured_post']) {
				$class_array['featured'] = 'skt-spl-column skt-spl-featured-post-wrap ' . esc_attr($settings['featured_post_column']);
				$class_array['featured_inner'] = 'skt-spl-featured-post ' . 'skt-spl-featured-' . esc_attr($settings['featured_post_style']);
			}

			$per_page = $settings['per_page'];
			$args = $settings['args'];
			$args['posts_per_page'] = $per_page;

			if ($_POST['offset']) {
				$args['offset'] = sanitize_text_field($_POST['offset']);
			}
			if ($_POST['termId'] && is_numeric($_POST['termId'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => '',
						'field' => 'term_taxonomy_id',
						'terms' => sanitize_text_field($_POST['termId']),
					),
				);
			}

			$args['suppress_filters'] = false;

			$posts = get_posts($args);
			$loop = 1;

			if (count($posts) !== 0) {

				self::render_spl_markup($settings, $posts, $class_array, $list_column, $per_page);
			}

		endif;
		wp_die();
	}

	/**
	 * Post Grid Ajax Handler
	 */
	public function post_grid_ajax() {

		echo 1;
		exit();

		$security = check_ajax_referer('skt_addons_elementor_addons_nonce', 'security');

		if (true == $security && isset($_POST['querySettings'])) :

			$settings = sanitize_text_field($_POST['querySettings']);
			$loaded_item = sanitize_text_field($_POST['loadedItem']);

			$args = $settings['args'];
			$args['offset'] = $loaded_item;
			$_query = new WP_Query($args);

			if ($_query->have_posts()) :
				while ($_query->have_posts()) : $_query->the_post();

					if (!empty($settings['_skin'])) {
						self::{'render_' . $settings['_skin'] . '_markup'}($settings, $_query);
					}

					if (!empty($settings['skin'])) {
						$this->{'new_render_' . $settings['skin'] . '_markup'}($settings, $_query);
					}

				endwhile;
				wp_reset_postdata();
			endif;
		endif;
		wp_die();
	}

	/**
	 * Post Tab Ajax call
	 */
    public static function post_tab() {

		$security = check_ajax_referer('skt_addons_elementor_addons_nonce', 'security');

		if (true == $security) :
			$settings   = $_POST['post_tab_query'];
			$post_type  = $settings['post_type'];
			$taxonomy   = $settings['taxonomy'];
			$item_limit = $settings['item_limit'];
			$excerpt    = $settings['excerpt'];
			$term_id    = $_POST['term_id'];

			$args = [
				'post_status'      => 'publish',
				'post_type'        => $post_type,
				'posts_per_page'   => $item_limit,
				'suppress_filters' => false,
				'tax_query'        => [
					[
						'taxonomy' => $taxonomy,
						'field'    => 'term_id',
						'terms'    => $term_id,
					],
				],
			];

			$posts = get_posts($args);

			if (count($posts) !== 0) :
			?>
				<div class="skt-post-tab-item-wrapper active" data-term="<?php echo esc_attr($term_id); ?>">
					<?php foreach ($posts as $post) : ?>
						<div class="skt-post-tab-item">
							<div class="skt-post-tab-item-inner">
								<?php if (has_post_thumbnail($post->ID)) : ?>
									<a href="<?php echo esc_url(get_the_permalink($post->ID)); ?>" class="skt-post-tab-thumb">
										<?php echo wp_kses_post(get_the_post_thumbnail($post->ID, 'full')); ?>
									</a>
								<?php endif; ?>
								<h2 class="skt-post-tab-title">
									<a href="<?php echo esc_url(get_the_permalink($post->ID)); ?>"> <?php echo esc_html($post->post_title); ?></a>
								</h2>
								<div class="skt-post-tab-meta">
									<span class="skt-post-tab-meta-author">
										<i class="fa fa-user-o"></i>
										<a href="<?php echo esc_url(get_author_posts_url($post->post_author)); ?>"><?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?></a>
									</span>
									<?php
									$archive_year  = get_the_time('Y', $post->ID);
									$archive_month = get_the_time('m', $post->ID);
									$archive_day   = get_the_time('d', $post->ID);
									?>
									<span class="skt-post-tab-meta-date">
										<i class="fa fa-calendar-o"></i>
										<a href="<?php echo esc_url(get_day_link($archive_year, $archive_month, $archive_day)); ?>"><?php echo esc_html(get_the_date("M d, Y", $post->ID)); ?></a>
									</span>
								</div>
								<?php if ('yes' === $excerpt && !empty($post->post_excerpt)) : ?>
									<div class="skt-post-tab-excerpt">
										<p><?php echo esc_html($post->post_excerpt); ?></p>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
	<?php

			endif;
		endif;
		wp_die();
	}

	/**
	 * Mailchimp subscriber handler Ajax call
	 */
	public static function mailchimp_prepare_ajax() {

        $security = check_ajax_referer('skt_addons_elementor_addons_nonce', 'security');

        if (!$security) return;

        sanitize_text_field(parse_str(isset($_POST['subscriber_info']) ? $_POST['subscriber_info'] : '', $subsciber));

		if(!class_exists('Skt_Addons_Elementor\Elementor\Widget\Mailchimp\Mailchimp_api')) {
			include_once SKT_ADDONS_ELEMENTOR_DIR_PATH . 'widgets/mailchimp/mailchimp-api.php';
		}

        $response = Widget\Mailchimp\Mailchimp_api::insert_subscriber_to_mailchimp($subsciber);

        echo wp_send_json($response);

        wp_die();
    }
	
		/**
	 * Product quick view ajax handler
	 *
	 * @return void
	 */
	public static function edd_show_product_quick_view() {
		$nonce = ! empty( $_GET['nonce'] ) ? $_GET['nonce'] : '';
		$product_id = ! empty( $_GET['download_id'] ) ? absint( $_GET['download_id'] ) : 0;

		if ( ! function_exists( 'EDD' ) ) {
			wp_send_json_error( 'Looks like you are not trying a product quick view!' );
		}

		if ( ! wp_verify_nonce( $nonce, 'skt_show_edd_product_quick_view' ) ) {
			wp_send_json_error( 'Invalid request!' );
		}

		$_product = get_post( $product_id );

		if ( empty( $_product ) || get_post_type( $_product ) !== 'download' ) {
			wp_send_json_error( 'Incomplete request!' );
		}

		global $post;

		$post = $_product;

		setup_postdata( $post );
		add_filter( 'edd_purchase_link_defaults', [ __CLASS__, 'hide_button_prices'] );

		?>
		<div class="skt-pqv-edd">
			<div class="skt-pqv-edd__img">
				<?php echo get_the_post_thumbnail($post, 'full'); ?>
			</div>
			<div class="skt-pqv-edd__content">
				<h2 class="skt-pqv-edd__title"><?php the_title(); ?></h2>
				<div class="skt-pqv-edd__price"><?php edd_price( $post->ID ); ?></div>
				<div class="skt-pqv-edd__summary"><?php echo wp_trim_words( get_the_content( $post ), 100 ); ?></div>
				<div class="skt-pqv-edd__cart">
					<?php
					if( edd_has_variable_prices( $post->ID ) ){
						printf( '<a href="%s" class="button" target="_blank">%s</a>',
							esc_url( get_the_permalink( $post->ID ) ),
						 	__( 'Select Options', 'skt-addons-elementor' )
						);
					}else{
						printf( '<a href="%s" class="button" target="_blank">%s</a>',
							esc_url( get_the_permalink( $post->ID ) ),
						 	__( 'Buy Now', 'skt-addons-elementor' )
						);
					}
					?>
				</div>
			</div>
		</div>
		<?php

		wp_reset_postdata();

		exit;
	}
	
}



Ajax_Handler::init();