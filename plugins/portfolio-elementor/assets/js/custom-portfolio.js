jQuery(window).on('load', function () {


    // Aqui eu consigo alternar entre a versao paginada ou normal, apenas pela classe
    // vamos deixar tudo no mesmo arquivo, e mudar a classe atraves do elementor.
    // vamos precisar passar algumas informacoes para o JS (numero de paginas, etc)

    // Talvez de para deixar o packery etc tudo na mesma variavel, porque o packery tambem pode ser paginado

    if ( jQuery( ".elpt-portfolio-content" ).length ) {
        //Isotope Layout
        var $grid = jQuery('.elpt-portfolio-content-isotope').isotope({
            //layoutMode: 'packery',
            layoutMode: 'masonry',
            itemSelector: '.portfolio-item-wrapper'
        });
        
        $grid.imagesLoaded().progress( function() {
            $grid.isotope('layout');
        });

        //Packery Layout
        var $packery = jQuery('.elpt-portfolio-content-packery').isotope({
            layoutMode: 'packery',            
            itemSelector: '.portfolio-item-wrapper'
        });

        $packery.imagesLoaded().progress( function() {
            $packery.isotope('layout');
        });

        /*
        * Paginated Isotope
        */
        //https://codepen.io/TimRizzo/details/ervrRq
        //https://codepen.io/Igorxp5/pen/ojJLQE

        var itemSelector = ".portfolio-item-wrapper"; 

        var $container = jQuery('.elpt-portfolio-content-isotope-pro').isotope({
            layoutMode: 'masonry',
            itemSelector: itemSelector
        });

        $container.imagesLoaded().progress( function() {
            $container.isotope('layout');
        });
    
        // Pagination Variables
        var itemsPerPageDefault = 10; // Default value

        if (typeof gridSettings !== 'undefined' && gridSettings.itemsPerPageDefault !== undefined) {
            itemsPerPageDefault = gridSettings.itemsPerPageDefault;
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
            console.log(selector);
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
    
            var SettingsPagesOnItems = function(){
    
                var itemsLength = $container.children(itemSelector).length;
                
                var pages = Math.ceil(itemsLength / itemsPerPage);
                var item = 1;
                var page = 1;
                var selector = getFilterSelector();
                
                $container.children(selector).each(function(){
                    if( item > itemsPerPage ) {
                        page++;
                        item = 1;
                    }
                    jQuery(this).attr(pageAtribute, page);
                    item++;
                });
    
                currentNumberPages = page;
    
            }();
    
            var CreatePagers = function() {
    
                var $isotopePager = ( jQuery('.'+pagerClass).length == 0 ) ? jQuery('<div class="'+pagerClass+'"></div>') : jQuery('.'+pagerClass);
    
                $isotopePager.html('');
                
                for( var i = 0; i < currentNumberPages; i++ ) {
                    var $pager = jQuery('<a href="javascript:void(0);" class="pager" '+pageAtribute+'="'+(i+1)+'"></a>');
                        $pager.html(i+1);
                        
                        $pager.click(function(){
                            jQuery('.isotope-pager .active').removeClass('active');
                            jQuery(this).addClass('active');
                            var page = jQuery(this).eq(0).attr(pageAtribute);
                            goToPage(page);
                        });
    
                    $pager.appendTo($isotopePager);
                }
    
                $container.after($isotopePager);
    
            }();
    
        }
    
        setPagination();
        goToPage(1);


        // On Click Actions
        jQuery('.elpt-portfolio-filter').on('click', 'button', function () {   
            jQuery('.elpt-portfolio-filter button').removeClass('item-active');
            jQuery(this).addClass('item-active');

            var filterValue = jQuery(this).attr(filterAtribute);
            var filter = filterValue;
            currentFilter = filter;

            $grid.isotope({
                filter: filterValue
            });
            $packery.isotope({
                filter: filterValue
            });

            setPagination();
            goToPage(1);
        });       
        
    }

});