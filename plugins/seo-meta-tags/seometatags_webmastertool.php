<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// of the custom Test Toplevel menu
function seometatag_webmaster_tool() {
    echo "<h2>" . __( 'WebMaster Tool', 'menu-seometatags' ) . "</h2>";
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
                    <?php smt_input_text_field('smt_alexa_varification','<a href="http://www.alexa.com/siteowners/claim" target="_blank">Alexa Verification ID</a>');?>
                    <?php smt_input_text_field('smt_bing_webmaster','<a href="http://www.bing.com/webmaster/?rfp=1#/Dashboard/?url=wordpress.purab.com" target="_blank">Bing Webmaster Tools</a>');?>
                    <?php smt_input_text_field('smt_google_varification','<a href="https://www.google.com/webmasters/verification/verification?hl=en&amp;siteUrl=http%3A%2F%2Fwordpress.purab.com/" target="_blank">Google Webmaster Tools</a>');?>
                    <?php smt_input_text_field('smt_pinterest_webmaster','<a href="https://help.pinterest.com/entries/22488487-Verify-with-HTML-meta-tags" target="_blank">Pinterest</a>');?>
                    <?php smt_input_text_field('smt_yandex_webmaster','<a href="http://help.yandex.com/webmaster/service/rights.xml#how-to" target="_blank">Yandex Webmaster Tools</a>');?>                       
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


