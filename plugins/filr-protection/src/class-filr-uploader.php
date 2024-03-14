<?php
namespace filr;

class FILR_Uploader {

	/**
	 * @var array $default_options default options to set.
	 */
	private $default_options = array(
		'limit'                => null,
		'maxSize'              => null,
		'fileMaxSize'          => null,
		'extensions'           => null,
		'disallowedExtensions' => array( 'htaccess', 'php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml', 'js', 'sql', 'phar' ),
		'required'             => false,
		'uploadDir'            => '',
		'title'                => array( 'auto', 12 ),
		'replace'              => false,
		'editor'               => null,
		'listInput'            => true,
		'files'                => array(),
		'move_uploaded_file'   => null,
		'validate_file'        => null,
	);

	/**
	 * The input field.
	 *
	 * @var array|null
	 */
	private $field = null;

	/**
	 * Given options.
	 *
	 * @var array|null
	 */
	protected $options = null;

	/**
	 * __construct method
	 *
	 * @public
	 * @param $name {$_FILES key}
	 * @param $options {null, Array}
	 */
	public function __construct( $name, $options = null ) {
		$this->default_options['move_uploaded_file'] = function( $tmp, $dest ) {
			return move_uploaded_file( $tmp, $dest );
		};

		$this->default_options['extensions'] = get_allowed_mime_types();

		return $this->initialize( $name, $options );
	}

