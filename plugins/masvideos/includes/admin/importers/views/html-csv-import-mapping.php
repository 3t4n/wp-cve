<?php
/**
 * Admin View: Importer - CSV mapping
 *
 * @package MasVideos\Admin\Importers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form class="masvideos-progress-form-content masvideos-importer" method="post" action="<?php echo esc_url( $this->get_next_step_link() ); ?>">
	<header>
		<h2><?php esc_html_e( 'Map CSV fields', 'masvideos' ); ?></h2>
		<p><?php esc_html_e( 'Select fields from your CSV file to map against posts fields, or to ignore during import.', 'masvideos' ); ?></p>
	</header>
	<section class="masvideos-importer-mapping-table-wrapper">
		<table class="widefat masvideos-importer-mapping-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Column name', 'masvideos' ); ?></th>
					<th><?php esc_html_e( 'Map to field', 'masvideos' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $headers as $index => $name ) : ?>
					<?php $mapped_value = $mapped_items[ $index ]; ?>
					<tr>
						<td class="masvideos-importer-mapping-table-name">
							<?php echo esc_html( $name ); ?>
							<?php if ( ! empty( $sample[ $index ] ) ) : ?>
								<span class="description"><?php esc_html_e( 'Sample:', 'masvideos' ); ?> <code><?php echo esc_html( $sample[ $index ] ); ?></code></span>
							<?php endif; ?>
						</td>
						<td class="masvideos-importer-mapping-table-field">
							<input type="hidden" name="map_from[<?php echo esc_attr( $index ); ?>]" value="<?php echo esc_attr( $name ); ?>" />
							<select name="map_to[<?php echo esc_attr( $index ); ?>]">
								<option value=""><?php esc_html_e( 'Do not import', 'masvideos' ); ?></option>
								<option value="">--------------</option>
								<?php foreach ( $this->get_mapping_options( $mapped_value ) as $key => $value ) : ?>
									<?php if ( is_array( $value ) ) : ?>
										<optgroup label="<?php echo esc_attr( $value['name'] ); ?>">
											<?php foreach ( $value['options'] as $sub_key => $sub_value ) : ?>
												<option value="<?php echo esc_attr( $sub_key ); ?>" <?php selected( $mapped_value, $sub_key ); ?>><?php echo esc_html( $sub_value ); ?></option>
											<?php endforeach ?>
										</optgroup>
									<?php else : ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $mapped_value, $key ); ?>><?php echo esc_html( $value ); ?></option>
									<?php endif; ?>
								<?php endforeach ?>
							</select>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</section>
	<div class="masvideos-actions">
		<button type="submit" class="button button-primary button-next" value="<?php esc_attr_e( 'Run the importer', 'masvideos' ); ?>" name="save_step"><?php esc_html_e( 'Run the importer', 'masvideos' ); ?></button>
		<input type="hidden" name="file" value="<?php echo esc_attr( $this->file ); ?>" />
		<input type="hidden" name="delimiter" value="<?php echo esc_attr( $this->delimiter ); ?>" />
		<input type="hidden" name="update_existing" value="<?php echo (int) $this->update_existing; ?>" />
		<?php wp_nonce_field( 'masvideos-csv-importer' ); ?>
	</div>
</form>
