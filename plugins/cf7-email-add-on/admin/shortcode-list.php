<?php
/**
 * CF7 Email shortcode list.
 *
 * @package Contact form 7
 * @subpackage Cotact form 7 email addon
 */

?>

<!-- Suggestion shortcode list-->
<div id="cf7-email-suggestion-list" style="display: none;">
	<div class="wrap">
		<h2 class="nav-tab-wrapper">
			<a href="#cf7_submissions" class="nav-tab nav-tab-active">
				<?php
					_e( 'Submissions', 'cf7-email-add-on' );
				?>
			</a>
			<a href="#cf7_post_related" class="nav-tab">
				<?php
					_e( 'Post Related', 'cf7-email-add-on' );
				?>
			</a>
			<a href="#cf7_site_related" class="nav-tab">
				<?php
					_e( 'Site Related', 'cf7-email-add-on' );
				?>
			</a>
			<a href="#cf7_user_related" class="nav-tab">
				<?php
					_e( 'User Related', 'cf7-email-add-on' );
				?>
			</a>
		</h2>
	</div>
	<div id="cf7_submissions" class="shortcode-list cf7-features-list">
		<table cellpadding="0" cellspacing="0" class="wp-list-table widefat cf7-table fixed table-wrap users">
			<tr>
				<td width="185"><code><?php echo esc_html( '[_remote_ip]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the submitter’s IP address.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_user_agent]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the submitter’s user agent (browser) information.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_url]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the URL of the page in which the contact form is placed.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_date]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the date of the submission.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_time]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the time of the submission.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_invalid_fields]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the number of form fields with invalid input.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_serial_number]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by a numeric string whose value increments, <br> so this tag can work as the serial number of each submission. Requires Flamingo 1.5+ be installed.', 'cf7-email-add-on' ); ?></td>
			</tr>
		</table>
	</div>
	<div id="cf7_post_related" class="shortcode-list cf7-features-list" style="display: none;">
		<table cellpadding="0" cellspacing="0" class="wp-list-table widefat cf7-table fixed table-wrap users">
			<tr>
				<td width="185"><code><?php echo esc_html( '[_post_id]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the ID of the post.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_post_name]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the name (slug) of the post.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_post_title]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the title of the post.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_post_url]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the permalink URL of the post.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_post_author]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the author name of the post.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_post_author_email]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the author email of the post.', 'cf7-email-add-on' ); ?></td>
			</tr>
		</table>
	</div>
	<div id="cf7_site_related" class="shortcode-list cf7-features-list" style="display: none;">
		<table cellpadding="0" cellspacing="0" class="wp-list-table widefat cf7-table fixed table-wrap users">
			<tr>
				<td width="185"><code><?php echo esc_html( '[_site_title]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the title of the website.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_site_description]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the description (tagline) of the website.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_site_url]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the home URL of the website.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_site_admin_email]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the email address of the primary admin user of the website.', 'cf7-email-add-on' ); ?></td>
			</tr>
		</table>
	</div>
	<div id="cf7_user_related" class="shortcode-list cf7-features-list" style="display: none;">
		<table cellpadding="0" cellspacing="0" class="wp-list-table widefat cf7-table fixed table-wrap users">
			<tr>
				<td width="185"><code><?php echo esc_html( '[_user_login]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the login name of the user.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_user_email]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the email address of the user.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_user_url]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the website URL of the user.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_user_first_name]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the first name of the user.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_user_last_name]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the last name of the user.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_user_nickname]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the nickname of the user.', 'cf7-email-add-on' ); ?></td>
			</tr>
			<tr>
				<td width="185"><code><?php echo esc_html( '[_user_display_name]' ); ?></code></td>
				<td><?php _e( 'This tag is replaced by the display name of the user.', 'cf7-email-add-on' ); ?></td>
			</tr>
		</table>
	</div>
</div>
