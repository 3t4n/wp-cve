<?php


#namespace WilokeTest;


#use WilokeOriginalNamespace\Illuminate\Query\IQuery;
#use WilokeOriginalNamespace\Illuminate\Query\IResponse;

class DBQuery implements IQuery
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
		$query = new \WP_Query($this->aArgs);
		$this->oQuery = $query;
		$aResponse = [];

		if (!$query->have_posts()) {
			wp_reset_postdata();

			return $aResponse;
		}

		while ($query->have_posts()) {
			$query->the_post();
			$aResponse[] = $this->oResponse->render($query->post);
		}

		wp_reset_postdata();
		return $aResponse;
	}

	public function getQuery(): \WP_Query
	{
		return ($this->oQuery);
	}
}
