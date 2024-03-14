<?php

/**
 * An abstract class for the order, contains the basic structure
 * for a shiptimize order object and forces implementation of
 * platform dependent methods
 *
 * @package Shiptimize.core
 * @since 1.0.0
 *
 */
abstract class ShiptimizeOrder
{

  /**
   * @var int status not exported
   */
    public static $STATUS_NOT_EXPORTED = 1;

    /**
     * @var int status exported succesfully
     */
    public static $STATUS_EXPORTED_SUCCESSFULLY = 2;

    /**
     * @var int status export error
     */
    public static $STATUS_EXPORT_ERRORS = 3;

    /**
     * @var int status test successfull
     */
    public static $STATUS_TEST_SUCCESSFUL = 4;

    /**
     * @var int label status not requested 
     */
    public static $LABEL_STATUS_NOT_REQUESTED = 5; 

    /**
     * @var int label status requested 
     */ 
    public static $LABEL_STATUS_PRINTED = 6;

    /**
     * @var int label status requested 
     */ 
    public static $LABEL_STATUS_ERROR = 7;

    /** 
     * @var int $ERROR_ORDER_EXISTS 
     */ 
    public static $ERROR_ORDER_EXISTS = 200;
 
    /** 
     * @var int $PICKUP_BEHAVIOUR_OPTIONAL
     */
    public static $PICKUP_BEHAVIOUR_OPTIONAL = 0; 

    /** 
     * @var int $PICKUP_BEHAVIOUR_MANDATORY
     **/
    public static $PICKUP_BEHAVIOUR_MANDATORY = 1; 

    /** 
     * @var int $PICKUP_BEHAVIOUR_IMPOSSIBLE
     **/
    public static $PICKUP_BEHAVIOUR_IMPOSSIBLE = 2; 


    /**
     *
     * @var string $ShopItemId - the order id  before filters are applied
     */
    public $ShopItemId=null;

    /** 
     *
     * @var string $CompanyName
     */ 
    protected $CompanyName = NULL; 

    /**
     *
     * @var string $Name -  Name of the recipient
     */
    protected $Name = null;

    /**
     *
     * @var string $ClientReference - the order number after filters are applied
     */
    protected $ClientReferenceCode = null;

    /**
     *
     * @var string $Streetname - the first line of the shipping address
     */
    protected $Streetname1 = null;

    /**
     *
     * @var string $Streetname2 - the second line of the shippping ddress
     */
    protected $Streetname2 = null;

    /**
     *
     * @var string $HouseNumber - the house number - applicable in some addresses
     */
    protected $HouseNumber = null;

    /**
     *
     * @var string $NumberExtension - the number extension ex. App B,A, etc.
     */
    protected $NumberExtension = null;

    /**
     *
     * @var string Postalcode - the shipping address postal code
     */
    protected $PostalCode = null;

    /**
     *
     * @var string $City - the shipping address City
     */
    protected $City = null;

    /** 
     * @var string $State - the State/Province/County when applicable 
     */ 
    protected $State = null; 

    /**
     *
     * @var string $Country - the shipping address country
     */
    protected $Country = null;

    /** 
     * @var String $Neighborhood
     */ 
    protected $Neighborhood = null; 

    /** 
     * @var string $CPF
     */ 
    protected $CPF = null; 
    
    /** 
     * @var string $CNPJ
     */ 
    protected $CNPJ = null;   

    /**
     *
     * @var string $Phone - the shipping phone
     */
    protected $Phone = null;

    /**
     * Transporter - the carrier id 
     */
    protected $Transporter = '';

    /**
     *
     * @var string $Email - the shipping email
     */
    protected $Email = null;

    /**
     *
     * @var string $Weight - the total weight of this order in grams 
     */
    protected $Weight = null;

    /**
     *
     * @var number $Length -  length  of this order in cm 
     */
    protected $Length = null;

    /**
     *
     * @var number $Height -   height of this order in cm
     */
    protected $Height = null;

    /** 
     * @var number Width
     */
    protected $Width = null; 

    /**
     *
     * @var int CustomsType - customs information: type of shipment
     */
    protected $CustomsType = null;

    /**
     *
     * @var string Description - a short description of the package content
     */
    protected $Description = null;

