<?php

namespace Reuse\Builder;

class Provider
{
    public function __construct()
    {
        $this->taxonomies = reuseb_get_all_taxonomies();
        $this->post_types = reuseb_get_all_post_types();
    }

    public function reuse_builder_settings_array()
    {
        return apply_filters('reuseb_settings_fields_ayyar', array(
            array(
                'id' => 'rest_enable_post_types',
                'type' => 'select',
                'label' => esc_html__('Select Post Type to Enable Rest API', 'reuse-builder'),
                'multiple' => 'true',
                'options' => $this->post_types,
            ),
            array(
                'id' => 'rest_enable_post_types_meta',
                'type' => 'select',
                'label' => esc_html__('Select Post Type to Enable Rest API for Meta', 'reuse-builder'),
                'multiple' => 'true',
                'options' => $this->post_types,
            ),
            array(
                'id' => 'rest_enable_taxonomy',
                'type' => 'select',
                'label' => esc_html__('Select Taxonomye to Enable Rest API', 'reuse-builder'),
                'multiple' => 'true',
                'options' => $this->taxonomies,
            ),
            array(
                'id' => 'rest_enable_term_meta',
                'type' => 'select',
                'label' => esc_html__('Select Taxonomye to Enable TermMeta in Rest API', 'reuse-builder'),
                'multiple' => 'true',
                'options' => $this->taxonomies,
            ),
            array(
                'id' => 'geobox_enable_post_type',
                'type' => 'select',
                'label' => esc_html__('Select Post Type to Enable Location', 'reuse-builder'),
                'multiple' => 'true',
                'options' => $this->post_types,
            ),
        ));
    }

    public function geobox_preview_array()
    {
        $geobox_preview_array = array(
      array(
        'id' => 'location',
        'type' => 'geobox',
        'label' => 'Location',
      ),
    );

        return $geobox_preview_array;
    }
}
