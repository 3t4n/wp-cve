<?php

class MM_WPFS_States {

    public static function getStateByCode( $stateCode) {
        if (isset($stateCode)) {
            $availableStates = self::getAvailableStates();
            if (isset($availableStates) && array_key_exists($stateCode, $availableStates)) {
                return $availableStates[$stateCode];
            }
        }

        return null;
    }

    public static function getStateCodeByName( $stateName ) {
        $result = null;

        foreach ( self::getAvailableStates() as $code => $state ) {
            if ( $state['name'] == $stateName ) {
                $result = $code;
                break;
            }
        }

        return $result;
    }

    /**
     * @param $stateCode
     *
     * @return null|string
     */
    public static function getStateNameFor( $stateCode ) {
        if (isset($stateCode)) {
            $availableStates = self::getAvailableStates();
            if (isset($availableStates) && array_key_exists($stateCode, $availableStates)) {
                $countryName = $availableStates[$stateCode]['name'];
            } else {
                $countryName = strtoupper($stateCode);
            }

            return $countryName;
        }

        return null;
    }

    /**
     * @return array ISO 3166-2 list of subdivision codes, only in the United States
     * The codes are without country prefix. For example, “NY” for New York, United States.
     */
    public static function getAvailableStates() {
        $states = array(
            'AL' => array(
                'code'      => 'AL',
                'name'      => 'Alabama',
                'category'  => 'state'
            ),
            'AK' => array(
                'code'      => 'AK',
                'name'      => 'Alaska',
                'category'  => 'state'
            ),
            'AZ' => array(
                'code'      => 'AZ',
                'name'      => 'Arizona',
                'category'  => 'state'
            ),
            'AR' => array(
                'code'      => 'AR',
                'name'      => 'Arkansas',
                'category'  => 'state'
            ),
            'CA' => array(
                'code'      => 'CA',
                'name'      => 'California',
                'category'  => 'state'
            ),
            'CO' => array(
                'code'      => 'CO',
                'name'      => 'Colorado',
                'category'  => 'state'
            ),
            'CT' => array(
                'code'      => 'CT',
                'name'      => 'Connecticut',
                'category'  => 'state'
            ),
            'DC' => array(
                'code'      => 'DC',
                'name'      => 'Washinton DC',
                'category'  => 'district'
            ),
            'DE' => array(
                'code'      => 'DE',
                'name'      => 'Delaware',
                'category'  => 'state'
            ),
            'FL' => array(
                'code'      => 'FL',
                'name'      => 'Florida',
                'category'  => 'state'
            ),
            'GA' => array(
                'code'      => 'GA',
                'name'      => 'Georgia',
                'category'  => 'state'
            ),
            'HI' => array(
                'code'      => 'HI',
                'name'      => 'Hawai',
                'category'  => 'state'
            ),
            'ID' => array(
                'code'      => 'ID',
                'name'      => 'Idaho',
                'category'  => 'state'
            ),
            'IL' => array(
                'code'      => 'IL',
                'name'      => 'Illinois',
                'category'  => 'state'
            ),
            'IN' => array(
                'code'      => 'IN',
                'name'      => 'Indiana',
                'category'  => 'state'
            ),
            'IA' => array(
                'code'      => 'IA',
                'name'      => 'Iowa',
                'category'  => 'state'
            ),
            'KS' => array(
                'code'      => 'KS',
                'name'      => 'Kansas',
                'category'  => 'state'
            ),
            'KY' => array(
                'code'      => 'KY',
                'name'      => 'Kentucky',
                'category'  => 'state'
            ),
            'LA' => array(
                'code'      => 'LA',
                'name'      => 'Louisiana',
                'category'  => 'state'
            ),
            'ME' => array(
                'code'      => 'ME',
                'name'      => 'Maine',
                'category'  => 'state'
            ),
            'MD' => array(
                'code'      => 'MD',
                'name'      => 'Maryland',
                'category'  => 'state'
            ),
            'MA' => array(
                'code'      => 'MA',
                'name'      => 'Massachusetts',
                'category'  => 'state'
            ),
            'MI' => array(
                'code'      => 'MI',
                'name'      => 'Michigan',
                'category'  => 'state'
            ),
            'MN' => array(
                'code'      => 'MN',
                'name'      => 'Minnesota',
                'category'  => 'state'
            ),
            'MS' => array(
                'code'      => 'MS',
                'name'      => 'Mississippi',
                'category'  => 'state'
            ),
            'MO' => array(
                'code'      => 'MO',
                'name'      => 'Missouri',
                'category'  => 'state'
            ),
            'MT' => array(
                'code'      => 'MT',
                'name'      => 'Montana',
                'category'  => 'state'
            ),
            'NE' => array(
                'code'      => 'NE',
                'name'      => 'Nebraska',
                'category'  => 'state'
            ),
            'NV' => array(
                'code'      => 'NV',
                'name'      => 'Nevada',
                'category'  => 'state'
            ),
            'NH' => array(
                'code'      => 'NH',
                'name'      => 'New Hampshire',
                'category'  => 'state'
            ),
            'NJ' => array(
                'code'      => 'NJ',
                'name'      => 'New Jersey',
                'category'  => 'state'
            ),
            'NM' => array(
                'code'      => 'NM',
                'name'      => 'New Mexico',
                'category'  => 'state'
            ),
            'NY' => array(
                'code'      => 'NY',
                'name'      => 'New York',
                'category'  => 'state'
            ),
            'NC' => array(
                'code'      => 'NC',
                'name'      => 'North Carolina',
                'category'  => 'state'
            ),
            'ND' => array(
                'code'      => 'ND',
                'name'      => 'North Dakota',
                'category'  => 'state'
            ),
            'OH' => array(
                'code'      => 'OH',
                'name'      => 'Ohio',
                'category'  => 'state'
            ),
            'OK' => array(
                'code'      => 'OK',
                'name'      => 'Oklahoma',
                'category'  => 'state'
            ),
            'OR' => array(
                'code'      => 'OR',
                'name'      => 'Oregon',
                'category'  => 'state'
            ),
            'PA' => array(
                'code'      => 'PA',
                'name'      => 'Pennsylvania',
                'category'  => 'state'
            ),
            'RI' => array(
                'code'      => 'RI',
                'name'      => 'Rhode Island',
                'category'  => 'state'
            ),
            'SC' => array(
                'code'      => 'SC',
                'name'      => 'South Carolina',
                'category'  => 'state'
            ),
            'SD' => array(
                'code'      => 'SD',
                'name'      => 'South Dakota',
                'category'  => 'state'
            ),
            'TN' => array(
                'code'      => 'TN',
                'name'      => 'Tennessee',
                'category'  => 'state'
            ),
            'TX' => array(
                'code'      => 'TX',
                'name'      => 'Texas',
                'category'  => 'state'
            ),
            'UT' => array(
                'code'      => 'UT',
                'name'      => 'Utah',
                'category'  => 'state'
            ),
            'VT' => array(
                'code'      => 'VT',
                'name'      => 'Vermont',
                'category'  => 'state'
            ),
            'VA' => array(
                'code'      => 'VA',
                'name'      => 'Virginia',
                'category'  => 'state'
            ),
            'WA' => array(
                'code'      => 'WA',
                'name'      => 'Washington',
                'category'  => 'state'
            ),
            'WV' => array(
                'code'      => 'WV',
                'name'      => 'West Virginia',
                'category'  => 'state'
            ),
            'WI' => array(
                'code'      => 'WI',
                'name'      => 'Wisconsin',
                'category'  => 'state'
            ),
            'WY' => array(
                'code'      => 'WY',
                'name'      => 'Wyoming',
                'category'  => 'state'
            ),
        );

        return $states;
    }
}
