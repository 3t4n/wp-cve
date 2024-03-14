<?php
/**
 * Hookable attribute class file.
 *
 * @package WP Utils
 * @subpackage Decorators
 */

namespace Oblak\WP\Decorators;

use Attribute;

/**
 * Defines class as hookable - it will be automatically constructed on given hook with given priority.
 */
#[Attribute( Attribute::TARGET_CLASS )]
class Hookable {
    /**
     * Constructor
     *
     * @param  string   $hook        Hook name.
     * @param  int      $priority    Hook priority.
     * @param  callable $conditional Conditional callback function.
     */
    public function __construct(
        /**
         * Hook name
         *
         * @var string
         */
        public string $hook,
        /**
         * Hook priority
         *
         * @var int
         */
        public int $priority = 10,
        /**
         * Conditional callback function
         *
         * @var callable
         */
        public $conditional = '__return_true',
    ) {
    }
}
