<?php
namespace SnazzyMaps;
defined( 'ABSPATH' ) OR exit;
include_once(plugin_dir_path(__FILE__) . _DS . 'helpers.php');

class SnazzyMaps_Settings {
    public static function admin_my_snazzymaps_head($tab){           
        if(isset($_POST['api_key']) && check_admin_referer('snazzy_maps_save_api_key')){
            update_option('MySnazzyAPIKey', sanitize_text_field($_POST['api_key']));
            
			//Redirect to the next page
			if(!headers_sent()){
                wp_safe_redirect(\SnazzyMaps\SnazzyMaps_Helpers::esc_rel_url("?page=snazzy_maps&tab=2"));
				exit();
			}   
        }
    }

    public static function admin_my_snazzymaps_tab($tab){
        if(isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'delete_key' && wp_verify_nonce($_GET['_wpnonce'], 'delete_key')){
            delete_option('MySnazzyAPIKey');
        }        
        $api_key = get_option('MySnazzyAPIKey', null);
?>
   
    <div class="message row">
        <div class="col-md-8">
            <h3>API Key</h3>
            <p>
                If you have a <a href="https://snazzymaps.com" target="_blank">Snazzy Maps</a> account you can access your favorites and private styles from within the plugin.
                Sign up for an <a href="https://snazzymaps.com/account/developer" target="_blank">API Key</a> and paste it into the text box below to access these styles on the <a href="?page=snazzy_maps&tab=1">Explore</a> tab.
            </p>

            <form action="?page=snazzy_maps&tab=2" method="post" class="pure-form pure-form-stacked api-form box-shadow-cell">

               <label for="api_key"><strong>API Key</strong></label>
               <input type="text" id="api_key" name="api_key" 
                      placeholder="Enter your API Key" value="<?php echo esc_attr($api_key); ?>"/>
				<?php wp_nonce_field( 'snazzy_maps_save_api_key' ); ?>
               <button type="submit" class="button button-primary">SAVE</button>
               <?php if(!is_null($api_key)){ ?>
                  <a href="?page=snazzy_maps&tab=2&action=delete_key&_wpnonce=<?php echo esc_attr(wp_create_nonce('delete_key')); ?>" 
                                class="button button-error">DELETE</a>
               <?php } ?>             
            </form>
        </div>
    </div>
   
<?php 
	} 
}
?>