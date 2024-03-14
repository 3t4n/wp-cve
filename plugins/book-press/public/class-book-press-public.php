<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/book-press
 * @since      1.0.0
 *
 * @package    Book_Press
 * @subpackage Book_Press/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Book_Press
 * @subpackage Book_Press/public
 * @author     Md Kabir Uddin <bd.kabiruddin@gmail.com>
 */
class Book_Press_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Book_Press_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Book_Press_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/book-press-public.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/swipeable-mobile.css', array(), $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Book_Press_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Book_Press_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/book-press-public.js', array('jquery'), $this->version, false);
		wp_localize_script($this->plugin_name, 'bp_Vars', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('wedocs-ajax'),
		));

		wp_enqueue_script('download-pdf', plugin_dir_url(__FILE__) . 'js/download.js', array('jquery'), $this->version, false);

		wp_localize_script('download-pdf', 'download_min_lib', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('wedocs-ajax'),
		));

		wp_enqueue_script('loading-disable', plugin_dir_url(__FILE__) . 'js/loading-disable.js', array('jquery'), $this->version, false);
		wp_localize_script('loading-disable', 'jquery_min_lib', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('wedocs-ajax'),
		));

		wp_enqueue_script('jquery-mobile-lib', plugin_dir_url(__FILE__) . 'js/jquery-min-mobile.js', array('jquery'), $this->version, false);
		wp_localize_script('jquery-mobile-lib', 'jquery_mobile_lib', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('wedocs-ajax'),
		));
		wp_enqueue_script('turn', plugin_dir_url(__FILE__) . 'js/turn.js', array('jquery'), $this->version, false);

		wp_localize_script('turn', 'turn', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('wedocs-ajax'),
		));

		// }  else {

		// }
	}

	public function book_custom_template($single) {
		global $post;
		if ($post->post_type == 'book') {
			if (file_exists(plugin_dir_path(__FILE__) . '/book-single.php')) {
				return plugin_dir_path(__FILE__) . '/book-single.php';
			}
		}
		return $single;
	}

	public function book_shortcode_func($atts) {

		$data = shortcode_atts(array(
			'id' => null,
			'single_page' => false,
		), $atts);

		if (!$data['id']) {
			return __('Book not found', 'book-press');
		}

		if (!get_post($data['id'])) {
			return __('Book not found', 'book-press');
		}

		$html = '';

		$book_id = $data['id'];

		$single_page = $data['single_page'] ? $data['single_page'] : true;

		$book = get_post_meta($book_id, 'book', true);

		if ($book) {
			$bg_color = $book['page']['bg_color'];
			$font_color = $book['page']['font_color'];
			$font_size = $book['page']['font_size'];
			$font_family = $book['page']['font'];
			$border_style = $book['border']['style'];
			$border_weight = $book['border']['weight'];
			$border_radius = $book['border']['radius'];
			$page_navigation_color = $book['page']['navigation_color'];
			$html .= '
			<style type="text/css">
			.single-book .page {
				background:' . $bg_color . ';
				color:' . $font_color . ';
				font-size:' . $font_size . ';
				font-family:' . $font_family . ';
				margin-bottom: 0px;
			}
			.single-book .pages_cont {
			    width: 13in;
			    height: 9in;
			    margin: 0 auto;
			    overflow: hidden;
			    transform: scale(0.90);
			    transform-origin: top;
			}
			';
			if ($single_page == 'true') {
				$html .= '
				.single_page_true.single-book .pages_cont {
					width: 6in;
					height: 9in;
					margin: 0 auto;
					overflow:hidden;
				}
				.single_page_true.single-book .pages_cont  .page {
					margin-right: 0in !important
				}
				';
			}
			$html .= '
			.navigation {
				margin-top: -85px !important;
			}
			</style>';
			$pagination_location_y = get_post_meta($book_id, 'pagination_location_y', true) ? get_post_meta($book_id, 'pagination_location_y', true) : 'top';
			$pagination_location_x = get_post_meta($book_id, 'pagination_location_x', true) ? get_post_meta($book_id, 'pagination_location_x', true) : 'left';
			$class = '';
			if ($single_page == 'true') {
				$class .= ' single_page_true';
			}
			if (wp_is_mobile()) {
				$html .= '
				<div class="single-book ' . $class . ' pagiloc-' . $pagination_location_y . '-' . $pagination_location_x . '" style="height: 90vh;">';
			}
			$html .= '
			<div class="single-book ' . $class . ' pagiloc-' . $pagination_location_y . '-' . $pagination_location_x . '" style="height: 90vh;">
				<div class="pages_cont">
					<div id="pages" class="pages" style="left: 0"></div>
				</div>
				<div class="navigation" style="text-align: center; margin-top: 5px; background:' . $page_navigation_color . '">';
			if (get_post_meta($book_id, 'pagination', true)) {
				if (get_post_meta($book_id, 'ajax_pages_type', true) === 'buttons') {
					$html .= '
								<button class="prev">' . get_post_meta($book_id, 'ajax_prev_text', true) . '</button>
								<button class="next">' . get_post_meta($book_id, 'ajax_next_text', true) . '</button>';
				}
				if (get_post_meta($book_id, 'ajax_pages_type', true) === 'numbers') {
					$html .= '
								<div class="pager-both" style="text-align: center;">
									<span class="pagertype pager-front">
									</span>
									<span class="pagertype pager-body">
									</span>
								</div>
								<div style="height: 0px; overflow: hidden;">
									<button class="prev">' . get_post_meta($book_id, 'ajax_prev_text', true) . '</button>
									<button class="next">' . get_post_meta($book_id, 'ajax_next_text', true) . '</button>
								</div>';
				}
			}
			$html .= '<input style="width: 30px;height: 30px; display: inline-block; padding: 0;" type="hidden" class="clickcount" name="clickcount" value="0">
				</div>
			</div>';
			$plugin = new Book_Press();
			$book = $plugin->get_book_new($book_id);
			$html .= '
			<script type="text/javascript">
				var sections = ' . json_encode($book) . ';
				var book_meta = ' . json_encode(get_post_meta($book_id)) . ';
				var single_page = ' . $single_page . ';
				jQuery(\'#book-pdf\').click(function() {
					var bookData = jQuery(\'.pages\').html();
					var opt = {
						margin: [0, 0, 0, 0],
						filename: name + \'.pdf\',
						image: {
							type: \'jpeg\',
							quality: 1
						},
						html2canvas: {
						},
						jsPDF: {
							unit: \'in\',
							format: [6,9],
						},
					};
					html2pdf(bookData, opt);
				});
			</script>';
		}
		return $html;
	}

	public function get_all_books() {

		$args = array(
			'post_type' => 'book',
			'meta_query' => array(
				array(
					'key' => 'type',
					'value' => array('book'),
				),
			),
		);

		$query = new WP_Query($args);

		if ($query->posts) {
			return $query->posts;
		} else {
			return array();
		}

	}

	public function footnote_shortcode_func($atts, $content = null) {
		$arg = shortcode_atts(array(
			'note' => null,
		), $atts);
		return '<i class="footnote_i"><span class="footnote">' . $arg['note'] . '</span></i>';

	}

	public function index_shortcode_func($atts, $content = null) {

		$arg = shortcode_atts(array(
			'hide' => null,
		), $atts);

		return '<span class="index ' . $arg['hide'] . '">' . $content . '</span>';
	}

	public function library_shortcode_func($atts, $content = null) {

		$arg = shortcode_atts(array(
			'genre' => null,
			'id' => null,
			'exclude' => null,
			'orderby' => 'title',
			'order' => 'DESC',
			'section_title' => null,
			'max_post' => -1,
		), $atts);

		$tax_query = array();

		if ($arg['genre']) {
			array_push($tax_query, array(
				'taxonomy' => 'genre',
				'field' => 'slug',
				'terms' => explode(',', $arg['genre']),
			));
		}

		$post__in = array();

		if ($arg['id']) {
			$post__in = explode(',', $arg['id']);
		}

		$post__not_in = array();

		if ($arg['exclude']) {
			$post__not_in = explode(',', $arg['exclude']);
		}

		$args = array(
			'post_type' => 'book',
			'post__not_in' => $post__not_in,
			'post__in' => $post__in,
			'tax_query' => $tax_query,
			'orderby' => $arg['orderby'],
			'order' => $arg['order'],
			'posts_per_page' => $arg['max_post'],
			'meta_query' => array(
				array(
					'key' => 'type',
					'value' => array('book'),
				),
			),
			'fields' => 'ids',
		);

		$query = new WP_Query($args);

		?>
		<div class="library-books-gener">
			<div class="library-books">

				<?php if ($arg['section_title']) {?>
					<h3><?php echo $arg['section_title']; ?></h3>
				<?php }?>

				<?php

		if ($query->posts) {
			foreach ($query->posts as $key => $book_id) {
				if ($book_id) {

					$book = get_post($book_id);
					if ($book) {

						$thumbnail = null;

						$argsb = array(
							'post_parent' => $book_id,
							'post_type' => 'book',
							'numberposts' => -1,
							'post_status' => 'any',
							'orderby' => 'menu_order',
							'order' => 'ASC',
						);
						$sections = get_children($argsb);
						foreach ($sections as $key => $section) {
							if ($section->post_title === 'Cover Matter') {
								$argsb = array(
									'post_parent' => $section->ID,
									'post_type' => 'book',
									'numberposts' => -1,
									'post_status' => 'any',
									'orderby' => 'menu_order',
									'order' => 'ASC',
								);
								$elements = get_children($argsb);
								foreach ($elements as $key => $element) {
									if ($element->post_title === 'Cover Image') {
										$thumbnail = get_the_post_thumbnail_url($element->ID, 'full');
									}
								}
							}
						}

						?>
						<div class="single-library-book">

							<table>
								<tr>
									<td valign="top">
										<?php if ($thumbnail) {?>
											<a href="<?php echo get_the_permalink($book->ID); ?>">
												<img style="max-width: 60px" src="<?php echo $thumbnail; ?>">
											</a>
										<?php }?>
									</td>
									<td valign="top">
										<div style="font-size: 20px; line-height: initial;">
										 <strong> <?php echo $book->post_title; ?> </strong>
										</div>
										<p style="margin-top: -1px; margin-bottom: 5px;">Author : <?php echo get_the_author_meta('display_name', $book->post_author); ?></p>
										<a href="<?php echo get_the_permalink($book->ID); ?>">Read the Book</a>
									</td>
								</tr>
							</table>
							</div>
							<?php
}
				}
			}
		}
		?>
			</div>
		</div>
		<?php
}

}
