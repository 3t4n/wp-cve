<?php

namespace TotalContestVendors\TotalCore\Foundation;

use TotalContestVendors\League\Container\ContainerInterface as ContainerContract;
use TotalContestVendors\TotalCore\Application;
use TotalContestVendors\TotalCore\Contracts\Foundation\Plugin as PluginContract;

/**
 * Class Plugin
 * @package TotalContestVendors\TotalCore\Foundation
 */
abstract class Plugin implements PluginContract {
	/**
	 * @var Application $application
	 */
	protected $application;
	/**
	 * @var ContainerContract $container
	 */
	protected $container;

	/**
	 * Get application.
	 *
	 * @return Application
	 */
	final public function getApplication() {
		return $this->application;
	}

	/**
	 * Set application.
	 *
	 * @param Application $application
	 */
	final public function setApplication( Application $application ) {
		$this->application = $application;
		$this->container   = $application->container();
	}

    /**
     * @return array
     */
	public function objectsCount() {
	    return [];
    }
}