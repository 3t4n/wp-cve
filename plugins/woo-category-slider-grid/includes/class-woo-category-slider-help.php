<?php
/**
 * The plugin help page.
 *
 * @link       https://shapedplugin.com/
 * @since      1.1.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/includes
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

/**
 * Woo_Category_Slider_Help class.
 */
class Woo_Category_Slider_Help {

	/**
	 * Add SubMenu Page
	 */
	public function help_page() {
		add_submenu_page( 'edit.php?post_type=sp_wcslider', __( 'Category Slider for WooCommerce Help', 'woo-category-slider-grid' ), __( 'Help', 'woo-category-slider-grid' ), 'manage_options', 'wcsp_help', array( $this, 'help_page_callback' ) );
	}

	/**
	 * Help Page Callback
	 */
	public function help_page_callback() {
		wp_enqueue_style( 'woo-category-free-admin-help', SP_WCS_URL . 'admin/css/help-page.min.css', array(), SP_WCS_VERSION );
		$add_new_category_link = admin_url( 'post-new.php?post_type=sp_wcslider' );
		?>

<div class="sp-woo-cat-slider-help-page">
		<!-- Header section start -->
		<section class="sp-wcsp-help header">
			<div class="header-area">
				<div class="container">
					<div class="header-logo">
					<img src="<?php echo esc_url( SP_WCS_URL . 'admin/css/images/woo-category-slider-logo.svg' ); ?>" alt="">
					<span><?php echo esc_html( SP_WCS_VERSION ); ?></span>
					</div>
					<div class="header-content">
						<p>Thank you for installing Category Slider for WooCommerce plugin! This video will help you get started with the plugin.</p>
					</div>
				</div>
			</div>
			<div class="video-area">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?list=PLoUb-7uG-5jMzpAUaCkor0Ldc4F_7gfjz" frameborder="0" allowfullscreen=""></iframe>
			</div>
			<div class="content-area">
				<div class="container">
					<div class="content-button">
						<a href="<?php echo esc_url( $add_new_category_link ); ?>">Start Creating Slider</a>
						<a href="https://docs.shapedplugin.com/docs/woocommerce-category-slider/introduction/" target="_blank">Read Documentation</a>
					</div>
				</div>
			</div>
		</section>
		<!-- Header section end -->

		<!-- Upgrade section start -->
		<section class="sp-wcsp-help upgrade">
			<div class="upgrade-area">
				<div class="upgrade-img">
				<img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/wcs-icon.svg'; ?>" alt="">
				</div>
				<h2>Upgrade To WooCommerce Category Slider Pro</h2>
				<p>Display by filtering your WooCommerce products categories atheistically in strategic position 
to your shop or site and boost sales magically!.</p>
			</div>
			<div class="upgrade-info">
				<div class="container">
					<div class="row">
						<div class="col-lg-6">
							<ul class="upgrade-list">
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">
								Super fast, powerful, and slick.
							</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">
								3+ Layout presets (Slider, Grid, and Block).</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">
								Multiple category Slider or Grid display on the same page.</li>
<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">Filter & display the list of categories you want.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">Parent and First level child selection option.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt=""> Display specific categories with the child.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt=""> Exclude the categories which you don't want to display.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">
								Display child categories beside, below parent.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">Show child categories only.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">
								Child categories product count.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt=""> Hide category without thumbnail.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt=""> Display category content (Category name, child, thumbnail, icon, counter, custom text, description, shop now button, etc.)</li>
							</ul>
						</div>
						<div class="col-lg-6">
							<ul class="upgrade-list">								
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">
								5 Category content positions.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">
								900+ Google Fonts (typography, colors).</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">
								915+ Category flat icons library.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">

								20+ Slider controls including ticker slider.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">

								Multiple rows in the slider.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">

								RTL & multilingual ready.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">

								Multisite supported.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">
								Show/hide every element.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">
								Thumbnail custom size, 3 shapes, grayscale, zoom, box-shadow, padding, margin, etc.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">
								Advanced settings and custom CSS options.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">
								Page builders compatible.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">
								Frequently updated and secured codebase.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt="">
								Exclusive top-notch support.</li>
								<li><img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/checkmark.svg'; ?>" alt=""><span>
Not Happy? 100% No Questions Asked <a href="https://shapedplugin.com/refund-policy/" target="_blank">Refund Policy!</a></span></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="upgrade-pro">
					<div class="pro-content">
						<div class="pro-icon">
							<img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/wcs-pro.svg'; ?>" alt="">
						</div>
						<div class="pro-text">
							<h2>WooCommerce Category Slider Pro</h2>
							<p>A Simple Tool for a More Professional and Converting Store!</p>
						</div>
					</div>
					<div class="pro-btn">
						<a href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/?ref=115" target="_blank">Upgrade To Pro Now</a>
					</div>
				</div>
			</div>
		</section>
		<!-- Upgrade section end -->

		<!-- Testimonial section start -->
		<section class="sp-wcsp-help testimonial">
			<div class="row">
				<div class="col-lg-6">
					<div class="testimonial-area">
						<div class="testimonial-content">
							<p>Nice plugin and it does what it promises. Well written, good UI. Works like a charm! I’m very happy with the high quality pro plugin from ShapedPlugin. </p>
						</div>
						<div class="testimonial-info">
							<div class="img">
								<img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/Eeva-Lamminen.jpeg'; ?>" alt="">
							</div>
							<div class="info">
								<h3>Eeva Lamminen</h3>
								<p>WordPress Developer</p>
								<div class="star">
								<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="testimonial-area">
						<div class="testimonial-content">
							<p>Very Useful plugin! Good slider for displaying product categories. Useful and easy to use! I advise Category Slider for WooCommerce to everyone. Thank you!</p>
						</div>
						<div class="testimonial-info">
							<div class="img">
								<img src="<?php echo esc_url( SP_WCS_URL ) . 'admin/css/images/Silperman.jpeg'; ?>" alt="">
							</div>
							<div class="info">
								<h3>Silperman</h3>
								<p>Web Designer</p>
								<div class="star">
								<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Testimonial section end -->

</div>
		<?php
	}

}
