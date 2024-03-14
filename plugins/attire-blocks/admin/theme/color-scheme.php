<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
function atbs_hex2rgb($hex)
{
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    return "$r, $g, $b";
}

// Attire theme active?

$current_theme = wp_get_theme(get_template())->get( 'Name' );

if ($current_theme !== 'Attire') return;

add_action("customize_controls_print_footer_scripts", function () {
    ?>
    <script>

        jQuery(function ($) {
            $('.customize-save-button-wrapper').prepend("<a title='<?php _e('Reset to default settings', 'attire'); ?>' id='reset-attire' class='button button-secondary' href='#' style='float: left;margin-right: 5px;background: #fb4e60;color:#ffffff;border-color: rgba(251, 55, 56, 0.8)'><?php _e('Reset', 'attire'); ?></a>");
            $('body').on('click', '#reset-attire', function (e) {
                e.preventDefault();
                if (!confirm("<?php _e('Are you trying to reset Attire theme options to it\'s default settings.\nAction can not be reverted.\nAre your sure?', 'attire'); ?>")) return false;
                var tt = $(this);
                tt.attr('disabled', 'disabled').html('<?php _e('Reseting...', 'attire'); ?>');
                $.post(ajaxurl, {
                    action: 'reset_attire_options',
                    __reset_attire: '<?php echo wp_create_nonce(NONCE_KEY); ?>'
                }, function (res) {
                    //tt.removeAttr('disabled').html('<?php _e('Reset to Default', 'attire'); ?>');
                    if (res.success) {
                        //jQuery('#customize-preview iframe').attr('src', jQuery('#customize-preview iframe').attr('src'));
                        location.reload(true);
                    }
                });
            });

        });
    </script>
    <?php
});

add_action("wp_ajax_reset_attire_options", function () {
    if (wp_verify_nonce($_REQUEST['__reset_attire'], NONCE_KEY) && current_user_can('manage_options')) {
        delete_option('attire_options');
        wp_send_json(array('success' => true));
    }
});

add_action('customize_register', __NAMESPACE__ . '\atbs_customize_color_scheme');

