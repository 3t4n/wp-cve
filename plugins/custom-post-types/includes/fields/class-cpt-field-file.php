<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_File extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'file';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'File upload', 'custom-post-types' );
	}

	/**
	 * @return array[]
	 */
	public static function get_extra() {
		return array(
			array( //types
				'key'      => 'types',
				'label'    => __( 'Type', 'custom-post-types' ),
				'info'     => false,
				'required' => false,
				'type'     => 'select',
				'extra'    => array(
					'placeholder' => __( 'Image (all extensions)', 'custom-post-types' ) . ' - ' . __( 'Default', 'custom-post-types' ),
					'multiple'    => true,
					'options'     => array(
						'image'              => __( 'Image (all extensions)', 'custom-post-types' ) . ' - ' . __( 'Default', 'custom-post-types' ),
						'audio'              => __( 'Audio (all extensions)', 'custom-post-types' ),
						'video'              => __( 'Video (all extensions)', 'custom-post-types' ),
						'application/pdf'    => __( '.pdf', 'custom-post-types' ),
						'application/zip'    => __( '.zip', 'custom-post-types' ),
						'text/plain'         => __( '.txt', 'custom-post-types' ),
						'application/msword' => __( '.doc', 'custom-post-types' ),
					),
				),
				'wrap'     => array(
					'width'  => '',
					'class'  => '',
					'id'     => '',
					'layout' => '',
				),
			),
		);
	}

	/**
	 * @param $input_name
	 * @param $input_id
	 * @param $field_config
	 *
	 * @return false|string
	 */
	public static function render( $input_name, $input_id, $field_config ) {
		$types = ! empty( $field_config['extra']['types'] ) ? $field_config['extra']['types'] : array( 'image' );
		ob_start();
		?>
		<div class="cpt-file-section"
			data-type="<?php echo htmlspecialchars( wp_json_encode( $types ), ENT_QUOTES, 'UTF-8' ); ?>">
			<input name="<?php echo $input_name; ?>" value="<?php echo $field_config['value']; ?>" class="cpt-hidden-input">
			<div class="cpt-file-wrap">
				<div class="cpt-file-preview">
					<?php
					echo $field_config['value'] && wp_get_attachment_image( $field_config['value'], 'thumbnail', false, array() ) ? wp_get_attachment_image( $field_config['value'], 'thumbnail', false, array() ) : '<img src="" width="150" height="150" style="display: none;" class="attachment-thumbnail size-thumbnail" alt="" loading="lazy">';
					?>
				</div>
				<div class="cpt-file-actions"
					title="<?php echo $field_config['value'] && get_post( $field_config['value'] ) ? basename( get_attached_file( $field_config['value'] ) ) : __( 'Choose', 'custom-post-types' ); ?>">
					<div class="file-name"
						dir="rtl"><?php echo $field_config['value'] && get_post( $field_config['value'] ) ? basename( get_attached_file( $field_config['value'] ) ) : ''; ?></div>
					<div class="buttons">
						<button class="button cpt-file-button button-primary cpt-file-upload" id="<?php echo $input_id; ?>"
								title="<?php _e( 'Choose', 'custom-post-types' ); ?>"
								aria-label="<?php _e( 'Choose', 'custom-post-types' ); ?>">
							<span class="dashicons dashicons-upload"></span>
						</button>
						<button class="button cpt-file-button button-secondary cpt-file-remove" <?php echo empty( $field_config['value'] ) ? ' disabled="disabled"' : ''; ?>
								title="<?php _e( 'Remove', 'custom-post-types' ); ?>"
								aria-label="<?php _e( 'Remove', 'custom-post-types' ); ?>">
							<span class="dashicons dashicons-trash"></span>
						</button>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param $meta_value
	 *
	 * @return string
	 */
	public static function sanitize( $meta_value ) {
		return get_post( $meta_value ) ? $meta_value : '';
	}

	/**
	 * @param $meta_value
	 *
	 * @return false|string
	 */
	public static function get( $meta_value ) {
		$file_type  = get_post_mime_type( $meta_value );
		$file_types = explode( '/', $file_type );
		$main_type  = isset( $file_types[0] ) ? $file_types[0] : false;
		if ( $main_type && 'image' == $main_type ) { //phpcs:ignore Universal.Operators.StrictComparisons
			return wp_get_attachment_image( $meta_value, 'full' );
		}
		return wp_get_attachment_url( $meta_value );
	}
}

cpt_fields()->add_field_type( CPT_Field_File::class );
