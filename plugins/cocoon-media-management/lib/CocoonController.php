<?php

class CMM_Cocoon {
	function __construct() {
		$this->thumbTypes = $this->getThumbTypes();
		$this->subDomain  = get_option( 'cmm_stng_domain' );
		$this->thumbsPerPage  = get_option( 'cmm_thumbs_per_page' ) ? (int)get_option( 'cmm_thumbs_per_page' ) : 1;
	}

	public static $domainName = 'use-cocoon.nl';

	public $subDomain;

	public $thumbsPerPage;

	public $thumbTypes;

	public static function SoapClient( $reqId ) {
		$subDomain  = get_option( 'cmm_stng_domain' );
		$domainName = self::$domainName;
		$username   = get_option( 'cmm_stng_username' );
		$requestId  = $reqId;
		$secretkey  = get_option( 'cmm_stng_secret' );
		$wsdl       = "https://{$subDomain}.{$domainName}/webservice/wsdl";

		$hash = sha1( $subDomain . $username . $requestId . $secretkey );

		$oAuth            = new stdClass;
		$oAuth->username  = $username;
		$oAuth->requestId = $requestId;
		$oAuth->hash      = $hash;

		if ( ! extension_loaded( 'soap' ) ) {
			throw new Exception( 'PHP_Soap extension is not installed, please contact your server administrator' );
		}
		$oSoapClient = new SoapClient( $wsdl );
		$SoapHeader  = new SoapHeader( 'auth', 'authenticate', $oAuth );
		$oSoapClient->__setSoapHeaders( $SoapHeader );

		return $oSoapClient;
	}

	function getThumbTypes() {
		try {
			$output = self::SoapClient( $this->getRequestId() )->getThumbtypes();
		} catch ( SoapFault $oSoapFault ) {
			$output = $oSoapFault;
		} catch(Exception $e) {
			$output = $e->getMessage();
		}

		return $output;
	}

	function getSets() {
		try {
			$output = self::SoapClient( $this->getRequestId() )->getSets();
		} catch ( SoapFault $oSoapFault ) {
			$output = $oSoapFault;
		} catch(Exception $e) {
			$output = $e->getMessage();
		}

		return $output;
	}

	function getFilesBySet( $setId ) {
		try {
			$output = self::SoapClient( $this->getRequestId() )->getFilesBySet( $setId );
		} catch ( SoapFault $oSoapFault ) {
			$output = $oSoapFault;
		} catch(Exception $e) {
			$output = $e->getMessage();
		}

		return $output;
	}

	function getFile( $fileId ) {
		try {
			$output = self::SoapClient( $this->getRequestId() )->getFile( $fileId );
		} catch ( SoapFault $oSoapFault ) {
			$output = $oSoapFault;
		} catch(Exception $e) {
			$output = $e->getMessage();
		}

		return $output;
	}

	function getThumbInfo( $aFile ) {
		$subDomain  = $this->subDomain;
		$domainName = self::$domainName;
		$url        = "https://{$subDomain}.{$domainName}";
		$thumbOrg   = 'original';
		$thumbWeb   = '400px';
		$thumbWeb2  = 'web';

		$noThumb = true;

		$aThumbTypes = $this->thumbTypes;

		$thumbOrgPath = $aThumbTypes[ $thumbOrg ]['path'];

		if ( $aThumbTypes[ $thumbWeb ]['path'] == null ) {
			if ( $aThumbTypes[ $thumbWeb2 ]['path'] == null ) {
				$thumbWebPath = $thumbOrgPath;
			} else {
				$thumbWebPath = $aThumbTypes[ $thumbWeb2 ]['path'];
			}
		} else {
			$thumbWebPath = $aThumbTypes[ $thumbWeb ]['path'];
		}

		$filename  = $aFile['filename'];
		$extention = strtolower( $aFile['extension'] );

		if ( $extention === 'jpg' ||
		     $extention === 'png'
		) {
			$noThumb = false;
		}

		$fileDim  = $aFile['width'] && $aFile['height'] ? $aFile['width'] . ' x ' . $aFile['height'] : '';
		$fileSize = $aFile['size'] ? round( $aFile['size'] / 1024 ) . ' KB' : '';

		if ( $aFile['upload_date'] ) {
			$date         = date_create( $aFile['upload_date'] );
			$fileUploaded = date_format( $date, get_option( 'date_format' ) );
		} else {
			$fileUploaded = '';
		}

		return array(
			'path'     => $url . $thumbOrgPath . '/' . $filename . '.' . $extention,
			'web'      => ! $noThumb ? $url . $thumbWebPath . '/' . $filename . '.' . $extention : '',
			'ext'      => $extention,
			'name'     => $filename,
			'dim'      => $fileDim,
			'size'     => $fileSize,
			'uploaded' => $fileUploaded,
			'domain'   => $url
		);
	}

    function getTags() {
        try {
            $output = self::SoapClient( $this->getRequestId() )->getTags();
        } catch ( SoapFault $oSoapFault ) {
            $output = $oSoapFault;
        } catch(Exception $e) {
            $output = $e->getMessage();
        }

        return $output;
    }

    function getFilesByTag($tagId) {
        try {
            $output = self::SoapClient( $this->getRequestId() )->getFilesByTag($tagId);
        } catch ( SoapFault $oSoapFault ) {
            $output = $oSoapFault;
        } catch(Exception $e) {
            $output = $e->getMessage();
        }

        return $output;
    }

	public function getRequestId() {
		return (string) microtime( true );
	}

	function getVersion() {
		try {
			$output = self::SoapClient( $this->getRequestId() )->getVersion();
		} catch ( SoapFault $oSoapFault ) {
			$output = $oSoapFault;
		} catch(Exception $e) {
			$output = $e;
		}

		return $output;
	}
}