<?php
/**
 * Lord of the Files: File Settings
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm;

use blobfolio\wp\bm\admin\settings;

// Do not execute directly.
if (! \defined('ABSPATH')) {
	exit;
}

// This is easier to build from an array.
$out = array(
	settings::VALIDATE_TYPES=>array(
		'flag'=>settings::VALIDATE_TYPES,
		'status'=>!! settings::get(settings::VALIDATE_TYPES),
		'constant'=>'LOTF_NO_VALIDATE_TYPES',
		'title'=>\esc_html__('Fix Upload Type Validation', 'blob-mimes'),
		'description'=>array(
			\esc_html__('WordPress is currently unable to consistently validate file content across platforms, but tries to do so anyway. As a result, sometimes good files are mistakenly rejected, and sometimes bad files are mistakenly allowed.', 'blob-mimes'),
			\esc_html__("Lord of the Files fills in a lot of the gaps, increasing WordPress' overall type-awareness more than 20-fold, making verification results more consistent and accurate.", 'blob-mimes'),
			\esc_html__('Unless your site has no upload capabilities, you should enable this option.', 'blob-mimes'),
		),
	),

	settings::SANITIZE_SVGS=>array(
		'flag'=>settings::SANITIZE_SVGS,
		'status'=>!! settings::get(settings::SANITIZE_SVGS),
		'constant'=>'LOTF_NO_SANITIZE_SVGS',
		'title'=>\esc_html__('Sanitize SVG Uploads', 'blob-mimes'),
		'description'=>array(
			\esc_html__("WordPress has no means of protecting sites from malicious SVG images. Enable this option to sanitize new SVG images as they're uploaded to make sure everything is on the up-and-up!", 'blob-mimes'),
			\sprintf(
				\esc_html__('More granular controls for this feature are available in the form of filter hooks. See the %s for more information.', 'blob-mimes'),
				\sprintf(
					'<a href="%s">%s</a>',
					\esc_url(\admin_url('tools.php?page=blob-mimes-reference')),
					\esc_html__('developer reference', 'blob-mimes')
				)
			),
		),
	),
);

// We don't need to cover SVGs if they're not enabled.
if (false === \array_search('image/svg+xml', \get_allowed_mime_types(), true)) {
	unset($out[settings::SANITIZE_SVGS]);
}

?>
<table class="widefat lotf__settings_table">
	<thead>
		<tr>
			<th><?php echo \esc_html__('Enable', 'blob-mimes'); ?></th>
			<th><?php echo \esc_html__('Feature', 'blob-mimes'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($out as $setting) { ?>
			<tr class="lotf__setting_row <?php echo $setting['status'] ? 'is-active' : ''; ?>">
				<td>
					<input
						id="<?php echo \esc_attr($setting['constant']); ?>"
						type="checkbox"
						value="<?php echo \esc_attr($setting['constant']); ?>"
						name="<?php echo \esc_attr($setting['constant']); ?>"
						class="lotf__setting"
						<?php echo $setting['status'] ? 'checked' : ''; ?>
					/>
				</td>
				<td>
					<p class="lotf__setting_label">
						<label for="<?php echo \esc_attr($setting['constant']); ?>">
							<strong><?php echo $setting['title']; ?></strong>
						</label>
					</p>

					<p><?php echo \implode('</p><p>', $setting['description']); ?></p>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<div class="postbox">
	<h2 class="hndle"><span><?php echo \esc_html__('Instructions', 'blob-mimes'); ?></span></h2>
	<div class="inside">
		<div id="lotf-settings-default" style="display: none;">
			<p><?php echo \esc_html__("You've chosen all the default settings.", 'blob-mimes'); ?></p>

			<p><?php \printf(
				\esc_html__('If you had previously defined overrides in %s, simply remove them.', 'blob-mimes'),
				'<code>wp-config.php</code>'
			); ?></p>

			<p><?php echo \esc_html__("Otherwise you're good to go!", 'blob-mimes'); ?></p>
		</div>

		<div id="lotf-settings-override" style="display: none;">
			<p><?php \printf(
				\esc_html__('Copy and paste the following to %s. If there are any existing overrides defined, remove them.', 'blob-mimes'),
				'<code>' . \ABSPATH . 'wp-config.php</code>',
				'<strong>Lord of the Files</strong>'
			); ?></p>

			<pre id="lotf-settings"></pre>
		</div>
	</div><!--.inside -->
</div><!-- .postbox -->
