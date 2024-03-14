<?php

namespace QT_Importer;

class Utils {
	public function _lang_map( $code ) {
		switch ( $code ) {
			case 'zh':
				$code = 'zh-hans';
				break;
			case 'pt':
				$code = 'pt-pt';
				break;
			case 'se':
				$code = 'sv';
				break;
			case 'iw':
				$code = 'he';
				break;
			case 'No':
				$code = 'nb';
				break;
			case 'cz':
				$code = 'cs';
				break;
			case 'gr':
				$code = 'el';
				break;
		}

		return strtolower( $code );
	}
}
