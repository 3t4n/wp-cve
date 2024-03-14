<?php
/*********************************************************************/
/* PROGRAM    (C) 2022 FlexRC                                        */
/* PROPERTY   604-1097 View St                                        */
/* OF         Victoria, BC, V8V 0G9                                   */
/*            CANADA                                                 */
/*            Voice (604) 800-7879                                   */
/*********************************************************************/

namespace OneTeamSoftware\WooCommerce\Logger;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\LoggerInstance')):

class LoggerInstance
{
	private static $instances = array();

	public static function &getInstance($id)
	{
		if (empty(self::$instances[$id])) {
			self::$instances[$id] = new Logger($id);
		}

		return self::$instances[$id];
	}
}

endif;