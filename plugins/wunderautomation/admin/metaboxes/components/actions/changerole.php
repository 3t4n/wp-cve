<?php
$wp_roles = wp_roles();

$allRoles      = $wp_roles->roles;
$editableRoles = apply_filters('editable_roles', $allRoles);
?>

<div v-if="step.action.action == '\\WunderAuto\\Types\\Actions\\ChangeRole'">
    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Change role for', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.who" class="tw-w-full">
                <option v-for="item in $root.currentObjects(stepKey, 'user')"
                        :value="item.id">
                    {{ item.id }}
                </option>
            </select>
            <br>
            <i>
                <?php _e(
                    'Select the user to update the role for.',
                    'wunderauto'
                );?>
            </i>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Change role to', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.newRole" class="tw-w-full">
                <option value="no_role"><?php _e('No role', 'wunderauto');?></option>
                <?php foreach ($editableRoles as $key => $role) :?>
                    <option value="<?php esc_attr_e($key)?>">
                        <?php esc_html_e($role['name'] . " ($key)")?>
                    </option>
                <?php endforeach?>
            </select>
            <br>
            <i>
                <?php _e(
                    'Select the new role to set for the user',
                    'wunderauto'
                );?>
            </i>
        </div>
    </div>
</div>



