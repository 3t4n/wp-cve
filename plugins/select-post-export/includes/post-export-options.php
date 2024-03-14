<?php
/* options page post-export-options */

namespace SPEX_post_export\options;

require_once(SPEX_EXPORT_DIR . '/includes/select-post-export-class.php');
function post_export_options_page()

{
    add_menu_page(
        __('Select Options Page', 'select_post_export'),
        'Select Post Export',
        'manage_options',
        'post-export-options',
        'SPEX_post_export\\options\\post_export_options',
        '',
        6
    );
    add_action('admin_init', 'SPEX_post_export\\options\\register_post_export_options_settings');
}

function register_post_export_options_settings()
{

    register_setting('post-export-options-group', 'select_post_options');
}
add_action('admin_menu', 'SPEX_post_export\\options\\post_export_options_page');

function post_export_options()
{
?>
    <div class="wrap">
        <h1>Select Post Export Options</h1>


        <form method="post" action="options.php">
            <?php settings_fields('post-export-options-group'); ?>
            <?php do_settings_sections('post-export-options-group'); ?>


            <?php $options = get_option('select_post_options'); ?>


            <input type="checkbox" id="select_post_options[post]" name="select_post_options[post]" value="1" <?php checked('1', isset($options['post']) ? $options['post'] : 0); ?>>
            <label for="select_post_options[post]"> Posts</label><br>

            <input type="checkbox" id="select_post_options[page]" name="select_post_options[page]" value="1" <?php checked('1', isset($options['page']) ? $options['page'] : 0); ?>>
            <label for="page"> Pages</label><br>

            <input type="checkbox" id="select_post_options[media]" name="select_post_options[media]" value="1" <?php checked('1', isset($options['media']) ? $options['media'] : 0); ?>>
            <label for="media">Media</label><br>



            <?php

            $args = array(

                '_builtin' => false
            );



            $x = get_post_types($args, 'objects');

            foreach ($x as $y) {
                if ($y->show_in_menu) {
            ?>
                    <input type="checkbox" id="select_post_options[<?php echo esc_attr($y->name) ?>]" name="select_post_options[<?php echo esc_attr($y->name)?>]" value="1" <?php checked('1', isset($options[$y->name]) ? $options[$y->name] : 0); ?>>
                    <label for="select_post_options[<?php echo esc_attr($y->name) ?>]"> <?php echo esc_attr($y->label) ?></label><br>
            <?php

                }
            }

            ?>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}
