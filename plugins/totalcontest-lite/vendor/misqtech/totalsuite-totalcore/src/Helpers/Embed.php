<?php

namespace TotalContestVendors\TotalCore\Helpers;


use TotalContestVendors\TotalCore\Contracts\Helpers\Embed as EmbedContract;

/**
 * Class Embed
 * @package TotalContestVendors\TotalCore\Helpers
 */
class Embed implements EmbedContract {

	/**
	 * @var $wpOEmbed \WP_oEmbed
	 */
	protected $wpOEmbed;
	protected $cache = [];

	public function __construct( $wpOEmbed ) {
		$this->wpOEmbed = $wpOEmbed;
	}

	public function getProviderName( $url ) {
		$provider = $this->getProvider( $url );
		if ( ! empty( $provider->provider_name ) ):
			return strtolower( $provider->provider_name );
		endif;

		return false;
	}

	public function getProvider( $url ) {
		if ( ! isset( $this->cache[ $url ] ) ):
			$providerUrl         = $this->wpOEmbed->get_provider( $url );
			$this->cache[ $url ] = $this->wpOEmbed->fetch( $providerUrl, $url );
		endif;

		return $this->cache[ $url ];
	}

	public function getProviderThumbnail( $url ) {
		$provider = $this->getProvider( $url );
		if ( ! empty( $provider->thumbnail_url ) ):
			return $provider->thumbnail_url;
		endif;

		return false;
	}

	public function getProviderHtml( $url ) {
		$provider = $this->getProvider( $url );
		if ( ! empty( $provider ) ):
			return $this->wpOEmbed->data2html( $provider, $url );
		endif;

		return false;
	}

}