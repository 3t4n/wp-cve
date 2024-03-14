<div class="apeGalleryFields">
	<?php if ($contentBefore) : ?>
		<div class="metabox content-before row">
			<div class="large-12 columns">
				<?php echo $contentBefore; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ($content) : ?>
		<div class="metabox content row">
			<div class="large-12 columns">
				<?php echo $content; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php foreach ($fields as $field) : ?>
		<div id="wrap-field-<?php echo $field['id']; ?>"
		     class="row metabox wrap-field <?php echo "{$field['type']}-{$field['view']}"; ?>" 
		     <?php if( $field['is_hide'] ) echo ' style="display:none;"';?> 
		>
			<?php if ($field['is_lock']) : ?>
				<div class="lock-overlay  twoj-gallery-option-premium">
					<div class="lock-message">
						<?php echo __('Premium function', 'gallery-images-ape'); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ($field['is_new']) : ?>
				<button class="button warning tiny twoj-gallery-option-new"><?php echo __('New Feature', 'gallery-images-ape'); ?></button>
			<?php endif; ?>


			<?php if ($field['contentBefore']) : ?>
				<div class="content-before small-12 columns">
					<?php echo $field['contentBefore']; ?>
				</div>
			<?php endif; ?>

			<?php if ($field['content']) : ?>
				<div class="content small-12 columns">
					<?php echo $field['content']; ?>
				</div>
			<?php endif; ?>

			<?php echo $field['field']; ?>

			<?php if ($field['contentAfter']) : ?>
				<div class="content-after small-12 columns">
					<?php echo $field['contentAfter']; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ($field['contentAfterBlock']) : ?>
			<?php echo $field['contentAfterBlock']; ?>
		<?php endif; ?>
		
	<?php endforeach; ?>

	<?php if ($contentAfter) : ?>
		<div class="metabox content-after row">
			<div class=" large-12 columns">
				<?php echo $contentAfter; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
