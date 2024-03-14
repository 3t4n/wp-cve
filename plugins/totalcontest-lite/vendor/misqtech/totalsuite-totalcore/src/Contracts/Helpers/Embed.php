<?php

namespace TotalContestVendors\TotalCore\Contracts\Helpers;


interface Embed {

	public function getProviderName( $url );

	public function getProviderThumbnail( $url );

	public function getProviderHtml( $url );

	public function getProvider( $url );

}