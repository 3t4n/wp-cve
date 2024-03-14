<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\Ops\ZipDownload;

class Download extends Base {

	/**
	 * @throws \Exception
	 */
	public function byID( string $id ) {
		$FS = $this->loadFS();

		$id = sanitize_key( $id ); // no funky biz
		if ( empty( $id ) ) {
			throw new \Exception( 'No ID provided.' );
		}

		$file = realpath( path_join( $this->getZipsDir(), $id.'.zip' ) );
		if ( empty( $file ) || !$FS->exists( $file ) ) {
			throw new \Exception( 'File does not exist.' );
		}

		$this->sendFile( $file );
		$FS->deleteFile( $file );
		die();
	}

	private function sendFile( $sFile ) {
		header( "Pragma: public" );
		header( "Expires: 0" );
		header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header( "Cache-Control: public" );
		header( "Content-Description: File Transfer" );
		header( "Content-type: application/octet-stream" );
		header( 'Content-Disposition: attachment; filename="'.basename( $sFile ).'"' );
		header( "Content-Transfer-Encoding: binary" );
		header( "Content-Length: ".filesize( $sFile ) );
		@readfile( $sFile );
	}
}
