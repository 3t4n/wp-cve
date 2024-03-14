<?php

/**
 * @class NJBATestimonialsModule
 */
class NJBA_Highlight_Box_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Highlight Box', 'bb-njba' ),
			'description'     => __( 'Addon to display Highlight Box.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'content' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-highlight-box/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-highlight-box/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'partial_refresh' => false, // Set this to true to enable partial refresh.
			'enabled'         => true, // Defaults to true and can be omitted.
		) );
		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered
		$this->add_css( 'font-awesome' );
	}

	/**
	 * Use this method to work with settings data before
	 * it is saved. You must return the settings object.
	 *
	 * @method update
	 * @param $settings {object}
	 *
	 * @return object
	 */
	public function update( $settings ) {
		return $settings;
	}

	/**
	 * @param $content_type
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function njbaHighlightModule( $content_type ) {
		//ob_start();
		$html = '';
		$html .= '<div class="njba-highlight-box-main">';
		$html .= '<div class="njba-inner-box">';
		$html .= '<div class="njba-image-box">';
		if ( $content_type === 'photo' && ! empty( $this->settings->photo ) ) :
			$highlight_box_image = wp_get_attachment_image_src( $this->settings->photo );
			if ( ! is_wp_error( $highlight_box_image ) ) {
				$photo_src    = $highlight_box_image[0];
				$photo_width  = $highlight_box_image[1];
				$photo_height = $highlight_box_image[2];
			}
			$html .= '<img src="' . $this->settings->photo_src . '" width="' . $photo_width . '" height="' . $photo_height . '" class="njba-image-responsive" >';
		endif;
		if ( $content_type === 'text' && $this->settings->select === 'text' ) :
			$html .= $this->njbaRenderHeading();
		endif;
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="njba-hover-box">';
		$html .= '<div class="njba-center-box">';
		$html .= '<div class="njba-inner-contant">';
		$html .= '<a href="';
		if ( $this->settings->box_link ) :
			$html .= $this->settings->box_link;
		endif;
		$html .= '" target="';
		if ( $this->settings->link_target ) :
			$html .= $this->settings->link_target;
		endif;
		$html .= '">';

		if ( $this->settings->image_type === 'icon' ) :
			$html .= $this->njbaRenderIcon();

		endif;
		if ( $this->settings->image_type === 'photo' ) :
			$html .= $this->njbaRenderImageIcon();
		endif;
		if ( $this->settings->caption_select === 'Yes' ) :
			$html .= '<span>';
			//$html .= FLBuilder::render_module_html('njba-heading', $cap);
			$html .= $this->njbaRenderCaption();
			$html .= '</span>';
		endif;
		$html .= '</a>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * @return false|string
	 * @since 1.0.0
	 */
	protected function njbaRenderHeading() {
		$text = array(
			'main_title'     => $this->settings->main_title,
			'sub_title'      => $this->settings->sub_title,
			'main_title_tag' => $this->settings->main_title_tag
		);
		ob_start();
		FLBuilder::render_module_html( 'njba-heading', $text );

		return ob_get_clean();
	}

	/**
	 * @return false|string
	 * @since 1.0.0
	 */
	protected function njbaRenderIcon() {
		$icon = array(
			'image_type'                 => $this->settings->image_type,
			'overall_alignment_img_icon' => $this->settings->overall_alignment_img_icon,
			'icon'                       => $this->settings->icon
		);
		ob_start();
		FLBuilder::render_module_html( 'njba-icon-img', $icon );

		return ob_get_clean();
	}

	/**
	 * @return false|string
	 * @since 1.0.0
	 */
	protected function njbaRenderImageIcon() {
		$photo = $this->settings->icon_photo;
		if ( $photo !== '' ) {
			$image_icon = array(
				'image_type'                 => $this->settings->image_type,
				'overall_alignment_img_icon' => $this->settings->overall_alignment_img_icon,
				'photo'                      => $this->settings->icon_photo,
				'photo_src'                  => $this->settings->icon_photo_src
			);
			ob_start();
			FLBuilder::render_module_html( 'njba-icon-img', $image_icon );

			return ob_get_clean();
		}
	}

	/**
	 * @return false|string
	 * @since 1.0.0
	 */
	protected function njbaRenderCaption() {
		$cap = array(
			'main_title'     => $this->settings->caption_title,
			'main_title_tag' => $this->settings->caption_title_tag,
			'sub_title'      => ''
		);
		ob_start();
		FLBuilder::render_module_html( 'njba-heading', $cap );

		return ob_get_clean();
	}
}

