<?php

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$obj = WTVCP_Visitors::WTVCP_get_settings();

?>

    <form class="tvcp-container" method="POST">
        <input type="hidden" name="tvcp_save" value="true">
        <?= wp_nonce_field( 'tvcp-save-settings' ) ?>

        <div>
            <h2 class="cc-admin-title">The Visitor Counter Plugin</h2>
        </div>

        <div>
            <label for="title">Widget title</label>
            <input type="text" name="title" value="<?= $obj->title ?>" class="cc-input-title">
            <input type="checkbox" <?= ($obj->show_title != 'true') ? 'checked' : 'false'; ?> name="show-title">
            <span>Hidden</span>
        </div>

        <div class="form-group">
            <div class="bg-color" id="back">
                <input type="hidden" name="bg-color" id="bg-color" value="<?= $obj->background; ?>">
                <div class="title">Background color</div>
                <div class="color" id="background-color-preview" style="background-color: <?= $obj->background; ?>;"></div>
            </div>
        </div>

        <div class="form-group">
            <div class="bg-color" id="text">
                <input type="hidden" name="text-color" id="text-color" value="<?= $obj->color; ?>">
                <div class="title">Text color</div>
                <div class="color" id="text-color-preview" style="background-color: <?= $obj->color; ?>;"></div>
            </div>
        </div>

        <div>
            <label for="border-radius">Border Radius</label>
            <input type="number" name="border-radius" value="<?= $obj->border_radius ?>" class="cc-input-title">
        </div>

        <div>
            <label for="width">Width</label>
            <input type="number" min="90" name="width" value="<?= $obj->width ?>" class="cc-input-title">
        </div>

        <div>
            <h2>Shortcode: <span>[visitors]</span></h2>
        </div>

        <input type="submit" class="tvcp-save" value="save">

    </form>

<?php
