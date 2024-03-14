<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}

/**
 * Created by PhpStorm.
 * User: simon
 * Date: 12/26/2019
 * Time: 3:24 PM
 */
class JEMEXP_Export_Data
{

    // These are the default settings
    private $settings = array(
        'preview'               => false,
        'new_order_settings'    => array(),
        'orderStatus'           => array(),
        'order_filters_status'  => array(),
        'order_filters_fba'     => array(),
        'product_filter'        => array(),
        'category_filter'       => array(),
        'coupon_filter'         => array(),
        'any_coupons'           => false,
        'customer_filters'      => array(),
        'fields_to_export'      => array(),
        'export_new_orders'     => false,
        'starting_from_num'     => '',
        'date_from'             => "",
        'date_to'               => "",
        'selected_range'        => "select-range",
        'predefined_date'       => "todays-orders-group",
        'custom_code_hooks'     => "",
        'hook_code_valid'       => "",
        'sort_by'               => 'date',
        'order_by'              => 'asc',
        'date_format'           => 'F j,y',
        'time_format'           => 'g:i m',
        'filename'              => 'order-export.csv',
        'temp_filename'         => 'JEMEXP_TEMP',
        'encoding'              => 'UTF-8',
        'delimiter'             => ',',
        'product_grouping'      => 'rows',
        'content_type'          => 'text/plain, charset="UTF-8"',
        'mime_version'          => 'MIME-Version: 1.0',
        'email_from'            => '',
        'line_break'            => '\r\n',

    );


    //*****************************
    //Getters & setters
    //*****************************

    /**
     * @return boolean
     */
    public function isAnyCoupons()
    {
        return $this->settings['any_coupons'];
    }

    /**
     * @param boolean $any_coupons
     */
    public function setAnyCoupons($any_coupons)
    {
        $this->settings['any_coupons'] = $any_coupons;
    }

    /**
     * @return array
     */
    public function getCategoryFilter()
    {
        return $this->settings['category_filter'];
    }

    /**
     * @param array $category_filter
     */
    public function setCategoryFilter($category_filter)
    {
        $this->settings['category_filter'] = $category_filter;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->settings['content_type'];
    }

    /**
     * @param string $content_type
     */
    public function setContentType($content_type)
    {
        $this->settings['content_type'] = $content_type;
    }

    /**
     * @return array
     */
    public function getCouponFilter()
    {
        return $this->settings['coupon_filter'];
    }

    /**
     * @param array $coupon_filter
     */
    public function setCouponFilter($coupon_filter)
    {
        $this->settings['coupon_filter'] = $coupon_filter;
    }

    /**
     * @return array
     */
    public function getCustomerFilters()
    {
        return $this->settings['customer_filters'];
    }

    /**
     * @param array $customer_filters
     */
    public function setCustomerFilters($customer_filters)
    {
        $this->settings['customer_filters'] = $customer_filters;
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return $this->settings['date_format'];
    }

    /**
     * @param string $date_format
     */
    public function setDateFormat($date_format)
    {
        $this->settings['date_format'] = $date_format;
    }

    /**
     * @return string
     */
    public function getSelectedRange()
    {
        return $this->settings['selected_range'];        
    }

    /**
     * @param string $selected_range
     */
    public function setSelectedRange($selected_range)
    {
        $this->settings['selected_range'] = $selected_range;
    }

    /**
     * @return string
     */
    public function getPredefinedDate()
    {
        return $this->settings['predefined_date'];        
    }

    /**
     * @param string $predefined_date
     */
    public function setPredefinedDate($predefined_date)
    {
        $this->settings['predefined_date'] = $predefined_date;
    }

    /**
     * @return string
     */
    public function getCustomcodeHooks()
    {
        return $this->settings['custom_code_hooks'];        
    }

    /**
     * @param string $custom_code_hooks
     */
    public function setCustomcodeHooks($custom_code_hooks)
    {
        $this->settings['custom_code_hooks'] = $custom_code_hooks;
    }

    /**
     * @return string
     */
    public function getHookCodeValid()
    {
        return $this->settings['hook_code_valid'];        
    }

    /**
     * @param string $hook_code_valid
     */
    public function setHookCodeValid($hook_code_valid)
    {
        $this->settings['hook_code_valid'] = $hook_code_valid;
    }

    /**
     * @return string
     */
    public function getDateFrom()
    {
        return $this->settings['date_from'];
    }

    /**
     * @param string $dateFrom
     */
    public function setDateFrom($dateFrom)
    {
        $this->settings['date_from'] = $dateFrom;
    }

    /**
     * @return string
     */
    public function getDateTo()
    {
        return $this->settings['date_to'];
    }

    /**
     * @param string $dateTo
     */
    public function setDateTo($dateTo)
    {
        $this->settings['date_to'] = $dateTo;
    }

    /**
     * @return string
     */
    public function getDelimiter()
    {
        return $this->settings['delimiter'];
    }

    /**
     * @param string $delimiter
     */
    public function setDelimiter($delimiter)
    {
        $this->settings['delimiter'] = $delimiter;
    }

    /**
     * @return string
     */
    public function getEmailFrom()
    {
        return $this->settings['email_from'];
    }

