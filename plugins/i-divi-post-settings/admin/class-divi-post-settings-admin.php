<?php

/**
 * The admin-specific functionality of the plugin.
 */
class idivi_post_settings_Admin
{

    /**
     * The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /*
     * Enqueue and localize idivi-ajax script
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script('idivi-ajax', plugin_dir_url(__FILE__) . 'js/idivi-ajax.js', array('jquery'), $this->version);
        wp_localize_script('idivi-ajax', 'idivi_vars', array(
			'idivi_nonce' => wp_create_nonce('idivi-nonce'),
        )
        );
    }

    /*
     * Register stylesheets for the admin
     */
    public function enqueue_styles()
    {
        wp_enqueue_style('admin-css', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version);
    }

    /**
     * If Divi is not active alert the user the plugin need Divi in order to work otherwise show the info notice redirecting to the linked Theme Customizer
     *
     * @since   1.1
     *
     */
    public function inform_user()
    {
		$user_id = get_current_user_id();

        $idivi_dismiss = get_user_option("idivi-dismiss", $user_id);
        $current_theme = wp_get_theme();
        $option_saved = get_option('idivi_post_settings_dot');


        if ('Divi' === $current_theme->get('Name') || 'Divi' === $current_theme->get('Template')) {
            if ($idivi_dismiss != 'dismissed' ||
                '' != $option_saved ) {
                echo '<div class="notice notice-info idivi-notice is-dismissible" data-notice-id="idivi-notice"><p>You need to <a href="customize.php?autofocus[panel]=et_divi_blog_settings" class="notice-link">go to the Theme Customizer</a> in order to set your preferences!</p><p><b>NOTE:</b> If you have updated from 1.2 version you should save again your settings from the Theme Customizer.</p></div>';
			}
        } else {
            echo '<div class="notice notice-error"><p>You need to have Divi theme active. Divi Post Settings <b>depends</b> from Divi!</p></div>';
        }
	}

    /*
     * Process Ajax request updating 'idivi-dismiss' user option
     */
    public function process_ajax()
    {
        $user_id = get_current_user_id();
        if (!isset($_POST['idivi_nonce']) || !wp_verify_nonce($_POST['idivi_nonce'], 'idivi-nonce')) {
            die('Permissions check failed');
        }
        update_user_option($user_id, "idivi-dismiss", 'dismissed');
        die();
	}

