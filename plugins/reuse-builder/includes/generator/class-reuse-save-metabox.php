<?php

/**
* Generate MetaBox Saver
*/

namespace Reuse\Builder;

class Reuse_Generate_Metabox_Saver{
	public function __construct( $args ) {
		$this->generate_metabox_saver( $args );
	}
	public function generate_metabox_saver( $args ){
		global $wpdb;
		foreach ($args as $meta_args) {
			$post_type = get_post_type($meta_args['post_id']);
			if($meta_args['post_type'] == 'user'){
				foreach( $meta_args['meta_fields'] as $key => $meta_field ) {
					if( isset( $_POST[$meta_field]) ) {
						$meta_value = $_POST[$meta_field];
						update_user_meta($meta_args['post_id'], $meta_field, $meta_value);
						$json_meta_value = json_decode( stripslashes_deep( $meta_value ), true );
						if( isset( $meta_args['has_individual'] ) && $meta_args['has_individual']) {
							if( $meta_value ) {
								$meta_object_data = json_decode( stripslashes_deep($meta_value), true );
								foreach ($meta_object_data as $key => $value) {
									update_user_meta($meta_args['post_id'], $key, $value);
								}
							}
						}
					}
				}
			}else{
				if( $post_type === $meta_args['post_type'] ){
					foreach( $meta_args['meta_fields'] as $key => $meta_field ) {
						if( isset( $_POST[$meta_field]) ) {
							$meta_value = $_POST[$meta_field];
							$json_meta_value = json_decode( stripslashes_deep( $meta_value ), true );
							$updated_pre_value = array();
							if(isset($_POST['_thumbnail_id'])){
								$featured_image_id = $_POST['_thumbnail_id'];
								$featured_image_url = wp_get_attachment_url($featured_image_id);
								$featured_image[] = array(
									'id' 	=> $featured_image_id,
									'rsid' 	=> $featured_image_id,
									'value' => $featured_image_id,
									'url' 	=> $featured_image_url,
								);
							}
							// Find Out all the registered form for this post type
							$meta_query = $wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta}
								WHERE meta_key = %s and
								meta_value = %s", 'formBuilderPostTypeSelect', $meta_args['post_type']);
								$form_list = $wpdb->get_results($meta_query, 'ARRAY_A');
								if(isset($form_list) && !empty($form_list)){
									$form_post_id = $form_list[0]['post_id'];
									//get form settings
									$form_builder_string_meta = get_post_meta($form_post_id, '_reuse_form_builder_data', true);
									$form_builder_meta_object = json_decode($form_builder_string_meta);
									$fields = $form_builder_meta_object->formBuilder->fields;
									foreach ($fields as $key => $field) {
										if($field->type == 'geobox'){
											$map_field_id = $field->id;
										}
									}
									$global_settings = $form_builder_meta_object->formBuilder->globalSettings->settings->fieldSettings;
									foreach ($global_settings as $value) {
										if($value->postDestination == 'taxonomies'){
											$fields_by_destinations['taxonomies'][] = $value;
										}
										if($value->postDestination == 'post_keys'){
											$fields_by_destinations['post_keys'][] = $value;
										}
										if($value->postDestination == 'meta_keys'){
											$fields_by_destinations['meta_keys'][] = $value;
										}
									}
									$content_post = get_post( $meta_args['post_id'] );
									if(array_key_exists('post_keys', $fields_by_destinations)){
										foreach ($fields_by_destinations['post_keys'] as $key => $post_key) {
											$updated_pre_value[$post_key->fieldKey] = $content_post->{$post_key->saveKey};
										}
									}
									if(array_key_exists('meta_keys', $fields_by_destinations)){
										foreach ($fields_by_destinations['meta_keys'] as $key => $meta_key) {
											// _log($meta_key);
											if(isset($featured_image) && $meta_key->saveKey == '_thumbnail_id'){
												$updated_pre_value[$meta_key->fieldKey] = $featured_image;
											}
											if(array_key_exists($meta_key->saveKey, $json_meta_value)){
												$updated_pre_value[$meta_key->fieldKey] = $json_meta_value[$meta_key->saveKey];
											}
										}
									}
									if(array_key_exists('taxonomies', $fields_by_destinations)){
										foreach ($fields_by_destinations['taxonomies'] as $taxonomies) {
											$all_terms_for_a_taxonomy = [];
											$terms = wp_get_post_terms($meta_args['post_id'], $taxonomies->saveKey);
											foreach ($terms as $key => $single_term) {
												$all_terms_for_a_taxonomy[] = $single_term->slug;
											}
											$updated_pre_value[$taxonomies->fieldKey] = implode(',', $all_terms_for_a_taxonomy);
										}
									}
									update_post_meta( $meta_args['post_id'], 'pre_value', $updated_pre_value );
								}
								update_post_meta( $meta_args['post_id'], $meta_field, $meta_value );
								if( isset( $meta_args['has_individual'] ) && $meta_args['has_individual']) {
									if( $meta_value ) {
										$meta_object_data = json_decode( stripslashes_deep($meta_value), true );
										foreach ($meta_object_data as $key => $value) {
											if(isset($map_field_id) && $key == $map_field_id){
												reactive_lat_long($meta_args['post_id'], $value);
											}else if ($key === 'location'){
												$this->headless_save_lat_lng($meta_args['post_id'], $value);
											}
											update_post_meta($meta_args['post_id'], $key, $value);
										}
									}
								}
							}
						}
					}
				}
			}
		}

	public function headless_save_lat_lng($post_id, $data){
		global $wpdb;
		$check_link = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}headless_lat_lng WHERE id = '" . $post_id . "'");
		if(isset($data['zipcode'])){
			$zipcode = $data['zipcode'];
		}
		if(isset($data['zip_code'])){
			$zipcode = $data['zip_code'];
		}
		if ($check_link != null) {
			if ( isset($data['lat']) && $data['lng'] )
			$wpdb->update( $wpdb->prefix.'headless_lat_lng', array(
				'lat' => $data['lat'],
				'lng' => $data['lng'],
				'state' => $data['state_long'],
				'city' => $data['city'],
				'country' => $data['country_long'],
				'country_short_name' => $data['country_short'],
				'state_short_name' => $data['country_long'],
				'zipcode' => $zipcode,
				'formatted_address' => $data['formattedAddress'],
			), array( 'id' => $post_id ),
			array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s') );
		}else{
			if ( isset($data['lat']) && $data['lng'] )
			$wpdb->insert( $wpdb->prefix.'headless_lat_lng',
			array(
				'id' => $post_id,
				'lat' => $data['lat'],
				'lng' => $data['lng'],
				'state' => $data['state_long'],
				'city' => $data['city'],
				'country' => $data['country_long'],
				'country_short_name' => $data['country_short'],
				'state_short_name' => $data['country_long'],
				'zipcode' => $zipcode,
				'formatted_address' => $data['formattedAddress'],
			),
			array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ));
		}
		}
		
	}
