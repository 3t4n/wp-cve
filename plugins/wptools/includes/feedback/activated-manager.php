<?php Namespace wptoolsPlugin_activate{

    if( is_multisite())
       return;


if ( __NAMESPACE__ == 'wptoolsPlugin_activate')
    { 
     $BILLPRODUCT = 'WPTOOLS' ;
     $BILLPRODUCTNAME = 'WP Tools Plugin';
     $BILLPRODUCTSLANGUAGE = 'wptools' ; 
     // $BILLPRODUCTPAGE = 'stop-bad-bots' ;
     $BILLCLASS = 'ACTIVATED_'.$BILLPRODUCT;
     $BILL_OPTIN = strtolower($BILLPRODUCT).'_optin'; 
     $PRODUCT_URL = WPTOOLSURL;
     $PRODUCTVERSION = WPTOOLSVERSION; 
}


  $page_slug = 'wptools_options39';
  $showroom_url = menu_page_url($page_slug, false);


   if(!isset($_COOKIE[$BILLCLASS]))
   {


    ?>
    <div id="bill-activate-modal-wptools" class="bill-activate-modal-wptools" style="display:none" >


                    <div class="bill-vote-message-wptools">
                    <h3><?php echo __('TERMS OF SERVICE', 'wptools');?></h3>
                    <p>

Before using our plugins and themes, it's crucial to understand and agree with:
<br />
1) WordPress is excellent, but proper configurations are necessary. Setting the memory limit is essential to avoid issues as your site grows. If not done, your memory will be very low, leading to warnings, errors, blank pages, and possible site collapse.
<br />
2) You must determine the maximum file size for uploads, page loading time, and ensure secure file permissions and server capacity to support your site.
<br />
3) Although you can reinstall WordPress, plugins, and themes, if you don't back up your database, you may lose all the content.  
<br />
4) Even small sites attract bots (Automated programs simulating visits) and hackers within hours of creation, seeking to steal content, create spam comments, and harm SEO. They may use pingbacks (WordPress resource) to attack other sites, overloading your server and affecting user experience. This could result in a negative reputation with your hosting and potential account suspension.
<br />
5) Our plugins and themes are crucial components that require proper setup. Use our online resources, troubleshooting page, and free support site to explore solutions before leaving negative feedback. 
<br />
We value positive feedback on WordPress as it inspires us to enhance and maintain our products. 
<br />
<br />
Please consider these free solutions, which bolster security and provide a comprehensive suite to tackle potential issues, enhance security, and ensure smooth functionality. Your choice will be recorded and remembered.

<br />
</p>

<form>                         
<a href="#" class="button button-primary" id="wptools-activate-install">
<?php _e("I Agree",'wptools');?></a>

<img alt="aux" src="/wp-admin/images/wpspin_light-2x.gif" id="imagewait" style="display:block" />
                            
                            
<a href="#" class="button button-Secondary" id="wptools-activate-close-dialog">
<?php _e("I do not Agree",'wptools');?></a>



<input type="hidden" id="nonce" name="nonce" value="<?php echo wp_create_nonce('wptools_install_plugin');?>" />
<input type="hidden" id="showroom" name="showroom" value="<?php echo esc_attr($showroom_url);?>" />
 



<br /><br />
</form>                   
</div>
</div>   <!-- end modal -->   
    
    <?php 
    

  add_option( 'wptools_activated_notice', '0' );
	update_option( 'wptools_activated_notice', '0' );


        if(!isset($_COOKIE[$BILLCLASS])) {
            $wtime = time()+(3600*24);
            $jsCode = "document.cookie = '" . $BILLCLASS . "=".time()."; expires=" . date('D, d M Y H:i:s', $wtime ) . " UTC; path=/';";
            echo "<script>{$jsCode}</script>";
        }
 

  } //nao tem cookie...

} // end Namespace
?>