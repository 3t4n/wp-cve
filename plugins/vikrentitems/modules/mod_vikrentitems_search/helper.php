<?php
/**
 * @package     VikRentItems
 * @subpackage  mod_vikrentitems_search
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

class ModVikrentitemsSearchHelper
{
    public static function mgetHoursMinutes($secs)
	{
		if ($secs >= 3600) {
			$op = $secs / 3600;
			$hours = floor($op);
			$less = $hours * 3600;
			$newsec = $secs - $less;
			$optwo = $newsec / 60;
			$minutes = floor($optwo);
		} else {
			$hours = "0";
			$optwo = $secs / 60;
			$minutes = floor($optwo);
		}
		$x[] = $hours;
		$x[] = $minutes;
		return $x;
	}

	public static function formatLocationClosingDays($clostr)
	{
		$ret = array();
		$cur_time = time();
		$x = explode(",", $clostr);
		foreach($x as $y) {
			if (strlen(trim($y)) > 0) {
				$parts = explode("-", trim($y));
				$date_ts = mktime(0, 0, 0, (int)$parts[1], (int)str_replace(':w', '', $parts[2]), (int)$parts[0]);
				$date = date('Y-n-j', $date_ts);
				if (strlen($date) > 0 && $date_ts >= $cur_time) {
					$ret[] = '"'.$date.'"';
				}
				if (strpos($parts[2], ':w') !== false) {
					$info_ts = getdate($date_ts);
					$ret[] = '"'.$info_ts['wday'].'"';
				}
			}
		}
		return $ret;
	}
	
	public static function loadGlobalTimeSlots($vri_tn = null)
	{
		$dbo = JFactory::getDbo();
		$q = "SELECT * FROM `#__vikrentitems_timeslots` WHERE `global`='1' ORDER BY `#__vikrentitems_timeslots`.`tname` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$fetched = $dbo->loadAssocList();
			if (is_object($vri_tn)) {
				$vri_tn->translateContents($fetched, '#__vikrentitems_timeslots');
			}
			return $fetched;
		}
		return array();
	}
	
	public static function getForcedPickDropTimes($skipsession = false)
	{
		if ($skipsession) {
			$dbo = JFactory::getDbo();
			$retval = array(0 => '', 1 => '');
			$q = "SELECT `param`,`setting` FROM `#__vikrentitems_config` WHERE `param`='globpickupt' OR `param`='globdropofft';";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$s = $dbo->loadAssocList();
				foreach($s as $cf) {
					if ($cf['param'] == 'globpickupt') {
						if (!empty($cf['setting'])) {
							$parts = explode(':', $cf['setting']);
							$retval[0] = array(0 => $parts[0], 1 => $parts[1]);
						}
					} elseif ($cf['param'] == 'globdropofft') {
						if (!empty($cf['setting'])) {
							$parts = explode(':', $cf['setting']);
							$retval[1] = array(0 => $parts[0], 1 => $parts[1]);
						}
					}
				}
			}
			return $retval;
		} else {
			$session = JFactory::getSession();
			$sval = $session->get('getForcedPickDropTimes', '');
			if (is_array($sval) && count($sval) > 0) {
				return $sval;
			} else {
				$dbo = JFactory::getDbo();
				$retval = array(0 => '', 1 => '');
				$q = "SELECT `param`,`setting` FROM `#__vikrentitems_config` WHERE `param`='globpickupt' OR `param`='globdropofft';";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$s = $dbo->loadAssocList();
					foreach($s as $cf) {
						if ($cf['param'] == 'globpickupt') {
							if (!empty($cf['setting'])) {
								$parts = explode(':', $cf['setting']);
								$retval[0] = array(0 => $parts[0], 1 => $parts[1]);
							}
						} elseif ($cf['param'] == 'globdropofft') {
							if (!empty($cf['setting'])) {
								$parts = explode(':', $cf['setting']);
								$retval[1] = array(0 => $parts[0], 1 => $parts[1]);
							}
						}
					}
				}
				$session->set('getForcedPickDropTimes', $retval);
				return $retval;
			}
		}
	}

	public static function getDateFormat()
	{
		$session = JFactory::getSession();
		$sval = $session->get('getDateFormat', '');
		if (!empty($sval)) {
			return $sval;
		} else {
			$dbo = JFactory::getDbo();
			$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='dateformat';";
			$dbo->setQuery($q);
			$dbo->execute();
			$s = $dbo->loadAssocList();
			$session->set('getDateFormat', $s[0]['setting']);
			return $s[0]['setting'];
		}
	}
	
	public static function getTimeFormat()
	{
		$session = JFactory::getSession();
		$sval = $session->get('getTimeFormat', '');
		if (!empty($sval)) {
			return $sval;
		} else {
			$dbo = JFactory::getDbo();
			$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='timeformat';";
			$dbo->setQuery($q);
			$dbo->execute();
			$s = $dbo->loadAssocList();
			$session->set('getTimeFormat', $s[0]['setting']);
			return $s[0]['setting'];
		}
	}
	
	public static function getFirstWeekDay()
	{
		$session = JFactory::getSession();
		$sval = $session->get('vrifirstWeekDay', '');
		if (strlen($sval)) {
			return $sval;
		} else {
			$dbo = JFactory::getDbo();
			$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='firstwday';";
			$dbo->setQuery($q);
			$dbo->execute();
			$s = $dbo->loadAssocList();
			$session->set('vrifirstWeekDay', $s[0]['setting']);
			return $s[0]['setting'];
		}
	}

	public static function parseJsDrangeWdayCombo($drestr)
	{
		$combo = array();
		if (strlen($drestr['wday']) > 0 && strlen($drestr['wdaytwo']) > 0 && !empty($drestr['wdaycombo'])) {
			$cparts = explode(':', $drestr['wdaycombo']);
			foreach ($cparts as $kc => $cw) {
				if (!empty($cw)) {
					$nowcombo = explode('-', $cw);
					$combo[intval($nowcombo[0])][] = intval($nowcombo[1]);
				}
			}
		}
		return $combo;
	}

	public static function setDropDatePlus()
	{
		$dbo = JFactory::getDbo();
		$session = JFactory::getSession();
		$sval = $session->get('setDropDatePlus', '');
		if (!empty($sval)) {
			return $sval;
		} else {
			$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='setdropdplus';";
			$dbo->setQuery($q);
			$dbo->execute();
			$s = $dbo->loadAssocList();
			$session->set('setDropDatePlus', $s[0]['setting']);
			return $s[0]['setting'];
		}
	}
	
	public static function getMinDaysAdvance()
	{
		$dbo = JFactory::getDbo();
		$session = JFactory::getSession();
		$sval = $session->get('vriminDaysAdvance', '');
		if (!empty($sval)) {
			return (int)$sval;
		} else {
			$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='mindaysadvance';";
			$dbo->setQuery($q);
			$dbo->execute();
			$s = $dbo->loadAssocList();
			$session->set('vriminDaysAdvance', $s[0]['setting']);
			return (int)$s[0]['setting'];
		}
	}
	
	public static function getMaxDateFuture()
	{
		$dbo = JFactory::getDbo();
		$session = JFactory::getSession();
		$sval = $session->get('vrimaxDateFuture', '');
		if (!empty($sval)) {
			return $sval;
		} else {
			$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='maxdate';";
			$dbo->setQuery($q);
			$dbo->execute();
			$s = $dbo->loadAssocList();
			$session->set('vrimaxDateFuture', $s[0]['setting']);
			return $s[0]['setting'];
		}
	}

	public static function getGlobalClosingDays()
	{
		$dbo = JFactory::getDbo();
		$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='globalclosingdays';";
		$dbo->setQuery($q);
		$dbo->execute();
		$n = $dbo->loadAssocList();
		if (empty ($n[0]['setting'])) {
			return '';
		}
		$ret = array('singleday' => array(), 'weekly' => array());
		$parts = explode(';', $n[0]['setting']);
		foreach ($parts as $p) {
			if (!empty($p)) {
				$dateparts = explode(':', $p);
				if (count($dateparts) == 2) {
					if (intval($dateparts[1]) == 1) {
						$ret['singleday'][] = $dateparts[0];
					} else {
						$ret['weekly'][] = $dateparts[0];
					}
				}
			}
		}
		if (count($ret['singleday']) > 0) {
			$ret['singleday'] = array_unique($ret['singleday']);
		}
		if (count($ret['weekly']) > 0) {
			$ret['weekly'] = array_unique($ret['weekly']);
		}
		return $ret;
	}

	/**
	 * This method is compatible with both Joomla and WordPress.
	 * 
	 * @since 	1.7
	 */
	public static function importVriLib()
	{
		if (class_exists('VikRentItems')) {
			return;
		}
		if (defined('ABSPATH') && is_file(VRI_SITE_PATH . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'lib.vikrentitems.php')) {
			require_once(VRI_SITE_PATH . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'lib.vikrentitems.php');
		} elseif (defined('JPATH_SITE') && is_file(JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_vikrentitems' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'lib.vikrentitems.php')) {
			require_once(JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_vikrentitems' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'lib.vikrentitems.php');
		}
	}

	public static function getTranslator()
	{
		return VikRentItems::getTranslator();
	}

	public static function loadFontAwesome($force_load = false)
	{
		return VikRentItems::loadFontAwesome($force_load);
	}
	
}
