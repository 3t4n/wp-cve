<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    IfwPsn_Vendor_Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Interface.php 1312332 2015-12-19 13:29:57Z worschtebrot $
 */

/**
 * @category   Zend
 * @package    IfwPsn_Vendor_Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface IfwPsn_Vendor_Zend_View_Helper_Interface
{
    /**
     * Set the View object
     *
     * @param  IfwPsn_Vendor_Zend_View_Interface $view
     * @return IfwPsn_Vendor_Zend_View_Helper_Interface
     */
    public function setView(IfwPsn_Vendor_Zend_View_Interface $view);

    /**
     * Strategy pattern: helper method to invoke
     *
     * @return mixed
     */
    public function direct();
}
