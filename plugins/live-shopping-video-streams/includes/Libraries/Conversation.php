<?php namespace Includes\Libraries;

class Conversation extends Resource {

	protected $conversationId;

	public function __construct( $conversationId = null, $client = null, $href = null ) {
		parent::__construct( $href, $client );
		if ( ! is_null( $conversationId ) ) {
			$this->conversationId = $conversationId;
		}
	}

	public function create( $data ) {
		return $this->request( Client::POST, Client::PATH_CONVERSATIONS, $data );
	}

	public function update( $data ) {
		return $this->request( Client::PUT, $this->uri() . '/' . $this->conversationId, $data );
	}

	protected function uri( $urlSuffix = null ) {
		if ( ! empty( $this->href ) ) {
			return $this->getHref();
		}
		return $this->uriForConversation( $urlSuffix );
	}

	protected function uriForConversation( $urlSuffix ) {
		$uri = Client::PATH_CONVERSATIONS;
		if ( $urlSuffix ) {
			return $uri . '/' . rawurlencode( $urlSuffix );
		}

		return $uri;
	}
}