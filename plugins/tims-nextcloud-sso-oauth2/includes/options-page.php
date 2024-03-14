<?php 

defined( 'ABSPATH' ) || exit;

add_action('admin_menu', 'tims_nso_create_menu');

function tims_nso_create_menu() {
	global $page_hook_suffix;
    $page_hook_suffix = add_options_page("Tim's Nextcloud SSO OAuth2", 'Nextcloud OAuth2', 'administrator', __FILE__, 'tims_nso_options' );
	add_action( 'admin_init', 'tims_nso_settings' );
}

function my_enqueue($hook) {
    global $page_hook_suffix;
    if( $hook != $page_hook_suffix )
        return;        
    wp_register_style('options_page_style', TIMS_NSO_OAUTH2_PLUGIN_ASSET_FOLDER.'/css/options-page.css');
    wp_enqueue_style('options_page_style');
}
add_action( 'admin_enqueue_scripts', 'my_enqueue' );

function tims_nso_settings() {
	register_setting('wp-nextcloud-sso-oauth2-settings', 'tims_nso_address', array('sanitize_callback' => 'tims_nso_address_sanitize'));
    register_setting('wp-nextcloud-sso-oauth2-settings', 'tims_nso_client_id');
    register_setting('wp-nextcloud-sso-oauth2-settings', 'tims_nso_client_secret');
    register_setting('wp-nextcloud-sso-oauth2-settings', 'tims_nso_redirect_url');
    register_setting('wp-nextcloud-sso-oauth2-settings', 'tims_nso_login_button');
    register_setting('wp-nextcloud-sso-oauth2-settings', 'tims_nso_login_button_text');
    register_setting('wp-nextcloud-sso-oauth2-settings', 'tims_nso_default_role');
    register_setting('wp-nextcloud-sso-oauth2-settings', 'tims_nso_match');
    register_setting('wp-nextcloud-sso-oauth2-settings', 'tims_nso_create_account');
    register_setting('wp-nextcloud-sso-oauth2-settings', 'tims_nso_group_link');
    register_setting('wp-nextcloud-sso-oauth2-settings', 'tims_nso_default_group_link_role');
    register_setting('wp-nextcloud-sso-oauth2-settings', 'tims_persistent_data_type');
    register_setting('wp-nextcloud-sso-oauth2-settings', 'tims_nso_login_type');
    register_setting('wp-nextcloud-sso-oauth2-settings', 'tims_nso_debug_log', array('sanitize_callback' => 'tims_nso_debug_log_setting'));
}

function tims_nso_address_sanitize($value){
    if(!(str_starts_with($value, 'https://') || str_starts_with($value, 'http://'))) {
        $value = 'https://'.$value;
    }

    if(!(str_ends_with($value, '/'))){
         $value = $value.'/';
    }

    return sanitize_url($value);
}


function tims_nso_debug_log_setting($val){
    if(!$val){
        $wp_upload_dir = wp_upload_dir();
        unlink($wp_upload_dir['basedir'].'/tims-nextcloud-sso-oauth2-log.txt');
    }
    return $val;
}

