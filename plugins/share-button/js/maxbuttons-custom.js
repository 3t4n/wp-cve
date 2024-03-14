
jQuery(document).ready(function(jq) {
	$ = jq;

 var MBCustom = function() {
	 $(document).off('click', '#networkBlock .item .config_button button');
	 $(document).on('click', '#networkBlock .item .config_button button', $.proxy(this.openMBCustomModal, this) );
 }

 MBCustom.prototype = {
	 share_mm: new window.maxFoundry.maxMedia,
	 custom_target: '',
	 clone: null,
	 option_index: 0,
 };

	/** Function to open MB Custom selection modal, for custom buttons **/
	MBCustom.prototype.openMBCustomModal = function (e)
	{
			this.custom_target = $(e.target).parents('.item');
			var self = this;

			this.createSettings();
			this.share_mm.init({callback: $.proxy(this.selectMBCustomButton,this), useShortCodeOptions: true, parent: '#maxbuttons'});

			// init off the events.
			$(document).on('media_button_content_buttons_load', $.proxy(this.checkButtonConfig, this) );
			$(document).on('media_button_content_shortcode_options', $.proxy(this.shortcodeOptions, this) );

			this.share_mm.openModal();
	}

	MBCustom.prototype.checkButtonConfig = function (result)
	{
 		var button_id = $(this.clone).find('input[name="mbcustom_id[' + this.option_index +']"]').val();
		 if (! isNaN(button_id) && button_id > 0)
		 {
			 // Button present: fake event, order next page.
			 var event = {}; // { target: $('<div data-button="' + button_id + '">') }
			 event.target = $('<div data-button="' + button_id + '">');
			 event.preventDefault = function () {};

			 this.share_mm.shortCodeOptions(event);
		 }

	}

	// clone and index custom settings | more to other index due to unique ID issues.
	MBCustom.prototype.createSettings = function()
	{
		this.option_index = $(this.custom_target).find('input[name="mbcustom_index[]"]').val();
		var options = $(this.custom_target).find('.mbcustom-options');

		if (options.length > 0)
		{
			this.clone = $(options).clone();

			// redo some problem fields
			var option = $(options).find('select[name="mbcustom_network[' + this.option_index + ']"]').val();
			$(this.clone).find('select[name="mbcustom_network[' + this.option_index + ']"]').val(option);

		}
		else
		{ // if no options present, add options to a new index to prevent overwrite
				var nextdex = 0;
				$('input[name="mbcustom_index[]"]').each(function()
				{
					if ($(this).val() > nextdex)
						nextdex = $(this).val();
				}); // find next index
				nextdex++;

				this.clone = $('.mbcustom-helper .mbcustom-options').clone();

			 // put next index at clone
			 $(this.clone).find('input, select, label').each(function() {

					if (typeof $(this).attr('name') !== 'undefined')
						$(this).attr('name', $(this).attr('name').replace(/-1/i, nextdex));

					if (typeof $(this).attr('id') !== 'undefined')
						$(this).attr('id', $(this).attr('id').replace(/-1/i, nextdex));

					if (typeof $(this).attr('for') !== 'undefined')
						$(this).attr('for', $(this).attr('for').replace(/-1/i, nextdex));
			 });

			 $(this.clone).find('input[name="mbcustom_index[]"]').val(nextdex); // put index on the counter
			 this.option_index = nextdex;

		}

		var the_switch = $(this.clone).find('.switch_button');
		$(the_switch).find('label').attr('for', 'mbcustom_usenetwork_active');
		$(the_switch).find('input').attr('id', 'mbcustom_usenetwork_active');


	}

	MBCustom.prototype.shortcodeOptions = function(event, result)
	{

		var button_id = result.button_id;
		$(this.clone).find('input[name="mbcustom_id[' + this.option_index +']"]').val(button_id);

		var index = $(this.custom_target).find('input[name="mbcustom_index[]"]').val();
		var modal = this.share_mm.maxmodal;

		// get setting from options
		var url = $(this.clone).find('input[name="mbcustom_url[' + this.option_index + ']"]').val();
		var text = $(this.clone).find('input[name="mbcustom_text[' + this.option_index + ']"]').val();

		// put settings in interface
		$(modal.currentModal).find('input[name="shortcode_url"]').val(url);
		$(modal.currentModal).find('input[name="shortcode_text"]').val(text);

		// hide not supported stuff
		$(modal.currentModal).find('.shortcode_text2,.option.more').hide();

	}

	// Select shortcode window, add MB Custom specific fields
	MBCustom.prototype.selectMBCustomButton = function (button_id, event)
	{
		options = (typeof options !== 'undefined') ?  options : false;
		var modal = this.share_mm.maxmodal; // find modal

		// get settings from modal
		var url = $(modal.currentModal).find('input[name="shortcode_url"]').val();
		var text = $(modal.currentModal).find('input[name="shortcode_text"]').val();

		// find these settings and write them to the options.
		$(this.clone).find('input[name="mbcustom_url[' + this.option_index +']"]').val(url);
		$(this.clone).find('input[name="mbcustom_text[' + this.option_index +']"]').val(text);

		modal.resetControls();
		modal.addControl(mbtrans.insert, '', $.proxy(this.putMBCustomOptions, this) );

		this.clone.removeClass('hidden');
		modal.setContent(this.clone); // insert the fields
		modal.setControls();

		modal.checkResize();

	}

	// Write back custom options to the main editor
	MBCustom.prototype.putMBCustomOptions = function(event)
	{
			var modal = this.share_mm.maxmodal;

			// find the options in the modal
			var options = $(modal.currentModal).find('.mbcustom-options');
			var the_switch = $(options).find('.switch_button'); // move back ID
			$(the_switch).find('label').attr('for', 'mbcustom_usenetwork_' + this.option_index);
			$(the_switch).find('input').attr('id', 'mbcustom_usenetwork_' + this.option_index);

			if (options.length > 0)
			{
				$(options).addClass('hidden');
				if ($(this.custom_target).find('.mbcustom-options').length > 0)
					$(this.custom_target).find('.mbcustom-options').replaceWith(options);
				else {
					$(this.custom_target).append(options); // new entries
				}
			}
			else {

			}

			$('#network_trigger_change').trigger('change');
			this.share_mm.close();

	}



	if (typeof window.maxFoundry === 'undefined')
		window.maxFoundry = {};

	window.maxFoundry.MBCustom = new MBCustom();


});
