var $j = jQuery.noConflict();
$j(function()
{
	function checkOption(optionId, relativeOptionIds) {
		if ($j('#' + optionId).prop('checked')) {
			for (var i = 0; i < relativeOptionIds.length; i++) {
				$j('#' + relativeOptionIds[i]).closest('tr, .form-field').show(200);
			}
		}
		else {
			for (var i = 0; i < relativeOptionIds.length; i++) {
				$j('#' + relativeOptionIds[i]).closest('tr, .form-field').hide(200);
			}
		}
	}
	checkOption('setting_show_in_lightbox', ['settings_lightbox_box']);

	$j('#setting_show_in_lightbox').click(function(){
		checkOption('setting_show_in_lightbox', ['settings_lightbox_box']);
	});
	
	checkOption('setting_lightbox_close_button', ['setting_lightbox_close_button_text']);

	$j('#setting_lightbox_close_button').click(function(){
		checkOption('setting_lightbox_close_button', ['setting_lightbox_close_button_text']);
	});

	checkOption('setting_show_expand_button', ['setting_expanded_website']);

	$j('#setting_show_expand_button').click(function(){
		checkOption('setting_show_expand_button', ['setting_expanded_website']);
	});

	// Hide advanced options by default
	$j("#advanced-options").hide();
	
	// The link that hides/shows the advanced section
	var hideShowLink = $j("#wpp-hide-show-advanced a");
	
	hideShowLink.click(function()
	{
		// Show the advanced section
		if (hideShowLink.text() == 'Show Advanced Settings') 
		{
			hideShowLink.text('Hide Advanced Settings');
			$j("#wpp-hide-show-advanced").removeClass('wpp_hide');
			$j("#advanced-options").show();
		} 
		
		// Hide the section again
		else 
		{
			$j("#advanced-options").hide();
			$j("#wpp-hide-show-advanced").addClass('wpp_hide');
			hideShowLink.text('Show Advanced Settings');
		}
		return false;
	});
	
	
	// Add an event to handle refreshing a thumbnail
	$j(".wpp-refresh").click(function()
	{
		// Reference to the image table cell holder
		var thumbHolder = $j(this).parent().parent().children('.wpp-thumbnail');

		var siteid = $j(this).parent().parent().children('.wpp-id').html();

		var data = {
			action: 'thumbnail_refresh',
			siteid: siteid
		};

		// Change to the loader
		var loaderSRC = $j('#wpp-loader').html();
		thumbHolder.html('<span style="width: 120px; height: 66px; text-align: center; margin-top: 30px; display: block;"><img src="' + loaderSRC + '" width="32" height="32" style="border: 0;"/></span>');

		// Request a thumbnail update
		jQuery.post(ajaxurl, data, function(response) {
			thumbHolder.html('<img src="' + response + '" />');
			//thumbHolder.html(response); // Use this for debugging response.
		});

		return false;
	});
	
	
	/**
	 * JS for pro accounts only
	 */ 
	/**
	 * Update the custom size box to show actual size.
	 */
	function changeCustomSizeInfo()
	{
		if (!$j(".wpp-custom-size")) {
			return;
		}
		
		var customx = $j(".wpp-custom-size").val();
		if ((customx - 0) == customx && customx.length > 0)
		{	
			customy = Math.ceil((customx / 4) * 3);
			$j(".wpp-size-custom-other").text(customx + 'px (width) by ' + customy + 'px (height)'); 
		}
	}
	
	// Handle the custom size
	$j(".wpp-size-custom").change(function() {
		changeCustomSizeInfo();
	});
	$j(".wpp-size-custom").keyup(function() {
		changeCustomSizeInfo();
	});
	$j(".wpp-size-custom").focus(function() {
		changeCustomSizeInfo();
	});
	changeCustomSizeInfo();
	
	
	/**
	 * Handle the thumbnail size types
	 */
	function showCorrectSizeMethod()
	{
		if ($j(".wpp-size-type").val() == 'custom') 
		{ 
			$j(".wpp-size-custom").parent().parent().show();
			$j(".wpp-resolution-custom").parent().parent().show();
			$j(".wpp-full-length").parent().parent().show();
			$j(".wpp-size-select").parent().parent().hide();
		} 
		else 
		{
			$j(".wpp-size-custom").parent().parent().hide();
			$j(".wpp-resolution-custom").parent().parent().hide();
			$j(".wpp-full-length").parent().parent().hide();
			$j(".wpp-size-select").parent().parent().show();
		}
	}
	
	$j(".wpp-size-type").change(function() {
		showCorrectSizeMethod();
	});
	showCorrectSizeMethod();
	
	
	/**
	 * Handle hiding cache date options when embedding the thumbnail.
	 */
	function hideCacheDaysIfEmbedding()
	{
		if ($j(".wpp-rendering-type-select").val() == 'embedded') 
		{ 
			$j(".wpp-cache-days-select").closest('tr').hide();
			$j(".wpp-fetch-method-select").closest('tr').hide();
		}
		else
		{
			$j(".wpp-cache-days-select").closest('tr').show();
			$j(".wpp-fetch-method-select").closest('tr').show();
		}
	}

	$j(".wpp-rendering-type-select").change(function() {
		hideCacheDaysIfEmbedding();
	});
	hideCacheDaysIfEmbedding();

	// Enable and disable form components.
	$j(".wpp-inactive").attr('disabled', 'disabled');
	$j(".wpp-active").removeAttr('disabled')

	// Custom fields adder.
	$j(function() {
		var scntDiv = $j('#custom_fields');
		var i = $j('#custom_fields p').size() + 1;

		$j('#addField').live('click', function() {
			$j('<p>' +
					'<input type="text" id="custom_field_name" name="custom_field_name_' + i +'" value="" placeholder="Enter field name">' +
					'<input type="text" id="custom_field_value" name="custom_field_value_' + i +'" value="" placeholder="Enter field value" style="margin-left: 4px;">' +
				'<label id="custom_field_is_hidden" style="margin-left: 4px;"><input type="checkbox" id="custom_field_is_hidden" name="custom_field_is_hidden_' + i +'">Hidden</label>' +
				'<a href="#" id="removeField" class="button-primary" style="margin-left: 24px;">X</a></p>').appendTo(scntDiv);
			i++;
			return false;
		});

		$j('#removeField').live('click', function() {
			if( i > 2 ) {
				$j(this).parents('p').remove();
				i--;
			}
			return false;
		});
	});
});
