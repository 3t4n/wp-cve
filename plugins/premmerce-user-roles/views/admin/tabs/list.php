<?php if ( ! defined('WPINC')) {
    die;
} ?>
<br class="clear">
<div id="col-container">
    <div id="col-left">
        <div class="col-wrap">
            <div class="form-wrap">
                <h2><?php _e('Add new role', 'premmerce-users-roles'); ?></h2>

                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                    <?php wp_nonce_field( 'premmerce-edit-user-role', '_premmerce_edit_user_role_nonce' ); ?>
                    <div class="form-field form-required">
                        <label for="display_name"><?php _e('Name', 'premmerce-users-roles'); ?></label>
                        <input name="display_name" id="display_name"
                               class="display_name"
                               type="text" maxlength="25"
                               value="<?php echo $dName ?>">
                        <p class="description"><?php _e('Role display name', 'premmerce-users-roles'); ?></p>
                    </div>

                    <div class="form-field">
                        <label for="role"><?php _e('Inherit role', 'premmerce-users-roles'); ?></label>
                        <select name="role" id="role" class="role">
                            <option value="null">-</option>
                            <?php foreach ($roles as $key => $role): ?>
                                <option value="<?php echo $key; ?>"><?php echo $role['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php _e('Inherit rights from selected role',
                                'premmerce-users-roles'); ?></p>
                    </div>

                    <input type="hidden" name="action" value="premmerce_create_role">

                    <input type="submit" name="new_rol" id="submit" class="button button-primary"
                           value="<?php _e('Add Role', 'premmerce-users-roles'); ?>"
                    >
                </form>

                <div class="form-field">
                    <a target="_blank"
                       href="https://codex.wordpress.org/Roles_and_Capabilities"><?php _e('Roles and Capabilities',
                            'premmerce-users-roles'); ?></a>
                    <p class="description"><?php _e('Wordpress documentation about Roles and Capabilities.',
                            'premmerce-users-roles'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div id="col-right" class="col-right">
        <div class="col-wrap">
            <table class="widefat attributes-table wp-list-table ui-sortable">
                <thead>
                <tr>
                    <th scope="col"><?php _e('Name', 'premmerce-users-roles'); ?></th>
                    <th scope="col"><?php _e('Capabilities', 'premmerce-users-roles'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($roles as $key => $r): ?>
                    <tr class="alternate">
                        <td>
                            <strong>
                                <a href="<?php echo $editUrl . $key ?>"><?php echo $r['name']; ?></a>
                            </strong>

                            <div class="row-actions">

                                        <span class="edit">
                                            <a href="<?php echo $editUrl . $key ?>">
                                                <?php if ( ! in_array($key, $defaultRoles)): ?>
                                                    <?php _e('Edit', 'premmerce-users-roles'); ?>
                                                <?php else: ?>
                                                    <?php _e('View', 'premmerce-users-roles'); ?>
                                                <?php endif; ?>
                                            </a>
                                            |
                                        </span>

                                <span class="delete">
                                            <?php if ( ! in_array($key, $defaultRoles)): ?>
                                                <a class="delete" href="<?php echo str_replace( '__role__', $key, $deleteUrl ); ?>"
                                                   data-action--delete><?php _e('Delete',
                                                        'premmerce-users-roles'); ?></a>
                                            <?php else: ?>
                                                <?php _e('Standard roles cannot be removed',
                                                    'premmerce-users-roles'); ?>
                                            <?php endif; ?>
                                        </span>
                            </div>
                        </td>
                        <td><?php echo count($r['capabilities']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
