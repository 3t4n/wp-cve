jQuery(function ($) {

	function UpdateWording(){
	 	if ($('.ostype').val() == 'collection') {
		    $('.osid').html('Collection Slug');
		    $('.osidcaption').html('Please put your collection slug.');
		} else {
		    $('.osid').html('Wallet Address');
		    $('.osidcaption').html('Please put your wallet address.');
		}
	}

  	$(document).ready(function() {
  		UpdateWording();
		$('.ostype').on('change', function() {
		    UpdateWording();
		});
  });
});