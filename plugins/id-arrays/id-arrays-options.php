<?php

class IDArraysOptions
{
    private $ida79_options;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'ida79_add_plugin_page'));
        add_action('admin_init', array($this, 'ida79_page_init'));
    }

    public function ida79_add_plugin_page()
    {
        $hook = add_options_page(
            'ID Arrays', // Page Title
            'ID Arrays', // Menu Title
            'manage_options', // Capability Required
            'id-arrays', // Menu Slug
            array($this, 'ida79_create_admin_page') // Function Name
        );
        add_action("load-$hook", 'ida79_screen_options');
    }

    public function ida79_page_init()
    {
        register_setting(
            'ida79_option_group', // Option Group
            'ida79_options_val', // Option Name
            array($this, 'ida79_sanitize') // Sanitize Output
        );

        add_settings_section(
            'ida79_setting_section', // ID
            '', // Title
            '', // Callback
            'id-arrays-admin' // Page
        );

        add_settings_field(
            'ida79_delimiter', // ID
            'Set delimiter for array lists', // Title
            array($this, 'ida79_delimiter_callback'), // Callback
            'id-arrays-admin', // Page
            'ida79_setting_section' // Section
        );

        add_settings_field(
            'ida79_WPListTable', // ID
            'Remove the copy selected IDs function', // Title
            array($this, 'ida79_WPListTable_callback'), // Callback
            'id-arrays-admin', // Page
            'ida79_setting_section' // Section
        );

        add_settings_field(
            'ida79_textbox', // ID
            'Remove textbox of selected IDs function', // Title
            array($this, 'ida79_textbox_callback'), // Callback
            'id-arrays-admin', // Page
            'ida79_setting_section' // Section
        );

        add_settings_field(
            'ida79_hide_col', // ID
            'Remove ID columns', // Title
            array($this, 'ida79_hide_col_callback'), // Callback
            'id-arrays-admin', // Page
            'ida79_setting_section' // Section
        );

        add_settings_field(
            'ida79_first_col', // ID
            'Position ID column first', // Title
            array($this, 'ida79_first_col_callback'), // Callback
            'id-arrays-admin', // Page
            'ida79_setting_section' // Section
        );
    }

    public function ida79_create_admin_page()
    {
        // Define the default options
        $defaults = array(
            'ida79_delimiter' => ',&nbsp;',
            'ida79_WPListTable' => 'default',
            'ida79_textbox' => 'default',
            'ida79_hide_col' => 'default',
            'ida79_first_col' => 'default'
        );

        // If option does not exist add it with default values
        if (!get_option('ida79_options_val')) {
            add_option('ida79_options_val', $defaults);
            $this->ida79_options = get_option('ida79_options_val');
        } else { // Option exists
            $current_op = get_option('ida79_options_val');
            $this->ida79_options = wp_parse_args($current_op, $this->ida79_options);
            update_option('ida79_options_val', $this->ida79_options);
        } ?>

        <div class="wrap">
            <h2>ID Arrays</h2>
            <?php
            $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'tax_table_tab';
            ?>
            <h2 class="nav-tab-wrapper"><a href="?page=id-arrays&tab=tax_table_tab"
                                           class="ida79_tab nav-tab <?php echo $active_tab == 'tax_table_tab' ? 'nav-tab-active' : ''; ?>">Taxonomies</a>
                <a href="?page=id-arrays&tab=post_type_table_tab"
                   class="ida79_tab nav-tab <?php echo $active_tab == 'post_type_table_tab' ? 'nav-tab-active' : ''; ?>">Post
                    Types</a> <a href="?page=id-arrays&tab=template_table_tab"
                                 class="ida79_tab nav-tab <?php echo $active_tab == 'template_table_tab' ? 'nav-tab-active' : ''; ?>">Templates</a>
                <a href="?page=id-arrays&tab=settings_tab"
                   class="ida79_tab nav-tab <?php echo $active_tab == 'settings_tab' ? 'nav-tab-active' : ''; ?>">Settings</a>
                <a href="?page=id-arrays&tab=readme_tab"
                   class="ida79_tab nav-tab <?php echo $active_tab == 'readme_tab' ? 'nav-tab-active' : ''; ?>">Read
                    Me</a></h2>
            <?php
            if ($active_tab == 'tax_table_tab') {
                // Calls the class for displaying the taxonomies list
                $ida79TaxListTable = new ida79_Tax_List_Table(); ?>
                <form class="ida79_form" method="post" action="">
                    <?php
                    $ida79TaxListTable->prepare_items();
                    $ida79TaxListTable->display();
                    submit_button('Submit');
                    $render = $ida79TaxListTable->process_tax_list_data();
                    if (!empty($render)) { ?>
                        <span>Displaying IDs of posts and/or pages for <b><?php echo $render[0]; ?></b></span> <br/>
                        <textarea style="margin-top:5px;" rows="4" cols="50"><?php echo $render[1]; ?></textarea> <br/>
                        <span>Number of IDs found: <b><?php echo $render[2]; ?></b></span>
                    <?php } ?>
                </form>
                <?php
            } else if ($active_tab == 'post_type_table_tab') {
                // Calls the class for displaying the templates list
                $ida79PostTypeListTable = new ida79_Post_Type_List_Table(); ?>
                <form class="ida79_form" method="post" action="">
                    <?php
                    $ida79PostTypeListTable->prepare_items();
                    $ida79PostTypeListTable->display();
                    submit_button('Submit');
                    $render = $ida79PostTypeListTable->process_post_type_list_data();
                    if (!empty($render)) { ?>
                        <span>Displaying IDs for <b><?php echo $render[0]; ?></b></span> <br/>
                        <textarea style="margin-top:5px;" rows="4" cols="50"><?php echo $render[1]; ?></textarea> <br/>
                        <span>Number of IDs found: <b><?php echo $render[2]; ?></b></span>
                    <?php } ?>
                </form>
                <?php
            } else if ($active_tab == 'template_table_tab') {
                // Calls the class for displaying the templates list
                $ida79TemplateListTable = new ida79_Template_List_Table(); ?>
                <form class="ida79_form" method="post" action="">
                    <?php
                    $ida79TemplateListTable->prepare_items();
                    $ida79TemplateListTable->display();
                    submit_button('Submit');
                    $render = $ida79TemplateListTable->process_template_list_data();
                    if (!empty($render)) { ?>
                        <span>Displaying IDs of pages for <b><?php echo $render[0]; ?></b></span> <br/>
                        <textarea style="margin-top:5px;" rows="4" cols="50"><?php echo $render[1]; ?></textarea> <br/>
                        <span>Number of IDs found: <b><?php echo $render[2]; ?></b></span>
                    <?php } ?>
                </form>
                <?php
            } else if ($active_tab == 'settings_tab') { ?>
                <form class="ida79_form" method="post" action="options.php">
                    <?php
                    settings_fields('ida79_option_group');
                    do_settings_sections('id-arrays-admin');
                    submit_button(); ?>
                </form>
                <?php
            } else if ($active_tab == 'readme_tab') { ?>
                <p style="max-width:1280px;"><b>This plugin has two functionalities, as described below.</b><br/>
                    <br/>
                    1. It adds a column showing IDs in the posts/pages, media, taxonomy and users screens. This applies
                    for all types of posts and categories, built-in and custom. You can also select the checkboxes of
                    the posts/pages or taxonomies/tags for which you want to get the IDs in the built-in post or
                    taxonomy edit lists.
                    Then just click on the "Copy Selected IDs" button located next to the bulk actions and you have your
                    delimited
                    list of IDs copied to the clipboard. They also appear in the textbox in case you want to copy them
                    manually.
                    An example for posts is shown below. <br/>
                    <br/>
                    <img style="width:100%;"
                         src="<?php echo plugins_url('id-arrays/includes/example-1.png', dirname(__FILE__)) ?>"/><br/>
                    <br/>
                    2.
                    It returns delimited lists of IDs for posts/pages by taxonomy or by post type. For pages you also
                    have the option to get
                    IDs by template. You can do that here, using the tabs next to the one you are currently at.. An
                    example for posts/pages belonging
                    to certain taxonomies is shown below.<br/>
                    <br/>
                    <img style="width:100%;"
                         src="<?php echo plugins_url('id-arrays/includes/example-2.png', dirname(__FILE__)) ?>"/><br/>
                    <br/>
                    The plugin has been tested to work OK with any custom post type or custom taxonomy. It also works OK
                    with Woocommerce and WPML.<br/>
                    <br/>
                    If you find the plugin useful, please give it a high rating!<br/>
                    <br/>
                    If you have any problems with it, major or minor, let me know through its official
                    <a target="_blank" href="https://wordpress.org/support/plugin/id-arrays">support page</a>.<br/>
                    <br/>
                    Cheers,<br/>
                    Harry </p>
                <?php
            }
            ?>
        </div>
    <?php }

    public function ida79_sanitize($input)
    {
        $sanitary_values = array();
        if (isset($input['ida79_delimiter'])) {
            $sanitary_values['ida79_delimiter'] = sanitize_text_field($input['ida79_delimiter']);
        }
        if (isset($input['ida79_WPListTable'])) {
            $sanitary_values['ida79_WPListTable'] = esc_attr($input['ida79_WPListTable']);
        }
        if (isset($input['ida79_textbox'])) {
            $sanitary_values['ida79_textbox'] = esc_attr($input['ida79_textbox']);
        }
        if (isset($input['ida79_hide_col'])) {
            $sanitary_values['ida79_hide_col'] = esc_attr($input['ida79_hide_col']);
        }
        if (isset($input['ida79_first_col'])) {
            $sanitary_values['ida79_first_col'] = esc_attr($input['ida79_first_col']);
        }
        return $sanitary_values;
    }


    public function ida79_delimiter_callback()
    {
        printf('<input style="width:50px" class="regular-text" type="text" name="ida79_options_val[ida79_delimiter]" id="ida79_delimiter" value="%s">
	  <label for="ida79_delimiter">For non-breaking space use the standard &amp;nbsp; entity</label>	
	  ', isset($this->ida79_options['ida79_delimiter']) ? esc_attr($this->ida79_options['ida79_delimiter']) : '');
    }

    public function ida79_WPListTable_callback()
    {
        printf('<input type="checkbox" name="ida79_options_val[ida79_WPListTable]" id="ida79_WPListTable" value="ida79_WPListTable" %s>
	  <label for="ida79_WPListTable">Checking this will disable the copy selected IDs functionality from the standard WP posts and taxonomies list tables</label>	
	  ', (isset($this->ida79_options['ida79_WPListTable']) && $this->ida79_options['ida79_WPListTable'] === 'ida79_WPListTable') ? 'checked' : '');
    }

    public function ida79_textbox_callback()
    {
        printf('<input type="checkbox" name="ida79_options_val[ida79_textbox]" id="ida79_textbox" value="ida79_textbox" %s>
	  <label for="ida79_textbox">Checking this will remove the textbox next to the Copy Selected IDs button in the standard WP posts and taxonomies list tables</label>	
	  ', (isset($this->ida79_options['ida79_textbox']) && $this->ida79_options['ida79_textbox'] === 'ida79_textbox') ? 'checked' : '');
    }

    public function ida79_hide_col_callback()
    {
        printf('<input type="checkbox" name="ida79_options_val[ida79_hide_col]" id="ida79_hide_col" value="ida79_hide_col" %s>
	  <label for="ida79_hide_col">Checking this will remove the ID columns in the standard WP posts, media and taxonomies list tables</label>	
	  ', (isset($this->ida79_options['ida79_hide_col']) && $this->ida79_options['ida79_hide_col'] === 'ida79_hide_col') ? 'checked' : '');
    }

    public function ida79_first_col_callback()
    {
        printf('<input type="checkbox" name="ida79_options_val[ida79_first_col]" id="ida79_first_col" value="ida79_first_col" %s>
	  <label for="ida79_first_col">Checking this will set the ID column as the first column</label>	
	  ', (isset($this->ida79_options['ida79_first_col']) && $this->ida79_options['ida79_first_col'] === 'ida79_first_col') ? 'checked' : '');
    }
}

$ida79 = new IDArraysOptions();

// Adds the sreen options
function ida79_screen_options()
{
    $args = array(
        'label' => 'Items per Page',
        'default' => 10,
        'option' => 'items_per_page'
    );
    add_screen_option('per_page', $args);

}

add_filter('set-screen-option', 'ida79_set_option', 10, 3);

// Returns the screen option values set by the user
function ida79_set_option($status, $option, $value)
{
    if ('items_per_page' == $option) return $value;
    return $status;
}

?>
