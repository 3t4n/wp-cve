<?php

namespace ElementinvaderAddonsForElementor\Widgets;

use ElementinvaderAddonsForElementor\Core\Elementinvader_Base;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Typography;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class EliMap extends Elementinvader_Base {

    public $view_folder = 'map';
    public $inline_css = '';
    public $inline_css_tablet = '';
    public $inline_css_mobile = '';

    public function __construct($data = array(), $args = null) {
        parent::__construct($data, $args);
    }

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'eli-map';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Eli Map', 'elementinvader-addons-for-elementor');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-google-maps';
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function register_controls() {

        /* TAB_STYLE */
        $this->start_controls_section(
                'section_content',
                [
                    'label' => esc_html__('Primary Marker', 'elementinvader-addons-for-elementor'),
                ]
                
        );
        
        $this->add_control(
                'show_bydefault',
                [
                    'label' => esc_html__('Opened by default', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'elementinvader-addons-for-elementor'),
                    'label_off' => esc_html__('No', 'elementinvader-addons-for-elementor'),
                    'return_value' => 'true',
                    'default' => 'true',
                ]
        );

        $this->add_control(
            'section_content_title',
            [
                'label' => esc_html__('Title', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Title Example', 'elementinvader-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
                'section_content_text',
                [
                    'label' => esc_html__('Body', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__('Body Example', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'address',
                [
                    'label' => esc_html__('Address', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                ]
        );

        $this->add_control(
                'gps_lat',
                [
                    'label' => esc_html__('GPS lat', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '45.675243',
                ]
        );

        $this->add_control(
                'gps_lng',
                [
                    'label' => esc_html__('GPS lng', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '5.907848',
                ]
        );

        $this->add_responsive_control(
            'marker_icon',
            [
                'label' => esc_html__('Icon', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'default' => [
                    'value' => 'fa fa-home',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'marker_icon_image', 
            [
                    'label' => esc_html__( 'Upload custom image for marker', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'conf_custom_map_auto_center',
            [
                    'label' => esc_html__( 'Auto Centered Map By Markers', 'elementinvader-addons-for-elementor' ),
                    'description' => esc_html__( 'Generated center position, basic on results, note: z-index and defined center positon will be ignored if exists listings', 'elementinvader-addons-for-elementor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'none' => esc_html__( 'True', 'elementinvader-addons-for-elementor' ),
                    'block' => esc_html__( 'False', 'elementinvader-addons-for-elementor' ),
                    'render_type' => 'template',
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'map_type',
                                'operator' => '==',
                                'value' => 'open_street',
                            ]
                        ],
                    ],
            ]
        );
                    
        $this->add_responsive_control(
            'conf_custom_map_custer_disable',
            [
                    'label' => esc_html__( 'Cluster disable', 'elementinvader-addons-for-elementor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'none' => esc_html__( 'True', 'elementinvader-addons-for-elementor' ),
                    'block' => esc_html__( 'False', 'elementinvader-addons-for-elementor' ),
                    'render_type' => 'template',
                    'return_value' => 'yes',
                    'default' => '',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'map_type',
                                'operator' => '==',
                                'value' => 'open_street',
                            ]
                        ],
                    ],
            ]
        );
                    
        $this->add_responsive_control(
            'enable_default_map_markers',
            [
                    'label' => esc_html__( 'Enable default map markers', 'elementinvader-addons-for-elementor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'none' => esc_html__( 'True', 'elementinvader-addons-for-elementor' ),
                    'block' => esc_html__( 'False', 'elementinvader-addons-for-elementor' ),
                    'render_type' => 'template',
                    'return_value' => 'yes',
                    'default' => '',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'map_type',
                                'operator' => '==',
                                'value' => 'open_street',
                            ]
                        ],
                    ],
            ]
        );

        $this->add_responsive_control(
            'marker_size',
            [
                'label' => esc_html__('Marker Size', 'elementinvader-addons-for-elementor'),
                'description' => esc_html__('Will not affect png icons', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .elementinvader-addons-for-elementor .wl_marker-container' => 'height: {{SIZE}}px;width: {{SIZE}}px',
                    '{{WRAPPER}} .elementinvader-addons-for-elementor .wl_face i' => 'line-height: calc({{SIZE}}px * 1.1)',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'map_type',
                            'operator' => '==',
                            'value' => 'open_street',
                        ],
                        [
                            'name' => 'enable_default_map_markers',
                            'operator' => '!=',
                            'value' => 'yes',
                        ]
                    ],
                ],
            ]
        );
        
        $this->end_controls_section();
        
        /* TAB_STYLE */
        $this->start_controls_section(
                'section_markers',
                [
                    'label' => esc_html__('Additional Markers', 'elementinvader-addons-for-elementor'),
                ]
                
        );

        if(true) {

            $repeater = new Repeater();
            
            $repeater->start_controls_tabs( 'markers' );

            $repeater->add_control(
                    'gps_lat',
                    [
                        'label' => esc_html__('GPS lat', 'elementinvader-addons-for-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'default' => '45.675243',
                    ]
            );

            $repeater->add_control(
                    'gps_lng',
                    [
                        'label' => esc_html__('GPS lng', 'elementinvader-addons-for-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'default' => '5.907848',
                    ]
            );

            $repeater->add_control(
                    'marker_icon',
                    [
                        'label' => esc_html__('Icon', 'elementinvader-addons-for-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'default' => 'fa fa-home',
                    ]
            );
            
            $repeater->add_control(
                    'title',
                    [
                        'label' => esc_html__('Title', 'elementinvader-addons-for-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'default' => esc_html__('Title Example', 'elementinvader-addons-for-elementor'),
                    ]
            );
            
            $repeater->add_control(
                    'text',
                    [
                        'label' => esc_html__('Body', 'elementinvader-addons-for-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'default' => esc_html__('Body Example', 'elementinvader-addons-for-elementor'),
                    ]
            );

            $repeater->add_control(
                    'marker_icon_image', 
                    [
                            'label' => esc_html__( 'Choose Image For Custom Marker', 'plugin-domain' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                    ]
            );

            $repeater->end_controls_tabs();
            
            $this->add_control(
                'markers',
                [
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [],
                    'title_field' => '{{{ title }}}',
                ]
            );
            /* end form field content */
        }

        $this->end_controls_section();
        
        $this->start_controls_section(
                'section_elements',
                [
                    'label' => esc_html__('Advanced options', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'map_type',
                [
                    'label' => 'Map Type',
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'open_street' => esc_html__('Open Street', 'elementinvader-addons-for-elementor'),
                        'google' => esc_html__('Google', 'elementinvader-addons-for-elementor'),
                    ],
                    'default' => 'open_street',
                    'description' => esc_html__('If visible grey background, please change height of map', 'elementinvader-addons-for-elementor'),
                ]
        );


        $this->add_control(
            'center_gps_lat',
            [
                'label' => esc_html__('Center GPS lat', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
                'center_gps_lng',
                [
                    'label' => esc_html__('Center GPS lng', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                ]
        );

        $this->add_responsive_control(
                'map_height',
                [
                    'label' => esc_html__('Height', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 300,
                            'max' => 1500,
                        ],
                    ],
                    'render_type' => 'template',
                    'default' => [
                        'size' => 350,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .sw_map_box' => 'height: {{SIZE}}px',
                    ],
                    'separator' => 'after',
                ]
        );

        $this->add_control(
                'zoom_index',
                [
                    'label' => esc_html__('Zoom Index', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'max' => 18,
                        ],
                    ],
                    'render_type' => 'template',
                    'default' => [
                        'size' => 7,
                    ],
                ]
        );

        $this->end_controls_section();

        /* TAB_STYLE */
        $this->start_controls_section(
                'section_google_style',
                [
                    'label' => esc_html__('Google Map Styles', 'elementinvader-addons-for-elementor'),
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'map_type',
                                'operator' => '==',
                                'value' => 'google',
                            ]
                        ],
                    ],
                ]
        );

        $this->add_control(
                'google_map_styes',
                [
                    'label' => esc_html__('Type', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Default', 'elementinvader-addons-for-elementor'),
                        'custom' => esc_html__('Custom', 'elementinvader-addons-for-elementor'),
                        '[{"elementType":"geometry","stylers":[{"color":"#1d2c4d"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#8ec3b9"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#1a3646"}]},{"featureType":"administrative.country","elementType":"geometry.stroke","stylers":[{"color":"#4b6878"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#64779e"}]},{"featureType":"administrative.province","elementType":"geometry.stroke","stylers":[{"color":"#4b6878"}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"color":"#334e87"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#023e58"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#283d6a"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#6f9ba5"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#023e58"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#3C7680"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#304a7d"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#98a5be"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#2c6675"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#255763"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#b0d5ce"}]},{"featureType":"road.highway","elementType":"labels.text.stroke","stylers":[{"color":"#023e58"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#98a5be"}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"transit.line","elementType":"geometry.fill","stylers":[{"color":"#283d6a"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#3a4762"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#0e1626"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#4e6d70"}]}]' => esc_html__('Aubergine', 'elementinvader-addons-for-elementor'),
                        '[{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]' => esc_html__('Silver', 'elementinvader-addons-for-elementor'),
                        '[{"elementType":"geometry","stylers":[{"color":"#242f3e"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#242f3e"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#746855"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#263c3f"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#6b9a76"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#38414e"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#212a37"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#9ca5b3"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#746855"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#1f2835"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#f3d19c"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#2f3948"}]},{"featureType":"transit.station","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#17263c"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#515c6d"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#17263c"}]}]' => esc_html__('Night', 'elementinvader-addons-for-elementor'),
                        '[{"elementType":"geometry","stylers":[{"color":"#ebe3cd"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#523735"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f1e6"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#c9b2a6"}]},{"featureType":"administrative.land_parcel","elementType":"geometry.stroke","stylers":[{"color":"#dcd2be"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#ae9e90"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#93817c"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#a5b076"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#447530"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#f5f1e6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#fdfcf8"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#f8c967"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#e9bc62"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#e98d58"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.stroke","stylers":[{"color":"#db8555"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#806b63"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"transit.line","elementType":"labels.text.fill","stylers":[{"color":"#8f7d77"}]},{"featureType":"transit.line","elementType":"labels.text.stroke","stylers":[{"color":"#ebe3cd"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#b9d3c2"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#92998d"}]}]' => esc_html__('Retro', 'elementinvader-addons-for-elementor'),
                        '[{"featureType":"poi","stylers":[{"visibility":"off"}]},{"featureType":"all","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]}]' => esc_html__('Hide Features', 'elementinvader-addons-for-elementor'),
                        '[{"elementType":"geometry","stylers":[{"color":"#212121"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#212121"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#757575"}]},{"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"administrative.land_parcel","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#181818"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"poi.park","elementType":"labels.text.stroke","stylers":[{"color":"#1b1b1b"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#2c2c2c"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#8a8a8a"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#373737"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#3c3c3c"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#4e4e4e"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#3d3d3d"}]}]' => esc_html__('Black', 'elementinvader-addons-for-elementor'),
                        '[{"elementType":"geometry","stylers":[{"color":"#1d2c4d"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#8ec3b9"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#1a3646"}]},{"featureType":"administrative.country","elementType":"geometry.stroke","stylers":[{"color":"#4b6878"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#64779e"}]},{"featureType":"administrative.province","elementType":"geometry.stroke","stylers":[{"color":"#4b6878"}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"color":"#334e87"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#023e58"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#283d6a"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#6f9ba5"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#023e58"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#3C7680"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#304a7d"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#98a5be"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#2c6675"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#255763"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#b0d5ce"}]},{"featureType":"road.highway","elementType":"labels.text.stroke","stylers":[{"color":"#023e58"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#98a5be"}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"transit.line","elementType":"geometry.fill","stylers":[{"color":"#283d6a"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#3a4762"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#0e1626"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#4e6d70"}]}]' => esc_html__('Aubergine', 'elementinvader-addons-for-elementor'),
                        '[{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}]' => esc_html__('Grey Light', 'elementinvader-addons-for-elementor'),
                        '[{"featureType":"water","elementType":"all","stylers":[{"color":"#3b5998"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"all","elementType":"all","stylers":[{"hue":"#3b5998"},{"saturation":-22}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"on"},{"color":"#f7f7f7"},{"saturation":10},{"lightness":76}]},{"featureType":"landscape.natural","elementType":"all","stylers":[{"color":"#f7f7f7"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"color":"#8b9dc3"}]},{"featureType":"administrative.country","elementType":"geometry.stroke","stylers":[{"visibility":"simplified"},{"color":"#3b5998"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"on"},{"color":"#8b9dc3"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"},{"color":"#8b9dc3"}]},{"featureType":"transit.line","elementType":"all","stylers":[{"invert_lightness":false},{"color":"#ffffff"},{"weight":0.43}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#8b9dc3"}]},{"featureType":"administrative","elementType":"labels.icon","stylers":[{"visibility":"on"},{"color":"#3b5998"}]}]' => esc_html__('Facebook', 'elementinvader-addons-for-elementor'),
                        '[{"featureType":"all","elementType":"all","stylers":[{"invert_lightness":true}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#191D24"},{"lightness":5}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"invert_lightness":true},{"weight":0.15},{"hue":"#5676ad"},{"saturation":0},{"lightness":-47}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#7C8AA3"},{"lightness":-53}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffdd00"},{"saturation":-23},{"lightness":-70}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"color":"#191D24"},{"lightness":-6}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#3B3C82"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"saturation":-64},{"lightness":-22}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#7C8AA3"},{"weight":1.19},{"lightness":-100}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"saturation":0},{"lightness":-6}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"off"},{"lightness":0}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"invert_lightness":true},{"saturation":-52},{"lightness":-30}]},{"featureType":"poi.school","elementType":"labels","stylers":[{"visibility":"on"},{"hue":"#00ff26"},{"saturation":51},{"lightness":-20}]},{"featureType":"transit","elementType":"geometry.fill","stylers":[{"color":"#08b1ff"},{"saturation":-72},{"lightness":-8}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"saturation":21},{"lightness":21}]},{"featureType":"administrative.neighborhood","elementType":"all","stylers":[{"lightness":19}]},{"featureType":"poi.sports_complex","elementType":"labels.text.fill","stylers":[{"saturation":-60},{"lightness":-21}]},{"featureType":"poi.attraction","elementType":"labels.text","stylers":[{"saturation":-60},{"lightness":-31}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"saturation":-45}]},{"featureType":"poi.sports_complex","elementType":"geometry.fill","stylers":[{"lightness":6}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"lightness":-32}]},{"featureType":"poi.medical","elementType":"geometry.fill","stylers":[{"saturation":-57},{"lightness":10}]},{"featureType":"poi.school","elementType":"geometry.fill","stylers":[{"lightness":6}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#5cff0a"},{"weight":1.12},{"saturation":-11},{"lightness":-35}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"},{"saturation":-100},{"lightness":-100}]},{"featureType":"road.local","elementType":"labels.icon","stylers":[{"visibility":"off"}]}]' => esc_html__('Dark', 'elementinvader-addons-for-elementor'),
                        '[{"featureType":"all","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#eaeaeb"},{"lightness":60}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#98d1a8"},{"saturation":-100},{"lightness":38}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#efefef"},{"lightness":60}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#cecece"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#b9cf3a"},{"saturation":-90},{"lightness":60}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":92}]},{"featureType":"administrative.province","elementType":"geometry.stroke","stylers":[{"color":"#b9cf3a"},{"weight":1.48},{"saturation":-100}]},{"featureType":"administrative.country","elementType":"geometry.stroke","stylers":[{"color":"#b9cf3a"},{"saturation":-100}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"administrative.province","elementType":"labels.text","stylers":[{"visibility":"on"},{"color":"#666666"}]},{"featureType":"administrative.province","elementType":"labels.text.stroke","stylers":[{"visibility":"off"},{"weight":0}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#616669"},{"saturation":-24},{"lightness":33}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#98d1a8"},{"saturation":-62},{"lightness":18}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"visibility":"on"},{"invert_lightness":true},{"hue":"#98d1a8"},{"lightness":28},{"gamma":1.55}]}]' => esc_html__('Clinique', 'elementinvader-addons-for-elementor'),
                        '[{"featureType":"all","elementType":"geometry","stylers":[{"hue":"#256bc2"},{"saturation":48},{"lightness":8}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"labels.text","stylers":[{"visibility":"off"}]}]' => esc_html__('Indigo', 'elementinvader-addons-for-elementor'),
                        '[{"featureType":"landscape.man_made","elementType":"all","stylers":[{"color":"#fff9e8"}]},{"featureType":"poi.medical","elementType":"all","stylers":[{"color":"#9b978d"}]},{"featureType":"poi.park","elementType":"all","stylers":[{"color":"#d3cec0"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#e0dac9"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.fill","stylers":[{"color":"#cec9bb"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.stroke","stylers":[{"color":"#000000"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#cec9bb"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#817d74"},{"weight":0.1}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#cec9bb"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#dedace"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry.fill","stylers":[{"color":"#fff9e8"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#fff9e8"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"color":"#fff9e8"}]},{"featureType":"landscape.natural.landcover","elementType":"labels.text","stylers":[{"color":"#575757"}]},{"featureType":"poi.attraction","elementType":"geometry.fill","stylers":[{"color":"#d3cec0"}]},{"featureType":"poi.sports_complex","elementType":"geometry.fill","stylers":[{"color":"#d3cec0"}]},{"featureType":"poi.school","elementType":"geometry.fill","stylers":[{"color":"#d3cec0"}]},{"featureType":"poi.place_of_worship","elementType":"geometry.fill","stylers":[{"color":"#d3cec0"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#d3cec0"}]},{"featureType":"poi.business","elementType":"geometry.fill","stylers":[{"color":"#d3cec0"}]},{"featureType":"poi.place_of_worship","elementType":"geometry.fill","stylers":[{"color":"#d3cec0"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#d3cec0"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#525252"}]}]' => esc_html__('Beige', 'elementinvader-addons-for-elementor'),
                        '[{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2e5d4"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]}]' => esc_html__('Pale Down', 'elementinvader-addons-for-elementor'),
                        '[{"featureType":"all","elementType":"all","stylers":[{"hue":"#16a085"},{"saturation":0}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"simplified"},{"lightness":100}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]}]' => esc_html__('Turqoise', 'elementinvader-addons-for-elementor'),
                        '[{"featureType":"water","elementType":"all","stylers":[{"color":"#19a0d8"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"},{"weight":6}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#e85113"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efe9e4"},{"lightness":-40}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#efe9e4"},{"lightness":-20}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"lightness":-100}]},{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#efe9e4"},{"lightness":20}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"lightness":-100}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"hue":"#11ff00"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"hue":"#4cff00"},{"saturation":58}]},{"featureType":"poi","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#f0e4d3"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#efe9e4"},{"lightness":-25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#efe9e4"},{"lightness":-10}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"simplified"}]}]' => esc_html__('Bright', 'elementinvader-addons-for-elementor'),
                    ],
                    'default' => '',
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
        );

        $this->add_control(
                'google_map_key',
                [
                    'label' => esc_html__('Google Map Api Key', 'elementinvader-addons-for-elementor'),
                    'description' => esc_html__('After added key, required reload page', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXTAREA,
                    'default' => '',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'google_map_styes',
                                'operator' => '!=',
                                'value' => '',
                            ]
                        ],
                    ],
                ]
        );


        $this->add_control(
                'google_map_styes_string',
                [
                    'label' => esc_html__('Custom Json', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXTAREA,
                    'default' => '',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'google_map_styes',
                                'operator' => '==',
                                'value' => 'custom',
                            ]
                        ],
                    ],
                ]
        );


        $this->end_controls_section();

        /* TAB_STYLE */
        $this->start_controls_section(
                'section_openstreet_style',
                [
                    'label' => esc_html__('Openstreet Map Styles', 'elementinvader-addons-for-elementor'),
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'map_type',
                                'operator' => '==',
                                'value' => 'open_street',
                            ]
                        ],
                    ],
                ]
        );

        $this->add_control(
                'openstreet_map_styes',
                [
                    'label' => esc_html__('Type', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Default', 'elementinvader-addons-for-elementor'),
                        'custom' => esc_html__('Custom', 'elementinvader-addons-for-elementor'),
                        'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png' => esc_html__('Osmde', 'elementinvader-addons-for-elementor'),
                        'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png' => esc_html__('OpenTopoMap', 'elementinvader-addons-for-elementor'),
                        'https://{s}.tile.thunderforest.com/cycle/{z}/{x}/{y}.png' => esc_html__('OpenCycleMap', 'elementinvader-addons-for-elementor'),
                        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}' => esc_html__('WorldImagery', 'elementinvader-addons-for-elementor'),
                        'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png' => esc_html__('Carto DarkMatter', 'elementinvader-addons-for-elementor'),
                        'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png' => esc_html__('Carto Voyager', 'elementinvader-addons-for-elementor'),
                        'https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_all/{z}/{x}/{y}{r}.png' => esc_html__('Maptiler dark (demo)', 'elementinvader-addons-for-elementor'),
                        'https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png' => esc_html__('Light map', 'elementinvader-addons-for-elementor'),
                    ],
                    'default' => '',
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
        );
        
        $this->add_control(
            'conf_custom_map_style_self',
            [
                'label' => esc_html__('Link to custom Map Style', 'elementinvader-addons-for-elementor'),
                'description' => esc_html__( 'You can add some custom map by link example https://leaflet-extras.github.io/leaflet-providers/preview/ or create your custom style and put link for example on maps.cloudmade.com/editor', 'elementinvader-addons-for-elementor' ),
                'type' => Controls_Manager::TEXTAREA,
                'render_type' => 'template',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'openstreet_map_styes',
                            'operator' => '==',
                            'value' => 'custom',
                        ]
                    ],
                ]
            ]
        );

        $this->end_controls_section();

        /* TAB_STYLE */
        $this->start_controls_section(
                'section_form_style',
                [
                    'label' => esc_html__('Map', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'map_type',
                                'operator' => '==',
                                'value' => 'open_street',
                            ],
                            [
                                'name' => 'enable_default_map_markers',
                                'operator' => '!=',
                                'value' => 'yes',
                            ]
                        ],
                    ],
                ]
        );


        $this->add_control(
                'marker_label',
                [
                    'label' => esc_html__('Marker', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
            'marker_icon_size',
            [
                'label' => esc_html__('Icon Size', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 50,
                    ],
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .elementinvader-addons-for-elementor .wl_face i' => 'font-size: {{SIZE}}px',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'map_type',
                            'operator' => '==',
                            'value' => 'open_street',
                        ]
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'marker_icon_top_padding',
            [
                'label' => esc_html__('Icon Top Padding', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .elementinvader-addons-for-elementor .wl_face i' => 'top: {{SIZE}}px',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'map_type',
                            'operator' => '==',
                            'value' => 'open_street',
                        ]
                    ],
                ],
            ]
        );
             
        $this->add_control(
            'marker_icon_hide',
            [
                    'label' => esc_html__( 'Hide Icon', 'eli-blocks' ),
                    'type' => Controls_Manager::SWITCHER,
                    'none' => esc_html__( 'Hide', 'eli-blocks' ),
                    'block' => esc_html__( 'Show', 'eli-blocks' ),
                    'return_value' => 'none',
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl_face i' => 'display: {{VALUE}};',
                    ],
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'map_type',
                                'operator' => '==',
                                'value' => 'open_street',
                            ]
                        ],
                    ],
            ]
        );

        $this->start_controls_tabs('marker_button_style');
        $this->start_controls_tab(
                'marker',
                [
                    'label' => esc_html__('Normal', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'marker_color',
                [
                    'label' => esc_html__('Marker Border', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl_marker-card:before' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'marker_border',
                [
                    'label' => esc_html__('Marker Background', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl_marker-card:after' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'marker_color_text',
                [
                    'label' => esc_html__('Marker Icon Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wl_face i' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'marker_hover',
                [
                    'label' => esc_html__('Hover', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'marker_color_hover',
                [
                    'label' => esc_html__('Marker Border', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementinvader-addons-for-elementor .wl_marker-container:hover .wl_marker-card:before' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
            'marker_bk_hover',
            [
                'label' => esc_html__('Marker Background', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementinvader-addons-for-elementor .wl_marker-container:hover .wl_marker-card:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
                'marker_color_text_hover',
                [
                    'label' => esc_html__('Marker Icon', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wl_marker-container:hover .wl_face.back i, {{WRAPPER}} .wl_marker-container:hover .wl_face i' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'marker_effect_duration',
                [
                    'label' => esc_html__('Transition Duration', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wl_map-marker-container.clicked .wl_face.front, .wl_marker-container:hover .wl_face.front' => 'transition-duration: {{SIZE}}ms',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
        
            /* marker */
            $this->start_controls_section(
                    'styles_cluster',
                    [
                        'label' => esc_html__('Cluster', 'elementinvader-addons-for-elementor'),
                        'tab' => Controls_Manager::TAB_STYLE,
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'map_type',
                                    'operator' => '==',
                                    'value' => 'open_street',
                                ]
                            ],
                        ],
                    ]
            );
            
            $this->add_control(
                'styles_cluster_color',
                [
                    'label' => esc_html__('Cluster Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .marker-cluster-small div' => 'background-color: {{VALUE}};',
                    ],
                ]
            );
            
            $this->add_control(
                'styles_cluster_color_border',
                [
                    'label' => esc_html__('Cluster Color Border', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .marker-cluster' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .marker-cluster div::before' => 'border: 6px solid {{VALUE}}; box-shadow: inset 0 0 0 4px {{VALUE}};',
                    ],
                ]
            );
            
            $this->add_control(
                'styles_cluster_color_text',
                [
                    'label' => esc_html__('Cluster Color Text', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .marker-cluster-small' => 'color: {{VALUE}};',
                    ],
                ]
            );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name' => 'styles_cluster_color_font',
                        'selector' => '{{WRAPPER}} .marker-cluster-small span',
                ]
            );
    
            $this->end_controls_section();
        
        /* TAB_STYLE */
        $this->start_controls_section(
                'section_map_infobox',
                [
                    'label' => esc_html__('Map Infobox', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        
        $this->add_control(
                'section_map_infobox_body',
                [
                    'label' => esc_html__('Infobox', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $object = [
            'normal' => '{{WRAPPER}} .elementinvader-addons-for-elementor .eli_infobox',
        ];
        $this->generate_renders_tabs($object, 'section_map_infobox_s', ['align','background','border','border_radius','padding','shadow']);
         /*
        $this->add_control(
                'section_map_infobox_width',
                [
                    'label' => esc_html__('Width', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'min' => 60,
                            'max' => 450,
                        ],
                    ],
                ]
        );*/
        
        $this->add_control(
                'section_map_infobox_title',
                [
                    'label' => esc_html__('Title', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $object = [
            'normal' => '{{WRAPPER}} .elementinvader-addons-for-elementor .eli_infobox .eli_infobox_title',
        ];
        $this->generate_renders_tabs($object, 'section_map_infobox_title_s','text');
       
        
        $this->add_control(
                'section_map_infobox_text',
                [
                    'label' => esc_html__('Body', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $object = [
            'normal' => '{{WRAPPER}} .elementinvader-addons-for-elementor .eli_infobox .eli_infobox_text',
        ];
        $this->generate_renders_tabs($object, 'section_map_infobox_text_s', 'text');
        
        $this->add_control(
                'section_map_infobox_close',
                [
                    'label' => esc_html__('Close Button', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        
        $this->add_control(
                'card_items_hide',
                [
                        'label' => esc_html__( 'Hide Element', 'eli-blocks' ),
                        'type' => Controls_Manager::SWITCHER,
                        'none' => esc_html__( 'Hide', 'eli-blocks' ),
                        'block' => esc_html__( 'Show', 'eli-blocks' ),
                        'return_value' => 'none',
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .elementinvader-addons-for-elementor .leaflet-container a.leaflet-popup-close-button' => 'display: {{VALUE}};',
                        ],
                ]
        );

        $object = [
            'normal' => '{{WRAPPER}} .elementinvader-addons-for-elementor .leaflet-container a.leaflet-popup-close-button',
            'hover' => '{{WRAPPER}} .elementinvader-addons-for-elementor .leaflet-container a.leaflet-popup-close-button%1$s',
        ];
        $this->generate_renders_tabs($object, 'section_map_infobox_close_s', ['background','border','color', 'border_radius','padding','shadow']);
         
        $this->end_controls_section();

        parent::register_controls();
    }

    protected function render() {
        parent::render();
        $settings = $this->get_settings();
     

        ?>
        <div class="elementinvader-addons-for-elementor elementor-clickable elementor-custom-embed" id="eli_<?php echo esc_html($this->get_id_int()); ?>">
            <div class="eli-container">
                <?php
                if (Plugin::$instance->editor->is_edit_mode()):
                    ?>
                    <?php echo $this->generate_map(['settings' => $settings, 'is_edit_mode' => true]); ?>
                <?php else: ?>
                    <?php echo $this->generate_map(['settings' => $settings]); ?>
        <?php endif; ?>
            </div>
        </div>
        <?php
        $this->add_page_settings_css();
    }

    private function generate_map($obj = []) {
        $output = $this->view('map_layout', $obj);
        return $output;
    }

    function _infowindow_content($listing, $custom_data = array()) {
        $CI = & get_instance();

        $output = '';

        if (!isset($custom_data['show_details']))
            $custom_data['show_details'] = true;

        $CI->load->add_package_path(ELEMENTOR_WLISTINGS_PATH . '/');
        $output = $CI->load->view('map/infowindow.php', array_merge($CI->data, array('listing' => $listing), $custom_data), true);
        return escapeJavaScriptText($output);
    }

        
    public function enqueue_styles_scripts() {

        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
        if (true) {
            wp_enqueue_script('leaflet', plugins_url( '/assets/libs/leaflet/leaflet.js', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__ ), array('jquery'));
            wp_enqueue_script('leaflet-cluster', plugins_url( '/assets/libs/leaflet/leaflet.markercluster.js', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__ ), array('jquery'));
            wp_enqueue_style('leaflet', plugins_url( '/assets/libs/leaflet/leaflet.css', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__ ));
            wp_enqueue_style('leaflet-cluster-def', plugins_url( '/assets/libs/leaflet/markerCluster.Default.css', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__ ));
            wp_enqueue_style('leaflet-cluster', plugins_url( '/assets/libs/leaflet/MarkerCluster.css', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__ ));
        } else {
            wp_deregister_script('maps-google-api-js');
            wp_register_script('maps-google-api-js', $protocol . "://maps.google.com/maps/api/js?libraries=places,geometry" . $api_key_part . "", array('jquery'));
            wp_enqueue_script('maps-google-api-js');
        }
    }

}
