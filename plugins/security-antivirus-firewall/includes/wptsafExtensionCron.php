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

class wptsafExtensionCron{
	const TASK_PREFIX = 'wptsaf_security_';

	protected $extension;
	protected $hookNameLogRotation;

	public function __construct(wptsafAbstractExtension $extension){
		$this->extension = $extension;
		$this->hookNameLogRotation = $this->generateHookName('log_rotation');
		add_action($this->hookNameLogRotation, array($this, 'logRotation'));
	}

	protected function generateHookName($name){
		return self::TASK_PREFIX . $this->extension->getName() . '_cron_' . $name;
	}

	public function initSchedule(){
		$logRotationFrequency = $this->extension->getLogRotationFrequency();
		$this->addScheduleSingleEvent(
			$this->hookNameLogRotation,
			$logRotationFrequency,
			wptsafSecurity::getInstance()->getSettings()->get('log_rotation_time/h'),
			wptsafSecurity::getInstance()->getSettings()->get('log_rotation_time/m')
		);
	}

	public function clearSchedule(){
		wp_clear_scheduled_hook($this->hookNameLogRotation);
	}

	protected function addScheduleSingleEvent($hook, $recurrence, $hour = null, $min = null){
		if (!$this->extension->isEnabled()) {
			return;
		}
		if (0 == $recurrence) {
			return;
		}

		$frequencyType = $this->getFrequencyType($recurrence);
		$date = new DateTime('now', new DateTimeZone('UTC'));
		$hour = null === $hour ? $date->format('H') : $hour;
		$min = null === $min ? $date->format('i') : $min;
		$dateInterval = $this->recurrenceToDateInterval($recurrence);

		switch ($frequencyType) {
			case 'W':
				$date->modify('monday this week');
				break;
			case 'M':
				$date->modify('first day of this month');
				break;
		}

		$date->setTime($hour, $min, 0);
		if ($dateInterval) {
			$date->add($dateInterval);
		}

		wp_clear_scheduled_hook($hook);
		wp_schedule_single_event($date->getTimestamp(), $hook);
	}

	protected function getFrequencyType($recurrence){
		preg_match('/^[0-9]+(\S+)$/', $recurrence, $frequencyRunning);
		return isset($frequencyRunning[1]) ? $frequencyRunning[1] : null;
	}


	protected function recurrenceToDateInterval($recurrence){
		$frequencyType = $this->getFrequencyType($recurrence);
		$dateInterval = null;
		switch ($frequencyType) {
			case 'SEC':
				$formattedRecurrence = str_replace('SEC', 'S', $recurrence);
				$dateInterval = new DateInterval("PT{$formattedRecurrence}");
				break;
			case 'MIN':
				$formattedRecurrence = str_replace('MIN', 'M', $recurrence);
				$dateInterval = new DateInterval("PT{$formattedRecurrence}");
				break;
			case 'H':
				$dateInterval = new DateInterval("PT{$recurrence}");
				break;
			case 'D':
				$dateInterval = new DateInterval("P{$recurrence}");
				break;
			case 'W':
				$dateInterval = new DateInterval("P{$recurrence}");
				break;
			case 'M':
				$dateInterval = new DateInterval("P{$recurrence}");
				break;
			default:
				wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
					$this->extension,
					sprintf(__('Wrong frequency type "%s"', 'security-antivirus-firewall'), $frequencyType)
				);
		}

		return $dateInterval;
	}

	public function logRotation(){
		// set next schedule event
		$this->addScheduleSingleEvent(
			$this->hookNameLogRotation,
			$this->extension->getLogRotationFrequency(),
			null,
			wptsafSecurity::getInstance()->getSettings()->get('log_rotation_time/m')
		);

		if ($log = $this->extension->getLog()) {
			$log->rotate();
		}
	}
}
