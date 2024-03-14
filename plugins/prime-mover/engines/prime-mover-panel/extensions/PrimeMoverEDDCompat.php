<?php
namespace Codexonics\PrimeMoverFramework\extensions;

/*
 * This file is part of the Codexonics.PrimeMoverFramework package.
 *
 * (c) Codexonics Ltd
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Codexonics\PrimeMoverFramework\classes\PrimeMover;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Prime Mover Easy Digital Downloads Compatibility Class
 * Helper class for interacting with WordPress EDD plugin
 *
 */
class PrimeMoverEDDCompat
{     
    private $prime_mover;
    private $edd_plugin;
    private $edd_pro_plugin;
    private $callbacks;
    
    /**
     * Construct
     * @param PrimeMover $prime_mover
     * @param array $utilities
     */
    public function __construct(PrimeMover $prime_mover, $utilities = [])
    {
        $this->prime_mover = $prime_mover;
        $this->edd_plugin = 'easy-digital-downloads/easy-digital-downloads.php';
        $this->edd_pro_plugin = 'easy-digital-downloads-pro/easy-digital-downloads.php';
        
        $this->callbacks = [
            'maybeAdjustEDDOrders' => 511,
            'maybeAdjustEDDNotes' => 512,
            'maybeAdjustEDDLogsApiRequests' => 513,
            'maybeAdjustEDDLogs' => 514,
            'maybeAdjustEDDCustomers' => 515
        ];
    }
    
    /**
     * Get Prime Mover
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMover
     */
    public function getPrimeMover()
    {
        return $this->prime_mover;
    }
    
    /**
     * Get system initialization
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemInitialization
     */
    public function getSystemInitialization()
    {
        return $this->getPrimeMover()->getSystemInitialization();    
    }
    
    /**
     * Get system functions
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemFunctions
     */
    public function getSystemFunctions()
    {
        return $this->getPrimeMover()->getSystemFunctions();
    }
    
    /**
     * Get callbacks
     * @return number[]
     */
    public function getCallBacks()
    {
        return $this->callbacks;
    }
    
    /**
     * Get Easy Digital Downloads plugin
     * @return string
     */
    public function getEDDPlugin()
    {
        return $this->edd_plugin;
    }
    
    /**
     * Get Easy Digital Downloads PRO plugin
     * @return string
     */
    public function getEDDProPlugin()
    {
        return $this->edd_pro_plugin;
    }
                        
    /**
     * Initialize hooks
     */
    public function initHooks()
    {      
        foreach ($this->getCallBacks() as $callback => $priority) {
            add_filter('prime_mover_do_process_thirdparty_data', [$this, $callback], $priority, 3);
        }
        
        add_action('prime_mover_before_thirdparty_data_processing', [$this, 'removeProcessorHooksWhenDependencyNotMeet'], 10, 2); 
    }   
 
    /**
     * [OK] Adjust user IDs in EDD orders table
     * Hooked to `prime_mover_do_process_thirdparty_data` filter, priority 511
     * @param array $ret
     * @param number $blogid_to_import
     * @param number $start_time
     * @return array
     */
    public function maybeAdjustEDDOrders($ret = [], $blogid_to_import = 0, $start_time = 0)
    {
        $validation_error = apply_filters('prime_mover_validate_thirdpartyuser_processing', $ret, $blogid_to_import, [$this->getEDDPlugin(),$this->getEDDProPlugin()]);
        if (is_array($validation_error)) {
            return $validation_error;
        }
        
        if (!empty($ret['3rdparty_current_function']) && __FUNCTION__ !== $ret['3rdparty_current_function']) {
            return $ret;
        }
        
        $ret['3rdparty_current_function'] = __FUNCTION__;
        $specs = $this->getSystemInitialization()->getSpecificationsFromIdentifier(sha1(__METHOD__));
        if (empty($specs)) {
            return $this->getSystemFunctions()->logSkippedDefaultUserAdj($ret, $blogid_to_import);
        }
        
        list($table, $primary_index, $user_column) = $specs;
        $column_strings = "{$primary_index}, {$user_column}";
        $this->getSystemFunctions()->maybeLogDefaultUserAdj(__METHOD__, $table, $primary_index, $user_column, $blogid_to_import);
        
        $leftoff_identifier = "3rdparty_{$table}_leftoff";
        $update_variable = "3rdparty_{$table}_updated";
        
        $progress_identifier = 'EDD orders table';
        $last_processor = apply_filters('prime_mover_is_thirdparty_lastprocessor', false, $this, __FUNCTION__, $ret, $blogid_to_import);
        $handle_unique_constraint = '';
        
        return apply_filters('prime_mover_process_userid_adjustment_db', $ret, $table, $blogid_to_import, $leftoff_identifier, $primary_index, $column_strings,
            $update_variable, $progress_identifier, $start_time, $last_processor, $handle_unique_constraint);
    }   

