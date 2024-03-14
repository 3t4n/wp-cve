<?php

/**
 * The Gutenberg Block functionality of the plugin.
 *
 * @link       https://profilegrid.co
 * @since      1.0.0
 *
 * @package    Profile_Magic
 * @subpackage Profile_Magic/block
 */
class Reg_Magic_Block {

    private $profile_magic;
    private $version;

    public function enqueue_scripts() {
        $index_js = 'index.js';
        wp_enqueue_script(
                'reg-magic-blocks-forms',
                plugins_url($index_js, __FILE__),
                array(
                    'wp-blocks',
                    'wp-editor',
                    'wp-i18n',
                    'wp-element',
                    'wp-components',
                    'rm_ctabs_script',
                ),
                $this->version,
                true
        );
        //wp_localize_script('reg-magic-blocks-forms', 'rm_default_form', $this->regmagic_default_form());
        wp_localize_script('reg-magic-blocks-forms', 'rm_ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'premium_active' => true,  'rm_gutenberg_strings' => $this->get_gutenberg_strings()));
        wp_register_script(
                'rm_ctabs_script',
                RM_BASE_URL . 'public/js/rm_custom_tabs.js',
                array(),
                $this->version,
                true
        );
    }

    public function enqueue_block_editor_assets() {
        $settings = new RM_Options;
        $theme = $settings->get_value_of('theme');
        $layout = $settings->get_value_of('form_layout');
        if (defined('REGMAGIC_ADDON'))
            wp_enqueue_style('style_rm_rating', RM_ADDON_BASE_URL . 'public/js/rating3/rateit.css', array(), $this->version, 'all');

        switch ($theme) {
            case 'classic':
                if ($layout == 'label_top') {
                    wp_enqueue_style('rm_theme_classic_label_top', RM_BASE_URL . 'public/css/theme_rm_classic_label_top.css', array(), $this->version, 'all');
                    if (defined('REGMAGIC_ADDON'))
                        wp_enqueue_style('rm_theme_classic_label_top_addon', RM_ADDON_BASE_URL . 'public/css/theme_rm_classic_label_top.css', array(), $this->version, 'all');
                } elseif ($layout == 'two_columns') {
                    wp_enqueue_style('rm_theme_classic_two_columns', RM_BASE_URL . 'public/css/theme_rm_classic_two_columns.css', array(), $this->version, 'all');
                    if (defined('REGMAGIC_ADDON'))
                        wp_enqueue_style('rm_theme_classic_two_columns_addon', RM_ADDON_BASE_URL . 'public/css/theme_rm_classic_two_columns.css', array(), $this->version, 'all');
                } else
                    wp_enqueue_style('rm_theme_classic', RM_BASE_URL . 'public/css/theme_rm_classic.css', array(), $this->version, 'all');
                break;

            case 'matchmytheme':
                if ($layout == 'label_top') {
                    wp_enqueue_style('rm_theme_matchmytheme_label_top', RM_BASE_URL . 'public/css/theme_rm_matchmytheme_label_top.css', array(), $this->version, 'all');
                    if (defined('REGMAGIC_ADDON'))
                        wp_enqueue_style('rm_theme_matchmytheme_label_top_addon', RM_ADDON_BASE_URL . 'public/css/theme_rm_matchmytheme_label_top.css', array(), $this->version, 'all');
                } elseif ($layout == 'two_columns') {
                    wp_enqueue_style('rm_theme_matchmytheme_two_columns', RM_BASE_URL . 'public/css/theme_rm_matchmytheme_two_columns.css', array(), $this->version, 'all');
                    if (defined('REGMAGIC_ADDON'))
                        wp_enqueue_style('rm_theme_matchmytheme_two_columns_addon', RM_ADDON_BASE_URL . 'public/css/theme_rm_matchmytheme_two_columns.css', array(), $this->version, 'all');
                } else
                    wp_enqueue_style('rm_theme_matchmytheme', RM_BASE_URL . 'public/css/theme_rm_matchmytheme.css', array(), $this->version, 'all');
                break;

            default:
                break;
        }
        //wp_enqueue_style('rm-jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css', false, $this->version, 'all');        
        wp_register_style('rm_magic_front_style', RM_BASE_URL . 'public/css/style_rm_front_end.css', array(), $this->version, 'all');

        if (defined('REGMAGIC_ADDON')) {
            wp_register_style('rm_magic_front_style_addon', RM_ADDON_BASE_URL . 'public/css/style_rm_front_end.css', array(), $this->version, 'all');
        }
        //wp_enqueue_style('rm_default_theme', plugin_dir_url(__FILE__) . 'css/rm_default_theme.css', array(), $this->version, 'all');
        if ($theme == 'default') {
            wp_enqueue_style('rm_default_theme', RM_BASE_URL . 'public/css/rm_default_theme.css', array(), $this->version, 'all');
        }
        wp_register_script('rm-block-editor', plugins_url('rm-editor.js', __FILE__), array('jquery'), false, true);

        $premium_active = false;
        if (defined('REGMAGIC_ADDON')) {
            $premium_active = true;
        }
        wp_localize_script( 'reg-magic-blocks-forms', 'rm_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'premium_active'=> $premium_active, 'rm_form_default_ID' => $this->regmagic_default_form(), 'rm_gutenberg_strings' => $this->get_gutenberg_strings() ) );
    }

    public function reg_magic_register_rest_route() {
        register_rest_route(
                'regmagic/v1',
                '/forms',
                array(
                    'method' => 'GET',
                    'callback' => array($this, 'regmagic_load_forms'),
                    'permission_callback' => array($this, 'rm_get_private_data_permissions_check'),
                )
        );
        register_rest_route(
                'regmagic/v1',
                '/timerange',
                array(
                    'method' => 'GET',
                    'callback' => array($this, 'regmagic_check_timerange'),
                    'permission_callback' => array($this, 'rm_get_private_data_permissions_check'),
                )
        );
    }

    public function reg_magic_block_categories_all($categories) {
        $categories[] = array(
            'slug' => 'regmagic',
            'icon' => 'regmagic-title-icon',
            //'title' => esc_html('RegistrationMagic','custom-registration-form-builder-with-submission-manager'),
            'title' => sprintf(
                    esc_html__('RegistrationMagic', 'custom-registration-form-builder-with-submission-manager'),
                    '<span class="regmagic-title-icon">icon</span>'
            ),
        );

        return $categories;
    }

    public function rm_get_private_data_permissions_check() {
        // Restrict endpoint to only users who have the edit_posts capability.
        if (!current_user_can('edit_posts')) {
            return new WP_Error('rest_forbidden', esc_html__('OMG you can not view private data.', 'custom-registration-form-builder-with-submission-manager'), array('status' => 401));
        }

        // This is a black-listing approach. You could alternatively do this via white-listing, by returning false here and changing the permissions check.
        return true;
    }

    public function regmagic_default_form() {
        $forms = RM_Utilities::get_forms_dropdown(new RM_Services());
        $default_form_id = 0;
        if (!empty($forms)) {
            foreach ($forms as $id => $name) {
                $default_form_id = $id;
                break;
            }
        }
        return $default_form_id;
    }

    public function regmagic_check_timerange() {
        $timerange = array(
            array('value' => '', 'label' => 'Select'),
            array('value' => 'year', 'label' => 'Year'),
            array('value' => 'month', 'label' => 'Month')
        );
        return rest_ensure_response($timerange);
    }

    public function regmagic_load_forms() {
        $forms = RM_Utilities::get_forms_dropdown(new RM_Services());
        $return = array(array('value' => '', 'label' => 'Select Form'));
        if (!empty($forms)) {
            foreach ($forms as $id => $name) {
                $return[] = array('value' => $id, 'label' => $name);
            }
        }
        return rest_ensure_response($return);
    }

    public function reg_magic_block_register() {
        global $pagenow;

        // Skip block registration if Gutenberg is not enabled/merged.
        if (!function_exists('register_block_type')) {
            return;
        }
        $dir = dirname(__FILE__);

        $index_js = 'index.js';
        if ($pagenow !== 'widgets.php') {
            wp_register_script(
                    'reg-magic-blocks-forms',
                    plugins_url($index_js, __FILE__),
                    array(
                        'wp-blocks',
                        'wp-editor',
                        'wp-i18n',
                        'wp-element',
                        'wp-components',
                        'rm_ctabs_script',
                    ),
                    filemtime("$dir/$index_js"), false
            );
        } else {
            wp_register_script(
                    'reg-magic-blocks-forms',
                    plugins_url($index_js, __FILE__),
                    array(
                        'wp-blocks',
                        'wp-edit-widgets',
                        'wp-i18n',
                        'wp-element',
                        'wp-components',
                        'rm_ctabs_script',
                    ),
                    filemtime("$dir/$index_js"), false
            );
        }
        wp_enqueue_style('rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css');
        wp_register_style('rm_blocks_custom_tabs', RM_BASE_URL . 'public/css/rm_custom_tabs.css');
        if (defined('REGMAGIC_ADDON')) {
            wp_register_style('rm_magic_front_style_addon', RM_ADDON_BASE_URL . 'public/css/style_rm_front_end.css');
        }
        wp_register_script('rm-block-editor', plugins_url('rm-editor.js', __FILE__), array('jquery'), false, true);
        if (defined('REGMAGIC_ADDON')) {
            wp_register_style('reg-magic-gutenberg', RM_BASE_URL . 'blocks/reg-magic-gutenberg-style.css', array('rm_blocks_custom_tabs', 'rm_magic_front_style', 'rm_magic_front_style_addon'), $this->version, 'all');
        } else {
            wp_register_style('reg-magic-gutenberg', RM_BASE_URL . 'blocks/reg-magic-gutenberg-style.css', array('rm_blocks_custom_tabs', 'rm_magic_front_style'), $this->version, 'all');
        }
        wp_register_script(
                'rm_ctabs_script',
                RM_BASE_URL . 'public/js/rm_custom_tabs.js',
                array(),
                $this->version,
                true
        );
        register_block_type(
                'regmagic-blocks/form-page',
                array(
                    'editor_script' => 'reg-magic-blocks-forms',
                    'editor_style' => 'reg-magic-gutenberg',
                    'render_callback' => array($this, 'regmagic_forms'),
                    'keywords' => ['RegistrationMagic', 'Registration', 'registration form', 'forms', 'user register', 'sign up form', 'user account creation'],
                    'attributes' => array(
                        'fid' => array(
                            //'default' => $this->regmagic_default_form(),
                            'default' => null,
                            'type' => 'string',
                        ),
                    ),
                )
        );
        register_block_type(
                'regmagic-blocks/submission-page',
                array(
                    'editor_script' => 'rm-block-editor',
                    'editor_style' => 'reg-magic-gutenberg',
                    'render_callback' => array($this, 'regmagic_submissions'),
                    'keywords' => ['RegistrationMagic', 'Registration', 'submissions', 'User profile', 'profile', 'user data submission', 'user account', 'user area'],
                )
        );
        register_block_type(
                'regmagic-blocks/login-page',
                array(
                    'editor_script' => 'reg-magic-blocks-forms',
                    'editor_style' => 'reg-magic-gutenberg',
                    'render_callback' => array($this, 'regmagic_login'),
                    'keywords' => ['RegistrationMagic', 'Login', 'RegistrationMagic Login', 'User Login', 'Member Login', 'Sign In', 'Sign in', 'Authentication']
                )
        );
        if (defined('REGMAGIC_ADDON')) {
            register_block_type(
                    'regmagic-blocks/users-page',
                    array(
                        'editor_script' => 'reg-magic-blocks-forms',
                        'editor_style' => 'rm_magic_front_style_addon',
                        'render_callback' => array($this, 'regmagic_user'),
                        'keywords' => ['RegistrationMagic', 'users', 'RM users', 'users directory', 'members'],
                        'attributes' => array(
                            'fid' => array(
                                'default' => '',
                                'type' => 'string',
                            ),
                            'timerange' => array(
                                'default' => '',
                                'type' => 'string',
                            ),
                        ),
                    )
            );
        }
    }

    public function regmagic_forms($atts) {
        $fid = isset($atts['fid']) ? absint($atts['fid']) : 0;
        ob_start();
        if (!empty($fid)) {
            echo do_shortcode('[RM_Form id="' . $fid . '"]');
        } else {
            echo '<div class="rm-gutenberg-empty-content">' . esc_html__('Please Select Form', 'custom-registration-form-builder-with-submission-manager') . '</div>';
        }

        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function regmagic_submissions($atts) {
        ob_start();

        echo '<div class="rm-block-users" id="rmsubmissionTrigger">';

        echo do_shortcode('[RM_Front_Submissions]');
        echo '</div>';

        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function regmagic_login($atts) {
        ob_start();

        echo '<div class="rm-block-login">';
        echo do_shortcode('[RM_Login]');
        echo '</div>';

        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function regmagic_user($atts) {
        ob_start();
        $fid = isset($atts['fid']) ? absint($atts['fid']) : '';
        $timerange = isset($atts['timerange']) ? $atts['timerange'] : 'all';
        echo '<div class="rm-block-users">';
        if (empty($fid) && empty($timerange)) {
            echo do_shortcode('[RM_Users]');
        } elseif (!empty($fid) && !empty($timerange)) {
            echo do_shortcode('[RM_Users form_id="' . $fid . '" timerange="' . $timerange . '"]');
        } elseif (!empty($fid)) {
            echo do_shortcode('[RM_Users form_id="' . $fid . '"]');
        } elseif (!empty($timerange)) {
            echo do_shortcode('[RM_Users timerange="' . $timerange . '"]');
        }

        echo '</div>';

        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function get_gutenberg_strings() {
        $strings = [
            'gutenberg_notice' => [
                'template' => $this->rm_get_gutenberg_notice_template(),
                'button'   => __( 'Get Started', 'custom-registration-form-builder-with-submission-manager' ),
            
            ],
        ];
        if ( !isset($this->has_forms) || !$this->has_forms ) {
            $strings['gutenberg_notice']['url'] = add_query_arg( 'page', 'rm_form_manage', admin_url( 'admin.php' ) );
            return $strings;
        }
        return $strings;
    }

    public function rm_get_gutenberg_notice_template() {
        return __('<div><strong>Hey there, we noticed you are working on a contact form!</strong><br/> Do you know, apart from registration forms RegistrationMagic can create equally powerful contact forms with few simple clicks?</div>','custom-registration-form-builder-with-submission-manager');
    }
}
