<?php
if ( ! defined('WPINC')) {
    die;
}
?>
<h2><?php _e('Additional information','woo-customers-manager'); ?></h2>

<table class="form-table">
    <tbody>
        <tr>
            <th><label><?php _e('Registered','woo-customers-manager'); ?></label></th>
            <td>
                <?php echo $user->data->user_registered; ?>
            </td>
        </tr>
    </tbody>
</table>
