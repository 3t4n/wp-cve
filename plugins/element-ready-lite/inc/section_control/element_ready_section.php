<?php
/*
  Global Section
*/

namespace Element_Ready\section_control;

use Elementor\Controls_Manager;
use \Elementor\Core\Kits\Documents\Tabs\Global_Colors as GlobalColors;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

class Element_Ready_Section
{
  // Hold the class instance.
  private static $instance = null;

  private function __construct()
  {
    add_action('elementor/element/before_section_start', [$this, 'alignment'], 15, 3);
    add_action('elementor/element/before_section_start', [$this, 'custom_advanced'], 15, 3);
    add_action('elementor/element/before_section_start', [$this, 'widget_advanced'], 15, 3);
    add_action('elementor/element/before_section_start', [$this, 'widget_column_layout'], 15, 3);
    add_action('elementor/element/before_section_start', [$this, 'widget_background_effect'], 15, 3);
    add_action('elementor/element/wp-page/document_settings/after_section_end', [$this, 'add_page_setting_section'], 15, 2);
    // front render
    add_action('wp_head', [$this, 'inline_script']);
    add_action('elementor/frontend/section/after_render', [$this, 'after_section_render'], 10, 2);
    // Elementor Settings
    add_action('elementor/element/after_section_end', [$this, 'add_footer_controls_section'], 15, 3);
  }
  public function add_footer_controls_section($element, $section_id, $args)
  {

    if ($section_id == 'section_custom_css_pro') {

      $element->start_controls_section(
        'er_appie_section_',
        [
          'label' => esc_html__('ER Footer', 'element-ready-lite'),
          'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
        ]
      );

      $element->add_control(
        'er_footer_div_missing',
        [
          'label' => esc_html__('Fix Footer div missing', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SWITCHER,
          'default' => '',
          'return_value' => 'no',

        ]
      );

      $element->end_controls_section();
    }

    // Isolation

  }
  function add_page_setting_section(\Elementor\Core\DocumentTypes\Page $page, $args)
  {


    $page->start_controls_section(
      'er_section_page_container',
      [
        'label' => esc_html__('ER Page Setting', 'element-ready-lite'),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );


    $page->add_control(
      'er_page_custom_container_layout',
      [
        'label' => __('Width', 'element-ready-lite'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px', '%'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 2500,
            'step' => 5,
          ],
          'rem' => [
            'min' => 0,
            'max' => 2500,
            'step' => 5,
          ],
          '%' => [
            'min' => 0,
            'max' => 100,
          ],
        ],

        'selectors' => [
          '{{WRAPPER}} .elementor-section.elementor-section-boxed > .elementor-container' => 'width: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $page->add_control(
      'er_page_custom_max_width_container_layout',
      [
        'label' => __('Max Width', 'element-ready-lite'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px', '%'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 2500,
            'step' => 5,
          ],
          'rem' => [
            'min' => 0,
            'max' => 2500,
            'step' => 5,
          ],
          '%' => [
            'min' => 0,
            'max' => 100,
          ],
        ],

        'selectors' => [
          '{{WRAPPER}} .elementor-section.elementor-section-boxed > .elementor-container' => 'max-width: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $page->end_controls_section();
  }

  public function inline_script()
  {

    if (element_ready_get_modules_option('section_perticles')) {

      $particles_library = ELEMENT_READY_ROOT_JS . 'particles.min.js';
      $stats = ELEMENT_READY_ROOT_JS . 'stats.js';

      wp_localize_script('element-ready-particle', 'element_ready_script', [
        'particle' => $particles_library,
        'stats' => $stats
      ]);
    }

    echo '
        <script type = "text/javascript">
        
          var element_ready_section_data = {};
         
        </script>
      ';
  }
  public function after_section_render(\Elementor\Element_Base $element)
  {
    $data = $element->get_data();
    $settings = $data['settings'];


    if (isset($settings['element_ready_background_effect_active']) && $settings['element_ready_background_effect_active'] == 'red') {

      echo "
            <script>
             
                window.element_ready_section_data.section" . $data['id'] . " = JSON.parse('" . json_encode($settings) . "');
            </script>
            ";
    }
  }
  function widget_background_effect($element, $section_id, $args)
  {
    if (!element_ready_get_modules_option('section_perticles')) {
      return;
    }
    if ('section' == $element->get_name() && 'section_structure' == $section_id) {

      $element->start_controls_section(
        'element_ready_background_particle_efffect__section',
        [
          'tab' => \Elementor\Controls_Manager::TAB_STYLE,
          'label' => esc_html__('Element Ready Background Effect', 'element-ready-lite'),
        ]
      );
      $element->add_control(
        'element_ready_background_effect_active',
        [
          'label' => esc_html__('Background Particles', 'element-ready-lite'),
          'type' => Controls_Manager::SWITCHER,
          'default' => '',
          'return_value' => 'red',

        ]
      );

      $element->add_control(
        'element_read_global_section_particles_number_popover',
        [
          'label' => esc_html__('Number', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
          'label_off' => esc_html__('Default', 'element-ready-lite'),
          'label_on' => esc_html__('Custom', 'element-ready-lite'),
          'return_value' => 'yes',
          'default' => 'yes',
          'condition' => [
            'element_ready_background_effect_active' => ['red']
          ]

        ]
      );

      $element->start_popover();

      $element->add_control(
        'element_ready_particle_number',
        [
          'label' => esc_html__('Number', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::NUMBER,
          'min' => 1,
          'max' => 1000,
          'step' => 5,
          'default' => 40,
        ]
      );

      $element->add_control(
        'element_ready_particle_density',
        [
          'label' => esc_html__('Density', 'element-ready-lite'),
          'type' => Controls_Manager::SWITCHER,
          'default' => '',
          'return_value' => true,

        ]
      );

      $element->add_control(
        'element_ready_particle_value_area',
        [
          'label' => esc_html__('Value Area', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::NUMBER,
          'min' => 1,
          'max' => 1000,
          'step' => 5,
          'default' => 400,
          'condition' => [
            'element_ready_particle_density' => ['true']
          ]
        ]
      );

      $element->end_popover();

      $element->add_control(
        'element_ready_particle_color',
        [
          'label' => esc_html__('Color', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::COLOR,
          'global' => array(
            'default' => GlobalColors::COLOR_PRIMARY
          ),
          'condition' => [
            'element_ready_background_effect_active' => ['red']
          ]

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_shape_popover',
        [
          'label' => esc_html__('Shape', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
          'label_off' => esc_html__('Default', 'element-ready-lite'),
          'label_on' => esc_html__('Custom', 'element-ready-lite'),
          'return_value' => 'yes',
          'default' => 'yes',
          'condition' => [
            'element_ready_background_effect_active' => ['red']
          ]

        ]
      );

      $element->start_popover();

      $element->add_control(
        'element_ready_global_section_particles_shape_type',
        [
          'label' => esc_html__('Shape', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SELECT2,
          'default' => 'circle',
          'label_block' => true,
          'multiple' => true,
          'options' => [
            'circle' => esc_html__('Circle', 'element-ready-lite'),
            'edge' => esc_html__('Edge', 'element-ready-lite'),
            'triangle' => esc_html__('triangle', 'element-ready-lite'),
            'polygon' => esc_html__('polygon', 'element-ready-lite'),
            'star' => esc_html__('star', 'element-ready-lite'),
            'image' => esc_html__('image', 'element-ready-lite'),
          ],
        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_shape_stroke_width',
        [
          'label' => esc_html__('Strok Width', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::NUMBER,
          'min' => 0,
          'max' => 1000,
          'step' => 5,
          'default' => 0,

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_shape_stroke_color',
        [
          'label' => esc_html__('Color', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::COLOR,
          'global' => array(
            'default' => GlobalColors::COLOR_PRIMARY
          ),

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_shape_polygon_slides',
        [
          'label' => esc_html__('Polygon Slides', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::NUMBER,
          'min' => 0,
          'max' => 100,
          'step' => 5,
          'default' => 5,
          'condition' => [
            'element_ready_global_section_particles_shape_type' => 'polygon'
          ]

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_shape_image',
        [
          'label' => esc_html__('Choose Shape', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::MEDIA,
          'description' => esc_html__('upload Svg image', 'element-ready-lite'),
          'condition' => [
            'element_ready_global_section_particles_shape_type' => 'image'
          ]
        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_shape_image_width',
        [
          'label' => esc_html__('Width', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::NUMBER,
          'min' => 0,
          'max' => 800,
          'step' => 5,
          'default' => 100,
          'condition' => [
            'element_ready_global_section_particles_shape_type' => 'image'
          ]

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_shape_image_height',
        [
          'label' => esc_html__('Height', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::NUMBER,
          'min' => 0,
          'max' => 800,
          'step' => 5,
          'default' => 100,
          'condition' => [
            'element_ready_global_section_particles_shape_type' => 'image'
          ]

        ]
      );
      $element->end_popover();

      $element->add_control(
        'element_ready_global_section_particles_opacity',
        [
          'label' => esc_html__('Opacity', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
          'label_off' => esc_html__('Default', 'element-ready-lite'),
          'label_on' => esc_html__('Custom', 'element-ready-lite'),
          'return_value' => 'yes',
          'default' => 'yes',
          'condition' => [
            'element_ready_background_effect_active' => ['red']
          ]

        ]
      );

      $element->start_popover();
      $element->add_control(
        'element_ready_global_section_particles_opacity_value',
        [
          'label' => esc_html__('Opacity', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 1,
              'step' => 0.1,
            ],

          ],

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_opacity_random',
        [
          'label' => esc_html__('Random', 'element-ready-lite'),
          'type' => Controls_Manager::SWITCHER,
          'default' => '',
          'return_value' => true,

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_opacity_anim',
        [
          'label' => esc_html__('Anim', 'element-ready-lite'),
          'type' => Controls_Manager::SWITCHER,
          'default' => '',
          'return_value' => true,

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_opacity_anim_speed',
        [
          'label' => esc_html__('Anim Speed', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 100,
              'step' => 1,
            ],

          ],

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_opacity_min_value',
        [
          'label' => esc_html__('Anim Min Opacity', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 1,
              'step' => 0.1,
            ],

          ],

        ]
      );
      $element->end_popover();

      // line linked
      $element->add_control(
        'element_ready_global_section_particles_line_linked_popover',
        [
          'label' => esc_html__('Line Linked', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
          'label_off' => esc_html__('Default', 'element-ready-lite'),
          'label_on' => esc_html__('Custom', 'element-ready-lite'),
          'return_value' => 'yes',
          'default' => 'yes',
          'condition' => [
            'element_ready_background_effect_active' => ['red']
          ]
        ]
      );

      $element->start_popover();

      $element->add_control(
        'element_ready_global_section_particles_line_linked',
        [
          'label' => esc_html__('Enable', 'element-ready-lite'),
          'type' => Controls_Manager::SWITCHER,
          'default' => '',
          'return_value' => true,

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_line_linked_opacity_value',
        [
          'label' => esc_html__('Opacity', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 1,
              'step' => 0.1,
            ],

          ],

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_line_linked_distance',
        [
          'label' => esc_html__('Distance', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 800,
              'step' => 1,
            ],

          ],

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_line_linked_width',
        [
          'label' => esc_html__('Width', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 500,
              'step' => 1,
            ],

          ],

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_line_linked_color',
        [
          'label' => esc_html__('Color', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::COLOR,
          'global' => array(
            'default' => GlobalColors::COLOR_PRIMARY
          ),

        ]
      );
      $element->end_popover();

      $element->add_control(
        'element_ready_global_section_particles_move_popover',
        [
          'label' => esc_html__('Move', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
          'label_off' => esc_html__('Default', 'element-ready-lite'),
          'label_on' => esc_html__('Custom', 'element-ready-lite'),
          'return_value' => 'yes',
          'default' => 'yes',
          'condition' => [
            'element_ready_background_effect_active' => ['red']
          ]

        ]
      );

      $element->start_popover();

      $element->add_control(
        'element_ready_global_section_particles_move',
        [
          'label' => esc_html__('Enable', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SWITCHER,
          'default' => '',
          'return_value' => true,

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_move_speed',
        [
          'label' => esc_html__('Speed', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 300,
              'step' => 1,
            ],

          ],

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_move_direction',
        [
          'label' => esc_html__('Direction', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SELECT,
          'default' => 'none',
          'label_block' => true,
          'multiple' => false,
          'options' => [
            'top' => esc_html__('Top', 'element-ready-lite'),
            'top-right' => esc_html__('Top Right', 'element-ready-lite'),
            'right' => esc_html__('Right', 'element-ready-lite'),
            'bottom-right' => esc_html__('Bottom Right', 'element-ready-lite'),
            'bottom' => esc_html__('Bottom', 'element-ready-lite'),
            'bottom-left' => esc_html__('Bottom Left', 'element-ready-lite'),
            'left' => esc_html__('Left', 'element-ready-lite'),
            'top-left' => esc_html__('Top Left', 'element-ready-lite'),
            'none' => esc_html__('none', 'element-ready-lite'),
          ],
        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_move_random',
        [
          'label' => esc_html__('Move Random', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SWITCHER,
          'default' => '',
          'return_value' => true,

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_move_straight',
        [
          'label' => esc_html__('Move Straight', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SWITCHER,
          'default' => '',
          'return_value' => true,

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_move_mode',
        [
          'label' => esc_html__('Mode', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SELECT2,
          'default' => 'out',
          'label_block' => true,

          'options' => [
            'out' => esc_html__('Out', 'element-ready-lite'),
            'bounce' => esc_html__('Bounce', 'element-ready-lite'),

          ],
        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_move_bounce',
        [
          'label' => esc_html__('Bounce', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SWITCHER,
          'default' => true,
          'return_value' => true,

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_move_attract',
        [
          'label' => esc_html__('Attract /Rotation', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SWITCHER,
          'default' => true,
          'return_value' => true,

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_move_attract_x',
        [
          'label' => esc_html__('Rotate X', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 9000,
              'step' => 1,
            ],

          ],

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_move_attract_y',
        [
          'label' => esc_html__('Rotate Y', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 9000,
              'step' => 1,
            ],

          ],

        ]
      );
      $element->end_popover();

      // interectivity
      $element->add_control(
        'element_ready_global_section_particles_interactivity_popover',
        [
          'label' => esc_html__('interactivity', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
          'label_off' => esc_html__('Default', 'element-ready-lite'),
          'label_on' => esc_html__('Custom', 'element-ready-lite'),
          'return_value' => 'yes',
          'default' => 'yes',
          'condition' => [
            'element_ready_background_effect_active' => ['red']
          ]

        ]
      );

      $element->start_popover();

      $element->add_control(
        'element_ready_global_section_particles_interactivity_detect_on',
        [
          'label' => esc_html__('Detect on', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SELECT,
          'default' => 'canvas',
          'label_block' => true,
          'multiple' => false,
          'options' => [
            'canvas' => esc_html__('Canvas', 'element-ready-lite'),
            'window' => esc_html__('window', 'element-ready-lite'),
          ],
        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_interactivity_onclick',
        [
          'label' => esc_html__('Click Effect', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SWITCHER,
          'default' => true,
          'return_value' => true,

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_interactivity_click_mode',
        [
          'label' => esc_html__('Click Mode', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SELECT2,
          'default' => 'grab',
          'label_block' => true,
          'multiple' => true,
          'options' => [
            'grab' => esc_html__('grab', 'element-ready-lite'),
            'remove' => esc_html__('remove', 'element-ready-lite'),
            'bubble' => esc_html__('bubble', 'element-ready-lite'),
            'repulse' => esc_html__('repulse', 'element-ready-lite'),
          ],
          'condition' => [
            'element_ready_global_section_particles_interactivity_onclick' => 'true'
          ]
        ]
      );


      $element->add_control(
        'element_ready_global_section_particles_interactivity_onhover',
        [
          'label' => esc_html__('Hover Effect', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SWITCHER,
          'default' => true,
          'return_value' => true,

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_interactivity_hover_mode',
        [
          'label' => esc_html__('Hover Mode', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SELECT2,
          'default' => 'grab',
          'label_block' => true,
          'multiple' => true,
          'options' => [
            'grab' => esc_html__('grab', 'element-ready-lite'),
            'remove' => esc_html__('remove', 'element-ready-lite'),
            'bubble' => esc_html__('bubble', 'element-ready-lite'),
            'repulse' => esc_html__('repulse', 'element-ready-lite'),
          ],
          'condition' => [
            'element_ready_global_section_particles_interactivity_onhover' => 'true'
          ]
        ]
      );

      $element->end_popover();

      $element->add_control(
        'element_ready_global_section_particles_interactivity_bubble_mode_popover',
        [
          'label' => esc_html__('Bubble Mode', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
          'label_off' => esc_html__('Default', 'element-ready-lite'),
          'label_on' => esc_html__('Custom', 'element-ready-lite'),
          'return_value' => 'yes',
          'default' => 'yes',
          'condition' => [
            'element_ready_background_effect_active' => ['red']
          ]

        ]
      );

      $element->start_popover();

      $element->add_control(
        'element_ready_global_section_particles_interactivity_bubble_distance',
        [
          'label' => esc_html__('Distance', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 9000,
              'step' => 1,
            ],

          ],

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_interactivity_bubble_size',
        [
          'label' => esc_html__('Size', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 200,
              'step' => 1,
            ],

          ],

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_interactivity_bubble_duration',
        [
          'label' => esc_html__('Duration', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 1,
              'step' => 0.1,
            ],

          ],

        ]
      );
      $element->end_popover();

      $element->add_control(
        'element_ready_global_section_particles_interactivity_repulse_mode_popover',
        [
          'label' => esc_html__('Repulse Mode', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
          'label_off' => esc_html__('Default', 'element-ready-lite'),
          'label_on' => esc_html__('Custom', 'element-ready-lite'),
          'return_value' => 'yes',
          'default' => 'yes',
          'condition' => [
            'element_ready_background_effect_active' => ['red']
          ]

        ]
      );

      $element->start_popover();

      $element->add_control(
        'element_ready_global_section_particles_interactivity_repulse_distance',
        [
          'label' => esc_html__('Distance', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 9000,
              'step' => 1,
            ],

          ],

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_interactivity_repulse_size',
        [
          'label' => esc_html__('Size', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 200,
              'step' => 1,
            ],

          ],

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_interactivity_repulse_duration',
        [
          'label' => esc_html__('Duration', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 1,
              'step' => 0.1,
            ],

          ],

        ]
      );

      $element->end_popover();

      $element->add_control(
        'element_ready_global_section_particles_mode_push',
        [
          'label' => esc_html__('Push Particles', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'condition' => [
            'element_ready_background_effect_active' => ['red']
          ],
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 200,
              'step' => 1,
            ],

          ],

        ]
      );

      $element->add_control(
        'element_ready_global_section_particles_mode_remove',
        [
          'label' => esc_html__('Remove Particles', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'condition' => [
            'element_ready_background_effect_active' => ['red']
          ],
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 200,
              'step' => 1,
            ],

          ],

        ]
      );
      $element->end_controls_section();
    }
  }

  function widget_column_layout($element, $section_id, $args)
  {


    if ('common' == $element->get_name() && '_section_responsive' == $section_id && element_ready_get_modules_option('widget_order')) {

      $element->start_controls_section(
        'element_ready_widget__column_order__section',
        [
          'tab' => \Elementor\Controls_Manager::TAB_STYLE,
          'label' => esc_html__('Element Ready Widget Order', 'element-ready-lite'),
        ]
      );

      $element->add_responsive_control(
        'element_raedy_column_widget_order_',
        [
          'label' => esc_html__('Widget Order', 'element-ready-lite'),
          'type' => Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => -100,
              'max' => 100,
              'step' => 1,
            ],

          ],

          'selectors' => [
            '{{WRAPPER}}' => 'order: {{SIZE}};',
          ],
        ]
      );

      $element->end_controls_section();
    }

    if ('column' == $element->get_name() && 'section_advanced' == $section_id && element_ready_get_modules_option('columns_order_width')) {


      $element->start_controls_section(
        'element_ready_widget__column_layout__section',
        [
          'tab' => \Elementor\Controls_Manager::TAB_STYLE,
          'label' => esc_html__('Element Ready Column Order & Width', 'element-ready-lite'),
        ]
      );

      $element->add_responsive_control(
        'element_raedy_column_layout_order_',
        [
          'label' => esc_html__('Column order', 'element-ready-lite'),
          'type' => Controls_Manager::SLIDER,
          'range' => [
            'px' => [
              'min' => -100,
              'max' => 100,
              'step' => 1,
            ],

          ],

          'selectors' => [
            '{{WRAPPER}}' => 'order: {{SIZE}};',
          ],
        ]
      );

      $element->add_responsive_control(
        'element_raedy_column_layout_width_',
        [
          'label' => esc_html__('Column Width', 'element-ready-lite'),
          'type' => Controls_Manager::SLIDER,
          'size_units' => ['px', '%', 'em'],
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 2100,
              'step' => 1,
            ],
            'em' => [
              'min' => 0,
              'max' => 500,
              'step' => 1,
            ],

          ],

          'selectors' => [
            '{{WRAPPER}}' => 'width: calc(100% - {{SIZE}}{{UNIT}}) !important;',
          ],
        ]
      );

      $element->end_controls_section();
    }
  }
  function widget_advanced($element, $section_id, $args)
  {

    if ('common' == $element->get_name() && '_section_responsive' == $section_id && element_ready_get_modules_option('widget_advanced')) {

      $element->start_controls_section(
        'element_ready_widget_advanced_widget__section',
        [
          'tab' => \Elementor\Controls_Manager::TAB_STYLE,
          'label' => esc_html__('ER Advanced', 'element-ready-lite'),
        ]
      );

      $element->start_controls_tabs(
        'element_ready_widget_common_advanced__style_tabs'
      );


      $element->add_responsive_control(
        'element_ready_widget_common_advanced_image_width',
        [
          'label' => esc_html__('Image Max Width', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px', '%'],
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 2000,
              'step' => 5,
            ],
            '%' => [
              'min' => 0,
              'max' => 100,
            ],
          ],

          'selectors' => [
            '{{WRAPPER}} img' => 'width: {{SIZE}}{{UNIT}};',
          ],
        ]
      );

      $element->start_controls_tab(
        'element_ready_widget_common_advanced_style_normal_tab',
        [
          'label' => esc_html__('Normal', 'element-ready-lite'),
        ]
      );

      $element->add_responsive_control(
        'element_ready_widget_advanced__margin',
        [
          'label' => esc_html__('Margin', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::DIMENSIONS,
          'size_units' => ['px', '%', 'em'],
          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container > :first-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
        ]
      );

      $element->add_responsive_control(
        'element_ready_widget_advanced_padding',
        [
          'label' => esc_html__('Padding', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::DIMENSIONS,
          'size_units' => ['px', '%', 'em'],
          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container > :first-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
        ]
      );

      $element->add_group_control(
        \Elementor\Group_Control_Background::get_type(),
        [
          'name' => 'element_ready_widget_advanced_background',
          'label' => esc_html__('Background', 'element-ready-lite'),
          'types' => ['classic', 'gradient', 'video'],
          'selector' => '{{WRAPPER}} .elementor-widget-container > :first-child',
        ]
      );

      $element->add_responsive_control(
        'element_ready_widget_common_advanced_z_index',
        [
          'label' => esc_html__('Z-index', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px'],
          'range' => [
            'px' => [
              'min' => -100,
              'max' => 2000,
              'step' => 5,
            ],

          ],

          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container > :first-child' => 'z-index: {{SIZE}};',
          ],
        ]
      );

      $element->add_responsive_control(
        'element_ready_widget_ad_overflow',
        [
          'label' => esc_html__('Overflow', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SELECT,
          'default' => '',
          'options' => [
            'overflow: hidden' => esc_html__('X Y Hidden', 'element-ready-lite'),
            'overflow: visible' => esc_html__('X Y Visible', 'element-ready-lite'),
            'overflow-x: auto' => esc_html__('X Auto', 'element-ready-lite'),
            'overflow-x: hidden' => esc_html__('X Hidden', 'element-ready-lite'),
            'overflow-x: visible' => esc_html__('X Visible', 'element-ready-lite'),
            'overflow-x: scroll' => esc_html__('X Scroll', 'element-ready-lite'),
            'overflow-y: auto' => esc_html__('Y Auto', 'element-ready-lite'),
            'overflow-y: hidden' => esc_html__('Y Hidden', 'element-ready-lite'),
            'overflow-y: visible' => esc_html__('Y Visible', 'element-ready-lite'),
            'overflow-y: scroll' => esc_html__('Y Scroll', 'element-ready-lite'),
          ],
          'selectors' => [
            '{{WRAPPER}}' => '{{VALUE}};',
          ],
        ]
      );

      $element->add_control(
        'element_ready_lite_widgte_c_popup_position',
        [
          'label' => esc_html__('Position', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
          'label_off' => esc_html__('Default', 'element-ready-lite'),
          'label_on' => esc_html__('Custom', 'element-ready-lite'),
          'return_value' => 'yes'
        ]
      );



      $element->start_popover();

      $element->add_responsive_control(
        'element_ready_widget_ad_position_type',
        [
          'label' => esc_html__('Position', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SELECT,
          'default' => '',
          'options' => [
            'fixed' => esc_html__('Fixed', 'element-ready-lite'),
            'absolute' => esc_html__('Absolute', 'element-ready-lite'),
            'relative' => esc_html__('Relative', 'element-ready-lite'),
            'sticky' => esc_html__('Sticky', 'element-ready-lite'),
            'static' => esc_html__('Static', 'element-ready-lite'),
            'inherit' => esc_html__('inherit', 'element-ready-lite'),
            '' => esc_html__('none', 'element-ready-lite'),
          ],
          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container > :first-child' => 'position: {{VALUE}};',
          ],
        ]
      );

      $element->end_controls_tab();

      $element->start_controls_tab(
        'element_ready_widget_common_advanced_style_hover_tab',
        [
          'label' => esc_html__('Hover', 'element-ready-lite'),
        ]
      );

      $element->add_responsive_control(
        'element_ready_widget_advanced_hover__margin',
        [
          'label' => esc_html__('Margin', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::DIMENSIONS,
          'size_units' => ['px', '%', 'em'],
          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container > :first-child:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
        ]
      );

      $element->add_responsive_control(
        'element_ready_widget_advanced_hover_padding',
        [
          'label' => esc_html__('Padding', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::DIMENSIONS,
          'size_units' => ['px', '%', 'em'],
          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container > :first-child:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          ],
        ]
      );

      $element->add_group_control(
        \Elementor\Group_Control_Background::get_type(),
        [
          'name' => 'element_ready_widget_advanced_hover_background',
          'label' => esc_html__('Background', 'element-ready-lite'),
          'types' => ['classic', 'gradient', 'video'],
          'selector' => '{{WRAPPER}} .elementor-widget-container > :first-child:hover',
        ]
      );


      $element->end_controls_tab();

      $element->end_controls_tabs();

      $element->end_controls_section();
    }
    // overlay

    if ('common' == $element->get_name() && '_section_responsive' == $section_id && element_ready_get_modules_option('widget_overlay')) {

      $element->start_controls_section(
        'element_ready_widget_overlay_advanced_widget__section',
        [
          'tab' => \Elementor\Controls_Manager::TAB_STYLE,
          'label' => esc_html__('ER Overlay', 'element-ready-lite'),
        ]
      );

      $element->start_controls_tabs(
        'element_ready_widget_overlay_advanced_style_tabs'
      );

      $element->start_controls_tab(
        'element_ready_widget_overlay_advanced_style_normal_tab',
        [
          'label' => esc_html__('Normal', 'element-ready-lite'),
        ]
      );

      $element->add_group_control(
        \Elementor\Group_Control_Background::get_type(),
        [
          'name' => 'element_ready_widget_overlay_advanced_normal_background',
          'label' => esc_html__('Background', 'element-ready-lite'),
          'types' => ['classic', 'gradient', 'video'],
          'selector' => '{{WRAPPER}} .elementor-widget-container > :first-child::after',
        ]
      );

      $element->add_responsive_control(
        'element_ready_widget_overlay_advanced_opacity',
        [
          'label' => esc_html__('Opacity', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px'],
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 1,
              'step' => 0.1,
            ],

          ],
          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container > :first-child::after' => 'opacity: {{SIZE}};content:"";position:absolute;height:100%;width:100%;top:0;left:0',
            '{{WRAPPER}} .elementor-widget-container > :first-child' => 'position:relative;',
          ],
        ]
      );


      $element->add_group_control(
        \Elementor\Group_Control_Css_Filter::get_type(),
        [
          'name' => 'element_ready_widget_overlay_advanced_image_filters',
          'selector' => '{{WRAPPER}} .elementor-widget-container > :first-child',

        ]
      );

      $element->add_responsive_control(
        'element_ready_widget_hover_transition',
        [
          'label' => esc_html__('Hover Transition time', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px'],
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 5,
              'step' => 0.1,
            ],

          ],

          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container > :first-child::after' => 'transition: {{SIZE}}s;',
          ],
        ]
      );

      $element->end_controls_tab();

      $element->start_controls_tab(
        'element_ready_widget_overlay_advanced_style_hover_tab',
        [
          'label' => esc_html__('Hover', 'element-ready-lite'),
        ]
      );

      $element->add_group_control(
        \Elementor\Group_Control_Background::get_type(),
        [
          'name' => 'element_ready_widget_overlay_advanced_hover_background',
          'label' => esc_html__('Background', 'element-ready-lite'),
          'types' => ['classic', 'gradient', 'video'],
          'selector' => '{{WRAPPER}} .elementor-widget-container:hover > :first-child::after',
        ]
      );

      $element->add_responsive_control(
        'element_ready_widget_overlay_advanced_hover_opacity',
        [
          'label' => esc_html__('Opacity', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px'],
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 1,
              'step' => 0.1,
            ],

          ],
          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container:hover > :first-child::after' => 'opacity: {{SIZE}};',

          ],
        ]
      );

      $element->add_group_control(
        \Elementor\Group_Control_Css_Filter::get_type(),
        [
          'name' => 'element_ready_widget_overlay_advanced_hover_image_filters',
          'selector' => '{{WRAPPER}} .elementor-widget-container:hover > :first-child',

        ]
      );


      $element->end_controls_tab();

      $element->end_controls_tabs();

      $element->end_controls_section();
    }

    if ('common' == $element->get_name() && '_section_responsive' == $section_id && element_ready_get_modules_option('widget_background_overlay')) {
      $element->start_controls_section(
        'element_ready_widget_background_overlay_advanced_widget__section',
        [
          'tab' => \Elementor\Controls_Manager::TAB_STYLE,
          'label' => esc_html__('ER Background Overlay', 'element-ready-lite'),
        ]
      );


      $element->add_group_control(
        \Elementor\Group_Control_Background::get_type(),
        [
          'name' => 'element_ready_widgetbgoverlay_advanced__background',
          'label' => esc_html__('Background', 'element-ready-lite'),
          'types' => ['classic', 'gradient'],
          'selector' => '{{WRAPPER}} .elementor-widget-container::after',
        ]
      );


      $element->add_responsive_control(
        'element_ready_bgwidget_overlay_advanced_opacity',
        [
          'label' => esc_html__('Opacity', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px'],
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 1,
              'step' => 0.1,
            ],

          ],

          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container::after' => 'opacity: {{SIZE}};content:"";position:absolute;height:100%;width:100%;top:0;left:0',

          ],
        ]
      );

      $element->add_responsive_control(
        'element_ready_widget__after_common_advanced_width',
        [
          'label' => esc_html__('Width', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px', '%'],
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 2000,
              'step' => 1,
            ],
            '%' => [
              'min' => 0,
              'max' => 200,
            ],
          ],

          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container::after' => 'width: {{SIZE}}{{UNIT}};',
          ],
        ]
      );

      $element->add_responsive_control(
        'element_ready_widget__after_common_advanced_height',
        [
          'label' => esc_html__('Height', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px', '%'],
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 2000,
              'step' => 1,
            ],
            '%' => [
              'min' => 0,
              'max' => 200,
            ],
          ],

          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container::after' => 'height: {{SIZE}}{{UNIT}};',
          ],
        ]
      );

      $element->add_responsive_control(
        'element_ready_widget__after_common_advanced_top_pos',
        [
          'label' => esc_html__('Top Position', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px', '%'],
          'range' => [
            'px' => [
              'min' => -1000,
              'max' => 2000,
              'step' => 1,
            ],
            '%' => [
              'min' => 0,
              'max' => 100,
            ],
          ],

          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container::after' => 'top: {{SIZE}}{{UNIT}};',
          ],
        ]
      );

      $element->add_responsive_control(
        'element_ready_widget__after_common_advanced_left_pos',
        [
          'label' => esc_html__('Left Position', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px', '%'],
          'range' => [
            'px' => [
              'min' => -500,
              'max' => 2000,
              'step' => 1,
            ],
            '%' => [
              'min' => 0,
              'max' => 100,
            ],
          ],

          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container::after' => 'left: {{SIZE}}{{UNIT}};',
          ],
        ]
      );

      $element->add_responsive_control(
        'element_ready_widget__after_common_advanced_z_index',
        [
          'label' => esc_html__('Z-index', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px'],
          'range' => [
            'px' => [
              'min' => -100,
              'max' => 99999,
              'step' => 1,
            ],

          ],

          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container::after' => 'z-index: {{SIZE}};',
          ],
        ]
      );

      $element->add_responsive_control(
        'element_ready_widget__after_common_advanced_border_radi',
        [
          'label' => esc_html__('Border Radius', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px', '%'],
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 700,
              'step' => 1,
            ],
            '%' => [
              'min' => 0,
              'max' => 100,
            ],
          ],

          'selectors' => [
            '{{WRAPPER}} .elementor-widget-container::after' => 'border-radius: {{SIZE}}{{UNIT}};',
          ],
        ]
      );

      $element->end_controls_section();

      //} 
    }
  }
  function alignment($element, $section_id, $args)
  {

    if ('column' === $element->get_name() && 'section_border' == $section_id && element_ready_get_modules_option('columns_content_alignmnet')) {

      $element->start_controls_section(
        'element_ready_alignment_section',
        [
          'tab' => \Elementor\Controls_Manager::TAB_STYLE,
          'label' => esc_html__('Element Ready Alignment', 'element-ready-lite'),
        ]
      );


      $element->add_responsive_control(
        'element_ready_title_align',
        [
          'label' => esc_html__('Alignment', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::CHOOSE,
          'options' => [

            'left' => [

              'title' => esc_html__('Left', 'element-ready-lite'),
              'icon' => 'fa fa-align-left',

            ],

            'center' => [

              'title' => esc_html__('Center', 'element-ready-lite'),
              'icon' => 'fa fa-align-center',

            ],

            'right' => [

              'title' => esc_html__('Right', 'element-ready-lite'),
              'icon' => 'fa fa-align-right',

            ],

            'justify' => [

              'title' => esc_html__('Justified', 'element-ready-lite'),
              'icon' => 'fa fa-align-justify',

            ],
          ],

          'selectors' => [
            '{{WRAPPER}}' => 'text-align: {{VALUE}};',
          ],
        ]

      ); //Responsive control end

      $element->end_controls_section();
    }
  }

  function custom_advanced($element, $section_id, $args)
  {


    if ('column' === $element->get_name() && 'section_border' == $section_id && element_ready_get_modules_option('advance_section')) {

      $element->start_controls_section(
        'element_ready_position_section',
        [
          'tab' => \Elementor\Controls_Manager::TAB_STYLE,
          'label' => esc_html__('Element Ready Advanced', 'element-ready-lite'),
        ]
      );

      $element->add_control(
        'element_ready_lite_advanced_overflow',
        [
          'label' => esc_html__('Overflow', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SELECT,
          'default' => '',
          'options' => [
            'hidden' => esc_html__('Hidden', 'element-ready-lite'),
            'scroll' => esc_html__('Scroll', 'element-ready-lite'),
            'visible' => esc_html__('Visible', 'element-ready-lite'),
            'auto' => esc_html__('Auto', 'element-ready-lite'),

          ],
          'selectors' => [
            '{{WRAPPER}}' => 'overflow: {{VALUE}};',
          ],
        ]
      );

      $element->add_group_control(
        \Elementor\Group_Control_Box_Shadow::get_type(),
        [
          'name' => 'element_ready_column_box_shadow',
          'label' => esc_html__('Box Shadow', 'element-ready-lite'),
          'selector' => '{{WRAPPER}}',
        ]
      );

      $element->add_group_control(
        \Elementor\Group_Control_Box_Shadow::get_type(),
        [
          'name' => 'element_ready_column_hover_box_shadow',
          'label' => esc_html__('Hover Box Shadow', 'element-ready-lite'),
          'selector' => '{{WRAPPER}}:hover',
        ]
      );

      $element->add_responsive_control(
        'element_ready_column_hover_transition',
        [
          'label' => esc_html__('Hover Transition time', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px'],
          'range' => [
            'px' => [
              'min' => 0,
              'max' => 5,
              'step' => 0.1,
            ],

          ],

          'selectors' => [
            '{{WRAPPER}}' => 'transition: {{SIZE}}s;',
          ],
        ]
      );

      $element->add_responsive_control(
        'element_ready_column_transition_type',
        [
          'label' => esc_html__('Transition Type', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SELECT,
          'default' => 'linear',
          'options' => [
            'linear' => esc_html__('Linear', 'element-ready-lite'),
            'ease' => esc_html__('Ease', 'element-ready-lite'),
            'ease-in' => esc_html__('Ease-in', 'element-ready-lite'),
            'ease-out' => esc_html__('Ease-out', 'element-ready-lite'),
            'ease-in-out' => esc_html__('Ease-in-out', 'element-ready-lite'),
            '' => esc_html__('none', 'element-ready-lite'),
          ],
          'selectors' => [
            '{{WRAPPER}}' => 'transition-timing-function: {{VALUE}};',
          ],
        ]
      );

      $element->add_control(
        'element_ready_column_popup_position',
        [
          'label' => esc_html__('Position', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
          'label_off' => esc_html__('Default', 'element-ready-lite'),
          'label_on' => esc_html__('Custom', 'element-ready-lite'),
          'return_value' => 'yes'
        ]
      );

      $element->start_popover();

      $element->add_responsive_control(
        'element_ready_column_position_type',
        [
          'label' => esc_html__('Position', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SELECT,
          'default' => '',
          'options' => [
            'fixed' => esc_html__('Fixed', 'element-ready-lite'),
            'absolute' => esc_html__('Absolute', 'element-ready-lite'),
            'relative' => esc_html__('Relative', 'element-ready-lite'),
            'sticky' => esc_html__('Sticky', 'element-ready-lite'),
            'static' => esc_html__('Static', 'element-ready-lite'),
            'inherit' => esc_html__('inherit', 'element-ready-lite'),
            '' => esc_html__('none', 'element-ready-lite'),
          ],
          'selectors' => [
            '{{WRAPPER}}' => 'position: {{VALUE}};',
          ],
        ]
      );

      $element->add_responsive_control(
        'element_ready_column_position_left',
        [
          'label' => esc_html__('Position Left', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px', '%'],
          'range' => [
            'px' => [
              'min' => -2100,
              'max' => 2100,
              'step' => 5,
            ],
            '%' => [
              'min' => -100,
              'max' => 100,
            ],
          ],

          'selectors' => [
            '{{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}};',
          ],
        ]
      );

      $element->add_responsive_control(
        'er_mainelement_ready_column_r_position_top',
        [
          'label' => esc_html__('Position Top', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px', '%'],
          'range' => [
            'px' => [
              'min' => -2100,
              'max' => 2100,
              'step' => 5,
            ],
            '%' => [
              'min' => -100,
              'max' => 100,
            ],
          ],

          'selectors' => [
            '{{WRAPPER}}' => 'top: {{SIZE}}{{UNIT}};',
          ],
        ]
      );

      $element->add_responsive_control(
        'element_ready_column_r_position_right',
        [
          'label' => esc_html__('Position Right', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px', '%'],
          'range' => [
            'px' => [
              'min' => -2100,
              'max' => 2100,
              'step' => 5,
            ],
            '%' => [
              'min' => -100,
              'max' => 100,
            ],
          ],

          'selectors' => [
            '{{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}};',
          ],
        ]
      );

      $element->add_responsive_control(
        'element_ready_column_r_position_bottom',
        [
          'label' => esc_html__('Position Bottom', 'element-ready-lite'),
          'type' => \Elementor\Controls_Manager::SLIDER,
          'size_units' => ['px', '%'],
          'range' => [
            'px' => [
              'min' => -2100,
              'max' => 2100,
              'step' => 5,
            ],
            '%' => [
              'min' => -100,
              'max' => 100,
            ],
          ],

          'selectors' => [
            '{{WRAPPER}}' => 'bottom: {{SIZE}}{{UNIT}};',
          ],
        ]
      );

      $element->end_popover();


      $element->end_controls_section();
    }
  }

  // The object is created from within the class itself
  // only if the class has no instance.
  public static function getInstance()
  {
    if (self::$instance == null) {
      self::$instance = new self();
    }
    return self::$instance;
  }
}

Element_Ready_Section::getInstance();
