<?php

/**
 * Generate custom term meta on request
 */

namespace Reuse\Builder;

class Reuse_Generate_Term_Meta
{
	protected static $_instance = null;

	public function __construct()
	{

		$meta_query_args = array(
			'post_type' => 'reuseb_term_metabox',
			'post_per_page' => -1,
		);
		$meta_query = get_posts($meta_query_args);

		foreach ($meta_query as $query) {

			$taxonomy_select = get_post_meta($query->ID, 'reuseb_taxonomy_select', true);
			$form_builder = get_post_meta($query->ID, 'formBuilder', true);

			// create new fields
			add_action($taxonomy_select . '_add_form_fields', array($this, 'add_term_meta_field'), 10, 2);

			// save created fields data
			add_action('created_' . $taxonomy_select, array($this, 'save_term_fields_meta'), 10, 2);

			// edit saved fields
			add_action($taxonomy_select . '_edit_form_fields', array($this, 'edit_saved_term_field'), 10, 2);

			// update edited fields
			add_action('edited_' . $taxonomy_select, array($this, 'update_edited_meta'), 10, 2);


			// add column
			// add_filter( 'manage_edit-' . $taxonomy_select . '_columns', array( $this, 'add_term_meta_column' ) );

			// show column content
			// add_filter( 'manage_' . $taxonomy_select . '_custom_column', array( $this, 'add_term_meta_column_content' ), 10, 3 );

			// coulmn sortable
			// add_filter( 'manage_edit-' . $taxonomy_select . '_sortable_columns', array( $this, 'add_term_meta_column_sortable' ) );
		}
	}

	public function add_term_meta_column_sortable($sortable)
	{
		// _log($sortable);
		// $sortable[ 'feature_group' ] = 'feature_group';

		$meta_query_args = array(
			'post_type' => 'reuseb_term_metabox',
			'post_per_page' => -1,
		);
		$meta_query = get_posts($meta_query_args);

		foreach ($meta_query as $query) {

			$taxonomy_select = get_post_meta($query->ID, 'reuseb_taxonomy_select', true);
			$form_builder = get_post_meta($query->ID, 'formBuilder', true);
			self::$_instance = $form_builder;

			foreach ($form_builder['fields'] as $field) {
				$sortable[$field['id']] = $field['label'];
			}
		}
		return $sortable;
	}

	public function add_term_meta_column_content($content, $column_name, $term_id)
	{

		$term_id = absint($term_id);
		$term_meta_data = get_term_meta($term_id, '_reuseb_term_meta_data', true);

		if (!empty($term_meta_data)) {
			foreach (json_decode($term_meta_data, true) as $key => $value) {

				if ($column_name !== $key) {
					return $content;
				}

				$all_fields = self::$_instance['fields'];

				foreach ($all_fields as $field) {
					// _log($field);
					// _log($value);
					if ($column_name === $key && $key === $field['id']) {
						switch ($field['type']) {
							case 'imageupload':
								$content .= '<img src="' . $value[0]['url'] . '" >';
								break;

							case 'fileupload':
								$content .= '<a target="_blank" href="' . $value[0]['url'] . '">' . $value[0]['name'] . '</a>';
								break;

							default:
								$content .= $value;
								break;
						}
					}
				}
			}
			// $content .= esc_attr( $feature_groups[ $feature_group ] );

		}

		return $content;
	}

	public function add_term_meta_column($columns)
	{

		$meta_query_args = array(
			'post_type'     => 'reuseb_term_metabox',
			'post_per_page' => -1,
		);
		$meta_query = get_posts($meta_query_args);

		foreach ($meta_query as $query) {

			$taxonomy_select = get_post_meta($query->ID, 'reuseb_taxonomy_select', true);
			$form_builder = get_post_meta($query->ID, 'formBuilder', true);

			foreach ($form_builder['fields'] as $field) {
				$columns[$field['id']] = $field['label'];
			}
		}


		return $columns;
	}



	public function edit_saved_term_field($term, $taxonomy)
	{
?>
		<tr class="form-field term-custom-wrap">
			<th scope="row"><label for="custom-group"><?php _e('Custom Fileds', 'reuse-builder'); ?></label></th>
			<td>
				<?php
				$term_meta_data = get_term_meta($term->term_id, '_reuseb_term_meta_data', true);
				include_once(REUSE_BUILDER_DIR . '/admin-templates/form/edit-term-meta.php');
				?>
			</td>
		</tr>
<?php
	}

	public function add_term_meta_field($taxonomy)
	{
		include_once(REUSE_BUILDER_DIR . '/admin-templates/form/add-term-meta.php');
	}

	public function save_term_fields_meta($term_id, $tt_id)
	{
		if (isset($_POST['_reuseb_term_meta_data']) && '' !== $_POST['_reuseb_term_meta_data']) {

			$term_meta_data = json_decode(stripslashes_deep($_POST['_reuseb_term_meta_data']), true);
			add_term_meta($term_id, '_reuseb_term_meta_data', $_POST['_reuseb_term_meta_data'], true);

			foreach ($term_meta_data as $key => $value) {
				add_term_meta($term_id, $key, $value, true);
			}
		}
	}


	public function update_edited_meta($term_id, $tt_id)
	{

		if (isset($_POST['_reuseb_term_meta_data']) && '' !== $_POST['_reuseb_term_meta_data']) {

			$term_meta_data = json_decode(stripslashes_deep($_POST['_reuseb_term_meta_data']), true);
			update_term_meta($term_id, '_reuseb_term_meta_data', $_POST['_reuseb_term_meta_data']);

			foreach ($term_meta_data as $key => $value) {
				update_term_meta($term_id, $key, $value);
			}
		}
	}
}
