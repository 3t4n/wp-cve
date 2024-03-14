<?php
/**
 * @class XPROTeamModule
 */

if ( ! class_exists( 'XPROTeamModule' ) ) {

	class XPROTeamModule extends FLBuilderModule {

		/**
		 * @method __construct
		 */
		public function __construct() {
			parent::__construct(
				array(
					'name'            => __( 'Xpro Team', 'xpro-bb-addons' ),
					'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
					'group'           => XPRO_Plugins_Helper::$branding_modules,
					'category'        => XPRO_Plugins_Helper::$content_modules,
					'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-team/',
					'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-team/',
					'partial_refresh' => true,
				)
			);
		}

		/**
		 * @method enqueue_scripts
		 */
		public function enqueue_scripts() {
			 // Already registered
			$this->add_js( 'jquery-bxslider' );

		}

	}

	/**
	 * Register the module and its form settings.
	 */
	FLBuilder::register_module(
		'XPROTeamModule',
		array(
			'general' => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'general' => array(
						'title'  => __( 'General', 'xpro-bb-addons' ),
						'fields' => array(
							'layout'      => array(
								'type'    => 'select',
								'label'   => __( 'Layout', 'xpro-bb-addons' ),
								'default' => '1',
								'options' => array(
									'1'  => __( 'Style 1', 'xpro-bb-addons' ),
									'2'  => __( 'Style 2', 'xpro-bb-addons' ),
									'3'  => __( 'Style 3', 'xpro-bb-addons' ),
									'4'  => __( 'Style 4', 'xpro-bb-addons' ),
									'5'  => __( 'Style 5', 'xpro-bb-addons' ),
									'6'  => __( 'Style 6', 'xpro-bb-addons' ),
									'7'  => __( 'Style 7', 'xpro-bb-addons' ),
									'8'  => __( 'Style 8', 'xpro-bb-addons' ),
									'9'  => __( 'Style 9', 'xpro-bb-addons' ),
									'10' => __( 'Style 10', 'xpro-bb-addons' ),
									'11' => __( 'Style 11', 'xpro-bb-addons' ),
									'12' => __( 'Style 12', 'xpro-bb-addons' ),
									'13' => __( 'Style 13', 'xpro-bb-addons' ),
									'14' => __( 'Style 14', 'xpro-bb-addons' ),
									'15' => __( 'Style 15', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'1'  => array(
										'fields' => array( 'align', 'content_background' ),
									),
									'2'  => array(
										'fields' => array( 'align', 'content_background' ),
									),
									'3'  => array(
										'fields' => array( 'align', 'content_background' ),
									),
									'4'  => array(
										'fields' => array( 'align', 'content_background' ),
									),
									'5'  => array(
										'fields' => array( 'align', 'image_overlay', 'content_background' ),
									),
									'6'  => array(
										'fields' => array( 'align', 'content_height', 'content_backdrop_blur', 'content_background' ),
									),
									'7'  => array(
										'fields' => array( 'align', 'content_background' ),
									),
									'8'  => array(
										'fields' => array( 'xpro-widget-seprator4', 'icon_wrapper_background', 'icon_wrapper_border', 'icon_wrapper_padding', 'icon_wrapper_margin' ),
									),
									'9'  => array(
										'fields' => array( 'xpro-widget-seprator4', 'icon_wrapper_background', 'image_padding', 'icon_wrapper_border', 'icon_wrapper_padding', 'separator_color' ),
									),
									'10' => array(
										'fields' => array( 'align', 'content_background' ),
									),
									'11' => array(
										'fields' => array( 'align', 'content_background' ),
									),
									'12' => array(
										'fields' => array( 'align', 'image_overlay' ),
									),
									'13' => array(
										'fields' => array( 'align', 'shape_color', 'shape_hcolor' ),
									),
									'14' => array(
										'fields' => array( 'align', 'content_background' ),
									),
									'15' => array(
										'fields' => array( 'align', 'icon_wrapper_bg', 'xpro-widget-seprator4', 'icon_wrapper_background', 'icon_wrapper_border', 'icon_wrapper_padding' ),
									),

								),
							),
							'image'       => array(
								'type'        => 'photo',
								'label'       => ' ',
								'show_remove' => false,
							),
							'title'       => array(
								'type'        => 'text',
								'label'       => __( 'Title', 'xpro-bb-addons' ),
								'placeholder' => __( 'Type Your Title Here', 'xpro-bb-addons' ),
								'default'     => __( 'John Walker', 'xpro-bb-addons' ),
							),
							'title_link'  => array(
								'type'          => 'link',
								'label'         => __( 'Link', 'xpro-bb-addons' ),
								'show_target'   => true,
								'show_nofollow' => true,
								'placeholder'   => __( 'https://your-link.com', 'xpro-bb-addons' ),
							),
							'designation' => array(
								'type'        => 'text',
								'label'       => __( 'Designation', 'xpro-bb-addons' ),
								'placeholder' => __( 'Type Your Designation Here', 'xpro-bb-addons' ),
								'default'     => __( 'Managing Director', 'xpro-bb-addons' ),
							),
							'description' => array(
								'type'          => 'editor',
								'label'         => __( 'Content', 'xpro-bb-addons' ),
								'placeholder'   => __( 'Type Your Designation Here', 'xpro-bb-addons' ),
								'default'       => __( 'It is a long established fact that a reader will be distracted by the content.', 'xpro-bb-addons' ),
								'wpautop'       => false,
								'media_buttons' => false,
								'connections'   => array( 'string', 'html' ),
							),
							'align'       => array(
								'type'       => 'align',
								'label'      => 'Alignment',
								'default'    => 'left',
								'responsive' => true,
							),
						),
					),
				),
			),
			'social'  => array(
				'title'    => __( 'Social', 'xpro-bb-addons' ),
				'sections' => array(
					'social-list' => array(
						'title'  => __( 'Social Item', 'xpro-bb-addons' ),
						'fields' => array(
							'social_icon_list' => array(
								'type'     => 'form',
								'label'    => __( 'Social Item', 'xpro-bb-addons' ),
								'form'     => 'xpro_team_social_icon_form',
								'multiple' => true,
								'default'  => array(
									array(
										'social_icon' => 'fab fa-facebook-f',
									),
									array(
										'social_icon' => 'fab fa-instagram',
									),
									array(
										'social_icon' => 'fab fa-twitter',
									),
								),
							),
						),
					),
				),
			),
			'style'   => array(
				'title'    => __( 'Style', 'xpro-bb-addons' ),
				'sections' => array(
					'image'   => array(
						'title'  => __( 'Image', 'xpro-bb-addons' ),
						'fields' => array(
							'width'                       => array(
								'type'         => 'unit',
								'label'        => 'Width',
								'units'        => array( 'px', 'vw', '%' ),
								'default_unit' => 'px',
								'responsive'   => true,
								'slider'       => true,
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-image > img',
									'property' => 'width',
								),
							),
							'height'                      => array(
								'type'         => 'unit',
								'label'        => 'Height',
								'units'        => array( 'px', 'vh' ),
								'default_unit' => 'px',
								'responsive'   => true,
								'slider'       => true,
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-image > img',
									'property' => 'height',
								),
							),
							'object_fit'                  => array(
								'type'    => 'select',
								'label'   => __( 'Background Type', 'xpro-bb-addons' ),
								'default' => '',
								'options' => array(
									''        => __( 'Default', 'xpro-bb-addons' ),
									'fill'    => __( 'Fill', 'xpro-bb-addons' ),
									'cover'   => __( 'Cover', 'xpro-bb-addons' ),
									'contain' => __( 'Contain', 'xpro-bb-addons' ),
								),
								'preview' => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-image > img',
									'property' => 'object-fit',
								),
							),
							'image_effects_type'          => array(
								'type'    => 'button-group',
								'label'   => __( 'Background Type', 'xpro-bb-addons' ),
								'default' => 'normal',
								'options' => array(
									'normal' => __( 'Normal', 'xpro-bb-addons' ),
									'hover'  => __( 'Hover', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'normal' => array(
										'fields' => array( 'shape_color', '' ),
									),
									'hover'  => array(
										'fields' => array( 'image_overlay', 'shape_hcolor' ),
									),
								),
							),
							'shape_color'                 => array(
								'type'       => 'color',
								'label'      => __( 'Shape Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-pricing-item-inner',
									'property' => 'background-color',
								),
							),
							'image_overlay'               => array(
								'type'       => 'color',
								'label'      => __( 'Overlay Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-layout-5 .xpro-team-image::before, .xpro-team-layout-12 .xpro-team-image::after',
									'property' => 'background-color',
								),
							),
							'shape_hcolor'                => array(
								'type'       => 'color',
								'label'      => __( 'Shape Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-layout-13:hover::after',
									'property' => 'background-color',
								),
							),
							'background_hover_transition' => array(
								'type'         => 'unit',
								'label'        => 'Transition Duration',
								'default_unit' => 's', // Optional
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-image img',
									'property' => 'transition-duration',
								),
							),
							'image_border'                => array(
								'type'       => 'border',
								'label'      => 'Border',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-image > img',
								),
							),
							'image_padding'               => array(
								'type'         => 'dimension',
								'label'        => __( 'Padding', 'xpro-bb-addons' ),
								'units'        => array( 'px', 'em', '%' ),
								'responsive'   => true,
								'default_unit' => 'px',
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-image',
									'property' => 'padding',
								),
							),
							'image_margin'                => array(
								'type'         => 'dimension',
								'label'        => __( 'Margin', 'xpro-bb-addons' ),
								'units'        => array( 'px', 'em', '%' ),
								'responsive'   => true,
								'default_unit' => 'px',
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-image > img',
									'property' => 'margin',
								),
							),
						),
					),
					'content' => array(
						'title'     => __( 'Content', 'xpro-bb-addons' ),
						'collapsed' => true,
						'fields'    => array(
							'content_height'         => array(
								'type'         => 'unit',
								'label'        => 'Height',
								'units'        => array( 'px', 'vh' ),
								'default_unit' => 'px',
								'responsive'   => true,
								'slider'       => true,
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-team-layout-6 .xpro-team-content',
									'property' => 'height',
								),
							),
							'content_backdrop_blur'  => array(
								'type'         => 'unit',
								'label'        => 'Backdrop Blur',
								'units'        => array( 'px', 'vh' ),
								'default_unit' => 'px',
								'responsive'   => true,
								'slider'       => true,
							),
							'content_background'     => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-content, .xpro-team-layout-9 .xpro-team-inner-content.xpro-pricing-item-inner',
									'property' => 'background-color',
								),
							),
							'content_border'         => array(
								'type'       => 'border',
								'label'      => 'Border',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-content',
								),
							),
							'separator_color'        => array(
								'type'       => 'color',
								'label'      => __( 'Separator Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-layout-9 .xpro-team-description::before',
									'property' => 'background-color',
								),
							),
							'content_padding'        => array(
								'type'         => 'dimension',
								'label'        => __( 'Padding', 'xpro-bb-addons' ),
								'units'        => array( 'px', 'em', '%' ),
								'responsive'   => true,
								'default_unit' => 'px',
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-content, .xpro-team-layout-9 .xpro-team-description',
									'property' => 'padding',
								),
							),
							'xpro-widget-seprator1'  => array(
								'type'    => 'raw',
								'content' => '<h2 class="xpro-widget-separator-heading">Title<hr></h2>',
							),
							'title_typography'       => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-title',
								),
							),
							'title_color'            => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-title',
									'property' => 'color',
								),
							),
							'title_margin'           => array(
								'type'         => 'dimension',
								'label'        => __( 'Margin', 'xpro-bb-addons' ),
								'units'        => array( 'px', 'em', '%' ),
								'responsive'   => true,
								'default_unit' => 'px',
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-title',
									'property' => 'margin',
								),
							),
							'xpro-widget-seprator2'  => array(
								'type'    => 'raw',
								'content' => '<h2 class="xpro-widget-separator-heading">Designation<hr></h2>',
							),
							'designation_typography' => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-designation',
								),
							),
							'designation_color'      => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-designation',
									'property' => 'color',
								),
							),
							'designation_margin'     => array(
								'type'         => 'dimension',
								'label'        => __( 'Margin', 'xpro-bb-addons' ),
								'units'        => array( 'px', 'em', '%' ),
								'responsive'   => true,
								'default_unit' => 'px',
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-designation',
									'property' => 'margin',
								),
							),
							'xpro-widget-seprator3'  => array(
								'type'    => 'raw',
								'content' => '<h2 class="xpro-widget-separator-heading">Description<hr></h2>',
							),
							'description_typography' => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-description',
								),
							),
							'description_color'      => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-description',
									'property' => 'color',
								),
							),
							'description_margin'     => array(
								'type'         => 'dimension',
								'label'        => __( 'Margin', 'xpro-bb-addons' ),
								'units'        => array( 'px', 'em', '%' ),
								'responsive'   => true,
								'default_unit' => 'px',
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-team-wrapper .xpro-team-description',
									'property' => 'margin',
								),
							),
						),
					),
					'social'  => array(
						'title'     => __( 'Social', 'xpro-bb-addons' ),
						'collapsed' => true,
						'fields'    => array(
							'icon_size'               => array(
								'type'         => 'unit',
								'label'        => 'Size',
								'units'        => array( 'px' ),
								'default_unit' => 'px',
								'responsive'   => true,
								'slider'       => true,
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-team-social-list .xpro-team-social-icon > i',
									'property' => 'font-size',
								),
							),
							'icon_bg_size'            => array(
								'type'         => 'unit',
								'label'        => 'Background Size',
								'units'        => array( 'px', 'vh' ),
								'default_unit' => 'px',
								'responsive'   => true,
								'slider'       => true,
								'preview'      => array(
									'type'  => 'css',
									'rules' => array(
										array(
											'selector' => '.xpro-team-social-list .xpro-team-social-icon',
											'property' => 'width',
										),
										array(
											'selector' => '.xpro-team-social-list .xpro-team-social-icon',
											'property' => 'height',
										),
									),
								),
							),
							'icon_space'              => array(
								'type'         => 'unit',
								'label'        => 'Space Between',
								'units'        => array( 'px' ),
								'default_unit' => 'px',
								'responsive'   => true,
								'slider'       => true,
								'preview'      => array(
									'type'  => 'css',
									'rules' => array(
										array(
											'selector' => '.xpro-team-wrapper .xpro-team-social-list > li',
											'property' => 'margin-right',
										),
										array(
											'selector' => '.xpro-team-layout-9 .xpro-team-social-list > li, .xpro-team-layout-13 .xpro-team-social-list > li, .xpro-team-layout-15 .xpro-team-social-list > li',
											'property' => 'margin-bottom',
										),
									),
								),
							),
							'social_icon_type'        => array(
								'type'    => 'button-group',
								'label'   => __( 'Background Type', 'xpro-bb-addons' ),
								'default' => 'normal',
								'options' => array(
									'normal' => __( 'Normal', 'xpro-bb-addons' ),
									'hover'  => __( 'Hover', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'normal' => array(
										'fields' => array( 'icon_color', 'icon_bg', 'icon_wrapper_bg' ),
									),
									'hover'  => array(
										'fields' => array( 'icon_hover_color', 'icon_hbg', 'icon_border_hover_color' ),
									),
								),
							),
							'icon_color'              => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-social-list .xpro-team-social-icon > i',
									'property' => 'color',
								),
							),
							'icon_bg'                 => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-social-list .xpro-team-social-icon',
									'property' => 'background-color',
								),
							),
							'icon_wrapper_bg'         => array(
								'type'       => 'color',
								'label'      => __( 'Wrapper Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-layout-15 .xpro-team-social-list',
									'property' => 'background-color',
								),
							),
							'icon_hover_color'        => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-social-list .xpro-team-social-icon:hover > i, .xpro-team-social-list .xpro-team-social-icon:focus > i',
									'property' => 'color',
								),
							),
							'icon_hbg'                => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-social-list .xpro-team-social-icon:hover, .xpro-team-social-list .xpro-team-social-icon:focus',
									'property' => 'background-color',
								),
							),
							'icon_border_hover_color' => array(
								'type'       => 'color',
								'label'      => __( 'Border Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-social-list .xpro-team-social-icon:hover, .xpro-team-social-list .xpro-team-social-icon:focus',
									'property' => 'border-color',
								),
							),
							'icon_border'             => array(
								'type'       => 'border',
								'label'      => 'Border',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-social-list .xpro-team-social-icon',
								),
							),
							'xpro-widget-seprator4'   => array(
								'type'    => 'raw',
								'content' => '<h2 class="xpro-widget-separator-heading">Wrapper<hr></h2>',
							),
							'icon_wrapper_background' => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-layout-8 .xpro-team-social-list, .xpro-team-layout-9 .xpro-team-social-list, .xpro-team-layout-15 .xpro-team-social-list',
									'property' => 'background-color',
								),
							),
							'icon_wrapper_border'     => array(
								'type'       => 'border',
								'label'      => 'Border',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-team-layout-8 .xpro-team-social-list, .xpro-team-layout-9 .xpro-team-social-list, .xpro-team-layout-15 .xpro-team-social-list',
								),
							),
							'icon_wrapper_padding'    => array(
								'type'         => 'dimension',
								'label'        => __( 'Padding', 'xpro-bb-addons' ),
								'units'        => array( 'px', 'em', '%' ),
								'responsive'   => true,
								'default_unit' => 'px',
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-team-layout-8 .xpro-team-social-list, .xpro-team-layout-15 .xpro-team-social-list, .xpro-team-layout-9 .xpro-team-social-list',
									'property' => 'padding',
								),
							),
							'icon_wrapper_margin'     => array(
								'type'         => 'dimension',
								'label'        => __( 'Margin', 'xpro-bb-addons' ),
								'units'        => array( 'px', 'em', '%' ),
								'responsive'   => true,
								'default_unit' => 'px',
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-team-layout-8 .xpro-team-social-list',
									'property' => 'margin',
								),
							),
						),
					),
				),
			),
		)
	);

	/**
	 * Register a settings form to use in the "form" field type above.
	 */
	FLBuilder::register_settings_form(
		'xpro_team_social_icon_form',
		array(
			'title' => __( 'Add Icon', 'xpro-bb-addons' ),
			'tabs'  => array(
				'general' => array(
					'title'    => __( 'General', 'xpro-bb-addons' ),
					'sections' => array(
						'general' => array(
							'title'  => 'Social Icon',
							'fields' => array(
								'social_icon'             => array(
									'type'        => 'icon',
									'label'       => __( 'Icon', 'xpro-bb-addons' ),
									'default'     => 'fab fa-facebook-f',
									'show_remove' => true,
								),
								'icon_link'               => array(
									'type'          => 'link',
									'label'         => __( 'Link', 'xpro-bb-addons' ),
									'show_target'   => true,
									'show_nofollow' => true,
									'placeholder'   => __( 'https://your-link.com', 'xpro-bb-addons' ),
								),
								'social_icon_inline_type' => array(
									'type'    => 'button-group',
									'label'   => __( 'Background Type', 'xpro-bb-addons' ),
									'default' => 'none',
									'options' => array(
										'none'   => __( 'None', 'xpro-bb-addons' ),
										'normal' => __( 'Normal', 'xpro-bb-addons' ),
										'hover'  => __( 'Hover', 'xpro-bb-addons' ),
									),
									'toggle'  => array(
										'normal' => array(
											'fields' => array( 'icon_inline_color', 'icon_inline_bg', 'icon_inline_border' ),
										),
										'hover'  => array(
											'fields' => array( 'icon_inline_hover_color', 'icon_inline_hover_bg', 'icon_inline_border_hcolor' ),
										),
									),
								),
								'icon_inline_color'       => array(
									'type'       => 'color',
									'label'      => __( 'Color', 'xpro-bb-addons' ),
									'show_reset' => true,
									'show_alpha' => true,
								),
								'icon_inline_bg'          => array(
									'type'       => 'color',
									'label'      => __( 'Background Color', 'xpro-bb-addons' ),
									'show_reset' => true,
									'show_alpha' => true,
								),
								'icon_inline_border'      => array(
									'type'       => 'color',
									'label'      => __( 'Border Color', 'xpro-bb-addons' ),
									'show_reset' => true,
									'show_alpha' => true,
								),

								'icon_inline_hover_color' => array(
									'type'       => 'color',
									'label'      => __( 'Color', 'xpro-bb-addons' ),
									'show_reset' => true,
									'show_alpha' => true,
								),
								'icon_inline_hover_bg'    => array(
									'type'       => 'color',
									'label'      => __( 'Background Color', 'xpro-bb-addons' ),
									'show_reset' => true,
									'show_alpha' => true,
								),
								'icon_inline_border_hcolor' => array(
									'type'       => 'color',
									'label'      => __( 'Border Color', 'xpro-bb-addons' ),
									'show_reset' => true,
									'show_alpha' => true,
								),
							),

						),
					),
				),
			),
		)
	);

}
