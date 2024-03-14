// IIFE - Immediately Invoked Function Expression
(function($, window, document) {


    // Listen for the jQuery ready event on the document
    $(function() {

        // The DOM is ready!

        // colorpicker
        $('[name="relpoststh_background"], [name="relpoststh_hoverbackground"], [name="relpoststh_bordercolor"], [name="relpoststh_fontcolor"]').wpColorPicker();

        // datepicker
        $('.rpt_post_include').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        // Clear datepicker value
        $('.rpt_clear_date').on('click', function(event) {
            event.preventDefault();
            $('.rpt_post_include').datepicker('setDate', null);
        });


        $('[name="relpoststh_number"],[name="relpoststh_customwidth"],[name="relpoststh_customheight"],[name="relpoststh_fontsize"],[name="relpoststh_textlength"],[name="relpoststh_excerptlength"],[name="relpoststh_textblockheight"]').keypress(function(e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which !== 8 && e.which !== 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                $(this).nextAll('.rpt-no-validate-error').show().delay(2000).fadeOut("400");
                return false;
            }
        });



        // Settings Tabs
        $(".select_all").click(function() {
            if (this.checked) {
                $(this).closest('.specific_options').find("div.other_options").hide();
            } else {
                $(this).closest('.specific_options').find("div.other_options").show();
            }
        });

        // TODO Following is to be done with css.
        if ($('#relpoststh_thsource').val() == 'custom-field') {
            $('#relpoststh-post-thumbnails').hide();
            $('#relpoststh-custom-field').show();
        } else if ($('#relpoststh_thsource').val() == 'post-thumbnails') {
            $('#relpoststh-post-thumbnails').show();
            $('#relpoststh-custom-field').hide();
        }

        $('#relpoststh_thsource').change(function() {
            if (this.value == 'post-thumbnails') {
                $('#relpoststh-post-thumbnails').show();
                $('#relpoststh-custom-field').hide();
            } else {
                $('#relpoststh-post-thumbnails').hide();
                $('#relpoststh-custom-field').show();
            }
        });
        $('#relpoststh_show_date').change(function() {
            if ( $('#relpoststh_show_date').is(':checked') ) {
                $('.relpoststh_date_format').fadeIn();
            } else {
                $('.relpoststh_date_format').hide();
            }
        });
		$('#relpoststh_output_style').change(function() {
            if (this.value == 'list') {
                $('#relpoststh_cleanhtml').show();
            } else {
                $('#relpoststh_cleanhtml').hide();
            }
        });
        $("input[name='relpoststh_relation']").change(function() {
            if ($("input[name='relpoststh_relation']:checked").val() == 'custom') {
                $('#custom_taxonomies').show();
            } else {
                $('#custom_taxonomies').hide();
            }
        });

        // Ajax for subsriber
        $('#rpt_subscribe_btn').on('click', function(event) {
            event.preventDefault();

            var subscriber_mail = $('#rpt_subscribe_mail').val();
            var name = $('#rpt_subscribe_name').val();
            if (!subscriber_mail) {
                $('.rpt_subscribe_warning').html('Please Enter Email');
                return;
            }

            $.ajax({
				url: 'https://wpbrigade.com/wp-json/wpbrigade/v1/subsribe-to-mailchimp',
				type: 'POST',
				data: {
					subscriber_mail: subscriber_mail,
					name: name,
					plugin_name: 'rpt'
				},
				beforeSend: function() {
					$('.rpt_subscribe_loader').show();
					$('#rpt_subscribe_btn').attr('disabled', 'disabled');
				}
			})
			.done(function(res) {
				$('.rpt_return_message').html(res);
				$('.rpt_return_message').show();
				$('.rpt_subscribe_loader').hide();

				setTimeout(() => {
					$('.rpt_return_message').hide();
				}, 5000);
			});
        });

        // Switches option sections
        $('#relpoststh-settings .postbox').hide();
        var activetab = '';
        if (typeof(localStorage) != 'undefined') {
            activetab = localStorage.getItem("rpt-activetab");
        }

        //if url has section id as hash then set it as active or override the current local storage value
        if (window.location.hash) {
            activetab = window.location.hash;
            if (typeof(localStorage) != 'undefined') {
                localStorage.setItem("rpt-activetab", activetab);
            }
        }

        if (activetab != '' && $(activetab).length) {
            $(activetab).fadeIn();
        } else {
            $('#relpoststh-settings .postbox:first').fadeIn();
        }
        $('#relpoststh-settings .postbox .collapsed').each(function() {
            $(this).find('input:checked').parent().parent().parent().nextAll().each(
                function() {
                    if ($(this).hasClass('last')) {
                        $(this).removeClass('hidden');
                        return false;
                    }
                    $(this).filter('.hidden').removeClass('hidden');
                });
        });

        if (activetab != '' && $(activetab + '-tab').length) {
            $(activetab + '-tab').addClass('nav-tab-active');
        } else {
            $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
        }
        $('.nav-tab-wrapper a').click(function(evt) {
			evt.preventDefault();
			if(!$(this).hasClass('nav-tab-active')){
				
				$('.nav-tab-wrapper a').removeClass('nav-tab-active');
				$(this).addClass('nav-tab-active').blur();
				var clicked_group = $(this).attr('href');
				if (typeof(localStorage) != 'undefined') {
					localStorage.setItem("rpt-activetab", $(this).attr('href'));
				}
				$('#relpoststh-settings .postbox').hide();
				$(clicked_group).fadeIn();
			}
        });
        /// Gallery image selection script
        $('.relpoststh_set_def_image').on('click', function(event) {
            event.preventDefault();
            var self = $(this);
            var file_frame = wp.media.frames.file_frame = wp.media({
                    title: self.data('uploader_title'),
                    button: {
                        text: self.data('uploader_button_text'),
                    },
					library: {
						type: 'image', // Set the media type to 'image' to filter for images only.
					},
                    multiple: false
                })
                .on('select', function() {
                    attachment = file_frame.state().get('selection').first().toJSON();
                    // Check if the selected file is an image
					if (attachment.mime && attachment.mime.indexOf('image') !== -1) {
						$('#relpoststh_default_image_prev').attr('src', attachment.url);
						$('#relpoststh_default_image').val(attachment.url);
					} else {
						// Display an error message or handle the situation as needed
						alert('Please select an image file.');
						// Do not close the media modal on error
						file_frame.open();
					}
                })
                .open();
        });

        $(document).on('click', '.relpoststh_set_plug_image', function(e) {
            e.preventDefault();
            var defValue = $(this).val();
            $('#relpoststh_default_image_prev').attr('src', defValue);
            $('#relpoststh_default_image').val(defValue);
        });

        // Code for toggle class on navbar on click @since 3.0
        $('.toplevel_page_related-posts-thumbnails .tabs-toggle').on('click' , function() {
            $(this).parent('.rpt-navigation-wrapper').toggleClass('active').find('.nav-tab-wrapper').slideToggle();
        });
        $(".settings-tabs-list").on("click", function(){
			if(window.matchMedia('(max-width: 767px)').matches === true){
				$(this).closest(".nav-tab-wrapper").slideUp();
				$(".rpt-navigation-wrapper").removeClass("active");
			}
		})
        
        // Code for Chosen librarey. @since 3.0
        $(document).ready(function() {
			//Disable other options if 'all' option selected on page load
            $('.relpoststh_show_categoriesall, .relpoststh_categoriesall').each(function(){
				if($(this).val() == '0'){
					$(this).find('option:not(:first-child)').prop('disabled', true).trigger("liszt:updated");
					$(this).find('option:not(:first-child)').prop('disabled', true).trigger("chosen:updated");
				}
			});
			//Run chosen
			$('.chosen-select').chosen();
			
			//Disable other options if 'all' option selected on change
			$('.relpoststh_show_categoriesall, .relpoststh_categoriesall').on('change', function(evt, params) {
				
				if (  params.deselected == 0 ) {
					$(this).find('option:not(:first-child)').prop('disabled', false).trigger("liszt:updated");
					$(this).find('option:not(:first-child)').prop('disabled', false).trigger("chosen:updated");
				}
				if (  params.selected == 0 ) {
					$(this).find( 'option:not(:first-child)' ).attr( 'selected' );
					$('select')[0].checked = true;
					$('#'+ $(this).attr('id') +'_check')[0].checked = true;
					$(this).find('option:not(:first-child)').prop('disabled', true).trigger("liszt:updated");
					$(this).find('option:not(:first-child)').prop('disabled', true).trigger("chosen:updated");
				} else{
					$('#'+ $(this).attr('id') +'_check')[0].checked = false;
				}
			  });


			// Update Chosen2 to reflect the All
			$(".chosen-select").trigger("chosen:updated");
        });

		// Change the default placeholder text
		
        // Iterate over each select element
        $('select:not(.chosen-select)').each(function () {
            // Cache the number of options
            var $this = $(this),
            numberOfOptions = $(this).children('option').length;

            // Hides the select element
            $this.addClass('s-hidden');

            // Wrap the select element in a div
            $this.wrap('<div class="select"></div>');

            // Insert a styled div to sit over the top of the hidden select element
            $this.after('<div class="styledSelect"></div>');

            // Cache the styled div
            var $styledSelect = $this.next('div.styledSelect');

            // Show the first select option in the styled div

            $styledSelect.text($this.children('option[selected]').text());
            // $styledSelect.text($this.children('option').eq(0).text());        

            // Insert an unordered list after the styled div and also cache the list
            var $list = $('<ul />', {
                'class': 'options'
            }).insertAfter($styledSelect);

            // Insert a list item into the unordered list for each select option
            for (var i = 0; i < numberOfOptions; i++) {
                $('<li />', {
                    text: $this.children('option').eq(i).text(),
                    rel: $this.children('option').eq(i).val()
                }).appendTo($list);
            }

            // Cache the list items
            var $listItems = $list.children('li');

            // Show the unordered list when the styled div is clicked (also hides it if the div is clicked again)
            $styledSelect.click(function (e) {
                e.stopPropagation();
                $('div.styledSelect.active').each(function () {
                    $(this).removeClass('active').next('ul.options').hide();
                });
                $(this).toggleClass('active').next('ul.options').toggle();
            });

            // Hides the unordered list when a list item is clicked and updates the styled div to show the selected list item
            // Updates the select element to have the value of the equivalent option
            $listItems.click(function (e) {
                e.stopPropagation();
                $styledSelect.text($(this).text()).removeClass('active');
                $this.val($(this).attr('rel'));
                $list.hide();
                $this.trigger('change');
                /* alert($this.val()); Uncomment this for demonstration! */
            });

            // Hides the unordered list when clicking outside of it
            $(document).click(function () {
                $styledSelect.removeClass('active');
                $list.hide();
            });
        });

    }); // End of document ready

    // The rest of the code goes here!

}(window.jQuery, window, document));