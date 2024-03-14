<table class="form-table berocket_compare_products_styler">
    <thead>
        <tr><th colspan="6" style="text-align: center; font-size: 2em;"><?php _e('Compare Button on Widgets', 'products-compare-for-woocommerce') ?></th></tr>
        <tr>
            <th><?php _e('Border color', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Border width', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Border radius', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Size', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Font color', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Background', 'products-compare-for-woocommerce') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][button][bcolor]', 
                    br_get_value_from_array($options, array('button', 'bcolor')), 
                    (empty($defaults['button']['bcolor']) ? -1 : $defaults['button']['bcolor'])
                ); ?>
            </td>
            <td>
                <input data-default="<?php echo $defaults['button']['bwidth']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][button][bwidth]" value="<?php echo @ $options['button']['bwidth'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['button']['bradius']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][button][bradius]" value="<?php echo @ $options['button']['bradius'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['button']['fontsize']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][button][fontsize]" value="<?php echo @ $options['button']['fontsize'] ?>" />
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][button][fcolor]', 
                    br_get_value_from_array($options, array('button', 'fcolor')), 
                    (empty($defaults['button']['fcolor']) ? -1 : $defaults['button']['fcolor'])
                ); ?>
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][button][backcolor]', 
                    br_get_value_from_array($options, array('button', 'backcolor')), 
                    (empty($defaults['button']['backcolor']) ? -1 : $defaults['button']['backcolor'])
                ); ?>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th class="manage-column admin-column-theme" colspan="6" scope="col">
                <input class="all_theme_default button" type="button" value="Set all to theme default">
                <div style="clear:both;"></div>
            </th>
        </tr>
    </tfoot>
</table>
<table class="form-table berocket_compare_products_styler">
    <thead>
        <tr><th colspan="6" style="text-align: center; font-size: 2em;"><?php _e('Toolbar Button', 'products-compare-for-woocommerce') ?></th></tr>
        <tr>
            <th><?php _e('Border color', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Border width', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Border radius', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Size', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Font color', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Background', 'products-compare-for-woocommerce') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][toolbutton][bcolor]', 
                    br_get_value_from_array($options, array('toolbutton', 'bcolor')), 
                    (empty($defaults['toolbutton']['bcolor']) ? -1 : $defaults['toolbutton']['bcolor'])
                ); ?>
            </td>
            <td>
                <input data-default="<?php echo $defaults['toolbutton']['bwidth']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][toolbutton][bwidth]" value="<?php echo @ $options['toolbutton']['bwidth'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['toolbutton']['bradius']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][toolbutton][bradius]" value="<?php echo @ $options['toolbutton']['bradius'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['toolbutton']['fontsize']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][toolbutton][fontsize]" value="<?php echo @ $options['toolbutton']['fontsize'] ?>" />
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][toolbutton][fcolor]', 
                    br_get_value_from_array($options, array('toolbutton', 'fcolor')), 
                    (empty($defaults['toolbutton']['fcolor']) ? -1 : $defaults['toolbutton']['fcolor'])
                ); ?>
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][toolbutton][backcolor]', 
                    br_get_value_from_array($options, array('toolbutton', 'backcolor')), 
                    (empty($defaults['toolbutton']['backcolor']) ? -1 : $defaults['toolbutton']['backcolor'])
                ); ?>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th class="manage-column admin-column-theme" colspan="6" scope="col">
                <input class="all_theme_default button" type="button" value="Set all to theme default">
                <div style="clear:both;"></div>
            </th>
        </tr>
    </tfoot>