function tims_nso_options(){
    $roles = get_editable_roles();
?>

<div class="wrap tims_nso_sso">
    <h1><?php echo __("Tim's Nextcloud SSO OAuth2", 'tims-nextcloud-sso-oauth2') ?> - <small><?php echo __('Login to your WordPress site with your Nextcloud account', 'tims-nextcloud-sso-oauth2') ?></small></h1>
    <form method="post" action="options.php" autocomplete="off">
        <?php settings_fields( 'wp-nextcloud-sso-oauth2-settings' ); ?>
        <div class="tims-nextcloud-box">
            <h2><?php echo __('Step 1 - Nextcloud Location', 'tims-nextcloud-sso-oauth2') ?></h2>
            <div class="form-row">
                <label for="tims_nso_address"><?php echo __('Enter your Nextcloud URL:', 'tims-nextcloud-sso-oauth2') ?></label>
                <input id="tims_nso_address" class="disable-checker-on-change" type="text" name="tims_nso_address" value="<?php echo esc_attr(get_option('tims_nso_address')); ?>">
                <p><strong><?php echo __('Your Nextcloud URL with trailing slash, example: https://cloud.example.org/', 'tims-nextcloud-sso-oauth2') ?></strong></p>
            </div>
        </div>
        <div class="tims-nextcloud-box">
            <h2><?php echo __('Step 2 - Linking Nextcloud', 'tims-nextcloud-sso-oauth2') ?></h2>
            <ol>
                <li><?php echo __('Login to your Nextcloud install as an administrator', 'tims-nextcloud-sso-oauth2') ?></li>
                <li><?php echo __('Go to Settings -> Security', 'tims-nextcloud-sso-oauth2') ?></li>
                <li><?php echo __('Then under "OAuth 2.0 clients" add a new client', 'tims-nextcloud-sso-oauth2') ?></li>
                <li><?php echo __('Set the name to anything you like and the redirect URL to:', 'tims-nextcloud-sso-oauth2') ?> <code><?php echo get_site_url() ?></code></li>
                <li><?php echo __('Then copy the Client Identifier and Secret key it provides you the below fields', 'tims-nextcloud-sso-oauth2') ?></li>
            </ol>
            <div class="form-row">
                <label><?php echo __('Nextcloud Client Identifier', 'tims-nextcloud-sso-oauth2') ?></label>
                <input class="disable-checker-on-change" type="text" name="tims_nso_client_id" value="<?php echo esc_attr(get_option('tims_nso_client_id')); ?>">
            </div>
            <div class="form-row">
                <label><?php echo __('Nextcloud Secret', 'tims-nextcloud-sso-oauth2') ?></label>
                <input class="disable-checker-on-change" type="password" name="tims_nso_client_secret" value="<?php echo esc_attr(get_option('tims_nso_client_secret')); ?>" <?php if(defined('NEXTCLOUD_SECRET')){ echo 'disabled'; } ?>>
                <?php if(defined('NEXTCLOUD_SECRET')){ echo 'Nextcloud Secret key has been set in the wp-config.php file'; } ?>
            </div>
            <p class="info"><?php echo __('You can set the Secret key in the wp-config.php file with:', 'tims-nextcloud-sso-oauth2') ?> <code>define('NEXTCLOUD_SECRET', 'Your Secret key');</code> <?php echo __("If you don't want to save it in the database.", 'tims-nextcloud-sso-oauth2') ?></p>
        </div>
        <div class="tims-nextcloud-box">
            <h2><?php echo __('Step 3 - Test The Connection', 'tims-nextcloud-sso-oauth2') ?></h2>

            <div class="status-return"></div>
            <div class="check-url button-primary" data-text="<?php echo __("Test Connection", 'tims-nextcloud-sso-oauth2') ?>">
                <?php echo __("Test Connection", 'tims-nextcloud-sso-oauth2') ?>
            </div>
        </div>
        <div class="tims-nextcloud-box">
            <h2><?php echo __('Step 4 - User Actions', 'tims-nextcloud-sso-oauth2') ?></h2>
            <div class="form-row">
                <label><?php echo __('How to match Nextcloud users to WordPress users', 'tims-nextcloud-sso-oauth2') ?></label>
                <select name="tims_nso_match" >
                    <option value="email" <?php if(get_option('tims_nso_match') == 'email'){ echo 'selected';} ?>><?php echo __('Email Address', 'tims-nextcloud-sso-oauth2') ?></option>
                    <option value="username" <?php if(get_option('tims_nso_match') == 'username'){ echo 'selected';} ?>><?php echo __('Username', 'tims-nextcloud-sso-oauth2') ?></option>
                </select>
            </div>
            <div class="form-row">
                <label><?php echo __('Create an account if not registered', 'tims-nextcloud-sso-oauth2') ?></label>
                <select name="tims_nso_create_account">
                    <option value="no" <?php if(get_option('tims_nso_create_account') == 'no'){ echo 'selected';} ?>><?php echo __('No', 'tims-nextcloud-sso-oauth2') ?></option>
                    <option value="yes" <?php if(get_option('tims_nso_create_account') == 'yes'){ echo 'selected';} ?>><?php echo __('Yes', 'tims-nextcloud-sso-oauth2') ?></option>
                </select>
            </div>
            <div class="hide-if-not-registering-users">
                <div class="form-row">
                    <label><?php echo __('Default WordPress role for new users', 'tims-nextcloud-sso-oauth2') ?></label>
                    <select name="tims_nso_default_role" class="role-select">
                        <?php foreach ($roles as $role_id => $role) { ?>
                            <option value="<?php echo $role_id ?>" <?php if(get_option('tims_nso_default_role') == $role_id){ echo 'selected';} ?>><?php echo esc_attr($role['name']) ?></option>
                        <?php } ?>
                        <option value="custom" <?php if(get_option('tims_nso_default_role') == 'custom'){ echo 'selected';} ?>><?php echo __('Custom match Nextcloud user groups to WordPress roles', 'tims-nextcloud-sso-oauth2') ?></option>
                    </select>
                </div>
                <div class="groups">  
                    <h3 style="margin-top: 0xp;padding-top: 0px;"><?php echo __('Match Nextcloud user groups to WordPress Roles', 'tims-nextcloud-sso-oauth2') ?></h3>
                    <p><?php echo __('Enter a comma separated list (if more than one group per role) of the groups you want to match to the WordPress user role', 'tims-nextcloud-sso-oauth2') ?></p>
                    <table>
                        <tr>
                            <th><?php echo __('WordPress Roles', 'tims-nextcloud-sso-oauth2') ?></th>
                            <th><?php echo __('Nextcloud User Groups', 'tims-nextcloud-sso-oauth2') ?></th>
                        </tr>
                        <?php $tims_nso_group_link = get_option('tims_nso_group_link'); ?>
                        <?php foreach ($roles as $role_id => $role) { ?>
                            <tr>
                                <td><?php echo esc_attr($role['name']) ?></td>
                                <td><input type="text" name="tims_nso_group_link[<?php echo $role_id ?>]" value="<?php if(isset($tims_nso_group_link[$role_id])){ echo esc_attr( $tims_nso_group_link[$role_id] );} ?>" /></td>
                           </tr>
                        <?php } ?>
                    </table>
                    <hr>
                    <table>
                        <tr>
                            <th>Fallback Role</th>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <select name="tims_nso_default_group_link_role" class="fallback-role-select">
                                    <option value="" <?php if(get_option('tims_nso_default_group_link_role') == ''){ echo 'selected';} ?>><?php echo __('None', 'tims-nextcloud-sso-oauth2') ?></option>
                                    <?php foreach ($roles as $role_id => $role) { ?>
                                        <option value="<?php echo $role_id ?>" <?php if(get_option('tims_nso_default_group_link_role') == $role_id){ echo 'selected';} ?>><?php echo esc_attr($role['name']) ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td style="padding-left: 20px;"><?php echo __("If the Nextcloud user doesn't match a user group under Nextcloud User Groups, they will be assigned this role.", 'tims-nextcloud-sso-oauth2') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="tims-nextcloud-box">
            <h2><?php echo __('Step 5 - When a User Successfully Logs in:', 'tims-nextcloud-sso-oauth2') ?></h2>
            <div class="form-row">
                <select name="tims_nso_login_type">
                    <option value="home"><?php echo __('Redirect to home page', 'tims-nextcloud-sso-oauth2') ?></option>
                    <option value="redirect_back" <?php if(get_option('tims_nso_login_type') == 'redirect_back'){ echo 'selected';} ?>><?php echo __('Redirect back to the page they were on', 'tims-nextcloud-sso-oauth2') ?></option>
                    <option value="custom_url" <?php if(get_option('tims_nso_redirect_url')){ echo 'selected';} ?>><?php echo __('Redirect to custom URL', 'tims-nextcloud-sso-oauth2') ?></option>
                </select>
            </div>
            <div class="form-row hide-if-not-redirecting-to-custom-url">
                <label><?php echo __('On login redirect user to', 'tims-nextcloud-sso-oauth2') ?></label>
                <input type="text" name="tims_nso_redirect_url" value="<?php echo esc_attr(get_option('tims_nso_redirect_url')); ?>" />
            </div>
        </div>
        <div class="tims-nextcloud-box">
            <h2><?php echo __('Step 6 - Other Settings', 'tims-nextcloud-sso-oauth2') ?></h2>
            <div class="form-row">
                <label><?php echo __('Add the Nextcloud login button to the WordPress login page', 'tims-nextcloud-sso-oauth2') ?></label>
                <select name="tims_nso_login_button">
                    <option value="yes" <?php if(get_option('tims_nso_login_button') == 'yes'){ echo 'selected';} ?>><?php echo __('Yes', 'tims-nextcloud-sso-oauth2') ?></option>
                    <option value="no" <?php if(get_option('tims_nso_login_button') == 'no'){ echo 'selected';} ?>><?php echo __('No', 'tims-nextcloud-sso-oauth2') ?></option>
                </select>
            </div>
            <div class="form-row hide-if-not-showing-button">
                <label><?php echo __('Login button text', 'tims-nextcloud-sso-oauth2') ?></label>
                <input type="text" name="tims_nso_login_button_text" value="<?php if(get_option('tims_nso_login_button_text')){echo esc_attr(get_option('tims_nso_login_button_text')); }else{ echo __('Login with Nextcloud', 'tims-nextcloud-sso-oauth2');} ?>" />
            </div>
            <div class="form-row">
                <label><?php echo __('Storage Type', 'tims-nextcloud-sso-oauth2') ?></label>
                <select name="tims_persistent_data_type" >
                    <option value="session" <?php if(get_option('tims_persistent_data_type') == 'session'){ echo 'selected';} ?>><?php echo __('Session', 'tims-nextcloud-sso-oauth2') ?></option>
                    <option value="cookie" <?php if(get_option('tims_persistent_data_type') == 'cookie'){ echo 'selected';} ?>><?php echo __('Cookie', 'tims-nextcloud-sso-oauth2') ?></option>
                </select>
                <p><?php echo __("A unique string and the redirect URL (if you have selected to redirect users back to the page they were on) needs to be saved before visiting Nextcloud, you can choose to save this in the session or as a cookie.", 'tims-nextcloud-sso-oauth2') ?></p>
                <p><?php echo __("If you're not sure what this is and the plugin is working, just leave it on session.", 'tims-nextcloud-sso-oauth2') ?></p>
            </div>
            <hr>
            <div class="form-row">
                <label class="inline"><?php echo __('Enable debug log', 'tims-nextcloud-sso-oauth2') ?></label>
                <input type="checkbox" name="tims_nso_debug_log" <?php if(get_option('tims_nso_debug_log')){ echo 'checked';} ?> />
                <?php if(get_option('tims_nso_debug_log')){ ?>
                    <p><?php echo __('Location of debug file:', 'tims-nextcloud-sso-oauth2') ?> <br><small><?php $wp_upload_dir = wp_upload_dir(); echo esc_attr($wp_upload_dir['basedir'].'/tims-nextcloud-sso-oauth2-log.txt') ?></small></p>
                    <p style="color: #9b0000;"><strong><?php echo __('The debug log will contain sensitive information, its a good idea not to leave this on and remove the file after use.', 'tims-nextcloud-sso-oauth2') ?></strong></p>
                <?php } ?>
            </div>
        </div>
        <div class="tims-nextcloud-box" style="padding-bottom: 20px;">
            <h2><?php echo __('Information', 'tims-nextcloud-sso-oauth2') ?></h2>
            <p><?php echo __('If you want to add the Nextcloud login button to a custom page you can with the shortcode:', 'tims-nextcloud-sso-oauth2') ?></p>
            <p><code>[nextcloud_login class="btn" style=""]button text[/nextcloud_login]</code></p>
            <p><?php echo __('If you want to build your own button you can use the below URL to redirect the user off to Nextcloud to be authenticated', 'tims-nextcloud-sso-oauth2') ?></p>
            <p><code><?php echo wp_login_url() ?>?nc-sso=redirect</code></p>
            <p><?php echo __('If you have enabled the option to redirect a user back to the page they were on you can use the URL (replacing the OriginalPage with the URL you want the user to go back to) :', 'tims-nextcloud-sso-oauth2') ?></p>
            <code><?php echo wp_login_url() ?>?nc-sso=redirect&redirect_to=<strong>OriginalPage</strong></code>
        </div>
        <?php submit_button(); ?>
    </form>
</div>


<script>
jQuery(document).ready(function() {
    jQuery('select[name="tims_nso_create_account"]').change(function(){
        var select = jQuery(this).find(":selected").val();
        if(select == 'yes'){
            jQuery('.hide-if-not-registering-users').show();
        }else{
            jQuery('.hide-if-not-registering-users').hide();
        }
    });
    jQuery('select[name="tims_nso_create_account"]').trigger("change");

    jQuery('.tims_nso_sso .role-select').change(function(){
        var select = jQuery(this).find(":selected").val();
        if(select == 'custom'){
            jQuery('.groups').show();
        }else{
            jQuery('.groups').hide();
        }
    });
    jQuery('.tims_nso_sso .role-select').trigger("change");

    jQuery('select[name="tims_nso_login_button"]').change(function(){
        var select = jQuery(this).find(":selected").val();
        if(select == 'yes'){
            jQuery('.hide-if-not-showing-button').show();
        }else{
            jQuery('.hide-if-not-showing-button').hide();
        }
    });
    jQuery('select[name="tims_nso_login_button"]').trigger("change");

    jQuery('select[name="tims_nso_login_type"]').change(function(){
        var select = jQuery(this).find(":selected").val();
        if(select == 'custom_url'){
            jQuery('.hide-if-not-redirecting-to-custom-url').show();
        }else{
            jQuery('.hide-if-not-redirecting-to-custom-url').hide();
            jQuery('input[name="tims_nso_redirect_url"]').val('');
        }
    });
    jQuery('select[name="tims_nso_login_type"]').trigger("change");

    jQuery(document).on('click','.check-url',function(e){
        e.preventDefault();
        var thisBTN = jQuery(this);  
        thisBTN.html('Checking...');
        //force a trailing slash
        var url = jQuery('input[name="tims_nso_address"]').val();
        url = url.replace(/\/?$/, '/');
        jQuery('input[name="tims_nso_address"]').val(url);
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action:'tims_nso_test_connection',
                identifier: jQuery('input[name="tims_nso_client_id"]').val(),
                url: jQuery('input[name="tims_nso_address"]').val()   
            },
            dataType: 'html',
            success: function(data) {
                jQuery('.status-return').html(data);
                thisBTN.html(thisBTN.data('text'));        
            },
            error: function(xhr, textStatus, errorThrown){
                jQuery('.status-return').html(data);
                thisBTN.html(thisBTN.data('text-error'));
            }
        })
        return false; 
    });
});
</script>
<?php } 