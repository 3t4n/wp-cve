<?php
/**
 * Main file for omnipress plugin.
 *
 * @package Omnipress
 */

namespace Omnipress;

use Omnipress\Abstracts\BlockTemplateBase;
use Omnipress\Admin\Init as AdminInit;
use Omnipress\Publics\Init as PublicsInit;
use Omnipress\RestApi\RestApi;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main file for omnipress plugin.
 *
 * @since 1.1.0
 */
class Init {

	/**
	 * This object instance.
	 *
	 * @var \Omnipress\Init
	 */
	protected static $instance = null;

	/**
	 * Returns this object instance.
	 *
	 * @return \Omnipress\Init
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class construct.
	 */
	public function __construct() {
		Blocks::init();
		AdminInit::init();
		PublicsInit::init();
		RestApi::init();
		BlockTemplateBase::init();
	}

	public function filter_theme_json_theme( $theme_json ) {
		$new_data = array(
			'version'  => 2,
			'styles'   => json_decode(
				'{
									"elements": {
										"heading": {
											"typography": {
												"fontFamily": "var(--wp--preset--font-family--inter)"
											}
										},
										"link": {
											"typography": {
												"textDecoration": "none"
											}
										}
									},
									"typography": {
										"fontFamily": "var(--wp--preset--font-family--inter)",
										"fontSize": "var(--wp--preset--font-size--18)"
									},
									"css": "\n/* product-card-carousel */\n@media (max-width: 1024px) {\n\n.gd-home-offer ul.wp-block-post-template {\n    display: flex !important;\n    overflow: auto;\n}\n\n.gd-home-offer ul.wp-block-post-template li {\n     display: flex !important;\n     text-align: center;\n     flex-shrink: 0;\n}\n\n}\n\n\n.product-card-carousel ul .product:hover .wc-block-components-product-image,\n.product-card-carousel ul .product:hover h3.wp-block-post-title,\n.product-card-carousel ul .product:hover .wc-block-grid__product-price {\n-webkit-transform: translateY(0px) !important;\n    transform: translateY(0px) !important;\n}\n\n\n\n/*Home form*/\n.home-newsletter-form.wpforms-container {\nmargin: 0 !important;\n}\n\n.home-newsletter-form form.wpforms-form {\nposition: relative;\nmax-width: 400px;\ndisplay: flex !important;\njustify-content: space-between;\nalign-items: flex-start;\ngap: 8px;\nborder: 1px solid white;\nborder-radius: 8px;\npadding: 16px 16px;\n}\n\n.home-newsletter-form form.wpforms-form .wpforms-field.wpforms-field-email {\npadding: 0;\n}\n\n.home-newsletter-form form.wpforms-form .wpforms-field.wpforms-field-email input {\nmax-width: 100%;\n}\n\n.home-newsletter-form form.wpforms-form .wpforms-submit-container {\n    margin: 0 !important;\n    padding: 0 !important;\n}",
									"color": {
										"background": "#fafafa"
									}
								}', true
			),
			'settings' => json_decode(
				'{
								"color": {
									"palette": {
										"theme": [
											{
												"slug": "primary",
												"name": "Primary",
												"color": "#0135b9"
											},
											{
												"slug": "secondary",
												"name": "Secondary",
												"color": "#ffe600"
											},
											{
												"slug": "foreground-color",
												"name": "Foreground color",
												"color": "#121212"
											},
											{
												"slug": "bg-color",
												"name": "Background color",
												"color": "#ffffff"
											},
											{
												"slug": "heading",
												"name": "Heading",
												"color": "#242424"
											},
											{
												"slug": "paragraph",
												"name": "Paragraph",
												"color": "#333333"
											},
											{
												"slug": "lightcolor",
												"name": "Light Color",
												"color": "#fefefe"
											},
											{
												"slug": "bd-color",
												"name": "Border color",
												"color": "#f3f3f3"
											},
											{
												"slug": "transparent",
												"name": "Transparent",
												"color": "transparent"
											},
											{
												"slug": "spring-green",
												"name": "Spring Green",
												"color": "#91BE0B"
											},
											{
												"slug": "celery",
												"name": "Celery",
												"color": "#83B400"
											},
											{
												"slug": "kelly-green",
												"name": "Kelly Green",
												"color": "#1F7009"
											},
											{
												"slug": "lagoon",
												"name": "Lagoon",
												"color": "#3BBE94"
											},
											{
												"slug": "mermaid",
												"name": "Mermaid",
												"color": "#01A58A"
											},
											{
												"slug": "turquoise",
												"name": "Turquoise",
												"color": "#24BBC2"
											},
											{
												"slug": "peacock",
												"name": "Peacock",
												"color": "#006776"
											},
											{
												"slug": "iris",
												"name": "Iris",
												"color": "#5C58A4"
											},
											{
												"slug": "beet",
												"name": "Beet",
												"color": "#650044"
											},
											{
												"slug": "purple",
												"name": "Purple",
												"color": "#5E047F"
											},
											{
												"slug": "lemon",
												"name": "Lemon",
												"color": "#FBE201"
											},
											{
												"slug": "gold",
												"name": "Gold",
												"color": "#FDC003"
											},
											{
												"slug": "latte",
												"name": "Latte",
												"color": "#D2BF9E"
											},
											{
												"slug": "mocha",
												"name": "Mocha",
												"color": "#714F2A"
											},
											{
												"slug": "slate",
												"name": "Slate",
												"color": "#3B453C"
											}
										]
									}
								}', true
			),
		);

		return $theme_json->update_with( $new_data );
	}
}
