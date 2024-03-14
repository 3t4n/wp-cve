<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\Ops\Log;

class ToFile extends \ICWP_APP_Foundation {

	public static function LogIt( string $content, string $logFileSuffix = '' ) :bool {
		global $g_oWorpit;
		$file = path_join(
			$g_oWorpit->getController()->getRootDir(),
			sprintf( 'tmp/tmplog%s.txt', empty( $logFileSuffix ) ? '' : '-'.$logFileSuffix )
		);
		return !empty( file_put_contents( $file, $content."\n", FILE_APPEND ) );
	}
}