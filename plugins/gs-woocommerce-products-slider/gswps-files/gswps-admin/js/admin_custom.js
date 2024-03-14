function copyToClipboard(text, el) {
  var copyTest = document.queryCommandSupported('copy');
  var elOriginalText = el.attr('data-original-title');

  if (copyTest === true) {
    var copyTextArea = document.createElement("textarea");
    copyTextArea.value = text;
    document.body.appendChild(copyTextArea);
    copyTextArea.select();
    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'Copied!' : 'Whoops, not copied!';
      el.attr('data-original-title', msg).tooltip('show');
    } catch (err) {
      console.log('Oops, unable to copy');
    }
    document.body.removeChild(copyTextArea);
    el.attr('data-original-title', elOriginalText);
  } else {
    // Fallback if browser doesn't support .execCommand('copy')
    window.prompt("Copy to clipboard: Ctrl+C or Command+C, Enter", text);
  }
}


jQuery(document).ready(function($) {
  // Initialize
  // ---------------------------------------------------------------------


  // Copy to clipboard
  // Grab any text in the attribute 'data-copy' and pass it to the 
  // copy function
  $('.js-copy').click(function(e) {
    var text = $("#copy_id").html();
    var el = $(this);
    copyToClipboard(text, el);
     e.preventDefault(); 
  });
});


jQuery(function($){
	var product_type= $('#gs_product_type');
	if(product_type.val()!==('custom_select_tag' ||'custom_select_product' || 'custom_select_sku' )){
		$('#gs_tag').hide();
		$('#gs_products').hide();
		$('#gs_cat').hide();
        $('#gs_sku').hide();
        $('#gs_attr').hide();
	}
	$('#gs_product_category_type').change(function(){
		if ($(this).val() == 'select_category' )
        {
        	//console.log($(this).val());
             $('#gs_cat').show();
             
        }
        if ($(this).val() == 'all_category' )
        {
        	//console.log($(this).val());
             $('#gs_cat').hide();
             
        }
	 });

    // if($('#gs_product_category_type').children("option").filter(":selected").val()=='all_category'){

    //  $('#gs_cat').hide();
    // }
    if($('#gs_product_category_type').children("option").filter(":selected").val()=='select_category'){

        $('#gs_cat').show();
    }
	// simple multiple select
	$('#gswps_select2_tags').select2();

	
    $('#gswps_select2_cats').select2();
 	$('#gswps_select2_cats_exclude').select2();
    $('#gswps_select2_sku').select2();

	// multiple select with AJAX search
	$('#gswps_select2_posts').select2();

	product_type.change(function(){
		$('#gs_tag').hide();
		$('#gs_products').hide();
        $('#gs_sku').hide();
        $('#gs_attr').hide();
        
        if ($(this).val() == 'custom_select_tag' )
        {
        	
             $('#gs_tag').show();
             $('#gs_products').hide();
             $('#gs_sku').hide();
             $('#gs_attr').hide();
        }
        if ($(this).val() == 'custom_select_product')
        {
             $('#gs_products').show();
             $('#gs_tag').hide();
             $('#gs_sku').hide();
             $('#gs_attr').hide();
        }
        if ($(this).val() == 'custom_select_sku')
        {
             $('#gs_sku').show();
             $('#gs_tag').hide();
             $('#gs_products').hide();
             $('#gs_attr').hide();

        }
        if ($(this).val() == 'custom_select_attr')
        {
             $('#gs_sku').hide();
             $('#gs_tag').hide();
             $('#gs_products').hide();
             $('#gs_attr').show();

        }
    });

    var sel_value = product_type.children("option").filter(":selected").val();
    if(sel_value =='custom_select_tag'){

    	 $('#gs_tag').show();
        $('#gs_products').hide();
         $('#gs_sku').hide();
        $('#gs_attr').hide();


    }

    if(sel_value =='custom_select_product'){
    	$('#gs_products').show();
        $('#gs_tag').hide();
        $('#gs_sku').hide();
        $('#gs_attr').hide();


    }
    if(sel_value =='custom_select_sku'){
    	 $('#gs_sku').show();
         $('#gs_tag').hide();
         $('#gs_products').hide();
         $('#gs_attr').hide();
    }

    if(sel_value =='custom_select_attr'){
         $('#gs_sku').hide();
         $('#gs_attr').show();
         $('#gs_tag').hide();
         $('#gs_products').hide();
    }


    




});
