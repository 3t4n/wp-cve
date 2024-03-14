<?php

/**
 * Prevent direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

require_once BDSTFW_SWISS_TOOLKIT_PATH . 'includes/class-boomdevs-swiss-toolkit-settings.php';

/**
 * Manages bulk theme deletion functionality for the WP Swiss Toolkit plugin.
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Bulk_Theme_Delete')) {
    class BDSTFW_Swiss_Toolkit_Bulk_Theme_Delete
    {
        /**
         * The single instance of the class.
         */
        protected static $instance;

        /**
         * Returns the single instance of the class.
         *
         * @return BDSTFW_Swiss_Toolkit_Bulk_Theme_Delete Singleton instance.
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
         * Initializes bulk theme deletion if enabled in settings.
         */
        public function __construct()
        {
            $settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();
            if (isset($settings['boomdevs_swiss_bulk_theme_delete'])) {
                if ($settings['boomdevs_swiss_bulk_theme_delete'] === '1') {
                    add_action('admin_menu', array($this, 'add_menu'));
                    add_action('admin_init', array($this, 'bulk_theme_delete'));
                }
            }
        }

        /**
         * Adds a menu item for bulk theme deletion.
         */
        public function add_menu()
        {
            add_theme_page(
                esc_html__("Bulk Theme Delete", 'swiss-toolkit-for-wp'),
                esc_html__("Bulk Theme Delete", 'swiss-toolkit-for-wp'),
                'manage_options',
                'bulk-theme-delete',
                array($this, 'bulk_theme_display')
            );
        }

        /**
         * Displays the bulk theme deletion page.
         */
        public function bulk_theme_display()
        {
            /* Display Theme Table */
            $themes = wp_get_themes();
            $active_theme = wp_get_theme();
            $parents[] = null;

            foreach ($themes as $theme) {

                if ($theme->parent_theme) {
                    $parents[] = $theme->parent_theme;
                }
            }

?>
            <div class="wrap">
                <h2><?php echo esc_html__('Bulk Theme Delete', 'swiss-toolkit-for-wp'); ?></h2>
                <br>
                <div>
                    <form method="post">
                        <table class="wp-list-table widefat fixed posts">
                            <thead>
                                <tr>
                                    <th class="check-column"><?php esc_html_e('&nbsp;', 'swiss-toolkit-for-wp'); ?></th>
                                    <th><?php esc_html_e('Name', 'swiss-toolkit-for-wp'); ?></th>
                                    <th><?php esc_html_e('Author', 'swiss-toolkit-for-wp'); ?></th>
                                    <th><?php esc_html_e('Version', 'swiss-toolkit-for-wp'); ?></th>
                                    <th><?php esc_html_e('Path', 'swiss-toolkit-for-wp'); ?></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="check-column"><?php esc_html_e('&nbsp;', 'swiss-toolkit-for-wp'); ?></th>
                                    <th><?php esc_html_e('Name', 'swiss-toolkit-for-wp'); ?></th>
                                    <th><?php esc_html_e('Author', 'swiss-toolkit-for-wp'); ?></th>
                                    <th><?php esc_html_e('Version', 'swiss-toolkit-for-wp'); ?></th>
                                    <th><?php esc_html_e('Path', 'swiss-toolkit-for-wp'); ?></th>
                                </tr>
                            </tfoot>
                            <tbody class="the-list">
                                <?php

                                foreach ($themes as $theme) : ?>
                                    <tr>

                                        <th scope="row" class="check-column">
                                            <?php if ($active_theme->stylesheet != $theme->stylesheet) { ?>
                                                <input type="checkbox" name="theme[]" value='<?php printf(esc_html__('%s', 'swiss-toolkit-for-wp'), esc_html($theme->stylesheet)); ?>' />
                                            <?php } ?>
                                        </th>
                                        <td><?php printf(
                                                esc_html__('%s', 'swiss-toolkit-for-wp'),
                                                esc_html($theme->Name)
                                            )
                                            ?>
                                            <?php
                                            if ($active_theme->stylesheet == $theme->stylesheet) {
                                                echo wp_kses('<br><span style="color:green;">' . esc_html__('(Current Theme)', 'swiss-toolkit-for-wp') . '</span>', array(
                                                    'span' => array(
                                                        'class' => true,
                                                        'style' => true
                                                    ),
                                                    'br' => array()
                                                ));
                                            }
                                            if (is_array($parents)) {
                                                if (in_array($theme->Name, $parents)) {
                                                    echo wp_kses('<br><span style="color:orange;">' . esc_html__('Has Child Themes', 'swiss-toolkit-for-wp') . '</span>', array(
                                                        'span' => array(
                                                            'class' => true,
                                                            'style' => true
                                                        ),
                                                        'br' => array()
                                                    ));
                                                }
                                            }
                                            ?></td>
                                        <td>
                                            <?php
                                            echo wp_kses($theme->Author, array(
                                                'a' => array(
                                                    'href' => true,
                                                    'title' => true,
                                                    'target' => '_blank',
                                                ),
                                            ));
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            printf(
                                                esc_html__('%s.', 'swiss-toolkit-for-wp'),
                                                esc_html($theme->version)
                                            );
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            printf(
                                                esc_html__('%s.', 'swiss-toolkit-for-wp'),
                                                esc_html($theme->template)
                                            );
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                endforeach; ?>
                            </tbody>
                        </table>
                        <br />
                        <?php wp_nonce_field('bulk_theme_delete', 'themes_delete'); ?>
                        <input type="submit" class="button action" value="Bulk Delete Theme">
                    </form>
                </div>
            </div>
<?php
        }

        /**
         * Handles the bulk theme deletion action.
         */
        public function bulk_theme_delete()
        {
            if (isset($_POST['themes_delete']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['themes_delete'])), 'bulk_theme_delete')) {
                $themes_delete = array_map('sanitize_text_field', $_POST['theme']);

                foreach ($themes_delete as $theme) {
                    delete_theme($theme);
                }
            }
        }
    }

    // Initialize the BDSTFW_Bulk_Theme_Delete class
    BDSTFW_Swiss_Toolkit_Bulk_Theme_Delete::get_instance();
}
