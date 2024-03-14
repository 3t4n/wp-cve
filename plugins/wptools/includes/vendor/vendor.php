<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-19 10:20:31
 */
// http://codylindley.com/thickbox/
if (!defined('ABSPATH'))
  exit; // Exit if accessed directly
add_thickbox();

$is_modal = "&modal=true";
$is_modal = "&modal=false";
$is_modal = "";
if (empty($is_modal))
  $wheight = "400";
else
  $wheight = "300";
?>
<div style="display:none; max-width:430px !important;">
    <a href="#TB_inline?&width=430&height=390&inlineId=wptools-scan-id<?php echo esc_attr($is_modal); ?>" id="wptools-scan-ok"
        class="thickbox" title="WP Tools Plugin"  style="display:none;background:yellow;">xx---xxx</a>
</div>
<div id="wptools-scan-id" style="display:none;">
            <video id="bill-banner-wpt" style="margin: 0px; padding:0px;" width="400" height="240" muted>
                <source src="<?php echo esc_url(WPTOOLSURL); ?>assets/videos/tools3.mp4" type="video/mp4">
            </video>
            <br> 

            <div id="wptools-wait" style="display:none;text-align:center; padding:10px;">
            
            <span class="spinner" style="display:none;"></span>

            <h3><?php esc_attr_e("Please, wait...  Dismissing...","wptools");?></h3>
            </div>

            <div class:bill-navpanel>
                <a href="#" id="bill-vendor-button-ok-wpt" style="margin-top: 0px !important; margin-right:0px;"
                    class="button button-primary bill-navitem"><?php esc_attr_e("Learn More","wptools");?></a>
                <a href="#" id="bill-vendor-button-again-wpt" style="margin-top: 0px !important;" class="button bill-navitem"><?php esc_attr_e("Watch Again","wptools");?></a>
                <a href="#" id="bill-vendor-button-dismiss-wpt" class="button bill-navitem" style="margin-left:0px !important;align:right;"><?php esc_attr_e("Dismiss","wptools");?></a>
           
              </div>
</div>     
<?php
return;