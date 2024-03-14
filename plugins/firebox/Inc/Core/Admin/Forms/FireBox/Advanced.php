<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Admin\Forms\FireBox;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Advanced
{
	/**
	 * Holds the Advanced Settings
	 * 
	 * @return  array
	 */
	public function getSettings()
	{
		$settings = [
			'title' => 'FPF_ADVANCED',
			'content' => [
				'advanced_custom' => [
					'title' => [
						'title' => 'FPF_CUSTOM_CODE',
						'description' => 'FPF_CUSTOM_CODE_DESC'
					],
					'fields' => [
						[
							'name' => 'customcode',
							'type' => 'Textarea',
							'label' => 'FPF_CUSTOM_JAVASCRIPT',
							'description' => 'FPF_CUSTOM_JAVASCRIPT_DESC',
							'input_class' => ['fullwidth'],
							'rows' => 10,
							'filter' => 'raw',
							'mode' => 'javascript'
						],
						[
							'name' => 'customcss',
							'type' => 'Textarea',
							'label' => 'FPF_CUSTOM_CSS',
							'description' => 'FPF_CUSTOM_CSS_DESC',
							'input_class' => ['fullwidth'],
							'rows' => 10,
							'filter' => 'css',
							'mode' => 'css'
						]
					]
				],
				'advanced_misc' => [
					'title' => [
						'title' => 'FPF_MISC',
						'description' => 'FPF_MISC_SETTINGS_DESC'
					],
					'fields' => [
						[
							'name' => 'testmode',
							'type' => 'FPToggle',
							'label' => firebox()->_('FB_METABOX_TEST_MODE'),
							'description' => firebox()->_('FB_METABOX_TEST_MODE_DESC')
						],
						[
							'name' => 'rtl',
							'type' => 'FPToggle',
							'label' => firebox()->_('FB_METABOX_ADV_ENABLE_RTL'),
							'description' => firebox()->_('FB_METABOX_ADV_ENABLE_RTL_DESC'),
						],
						[
							'name' => 'preventpagescroll',
							'type' => 'FPToggle',
							'label' => firebox()->_('FB_METABOX_ADV_PREVENT_PAGE_SCROLLING'),
							'description' => firebox()->_('FB_METABOX_ADV_PREVENT_PAGE_SCROLLING_DESC'),
						],
						[
							'name' => 'stats',
							'type' => 'Toggle',
							'label' => firebox()->_('FB_METABOX_ADV_STATISTICS'),
							'description' => firebox()->_('FB_SETTINGS_ANALYTICS_DESC'),
							'default' => 2,
							'choices' => [
								'2' => 'FPF_USE_GLOBAL',
								'1' => 'FPF_YES',
								'0' => 'FPF_NO'
							]
						],
						[
							'name' => 'classsuffix',
							'type' => 'Text',
							'label' => 'FPF_CLASS_SUFFIX',
							'description' => 'FPF_CLASS_SUFFIX_DESC',
							'input_class' => ['medium']
						],
						[
							'name' => 'zindex',
							'type' => 'Number',
							'label' => firebox()->_('FB_METABOX_ADV_ZINDEX'),
							'description' => firebox()->_('FB_METABOX_ADV_ZINDEX_DESC'),
							'input_class' => ['small'],
							'placeholder' => '99999',
							'min' => 1000,
							'step' => 1000
						],
					]
				]
			]
		];

		return apply_filters('firebox/box/settings/advanced/edit', $settings);
	}
}