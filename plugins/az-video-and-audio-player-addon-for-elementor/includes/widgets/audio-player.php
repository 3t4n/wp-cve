<?php
class VAPFEM_Audio_Player extends Elementor\Widget_Base {

    public function get_name() {
        return "vapfem_audio_player";
    }

    public function get_title() {
        return esc_html__( "Audio Player", 'vapfem' );
    }

    public function get_icon() {
        return 'az_icon eicon-headphones';
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
            'src_type',
            [
                'label' => esc_html__( 'Audio Source', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'upload',
                'options' => [
                    'upload' => esc_html__( 'Upload Audio', 'vapfem' ),
                    'link' => esc_html__( 'Audio Link', 'vapfem' ),
                ],
            ]
        );

        $this->add_control(
            'audio_upload',
            array(
                'label' => esc_html__( 'Upload Audio', 'vapfem' ),
                'type'  => \Elementor\Controls_Manager::MEDIA,
                'media_type' => 'audio',
                'condition' => array(
                    'src_type' => 'upload',
                ),
            )
        );

        $this->add_control(
            'audio_link',
            [
                'label' => esc_html__( 'Audio Link', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://example.com/music-name.mp3', 'vapfem' ),
                'show_external' => false,
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'src_type'    =>  'link',
                ]
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => __('Note: Mobile browsers don’t allow autoplay for Audio. Some desktop or laptop browsers also automatically block videos from automatically playing or may automatically mute the audio.', 'vapfem'),
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
            ]
        );

        $this->add_control(
            'preload',
            [
                'label' => esc_html__( 'Preload', 'vapfem' ),
                'description' => __( 'Specifies how the the audio should be loaded when the page loads. <a target="_blank" href="https://www.w3schools.com/tags/att_audio_preload.asp">Learn More</a>', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'upload',
                'options' => [
                    'auto' => esc_html__( 'Auto', 'vapfem' ),
                    'metadata' => esc_html__( 'Metadata', 'vapfem' ),
                    'none' => esc_html__( 'None', 'vapfem' ),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'controls',
            [
                'label' => esc_html__( 'Control Options', 'vapfem' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'description'   =>  esc_html__('Add/Remove your prefered audio control options'),
                'multiple' => true,
                'options' => [
                    'play' => esc_html__( 'Play Icon', 'vapfem' ),
                    'progress' => esc_html__( 'Progress Bar', 'vapfem' ),
                    'mute' => esc_html__( 'Mute Icon', 'vapfem' ),
                    'volume' => esc_html__( 'Volume Bar', 'vapfem' ),
                    'settings' => esc_html__( 'Settings Icon', 'vapfem' ),
                    'airplay' => esc_html__( 'Airplay Icon', 'vapfem' ),
                    'download' => esc_html__( 'Download Button', 'vapfem' ),
                ],
                'default' => [ 'play', 'progress', 'mute', 'volume', 'settings' ],
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

        // audio link
        if($settings['src_type'] == 'upload'){
            $audio_link = $settings['audio_upload']['url'];
        } else {
            $audio_link = $settings['audio_link']['url'];
        }

        $autoplay = $settings['autoplay'] == 'true' ? 'true' : 'false';
        $muted = $settings['muted'] == 'true' ? 'true' : 'false';
        $loop = $settings['loop'] == 'true' ? 'true' : 'false';
        $seek_time = $settings['seek_time'];
        $tooltips_seek = $settings['tooltips_seek'] == 'true' ? 'true' : 'false';
        $invert_time = $settings['invert_time'] == 'true' ? 'true' : 'false';
        $speed_selected = $settings['speed_selected'];
        $speed_selected = substr($speed_selected, 6 );
        $preload = $settings['preload'];
        $controls = $settings['controls'];
        $debug_mode = $settings['debug_mode'] == 'true' ? 'true' : 'false';

        // data settings
        $data_settings = array();
        $data_settings['muted'] = $muted;
        $data_settings['seek_time'] = $seek_time;
        $data_settings['tooltips_seek'] = $tooltips_seek;
        $data_settings['invertTime'] = $invert_time;
        $data_settings['speed_selected'] = $speed_selected;
        $data_settings['controls'] = $controls;
        $data_settings['debug_mode'] = $debug_mode;

        if($audio_link):
            $arr = explode('.', $audio_link);
            $file_ext = end($arr);
        ?>
        <audio
            class="vapfem_player vapfem_audio" 
            data-settings='<?php echo wp_json_encode($data_settings); ?>' 
            <?php echo esc_attr($autoplay == 'true' ? 'autoplay allow="autoplay"' : ''); ?>
            <?php echo esc_attr($loop == 'true' ? 'loop' : ''); ?> 
            preload="<?php echo esc_attr($preload); ?>"
        >
            <source
                src="<?php echo esc_url($audio_link); ?>"
                type="audio/<?php echo esc_attr($file_ext); ?>"
            />
        </audio>
        <?php
        else:
            echo '<div class="vapfem_not_found">';
            echo "<span>". esc_html__('No Audio File Selected/Uploaded', 'vapfem') ."</span>";
            echo '</div>';
        endif;
    }
}