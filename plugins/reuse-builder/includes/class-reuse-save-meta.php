<?php
/**
 * Save MetaBox.
 */

namespace Reuse\Builder;

class Reuse_Builder_Save_Meta
{
    public function __construct()
    {
        add_action('save_post', array($this, 'save_metabox'), 9);
        add_filter('attachment_fields_to_save', array($this, 'process_data'), 11, 2);
        add_filter('personal_options_update', array($this, 'save_metabox'), 11, 2);
        add_filter('edit_user_profile_update', array($this, 'save_metabox'), 11, 2);
    }

    public function process_data($post, $attachments)
    {
        $this->save_metabox($post['ID']);

        return $post;
    }

    public function save_metabox($post_id)
    {
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE post_type = %s", 'reuseb_metabox');
        $the_query = $wpdb->get_results($query);
        // $query_args = array(
        //     'post_type' 	=> 'reuseb_metabox',
        //     'post_per_page' => -1,
        // );
        // $the_query = get_posts( $query_args );

        // get dynamic metaboxes from the builder
        $dynamic_args = array();

        foreach ($the_query as $post) {
            $post_type = get_post_meta($post->ID, 'reuseb_post_type_select', true);

            $generated_id = str_replace('-', '_', strtolower($post->post_name));
            $dynamic_input_name = '_reuseb_dynamic_meta_data_'.$generated_id;
            $dynamic_args[] = array(
                'post_id' => $post_id,
                'post_type' => $post_type,
                'has_individual' => true,
                'meta_fields' => array(
                    $dynamic_input_name,
                ),
            );
        }

        $args = array(
            array(
                'post_id' => $post_id,
                'post_type' => 'reuseb_post_type',
                'has_individual' => true,
                'meta_fields' => array(
                    '_reuse_builder_post_types_data',
                ),
            ),
            array(
                'post_id' => $post_id,
                'post_type' => 'reuseb_metabox',
                'has_individual' => true,
                'meta_fields' => array(
                    '_reuseb_metabox_builder_output',
                ),
            ),
            array(
                'post_id' => $post_id,
                'post_type' => 'reuseb_taxonomy',
                'has_individual' => true,
                'meta_fields' => array(
                    '_reuse_builder_taxonomies_data',
                ),
            ),
            array(
                'post_id' => $post_id,
                'post_type' => 'reuseb_term_metabox',
                'has_individual' => true,
                'meta_fields' => array(
                    '_reuseb_term_meta_builder_data',
                ),
            ),
            array(
                'post_id' => $post_id,
                'post_type' => 'reuseb_template',
                'has_individual' => true,
                'meta_fields' => array(
                    '_reuseb_template_data',
                ),
            ),
        );

        $reuse_builder_settings = stripslashes_deep(get_option('reuseb_settings', true));
        $geobox_post_types = json_decode($reuse_builder_settings);
        $geobox_post_types_array = $geobox_post_types != '1' && $geobox_post_types->geobox_enable_post_type != '' ? explode(',', $geobox_post_types->geobox_enable_post_type) : [];
        if (!empty($geobox_post_types_array)) {
            foreach ($geobox_post_types_array as $post_type) {
                $args[] = array(
                    'post_id' => $post_id,
                    'post_type' => $post_type,
                    'has_individual' => true,
                    'meta_fields' => array(
                        '_reuseb_geobox_preview',
                    ),
                );
            }
        }
        new Reuse_Generate_Metabox_Saver(array_merge($args, $dynamic_args));
    }
}
