<?php
$wp_roles = wp_roles();

$allRoles      = $wp_roles->roles;
$editableRoles = apply_filters('editable_roles', $allRoles);
?>

<div v-if="step.action.action == '\\WunderAuto\\Types\\Actions\\CreateUser'">

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('User login', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input v-model="step.action.value.login" class="tw-w-full"/><br>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('User password', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input v-model="step.action.value.password" class="tw-w-full"/>
            <br>
            <i>
                <?php _e(
                    'Leave blank to let WordPress generate a random password',
                    'wunderauto'
                );?>
            </i>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Email', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input v-model="step.action.value.email" class="tw-w-full"/><br>
            <br>
            <i>
                <?php _e('Blank to skip setting email, must be unique if filled', 'wunderauto');?>
            </i>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('First name', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input v-model="step.action.value.firstName" class="tw-w-full"/><br>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Last name', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input v-model="step.action.value.lastName" class="tw-w-full"/><br>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Nickname', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input v-model="step.action.value.nickName" class="tw-w-full"/><br>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Role', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.role" class="tw-w-full"
                    v-if="!step.action.value.newRoleViaText">
                <option value="no_role"><?php _e('No role', 'wunderauto');?></option>
                <?php foreach ($editableRoles as $key => $role) :?>
                    <option value="<?php esc_attr_e($key)?>">
                        <?php esc_html_e($role['name'] . " ($key)")?>
                    </option>
                <?php endforeach?>
            </select>
            <input v-if="step.action.value.newRoleViaText" v-model="step.action.value.role" size="40">
            <br>
            <input type="checkbox" v-model="step.action.value.newRoleViaText">Use text input
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Description', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <textarea v-model="step.action.value.description" rows="4" style="width: 100%;"></textarea>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Notify', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.notify" class="tw-w-full">
                <option value="none"><?php _e('None', 'wunderauto');?></option>
                <option value="user"><?php _e('User', 'wunderauto');?></option>
                <option value="admin"><?php _e('Admin', 'wunderauto');?></option>
                <option value="both"><?php _e('Both', 'wunderauto');?></option>
            </select>
            <br>
            <i>
                <?php
                _e(
                    'Note, user notification contains a set/reset password link. The password set above will ' .
                    'likely be changed / overwritten by the user',
                    'wunderauto'
                );
                ?>
            </i>
        </div>
    </div>
</div>


