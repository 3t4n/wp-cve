<div class="wrap">
	<form method="post" action="options.php">
		<?php settings_fields( 'mantis-settings' ); ?>

        <h2>Publisher Configuration</h2>
		
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Website Identifier:</th>
				<td>
					<input type="text" name="mantis_site_id" value="<?php echo get_option('mantis_site_id'); ?>" />
					<p class="description">This identifier can be found in the code tab of your <a href="https://admin.mantisadnetwork.com" target="_blank">MANTIS administrative panel.</a></p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Load Advertisement Code Asynchronously</th>
				<td>
					<select name="mantis_async">
						<option value="0">Turn Off</option>
						<option value="1" <?php echo get_option('mantis_async') ? 'selected="selected"' : ''; ?>>Turn On</option>
					</select>
					<p class="description">When enabled, the loading of MANTIS advertisements will not impact the time it takes to load a page. However, you may notice a slight delay in serving ads with this option enabled. DO NOT turn on if you use services like CloudFlare.</a></p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Include Javascript at all times</th>
				<td>
					<select name="mantis_always">
						<option value="0">Turn Off</option>
						<option value="1" <?php echo get_option('mantis_always') ? 'selected="selected"' : ''; ?>>Turn On</option>
					</select>
					<p class="description">When enabled, the MANTIS javascript library will always load in the footer. Enable this if you added custom zone tags to your theme.</a></p>
				</td>
			</tr>

			<tr valign="top">
                <th scope="row">After Content Widget</th>
                <td>
                    <select name="mantis_after">
                    	<option value="">Turn Off</option>
                        <option value="after_content" <?php echo get_option('mantis_after') == 'after_content' ? 'selected="selected"' : ''; ?>>Show After Content</option>
                        <option value="before_comments" <?php echo get_option('mantis_after') == 'before_comments' ? 'selected="selected"' : ''; ?>>Show Before Comments</option>
                        <option value="after_comments" <?php echo get_option('mantis_after') == 'after_comments' ? 'selected="selected"' : ''; ?>>Show After Comments</option>
                    </select>
                    <p class="description">When enabled, a new area is added to your Appearance > Widget page allowing you to add additional zones using the MANTIS advertisement widget.</a></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Recommended Content Widget</th>
                <td>
                    <select name="mantis_recommend">
                        <option value="">Turn Off</option>
                        <option value="after_content" <?php echo get_option('mantis_recommend') == 'after_content' ? 'selected="selected"' : ''; ?>>Show After Content</option>
                        <option value="before_comments" <?php echo get_option('mantis_recommend') == 'before_comments' ? 'selected="selected"' : ''; ?>>Show Before Comments</option>
                        <option value="after_comments" <?php echo get_option('mantis_recommend') == 'after_comments' ? 'selected="selected"' : ''; ?>>Show After Comments</option>
                    </select>
                    <p class="description">When enabled, the MANTIS content recommendation widget will display after your post content.</a></p>
                </td>
            </tr>
		</table>


        <h2>Advertiser Configuration</h2>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">Advertiser Identifier:</th>
                <td>
                    <input type="text" name="mantis_advertiser_id" value="<?php echo get_option('mantis_advertiser_id'); ?>" />
                    <p class="description">This identifier can be found in the conversion tab of your <a href="https://admin.mantisadnetwork.com" target="_blank">MANTIS administrative panel.</a></p>
                </td>
            </tr>
        </table>

		<?php submit_button(); ?>
	</form>
</div>


