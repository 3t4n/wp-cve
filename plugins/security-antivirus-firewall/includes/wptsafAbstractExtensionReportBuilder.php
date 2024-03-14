<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class wptsafAbstractExtensionReportBuilder
{
	/**
	 * @var wptsafAbstractExtension
	 */
	protected $extension;

	/**
	 * @constructor
	 * @param wptsafAbstractExtension $extension
	 */
	public function __construct(wptsafAbstractExtension $extension)
	{
		$this->extension = $extension;
	}

	/**
	 * @param int $dateFrom
	 * @param int $dateTo
	 * @return string
	 */
	abstract public function makeReport($dateFrom, $dateTo);
}
