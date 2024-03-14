<?php
include 'parts/help.php';

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.david-manson.com
 * @since      1.0.0
 *
 * @package    Say_It
 * @subpackage Say_It/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <!-- The title -->
    <h1>Say It! <?php _e('Options', $this->plugin_name); ?></h1>
    
    <!-- The tabs -->
    <h2 class="nav-tab-wrapper say-it-tabs">
        <a href="#general" class="nav-tab nav-tab-active">General</a>
        <a href="#google_tts" class="nav-tab">Google TTS</a>
        <a href="#amazon_key" class="nav-tab">Amazon Polly</a>
        <a href="#help" class="nav-tab">Help</a>
    </h2>

    <!-- The main form -->
    <form method="post" name="cleanup_options" action="options.php">

        <?php
            settings_fields($this->plugin_name);
            do_settings_sections($this->plugin_name);
        ?>

        <div id="google_tts" class="say-it-tab">
            <?php $this->display_template_part('google-key'); ?>
        </div>

        <div id="amazon_key" class="say-it-tab">
            <?php $this->display_template_part('amazon-key'); ?>
        </div>

        <div id="general" class="say-it-tab active">
            <div class="card">
                <h2>Global</h2>
                <?php $this->display_template_part('mode'); ?>
            </div>

            <div class="card">
                <h2>HTML5 Voice configuration</h2>
                <?php $this->display_template_part('html5'); ?>
            </div>

            <div class="card">
                <h2>Google Voice configuration</h2>
                <?php $this->display_template_part('google'); ?>
            </div>

            <div class="card">
                <h2>Amazon Voice configuration</h2>
                <?php $this->display_template_part('amazon'); ?>
            </div>

        </div>

        <?php submit_button( __( 'Save all changes', $this->plugin_name ), 'primary','submit', TRUE ); ?>
    </form>
    

    <div id="help" class="say-it-tab">
        <?php print_help($this) ?>
        <?php $this->display_template_part('debug'); ?>
    </div>

</div>