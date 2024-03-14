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

class wptsafExtensionNetworkMonitorReportBuilder extends wptsafAbstractExtensionReportBuilder{

	public function makeReport($dateFrom, $dateTo){
		$view = new wptsafView();
		$log = $this->extension->getLog();
		$managerLog = $this->extension->getManagerIp();
		$wherePeriod = array(
			array('date_gmt', '>=', $dateFrom),
			array('date_gmt', '<=', $dateTo)
		);
		$report = $view->content(
			$this->extension->getExtensionDir() . 'template/report.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
				'dateFrom' => date(WPTSAF_DATE_FORMAT_REPORT, $dateFrom),
				'dateTo' => date(WPTSAF_DATE_FORMAT_REPORT, $dateTo),
				'logRowsAmount' => $log->getRowsAmount(),
				'logRowsAmountForPeriod' => $log->getRowsAmount($wherePeriod),
				'logRows' => $log->getRows(10, 0, 'DESC', 'id', $wherePeriod),
				'managerRowsAmount' => $managerLog->getRowsAmount(),
				'managerRowsAmountForPeriod' => $managerLog->getRowsAmount($wherePeriod),
				'managerRows' => $managerLog->getRows(10, 0, 'DESC', 'id', $wherePeriod)
			)
		);

		return $report;
	}
}
