<?php
if (!function_exists('add_action'))
{
    echo "<h3>an error occured! You may not be able to access this plugin via direct URL...</h3>";
    exit();
}
else if (!defined('ABSPATH'))
{
    echo "<h3>an error occured! You may not be able to access this plugin via direct URL...</h3>";
    exit();
}
?>

<h4><?php echo __('1- At first, either buy the font you want (not free) or download it (free fonts)', 'pfmdz') ?></h4>

<p><?php echo __('To use the downloaded fonts, you only need 2 woff and woff2 formats:', 'pfmdz') ?></p>

<p><?php echo __('woff2 format: It is used for new versions of Firefox, Chrome and Opera browsers', 'pfmdz') ?></p>

<p><?php echo __('woff format: for older versions of Firefox, Chrome and Opera browsers, as well as version 5.1 and above of Safari browser (Apple)', 'pfmdz') ?></p>

<p style="margin: 25px 0px;"></p>

<div onclick="exp_imgs_lightbox(this)"><img class="exampleimgs" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/help-pic1.webp');  ?>" width="300" height="auto" /></div>

<h4><?php echo __('2- After downloading the desired font, you must upload the woff and woff2 formats of that font in the WordPress media section:', 'pfmdz') ?></h4>

<div onclick="exp_imgs_lightbox(this)"><img class="exampleimgs" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/help-pic2.webp');  ?>" width="300" height="auto" /></div>

<p><?php echo __('If WordPress does not allow you to upload the font, in the plugin settings section (this page), in the advanced section, enable the option (Allow font upload in WordPress media)', 'pfmdz') ?></p>

<div onclick="exp_imgs_lightbox(this)"><img class="exampleimgs" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/help-pic7.webp');  ?>" width="300" height="auto" /></div>

<div onclick="exp_imgs_lightbox(this)"><img class="exampleimgs" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/allow-upload-fonts.webp');  ?>" width="300" height="auto" /></div>

<h4><?php echo __('3- After successful upload, click on each font and copy its URL by button (Copy URL to clipboard):', 'pfmdz') ?></h4>

<div onclick="exp_imgs_lightbox(this)"><img class="exampleimgs" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/help-pic4.webp'); ?>" width="300" height="auto" /></div>

<h4><?php echo __('4- After copying the address of the font file, paste it in the desired section (on the same page) for admin fonts or front fonts:', 'pfmdz') ?></h4>

<div onclick="exp_imgs_lightbox(this)"><img class="exampleimgs" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/help-pic5.webp'); ?>" alt="" width="300" height="auto" /></div>

<h4><?php echo __('5- In the last step, press the save settings button and enjoy the beautiful Persian fonts for WordPress!', 'pfmdz') ?></h4>

<div onclick="exp_imgs_lightbox(this)"><img class="exampleimgs" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/help-pic6.webp'); ?>" width="300" height="auto" /></div>

<p><?php echo __('Note: If you cannot see a change in the fonts of your site after saving the changes, be sure to clear the internal cache of your browser by using the control buttons + F5.', 'pfmdz') ?></p>

<div onclick="exp_imgs_lightbox(this)"><img class="exampleimgs" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/help-pic8.webp'); ?>" width="300" height="auto" /></div>

<h4><?php echo __('Note, if you use wp cache plugins such as Wp-Rocket and Lightspeed-Cache plugins, it is better to delete the entire site cache once after saving the changes:', 'pfmdz') ?></h4>

<div onclick="exp_imgs_lightbox(this)"><img class="exampleimgs" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/help-pic9.jpg'); ?>" width="300" height="auto" /></div>

<div onclick="exp_imgs_lightbox(this)"><img class="exampleimgs" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/help-pic10.jpg'); ?>" width="300" height="auto" /></div>

<p><?php echo __('Note: Avoid uploading and using files in other formats such as Json and formats that have nothing to do with fonts.', 'pfmdz') ?></p>

<p><?php echo __('Note: Avoid uploading irrelevant files (other than fonts) inside the WordPress media, this action can interfere with the functioning of your site.', 'pfmdz') ?></p>

<p><?php echo __('Note: This plugin does not intelligently apply the font to any of the HTML elements of the page that have the term icon in their CSS class', 'pfmdz') ?></p>

<p><?php echo __('Note: If after activating and using this plugin, there is a display problem for some of the icons on your site, either in the admin or on the front-side of the site, use additional CSS boxes and enter the additional CSS code for this problem.', 'pfmdz') ?></p>

<div onclick="exp_imgs_lightbox(this)"><img class="exampleimgs" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/help-pic11.webp'); ?>" width="300" height="auto" /></div>

<p><?php echo __('Persian font plugin (Persian admin fonts) is a free plugin for WordPress font management and can be used both for WordPress admin and for applying fonts on your front page.', 'pfmdz') ?></p>

<div onclick="exp_imgs_lightbox(this)"><img class="exampleimgs" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/help-pic12.webp'); ?>" width="300" height="auto" /></div>

<p><?php echo __('If you have any problems or questions about this plugin, please contact me:', 'pfmdz') ?></p>

<p style="text-align: center;"><strong>Email: mdesign.fa@gmail.com</strong></p>

<a class="button button-primary" href="https://t.me/g_mdz" target="_blank"><?php echo __('Quick Contact', 'pfmdz') ?></a>