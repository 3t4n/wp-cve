<?php


namespace WilokeEmailCreator\Shared\Post;


interface IService {
	/**
	 *
	 * @return array
	 */
	public function defineFields(): array;

	/**
	 *
	 * @param array $aRawData
	 *
	 * @return IService
	 */
	public function setRawData( array $aRawData ): IService;

	/**
	 * @return IService
	 */
	public function validateFields(): IService;

	/**
	 *
	 * @return array{id: string}
	 */
	public function performSaveData(): array;
}
