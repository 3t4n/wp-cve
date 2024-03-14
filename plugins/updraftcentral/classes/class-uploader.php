<?php
if (!defined('ABSPATH')) die('Access denied.');

if (!class_exists('UpdraftCentral_Uploader')) :

/**
 * Handles file upload process initiated by plupload which sends the data directly to the
 * remote website. Intended to be shared and used by the "plugin" and "theme" modules.
 */
class UpdraftCentral_Uploader {

	protected static $_instance = null;

	/**
	 * Creates an instance of this class. Singleton Pattern
	 *
	 * @return object Instance of this class
	 */
	public static function instance() {
		if (empty(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Sends the uploaded data to the remote website
	 *
	 * @param integer $site_id The ID of the remote site where the data is to be sent
	 * @param array   $data    The data to send
	 * @param string  $module  Indicates whether this request is intended for the 'plugin' or 'theme' module
	 * @return array
	 */
	private function send_upload_request($site_id, $data, $module) {

		$user = UpdraftCentral()->user;

		$user_id = get_current_user_id();
		if (empty($user)) $user = UpdraftCentral()->get_user_object($user_id);

		if (!empty($user) && is_a($user, 'UpdraftCentral_User')) {
			$remote_params = array(
				'site_id' => $site_id,
				'data' => array(
					'command' => $module.'.upload_'.$module,
					'data' => $data
				)
			);

			$remote_response = $user->send_remote_command($remote_params);
			if (!empty($remote_response) && 'ok' == $remote_response['responsetype']) {
				$response = $remote_response['rpc_response']['response'];
				$data = $remote_response['rpc_response']['data'];

				if ('rpcok' === $response) {
					return $data;
				} else {
					$data['error'] = true;
					if (is_null($data['data'])) $data['data'] = array();

					return $data;
				}
			}

			// If proccess gets to this line it would mean that we have encountered
			// an issue other than the expected response (like fatal error, etc.). So, it would be
			// helpful if we pass back the original response to the caller for easier debugging if need be.
			return $remote_response;
		}
	}
	
	/**
	 * Retrieves a PHP error message associated with the error code. These error messages
	 * are the ones raised when an upload error has occured using PHP.
	 *
	 * @param integer $code The error code returned by $_FILES
	 * @return string
	 */
	private function get_upload_error_message($code) {

		// $_FILES error code may return as string. Thus, we're going to
		// cast it into int before proceeding with the check.
		switch ((int) $code) {
			case 1:
				$message = __('The uploaded file exceeds the upload_max_filesize directive set in your UpdraftCentral dashboard\'s php.ini file.', 'updraftcentral');
				break;
			case 2:
				$message = __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.', 'updraftcentral');
				break;
			case 3:
				$message = __('The uploaded file was only partially uploaded.', 'updraftcentral');
				break;
			case 4:
				$message = __('No file was uploaded.', 'updraftcentral');
				break;
			case 6:
				$message = __('Missing a temporary folder that is needed to upload files to your UpdraftCentral dashboard.', 'updraftcentral');
				break;
			case 7:
				$message = __('Failed to write file to disk. Make sure you have sufficient permission to upload files to your UpdraftCentral dashboard\'s file system.', 'updraftcentral');
				break;
			case 8:
				$message = __('Your UpdraftCentral dashboard\'s PHP extension stopped the file upload.', 'updraftcentral');
				break;
			default:
				$message = __('The file was not uploaded successfully. Please try again.', 'updraftcentral');
				break;
		}

		return $message;
	}

	/**
	 * Retrieves the Plupload configuration
	 *
	 * @param string $module Indicates whether the current request is intended for the 'plugin' or 'theme' module
	 * @return string
	 */
	public function get_plupload_config($module) {

		$chunk_size = min(wp_max_upload_size()-1024, 1024*1024*2-1024);
		$plupload_init = array(
			'runtimes' => 'html5,flash,silverlight,html4',
			'browse_button' => 'plupload-browse-button',
			'container' => 'plupload-upload-ui',
			'drop_element' => 'drag-drop-area',
			'file_data_name' => 'async-upload',
			'multiple_queues' => false,
			'max_file_count' => 1,
			'max_file_size' => '100Gb',
			'chunk_size' => $chunk_size.'b',
			'url' => admin_url('admin-ajax.php'),
			'filters' => array(array('title' => __('Allowed Files'), 'extensions' => 'zip')),
			'multipart' => true,
			'multi_selection' => false,
			'urlstream_upload' => true,
			// additional post data to send to our ajax hook
			'multipart_params' => array(
				'_ajax_nonce' => wp_create_nonce('updraftcentral-uploader-'.$module),
				'action' => $module.'_uploader_action'
			)
		);

		// WP 3.9 updated to plupload 2.0 - https://core.trac.wordpress.org/ticket/25663
		if (is_file(ABSPATH.WPINC.'/js/plupload/Moxie.swf')) {
			$plupload_init['flash_swf_url'] = includes_url('js/plupload/Moxie.swf');
		} else {
			$plupload_init['flash_swf_url'] = includes_url('js/plupload/plupload.flash.swf');
		}

		if (is_file(ABSPATH.WPINC.'/js/plupload/Moxie.xap')) {
			$plupload_init['silverlight_xap_url'] = includes_url('js/plupload/Moxie.xap');
		} else {
			$plupload_init['silverlight_xap_url'] = includes_url('js/plupload/plupload.silverlight.swf');
		}

		// plupload_config
		return json_encode($plupload_init);
	}

	/**
	 * Process the upload request originating from the plupload client and send
	 * the uploaded data directly to the remote website.
	 *
	 * @param string $module Indicates whether the current request is intended for the 'plugin' or 'theme' module
	 * @return string
	 */
	public function plupload_action($module) {

		// Verify the nonce submitted
		$post = wp_unslash($_POST);
		$ajax_nonce = !empty($post['_ajax_nonce']) ? sanitize_text_field($post['_ajax_nonce']) : '';
		if (empty($ajax_nonce) || !wp_verify_nonce($ajax_nonce, 'updraftcentral-uploader-'.$module)) {
			echo json_encode(array('e' => sprintf(__('Error: %s', 'updraftcentral'), __('Nonce verification failed.', 'updraftcentral'))));
			exit;
		}

		// "tmp_name" field of the $_FILES array is auto-generated by PHP, not a user input.
		// It contains the temporary location/path of the uploaded file. Adding "wp_unslash" will
		// break this upload feature on a Windows System thus, we added the phpcs:ignore
		// annotation here.
		$tmp_name = isset($_FILES['async-upload']['tmp_name']) ? sanitize_text_field($_FILES['async-upload']['tmp_name']) : '';// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash - this value is not slashed
		$filename = isset($post['name']) ? sanitize_file_name(basename($post['name'])) : '';
		
		if (isset($_FILES['async-upload']['error']) && UPLOAD_ERR_OK == sanitize_text_field(wp_unslash($_FILES['async-upload']['error'])) && is_uploaded_file($tmp_name)) {
			if (isset($post['chunk']) && 0 === (int) $post['chunk']) {
				$validate = wp_check_filetype_and_ext($tmp_name, $filename);
			} else {
				$validate = wp_check_filetype($filename);
			}

			if (!empty($validate['ext']) && 'zip' === $validate['ext']) {
				$filename = (isset($validate['proper_filename']) && false !== $validate['proper_filename']) ? $validate['proper_filename'] : $filename;
				$args = array('filename' => $filename);

				// Check to see if the current upload was split in chunks
				if (isset($post['chunks'])) {
					// Handling upload in chunks
					$chunks = sanitize_text_field($post['chunks']);
					if (1 < (int) $chunks) {
						$args['chunks'] = $chunks;
						if (isset($post['chunk'])) {
							$args['chunk'] = sanitize_text_field($post['chunk']);
						}
					}
				}

				$args['data'] = base64_encode(file_get_contents($tmp_name));
				$args['activate'] = isset($post['activate']) ? sanitize_text_field($post['activate']) : '';

				$response_data = array();
				if (isset($post['sites'])) {
					$sites = json_decode(base64_decode(sanitize_text_field($post['sites'])), true);

					foreach ($sites as $site) {
						$args['filesystem_credentials'] = $site['filesystem_credentials'];
						$response_data[] = array(
							'site_id' => $site['id'],
							'site_description' => $site['description'],
							'response' => $this->send_upload_request($site['id'], $args, $module)
						);
					}
				}

				echo json_encode($response_data);
				exit;
			} else {
				// Not a valid zip file...
				echo json_encode(array('e' => sprintf(__('Error: %s', 'updraftcentral'), __('This file does not appear to be a zip file.', 'updraftcentral'))));
				exit;
			}
		} else {
			// An error has occured while processing the upload request
			$error_message = $this->get_upload_error_message(sanitize_text_field(wp_unslash($_FILES['async-upload']['error'])));
			echo json_encode(array('e' => sprintf(__('Error: %s', 'updraftcentral'), $error_message)));
			exit;
		}
	}
}

endif;
