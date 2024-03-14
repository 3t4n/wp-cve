jQuery(document).ready(function($) {
	// Sortable lists
	$('.sortable-list').sortable({
		placeholder: 'list-item-placeholder',
		forcePlaceholderSize: true,
		handle: '.list-item-handle'
	});

	$(document).on('keyup', '.title-input', function(e) {
		var text = $(this).val();
		if (text === '') text = 'undefined';

		$(this).closest('.list-item').find('.menu-item-title').text(text);
	});

	$(document).on('click', '.menu-item-toggle', function(e) {
		e.preventDefault();
		$(this).closest('.list-item').children('.list-item-settings').slideToggle(200);
		$(this).toggleClass('opened');
	});

	// New map type select
	$('#mapplic-new-type').on('change', function() {
		if (this.value != 'custom') $('#mapplic-mapfile').hide();
		else $('#mapplic-mapfile').show();
	});

	// Edit mode
	$('#mapplic-editmode').click(function() {
		$('.mapplic-rawedit').toggle();
		$('#mapplic-admin-map').toggle();
		$(this).val(function(i, text) { return text === mapplic_localization.raw ? mapplic_localization.map : mapplic_localization.raw; });
	});

	// Indentation
	$('#mapplic-indent').change(function() {
		var ischecked = $(this).is(':checked'),
			object = JSON.parse($('#mapplic-mapdata').val());
		if (ischecked) $('#mapplic-mapdata').val(JSON.stringify(object, null, 4));
		else $('#mapplic-mapdata').val(JSON.stringify(object));
	});

	// Import select
	$("#mapplic-new-type").change(function() {
		if ($(this).val() == 'import') $('#mapplic-import').show();
		else $('#mapplic-import').hide();
	});

	// WordPress colorpicker
	$('.mapplic-color-picker').each(function() {
		var text = $(this).attr('data-text'),
			cp = $(this).wpColorPicker();
		if (text) cp.parent().parent().prev().find('.wp-color-result-text').html(text);
	});

	$('.mapplic-alpha-color-picker').each(function() {
		var text = $(this).attr('data-text'),
			cp = $(this).alphaColorPicker();
		if (text) cp.parent().parent().prev().find('.wp-color-result-text').html(text);
	});


	// Media button
	$(document).on('click', '.media-button', function(e) {
		e.preventDefault();

		var button = this;

		var media_popup = wp.media({
			title: 'Select or Upload File',
			button: { text: 'Select' },
			multiple: false
		});

		media_popup.on('select', function() {
			var attachment = media_popup.state().get('selection').first().toJSON();
			$(button).closest('div').find('.input-text').val(attachment.url);
		}).open();
	});

	// Item actions
	$(document).on('click', '.item-cancel', function(e) {
		e.preventDefault();
		$(this).closest('.list-item-settings').slideToggle(200);
	});

	$(document).on('click', '.item-delete', function(e) {
		e.preventDefault();
		if (confirm('Are you sure you want to delete the selected item?')) {
			$(this).closest('.list-item').remove();
		}
	});

	// Styles
	$('#new-style').click(function() {
		$('#style-list .new-item').clone().removeClass('new-item').appendTo('#style-list').find('.list-item-settings').slideDown(200);
	});

	// Pin switcher
	$('#pins-input > li').click(function() {
		$('#pins-input .selected').removeClass('selected');
		$(this).addClass('selected');

		// Show label field only when it's available
		if ($('.mapplic-pin', this).hasClass('pin-label')) $('#landmark-settings .label-input').show();
		else $('#landmark-settings .label-input').hide();

		var selected = $('.selected-pin');
		if (selected.length) {

			var data = selected.data('landmarkData'),
				pin = $('.mapplic-pin', this).data('pin');

			selected.attr('class', 'mapplic-pin selected-pin ' + pin);
			data.pin = pin;
		}
	});

	// Settings panel
	$(document).on('keyup', '#setting-height', function(e) {
		var text = $(this).val();
		if (text === '') text = 'auto';

		$('#h-attribute').text(text);
	});

	$('.help-toggle').mousedown(function(e) { e.preventDefault(); });
	$('.help-toggle').click(function() { $(this).parent().next('.help-content').toggle(100); });

	// setting groups
	$('.settings-toggle').change(function() {
		var group = $('.settings-group[data-group="' + $(this).attr('data-setting') + '"]');
		if (this.checked) {
			$('input:not(.dis)', group).prop('disabled', false);
			group.removeClass('disabled');
		}
		else {
			$('input', group).prop('disabled', true);
			group.addClass('disabled');
		}
	}).change();

	// Landmarks
	function MapplicAdmin() {
		
		this.init = function() {
			return this;
		}

		this.newLocation = function(id) {
			// Remove selection if any
			$('.selected-pin').removeClass('selected-pin');
			// Show empty landmark fields
			$('#landmark-settings').show();
			$('#landmark-settings input[type="text"]').val('');
			$('#landmark-settings .mapplic-landmark-field').val('');
			
			if (typeof id !== 'undefined') $('#landmark-settings .id-input').val(id);

			if ($('#wp-descriptioninput-wrap').hasClass('html-active')) $('#descriptioninput').val('');
			else tinyMCE.get('descriptioninput').setContent('');
			
			$('#landmark-settings .style-select').val('false');
			$('#landmark-settings .category-select').val('false');
			$('#landmark-settings .action-select').val('default');
			$('#landmark-settings .hide-input').prop('checked', false);
			// Change button text
			$('.save-landmark').val(mapplic_localization.add);
			$('.duplicate-landmark').hide();
		}
	}
	var admin = new MapplicAdmin().init();

	$('#new-landmark').click(function() {
		admin.newLocation();
	});

	$('.save-landmark').click(function() {
		var data = null,
			selected = $('.selected-pin');

		// No id specified
		if (!$('#landmark-settings .id-input').val()) {
			alert(mapplic_localization.missing_id);
			return false;
		}
		
		if (selected.length) {
			// Save existing landmark
			data = selected.data('landmarkData');
			saveLandmarkData(data);

			$('.selected-pin').removeClass('selected-pin');
			$('#landmark-settings').hide();
		}
		else {
			// Add new landmark
			data = {};
			saveLandmarkData(data);

			data.x = 0.5;
			data.y = 0.5;

			newLandmark(data);
			$(this).val(mapplic_localization.save);
		}
	});

	$('.delete-landmark').click(function() {
		var data = $('.selected-pin').data('landmarkData');

		// Remove the location and pin
		if (data) {
			data.id = null;
			$('.selected-pin').remove();
		}

		// Hide the settings
		$('#landmark-settings').hide();
	});

	$('.duplicate-landmark').click(function() {
		var original = $('.selected-pin').data('landmarkData'),
			duplicate = jQuery.extend(true, {}, original);

		duplicate.id = prompt('Unique ID of the new landmark:', original.id + '-d');
		$('.selected-pin').removeClass('selected-pin');
		newLandmark(duplicate);
		$('#landmark-settings .id-input').val(duplicate.id);
	});

	var newLandmark = function(data) {
		$.each(mapData.levels, function(index, level) {
			if (level.id == shownLevel) {
				level.locations.push(data);
			}
		});

		// Add new pin to the map
		var pin = $('<a></a>').attr({'href': '#' + data.id, 'title': data.title}).addClass('mapplic-pin selected-pin').addClass(data.pin).css({'top': '50%', 'left': '50%'}).click(function(e) {
			e.preventDefault();
		}).appendTo($('.mapplic-layer:visible'));
		pin.data('landmarkData', data);

		$('.duplicate-landmark').show();
	}

	var saveLandmarkData = function(data) {
		data.id 			= $('#landmark-settings .id-input').val();
		data.title 			= $('#landmark-settings .title-input').val();
		data.description 	= $('#wp-descriptioninput-wrap').hasClass('html-active') ? $('#descriptioninput').val() : tinyMCE.get('descriptioninput').getContent();
		data.pin 			= $('#pins-input .selected .mapplic-pin').data('pin');
		data.label 			= $('#landmark-settings .label-input').val();
		data.fill 			= $('#landmark-settings .fill-input').val();
		data.link 			= $('#landmark-settings .link-input').val();
		data.style 			= $('#landmark-settings .style-select').val();
		data.action 		= $('#landmark-settings .action-select').val();
		data.zoom 			= $('#landmark-settings .zoom-input').val();

		// Custom fields
		$('#landmark-settings .mapplic-landmark-field').each(function(){
			var field = $(this).data('field');
			data[field] = $(this).val();
		});
	}

	var getParameter = function(param) {
		var pageURL = window.location.search.substring(1);
		var variables = pageURL.split('&');
		for (var i = 0; i < variables.length; i++) {
			var paramName = variables[i].split('=');
			if (paramName[0] == param) {
				return paramName[1];
			}
		}
	}

	// Load the map
	var adminmap = $('#mapplic-admin-map').mapplic({
		id: getParameter('map'),
		height: 480,
		locations: true,
		sidebar: false,
		search: true,
		minimap: true,
		slide: 0
	}).data('mapplic');
	if (adminmap) adminmap.admin = admin;

	var invalid;
	var errormsg;

	// Form submit
	$('input[type=submit]').click(function(event) {
		if ($('#mapplic-admin-map').is(':visible')) {
			invalid = false;

			var newData = {};

			if (typeof mapData === 'undefined') mapData = {};
			else newData = mapData;

			// required fields
			newData['mapwidth'] = $('#setting-mapwidth').val();
			newData['mapheight'] = $('#setting-mapheight').val();

			// text and select
			$('#settings input[type="text"], #settings select, #settings textarea').each(function() {
				var setting = $(this).attr('data-setting');
				if (setting) {
					if ($(this).val()) newData[setting] = $(this).val();
					else delete newData[setting];
				}
			});

			// checkboxes
			$('#settings input[type="checkbox"]').each(function() {
				var setting = $(this).attr('data-setting');
				if (setting) newData[setting] = $(this).is(':checked');
			});

			// Fetching data
			newData['levels'] = getLevels();
			newData['styles'] = getStyles();

			// Trigger event
			$('#mapplic-admin-map').trigger('mapplic-savedata', [newData]);

			// Validation
			if (invalid) {
				alert(errormsg);
				event.preventDefault();
				return false;
			}

			var str = JSON.stringify(newData);
			if (str.charAt(0) == '"' && str.charAt(str.length - 1) == '"') str = str.slice(1, -1);

			// Saving
			$('#mapplic-mapdata').val(str);
		}
	});

	var getLevels = function() {
		var levels = [];
		$('#floor-list .list-item:not(.new-item)').each(function() {
			var level = {};

			level['id']        = $('.id-input', this).val();
			level['title']     = $('.title-input', this).val();
			level['map']       = $('.map-input', this).val().replace('https:', '').replace('http:', '');
			level['minimap']   = $('.minimap-input', this).val();
			if ($('.show-input', this).is(':checked')) {
				level['show']  = 'true';
			}
			level['locations'] = getLocations(level['id']);

			// Validation
			if (level['id'] == '') {
				if (!invalid) errormsg = 'The floor titled "' + level['title'] + '" has no ID.';
				invalid = true;
			}

			levels.push(level);
		});

		levels.reverse();

		return levels;
	}

	var getStyles = function() {
		var styles = [];
		$('#style-list .list-item:not(.new-item)').each(function() {
			var style = {base: {}, hover:{}, active:{}};
			
			style['class'] = $('.class-input', this).val();

			style.base['fill'] = $('.base-fill', this).val();
			style.hover['fill'] = $('.hover-fill', this).val();
			style.active['fill'] = $('.active-fill', this).val();

			// Validation
			if (!/^([a-z_]|-[a-z_-])[a-z\d_-]*$/i.test(style['class'])) {
				if (!invalid) errormsg = '"' + style['class'] + '" is not a valid class name.';
				invalid = true;
			}

			styles.push(style);
		});

		return styles;
	}

	var getLocations = function(targetLevel) {
		var locations = [];
		
		if (typeof mapData.levels !== 'undefined') {
			$.each(mapData.levels, function(index, level) {
				if (level.id == targetLevel) {
					$.each(level.locations, function(index, location) {
						if (location.id !== null) {
							delete location.el;

							for (var key in location) {
								if (location[key] == '') delete location[key];
							}
							locations.push(location);
						}
					});
				}
			});
		}
		
		return locations;
	}
});