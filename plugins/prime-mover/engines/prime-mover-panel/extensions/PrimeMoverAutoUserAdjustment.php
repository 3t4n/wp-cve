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
 * Prime Mover AutoUser Adjustment Compatibility Class
 * Helper class for interacting with third party plugins having custom user ID columns
 *
 */
class PrimeMoverAutoUserAdjustment
{     
    
    private $prime_mover;
    
    /**
     * Construct
     * @param PrimeMover $prime_mover
     * @param array $utilities
     */
    public function __construct(PrimeMover $prime_mover, $utilities = [])
    {
        $this->prime_mover = $prime_mover;
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
     * Get system authorization
     * @return \Codexonics\PrimeMoverFramework\classes\PrimeMoverSystemAuthorization
     */
    public function getSystemAuthorization()
    {
        return $this->getPrimeMover()->getSystemAuthorization();
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
     * Initialize hooks
     */
    public function initHooks()
    {      
        add_filter('prime_mover_filter_export_footprint', [$this, 'maybeAddAutoUserAdjustMentExportFootPrint'], 500, 3);       
        add_action('prime_mover_before_thirdparty_data_processing', [$this, 'maybeAddAutoUserAdjustmentHooks'], 0, 2);        
    }   
    
    /**
     * Maybe add auto user adjustment hooks
     * @param array $ret
     * @param number $blogid_to_import
     */
    public function maybeAddAutoUserAdjustmentHooks($ret = [], $blogid_to_import = 0)
    {
        if (!$this->getSystemAuthorization()->isUserAuthorized()) {
            return;
        }

        if (empty($ret['imported_package_footprint']['auto_user_adjustment'])) {
            return;
        }        
        
        $auto_user_adj = $ret['imported_package_footprint']['auto_user_adjustment'];
        $i = 100000;    
        $defaults = $this->getHashOfDefaultUserAdjustments();
        
        foreach ($auto_user_adj as $v) {
            if (!is_array($v)) {
                continue;
            }
            
            list($table, $primary_index, $column) = $this->getRequiredParametersToHook($v);
            if (!$table || !$primary_index || !$column) {
                continue;
            }                       
            
            $func_signature = $this->generateFunctionSignature($table, $primary_index, $column);               
            if (in_array($func_signature, $defaults)) {
                continue;
            }
            
            add_filter('prime_mover_do_process_thirdparty_data', function($ret = [], $blogid_to_import = 0, $start_time = 0) use ($i, $table, $column, $primary_index, $func_signature) {                
                if ($this->userDoesNotNeedAdjustment($ret, $blogid_to_import)) {
                    return $ret;
                }               
                
                if (!empty($ret['3rdparty_current_function']) && $func_signature !== $ret['3rdparty_current_function']) {
                    return $ret;
                }
                
                $ret['3rdparty_current_function'] = $func_signature;                
                $leftoff_identifier = "3rdparty_{$table}_leftoff";                
                $column_strings = "{$primary_index}, {$column}";
                
                $update_variable = "3rdparty_{$table}_updated";                
                $progress_identifier = "{$table} table";
                $auto_user_adj_args = [
                    'table' => $table,
                    'primary_index' => $primary_index,
                    'column' => $column
                    ];
                
                $last_processor = apply_filters('prime_mover_is_thirdparty_lastprocessor', false, $this,  $func_signature, $ret, $blogid_to_import, $auto_user_adj_args);
                $handle_unique_constraint = '';
                   
                return apply_filters('prime_mover_process_userid_adjustment_db', $ret, $table, $blogid_to_import, $leftoff_identifier, $primary_index, $column_strings,
                    $update_variable, $progress_identifier, $start_time, $last_processor, $handle_unique_constraint);
                
            }, $i, 3);
                
            $i++;
        }        
    }

    /**
     * Get required parameters to hook
     * @param array $v
     * @return string[]
     */
    protected function getRequiredParametersToHook($v = [])
    {
        $table = '';
        $primary_key = '';
        $column = '';
        
        foreach($v as $k => $params) {
            $table = $k;
            if (!is_array($params)) {
                continue;
            }
            if (isset($params['primary'])) {
                $primary_key = $params['primary'];
            }
            if (isset($params['column'])) {
                $column = $params['column'];
            }
        }
        
        return [$table, $primary_key, $column];
    }
    
    /**
     * Generate unique identifiable function signature
     * @param string $table
     * @param string $primary_key
     * @param string $col
     * @return string
     */
    protected function generateFunctionSignature($table = '', $primary_key = '', $col = '')
    {
        $string = $table . $primary_key . $col;        
        $hash_algo = $this->getSystemInitialization()->getFastHashingAlgo();
        
        return hash($hash_algo, $string);
    }
    
    /**
     * Checks if user does not need adjustment
     * @param array $ret
     * @param number $blogid_to_import
     * @return boolean
     */
    protected function userDoesNotNeedAdjustment($ret = [], $blogid_to_import = 0)
    {
        if (!$this->getSystemAuthorization()->isUserAuthorized()) {
            return true;
        }
        
        if (!isset($ret['user_equivalence']) || !$blogid_to_import) {
            return true;
        }
        
        $mismatch_count = 0;
        if (isset($ret['user_mismatch_count'])) {
            $mismatch_count = $ret['user_mismatch_count'];
        }
        
        if (!$mismatch_count) {
            do_action('prime_mover_log_processed_events', "User equivalence check enabled - but post mismatch count is zero, skipping third party processing user update.", $blogid_to_import, 'import', __FUNCTION__, $this);
            return true;
        }
        
        return false;
    }
    
    /**
     * Maybe add automatic user adjustment to footprint config.
     * @param array $export_system_footprint
     * @param array $ret
     * @param number $blogid_to_export
     * @return array
     */
    public function maybeAddAutoUserAdjustMentExportFootPrint($export_system_footprint = [], $ret = [], $blogid_to_export = 0)
    {
        if (!$this->getSystemAuthorization()->isUserAuthorized()) {
            return $export_system_footprint;
        }
        
        if (!is_array($export_system_footprint) || !is_array($ret)) {
            return $export_system_footprint;
        }
        
        if (!isset($ret['autouser_id_adjust'])) {
            return $export_system_footprint;
        }
        
        if (!is_array($ret['autouser_id_adjust']) || empty($ret['autouser_id_adjust'])) {
            return $export_system_footprint;
        }
        
        $export_system_footprint['auto_user_adjustment'] = $ret['autouser_id_adjust'];          
        return $export_system_footprint;
    }
    
    /**
     * Get hash of default user adjustments
     * @return array
     */
    protected function getHashOfDefaultUserAdjustments()
    {
        $default = primeMoverDefaultUserAdjustments();
        $hashed = array_map([$this, 'implodeValues'], array_values($default));
        
        return array_unique($hashed);        
    }
    
    /**
     * Implode and hash values
     * @param array $v
     * @return string
     */
    protected function implodeValues($v = [])
    {
        $hash_algo = $this->getSystemInitialization()->getFastHashingAlgo();        
        return hash($hash_algo, implode($v));
    }    
}
