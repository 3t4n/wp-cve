<?php

/**
 * Fired during plugin activation
 *
 * @link       https://http://phoeniixx.com/
 * @since      1.0.0
 *
 * @package    Phoen_Pincode_Zipcode
 * @subpackage Phoen_Pincode_Zipcode/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Phoen_Pincode_Zipcode
 * @subpackage Phoen_Pincode_Zipcode/includes
 * @author     PHOENIIXX TEAM <raghavendra@phoeniixx.com>
 */
class Phoen_Pincode_Zipcode_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        global $table_prefix, $wpdb;

        $pincodeTableSettingName    = $wpdb->prefix . 'pincode_zipcode_setting_free';

        $pincodeOldTableName        = $table_prefix . 'check_pincode_p';
        // $pincodeTableName           = $table_prefix . 'pincode_zipcode_list_free';

        if($wpdb->get_var( "show tables like '$pincodeOldTableName'" ) == $pincodeOldTableName) {
            $wpdb->query("ALTER TABLE `".$pincodeOldTableName."` ADD `country` VARCHAR(50) NOT NULL AFTER `state`");
        }else{
            if($wpdb->get_var( "show tables like '$pincodeOldTableName'" ) != $pincodeOldTableName) {
                $sql0  = " CREATE TABLE `". $pincodeOldTableName . "` ( ";
                $sql0 .= " `id`  int(11)   NOT NULL auto_increment, ";
                $sql0 .= " `pincode`  varchar(250)   NOT NULL, ";
                $sql0 .= " `city`  varchar(250)   NOT NULL, ";
                $sql0 .= " `state`  varchar(250)   NOT NULL, ";
                $sql0 .= " `country`  varchar(250)   NOT NULL, ";
                $sql0 .= " `dod`  int(11)   NOT NULL, ";
                $sql0 .= " `cod`  varchar(250)   NOT NULL DEFAULT 'no', ";
                $sql0 .= "  PRIMARY KEY `order_id` (`id`) "; 
                $sql0 .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
                require_once(ABSPATH . '/wp-admin/upgrade-functions.php');
                dbDelta($sql0);
            }
        }

        if($wpdb->get_var( "show tables like '$pincodeTableSettingName'" ) != $pincodeTableSettingName) {
            $sql1  = " CREATE TABLE `". $pincodeTableSettingName . "` ( ";
            $sql1 .= " `id` int(11) NOT NULL auto_increment, ";
            $sql1 .= " `enter_pincode_heading` varchar(250) NULL, ";
            $sql1 .= " `check_btn_name` varchar(250) NULL, ";
            $sql1 .= " `available_pincode_heading` varchar(250) NULL, ";
            $sql1 .= " `change_btn_name` varchar(250) NULL, ";
            $sql1 .= " `show_state` varchar(250) NULL, ";
            $sql1 .= " `show_city` varchar(250) NULL, ";
            $sql1 .= " `cod_heading` varchar(250) NULL, ";
            $sql1 .= " `cod_help_text` varchar(250) NULL, ";
            $sql1 .= " `dod_heading` varchar(250) NULL, ";
            $sql1 .= " `enable_delivery_date` varchar(250) NULL DEFAULT 'yes', ";
            $sql1 .= " `delivery_date_help_text` varchar(250) NULL, ";
            $sql1 .= " `box_bg_color` varchar(250) NULL, ";
            $sql1 .= " `label_txt_color` varchar(250) NULL, ";
            $sql1 .= " `btn_txt_color` varchar(250) NULL, ";
            $sql1 .= " `btn_bg_color` varchar(250) NULL, ";
            $sql1 .= " `pincode_verify_error` varchar(250) NULL, ";
            $sql1 .= " `pincode_input_error` varchar(250) NULL, ";
            $sql1 .= " `wrong_pincode_error` varchar(250) NULL, ";
            $sql1 .= "  PRIMARY KEY `order_id` (`id`) "; 
            $sql1 .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
            require_once(ABSPATH . '/wp-admin/upgrade-functions.php');
            dbDelta($sql1);
            
            $insert = [
                'enter_pincode_heading'     => 'Available Check AT.',
                'check_btn_name'            => 'CHECK',
                'available_pincode_heading' => 'Availability At',
                'change_btn_name'           => 'CHANGE',
                'show_state'                => 'yes',
                'show_city'                 => 'yes',
                'cod_heading'               => 'Cash On Delivery',
                'cod_help_text'             => 'Cash On Delivery',
                'dod_heading'               => 'Item Delivery By,',
                'delivery_date_help_text'   => 'Delivery Date Help Text', 
                'box_bg_color'              => '#f4f2f2', 
                'label_txt_color'           => '#737070', 
                'btn_bg_color'              => '#a46497', 
                'btn_txt_color'             => '#ffffff',
                'pincode_verify_error'      => 'Please verify pincode before Add to cart.',
                'pincode_input_error'       => 'Pincode field is required',
                'wrong_pincode_error'       => 'Oops! we are not servicing given pincode'
            ];
            $rows_affected = $wpdb->insert( $pincodeTableSettingName, $insert);
            dbDelta( $rows_affected );
        }
    }

}
