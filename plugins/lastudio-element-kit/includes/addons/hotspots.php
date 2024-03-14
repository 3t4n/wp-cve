<?php
/**
 * Class: LaStudioKit_Hotspots
 * Name: Hotspots
 * Slug: lakit-hotspots
 */

namespace Elementor;

if ( ! defined( 'WPINC' ) ) {
  die;
}

class LaStudioKit_Hotspots extends Widget_Image {

  public function __construct( $data = [], $args = null ) {
    parent::__construct( $data, $args );

    $this->enqueue_addon_resources();
  }

  protected function enqueue_addon_resources() {
    if ( ! lastudio_kit_settings()->is_combine_js_css() ) {
      wp_register_script( $this->get_name(), lastudio_kit()->plugin_url( 'assets/js/addons/hotspots.min.js' ), [ 'elementor-frontend' ], lastudio_kit()->get_version(), true );
      $this->add_script_depends( $this->get_name() );
      if ( !lastudio_kit()->is_optimized_css_mode() ) {
        wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/hotspots.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
        $this->add_style_depends( $this->get_name() );
      }
    }
  }

  public function get_widget_css_config( $widget_name ) {
    $file_url  = lastudio_kit()->plugin_url( 'assets/css/addons/hotspots.min.css' );
    $file_path = lastudio_kit()->plugin_path( 'assets/css/addons/hotspots.min.css' );

    return [
      'key'       => $widget_name,
      'version'   => lastudio_kit()->get_version( false ),
      'file_path' => $file_path,
      'data'      => [
        'file_url' => $file_url
      ]
    ];
  }

  public function get_name() {
    return 'lakit-hotspots';
  }

  public function get_title() {
    return 'LaStudioKit ' . __( 'Hotspots', 'lastudio-kit' );
  }

  public function get_icon() {
    return 'eicon-image-hotspot';
  }

  public function get_keywords() {
    return [ 'image', 'tooltip', 'CTA', 'dot', 'hotspot' ];
  }

