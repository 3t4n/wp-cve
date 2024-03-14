<?php
require_once BDSTFW_SWISS_TOOLKIT_PATH . 'utils/Translator.php';
require_once BDSTFW_SWISS_TOOLKIT_PATH . 'utils/Random.php';

// Prevent direct access to this file.
if (!defined('ABSPATH')) {
    exit;
}

// Include the Swiss Toolkit Settings class to manage plugin settings.
require_once BDSTFW_SWISS_TOOLKIT_PATH . 'includes/class-boomdevs-swiss-toolkit-settings.php';

// Include the Composer autoload file to load plugin dependencies.
require_once BDSTFW_SWISS_TOOLKIT_PATH . 'vendor/autoload.php';

/**
 * Class BDSTFW_Swiss_Toolkit_Generate_URL
 * Manages URL generation functionality.
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Generate_URL')) {
    class BDSTFW_Swiss_Toolkit_Generate_URL
    {
        /**
         * The single instance of the class.
         */
        protected static $instance;
        static $prefix = 'generate_url_options';

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
         * Initializes the Generate_URL class.
         */
        public function __construct()
        {
            $settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();

            // Check if URL generation switch is enabled in settings
            if ($settings && isset($settings['boomdevs_swiss_generate_url_switch']) && $settings['boomdevs_swiss_generate_url_switch'] === '1') {
                // Register necessary actions and filters for URL generation
                add_action('init', [$this, 'register_swiss_generate_url_post_type'], 0);
                add_action('admin_menu', [$this, 'add_generate_url_admin_menu_pages']);
                // add_action('add_meta_boxes', [$this, 'remove_publish_metabox']);
                add_action('save_post_swiss_generate_url', [$this, 'save_swiss_generate_url_post']);
                add_filter('post_updated_messages', [$this, 'modify_generate_url_post_updated_messages']);
                add_action('manage_swiss_generate_url_posts_custom_column', [$this, 'render_generate_url_column_content'], 10, 2);
                add_filter('manage_swiss_generate_url_posts_columns', [$this, 'modify_generate_url_columns']);
                add_filter('post_row_actions', [$this, 'modify_generate_url_row_actions'], 10, 1);
                // add_action('init', [$this, 'check_user_has_permission']);

                // Initialize create_generate_url_metaboxes for URL generation options
                $this->create_generate_url_metaboxes();
            }
        }


        /**
         * Modify row actions for Swiss Generate URL posts in the post table.
         *
         * @param array $actions The existing row actions.
         * @return array The modified row actions.
         */
        public function modify_generate_url_row_actions($actions)
        {
            // Check if the current post type is 'swiss_generate_url'
            if (get_post_type() === 'swiss_generate_url') {
                // Remove unnecessary row actions
                unset($actions['view']);
                unset($actions['inline hide-if-no-js']);

                // Add a custom row action for copying URL token
                $actions['copy_generated_url'] = '<span class="copy_url_token"><a aria-label="Copy URL Token">' . esc_html__('Copy URL Token', 'swiss-toolkit-for-wp') . '</a></span>';
            }

            return $actions;
        }

        /**
         * Modify post updated messages for Swiss Generate URL posts.
         *
         * @param array $messages The existing post updated messages.
         * @return array The modified post updated messages.
         */
        public function modify_generate_url_post_updated_messages($messages)
        {
            global $post;

            $post_type = get_post_type($post);

            // Check if the current post type is 'swiss_generate_url'
            if ($post_type === 'swiss_generate_url') {
                $messages['post'][1] = esc_html__('Token Updated successfully!', 'swiss-toolkit-for-wp');
                $messages['post'][4] = esc_html__('Token Updated!', 'swiss-toolkit-for-wp');
                $messages['post'][6] = esc_html__('Token Created successfully!', 'swiss-toolkit-for-wp');
                $messages['post'][7] = esc_html__('Token Saved!', 'swiss-toolkit-for-wp');
                $messages['post'][10] = esc_html__('Token Drafted successfully!', 'swiss-toolkit-for-wp');
            }

            return $messages;
        }

        /**
         * Modify the columns for the 'swiss_generate_url' post type.
         *
         * @param array $columns The existing post columns.
         * @return array The modified post columns.
         */
        public function modify_generate_url_columns($columns)
        {
            $new_columns = array();

            foreach ($columns as $key => $value) {
                if ($key === 'date') {
                    $new_columns['generate_url'] = esc_html__('Generated URL', 'swiss-toolkit-for-wp');
                    $new_columns['expire'] = esc_html__('Expire Date', 'swiss-toolkit-for-wp');
                    $new_columns['status'] = esc_html__('Status', 'swiss-toolkit-for-wp');
                }
                $new_columns[$key] = $value;
            }

            return $new_columns;
        }


        /**
         * Render content for custom columns in the 'swiss_generate_url' post type.
         *
         * @param string $column The column name.
         * @param int $post_id The post ID.
         */
        public function render_generate_url_column_content($column, $post_id)
        {
            if ($column === 'generate_url') {
                $custom_data = get_post_meta(intval($post_id), 'bdstfw_encrypted_token', true);
                if (!empty($custom_data)) {
                    $login_url = home_url('?sk=') . $custom_data;
                    echo wp_kses('<span class="copy_generate_url">' . esc_url($login_url) . '</span>', array(
                        'span' => array(
                            'class' => true,
                        ),
                    ));
                }
            } elseif ($column === 'status' || $column === 'expire') {
                $expiration_time = get_post_meta(intval($post_id), 'bdstfw_swiss_expiration_time', true);
                $usage_count = get_post_meta(intval($post_id), 'bdstfw_swiss_usage_count', true);
                $expiration_limit = get_post_meta(intval($post_id), 'bdstfw_swiss_usage_limitation', true);

                if ($expiration_time) {
                    if ($column === 'status') {
                        if ($expiration_time > time() && $usage_count < $expiration_limit) {
                            $status = esc_html__('Active', 'swiss-toolkit-for-wp');
                        } else {
                            $status = esc_html__('Expired', 'swiss-toolkit-for-wp');
                        }
                        printf(
                            esc_html__('%s', 'swiss-toolkit-for-wp'),
                            esc_html($status)
                        );
                    } elseif ($column === 'expire') {
                        printf(
                            esc_html__('%s', 'swiss-toolkit-for-wp'),
                            esc_html(date("M d, Y, h:i A", $expiration_time))
                        );
                    }
                } else {
                    esc_html_e('N/A', 'swiss-toolkit-for-wp');
                }
            }
        }

        /**
         * Remove the 'Publish' metabox from 'swiss_generate_url' post type.
         */
        // public function remove_publish_metabox()
        // {
        //     remove_meta_box('submitdiv', 'swiss_generate_url', 'side');
        // }

        /**
         * Generate a preview URL for 'swiss_generate_url' posts.
         */
        public function generate_preview_url()
        {
            if (isset($_GET['post'])) {
                $token = home_url() . '?sk=' . get_post_meta(intval($_GET['post']), 'bdstfw_encrypted_token', true);

                Redux_Metaboxes::set_box(
                    BDSTFW_Swiss_Toolkit_Settings::$prefix,
                    array(
                        'id' => 'generate_url_preview_meta',
                        'title' => esc_html__('Preview URL', 'swiss-toolkit-for-wp'),
                        'post_types' => array('swiss_generate_url'),
                        'position' => 'normal',
                        'priority' => 'high',
                        'sections' => array(
                            array(
                                'fields' => array(
                                    array(
                                        'id' => 'preview_content_url',
                                        'type' => 'raw',
                                        'content' => esc_url($token),
                                        'class' => 'copy_preview_url'
                                    ),
                                ),
                            ),
                        ),
                    )
                );
            }
        }

        /**
         * Create metaboxes for 'swiss_generate_url' post type.
         */
        public function create_generate_url_metaboxes()
        {
            $pro_generate_login_settings = apply_filters('wp_swiss_toolkit_generate_login_url_premium_settings', []);
            if (!$pro_generate_login_settings) {
                $pro_generate_login_settings = array(
                    array(
                        'id' => 'general_url_option_id',
                        'type' => 'raw',
                        'content' => sprintf('To unlock Generate URL condition, <a href="%s">Upgrade To Pro!</a>', esc_url('https://boomdevs.com/product-category/wordpress/wordpress-plugins/')),
                    ),
                    array(
                        'id' => 'swiss_expiration_date',
                        'type' => 'select',
                        'title' => esc_html__('Expiration Date', 'swiss-toolkit-for-wp'),
                        'options' => array(
                            '1' => esc_html__('1 Day', 'swiss-toolkit-for-wp'),
                        ),
                        'class' => 'pro_generate_only',
                        'placeholder' => 'Select date',
                        'default' => '1',
                        'select2' => array('select2' => array('allowClear' => False))
                    ),
                    array(
                        'id' => 'swiss_expiration_custom_date',
                        'type' => 'datetime',
                        'title' => esc_html__('Custom Expiration Date', 'swiss-toolkit-for-wp'),
                        'placeholder' => esc_html__('Select Expiration Date', 'swiss-toolkit-for-wp'),
                        'required' => array('swiss_expiration_date', 'equals', 'custom')
                    ),
                    array(
                        'id' => 'swiss_usage_limitation',
                        'type' => 'select',
                        'title' => esc_html__('Usage Limitation', 'swiss-toolkit-for-wp'),
                        'options' => array(
                            '3' => esc_html__('3 times', 'swiss-toolkit-for-wp'),
                        ),
                        'class' => 'pro_generate_only',
                        'placeholder' => 'Select limit',
                        'default' => '3',
                        'select2' => array('select2' => array('allowClear' => False))
                    ),
                    array(
                        'id' => 'swiss_usage_custom_limitation',
                        'type' => 'text',
                        'min' => 1,
                        'title' => esc_html__('Custom Limitation Number', 'swiss-toolkit-for-wp'),
                        'placeholder' => esc_html__('Select Limitation Date', 'swiss-toolkit-for-wp'),
                        'validate' => array( 'numeric' ),
                        'required' => array('swiss_usage_limitation', 'equals', 'custom')
                    ),
                );
            }


            // Preview generated URL
            $this->generate_preview_url();

            // Generate token options
            Redux_Metaboxes::set_box(
                BDSTFW_Swiss_Toolkit_Settings::$prefix,
                array(
                    'id' => 'generate_url_options_meta',
                    'title' => esc_html__('Manage URL', 'swiss-toolkit-for-wp'),
                    'post_types' => array('swiss_generate_url'),
                    'position' => 'normal',
                    'priority' => 'high',
                    'sections' => array(
                        array(
                            'fields' => array(
                                ...$pro_generate_login_settings,
                                array(
                                    'id' => 'button_option_id',
                                    'type' => 'raw',
                                    'content' => '<button id="link_publish" type="button" class="button button-primary button-large">' . esc_html('Save', 'swiss-toolkit-for-wp') . '</button>',
                                ),
                            ),
                        ),
                    ),
                )
            );
        }


        /**
         * Registers the custom 'swiss_generate_url' post type.
         */
        public function register_swiss_generate_url_post_type()
        {
            $labels = array(
                'name' => esc_html__('Generate URL', 'swiss-toolkit-for-wp'),
                'singular_name' => esc_html__('Generate URL', 'swiss-toolkit-for-wp'),
                'menu_name' => esc_html__('Generate URLs', 'swiss-toolkit-for-wp'),
                'name_admin_bar' => esc_html__('Generate URL', 'swiss-toolkit-for-wp'),
                'archives' => esc_html__('Generate URL Archives', 'swiss-toolkit-for-wp'),
                'attributes' => esc_html__('Generate URL Attributes', 'swiss-toolkit-for-wp'),
                'parent_item_colon' => esc_html__('Parent Generate URL:', 'swiss-toolkit-for-wp'),
                'all_items' => esc_html__('All Generate URLs', 'swiss-toolkit-for-wp'),
                'add_new_item' => esc_html__('Add New URL', 'swiss-toolkit-for-wp'),
                'add_new' => esc_html__('Add New', 'swiss-toolkit-for-wp'),
                'new_item' => esc_html__('New Item', 'swiss-toolkit-for-wp'),
                'edit_item' => esc_html__('Edit Generated Login URL', 'swiss-toolkit-for-wp'),
                'update_item' => esc_html__('Update Item', 'swiss-toolkit-for-wp'),
                'view_item' => esc_html__('View Item', 'swiss-toolkit-for-wp'),
                'view_items' => esc_html__('View Items', 'swiss-toolkit-for-wp'),
                'search_items' => esc_html__('Search Item', 'swiss-toolkit-for-wp'),
                'not_found' => esc_html__('Not found', 'swiss-toolkit-for-wp'),
                'not_found_in_trash' => esc_html__('Not found in Trash', 'swiss-toolkit-for-wp'),
                'featured_image' => esc_html__('Featured Image', 'swiss-toolkit-for-wp'),
                'set_featured_image' => esc_html__('Set featured image', 'swiss-toolkit-for-wp'),
                'remove_featured_image' => esc_html__('Remove featured image', 'swiss-toolkit-for-wp'),
                'use_featured_image' => esc_html__('Use as featured image', 'swiss-toolkit-for-wp'),
                'insert_into_item' => esc_html__('Insert into', 'swiss-toolkit-for-wp'),
                'uploaded_to_this_item' => esc_html__('Uploaded to this item', 'swiss-toolkit-for-wp'),
                'items_list' => esc_html__('Items list', 'swiss-toolkit-for-wp'),
                'items_list_navigation' => esc_html__('Items list navigation', 'swiss-toolkit-for-wp'),
                'filter_items_list' => esc_html__('Filter items list', 'swiss-toolkit-for-wp'),
            );

            $args = array(
                'label' => esc_html__('Generate URL', 'swiss-toolkit-for-wp'),
                'description' => esc_html__('Generate URL Description', 'swiss-toolkit-for-wp'),
                'labels' => $labels,
                'supports' => array('title'),
                'hierarchical' => false,
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => false,
                'menu_position' => 5,
                'show_in_admin_bar' => false,
                'show_in_nav_menus' => false,
                'can_export' => true,
                'has_archive' => true,
                'exclude_from_search' => false,
                'publicly_queryable' => true,
                'capability_type' => 'page',
            );

            register_post_type('swiss_generate_url', $args);
        }

        /**
         * Adds admin menu pages for 'swiss_generate_url' post type.
         */
        public function add_generate_url_admin_menu_pages()
        {
            add_menu_page(
                esc_html__('Temp Login', 'swiss-toolkit-for-wp'),
                esc_html__('Temp Login', 'swiss-toolkit-for-wp'),
                'manage_options',
                'edit.php?post_type=swiss_generate_url',
                '',
                BDSTFW_SWISS_TOOLKIT_URL . 'admin/img/toolkit-settings.svg'
            );

            add_submenu_page(
                'edit.php?post_type=swiss_generate_url',
                esc_html__('Add New URL', 'swiss-toolkit-for-wp'),
                esc_html__('Add New URL', 'swiss-toolkit-for-wp'),
                'manage_options',
                'post-new.php?post_type=swiss_generate_url'
            );
        }

        /**
         * Updates the post title for 'swiss_generate_url' if it's empty.
         */
        public function update_swiss_generate_url_post_title($post_id, $post_title)
        {
            $existing_title = get_the_title(intval($post_id));

            if (empty($existing_title)) {
                // Sanitize the post title before concatenating
                $sanitized_post_title = sanitize_text_field($post_title);

                $post = array(
                    'ID' => intval($post_id),
                    'post_title' => $sanitized_post_title . ' #' . intval($post_id)
                );

                // Update the post title in the database
                wp_update_post($post);
            }
        }

        /**
         * Generates an encrypted token for 'swiss_generate_url'.
         */
        public function generate_encrypted_token($post_id)
        {
            global $current_user;
            $number = Random::number();
            return Translator::encode(intval($post_id), $number, $current_user->roles[0]);
        }

        /**
         * Handles saving 'swiss_generate_url' post data.
         */
        public function save_swiss_generate_url_post($post_id)
        {
            // Generate and save encrypted text
            $encrypted_token = $this->generate_encrypted_token($post_id);
            if (!get_post_meta(intval($post_id), 'bdstfw_encrypted_token', true)) {
                update_post_meta(intval($post_id), 'bdstfw_encrypted_token', $encrypted_token);
            }

            // Update user ID
            update_post_meta(intval($post_id), 'bdstfw_current_login_userId', get_current_user_id());

            // Handle expiration date
            $expiration_date = isset($_POST['swiss-toolkit-for-wp']['swiss_expiration_date']) ? sanitize_text_field($_POST['swiss-toolkit-for-wp']['swiss_expiration_date']) : '';

            if ($expiration_date === 'custom') {
                $custom_expiration_date = isset($_POST['swiss-toolkit-for-wp']['swiss_expiration_custom_date']) ? sanitize_text_field($_POST['swiss-toolkit-for-wp']['swiss_expiration_custom_date']) : '';
                $expiration_time = strtotime($custom_expiration_date);
            } elseif ($expiration_date === 'no_expire') {
                $expiration_time = strtotime('+100 years');
            } else {
                $expiration_time = strtotime('+' . absint($expiration_date) . ' days'); // Use absint to ensure it's a positive integer.
            }

            // Handle expiration limit
            $expiration_limitation = isset($_POST['swiss-toolkit-for-wp']['swiss_usage_limitation']) ? sanitize_text_field($_POST['swiss-toolkit-for-wp']['swiss_usage_limitation']) : '';
            $pro_global_settings = apply_filters('wp_swiss_toolkit_generate_login_url_premium_settings', []);

            if (!$pro_global_settings) {
                $expiration_time = strtotime('+1 days');
                update_post_meta(intval($post_id), 'bdstfw_swiss_expiration_time', $expiration_time);
                update_post_meta(intval($post_id), 'bdstfw_swiss_usage_limitation', 3);
            } else {
                update_post_meta(intval($post_id), 'bdstfw_swiss_expiration_time', $expiration_time);

                if ($expiration_limitation === 'custom') {
                    $custom_expiration_limit = isset($_POST['swiss-toolkit-for-wp']['swiss_usage_custom_limitation']) ? sanitize_text_field($_POST['swiss-toolkit-for-wp']['swiss_usage_custom_limitation']) : '';
                    $expiration_limit = $custom_expiration_limit;
                } elseif ($expiration_limitation === 'unlimited') {
                    $expiration_limit = '1000';
                } else {
                    $expiration_limit = absint($expiration_limitation); // Use absint to ensure it's a positive integer.
                }

                update_post_meta(intval($post_id), 'bdstfw_swiss_usage_limitation', $expiration_limit);
            }

            // Increase count
            $usage_count = get_post_meta(intval($post_id), 'bdstfw_swiss_usage_count', true);
            if ($usage_count === '') {
                // If it doesn't exist, set it to 0
                $usage_count = 0;
                update_post_meta(intval($post_id), 'bdstfw_swiss_usage_count', $usage_count);
            }

            // Update post title if it's empty
            $this->update_swiss_generate_url_post_title(intval($post_id), 'Token');
        }
    }

    // Initialize the BDSTFW_Generate_URL class
    BDSTFW_Swiss_Toolkit_Generate_URL::get_instance();
}