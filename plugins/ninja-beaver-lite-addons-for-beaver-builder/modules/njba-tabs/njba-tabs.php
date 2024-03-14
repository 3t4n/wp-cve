<?php

/**
 * @class NJBA_Tabs_Module
 */
class NJBA_Tabs_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Tabs', 'bb-njba' ),
			'description'     => __( 'Addon to display tabs.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'content' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-tabs/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-tabs/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Set this to true to enable partial refresh.
			'icon'            => 'layout.svg',
		) );
		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered

		$this->add_css( 'font-awesome' );
		$this->add_css( 'njba-tabs-frontend', NJBA_MODULE_URL . 'modules/njba-tabs/css/frontend.css' );

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

	/**
	 *Get tab content
	 * @since 1.0.0
	 */
	public function njbaTabContent() {
		$activeTabIndex = '';
		$i              = '';
		for ( $i = 0, $iMax = count( $this->settings->items ); $i < $iMax; $i ++ ) : if ( ! is_object( $this->settings->items[ $i ] ) ) {
			continue;
		} ?>
            <div class="njba-tabs-panel"<?php if ( ! empty( $this->settings->id ) ) {
				echo ' id="' . sanitize_html_class( $this->settings->id ) . '-' . $i . '"';
			} ?>>
                <div class="njba-tabs-panel-content njba-content njba-tab-acc-content njba-clearfix<?php if ( $i == $activeTabIndex ) {
					echo ' njba-tab-active';
				} ?>" data-index="<?php echo $i; ?>">
					<?php echo $this->njbaGetItemContent( $this->settings->items[ $i ] ); ?>
                </div>
            </div>
		<?php endfor; ?><?php
	}

	/**
	 * Get Specific item content
	 *
	 * @param $settings
	 *
	 * @return string|void
	 * @since 1.0.0
	 */
	public function njbaGetItemContent( $settings ) {
		$content_type = $settings->content_type;
		switch ( $content_type ) {
			case 'content':
				return $settings->content;
				break;
			case 'photo':
				if ( isset( $settings->content_photo_src ) ) {
					$accordian_content_image = wp_get_attachment_image_src( $settings->content_photo );
					if ( ! is_wp_error( $accordian_content_image ) ) {
						$content_photo_src    = $accordian_content_image[0];
						$content_photo_width  = $accordian_content_image[1];
						$content_photo_height = $accordian_content_image[2];
					}

					return '<img src="' . $settings->content_photo_src . '" width="' . $content_photo_width . '" height="' . $content_photo_height . '" style="max-width: 100%;"/>';
				}
				break;
			case 'video':
				global $wp_embed;

				return $wp_embed->autoembed( $settings->content_video );
				break;
			case 'module':
				return '[fl_builder_insert_layout id="' . $settings->content_module . '"]';
				break;
			case 'row':
				return '[fl_builder_insert_layout id="' . $settings->content_row . '"]';
				break;
			case 'layout':
				return '[fl_builder_insert_layout id="' . $settings->content_layout . '"]';
				break;
			default:
				return;
				break;
		}
	}

	/**
	 *njba get tab title
	 * @since 1.0.0
	 */
	public function njbaGetTabTitle() {
		$activeTabIndex = '';
		$number_of_tabs = count( $this->settings->items );
		for ( $i = 0; $i < $number_of_tabs; $i ++ ) :
			if ( ! is_object( $this->settings->items[ $i ] ) ) {
				continue;
			}
			//$css_id = ( $settings->tab_id_prefix != '' ) ? $settings->tab_id_prefix . '-' . ($i+1) : 'njba-tab-' . $id . '-' . ($i+1);
			$class      = ( $this->settings->show_icon === 'yes' ) ? '<span class="njba-tabs-icon "><i class= " ' . $this->settings->items[ $i ]->tab_font_icon . '"></i></span>' : '';
			$hover      = 'hover_title_class';
			$hover_icon = 'hover_icon_class';
			?>
            <div id="" class="<?php ?> njba-tabs-label<?php if ( $i == $activeTabIndex ) {
				echo ' njba-tab-active';
			} ?> <?php if ( $this->settings->title_hover === 'yes' ) { ?> <?php echo $hover; ?><?php } ?> <?php if ( $this->settings->title_hover === 'no' ) { ?> <?php echo $hover_icon; ?><?php } ?>"
                 data-index="<?php echo $i; ?>">
                <div class="njba-tab-label-inner icon-align-<?php echo $this->settings->tab_icon_position; ?>">
					<?php if ( $this->settings->tab_icon_position === 'left' || $this->settings->tab_icon_position === 'top' ) { ?>
						<?php echo $class; ?>
                        <span class="njba-tab-title"> <?php echo $this->settings->items[ $i ]->label; ?></span>
					<?php } ?>
					<?php if ( $this->settings->tab_icon_position === 'right' || $this->settings->tab_icon_position === 'bottom' ) { ?>
                        <span class="njba-tab-title"> <?php echo $this->settings->items[ $i ]->label; ?></span>
						<?php echo $class; ?>
					<?php } ?>
                    <span class="njba-icon-separator"></span>
                </div>
            </div>
		<?php endfor; ?><?php
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBA_Tabs_Module', array(
	'items' => array(
		'title'    => __( 'Tabs', 'bb-njba' ),
		'sections' => array(
			'general' => array(
				'title'  => '',
				'fields' => array(
					'items' => array(
						'type'         => 'form',
						'label'        => __( 'Tab', 'bb-njba' ),
						'form'         => 'item_form', // ID from registered form below
						'preview_text' => 'label', // Name of a field to use for the preview text
						'multiple'     => true
					),
				)
			)
		)
	),

	'style'      => array(
		'title'    => __( 'Style', 'bb-njba' ),
		'sections' => array(
			'general'        => array(
				'title'  => __( 'General', 'bb-njba' ),
				'fields' => array(
					'tab_style_layout'     => array(
						'type'    => 'select',
						'label'   => __( 'layout', 'bb-njba' ),
						'default' => 'style-7',
						'options' => array(
							'style-1' => __( 'Style 1', 'bb-njba' ),
							'style-2' => __( 'Style 2', 'bb-njba' ),
							'style-3' => __( 'Style 3', 'bb-njba' ),
							'style-4' => __( 'Style 4', 'bb-njba' ),
							'style-5' => __( 'Style 5', 'bb-njba' ),
							'style-6' => __( 'Style 6', 'bb-njba' ),
							'style-7' => __( 'Style 7', 'bb-njba' ),
							'style-8' => __( 'Style 8', 'bb-njba' ),
						),
						'toggle'  => array(
							'style-1' => array(
								'fields' => array(
									'tab_background_color',
									'box_border_width',
									'box_border_style',
									'box_border_color',
									'box_border_radius',
									'title_hover'
								)
							),
							'style-2' => array(
								'fields' => array(
									'separator_size',
									'separator_color',
									'box_border_width',
									'box_border_style',
									'box_border_color',
									'box_border_radius',
									'title_hover'
								)
							),
							'style-3' => array(
								'fields' => array(
									'tab_alignment',
									'title_active_border',
									'title_active_border_style',
									'title_active_border_color',
									'title_active_border_radius',
									'tab_background_color',
									'box_border_width',
									'box_border_style',
									'box_border_color',
									'box_border_radius',
									'title_hover'
								)
							),
							'style-4' => array(
								'fields' => array(
									'separator_size',
									'separator_color',
									'box_border_width',
									'box_border_style',
									'box_border_color',
									'box_border_radius',
									'title_hover'
								)
							),
							'style-5' => array(
								'fields' => array(
									'separator_size',
									'separator_color',
									'tab_alignment',
									'tab_background_color',
									'box_border_width',
									'box_border_style',
									'box_border_color',
									'box_border_radius',
									'title_hover'
								)
							),
							'style-6' => array(
								'fields' => array(
									'tab_alignment',
									'title_active_border',
									'title_active_border_style',
									'title_active_border_color',
									'title_active_border_radius',
									'tab_background_color',
									'box_border_width',
									'box_border_style',
									'box_border_color',
									'box_border_radius',
									'title_hover'
								)
							),
							'style-7' => array(
								'fields' => array(
									'tab_border',
									'tab_border_style',
									'tab_border_color',
									'tab_border_radius',
									'tab_background_color',
									'header_border_width',
									'header_border_style',
									'header_border_color',
									'header_border_radius',
									'box_border_width',
									'box_border_style',
									'box_border_color',
									'box_border_radius'
								)
							),
							'style-8' => array(
								'fields' => array(
									'tab_border',
									'tab_border_style',
									'tab_border_color',
									'tab_border_radius',
									'tab_background_color',
									'body_border_width',
									'body_border_style',
									'body_border_color',
									'body_border_radius',
									'box_border_width',
									'box_border_style',
									'box_border_color',
									'box_border_radius'
								)
							),
						)
					),
					'title_hover'          => array(
						'type'    => 'select',
						'label'   => __( 'Hover Title On Header', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none' => __( 'None', 'bb-njba' ),
							'no'   => __( 'Show Icon', 'bb-njba' ),
							'yes'  => __( 'Show Title', 'bb-njba' ),
						),
					),
					'separator_size'       => array(
						'type'        => 'text',
						'label'       => __( 'Separator Size', 'bb-njba' ),
						'placeholder' => __( 'Inherit', 'bb-njba' ),
						'size'        => '8',
						'description' => 'px',
						'help'        => __( '', 'bb-njba' ),
					),
					'separator_color'      => array(
						'type'       => 'color',
						'label'      => __( 'Separator Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),
					'body_border_style'    => array(
						'type'    => 'select',
						'label'   => __( 'Tabs Border Style', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
						'toggle'  => array(
							'solid'  => array(
								'fields' => array( 'body_border_width', 'body_border_color', 'body_border_radius' )
							),
							'dashed' => array(
								'fields' => array( 'body_border_width', 'body_border_color', 'body_border_radius' )
							),
							'dotted' => array(
								'fields' => array( 'body_border_width', 'body_border_color', 'body_border_radius' )
							),
							'double' => array(
								'fields' => array( 'body_border_width', 'body_border_color', 'body_border_radius' )
							)
						),
					),
					'body_border_width'    => array(
						'type'        => 'text',
						'default'     => '',
						'maxlength'   => '2',
						'size'        => '5',
						'label'       => __( 'Tabs Border Width', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border width. Such as: "5 px"', 'bb-njba' ),
						'preview'     => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.njba-tabs',
									'property' => 'border-width',
									'unit'     => 'px'
								),
							),
						)
					),
					'body_border_color'    => array(
						'type'       => 'color',
						'label'      => __( 'Tabs Border Color', 'bb-njba' ),
						'default'    => '828282',
						'show_reset' => true,
						'preview'    => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.njba-tabs',
									'property' => 'border-color',
								),
							),
						)
					),
					'body_border_radius'   => array(
						'type'        => 'text',
						'default'     => '0',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border radius. Such as: "5 px"', 'bb-njba' ),
						'preview'     => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.njba-tabs ',
									'property' => 'border-radius',
									'unit'     => 'px'
								),
							),
						)
					),
					'header_border_style'  => array(
						'type'    => 'select',
						'label'   => __( 'Header Border Style', 'bb-njba' ),
						'default' => 'solid',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
						/*'toggle'  => array(
							'solid'  => array(
								'fields' => array( 'header_border_width', 'header_border_color', 'header_border_radius' )
							),
							'dashed' => array(
								'fields' => array( 'header_border_width', 'header_border_color', 'header_border_radius' )
							),
							'dotted' => array(
								'fields' => array( 'header_border_width', 'header_border_color', 'header_border_radius' )
							),
							'double' => array(
								'fields' => array( 'header_border_width', 'header_border_color', 'header_border_radius' )
							)
						),*/
					),
					'header_border_width'  => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Header Border Size', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 1,
							'bottom' => 1,
							'left'   => 1,
							'right'  => 1,
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
							)
						)
					),
					'header_border_color'  => array(
						'type'       => 'color',
						'label'      => __( 'Header Border Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.njba-tabs-label.njba-tab-active .njba-tab-label-inner',
									'property' => 'border-color',
								),
							),
						)
					),
					'header_border_radius' => array(
						'type'        => 'text',
						'default'     => '0',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Header Border Radius', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border radius. Such as: "5 px"', 'bb-njba' ),
						'preview'     => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.njba-tabs-label.njba-tab-active .njba-tab-label-inner',
									'property' => 'border-radius',
									'unit'     => 'px'
								),
							),
						)
					),
				)
			),
			'icon_style'     => array(
				'title'  => __( 'Tab Icon', 'bb-njba' ),
				'fields' => array(
					'show_icon'         => array(
						'type'    => 'select',
						'label'   => __( 'Icon', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'no'  => __( 'Disable', 'bb-njba' ),
							'yes' => __( 'Enable', 'bb-njba' ),
						),
						'toggle'  => array(
							'no'  => array(
								'fields' => array( '' )
							),
							'yes' => array(

								'fields' => array( 'tab_icon_position', 'icon_size', 'icon_color', 'icon_active_color', 'icon_line_height' )
							),
						)
					),
					'tab_icon_position' => array(
						'type'    => 'select',
						'label'   => __( 'Position', 'bb-njba' ),
						'default' => 'left',
						'options' => array(
							'top'    => __( 'Top', 'bb-njba' ),
							'bottom' => __( 'Bottom', 'bb-njba' ),
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
						),
					),
					'icon_size'         => array(
						'type'        => 'text',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'placeholder' => __( 'Inherit', 'bb-njba' ),
						'size'        => '8',
						'description' => 'px',
						'help'        => __( 'If icon size is kept bank then title font size would be applied', 'bb-njba' ),
					),
					'icon_line_height'  => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						)
					),
					'icon_color'        => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),
					'icon_active_color' => array(
						'type'       => 'color',
						'label'      => __( 'Active Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),
				)
			),
			'tab_style_main' => array(
				'title'  => __( 'Tab Style', 'bb-njba' ),
				'fields' => array(
					'tab_alignment'        => array(
						'type'    => 'select',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'default' => 'left',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
						),
					),
					'title_margin'         => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Spacing', 'bb-njba' ),
						'description' => 'px',
						'responsive'  => true,
						'default'     => array(
							'top'    => 10,
							'bottom' => 10,
							'left'   => 10,
							'right'  => 10,
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
							)
						)
					),
					'tab_background_color' => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'default'    => 'f6f6f6',
						'show_reset' => true,
					),
					'title_shadow'         => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Box Shadow Size', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'vertical'   => 0,
							'horizontal' => 0,
							'blur'       => 0,
							'spread'     => 0
						),
						'options'     => array(
							'vertical'   => array(
								'placeholder' => __( 'Vertical', 'bb-njba' ),
								'icon'        => 'fa-arrows-v'
							),
							'horizontal' => array(
								'placeholder' => __( 'Horizontal', 'bb-njba' ),
								'icon'        => 'fa-arrows-h'
							),
							'blur'       => array(
								'placeholder' => __( 'Blur', 'bb-njba' ),
								'icon'        => 'fa-circle-o'
							),
							'spread'     => array(
								'placeholder' => __( 'Spread', 'bb-njba' ),
								'icon'        => 'fa-paint-brush'
							),
						)
					),
					'title_shadow_color'   => array(
						'type'       => 'color',
						'label'      => __( 'Box Shadow Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'title_shadow_opacity' => array(
						'type'        => 'text',
						'label'       => __( 'Box Shadow Opacity', 'bb-njba' ),
						'description' => '%',
						'size'        => 5,
						'default'     => 50,
					),
					'tab_padding'          => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Tab Header Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'bottom' => 10,
							'left'   => 10,
							'right'  => 10
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'default'     => '40',
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
							)
						)
					),
					'header_margin'        => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Tab Header Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'bottom' => 10,
							'left'   => 10,
							'right'  => 10
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
							)
						)
					),

				)
			),
			'content_style'  => array(
				'title'  => __( 'Content Style', 'bb-njba' ),
				'fields' => array(
					'content_alignment'            => array(
						'type'    => 'select',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'default' => 'left',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
						),
					),
					'content_color'                => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'content_background_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'default'    => 'ffffff',
						'show_reset' => true,
					),
					'content_background_color_opc' => array(
						'type'        => 'text',
						'label'       => __( 'Background Opacity', 'bb-njba' ),
						'default'     => '100',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
					'box_border_style'             => array(
						'type'    => 'select',
						'label'   => __( 'Border Style', 'bb-njba' ),
						'default' => 'solid',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
						'toggle'  => array(
							'solid'  => array(
								'fields' => array( 'box_border_color', 'box_border_radius', 'box_border_width' )
							),
							'dashed' => array(
								'fields' => array( 'box_border_color', 'box_border_radius', 'box_border_width' )
							),
							'dotted' => array(
								'fields' => array( 'box_border_color', 'box_border_radius', 'box_border_width' )
							),
							'double' => array(
								'fields' => array( 'box_border_color', 'box_border_radius', 'box_border_width' )
							)
						),
					),
					'box_border_width'             => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Border Size', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 1,
							'bottom' => 1,
							'left'   => 1,
							'right'  => 1,
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
							)
						)
					),
					'box_border_color'             => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => '828282',
						'show_reset' => true,
					),
					'box_border_radius'            => array(
						'type'        => 'text',
						'default'     => '0',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border radius. Such as: "5 px"', 'bb-njba' ),
					),
					'content_shadow'               => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Box Shadow Size', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'vertical'   => 0,
							'horizontal' => 0,
							'blur'       => 0,
							'spread'     => 0
						),
						'options'     => array(
							'vertical'   => array(
								'placeholder' => __( 'Vertical', 'bb-njba' ),
								'icon'        => 'fa-arrows-v'
							),
							'horizontal' => array(
								'placeholder' => __( 'Horizontal', 'bb-njba' ),
								'icon'        => 'fa-arrows-h'
							),
							'blur'       => array(
								'placeholder' => __( 'Blur', 'bb-njba' ),
								'icon'        => 'fa-circle-o'
							),
							'spread'     => array(
								'placeholder' => __( 'Spread', 'bb-njba' ),
								'icon'        => 'fa-paint-brush'
							),
						)
					),
					'content_shadow_color'         => array(
						'type'       => 'color',
						'label'      => __( 'Box Shadow Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'content_shadow_opacity'       => array(
						'type'        => 'text',
						'label'       => __( 'Box Shadow Opacity', 'bb-njba' ),
						'description' => '%',
						'size'        => 5,
						'default'     => 50,
					),
					'content_box_padding'          => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'bottom' => 10,
							'left'   => 10,
							'right'  => 10
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
							)
						)
					),
					'content_margin'               => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'bottom' => 10,
							'left'   => 10,
							'right'  => 10
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
							)
						)
					),
				)
			),
			'title_style'    => array(
				'title'  => __( 'Title Caption', 'bb-njba' ),
				'fields' => array(
					'title_color'                 => array(
						'type'       => 'color',
						'label'      => __( 'Text Color', 'bb-njba' ),
						'default'    => '999999',
						'show_reset' => true,
					),
					'title_active_color'          => array(
						'type'       => 'color',
						'label'      => __( 'Text Active Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'title_background_color'      => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),
					'active_tab_background_color' => array(
						'type'       => 'color',
						'label'      => __( 'Active Background Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),

					'title_background_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Background Hover Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),
					'text_alignment'               => array(
						'type'    => 'select',
						'label'   => __( 'Text Alignment', 'bb-njba' ),
						'default' => 'left',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
						),
					),
					'tab_box_padding'              => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'bottom' => 10,
							'left'   => 10,
							'right'  => 10,
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
							)
						)
					),
					'title_active_border'          => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Active Border', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 1,
							'bottom' => 1,
							'left'   => 1,
							'right'  => 1,
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
							)
						)
					),
					'title_active_border_style'    => array(
						'type'    => 'select',
						'label'   => __( 'Active Border Style', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
					),
					'title_active_border_color'    => array(
						'type'       => 'color',
						'label'      => __( 'Active Border Color', 'bb-njba' ),
						'default'    => 'F8F8F8',
						'show_reset' => true,
					),
					'title_active_border_radius'   => array(
						'type'        => 'text',
						'default'     => '0',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Active Border Radius', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border radius. Such as: "5 px"', 'bb-njba' ),
					),
					'tab_border'                   => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Border Size', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 1,
							'bottom' => '',
							'left'   => '',
							'right'  => 1,
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
							)
						)
					),
					'tab_border_style'             => array(
						'type'    => 'select',
						'label'   => __( 'Border Style', 'bb-njba' ),
						'default' => 'solid',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
					),
					'tab_border_color'             => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => '828282',
						'show_reset' => true,
					),
					'tab_border_radius'            => array(
						'type'        => 'text',
						'default'     => '0',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border radius. Such as: "5 px"', 'bb-njba' ),
					),
					'box_shadow'                   => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Box Shadow Size', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'vertical'   => 0,
							'horizontal' => 0,
							'blur'       => 0,
							'spread'     => 0
						),
						'options'     => array(
							'vertical'   => array(
								'placeholder' => __( 'Vertical', 'bb-njba' ),
								'icon'        => 'fa-arrows-v'
							),
							'horizontal' => array(
								'placeholder' => __( 'Horizontal', 'bb-njba' ),
								'icon'        => 'fa-arrows-h'
							),
							'blur'       => array(
								'placeholder' => __( 'Blur', 'bb-njba' ),
								'icon'        => 'fa-circle-o'
							),
							'spread'     => array(
								'placeholder' => __( 'Spread', 'bb-njba' ),
								'icon'        => 'fa-paint-brush'
							),
						)
					),
					'box_shadow_color'             => array(
						'type'       => 'color',
						'label'      => __( 'Box Shadow Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'box_shadow_opacity'           => array(
						'type'        => 'text',
						'label'       => __( 'Box Shadow Opacity', 'bb-njba' ),
						'description' => '%',
						'size'        => 5,
						'default'     => 50,
					),


				)
			)
		)
	),
	'typography' => array(
		'title'    => __( 'Typography', 'bb-njba' ),
		'sections' => array(
			'label_typography'   => array(
				'title'  => __( 'Title', 'bb-njba' ),
				'fields' => array(
					'tab_label_font'         => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-tabs .njba-tab-title'
						)
					),
					'title_font_size'        => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						)
					),
					'title_font_line_height' => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						)
					),
					'label_text_transform'   => array(
						'type'    => 'select',
						'label'   => __( 'Text Transform', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'      => __( 'Default', 'bb-njba' ),
							'lowercase' => __( 'lowercase', 'bb-njba' ),
							'uppercase' => __( 'UPPERCASE', 'bb-njba' ),
						),
					),

				)
			),
			'content_typography' => array(
				'title'  => __( 'Content', 'bb-njba' ),
				'fields' => array(
					'tab_content_font'    => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-tabs-panels .njba-tabs-panel-content'
						)
					),
					'content_font_size'   => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						)
					),
					'content_line_height' => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						)
					),
				)
			),
		)
	)
) );
/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form( 'item_form', array(
	'title' => __( 'Add Tab', 'bb-njba' ),
	'tabs'  => array(
		'general' => array(
			'title'    => __( 'General', 'bb-njba' ),
			'sections' => array(
				'general' => array(
					'title'  => '',
					'fields' => array(
						'tab_font_icon' => array(
							'type'        => 'icon',
							'label'       => __( 'Icon', 'bb-njba' ),
							'show_remove' => true
						),
						'label'         => array(
							'type'    => 'text',
							'label'   => __( 'Title', 'bb-njba' ),
							'default' => 'Title-1',
							'preview' => array(
								'type' => 'none'
							)
						),
					)
				),
				'content' => array(
					'title'  => __( 'Content', 'bb-njba' ),
					'fields' => apply_filters( 'get_advanced_content_fields', array(
						'content_type' => array(
							'type'    => 'select',
							'label'   => __( 'Type', 'bb-njba' ),
							'default' => 'content',
							'options' => apply_filters( 'get_advanced_content_options', array(
								'content' => __( 'Content', 'bb-njba' )
							) ),
							'toggle'  => apply_filters( 'get_advanced_content_toggle', array(
								'content' => array(
									'fields' => array( 'content' )
								)
							) ),
						),
						'content'      => array(
							'type'        => 'editor',
							'label'       => '',
							'default'     => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.',
							'connections' => array( 'string', 'html', 'url' ),
						),
					) )
				)
			)
		)
	)
) );
