<?php

#namespace WilokeTest;

interface IQuery
{
	public function setQueryArgs(array $aArgs): IQuery;

	public function setResponse(IResponse $oResponse): IQuery;

	public function getQuery();

	public function query(): array;
}
