<?php

namespace EazyGrid\Elementor\Classes;

/**
 * Admin Menu
 */
class Dashboard {


	/**
	 * Kick-in the class
	 */
	public function __construct() {
		add_action( 'admin_menu', array($this, 'admin_menu') );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_scripts'] );
	}


	public function enqueue_scripts() {
		wp_enqueue_style(
			'eazygrid-elementor-style',
			EAZYGRIDELEMENTOR_URL . 'assets/admin/css/dashboard.css',
			null,
			EAZYGRIDELEMENTOR_VERSION
		);
	}

	/**
	 * Add menu items
	 *
	 * @return void
	 */
	public function admin_menu() {
		/** Top Menu **/
		add_menu_page(
			__( 'EazyGrid', 'eazygrid-elementor' ),
			__( 'EazyGrid', 'eazygrid-elementor' ),
			'manage_options',
			'eazygrid',
			array($this, 'plugin_page'),
			'dashicons-eazygrid',
			null
		);

		add_submenu_page(
			'eazygrid',
			__( 'EazyGrid', 'eazygrid-elementor' ),
			__( 'EazyGrid', 'eazygrid-elementor' ),
			'manage_options',
			'eazygrid',
			array($this, 'plugin_page')
		);
	}

	/**
	 * Handles the plugin page
	 *
	 * @return void
	 */
	public function plugin_page() {
		?>
		<!-- <img class="header-banner" src="<?php echo esc_url( EAZYGRIDELEMENTOR_URL ) . 'assets/img/admin/eazygrid-dashboard-banner.jpg'; ?>" alt=""> -->
		<div class="header-banner">
			<h1>EazyGrid for Elementor</h1>
		</div>
		<div id="grid-pattern">
			<div class="content">
				<img width="60" src="<?php echo esc_url( EAZYGRIDELEMENTOR_URL ) . 'assets/img/admin/icon-brick-wall.svg'; ?>" style="margin-bottom: 15px" loading="lazy">
				<h2 class="infobox-title">Prebuilt grid pattern</h2>
				<div class="infobox-text">
					<p>Expertly crafted prebuilt grid patterns bring the focus to your content. Our grids are created after thorough research in UI/UX that offers the most convenient options.</p>
				</div>
				<img width="69" height="17" src="<?php echo esc_url( EAZYGRIDELEMENTOR_URL ) . 'assets/img/admin/zigzag-shape.svg'; ?>" style="margin-top: 50px" loading="lazy">
			</div>
			<div class="media">
				<img src="<?php echo esc_url( EAZYGRIDELEMENTOR_URL ) . 'assets/img/admin/eazygrid-dashboard-pattern.gif'; ?>" loading="lazy">
			</div>
		</div>

		<div id="grid-features">
			<div class="feature">
				<img src="<?php echo esc_url( EAZYGRIDELEMENTOR_URL ) . 'assets/img/admin/icon-lightbox.svg'; ?>" alt="">
				<h3>Easy Lightbox</h3>
				<div class="infobox-text">
					<p>EazyGrid treats your image right with fantastic lightbox support out of the box. With EazyGrid lightbox, your images will get the royal treatment they deserve. </p>
				</div>
			</div>
			<div class="feature">
				<img src="<?php echo esc_url( EAZYGRIDELEMENTOR_URL ) . 'assets/img/admin/icon-shape-mix.svg'; ?>" alt="">
				<h3>Responsive design</h3>
				<div class="infobox-text">
					<p>EazyGrid will adopt to your screen size, not the other way around. Now your grid will look stunning no matter where you are viewing it from. </p>
				</div>
			</div>
			<div class="feature">
				<img src="<?php echo esc_url( EAZYGRIDELEMENTOR_URL ) . 'assets/img/admin/icon-blog-content.svg'; ?>" alt="">
				<h3>Optimized Performance</h3>
				<div class="infobox-text">
					<p>EazyGrid is precisely engineered for minimum overhead. Not only it is super lightweight, but also extremely fast. One thing is for certain, EazyGrid won’t bog down your website’s performance.
					</p>
				</div>
			</div>
		</div>
		<?php
	}
}
