<?php
/**
 * @package CTXFeed\V5\File
 */

namespace CTXFeed\V5\File;

use CTXFeed\V5\Structure\GooglereviewStructure;
use CTXFeed\V5\Utility\Settings;
use SimpleXMLElement;

/**
 * XML file creation class implementing the FileInterface.
 *
 * This class is responsible for creating XML formatted files based on provided data and configuration.
 */
class XML implements FileInterface {
	/**
	 * Data to be written to the XML file.
	 *
	 * @var array
	 */
	private $data;

	/**
	 * Configuration settings for the XML file creation.
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * Stores the generated XML body content.
	 *
	 * @var string
	 */
	private $feed_body;

	/**
	 * Constructor for the XML class.
	 *
	 * Initializes the XML file with provided data and configuration.
	 *
	 * @param array $data Data for the XML file.
	 * @param Config $config Configuration settings for the XML file.
	 */
	public function __construct( $data, $config ) {

		$this->data   = $data;
		$this->config = $config;
	}

	/**
	 * Creates the header and footer for the XML file.
	 *
	 * @return array An array with 'header' and 'footer' keys.
	 */
	public function make_header_footer() {
		$header_footer = $this->get_header_footer( $this->config );

		return \apply_filters( "ctx_make_{$this->config->feedType}_feed_header_footer", $header_footer, $this->data, $this->config );
	}

	/**
	 * Convert an array to XML format.
	 *
	 * This method recursively converts an array into an XML string. Special handling is applied for
	 * certain keys and configurations (e.g., Google Review specific formatting).
	 *
	 * @param array  $array The array to convert.
	 * @param string $xml   Reference to the XML string being built.
	 */
	public function array_to_xml( $array, &$xml ) {
		foreach ( $array as $key => $value ) {
			if ( \is_array( $value ) ) {
				if ( !\is_numeric( $key ) ) {
					$this->feed_body .= "<$key>" . PHP_EOL;
					self::array_to_xml( $value, $child );
					$this->feed_body .= "</$key>" . PHP_EOL;
				} else {
					self::array_to_xml( $value, $xml );
				}
			} else {
				$value = $this->format_value( $key, $value );
				$this->feed_body .= $value;
			}
		}
	}

	/**
	 * Format the value for XML output.
	 *
	 * This method applies XML encoding and specific formatting based on the key and configuration.
	 *
	 * @param string $key   The key associated with the value.
	 * @param mixed  $value The value to be formatted.
	 *
	 * @return string The formatted value.
	 */
	private function format_value( $key, $value ) {
		if ( !\in_array( $key, ['g:tax', 'g:shipping'], true ) ) {
			$value = \htmlentities( $value, ENT_XML1 | ENT_QUOTES, 'UTF-8' );
			$value = $this->get_CDATA( $value );
		}

		if ( $this->config->get_feed_template() === 'googlereview' ) {
			if ( "overall" === $key ) {
				$value = "<$key min='1' max='5'>" . $value . "</$key>". PHP_EOL;
			} elseif ( "review_url" === $key ) {
				$value = "<$key type='group'>" . $value . "</$key>". PHP_EOL;
			}else {
				$value = "<$key>" . $value . "</$key>" . PHP_EOL;
			}
		}else{
			$value = "<$key>" . $value . "</$key>" . PHP_EOL;
		}

		return $value;
	}

	/**
	 * CDATA add
	 *
	 * @return string
	 */
	private function get_CDATA( $value ){
		$settings = Settings::get( 'enable_cdata' );
		return $this->add_CDATA( $settings,$value );

	}

	/** Add CDATA to String
	 *
	 * @param string $status
	 * @param string $output
	 *
	 * @return string
	 */
	private function add_CDATA( $status, $output ) {

		if ( 'yes' === $status && $output && $output!="") {
			$output = $this->remove_CDATA( $output );

			return '<![CDATA[' . $output . ']]>';
		}

		return $output;
	}

	/** Remove CDATA from String
	 *
	 * @param string $output
	 *
	 * @return string
	 */
	private function remove_CDATA( $output ) {
		$output = \html_entity_decode( $output );
		return \str_replace( [ "<![CDATA[", "]]>" ], "", $output );
	}

	/**
	 * Make XML body.
	 *
	 * @return false|string
	 */
	public function make_body() {
		// create simpleXML object

		$xml = '';
		$this->array_to_xml( $this->data, $xml );

		return \apply_filters( "ctx_make_{$this->config->feedType}_feed_body", $this->feed_body, $this->data, $this->config );
	}

