<?php
// busicare default service data
if (!function_exists('spiceb_busicare_service_default_customize_register')) :

    function spiceb_busicare_service_default_customize_register($wp_customize) {

        $busicare_service_content_control = $wp_customize->get_setting('busicare_service_content');
        if (!empty($busicare_service_content_control)) {
            $busicare_service_content_control->default = json_encode(array(
                array(
                    'icon_value' => 'fa-headphones',
                    'title' => esc_html__('Suspendisse Tristique', 'spicebox'),
                    'text' => esc_html__('Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.', 'spicebox'),
                    'choice' => 'customizer_repeater_icon',
                    'link' => '#',
                    'open_new_tab' => 'yes',
                    'id' => 'customizer_repeater_56d7ea7f40b56',
                ),
                array(
                    'icon_value' => 'fa-mobile',
                    'title' => esc_html__('Blandit-Gravida', 'spicebox'),
                    'text' => esc_html__('Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.', 'spicebox'),
                    'choice' => 'customizer_repeater_icon',
                    'link' => '#',
                    'open_new_tab' => 'yes',
                    'id' => 'customizer_repeater_56d7ea7f40b66',
                ),
                array(
                    'icon_value' => 'fa fa-cogs',
                    'title' => esc_html__('Justo Bibendum', 'spicebox'),
                    'text' => esc_html__('Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.', 'spicebox'),
                    'choice' => 'customizer_repeater_icon',
                    'link' => '#',
                    'open_new_tab' => 'yes',
                    'id' => 'customizer_repeater_56d7ea7f40b86',
                ),
            ));
        }
    }

    add_action('customize_register', 'spiceb_busicare_service_default_customize_register');

endif;

// busicare default Testimonial data
if (!function_exists('spiceb_busicare_testimonial_default_customize_register')) :

    function spiceb_busicare_testimonial_default_customize_register($wp_customize) {

        $busicare_service_content_control = $wp_customize->get_setting('busicare_testimonial_content');
        if (!empty($busicare_service_content_control)) {
            $busicare_service_content_control->default = json_encode(array(
                array(
                    'title' => 'Nam Viverra Iaculis Finibus',
                    'text' => 'Sed ut Perspiciatis Unde Omnis Iste Sed ut perspiciatis unde omnis iste natu error sit voluptatem accu tium neque fermentum veposu miten a tempor nise. Duis autem vel eum iriure dolor in hendrerit in vulputate velit consequat reprehender in voluptate velit esse cillum duis dolor fugiat nulla pariatur.',
                    'clientname' => esc_html__('Cras Vitae', 'busicare-plus'),
                    'designation' => esc_html__('Eu Suscipit', 'busicare-plus'),
                    'link' => '#',
                    'image_url' => SPICEB_PLUGIN_URL . '/inc/busicare/images/testimonial/user1.jpg',
                    'open_new_tab' => 'no',
                    'id' => 'customizer_repeater_56d7ea7f40b96',
                ),
                array(
                    'title' => 'Nam Viverra Iaculis Finibus',
                    'text' => 'Sed ut Perspiciatis Unde Omnis Iste Sed ut perspiciatis unde omnis iste natu error sit voluptatem accu tium neque fermentum veposu miten a tempor nise. Duis autem vel eum iriure dolor in hendrerit in vulputate velit consequat reprehender in voluptate velit esse cillum duis dolor fugiat nulla pariatur.',
                    'clientname' => esc_html__('Cras Vitae', 'busicare-plus'),
                    'designation' => esc_html__('Eu Suscipit', 'busicare-plus'),
                    'link' => '#',
                    'image_url' => SPICEB_PLUGIN_URL . '/inc/busicare/images/testimonial/user2.jpg',
                    'open_new_tab' => 'no',
                    'id' => 'customizer_repeater_56d7ea7f40b97',
                ),
                array(
                    'title' => 'Nam Viverra Iaculis Finibus',
                    'text' => 'Sed ut Perspiciatis Unde Omnis Iste Sed ut perspiciatis unde omnis iste natu error sit voluptatem accu tium neque fermentum veposu miten a tempor nise. Duis autem vel eum iriure dolor in hendrerit in vulputate velit consequat reprehender in voluptate velit esse cillum duis dolor fugiat nulla pariatur.',
                    'clientname' => esc_html__('Cras Vitae', 'busicare-plus'),
                    'designation' => esc_html__('Eu Suscipit', 'busicare-plus'),
                    'link' => '#',
                    'image_url' => SPICEB_PLUGIN_URL . '/inc/busicare/images/testimonial/user3.jpg',
                    'id' => 'customizer_repeater_56d7ea7f40b98',
                    'open_new_tab' => 'no',
                ),
                    ));
        }
    }

    add_action('customize_register', 'spiceb_busicare_testimonial_default_customize_register');

endif;

