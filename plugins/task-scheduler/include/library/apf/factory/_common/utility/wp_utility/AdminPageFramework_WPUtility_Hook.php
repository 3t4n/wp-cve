<?php 
/**
	Admin Page Framework v3.9.0b10 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/task-scheduler>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class TaskScheduler_AdminPageFramework_WPUtility_Hook extends TaskScheduler_AdminPageFramework_WPUtility_Page {
    static public function registerAction($sActionHook, $oCallable, $iPriority = 10) {
        if (did_action($sActionHook)) {
            return call_user_func_array($oCallable, array());
        }
        add_action($sActionHook, $oCallable, $iPriority);
    }
    static public function doActions($aActionHooks, $vArgs1 = null, $vArgs2 = null, $_and_more = null) {
        $aArgs = func_get_args();
        $aActionHooks = $aArgs[0];
        foreach (( array )$aActionHooks as $sActionHook) {
            $aArgs[0] = $sActionHook;
            call_user_func_array('do_action', $aArgs);
        }
    }
    static public function addAndDoActions() {
        $aArgs = func_get_args();
        $oCallerObject = $aArgs[0];
        $aActionHooks = $aArgs[1];
        foreach (( array )$aActionHooks as $sActionHook) {
            if (!$sActionHook) {
                continue;
            }
            $aArgs[1] = $sActionHook;
            call_user_func_array(array(get_class(), 'addAndDoAction'), $aArgs);
        }
    }
    static public function addAndDoAction() {
        $_iArgs = func_num_args();
        $_aArgs = func_get_args();
        $_oCallerObject = $_aArgs[0];
        $_sActionHook = $_aArgs[1];
        if (!$_sActionHook) {
            return;
        }
        $_sAutoCallbackMethodName = str_replace('\\', '_', $_sActionHook);
        if (method_exists($_oCallerObject, $_sAutoCallbackMethodName)) {
            add_action($_sActionHook, array($_oCallerObject, $_sAutoCallbackMethodName), 10, $_iArgs - 2);
        }
        array_shift($_aArgs);
        call_user_func_array('do_action', $_aArgs);
    }
    static public function addAndApplyFilters() {
        $_aArgs = func_get_args();
        $_aFilters = $_aArgs[1];
        $_vInput = $_aArgs[2];
        foreach (( array )$_aFilters as $_sFilter) {
            if (!$_sFilter) {
                continue;
            }
            $_aArgs[1] = $_sFilter;
            $_aArgs[2] = $_vInput;
            $_vInput = call_user_func_array(array(get_class(), 'addAndApplyFilter'), $_aArgs);
        }
        return $_vInput;
    }
    static public function addAndApplyFilter() {
        $_iArgs = func_num_args();
        $_aArgs = func_get_args();
        $_oCallerObject = $_aArgs[0];
        $_sFilter = $_aArgs[1];
        if (!$_sFilter) {
            return $_aArgs[2];
        }
        $_sAutoCallbackMethodName = str_replace('\\', '_', $_sFilter);
        if (method_exists($_oCallerObject, $_sAutoCallbackMethodName)) {
            add_filter($_sFilter, array($_oCallerObject, $_sAutoCallbackMethodName), 10, $_iArgs - 2);
        }
        array_shift($_aArgs);
        return call_user_func_array('apply_filters', $_aArgs);
    }
    static public function getFilterArrayByPrefix($sPrefix, $sClassName, $sPageSlug, $sTabSlug, $bReverse = false) {
        $_aFilters = array();
        if ($sTabSlug && $sPageSlug) {
            $_aFilters[] = "{$sPrefix}{$sPageSlug}_{$sTabSlug}";
        }
        if ($sPageSlug) {
            $_aFilters[] = "{$sPrefix}{$sPageSlug}";
        }
        if ($sClassName) {
            $_aFilters[] = "{$sPrefix}{$sClassName}";
        }
        return $bReverse ? array_reverse($_aFilters) : $_aFilters;
    }
    }
    