    /*
     * Remove the default Divi Metaboxes
     */
    public function remove_metabox()
    {
        remove_action('add_meta_boxes', 'et_settings_meta_box');
    }

/**
 * Adding the custom Metabox only if 'post', 'page', 'project' or WC 'product'.
 *
 * @since   1.3
 *
 */
    public function idivi_add_custom_metabox()
    {
        $post_type = !empty($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : 'post';
        $post_type_allowed = array(
            'post',
            'page',
            'project',
            'product',
		);
		$dismiss_metabox = get_theme_mod( 'idivi_post_settings_metabox' );

        if (in_array($post_type, $post_type_allowed) && $dismiss_metabox === false ) {
            add_meta_box('idivi_settings_meta_box', esc_html__('Divi ' . ucfirst($post_type) . ' Settings', 'Divi'), 'idivi_single_settings_meta_box', $post_type, 'side', 'high');
        }
    }

/**
 * TODO: not working (at first launch theme mods don't apply!)
 * Apply Theme Mods at first launch of the Visual Builder.
 *
 * @since   1.3
 *
 */
  /* public function set_initial_theme_mods_values($post)
    {
        $post_id = get_the_ID();
        $post_type = get_post_type($post_id);

        // GET CUSTOMIZER THEME MODS
        $layout_setting_theme_mod = get_theme_mod('idivi_' . $post_type . '_settings_sidebar');
        $dot_theme_mod = get_theme_mod('idivi_' . $post_type . '_settings_dot');
        $hide_before_setting_theme_mod = get_theme_mod('idivi_' . $post_type . '_settings_before_scroll');
        $show_title_setting_theme_mod = get_theme_mod('idivi_post_settings_post_title');
        $project_nav_setting_theme_mod = get_theme_mod('idivi_project_settings_nav');

        // Check to see if we are autosaving
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
         return;

            // ASSIGN POST META VALUES ACCORDING TO THEME MODS (this way when launch VB the values will be already in place)
        switch ($post_type) {
            case 'post':
            update_post_meta($post_id, '_et_pb_page_layout', $layout_setting_theme_mod);
            update_post_meta($post_id, '_et_pb_side_nav', $dot_theme_mod);
            update_post_meta($post_id, '_et_pb_post_hide_nav', $hide_before_setting_theme_mod);
            update_post_meta($post_id, '_et_pb_show_title', $show_title_setting_theme_mod);
            break;
            case 'page':
            update_post_meta($post_id, '_et_pb_side_nav', $dot_theme_mod);
            update_post_meta($post_id, '_et_pb_post_hide_nav', $hide_before_setting_theme_mod);
            break;
            case 'project':
            update_post_meta($post_id, '_et_pb_side_nav', $dot_theme_mod);
            update_post_meta($post_id, '_et_pb_post_hide_nav', $hide_before_setting_theme_mod);
            update_post_meta($post_id, '_et_pb_project_nav', $project_nav_setting_theme_mod);
            break;
            case 'product':
            update_post_meta($post_id, '_et_pb_page_layout', $layout_setting_theme_mod);
            update_post_meta($post_id, '_et_pb_side_nav', $dot_theme_mod);
            update_post_meta($post_id, '_et_pb_post_hide_nav', $hide_before_setting_theme_mod);
            break;
        
            default:
            update_post_meta($post_id, '_et_pb_page_layout', $layout_setting_theme_mod);
            update_post_meta($post_id, '_et_pb_side_nav', $dot_theme_mod);
            update_post_meta($post_id, '_et_pb_post_hide_nav', $hide_before_setting_theme_mod);
            break;
         }
        
    }  */

/**
 * Adding Divi Settings toggle in VB Page Settings.
 * TODO: open it by default
 *
 * @since   1.3
 *
 */
    public function idivi_add_page_toggles($toggles)
    {

        // Get current post type singular name and use it as toggle title.
        $post_type = get_post_type(et_core_page_resource_get_the_ID());
        $post_type_obj = get_post_type_object($post_type);

        $page_custom_toggles = [
            'page_settings' => $post_type_obj->labels->singular_name . esc_html__(' Settings', 'et_builder'),
        ];

        return array_merge(array_slice($toggles, 0, 0), $page_custom_toggles, array_slice($toggles, 0));
    }

    /**
     * Adding Divi Settings in Classic and Visual Builder.
     *
     * @since   1.3
     *
     */
    public function idivi_add_page_settings($page_fields)
    {

        // Get Post Type from $_GET on BB and VB
        if (!empty($_GET['et_fb'])) {
            if (!empty($_GET['p'])) {
                $post_type = get_post_type($_GET['p']);
            } else if (!empty($_GET['page_id'])) {
                $post_type = get_post_type($_GET['page_id']);
            } else {
                $post_type = 'post';
            }
        } else {
            if (!empty($_GET['post_type'])) {
                $post_type = sanitize_text_field($_GET['post_type']);
            } else if (!empty($_GET['post'])) {
                $post_type = get_post_type($_GET['post']);
            } else if (!empty($_GET['page_id'])) {
                $post_type = get_post_type($_GET['page_id']);
            } else if (!empty($_GET['preview_id'])) {
                $post_type = get_post_type($_GET['preview_id']);
            } else if (!empty($_GET['p'])) {
                $post_type = get_post_type($_GET['p']);
            } else {
                $post_type = 'post';
            }
        }

        // Set default Layout values
        $default_post = get_theme_mod('idivi_post_settings_sidebar');
        $default_project = get_theme_mod('idivi_project_settings_sidebar');
        $default_product = get_theme_mod('idivi_product_settings_sidebar');
        // Set default Dot values
        $default_page_dot = get_theme_mod('idivi_page_settings_dot');
        $default_post_dot = get_theme_mod('idivi_post_settings_dot');
        $default_project_dot = get_theme_mod('idivi_project_settings_dot');
        $default_product_dot = get_theme_mod('idivi_product_settings_dot');
        // Set default Hide Nav values
        $default_page_hide = get_theme_mod('idivi_page_settings_before_scroll');
        $default_post_hide = get_theme_mod('idivi_post_settings_before_scroll');
        $default_project_hide = get_theme_mod('idivi_project_settings_before_scroll');
        $default_product_hide = get_theme_mod('idivi_product_settings_before_scroll');
        // Set default Show Title values
        $default_post_title = get_theme_mod('idivi_post_settings_post_title');
        // Set default Project Nav values
        $default_project_nav = get_theme_mod('idivi_project_settings_nav');

        // Create array of custom settings for BB and VB
        $page_custom_fields = [];

        $page_custom_fields['idivi_post_layout'] = [
            'meta_key' => '_idivi_post_layout',
            'default' => $default_post,
            'default_on_front' => true,
            'label' => esc_html__('Page Layout', 'et_builder'),
            'id' => 'idivi_post_layout',
            'type' => 'select',
            'options' => array(
                'et_right_sidebar' => esc_html__('Right Sidebar', 'et_builder'),
                'et_left_sidebar' => esc_html__('Left Sidebar', 'et_builder'),
                'et_no_sidebar' => esc_html__('No Sidebar', 'et_builder'),
                'et_full_width_page' => esc_html__('Fullwidth Sidebar', 'et_builder'),
            ),
            'show_in_bb' => $post_type === 'post' ? true : false,
            'option_category' => 'basic_option',
            'description' => esc_html__('Choose the page layout.'),
            'tab_slug' => 'content',
            'toggle_slug' => 'page_settings',
            'depends_on_post_type' => array('post'),
        ];

        $page_custom_fields['idivi_project_layout'] = [
            'meta_key' => '_idivi_project_layout',
            'default' => $default_project,
            'default_on_front' => true,
            'label' => esc_html__('Project Layout', 'et_builder'),
            'id' => 'idivi_project_layout',
            'type' => 'select',
            'options' => array(
                'et_right_sidebar' => esc_html__('Right Sidebar', 'et_builder'),
                'et_left_sidebar' => esc_html__('Left Sidebar', 'et_builder'),
                'et_no_sidebar' => esc_html__('No Sidebar', 'et_builder'),
            ),
            'show_in_bb' => $post_type === 'project' ? true : false,
            'option_category' => 'basic_option',
            'description' => esc_html__('Choose the page layout.'),
            'tab_slug' => 'content',
            'toggle_slug' => 'page_settings',
            'depends_on_post_type' => array('project'),
        ];

        $page_custom_fields['idivi_product_layout'] = [
            'meta_key' => '_idivi_product_layout',
            'default' => $default_product,
            'default_on_front' => true,
            'label' => esc_html__('Product Layout', 'et_builder'),
            'id' => 'idivi_product_layout',
            'type' => 'select',
            'options' => array(
                'et_right_sidebar' => esc_html__('Right Sidebar', 'et_builder'),
                'et_left_sidebar' => esc_html__('Left Sidebar', 'et_builder'),
                'et_no_sidebar' => esc_html__('No Sidebar', 'et_builder'),
            ),
            'show_in_bb' => $post_type === 'product' ? true : false,
            'option_category' => 'basic_option',
            'description' => esc_html__('Choose the post layout.'),
            'tab_slug' => 'content',
            'toggle_slug' => 'page_settings',
            'depends_on_post_type' => array('product'),
        ];

        // Adding Dot Nav (page, post, project, product)
        $page_custom_fields['idivi_page_dot_nav'] = [
            'meta_key' => '_idivi_page_dot_nav',
            'default' => $default_page_dot,
            'default_on_front' => true,
            'label' => esc_html__('Dot Navigation', 'et_builder'),
            'id' => 'idivi_page_dot_nav',
            'type' => 'select',
            'options' => array(
                'off' => esc_html__('Off', 'et_builder'),
                'on' => esc_html__('On', 'et_builder'),
            ),
            'show_in_bb' => $post_type === 'page' ? true : false,
            'option_category' => 'basic_option',
            'description' => esc_html__('Enable the dot navigation.'),
            'tab_slug' => 'content',
            'toggle_slug' => 'page_settings',
            'depends_on_post_type' => array('page'),
        ];

        $page_custom_fields['idivi_post_dot_nav'] = [
            'meta_key' => '_idivi_post_dot_nav',
            'default' => $default_post_dot,
            'default_on_front' => true,
            'label' => esc_html__('Dot Navigation', 'et_builder'),
            'id' => 'idivi_post_dot_nav',
            'type' => 'select',
            'options' => array(
                'off' => esc_html__('Off', 'et_builder'),
                'on' => esc_html__('On', 'et_builder'),
            ),
            'show_in_bb' => $post_type === 'post' ? true : false,
            'option_category' => 'basic_option',
            'description' => esc_html__('Enable the dot navigation.'),
            'tab_slug' => 'content',
            'toggle_slug' => 'page_settings',
            'depends_on_post_type' => array('post'),
        ];

        $page_custom_fields['idivi_project_dot_nav'] = [
            'meta_key' => '_idivi_project_dot_nav',
            'default' => $default_project_dot,
            'default_on_front' => true,
            'label' => esc_html__('Dot Navigation', 'et_builder'),
            'id' => 'idivi_project_dot_nav',
            'type' => 'select',
            'options' => array(
                'off' => esc_html__('Off', 'et_builder'),
                'on' => esc_html__('On', 'et_builder'),
            ),
            'show_in_bb' => $post_type === 'project' ? true : false,
            'option_category' => 'basic_option',
            'description' => esc_html__('Enable the dot navigation.'),
            'tab_slug' => 'content',
            'toggle_slug' => 'page_settings',
            'depends_on_post_type' => array('project'),
        ];

        $page_custom_fields['idivi_product_dot_nav'] = [
            'meta_key' => '_idivi_product_dot_nav',
            'default' => $default_product_dot,
            'default_on_front' => true,
            'label' => esc_html__('Dot Navigation', 'et_builder'),
            'id' => 'idivi_product_dot_nav',
            'type' => 'select',
            'options' => array(
                'off' => esc_html__('Off', 'et_builder'),
                'on' => esc_html__('On', 'et_builder'),
            ),
            'show_in_bb' => $post_type === 'product' ? true : false,
            'option_category' => 'basic_option',
            'description' => esc_html__('Enable the dot navigation.'),
            'tab_slug' => 'content',
            'toggle_slug' => 'page_settings',
            'depends_on_post_type' => array('product'),
        ];

        // Adding Hide Before Scroll (page, post, project, product)
        $page_custom_fields['idivi_page_hide_before_scroll'] = [
            'meta_key' => '_idivi_page_hide_before_scroll',
            'default' => $default_page_hide,
            'default_on_front' => true,
            'label' => esc_html__('Hide Nav Before Scroll', 'et_builder'),
            'id' => 'idivi_page_hide_before_scroll',
            'type' => 'select',
            'options' => array(
                'default' => esc_html__('Default', 'et_builder'),
                'no' => esc_html__('Off', 'et_builder'),
                'on' => esc_html__('On', 'et_builder'),
            ),
            'show_in_bb' => $post_type === 'page' ? true : false,
            'option_category' => 'basic_option',
            'description' => esc_html__('Hide nav before scrolling.'),
            'tab_slug' => 'content',
            'toggle_slug' => 'page_settings',
            'depends_on_post_type' => array('page'),
        ];

        $page_custom_fields['idivi_post_hide_before_scroll'] = [
            'meta_key' => '_idivi_post_hide_before_scroll',
            'default' => $default_post_hide,
            'default_on_front' => true,
            'label' => esc_html__('Hide Nav Before Scroll', 'et_builder'),
            'id' => 'idivi_post_hide_before_scroll',
            'type' => 'select',
            'options' => array(
                'default' => esc_html__('Default', 'et_builder'),
                'no' => esc_html__('Off', 'et_builder'),
                'on' => esc_html__('On', 'et_builder'),
            ),
            'show_in_bb' => $post_type === 'post' ? true : false,
            'option_category' => 'basic_option',
            'description' => esc_html__('Hide nav before scrolling.'),
            'tab_slug' => 'content',
            'toggle_slug' => 'page_settings',
            'depends_on_post_type' => array('post'),
        ];

        $page_custom_fields['idivi_project_hide_before_scroll'] = [
            'meta_key' => '_idivi_project_hide_before_scroll',
            'default' => $default_project_hide,
            'default_on_front' => true,
            'label' => esc_html__('Hide Nav Before Scroll', 'et_builder'),
            'id' => 'idivi_project_hide_before_scroll',
            'type' => 'select',
            'options' => array(
                'default' => esc_html__('Default', 'et_builder'),
                'no' => esc_html__('Off', 'et_builder'),
                'on' => esc_html__('On', 'et_builder'),
            ),
            'show_in_bb' => $post_type === 'project' ? true : false,
            'option_category' => 'basic_option',
            'description' => esc_html__('Hide nav before scrolling.'),
            'tab_slug' => 'content',
            'toggle_slug' => 'page_settings',
            'depends_on_post_type' => array('project'),
        ];

        $page_custom_fields['idivi_product_hide_before_scroll'] = [
            'meta_key' => '_idivi_product_hide_before_scroll',
            'default' => $default_product_hide,
            'default_on_front' => true,
            'label' => esc_html__('Hide Nav Before Scroll', 'et_builder'),
            'id' => 'idivi_product_hide_before_scroll',
            'type' => 'select',
            'options' => array(
                'default' => esc_html__('Default', 'et_builder'),
                'no' => esc_html__('Off', 'et_builder'),
                'on' => esc_html__('On', 'et_builder'),
            ),
            'show_in_bb' => $post_type === 'product' ? true : false,
            'option_category' => 'basic_option',
            'description' => esc_html__('Hide nav before scrolling.'),
            'tab_slug' => 'content',
            'toggle_slug' => 'page_settings',
            'depends_on_post_type' => array('product'),
        ];

        // Adding Show Title (post)
        $page_custom_fields['idivi_post_show_title'] = [
            'meta_key' => '_idivi_post_show_title',
            'default' => $default_post_title,
            'default_on_front' => true,
            'label' => esc_html__('Show Title', 'et_builder'),
            'id' => 'idivi_post_show_title',
            'type' => 'select',
            'options' => array(
                'on' => 'Show',
                'off' => 'Hide',
            ),
            'show_in_bb' => $post_type === 'post' ? true : false,
            'option_category' => 'basic_option',
            'description' => esc_html__('Show/Hide the post title.'),
            'tab_slug' => 'content',
            'toggle_slug' => 'page_settings',
            'depends_on_post_type' => array('post'),
        ];
        // Adding Project Navigation (project)
        $page_custom_fields['idivi_project_nav'] = [
            'meta_key' => '_idivi_project_nav',
            'default' => $default_project_nav,
            'default_on_front' => true,
            'label' => esc_html__('Project Navigation', 'et_builder'),
            'id' => 'idivi_project_nav',
            'type' => 'select',
            'options' => array(
                'off' => 'Hide',
                'on' => 'Show',
            ),
            'show_in_bb' => $post_type === 'project' ? true : false,
            'option_category' => 'basic_option',
            'description' => esc_html__('Show/Hide the project navigation.'),
            'tab_slug' => 'content',
            'toggle_slug' => 'page_settings',
            'depends_on_post_type' => array('project'),
        ];

        // Merge Custom settings with the Divi ones (slice them to the top)
        return array_merge(array_slice($page_fields, 0, 0), $page_custom_fields, array_slice($page_fields, 0));
    }

    /**
     * Saving Divi Page Settings values.
     *
     * @since   1.3
     *
     */
    public function idivi_save_page_settings($values)
    {
        $post_id = get_the_ID();
        $post_type = get_post_type($post_id);
        $is_default = array();

		// Get Theme Mods
        $layout_setting_theme_mod = get_theme_mod('idivi_' . $post_type . '_settings_sidebar');
        $dot_theme_mod = get_theme_mod('idivi_' . $post_type . '_settings_dot');
        $hide_before_setting_theme_mod = get_theme_mod('idivi_' . $post_type . '_settings_before_scroll');
        $show_title_setting_theme_mod = get_theme_mod('idivi_post_settings_post_title');
        $project_nav_setting_theme_mod = get_theme_mod('idivi_project_settings_nav');

        // Grab Page settings fields
        $fields = ET_Builder_Settings::get_fields();

        // Get PAGE LAYOUT values (Post and Product)
        $post_layout = get_post_meta($post_id, '_idivi_post_layout', true);
        $default_post_layout = $fields['idivi_post_layout']['default'];
        $et_post_layout = '' !== $post_layout ? $post_layout : $default_post_layout;
        $is_default[] = $et_post_layout === $default_post_layout ? 'idivi_post_layout' : '';

        $product_layout = get_post_meta($post_id, '_idivi_product_layout', true);
        $default_product_layout = $fields['idivi_product_layout']['default'];
        $et_product_layout = '' !== $product_layout ? $product_layout : $default_product_layout;
        $is_default[] = $et_product_layout === $default_product_layout ? 'idivi_product_layout' : '';

        // Save PAGE LAYOUT values
        // Change theme mods if still are stored with old values
        switch ($layout_setting_theme_mod) {
            case 'Right':
                set_theme_mod('idivi_' . $post_type . '_settings_sidebar', 'et_right_sidebar');
                break;
            case 'Left':
                set_theme_mod('idivi_' . $post_type . '_settings_sidebar', 'et_left_sidebar');
                break;
            case 'No':
                set_theme_mod('idivi_' . $post_type . '_settings_sidebar', 'et_no_sidebar');
                break;
            case 'Full':
                set_theme_mod('idivi_' . $post_type . '_settings_sidebar', 'et_full_width_page');
                break;
            default:
                get_theme_mod('idivi_' . $post_type . '_settings_sidebar');
                break;
        }
        // Get Layout theme mod
        $layout_theme_mod = get_theme_mod('idivi_' . $post_type . '_settings_sidebar');

        // Set Layout Page Settings (Post and Product)
        if ($post_layout === '') {
            $layout_post_settings = $layout_theme_mod;
        } else {
            $layout_post_settings = $post_layout;
        }

        if ($product_layout === '') {
            $layout_product_settings = $layout_theme_mod;
        } else {
            $layout_product_settings = $product_layout;
        }

        if ('product' === $post_type) {
            if ($layout_product_settings !== '') {
                update_post_meta($post_id, '_et_pb_page_layout', $layout_product_settings);
            } else {
                delete_post_meta($post_id, '_et_pb_page_layout');
            }
        } else { // if 'post'
            if ($layout_post_settings !== '') {
                update_post_meta($post_id, '_et_pb_page_layout', $layout_post_settings);
            } else {
                delete_post_meta($post_id, '_et_pb_page_layout');
            }
        }

        // Get DOT NAV values
        $page_dot_nav = get_post_meta($post_id, '_idivi_page_dot_nav', true);
        $default_page_dot = $fields['idivi_page_dot_nav']['default'];
        $et_page_dot_nav = '' !== $page_dot_nav ? $page_dot_nav : $default_page_dot;
        $is_default[] = $et_page_dot_nav === $default_page_dot ? 'idivi_page_dot_nav' : '';

        $post_dot_nav = get_post_meta($post_id, '_idivi_post_dot_nav', true);
        $default_post_dot = $fields['idivi_post_dot_nav']['default'];
        $et_post_dot_nav = '' !== $post_dot_nav ? $post_dot_nav : $default_post_dot;
        $is_default[] = $et_post_dot_nav === $default_post_dot ? 'idivi_post_dot_nav' : '';

        $project_dot_nav = get_post_meta($post_id, '_idivi_project_dot_nav', true);
        $default_project_dot = $fields['idivi_project_dot_nav']['default'];
        $et_project_dot_nav = '' !== $project_dot_nav ? $project_dot_nav : $default_project_dot;
        $is_default[] = $et_project_dot_nav === $default_project_dot ? 'idivi_project_dot_nav' : '';

        $product_dot_nav = get_post_meta($post_id, '_idivi_product_dot_nav', true);
        $default_product_dot = $fields['idivi_product_dot_nav']['default'];
        $et_product_dot_nav = '' !== $product_dot_nav ? $product_dot_nav : $default_product_dot;
        $is_default[] = $et_product_dot_nav === $default_product_dot ? 'idivi_product_dot_nav' : '';

        //    SAVE DOT VALUES
        // Change theme mods if still are stored with old values
        switch ($dot_theme_mod) {
            case 'On':
                set_theme_mod('idivi_' . $post_type . '_settings_dot', 'on');
                break;
            case 'Off':
                set_theme_mod('idivi_' . $post_type . '_settings_dot', 'off');
                break;
            default:
                get_theme_mod('idivi_' . $post_type . '_settings_dot');
                break;
        }
        // Get Layout theme mod
        $layout_theme_mod = get_theme_mod('idivi_' . $post_type . '_settings_dot');

        if ($page_dot_nav === '') {
            $dot_nav_page_settings = $layout_theme_mod;
        } else {
            $dot_nav_page_settings = $page_dot_nav;
        }

        if ($post_dot_nav === '') {
            $dot_nav_post_settings = $layout_theme_mod;
        } else {
            $dot_nav_post_settings = $post_dot_nav;
        }

        if ($project_dot_nav === '') {
            $dot_nav_project_settings = $layout_theme_mod;
        } else {
            $dot_nav_project_settings = $project_dot_nav;
        }

        if ($product_dot_nav === '') {
            $dot_nav_product_settings = $layout_theme_mod;
        } else {
            $dot_nav_product_settings = $product_dot_nav;
        }

        if ('page' === $post_type) {
            if ($dot_nav_page_settings !== '') {
                update_post_meta($post_id, '_et_pb_side_nav', $dot_nav_page_settings);
            } else {
                delete_post_meta($post_id, '_et_pb_side_nav');
            }
        } else if ('post' === $post_type) {
            if ($dot_nav_post_settings !== '') {
                update_post_meta($post_id, '_et_pb_side_nav', $dot_nav_post_settings);
            } else {
                delete_post_meta($post_id, '_et_pb_side_nav');
            }
        } else if ('project' === $post_type) {
            if ($dot_nav_project_settings !== '') {
                update_post_meta($post_id, '_et_pb_side_nav', $dot_nav_project_settings);
            } else {
                delete_post_meta($post_id, '_et_pb_side_nav');
            }
        } else {
            if ($dot_nav_product_settings !== '') {
                update_post_meta($post_id, '_et_pb_side_nav', $dot_nav_product_settings);
            } else {
                delete_post_meta($post_id, '_et_pb_side_nav');
            }
        }

        // Get HIDE BEFORE SCROLL values
        $page_hide_before_scroll = get_post_meta($post_id, '_idivi_page_hide_before_scroll', true);
        $default_page_hide = $fields['idivi_page_hide_before_scroll']['default'];
        $et_page_hide_before_scroll = '' !== $page_hide_before_scroll ? $page_hide_before_scroll : $default_page_hide;
        $is_default[] = $et_page_hide_before_scroll === $default_page_hide ? 'idivi_page_hide_before_scroll' : '';

        $post_hide_before_scroll = get_post_meta($post_id, '_idivi_post_hide_before_scroll', true);
        $default_post_hide = $fields['idivi_post_hide_before_scroll']['default'];
        $et_post_hide_before_scroll = '' !== $post_hide_before_scroll ? $post_hide_before_scroll : $default_post_hide;
        $is_default[] = $et_post_hide_before_scroll === $default_post_hide ? 'idivi_post_hide_before_scroll' : '';

        $project_hide_before_scroll = get_post_meta($post_id, '_idivi_project_hide_before_scroll', true);
        $default_project_hide = $fields['idivi_project_hide_before_scroll']['default'];
        $et_project_hide_before_scroll = '' !== $project_hide_before_scroll ? $project_hide_before_scroll : $default_project_hide;
        $is_default[] = $et_project_hide_before_scroll === $default_project_hide ? 'idivi_project_hide_before_scroll' : '';

        $product_hide_before_scroll = get_post_meta($post_id, '_idivi_product_hide_before_scroll', true);
        $default_product_hide = $fields['idivi_product_hide_before_scroll']['default'];
        $et_product_hide_before_scroll = '' !== $product_hide_before_scroll ? $product_hide_before_scroll : $default_product_hide;
        $is_default[] = $et_product_hide_before_scroll === $default_product_hide ? 'idivi_product_hide_before_scroll' : '';

        //    Save HIDE BEFORE SCROLL values
        switch ($hide_before_setting_theme_mod) {
            case 'Default':
                set_theme_mod('idivi_' . $post_type . '_settings_before_scroll', 'default');
                break;
            case 'Off':
                set_theme_mod('idivi_' . $post_type . '_settings_before_scroll', 'no');
                break;
            case 'On':
                set_theme_mod('idivi_' . $post_type . '_settings_before_scroll', 'on');
                break;
            default:
                get_theme_mod('idivi_' . $post_type . '_settings_before_scroll');
                break;
        }

        $hide_before_theme_mod = get_theme_mod('idivi_' . $post_type . '_settings_before_scroll');

        // set Hide Before Settings
        if ($page_hide_before_scroll === '') {
            $hide_before_page_settings = $hide_before_theme_mod;
        } else {
            $hide_before_page_settings = $page_hide_before_scroll;
        }

        if ($post_hide_before_scroll === '') {
            $hide_before_post_settings = $hide_before_theme_mod;
        } else {
            $hide_before_post_settings = $post_hide_before_scroll;
        }

        if ($project_hide_before_scroll === '') {
            $hide_before_project_settings = $hide_before_theme_mod;
        } else {
            $hide_before_project_settings = $project_hide_before_scroll;
        }

        if ($product_hide_before_scroll === '') {
            $hide_before_product_settings = $hide_before_theme_mod;
        } else {
            $hide_before_product_settings = $product_hide_before_scroll;
        }

        if ('page' === $post_type) {
            if ($hide_before_page_settings !== 'no') {
                update_post_meta($post_id, '_et_pb_post_hide_nav', $hide_before_page_settings);
            } else {
                delete_post_meta($post_id, '_et_pb_post_hide_nav');
            }
        } else if ('post' === $post_type) {
            if ($hide_before_post_settings !== 'no') {
                update_post_meta($post_id, '_et_pb_post_hide_nav', $hide_before_post_settings);
            } else {
                delete_post_meta($post_id, '_et_pb_post_hide_nav');
            }
        } else if ('project' === $post_type) {
            if ($hide_before_project_settings !== 'no') {
                update_post_meta($post_id, '_et_pb_post_hide_nav', $hide_before_project_settings);
            } else {
                delete_post_meta($post_id, '_et_pb_post_hide_nav');
            }
        } else if ('product' === $post_type) {
            if ($hide_before_product_settings !== 'no') {
                update_post_meta($post_id, '_et_pb_post_hide_nav', $hide_before_product_settings);
            } else {
                delete_post_meta($post_id, '_et_pb_post_hide_nav');
            }
        }

        // Get SHOW TITLE values
        $post_show_title = get_post_meta($post_id, '_idivi_post_show_title', true);
        $default_show_title = $fields['idivi_post_show_title']['default'];
        $et_post_show_title = '' !== $post_show_title ? $post_show_title : $default_show_title;
        $is_default[] = $et_post_show_title === $default_show_title ? 'idivi_post_show_title' : '';

        //    save SHOW TITLE values
        switch ($show_title_setting_theme_mod) {
            case 'Show':
                set_theme_mod('idivi_post_settings_post_title', 'on');
                break;
            case 'Hide':
                set_theme_mod('idivi_post_settings_post_title', 'off');
                break;
            default:
                get_theme_mod('idivi_post_settings_post_title');
                break;
        }

        $show_title_theme_mod = get_theme_mod('idivi_post_settings_post_title');

        if ($post_show_title === '') {
            $show_title_post_settings = $show_title_theme_mod;
        } else {
            $show_title_post_settings = $post_show_title;
        }

        if ('post' === $post_type) {
            if ($show_title_post_settings !== 'on') {
                update_post_meta($post_id, '_et_pb_show_title', $show_title_post_settings);
            } else {
                delete_post_meta($post_id, '_et_pb_show_title');
            }
        }

        // Get PROJECT NAV values
        $project_show_nav = get_post_meta($post_id, '_idivi_project_nav', true);
        $default_project_nav = $fields['idivi_project_nav']['default'];
        $et_project_show_nav = '' !== $project_show_nav ? $project_show_nav : $default_project_nav;
        $is_default[] = $et_project_show_nav === $default_project_nav ? 'idivi_project_nav' : '';

        //    save PROJECT NAV values
        switch ($project_nav_setting_theme_mod) {
            case 'Show':
                set_theme_mod('idivi_project_settings_nav', 'on');
                break;
            case 'Hide':
                set_theme_mod('idivi_project_settings_nav', 'off');
                break;
            default:
                get_theme_mod('idivi_project_settings_nav');
                break;
        }

        $project_nav_theme_mod = get_theme_mod('idivi_project_settings_nav');
        if ($project_show_nav === '') {
            $project_nav_post_settings = $project_nav_theme_mod;
        } else {
            $project_nav_post_settings = $project_show_nav;
        }

        if ('project' === $post_type) {
            if ($project_nav_post_settings !== 'off') {
                update_post_meta($post_id, '_et_pb_project_nav', $project_nav_post_settings);
            } else {
                delete_post_meta($post_id, '_et_pb_project_nav');
            }
        }

        $post = get_post($post_id);

        $custom_values = array(
            'idivi_post_layout' => $et_post_layout,
            'idivi_product_layout' => $et_product_layout,

            'idivi_page_dot_nav' => $et_page_dot_nav,
            'idivi_post_dot_nav' => $et_post_dot_nav,
            'idivi_project_dot_nav' => $et_project_dot_nav,
            'idivi_product_dot_nav' => $et_product_dot_nav,

            'idivi_page_hide_before_scroll' => $et_page_hide_before_scroll,
            'idivi_post_hide_before_scroll' => $et_post_hide_before_scroll,
            'idivi_project_hide_before_scroll' => $et_project_hide_before_scroll,
            'idivi_product_hide_before_scroll' => $et_product_hide_before_scroll,

            'idivi_post_show_title' => $et_post_show_title,

            'idivi_project_nav' => $et_project_show_nav,
        );

        return array_merge($values, $custom_values);
    }

/**
 * Add options to the Theme Customizer (Blog panel)
 *
 * @since 1.1
 * @since 1.3 (Refactored)
 *
 */
    public function post_settings_options($wp_customize)
    {

        $post_array = array(
            'post',
            'page',
            'project',
            'product',
        );
        $post_array_except_post = array(
            'page',
            'project',
        );

        // Add Dot Nav, Hide Before and Remember Last for All post types
        foreach ($post_array as $post_type) {
            $wp_customize->add_section('idivi_' . $post_type . '_settings_section', array(
                'title' => __('Divi ' . ucfirst($post_type) . ' Settings', $this->plugin_name),
                'panel' => 'et_divi_blog_settings',
            ));
            $wp_customize->add_setting('idivi_' . $post_type . '_settings_dot', array(
                'default' => 'off',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
            ));
            $wp_customize->add_control('idivi_' . $post_type . '_settings_dot_nav', array(
                'label' => __('Dot Navigation', $this->plugin_name),
                'section' => 'idivi_' . $post_type . '_settings_section',
                'type' => 'select',
                'choices' => array(
                    'off' => 'Off',
                    'on' => 'On',
                ),
                'priority' => 5,
                'settings' => 'idivi_' . $post_type . '_settings_dot',
            ));
            $wp_customize->add_setting('idivi_' . $post_type . '_settings_before_scroll', array(
                'default' => 'default',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
            ));
            $wp_customize->add_control('idivi_' . $post_type . '_settings_hide_before_scroll', array(
                'label' => __('Hide Nav Before Scroll', $this->plugin_name),
                'section' => 'idivi_' . $post_type . '_settings_section',
                'type' => 'select',
                'choices' => array(
                    'default' => 'Default',
                    'no' => 'Off',
                    'on' => 'On',
                ),
                'priority' => 5,
                'settings' => 'idivi_' . $post_type . '_settings_before_scroll',
            ));

        }
        // Add Page Layout Notices for Pages and Projects
        foreach ($post_array_except_post as $post_type) {
            $wp_customize->add_setting('idivi_' . $post_type . '_settings_sidebar', array(
                'default' => 'no_sidebar',
                'type' => 'theme_mod',
                'capability' => 'edit_theme_options',
            ));
            $wp_customize->add_control('idivi_' . $post_type . '_settings_layout', array(
                'label' => __(ucfirst($post_type) . ' Layout', $this->plugin_name),
                'description' => __('By default ' . ucfirst($post_type) . 's can be only No Sidebar when using Divi Builders', $this->plugin_name),
                'section' => 'idivi_' . $post_type . '_settings_section',
                'type' => 'select',
                'choices' => array(
                    /*     'et_right_sidebar' => 'Right Sidebar',
                    'et_left_sidebar' => 'Left Sidebar', */
                    'et_no_sidebar' => 'No Sidebar',
                ),
                'priority' => 1,
                'settings' => 'idivi_' . $post_type . '_settings_sidebar',
            ));
        }
        // Add Page Layout settings for Products
        $wp_customize->add_setting('idivi_product_settings_sidebar', array(
            'default' => 'et_right_sidebar',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
        ));
        $wp_customize->add_control('idivi_product_settings_layout', array(
            'label' => __('Product Layout', $this->plugin_name),
            'section' => 'idivi_product_settings_section',
            'type' => 'select',
            'choices' => array(
                'et_right_sidebar' => 'Right Sidebar',
                'et_left_sidebar' => 'Left Sidebar',
                'et_no_sidebar' => 'No Sidebar',
            ),
            'priority' => 1,
            'settings' => 'idivi_product_settings_sidebar',
        ));

        // Add Page Layout settings for Posts
        $wp_customize->add_setting('idivi_post_settings_sidebar', array(
            'default' => 'et_right_sidebar',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
        ));
        $wp_customize->add_control('idivi_post_settings_layout', array(
            'label' => __('Post Layout', $this->plugin_name),
            'section' => 'idivi_post_settings_section',
            'type' => 'select',
            'choices' => array(
                'et_right_sidebar' => 'Right Sidebar',
                'et_left_sidebar' => 'Left Sidebar',
                'et_no_sidebar' => 'No Sidebar',
                'et_full_width_page' => 'Fullwidth',
            ),
            'priority' => 1,
            'settings' => 'idivi_post_settings_sidebar',
        ));

        $wp_customize->add_setting('idivi_post_settings_post_title', array(
            'default' => 'on',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
        ));
        $wp_customize->add_control('idivi_post_settings_post_title_show', array(
            'label' => __('Post Title', $this->plugin_name),
            'section' => 'idivi_post_settings_section',
            'type' => 'select',
            'choices' => array(
                'on' => 'Show',
                'off' => 'Hide',
            ),
            'priority' => 5,
            'settings' => 'idivi_post_settings_post_title',
		));
		$wp_customize->add_setting('idivi_post_settings_metabox', array(
		    'default' => false,
		    'type' => 'theme_mod',
		    'capability' => 'edit_theme_options',
	        ));
	    $wp_customize->add_control('idivi_post_settings_metabox_dismiss', array(
		    'label' => __('Dismiss Metabox', $this->plugin_name),
		    'section' => 'idivi_post_settings_section',
		    'type' => 'checkbox',
		    'priority' => 10,
		    'settings' => 'idivi_post_settings_metabox'
             ));

        // Add Project Nav settings for Projects
        $wp_customize->add_setting('idivi_project_settings_nav', array(
            'default' => 'off',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
        ));
        $wp_customize->add_control('idivi_project_settings_nav_show', array(
            'label' => __('Project Navigation', $this->plugin_name),
            'section' => 'idivi_project_settings_section',
            'type' => 'select',
            'choices' => array(
                'off' => 'Hide',
                'on' => 'Show',
            ),
            'priority' => 5,
            'settings' => 'idivi_project_settings_nav',
        ));

    }

}
