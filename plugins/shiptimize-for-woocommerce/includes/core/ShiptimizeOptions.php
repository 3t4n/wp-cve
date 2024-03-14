<?php 
/** 
 * A class that knows what to do with options 
 * The api sends a bunch of ids but no info about what they mean
 * So something on the plugin must know what to do with them 
 * 
 * The exception is service level, which we always show everywhere and it's just an option id
 */ 
class ShiptimizeOptions  { 

    /** 
     * @var a list of option ids who's value is the order value regardless of type 
     */ 
    private $valueisordervalue = array(
        '2', # cash service on all carriers that support it 
        '47', # CTT send insured 
        '13', # UPS send insured 
        '31', # PostNL Pakkatten send insured 
        '57', # DHL Parcel (2C) send insured 
        '70', # 
    );

    /** 
     * @var ShiptimizeOptions - a reference to the singleton instance 
     */ 
    static $me = null; 

    /*** 
     * Make this a singleton to optimize 
     */ 
    private function __construct(){

    }

    public static function getInstance(){
        if(!self::$me){
            self::$me = new ShiptimizeOptions();
        }

        return self::$me; 
    }

    /** 
     * @return array of ids that corresponde with the extra options select box 
     */ 
    public function getAllowedExtraOptions(){
        return  array(
          '2',   # cash service on all carriers that support it 
          '47',  # CTT send insured 
          '46',  # DHL (2c) Brievenbus pakje
          '49',  # DHL (2c) Do not deliver at neighbour 
          '42',  # DHL (2c) Evening delivery
          '62',  # DPD (2c) / GLS  saturday delivery
          '55',  # Delivery window
          '73',  # Delivery attempts
          '80',  # CTT return label
        );
    }

    /** 
     * checkboxes we want to include.
     * 
     * @return an array or (Id => stroptiontype ) for all options that are checkboxes 
     */
    public function getCheckboxFieldIds(){
        return array(
          '13' => 'sendinsured', # UPS
          '31' => 'sendinsured', # PostNL Pakkatten 
          '32' => 'activatepickup', # Activate pickup at seller
          '56' => 'activatepickup', # CTT Activate pickup at seller
          '57' => 'sendinsured', # DHL Parcel (2C) 
          '59' => 'activatepickup', # Activate pickup at seller
          '70' => 'fragile', # CTT Fragile
        );
    }

    /** 
     * Return only the strids of the checkbox fields
     */
    public function getCheckoutStrIds(){
        $checkboxes = $this->getCheckboxFieldIds();
        $outstr = array(); 
        foreach ($checkboxes as $boxid => $boxhandle ) {
            if (!in_array($boxhandle, $outstr)) {
                array_push($outstr, $boxhandle);
            } 
        }
        return $outstr;
    }

    /** 
     * @param ShiptimizeOrder - an order with all relevant properties filled in 
     * @param int optionId - the id of the options 
     * @param int optionChildId - if it exists the child option id 
     * 
     * @return an array containing the proper properties to append to the OptionList
     */ 
    public function getOptionValue($shiptimizeOrder, $optionId, $optionChildId = 1){
        if(!$optionId || $optionId == '-'){ 
            error_log("Invalid optionId [$optionId] sent");
            return; 
        }

        if (in_array($optionId, $this->valueisordervalue)) {
            return array(
                'Id' => $optionId,
                'OptionFields'  => array(
                  array(
                    'Id' => 1,
                    'Value' => $shiptimizeOrder->getOrderValue()
                  )
                ),
                'Value'  => $shiptimizeOrder->getOrderValue() 
            );  
        }
        else if(in_array($optionId, array_keys($this->getCheckboxFieldIds()))) {
            $option = array(
                'Id' => $optionId,
                'Value' => 1
            );
        }
        else { //default all others to value => 1 which basically just means set 
            $option =  array(
                'Id' => $optionId
            );

            if ($optionChildId > 1  ) {
                $option['OptionFields']  = array(
                      array(
                        'Id' => 1, #this is actually the child option id
                        'Value' => $optionChildId,
                      )
                    ); 
            }
            else {
                $option['Value'] = 1;
                $option['OptionFields']  = array(
                  array(
                    'Id' => 1, #this is actually the child option id
                    'Value' => $optionId,
                  )
                ); 
            }
        }


        return $option;
    }
}