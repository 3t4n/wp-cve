<?php
/**
 * The Config class
 *
 * @package		LetAProDoIT.Helpers
 * @filename	Config.php
 * @version		2.0.0
 * @author		Sharron Denice, Let A Pro Do IT! (www.letaprodoit.com)
 * @copyright	Copyright 2016 Let A Pro Do IT! (www.letaprodoit.com). All rights reserved
 * @license		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @brief		Global functions used by various services
 *
 */	
class LAPDI_Config
{
    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @param object conn - The database connection
     *
     * @return void
     *
     */
    function __construct($conn = null) 
    {
        $this->conn = $conn;
    }

    /**
     * Function to get a config value
     *
     * @since 1.0.0
     *
     * @param string find - The config value to find
     *
     * @return obj config value
     *
     */
    public static function get($find)
    {
 		$levels = substr_count($find, '.') + 1;
 		$config_arrkey = null;

        // There will always be at least 2 levels
        list($null, $config_key) = preg_split("/\./", $find, $levels);

        // There will never be more than three levels
        if ($levels == 3)
        {
            list($null, $null, $config_arrkey) = preg_split("/\./", $find, $levels);
        }

        if (!is_array(LAPDI_Settings::${$config_key}) || (is_array(LAPDI_Settings::${$config_key}) && empty($config_arrkey)))
        {
            return LAPDI_Settings::${$config_key};
        }
        else if (is_array(LAPDI_Settings::${$config_key}) && !empty($config_arrkey))
        {
            return LAPDI_Settings::${$config_key}[$config_arrkey];
        }    
        
        return "";
    }
}

/**
 * TSP_Config
 *
 * @since 1.0.0
 *
 * @deprecated 2.0.0 Please use LAPDI_Config instead
 *
 * @return void
 *
 */
class TSP_Config extends LAPDI_Config
{

}// end class