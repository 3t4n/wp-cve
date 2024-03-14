<?php

namespace FSPoster\App\Providers;

class FSCodeUpdater
{
	private static $sql = [
		'10000'     => '1.0.0', // v1.0.0
	];

	public function __construct ()
	{
		$from = $this->getIntVersion( Helper::getInstalledVersion() );
		$to   = $this->getIntVersion( Helper::getVersion() );

		if ( $to > $from && ! empty( Helper::getOption( 'access_token', '', TRUE ) ) )
		{
            foreach ( self::getSQL( $from, $to ) as $query )
			{
                if ( ! empty( trim( $query ) ) )
                {
                    DB::DB()->query( $query );
                }
			}

			$plgnVer = Helper::getOption( 'poster_plugin_installed', '0', TRUE );

			if ( empty( $plgnVer ) )
			{
				Helper::setOption( 'poster_plugin_installed', Helper::getVersion(), TRUE );
			}
		}
	}

	private function getIntVersion ( $version )
	{
		$version_nums = explode( '.', $version );

		return intval( self::zero_pad( $version_nums[ 0 ] ) . self::zero_pad( $version_nums[ 1 ] ) . self::zero_pad( $version_nums[ 2 ] ) );
	}

	private static function zero_pad ( $version )
	{
		return str_pad( $version[ 0 ], '2', '0', STR_PAD_LEFT );
	}

	public static function getSQL ( $from, $to )
	{
        $canUpdate     = FALSE;
		$getFirstIndex = 0;

        foreach ( array_keys( self::$sql ) as $i => $key )
        {
            if ( $key > $from )
            {
                $canUpdate     = TRUE;
                $getFirstIndex = $i;
                break;
            }
        }

		$sql = [];

		if ( $canUpdate )
		{
			for ( $i = $getFirstIndex; $i < count( self::$sql ); $i++ )
			{
				$filePath = FSPL_ROOT_DIR . '/sqls/v' . array_values( self::$sql )[ $i ] . '.sql';

				if ( file_exists( $filePath ) )
				{
					$content = str_replace( [
						'{tableprefix}',
					], [
						DB::DB()->base_prefix . DB::PLUGIN_DB_PREFIX,
					], file_get_contents( $filePath ) );

					$queries = explode( ';', $content );

					foreach ( $queries as $query )
					{
						$sql[] = $query;
					}
					//unlink( $filePath );
				}
			}
		}

		return $sql;
	}
}
