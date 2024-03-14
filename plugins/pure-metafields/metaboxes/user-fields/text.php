<?php
/**
 * Text field
 */
if( !defined('ABSPATH') ) exit;

$user_value = empty(get_user_meta($user_id, $id, true))? $default : get_user_meta($user_id, $id, true);

?>
<tr>
    <th><?php echo esc_html($label); ?></th>
    <td>
        <input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($user_value); ?>" class="regular-text" placeholder="<?php echo esc_attr($placeholder); ?>"/>
    </td>
</tr>