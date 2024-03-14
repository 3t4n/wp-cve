($=>{

	'use strict';

	function themify_sl_add_num_row(value){
		$('.themify_sl_multi_num').append( '<tr class="themify_sl_multi_row"><td><input type="text" style="display:inline;width:100%;" class="themify_sl_number_lable" value="'+(value.lable)+'"></td><td><textarea type="text" style="display:inline;width:100%;" class="themify_sl_number">'+value.number+'</textarea></td><td><input type="text" style="display:inline;width:100%;" class="themify_sl_link" value="'+(value.link)+'"></td><td><span class="ti-close tsl_row_remover" style="vertical-align:middle;cursor:pointer" width="16px" height="16px"></span></td></tr>' );
		$('.tsl_row_remover').on('click', function(event){
			$(this).closest('.themify_sl_multi_row').remove();
			event.preventDefault();
		});
	}
	
	function themify_sl_add_time_row(value){
		$('.themify_sl_multi_time').append( '<tr class="themify_sl_multi_row"><td><input type="text" style="display:inline;width:100%;" class="themify_sl_time_day" value="'+(value.lable)+'"></td><td><input type="text" class="themify_sl_time_open" style="display:inline;width:100%;" value="'+(value.open)+'"/></td><td><input type="text" style="display:inline;width:100%;" class="themify_sl_time_close" value="'+(value.close)+'"></td><td><span class="ti-close tsl_row_remover" style="vertical-align:middle;cursor:pointer" width="16px" height="16px"></span></td></tr>' );
		$('.tsl_row_remover').on('click', function(event){
			$(this).closest('.themify_sl_multi_row').remove();
			event.preventDefault();
		});
	}
	var address = $( '#themify_storelocator_address' );
	if ( ! address.length ) {
		return;
	}
	var numbers = $( '#themify_storelocator_numbers' );
	var store_time = $( '#themify_storelocator_timing' );
	numbers.hide(),store_time.hide();
	
	if(address.val() == ''){
		address.val('{}');
	}
	if(numbers.val() == ""){
		numbers.val('[]');
	}
	if(store_time.val() == ""){
		store_time.val('[]');
	}
	
	$('#themify_sl_add_num').on('click',function(event){
		themify_sl_add_num_row({ 'lable':'','number':'', 'link' : '' });
		event.preventDefault();
	});
	
	$('#themify_sl_add_time').on('click',function(event){
		themify_sl_add_time_row({'lable':'','open':'','close':''});
		event.preventDefault();
	});
	
	function update_address( e ) {
		var multi_num = $(".themify_sl_multi_num").find('.themify_sl_multi_row'),
			multi_time = $(".themify_sl_multi_time").find('.themify_sl_multi_row'),
			temp=[], temp2;
		multi_num.each(function(){
			temp2 = {
					'lable':$(this).find('.themify_sl_number_lable').val(),
					'number':$(this).find('.themify_sl_number').val(),
					'link' : $(this).find('.themify_sl_link').val()
			};
			if(temp2.lable != "" || temp2.number != ""){
				temp.push(temp2);
			}
			$(this).remove();
		});
		numbers.val(JSON.stringify(temp));
		temp = [];
		multi_time.each(function(){
			temp2 = {
					'lable':$(this).find('.themify_sl_time_day').val(),
					'open':$(this).find('.themify_sl_time_open').val(),
					'close':$(this).find('.themify_sl_time_close').val()
			};
			if(temp2.lable != "" || temp2.open != "" || temp2.close != ""){
				temp.push(temp2);
			}
			$(this).remove();
		});
		store_time.val(JSON.stringify(temp));
		temp = { address : address.val() };
		if(themify_SL_map_marker.post_marker){
			temp['position'] = themify_SL_map_marker.latlng_from_marker(themify_SL_map_marker.post_marker.marker);
		}
		address.val(JSON.stringify(temp));
    }

	$( '#post' ).on( 'submit', update_address );

	var temp = JSON.parse(numbers.val());
	for(var i=0;i<temp.length;i++){
		let link = temp[i].link ? temp[i].link : '';
		themify_sl_add_num_row( { 'lable':temp[i].lable,'number':temp[i].number, 'link' : link } );
	}
	
	temp = JSON.parse(store_time.val());
	for(var i=0;i<temp.length;i++){
		themify_sl_add_time_row({'lable':temp[i].lable,'open':temp[i].open,'close':temp[i].close});
	}
	if(numbers.val() == '[]'){
		themify_sl_add_num_row( { 'lable':'', 'number':'', 'link' : '' });
	}
	if(store_time.val() == '[]'){
		themify_sl_add_time_row({'lable':'','open':'','close':''});
	}

	/** load map for Address field */
	$( '#themify_storelocator_address' ).css({'visibility':'hidden'});
	function MapCallback(){
		var address = $( '#themify_storelocator_address' );
		if ( ! address.length ) {
			return;
		}

		address.css({'visibility':'visible'});
		let temp;
		try {
			temp = JSON.parse( address.val() );
		}
		catch( error ) {
			temp = { 'address' : '' };
		}
		address.val( temp.address );
		if ( typeof temp.position === 'object' ) {
			var temp2 = {'position':temp.position,'title':'Store Location'};
			temp = {lat: temp.position.lat, lng: temp.position.lng};
		} else {
			temp = {lat: 35.8799875, lng: 76.5151001};
		}
		themify_SL_map_marker.init({
			map_container: $('.themify_storelocator_map').get(0),
			settings: {
						'map_load':{
							center:{lat: temp.lat, lng: temp.lng},
							zoom:8,
							mapTypeId:'roadmap'
							},
						'map_width':'50%',
						'map_height':'200px'
						},
			markers: [],
			suggestion: false
		});
		if(typeof temp2 === 'object'){
			themify_SL_map_marker.post_marker = themify_SL_map_marker.add_marker(temp2);
		}
		address.on('blur',function(){
			address = $(this).val();
			themify_SL_map_marker.admin_post_marker(address);
		});
	}

	if ( typeof google !== 'object' || typeof google.maps !== 'object' ) {
		$.getScript( '//maps.googleapis.com/maps/api/js?key=' + ThemifyStoreLocator.map_key, function() {
			MapCallback();
		} );
	} else {
		MapCallback();
	}
})(jQuery, window, document);
