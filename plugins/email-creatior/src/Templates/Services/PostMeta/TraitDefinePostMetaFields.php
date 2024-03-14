<?php


namespace WilokeEmailCreator\Templates\Services\PostMeta;


trait TraitDefinePostMetaFields
{
	protected array $aFields = [];

	public function defineFields(): array
	{
		$this->aFields = [
			'sections'      => [
				'key' => 'sections'
			],
			'html_template' => [
				'key' => 'html_template'
			],
			'emailType'     => [
				'key'    => 'emailType',
				'assert' => [
					'callbackFunc' => 'isJson'
				]
			],
			'feId'          => [
				'key' => 'feId'
			],
			'imageBase64'   => [
				'key' => 'imageBase64'
			],
			'background'    => [
				'key' => 'background'
			],
			'package'    => [
				'key' => 'package'
			],
			'emailSubject'  => [
				'key' => 'emailSubject'
			],
		];

		return $this->aFields;
	}
}
