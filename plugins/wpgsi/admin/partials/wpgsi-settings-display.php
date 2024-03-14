<div class="wrap">
	<div id="icon-options-general" class="icon32"> </div>
	<h1> <?php 
esc_attr_e( "Google service account Settings ", "wpgsi" );
?> </h1>

    <div id="wpgsi_google"> <br>
        <form name="woogs_settings" method="POST" action="<?php 
echo  esc_url( admin_url( 'admin-post.php' ) ) ;
?>" > 
            <!-- Form Submission  Fields Starts -->
            <input type="hidden" name="action" value="google_settings">
            <input type="hidden" name="nonce"  value="<?php 
echo  wp_create_nonce( 'wpgsi-google-nonce' ) ;
?>" > 
            <!-- Form Submission  Fields Ends -->
            <?php 

if ( isset( $credential['client_email'] ) ) {
    ?>
                <!-- credential JSON Starts disabled -->
                <p><b> Service Account email address : </b></p>
                <textarea id="credentialFinal" name="credential" cols="80" rows="8"  class="large-text" disabled > 
                    <?php 
    echo  "*** Share your Google spreadsheet with this service account email address :  " . esc_html( $credential['client_email'] ) ;
    ?>
                </textarea>
                <br><br>
                <!-- credential JSON Ends -->
                <?php 
    echo  "<a href=" . admin_url( 'admin-post.php?action=google_settings&deleteCredential=1&nonce=' ) . wp_create_nonce( 'wpgsi-google-nonce-delete' ) . " class='button-secondary' style=' text-decoration: none; color: #7f7f7f;'>  Remove Credential  </a>" ;
    $ret = $this->googleSheet->wpgsi_token_validation_checker();
    # Checking token is valid || Display it
    
    if ( $ret[0] ) {
        echo  "<span style='vertical-align: middle;padding-top: 5px;' class='dashicons dashicons-yes'> </span>" ;
        # if valid it will show tick
    } else {
        echo  "<span style='vertical-align: middle;padding-top: 5px;' class='dashicons dashicons-no'>  </span>" ;
        # if false it will Show cross
    }
    
    ?>
            <?php 
} else {
    ?>
                <!-- credential JSON Starts disabled -->
                <p><b> Exactly copy the downloaded file Credentials, and Paste it here : </b></p>
                <textarea id="credential" name="credential" cols="80" rows="8"  class="large-text">  </textarea>
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
        </form> <br><br>  
        <p>
            <i>
                <span> 
                    <b>1.</b> <code><b>step-by-step</b></code>  instructions for creating  <a href="<?php 
echo  admin_url( 'admin.php?page=wpgsi-settings&action=service-account-help' ) ;
?>" style='text-decoration: none;' target="_blank" > Google Service account & Service account credentials </a>
                </span>
                <br><br>

                <span> 
                    <b>2.</b> If your integration <b> didn't send data to Google Sheets </b> or didn't respond to the event, Please Delete that integration and create a new one.</a>
                </span>
                <br><br>

                <span> 
                    <b>3.</b> Please share your google spreadsheet with your service account email. Otherwise, it will not work.
                </span>
                <br><br>

                <span> 
                    <b>4.</b> You can only update and create WordPress post type, users and database table from Google sheet. ( users and database table are in the professional version )
                </span>
                <br><br>

                <span> 
                    <b>5.</b> If USER ID or POST ID  is not present then a new user or post will be created. remember it may create it also setting dependent.
                </span>
                <br><br>

                <span> 
                    <b>6.</b> All default WordPress events are in the <b> Free version </b>. enjoy ! 3rd party plugin events are in the Professional version. Though, some events are in the Free version to see the functionality.
                </span>
                <br><br>

                <span> 
                    <b>7.</b> You can use the Professional version for 7 days as a Trial.
                </span>
                <br><br>

                <span> 
                    <b>8.</b> Spreadsheet Integration uses <code> <a style='text-decoration: none;' href='https://github.com/woocommerce/woocommerce/blob/master/templates/checkout/thankyou.php'> woocommerce_thankyou</a></code> Hook for WooCommerce Checkout Page orders so it will  <b>  not </b> work 
                    without any thank you page. Please make sure you have a thank you page for WooCommerce. 
                </span> 
                <br><br>

                <span> 
                   <b>9.</b> Professional version supports custom post type that is created with wordpress default <code><a style='text-decoration: none;' href='https://developer.wordpress.org/reference/functions/register_post_type'> register_post_type()</a></code> Function . The professional version also supports <b> MetaData </b> as a data source.
                </span> 
                <br><br>

                <span> 
                   <b>10.</b> Every WordPress installation is <b>different</b>, their version, PHP version, MySql version, even hosting environments are different, so please follow the installation instructions carefully.
                </span> 
                <br><br>

                <span>
                  <b>11.</b> This Plugin has <b> 21 </b> files and  <b>19,497</b> lines of code,  It is the First & only Google service account-based Plugin in the Whole WordPress. 
                    A service account is the most secure and idiomatic way to connect Google API with a single service no more 3rd party & Oauth2.  
                    Just your site and Google API.
                    Development, Testing, and Debugging take a lot of time & patience. 
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
    echo  '<a style="text-decoration: none;" href="' . wpgsi_fs()->get_upgrade_url() . '">' . __( ' Professional copy', 'my-text-domain' ) . '</a>' ;
    ?>, 
                    if not  please leave a <a style='text-decoration: none;' href='https://wordpress.org/support/plugin/wpgsi/reviews/?filter=5'> 5-star review </a>, It will inspire me to add more awesome feature .    
                    <br><br> 
                    thank you & kindest regards.
                    <br><br>
                    <b> P.S :</b> <a style="text-decoration: none;" href=" <?php 
    echo  admin_url( 'admin.php?page=wpgsi-contact' ) ;
    ?> "> let me know your questions & thoughts.</a> 
                <?php 
}

?>
            </i>
        </p>
    </div>
</div> 
