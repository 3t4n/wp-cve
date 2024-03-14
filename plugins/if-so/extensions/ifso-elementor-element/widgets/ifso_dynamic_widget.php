<?php
namespace Elementor;

use IfSo\PublicFace\Services\TriggersService;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class IFSO_Dynamic_Widget extends Widget_Base {
    public function get_name() {
        return 'ifso_dynamic_content';
    }

    public function get_title() {
        return __( 'Dynamic Content', 'elementor' );
    }

    public function get_icon() {
        return 'icon-ifso-logo';
    }

    public function get_categories() {
        return [ 'IFSO' ];
    }

    public function get_keywords() {
        return [ 'dynamic', 'content', 'dynamic content', 'personalization', 'if-so', 'ifso' ];
    }

    public function get_script_depends() {
        return [ 'ifso-elementor-tr-widget' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_settings',
            [
                'label' => __( 'Settings', 'elementor' ),
                'type'  => Controls_Manager::SECTION,
            ]
        );
        $trigger_list = $this->get_trigger_list();
        if ( ! empty( $trigger_list ) ) {
            $this->add_control(
                'trigger',
                [
                    'label'              => __( 'Select Trigger', 'elementor' ),
                    'type'               => Controls_Manager::SELECT,
                    'options'            => $trigger_list,
                    'render_type'        => 'template',
                    'frontend_available' => true,

                ]
            );
        }
        else {
            $new_trigger_url = admin_url( 'post-new.php?post_type=ifso_triggers' );
            $this->add_control(
                'add_a_trigger',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw'  => "No triggers found, please <a href='$new_trigger_url' target='_blank'>add a new trigger</a>",
                ]
            );
        }

     $this->add_control(
            'load_trigger_with_ajax',
            [
                'label'=>'Load with AJAX',
                'type'=>Controls_Manager::SELECT,
                'options'=>['default'=>'Site global','yes'=>'Yes','no'=>'No'],
                'default'=>'default'
            ]
        );

        $preview_triggers = [];
        $firstIterFlag= true;
        foreach ( $trigger_list as $key => $value ) {
            $trigger_versions = $this->get_trigger_versions( $key );
            if ( $trigger_versions ) {
                $view_trigger = get_permalink( $key );
                $edit_trigger = get_edit_post_link( $key );
                $html = "<div class='ifso-trigger-buttons'>
				<a href='$view_trigger' target='_blank'>View Trigger</a>
				<a href='$edit_trigger' target='_blank'>Edit Trigger</a>
				</div>";

                // setting up for separate tab
                $preview_triggers[ $key ] = [
                    'label'              => __( 'Preview Version', 'elementor' ),
                    'type'               => Controls_Manager::SELECT,
                    'options'            => $trigger_versions,
                    'default'            => 'default',
                    'render_type'        => 'template',
                    'frontend_available' => true,
                    'condition'          => [
                        'trigger' => "$key",
                    ],
                ];

                $this->add_control(
                    'trigger_buttons' . $key,
                    [
                        'label'     => __( 'Actions', 'elementor' ),
                        'type'      => Controls_Manager::RAW_HTML,
                        'raw'       => $html,
                        'condition' => [
                            'trigger' => "$key",
                        ],
                    ]
                );

                $this->add_control(
                    'hr_for_versions' . $key,
                    [
                        'type'      => Controls_Manager::DIVIDER,
                        'condition' => [
                            'trigger' => "$key",
                        ],
                    ]
                );

                $overview_html = $this->get_overview_section( $key );
                $this->add_control(
                    'trigger_versions' . $key,
                    [
                        'label'     => __( 'Trigger Overview', 'elementor' ),
                        'type'      => Controls_Manager::RAW_HTML,
                        'raw'       => $overview_html,
                        'condition' => [
                            'trigger' => "$key",
                        ],
                    ]
                );

                $this->add_control(
                    'hr_for_analytics' . $key,
                    [
                        'type'      => Controls_Manager::DIVIDER,
                        'condition' => [
                            'trigger' => "$key",
                        ],
                    ]
                );

                $analytics_html = $this->get_trigger_analytics( $key, $firstIterFlag );
                $this->add_control(
                    'trigger_analytics' . $key,
                    [
                        'label'     => __( 'Analytics', 'elementor' ),
                        'type'      => Controls_Manager::RAW_HTML,
                        'raw'       => $analytics_html,
                        'condition' => [
                            'trigger' => "$key",
                        ],
                    ]
                );

            }
            $firstIterFlag = false;
        }

        if ( $preview_triggers ) {
            $this->add_control(
                'preview_html_label',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw'  => "Preview",
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'preview_triggers_note_label',
                [
                    'label'     => __( 'Note that this section only controls the preview in the Elementor editor itself.', 'plugin-name' ),
                    'type'      => Controls_Manager::HEADING,
                ]
            );
            foreach ( $preview_triggers as $key => $control_settings ) {
                $this->add_control(
                    'preview_trigger' . $key,
                    $control_settings
                );
            }
        }
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();

        if ( ! isset( $settings['trigger'] ) || empty( $settings['trigger'] ) )
            return;

        $trigger_id = $settings['trigger'];
        $preview_id = isset( $settings[ 'preview_trigger' . $trigger_id ] ) ? $settings[ 'preview_trigger' . $trigger_id ] : "";
        if ( $preview_id === "" )
            return;

        $data_versions = get_post_meta( $trigger_id, 'ifso_trigger_version', false );
        $default       = get_post_meta( $trigger_id, 'ifso_trigger_default', true );
        $atts = [];
        if(isset($settings['load_trigger_with_ajax']) && $settings['load_trigger_with_ajax']!=='default'){
            $atts = ($settings['load_trigger_with_ajax'] === 'yes') ? ['ajax'=>'yes'] : ['ajax'=>'no'];
        }

        if ( Plugin::$instance->editor->is_edit_mode() ) {
            if ( $preview_id === 'default' )
                echo $default ? do_shortcode( $default ) : '';
            else
                echo isset( $data_versions[ $preview_id ] ) ? do_shortcode( $data_versions[ $preview_id ] ) : '';
        }
        else {
            ifso($trigger_id,$atts);
        }
    }


    protected function get_trigger_list() {
        $result = [];
        $args   = [
            'post_type'      => 'ifso_triggers',
            'posts_per_page' => - 1,
        ];
        $query  = new \WP_Query( $args );
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                // Loop in here
                $result[ get_the_ID() ] = ( null != the_title( '', '', false ) ? the_title( '', '', false ) : "No name (id=".get_the_ID().")" );
            }
        }
        wp_reset_postdata();

        return $result;
    }

    protected function get_trigger_versions( $trigger ) {
        $version_names = [];
        $default       = get_post_meta( $trigger, 'ifso_trigger_default', false );
        $data_versions = get_post_meta( $trigger, 'ifso_trigger_version', false );

        if ( isset( $default[0] ) ) {
            $version_names['default'] = "Default";
        }
        foreach ( $data_versions as $key => $value ) {
            $version_names[ $key ] = $this->generate_version_symbol(65 + intval($key));
        }


        return $version_names;
    }
    protected function generate_version_symbol($version_number) {
        $num_of_characters_in_abc = 26;
        $base_ascii = 64;
        $version_number = intval($version_number) - $base_ascii;

        $postfix = '';
        if ($version_number > $num_of_characters_in_abc) {
            $postfix = intval($version_number / $num_of_characters_in_abc) + 1;
            $version_number %= $num_of_characters_in_abc;
            if ($version_number == 0) {
                $version_number = $num_of_characters_in_abc;
                $postfix -= 1;
            }
        }

        $version_number += $base_ascii;
        return chr($version_number) . strval($postfix);
    }

    protected function get_trigger_analytics( $trigger_id, $firstIterFlag ) {
        if ( get_post_status( $trigger_id ) == 'publish' ) {
            return "<h4 onclick='constructIfsoAnalyticsUi()' class='analytics-loading-notice' id='analytics-loading-notice-$trigger_id'
		                    style='margin-bottom:8px;font-weight:normal;text-align:center;cursor:pointer;'>" . __( 'Loading stats...', 'if-so' ) . "<br>" . __( 'Click here to refresh', 'if-so' ) . "</h4>
		<div class='analytics-container' id='analytics-container-$trigger_id' pid='$trigger_id'></div>";
        }
        else {
            return "<h4 style='margin-bottom:8px;font-weight:normal;'>" . __( 'Statistics will be available after you publish', 'if-so' ) . "</h4>";
        }
    }

    protected function get_readable_condition( $data ) {
        if ( ! isset( $data['trigger_type'] ) || $data['trigger_type'] == [] ) {
            return 'Blank';
        }
        $readable_condition   = [];
        $readable_condition[] = $data['trigger_type'];

        switch ( $data['trigger_type'] ) {
            case 'Device' :
                if ( $data['user-behavior-device-mobile'] ) {
                    $readable_condition[1] = "Mobile";
                }
                if ( $data['user-behavior-device-tablet'] ) {
                    $readable_condition[1] = isset( $readable_condition[1] ) ? $readable_condition[1] . " ,Tablet" : "Tablet";
                }
                if ( $data['user-behavior-device-desktop'] ) {
                    $readable_condition[1] = isset( $readable_condition[1] ) ? $readable_condition[1] . " ,Desktop" : "Desktop";
                }
                break;
            case 'User-Behavior' :
                if ( $data['User-Behavior'] ) {
                    $readable_condition[1] = $data['User-Behavior'];
                }
                switch ( $data['User-Behavior'] ) {
                    case "BrowserLanguage ":
                        if ( $data['user-behavior-browser-language'] ) {
                            $readable_condition[2] = $data['user-behavior-browser-language'];
                        }
                        break;
                    case "Returning":
                        if ( $data['user-behavior-returning'] ) {
                            $readable_condition[2] = $data['user-behavior-returning'] === 'custom' ? $data['user-behavior-retn-custom'] : $data['user-behavior-returning'];
                        }
                        break;
                    case "Logged":
                        if ( $data['user-behavior-logged'] ) {
                            $readable_condition[2] = $data['user-behavior-logged'];
                        }
                        break;
                    case 'NewUser' :
                        $readable_condition[2] = 'NewUser';
                }
                break;
            case 'referrer':
                switch ( $data['trigger'] ) {
                    case 'custom':
                        $readable_condition[] = $data['operator'];
                        $readable_condition[] = $data['compare'];
                        break;
                    case 'common-referrers':
                        $readable_condition[] = $data['chosen-common-referrers'];
                        break;
                    case 'page-on-website':
                        $readable_condition[] = $data['page'];
                        break;
                }
                break;
            case 'PageUrl' :
                $readable_condition[] = $data['page-url-operator'];
                $readable_condition[] = $data['page-url-compare'];
                break;
            case 'advertising-platforms':
                $readable_condition[] = $data['advertising_platforms_option'];
                break;
            case 'url':
                $readable_condition[] = $data['compare'];
                break;
            case 'AB-Testing':
                $readable_condition[] = $data['AB-Testing'];
                if ( $data['AB-Testing'] === 'Custom' ) {
                    $readable_condition[] = $data['ab-testing-custom-no-sessions'] . ' ' . __( 'Sessions', 'elementor' );
                } else {
                    $readable_condition[] = $data['ab-testing-sessions'] . ' ' . __( 'Sessions', 'elementor' );
                }
                break;
            case 'Cookie':
                $readable_condition[] = "Cookie Name: " . $data['cookie-input'];
                if ( $data['cookie-value-input'] ) {
                    $readable_condition[] = "Cookie Value: " . $data['cookie-value-input'];
                }
                break;
            case 'UserIp':
                $readable_condition[] = $data['ip-values'];
                $readable_condition[] = $data['ip-input'];
                break;
            case 'Utm':
                $readable_condition[] = $data['utm-type'];
                $readable_condition[] = $data['utm-relation'];
                $readable_condition[] = $data['utm-value'];
                break;
            case 'Groups':
                $readable_condition[] = $data['user-group-relation'];
                $readable_condition[] = $data['group-name'];
                break;
            case 'userRoles':
                $readable_condition[] = $data['user-role-relationship'];
                $readable_condition[] = $data['user-role'];
                break;
        }

        return implode( ' > ', $readable_condition );
    }

    protected function get_overview_section( $trigger_id ) {
        $ifso_trigger_rules = get_post_meta( $trigger_id, 'ifso_trigger_rules', true );

        $html = '';
        if ( ! empty( $ifso_trigger_rules ) ) {
            $all_triggers = json_decode( $ifso_trigger_rules, true );
            if(!empty($all_triggers)){
                foreach ( $all_triggers as $key => $trigger_data ) {
                    $trigger_condition    = $this->get_readable_condition( $trigger_data );
                    $trigger_version_name = $this->generate_version_symbol(65 + intval($key));
                    $html                 .= "</br><b>$trigger_version_name:</b><p>Condition: $trigger_condition</p>";
                }
            }
        }

        return $html;
    }
}