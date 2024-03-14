<?php

namespace HQRentalsPlugin\HQRentalsModels;

use HQRentalsPlugin\HQRentalsDb\HQRentalsDbManager;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsModelsLocation extends HQRentalsBaseModel
{
    public $locationsCustomPostName = 'hqwp_locations';
    public $locationsCustomPostSlug = 'locations';
    private $tableName = 'hq_locations';
    private $columns = array(
        array(
            'column_name' => 'id',
            'column_data_type' => 'int'
        ),
        array(
            'column_name' => 'name',
            'column_data_type' => 'varchar(255)'
        ),
        array(
            'column_name' => 'brand_id',
            'column_data_type' => 'int'
        ),
        array(
            'column_name' => 'is_airport',
            'column_data_type' => 'tinyint(1)'
        ),
        array(
            'column_name' => 'coordinates',
            'column_data_type' => 'varchar(255)'
        ),
        array(
            'column_name' => 'active',
            'column_data_type' => 'tinyint(1)'
        ),
        array(
            'column_name' => 'location_order',
            'column_data_type' => 'int'
        ),
        array(
            'column_name' => 'address',
            'column_data_type' => 'varchar(255)'
        ),
        array(
            'column_name' => 'open_hours',
            'column_data_type' => 'varchar(255)'
        ),
        array(
            'column_name' => 'label_for_website',
            'column_data_type' => 'varchar(1024)'
        ),
        array(
            'column_name' => 'all_map_coordinate',
            'column_data_type' => 'varchar(255)'
        ),
        array(
            'column_name' => 'pick_up_allowed',
            'column_data_type' => 'tinyint(1)'
        ),
        array(
            'column_name' => 'return_allowed',
            'column_data_type' => 'tinyint(1)'
        ),
        array(
            'column_name' => 'label_for_website_translated',
            'column_data_type' => 'varchar(255)'
        ),
        array(
            'column_name' => 'updated_at',
            'column_data_type' => 'varchar(50)'
        )
    );

    protected $metaId = 'hq_wordpress_location_id_meta';
    protected $metaBrandId = 'hq_wordpress_location_brand_id_meta';
    protected $metaName = 'hq_wordpress_location_name_meta';
    protected $metaAirport = 'hq_wordpress_location_is_airport_meta';
    protected $metaOffice = 'hq_wordpress_location_is_office_meta';
    protected $metaCoordinates = 'hq_wordpress_location_coordinates_meta';
    protected $metaImage = 'hq_wordpress_location_image_meta';
    protected $metaDescription = 'hq_wordpress_location_description_meta';
    protected $metaAddressLabel = 'hq_wordpress_location_address_label_meta';
    protected $metaOfficeHours = 'hq_wordpress_location_office_hours_meta';
    protected $metaAddress = 'hq_wordpress_location_address_meta';
    protected $metaPhone = 'hq_wordpress_location_phone_meta';
    protected $metaBrands = 'hq_wordpress_location_brands_meta';
    protected $metaIsActive = 'hq_wordpress_location_is_active_meta';
    protected $metaOrder = 'hq_wordpress_location_order_meta';
    protected $metaLabelForWebsites = 'hq_wordpress_location_labels_meta';

    public $id = '';
    public $brandId = '';
    public $name = '';
    public $isAirport = '';
    public $isOffice = '';
    public $coordinates = '';
    public $isActive = '';
    public $order = '';
    public $image = '';
    public $description = '';
    public $addressLabel = '';
    public $officeHours = '';
    public $brands = [];
    public $address = '';
    public $phone = '';
    public $labelsForWebsite = [];
    public $updated_at;


    public function __construct($post = null)
    {
        $this->post_id = '';
        $this->settings = new HQRentalsSettings();
        $this->postArgs = array(
            'post_type' => $this->locationsCustomPostName,
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
        $this->labels = array(
            'name' => _x('Locations', 'post type general name', 'hq-wordpress'),
            'singular_name' => _x('Location', 'post type singular name', 'hq-wordpress'),
            'menu_name' => _x('Locations', 'admin menu', 'hq-wordpress'),
            'name_admin_bar' => _x('Location', 'add new on admin bar', 'hq-wordpress'),
            'add_new' => _x('Add New', 'brand', 'hq-wordpress'),
            'add_new_item' => __('Add New Location', 'hq-wordpress'),
            'new_item' => __('New Location', 'hq-wordpress'),
            'edit_item' => __('Edit Location', 'hq-wordpress'),
            'view_item' => __('View Location', 'hq-wordpress'),
            'all_items' => __('All Locations', 'hq-wordpress'),
            'search_items' => __('Search Locations', 'hq-wordpress'),
            'parent_item_colon' => __('Parent Locations', 'hq-wordpress'),
            'not_found' => __('No location found.', 'hq-wordpress'),
            'not_found_in_trash' => __('No location found in Trash.', 'hq-wordpress')
        );
        $this->customPostArgs = array(
            'labels' => $this->labels,
            'public' => false,
            'show_in_admin_bar' => true,
            'publicly_queryable' => $this->settings->isEnableCustomPostsPages(),
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_nav_menus' => true,
            'query_var' => true,
            'rewrite' => array('slug' => $this->locationsCustomPostSlug),
            'has_archive' => false,
            'hierarchical' => false,
            'exclude_from_search' => false,
            'menu_icon' => 'dashicons-location-alt',
            'menu_position' => 7,
            'capabilities' => array(
                'create_posts' => 'do_not_allow'
            )
        );
        $this->db = new HQRentalsDbManager();
        if (!empty($post)) {
            $this->setFromPost($post);
        }
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function setLocationFromApi($data)
    {
        $this->id = $data->id;
        $this->brandId = $data->brand_id;
        $this->name = $data->name;
        $this->isAirport = $data->is_airport;
        $this->isOffice = $data->is_office ?? '';
        $this->coordinates = $data->coordinates ?? '';
        $this->isActive = $data->active ?? true;
        $this->order = $data->order ?? '';
        $this->image = $data->image ?? '';
        $this->description = $data->description ?? '';
        $this->officeHours = $data->officeHours ?? '';
        $this->addressLabel = $data->addressLabel ?? '' ;
        $this->brands = $data->brands ?? '';
        $this->phone = $data->phone ?? '';
        $this->address = $data->address ?? '';
        $this->labelsForWebsite = $data->label_for_website ?? '';
        $this->updated_at = current_time('mysql', 1);
    }


    public function create()
    {
        $this->postArgs = array_merge(
            $this->postArgs,
            array(
                'post_title' => $this->name,
                'post_name' => $this->name
            )
        );
        $post_id = wp_insert_post($this->postArgs);
        $this->post_id = $post_id;
        hq_update_post_meta($post_id, $this->metaId, $this->id);
        hq_update_post_meta($post_id, $this->metaBrandId, $this->brandId);
        hq_update_post_meta($post_id, $this->metaName, $this->name);
        hq_update_post_meta($post_id, $this->metaAirport, $this->isAirport);
        hq_update_post_meta($post_id, $this->metaOffice, $this->isOffice);
        hq_update_post_meta($post_id, $this->metaCoordinates, $this->coordinates);
        hq_update_post_meta($post_id, $this->metaIsActive, $this->isActive);
        hq_update_post_meta($post_id, $this->metaOrder, $this->order);
        hq_update_post_meta($post_id, $this->metaImage, $this->image);
        hq_update_post_meta($post_id, $this->metaDescription, $this->description);
        hq_update_post_meta($post_id, $this->metaBrands, $this->brands);
        hq_update_post_meta($post_id, $this->metaOfficeHours, $this->officeHours);
        hq_update_post_meta($post_id, $this->metaAddressLabel, $this->addressLabel);
        hq_update_post_meta($post_id, $this->metaAddress, $this->address);
        hq_update_post_meta($post_id, $this->metaPhone, $this->phone);
        hq_update_post_meta($post_id, $this->metaLabelForWebsites, $this->labelsForWebsite);
    }

    public function all()
    {
        $args = array_merge(
            $this->postArgs,
            array(
                'orderby' => 'ID',
            ),
            array(
                'meta_query' => array(
                    array(
                        'key' => $this->metaIsActive,
                        'value' => '1',
                        'compare' => '='
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        return $query->posts;
    }

    public function setFromPost($post)
    {
        foreach ($this->getAllMetaTags() as $property => $metakey) {
            $this->{$property} = get_post_meta($post->ID, $metakey, true);
        }
    }

    public function getAllMetaTags()
    {
        return array(
            'id' => $this->metaId,
            'brandId' => $this->metaBrandId,
            'name' => $this->metaName,
            'isAirport' => $this->metaAirport,
            'isOffice' => $this->metaOffice,
            'coordinates' => $this->metaCoordinates,
            'isActive' => $this->metaIsActive,
            'order' => $this->metaOrder,
            'image' => $this->metaImage,
            'description' => $this->metaDescription,
            'officeHours' => $this->metaOfficeHours,
            'addressLabel' => $this->metaAddressLabel,
            'brands' => $this->metaBrands,
            'address' => $this->metaAddress,
            'phone' => $this->metaPhone,
            'labelsForWebsite' => $this->metaLabelForWebsites
        );
    }

    public function getMetaKeyFromBrandID()
    {
        return $this->metaBrandId;
    }

    public function getOfficeHours($cssClass = "")
    {
        $html = '';
        foreach (explode(PHP_EOL, $this->officeHours) as $hour) {
            $html .= '<p class="' . $cssClass . '" >' . $hour . '</p>';
        }
        return $html;
    }

    public function getBrands()
    {
        if (is_array($this->brands)) {
            $html = '';
            foreach ($this->brands as $brand) {
                $html .= $brand . ' ';
            }
            return $html;
        }
    }

    public function getCustomFieldForAddress()
    {
        return $this->address;
    }
    public function getLatitude()
    {
        return empty($this->coordinates->lat) ? 0.00 : $this->coordinates->lat;
    }
    public function getLongitude()
    {
        return empty($this->coordinates->lng) ? 0.00 : $this->coordinates->lng;
    }

    public function getCustomFieldForOfficeHours()
    {
        return $this->officeHours;
    }

    public function getCustomFieldForPhone()
    {
        return $this->phone;
    }

    public function getLabelForWebsite($override = false, $lang = 'en')
    {
        try {
            if ($override) {
                return $this->labelsForWebsite[$lang];
            }
            return empty($this->labelsForWebsite->{explode('_', get_locale())[0]}) ?
                $this->getName() : $this->labelsForWebsite->{explode('_', get_locale())[0]};
        } catch (\Throwable $e) {
            return '';
        }
    }

    public function getDataToCreateTable(): array
    {
        return array(
            'table_name' => $this->tableName,
            'table_columns' => $this->columns
        );
    }

    public function saveOrUpdate(): void
    {
        $result = $this->db->selectFromTable($this->tableName, '*', 'id="' . $this->id . '"');
        if ($result->success) {
            $resultUpdate = $this->db->updateIntoTable($this->tableName, $this->parseDataToSaveOnDB(), array('id' => $this->id));
        } else {
            $resultInsert = $this->db->insertIntoTable($this->tableName, $this->parseDataToSaveOnDB());
        }
    }

    private function parseDataToSaveOnDB(): array
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'brand_id' => $this->brandId,
            'is_airport' => $this->isAirport,
            'coordinates' => json_encode($this->coordinates),
            'active' => $this->isActive,
            'location_order' => $this->order,
            'address' => $this->address,
            'open_hours' => '',
            'label_for_website' => json_encode($this->labelsForWebsite),
            'all_map_coordinate' => '',
            'pick_up_allowed' => 1,
            'return_allowed' => 1,
            'label_for_website_translated' => '',
            'updated_at' => $this->updated_at
        );
    }

    public function setFromDB($locationFromDB)
    {
        $this->id = $locationFromDB->id;
        $this->name = $locationFromDB->name;
        $this->brandId = $locationFromDB->brand_id;
        $this->isAirport = $locationFromDB->is_airport;
        $this->coordinates = json_decode($locationFromDB->coordinates);
        $this->isActive = $locationFromDB->active;
        $this->order = $locationFromDB->location_order;
        $this->address = $locationFromDB->address;
        $this->officeHours = $locationFromDB->open_hours;
        $this->labelsForWebsite = json_decode($locationFromDB->label_for_website);
        $this->setUpdatedAt($locationFromDB->updated_at);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getBrandId()
    {
        return $this->brandId;
    }

    public function getIsAirport()
    {
        return $this->isAirport;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function getOrder()
    {
        return $this->order;
    }
}
