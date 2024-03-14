<?php

/**
 * @class XPROIconBoxModule
 */

if ( ! class_exists( 'XPROPostContentModule' ) ) {

	class XPROPostContentModule extends FLBuilderModule {

		/**
		 * @method __construct
		 */
		public function __construct() {
			parent::__construct(
				array(
					'name'            => __( 'Post Content', 'xpro-bb-addons' ),
					'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
					'group'           => XPRO_Plugins_Helper::$branding_modules,
					'category'        => XPRO_Plugins_Helper::$themer_modules,
					'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-post-content/',
					'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-post-content/',
					'partial_refresh' => true,
				)
			);
		}

		/**
		 * Get First/Current Post id
		 *
		 * @method @get_post_id
		 */
		public static function get_post_id( $i ) {
			global $wpdb;
			global $post;

			$post_type = $post->post_type;
			if ( 'xpro-themer' === $post_type ) {
				$type        = 'post';
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
			}

			if ( 'xpro-themer' === $post->post_type ) {
				$post_id = $f_post_id;
			} else {
				$post_id = $post->ID;
			}

			return $post_id;
		}
	}

	/**
	 * Register the module and its form settings.
	 */
	FLBuilder::register_module(
		'XPROPostContentModule',
		array(
			'general' => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'content' => array(
						'title'  => __( 'General', 'xpro-bb-addons' ),
						'fields' => array(
							'content_type' => array(
								'type'    => 'button-group',
								'label'   => __( 'Content Type', 'xpro-bb-addons' ),
								'default' => 'full',
								'options' => array(
									'excerpt' => __( 'Excerpt', 'xpro-bb-addons' ),
									'full'    => __( 'Full Content', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'excerpt' => array(
										'fields' => array( 'limit' ),
									),
								),
							),
							'limit'        => array(
								'type'       => 'unit',
								'label'      => __( 'Excerpt', 'xpro-bb-addons' ),
								'responsive' => true,
								'default'    => 10,
								'slider'     => true,
								'ms'         => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
							),
							'align'        => array(
								'type'       => __( 'align', 'xpro-bb-addons' ),
								'label'      => 'Alignment',
								'default'    => 'left',
								'responsive' => true,
							),
						),
					),
				),
			),
			'style'   => array(
				'title'    => __( 'Style', 'xpro-bb-addons' ),
				'sections' => array(
					'title' => array(
						'title'  => __( 'Title', 'xpro-bb-addons' ),
						'fields' => array(
							'content_typography' => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-content',
								),
							),
							'content_color'      => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-content',
									'property' => 'color',
								),
							),
							'link_content_type'  => array(
								'type'    => 'button-group',
								'label'   => __( 'Color Type', 'xpro-bb-addons' ),
								'default' => 'before-title',
								'options' => array(
									'normal' => __( 'Normal', 'xpro-bb-addons' ),
									'hover'  => __( 'Hover', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'normal' => array(
										'fields' => array( 'link_color' ),
									),
									'hover'  => array(
										'fields' => array( 'link_hv_color' ),
									),
								),
							),
							'link_color'         => array(
								'type'       => 'color',
								'label'      => __( 'Link Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => 'a',
									'property' => 'color',
								),
							),
							'link_hv_color'      => array(
								'type'       => 'color',
								'label'      => __( 'Link Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => 'a:hover',
									'property' => 'color',
								),
							),
						),
					),
				),
			),
		)
	);
}
