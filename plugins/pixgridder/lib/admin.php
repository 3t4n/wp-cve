<?php
	global $options;

	$plugin = get_plugin_data(PIXGRIDDER_PATH.'pixgridder.php');

    if (isset($_GET['page']) && $_GET['page']=='pixgridder_admin') {
?>

	<div id="pixgridder-box">

		<div id="pixgridder-visible">

			<h1>Hi, and thank you for visiting this page</h1>

			<hr>

		    <h4>
		    	If you like PixGridder and are enjoying it, please consider to support its author somehow
		    </h4>

		    <br>

		    <h3>
		    	Share it:

		    </h3>
		    <iframe src="http://www.paywithatweet.com/dlbutton03.php?id=4b9da35c-ada6-498d-8af3-a1779ec9f6af" class="spaced-right" name="paytweet_button" scrolling="no" frameborder="no" height="48px" width="292px"></iframe>

			<hr>

		    <br>
		    
		    <h3>
		    	Buy me an ice cream (or a Maserati, if you prefer):
		    </h3>

		    <p>
		    	
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input name="item_name" type="hidden" value="My donation for PixGridder" />
	<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
	">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
	</form>

		    </p>

			<hr>

		    <br>
		    
		    <h3>
		    	Consider one of these premium themes and plugins
		    </h3>

		    <div id="pixedelic-teaser-products">
		    	<figure class="teaser-item">
		    		<a href="http://codecanyon.net/item/pixgridder-pro-page-grid-composer-for-wordpress/5251972?ref=pixedelic" target="_blank">
		    			<img src="<?php echo PIXGRIDDER_URL; ?>images/pixgridder-banner.png">
		    		</a>
		    		<figcaption>
		    			<h3>
		    				<a href="http://codecanyon.net/item/pixgridder-pro-page-grid-composer-for-wordpress/5251972?ref=pixedelic" target="_blank">
		    					PixGridder Pro Page Grid Composer for Wordpress
		    				</a>
		    			</h3>
					    <p>
							The Pro version of the page builder, with parallax effect, rotation, reveal on scroll etc. It also includes <a href="http://codecanyon.net/item/shortcodelic-wordpress-plugin-bundle/6076939?ref=pixedelic" target="_blank">Shortcodelic</a>
					    </p>
					</figcaption>
		    	</figure><figure class="teaser-item">
		    		<a href="http://themeforest.net/item/geode-elegant-ecommerce-multipurpose-theme/8181066?ref=pixedelic" target="_blank">
		    			<img src="<?php echo PIXGRIDDER_URL; ?>images/geode-banner.jpg">
		    		</a>
		    		<figcaption>
		    			<h3>
		    				<a href="http://themeforest.net/item/geode-elegant-ecommerce-multipurpose-theme/8181066?ref=pixedelic" target="_blank">
		    					Geode Elegant eCommerce Multipurpose Theme
		    				</a>
		    			</h3>
					    <p>
							A multipurpose theme, perfect like blog, ecommerce, portfolio. It also includes <a href="http://codecanyon.net/item/shortcodelic-wordpress-plugin-bundle/6076939?ref=pixedelic" target="_blank">Shortcodelic</a> and <a href="http://codecanyon.net/item/pixgridder-pro-page-grid-composer-for-wordpress/5251972?ref=pixedelic" target="_blank">PixGridder Pro</a>
					    </p>
					</figcaption>
		    	</figure><figure class="teaser-item">
		    		<a href="http://codecanyon.net/item/shortcodelic-wordpress-plugin-bundle/6076939?ref=pixedelic" target="_blank">
		    			<img src="<?php echo PIXGRIDDER_URL; ?>images/shortcodelic-banner.jpg">
		    		</a>
		    		<figcaption>
		    			<h3>
		    				<a href="http://codecanyon.net/item/shortcodelic-wordpress-plugin-bundle/6076939?ref=pixedelic" target="_blank">
		    					Shortcodelic - Wordpress Plugin Bundle
		    				</a>
		    			</h3>
					    <p>
							A plugins bundle that puts in the same plugin lots of features: slideshows (the sliders support videos, also as background, and can adapt themselves to their parent element, so can ba fullscreen too), tabs, accordions, comparative tables, advanced Google maps, progress bars, font icons etc. It also includes <a href="http://codecanyon.net/item/pixgridder-pro-page-grid-composer-for-wordpress/5251972?ref=pixedelic" target="_blank">PixGridder Pro</a>
					    </p>
					</figcaption>
		    	</figure><figure class="teaser-item">
		    		<a href="http://themeforest.net/item/forte-multipurpose-wp-theme-woocommerce-ready/3888979?ref=pixedelic" target="_blank">
		    			<img src="<?php echo PIXGRIDDER_URL; ?>images/forte-banner.jpg">
		    		</a>
		    		<figcaption>
		    			<h3>
		    				<a href="http://themeforest.net/item/forte-multipurpose-wp-theme-woocommerce-ready/3888979?ref=pixedelic" target="_blank">
		    					Forte — Multipurpose WP Theme (WooCommerce Ready)
		    				</a>
		    			</h3>
					    <p>
							A powerful, elegant and flexible WordPress e-commerce theme with an adaptive layout and many features inside. It has got a (really) powerful admin panel, and an intuitive page builder (page composer).
					    </p>
					</figcaption>
		    	</figure><figure class="teaser-item">
		    		<a href="http://themeforest.net/item/enfinity-adaptive-ecommerce-portfolio-wp-theme/2663674?ref=pixedelic" target="_blank">
		    			<img src="<?php echo PIXGRIDDER_URL; ?>images/enfinity-banner.jpg">
		    		</a>
		    		<figcaption>
		    			<h3>
		    				<a href="http://themeforest.net/item/enfinity-adaptive-ecommerce-portfolio-wp-theme/2663674?ref=pixedelic" target="_blank">
		    					Enfinity - Adaptive Ecommerce Portfolio WP theme
		    				</a>
		    			</h3>
					    <p>
							An elegant and flexible WordPress e-commerce theme with an adaptive layout and many features inside. It has got a (really) powerful admin panel
					    </p>
					</figcaption>
		    	</figure><figure class="teaser-item">
		    		<a href="http://themeforest.net/item/delight-fullscreen-wordpress-portfolio-theme/255488?ref=pixedelic" target="_blank">
		    			<img src="<?php echo PIXGRIDDER_URL; ?>images/delight-banner.jpg">
		    		</a>
		    		<figcaption>
		    			<h3>
		    				<a href="http://themeforest.net/item/delight-fullscreen-wordpress-portfolio-theme/255488?ref=pixedelic" target="_blank">
		    					Delight Fullscreen Wordpress Portfolio Theme
		    				</a>
		    			</h3>
					    <p>
							A Powerful Professional Wordpress Theme, with Fullscreen background. It requires PHP5 . It comes with many jQuery effects (Cycle, Tabs, Accordions, Drop Down Menu, Tooltip, Colorbox, Auto alignment and more…), unlimited skins, unlimited possibilities, Goolge web fonts, different page templates, drag & drop system to compose your contact forms, and slideshows, a huge number of shortcodes with useful buttons in the text editor, a powerful extended administration panel, widget ready with four internal widgets, easy to translate with a .po file.
					    </p>
					</figcaption>
		    	</figure>
		    </div><!-- #pixedelic-teaser-products -->

		    <br>
		    
			<hr>

			<h4>
				If you don't want to see this page and the other messages on your backend anymore, just tick the field below and save.<br>
				It wasn't my intention to bother you, sorry.
			</h4>

		    	<form action="/" class="dynamic_form ajax_form cf" id="pixgridder_form">
		    
				<input type="hidden" name="pixgridder_hide_donate" value="0">
				<input type="checkbox" name="pixgridder_hide_donate" value="true" <?php checked( get_option('pixgridder_hide_donate'), 'true' ); ?>>

		        <input type="hidden" name="action" value="pixgridder_data_save" />
		        <input type="hidden" name="pixgridder_security" value="<?php echo wp_create_nonce('pixgridder_data'); ?>" />
		        <button type="submit" class="pix-save-options button button-primary" id="pixgridder_submit"><?php _e('Save and hide everything','pixgridder'); ?></button>

		    </form><!-- .dynamic_form -->

		</div>

	    <h2 style="display:none" id="pixgridder-thank">
	    	Thank you! Bye.
	    </h2>

	</div>

<?php 
}