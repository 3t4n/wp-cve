<?php
use Elementor\Modules\DynamicTags\Module as TagsModule;

class VAPFEM_Video_Player extends \Elementor\Widget_Base {

    public function get_name() {
        return "vapfem_video_player";
    }

    public function get_title() {
        return esc_html__( "Video Player", 'vapfem' );
    }

    public function get_icon() {
        return 'az_icon eicon-youtube';
    }

    public function get_categories() {
        return array( 'general' );
    }
    
    public function get_script_depends() {
        return [
            'plyr',
            'plyr-polyfilled',
            'vapfem-main',
        ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'General Options', 'vapfem' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'video_type',
            [
                'label' => esc_html__( 'Video Type', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube'  => esc_html__( 'Youtube', 'vapfem' ),
                    'vimeo' => esc_html__( 'Vimeo', 'vapfem' ),
                    'html5' => esc_html__( 'HTML5', 'vapfem' ),
                ],

            ]
        );

        $this->add_control(
            'youtube_video_id',
            [
                'label' => esc_html__( 'Youtube Video ID', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'bTqVqk7FSmY', 'vapfem' ),
                'placeholder' => esc_html__( 'Put your video id here', 'vapfem' ),
                'separator' => 'before',
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'video_type'    =>  'youtube',
                ]
            ]
        );


