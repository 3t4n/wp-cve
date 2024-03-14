jQuery(document).ready(()=>{
    var url = "https://www.vbt.io/Regions/Countries";
		var check = 0;
		jQuery.getJSON(url, function(jd) {
			if(check == 0){
				countries = jd.data;
				for(var i = 0 ; i<jd.data.length; i++) {
					jQuery('select[data-type="countries"]').append( '<option data-id="' + jd.data[i][0] + '" value="'+jd.data[i][1]+'" code="'+ jd.data[i][2] +'">'+jd.data[i][1]+'</option>' );
				}
			}
			check++;                              
		});

		jQuery('select[data-type="countries"]').change(function() {    
			var formy= jQuery(this).closest("form");
			var countryID = jQuery(this).find('option:selected').data("id");
			if(formy.find('select[data-type="states"]').length != 0){
				var url = "https://www.vbt.io/Regions/States/" + countryID;
				jQuery.getJSON(url, function(jd) {
					formy.find('select[data-type="states"]').find('option').not(':first').remove()
					for(var i = 0 ; i<jd.data.length; i++) {
						formy.find('select[data-type="states"]').append( '<option data-id="' + jd.data[i][0] + '" value="'+jd.data[i][1]+'" code="'+ jd.data[i][2] +'">'+jd.data[i][1]+'</option>' );
					}
				});
			}
		});

		jQuery('select[data-type="states"]').change(function() {    
			var formy= jQuery(this).closest("form");
			var stateID = jQuery(this).find('option:selected').data("id");
			if(formy.find('select[data-type="cities"]').length != 0){
				var url = "https://www.vbt.io/Regions/Cities/" + stateID;
				jQuery.getJSON(url, function(jd) {
					formy.find('select[data-type="cities"]').find('option').not(':first').remove()
					for(var i = 0 ; i<jd.data.length; i++) {
						formy.find('select[data-type="cities"]').append( '<option data-id="' + jd.data[i][0] + '" value="'+jd.data[i][1]+'" code="'+ jd.data[i][2] +'">'+jd.data[i][1]+'</option>' );
					}
				});
			}
		});
})