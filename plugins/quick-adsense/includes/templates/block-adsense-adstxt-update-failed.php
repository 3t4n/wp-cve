<div class="quick_adsense_popup_content_wrapper">
	<p>Auto Creation / Updation of ads.txt failed due to access permission restrictions on the server.</p>
	<p>You have to manually upload the file using your Host\'s File manager or your favourite FTP program</p>
	<p>ads.txt should be located in the root of your server. After manually uploading the file click <a href="<?php echo esc_url( site_url( '/ads.txt' ) ); ?>">here</a> to check if its accessible from the correct location</p>
	<textarea style="display: none;" id="quick_adsense_adstxt_content"><?php echo wp_kses( quick_adsense_get_value( $args, 'content' ), quick_adsense_get_allowed_html() ); ?></textarea>
	<p><a onclick="quick_adsense_adstxt_content_download()" class="button button-primary" href="javascript:;">Download ads.txt</a></p>
</div>
