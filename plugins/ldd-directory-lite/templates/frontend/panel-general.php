<?php
/*
* File version: 2
*/
?>
<div class="container-fluid">
	<?php if (ldl()->get_option('submit_intro')): ?>
    <div class="row">
		<div class="col-md-12">
            <?php echo wpautop(ldl()->get_option('submit_intro')); ?>
		</div>
	</div>
    <?php endif; ?>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for="f_title"><?php esc_html_e('Title', 'ldd-directory-lite'); ?></label>
				<input type="text" id="f_title" class="form-control" name="n_title" value="<?php echo esc_attr(ldl_get_value('title')); ?>" required>
				<?php echo ldl_get_error('title'); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for="f_category"><?php esc_html_e('Category', 'ldd-directory-lite'); ?></label>
				<?php ldl_submit_multi_categories_dropdown( ldl_get_value('category'), 'category' ); ?>
				<?php echo ldl_get_error('category'); ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label" for="f_logo"><?php esc_html_e('Logo', 'ldd-directory-lite'); ?></label>
				<input type="file" id="f_logo" class="form-control" name="n_logo">
				<?php echo ldl_get_error('category'); ?>
			</div>
		</div>
	</div>
	<div class="row bump-down">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label" for="f_description"><?php esc_html_e('Description', 'ldd-directory-lite'); ?></label>
				<textarea id="f_description" class="form-control" name="n_description" rows="5" required><?php echo esc_textarea(ldl_get_value('description')); ?></textarea>
				<?php echo ldl_get_error('description'); ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label" for="f_summary"><?php esc_html_e('Summary', 'ldd-directory-lite'); ?></label>
				<input type="text" id="f_summary" class="form-control" name="n_summary" value="<?php echo esc_attr(ldl_get_value('summary')); ?>" required>
				<?php echo ldl_get_error('summary'); ?>
				<p class="help-block"><?php esc_html_e('Please provide a short summary of your listing that will appear in search results.', 'ldd-directory-lite'); ?></p>
			</div>
		</div>
	</div>
</div>
