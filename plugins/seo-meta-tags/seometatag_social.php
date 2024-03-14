<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// of the custom Test Toplevel menu
function seometatag_social() {
    echo "<h2>" . __( 'Social Info', 'menu-seometatags' ) . "</h2>";
    
    if ($_POST['action'] == 'update') { do_action( 'update_seo_meta_tags' ); }	
	?>
<div class="wpsmt_content_wrapper">
    <div id="wpsmt_content_top" class="wpsmt_content_cell">
        <div class="tool-box">	
	<p>You can use the boxes below to verify with the different Webmaster Tools, 
            if your site is already verified, you can just forget about these. Enter the verify meta values for:
	</p>
	
	<form method="post">
	<input type="hidden" name="action" value="update" />
	 <?php wp_nonce_field('seo_meta_tags'); ?>				
		
		<table class="form-table">     
                    <?php smt_input_text_field('smt_facebookpage_url','Facebook Page URL:','Facebook Publisher/Author URL:');?>
                    <?php smt_input_text_field('smt_twitter_username','Twitter Username:');?>
                    <?php smt_input_text_field('smt_google_publisher_page','Google Plus Posts Page:');?>
                                          
		</table>
	<p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
	</form>
	</div>
    </div>
    <div id="sidebar-container" class="wpsmt_content_cell">
        
    </div>
</div>
	
	<?php
    
}


