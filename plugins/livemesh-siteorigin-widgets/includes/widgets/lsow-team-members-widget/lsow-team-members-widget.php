<?php

/*
Widget Name: Team Members
Description: Display a list of your team members optionally in a multi-column grid.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

class LSOW_Team_Widget extends SiteOrigin_Widget {

    function __construct() {
        parent::__construct(
            'lsow-team-members',
            __('Team Members', 'livemesh-so-widgets'),
            array(
                'description' => __('Create team members to display in a column grid.', 'livemesh-so-widgets'),
                'panels_icon' => 'dashicons dashicons-minus',
                'help' => LSOW_PLUGIN_HELP_URL. '#team-members'
            ),
            array(),
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'livemesh-so-widgets'),
                ),

                'style' => array(
                    'type' => 'select',
                    'label' => __('Choose Style', 'livemesh-so-widgets'),
                    'state_emitter' => array(
                        'callback' => 'select',
                        'args' => array('style')
                    ),
                    'default' => 'style1',
                    'options' => array(
                        'style1' => __('Style 1', 'livemesh-so-widgets'),
                        'style2' => __('Style 2', 'livemesh-so-widgets'),
                    )
                ),

                'team-members' => array(
                    'type' => 'repeater',
                    'label' => __('Team Members', 'livemesh-so-widgets'),
                    'item_name' => __('Team Member', 'livemesh-so-widgets'),
                    'item_label' => array(
                        'selector' => "[id*='team-members-name']",
                        'update_event' => 'change',
                        'value_method' => 'val'
                    ),
                    'fields' => array(
                        'name' => array(
                            'type' => 'text',
                            'label' => __('Name', 'livemesh-so-widgets'),
                            'description' => __('Name of the team member.', 'livemesh-so-widgets'),
                        ),

                        'image' => array(
                            'type' => 'media',
                            'label' => __('Team member image.', 'livemesh-so-widgets'),
                        ),

                        'position' => array(
                            'type' => 'text',
                            'label' => __('Position', 'livemesh-so-widgets'),
                            'description' => __('Specify the position/title of the team member.', 'livemesh-so-widgets'),
                        ),

                        'details' => array(
                            'type' => 'textarea',
                            'label' => __('Short details', 'livemesh-so-widgets'),
                            'description' => __('Provide a short writeup for the team member', 'livemesh-so-widgets'),
                        ),


                        'social_profile' => array(
                            'type' => 'section',
                            'label' => __('Social profile', 'livemesh-so-widgets'),
                            'fields' => array(
                                'email_address' => array(
                                    'type' => 'text',
                                    'label' => __('Email Address', 'livemesh-so-widgets'),
                                    'description' => __('Enter the email address of the team member.', 'livemesh-so-widgets'),
                                ),

                                'facebook' => array(
                                    'type' => 'text',
                                    'label' => __('Facebook Page URL', 'livemesh-so-widgets'),
                                    'description' => __('URL of the Facebook page of the team member.', 'livemesh-so-widgets'),
                                ),

                                'twitter' => array(
                                    'type' => 'text',
                                    'label' => __('Twitter Profile URL', 'livemesh-so-widgets'),
                                    'description' => __('URL of the Twitter page of the team member.', 'livemesh-so-widgets'),
                                ),

                                'linkedin' => array(
                                    'type' => 'text',
                                    'label' => __('LinkedIn Page URL', 'livemesh-so-widgets'),
                                    'description' => __('URL of the LinkedIn profile of the team member.', 'livemesh-so-widgets'),
                                ),

                                'pinterest' => array(
                                    'type' => 'text',
                                    'label' => __('Pinterest Page URL', 'livemesh-so-widgets'),
                                    'description' => __('URL of the Pinterest page for the team member.', 'livemesh-so-widgets'),
                                ),

                                'dribbble' => array(
                                    'type' => 'text',
                                    'label' => __('Dribbble Profile URL', 'livemesh-so-widgets'),
                                    'description' => __('URL of the Dribbble profile of the team member.', 'livemesh-so-widgets'),
                                ),

                                'google_plus' => array(
                                    'type' => 'text',
                                    'label' => __('GooglePlus Page URL', 'livemesh-so-widgets'),
                                    'description' => __('URL of the Google Plus page of the team member.', 'livemesh-so-widgets'),
                                ),

                                'instagram' => array(
                                    'type' => 'text',
                                    'label' => __('Instagram Page URL', 'livemesh-so-widgets'),
                                    'description' => __('URL of the Instagram feed for the team member.', 'livemesh-so-widgets'),
                                ),

                            )
                        ),

                        'animation' => array(
                            'type' => 'select',
                            'label' => __('Choose Animation Type', 'livemesh-so-widgets'),
                            'default' => 'none',
                            'options' => lsow_get_animation_options(),
                        ),

                    )
                ),

                'settings' => array(
                    'type' => 'section',
                    'label' => __('Settings', 'livemesh-so-widgets'),
                    'fields' => array(


                        'per_line' => array(
                            'type' => 'slider',
                            'label' => __('Team Members per row', 'livemesh-so-widgets'),
                            'state_handler' => array(
                                'style[style1]' => array('show'),
                                'style[style2]' => array('hide'),
                            ),
                            'min' => 1,
                            'max' => 6,
                            'integer' => true,
                            'default' => 3
                        ),

                        'per_line_tablet' => array(
                            'type' => 'slider',
                            'label' => __('Team Members per row in Tablet Resolution', 'livemesh-so-widgets'),
                            'state_handler' => array(
                                'style[style1]' => array('show'),
                                'style[style2]' => array('hide'),
                            ),
                            'min' => 1,
                            'max' => 6,
                            'integer' => true,
                            'default' => 2
                        ),

                        'per_line_mobile' => array(
                            'type' => 'slider',
                            'label' => __('Team Members per row in Mobile Resolution', 'livemesh-so-widgets'),
                            'state_handler' => array(
                                'style[style1]' => array('show'),
                                'style[style2]' => array('hide'),
                            ),
                            'min' => 1,
                            'max' => 4,
                            'integer' => true,
                            'default' => 1
                        ),

                        'image_size'    => array(
                            'type'          => 'image-size',
                            'label'         => __( 'Image Size', 'livemesh-so-widgets' ),
                            'default'       => 'large',
                        ),

                        'customize' => array(
                            'type' => 'notify',
                            'custom_label' => __('Customize this widget with the intuitive yet powerful visual styles editor - ', 'livemesh-so-widgets')  . '<a target="_blank" href="https://wordpress.org/plugins/so-css/" title="SiteOrigin CSS">' . __('SiteOrigin CSS.', 'livemesh-so-widgets') . '</a>',
                        ),
                    )
                ),
            )
        );
    }

    function initialize() {

        $this->register_frontend_scripts(
            array(
                array(
                    'lsow-waypoints',
                    LSOW_PLUGIN_URL . 'assets/js/jquery.waypoints' . LSOW_JS_SUFFIX . '.js',
                    array('jquery'),
                    LSOW_VERSION
                ),
            )
        );

        $this->register_frontend_styles(
            array(
                array(
                    'lsow-icomoon',
                    LSOW_PLUGIN_URL . 'assets/css/icomoon.css',
                    array(),
                    LSOW_VERSION
                ),
                array(
                    'lsow-animate',
                    LSOW_PLUGIN_URL . 'assets/css/animate.css',
                    array(),
                    LSOW_VERSION
                ),
                array(
                    'lsow-frontend',
                    LSOW_PLUGIN_URL . 'assets/css/lsow-frontend.css',
                    array(),
                    LSOW_VERSION
                ),
            )
        );

        $this->register_frontend_styles(array(
            array(
                'lsow-team-members',
                plugin_dir_url(__FILE__) . 'css/style.css'
            )
        ));
    }

    function get_style_name($instance) {
        if ($instance['style'] == 'style2')
            return false; // no stylesheet required for style 2 template
        return $instance['style'];
    }

    function get_template_variables($instance, $args) {
        $settings = $instance['settings'];

        $settings = array_merge($settings, array(
            'style' => $instance['style'],
            'team_members' => !empty($instance['team-members']) ? $instance['team-members'] : array(),
        ));

        return array('settings' => $settings);
    }

    public static function social_profile($team_member, $settings) {

        $output = '<div class="lsow-social-wrap">';

        $output .= '<div class="lsow-social-list">';

        $social_profile = $team_member['social_profile'];

        $email = $social_profile['email_address'];
        $facebook_url = $social_profile['facebook'];
        $twitter_url = $social_profile['twitter'];
        $linkedin_url = $social_profile['linkedin'];
        $dribbble_url = $social_profile['dribbble'];
        $pinterest_url = $social_profile['pinterest'];
        $googleplus_url = $social_profile['google_plus'];
        $instagram_url = $social_profile['instagram'];

        if ($email)
            $output .= '<div class="lsow-social-list-item"><a class="lsow-email" href="mailto:' . $email . '" title="' . __("Send an email", 'livemesh-so-widgets') . '"><i class="lsow-icon-email"></i></a></div>';
        if ($facebook_url)
            $output .= '<div class="lsow-social-list-item"><a class="lsow-facebook" href="' . $facebook_url . '" target="_blank" title="' . __("Follow on Facebook", 'livemesh-so-widgets') . '"><i class="lsow-icon-facebook"></i></a></div>';
        if ($twitter_url)
            $output .= '<div class="lsow-social-list-item"><a class="lsow-twitter" href="' . $twitter_url . '" target="_blank" title="' . __("Subscribe to Twitter Feed", 'livemesh-so-widgets') . '"><i class="lsow-icon-twitter"></i></a></div>';
        if ($linkedin_url)
            $output .= '<div class="lsow-social-list-item"><a class="lsow-linkedin" href="' . $linkedin_url . '" target="_blank" title="' . __("View LinkedIn Profile", 'livemesh-so-widgets') . '"><i class="lsow-icon-linkedin"></i></a></div>';
        if ($googleplus_url)
            $output .= '<div class="lsow-social-list-item"><a class="lsow-googleplus" href="' . $googleplus_url . '" target="_blank" title="' . __("Follow on Google Plus", 'livemesh-so-widgets') . '"><i class="lsow-icon-googleplus"></i></a></div>';
        if ($instagram_url)
            $output .= '<div class="lsow-social-list-item"><a class="lsow-instagram" href="' . $instagram_url . '" target="_blank" title="' . __("View Instagram Feed", 'livemesh-so-widgets') . '"><i class="lsow-icon-instagram"></i></a></div>';
        if ($pinterest_url)
            $output .= '<div class="lsow-social-list-item"><a class="lsow-pinterest" href="' . $pinterest_url . '" target="_blank" title="' . __("Subscribe to Pinterest Feed", 'livemesh-so-widgets') . '"><i class="lsow-icon-pinterest"></i></a></div>';
        if ($dribbble_url)
            $output .= '<div class="lsow-social-list-item"><a class="lsow-dribbble" href="' . $dribbble_url . '" target="_blank" title="' . __("View Dribbble Portfolio", 'livemesh-so-widgets') . '"><i class="lsow-icon-dribbble"></i></a></div>';

        $output .= '</div><!-- .lsow-social-list -->';

        $output .= '</div><!-- .lsow-social-wrap -->';

        return apply_filters('lsow_team_member_social_links', $output, $team_member, $settings);

    }

}

siteorigin_widget_register('lsow-team-members', __FILE__, 'LSOW_Team_Widget');