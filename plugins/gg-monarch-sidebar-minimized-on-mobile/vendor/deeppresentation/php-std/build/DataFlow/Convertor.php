<?php namespace MSMoMDP\Std\DataFlow;

use MSMoMDP\Std\Core\Arr;


/**
 * Class Convertor
 *
 */

class Convertor {


	private static function capitalize( $subValue, $args ) {
		return ucfirst( $subValue );
	}

	private static function convert_by_table( $subValue, $args, &$nextConvertCustom ) {
		$convertTable = $args['convert-table'];
		if ( isset( $convertTable ) ) {
			$valueTag       = $args['value-tag'];
			$nextConvertTag = Arr::sget( $args, 'next-convert-tag' );
			if ( isset( $nextConvertTag ) ) {
				$nextConvertCustom = Arr::get( Arr::sget( $convertTable, $subValue, '' ), $nextConvertTag );
			}
			return Arr::get( Arr::sget( $convertTable, $subValue, '' ), $valueTag );
		}
		return $subValue;
	}

	private static function associative( $subValue, $args ) {
		$key = $args['key'];
		if ( isset( $key ) ) {
			return array( $key => $subValue );
		}
		return $subValue;
	}

	private static function set_custom_value( $subValue, $args ) {
		return $args['value'] ?? '';
	}

	private static function convert_sub_value( $subValue, $convertAction ) {
		$convertType       = $convertAction['convert-type'];
		$nextConvertMode   = $convertAction['next-convert'] ?? 'result';
		$args              = $convertAction['args'] ?? array();
		$nextConvertCustom = null;
		$result            = $subValue;
		switch ( $convertType ) {
			case 'capitalize':
				$result = self::capitalize( $subValue, $args );
				break;
			case 'convert-table':
				$result = self::convert_by_table( $subValue, $args, $nextConvertCustom );
				break;
			case 'associative':
				$result = self::associative( $subValue, $args );
				break;
			case 'set-custom-value':
				$result = self::set_custom_value( $subValue, $args );
				break;
			case 'custom-function':
				$args['val'] = $subValue;
				$result      = call_user_func( $convertAction['function'], $args );
				break;
		}
		switch ( $nextConvertMode ) {
			case 'result':
				$nextConvert = $result;
				break;
			case 'input':
				$nextConvert = $subValue;
				break;
			case 'custom':
				$nextConvert = $nextConvertCustom;
				break;
		}
		return array(
			'value' => $result ?? '',
			'next'  => $nextConvert,
		);
	}

	private static function convert_value( $processValue, $convertAction ) {
		$result = array();
		foreach ( $processValue as $subValue ) {
			$result[] = self::convert_sub_value( $subValue, $convertAction );
		}
		return $result;
	}

	public static function process( $value, $convertArgs ) {
		if ( isset( $value ) && isset( $convertArgs ) ) {
			$convertActions  = $convertArgs['actions'];
			$resultAssociate = $convertArgs['result-associate'] ?? 'input';
			$resultType      = $convertArgs['result-type'] ?? 'auto';
			if ( isset( $convertActions ) ) {
				$isValArray  = is_array( $value );
				$processData = $isValArray ? $value : array( $value ); // unification to array
				$actionCount = count( $convertActions );
				$i           = 0;
				$ri          = 0;
				$result      = array();
				do {
					$isAppendMode = Arr::sget( $convertActions[ $i ], 'mode' ) == 'append-to-original';
					$resultKey    = $convertActions[ $i ]['result-key'];
					$seqRresult   = self::convert_value( $processData, $convertActions[ $i ] );
					$processData  = array();

					if ( ! isset( $resultKey ) ) {
						if ( $isAppendMode && isset( $result[ $ri ] ) ) {
							$ri++;
						}

						if ( ! isset( $result[ $ri ] ) || ! $isAppendMode ) {
							$result[ $ri ] = array();
						}
						foreach ( $seqRresult as $subSeqRresult ) {
							$processData[]   = $subSeqRresult['next'];
							$result[ $ri ][] = $subSeqRresult['value'];
						}
					} else {
						foreach ( $seqRresult as $subSeqRresult ) {
							$processData[]          = $subSeqRresult['next'];
							$result[ $resultKey ][] = $subSeqRresult['value'];
						}
					}
					$i++;
				} while ( $i < $actionCount );
				// Postprocess result
				if ( $resultAssociate != 'action' ) {
					$result = Arr::transpose( $result );
				}
				switch ( $resultType ) {
					case 'string':
						return Arr::as_string( $result );
					case 'key-val':
						return  array( key( Arr::as_array( $result ) ) => Arr::as_string( $result ) );
					case 'array':
						return Arr::as_array( $result );
					default:
						return $result;
				}
			}
		}
		return $value;
	}
}
