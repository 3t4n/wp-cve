<?php
/**
 * Define njba module group
 * @return mixed|string
 * @since 1.0.0
 */
function njba_get_modules_group() {
	$njba               = array();
	$njba_builder_label = '';
	if ( is_array( $njba ) ) {
		$njba_builder_label = ( array_key_exists( 'njba-builder-label', $njba ) ) ? $njba['njba-builder-label'] : esc_html__( 'NJBA Modules', 'bb-njba' );
	}
	if ( $njba_builder_label == '' ) {
		$njba_builder_label = esc_html__( 'NJBA Modules', 'bb-njba' );

		return $njba_builder_label;
	}

	return $njba_builder_label;
}

/**
 * Define njba Module Categories
 *
 * @param string $category
 *
 * @return array|mixed|string
 * @since 1.0.0
 */
function njba_get_modules_cat( $category = '' ) {
	$njba             = array();
	$njba_builder_cat = '';
	if ( is_array( $njba ) ) {
		$njba_builder_cat = ( array_key_exists( 'njba-builder-category', $njba ) ) ? $njba['njba-builder-category'] : esc_html__( 'NJBA', 'bb-njba' );
	}
	if ( $njba_builder_cat == '' ) {
		$njba_builder_cat = esc_html__( 'NJBA', 'bb-njba' );
	}
	$default = 'default';
	$new     = 'new';
	$cats    = array(
		'social'     => sprintf( __( 'Social Modules - %s', 'bb-njba' ), $njba_builder_cat ),
		'carousel'   => sprintf( __( 'Carousel Modules - %s', 'bb-njba' ), $njba_builder_cat ),
		'content'    => sprintf( __( 'Content Modules - %s', 'bb-njba' ), $njba_builder_cat ),
		'creative'   => sprintf( __( 'Creative Modules - %s', 'bb-njba' ), $njba_builder_cat ),
		'form_style' => sprintf( __( 'Form Style Modules - %s', 'bb-njba' ), $njba_builder_cat ),
		'separator'  => sprintf( __( 'Separator Modules - %s', 'bb-njba' ), $njba_builder_cat ),
	);
	if ( empty( $category ) ) {
		return $cats/*[$default]*/ ;
	}

	if ( isset( $cats[ $category ] ) ) {
		return $cats[ $category ];
	}

	return $category;
}

function njbaProModulesList() {
	return array(
		array(
			'module_name'           => 'Advanced Accordion',
			'module_slug'           => 'njba-accordion',
			'module_license_key'    => 'njba_accordion_license_key',
			'module_license_status' => 'njba_accordion_license_status'
		),
		array(
			'module_name'           => 'Advanced Tabs',
			'module_slug'           => 'njba-advanced-tabs',
			'module_license_key'    => 'njba_advanced_tabs_license_key',
			'module_license_status' => 'njba_advanced_tabs_license_status'
		),
		array(
			'module_name'           => 'Audio',
			'module_slug'           => 'njba-audio',
			'module_license_key'    => 'njba_audio_license_key',
			'module_license_status' => 'njba_audio_license_status'
		),
		array(
			'module_name'           => 'Before After Slider',
			'module_slug'           => 'njba-after-before-slider',
			'module_license_key'    => 'njba_after_before_slider_license_key',
			'module_license_status' => 'njba_after_before_slider_license_status'
		),

		array(
			'module_name'           => 'Forms',
			'module_slug'           => 'njba-forms-options',
			'module_license_key'    => 'njba_forms_options_license_key',
			'module_license_status' => 'njba_forms_options_license_status'
		),
		array(
			'module_name'           => 'Blog Post Content',
			'module_slug'           => 'njba-blog-post',
			'module_license_key'    => 'njba_blog_post_license_key',
			'module_license_status' => 'njba_blog_post_license_status'
		),

		array(
			'module_name'           => 'Countdown',
			'module_slug'           => 'njba-countdown',
			'module_license_key'    => 'njba_countdown_license_key',
			'module_license_status' => 'njba_countdown_license_status'
		),
		array(
			'module_name'           => 'Counter',
			'module_slug'           => 'njba-counter',
			'module_license_key'    => 'njba_counter_license_key',
			'module_license_status' => 'njba_counter_license_status'
		),
		array(
			'module_name'           => 'Dual Button',
			'module_slug'           => 'njba-dual-button',
			'module_license_key'    => 'njba_dual_button_license_key',
			'module_license_status' => 'njba_dual_button_license_status'
		),

		array(
			'module_name'           => 'Image Carousel',
			'module_slug'           => 'njba-image-carousel',
			'module_license_key'    => 'njba_image_carousel_license_key',
			'module_license_status' => 'njba_image_carousel_license_status'
		),
		array(
			'module_name'           => 'Logo Grid & Carousel',
			'module_slug'           => 'njba-logo-grid-carousel',
			'module_license_key'    => 'njba_logo_grid_carousel_license_key',
			'module_license_status' => 'njba_logo_grid_carousel_license_status'
		),
		array(
			'module_name'           => 'Modal Box',
			'module_slug'           => 'njba-modal-box',
			'module_license_key'    => 'njba_modal_box_license_key',
			'module_license_status' => 'njba_modal_box_license_status'
		),
		array(
			'module_name'           => 'Polaroid',
			'module_slug'           => 'njba-polaroid-options',
			'module_license_key'    => 'njba_polaroid_options_license_key',
			'module_license_status' => 'njba_polaroid_options_license_status'
		),

		array(
			'module_name'           => 'Price Box',
			'module_slug'           => 'njba-price-box',
			'module_license_key'    => 'njba_price_box_license_key',
			'module_license_status' => 'njba_price_box_license_status'
		),
		array(
			'module_name'           => 'Quote Box Pro',
			'module_slug'           => 'njba-quote-box',
			'module_license_key'    => 'njba_quote_box_license_key',
			'module_license_status' => 'njba_quote_box_license_status'
		),
		array(
			'module_name'           => 'Teams Pro',
			'module_slug'           => 'njba-teams',
			'module_license_key'    => 'njba_teams_license_key',
			'module_license_status' => 'njba_teams_license_status'
		),
		array(
			'module_name'           => 'Testimonials Pro',
			'module_slug'           => 'njba-testimonials',
			'module_license_key'    => 'njba_testimonials_license_key',
			'module_license_status' => 'njba_testimonials_license_status'
		),
		array(
			'module_name'           => 'Timeline',
			'module_slug'           => 'njba-timeline',
			'module_license_key'    => 'njba_timeline_license_key',
			'module_license_status' => 'njba_timeline_license_status'
		)
	);
}


