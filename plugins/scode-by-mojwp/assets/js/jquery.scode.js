jQuery(document).ready(function($) {
	
	$('a.open-modal-button').click(function() {
		var id = $(this).attr('href');
		if ($(id).size() != 0) openModal(id);
		return false;
	});
	
	function openModal(id) {
		var modal = $(id);
		
		if ($('#fade').size() == 0)
			$('body').append('<div id="fade"></div>');
		
		$('#fade').css("opacity", 0.8).data('currentModal', id).fadeIn();

		modal.fadeIn().position({
			my: "center",
			at: "center",
			of: window,
			collision : "flipfit"
		});
	}
	
	function closeModals() {
		var currentModal = $('#fade').data('currentModal');
		if (currentModal != '')
			$('#fade, '+currentModal).fadeOut('slow', function() {
				if ($(this).attr('id') != 'fade' && currentModal == '#editShortcodeModal') {
					var shortcode_input = $('#editableShortcode');
					var description_input = $('#editableShortcodeDescription');
					var value_input = $('#editableShortcodeValue');
					var new_group_input = $('#editableShortcodeNewGroup');
					// var group_select_options = $('#editableShortcodeAvailableGroup');
					var editButton = $('#editShortcodeButton');
					var progressContainer = $('#viewShortcodeProgressContainer');
					var form = $('#editable');
					var pResult = $('p#editResult');
					
					shortcode_input.val('');
					description_input.val('');
					value_input.val('');
					new_group_input.val('');
					editButton.removeData('shortcodeId'); 
					editButton.removeData('editNonce'); 
					progressContainer.removeClass('hidden');
					form.addClass('hidden');
					pResult.text('');
				}
			});
	}
	
	$('body').on('click', 'div#fade, a.modal-close-button', (function() {
		closeModals();
		return false;
	}));
	
	$(document).keyup(function(e) {
	  if (e.keyCode == 27) closeModals();
	});
	
	$('#addNewShortcodeButton').click(function() {
		var button = $(this);
		var progressIcon = $('#addShortcodeProgress');
		var pResult = $('p#addResult');
		
		sendData = {};
		sendData.action = 'scode_add_shortcode';
		sendData.scode_new_shortcode = $('#newShortcode').val();
		sendData.scode_new_description = $('#newShortcodeDescription').val();
		sendData.scode_new_value = $('#newShortcodeValue').val();
		sendData.scode_new_nonce = button.data('addNonce');
		sendData.scode_new_group = $('#newShortcodeNewGroup').val();
		var groupSelect = $('#newShortcodeAvailableGroup');
		
		if (groupSelect.size != 0) sendData.scode_group = groupSelect.val();
		
		progressIcon.removeClass('hidden');
		button.prop('disabled', true);
		pResult.text('').removeClass('success error');
		
		$.post(ajaxurl, sendData, function(data) {
			// data = $.parseJSON(data);

			if (data.success) {
				pResult.addClass('success').text(data.success);
				progressIcon.addClass('hidden');
				
				setTimeout("window.location.reload()", 300);
			} else {
				if (data.error) pResult.addClass('error').text(data.error); else pResult.addClass('error').text('Unknown error, please contact the plugin developers...');
				button.prop('disabled', false);
				progressIcon.addClass('hidden');
			} 
		}, 'json');
		
		return false;
	});
	
	$('a.submitdelete').click(function() {
		var shortcodeId = $(this).data('shortcodeId');
		var nonce = $('input[name=nonce_del]').val();
		var parentTr = $(this).parents('tr');

		$.post(ajaxurl, {scode_shortcode_id: shortcodeId, scode_del_nonce: nonce, action: 'scode_del_shortcode'}, function(data) {
			// data = $.parseJSON(data);

			if (data.success) {
				alert(data.success);
				parentTr.fadeOut(1000, function() {
					$(this).remove();
				});
			} else {
				if (data.error) alert(data.error); else alert('Unknown error, please contact the plugin developers...');
			} 
		}, 'json');
		
		return false;
	});
	
	$('a.submitedit').click(function() {
		var modalID = $(this).attr('href');
		var shortcodeId = $(this).data('shortcodeId');
		var nonce = $('input[name=nonce_view]').val();
		var progressContainer = $('#viewShortcodeProgressContainer');
		var form = $('#editable');
		
		var sendData = {};
		sendData.scode_shortcode_id = shortcodeId;
		sendData.scode_view_nonce = nonce;
		sendData.action = 'scode_view_shortcode';
		
		var shortcode_input = $('#editableShortcode');
		var description_input = $('#editableShortcodeDescription');
		var value_input = $('#editableShortcodeValue');
		var group_select_options = $('#editableShortcodeAvailableGroup option');
		var editButton = $('#editShortcodeButton');
		var pResult = $('p#editResult');
		
		openModal(modalID);
		
		$.post(ajaxurl, sendData, function(data) {
			if (data.success) {
				shortcode_input.val(data.success.code);
				description_input.val(data.success.description);
				value_input.val(data.success.value);
				group_select_options.filter(function() {
					return $(this).val() == data.success.group_id;
				}).prop('selected', true);
				editButton.data('shortcodeId', data.success.shortcode_id);
				editButton.data('editNonce', data.success.nonce);
				
				progressContainer.addClass('hidden');
				form.removeClass('hidden');
			} else {
				progressContainer.addClass('hidden');
				if (data.error) pResult.addClass('error').text(data.error); else pResult.addClass('error').text('Unknown error, please contact the plugin developers...');
			}
			
			$(modalID).position({
				my: "center",
				at: "center",
				of: window,
				collision : "flipfit"
			});
		}, 'json');
		
		return false;
	});
	
	$('#editShortcodeButton').click(function() {
		var button = $(this);
		var progressIcon = $('#editShortcodeProgress');
		var pResult = $('p#editResult');
		
		var shortcodeID = button.data('shortcodeId');
		
		var sendData = {};
		sendData.scode_editable_shortcode_id = shortcodeID;
		sendData.scode_editable_shortcode = $('#editableShortcode').val();
		sendData.scode_editable_description = $('#editableShortcodeDescription').val();
		sendData.scode_editable_value = $('#editableShortcodeValue').val();
		sendData.scode_editable_nonce = button.data('editNonce');
		sendData.action = 'scode_edit_shortcode';
		sendData.scode_new_group = $('#editableShortcodeNewGroup').val();
		var groupSelect = $('#editableShortcodeAvailableGroup');
		
		if (groupSelect.size != 0) sendData.scode_group = groupSelect.val();
			
		progressIcon.removeClass('hidden');
		
		if (shortcodeID == '') {
			progressIcon.addClass('hidden');
			return false;
		}
		
		button.prop('disabled', true);
		pResult.text('').removeClass('success error');
		
		$.post(ajaxurl, sendData, function(data) {
			if (data.success) {
				pResult.addClass('success').text(data.success);
				progressIcon.addClass('hidden');
				setTimeout("window.location.reload()", 300);
			} else {
				if (data.error) pResult.addClass('error').text(data.error); else pResult.addClass('error').text('Unknown error, please contact the plugin developers...');
				button.prop('disabled', false);
				progressIcon.addClass('hidden');
			} 
		}, 'json');
		
		return false;
	});

	$('#scode-shortcodes-table div.row-title').click(function() {
		var e=this;
		if (window.getSelection) {
			var s = window.getSelection();
			if (s.setBaseAndExtent) {
				s.setBaseAndExtent(e,0,e,e.innerText.length-1);
			} else {
				var r = document.createRange();
				r.selectNodeContents(e);
				s.removeAllRanges();
				s.addRange(r);
			}
		} else if (document.getSelection) {
			var s = document.getSelection();
			var r = document.createRange();
			r.selectNodeContents(e);
			s.removeAllRanges();
			s.addRange(r);
		} else if (document.selection) {
			var r = document.body.createTextRange();
			r.moveToElementText(e);
			r.select();
		}
	});
	
});