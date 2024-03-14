<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'WPPSGS_Field_backup' ) ) {
  class WPPSGS_Field_backup extends WPPSGS_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $unique = $this->unique;
      $nonce  = wp_create_nonce( 'wppsgs_backup_nonce' );
      $export = add_query_arg( array( 'action' => 'wppsgs-export', 'unique' => $unique, 'nonce' => $nonce ), admin_url( 'admin-ajax.php' ) );

      echo $this->field_before();

      echo '<textarea name="wppsgs_import_data" class="wppsgs-import-data"></textarea>';
      echo '<button type="submit" class="button button-primary wppsgs-confirm wppsgs-import" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html__( 'Import', 'wppsgs' ) .'</button>';
      echo '<hr />';
      echo '<textarea readonly="readonly" class="wppsgs-export-data">'. esc_attr( json_encode( get_option( $unique ) ) ) .'</textarea>';
      echo '<a href="'. esc_url( $export ) .'" class="button button-primary wppsgs-export" target="_blank">'. esc_html__( 'Export & Download', 'wppsgs' ) .'</a>';
      echo '<hr />';
      echo '<button type="submit" name="wppsgs_transient[reset]" value="reset" class="button wppsgs-warning-primary wppsgs-confirm wppsgs-reset" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html__( 'Reset', 'wppsgs' ) .'</button>';

      echo $this->field_after();

    }

  }
}
