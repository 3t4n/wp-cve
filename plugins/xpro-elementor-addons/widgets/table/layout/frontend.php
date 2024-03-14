<?php
use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
?>
<table class="xpro-table<?php echo ( 'yes' === $settings['responsive_mobile'] ) ? ' xpro-table-responsive' : ''; ?>">
	<thead class="xpro-table-head">
	<tr class="xpro-table-head-row">
		<?php
		foreach ( $settings['columns_data'] as $index => $column_cell ) :
			$column_repeater_key = $this->get_repeater_setting_key( 'column_span', 'columns_data', $index );

			$this->add_render_attribute( $column_repeater_key, 'class', 'xpro-table-head-column-cell' );
			$this->add_render_attribute( $column_repeater_key, 'class', 'elementor-repeater-item-' . $column_cell['_id'] );

			if ( $column_cell['column_span'] ) {
				$this->add_render_attribute( $column_repeater_key, 'colspan', $column_cell['column_span'] );
			}
			?>
			<th <?php $this->print_render_attribute_string( $column_repeater_key ); ?>>
				<div class="xpro-table-head-column-cell-inner">
					<span class="xpro-table-head-column-cell-content"><?php echo wp_kses_post( $column_cell['column_name'] ); ?></span>
					<?php if ( 'icon' === $column_cell['column_media'] && ! empty( $column_cell['column_icons'] ) ) : ?>
						<span class="xpro-table-head-column-cell-icon">
								<?php Icons_Manager::render_icon( $column_cell['column_icons'] ); ?>
							</span>
					<?php endif; ?>

					<?php
					if ( ! empty( $column_cell['column_image']['url'] ) || ! empty( $column_cell['column_image']['id'] ) ) :
						$this->add_render_attribute( 'column_image', 'src', $column_cell['column_image']['url'] );
						$this->add_render_attribute( 'column_image', 'alt', Control_Media::get_image_alt( $column_cell['column_image'] ) );
						$this->add_render_attribute( 'column_image', 'title', Control_Media::get_image_title( $column_cell['column_image'] ) );
						?>
						<span class="xpro-table-head-column-cell-icon">
								<?php
								echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $column_cell, 'column_thumbnail', 'column_image' ) );
								?>
							</span>
					<?php endif; ?>
				</div>
			</th>
		<?php endforeach; ?>
	</tr>
	</thead>
	<tbody class="xpro-table-body">
	<?php
	$table_row_count = count( $table_row );
	for ( $i = 0; $i < $table_row_count; $i ++ ) :
		?>
		<tr class="xpro-table-body-row">
			<?php
			$table_cell_count = count( $table_cell );
			for ( $j = 0; $j < $table_cell_count; $j ++ ) :
				if ( $table_row[ $i ]['id'] === $table_cell[ $j ]['row_id'] ) :
					$row_span_repeater_key = $this->get_repeater_setting_key( 'row_span', 'rows_data', $table_cell[ $j ]['row_id'] . $i . $j );
					$this->add_render_attribute( $row_span_repeater_key, 'class', 'xpro-table-body-row-cell' );
					$this->add_render_attribute( $row_span_repeater_key, 'class', 'elementor-repeater-item-' . $table_cell[ $j ]['repeater_id'] );
					if ( ' ' !== $table_cell[ $j ]['row_column_span'] ) {
						$this->add_render_attribute( $row_span_repeater_key, 'colspan', $table_cell[ $j ]['row_column_span'] );
					}
					if ( ! empty( $table_cell[ $j ]['row_span'] ) ) {
						$this->add_render_attribute( $row_span_repeater_key, 'rowspan', $table_cell[ $j ]['row_span'] );
					}

					// link
					if ( ! empty( $table_cell[ $j ]['cell_link']['url'] ) ) {
						$row_link_key = $this->get_repeater_setting_key( 'cell_link', 'rows_data', $table_cell[ $j ]['row_id'] . $i . $j );
						$this->add_link_attributes( $row_link_key, $table_cell[ $j ]['cell_link'] );
					}
					?>
					<td <?php $this->print_render_attribute_string( $row_span_repeater_key ); ?>>
						<div class="xpro-table-body-row-cell-inner">
							<?php if ( ! empty( $table_cell[ $j ]['cell_link']['url'] ) ) : ?>
							<a <?php $this->print_render_attribute_string( $row_link_key ); ?>>
							<?php endif; ?>
							<span class="xpro-table-body-row-cell-content">
								<?php echo wp_kses_post( $table_cell[ $j ]['title'] ); ?>
							</span>
							<?php if ( ! empty( $table_cell[ $j ]['row_icons'] ) ) : ?>
								<span class="xpro-table-body-row-cell-icon">
									<?php Icons_Manager::render_icon( $table_cell[ $j ]['row_icons'] ); ?>
								</span>
							<?php endif; ?>

							<?php
							if ( ! empty( $table_cell[ $j ]['row_image']['url'] ) || ! empty( $table_cell[ $j ]['row_image']['id'] ) ) :
								$image = wp_get_attachment_image_url( $table_cell[ $j ]['row_image']['id'], $table_cell[ $j ]['row_thumbnail_size'] );
								if ( ! $image ) {
									$image = $table_cell[ $j ]['row_image']['url'];
								}
								?>
								<span class="xpro-table-body-row-cell-icon">
									<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $table_cell[ $j ]['title'] ); ?>">
								</span>
							<?php endif; ?>
							<?php if ( ! empty( $table_cell[ $j ]['cell_link']['url'] ) ) : ?>
							</a>
							<?php endif; ?>
						</div>
					</td>
					<?php
				endif;
			endfor;
			?>
		</tr>
	<?php endfor; ?>
	</tbody>
</table>
