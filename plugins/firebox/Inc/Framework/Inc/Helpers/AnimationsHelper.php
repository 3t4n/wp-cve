<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class AnimationsHelper {

	/**
	 * Returns all In Transitions
	 * 
	 * @return  array
	 */
	public static function getTransitionsIn()
	{
		return [
			'callout.bounce' => 'Bounce',
			'callout.shake' => 'Shake',
			'callout.flash' => 'Flash',
			'callout.pulse' => 'Pulse',
			'callout.swing' => 'Swing',
			'callout.tada' => 'Tada',
			'transition.fadeIn' => 'fadeIn',
			'transition.swoopIn' => 'swoopIn',
			'transition.whirlIn' => 'whirlIn',
			'transition.shrinkIn' => 'shrinkIn',
			'transition.expandIn' => 'expandIn',
			'transition.flipXIn' => 'flipXIn',
			'transition.flipYIn' => 'flipYIn',
			'transition.flipBounceXIn' => 'flipBounceXIn',
			'transition.flipBounceYIn' => 'flipBounceYIn',
			'transition.bounceIn' => 'bounceIn',
			'transition.bounceUpIn' => 'bounceUpIn',
			'transition.bounceDownIn' => 'bounceDownIn',
			'transition.bounceLeftIn' => 'bounceLeftIn',
			'transition.bounceRightIn' => 'bounceRightIn',
			'slideDown' => 'slideIn',
			'firebox.slideUpIn' => 'slideUpIn',
			'firebox.slideDownIn' => 'slideDownIn',
			'firebox.slideLeftIn' => 'slideLeftIn',
			'firebox.slideRightIn' => 'slideRightIn',
			'transition.slideUpIn' => 'slideFadeUpIn',
			'transition.slideDownIn' => 'slideFadeDownIn',
			'transition.slideLeftIn' => 'slideFadeLeftIn',
			'transition.slideRightIn' => 'slideFadeRightIn',
			'transition.slideUpBigIn' => 'slideUpBigIn',
			'transition.slideDownBigIn' => 'slideDownBigIn',
			'transition.slideLeftBigIn' => 'slideLeftBigIn',
			'transition.slideRightBigIn' => 'slideRightBigIn',
			'transition.perspectiveUpIn' => 'perspectiveUpIn',
			'transition.perspectiveDownIn' => 'perspectiveDownIn',
			'transition.perspectiveLeftIn' => 'perspectiveLeftIn',
			'transition.perspectiveRightIn' => 'perspectiveRightIn'
		];
	}

	/**
	 * Returns all Out Transitions
	 * 
	 * @return  array
	 */
	public static function getTransitionsOut()
	{
		return [
			'transition.fadeOut' => 'fadeOut',
			'transition.swoopOut' => 'swoopOut',
			'transition.whirlOut' => 'whirlOut',
			'transition.shrinkOut' => 'shrinkOut',
			'transition.expandOut' => 'expandOut',
			'transition.flipXOut' => 'flipXOut',
			'transition.flipYOut' => 'flipYOut',
			'transition.flipBounceXOut' => 'flipBounceXOut',
			'transition.flipBounceYOut' => 'flipBounceYOut',
			'transition.bounceOut' => 'bounceOut',
			'transition.bounceUpOut' => 'bounceUpOut',
			'transition.bounceDownOut' => 'bounceDownOut',
			'transition.bounceLeftOut' => 'bounceLeftOut',
			'transition.bounceRightOut' => 'bounceRightOut',
			'slideUp' => 'slideOut',
			'firebox.slideUpOut' => 'slideUpOut',
			'firebox.slideDownOut' => 'slideDownOut',
			'firebox.slideLeftOut' => 'slideLeftOut',
			'firebox.slideRightOut' => 'slideRightOut',               
			'transition.slideUpOut' => 'slideFadeUpOut',
			'transition.slideDownOut' => 'slideFadeDownOut',
			'transition.slideLeftOut' => 'slideFadeLeftOut',
			'transition.slideRightOut' => 'slideFadeRightOut',
			'transition.slideUpBigOut' => 'slideUpBigOut',
			'transition.slideDownBigOut' => 'slideDownBigOut',
			'transition.slideLeftBigOut' => 'slideLeftBigOut',
			'transition.slideRightBigOut' => 'slideRightBigOut',
			'transition.perspectiveUpOut' => 'perspectiveUpOut',
			'transition.perspectiveDownOut' => 'perspectiveDownOut',
			'transition.perspectiveLeftOut' => 'perspectiveLeftOut',
			'transition.perspectiveRightOut' => 'perspectiveRightOut',
		];
	}

}