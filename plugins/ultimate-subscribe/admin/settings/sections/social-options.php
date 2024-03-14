<?php 
$options            = get_option('ultimate_subscribe_options');

$social_enable      = isset($options['social_enable'])?$options['social_enable']:0;
$social_new_tab     = isset($options['social_new_tab'])?$options['social_new_tab']:0;

$socials            = isset($options['socials'])?$options['socials']:array();
$facebook_url       = isset($socials['facebook']['url'])?$socials['facebook']['url']:'';
$twitter_url        = isset($socials['twitter']['url'])?$socials['twitter']['url']:'';
$google_url         = isset($socials['google']['url'])?$socials['google']['url']:'';
$youtube_url        = isset($socials['youtube']['url'])?$socials['youtube']['url']:'';
$instagram_url      = isset($socials['instagram']['url'])?$socials['instagram']['url']:'';



$facebook_icon      = !empty($socials['facebook']['icon'])?$socials['facebook']['icon']:'fa fa-facebook';
$twitter_icon       = !empty($socials['twitter']['icon'])?$socials['twitter']['icon']:'fa fa-twitter';
$google_icon        = !empty($socials['google']['icon'])?$socials['google']['icon']:'fa fa-google-plus';
$youtube_icon       = !empty($socials['youtube']['icon'])?$socials['youtube']['icon']:'fa fa-youtube';
$instagram_icon     = !empty($socials['instagram']['icon'])?$socials['instagram']['icon']:'fa fa-instagram';
?>
<div id="social-options" class="tab-pane">
    <h3> <?php esc_html_e('Social Options', 'ultimate-subscribe'); ?> </h3>
    <div class="form-fieldset">
            <!-- <div class="field-group"> -->
            <div class="field-row">
                <div class="field-label"> <?php _e('Enable Form Social', 'ultimate-subscribe'); ?> </div>
                <div class="field-data"> 
                    <div class="checkbox">  
                        <input type="checkbox" value="1" id="social_enable" name="ultimate_subscribe_options[social_enable]" <?php checked($social_enable, 1, true); ?>/>
                        <label for="social_enable"></label>
                    </div>
                </div>
            </div>
            <div class="field-row">
                <div class="field-label"> <?php _e('Open As New Tab', 'ultimate-subscribe'); ?> </div>
                <div class="field-data"> 
                    <div class="checkbox">  
                        <input type="checkbox" value="1" id="social_new_tab" name="ultimate_subscribe_options[social_new_tab]" <?php checked($social_new_tab, 1, true); ?>/>
                        <label for="social_new_tab"></label>
                    </div>
                </div>
            </div>
            <div class="field-row">
                <span class="descricption"> <?php _e('Select icon class to change icon from FontAwesome', 'ultimate-subscribe'); ?> <a href="http://fontawesome.io/icons/" rel="nofollow" target="_blank"><?php _e('Click Here', 'ultimate-subscribe'); ?></a></span>
            </div>
            <div class="field-group">
                <div class="field-row">
                    <div class="field-label"> <?php _e('Facebook URL', 'ultimate-subscribe'); ?> </div>
                    <div class="field-data"> <input type="text" class="input-field" name="ultimate_subscribe_options[socials][facebook][url]" value="<?php echo esc_url($facebook_url); ?>"> </div>
                </div>
                <div class="field-row"> 
                    <div class="field-label"> <?php _e('Facebook Icon', 'ultimate-subscribe'); ?> </div>
                    <div class="field-data">
                        <input type="text" class="input-field" name="ultimate_subscribe_options[socials][facebook][icon]" value="<?php echo esc_attr($facebook_icon); ?>">
                    </div>
                </div>
            </div>
            <div class="field-group">
                <div class="field-row">
                    <div class="field-label"> <?php _e('Twitter URL', 'ultimate-subscribe'); ?> </div>
                    <div class="field-data"> <input type="text" class="input-field" name="ultimate_subscribe_options[socials][twitter][url]" value="<?php echo esc_url($twitter_url); ?>"> </div>
                </div>
                <div class="field-row"> 
                    <div class="field-label"> <?php _e('Twitter Icon', 'ultimate-subscribe'); ?> </div>
                    <div class="field-data">
                        <input type="text" class="input-field" name="ultimate_subscribe_options[socials][twitter][icon]" value="<?php echo esc_attr($twitter_icon); ?>">
                    </div>
                </div>
            </div>
            <div class="field-group">
                <div class="field-row">
                    <div class="field-label"> <?php _e('Google URL', 'ultimate-subscribe'); ?> </div>
                    <div class="field-data"> <input type="text" class="input-field" name="ultimate_subscribe_options[socials][google][url]" value="<?php echo esc_url($google_url); ?>"> </div>
                </div>
                <div class="field-row"> 
                    <div class="field-label"> <?php _e('Google Icon', 'ultimate-subscribe'); ?> </div>
                    <div class="field-data">
                        <input type="text" class="input-field" name="ultimate_subscribe_options[socials][google][icon]" value="<?php echo esc_attr($google_icon); ?>">
                    </div>
                </div>
            </div>
            <div class="field-group">
                <div class="field-row">
                    <div class="field-label"> <?php _e('Youtube URL', 'ultimate-subscribe'); ?> </div>
                    <div class="field-data"> <input type="text" class="input-field" name="ultimate_subscribe_options[socials][youtube][url]" value="<?php echo esc_url($youtube_url); ?>"> </div>
                </div>
                <div class="field-row"> 
                    <div class="field-label"> <?php _e('Youtube Icon', 'ultimate-subscribe'); ?> </div>
                    <div class="field-data">
                        <input type="text" class="input-field" name="ultimate_subscribe_options[socials][youtube][icon]" value="<?php echo esc_attr($youtube_icon); ?>">
                    </div>
                </div>
            </div>
            <div class="field-group">
                <div class="field-row">
                    <div class="field-label"> <?php _e('Instagram URL', 'ultimate-subscribe'); ?> </div>
                    <div class="field-data"> <input type="text" class="input-field" name="ultimate_subscribe_options[socials][instagram][url]" value="<?php echo esc_url($instagram_url); ?>"> </div>
                </div>
                <div class="field-row"> 
                    <div class="field-label"> <?php _e('Instagram Icon', 'ultimate-subscribe'); ?> </div>
                    <div class="field-data">
                        <input type="text" class="input-field" name="ultimate_subscribe_options[socials][instagram][icon]" value="<?php echo esc_attr($instagram_icon); ?>">
                    </div>
                </div>
            </div>
    </div>
</div>