</table>
<table class="form-table berocket_compare_products_styler">
    <thead>
        <tr><th colspan="5" style="text-align: center; font-size: 2em;"><?php _e('Table', 'products-compare-for-woocommerce') ?></th></tr>
        <tr>
            <th><?php _e('Column minimum width', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Image width', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Padding top', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Background color', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Background color for attributes with same value', 'products-compare-for-woocommerce') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <input data-default="<?php echo $defaults['table']['colwidth']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][table][colwidth]" value="<?php echo @ $options['table']['colwidth'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['table']['imgwidth']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][table][imgwidth]" value="<?php echo @ $options['table']['imgwidth'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['table']['toppadding']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][table][toppadding]" value="<?php echo @ $options['table']['toppadding'] ?>" />
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][table][backcolor]', 
                    br_get_value_from_array($options, array('table', 'backcolor')), 
                    (empty($defaults['table']['backcolor']) ? -1 : $defaults['table']['backcolor'])
                ); ?>
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][table][backcolorsame]', 
                    br_get_value_from_array($options, array('table', 'backcolorsame')), 
                    (empty($defaults['table']['backcolorsame']) ? -1 : $defaults['table']['backcolorsame'])
                ); ?>
            </td>
        </tr>
    </tbody>
    <thead>
        <tr>
            <th><?php _e('Padding outside', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Padding outside full size', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Same value from attributes', 'products-compare-for-woocommerce') ?></th>
            <th colspan="2"><?php _e('Same value from attributes on mouse over', 'products-compare-for-woocommerce') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <p><?php _e('Top', 'products-compare-for-woocommerce') ?></p>
                <input data-default="<?php echo $defaults['table']['margintop']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][table][margintop]" value="<?php echo @ $options['table']['margintop'] ?>" />
                <p><?php _e('Bottom', 'products-compare-for-woocommerce') ?></p>
                <input data-default="<?php echo $defaults['table']['marginbottom']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][table][marginbottom]" value="<?php echo @ $options['table']['marginbottom'] ?>" />
                <p><?php _e('Left', 'products-compare-for-woocommerce') ?></p>
                <input data-default="<?php echo $defaults['table']['marginleft']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][table][marginleft]" value="<?php echo @ $options['table']['marginleft'] ?>" />
                <p><?php _e('Right', 'products-compare-for-woocommerce') ?></p>
                <input data-default="<?php echo $defaults['table']['marginright']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][table][marginright]" value="<?php echo @ $options['table']['marginright'] ?>" />
            </td>
            <td>
                <p><?php _e('Top', 'products-compare-for-woocommerce') ?></p>
                <input data-default="<?php echo $defaults['table']['top']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][table][top]" value="<?php echo @ $options['table']['top'] ?>" />
                <p><?php _e('Bottom', 'products-compare-for-woocommerce') ?></p>
                <input data-default="<?php echo $defaults['table']['bottom']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][table][bottom]" value="<?php echo @ $options['table']['bottom'] ?>" />
                <p><?php _e('Left', 'products-compare-for-woocommerce') ?></p>
                <input data-default="<?php echo $defaults['table']['left']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][table][left]" value="<?php echo @ $options['table']['left'] ?>" />
                <p><?php _e('Right', 'products-compare-for-woocommerce') ?></p>
                <input data-default="<?php echo $defaults['table']['right']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][table][right]" value="<?php echo @ $options['table']['right'] ?>" />
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][table][samecolor]', 
                    br_get_value_from_array($options, array('table', 'samecolor')), 
                    (empty($defaults['table']['samecolor']) ? -1 : $defaults['table']['samecolor'])
                ); ?>
            </td>
            <td class="admin-column-color" colspan="2">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][table][samecolorhover]', 
                    br_get_value_from_array($options, array('table', 'samecolorhover')), 
                    (empty($defaults['table']['samecolorhover']) ? -1 : $defaults['table']['samecolorhover'])
                ); ?>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th class="manage-column admin-column-theme" colspan="5" scope="col">
                <input class="all_theme_default button" type="button" value="Set all to theme default">
                <div style="clear:both;"></div>
            </th>
        </tr>
    </tfoot>
</table>

