<?php

namespace WilokeEmailCreator\Shared;

use Exception;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;

class Assert {
	/**
	 * @throws Exception
	 */
	public static function isJson( string $value ): bool {
		json_decode( $value );
		if ( json_last_error() === JSON_ERROR_NONE ) {
			return true;
		}

		throw new Exception( esc_html__( 'The data is not json format', 'emailcreator' ) );
	}

	public static function perform( $aAssert, $value ): array {
		try {
			$func = $aAssert['callbackFunc'];
			$msg  = $aAssert['message'] ?? '';

			switch ( $func ) {
				case 'notEmpty':
				case 'isEmpty':
				case 'true':
				case 'false':
				case 'notFalse':
				case 'null':
					call_user_func( [ '\Webmozart\Assert\Assert', $func ], $value, $msg );
					break;
				case 'eq':
				case 'same':
				case 'notEq':
				case 'greaterThan':
				case 'greaterThanEq':
				case 'lessThan':
				case 'lessThanEq':
					$compare = $aAssert['expected'];
					call_user_func( [ '\Webmozart\Assert\Assert', $func ], $value, $compare, $msg );
					break;
				case 'inArray':
					if (!in_array($value, $aAssert['expected'])) {
						throw new Exception(
							"The value must be one of the following value " . implode(", ",
								$aAssert['expected'])
						);
					}
					break;
				case 'isJson':
					self::isJson( $value );
					break;
			}

			return MessageFactory::factory()->success( esc_html__( 'The data has been validated and it\'s correct.',
				'emailcreator' ) );
		}
		catch ( Exception $oException ) {
			return MessageFactory::factory()->error( $oException->getMessage(), $oException->getCode() );
		}
	}
}
