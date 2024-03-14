jQuery(document).ready(function(jq) {
	$ = jq;

	var maxSocial = function () {
		var timer;
		var form_updated = false;
		var style_changed = false;
		var preview_in_view = false;
	}

	maxSocial.prototype.init = function ()
	{
		this.initSortables();

		colorPalette = (typeof mbpro_options !== 'undefined' && mbpro_options.colorPalette !== '' ? mbpro_options.colorPalette : true);

		$('.maxbuttons-social .mb-color-field').wpColorPicker(
		{
				width: 300,
				palettes: colorPalette,
				changeFunc: $.proxy( _.throttle(function(event, ui) {
						event.preventDefault();
						var target = $(event.target);
						var color = ui.color.toString();

						//if (color.indexOf('#') === -1)
						//color = '#' + color;

						var id = target.attr('id');
						$('#' + id).val(color).trigger('change');

				}, 200), this),
			}
		);

		$('.toggle .title').on('click', this.toggle);

		$(document).on('maxajax_success_save_collection', $.proxy(this.savedCollection, this) );
		$(document).on('maxajax_success_remove-collection', $.proxy(this.deletedCollection, this));

		// Function to check for need to refresh the preview block
		this.hookBlockRefresh();

		// prevent buttons becoming clickable in preview
		$(document).on('click', '#previewBlock a', function (e) { e.preventDefault(); return true; } );
		$(document).on('click', '.style_modal a', function (e) { e.preventDefault(); $(e.target).parents('label').click(); return false; });

		$(window).on('beforeunload', $.proxy(function () { if (this.form_updated) return maxajax.leave_page; }, this));

		$('#maxbuttons.maxbuttons-social').on('change', 'input,select', $.proxy( function (e, param)
		{

			if (typeof param !== 'undefined' && param == 'conditional')
				return false; // conditional init, not a real change.

			this.form_updated = true;

		 } ,this) );

		 // first this, before unhook maxmodal, otherwise this won't work (workaround)
		 if ($('input[name="collection_id"]').val() == 0)
		 {
			 $('#open_presets').click();
		 }

		 // Update fonts
		 $('#font').on('change', $.proxy( this.checkFonts , this));


	//	 $('input[name="network_item_active[]"]').trigger('change'); // init for conditionals

	}

	maxSocial.prototype.checkPreviewPosition = function(e)
	{
			var element = $('#previewBlock');
			var pageTop = $(window).scrollTop();
			var pageBottom = pageTop + $(window).height();
			var elementTop = $(element).offset().top;
			var elementBottom = elementTop + $(element).height();

			var is_visible = ((elementTop <= pageBottom) && (elementBottom >= pageTop));

			if (! is_visible)
			{
				 var goOn = true;

				 $('.option-container').each(function(index, el)
				 {
					 var top = ( $(this).offset().top ); // add some offset so it won't up barely visible at the top

					 if (top > (pageTop + 60) )
					 {
						 $('#previewBlock').insertBefore(this);
						 goOn = false;
					 }


					 return goOn;

				 });
			}

	}

	maxSocial.prototype.checkFonts  = function()
	{ // these two functions should be merged at some point
		var maxfonts = window.maxFoundry.maxfonts;

		maxfonts.checkFonts();
		$('.mb-label, .mb-share-count').each(function () {

			var ff =  $('#font').val(); //$(this).css('fontFamily');
			var family = maxfonts.getWebFamily(ff);
			if (family !== false)
			{
					$("head").append("<link rel='stylesheet' type='text/css' href='" + family + "' />");
			}

		});

	}

	maxSocial.prototype.toggle = function (e)
	{
		e.preventDefault();
		var target = $(e.target);
		if (! target.hasClass('.toggle') )
			target= target.parents('.toggle');

		var toggle_target = $(target).find('.toggle-target');

		$(toggle_target).toggle('fast', function () {
			if ( $(this).is(':visible') )
				$(target).addClass('toggle_active');
			else
				$(target).removeClass('toggle_active');

		});
	}

	maxSocial.prototype.initSortables = function ()
	{
	var mbsocial = this;

		$('.drag-area').sortable({

			connectWith: '.drag-area',
			update: function (event, ui)
			{
					if ( $(event.target).data('area') == 'active')
					{
						$('#network_trigger_change').trigger('change'); // This trigger the preview /
						//$('input[name="network_item_active[]"]').trigger('change'); // for conditionals
					}
			},
	 		receive: function(event, ui) {

					is_clonable = false;
					if ( $(ui.item).find('input[value*="maxbutton"]').length > 0)
					{	var is_clonable = true; }

					if ( $(event.target).data('area') == 'active')
	 				{

						if (is_clonable)
						{
							var item = $(ui.item).clone().insertAfter(ui.item); //.find('input');
							$(ui.sender).sortable('cancel');
							$(item).append ( $('.mbcustom-helper .config_button').clone() );
						//	var button = $(item).find('.config_button button');
							$(item).find('button').trigger('click'); // open the thing.
							//$(item).on('click','button', $.proxy(mbsocial.openMBCustomModal, mbsocial) ) ;
						}
						else {
							var item = $(ui.item);
						}

	 					$(item).find('input').attr('name', 'network_item_active[]'); // change item to network-active
	 					var name =  $(item).attr('name');

	 					$('input[name="' + name + '"]').trigger('change'); // trigger change on item name

	 				}
	 				else
	 				{
	 					$(ui.item).find('input').attr('name', 'network_item[]');
						if ( is_clonable && $(ui.sender).data('area') == 'active') // remove clonable items if they are leaving active area.
						{
							$(ui.item).remove();
						}

	 				}

					$('input[name="network_item_active[]"]').trigger('change'); // for conditionals

	 		},

			remove: function (event,ui)
			{
				//$(this).sortable('cancel');
			}


		});

	}

	maxSocial.prototype.savedCollection = function(event, result, status, object)
	{
		result = JSON.parse(result);

		if (result.error == true)
		{
			console.error('Something went wrong with the save');
			return false;
		}

		var collection_id = result.data.id;
		$('.mb-ajax-form input[name="collection_id"]').val(collection_id);

		var modal = window.maxFoundry.maxmodal;

		modal.newModal('save_done');
		modal.setTitle(result.title);
		modal.setContent('');
		modal.show();

		this.form_updated = false; // set updated to false
		window.setTimeout( function () {  modal.fadeOut(); }, 1000);

	}

	maxSocial.prototype.deletedCollection = function(event, result, status, object)
	{
		result = JSON.parse(result);

		if (result.error == true)
		{
			console.error('Something went wrong while deleting');
			return false;
		}

		var url = result.redirect;
		window.location = url;

	}

	maxSocial.prototype.displayOptions = function (modal)
	{
		// suboptimal
		var parent = $(modal.target).parents('.display_group');

		var options = $(parent).find('input[type="hidden"]').val();
		var options_field = $(parent).find('input[type="hidden"]');

		var selection = $(parent).find('input:checked').val();

		// checked radio button
		$(modal.currentModal).on('modal_close',{target: options_field }, $.proxy(this.saveOptions, this ) );

		// show the correct screen
		if (selection == 'static')
		{
			$(modal.currentModal).find('.static_options').show();
			$(modal.currentModal).find('.post_options').show();
			$(modal.currentModal).find('.content_options').hide();

		}
		else
		{
			$(modal.currentModal).find('.post_options').show();
			$(modal.currentModal).find('.content_options').show();
			$(modal.currentModal).find('.static_options').hide();
		}

		if (options)
		{
			options = JSON.parse(options);

			$.each(options, function (field_name, value)
			{
				var field = $(modal.currentModal).find('[name="' + field_name + '"]');

				if ($(field).is('input') )
				{
					switch($(field).attr('type'))
					{
							case 'checkbox':
								if ($(field).val() == value)
								{
									$(field).prop('checked', true);
								}
							break;
							case 'radio':
								$(field).children('[value="' + value + '"]').prop('checked',true);

								$(field).each( function () {
										if ( $(this).val() == value)
										{ $(this).prop('checked', true);
									  }
								});
							break;
							default:
								field.val(value);
							break;
					}

				}
				else if ($(field).is('select') )
				{
					$(field).find('option[value=' + value + ']').prop('selected', true);
				}

			});

		}

		// set ID's to random otherwise it will not work ( since fields are cloned from hidden modal data )
		$(modal.currentModal).find('.switch_button, .input.radio').each( function (index, element) {

			var rnd = Math.floor(Math.random() * (1000 - 1)) + 1;
			$(this).find('input').attr('id', 'fix_position' + rnd);
			$(this).children('label').attr('for', 'fix_position' + rnd);

		} );

		modal.checkResize();
	}

	/* Function to save options in modal back to hidden input */
	maxSocial.prototype.saveOptions = function (event, modal)
	{
		var target = event.data.target;
		var fields = $(modal.currentModal).find('input, select');

		var saveArray = {};

		$(fields).each(function () {

			var name = $(this).attr('name');

			switch ($(this).attr('type') )
			{
				case 'checkbox':
					if ( $(this).is(':checked') )
						var value =  1;
					else
						var value = 0;
				break;
				case 'radio':
					 if ($(this).prop('checked'))
					 	var value = $(this).val();
				break;
				default:
					var value = $(this).val();

				break;
			}

			if (typeof value !== 'undefined')
				saveArray[name] = value;
		});


		var str = JSON.stringify(saveArray);

		$(target).val(str);
	}

	// define the events that trigger refreshing a block
	maxSocial.prototype.hookBlockRefresh = function()
	{
		var refreshBlock = $('.option-container[data-refresh]');
		var self = this;

		$(refreshBlock).each(function ( index, block)
		{
			var refreshField = $(block).find('.updatables').text();
			if (refreshField.length === 0)
			{ return; }


			$(block).off('change');

			$(block).on('change', refreshField, {'block' : block, 'field' : refreshField}, $.proxy(function (event)
			{
				var block = event.data.block;
				var form = $(block).parents('form');

				args = { collection_id: $('input[name="collection_id"]').val(),
						 target: $(block).data('refresh'),
						 form: form,
						};
				this.refreshBlock(args);
			}, self) );

		});
	}



	/* Refresh any block on updating fields. Most notable the style block */
	maxSocial.prototype.refreshBlock = function (args)
	{
		clearTimeout(this.timer);

		var maxajax = window.maxFoundry.maxAjax;
		var data  = maxajax.ajaxInit();
		var self = this;

		data['collection_id'] = args['collection_id'];
		data['block_name'] = args['target'];
		data['plugin_action'] = 'refreshblock';
		//data['form'] = args['form'];

		this.timer = setTimeout( function () {

			data['form'] = args['form'].serialize(); // serialize on send, since in 1500ms stuff can happen.
			$('#' + args['target']).find('.title').append('<div class="max-load-spinner"></div>');

			maxajax.ajaxPost(data, $.proxy(self.refreshBlockUpdate,self) );
			}, 1500);

	}

	maxSocial.prototype.refreshBlockUpdate = function (result, status, object) {
		var result = JSON.parse(result);
		var content = result.content;
		var blockname = result.block;
		var self = this;

		$('#' + blockname).fadeTo(900, 0, function () {
			$('#' + blockname).replaceWith( $(content).hide());
			$('#' + blockname).fadeTo(900,1);
			self.hookBlockRefresh(); //  rehook whatever changed.
			self.checkPreviewPosition();

		});

	}

	/* Load popup for presets selection */
	maxSocial.prototype.loadPresetsPopup = function(modal)
	{
		var maxajax = window.maxFoundry.maxAjax;
		var data  = maxajax.ajaxInit();

		// get current selection
		var current_style = $('input[name="style"]').val();
		var collection_id = $('input[name="collection_id"]').val();

		data['collection_id'] = collection_id;
		data['plugin_action'] = 'get_presets';

		if (collection_id > 0)
		{
			$(modal.currentModal).find('.top-note.warning').removeClass('hidden');
			$(modal.currentModal).find('.top-note.welcome').addClass('hidden');
		}
		else {
			$(modal.currentModal).find('.top-note.warning').addClass('hidden');
			$(modal.currentModal).find('.top-note.welcome').removeClass('hidden');
		}
		// do ajax call
		$(modal.currentModal).find('.modal_content').append('<span class="max-load-overlay"><span class="max-load-spinner large"></span></span>')

		maxajax.ajaxPost(data, $.proxy(this.displayPresetSelection, this, [modal] ) );
	}

	maxSocial.prototype.displayPresetSelection = function(modal, result, status, object)
	{
		var result = JSON.parse(result);
		var collection_id = $('input[name="collection_id"]').val();

		var modal = modal[0]; // proxy gives an array

		var style = $(modal.currentModal).find('.style_modal');
		$(modal.currentModal).find('.modal_content .max-load-overlay').fadeOut(900);

		$.each(result, function ( index, obj)
		{
				var button = '<button type="button" class="button-primary" data-preset="' + obj.name + '">Use this Preset</button>';

		    var div = $('<div>', { class: 'preset preset-' + index,
		    			})
		    			.append('<div class="description"><span>' + obj.label + '</span>' + button + '</div>')
		    			.append(obj.collection);
		    $(style).append(div);


		} );

		// append click after append.
		$(modal.currentModal).find('.preset button').off('click');
		$(modal.currentModal).find('.preset button').on('click', {modal : modal},  $.proxy(this.putPreset, this ) );

		modal.checkResize();
		modalHeight = modal.currentModal.height();
		topHeight = modal.currentModal.find('.modal_header').height() + 17; // padding
		//controlsHeight = modal.currentModal.find('.controls').height() + 21;
		modal.currentModal.find('.modal_content').css('height', modalHeight - topHeight);

		modal.focus();
	}

	maxSocial.prototype.putPreset = function (e)
	{
		 var maxajax = window.maxFoundry.maxAjax;
		 var modal = e.data.modal;
		 var target = $(e.target);
		 var preset = $(target).data('preset');

		 var collection_id = $('input[name="collection_id"]').val();

		 var data  = maxajax.ajaxInit();
		 data['plugin_action'] = 'set_preset';
		 data['collection_id'] = collection_id;
		 data['preset'] = preset;

		 maxajax.ajaxPost(data, $.proxy( function (modal, result, status)
		 {
				var modal = modal[0];
				var result = JSON.parse(result);
				var url = result.redirect;

			 	modal.close();
				this.form_updated = false;

				window.location = url;
		 }, this, [modal] ) );

	}


	if (typeof window.maxFoundry === 'undefined')
		window.maxFoundry = {};

	window.maxFoundry.maxSocial = new maxSocial();
	window.maxFoundry.maxSocial.init();
});
