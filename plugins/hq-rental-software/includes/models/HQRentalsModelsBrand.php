<?php

namespace HQRentalsPlugin\HQRentalsModels;

use HQRentalsPlugin\HQRentalsDb\HQRentalsDbManager;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsDataFilter;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsModelsBrand extends HQRentalsBaseModel
{
    public $brandsCustomPostName = 'hqwp_brands';
    public $brandsCustomPostSlug = 'brands';
    private $tableName = 'hq_brands';
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
            'column_name' => 'location_fee',
            'column_data_type' => 'varchar(255)'
        ),
        array(
            'column_name' => 'tax_label',
            'column_data_type' => 'varchar(255)'
        ),
        array(
            'column_name' => 'website_link',
            'column_data_type' => 'varchar(255)'
        ),
        array(
            'column_name' => 'uuid',
            'column_data_type' => 'varchar(255)'
        ),
        array(
            'column_name' => 'reservation_form_snippet',
            'column_data_type' => 'varchar(1000)'
        ),
        array(
            'column_name' => 'reservations_snippet',
            'column_data_type' => 'varchar(1000)'
        ),
        array(
            'column_name' => 'quote_snippet',
            'column_data_type' => 'varchar(1000)'
        ),
        array(
            'column_name' => 'package_quotes_snippet',
            'column_data_type' => 'varchar(1000)'
        ),
        array(
            'column_name' => 'payment_requests_snippet',
            'column_data_type' => 'varchar(1000)'
        ),
        array(
            'column_name' => 'abb_tax',
            'column_data_type' => 'varchar(24)'
        ),
        array(
            'column_name' => 'calendar_snippet',
            'column_data_type' => 'varchar(1000)'
        ),
        array(
            'column_name' => 'updated_at',
            'column_data_type' => 'varchar(50)'
        )
    );

    protected $metaId = 'hq_wordpress_brand_id_meta';
    protected $metaName = 'hq_wordpress_brand_name_meta';
    protected $metaTaxLabel = 'hq_wordpress_brand_tax_label_meta';
    protected $metaWebsiteLink = 'hq_wordpress_brand_website_link_meta';
    protected $metaPublicReservationsLinkFull = 'hq_wordpress_brand_public_reservations_link_full';
    protected $metaPublicPackagesLinkFull = 'hq_wordpress_brand_public_packages_link_full';
    protected $metaPublicReservationsFirstStepLink = 'hq_wordpress_brand_public_reservations_first_step_link';
    protected $metaPublicPackagesFirstStepLink = 'hq_wordpress_brand_public_packages_first_step_link';
    protected $metaPublicReservationPackagesFirstStepLink = 'hq_wordpress_brand_public_reservation_packages_first_step_link';
    protected $metaMyReservationsLink = 'hq_wordpress_brand_my_reservation_link';
    protected $metaMyPackagesReservationsLink = 'hq_wordpress_brand_my_packages_reservation_link';
    protected $metaPublicCalendarLink = 'hq_wordpress_brand_public_calendar_link';
    protected $metaIntegrationSnippetsReservations = 'hq_wordpress_brand_integration_snippets_reservations';
    protected $metaIntegrationSnippetsReservationForm = 'hq_wordpress_brand_integration_snippets_reservation_form';
    protected $metaIntegrationSnippetsQuotes = 'hq_wordpress_brand_integration_snippets_quotes';
    protected $metaIntegrationSnippetsPackageQuotes = 'hq_wordpress_brand_integration_snippets_packages_quotes';
    protected $metaIntegrationSnippetsPaymentRequest = 'hq_wordpress_brand_integration_snippets_payment_requests';
    protected $metaUUID = 'hq_wordpress_brand_uuid';
    protected $metaIntegrationSnippetsCalendar = 'hq_wordpress_brand_integration_snippets_calendar';
    protected $metaIntegrationSnippetsClassCalendar = 'hq_wordpress_brand_integration_snippets_class_calendar';
    protected $metaIntegrationSnippetsMyReservation = 'hq_wordpress_brand_integration_snippets_my_reservation';

    public $id = '';
    public $name = '';
    public $taxLabel = '';
    public $abbTax = '';
    public $websiteLink = '';
    public $publicReservationsLinkFull = '';
    public $publicPackagesLinkFull = '';
    public $publicReservationsFirstStepLink = '';
    public $publicPackagesFirstStepLink = '';
    public $publicReservationPackagesFirstStepLink = '';
    public $myReservationsLink = '';
    public $myPackagesReservationsLink = '';
    public $publicCalendarLink = '';
    public $metaBrandId = 'hq_wordpress_brand_id_meta';
    public $snippetReservations = '';
    public $snippetReservationForm = '';
    public $snippetQuotes = '';
    public $snippetPackageQuote = '';
    public $snippetPaymentRequest = '';
    public $snippetCalendar = '';
    public $snippetClassCalendar = '';
    public $snippetMyReservation = '';
    public $uuid = '';
    public $updated_at = '';

    public function __construct($post = null)
    {
        $this->pluginSettings = new HQRentalsSettings();
        $this->post_id = '';
        if ($post) {
            $this->systemId = $post->ID;
        }
        $this->postArgs = array(
            'post_type' => $this->brandsCustomPostName,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'order' => 'ASC'
        );
        $this->labels = array(
            'name' => _x('Branches', 'post type general name', 'hq-rental-software'),
            'singular_name' => _x('Branch', 'post type singular name', 'hq-rental-software'),
            'menu_name' => _x('Branches', 'admin menu', 'hq-rental-software'),
            'name_admin_bar' => _x('Branch', 'add new on admin bar', 'hq-rental-software'),
            'add_new' => _x('Add New', 'Branch', 'hq-rental-software'),
            'add_new_item' => __('Add New Branch', 'hq-rental-software'),
            'new_item' => __('New Branch', 'hq-rental-software'),
            'edit_item' => __('Edit Branch', 'hq-rental-software'),
            'view_item' => __('View Branch', 'hq-rental-software'),
            'all_items' => __('All Branches', 'hq-rental-software'),
            'search_items' => __('Search Branches', 'hq-rental-software'),
            'parent_item_colon' => __('Parent Branches', 'hq-rental-software'),
            'not_found' => __('No Branches found.', 'hq-rental-software'),
            'not_found_in_trash' => __('No Branches found in Trash.', 'hq-rental-software')
        );
        $this->customPostArgs = array(
            'labels' => $this->labels,
            'public' => true,
            'show_in_admin_bar' => true,
            'publicly_queryable' => $this->pluginSettings->isEnableCustomPostsPages(),
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_nav_menus' => true,
            'query_var' => true,
            'rewrite' => array('slug' => $this->brandsCustomPostSlug),
            'has_archive' => true,
            'hierarchical' => false,
            'exclude_from_search' => false,
            'menu_icon' => 'dashicons-store',
            'menu_position' => 6,
            'capabilities' => array(
                'create_posts' => 'do_not_allow'
            )
        );
        $this->filter = new HQRentalsDataFilter();
        $this->settings = new HQRentalsSettings();
        if (!empty($post)) {
            $this->setBrandFromPost($post);
        }
        $this->db = new HQRentalsDbManager();
    }

    public function setBrandFromApi($data)
    {
        $baseUrlForCalendar = explode('packages', $data->my_package_reservations_link);
        $this->id = $data->id;
        $this->name = $data->name;
        $this->uuid = $data->uuid;
        $this->taxLabel = $data->tax_label;
        $this->abbTax = $data->abb_tax;
        $this->websiteLink = $data->website_link;
        $snippetData = isset($data->integration_snippets) ? (array)$data->integration_snippets : [];
        $this->snippetReservations = htmlspecialchars($snippetData['reservations']);
        $this->snippetReservationForm = htmlspecialchars($snippetData['reservation-form']);
        $this->snippetQuotes = htmlspecialchars($snippetData['quotes']);
        $this->snippetPackageQuote = htmlspecialchars($snippetData['package-quotes']);
        $this->snippetPaymentRequest = htmlspecialchars($snippetData['payment-request'] ?? '');
        $this->snippetCalendar = htmlspecialchars($snippetData['calendar']);
        $this->snippetClassCalendar = htmlspecialchars($snippetData['class-calendar']);
        $this->snippetMyReservation = htmlspecialchars($snippetData['my-reservations']);
        $this->updated_at = current_time('mysql', 1);
        if ($this->settings->getReplaceBaseURLOnBrandsSetting() === "true") {
            $urlReplacement = $this->settings->getBrandURLToReplaceSetting();
            $this->publicReservationsLinkFull = $this->resolveBrandURL($data->public_reservations_link_full, $urlReplacement);
            $this->publicPackagesLinkFull = $this->resolveBrandURL($data->public_packages_link_full, $urlReplacement);
            $this->publicReservationsFirstStepLink = $this->resolveBrandURL($data->public_reservations_link_first_step, $urlReplacement);
            $this->publicPackagesFirstStepLink = $this->resolveBrandURL($data->public_packages_link_first_step, $urlReplacement);
            $this->publicReservationPackagesFirstStepLink = $this->resolveBrandURL($data->public_reservations_packages_link_first_step, $urlReplacement);
            $this->myReservationsLink = $this->resolveBrandURL($data->my_reservations_link, $urlReplacement);
            $this->myPackagesReservationsLink = $this->resolveBrandURL($data->my_package_reservations_link, $urlReplacement);
            $this->publicCalendarLink = $this->resolveBrandURL($baseUrlForCalendar[0] . 'calendar?brand_id=' . $data->uuid, $urlReplacement);
        } else {
            $this->publicReservationsLinkFull = $data->public_reservations_link_full;
            $this->publicPackagesLinkFull = $data->public_packages_link_full;
            $this->publicReservationsFirstStepLink = $data->public_reservations_link_first_step;
            $this->publicPackagesFirstStepLink = $data->public_packages_link_first_step;
            $this->publicReservationPackagesFirstStepLink = $data->public_reservations_packages_link_first_step;
            $this->myReservationsLink = $data->my_reservations_link;
            $this->myPackagesReservationsLink = $data->my_package_reservations_link;
            $this->publicCalendarLink = $baseUrlForCalendar[0] . 'calendar?brand_id=' . $data->uuid;
        }
    }

    /*
     * Create Brand Model Custom Post
     */
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
        hq_update_post_meta($post_id, $this->metaName, $this->name);
        hq_update_post_meta($post_id, $this->metaTaxLabel, $this->taxLabel);
        hq_update_post_meta($post_id, $this->metaWebsiteLink, $this->websiteLink);
        hq_update_post_meta($post_id, $this->metaPublicReservationsLinkFull, $this->publicReservationsLinkFull);
        hq_update_post_meta($post_id, $this->metaPublicPackagesLinkFull, $this->publicPackagesLinkFull);
        hq_update_post_meta($post_id, $this->metaPublicReservationsFirstStepLink, $this->publicReservationsFirstStepLink);
        hq_update_post_meta($post_id, $this->metaMyReservationsLink, $this->myReservationsLink);
        hq_update_post_meta($post_id, $this->metaMyPackagesReservationsLink, $this->myPackagesReservationsLink);
        hq_update_post_meta($post_id, $this->metaPublicCalendarLink, $this->publicCalendarLink);
        hq_update_post_meta($post_id, $this->metaIntegrationSnippetsReservations, $this->snippetReservations);
        hq_update_post_meta($post_id, $this->metaIntegrationSnippetsReservationForm, $this->snippetReservationForm);
        hq_update_post_meta($post_id, $this->metaIntegrationSnippetsQuotes, $this->snippetQuotes);
        hq_update_post_meta($post_id, $this->metaIntegrationSnippetsPackageQuotes, $this->snippetPackageQuote);
        hq_update_post_meta($post_id, $this->metaIntegrationSnippetsPaymentRequest, $this->snippetPaymentRequest);
        hq_update_post_meta($post_id, $this->metaIntegrationSnippetsCalendar, $this->snippetCalendar);
        hq_update_post_meta($post_id, $this->metaIntegrationSnippetsClassCalendar, $this->snippetClassCalendar);
        hq_update_post_meta($post_id, $this->metaIntegrationSnippetsMyReservation, $this->snippetMyReservation);
        hq_update_post_meta($post_id, $this->metaUUID, $this->uuid);
    }

    public function find($brandId)
    {
        $args = array_merge(
            $this->postArgs,
            array(
                'meta_query' => array(
                    array(
                        'key' => $this->metaId,
                        'value' => $brandId,
                        'compare' => '=',
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        $this->setBrandFromPost($query->posts[0]->ID);
    }

    public function setBrandFromPost($brandPost)
    {
        $this->setBrandFromPostId($brandPost->ID);
    }

    public function setBrandFromPostId($id)
    {
        $this->id = get_post_meta($id, $this->metaId, true);
        $this->name = get_post_meta($id, $this->metaName, true);
        $this->taxLabel = get_post_meta($id, $this->metaTaxLabel, true);
        $this->uuid = get_post_meta($id, $this->metaUUID, true);
        $this->websiteLink = get_post_meta($id, $this->metaWebsiteLink, true);
        $this->publicReservationsLinkFull = get_post_meta($id, $this->metaPublicReservationsLinkFull, true);
        $this->publicPackagesLinkFull = get_post_meta($id, $this->metaPublicPackagesLinkFull, true);
        $this->publicReservationsFirstStepLink = get_post_meta($id, $this->metaPublicReservationsFirstStepLink, true);
        $this->publicPackagesFirstStepLink = get_post_meta($id, $this->metaPublicPackagesFirstStepLink, true);
        $this->publicReservationPackagesFirstStepLink = get_post_meta($id, $this->metaPublicReservationPackagesFirstStepLink, true);
        $this->myReservationsLink = get_post_meta($id, $this->metaMyReservationsLink, true);
        $this->myPackagesReservationsLink = get_post_meta($id, $this->metaMyPackagesReservationsLink, true);
        $this->publicCalendarLink = get_post_meta($id, $this->metaPublicCalendarLink, true);
        $this->snippetReservations = htmlspecialchars_decode(get_post_meta($id, $this->metaIntegrationSnippetsReservations, true));
        $this->snippetReservationForm = htmlspecialchars_decode(get_post_meta($id, $this->metaIntegrationSnippetsReservationForm, true));
        $this->snippetQuotes = htmlspecialchars_decode(get_post_meta($id, $this->metaIntegrationSnippetsQuotes, true));
        $this->snippetPackageQuote = htmlspecialchars_decode(get_post_meta($id, $this->metaIntegrationSnippetsPackageQuotes, true));
        $this->snippetPaymentRequest = htmlspecialchars_decode(get_post_meta($id, $this->metaIntegrationSnippetsPaymentRequest, true));
        $this->snippetCalendar = htmlspecialchars_decode(get_post_meta($id, $this->metaIntegrationSnippetsCalendar, true));
        $this->snippetClassCalendar = htmlspecialchars_decode(get_post_meta($id, $this->metaIntegrationSnippetsClassCalendar, true));
        $this->snippetMyReservation = htmlspecialchars_decode(get_post_meta($id, $this->metaIntegrationSnippetsMyReservation, true));
    }

    public function all()
    {
        $query = new \WP_Query($this->postArgs);
        return $query->posts;
    }

    public function findBySystemId($hqBrandId)
    {
        $args = array_merge(
            $this->postArgs,
            array(
                'meta_query' => array(
                    array(
                        'key' => $this->metaId,
                        'value' => $hqBrandId,
                        'compare' => '=',
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        return $this->setBrandFromPost($query->posts[0]);
    }

    protected function resolveBrandURL($url, $replacement)
    {
        $url_info = parse_url($url);
        $baseURL = $url_info["host"];
        return str_replace($baseURL, $replacement, $url);
    }

    public function getReservationSnippet()
    {
        return $this->snippetReservations;
    }

    public function getReservationFormSnippet()
    {
        return $this->snippetReservationForm;
    }

    public function getQuoteSnippet()
    {
        return $this->snippetQuotes;
    }

    public function getPackageSnippet()
    {
        return $this->snippetPackageQuote;
    }

    public function getPaymentRequestSnippet()
    {
        return $this->snippetPaymentRequest;
    }

    public function getUUID()
    {
        return $this->uuid;
    }

    public function getUUIDMetaKey()
    {
        return $this->metaUUID;
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
        $result = $this->db->selectFromTable($this->tableName, '*', 'id=' . $this->id);
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
            'location_fee' => '',
            'tax_label' => $this->taxLabel,
            'abb_tax' => $this->abbTax,
            'website_link' => $this->websiteLink,
            'uuid' => $this->uuid,
            'reservation_form_snippet' => $this->snippetReservationForm,
            'reservations_snippet' => $this->snippetReservations,
            'quote_snippet' => $this->snippetQuotes,
            'package_quotes_snippet' => $this->snippetPackageQuote,
            'payment_requests_snippet' => $this->snippetPaymentRequest,
            'calendar_snippet' => $this->getCalendarSnippet(),
            'updated_at' => $this->updated_at
        );
    }
    public function setFromDB($brandFromDB)
    {
        $this->id = $brandFromDB->id;
        $this->name = $brandFromDB->name;
        $this->taxLabel = $brandFromDB->tax_label;
        $this->websiteLink = $brandFromDB->website_link;
        $this->uuid = $brandFromDB->uuid;
        $this->snippetReservations = htmlspecialchars_decode($brandFromDB->reservations_snippet);
        $this->snippetReservationForm = htmlspecialchars_decode($brandFromDB->reservation_form_snippet);
        $this->snippetQuotes = htmlspecialchars_decode($brandFromDB->quote_snippet);
        $this->snippetPackageQuote = htmlspecialchars_decode($brandFromDB->package_quotes_snippet);
        $this->snippetPaymentRequest = htmlspecialchars_decode($brandFromDB->payment_requests_snippet);
        $this->abbTax = $brandFromDB->abb_tax;
        $this->setUpdatedAt($brandFromDB->updated_at);
    }
    public function getTableName(): string
    {
        return $this->tableName;
    }
    public function getCalendarSnippet(): string
    {
        return $this->snippetCalendar;
    }
    public function getCalendarClassSnippet(): string
    {
        return $this->snippetCalendar;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getAbbTaxAsNumber(): float
    {
        try {
            return 1 + (floatval($this->abbTax) / 100);
        } catch (\Throwable $e) {
            return 1;
        }
    }
}
