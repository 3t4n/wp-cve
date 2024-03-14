<fieldset id="pb-grid-settings" class="serialize">
	<h3><?php esc_html_e( 'Grid settings', 'photoblocks' ); ?></h3>
	<input type="hidden" name="id" value="<?php echo isset( $_GET['id'] ) ? intval( $_GET['id'] ) : ''; ?>">
	<!--<div class="field">
		<label><?php esc_html_e( 'Gallery name', 'photoblocks' ); ?> <input type="text" name="name" class="js-serialize"></label>
	</div>-->
	<div class="field">
		<label><?php esc_html_e( 'Image board width (px)', 'photoblocks' ); ?> <input type="text" name="abwidth" value="800" class="abwidth js-serialize"></label>
	</div>
	<!--<div class="field">
		<label><?php esc_html_e( 'Gallery width (px or %)', 'photoblocks' ); ?> <input type="text" name="width" value="" class="width js-serialize"></label>
	</div>-->
	<div class="field">
		<label><?php esc_html_e( 'Columns', 'photoblocks' ); ?> <input type="number" name="columns" value="" class="columns js-serialize"></label>
	</div>
	<div class="field">
		<label><?php esc_html_e( 'Padding between images', 'photoblocks' ); ?> <input type="number" value="" name="padding" class="padding js-serialize"></label>
	</div>
	<!--<div class="field">
		<label><input type="checkbox" name="show_empty_overlays" value="1" class="show_empty_overlays js-serialize"> <?php esc_html_e( 'Show empty overlays', 'photoblocks' ); ?> </label>
	</div>
	<div class="field">
		<label><input type="checkbox" name="show_ab_captions" value="1" class="show_ab_captions js-serialize"> <?php esc_html_e( 'Show captions on image board', 'photoblocks' ); ?> </label>
	</div>-->
	<a href="#" onclick="PhotoBlocks.updateGrid()" title="<?php esc_attr_e( 'Done', 'photoblocks' ); ?>" class="button close-drawer" tabindex="-1"><?php esc_html_e( 'Done', 'photoblocks' ); ?></a>
</fieldset>
