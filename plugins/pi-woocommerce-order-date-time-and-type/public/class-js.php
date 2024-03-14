<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
class pi_dtt_js{

    public $plugin_name;
    
    function __construct(){
        $this->plugin_name = 'pi-woocommerce-order-date-time-and-type-pro';
        add_action( 'wp_enqueue_scripts', array($this,'addJs'),990 );
    }

    function addJs(){
        if( is_checkout() ){
            $this->addJsFile();
            $this->addLocalizedJs();
        }
    }

    function addJsFile(){
        
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'jquery-blockui' );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pi-woocommerce-order-date-time-and-type-pro-public.js', array( 'jquery','jquery-ui-datepicker', 'jquery-blockui', 'selectWoo' ),'3.3.2.6');

        if(defined( 'ET_CORE_VERSION' ) && apply_filters('pi_dtt_enable_divi_compatibility_js', true)){
            wp_enqueue_script( $this->plugin_name.'-divi-compatible', plugin_dir_url( __FILE__ ) . 'js/pi-divi-page-builder.js', array( 'jquery'),'3.3.9.3');
        }
        
        wp_enqueue_script( $this->plugin_name.'-save-checkout', plugin_dir_url( __FILE__ ) . 'js/save-checkout-data.js', array( 'jquery'),'3.3.2.6');
        wp_register_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'selectWoo' );
        wp_enqueue_style( 'select2', WC()->plugin_url() . '/assets/css/select2.css');
        
    }

    function addLocalizedJs(){
            
        $localize_values = self::dateOptions();
        wp_localize_script( 'pi-woocommerce-order-date-time-and-type-pro', 'pi_date_options', $localize_values);
        
    }

    static function dateOptions(){
        $minDate = (int)pisol_dtt_get_setting('pi_order_preparation_days',0);
        $pre_order_days = empty(pisol_dtt_get_setting('pi_preorder_days', 10)) ? 0 : abs(pisol_dtt_get_setting('pi_preorder_days', 10));
        $allowed_dates = self::allowedDates();

        $maxDateBasedOnDates = self::longestDateAwayFromToday($allowed_dates);
        if($maxDateBasedOnDates <= ($pre_order_days+$minDate)){
            $maxDate = apply_filters('pisol_calendar_max_date',$pre_order_days+$minDate);
        }elseif($maxDateBasedOnDates > ($pre_order_days+$minDate)){
            $maxDate = apply_filters('pisol_calendar_max_date',$maxDateBasedOnDates);
        }

        $minDate = self::minDateBasedOnAvailableDates($minDate, $maxDate, $allowed_dates);
        
        $disable_ajax_loading_location = pi_dtt_pickup_location::isLocationPresentInSystem() ? false : true;

        $return  = array(
            'ajaxUrl'=>admin_url('admin-ajax.php'),
            'minDate' => $minDate,
            'maxDate' => $maxDate,
            'allowedDates' => $allowed_dates,
            'allSlotsBooked'=>__('All time slots booked for this date','pisol-dtt'),
            'selectTimeSlot' => __('Time', 'pisol-dtt'),
            'todaysDate'=>current_time('Y/m/d'),
            'datePlaceholder' => __('Date','pisol-dtt'),
            'autoSelectDate'=>apply_filters('pisol_auto_select_date', '0'), // false, first, last
            'autoSelectTime'=>apply_filters('pisol_auto_select_time', '0'),
            'allDatesBooked'=>__('All dates are booked', 'pisol-dtt'),
            'disableAjaxLocationReload'=>apply_filters('pisol_disable_ajax_location',true),
            'typeSupportingPickupLocation'=> apply_filters('pisol_dtt_type_supporting_pickup_location','')
        );
        return apply_filters('pisol_dtt_settings_filter',$return);
    }

    static function allowedDates(){
        $obj = new pi_dtt_date();
        $dates = $obj->getValidDates();
        return !empty($dates) ? array_values($dates) : array();
    }

    static function minDateBasedOnAvailableDates($minDate, $maxDate, $allowed_dates){
        
        if(empty($allowed_dates) || !is_array($allowed_dates)) return $minDate;
        $today = current_time('Y/m/d');
        $today_timestamp = strtotime($today);
        $shortest_date = "";
        foreach($allowed_dates as $date){
            if(empty($shortest_date)){
                $shortest_date = $date;
            }

            $date_timestamp = strtotime($date);
            $shortest_date_timestamp = strtotime($shortest_date);
            if($date_timestamp < $shortest_date_timestamp){
                $shortest_date = $date;
            }
            
        }

        $date1 = date_create($today);
        $date2 = date_create($shortest_date);
        $diff = date_diff($date1,$date2);
        $minDate = $diff->format('%a');
        if($minDate < 0 || $minDate > $maxDate) return 0;
        return $minDate;
    }

    static function longestDateAwayFromToday($dates){
        if(!is_array($dates)) array();
        $today = current_time('Y/m/d');
        $today_timestamp = strtotime($today);
        $longest_date = $today;
        foreach($dates as $date){
           
            $date_timestamp = strtotime($date);
            $longest_date_timestamp = strtotime($longest_date);
            if($date_timestamp > $longest_date_timestamp){
                $longest_date = $date;
            }
            
        }
        $date1=date_create($today);
        $date2=date_create($longest_date);
        $diff = date_diff($date1,$date2);
        return $diff->format('%a');
    }
}

new pi_dtt_js();