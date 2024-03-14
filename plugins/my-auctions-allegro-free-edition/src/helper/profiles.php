<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

class GJMAA_Helper_Profiles
{

    public function getFieldsData($type = 'form')
    {
        $form = [
            'profile_id'                 => [
                'type' => 'hidden',
                'name' => 'profile_id'
            ],
            'profile_setting_id'         => [
                'id'       => 'setting_id',
                'type'     => 'select',
                'name'     => 'profile_setting_id',
                'label'    => 'API Settings',
                'source'   => 'settings',
                'required' => true,
                'help'     => __('Choose API settings that you will used to import', GJMAA_TEXT_DOMAIN)
            ],
            'profile_name'               => [
                'type'     => 'text',
                'name'     => 'profile_name',
                'label'    => 'Name',
                'required' => true,
                'help'     => __('Type here your custom profile name', GJMAA_TEXT_DOMAIN)
            ],
            'profile_type'               => [
                'id'       => 'profile_type',
                'type'     => 'select',
                'name'     => 'profile_type',
                'label'    => 'Type of auctions',
                'source'   => 'allegro_type',
                'required' => true,
                'help'     => __('Choose type of auction that will be imported', GJMAA_TEXT_DOMAIN)
            ],
            'profile_auctions'           => [
                'type'     => 'number',
                'name'     => 'profile_auctions',
                'label'    => 'Count of auctions',
                'min' => $this->isProPluginEnabled() ? 0 : 1,
                'max' => $this->isProPluginEnabled() ? 99999 : 100,
                'required' => true,
                'help'     => __('Type number of auctions', GJMAA_TEXT_DOMAIN) . '. ' .
                    ($this->isProPluginEnabled() ? __('Set 0 if you want unlimited', GJMAA_TEXT_DOMAIN) : '<span style="color:red">' . sprintf(__('You can import only 100 auctions, <a href="%s" target="_blank">buy pro version</a> for unlimited', GJMAA_TEXT_DOMAIN), $this->getProUrl()) . '</span>') .'.'
            ],
            'profile_category'           => [
                'id'     => 'category',
                'type'   => 'select',
                'name'   => 'profile_category',
                'label'  => 'Category',
                'source' => $this->getSourceByType($type),
                'help'   => __('Choose allegro category that will be filtered during import', GJMAA_TEXT_DOMAIN)
            ],
            'profile_category_hidden'    => [
                'id'   => 'category_hidden',
                'type' => 'hidden',
                'name' => 'profile_category_hidden'
            ],
            'profile_sort'               => [
                'type'   => 'select',
                'name'   => 'profile_sort',
                'label'  => 'Sort',
                'source' => 'allegro_sort',
                'help'   => __('Choose type of sorting that will be imported', GJMAA_TEXT_DOMAIN)
            ],
            'profile_user'               => [
                'id'       => 'profile_user',
                'type'     => 'text',
                'name'     => 'profile_user',
                'label'    => 'Seller ID',
                'disabled' => true
            ],
            'profile_search_query'       => [
                'id'       => 'profile_search_query',
                'type'     => 'text',
                'name'     => 'profile_search_query',
                'label'    => 'Query',
                'disabled' => true,
                'help'     => __('Type here query that you want to find', GJMAA_TEXT_DOMAIN)
            ],
            'profile_sellingmode_format' => [
                'id'             => 'profile_sellingmode_format',
                'type'           => 'select',
                'name'           => 'profile_sellingmode_format[]',
                'label'          => __('Selling mode format', GJMAA_TEXT_DOMAIN),
                'disabled'       => true,
                'is_multiselect' => true,
                'source'         => 'allegro_offersellingmode',
                'help'           => __('Choose which type of offers you want to import', GJMAA_TEXT_DOMAIN)
            ],
            'profile_last_sync'          => [
                'type'     => 'text',
                'name'     => 'profile_last_sync',
                'label'    => 'Last synchronization',
                'disabled' => true,
                'help'     => __('Time of last synchronization', GJMAA_TEXT_DOMAIN)
            ]
        ];

        if (GJMAA::getService('woocommerce')->isEnabled()) {
            $form += [
                'profile_sync_price'                      => [
                    'type'   => 'select',
                    'name'   => 'profile_sync_price',
                    'label'  => __('Update prices?', GJMAA_TEXT_DOMAIN),
                    'source' => 'yesno',
                    'help'   => __('Choose that you want to update auction prices to WooCommerce Product', GJMAA_TEXT_DOMAIN),
                    'value'  => 0
                ],
                'profile_sync_stock'                      => [
                    'type'   => 'select',
                    'name'   => 'profile_sync_stock',
                    'label'  => __('Update stock?', GJMAA_TEXT_DOMAIN),
                    'source' => 'yesno',
                    'help'   => __('Choose that you want to update auction stock to WooCommerce Product', GJMAA_TEXT_DOMAIN),
                    'value'  => 1
                ],
                'profile_to_woocommerce'                  => [
                    'id'     => 'profile_to_woocommerce',
                    'type'   => 'select',
                    'name'   => 'profile_to_woocommerce',
                    'label'  => 'WooCommerce?',
                    'source' => 'yesno',
                    'help'   => __('Choose that you want import auctions from allegro to WooCommerce', GJMAA_TEXT_DOMAIN)
                ],
                'profile_sync_woocommerce_fields'         => [
                    'id'             => 'profile_sync_woocommerce_fields',
                    'type'           => 'select',
                    'name'           => 'profile_sync_woocommerce_fields[]',
                    'label'          => __('Update fields for product?', GJMAA_TEXT_DOMAIN),
                    'source'         => 'woocommerce_fields',
                    'is_multiselect' => true,
                    'help'           => __('Choose which field you want update to WooCommerce Product', GJMAA_TEXT_DOMAIN),
                    'value'          => ''
                ],
                'profile_publication_status' => [
                    'id'             => 'profile_publication_status',
                    'type'           => 'select',
                    'name'           => 'profile_publication_status[]',
                    'label'          => __('Status of auction to sync with WooCommerce', GJMAA_TEXT_DOMAIN),
                    'source'         => 'allegro_offerstatus',
                    'is_multiselect' => true,
                    'help'           => __('Set up status of auctions that will be added to WooCommerce', GJMAA_TEXT_DOMAIN)
                ],
	            'profile_import_new_flag' => [
		            'id'             => 'profile_import_new_flag',
		            'type'           => 'select',
		            'name'           => 'profile_import_new_flag',
		            'label'          => __('Import new?', GJMAA_TEXT_DOMAIN),
		            'source'         => 'yesno',
		            'help'           => __('Set, if you want to import offers from allegro that not exist in WooCommerce', GJMAA_TEXT_DOMAIN)
	            ],
	            'profile_link_by_signature' => [
					'id' => 'profile_link_by_signature',
		            'type' => 'select',
		            'name' => 'profile_link_by_signature',
		            'label' => __('Link products by allegro signature',GJMAA_TEXT_DOMAIN),
		            'source' => 'yesno',
		            'help' => __('Set, if you want to link WooCommerce products by Allegro Signature', GJMAA_TEXT_DOMAIN)
	            ],
                'profile_save_woocommerce_category_level' => [
                    'type'  => 'number',
                    'name'  => 'profile_save_woocommerce_category_level',
                    'label' => __('WooCommerce Category Level (0 - 3)', GJMAA_TEXT_DOMAIN),
                    'help'  => __('Choose from which level category should be saved for product', GJMAA_TEXT_DOMAIN)
                ]
            ];
        }

        $form += [
            'profile_cron_sync'      => [
                'type'   => 'select',
                'name'   => 'profile_cron_sync',
                'label'  => __('Sync with CRON', GJMAA_TEXT_DOMAIN),
                'source' => 'yesno',
                'help'   => __('Choose that you want import auctions from allegro with CRON', GJMAA_TEXT_DOMAIN)
            ],
            'profile_clear_auctions' => [
                'type'   => 'select',
                'name'   => 'profile_clear_auctions',
                'label'  => __('Clear auctions', GJMAA_TEXT_DOMAIN),
                'source' => 'yesno',
                'help'   => __('Clear auctions during every import?', GJMAA_TEXT_DOMAIN)
            ],
            'save'                   => [
                'type'  => 'submit',
                'name'  => 'save',
                'label' => 'Save'
            ]
        ];

        return $form;
    }

    public function getTotalProfiles()
    {
        return GJMAA::getModel('profiles')->getCountAll();
    }

    public function getSourceByType($type)
    {
        switch ($type) {
            case 'table':
                return 'allegro_category_tree';
            default:
                return 'allegro_category';
        }
    }

    public function isProPluginEnabled()
    {
        self::addIsPluginActiveMethod();

        $isProPluginActive = false;
        foreach(GJMAA::GjwaProPath as $path) {
            if(is_plugin_active($path)) {
                $isProPluginActive = true;
                break;
            }
        }
        return $isProPluginActive;
    }

    public function addIsPluginActiveMethod()
    {
        if (! function_exists('is_plugin_active')) {
            include_once (ABSPATH . 'wp-admin/includes/plugin.php');
        }
    }

    public function getProUrl()
    {
        return 'https://wphocus.com/produkt/woocommerce-allegro-pro-integracja?utm_source=plugin&utm_medium=profile&utm_campaign=upgrade&utm_id=my-auctions-allegro';
    }
}