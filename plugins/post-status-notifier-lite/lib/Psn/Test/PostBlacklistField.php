<?php
/**
 * Test for table field
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) ifeelweb.de
 * @version   $Id: PostBlacklistField.php 1850578 2018-03-31 19:27:09Z worschtebrot $
 * @package   
 */ 
class Psn_Test_PostBlacklistField implements IfwPsn_Wp_Plugin_Selftest_Interface
{
    private $_result = false;

    /**
     * @var Psn_Patch_Database
     */
    private $_dbPatcher;



    public function __construct()
    {
        $this->_dbPatcher = new Psn_Patch_Database();
    }

    /**
     * Gets the test name
     * @return mixed
     */
    public function getName()
    {
        return __('Post blacklist field', 'psn');
    }

    /**
     * Gets the test description
     * @return mixed
     */
    public function getDescription()
    {
        return __('Checks if the field exists in the database', 'psn');
    }

    /**
     * Runs the test
     * @param IfwPsn_Wp_Plugin_Manager $pm
     * @return mixed
     */
    public function execute(IfwPsn_Wp_Plugin_Manager $pm)
    {
        if ($this->_dbPatcher->isFieldPostBlacklist()) {
            $this->_result = true;
        }
    }

    /**
     * Gets the test result, true on success, false on failure
     * @return bool
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * Gets the error message
     * @return mixed
     */
    public function getErrorMessage()
    {
        return __('The database field could not be found', 'psn');
    }

    /**
     * @return bool
     */
    public function canHandle()
    {
        return true;
    }

    /**
     * Handles an error, should provide a solution for an unsuccessful test
     * @param IfwPsn_Wp_Plugin_Manager $pm
     * @return mixed
     */
    public function handleError(IfwPsn_Wp_Plugin_Manager $pm)
    {
        $this->_dbPatcher->createRulesFieldPostBlacklist();

        return __('Trying to create the field...', 'psn');
    }

}
