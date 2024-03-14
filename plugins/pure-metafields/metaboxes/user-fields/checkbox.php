<?php
/**
 * Checkbox field
 */
if( !defined('ABSPATH') ) exit;

$user_value = empty(get_user_meta($user_id, $id, true))? $default : get_user_meta($user_id, $id, true);

?>
<tr>
    <th><?php echo esc_html($label); ?></th>
    <td>
        <label for="<?php echo esc_attr($id); ?>">
            <input type="checkbox" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" <?php checked($user_value, 'on'); ?>/>
            <?php echo esc_html($label); ?>
        </label>
    </td>
</tr>