function atbs_customize_color_scheme($wp_customize)
{

    class Attire_Color_Choice_panel extends \WP_Customize_Control
    {
        public $type = 'color-scheme';


        public function atbs_build_field_html($key, $setting)
        {
            $value = '';
            if (isset($this->settings[$key])) {
                $value = $this->settings[$key]->value();
            }
            echo '<td  style="width: 33.33%">
                        <input style="height: 30px;border: 0;padding: 0;margin-top: 5px;margin-right: 5px" type="color" value="' . $value . '" ' . $this->get_link($key) . ' />
                      </td>';
        }

        public function render_content()
        {
            ?>
            <div class="customize-control">
                <?php if (!empty($this->label)) : ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif;
                if (is_array($this->description)) {
                    echo '<p>' . implode('</p><p>', $this->description) . '</p>';
                } else {
                    echo $this->description;
                } ?>
                <div class="card p-0">
                    <div class="card-body p-0">
                        <table class="table m-0" style="width: 100%" ">
                            <thead>
                            <tr>
                                <th scope="col">Normal</th>
                                <th scope="col">Hover</th>
                                <th scope="col">Active</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <?php
                                foreach ($this->settings as $key => $value) {
                                    $this->atbs_build_field_html($key, $value);
                                }
                                ?>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
        }
    }


    $wp_customize->add_section('attire_options', array(
        'title' => __('Color Scheme', 'attire-blocks'),
        'description' => '',
        'panel' => 'attire_color_panel',
        'priority' => 120,
    ));
    /**
     * Primary Color
     **/
    $wp_customize->add_setting('attire_options[primary_color]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[primary_color_hover]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[primary_color_active]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_control(
        new Attire_Color_Choice_panel(
            $wp_customize,
            'attire_options[primary]',
            array(
                'label' => __('Primary Color', 'attire-blocks'),
                'section' => 'attire_options',
                'settings' => array('attire_options[primary_color]', 'attire_options[primary_color_hover]', 'attire_options[primary_color_active]'),
            )
        )
    );


    /**
     * Secondary Color
     **/
    $wp_customize->add_setting('attire_options[secondary_color]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[secondary_color_hover]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[secondary_color_active]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));


    $wp_customize->add_control(
        new Attire_Color_Choice_panel(
            $wp_customize,
            'attire_options[secondary]',
            array(
                'label' => __('Secondary Color', 'attire-blocks'),
                'section' => 'attire_options',
                'settings' => array('attire_options[secondary_color]', 'attire_options[secondary_color_hover]', 'attire_options[secondary_color_active]'),
            )
        )
    );

    /**
     * Success Color
     **/
    $wp_customize->add_setting('attire_options[success_color]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[success_color_hover]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[success_color_active]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));


    $wp_customize->add_control(
        new Attire_Color_Choice_panel(
            $wp_customize,
            'attire_options[success]',
            array(
                'label' => __('Success Color', 'attire-blocks'),
                'section' => 'attire_options',
                'settings' => array('attire_options[success_color]', 'attire_options[success_color_hover]', 'attire_options[success_color_active]'),
            )
        )
    );

    /**
     * Danger Color
     **/
    $wp_customize->add_setting('attire_options[danger_color]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[danger_color_hover]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[danger_color_active]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));


    $wp_customize->add_control(
        new Attire_Color_Choice_panel(
            $wp_customize,
            'attire_options[danger]',
            array(
                'label' => __('Danger Color', 'attire-blocks'),
                'section' => 'attire_options',
                'settings' => array('attire_options[danger_color]', 'attire_options[danger_color_hover]', 'attire_options[danger_color_active]'),
            )
        )
    );

    /**
     * Warning Color
     **/
    $wp_customize->add_setting('attire_options[warning_color]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[warning_color_hover]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[warning_color_active]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));


    $wp_customize->add_control(
        new Attire_Color_Choice_panel(
            $wp_customize,
            'attire_options[warning]',
            array(
                'label' => __('Warning Color', 'attire-blocks'),
                'section' => 'attire_options',
                'settings' => array('attire_options[warning_color]', 'attire_options[warning_color_hover]', 'attire_options[warning_color_active]'),
            )
        )
    );

    /**
     * Info Color
     **/
    $wp_customize->add_setting('attire_options[info_color]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[info_color_hover]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[info_color_active]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));


    $wp_customize->add_control(
        new Attire_Color_Choice_panel(
            $wp_customize,
            'attire_options[info]',
            array(
                'label' => __('Info Color', 'attire-blocks'),
                'section' => 'attire_options',
                'settings' => array('attire_options[info_color]', 'attire_options[info_color_hover]', 'attire_options[info_color_active]'),
            )
        )
    );

    /**
     * Light Color
     **/
    $wp_customize->add_setting('attire_options[light_color]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[light_color_hover]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[light_color_active]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));


    $wp_customize->add_control(
        new Attire_Color_Choice_panel(
            $wp_customize,
            'attire_options[light]',
            array(
                'label' => __('Light Color', 'attire-blocks'),
                'section' => 'attire_options',
                'settings' => array('attire_options[light_color]', 'attire_options[light_color_hover]', 'attire_options[light_color_active]'),
            )
        )
    );

    /**
     * Dark Color
     **/
    $wp_customize->add_setting('attire_options[dark_color]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[dark_color_hover]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_setting('attire_options[dark_color_active]', array(
        'default' => '',
        'transport' => 'postMessage',
        'capability' => 'edit_theme_options',
    ));


    $wp_customize->add_control(
        new Attire_Color_Choice_panel(
            $wp_customize,
            'attire_options[dark]',
            array(
                'label' => __('Dark Color', 'attire-blocks'),
                'section' => 'attire_options',
                'settings' => array('attire_options[dark_color]', 'attire_options[dark_color_hover]', 'attire_options[dark_color_active]'),
            )
        )
    );
}

function atbs_customize_css()
{
    $uicolors = get_theme_mod('attire_options');
    $primary = isset($uicolors['primary_color']) ? $uicolors['primary_color'] : '#007bff';
    $secondary = isset($uicolors['secondary_color']) ? $uicolors['secondary_color'] : '#6c757d';
    $success = isset($uicolors['success_color']) ? $uicolors['success_color'] : '#28a745';
    $info = isset($uicolors['info_color']) ? $uicolors['info_color'] : '#17a2b8';
    $warning = isset($uicolors['warning_color']) ? $uicolors['warning_color'] : '#ffc107';
    $danger = isset($uicolors['danger_color']) ? $uicolors['danger_color'] : '#dc3545';
    $light = isset($uicolors['light_color']) ? $uicolors['light_color'] : '#f8f9fa';
    $dark = isset($uicolors['dark_color']) ? $uicolors['dark_color'] : '#343a40';
    ?>
    <style id="atbs_theme_extend_css" type="text/css">
        :root {
            --color-primary: <?php echo $primary; ?>;
            --color-primary-rgb: <?php echo atbs_hex2rgb($primary); ?>;
            --color-primary-hover: <?php echo isset($uicolors['primary_color_hover'])?$uicolors['primary_color_hover']:'#0069d9'; ?>;
            --color-primary-active: <?php echo isset($uicolors['primary_color_active'])?$uicolors['primary_color_active']:'#0069d9'; ?>;
            --color-secondary: <?php echo $secondary; ?>;
            --color-secondary-rgb: <?php echo atbs_hex2rgb($secondary); ?>;
            --color-secondary-hover: <?php echo isset($uicolors['secondary_color_hover'])?$uicolors['secondary_color_hover']:'#5a6268'; ?>;
            --color-secondary-active: <?php echo isset($uicolors['secondary_color_active'])?$uicolors['secondary_color_active']:'#5a6268'; ?>;
            --color-success: <?php echo $success; ?>;
            --color-success-rgb: <?php echo atbs_hex2rgb($success); ?>;
            --color-success-hover: <?php echo isset($uicolors['success_color_hover'])?$uicolors['success_color_hover']:'#218838'; ?>;
            --color-success-active: <?php echo isset($uicolors['success_color_active'])?$uicolors['success_color_active']:'#218838'; ?>;
            --color-info: <?php echo $info; ?>;
            --color-info-rgb: <?php echo atbs_hex2rgb($info); ?>;
            --color-info-hover: <?php echo isset($uicolors['info_color_hover'])?$uicolors['info_color_hover']:'#138496'; ?>;
            --color-info-active: <?php echo isset($uicolors['info_color_active'])?$uicolors['info_color_active']:'#138496'; ?>;
            --color-warning: <?php echo $warning; ?>;
            --color-warning-rgb: <?php echo atbs_hex2rgb($warning); ?>;
            --color-warning-hover: <?php echo isset($uicolors['warning_color_hover'])?$uicolors['warning_color_hover']:'#e0a800'; ?>;
            --color-warning-active: <?php echo isset($uicolors['warning_color_active'])?$uicolors['warning_color_active']:'#e0a800'; ?>;
            --color-danger: <?php echo $danger; ?>;
            --color-danger-rgb: <?php echo atbs_hex2rgb($danger); ?>;
            --color-danger-hover: <?php echo isset($uicolors['danger_color_hover'])?$uicolors['danger_color_hover']:'#c82333'; ?>;
            --color-danger-active: <?php echo isset($uicolors['danger_color_active'])?$uicolors['danger_color_active']:'#c82333'; ?>;
            --color-light: <?php echo $light; ?>;
            --color-light-rgb: <?php echo atbs_hex2rgb($light); ?>;
            --color-light-hover: <?php echo isset($uicolors['light_color_hover'])?$uicolors['light_color_hover']:'#e2e6ea'; ?>;
            --color-light-active: <?php echo isset($uicolors['light_color_active'])?$uicolors['light_color_active']:'#e2e6ea'; ?>;
            --color-dark: <?php echo $dark; ?>;
            --color-dark-rgb: <?php echo atbs_hex2rgb($dark); ?>;
            --color-dark-hover: <?php echo isset($uicolors['dark_color_hover'])?$uicolors['dark_color_hover']:'#23272b'; ?>;
            --color-dark-active: <?php echo isset($uicolors['dark_color_active'])?$uicolors['dark_color_active']:'#23272b'; ?>;

            --color-muted: rgba(69, 89, 122, 0.6);
        }
    </style>
    <?php
}

add_action('wp_head', __NAMESPACE__ . '\atbs_customize_css');
