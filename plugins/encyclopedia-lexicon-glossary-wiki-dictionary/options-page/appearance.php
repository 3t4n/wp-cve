<?php

use WordPress\Plugin\Encyclopedia\{
    I18n,
    Options
};

?>
<table class="form-table">

    <tr>
        <th><label for="embed_default_style"><?php I18n::_e('Default style') ?></label></th>
        <td>
            <select name="embed_default_style" id="embed_default_style">
                <option value="1" <?php selected(Options::get('embed_default_style')) ?>><?php I18n::_e('On') ?></option>
                <option value="0" <?php selected(!Options::get('embed_default_style')) ?>><?php I18n::_e('Off') ?></option>
            </select>
            <p class="help"><?php I18n::_e('Enables or disables the encyclopedia default CSS on the frontend.') ?></p>
        </td>
    </tr>

</table>