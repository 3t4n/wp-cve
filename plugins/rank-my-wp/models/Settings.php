<?php

class RKMW_Models_Settings {

    /**
     * Save the Values in database
     * @param $params
     */
    public function saveValues($params) {

        //Save the option values
        foreach ($params as $key => $value) {
            if (in_array($key, array_keys(RKMW_Classes_Helpers_Tools::$options))) {

                if (is_array(RKMW_Classes_Helpers_Tools::$options[$key])) {

                    //Sanitize each value from subarray
                    $array = RKMW_Classes_Helpers_Tools::getValue($key);

                    //Save the array values
                    if (is_array($array)) {
                        if (!empty($array)) {
                            foreach ($array as $subkey => $subvalue) {
                                RKMW_Classes_Helpers_Tools::$options[$key][$subkey] = $subvalue;
                            }
                        }
                    }

                    //print_R(RKMW_Classes_Helpers_Tools::$options[$key]);
                    //sanitize the value and save it
                    RKMW_Classes_Helpers_Tools::saveOptions();
                } else {

                    //sanitize the value and save it
                    RKMW_Classes_Helpers_Tools::saveOptions($key, RKMW_Classes_Helpers_Tools::getValue($key));
                }
            }
        }
    }

}