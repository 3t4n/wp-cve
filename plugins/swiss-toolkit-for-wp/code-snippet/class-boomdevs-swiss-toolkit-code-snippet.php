<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include the settings class to retrieve settings from the settings panel.
require_once BDSTFW_SWISS_TOOLKIT_PATH . 'includes/class-boomdevs-swiss-toolkit-settings.php';

/**
 * Define the BDSTFW_Swiss_Toolkit_Code_Snippet for handling code snippets.
 * 
 * @package    BDSTFW_Swiss_Toolkit_Code_Snippet
 * @author     BoomDevs <contact@boomdevs.com>
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Code_Snippet')) {
    class BDSTFW_Swiss_Toolkit_Code_Snippet
    {
        /**
         * The single instance of the class.
         */
        protected static $instance;

        /**
         * Returns single instance of the class
         */
        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Constructor.
         * Initializes the class and registers actions and hooks.
         */
        public function __construct()
        {
            $settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();

            if (isset($settings['boomdevs_swiss_insert_header_footer_switch'])) {
                if ($settings['boomdevs_swiss_insert_header_footer_switch'] === '1') {
                    add_action('init', [$this, 'register_snippet_post_type'], 0);
                    add_action('admin_menu', [$this, 'snippets_page']);
                    add_action('admin_init', [$this, 'custom_metabox']);
                    add_filter('post_updated_messages', [$this, 'post_updated_messages']);
                    add_action("save_post_swiss_snippets", [$this, "swiss_snippets_save_post"]);
                    add_action('admin_enqueue_scripts', [$this, 'codemirror_enqueue_scripts']);
                    add_filter('post_row_actions', [$this, 'remove_snippets_row_actions'], 10, 1);
                }
            }
        }

        /**
         * Customize row actions for Swiss Snippet posts in the WordPress admin table.
         *
         * This method allows customization of the row actions displayed for Swiss Snippet posts
         * in the WordPress admin table, providing a streamlined user experience.
         *
         * @param array $actions An array of row action links.
         * @return array Remove array of row action links.
         */
        public function remove_snippets_row_actions($actions)
        {
            if (get_post_type() === 'swiss_snippets') {
                unset($actions['view']);
                unset($actions['inline hide-if-no-js']);
            }
            return $actions;
        }

        /**
         * Define custom update messages for Swiss Snippet posts.
         *
         * This method defines custom update messages to provide informative feedback to users when
         * interacting with Swiss Snippet posts in the WordPress admin area.
         *
         * @param array $messages An array of post update messages.
         * @return array Modified array of post update messages.
         */
        public function post_updated_messages($messages)
        {
            global $post;

            $post_type = get_post_type($post);

            if ($post_type === 'swiss_snippets') {
                $messages['post'][1] = esc_html__('Snippet Updated successfully!', 'swiss-toolkit-for-wp');
                $messages['post'][4] = esc_html__('Snippet Updated!', 'swiss-toolkit-for-wp');
                $messages['post'][6] = esc_html__('Snippet Created successfully!', 'swiss-toolkit-for-wp');
                $messages['post'][7] = esc_html__('Snippet Saved!', 'swiss-toolkit-for-wp');
                $messages['post'][10] = esc_html__('Snippet Drafted successfully!', 'swiss-toolkit-for-wp');
            }

            return $messages;
        }

        /**
         * Update the post title for Swiss Snippet posts if it's empty.
         *
         * This method checks if the post title is empty and, if so, updates it with a generated
         * title based on the provided post title and post ID. This ensures that Swiss Snippet
         * posts have meaningful titles.
         *
         * @param int    $post_id    The ID of the Swiss Snippet post.
         * @param string $post_title The desired post title.
         */
        public function swiss_knife_update_post_title($post_id, $post_title)
        {
            $exist_title = get_the_title($post_id);
            if ($exist_title === '') {
                $post = array(
                    'ID'           => $post_id,
                    'post_title'   => $post_title . ' #' . $post_id
                );

                // Update the post into the database
                wp_update_post($post);
            }
        }

        /**
         * Enqueue CodeMirror scripts and styles for code editing.
         *
         * This method dynamically loads the CodeMirror editor scripts and styles based on the
         * selected language for the code snippet post, enhancing the code editing experience in
         * the WordPress admin.
         *
         * @param string $hook The current admin page hook.
         */
        public function codemirror_enqueue_scripts($hook)
        {
            global $post;
            if (!($post === NULL)) {
                $language = get_post_meta($post->ID, 'bdstfw_code_snippets_language', true);
                if ($language === 'php') {
                    $selected_language = 'text/x-php';
                } else if ($language === 'html') {
                    $selected_language = 'text/html';
                } else if ($language === 'universal') {
                    $selected_language = 'application/x-httpd-php';
                } else if ($language === 'css') {
                    $selected_language = 'text/css';
                } else if ($language === 'js') {
                    $selected_language = 'application/x-javascript';
                } else {
                    $selected_language = 'text/x-php';
                }

                // Enqueue code editor and settings for manipulating HTML.
                $settings = wp_enqueue_code_editor(array('type' => $selected_language));

                // Return if the editor was not enqueued.
                if (false === $settings) {
                    return;
                }
                wp_enqueue_script('htmlhint');
                wp_enqueue_script('csslint');
                wp_enqueue_script('jshint');
                if (!current_user_can('unfiltered_html')) {
                    wp_enqueue_script('htmlhint-kses');
                }
                wp_add_inline_script(
                    'code-editor',
                    sprintf(
                        'jQuery( function() { wp.codeEditor.initialize( "code_snippets_textarea", %s ); } );',
                        wp_json_encode($settings)
                    )
                );
            }
        }

        /**
         * Register a custom post type for managing code snippets.
         *
         * This method defines and registers a custom post type named 'swiss_snippets' to
         * facilitate the management of code snippets in the WordPress admin area.
         */
        public function register_snippet_post_type()
        {

            $labels = array(
                'name'                  => esc_html__('Code Snippets', 'Post Type General Name', 'swiss-toolkit-for-wp'),
                'singular_name'         => esc_html__('Post Type', 'Post Type Singular Name', 'swiss-toolkit-for-wp'),
                'menu_name'             => esc_html__('Post Types', 'swiss-toolkit-for-wp'),
                'name_admin_bar'        => esc_html__('Post Type', 'swiss-toolkit-for-wp'),
                'archives'              => esc_html__('Item Archives', 'swiss-toolkit-for-wp'),
                'attributes'            => esc_html__('Item Attributes', 'swiss-toolkit-for-wp'),
                'parent_item_colon'     => esc_html__('Parent Item:', 'swiss-toolkit-for-wp'),
                'all_items'             => esc_html__('All Items', 'swiss-toolkit-for-wp'),
                'add_new_item'          => esc_html__('Add New Snippet', 'swiss-toolkit-for-wp'),
                'add_new'               => esc_html__('Add New', 'swiss-toolkit-for-wp'),
                'new_item'              => esc_html__('New Item', 'swiss-toolkit-for-wp'),
                'edit_item'             => esc_html__('Edit Snippet', 'swiss-toolkit-for-wp'),
                'update_item'           => esc_html__('Update Item', 'swiss-toolkit-for-wp'),
                'view_item'             => esc_html__('View Item', 'swiss-toolkit-for-wp'),
                'view_items'            => esc_html__('View Items', 'swiss-toolkit-for-wp'),
                'search_items'          => esc_html__('Search Item', 'swiss-toolkit-for-wp'),
                'not_found'             => esc_html__('Not found', 'swiss-toolkit-for-wp'),
                'not_found_in_trash'    => esc_html__('Not found in Trash', 'swiss-toolkit-for-wp'),
                'featured_image'        => esc_html__('Featured Image', 'swiss-toolkit-for-wp'),
                'set_featured_image'    => esc_html__('Set featured image', 'swiss-toolkit-for-wp'),
                'remove_featured_image' => esc_html__('Remove featured image', 'swiss-toolkit-for-wp'),
                'use_featured_image'    => esc_html__('Use as featured image', 'swiss-toolkit-for-wp'),
                'insert_into_item'      => esc_html__('Insert into', 'swiss-toolkit-for-wp'),
                'uploaded_to_this_item' => esc_html__('Uploaded to this item', 'swiss-toolkit-for-wp'),
                'items_list'            => esc_html__('Items list', 'swiss-toolkit-for-wp'),
                'items_list_navigation' => esc_html__('Items list navigation', 'swiss-toolkit-for-wp'),
                'filter_items_list'     => esc_html__('Filter items list', 'swiss-toolkit-for-wp'),
            );
            $args = array(
                'label'                 => esc_html__('Snippet', 'swiss-toolkit-for-wp'),
                'description'           => esc_html__('Snippet Description', 'swiss-toolkit-for-wp'),
                'labels'                => $labels,
                'supports'              => array('title'),
                'hierarchical'          => false,
                'public'                => false,
                'show_ui'               => true,
                'show_in_menu'          => false,
                'menu_position'         => 5,
                'show_in_admin_bar'     => false,
                'show_in_nav_menus'     => false,
                'can_export'            => true,
                'has_archive'           => true,
                'exclude_from_search'   => false,
                'publicly_queryable'    => true,
                'capability_type'       => 'page',
            );

            register_post_type('swiss_snippets', $args);
        }

        /**
         * Add menu pages for managing Swiss Snippet posts.
         *
         * This method adds menu pages in the WordPress admin for managing Swiss Snippet posts,
         * including a top-level 'Snippets' menu and a submenu for adding new snippets.
         */
        public function snippets_page()
        {
            add_menu_page(
                esc_html__('Snippets', 'swiss-toolkit-for-wp'),
                esc_html__('Snippets', 'swiss-toolkit-for-wp'),
                'manage_options',
                'edit.php?post_type=swiss_snippets',
                '',
                BDSTFW_SWISS_TOOLKIT_URL . 'admin/img/toolkit-settings.svg'
            );

            add_submenu_page(
                'edit.php?post_type=swiss_snippets',
                esc_html__('Add New Snippet', 'swiss-toolkit-for-wp'),
                esc_html__('Add New Snippet', 'swiss-toolkit-for-wp'),
                'manage_options',
                'post-new.php?post_type=swiss_snippets'
            );
        }

        /**
         * Add custom metaboxes for Swiss Snippet post editing.
         *
         * This method adds custom metaboxes to the Swiss Snippet post editing screen in the WordPress admin.
         * These metaboxes provide options for editing the code snippet and enabling/disabling it.
         */
        public function custom_metabox()
        {
            add_meta_box('code_snippet', esc_html__('Code Snippet', 'swiss-toolkit-for-wp'), [$this, 'custom_metabox_field'], 'swiss_snippets', 'normal', 'default');
            add_meta_box('enable_disable', esc_html__('Enable/Disable', 'swiss-toolkit-for-wp'), [$this, 'custom_enable_disabled_field'], 'swiss_snippets', 'side', 'default');
        }

        /**
         * Render the custom metabox field for editing Swiss Snippet posts.
         *
         * This method generates the HTML markup for the custom metabox field, providing options for
         * editing the code snippet, including language selection and location.
         *
         * @param WP_Post $post The current post object.
         */
        public function custom_metabox_field($post)
        {
            $value = get_post_meta($post->ID, 'bdstfw_code_snippets_textarea', true);
            $language = get_post_meta($post->ID, 'bdstfw_code_snippets_language', true);
            $location = get_post_meta($post->ID, 'bdstfw_code_snippets_location', true);

            if($language === 'css') {
                $code_mirror_before = '<style type="text/css">';
                $code_mirror_after  = '</style>';
                $code_mirror_mode   = 'text/css';
            } elseif ($language === 'js') {
                $code_mirror_mode   = 'text/javascript';
                $code_mirror_before = '<script type="text/javascript">';
                $code_mirror_after  = '</script>';
            } elseif ($language === 'html') {
                $code_mirror_mode   = 'html';
                $code_mirror_before = '';
                $code_mirror_after  = '';
            } elseif ($language === 'php') {
                $code_mirror_mode   = 'php';
                $code_mirror_before = '<?php';
                $code_mirror_after  = '?>';
            } else {
                $code_mirror_mode   = '';
                $code_mirror_before = '';
                $code_mirror_after  = '';
            }
?>
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; border-bottom: 1px solid #e9e9e9;">
                    <h2 style="font-weight: 700; font-size: 15px; padding: 0;"><?php esc_html_e('Options', 'swiss-toolkit-for-wp'); ?></h2>
                    <div>
                        <select name="language" id="swiss_knife_lang_switch">
                            <option <?php selected($language, 'php'); ?> value="php"><?php esc_html_e('PHP', 'swiss-toolkit-for-wp'); ?></option>
                            <option <?php selected($language, 'html'); ?> value="html"><?php esc_html_e('HTML', 'swiss-toolkit-for-wp'); ?></option>
                            <option <?php selected($language, 'universal'); ?> value="universal"><?php esc_html_e('Universal Snippet', 'swiss-toolkit-for-wp'); ?></option>
                            <option <?php selected($language, 'css'); ?> value="css"><?php esc_html_e('CSS', 'swiss-toolkit-for-wp'); ?></option>
                            <option <?php selected($language, 'js'); ?> value="js"><?php esc_html_e('JS', 'swiss-toolkit-for-wp'); ?></option>
                        </select>

                        <select name="location">
                            <option <?php selected($location, 'header'); ?> value="header"><?php esc_html_e('Header', 'swiss-toolkit-for-wp'); ?></option>
                            <option <?php selected($location, 'footer'); ?> value="footer"><?php esc_html_e('Footer', 'swiss-toolkit-for-wp'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="swiss_knife_snippet" data-code-type="<?php printf(esc_html__('%s', 'swiss-toolkit-for-wp'), esc_html($language)); ?>">
                    <div class="code-mirror-before"><div><?php echo htmlentities( $code_mirror_before ); ?></div></div>
                    <textarea id="code_snippets_textarea" name="code_snippets_textarea" mode="<?php echo htmlentities( $code_mirror_mode ); ?>"><?php echo esc_textarea($value); ?></textarea>
                    <div class="code-mirror-after"><div><?php echo htmlentities( $code_mirror_after ); ?></div></div>
                </div>
            </div>
        <?php
        }

        /**
         * Render the custom field for enabling/disabling Swiss Snippet posts.
         *
         * This method generates the HTML markup for the custom field, allowing users to enable or disable a snippet.
         *
         * @param WP_Post $post The current post object.
         */
        public function custom_enable_disabled_field($post)
        {
            $toggle = get_post_meta($post->ID, 'bdstfw_code_snippets_toggle', true);
        ?>
            <div style="display: flex; align-items: center; gap: 20px;">
                <p><?php esc_html_e('Enable', 'swiss-toolkit-for-wp'); ?></p>
                <label for="code_snippets_toggle" class="switch__thumb">
                    <input type="checkbox" id="code_snippets_toggle" name="code_snippets_toggle" <?php printf(esc_html__('%s', 'swiss-toolkit-for-wp'), esc_html(($toggle === 'on') ? 'checked' : '')); ?> />
                    <span class="toggle"></span>
                </label>
            </div>
<?php
        }

        /**
         * Save or update Swiss Snippet post data when the post is saved or updated.
         *
         * This method handles the saving of snippet data, including language, code, and location.
         *
         * @param int $post_id The ID of the post being saved.
         */
        public function swiss_snippets_save_post($post_id)
        {
            // Check if bdstfw_code_snippets_textarea is set in the POST data
            if (isset($_POST['code_snippets_textarea'])) {
                // Update post meta for language, code, and location
                update_post_meta($post_id, 'bdstfw_code_snippets_language', sanitize_text_field($_POST['language']));
                update_post_meta($post_id, 'bdstfw_code_snippets_textarea', $_POST['code_snippets_textarea']);
                update_post_meta($post_id, 'bdstfw_code_snippets_location', sanitize_text_field($_POST['location']));
            }

            // Check if bdstfw_code_snippets_toggle is set in the POST data
            if (isset($_POST['code_snippets_toggle'])) {
                // Update or delete post meta for bdstfw_code_snippets_toggle based on the checkbox state
                update_post_meta($post_id, 'bdstfw_code_snippets_toggle', 'on');
            } else {
                delete_post_meta($post_id, 'bdstfw_code_snippets_toggle');
            }

            // Update post title if it's empty
            $this->swiss_knife_update_post_title($post_id, 'Snippet');
        }
    }

    // Initialize the BDSTFW_Code_Snippet class
    BDSTFW_Swiss_Toolkit_Code_Snippet::get_instance();
}