/**
 *admin notice for woo beaver
 * @since 1.0.2
 */
add_action( 'admin_notices', 'wooNjbaAdminNotice' );
add_action( 'network_admin_notices', 'wooNjbaAdminNotice' );
function wooNjbaAdminNotice() {
	//global $pagenow;
	$woo_njba_admin_notice = trim( get_option( 'woo-njba-notice-dismissed' ) );
	if ( empty( $woo_njba_admin_notice ) ) {
		$url           = admin_url( 'index.php' );
		$learn_more    = 'https://www.woobeaveraddons.com/';
		$documentation = 'https://www.woobeaveraddons.com/category/docs/';
		$image         = 'https://www.woobeaveraddons.com/wb-core/wp-content/uploads/2017/12/woo-logo.png';
		echo '<div class="notice notice-info is-dismissible woo-info"><div class="info-image"><p>';
		echo sprintf( __( "<img src='$image' alt='BB Ninja'>", 'bb-njba' ), $url );
		echo '</p></div><div class="info-descriptions"><div class="info-descriptions-title"><h3><strong>Introducing Woo Beaver</strong></h3></div><p>';
		echo sprintf( __( "You can create page templates for single product and category pages. you can also use single product module, product list module, grid modules and add to cart modules for woocommerce. You can create single product template for specific category products or also specific products too. You can easily set rules for it.</br></br><a href='$learn_more' target='_blank'>Learn More</a>   <a href='$documentation' target='_blank'>Documentation</a>",
			'bb-njba' ), $url );
		echo '</p></div></div>';
	}
}

/**
 *dismissed woo admin notice request
 * @since 1.0.2
 */
add_action( 'admin_footer', 'wooNjbaAdminNoticeScript' );
function wooNjbaAdminNoticeScript() {
	$woo_njba_admin_notice = trim( get_option( 'woo-njba-notice-dismissed' ) );
	if ( empty( $$woo_njba_admin_notice ) ) { ?>
        <script type="text/javascript">
            jQuery(document).on('click', '.woo-info .notice-dismiss', function () {
                jQuery.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'dismiss_woo_njba'
                    }
                })
            })
        </script>
		<?php
	}
}

/**
 *Removed woo admin notice
 * @since 1.0.2
 */
add_action( 'wp_ajax_dismiss_woo_njba', 'njbaSuccessfullyRemoveNotice' );
function njbaSuccessfullyRemoveNotice() {
	update_option( 'woo-njba-notice-dismissed', 'yes' );
	echo 'success';
	exit();
}
