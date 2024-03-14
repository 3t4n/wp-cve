<?php

function apa_cf7sdomt_f_generate_html(){

    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
?>
<div class="wrap">
	<h2>Submission DOM tracking for Contact Form 7</h2>
	<div id="main-container" class="postbox-container metabox-holder" style="width:75%;">
    	<div style="margin:0 8px;">
            <div class="postbox">
                <h3 style="cursor:default;"><span>Submission DOM tracking for Contact Form 7 - <?php _e('Settings', 'apa-cf7sdomt'); ?></span></h3>
                <div class="inside">
                    <p><?php _e( 'We created this plugin to be able to track form submissions in Google Analytics, with the Facebook pixel and to hide the form once completed. Before we were doing this with the "on_sent_ok". We also included the possibility to deregister the styles and JavaScript of the plugin on pages that doesn\'t contain a contact form.', 'apa-cf7sdomt' ); ?></p>
					<p><?php _e( 'This was published on the official Contact Form 7 website on June 7th, 2017 by the author of the plugin (<a href="https://contactform7.com/2017/06/07/on-sent-ok-is-deprecated/" target="_blank">on_sent_ok Is Deprecated</a>):', 'apa-cf7sdomt' ); ?></p>
					<blockquote><i><?php _e( 'The "on_sent_ok" and its sibling setting "on_submit" of the Contact Form 7 plugin are deprecated and scheduled to be abolished by the end of 2017. The recommended alternative to on_sent_ok is using DOM events. This plugin helps to set this DOM events to track form submissions.', 'apa-cf7sdomt' ); ?></i></blockquote>
					<p><?php _e( 'In order to minimize the impact on the large amount of sites we run, we decided to code this plugin.', 'apa-cf7sdomt' ); ?></p>
					<p><?php _e( 'This plugin is made for Contact Form 7 version 4.8 or higher with DOM events support.', 'apa-cf7sdomt' ); echo ' '; if (defined('WPCF7_VERSION')) { _e( 'You are using version:', 'apa-cf7sdomt' ); echo ' ' . WPCF7_VERSION . ' '; if ( WPCF7_VERSION <= 4.7 ) { echo '(<strong>'; _e( 'ERROR', 'apa-cf7sdomt' ); echo '</strong>)'; } else { echo '(<strong>'; _e( 'OK', 'apa-cf7sdomt' ); echo '</strong>)'; } 
					} else { _e( 'Contact form 7 is not active', 'apa-cf7sdomt' ); } ?></p>
					<form method="post" action="options.php">
						<?php settings_fields( 'apa-cf7sdomt-settings-group' ); ?>
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><?php _e( 'Pages with contact forms', 'apa-cf7sdomt' ); ?></th>
								<td>
									<textarea id="apa_cf7sdomt_pages_with_contact_forms" name="apa_cf7sdomt_pages_with_contact_forms" cols="70" rows="3"><?php echo get_option( 'apa_cf7sdomt_pages_with_contact_forms' ); ?></textarea>
									<p class="description"><?php _e( 'Here you can add the pages with contact forms', 'apa-cf7sdomt' ); ?></p>
									<p class="description"><?php _e( 'If you don\'t add the pages here, the tracking code is added to the footer of all pages.', 'apa-cf7sdomt' ); ?></p>
									<p class="description"><?php _e( 'You can add either ID\'s of pages (numbers), page slugs or page names separated by coma.', 'apa-cf7sdomt' ); ?></p>
									<p class="description"><?php _e( 'For example:', 'apa-cf7sdomt' ); ?></p>
									<p class="description"><code><?php echo ( esc_html('69,contact, Contact Us') ); ?></code></p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( 'Tracking options', 'apa-cf7sdomt' ); ?></th>
								<td>
									<p><label><input type="checkbox" id="apa_cf7sdomt_ga_page_view" name="apa_cf7sdomt_ga_page_view" value="<?php echo ( 'show' ); ?>" <?php if ( get_option( 'apa_cf7sdomt_ga_page_view' ) === 'show') { echo 'checked'; } ?> ><?php _e('Track as Google Analytics (page view)?', 'apa-cf7sdomt'); ?></label></p>
									<p><label><input type="checkbox" id="apa_cf7sdomt_ga_event" name="apa_cf7sdomt_ga_event" value="<?php echo ( 'show' ); ?>" <?php if ( get_option( 'apa_cf7sdomt_ga_event' ) === 'show') { echo 'checked'; } ?> ><?php _e('Track as Google Analytics (event)?', 'apa-cf7sdomt'); ?></label></p>
									<p><label><input type="checkbox" id="apa_cf7sdomt_fb_pixel_lead" name="apa_cf7sdomt_fb_pixel_lead" value="<?php echo ( 'show' ); ?>" <?php if ( get_option( 'apa_cf7sdomt_fb_pixel_lead' ) === 'show') { echo 'checked'; } ?> ><?php _e('Track Facebook Pixel (lead)?', 'apa-cf7sdomt'); ?></label></p>
									<p class="description"><?php _e( 'Settings to track Google Analytics (page view), Google Analytics (event) and Facebook Pixel (lead).', 'apa-cf7sdomt' ); ?></p>
									<p class="description"><?php _e( 'If none is selected the submission will not be tracked.', 'apa-cf7sdomt' ); ?></p>
									<p class="description"><?php _e( 'IMPORTANT:', 'apa-cf7sdomt' ); ?> <strong><?php _e( 'If you are not using Google Analytics or do not have the Facebook pixel installed, please do not select these tracking options because it will lead to a JavaScript errors.', 'apa-cf7sdomt' ); ?></strong></p>
									<p class="description"><?php _e( 'PD: We also detect if the plugin "Google Analytics for WordPress by MonsterInsights" is active because it uses a non standard call to Google Analytics. Instead of ga it calls __gatracker.', 'apa-cf7sdomt' ); ?></p>
									<p class="description"><?php _e( 'PD2: Please keep an eye on 404 errors in Google Search Console when using the page view tracking of Google Analytics. Some users see these virtual page views as 404 errors in GSC. We are mantaining this option in the plugin, but recommend NOT to use it; use EVENT TRACKING instead.', 'apa-cf7sdomt' ); ?></p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( 'Customize tracking options', 'apa-cf7sdomt' ); ?></th>
								<td>							
									<p><label><?php _e('URL to track the page view (default:', 'apa-cf7sdomt'); ?> "<strong><?php _e('URL of the contact form', 'apa-cf7sdomt'); ?></strong>" + "<strong>/ok/</strong>"): <input type="text" id="apa_cf7sdomt_ga_page_view_url" size="70" name="apa_cf7sdomt_ga_page_view_url" value="<?php echo get_option( 'apa_cf7sdomt_ga_page_view_url' ); ?>" /></label></p>
									<p><label><?php _e('Event category (default:', 'apa-cf7sdomt'); ?>  "<strong>Contact form 7</strong>"): <input type="text" id="apa_cf7sdomt_ga_event_category" size="20" name="apa_cf7sdomt_ga_event_category" value="<?php echo get_option( 'apa_cf7sdomt_ga_event_category' ); ?>" /></label></p>
									<p><label><?php _e('Event action (default:', 'apa-cf7sdomt'); ?>  "<strong>sent</strong>"): <input type="text" id="apa_cf7sdomt_ga_event_action" size="20" name="apa_cf7sdomt_ga_event_action" value="<?php echo get_option( 'apa_cf7sdomt_ga_event_action' ); ?>" /></label></p>										
									<p><?php _e('Event label (default:', 'apa-cf7sdomt'); ?>  "<strong><?php _e('URL of the contact form', 'apa-cf7sdomt'); ?></strong>"): <i><?php _e('Sorry, but the label can\'t be customized.', 'apa-cf7sdomt'); ?></i></p>
									</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( 'Hide form?', 'apa-cf7sdomt' ); ?></th>
								<td>
									<p><label><input type="checkbox" id="apa_cf7sdomt_hide_form" name="apa_cf7sdomt_hide_form" value="<?php echo ( 'show' ); ?>" <?php if ( get_option( 'apa_cf7sdomt_hide_form' ) === 'show') { echo 'checked'; } ?> ><?php _e('Hide form after succesful submission?', 'apa-cf7sdomt'); ?></label></p>
									<p class="description"><?php _e( 'In order to be able to hide only the form and not the succesful submission message, the form must be wrapped in a div called <code>hidecontactform7contactform</code>.', 'apa-cf7sdomt' ); ?></p>
									<p class="description"><?php _e( 'Example:', 'apa-cf7sdomt' ); ?></p>
									<p class="description"><code><?php echo ( esc_html('<div id="hidecontactform7contactform">') ); ?></code></p>
									<p class="description"><?php _e( 'The form code.', 'apa-cf7sdomt' ); ?></p>
									<p class="description"><code><?php echo ( esc_html('</div>') ); ?></code></p>
									<p class="description"><?php _e( 'IMPORTANT:', 'apa-cf7sdomt' ); ?> <strong><?php _e( 'If you are not wrappping your form into the <code>hidecontactform7contactform</code> div, don\'t activate this option as it will lead to a JavaScript errors and the code after successfull submission of the contact form will not be executed.', 'apa-cf7sdomt' ); ?></strong></p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( 'Deregister Styles and JS?', 'apa-cf7sdomt' ); ?></th>
								<td>
									<p><label><input type="checkbox" id="apa_cf7sdomt_deregister_styles" name="apa_cf7sdomt_deregister_styles" value="<?php echo ( 'show' ); ?>" <?php if ( get_option( 'apa_cf7sdomt_deregister_styles' ) === 'show') { echo 'checked'; } ?> ><?php _e('Deregister Styles?', 'apa-cf7sdomt'); ?></label></p>
									<p><label><input type="checkbox" id="apa_cf7sdomt_deregister_javascript" name="apa_cf7sdomt_deregister_javascript" value="<?php echo ( 'show' ); ?>" <?php if ( get_option( 'apa_cf7sdomt_deregister_javascript' ) === 'show') { echo 'checked'; } ?> ><?php _e('Deregister Javascript?', 'apa-cf7sdomt'); ?></label></p>
									<p class="description"><?php _e( 'Settings to deregister Contact Form 7 Styles and Javascript on all pages that do not contain a contact form. Please keep in mind that the forms have to be defined in the section "<a href="#apa_cf7sdomt_pages_with_contact_forms">Pages with contact forms</a>" on this page so that these settings are used.', 'apa-cf7sdomt' ); ?></p>
								</td>
							</tr>							
							</table>
						<p class="submit">
							<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'apa-cf7sdomt' ); ?>" />
						</p>
					</form>
				</div> <!-- .inside -->
            </div> <!-- .postbox -->
		</div> <!-- style margin -->
	</div> <!-- #main-container -->
	<div id="side-container" class="postbox-container metabox-holder" style="width:24%;">
    	<div style="margin:0 8px;">
            <div class="postbox">
                <h3 style="cursor:default;"><span><?php _e('Do you like this Plugin?', 'apa-cf7sdomt'); ?></span></h3>
                <div class="inside">
                    <p><?php _e('We also need volunteers to translate this and our other plugins into more languages.', 'apa-cf7sdomt'); ?></p>
                    <p><?php _e('If you wish to help then use our', 'apa-cf7sdomt'); echo ' <a href="https://apasionados.es/contacto/index.php?desde=wordpress-org-contactform7sdomtracking-administracionplugin" target="_blank">'; _e('contact form', 'apa-cf7sdomt'); echo '</a> '; _e('or contact us on Twitter:', 'apa-cf7sdomt'); echo ' <a href="https://twitter.com/apasionados" target="_blank">@Apasionados</a>.'; ?></p>
                    <h4 align="right"><img src="<?php echo (plugin_dir_url(__FILE__) . 'love_bw.png'); ?>" /> <span style="color:#b5b5b5;"><?php _e('Developed with love by:', 'apa-cf7sdomt'); ?></span> <a href="https://apasionados.es/" target="_blank">Apasionados.es</a></h4>
                </div> <!-- .inside -->
            </div> <!-- .postbox -->
		</div> <!-- style margin -->
	</div> <!-- #side-container -->
</div> <!-- wrap -->


<?php
}
apa_cf7sdomt_f_generate_html();
?>