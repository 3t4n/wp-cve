/* global ppqp_params */

;(function ( $, window, document, undefined ) {
	var ppqp = function( $form ) {
		this.$form                	= $form;
		this.$template 				= wp.template( 'ppqp-price-template' );
		this.$price_holder 			= $('.ppqp-price-holder');
		this.product_total 			= null;
		this.current_product 		= 0;
		
		$form.on( 'input.preview_price', '[name="quantity"]', 	{ppqp: this}, this.onChange );
		$form.on( 'click', '.quantity .plus,.quantity .minus', 	function(){
			setTimeout(function(){
				$form.find( '[name="quantity"]' ).trigger('input.preview_price');
			},100)
		} );
	};
	
	ppqp.prototype.calculatePrice = function ( event ) {
		this.product_total = this.qty * ppqp_params.price;
	};
	
	ppqp.prototype.updatePrice_html = function ( event ) {
		var ppqp = this,
			$template_html = '';
		if ( ppqp.product_total != null ) {
			try {
				$template_html = ppqp.$template( {
					price:   	ppqp.product_total.formatMoney(
									ppqp_params.precision,
									ppqp_params.decimal_separator,
									ppqp_params.thousand_separator
								),
					currency: 	ppqp_params.currency
				} );
			} catch (err) {
				$template_html = '<p style="color:red;">Something is wrong with your price-preview.php template.</p>';
			}
			$template_html = $template_html.replace( '/*<![CDATA[*/', '' );
			$template_html = $template_html.replace( '/*]]>*/', '' );
			ppqp.$price_holder.html( $template_html );
		}
		
	};
	
	ppqp.prototype.onChange = function ( event ) {
		event.data.ppqp.qty = this.value;
		event.data.ppqp.calculatePrice();
		event.data.ppqp.updatePrice_html();
	};
	
	
	$(function() {
		if ( typeof ppqp_params !== 'undefined' ) {
			$( '.woocommerce div.product form.cart' ).each( function() {
				$( this ).ppqp();
			});
		}
	});
	
	$.fn.ppqp = function() {
		new ppqp( this );
		return this;
	};
	
	Number.prototype.formatMoney = function(c, d, t){
	var n = this, 
		c = isNaN(c = Math.abs(c)) ? 2 : c, 
		d = d == undefined ? "." : d, 
		t = t == undefined ? "," : t, 
		s = n < 0 ? "-" : "", 
		i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
		j = (j = i.length) > 3 ? j % 3 : 0;
		return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	};
	
})( jQuery, window, document );