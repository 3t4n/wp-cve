<?php


#namespace WilokeTest;


#use WilokeOriginalNamespace\Helpers\FunctionHelper;
#use WilokeOriginalNamespace\Helpers\StringHelper;
#use WilokeOriginalNamespace\Illuminate\Query\IQueryBuilder;

class QueryTermsBuilder implements IQueryBuilder
{
	private $aRawArgs;
	private $aArgs;
	private $aDefaultArgs
		= [
			'taxonomy'   => '',
			'orderby'    => 'name',
			'order'      => 'DESC',
			'hide_empty' => false
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

		$this->aArgs = shortcode_atts($this->aDefaultArgs, $aParsedRawArgs);

		if (isset($aParsedRawArgs['s'])) {
			$this->aArgs['name__like'] = $aParsedRawArgs['s'];
		}

		if (isset($aParsedRawArgs['id'])) {
			$this->aArgs['include'] = $aParsedRawArgs['id'];
		}

		if (isset($aParsedRawArgs['posts_per_page'])) {
			$this->aArgs['number'] = $aParsedRawArgs['posts_per_page'];
		}

		if (isset($aParsedRawArgs['is_hide_empty'])) {
			$this->aArgs['hide_empty'] = $aParsedRawArgs['is_hide_empty'] == 'yes';
		}

		if (isset($aParsedRawArgs['taxonomy'])) {
			$aRawTaxonomy = explode(',', $aParsedRawArgs['taxonomy']);
			$aTaxonomy = array_reduce($aRawTaxonomy, function ($aCarry, $item) {
				if (taxonomy_exists(trim($item))) {
					$aCarry[] = $item;
				}
				return $aCarry;
			}, []);
			$this->aArgs['taxonomy'] = $aTaxonomy;
		}

		return $this;
	}

	public function getArgs(): array
	{
		return $this->aArgs;
	}
}