	/**
	 * Create XML File Header and Footer based on configuration.
	 *
	 * This method generates the header and footer for the XML file. It handles different templates
	 * and configurations, such as a special format for Google Review feeds.
	 *
	 * @param Config $config Configuration object for the feed.
	 *
	 * @return array An array with 'header' and 'footer' keys containing the XML strings.
	 */
	private function get_header_footer( $config ) {

		if( $config->get_feed_template() === 'googlereview' ){
			$header = GooglereviewStructure::make_google_review_header();
			$footer = '</' . $config->itemsWrapper . '></feed>';

			$xml_wrapper['header'] = $this->make_header( $config, $header );
			$xml_wrapper['footer'] = "\n" . $this->make_footer( $config, $footer );
		}else{
			$xml_wrapper['header'] = $this->make_header( $config );
			$xml_wrapper['footer'] = "\n" . $this->make_footer( $config );
		}

		$config->itemWrapper  = \str_replace( ' ', '_', $config->itemWrapper );
		$config->itemsWrapper = \str_replace( ' ', '_', $config->itemsWrapper );

		if ( \file_exists( WOO_FEED_FREE_ADMIN_PATH . 'partials/templates/' . $config->provider . '.txt' ) ) {
			$txt = \file_get_contents( WOO_FEED_FREE_ADMIN_PATH . 'partials/templates/' . $config->provider . '.txt' );
			$txt = \trim( $txt );
			$txt = \explode( '{separator}', $txt );
			if ( 2 === \count( $txt ) ) {
				$xml_wrapper['header'] = $this->make_header( $config, \trim( $txt[0] ) );
				$xml_wrapper['footer'] = "\n" . $this->make_footer( $config, \trim( $txt[1] ) );
			}
		}

		return $xml_wrapper;
	}

	/**
	 * Replace template variables.
	 *
	 *
	 * @param $header
	 * @param $config
	 *
	 * @return array|string|string[]
	 */
	private function replace_template_variable( $header, $config ) {

		$variables = [
			'{DateTimeNow}'     => \gmdate( 'Y-m-d H:i:s', \strtotime( \current_time( 'mysql' ) ) ),
			'{BlogName}'        => \get_bloginfo( 'name' ),
			'{BlogURL}'         => \get_bloginfo( 'url' ),
			'{BlogDescription}' => "CTX Feed - This product feed is generated with the CTX Feed - WooCommerce Product Feed Manager plugin by WebAppick.com. For all your support questions check out our plugin Docs on https://webappick.com/docs or e-mail to: support@webappick.com",
			'{BlogEmail}'       => \get_bloginfo( 'admin_email' ),
		];

		$variables = \apply_filters( 'ctx_xml_header_template_variables', $variables, $config );

		return \str_replace( \array_keys( $variables ), \array_values( $variables ), $header );
	}

	/**
	 * Make XML Header.
	 *
	 * @param $config
	 * @param $override
	 *
	 * @return mixed|void
	 */
	private function make_header( $config, $override = '' ) {
		$config->itemsWrapper = \str_replace( ' ', '_', $config->itemsWrapper );
		if ( ! empty( $override ) ) {
			$header = $override;
		} else {
			$header = '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL . "<" . \wp_unslash( $config->itemsWrapper ) . ">";
		}

		if ( ! empty( $config->extraHeader ) ) {
			$header .= PHP_EOL . \wp_unslash( $config->extraHeader );
		}

		// replace template variables.
		$header = $this->replace_template_variable( $header, $config );

		return \apply_filters( 'ctx_make_xml_header', $header, $config );
	}

	/**
	 * Make XML Footer.
	 *
	 * @param $config
	 * @param $override
	 *
	 * @return mixed|void
	 */
	private function make_footer( $config, $override = '' ) {
		if ( ! empty( $override ) ) {
			$footer = $override;
		} else {
			$footer = '</' . $config->itemsWrapper . '>';
		}

		return \apply_filters( 'ctx_make_xml_footer', $footer, $config );
	}

	/**
	 * @param $feed
	 *
	 * @return array|string|string[]
	 */
	private function remove_header_footer( $feed ) {
		return \str_replace(
			[ '<?xml version="1.0" encoding="utf-8"?>', '<?xml version="1.0"?>', '<products>', '</products>' ],
			'',
			$feed
		);
	}

}
