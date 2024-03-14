<?php

/**
 * @class XPROIconBoxModule
 */

if ( ! class_exists( 'XPROPostTitleModule' ) ) {

	class XPROPostTitleModule extends FLBuilderModule {


		/**
		 * @method __construct
		 */
		public function __construct() {
			parent::__construct(
				array(
					'name'            => __( 'Post Title', 'xpro-bb-addons' ),
					'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
					'group'           => XPRO_Plugins_Helper::$branding_modules,
					'category'        => XPRO_Plugins_Helper::$themer_modules,
					'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-post-title/',
					'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-post-title/',
					'partial_refresh' => true,
				)
			);
		}


		/**
		 * Get First/Current Product id
		 *
		 * @method @get_product_id
		 */
		public static function get_product_id( $i ) {
			global $woocommerce;
			global $wpdb;
			global $product;

			$type         = 'product';
			$product_name = $product;

			// current post id
			$current_post = $wpdb->get_results(
				$wpdb->prepare(
					"
			SELECT ID FROM {$wpdb->posts}
			WHERE post_type = %s AND post_status = 'publish' AND post_name = '$product_name' limit 1",
					$type
				)
			);

			foreach ( $current_post as $c_post ) {
				$curr_post_id = $c_post->ID;
			}

			// first post id
			$first_posts = $wpdb->get_results(
				$wpdb->prepare(
					"
			SELECT ID FROM {$wpdb->posts}
			WHERE post_type = %s AND post_status = 'publish' 
			ORDER BY post_date ASC limit 1",
					$type
				)
			);

			foreach ( $first_posts as $f_post ) {
				$f_post_id = $f_post->ID;
			}

			if ( ! empty( $product ) ) {
				$post_id = $curr_post_id;
			} else {
				$post_id = $f_post_id;
			}

			return $post_id;
		}
	}

	/**
	 * Register the module and its form settings.
	 */
	FLBuilder::register_module(
		'XPROPostTitleModule',
		array(
			'general' => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'content' => array(
						'title'  => __( 'General', 'xpro-bb-addons' ),
						'fields' => array(
							'title_tag'     => array(
								'type'    => 'select',
								'label'   => __( 'HTML Tag', 'xpro-bb-addons' ),
								'default' => 'h1',
								'options' => array(
									'h1'   => __( 'H1', 'xpro-bb-addons' ),
									'h2'   => __( 'H2', 'xpro-bb-addons' ),
									'h3'   => __( 'H3', 'xpro-bb-addons' ),
									'h4'   => __( 'H4', 'xpro-bb-addons' ),
									'h5'   => __( 'H5', 'xpro-bb-addons' ),
									'h6'   => __( 'H6', 'xpro-bb-addons' ),
									'span' => __( 'Span', 'xpro-bb-addons' ),
									'div'  => __( 'Div', 'xpro-bb-addons' ),
								),
							),
							'post_align'    => array(
								'type'       => 'align',
								'label'      => 'Alignment',
								'default'    => 'left',
								'responsive' => true,
								'preview'    => array(
									'type'      => 'css',
									'selector'  => '.fl-module-content.fl-node-content',
									'property'  => 'text-align',
									'important' => true,
								),
							),
							'title_color'   => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'  => 'css',
									'rules' => array(
										array(
											'selector' => '.xpro-post-title-text',
											'property' => 'color',
										),
									),
								),
							),
							'title_padding' => array(
								'type'       => 'dimension',
								'label'      => __( 'Title Padding', 'xpro' ),
								'slider'     => true,
								'units'      => array( 'px' ),
								'responsive' => true,
								'preview'    => array(
									'type'      => 'css',
									'selector'  => '.xpro-post-title-text',
									'property'  => 'padding',
									'unit'      => 'px',
									'important' => true,
								),
							),
							'title_margin'  => array(
								'type'       => 'dimension',
								'label'      => __( 'Title Margin', 'xpro' ),
								'slider'     => true,
								'units'      => array( 'px' ),
								'responsive' => true,
								'preview'    => array(
									'type'      => 'css',
									'selector'  => '.xpro-post-title-wrapper',
									'property'  => 'margin',
									'unit'      => 'px',
									'important' => true,
								),
							),
						),
					),
				),
			),
			'typo'    => array(
				'title'    => __( 'Typography', 'xpro-bb-addons' ),
				'sections' => array(
					'title' => array(
						'title'  => __( 'Title', 'xpro-bb-addons' ),
						'fields' => array(
							'title_typography' => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-title-text',
								),
							),
						),
					),
				),
			),
		)
	);
}
