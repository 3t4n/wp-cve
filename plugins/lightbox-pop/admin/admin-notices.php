<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function wp_lightbox_admin_notice()
{
    add_thickbox();
    $sharelink_text_array = array
    (
        "I use Lightbox Pop wordpress plugin from @xyzscripts and you should too.",
        "Lightbox Pop wordpress plugin from @xyzscripts is awesome",
        "Thanks @xyzscripts for developing such a wonderful lightbox pop wordpress plugin",
        "I was looking for a lightbox pop plugin and I found this. Thanks @xyzscripts",
        "Its very easy to use Lightbox Pop wordpress plugin from @xyzscripts",
        "I installed Lightbox Pop from @xyzscripts,it works flawlessly",
        "Lightbox Pop wordpress plugin that i use works terrific",
        "I am using Lightbox Pop wordpress plugin from @xyzscripts and I like it",
        "The Lightbox Pop plugin from @xyzscripts is simple and works fine",
        "I've been using this lightbox pop plugin for a while now and it is really good",
        "Lightbox Pop wordpress plugin is a fantastic plugin",
        "Lightbox Pop wordpress plugin is easy to use and works great. Thank you!",
        "Good and flexible lightbox pop plugin especially for beginners",
        "The best lightbox pop wordpress plugin I have used ! THANKS @xyzscripts",
    );
    $sharelink_text = array_rand($sharelink_text_array, 1);
    $sharelink_text = $sharelink_text_array[$sharelink_text];
    $xyz_lightbox_link = admin_url('admin.php?page=lightbox-basic-settings&lightbox_blink=en');
    $xyz_lightbox_link = wp_nonce_url($xyz_lightbox_link,'lightbox_blink');
    $xyz_lightbox_notice = admin_url('admin.php?page=lightbox-basic-settings&lightbox_notice=hide');
    $xyz_lightbox_notice = wp_nonce_url($xyz_lightbox_notice,'lightbox_notice');
    echo '
	<script type="text/javascript">
			function xyz_lbx_shareon_tckbox(){
			tb_show("Share on","#TB_inline?width=500&amp;height=75&amp;inlineId=show_share_icons_light&class=thickbox");
		}
	</script>
	<div id="lbx_notice_td" class="error" style="color: #666666;margin-left: 2px; padding: 5px;line-height:16px;">
	<p>Thank you for using <a href="https://wordpress.org/plugins/lightbox-pop/" target="_blank"> Lightbox popup </a> plugin from <a href="https://xyzscripts.com/" target="_blank">xyzscripts.com</a>. Would you consider supporting us with the continued development of the plugin using any of the below methods?</p>
	<p>
	<a href="https://wordpress.org/support/plugin/lightbox-pop/reviews" class="button xyz_rate_btn" target="_blank">Rate it 5★\'s on wordpress</a>';
    if(get_option('xyz_credit_link')=="0")
        echo '<a href="'.$xyz_lightbox_link.'" class="button xyz_backlink_btn xyz_blink">Enable Backlink</a>';
        
        echo '<a class="button xyz_share_btn" onclick=xyz_lbx_shareon_tckbox();>Share on</a>
		<a href="https://xyzscripts.com/donate/5" class="button xyz_donate_btn" target="_blank">Donate</a>
            
	<a href="'.$xyz_lightbox_notice.'" class="button xyz_show_btn">Don\'t Show This Again</a>
	</p>
	    
	<div id="show_share_icons_light" style="display: none;">
	<a class="button" style="background-color:#3b5998;color:white;margin-right:4px;margin-left:100px;margin-top: 25px;" href="http://www.facebook.com/sharer/sharer.php?u=http://xyzscripts.com/wordpress-plugins/lightbox-pop/" target="_blank">Facebook</a>
	<a class="button" style="background-color:#00aced;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="http://twitter.com/share?url=http://xyzscripts.com/wordpress-plugins/lightbox-pop/&text='.$sharelink_text.'" target="_blank">Twitter</a>
	<a class="button" style="background-color:#007bb6;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="http://www.linkedin.com/shareArticle?mini=true&url=http://xyzscripts.com/wordpress-plugins/lightbox-pop/" target="_blank">LinkedIn</a>
	<a class="button" style="background-color:#dd4b39;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="https://plus.google.com/share?&hl=en&url=http://xyzscripts.com/wordpress-plugins/lightbox-pop/" target="_blank">Google +</a>
	</div>
	</div>';
        
        
}
$lightbox_installed_date = get_option('lightbox_installed_date');
if ($lightbox_installed_date=="")
{
    $lightbox_installed_date = time();
}
if($lightbox_installed_date < ( time() - (30*24*60*60) ))
{
    if (get_option('xyz_lightbox_dnt_shw_notice') != "hide")
    {
        add_action('admin_notices', 'wp_lightbox_admin_notice');
    }
}
?>