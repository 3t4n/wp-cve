/** Sibs function to show feedback pop up
 *
 * @package Sibs/js
 */

window.ATL_JQ_PAGE_PROPS = {
	"triggerFunction": function( showCollectorDialog ) {
		// Requires that jQuery is available!
		jQuery( "#feedback-button" ).click(
			function( e ) {
					e.preventDefault();
					showCollectorDialog();
			}
		);
	}
};
