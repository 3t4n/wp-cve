( function( window, wp ){

    // just to keep it cleaner - we refer to our link by id for speed of lookup on DOM.
    var link_id = 'spiceblocklib';

    // prepare our custom link's html.
    var link_html = '<a type="button" class="model-btn" id="' + link_id + '" onclick="spice_block_lib_open()"><img src="'+plugin1.pluginpath1 +'assets/images/btn-icon.svg" alt=""/><span>Spice Blocks Librery</span></a>';

     var link_id_exp = 'spiceblocksexport';

    // prepare our custom link's html.
    var link_html_exp = '<form method="post"><input type="hidden" id="Post_Id" name="Post_Id" value=""><button type="submit" id="' + link_id_exp + '" class="components-button" name="download_page" >Export Link</button></form>';

    // check if gutenberg's editor root element is present.
    var editorEl = document.getElementById( 'editor' );
    if( !editorEl ){ // do nothing if there's no gutenberg root element on page.
        return;
    }
    

    var unsubscribe = wp.data.subscribe( function () {
        setTimeout( function () {
            if ( !document.getElementById( link_id ) ) {
                var toolbalEl = editorEl.querySelector( '.edit-post-header__toolbar .edit-post-header-toolbar__left' );
                if( toolbalEl instanceof HTMLElement ){
                    toolbalEl.insertAdjacentHTML( 'beforeend', link_html );
                }
            }
            // if ( !document.getElementById( link_id_exp ) ) {
            //     var toolbalEl_exp = editorEl.querySelector( '.edit-post-header__toolbar' );
            //     if( toolbalEl_exp instanceof HTMLElement ){
            //         toolbalEl_exp.insertAdjacentHTML( 'afterend', link_html_exp );
            //     }
            // }
        }, 1 )
    } );
    
    // unsubscribe is a function - it's not used right now 
    // but in case you'll need to stop this link from being reappeared at any point you can just call unsubscribe();

} )( window, wp );
var modal = document.getElementById("spice-block-library");
var btn = document.getElementById("spiceblocklib");
var span = document.getElementsByClassName("close")[0];
function spice_block_lib_open(){//console.log('dev');
    jQuery("#spice-block-loader").hide();
       modal.style.visibility = "visible";
       modal.style.opacity = "1";
       modal.style.zIndex  = "9999";


};
function post_via_ajax01(url,name){
    var pva_ajax_url = pva_params.pva_ajax_url;
    const arr = name.split('-');
    const firstWord = arr[0];
    const lastWord = arr[arr.length - 1];
    var files = 'https://spiceblocks.com/starter-sites/'+firstWord+'/'+lastWord+'/'+name+'.json';
    //alert(files);
    var url01 = new URL(url );
    var postid = url01.searchParams.get("post");
    var rdurl=window.location.pathname;
    var updatepathname= rdurl.substring(0, rdurl.lastIndexOf('/'))+'/post.php?post='+postid+'&action=edit';    
    jQuery.ajax({
        type: 'POST',
        url: pva_ajax_url,
        data: {
            action: 'pva_create01',
            file_name:name,
            post_id: postid,
            file_path: files,
        },
        beforeSend: function ()
        {   
            jQuery("#spice-block-loader").show();
        },
        success: function(data)
        {   
            //alert(data);
            modal.style.visibility = "hidden";
            modal.style.opacity = "0";
            window.location.href=updatepathname;
            jQuery("#spice-block-loader").hide();

        },
        error: function()
        {
            alert("Import process failed");
            jQuery("#spice-block-loader").hide();
        }
    }) 


}
jQuery('#spiceblocksexport').click(function(){//alert('dev');
    var url01 = new URL(_wpMetaBoxUrl );
    var postid = url01.searchParams.get("post");console.log(postid); 
    jQuery('#Post_Id').setAttribute('value',postid);

});
var modal = document.getElementById("spice-block-library");
var btn = document.getElementById("spiceblocklib");
var span = document.getElementsByClassName("close")[0];
function spice_block_lib_open(){//console.log('dev');
       modal.style.visibility = "visible";
       modal.style.opacity = "1";
       modal.style.zIndex  = "9999";
};

