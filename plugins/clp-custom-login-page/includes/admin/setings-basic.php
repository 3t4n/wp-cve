<?php 
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$wp_version = get_bloginfo( 'version' );
$min_version_msg = '';
if (version_compare($wp_version, '4.5.0', '<') ) {
    $min_version_msg = __(' This functionality requires minimum WordPress 4.5.0 version.', 'clp-custom-login');
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if ( isset($_POST['clp-login-type']) ) {
        $settings['basic']['login-type'] = esc_attr($_POST['clp-login-type']);   
    }    

	if ( isset($_POST['clp-auth-exp']) ) {
        $settings['basic']['auth-cookie'] = esc_attr($_POST['clp-auth-exp']);   
    }

	if ( isset($_POST['clp-auth-exp-unit']) ) {
        $settings['basic']['auth-cookie-unit'] = esc_attr($_POST['clp-auth-exp-unit']);   
    }

	if ( isset($_POST['clp-auth-exp-remember']) ) {
        $settings['basic']['auth-cookie-remember'] = esc_attr($_POST['clp-auth-exp-remember']);   
    }

	if ( isset($_POST['clp-auth-exp-remember-unit']) ) {
        $settings['basic']['auth-cookie-remember-unit'] = esc_attr($_POST['clp-auth-exp-remember-unit']);   
    }  
}

?>

<div class="table-wrapper">
    <h3><?php _e('Customize Login Page', 'clp-custom-login');?></h3>
    <table>
        <tbody>

        <tr><th>
         <p class="clp-hint"><?php _e('All customization changes are made in WordPress Customizer. You can start by clicking the button below.', 'clp-custom-login');?></p>
            <p>
                <a href="<?php echo admin_url( 'admin.php?page=clp-customize' );?>" class="clp-customize-button"><?php _e('Customize', 'clp-custom-login');?></a>
            </p>
        </th></tr>

        </tbody>
    </table>

</div>


<div class="table-wrapper">
    <h3><?php _e('Settings', 'clp-custom-login');?></h3>
    <table>
        <tbody>

        <tr>
            <th><?php _e('Login Method', 'clp-custom-login');?></th>
            <td>
                <h4><?php printf(__('You can change the default login method to accept Email, Username or both.%s', 'clp-custom-login'), $min_version_msg);?></h4>
                <fieldset>
                    <p>
                        <label><input type="radio" name="clp-login-type" value="default" <?php checked('default', $settings['basic']['login-type']);?>><?php _e('Default (both Email and Username)', 'clp-custom-login');?></label>
                    </p>
                    <p>
                        <label><input type="radio" name="clp-login-type" value="email" <?php checked('email', $settings['basic']['login-type']);?>><?php _e('Accept only Email', 'clp-custom-login');?></label>
                    </p>
                    <p>
                        <label><input type="radio" name="clp-login-type" value="username" <?php checked('username', $settings['basic']['login-type']);?>><?php _e('Accept only Username', 'clp-custom-login');?></label>
                    </p>
                </fieldset>
            </td>
        </tr>
        
        <tr>
            <th><?php _e('Session Expiration', 'clp-custom-login');?></th>
            <td>

                <h4><?php _e('Change session expiration time of logged-in user.', 'clp-custom-login');?></h4>
                    <fieldset>
                    <p>
                        <label for="clp-auth-exp"><?php _e('Unchecked "Remember Me"', 'clp-custom-login');?></label>
                        <input type="number" id="clp-auth-exp" name="clp-auth-exp" value="<?php echo esc_attr( $settings['basic']['auth-cookie'] );?>">
                        <select name="clp-auth-exp-unit">
                            <option value="minute" <?php selected('minute', $settings['basic']['auth-cookie-unit']);?>><?php _e('Minutes', 'clp-custom-login');?></option>
                            <option value="hour" <?php selected('hour', $settings['basic']['auth-cookie-unit']);?>><?php _e('Hours', 'clp-custom-login');?></option>
                            <option value="day" <?php selected('day', $settings['basic']['auth-cookie-unit']);?>><?php _e('Days', 'clp-custom-login');?></option>
                            <option value="month" <?php selected('month', $settings['basic']['auth-cookie-unit']);?>><?php _e('Months', 'clp-custom-login');?></option>
                        </select>
                    </p>
                    <p>
                        <label for="clp-auth-exp-remember"><?php _e('Checked "Remember Me"', 'clp-custom-login');?></label>
                        <input type="number" id="clp-auth-exp-remember" name="clp-auth-exp-remember" value="<?php echo esc_attr( $settings['basic']['auth-cookie-remember'] );?>">
                        <select name="clp-auth-exp-remember-unit">
                            <option value="minute" <?php selected('minute', $settings['basic']['auth-cookie-remember-unit']);?>><?php _e('Minutes', 'clp-custom-login');?></option>
                            <option value="hour" <?php selected('hour', $settings['basic']['auth-cookie-remember-unit']);?>><?php _e('Hours', 'clp-custom-login');?></option>
                            <option value="day" <?php selected('day', $settings['basic']['auth-cookie-remember-unit']);?>><?php _e('Days', 'clp-custom-login');?></option>
                            <option value="month" <?php selected('month', $settings['basic']['auth-cookie-remember-unit']);?>><?php _e('Months', 'clp-custom-login');?></option>
                        </select>
                    </p>
                </fieldset>

                <p><?php _e('* set 0 for no expiration.', 'clp-custom-login');?></p>
            </td>
        </tr>

        <tr><th>
            <p class="clp-submit">
                <input type="submit" name="submit" class="button clp-button submit" value="<?php _e('Save All Settings', 'clp-custom-login');?>" form="clp-options"/>
            </p>
        </th></tr>

        </tbody>
    </table>

</div>