<?php
/**
 * Textarea field
 */
if( !defined('ABSPATH') ) exit;

$user_value = empty(get_user_meta($user_id, $id, true))? $default : get_user_meta($user_id, $id, true);

?>
<tr>
    <th><?php echo esc_html($label); ?></th>
    <td>
        <textarea name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" placeholder="<?php echo esc_attr($placeholder); ?>"><?php echo esc_attr($user_value); ?></textarea>
    </td>
</tr>