jQuery(document).ready(function() {    
    
    //close the modal
    span.onclick = function() {
        modal.style.visibility = "hidden";
        modal.style.opacity = "0";
    }
    
    // js for switch buttons
    
    var starterPack = document.getElementById("starter-pack-btn");
    var demoPack = document.getElementById("demo-section-pack-btn");
    
    starterPack.onclick = function() {
        document.getElementById("starter-pack").style.display = "flex";
        document.getElementById("demo-section-pack").style.display = "none";
        demoPack.classList.remove("active");
        starterPack.classList.add("active");
        document.getElementById('addinnerpage').innerHTML = "";
    }
    
    demoPack.onclick = function() {
        document.getElementById("starter-pack").style.display = "none";
        document.getElementById("demo-section-pack").style.display = "flex";
        starterPack.classList.remove("active");
        demoPack.classList.add("active");
        document.getElementById('addinnerpage').innerHTML = "";
    }
    // js for switch buttons
    
    
    // ----------------------------------------------------------
     //Filter Block Sections
     // ----------------------------------------------------------
       var filterArray = [];
    
    // SETUP
    // ----------------------------------------------------------
    // Add a new array to filterArray for each filter-group
    // ----------------------------------------------------------
    jQuery('.spice-filter-group').each( function(index) {
        filterArray.push([]);
    });
    
    // CLICK
    // ----------------------------------------------------------
    jQuery('.spice-filter-group [data-filter-toggle]').on('click', function() {
        var filter = jQuery(this).attr('data-filter-toggle'),
            parent = jQuery(this).parent('.spice-filter-group'),
            parentIndex = jQuery('.spice-filter-group').index(parent);
        
        // Toggle button active state
        jQuery(this).toggleClass('is-active');
        
        // Modify filter array
        if (jQuery(this).hasClass('is-active')) {
            
            // Add an item to the filter array
            filterArray[parentIndex].push(filter);
        } else {
            
            // Remove an item from the filter array
            var i = filterArray[parentIndex].indexOf(filter);
            if (i != -1) {
                filterArray[parentIndex].splice(i, 1);
            }
        }
        
        // Render items with filtering
        render();
    });
    
    
    // RENDER
    // ----------------------------------------------------------
    function render() {
        var filterGroupsActive = countActiveFilterGroups(),
            filterGroupMatches,
            filtersShowing = [],
            itemTags;
        
        // If no active filters show everything
        if (filterGroupsActive === 0) {
            jQuery('#demo-section-pack .item').removeClass('is-hidden');
            return false;
        }

        // Loop through items and filter
        jQuery('#demo-section-pack .item').each(function(index) {
            itemTags = jQuery(this).attr('data-filter-tags').split(',');
            filterGroupMatches = 0;
            
            // Count item filter group matches
            for (i = 0; i < filterArray.length; i++) {
                if (findOne(filterArray[i], itemTags)) {
                    filterGroupMatches++;
                }
            }
            
            // If item matches all filter groups show it
            if (filterGroupMatches === filterGroupsActive) {
                jQuery(this).removeClass('is-hidden');
            } else {
                jQuery(this).addClass('is-hidden');
            }
            
            // If item showing add unique filters to filtersShowing
            if (! jQuery(this).hasClass('is-hidden')) {
                for (i = 0; i < itemTags.length; i++) {
                    if (! findOne(filtersShowing, [itemTags[i]])) {
                        filtersShowing.push(itemTags[i]);
                    }
                }
            }
        });
        
    }

    
    // COUNT ACTIVE FILTER GROUPS
    // ----------------------------------------------------------
    // Counts the number of filter groups containing an
    // active filter
    // ----------------------------------------------------------
    function countActiveFilterGroups() {
        var filterGroupsActive = 0;
        
        for (i = 0; i < filterArray.length; i++) { 
            if (filterArray[i].length != 0){
                filterGroupsActive++;
            }
        }
        return filterGroupsActive;
    }

    
    // FIND ONE
    // ----------------------------------------------------------
    // If haystack array contains any value in query array return
    // true else return false, quits after first match.
    // ----------------------------------------------------------
    function findOne(haystack, query) {
        return query.some(function (u) {
            return haystack.indexOf(u) >= 0;
        });
    };


   // ----------------------------------------------------------
   //Filter Starter sites
   // ----------------------------------------------------------
    var filterArray1 = [];
     
    // SETUP
    // ----------------------------------------------------------
    // Add a new array to filterArray for each filter-group
    // ----------------------------------------------------------
    jQuery('.filter-group-2').each( function(index1) {
        filterArray1.push([]);
    });
    
    // CLICK
    // ----------------------------------------------------------
    jQuery('.filter-group-2 [data-filter-toggle1]').on('click', function() {
        var filter1 = jQuery(this).attr('data-filter-toggle1'),
            parent1 = jQuery(this).parent('.filter-group-2'),
            parentIndex1 = jQuery('.filter-group-2').index(parent1);
        
        jQuery(this).toggleClass('is-active');
        
        // Modify filter array
        if (jQuery(this).hasClass('is-active')) {
            
            // Add an item to the filter array
            filterArray1[parentIndex1].push(filter1);
        } else {
            
            // Remove an item from the filter array
            var i = filterArray1[parentIndex1].indexOf(filter1);
            if (i != -1) {
                filterArray1[parentIndex1].splice(i, 1);
            }
        }
        
        // Render items with filtering
        render1();
    });
    
    
    // RENDER
    // ----------------------------------------------------------
    function render1() {
        var filterGroupsActive1 = countActiveFilterGroups1(),
            filterGroupMatches1,
            filtersShowing1 = [],
            itemTags1;
        
        // If no active filters show everything
        if (filterGroupsActive1 === 0) {
            jQuery('#starter-pack .item').removeClass('is-hidden');
            return false;
        }

        // Loop through items and filter
        jQuery('#starter-pack .item').each(function(index1) {

            itemTags1 = jQuery(this).attr('data-filter-tags').split(',');
            filterGroupMatches1 = 0;
            // Count item filter group matches
            for (i = 0; i < filterArray1.length; i++) {
                if (findOne1(filterArray1[i], itemTags1)) {
                    filterGroupMatches1++;
                }
            }
            
            // If item matches all filter groups show it
            if (filterGroupMatches1 === filterGroupsActive1) {
                jQuery(this).removeClass('is-hidden');
            } else {
                jQuery(this).addClass('is-hidden');
            }
            
            // If item showing add unique filters to filtersShowing
            if (! jQuery(this).hasClass('is-hidden')) {
                for (i = 0; i < itemTags1.length; i++) {
                    if (! findOne1(filtersShowing1, [itemTags1[i]])) {
                        filtersShowing1.push(itemTags1[i]);
                    }
                }
            }
        });
        
    }

    
    // COUNT ACTIVE FILTER GROUPS
    // ----------------------------------------------------------
    // Counts the number of filter groups containing an
    // active filter
    // ----------------------------------------------------------
    function countActiveFilterGroups1() {
        var filterGroupsActive1 = 0;
        
        for (i = 0; i < filterArray1.length; i++) { 
            if (filterArray1[i].length != 0){
                filterGroupsActive1++;
            }
        }
        return filterGroupsActive1;
    }

    // FIND ONE
    // ----------------------------------------------------------
    // If haystack array contains any value in query array return
    // true else return false, quits after first match.
    // ----------------------------------------------------------
    function findOne1(haystack1, query1) {
        return query1.some(function (v) {
            return haystack1.indexOf(v) >= 0;
        });
    };

    // FIND ONE
    // ----------------------------------------------------------
    // If haystack array contains any value in query array return
    // true else return false, quits after first match.
    // ----------------------------------------------------------

  // jQuery("button").click(function(){
  //   jQuery("#div1").load("demo_test.txt");
  // });

   /* Preloader */
  // jQuery(".contect-section .card-btn").on('click', function() { 
  // jQuery(window).on('load', function() {
  //   var preloaderFadeOutTime = 500;
  //   function spiceHidePreloader() {
  //     var preloader = jQuery('#spice-block-loader');
  //     setTimeout(function() {
  //       preloader.fadeOut(preloaderFadeOutTime);
  //     }, 500);
  //   }
  //   spiceHidePreloader();
  // }); 
  //  });

});