// busicare default Team data
if (!function_exists('spiceb_busicare_team_default_customize_register')) :

    function spiceb_busicare_team_default_customize_register($wp_customize) {

        $busicare_team_content_control = $wp_customize->get_setting('busicare_team_content');
        if (!empty($busicare_team_content_control)) {
            $busicare_team_content_control->default = json_encode(array(
                array(
                    'image_url' => SPICEB_PLUGIN_URL . '/inc/busicare/images/team/team1.jpg',
                    'membername' => 'Danial Wilson',
                    'designation' => esc_html__('Senior Manager', 'busicare-plus'),
                    'open_new_tab' => 'no',
                    'id' => 'customizer_repeater_56d7ea7f40c56',
                    'social_repeater' => json_encode(
                            array(
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb908674e06',
                                    'link' => 'facebook.com',
                                    'icon' => 'fa-facebook',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9148530fc',
                                    'link' => 'twitter.com',
                                    'icon' => 'fa-twitter',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9150e1e89',
                                    'link' => 'linkedin.com',
                                    'icon' => 'fa-linkedin',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9150e1e256',
                                    'link' => 'behance.com',
                                    'icon' => 'fa-behance',
                                ),
                            )
                    ),
                ),
                array(
                    'image_url' => SPICEB_PLUGIN_URL . '/inc/busicare/images/team/team2.jpg',
                    'membername' => 'Amanda Smith',
                    'designation' => esc_html__('Founder & CEO', 'busicare-plus'),
                    'open_new_tab' => 'no',
                    'id' => 'customizer_repeater_56d7ea7f40c66',
                    'social_repeater' => json_encode(
                            array(
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9155a1072',
                                    'link' => 'facebook.com',
                                    'icon' => 'fa-facebook',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9160ab683',
                                    'link' => 'twitter.com',
                                    'icon' => 'fa-twitter',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb916ddffc9',
                                    'link' => 'linkedin.com',
                                    'icon' => 'fa-linkedin',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb916ddffc784',
                                    'link' => 'behance.com',
                                    'icon' => 'fa-behance',
                                ),
                            )
                    ),
                ),
                array(
                    'image_url' => SPICEB_PLUGIN_URL . '/inc/busicare/images/team/team3.jpg',
                    'membername' => 'Victoria Wills',
                    'designation' => esc_html__('Web Master', 'busicare-plus'),
                    'open_new_tab' => 'no',
                    'id' => 'customizer_repeater_56d7ea7f40c76',
                    'social_repeater' => json_encode(
                            array(
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb917e4c69e',
                                    'link' => 'facebook.com',
                                    'icon' => 'fa-facebook',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb91830825c',
                                    'link' => 'twitter.com',
                                    'icon' => 'fa-twitter',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb918d65f2e',
                                    'link' => 'linkedin.com',
                                    'icon' => 'fa-linkedin',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb918d65f2e8',
                                    'link' => 'behance.com',
                                    'icon' => 'fa-behance',
                                ),
                            )
                    ),
                ),
                array(
                    'image_url' => SPICEB_PLUGIN_URL . '/inc/busicare/images/team/team4.jpg',
                    'membername' => 'Travis Marcus',
                    'designation' => esc_html__('UI Developer', 'busicare-plus'),
                    'open_new_tab' => 'no',
                    'id' => 'customizer_repeater_56d7ea7f40c86',
                    'social_repeater' => json_encode(
                            array(
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb925cedcb2',
                                    'link' => 'facebook.com',
                                    'icon' => 'fa-facebook',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb92615f030',
                                    'link' => 'twitter.com',
                                    'icon' => 'fa-twitter',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9266c223a',
                                    'link' => 'linkedin.com',
                                    'icon' => 'fa-linkedin',
                                ),
                                array(
                                    'id' => 'customizer-repeater-social-repeater-57fb9266c223a',
                                    'link' => 'behance.com',
                                    'icon' => 'fa-behance',
                                ),
                            )
                    ),
                ),
                    ));
    }
}

add_action('customize_register', 'spiceb_busicare_team_default_customize_register');

endif;

function spiceb_busicare_sections_settings($wp_customize) {

    $selective_refresh = isset($wp_customize->selective_refresh) ? 'postMessage' : 'refresh';
    /* Sections Settings */
    $wp_customize->add_panel('section_settings', array(
        'priority' => 126,
        'capability' => 'edit_theme_options',
        'title' => esc_html__('Homepage Section Settings', 'busicare'),
    ));
}

add_action('customize_register', 'spiceb_busicare_sections_settings');

/* * *********************** Slider Callback function ******************************** */

function spiceb_busicare_slider_callback($control) {
    if (true == $control->manager->get_setting('home_page_slider_enabled')->value()) {
        return true;
    } else {
        return false;
    }
}

/* * *********************** Service Callback function ******************************** */

function spiceb_busicare_service_callback($control) {
    if (true == $control->manager->get_setting('home_service_section_enabled')->value()) {
        return true;
    } else {
        return false;
    }
}

/* * *********************** Testimonial Callback function ******************************** */

function spiceb_busicare_testimonial_callback($control) {
    if (true == $control->manager->get_setting('testimonial_section_enable')->value()) {
        return true;
    } else {
        return false;
    }
}

/* * *********************** Latest News Callback function ******************************** */

function spiceb_busicare_news_callback($control) {
    if (true == $control->manager->get_setting('latest_news_section_enable')->value()) {
        return true;
    } else {
        return false;
    }
}

/* * *********************** CTA1 Callback function ******************************** */

function spiceb_busicare_team_callback($control) {
    if (true == $control->manager->get_setting('team_section_enable')->value()) {
        return true;
    } else {
        return false;
    }
}

add_action('wp_head','spicebox_busicare_sections_script');
function spicebox_busicare_sections_script(){?>
    <script type="text/javascript">

        

    </script>
<?php
}