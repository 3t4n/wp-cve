<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 * Field: backup
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'KIANFR_Field_backup' ) ) {
	class KIANFR_Field_backup extends KIANFR_Fields
	{

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			$unique = $this->unique;
			$nonce  = wp_create_nonce( 'kianfr_backup_nonce' );
			$export = add_query_arg( [
				'action' => 'kianfr-export',
				'unique' => $unique,
				'nonce'  => $nonce,
			], admin_url( 'admin-ajax.php' ) );

			echo $this->field_before();

			echo '<textarea name="kianfr_import_data" class="kianfr-import-data"></textarea>';
			echo '<button type="submit" class="button button-primary kianfr-confirm kianfr-import" data-unique="' . esc_attr( $unique ) . '" data-nonce="' . esc_attr( $nonce ) . '">' . esc_html__( 'Import', 'kianfr' ) . '</button>';
			echo '<hr />';
			echo '<textarea readonly="readonly" class="kianfr-export-data">' . esc_attr( json_encode( get_option( $unique ) ) ) . '</textarea>';
			echo '<a href="' . esc_url( $export ) . '" class="button button-primary kianfr-export" target="_blank">' . esc_html__( 'Export & Download', 'kianfr' ) . '</a>';
			echo '<hr />';
			echo '<button type="submit" name="kianfr_transient[reset]" value="reset" class="button kianfr-warning-primary kianfr-confirm kianfr-reset" data-unique="' . esc_attr( $unique ) . '" data-nonce="' . esc_attr( $nonce ) . '">' . esc_html__( 'Reset', 'kianfr' ) . '</button>';

			echo $this->field_after();
		}

	}
}
