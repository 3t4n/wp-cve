<?php


namespace WilokeEmailCreator\Templates\Services\Post;


use Exception;



use WilokeEmailCreator\Shared\Post\TraitMaybeAssertion;
use WilokeEmailCreator\Shared\Post\TraitMaybeSanitizeCallback;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WilokeEmailCreator\Shared\Post\IService;

class CreatePostService extends PostService implements IService
{
	use TraitDefinePostFields;
	use TraitMaybeAssertion;
	use TraitMaybeSanitizeCallback;


	/**
	 * @return array
	 */
	public function performSaveData(): array
	{
		try {
			$this->validateFields();
			$aData = $this->aData;
			unset($aData['ID']);
			$id = wp_insert_post($aData);
			if (is_wp_error($id)) {
				return MessageFactory::factory()->error($id->get_error_message(), $id->get_error_code());
			}

			return MessageFactory::factory()->success(
				esc_html__('Congrats! The item has created successfully', 'emailcreator'),
				[
					'id' => $id
				]
			);
		}
		catch (Exception $oException) {
			return MessageFactory::factory()->error($oException->getMessage(), $oException->getCode());
		}
	}

	/**
	 * @throws Exception
	 */
	public function validateFields(): IService
	{
		foreach ($this->defineFields() as $friendlyKey => $aField) {
			if (isset($aField['isReadOnly'])) {
				$this->aData[$aField['key']] = $aField['value'];
			} else {
				$value = '';
				if (isset($this->aRawData[$friendlyKey])) {
					$value = $this->aRawData[$friendlyKey];
				} else {
					if (isset($aField['value'])) {
						$value = $aField['value'];
					}
				}
				$aAssertionResponse = $this->maybeAssert($aField, $value);
				if ($aAssertionResponse['status'] === 'error') {
					throw new Exception($aAssertionResponse['message']);
				}
				$this->aData[$aField['key']] = $this->maybeSanitizeCallback($aField, $value);
			}
		}
		return $this;
	}

	public function setRawData(array $aRawData): IService
	{
		$this->aRawData = $aRawData;
		return $this;
	}
}
