<?php
namespace CTXFeed\V5\Tracker;
interface TrackerInterface {
	public function is_activated(  );
	public function enqueueScript(  );
	public function loadBaseScript(  );
}