<table class="form-table berocket_compare_products_styler">
    <thead>
        <tr><th colspan="6" style="text-align: center; font-size: 2em;"><?php _e('Hide attributes with same value button', 'products-compare-for-woocommerce') ?></th></tr>
        <tr>
            <th><?php _e('Button type', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Font size', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Padding from top', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Padding from bottom', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Font color', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Background color', 'products-compare-for-woocommerce') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <?php _e('Normal', 'products-compare-for-woocommerce') ?>
            </td>
            <td>
                <input data-default="<?php echo $defaults['dif_button']['fontsize']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][dif_button][fontsize]" value="<?php echo @ $options['dif_button']['fontsize'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['dif_button']['top']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][dif_button][top]" value="<?php echo @ $options['dif_button']['top'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['dif_button']['bottom']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][dif_button][bottom]" value="<?php echo @ $options['dif_button']['bottom'] ?>" />
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][dif_button][color]', 
                    br_get_value_from_array($options, array('dif_button', 'color')), 
                    (empty($defaults['dif_button']['color']) ? -1 : $defaults['dif_button']['color'])
                ); ?>
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][dif_button][backcolor]', 
                    br_get_value_from_array($options, array('dif_button', 'backcolor')), 
                    (empty($defaults['dif_button']['backcolor']) ? -1 : $defaults['dif_button']['backcolor'])
                ); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php _e('On mouse over', 'products-compare-for-woocommerce') ?>
            </td>
            <td>
                <input data-default="<?php echo $defaults['dif_button_hover']['fontsize']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][dif_button_hover][fontsize]" value="<?php echo @ $options['dif_button_hover']['fontsize'] ?>" />
            </td>
            <td></td>
            <td></td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][dif_button_hover][color]', 
                    br_get_value_from_array($options, array('dif_button_hover', 'color')), 
                    (empty($defaults['dif_button_hover']['color']) ? -1 : $defaults['dif_button_hover']['color'])
                ); ?>
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][dif_button_hover][backcolor]', 
                    br_get_value_from_array($options, array('dif_button_hover', 'backcolor')), 
                    (empty($defaults['dif_button_hover']['backcolor']) ? -1 : $defaults['dif_button_hover']['backcolor'])
                ); ?>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th class="manage-column admin-column-theme" colspan="6" scope="col">
                <input class="all_theme_default button" type="button" value="Set all to theme default">
                <div style="clear:both;"></div>
            </th>
        </tr>
    </tfoot>
</table>

<table class="form-table berocket_compare_products_styler">
    <thead>
        <tr><th colspan="6" style="text-align: center; font-size: 2em;"><?php _e('Clear compare list button', 'products-compare-for-woocommerce') ?></th></tr>
        <tr>
            <th><?php _e('Button type', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Font size', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Padding from top', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Padding from bottom', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Font color', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Background color', 'products-compare-for-woocommerce') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <?php _e('Normal', 'products-compare-for-woocommerce') ?>
            </td>
            <td>
                <input data-default="<?php echo $defaults['clear_button']['fontsize']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][clear_button][fontsize]" value="<?php echo @ $options['clear_button']['fontsize'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['clear_button']['top']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][clear_button][top]" value="<?php echo @ $options['clear_button']['top'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['clear_button']['bottom']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][clear_button][bottom]" value="<?php echo @ $options['clear_button']['bottom'] ?>" />
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][clear_button][color]', 
                    br_get_value_from_array($options, array('clear_button', 'color')), 
                    (empty($defaults['clear_button']['color']) ? -1 : $defaults['clear_button']['color'])
                ); ?>
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][clear_button][backcolor]', 
                    br_get_value_from_array($options, array('clear_button', 'backcolor')), 
                    (empty($defaults['clear_button']['backcolor']) ? -1 : $defaults['clear_button']['backcolor'])
                ); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php _e('On mouse over', 'products-compare-for-woocommerce') ?>
            </td>
            <td>
                <input data-default="<?php echo $defaults['clear_button_hover']['fontsize']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][clear_button_hover][fontsize]" value="<?php echo @ $options['clear_button_hover']['fontsize'] ?>" />
            </td>
            <td></td>
            <td></td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][clear_button_hover][color]', 
                    br_get_value_from_array($options, array('clear_button_hover', 'color')), 
                    (empty($defaults['clear_button_hover']['color']) ? -1 : $defaults['clear_button_hover']['color'])
                ); ?>
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][clear_button_hover][backcolor]', 
                    br_get_value_from_array($options, array('clear_button_hover', 'backcolor')), 
                    (empty($defaults['clear_button_hover']['backcolor']) ? -1 : $defaults['clear_button_hover']['backcolor'])
                ); ?>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th class="manage-column admin-column-theme" colspan="6" scope="col">
                <input class="all_theme_default button" type="button" value="Set all to theme default">
                <div style="clear:both;"></div>
            </th>
        </tr>
    </tfoot>