jQuery(document).ready(function(){
  jQuery(".starter-pack-img.new").click(function(){
    var name=jQuery(this).data('slug');
    var URL=plugin1.pluginpath1+'/inc/data/theme-data.json';
    jQuery.getJSON(URL, function (data) { console.log(data);
        let ghtml='';
        for (var i = 0; i < data[name].length; i++) {
            var counter = data[name][i];
            
            ghtml+='<div class="content-section starter-pack">';
            ghtml+='<div class="card '+counter.slug+'">';
            ghtml+='<div class="starter-pack-inner-img" style="background-image: url(https://spiceblocks.com/starter-sites/'+name+'/img/'+counter.images+');"></div>';
            ghtml+='<div class="card-details">' ;
            ghtml+='<div class="heading"><h4>'+counter.name+'</h4></div>';
            ghtml+='<div class="card-btn">';
            ghtml+='<button class="sbimport" onclick="spice_block_import(this.value)" value="'+name+'-'+counter.slug+'-layout">Import</button></div>';
            ghtml+='</div></div></div></div>';
            
        }
        //alert(ghtml);
        jQuery("#addinnerpage").append(ghtml);
    });
    jQuery(".library-single-page").show();
    jQuery("#starter-pack").hide();
  });
  jQuery(".back-btn").click(function(){
    document.getElementById('addinnerpage').innerHTML = "";
    jQuery(".library-single-page").hide();
    jQuery("#starter-pack").show();
  });  
});
function spice_block_import(name){
    post_via_ajax01(_wpMetaBoxUrl,name);       
}

