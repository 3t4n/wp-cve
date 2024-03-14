jQuery( document ).ready(
	function($){
		$( '#eos-dp-wrp input[type="checkbox"]' ).on(
			'click',
			function(){
				var opts = {};
				$( '#fdp-roles,#fdp-administrators' ).each(
					function(k,table){
						var rowsObj = {};
						$( table ).find( 'tbody tr' ).each(
							function(r,tr){
								var chksObj = {};
								$( this ).find( 'input[type="checkbox"]' ).each(
									function(c,chk){
										chksObj[c] = chk.checked;
									}
								);
								rowsObj[tr.dataset.opt_name] = chksObj;
							}
						);
						opts[table.dataset.opt_name] = rowsObj;
					}
				);
				$( '#fdp-roles-manager' ).val( JSON.stringify( opts ) );
			}
		);
	}
);