</table>
<table class="form-table berocket_compare_products_styler">
    <thead>
        <tr><th colspan="5" style="text-align: center; font-size: 2em;"><?php _e('"Add to compare" button', 'products-compare-for-woocommerce') ?></th></tr>
        <tr>
            <th><?php _e('Button type', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Font size', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Width', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Font color', 'products-compare-for-woocommerce') ?></th>
            <th><?php _e('Background color', 'products-compare-for-woocommerce') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <?php _e('Normal', 'products-compare-for-woocommerce') ?>
            </td>
            <td>
                <input data-default="<?php echo $defaults['comparebutton']['fontsize']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][comparebutton][fontsize]" value="<?php echo @ $options['comparebutton']['fontsize'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['comparebutton']['width']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][comparebutton][width]" value="<?php echo @ $options['comparebutton']['width'] ?>" />
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][comparebutton][color]', 
                    br_get_value_from_array($options, array('comparebutton', 'color')), 
                    (empty($defaults['comparebutton']['color']) ? -1 : $defaults['comparebutton']['color'])
                ); ?>
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][comparebutton][backcolor]', 
                    br_get_value_from_array($options, array('comparebutton', 'backcolor')), 
                    (empty($defaults['comparebutton']['backcolor']) ? -1 : $defaults['comparebutton']['backcolor'])
                ); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php _e('On mouse over', 'products-compare-for-woocommerce') ?>
            </td>
            <td>
                <input data-default="<?php echo $defaults['comparebuttonhover']['fontsize']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][comparebuttonhover][fontsize]" value="<?php echo @ $options['comparebuttonhover']['fontsize'] ?>" />
            </td>
            <td></td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][comparebuttonhover][color]', 
                    br_get_value_from_array($options, array('comparebuttonhover', 'color')), 
                    (empty($defaults['comparebuttonhover']['color']) ? -1 : $defaults['comparebuttonhover']['color'])
                ); ?>
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][comparebuttonhover][backcolor]', 
                    br_get_value_from_array($options, array('comparebuttonhover', 'backcolor')), 
                    (empty($defaults['comparebuttonhover']['backcolor']) ? -1 : $defaults['comparebuttonhover']['backcolor'])
                ); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php _e('Added', 'products-compare-for-woocommerce') ?>
            </td>
            <td>
                <input data-default="<?php echo $defaults['comparebuttonadded']['fontsize']; ?>" type="text" placeholder="<?php _e('Theme Default', 'products-compare-for-woocommerce') ?>" name="<?php echo $settings_name; ?>[style_settings][comparebuttonadded][fontsize]" value="<?php echo @ $options['comparebuttonadded']['fontsize'] ?>" />
            </td>
            <td></td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][comparebuttonadded][color]', 
                    br_get_value_from_array($options, array('comparebuttonadded', 'color')), 
                    (empty($defaults['comparebuttonadded']['color']) ? -1 : $defaults['comparebuttonadded']['color'])
                ); ?>
            </td>
            <td class="admin-column-color">
                <?php echo br_color_picker(
                    $settings_name . '[style_settings][comparebuttonadded][backcolor]', 
                    br_get_value_from_array($options, array('comparebuttonadded', 'backcolor')), 
                    (empty($defaults['comparebuttonadded']['backcolor']) ? -1 : $defaults['comparebuttonadded']['backcolor'])
                ); ?>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th class="manage-column admin-column-theme" colspan="5" scope="col">
                <input class="all_theme_default button" type="button" value="Set all to theme default">
                <div style="clear:both;"></div>
            </th>
        </tr>
    </tfoot>
</table>
