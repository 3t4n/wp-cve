<?php
// check user capabilities
if ( ! current_user_can( 'manage_options' ) ) {
    return;
}

$text_domain = $this->plugin_config->get_text_domain();
$option_group = $this->prefix;
$settings_page = $this->prefix . '_settings';
$settings_section = $this->prefix . '_settings_section';

// add error/update messages
// check if the user have submitted the settings
// WordPress will add the "settings-updated" $_GET parameter to the url
if ( isset( $_GET['settings-updated'] ) ) {
    // add settings saved message with the class of "updated"
    add_settings_error( 
        $this->prefix . '_messages', 
        $this->prefix . '_messages', 
        __( 'Settings Saved', $text_domain ),
        'updated'
    );
}

// show error/update messages
settings_errors( $this->prefix . '_messages' );

?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <div class="card">
        <form action="options.php" method="POST">
            <?php 
                settings_fields( $option_group ); 
                do_settings_sections( $settings_page );
                submit_button('Save Settings');
            ?>
        </form>
    </div>
</div>