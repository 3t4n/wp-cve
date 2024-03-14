<?php

/**
 * This php file render HTML header for addons dashboard page
 */
    if( !isset( $this->main_menu_slug ) ):
        return;
    endif;

    $cool_plugins_docs = "https://docs.coolplugins.net/";
    $cool_plugins_more_info =CCEW_DEMO_URL;
?>

<div id="cool-plugins-container" class="<?php echo esc_attr($this->main_menu_slug) ; ?>">
    <div class="cool-header">
        <h2 style=""><?php echo esc_html($this->dashboar_page_heading); ?></h2>
    <a href="<?php echo esc_url($cool_plugins_docs) ?>" target="_docs" class="button">Docs</a>
    <a href="<?php echo esc_url($cool_plugins_more_info) ?>" target="_info" class="button">Demos</a>
</div>