<div id="reuseb_term_meta_builder_metabox_reuse_form"></div>
<div id="reuseb_term_meta_builder_metabox_form_builder"></div>

<input type="hidden" id="reuseb_term_meta_builder_metabox_output" name="_reuseb_term_meta_builder_data">
<?php
$term_meta_pre_data = get_post_meta( $post->ID, '_reuseb_term_meta_builder_data', true );
/**
 * Localize the updated data from database
 */
wp_localize_script( 'reuseb_form_builder', 'REUSEB_ADMIN', apply_filters('reuseb_admin_generator_localize_args', array( 'UPDATED_TERM_META' => $term_meta_pre_data )));
/**
 * Localize the updated data from database
 */
wp_localize_script( 'reuseb_term_meta_generator_builder', 'REUSEB_ADMIN', apply_filters('reuseb_admin_generator_localize_args', array( 'UPDATED_TERM_META' => $term_meta_pre_data )));

?>
