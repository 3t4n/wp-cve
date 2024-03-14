<?php
class Wc_updateVariationInCartAdmin
{

    public function __construct()
    {

        /*------------------------Add admin menu in setting------------------------------*/

        add_action('admin_menu', array(
            $this,
            'woo_ck_wuvic_cart_variation_plugin_menu'
        ));

        /*--------------------------------Variation edit ajax------------------------------*/

        add_action('wp_ajax_nopriv_cart_variation_edit', array(
            $this,
            'cart_variation_edit_callback'
        ));

        add_action('wp_ajax_cart_variation_edit', array(
            $this,
            'cart_variation_edit_callback'
        ));

        /*--------------------------------plugin activation link filter------------------------------*/

        add_filter('plugin_action_links_' . WUVIC_PLUGIN_BASENAME , array(
            $this,
            'add_action_links'
        ));

        /*--------------------------------admin ajax variable------------------------------*/

        add_action('admin_head', array(
            $this,
            'woo_ck_wuvic_admin_global_js_vars'
        ));

    }

    public function woo_ck_wuvic_admin_global_js_vars()
    {

        $ajax_url = 'var admin_ajax_params = {"ajax_url":"' . admin_url('admin-ajax.php') . '"};';

        echo "<script type='text/javascript'>\n";

        echo "/* <![CDATA[ */\n";

        echo $ajax_url;

        echo "\n/* ]]> */\n";

        echo "</script>\n";

    }

    /*------------------------function to add setting menu and its page-----------------------------------*/

    public function woo_ck_wuvic_cart_variation_plugin_menu()
    {

        add_options_page(__('Cart Variation Update', 'woocommerce-extension') ,

        __('Cart Variation Update', 'woocommerce-extension') , 'manage_options', 'woocommerce-edit-variation', array(
            $this,
            'woo_ck_wuvic_cart_variation_plugin_menu_option'
        ));

    }

    public function woo_ck_wuvic_cart_variation_plugin_menu_option()
    {

        if (!current_user_can('manage_options'))
        {

            wp_die(__('You do not have sufficient permissions to access this page.'));

        }

?>



		<h1><?php _e("Woocommerce Edit Variation On cart Setting", 'woocommerce-extension'); ?></h1>



		<div class="wrap">



			<div class="col span_12" id="WOO_CK_WUVIC_form_success" style="display: none;font-size: 21px; font-style: italic;"></div>



			<form id="update_cvar_content" method="post" action="">



				<table class="form-table">



					<tbody>



						<tr>



							<th scope="row"><label for="cvar_enable"><?php _e("Enable", 'woocommerce-extension'); ?></label></th>



							<td><input name="WOO_CK_WUVIC_enable" type="checkbox" id="WOO_CK_WUVIC_enable" value="enable" <?php if ("true" == get_option('WOO_CK_WUVIC_status'))
        {
            echo 'checked';
        } ?> >



								<p class="description" id="cvar_enable_descr"><?php _e("Uncheck this box to completely disable plugin.", 'woocommerce-extension'); ?></p>



							</td>



						</tr><!-- Enable Field -->



						<tr>



							<th scope="row"><label for="cvar_edit_link"><?php _e("Edit Link Text", 'woocommerce-extension'); ?></label></th>



							<td><input name="WOO_CK_WUVIC_edit_link" type="text" id="WOO_CK_WUVIC_edit_link" value="<?php echo get_option('WOO_CK_WUVIC_edit_link_text'); ?>" class="regular-text">



								<p class="description" id="cvar_link_text_descr"><?php _e("Text for edit link.", 'woocommerce-extension'); ?></p>



							</td>



						</tr><!-- Edit Link Text Field -->



						<tr>



							<th scope="row"><label for="cvar_edit_link_class"><?php _e("Css Class For Edit Link.", 'woocommerce-extension'); ?></label></th>



							<td><input name="WOO_CK_WUVIC_edit_link_class" type="text" id="WOO_CK_WUVIC_edit_link_class" value="<?php echo get_option('WOO_CK_WUVIC_edit_link_class'); ?>" class="regular-text">



							<p class="description" id="cvar_link_class_descr"><?php _e("Add css classes.", 'woocommerce-extension'); ?></p>



							</td>



						</tr><!-- Css Class For Edit Link Field -->



						<tr>



							<th scope="row"><label for="cvar_update_btn"><?php _e("Update Button Text", 'woocommerce-extension'); ?></label></th>



							<td><input name="WOO_CK_WUVIC_update_btn" type="text" id="WOO_CK_WUVIC_update_btn" value="<?php echo get_option('WOO_CK_WUVIC_update_btn_text'); ?>" class="regular-text">



							<p class="description" id="cvar_update_btn_descr"><?php _e("Text for update button.", 'woocommerce-extension'); ?></p>



							</td>



						</tr><!-- Update Button Text Field -->



						<tr>



							<th scope="row"><label for="cvar_update_btn_class"><?php _e("Css Class For Update Button", 'woocommerce-extension'); ?></label></th>



							<td><input name="WOO_CK_WUVIC_update_btn_class" type="text" id="WOO_CK_WUVIC_update_btn_class" value="<?php echo get_option('WOO_CK_WUVIC_update_btn_class'); ?>" class="regular-text">



							<p class="description" id="cvar_update_btn_class_descr"><?php _e("Add css class for update button.", 'woocommerce-extension'); ?></p>



							</td>



						</tr><!-- Css Class For Update Button Field -->



						<tr>



							<th scope="row"><label for="cvar_cancel_btn"><?php _e("Cancel Button Text", 'woocommerce-extension'); ?></label></th>



							<td><input name="WOO_CK_WUVIC_cancel_btn" type="text" id="WOO_CK_WUVIC_cancel_btn" value="<?php echo get_option('WOO_CK_WUVIC_cancel_btn'); ?>" class="regular-text">



								<p class="description" id="cvar_cancel_btn_descr"><?php _e("Text for cancel button.", 'woocommerce-extension'); ?></p>



							</td>



						</tr><!-- cancel Button Field -->



						<tr>



							<th scope="row"><label for="cvar_cancel_btn_class"><?php _e("Css Class For Cancel Button", 'woocommerce-extension'); ?></label></th>



							<td><input name="WOO_CK_WUVIC_cancel_btn_class" type="text" id="WOO_CK_WUVIC_cancel_btn_class" value="<?php echo get_option('WOO_CK_WUVIC_cancel_btn_class'); ?>" class="regular-text">



								<p class="description" id="cvar_cancel_btn_class_descr"><?php _e("Text for cancel button class.", 'woocommerce-extension'); ?></p>



							</td>



						</tr><!-- Css Class For cancel Button Field -->



					</tbody>



				</table>



				<?php $woo_ck_wuvic_img_loader = WUVIC_WOO_UPDATE_CART_ASSESTS_URL . 'img/uploading.gif'; ?>



				<img src="<?php echo $woo_ck_wuvic_img_loader; ?>" alt="Smiley face" height="42" width="42" 



				id="loder_img_cvform" style="display:none;" />



				<p class="submit"><input type="button" name="cvar_submit" id="cvar_submit" class="button button-primary" value="<?php _e("Save Changes", 'woocommerce-extension'); ?>"></p>



			</form>



		</div>



		<?php

        $this->add_script_cart_variation();

    }

