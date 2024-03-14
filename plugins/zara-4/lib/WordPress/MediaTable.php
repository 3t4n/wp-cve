<?php
if ( ! class_exists( 'Zara4_WordPress_MediaTable' ) ) {


  /**
   * Class Zara4_WordPress_MediaTable
   */
  class Zara4_WordPress_MediaTable {

    const COLUMN__ORIGINAL_SIZE = 'original_size';
    const COLUMN__ZARA4_SIZE = 'zara4_size';


    /**
     * Add 'Original Size' and 'Zara 4 Size' columns to media table.
     *
     * @param $columns
     * @return array
     */
    public static function add_media_columns( $columns ) {
      $columns[self::COLUMN__ORIGINAL_SIZE] = 'Original Size';
      $columns[self::COLUMN__ZARA4_SIZE] = 'Zara 4 Size';
      return $columns;
    }


    /**
     * Fill the media library columns added by Zara 4.
     *
     * @param $column_name
     * @param $attachment_id
     */
    public static function fill_media_columns( $column_name, $attachment_id ) {

      // 'Original Size' Column
      if ( self::COLUMN__ORIGINAL_SIZE == $column_name ) {
        self::original_size_column( $attachment_id );
      }

      // 'Zara 4 Size' Column
      else if ( self::COLUMN__ZARA4_SIZE == $column_name ) {
        self::zara4_size_column( $attachment_id );
      }

    }


    /**
     * @param $attachment_id
     */
    private static function original_size_column( $attachment_id ) {

      $image = new Zara4_WordPress_Attachment_Attachment( $attachment_id );

      echo '<span id="zara4-original-size-' . $attachment_id . '">' . Zara4_WordPress_Util::format_bytes( $image->original_file_size() ) . '</span>';
    }


    /**
     * @param $attachment_id
     */
    private static function zara4_size_column( $attachment_id ) {

      //
      // Not an image - can't be compressed
      //
      if ( ! Zara4_WordPress_Attachment_Attachment::id_is_image( $attachment_id ) ) {
        echo 'Cannot be compressed by Zara 4';
        return;
      }

      // --- --- ---

      $image = new Zara4_WordPress_Attachment_Attachment( $attachment_id );

      $compressed = $image->atleast_one_size_is_compressed();
      $should_be_excluded_from_bulk_compression = $image->should_be_excluded_from_bulk_compression();

      $response = $image->generate_response();
      /** @noinspection PhpUnusedLocalVariableInspection */
      $original_file_size = $response['original-file-size'];
      $compressed_file_size = $response['compressed-file-size'];
      /** @noinspection PhpUnusedLocalVariableInspection */
      $bytes_saved = $response['bytes-saved'];
      $percentage_saving = $response['percentage-saving'];
      /** @noinspection PhpUnusedLocalVariableInspection */
      $has_backup = $response['has-backup'];


      // --- --- ---

      echo '<div class="zara-4 size-column" id="zara4-optimise-wrapper-' . $attachment_id . '" data-id="' . $attachment_id . '">';

      // Loading
      echo '<div class="loading-wrapper hidden"><img src="' . ZARA4_PLUGIN_BASE_URL . '/img/loading.gif' . '"/> Please wait</div>';

      // Optimised stats
      echo '<div class="restore-original-wrapper' . ( $compressed ? '' : ' hidden' ) . '">';
      echo '<div class="compressed-size">' . Zara4_WordPress_Util::format_bytes( $compressed_file_size ) . '</div>';
      echo '<div>Saved <span class="percentage-saving">' . number_format( floatval( $percentage_saving ), 1 ) . '</span>%</div>';
      echo '<div class="zara-4 compression-info"><span class="zara-4 a">Advanced Compression</span></div>';

      // Restore link
      echo '<div class="mt-10 zara-4 original-image-group' . ( ! $image->has_backup() ? ' hidden' : '') . '">';
      echo '<div><span data-id="' . $attachment_id . '" class="zara-4 link restore-original">Restore original</span></div>';
      echo '<div><span data-id="' . $attachment_id . '" class="delete zara-4 link delete-original">Delete original</span></div>';
      echo '</div>';

      echo '</div>';


      echo '<div class="zara-4 optimise-wrapper' . ( $compressed ? ' hidden' : '' ) . '">';
      echo '<div class="btn-group">';
      echo '<button id="zara4-optimise-btn-' . $attachment_id . '" type="button" data-id="' . $attachment_id . '" class="zara-4 optimise button' . ($should_be_excluded_from_bulk_compression ? '' : ' button-primary') . '">Compress Now</button>';
      echo '<button class="btn button dropdown-toggle' . ($should_be_excluded_from_bulk_compression ? '' : ' button-primary') . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>';
      echo '<ul class="dropdown-menu">';
      echo '<li><span class="zara-4 a compression-info">Advanced compress</span></li>';
      echo '<li class="zara-4 exclude-from-bulk-compress' . ($should_be_excluded_from_bulk_compression ? ' hidden' : '') . '"><span>Exclude from bulk compress</span></li>';
      echo '<li class="zara-4 include-in-bulk-compress' . ($should_be_excluded_from_bulk_compression ? '' : ' hidden') . '"><span>Include in bulk compress</span></li>';
      echo '</ul>';
      echo '</div>';
      echo '<div class="excluded_from_bulk_compression_label_wrapper' . ($should_be_excluded_from_bulk_compression ? '' : ' hidden') . '">';
      echo '<div style="margin-top: 10px">- Excluded from compression</div>';
      echo '</div>';
      echo '</div>';


      echo '</div>';

    }


  }

}