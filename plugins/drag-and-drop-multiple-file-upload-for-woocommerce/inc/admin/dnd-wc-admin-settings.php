<?php

	/**
	* @Description : WC Settings Hooks
	* @Package : Drag & Drop Multiple File Upload - WooCommerce
	* @Author : CodeDropz
	*/

	if ( ! defined( 'ABSPATH' ) || ! defined('DNDMFU_WC') ) {
		exit;
	}

	class DNDMFU_WC_Settings extends WC_Settings_Page {

		public function __construct() {

			$this->id    = 'dnd-wc-file-uploads';
			$this->label = __( 'File Uploads', 'dnd-file-upload-wc' );

			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
			add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );

		}

		/**
		* Display - Create Fields
		*/

		public function get_settings( $current_section = '' ) {
            $lang = ( ( isset( $_GET['lang'] ) && $_GET['lang'] != 'en' ) ? '-'.$_GET['lang'] : '' );
			$settings = apply_filters(
				'dnd_wc_upload_settings',
					array(

						// Title - Heading
						array(
							'title' => 	__( 'Drag & Drop Uploader - Settings', 'dnd-file-upload-wc' ),
							'type'  => 	'title'
						),

						// Heading - 1
						array(
							'title' => 	__( 'Uploader Info', 'dnd-file-upload-wc' ),
							'type'  => 	'title',
							'id'	=>	'wcf_dnd_uploader_info'
						),
                        
						array(
							'title'    		=> 	__( 'Drag & Drop Text', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_text'.$lang,
							'placeholder'	=>	'Drag & Drop Files Here',
							'type'     		=> 	'text'
						),

						array(
							'id'       		=> 	'wcf_drag_n_drop_separator'.$lang,
							'placeholder'	=>	'|',
							'type'     		=> 	'text'
						),

						array(
							'title'    		=> 	__( 'Browse Text', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_browse_text'.$lang,
							'placeholder'	=>	'Browse Text',
							'type'     		=> 	'text'
						),

						array(
							'title'    		=> 	__( 'File Upload Label', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_default_label'.$lang,
							'desc'			=>	__('Display title/heading before file upload area.'),
							'placeholder'	=>	'Multiple File Uploads',
							'type'     		=> 	'text',
							'desc_tip'		=>	true
						),

						// End Heading - 1
						array(
							'type' => 'sectionend',
							'id'   => 'wcf_dnd_uploader_info',
						),

                        // Begin Uploader Text
						array(
							'title' => 	__( 'Uploader Text', 'dnd-file-upload-wc' ),
							'type'  => 	'title',
							'id'	=>	'wcf_dnd_uploader_text'
						),

                        // Of text
                        array(
							'title'    		=> 	__( 'Of Text', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_of_text'.$lang,
							'placeholder'	=>	'1 of 10',
                            'css'           =>  'width:120px',
							'type'     		=> 	'text'
						),

                        // Deleting text
                        array(
							'title'    		=> 	__( 'Deleting Text', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_deleting_text'.$lang,
							'placeholder'	=>	'Deleting...',
							'type'     		=> 	'text'
						),

                        // Removing Text
                        array(
							'title'    		=> 	__( 'Remove Text', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_remove_text'.$lang,
							'placeholder'	=>	'Remove',
							'type'     		=> 	'text'
						),

                        // End Uploader Text
						array(
							'type' => 'sectionend',
							'id'   => 'wcf_dnd_uploader_text',
						),

						// Heading - 2
						array(
							'title' => __( 'Error Message', 'dnd-file-upload-wc' ),
							'type'  => 'title',
							'id'	=>	'wcf_dnd_heading_2'
						),

						array(
							'title'    		=> 	__( 'File exceeds server limit', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_error_server_limit'.$lang,
							'placeholder'	=>	'The uploaded file exceeds the maximum upload size of your server.',
							'type'     		=> 	'text'
						),

						array(
							'title'    		=> 	__( 'Failed to Upload', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_error_failed_to_upload'.$lang,
							'placeholder'	=>	'Uploading a file fails for any reason',
							'type'     		=> 	'text'
						),

						array(
							'title'    		=> 	__( 'Files too large', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_error_files_too_large'.$lang,
							'placeholder'	=>	'Uploaded file is too large',
							'type'     		=> 	'text'
						),

						array(
							'title'    		=> 	__( 'Invalid file Type', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_error_invalid_file'.$lang,
							'placeholder'	=>	'Uploaded file is not allowed for file type',
							'type'     		=> 	'text'
						),

						array(
							'title'    		=> 	__( 'Max Upload Limit', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_error_max_file'.$lang,
							'desc'			=>	__('Example: Note : Some of the files are not uploaded ( Only %count% files allowed )','dnd-file-upload-wc'),
							'type'     		=> 	'text',
							'desc_tip'		=>	true
						),

						array(
							'title'    		=> 	__( 'Max File Limit', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_error_max_number_of_files'.$lang,
							'desc'			=>	__('Defaut: You have reached the maximum number of files ( Only %s files allowed )','dnd-file-upload-wc'),
							'type'     		=> 	'text',
							'desc_tip'		=>	true
						),

						array(
							'title'    		=> 	__( 'Mininimum File', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_error_min_file'.$lang,
							'placeholder'	=>	'Please upload atleast %s file(s)',
							'desc'			=>	__('Display an error if total file upload less than minimum specified.'),
							'type'     		=> 	'text',
							'desc_tip'		=>	true
						),

						// End Heading - 2
						array(
							'type' => 'sectionend',
							'id'   => 'wcf_dnd_heading_2',
						),

						// Heading - 3
						array(
							'title' => __( 'Upload Restriction - Options', 'dnd-file-upload-wc' ),
							'type'  => 'title',
							'id'	=>	'wcf_dnd_heading_3'
						),

						/* Required */
						array(
							'title'    => __( 'Required?', 'dnd-file-upload-wc' ),
							'desc'     => __( 'Yes, file upload is required.', 'dnd-file-upload-wc' ),
							'id'       => 'wcf_drag_n_drop_required',
							'default'  => 'no',
							'type'     => 'checkbox',
							'desc_tip' => false
						),

						/* Required */
						array(
							'title'    => __( 'Disable File Upload?', 'dnd-file-upload-wc' ),
							'desc'     => __( 'Yes (Global)', 'dnd-file-upload-wc' ),
							'id'       => 'wcf_drag_n_drop_disable',
							'default'  => 'no',
							'type'     => 'checkbox',
							'desc_tip' => false
						),

						/* Name */
						array(
							'title'    		=> 	__( 'Name', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_field_name',
							'placeholder'	=>	'upload-file-352',
							'desc'			=>	__( 'Change the name of file upload.' ),
							'type'     		=> 	'text',
							'desc_tip' 		=> true
						),

						/* Max File Size*/
						array(
							'title'    		=> 	__( 'Max File Size (Bytes)', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_file_size_limit',
							'placeholder'	=>	'10485760',
							'desc'			=>	__( 'Set file size limit for each file (default: 10MB)' ),
							'type'     		=> 	'text',
							'desc_tip' 		=> true
						),

						/* Max File Limit */
						array(
							'title'    		=> 	__( 'Max File Upload', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_max_file_upload',
							'placeholder'	=>	'10',
							'desc'			=>	__( 'Set maximum file upload limit. (default: 10)' ),
							'type'     		=> 	'text',
							'desc_tip' 		=> true
						),

						/* Min File Upload */
						array(
							'title'    		=> 	__( 'Min File Upload', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_min_file_upload',
							'desc'			=>	__( 'Set minimum file upload.' ),
							'type'     		=> 	'text',
							'desc_tip' 		=> true
						),

						/* Supported Types */
						array(
							'title'    		=> 	__( 'Supported File Types', 'dnd-file-upload-wc' ),
							'id'       		=> 	'wcf_drag_n_drop_support_file_upload',
							'placeholder'	=>	'jpg, jpeg, png, gif, svg',
							'desc'			=>	__( 'Enter supported File Types separated by comma.' ),
							'type'     		=> 	'text',
							'desc_tip' 		=> true
						),

						// End Heading - 3
						array(
							'type' => 'sectionend',
							'id'   => 'wcf_dnd_heading_3',
						),

						// Heading - 4
						array(
							'title' => __( 'WooCommerce Options', 'dnd-file-upload-wc' ),
							'type'  => 'title',
							'id'	=>	'wcf_dnd_heading_4'
						),

						/* Show Uploader In */
						array(
							'title'   		=> __( 'Show Uploader In', 'dnd-file-upload-wc' ),
							'id'      		=> 'wcf_show_in_dnd_file_uploader_in',
							'type'			=> 'select',
							'class'    		=> 'wc-enhanced-select',
							'desc'			=> __( 'Select which page you want to show the uploader.','dnd-file-upload-wc' ),
							'options' 		=> array(
								'single-page'	=>	'Single - Product Page',
								'checkout-page'	=>	'Checkout Page - PRO'
							),
							'default'   	=> 'single-page',
							'desc_tip'		=> true
						),

						/* Show Before */
						array(
							'title'   		=> __( 'Show Before', 'dnd-file-upload-wc' ),
							'id'      		=> 'wcf_show_in_dnd_file_upload_after',
							'type'			=> 'select',
							'class'    		=> 'wc-enhanced-select',
							'desc'			=> __( 'Select which section to <br>display the uploader <br>(default: Add To Cart)','dnd-file-upload-wc' ),
							'options' 		=> array(
								'woocommerce_before_add_to_cart_form'	=>	'Add to Cart Form',
								'woocommerce_before_variations_form'	=>	'Variations Form',
								'woocommerce_before_add_to_cart_button'	=>	'Add to Cart Button',
								'woocommerce_before_single_variation'	=>	'Single Variation'
							),
							'default'   	=> 'woocommerce_before_add_to_cart_button',
							'desc_tip'		=> true
						),

						// End Heading - 4
						array(
							'type' => 'sectionend',
							'id'   => 'wcf_dnd_heading_4',
						),

					)
				);

			return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
		}

		/**
		* Display - Output Fields
		*/

		public function output() {
			$settings = $this->get_settings();
			WC_Admin_Settings::output_fields( $settings );
		}

		/**
		* Save Options
		*/

		public function save() {
			$settings = $this->get_settings();
			WC_Admin_Settings::save_fields( $settings );
		}
	}

	new DNDMFU_WC_Settings();

