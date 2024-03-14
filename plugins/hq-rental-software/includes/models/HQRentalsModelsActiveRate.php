<?php

namespace HQRentalsPlugin\HQRentalsModels;

use HQRentalsPlugin\HQRentalsHelpers\HQRentalsDataFilter;

class HQRentalsModelsActiveRate extends HQRentalsBaseModel
{
    /*
     * Custom Post Configuration
     */
    public $activeRateCustomPostName = 'hqwp_active_rate';
    private $tableName = 'hq_vehicle_active_rates';
    public $activeRateCustomPostSlug = 'rate';
    private $columns = array(
        array(
            'column_name' => 'vehicle_class_id',
            'column_data_type' => 'int'
        ),
        array(
            'column_name' => 'minute_rate_currency',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'minute_rate_currency_icon',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'minute_rate_amount',
            'column_data_type' => 'DECIMAL(19,4)'
        ),
        array(
            'column_name' => 'minute_rate_usd_amount',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'minute_rate_amount_for_display',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'hourly_rate_currency',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'hourly_rate_currency_icon',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'hourly_rate_amount',
            'column_data_type' => 'DECIMAL(19,4)'
        ),
        array(
            'column_name' => 'hourly_rate_usd_amount',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'hourly_rate_amount_for_display',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'daily_rate_currency',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'daily_rate_currency_icon',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'daily_rate_amount',
            'column_data_type' => 'DECIMAL(19,4)'
        ),
        array(
            'column_name' => 'daily_rate_usd_amount',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'daily_rate_amount_for_display',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'weekly_rate_currency',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'weekly_rate_currency_icon',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'weekly_rate_amount',
            'column_data_type' => 'DECIMAL(19,4)'
        ),
        array(
            'column_name' => 'weekly_rate_usd_amount',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'weekly_rate_amount_for_display',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'monthly_rate_currency',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'monthly_rate_currency_icon',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'monthly_rate_amount',
            'column_data_type' => 'DECIMAL(19,4)'
        ),
        array(
            'column_name' => 'monthly_rate_usd_amount',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'monthly_rate_amount_for_display',
            'column_data_type' => 'varchar(30)'
        ),
        array(
            'column_name' => 'decreasing_rates_based_on_intervals',
            'column_data_type' => 'tinyint(1)'
        ),
        array(
            'column_name' => 'decreasing_rates_based_on_intervals_hourly',
            'column_data_type' => 'tinyint(1)'
        ),
        array(
            'column_name' => 'decreasing_rates_based_on_intervals_per_minute',
            'column_data_type' => 'tinyint(1)'
        ),
    );

    /*
     * HQ Rentals Active Rate Data
     * Custom Post Meta
     */

    protected $metaVehicleIdClass = 'hq_wordpress_active_rate_vehicle_class_id_meta';
    protected $metaBaseRate = 'hq_wordpress_active_rate_base_rate_meta';
    protected $metaMinuteRate = 'hq_wordpress_active_rate_minute_rate_meta';
    protected $metaHourRate = 'hq_wordpress_active_rate_hourly_rate_meta';
    protected $metaDailyRate = 'hq_wordpress_active_rate_daily_rate_meta';
    protected $metaWeeklyRate = 'hq_wordpress_active_rate_weekly_rate_meta';
    protected $metaMonthlyRate = 'hq_wordpress_active_rate_monthly_rate_meta';


    /*
     * Object Data to Display
     */
    public $vehicleClassId = '';
    public $baseRate = '';
    public $minuteRate = '';
    public $hourlyRate = '';
    public $dailyRate = '';
    public $weeklyRate = '';
    public $monthlyRate = '';

