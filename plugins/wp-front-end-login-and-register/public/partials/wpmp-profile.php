<div id="preloader" style="position: fixed;left: 0px;top: 0px;z-index: 999;opacity: 1.0;width: 100%;height: 100%;overflow: visible;background: url(<?php echo site_url(); ?>/wp-content/themes/price/img/ajax-loader.gif) center center no-repeat #d9edf7ed; display: none;"></div>
<div class="col-md-3">
   <div class="user_img2">
      <div class="u_pro">
         <?php 
            $current_user = wp_get_current_user();
            $profile_pic = get_user_meta($current_user->ID,'wpmp_profile_pic',true);
            
            if(isset($profile_pic) && $profile_pic !=''){
              $url = $profile_pic;
            }else{
              $url = "https://cdn5.vectorstock.com/i/1000x1000/23/54/user-icon-man-human-profile-avatar-vector-10552354.jpg";
            }
            ?>
         <center><img alt="100%x200" src="<?php echo $url; ?>" data-holder-rendered="true" style="border-radius: 50%; height: 130px; width: 130px; display: block;"></center>
         <br>
         <small class="ttl_name1"><?php echo $current_user->first_name.'  '.$current_user->last_name; ?></small> 
         <br>
         <small class="ttl_name"><?php echo $current_user->user_email ; ?></small>   
         <br>         
         <small class="ttl_name"><?php echo $current_user->user_login  ; ?></small>            
         <br>
         <a href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a>
      </div>
   </div>
</div>
<div class="col-md-9">
   <div class="asdf">
<ul class="nav nav-tabs text_color2">
         <li class="active"><a data-toggle="tab" href="#home">Profile</a></li>
      </ul>
      <div class="tab-content register_r">
         <div id="home" class="tab-pane fade in active">
            <br>           
            <form name="wpmpProfileForm" id="wpmpProfileForm" method="post" enctype="multipart/form-data">
               <div id="wpmp-profile-loader-info" class="wpmp-loader" style="display:none;">
                  <img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
                  <span><?php _e('Please wait ...', $this->plugin_name); ?></span>
               </div>
               <div id="wpmp-profile-alert" class="alert alert-danger" role="alert" style="display:none;"></div>
               <div class="form-group">
                  <label for="firstname"><?php _e('First name', $this->plugin_name); ?></label>
                  <sup class="wpmp-required-asterisk">*</sup>
                  <input type="text" class="form-control re_register" name="wpmp_fname" id="wpmp_fname" value="<?php echo $current_user->first_name; ?>">
               </div>
               <div class="form-group">
                  <label for="lastname"><?php _e('Last name', $this->plugin_name); ?></label>
                  <input type="text" class="form-control re_register" name="wpmp_lname" id="wpmp_lname" value="<?php echo $current_user->last_name; ?>">
               </div>
               <div class="form-group">
                  <label for="email"><?php _e('Email', $this->plugin_name); ?></label>
                  <sup class="wpmp-required-asterisk">*</sup>
                  <input type="text" class="form-control re_register" name="wpmp_email" id="wpmp_email" value="<?php echo $current_user->user_email; ?>">
               </div>
               <div class="form-group">
                  <label for="profile_pic"><?php _e('Profile Image', $this->plugin_name); ?></label>
                  <sup class="wpmp-required-asterisk">*</sup>
                  <input type="file" class="form-control re_register" name="wpmp_profile_pic" id="wpmp_profile_pic">
               </div>
               <?php
                  // this prevent automated script for unwanted spam
                  if (function_exists('wp_nonce_field'))
                      wp_nonce_field('wpmp_profile_action', 'wpmp_profile_nonce');
                  
                  ?>
               <button type="submit" class="btn btn-primary re_submit"><?php _e("Submit") ?></button>
            </form>
         </div>
      </div>   
   </div>
   <!---/user_bg_img-->
</div>