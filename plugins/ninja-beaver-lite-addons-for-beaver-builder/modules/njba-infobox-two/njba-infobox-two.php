<?php

/**
 * @class NJBA_Infobox_Two_Module
 */
class NJBA_Infobox_Two_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Infobox 2', 'bb-njba' ),
			'description'     => __( 'Addon to display Infobox with Heading and Subtitle.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'content' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-infobox-two/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-infobox-two/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Defaults to false and can be omitted.
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
	 * This method will be called by the builder
	 * right before the module is deleted.
	 *
	 * @method delete
	 */
	public function delete() {
	}

	public function njbaIconModule( $sep_type ) {
		if ( $sep_type === 'icon' ) :
			$html = '<div class="njba-infobox-two"><i class="' . $this->settings->infobox_two_icon . '" aria-hidden="true"></i></div>';
		endif;
		if ( $sep_type === 'text' ) :
			$html = '<div class="njba-infobox-two">' . $this->settings->infobox_two_text_select . '</div>';
		endif;
		if ( $sep_type === 'image' ) :
			$src            = $this->njbaGetImageSrc();
			$info_box_image = wp_get_attachment_image_src( $this->settings->infobox_two_image );
			if ( ! is_wp_error( $info_box_image ) ) {
				$infobox_two_image_src    = $info_box_image[0];
				$infobox_two_image_width  = $info_box_image[1];
				$infobox_two_image_height = $info_box_image[2];
			}
			$html = '<div class="njba-infobox-two"><img src="' . $src . '" width="' . $infobox_two_image_width . '" height="' . $infobox_two_image_height . '" ></div>';
		endif;

		return $html;
	}

	public function njbaGetImageSrc() {
		$src = $this->njbaGetImageUrl();

		return $src;
	}

	/**
	 * @method njbaGetImageUrl
	 * @protected
	 */
	protected function njbaGetImageUrl() {
		if ( ! empty( $this->settings->infobox_two_image_src ) ) {
			$url = $this->settings->infobox_two_image_src;
		} else {
			$url = FL_BUILDER_URL . 'img/pixel.png';
		}

		return $url;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBA_Infobox_Two_Module', array(
	'general' => array( //Tab
		'title'    => __( 'General', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'heading'       => array( // Section
				'title'  => __( 'Title', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'main_title'     => array(
						'type'    => 'text',
						'label'   => __( 'Title', 'bb-njba' ),
						'default' => 'NJBA HEADING',
						'preview' => array(
							'type'     => 'text',
							'selector' => '.njba-heading-title'
						)
					),
					'main_title_tag' => array(
						'type'    => 'select',
						'label'   => __( 'Tag', 'bb-njba' ),
						'default' => 'h1',
						'options' => array(
							'h1' => __( 'H1', 'bb-njba' ),
							'h2' => __( 'H2', 'bb-njba' ),
							'h3' => __( 'H3', 'bb-njba' ),
							'h4' => __( 'H4', 'bb-njba' ),
							'h5' => __( 'H5', 'bb-njba' ),
							'h6' => __( 'H6', 'bb-njba' )
						),
					)
				)
			),
			'sub_title_sec' => array( // Section
				'title'  => __( 'Subtitle', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'sub_title' => array(
						'type'          => 'editor',
						//'label'         => __( 'Subtitle', 'bb-njba' ),
						'media_buttons' => false,
						'rows'          => 6,
						'default'       => __( 'Enter description text here.', 'bb-njba' ),
						'preview'       => array(
							'type'     => 'text',
							'selector' => '.njba-heading-sub-title'
						)
					)
				)
			),
			'infobox_two'   => array( // Section
				'title'  => __( 'Prefix', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'infobox_two_type'        => array(
						'type'    => 'select',
						'label'   => __( 'Type', 'bb-njba' ),
						'default' => 'text',
						'options' => array(
							'text'  => __( 'Text', 'bb-njba' ),
							'icon'  => __( 'Icon', 'bb-njba' ),
							'image' => __( 'Image', 'bb-njba' )
						),
						'toggle'  => array(
							'text'  => array(
								'sections' => array( 'infobox_two_style' ),
								'fields'   => array( 'infobox_two_font_size', 'infobox_two_font_color', 'infobox_two_text_select', 'infobox_two_font' )
							),
							'icon'  => array(
								'sections' => array( 'infobox_two_style' ),
								'fields'   => array( 'infobox_two_font_size', 'infobox_two_font_color', 'infobox_two_icon' )
							),
							'image' => array(
								'fields' => array( 'infobox_two_image' )
							)
						)
					),
					'infobox_two_text_select' => array(
						'type'      => 'text',
						'label'     => __( 'Text', 'bb-njba' ),
						'maxlength' => '2',
						'size'      => '5',
						'help'      => 'Enter one or two words text.',
						'default'   => '01',
						'preview'   => array(
							'type'     => 'text',
							'selector' => '.njba-infobox-two'
						)
					),
					'infobox_two_icon'        => array(
						'type'  => 'icon',
						'label' => __( 'Icon', 'bb-njba' )
					),
					'infobox_two_image'       => array(
						'type'        => 'photo',
						'label'       => __( 'Image', 'bb-njba' ),
						'show_remove' => true
					),
					'infobox_two_position'    => array(
						'type'    => 'select',
						'label'   => __( 'Allover Position', 'bb-njba' ),
						'default' => 'left',
						'options' => array(
							'left'  => __( 'Left', 'bb-njba' ),
							'right' => __( 'Right', 'bb-njba' )
						)
					),
				)
			)
		)
	),
	'styles'  => array( //Tab
		'title'    => __( 'Styles', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'infobox_two_style' => array(
				'title'  => __( 'Prefix' ),
				'fields' => array(
					'infobox_two_font'       => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-infobox-two',
						)
					),
					'infobox_two_font_size'  => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '50',
							'medium'  => '40',
							'small'   => '30',
						),
						'description' => 'Please Enter Value in pixels. ',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-infobox-two',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'infobox_two_line_height'  => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => '',
						),
						'description' => 'Please Enter Value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-infobox-two',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'infobox_two_font_color' => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-infobox-two',
							'property' => 'color'
						)
					),
					'infobox_two_marginlr'   => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'left'  => 15,
							'right' => 15,
						),
						'options'     => array(
							'left'  => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'selector' => '.njba-infobox-two',
									'property' => 'margin-left',
								),
							),
							'right' => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'selector' => '.njba-infobox-two',
									'property' => 'margin-right',
								),
							),
						)
					),
				)
			),
			'heading_style'     => array( // Section
				'title'  => __( 'Title', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'heading_title_font'         => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.section-title-details h1,.section-title-details h2,.section-title-details h3,.section-title-details h4,.section-title-details h5,.section-title-details h6',
						)
					),
					'heading_title_font_size'    => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '28',
							'medium'  => '24',
							'small'   => '20',
						),
						'description' => 'Please Enter Value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.section-title-details h1,.section-title-details h2,.section-title-details h3,.section-title-details h4,.section-title-details h5,.section-title-details h6',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'heading_title_line_height'  => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => '',
						),
						'description' => 'Please Enter Value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.section-title-details h1,.section-title-details h2,.section-title-details h3,.section-title-details h4,.section-title-details h5,.section-title-details h6',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'heading_title_color'        => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.section-title-details h1,.section-title-details h2,.section-title-details h3,.section-title-details h4,.section-title-details h5,.section-title-details h6',
							'property' => 'color',
						)
					),
					'infobox_two_heading_margin' => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'left'   => 10,
							'right'  => 10,
							'top'    => 10,
							'bottom' => 10,
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.section-title-details',
									'property' => 'margin-top',
									'unit'     => 'px'
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.section-title-details',
									'property' => 'margin-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.section-title-details',
									'property' => 'margin-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.section-title-details',
									'property' => 'margin-left',
									'unit'     => 'px'
								),
							)
						)
					)
				)
			),
			'sub_title_style'   => array( // Section
				'title'  => __( 'Subtitle', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'heading_sub_title_font'              => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.section-title-details p'
						)
					),
					'heading_sub_title_font_size'         => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '20',
							'medium'  => '20',
							'small'   => '20',
						),
						'description' => 'Please Enter Value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.section-title-details p',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'heading_sub_title_line_height'  => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => '',
						),
						'description' => 'Please Enter Value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.section-title-details p',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'heading_sub_title_color'             => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.section-title-details p',
							'property' => 'color',
						)
					),
					'infobox_two_heading_subtitle_margin' => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'left'   => 10,
							'right'  => 10,
							'top'    => 10,
							'bottom' => 10,
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.section-title-details p',
									'property' => 'margin-top',
									'unit'     => 'px',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.section-title-details p',
									'property' => 'margin-right',
									'unit'     => 'px',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.section-title-details p',
									'property' => 'margin-bottom',
									'unit'     => 'px',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.section-title-details p',
									'property' => 'margin-left',
									'unit'     => 'px',
								),
							)
						)
					)
				)
			)
		)
	)
) );
