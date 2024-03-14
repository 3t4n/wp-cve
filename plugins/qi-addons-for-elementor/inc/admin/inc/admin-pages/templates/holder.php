<div id="qodef-page" class="qodef-admin-page qodef-dashboard-admin qodef-admin-content-grid">
	<?php $this_object->get_header(); ?>
	<div class="qodef-admin-content qodef-admin-grid qodef-admin-layout--columns qodef-admin-col-num--2 qodef-admin-gutter--normal">
		<div class="qodef-admin-grid-inner">
			<div class="qodef-admin-container qodef-admin-grid-item qodef-admin-col--9">
				<?php $this_object->get_content(); ?>
			</div>
			<?php $this_object->get_sidebar(); ?>
		</div>
	</div>
	<div class="qodef-admin-grid qodef-admin-layout--columns qodef-admin-col-num--2 qodef-admin-gutter--normal">
		<div class="qodef-admin-grid-inner">
			<div class="qodef-admin-grid-item qodef-admin-col--9">
				<?php $this_object->get_footer(); ?>
			</div>
		</div>
	</div>
</div>
