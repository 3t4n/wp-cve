(function($) {

window.addEventListener('load', function()
{
	var divs = jQuery( '.hcj-tell-listen' );
	divs.each( function(index){
		$this = jQuery(this);

		var $tell = $this.find('.hcj-tell').find('input[type=checkbox]');
		var $listen = $this.find('.hcj-listen');

		$tell.on( 'change', function(e)
		{
			$tell.prop('checked') ? $listen.show() : $listen.hide();
		});
		$tell.trigger('change');
	});
});

}());