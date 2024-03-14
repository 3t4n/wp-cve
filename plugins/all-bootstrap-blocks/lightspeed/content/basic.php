<?php $justify = 'justify-content-' . lightspeed_get_attribute( 'content_alignment', $default_align ); ?>

<div class="row <?php echo 'text-' . lightspeed_get_attribute( 'content_alignment', $default_align ) ?> <?php echo $justify ?>">
	
	<div class="col <?php echo $col_class ?>">
		
		<?php lightspeed_heading( $heading_level ) ?>

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