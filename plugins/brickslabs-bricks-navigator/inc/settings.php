<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Add "Bricks Navigator" options page as a submenu under Bricks admin page.
add_action( 'admin_menu', function () {
    add_submenu_page(
        'bricks', // The slug name for the parent menu (or the file name of a standard WordPress admin page)
        'Bricks Navigator Settings', // The text to be displayed in the title tags of the page when the menu is selected
        'Bricks Navigator', // The text to be used for the menu
        'manage_options', // The capability required for this menu to be displayed to the user
        'brickslabs-bricks-navigator', // Unique slug name to refer to this menu by
        'brickslabs_bricks_navigator_submenu_page_callback' // The function to be called to output the content for this page
    );
}, 99 ); // 99 = as the last item in the Bricks menu

// Callback to render the submenu page.
function brickslabs_bricks_navigator_submenu_page_callback() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "brickslabs-bricks-navigator"
            settings_fields( 'brickslabs-bricks-navigator' );

            // output setting sections and their fields
            // (sections are registered for "brickslabs-bricks-navigator", each field is registered to a specific section)
            do_settings_sections( 'brickslabs-bricks-navigator' );
            
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}

// Register a new setting, section and field for "brickslabs-bricks-navigator" page.
add_action( 'admin_init', function () {
    // register new settings for "brickslabs-bricks-navigator" page
    register_setting(
        'brickslabs-bricks-navigator', // A settings group name. Should correspond to an allowed option key name
        'brickslabs_bricks_navigator_show_in_editor' // The name of an option to sanitize and save
    );
    register_setting(
        'brickslabs-bricks-navigator', // A settings group name. Should correspond to an allowed option key name
        'brickslabs_bricks_navigator_hide_community_menu' // The name of an option to sanitize and save
    );
    register_setting(
        'brickslabs-bricks-navigator', // A settings group name. Should correspond to an allowed option key name
        'brickslabs_bricks_navigator_hide_bricks_internal' // The name of an option to sanitize and save
    );
    register_setting(
        'brickslabs-bricks-navigator', // A settings group name. Should correspond to an allowed option key name
        'brickslabs_bricks_navigator_hide_bricks_external' // The name of an option to sanitize and save
    );
    register_setting(
        'brickslabs-bricks-navigator', // A settings group name. Should correspond to an allowed option key name
        'brickslabs_bricks_navigator_hide_thirdparty_plugins' // The name of an option to sanitize and save
    );

    // register a new section in the "brickslabs-bricks-navigator" page
    add_settings_section(
        'brickslabs_bricks_navigator_settings_section', // An identifier for the section
        '', // The title of the section
        'brickslabs_bricks_navigator_settings_section_callback', // A callback function that echoes out any content at the top of the section
        'brickslabs-bricks-navigator' // The menu page on which to display this section. Should match $menu_slug from add_submenu_page() above
    );    

    // register new fields in the "brickslabs_bricks_navigator_settings_section" section, inside the "brickslabs-bricks-navigator" page
    add_settings_field(
        'brickslabs_bricks_navigator_settings_show_in_editor', // An identifier for the field
        'Show admin bar in Bricks editor', // Title
        'brickslabs_bricks_navigator_settings_show_in_editor_callback', // A callback function that echoes the field HTML
        'brickslabs-bricks-navigator', // The menu page on which to display this field. Should match $menu_slug from add_submenu_page() above
        'brickslabs_bricks_navigator_settings_section' // The section on which to show this field. Should match $id from add_settings_section() above
    );
    
    add_settings_field(
        'brickslabs_bricks_navigator_settings_hide_community_menu',
        'Hide Community in the admin bar menu',
        'brickslabs_bricks_navigator_settings_hide_community_menu_callback',
        'brickslabs-bricks-navigator',
        'brickslabs_bricks_navigator_settings_section'
    );
    
    add_settings_field(
        'brickslabs_bricks_navigator_settings_hide_bricks_internal',
        'Hide internal Bricks links in the admin bar menu',
        'brickslabs_bricks_navigator_settings_hide_bricks_internal_callback',
        'brickslabs-bricks-navigator',
        'brickslabs_bricks_navigator_settings_section'
    );
    
    add_settings_field(
        'brickslabs_bricks_navigator_settings_hide_bricks_external',
        'Hide external Bricks links in the admin bar menu',
        'brickslabs_bricks_navigator_settings_hide_bricks_external_callback',
        'brickslabs-bricks-navigator',
        'brickslabs_bricks_navigator_settings_section'
    );
    
    add_settings_field(
        'brickslabs_bricks_navigator_settings_hide_thirdparty_plugins',
        'Hide Plugin Settings in the admin bar menu',
        'brickslabs_bricks_navigator_settings_hide_thirdparty_plugins_callback',
        'brickslabs-bricks-navigator',
        'brickslabs_bricks_navigator_settings_section'
    );
} );

/**
 * callback functions
 */

// section content cb
function brickslabs_bricks_navigator_settings_section_callback() {
	// echo '<p>Options for Bricks Navigator plugin</p>';
}

// field content cb
function brickslabs_bricks_navigator_settings_show_in_editor_callback() {
	// get the value of the setting we've registered with register_setting()
	$show_in_editor = get_option( 'brickslabs_bricks_navigator_show_in_editor' );
	// output the field
	?>
	<input type="checkbox" name="brickslabs_bricks_navigator_show_in_editor" value="1" <?php checked( 1, $show_in_editor ); ?> />
    <?php
}

function brickslabs_bricks_navigator_settings_hide_community_menu_callback() {
	// get the value of the setting we've registered with register_setting()
	$hide_community_menu = get_option( 'brickslabs_bricks_navigator_hide_community_menu' );
	// output the field
	?>
    <input type="checkbox" name="brickslabs_bricks_navigator_hide_community_menu" value="1" <?php checked( 1, $hide_community_menu ); ?> />
    <?php
}

function brickslabs_bricks_navigator_settings_hide_bricks_internal_callback() {
	// get the value of the setting we've registered with register_setting()
	$hide_bricks_internal = get_option( 'brickslabs_bricks_navigator_hide_bricks_internal' );
	// output the field
	?>
    <input type="checkbox" name="brickslabs_bricks_navigator_hide_bricks_internal" value="1" <?php checked( 1, $hide_bricks_internal ); ?> /><span style="margin-left: 0.5em;">Hide these: Getting Started, Custom Fonts, Sidebars, System Information, License</span>
    <?php
}

function brickslabs_bricks_navigator_settings_hide_bricks_external_callback() {
	// get the value of the setting we've registered with register_setting()
	$hide_bricks_external = get_option( 'brickslabs_bricks_navigator_hide_bricks_external' );
	// output the field
	?>
    <input type="checkbox" name="brickslabs_bricks_navigator_hide_bricks_external" value="1" <?php checked( 1, $hide_bricks_external ); ?> /><span style="margin-left: 0.5em;">Hide these: Idea Board, Roadmap, Changelog, Academy, Forum, Facebook Group, YouTube Channel</span>
    <?php
}

function brickslabs_bricks_navigator_settings_hide_thirdparty_plugins_callback() {
	// get the value of the setting we've registered with register_setting()
	$hide_thirdparty_plugins = get_option( 'brickslabs_bricks_navigator_hide_thirdparty_plugins' );
	// output the field
	?>
    <input type="checkbox" name="brickslabs_bricks_navigator_hide_thirdparty_plugins" value="1" <?php checked( 1, $hide_thirdparty_plugins ); ?> />
    <?php
}