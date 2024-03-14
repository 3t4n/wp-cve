jQuery(function ($) {
 
 
 $('.dafe-portfolio-container').each( function() {
		
		var $this = $( this );
		var $portfolioContainer = $this.find('.isotope-list');
		 $portfolioContainer.isotope({ 
			itemSelector : '.item', 
			layoutMode: 'fitRows'
  
		});
		
		var $categories = $this.find('.filters');
		$categoryLinks = $categories.find('a');
 
		$categoryLinks.click(function(){
		var $this = $(this);

		if ( $this.hasClass('selected') ) {
			return false;
		}
 
		$categories.find('.selected').removeClass('selected');
		$this.addClass('selected');
 
		var selector = $(this).attr('data-filter');
	
		$portfolioContainer.isotope({ filter: selector });
 
		return false;
 
		});
 
	});
 });