<?php

/**
 * @class PPInfoBoxModule
 */
class PPInfoBoxModule extends FLBuilderModule {

	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          => __('Info Box', 'bb-powerpack'),
			'description'   => __('Addon to display info box.', 'bb-powerpack'),
			'group'         => pp_get_modules_group(),
			'category'		=> pp_get_modules_cat( 'content' ),
			'dir'           => BB_POWERPACK_DIR . 'modules/pp-infobox/',
			'url'           => BB_POWERPACK_URL . 'modules/pp-infobox/',
			'editor_export' => true, // Defaults to true and can be omitted.
			'enabled'       => true, // Defaults to true and can be omitted.
			'partial_refresh'   => true,
		));
	}

	public function enqueue_icon_styles() {
		$enqueue = false;
		$settings = $this->settings;

		if ( isset( $settings->icon_type ) && 'icon' === $settings->icon_type && ! empty( $settings->icon_select ) ) {
			$enqueue = true;
		}

		if ( isset( $settings->pp_infobox_link_type ) && in_array( $settings->pp_infobox_link_type, array( 'button', 'button+title' ) )  ) {
			if ( ! empty( $settings->button_icon ) ) {
				$enqueue = true;
			}
		}

		if ( $enqueue && is_callable( 'parent::enqueue_icon_styles' ) ) {
			parent::enqueue_icon_styles();
		}
	}
	
	public function filter_settings( $settings, $helper )
	{
		// Handle old link, link_target, link_nofollow fields.
		$settings = PP_Module_Fields::handle_link_field( $settings, array(
			'link'			=> array(
				'type'			=> 'link'
			),
			'link_target'	=> array(
				'type'			=> 'target'
			),
			'link_nofollow'	=> array(
				'type'			=> 'nofollow'
			)
		), 'link' );

		// Handle button old padding field.
		$settings = PP_Module_Fields::handle_multitext_field( $settings, 'button_padding', 'padding' );

		// Handle button old margin field.
		$settings = PP_Module_Fields::handle_multitext_field( $settings, 'read_more_margin', 'margin' );

		// Handle old button border and radius fields.
		$settings = PP_Module_Fields::handle_border_field( $settings, array(
			'button_border_custom'	=> array(
				'type'		=> 'width',
				'condition'	=> ( isset( $settings->button_border ) && 'custom' == $settings->button_border )
			),
			'button_border_color'	=> array(
				'type'		=> 'color',
				'condition'	=> ( isset( $settings->button_border ) && 'custom' == $settings->button_border )
			),
			'button_border_style'	=> array(
				'type'		=> 'style',
				'condition'	=> ( isset( $settings->button_border ) && 'custom' == $settings->button_border )
			),
			'button_radius'	=> array(
				'type'			=> 'radius'
			)
		), 'button_border_setting' );

		if ( isset( $settings->button_border ) ) {
			unset( $settings->button_border );
		}

		// Handle box old border fields.
		$settings = PP_Module_Fields::handle_border_field( $settings, array(
			'box_border_style'	=> array(
				'type'				=> 'style'
			),
			'box_border_width'	=> array(
				'type'				=> 'width'
			),
			'box_border_color'	=> array(
				'type'				=> 'color'
			),
			'box_border_radius'	=> array(
				'type'				=> 'radius'
			)
		), 'box_border' );

		// Handle old box padding fields.
		if ( isset( $settings->padding_top ) && ( ! isset( $settings->padding_bottom ) || '' === $settings->padding_bottom ) ) {
			$settings->padding_bottom = $settings->padding_top;
		}
		if ( isset( $settings->padding_left ) && ( ! isset( $settings->padding_right ) || '' === $settings->padding_right ) ) {
			$settings->padding_right = $settings->padding_left;
		}

		// Handle box old alignment_responsive field.
		$settings = PP_Module_Fields::handle_alignment_field( $settings, 'alignment', 'alignment_responsive' );

		// Handle icon old border fields.
		$settings = PP_Module_Fields::handle_border_field( $settings, array(
			'icon_border_width'		=> array(
				'type'			=> 'width',
				'condition'		=> ( isset( $settings->show_border ) && 'yes' == $settings->show_border )
			),
			'icon_border_color'		=> array(
				'type'			=> 'color',
				'condition'		=> ( isset( $settings->show_border ) && 'yes' == $settings->show_border )
			),
			'icon_border_style'		=> array(
				'type'			=> 'style',
				'condition'		=> ( isset( $settings->show_border ) && 'yes' == $settings->show_border )
			),
			'icon_border_radius'	=> array(
				'type'			=> 'radius'
			)
		), 'icon_border' );

		// Handle old typography fields - Title Prefix.
		$settings = PP_Module_Fields::handle_typography_field( $settings, array(
			'title_prefix_font'	=> array(
				'type'				=> 'font'
			),
			'title_prefix_font_size'	=> array(
				'type'				=> 'font_size'
			),
			'title_prefix_line_height'	=> array(
				'type'				=> 'line_height'
			)
		), 'title_prefix_typography' );

		// Handle old typography fields - Title.
		$settings = PP_Module_Fields::handle_typography_field( $settings, array(
			'title_font'	=> array(
				'type'				=> 'font'
			),
			'title_font_size'	=> array(
				'type'				=> 'font_size'
			),
			'title_line_height'	=> array(
				'type'				=> 'line_height'
			)
		), 'title_typography' );

		// Handle old typography fields - Description.
		$settings = PP_Module_Fields::handle_typography_field( $settings, array(
			'text_font'	=> array(
				'type'				=> 'font'
			),
			'text_font_size'	=> array(
				'type'				=> 'font_size'
			),
			'text_line_height'	=> array(
				'type'				=> 'line_height'
			)
		), 'desc_typography' );

		// Handle old typography fields - Button.
		$settings = PP_Module_Fields::handle_typography_field( $settings, array(
			'pp_infobox_read_more_font'	=> array(
				'type'				=> 'font'
			),
			'pp_infobox_read_more_font_size'	=> array(
				'type'				=> 'font_size'
			),
		), 'button_typography' );

		return $settings;
	}

	public function render_title() {
		$settings    = $this->settings;
		$link_type   = $settings->pp_infobox_link_type;
		$valid_types = array( 'title', 'button+title' );
		$nofollow	 = ( isset( $settings->link_nofollow ) && 'yes' == $settings->link_nofollow ) ? ' rel="nofollow"' : '';
		$text        = in_array( $link_type, $valid_types ) ? sprintf(
			'<a class="pp-title-link" href="%s" target="%s"%s>%s</a>',
			$settings->link,
			$settings->link_target,
			$nofollow,
			$settings->title
		) : $settings->title;
		$title = sprintf( '<%1$s class="pp-infobox-title">%2$s</%1$s>', $settings->title_tag, $text );
		?>

		<div class="pp-infobox-title-wrapper"><?php echo $title; ?></div>
		<?php
	}

	public function render_title_prefix() {
		$settings   = $this->settings;

		if ( empty( $this->settings->title_prefix ) ) {
			return;
		}

		$prefix_tag = ( isset( $settings->title_prefix_tag ) ) ? $settings->title_prefix_tag : 'span';
		?>
		<<?php echo $prefix_tag; ?> class="pp-infobox-title-prefix"><?php echo $settings->title_prefix; ?></<?php echo $prefix_tag; ?>>
		<?php
	}

	public function render_link()
	{
		$settings 		= $this->settings;
		$button_class 	= ( 'button' == $settings->pp_infobox_link_type && '' != $settings->link_css_class ) ? ' ' . $settings->link_css_class : '';
		$nofollow		= ( isset( $settings->link_nofollow ) && 'yes' == $settings->link_nofollow ) ? ' rel="nofollow"' : '';
		$force_render	= apply_filters( 'pp_infobox_button_force_render', false, $settings );
		$link_tag	    = $force_render && 'box' === $settings->pp_infobox_link_type ? 'div' : 'a';

		if ( 'button' === $settings->pp_infobox_link_type || 'button+title' === $settings->pp_infobox_link_type || $force_render ) {
			?>
			<div class="pp-infobox-button pp-button-wrap">
				<<?php echo $link_tag; ?> class="pp-more-link pp-button<?php echo $button_class; ?>" href="<?php echo $settings->link; ?>" role="button" target="<?php echo $settings->link_target; ?>"<?php echo $nofollow; ?>>
					<?php if ( isset( $settings->button_icon ) && 'left' === $settings->button_icon_aligment ) { ?>
						<i class="pp-button-icon pp-button-icon-left <?php echo $settings->button_icon; ?>"></i>
					<?php } ?>
					<?php echo $settings->pp_infobox_read_more_text; ?>
					<?php if ( isset( $settings->button_icon ) && 'right' === $settings->button_icon_aligment ) { ?>
						<i class="pp-button-icon pp-button-icon-right <?php echo $settings->button_icon; ?>"></i>
					<?php } ?>
				</<?php echo $link_tag; ?>>
			</div>
			<?php
		}
		if ( 'read_more' === $settings->pp_infobox_link_type && ! $force_render ) {
			?>
			<div class="pp-infobox-button">
				<p>
					<a class="pp-more-link<?php echo $button_class; ?>" href="<?php echo $settings->link; ?>" role="button" target="<?php echo $settings->link_target; ?>"<?php echo $nofollow; ?>>
						<?php echo $settings->pp_infobox_read_more_text; ?>
					</a>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * @method get_alt
	 */
	public function get_alt()
	{
		$photo = FLBuilderPhoto::get_attachment_data($this->settings->image_select);

		if(!empty($photo->alt)) {
			return htmlspecialchars($photo->alt);
		}
		else if(!empty($photo->description)) {
			return htmlspecialchars($photo->description);
		}
		else if(!empty($photo->caption)) {
			return htmlspecialchars($photo->caption);
		}
		else if(!empty($photo->title)) {
			return htmlspecialchars($photo->title);
		}
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('PPInfoBoxModule', array(
	'general'      => array( // Tab
		'title'         => __('General', 'bb-powerpack'), // Tab title
		'sections'      => array( // Tab Sections
			'layout'        => array(
				'title'         => '',
				'fields'        => array(
					'layouts'       => array(
						'type'      => 'select',
						'label'     => __('Layout', 'bb-powerpack'),
						'default'   => '5',
						'options'   => array(
							'0'         => __('No Icon', 'bb-powerpack'),
							'5'         => __('Icon at Top', 'bb-powerpack'),
							'3'         => __('Icon at Left', 'bb-powerpack'),
							'4'         => __('Icon at Right', 'bb-powerpack'),
							'1'         => __('Icon at Left with Title', 'bb-powerpack'),
							'2'         => __('Icon at Right with Title', 'bb-powerpack'),
							'6'         => __('Icon below Title', 'bb-powerpack'),
						),
						'toggle' => array(
							'1' => array(
								'tabs'     => array('icon_styles'),
								'sections' => array('type'),
							),
							'2' => array(
								'tabs'     => array('icon_styles'),
								'sections' => array('type'),
							),
							'3' => array(
								'tabs'     => array('icon_styles'),
								'sections' => array('type'),
								'fields'   => array('space_bt_icon_text', 'icon_position', 'stack_breakpoint')
							),
							'4' => array(
								'tabs'     => array('icon_styles'),
								'sections' => array('type'),
								'fields'   => array('space_bt_icon_text', 'icon_position', 'stack_breakpoint')
							),
							'5' => array(
								'tabs'     => array('icon_styles'),
								'sections' => array('type'),
							),
							'6'  => array(
								'tabs'     => array('icon_styles'),
								'sections' => array('type'),
							),
						)
					),
					'icon_position'	=> array(
						'type'			=> 'select',
						'label'			=> __('Align Icon Vertically', 'bb-powerpack'),
						'default'		=> 'default',
						'options'		=> array(
							'default'		=> __('Default', 'bb-powerpack'),
							'top'			=> __('Top', 'bb-powerpack'),
							'center'		=> __('Center', 'bb-powerpack'),
							'bottom'		=> __('Bottom', 'bb-powerpack'),
						)
					),
					'stack_breakpoint' => array(
						'type'    => 'unit',
						'label'   => __( 'Stack on breakpoint', 'bb-powerpack' ),
						'help'   => __( 'This will stack icon and text on the viewport size equals to or below the breakpoint.', 'bb-powerpack' ),
						'default' => '480',
						'units'   => array( 'px' ),
					),
					'inherit_eql_heights' => array(
						'type' => 'pp-switch',
						'label' => __( 'Inherit Columns Equal Heights', 'bb-powerpack' ),
						'default' => 'yes',
						'help' => __( 'This option will only work if equal heights option is enabled in columns setting.', 'bb-powerpack' ),
					),
				)
			),
			'type'      => array(
				'title'     => __('Icon', 'bb-powerpack'),
				'fields'    => array(
					'icon_type'      => array(
						'type'      => 'pp-switch',
						'label'     => __('Icon Type', 'bb-powerpack'),
						'default'   => 'icon',
						'options'   => array(
							'icon'      => __('Icon', 'bb-powerpack'),
							'image'      => __('Image', 'bb-powerpack'),
						),
						'toggle'        => array(
							'icon'      => array(
								'sections'      => array('icon_colors'),
								'fields'        => array('icon_select', 'icon_color', 'icon_font_size', 'icon_color_hover', 'icon_background', 'icon_background_hover', 'icon_width'),
							),
							'image'      => array(
								'fields'        => array('image_select', 'image_width_type'),
							),
						),
					),
					'icon_select'       => array(
						'type'      => 'icon',
						'label'     => __('Icon', 'bb-powerpack'),
						'show_remove'   => true
					),
					'image_select'       => array(
						'type'      => 'photo',
						'label'     => __('Image Icon', 'bb-powerpack'),
						'show_remove'   => true,
						'connections'   => array( 'photo' ),
					),
					'space_bt_icon_text'    => array(
						'type'                  => 'unit',
						'label'                 => __('Space between icon and text', 'bb-powerpack'),
						'units'           		=> array('px'),
						'default'               => 10,
						'slider'				=> true,
						'responsive'			=> true,
						'preview'               => array(
							'type'                  => 'css',
							'rules'                 => array(
								array(
									'selector'              => '.pp-infobox-wrap .layout-3 .pp-icon-wrapper',
									'property'              => 'margin-right',
									'unit'                  => 'px'
								),
								array(
									'selector'              => '.pp-infobox-wrap .layout-4 .pp-icon-wrapper',
									'property'              => 'margin-left',
									'unit'                  => 'px'
								)
							)
						)
					),
					'icon_animation'     => array(
						'type'      => 'select',
						'label'     => __('Animation', 'bb-powerpack'),
						'default'     => 'none',
						'options'       => array(
							'none'          => __('None', 'bb-powerpack'),
							'swing'          => __('Swing', 'bb-powerpack'),
							'pulse'          => __('Pulse', 'bb-powerpack'),
							'flash'          => __('Flash', 'bb-powerpack'),
							'fadeIn'          => __('Fade In', 'bb-powerpack'),
							'fadeInUp'          => __('Fade In Up', 'bb-powerpack'),
							'fadeInDown'          => __('Fade In Down', 'bb-powerpack'),
							'fadeInLeft'          => __('Fade In Left', 'bb-powerpack'),
							'fadeInRight'          => __('Fade In Right', 'bb-powerpack'),
							'slideInUp'          => __('Slide In Up', 'bb-powerpack'),
							'slideInDown'          => __('Slide In Down', 'bb-powerpack'),
							'slideInLeft'          => __('Slide In Left', 'bb-powerpack'),
							'slideInRight'          => __('Slide In Right', 'bb-powerpack'),
							'bounceIn'          => __('Bounce In', 'bb-powerpack'),
							'bounceInUp'          => __('Bounce In Up', 'bb-powerpack'),
							'bounceInDown'          => __('Bounce In Down', 'bb-powerpack'),
							'bounceInLeft'          => __('Bounce In Left', 'bb-powerpack'),
							'bounceInRight'          => __('Bounce In Right', 'bb-powerpack'),
							'flipInX'          => __('Flip In X', 'bb-powerpack'),
							'FlipInY'          => __('Flip In Y', 'bb-powerpack'),
							'lightSpeedIn'          => __('Light Speed In', 'bb-powerpack'),
							'rotateIn'          => __('Rotate In', 'bb-powerpack'),
							'rotateInUpLeft'          => __('Rotate In Up Left', 'bb-powerpack'),
							'rotateInUpRight'          => __('Rotate In Up Right', 'bb-powerpack'),
							'rotateInDownLeft'          => __('Rotate In Down Left', 'bb-powerpack'),
							'rotateInDownRight'          => __('Rotate In Down Right', 'bb-powerpack'),
							'rollIn'          => __('Roll In', 'bb-powerpack'),
							'zoomIn'          => __('Zoom In', 'bb-powerpack'),
							'slideInUp'          => __('Slide In Up', 'bb-powerpack'),
							'slideInDown'          => __('Slide In Down', 'bb-powerpack'),
							'slideInLeft'          => __('Slide In Left', 'bb-powerpack'),
							'slideInRight'          => __('Slide In Right', 'bb-powerpack'),
						)
					),
					'animation_duration'    => array(
						'type'      => 'text',
						'label'     => __('Animation Duration', 'bb-powerpack'),
						'default'     => '500',
						'maxlength'     => '4',
						'size'      => '5',
						'description'   => _x( 'ms', 'Value unit for animation duration. Such as: "1s"', 'bb-powerpack' ),
						'preview'       => array(
							'type'      => 'css',
							'selector'  => '.animated',
							'property'  => 'animation-duration'
						),
					),
				),
			),
		)
	),
	'content'   => array(
		'title'     => __('Content', 'bb-powerpack'),
		'sections'  => array(
			'title_prefix'     => array(
				'title'     => __('Title Prefix', 'bb-powerpack'),
				'fields'    => array(
					'title_prefix'     => array(
						'type'      => 'text',
						'label'     => '',
						'connections'   => array( 'string', 'html', 'url' ),
					),
				),
			),
			'title'     => array(
				'title'     => __('Title', 'bb-powerpack'),
				'fields'    => array(
					'title'     => array(
						'type'      => 'text',
						'label'     => '',
						'connections'   => array( 'string', 'html', 'url' ),
						'preview'       => array(
							'type'          => 'text',
							'selector'      => '.pp-infobox-title-wrapper .pp-infobox-title'
						)
					),
				),
			),
			'description'     => array(
				'title'     => __('Description', 'bb-powerpack'),
				'fields'    => array(
					'description'     => array(
						'type'      	=> 'editor',
						'label'     	=> '',
						'default'     	=> '',
						'media_buttons' => true,
						'rows'      	=> 4,
						'connections'   => array( 'string', 'html', 'url' ),
						'preview'       => array(
							'type'          => 'text',
							'selector'      => '.pp-infobox-description .pp-description-wrap'
						)
					),
				),
			),
		)
	),
	'button_link'   => array(
		'title'         => __('Link', 'bb-powerpack'),
		'sections'      => array(
			'pp_infobox_link_type'     => array(
				'collapsed'		=> false,
				'title'     => __('Link', 'bb-powerpack'),
				'fields'    => array(
					'pp_infobox_link_type'      => array(
						'type'      => 'select',
						'label'     => __('Link Type', 'bb-powerpack'),
						'default'   => 'none',
						'options'   => array(
							'none'      => __('None', 'bb-powerpack'),
							'box'       => __('Complete Box', 'bb-powerpack'),
							'title'     => __('Title Only', 'bb-powerpack'),
							'read_more' => __('Text Link', 'bb-powerpack'),
							'button'    => __('Button', 'bb-powerpack'),
							'button+title' => __('Button + Title', 'bb-powerpack'),
						),
						'toggle'    => array(
							'box'     => array(
								'fields'    => array('link')
							),
							'title'     => array(
								'fields'    => array('link')
							),
							'read_more'     => array(
								'fields'        => array('pp_infobox_read_more_text', 'link'),
								'sections'      => array('link_style', 'button_typography')
							),
							'button'     => array(
								'fields'        => array('pp_infobox_read_more_text', 'button_icon', 'button_icon_aligment', 'button_icon_spacing', 'link', 'link_css_class', 'button_bg_color', 'button_bg_hover_color', 'button_padding', 'button_radius', 'button_width', 'field_separator_1', 'field_separator_3', 'button_icon_color', 'button_icon_color_hover', 'button_icon_size'),
								'sections'      => array('link_style', 'button_border', 'button_typography')
							),
							'button+title'     => array(
								'fields'        => array('pp_infobox_read_more_text', 'button_icon', 'button_icon_aligment', 'button_icon_spacing', 'link', 'link_css_class', 'button_bg_color', 'button_bg_hover_color', 'button_padding', 'button_radius', 'button_width', 'field_separator_1', 'field_separator_3', 'button_icon_color', 'button_icon_color_hover', 'button_icon_size'),
								'sections'      => array('link_style', 'button_border', 'button_typography')
							),
						)
					),
					'pp_infobox_read_more_text' => array(
						'type'      => 'text',
						'label'         => __('Text', 'bb-powerpack'),
						'default'       => __('Read More', 'bb-powerpack'),
						'preview'       => array(
							'type'      => 'text',
							'selector'  => '.pp-more-link'
						),
						'connections'	=> array('string'),
					),
					'button_icon'               => array(
						'type'          => 'icon',
						'label'         => __('Icon', 'bb-powerpack'),
						'show_remove'	=> true
					),
					'button_icon_aligment'             => array(
						'type'          => 'pp-switch',
						'label'         => __('Icon Position', 'bb-powerpack'),
						'default'       => 'left',
						'options'       => array(
							'left'      => __('Before Text', 'bb-powerpack'),
							'right'     => __('After Text', 'bb-powerpack')
						),
					),
					'button_icon_spacing'              => array(
						'type'        => 'unit',
						'label'       => __('Spacing', 'bb-powerpack'),
						'default'     => '10',
						'units'       => array( 'px' ),
						'slider'      => true,
					),
					'link'                      => array(
						'type'          => 'link',
						'label'         => __('Link', 'bb-powerpack'),
						'placeholder'   => 'http://www.example.com',
						'show_target'	=> true,
						'show_nofollow'	=> true,
						'connections'   => array( 'url' ),
						'preview'       => array(
							'type'          => 'none'
						),
					),
					'link_css_class'            => array(
						'type'  => 'text',
						'label' => __('Custom CSS Class', 'bb-powerpack'),
						'default'   => ''
					),
				),
			),
			'link_style'    => array(
				'collapsed'		=> true,
				'title'         => __('Style', 'bb-powerpack'),
				'fields'        => array(
					'button_bg_color'                  => array(
						'type'      => 'color',
						'label'     => __('Background Color', 'bb-powerpack'),
						'default'   => '',
						'show_reset'    => true,
						'show_alpha'	=> true,
						'connections'	=> array('color'),
						'preview'   => array(
							'type'      => 'css',
							'selector'  => '.pp-infobox .pp-more-link',
							'property'  => 'background-color'
						),
					),
					'button_bg_hover_color'            => array(
						'type'      => 'color',
						'label'     => __('Background Hover Color', 'bb-powerpack'),
						'default'   => '',
						'show_reset'    => true,
						'show_alpha'	=> true,
						'connections'	=> array('color'),
						'preview'   => array(
							'type'      => 'none',
						),
					),
					'field_separator_1'                => array(
						'type'      => 'pp-separator',
						'color'   => 'eeeeee'
					),
					'pp_infobox_read_more_color'       => array(
						'type'      => 'color',
						'label'     => __('Text Color', 'bb-powerpack'),
						'default'   => '',
						'show_reset'    => true,
						'connections'	=> array('color'),
						'preview'   => array(
							'type'  => 'css',
							'selector'  => '.pp-infobox .pp-more-link',
							'property'  => 'color'
						),
					),
					'pp_infobox_read_more_color_hover' => array(
						'type'      => 'color',
						'label'     => __('Text Hover Color', 'bb-powerpack'),
						'default'   => '',
						'show_reset'    => true,
						'connections'	=> array('color'),
						'preview'   => array(
							'type'  => 'css',
							'selector'  => '.pp-infobox .pp-more-link:hover',
							'property'  => 'color'
						),
					),
					'field_separator_2'                => array(
						'type'      => 'pp-separator',
						'color'     => 'eeeeee'
					),
					'button_padding'                   => array(
						'type'				=> 'dimension',
						'label'				=> __('Padding', 'bb-powerpack'),
						'default'			=> '10',
						'units'				=> array('px'),
						'slider'			=> true,
						'responsive'		=> true,
						'preview'			=> array(
							'type'				=> 'css',
							'selector'			=> '.pp-infobox .pp-more-link',
							'property'			=> 'padding',
							'unit'				=> 'px'
						)
					),
					'read_more_margin'                 => array(
						'type'              => 'dimension',
						'label'             => __('Margin', 'bb-powerpack'),
						'default'           => '',
						'units'				=> array('px'),
						'slider'			=> true,
						'responsive'		=> true,
						'preview'			=> array(
							'type'				=> 'css',
							'selector'			=> '.pp-infobox .pp-more-link',
							'property'			=> 'margin',
							'unit'				=> 'px'
						)
					),
					'field_separator_3'                => array(
						'type'      => 'pp-separator',
						'color'     => 'eeeeee'
					),
					'button_width'                     => array(
						'type'          => 'pp-switch',
						'label'         => __('Button Width', 'bb-powerpack'),
						'default'       => 'auto',
						'options'       => array(
							'auto'          => __('Auto', 'bb-powerpack'),
							'custom'        => __('Custom', 'bb-powerpack'),
							'full'          => __('Full Width', 'bb-powerpack')
						),
						'toggle'        => array(
							'custom'        => array(
								'fields'        => array('button_width_custom')
							)
						)
					),
					'button_width_custom'              => array(
						'type'                  => 'unit',
						'label'                 => __('Custom Width', 'bb-powerpack'),
						'default'               => '',
						'units'					=> array('px'),
						'slider'           		=> true,
						'responsive'			=> true,
						'preview'				=> array(
							'type'					=> 'css',
							'selector'				=> '.pp-infobox .pp-more-link',
							'property'				=> 'width',
							'unit'					=> 'px'
						)
					),
					'button_icon_color'                => array(
						'type'        => 'color',
						'label'       => __('Icon Color', 'bb-powerpack'),
						'default'     => '',
						'show_reset'  => true,
						'connections' => array('color'),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.pp-button-icon',
							'property' => 'color'
						),
					),
					'button_icon_color_hover'          => array(
						'type'        => 'color',
						'label'       => __('Icon Hover Color', 'bb-powerpack'),
						'default'     => '',
						'show_reset'  => true,
						'connections' => array('color'),
						'preview'     => array(
							'type'     => 'none',
						),
					),
					'button_icon_size'                 => array(
						'type'        => 'unit',
						'label'       => __('Icon Size', 'bb-powerpack'),
						'default'     => '15',
						'units'       => array( 'px' ),
						'slider'      => true,
					),
				),
			),
			'button_border' => array(
				'collapsed'		=> true,
				'title'         => __('Border', 'bb-powerpack'),
				'fields'        => array(
					'button_border_setting' => array(
						'type'          => 'border',
						'label'         => __( 'Border', 'bb-powerpack' ),
						'responsive'	=> true,
						'preview'       => array(
							'type'          => 'css',
							'selector'		=> '.pp-infobox .pp-more-link',
							'important'		=> true,
						),
					),
					'button_border_hover_color'   => array(
						'type'          => 'color',
						'label'         => __('Border Hover Color', 'bb-powerpack'),
						'show_reset'    => true,
						'connections'	=> array('color'),
						'preview'       => array(
							'type'          => 'none',
						),
					)
				)
			)
		)
	),
	'box'       => array(
		'title'     => __('Style', 'bb-powerpack'),
		'sections'  => array(
			'box_style'     => array(
				'collapsed'		=> false,
				'title'         => __('Box', 'bb-powerpack'),
				'fields'        => array(
					'box_background'    => array(
						'type'              => 'color',
						'label'             => __('Background Color', 'bb-powerpack'),
						'default'           => 'ffffff',
						'show_reset'        => true,
						'show_alpha'    	=> true,
						'connections'		=> array('color'),
						'preview'           => array(
							'type'              => 'css',
							'selector'          => '.pp-infobox',
							'property'          => 'background'
						),
					),
					'box_background_hover' => array(
						'type'        => 'color',
						'label'       => __('Background Hover Color', 'bb-powerpack'),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'connections' => array('color'),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.pp-infobox:hover',
							'property' => 'background'
						),
					),
					'alignment' => array(
						'type'       => 'align',
						'label'      => __('Content Alignment', 'bb-powerpack'),
						'default'    => 'center',
						'responsive' => true
					),
					'padding'    => array(
						'type'       => 'dimension',
						'label'      => __( 'Padding', 'bb-powerpack' ),
						'units'      => array( 'px' ),
						'slider'     => true,
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.pp-infobox',
							'property' => 'padding'
						),
					),
					'box_border'	=> array(
						'type'          => 'border',
						'label'         => __( 'Border', 'bb-powerpack' ),
						'responsive'	=> true,
						'preview'   	=> array(
							'type'  		=> 'css',
							'selector'  	=> '.pp-infobox',
							'property'  	=> 'border',
						),
					),
				)
			),
			'title_prefix_style'   => array(
				'collapsed'		=> true,
				'title'         => __('Title Prefix', 'bb-powerpack'),
				'fields'        => array(
					'title_prefix_color'    => array(
						'type'          => 'color',
						'label'         => __('Color', 'bb-powerpack'),
						'show_reset'    => true,
						'connections'	=> array('color'),
						'preview'       => array(
							'type'          => 'css',
							'selector'      => '.pp-infobox-title-prefix',
							'property'      => 'color',
						)
					),
					'title_prefix_margin'      => array(
						'type'              => 'pp-multitext',
						'label'             => __('Margin', 'bb-powerpack'),
						'description'       => 'px',
						'default'           => array(
							'top'               => '',
							'bottom'            => '',
						),
						'options'           => array(
							'top'               => array(
								'placeholder'       => __('Top', 'bb-powerpack'),
								'tooltip'           => __('Top', 'bb-powerpack'),
								'icon'              => 'fa-long-arrow-up',
								'preview'           => array(
									'selector'          => '.pp-infobox-title-prefix',
									'property'          => 'margin-top',
									'unit'              => 'px'
								),
							),
							'bottom'            => array(
								'placeholder'       => __('Bottom', 'bb-powerpack'),
								'tooltip'           => __('Bottom', 'bb-powerpack'),
								'icon'              => 'fa-long-arrow-down',
								'preview'           => array(
									'selector'          => '.pp-infobox-title-prefix',
									'property'          => 'margin-bottom',
									'unit'              => 'px'
								),
							),
						)
					),
				)
			),
			'title_style'   => array(
				'collapsed'		=> true,
				'title'         => __('Title', 'bb-powerpack'),
				'fields'        => array(
					'title_color'    => array(
						'type'          => 'color',
						'label'         => __('Color', 'bb-powerpack'),
						'show_reset'    => true,
						'connections'	=> array('color'),
						'preview'       => array(
							'type'          => 'css',
							'selector'      => '.pp-infobox-title-wrapper .pp-infobox-title',
							'property'      => 'color',
						)
					),
					'title_color_h'    => array(
						'type'          => 'color',
						'label'         => __('Hover Color', 'bb-powerpack'),
						'show_reset'    => true,
						'connections'	=> array('color'),
						'preview'       => array(
							'type'          => 'none',
						)
					),
					'title_margin'      => array(
						'type'              => 'pp-multitext',
						'label'             => __('Margin', 'bb-powerpack'),
						'description'       => 'px',
						'default'           => array(
							'top'               => 10,
							'bottom'            => 10,
						),
						'options'           => array(
							'top'               => array(
								'placeholder'       => __('Top', 'bb-powerpack'),
								'tooltip'           => __('Top', 'bb-powerpack'),
								'icon'              => 'fa-long-arrow-up',
								'preview'           => array(
									'selector'          => '.pp-infobox-title-wrapper .pp-infobox-title',
									'property'          => 'margin-top',
									'unit'              => 'px'
								),
							),
							'bottom'            => array(
								'placeholder'       => __('Bottom', 'bb-powerpack'),
								'tooltip'           => __('Bottom', 'bb-powerpack'),
								'icon'              => 'fa-long-arrow-down',
								'preview'           => array(
									'selector'          => '.pp-infobox-title-wrapper .pp-infobox-title',
									'property'          => 'margin-bottom',
									'unit'              => 'px'
								),
							),
						)
					),
				)
			),
			'description_style' => array(
				'collapsed'		=> true,
				'title'             => __('Description', 'bb-powerpack'),
				'fields'            => array(
					'text_color'    => array(
						'type'          => 'color',
						'label'         => __('Color', 'bb-powerpack'),
						'show_reset'    => true,
						'connections'	=> array('color'),
						'preview'       => array(
							'type'          => 'css',
							'selector'      => '.pp-infobox-description .pp-description-wrap',
							'property'      => 'color',
						)
					),
					'text_color_h'    => array(
						'type'          => 'color',
						'label'         => __('Hover Color', 'bb-powerpack'),
						'show_reset'    => true,
						'connections'	=> array('color'),
						'preview'       => array(
							'type'          => 'none',
						)
					),
					'text_margin'       => array(
						'type'              => 'pp-multitext',
						'label'             => __('Margin', 'bb-powerpack'),
						'description'       => 'px',
						'default'           => array(
							'top'               => 0,
							'bottom'            => 0,
						),
						'options'           => array(
							'top'               => array(
								'placeholder'       => __('Top', 'bb-powerpack'),
								'tooltip'           => __('Top', 'bb-powerpack'),
								'icon'              => 'fa-long-arrow-up',
								'preview'           => array(
									'selector'          => '.pp-infobox-description .pp-description-wrap',
									'property'          => 'margin-top',
									'unit'              => 'px'
								),
							),
							'bottom'            => array(
								'placeholder'       => __('Bottom', 'bb-powerpack'),
								'tooltip'           => __('Bottom', 'bb-powerpack'),
								'icon'              => 'fa-long-arrow-down',
								'preview'           => array(
									'selector'          => '.pp-infobox-description .pp-description-wrap',
									'property'          => 'margin-bottom',
									'unit'              => 'px'
								),
							),
						)
					),
				)
			),
		),
	),
	'icon_styles'   => array(
		'title'     => __('Icon Style', 'bb-powerpack'),
		'sections'  => array(
			'icon_size'   => array(
				'collapsed'	=> false,
				'title'     => __('Size', 'bb-powerpack'),
				'fields'    => array(
					'icon_font_size'    => array(
						'type'          => 'unit',
						'label'         => __('Icon Size', 'bb-powerpack'),
						'default'       => '16',
						'units'   		=> array('px'),
						'slider'		=> true,
						'responsive'    => true,
						'preview'       => array(
							'type'          => 'css',
							'rules'         => array(
								array(
									'selector'      => '.pp-infobox-icon-inner span.pp-icon',
									'property'      => 'font-size',
									'unit'          => 'px'
								),
								array(
									'selector'      => '.pp-infobox-icon-inner span.pp-icon:before',
									'property'      => 'font-size',
									'unit'          => 'px'
								),
							),
						)
					),
					'icon_width'    => array(
						'type'          => 'unit',
						'label'         => __('Icon Box Size', 'bb-powerpack'),
						'default'       => '0',
						'units'   		=> array('px'),
						'slider'		=> true,
						'responsive'	=> true,
						'preview'       => array(
							'type'          => 'css',
							'rules'           => array(
								array(
									'selector'      => '.pp-infobox-icon-inner',
									'property'     => 'width',
									'unit'          => 'px'
								),
								array(
									'selector'      => '.pp-infobox-icon-inner',
									'property'     => 'height',
									'unit'          => 'px'
								),
							),
						)
					),
					'image_width_type'  => array(
						'type'              => 'pp-switch',
						'label'             => __('Image Icon Size', 'bb-powerpack'),
						'default'           => 'custom',
						'options'           => array(
							'default'           => __('Default', 'bb-powerpack'),
							'custom'            => __('Custom', 'bb-powerpack'),
						),
						'toggle'            => array(
							'custom'            => array(
								'fields'            => array('image_width')
							)
						)
					),
					'image_width'    => array(
						'type'          => 'unit',
						'label'         => __('Image Icon Custom Size', 'bb-powerpack'),
						'default'     	=> '100',
						'units'   		=> array('px'),
						'slider'		=> true,
						'responsive'	=> true,
					),
				),
			),
			'icon_colors'   => array(
				'collapsed'		=> true,
				'title'         => __('Colors', 'bb-powerpack'),
				'fields'        => array(
					'icon_color'    => array(
						'type'          => 'color',
						'label'         => __('Color', 'bb-powerpack'),
						'show_reset'    => true,
						'connections'	=> array('color'),
						'preview'       => array(
							'type'          => 'css',
							'selector'      => '.pp-infobox-icon span.pp-icon',
							'property'      => 'color',
						)
					),
					'icon_color_hover'    => array(
						'type'          => 'color',
						'label'         => __('Hover Color', 'bb-powerpack'),
						'show_reset'    => true,
						'connections'	=> array('color'),
						'preview'       => array(
							'type'          => 'none',
						)
					),
					'icon_background'    => array(
						'type'          => 'color',
						'label'         => __('Background', 'bb-powerpack'),
						'show_reset'    => true,
						'show_alpha'	=> true,
						'connections'	=> array('color'),
						'preview'       => array(
							'type'          => 'css',
							'selector'      => '.pp-infobox-icon',
							'property'      => 'background',
						)
					),
					'icon_background_hover'    => array(
						'type'          => 'color',
						'label'         => __('Background Hover Color', 'bb-powerpack'),
						'show_reset'    => true,
						'show_alpha'	=> true,
						'connections'	=> array('color'),
						'preview'       => array(
							'type'          => 'css',
							'selector'      => '.pp-infobox-icon:hover',
							'property'      => 'background',
						)
					),
				)
			),
			'icon_border'   => array(
				'collapsed'		=> true,
				'title'         => __('Border', 'bb-powerpack'),
				'fields'        => array(
					'icon_border'	=> array(
						'type'          => 'border',
						'label'         => __( 'Border', 'bb-powerpack' ),
						'responsive'	=> true,
					),
					'icon_border_color_hover'    => array(
						'type'          => 'color',
						'label'         => __('Border Hover Color', 'bb-powerpack'),
						'show_reset'    => true,
						'connections'	=> array('color'),
						'preview'       => array(
							'type'          => 'css',
							'rules'     => array(
								array(
									'selector'      => '.pp-infobox-icon:hover',
									'property'      => 'border-color',
								),
								array(
									'selector'      => '.pp-infobox-image img:hover',
									'property'      => 'border-color',
								),
							),
						)
					),
					'icon_box_size'     => array(
						'type'          	=> 'unit',
						'default'     		=> '0',
						'label'         	=> __('Inside Spacing', 'bb-powerpack'),
						'units'   			=> array('px'),
						'slider'			=> true,
						'responsive'		=> true,
						'help'      		=> __('Space between icon and the border', 'bb-powerpack'),
						'preview'       	=> array(
							'type'          	=> 'css',
							'rules'           	=> array(
								array(
									'selector'      => '.pp-infobox-image img',
									'property'     => 'padding',
									'unit'          => 'px'
								),
								array(
									'selector'      => '.pp-infobox-icon',
									'property'     => 'padding',
									'unit'          => 'px'
								),
							),
						)
					),
				)
			),
		),
	),
	'typography'      => array( // Tab
		'title'         => __('Typography', 'bb-powerpack'), // Tab title
		'sections'      => array( // Tab Sections
			'title_prefix'     => array(
				'collapsed'	=> false,
				'title'     => __('Title Prefix', 'bb-powerpack'),
				'fields'    => array(
					'title_prefix_tag' => array(
						'type'      => 'select',
						'label'     => __('HTML Tag', 'bb-powerpack'),
						'default'   => 'span',
						'options'   => array(
							'h1'        => 'H1',
							'h2'        => 'H2',
							'h3'        => 'H3',
							'h4'        => 'H4',
							'h5'        => 'H5',
							'h6'        => 'H6',
							'div'       => 'div',
							'p'         => 'p',
							'span'      => 'span'
						)
					),
					'title_prefix_typography'		=> array(
						'type'					=> 'typography',
						'label'					=> __('Typography', 'bb-powerpack'),
						'responsive'  			=> true,
						'preview'				=> array(
							'type'					=> 'css',
							'selector'				=> '.pp-infobox-title-prefix'
						)
					),
				),
			),
			'general'     => array(
				'collapsed'	=> true,
				'title'     => __('Title', 'bb-powerpack'),
				'fields'    => array(
					'title_tag' => array(
						'type'      => 'select',
						'label'     => __('HTML Tag', 'bb-powerpack'),
						'default'   => 'h4',
						'options'   => array(
							'h1'        => 'H1',
							'h2'        => 'H2',
							'h3'        => 'H3',
							'h4'        => 'H4',
							'h5'        => 'H5',
							'h6'        => 'H6',
							'div'       => 'div',
							'p'         => 'p'
						)
					),
					'title_typography'		=> array(
						'type'					=> 'typography',
						'label'					=> __('Typography', 'bb-powerpack'),
						'responsive'  			=> true,
						'preview'				=> array(
							'type'					=> 'css',
							'selector'				=> '.pp-infobox-title-wrapper .pp-infobox-title'
						)
					),
				),
			),
			'text_typography'   => array(
				'collapsed'	=> true,
				'title'     => __('Description', 'bb-powerpack'),
				'fields'    => array(
					'desc_typography'		=> array(
						'type'					=> 'typography',
						'label'					=> __('Typography', 'bb-powerpack'),
						'responsive'  			=> true,
						'preview'				=> array(
							'type'					=> 'css',
							'selector'				=> '.pp-infobox-description'
						)
					),
				)
			),
			'button_typography'     => array(
				'collapsed'	=> true,
				'title'     => __('Link', 'bb-powerpack'),
				'fields'    => array(
					'button_typography'	=> array(
						'type'					=> 'typography',
						'label'					=> __('Typography', 'bb-powerpack'),
						'responsive'  			=> true,
						'preview'				=> array(
							'type'					=> 'css',
							'selector'				=> '.pp-more-link'
						)
					),
				)
			),
		)
	)
));
