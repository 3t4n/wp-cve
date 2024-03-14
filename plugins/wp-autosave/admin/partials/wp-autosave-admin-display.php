<?php

/**
 * Provide a admin area view for the plugin
 *
 * @link       wpautosave@gmail.com
 * @since      1.0.0
 *
 * @package    Wp_Autosave
 * @subpackage Wp_Autosave/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<!-- Main page of settings -->
<div class="wrap">    
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <h2>Basic:</h2>
    <form method="post" name="wp_autosave_options" action="options.php">

    <?php
        /* Our array of default settings */
        $defaults = array(
            'time_mark' => 1,
            'interval'  => 300,
            'type_save' => 1,
        );
        /* Use settings from db or from defaults */
        $options = get_option( $this->plugin_name );
        $options = wp_parse_args( $options, $defaults );

        /* Grab our settings properly */
        $time_mark = $options['time_mark'];
        $interval  = $options['interval'];
        $type_save = $options['type_save'];
    ?>

    <!-- Settings field -->
    <?php
        settings_fields( $this->plugin_name );
        do_settings_sections( $this->plugin_name );
    ?>

    <!-- Add time of last save to request -->
    <fieldset>
        <legend class="screen-reader-text">
            <span>Add time of last save to request</span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-time_mark">
            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-time_mark" name="<?php echo $this->plugin_name; ?>[time_mark]" value="1" <?php checked( $time_mark, 1 ); ?> />
            <span><?php esc_attr_e( 'Add time of last save to request', $this->plugin_name ); ?></span>
        </label>
    </fieldset>

    <!-- Type of save radio-buttons -->
    <h2>Type of save:</h2>
    <fieldset>
        <legend class="screen-reader-text">
            <span>Type of save</span>
        </legend>
        <input type="radio" id="<?php echo $this->plugin_name; ?>-type_save" name="<?php echo $this->plugin_name; ?>[type_save]" value="0" <?php checked( $type_save, 0 ); ?> />
        <span><?php esc_attr_e( 'Save by changes in editor', $this->plugin_name ); ?></span>
        <br />
        <input type="radio" id="<?php echo $this->plugin_name; ?>-type_save" name="<?php echo $this->plugin_name; ?>[type_save]" value="1" <?php checked( $type_save, 1 ); ?> />
        <span><?php esc_attr_e( 'Save by timer', $this->plugin_name ); ?></span>
    </fieldset>

    <!-- Timer settings -->
    <h2>Save by timer settings:</h2>
    
    <!-- Interval -->
    <fieldset>
        <legend class="screen-reader-text">
            <span>Interval of requests</span>
        </legend>
        <input type="text" style="width: 50px;" maxlength="4" class="regular-text" id="<?php echo $this->plugin_name; ?>-interval" name="<?php echo $this->plugin_name; ?>[interval]" value="<?php echo $interval; ?>"/>
        <span>Interval of requests (in sec.)</span>
    </fieldset>

    <?php submit_button( 'Save all changes', 'primary','submit', TRUE ); ?>

    </form>
</div>