  protected function register_controls() {
    parent::register_controls();

    /**
     * Image Section
     */

    $this->remove_control( 'caption_source' );
    $this->remove_control( 'caption' );
    $this->remove_control( 'link_to' );
    $this->remove_control( 'link' );
    $this->remove_control( 'open_lightbox' );

    /**
     * Section Hotspot
     */
    $this->start_controls_section(
      'hotspot_section',
      [
        'label' => esc_html__( 'Hotspot', 'lastudio-kit' ),
      ]
    );

    $repeater = new Repeater();

    $repeater->start_controls_tabs( 'hotspot_repeater' );

    $repeater->start_controls_tab(
      'hotspot_content_tab',
      [
        'label' => esc_html__( 'Content', 'lastudio-kit' ),
      ]
    );

    $repeater->add_control(
      'hotspot_content_type',
      array(
        'label'   => esc_html__( 'Content Type', 'lastudio-kit' ),
        'type'    => Controls_Manager::SELECT,
        'default' => 'default',
        'options' => array(
          'default'  => esc_html__( 'Default', 'lastudio-kit' ),
          'template' => esc_html__( 'Product', 'lastudio-kit' ),
        ),
      )
    );

    $repeater->add_control(
      'hotspot_label',
      [
        'label'   => esc_html__( 'Label', 'lastudio-kit' ),
        'type'    => Controls_Manager::TEXT,
        'default' => '',
        'dynamic' => [
          'active' => true,
        ],
      ]
    );

    $repeater->add_control(
      'hotspot_link',
      [
        'label'       => esc_html__( 'Link', 'lastudio-kit' ),
        'type'        => Controls_Manager::URL,
        'dynamic'     => [
          'active' => true,
        ],
        'placeholder' => esc_html__( 'https://your-link.com', 'lastudio-kit' ),
        'condition'   => [
          'hotspot_content_type' => 'default'
        ]
      ]
    );

    $repeater->add_control(
      'hotspot_icon',
      [
        'label'       => esc_html__( 'Icon', 'lastudio-kit' ),
        'type'        => Controls_Manager::ICONS,
        'skin'        => 'inline',
        'label_block' => false,
      ]
    );

    $repeater->add_control(
      'hotspot_icon_position',
      [
        'label'                => esc_html__( 'Icon Position', 'lastudio-kit' ),
        'type'                 => Controls_Manager::CHOOSE,
        'options'              => [
          'start' => [
            'title' => esc_html__( 'Icon Start', 'lastudio-kit' ),
            'icon'  => 'eicon-h-align-left',
          ],
          'end'   => [
            'title' => esc_html__( 'Icon End', 'lastudio-kit' ),
            'icon'  => 'eicon-h-align-right',
          ],
        ],
        'selectors_dictionary' => [
          'start' => 'grid-column: 1;',
          'end'   => 'grid-column: 2;',
        ],
        'condition'            => [
          'hotspot_icon[value]!'  => '',
          'hotspot_label[value]!' => '',
        ],
        'selectors'            => [
          '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-hotspot__icon' => '{{VALUE}}',
        ],
        'default'              => 'start',
      ]
    );

    $repeater->add_control(
      'hotspot_icon_spacing',
      [
        'label'     => esc_html__( 'Icon Spacing', 'lastudio-kit' ),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 100,
          ],
        ],
        'default'   => [
          'size' => '5',
          'unit' => 'px',
        ],
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-hotspot__button' =>
            'grid-gap: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          'hotspot_icon[value]!'  => '',
          'hotspot_label[value]!' => '',
        ],
      ]
    );

    $repeater->add_control(
      'hotspot_custom_size',
      [
        'label'       => esc_html__( 'Custom Hotspot Size', 'lastudio-kit' ),
        'type'        => Controls_Manager::SWITCHER,
        'label_off'   => esc_html__( 'Off', 'lastudio-kit' ),
        'label_on'    => esc_html__( 'On', 'lastudio-kit' ),
        'default'     => 'no',
        'description' => esc_html__( 'Set custom Hotspot size that will only affect this specific hotspot.', 'lastudio-kit' ),
        'condition'   => array(
          'hotspot_content_type' => 'default',
        ),
      ]
    );

    $repeater->add_control( 'hotspot_width',
      [
        'label'      => esc_html__( 'Min Width', 'lastudio-kit' ),
        'type'       => Controls_Manager::SLIDER,
        'range'      => [
          'px' => [
            'min'  => 0,
            'max'  => 1000,
            'step' => 1,
          ],
        ],
        'size_units' => [ 'px' ],
        'selectors'  => [
          '{{WRAPPER}} {{CURRENT_ITEM}}' => '--hotspot-min-width: {{SIZE}}{{UNIT}}',
        ],
        'condition'  => [
          'hotspot_custom_size' => 'yes',
        ],
      ]
    );

    $repeater->add_control(
      'hotspot_height',
      [
        'label'      => esc_html__( 'Min Height', 'lastudio-kit' ),
        'type'       => Controls_Manager::SLIDER,
        'range'      => [
          'px' => [
            'min'  => 0,
            'max'  => 1000,
            'step' => 1,
          ],
        ],
        'size_units' => [ 'px' ],
        'selectors'  => [
          '{{WRAPPER}} {{CURRENT_ITEM}}' => '--hotspot-min-height: {{SIZE}}{{UNIT}}',
        ],
        'condition'  => [
          'hotspot_custom_size' => 'yes',
        ],
      ]
    );

      $repeater->add_control(
          'hotspot_tooltip_title',
          [
              'label'   => esc_html__( 'Tooltip Content Title', 'lastudio-kit' ),
              'type'    => Controls_Manager::TEXT,
              'default' => '',
              'dynamic' => [
                  'active' => true,
              ],
              'label_block' => true,
              'condition'   => array(
                  'hotspot_content_type' => 'default',
              ),
          ]
      );
    $repeater->add_control(
      'hotspot_tooltip_content',
      [
        'render_type' => 'template',
        'label'       => esc_html__( 'Tooltip Content Description', 'lastudio-kit' ),
        'type'        => Controls_Manager::WYSIWYG,
        'default'     => esc_html__( 'Add Your Tooltip Text Here', 'lastudio-kit' ),
        'condition'   => array(
          'hotspot_content_type' => 'default',
        ),
      ]
    );
      $repeater->add_control(
          'hotspot_tooltip_image',
          [
              'label' => esc_html__( 'Tooltip Content Image', 'elementor' ),
              'type' => Controls_Manager::MEDIA,
              'dynamic' => [
                  'active' => true,
              ],
              'condition'   => array(
                  'hotspot_content_type' => 'default',
              ),
          ]
      );
    $repeater->add_control(
      'product_id',
      array(
        'label'       => esc_html__( 'Choose Product', 'lastudio-kit' ),
        'label_block' => 'true',
        'type'        => 'lastudiokit-query',
        'object_type' => 'product',
        'filter_type' => 'by_id',
        'condition'   => array(
          'hotspot_content_type' => 'template',
        ),
      )
    );

    $repeater->end_controls_tab();

    $repeater->start_controls_tab(
      'hotspot_position_tab',
      [
        'label' => esc_html__( 'POSITION', 'lastudio-kit' ),
      ]
    );

    $repeater->add_control(
      'always_show',
      [
        'label'       => esc_html__( 'Always show', 'lastudio-kit' ),
        'type'        => Controls_Manager::SWITCHER,
        'label_off'   => esc_html__( 'Off', 'lastudio-kit' ),
        'label_on'    => esc_html__( 'On', 'lastudio-kit' ),
        'default'     => 'no',
        'description' => esc_html__( 'Always show tooltip', 'lastudio-kit' ),
      ]
    );

    $repeater->add_control(
      'hotspot_horizontal',
      [
        'label'   => esc_html__( 'Horizontal Orientation', 'lastudio-kit' ),
        'type'    => Controls_Manager::CHOOSE,
        'default' => is_rtl() ? 'right' : 'left',
        'options' => [
          'left'  => [
            'title' => esc_html__( 'Left', 'lastudio-kit' ),
            'icon'  => 'eicon-h-align-left',
          ],
          'right' => [
            'title' => esc_html__( 'Right', 'lastudio-kit' ),
            'icon'  => 'eicon-h-align-right',
          ],
        ],
        'toggle'  => false,
      ]
    );

    $repeater->add_responsive_control(
      'hotspot_offset_x',
      [
        'label'      => esc_html__( 'Offset', 'lastudio-kit' ),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => [ '%' ],
        'default'    => [
          'unit' => '%',
          'size' => '50',
        ],
        'selectors'  => [
          '{{WRAPPER}} {{CURRENT_ITEM}}' =>
            '{{hotspot_horizontal.VALUE}}: {{SIZE}}%; --hotspot-translate-x: {{SIZE}}%;',
        ],
      ]
    );

    $repeater->add_control(
      'hotspot_vertical',
      [
        'label'   => esc_html__( 'Vertical Orientation', 'lastudio-kit' ),
        'type'    => Controls_Manager::CHOOSE,
        'options' => [
          'top'    => [
            'title' => esc_html__( 'Top', 'lastudio-kit' ),
            'icon'  => 'eicon-v-align-top',
          ],
          'bottom' => [
            'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
            'icon'  => 'eicon-v-align-bottom',
          ],
        ],
        'default' => 'top',
        'toggle'  => false,
      ]
    );

    $repeater->add_responsive_control(
      'hotspot_offset_y',
      [
        'label'      => esc_html__( 'Offset', 'lastudio-kit' ),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => [ '%' ],
        'default'    => [
          'unit' => '%',
          'size' => '50',
        ],
        'selectors'  => [
          '{{WRAPPER}} {{CURRENT_ITEM}}' =>
            '{{hotspot_vertical.VALUE}}: {{SIZE}}%; --hotspot-translate-y: {{SIZE}}%;',
        ],
      ]
    );

    $repeater->add_control(
      'hotspot_tooltip_position',
      [
        'label'       => esc_html__( 'Custom Tooltip Properties', 'lastudio-kit' ),
        'type'        => Controls_Manager::SWITCHER,
        'label_off'   => esc_html__( 'Off', 'lastudio-kit' ),
        'label_on'    => esc_html__( 'On', 'lastudio-kit' ),
        'default'     => 'no',
        'description' => sprintf( esc_html__( 'Set custom Tooltip opening that will only affect this specific hotspot.', 'lastudio-kit' ), '<code>|</code>' ),
      ]
    );

    $repeater->add_control(
      'hotspot_heading',
      [
        'label'     => esc_html__( 'Box', 'lastudio-kit' ),
        'type'      => Controls_Manager::HEADING,
        'condition' => [
          'hotspot_tooltip_position' => 'yes',
        ],
      ]
    );

    $repeater->add_responsive_control(
      'hotspot_position',
      [
        'label'                => esc_html__( 'Position', 'lastudio-kit' ),
        'type'                 => Controls_Manager::CHOOSE,
        'options'              => [
          'right'        => [
            'title' => esc_html__( 'Left', 'lastudio-kit' ),
            'icon'  => 'eicon-h-align-left',
          ],
          'bottom'       => [
            'title' => esc_html__( 'Top', 'lastudio-kit' ),
            'icon'  => 'eicon-v-align-top',
          ],
          'left'         => [
            'title' => esc_html__( 'Right', 'lastudio-kit' ),
            'icon'  => 'eicon-h-align-right',
          ],
          'top'          => [
            'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
            'icon'  => 'eicon-v-align-bottom',
          ],
          'top-left'     => [
            'title' => esc_html__( 'Top left', 'lastudio-kit' ),
            'icon'  => 'dlicon arrows-4_block-top-left',
          ],
          'top-right'    => [
            'title' => esc_html__( 'Top right', 'lastudio-kit' ),
            'icon'  => 'dlicon arrows-4_block-top-right',
          ],
          'bottom-left'  => [
            'title' => esc_html__( 'Bottom left', 'lastudio-kit' ),
            'icon'  => 'dlicon arrows-4_block-bottom-left',
          ],
          'bottom-right' => [
            'title' => esc_html__( 'Bottom right', 'lastudio-kit' ),
            'icon'  => 'dlicon arrows-4_block-bottom-right',
          ],
        ],
        'selectors_dictionary' => [
          'right'        => 'right: initial;bottom: initial;left: initial;top: initial;right: calc(100% + 15px );',
          'bottom'       => 'right: initial;bottom: initial;left: initial;top: initial;bottom: calc(100% + 15px );',
          'left'         => 'right: initial;bottom: initial;left: initial;top: initial;left: calc(100% + 15px );',
          'top'          => 'right: initial;bottom: initial;left: initial;top: initial;top: calc(100% + 15px );',
          'top-left'     => 'right: initial;bottom: 0;left: initial;top: initial;right: calc(100% + 15px );',
          'top-right'    => 'right: initial;bottom: 0;left: initial;top: initial;left: calc(100% + 15px );',
          'bottom-left'  => 'right: initial;bottom: initial;left: initial;top: 0;right: calc(100% + 15px );',
          'bottom-right' => 'right: initial;bottom: initial;left: initial;top: 0;left: calc(100% + 15px );',
        ],
        'selectors'            => [
          '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-hotspot--tooltip-position' => '{{VALUE}}',
        ],
        'condition'            => [
          'hotspot_tooltip_position' => 'yes',
        ],
        'render_type'          => 'template',
        'label_block'          => true,
      ]
    );

    $repeater->add_responsive_control(
      'hotspot_tooltip_width',
      [
        'label'      => esc_html__( 'Min Width', 'lastudio-kit' ),
        'type'       => Controls_Manager::SLIDER,
        'range'      => [
          'px' => [
            'min'  => 0,
            'max'  => 2000,
            'step' => 1,
          ],
        ],
        'size_units' => [ 'px' ],
        'selectors'  => [
          '{{WRAPPER}} {{CURRENT_ITEM}} .lakit-hotspot__tooltip' => 'min-width: {{SIZE}}{{UNIT}}',
        ],
        'condition'  => [
          'hotspot_tooltip_position' => 'yes',
        ],
      ]
    );

    $repeater->add_control(
      'hotspot_tooltip_text_wrap',
      [
        'label'     => esc_html__( 'Text Wrap', 'lastudio-kit' ),
        'type'      => Controls_Manager::SWITCHER,
        'label_off' => esc_html__( 'Off', 'lastudio-kit' ),
        'label_on'  => esc_html__( 'On', 'lastudio-kit' ),
        'selectors' => [
          '{{WRAPPER}} {{CURRENT_ITEM}}' => '--white-space: normal',
        ],
        'condition' => [
          'hotspot_tooltip_position' => 'yes',
        ],
      ]
    );

    $repeater->end_controls_tab();

    $repeater->end_controls_tabs();

    $this->add_control(
      'hotspot',
      [
        'label'              => esc_html__( 'Hotspot', 'lastudio-kit' ),
        'type'               => Controls_Manager::REPEATER,
        'fields'             => $repeater->get_controls(),
        'title_field'        => '{{{ hotspot_label }}}',
        'frontend_available' => false,
      ]
    );

    $this->add_control(
      'hotspot_animation',
      [
        'label'     => esc_html__( 'Animation', 'lastudio-kit' ),
        'type'      => Controls_Manager::SELECT,
        'options'   => [
          'lakit-hotspot--soft-beat' => esc_html__( 'Soft Beat', 'lastudio-kit' ),
          'lakit-hotspot--expand'    => esc_html__( 'Expand', 'lastudio-kit' ),
          'lakit-hotspot--overlay'   => esc_html__( 'Overlay', 'lastudio-kit' ),
          ''                         => esc_html__( 'None', 'lastudio-kit' ),
        ],
        'default'   => 'lakit-hotspot--expand',
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'hotspot_sequenced_animation',
      [
        'label'              => esc_html__( 'Sequenced Animation', 'lastudio-kit' ),
        'type'               => Controls_Manager::SWITCHER,
        'label_off'          => esc_html__( 'Off', 'lastudio-kit' ),
        'label_on'           => esc_html__( 'On', 'lastudio-kit' ),
        'default'            => 'no',
        'frontend_available' => true,
        'render_type'        => 'none',
      ]
    );

    $this->add_control(
      'hotspot_sequenced_animation_duration',
      [
        'label'              => esc_html__( 'Sequence Duration (ms)', 'lastudio-kit' ),
        'type'               => Controls_Manager::SLIDER,
        'range'              => [
          'px' => [
            'min' => 100,
            'max' => 20000,
          ],
        ],
        'condition'          => [
          'hotspot_sequenced_animation' => 'yes',
        ],
        'frontend_available' => true,
        'render_type'        => 'ui',
      ]
    );

    $this->end_controls_section();

    /**
     * Tooltip Section
     */
    $this->start_controls_section(
      'tooltip_section',
      [
        'label' => esc_html__( 'Tooltip', 'lastudio-kit' ),
      ]
    );

    $this->add_responsive_control(
      'tooltip_position',
      [
        'label'              => esc_html__( 'Position', 'lastudio-kit' ),
        'type'               => Controls_Manager::CHOOSE,
        'default'            => 'top',
        'toggle'             => false,
        'options'            => [
          'right'  => [
            'title' => esc_html__( 'Left', 'lastudio-kit' ),
            'icon'  => 'eicon-h-align-left',
          ],
          'bottom' => [
            'title' => esc_html__( 'Top', 'lastudio-kit' ),
            'icon'  => 'eicon-v-align-top',
          ],
          'left'   => [
            'title' => esc_html__( 'Right', 'lastudio-kit' ),
            'icon'  => 'eicon-h-align-right',
          ],
          'top'    => [
            'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
            'icon'  => 'eicon-v-align-bottom',
          ],
        ],
        'selectors'          => [
          '{{WRAPPER}} .lakit-hotspot--tooltip-position' => 'right: initial;bottom: initial;left: initial;top: initial;{{VALUE}}: calc(100% + 15px );',
        ],
        'frontend_available' => true,
      ]
    );

    $this->add_responsive_control(
      'tooltip_trigger',
      [
        'label'              => esc_html__( 'Trigger', 'lastudio-kit' ),
        'type'               => Controls_Manager::SELECT,
        'options'            => [
          'mouseenter' => esc_html__( 'Hover', 'lastudio-kit' ),
          'click'      => esc_html__( 'Click', 'lastudio-kit' ),
          'none'       => esc_html__( 'None', 'lastudio-kit' ),
        ],
        'default'            => 'click',
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'tooltip_animation',
      [
        'label'              => esc_html__( 'Animation', 'lastudio-kit' ),
        'type'               => Controls_Manager::SELECT,
        'options'            => [
          'lakit-hotspot--fade-in-out'     => esc_html__( 'Fade In/Out', 'lastudio-kit' ),
          'lakit-hotspot--fade-grow'       => esc_html__( 'Fade Grow', 'lastudio-kit' ),
          'lakit-hotspot--fade-direction'  => esc_html__( 'Fade By Direction', 'lastudio-kit' ),
          'lakit-hotspot--slide-direction' => esc_html__( 'Slide By Direction', 'lastudio-kit' ),
        ],
        'default'            => 'lakit-hotspot--fade-in-out',
        'placeholder'        => esc_html__( 'Enter your image caption', 'lastudio-kit' ),
        'condition'          => [
          'tooltip_trigger!' => 'none',
        ],
        'frontend_available' => true,
      ]
    );

    $this->add_control(
      'tooltip_animation_duration',
      [
        'label'     => esc_html__( 'Duration (ms)', 'lastudio-kit' ),
        'type'      => Controls_Manager::SLIDER,
        'range'     => [
          'px' => [
            'min' => 0,
            'max' => 10000,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}}' => '--tooltip-transition-duration: {{SIZE}}ms;',
        ],
        'condition' => [
          'tooltip_trigger!' => 'none',
        ],
      ]
    );

    $this->end_controls_section();

    /*************
     * Style Tab
     ************/
    /**
     * Section Style Image
     */

    $this->remove_control( 'section_style_caption' );

    $this->remove_control( 'caption_align' );

    $this->remove_control( 'text_color' );

    $this->remove_control( 'caption_background_color' );

    $this->remove_control( 'caption_typography' );

    $this->remove_control( 'caption_text_shadow' );

    $this->remove_control( 'caption_space' );

    $this->update_control( 'align', [
      'options'   => [
        'flex-start' => [
          'title' => esc_html__( 'Start', 'lastudio-kit' ),
          'icon'  => 'eicon-text-align-left',
        ],
        'center'     => [
          'title' => esc_html__( 'Center', 'lastudio-kit' ),
          'icon'  => 'eicon-text-align-center',
        ],
        'flex-end'   => [
          'title' => esc_html__( 'End', 'lastudio-kit' ),
          'icon'  => 'eicon-text-align-right',
        ],
      ],
      'selectors' => [
        '{{WRAPPER}}' => '--background-align: {{VALUE}};',
      ],
    ] );

    $this->update_control(
      'width',
      [
        'selectors' => [
          '{{WRAPPER}}' => '--container-width: {{SIZE}}{{UNIT}}; --image-width: 100%;',
        ],
      ]
    );

    $this->update_control(
      'space',
      [
        'selectors' => [
          '{{WRAPPER}}' => '--container-max-width: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->update_control(
      'height',
      [
        'selectors' => [
          '{{WRAPPER}}' => '--container-height: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->remove_control( 'hover_animation' );

    $this->update_control(
      'opacity',
      [
        'selectors' => [
          '{{WRAPPER}}' => '--opacity: {{SIZE}};',
        ],
      ]
    );

    $this->update_control(
      'opacity_hover',
      [
        'selectors' => [
          '{{WRAPPER}} .elementor-widget-container>img:hover' => '--opacity: {{SIZE}};',
        ],
      ]
    );

    /**
     * Section Style Hotspot
     */
    $this->start_controls_section(
      'section_style_hotspot',
      [
        'label' => esc_html__( 'Hotspot', 'lastudio-kit' ),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'style_hotspot_color',
      [
        'label'     => esc_html__( 'Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}}' => '--hotspot-color: {{VALUE}};',
        ],
      ]
    );
      $this->add_control(
          'style_hotspot_box_color',
          [
              'label'     => esc_html__( 'Box Color', 'lastudio-kit' ),
              'type'      => Controls_Manager::COLOR,
              'selectors' => [
                  '{{WRAPPER}}' => '--hotspot-box-color: {{VALUE}};',
              ],
          ]
      );

      $this->add_control(
      'style_hotspot_active_color',
      [
        'label'     => esc_html__( 'Active Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}}' => '--hotspot-active-color: {{VALUE}};',
        ],
      ]
    );
      $this->add_control(
          'style_hotspot_box_active_color',
          [
              'label'     => esc_html__( 'Active Box Color', 'lastudio-kit' ),
              'type'      => Controls_Manager::COLOR,
              'selectors' => [
                  '{{WRAPPER}}' => '--hotspot-box-active-color: {{VALUE}};',
              ],
          ]
      );

    $this->add_responsive_control(
      'style_hotspot_size',
      [
        'label'      => esc_html__( 'Size', 'lastudio-kit' ),
        'type'       => Controls_Manager::SLIDER,
        'range'      => [
          '%'  => [
            'min' => 0,
            'max' => 100,
          ],
          'px' => [
            'min'  => 0,
            'max'  => 300,
            'step' => 1,
          ],
        ],
        'size_units' => [ 'px', '%' ],
        'default'    => [
          'unit' => 'px',
        ],
        'selectors'  => [
          '{{WRAPPER}}' => '--hotspot-size: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'style_typography',
        'selector' => '{{WRAPPER}} .lakit-hotspot__label',
      ]
    );

    $this->add_responsive_control(
      'style_hotspot_width',
      [
        'label'      => esc_html__( 'Min Width', 'lastudio-kit' ),
        'type'       => Controls_Manager::SLIDER,
        'range'      => [
          'px' => [
            'min'  => 0,
            'max'  => 1000,
            'step' => 1,
          ],
        ],
        'size_units' => [ 'px' ],
        'selectors'  => [
          '{{WRAPPER}}' => '--hotspot-min-width: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'style_hotspot_height',
      [
        'label'      => esc_html__( 'Min Height', 'lastudio-kit' ),
        'type'       => Controls_Manager::SLIDER,
        'range'      => [
          'px' => [
            'min'  => 0,
            'max'  => 1000,
            'step' => 1,
          ],
        ],
        'size_units' => [ 'px' ],
        'selectors'  => [
          '{{WRAPPER}}' => '--hotspot-min-height: {{SIZE}}{{UNIT}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'style_hotspot_padding',
      [
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::SLIDER,
        'range'      => [
          'em' => [
            'min' => 0,
            'max' => 100,
          ],
          'px' => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
          ],
        ],
        'size_units' => [ 'px', 'em' ],
        'selectors'  => [
          '{{WRAPPER}}' => '--hotspot-padding: {{SIZE}}{{UNIT}};',
        ],
        'default'    => [
          'unit' => 'px',
        ],
      ]
    );

    $this->add_control(
      'style_hotspot_border_radius',
      [
        'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', 'em', '%' ],
        'selectors'  => [
          '{{WRAPPER}}' => '--hotspot-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
        'default'    => [
          'unit' => 'px',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'style_hotspot_box_shadow',
        'selector' => '
					{{WRAPPER}} .lakit-hotspot:not(.lakit-hotspot--circle) .lakit-hotspot__button,
					{{WRAPPER}} .lakit-hotspot.lakit-hotspot--circle .lakit-hotspot__button .lakit-hotspot__outer-circle
				',
      ]
    );

    $this->end_controls_section();

    /**
     * Section Style Tooltip
     */
    $this->start_controls_section(
      'section_style_tooltip',
      [
        'label' => esc_html__( 'Tooltip Box', 'lastudio-kit' ),
        'tab'   => Controls_Manager::TAB_STYLE,
      ]
    );
    $this->add_control(
      'style_tooltip_text_color',
      [
        'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}}' => '--tooltip-text-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'style_tooltip_typography',
        'selector' => '{{WRAPPER}} .lakit-hotspot__tooltip',
      ]
    );

    $this->add_responsive_control(
      'style_tooltip_align',
      [
        'label'     => esc_html__( 'Alignment', 'lastudio-kit' ),
        'type'      => Controls_Manager::CHOOSE,
        'options'   => [
          'left'    => [
            'title' => esc_html__( 'Left', 'lastudio-kit' ),
            'icon'  => 'eicon-text-align-left',
          ],
          'center'  => [
            'title' => esc_html__( 'Center', 'lastudio-kit' ),
            'icon'  => 'eicon-text-align-center',
          ],
          'right'   => [
            'title' => esc_html__( 'Right', 'lastudio-kit' ),
            'icon'  => 'eicon-text-align-right',
          ],
          'justify' => [
            'title' => esc_html__( 'Justified', 'lastudio-kit' ),
            'icon'  => 'eicon-text-align-justify',
          ],
        ],
        'selectors' => [
          '{{WRAPPER}}' => '--tooltip-align: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'style_tooltip_heading',
      [
        'label'     => esc_html__( 'Box', 'lastudio-kit' ),
        'type'      => Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

      $this->add_control(
          'box_content_layout',
          array(
              'label'   => esc_html__( 'Box layout', 'lastudio-kit' ),
              'type'    => Controls_Manager::SELECT,
              'default' => 'default',
              'options' => array(
                  'default'  => esc_html__( 'Default', 'lastudio-kit' ),
                  'layout01' => esc_html__( 'Layout 01', 'lastudio-kit' ),
                  'layout02' => esc_html__( 'Layout 02', 'lastudio-kit' ),
              ),
          )
      );

      $this->add_responsive_control(
          'style_tooltip_width',
          [
              'label'      => esc_html__( 'Box Width', 'lastudio-kit' ),
              'type'       => Controls_Manager::SLIDER,
              'range'      => [
                  'px' => [
                      'min'  => 0,
                      'max'  => 2000,
                      'step' => 1,
                  ],
              ],
              'size_units' => [ 'px' ],
              'selectors'  => [
                  '{{WRAPPER}}' => '--tooltip-min-width: {{SIZE}}{{UNIT}}',
              ],
          ]
      );

      $this->add_group_control(
          Group_Control_Background::get_type(),
          [
              'name' => 'tooltip_box_background',
              'label' => __( 'Background', 'lastudio-kit' ),
              'types' => [ 'classic', 'gradient' ],
              'selector' => '{{WRAPPER}} .lakit-hotspot__tooltip',
          ]
      );

    $this->add_responsive_control(
      'style_tooltip_padding',
      [
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', 'em', '%' ],
        'default'    => [
          'unit' => 'px',
        ],
        'selectors'  => [
          '{{WRAPPER}}' => '--tooltip-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_control(
      'style_tooltip_border_radius',
      [
        'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', 'em', '%' ],
        'selectors'  => [
          '{{WRAPPER}}' => '--tooltip-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name'     => 'style_tooltip_box_shadow',
        'selector' => '{{WRAPPER}} .lakit-hotspot__tooltip',
      ]
    );

      $this->add_control(
          'style_tooltip_heading0',
          [
              'label'     => esc_html__( 'Box inner', 'lastudio-kit' ),
              'type'      => Controls_Manager::HEADING,
              'separator' => 'before',
          ]
      );

      $this->add_responsive_control(
          'tooltip_box_inner_padding',
          [
              'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
              'type'       => Controls_Manager::DIMENSIONS,
              'size_units' => [ 'px', 'em', '%', 'custom' ],
              'default'    => [
                  'unit' => 'px',
              ],
              'selectors'  => [
                  '{{WRAPPER}}' => '--tooltip-inner-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
              ],
          ]
      );

      $this->add_control(
          'style_tooltip_heading1',
          [
              'label'     => esc_html__( 'Image', 'lastudio-kit' ),
              'type'      => Controls_Manager::HEADING,
              'separator' => 'before',
          ]
      );

      $this->add_responsive_control(
          'tooltip_box_img_width',
          [
              'label'      => esc_html__( 'Image Width', 'lastudio-kit' ),
              'type'       => Controls_Manager::SLIDER,
              'range'      => [
                  'px' => [
                      'min'  => 0,
                      'max'  => 1000,
                      'step' => 1,
                  ],
              ],
              'size_units' => [ 'px', '%', 'custom' ],
              'selectors'  => [
                  '{{WRAPPER}}' => '--tooltip-box-img-width: {{SIZE}}{{UNIT}}',
              ],
          ]
      );
      $this->add_responsive_control(
          'tooltip_box_img_height',
          [
              'label'      => esc_html__( 'Image Height', 'lastudio-kit' ),
              'type'       => Controls_Manager::SLIDER,
              'range'      => [
                  'px' => [
                      'min'  => 0,
                      'max'  => 1000,
                      'step' => 1,
                  ],
              ],
              'size_units' => [ 'px', '%', 'custom' ],
              'selectors'  => [
                  '{{WRAPPER}}' => '--tooltip-box-img-height: {{SIZE}}{{UNIT}}',
              ],
          ]
      );

      $this->add_responsive_control(
          'tooltip_box_img_margin',
          [
              'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
              'type'       => Controls_Manager::DIMENSIONS,
              'size_units' => [ 'px', 'em', '%', 'custom' ],
              'selectors'  => [
                  '{{WRAPPER}}' => '--tooltip-box-img-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
              ],
          ]
      );

    $this->add_control(
      'style_tooltip_heading2',
      [
        'label'     => esc_html__( 'Content/Product Price', 'lastudio-kit' ),
        'type'      => Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );
    $this->add_control(
      'style_tooltip_price_color',
      [
        'label'     => esc_html__( 'Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .product_item--price' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'style_tooltip_price_typography',
        'selector' => '{{WRAPPER}} .product_item--price',
      ]
    );
    $this->add_responsive_control(
      'style_tooltip_price_margin',
      [
        'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', 'em', '%' ],
        'selectors'  => [
          '{{WRAPPER}} .product_item--price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );


    $this->add_control(
      'style_tooltip_heading3',
      [
        'label'     => esc_html__( 'Product Button', 'lastudio-kit' ),
        'type'      => Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name'     => 'style_tooltip_btn_typography',
        'selector' => '{{WRAPPER}} .product_item--action',
      ]
    );

    $this->start_controls_tabs( 'style_tooltip_btn_tabs' );
    $this->start_controls_tab(
      'style_tooltip_btn_normal_tab',
      [
        'label' => esc_html__( 'Normal', 'lastudio-kit' ),
      ]
    );

    $this->add_control(
      'style_tooltip_btn_color',
      [
        'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .product_item--action' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->add_control(
      'style_tooltip_btn_bgcolor',
      [
        'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .product_item--action' => 'background-color: {{VALUE}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'style_tooltip_btn_padding',
      [
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', 'em', '%' ],
        'selectors'  => [
          '{{WRAPPER}} .product_item--action' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'style_tooltip_btn_margin',
      [
        'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', 'em', '%' ],
        'selectors'  => [
          '{{WRAPPER}} .product_item--action' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'style_tooltip_btn_border',
        'label'    => esc_html__( 'Border', 'lastudio-kit' ),
        'selector' => '{{WRAPPER}} .product_item--action',
      ]
    );

    $this->add_responsive_control(
      'style_tooltip_btn_radius',
      [
        'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', 'em', '%' ],
        'selectors'  => [
          '{{WRAPPER}} .product_item--action' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_tab();

    $this->start_controls_tab(
      'style_tooltip_btn_hover_tab',
      [
        'label' => esc_html__( 'Hover', 'lastudio-kit' ),
      ]
    );

    $this->add_control(
      'style_tooltip_btn_color_hover',
      [
        'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .product_item--action:hover' => 'color: {{VALUE}}',
        ],
      ]
    );
    $this->add_control(
      'style_tooltip_btn_bgcolor_hover',
      [
        'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .product_item--action:hover' => 'background-color: {{VALUE}}',
        ],
      ]
    );

    $this->add_responsive_control(
      'style_tooltip_btn_padding_hover',
      [
        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', 'em', '%' ],
        'selectors'  => [
          '{{WRAPPER}} .product_item--action:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_responsive_control(
      'style_tooltip_btn_margin_hover',
      [
        'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', 'em', '%' ],
        'selectors'  => [
          '{{WRAPPER}} .product_item--action:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Border::get_type(),
      [
        'name'     => 'style_tooltip_btn_border_hover',
        'label'    => esc_html__( 'Border', 'lastudio-kit' ),
        'selector' => '{{WRAPPER}} .product_item--action:hover',
      ]
    );

    $this->add_responsive_control(
      'style_tooltip_btn_radius_hover',
      [
        'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', 'em', '%' ],
        'selectors'  => [
          '{{WRAPPER}} .product_item--action:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->end_controls_section();
  }

  protected function render() {
    $settings = $this->get_settings_for_display();

    $is_tooltip_direction_animation = 'lakit-hotspot--slide-direction' === $settings['tooltip_animation'] || 'lakit-hotspot--fade-direction' === $settings['tooltip_animation'];
    $show_tooltip                   = 'none' === $settings['tooltip_trigger'];
    $sequenced_animation_class      = 'yes' === $settings['hotspot_sequenced_animation'] ? 'lakit-hotspot--sequenced' : '';
    $box_content_layout             = $this->get_settings_for_display('box_content_layout');

    // Main Image
    Group_Control_Image_Size::print_attachment_image_html( $settings, 'image', 'image' );

    // Hotspot
    foreach ( $settings['hotspot'] as $key => $hotspot ){
      $is_circle                    = ! $hotspot['hotspot_label'] && ! $hotspot['hotspot_icon']['value'];
      $is_only_icon                 = ! $hotspot['hotspot_label'] && $hotspot['hotspot_icon']['value'];
      $hotspot_position_x           = '%' === $hotspot['hotspot_offset_x']['unit'] ? 'lakit-hotspot--position-' . $hotspot['hotspot_horizontal'] : '';
      $hotspot_position_y           = '%' === $hotspot['hotspot_offset_y']['unit'] ? 'lakit-hotspot--position-' . $hotspot['hotspot_vertical'] : '';
      $is_hotspot_link              = ! empty( $hotspot['hotspot_link']['url'] );
      $tooltip_type                 = $hotspot['hotspot_content_type'];
      $tooltip_product_id           = ! empty( $hotspot['product_id'] ) ? $hotspot['product_id'] : 0;
      $tooltip_product_id           = apply_filters('wpml_object_id', $tooltip_product_id, 'product', true);
      if ( $tooltip_type == 'template' ) {
        $is_hotspot_link = false;
      }
      if( !empty($hotspot['hotspot_tooltip_title']) || !empty($hotspot['hotspot_tooltip_content']) || !empty($hotspot['hotspot_tooltip_image']['id']) ){
          $is_hotspot_link = false;
      }

      $hotspot_element_tag = $is_hotspot_link ? 'a' : 'div';

      // hotspot attributes
      $hotspot_repeater_setting_key = $this->get_repeater_setting_key( 'hotspot', 'hotspots', $key );
      $hotspot_repeater_box_key = $this->get_repeater_setting_key( 'hotspot', 'box', $key );
      $this->add_render_attribute(
        $hotspot_repeater_setting_key, [
          'class' => [
            'lakit-hotspot',
            'elementor-repeater-item-' . $hotspot['_id'],
            $sequenced_animation_class,
            $hotspot_position_x,
            $hotspot_position_y,
            $is_hotspot_link ? 'lakit-hotspot--link' : '',
            ( 'click' === $settings['tooltip_trigger'] && $is_hotspot_link ) ? 'lakit-hotspot--no-tooltip' : '',
            'hotspot-content-type-' . $tooltip_type,
            'box-tpl-'. $box_content_layout
          ],
        ]
      );
      $this->add_render_attribute( $hotspot_repeater_setting_key, 'data-id', $hotspot['_id'] );
      if ( filter_var( $hotspot['always_show'], FILTER_VALIDATE_BOOLEAN ) ) {
        $this->add_render_attribute( $hotspot_repeater_setting_key, 'class', 'lakit-hotspot--active lakit-hotspot--always' );
      }
      if ( $is_circle ) {
        $this->add_render_attribute( $hotspot_repeater_setting_key, 'class', 'lakit-hotspot--circle' );
      }
      if ( $is_only_icon ) {
        $this->add_render_attribute( $hotspot_repeater_setting_key, 'class', 'lakit-hotspot--icon' );
      }

      if ( $is_hotspot_link ) {
        $this->add_link_attributes( $hotspot_repeater_setting_key, $hotspot['hotspot_link'] );
      }

      // hotspot trigger attributes
      $trigger_repeater_setting_key = $this->get_repeater_setting_key( 'trigger', 'hotspots', $key );
      $this->add_render_attribute(
        $trigger_repeater_setting_key, [
          'class' => [
            'lakit-hotspot__button',
            $settings['hotspot_animation'],
          ],
        ]
      );

      //direction mask attributes
      $direction_mask_repeater_setting_key = $this->get_repeater_setting_key( 'lakit-hotspot__direction-mask', 'hotspots', $key );
      $this->add_render_attribute(
        $direction_mask_repeater_setting_key, [
          'class' => [
            'lakit-hotspot__direction-mask',
            ( $is_tooltip_direction_animation ) ? 'lakit-hotspot--tooltip-position' : '',
          ],
        ]
      );

      //tooltip attributes
      $tooltip_custom_position      = ( $is_tooltip_direction_animation && $hotspot['hotspot_tooltip_position'] && $hotspot['hotspot_position'] ) ? 'lakit-hotspot--override-tooltip-animation-from-' . $hotspot['hotspot_position'] : '';
      $tooltip_repeater_setting_key = $this->get_repeater_setting_key( 'tooltip', 'hotspots', $key );

      $this->add_render_attribute( $tooltip_repeater_setting_key, 'data-id', $hotspot['_id'] );
      $this->add_render_attribute(
        $tooltip_repeater_setting_key, [
          'class' => [
            'lakit-hotspot__tooltip',
            ( $show_tooltip ) ? 'lakit-hotspot--show-tooltip' : '',
            ( ! $is_tooltip_direction_animation ) ? 'lakit-hotspot--tooltip-position' : '',
            ( ! $show_tooltip ) ? $settings['tooltip_animation'] : '',
            $tooltip_custom_position,
          ],
        ]
      ); ?>
      <?php // Hotspot
      ?>
      <<?php Utils::print_validated_html_tag( $hotspot_element_tag ); ?> <?php $this->print_render_attribute_string( $hotspot_repeater_setting_key ); ?>>
      <?php // Hotspot Trigger
      ?>
      <div <?php $this->print_render_attribute_string( $trigger_repeater_setting_key ); ?>>
        <?php if ( $is_circle ) : ?>
          <div class="lakit-hotspot__outer-circle"></div>
          <div class="lakit-hotspot__inner-circle"></div>
        <?php else : ?>
          <?php if ( $hotspot['hotspot_icon']['value'] ) : ?>
            <div class="lakit-hotspot__icon"><?php Icons_Manager::render_icon( $hotspot['hotspot_icon'] ); ?></div>
          <?php endif; ?>
          <?php if ( $hotspot['hotspot_label'] ) : ?>
            <div class="lakit-hotspot__label"><?php
              // PHPCS - the main text of a widget should not be escaped.
              echo $hotspot['hotspot_label']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
              ?></div>
          <?php endif; ?>
        <?php endif; ?>
      </div>
      <?php
      // Hotspot Tooltip
      $hotspot_tooltip_content = '';
      if ( $tooltip_type == 'template' ) {
        if ( function_exists( 'wc_get_product' ) ) {
          $product_obj = wc_get_product( $tooltip_product_id );
          if ( $product_obj ) {
            $tpl                     = '<div class="lakit-hotspot__product">%1$s<div class="lakit-hotspot__product_info">%2$s%3$s%4$s</div></div>';
            $product_image           = $product_obj->get_image();
            $product_title           = sprintf( '<a class="product_item--title" href="%1$s">%2$s</a>', esc_url( $product_obj->get_permalink() ), $product_obj->get_title() );
            $product_price           = sprintf( '<span class="product_item--price price">%1$s</span>', $product_obj->get_price_html() );

            $add_card__key = $trigger_repeater_setting_key . '_addcart';

            $add_cart_classes = ['product_item--action', 'elementor-button', 'elementor-size-xs', 'la-addcart', 'add_to_cart_button', 'ajax_add_to_cart'];
            $add_cart_classes[] = 'product_type_' . $product_obj->get_type();
            $this->add_render_attribute($add_card__key, 'class', $add_cart_classes);
            $this->add_render_attribute($add_card__key, 'href', $product_obj->get_permalink());
            $this->add_render_attribute($add_card__key, 'data-quantity', '1');
            $this->add_render_attribute($add_card__key, 'data-product_id', $product_obj->get_id());
            $this->add_render_attribute($add_card__key, 'data-product_sku', $product_obj->get_sku());

            $product_action          = sprintf('<a %1$s>%2$s</a>',$this->get_render_attribute_string($add_card__key), $product_obj->add_to_cart_text() );
            $hotspot_tooltip_content = sprintf( $tpl, $product_image, $product_title, $product_price, $product_action );
          }
        }
      }
      else {
          $tpl                     = '<div class="lakit-hotspot__product">%1$s<div class="lakit-hotspot__product_info">%2$s%3$s</div>%4$s</div>';
          $product_title           = !empty($hotspot['hotspot_tooltip_title']) ? sprintf( '<div class="product_item--title">%1$s</div>', $hotspot['hotspot_tooltip_title'] ) : '';
          $product_price           = !empty($hotspot['hotspot_tooltip_content']) ? sprintf( '<div class="product_item--price">%1$s</div>', $hotspot['hotspot_tooltip_content'] ) : '';
          $product_image           = '';
          if(!empty($hotspot['hotspot_tooltip_image']['id'])){
              $product_image = wp_get_attachment_image($hotspot['hotspot_tooltip_image']['id'], 'full');
          }
          $product_link = '';
          if(!empty( $hotspot['hotspot_link']['url'] )){
              $this->add_link_attributes( $hotspot_repeater_box_key, $hotspot['hotspot_link'] );
              $product_link = sprintf('<a class="lakit-hotspot--linkoverlay" %1$s>%2$s</a>', $this->get_render_attribute_string( $hotspot_repeater_box_key ), $hotspot['hotspot_tooltip_title']);
          }
          $hotspot_tooltip_content = sprintf( $tpl, $product_image, $product_title, $product_price, $product_link );
      }

      ?>
      <?php if ( $hotspot_tooltip_content && ! ( 'click' === $settings['tooltip_trigger'] && $is_hotspot_link ) ) : ?>
      <?php if ( $is_tooltip_direction_animation ) : ?>
        <div <?php $this->print_render_attribute_string( $direction_mask_repeater_setting_key ); ?>>
      <?php endif; ?>
      <div <?php $this->print_render_attribute_string( $tooltip_repeater_setting_key ); ?> >
        <?php
        // PHPCS - the main text of a widget should not be escaped.
        echo $hotspot_tooltip_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
      </div>
      <?php if ( $is_tooltip_direction_animation ) : ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
      </<?php Utils::print_validated_html_tag( $hotspot_element_tag ); ?>>
    <?php } ?>
    <?php
  }

  /**
   * Render Hotspot widget output in the editor.
   *
   * Written as a Backbone JavaScript template and used to generate the live preview.
   *
   * @since  2.9.0
   * @access protected
   */

  protected function content_template(){
      return false;
  }

  protected function content_templatea() { ?>
    <#
      const image = {
      id: settings.image.id,
      url: settings.image.url,
      size: settings.image_size,
      dimension: settings.image_custom_dimension,
      model: view.getEditModel()
    };
    const imageUrl = elementor.imagesManager.getImageUrl( image );
    let productHTMLPlaceholder = '<div class="lakit-hotspot__product">';
      productHTMLPlaceholder += '<img src="<?php echo esc_url( Utils::get_placeholder_image_src() ); ?>" title="" alt="" loading="lazy" decoding="async"/>';
      productHTMLPlaceholder += '<div class="lakit-hotspot__product_info">';
      productHTMLPlaceholder += '<a href="#" class="product_item--title">Name of product</a>';
      productHTMLPlaceholder += '<span class="product_item--price price"><del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>59.99</bdi></span></del> <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>35.99</bdi></span></ins></span>';
      productHTMLPlaceholder += '<a class="product_item--action elementor-button elementor-size-xs" href="#">Shop Now</a>';
      productHTMLPlaceholder += '</div>';
      productHTMLPlaceholder += '</div>';
    #>
    <img src="{{ imageUrl }}" title="" alt="" loading="lazy" decoding="async"/>
    <#
    const isTooltipDirectionAnimation = (settings.tooltip_animation==='lakit-hotspot--slide-direction' || settings.tooltip_animation==='lakit-hotspot--fade-direction' ) ? true : false;
    const showTooltip = ( settings.tooltip_trigger === 'none' );
    _.each( settings.hotspot, ( hotspot, index ) => {
      const iconHTML = elementor.helpers.renderIcon( view, hotspot.hotspot_icon, {}, 'i' , 'object' );
      const isCircle = !hotspot.hotspot_label && !hotspot.hotspot_icon.value;
      const isOnlyIcon = !hotspot.hotspot_label && hotspot.hotspot_icon.value;
      const hotspotPositionX = '%' === hotspot.hotspot_offset_x.unit ? 'lakit-hotspot--position-' + hotspot.hotspot_horizontal : '';
      const hotspotPositionY = '%' === hotspot.hotspot_offset_y.unit ? 'lakit-hotspot--position-' + hotspot.hotspot_vertical : '';
      let hotspotLink = (hotspot.hotspot_content_type != 'template') ? hotspot.hotspot_link.url : false;
      if(hotspot.hotspot_content_type == 'template'){
        hotspot.hotspot_html_render = productHTMLPlaceholder;
      }
      else{
        let _imageUrl = elementor.imagesManager.getImageUrl({
          id: hotspot.hotspot_tooltip_image.id,
          url: hotspot.hotspot_tooltip_image.url,
          size: 'full',
          model: view.getEditModel()
        });
        let _htmlRender = '<div class="lakit-hotspot__product">';
          if(_imageUrl){
            _htmlRender += '<img src="' + _imageUrl + '" title="" alt="" loading="lazy" decoding="async"/>';
            hotspotLink = false;
          }
          _htmlRender += '<div class="lakit-hotspot__product_info">';
          if(hotspot.hotspot_tooltip_title){
            _htmlRender += '<div class="product_item--title">'+hotspot.hotspot_tooltip_title+'</div>';
            hotspotLink = false;
          }
          if(hotspot.hotspot_tooltip_content){
            _htmlRender += '<div class="product_item--price">'+hotspot.hotspot_tooltip_content+'</div>';
            hotspotLink = false;
          }
          _htmlRender += '</div></div>';
        hotspot.hotspot_html_render = _htmlRender;
      }
      const hotspotElementTag = hotspotLink ? 'a': 'div';
      // hotspot attributes
      const hotspotRepeaterSettingKey = view.getRepeaterSettingKey( 'hotspot', 'hotspots', index );
      view.addRenderAttribute( hotspotRepeaterSettingKey, {
        'class' : [
          'lakit-hotspot',
          'elementor-repeater-item-' + hotspot._id,
          'box-tpl-'. settings.box_content_layout'
          hotspotPositionX,
          hotspotPositionY,
          hotspotLink ? 'lakit-hotspot--link' : '',
        ]
      });
      view.addRenderAttribute( hotspotRepeaterSettingKey, 'data-id', hotspot._id );
      if ( isCircle ) {
        view.addRenderAttribute( hotspotRepeaterSettingKey, 'class', 'lakit-hotspot--circle' );
      }
      if ( isOnlyIcon ) {
        view.addRenderAttribute( hotspotRepeaterSettingKey, 'class', 'lakit-hotspot--icon' );
      }
      // hotspot trigger attributes
      const triggerRepeaterSettingKey = view.getRepeaterSettingKey( 'trigger', 'hotspots', index );
      view.addRenderAttribute(triggerRepeaterSettingKey, {
        'class' : [
          'lakit-hotspot__button',
          settings.hotspot_animation,
          //'hotspot-trigger-' + hotspot.hotspot_icon_position
        ]
      });
      //direction mask attributes
      const directionMaskRepeaterSettingKey = view.getRepeaterSettingKey( 'lakit-hotspot__direction-mask', 'hotspots', index );
      view.addRenderAttribute(directionMaskRepeaterSettingKey, {
        'class' : [
          'lakit-hotspot__direction-mask',
          ( isTooltipDirectionAnimation ) ? 'lakit-hotspot--tooltip-position' : ''
        ]
      });
      //tooltip attributes
      const tooltipCustomPosition = ( isTooltipDirectionAnimation && hotspot.hotspot_tooltip_position && hotspot.hotspot_position ) ? 'lakit-hotspot--override-tooltip-animation-from-' + hotspot.hotspot_position : '';
      const tooltipRepeaterSettingKey = view.getRepeaterSettingKey('tooltip', 'hotspots', index);
      view.addRenderAttribute( tooltipRepeaterSettingKey, 'data-id', hotspot._id );
      view.addRenderAttribute( tooltipRepeaterSettingKey, {
        'class': [
          'lakit-hotspot__tooltip',
          ( showTooltip ) ? 'lakit-hotspot--show-tooltip' : '',
          ( !isTooltipDirectionAnimation ) ? 'lakit-hotspot--tooltip-position' : '',
          ( !showTooltip ) ? settings.tooltip_animation : '',
          tooltipCustomPosition,
          ( hotspot.always_show ) ? 'lakit-hotspot--active lakit-hotspot--always' : 'ddd'
        ]
      });
    #>
    <{{{ hotspotElementTag }}} {{{ view.getRenderAttributeString( hotspotRepeaterSettingKey ) }}}>
    <?php // Hotspot Trigger ?>
    <div {{{ view.getRenderAttributeString( triggerRepeaterSettingKey ) }}}>
      <# if ( isCircle ) { #>
      <div class="lakit-hotspot__outer-circle"></div>
      <div class="lakit-hotspot__inner-circle"></div>
      <# } else { #>
      <# if (hotspot.hotspot_icon.value){ #>
      <div class="lakit-hotspot__icon">{{{ iconHTML.value }}}</div>
      <# } #>
      <# if ( hotspot.hotspot_label ){ #>
      <div class="lakit-hotspot__label">{{{ hotspot.hotspot_label }}}</div>
      <# } #>
      <# } #>
    </div>
    <?php // Hotspot Tooltip ?>
    <# if( hotspot.hotspot_html_render && ! ( 'click' === settings.tooltip_trigger && hotspotLink ) ){ #>
        <# if( isTooltipDirectionAnimation ){ #>
        <div {{{ view.getRenderAttributeString( directionMaskRepeaterSettingKey ) }}}>
        <# } #>
        <div {{{ view.getRenderAttributeString( tooltipRepeaterSettingKey ) }}}>
        {{{ hotspot.hotspot_html_render }}}
        </div>
        <# if( isTooltipDirectionAnimation ){ #>
        </div>
        <# } #>
    <# } #>
    </{{{ hotspotElementTag }}}>
    <# }); #>
    <?php
  }
}
