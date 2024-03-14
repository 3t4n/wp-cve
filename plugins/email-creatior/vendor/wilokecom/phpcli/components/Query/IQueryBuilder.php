<?php

#namespace WilokeTest;

interface IQueryBuilder
{
	public function setRawArgs(array $aRawArgs): IQueryBuilder;

	public function parseArgs(): IQueryBuilder;

	public function getArgs(): array;
}