    /**
     * @param string $email_from
     */
    public function setEmailFrom($email_from)
    {
        $this->settings['email_from'] = $email_from;
    }

    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->settings['encoding'];
    }

    /**
     * @param string $encoding
     */
    public function setEncoding($encoding)
    {
        $this->settings['encoding'] = $encoding;
    }

    /**
     * @return string
     */
    public function getExportNewOrders()
    {
        return $this->settings['export_new_orders'];
    }

    /**
     * @param string $export_new_orders
     */
    public function setExportNewOrders($export_new_orders)
    {
        $this->settings['export_new_orders'] = $export_new_orders;
    }

    /**
     * @return array
     */
    public function getFieldsToExport()
    {
        return $this->settings['fields_to_export'];
    }

    /**
     * @param array $fields_to_export
     */
    public function setFieldsToExport($fields_to_export)
    {
        $this->settings['fields_to_export'] = $fields_to_export;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->settings['filename'];
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->settings['filename'] = $filename;
    }

    /**
     * @return string
     */
    public function getTempFilename()
    {
        return $this->settings['temp_filename'];
    }

    /**
     * @param string $filename
     */
    public function setTempFilename($filename)
    {
        $this->settings['temp_filename'] = $filename;
    }

    /**
     * @return string
     */
    public function getLineBreak()
    {
        return $this->settings['line_break'];
    }

    /**
     * @param string $line_break
     */
    public function setLineBreak($line_break)
    {
        $this->settings['line_break'] = $line_break;
    }

    /**
     * @return string
     */
    public function getMimeVersion()
    {
        return $this->settings['mime_version'];
    }

    /**
     * @param string $mime_version
     */
    public function setMimeVersion($mime_version)
    {
        $this->settings['mime_version'] = $mime_version;
    }

    /**
     * @return array
     */
    public function getNewOrderSettings()
    {
        return $this->settings['new_order_settings'];
    }

    /**
     * @param array $new_order_settings
     */
    public function setNewOrderSettings($new_order_settings)
    {
        $this->settings['new_order_settings'] = $new_order_settings;
    }

    /**
     * @return string
     */
    public function getOrderBy()
    {
        return $this->settings['order_by'];
    }

    /**
     * @param string $order_by
     */
    public function setOrderBy($order_by)
    {
        $this->settings['order_by'] = $order_by;
    }

    /**
     * @return array
     */
    public function getOrderFiltersFba()
    {
        return $this->settings['order_filters_fba'];
    }

    /**
     * @param array $order_filters_fba
     */
    public function setOrderFiltersFba($order_filters_fba)
    {
        $this->settings['order_filters_fba'] = $order_filters_fba;
    }

    /**
     * @return array
     */
    public function getOrderFiltersStatus()
    {
        return $this->settings['order_filters_status'];
    }

    /**
     * @param array $order_filters_status
     */
    public function setOrderFiltersStatus($order_filters_status)
    {
        $this->settings['order_filters_status'] = $order_filters_status;
    }

    /**
     * @return array
     */
    public function getOrderStatus()
    {
        return $this->settings['orderStatus'];
    }

    /**
     * @param array $orderStatus
     */
    public function setOrderStatus($orderStatus)
    {
        $this->settings['orderStatus'] = $orderStatus;
    }

    /**
     * @return array
     */
    public function getProductFilter()
    {
        return $this->settings['product_filter'];
    }

    /**
     * @param array $product_filter
     */
    public function setProductFilter($product_filter)
    {
        $this->settings['product_filter'] = $product_filter;
    }

    /**
     * @return string
     */
    public function getProductGrouping()
    {
        return $this->settings['product_grouping'];
    }

    /**
     * @param string $product_grouping
     */
    public function setProductGrouping($product_grouping)
    {
        $this->settings['product_grouping'] = $product_grouping;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings['settings'];
    }

    /**
     * @param array $settings
     */
    public function setSettings($settings)
    {
        $this->settings['settings'] = $settings;
    }

    /**
     * @return string
     */
    public function getSortBy()
    {
        return $this->settings['sort_by'];
    }

    /**
     * @param string $sort_by
     */
    public function setSortBy($sort_by)
    {
        $this->settings['sort_by'] = $sort_by;
    }

    /**
     * @return string
     */
    public function getStartingFromNum()
    {
        return $this->settings['starting_from_num'];
    }

    /**
     * @param string $starting_from_num
     */
    public function setStartingFromNum($starting_from_num)
    {
        $this->settings['starting_from_num'] = $starting_from_num;
    }

    /**
     * @return string
     */
    public function getTimeFormat()
    {
        return $this->settings['time_format'];
    }

    /**
     * @param string $time_format
     */
    public function setTimeFormat($time_format)
    {
        $this->settings['time_format'] = $time_format;
    }

    /**
     * @return bool
     */
    public function getPreview()
    {
        return $this->settings['preview'];
    }

    /**
     * @param bool
     */
    public function setPreview($val)
    {
        $this->settings['preview'] = $val;
    }

    /**
     * This takes an array of settings and loads them into the object
     * It calls itself recursively!
     * @param $data
     */
    public function load_settings_from_array($data)
    {


        //loop through each item, if one already exists then load it
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (is_array($data)) {
                    $this->load_settings_from_array($data[$key]);
                }
                if (isset($this->settings[$key])) {
                    $this->settings[$key] = $val;
                }

            }
        }

    }

    /**
     * Saves itself to the options
     * We just save the fields as JSON stringified array
     */
    public function save_export_data_to_options()
    {

        $data = json_encode($this->settings);
        $ret = update_option(JEMEXP_DOMAIN, $data);
    }

    /**
     * Loads itself!
     */
    public function load_export_data_from_options()
    {
        //get the option
        $data = get_option(JEMEXP_DOMAIN, "");

        //did we get some data?
        if (is_string($data)) {
            $data = json_decode($data, true);

            //And load em up!
            $this->load_settings_from_array($data);
        }


    }
}