    /**
     *
     * @var string HSCode - the customs HS code
     */
    protected $HSCode = null;

    /**
     *
     * @var decimal Value - the total value of the shipped items
     */
    protected $Value = null;

    /** 
     * @var string pointId  - should fit in a varchar(25)
     */ 
    protected $PointId = null;

    /** 
     *
     * @var mixed array of ExtendedInfo { FieldName - the name, FieldId - the point id a string, Tekst - inserted by the client } - the id of the pickupPoint 
     */ 
    protected $ExtendedInfo = array(); 

    /** 
     * @param string ShippingMethodId - the shipping method id to be used in rules over the API 
     */ 
    protected $ShippingMethodId = ''; 

    /** 
     *  @param string ShippingMethodName - the shipping method name to be used in rules over the API;
     */
    protected $ShippingMethodName = ''; 

    /**
    * @var string[] errors
    */
    protected $errors = array();

    /**
     * @var Number shiptimize_status
     */
    protected $shiptimize_status = 0;

    /**
     *
     * @var string message
     */
    protected $shiptimize_message = '';

    /** 
     * @var integer - optional - an extra option 
     */ 
    protected $extraOptionId = '';

    /** 
     * @var array ShipmentItems - optional - a list of the items in the shipment 
     */ 
    protected $ShipmentItems = array(); 

    /** 
     * @var array A list of options to send to the api 
     */ 
    protected $OptionList = array(); 

    /**
     * A list of status ids and their localized values
     * TODO: pending API, the api will send localized messages
     */
    public static $status_text =  array(
      1 => 'Not Exported',
      2 => 'Exported',
      3 => 'Exported Error',
      4 => 'Test Succesfull'
    );

    /**
     *
     * @var string id - the system identifier for the order
     * @return ShiptimizeOrder
     */
    public function __construct($id)
    {
        $this->ShopItemId = $id;
        $this->bootstrap();
    }


    /**
     * Executes the sql received by param. Each platform will have a different way of accessing the database
     *
     * @param string sql
     *
     * @return bool - if the query succeded
     */
    abstract protected function executeSQL($sql);

