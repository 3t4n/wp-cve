<?php if ( ! defined('WPINC')) {
    die;
} ?>
<div class="wrap">

    <h1><?php _e('Edit role', 'premmerce-users-roles'); ?></h1>

    <a href="<?php echo $backUrl ?>">&larr;<?php _e('Back to', 'premmerce-users-roles') ?> Premmerce Users Roles</a>
    <br class="clear">

    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
        <?php wp_nonce_field( 'premmerce-edit-user-role', '_premmerce_edit_user_role_nonce' ); ?>
        <div id="col-container">
            <div id="col-left">
                <div class="col-wrap">
                    <div class="form-wrap">
                        <div class="form-field form-required">
                            <label for="display_name"><?php _e('Name', 'premmerce-users-roles'); ?></label>
                            <input name="display_name" id="display_name" type="text"
                                   class="display_name"
                                   maxlength="25"
                                   value="<?php echo $dName != '' ? $dName : $curName; ?>">
                            <p class="description"><?php _e('Role display name', 'premmerce-users-roles'); ?></p>
                        </div>

                        <div class="form-field">
                            <label for="role"><?php _e('Inherit role', 'premmerce-users-roles'); ?></label>
                            <select name="role" id="role" class="role"
                                    onchange="UsersRoles.getRoleCapabilities(this.options[this.selectedIndex].value)">
                                <option value="null">-</option>
                                <?php foreach ($roles as $key => $r): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $r['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php _e('Inherit capabilities from selected role',
                                    'premmerce-users-roles'); ?></p>
                        </div>

                        <input type="hidden" name="action" value="premmerce_update_role">
                        <input type="hidden" name="role_name" value="<?php echo $curKey; ?>">

                        <input type="submit"
                               name="update_role"
                               id="submit"
                               class="button button-primary"
                               value="<?php _e('Update', 'premmerce-users-roles'); ?>"
                        >

                        <span id="delete-link">
                            <a class="delete" href="<?php echo str_replace( '__role__', $curKey, $deleteUrl ); ?>"
                               data-action--delete><?= __('Delete', 'premmerce-extended-users'); ?></a>
                        </span>
                    </div>
                </div>
            </div>

            <div id="col-right" class="col-right">
                <div class="col-wrap">
                    <div class="form-wrap">

                        <div class="selectAll">
                            <label>
                                <input type="checkbox" onclick="UsersRoles.selectAllCapabilities(this)">
                                <strong><?php _e('Select all / Unselect all', 'premmerce-users-roles'); ?></strong>
                            </label>
                        </div>

                        <br>

                        <fieldset fildset-capabilities class="capabilitiesList">
                            <?php $chunks = array_chunk($capabilities, round(count($capabilities) / 4), true); ?>
                            <?php foreach ($chunks as $capabilities): ?>
                                <div>
                                    <?php foreach ($capabilities as $key => $c): ?>

                                        <label>
                                            <input name="capabilities[]" type="checkbox"
                                                <?php if (array_key_exists($key, $curCapabilities)): ?>
                                                    checked="checked"
                                                <?php endif; ?>
                                                   value="<?php echo $key; ?>">
                                            <?php echo $key; ?>
                                        </label>

                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </fieldset>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
