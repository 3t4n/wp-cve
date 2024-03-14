<?php
namespace shellpress\v1_4_0\src\Components\External;

/**
 * @author jakubkuranda@gmail.com
 * Date: 08.09.2017
 * Time: 14:41
 */

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use shellpress\v1_4_0\src\Shared\Components\IComponent;
use ZipArchive;

class UtilityHandler extends IComponent {

	/**
	 * Called on handler construction.
	 *
	 * @return void
	 */
	protected function onSetUp() {
		// TODO: Implement onSetUp() method.
	}

    /**
     * Merge two arrays without structure changing and overriding values
     *
     * @param array $array1     base array
     * @param array $array2     additional array with more keys/values
     *
     * @return array - merged array
     */
    public function arrayMergeRecursiveDistinctSafe( &$array1, &$array2 ){

        $merged = $array1;

        foreach( $array2 as $key => &$value ){

            // If both values are arrays
            if ( is_array( $value ) && isset( $merged[$key] ) && is_array( $merged[$key] ) ){

                $merged[$key] = $this->arrayMergeRecursiveDistinctSafe( $merged[$key], $value );

            } else if( ! isset( $merged[$key] ) ) {

                $merged[$key] = $value;

            }

        }

        return $merged;
    }

    /**
     * Gets value of multidimensional array by given array of keys.
     *
     * @param array         $array
     * @param array|string  $keys
     * @param mixed|null    $defaultValue
     *
     * @return mixed|null
     */
    public function getValueByKeysPath( $array, $keys, $defaultValue = null ) {

    	$value = (array) $array;

        foreach( (array) $keys as $key ) {

        	if( is_array( $value ) && $key && isset( $value[$key] ) ){
        		$value = $value[$key];
	        } else {
        		return $defaultValue;
	        }

        }

        return $value;

    }

    /**
     * Injects value to multidimensional array by given array of keys.
     * Returns changed array.
     *
     * @param array $array
     * @param array $keys
     * @param mixed $value
     *
     * @return array
     */
    public function setValueByKeysPath( $array, $keys, $value ) {

        for( $i =& $array; $key = array_shift( $keys ); $i =& $i[$key] ){
            if( ! isset( $i[$key] ) ) $i[$key] = array();
        }

        $i = $value;

        return $array;

    }

    /**
     * Generates CSV formatted string from array.
     *
     * @return string|false
     */
    public function arrayToCSV( $array, $hasHeaderRow = true, $colSep = ",", $rowSep = "\n", $qut = '"') {

        $output = '';

        if ( ! is_array( $array ) or ! is_array( $array[0] ) ) return false;

        //  Header row.
        if ( $hasHeaderRow ){

            foreach( $array[0] as $key => $val ){

                //  Escaping quotes.
                $key = str_replace( $qut, "$qut$qut", $key );
                $output .= "$colSep$qut$key$qut";

            }

            $output = substr( $output, 1 ) . $rowSep;
        }

        //  Data rows.
        foreach( $array as $key => $val ){

            $tmp = '';

            foreach( $val as $cell_key => $cell_val ){

                //  Escaping quotes.
                $cell_val = str_replace( $qut, "$qut$qut", $cell_val );
                $tmp .= "$colSep$qut$cell_val$qut";

            }

            $output .= substr( $tmp, 1 ) . $rowSep;

        }

        return $output;
    }

    /**
     * Returns var_export() wrapped with <pre> tag.
     *
     * @param mixed $var
     *
     * @return string
     */
    public function getFormattedVarExport( $var ) {

        return sprintf( '<pre>%1$s</pre>', var_export( $var, true ) );

    }

	/**
	 * @param string    $srcPath - path to file or dir
	 * @param string    $newFilePath - should contain name of the file with .zip extension
	 * @param bool      $withContainer - should zipped directory contain itself?
	 *
	 * @return bool
	 */
	public function zipData( $srcPath, $newFilePath, $withContainer = false ) {

		if( ! extension_loaded( 'zip' ) || ! file_exists( $srcPath ) ) return false;

		//  Delete file, if already exists.
		if( file_exists( $newFilePath ) ) unlink( $newFilePath );

		$zip = new ZipArchive();

		if( $zip->open( $newFilePath, ZIPARCHIVE::CREATE ) ){

			$srcPath = realpath( $srcPath );

			if( is_dir( $srcPath ) ){

				//  This will be cut off from all relative paths.
				$cutOffPath = $withContainer ? dirname( $srcPath ) : $srcPath;

				if( $withContainer ){
					$zip->addEmptyDir( basename( $srcPath ) );
				}

				$iterator = new RecursiveDirectoryIterator( $srcPath );

				// skip dot files while iterating
				$iterator->setFlags( RecursiveDirectoryIterator::SKIP_DOTS );

				$files          = new RecursiveIteratorIterator( $iterator, RecursiveIteratorIterator::SELF_FIRST );
				$forbiddenPaths = array();

				foreach( $files as $file ){

					$file       = realpath( $file );
					$fileName   = basename( $file );

					//  ----------------------------------------
					//  Check if file is not in forbidden path
					//  ----------------------------------------

					if( strpos( $fileName, '.' ) === 0 ){
						$forbiddenPaths[] = $file;
						continue;
					}

					foreach( $forbiddenPaths as $forbiddenPath ){
						if( strpos( $file, $forbiddenPath ) !== false ) continue 2;
					}

					//  ----------------------------------------
					//  Add to zip
					//  ----------------------------------------

					if( is_dir( $file ) ){
						$zip->addEmptyDir( str_replace( $cutOffPath . '/', '', $file . '/' ) );
					} else if( is_file( $file ) ){
						$zip->addFile( $file, str_replace( $cutOffPath . '/', '', $file ) );
					}

				}

			} else if( is_file( $srcPath ) ){
				$zip->addFromString( basename( $srcPath ), file_get_contents( $srcPath ) );
			}
		}

		return $zip->close();

	}

	/**
	 * Works with multidimensional array.
	 * Returns array of found values by searching by given key.
	 *
	 * @param mixed  $list
	 * @param string $searchKey
	 *
	 * @return mixed[]
	 */
	public function listPluck( $list, $searchKey ) {

		$results = array();

		if( is_array( $list ) ){

			foreach( $list as $key => $row ){

				if( $key === $searchKey ){
					$results[] = $row;
				} else {
					$moreResults = $this->listPluck( $row, $searchKey );
					$results = array_merge( $results, $moreResults );
				}

			}

		}

		return $results;

	}

}