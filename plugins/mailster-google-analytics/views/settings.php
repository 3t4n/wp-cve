<script type="text/javascript">
	jQuery(document).ready(function ($) {

		var inputs = $('.mailster-ga-value');
		inputs.on('keyup change', function(){
			var pairs = [];
			$.each(inputs, function(){
				var el = $(this),
					key = el.attr('name').replace('mailster_options[ga][','').replace(']', '');
				if(el.val()) pairs.push(key+'='+encodeURIComponent(el.val().replace(/%%([A-Z_]+)%%/g, '$1')));
			});
			$('#mailster-ga-preview').html('?'+pairs.join('&'));

		}).trigger('change');
	});
</script>
<table class="form-table">
<tr valign="top">
		<th scope="row"><?php esc_html_e( 'G-Tag ID:', 'mailster-google-analytics' ); ?></th>
		<td>
		<p class="description"><input type="text" name="mailster_options[ga_gtag]" value="<?php echo esc_attr( mailster_option( 'ga_gtag' ) ); ?>" class="regular-text" placeholder="G-XXXXXXXXXX">
		<?php esc_html_e( 'for the front end page of each newsletter', 'mailster-google-analytics' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'Web Property ID: (Legacy)', 'mailster-google-analytics' ); ?></th>
		<td>
		<p class="description"><input type="text" name="mailster_options[ga_id]" value="<?php echo esc_attr( mailster_option( 'ga_id' ) ); ?>" class="regular-text" placeholder="UA-XXXXXXX-X">
		<?php esc_html_e( 'for the front end page of each newsletter', 'mailster-google-analytics' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"></th>
		<td><p><a href="https://support.google.com/analytics/answer/1037445" class="external"><?php esc_html_e( 'read "Best Practices for creating Custom Campaigns"', 'mailster-google-analytics' ); ?></a></p></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'SetDomainName:', 'mailster-google-analytics' ); ?></th>
		<td>
		<p><input type="text" name="mailster_options[ga_setdomainname]" value="<?php echo esc_attr( mailster_option( 'ga_setdomainname' ) ); ?>" class="regular-text" placeholder="example.com"> <span class="description"><?php printf( esc_html__( '(Optional) Sets the %s variable.', 'mailster-google-analytics' ), '<code>_setDomainName</code>' ); ?></span></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'Defaults', 'mailster-google-analytics' ); ?><p class="description"><?php esc_html_e( 'Define the defaults for click tracking. Keep the default values until you know better.', 'mailster-google-analytics' ); ?></p></th>
		<td>
		<?php $ga_values = mailster_option( 'ga' ); ?>
		<div class="mailster_text"><label><?php esc_html_e( 'Campaign Source', 'mailster-google-analytics' ); ?> *:</label> <input type="text" name="mailster_options[ga][utm_source]" value="<?php echo esc_attr( $ga_values['utm_source'] ); ?>" class="mailster-ga-value regular-text"></div>
		<div class="mailster_text"><label><?php esc_html_e( 'Campaign Medium', 'mailster-google-analytics' ); ?> *:</label> <input type="text" name="mailster_options[ga][utm_medium]" value="<?php echo esc_attr( $ga_values['utm_medium'] ); ?>" class="mailster-ga-value regular-text"></div>
		<div class="mailster_text"><label><?php esc_html_e( 'Campaign Term', 'mailster-google-analytics' ); ?>:</label> <input type="text" name="mailster_options[ga][utm_term]" value="<?php echo esc_attr( $ga_values['utm_term'] ); ?>" class="mailster-ga-value regular-text"></div>
		<div class="mailster_text"><label><?php esc_html_e( 'Campaign Content', 'mailster-google-analytics' ); ?>:</label> <input type="text" name="mailster_options[ga][utm_content]" value="<?php echo esc_attr( $ga_values['utm_content'] ); ?>" class="mailster-ga-value regular-text"></div>
		<div class="mailster_text"><label><?php esc_html_e( 'Campaign Name', 'mailster-google-analytics' ); ?> *:</label> <input type="text" name="mailster_options[ga][utm_campaign]" value="<?php echo esc_attr( $ga_values['utm_campaign'] ); ?>" class="mailster-ga-value regular-text"></div>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'Example URL', 'mailster-google-analytics' ); ?></th>
		<td><code style="max-width:800px;white-space:normal;word-wrap:break-word;display:block;"><?php echo site_url( '/' ); ?><span id="mailster-ga-preview"></span></code></td>
	</tr>
	<tr valign="top">
		<th scope="row"></th>
		<td><p class="description"><?php esc_html_e( 'Available variables:', 'mailster-google-analytics' ); ?>(<?php esc_html_e( 'click to copy to clipboard', 'mailster-google-analytics' ); ?>)</p>
			<p>
				<a class="clipboard" data-clipboard-text="%%CAMP_ID%%">%%CAMP_ID%%</a>,
				<a class="clipboard" data-clipboard-text="%%CAMP_TITLE%%">%%CAMP_TITLE%%</a>,
				<a class="clipboard" data-clipboard-text="%%CAMP_TYPE%%">%%CAMP_TYPE%%</a>,
				<a class="clipboard" data-clipboard-text="%%CAMP_LINK%%">%%CAMP_LINK%%</a>,
				<a class="clipboard" data-clipboard-text="%%SUBSCRIBER_EMAIL%%">%%SUBSCRIBER_EMAIL%%</a>,
				<a class="clipboard" data-clipboard-text="%%SUBSCRIBER_HASH%%">%%SUBSCRIBER_HASH%%</a>,
				<a class="clipboard" data-clipboard-text="%%LINK%%">%%LINK%%</a>
			</p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'Campaign based value', 'mailster-google-analytics' ); ?></th>
		<td><label><input type="hidden" name="mailster_options[ga_campaign_based]" value=""><input type="checkbox" name="mailster_options[ga_campaign_based]" value="1" <?php checked( mailster_option( 'ga_campaign_based' ) ); ?>> <?php esc_html_e( 'allow campaign based variations of these values', 'mailster-google-analytics' ); ?></label><p class="description"><?php esc_html_e( 'adds a metabox on the campaign edit screen to alter the values for each campaign', 'mailster-google-analytics' ); ?></p></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php esc_html_e( 'External Domains', 'mailster-google-analytics' ); ?></th>
		<td><label><input type="hidden" name="mailster_options[ga_external_domains]" value=""><input type="checkbox" name="mailster_options[ga_external_domains]" value="1" <?php checked( mailster_option( 'ga_external_domains' ) ); ?>> <?php esc_html_e( 'allow tracking for external domains.', 'mailster-google-analytics' ); ?></label><p class="description"><?php printf( esc_html__( 'adds UTM parameters on external links other than %s.', 'mailster-google-analytics' ), '<i>' . site_url() . '</i>' ); ?></p></td>
	</tr>

</table>
