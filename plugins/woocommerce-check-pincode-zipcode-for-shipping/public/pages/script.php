<script>
	jQuery(document).on('click', '.single_add_to_cart_button', function(){
		let pincode = jQuery("#phoeniixx-pincode-input").val();
		let cookie_pincode = "<?= $_COOKIE['phoeniixx-pincode-zipcode'] ?>";
		if(pincode == '' || cookie_pincode == '' || pincode != cookie_pincode){
			jQuery("#phoeniixx-pincode-message").empty().text("<?= $this->setting::get()['pincode_verify_error']?>");
			return false;
		}
	});
</script>