    /**
     * [OK] Adjust user IDs in EDD notes table
     * Hooked to `prime_mover_do_process_thirdparty_data` filter, priority 512
     * @param array $ret
     * @param number $blogid_to_import
     * @param number $start_time
     * @return array
     */
    public function maybeAdjustEDDNotes($ret = [], $blogid_to_import = 0, $start_time = 0)
    {
        $validation_error = apply_filters('prime_mover_validate_thirdpartyuser_processing', $ret, $blogid_to_import, [$this->getEDDPlugin(),$this->getEDDProPlugin()]);
        if (is_array($validation_error)) {
            return $validation_error;
        }
        
        if (!empty($ret['3rdparty_current_function']) && __FUNCTION__ !== $ret['3rdparty_current_function']) {
            return $ret;
        }
        
        $ret['3rdparty_current_function'] = __FUNCTION__;
        $specs = $this->getSystemInitialization()->getSpecificationsFromIdentifier(sha1(__METHOD__));
        if (empty($specs)) {
            return $this->getSystemFunctions()->logSkippedDefaultUserAdj($ret, $blogid_to_import);
        }
        
        list($table, $primary_index, $user_column) = $specs;
        $column_strings = "{$primary_index}, {$user_column}";
        $this->getSystemFunctions()->maybeLogDefaultUserAdj(__METHOD__, $table, $primary_index, $user_column, $blogid_to_import);
        
        $leftoff_identifier = "3rdparty_{$table}_leftoff";
        $update_variable = "3rdparty_{$table}_updated";
        
        $progress_identifier = 'EDD notes table';
        $last_processor = apply_filters('prime_mover_is_thirdparty_lastprocessor', false, $this, __FUNCTION__, $ret, $blogid_to_import);
        $handle_unique_constraint = '';
        
        return apply_filters('prime_mover_process_userid_adjustment_db', $ret, $table, $blogid_to_import, $leftoff_identifier, $primary_index, $column_strings,
            $update_variable, $progress_identifier, $start_time, $last_processor, $handle_unique_constraint);
    }
    
    /**
     * Adjust user IDs in EDD logs API requests
     * Hooked to `prime_mover_do_process_thirdparty_data` filter, priority 513
     * @param array $ret
     * @param number $blogid_to_import
     * @param number $start_time
     * @return array
     */
    public function maybeAdjustEDDLogsApiRequests($ret = [], $blogid_to_import = 0, $start_time = 0)
    {
        $validation_error = apply_filters('prime_mover_validate_thirdpartyuser_processing', $ret, $blogid_to_import, [$this->getEDDPlugin(),$this->getEDDProPlugin()]);
        if (is_array($validation_error)) {
            return $validation_error;
        }
        
        if (!empty($ret['3rdparty_current_function']) && __FUNCTION__ !== $ret['3rdparty_current_function']) {
            return $ret;
        }
        
        $ret['3rdparty_current_function'] = __FUNCTION__;
        $specs = $this->getSystemInitialization()->getSpecificationsFromIdentifier(sha1(__METHOD__));
        if (empty($specs)) {
            return $this->getSystemFunctions()->logSkippedDefaultUserAdj($ret, $blogid_to_import);
        }
        
        list($table, $primary_index, $user_column) = $specs;
        $column_strings = "{$primary_index}, {$user_column}";
        $this->getSystemFunctions()->maybeLogDefaultUserAdj(__METHOD__, $table, $primary_index, $user_column, $blogid_to_import);        
        
        $leftoff_identifier = "3rdparty_{$table}_leftoff";
        $update_variable = "3rdparty_{$table}_updated";
        
        $progress_identifier = 'EDD API logs table';
        $last_processor = apply_filters('prime_mover_is_thirdparty_lastprocessor', false, $this, __FUNCTION__, $ret, $blogid_to_import);
        $handle_unique_constraint = '';
        
        return apply_filters('prime_mover_process_userid_adjustment_db', $ret, $table, $blogid_to_import, $leftoff_identifier, $primary_index, $column_strings,
            $update_variable, $progress_identifier, $start_time, $last_processor, $handle_unique_constraint);
    }   
 
