<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 * 
 * @since 1.0.0
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">
	<?php 
	/**
	* Show page title
	* 
	* @since 1.0.0
	*/
	if ( apply_filters( 'woocommerce_show_page_title', true ) ) : 
		?>
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 * 
	 * @since 1.0.0
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>
<?php

$cate = get_queried_object();

echo wp_kses("<div class=\"snize-navigation-wrapper\" data-snize-category-id=\"{$cate->term_id}\"><div class=\"snize\" id=\"snize_results\"></div></div>", array(
	'div' => array(
		'class' => array(),
		'data-snize-category-id' => array(),
		'id' => array(),
	),
));

/**
 * Hook: woocommerce_after_main_content.
 * 
 * @since 1.0.0
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 * 
 * @since 1.0.0
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
