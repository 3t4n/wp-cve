jQuery(document).ready(function($) {

jQuery( document ).on( 'postboxes-columnchange',function(){

        //To allow widgets to redraw themselves , if needed.
        // For example, the fast tagger widgets need to be  redrawn when the layout
        // changes from single column to 2 column
        jQuery(window).trigger('resize');

    } )


});

/*

function showonlyone(selected) {

     jQuery('.tabs').each(function(index) {

		 //alert(jQuery(this).attr("id"));

          if (jQuery(this).attr("id") == selected) {

               jQuery(this).show();

          }else {

               jQuery(this).hide();

          }

     });

	return false;

}

*/
