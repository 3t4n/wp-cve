<?php
/**
 * Lord of the Files: Debug File Validation
 *
 * The sidebar body content goes here.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm;

use blobfolio\wp\bm\admin\debug;

// Do not execute directly.
if (! \defined('ABSPATH')) {
	exit;
}

?>
<!-- Upload Box -->
<div class="postbox">
	<h2 class="hndle"><span><?php echo \esc_html__('Test File', 'blob-mimes'); ?></span></h2>
	<div class="inside">
		<form
			method="post"
			action="<?php echo \esc_url(debug::url()); ?>"
			enctype="multipart/form-data"
			name="validationForm"
		>
			<!-- A Nonce for the form. -->
			<input
				type="hidden"
				name="n"
				value="<?php echo \esc_attr(debug::nonce()); ?>"
			/>

			<table>
				<tbody>
					<tr>
						<th scope="row">
							<label
								for="validation-form-file"
								class="is-required"
							><?php echo \esc_html__('File', 'blob-mimes'); ?>:</label>
						</th>
						<td>
							<input
								type="file"
								name="file"
								id="validation-form-file"
								required
							/>
						</td>
					</tr>
					<tr>
						<th scope="row">&nbsp;</th>
						<td>
							<button
								type="submit"
								class="button button-large button-primary"
							><?php echo \esc_html__('Upload', 'blob-mimes'); ?></button>
						</td>
					</tr>
				</tbody>
			</table>

			<!-- A blank line. -->
			<p class="description">&nbsp;</p>

			<p class="description"><?php \printf(
				\esc_html__('Files uploaded with this tool are Burned After Reading. They will *not* be added to the %s or anywhere else.', 'blob-mimes'),
				\sprintf(
					'<a href="%s">%s</a>',
					\admin_url('upload.php'),
					// Use the WordPress Core translation for this.
					\esc_html__('Media Library')
				)
			); ?></p>
		</form>
	</div><!-- .inside -->
</div><!-- .postbox -->

<!-- Report a Problem! -->
<div class="postbox lotf__report-issues">
	<h2 class="hndle">
		<span><?php echo \esc_html__('Report an Issue', 'blob-mimes'); ?></span>
		<a
			class="lotf__report-issues_link github"
			href="https://github.com/Blobfolio/righteous-mimes/issues/new"
			rel="noopener"
			target="_blank"
			title="Righteous MIMEs! (Upstream Github Repository)"
		><?php echo \file_get_contents(\LOTF_BASE_PATH . '/assets/github.svg'); ?></a>
		<a
			class="lotf__report-issues_link wordpress"
			href="https://wordpress.org/support/plugin/blob-mimes/"
			rel="noopener"
			target="_blank"
			title="Lord of the Files (WordPress Support)"
		><?php echo \file_get_contents(\LOTF_BASE_PATH . '/assets/wordpress.svg'); ?></a>
	</h2>
	<div class="inside">
		<p><?php echo \esc_html__('If you have discovered an issue, please take a few moments to report it so we can help fix it for you — and everyone else!', 'blob-mimes'); ?></p>

		<p><?php \printf(
			\esc_html__('All you need to do is click the %s button (on top of the results) and paste that into a new support ticket at either %s or %s.', 'blob-mimes'),
			'<code>' . \esc_html__('Copy', 'blob-mimes') . '</code>',
			'<a href="https://github.com/Blobfolio/righteous-mimes/issues/new" target="_blank" rel="noopener">Github</a>',
			'<a href="https://wordpress.org/support/plugin/blob-mimes/" target="_blank" rel="noopener">WordPress</a>'
		); ?></p>

		<p>(<?php echo \esc_html__('And if you are able to, please also attach or link to example file(s).', 'blob-mimes'); ?>)</p>

		<p><?php echo \esc_html__('Thank you!', 'blob-mimes'); ?></p>
	</div><!-- .inside -->
</div><!-- .postbox -->
