<?php
/**
 * Functions to map and format WPCF7 fields to Notion's ones.
 *
 * @package add-on-cf7-for-notion
 */

namespace WPC_WPCF7_NTN\Fields;

defined( 'ABSPATH' ) || exit;

// ************************************
// *** WPCF7 > Notion field mapping ***
// ************************************

/**
 * Map WPCF7 text field.
 *
 * @param array $fields The supported WPCF7/Notion fields.
 * @return array
 */
function map_wpcf7_text( $fields ) {
	$fields['text'] = array(
		'title'        => __NAMESPACE__ . '\notion_title_format',
		'text'         => __NAMESPACE__ . '\notion_text_format',
		'rich_text'    => __NAMESPACE__ . '\notion_rich_text_format',
		'number'       => __NAMESPACE__ . '\notion_number_format',
		'url'          => __NAMESPACE__ . '\notion_url_format',
		'email'        => __NAMESPACE__ . '\notion_email_format',
		'phone_number' => __NAMESPACE__ . '\notion_phone_number_format',
		'select'       => __NAMESPACE__ . '\notion_select_format',
		'multi_select' => array(
			__NAMESPACE__ . '\explode_values_comma',
			__NAMESPACE__ . '\notion_multi_select_format',
		),
	);
	return $fields;
}

/**
 * Map WPCF7 email field.
 *
 * @param array $fields The supported WPCF7/Notion fields.
 * @return array
 */
function map_wpcf7_email( $fields ) {
	$fields['email'] = array(
		'title'     => __NAMESPACE__ . '\notion_title_format',
		'text'      => __NAMESPACE__ . '\notion_text_format',
		'rich_text' => __NAMESPACE__ . '\notion_rich_text_format',
		'email'     => __NAMESPACE__ . '\notion_email_format',
	);
	return $fields;
}

/**
 * Map WPCF7 url field.
 *
 * @param array $fields The supported WPCF7/Notion fields.
 * @return array
 */
function map_wpcf7_url( $fields ) {
	$fields['url'] = array(
		'title'     => __NAMESPACE__ . '\notion_title_format',
		'text'      => __NAMESPACE__ . '\notion_text_format',
		'rich_text' => __NAMESPACE__ . '\notion_rich_text_format',
		'url'       => __NAMESPACE__ . '\notion_url_format',
	);
	return $fields;
}

/**
 * Map WPCF7 tel field.
 *
 * @param array $fields The supported WPCF7/Notion fields.
 * @return array
 */
function map_wpcf7_tel( $fields ) {
	$fields['tel'] = array(
		'title'        => __NAMESPACE__ . '\notion_title_format',
		'text'         => __NAMESPACE__ . '\notion_text_format',
		'rich_text'    => __NAMESPACE__ . '\notion_rich_text_format',
		'phone_number' => __NAMESPACE__ . '\notion_phone_number_format',
	);
	return $fields;
}

/**
 * Map WPCF7 number field.
 *
 * @param array $fields The supported WPCF7/Notion fields.
 * @return array
 */
function map_wpcf7_number( $fields ) {
	$fields['number'] = array(
		'title'     => __NAMESPACE__ . '\notion_title_format',
		'text'      => __NAMESPACE__ . '\notion_text_format',
		'rich_text' => __NAMESPACE__ . '\notion_rich_text_format',
		'number'    => __NAMESPACE__ . '\notion_number_format',
	);
	return $fields;
}

/**
 * Map WPCF7 range field.
 *
 * @param array $fields The supported WPCF7/Notion fields.
 * @return array
 */
function map_wpcf7_range( $fields ) {
	$fields['range'] = array(
		'title'     => __NAMESPACE__ . '\notion_title_format',
		'text'      => __NAMESPACE__ . '\notion_text_format',
		'rich_text' => __NAMESPACE__ . '\notion_rich_text_format',
		'number'    => __NAMESPACE__ . '\notion_number_format',
	);
	return $fields;
}

/**
 * Map WPCF7 date field.
 *
 * @param array $fields The supported WPCF7/Notion fields.
 * @return array
 */
function map_wpcf7_date( $fields ) {
	$fields['date'] = array(
		'date' => array(
			__NAMESPACE__ . '\to_datetime',
			__NAMESPACE__ . '\notion_date_format',
		),
	);
	return $fields;
}

/**
 * Map WPCF7 textarea field.
 *
 * @param array $fields The supported WPCF7/Notion fields.
 * @return array
 */
function map_wpcf7_textarea( $fields ) {
	$fields['textarea'] = array(
		'title'     => __NAMESPACE__ . '\notion_title_format',
		'text'      => __NAMESPACE__ . '\notion_text_format',
		'rich_text' => __NAMESPACE__ . '\notion_rich_text_format',
	);
	return $fields;
}

/**
 * Map WPCF7 select field.
 *
 * @param array $fields The supported WPCF7/Notion fields.
 * @return array
 */
