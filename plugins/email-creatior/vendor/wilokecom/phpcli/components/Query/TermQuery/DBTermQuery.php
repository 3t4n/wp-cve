<?php


#namespace WilokeTest;


#use WilokeOriginalNamespace\Illuminate\Query\IQuery;
#use WilokeOriginalNamespace\Illuminate\Query\IResponse;

class DBTermQuery implements IQuery
{
	private $aArgs;
	private $oQuery;
	/**
	 * @var IResponse $oResponse
	 */
	private $oResponse;

	public function setQueryArgs(array $aArgs): IQuery
	{
		$this->aArgs = $aArgs;

		return $this;
	}

	public function setResponse(IResponse $oResponse): IQuery
	{
		$this->oResponse = $oResponse;

		return $this;
	}

	public function query(): array
	{
		$aTerms = get_terms($this->aArgs);
		$aResponse = [];

		if (is_wp_error($aTerms) || empty($aTerms)) {
			return $aResponse;
		}

		foreach ($aTerms as $oTerm) {
			$aResponse[] = $this->oResponse->render($oTerm);
		}

		return $aResponse;
	}

	public function getQuery(): \WP_Query
	{
		return ($this->oQuery);
	}
}
