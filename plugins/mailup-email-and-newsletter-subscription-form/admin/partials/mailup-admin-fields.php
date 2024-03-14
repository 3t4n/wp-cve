<?php declare(strict_types=1);

/**
 * Provide a admin area view for the plugin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @see  https://mailup.it
 * @since 1.2.6
 */
?>
<section class="pattern" id="">
    <h2><?php esc_attr_e('Form Fields:', 'mailup'); ?>
    </h2>
    <span
        class="info"><?php _e('Create your form including some custom fields. Including at least email or phone, is mandatory.', 'mailup'); ?></span>
    <table class="form-table custom-fields">
        <tbody>
            <tr>
                <th><?php _e('Field ID on MailUp', 'mailup'); ?>
                </th>
                <th><?php _e('Type', 'mailup'); ?>
                </th>
                <th><?php _e('Displayed Name', 'mailup'); ?>
                </th>
                <th><?php _e('Required', 'mailup'); ?>
                </th>
                <th></th>
            </tr>
            <?php
            $index_row = null;

if (isset($form_mup->fields) && count($form_mup->fields) > 0) {
    foreach ($form_mup->fields as $key => $field) {
        $index_row = $key + 1;
        ?>
            <tr class="data-row-field">
                <td>
                    <select class="mup_field_type" name="field_type" disabled>
                        <option value="<?php echo $field->id; ?>">
                            <?php esc_attr_e($this->get_name_field_type($field->id), 'mailup'); ?>
                        </option>
                    </select>
                </td>
                <td>
                    <select name="field_type_type" class="mup_field_type_type" <?php if (ctype_alpha($field->id)) {
                        echo 'disabled ';
                    } ?>>
                        <?php $type_values = ['text', 'date', 'number', 'email'];

        foreach ($type_values as $tv) { ?>
                        <option <?php if ($field->type === $tv) {
                            echo 'selected ';
                        } ?> value="<?php echo $tv; ?>"><?php esc_attr_e($tv, 'mailup'); ?>
                        </option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <input type="text" name="input-text" class="label-field" value="<?php esc_attr_e($field->name); ?>"
                        placeholder="">
                </td>
                <td>
                    <label for="<?php echo sprintf('req_%s', $index_row); ?>">
                        <input name="<?php if (ctype_alpha($field->id)) {
                            echo 'must_have';
                        } ?>" id="<?php echo sprintf('req_%s', $index_row); ?>" type="checkbox" <?php if ($field->required) {
                            echo 'checked';
                        } ?> class="chk-required" />
                        <span><?php esc_attr_e('Required', 'mailup'); ?></span>
                    </label>
                </td>
                <td>
                    <input type="button" name="remove-field" value="<?php esc_attr_e('Remove', 'mailup'); ?>"
                        class="button remove-field form-action">
                </td>
            </tr>
                    <?php
    }
} else { ?>
            <tr class="data-row-field">
                <td>
                    <select class="mup_field_type" name="field_type" disabled>
                        <option value="email">
                            <?php esc_attr_e('email', 'mailup'); ?>
                        </option>
                    </select>
                </td>
                <td>
                    <select name="field_type_type" class="mup_field_type_type" disabled>
                        <option value="email" selected><?php esc_attr_e('email', 'mailup'); ?>
                        </option>
                    </select>
                </td>
                <td>
                    <input type="text" name="input-text" class="label-field"
                        value="<?php _e(ucfirst(__('email', 'mailup'))); ?>" placeholder="">
                </td>
                <td>
                    <label for="<?php _e(sprintf('req_%s', $index_row)); ?>">
                        <input name="must_have" id="req_1" type="checkbox" checked class="chk-required" />
                        <span><?php esc_attr_e('Required', 'mailup'); ?></span>
                    </label>
                </td>
                <td>
                    <input type="button" name="remove-field" value="<?php esc_attr_e('Remove', 'mailup'); ?>"
                        class="button remove-field form-action">
                </td>
            </tr>
                    <?php
}
?>

            <tr class="data-row-field">
                <td>
                    <select class="mup_field_type mup_new_field_type" name="field_type">
                        <option></option>
                        <?php foreach ($type_fields as $type_field) { ?>
                        <option value="<?php echo $type_field->id; ?>">
                            <?php
             esc_attr_e($type_field->name, 'mailup'); ?>
                        </option>
                            <?php
                        } ?>
                    </select>
                </td>
                <td>
                    <select name="field_type_type" class="mup_field_type_type">
                        <option value="text" selected="selected"><?php esc_attr_e('text', 'mailup'); ?>
                        </option>
                        <option value="date"><?php esc_attr_e('date', 'mailup'); ?>
                        </option>
                        <option value="number"><?php esc_attr_e('number', 'mailup'); ?>
                        </option>
                        <option value="email"><?php esc_attr_e('email', 'mailup'); ?>
                        </option>
                    </select>
                </td>
                <td>
                    <input type="text" name="input-text" class="label-field" placeholder="" value="">
                </td>
                <td>
                    <label for="<?php _e(sprintf('req_%s', 0)); ?>">
                        <input name="" id="<?php _e(sprintf('req_%s', 0)); ?>" type="checkbox" class="chk-required" />
                        <span><?php esc_attr_e('Required', 'mailup'); ?></span>
                    </label>
                </td>
                <td>
                    <input type="button" name="add-field" value="<?php esc_attr_e('Add', 'mailup'); ?>"
                        class="button add-field form-action">
                </td>
            </tr>
        </tbody>
    </table>
</section>