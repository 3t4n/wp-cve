<?php
namespace Includes\Libraries;

class User extends Resource {


	protected $userId;

	public function __construct( $userId = null, $client = null, $href = null ) {
		parent::__construct( $href, $client );
		if ( ! is_null( $userId ) ) {
			$this->userId = $userId;
		}
	}

	public function create( $data = array() ) {
		return $this->request( Client::POST, $this->uri(), $data );
	}

	public function update( $data = array() ) {
		return $this->request( Client::PUT, $this->uri() . '/' . $this->userId, $data );
	}

	public function delete() {
		return $this->request( Client::DELETE, $this->uri() . '/' . $this->userId );
	}

	public function addFriend( $data = array() ) {
		return $this->request( Client::POST, $this->uri( 'add_friend' ), $data );
	}

	public function removeFriend( $data = array() ) {
		return $this->request( Client::POST, $this->uri( 'remove_friend' ), $data );
	}

	public function blockUsers( $data = array() ) {
		return $this->request( Client::POST, $this->uri( 'block' ), $data );
	}

	public function unblockUsers( $data = array() ) {
		return $this->request( Client::POST, $this->uri( 'unblock' ), $data );
	}

	public function logout( $data = array() ) {
		return $this->request( Client::POST, $this->uri( 'logout' ), $data );
	}

	public function login( $data = array() ) {
		return $this->request( Client::POST, $this->uri( 'login' ), $data );
	}

	public function createAccessToken( $data = array() ) {
		return $this->request( Client::POST, $this->uri( 'create_access_token' ), $data );
	}

	protected function uri( $urlSuffix = null ) {
		if ( ! empty( $this->href ) ) {
			return $this->getHref();
		}
		return $this->uriForUser( $urlSuffix );
	}

	protected function uriForUser( $urlSuffix ) {
		$uri = Client::PATH_USERS;
		if ( $urlSuffix ) {
			return $uri . '/' . rawurlencode( $urlSuffix );
		}

		return $uri;
	}
	

	
}
