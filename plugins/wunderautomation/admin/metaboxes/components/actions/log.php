<?php
$wp_roles = wp_roles();

$allRoles      = $wp_roles->roles;
$editableRoles = apply_filters('editable_roles', $allRoles);
?>

<div v-if="step.action.action == '\\WunderAuto\\Types\\Actions\\Log'">

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Log file', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input v-model="step.action.value.path" class="tw-w-full"/><br>
            <i>
                <?php _e(
                    'Use an absolute path or a path relative to WP_CONTENT_DIR',
                    'wunderauto'
                );?>
                 (<?php esc_html_e(WP_CONTENT_DIR) ?>)
            </i>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Log data', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input v-model="step.action.value.data" class="tw-w-full"/><br>
        </div>
    </div>

</div>
