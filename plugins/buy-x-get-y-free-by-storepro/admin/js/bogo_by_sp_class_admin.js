(function ($) {
	'use strict';

/* JQuery for free product */

		function sp_free_bogo_product() {
		this.init = function () {
			this.applySelectProduct("#sp_bogo_product_free");
		}

		this.applySelectProduct = function (id) {

			jQuery(id).selectWoo({
				placeholder: "Select a product",
				allowClear: true,
				ajax: {
					url: ajaxurl,
					dataType: 'json',
					type: "GET",
					delay: 1000,
					data: function (params) {
						return {
							keyword: params.term,
							action: "Sp_Bogo_Free_Product",
						};
					},
					processResults: function (data) {
						return {
							results: data
						};

					},
				}
			});

		}
	}

	//Product 2
	function sp_free_bogo_product_2() {
		this.init = function () {
			this.applySelectProduct("#sp_bogo_product_2_free");
		}

		this.applySelectProduct = function (id) {

			jQuery(id).selectWoo({
				placeholder: "Select a product",
				allowClear: true,
				ajax: {
					url: ajaxurl,
					dataType: 'json',
					type: "GET",
					delay: 1000,
					data: function (params) {
						return {
							keyword: params.term,
							action: "Sp_Bogo_Free_Product",
						};
					},
					processResults: function (data) {
						return {
							results: data
						};

					},
				}
			});

		}
	}

	//Product 3
	function sp_free_bogo_product_3() {
		this.init = function () {
			this.applySelectProduct("#sp_bogo_product_3_free");
		}

		this.applySelectProduct = function (id) {

			jQuery(id).selectWoo({
				placeholder: "Select a product",
				allowClear: true,
				ajax: {
					url: ajaxurl,
					dataType: 'json',
					type: "GET",
					delay: 1000,
					data: function (params) {
						return {
							keyword: params.term,
							action: "Sp_Bogo_Free_Product",
						};
					},
					processResults: function (data) {
						return {
							results: data
						};

					},
				}
			});

		}
	}
	
		jQuery(function ($) {
		var sp_free_bogo_product_obj = new sp_free_bogo_product();
		sp_free_bogo_product_obj.init();

		var sp_free_bogo_product_obj_2 = new sp_free_bogo_product_2();
		sp_free_bogo_product_obj_2.init();

		var sp_free_bogo_product_obj_3 = new sp_free_bogo_product_3();
		sp_free_bogo_product_obj_3.init();
	});			
	
	
	function sp_buy_bogo_product() {
		this.init = function () {
			this.applySelectProduct("#sp_bogo_product_buy");
		}

		this.applySelectProduct = function (id) {

			jQuery(id).selectWoo({
				placeholder: "Select a product",
				allowClear: true,
				ajax: {
					url: ajaxurl,
					dataType: 'json',
					type: "GET",
					delay: 1000,
					data: function (params) {
						return {
							keyword: params.term,
							action: "Sp_Bogo_Buy_Product",
						};
					},
					processResults: function (data) {
						return {
							results: data
						};

					},
				}
			});

		}
	}
	
	//Product 2
	function sp_buy_bogo_product_2() {
		this.init = function () {
			this.applySelectProduct("#sp_bogo_product_2_buy");
		}

		this.applySelectProduct = function (id) {

			jQuery(id).selectWoo({
				placeholder: "Select a product",
				allowClear: true,
				ajax: {
					url: ajaxurl,
					dataType: 'json',
					type: "GET",
					delay: 1000,
					data: function (params) {
						return {
							keyword: params.term,
							action: "Sp_Bogo_Buy_Product",
						};
					},
					processResults: function (data) {
						return {
							results: data
						};

					},
				}
			});

		}
	}

	//Product3
	function sp_buy_bogo_product_3() {
		this.init = function () {
			this.applySelectProduct("#sp_bogo_product_3_buy");
		}

		this.applySelectProduct = function (id) {

			jQuery(id).selectWoo({
				placeholder: "Select a product",
				allowClear: true,
				ajax: {
					url: ajaxurl,
					dataType: 'json',
					type: "GET",
					delay: 1000,
					data: function (params) {
						return {
							keyword: params.term,
							action: "Sp_Bogo_Buy_Product",
						};
					},
					processResults: function (data) {
						return {
							results: data
						};

					},
				}
			});

		}
	}



		jQuery(function ($) {
		var sp_buy_bogo_product_obj = new sp_buy_bogo_product();
		sp_buy_bogo_product_obj.init();

		var sp_buy_bogo_product_obj_2 = new sp_buy_bogo_product_2();
		sp_buy_bogo_product_obj_2.init();

		var sp_buy_bogo_product_obj_3 = new sp_buy_bogo_product_3();
		sp_buy_bogo_product_obj_3.init();
	});	

})(jQuery);

