<?php
/**
 * Lord of the Files: Debug File Validation
 *
 * This is a simple tool allowing administrators to (temporarily) upload
 * arbitrary files to see whether or not WordPress would allow it
 * (in e.g. the Media Library) as well as more detailed information
 * about type detection, etc.
 *
 * The main body content goes here.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm;

use blobfolio\wp\bm\admin\debug;



// Print results.
if (null !== $results = debug::results()) { ?>
	<div class="postbox">
		<h2 class="hndle"><?php \printf(
			'%s: <code>%s</code>',
			\esc_html__('Results', 'blob-mimes'),
			\esc_html($results['naive_name'])
		);?></h2>
		<div class="inside">
			<?php
			// Print the status message(s) first. These are separate from
			// the debug details that would need to go into a support
			// ticket.
			if (! empty($results['msg'])) {
				echo '<p>' . \implode('<br>', $results['msg']) . '</p>';
			}

			// Format the output.
			$system = debug::system();
			$out = array(
				\esc_html__('Validation', 'blob-mimes')=>array(
					\esc_html__('Naive Name', 'blob-mimes')=>$results['naive_name'],
					\esc_html__('Naive Extension', 'blob-mimes')=>$results['naive_ext'],
					\esc_html__('Naive Type', 'blob-mimes')=>$results['naive_type'],
					\esc_html__('Magic Type', 'blob-mimes')=>$results['magic_type'],
					\esc_html__('Best Type', 'blob-mimes')=>$results['best_type'],
				),
				\esc_html__('Final', 'blob-mimes')=>array(
					\esc_html__('Name', 'blob-mimes')=>$results['name'],
					\esc_html__('Extension', 'blob-mimes')=>$results['ext'],
					\esc_html__('Type', 'blob-mimes')=>$results['type'],
					\esc_html__('Code', 'blob-mimes')=>$results['status'],
				),
				\esc_html__('System', 'blob-mimes')=>array(
					\esc_html__('Kernel', 'blob-mimes')=>$system['os'],
					\esc_html__('PHP', 'blob-mimes')=>$system['php'],
					\esc_html__('Modules', 'blob-mimes')=>\implode('; ', $system['php_ext']),
					\esc_html__('WordPress', 'blob-mimes')=>$system['wp'],
					\esc_html__('Plugins', 'blob-mimes')=>\implode('; ', $system['plugins']),
					\esc_html__('Theme', 'blob-mimes')=>$system['theme'],
				),
			);

			// Actually print the output!
			?>
			<div id="lotf-results" class="lotf__results">
				<?php
				// We're going to want to build a text-only version of
				// the validation results for copy purposes.
				$text = array();
				foreach ($out as $section=>$data) {
					$text[] = \mb_strtoupper($section, 'UTF-8') . ':';
					?>
					<div class="lotf__results_section">
						<div class="lotf__results_title"><?php echo $section; ?></div>

						<?php
						foreach ($data as $k=>$v) {
							$text[] = "  $k: $v";
							?>
							<div class="lotf__result">
								<div class="lotf__result_key"><?php echo $k; ?>:</div>
								<div class="lotf__result_value"><?php echo \esc_html($v); ?></div>
							</div>
							<?php
						}

						$text[] = '';
						?>
					</div><!-- .result -->
					<?php
				}

				// Let's add a copy link!
				$text = \trim(\implode("\n", $text));
				?>
				<button
					type="button"
					id="lotf-results-copy"
					class="lotf__results_copy button"
					data-raw="<?php echo \esc_attr($text); ?>"
				>
					<span class="lotf__results_copy_label is-copy"><?php echo \esc_html__('Copy', 'blob-mimes'); ?></span>
					<span class="lotf__results_copy_label is-copied"><?php echo \esc_html__('Copied!', 'blob-mimes'); ?></span>
				</button>
			</div>
		</div><!-- .inside -->
	</div><!-- .postbox -->
<?php } // End results. ?>

<div class="postbox">
	<h2 class="hndle"><?php echo \esc_html__('Instructions', 'blob-mimes'); ?></h2>
	<div class="inside">
		<p><?php echo \esc_html__('If WordPress is incorrectly rejecting — or mistyping or renaming — a given file "for security reasons", try uploading that same file here to help pinpoint where exactly in that process things are going wrong.', 'blob-mimes'); ?></p>

		<p><?php echo \esc_html__("Don't forget to report the issue so we can help fix it!", 'blob-mimes'); ?></p>
	</div><!-- .inside -->
</div><!-- .postbox -->