    /*********************  cart variation Jquery  ********************/

    public function add_script_cart_variation()
    { ?>



		<script type="text/javascript">



			jQuery(document).ready(function(){



				jQuery('#cvar_submit').click(function(e){



					e.preventDefault();



					jQuery('#WOO_CK_WUVIC_form_success').css('display', 'none');



					jQuery('#loder_img_cvform').css('display','block');



					var i = "yes";



					var woo_ck_wuvic_edit_link_text = jQuery("#WOO_CK_WUVIC_edit_link").val();



					var woo_ck_wuvic_edit_link_class = jQuery("#WOO_CK_WUVIC_edit_link_class").val();



					var woo_ck_wuvic_update_btn = jQuery("#WOO_CK_WUVIC_update_btn").val();



					var woo_ck_wuvic_update_btn_class = jQuery("#WOO_CK_WUVIC_update_btn_class").val();



					var woo_ck_wuvic_cancel_btn = jQuery("#WOO_CK_WUVIC_cancel_btn").val();



					var woo_ck_wuvic_cancel_btn_class = jQuery("#WOO_CK_WUVIC_cancel_btn_class").val();



					if (jQuery('input#WOO_CK_WUVIC_enable').is(':checked')) { 



						var woo_ck_wuvic_enable = "true";



					}else{



						var woo_ck_wuvic_enable = "false";



					}



					var final_sring = "action=cart_variation_edit&WOO_CK_WUVIC_edit_link="+woo_ck_wuvic_edit_link_text+"&WOO_CK_WUVIC_edit_link_class="+woo_ck_wuvic_edit_link_class+"&WOO_CK_WUVIC_update_btn="+	woo_ck_wuvic_update_btn+"&WOO_CK_WUVIC_update_btn_class="+woo_ck_wuvic_update_btn_class+"&WOO_CK_WUVIC_enable="+woo_ck_wuvic_enable+"&WOO_CK_WUVIC_cancel_btn="+woo_ck_wuvic_cancel_btn+"&WOO_CK_WUVIC_cancel_btn_class="+woo_ck_wuvic_cancel_btn_class; 



					var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';



					jQuery.ajax({



						type:    "POST",



						url:     admin_ajax_params.ajax_url,



						dataType: 'json',



						data:    final_sring,



						// async : false,



						success: function(data){



							jQuery('#loder_img_cvform').css('display','none');



							jQuery('#WOO_CK_WUVIC_form_success').css('display', 'block');



							jQuery('#WOO_CK_WUVIC_form_success').text('Updated Successfully');



						}



					}); 



				});



			});



		</script>



	<?php
    }

    public function cart_variation_edit_callback()
    {

        if (isset($_POST))
        {

            update_option('WOO_CK_WUVIC_status', sanitize_text_field($_POST['WOO_CK_WUVIC_enable']));

            update_option('WOO_CK_WUVIC_edit_link_text', sanitize_text_field($_POST['WOO_CK_WUVIC_edit_link']));

            update_option('WOO_CK_WUVIC_edit_link_class', sanitize_text_field($_POST['WOO_CK_WUVIC_edit_link_class']));

            update_option('WOO_CK_WUVIC_update_btn_text', sanitize_text_field($_POST['WOO_CK_WUVIC_update_btn']));

            update_option('WOO_CK_WUVIC_update_btn_class', sanitize_text_field($_POST['WOO_CK_WUVIC_update_btn_class']));

            update_option('WOO_CK_WUVIC_cancel_btn', sanitize_text_field($_POST['WOO_CK_WUVIC_cancel_btn']));

            update_option('WOO_CK_WUVIC_cancel_btn_class', sanitize_text_field($_POST['WOO_CK_WUVIC_cancel_btn_class']));

        }

        die();

    }

    public function add_action_links($links)
    {

        $mylinks = array(
            '<a href="' . admin_url('options-general.php?page=woocommerce-edit-variation') . '">Settings</a>',

        );

        return array_merge($links, $mylinks);

    }

}

new Wc_updateVariationInCartAdmin;
?>