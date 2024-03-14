jQuery(document).ready(function(){    
 jQuery(".ldd-dropdown-toggle").dropdown();
 
 jQuery(".ldd-dropdown-toggle").click(function(e){
	 e.preventDefault();
	 
	 jQuery(".dropdown-menu").slideToggle()})
/*jQuery(function(){
    jQuery('.ldd_header_view').hover(
	function(){
		
            jQuery(this+ '.ldd_tooltip').show();
        },
        function(){
            jQuery(this+ '.ldd_tooltip').hide();   
        }
    )   
});
*/
 var container = document.querySelector('.masonry-cols3');
    //create empty var msnry
    var msnry;
    // initialize Masonry after all images have loaded
   
       new Masonry( container, {
            itemSelector: '.grid-item',
			columnWidth: 200
     
    });

    jQuery(".ldd_search .show_search").click(function(){

        if(jQuery(this).hasClass('fa-search-plus'))
        {
            jQuery(this).removeClass("fa-search-plus");
            jQuery(this).addClass("fa-search-minus");            

            jQuery(".ldd_main_search_box").css("display","block");
        }
        else
        {
            jQuery(this).removeClass("fa-search-minus");
            jQuery(this).addClass("fa-search-plus");

            jQuery(".ldd_main_search_box").css("display","none");
        }
    
    });

   


});



var heights = jQuery(".type-grid.grid-item").map(function ()
{
    return jQuery(this).height();
}).get(),

 maxHeight = Math.max.apply(null, heights);
 maxdiv = maxHeight+10;
//alert(maxHeight);

//jQuery(".js-isotope2 .grid-item").css("height",maxHeight+"px");
//Remove height attribute for 2 column
jQuery(".js-isotope2 .col-md-6").removeAttr("style");


jQuery(document).ready( function($) {
    $("#contact-form").isHappy({
        fields: {
            '#senders_name': {
                required: true,
                message: "Please enter a valid name",
                test: happy.minLength,
                arg: 3
            },
            '#email': {
                required: true,
                message: "Please enter a valid email address",
                test: happy.email
            },
            '#subject': {
                required: true,
                message: 'Please enter a valid subject',
                test: happy.minLength,
                arg: 6
            },
            '#message': {
                required: true,
                message: 'Please enter a longer message..',
                test: happy.minLength,
                arg: 10
            },
            
            
            
        }
    })
})