    /**
     * Adjust user IDs in EDD logs.
     * Hooked to `prime_mover_do_process_thirdparty_data` filter, priority 514
     * @param array $ret
     * @param number $blogid_to_import
     * @param number $start_time
     * @return array
     */
    public function maybeAdjustEDDLogs($ret = [], $blogid_to_import = 0, $start_time = 0)
    {
        $validation_error = apply_filters('prime_mover_validate_thirdpartyuser_processing', $ret, $blogid_to_import, [$this->getEDDPlugin(),$this->getEDDProPlugin()]);
        if (is_array($validation_error)) {
            return $validation_error;
        }
        
        if (!empty($ret['3rdparty_current_function']) && __FUNCTION__ !== $ret['3rdparty_current_function']) {
            return $ret;
        }
        
        $ret['3rdparty_current_function'] = __FUNCTION__;
        $specs = $this->getSystemInitialization()->getSpecificationsFromIdentifier(sha1(__METHOD__));
        if (empty($specs)) {
            return $this->getSystemFunctions()->logSkippedDefaultUserAdj($ret, $blogid_to_import);
        }
        
        list($table, $primary_index, $user_column) = $specs;
        $column_strings = "{$primary_index}, {$user_column}";
        $this->getSystemFunctions()->maybeLogDefaultUserAdj(__METHOD__, $table, $primary_index, $user_column, $blogid_to_import);
        
        $leftoff_identifier = "3rdparty_{$table}_leftoff";
        $update_variable = "3rdparty_{$table}_updated";
        
        $progress_identifier = 'EDD logs table';
        $last_processor = apply_filters('prime_mover_is_thirdparty_lastprocessor', false, $this, __FUNCTION__, $ret, $blogid_to_import);
        $handle_unique_constraint = '';
        
        return apply_filters('prime_mover_process_userid_adjustment_db', $ret, $table, $blogid_to_import, $leftoff_identifier, $primary_index, $column_strings,
            $update_variable, $progress_identifier, $start_time, $last_processor, $handle_unique_constraint);
    } 
 
    /**
     * Adjust user IDs in EDD customer logs.
     * Hooked to `prime_mover_do_process_thirdparty_data` filter, priority 514
     * @param array $ret
     * @param number $blogid_to_import
     * @param number $start_time
     * @return array
     */
    public function maybeAdjustEDDCustomers($ret = [], $blogid_to_import = 0, $start_time = 0)
    {
        $validation_error = apply_filters('prime_mover_validate_thirdpartyuser_processing', $ret, $blogid_to_import, [$this->getEDDPlugin(),$this->getEDDProPlugin()]);
        if (is_array($validation_error)) {
            return $validation_error;
        }
        
        if (!empty($ret['3rdparty_current_function']) && __FUNCTION__ !== $ret['3rdparty_current_function']) {
            return $ret;
        }
        
        $ret['3rdparty_current_function'] = __FUNCTION__;
        $specs = $this->getSystemInitialization()->getSpecificationsFromIdentifier(sha1(__METHOD__));
        if (empty($specs)) {
            return $this->getSystemFunctions()->logSkippedDefaultUserAdj($ret, $blogid_to_import);
        }
        
        list($table, $primary_index, $user_column) = $specs;
        $column_strings = "{$primary_index}, {$user_column}";
        $this->getSystemFunctions()->maybeLogDefaultUserAdj(__METHOD__, $table, $primary_index, $user_column, $blogid_to_import);        
        
        $leftoff_identifier = "3rdparty_{$table}_leftoff";
        $update_variable = "3rdparty_{$table}_updated";
        
        $progress_identifier = 'EDD customers table';
        $last_processor = apply_filters('prime_mover_is_thirdparty_lastprocessor', false, $this, __FUNCTION__, $ret, $blogid_to_import);
        $handle_unique_constraint = '';
        
        return apply_filters('prime_mover_process_userid_adjustment_db', $ret, $table, $blogid_to_import, $leftoff_identifier, $primary_index, $column_strings,
            $update_variable, $progress_identifier, $start_time, $last_processor, $handle_unique_constraint);
    } 
    
    /**
     * Remove processor hooks when not activated
     * @param array $ret
     * @param number $blogid_to_import
     */
    public function removeProcessorHooksWhenDependencyNotMeet($ret = [], $blogid_to_import = 0)
    {
        $validation_error = apply_filters('prime_mover_validate_thirdpartyuser_processing', $ret, $blogid_to_import, [$this->getEDDPlugin(),$this->getEDDProPlugin()]);
        if (is_array($validation_error)) {
            foreach ($this->getCallBacks() as $callback => $priority) {
                remove_filter('prime_mover_do_process_thirdparty_data', [$this, $callback], $priority, 3);
            }
        }
    }    
}