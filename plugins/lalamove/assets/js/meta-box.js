jQuery(
	function ($) {
		var widget_items = {
			init: function () {
				$( '#wc-llm-widget' )
				.on( 'click', 'button.button-send-with', this.lalamove );
			},
			lalamove: function () {
				var wc_order_id = woocommerce_admin_meta_boxes.post_id;
				if (lalamove_order_status == null || lalamove_order_status_uncompleted_list.indexOf( lalamove_order_status ) >= 0) {
					window.location.href = store_admin_url + "?page=Lalamove&sub-page=place-order&id=" + wc_order_id;
				} else {
					window.open( lalamove_web_app_url + "/orders/" + lalamove_order_id, '_blank' );
				}
			}
		};
		widget_items.init();
	}
);
