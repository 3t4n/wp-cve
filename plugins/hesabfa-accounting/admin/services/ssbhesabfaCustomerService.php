<?php

class ssbhesabfaCustomerService
{
    public static $countries;
    public static $states;

    public static function mapCustomer($code, $id_customer, $type = 'first',$id_order = ''): array
    {
        self::getCountriesAndStates();

        $customer = new WC_Customer($id_customer);
        $order = new WC_Order($id_order);
        $firstName = $customer->get_first_name() ? $customer->get_first_name() : $customer->get_billing_first_name();
        $lastName = $customer->get_last_name() ? $customer->get_last_name() : $customer->get_billing_last_name();
        $name = $firstName . ' ' . $lastName;
        $nodeFamily = get_option('ssbhesabfa_contact_automatic_save_node_family') == 'yes'? 'اشخاص :' . get_option('ssbhesabfa_contact_node_family') : null;

        //checkout fields
        $checkout_fields = ssbhesabfaCustomerService::getAdditionalCheckoutFileds($id_order);
        $NationalCode = $checkout_fields['NationalCode'];
        $EconomicCode = $checkout_fields['EconomicCode'];
        $RegistrationNumber = $checkout_fields['RegistrationNumber'];
        $Website = $checkout_fields['Website'];

        if($NationalCode === false) $NationalCode = '';
        if($EconomicCode === false) $EconomicCode = '';
        if($RegistrationNumber === false) $RegistrationNumber = '';
        if($Website === false) $Website = '';

        if (empty($name) || $name === ' ') $name = __('Not Defined', 'ssbhesabfa');

        $hesabfaCustomer = array();

        switch ($type) {
            case 'first':
                //
            case 'billing':
                $country_name = self::$countries[$order->get_billing_country()];
                $state_name = self::$states[$order->get_billing_country()][$order->get_billing_state()];
                $fullAddress = $order->get_billing_address_1() . '-' . $order->get_billing_address_2();
                $postalCode = $order->get_billing_postcode();
                if(strlen($fullAddress) < 5) {
                    $fullAddress = $customer->get_billing_address_1() . '-' . $customer->get_billing_address_2();
                }
                if(empty($country_name))
                    $country_name = self::$countries[$customer->get_billing_country()];
                if(empty($state_name))
                    $state_name = self::$states[$customer->get_billing_country()][$customer->get_billing_state()];
                if(empty($postalCode))
                    $postalCode = $customer->get_billing_postcode();

                $city = $order->get_billing_city();
                if(preg_match('/^[0-9]+$/', $city)) {
                    $func = new Ssbhesabfa_Admin_Functions();
                    $city = $func->convertCityCodeToName($order->get_billing_city());
                }

                $hesabfaCustomer = array(
                    'Code' => $code,
                    'Name' => $name,
                    'FirstName' => Ssbhesabfa_Validation::contactFirstNameValidation($firstName),
                    'LastName' => Ssbhesabfa_Validation::contactLastNameValidation($lastName),
                    'ContactType' => 1,
                    'NodeFamily' => $nodeFamily,
                    'NationalCode' => $NationalCode,
                    'EconomicCode' => $EconomicCode,
                    'RegistrationNumber' => $RegistrationNumber,
                    'Website' => $Website,
                    'Address' => Ssbhesabfa_Validation::contactAddressValidation($fullAddress),
                    'City' => Ssbhesabfa_Validation::contactCityValidation($city),
                    'State' => Ssbhesabfa_Validation::contactStateValidation($state_name),
                    'Country' => Ssbhesabfa_Validation::contactCountryValidation($country_name),
                    'PostalCode' => Ssbhesabfa_Validation::contactPostalCodeValidation($postalCode),
                    'Phone' => Ssbhesabfa_Validation::contactPhoneValidation($customer->get_billing_phone()),
                    'Email' => Ssbhesabfa_Validation::contactEmailValidation($customer->get_email()),
                    'Tag' => json_encode(array('id_customer' => $id_customer)),
                    'Note' => __('Customer ID in OnlineStore: ', 'ssbhesabfa') . $id_customer,
                );
                break;
            case 'shipping':
                $country_name = self::$countries[$order->get_shipping_country()];
                $state_name = self::$states[$order->get_shipping_country()][$order->get_shipping_state()];
                $fullAddress = $order->get_shipping_address_1() . ' - ' . $order->get_shipping_address_2();
                $postalCode = $order->get_shipping_postcode();

                if(strlen($fullAddress) < 5)
                    $fullAddress = $customer->get_billing_address_1() . '-' . $customer->get_billing_address_2();
                if(empty($country_name))
                    $country_name = self::$countries[$customer->get_billing_country()];
                if(empty($state_name))
                    $state_name = self::$states[$customer->get_billing_country()][$customer->get_billing_state()];
                if(empty($postalCode))
                    $postalCode = $customer->get_shipping_postcode();

                $city = $order->get_shipping_city();
                if(preg_match('/^[0-9]+$/', $city)) {
                    $func = new Ssbhesabfa_Admin_Functions();
                    $city = $func->convertCityCodeToName($order->get_shipping_city());
                }

                $hesabfaCustomer = array(
                    'Code' => $code,
                    'Name' => $name,
                    'FirstName' => Ssbhesabfa_Validation::contactFirstNameValidation($firstName),
                    'LastName' => Ssbhesabfa_Validation::contactLastNameValidation($lastName),
                    'ContactType' => 1,
                    'NodeFamily' => $nodeFamily,
                    'NationalCode' => $NationalCode,
                    'EconomicCode' => $EconomicCode,
                    'RegistrationNumber' => $RegistrationNumber,
                    'Website' => $Website,
                    'Address' => Ssbhesabfa_Validation::contactAddressValidation($fullAddress),
                    'City' => Ssbhesabfa_Validation::contactCityValidation($city),
                    'State' => Ssbhesabfa_Validation::contactStateValidation($state_name),
                    'Country' => Ssbhesabfa_Validation::contactCountryValidation($country_name),
                    'PostalCode' => Ssbhesabfa_Validation::contactPostalCodeValidation($postalCode),
                    'Phone' => Ssbhesabfa_Validation::contactPhoneValidation($customer->get_billing_phone()),
                    'Email' => Ssbhesabfa_Validation::contactEmailValidation($customer->get_email()),
                    'Tag' => json_encode(array('id_customer' => $id_customer)),
                    'Note' => __('Customer ID in OnlineStore: ', 'ssbhesabfa') . $id_customer,
                );
                break;
        }

        return self::correctCustomerData($hesabfaCustomer);
    }
//===========================================================================================================
    public static function mapGuestCustomer($code, $id_order): array
    {
        $order = new WC_Order($id_order);

        $name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
        if (empty($order->get_billing_first_name()) && empty($order->get_billing_last_name())) {
            $name = __('Guest Customer', 'ssbhesabfa');
        }
        $nodeFamily = get_option('ssbhesabfa_contact_automatic_save_node_family') == 'yes'? 'اشخاص :' . get_option('ssbhesabfa_contact_node_family') :null;

        //checkout fields
        $checkout_fields = ssbhesabfaCustomerService::getAdditionalCheckoutFileds($id_order);
        $NationalCode = $checkout_fields['NationalCode'];
        $EconomicCode = $checkout_fields['EconomicCode'];
        $RegistrationNumber = $checkout_fields['RegistrationNumber'];
        $Website = $checkout_fields['Website'];

//        $country_name = self::$countries[$order->get_billing_country()];
//        $state_name = self::$states[$order->get_billing_state()];


        WC()->countries->countries[ $order->shipping_country ];
        $country_name = WC()->countries->countries[ $order->get_billing_country() ];
        $states = WC()->countries->get_states( $order->get_billing_country() );
        $state_name = $states[ $order->get_billing_state() ];
        if(!$state_name) $state_name = WC()->countries->states[$order->billing_country][$order->billing_state];
        if(!$state_name) $state_name = $order->get_billing_state();

        $city = $order->get_billing_city();
        if(preg_match('/^[0-9]+$/', $city)) {
            $func = new Ssbhesabfa_Admin_Functions();
            $city = $func->convertCityCodeToName($order->get_billing_city());
        }

        $fullAddress = $order->get_billing_address_1() . '-' . $order->get_billing_address_2();

        $hesabfaCustomer = array(
            'Code' => $code,
            'Name' => $name,
            'FirstName' => Ssbhesabfa_Validation::contactFirstNameValidation($order->get_billing_first_name()),
            'LastName' => Ssbhesabfa_Validation::contactLastNameValidation($order->get_billing_last_name()),
            'ContactType' => 1,
            'NationalCode' => $NationalCode,
            'EconomicCode' => $EconomicCode,
            'RegistrationNumber' => $RegistrationNumber,
            'Website' => $Website,
            'NodeFamily' => $nodeFamily,
            'Address' => Ssbhesabfa_Validation::contactAddressValidation($fullAddress),
            'City' => Ssbhesabfa_Validation::contactCityValidation($city),
            'State' => Ssbhesabfa_Validation::contactStateValidation($state_name),
            'Country' => Ssbhesabfa_Validation::contactCountryValidation($country_name),
            'PostalCode' => Ssbhesabfa_Validation::contactPostalCodeValidation($order->get_billing_postcode()),
            'Phone' => Ssbhesabfa_Validation::contactPhoneValidation($order->get_billing_phone()),
            'Email' => Ssbhesabfa_Validation::contactEmailValidation($order->get_billing_email()),
            'Tag' => json_encode(array('id_customer' => 0)),
            'Note' => __('Customer registered as a GuestCustomer.', 'ssbhesabfa'),
        );

        return self::correctCustomerData($hesabfaCustomer);
    }
//===========================================================================================================
    private static function getMobileFromPhone($phone) {
        if(preg_match("/^09\d{9}$/", $phone))
            return $phone;
        else if(preg_match("/^9\d{9}$/", $phone))
            return '0' . $phone;
        else if(preg_match("/^989\d{9}$/", $phone))
            return str_replace('98', '0' ,$phone);
        else return '';
    }
//===========================================================================================================
    private static function correctCustomerData($hesabfaCustomer) {
        if($hesabfaCustomer["Phone"] == '')
            unset($hesabfaCustomer["Phone"]);
        else {
            $mobile = self::getMobileFromPhone($hesabfaCustomer["Phone"]);

            if($mobile)     $hesabfaCustomer["Mobile"] = $mobile;
        }

        if($hesabfaCustomer["Email"] == '')         unset($hesabfaCustomer["Email"]);
        if($hesabfaCustomer["Address"] == '')       unset($hesabfaCustomer["Address"]);
        if($hesabfaCustomer["PostalCode"] == '')    unset($hesabfaCustomer["PostalCode"]);
        if($hesabfaCustomer["City"] == '')          unset($hesabfaCustomer["City"]);
        if($hesabfaCustomer["State"] == '')         unset($hesabfaCustomer["State"]);
        if($hesabfaCustomer["Country"] == '')       unset($hesabfaCustomer["Country"]);

        return $hesabfaCustomer;
    }
//===========================================================================================================
    private static function getCountriesAndStates()
    {
        if (!isset(self::$countries)) {
            $countries_obj = new WC_Countries();
            self::$countries = $countries_obj->get_countries();
            self::$states = $countries_obj->get_states();
        }
    }
//===========================================================================================================
    private static function getAdditionalCheckoutFileds($id_order) {
        $NationalCode = '_billing_hesabfa_nationalcode';
        $EconomicCode = '_billing_hesabfa_economiccode';
        $RegistrationNumber = '_billing_hesabfa_registerationnumber';
        $Website = '_billing_hesabfa_website';
        $NationalCode_isActive = get_option('ssbhesabfa_contact_NationalCode_checkbox_hesabfa');
        $EconomicCode_isActive = get_option('ssbhesabfa_contact_EconomicCode_checkbox_hesabfa');
        $RegistrationNumber_isActive = get_option('ssbhesabfa_contact_RegistrationNumber_checkbox_hesabfa');
        $Website_isActive = get_option('ssbhesabfa_contact_Website_checkbox_hesabfa');
        $add_additional_fileds = get_option('ssbhesabfa_contact_add_additional_checkout_fields_hesabfa');
        $fields = array();

        // add additional fields to checkout
        if($add_additional_fileds == '1') {
            $fields['NationalCode'] = get_post_meta( $id_order, $NationalCode, true) ?? null;
            $fields['EconomicCode'] = get_post_meta( $id_order, $EconomicCode, true) ?? null;
            $fields['RegistrationNumber'] = get_post_meta( $id_order, $RegistrationNumber, true) ?? null;
            $fields['Website'] = get_post_meta( $id_order, $Website, true) ?? null;
        } elseif($add_additional_fileds == '2') {
            $NationalCode = get_option('ssbhesabfa_contact_NationalCode_text_hesabfa');
            $EconomicCode = get_option('ssbhesabfa_contact_EconomicCode_text_hesabfa');
            $RegistrationNumber = get_option('ssbhesabfa_contact_RegistrationNumber_text_hesabfa');
            $Website = get_option('ssbhesabfa_contact_Website_text_hesabfa');

            if($NationalCode_isActive == 'yes' && $NationalCode)
                $fields['NationalCode'] = get_post_meta( $id_order, $NationalCode, true) ?? null;

            if($EconomicCode_isActive == 'yes' && $EconomicCode)
                $fields['EconomicCode'] = get_post_meta( $id_order, $EconomicCode, true) ?? null;

            if($RegistrationNumber_isActive == 'yes' && $RegistrationNumber)
                $fields['RegistrationNumber'] = get_post_meta( $id_order, $RegistrationNumber, true) ?? null;

            if($Website_isActive == 'yes' && $Website)
                $fields['Website'] = get_post_meta( $id_order, $Website, true) ?? null;
        }
        return $fields;
    }
}