var spiceBtSearch = {
            init: function(search_field, searchable_elements, searchable_text_class) {
                jQuery(search_field).keyup(function(e){
                    e.preventDefault();
                    var query = jQuery(this).val().toLowerCase();
                    if(query){
                        // loop through all elements to find match
                        jQuery.each(jQuery(searchable_elements), function(){
                            var title = jQuery(this).find(searchable_text_class).text().toLowerCase();
                            if(title.indexOf(query) == -1){
                                jQuery(this).hide();
                            } else {
                                jQuery(this).show();
                            }
                        });
                    } else {
                        // empty query so show everything
                        jQuery(searchable_elements).show();
                    }
                });
            }
        }

        // INIT
        jQuery(function(){
          // USAGE: spiceBtSearch.init(('search field element', 'searchable children elements', 'searchable text class');
          spiceBtSearch.init('#search_field', '#demo-section-pack div.item', '.heading h4');

       });
         var spiceBtSearch2 = {
            init: function(search_field2, searchable_elements, searchable_text_class) {
                jQuery(search_field2).keyup(function(e){
                    e.preventDefault();
                    var query = jQuery(this).val().toLowerCase();
                    if(query){
                        // loop through all elements to find match
                        jQuery.each(jQuery(searchable_elements), function(){
                            var title = jQuery(this).find(searchable_text_class).text().toLowerCase();
                            if(title.indexOf(query) == -1){
                                jQuery(this).hide();
                            } else {
                                jQuery(this).show();
                            }
                        });
                    } else {
                        // empty query so show everything
                        jQuery(searchable_elements).show();
                    }
                });
            }
        }

        // INIT
        jQuery(function(){
          // USAGE: spiceBtSearch.init(('search field element', 'searchable children elements', 'searchable text class');
          spiceBtSearch2.init('#search_field2', '#starter-pack div.item', '.heading h4');

       });