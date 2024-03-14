//IMPORTANT: This need a fix on the isotope file to work in the elementor preview. See https://github.com/elementor/elementor/issues/6756
/*You need to comment the following files on the isotope file:
// check that elem is an actual element
    /*if ( !( elem instanceof HTMLElement ) ) {
      return;
}*/
function startElemenfolio(){   

    jQuery('#elementor-preview-iframe').contents().find('.elpt-portfolio-content-isotope').imagesLoaded( function() {
        //Masonry
        var $grid = jQuery('#elementor-preview-iframe').contents().find('.elpt-portfolio-content-isotope').isotope({
            itemSelector: '.portfolio-item-wrapper',
            layoutMode: 'masonry',     
        });  
        //Packery
        var $packery = jQuery('#elementor-preview-iframe').contents().find('.elpt-portfolio-content-packery').isotope({
            layoutMode: 'packery',            
            itemSelector: '.portfolio-item-wrapper'
        });

        /*
        * Paginated Isotope
        */
        var itemSelector = ".portfolio-item-wrapper"; 

        var $container = jQuery('#elementor-preview-iframe').contents().find('.elpt-portfolio-content-isotope-pro').isotope({
            layoutMode: 'masonry',
            itemSelector: itemSelector
        });

        $container.imagesLoaded().progress( function() {
            $container.isotope('layout');
        });
     
        // Pagination Variables
        var itemsPerPageDefault = jQuery('#elementor-preview-iframe').contents().find('#powerfolio_pagination_postsperpage').val();
        if (itemsPerPageDefault === undefined) {
            itemsPerPageDefault = 10;
        }

        var itemsPerPage = defineItemsPerPage();
        var currentNumberPages = 1;
        var currentPage = 1;
        var currentFilter = '*';
        var filterAtribute = 'data-filter';
        var pageAtribute = 'data-page';
        var pagerClass = 'isotope-pager';
 
         //Restore on window resize
         jQuery(window).resize(function(){
             changeFilter(itemSelector);
         })
 
         // update items based on current filters    
         function changeFilter(selector) { 
             $container.isotope({ filter: selector }
         ); }
 
 
         function getFilterSelector() {
             var selector = itemSelector;
             if (currentFilter != '*') {
               selector += currentFilter;
             }
             //console.log(selector);
             return selector;
         }
 
         function goToPage(n) {
             currentPage = n;
     
             var selector = getFilterSelector();
             selector += `[${pageAtribute}="${currentPage}"]`;
     
             changeFilter(selector);
         }
     
         function defineItemsPerPage() {
             var pages = itemsPerPageDefault;
     
             return pages;
         }
         
         function setPagination() {
             var SettingsPagesOnItems = function() {
                 var itemsLength = $container.children(itemSelector).length;
                 var pages = Math.ceil(itemsLength / itemsPerPage);
                 var item = 1;
                 var page = 1;
                 var selector = getFilterSelector();
         
                 $container.children(selector).each(function(){
                     if (item > itemsPerPage) {
                         page++;
                         item = 1;
                     }
                     jQuery(this).attr(pageAtribute, page);
                     item++;
                 });
         
                 currentNumberPages = page;
             }();
         
             var CreatePagers = function() {
                 var $isotopePager = jQuery('#elementor-preview-iframe').contents().find('.' + pagerClass);
                 if ($isotopePager.length === 0) {
                     $isotopePager = jQuery('<div class="' + pagerClass + '"></div>');
                     $container.after($isotopePager);
                 } else {
                     $isotopePager.html(''); // Clear existing pagination
                 }
         
                 for (var i = 0; i < currentNumberPages; i++) {
                     var $pager = jQuery('<a href="javascript:void(0);" class="pager" ' + pageAtribute + '="' + (i + 1) + '"></a>');
                     $pager.html(i + 1);
         
                     $pager.click(function() {
                         //var page = jQuery(this).eq(0).attr(pageAtribute);
                         //goToPage(page);
                     });
         
                     $pager.appendTo($isotopePager);
                 }
             }();
         }
         
     
         setPagination();
         goToPage(1);
    });                
}

jQuery(window).on('load', function(){
    elementorFrontend.hooks.addAction('frontend/element_ready/widget', function($scope){
        startElemenfolio();        
    });

    setInterval(function() {	
        console.log(gridSettings.itemsPerPageDefault);
		startElemenfolio(); 
	}, 1000);
});