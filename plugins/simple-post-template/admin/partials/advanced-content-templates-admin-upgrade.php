<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://objectiv.co
 * @since      1.0.0
 *
 * @package    Advanced_Content_Templates
 * @subpackage Advanced_Content_Templates/admin/partials
 */

$post_type_objects = $this->plugin->get_post_types();
$templates = $this->get_templates();
$act_post_type_settings = $this->plugin->get_setting('act_post_type_settings');
?>
<div class="wrap">
    <?php global $wp_tabbed_navigation; ?>
    <?php $wp_tabbed_navigation->display_tabs(); ?>

    <div class="" style="padding: 20px; border-radius: 4px; background-color:#ffe01a; margin-top: 20px; max-width: 500px;">
        <h2 style="font-size: 22px; margin-top: 0px;">You're missing out!</h2>
        <p style="font-size: 18px;">Advanced Content Templates has tons of bonus features, and it's growing all the time. Join over 600 other pro users and receive:</p>
        <ol style="font-size: 18px;">
            <li>Premium Support</li>
            <li>Featured Images</li>
            <li>Categories, Tags, and Custom Taxonomies</li>
            <li>Custom Fields</li>
            <li>Custom Post Type Support</li>
            <li>And much more!</li>
        </ol>
    
        <p style="font-size: 18px; font-style: italic;">P.S. You can save 25% on your pro upgrade with code <b>LITE25</b></p>

        <a class="button-secondary" style="font-size: 18px;" target="_blank" href="https://www.advancedcontenttemplates.com/?utm_campaign=free&utm_source=wprepo">Check Out Advanced Content Templates!</a>
    </div>
</div>
