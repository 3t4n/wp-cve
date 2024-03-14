/*-----------------------------
* Build Your Plugin JS / jQuery
-----------------------------*/
/*
Jquery Ready!
*/
jQuery( document ).ready(
	function($){
		"use strict";
		/*
		*   Add basic front-end page scripts here
		*/
		/*
		*   Simple jQuery Click
		*
		*   Add id="mySpecialButton" to any element and when
		*   clicked the same element will get the class "active".
		*
		*/
		$( '#mySpecialButton' ).click(
			function(){
				$( this ).addClass( 'active' );
			}
		);

		// End basic front-end scripts here
	}
);
