<?php if ( ! defined( 'ABSPATH' ) ) exit;  ?>
<br/>
<table class="wp-list-table widefat">
    <thead>
    <tr>
    	<th colspan="2"><strong>General Settings</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
    	<td colspan="2"><strong>This plugin should work with default settings, however if you begin to get spam, update the field name below.</strong></td>
    </tr>	

    <form method="post" action="">
    <tr>
    	<td width="250">Honey Pot Field Name</td>
        <td>
             <input id="wpa_field_name" name="wpa_field_name" style="width:300px;" value="<?php echo esc_attr(get_option('wpa_field_name'));?>" type="text" readonly="readonly" />

             <span class="dashicons dashicons-update" style="font-size: 28px; cursor: pointer;" onclick="wpa_unqiue_field_name()"></span>
             
             <br/>
                <em>Changing the field name regularly is a good idea. Please click on icon above to generate new field name.</em>
        </td>
    </tr>    
    <tr>
        <td>Honey Pot Error Message</td>
        <td>
            <input name="wpa_error_message" style="width:300px;" value="<?php echo esc_attr(get_option('wpa_error_message'));?>" type="text" /><br/><em>Mesage for bots. No average human users will see though.</em>
        </td>
    </tr>

    <tr>
        <td>Disable Honeypot Test Widget</td>
        <td>
            <select name="wpa_disable_test_widget">
                    <option value="no" <?php echo get_option('wpa_disable_test_widget') == 'no'?'selected="selected"':''; ?> >No</option>
                    <option value="yes" <?php echo get_option('wpa_disable_test_widget') == 'yes'?'selected="selected"':''; ?> >Yes</option>
            </select>
            <em>Only visible when Admin user is logged in.</em>
        </td>
    </tr>
       
    <tr>        
    	<td colspan="2">
            <?php wp_nonce_field( 'wpa_save_settings', 'wpa_nonce' ); ?>
            <input type="submit" name="submit-wpa-general-settings" class="button-primary" value="Save General Settings" />
        </td>
	</tr>
    </form>
    
    </tbody>
</table><br/>

<script type="text/javascript">
    function wpa_unqiue_field_name(){
        var randomChars = 'abcdefghijklmnopqrstuvwxyz';
        var length      = 6;
        var string = '';
        for ( var i = 0; i < length; i++ ) {
            string += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
        }
        var number = Math.floor(1000 + Math.random() * 9000);

        jQuery('#wpa_field_name').val(string+number);
    }
</script>