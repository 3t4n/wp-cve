<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\Utilities\File;

/**
 * https://stackoverflow.com/questions/1334613/how-to-recursively-zip-a-directory-in-php
 */
class ZipDir {

	public static function IsSupported() :bool {
		return \extension_loaded( 'zip' ) && @\class_exists( 'ZipArchive' );
	}

	public function run( string $sourceDir, string $targetZip ) :bool {
		if ( !self::IsSupported() || !\file_exists( $sourceDir ) ) {
			return false;
		}

		$zip = new \ZipArchive();
		if ( !$zip->open( $targetZip, \ZIPARCHIVE::CREATE ) ) {
			return false;
		}

		$sourceDir = \str_replace( '\\', '/', \realpath( $sourceDir ) );

		if ( \is_dir( $sourceDir ) === true ) {
			$oFileIT = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator( $sourceDir ),
				\RecursiveIteratorIterator::SELF_FIRST
			);

			foreach ( $oFileIT as $file ) {
				$file = \str_replace( '\\', '/', $file );

				// Ignore "." and ".." folders
				if ( \in_array( \substr( $file, \strrpos( $file, '/' ) + 1 ), [ '.', '..' ] ) ) {
					continue;
				}

				$file = \realpath( $file );

				if ( \is_dir( $file ) === true ) {
					$zip->addEmptyDir( \str_replace( $sourceDir.'/', '', $file.'/' ) );
				}
				elseif ( \is_file( $file ) === true ) {
					$zip->addFromString( \str_replace( $sourceDir.'/', '', $file ), \file_get_contents( $file ) );
				}
			}
		}
		elseif ( is_file( $sourceDir ) === true ) {
			$zip->addFromString( \basename( $sourceDir ), \file_get_contents( $sourceDir ) );
		}

		return $zip->close();
	}
}
