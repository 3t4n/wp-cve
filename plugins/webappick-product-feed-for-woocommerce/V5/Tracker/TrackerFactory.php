<?php
namespace CTXFeed\V5\Tracker;
class TrackerFactory {
	public static function Track(  ) {
		new FacebookTracker();
		new GoogleTracker();
		new PinterestTracker();
		new TiktokTracker();
	}
}