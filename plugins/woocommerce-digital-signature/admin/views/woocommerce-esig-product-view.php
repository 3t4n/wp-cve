<?php
// Silence is golden
if ( ! defined( 'ABSPATH' ) ) { 
	exit; // Exit if accessed directly
}
// Add an nonce field so we can check for it later.
wp_nonce_field('esig_woo_product_nonce', 'esig_woo_product_box_nonce');


$product_agreement = get_post_meta($data['ID'], '_esig_woo_meta_product_agreement', true );

$esign_woo_sad_page = get_post_meta($data['ID'], '_esig_woo_meta_sad_page', true );

$esign_woo_sign_logic= get_post_meta($data['ID'], '_esign_woo_sign_logic', true );


 if($product_agreement)
{
   $checked="checked";	
}
else
{
	$checked="";
}

?>
 <div><input type="checkbox" name="esig_product_agreement" value="1" <?php echo $checked; ?>><?php _e('Enable product agreement for this product','esig'); ?></div>

  <div><h4><?php _e('Signing Logic?','esig'); ?></h4></div>
  
  <div>
      <select id="esign_woo_sign_logic" name="esign_woo_sign_logic">
          
          <option value="before_checkout" <?php if($esign_woo_sign_logic=="before_checkout") echo "selected" ; ?>><?php _e('Redirect user to sign before checkout','esig'); ?></option>
          <option value="after_checkout" <?php if($esign_woo_sign_logic=="after_checkout") echo "selected" ; ?>><?php _e('Redirect user to esign after checkout','esig'); ?></option>
      
      </select>   
   </div>
 
  

 <div><h4><?php _e('What agreement needs to be signed?','esig'); ?></h4></div>
	
<div><select name="esign_woo_sad_page">
			<?php
			
			$esig_sad = new esig_woocommerce_sad();
			$stand_alone_pages = $esig_sad->esig_get_sad_pages();
			
			foreach($stand_alone_pages as $sad_key => $sad_page)
			{
			    
				if($esign_woo_sad_page != "pleaseslc" && $esign_woo_sad_page == $sad_key){ $selected="selected"; } else { $selected=""; }
				echo '<option value="'. $sad_key .'" '. $selected .' > '. $sad_page .' </option>';	
			}
			
			?></select></div>

<div>&nbsp;</div>			
 <div><a href="admin.php?post_type=esign&page=esign-add-document&esig_type=sad"><?php _e('Create a new document','esig'); ?></a><br><a href="index.php?page=esign-woocommerce-about"><?php _e('Need help?','esign')?></a></div>

