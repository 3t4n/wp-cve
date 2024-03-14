<?php
namespace tmc\revisionmanager\src;

/**
 * @author jakubkuranda@gmail.com
 * Date: 06.03.2018
 * Time: 10:21
 */

use shellpress\v1_4_0\ShellPress;
use tmc\revisionmanager\src\Components\AdminPage;
use tmc\revisionmanager\src\Components\DashboardWidget;
use tmc\revisionmanager\src\Components\AcfDifferences;
use tmc\revisionmanager\src\Components\JetPlugs;
use tmc\revisionmanager\src\Components\Notifications;
use tmc\revisionmanager\src\Components\Revisions;
use tmc\revisionmanager\src\Components\Settings;
use tmc\revisionmanager\src\Components\System;
use tmc\revisionmanager\src\Components\Upgrade;
use tmc\revisionmanager\src\Components\Utilities;

class App extends ShellPress {

    const TMC_SHOP_URL = 'https://themastercut.co/plugins/revision-manager-tmc/?utm_source=client&utm_medium=plugin&utm_campaign=revision-manager-tmc';

    /** @var JetPlugs */
    public $jetPlugs;

    /** @var Settings */
    public $settings;

    /** @var Revisions */
    public $revisions;

    /** @var Utilities */
    public $utilities;

    /** @var Upgrade */
    public $upgrade;

    /** @var DashboardWidget */
    public $dashboardWidget;

    /** @var AcfDifferences */
    public $acfDifferences;

    /** @var Notifications */
    public $notifications;

    /** @var System */
    public $system;
	
	/** @var AdminPage */
	public $adminPage;

    /**
     * Called automatically after core is ready.
     *
     * @return void
     */
    protected function onSetUp() {

        //  ----------------------------------------
        //  Components
        //  ----------------------------------------

        $this->upgrade          = new Upgrade( $this );
        $this->jetPlugs         = new JetPlugs( $this );
        $this->settings         = new Settings( $this );
        $this->utilities        = new Utilities( $this );
        $this->notifications    = new Notifications( $this );
        $this->revisions        = new Revisions( $this );
        $this->dashboardWidget  = new DashboardWidget( $this );
        $this->acfDifferences   = new AcfDifferences( $this );
        $this->system           = new System( $this );
		$this->adminPage        = new AdminPage( $this );

    }

}