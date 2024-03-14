<?php namespace flow\social;

use Exception;
use stdClass;
use Unirest\Request;

/**
 * Flow-Flow
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 * @link      http://looks-awesome.com
 * @copyright 2014-2020 Looks Awesome
 */
class FFRemoteFeed implements FFFeed {
	/** @var stdClass */
	public $feed;
	/**
	 * @var FFFeed $object
	 */
	private $object;
	/**
	 * @var array $remote_source
	 */
	private $remote_source = null;

	/**
	 * FFRemoteFeed constructor.
	 *
	 * @param FFFeed $object
	 */
	public function __construct( FFFeed $object ) {
		$this->object = $object;
	}

	public function id() {
		return $this->object->id();
	}

	public function init( $context, $feed ) {
		$this->feed = $feed;
		$this->object->init($context, $feed);
	}

	public function posts( $is_empty_feed ) {
		return [];
	}

	public function errors() {
		$source = $this->getSource();
		if (isset($source['errors'])){
			return $source['errors'];
		}
		return [];
	}

	public function hasCriticalError() {
		return false;
	}

	public function getType() {
		return $this->object->getType();
	}

	/**
	 * @return array|mixed
	 * @throws Exception
	 */
	private function getSource(){
		if ($this->remote_source == null){
			$this->remote_source = $this->proxyRequest();
		}
		return $this->remote_source;
	}

	/**
	 * @return mixed
	 * @throws Exception
	 */
	private function proxyRequest(){
		$domain = $_SERVER['HTTP_HOST'];
		$url = FF_BOOST_SERVER . 'flow-flow/ff?shop=' . $domain . '&action=get_source&feed=' . $this->object->id();
		Request::jsonOpts(true);
		$response = Request::post($url, [
			'Content-Type: application/x-www-form-urlencoded'
		], http_build_query($_POST));
		if ($response->code != 200){
			error_log(print_r($response, true));
			throw new Exception('Problem: Remote get source doesnt work');
		}
		return json_decode($response->raw_body, true);
	}
}