FLBuilder::register_module( 'NJBA_Highlight_Box_Module', array(
	'highlight_box' => array( // Tab
		'title'    => __( 'General', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'select'         => array(
				'title'  => __( 'Front', 'bb-njba' ),
				'fields' => array( // Section Fields
					'select'                       => array(
						'type'    => 'select',
						'label'   => __( 'Type', 'bb-njba' ),
						'default' => 'photo',
						'options' => array(
							'photo' => __( 'Photo', 'bb-njba' ),
							'text'  => __( 'Text', 'bb-njba' )
						),
						'toggle'  => array(
							'photo' => array(
								'fields' => array( 'photo' )
							),
							'text'  => array(
								'tabs'   => array( 'text' ),
								'fields' => array( 'main_title', 'sub_title' ),
							)
						)
					),
					'photo'                        => array(
						'type'        => 'photo',
						'label'       => __( 'Photo', 'bb-njba' ),
						'show_remove' => true,
					),
					'main_title'                   => array(
						'type'    => 'text',
						'label'   => __( 'Heading', 'bb-njba' ),
						'default' => 'NJBA HEADING',
						'preview' => array(
							'type'     => 'text',
							'selector' => '.njba-heading-title'
						)
					),
					'sub_title'                    => array(
						'type'          => 'editor',
						'label'         => __( 'Subtitle', 'bb-njba' ),
						'media_buttons' => false,
						'rows'          => 6,
						'default'       => __( 'Enter description text here.', 'bb-njba' ),
						'preview'       => array(
							'type'     => 'text',
							'selector' => '.njba-heading-sub-title'
						)
					),
				),
			),
			'hover_section' => array(
				'title'  => __( 'Hover', 'bb-njba' ),
				'fields' => array(
					'hover_effect'                 => array(
						'type'    => 'select',
						'label'   => __( 'Hover Effect', 'bb-njba' ),
						'options' => array(
							'1' => __( 'Hover', 'bb-njba' ),
							'2' => __( 'Hover Left To Right', 'bb-njba' ),
							'3' => __( 'Hover Top To Bottom', 'bb-njba' ),
							'4' => __( 'Hover Right To Left', 'bb-njba' ),
							'5' => __( 'Hover Bottom To Top', 'bb-njba' ),
							'6' => __( 'None', 'bb-njba' )
						),
						'toggle'  => array(
							'1' => array(
								'tabs'     => array( 'icon_image' ),
								'sections' => array( 'box_caption_field' ),
								'fields'   => array( 'caption_select', 'box_bg_color', 'box_bg_opacity', 'box_bg_hover_color', 'box_bg_hover_opacity' )
							),
							'2' => array(
								'tabs'     => array( 'icon_image' ),
								'sections' => array( 'box_caption_field' ),
								'fields'   => array( 'caption_select', 'box_bg_color', 'box_bg_opacity', 'box_bg_hover_color', 'box_bg_hover_opacity' )
							),
							'3' => array(
								'tabs'     => array( 'icon_image' ),
								'sections' => array( 'box_caption_field' ),
								'fields'   => array( 'caption_select', 'box_bg_color', 'box_bg_opacity', 'box_bg_hover_color', 'box_bg_hover_opacity' )
							),
							'4' => array(
								'tabs'     => array( 'icon_image' ),
								'sections' => array( 'box_caption_field' ),
								'fields'   => array( 'caption_select', 'box_bg_color', 'box_bg_opacity', 'box_bg_hover_color', 'box_bg_hover_opacity' )
							),
							'5' => array(
								'tabs'     => array( 'icon_image' ),
								'sections' => array( 'box_caption_field' ),
								'fields'   => array( 'caption_select', 'box_bg_color', 'box_bg_opacity', 'box_bg_hover_color', 'box_bg_hover_opacity' )
							),
							'6' => array(
								'fields' => array( 'box_bg_color', 'box_bg_opacity', 'box_hover_bg_color', 'box_hover_bg_opacity' )
							)
						),
					),
					'caption_select'               => array(
						'type'    => 'select',
						'label'   => __( 'Caption Show', 'bb-njba' ),
						'default' => 'No',
						'options' => array(
							'No'  => __( 'No', 'bb-njba' ),
							'Yes' => __( 'Yes', 'bb-njba' )
						),
						'toggle'  => array(
							'Yes' => array(
								'sections' => array( 'box_caption_field' ),
								'fields'   => array( 'caption_title' )
							),
						)
					),
					'caption_title'                => array(
						'type'    => 'text',
						'label'   => __( 'Caption Title', 'bb-njba' ),
						'default' => 'NJBA HEADING',
					),
					'box_icon_transition_duration' => array(
						'type'        => 'text',
						'label'       => __( 'Transition Duration', 'bb-njba' ),
						'description' => 'sec',
						'class'       => 'bb-box-input input-small',
						'default'     => '0.5',
						'maxlength'   => '4',
						'size'        => '5',
						'show_reset'  => true,
					),
				)
			),
			'box_link_field' => array(
				'title'  => __( 'Link', 'bb-njba' ),
				'fields' => array(
					'box_link'    => array(
						'type'    => 'link',
						'label'   => __( 'URL', 'bb-njba' ),
						'preview' => array(
							'type' => 'none'
						)
					),
					'link_target' => array(
						'type'    => 'select',
						'label'   => __( 'Target', 'bb-njba' ),
						'default' => '_self',
						'options' => array(
							'_self'  => __( 'Same Window', 'bb-njba' ),
							'_blank' => __( 'New Window', 'bb-njba' )
						),
						'preview' => array(
							'type' => 'none'
						)
					)
				)
			),
		)
	),
	'icon_image'    => array(
		'title'    => __( 'Hover Icon / Image', 'bb-njba' ),
		'sections' => array(
			'type_general'   => array( // Section
				'title'  => __( 'Icon / Image', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'image_type'                 => array(
						'type'    => 'select',
						'label'   => __( 'Type', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'  => __( 'None', 'Image type.', 'bb-njba' ),
							'icon'  => __( 'Icon', 'bb-njba' ),
							'photo' => __( 'Photo', 'bb-njba' ),
						),
						'class'   => 'class_image_type',
						'toggle'  => array(
							'icon'  => array(
								'sections' => array( 'icon_basic', 'icon_style', 'icon_colors', 'common_style' ),
								'fields'   => array( 'overall_alignment_img_icon' )
							),
							'photo' => array(
								'sections' => array( 'img_basic', 'img_style', 'common_style' ),
								'fields'   => array( 'overall_alignment_img_icon' )
							)
						),
					),
					'overall_alignment_img_icon' => array(
						'type'    => 'select',
						'label'   => __( 'Overall Alignment', 'bb-njba' ),
						'default' => 'left',
						'help'    => __( 'Icon / Image & Text position', 'bb-njba' ),
						'options' => array(
							'center' => __( 'Center', 'bb-njba' ),
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' )
						)
					)
				),
			),
			'icon_basic'     => array( // Section
				'title'  => __( 'Icon Basics', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'icon'             => array(
						'type'        => 'icon',
						'label'       => __( 'Icon', 'bb-njba' ),
						'show_remove' => true
					),
					'icon_size'        => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '18',
							'medium'  => '16',
							'small'   => ''
						),
						'preview'     => array(
							'type' => 'refresh',
						)
					),
					'icon_line_height' => array(
						'type'        => 'text',
						'label'       => __( 'Width / Height', 'bb-njba' ),
						'placeholder' => '35',
						'help'        => 'Width / Height values are equal',
						'maxlength'   => '5',
						'size'        => '6',
						'description' => 'px',
						'preview'     => array(
							'type' => 'refresh',
						),
					),
				)
			),
			/* Image Basic Setting */
			'img_basic'      => array( // Section
				'title'  => __( 'Image Basics', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'icon_photo' => array(
						'type'        => 'photo',
						'label'       => __( 'Photo', 'bb-njba' ),
						'show_remove' => true,
					),
					'img_size'   => array(
						'type'        => 'text',
						'label'       => __( 'Size', 'bb-njba' ),
						'placeholder' => 'auto',
						'help'        => __( 'This size is adjust your photo and it\'s Background.', 'bb-njba' ),
						'maxlength'   => '5',
						'size'        => '6',
						'description' => 'px',
					)
				)
			),
			'img_icon_style' => array(
				'title'  => 'Border',
				'fields' => array(
					'img_icon_show_border'        => array(
						'type'    => 'select',
						'label'   => __( 'Show Border', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' )
						),
						'toggle'  => array(
							'yes' => array(
								'fields' => array(
									'img_icon_border_width',
									'icon_img_border_radius_njba',
									'img_icon_border_style',
									'img_icon_border_color',
									'img_icon_border_hover_color'
								)
							)
						)
					),
					'img_icon_border_width'       => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'default'     => '1',
						'description' => 'px',
						'maxlength'   => '3',
						'size'        => '5',
					),
					'icon_img_border_radius_njba' => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'description' => 'px',
						'help'        => 'Enter Padding for.',
						'default'     => array(
							'topleft'     => 0,
							'topright'    => 0,
							'bottomleft'  => 0,
							'bottomright' => 0
						),
						'options'     => array(
							'topleft'     => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'topright'    => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right'
							),
							'bottomleft'  => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),
							'bottomright' => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left'
							)
						)
					),
					'img_icon_border_style'       => array(
						'type'    => 'select',
						'label'   => __( 'Border Style', 'bb-njba' ),
						'default' => 'solid',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						)
					),
					'img_icon_border_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),
					'img_icon_border_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Border Hover Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),
				)
			),
			'icon_colors'    => array( // Section
				'title'  => __( 'Colors', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					/* Icon Color */
					'icon_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Icon Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),
					'icon_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Icon Hover Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type' => 'none',
						)
					),
					'icon_transition'  => array(
						'type'        => 'text',
						'label'       => __( 'Transition', 'bb-njba' ),
						'default'     => '0.3',
						'description' => 'sec',
						'maxlength'   => '3',
						'size'        => '5',
					)
				)
			),
			'common_style'   => array( // Section
				'title'  => __( 'Icon / Image', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'img_icon_padding'            => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 0,
							'right'  => 0,
							'bottom' => 0,
							'left'   => 0
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left'
							)
						)
					),
					'img_icon_margin'             => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 0,
							'right'  => 0,
							'bottom' => 0,
							'left'   => 0
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left'
							)
						)
					),
					/* Background Color Dependent on Icon Style **/
					'img_icon_bg_color'           => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),
					'img_icon_bg_color_opc'       => array(
						'type'        => 'text',
						'label'       => __( 'Background Opacity', 'bb-njba' ),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
					'img_icon_bg_hover_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Background Hover Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type' => 'none',
						)
					),
					'img_icon_bg_hover_color_opc' => array(
						'type'        => 'text',
						'label'       => __( 'Background Hover Opacity', 'bb-njba' ),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
				)
			)
		)
	),
	'style'         => array( // Tab
		'title'    => __( 'Style', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'general_colors' => array( // Section
				'title'  => __( 'Box', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'box_bg_color'         => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-highlight-box-main',
							'property' => 'background-color'
						)
					),
					'box_bg_opacity'       => array(
						'type'        => 'text',
						'label'       => __( 'Background Color Opacity', 'bb-njba' ),
						'default'     => '100',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
						'placeholder' => '100'
					),
					'box_bg_hover_color'   => array(
						'type'       => 'color',
						'label'      => __( 'Hover Background Color', 'bb-njba' ),
						'default'    => '#ffffff;',
						'show_reset' => true,
					),
					'box_bg_hover_opacity' => array(
						'type'        => 'text',
						'label'       => __( 'Hover Background Color Opacity', 'bb-njba' ),
						'default'     => '100',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
						'placeholder' => '100'
					),
					'box_hover_bg_color'   => array(
						'type'       => 'color',
						'label'      => __( 'None Hover Background Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.pp-highlight-box-content',
							'property' => 'background-color'
						)
					),
					'box_hover_bg_opacity' => array(
						'type'        => 'text',
						'label'       => __( 'None Hover Background Color Opacity', 'bb-njba' ),
						'default'     => '100',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
						'placeholder' => '100'
					),
					'padding'              => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Box Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 30,
							'right'  => 15,
							'bottom' => 30,
							'left'   => 15,
						),
						'options'     => array(
							'top'    => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Top', 'bb-njba' ),
								'tooltip'     => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'selector' => '',
									'property' => 'padding-top',
									'unit'     => 'px'
								)
							),
							'bottom' => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'tooltip'     => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'selector' => '',
									'property' => 'padding-bottom',
									'unit'     => 'px'
								)
							),
							'left'   => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Left', 'bb-njba' ),
								'tooltip'     => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'selector' => '',
									'property' => 'padding-left',
									'unit'     => 'px'
								)
							),
							'right'  => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Right', 'bb-njba' ),
								'tooltip'     => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'selector' => '',
									'property' => 'padding-right',
									'unit'     => 'px'
								)
							)
						)
					)
				)
			),
		)
	),
	'text'          => array(
		'title'    => __( 'Typogrphy', 'bb-njba' ),
		'sections' => array(
			'text'              => array( // Section
				'title'  => __( 'Title', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'main_title_tag'            => array(
						'type'    => 'select',
						'label'   => __( 'Title Tag', 'bb-njba' ),
						'default' => 'h1',
						'options' => array(
							'h1' => __( 'H1', 'bb-njba' ),
							'h2' => __( 'H2', 'bb-njba' ),
							'h3' => __( 'H3', 'bb-njba' ),
							'h4' => __( 'H4', 'bb-njba' ),
							'h5' => __( 'H5', 'bb-njba' ),
							'h6' => __( 'H6', 'bb-njba' )
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-heading-main'
						)
					),
					'heading_title_alignment'   => array(
						'type'    => 'select',
						'default' => 'center',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'text-align'
						)
					),
					'heading_title_font'        => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-heading-title'
						)
					),
					'heading_title_font_size'   => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '28',
							'medium'  => '24',
							'small'   => '20',
						),
						'description' => _x( 'Pleas enter value in pixels.', 'Value unit for font size. Such as: "14 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'heading_title_line_height' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'default'     => array(
							'desktop' => '30',
							'medium'  => '26',
							'small'   => '22',
						),
						'description' => _x( 'Pleas enter value in pixels.', 'Value unit for line height. Such as: "14 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'heading_title_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'color',
						)
					),
					'heading_margin'            => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'right'  => 10,
							'bottom' => 10,
							'left'   => 10
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-top',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-right',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-left',
								),
							)
						)
					)
				)
			),
			'sub_heading'       => array( // Section
				'title'  => __( 'Sub Title', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'heading_sub_title_alignment'   => array(
						'type'    => 'select',
						'default' => 'center',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'text-align'
						)
					),
					'heading_sub_title_font'        => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-heading-sub-title'
						)
					),
					'heading_sub_title_font_size'   => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '20',
							'medium'  => '20',
							'small'   => '20',
						),
						'description' => _x( 'Pleas enter value in pixels.', 'Value unit for font size. Such as: "14 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'heading_sub_title_line_height' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'default'     => array(
							'desktop' => '20',
							'medium'  => '20',
							'small'   => '20',
						),
						'description' => _x( 'Pleas enter value in pixels.', 'Value unit for line height. Such as: "14 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'heading_sub_title_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'color',
						)
					),
					'heading_subtitle_margin'       => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'right'  => 10,
							'bottom' => 10,
							'left'   => 10
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-top',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-right',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-left',
								),
							)
						)
					)
				)
			),
			'box_caption_field' => array(
				'title'  => __( 'Caption', 'bb-njba' ),
				'fields' => array(
					'caption_title_tag'         => array(
						'type'    => 'select',
						'label'   => __( 'Caption Tag', 'bb-njba' ),
						'default' => 'h1',
						'options' => array(
							'h1' => __( 'H1', 'bb-njba' ),
							'h2' => __( 'H2', 'bb-njba' ),
							'h3' => __( 'H3', 'bb-njba' ),
							'h4' => __( 'H4', 'bb-njba' ),
							'h5' => __( 'H5', 'bb-njba' ),
							'h6' => __( 'H6', 'bb-njba' )
						)
					),
					'caption_title_alignment'   => array(
						'type'    => 'select',
						'default' => 'center',
						'label'   => __( 'Caption Alignment', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'text-align'
						)
					),
					'caption_title_font'        => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Caption Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-heading-title'
						)
					),
					'caption_title_font_size'   => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Caption Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '28',
							'medium'  => '24',
							'small'   => '20',
						),
						'description' => _x( 'Pleas enter value in pixels.', 'Value unit for font size. Such as: "14 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'caption_title_line_height' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Caption Line Height', 'bb-njba' ),
						'default'     => array(
							'desktop' => '30',
							'medium'  => '26',
							'small'   => '22',
						),
						'description' => _x( 'Pleas enter value in pixels.', 'Value unit for line height. Such as: "14 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'caption_title_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Caption Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'color',
						)
					),
					'caption_margin'            => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Caption Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'right'  => 10,
							'bottom' => 10,
							'left'   => 10
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-top',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-right',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-left',
								),
							)
						)
					)
				)
			)
		)
	),
) );
