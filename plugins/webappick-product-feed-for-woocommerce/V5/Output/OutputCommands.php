<?php

namespace CTXFeed\V5\Output;

use CTXFeed\V5\Helper\CommonHelper;
use CTXFeed\V5\Helper\FeedHelper;
use CTXFeed\V5\Output\FormatOutput;

/**
 * Class OutputCommands
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Output
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   MyCategory
 */
class OutputCommands extends FormatOutput {

	private $product;
	/**
	 * @var Config $config
	 */
	private $config;
	private $attribute;

	private $commands;


	public function __construct( $product, $config, $attribute ) {
		parent::__construct( $product, $config, $attribute );
		$this->product   = $product;
		$this->config    = $config;
		$this->attribute = $attribute;
	}

	/**
	 * Remove shortcodes from string.
	 *
	 * @param string $output
	 * @param string $command
	 *
	 * @return string
	 */
	private function replace_string( $output, $command, $type = 'str_replace' ) {

		if ( strpos( $command, $type ) && strpos( $this->commands, $type ) ) {
			$command = $this->commands;
		}

		$args = explode( '=>', $command, 3 );
		if ( array_key_exists( 1, $args ) && array_key_exists( 2, $args ) ) {

			list( $argument1, $argument2 ) = array_map( 'trim', array( $args[1], $args[2] ) );

			if ( false !== strpos( $args[1], 'comma' ) ) {
				$argument1 = str_replace( 'comma', ',', $args[1] );
			}

			if ( false !== strpos( $args[2], 'comma' ) ) {
				$argument2 = str_replace( 'comma', ',', $args[2] );
			}
			if ( 'str_replace' === $type ) {
				$output = str_replace( (string) $argument1, (string) $argument2, $output );
			} else {
				$output = preg_replace( wp_unslash( $argument1 ), wp_unslash( $argument2 ), $output );
			}
		}

		return $output;
	}

	/**
	 * Number Format Output.
	 *
	 * @param string $output
	 * @param string $command
	 *
	 * @return string
	 */
	public function format_number( $output, $command ) {
		if ( ! empty( $output ) ) {
			$args      = explode( ' ', $command, 4 );
			$arguments = array( 0 => '' );

			if ( isset( $args[1] ) ) {
				$arguments[1] = $args[1];
			}

			if ( isset( $args[2] ) && 'point' === $args[2] ) {
				$arguments[2] = '.';
			} elseif ( isset( $args[2] ) && 'comma' === $args[2] ) {
				$arguments[2] = ',';
			} elseif ( isset( $args[2] ) && 'space' === $args[2] ) {
				$arguments[2] = ' ';
			}

			if ( isset( $args[3] ) && 'point' === $args[3] ) {
				$arguments[3] = '.';
			} elseif ( isset( $args[3] ) && 'comma' === $args[3] ) {
				$arguments[3] = ',';
			} elseif ( isset( $args[3] ) && 'space' === $args[3] ) {
				$arguments[3] = ' ';
			} else {
				$arguments[3] = '';
			}

			if ( isset( $arguments[1], $arguments[2], $arguments[3] ) ) {
				$output = number_format( $output, $arguments[1], $arguments[2], $arguments[3] );
			} elseif ( isset( $arguments[1], $arguments[2] ) ) {
				$output = number_format( $output, $arguments[1], $arguments[2], $arguments[3] );
			} elseif ( isset( $arguments[1] ) ) {
				$output = number_format( $output, $arguments[1] );
			} else {
				$output = number_format( $output );
			}
		}

		return $output;
	}

	/**
	 * Remove shortcodes from string.
	 *
	 * @param string $string String to remove shortcodes from.
	 *
	 * @return array
	 */

	public function get_functions( $string ) {
		$functions = explode( ',', $string );
		$funArray  = array();
		if ( count( $functions ) ) {
			foreach ( $functions as $value ) {
				if ( ! empty( $value ) ) {
					$funArray['formatter'][] = FeedHelper::get_string_between( $value, '[', ']' );
				}
			}
		}

		return $funArray;
	}


	/**
	 * php function from string.
	 *
	 * @param string $string String to remove shortcodes from.
	 *
	 * @return string
	 */
	public function get_function( $string ) {
		$function = explode( ' ', $string );

		return $function[0];
	}

	/**
	 * @param $string
	 *
	 * Get function command for str_replace
	 *
	 * @return mixed|string
	 */
	public function get_function_command( $string ) {
		if ( strpos( $string, '=>' ) !== false ) {
			$this->commands = $string;
			$function       = explode( '=>', $string );

			return $function[0];
		}

		return $string;
	}

	/**
	 * Process commands.
	 *
	 * @param $output
	 * @param $commands
	 *
	 * @return array|false|mixed|string|string[]|null
	 */
	public function process_command( $output, $commands ) {
		// Custom Template 2 return commands as array
		if ( ! is_array( $commands ) ) {
			$commands = $this->get_functions( $commands );
		}

		foreach ( $commands['formatter'] as $command ) {
			if ( ! empty( $command ) ) {

				$function = $this->get_function( $command );
				$function = $this->get_function_command( $function );

				switch ( $function ) {
					case 'substr':
						$args   = preg_split( '/\s+/', $command );
						$output = CommonHelper::strip_all_tags( $output );
						$output = substr( $output, $args[1], $args[2] );
						break;
					case 'strip_tags':
						$output = CommonHelper::strip_all_tags( $output );
						break;
					case 'htmlentities':
						$output = htmlentities( $output );
						break;
					case 'clear':
						$output = CommonHelper::strip_invalid_xml( $output );
						break;
					case 'ucwords':
						$output = ucwords( mb_strtolower( $output ) );
						break;
					case 'ucfirst':
						$output = ucfirst( mb_strtolower( $output ) );
						break;
					case 'strtoupper':
						$output = mb_strtoupper( $output );
						break;
					case 'strtolower':
						$output = mb_strtolower( $output );
						break;
					case 'strip_shortcodes':
						$output = CommonHelper::remove_shortcodes( $output );
						break;
					case 'number_format':
						$output = $this->format_number( $output, $command );
						break;
					case 'urltounsecure':
						if ( strpos( $output, 'http' ) === 0 ) {
							$output = str_replace( 'https://', 'http://', $output );
						}
						break;
					case 'urltosecure':
						if ( strpos( $output, 'http' ) === 0 ) {
							$output = str_replace( 'http://', 'https://', $output );
						}
						break;
					case 'str_replace':
						$output = $this->replace_string( $output, $command );
						break;
					case 'preg_replace':
						$output = $this->replace_string( $output, $command, 'preg_replace' );
						break;
					case 'only_parent';
						$output = $this->get_only_parent( $output );
						break;
					case 'parent_if_empty';
						$output = $this->get_parent_if_empty( $output );
						break;
					case 'parent';
						$output = $this->get_parent( $output );
						break;
					default:
						break;
				}
			}
		}

		return $output;
	}
}
