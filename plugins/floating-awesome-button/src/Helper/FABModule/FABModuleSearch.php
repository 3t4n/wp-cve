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

class FABModuleSearch extends FABModule {

    /**
     * Module construect
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->key         = 'module_search';
        $this->name        = 'Search';
        $this->description = 'Modal Search Configuration';

        /** Initialize Options */
        $this->options = array(
            'label' => array(
                'text' => 'Search Label',
                'type' => 'text',
                'value' => 'Search...',
            ),
            'pagination' => array(
                'text' => 'Pagination',
                'children' => array(
                    'enable' => array(
                        'text' => 'Enable Pagination',
                        'label' => array( 'text' => 'Enable/Disable' ),
                        'type' => 'switch',
                        'value' => 1,
                    ),
                    'per_page' => array(
                        'text' => 'Per Page',
                        'type' => 'text',
                        'value' => '10',
                        'info' => 'Maximum number of items to be returned in result set.'
                    ),
                )
            )
        );
        $options = $this->WP->get_option( sprintf('fab_%s', $this->key) );
        $this->options = (is_array($options)) ? $this->Helper->ArrayMergeRecursive($this->options, $options) : $this->options;
    }

    /** Render Module */
    public function render(){
        View::RenderStatic('Frontend.Module.search');
    }

}