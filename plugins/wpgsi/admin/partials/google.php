<div class="wrap">
	<div id="icon-options-general" class="icon32"> </div>
	<h1> <?php 
esc_attr_e( "Google Spreadsheet Settings ", "wpgsi" );
?> </h1>

    <div id="wpgsi_google">
        <br>
        <form name="woogs_settings" method="POST" action="<?php 
echo  esc_url( admin_url( 'admin-post.php' ) ) ;
?>" > 
            <!-- Form Submission  Fields Starts -->
            <input type="hidden" name="action" value="google_settings">
            <input type="hidden" name="nonce"  value="<?php 
echo  wp_create_nonce( 'wpgsi-google-nonce' ) ;
?>"> 
            <!-- Form Submission  Fields Ends -->
            <?php 

if ( isset( $credential['client_email'] ) ) {
    ?>
                <!-- credential JSON Starts disabled -->
                <p><b> Service Account email address : </b></p>
                <textarea id="credential" name="credential" cols="80" rows="8" class="large-text" disabled > 
                    <?php 
    echo  "***Share your Google Spreadsheet with this service account email address :   " . $credential['client_email'] ;
    ?>
                </textarea>
                <br><br>
                <!-- credential JSON Ends -->
                <?php 
    echo  "<a href=" . admin_url( 'admin-post.php?action=google_settings&deleteCredential=1&nonce=' ) . wp_create_nonce( 'wpgsi-google-nonce-delete' ) . " class='button-secondary' style='text-decoration: none; color: #7f7f7f;'>  Remove Credential  </a>" ;
    $ret = $this->googleSheet->wpgsi_token_validation_checker( $google_token );
    #  Checking token is valid || Display it
    
    if ( $ret[0] ) {
        echo  "<span style='vertical-align: middle;padding-top: 5px;' class='dashicons dashicons-yes'> </span>" ;
        # if valid it will show tick
    } else {
        echo  "<span style='vertical-align: middle;padding-top: 5px;' class='dashicons dashicons-no'> </span>" ;
        # if false it will Show cross
    }
    
    ?>
            <?php 
} else {
    ?>
                <!-- credential JSON Starts disabled -->
                <p><b> Exactly copy the downloaded file Credentials, and Paste it here : </b></p>
                <textarea id="credential" name="credential" cols="80" rows="8" class="large-text">  </textarea>
                <br><br>
                <input type='submit' class='button-primary' name='save_btn'   value='Save' /> 
                <span style='float:right; padding-right:25px;'> <a href='https://console.developers.google.com/apis/dashboard' target='_blank' style='text-decoration: none;font-style: italic; color: #ffba00;'> enable API & create service account</a> </span>
                <!-- credential JSON Ends -->
            <?php 
}

?>
            <!--  Log Link  -->
            <span style='float:right; padding-right:25px;'><a href="<?php 
echo  admin_url( 'admin.php?page=wpgsi-settings&action=log' ) ;
?>" style='text-decoration: none;font-style: italic;'  >  log for good ! log page. </a> </span>
        </form>

        <br><br>
                
        <p>
            <i>
                <span> 
                    <b>1.</b> <code><b>step-by-step</b></code>  instructions for creating  <a href="<?php 
echo  admin_url( 'admin.php?page=wpgsi-settings&action=service-account-help' ) ;
?>" style='text-decoration: none;' target="_blank" > Google Service account & Service account credentials </a>
                </span>
                <br><br>
                
                <span> 
                    <b>2.</b> Spreadsheet Integration uses <a style='text-decoration: none;' href='https://github.com/woocommerce/woocommerce/blob/master/templates/checkout/thankyou.php'> woocommerce_thankyou </a> Hook for WooCommerce Checkout Page orders so it will  <b>  not </b> work 
                    without any thank you page. Please make sure you have a thank you page for WooCommerce. 
                </span> 
                <br><br>

                <span> 
                    <b>3.</b> This plugin <b>didn't support custom post type</b>. The professional version also supports <b> MetaData </b> as a data source.
                </span> 
                <br><br>

                <span>
                  <b>4.</b> This Plugin has <b> 21 </b> files and  <b>15,955</b> lines of code,  It is the First & only Google service account-based Plugin in the Whole WordPress. 
                    A service account is the most secure and idiomatic way to connect Google API with a single service no more 3rd party & Oauth2.  
                    Just your site and Google API.
                    Development, Testing, and Debugging takes a lot of time & patience. 
                    I hope you will appreciate my effort. 
                </span> 
                
                <!-- For Paid User  -->
                <?php 
?>
                    
                <!-- for Free and Trial  user  -->
                <?php 

if ( wpgsi_fs()->is_trial() || wpgsi_fs()->is_not_paying() ) {
    ?>
                    
                    If possible Please purchase the <?php 
    echo  '<a style="text-decoration: none;" href="' . wpgsi_fs()->get_upgrade_url() . '">' . __( ' Professional copy', 'wpgsi' ) . '</a>' ;
    ?>, 
                    if not please leave a <a style='text-decoration: none;' href='https://wordpress.org/support/plugin/wpgsi/reviews/?filter=5'> 5-star review </a>, It will inspire me to add more awesome feature .    
                    <br><br> 
                    thank you & best regards.
                    <br><br>
                    <b> P.S :</b><a style="text-decoration: none;" href=" <?php 
    echo  admin_url( 'admin.php?page=wpgsi-contact' ) ;
    ?> "> let me know your questions & thoughts.</a> 
                
                <?php 
}

?>
            </i>
        </p>


    </div>

</div> 
