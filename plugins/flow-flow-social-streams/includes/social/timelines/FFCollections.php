<?php namespace flow\social\timelines;
use flow\social\FFTwitter;

if ( ! defined( 'WPINC' ) ) die;
/**
 * Flow-Flow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>

 * @link      http://looks-awesome.com
 * @copyright 2014-2016 Looks Awesome
 */
class FFCollections implements FFTimeline {
	const URL = 'https://api.twitter.com/1.1/collections/entries.json';

	private $count;
	private $id;

	/**
	 * @param FFTwitter $twitter
	 * @param $feed
	 */
	public function init($twitter, $feed) {
		$this->count = $twitter->getCount();
		$this->id = 'custom-' . $feed->content;
	}

	public function getUrl() {
		return self::URL;
	}

	public function getField() {
		return "?id={$this->id}&count={$this->count}";
	}
}