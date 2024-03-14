
localStorage.setItem('categorifyModalActiveFolder', '');

(function( $ ) {
    "use strict";
	
    $(document).ready(function(){
        var wp 				= window.wp;
		
		
		if (wp.media) {
			wp.media.view.Modal.prototype.on('open', function() {
				categorify_cc_recallMe();
				categorify_cc_ifModalOpen();
			});
		}
      
        if (typeof wp !== 'undefined' && typeof wp.Uploader === 'function') {
            $.extend( wp.Uploader.prototype, {
                progress: function() {},
                init : function() {
                  
                    if (this.uploader) {
                      
                        
                        this.uploader.bind('FileFiltered', function( up, file ) {
                           
                        });
                       
                        this.uploader.bind('BeforeUpload', function(uploader, file) {
							var folderID,params;
							
							if(localStorage.getItem('categorifyModalActiveFolder') !== ''){
								folderID 	= Number(localStorage.getItem('categorifyModalActiveFolder'));
							}else{
								folderID 	= Number($( ".categorify-upload-category-filter" ).val());
							}
							params 			= uploader.settings.multipart_params;
							
							params.ccFolder = folderID;
                        });
						
                        this.uploader.bind('UploadProgress', function(up, file) {
							$('.uploader-window').hide().css('opacity', 0);
							categorify_eug_start_preloader(); 
                        });


						//run after FilesAdded
                        this.uploader.bind('UploadComplete', function(up, files) {
							var currentFolderID;
							if(localStorage.getItem('categorifyModalActiveFolder') !== ''){
								currentFolderID = localStorage.getItem('categorifyModalActiveFolder');
								categorify_eug_set_active_folder(currentFolderID);
								categorify_cc_ifModalOpen(); // all will be broken, if up this code to one line
							}else{
								currentFolderID = $(".categorify-upload-category-filter").val();
								categorify_eug_set_active_folder(currentFolderID);
								categorify_cc_ifModalOpen(); // all will be broken, if up this code to one line
								//console.log(currentFolderID);
							}
                        });

                        this.uploader.bind('FilesAdded', function( up, files ) {
                            var currentFolderID;
							
							
							if(localStorage.getItem('categorifyModalActiveFolder') !== ''){
								currentFolderID	= localStorage.getItem('categorifyModalActiveFolder');
							}else if($('.media-frame-content').attr('aria-labelledby') === 'menu-item-browse'){
								currentFolderID	= $(".wpmediacategory-filter").val();
							}else{
								currentFolderID	= $(".categorify-upload-category-filter").val();
							}
							
							//console.log(currentFolderID);
							
							
                            files.forEach(function(file){
                                if(currentFolderID === 'all'){
                                    categorify_eug_update_count(null, -1);
                                }else if(Number(currentFolderID) === -1){
                                    categorify_eug_update_count(null, -1);
                                }else{
                                    categorify_eug_update_count(null, currentFolderID);
                                }
                            });
                            
                        });

                    }

                }
            });
        }

        
    });
	
	function categorify_eug_start_preloader(){
		$('.categorify_be_loader').addClass('active');
	}
	function categorify_eug_stop_preloader(){
		$('.categorify_be_loader').removeClass('active');
	}
	
	
	function categorify_eug_increase_count(folderID){
		var folderCount 	= $('ul li.category_item[data-id="' + folderID + '"] .cc_count').text();
		if(folderCount === ''){folderCount = 0;}
		folderCount			= parseInt(folderCount) - 1;
		$('ul li.category_item[data-id="' + folderID + '"] .cc_count').text(folderCount);
		var totalCount	 	= $('ul li.category_item[data-id="all"] .cc_count').text();
		if(totalCount === ''){totalCount = 0;}
		totalCount			= parseInt(totalCount) - 1;
		$('ul li.category_item[data-id="all"] .cc_count').text(totalCount);
	}
	
	function categorify_eug_update_count(from,to){
		
		from 	= parseInt(from);
		to 		= parseInt(to);
		
		if(from !== to){
			if(from){
				var countTermFrom 	= $('ul li.category_item[data-id="' + from + '"] .cc_count').text();
				
				if(countTermFrom === ''){countTermFrom = 0;}
				countTermFrom 		= parseInt(countTermFrom) - 1;
				if(countTermFrom){
					$('ul li.category_item[data-id="' + from + '"] .cc_count').text(countTermFrom);
				}else{
					$('ul li.category_item[data-id="' + from + '"] .cc_count').text('');
				}
			}else{
				var all				= $('ul li.category_item[data-id="all"]');
				var count			= all.find('.cc_count').text();
				if(count === ''){count = 0;}
				count				= parseInt(count) + 1;
				all.find('.cc_count').text(count);
			}
			if(to){
				var countTermTo 	= $('ul li.category_item[data-id="' + to + '"] .cc_count').text();
				if(countTermTo === ''){countTermTo = 0;}
				countTermTo 		= parseInt(countTermTo) +1;
				$('ul li.category_item[data-id="' + to + '"] .cc_count').text(countTermTo);
			}
		}
		
	}
	
	function categorify_eug_set_active_folder(currentFolderID){
		categorify_eug_start_preloader();
		
		
		$('.wpmediacategory-filter').val(currentFolderID);
		$('.wpmediacategory-filter').trigger('change');
		
		
		var sidebar 	= $('.cc_categorify_sidebar');
		var backbone 	= categorify_eug_getBackboneOfMedia(sidebar);
		if (backbone.browser.length > 0 && typeof backbone.view == "object") {
			try{
				backbone.view.collection.props.set({ ignore: (+ new Date()) });
			}catch(e){
				console.log(e);
			}
		}else{
			sidebar 	= $('.media-modal-content');
			backbone 	= categorify_eug_getBackboneOfMedia(sidebar);
			if (backbone.browser.length > 0 && typeof backbone.view == "object") {
				try{
					backbone.view.collection.props.set({ ignore: (+ new Date()) });
				}catch(e){
					console.log(e);
				}
			}
		}
		

		$('.attachments').css('height', 'auto');
		
		// stop preloader
		categorify_eug_stop_preloader();

	}
	
	function categorify_eug_getBackboneOfMedia(obj) {
		
		var browser,
			backboneView,
			parentModal = obj.parents(".media-modal");
		if (parentModal.length > 0){
			browser 	= parentModal.find(".attachments-browser");
		}else{
			browser 	= $("#wpbody-content .attachments-browser");
		}
		backboneView 	= browser.data("backboneView");
		return { browser: browser, view: backboneView };
	}
	
	function categorify_cc_ifModalOpen(){
		var myFilter		= $(".categorify-upload-category-filter");
		var activeFolderID	= myFilter.val();
		localStorage.setItem('categorifyModalActiveFolder', activeFolderID);
		
		if($('.media-frame-content').attr('aria-labelledby') === 'menu-item-browse'){
			activeFolderID	= $(".wpmediacategory-filter").val();
			localStorage.setItem('categorifyModalActiveFolder', activeFolderID);
		}
		myFilter.on('change', function() {
			activeFolderID 	= this.value;
			localStorage.setItem('categorifyModalActiveFolder', activeFolderID);
		});
		
		$(".wpmediacategory-filter").on('change',function(){
			activeFolderID 	= this.value;
			if($('.media-frame-content').attr('aria-labelledby') === 'menu-item-browse'){
				localStorage.setItem('categorifyModalActiveFolder', activeFolderID);
			}
		});
		
		var sidebarCat = jQuery('.cc_categorify_category_list .category_item');
		if(sidebarCat.hasClass('active')){
			activeFolderID = jQuery('.cc_categorify_category_list .category_item.active').data('id');
			localStorage.setItem('categorifyModalActiveFolder', activeFolderID);
		}
		
	}
	
	function categorify_cc_recallMe(){
		$('.media-menu-item').on('click',function(){
			setTimeout(function(){
				categorify_cc_ifModalOpen();
			},3);
		});
	}

	
    jQuery(document).ajaxSend(function (e, xhs, req) {
        
        try {
            if(req.data.indexOf("action=delete-post") > -1){
                var attachmentID 	= req.context.id;
				
				var requestData		= {
					attachmentID: attachmentID,
					action: 'categorifyAjaxCheckDeletingMedia',
					security: categorifyConfig.nonce,
				};
				
                jQuery.ajax({
                  type: "POST",
                  data: requestData,
                  url: ajaxurl,
                  success: function (fromdata){
					var fnQueriedObj	= jQuery.parseJSON(fromdata),
						result			= fnQueriedObj.terms,
						error			= fnQueriedObj.error,
						hiddenValue		= '';
					if(error === 'no'){
						if(result.length){
							$.each(result,function(index,value){
								hiddenValue += '' + value.term_id +  ',';
							});
							hiddenValue = hiddenValue.slice(0, hiddenValue.length - 1);
						}
						$('#categorify_hidden_terms').val(hiddenValue);
					}
				  }
                });
				
            }
        }catch(e) {}

    }.bind(this));


    jQuery(document).ajaxComplete(function (e, xhs, req) {
        try{
            if(req.data.indexOf("action=delete-post") > -1){
				
                var hiddenTermValue 	= $('#categorify_hidden_terms').val();
				
                if(hiddenTermValue){
                    var terms = hiddenTermValue.split(",");
                    $.each(terms, function(index, value){
                        categorify_eug_increase_count(value);
                    });
                }
				
            }
        }catch(e){}
    }.bind(this));
	
	
	

})( jQuery );



(function($){
	
    "use strict";
	
    var categorifyHook 			= {};
	
    categorifyHook.uploadMedia 	= function(){

        if (!$("body").hasClass("media-new-php")){
            return;
        }
		
        setTimeout(function(){
            if(uploader){
                uploader.bind('BeforeUpload', function(uploader, file) {
                    var params 		= uploader.settings.multipart_params;
                    params.ccFolder = $('.categorify-upload-category-filter').val();
                });
            }
        }.bind(this), 500);
    };

    $(document).ready(function(){
        var wp = window.wp;
        categorifyHook.uploadMedia();

    });
})(jQuery);