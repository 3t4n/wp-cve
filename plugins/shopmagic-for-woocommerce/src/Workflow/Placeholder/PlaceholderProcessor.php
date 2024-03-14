<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use WPDesk\ShopMagic\Exception\ResourceNotFound;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;

/**
 * Process string and delegate any variable creation to subsequent classes.
 */
final class PlaceholderProcessor {
	private const PARAM_SEPARATOR       = ',';
	private const PARAM_VALUE_SEPARATOR = ':';
	private const PARAM_VALUE_WRAP      = "'";
	private const PARAMS_SEPARATOR      = '|';

	private const PLACEHOLDER_REGEX = '/{{[ ]*([^}]+)[ ]*}}/';

	/** @var DataLayer */
	private $data_layer;

	/** @var PlaceholdersList */
	private $placeholders_list;

	/** @var LoggerInterface */
	private $logger;

	public function __construct( PlaceholdersList $placeholders_list, LoggerInterface $logger ) {
		$this->placeholders_list = $placeholders_list;
		$this->logger            = $logger;
	}

	public function set_data_layer( DataLayer $data_layer ): void {
		$this->data_layer = $data_layer;
	}

	/**
	 * @TODO: Process method should directly receive DataLayer.
	 */
	public function process( string $message ): string {
		$replacement_count = 0;
		do {
			$message = preg_replace_callback(
				self::PLACEHOLDER_REGEX,
				function ( $matches ): string {
					$full_placeholder = $matches[1] ?? null;
					if ( $full_placeholder === null ) {
						return '';
					}

					return $this->process_single_placeholder( $full_placeholder );
				},
				$message,
				1,
				$replacement_count
			);

			if ( $message === null ) {
				return '';
			}
		} while ( $replacement_count > 0 );

		return $message;
	}

	private function process_single_placeholder( string $full_placeholder ): string {
		@list( $placeholder_slug, $params_string ) = array_map( //phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			'trim',
			explode( self::PARAMS_SEPARATOR, $full_placeholder, 2 )
		);

		if ( isset( $this->placeholders_list[ $placeholder_slug ] ) ) {
			$placeholder = $this->placeholders_list[ $placeholder_slug ];
			$placeholder->set_provided_data( $this->data_layer );

			try {
				return $placeholder->value( $this->extract_parameters( $params_string ) );
			} catch ( ResourceNotFound $e ) {
				$this->logger->warning(
					'Requested placeholder `{slug}` could not be processed. Provided data did not fulfill group `{group}`. Try to use different placeholder or use automation event which provides required data.',
					[
						'slug'      => $placeholder_slug,
						'group'     => $placeholder->get_group_slug(),
						'exception' => $e->getMessage(),
					]
				);
			} catch ( \Exception $e ) {
				$this->logger->error(
					'Failed to process placeholder {slug}.',
					[
						'slug'      => $placeholder_slug,
						'exception' => $e->getMessage(),
					]
				);
			}
		}

		return '';
	}

	/**
	 * @param string|null $params_string
	 *
	 * @return array<string, string>
	 */
	private function extract_parameters( ?string $params_string ): array {
		if ( $params_string === null ) {
			return [];
		}

		if ( trim( $params_string ) === '' ) {
			return [];
		}

		$params = [];
		$pos    = - 1;
		do {
			++$pos;
			$param_separator_pos = strpos( $params_string, self::PARAM_VALUE_SEPARATOR, $pos );
			if ( $param_separator_pos === false ) {
				$pos = $this->next_param_position( $params_string, $pos );
				continue;
			}
			$param_name            = trim( substr( $params_string, $pos, $param_separator_pos - $pos ) );
			$param_value_start_pos = strpos( $params_string, self::PARAM_VALUE_WRAP, $param_separator_pos );
			if ( $param_value_start_pos === false ) {
				$pos = $this->next_param_position( $params_string, $pos );
				continue;
			}
			$param_value_end_pos = strpos( $params_string, self::PARAM_VALUE_WRAP, $param_value_start_pos + 1 );
			if ( $param_value_end_pos === false ) {
				$pos = $this->next_param_position( $params_string, $pos );
				continue;
			}
			$param_value           = trim(
				substr(
					$params_string,
					$param_value_start_pos + 1,
					$param_value_end_pos - $param_value_start_pos - 1
				)
			);
			$params[ $param_name ] = $param_value;
			$pos                   = $this->next_param_position( $params_string, $param_value_end_pos );
		} while ( $pos !== false );

		return $params;
	}

	/** @return int|false */
	private function next_param_position( string $params_string, int $initial_position ) {
		return strpos( $params_string, self::PARAM_SEPARATOR, $initial_position );
	}
}