    /**
     * insert order meta , don't forget to escape the strings
     *
     * @param {type} $order_id - the type is defined by the platform, usually int but it can be a string
     * @param int $status
     * @param int $carrier_id
     * @param int $pickup_id
     * @param string $pickup_label
     * @param string $pickup_extended
     * @param string $tracking_id
     * @param string $message
     */
    public function add_order_meta($order_id, $status, $carrier_id, $pickup_id, $pickup_label, $pickup_extended, $tracking_id ='', $message='')
    {
        $sql = sprintf(
        "insert into `{$this->db_prefix}shiptimize` 
           (id,  `status`,carrier_id,pickup_id,pickup_label,pickup_extended,tracking_id,message) VALUES(\"%s\",%d,%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\") ",
      $order_id,
      $status,
      $carrier_id,
      $pickup_id,
      $pickup_label,
      $pickup_extended,
      $tracking_id,
      $message
    );

        return $this->executeSQL($sql);
    }

    /**
     * sets the order status
     * @param int $order_id
     * @param int $status
     */
    public function set_order_status($order_id, $status)
    {
        $sql = sprintf("update `{$this->db_prefix}shiptimize` set status=%d where id=%d", $order_id, $status);
        return $this->executeSQL($sql);
    }

    public function set_label_meta($order_id,$status,$labelurl,$msg) 
    {
        if (!$order_id) {
            error_log("no order id was provided to set the label meta, ignoring"); 
            return; 
        }
 
        $msg  = '<br/>' . date('Y-m-d') . ' ' . $msg; 
        $sql = sprintf("update `{$this->db_prefix}shiptimize` set status=%d, message=\"%s\", labelurl=\"%s\" where id=%d", $status, $msg, $labelurl, $order_id);
        error_log("\n\n$msg");
        return $this->executeSQL($sql);        
    }
 

    /** 
     * @param int - the carrier id as understood by the Shiptimize Api 
     */  
    public function set_transporter($carrier_id){
        $this->Transporter = $carrier_id; 
    }

    /**
     * Execute an sql select
     *
     * @param string $sql
     *
     * @return the results
     */
    abstract protected function sqlSelect($sql);

    /**
     * get the necessary fields from the system and translate the system order into a shiptimize order
     */
    abstract protected function bootstrap();

    /**
     * sets the order number applying the filters
     */
    abstract protected function set_client_reference();

    /**
     * Set the message for this order
     *
     * @param string message
     */
    public function set_message($message)
    {
        $this->executeSQL(" update `{$this->db_prefix}shiptimize` set message=\"$message\"  where id= {$this->id}");
    }

    /**
     * Add a message to the existing list of messages 
     * If you want to append a date make sure to run the getFormatedMessage before 
     * 
     * @param string message 
     */ 
    public function add_message($message){
        $meta = $this->get_order_meta();  
        $previous_message = is_array($meta) ? $meta['message'] : $meta->message; 

        $sql = sprintf("update %sshiptimize set message=\"%s\" where id=%d",
            $this->db_prefix,
            $previous_message.$message,
            $this->ShopItemId
        );

        return $this->executeSQL($sql);
    }

    /** 
     * Appends new line and current date to message 
     */ 
    public static function get_formated_message($message){
        return "<br/>".date("d/m").' - '.$message;  
    }

    /** 
     * @param mixed $errors - array(Id, Tekst)
     */ 
    public function append_errors($errors){
        $messages = ''; 

        foreach ($errors as $error) {
            if( isset($error->Id) && $error->Id == ShiptimizeOrder::$ERROR_ORDER_EXISTS){
                $this->set_status(ShiptimizeOrder::$STATUS_EXPORTED_SUCCESSFULLY);
                $this->add_message($this->get_formated_message("Order Exists"));
            }
            else {
                $messages.= $this->get_formated_message( isset($error->Tekst) ? $error->Tekst : var_export($error,true) );
            }  
        }  

        $this->add_message($messages);
    }

    /**
     * Set the message for this order
     *
     * @param int status
     */
    public function set_status($status)
    {
        $this->executeSQL(" update `{$this->db_prefix}shiptimize` set status=$status  where id= {$this->ShopItemId}");
    }

    /** 
     * @param number $status - this is mapped in tables shared between the plugin and the api - check the plugin docs 
     * we should append a message to the order saying a status was pushed from the api 
     */ 
    abstract public function set_status_from_api($status);

    /** 
     * @param string tracking_id 
     * we should append a message saying the tracking id was pushed from the api 
     * this should also be appended to the order details so the client can check it 
     */
    abstract public function set_tracking_id($tracking_id);
    

    /**
     * Returns the status the user selected in the plugin options as order status to export on "export all"
     * @return array of valid status, type will depend on platform either string or int
     */
    abstract public static function get_valid_status_to_export_all();

    /**
     * If there is not meta for this order create it
     *
     * @param int $order_id
     */
    public function grant_order_meta_exists($order_id)
    {
        $meta = self::get_order_meta($order_id);

        if (! $meta) {
            $sql = sprintf(" insert into `%sshiptimize` (`id`) VALUES( %d ) ", $this->db_prefix, $order_id);
            $this->executeSQL($sql);
        }
    }

    /** 
     * @return String country 
     */ 
    public function get_country() {
        return $this->Country; 
    }

    /** 
     * @return int the Carrier id associated with this order according to the API 
     */ 
    public function get_carrier_id(){
        return $this->Transporter; 
    }

    /**
     * Retrieve shiptimize metadata for the order with id
     * The type of the id will vary with the platform
     * usually int or string
     * 
     */
    public function get_order_meta()
    {
        $results = $this->sqlSelect(" select * from `{$this->db_prefix}shiptimize` where id={$this->ShopItemId}");
        return count($results) ? $results[0] : null;
    }

    /**
     * return the status for this order
     * @return int status
     */
    public function get_order_status()
    {
        return $this->shiptimize_status;
    }

    /**
     * returns the shiptimize message for this order
     * @return string - the message for this order
     */
    public function get_order_message()
    {
        return $this->shiptimize_message;
    }

    /**
     *
     * @return String ClientReference
     */
    public function get_client_reference()
    {
        return $this->ClientReference;
    }

    /**
     *
     * @return mixed - ShopItemId
     */
    public function get_shop_item_id()
    {
        return $this->ShopItemId;
    }

    /** 
    *  @return string 
    */ 
    public function get_state(){
        return $this->State;
    }


    /**
     * @return string - a string containing all the error messages
     */
    public function get_error_messages()
    {
        $errors = '';

        foreach ($this->errors as $error) {
            $errors .= $error;
        }

        return $errors;
    }

    /**
     * @return boolean true if this order is valid
     */
    public function is_valid()
    {
        return $this->is_name_valid() && $this->is_address_valid();
    }

    /**
     * @return boolean true if the name for this address is valid
     */
    public function is_name_valid()
    {
        if (!($nameValid = ($this->Name))) {
            $this->errors[] = 'Name is required';
        }
        return $nameValid;
    }

    public function is_weight_valid(){
        return $this->Weight && !is_integer($this->Weight);
    }

    /**
     * Checks if the address is correctly set for this order.
     * This is important because some plugins may change the order meta
     * and save crucial address parts in other fields
     *
     * @since 1.0.0
     * @return boolean - true If the address contains all required fields
     */
    public function is_address_valid()
    {
        //TODO: consider does it make sense to have a special validation by country? how does the app handle different country addresses ?
        $addressValid = (trim($this->Streetname1) || trim($this->Streetname2) ) != '' && trim($this->PostalCode) != '' && trim($this->City) != '' && trim($this->Country) != '' ;

        if (!$addressValid) {
            $this->errors[] = 'Invalid Shipping Address 
            <br/>Streetname1: '. $this->Streetname1 
            . '<br/>Streetname2: '. $this->Streetname2
            . '<br/>Postalcode: '. $this->PostalCode 
            . '<br/>City: '. $this->City  
            . ' <br/>Country: '. $this->Country;
        }

        return $addressValid;
    }

    /** 
     * Sometimes systems are messy in how they assign data and this may trigger api errors 
     */
    public function normalizeData(){
        if(is_numeric($this->Streetname2)){
            $this->HouseNumber = $this->Streetname2;
            $this->Streetname2 = "";
        }

//      Sometimes people will input '-' To mean Idfk why are you asking me to input this?
        if($this->Phone && strlen($this->Phone) < 3){
            $this->add_message($this->get_formated_message("Invalid Phone [$this->Phone] ignoring"));
            $this->Phone = ''; 
        }

        if($this->State && strlen($this->State) < 2){
            $this->add_message($this->get_formated_message("Invalid State [$this->State] ignoring"));
            $this->State = ''; 
        }

        if($this->CompanyName && strlen($this->CompanyName) < 3){
            $this->add_message($this->get_formated_message("Invalid CompanyName[$this->CompanyName] ignoring "));
            $this->CompanyName = '';
        }

        $this->Description = $this->escape_text_data($this->Description); 
   
        if($this->Description && strlen($this->Description) > 255 ){
            $this->Description = substr( $this->Description, 0, 255);  
            $chars = str_split($this->Description);  
           
            //Make sure we are not sending a broken special char 
            for($i = 254; $i > 251; --$i){
                if( $chars[$i] == '&'){
                    $this->Description = substr($this->Description, 0, $i ); 
                }
            } 
        }

        if($this->PostalCode && strlen($this->PostalCode) > 15){
            $originalPostalCode = $this->PostalCode;

            $words = explode(" ", $this->PostalCode); 
            $validPostalCode = '';
            for ( $i = 0;  $i < count($words) && strlen($validPostalCode . " " . $words[$i]) < 15 ; ++$i ){
                $validPostalCode .= ($i ? " " : "") . $words[$i]; 
            }

            $this->PostalCode = $validPostalCode;
            $this->add_message($this->get_formated_message("$originalPostalCode to large. Ignoring city name will send $this->PostalCode "));
        }
    }

    /** 
     * Remove all non-latin one characters we can find since our app does not support it 
     */ 
    public function escape_non_latin1 ($str){
        $normalize = array(
            'Ā'=>'A','Ă'=>'A','Ą'=>'A','Ḁ'=>'A', 'Ắ'=>'A',
            'Ḃ'=>'B','Ḅ' => 'B', 'Ḇ' => 'B',
            'Ć'=>'C','Ĉ'=>'C','Ċ'=>'C','Č'=>'C','Ḉ' => 'C',
            'Đ'=>'D','Ḋ' => 'D','Ḍ' => 'D','Ḏ' => 'D','Ḑ' => 'D','Ḓ' => 'D',
            'Ē'=>'E','Ĕ'=>'E','Ė'=>'E','Ę'=>'E','Ě'=>'E','Ḕ' => 'E','Ḗ' => 'E','Ḙ' => 'E','Ḛ' => 'E','Ḝ' => 'E','Ẽ‬'=>'E',
            'ā'=>'a','ă'=>'a','ą'=>'a','ḁ' => 'a', 
            'ḃ' => 'b','ḅ' => 'b','ḇ' => 'b',
            'ć'=>'c','ĉ'=>'c','ċ'=>'c','č'=>'c','ḉ' => 'c',
            'đ'=>'d','ḋ' => 'd','ḍ' => 'd','ḏ' => 'd','ḑ' => 'd','ḓ' => 'd',
            'ē'=>'e','ĕ'=>'e','ė'=>'e','ę'=>'e','ě'=>'e','ḕ'=>'e','ḗ' => 'e','ḙ' => 'e','ḛ' => 'e','ḝ' => 'e',
            'ñ'=>'n',
            'ņ'=>'n', 'ṅ' => 'n','ṇ' => 'n','ṉ'=> 'n','ṋ' => 'n',
            'Š'=>'S', 'š'=>'s', 'ś' => 's',
            'Ž'=>'Z', 'ž'=>'z',
            'ƒ'=>'f','ḟ' => 'f',
            'Ḟ' => 'F',
            'Ĝ'=>'G', 'ğ'=>'g', 'Ġ'=>'G', 'ġ'=>'g', 'Ģ'=>'G', 'ģ'=>'g','Ḡ' => 'G', 'ḡ' =>'g',
            'Ĥ'=>'H', 'ĥ'=>'h', 'Ħ'=>'H', 'ħ'=>'h','Ḣ' => 'H','ḣ' => 'h','Ḥ' => 'h','ḥ' => 'h','Ḧ' => 'H','ḧ' => 'h','Ḩ' => 'H','ḩ' => 'h','Ḫ' => 'H','ḫ' => 'h',
            'Ĩ'=>'I', 'ĩ'=>'i', 'Ī'=>'I', 'ī'=>'i', 'Ĭ'=>'I', 'ĭ'=>'i', 'Į'=>'I', 'į'=>'i', 'İ'=>'I', 'ı'=>'i','Ḭ' => 'I','ḭ' => 'i','Ḯ' => 'I','ḯ' => 'i',
            'Ĳ'=>'IJ', 'ĳ'=>'ij',
            'Ĵ'=>'j', 'ĵ'=>'j',
            'Ķ'=>'K', 'ķ'=>'k', 'ĸ'=>'k','Ḱ' => 'K','ḱ' => 'k','Ḳ' => 'K','ḳ' => 'k','Ḵ' => 'K','ḵ' => 'k',
            'Ĺ'=>'L', 'ĺ'=>'l', 'Ļ'=>'L', 'ļ'=>'l', 'Ľ'=>'L', 'ľ'=>'l', 'Ŀ'=>'L', 'ŀ'=>'l', 'Ł'=>'L', 'ł'=>'l','Ḷ' => 'L','ḷ' => 'l','Ḹ'=>'L','ḹ' => 'l','Ḻ' => 'L','ḻ' => 'l','Ḽ' => 'L','ḽ' => 'l',
            'Ḿ' => 'M','ḿ' => 'm','Ṁ' => 'M','ṁ' => 'm','Ṃ' => 'M','ṃ' => 'm',
            'Ń'=>'N', 'ń'=>'n', 'Ņ'=>'N', 'ņ'=>'n', 'Ň'=>'N', 'ň'=>'n', 'ŉ'=>'n', 'Ŋ'=>'N', 'ŋ'=>'n','Ṅ'=> 'N','Ṇ' => 'N','Ṉ' => 'N','Ṋ' => 'N',
            'Ō'=>'O', 'ō'=>'o', 'Ŏ'=>'O', 'ŏ'=>'o', 'Ő'=>'O', 'ő'=>'o', 'Œ'=>'OE', 'œ'=>'oe','Ṍ'=> 'O','ṍ'=>'o','Ṏ' => 'O','ṏ' => 'ṏ','Ṑ' => 'O','ṑ'=>'O','Ṓ' => 'O','ṓ' => 'o',
            'Ṕ' => 'P','ṕ' => 'p','Ṗ' => 'P','ṗ' => 'p',
            'Ŕ'=>'R', 'ŕ'=>'r', 'Ŗ'=>'R', 'ŗ'=>'r', 'Ř'=>'R', 'ř'=>'r','Ṙ' => 'R','ṙ' => 'r','Ṛ' => 'R','ṛ' => 'r','Ṝ' => 'R','ṝ' => 'r','Ṟ'=> 'R','ṟ' => 'r',
            'Ś'=>'S', 'ś'=>'s', 'Ŝ'=>'S', 'ŝ'=>'s', 'Ş'=>'S', 'ş'=>'s', 'Š'=>'S', 'š'=>'s','Ṡ' => 'S','ṡ'=>'s','Ṣ' => 'S',
'ṣ'=>'s', 'Ṥ' => 'S','ṥ'=>'s','Ṧ'=>'S','ṧ'=>'s','Ṩ' => 'S','ṩ' => 's',
            'Ţ'=>'T', 'ţ'=>'t', 'Ť'=>'T', 'ť'=>'t', 'Ŧ'=>'T', 'ŧ'=>'t','Ṫ' => 'T','ṫ'=>'t','Ṭ' => 'T','ṭ'=>'t','Ṯ'=>'T','ṯ' => 't','Ṱ'=>'T','ṱ' =>'t',
            'Ũ'=>'U', 'ũ'=>'u', 'Ū'=>'U', 'ū'=>'u', 'Ŭ'=>'U', 'ŭ'=>'u', 'Ů'=>'U', 'ů'=>'u', 'Ű'=>'U', 'ű'=>'u','Ṳ' => 'U','ṳ'=> 'u','Ṵ'=>'U','ṵ'=>'u','Ṷ' => 'U','ṷ' => 'u','Ṹ' => 'U','ṹ'=>'u','Ṻ' => 'U','ṻ' => 'u',
            'Ų'=>'U', 'ų'=>'u',
            'Ṽ' => 'v','ṽ'=>'v','Ṿ' => 'v','ṿ' => 'v',
            'Ŵ'=>'W', 'ŵ'=>'w',
            'Ŷ'=>'Y', 'ŷ'=>'y',
            'Ź'=>'Z', 'ź'=>'z', 'Ż'=>'Z', 'ż'=>'z', 'Ž'=>'Z', 'ž'=>'z', 'ſ'=>'f',
            '"' => "'",
            //control chars in windows1252 that perl Forks up
            '€' => 'E', 
            '‚'=>',',
            'ƒ'=>'f', 
            '„'=>',,', 
            '…' => '...', 
            '†'=>'t',
            '‡' => '+', 
            'ˆ' => '^', 
            '‰'=>'%', 
            '‹' => '(', 
            'Œ' => 'CE',
            '‘' => "'", 
            '’' => "'", 
            '“' => "\"", 
            '”'=>"\"", 
            '•'=> '.', 
            '–'=> '-',
            '—'=>'-', 
            '˜'=>'~', 
            '™'=>'TM', 
            '›'=>')', 
            'œ'=>'OE',
            'Ÿ'=>'Y'
        );
        return strtr($str, $normalize);
    }

    /** 
     * Because you don't know what a nightmare char encodings are untill you 
     * make software that is used accross borders 
     * Unicode-proof htmlentities.
     * Returns 'normal' chars as chars and weirdos as numeric html entites.
     */  
    public function escape_text_data( $str ){  
        
        //It's the wild wild web... and people import stuff from everywhere 
        //We've seen these things pop up... 
        $str = preg_replace("/\r|\n|\t|\'|\"/", " ",$str);

        // get rid of existing entities else double-escape
        $str = html_entity_decode(stripslashes($str),ENT_QUOTES,'UTF-8');
        $str = $this->escape_non_latin1($str); 
        $ar = preg_split('/(?<!^)(?!$)/u', $str );  // return array of every multi-byte character
        $str2 = '';  
        foreach ($ar as $c){
            $o = ord($c); 
            $charInBytes = strlen($c); 

            # trash any remaining larger than  3 bytes or 1 byte and bellow 31 
            if ( $charInBytes < 3  &&  ($o > 31 || strlen($c) > 1) ) {
                $str2 .= $c; 
            }  
            else {
                ///error_log("invalid char o: $o - c:[$c]");
                $str2 .= ' '; 
            }               
        } 
        return trim($str2);
    }

    /**
     * TODO: remove id and add shoptitemid when moving to v3
     * @return mixed - associative array with the properties we will export to the api
     */
    public function get_api_props()
    {

        $this->normalizeData(); 

        $data = array(  
           'ShopItemId'  => $this->ShopItemId, 
           'ClientReferenceCode' => ''.$this->ClientReferenceCode, 
          
           "Address" => array(
                "CompanyName" => $this->escape_text_data($this->CompanyName),
                'Name' => $this->escape_text_data($this->Name),
                'Streetname1' => $this->escape_text_data($this->Streetname1),
                'Streetname2' => $this->escape_text_data($this->Streetname2),
                'HouseNumber' => $this->HouseNumber,
                'NumberExtension' => $this->NumberExtension,
                'PostalCode' => $this->PostalCode,
                'City' =>  $this->escape_text_data($this->City),
                'State' => $this->escape_text_data($this->State), 
                'Country' => $this->escape_text_data($this->Country),
                'Phone' => $this->Phone,
                'Email' => trim($this->Email), 
                'CPF' => $this->CPF,
                'CNPJ' => $this->CNPJ,
                'Neighborhood' => $this->Neighborhood
           ),
           "OptionList" => $this->OptionList
        );

        if($this->Description && strlen( trim($this->Description) ) > 3 ){
           $data["Customs"] = array( 
                'CustomsType' => $this->CustomsType,
                'Description' => $this->escape_text_data($this->Description),
                'HSCode' => $this->HSCode,
                'Type' => 4,
                'Value' => $this->Value ? number_format($this->Value,2,'.','') : '' , // API assumes a max of 2 decimal places 
           );
        }

        if ($this->ShippingMethodId) {
            $data['ShippingMethodId'] = $this->ShippingMethodId; 
        }

        if ($this->ShippingMethodName) {
            $data['ShippingMethodName'] = $this->ShippingMethodName;
        }

        if($this->Transporter && is_numeric($this->Transporter)){
            $data['Carrier'] = array(
                "Id"=> $this->Transporter,
           );
        }

        if($this->Weight != ''){
            $data['Weight'] = $this->Weight; //in grams 
        }

        if($this->Length || $this->Width || $this->Height){
           $data['Dimensions'] = array(
                'Width' => $this->Width,
                'Length' => $this->Length,
                'Height' => $this->Height,
           );
        }

        if($this->PointId)
        {
           $data["PickupPoint"] = array(
            "PointId" => $this->PointId,
           );

           if( $this->ExtendedInfo )
           {
            $data["PickupPoint"]["ExtendedInfo"] = $this->ExtendedInfo;
           }
        } 

        if( $this->extraOptionId )
        {
            array_push( $data['OptionList'], array(
                "Id" => $this->extraOptionId
            ));
        }

        if(!empty($this->ShipmentItems))
        {
            $data['ShipmentItems'] = $this->ShipmentItems;
        }

        //die(var_export($data));
        return (object) $data; 
    }

    /**
     * update order meta , don't forget to escape the strings
     *
     * @param {type} $order_id - the type is defined by the platform, usually int but it can be a string
     * @param int $status
     * @param int $carrier_id
     * @param int $pickup_id
     * @param string $pickup_label
     * @param string $pickup_extended
     * @param string $tracking_id
     * @param string $message
     */
    public function update_order_meta($order_id, $status, $carrier_id, $pickup_id, $pickup_label, $pickup_extended, $tracking_id ='', $message='')
    {
        $sql = sprintf(
        "update `{$this->db_prefix}shiptimize` 
            set `status` =  %d, 
            carrier_id=%d,
            pickup_id =%d,
            pickup_label = \"%s\",
            pickup_extended = \"%s\",
            tracking_id=\"%s\",
            message=\"%s\"
            where id=\"%s\"", 
          $status,
          $carrier_id,
          $pickup_id,
          $pickup_label,
          $pickup_extended,
          $tracking_id,
          $message,
          $order_id
        );

        return $this->executeSQL($sql);
    }
}
