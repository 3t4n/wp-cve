jQuery( document ).ready(
	function($){

		// Category Lists
		jQuery( 'body' ).on(
			'change',
			'.brm-taxonomy-selector',
			function($)
			{
					da           = jQuery( this ).attr( 'id' );
					suffix       = da.match( /\d+/ );
					var taxonomy = jQuery( this ).val();
					var ran      = jQuery( '#brm-terms-ran' ).val();

					jQuery.ajax(
						{
							url: ajaxurl,
							data:{
								'action': 'brm_recipe_tax_terms',
								'taxonomy': taxonomy,
								'random': ran,
							},
							dataType: 'html',
							context: this,
							type: 'POST',
							success:function(response){
								jQuery( this ).parent().siblings( 'p' ).find( '.brm-terms-holder' ).html( response ).find( '.brm-cat-select' ).attr( 'name','widget-brm_recipe_categories[' + suffix + '][categories][]' );
							},
							error: function(errorThrown){
								alert( 'Error...' );
							}
						}
					);
			}
		);

		// Category Lists
		jQuery( 'body' ).on(
			'change',
			'.brm-cat-taxonomy-selector',
			function($)
			{
					da           = jQuery( this ).attr( 'id' );
					suffix       = da.match( /\d+/ );
					var taxonomy = jQuery( this ).val();
					var ran      = jQuery( '#brm-cat-terms-ran' ).val();

					jQuery.ajax(
						{
							url: ajaxurl,
							data:{
								'action': 'brm_recipe_slider_tax_terms',
								'taxonomy': taxonomy,
								'random': ran,
							},
							dataType: 'html',
							context: this,
							type: 'POST',
							success:function(response){
								jQuery( this ).parent().siblings( 'p' ).find( '.brm-cat-terms-holder' ).html( response ).find( '.brm-terms-select' ).attr( 'name','widget-brm_recipe_categories_slider[' + suffix + '][category]' );
							},
							error: function(errorThrown){
								alert( 'Error...' );
							}
						}
					);
			}
		);

		// jQuery('select.brm-cat-select[multiple] option').mousedown(function(){
		// jQuery(this).toggleClass('selected');

		// jQuery(this).prop('selected', !jQuery(this).prop('selected'));
		// jQuery(this).trigger('change');
		// return false;

		// });
	}
);
function calcTotalTime()
{

	var prep_time  = jQuery( '#br_recipe_prep_time' ).val();
	var cook_time  = jQuery( '#br_recipe_cook_time' ).val();
	var total_time = Number( prep_time ) + Number( cook_time );
	jQuery( '#br_recipe_total_time' ).val( total_time );

}
