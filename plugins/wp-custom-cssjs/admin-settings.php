<br class="clear">
<div id="col-container">
	<div id="col-left" style="float:right">
    	<div class="col-wrap">
        	<div class="form-wrap">
            	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="V6KS8QKHAC8HJ">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
                <p><?php _e("If you like it, please don't forget to rate us on Wordpress",'wpcssjs');?></p>
                <p><?php _e("Please help contributing towards open source by donating $$ now",'wpcssjs');?>
            </div>
        </div>
    </div>
    <div id="col-right" style="clear:none">
    	<div class="col-wrap">
        	<div class="form-wrap">
            	<form id="update_pie_cssjs" class="validate" method="post">
                	<?php wp_nonce_field( 'update_pie_cssjs','_wpnonce',1,1); ?>
                    <input type="hidden" name="wpccj_update_cssjs" value="1">
                    <h3><?php _e('Header','wpcssjs');?></h3>
                	<div class="form-field">
                    	<label for="pie_html_box_header" class="sr-only"><?php _e('HTML','wpcssjs');?></label>
                        <textarea id="wpccj-html-box-header" cols="40" rows="14" name="pie_html_box_header"><?php
							echo $this->wpccj_option['pie_html_box_header'];
						?></textarea>
                        <p class="help-text"><?php _e("You can use this box to put any HTML you want to add in the page head.",'wpcssjs');?></p>
                    </div>
                    <div class="form-field">
                    	<label for="pie_css_box_header" class="sr-only"><?php _e('Inline CSS','wpcssjs');?></label>
                        <code>&lt;style&gt;</code>
                        <textarea id="wpccj-css-box-header" cols="40" rows="14" name="pie_css_box_header"><?php
							echo $this->wpccj_option['pie_css_box_header'];
						?></textarea>
                        <code>&lt;/style&gt;</code>
                        <p class="help-text"><?php _e("You can use this box to put any CSS CODE you want to add in the page head.",'wpcssjs');?></p>
                    </div>
                    <div class="form-field">
                    	<label for="pie_js_box_header" class="sr-only"><?php _e('Inline JS/Jquery','wpcssjs');?></label>
                        <code>&lt;script&gt;</code>
                        <textarea id="wpccj-js-box-header" cols="40" rows="14" name="pie_js_box_header"><?php
							echo $this->wpccj_option['pie_js_box_header'];
						?></textarea>
                        <code>&lt;/script&gt;</code>
                        <p class="help-text"><?php _e("You can use this box to put any SCRIPT you want to add in the page head.",'wpcssjs');?></p>
                    </div>
                     <h3><?php _e('Footer','wpcssjs');?></h3>
                	<div class="form-field">
                    	<label for="pie_html_box_footer" class="sr-only"><?php _e('HTML','wpcssjs');?></label>
                        <textarea id="wpccj-html-box-footer" cols="40" rows="14" name="pie_html_box_footer"><?php
							echo $this->wpccj_option['pie_html_box_footer'];
						?></textarea>
                        <p class="help-text"><?php _e("You can use this box to put any HTML you want to add before closing </BODY> Tag.",'wpcssjs');?></p>
                    </div>
                    <div class="form-field">
                    	<label for="pie_css_box_footer" class="sr-only"><?php _e('Inline CSS','wpcssjs');?></label>
                        <code>&lt;style&gt;</code>
                        <textarea id="wpccj-css-box-header" cols="40" rows="14" name="pie_css_box_footer"><?php

							echo $this->wpccj_option['pie_css_box_footer'];
						?></textarea>
                        <code>&lt;/style&gt;</code>
                        <p class="help-text"><?php _e("You can use this box to put any CSS CODE you want to add before closing </BODY> Tag.",'wpcssjs');?></p>
                    </div>
                    <div class="form-field">
                    	<label for="parent" class="sr-only"><?php _e('Inline JS/Jquery','wpcssjs');?></label>
                        <code>&lt;script&gt;</code>
                        <textarea id="wpccj-js-box-footer" cols="40" rows="14" name="pie_js_box_footer"><?php
							echo $this->wpccj_option['pie_js_box_footer'];
						?></textarea>
                        <code>&lt;/script&gt;</code>
                        <p class="help-text"><?php _e("You can use this box to put any SCRIPT you want to add before closing </BODY> Tag.",'wpcssjs');?></p>
                    </div>
                    <p class="submit">
                    	<input id="submit" class="button button-primary" type="submit" value="<?php _e('Save all Changes','wpcssjs');?>" name="submit">
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
<br class="clear">