    /*
     * DB
     *
     * */
    public $minuteRateCurrency = '';
    public $minuteRateCurrencyIcon = '';
    public $minuteRateAmount = '';
    public $minuteRateUSDAmount = '';
    public $minuteRateAmountForDisplay = '';
    public $hourlyRateCurrency = '';
    public $hourlyRateCurrencyIcon = '';
    public $hourlyRateAmount = '';
    public $hourlyRateUSDAmount = '';
    public $hourlyRateAmountForDisplay = '';
    public $dailyRateCurrency = '';
    public $dailyRateCurrencyIcon = '';
    public $dailyRateAmount = '';
    public $dailyRateUSDAmount = '';
    public $dailyRateAmountForDisplay = '';
    public $weeklyRateCurrency = '';
    public $weeklyRateCurrencyIcon = '';
    public $weeklyRateAmount = '';
    public $weeklyRateUSDAmount = '';
    public $weeklyRateAmountForDisplay = '';
    public $monthlyRateCurrency = '';
    public $monthlyRateCurrencyIcon = '';
    public $monthlyRateAmount = '';
    public $monthlyRateUSDAmount = '';
    public $monthlyRateAmountForDisplay = '';
    public $decreasingRateBasedOnIntervals = '';
    public $decreasingRateBasedOnIntervalsHourly = '';
    public $decreasingRateBasedOnIntervalsPerMinute = '';


