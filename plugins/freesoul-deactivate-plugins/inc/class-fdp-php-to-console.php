<?php
/**
 * Class to print PHP variables to the JS Console.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Class FDP PHP To Console
 *
 * Helper class for debugging.
 *
 * @version  1.0.0
 * @package  Freesoul Deactivate Plugins\Classes
 */
class FDP_PHP_To_Console {

	/**
	 * Input variable.
	 *
	 * @var int|string|array|object $var Input variable
	 * @since  2.1.4
	 */		
	public $var;

	/**
	 * Input variable name.
	 *
	 * @var string $var_name Input variable name
	 * @since  2.1.4
	 */		
	public $var_name;

    /**
	 * Console.
	 *
	 * @var string $console Output
	 * @since  2.1.4
	 */		
	public $console;

	/**
	 * Default constructor.
	 *
	 * @param string $var Input variable
	 * @since  2.1.4
	 */
	public function __construct( $var, $var_name ) {
        $this->var = $var;
        $this->var_name = $var_name ? $var_name : 'FDP PHP to Console';
        add_action( 'wp_footer', array( $this, 'print_console' ), 999999 );
        add_action( 'admin_footer', array( $this, 'print_console' ), 999999 );
    }

    /**
	 * Print console output.
	 *
	 * @since  2.1.4
	 */
	public function print_console() {
        ?>
        <script id="fdp-print-console">
			console.log( "<?php echo esc_js( esc_attr( $this->var_name ) ); ?>");
            console.log(<?php echo wp_json_encode( $this->var ); ?> );
        </script>
        <?php
    }
}

/**
 * Output the input variable to the console.
 *
 * @since 2.1.4
 *
 */
function fdp_php2console( $var, $var_name = false ) {
	$console = new FDP_PHP_To_Console( $var, $var_name );
}