	/**
	 * initialize the uploader.
	 *
	 * @private
	 * @param $inputName {String} Input name
	 * @param $options {null, Array}
	 */
	private function initialize( $inputName, $options ): bool {
		$name       = is_array( $inputName ) ? end( $inputName ) : $inputName;
		$_FilesName = is_array( $inputName ) ? $inputName[0] : $inputName;

		// merge options
		$this->options = $this->default_options;
		if ( $options ) {
			$this->options = array_merge( $this->options, $options );
		}

		if ( ! is_array( $this->options['files'] ) ) {
			$this->options['files'] = array();
		}


		// create field array
		$this->field = array(
			'name'      => $name,
			'input'     => null,
			'listInput' => $this->read_list_input( $name )
		);

		if ( isset ( $_FILES[ $_FilesName ] ) ) {
			// set field input
			$this->field['input'] = $_FILES[ $_FilesName ];
			if ( is_array( $inputName ) ) {
				$arr = array();

				foreach( $this->field['input'] as $k => $v ) {
					$arr[ $k ] = $v[ $inputName[1] ];
				}

				$this->field['input'] = $arr;
			}

			// tranform an no-multiple input to multiple
			// made only to simplify the next uploading steps
			if ( ! is_array( $this->field['input']['name'] ) ) {
				$this->field['input'] = array_merge( $this->field['input'], array(
					"name"     => array($this->field['input']['name']),
					"tmp_name" => array($this->field['input']['tmp_name']),
					"type"     => array($this->field['input']['type']),
					"error"    => array($this->field['input']['error']),
					"size"     => array($this->field['input']['size'])
				));
			}

			// remove empty filenames
			// only for addMore option
			foreach( $this->field['input']['name'] as $key=>$value ) {
				if ( empty( $value ) ) {
					unset( $this->field['input']['name'][ $key ] );
					unset( $this->field['input']['type'][ $key ] );
					unset( $this->field['input']['tmp_name'][ $key ] );
					unset( $this->field['input']['error'][ $key] );
					unset( $this->field['input']['size'][ $key ] );
				}
			}

			// set field length (files count)
			$this->field['count'] = count( $this->field['input']['name'] );
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Call the upload_files method.
	 *
	 * @public
	 * @return array|void {Array}
	 */
	public function upload() {
		return $this->upload_files();
	}

	/**
	 * Get the list of the preloaded and uploaded files.
	 *
	 * @public
	 *
	 * @param null $custom_key
	 *
	 * @return array|mixed {null, Array}
	 */
	public function get_file_list($custom_key = null) {
		$result = array();

		if ( $custom_key != null ) {
			foreach( $this->options['files'] as $key => $value ) {
				$attribute = $this->get_file_attribute( $value, $custom_key );
				$result[]  = $attribute ?: $value['file'];
			}
		} else {
			$result = $this->options['files'];
		}

		return $result;
	}

	/**
	 * Get value from the list_input.
	 *
	 * @private
	 *
	 * @param null $name {String} FileUploader $_FILES name
	 *
	 * @return array|null {null, Array}
	 */
	private function read_list_input( $name = null ): ?array {
		$input_name = 'fileuploader-list-' . ( $name ?: $this->field['name'] );

		if( isset( $_POST[ $input_name ] ) ) {
			$input = sanitize_text_field( stripslashes( $_POST[ $input_name ] ) );
		} else {
			$input = '';
		}


		if ( empty( $input ) ) {
			$input = null;
		}

		if ($input && $this->is_json( $input ) ) {
			$list = array(
				'list'   => array(),
				'values' => wp_json_decode( $input, true )
			);

			foreach( $list['values'] as $key => $value ) {
				$list['list'][] = $value['file'];
			}

			return $list;
		}

		return null;
	}

	/**
	 * Get a list with all uploaded files.
	 *
	 * @public
	 * @return array {Array}
	 */
	public function get_uploaded_files(): array {
		$result = array();

		foreach( $this->get_file_list() as $key=>$item ) {
			if ( isset( $item['uploaded'] ) ) {
				$result[] = $item;
			}
		}

		return $result;
	}

	/**
	 * Get the file attribute.
	 *
	 * @private
	 *
	 * @param $item {Array} Item
	 * @param $attribute
	 *
	 * @return mixed|null
	 */
	private function get_file_attribute($item, $attribute) {
		$result = null;

		if ( isset( $item['data'][$attribute] ) ) {
			$result = $item['data'][ $attribute ];
		}

		if ( isset( $item[$attribute] ) ) {
			$result = $item[ $attribute ];
		}

		return $result;
	}

	/**
	 * validation method
	 * Check ini settings, field and files
	 *
	 * @private
	 *
	 * @param null $item {Array} Item
	 *
	 * @return bool {boolean, String}
	 */
	private function validate( $item = null ) {
		if ( $item == null ) {
			// check ini settings and some generally options
			$ini = array(
				(boolean) ini_get('file_uploads'),
				(int) ini_get('upload_max_filesize'),
				(int) ini_get('post_max_size'),
				(int) ini_get('max_file_uploads'),
				(int) ini_get('memory_limit')
			);

			if ( ! $ini[0] ) {
				return $this->code_to_message( 'file_uploads' );
			}

			if ( $this->options['required'] && strtolower( $_SERVER['REQUEST_METHOD']) == "post" && $this->field['count'] + count( $this->options['files'] ) == 0 ) {
				return $this->code_to_message( 'required_and_no_file' );
			}

			if ( ( $this->options['limit'] && $this->field['count'] + count( $this->options['files'] ) > $this->options['limit']) || ( $ini[3] != 0 && ( $this->field['count'] ) > $ini[3] ) ) {
				return $this->code_to_message( 'max_number_of_files' );
			}

			if ( ! file_exists( $this->options['uploadDir'] ) || ! is_writable( $this->options['uploadDir'] ) ) {
				return $this->code_to_message( 'invalid_folder_path' );
			}

			$total_size = 0;

			foreach ( $this->field['input']['size'] as $key => $value ) {
				$total_size += $value;
			}

			$total_size = $total_size / 1000000;

			if ( $ini[2] != 0 && $total_size > $ini[2] ) {
				return $this->code_to_message('post_max_size');
			}

			if ( $this->options['maxSize'] && $total_size > $this->options['maxSize'] ) {
				return $this->code_to_message( 'max_files_size' );
			}

		} else {
			// check file
			if ( $item['error'] > 0 ) {
				return $this->code_to_message( $item['error'], $item );
			}

			if ( is_array( $this->options['disallowedExtensions'] ) && ( in_array( strtolower( $item['extension'] ), $this->options['disallowedExtensions'] ) || preg_grep( '/(' . $item['format'] . '\/\*|' . preg_quote($item['type'], '/' ) . ')/', $this->options['disallowedExtensions'] ) ) ) {
				return $this->code_to_message( 'accepted_file_types', $item );
			}

			if ( is_array( $this->options['extensions'] ) && ! in_array( strtolower( $item['extension'] ), $this->options['extensions'] ) && ! preg_grep( '/(' . $item['format'] . '\/\*|' . preg_quote( $item['type'], '/') . ')/', $this->options['extensions'] ) ) {
				return $this->code_to_message( 'accepted_file_types', $item );
			}

			if ( $this->options['fileMaxSize'] && $item['size']/1000000 > $this->options['fileMaxSize'] ) {
				return $this->code_to_message( 'max_file_size', $item );
			}

			if ( $this->options['maxSize'] && $item['size']/1000000 > $this->options['maxSize'] ) {
				return $this->code_to_message( 'max_file_size', $item );
			}

			$custom_validation = is_callable( $this->options['validate_file'] ) ? $this->options['validate_file']( $item, $this->options ) : true;

			if ( $custom_validation != true ) {
				return $custom_validation;
			}
		}

		return true;
	}

	/**
	 * Process and upload the files
	 *
	 * @private
	 * @return array|void {null, Array}
	 */
	private function upload_files() {
		$data = array(
			"hasWarnings" => false,
			"isSuccess" => false,
			"warnings" => array(),
			"files" => array()
		);
		$listInput = $this->field['listInput'];
		$uploadDir = str_replace( getcwd() . '/', '', $this->options['uploadDir'] );
		$chunk     = isset( $_POST['_chunkedd'] ) && count( $this->field['input']['name'] ) == 1 ? wp_json_decode( stripslashes( $_POST['_chunkedd'] ), true ) : false;

		if ( false !== $chunk ) {
			$chunk = sanitize_text_field( $chunk );
		}

		if ( $this->field['input'] ) {
			// validate ini settings and some generally options
			$validate          = $this->validate();
			$data['isSuccess'] = true;

			if ( $validate === true ) {
				// process the files
				$count = count( $this->field['input']['name'] );

				for( $i = 0; $i < $count; $i++ ) {
					$file = array(
						'name' => $this->field['input']['name'][$i],
						'tmp_name' => $this->field['input']['tmp_name'][$i],
						'type' => $this->field['input']['type'][$i],
						'error' => $this->field['input']['error'][$i],
						'size' => $this->field['input']['size'][$i]
					);

					// chunk
					if ( $chunk ) {
						if ( isset($chunk['isFirst'] ) ) {
							$chunk['temp_name'] = $this->random_string(6) . time();
						}

						$tmp_name = $uploadDir . '.unconfirmed_' . self::filter_file_name( $chunk['temp_name'] );

						if ( ! isset( $chunk['isFirst'] ) && ! file_exists( $tmp_name ) ) {
							continue;
						}

						$sp = fopen( $file['tmp_name'], 'rb' );
						$op = fopen( $tmp_name, isset( $chunk['isFirst'] ) ? 'wb' : 'ab' );

						while ( ! feof( $sp ) ) {
							$buffer = fread( $sp, 512 );
							fwrite( $op, $buffer );
						}

						// close handles
						fclose( $op );
						fclose( $sp );

						if ( isset( $chunk['isLast'] ) ) {
							$file['tmp_name'] = $tmp_name;
							$file['name']     = $chunk['name'];
							$file['type']     = $chunk['type'];
							$file['size']     = $chunk['size'];
						} else {
							echo json_encode(
								array(
									'fileuploader' => array(
										'temp_name' => $chunk['temp_name']
									)
								)
							);
							exit;
						}
					}

					$metas = array();

					$metas['tmp_name']  = $file['tmp_name'];
					$metas['extension'] = strtolower( substr( strrchr( $file['name'], "." ), 1 ) );
					$metas['type']      = $file['type'];
					$metas['format']    = strtok( $file['type'], '/' );
					$metas['name']      = $metas['old_name'] = $file['name'];
					$metas['title']     = $metas['old_title'] = substr( $metas['old_name'], 0, ( strlen( $metas['extension'] ) > 0 ? -( strlen( $metas['extension'] )+1 ) : strlen( $metas['old_name'] ) ) );
					$metas['size']      = $file['size'];
					$metas['size2']     = $this->format_size( $file['size'] );
					$metas['date']      = date( 'r' );
					$metas['error']     = $file['error'];
					$metas['chunked']   = $chunk;

					// validate file
					$validateFile = $this->validate( array_diff_key( $metas, array_flip( array( 'tmp_name', 'chunked' ) ) ) );

					// check if file is in listInput
					$listInputName = '0:/' . $metas['old_name'];
					$fileInList    = $listInput === null || in_array( $listInputName, $listInput['list'] );

					// add file to memory
					if ( $validateFile === true ) {
						if ( $fileInList ) {

							if ( $listInput ) {
								$fileListIndex      = array_search( $listInputName, $listInput['list'] );
								$metas['listProps'] = $listInput['values'][$fileListIndex];

								unset( $listInput['list'][$fileListIndex] );
								unset( $listInput['values'][$fileListIndex] );
							}

							$metas['i']        = count($data['files']);
							$metas['name']     = $this->generate_file_name( $this->options['title'], array_diff_key( $metas, array_flip( array( 'tmp_name', 'error', 'chunked' ) ) ) );
							$metas['title']    = substr( $metas['name'], 0, ( strlen( $metas['extension'] ) > 0 ? -( strlen( $metas['extension'] ) + 1 ) : strlen( $metas['name'] ) ) );
							$metas['file']     = $uploadDir . $metas['name'];
							$metas['replaced'] = file_exists( $metas['file'] );

							ksort($metas);
							$data['files'][] = $metas;
						}
					} else {
						if ( $metas['chunked'] && file_exists( $metas['tmp_name'] ) ) {
							unlink( $metas['tmp_name'] );
						}

						if ( ! $fileInList ) {
							continue;
						}

						$data['isSuccess']   = false;
						$data['hasWarnings'] = true;
						$data['warnings'][]  = $validateFile;
						$data['files']       = array();

						break;
					}
				}

				// upload the files
				if ( ! $data['hasWarnings'] ) {
					foreach( $data['files'] as $key => $file ) {
						if ( $file['chunked'] ? rename( $file['tmp_name'], $file['file'] ) : $this->options['move_uploaded_file']( $file['tmp_name'], $file['file'], $file ) ) {
							unset( $data['files'][$key]['i'] );
							unset( $data['files'][$key]['chunked'] );
							unset( $data['files'][$key]['error'] );
							unset( $data['files'][$key]['tmp_name'] );

							$data['files'][$key]['uploaded'] = true;

							$this->options['files'][] = $data['files'][$key];
						} else {
							unset( $data['files'][$key] );
						}
					}
				}
			} else {
				$data['isSuccess']   = false;
				$data['hasWarnings'] = true;
				$data['warnings'][]  = $validate;
			}
		} else {
			$lastPHPError = error_get_last();

			if ( $lastPHPError && $lastPHPError['type'] == E_WARNING && $lastPHPError['line'] == 0 ) {
				$errorMessage = null;

				if ( strpos( $lastPHPError['message'], "POST Content-Length") !== false ) {
					$errorMessage = $this->code_to_message( UPLOAD_ERR_INI_SIZE );
				}

				if ( strpos( $lastPHPError['message'], "Maximum number of allowable file uploads") !== false ) {
					$errorMessage = $this->code_to_message( 'max_number_of_files' );
				}

				if ( $errorMessage != null ) {
					$data['isSuccess']   = false;
					$data['hasWarnings'] = true;
					$data['warnings'][]  = $errorMessage;
				}
			}

			if ( $this->options['required'] && strtolower( $_SERVER['REQUEST_METHOD'] ) == "post" ) {
				$data['hasWarnings'] = true;
				$data['warnings'][]  = $this->code_to_message( 'required_and_no_file' );
			}
		}

		// add listProp attribute to the files
		if ( $listInput )
			foreach( $this->get_file_list() as $key => $item)  {
				if ( ! isset( $item['listProps'] ) ) {
					$fileListIndex = array_search( $item['file'], $listInput['list'] );

					if ( $fileListIndex !== false ) {
						$this->options['files'][$key]['listProps'] = $listInput['values'][$fileListIndex];
					}
				}

				if ( isset( $item['listProps'] ) ) {
					unset( $this->options['files'][$key]['listProps']['file'] );

					if ( empty( $this->options['files'][$key]['listProps'] ) ) {
						unset( $this->options['files'][$key]['listProps'] );
					}
				}
			}

		$data['files'] = $this->get_uploaded_files();

		return $data;
	}

	/**
	 * generate_file_name method
	 * Generated a new file name
	 *
	 * @private
	 *
	 * @param $conf {null, String, Array} FileUploader title option
	 * @param $item {Array} Item
	 * @param bool $skip_replace_check {boolean} Used only for recursive auto generating file name to exclude replacements
	 *
	 * @return string {String}
	 */
	private function generate_file_name( $conf, $item, bool $skip_replace_check = false ): string {
		if ( is_callable( $conf ) ) {
			$conf = $conf( $item );
		}

		$conf           = ! is_array( $conf ) ? array( $conf ) : $conf;
		$type           = $conf[0];
		$length         = isset( $conf[1] ) ? max( 1, (int) $conf[1] ) : 12;
		$forceExtension = isset( $conf[2] ) && $conf[2] == true;
		$random_string  = $this->random_string( $length );
		$extension      = ! empty( $item['extension'] ) ? '.' . $item['extension'] : '';

		switch( $type ) {
			case null:
			case "auto":
				$string = $random_string;
				break;
			case "name":
				$string = $item['title'];
				break;
			default:
				$string = $type;
				$string_extension = substr(strrchr($string, "."), 1);

				$string = str_replace("{i}", $item['i'] + 1, $string);
				$string = str_replace("{random}", $random_string, $string);
				$string = str_replace("{file_name}", $item['title'], $string);
				$string = str_replace("{file_size}", $item['size'], $string);
				$string = str_replace("{timestamp}", time(), $string);
				$string = str_replace("{date}", date('Y-n-d_H-i-s'), $string);
				$string = str_replace("{extension}", $item['extension'], $string);
				$string = str_replace("{format}", $item['format'], $string);
				$string = str_replace("{index}", $item['listProps']['index'] ?? 0, $string );

				if ( $forceExtension && !empty($string_extension ) ) {
					if ( $string_extension != "{extension}" ) {
						$type      = substr( $string, 0, -( strlen( $string_extension) + 1 ) );
						$extension = $item['extension'] = $string_extension;
					} else {
						$type = substr( $string, 0, -(strlen($item['extension'] ) + 1 ) );
						$extension = '';
					}
				}
		}

		if ( $extension && !preg_match('/'.$extension.'$/', $string ) ) {
			$string .= $extension;
		}

		// generate another filename if a file with the same name already exists
		// only when replace options is true
		if ( ! $this->options['replace'] && !$skip_replace_check ) {
			$title = $item['title'];
			$i     = 1;

			while ( file_exists( $this->options['uploadDir'] . $string ) ) {
				$item['title'] = $title . " ({$i})";
				$conf[0]       = $type == "auto" || $type == "name" || strpos( $string, "{random}") !== false ? $type : $type  . " ({$i})";
				$string        = $this->generate_file_name( $conf, $item, true );
				$i++;
			}
		}

		return self::filter_file_name( $string );
	}

	/**
	 * codeToMessage method
	 * Translate a warning code into text
	 *
	 * @private
	 *
	 * @param $code {Number, String}
	 * @param null $file {null, Array}
	 *
	 * @return string {String}
	 */
	private function code_to_message( $code, $file = null ): string {
		$message = null;

		switch ( $code ) {
			case UPLOAD_ERR_INI_SIZE:
				$message = esc_html__( 'The uploaded file exceeds the upload_max_filesize directive in php.ini', 'filr' );
				break;
			case UPLOAD_ERR_FORM_SIZE:
				$message = esc_html__( 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'filr' );
				break;
			case UPLOAD_ERR_PARTIAL:
				$message = esc_html__( 'The uploaded file was only partially uploaded', 'filr' );
				break;
			case UPLOAD_ERR_NO_FILE:
				$message = esc_html__( 'No file was uploaded', 'filr' );
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$message = esc_html__( 'Missing a temporary folder', 'filr' );
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$message = esc_html__( 'Failed to write file to disk', 'filr' );
				break;
			case UPLOAD_ERR_EXTENSION:
				$message = esc_html__( 'File upload stopped by extension', 'filr' );
				break;
			case 'accepted_file_types':
				$message = sprintf( esc_html__( 'File type is not allowed for %s', 'filr' ), $file['old_name'] );
				break;
			case 'file_uploads':
				$message = esc_html__( 'File uploading option in disabled in php.ini', 'filr' );
				break;
			case 'max_file_size':
				$message = sprintf( esc_html__( '%s is too large', 'filr' ), $file['old_name'] );
				break;
			case 'max_files_size':
				$message = esc_html__( 'Files are too big', 'filr' );
				break;
			case 'max_number_of_files':
				$message = esc_html__( 'Maximum number of files is exceeded', 'filr' );
				break;
			case 'required_and_no_file':
				$message = esc_html__( 'No file was choosed. Please select one', 'filr' );
				break;
			case 'invalid_folder_path':
				$message = esc_html__( 'Upload folder doesn"t exist or is not writable', 'filr' );
				break;
			default:
				$message = esc_html__( 'Unknown upload error', 'filr' );
				break;
		}

		return $message;
	}

	/**
	 * Cover bytes to readable file size format.
	 *
	 * @param  number $bytes Number.
	 *
	 * @return string
	 */
	private function format_size( $bytes ): string {
		if ( $bytes >= 1073741824 ) {
			$bytes = number_format( $bytes / 1073741824, 2 ) . ' GB';
		} elseif ( $bytes >= 1048576 ) {
			$bytes = number_format( $bytes / 1048576, 2 ) . ' MB';
		} elseif ( $bytes > 0 ) {
			$bytes = number_format( $bytes / 1024, 2 ) . ' KB';
		} else {
			$bytes = '0 bytes';
		}

		return $bytes;
	}

	/**
	 * Check if string is a valid json.
	 *
	 * @param string $string String.
	 *
	 * @return boolean
	 */
	private function is_json( string $string ): bool {
		json_decode( $string );
		return ( json_last_error() == JSON_ERROR_NONE ) ;
	}

	/**
	 * Generate a random string.
	 *
	 * @param integer $length Number of characters.
	 *
	 * @return string
	 */
	private function random_string( int $length = 12 ): string {
		return substr( str_shuffle( '_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ), 0, $length );
	}

	/**
	 * Remove invalid characters from filename.
	 *
	 * @param string $filename Filename.
	 *
	 * @return string
	 */
	public static function filter_file_name( string $filename ): string {
		$delimiter         = '_';
		$invalidCharacters = array_merge( array_map( 'chr', range( 0, 31 ) ), array( "<", ">", ":", '"', "/", "\\", "|", "?", "*" ) );

		$filename = str_replace( $invalidCharacters, $delimiter, $filename );
		$filename = preg_replace( '/(' . preg_quote( $delimiter, DIRECTORY_SEPARATOR ) . '){2,}/', '$1', $filename );

		return $filename;
	}
}
