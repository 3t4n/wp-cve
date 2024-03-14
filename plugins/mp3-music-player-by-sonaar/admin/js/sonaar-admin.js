(function($) {
	$( document ).on('change', '#inspector-select-control-0, #inspector-toggle-control-0, #inspector-toggle-control-1, #inspector-toggle-control-2, #inspector-toggle-control-3, #inspector-toggle-control-4', function (e) {
		setTimeout(function(){ 
			IRON.players = []
			$('.iron-audioplayer').each(function(){

				var player = Object.create(  IRON.audioPlayer )
				player.init($(this))

				IRON.players.push(player)
			})
		}, 2500);
	});

	var post_audiopreview_all_ready = false;
	$('#post_audiopreview_all').on('change', function() {
		if(post_audiopreview_all_ready){
		// Get the selected value from the main selector
		var selectedValue = $(this).val();
		// Set the value of all other selectors to match the main selector's value
		$('select[name^="alb_tracklist["][name$="[post_audiopreview]"]').val(selectedValue).trigger('change');
		}
		post_audiopreview_all_ready = true;
	});



	$(document).ready(function() {
		
		//srmp3_option_page_accordeons_tabs(); // this cause major issue with the condition!! so we dont use it for now.
		
		var $myRepeatGroup = $('#alb_tracklist_repeat');
		if ($myRepeatGroup.length) {
			//only execute if we are in presence of album repeater group.
			init_TrackTitleOnRepeater();
			addTrackTitletoTrackRepeater();
			init_toggleTracklistBox();
			hideShowTracklistStorelist();
			
			$( document ).on('cmb2_add_row', function (event, newRow) {
				init_TrackTitleOnRepeater();
			});
		}

		init_srmp3_generate_bt();
		init_srmp3_tools();
		init_srmp3_audioPreview();
		init_srmp3_importTemplates();
	});

	function init_srmp3_importTemplates(){
		// check if $('.srmp3_import_overlay') is not present, then we dont need to init the function
		if(!$('.srmp3_import_overlay').length) return;

		var options = {
			valueNames: [ 'srp-tmpl-title' ],
			listClass: 'template-list',
			searchClass: 'srp_search',
		};
		
		srpTemplatesSearch =  new List('srp_templates_container', options);

		// Attach a click event handler to the import button
		$('.srmp3_import_overlay').click(function(e){
			e.preventDefault();
			var elt = jQuery(this);
			var please_wait = elt.parent().find('.srmp3_importing');
			elt.css('background-color', '#00000057');
			$('.srmp3_import_notice').hide();
			please_wait.show();
			var json_file = $(this).data('filename');
			var data = {
				action: 'import_srmp3_template',
				nonce: sonaar_admin_ajax.ajax.ajax_nonce,
				filename: json_file
			};
			$.post(
				sonaar_admin_ajax.ajax.ajax_url,
				data, 
				function(response) {
					var obj;
		
					obj = $.parseJSON(response);
					elt.show();
					please_wait.hide();
					$("html, body").animate({ scrollTop: 0 }, "slow");
					console.log(obj);
					if(obj.success === true) { 
						$('.srmp3_import_success').show();
					} else {
						$('.srmp3_import_error_message').remove();
						$('.srmp3_import_failed').append('<div class="srmp3_import_error_message">' + obj.message + '</div>');
						$('.srmp3_import_failed').show();
					}
				});

		});
	}
	function init_srmp3_generate_bt(){
		if($('.srmp3-generate-bt').length && $('#acf_albums_infos .ffmpeg-not-installed').length === 0){
			const url = new URL(window.location.href);
			var posts_in = url.searchParams.get("posts_in");
			//seperate the post ids with commas
			if(posts_in){
				//count how many posts_in are set
				var posts_in_count = posts_in.split(',').length;
				posts_in = posts_in.replace(/,/g, ', ');
				$('.nav-tab-wrapper').after('<h1 style="color:#7500df;font-size:18px;" class="audiopreview_posts_in_notice"><strong>Action required!</strong> ' + posts_in_count + ' posts are ready to have their audio previews generated.<br>Review the settings below and click <strong>Generate</strong> Button.</h3><div style="font-size:10px;">Posts: ' + posts_in + '</div>');
				$('#srmp3_indexTracks_status').text('We will proceed with ' + posts_in_count + ' posts.');
			}
			setTimeout(function(){ // timeout needed to allow jquery datepicker to load
				// Select all the input fields and checkboxes within your DOM structure
				const inputsAndCheckboxes = document.querySelectorAll('.cmb-row input, .cmb-row select, #ui-datepicker-div');
				// Add an event listener to each input and checkbox
				inputsAndCheckboxes.forEach(function(input) {
					if (input.type === 'text') {
						input.addEventListener('input', handleInputChange);
					} else {
						input.addEventListener('change', handleInputChange);
					}
				});
				// Add the event listener for the upload and remove actions
				document.querySelectorAll('.file-status, .cmb2-upload-button, .cmb2-remove-file-button').forEach(function(element) {
					element.addEventListener('click', handleInputChange);
				});
			}, 2000);
			
			function handleInputChange(event) {
				
				const excludedIds = ['peaks_overwrite1', 'peaks_overwrite2']; // Add more IDs as needed

				// Check if the target element has an ID and if that ID is in the exclusion list
				if (event.target.id && excludedIds.includes(event.target.id)) {
					return; // Exclude this element
				}
				$('.srmp3-generate-bt').css('opacity', '0.5').css('pointer-events', 'none');
				
				// Check if the message is already present; if not, add it
				if ($('#saveChangeMessage').length === 0) {
					$('#srmp3_indexTracks').after('<span id="saveChangeMessage" style="margin-left:10px; color:red;">Save changes and refresh this page before rebuiling index.</span>');
					$('.srmp3-audiopreview-bt').after('<span id="saveChangeMessage" style="margin-left:10px; color:red;">Save changes and refresh this page before generate previews.</span>');
				}
			}
		}
	}

	function init_srmp3_tools(){
		if ($('.option-srmp3_settings_tools').length) {
		}else{
			return;
		}
		//console.log("INIT SRMP3 AUDIOREVIEW");
		// For audio preview generation

		var continueIndexing = true;
		// For lazyload search indexation
		function isFirefox() {
			return navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
		}
		if (!isFirefox()) {
			$('#srmp3-settings-generatepeaks-bt-container').after('<div style="margin-top:10px;color:red;"><strong>Warning:</strong> Peaks Generation is resource-intensive. For audio tracks longer than 30 minutes, consider using Firefox for better memory management. If your browser crashes during peak generation, try Firefox.</div><br>');
        }
		$('.srmp3-generatepeaks-bt').click(function(e) {
			// GENERATE BT SPECIFIC TRACK
			$(this).css('opacity', '0.5').css('pointer-events', 'none');
			e.preventDefault();

			$(this).siblings('#indexationProgress').css('display', 'inline-block').val(0);
			originalText = $(this).text();
			$(this).text("Generating the file(s)...");
			$(this).addClass('spinningIcon showSpinner').removeClass('showCheckmark');

			if (!$(this).siblings('#stopGeneratePeaksButton').length) {
				$(this).after('<button id="stopGeneratePeaksButton" class="srmp3-stopgenerate-bt">Stop</button>');
			}
			$(this).siblings('#srmp3_indexTracks_status').text('Processing...');
			
			const audioContext = new (window.AudioContext || window.webkitAudioContext)();

			get_audio_files(0, originalText, $(this), audioContext);


			$(document).on('click', '#stopGeneratePeaksButton', function() {
				continueIndexing = false;
				var btn = $(this).siblings('.srmp3-generatepeaks-bt');
				btn.text(originalText)
					.removeClass('showSpinner spinningIcon')
					.addClass('showCheckmark')
					.css('opacity', '1')
					.css('pointer-events', 'initial');
	
				btn.siblings('#srmp3_indexTracks_status').text('Stopped by user. ');
				
				$(this).siblings('#indexationProgress').css('display', 'none');
				$(this).remove();
			});

			function get_audio_files(index, originalText, $clickedButton, audioContext){
				if (!continueIndexing) {
					return;
				}

				var overwrite = document.querySelector('.cmb2-id-peaks-overwrite .cmb2-enable.selected') ? 'true' : 'false';

				$.ajax({
					url: sonaar_admin_ajax.ajax.ajax_url,
					type: 'post',
					dataType: 'json',
					data: {
						action: 'get_audio_files',
						nonce: sonaar_admin_ajax.ajax.ajax_nonce,
						offset: index,
						overwrite: overwrite,
					},
					success: function(response) {
						if(response.error){
							$clickedButton.siblings('#srmp3_indexTracks_status').text(response.error);
							return;
						}
				
						this.audioContext;
						
						generatePeaks(response.files, 0, audioContext);
						
						if (response.totalPosts && response.processedPosts) {
							$clickedButton.siblings('#progressText').text(response.processedPosts + " / " + response.totalPosts + " posts");
						}
	
						if (response.progress) {
							$clickedButton.siblings('#indexationProgress').val(Math.round(Number(response.progress)));
						}
			
						if (response.message) {
							if(indexPos != trackLength && !completed){
								$clickedButton.siblings('#srmp3_indexTracks_status').text(response.message);
							}
						}

						if (response.completed) {
							$clickedButton.siblings('#stopIndexingButton').remove();
							$clickedButton.siblings('#indexationProgress').css('display', 'none');
							$clickedButton.siblings('#stopGeneratePeaksButton').css('display', 'none');
							$clickedButton.siblings('#srmp3_indexTracks_status').text('Completed 🎉 ' + response.message);
							$clickedButton
								.text(originalText)
								.removeClass('showSpinner spinningIcon')
								.addClass('showCheckmark')
								.css('opacity', '1')
								.css('pointer-events', 'initial');

						}else{
							index +=1;
							get_audio_files(index, originalText, $clickedButton, audioContext);
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.error("Error: ", textStatus, errorThrown);
					},
				});

			}
			async function generatePeaks(files, currentIndex, audioContext) {
				// Base condition to stop recursion
				if (currentIndex >= files.length) {
					return;
				}
				const file = files[currentIndex];
				try {
					const response 		= await fetch(file.file);
					const arrayBuffer 	= await response.arrayBuffer();
					try {
						console.log('Try to decode audio for:' , file.post_id , ' index: ', file.index, ' file: ', file.file);
						var audioBuffer = await audioContext.decodeAudioData(arrayBuffer);
						let peaks 		= IRON.extractPeaks(audioBuffer);
						IRON.updatePeaksOnServer(file.post_id, file.media_id, file.index, peaks, file.file, file.is_temp, file.is_preview);
						 // Attempt to release the audioBuffer memory
						 audioBuffer = null;
						// Process the next file after the current one is done
						await generatePeaks(files, currentIndex + 1, audioContext);
					} catch (decodeError) {
						deleteTempFile(file.file, file.is_temp);
						
						console.error('Error decoding file:', file.file, decodeError);
						// Even if there is an error, proceed with the next file
						await generatePeaks(files, currentIndex + 1, audioContext);
					}
				} catch (fetchError) {
					console.error('Error fetching file:', file.file, fetchError);
					// Proceed with the next file in case of fetch error
					await generatePeaks(files, currentIndex + 1, audioContext);
				}
			}

			function deleteTempFile(file, is_temp){
				if(!is_temp) return;

				console.log('try to delete the file');
				$.ajax({
					url: 		sonaar_admin_ajax.ajax.ajax_url,
					type: 		'post',
					dataType: 	'json',
					data: {
						action: 'removeTempFiles',
						nonce: sonaar_admin_ajax.ajax.ajax_nonce,
						file: file,
						is_temp: true,
					},
					success: function(response) {
						console.log('File deleted');
					},
					error: function(textStatus, errorThrown) {
						console.error("Error deleting file", textStatus, errorThrown);
					}
				});
			}
		});
		var delete_bt_originalText = $('#srmp3-bulkRemove-bt').html();

		function countPeakFiles_AJAX(){			
			$.ajax({
				url: sonaar_admin_ajax.ajax.ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'count_peak_files',
					nonce: sonaar_admin_ajax.ajax.ajax_nonce,
				},
				success: function(response) {
					var fileCount = response.count;
					// Append fileCount to the original text
					$('#srmp3-bulkRemove-bt').html(delete_bt_originalText + ' (' + fileCount + ')');
				},
				error: function(xhr, status, error) {
					//return false;
				}
			});
		}
		countPeakFiles_AJAX();
		$('#srmp3-bulkRemove-bt').click(function(e) {
			e.preventDefault();

			// Disable the button to avoid multiple clicks
			$(this).prop('disabled', true);

			$.ajax({
				url: sonaar_admin_ajax.ajax.ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'count_peak_files',
					nonce: sonaar_admin_ajax.ajax.ajax_nonce
				},
				success: function(response) {
					var fileCount = response.count;
					var userConfirmation = confirm("Time to cleanup !?\n\nAre you sure you want to remove the " + fileCount + " peak files on your server? Do not worry, this will NOT delete or remove any of your audio files.");
					if (userConfirmation) {
						// Start the removal process
						$.ajax({
							url: sonaar_admin_ajax.ajax.ajax_url,
							type: 'post',
							dataType: 'json',
							data: {
								action: 'remove_peak_files_and_update_posts',
								nonce: sonaar_admin_ajax.ajax.ajax_nonce
							},
							success: function(response) {
								if(response.error){
									alert('There was an error: ' + response.error);
									return;
								};
								if(response.success) {
									countPeakFiles_AJAX();
									alert('All temporary peak files removed.');
								} else {
									alert('There was an error: ' + response.message);
								}
								// Enable the button again
								$('#srmp3-bulkRemove-bt').prop('disabled', false);
							},
							error: function(xhr, status, error) {
								alert('An error occurred: ' + error);
								// Enable the button again
								$('#srmp3-bulkRemove-bt').prop('disabled', false);
							}
						});
					}else{
						$('#srmp3-bulkRemove-bt').prop('disabled', false);
					}
				},
				error: function(xhr, status, error) {
					alert('An error occurred while counting the files: ' + error);
				}
			});
		});

		$('#srmp3_indexTracks').click(function(e) {
			e.preventDefault();
			$('#srmp3_indexTracks').siblings('#indexationProgress').css('display', 'inline-block').val(0);
			var originalText = $(this).text();
			$(this).text("Indexing Tracks...");
			$(this).addClass('spinningIcon showSpinner').removeClass('showCheckmark');
			indexPosts_AJAX(0, originalText);
		});

		function indexPosts_AJAX(offset, originalText) {
			$.ajax({
				url: sonaar_admin_ajax.ajax.ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'index_alb_tracklist_for_lazyload',
					nonce: sonaar_admin_ajax.ajax.ajax_nonce,
					offset: offset
				},
				success: function(response) {
					console.log(response);
		
					if (response.totalPosts && response.processedPosts) {
						$('#srmp3_indexTracks').siblings('#progressText').text(response.processedPosts + " / " + response.totalPosts + " posts");
					}
		
					if (response.progress) {
						$('#srmp3_indexTracks').siblings('#indexationProgress').val(Math.round(Number(response.progress)));
					}
		
					if (response.message) {
						$('#srmp3_indexTracks').siblings('#srmp3_indexTracks_status').text(response.message);
					}
		
					if (response.completed) {
						$('#srmp3_indexTracks').siblings('#indexationProgress').css('display', 'none');
						$('#srmp3_indexTracks').siblings('#srmp3_indexTracks_status').text('Completed 🎉 ' + response.message);
						$('#srmp3_indexTracks')
							.text(originalText)
							.removeClass('showSpinner spinningIcon')
							.addClass('showCheckmark');
					} else {
						indexPosts_AJAX(offset + 250, originalText);
					}
				},
				error: function() {
					$('#srmp3_indexTracks').text(originalText);
					$('#srmp3_indexTracks').siblings('#srmp3_indexTracks_status').text('An error occurred.');
				}
			});
		}

	}


	function init_srmp3_audioPreview(){
		if ($('.option-srmp3_settings_audiopreview').length || $('#acf_albums_infos').length) {
		}else{
			return;
		}
		//console.log("INIT SRMP3 AUDIOREVIEW");
		// For audio preview generation

		var continueIndexing = true;
		var completed;
		var originalText;

		if ($('.option-srmp3_settings_audiopreview .ffmpeg-not-installed').length) {
			$('.option-srmp3_settings_audiopreview .ffmpeg_field').css('opacity', '0.5').css('pointer-events', 'none');
			$('.option-srmp3_settings_audiopreview .cmb2-id-audiopreview-generate-settings-title').css('opacity', '0.5').css('pointer-events', 'none');
		}

		if ($('#acf_albums_infos .ffmpeg-not-installed').length) {
			$('#acf_albums_infos .ffmpeg_field:not(.srmp3-settings-generate-bt-container)').css('opacity', '0.5').css('pointer-events', 'none');
			$('#acf_albums_infos .srmp3-generate-bt').css('opacity', '0.5').css('pointer-events', 'none');
			$('#acf_albums_infos .srmp3-generate-bt').after('<span class="ffmpeg-required" style="font-size:10px;margin-left:10px; color:#9d0000;">FFMpeg Library required to generate preview automatically  <a href="https://sonaar.io/docs/how-to-add-audio-preview-in-wordpress/" target="_blank">Learn More</a></span>');
			$('#acf_albums_infos .ffmpeg-required').css('opacity', '0.7');
		}

		if ($('.option-srmp3_settings_audiopreview .audiopreview-denied').length) {
			$('.option-srmp3_settings_audiopreview .cmb-row:not(:first-child):not(:nth-child(2))').css('opacity', '0.5').css('pointer-events', 'none');
			$('.option-srmp3_settings_audiopreview .submit').css('opacity', '0.5').css('pointer-events', 'none');
		}

		$('.srmp3-cmb2-preview-file .file-status.cmb2-media-item').each(function() {
			let content = $(this).html();
			content = content.replace(/&nbsp;&nbsp;/g, '');
			$(this).html(content);
		});

		$('.srmp3-post-all-audiopreview-bt').click(function(e) {
			// GENERATE BT POST ALL
			trackLength = $('.srmp3-audiopreview-bt').length;
			e.preventDefault(); // Prevent any default behavior of the button
			var userConfirmation = confirm("Are you sure you want to proceed?\n\nWe will generate " + trackLength + " preview files.");
			if (userConfirmation) {
				$('.srmp3-audiopreview-bt').trigger('click');
				$(this).css('opacity', '0.5').css('pointer-events', 'none');
				$(this).siblings('#srmp3_indexTracks_status').text('Processing...');
				$(this).addClass('spinningIcon showSpinner').removeClass('showCheckmark');
				completed = false;
			} // Trigger click event on all elements with the class srmp3-audiopreview-bt
		});

		$('.srmp3-audiopreview-bt').click(function(e) {
			// GENERATE BT SPECIFIC TRACK
			$(this).css('opacity', '0.5').css('pointer-events', 'none');
			var parentRow = $(this).closest('.cmb-row');
			e.preventDefault();

			$(this).siblings('#indexationProgress').css('display', 'inline-block').val(0);
			originalText = $(this).text();
			$(this).text("Generating the file(s)...");
			$(this).addClass('spinningIcon showSpinner').removeClass('showCheckmark');

			if (!$(this).siblings('#stopIndexingButton').length) {
				$(this).after('<button id="stopIndexingButton" class="srmp3-stopgenerate-bt">Stop</button>');
			}
			$(this).siblings('#srmp3_indexTracks_status').text('Processing...');
			var posts_in;
			var postID = $('#post_ID').val();
			var index = null;
			if (postID) {
				//console.log('we are in a POST !!');
				var selectElem = parentRow.prevAll('.cmb-row').find('select.cmb2_select[name^="alb_tracklist["]').first();

				if (selectElem.length > 0) {
					var selectName = selectElem.attr('name');
					var indexMatch = selectName.match(/\[(\d+)\]/);
					index = indexMatch ? indexMatch[1] : null;
				} else {
					console.error("Select element not found!");
				}
			}else{
				//check if we have post_id in the URL query
				const url = new URL(window.location.href);
				var posts_in = url.searchParams.get("posts_in");

			}

			indexAudioPreview_AJAX(0, originalText, postID, index, $(this), posts_in);
		});


		$(document).on('click', '#stopIndexingButton', function() {
			continueIndexing = false;
			countFiles_AJAX();
			var btn = $(this).siblings('.srmp3-audiopreview-bt');
			btn.text(originalText)
				.removeClass('showSpinner spinningIcon')
				.addClass('showCheckmark')
				.css('opacity', '1')
				.css('pointer-events', 'initial');

			btn.siblings('#srmp3_indexTracks_status').text('Stopped by user. ');
			
			$('#indexationProgress').css('display', 'none');
			$(this).remove();
		});

		var delete_bt_originalText = $('#srmp3-bulkRemove-bt').html();

		function countFiles_AJAX(){
			if($('.ffmpeg-not-installed').length || $('.audiopreview-denied').length) return;

			//need a better check here should move it up
			if(!$('.option-srmp3_settings_audiopreview').length) return; 
			
			$.ajax({
				url: sonaar_admin_ajax.ajax.ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'count_audio_files',
					nonce: sonaar_admin_ajax.ajax.ajax_nonce,
				},
				success: function(response) {
					var fileCount = response.count-1;
					// Append fileCount to the original text
					$('#srmp3-bulkRemove-bt').html(delete_bt_originalText + ' (' + fileCount + ')');
				},
				error: function(xhr, status, error) {
					//return false;
				}
			});
		}
		countFiles_AJAX();
		$('#srmp3-bulkRemove-bt').click(function(e) {
			e.preventDefault();

			// Disable the button to avoid multiple clicks
			$(this).prop('disabled', true);

			$.ajax({
				url: sonaar_admin_ajax.ajax.ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'count_audio_files',
					nonce: sonaar_admin_ajax.ajax.ajax_nonce
				},
				success: function(response) {
					var fileCount = response.count;
					var userConfirmation = confirm("Time to cleanup !?\n\nAre you sure you want to remove the " + fileCount + " audio preview files on your server? You will need to re-generate them again.");
					if (userConfirmation) {
						// Start the removal process
						$.ajax({
							url: sonaar_admin_ajax.ajax.ajax_url,
							type: 'post',
							dataType: 'json',
							data: {
								action: 'remove_audio_files_and_update_posts',
								nonce: sonaar_admin_ajax.ajax.ajax_nonce
							},
							success: function(response) {
								if(response.success) {
									countFiles_AJAX();
									alert('All files removed and posts updated successfully!');
								} else {
									alert('There was an error: ' + response.message);
								}
								// Enable the button again
								$('#srmp3-bulkRemove-bt').prop('disabled', false);
							},
							error: function(xhr, status, error) {
								alert('An error occurred: ' + error);
								// Enable the button again
								$('#srmp3-bulkRemove-bt').prop('disabled', false);
							}
						});
					}else{
						$('#srmp3-bulkRemove-bt').prop('disabled', false);
					}
				},
				error: function(xhr, status, error) {
					alert('An error occurred while counting the files: ' + error);
				}
			});
		});

		function indexAudioPreview_AJAX(offset, originalText, postID, index, clickedButton, posts_in) {
			if (!continueIndexing) {
				return;
			}

			$.ajax({
				url: sonaar_admin_ajax.ajax.ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'index_audio_preview',
					nonce: sonaar_admin_ajax.ajax.ajax_nonce,
					offset: offset,
					post_id: postID,
					posts_in: posts_in,
					index: index
				},
				success: function(response) {
					countFiles_AJAX();
					//console.log(response);
					indexPos = parseInt(index) + 1;
					trackLength = $('.srmp3-audiopreview-bt').length;

					if (response.totalPosts && response.processedPosts) {
						clickedButton.siblings('#progressText').text(response.processedPosts + " / " + response.totalPosts + " posts");
					}

					if (response.progress) {
						clickedButton.siblings('#indexationProgress').val(Math.round(Number(response.progress)));
					}
		
					if (response.message) {
						if(indexPos != trackLength && !completed){
							$('.srmp3-post-all-audiopreview-bt').siblings('#srmp3_indexTracks_status').text(indexPos + ' / ' + trackLength + ' ' + response.message );
							clickedButton.siblings('#srmp3_indexTracks_status').text(response.message);
						}
					}
					if (response.completed) {
						var clickableLink = '';

						file_path = response.file_output;

						$('#alb_tracklist_'+index+'_audio_preview').val(response.file_output);

						if(!response.error){
							//console.log(indexPos,trackLength );
							if(indexPos == trackLength){
								completed = true;
								$('.srmp3-post-all-audiopreview-bt').siblings('#srmp3_indexTracks_status').html('Showtime! 🎉 ( ' + indexPos + ' / ' + indexPos + ' ) <em>Don\'t forget to <strong>save</strong> this post.</em>');
							}

							if (response.file_output != null && response.file_output != ''){
								clickableLink = '<a href="' + file_path + '" target="_blank">Listen Preview</a>';
								clickedButton.siblings('#progressText').text('');
								clickedButton.siblings('#srmp3_indexTracks_status').html('Success! 🎉 (' + clickableLink + ') <em>Don\'t forget to <strong>save</strong> this post.</em>').css('margin-right', '10px');
							}else{
								clickedButton.siblings('#srmp3_indexTracks_status').html('Showtime! 🎉').css('margin-right', '10px');
							}
						}else{
							// There is an error!
							clickedButton.siblings('#srmp3_indexTracks_status').html('<span style=color:red;>' + response.message + '</span>').css('margin-right', '10px');
							
						}
						
						clickedButton.siblings('#stopIndexingButton').remove();

						clickedButton.siblings('#indexationProgress').css('display', 'none');
						$('.srmp3-post-all-audiopreview-bt')
							.removeClass('showSpinner spinningIcon')
							.addClass('showCheckmark')
							.css('opacity', '1')
							.css('pointer-events', 'initial');

						clickedButton
							.text(originalText)
							.removeClass('showSpinner spinningIcon')
							.addClass('showCheckmark')
							.css('opacity', '1')
							.css('pointer-events', 'initial');
					} else {
						indexAudioPreview_AJAX(offset + parseInt(sonaar_music_pro.option.preview_batch_size), originalText, postID, index, clickedButton, posts_in);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					clickedButton.siblings('#stopIndexingButton').remove();
					clickedButton.siblings('#srmp3_index_audio_preview').text(originalText);
					
					if (jqXHR.status == 500) {
						// Server-side error
						clickedButton.siblings('#srmp3_indexTracks_status').text('An error occurred. It might be due to the server max execution time limit reached.');
					} else {
						// Some other error
						clickedButton.siblings('#srmp3_indexTracks_status').text('An error occurred. ' + jqXHR.responseText);
					}
				
					clickedButton
						.text(originalText)
						.removeClass('showSpinner spinningIcon')
						.addClass('showCheckmark')
						.css('opacity', '1')
						.css('pointer-events', 'initial');
				}
			});
		}

	}


	function init_toggleTracklistBox(){

		const button = document.createElement('div');

		button.textContent = 'Expand/Collapse All';

		button.classList.add('button', 'button-secondary' , 'srmp3-expand-collapse');

		const targetDiv = document.querySelector('div[data-groupid="alb_tracklist"] .cmb-row');
		if (targetDiv) {
			targetDiv.appendChild(button, targetDiv.firstChild);
		}

		button.addEventListener('click', toggleClosedClass);
		function toggleClosedClass() {
			const divs = document.querySelectorAll('div.postbox .cmb-row .cmb-repeatable-grouping');
			divs.forEach(div => {
				if (sonaar_music.option.collapse_tracklist_backend === 'true') {
					div.classList.remove('closed');
				} else {
					div.classList.add('closed');
				}
			});
			sonaar_music.option.collapse_tracklist_backend = (sonaar_music.option.collapse_tracklist_backend === 'true') ? 'false' : 'true';
		}
	}

	function hideShowTracklistStorelist() {
		// hide or show the tracklist and store list fields if the player type is set to "csv or rss" in the admin area
		var selectElement = document.getElementById("post_playlist_source");
		if (selectElement === null) return;
		var albTracklist = document.querySelector(".cmb2-id-alb-tracklist");
		var albStoreList = document.querySelector(".cmb2-id-alb-store-list.cmb-repeat");

		if (selectElement.value === "csv" || selectElement.value === "rss") {
		albTracklist.style.display = "none";
		albStoreList.style.display = "none";
		}

		selectElement.addEventListener("change", function() {
		if (selectElement.value === "csv"  || selectElement.value === "rss") {
			albTracklist.style.display = "none";
			albStoreList.style.display = "none";
		} else {
			albTracklist.style.display = "";
			albStoreList.style.display = "";
		}
		});
	}
	// When a new group row is added, clear selection and initialise Select2

	var observer;
	function init_TrackTitleOnRepeater(){
		// Set a timeout variable to be used for debouncing
		var timeoutId;
		
		// --------------------------------------------
		// Update Titles for External Audio Files for our admin custom fields
		// --------------------------------------------
		var inputFields = document.querySelectorAll('.srmp3-cmb2-file input');
		inputFields.forEach(function(inputField) {
			inputField.addEventListener('input', function() {
				clearTimeout(timeoutId);
				var $myElement = inputField.closest('.cmb-repeatable-grouping');
				var myElementArray = $myElement ? [$myElement] : [];
				// Set a new timeout to call the function after 1500 milliseconds since we type in the field.
				timeoutId = setTimeout(addTrackTitletoTrackRepeater(myElementArray), 1000);
			});
		});
		
		// --------------------------------------------
		// Update Titles for Local MP3 for our admin custom fields
		// --------------------------------------------

		// If there is a previous observer, disconnect it
		if (observer) {
			observer.disconnect();
		}

		function onMutation(mutationsList, observer) {
			// Check if there are any childList mutations
			var hasChildListMutation = mutationsList.some(mutation => mutation.type === 'childList');

			if (hasChildListMutation) {

				// Clear any existing timeouts
				clearTimeout(timeoutId);
				var $myElement = mutationsList[0].target.closest('.cmb-repeatable-grouping');


				// Clear the value of the peak field when we change track file
				var peakField = $myElement.querySelector('[name^="alb_tracklist"][name$="[track_peaks]"]'); 
				if (peakField) {
					peakField.value = '';
				}

				var myElementArray = $myElement ? [$myElement] : [];
				// Set a new timeout to call the function after a seconds
				timeoutId = setTimeout(addTrackTitletoTrackRepeater(myElementArray), 1000);
			}
		}

		// Create a new observer instance
		observer = new MutationObserver(onMutation);

		var fileStatusElements = document.querySelectorAll('.srmp3-cmb2-file');
		fileStatusElements.forEach(function(element) {
			observer.observe(element, { childList: true, subtree: true });
		});

	}

	function addTrackTitletoTrackRepeater(el = null) {
		// Get all the elements containing both the track title and filename
		if(el){
			var trackElements = el;
		}else{
			var trackElements = document.querySelectorAll('#alb_tracklist_repeat .cmb-repeatable-grouping');

		}
	
		// Loop through each track element
		trackElements.forEach(function(trackElement) {
			// Find the track title span within this track element
			var trackTitle = trackElement.querySelector('.cmb-group-title.cmbhandle-title');
		
			var selectElement = trackElement.querySelector('select[name$="[FileOrStream]"]');
			var selectedOptionValue = selectElement.value;
			var $track;
			switch (selectedOptionValue) {
				case 'mp3':
					let mp3Element = trackElement.querySelector('.srmp3-cmb2-file .cmb2-media-status strong');
					$track = mp3Element ? mp3Element.innerText : '';
					break;
			
				case 'stream':
					let streamElement = trackElement.querySelector('.srmp3-cmb2-file input[name$="[stream_title]"]');
					$track = streamElement ? streamElement.value : '';
					break;
			
				case 'icecast':
					let icecastElement = trackElement.querySelector('.srmp3-cmb2-file input[name$="[icecast_link]"]');
					$track = icecastElement ? icecastElement.value : '';
					break;
			
				default:
					$track = '';
			}
	
			if (trackTitle && $track) {
			// Extract the track number
			var trackNumber = trackTitle.innerText.split(' : ')[0];

			// Create a new filename span element
			var fileNameSpan = document.createElement('span');
			fileNameSpan.className = 'srp-cmb2-filename';
			fileNameSpan.innerText = $track;

			// Remove any existing filename span element
			var existingFileNameSpan = trackTitle.querySelector('.srp-cmb2-filename');
			if (existingFileNameSpan) {
				existingFileNameSpan.remove();
			}

			// Set the track title text content and append the filename span element
			trackTitle.innerText = trackNumber + ' : ';
			trackTitle.appendChild(fileNameSpan);
			}
		});
	}

	function srmp3_option_page_accordeons_tabs(){
		if($('.cmb2-options-page').length && !$('body.sr_playlist_page_srmp3_settings_tools').length){
			$('.cmb2-options-page').addClass('srmp3-option-pages-tabbed');
			// Function to get a URL parameter
			function getUrlParameter(url, name) {
				name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
				var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
				var results = regex.exec(url);
				return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
			}
		
			// Function to set a URL parameter
			function setUrlParameter(url, key, value) {
				var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
				var separator = url.indexOf('?') !== -1 ? "&" : "?";
				if (url.match(re)) {
					return url.replace(re, '$1' + key + "=" + value + '$2');
				} else {
					return url + separator + key + "=" + value;
				}
			}
		
			// Hide all sections
			function hideAllSections() {
				$('.cmb-row:not(.cmb-type-title)').hide();
			}
		
			// Toggle section based on title
			function toggleSection($title) {
				$title.nextUntil('.cmb-row.cmb-type-title').toggle();
			}
		
			// Check if there's a 'tab' parameter in the URL
			let openedTabId = getUrlParameter(window.location.href, 'tab');
		
			hideAllSections();
		
			if (openedTabId) {
				// Show the section corresponding to the tab parameter
				toggleSection($('#' + openedTabId).closest('.cmb-row.cmb-type-title'));
			} else {
				// Show the first section by default
				toggleSection($('.cmb-row.cmb-type-title').first());
			}
		
			$('.cmb-row.cmb-type-title').on('click', function() {
				let $this = $(this);
		
				// Update the URL with the tab ID
				let tabId = $this.find('.cmb2-metabox-title').attr('id');
				let newUrl = setUrlParameter(window.location.href, 'tab', tabId);
				history.pushState(null, '', newUrl);
		
				toggleSection($this);
			});
		}
	}

	//Load Music player Content
	function setIronAudioplayers(){
		if (typeof IRON === 'undefined') return;

		setTimeout(function(){ 
			IRON.players = []
			$('.iron-audioplayer').each(function(){

				var player = Object.create(  IRON.audioPlayer )
				player.init($(this))

				IRON.players.push(player)
			})
		}, 4000);
	
	}

	setIronAudioplayers();
})(jQuery);