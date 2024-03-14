/**
 * CodeDropz Uploader v1.0.10
 * Copyright 2019 Glen Mongaya
 * CodeDropz - Drag & Drop Uploader
 * @version 1.1.0
 * @author CodeDropz, Glen Don L. Mongaya
 * @license The MIT License (MIT)
 */

(function($){
	$.fn.CodeDropz_Uploader_WC = function( settings ){

		// Queue, DataFiles ( Store Queue, Data Information )
		var FileDataRecord = [];
		var DataQueue = [];

        // Check for nonce
        const form = document.querySelector('form.cart');
        if( form ) {
            const data = new FormData();
            data.append('action', 'wc_upload_nonce');
            data.append('nonce', dnd_wc_uploader.nonce);
            fetch(dnd_wc_uploader.ajax_url, { method: 'POST', body: data })
            .then(res => res.json())
            .then(({ data, success }) => success && (dnd_wc_uploader.nonce = data))
            .catch(console.error)
        }

		// Support multiple elements
		this.each(function(){

			// Parent input file type
			var input = $(this);

			// Extends options
			var options = $.extend({
				is_pro				: false,
				handler 			: input,
				form				: input.parents('form'),
				color 				: "#000",
				background 			: '',
				upload_dir			: null, //@ hidden input fields @todo - add in input file attribute
				server_max_error 	: 'Uploaded file exceeds the maximum upload size of your server.', //@ Server error
				max_file 			: input.data('max') ? input.data('max') : 5, //@ default 10
				max_upload_size 	: input.data('limit') ? input.data('limit') : '10485760', //@ should be a bytes it's (10MB)
				supported_type 		: input.data('type') ? input.data('type') : 'jpg|jpeg|JPG|png|gif|pdf|doc|docx|ppt|pptx|odt|avi|ogg|m4a|mov|mp3|mp4|mpg|wav|wmv|xls',
				max_total_size      : '100MB', //@ total size of all files uploaded - should be MB
				parallel_uploads	: 2, //@ Number of files to simultaneously upload
				chunks				: false, //@ wheather upload files in chunks
				chunk_size			: 10000, //@ 10MB - default chunk size (KB - format)
				text 				: 'Drag & Drop Files Here',
				separator			: 'or',
				button_text 		: 'Browse Files',
				err_message			: {
					maxNumFiles 		: 'You have reached the maximum number of files ( Only %s files allowed )',
					maxUploadLimit		: 'Note : Some of the files could not be uploaded ( Only %s files allowed )',
					maxTotalSize		: 'The total file(s) size exceeding the max size limit of %s.'
				},
				on_success			: '',
				in_progress			: '',
				completed			: ''
			}, settings);

			// Custom Upload Options - PRO version only.
			var FileOptions = {
				parallelUploads : options.parallel_uploads, // sequential upload - how my files at a time.
				chunking 		: options.chunks,
				chunkSize 		: options.chunk_size, //kb
				progress__id	: 'codedropz--results'
			}

			// Clean and convert MB to Bytes
			var totalSize__Limit	= parseInt( options.max_total_size.replace('[^0-9]/g','') ) * 1048576; // convert MB to Bytes

			//Unique Filename for multiple uploads -- useful for multiple file upload in 1 form...
			var FileName = input.data('name');

			// Template Container
			var cdropz_template = '<div class="codedropz-upload-handler wc-upload-wrap">'
				+ '<div class="codedropz-upload-container">'
				+ '<div class="codedropz-upload-inner">'
					+ '<div class="codedropz-label">'
						+ '<span class="cd-icon"><i class="icon-cloud-upload"></i></span>'
						+ '<span class="text">'+ options.text +'</span>'
						+ '<span class="cd-separator">'+ options.separator +'</span>'
						+ '<a class="cd-upload-btn" href="javascript:void(0)">'+ options.button_text + '</a>'
					+ '</div>'
				+ '</div>'
				+ '</div>'
				+ '<span class="dnd-upload-counter"><span>0</span> '+ dnd_wc_uploader.uploader_text.of +' '+ parseInt(options.max_file) +'</span>'
				+ '</div>';

			// Wrap input fields
			options.handler.wrapAll('<div class="codedropz-upload-wrapper"></div>');

			// Element Handler
			var form_handler = options.form,
				options_handler = options.handler.parents('.codedropz-upload-wrapper');

			// Append Format
			options.handler.after( cdropz_template);

			// Uploader Object
			var CodeDropzUploader = {

				// Initialize
				init : function() {

					var self = this;

					// Get clean Field Name...
					FileName = this.getFieldName( FileName );

					// Create queue with index of file
					DataQueue[FileName] = [];

					// Data Record
					FileDataRecord[FileName] = {
						total 			: 0,
						uploaded 		: 0,
						uploading 		: true, // true if auto start upload / set to false if not
						maxTotalSize 	: 0, // total size of all files
						maxSize 		: options.max_upload_size,	//kb format default : 5mb - for indivual
						maxFile 		: options.max_file, // total number of files that can be uploaded
					};

					// preventing the unwanted behaviours
					$('.codedropz-upload-handler', options_handler ).on( 'drag dragstart dragend dragover dragenter dragleave drop',  function( e ){
						e.preventDefault();
						e.stopPropagation();
					})

					// dragover and dragenter - add class
					$('.codedropz-upload-handler', options_handler ).on( 'dragover dragenter',  function( e ){
						$(this).addClass('codedropz-dragover');
					});

					// dragleave dragend drop - remove class
					$('.codedropz-upload-handler', options_handler ).on( 'dragleave dragend drop',  function( e ){
						$(this).removeClass('codedropz-dragover');
					});

					// Append codedropz-results container
					if( ! $('.' + FileOptions.progress__id, options_handler).length > 0 ) {
						options_handler.append('<div class="'+ FileOptions.progress__id +'"></div>');
					}

					// Begin upload files
					this.getUploadFiles();

					// Delete or Remove files
					$(document).on("click", 'a.remove-file', function(e){
						if( ! $(this).hasClass('deleting') ) {
							self.deleteFiles( $(this).data('index'), $(this), $(this).data('name') );
						}
					});
				},

				// Get Upload Filed Name
				getFieldName : function( FieldName ) {
					return FieldName.replace(/[^a-zA-Z0-9_-]/g, "");
				},

				// Remove files
				deleteFiles : function( index, a_remove, fieldName ) {

					var file;
					var fileData = DataQueue[fieldName];
					var $_this = this;

					// Add delete status...
					a_remove.addClass('deleting').text( dnd_wc_uploader.uploader_text.delete );

					// Loop files data queue
					for( var i=0; i < fileData.length; i++ ) {

						// Assign individual fileData to variable name - file
						file = fileData[i];

						// Check & Make sure we have property of file
						if( fileData[i].hasOwnProperty('file') ) {

							if( $.type(index) === "undefined" || file.index === index ) {

								// File started and not yet completed
								if( file.queued && file.complete == false && ! file.error ) {

									// Add abort status
									a_remove.addClass('deleting').text('aborting...');

									// Abort Upload & Remove progressbar
									this.abortFile( file );
									this.removeFile( file, i, fieldName );

								} else if( file.complete ) {

									// Assign file for ajax deletion ( Only `complete` status file ).
									var ajax_file = { _file : file, _index : i, _name : fieldName };

									// Remove uploaded file through ajax request
									data = {
										action 	 : 'dnd_codedropz_upload_delete_wc',
										security : dnd_wc_uploader.nonce,
										path 	 : $('input[data-index="'+ file.progressbar +'"]').val()
									}

									// Begin ajax delete
									$.post( options.ajax_url, data , function(response) {

										if( response.success ) {

											// Remove file & progress bar
											$_this.removeFile( ajax_file._file, ajax_file._index, ajax_file._name );

											//@debug - for developers
											console.log( ajax_file._file.name + ' - file deleted.');
										}

									});

								}else {

									// If there any error remove directly.
									this.removeFile( file, i, fieldName );
								}
							}
						}
					}
				},

				// Abort File Upload
				abortFile : function( file ) {
					if( FileOptions.chunking ) {
						if( file.chunkTransfer )
							file.chunkTransfer.abort();
					} else {
						file.transfer.abort();
					}
				},

				// Remove File - Specific File
				removeFile : function( file, i, FieldName ) {

					// Get parent element of file
					var CurrentProgress = $('#' + file.progressbar).parents('.codedropz-upload-wrapper');

					if( file && file.hasOwnProperty('file') && file.progressbar != null ) {

						// Find specific progress bar of file
						progressBar = CurrentProgress.find('#'+ file.progressbar );

						// Begin to remove progress bar
						if( progressBar.length > 0 ) {

							//DataQueue.splice( i, 1 );
							file.deleted = true; 	// set status deleted in Queue
							FileDataRecord[FieldName].total--; 	// reduce total
							FileDataRecord[FieldName].maxTotalSize = ( FileDataRecord[FieldName].maxTotalSize - file.size ); // minus total size of current deleted file.size

							// reduced file uploaded - counter
							if( file.complete && FileDataRecord[FieldName].uploaded > 0 ) {
								FileDataRecord[FieldName].uploaded--;
							}

							// Remove progress bar status
							progressBar.remove();

							// Remove input hidden
							$('input[data-index="'+ file.progressbar +'"]').remove();

							// Continue processing the queue
							this.resetQueue( FieldName );

						}else{
							console.log('Progress Bar not exists!');
						}

						// Hide any error
						if( FileDataRecord[FieldName].uploaded < FileDataRecord[FieldName].maxFile ) {
							CurrentProgress.find('span.has-error-msg').remove();
						}

						// Show error file - ( If Total Size Exceeds ) - PRO only
						if( FileDataRecord[FieldName].maxTotalSize > totalSize__Limit && options.is_pro == true ) {
							options_handler = CurrentProgress;
							this.validateFiles.setError( options.err_message['maxTotalSize'], false, options.max_total_size );
						}

						// Update counter
						$('.dnd-upload-counter span', $('input[data-name="'+FieldName+'"]').parents('.codedropz-upload-wrapper')).text( FileDataRecord[FieldName].total );

						//console.log(FileDataRecord); // debug
					}
				},

				// Get upload files
				getUploadFiles : function() {
					var _this = this;

					// when dropping files
					$('.codedropz-upload-handler', options_handler ).on('drop', function(event){
						_this.handleFiles( event.originalEvent.dataTransfer.files ); // Run the uploader
					});

					// Browse button clicked
					$( 'a.cd-upload-btn', options_handler ).on("click", function(e){
						e.preventDefault(); // stops the default action of an element from happening
						options.handler.val(null); // Reset value &
						options.handler.click(); // Click input type[file] element
					});

					// Trigger when input type[file] is click/changed
					options.handler.on("change", function(e){
						_this.handleFiles( this.files ); // Run the uploader
					});
				},

				// Handle Upload
				handleFiles : function( files ) {
					var numFiles = files.length,
						file = [];

					// Check max file limit - ex : 5
					if( FileDataRecord[FileName].maxFile ) {
						var remainingFiles = FileDataRecord[FileName].maxFile - FileDataRecord[FileName].uploaded; // ( 5:max - 3:uploaded = 2 remaining )
						if( remainingFiles >= 0 && files.length > remainingFiles ) { // 5:len > 3:remaining
							numFiles = remainingFiles;
						}
						if( FileDataRecord[FileName].uploaded == 0 && FileDataRecord[FileName].total > 0 ) {
							numFiles = ( FileDataRecord[FileName].maxFile - FileDataRecord[FileName].total );
						}
					}

					// Remove error message
					$('span.has-error-msg', options_handler).remove();

					// Total files reached max file limit
					if( FileDataRecord[FileName].total >= FileDataRecord[FileName].maxFile ) {
						return this.validateFiles.setError( options.err_message['maxNumFiles'], true, options.max_file );
					}

					// Loop Files - make sure we have remaining items ( greater > 0 ), /\ if reached 0 - it means zero remaining files
					if( numFiles > 0 ) {

						for( var i = 0; i < numFiles; i++ ) {

							// Generate unique index key
							$unique_index = 'index-' + Date.now().toString(36) + Math.random().toString(36).substr(2, 5);

							// Supply Files details
							file = {
								index: $unique_index,
								file: files[i],
								name: files[i].name,
								size: files[i].size,
								queued: false,
								complete: false,
								error: false,
								pause : false,
								transfer: null,
								progressbar : null,
								deleted : false,
							};

							// Check if file already exceeds the max upload limit
							if( files.length - numFiles > 0  ) {
								this.validateFiles.setError( options.err_message['maxUploadLimit'], false, options.max_file );
							}

							// Increment File Index
							FileDataRecord[FileName].total++;

							// Add up total size of file
							FileDataRecord[FileName].maxTotalSize += file.size;

							// Create progress
							file.progressbar = this.progressBar.make( file );

							// Files - validation ( check file_type, file size etc... )
							if( this.validateFiles.check( file, FileName ) === false ) {
								file.error = true;
							}

							//@debug - dev purposes
							//$('.codedropz-btn-wrap a').text( CodeDropzUploader.bytesToSize( FileDataRecord[FileName].maxTotalSize ) +' of '+ options.max_total_size );

							// Check total size for all files - For PRO only
							if( ( FileDataRecord[FileName].maxTotalSize ) > totalSize__Limit && options.is_pro == true ) {
								this.validateFiles.setError( options.err_message['maxTotalSize'], true, options.max_total_size );
								file.pause = true;
							}

							// Push new file to Queue
							DataQueue[FileName].push( file );

						}
					}

					// Set uploading - to start queue / auto upload
					FileDataRecord[FileName].uploading = true;

					// Process QUEUE files
					this.processQueue( DataQueue[FileName], FileName );

				},

				// File validations
				validateFiles : {

					// Add error message
					setError : function( msg, _return, error_code ) {

						// Remove error msg
						$('span.has-error-msg', options_handler).remove();

						// Append error msg
						$('.' + FileOptions.progress__id, options_handler).after('<span class="has-error-msg">'+ msg.replace( '%s', error_code ) +'</span>');

						// return bolean
						if( _return ) {
							return false;
						}

					},

					// Check files
					check : function( file, FileName ){

						// Make sure we have files before we can validate.
						if( ! file ) return true;

						// Begin to validate
						if( file.progressbar ) {

							// Get specific Element (progress bar ID) of File
							var progress__bar = $('#'+ file.progressbar).find('.dnd-upload-details');

							// Reset & remove error msg
							$('#'+ file.progressbar).find('.has-error').remove();

							// Filesize validation
							if( file.size > FileDataRecord[FileName].maxSize ) {
								progress__bar.append('<span class="has-error">'+ dnd_wc_uploader.drag_n_drop_upload.large_file +'</span>');
								return false;
							}

							// Filetype validation
							regex_type = new RegExp("(.*?)\.("+ options.supported_type +")$");
							if ( !( regex_type.test( file.name.toLowerCase() ) ) ) {
								progress__bar.append('<span class="has-error">'+ dnd_wc_uploader.drag_n_drop_upload.inavalid_type +'</span>');
								return false;
							}

						}

						return file;
					}
				},

				// Reset/Resume file Queue
				resetQueue : function( name ) {

					// Assign filtered file to new queue
					var newQueue = [];

					// Set option uploading to start uploading a file...
					FileDataRecord[name].uploading = true;

					// Loop DataQueue
					if( DataQueue[name].length > 0 ) {
						for( var e in DataQueue[name] ) {

							// Delete file from queue
							if( ! DataQueue[name][e].deleted == true ) {
								newQueue.push( DataQueue[name][e] );
							}

							// Resume if file status pause
							if( DataQueue[name][e].pause == true ) {
								if( FileDataRecord[name].maxTotalSize < totalSize__Limit ) {
									DataQueue[name][e].pause = false;
								}
							}
						}
					}

					if( newQueue.length > 0 ) {
						DataQueue[name] = newQueue;
					}

					// Re process Queue if there's remaining...
					this.processQueue( DataQueue[name], name );

					//@For debug purposes...
					console.log( DataQueue[name] );
					console.log( FileDataRecord[name] );
					console.log( CodeDropzUploader.bytesToSize(FileDataRecord[name].maxTotalSize) +' of '+ options.max_total_size );
				},

				// Process Queue Data
				processQueue : function( data, name ){

					var transfering = 0;
					var forQueue = [];

					if( ! FileDataRecord[name].uploading ) {
						return;
					}

					// Queue & Ignore files that has `error` and `completed` - assign to new Queue
					for( var i in data ) {
						if( data[i].complete == false && data[i].error == false ) {
							if( data[i].pause == false ) {
								forQueue.push( data[i] );
							}
						}
					}

					// in progress hooks
					if ( $.isFunction( options.in_progress ) ) {
						options.in_progress.call( this, form_handler, forQueue, DataQueue[name] );
					}

					// Loop Newly Queued files
					for( var i=0; i < forQueue.length; i++ ) {
						if( forQueue[i].hasOwnProperty('file') ) {

							// Get the files that are not being queued
							if( forQueue[i].queued == false ) {
								this.uploadFile( DataQueue[name], forQueue[i], name );
							}

							// Transferring increment ( so we can match into parallel uploads )
							transfering++;

							// parallel uploads - how many files uploaded at the same time - for PRO only
							if( transfering >= FileOptions.parallelUploads && options.is_pro == true ) {
								return;
							}
						}
					}

					// All uploads are completed - js hook
					if( transfering == 0 ) {
						FileDataRecord[name].uploading = false;
						if ( $.isFunction( options.completed ) ) {
							options.completed.call( this, form_handler, name, DataQueue[name] );
						}
					}

				},

				// Create progress bar
				progressBar : {

					// Make a progress bar elements
					make : function( file ) {
						// Setup progress bar variable
						var generated_ID = 'dnd-file-' + Math.random().toString(36).substr(2, 9);

						// Setup progressbar elements
						var fileDetails = '<div class="dnd-upload-image"><span class="icon-images"></span></div>'
							+ '<div class="dnd-upload-details">'
							+ '<span class="name"><span>'+ file.name +'</span> <em>('+ CodeDropzUploader.bytesToSize( file.size ) +')</em></span>'
							+ '<a href="javascript:void(0)" title="'+ dnd_wc_uploader.uploader_text.remove +'" class="remove-file" data-name="'+ FileName +'" data-index="'+ file.index +'"><span class="icon-close-outline"></span></a>'
							+ '<span class="dnd-progress-bar"><span></span></span>'
							+ '</div>';

						// Append file details
						$('.' + FileOptions.progress__id, options_handler).append('<div id="'+ generated_ID +'" class="dnd-upload-status">'+ fileDetails +'</div>');

						return generated_ID;
					},

					// Set progress bar size
					setProgress : function( statusbar, percent ) {
						var statusBar = $( '.dnd-progress-bar', $('#' + statusbar) );
						if( statusBar.length > 0 ) {

							// Compute Progress bar percentage
							progress_width = ( percent * statusBar.width() / 100);

							// Start progress animation
							$('span', statusBar ).addClass('in-progress').animate({ width: progress_width }, 10).text( percent + '% ');

							// Set progress bar to 100%
							if( percent == 100 ) {
								$('span', statusBar ).addClass('complete').removeClass('in-progress');
							}
						}
						return false;
					}
				},

				// Upload Single File
				uploadFile : function( data, file, name ) {
					var _this = this;

					// gathering the form data
					var formData = new FormData();
					var chunkSize = ( 1024 * FileOptions.chunkSize ); // convert ( 1024 Bytes = 1KB ) ( 1MB = 1000KB )

					// Setup form data
					formData.append('supported_type', options.supported_type );
					formData.append('size_limit', options.max_upload_size );
					formData.append('action', 'dnd_codedropz_upload_wc' );

                    // Security nonce
                    formData.append('security', dnd_wc_uploader.nonce );

					// Chunks file upload - for PRO only
					if( FileOptions.chunking && file.size > chunkSize && options.is_pro == true ) {
						file.queued = true;
						file.chunkSize = chunkSize;
						file.totalChunks = Math.ceil( file.size / file.chunkSize );
						file.currentChunk = 0;
						this.uploadChunks( data, file, name );
					} else {

						// set queued to true
						file.queued = true;

						// Append file
						formData.append('dnd-wc-upload-file', file.file );

						// Process ajax upload
						file.transfer =  $.ajax({
							url			: options.ajax_url,
							type		: form_handler.attr('method'),
							data		: formData,
							dataType	: 'json',
							cache		: false,
							contentType	: false,
							processData	: false,
							xhr			: function(){
								//objects to interact with servers.
								var _xhr = new window.XMLHttpRequest();

								// reference : https://stackoverflow.com/questions/15410265/file-upload-progress-bar-with-jquery
								_xhr.upload.addEventListener("progress", function(event){
									if ( event.lengthComputable ) {
										var percentComplete = ( event.loaded / event.total );
										var	percentage = parseInt( percentComplete * 100 );

										// Progress Loading
										_this.progressBar.setProgress( file.progressbar, percentage );

									}
								}, false);

								return _xhr;
							},
							complete	: function() {
								// Set progress bar to 100%
								_this.progressBar.setProgress( file.progressbar, 100 );
							},
							success: function( response ) {
								if( response.success ) {

									// Complete file
									file.complete = true;

									// Increment Uploaded counter
									FileDataRecord[name].uploaded++;

									// Run uploaded again
									_this.processQueue( data, name );

									// Callback on success
									if ( $.isFunction( options.on_success ) ) {
										options.on_success.call( this, file.progressbar, response, name, FileDataRecord[name] );
									}

								}else {

									// Display erro message
									$('#' + file.progressbar)
										.find('.dnd-upload-details')
											.append('<span class="has-error">'+ response.data +'</span>');


									// Set file status to error
									file.error = true;

									// Run uploaded again
									_this.processQueue( data, name );
								}
							},
							error: function( xhr,ajax,thrownError ) {

								// Display Progress bar - with erro msg
								$('#'+ file.progressbar)
								.find('.dnd-upload-details')
									.append('<span class="has-error">'+ thrownError +'</span>');

								// Set file has error & process queue
								file.error = true;
								_this.processQueue( data, name );
							}
						});
					}

				},

				// Upload files in chunks
				uploadChunks : function( data, file, name ){

					var chunk_start = ( file.chunkSize * file.currentChunk ), // @description: chunk_start = (5000 * 0) // @currentChunk( increment : 0,1,2,3 )
						chunk_end = ( chunk_start + file.chunkSize),
						$_this = this;

					// If size - end less than 0 end should be total file of size
					if ( chunk_end > file.size ) {
						chunk_end = file.size;
					}

					// Begin Slicing files here ( file, start , end )
					var slicedPart = this.sliceFile( file.file, chunk_start, chunk_end );
					var formData = new FormData();

					// Security
					formData.append('security', dnd_wc_uploader.nonce );

					// Append needed data
					formData.append('start', chunk_start );
					formData.append('end', chunk_end );
					formData.append('total_chunks', file.totalChunks ); // Count Total of chunks ( file.size / FileOptions.chunkSize )
					formData.append('chunk_size', file.chunkSize );
					formData.append('chunk', file.currentChunk );
					formData.append('chunks-file', slicedPart, file.file.name );
					formData.append('post_id', input.data('id') );
					formData.append('action', 'dnd_codedropz_upload_chunks_wc' );

					// Begin ajax request
					file.chunkTransfer = $.ajax({
						url			: options.ajax_url,
						type		: form_handler.attr('method'),
						dataType	: 'json',
						data		: formData,
						type		: "POST",
						contentType	: false,
						processData	: false,
						cache		: false,
						success		: function( response, status, jqXHR ) {

							// Increment currentChunk
							file.currentChunk++;

							// Compuate percentage ( divide currentChunk & totalChunks )
							chunks_percentage = Math.ceil( ( file.currentChunk / file.totalChunks ) * 100 ) ; //@description - 2 / 3 = 66 percent

							// Animate progress bar
							$_this.progressBar.setProgress( file.progressbar, chunks_percentage );

							if( file.currentChunk < file.totalChunks ) { //@Recursive - until currentChunk is Equal to TotalChunks ( ex: 3 < 4 )
								$_this.uploadChunks( data, file, name );
							}

							// Logs chunks
							console.log( file.name +' [chunk -'+ file.currentChunk +' of '+ file.totalChunks +']' );

							// Check and make sure we have response
							if( response && typeof response != 'undefined' ) {

								// Complete File & Increment Uloaded - FileOptions
								file.complete = true;
								FileDataRecord[name].uploaded++;

								// run queue
								$_this.processQueue( data, name );

								// Callback on success
								if ( $.isFunction( options.on_success ) ) {
									options.on_success.call( this, file.progressbar, response, name, FileDataRecord[name] );
								}
							}

						},
						error: function( xhr, ajax, thrownError ) {

							// Display Progress bar - with erro msg
							$('#'+ file.progressbar)
								.find('.dnd-upload-details')
									.append('<span class="has-error">'+ thrownError +'</span>');

							// Set file has error - and process queue
							file.error = true;
							$_this.processQueue( data, name );
						}
					});
				},

				// File Slicing
				sliceFile : function( file, start, end ) {
					var slice = file.mozSlice ? file.mozSlice :	file.webkitSlice ? file.webkitSlice : file.slice ? file.slice : {};
					return slice.bind(file)(start, end);
				},

				// Size Conversion
				bytesToSize : function( bytes ) {
					if( bytes === 0 ) return '0';
					kBytes = ( bytes / 1024 );
					fileSize = ( kBytes >= 1024 ? ( kBytes / 1024 ).toFixed(2) + 'MB' : kBytes.toFixed(2) + 'KB' );
					return fileSize;
				},
			}

			// Initialize plugin
			CodeDropzUploader.init();

		});

	}
}( jQuery ));