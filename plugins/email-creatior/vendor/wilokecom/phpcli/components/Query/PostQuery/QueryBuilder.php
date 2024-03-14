<?php


#namespace WilokeTest;


#use WilokeOriginalNamespace\Helpers\StringHelper;
#use WilokeOriginalNamespace\Illuminate\Query\IQueryBuilder;

class QueryBuilder implements IQueryBuilder
{
	private $aRawArgs;
	private $aArgs
		= [
			'post_status'    => 'publish',
			'posts_per_page' => 10
		];

	public function setRawArgs(array $aRawArgs): IQueryBuilder
	{
		$this->aRawArgs = $aRawArgs;

		return $this;
	}

	public function parseArgs(): IQueryBuilder
	{
		$aRawKeys = array_keys($this->aRawArgs);

		$aParsedRawArgs = array_reduce($aRawKeys, function ($aCarry, $key) {
			$aCarry[StringHelper::replaceUpperCaseWithUnderscore($key)] = $this->aRawArgs[$key];
			return $aCarry;
		}, []);

		$this->aArgs = wp_parse_args($aParsedRawArgs, $this->aArgs);

		if (!isset($aParsedRawArgs['posts_per_page'])) {
			if (isset($aParsedRawArgs['items_per_row']) && isset($aParsedRawArgs['max_rows'])) {
				$this->aArgs['posts_per_page'] = $aParsedRawArgs['items_per_row'] * $aParsedRawArgs['max_rows'];
			}
		}

		return $this;
	}

	public function getArgs(): array
	{
		return $this->aArgs;
	}
}
