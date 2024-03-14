<div class="wrap">
	<h1><?php echo __('Easy Pixels by <b>JEVNET</b>','easy-pixels-by-jevnet'); ?></h1>

	<?php
		echo '<h2 class="nav-tab-wrapper">';
		do_action('easypixels_admintabs');
		echo '</h2>';
	?>
	 <p><?php echo __('Track Contact Form 7! Works with Google Tag Manager, Google Analytics, Google Ads, Bing, Facebook and Twitter. This plugin sends the events shown at the right side of each form to all platforms with no extra configuration. Automatically it sends the event including the form name and the form id.','easy-pixels-by-jevnet'); ?><br/><br/></p>
	 <h2><?php echo __('Install contact form 7 extension for FREE!'); ?></h2><br/><?php echo '<a href="'.admin_url().'plugin-install.php?tab=plugin-information&plugin=easy-pixels-contact-form-extension-by-jevnet&TB_iframe=true&width=640&height=500" target="_blank" class="button button-primary">'.__('Download for free','easy-pixels-by-jevnet').'</a>'; ?><br/><br/>
	 <p><img src="<?php echo JN_EasyPixels_URL; ?>/img/contactForm.png" style="float:left;margin-right:.5em;margin-bottom:.5em"><?php echo __('You can install and activate the contact form 7 extension for free<br/><br/>No extra configurations! Each form is tracked individually, informing automatically the form ID for specific tracking. The easiest way to track Contact Form 7 forms!','easy-pixels-by-jevnet'); ?><br/><br/></p>
	 <center>
	 	<div id="imgJNCF7container" style="border:1px solid #888;max-width: 100%;width:900px">
	 		<img src="<?php echo JN_EasyPixels_URL; ?>/img/screenshot-cf7.jpg" alt="" style="max-width: 100%" />
	 		<div id="JNCF7downloadCTA"><?php echo '<a href="'.admin_url().'plugin-install.php?tab=plugin-information&plugin=easy-pixels-contact-form-extension-by-jevnet&TB_iframe=true&width=640&height=500" target="_blank" class="button button-primary" id="JNCF7downloadCTA_CTA">'.__('Download for free','easy-pixels-by-jevnet').'</a>'; ?>
	 		</div>
	 	</div>
	 </center>
	 <p><?php echo '<a href="'.admin_url().'plugin-install.php?tab=plugin-information&plugin=easy-pixels-contact-form-extension-by-jevnet&TB_iframe=true&width=640&height=500" target="_blank" class="button button-primary">'.__('Download for free','easy-pixels-by-jevnet').'</a>'; ?></p>
</div>
<style>
	#imgJNCF7container{position: relative;overflow: hidden;}
	#imgJNCF7container #JNCF7downloadCTA{display: none;position: absolute;top:0;left:0;width: 100%;height:1000px;background:rgba(255,255,255,0.6);}
	#imgJNCF7container:hover #JNCF7downloadCTA{display:block;z-index:10;}
	 #JNCF7downloadCTA_CTA{position: absolute;top:100px;left:0;right:0;margin:auto;height:2.4em;width:20em;z-index:20;font-size: 1.4em}
</style>