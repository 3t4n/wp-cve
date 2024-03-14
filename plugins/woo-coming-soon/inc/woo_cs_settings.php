<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<style type="text/css">
	.notice.notice-error,
	.error.notice{
		display:none;
	}
</style>
<?php 
	
	
	global $woo_cs_data, $woo_cs_url, $woo_cs_android_settings, $woo_cs_pro, $woo_cs_premium_link, $woo_cs_text_style;
	
	$woo_cs_options = woo_cs_settings_update();
	//pree($woo_cs_options);
	$woo_css_selected_position = (array_key_exists('product_page_position', $woo_cs_options) && $woo_cs_options['product_page_position'] ? $woo_cs_options['product_page_position'] : '');

	$product_page_hooks = array(

	        'woocommerce_before_single_product' => '',
	        'woocommerce_before_single_product_summary' => '',
	        'woocommerce_single_product_summary' => '',
	        'woocommerce_before_add_to_cart_form' => '',
//	        'woocommerce_after_add_to_cart_form' => '',
//	        'woocommerce_before_variations_form' => '',
//	        'woocommerce_before_add_to_cart_button' => '',
//	        'woocommerce_before_single_variation' => '',
//	        'woocommerce_single_variation' => '',
//	        'woocommerce_after_single_variation' => '',
//	        'woocommerce_after_add_to_cart_button' => '',
//	        'woocommerce_after_variations_form' => '',
//	        'woocommerce_after_add_to_cart_button' => '',
	        'woocommerce_product_meta_start' => '',
	        'woocommerce_product_meta_end' => '',
	        'woocommerce_share' => '',
	        'woocommerce_after_single_product_summary' => '',
	        'woocommerce_after_single_product' => '',

    );	
	
	$arrival_date = array_key_exists('arrival_date', $woo_cs_options);
	$stock_based = array_key_exists('stock_based', $woo_cs_options);
?>	

<div class="wrap woo_cs_settings">

<div id="icon-options-general"><br></div><h2><i class="fas fa-road"></i>&nbsp;<?php echo $woo_cs_data['Name']; ?> (<?php echo $woo_cs_data['Version']; ?>)<?php echo ($woo_cs_pro?' '.'Pro':''); ?> - <?php _e('Settings', 'woo-coming-soon'); ?>

<?php $woo_cs_android_settings->ab_io_display($woo_cs_url);?>
</h2>

<hr />
<br />


<div style="font-size: 14px; font-weight:bold; margin-bottom:10px;"><?php _e('Shortcode', 'woo-coming-soon'); ?> <span style="color: #e80808;"><?php echo ($woo_cs_pro?''.'':'(Premium)'); ?></span>:</div>
<div style="font-size: 14px; margin-bottom:10px;">
    <span class="woo_cs_shortcode">[woo_coming_soon product_id="1234"]</span>
</div>



