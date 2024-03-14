<?php
/**
 * Plugin Name:		   Add custom page template
 * Plugin URI:		   http://clariontechnologies.co.in
 * Description:		   Add template files through admin screen, And view listing for all custom template files in theme with linked pages.
 * Version: 		   2.0.1
 * Author: 		       Kiran Patil
 * Author URI: https://www.clariontech.com/
 * Text Domain:     add-custom-page-template
 */
// Add admin Top menu 
function acpt_admin_menu() {
    add_menu_page('Theme Templates', 'Theme Templates', 'manage_options', 'add-custom-template', 'acpt_custom_pages_list');
}

add_action('admin_menu', 'acpt_admin_menu');

// Add admin Sub menu  
function acpt_add_submenu_page() {
    add_submenu_page(
            'add-custom-template', 'Add Template', 'Add Template', 'manage_options', 'addnew_template', 'acpt_add_options_function'
    );
}

add_action('admin_menu', 'acpt_add_submenu_page');

// Top Menu Page options
function acpt_custom_pages_list() {
    $templates = wp_get_theme()->get_page_templates(); ?>
    <div class="wrap">
        <div class="table-responsive">
            <?php if (isset($_REQUEST['settings-updated']) && ($_REQUEST['settings-updated'] == 'true')) : ?>
                <div class="updated fade"><p><strong><?php _e('Your Template file has been created.'); ?></strong></p></div>
            <?php endif; ?>
            
            <h1 class="wp-heading-inline">List of custom template files in a theme. </h1>
            <a href="<?php echo admin_url( 'admin.php?page=addnew_template' ); ?>" class="page-title-action">Add new Template</a>
            
            <table class="wp-list-table widefat fixed striped table-view-list pages">
                <thead>
                    <tr>
                        <th class="manage-column column-author" id="author" scope="col"><?php echo esc_html('Template Name'); ?></th>
                        <th class="manage-column column-author" id="author" scope="col"><?php echo esc_html('Template File name'); ?></th>
                        <th class="manage-column column-author" id="author" scope="col"><?php echo esc_html('Linked Page'); ?></th>
                    </tr>
                </thead>
                <tbody id="the-list">
                    <?php
                    foreach ($templates as $template_name => $template_filename) {
                        $pages = get_pages(array(
                            'meta_key' => '_wp_page_template',
                            'meta_value' => $template_name
                        ));
                        ?>
                        <tr class="template-tr">
                            <td>
                                <?php echo esc_html($template_filename); ?>
                            </td>
                            <td>
                                <?php echo esc_html($template_name); ?>
                            </td>
                            <td>
                                <?php
                                $i = 0 ;
                                foreach ($pages as $page) {
                                    
                                    if($i > 0){
                                        echo ", ";
                                    }
                                    
                                    if ($page->post_title != '') {
                                        echo esc_html($page->post_title);
                                        
                                    } else {
                                        echo "-";
                                    }
                                    
                                $i++;
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
}

// register fields for sub menu page 
function acpt_add_register_settings() {

    register_setting('acpt_add_settings_group', 'template_name', 'acpt_validate_setting');
}

add_action('admin_init', 'acpt_add_register_settings');

// Validate user input 
function acpt_validate_setting($plugin_options) {
	
    if (isset($_POST['template_name']) && $_POST['template_name'] != '' ) {
        $text = sanitize_text_field($_POST['template_name']);
        $text = (strlen($text) > 30) ? substr($text, 0, 30) : $text;
        // Conversion method.
        $cleantext = strtolower(trim(preg_replace('#\W+#', '_', $text), '_'));
        $current_theme = wp_get_theme();
        $theme_path = get_theme_root() . "/" . $current_theme->stylesheet;
        $template_file = $theme_path . "/" . $cleantext . ".php";
        // Content for template file Start
        $content = "<?php
		/**
		* Template name: $text
		 */";
        // Content for template file End
       
       if (file_exists($template_file)) {
            wp_die('Template is already Exist');
        } else {
            $filehandle = fopen($template_file, "wb")or die('Cannot open file:  ' . $template_file);
            fwrite($filehandle, $content);
            fclose($filehandle);
			 exit(wp_redirect(admin_url('admin.php?page=add-custom-template&settings-updated=true')));
        }
    } else {
        wp_die('No Data is Added');
    }
    return $plugin_options;
}

// render Add template page view 
function acpt_add_options_function() {

    ?>
    <div class="wrap">
        <?php if (isset($_REQUEST['settings-updated']) && ($_REQUEST['settings-updated'] == 'true')) : ?>
            <div class="updated fade"><p><strong><?php _e('Your Template file has been created.'); ?></strong></p></div>
        <?php endif; ?>
        <h2><?php echo esc_html('Add Custom Template - Add New'); ?></h2>
        <form method="post" name="template_form" id="template_form" action="options.php">
            <?php settings_fields('acpt_add_settings_group'); ?>
            <?php do_settings_sections('acpt_add_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo esc_html('Enter Template Name:'); ?></th>
                    <td><input required type="text" name="template_name" class="" value="" maxlength="30"/><?php echo esc_html('  - Max 30 characters.'); ?></td>
                </tr>	

            </table>

            <?php submit_button(); ?>

        </form>

    </div> <?php
}

    /* Plugin Acivation Hook
        * 
        */

    function hook_activate() {

        if (!current_user_can('activate_plugins'))
            return;
        $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
        check_admin_referer("activate-plugin_{$plugin}");
    }

    /* Plugin Deactivation Hook
        * 
        */

    function hook_deactivate() {

        if (!current_user_can('activate_plugins'))
            return;
        $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
        check_admin_referer("deactivate-plugin_{$plugin}");
    }
     
    
    function add_custom_template_load_plugin_textdomain() {
        load_plugin_textdomain( 'add-custom-page-template', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
    }
    add_action( 'plugins_loaded', 'add_custom_template_load_plugin_textdomain' );
