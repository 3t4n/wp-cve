<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$fieldset_types = ['alt', 'title'];

$settings = woo_image_seo_get_settings();

?>

<form id="woo_image_seo_form">
    <input
        type="hidden"
        name="action"
        value="woo_image_seo_save_settings"
    >

    <div class="wrap">
        <?php
        foreach ( $fieldset_types as $type ) {
            require WOO_IMAGE_SEO['root_dir'] . 'views/partials/fieldset.php';
        }
        ?>
    </div>

    <?php require_once WOO_IMAGE_SEO['views_dir'] . 'partials/actions.php' ?>

    <?php wp_nonce_field( 'woo_image_seo_save_settings' ) ?>
</form>
