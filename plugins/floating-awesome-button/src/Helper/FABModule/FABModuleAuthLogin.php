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

class FABModuleAuthLogin extends FABModule {

    /**
     * Module construect
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->key         = 'module_auth_login';
        $this->name        = 'Auth Login';
        $this->description = 'Popup Auth Login';
    }

    /** Render Module */
    public function render(){
        View::RenderStatic('Frontend.Module.login');
    }

}