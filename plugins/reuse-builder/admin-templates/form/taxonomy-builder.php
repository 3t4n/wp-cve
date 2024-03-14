<div id="reuseb_taxonomy_metabox"></div>
<?php
/**
 * Localize the updated data from database
 */
wp_localize_script( 'reuseb_taxonomy_generator', 'REUSEB_ADMIN', apply_filters('reuseb_admin_generator_localize_args', array( 'UPDATED_TAX' => get_post_meta( $post->ID, '_reuse_builder_taxonomies_data', true ) )
) );
?>
<input type="hidden" id="_reuseb_taxonomies_data" name="_reuse_builder_taxonomies_data">
