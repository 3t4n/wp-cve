<?php
if (!defined("ABSPATH")) {
   exit;
} // Exit if accessed directly
else {
   include WP_LIGHTBOX_BANK_BK_PLUGIN_DIR . "/lib/get-wp-lightbox-bank-setting.php";
   if ($wp_galleries == "1" || $wp_caption_image == "1" || $attachment_image == "1") {
      echo "<style>
	#lightGallery-slider .info .desc
	{
		direction: " . $language_direction . " !important;
		text-align: " . $text_align . " !important;
	}
	#lightGallery-slider .info .title
	{
		direction: " . $language_direction . " !important;
		text-align: " . $text_align . " !important;
	}
	</style>";
      ?>
      <script type="text/javascript">
         var string = ".wp-lightbox-bank,", ie, ieVersion, lightease;
         jQuery(document).ready(function ($) {
      <?php
      if ($wp_galleries == "1") {
         ?>
               string = ".gallery-item, ";
         <?php
      }
      if ($wp_caption_image == "1") {
         ?>
               string += ".wp-caption > a, ";
         <?php
      }
      if ($attachment_image == "1") {
         ?>
               string += "a:has(img[class*=wp-image-])";
         <?php
      }
      ?>
            if (navigator.appName == "Microsoft Internet Explorer") {
               //Set IE as true
               ie = true;
               //Create a user agent var
               var ua = navigator.userAgent;
               //Write a new regEx to find the version number
               var re = new RegExp("MSIE ([0-9]{1,}[.0-9]{0,})");
               //If the regEx through the userAgent is not null
               if (re.exec(ua) != null) {
                  //Set the IE version
                  ieVersion = parseInt(RegExp.$1);
               }
            }
            if (ie = true && ieVersion <= 9)
            {
               lightease = "";
            } else
            {
               lightease = "ease";
            }
            var selector = string.replace(/,\s*$/, "");
            jQuery(selector).lightGallery({
               caption: <?php echo esc_html($image_title); ?>,
               desc: <?php echo esc_attr($image_caption); ?>,
               disableOther: <?php echo esc_attr($disable_other_lightbox); ?>,
               closable: <?php echo esc_attr($overlay_click); ?>,
               errorMessage: "<?php echo esc_attr($error_message); ?>",
               easing: lightease
            });
         });

      </script>
      <?php
   }
}