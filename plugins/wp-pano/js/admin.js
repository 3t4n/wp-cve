var NewPanoLoaded = false;

function pos_update(ath, atv) {
	document.getElementById('ath').value=ath;
	document.getElementById('atv').value=atv;
}

function wppano_JS_OnNewPano(vtour_name, pano, scene) {
	jQuery('#pano_name').val(pano.toString());
	jQuery('#scene_name').val(scene.toString());
	jQuery('#vtourname').val(vtour_name.toString());
	var post_id = jQuery('#post_id');
	var post_title = jQuery('#post_title');
	var visible = false;
	jQuery('.hotspot_element').each(function() {
		_this = jQuery(this);
		if (_this.attr('vtourname') != vtour_name) {
			_this.hide();
		} else {
			_this.css("display", "table");
		}
		if (_this.attr('scene_name') != scene || _this.attr('pano_name') != pano) {
			_this.children('.SearchHotspot').show();
			_this.children('.wp-pano-hs-style').hide();
			_this.children('.wppano_UpdateHotspot').hide();
			_this.children('.wppano_DeleteHotspot').hide();
		} else {
			jQuery('.all-hotspots').prepend( _this );
			_this.children('.SearchHotspot').hide();
			_this.children('.wp-pano-hs-style').val(_this.attr('hs_style'));
			_this.children('.wp-pano-hs-style').show();
			_this.children('.wppano_UpdateHotspot').show();
			_this.children('.wppano_DeleteHotspot').show();
			visible = true;
			krpano.set('wp_pano.post_title', post_title.val());
			krpano.call("wppano_admin_addnewhotspot("+post_id.val()+", "+_this.attr('ath')+", "+_this.attr('atv')+", '"+_this.attr('hs_style')+"')");
			krpano.call("moveto("+_this.attr('ath')+","+_this.attr('atv')+");");
		}
	});
	if(visible) {
		jQuery('.admin-add-new-hotspot :input').attr("disabled", true);
		jQuery('.admin-add-new-hotspot').css("opacity", 0.5);
	} else {
		jQuery('.admin-add-new-hotspot :input').attr("disabled", false);
		jQuery('.admin-add-new-hotspot').css("opacity", 1);
		var hotspot = jQuery('.hotspot_element').last();
		if (hotspot.length != 0 && !NewPanoLoaded) {
			if (hotspot.attr('pano_name') != pano) krpano.call("loadpano(" + hotspot.attr('pano_name') + ");");
			if (hotspot.attr('scene_name') != scene) krpano.call("loadscene(" + hotspot.attr('scene_name') + ");");
		}
	}
	NewPanoLoaded = true;
}

function wp_pano_updateHotspot(ath, atv) {
    console.log('update hotspot position');
    var post_id = jQuery('#post_id');
    var vtourname = jQuery('#vtourname');
    var pano_name = jQuery('#pano_name');
    var scene_name = jQuery('#scene_name');
	var button = jQuery('.wppano_UpdateHotspot');
    jQuery.ajax({
        type: 'POST',
        url: ajax.url,
        data : {action: "wppano_UpdateHotspot", nonce : ajax.nonce, post_id:post_id.val(), vtourname:vtourname.val(), pano_name:pano_name.val(), scene_name:scene_name.val(), ath:ath, atv:atv},
        beforeSend:function(xhr){
			button.attr('readonly','readonly').html('Updating...');
        },
        success: function(response){
            result = jQuery.parseJSON( response );
			console.log(result);
            if(result.type == "success") {
				button.attr('readonly','readonly').html('Success');
				setTimeout(function() {
					button.removeAttr('readonly').html('Update');
				}, 1000);
            } else {
				button.attr('readonly','readonly').html('Something broke:(');
				setTimeout(function() {
					button.removeAttr('readonly').html('Update');
				}, 1000);				
            }
        }
    });
}

