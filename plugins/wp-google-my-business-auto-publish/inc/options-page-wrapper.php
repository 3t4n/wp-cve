<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
?>

<div class="wrap">
    <div id="poststuff">
        <!--main heading-->
        <h1 style="margin-bottom: 20px;"><i style="color: #4a8af4;" class="fa fa-google" aria-hidden="true"></i> <?php _e('Auto Publish for Google My Business', 'wp-google-my-business-auto-publish' ); ?><a target="_blank" class="donate-button" href="https://northernbeacheswebsites.com.au/product/donate-to-northern-beaches-websites/">Donate now</a></h1>
        
        <!--notice message--> 
        <?php 
        //autosocial promotion
        echo '<div class="notice notice-error">';
            echo '<h3 style="margin-top: 15px;" >Upgrade to AutoSocial now!</h3>';

            echo '<p>Please check out the pro version <a href="https://northernbeacheswebsites.com.au/autosocial/">AutoSocial</a> which provides the same functionality as Auto Publish for Google My Business but adds Facebook, LinkedIn, Twitter, Instagram and Pinterest, as well as many more cool features including support for <a target="_blank" href="https://wordpress.org/plugins/the-events-calendar/">The Events Calendar</a> to Google My Business Events!</p>';

            echo '<a style="margin-top: 5px !important; margin-left: 0px !important; color: white !important;" href="https://northernbeacheswebsites.com.au/autosocial/" class="button button-primary">
            Learn More</a>';

        echo '</div>';


        ?>
        
        <?php
        
        //get options
        if( get_option( 'wp_google_my_business_auto_publish_settings' ) ){
            $options = get_option( 'wp_google_my_business_auto_publish_settings' );
            
            if( !isset($options['wp_google_my_business_auto_publish_dismiss_welcome_message']) || $options['wp_google_my_business_auto_publish_dismiss_welcome_message'] != wp_google_my_business_auto_publish_get_version() ){
    
            
        ?>
        
            <div id="google-my-business-message" data-version="<?php echo esc_html(wp_google_my_business_auto_publish_get_version()); ?>" data-dismissible="disable-done-notice-forever" class="notice notice-warning is-dismissible">
            
                <p><h3>A Message from the Developer</h3><p>Hi there! Thanks for using my plugin, if you like it please rate the plugin <a href="https://wordpress.org/support/plugin/wp-google-my-business-auto-publish/reviews/?rate=5#new-post" target="_blank"><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i></a>'s. If the plugin doesn't work please visit the <a class="open-tab" href="#googleBusinessHelpPage">Help tab</a>.</p>
                
                
            </div>
        
        <?php 
        
                } 
            }
        
        ?>
        
        <!--notice message-->
        <?php 
  
        $accessToken = wp_google_my_business_auto_publish_get_access_token();
        
        if( get_option('wp_google_my_business_auto_publish_settings') ){

            $pluginSettings = get_option('wp_google_my_business_auto_publish_settings');
            $locationSelection = $pluginSettings['wp_google_my_business_auto_publish_location_selection'];
            $accountSelection = $pluginSettings['wp_google_my_business_auto_publish_account_selection'];
            
                
            if(strlen($locationSelection) > 0 && strlen($accountSelection) > 0 && $accessToken !== 'ERROR'){
        
        ?>
        
        
            <div data-dismissible="disable-done-notice-forever" class="notice notice-success is-dismissible">
            <p><h3><?php  _e('Current Authentication Status', 'wp-google-my-business-auto-publish' ); ?></h3><p><?php  _e('You are currently authenticated and you have selected an account and location to share to.', 'wp-google-my-business-auto-publish' ); ?></p>       
            </div>
            
            <?php } elseif(( strlen($locationSelection) < 1 || strlen($accountSelection) < 1) && $accessToken !== 'ERROR') { ?>  
            
            <div data-dismissible="disable-done-notice-forever" class="notice notice-warning is-dismissible">
            <p><h3><?php  _e('Current Authentication Status', 'wp-google-my-business-auto-publish' ); ?></h3><p><?php  _e('You are currently authenticated but you have not selected both an <a class="open-tab" href="#googleAccountSelect">Account Select</a> and/or <a class="open-tab" href="#googleLocationSelect">Location Select</a> yet.', 'wp-google-my-business-auto-publish' ); ?></p>       
            </div>
            
            <?php } else { ?>  
            
            <div data-dismissible="disable-done-notice-forever" class="notice notice-error is-dismissible">
            <p><h3><?php  _e('Current Authentication Status', 'wp-google-my-business-auto-publish' ); ?></h3><p><?php  _e('You are not currently authenticated please go to the <a class="open-tab" href="#googleConnect">Connect</a> tab.', 'wp-google-my-business-auto-publish' ); ?></p>       
            </div>
        
        <?php 
                }
            } 
            
        ?> 


        <!--start form-->
        <form data="<?php echo esc_attr(wp_google_my_business_auto_publish_get_access_token()); ?>" id='google_my_business_auto_publish_settings_form' data-nonce-accounts="<?php echo wp_create_nonce( 'wp_google_my_business_auto_publish_accounts' )?>" data-nonce-locations="<?php echo wp_create_nonce( 'wp_google_my_business_auto_publish_locations' )?>" data-nonce-save-auth-details="<?php echo wp_create_nonce( 'save_authentication_details' )?>" action='options.php' method='post'>
            <!--start tabs-->
            <div id="tabs" class="nav-tab-wrapper"> 
                <ul class="tab-titles">
                    <li><a class="nav-tab" href="#googleConnect"><i class="fa fa-lock" aria-hidden="true"></i> <?php _e('Connect', 'wp-google-my-business-auto-publish' ); ?></a></li>
                    
                    <li><a class="nav-tab" href="#googleAccountSelect"><i class="fa fa-user-circle-o" aria-hidden="true"></i> <?php _e('Account Select', 'wp-google-my-business-auto-publish' ); ?></a></li>
                    
                    <li><a class="nav-tab" href="#googleLocationSelect"><i class="fa fa-map-marker" aria-hidden="true"></i> <?php _e('Location Select', 'wp-google-my-business-auto-publish' ); ?></a></li>
                    
                    <li><a class="nav-tab" href="#googleBusinessSharingOptionsPage"><i class="fa fa-share-alt" aria-hidden="true"></i> <?php  _e('Sharing Options', 'wp-google-my-business-auto-publish' ); ?></a></li>
                    
                    <li><a class="nav-tab" href="#googleBusinessAdditionalOptionsPage"><i class="fa fa-cogs" aria-hidden="true"></i> <?php  _e('Additional Options', 'wp-google-my-business-auto-publish' ); ?></a></li>
                    
                    <li><a class="nav-tab" href="#googleBusinessPostNow"><i class="fa fa-paper-plane" aria-hidden="true"></i> <?php  _e('Post Now', 'wp-google-my-business-auto-publish' ); ?></a></li>
                    
                    <li><a class="nav-tab" href="#googleBusinessManagePosts"><i class="fa fa-thumb-tack" aria-hidden="true"></i> <?php  _e('Manage Posts', 'wp-google-my-business-auto-publish' ); ?></a></li>

                    <li><a class="nav-tab" href="#googleBusinessReviews"><i class="fa fa-commenting" aria-hidden="true"></i> <?php  _e('Reviews', 'wp-google-my-business-auto-publish' ); ?></a></li>
                    
                    <li><a class="nav-tab" href="#googleBusinessHelpPage"><i class="fa fa-question" aria-hidden="true"></i> <?php _e('Help', 'wp-google-my-business-auto-publish' ); ?></a></li>

                    
                </ul>

                <!--add settings pages-->
                <?php 

                wp_google_my_business_auto_publish_tab_content('googleConnect'); 
                
                wp_google_my_business_auto_publish_tab_content('googleAccountSelect');
                
                wp_google_my_business_auto_publish_tab_content('googleLocationSelect');

                wp_google_my_business_auto_publish_tab_content('googleBusinessSharingOptionsPage');

                wp_google_my_business_auto_publish_tab_content('googleBusinessAdditionalOptionsPage');
                
                wp_google_my_business_auto_publish_tab_content('googleBusinessPostNow');
                
                wp_google_my_business_auto_publish_tab_content('googleBusinessManagePosts');

                wp_google_my_business_auto_publish_tab_content('googleBusinessReviews');

                wp_google_my_business_auto_publish_tab_content('googleBusinessHelpPage');

                ?>

            </div> <!--end tabs div-->     
        </form>
        <!--ad-->
        <?php

            require('nbw.php');  
            
            echo northernbeacheswebsites_information();

        ?>

    </div>
</div>