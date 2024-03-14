<form action="" method="post" id="helloBarAdmin">
    <?php wp_nonce_field($namespace."_options", $namespace.'_update_wpnonce'); ?>
    <input type="hidden" name="form_action" value="update_options">
    <div class="wrap" style="margin:0">
        <header><a href="javascript:void(0)" id="helloBasLogo"><img src="<?php echo WP_PLUGIN_URL."/hellobar"; ?>/images/logo.png"></a><br><br><span class="hbhdrTitle"><b>Convert</b> visitors into customers with Hello Bar</span></header>
        <div class="tool-box">
            <p><a class="helloBarSiteLink" target="new" href="https://www.hellobar.com">Visit Site to Learn More or Sign Up</a></p>
            <p class="helloBarHiLight"><?php
            if ($hellobar_code) {
                ?>Congratulations! Hello Bar is now installed on your site.<?php
            } else {
                ?>Thanks for the install! Glad to have you in the Hello Bar community!<br><br>You’re just a hop and a skip away from converting your site visitors into customers.<br><br>To get Hello Bar live on your WordPress site, you’ll just do a quick and easy “copy and paste” to install the site snippet.<br><br><a href="<?php echo WP_PLUGIN_URL."/hellobar"; ?>/images/demo.png" class="hellobardemoanc open-lightbox"><img class="hellobardemoimg" src="<?php echo WP_PLUGIN_URL . "/hellobar"; ?>/images/demo.png"></a><span class="hellobarInstruct">Here’s how:<br><br>1. Log in to HelloBar.com<br>2. Click on your email address at the top right corner of the screen<br>3. Select “Installation Instructions” from the drop down menu<br>4. Select “I use Wordpress” then click the green “Copy” button.<br><br> Now, your site snipped will be saved and ready to paste into WordPress directly, down below!<br><br>And with that, you’re ready to collect emails, drive traffic, and grow your social community! Kiss those one-time visitors goodbye and say hello to your new conversions with Hello Bar!</span><?php
            } ?></p><?php
if ($hellobar_code) {
    ?><input type="hidden" name="hellobar_code" value=""><?php
} else {
    ?>
                <div class="hellobarLable">
                    <div class="hbarLl"></div>
                    <div class="hbarLr"><a href="javascript:void(0)" class="hbarqtip1 hasTooltip">Locate my site snippet
                    <img src="<?php echo WP_PLUGIN_URL; ?>/hellobar/images/q.png"></a>
                <div class="hbarqtiptext1">Not quite sure how to follow the yellow brick road to Hello Bar installation? No worries, we’ve got your back! Just click your ruby red slippers together three times and shoot us a message at support@hellobar.com, and we’ll work our magic to take you from confusion to conversion!</div>
                    </div>
                </div>
                <textarea rows="3" name="hellobar_code" placeholder="Enter Hello Bar Site Snippet" ><?php echo $hellobar_code; ?></textarea>
                <input id="<?php echo $namespace; ?>-load-code-in-header" type="checkbox" name="load_hellobar_in" value="header"<?php echo $load_hellobar_in == 'header' ? ' checked="checked"' : ''; ?> /><label for="<?php echo $namespace; ?>-load-code-in-header">Load the Hello Bar code in your site's Header (before &lt;/head&gt;)</label>
                <?php
}
if ($hellobar_code) {
    ?><p class="helloBarSubmit"><input type="submit" name="Submit" class="helloBarSubmitBtn" value="<?php esc_attr_e('Start Over') ?>"/></p><?php
} else {
    ?><p class="helloBarSubmit"><input type="submit" name="Submit" class="helloBarSubmitBtn" value="<?php esc_attr_e('Activate Hello Bar') ?>"/></p><?php
}
?>
        </div>
    </div>
</form>
<?php
if (HelloBarForWordPress::is_script()!=false) {
    HelloBarForWordPress::reset_old_api(HelloBarForWordPress::is_script());
}
?>
<script type="text/javascript">jQuery(document).ready(function($){(function($){
$(document).on('click','.hellobardemoanc',function(e){
var image=$(this).attr('href');$('html').addClass('no-scroll');
$('body').append('<div class="lightbox-opened"><img src="'+image+'"></div>');return false});
$('body').on('click','.lightbox-opened',function(){$('html').removeClass('no-scroll');$('.lightbox-opened').remove();
});
$('.hbarqtip1').each(function(){$(this).qtip({position:{my:'top left',at:'bottom left',target:'.hbarqtip1'},content:{text:$('.hbarqtiptext1')},style:{classes:'hbarqtipcss1'}})});
}(jQuery))});</script>
