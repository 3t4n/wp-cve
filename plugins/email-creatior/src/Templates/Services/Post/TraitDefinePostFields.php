<?php

namespace WilokeEmailCreator\Templates\Services\Post;


use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;

trait TraitDefinePostFields
{
	private array $aFields = [];

	public function defineFields(): array
	{
		$this->aFields = [
			'status'     => [
				'key'              => 'post_status',
				'sanitizeCallback' => [$this, 'sanitizePostStatus'],
				'value'            => 'enabled',
				'assert'           => [
					'callbackFunc' => 'inArray',
					'expected'     => ['enabled', 'disabled', 'trash']
				]
			],
			'id'         => [
				'key'              => 'ID',
				'sanitizeCallback' => 'abs',
				'value'            => 0
			],
			'label'      => [
				'key'              => 'post_title',
				'sanitizeCallback' => 'sanitize_text_field',
				'value'            => uniqid('wiloke-email-template'),
				'assert'           => [
					'callbackFunc' => 'notEmpty'
				]
			],
			'type'       => [
				'key'        => 'post_type',
				'value'      => AutoPrefix::namePrefix('templates'),
				'isReadOnly' => true
			],
			'author'     => [
				'key'        => 'post_author',
				'isRequired' => true,
				'isReadOnly' => true,
				'value'      => get_current_user_id()
			]
		];

		return $this->aFields;
	}

	public function sanitizePostStatus($value): string
	{
		switch ($value) {
			case 'enabled':
				$status = 'publish';
				break;
			case 'trash':
				$status = 'trash';
				break;
			default:
				$status = 'draft';
				break;
		}
		return $status;
	}
}
