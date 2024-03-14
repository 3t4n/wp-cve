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

class wptsafExtensionReportReportBuilder extends wptsafAbstractExtensionReportBuilder{

	public function makeReport($dateFrom, $dateTo){
		$extensions = wptsafSecurity::getInstance()->getExtensions();
		$extensionReportName = $this->extension->getName();
		$extensionNetworkMonitorName = wptsafExtensionNetworkMonitor::getInstance()->getName();
		$view = new wptsafView();
		$reports = array();
		$wherePeriod = array(
			array('date_gmt', '>=', $dateFrom),
			array('date_gmt', '<=', $dateTo)
		);
		$rowsAmount = 0;
		$rowsAmountForPeriod = 0;

		foreach ($extensions as $extension) {
			$extensionName = $extension->getName();

			if ($extension->getName() == $extensionReportName) {
				continue;
			}
			if (!$reportBuilder = $extension->createReportBuilder()) {
				continue;
			}

			$reports[] = $reportBuilder->makeReport($dateFrom, $dateTo);

			if ($log = $extension->getLog()) {
				$rowsAmount += $log->getRowsAmount();
				$rowsAmountForPeriod += $log->getRowsAmount($wherePeriod);
			}
			if ($extensionName == $extensionNetworkMonitorName) {
				/** @var wptsafExtensionNetworkMonitor $extension */
				$managerIp = $extension->getManagerIp();
				$rowsAmount += $managerIp->getRowsAmount();
				$rowsAmountForPeriod += $managerIp->getRowsAmount($wherePeriod);
			}
		}

		return $view->content(
			$this->extension->getExtensionDir() . 'template/report.php',
			array(
				'dateFrom' => date(WPTSAF_DATE_FORMAT_REPORT, $dateFrom),
				'dateTo' => date(WPTSAF_DATE_FORMAT_REPORT, $dateTo),
				'rowsAmount' => $rowsAmount,
				'rowsAmountForPeriod' => $rowsAmountForPeriod,
				'reports' => implode("\n\n\n", $reports)
			)
		);
	}
}
