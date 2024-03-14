<?php
/**
 * Select field
 */
if( !defined('ABSPATH') ) exit;

$user_value = empty(get_user_meta($user_id, $id, true))? $default : get_user_meta($user_id, $id, true);
$placeholder = empty($placeholder)? 'Select an option' : $placeholder;
?>
<tr>
    <th><?php echo esc_html($label); ?></th>
    <td>
        <select name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>">
            <option value="-1"><?php echo esc_attr($placeholder); ?></option>
            <?php 
            if(!empty($options)):
            foreach($options as $key => $val): ?>
                <option value="<?php echo esc_attr($key); ?>" <?php selected($key, $user_value); ?>><?php echo esc_html($val); ?></option>
            <?php endforeach; endif; ?>
        </select>
    </td>
</tr>