    public function __construct($vehicleClassID = null, $allRates = null)
    {
        $this->post_id = '';
        $this->dataType = new HQRentalsDataFilter();
        $this->postArg = array(
            'post_type' => $this->activeRateCustomPostName,
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
        $this->labelsPost = [
            'name' => _x('Active Rates', 'post type general name', 'hq-wordpress'),
            'singular_name' => _x('Active Ratee', 'post type singular name', 'hq-wordpress'),
            'menu_name' => _x('Active Rates', 'admin menu', 'hq-wordpress'),
            'name_admin_bar' => _x('Active Ratee', 'add new on admin bar', 'hq-wordpress'),
            'add_new' => _x('Add New', 'brand', 'hq-wordpress'),
            'add_new_item' => __('Add New Active Ratee', 'hq-wordpress'),
            'new_item' => __('New Active Ratee', 'hq-wordpress'),
            'edit_item' => __('Edit Active Ratee', 'hq-wordpress'),
            'view_item' => __('View Active Ratee', 'hq-wordpress'),
            'all_items' => __('All Active Rates', 'hq-wordpress'),
            'search_items' => __('Search Active Rates', 'hq-wordpress'),
            'parent_item_colon' => __('Parent Active Rates', 'hq-wordpress'),
            'not_found' => __('No Active Rates found.', 'hq-wordpress'),
            'not_found_in_trash' => __('No Active Rates found in Trash.', 'hq-wordpress'),
        ];
        $this->customPostArgs = [
            'labels' => $this->labelsPost,
            'public' => false,
            'show_in_admin_bar' => false,
            'publicly_queryable' => false,
            'show_ui' => false,
            'show_in_menu' => false,
            'show_in_nav_menus' => false,
            'query_var' => true,
            'rewrite' => ['slug' => $this->activeRateCustomPostSlug],
            'has_archive' => true,
            'hierarchical' => false,
            'exclude_from_search' => false,
            'menu_icon' => 'dashicons-thumbs-up',
            'menu_position' => 8,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'capabilities' => [
                'create_posts' => 'do_not_allow',
            ],
        ];


        if ($this->dataType->isPost($vehicleClassID)) {
            $this->setFromPost($vehicleClassID);
        } elseif (!empty($vehicleClassID)) {
            $this->setFromVehicleClass($vehicleClassID, $allRates);
        }
    }

    public function setActiveRateFromApi($vehicle_class_id, $data)
    {
        $this->baseRate = $data->daily_rate;
        $this->vehicleClassId = $vehicle_class_id;
        $this->minuteRate = $data->minute_rate;
        $this->hourlyRate = $data->hourly_rate;
        $this->dailyRate = $data->daily_rate;
        $this->weeklyRate = $data->weekly_rate;
        $this->monthlyRate = $data->monthly_rate;
    }

    public function create()
    {

        //ojo si da problemas con esot
        $this->postArg = array_merge(
            $this->postArg,
            array(
                'post_title' => 'Active Rate ' . ($this->id ?? ''),
                'post_name' => 'Active Rate ' . ($this->id ?? '')
            )
        );
        $post_id = wp_insert_post($this->postArg);
        $this->post_id = $post_id;
        hq_update_post_meta($post_id, $this->metaVehicleIdClass, $this->vehicleClassId);
        hq_update_post_meta($post_id, $this->metaBaseRate, $this->baseRate);
        hq_update_post_meta($post_id, $this->metaMinuteRate, $this->minuteRate);
        hq_update_post_meta($post_id, $this->metaHourRate, $this->hourlyRate);
        hq_update_post_meta($post_id, $this->metaDailyRate, $this->dailyRate);
        hq_update_post_meta($post_id, $this->metaWeeklyRate, $this->weeklyRate);
        hq_update_post_meta($post_id, $this->metaMonthlyRate, $this->monthlyRate);
    }

    public function find($vehicleClassPostId)
    {
        $args = array_merge(
            $this->postArg,
            array(
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => $this->metaVehicleIdClass,
                        'value' => $vehicleClassPostId,
                        'compare' => '='
                    )
                )
            )
        );
        $query = new \WP_Query($args);
        return $query->posts;
    }

    public function first()
    {
        // TODO: Implement first() method.
    }

    public function all($order = 'daily')
    {
        $args = array_merge(
            $this->postArg,
            array(
                'order' => 'ASC',
                'orderby' => 'meta_value_num',
                'meta_key' => (!(empty($order))) ? $this->getOrderMetaForQuery($order) : $this->metaDailyRate
            )
        );
        $query = new \WP_Query($args);
        return $query->posts;
    }

    public function setFromPost($post)
    {
        foreach ($this->getAllMetaTag() as $property => $metaKey) {
            $this->{$property} = get_post_meta($post->ID, $metaKey, true);
        }
    }

    /***
     * Maps Class Properties with Posts Metas
     * @return array
     */
    public function getAllMetaTag()
    {
        return array(
            'vehicleClassId' => $this->metaVehicleIdClass,
            'baseRate' => $this->metaBaseRate,
            'minuteRate' => $this->metaMinuteRate,
            'hourlyRate' => $this->metaHourRate,
            'dailyRate' => $this->metaDailyRate,
            'weeklyRate' => $this->metaWeeklyRate,
            'monthlyRate' => $this->metaMonthlyRate
        );
    }

    public function getQueryArgumentsFromVehicleClass($vehicleClassID)
    {
        return array_merge(
            $this->postArg,
            array(
                'meta_query' => array(
                    array(
                        'key' => $this->metaVehicleIdClass,
                        'value' => $vehicleClassID,
                        'compare' => '='
                    )
                )
            )
        );
    }

    public function setFromVehicleClass($vehicleClassId, $getAllRates = null)
    {
        $query = new \WP_Query($this->getQueryArgumentsFromVehicleClass($vehicleClassId));
        $post = $query->posts[0];
        foreach ($this->getAllMetaTag() as $property => $metakey) {
            $this->{$property} = get_post_meta($post->ID, $metakey, true);
        }
    }

    public function allRatesFromVehicleClass($vehicleClassId)
    {
        $query = new \WP_Query($this->getQueryArgumentsFromVehicleClass($vehicleClassId));
        $rates = [];
        foreach ($query->posts as $postRate) {
            $rates[] = new HQRentalsModelsActiveRate($postRate);
        }
        return $rates;
    }

    public function formatRateForDisplay($rate, $decimals = 2)
    {
        if ($rate and $rate !== "0.00") {
            return number_format((float)$rate, $decimals, '.', '');
        } else {
            return '';
        }
    }

    public function getFormattedBaseRate()
    {
        return $this->formatRateForDisplay($this->baseRate->amount);
    }

    public function getFormattedBaseRateAsNumber()
    {
        return (float)$this->getFormattedBaseRate();
    }

    public function getFormattedMinuteRate()
    {
        return $this->formatRateForDisplay($this->minuteRate->amount);
    }

    public function getFormattedMinuteRateAsNumber()
    {
        return (float)$this->getFormattedMinuteRate();
    }

    public function getFormattedHourlyRate()
    {
        return $this->formatRateForDisplay($this->hourlyRate->amount);
    }

    public function getFormattedHourlyRateAsNumber()
    {
        return (float)$this->getFormattedHourlyRate();
    }

    public function getFormattedDailyRate()
    {
        return $this->formatRateForDisplay($this->dailyRate->amount);
    }

    public function getDailyRateAmountForDisplay()
    {
        return $this->dailyRate->amount_for_display;
    }

    public function getMonthlyRateAmountForDisplay()
    {
        return $this->monthlyRate->amount_for_display;
    }

    public function getFormattedDailyRateAsNumber()
    {
        return (float)$this->getFormattedDailyRate();
    }

    public function getFormattedWeeklyRate()
    {
        return $this->formatRateForDisplay($this->weeklyRate->amount);
    }

    public function getFormattedWeeklyRateAsNumber()
    {
        return (float)$this->getFormattedWeeklyRate();
    }

    public function getFormattedMonthlyRate($decimals = 2)
    {
        return $this->formatRateForDisplay($this->monthlyRate->amount, $decimals);
    }

    public function getFormattedMonthlyRateAsNumber()
    {
        return (float)$this->getFormattedMonthlyRate();
    }

    public function getOrderMetaForQuery($order)
    {
        switch ($order) {
            case 'minute':
                return $this->metaMinuteRate;
                break;
            case 'hourly':
                return $this->metaHourRate;
                break;
            case 'daily':
                return $this->metaDailyRate;
                break;
            case 'weekly':
                return $this->metaWeeklyRate;
                break;
            case 'monthly':
                return $this->metaMonthlyRate;
                break;
            default:
                return $this->metaDailyRate;
                break;
        }
    }

    public function ratePublicInterface()
    {
        $data = new \stdClass();
        $data->dailyRate = $this->getFormattedDailyRate();
        $data->dailyRateAmountForDisplay = $this->getDailyRateAmountForDisplay();
        $data->monthlyRate = $this->getFormattedMonthlyRate();
        $data->monthlyRateAmountForDisplay = $this->getMonthlyRateAmountForDisplay();
        return $data;
    }

    public function getDailyRateObject()
    {
        return $this->dailyRate;
    }
    public function parseDataToSaveOnDB(): array
    {
        return array(
            'vehicle_class_id' => $this->vehicleClassId,
            'minute_rate_currency' => $this->minuteRateCurrency,
            'minute_rate_currency_icon' => $this->minuteRateCurrencyIcon,
            'minute_rate_amount' => $this->minuteRateAmount,
            'minute_rate_usd_amount' => $this->minuteRateUSDAmount,
            'minute_rate_amount_for_display' => $this->minuteRateAmountForDisplay,
            'hourly_rate_currency' => $this->hourlyRateCurrency,
            'hourly_rate_currency_icon' => $this->hourlyRateCurrencyIcon,
            'hourly_rate_amount' => $this->hourlyRateAmount,
            'hourly_rate_usd_amount' => $this->hourlyRateUSDAmount,
            'hourly_rate_amount_for_display' => $this->hourlyRateAmountForDisplay,
            'daily_rate_currency' => $this->dailyRateCurrency,
            'daily_rate_currency_icon' => $this->dailyRateCurrencyIcon,
            'daily_rate_amount' => $this->dailyRateAmount,
            'daily_rate_usd_amount' => $this->dailyRateUSDAmount,
            'daily_rate_amount_for_display' => $this->dailyRateAmountForDisplay,
            'weekly_rate_currency' => $this->weeklyRateCurrency,
            'weekly_rate_currency_icon' => $this->weeklyRateCurrencyIcon,
            'weekly_rate_amount' => $this->weeklyRateAmount,
            'weekly_rate_usd_amount' => $this->weeklyRateUSDAmount,
            'weekly_rate_amount_for_display' => $this->weeklyRateAmountForDisplay,
            'monthly_rate_currency' => $this->monthlyRateCurrency,
            'monthly_rate_currency_icon' => $this->monthlyRateCurrencyIcon,
            'monthly_rate_amount' => $this->monthlyRateAmount,
            'monthly_rate_usd_amount' => $this->monthlyRateUSDAmount,
            'monthly_rate_amount_for_display' => $this->monthlyRateAmountForDisplay,
            'decreasing_rates_based_on_intervals' => $this->decreasingRateBasedOnIntervals,
            'decreasing_rates_based_on_intervals_hourly' => $this->decreasingRateBasedOnIntervalsHourly,
            'decreasing_rates_based_on_intervals_per_minute' => $this->decreasingRateBasedOnIntervalsPerMinute,
        );
    }
    public function getDataToCreateTable()
    {
        return array(
            'table_name' => $this->tableName,
            'table_columns' => $this->columns
        );
    }
    public function getTableName(): string
    {
        return $this->tableName;
    }
    public function setDBFromAPI($vehicleClassId, $rate)
    {
        $this->vehicleClassId = $vehicleClassId;
        $this->minuteRateCurrency = $rate->minuteRate->currency;
        $this->minuteRateCurrencyIcon = $rate->minuteRate->currency_icon;
        $this->minuteRateAmount = (float)$rate->minuteRate->amount;
        $this->minuteRateUSDAmount = $rate->minuteRate->usd_amount;
        $this->minuteRateAmountForDisplay = $rate->minuteRate->amount_for_display;
        $this->hourlyRateCurrency = $rate->hourlyRate->currency;
        $this->hourlyRateCurrencyIcon = $rate->hourlyRate->currency_icon;
        $this->hourlyRateAmount = (float) $rate->hourlyRate->amount;
        $this->hourlyRateUSDAmount = $rate->hourlyRate->usd_amount;
        $this->hourlyRateAmountForDisplay = $rate->hourlyRate->amount_for_display;
        $this->dailyRateCurrency = $rate->dailyRate->currency;
        $this->dailyRateCurrencyIcon = $rate->dailyRate->currency_icon;
        $this->dailyRateAmount = (float)$rate->dailyRate->amount;
        $this->dailyRateUSDAmount = $rate->dailyRate->usd_amount;
        $this->dailyRateAmountForDisplay = $rate->dailyRate->amount_for_display;
        $this->weeklyRateCurrency = $rate->weeklyRate->currency;
        $this->weeklyRateCurrencyIcon = $rate->weeklyRate->currency_icon;
        $this->weeklyRateAmount = (float)$rate->weeklyRate->amount;
        $this->weeklyRateUSDAmount = $rate->weeklyRate->usd_amount;
        $this->weeklyRateAmountForDisplay = $rate->weeklyRate->amount_for_display;
        $this->monthlyRateCurrency = $rate->monthlyRate->currency;
        $this->monthlyRateCurrencyIcon = $rate->monthlyRate->currency_icon;
        $this->monthlyRateAmount = (float)$rate->monthlyRate->amount;
        $this->monthlyRateUSDAmount = $rate->monthlyRate->usd_amount;
        $this->monthlyRateAmountForDisplay = $rate->monthlyRate->amount_for_display;
    }
    public function getDailyRateObjectDB()
    {
        return $this->daily_rate;
    }
    public function getDailyRateAmountForDisplayDB()
    {
        return $this->getDailyRateObjectDB()->amount_for_display;
    }
}
