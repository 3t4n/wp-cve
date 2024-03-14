<?php

namespace HQRentalsPlugin\HQRentalsModels;

class HQRentalsModelsVehicleClassCustomFields extends HQRentalsBaseModel
{
    /*
     *
     */
    public $customFieldsPostName = 'hqwp_veh_cfields';
    public $customFieldsPostSlug = 'vehicle-classes-fields';

    /*
     *
     */
    protected $idMeta = 'hq_wordpress_custom_field_meta_id';
    protected $labelMeta = 'hq_wordpress_custom_field_label_meta';
    protected $typeMeta = 'hq_wordpress_custom_field_type_meta';
    protected $dbcolumnMeta = 'hq_wordpress_custom_field_db_column_meta';

    public $id = '';
    public $label = '';
    public $type = '';
    public $dbcolumn = '';

    public function __construct($dbColumn = null)
    {
        $this->post_id = '';
        $this->postArgs = array(
            'post_type' => $this->customFieldsPostName,
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
    }

    public function setCustomFieldFromApi($data)
    {
        $this->id = $data->id;
        $this->label = $data->label;
        $this->type = $data->type;
        $this->dbcolumn = $data->dbcolumn;
    }

    public function create()
    {
        $queryArgs = array(
            $this->postArgs,
            array(
                'post_type' => $this->label,
                'post_name' => $this->label
            )
        );
        $post_id = wp_insert_post($queryArgs);
        hq_update_post_meta($post_id, $this->idMeta, $this->id);
        hq_update_post_meta($post_id, $this->labelMeta, $this->label);
        hq_update_post_meta($post_id, $this->typeMeta, $this->type);
        hq_update_post_meta($post_id, $this->dbcolumnMeta, $this->dbcolumn);
    }

    protected function find($caag_id)
    {
        // TODO: Implement find() method.
    }


    protected function all()
    {
        // TODO: Implement all() method.
    }
}
