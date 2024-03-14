<?php
/**
 * Shiptimize
 * Logic should be handled here
 *
 * @author Shiptimize 
 * @copyright Shiptimize 
 * @license  
 * @package Shiptimize.core
 * @since   1.0.0
 */

/**
 * Main shiptimize class
 * @class Shipitmize
 */
abstract class ShiptimizeV3
{

  /**
   * abstract Shiptimize version
   *
   * @var string
   */
    protected static $shiptimize_version = '1.0.0';

    /**
     * The datamodel version
     * @var string
     */
    protected static $database_version = "2.0";

    /**
     * The single instance
     *
     * @var Shiptimize
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * The api instance
     *
     * @var ShiptimizeApi
     */
    protected static $api = null;

    /**
     * The db prefix for the  platform
     * can either be set while creating the instance of in your child class
     * @var string $db_prefix
     */
    protected $db_prefix = '';

   /** 
     * @var String $lang 
     */ 
    protected $lang = null; 

    /** 
     * @var array 
     */ 
    protected $langs = array(); 

    /** 
     * @var array lang_extras
     */ 
    protected $lang_extras = array(); 

    /**
     * Executes the sql received by param. Each platform will have a different way of accessing the database
     *
     * @param string sql
     *
     * @return bool - if the query succeded
     */
    abstract protected function executeSQL($sql);


    /**
     * Execute an sql select
     *
     * @param string $sql
     *
     * @return the results
     */
    abstract protected function sqlSelect($sql);

    /**
     * boostrap the plugin make checks and load necessary files
     */
    abstract protected function bootstrap();

    /**
     * check if all required options are set
     *
     * @return bool - true if all required options are set
     */
    abstract protected function is_options_valid();

    /** 
     * Handles an update from the api.
     * Receives a JSON object {"TrackingId":, "OrderId", "Hash", "Status"}
     * Should validate the hash before processing any updates  
     */ 
    abstract public function api_update(); 

    /**
     * get an api instance
     * @return ShiptimizeApi - an instance of the selected api version
     */
    abstract protected static function get_api( );

    /** 
     * When users update carrier settings the token is invalidated 
     * Therefore everytime we get a new valid token we should also refresh the carriers 
     */ 
    abstract protected static function refresh_token(); 

    /** 
     * Explicitly refresh the carriers from the api 
     */     
    abstract protected static function refresh_carriers( ); 

    /** 
     * @param mixed $address 
     * @param int $shipping_method_id 
     */ 
    abstract public static function get_pickup_locations($address, $shipping_method_id);
    
    /**
     * Make sure we are sending utf8 content to the api.
     *
     * @since 1.0.0
     * @param string  $content
     * @return string - the content as an utf8 string if not, else self
     */
    public function grant_localized($content)
    {
    }

    /**
     * Create the datamodel.
     *
     * @param string $data_type - the data type for the id, some platforms use ints, some use strings
     *
     * @return string containing the sql to create the shiptimize datamodel
     *
     */
    public function create_shiptimize_data_model($data_type)
    {
        return $this->executeSQL("CREATE TABLE IF NOT EXISTS `{$this->db_prefix}shiptimize` (
        `id` {$data_type}  NOT NULL,
        `status` int(11) DEFAULT NULL,
        `tracking_id` varchar(90) DEFAULT NULL,
        `message` text DEFAULT NULL,
        `carrier_id` int(11) DEFAULT NULL,
        `pickup_id` varchar(25) DEFAULT NULL,
        `pickup_label` varchar(255) DEFAULT NULL,
        `pickup_extended` varchar(255) DEFAULT NULL,
        PRIMARY KEY (`id`)
      )");
    }

    /**
     * For platforms with transitory states, where the checkout is done in multiple steps,
     * When they don't provide a SANE! way to append metadata to the order we save it on a
     * separate cart entity
     *
     * @param string $prefix - the platform prefix
     * @param string $cart_id_type - typically either string or int
     * @param string $carrier_id_type - typically either string or int
     */
    public function create_shiptimize_cart($cart_id_type, $carrier_id_type)
    {
        return $this->executeSQL("CREATE TABLE IF NOT EXISTS `{$this->db_prefix}shiptimize_cart` (
        `cart_id` {$cart_id_type}  NOT NULL,
        `carrier_id` {$carrier_id_type}  DEFAULT NULL,   
        `pickup_id` varchar(25) DEFAULT NULL,
        `pickup_label` varchar(255) DEFAULT NULL, 
        `pickup_extended`varchar(255) DEFAULT NULL,
        PRIMARY KEY (`cart_id`)
      )");
    }

    /**
     * Drop the datamodel
     * @return bool success
     */
    public function drop_shiptimize_data_model()
    {
        $sql = "DROP TABLE `{$this->db_prefix}shiptimize` ";
        $this->executeSQL($sql);

        $this->executeSQL("DROP TABLE IF EXISTS `{$this->db_prefix}shiptimize_cart`");
    }


    /**
     * return the order meta for the given id
     * @param int $order_id
     */
    public function get_order_meta($order_id)
    {
        return $this->executeSQL("select * from `{$this->db_prefix}shiptimize` where id=\"$order_id\"");
    }

   
    /**
     * Some platforms return sql results as arrays of arrays. We want to make sure we are returning an object here
     * @param int $cart_id
     * @return the meta for the cart if exists, else null
     */
    public function get_cart_meta($cart_id)
    {
        $sql = sprintf(" select * from `%sshiptimize_cart` where cart_id=%d ", $this->db_prefix, $cart_id);
        $results =  $this->sqlSelect($sql);
        return $results && is_array($results)  && count($results) ? (object)$results[0] : null;
    }

