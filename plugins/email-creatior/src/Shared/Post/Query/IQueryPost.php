<?php


namespace WilokeEmailCreator\Shared\Post\Query;


interface IQueryPost {

	public function setRawArgs( array $aRawArgs ): IQueryPost;
	public function parseArgs(): IQueryPost;
	public function getArgs(): array;

	/**
	 *
	 *
	 * @param PostSkeleton $oPostSkeleton
	 * @param false $isSingle
	 * @param string $pluck
	 *
	 * @return array
	 */
	public function query( PostSkeleton $oPostSkeleton, string $pluck = '', bool $isSingle = false ): array;
}
