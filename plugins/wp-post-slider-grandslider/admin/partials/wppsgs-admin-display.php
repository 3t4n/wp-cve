<?php
/**
 * The main class for Meta-box configurations.
 *
 * @package WP_Post_Slider_Grandslider
 * @subpackage WP_Post_Slider_Grandslider/partials/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

/**
 * HFC Show Help Page.
 */
class WP_Post_Slider_Grandslider_Admin_Display {

	/**
	 * Add admin sub-menu.
	 *
	 * @return void
	 */
	public function wppsgs_admin_display() {

		add_submenu_page(
			'edit.php?post_type=wppsgs_slider',
			'+ Add Testimonial',
			'+ Add Testimonial',
			'manage_options',
			'post-new.php?post_type=wppsgs_tmonial'
		);
	}

	/**
	 * Help Page Callback
	 */
	public function wppsgs_gs_callback() {

		wp_enqueue_style( 'hfc-getting-started', esc_url( WPPSGS_DIR_URL_FILE . 'header-footer-customizer/admin/css/hfc-main-page.css' ), array(), WP_POST_SLIDER_GRANDSLIDER_VERSION );
		$add_new_carousel_link = admin_url( 'post-new.php?post_type=wppsgs_blocks' );
		?>
		<div class="hfc-option-body">
			<div class="hfc-setting-header">
				<div class="hfc-setting-header-info">
					<img src="<?php echo esc_url( WPPSGS_URL . 'admin/img/plugin-logo.gif' ); ?>" alt="FancyPost">
					<div class="hfc-plugin-about">
						<h1>FancyPost<sup id="hfc-plugin-version">1.0.0</sup></h1>
						<p>Thank you for installing.</p>
						<p>Most Powerful &amp; Advanced Gutenberg Blocks!</p>
					</div>
				</div>
			</div>

			<div class="hfc-container-wrap">
				<div class="hfc-container-overview">
					<div class="hfc-container-hero">
						<div class="hfc-hero-video">
							<iframe width="100%" height="400" src="https://www.youtube.com/embed/3-XGJ3QSQaM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
							<div class="hfc-hero-buttons">
								<a href="#" target="_blank">Live Demo</a>
								<a href="#" target="_blank">Plugin Details</a>
								<a href="#" target="_blank">Documentation</a>
							</div>
						</div>
						<div class="hfc-container-ad">
							<a href="https://pluginic.com/plugins/gutenberg-post-blocks/" target="_blank">
								<picture>
									<source media="(max-width:960px)" srcset="<?php echo esc_url( WPPSGS_URL . 'admin/img/banner-960x340.jpg' ); ?>">
									<img src="<?php echo esc_url( WPPSGS_URL . 'admin/img/banner-340x520.jpg' ); ?>">
								</picture>
							</a>
						</div>
					</div>
				</div>
				<div class="hfc-spacer" style="height: 20px;"></div>
				<div class="hfc-hero-upgrade">
					<h2><span class="dashicons dashicons-superhero-alt"></span>Pro Feature List :</h2>
					<div class="hfc-upgrade-feature-list">
						<ul>
							<li>Fully responsive, SEO-friendly & optimized.</li>
							<li>Advanced Shortcode Generator.</li>
							<li>Advanced Shortcode Generator.</li>
							<li>Slide Anything (e.g. Image, Post, Product, Content, Video, Text, HTML, Shortcodes, etc.)</li>
							<li>Display posts from multiple Categories, Tags, Formats, or Types. (e.g. Latest, Taxonomies, Specific, etc.).</li>
							<li>Multiple Carousels on the same page.</li>
							<li>Multiple Carousels on the same page.</li>
							<li>100+ Visual Customization options.</li>
							<li>Drag & Drop Carousel builder (image, content, video, etc.).</li>
							<li>Drag & Drop Carousel builder (image, content, video, etc.).</li>
							<li>Image Carousel with internal and external links.</li>
							<li>Image Carousel with caption and description.</li>
						</ul>
						<ul>
							<li>Image Content Position (Bottom, Top, Right, and Overlay).</li>
							<li>Show/hide image caption and description.</li>
							<li>Post Carousel with Title, image, excerpt, read more, category, date, author, tags, comments, etc.).</li>
							<li>Post excerpt, full content, and content with the limit.</li>
							<li>WooCommerce Product Carousel.</li>
							<li>Show/hide the standard product contents (product name, image, price, excerpt, read more, rating, add to cart, etc.).</li>
							<li>Supported YouTube, Vimeo, Dailymotion, mp4, WebM, and even self-hosted video.</li>
							<li>Add Custom Video Thumbnails (for self-hosted) and video icon.</li>
							<li>Carousel Mode (standard, center, ticker).</li>
							<li>8+ Different navigation positions.</li>
							<li>Typography & Styling options (840+ Google fonts).</li>
						</ul>
					</div>
					<a class="hfc-hero-btn-pro" href="#" target="_blank">Upgrade to Pro <span>→</span></a>
				</div>
				<div class="hfc-spacer" style="height: 20px;"></div>
				<div class="hfc-testimonial">
					<div class="hfc-testimonial-columns">
						<div class="hfc-testimonial-column">
							<span class="hfc-testimonial-stars"></span>
							<p style="font-size:18px;line-height:1.3;margin-bottom:15px">“I have tried many plugins and this is the best. It is easy to use, has so many themes, and is free!</p>
							<div class="hfc-testimonial-client">
								<img width="50" height="50" src="<?php echo esc_url( WPPSGS_URL . 'admin/img/client-1.jpg' ); ?>" alt="" class="wp-image-3273">
								<div class="hfc-testimonial-client-ghost">
									<h4>Roman Rybakov</h4>
									<p>Frontend Engineer</p>
								</div>
							</div>
						</div>
						<div class="hfc-testimonial-column">
							<span class="hfc-testimonial-stars"></span>
							<p style="font-size:18px;line-height:1.3;margin-bottom:15px">“I have tried many plugins and this is the best. It is easy to use, has so many themes, and is free!</p>
							<div class="hfc-testimonial-client">
								<img width="50" height="50" src="<?php echo esc_url( WPPSGS_URL . 'admin/img/client-2.jpg' ); ?>" alt="" class="wp-image-3273">
								<div class="hfc-testimonial-client-ghost">
									<h4>Roman Rybakov</h4>
									<p>Frontend Engineer</p>
								</div>
							</div>
						</div>
						<div class="hfc-testimonial-column">
							<span class="hfc-testimonial-stars"></span>
							<p style="font-size:18px;line-height:1.3;margin-bottom:15px">“I have tried many plugins and this is the best. It is easy to use, has so many themes, and is free!</p>
							<div class="hfc-testimonial-client">
								<img width="50" height="50" src="<?php echo esc_url( WPPSGS_URL . 'admin/img/client-3.jpg' ); ?>" alt="" class="wp-image-3273">
								<div class="hfc-testimonial-client-ghost">
									<h4>Roman Rybakov</h4>
									<p>Frontend Engineer</p>
								</div>
							</div>
						</div>
						<div class="hfc-testimonial-column">
							<span class="hfc-testimonial-stars"></span>
							<p style="font-size:18px;line-height:1.3;margin-bottom:15px">“I have tried many plugins and this is the best. It is easy to use, has so many themes, and is free!</p>
							<div class="hfc-testimonial-client">
								<img width="50" height="50" src="<?php echo esc_url( WPPSGS_URL . 'admin/img/client-4.jpg' ); ?>" alt="" class="wp-image-3273">
								<div class="hfc-testimonial-client-ghost">
									<h4>Roman Rybakov</h4>
									<p>Frontend Engineer</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Add action links.
	 *
	 * @param Array $actions Get all action links.
	 * @param Sting $plugin_file Get all plugin file paths.
	 * @return Array
	 */
	public function wppsgs_add_action_plugin( $actions, $plugin_file ) {

		static $plugin;

		if ( ! isset( $plugin ) ) {

			$plugin = WPPSGS_BASENAME_FILE;
		}

		if ( $plugin == $plugin_file ) {

			$site_link = array( 'support' => '<a href="#" target="_blank">Support</a>' );
			$settings  = array( 'settings' => '<a href="#">' . __( 'Settings', 'General' ) . '</a>' );

			// Add link before Deactivate.
			$actions = array_merge( $site_link, $actions );
			$actions = array_merge( $settings, $actions );

			// Add link after Deactivate.
			$actions[] = '<a href="#">' . __( '<svg style="width: 14px;height: 14px;margin-bottom: -2px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><path fill="#4caf50" d="M35 19c0-2.062-.367-4.039-1.04-5.868-.46 5.389-3.333 8.157-6.335 6.868-2.812-1.208-.917-5.917-.777-8.164.236-3.809-.012-8.169-6.931-11.794 2.875 5.5.333 8.917-2.333 9.125-2.958.231-5.667-2.542-4.667-7.042-3.238 2.386-3.332 6.402-2.333 9 1.042 2.708-.042 4.958-2.583 5.208-2.84.28-4.418-3.041-2.963-8.333C2.52 10.965 1 14.805 1 19c0 9.389 7.611 17 17 17s17-7.611 17-17z"/><path fill="#cddc39" d="M28.394 23.999c.148 3.084-2.561 4.293-4.019 3.709-2.106-.843-1.541-2.291-2.083-5.291s-2.625-5.083-5.708-6c2.25 6.333-1.247 8.667-3.08 9.084-1.872.426-3.753-.001-3.968-4.007C7.352 23.668 6 26.676 6 30c0 .368.023.73.055 1.09C9.125 34.124 13.342 36 18 36s8.875-1.876 11.945-4.91c.032-.36.055-.722.055-1.09 0-2.187-.584-4.236-1.606-6.001z"/></svg><span style="font-weight: bold;color: #4caf50;"> Go Pro</span>', 'General' ) . '</a>';
		}

		return $actions;
	}

}
