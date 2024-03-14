<div id="bucket-shortcode-display">
	<?php
		$pid = $GLOBALS['post_ID'];
		$title = get_the_title($pid);

		if ($title != 'Auto Draft'):
	?>
		<p>Copy and paste this shortcode into any Post, Page or even another Bucket. Yeah that's right... a Bucket within a Bucket.</p>
		<div class="bucket-shortcode">
			<input type="text" readonly value='[bucket id="<?php echo $pid; ?>" title="<?php echo $title; ?>"]' onFocus="this.select()" />
		</div>
	<?php else: ?>
		<p>Save the Bucket and a shortcode will be generated.</p>
	<?php endif; ?>
</div>