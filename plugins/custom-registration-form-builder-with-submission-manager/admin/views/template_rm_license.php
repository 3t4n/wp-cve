<?php
$license = new RM_Licensing();
$path       =  plugin_dir_url( __FILE__ );
$identifier = 'SETTINGS';


$rm_premium_license_key = get_option( 'rm_premium_license_key','' );
    $rm_premium_license_status = get_option( 'rm_premium_license_status', '' );
    $rm_premium_license_response = get_option( 'rm_premium_license_response', '' );
    $is_any_ext_activated = $license->rm_get_activate_extensions();
     $disabled = '';
     $disabled2 = '';
     $premium_not_found_notice = 0;
  if(!defined('REGMAGIC_ADDON') && empty($rm_premium_license_key)){
      $disabled = 'disabled="disabled"';
      $disabled2 = 'disabled="disabled"';
      $premium_not_found_notice = 1;
  }
  
  if(defined('REGMAGIC_ADDON') && empty($rm_premium_license_key)){
      $disabled = '';
      $disabled2 = 'disabled="disabled"';
      $premium_not_found_notice = 0;
  }
  

 
 ?>




    <form name="rm_license_settings" class="rm-setting-table-main" id="rm_license_settings" method="post">
    <!-----Dialogue Box Starts----->

      <h2 class="rm-setting-tab-content">
        <?php esc_html_e( 'License Settings', 'custom-registration-form-builder-with-submission-manager' ); ?>
      </h2>
    
      <p><strong>Read about activating licenses <a target="_blank" href="https://registrationmagic.com/how-to-activate-registrationmagic-license">here</a></strong></p>

      <div class="wrap">
      <div class="tablenav top">
          <div class="alignleft actions rm-ml-2">
              <a href="admin.php?page=rm_options_manage" class="page-title-action"> <?php esc_html_e( 'Back', 'custom-registration-form-builder-with-submission-manager' ); ?></a>
           
          </div>
          
          <div class="alignright actions rm-ml-2">
                 <a href="#" class="rm-remove-license-btn rm-ml-2 rm-fw-bold"> <!--<i class="fa fa-spinner fa-spin " style="display: none"></i> --> <?php esc_html_e( 'Remove License', 'custom-registration-form-builder-with-submission-manager' ); ?></a>
          </div>
      </div>
          </div>  
      
            <table class="form-table">
                <tbody>
                    <tr>
                        <td class="rm-form-table-wrapper" colspan="2">
                            <table class="rm-form-table-setting rm-setting-table widefat">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e( 'Product', 'custom-registration-form-builder-with-submission-manager' );?></th>
                                        <th><?php esc_html_e( 'License Key', 'custom-registration-form-builder-with-submission-manager' );?></th>
                                        <th class="rm-text-center"><?php esc_html_e( 'Validity', 'custom-registration-form-builder-with-submission-manager' );?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($premium_not_found_notice==1):?>
                                    <tr>
                                        <td colspan="4"> 
                                            
                                            <!-- Premium is not installed || No Premium key has been previously saved --->
                                            
                                            <div class="rm_admin_notice_banner rm-notice-banner notice notice-error inline rm-py-2 rm-my-2">
                                                <p>
                                                    <strong><?php esc_html_e( 'RegistrationMagic Premium not found!', 'custom-registration-form-builder-with-submission-manager' );?></strong> <br>
                                                    <?php echo esc_html__( 'If already purchased, please visit ', 'custom-registration-form-builder-with-submission-manager' ).'<a href="https://registrationmagic.com/checkout/order-history/" target="_blank">'.esc_html__('your orders page ','custom-registration-form-builder-with-submission-manager').'</a>'. esc_html__( 'to download the latest plugin zip file.', 'custom-registration-form-builder-with-submission-manager' ); ?>
                                                </p>
                                            </div>
                                            
                                             <!-- Ends--->
                                            
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr style="display: none">
                                        <td colspan="4"> 
                                        
                                        </td>
                                    </tr>
                                 
                                 
                                    <?php if( isset( $is_any_ext_activated ) && !empty($is_any_ext_activated ) ) {
                                        foreach($is_any_ext_activated as $key=>$product):
                                        if(empty($product) || $product[0]=='')
                                        {
                                            continue;
                                        }
                                        //echo $key;die;
                                          $id = $key.'_license_key';
                                          $response = $key.'_license_response';
                                          $status = $key.'_license_status';
                                          $rm_license_key = get_option($id,'' );
                                          $rm_license_response = get_option($response,'' );
                                          $rm_license_status = get_option($status,'' );
                                          $deactivate_license_btn = $key.'_license_deactivate';
                                          $activate_license_btn = $key.'_license_activate';
                                          $bundle_id = get_option($key.'_license_id','' );
                                          //echo $rm_license_status;die;
                                          if ( isset( $rm_license_response->error ) && $rm_license_response->error != '') {
                                              $expire_class = 'rm-license-expired';
                                          }
                                          else
                                          {
                                              $expire_class = '';
                                          }
                                        ?>
                                    
                                            <tr valign="top" class="<?php esc_attr_e($key);?>">
                                            <td>
                                            <div class="rm-purchase-selector">
                                                <select onchange="rm_on_change_bundle(this.value)" <?php echo esc_attr($disabled);?>>
                                                <option value=""> <?php esc_html_e( 'Select Product', 'custom-registration-form-builder-with-submission-manager' );?></option>
                                                <option value="55382" <?php selected('55382',$bundle_id); ?>><?php esc_html_e('RegistrationMagic Premium','custom-registration-form-builder-with-submission-manager');?></option>
                                                <option value="55393" <?php selected('55393',$bundle_id); ?>><?php esc_html_e('RegistrationMagic Premium +','custom-registration-form-builder-with-submission-manager');?></option>
                                                <option value="55406" <?php selected('55406',$bundle_id); ?>><?php esc_html_e('MetaBundle','custom-registration-form-builder-with-submission-manager');?></option>
                                                <option value="55407" <?php selected('55407',$bundle_id); ?>><?php esc_html_e('MetaBundle+','custom-registration-form-builder-with-submission-manager');?></option>
                                                <option value="55414" <?php selected('55414',$bundle_id); ?>><?php esc_html_e('Premium Subscription','custom-registration-form-builder-with-submission-manager');?></option>
                                                <option value="55394" <?php selected('55394',$bundle_id); ?>><?php esc_html_e('Premium Subscription 1 Year','custom-registration-form-builder-with-submission-manager');?></option>
                                           
                                            </select>

                                              <!--  <span class="rm-tooltips" tooltip="<?php esc_html_e( 'If you have purchased a Bundle, please select the name of the Bundle and enter its license key in the corresponding input box', 'custom-registration-form-builder-with-submission-manager' );?>" tooltip-position="top"></span>-->
                                                <div class="rm-mt-1 rm-align-right rm-fw-bold"><a href="https://registrationmagic.com/how-to-activate-registrationmagic-licenses/" target="_blank" class="rm-text-small rm-text-decoration-underline"><?php esc_html_e('How to find the product I purchased?','custom-registration-form-builder-with-submission-manager');?></a></div>
                                            </div>
                                            </td>
                    <td>
                        <input id="<?php esc_attr_e($id);?>" name="<?php esc_attr_e($id);?>" type="text" class="rm-license-checker-box regular-text rm-license-block <?php echo $expire_class;?>" data-prefix="<?php esc_attr_e($bundle_id);?>" data-key="<?php esc_attr_e($key);?>" value="<?php esc_attr_e($rm_license_key); ?>" placeholder="<?php esc_html_e( 'Please Enter License Key', 'custom-registration-form-builder-with-submission-manager' );?>" <?php echo esc_attr($disabled2);?> />
                        <div class="rm-mt-1 rm-align-right"><a href="https://registrationmagic.com/get-registrationmagic-premium-license-key/" target="_blank" class="rm-text-small rm-fw-bold rm-text-decoration-underline"><?php esc_html_e('Where do I find my key?','custom-registration-form-builder-with-submission-manager');?></a></div>
                      </td>
                      <td class="rm-text-center">         
                        <div class="rm-license-validity-notice license-expire-date <?php echo $expire_class;?>" style="padding-bottom:2rem;" >
                            <?php
                            /*if ( ! empty( $rm_license_response->expires ) && ! empty( $rm_license_status ) && $rm_license_status == 'valid' ) {
                                if( $rm_license_response->expires == 'lifetime' ){
                                    echo '<div class="rm-license-validity-note rm-license-not-active rm-text-success"> <span class="rm-d-flex rm-justify-content-center rm-text-start"> <span class="material-icons rm-mr-2">verified</span><span>'.esc_html__( 'Lifetime Validity.', 'custom-registration-form-builder-with-submission-manager').' <br/> <strong>'.esc_html__('Receiving Updates','custom-registration-form-builder-with-submission-manager').'</strong></span></span></div>';
                                  
                                }else{
                                    echo '<div class="rm-license-validity-note rm-license-not-active rm-text-success"> <span class="rm-d-flex rm-justify-content-center rm-text-start"> <span class="material-icons rm-mr-2">verified</span><span>'.sprintf( __( 'Valid until %s.', 'custom-registration-form-builder-with-submission-manager' ), date( 'F d, Y', strtotime($rm_license_response->expires) ) ).' <br/> <strong>'.esc_html__('Receiving Updates','custom-registration-form-builder-with-submission-manager').'</strong></span></span></div>';
                                   // echo sprintf( __('Your License Key expires on %s', 'custom-registration-form-builder-with-submission-manager' ), date( 'F d, Y', strtotime( $rm_license_response->expires ) ) );
                                }
                            } elseif ( isset( $rm_license_response->expires ) && ! empty( $rm_license_response->expires) && !empty(strtotime( $rm_license_response->expires )) && !is_numeric($rm_license_response->expires)) {
                                echo '<div class="rm-license-validity-note rm-license-expired rm-text-danger"> <span class="rm-d-flex rm-justify-content-center rm-text-start"> <span class="material-icons rm-mr-2">error</span><span>'.sprintf( __( 'Expired on %s.', 'custom-registration-form-builder-with-submission-manager' ), date( 'F d, Y', strtotime($rm_license_response->expires) ) ).'<br/> <strong>'.esc_html__('Not Receiving Updates','custom-registration-form-builder-with-submission-manager').'</strong></span></span></div>';    
                            }
                            elseif(!empty($rm_premium_license_response) && isset($rm_premium_license_response->license) && $rm_premium_license_response->license == 'invalid' && !empty($rm_premium_license_key))
                            {
                                echo '<div class="rm-license-validity-note rm-license-invalid rm-text-danger"> <span class="rm-d-flex rm-justify-content-center rm-text-start"> <span class="material-icons rm-mr-2">error</span><span>'.esc_html__('Invalid license key. Please recheck selected product and license key and try again.','custom-registration-form-builder-with-submission-manager').'</span></span></div>';
                            }
                            elseif(defined('REGMAGIC_ADDON') && !empty($rm_premium_license_key)){
                                echo '<div class="rm-license-validity-note rm-license-invalid"> <span class="rm-d-flex rm-justify-content-center rm-text-start"> <span class="material-icons rm-mr-2">timer</span><span>'.esc_html__('Unable to check due to possible server issue. Will check again automatically after sometime. Meanwhile, you have full access to all premium features.','custom-registration-form-builder-with-submission-manager').'</span></span></div>';
                            }
                            ?>
                            
                            <?php if(!defined('REGMAGIC_ADDON') && empty($rm_premium_license_key)): 
                        echo sprintf('<span class="rm-license-validity-notice rm-license-not-validity rm-license-not-active">%s</span>',esc_html__('Enter your license key and click', 'custom-registration-form-builder-with-submission-manager') .'<br/>'. ' <strong>' . esc_html__('Activate', 'custom-registration-form-builder-with-submission-manager') . '</strong> ' . esc_html__('to check its validity.', 'custom-registration-form-builder-with-submission-manager'));
                        endif;?>
                          
                        <?php if(defined('REGMAGIC_ADDON') && empty($rm_premium_license_key)): 
                           echo sprintf('<span class="rm-license-validity-notice rm-license-not-validity rm-license-not-active">%s</span>',esc_html__('Select purchased product and enter your license key. Then click ', 'custom-registration-form-builder-with-submission-manager') . ' <strong>' . esc_html__('Activate', 'custom-registration-form-builder-with-submission-manager') . '</strong> ' . esc_html__('to check its validity.', 'custom-registration-form-builder-with-submission-manager'));
                        endif; */
                        // new messages 
                        if(isset($rm_premium_license_response) && !empty($rm_premium_license_response))
                        {
                            if( isset( $rm_premium_license_response->expires ) && ! empty( $rm_premium_license_response->expires ) ) 
                            {
                                if($rm_premium_license_response->license=='valid')
                                {
                                    if($rm_premium_license_response->expires == 'lifetime')
                                    {
                                        echo '<div class="rm-license-validity-note rm-license-not-active rm-text-success"> <span class="rm-d-flex rm-justify-content-center rm-text-start"> <span class="material-icons rm-mr-2">verified</span><span>'.esc_html__( 'Lifetime Validity.', 'custom-registration-form-builder-with-submission-manager').'<br/> <strong>'.esc_html__('Receiving Updates','custom-registration-form-builder-with-submission-manager').'</strong></span></span></div>';
                                    }
                                    else
                                    {
                                         echo '<div class="rm-license-validity-note rm-license-not-active rm-text-success"> <span class="rm-d-flex rm-justify-content-center rm-text-start"> <span class="material-icons rm-mr-2">verified</span><span>'.sprintf( __( 'Valid until %s.', 'custom-registration-form-builder-with-submission-manager' ), date( 'F d, Y', strtotime($rm_premium_license_response->expires) ) ).'<br/> <strong>'.esc_html__('Receiving Updates','custom-registration-form-builder-with-submission-manager').'</strong></span></span></div>';  
                                    }                    
                                }
                                else
                                {
                                    if(isset( $rm_premium_license_response->error ) && $rm_premium_license_response->error == 'expired')
                                    {
                                        echo '<div class="rm-license-validity-note rm-license-expired rm-text-danger"> <span class="rm-d-flex rm-justify-content-center rm-text-start"> <span class="material-icons rm-mr-2">error</span><span>'.sprintf( __( 'Expired on %s.', 'custom-registration-form-builder-with-submission-manager' ), date( 'F d, Y', strtotime($rm_premium_license_response->expires) ) ).'<br/> <strong>'.esc_html__('Not Receiving Updates','custom-registration-form-builder-with-submission-manager').'</strong></span></span></div>';
                                    }
                                    else if(isset( $rm_premium_license_response->error ) && $rm_premium_license_response->error == 'no_activations_left')
                                    {
                                        if($rm_premium_license_response->expires == 'lifetime')
                                        {
                                            echo '<div class="rm-license-validity-note rm-license-expired rm-text-danger"> <span class="rm-d-flex rm-justify-content-center rm-text-start"> <span class="material-icons rm-mr-2">error</span><span>'.esc_html__( 'You have reached Maximum', 'custom-registration-form-builder-with-submission-manager' ).'<br/> <strong>'.esc_html__('activation limit ','custom-registration-form-builder-with-submission-manager').'</strong>'.esc_html__('for this license key under our fair usage policy.','custom-registration-form-builder-with-submission-manager').'<span class="rm-my-2 rm-d-inline-block">'.esc_html__('Please contact support if you wish to increase your activation limit.','custom-registration-form-builder-with-submission-manager').'</span>'.esc_html__('You can easily manage activation from your account.','custom-registration-form-builder-with-submission-manager').'</span></span></div>';
                                        }
                                        else
                                        {
                                            echo '<div class="rm-license-validity-note rm-license-expired rm-text-danger"> <span class="rm-d-flex rm-justify-content-center rm-text-start"> <span class="material-icons rm-mr-2">error</span><span>'.esc_html__( 'You have reached Maximum', 'custom-registration-form-builder-with-submission-manager' ).'<br/> <strong>'.esc_html__('activation limit ','custom-registration-form-builder-with-submission-manager').'</strong>'.esc_html__('for this license key.','custom-registration-form-builder-with-submission-manager').'<span class="rm-my-2 rm-d-inline-block">'.esc_html__('Please deactivate it elsewhere or upgrade license to use it on this site.','custom-registration-form-builder-with-submission-manager').'</span>'.esc_html__('You can easily manage activation from your account.','custom-registration-form-builder-with-submission-manager').'</span></span></div>';
                                        }
                                    }
                                    else
                                    {
                                       echo '<div class="rm-license-validity-note rm-license-invalid rm-text-danger"> <span class="rm-d-flex rm-justify-content-center rm-text-start"> <span class="material-icons rm-mr-2">error</span><span>'.esc_html__('Invalid license key. Please recheck selected product and license key and try again.','custom-registration-form-builder-with-submission-manager').'</span></span></div>';
                                    }
                                }
                            }
                            elseif($rm_premium_license_response->license == 'invalid')
                            {
                                echo '<div class="rm-license-validity-note rm-license-invalid rm-text-danger"> <span class="rm-d-flex rm-justify-content-center rm-text-start"> <span class="material-icons rm-mr-2">error</span><span>'.esc_html__('Invalid license key. Please recheck selected product and license key and try again.','custom-registration-form-builder-with-submission-manager').'</span></span></div>';
                            }
                            else
                            {
                                echo '<div class="rm-license-validity-note rm-license-invalid"> <span class="rm-d-flex"> <span class="material-icons rm-mr-2">timer</span><span class="rm-text-start">'.esc_html__('Unable to check due to possible server issue. Will check again automatically after sometime. Meanwhile, you have full access to all premium features.','custom-registration-form-builder-with-submission-manager').'</span></span></div>';
                            }
                        }
                        else 
                        {
                            if(!defined('REGMAGIC_ADDON') && empty($rm_premium_license_key)): 
                                echo sprintf('<span class="rm-license-validity-notice rm-license-not-validity rm-license-not-active">%s</span>',esc_html__('Enter your license key and click', 'custom-registration-form-builder-with-submission-manager') . ' <strong>' . esc_html__('Activate', 'custom-registration-form-builder-with-submission-manager') . '</strong> ' . esc_html__('to check its validity.', 'custom-registration-form-builder-with-submission-manager'));
                            endif;
                          
                            if(defined('REGMAGIC_ADDON') && empty($rm_premium_license_key)): 
                                echo sprintf('<span class="rm-license-validity-notice rm-license-not-validity rm-license-not-active">%s</span>',esc_html__('Select purchased product and enter your license key. Then click ', 'custom-registration-form-builder-with-submission-manager') . ' <strong>' . esc_html__('Activate', 'custom-registration-form-builder-with-submission-manager') . '</strong> ' . esc_html__('to check its validity.', 'custom-registration-form-builder-with-submission-manager'));
                            endif;
                        }
                        ?>  
                            
                            
                            
                            
                            
                        </div>
                        
                        
                        
                          
                    </td>
                    <td>
                        
                        <!-- Premium is not installed || No Premium key has been previously saved --->
                        <span class="<?php esc_attr_e($key);?>-license-status-block">
                            <?php 
                            if(empty($rm_license_status) && empty($rm_premium_license_response) ){?>
                            <button type="button" class="button button-primary rm_license_activate rm-license-active-button action rm_my-2" <?php echo esc_attr($disabled2);?> name="<?php esc_attr_e($activate_license_btn);?>" id="<?php esc_attr_e($activate_license_btn);?>" data-prefix="<?php esc_attr_e($bundle_id); ?>" data-key="<?php esc_attr_e($key);?>"><?php esc_html_e( 'Activate', 'custom-registration-form-builder-with-submission-manager' );?></button>
                            <button type="button" class="button   action rm-ml-2" ><a href="https://registrationmagic.com/comparison/" target="_blank">Buy License</a></button>
                            <?php
                            }
                            else if(!empty( $rm_license_status ) && $rm_license_status == 'valid' &&  $rm_license_response->expires != 'lifetime')
                            {?>
                                <a href="https://registrationmagic.com/renew-registrationmagic-premium-license-key/?key=<?php echo $rm_premium_license_key;?>&download_id=<?php echo $bundle_id;?>" target="_blank"><button type="button" class="button button-primary rm-license-active-button action rm_my-2" name="rm_renew_button" id="rm_renew_button"><?php esc_html_e('Renew','custom-registration-form-builder-with-submission-manager');?></button></a>
                                <button type="button" class="button action rm-ml-2" ><a href="https://registrationmagic.com/renew-registrationmagic-premium-license-key/?key=<?php echo $rm_premium_license_key;?>&download_id=<?php echo $bundle_id;?>" target="_blank"><?php esc_html_e('Upgrade','custom-registration-form-builder-with-submission-manager');?></a></button>
                            <?php
                            }
                            else if(!empty( $rm_license_status ) && $rm_license_status == 'valid' &&  $rm_license_response->expires == 'lifetime')
                            {
                                
                            }
                            else if(!empty( $rm_premium_license_response ) && isset($rm_premium_license_response->error) && $rm_premium_license_response->error == 'expired'){?>
                                 <button type="button" class="button button-primary rm_license_activate rm-license-active-button action rm_my-2" name="<?php esc_attr_e($activate_license_btn);?>" id="<?php esc_attr_e($activate_license_btn);?>" data-prefix="<?php esc_attr_e($bundle_id); ?>" data-key="<?php esc_attr_e($key);?>"><?php esc_html_e( 'Activate', 'custom-registration-form-builder-with-submission-manager' );?></button>
                                <a href="https://registrationmagic.com/renew-registrationmagic-premium-license-key/?key=<?php echo $rm_premium_license_key;?>&download_id=<?php echo $bundle_id;?>" target="_blank"><button type="button" class="button action rm-ml-2" ><?php esc_html_e('Renew','custom-registration-form-builder-with-submission-manager');?></button>  </a>  
                            <?php }
                            else if(isset( $rm_premium_license_response->error ) && $rm_premium_license_response->error == 'no_activations_left' && $rm_premium_license_response->expires != 'lifetime'){
                                ?>
                                 <button type="button" class="button button-primary rm_license_activate rm-license-active-button action rm_my-2" name="<?php esc_attr_e($activate_license_btn);?>" id="<?php esc_attr_e($activate_license_btn);?>" data-prefix="<?php esc_attr_e($bundle_id); ?>" data-key="<?php esc_attr_e($key);?>"><?php esc_html_e( 'Activate', 'custom-registration-form-builder-with-submission-manager' );?></button>
                                 <a href="https://registrationmagic.com/renew-registrationmagic-premium-license-key/?key=<?php echo $rm_premium_license_key;?>&download_id=<?php echo $bundle_id;?>" target="_blank"><button type="button" class="button action rm-ml-2" ><?php esc_html_e('Upgrade','custom-registration-form-builder-with-submission-manager');?></button></a>
                            <?php 
                            }
                            else if(isset( $rm_premium_license_response->error ) && $rm_premium_license_response->error == 'no_activations_left' && $rm_premium_license_response->expires == 'lifetime'){
                                ?>
                                 <button type="button" class="button button-primary rm_license_activate rm-license-active-button action rm_my-2" name="<?php esc_attr_e($activate_license_btn);?>" id="<?php esc_attr_e($activate_license_btn);?>" data-prefix="<?php esc_attr_e($bundle_id); ?>" data-key="<?php esc_attr_e($key);?>"><?php esc_html_e( 'Activate', 'custom-registration-form-builder-with-submission-manager' );?></button>
                                 <a href="https://registrationmagic.com/technical-support/" target="_blank"><button type="button" class="button action rm-ml-2" ><?php esc_html_e('Support','custom-registration-form-builder-with-submission-manager');?></button></a>
                            <?php 
                            }
                            else{
                                ?>
                                 <button type="button" class="button button-primary rm_license_activate rm-license-active-button action rm_my-2" <?php echo esc_attr($disabled2);?> name="<?php esc_attr_e($activate_license_btn);?>" id="<?php esc_attr_e($activate_license_btn);?>" data-prefix="<?php esc_attr_e($bundle_id); ?>" data-key="<?php esc_attr_e($key);?>"><?php esc_html_e( 'Activate', 'custom-registration-form-builder-with-submission-manager' );?></button>
                            <a href="https://registrationmagic.com/comparison/" target="_blank"><button type="button" class="button   action rm-ml-2" >Buy License</button></a>
                            <?php 
                            
                            }
                            ?>
                        </span>
                    </td>
                </tr>
                                    <?php endforeach; } ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
  </form>


        <div class="rm-status-update-model rm-status-success-model" id="rm-extension-license-status">
            <div class="rm-notification-overlay"></div>
            <div class="rm-modal-wrap-toast">
                <div class="rm-modal-container rm-dbfl">
                    <div class="rm-status-close" onclick="rm_close_toast()">×</div>
                    <div class="rm-pdbfl rm-status-box-row rm-status-update-body" id="rm-extension-license-message">
                   
                    </div>
                </div>
            </div>
        </div>

  