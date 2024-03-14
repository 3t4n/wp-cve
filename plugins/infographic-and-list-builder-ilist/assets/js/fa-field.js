jQuery(document).ready(function($) {
	
	$( '.ilist_fa_icon > .cmb-td > input' ).on( 'click', function(e) {
		e.preventDefault();
		
		
		
		$('#fa-field-modal').show();
		$("#fa-field-modal").attr("data", this.id);
	});

	$( '.fa-field-modal-close' ).on( 'click', function() {
		$('#fa-field-modal').removeAttr("data");
		$('#fa-field-modal').hide();

	});

	$( '.fa-field-modal-icon-holder' ).on( 'click', function() {

		
	});

	$("#id_search").quicksearch("div.fa-field-modal-icons div.qcld_ilist_fa_section div.fa-field-modal-icon-holder", {
		noResults: '#noresults',
		stripeRows: ['odd', 'even'],
		loader: 'span.loading',
		minValLength: 2
	});
	
		$("#id_search").quicksearch("div.fa-field-modal-icons div.qcld_ilist_fa_section", {
		noResults: '#noresults',
		stripeRows: ['odd', 'even'],
		loader: 'span.loading',
		minValLength: 2
	});

});

function showfamodal(data){
	
	document.getElementById('fa-field-modal').style.display = 'block';
	document.getElementById('fa-field-modal').setAttribute("data", data.id);
	//jQuery.('#fa-field-modal').show();
}