<?php
/**
 * @copyright	Copyright (C) 2017. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Plugins CK - C�dric KEIFLIN - https://www.ceikay.com
 */
namespace Accordeonmenuck;

defined('CK_LOADED') or die;

class Helper {

	public static $default_settings;

	static $keepMessages = false;

	public static function getSettings() {
		if (empty(self::$default_settings)) {
			$default_settings = array(
				'only_related' => '0'
				,'depth' => '0'
				,'container_id' => ''
				,'menu_class' => 'menu'
				,'before' => ''
				,'after' => ''
				,'link_before' => ''
				,'link_after' => ''
				,'filter' => ''
				,'filter_selection' => ''
				,'include_parent' => ''
				,'post_parent' => ''
				,'description' => '0'
				,'start_depth' => ''
				,'hide_title' => ''
				,'parent_title' => ''
				,'style' => '0'
				,'transition' => 'linear'
				,'duration' => '500'
				,'eventtype' => 'click'
				,'showactive' => '1'
				,'showactivesubmenu' => '0'
			);
			self::$default_settings = $default_settings;
		}
		return self::$default_settings;
	}

	/**
	 * List the replacement between the tags and the real final CSS rules
	 */
	public static function getCssReplacement() {
		$cssreplacements = Array(
//			'[menu-bar]' => ' .accordeonmenuck-bar-title'
//			,'[menu-bar-button]' => ' .accordeonmenuck-bar-button'
//			,'[menu]' => '.accordeonmenuck'
//			,'[menu-topbar]' => ' .accordeonmenuck-title'
//			,'[menu-topbar-button]' => ' .accordeonmenuck-button'
//			,'[level1menuitem]' => ' .accordeonmenuck-item > .level1'
//			,'[level2menuitem]' => ' .accordeonmenuck-item > .level2'
//			,'[level3menuitem]' => ' .level2 + .accordeonmenuck-submenu .accordeonmenuck-item > div'
//			,'[togglericon]' => ' .accordeonmenuck-togglericon:after'
//			,'[PRESETS_URI]' => ACCORDEONMENUCK_MEDIA_URL . '/presets'
		);

		return $cssreplacements;
	}

		public static function copyright() {
		$html = array();
		$html[] = '<hr style="margin:10px 0;clear:both;" />';
		$html[] = '<div class="ckpoweredby"><a href="https://www.ceikay.com" target="_blank">https://www.ceikay.com</a></div>';
		$html[] = '<div class="ckproversioninfo"><div class="ckproversioninfo-title"><a href="' . ACCORDEONMENUCK_WEBSITE . '" target="_blank">' . __('Get the Pro version', 'cookies-ck') . '</a></div>
		<div class="ckproversioninfo-content">
			
<p>Animated effects to open submenus</p>
<p>Friendly design interface for styles customization</p>
<p>Direct preview of your design in the interface</p>
<p>Multiple use on the same page</p>
<p>Widget to use in your website anywhere</p>
<p>Description for each menu link</b></p>
<p>Unlimited levels</p>
<p>Custom transition effects and duration</p>
<p>Click or mouseover behavior</p>
<p>Custom +/- images</p>
<p>Styling options</p>
<p><b>20+ themes preinstalled</b></p>
<p>Import / Export of styles to save and share</p>
<p>Custom CSS option</p>
<div class="ckproversioninfo-button"><a href="' . ACCORDEONMENUCK_WEBSITE . '" target="_blank">' . __('Get the Pro version', 'cookies-ck') . '</a></div>
		</div>';
		
		return implode($html);
	}

	static function getProMessage() {
		return '<a href="' . ACCORDEONMENUCK_WEBSITE . '" target="_blank">' . CKText::_('Only available in the Pro version') . '</a>';
	}
}
