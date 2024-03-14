jQuery(document).ready(function($){
    $('.color-picker').iris();
    $('.qbook-color-picker input').iris();
    toggleButtonText();
});


function toggleButtonText() {
	if(jQuery('.custom-widget-type select').val() == 'button') {
		jQuery('.multi-field').addClass('hideme');
		jQuery('.multi-only').addClass('hideme');
		jQuery('.button-field').removeClass('hideme');
	} 

	else if(jQuery('.custom-widget-type select').val() == 'calendar') {
		jQuery('.button-field').addClass('hideme');
	}

	else if(jQuery('.custom-widget-type select').val() == 'multi') {
		jQuery('.button-field').addClass('hideme');
		jQuery('.multi-field').removeClass('hideme');
	}

	else if(jQuery('input[name="quandoo-reservation-standard-fields[advanced-settings]"]').is(':checked')) {
		jQuery('.hideble').removeClass('hideme');
	} 

	else if(!jQuery('input[name="quandoo-reservation-standard-fields[advanced-settings]"]').is(':checked')) {
		jQuery('.hideble').addClass('hideme');
	}
}

jQuery('.custom-widget-type select').change(function() {
	toggleButtonText();
});

jQuery('input[name="quandoo-reservation-standard-fields[advanced-settings]"]').change(function() {
	toggleButtonText();
})



jQuery('input[name="quandoo-reservation-standard-fields[advanced-settings]"]').is(':checked');



//multi-widget
var formattedHTMLres;
var formattedNewHTMLres;
var currentIndex;
var newRes;

var HTMLres = `<p id="qRestaurant-%%INDEX%%">
	<input onkeyup="updateResList(%%INDEX%%, 'name')" 
		type="text" name="multiRes-name-%%INDEX%%" 
		id="multiRes-name-%%INDEX%%" value="%%VALUENAME%%"
		placeholder="Trattoria - Downtown" />
	<input onkeyup="updateResList(%%INDEX%%, 'bcid')" 
		type="text" name="multiRes-bcid-%%INDEX%%" 
		id="multiRes-bcid-%%INDEX%%" value="%%VALUEBCID%%"
		placeholder="zTevcdfse87SJD-00cy7534DDF" />
		<span class="dashicons dashicons-no" onclick="removeRes(%%INDEX%%)"></span>
</p>`;

if(jQuery('input[name="multi"]').val()) {
	var resArr = JSON.parse(jQuery('input[name="multi"]').val());
}

jQuery(document).ready(function($) {

jQuery('#qAddRestaurant').click(function(e){
	e.preventDefault();

	currentIndex = resArr.length;

	formattedNewHTMLres = HTMLres.replace('%%INDEX%%', currentIndex)
					.replace('%%INDEX%%', currentIndex)
					.replace('%%INDEX%%', currentIndex)
					.replace('%%INDEX%%', currentIndex)
					.replace('%%INDEX%%', currentIndex)
					.replace('%%INDEX%%', currentIndex)
					.replace('%%INDEX%%', currentIndex)
					.replace('%%INDEX%%', currentIndex)
					.replace('%%VALUENAME%%', '')
					.replace('%%VALUEBCID%%', '');

	jQuery('.multi-reservation-key > td').append(formattedNewHTMLres);

	newRes = {
		'name': jQuery('input[name="multiRes-name-'+currentIndex+'"]').val(),
		'bcid': jQuery('input[name="multiRes-bcid-'+currentIndex+'"]').val()
	}

	resArr.push(newRes);

	jQuery('input[name="multi"]').val(JSON.stringify(resArr));

	currentIndex++;
})

if(jQuery('input[name="multi"]').val()) {
	for(var i=0; i<resArr.length; i++) {
		formattedHTMLres = HTMLres.replace('%%INDEX%%', i)
							.replace('%%INDEX%%', i)
							.replace('%%INDEX%%', i)
							.replace('%%INDEX%%', i)
							.replace('%%INDEX%%', i)
							.replace('%%INDEX%%', i)
							.replace('%%INDEX%%', i)
							.replace('%%INDEX%%', i)
							.replace('%%VALUENAME%%', resArr[i].name)
							.replace('%%VALUEBCID%%', resArr[i].bcid);

		jQuery('.multi-reservation-key > td').append(formattedHTMLres);
	}
}

});

function updateResList(index, targetInput) {
	var inputVal = jQuery('input[name="multiRes-'+targetInput+'-'+index+'"]').val();

	if(targetInput == 'name') {
		resArr[index].name = jQuery('input[name="multiRes-'+targetInput+'-'+index+'"]').val();
	} else if (targetInput == 'bcid') {
		resArr[index].bcid = jQuery('input[name="multiRes-'+targetInput+'-'+index+'"]').val();
	}

	jQuery('input[name="multi"]').val(JSON.stringify(resArr));
	
}

function removeRes(index) {
	resArr.pop(resArr[index]);
	jQuery('input[name="multi"]').val(JSON.stringify(resArr));
	jQuery('#qRestaurant-'+index).remove();
}