    /**
     * Set the pickup point for this cart, if there is meta for it update else add
     *
     * @param string $prefix
     * @param mixed $cart_id - the cart id can be string or int depending on platform
     * @param int pickup_id - the id from the api
     * @param string pickup_label - what to display to the user
     * @param sting $pickup_extended - json_encode( [ { pickup_extended_id:, pickup_extended_value: } ] )
     *
     * @return string the sql to update the pickup point for the cart with id cart_id
     */
    public function set_pickup_point($cart_id, $carrier_id, $pickup_id, $pickup_label, $pickup_extended)
    {
        $cart_meta = $this->get_cart_meta($cart_id);

        if ($cart_meta) {
           return  $this->update_pickup_point($cart_id, $carrier_id, $pickup_id, $pickup_label, $pickup_extended);
        } else {
           return $this->add_pickup_point($cart_id, $carrier_id, $pickup_id, $pickup_label, $pickup_extended);
        }
    }

    /**
     * @param string $prefix
     * @param mixed $cart_id - the cart id can be string or int depending on platform
     * @param int carrier_id - the id of the carrier according to the API! not the platform
     * @param int pickup_id - the id from the api
     * @param string pickup_label - what to display to the user
     * @param sting $pickup_extended - json_encode( [ { pickup_extended_id:, pickup_extended_value: } ] )
     *
     * @return string the sql to update the pickup point for the cart with id cart_id
     */
    public function update_pickup_point($cart_id, $carrier_id, $pickup_id, $pickup_label, $pickup_extended)
    {
        $sql= sprintf(
        "update `%sshiptimize_cart` set carrier_id =%d, pickup_id=\"%s\", pickup_label=\"%s\", pickup_extended=\"%s\" where cart_id=%d",
      $this->db_prefix,
      $carrier_id,
      $pickup_id,
      $pickup_label,
      $pickup_extended,
      $cart_id
    );
        return $this->executeSQL($sql);
    }

    /**
     *
     * @param string $prefix
     * @param mixed $cart_id - the cart id can be string or int depending on platform
     * @param int carrier_id - the id of the carrier according to the API! not the platform
     * @param int pickup_id - the id from the api
     * @param string pickup_label - what to display to the user
     * @param sting $pickup_extended - json_encode( [ { pickup_extended_id:, pickup_extended_value: } ] )
     *
     * @return string the sql to add the pickup point for the cart with id cart_id
     */
    public function add_pickup_point($cart_id, $carrier_id, $pickup_id, $pickup_label, $pickup_extended)
    {
        $sql = sprintf(
        "insert into `%sshiptimize_cart` (carrier_id,pickup_id,pickup_label,pickup_extended, cart_id) VALUES(%d,\"%s\",\"%s\",\"%s\",%d)",
      $this->db_prefix,
      $carrier_id,
      $pickup_id,
      $pickup_label,
      $pickup_extended,
      $cart_id
    );
        $this->executeSQL($sql);
    }

    /** 
     * Print the string $string in the lang $lang 
     *  
     * @param String $lang 
     * @param String $string 
     */ 
    public function _e($string){
        if(!$this->lang){
            $this->lang  = $this->get_lang(); 
        }

        echo $this->__($string);
    }

    /**  
     * Get the string from the correct file 
     * @param String $lang 
     * @param String $string 
     */ 
    public function __($string) {
        if(!$this->lang){
            $this->lang  = $this->get_lang(); 
        }

        // error_log("\n\nTranslating [$string]");
        $str = ''; 

        if (!isset($this->langs[$this->lang])  && file_exists( __DIR__ . '/lang/' . $this->lang.'.php')) {
            $this->langs[$this->lang] = include 'lang/' . $this->lang . '.php';    
        }

        if (!isset($this->lang_extras[$this->lang]) && file_exists( __DIR__ . '/../lang/' . $this->lang . '.php') ) {
            $this->lang_extras[$this->lang] = include  __DIR__ . '/../lang/' . $this->lang . '.php';       
        }

        # Defined in the main language file 
        if( isset($this->langs[$this->lang][$string]) ) {
            $str = $this->langs[$this->lang][$string];  
        }

        # Allow strings to be overriden in extras 
        if (isset($this->lang_extras[$this->lang]) && isset($this->lang_extras[$this->lang][$string])) {
            $str = $this->lang_extras[$this->lang][$string];
        }
        
        # If no translation was found default to the english translation 
        if (!$str && !isset($this->lang['en'][$string])) {
             
            if (!isset($this->langs['en'])  && file_exists( __DIR__ . '/lang/en.php')) {
                $this->langs['en'] = include 'lang/en.php';    
            }

            $str = isset($this->langs['en'][$string]) ? $this->langs['en'][$string] : $string; 
        }

        // error_log("\n\nString is[$str]");

        return $str; 
    }

    /** 
     * If the platform uses a locale with localization just extract the ISO2 
     */ 
    public function get_ISO2_from_localisation($locale){
        return substr($locale, 0,2); 
    }

    /** 
     * Return an iso2 string with the lang 
     */ 
    abstract function get_lang();
}
