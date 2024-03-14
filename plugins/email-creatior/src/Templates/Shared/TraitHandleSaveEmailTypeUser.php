<?php

namespace WilokeEmailCreator\Templates\Shared;

use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;

trait TraitHandleSaveEmailTypeUser
{
	private string $key = 'user_email_type';

	public function getListEmailTypeUser(): array
	{
		return json_decode(get_option(AutoPrefix::namePrefix($this->key)) ?? [], true) ?: [];
	}

	/**
	 * @param array $aEmailType {
	 *      'id' => {
	 *          'label' => '',
	 *          'value' => '',
	 *          'type' => '',
	 * }
	 * }
	 * @return void
	 */
	public function updateListEmailTypeUser(array $aEmailType)
	{
		$aData = $this->getListEmailTypeUser();
		foreach ($aEmailType as $key => $value) {
			if (array_key_exists($key, $aData)) {
				unset($aData[$key]);
			}
			$aData[$key] = $value;
			update_option(AutoPrefix::namePrefix($this->key), json_encode($aData));
		}
	}

	public function deleteListEmailTypeUserAfterDeletePost(array $aEmailType)
	{
		$aData = $this->getListEmailTypeUser();
		foreach ($aEmailType as $key => $value) {
			if (array_key_exists($key, $aData)) {
				unset($aData[$key]);
				update_option(AutoPrefix::namePrefix($this->key), json_encode($aData));
			}
		}
	}

}
