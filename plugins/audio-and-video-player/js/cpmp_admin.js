var cpmp = function($){
	// Messages
	var files_required 		= 'At least a Songs/Videos files must be entered',
		annotation_required = 'Please, enter the item title to identify it';

	// Extend array
	function inArray(a, v){
		for(var j in a){
			if(a[j] == v){
				return j;
			}
		}
		return -1;
	}

	// Create the JSON object used through the project
	var JSON = JSON || {};

	// implement JSON.stringify serialization
	JSON.stringify = JSON.stringify || function (obj) {
		var t = typeof (obj);
		if (t != "object" || obj === null) {
			// simple data type
			if (t == "string") obj = '"'+obj+'"';
			return String(obj);
		}
		else {
			// recurse array or object
			var n, v, json = [], arr = (obj && obj.constructor == Array);
			for (n in obj) {
				v = obj[n]; t = typeof(v);
				if (t == "string") v = '"'+v+'"';
				else if (t == "object" && v !== null) v = JSON.stringify(v);
				json.push((arr ? "" : '"' + n + '":') + String(v));
			}
			return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
		}
	};

	// implement JSON.parse de-serialization
	JSON.parse = JSON.parse || function (str) {
		if (str === "") str = '""';
		eval("var p=" + str + ";");
		return p;
	};

	function is_empty(v){
		return /^\s*$/.test(v);
	}

	function display_item_form(){
		obj.clear_item_form();
		$('#item_form').show();
	}

	function set_skin(e, skin, width, height){
		e = $(e);
		$('.skin_selected').removeClass('skin_selected').css('border', '2px solid #FFF');
		e.addClass('skin_selected').css('border','2px solid #4291D1');
		$('input[name="cpmp_skin"]').val(skin);
		if(width){
			$('#cpmp_width_info').text('Value should be greater than or equal to:'+width);
			$('#cpmp_height_info').text('Value should be greater than or equal to:'+height);
		}
	}

	function load_additional_skins(data){
		var s 		= $('#skin_container'),
			title	= ' - Updates the premium version of plugin to get this skin';
		if(s.length){
			$.getJSON('//cpmediaplayer.dwbooster.com/cpmp_skins.php?callback=?', function(data){
				if(data){
					var skin_list = [];

					if(typeof cpmp_skin_list != 'undefined' && cpmp_skin_list){
						skin_list = cpmp_skin_list;
					}


					for(var i=0, h=data.length; i < h; i++){
						if(inArray(skin_list, data[i].id) == -1)
						s.append('<a href="'+data[i].link+'" target="_blank" style="margin-left:5px;"><img src="'+data[i].thumbnail+'" title='+data[i].name+( ( !/custom\-skin\-commercial/i.test( data[i].thumbnail ) ) ? '"'+title+'" style="opacity:.3;"' : '""' )+'" border="0" /></a>');
					}
				}
			});
		}
	}

	function edit_player(id){
		$('#player_id').val(id);
		$('#cpmp_action').val('update').parents('form')[0].submit();;
	}

	function duplicate_player(id){
		$('#player_id').val(id);
		$('#cpmp_action').val('duplicate').parents('form')[0].submit();;
	}

	function remove_player(id){
		$('#player_id').val(id);
		$('#cpmp_action').val('remove').parents('form')[0].submit();;
	}

	function add_field(e, base){
		var id = new Date().getTime();

		var e  = $(e),
			p  = e.parent('div'),
			c  = p.clone(),
			l  = c.find('.thickbox');
		if(l.length)
			l.attr('href', l.attr('href').replace(/container_id=[^&]+&/i, 'container_id='+base+id+'&'));
		c.find('.'+base).attr('id', base+id);
		c.find('input[type="text"]').val('');

		if(c.find('.remove_field').length == 0)
			c.append('<a href="javascript:void(0);" style="text-decoration:none;" class="remove_field" onclick="cpmp.remove_field(this);">[-] Remove</a>');

		p.after(c);
		return c;
	}

	function remove_field(e){
		$(e).parent('div').remove();
	}

	function add_item(){

		var item_id = $('#item_id').val();
		var annotation = $('#item_annotation').val(),
			item = {
			id 			: (item_id == "") ? new Date().getTime() : item_id,
			annotation 	: annotation.replace(/\"/g,"&quot;"),
			link	   	: $('#item_link').val().replace(/\"/g,"&quot;"),
			poster	   	: ($('#item_poster').length) ? $('#item_poster').val().replace(/\"/g,"&quot;") : "",
			files		: [],
			subtitles	: []
		};

		$('#item_form').find('.item_file').each(function(i, e){
			var v = $(this).val();
			if(!is_empty(v))
				item.files[i] = v.replace(/\"/g,"&quot;");
		});

		// Requirements
		if(is_empty(item.annotation)){
			alert(annotation_required);
			return;
		}

		if(item.files.length == 0){
			alert(files_required);
			return;
		}


		$('#item_form').find('.item_subtitle').each(function(i, e){
			var v = $(this).val();
			if(!is_empty(v)){
				item.subtitles[i] = {
					'link' : v.replace(/\"/g,"&quot;")
				};
				var l = $(this).next('.item_subtitle_lang').val();
				if(!is_empty(l)){
					item.subtitles[i]['language'] = l.replace(/\"/g,"&quot;");
				}else{
					l = v.substr(v.lastIndexOf('/')+1);
					var p = l.lastIndexOf('.');
					l = l.substr(0, ((p != -1) ? p: l.length));
					item.subtitles[i]['language'] = l.replace(/\"/g,"&quot;");
				}
			}
		});

		if(item_id == ''){ // Insert a new item
			obj.items.push(item);
			$('#items_container').append(
			'<div id="'+item.id+'" class="playlist_item" style="cursor:pointer;width:100%;margin:5px;background-color:#c7e4f3;">'+
			'<div style="float:left;">'+
			'<a href="javascript:void(0);" onclick="cpmp.move_item(\''+item.id+'\', -1);" title="Up" style="text-decoration:none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.58 5.59L20 12l-8-8-8 8z"/></svg></a>'+
			'<a href="javascript:void(0);" onclick="cpmp.move_item(\''+item.id+'\', 1);" title="Down" style="text-decoration:none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path fill="#010101" d="M20 12l-1.41-1.41L13 16.17V4h-2v12.17l-5.58-5.59L4 12l8 8 8-8z"/></svg></a>'+
			'<a href="javascript:void(0);" onclick="cpmp.delete_item(\''+item.id+'\');" title="Delete item" style="text-decoration:none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/><path d="M0 0h24v24H0z" fill="none"/></svg></a>'+
			'</div>'+
			'<div style="float:left;line-height:24px;"><span>'+annotation+'</span></div>'+
			'<div style="clear:both;"></div>'+
			'</div>');
		}else{ // Edit an existent item
			for(var i = 0, h = obj.items.length; i < h; i++){
			  if(obj.items[i].id == item_id){
				obj.items[i] = item;

				// Update the item text in playlist
				$('#'+item_id).find('span').text(annotation);
				break;
			  }
			}
		}
		clear_item_form();
	}

	function delete_item(item_id){

		// Clear item form if item is selected
		if( $('#item_id').val() == item_id){
			obj.clear_item_form();
		}

		for(var i = 0, h = obj.items.length; i < h; i++){
			if(obj.items[i].id == item_id){
				obj.items.splice(i, 1); // Remove item from obj.items
				$('#'+item_id).remove();// Remove item from playlist
				return;
			}
		}
	}

	function swap(a, b){
		var ea = $('#'+a),
			eb = $('#'+b),
			tmp;

		tmp = ea.html();
		ea.html(eb.html());
		eb.html(tmp);
		ea.attr('id', b);
		eb.attr('id', a);
	}

	function move_item(item_id, disp){

		var l = obj.items.length,
			e, ne,
			np, p = -1,
			tmp;

		if(l){
			for(var i = 0; i < l; i++){
				if(obj.items[i].id == item_id){
					p = i;
					break;
				}
			}

			np = p+disp;
			if(np < l && np >= 0){
				tmp = obj.items[p];
				obj.items[p] = obj.items[np];
				obj.items[np] = tmp;

				//ordering visual components
				swap(obj.items[p].id, obj.items[np].id);

			}
		}
	}

	function clear_item_form(){
		$('#item_form').find('input[type="text"], input[type="hidden"]').val('');
		$('.remove_field').click();
	}

	function hide_item_form(){
		$('#item_form').hide();
	}

	function submit_item_form(){
		if(is_empty($('#cpmp_player_name').val())){
			alert('The media player\' name is required.');
			return;
		}
		if(obj.items.length){
			var f = $('#cpmp_media_player_form'),
				playlist_items_str = JSON.stringify(obj.items),
				playlist_field = $('<input type="hidden" name="cpmp_media_player_playlist" />');

			playlist_field.val(playlist_items_str);
			f.append(playlist_field);
			f[0].submit();
		}else{
			alert('At least a item must be entered to playlist.');
		}
	}

	function edit_item($pl_item){

		function set_value(e, value){
			if(value){
				$('#'+e).val(value);
			}
		};

		var item_id = $pl_item.attr('id');

		// Clear the item form
		obj.clear_item_form();
		$('#item_form').show();

		// Search the item data
		for(var i = 0, h = obj.items.length; i < h; i++){
			if(obj.items[i].id == item_id){
				var item = obj.items[i];

				set_value('item_id', item_id);
				set_value('item_annotation', item.annotation.replace(/&quot;/g, '"'));
				set_value('item_link', item.link);
				set_value('item_poster', item.poster);

				for(var j = 0, k = item.files.length; j < k; j++){
					var e = $('.item_file').last();
					if(e.val() != ""){
						e = obj.add_field(e, 'item_file').find('.item_file');
					}
					e.val(item.files[j]);
				}

				for(var j = 0, k = item.subtitles.length; j < k; j++){
					var subtitle 	= item.subtitles[j],
						e 			= $('.item_subtitle').last();

					if(e.val() != ""){
						e = obj.add_field(e, 'item_subtitle').find('.item_subtitle');
					}
					e.val(subtitle.link)
					e.next('.item_subtitle_lang').val(subtitle.language);
				}
			}
		}
	}

	function new_player_window( type )
	{
		var c = $(
				'<div title="New Player"><div style="padding:20px;">'+
				'<label for="cpmp_media_player">Select the skin</label>'+
				'<div id="cpm_controls_container" style="white-space:nowrap;">'+cpmp_insert_media_player.skins+'&nbsp;&nbsp;</div>'+
				'</div></div>'
			),
			b = $('<button onclick="">Select '+type+' files</button>'),
			d = $(
				'<p>- or - Enter a subdir of "Uploads"</p>'+
				'<p><input type="text" id="cpm_dir" />&nbsp;&nbsp;<input type="button" value="Insert" id="cpm_insert_player" /></p>'
			),
			o;

		b.appendTo(c.find('#cpm_controls_container'))
		 .on('click', function(){
			var media = wp.media(
				{
					title: 'Select Media File',
					library: {
						type: [type]
					},
					button: {
						text: 'Select Item'
					},
					multiple: true
				}).on('select',
					function()
					{
						var player = "",
							playlist = "\n",
							skin = $('#cpmp_skins'),
							attachments = media.state().get('selection').map(
								function( attachment )
								{
									return attachment.toJSON();
								}
							);
						if(attachments.length)
						{
							for(var i in attachments)
							{
								var fileObj = attachments[i],
									url 	= fileObj.url,
									name 	= '';

								if(('title' in fileObj) && fileObj['title'].length) name = fileObj['title'];
								else if(('description' in fileObj) && fileObj['description'].length) name = fileObj['description'];
								else name = fileObj['filename'];
								playlist += "[cpm-item file=\""+url+"\"]"+name+"[/cpm-item]\n";
							}
						}
						player = '[cpm-player skin="'+((skin.length) ? skin.val(): 'device-player-skin')+'" width="100%" playlist="true" type="'+type+'"]'+playlist+'[/cpm-player]';
						if(send_to_editor) send_to_editor(player);
						o.dialog('close');
					}
				).open();
		});
		d.appendTo(c.find('#cpm_controls_container')).on('click', function(){
			var player = "",
				skin = $('#cpmp_skins'),
				dir = $('#cpm_dir').val();
			if(dir)
			{
				dir = dir.replace(/"/g, '');
				player = '[cpm-player skin="'+((skin.length) ? skin.val(): 'device-player-skin')+'" width="100%" playlist="true" type="'+type+'" dir="'+dir+'" /]';
				if(send_to_editor) send_to_editor(player);
				$('.cpm-dialog .ui-dialog-titlebar button').click();
			}
		});
		o = c.dialog({
			dialogClass: 'wp-dialog cpm-dialog',
			minWidth: 400,
            modal: true,
            closeOnEscape: true,
            buttons: [],
			close:function(){
				o.dialog('destroy');
				c.remove();
			}
        });
	}

	function open_insertion_window()
	{
		var c = $(' <div title="'+cpmp_insert_media_player.title+'"><div style="padding:20px;"><label for="cpmp_media_player">'+
					cpmp_insert_media_player.label+'<br />'+cpmp_insert_media_player.tag+'<br />'+
					'<a href="options-general.php?page=codepeople-media-player.php">'+cpmp_insert_media_player.new_label+'</a>'+
					'</div></div>'
				);

		c.dialog({
			dialogClass: 'wp-dialog cpm-dialog',
            modal: true,
            closeOnEscape: true,
            buttons: [
                {text: 'OK', click: function() {
					var p = $('#cpmp_media_player');
					if(p.length){
						var v = p[0].options[p[0].selectedIndex].value;
						if(send_to_editor){
							send_to_editor('[codepeople-html5-media-player id="'+v+'"]');
						}
					}
					$(this).dialog("close");
				}}
            ],
			close:function(){
				$(this).dialog('destroy');
				c.remove();
			}
        });
	};


    // Routines for files selection

    // Main application
    window['delete_purchase'] = function(id)
	{
		if(confirm('Are you sure to delete the purchase record?')){
			var f = $('#purchase_form');
			f.append('<input type="hidden" name="purchase_id" value="'+id+'" />');
			f[0].submit();
		}
	};

    jQuery('.for-sale,.for-watermark').on('click', 'input[type="button"]', function(evt)
	{
        var t = $(evt.target);
        if(t.hasClass('button_for_upload_cpmp')){
            var file_path_field = t.parent().find('[type="text"]');
			var media = wp.media(
				{
						title: 'Select Media File',
						button: {
						text: 'Select Item'
						},
						multiple: false
				}).on('select',
					(function( field ){
						return function() {
							var attachment = media.state().get('selection').first().toJSON();
							var url = attachment.url;
							field.val( url );
						};
					})( file_path_field )
				).open();
		}
        return false;
    });


	// Main program

	// Global events

	$('#items_container').on('click', function(evt){
        var t = $(evt.target);
        if(t[0].tagName == 'SPAN' && t.parents('.playlist_item').length > 0)
		{
			var new_position = $('#item_form').offset();
			cpmp.edit_item(t.parents('.playlist_item'));
			$('html, body').stop().animate({ scrollTop: new_position.top }, 500);
		}
    });

	// CPMP object definition
	var obj = {
		items:[]
	};

	// Assign methods
	obj.display_item_form = display_item_form;
	obj.set_skin = set_skin;
	obj.load_additional_skins = load_additional_skins;
	obj.edit_player = edit_player;
	obj.remove_player = remove_player;
	obj.duplicate_player = duplicate_player;
	obj.add_field = add_field;
	obj.remove_field = remove_field;
	obj.clear_item_form = clear_item_form;
	obj.hide_item_form = hide_item_form;
	obj.submit_item_form = submit_item_form;
	obj.add_item = add_item;
	obj.delete_item = delete_item;
	obj.move_item = move_item;
	obj.edit_item = edit_item;
	obj.open_insertion_window = open_insertion_window;
	obj.new_player_window = new_player_window;

	return obj;
}(jQuery);

jQuery(
	function($){

		if(typeof cpmp_playlist_items != 'undefined' && cpmp_playlist_items){
			cpmp.items = cpmp_playlist_items;
		}

		cpmp.load_additional_skins();
	}
);

function avp_select_file( e ){
	var avp_file_path_field = jQuery( e ).parent().find( 'input[type="text"]' )
	var media = wp.media(
		{
				title: 'Select Media File',
				button: {
				text: 'Select Item'
				},
				multiple: false
		}).on('select',
			(function( field ){
				return function() {
					var attachment = media.state().get('selection').first().toJSON();
					var url = attachment.url;
					field.val( url );
				};
			})( avp_file_path_field )
		).open();
	return false;
}

function avp_toggle_additional_attributes(e)
{
	var $ = jQuery,
		t;

	e = $(e);
	t = e.text();

	if(t.indexOf('[+]') != -1) t = t.replace('[+]','[-]');
	else t = t.replace('[-]','[+]');

	e.text(t);

	$('.cpmp-additional-attr').toggle();
}