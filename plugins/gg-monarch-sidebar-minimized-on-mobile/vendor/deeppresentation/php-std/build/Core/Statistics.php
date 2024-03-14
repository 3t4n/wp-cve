<?php namespace MSMoMDP\Std\Core;

class Statistics {

	private $statTable;
	private $statProps;
	public function __construct( $subjectsId, array $statProperty ) {
		$subjectsId = Arr::as_array( $subjectsId );

		$this->statTable = array();
		$this->statProps = array();
		foreach ( $statProperty as $statPropName => $statPropCfg ) {
			if ( function_exists( $statPropCfg['fce'] ) ) {
				$this->statProps[ $statPropName ] = $statPropCfg;
				if ( ! isset( $this->statProps[ $statPropName ]['isNumeric'] ) ) {
					$this->statProps[ $statPropName ]['isNumeric'] = true;
				}
			}
		}
		foreach ( $subjectsId as $id ) {
			$this->statTable[ $id ] = array();
			foreach ( $statProperty as $statPropName => $statPropCfg ) {
				if ( array_key_exists( $statPropName, $this->statProps ) ) {
					$this->statTable[ $id ][ $statPropName ] = $statPropCfg['initVal'] ?? 0;
				}
			}
		}
	}


	public function insertData( array $data ) {
		if ( $data && is_array( $data ) ) {
			foreach ( $data as $statSubjectId => $newVal ) {
				if ( array_key_exists( $statSubjectId, $this->statTable ) ) {
					foreach ( $this->statProps as $statPropName => $statPropCfg ) {
						$this->processValue( $statPropName, $statPropCfg, $statSubjectId, $newVal );
					}
				}
			}
		}
	}

	private function processValue( $statPropName, $statPropCfg, $statSubjectId, $newVal ) {
		if ( $statPropCfg['isNumeric'] ) {
			if ( is_string( $newVal ) && strlen( $newVal ) > 0 ) {
				$firstChar = $newVal[0];
				$newVal    = preg_replace( '/[^0-9]/', '', $newVal );
				if ( $firstChar == '-' ) {
					$newVal = $firstChar . $newVal;
				}
				if ( ! is_numeric( $newVal ) ) {
					return;
				}
				$res = call_user_func( $statPropCfg['fce'], $this->statTable[ $statSubjectId ][ $statPropName ], $newVal );
				if ( isset( $res ) ) {
					$this->statTable[ $statSubjectId ][ $statPropName ] = $res;
				}
			}
		} else {
			$this->statTable[ $statSubjectId ][ $statPropName ] = call_user_func( $statPropCfg['fce'], $this->statTable[ $statSubjectId ][ $statPropName ], $newVal );
		}
	}

	public function report_as_array() {
		 return $this->statTable;
	}

	public function report_as_json() {
		return json_encode( $this->statTable );
	}
}