function map_wpcf7_select( $fields ) {
	$fields['select'] = array(
		'title'        => __NAMESPACE__ . '\notion_title_format',
		'text'         => __NAMESPACE__ . '\notion_text_format',
		'rich_text'    => __NAMESPACE__ . '\notion_rich_text_format',
		'select'       => array(
			__NAMESPACE__ . '\flatten_values',
			__NAMESPACE__ . '\notion_select_format',
		),
		'multi_select' => __NAMESPACE__ . '\notion_multi_select_format',
	);
	return $fields;
}

/**
 * Map WPCF7 checkbox field.
 *
 * @param array $fields The supported WPCF7/Notion fields.
 * @return array
 */
function map_wpcf7_checkbox( $fields ) {
	$fields['checkbox'] = array(
		'title'        => __NAMESPACE__ . '\notion_title_format',
		'text'         => __NAMESPACE__ . '\notion_text_format',
		'rich_text'    => __NAMESPACE__ . '\notion_rich_text_format',
		'select'       => array(
			__NAMESPACE__ . '\flatten_values',
			__NAMESPACE__ . '\notion_select_format',
		),
		'multi_select' => __NAMESPACE__ . '\notion_multi_select_format',
	);
	return $fields;
}

/**
 * Map WPCF7 radio field.
 *
 * @param array $fields The supported WPCF7/Notion fields.
 * @return array
 */
function map_wpcf7_radio( $fields ) {
	$fields['radio'] = array(
		'title'        => __NAMESPACE__ . '\notion_title_format',
		'text'         => __NAMESPACE__ . '\notion_text_format',
		'rich_text'    => __NAMESPACE__ . '\notion_rich_text_format',
		'select'       => array(
			__NAMESPACE__ . '\flatten_values',
			__NAMESPACE__ . '\notion_select_format',
		),
		'multi_select' => __NAMESPACE__ . '\notion_multi_select_format',
	);
	return $fields;
}

/**
 * Map WPCF7 acceptance field.
 *
 * @param array $fields The supported WPCF7/Notion fields.
 * @return array
 */
function map_wpcf7_acceptance( $fields ) {
	$fields['acceptance'] = array(
		'title'     => __NAMESPACE__ . '\notion_title_format',
		'text'      => __NAMESPACE__ . '\notion_text_format',
		'rich_text' => __NAMESPACE__ . '\notion_rich_text_format',
	);
	return $fields;
}

/**
 * Map WPCF7 file upload field.
 *
 * @param array $fields The supported WPCF7/Notion fields.
 * @return array
 */
function map_wpcF7_files($fields)
{
	$fields['file'] = array(
		'files' => array(
			__NAMESPACE__ . '\save_files',
			__NAMESPACE__ . '\notion_files_format',
		)
	);
	return $fields;
}

// ****************************
// *** WPCF7 pre-formatters ***
// ****************************

/**
 * Explodes string value and returns an array. Returns false for empty field value.
 *
 * @param mixed $field_value The WPCF7 field value.
 * @return false|string[]
 */
function explode_values_comma( $field_value ) {
	if ( empty( $field_value ) ) {
		return false;
	}
	$array_value = explode( ', ', $field_value );
	return $array_value;
}

/**
 * Converts an array into a string separated by semicolon.
 * (we don't use commas because they are not compatible with select / multi_select Notion's fields).
 *
 * @param mixed $field_value The WPCF7 field value.
 * @return mixed|string
 */
function flatten_values( $field_value ) {
	if ( is_array( $field_value ) ) {
		$field_value = implode( ' ; ', $field_value );
	}

	return $field_value;
}

/**
 * Converts date string (e.g 2022-02-24) to a DateTime object.
 *
 * @param mixed $date_field_value The WPCF7 date field value.
 * @return \DateTime|false
 */
function to_datetime( $date_field_value ) {
	return date_create_from_format( '!Y-m-d', $date_field_value );
}

/**
 * Copies files from $filepaths to a public directory, returning their public URLs, or null if the copy failed.
 * The files are scheduled for deletion after the form has been processed.
 *
 * @param string[] $filepaths The file paths saved by Contact Form 7.
 * @return null|string[]
 */
function save_files( $filepaths ) {
	if ( empty( $filepaths ) ) {
		return null;
	}

	$upload_dir           = wp_upload_dir();
	$wpc_notion_dirname = $upload_dir['basedir'] . '/wpc_wpcf7_notion_uploads';
	
	if ( ! is_dir( $wpc_notion_dirname ) ) {
		if ( ! wp_mkdir_p( $wpc_notion_dirname ) ) {
			return null;
		}

		$htaccess_file = path_join( $wpc_notion_dirname, '.htaccess' );

		if ( ! file_exists( $htaccess_file ) ) {
			$handle = @fopen( $htaccess_file, 'w' );
			if ( $handle ) {
				fwrite( $handle, "Options -Indexes\n" );
				fclose( $handle );
			}
		}
	}

	$fileurls = array();

	foreach ( $filepaths as $index => $filepath ) {
		$time_now        = time();
		$uuid            = wp_generate_uuid4();
		$unique_filename = wp_unique_filename( $wpc_notion_dirname, $time_now . '-' . $uuid . '-' . wp_basename( $filepath ) );
		$new_filepath    = $wpc_notion_dirname . '/' . $unique_filename;
	
		if ( ! copy( $filepath, $new_filepath ) ) {
			$filepaths[ $index ] = null;
		} else {
			$filepaths[ $index ] = $new_filepath;
			$fileurls[ $index ] = str_replace( trailingslashit( ABSPATH ), trailingslashit( home_url() ), $new_filepath );
		}
	}
	
	$filepaths = array_filter( $filepaths );
	$fileurls = array_filter( $fileurls );

	return $fileurls;
}


