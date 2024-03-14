<div class="pb-settings-label">
	<span class="control"><?php echo wp_kses_post( $field['name'] ); ?>
	<select name="<?php echo esc_attr( $field['code'] ); ?>" class="js-serialize js-update-additional-data">
	<?php foreach ( $field['values'] as $k => $v ) : ?>
		<option value="<?php echo esc_attr( $k ); ?>"><?php echo wp_kses_post( $v['name'] ); ?></option>
	<?php endforeach ?>
	</select>

	<div class="additional-data additional-data-<?php echo esc_attr( $field['code'] ); ?>">
	<?php foreach ( $field['values'] as $k => $v ) : ?>
		<?php if ( array_key_exists( 'title', $v ) ) : ?>
		<div class="additional-data-item-<?php echo esc_attr( $k ); ?>">
			<?php $title = explode( '|', $v['title'] ); ?>
			<?php $description = explode( '|', $v['description'] ); ?>
			<?php $social = explode( '|', $v['social'] ); ?>
			<h4><?php esc_html_e( 'Suggested captions alignment', 'photoblocks' ); ?>:</h4>
			<p><strong><?php esc_html_e( 'Titles', 'photoblocks' ); ?></strong>: <?php echo esc_html( $title[0] ); ?> / <?php echo esc_html( $title[1] ); ?></p>
			<p><strong><?php esc_html_e( 'Descriptions', 'photoblocks' ); ?></strong>: <?php echo esc_html( $description[0] ); ?> / <?php echo esc_html( $description[1] ); ?></p>
			<p><strong><?php esc_html_e( 'Social icons', 'photoblocks' ); ?></strong>: <?php echo esc_html( $social[0] ); ?> / <?php echo esc_html( $social[1] ); ?></p>
			<a class="pb-button pb-small-button pb-button-green" onclick="PBAdmin.applyAlignments({ title: ['<?php echo esc_attr( $title[0] ); ?>', '<?php echo esc_attr( $title[1] ); ?>'], description: ['<?php echo esc_attr( $description[0] ); ?>', '<?php echo esc_attr( $description[1] ); ?>'], social: ['<?php echo esc_attr( $social[0] ); ?>', '<?php echo esc_attr( $social[1] ); ?>'] })"><?php esc_html_e( 'Apply alignments', 'photoblocks' ); ?></a>
		</div>
		<?php endif ?>
	<?php endforeach ?>
	</div>
</div>
<div class="pb-settings-description"><p><?php echo wp_kses_post( $field['description'] ); ?></p></div>