        $this->add_control(
            'vimeo_video_id',
            [
                'label' => esc_html__( 'Vimeo Video ID', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '76979871', 'vapfem' ),
                'placeholder' => esc_html__( 'Put your video id here', 'vapfem' ),
                'separator' => 'before',
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'video_type'    =>  'vimeo',
                ]
            ]
        );

        $this->add_control(
            'custom_poster',
            [
                'label' => esc_html__( 'Add Custom Poster', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'vapfem' ),
                'label_off' => esc_html__( 'No', 'vapfem' ),
                'return_value' => 'true',
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'poster',
            [
                'label' => esc_html__( 'Custom Poster For Video', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'custom_poster'    =>  'true',
                ]
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'src_type',
            [
                'label' => esc_html__( 'Video Source', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'link',
                'options' => [
                    'upload' => esc_html__( 'Upload Video', 'vapfem' ),
                    'link' => esc_html__( 'Put Video Link', 'vapfem' ),
                ],
            ]
        );
        $repeater->add_control(
            'video_upload',
            [
                'label' => esc_html__( 'Upload Video', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                    'categories' => [
                        TagsModule::MEDIA_CATEGORY,
                    ],
                ],
                'media_type' => 'video',
                'condition' => [
                    'src_type'    =>  'upload',
                ]
            ]
        );


        $repeater->add_control(
            'video_link',
            [
                'label' => esc_html__( 'Video Link', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'vapfem' ),
                'show_external' => false,
                'default' => [
                    'url' => 'https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4',
                    'is_external' => false,
                    'nofollow' => false,
                ],
                'condition' => [
                    'src_type'    =>  'link',
                ]
            ]
        );

        $repeater->add_control(
            'video_size',
            [
                'label' => esc_html__( 'Video Size', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Select', 'vapfem' ),
                    '240' => esc_html__( '240', 'vapfem' ),
                    '360' => esc_html__( '360', 'vapfem' ),
                    '480' => esc_html__( '480', 'vapfem' ),
                    '576' => esc_html__( '576', 'vapfem' ),
                    '720' => esc_html__( '720', 'vapfem' ),
                    '1080' => esc_html__( '1080', 'vapfem' ),
                    '1440' => esc_html__( '1440', 'vapfem' ),
                    '2160' => esc_html__( '2160', 'vapfem' ),
                    '2880' => esc_html__( '2880', 'vapfem' ),
                    '4320'  => esc_html__( '4320', 'vapfem' ),
                ],
            ]
        );

        $this->add_control(
            'video_list',
            [
                'label' => esc_html__( 'Video List', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'video_link' => 'https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4',
                        'video_size' => esc_html__( '576', 'vapfem' ),
                    ],
                    [
                        'video_link' => 'ttps://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-720p.mp4',
                        'video_size' => esc_html__( '720', 'vapfem' ),
                    ],
                    [
                        'video_link' => 'ttps://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-1080p.mp4',
                        'video_size' => esc_html__( '1080', 'vapfem' ),
                    ],
                ],
                'separator' => 'before',
                'condition' => [
                    'video_type'    =>  'html5',
                ]
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => __('Autoplay varies for each user by an intelligent system of the browsers. If you experience Autoplay does not work from your browser. Enable the "Muted" option below. <br><br>Muted autoplay is always allowed.', 'vapfem'),
                'label_on' => esc_html__( 'Yes', 'vapfem' ),
                'label_off' => esc_html__( 'No', 'vapfem' ),
                'return_value' => 'true',
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'muted',
            [
                'label' => esc_html__( 'Muted', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__('Enable this to start playback muted. This is also usefull if you experience autoplay is not working from your browser.', 'vapfem'),
                'label_on' => esc_html__( 'Yes', 'vapfem' ),
                'label_off' => esc_html__( 'No', 'vapfem' ),
                'return_value' => 'true',
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => esc_html__( 'Loop', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__('Loop the current media. ', 'vapfem'),
                'label_on' => esc_html__( 'Yes', 'vapfem' ),
                'label_off' => esc_html__( 'No', 'vapfem' ),
                'return_value' => 'true',
                'default' => '',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'volume',
            [
                'label' => esc_html__( 'Initial Volume', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' =>1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'click_to_play',
            [
                'label' => esc_html__( 'Click To Play', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description'   => esc_html__('Click (or tap) of the video container will toggle play/pause.','vapfem'),
                'label_on' => esc_html__( 'Enable', 'vapfem' ),
                'label_off' => esc_html__( 'Disable', 'vapfem' ),
                'return_value' => 'true',
                'default' => 'true',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'invert_time',
            [
                'label' => esc_html__( 'Display Time As Countdown', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__('Display the current time as a countdown rather than an incremental counter.', 'vapfem'),
                'label_on' => esc_html__( 'Yes', 'vapfem' ),
                'label_off' => esc_html__( 'No', 'vapfem' ),
                'return_value' => 'true',
                'default' => 'true',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'seek_time',
            [
                'label' => esc_html__( 'Seek Time', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'description' => esc_html__('The time, in seconds, to seek when a user hits fast forward or rewind.', 'vapfem'),
                'min' => 5,
                'max' => 100,
                'step' => 1,
                'default' => 10,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'hide_controls',
            [
                'label' => esc_html__( 'Hide Control Icons After 2s', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__('Hide video controls automatically after 2s of no mouse or focus movement,', 'vapfem'),
                'label_on' => esc_html__( 'Yes', 'vapfem' ),
                'label_off' => esc_html__( 'No', 'vapfem' ),
                'return_value' => 'true',
                'default' => 'false',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'reset_on_end',
            [
                'label' => esc_html__( 'Back To Start After End', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__('Back to start after end of playing', 'vapfem'),
                'label_on' => esc_html__( 'Yes', 'vapfem' ),
                'label_off' => esc_html__( 'No', 'vapfem' ),
                'return_value' => 'true',
                'default' => 'false',
                'separator' => 'before',
                'condition' => [
                    'video_type' => 'html5'
                ]
            ]
        );

        $this->add_control(
            'keyboard_focused',
            [
                'label' => esc_html__( 'Enable Keyboard Shortcuts On Focus', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__('Enable keyboard shortcuts for focused players only', 'vapfem'),
                'label_on' => esc_html__( 'Yes', 'vapfem' ),
                'label_off' => esc_html__( 'No', 'vapfem' ),
                'return_value' => 'true',
                'default' => 'true',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'keyboard_global',
            [
                'label' => esc_html__( 'Enable Keyboard Shortcuts Globally', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'vapfem' ),
                'label_off' => esc_html__( 'No', 'vapfem' ),
                'return_value' => 'true',
                'default' => 'false',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tooltips_controls',
            [
                'label' => esc_html__( 'Display Control Labels', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__('Display control labels as tooltips on :hover & :focus', 'vapfem'),
                'label_on' => esc_html__( 'Yes', 'vapfem' ),
                'label_off' => esc_html__( 'No', 'vapfem' ),
                'return_value' => 'true',
                'default' => 'false',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tooltips_seek',
            [
                'label' => esc_html__( 'Display Seek Tooltip', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__('Display a seek tooltip to indicate on click where the media would seek to.', 'vapfem'),
                'label_on' => esc_html__( 'Yes', 'vapfem' ),
                'label_off' => esc_html__( 'No', 'vapfem' ),
                'return_value' => 'true',
                'default' => 'true',
                'separator' => 'before',
            ]
        );



        $this->add_control(
            'fullscreen_enabled',
            [
                'label' => esc_html__( 'Enable Fullscreen Toggle', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => esc_html__('Enable fullscreen when double click on the player', 'vapfem'),
                'label_on' => esc_html__( 'Yes', 'vapfem' ),
                'label_off' => esc_html__( 'No', 'vapfem' ),
                'return_value' => 'true',
                'default' => 'true',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'speed_selected',
            [
                'label' => esc_html__( 'Initial Speed', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'speed_1',
                'options' => [
                    'speed_.5'  => esc_html__( '0.5', 'vapfem' ),
                    'speed_.75' => esc_html__( '0.75', 'vapfem' ),
                    'speed_1' => esc_html__( '1', 'vapfem' ),
                    'speed_1.25' => esc_html__( '1.25', 'vapfem' ),
                    'speed_1.5' => esc_html__( '1.5', 'vapfem' ),
                ],
                'separator' => 'before',
                'condition' => [
                    'video_type'    => 'html5'
                ]
            ]
        );

        $this->add_control(
            'quality_default',
            [
                'label' => esc_html__( 'Initial Quality', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '576',
                'options' => [
                    '240' => esc_html__( '240', 'vapfem' ),
                    '360' => esc_html__( '360', 'vapfem' ),
                    '480' => esc_html__( '480', 'vapfem' ),
                    '576' => esc_html__( '576', 'vapfem' ),
                    '720' => esc_html__( '720', 'vapfem' ),
                    '1080' => esc_html__( '1080', 'vapfem' ),
                    '1440' => esc_html__( '1440', 'vapfem' ),
                    '2160' => esc_html__( '2160', 'vapfem' ),
                    '2880' => esc_html__( '2880', 'vapfem' ),
                    '4320'  => esc_html__( '4320', 'vapfem' ),
                ],
                'separator' => 'before',
                'condition' => [
                    'video_type' => 'html5'
                ]
            ]
        );

        $this->add_control(
            'custom_ratio',
            [
                'label' => esc_html__( 'Enable Custom Ratio', 'vapfem' ),
                'description' => esc_html__( 'Force an aspect ratio.', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'vapfem' ),
                'label_off' => esc_html__( 'No', 'vapfem' ),
                'return_value' => 'true',
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'ratio',
            [
                'label' => esc_html__( 'Ratio', 'vapfem' ),
                'description' => esc_html__( 'The format is \'w:h\' - e.g. 16:9 or 4:3 or other', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder'   =>  esc_html__( '16:9', 'vapfem' ),
                'condition' => [
                    'custom_ratio' => 'true'
                ]

            ]
        );

        $this->add_control(
            'controls',
            [
                'label' => esc_html__( 'Control Options', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'description'   =>  esc_html__('Add/Remove your prefered video control options'),
                'multiple' => true,
                'options' => [
                    'play-large'  => esc_html__( 'Play Large', 'vapfem' ),
                    'play' => esc_html__( 'Play', 'vapfem' ),
                    'progress' => esc_html__( 'Progress Bar', 'vapfem' ),
                    'current-time' => esc_html__( 'Current Time', 'vapfem' ),
                    'mute' => esc_html__( 'Mute', 'vapfem' ),
                    'volume' => esc_html__( 'Volume', 'vapfem' ),
                    'captions' => esc_html__( 'Caption', 'vapfem' ),
                    'settings' => esc_html__( 'Settings Icon', 'vapfem' ),
                    'pip' => esc_html__( 'PIP', 'vapfem' ),
                    'airplay' => esc_html__( 'Airplay', 'vapfem' ),
                    'fullscreen' => esc_html__( 'Fullscreen', 'vapfem' ),
                ],
                'default' => [ 'play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'pip', 'airplay', 'fullscreen' ],
                'separator' => 'before',
            ]
        );

        
        $this->end_controls_section();

        $this->start_controls_section(
            'debug_section',
            [
                'label' => esc_html__( 'Debugging', 'vapfem' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

            $this->add_control(
                'debug_mode',
                [
                    'label' => esc_html__( 'Debug Mode', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'description' => esc_html__('Enable it when the player does not work properly. When debug is enable, the browser will show the informations about this player in the browser console. This is helpful for developer.', 'vapfem'),
                    'label_on' => esc_html__( 'Yes', 'vapfem' ),
                    'label_off' => esc_html__( 'No', 'vapfem' ),
                    'return_value' => 'true',
                    'default' => 'false',
                ]
            );

        $this->end_controls_section();

        // styling tab
        $this->start_controls_section(
            'styling_section',
            [
                'label' => esc_html__( 'Primary Color', 'vapfem' ),
                'tab' => \Elementor\controls_Manager::TAB_STYLE,
            ]
        );

        // primary_color
        $this->add_control(
            'primary_color',
            [
                'label' => esc_html__( 'Primary Color', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .plyr__control--overlaid' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .plyr__control[data-plyr]:hover' => 'background-color:{{VALUE}}',
                    '{{WRAPPER}} .plyr__progress__container input[type=range]' => 'color:{{VALUE}}',
                    '{{WRAPPER}} .plyr__volume input[type=range]' => 'color:{{VALUE}}',
                ],
            ]
        );

        // all_icon_color
        $this->add_control(
            'all_icon_color',
            [
                'label' => esc_html__( 'All Icon Color', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '.plyr--video .plyr__control svg' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        # styling play icon section start
        $this->start_controls_section(
            'styling_section_play_icon',
            [
                'label' => esc_html__( 'Play Icon', 'vapfem' ),
                'tab' => \Elementor\controls_Manager::TAB_STYLE,
            ]
        );

            // play_icon_bg_color
            $this->add_control(
                'play_icon_bg_color',
                [
                    'label' => esc_html__( 'BG Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__control[data-plyr="play"]' => 'background-color:{{VALUE}}',
                    ],
                ]
            );

            // play_icon_color
            $this->add_control(
                'play_icon_color',
                [
                    'label' => esc_html__( 'Icon Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__control[data-plyr="play"] svg' => 'color:{{VALUE}}',
                    ],
                ]
            );

            // play_icon_hover_bg_color
            $this->add_control(
                'play_icon_hover_bg_color',
                [
                    'label' => esc_html__( 'Hover BG Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__control[data-plyr="play"]:hover' => 'background-color:{{VALUE}}',
                    ],
                ]
            );

            // play_icon_hover_color
            $this->add_control(
                'play_icon_hover_color',
                [
                    'label' => esc_html__( 'Hover Icon Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__control[data-plyr="play"]:hover svg' => 'color:{{VALUE}}',
                    ],
                ]
            );

            // play_icon_border
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'play_icon_border',
                    'label' => esc_html__( 'Border', 'vapfem' ),
                    'selector' => '{{WRAPPER}} .plyr__control[data-plyr="play"]'
                ]
            );
        $this->end_controls_section(); // Styling- play icon section end

        $this->start_controls_section(
            'styling_progress_bar_section',
            [
                'label'     => esc_html__( 'Seek Progress Bar', 'vapfem' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
            // pbar_pointer_color
            $this->add_control(
                'pbar_pointer_color',
                [
                    'label' => esc_html__( 'Bar Pointer Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__progress__container input[type=range]::-webkit-slider-thumb' => 'background:{{VALUE}}',
                        '{{WRAPPER}} .plyr__progress__container input[type=range]::-moz-range-thumb' => 'background:{{VALUE}}',
                        '{{WRAPPER}} .plyr__progress__container input[type=range]::-ms-thumb' => 'background:{{VALUE}}',
                    ],
                ]
            );

            // pbar_color
            $this->add_control(
                'pbar_color_1',
                [
                    'label' => esc_html__( 'Bar Color 1', 'vapfem' ),
                    'desc'  => esc_html__( 'Use RGB color with some opacity. E.g: rgba(255,68,115,0.60). Otherwise buffer color will now show.', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__progress input[type=range]::-webkit-slider-runnable-track' => 'background-color:{{VALUE}}',
                        '{{WRAPPER}} .plyr__progress input[type=range]::-moz-range-track' => 'background-color:{{VALUE}}',
                        '{{WRAPPER}} .plyr__progress input[type=range]::-ms-track' => 'background-color:{{VALUE}}',
                    ],
                ]
            );

            // pbar_color_2
            $this->add_control(
                'pbar_color_2',
                [
                    'label' => esc_html__( 'Bar Color 2', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__progress__container input[type=range]' => 'color:{{VALUE}}',
                    ],
                ]
            );

            // pbar_buffer_color
            $this->add_control(
                'pbar_buffer_color',
                [
                    'label' => esc_html__( 'Buffered Bar Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr--audio .plyr__progress__buffer' => 'color:{{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section(); // styling_progress_bar_section end

        $this->start_controls_section(
            'styling_volume_section',
            [
                'label'     => esc_html__( 'Volume Icon', 'vapfem' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
            // volume_icon_bg_color
            $this->add_control(
                'volume_icon_bg_color',
                [
                    'label' => esc_html__( 'BG Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__control[data-plyr="mute"]' => 'background-color:{{VALUE}}',
                    ],
                ]
            );

            // volume_icon_color
            $this->add_control(
                'volume_icon_color',
                [
                    'label' => esc_html__( 'Icon Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__control[data-plyr="mute"] svg' => 'color:{{VALUE}}',
                    ],
                ]
            );

            // volume_icon_hover_bg_color
            $this->add_control(
                'volume_icon_hover_bg_color',
                [
                    'label' => esc_html__( 'Hover BG Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__control[data-plyr="mute"]:hover' => 'background-color:{{VALUE}}',
                    ],
                ]
            );

            // volume_icon_hover_color
            $this->add_control(
                'volume_icon_hover_color',
                [
                    'label' => esc_html__( 'Hover Icon Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__control[data-plyr="mute"]:hover svg' => 'color:{{VALUE}}',
                    ],
                ]
            );

            // volume_icon_border
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'volume_icon_border',
                    'label' => esc_html__( 'Border', 'vapfem' ),
                    'selector' => '{{WRAPPER}} .plyr__control[data-plyr="mute"]'
                ]
            );

            $this->end_controls_section(); // Styling- volume icon section end
            $this->start_controls_section(
                'styling_volume_bar_section',
                [
                    'label'     => esc_html__( 'Volume Bar', 'vapfem' ),
                    'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );
            // vbar_pointer_color
            $this->add_control(
                'vbar_pointer_color',
                [
                    'label' => esc_html__( 'Bar Pointer Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__volume input[type=range]::-webkit-slider-thumb' => 'background:{{VALUE}}',
                        '{{WRAPPER}} .plyr__volume input[type=range]::-moz-range-thumb' => 'background:{{VALUE}}',
                        '{{WRAPPER}} .plyr__volume input[type=range]::-ms-thumb' => 'background:{{VALUE}}',
                    ],
                ]

            );
            // vbar_color
            $this->add_control(
                'vbar_color',
                [
                    'label' => esc_html__( 'Bar Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__volume input[type=range]' => 'color:{{VALUE}}',
                    ],
                ]
            );

            // vbar_remaining_color
            $this->add_control(
                'vbar_remaining_color',
                [
                    'label' => esc_html__( 'Bar Empty Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__volume input[type=range]::-webkit-slider-runnable-track' => 'background-color:{{VALUE}}',
                        '{{WRAPPER}} .plyr__volume input[type=range]::-moz-range-track' => 'background-color:{{VALUE}}',
                        '{{WRAPPER}} .plyr__volume input[type=range]::-ms-track' => 'background-color:{{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section(); // style tab volume_section end

        $this->start_controls_section(
            'styling_setting_icon_section',
            [
                'label'     => esc_html__( 'Setting Icon', 'vapfem' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

            // settings_icon_bg_color
            $this->add_control(
                'settings_icon_bg_color',
                [
                    'label' => esc_html__( 'BG Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__control[data-plyr="settings"]' => 'background-color:{{VALUE}}',
                    ],
                ]
            );

            // settings_icon_color
            $this->add_control(
                'settings_icon_color',
                [
                    'label' => esc_html__( 'Icon Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__control[data-plyr="settings"] svg' => 'color:{{VALUE}}',
                    ],
                ]
            );

            // settings_icon_hover_bg_color
            $this->add_control(
                'settings_icon_hover_bg_color',
                [
                    'label' => esc_html__( 'Hover BG Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__control[data-plyr="settings"]:hover' => 'background-color:{{VALUE}}',
                    ],
                ]
            );

            // settings_icon_hover_color
            $this->add_control(
                'settings_icon_hover_color',
                [
                    'label' => esc_html__( 'Hover Icon Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__control[data-plyr="settings"]:hover svg' => 'color:{{VALUE}}',
                    ],
                ]
            );

            // volume_icon_border
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'settings_icon_border',
                    'label' => esc_html__( 'Border', 'vapfem' ),
                    'selector' => '{{WRAPPER}} .plyr__control[data-plyr="settings"]'
                ]
            );
        $this->end_controls_section(); // Style tab setting_icon_section end

        $this->start_controls_section(
            'styling_others_section',
            [
                'label'     => esc_html__( 'Others', 'vapfem' ),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

            // timer_color
            $this->add_control(
                'timer_color',
                [
                    'label' => esc_html__( 'Timer Color', 'vapfem' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .plyr__controls .plyr__time' => 'color:{{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section(); // Style tab others_section end

    }

    protected function render() {
        $settings    = $this->get_settings_for_display();
        $video_type = $settings['video_type'];
        $custom_poster = $settings['custom_poster'];
        $poster = $settings['poster'];
        $poster = isset($poster['url']) ? $poster['url'] : '';
        $youtube_video_id = $settings['youtube_video_id'];
        $vimeo_video_id = $settings['vimeo_video_id'];
        $autoplay = $settings['autoplay'] == 'true' ? 'true' : 'false';
        $muted = $settings['muted'] == 'true' ? 'true' : 'false';
        $loop = $settings['loop'] == 'true' ? 'true' : 'false';
        $video_list = $settings['video_list'];
        $volume = $settings['volume'];
        $volume = $settings['volume']['size'];
        $volume = (int) $volume / 100;
        $click_to_play = $settings['click_to_play'] == 'true' ? 'true' : 'false';
        $seek_time = $settings['seek_time'];
        $hide_controls = $settings['hide_controls'] == 'true' ? 'true' : 'false';
        $reset_on_end = $settings['reset_on_end'] == 'true' ? 'true' : 'false';
        $keyboard_focused = $settings['keyboard_focused'] == 'true' ? 'true' : 'false';
        $keyboard_global = $settings['keyboard_global'] == 'true' ? 'true' : 'false';
        $tooltips_controls = $settings['tooltips_controls'] == 'true' ? 'true' : 'false';
        $tooltips_seek = $settings['tooltips_seek'] == 'true' ? 'true' : 'false';
        $invert_time = $settings['invert_time'] == 'true' ? 'true' : 'false';
        $fullscreen_enabled = $settings['fullscreen_enabled'] == 'true' ? 'true' : 'false';
        $speed_selected = $settings['speed_selected'];
        $speed_selected = substr($speed_selected, 6 );
        $quality_default = $settings['quality_default'];
        $controls = $settings['controls'];
        $custom_ratio = $settings['custom_ratio'];
        $ratio = $settings['ratio'];
        $ratio = ( $custom_ratio && $settings['ratio'] ) ? $ratio : 'null';
        $debug_mode = $settings['debug_mode'] == 'true' ? 'true' : 'false';

        // data settings
        $data_settings = array();
        $data_settings['seek_time'] = $seek_time;
        $data_settings['volume'] = $volume;
        $data_settings['muted'] = $muted;
        $data_settings['clickToPlay'] = $click_to_play;
        $data_settings['keyboard_focused'] = $keyboard_focused;
        $data_settings['keyboard_global'] = $keyboard_global;
        $data_settings['tooltips_controls'] = $tooltips_controls;
        $data_settings['hideControls'] = $hide_controls;
        $data_settings['resetOnEnd'] = $reset_on_end;
        $data_settings['tooltips_seek'] = $tooltips_seek;
        $data_settings['invertTime'] = $invert_time;
        $data_settings['fullscreen_enabled'] = $fullscreen_enabled;
        $data_settings['speed_selected'] = $speed_selected;
        $data_settings['quality_default'] = $quality_default;
        $data_settings['controls'] = $controls;
        $data_settings['ratio'] = $ratio;
        $data_settings['debug_mode'] = $debug_mode;

        if($video_type == 'html5'):
        ?>
        <video
            poster="<?php echo esc_attr($poster); ?>"
            class="vapfem_player vapfem_video"
            <?php echo esc_attr($autoplay == 'true' ? 'autoplay' : ''); ?>
            <?php echo esc_attr($muted == 'true' ? 'muted' : ''); ?>
            <?php echo esc_attr($loop == 'true' ? 'loop' : ''); ?>
            data-settings='<?php echo wp_json_encode($data_settings); ?>'
        >

            <?php
            $video_link = '';
            foreach($video_list as $item):
                if($item['src_type'] == 'upload'){
                    $video_link = $item['video_upload'];
                    $video_link = $video_link['url'];
                } else {
                    $video_link = $item['video_link'];
                    $video_link = $video_link['url'];
                }

               $extension = $ext = pathinfo($video_link, PATHINFO_EXTENSION);
               $size = $item['video_size'];
            ?>
            <!-- Video files -->
            <source
                src="<?php echo esc_url($video_link); ?>"
                type="video/<?php echo esc_attr($extension); ?>"
                size="<?php echo esc_attr($size); ?>"
            />

            <?php endforeach; ?>

            <!-- Fallback for browsers that don't support the <video> element -->
            <a href="<?php echo esc_url($video_link); ?>"
                ><?php echo esc_html__('Download', 'vapfem'); ?></a
            >
        </video>
        <?php
        elseif($video_type == 'youtube'):
            ?>
            
            <div class="plyr__video-embed vapfem_player vapfem_video"
                data-settings='<?php echo wp_json_encode($data_settings); ?>'
            >
                <iframe
                    src="https://www.youtube.com/embed/<?php echo esc_attr($youtube_video_id); ?>?autoplay=<?php echo esc_attr($autoplay); ?>&amp;loop=<?php echo esc_attr($loop) ?>&amp;origin=<?php echo esc_url(get_home_url()); ?>&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
                    allowfullscreen
                    allowtransparency
                    allow="autoplay"
                    ></iframe>
            </div>

            <?php if($custom_poster && $poster): ?>
            <style type="text/css">
                .plyr__poster{
                    background-image: url('<?php echo esc_attr($poster) ?>') !important;
                }
            </style>
            <?php endif; ?>

        <?php
        elseif($video_type == 'vimeo'):
            ?>
            
            <div class="plyr__video-embed vapfem_player vapfem_video"
                data-settings='<?php echo wp_json_encode($data_settings); ?>'
            >
                <iframe
                    src="https://player.vimeo.com/video/<?php echo esc_attr($vimeo_video_id) ?>?autoplay=<?php echo esc_attr($autoplay); ?>&amp;loop=<?php echo esc_attr($loop) ?>&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media"
                    allowfullscreen
                    allowtransparency
                    allow="autoplay"
                    ></iframe>
            </div>
            <?php if($custom_poster && $poster): ?>
            <style type="text/css">
                .plyr__poster{
                    background-image: url('<?php echo esc_attr($poster) ?>') !important;
                }
            </style>
            <?php endif; ?>
        <?php
        endif;
    }
}