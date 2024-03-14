<div id="reuseb_geobox_preview"></div>

<?php
/**
 * Localize the updated data from database
 */
  // use Reuse\Builder;
  $preview_array = new Reuse\Builder\Provider();
  $preview_fields = $preview_array->geobox_preview_array();
  $geobox_preview = get_post_meta($post->ID, '_reuseb_geobox_preview', true );
  wp_localize_script( 'reuseb_geobox', 'REUSEB_ADMIN',
    apply_filters('reuseb_generator_localize_args', array(
      'GEOBOX_PREVIEW' => $geobox_preview,
      'fields' => apply_filters('reuseb_geobox_preview_fileds', $preview_fields),
  ) ));
?>

<input type="hidden" id="_reuseb_geobox_preview" name="_reuseb_geobox_preview" value="<?php echo esc_attr(isset($geobox_preview) ? $geobox_preview : null) ?>">