// **********************************
// *** Notion's fields formatters ***
// **********************************

/**
 * Format a field value to a Notion's title field format.
 *
 * @param mixed $field_value The WPCF7 field value.
 * @return false|\array[][][]
 */
function notion_title_format( $field_value ) {
	if ( empty( $field_value ) && '0' !== $field_value ) {
		return false;
	}
	return array( 'title' => array( array( 'text' => array( 'content' => $field_value ) ) ) );
}

/**
 * Format a field value to a Notion's text field format.
 *
 * @param mixed $field_value The WPCF7 field value.
 * @return false|\array[][][]
 */
function notion_text_format( $field_value ) {
	if ( empty( $field_value ) && '0' !== $field_value ) {
		return false;
	}
	return array( 'text' => array( array( 'text' => array( 'content' => $field_value ) ) ) );
}

/**
 * Format a field value to a Notion's rich_text field format.
 *
 * @param mixed $field_value The WPCF7 field value.
 * @return false|\array[][][]
 */
function notion_rich_text_format( $field_value ) {
	if ( empty( $field_value ) && '0' !== $field_value ) {
		return false;
	}
	return array( 'rich_text' => array( array( 'text' => array( 'content' => $field_value ) ) ) );
}

/**
 * Format a field value to a Notion's number field format.
 *
 * @param mixed $field_value The WPCF7 field value.
 * @return object
 */
function notion_number_format( $field_value ) {
	if ( '' === $field_value ) {
		return false;
	}
	return (object) array( 'number' => (float) $field_value );
}

/**
 * Format a field value to a Notion's url field format.
 *
 * @param mixed $field_value The WPCF7 field value.
 * @return false|object
 */
function notion_url_format( $field_value ) {
	if ( empty( $field_value ) ) {
		return false;
	}
	return (object) array( 'url' => $field_value );
}

/**
 * Format a field value to a Notion's email field format.
 *
 * @param mixed $field_value The WPCF7 field value.
 * @return false|object
 */
function notion_email_format( $field_value ) {
	if ( empty( $field_value ) ) {
		return false;
	}
	return (object) array( 'email' => $field_value );
}

/**
 * Format a field value to a Notion's phone_number field format.
 *
 * @param mixed $field_value The WPCF7 field value.
 * @return false|object
 */
function notion_phone_number_format( $field_value ) {
	if ( empty( $field_value ) ) {
		return false;
	}
	return (object) array( 'phone_number' => $field_value );
}

/**
 * Format a field value to a Notion's select field format.
 *
 * @param mixed $field_value The WPCF7 field value.
 * @return false|object
 */
function notion_select_format( $field_value ) {
	if ( empty( $field_value ) ) {
		return false;
	}
	// Commas are not allowed in select value.
	$field_value = str_replace( ',', ';', $field_value );
	return (object) array( 'select' => array( 'name' => $field_value ) );
}

/**
 * Format a field value to a Notion's multi_select field format.
 *
 * @param mixed $array_value The WPCF7 field value.
 * @return false|object
 */
function notion_multi_select_format( $array_value ) {
	if ( empty( $array_value ) ) {
		return false;
	}

	if ( is_string( $array_value ) ) {
		$array_value = array( $array_value );
	}

	// Remove empty values.
	$array_value = array_filter( $array_value );

	if ( empty( $array_value ) ) {
		return false;
	}

	return (object) array(
		'multi_select' => array_map(
			function( $value ) {
				// Commas are not allowed in select value.
				$value = str_replace( ',', ';', $value );
				return array( 'name' => $value );
			},
			$array_value
		),
	);
}

/**
 * Format a field value to a Notion's date field format.
 *
 * @param \DateTimeInterface|bool $field_datetime_value The WPCF7 formatted field value.
 * @return false|object
 */
function notion_date_format( $field_datetime_value ) {
	if ( ! is_subclass_of( $field_datetime_value, 'DateTimeInterface' ) ) {
		return false;
	}
	return (object) array( 'date' => array( 'start' => $field_datetime_value->format( \DateTime::ISO8601 ) ) );
}

/**
 * Format a field value to a Notion's file field format.
 *
 * @param array $field_value Array of URLs for uploaded files.
 * @return false|array
 */
function notion_files_format($field_value)
{
	if ( empty( $field_value ) ) {
		return null;
	}

	$files = [];
    foreach($field_value as $file_url){
        $files[] = array(
            'type' => 'external',
            'name' => wp_basename($file_url),
            'external' => array(
                'url' => $file_url
            )
        );
    }

    return array('files' => $files);
}