<?php
/**
 * Hook_Runner class file
 *
 * @package WP_Utils
 * @subpackage Abstracts
 */

namespace Oblak\WP\Abstracts;

use function Oblak\WP\Utils\invoke_class_hooks;

/**
 * Base hook runner.
 *
 * Runs all the hooks registered in the class.
 */
abstract class Hook_Runner {
    /**
     * Constructor
     */
    public function __construct() {
        invoke_class_hooks( $this );
    }
}
