<?php

namespace Fab\Module;

! defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 * setComponent
 *
 * @package    Fab
 * @subpackage Fab/Controller
 */

use FAB\Plugin;
use Fab\View;

class FABModuleAuthLogout extends FABModule {

    /**
     * Module construect
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->key         = 'module_auth_logout';
        $this->name        = 'Auth Logout';
        $this->description = 'Popup Auth Logout';
    }

    /** Render Module */
    public function render(){
        View::RenderStatic('Frontend.Module.logout');
    }

}