<?php

#namespace WilokeTest;

interface IResponse
{
	public function setAdditionalArgs(array $aAdditionalArgs): IResponse;

	public function setPluck($pluck): IResponse;

	/**
	 * Render data
	 * @param object $post
	 * @return mixed
	 */
	public function render(object $post): array;
}
