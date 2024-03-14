<?php

if ( !function_exists( 'pafe_bw_settings_init' ) ) {
    function pafe_bw_settings_init()
    {
        register_setting( 'napaeBasicWidgets', 'pafe_bw_settings' );
        // Card Title - Basic Widgets
        add_settings_section(
            'pafe_napaeBasicWidgets_section',
            __( 'Basic Widgets', 'primary-addon-for-elementor' ),
            '',
            'napaeBasicWidgets'
        );
        $napae_basic_widgets['about_me'] = __( 'About Me', 'primary-addon-for-elementor' );
        $napae_basic_widgets['about_us'] = __( 'About Us', 'primary-addon-for-elementor' );
        $napae_basic_widgets['blog'] = __( 'Blog', 'primary-addon-for-elementor' );
        $napae_basic_widgets['primary_button'] = __( 'Primary Button', 'primary-addon-for-elementor' );
        $napae_basic_widgets['chart'] = __( 'Chart', 'primary-addon-for-elementor' );
        $napae_basic_widgets['contact'] = __( 'Contact', 'primary-addon-for-elementor' );
        $napae_basic_widgets['gallery'] = __( 'Gallery', 'primary-addon-for-elementor' );
        $napae_basic_widgets['get_apps'] = __( 'Get Apps', 'primary-addon-for-elementor' );
        $napae_basic_widgets['history'] = __( 'History', 'primary-addon-for-elementor' );
        $napae_basic_widgets['image_compare'] = __( 'Image Compare', 'primary-addon-for-elementor' );
        $napae_basic_widgets['process'] = __( 'Process', 'primary-addon-for-elementor' );
        $napae_basic_widgets['section_title'] = __( 'Section Title', 'primary-addon-for-elementor' );
        $napae_basic_widgets['separator'] = __( 'Separator', 'primary-addon-for-elementor' );
        $napae_basic_widgets['services'] = __( 'Services', 'primary-addon-for-elementor' );
        $napae_basic_widgets['slider'] = __( 'Slider', 'primary-addon-for-elementor' );
        $napae_basic_widgets['subscribe_contact'] = __( 'Subscribe / Contact', 'primary-addon-for-elementor' );
        $napae_basic_widgets['table'] = __( 'Table', 'primary-addon-for-elementor' );
        $napae_basic_widgets['team_single'] = __( 'Team Single', 'primary-addon-for-elementor' );
        $napae_basic_widgets['team'] = __( 'Team', 'primary-addon-for-elementor' );
        $napae_basic_widgets['testimonials'] = __( 'Testimonials', 'primary-addon-for-elementor' );
        $napae_basic_widgets['typewriter'] = __( 'Typewriter', 'primary-addon-for-elementor' );
        $napae_basic_widgets['video'] = __( 'Video', 'primary-addon-for-elementor' );
        $napae_basic_widgets['woo_grid'] = __( 'Woo Product Grid', 'primary-addon-for-elementor' );
        $napae_basic_widgets['pricing_table'] = __( 'Pricing Table', 'primary-addon-for-elementor' );
        foreach ( $napae_basic_widgets as $key => $value ) {
            // Label
            add_settings_field(
                'napafe_' . $key,
                $value,
                'napafe_' . $key . '_render',
                'napaeBasicWidgets',
                'pafe_napaeBasicWidgets_section',
                array(
                'label_for' => 'napafe_' . $key . '-id',
            )
            );
        }
    }

}
// is_premium
// Output on Admin Page
if ( !function_exists( 'napae_admin_sub_page' ) ) {
    function napae_admin_sub_page()
    {
        ?>
    <h2 class="title">Enable & Disable - Primary Elementor Widgets</h2>
    <div class="card napae-fields-card napae-fields-basic">
      <form action='options.php' method='post'>
        <?php 
        settings_fields( 'napaeBasicWidgets' );
        do_settings_sections( 'napaeBasicWidgets' );
        submit_button( __( 'Save Basic Widgets Settings', 'primary-addon-for-elementor' ), 'basic-submit-class' );
        ?>
      </form>
    </div>
    <?php 
        // is_premium
    }

}