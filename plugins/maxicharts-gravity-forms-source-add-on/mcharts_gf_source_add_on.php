<?php
/*
 * Plugin Name: MaxiCharts Gravity Forms Source Add-on
 * Plugin URI: https://maxicharts.com/category/gravity-forms-add-on/
 * Description: Extend MaxiCharts : Add the possibility to graph Gravity Forms submitted datas
 * Version: 1.7.10
 * Author: MaxiCharts
 * Author URI: https://maxicharts.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mcharts_gf
 * Domain Path: /languages
 */
if (! defined('ABSPATH')) {
    exit();
}
define('DEFAULT_MAX_ENTRIES', 200);

if (! class_exists('maxicharts_reports')) {
    define('MAXICHARTS_PLUGIN_PATH', plugin_dir_path(__DIR__));
    $toInclude = MAXICHARTS_PLUGIN_PATH . '/maxicharts/mcharts_utils.php';
    if (file_exists($toInclude)) {
        include_once ($toInclude);
        //require_once MAXICHARTS_PLUGIN_PATH . '/maxicharts/libs/vendor/autoload.php';
    }
}

if (! function_exists('stats_standard_deviation')) {
    
    /**
     * This user-land implementation follows the implementation quite strictly;
     * it does not attempt to improve the code or algorithm in any way.
     * It will
     * raise a warning if you have fewer than 2 values in your array, just like
     * the extension does (although as an E_USER_WARNING, not E_WARNING).
     *
     * @param array $a
     * @param bool $sample
     *            [optional] Defaults to false
     * @return float|bool The standard deviation or false on error.
     */
    function stats_standard_deviation(array $a, $sample = false)
    {
        $n = count($a);
        if ($n === 0) {
            trigger_error("The array has zero elements", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("The array has only 1 element", E_USER_WARNING);
            return false;
        }
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = ((double) $val) - $mean;
            $carry += $d * $d;
        }
        ;
        if ($sample) {
            -- $n;
        }
        return sqrt($carry / $n);
    }
}

