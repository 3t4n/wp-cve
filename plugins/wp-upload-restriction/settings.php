<?php
    if(!defined('ABSPATH')){ exit(); }
    wp_enqueue_style('wp-upload-restrictions-styles');

    $tab = filter_input(INPUT_GET, 'tab');
    $roles = $wpUploadRestriction->getAllRoles();
    $custom_types_html = $wpUploadRestriction->prepareCustomTypeHTML();
    $ajax_nonce = wp_create_nonce('wpur-ajax-req');
?>
<script type="text/javascript">var wpur_ajax_nonce = "<?php echo $ajax_nonce; ?>";</script>
<div id="message" class="updated fade"><p><?php esc_html_e('Settings saved.', 'wp_upload_restriction') ?></p></div>
<div id="error_message" class="error fade"><p><?php esc_html_e('Settings could not be saved.', 'wp_upload_restriction') ?></p></div>
<div class="wrap">
    <div class="icon32" id="icon-options-general"><br></div>
    <h2>WP Upload Restriction</h2>
    <h2 class="nav-tab-wrapper">
        <a href="?page=wp-upload-restriction%2Fsettings.php" class="nav-tab <?php esc_attr_e(empty($tab) ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('Restrictions', 'wp_upload_restriction'); ?></a>
        <a href="?page=wp-upload-restriction%2Fsettings.php&tab=custom" class="nav-tab <?php esc_attr_e($tab == 'custom' ? 'nav-tab-active' : ''); ?>"><?php esc_html_e('Custom File Types', 'wp_upload_restriction'); ?></a>
    </h2>

    <?php if(empty($tab)): ?>
    <div class="role-list">

        <div class="sub-title"><?php esc_html_e('Roles', 'wp_upload_restriction'); ?></div>
        <div class="wp-roles">
        <?php foreach($roles as $key => $role):?>
        <a href="<?php esc_attr_e($key); ?>"><?php esc_attr_e($role['name']); ?></a>
        <?php endforeach; ?>
        </div>
    </div>
    
    <div class="mime-list-section">
        <form action="" method="post" id="wp-upload-restriction-form">
            <h2 id="role-name"><?php esc_html_e('Role', 'wp_upload_restriction'); ?>: <span></span></h2>
            <div id="mime-list">
 
            </div>
            <input type="hidden" name="role" value="" id="current-role">
            <input type="hidden" name="action" value="save_selected_mimes_by_role">
            <?php wp_nonce_field( 'wp-upload-restrict', 'wpur_nonce' ) ?>
            <p class="submit"><input type="button" value="<?php esc_attr_e('Save Changes', 'wp_upload_restriction'); ?>"> <span class="submit-loading ajax-loading-img"></span></p>
        </form>
    </div>
    <?php elseif($tab == 'custom'): ?>
    <form action="" method="post">
        <table class="form-table">
            <tbody>
                <tr>
                    <th><?php esc_html_e('Extenstions', 'wp_upload_restriction'); ?></th>
                    <td>
                        <input type="input" value="" name="extensions" id="extensions" size="50" maxlength="50" required data-msg="<?php esc_html_e('Extensions field is required', 'wp_upload_restriction'); ?>">
                        <br><span class="description"><?php esc_html_e('Enter the file extension here. If there are multiple extensions then seperate them with "|". Example, "jpg|jpeg".', 'wp_upload_restriction'); ?></span>
                    </td>
                </tr>
                <tr>
                    <th><?php esc_html_e('MIME Type', 'wp_upload_restriction'); ?></th>
                    <td><input type="input" value="" name="mime_type" id="mime_type" size="50" maxlength="50" required data-msg="<?php esc_html_e('MIME Type field is required', 'wp_upload_restriction'); ?>"></td>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <td id="cont_save_type">
                        <input type="button" id="save_type" value="<?php esc_html_e('Add Type', 'wp_upload_restriction'); ?>">
                        <div class="message"><?php esc_html_e('Type successfully added.', 'wp_upload_restriction'); ?></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <hr>
    <h3><?php esc_html_e('Custom Extensions', 'wp_upload_restriction'); ?></h3>
    <table class="wp-list-table widefat striped list-custom-types">
        <thead>
        <tr>
            <th><?php esc_html_e('Extensions', 'wp_upload_restriction'); ?></th>
            <th><?php esc_html_e('MIME Type', 'wp_upload_restriction'); ?></th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php 
            echo wp_kses($wpUploadRestriction->prepareCustomTypeHTML(), $wpUploadRestriction->allowed_html); 
        ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>