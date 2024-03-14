<?php
/**
 * Base_Plugin class file.
 *
 * @package WP Utils
 * @subpackage Abstracts
 */

namespace Oblak\WP\Traits;

use Oblak\WP\Decorators\Hookable;
use Oblak\WP\Traits\Singleton_Trait;

use function Oblak\WP\Utils\get_decorators;
use function Oblak\WP\Utils\invoke_class_hooks;

/**
 * Enables basic DI and hooking functionality for plugins / themes
 */
trait Hook_Processor_Trait {

    /**
     * Plugin textdomain
     *
     * @var string|null
     */
    protected ?string $textdomain = null;

    /**
     * Runs the hooks registered in the class, and initializes the dependencies.
     *
     * @param string $hook     Hook name.
     * @param int    $priority Hook priority.
     */
    public function init( string $hook = 'plugins_loaded', int $priority = 10 ) {
        add_action( $hook, array( $this, 'run_hooks' ), $priority );
        add_action( $hook, array( $this, 'init_dependencies' ), $priority );
    }

    /**
     * Return an array of class names to be instantiated on plugin init.
     *
     * @var array<int, class-string>
     */
    abstract protected function get_dependencies(): array;

    /**
     * Runs the registered hooks for the plugin.
     */
    public function run_hooks() {
        invoke_class_hooks( $this );
    }

    /**
     * Initializes the dependency dlasses
     */
    public function init_dependencies() {
        $di_data = array();

        foreach ( $this->get_dependencies() as $dep_class ) {
            $dep_data = $this->get_dependency_data( $dep_class );

            if ( ! $dep_data ) {
                continue;
            }

            $di_data[ $dep_data['hook'] ][ $dep_data['priority'] ][] = wp_array_slice_assoc( $dep_data, array( 'classname', 'conditional' ) );
        }

        foreach ( $di_data as $hook => $priorities ) {
            ksort( $priorities );

            foreach ( $priorities as $priority => $deps ) {
                add_action( $hook, fn() => $this->load_dependencies( $deps ), $priority );
            }
        }
    }

    /**
     * Get the dependency data from the class decorator
     *
     * @param  class-string $dep_class Dependency class name.
     * @return array|null              Dependency data.
     */
    protected function get_dependency_data( string $dep_class ): ?array {
        $metadata = get_decorators( $dep_class, Hookable::class );
        $metadata = array_shift( $metadata );

        return $metadata ? array(
            'hook'        => $metadata->hook,
            'priority'    => $metadata->priority,
            'classname'   => $dep_class,
            'conditional' => $metadata->conditional,
        ) : null;
    }


    /**
     * Loads the dependencies
     *
     * @param array<string, callable|class-string> $deps Array of dependencies.
     */
    protected function load_dependencies( array $deps ) {
        $deps = wp_list_pluck(
            array_filter(
                $deps,
                fn( $dep ) => $dep['conditional'](),
            ),
            'classname'
        );

        array_walk( $deps, fn( $d ) => new $d() );
    }
}
