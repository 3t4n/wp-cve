<div>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</div>
<div class="edac-settings-main-warapper">
    <div class="edac-header-section">
        <div class="edac-header-logo">
            <span class="edac-main-head-title"><?php _e('8 Degree Availability Calendar ','edac-plugin');?></span>
            <span class="edac-main-version">Version <?php echo EDAC_VERSION;?></span>
        </div>
        <div class="edac-header-social-link">
            <label><?php _e('Follow us for new updates','edac-plugin');?></label><br />
            <div class="fb-like" data-href="https://www.facebook.com/8DegreeThemes" data-layout="button" data-action="like" data-show-faces="true" data-share="false"></div>
            <a href="https://twitter.com/8degreethemes" class="twitter-follow-button" data-show-count="false">Follow @8degreethemes</a>
        </div>
    </div>
    <?php if(isset($_SESSION['edac_message'])){ ?>
    <p class="edac-sesion-msg error"><?php echo $_SESSION['edac_message'];unset($_SESSION['edac_message']);?></p>
    <?php } ?>
    <nav class="edac-plugin-menu">
        <div id="settings" class="edac-tabs-trigger edac-active-tab"><a href="javascript:void(0);"><?php _e('Settings','edac-plugin');?></a></div>
        <div id="calendar" class="edac-tabs-trigger"><a href="javascript:void(0);"><?php _e('Admin Calendar','edac-plugin');?></a></div>
        <div id="how-to-use" class="edac-tabs-trigger"><a href="javascript:void(0);"><?php _e('How to use','edac-plugin');?></a></div>
        <div id="about" class="edac-tabs-trigger"><a href="javascript:void(0);"><?php _e('About','edac-plugin');?></a></div>
    </nav>
    <div class="edac-setting">
        <form method="post" action="<?php echo admin_url('admin-post.php');?>" class="edac_setting_form">
            <input type="hidden" name="action" value="edac_settings_action"/>

            <div id="edac-settings" class="edac-blocks-tabs">
            <?php include_once('blocks/settings.php');?>
            </div><!-- end of settings -->
            
            <?php
                /**
                * Creating a nonce field
                * */
                wp_nonce_field('edac-nonce','edac_nonce_field');
            ?>
            <div class="edac-plugin-submit-wrap">
                <input class="edac_buttons" type="submit" name="settings_submit" value="<?php _e('Save','edac-plugin');?>"/>
                <?php $nonce = wp_create_nonce('edac-restore-default-nonce');?>
                <a class="edac-reset-buttons" href="<?php echo admin_url().'admin-post.php?action=edac_restore_default&_wpnonce='.$nonce;?>" onclick="return confirm('<?php _e('Are you sure you want to restore default settings?','edac-plugin');?>')"><input type="button" value="Restore Default" class="edac-reset-button"/></a>
            </div>
        </form>

        <div id="edac-calendar" class="edac-blocks-tabs" style="display: none;">
            <?php include_once('blocks/calendar.php');?>
        </div><!-- end of calendar -->

        <div id="edac-how-to-use" class="edac-blocks-tabs" style="display: none;">
            <?php include_once('blocks/how-to-use.php');?>
        </div><!-- end of edac-how-to-use -->

        <div id="edac-about" class="edac-blocks-tabs" style="display: none;">
            <?php include_once('blocks/about.php');?>
        </div><!-- end of edac-about -->
        
    </div>
</div>