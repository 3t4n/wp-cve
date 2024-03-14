<div class="wrap">
	<h1><?php echo __('Easy Pixels by <b>JEVNET</b>','easy-pixels-by-jevnet'); ?></h1>

	<?php
		echo '<h2 class="nav-tab-wrapper">';
		do_action('easypixels_admintabs');
		echo '</h2>';
	?>
	 <p><?php echo __('Track WooCommerce! Works with Google Tag Manager, Google Analytics, Google Ads and Facebook . <a href="https://wordpress.org/plugins/easy-pixels-ecommerce-extension-by-jevnet/">More info</a>','easy-pixels-ecommerce-extension-by-jevnet');?><br/><br/></p>

	 <h2><?php echo __('Install WooCommerce extension for FREE!'); ?></h2><br/><?php echo '<a href="'.admin_url().'plugin-install.php?tab=plugin-information&plugin=easy-pixels-ecommerce-extension-by-jevnet&TB_iframe=true&width=640&height=500" target="_blank" class="button button-primary">'.__('Download for free','easy-pixels-by-jevnet').'</a>'; ?><br/><br/>
	 <p><img src="<?php echo JN_EasyPixels_URL; ?>/img/woocommerce.png" style="float:left;margin-right:.5em;margin-bottom:.5em"><?php echo __('You can install and activate the WooCommerce extension for free.<br/><br/>No extra configurations! All tracking events are sent automatically. Just inform Google Ads conversion tracking if needed.'); ?><br/><br/></p>
	 <p>
	 <center>
	 	<div id="imgJNWCcontainer" style="border:1px solid #888;max-width: 100%;width:900px">
	 		<img src="<?php echo JN_EasyPixels_URL; ?>/img/screenshot-wc.jpg" alt="" style="max-width: 100%" />
	 		<div id="JNWCdownloadCTA"><?php echo '<a href="'.admin_url().'plugin-install.php?tab=plugin-information&plugin=easy-pixels-ecommerce-extension-by-jevnet&TB_iframe=true&width=640&height=500" target="_blank" class="button button-primary" id="JNWCdownloadCTA_CTA">'.__('Download for free','easy-pixels-by-jevnet').'</a>'; ?>
	 		</div>
	 	</div>
	 </center>
	</p>
	 <p><?php echo '<a href="'.admin_url().'plugin-install.php?tab=plugin-information&plugin=easy-pixels-ecommerce-extension-by-jevnet&TB_iframe=true&width=640&height=500" target="_blank" class="button button-primary">'.__('Download for free','easy-pixels-by-jevnet').'</a>'; ?></p>
</div>
<style>
	#imgJNWCcontainer{position: relative;overflow: hidden;}
	#imgJNWCcontainer #JNWCdownloadCTA{display: none;position: absolute;top:0;left:0;width: 100%;height:1000px;background:rgba(255,255,255,0.6);}
	#imgJNWCcontainer:hover #JNWCdownloadCTA{display:block;z-index:10;}
	 #JNWCdownloadCTA_CTA{position: absolute;top:100px;left:0;right:0;margin:auto;height:2.4em;width:20em;z-index:20;font-size: 1.4em}
</style>