jQuery(document).ready(function(){
	jQuery('.wppano_AddNewHotspot').click(function(){
		var _this = jQuery(this);
		var post_id = jQuery('#post_id');
		var post_title = jQuery('#post_title');
		var vtourname = jQuery('#vtourname');
		var pano_name = jQuery('#pano_name');
		var scene_name = jQuery('#scene_name');
		var style = jQuery('#hs_style');
		var ath = jQuery('#ath');
		var atv = jQuery('#atv');
		jQuery.ajax({
			type: 'POST',
			url: ajax.url,
			data : {action: "wppano_AddNewHotspot", nonce : ajax.nonce, style:style.val(), vtourname:vtourname.val(), post_id:post_id.val(), pano_name:pano_name.val(), scene_name:scene_name.val(), ath:ath.val(), atv:atv.val()},
			beforeSend:function(xhr){
				_this.attr('readonly','readonly').html('Saving...');
			},
			success: function(response){
				result = jQuery.parseJSON( response );
				if(result.type == "success") {
					_this.removeAttr('readonly').html('Success');
					krpano.set('wp_pano.post_title', post_title.val());
					krpano.call("wppano_admin_addnewhotspot("+post_id.val()+", "+ath.val()+", "+atv.val()+", '"+style.val()+"')");					
					var text = jQuery('.new-hotspot').html();
					jQuery('.all-hotspots').append(text);
					var new_hotspot = jQuery('.hotspot_element_template').last();
					new_hotspot.addClass( "hotspot_element" ).removeClass( "hotspot_element_template" );
					new_hotspot.attr( 'ath', jQuery('#ath').val());
					new_hotspot.attr( 'atv', jQuery('#atv').val());
					new_hotspot.attr( 'pano_name', jQuery('#pano_name').val());
					new_hotspot.attr( 'scene_name', jQuery('#scene_name').val());
					new_hotspot.attr( 'vtourname', jQuery('#vtourname').val());
					jQuery('.all-hotspots').prepend( new_hotspot );
					new_hotspot.css("display", "table");
					if (jQuery("#scene_name").val() != 'null') 
						text = 'vtour name: <b>' + jQuery("#vtourname").val() + '</b>' + ' pano: <b>' + jQuery("#pano_name").val() + '</b>' + ' scene: <b>' + jQuery("#scene_name").val() + '</b>';
					else
						text = 'vtour name: <b>' + jQuery("#vtourname").val() + '</b>' + ' pano: <b>' + jQuery("#pano_name").val() + '</b>';
					new_hotspot.find("span").append(text);
					jQuery('.admin-add-new-hotspot :input').attr("disabled", true);
					jQuery('.admin-add-new-hotspot').css("opacity", 0.5);
					setTimeout(function() {
						_this.html('Add new');
					}, 1000);
					
				} else {
					_this.removeAttr('readonly').html('Something wrong :(');
					setTimeout(function() {
						_this.next().html('');
					}, 1000);
				}
			}
		});
	});


	
	jQuery('.wppano_UpdateHotspot').on("click", function(){
		var _this = jQuery(this);
		var container = _this.closest('.hotspot_element');		
		var post_id = jQuery('#post_id');
		var vtourname = jQuery('#vtourname');
		var pano_name = jQuery('#pano_name');
		var scene_name = jQuery('#scene_name');
		var style = container.find('.wp-pano-hs-style');
		var ath = jQuery('#ath');
		var atv = jQuery('#atv');
		jQuery.ajax({
			type: 'POST',
			url: ajax.url,
			data : {action: "wppano_UpdateHotspot", nonce : ajax.nonce, post_id:post_id.val(), style:style.val(), vtourname:vtourname.val(), pano_name:pano_name.val(), scene_name:scene_name.val(), ath:ath.val(), atv:atv.val()},
			beforeSend:function(xhr){
				_this.attr('readonly','readonly').html('Updating...');
			},
			success: function(response){
				result = jQuery.parseJSON( response );
				
				if(result.type == "success") {
					krpano.call('wppano_UpdateHotspot('+post_id.val()+', '+ath.val()+', '+atv.val()+');');
					_this.html('Success');
					container.attr('ath', ath.val());
					container.attr('atv', atv.val());
					container.attr('hs_style', style.val());
					setTimeout(function() {
						_this.removeAttr('readonly').html('Update');
					}, 1000);
				} else {
					if(result.type == "nochanges") {
						_this.html('No changes');
						setTimeout(function() {
							_this.removeAttr('readonly').html('Update');
						}, 1000);						
					} else {
						_this.html('Something wrong :(');
						setTimeout(function() {
							_this.removeAttr('readonly').html('Update');
						}, 1000);
					}
				}
			}
		});
	});	
	
	jQuery('.wppano_DeleteHotspot').on("click", function(){
		var _this = jQuery(this);
		var container = _this.closest('.hotspot_element');
		var vtourname = jQuery('#vtourname');
		jQuery.ajax({
			type: 'POST',
			url: ajax.url,
			data : {action: "wppano_DeleteHotspot", nonce : ajax.nonce, vtourname:container.attr('vtourname'), post_id:container.attr('post_id'), pano_name:container.attr('pano_name'), scene_name:container.attr('scene_name')},
			beforeSend:function(xhr){
				_this.attr('readonly','readonly').next().html('Good bye...');
			},
			success: function(response){
				result = jQuery.parseJSON( response );
				if(result.type == "success") {

					_this.removeAttr('readonly').next().html('<span style="color:#0FB10F;">Good bye...</span>');
					krpano.call('wppano_RemoveHotspot('+container.attr('post_id')+');');
					container.fadeOut( 1000, function() {
						jQuery('.admin-add-new-hotspot :input').attr("disabled", false);
						jQuery('.admin-add-new-hotspot').css("opacity", 1);						
						container.remove();
						});
				} else {
					_this.removeAttr('readonly').next().html('<span style="color:#F00;">Something wrong :(</span>');
					setTimeout(function() {
						_this.next().html('');
					}, 1000);
				}
			}
		});
	});	
	
	jQuery('.SearchHotspot').on("click", function(){
		var _this = jQuery(this);
		var container = _this.closest('.hotspot_element');
		var pano_name = jQuery('#pano_name');
		var scene_name = jQuery('#scene_name');
		jQuery('.admin-add-new-hotspot :input').attr("disabled", true);
		jQuery('.admin-add-new-hotspot').css("opacity", 0.5);
		if (container.attr('pano_name') != pano_name.val()) {
			krpano.call('loadpano('+container.attr('pano_name')+');');
		};
		if (container.attr('scene_name') != scene_name.val()) {
			krpano.call('loadscene('+container.attr('scene_name')+'); lookto('+container.attr('ath')+','+container.attr('atv')+');');
		};
		if (container.attr('pano_name') != pano_name.val() || container.attr('scene_name') != scene_name.val()) {
			krpano.call('lookto('+container.attr('ath')+','+container.attr('atv')+');');
		};
		
	});		
});