if (! class_exists('maxicharts_gravity_forms')) {
    
    class maxicharts_gravity_forms
    {
        
        // protected static $logger = null;
        protected static $instance = null;
        
        function __construct()
        {
            if (! class_exists('MAXICHARTSAPI')) {
                $msg = __('Please install MaxiCharts Core before');
                return $msg;
            }
            
            if (static::$instance !== null) {
                $ex = new Exception();
                MAXICHARTSAPI::getLogger()->fatal("Construct " . __CLASS__, $ex);
                throw $ex;
            }
            MAXICHARTSAPI::getLogger()->debug("Construct " . __CLASS__);
            static::$instance = $this;
            
            // self::getLogger()->debug("Adding Module : " . __CLASS__);
        }
        
        function add_hooks()
        {
            if ($this->checkGravityForms()) {
                if (! is_admin()) {
                    add_action('maxicharts_add_shortcodes', array(
                        $this,
                        'add_gravity_forms_shortcode'
                    ));
                }
                add_filter("maxicharts_get_data_from_source", array(
                    $this,
                    "get_data_from_gf"
                ), 10, 3);
                
                add_filter('mcharts_filter_defaults_parameters', array(
                    $this,
                    'add_default_params'
                ));
                add_filter('mcharts_return_without_graph', array(
                    $this,
                    'return_without_graph'
                ));
                // self::getLogger()->debug("MaxiCharts GF Hooks created");
            } else {
                // self::getLogger()->error("Missing plugin Gravity Forms");
            }
        }
        
        static function getLogger()
        {
            if (class_exists('MAXICHARTSAPI')) {
                return MAXICHARTSAPI::getLogger('GF');
            }
        }
        
        function return_without_graph($atts)
        {
            self::getLogger()->trace($atts);
            if (isset($atts['type'])) {
                $type = str_replace(' ', '', $atts['type']);
            } else {
                $type = '';
                self::getLogger()->warn("No type set!");
            }
            if ($type === 'array' || $type === 'total' || $type === 'list' || $type === 'sum' || $type === 'sum_entries' || $type === 'sum_report_fields') {
                return true;
            }
            return false;
        }
        
        function checkGravityForms()
        {
            $result = true;
            $gfClassHere = class_exists('GFCommon');
            // self::getLogger()->info ( "GF? ".$gfClassHere." -> ".$gfPluginHere);
            if (! function_exists('is_plugin_active')) {
                include_once (ABSPATH . 'wp-admin/includes/plugin.php');
            }
            $gfPluginHere = is_plugin_active('gravityforms/gravityforms.php');
            // self::getLogger()->info ( "GF? ".$gfClassHere." -> ".$gfPluginHere);
            
            if (! $gfClassHere && ! $gfPluginHere) {
                // check if gravity forms installed and active
                $msg = "Please install/activate gravityforms plugin";
                self::getLogger()->error($msg);
                $result = false;
            }
            
            return $result;
        }
        
        function add_gravity_forms_shortcode()
        {
            if (! is_admin()) {
                self::getLogger()->trace("Adding shortcode : gfchartsreports");
                add_shortcode('gfchartsreports', array(
                    $this,
                    'gf_charts_shortcode'
                ));
                
                self::getLogger()->trace("Adding shortcode : gfentryfieldvalue");
                add_shortcode('gfentryfieldvalue', array(
                    $this,
                    'gf_entry_field_value'
                ));
            }
        }
        
        function gf_entry_field_value($atts = [], $content = null, $tag = '')
        {
            self::getLogger()->trace("Executing shortcode : gfentryfieldvalue");
            if (! is_admin()) {
                
                $source = 'gf';
                $destination = 'text';
                return $this->displayFieldValue($source, $destination, $atts, $content, $tag);
            }
        }
        
        function gf_charts_shortcode($atts = [], $content = null, $tag = '')
        {
            self::getLogger()->trace("Executing shortcode gfchartsreports");
            if (! is_admin() || wp_doing_ajax()) {
                // maxicharts_reports::maxicharts_enqueue_scripts();
                $source = 'gf';
                $destination = 'chartjs';
                self::getLogger()->trace("Executing shortcode gfchartsreports : " . $source . ' -> ' . $destination);
                return maxicharts_reports::chartReports($source, $destination, $atts, $content, $tag);
            } else {
                self::getLogger()->trace("Admin page for shortcode gfchartsreports : " . $source . ' -> ' . $destination);
            }
        }
        
        function add_default_params($defaults)
        {
            return $defaults;
        }
        
        function displayFieldValue($source, $destination, $atts)
        {
            self::getLogger()->debug("SHORTCODE::displayFieldValue " . $source . " to " . $destination);
            maxicharts_reports::maxicharts_enqueue_scripts();
            $defaultsParameters = array(
                'form_id' => '',
                'lead_id' => '',
                'field_id' => '',
                'style' => '',
                'class' => '',
                'custom_search_criteria' => ''
            );
            
            $final_atts = shortcode_atts($defaultsParameters, $atts, 'gfentryfieldvalue');
            self::getLogger()->debug($final_atts);
            
            $form_id = trim($final_atts['form_id']);
            $lead_id = trim($final_atts['lead_id']);
            $custom_search_criteria = trim($final_atts['custom_search_criteria']);
            
            if ($form_id > 0){
                // if form id specified, retrieve the last entry for logged in user
                // public static function get_entries( $form_ids, $search_criteria = array(), $sorting = null, $paging = null, $total_count = null ) {}
                
                $final_atts['gf_entry_id'] = $lead_id;
                //$custom_search_criteria = null;
                $entries = $this->getGFEntries($form_id, $maxentries, $custom_search_criteria, $final_atts);
                if (count($entries) > 1){
                    self::getLogger()->error("More than one entry...");
                } else {
                    $entry = array_shift($entries);
                }
            } else if ($lead_id > 0){
                $entry = GFAPI::get_entry($lead_id);
            } else {
                $msg = "Cannot get any value";
                self::getLogger()->error($msg);
                return $msg;
            }
            
            $field_id = trim($final_atts['field_id']);
            $style = trim($final_atts['style']);
            $classParam = trim($final_atts['class']);
            
            
            self::getLogger()->debug ( $field_id);
            $multiple_fields_array = explode(',',$field_id);
            $values_array = [];
            if (is_array($multiple_fields_array)){
                foreach ($multiple_fields_array as $field_id_to_catch) {
                    $value_found = rgar($entry, $field_id_to_catch);
                    $values_array[] = $value_found;
                    self::getLogger()->debug ( $field_id_to_catch . " value is " .$value_found);
                }
                $field_to_display = implode(' ',$values_array);
            } else {
                $field_to_display = rgar($entry, $field_id);
            }
            self::getLogger()->debug($field_to_display);
            if ($style) {
                $result = '<span style="' . $style . '">' . $field_to_display . '</span>';
            } else if ($classParam) {
                $result = '<div class="' . $classParam . '">' . $field_to_display . '</div>';
            } else {
                $result = $field_to_display;
            }
            self::getLogger()->debug($result);
            return $result;
        }
        
        function listValuesOfFieldInForm($gfEntries, $includes)
        {
            self::getLogger()->trace("GF Create list ");
            self::getLogger()->trace($gfEntries);
            $result = '<ul>';
            
            $answersToCatch = MAXICHARTSAPI::getArrayForFieldInForm($gfEntries, $includes);
            
            $result .= implode('</li><li>', $answersToCatch);
            $result .= '</ul>';
            return $result;
        }
        
        function buildReportFieldsForGF($form_id, $type, $includeArray, $excludeArray = null, $datasets_invert = null, $args = null)
        {
            self::getLogger()->trace("GF data to dig " . $form_id);
            $reportFields = array();
            $form = GFAPI::get_form($form_id);
            
            // $graphType = $type;
            $allFields = $form['fields'];
            self::getLogger()->debug("form fields to parse : " . count($allFields));
            self::getLogger()->trace($allFields);
            /*
             * if ((isset($args['css_classes_as_series']) && ! empty($args['css_classes_as_series']))) {
             * // group all report fields on the same chart
             * //$reportFields[$fieldToGroup]['datasets'][$serieName]['data'][$field_object->label] = $newValue;
             *
             * } else
             */
             if ((isset($args['group_fields']) && $args['group_fields'] == 1) /*|| (isset($args['css_classes_as_series']) && ! empty($args['css_classes_as_series']))*/) {
                 // check all included fields types (should be the same)
                 $fieldTypes = array();
                 // get all fiel types
                 foreach ($allFields as $formFieldId => $fieldData) {
                     
                     $fieldId = $fieldData['id'];
                     if (! in_array($fieldId, $includeArray)) {
                         continue;
                     }
                     
                     $fieldTypes[] = $fieldData['type'];
                 }
                 
                 $uniq_array = array_unique($fieldTypes);
                 $onlyOneType = count($uniq_array) == 1;
                 // make sure they are unique
                 if (! $onlyOneType) {
                     $errMsg = "Included fields are not of the same type : " . implode(',', $includeArray);
                     self::getLogger()->error($errMsg);
                     self::getLogger()->error($uniq_array);
                     $reportFields['error'] = $errMsg;
                     return $reportFields;
                 } else {
                     self::getLogger()->debug("Included fields are all of same type");
                     self::getLogger()->debug($uniq_array);
                     foreach ($allFields as $formFieldId => $fieldData) {
                         
                         $fieldId = $fieldData['id'];
                         if (! in_array($fieldId, $includeArray)) {
                             continue;
                         }
                         // FIXME : if radar, take each field as a new dimension, only one dataset
                         /*
                          * data: {
                          * labels: ['Running', 'Swimming', 'Eating', 'Cycling'],
                          * datasets: [{
                          * data: [20, 10, 4, 2]
                          * }]
                          * }
                          */
                         if ($args['type'] == 'radar') {
                             self::getLogger()->debug("Create radar report grouping fields");
                         }
                         
                         $newReportField = $this->createNewReportField($fieldData, $type, $includeArray, $excludeArray, $datasets_invert, $args);
                         if ($newReportField) {
                             $reportFields[$fieldId] = $newReportField;
                             break;
                         }
                         
                         self::getLogger()->debug("Creating report field  : " . $fieldData['label'] . ' of type ' . ' -> ' . $type . ' inverted:' . $datasets_invert);
                     }
                 }
             } else {
                 self::getLogger()->debug("Parsing not grouping");
                 foreach ($allFields as $formFieldId => $fieldData) {
                     if (is_object($fieldData)) {
                         $fieldData = (array) $fieldData;
                     }
                     $fieldId = $fieldData['id'];
                     self::getLogger()->trace($formFieldId);
                     self::getLogger()->trace($fieldData);
                     $newReportField = $this->createNewReportField($fieldData, $type, $includeArray, $excludeArray, $datasets_invert, $args);
                     if ($newReportField) {
                         $reportFields[$fieldId] = $newReportField;
                         self::getLogger()->debug("Creating report field  : " . $fieldData['label'] . ' of type ' . $type . ' inverted:' . $datasets_invert);
                     } else {
                         self::getLogger()->debug("No report field created");
                     }
                 }
             }
             
             self::getLogger()->debug("#### " . count($reportFields) . " report fields created");
             
             return $reportFields;
        }
        
        function createNewReportField($fieldData, $type, $includeArray, $excludeArray = null, $datasets_invert = null, $args = null)
        {
            $newReportField = array();
            $fieldType = $fieldData['type'];
            $fieldId = $fieldData['id'];
            if (! empty($includeArray)) {
                if (! in_array($fieldId, $includeArray)) {
                    self::getLogger()->debug("field id $fieldId not in include array " . implode(' | ', $includeArray));
                    return null;
                }
            }
            if (! empty($excludeArray)) {
                if (in_array($fieldId, $excludeArray)) {
                    self::getLogger()->debug("field id $fieldId in exclude array " . implode(' | ', $excludeArray));
                    return null;
                }
            }
            // MAXICHARTSAPI::getLogger()->debug($fieldData);
            self::getLogger()->debug($type . " ### Processing field " . $fieldId . ' of type ' . $fieldType);
            // $skipField = false;
            // $fieldData = apply_filters('mcharts_filter_gf_field_before_type_process', $formFieldId, $fieldData);
            $unknownType = false;
            
            switch ($fieldType) {
                case 'product':
                case 'option':
                case 'text':
                case 'hidden':
                case 'textarea':
                case 'name':
                case 'checkbox':
                case 'radio':
                case 'survey':
                case 'select':
                case 'multiselect':
                case 'number':
                case 'slider':
                case 'workflow_user':
                case 'list':
                case 'quiz':
                    $newReportField['inputType'] = $fieldData['inputType'];
                    if (isset($fieldData['gsurveyLikertEnableMultipleRows']) && $fieldData['gsurveyLikertEnableMultipleRows'] == 1) {
                        self::getLogger()->debug("MULTI ROW SURVEY LIKERT");
                        // self::getLogger()->debug($fieldData);
                        $newReportField['choices'] = $fieldData['choices'];
                        $newReportField['inputs'] = $fieldData['inputs'];
                        $newReportField['gsurveyLikertEnableMultipleRows'] = 1;
                        $newReportField['multisets'] = 1;
                    } else if ($fieldType == 'list' && $fieldData['enableColumns']) {
                        $newReportField['choices'] = $fieldData['choices'];
                        $newReportField['inputs'] = $fieldData['inputs'];
                        // $reportFields [$fieldId] ['gsurveyLikertEnableMultipleRows'] = 1;
                        $newReportField['multisets'] = 1;
                    } else {
                        $newReportField['choices'] = isset($fieldData['choices']) ? $fieldData['choices'] : '';
                        $newReportField['multisets'] = isset($fieldData['multisets']) ? $fieldData['multisets'] : '';
                    }
                    
                    if ($fieldType == 'quiz') {
                        self::getLogger()->debug('quiz type field');
                        self::getLogger()->debug($fieldData);
                        /*
                         * [gquizWeight] => 0
                         * [gquizIsCorrect] => 1
                         */
                    }
                    break;
                    
                default:
                    self::getLogger()->warn("Unknown field type : " . $fieldType);
                    self::getLogger()->warn("Field will be skipped");
                    $unknownType = true;
                    break;
            }
            
            if ($unknownType) {
                return null;
            }
            
            $newReportField['datasets_invert'] = $datasets_invert;
            $newReportField['label'] = $fieldData['label'];
            $newReportField['gf_field_id'] = $fieldId;
            $newReportField['graphType'] = $type;
            $newReportField['type'] = $fieldType;
            
            return $newReportField;
        }
        
        function initialize_ordered_answers($fieldId, $countArray, $fieldData, $atts)
        {
            self::getLogger()->debug("initialize_ordered_answers");
            if (is_array($fieldData['choices'])) {
                $possibleValues = array();
                if (isset($atts['correctness_count']) && $atts['correctness_count']) {
                    $possibleValues['correct'] = __('Correct');
                    $possibleValues['incorrect'] = __('Incorrect');
                } else {
                    self::getLogger()->warn($fieldData['choices']);
                    foreach ($fieldData['choices'] as $choice) {
                        $possibleValues[$choice['value']] = $choice['text'];
                        // $possibleValues[] = $newPossibleValue;
                    }
                }
                
                self::getLogger()->warn($possibleValues);
                foreach ($possibleValues as $newPossibleValue => $newPossibleText) {
                    
                    $countArray[$fieldId]['orderedAnswers'][$newPossibleValue] = 0;
                    $countArray[$fieldId]['valuesAndLabels'][$newPossibleValue] = $newPossibleText;
                }
            } else {
                self::getLogger()->warn("No choices set");
                self::getLogger()->warn($fieldData);
            }
            
            if (empty($countArray[$fieldId]['valuesAndLabels'])) {
                self::getLogger()->error("No valuesAndLabels for field id" . $fieldId);
                self::getLogger()->error($fieldData);
                $countArray[$fieldId]['valuesAndLabels'] = array();
            }
            
            if (isset($atts['correctness_count']) && $atts['correctness_count'] && count($countArray[$fieldId]['orderedAnswers']) != 2) {
                self::getLogger()->error("Size of answers mismatch");
            }
            
            self::getLogger()->trace("initialize_ordered_answers:");
            self::getLogger()->trace($countArray);
            return $countArray;
        }
        
        function get_unique_keys($array)
        {
            $newKeys = array();
            foreach ($array as $newItem) {
                $newKeys[] = $newItem['Year'] . $newItem['Quarter'] . $newItem['Type of Device'];
            }
            
            return $newKeys;
        }
        
        function aggregate_datas($previousDatas, $newDatas, $list_series_names = null, $list_series_values = null, $list_sum_on_value = null)
        {
            foreach ($newDatas as $newItem) {
                $concat_key = $newItem['Year'] . $newItem['Quarter'] . $newItem['Type of Device'];
                // bool array_key_exists ( mixed $key , array $array )
                if (array_key_exists($concat_key, $previousDatas)) {
                    // $msg = $concat_key." key exists, update total";
                    self::getLogger()->trace($msg);
                    if (! is_numeric($previousDatas[$concat_key]['Number'])) {
                        self::getLogger()->error("NAN : " . $newItem['Number']);
                    }
                    $newToAdd = $newItem['Number'];
                    if (! is_numeric($newToAdd)) {
                        $newToAdd = str_replace(',', '', $newToAdd);
                        if (! is_numeric($newToAdd)) {
                            self::getLogger()->warn("NAN : " . $newItem['Number']);
                            $newToAdd = 0;
                        }
                    }
                    
                    $newValue = $previousDatas[$concat_key]['Number'] + $newToAdd;
                    $msg = $concat_key . " key exists, update total : " . $previousDatas[$concat_key]['Number'] . " + " . $newToAdd . " = " . $newValue;
                    $previousDatas[$concat_key]['Number'] = $newValue;
                } else {
                    $msg = "adding new key : " . $concat_key . " => " . implode(' | ', $newItem);
                    self::getLogger()->trace($msg);
                    $newToAdd = $newItem['Number'];
                    if (! is_numeric($newToAdd)) {
                        $newToAdd = str_replace(',', '', $newToAdd);
                        if (! is_numeric($newToAdd)) {
                            self::getLogger()->warn("NAN : " . $newItem['Number']);
                            $newItem['Number'] = 0;
                        }
                    }
                    $previousDatas[$concat_key] = $newItem;
                }
            }
            
            return $previousDatas;
        }
        
        function countAnswers($reportFields, $entries, $atts = null)
        {
            $countArray = array();
            self::getLogger()->info("countAnswers::Counting all answers and populate selected report fields");
            if (count($entries) == 0) {
                self::getLogger()->error('no entries');
                return $countArray;
            }
            if (isset($atts['gf_form_id'])) {
                $form = GFAPI::get_form($atts['gf_form_id']);
            } else {
                self::getLogger()->fatal("No gf form id");
            }
            
            self::getLogger()->info("counting answers for report fields");
            self::getLogger()->trace($reportFields);
            $ignore_empty_values = filter_var(trim($atts['ignore_empty_values']), FILTER_VALIDATE_BOOLEAN);
            self::getLogger()->debug("ignore_empty_values : $ignore_empty_values");
            
            if (isset($atts['css_classes_as_series']) && ! empty($atts['css_classes_as_series'])) {
                // get classes defined:
                self::getLogger()->info("CSS Classes : Grouping answers of several fields into one chart based on classes");
                $classesToChartAsSeriesArray = explode(",", $atts['css_classes_as_series']);
                $datasets_labels = explode(',', $atts['css_datasets_labels']);
                self::getLogger()->debug($datasets_labels);
                $cssClassesAndLabels = array_combine($classesToChartAsSeriesArray, $datasets_labels);
                self::getLogger()->debug($cssClassesAndLabels);
                self::getLogger()->debug($classesToChartAsSeriesArray);
                if (isset($atts['include']) && (! empty($atts['include']))) {
                    $includeArray = explode(",", $atts['include']);
                } else {
                    self::getLogger()->error("Need to specify included fields");
                }
                foreach ($entries as $entry) {
                    self::getLogger()->debug("---> entry " . $entry['id']);
                    // $countArray[$fieldId]['orderedAnswers'][$valueForChoice] += 1;
                    foreach ($includeArray as $fieldToGroup) {
                        self::getLogger()->debug("-----> field " . $fieldToGroup);
                        $newValue = rgar($entry, $fieldToGroup);
                        $filteredNewValue = apply_filters('mcharts_modify_value_in_answers_array', $newValue);
                        $field_object = GFAPI::get_field($form, $fieldToGroup);
                        self::getLogger()->trace($field_object);
                        $fieldClasses = $field_object->cssClass;
                        if (empty($fieldClasses)) {
                            continue;
                        } else {
                            self::getLogger()->debug("-----> field " . $fieldClasses);
                            $fieldClassesArray = explode(' ', $fieldClasses);
                            $intersec = array_intersect($classesToChartAsSeriesArray, $fieldClassesArray);
                            $intersec_count = count($intersec);
                            if ($intersec_count === 1) {
                                // matching exactly one expected class as serie, get it
                                // self::getLogger()->debug($intersec);
                                // $serieName = $intersec[0];
                                $rev_intersec = array_reverse($intersec);
                                $serieClassName = array_pop($rev_intersec);
                                self::getLogger()->debug("Adding data to serie " . $serieClassName);
                                // $reportFields[$fieldToGroup]['datasets'][$serieName]['data'][$field_object->label] = $newValue;
                                $toRemove = $cssClassesAndLabels[$serieClassName];
                                // str_replace ( mixed $search , mixed $replace , mixed $subject [,
                                $datasetName = str_replace($toRemove, '', $field_object->label);
                                self::getLogger()->info("Adding answer " . $datasetName . " to chart with field title as dataset name : " . $filteredNewValue);
                                if (empty($filteredNewValue)) {
                                    self::getLogger()->error("Empty value added to dataset " . $datasetName);
                                }
                                $countArray[$fieldToGroup]['orderedAnswers'][$datasetName] = $filteredNewValue;
                                $countArray[$fieldToGroup]['cssSerieClass'] = $serieClassName; // cssSerieClass];
                                $countArray[$fieldToGroup]['cssDatasetLabel'] = $cssClassesAndLabels[$serieClassName];
                                self::getLogger()->debug($filteredNewValue . " added");
                            } else {
                                self::getLogger()->debug($intersec);
                            }
                            /*
                             * if (empty($newValue)) {
                             * self::getLogger()->warn("Empty value for field to group on chart " . $fieldToGroup);
                             *
                             * if ($field_object->type == 'number') {
                             * if ($ignore_empty_values) {
                             * continue;
                             * } else {
                             * self::getLogger()->warn("Set 0 to number field " . $fieldToGroup . " because no value set");
                             * $newValue = 0;
                             * }
                             * }
                             * }
                             * self::getLogger()->debug("New value : '" . $newValue . "' to add.");
                             * $filteredNewValue = apply_filters('mcharts_modify_value_in_answers_array', $newValue);
                             * self::getLogger()->trace("After filter value : '" . $newValue . "' to add.");
                             *
                             * $countArray[$fieldId]['answers'][] = $filteredNewValue;
                             * if (isset($atts['count_answers']) && $atts['count_answers'] == 1) {
                             *
                             * $datasetName = $field_object->label;
                             * self::getLogger()->info("Adding answer " . $datasetName . " to chart with field title as dataset name : " . $filteredNewValue);
                             * if (empty($filteredNewValue)) {
                             * self::getLogger()->error("Empty value added to dataset " . $datasetName);
                             * }
                             * $countArray[$fieldId]['orderedAnswers'][$datasetName] = $filteredNewValue;
                             * self::getLogger()->debug($filteredNewValue . " added");
                             * }
                             */
                        }
                    }
                }
            } else if (isset($atts['group_fields']) && $atts['group_fields'] == 1) {
                self::getLogger()->info("Grouping answers of several fields into one chart");
                // FIXME : what is this ? need to match count to report field id
                
                if (isset($atts['include']) && (! empty($atts['include']))) {
                    $includeArray = explode(",", $atts['include']);
                }
                $fieldId = array_pop(array_reverse($includeArray));
                self::getLogger()->debug("Group fields : " . implode(' | ', $includeArray));
                
                foreach ($entries as $entry) {
                    self::getLogger()->debug("---> entry " . $entry['id']);
                    
                    foreach ($includeArray as $fieldToGroup) {
                        
                        $newValue = rgar($entry, $fieldToGroup);
                        $field_object = GFAPI::get_field($form, $fieldToGroup);
                        if (empty($newValue)) {
                            self::getLogger()->warn("Empty value for field to group on chart " . $fieldToGroup);
                            
                            if ($field_object->type == 'number') {
                                if ($ignore_empty_values) {
                                    continue;
                                } else {
                                    self::getLogger()->warn("Set 0 to number field " . $fieldToGroup . " because no value set");
                                    $newValue = 0;
                                }
                            }
                        }
                        self::getLogger()->debug("New value : '" . $newValue . "' to add.");
                        $filteredNewValue = apply_filters('mcharts_modify_value_in_answers_array', $newValue);
                        self::getLogger()->trace("After filter value : '" . $newValue . "' to add.");
                        
                        $countArray[$fieldId]['answers'][] = $filteredNewValue;
                        if (isset($atts['count_answers']) && $atts['count_answers'] == 1) {
                            
                            $datasetName = $field_object->label;
                            self::getLogger()->info("Adding answer " . $datasetName . " to chart with field title as dataset name : " . $filteredNewValue);
                            if (empty($filteredNewValue)) {
                                self::getLogger()->error("Empty value added to dataset " . $datasetName);
                            }
                            $countArray[$fieldId]['orderedAnswers'][$datasetName] = $filteredNewValue;
                            self::getLogger()->debug($filteredNewValue . " added");
                        } else {
                            // by default process as count anwsers
                            
                            self::getLogger()->info("Counting answers");
                            if (isset($countArray[$fieldId]['orderedAnswers'][$filteredNewValue])) {
                                $countArray[$fieldId]['orderedAnswers'][$filteredNewValue] += 1;
                            } else {
                                $countArray[$fieldId]['orderedAnswers'][$filteredNewValue] = 1;
                            }
                            self::getLogger()->debug($filteredNewValue . " total is now : '" . $countArray[$fieldId]['orderedAnswers'][$filteredNewValue] . "'");
                        }
                    }
                }
            } else {
                
                foreach ($reportFields as $fieldIdx => $fieldData) {
                    $fieldId = $fieldData['gf_field_id'];
                    if (empty($fieldId)) {
                        self::getLogger()->warn('empty field ' . $fieldId);
                        continue;
                    }
                    
                    $fieldType = $fieldData['type'];
                    $inputType = $fieldData['inputType'];
                    if (isset($atts['correctness_count'])) {
                        $correctness_count = $atts['correctness_count'];
                    } else {
                        $correctness_count = false;
                    }
                    self::getLogger()->trace("-> Get answers for field " . $fieldId . " | " . $fieldType . " | " . $inputType . " | " . $correctness_count);
                    $multiRowsSurvey = isset($fieldData['gsurveyLikertEnableMultipleRows']) ? $fieldData['gsurveyLikertEnableMultipleRows'] == 1 : false;
                    // $listCondition
                    $multiRowsList = (isset($fieldData['type']) && $fieldData['type'] == 'list') ? $fieldData['enableColumns'] == 1 : false;
                    
                    $multiRows = ($multiRowsSurvey || $multiRowsList);
                    $multiText = $multiRows ? 'multirows' : 'single row';
                    self::getLogger()->info("-----> Counting answers in entries for field " . $fieldType . ' (' . $multiText . ') : ' . $fieldId);
                    
                    self::getLogger()->trace($fieldData);
                    // $allPossibleValues = array();
                    $countArray[$fieldId] = array();
                    if ($fieldType != 'list' && (! isset($atts['answers_typology']) || ! $atts['answers_typology'])) {
                        $countArray = $this->initialize_ordered_answers($fieldId, $countArray, $fieldData, $atts);
                    }
                    if (isset($atts['answers_typology']) && $atts['answers_typology']) {
                        $countArray[$fieldId]['answers_typology'] = array();
                    }
                    
                    self::getLogger()->trace($countArray[$fieldId]);
                    // used in order not to process name fields twice (or more!)
                    $processed_name_fields = array();
                    
                    foreach ($entries as $entry) {
                        self::getLogger()->debug($fieldType . " ---> entry " . $entry['id']);
                        // self::getLogger()->trace ( $entry );
                        foreach ($entry as $key => $value) {
                            self::getLogger()->trace($fieldType . " process " . $key . " => " . $value);
                            if (! isset($key) || ! isset($value) /*|| strlen ( strval ( $value) ) == 0*/) {
                                self::getLogger()->trace($fieldType . ' ' . $entry['id'] . " one is empty $key or $value");
                                continue;
                            } else {
                                self::getLogger()->trace($fieldType . " process " . $key . " => " . $value);
                            }
                            
                            if ($fieldType == 'list') {
                                if (trim($key) == trim($fieldId)) {
                                    self::getLogger()->debug("Working onlist field serialized data...");
                                    $data = @unserialize($value);
                                    if ($value === 'b:0;' || $data !== false) {
                                        self::getLogger()->debug("serialized");
                                        self::getLogger()->trace($data[0]);
                                        // FIXME make big total if several entries! list_sum_on_value
                                        
                                        $newDatas = array_values($data);
                                        
                                        if (! empty($newDatas)) {
                                            if (isset($countArray[$fieldId]['answers'])) {
                                                $countArray[$fieldId]['answers'] = $this->aggregate_datas($countArray[$fieldId]['answers'], $newDatas, $list_sum_on_value);
                                            } else {
                                                $countArray[$fieldId]['answers'] = $this->aggregate_datas(array(), $newDatas, $list_sum_on_value); // $newDatas;
                                            }
                                        }
                                    } else {
                                        self::getLogger()->warn("not serialized data");
                                        
                                        $countArray[$fieldId]['answers'][] = $value;
                                    }
                                }
                            } else if (($fieldType == 'quiz' && $inputType == 'checkbox') || $fieldType == 'multiselect' || $fieldType == 'name' || $fieldType == 'checkbox' || ($fieldType == 'option' && $fieldData['inputType'] == 'checkbox')) {
                                self::getLogger()->debug($entry['id'] . " ----> Field " . $fieldType . " $key => $value");
                                
                                if (! isset($countArray[$fieldId]['valuesAndLabels'])) {
                                    $countArray[$fieldId]['valuesAndLabels'] = array();
                                }
                                
                                $keyExploded = explode('.', $key);
                                if (isset($keyExploded[0]) && isset($keyExploded[1]) && $keyExploded[0] == $fieldId) {
                                    self::getLogger()->trace("------> Field matches current " . $fieldId . " $key => $value");
                                    if ($correctness_count) {
                                        self::getLogger()->debug("count correctness for " . $key . ' ' . $keyExploded[1]);
                                        if (isset($fieldData['choices'][$keyExploded[1]])) {
                                            $currentItemCorrectness = $fieldData['choices'][$keyExploded[1]]['gquizIsCorrect'];
                                        } else {
                                            self::getLogger()->error("No value set for : " . $keyExploded[1]);
                                            self::getLogger()->error($fieldData['choices']);
                                        }
                                        
                                        self::getLogger()->debug($currentItemCorrectness);
                                        $falsePositive = empty($value) && $currentItemCorrectness;
                                        self::getLogger()->debug($falsePositive);
                                        $falseNegative = $value && ! $currentItemCorrectness;
                                        self::getLogger()->debug($falseNegative);
                                        if ($falsePositive || $falseNegative) {
                                            self::getLogger()->debug("incorrect :(");
                                            $valueForChoice = 'incorrect';
                                            $countArray[$fieldId]['orderedAnswers'][$valueForChoice] += 1;
                                        } else {
                                            self::getLogger()->debug("correct :)");
                                            $valueForChoice = 'correct';
                                            $countArray[$fieldId]['orderedAnswers'][$valueForChoice] += 1;
                                        }
                                    } else if (isset($atts['answers_typology']) && $atts['answers_typology']) {
                                        
                                        if (! empty($value)) {
                                            $countArray[$fieldId]['answers_typology'][$entry['id']][] = $value; // $keyExploded[1];
                                        }
                                    } else {
                                        if (empty($value)) {
                                            self::getLogger()->trace("Checkbox not selected... skips");
                                            continue;
                                        } else if ($fieldType == 'option') {
                                            $splittedOption = explode('|', $value);
                                            $valueForChoice = $splittedOption[0];
                                        } else if ($fieldType == 'name') {
                                            // $splittedOption = explode('|',$value);
                                            $nameFieldKey = $entry['id'] . '_' . $fieldId;
                                            if (in_array($nameFieldKey, $processed_name_fields)) {
                                                self::getLogger()->trace("NAME field already processed");
                                                continue;
                                            }
                                            $firstn = $fieldId . '.3';
                                            $lastn = $fieldId . '.6';
                                            self::getLogger()->trace("NAME field : $firstn $lastn");
                                            $valueForChoice = ucfirst(strtolower(rgar($entry, $firstn))) . ' ' . strtoupper(rgar($entry, $lastn)); // $splittedOption[0];
                                            $processed_name_fields[] = $nameFieldKey;
                                        } else {
                                            $valueForChoice = $value;
                                        }
                                        
                                        self::getLogger()->trace("++++++ Found answer " . $valueForChoice);
                                        $valueForChoice = wp_strip_all_tags($valueForChoice);
                                        $countArray[$fieldId]['answers'][] = $valueForChoice;
                                        if (isset($countArray[$fieldId]['orderedAnswers'][$valueForChoice])) {
                                            $countArray[$fieldId]['orderedAnswers'][$valueForChoice] += 1;
                                        } else {
                                            $countArray[$fieldId]['orderedAnswers'][$valueForChoice] = 1;
                                        }
                                        
                                        $currentTotal = $countArray[$fieldId]['orderedAnswers'][$valueForChoice];
                                        self::getLogger()->trace("==== " . $valueForChoice . " Total : " . $currentTotal);
                                    }
                                } else if ($fieldType == 'multiselect' && ! empty($key) && trim($key) == trim($fieldId)) {
                                    
                                    $field_id = $fieldId;
                                    $form = GFAPI::get_form($entry['form_id']);
                                    $field = GFFormsModel::get_field($form, $field_id);
                                    $field_value = is_object($field) ? $field->get_value_export($entry) : '';
                                    self::getLogger()->debug("==== MULTISELECT values ");
                                    self::getLogger()->debug($field_value);
                                    
                                    $arrayValueForChoice = wp_strip_all_tags($field_value);
                                    
                                    $multiselectAnswersArray = explode(',', $arrayValueForChoice);
                                    self::getLogger()->trace($multiselectAnswersArray);
                                    
                                    foreach ($multiselectAnswersArray as $newAnswer) {
                                        $valueForChoice = trim($newAnswer);
                                        self::getLogger()->trace("Adding new answer : " . $newAnswer);
                                        $countArray[$fieldId]['answers'][] = $valueForChoice;
                                        if (isset($countArray[$fieldId]['orderedAnswers'][$valueForChoice])) {
                                            $countArray[$fieldId]['orderedAnswers'][$valueForChoice] += 1;
                                        } else {
                                            $countArray[$fieldId]['orderedAnswers'][$valueForChoice] = 1;
                                        }
                                    }
                                }
                            } else {
                                self::getLogger()->trace("----> Field " . $fieldType . " $key => $value");
                                if ($fieldType == 'option') {
                                    self::getLogger()->debug("Option with inputType : " . $fieldData['inputType']);
                                }
                                
                                self::getLogger()->trace(trim($key) . " ==? " . trim($fieldId));
                                $multiRowsCondition = ($multiRows && strpos(trim($key), trim($fieldId . '.')) === 0);
                                if (trim($key) == trim($fieldId) || $multiRowsCondition) {
                                    $newValue = '';
                                    self::getLogger()->trace("Field id " . $key . " in entry " . $entry['id'] . " MATCHES report field id " . $fieldId . " : " . " this is an answer to count");
                                    if ($fieldType == 'option') {
                                        self::getLogger()->debug("ADD Option with inputType : " . $fieldData['inputType']);
                                        
                                        $splittedOption = explode('|', $value);
                                        $newValue = $splittedOption[0];
                                        // }
                                    } else if ($fieldType == 'survey') {
                                        self::getLogger()->debug("### SURVEY FIELD " . $fieldData['inputType'] . " ###");
                                        // need to get score from choices
                                        $choice_key = array_search($value, array_column($fieldData['choices'], 'value'));
                                        $survey_score = $fieldData['choices'][$choice_key]['score'];
                                        
                                        if ($multiRows) {
                                            // need to get label instead of value!
                                            self::getLogger()->debug("### MULTI ROWS SURVEY FIELD ###");
                                            self::getLogger()->debug("new answer " . $value . " found in entry " . $entry['id'] . " field id " . $key);
                                            $newValue = $value;
                                        } else {
                                            self::getLogger()->debug("### SINGLE ROW SURVEY FIELD ###");
                                            self::getLogger()->debug("new answer " . $value . " found in entry " . $entry['id'] . " field id " . $key);
                                            self::getLogger()->debug($fieldData);
                                            if ($fieldData['inputType'] == 'rank') {
                                                $orderedAnswers = explode(',', $value);
                                                if (is_array($orderedAnswers)) {
                                                    $orderScore = range(count($orderedAnswers), 1);
                                                    self::getLogger()->trace($orderScore);
                                                    // array_combine ( array $keys , array $values )
                                                    $scoresToSet = array_combine($orderedAnswers, $orderScore);
                                                    foreach ($scoresToSet as $answer => $score) {
                                                        $filteredNewValue = apply_filters('mcharts_modify_value_in_answers_array', $answer);
                                                        self::getLogger()->trace("After filter value : '" . $score . "' to add.");
                                                        $countArray[$fieldId]['answers'][] = $filteredNewValue;
                                                        if (isset($countArray[$fieldId]['orderedAnswers'][$filteredNewValue])) {
                                                            $countArray[$fieldId]['orderedAnswers'][$filteredNewValue] += $score;
                                                        } else {
                                                            $countArray[$fieldId]['orderedAnswers'][$filteredNewValue] = $score;
                                                        }
                                                    }
                                                }
                                            } else {
                                                $newValue = $value;
                                            }
                                        }
                                    } else {
                                        $newValue = $value;
                                    }
                                    
                                    self::getLogger()->debug($fieldType . ") New value : '" . $newValue . "' to add.");
                                    if ($ignore_empty_values && empty($newValue)) {
                                        // ignore empty values of param ignore_empty_values set
                                        self::getLogger()->debug("ignore_empty_values set, ignoring value " . $newValue);
                                        continue;
                                    }
                                    if ($correctness_count) {
                                        self::getLogger()->debug("count correctness for " . $key . ' ' . $newValue);
                                        // $key = array_search('green', $fieldData['choices']); // $key = 2;
                                        $keyOfChoice = array_search($newValue, array_column($fieldData['choices'], 'value'));
                                        $currentItemCorrectness = $fieldData['choices'][$keyOfChoice]['gquizIsCorrect'];
                                        self::getLogger()->debug($currentItemCorrectness);
                                        $falsePositive = empty($value) && $currentItemCorrectness;
                                        self::getLogger()->debug($falsePositive);
                                        $falseNegative = $value && ! $currentItemCorrectness;
                                        self::getLogger()->debug($falseNegative);
                                        if ($falsePositive || $falseNegative) {
                                            self::getLogger()->debug("incorrect :(");
                                            $valueForChoice = 'incorrect';
                                            $countArray[$fieldId]['orderedAnswers'][$valueForChoice] += 1;
                                        } else {
                                            self::getLogger()->debug("correct :)");
                                            $valueForChoice = 'correct';
                                            $countArray[$fieldId]['orderedAnswers'][$valueForChoice] += 1;
                                        }
                                    } else {
                                        $filteredNewValue = apply_filters('mcharts_modify_value_in_answers_array', $newValue);
                                        self::getLogger()->trace("After filter value : '" . $newValue . "' to add.");
                                        $countArray[$fieldId]['answers'][] = $filteredNewValue;
                                        $countArray[$fieldId]['answersValues'][] = $survey_score;
                                        
                                        if (isset($countArray[$fieldId]['orderedAnswers'][$filteredNewValue])) {
                                            $countArray[$fieldId]['orderedAnswers'][$filteredNewValue] += 1;
                                        } else {
                                            $countArray[$fieldId]['orderedAnswers'][$filteredNewValue] = 1;
                                        }
                                        self::getLogger()->trace($filteredNewValue . " total is now : '" . $countArray[$fieldId]['orderedAnswers'][$filteredNewValue] . "'");
                                    }
                                }
                            }
                        }
                    }
                }
            }
            self::getLogger()->debug($countArray);
            return $countArray;
        }
        
        function getGFEntries($form_id, $maxentries = DEFAULT_MAX_ENTRIES, $custom_search_criteria, $atts)
        {
            $form = GFAPI::get_form($form_id);
            $allEntriesNb = GFAPI::count_entries($form_id);
            self::getLogger()->debug("All entries (also deleted!) : " . $allEntriesNb);
            self::getLogger()->debug($atts);
            $search_criteria = array();
            $jsonDecoded = false;
            if (! empty($custom_search_criteria)) {
                $jsonDecoded = json_decode($custom_search_criteria, true);
                if (false !== $jsonDecoded) {
                    $search_criteria = apply_filters('mcharts_modify_custom_search_criteria', $jsonDecoded, $atts);
                } else if (! empty($custom_search_criteria)) {
                    self::getLogger()->error("Cannot JSON decode custom criteria, although non empty");
                    self::getLogger()->error($custom_search_criteria);
                }
            } else {
                $search_criteria['status'] = 'active';
            }
            
            $sorting = null;
            $unique_entry = isset($atts['gf_entry_id']) ? trim($atts['gf_entry_id']) : '';
            $last_entry = false;
            if (! empty($unique_entry)) {
                self::getLogger()->debug($form_id . ' - get unique entry ' . $unique_entry);
                if (is_numeric($unique_entry)) {
                    self::getLogger()->debug($form_id . ' - get unique entry with id ' . $unique_entry);
                    // custom_search_criteria='{"status":"active","field_filters":{"0":{"key":"created_by","value":"current"},"1":{"key":"id","value":"169"}}}'
                    $search_criteria['field_filters'][] = array(
                        'key' => "id",
                        'value' => $unique_entry
                    );
                } elseif ($unique_entry == 'last') {
                    self::getLogger()->debug($form_id . ' - get last entry ' . $unique_entry);
                    $last_entry = true;
                    $sorting = array(
                        'key' => 'date_created',
                        'direction' => 'ASC'
                    );
                }
            }
            
            self::getLogger()->debug("Given Search crit : ");
            self::getLogger()->debug($custom_search_criteria);
            self::getLogger()->debug("Converted to Final Search crit : ");
            self::getLogger()->debug($sorting);
            self::getLogger()->debug($search_criteria);
            
            $paging = array(
                'offset' => 0,
                'page_size' => $maxentries
            );
            
            $entries = GFAPI::get_entries($form_id, $search_criteria, $sorting, $paging);
            $nbOfEntries = count($entries);
            
            if ($last_entry && $nbOfEntries > 0) {
                self::getLogger()->debug($form_id . ' - get last entry ' . $unique_entry);
                $entries = array(
                    end($entries)
                );
                self::getLogger()->debug($entries);
            }
            
            self::getLogger()->debug("Create complete report for form " . $form_id);
            if ($nbOfEntries > 0) {
                self::getLogger()->debug("entries found : " . $nbOfEntries);
            } else {
                self::getLogger()->warn("No entries : " . $nbOfEntries);
            }
            
            return apply_filters('mcharts_filter_gf_entries', $entries, $atts);
        }
        
        function getReportFieldIdxFromId($reportFields, $fieldId)
        {
            $result = - 1;
            self::getLogger()->debug("Finding idx for id " . $fieldId . ' in');
            self::getLogger()->trace($reportFields);
            foreach ($reportFields as $fieldIdx => $fieldDatas) {
                if ($fieldDatas['gf_field_id'] == $fieldId) {
                    $result = $fieldIdx;
                }
            }
            return $result;
        }
        
        function computeScores($countArray, $reportFields, $args)
        {
            if (empty($countArray)) {
                $msg = "Empty count array!";
                self::getLogger()->fatal($msg);
                return $msg;
            } else {
                self::getLogger()->info(count($countArray) . " item(s) in countarray");
            }
            self::getLogger()->debug("Count array");
            self::getLogger()->debug($countArray);
            self::getLogger()->debug("Report fields");
            self::getLogger()->debug($reportFields);
            $size_of_count_array = count($countArray);
            
            // FIXME : splits into two fields when group_by ?
            foreach ($countArray as $fieldIdx => $fieldValues) {
                
                // $fieldId = $fieldValues['gf_field_id'];
                self::getLogger()->debug("Current values:" . $fieldIdx);
                self::getLogger()->debug($fieldValues);
                $reportFieldIdx = $this->getReportFieldIdxFromId($reportFields, $fieldIdx);
                
                $reportFields[$reportFieldIdx]['cssSerieClass'] = isset($fieldValues['cssSerieClass']) ? $fieldValues['cssSerieClass'] : '';
                $reportFields[$reportFieldIdx]['cssDatasetLabel'] = isset($fieldValues['cssDatasetLabel']) ? $fieldValues['cssDatasetLabel'] : '';
                
                $orderedAnswers = isset($fieldValues['orderedAnswers']) ? $fieldValues['orderedAnswers'] : '';
                $answersValues = isset($fieldValues['answersValues']) ? $fieldValues['answersValues'] : '';
                
                if (isset($args['answers_typology']) && $args['answers_typology']) {
                    self::getLogger()->debug("Compute scores for answers typology:");
                    if (isset($fieldValues['answers_typology'])) {
                        $sorted = array();
                        foreach ($fieldValues['answers_typology'] as $entry_id => $set_of_answers) {
                            sort($set_of_answers);
                            $sorted[$entry_id] = $set_of_answers;
                        }
                        
                        foreach ($sorted as $entry_id => $set_of_answers) {
                            $reportFields[$reportFieldIdx]['answers_typology'][$entry_id] = $set_of_answers;
                        }
                    }
                    self::getLogger()->debug("Sorted typology:");
                    self::getLogger()->debug($reportFields[$reportFieldIdx]['answers_typology']);
                    
                    $array_to_count_values = array();
                    foreach ($reportFields[$reportFieldIdx]['answers_typology'] as $set_of_answers) {
                        $array_to_count_values[] = implode('|', $set_of_answers);
                    }
                    
                    $reportFields[$reportFieldIdx]['average'] = array_sum($array_to_count_values) / count($array_to_count_values);
                    $reportFields[$reportFieldIdx]['scores'] = array_count_values($array_to_count_values);
                    $reportFields[$reportFieldIdx]['min'] = min($array_to_count_values);
                    $reportFields[$reportFieldIdx]['max'] = max($array_to_count_values);
                    // Fixme replace all values by Text in choices
                } else if (!empty($orderedAnswers)) {
                    /*$orderedAnswers = $fieldValues['orderedAnswers'];
                     $answersValues = $fieldValues['answersValues'];*/
                    //if (isset($orderedAnswers) && ! empty($orderedAnswers)) {
                    $reportFields[$reportFieldIdx]['scores'] = $orderedAnswers;
                    
                    if ($reportFields[$reportFieldIdx]['type'] == 'survey') {
                        $reportFields[$reportFieldIdx]['average'] = array_sum($answersValues) / count($answersValues);
                        $reportFields[$reportFieldIdx]['min'] = min($answersValues);
                        $reportFields[$reportFieldIdx]['max'] = max($answersValues);
                    } else {
                        $reportFields[$reportFieldIdx]['average'] = array_sum($orderedAnswers) / count($orderedAnswers);
                        $reportFields[$reportFieldIdx]['min'] = min($orderedAnswers);
                        $reportFields[$reportFieldIdx]['max'] = max($orderedAnswers);
                    }
                    if (function_exists('stats_standard_deviation')) {
                        if ($reportFields[$reportFieldIdx]['type'] == 'survey') {
                            $reportFields[$reportFieldIdx]['std_dev'] = stats_standard_deviation($answersValues);
                        } else {
                            $reportFields[$reportFieldIdx]['std_dev'] = stats_standard_deviation($orderedAnswers);
                        }
                    }
                    // }
                } else if (isset($fieldValues['answers'])) {
                    
                    $answers = $fieldValues['answers'];
                    $answersValues = $fieldValues['answersValues'];
                    // replace null values in array
                    $answers = array_replace($answers, array_fill_keys(array_keys($answers, null), ''));
                    
                    self::getLogger()->debug("--> Computing score for field " . $fieldIdx);
                    
                    if (filter_var($args['no_score_computation'], FILTER_VALIDATE_BOOLEAN)) {
                        // just take answers
                        $reportFields[$reportFieldIdx]['scores'] = array_values($answers);
                        self::getLogger()->debug("just take answers for field " . $fieldIdx);
                    } else if (filter_var($args['case_insensitive'], FILTER_VALIDATE_BOOLEAN) == true) {
                        $lowered = array_map('strtolower', $answers);
                        $reportFields[$reportFieldIdx]['scores'] = array_count_values($lowered);
                        $reportFields[$reportFieldIdx]['average'] = array_sum($lowered) / count($lowered);
                        $reportFields[$reportFieldIdx]['min'] = min($lowered);
                        $reportFields[$reportFieldIdx]['max'] = max($lowered);
                        self::getLogger()->debug("count values case INsensitive from answers for field " . $fieldIdx);
                    } else {
                        // $reportFields[$reportFieldIdx]['average'] = array_sum($answers) / count($answers);
                        if ($reportFields[$reportFieldIdx]['type'] == 'survey') {
                            $reportFields[$reportFieldIdx]['average'] = array_sum($answersValues) / count($answersValues);
                            $reportFields[$reportFieldIdx]['min'] = min($answersValues);
                            $reportFields[$reportFieldIdx]['max'] = max($answersValues);
                        } else {
                            $reportFields[$reportFieldIdx]['average'] = array_sum($answers) / count($answers);
                            $reportFields[$reportFieldIdx]['min'] = min($answers);
                            $reportFields[$reportFieldIdx]['max'] = max($answers);
                        }
                        
                        $reportFields[$reportFieldIdx]['scores'] = array_count_values($answers);
                        self::getLogger()->debug("count values case sensitive from answers for field " . $fieldIdx);
                    }
                    self::getLogger()->info("Scores size " . count($reportFields[$reportFieldIdx]['scores']));
                    
                    self::getLogger()->debug("Scores computed: ");
                    self::getLogger()->debug($reportFields[$reportFieldIdx]['scores']);
                    self::getLogger()->debug("Scores computed from ordered answers: ");
                    self::getLogger()->debug($orderedAnswers);
                } else {
                    
                    self::getLogger()->warn("No answers for field " . $fieldIdx);
                    self::getLogger()->warn($fieldValues);
                    $reportFields[$reportFieldIdx]['no_answers'] = 1;
                    continue;
                }
                
                if (isset($fieldValues['valuesAndLabels']) && is_array($fieldValues['valuesAndLabels'])) {
                    $reportFields[$reportFieldIdx]['valuesAndLabels'] = array_merge(array(), $fieldValues['valuesAndLabels']);
                } else {
                    $reportFields[$reportFieldIdx]['valuesAndLabels'] = array();
                }
                
                self::getLogger()->debug("--- Stats computed ---");
                self::getLogger()->debug($reportFields[$reportFieldIdx]['min']);
                self::getLogger()->debug($reportFields[$reportFieldIdx]['max']);
                self::getLogger()->debug($reportFields[$reportFieldIdx]['average']);
                self::getLogger()->debug($reportFields[$reportFieldIdx]['std_dev']);
                
            }
            
            $size_of_report_fields = count($reportFields);
            if ($size_of_count_array != $size_of_report_fields) {
                self::getLogger()->error("array size " . $size_of_count_array . " != " . $size_of_report_fields);
            }
            
            self::getLogger()->info("Scores computed: ");
            self::getLogger()->debug($reportFields);
            
            return $reportFields;
        }
        
        function countDataFor($source, $entries, $reportFields, $args)
        {
            $nb_of_entries = count($entries);
            self::getLogger()->info('countDataFor::Building ' . count($reportFields) . " fields upon " . $nb_of_entries . " entries");
            if ($nb_of_entries == 0) {
                self::getLogger()->error('no entries');
                return $reportFields;
            } else {
                self::getLogger()->info('countDataFor for ' . $nb_of_entries . ' entries');
            }
            
            $countArray = $this->countAnswers($reportFields, $entries, $args);
            
            self::getLogger()->info(count($countArray) . ' graph should be displayed');
            
            $reportFields = $this->computeScores($countArray, $reportFields, $args);
            
            $reportFields = apply_filters('mcharts_gf_filter_fields_after_count', $reportFields, $args);
            
            $toDisplay = count($reportFields) - 1;
            self::getLogger()->debug($toDisplay . ' graph should be displayed');
            self::getLogger()->trace($reportFields);
            $reportFields = $this->buildDatasetsAndLabelsFromScores($reportFields, $args);
            
            return $reportFields;
        }
        
        function getSurveyScores($scores)
        {
            $result = array();
            foreach ($scores as $scoreKey => $scoreValue) {
                $xyValues = explode(':', $scoreKey);
                $datasetName = isset($xyValues[0]) ? $xyValues[0] : '';
                $datasetVal = isset($xyValues[1]) ? $xyValues[1] : '';
                if (! empty($datasetName) && ! empty($datasetVal)) {
                    $result[$datasetName][$datasetVal] = $scoreValue;
                } else {
                    self::getLogger()->error('cannot add survey score for ' . $scoreKey);
                }
            }
            
            return $result;
        }
        
        function getSurveyChoices($choices)
        {
            $result = array();
            foreach ($choices as $choiceKey => $choiceValue) {
                
                $result[$choiceValue['value']] = $choiceValue['text'];
            }
            
            return $result;
        }
        
        /*
         * function removeStringFromKeys($initial, $toRemove){
         * return
         * }
         */
        function buildDatasetsAndLabelsFromScores($reportFields, $args)
        {
            self::getLogger()->debug('buildDatasetsAndLabelsFromScores');
            
            if (! empty($args['css_classes_as_series'])) {
                // build datasets from css classes
                MAXICHARTSAPI::getLogger()->debug("build datasets from css classes");
                $graphType = '';
                $newReportFields = [];
                $reportGroupedFieldId = 1;
                $labels = [];
                
                foreach ($reportFields as $id => $datas) {
                    $css_class = $datas['cssSerieClass'];
                    $cssDatasetLabel = $datas['cssDatasetLabel'];
                    $scores = $datas['scores'];
                    // $scores = array_map( array($this,'removeStringFromKeys') , $datas['scores']);
                    MAXICHARTSAPI::getLogger()->debug($scores);
                    if (isset($newReportFields[$reportGroupedFieldId]['datasets'][$cssDatasetLabel]['data']) && is_array($newReportFields[$reportGroupedFieldId]['datasets'][$cssDatasetLabel]['data'])) {
                        $accumulatedScores = $newReportFields[$reportGroupedFieldId]['datasets'][$cssDatasetLabel]['data'];
                        $labels[] = str_replace($cssDatasetLabel, '', $datas['label']);
                    } else {
                        $newReportFields[$reportGroupedFieldId]['datasets'][$cssDatasetLabel]['data'] = array();
                        $labels[] = str_replace($cssDatasetLabel, '', $datas['label']);
                        $accumulatedScores = array();
                    }
                    // be carefull, if same keys, data will be lost!!!
                    // MAXICHARTSAPI::getLogger()->debug("merging scores ".implode(' | ',$scores)." with previous " .implode(' | ', $accumulatedScores));
                    //$newReportFields[$reportGroupedFieldId]['datasets'][$cssDatasetLabel]['data'] = array_merge($scores, $accumulatedScores);
                    
                    foreach ($scores as $key => $value){
                        $newReportFields[$reportGroupedFieldId]['datasets'][$cssDatasetLabel]['data'][$key] = $value;
                    }
                    
                    $graphType = $datas['graphType'];
                }
                
                $newReportFields[$reportGroupedFieldId]['graphType'] = $graphType;
                $newReportFields[$reportGroupedFieldId]['labels'] = array_unique($labels);
                // reorder datas like labels
                foreach ($newReportFields[$reportGroupedFieldId]['datasets'] as $dataset_name => $dataset) {
                    MAXICHARTSAPI::getLogger()->trace("Before: " . implode(' | ', $dataset['data']));
                    // $array = array( 1, '2', '45' );
                    if (count($newReportFields[$reportGroupedFieldId]['labels']) === count(array_filter($newReportFields[$reportGroupedFieldId]['labels'], 'is_numeric'))) {
                        // all numeric
                        //sort($newReportFields[$reportGroupedFieldId]['datasets'][$dataset_name]['data']);
                    } else {
                        $properOrderedArray = array_merge(array_flip($newReportFields[$reportGroupedFieldId]['labels']), $dataset['data']);
                        $newReportFields[$reportGroupedFieldId]['datasets'][$dataset_name]['data'] = $properOrderedArray;
                        MAXICHARTSAPI::getLogger()->trace("After: " . implode(' | ', $properOrderedArray));
                    }
                    
                }
                $newReportFields[$reportGroupedFieldId]['multisets'] = 1;
                // $reportFields[$id]['datasets'][$datasetNameLabel]['data'][$datasetValLabel] = $scoreDataValue;
                $reportFields = $newReportFields;
                MAXICHARTSAPI::getLogger()->debug($reportFields);
            } else {
                
                foreach ($reportFields as $id => $values) {
                    $scores = isset($values['scores']) ? $values['scores'] : '';
                    if (empty($scores)) {
                        continue;
                    }
                    
                    $multiRows = isset($values['gsurveyLikertEnableMultipleRows']) ? $values['gsurveyLikertEnableMultipleRows'] == 1 : false;
                    $forceMultisets = $values['multisets'] == 1 ? true : false;
                    if ($multiRows) {
                        self::getLogger()->debug("SURVEY MULTIROWS");
                        $arraySurveyScores = $this->getSurveyScores($scores);
                        $arraySurveyChoices = $this->getSurveyChoices($values['choices']);
                        $reportFields[$id]['labels'] = array();
                        foreach ($values['inputs'] as $inputIdx => $inputData) {
                            $questionId = trim($inputData['name']);
                            $datasetNameLabel = trim($inputData['label']);
                            
                            // $scoreValues = $arraySurveyScores[$questionId];
                            foreach ($arraySurveyChoices as $choiceId => $choicesText) {
                                $datasetValLabel = $choicesText;
                                // foreach ($scoreValues as $answerId => $dataValue) {
                                // $datasetValLabel = $arraySurveyChoices[$answerId];
                                $dataValue = $arraySurveyScores[$questionId][$choiceId];
                                
                                if ($values['datasets_invert']) {
                                    $reportFields[$id]['datasets'][$datasetValLabel]['data'][$datasetNameLabel] = $dataValue;
                                    if (! in_array($datasetNameLabel, $reportFields[$id]['labels'])) {
                                        $reportFields[$id]['labels'][] = $datasetNameLabel;
                                    }
                                } else {
                                    $reportFields[$id]['datasets'][$datasetNameLabel]['data'][$datasetValLabel] = $dataValue;
                                    if (! is_array($reportFields[$id]['labels']) || ! in_array($datasetValLabel, $reportFields[$id]['labels'])) {
                                        $reportFields[$id]['labels'][] = $datasetValLabel;
                                    }
                                }
                            }
                        }
                        
                        $reportFields[$id]['labels'] = apply_filters('mcharts_modify_multirows_labels', $reportFields[$id]['labels']);
                        self::getLogger()->debug($reportFields[$id]['labels']);
                        self::getLogger()->debug($reportFields[$id]['datasets']);
                    } else if ($values['type'] == 'list') {
                        self::getLogger()->debug("LIST");
                        // FIXME add all possible datasets even if no score (in order to keep colors ordered)
                        
                        // $reportFields[$id]['datasets'][$datasetNameLabel]
                        $data_conversion = $args['data_conversion'];
                        MAXICHARTSAPI::getLogger()->debug("### data_conversion " . $data_conversion);
                        MAXICHARTSAPI::getLogger()->debug($data_conversion);
                        $decoded_json_data = json_decode($data_conversion, true);
                        $transformationData = $decoded_json_data['transformation'];
                        MAXICHARTSAPI::getLogger()->debug($transformationData);
                        $typesToChange = array_keys($transformationData);
                        foreach ($typesToChange as $datasetName) {
                            $reportFields[$id]['datasets'][$datasetName] = array();
                        }
                        foreach ($scores as $scoreKey => $scoreValue) {
                            
                            $datasetNameLabel = $scoreValue[$args['list_series_names']];
                            $valLabelArray = explode('+', $args['list_series_values']);
                            
                            $mappedValLabelArray = array();
                            foreach ($valLabelArray as $labelPart) {
                                $mappedValLabelArray[] = $scoreValue[$labelPart];
                            }
                            $datasetValLabel = implode(' ', $mappedValLabelArray);
                            $scoreDataValue = $scoreValue[$args['list_labels_names']];
                            
                            $scoreDataValue = str_replace(',', '', $scoreDataValue);
                            $roundPrecision = 0;
                            $scoreDataValue = round(floatval($scoreDataValue), $roundPrecision);
                            // round ( float $val [, int $precision = 0 [, int $mode = PHP_ROUND_HALF_UP ]] )
                            
                            $reportFields[$id]['datasets'][$datasetNameLabel]['data'][$datasetValLabel] = $scoreDataValue;
                            $reportFields[$id]['labels'][] = $datasetValLabel;
                        }
                        
                        $allLabels = $reportFields[$id]['labels'];
                        $allUniqueLabels = array_unique($allLabels);
                        sort($allUniqueLabels);
                        $reportFields[$id]['labels'] = $allUniqueLabels;
                        
                        // add missing keys and sort in order to graph correctly
                        foreach ($reportFields[$id]['datasets'] as $dataSetName => $dataSetData) {
                            $dataSetLabels = array_keys($dataSetData['data']);
                            $labelDiff = array_diff($allUniqueLabels, $dataSetLabels);
                            $newArrayPart = array_fill_keys($labelDiff, 0);
                            $newDatasetData = array_merge($reportFields[$id]['datasets'][$dataSetName]['data'], $newArrayPart);
                            ksort($newDatasetData);
                            $reportFields[$id]['datasets'][$dataSetName]['data'] = $newDatasetData;
                        }
                    } else {
                        self::getLogger()->debug("STANDARD FIELD TYPE");
                        $percents = isset($values['percents']) ? $values['percents'] : '';
                        
                        if (! empty($reportFields[$id]['valuesAndLabels'])) {
                            self::getLogger()->debug("get labels from valuesAndlabels");
                            self::getLogger()->trace($reportFields[$id]['valuesAndLabels']);
                            $reportFields[$id]['labels'] = array_values($reportFields[$id]['valuesAndLabels']);
                        } else {
                            $reportFields[$id]['labels'] = array_keys($scores);
                        }
                        
                        $reportFields[$id]['data'] = array_values($scores);
                        $reportFields[$id]['labels'] = apply_filters('mcharts_modify_singlerow_labels', $reportFields[$id]['labels']);
                    }
                    
                    if (! isset($reportFields[$id]['labels']) || empty($reportFields[$id]['labels'])) {
                        self::getLogger()->error("buildDatasetsAndLabelsFromScores::No labels for field id " . $id);
                    } else {
                        self::getLogger()->debug("Labels set for field id " . $id);
                        self::getLogger()->debug($reportFields[$id]['labels']);
                    }
                }
            }
            
            self::getLogger()->debug($reportFields);
            return $reportFields;
        }
        
        function createReportFieldForList($reportFields)
        {}
        
        function sumAllScoresValues($report_fields, $includeArray, $list_sum_keys)
        {
            self::getLogger()->info("SUM REPORT FIELDS #### " . count($report_fields) . " entries of field(s) ");
            $keys_to_sum = explode(',', $list_sum_keys);
            
            foreach ($report_fields as $idx => $datas) {
                self::getLogger()->debug("$idx => $datas");
                if (! is_numeric($idx)) {
                    continue;
                }
                
                if (! empty($includeArray)) {
                    if (! in_array($idx, $includeArray)) {
                        continue;
                    }
                }
                
                self::getLogger()->debug("$idx => $datas");
                // $keys_to_sum = explode(',',$list_sum_keys);
                // self::getLogger ()->debug ( $datas );
                self::getLogger()->debug("score elements to add " . count($datas['scores']));
                foreach ($datas['scores'] as $idx => $data) {
                    foreach ($data as $list_key => $list_value) {
                        self::getLogger()->trace($data);
                        $key_found = array_search($list_key, $keys_to_sum);
                        if ($list_sum_keys == 'all' || $key_found !== false) {
                            if (is_numeric($list_value)) {
                                $sumArray[] = intval($list_value);
                            }
                        }
                    }
                }
            }
            
            self::getLogger()->trace("### Size of sum array " . count($sumArray));
            $errMsg = __('Empty sum array');
            $errLogMsg = $errMsg . ' : ' . count($entries) . " entries of field(s) " . implode('/', $includeArray);
            if (empty($sumArray)) {
                self::getLogger()->warn($errLogMsg);
                return $errMsg;
            } else {
                self::getLogger()->debug($sumArray);
                $result = array_sum($sumArray);
                self::getLogger()->debug("successfully computed sum : " . $result);
                return $result;
            }
        }
        
        function countEntriesBy($entries, $includeArray, $datasets_invert, $type)
        {
            $reportFields = array();
            // $userVal = reset($includeArray);
            $idx = 0;
            foreach ($includeArray as $userVal) {
                self::getLogger()->debug("count mode: " . $userVal);
                $reportFields[$idx] = array();
                foreach ($entries as $entry) {
                    $valueOfUserField = rgar($entry, $userVal);
                    self::getLogger()->debug("count mode: " . $userVal . "/" . $valueOfUserField);
                    if ((! empty($valueOfUserField))) {
                        
                        if ($userVal === "created_by") {
                            $author_obj = get_user_by('id', $valueOfUserField);
                            $valToInsert = $author_obj->display_name;
                        } else {
                            $valToInsert = $valueOfUserField;
                        }
                        self::getLogger()->debug("count mode:inserting: " . $valToInsert);
                        $reportFields[$idx]['answers'][] = $valToInsert;
                        $reportFields[$idx]['datasets_invert'] = $datasets_invert;
                        $reportFields[$idx]['label'] = $userVal;
                        $reportFields[$idx]['graphType'] = $type;
                    }
                }
                $idx ++;
            }
            return $reportFields;
        }
        
        function sumAllValues($entries, $includeArray, $list_sum_keys)
        {
            self::getLogger()->info("SUM #### " . count($entries) . " entries of field(s) " . implode('/', $includeArray));
            $sumArray = array();
            foreach ($includeArray as $field_id_to_count) {
                foreach ($entries as $entry) {
                    if (is_numeric($field_id_to_count)) {
                        $valToSum = rgar($entry, strval($field_id_to_count));
                        if (is_numeric($valToSum)) {
                            $sumArray[] = intval($valToSum);
                        } else {
                            self::getLogger()->debug("Not an int value for field " . $valToSum);
                            $unserializeData = @unserialize($valToSum);
                            if ($valToSum === 'b:0;' || $unserializeData !== false) {
                                self::getLogger()->debug("Serialized value decoded ");
                                self::getLogger()->trace($list_sum_keys);
                                
                                $keys_to_sum = explode(',', $list_sum_keys);
                                self::getLogger()->trace($keys_to_sum);
                                
                                // $itemTotal = 0;
                                foreach ($unserializeData as $idx => $datas) {
                                    foreach ($datas as $list_key => $list_value) {
                                        $key_found = array_search($list_key, $keys_to_sum);
                                        if ($list_sum_keys == 'all' || $key_found !== false) {
                                            if (is_numeric($list_value)) {
                                                $sumArray[] = intval($list_value);
                                            }
                                        }
                                    }
                                }
                            } else {
                                self::getLogger()->warn("Not an int nor a serialized value for field " . $valToSum);
                            }
                        }
                    } else {
                        self::getLogger()->warn("Not an numeric field number " . $field_id_to_count);
                    }
                }
            }
            if (is_array($sumArray)) {
                self::getLogger()->debug("Size of sum array " . count($sumArray));
            } else {
                self::getLogger()->error("No sum array");
            }
            $errMsg = __('Cannot compute sum');
            $errLogMsg = $errMsg . ' : ' . count($entries) . " entries of field(s) " . implode('/', $includeArray);
            if (empty($sumArray)) {
                self::getLogger()->warn($errLogMsg);
                return $errMsg;
            } else {
                $result = array_sum($sumArray);
                self::getLogger()->debug("successfully computed sum : " . $result);
                return $result;
            }
        }
        
        function getRadarDatasets($gf_form_id, $entries, $datasets_field, $includeArray)
        {
            self::getLogger()->debug("---> Create RADAR with " . $datasets_field);
            // if $datasets_field is numeric, it is the field number to get datasets names and values from
            // if it equals to 'entry', there is only one dataset,and each field is a new dimension of the radar chart
            
            $form = GFAPI::get_form($gf_form_id);
            
            $reportFields[0] = array(
                'datasets' => array(),
                'labels' => array()
            );
            $reportFields[0]['graphType'] = 'radar';
            $reportFields[0]['multisets'] = 1;
            $includedLabels = array();
            self::getLogger()->debug($reportFields);
            foreach ($entries as $entry) {
                self::getLogger()->debug("---> entry " . $entry['id']);
                
                foreach ($entry as $key => $value) {
                    self::getLogger()->debug($entry['id'] . ") process " . $key . " => " . $value);
                    if ($key == $datasets_field) {
                        self::getLogger()->debug("Radar new dataset " . $key . " => " . $value);
                        // $reportFields[0]['datasets'] = array();
                        $data = array();
                        // $labelsForDataset = array();
                        self::getLogger()->debug("Process all fields as radar axis : " . implode($includeArray));
                        foreach ($includeArray as $includeFieldId) {
                            
                            $includedValue = rgar($entry, $includeFieldId);
                            if (empty($includedValue)) {
                                self::getLogger()->error("No value in entry for field " . $includeFieldId);
                            }
                            // self::getLogger()->debug($form['fields'][$includeFieldId]);
                            $data[] = $includedValue;
                            // $form['fields'][0]->label;
                            
                            $fieldOfForm = GFAPI::get_field($form, $includeFieldId);
                            $newLabel = $fieldOfForm->label;
                            // $newLabel = $form['fields'][$includeFieldId]->label;
                            if (empty($newLabel)) {
                                self::getLogger()->error("No value in entry for label " . $newLabel);
                            }
                            
                            self::getLogger()->debug("radar " . $key . ' / ' . $newLabel . " add new data " . $includeFieldId . " = " . $includedValue);
                            if (! in_array($newLabel, $includedLabels)) {
                                $includedLabels[] = $newLabel;
                                self::getLogger()->debug("+++ New label added : " . $newLabel);
                            }
                            
                            // $newDataset['labels'][] = $datasetValLabel;
                        }
                        
                        $newDataset = array(
                            'data' => $data,
                            'label' => $key
                        );
                        // $newDataset['labels'] = $includedLabels;
                        $reportFields[0]['datasets'][$value] = $newDataset;
                    }
                }
            }
            
            $reportFields[0]['labels'] = $includedLabels;
            
            self::getLogger()->debug($reportFields);
            return $reportFields;
        }
        
        function get_data_from_gf($reportFields, $source, $atts)
        {
            self::getLogger()->info("Process source " . $source);
            // $reportFields = array ();
            if ($source == 'gf') {
                
                $defaultsParameters = array(
                    'type' => 'pie',
                    'mode' => '',
                    'url' => '',
                    'position' => '',
                    'float' => false,
                    'center' => false,
                    'title' => 'chart',
                    'canvaswidth' => '625',
                    'canvasheight' => '625',
                    'width' => '48%',
                    'height' => 'auto',
                    'margin' => '5px',
                    'relativewidth' => '1',
                    'align' => '',
                    'class' => '',
                    'labels' => '',
                    'data' => '30,50,100',
                    'data_conversion' => '',
                    'datasets_invert' => '',
                    'datasets' => '',
                    'gf_form_ids' => '',
                    'multi_include' => '',
                    'multisets' => '',
                    'datasets_field' => '',
                    'gf_form_id' => '1',
                    'gf_entry_id' => '',
                    'group_fields' => '',
                    'css_classes_as_series' => '',
                    'css_datasets_labels' => '',
                    'count_answers' => '1',
                    'maxentries' => strval(DEFAULT_MAX_ENTRIES),
                    'gf_criteria' => '',
                    'include' => '',
                    'exclude' => '',
                    'ignore_empty_values' => false,
                    'colors' => '',
                    'color_set' => '',
                    'color_rand' => false,
                    'chart_js_options' => '',
                    'tooltip_style' => 'BOTH',
                    'grouped_tooltips' => false,
                    'custom_search_criteria' => '',
                    'fillopacity' => '0.7',
                    'fill' => 'false',
                    'pointstrokecolor' => '#FFFFFF',
                    'animation' => 'true',
                    'xaxislabel' => '',
                    'yaxislabel' => '',
                    'scalefontsize' => '12',
                    'scalefontcolor' => '#666',
                    'scaleoverride' => 'false',
                    'scalesteps' => 'null',
                    'scalestepwidth' => 'null',
                    'scalestartvalue' => 'null',
                    'case_insensitive' => false,
                    'no_score_computation' => false,
                    'list_series_names' => '',
                    'list_series_values' => '',
                    'list_labels_names' => '',
                    'list_sum_keys' => 'all',
                    'data_only' => '',
                    'xcol' => '0',
                    'ycol' => '1',
                    'compute' => '',
                    'header_start' => '0',
                    'header_size' => '1',
                    // new CSV
                    'columns' => '',
                    'rows' => '',
                    'delimiter' => '',
                    'information_source' => '',
                    'no_entries_custom_message' => '',
                    'filter' => false
                );
                self::getLogger()->debug($atts);
                $atts = shortcode_atts($defaultsParameters, $atts);
                
                $datasets_field = trim($atts['datasets_field']);
                $type = trim($atts['type']);
                $mode = trim($atts['mode']);
                $url = trim($atts['url']);
                $title = trim($atts['title']);
                $data = explode(',', trim($atts['data']));
                $data_conversion = trim($atts['data_conversion']);
                $datasets_invert = trim($atts['datasets_invert']);
                $no_entries_custom_message = $atts['no_entries_custom_message'];
                // $gv_approve_status = explode ( ";", str_replace ( ' ', '', $gv_approve_status) ']);
                $datasets = explode("next", trim($atts['datasets']));
                $gf_form_ids = explode(',', trim($atts['gf_form_ids']));
                $multi_include = explode(',', trim($atts['multi_include']));
                $gf_form_id = trim($atts['gf_form_id']);
                if (empty($gf_form_id) || $gf_form_id < 0) {
                    $gf_form_id = 1;
                }
                $colors = trim($atts['colors']);
                $color_set = trim($atts['color_set']);
                $color_rand = trim($atts['color_rand']);
                $position = trim($atts['position']);
                $float = trim($atts['float']);
                $center = trim($atts['center']);
                $fill_option = trim($atts['fill']);
                // $information_source = $information_source;
                $case_insensitive = filter_var(trim($atts['case_insensitive']), FILTER_VALIDATE_BOOLEAN);
                $no_score_computation = filter_var(trim($atts['no_score_computation']), FILTER_VALIDATE_BOOLEAN);
                $ignore_empty_values = filter_var(trim($atts['ignore_empty_values']), FILTER_VALIDATE_BOOLEAN);
                self::getLogger()->debug($atts['ignore_empty_values'] . " : ignore_empty_values : $ignore_empty_values");
                $list_series_names = trim($atts['list_series_names']);
                $list_series_values = trim($atts['list_series_values']);
                $list_labels_names = trim($atts['list_labels_names']);
                
                $include = trim($atts['include']);
                $exclude = trim($atts['exclude']);
                $custom_search_criteria = trim($atts['custom_search_criteria']);
                $tooltip_style = trim($atts['tooltip_style']);
                //$groupedTooltips = filter_var(trim($atts['grouped_tooltips']), FILTER_VALIDATE_BOOLEAN);
                $xcol = trim($atts['xcol']);
                $columns = trim($atts['columns']);
                $rows = trim($atts['rows']);
                self::getLogger()->debug($columns);
                if (! empty($columns)) {
                    $columns = maxicharts_reports::get_all_ranges($columns);
                    self::getLogger()->debug($columns);
                }
                self::getLogger()->debug($rows);
                if (! empty($rows)) {
                    $rows = maxicharts_reports::get_all_ranges($rows);
                    self::getLogger()->debug($rows);
                }
                $delimiter = trim($atts['delimiter']);
                
                $compute = trim($atts['compute']);
                $maxentries = trim($atts['maxentries']);
                if (empty($maxentries)) {
                    $maxentries = DEFAULT_MAX_ENTRIES;
                }
                $header_start = trim($atts['header_start']);
                $header_size = trim($atts['header_size']);
                
                if ((! empty($include))) {
                    $includeArray = explode(",", $include);
                }
                if (! empty($exclude)) {
                    $excludeArray = explode(",", $exclude);
                }
                
                self::getLogger()->info("Get DATAS from GF source " . $source);
                // FIXME : multi gf sources does not work
                /*
                 * if (! empty($gf_form_ids) && count($gf_form_ids) > 1 && ! empty($multi_include) && count($multi_include) > 1) {
                 * // process multi-form sources
                 * self::getLogger()->info("#### MULTI sources process");
                 *
                 * $multiCombined = array_combine($gf_form_ids, $multi_include);
                 * self::getLogger()->info($multiCombined);
                 * $countArray = array();
                 * foreach ($multiCombined as $gf_id => $field_id) {
                 * self::getLogger()->info("#### MULTI " . $gf_id . ' -> ' . $field_id);
                 * $entries = $this->getGFEntries($gf_id, $maxentries, $custom_search_criteria, $atts);
                 * $currentReportFields = $this->buildReportFieldsForGF($gf_id, $type, array(
                 * $field_id
                 * ), null, $datasets_invert);
                 *
                 * self::getLogger()->info("#### MULTI Counting " . $gf_id . ' -> ' . $field_id);
                 *
                 * $currentCount = $this->countAnswers($currentReportFields, $entries);
                 * $reportFieldsArray[] = $currentReportFields;
                 *
                 * $answers = reset($currentCount)['answers'];
                 *
                 * $mergedAnswers = array_merge($mergedAnswers, $answers);
                 * $countArray[] = $currentCount;
                 * }
                 *
                 * self::getLogger()->debug("#### MULTI DATA RETRIEVED " . count($reportFieldsArray) . ' graph should be merged');
                 * self::getLogger()->debug($countArray);
                 * self::getLogger()->debug($reportFieldsArray);
                 * $reportFields = reset($reportFieldsArray);
                 * self::getLogger()->debug($reportFields);
                 *
                 * self::getLogger()->debug(array_search("answers", $countArray));
                 *
                 * $reportFields = $this->computeScores($countArray, $reportFields);
                 *
                 * self::getLogger()->info($rpa);
                 * } else
                 */
                 if (! empty($gf_form_id) && $gf_form_id > 0) {
                     self::getLogger()->info("#### SINGLE source process");
                     $entries = $this->getGFEntries($gf_form_id, $maxentries, $custom_search_criteria, $atts);
                     $nbOfFechedEntries = count($entries);
                     if ($nbOfFechedEntries > 0) {
                         self::getLogger()->info($nbOfFechedEntries . " entries fetched");
                         if ($type === 'total') {
                             return $nbOfFechedEntries;
                         } else if ($mode === 'count') {
                             self::getLogger()->debug("count mode");
                             $reportFields = $this->countEntriesBy($entries, $includeArray, $datasets_invert, $type);
                         } else if ($type === 'sum' || $type === 'sum_entries') {
                             
                             // $totalCount = 0;
                             $sumArray = $this->sumAllValues($entries, $includeArray, $list_sum_keys);
                             return $sumArray;
                         } else if ($type === 'array') {
                             self::getLogger()->info("#### array type");
                             $result = MAXICHARTSAPI::getArrayForFieldInForm($entries, $includeArray);
                             self::getLogger()->info("#### array type result");
                             self::getLogger()->info($result);
                             return $result;
                         } else if ($type === 'list') {
                             return $this->listValuesOfFieldInForm($entries, $includeArray);
                         } else if ($type === 'radar' && ! empty($datasets_field)) {
                             self::getLogger()->info('Radar with ' . $datasets_field);
                             
                             return $this->getRadarDatasets($gf_form_id, $entries, $datasets_field, $includeArray);
                         } else {
                             
                             // if standard graph type
                             $reportFields = $this->buildReportFieldsForGF($gf_form_id, $type, isset($includeArray) ? $includeArray : null, isset($excludeArray) ? $excludeArray : null, $datasets_invert, $atts);
                             
                             self::getLogger()->debug(count($reportFields) . ' graph(s) should be displayed');
                         }
                         
                         if (! empty($reportFields['error'])) {
                             self::getLogger()->error($reportFields['error']);
                             return $reportFields['error'];
                         }
                         
                         if (empty($reportFields)) {
                             $msg = "No data available for fields";
                             self::getLogger()->warn($msg);
                             return $msg;
                         }
                         if ($mode === 'count') {
                             
                             $countArray = array_merge(array(), $reportFields);
                             
                             self::getLogger()->debug($reportFields);
                             self::getLogger()->debug($countArray);
                             
                             $reportFields = $this->computeScores($countArray, $reportFields, $atts);
                             $reportFields = apply_filters('mcharts_gf_filter_fields_after_count', $reportFields, $atts);
                             $reportFields = $this->buildDatasetsAndLabelsFromScores($reportFields, $atts);
                         } else {
                             self::getLogger()->debug('### Entries values computation on report fields:', count($reportFields));
                             
                             $reportFields = $this->countDataFor($source, $entries, $reportFields, $atts);
                             
                             if ($type === 'sum_report_fields') {
                                 self::getLogger()->debug($reportFields);
                                 $sumArray = $this->sumAllScoresValues($reportFields, $includeArray, $list_sum_keys);
                                 return $sumArray;
                             }
                         }
                     } else {
                         
                         $form_object = GFAPI::get_form($gf_form_id);
                         $formTile = '';
                         if (! is_wp_error($form_object)) {
                             $formTitle = $form_object['title'];
                             $formId = $form_object['id'];
                         }
                         
                         if (empty($no_entries_custom_message)) {
                             $displayed_msg = "No answer to form " . '<em> ' . $formTitle . ' </em> (' . $formId . ') ' . "yet";
                         } else {
                             $displayed_msg = $no_entries_custom_message;
                         }
                         
                         self::getLogger()->warn($displayed_msg);
                         self::getLogger()->warn("check filter:");
                         self::getLogger()->warn($custom_search_criteria);
                         return $displayed_msg;
                     }
                 }
            }
            
            self::getLogger()->info(__CLASS__ . ' returns ' . count($reportFields) . ' report fields');
            
            return $reportFields;
        }
    }
}

$add_on = new maxicharts_gravity_forms(__FILE__);
if ($add_on) {
    call_user_func(array(
        $add_on,
        'add_hooks'
    ));
}