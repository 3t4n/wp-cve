<?php

/**
 * @class PPHeadingModule
 */
class PPHeadingModule extends FLBuilderModule {

	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Smart Headings', 'bb-powerpack' ),
				'description'     => __( 'A module for Smart Headings.', 'bb-powerpack' ),
				'group'           => pp_get_modules_group(),
				'category'        => pp_get_modules_cat( 'content' ),
				'dir'             => BB_POWERPACK_DIR . 'modules/pp-heading/',
				'url'             => BB_POWERPACK_URL . 'modules/pp-heading/',
				'editor_export'   => true, // Defaults to true and can be omitted.
				'enabled'         => true, // Defaults to true and can be omitted.
				'partial_refresh' => true,
			)
		);
	}

	public function enqueue_icon_styles() {
		$enqueue = false;

		if ( isset( $this->settings ) && 'font_icon_select' === $this->settings->heading_icon_select && ! empty( $this->settings->heading_font_icon_select ) ) {
			$enqueue = true;
		}

		if ( $enqueue && is_callable( 'parent::enqueue_icon_styles' ) ) {
			parent::enqueue_icon_styles();
		}
	}

	public function filter_settings( $settings, $helper ) {
		// Handle old link field.
		$settings = PP_Module_Fields::handle_link_field(
			$settings,
			array(
				'heading_link'            => array(
					'type' => 'link',
				),
				'heading_link_target' => array(
					'type' => 'target',
				),
			),
			'heading_link'
		);

		// Handle old title border width field.
		if ( isset( $settings->heading_border ) && is_array( $settings->heading_border ) ) {
			$settings = PP_Module_Fields::handle_multitext_field( $settings, 'heading_border', 'dimension' );
		}
		if ( isset( $settings->heading2_border ) && is_array( $settings->heading2_border ) ) {
			$settings = PP_Module_Fields::handle_multitext_field( $settings, 'heading2_border', 'dimension' );
		}

		// Handle old title padding field.
		if ( isset( $settings->heading_padding ) && is_array( $settings->heading_padding ) ) {
			$settings = PP_Module_Fields::handle_multitext_field( $settings, 'heading_padding', 'dimension' );
		}
		if ( isset( $settings->heading2_padding ) && is_array( $settings->heading2_padding ) ) {
			$settings = PP_Module_Fields::handle_multitext_field( $settings, 'heading2_padding', 'dimension' );
		}

		// Title Gradient Fields
		if ( isset( $settings->heading_gradient ) && 'yes' == $settings->heading_gradient ) {
			$settings->heading_color_type = 'gradient';
			unset( $settings->heading_gradient );
		}

		// - Handle old title gradient field.
		$settings = PP_Module_Fields::handle_gradient_field( $settings, array(
			'heading_gradient_primary_color'	=> array(
				'type'		=> 'primary_color',
			),
			'heading_gradient_secondary_color'	=> array(
				'type'		=> 'secondary_color',
			),
			'heading_gradient_degree'	=> array(
				'type'		=> 'angle',
			),
		), 'heading_gradient_setting' );

		// Secondary Title Gradient Fields
		if ( isset( $settings->heading2_gradient ) && 'yes' == $settings->heading2_gradient ) {
			$settings->heading2_color_type = 'gradient';
			unset( $settings->heading2_gradient );
		}

		// - Handle old secondary title gradient field.
		$settings = PP_Module_Fields::handle_gradient_field( $settings, array(
			'heading2_gradient_primary_color'	=> array(
				'type'		=> 'primary_color',
			),
			'heading2_gradient_secondary_color'	=> array(
				'type'		=> 'secondary_color',
			),
			'heading2_gradient_degree'	=> array(
				'type'		=> 'angle',
			),
		), 'heading2_gradient_setting' );

		// Heading Typography.
		$settings = PP_Module_Fields::handle_typography_field( $settings, array(
			'heading_font'	=> array(
				'type'			=> 'font'
			),
			'heading_font_size'	=> array(
				'type'			=> 'font_size',
				'condition'		=> ( isset( $settings->heading_font_size_select ) && 'custom' == $settings->heading_font_size_select )
			),
			'heading_tablet_font_size' => array(
				'type'			=> 'medium_font_size',
			),
			'heading_tablet_line_height_n' => array(
				'type'			=> 'medium_line_height',
			),
			'heading_mobile_font_size' => array(
				'type'			=> 'responsive_font_size',
			),
			'heading_mobile_line_height_n' => array(
				'type'			=> 'responsive_line_height',
			),
			'heading_line_height_n'	=> array(
				'type'			=> 'line_height',
			),
			'heading_letter_space'	=> array(
				'type'			=> 'letter_spacing'
			),
			'heading_text_transform'	=> array(
				'type'			=> 'text_transform',
				'condition'		=> ( isset( $settings->heading_text_transform ) && 'default' != $settings->heading_text_transform )
			),
			'heading_shadow'	=> array(
				'type'				=> 'text_shadow',
				'color'				=> ( isset( $settings->heading_shadow_color ) ) ? $settings->heading_shadow_color : '',
				'condition'			=> ( isset( $settings->heading_show_shadow ) && 'yes' == $settings->heading_show_shadow )
			)
		), 'title_typography' );

		if ( isset( $settings->heading_shadow_color ) ) {
			//unset( $settings->heading_shadow_color );
		}

		// Secondary Title Typography.
		$settings = PP_Module_Fields::handle_typography_field( $settings, array(
			'heading2_font'	=> array(
				'type'			=> 'font'
			),
			'heading2_font_size'	=> array(
				'type'			=> 'font_size',
				'condition'		=> ( isset( $settings->heading2_font_size_select ) && 'custom' == $settings->heading2_font_size_select )
			),
			'heading2_tablet_font_size' => array(
				'type'			=> 'medium_font_size',
			),
			'heading2_tablet_line_height_n' => array(
				'type'			=> 'medium_line_height',
			),
			'heading2_mobile_font_size' => array(
				'type'			=> 'responsive_font_size',
			),
			'heading2_mobile_line_height_n' => array(
				'type'			=> 'responsive_line_height',
			),
			'heading2_line_height_n'	=> array(
				'type'			=> 'line_height',
			),
			'heading2_letter_space'	=> array(
				'type'			=> 'letter_spacing'
			),
			'heading2_text_transform'	=> array(
				'type'			=> 'text_transform',
				'condition'		=> ( isset( $settings->heading2_text_transform ) && 'default' != $settings->heading2_text_transform )
			),
			'heading2_shadow'	=> array(
				'type'				=> 'text_shadow',
				'color'				=> ( isset( $settings->heading2_shadow_color ) ) ? $settings->heading2_shadow_color : '',
				'condition'			=> ( isset( $settings->heading2_show_shadow ) && 'yes' == $settings->heading2_show_shadow )
			)
		), 'title2_typography' );

		if ( isset( $settings->heading2_shadow_color ) ) {
			//unset( $settings->heading2_shadow_color );
		}

		// Sub Heading / Description Typography.
		$settings = PP_Module_Fields::handle_typography_field( $settings, array(
			'sub_heading_font'	=> array(
				'type'		=> 'font'
			),
			'sub_heading_font_size'	=> array(
				'type'		=> 'font_size',
				'condition'	=> ( isset( $settings->sub_heading_font_size_select ) && 'custom' == $settings->sub_heading_font_size_select )
			),
			'sub_heading_line_height_n'	=> array(
				'type'		=> 'line_height'
			),
			'sub_heading_tablet_font_size'	=> array(
				'type'		=> 'medium_font_size'
			),
			'sub_heading_tablet_line_height_n'	=> array(
				'type'		=> 'medium_line_height'
			),
			'sub_heading_mobile_font_size'	=> array(
				'type'		=> 'responsive_font_size'
			),
			'sub_heading_mobile_line_height_n'	=> array(
				'type'		=> 'responsive_line_height'
			),
		), 'desc_typography' );

		if ( isset( $settings->sub_heading_font_size_select ) ) {
			unset( $settings->sub_heading_font_size_select );
		}

		// Alignment
		if ( isset( $settings->heading_tablet_alignment ) && ! empty( $settings->heading_tablet_alignment ) ) {
			$settings->heading_alignment_medium = $settings->heading_tablet_alignment;
			unset( $settings->heading_tablet_alignment );
		}
		if ( isset( $settings->heading_mobile_alignment ) && ! empty( $settings->heading_mobile_alignment ) ) {
			$settings->heading_alignment_responsive = $settings->heading_mobile_alignment;
			unset( $settings->heading_mobile_alignment );
		}

		// Separator margin.
		if ( isset( $settings->separator_heading_top_margin ) ) {
			$settings->separator_margin_top = $settings->separator_heading_top_margin;
			unset( $settings->separator_heading_top_margin );
		}
		if ( isset( $settings->separator_heading_bottom_margin ) ) {
			$settings->separator_margin_bottom = $settings->separator_heading_bottom_margin;
			unset( $settings->separator_heading_bottom_margin );
		}

		return $settings;
	}

	public function is_html_in_text() {
		$title_1 = $this->settings->heading_title;
		$title_2 = 'yes' == $this->settings->dual_heading && ! empty( $this->settings->heading_title2 ) ? $this->settings->heading_title2 : '';

		if ( preg_match("/<[^<]+>/", $title_1 ) ) {
			return true;
		}
		if ( preg_match("/<[^<]+>/", $title_2 ) ) {
			return true;
		}

		return false;
	}

	public function has_text_padding() {
		$has_padding = false;

		$dimensions = array( 'top', 'right', 'bottom', 'left' );

		$settings = $this->settings;

		foreach ( $dimensions as $dimension ) {
			$padding_key = 'heading_padding_' . $dimension;
			if ( isset( $settings->{$padding_key} ) && ! empty( $settings->{$padding_key} ) ) {
				$has_padding = true;
			}
		}

		if ( 'yes' == $this->settings->dual_heading && ! empty( $this->settings->heading_title2 ) ) {
			foreach ( $dimensions as $dimension ) {
				$padding_key = 'heading2_padding_' . $dimension;
				if ( isset( $settings->{$padding_key} ) && ! empty( $settings->{$padding_key} ) ) {
					$has_padding = true;
				}
			}
		}

		return $has_padding;
	}

	public function maybe_text_inline_block() {
		if ( 'block' !== $this->settings->heading_style && ( $this->is_html_in_text() || $this->has_text_padding() ) ) {
			return true;
		}

		return false;
	}

	public function render_separator() {
		$settings  = $this->settings;
		$separator = $settings->heading_separator;

		if ( in_array( $settings->heading_separator, array( 'no_spacer', 'inline', 'inline-prefix' ) ) ) {
			return;
		}
		?>

		<div class="pp-heading-separator <?php echo $settings->heading_separator; ?> pp-<?php echo $settings->heading_alignment; ?>">
		<?php
		if ( 'line_only' === $separator ) {
			$this->render_separator_line_only();
		}
		if ( 'line_with_icon' === $separator ) {
			$this->render_separator_line_with_icon();
		}
		if ( 'icon_only' === $separator ) {
			$this->render_separator_icon_only();
		}
		?>
		</div>
		<?php
	}

	public function render_separator_line_only() {
		?>
		<span class="pp-separator-line"></span>
		<?php
	}

	public function render_separator_line_with_icon() {
		?>
		<div class="pp-heading-separator-wrapper">
			<div class="pp-heading-separator-align">
				<div class="pp-heading-separator-icon">
					<?php $this->render_separator_icon(); ?>
				</div>
			</div>
		</div>
		<?php
	}

	public function render_separator_icon_only() {
		?>
		<div class="pp-heading-separator-wrapper">
			<div class="pp-heading-separator-align">
				<div class="pp-heading-separator-icon">
					<?php $this->render_separator_icon(); ?>
				</div>
			</div>
		</div>
		<?php
	}

	public function render_separator_icon() {
		$settings = $this->settings;

		if ( 'font_icon_select' == $settings->heading_icon_select && ! empty( $settings->heading_font_icon_select ) ) {
			$this->render_separator_custom_icon();
		}

		if ( 'custom_icon_select' == $settings->heading_icon_select && ! empty( $settings->heading_custom_icon_select ) ) {
			$this->render_separator_custom_image();
		}
	}

	public function render_separator_custom_icon() {
		$settings = $this->settings;

		?>
		<i class="<?php echo $settings->heading_font_icon_select; ?> pp-separator-font-icon"></i>
		<?php
	}

	public function render_separator_custom_image() {
		$settings = $this->settings;
		$alt      = $settings->heading_title;

		if ( isset( $settings->dual_heading ) && 'yes' == $settings->dual_heading && ! empty( $settings->heading_title2 ) ) { 
			$alt .= ' ' . $settings->heading_title2;
		}

		?>
		<img class="heading-icon-image" src="<?php echo $settings->heading_custom_icon_select_src; ?>" alt="<?php echo $alt; ?>" />
		<?php
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'PPHeadingModule',
	array(
		'heading_info_tab'   => array( // Tab
			'title'         => __('General', 'bb-powerpack'), // Tab title
			'sections'      => array( // Tab Sections
				'heading_prefix' => array(
					'title'  => __( 'Prefix', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'prefix_tag' => array(
							'type'          => 'select',
							'label'         => __('Tag', 'bb-powerpack'),
							'default'       => 'div',
							'options'       => array(
								'h1'            => 'H1',
								'h2'            => 'H2',
								'h3'            => 'H3',
								'h4'            => 'H4',
								'h5'            => 'H5',
								'h6'            => 'H6',
								'div'			=> 'div',
								'p'				=> 'p',
								'span'			=> 'span',
							)
						),
						'prefix_text' => array(
							'type'          => 'text',
							'label'         => __('Text', 'bb-powerpack'),
							'connections'   => array( 'string', 'html', 'url' ),
							'preview'       => array(
								'type'     => 'text',
								'selector' => '.pp-heading-prefix'
							),
						),
					),
				),
				'heading_section'       => array( // Section
					'title'        => __('Heading', 'bb-powerpack'), // Section Title
					'fields'       => array( // Section Fields
						'heading_tag'    => array(
							'type'          => 'select',
							'label'         => __('Tag', 'bb-powerpack'),
							'default'       => 'h2',
							'options'       => array(
								'h1'            => 'H1',
								'h2'            => 'H2',
								'h3'            => 'H3',
								'h4'            => 'H4',
								'h5'            => 'H5',
								'h6'            => 'H6',
								'div'			=> 'div',
								'p'				=> 'p',
								'span'			=> 'span',
							)
						),
						'heading_title'   => array(
							'type'          => 'text',
							'label'         => __('Title', 'bb-powerpack'),
							'default'       => __('Title', 'bb-powerpack'),
							'connections'   => array( 'string', 'html', 'url' ),
							'preview'       => array(
								'type'     => 'text',
								'selector' => '.pp-primary-title'
							),
						),
						'dual_heading'  => array(
							'type'          => 'pp-switch',
							'label'         => __('Dual Heading?', 'bb-powerpack'),
							'default'       => 'no',
							'options'       => array(
								'yes'           => __('Yes', 'bb-powerpack'),
								'no'            => __('No', 'bb-powerpack')
							),
							'toggle'        => array(
								'yes'           => array(
									'sections'      => array('title2_typography', 'heading2_style'),
									'fields'        => array('heading_title2', 'heading_style', 'heading2_tablet_font_size', 'heading2_tablet_line_height_n', 'heading2_mobile_font_size', 'heading2_mobile_line_height_n')
								)
							),
							'help'  => __('This option allows you to create dual color heading or dual font heading.', 'bb-powerpack')
						),
						'heading_title2'   => array(
							'type'          => 'text',
							'label'         => __('Secondary Title', 'bb-powerpack'),
							'class'         => '',
							'default'       => __('Secondary Title', 'bb-powerpack'),
							'connections'   => array( 'string', 'html', 'url' ),
							'preview'       => array(
								'type'     => 'text',
								'selector' => '.pp-secondary-title'
							),
						),
						'heading_style'     => array(
							'type'              => 'pp-switch',
							'label'             => __('Style', 'bb-powerpack'),
							'default'           => 'inline-block',
							'options'           => array(
								'inline-block'      => __('Inline', 'bb-powerpack'),
								'block'             => __('Stacked', 'bb-powerpack')
							),
						),
						'heading_alignment'     => array(
							'type'       => 'align',
							'label'      => __('Alignment', 'bb-powerpack'),
							'default'    => 'center',
							'responsive' => true
						),
					)
				),
				'heading_link'	=> array(
					'title'			=> __('Link', 'bb-powerpack'),
					'fields'		=> array(
						'enable_link'   => array(
							'type'          => 'pp-switch',
							'label'         => __('Enable Link', 'bb-powerpack'),
							'default'       => 'yes',
							'options'       => array(
								'yes'           => __('Yes', 'bb-powerpack'),
								'no'            => __('No', 'bb-powerpack')
							),
							'toggle'        => array(
								'yes'           => array(
									'fields'        => array('heading_link')
								)
							)
						),
						'heading_link'          => array(
							'type'          => 'link',
							'label'         => __('Link', 'bb-powerpack'),
							'connections'   => array( 'url' ),
							'show_target'	=> true,
							'show_nofollow'	=> true,
							'preview'         => array(
								'type'            => 'none'
							)
						),
					)
				),
				'heading_sub_title'         => array(
					'title'                     => __('Description', 'bb-powerpack'),
					'fields'                    => array(
						'heading_sub_title'     => array(
							'type'                  => 'editor',
							'label'                 => '',
							'default'               => __('Description', 'bb-powerpack'),
							'rows'                  => '6',
							'media_buttons'         => false,
							'connections'            => array( 'string', 'html', 'url' ),
							'preview'               => array(
								'type'                  => 'text',
								'selector'              => '.pp-heading-content .pp-sub-heading'
							)
						),
					)
				),
			)
		),
		'heading_separator'  => array(
			'title'                     => __('Separator', 'bb-powerpack'),
			'sections'                  => array(
				'heading_separator'     => array(
					'title'                     => '',
					'fields'                    => array(
						'heading_separator'     => array(
							'type'      => 'select',
							'label'     => __('Separator', 'bb-powerpack'),
							'default'     => 'left',
							'options'       => array(
								'no_spacer'			=> __('No Separator', 'bb-powerpack'),
								'inline'        	=> __('Inline with Heading', 'bb-powerpack'),
								'inline-prefix'     => __('Inline with Prefix', 'bb-powerpack'),
								'line_only'     	=> __('Line', 'bb-powerpack'),
								'icon_only'     	=> __('Icon/Image', 'bb-powerpack'),
								'line_with_icon'    => __('Line with Icon/Image', 'bb-powerpack'),
							),
							'toggle' => array(
								'inline'        => array(
									'fields'  => array('heading_line_style', 'font_title_line_space'),
									'sections' => array('heading_line_style_section'),
								),
								'inline-prefix'        => array(
									'fields'  => array('heading_line_style', 'font_title_line_space'),
									'sections' => array('heading_line_style_section'),
								),
								'line_only'      => array(
									'fields'  => array('heading_line_style', 'heading_separator_postion'),
									'sections' => array('heading_line_style_section', 'heading_separator_style_section'),
								),
								'icon_only'      => array(
									'fields'  => array('heading_separator_postion', 'font_icon_color'),
									'sections'  => array('heading_separator_style_section', 'heading_icon_image_style_section', 'heading_icon_image_settings'),
								),
								'line_with_icon'      => array(
									'fields'  => array('heading_line_style', 'font_icon_line_space', 'heading_separator_postion', 'font_icon_color'),
									'sections' => array('heading_line_style_section', 'heading_separator_style_section', 'heading_icon_image_style_section', 'heading_icon_image_settings')
								),
								'no_spacer' => array(
									'fields' => array(),
									'sections' => array(),
								)
							)
						),
						'heading_separator_postion' => array(
							'type'    => 'select',
							'label'   => __( 'Position', 'bb-powerpack' ),
							'default' => 'middle',
							'options' => array(
								'top'     => __( 'Above Heading', 'bb-powerpack' ),
								'between' => __( 'Between Dual Heading', 'bb-powerpack' ),
								'middle'  => __( 'Below Heading', 'bb-powerpack' ),
								'bottom'  => __( 'Below Description', 'bb-powerpack' ),
								'left'    => __( 'Left of Heading', 'bb-powerpack' ),
								'right'   => __( 'Right of Heading', 'bb-powerpack' ),
							),
							'help'    => __( '"Between Dual Heading" option only works when Dual Heading is enabled and styled as "Stacked".', 'bb-powerpack' ),
						),
						'heading_line_style'     => array(
							'type'      => 'pp-switch',
							'label'     => __('Line Style', 'bb-powerpack'),
							'default'     => 'solid',
							'options'       => array(
								'solid'          => __('Solid', 'bb-powerpack'),
								'dashed'         => __('Dashed', 'bb-powerpack'),
								'dotted'         => __('Dotted', 'bb-powerpack'),
								'double'         => __('Double', 'bb-powerpack'),
							)
						),
						'font_title_line_space'   => array(
							'type'          => 'unit',
							'label'         => __('Space between Line & Title', 'bb-powerpack'),
							'units'   		=> array('px'),
							'slider'		=> true,
							'responsive'	=> true,
							'default'       => '20',
						),
						'hide_separator' => array(
							'type'    => 'select',
							'label'   => __( 'Hide Separator on', 'bb-powerpack' ),
							'default' => 'none',
							'options' => array(
								'none'              => __( 'None', 'bb-powerpack' ),
								'medium'            => __( 'Medium Devices Only', 'bb-powerpack' ),
								'medium-responsive' => __( 'Medium and Small Devices', 'bb-powerpack' ),
								'responsive'  	    => __( 'Small Devices Only', 'bb-powerpack' ),
							),
						),
					)
				),
				'heading_icon_image_settings'       => array( // Section
					'title'        => __('Icon', 'bb-powerpack'), // Section Title
					'fields'       => array( // Section Fields
						'heading_icon_select'       => array(
							'type'          => 'select',
							'label'         => __('Icon Source', 'bb-powerpack'),
							'default'       => 'font_icon_select',
							'options'       => array(
								'font_icon_select'   => __('Icon Library', 'bb-powerpack'),
								'custom_icon_select' => __('Custom Image', 'bb-powerpack')
							),
							'toggle' => array(
								'font_icon_select'    => array(
									'fields'   => array('heading_font_icon_select', 'font_icon_color'),
								),
								'custom_icon_select'   => array(
									'fields'  => array('heading_custom_icon_select'),
								),
							)
						),
						'heading_font_icon_select' => array(
							'type'          => 'icon',
							'label'         => __('Icon', 'bb-powerpack')
						),
						'heading_custom_icon_select'     => array(
							'type'          => 'photo',
							'label'         => __('Custom Image', 'bb-powerpack'),
							'default'       => '',
						),
						'font_icon_line_space'   => array(
							'type'          => 'unit',
							'label'         => __('Space between Line & Icon/Image', 'bb-powerpack'),
							'units'   		=> array('px'),
							'slider'		=> true,
							'default'       => '20',
						),
					)
				),
				'heading_line_style_section'    => array( // Section
					'title'             => __('Separator Size & Color', 'bb-powerpack'), // Section Title
					'fields'            => array( // Section Fields
						'line_width'   => array(
							'type'          => 'unit',
							'label'         => __('Width', 'bb-powerpack'),
							'units'   		=> array('px', '%'),
							'slider'		=> true,
							'default'       => '100',
						),
						'line_height'       => array(
							'type'          => 'unit',
							'label'         => __('Height', 'bb-powerpack'),
							'units'   		=> array('px'),
							'slider'		=> true,
							'default'       => '1',
							'preview'       => array(
								'type'      => 'css',
								'rules'           => array(
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator.line_with_icon:before',
									'property'        => 'border-bottom-width',
									'unit'            => 'px'
								),
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator.line_with_icon:after',
									'property'        => 'border-bottom-width',
									'unit'            => 'px'
								),
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator .pp-separator-line',
									'property'        => 'border-bottom-width',
									'unit'            => 'px'
								),
								array(
									'selector'        => '.pp-heading-content .pp-heading.pp-separator-inline .heading-title span:before',
									'property'        => 'border-bottom-width',
									'unit'            => 'px'
								),
								array(
									'selector'        => '.pp-heading-content .pp-heading.pp-separator-inline .heading-title span:after',
									'property'        => 'border-bottom-width',
									'unit'            => 'px'
								),
							),
							)
						),
						'line_color'    => array(
							'type'          => 'color',
							'label'         => __('Color', 'bb-powerpack'),
							'default'       => '',
							'show_reset'    => true,
							'connections'	=> array('color'),
							'preview'         => array(
								'type'            => 'css',
								'rules'           => array(
									array(
										'selector'        => '.pp-heading-content .pp-heading-separator.line_with_icon:before',
										'property'        => 'border-color',
									),
									array(
										'selector'        => '.pp-heading-content .pp-heading-separator.line_with_icon:after',
										'property'        => 'border-color',
									),
									array(
										'selector'        => '.pp-heading-content .pp-heading-separator .pp-separator-line',
										'property'        => 'border-bottom-color',
									),
									array(
										'selector'        => '.pp-heading-content .pp-heading.pp-separator-inline .heading-title span:before',
										'property'        => 'border-color',
									),
									array(
										'selector'        => '.pp-heading-content .pp-heading.pp-separator-inline .heading-title span:after',
										'property'        => 'border-color',
									),
									array(
										'selector'        => '.pp-heading-content .pp-heading-prefix:before',
										'property'        => 'border-color',
									),
									array(
										'selector'        => '.pp-heading-content .pp-heading-prefix:after',
										'property'        => 'border-color',
									),
								),
							)
						),
					)
				),
				'heading_icon_image_style_section'    => array( // Section
					'title'             => __('Icon/Image Style', 'bb-powerpack'), // Section Title
					'fields'            => array( // Section Fields
						'font_icon_font_size'   => array(
							'type'          => 'unit',
							'label'         => __('Icon Size', 'bb-powerpack'),
							'units'   		=> array('px'),
							'slider'		=> true,
							'default'       => '16',
							'preview'       => array(
								'type'      => 'css',
								'rules'           => array(
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator .pp-heading-separator-icon i, .pp-heading-content .pp-heading-separator .pp-heading-separator-icon i:before',
									'property'        => 'font-size',
									'unit'            => 'px'
								),
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator img.heading-icon-image',
									'property'        => 'width',
									'unit'            => 'px'
								),
							),
							)
						),
						'font_icon_color'    => array(
							'type'          => 'color',
							'label'         => __('Color', 'bb-powerpack'),
							'default'       => '',
							'show_reset'    => true,
							'connections'	=> array('color'),
							'preview'         => array(
								'type'            => 'css',
								'selector'        => '.pp-heading-content .pp-heading-separator',
								'property'        => 'color'
							)
						),
						'font_icon_bg_color'    => array(
							'type'          => 'color',
							'label'         => __('Background Color', 'bb-powerpack'),
							'default'       => '',
							'show_reset'    => true,
							'show_alpha'	=> true,
							'connections'	=> array('color'),
							'preview'         => array(
								'type'            => 'css',
								'rules'           => array(
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator .pp-heading-separator-icon',
									'property'        => 'background-color',
								),
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator.icon_only span',
									'property'        => 'background-color',
								),
							),
							)
						),
						'field_separator_5'  => array(
							'type'                => 'pp-separator',
							'color'               => 'eeeeee'
						),
						'font_icon_border_width'   => array(
							'type'          => 'unit',
							'label'         => __('Border Width', 'bb-powerpack'),
							'units'   		=> array('px'),
							'slider'		=> true,
							'default'       => '0',
							'preview'       => array(
								'type'      => 'css',
								'rules'           => array(
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator .pp-heading-separator-icon',
									'property'        => 'border-width',
									'unit'            => 'px'
								),
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator.icon_only span',
									'property'        => 'border-width',
									'unit'            => 'px'
								),
							),
							)
						),
						'font_icon_border_style'     => array(
							'type'      => 'select',
							'label'     => __('Border Style', 'bb-powerpack'),
							'default'     => 'none',
							'options'       => array(
								'none'          => __('None', 'bb-powerpack'),
								'solid'          => __('Solid', 'bb-powerpack'),
								'dashed'          => __('Dashed', 'bb-powerpack'),
								'dotted'          => __('Dotted', 'bb-powerpack'),
								'double'          => __('Double', 'bb-powerpack'),
							)
						),
						'font_icon_border_color'    => array(
							'type'          => 'color',
							'label'         => __('Border Color', 'bb-powerpack'),
							'default'       => '',
							'show_reset'    => true,
							'connections'	=> array('color'),
							'preview'         => array(
								'type'            => 'css',
								'rules'           => array(
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator .pp-heading-separator-icon',
									'property'        => 'border-color',
									'unit'            => 'px'
								),
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator.icon_only span',
									'property'        => 'border-color',
								),
							),
							)
						),
						'font_icon_border_radius'   => array(
							'type'          => 'unit',
							'label'         => __('Round Corners', 'bb-powerpack'),
							'units'   		=> array('px'),
							'slider'		=> true,
							'default'       => '100',
							'preview'       => array(
								'type'      => 'css',
								'rules'           => array(
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator .pp-heading-separator-icon',
									'property'        => 'border-radius',
									'unit'            => 'px'
								),
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator.icon_only span',
									'property'        => 'border-radius',
									'unit'            => 'px'
								),
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator.icon_only img',
									'property'        => 'border-radius',
									'unit'            => 'px'
								),
								array(
									'selector'        => '.pp-heading-content .pp-heading-separator.line_with_icon img',
									'property'        => 'border-radius',
									'unit'            => 'px'
								)
							),
							)
						),
						'field_separator_6'  => array(
							'type'                => 'pp-separator',
							'color'               => 'eeeeee'
						),
						'font_icon_padding'	=> array(
							'type'		=> 'dimension',
							'label'		=> __('Padding', 'bb-powerpack'),
							'units'   	=> array('px'),
							'slider'	=> true,
							'default'	=> '0',
							'responsive'	=> true,
							'preview'       => array(
								'type'      => 'css',
								'rules'           => array(
									array(
										'selector'        => '.pp-heading-content .pp-heading-separator .pp-heading-separator-icon',
										'property'        => 'padding',
										'unit'            => 'px'
									),
									array(
										'selector'        => '.pp-heading-content .pp-heading-separator.icon_only span',
										'property'        => 'padding',
										'unit'            => 'px'
									),
								),
							)
						),
					)
				),
				'heading_separator_style_section'    => array( // Section
					'title'             => __('Margin', 'bb-powerpack'), // Section Title
					'fields'            => array( // Section Fields
						'separator_margin' => array(
							'type'  => 'dimension',
							'label' => __( 'Margin', 'bb-powerpack' ),
							'units' => array('px'),
							'slider'	 => true,
							'responsive' => true,
							'preview'       => array(
								'type'      => 'css',
								'selector'  => '.pp-heading-content .pp-heading-separator',
								'property'  => 'margin',
								'unit'      => 'px'
							)
						),
						// 'separator_heading_top_margin'   => array(
						// 	'type'          => 'unit',
						// 	'label'         => __('Margin Top', 'bb-powerpack'),
						// 	'units'   		=> array('px'),
						// 	'slider'		=> true,
						// 	'default'       => '10',
						// 	'preview'       => array(
						// 		'type'      => 'css',
						// 		'selector'  => '.pp-heading-content .pp-heading-separator',
						// 		'property'  => 'margin-top',
						// 		'unit'      => 'px'
						// 	)
						// ),
						// 'separator_heading_bottom_margin' => array(
						// 	'type'          => 'unit',
						// 	'label'         => __('Margin Bottom', 'bb-powerpack'),
						// 	'units'   		=> array('px'),
						// 	'slider'		=> true,
						// 	'default'       => '10',
						// 	'preview'       => array(
						// 		'type'      => 'css',
						// 		'selector'  => '.pp-heading-content .pp-heading-separator',
						// 		'property'  => 'margin-bottom',
						// 		'unit'      => 'px'
						// 	)
						// ),
					)
				),
			)
		),
		'heading_style_tab'  => array( // Tab
			'title'         => __('Style', 'bb-powerpack'), // Tab title
			'sections'      => array( // Tab Sections
				'prefix_style' => array(
					'title' => __( 'Prefix', 'bb-powerpack' ),
					'collapsed' => true,
					'fields' => array(
						'prefix_text_color'    => array(
							'type'          => 'color',
							'label'         => __('Color', 'bb-powerpack'),
							'default'       => '',
							'show_reset'    => true,
							'show_alpha'    => false,
							'connections'	=> array('color'),
						),
					),
				),
				'main_heading_style'       => array( // Section
					'title'     => __('Title', 'bb-powerpack'), // Section Title
					'collapsed' => true,
					'fields'    => array( // Section Fields
						'heading_color_type'	=> array(
							'type'		=> 'pp-switch',
							'label'		=> __('Color Type', 'bb-powerpack'),
							'default'	=> 'solid',
							'options'	=> array(
								'solid'		=> __('Solid', 'bb-powerpack'),
								'gradient'	=> __('Gradient', 'bb-powerpack'),
							),
							'toggle'	=> array(
								'solid'		=> array(
									'fields'	=> array( 'heading_color' )
								),
								'gradient'	=> array(
									'fields'	=> array( 'heading_gradient_setting' )
								)
							)
						),
						'heading_color'    => array(
							'type'          => 'color',
							'label'         => __('Color', 'bb-powerpack'),
							'default'       => '',
							'show_reset'    => true,
							'show_alpha'    => true,
							'connections'	=> array('color'),
							'preview'         => array(
								'type'            => 'css',
								'selector'        => '.pp-heading-content .pp-heading .heading-title span.pp-primary-title',
								'property'        => 'color'
							)
						),
						'heading_gradient_setting'	=> array(
							'type'		=> 'gradient',
							'label'		=> __('Color', 'bb-powerpack'),
							'preview'	=> array(
								'type'		=> 'css',
								'selector'  => '.pp-heading-content .pp-heading .heading-title span.pp-primary-title',
								'property'  => 'background-image'
							)
						),
						'heading_hover_color'    => array(
							'type'          => 'color',
							'label'         => __('Hover Color', 'bb-powerpack'),
							'default'       => '',
							'show_reset'    => true,
							'show_alpha'    => true,
							'connections'	=> array('color'),
						),
						'field_separator_1'  => array(
							'type'                => 'pp-separator',
							'color'               => 'eeeeee'
						),
						'heading_bg_color' => array(
							'type'          => 'color',
							'label'         => __('Background Color', 'bb-powerpack'),
							'default'       => '',
							'show_reset'    => true,
							'show_alpha'    => true,
							'connections'	=> array('color'),
							'preview'         => array(
								'type'            => 'css',
								'selector'        => '.pp-heading-content .pp-heading .heading-title span.pp-primary-title',
								'property'        => 'background-color'
							)
						),
						'field_separator_1_1'  => array(
							'type'                => 'pp-separator',
							'color'               => 'eeeeee'
						),
						'heading_border_style'  => array(
							'type'                  => 'select',
							'label'                 => __('Border Style', 'bb-powerpack'),
							'default'               => 'none',
							'options'               => array(
								'none'                	=> __('None', 'bb-powerpack'),
								'dashed'                => __('Dashed', 'bb-powerpack'),
								'dotted'                => __('Dotted', 'bb-powerpack'),
								'double'                => __('Double', 'bb-powerpack'),
								'solid'                 => __('Solid', 'bb-powerpack'),
							),
							'preview'               => array(
								'type'                  => 'css',
								'rules'                 => array(
									array(
										'selector'              => '.pp-heading-content .pp-heading .heading-title span.pp-primary-title',
										'property'              => 'border-top-style',
									),
									array(
										'selector'              => '.pp-heading-content .pp-heading .heading-title span.pp-primary-title',
										'property'              => 'border-bottom-style',
									),
									array(
										'selector'              => '.pp-heading-content .pp-heading .heading-title span.pp-primary-title',
										'property'              => 'border-left-style',
									),
									array(
										'selector'              => '.pp-heading-content .pp-heading .heading-title span.pp-primary-title',
										'property'              => 'border-right-style',
									)
								)
							)
						),
						'heading_border'	=> array(
							'type'				=> 'dimension',
							'label'				=> __('Border Width', 'bb-powerpack'),
							'units'				=> array('px'),
							'slider'			=> true,
							'responsive'		=> false,
						),
						'heading_border_color' => array(
							'type'          => 'color',
							'label'         => __('Border Color', 'bb-powerpack'),
							'default'       => '000000',
							'show_reset'    => true,
							'connections'	=> array('color'),
							'preview'         => array(
								'type'            => 'css',
								'selector'        => '.pp-heading-content .pp-heading .heading-title span.pp-primary-title',
								'property'        => 'border-color'
							)
						),
						'field_separator_2'  => array(
							'type'                => 'pp-separator',
							'color'               => 'eeeeee'
						),
						'heading_padding'	=> array(
							'type'				=> 'dimension',
							'label'				=> __('Padding', 'bb-powerpack'),
							'units'				=> array('px'),
							'slider'			=> true,
							'responsive'		=> true,
							'preview'			=> array(
								'type'				=> 'css',
								'selector'			=> '.pp-heading-content .pp-heading .heading-title span.pp-primary-title',
								'property'			=> 'padding',
								'unit'				=> 'px'
							)
						),
						'heading_top_margin'   => array(
							'type'          => 'unit',
							'label'         => __('Margin Top', 'bb-powerpack'),
							'units'			=> array('px'),
							'slider'		=> true,
							'default'       => '10',
							'preview'       => array(
								'type'      => 'css',
								'selector'  => '.pp-heading-content .pp-heading .heading-title',
								'property'  => 'margin-top',
								'unit'      => 'px'
							)
						),
						'heading_bottom_margin'   => array(
							'type'          => 'unit',
							'label'         => __('Margin Bottom', 'bb-powerpack'),
							'units'			=> array('px'),
							'slider'		=> true,
							'default'       => '10',
							'preview'       => array(
								'type'      => 'css',
								'selector'  => '.pp-heading-content .pp-heading .heading-title',
								'property'  => 'margin-bottom',
								'unit'      => 'px'
							)
						),
					),
				),
				'heading2_style'	=> array( // Section
					'title'         	=> __('Secondary Title', 'bb-powerpack'), // Section Title
					'collapsed'			=> true,
					'fields'        	=> array( // Section Fields
						'heading2_color_type'	=> array(
							'type'		=> 'pp-switch',
							'label'		=> __('Color Type', 'bb-powerpack'),
							'default'	=> 'solid',
							'options'	=> array(
								'solid'		=> __('Solid', 'bb-powerpack'),
								'gradient'	=> __('Gradient', 'bb-powerpack'),
							),
							'toggle'	=> array(
								'solid'		=> array(
									'fields'	=> array( 'heading2_color' )
								),
								'gradient'	=> array(
									'fields'	=> array( 'heading2_gradient_setting' )
								)
							)
						),
						'heading2_color'    => array(
							'type'          => 'color',
							'label'         => __('Color', 'bb-powerpack'),
							'default'       => '',
							'show_reset'    => true,
							'show_alpha'    => true,
							'connections'	=> array('color'),
							'preview'         => array(
								'type'            => 'css',
								'selector'        => '.pp-heading-content .pp-heading span.pp-secondary-title',
								'property'        => 'color'
							)
						),
						'heading2_gradient_setting'	=> array(
							'type'		=> 'gradient',
							'label'		=> __('Color', 'bb-powerpack'),
							'preview'	=> array(
								'type'		=> 'css',
								'selector'  => '.pp-heading-content .pp-heading .heading-title span.pp-secondary-title',
								'property'  => 'background-image'
							)
						),
						'heading2_hover_color'    => array(
							'type'          => 'color',
							'label'         => __('Hover Color', 'bb-powerpack'),
							'default'       => '',
							'show_reset'    => true,
							'show_alpha'    => true,
							'connections'	=> array('color'),
						),
						'field_separator_2_1'  => array(
							'type'                => 'pp-separator',
							'color'               => 'eeeeee'
						),
						'heading2_bg_color' => array(
							'type'          => 'color',
							'label'         => __('Background Color', 'bb-powerpack'),
							'default'       => '',
							'show_reset'    => true,
							'show_alpha'    => true,
							'connections'	=> array('color'),
							'preview'         => array(
								'type'            => 'css',
								'selector'        => '.pp-heading-content .pp-heading span.pp-secondary-title',
								'property'        => 'background-color'
							)
						),
						'field_separator_3'  => array(
							'type'                => 'pp-separator',
							'color'               => 'eeeeee'
						),
						'heading2_border_style'  => array(
							'type'                  => 'select',
							'label'                 => __('Border Style', 'bb-powerpack'),
							'default'               => 'none',
							'options'               => array(
								'none'                => __('None', 'bb-powerpack'),
								'dashed'                => __('Dashed', 'bb-powerpack'),
								'dotted'                => __('Dotted', 'bb-powerpack'),
								'double'                => __('Double', 'bb-powerpack'),
								'solid'                 => __('Solid', 'bb-powerpack'),
							),
						),
						'heading2_border'   => array(
							'type'				=> 'dimension',
							'label'				=> __('Border Width', 'bb-powerpack'),
							'units'				=> array('px'),
							'slider'			=> true,
							'responsive'		=> false,
						),
						'heading2_border_color' => array(
							'type'          => 'color',
							'label'         => __('Border Color', 'bb-powerpack'),
							'default'       => '000000',
							'show_reset'    => true,
							'connections'	=> array('color'),
							'preview'         => array(
								'type'            => 'css',
								'selector'        => '.pp-heading-content .pp-heading .heading-title span.pp-secondary-title',
								'property'        => 'border-color'
							)
						),
						'field_separator_4'  => array(
							'type'                => 'pp-separator',
							'color'               => 'eeeeee'
						),
						'heading2_padding'   => array(
							'type'				=> 'dimension',
							'label'				=> __('Padding', 'bb-powerpack'),
							'units'				=> array('px'),
							'slider'			=> true,
							'responsive'		=> true,
							'preview'			=> array(
								'type'				=> 'css',
								'selector'			=> '.pp-heading-content .pp-heading .heading-title span.pp-secondary-title',
								'property'			=> 'padding',
								'unit'				=> 'px'
							)
						),
						'heading2_left_margin'   => array(
							'type'          => 'unit',
							'label'         => __('Margin Left', 'bb-powerpack'),
							'units'			=> array('px'),
							'slider'		=> true,
							'default'       => '0',
							'preview'       => array(
								'type'      => 'css',
								'selector'  => '.pp-heading-content .pp-heading .heading-title span.pp-secondary-title',
								'property'  => 'margin-left',
								'unit'      => 'px'
							)
						),
					)
				),
				'sub_heading_style'       => array( // Section
					'title'         => __('Description', 'bb-powerpack'), // Section Title
					'collapsed'		=> true,
					'fields'        => array( // Section Fields
						'sub_heading_color'    => array(
							'type'          => 'color',
							'label'         => __('Color', 'bb-powerpack'),
							'default'       => '',
							'show_reset'    => true,
							'connections'	=> array('color'),
							'preview'         => array(
								'type'            => 'css',
								'selector'        => '.pp-heading-content .pp-sub-heading',
								'property'        => 'color'
							)
						),
						'sub_heading_top_margin'   => array(
							'type'          => 'unit',
							'label'         => __('Margin Top', 'bb-powerpack'),
							'units'			=> array('px'),
							'slider'		=> true,
							'default'       => '0',
							'preview'       => array(
								'type'      => 'css',
								'selector'  => '.pp-heading-content .pp-sub-heading',
								'property'  => 'margin-top',
								'unit'      => 'px'
							)
						),
						'sub_heading_bottom_margin'   => array(
							'type'          => 'unit',
							'label'         => __('Margin Bottom', 'bb-powerpack'),
							'units'			=> array('px'),
							'slider'		=> true,
							'default'       => '0',
							'preview'       => array(
								'type'      => 'css',
								'selector'  => '.pp-heading-content .pp-sub-heading',
								'property'  => 'margin-bottom',
								'unit'      => 'px'
							)
						),
					)
				),
			)
		),
		'heading_typography' => array(
			'title'                 => __('Typography', 'bb-powerpack'),
			'sections'              => array(
				'prefix_typography'      => array(
					'title'                 => __('Prefix', 'bb-powerpack'),
					'fields'                => array(
						'prefix_typography'		=> array(
							'type'					=> 'typography',
							'label'					=> __('Typography', 'bb-powerpack'),
							'responsive'  			=> true,
							'preview'				=> array(
								'type'					=> 'css',
								'selector'				=> '.pp-heading-content .pp-heading-prefix'
							)
						),
					)
				),
				'title_typography'      => array(
					'title'                 => __('Title', 'bb-powerpack'),
					'fields'                => array(
						'title_typography'		=> array(
							'type'					=> 'typography',
							'label'					=> __('Typography', 'bb-powerpack'),
							'responsive'  			=> true,
							'preview'				=> array(
								'type'					=> 'css',
								'selector'				=> '.pp-heading-content .pp-heading .heading-title'
							)
						),
					)
				),
				'title2_typography'      => array(
					'title'                 => __('Secondary Title', 'bb-powerpack'),
					'fields'                => array(
						'title2_typography'		=> array(
							'type'					=> 'typography',
							'label'					=> __('Typography', 'bb-powerpack'),
							'responsive'  			=> true,
							'preview'				=> array(
								'type'					=> 'css',
								'selector'				=> '.pp-heading-content .pp-heading .heading-title span.pp-secondary-title'
							)
						),  
					)
				),
				'sub_heading_style'       => array( // Section
					'title'         => __('Description', 'bb-powerpack'), // Section Title
					'fields'        => array( // Section Fields
						'desc_typography'	=> array(
							'type'					=> 'typography',
							'label'					=> __('Typography', 'bb-powerpack'),
							'responsive'  			=> true,
							'preview'				=> array(
								'type'					=> 'css',
								'selector'				=> '.pp-heading-content .pp-sub-heading, .pp-heading-content .pp-sub-heading p'
							)
						),
					)
				)
			)
		),
	)
);
