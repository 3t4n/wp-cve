<div class="row row-cols-1 row-cols-lg-2 align-items-start justify-content-between <?php echo 'text-' . lightspeed_get_attribute( 'content_alignment', $default_align ) ?>">
	
	<div class="col <?php echo $col_class ?>">
		
		<?php lightspeed_heading( $heading_level ) ?>

	</div>

	<div class="col col-lg-6 <?php echo ( !trim( strip_tags( lightspeed_get_attribute( 'introduction', null ) ) ) && empty( lightspeed_get_attribute( 'columns', array() ) ) ) ? 'text-end' : '' ?>">

		<?php lightspeed_introduction() ?>

		<?php if ( lightspeed_get_attribute( 'columns', array() ) ) : ?>
			<div class="row row-cols-1 row-cols-md-<?php echo count( lightspeed_get_attribute( 'columns', array() ) ) ?> ">
				<?php foreach ( lightspeed_get_attribute( 'columns', array() ) as $column_key => $column ) : ?>
					<div class="col mb-4 position-relative">
						<?php lightspeed_item( $column, false, true ) ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php lightspeed_cta() ?>

	</div>

</div>
<?php if ( lightspeed_get_attribute( 'include_cta', false ) ) : ?>
	<div style="height: 50px;"></div>
<?php endif; ?>