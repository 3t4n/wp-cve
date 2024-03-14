<?php

namespace ZPOS\Admin\Tabs;

use ZPOS\Admin\Setting\Box;
use ZPOS\Admin\Setting\Input\Input;
use ZPOS\Admin\Setting\Input\Media;
use ZPOS\Admin\Setting\Input\DropdownSelect;
use ZPOS\Admin\Setting\PageTab;
use ZPOS\Deactivate;

class General extends PageTab
{
	public $exact = true;
	public $name = 'General';
	public $path = '/general';

	public function getBoxes()
	{
		return [
			new Box(
				null,
				null,
				new Media(__('Loading Screen Logo', 'zpos-wp-api'), 'pos_logo', [$this, 'media_get_value'])
			),
		];
	}

	public function init()
	{
		register_setting('pos' . $this->path, 'pos_logo');
	}

	public function media_get_value()
	{
		$id = get_option('pos_logo');
		$src = null;
		if ($id) {
			$src_data = wp_get_attachment_image_src($id, 'full');
			$src = $src_data[0];
		}

		return compact('id', 'src');
	}

	public static function reset()
	{
		if (!did_action(Deactivate::class . '::resetSettings')) {
			return _doing_it_wrong(
				__METHOD__,
				'Reset POS settings should called by ' . Deactivate::class . '::resetSettings',
				'2.0.3'
			);
		}

		delete_option('pos_logo');
	}
}