<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

	<a class="woo-cs-vt" title="<?php _e('Click here to watch the video tutorial', 'woo-coming-soon'); ?>" href="https://www.youtube.com/embed/j88rJSwcDf8" target="_blank"><i class="fab fa-youtube"></i></a>
	<?php wp_nonce_field( 'woo_cs_nonce_action', 'woo_cs_nonce_field' ); ?>

    
	<fieldset class="notice_position">

        <label for="woo_css_notice_position"><?php _e('Single Product Page Message Position', 'woo-coming-soon'); ?>:</label>

        <div style="margin: 0 0 5px 0; font-size: 12px;">
             <span style="font-weight: bold;"><?php _e('Default position'); ?>:</span> woocommerce_before_single_product
        </div>
        
        
        

        <div>

            <select id="woo_css_notice_position" name="woo_cs_options[product_page_position]" title="<?php _e("Single Product Page Message Position",'woo-coming-soon'); ?>">

                <option value=""><?php _e('Default', 'woo-coming-soon') ?></option>
                <?php

                    if(!empty($product_page_hooks)){

                        foreach($product_page_hooks as $hook_key => $hook){

                            $selected = ($woo_css_selected_position == $hook_key ? 'selected' : '');

                            echo "<option value='$hook_key' $selected>$hook_key</option>";
                        }

                    }

                ?>

            </select>
            <br />
            <br />

            <a href="https://ibulb.wordpress.com/2019/02/21/woocommerce-single-product-page-visual-hook-guide/" target="_blank" class="float-right mr-1"><small><?php _e('Visual Hook Guide', 'easy-upload-files-during-checkout'); ?></small></a>
            <br />
            <br />


        </div>


    </fieldset>

 	   
    <div class="product_msg">

		<div class="msg_left">
            <label for="product_page_text"><?php _e('Product Page Message', 'woo-coming-soon'); ?>:</label>
            <textarea id="product_page_text" type="text" name="woo_cs_options[product_page_text]"><?php echo array_key_exists('product_page_text', $woo_cs_options)?$woo_cs_options['product_page_text']:''; ?></textarea>
            
			<a href="https://www.youtube.com/embed/ECtI-V82Wjs" target="_blank">
           	<img src="<?php echo $woo_cs_url; ?>img/notice-styles.png" />
            </a>

            
		</div>
        <div class="style_right">
        	<label for="product_page_text"><?php _e('Default Style', 'woo-coming-soon'); ?>:</label>
        	<code>
            	<?php echo $woo_cs_text_style; ?>
            </code>
        	<textarea name="woo_cs_options[product_page_style]"><?php echo array_key_exists('product_page_style', $woo_cs_options)?$woo_cs_options['product_page_style']:$woo_cs_text_style; ?></textarea>
            
        </div>
        
	</div>        

	<fieldset>		
        <label for="product_page_edit"><?php _e('Product Page Button', 'woo-coming-soon'); ?> (<?php _e('Admin Panel', 'woo-coming-soon'); ?>):</label>
        <textarea placeholder="<?php echo woo_cs_btn_text(); ?>" id="product_page_edit" type="text" name="woo_cs_options[product_page_edit]"><?php echo array_key_exists('product_page_edit', $woo_cs_options)?$woo_cs_options['product_page_edit']:''; ?></textarea>        
	</fieldset>

    <fieldset>

        <label for="product_arrival_date">
            <input type="checkbox" id="product_arrival_date" value="1" name="woo_cs_options[arrival_date]" <?php checked($arrival_date) ?> />
            <?php _e('Specific date based?', 'woo-coming-soon'); ?>
        </label>
		<div class="wc_arrival_toggle" <?php echo $arrival_date?'style="display:block;"':''; ?>>
            <div class="wc_arrival_toggle_left">
            	<img src="<?php echo $woo_cs_url; ?>img/arrival-demo.png" />
            </div>
            <div class="wc_arrival_toggle_right">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/vm2JMMXYsCc" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
           
        </div>

    </fieldset>

    <fieldset style="margin-top: 10px;">

        <label for="product_stock_based">
            <input type="checkbox" id="product_stock_based" value="1" name="woo_cs_options[stock_based]" <?php checked($stock_based) ?> />
            <?php _e('Stock availability based?', 'woo-coming-soon'); ?>
        </label>
        
        <div class="wc_stock_toggle" <?php echo $stock_based?'style="display:block;"':''; ?>>
            <div class="wc_stock_toggle_left">
            	<a href="https://ps.w.org/woo-coming-soon/assets/screenshot-10.png" target="_blank">
            	<img src="<?php echo $woo_cs_url; ?>img/stock-based.png" />
                </a>
            </div>
            <div class="wc_stock_toggle_right">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/00hCYDJ4wA4" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
           
        </div>

    </fieldset>

	<p class="submit">
    <input type="submit" value="<?php _e('Save Changes', 'woo-coming-soon'); ?>" class="button button-primary" id="submit" name="submit" />
    </p>
</form>


<div class="wp-plugins-list">
<strong><?php _e('Install Plugins', 'woo-coming-soon'); ?> - (<?php _e('Optional', 'woo-coming-soon'); ?>):</strong>
<ul>
<li>
<a href="<?php echo admin_url('plugin-install.php?s=gulri+slider&tab=search&type=term'); ?>" target="_blank"><?php _e('Click here to install the recommended Image Slider plugin', 'woo-coming-soon'); ?></a>
</li>
<li>
<a href="<?php echo admin_url('plugin-install.php?s=wp+header+images+fahad&tab=search&type=term'); ?>" target="_blank"><?php _e('Click here to install the recommended Header Images plugin', 'woo-coming-soon'); ?></a>
</li>

<?php if(!$woo_cs_pro): ?>
<li>
<a href="<?php echo esc_url($woo_cs_premium_link); ?>" target="_blank"><?php _e('Go Premium', 'woo-coming-soon'); ?></a>
</li>
<?php endif; ?>
</ul>
<?php if(!$woo_cs_pro): ?>
<div style="text-align:center"><a href="<?php echo $woo_cs_premium_link; ?>" target="_blank"><img src="<?php echo $woo_cs_url; ?>/img/pro-features.png" /></a></div>
<?php endif; ?>
</div>


</div>


<script type="text/javascript" language="javascript">

jQuery(document).ready(function($) {

	

});

</script>
<style type="text/css">
.update-nag,
#message {
    display: none;
}
[class^="icon-"], [class*=" icon-"] {
    margin-right: 6px;
}
#wpcontent, #wpfooter {
    background-color: #fff;
}
</style>