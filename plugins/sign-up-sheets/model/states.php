<?php
/**
 * States Model
 */

namespace FDSUS\Model;

class States
{
    public function __construct()
    {
    }

    /**
     * Get list of states and abbreviations
     *
     * @return   array   states
     */
    public function get()
    {
        $states = array(
            'AL' => "Alabama",
            'AK' => "Alaska",
            'AS' => "American Samoa",
            'AZ' => "Arizona",
            'AR' => "Arkansas",
            'CA' => "California",
            'CO' => "Colorado",
            'CT' => "Connecticut",
            'DE' => "Delaware",
            'DC' => "District Of Columbia",
            'FL' => "Florida",
            'FM' => "Federated States of Micronesia",
            'GA' => "Georgia",
            'GU' => "Guam",
            'HI' => "Hawaii",
            'ID' => "Idaho",
            'IL' => "Illinois",
            'IN' => "Indiana",
            'IA' => "Iowa",
            'KS' => "Kansas",
            'KY' => "Kentucky",
            'LA' => "Louisiana",
            'ME' => "Maine",
            'MD' => "Maryland",
            'MA' => "Massachusetts",
            'MH' => "Marshall Islands",
            'MI' => "Michigan",
            'MN' => "Minnesota",
            'MP' => "Northern Mariana Islands",
            'MS' => "Mississippi",
            'MO' => "Missouri",
            'MT' => "Montana",
            'NE' => "Nebraska",
            'NV' => "Nevada",
            'NH' => "New Hampshire",
            'NJ' => "New Jersey",
            'NM' => "New Mexico",
            'NY' => "New York",
            'NC' => "North Carolina",
            'ND' => "North Dakota",
            'OH' => "Ohio",
            'OK' => "Oklahoma",
            'OR' => "Oregon",
            'PA' => "Pennsylvania",
            'PR' => "Puerto Rico",
            'PW' => "Palau",
            'RI' => "Rhode Island",
            'SC' => "South Carolina",
            'SD' => "South Dakota",
            'TN' => "Tennessee",
            'TX' => "Texas",
            'UT' => "Utah",
            'VT' => "Vermont",
            'VA' => "Virginia",
            'VI' => "Virgin Islands",
            'WA' => "Washington",
            'WV' => "West Virginia",
            'WI' => "Wisconsin",
            'WY' => "Wyoming"
        );

        /**
         * Filter for states dropdown array
         *
         * @param string $sql
         *
         * @return string
         *
         * @api
         * @since 2.2
         */
        return apply_filters('fdsus_states', $states);
    }
}
