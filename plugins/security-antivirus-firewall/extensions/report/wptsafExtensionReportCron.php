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

class wptsafExtensionReportCron extends wptsafExtensionCron{

	protected $hookNameSendReport;


	public function __construct(wptsafAbstractExtension $extension){
		parent::__construct($extension);

		$this->hookNameSendReport = $this->generateHookName('send_report');
		add_action($this->hookNameSendReport, array($this, 'sendReport'));
		//testing report
		//add_action('init', array($this, 'sendReport')); 
	}


	function initSchedule(){
		parent::initSchedule();

		$settings = $this->extension->getSettings();
		$this->addScheduleSingleEvent(
			$this->hookNameSendReport,
			$settings->get('frequency_running'),
			$settings->get('time_running/h'),
			$settings->get('time_running/m')
		);
	}


	public function clearSchedule(){
		parent::clearSchedule();
		wp_clear_scheduled_hook($this->hookNameSendReport);
	}


	public function sendReport(){
		// set next schedule event
		$settings = $this->extension->getSettings();
		$this->addScheduleSingleEvent(
			$this->hookNameSendReport,
			$settings->get('frequency_running'),
			$settings->get('time_running/h'),
			$settings->get('time_running/m')
		);

		$dateFrom = new DateTime('now', new DateTimeZone('UTC'));
		$dateTimestampTo = wptsafEnv::getInstance()->getDateGmt();
		$recurrence = $settings->get('frequency_running');
		$frequencyType = $this->getFrequencyType($recurrence);
		$dateInterval = $this->recurrenceToDateInterval($recurrence);

		switch ($frequencyType) {
			case 'W':
				$dateFrom->modify('Monday this week');
				break;
			case 'M':
				$dateFrom->modify('first day of this month');
				break;
		}
		$dateFrom->setTime(0, 0, 0);
		if ($dateInterval) {
			$dateFrom->sub($dateInterval);
		}

		$reportBuilder = $this->extension->createReportBuilder();
		$report = $reportBuilder->makeReport($dateFrom->getTimestamp(), $dateTimestampTo);
		$recipients = wptsafSecurity::getInstance()->getSettings()->get('notification_emails');
		$blogName = get_bloginfo('name');
		foreach ($recipients as $recipient) {
			wp_mail(
				$recipient,
				sprintf('%s: %s', $blogName, $settings->get('letter_title')),
				$report
			);
		}
	}
}
