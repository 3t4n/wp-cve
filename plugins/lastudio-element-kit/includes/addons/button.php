<?php

/**
 * Class: LaStudioKit_Button
 * Name: Button
 * Slug: lakit-button
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Advanced_Carousel Widget
 */
class LaStudioKit_Button extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'lastudio-kit-base' );
	    }
    }

    public function get_name() {
        return 'lakit-button';
    }

    protected function get_widget_title() {
        return esc_html__( 'Button', 'lastudio-kit');
    }

    public function get_icon() {
        return 'lastudio-kit-icon-button';
    }

    protected function register_controls() {
        $this->_start_controls_section(
            'section_button',
            [
                'label' => __( 'Button', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'button_type',
            [
                'label' => __( 'Type', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __( 'Default', 'lastudio-kit' ),
                    'info' => __( 'Info', 'lastudio-kit' ),
                    'success' => __( 'Success', 'lastudio-kit' ),
                    'warning' => __( 'Warning', 'lastudio-kit' ),
                    'danger' => __( 'Danger', 'lastudio-kit' ),
                ],
                'prefix_class' => 'elementor-button-',
            ]
        );

        $this->_add_control(
            'text',
            [
                'label' => __( 'Text', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __( 'Click here', 'lastudio-kit' ),
                'placeholder' => __( 'Click here', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'link',
            [
                'label' => __( 'Link', 'lastudio-kit' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __( 'https://your-link.com', 'lastudio-kit' ),
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->_add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left'    => [
                        'title' => __( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'prefix_class' => 'elementor%s-align-',
                'default' => '',
            ]
        );

        $this->_add_control(
            'size',
            [
                'label' => __( 'Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'sm',
                'options' => [
                    'xs' => __( 'Extra Small', 'lastudio-kit' ),
                    'sm' => __( 'Small', 'lastudio-kit' ),
                    'md' => __( 'Medium', 'lastudio-kit' ),
                    'lg' => __( 'Large', 'lastudio-kit' ),
                    'xl' => __( 'Extra Large', 'lastudio-kit' ),
                ],
                'style_transfer' => true,
            ]
        );

        $this->_add_control(
            'selected_icon',
            [
                'label' => __( 'Icon', 'lastudio-kit' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $this->_add_control(
            'icon_align',
            [
                'label' => __( 'Icon Position', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Before', 'lastudio-kit' ),
                    'right' => __( 'After', 'lastudio-kit' ),
                    'top' => __( 'Top', 'lastudio-kit' ),
                    'bottom' => __( 'Bottom', 'lastudio-kit' ),
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->_add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'icon_indent',
            [
                'label' => __( 'Icon Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-button-content-wrapper' => 'gap: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->_add_responsive_control(
            'icon_vert_spacing',
            [
                'label' => __( 'Vertical Icon Spacing', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range' => [
                    'px' => array(
                        'min' => -30,
                        'max' => 30,
                    ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-button-icon' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->_add_control(
            'view',
            [
                'label' => __( 'View', 'lastudio-kit' ),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->_add_control(
            'button_css_id',
            [
                'label' => __( 'Button ID', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'title' => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'lastudio-kit' ),
                'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'lastudio-kit' ),
                'separator' => 'before',

            ]
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_style',
            [
                'label' => __( 'Button', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->_add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->_add_responsive_control(
            'text_padding',
            [
                'label' => __( 'Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_start_controls_tabs( 'tabs_button_style' );

        $this->_start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'button_text_color',
            [
                'label' => __( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'icon_color',
            [
                'label' => __( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-button-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'label' => __( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .elementor-button',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ]
                ],
            ]
        );

        $this->_add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            \LaStudioKitExtensions\Elementor\Controls\Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'button_box_shadow',
                'selector'  => '{{WRAPPER}} .elementor-button'
            ]
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'hover_color',
            [
                'label' => __( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'hover_icon_color',
            [
                'label' => __( 'Icon Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover .elementor-button-icon, {{WRAPPER}} .elementor-button:focus .elementor-button-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );


        $this->_add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_background_hover',
                'label' => __( 'Background', 'lastudio-kit' ),
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                ],
            ]
        );

        $this->_add_control(
            'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'border_radius_hover',
            [
                'label' => __( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_group_control(
            \LaStudioKitExtensions\Elementor\Controls\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_hover',
                'selector' => '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus',
            ]
        );

        $this->_add_control(
            'hover_animation',
            [
                'label' => __( 'Hover Animation', 'lastudio-kit' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_end_controls_section();
    }

    /**
     * Render button widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );

        $btn_tag = 'button';

        if ( ! empty( $settings['link']['url'] ) ) {

            $tmp_url = $settings['link']['url'];
            if(false !== strpos($tmp_url, '{meta:')){
	            $tmp_url = str_replace(['{meta:', '}'], '', $tmp_url);
	            if(!empty($tmp_url)){
	                global $post;
	                if( $post instanceof \WP_Post ){
		                $new_url_val = get_post_meta($post->ID, $tmp_url, true);
		                if(!empty($new_url_val)){
			                $settings['link']['url'] = $new_url_val;
                        }
                    }
                }
            }
            $btn_tag = 'a';
            $this->add_link_attributes( 'button', $settings['link'] );
            $this->add_render_attribute( 'button', 'class', 'elementor-button-link' );
        }

        $this->add_render_attribute( 'button', 'class', 'elementor-button' );
        $this->add_render_attribute( 'button', 'class', 'elementor-btn-align-icon-' . $settings['icon_align'] );
        $this->add_render_attribute( 'button', 'role', 'button' );

        if ( ! empty( $settings['button_css_id'] ) ) {
            $this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
        }

        if ( ! empty( $settings['size'] ) ) {
            $this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
        }

        if ( $settings['hover_animation'] ) {
            $this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
        }

        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <<?php echo $btn_tag; ?> <?php echo $this->get_render_attribute_string( 'button' ); ?>>
                <?php $this->render_text(); ?>
            </<?php echo $btn_tag; ?>>
        </div>
        <?php
    }


    /**
     * Render button widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 2.9.0
     * @access protected
     */
    protected function content_template() {
        ?>
        <#
        view.addRenderAttribute( 'text', 'class', 'elementor-button-text' );
        view.addInlineEditingAttributes( 'text', 'none' );
        var iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
        migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );
        var elm_Tags = settings.link.url ? 'a' : 'button'
        #>
        <div class="elementor-button-wrapper">
            <{{{elm_Tags}}} id="{{ settings.button_css_id }}" class="elementor-button elementor-btn-align-icon-{{ settings.icon_align }} elementor-size-{{ settings.size }} elementor-animation-{{ settings.hover_animation }}" href="{{ settings.link.url }}" role="button">
				<span class="elementor-button-content-wrapper">
					<# if ( settings.icon || settings.selected_icon ) { #>
					<span class="elementor-button-icon">
						<# if ( ( migrated || ! settings.icon ) && iconHTML.rendered ) { #>
							{{{ iconHTML.value }}}
						<# } else { #>
							<i class="{{ settings.icon }}" aria-hidden="true"></i>
						<# } #>
					</span>
					<# } #>
					<span {{{ view.getRenderAttributeString( 'text' ) }}}>{{{ settings.text }}}</span>
                </span>
            </{{{elm_Tags}}}>
        </div>
        <?php
    }

    /**
     * Render button text.
     *
     * Render button widget text.
     *
     * @access protected
     */
    protected function render_text() {
        $settings = $this->get_settings_for_display();

        $migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
        $is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

        if ( ! $is_new && empty( $settings['icon_align'] ) ) {
            //old default
            $settings['icon_align'] = $this->get_settings( 'icon_align' );
        }

        $this->add_render_attribute( [
            'content-wrapper' => [
                'class' => 'elementor-button-content-wrapper',
            ],
            'icon-align' => [
                'class' => [
                    'elementor-button-icon'
                ],
            ],
            'text' => [
                'class' => 'elementor-button-text',
            ],
        ] );

        $this->add_inline_editing_attributes( 'text', 'none' );
        ?>
        <span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
                <span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
				<?php if ( $is_new || $migrated ) :
                    Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                else : ?>
                    <i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
                <?php endif; ?>
			</span>
            <?php endif; ?>
			<span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['text']; ?></span>
		</span>
        <?php
    }

    public function on_import( $element ) {
        return Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon' );
    }
}