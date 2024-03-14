<?php

if (!defined('ABSPATH')) {
	exit;
}

add_action('gform_field_advanced_settings', function($placement, $form_id) {
    if (-1 === $placement) {
        ?>
        <li class="gfcemAllowed_field_setting field_setting">
            <input type="checkbox" id="field_gfcemAllowed" onclick="SetFieldProperty('gfcemAllowed', this.checked); GFCEMToggleInputs()" onkeypress="SetFieldProperty('gfcemAllowed', this.checked); GFCEMToggleInputs()"/>
            <label for="field_gfcemAllowed" class="inline"><?php esc_html_e( 'Allow custom error messages', 'custom-error-messages-for-gravityforms' ) ?></label>
            <br/>
            <div id="field_gfcem_container" style="display:none; padding-top:10px;">
            </div>
        </li>
        <?php
    }
}, 10, 2);
