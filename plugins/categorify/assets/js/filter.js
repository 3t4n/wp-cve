	var mediaConfig 		= categorifyConfig2;
	
    var CategorifyFilter 		= {
		
		// get ajax URL
		ajaxurl:						mediaConfig.ajaxUrl,
		nonce:							mediaConfig.nonce,

		moveOneFile:					mediaConfig.moveOneFile,
		moveText:						mediaConfig.move,
		filesText:						mediaConfig.files,
		
		dragItem:						null,
		
        init: function () {
			
			var self = this;
			this.appendDragger();
			this.dragItem = jQuery("#categorify-dragger");
			
			jQuery( document ).ajaxComplete(function( event, xhr, settings ) {
				if(settings.data != undefined && settings.data != "" && settings.data.indexOf("action=query-attachments") != -1) {
					self.dragAndDropMedia();
				}
			});
			
        },
		appendDragger: function(){
			var self	= this;
			if(jQuery('#categorify-dragger').length === 0){
				jQuery("body").append('<div id="categorify-dragger" data-id="">' + self.moveOneFile + '</div>');
			}
		},
		dragAndDropMedia: function(){
			var self		= this,
				textDrag	= self.moveOneFile;

			
			jQuery('.attachments-browser li.attachment').draggable({
				
				revert: "invalid",
				containment: "document",
				cursor: 'move',
				cursorAt: {
					left: 2,
					top: 2
				},
				
				helper: function(){
					return jQuery("<div></div>");
				},
				
				start: function(){
					
					
					
					var selectedFiles = jQuery('.attachments li.selected').length;
					if (selectedFiles > 0) {textDrag = self.moveText + ' ' + selectedFiles + ' ' + self.filesText;}
					
					jQuery('body').addClass('cc_draging');
					self.dragItem.html(textDrag);
					self.dragItem.addClass('active');
				},
				
				stop: function() {
					jQuery('body').removeClass('cc_draging');
					self.dragItem.removeClass('active');
					textDrag = self.moveOneFile;
				},
				
				drag: function(){
					var id = jQuery(this).data("id");

					self.dragItem.data("id", id);

					self.dragItem.css({
						"top": event.clientY - 15,
						"left": event.clientX - 15,
					});
				}
				
				
			});
			
			
			setTimeout(function(){
				
				jQuery("li.category_item").droppable({
					accept: ".attachments-browser li.attachment",
					hoverClass: 'hover',
					classes: {
						"ui-droppable-active": "ui-state-highlight"
					},
					drop: function() {
						
						var folderID 	= jQuery(this).attr('data-id');
						var IDs 		= self.getSelectedFiles();

						if(IDs.length){
							self.moveMultipleMedia(IDs, folderID);
						}else{
							
							self.moveSingleMedia(folderID);
						}
						
					}
				});
			}, 1200);
			
			
			
		},
		
		
		getSelectedFiles: function(){
			var selectedFiles 	= jQuery('.attachments li.selected'),
				IDs 			= [];

			if (selectedFiles.length) {
				selectedFiles.each(function (index, item) {
					IDs.push(jQuery(item).data("id"));
				});
				return IDs;
			}

			return false;
		},
		
		moveMultipleMedia: function(IDs, folderID) {
			var self 			= this,
				currentFolder 	= jQuery(".wpmediacategory-filter").val();
			
			var requestData 	= {
				action: 'categorifyAjaxMoveMultipleMedia',
				IDs: IDs,
				folderID: folderID,
				security: self.nonce,
			};

			jQuery.ajax({
				type: 'POST',
				url: self.ajaxurl,
				cache: false,
				data: requestData,
				success: function(data) {
					var fnQueriedObj 	= jQuery.parseJSON(data),
						result			= fnQueriedObj.result;
					
					result.forEach(function(item){
						self.updateCount(item.from, item.to);
						if (currentFolder !== 'all') {
							jQuery('ul.attachments li[data-id="' + item.id + '"]').detach();
						}
					});
					
					self.disableBulkSelect();
					
				},
				error: function(xhr, textStatus, errorThrown){
					console.log(errorThrown);
					console.log(textStatus);
					console.log(xhr);
				}
			});



		},
		
		disableBulkSelect: function(){
			if(!jQuery(".wp-admin.upload-php .media-toolbar.wp-filter .media-toolbar-secondary .media-button.delete-selected-button").hasClass("hidden")){
			  	jQuery(".wp-admin.upload-php .media-toolbar.wp-filter .media-toolbar-secondary .media-button.select-mode-toggle-button").trigger("click");
			}
		},
		
		moveSingleMedia: function(folderID){
			
			var self			= this,
				mediaID			= self.dragItem.data("id"),
				mediaItem	 	= jQuery('.attachment[data-id="' + mediaID + '"]'),
				currentFolder 	= jQuery(".wpmediacategory-filter").val();
			currentFolder		= jQuery('.category_item.active').attr('data-id');
			
			
			if (folderID === 'all' || folderID 	== currentFolder){
				return false;
			}
			
			self.startPreloader();
			
			
			var requestData = {
				action: 'categorifyAjaxGetTermsByMedia',
				security: self.nonce,
				ID: mediaID,
			};

			jQuery.ajax({
				type: 'POST',
				url: self.ajaxurl,
				cache: false,
				data: requestData,
				success: function(data) {
					var fnQueriedObj 	= jQuery.parseJSON(data),
						error			= fnQueriedObj.error;
					if(error === 'no'){
						self.moveSingleMediaAjaxProcess(fnQueriedObj.terms,folderID,mediaID,currentFolder,mediaItem);
					}else{
						self.stopPreloader();
					}
					
				},
				error: function(xhr, textStatus, errorThrown){
					console.log(errorThrown);
					console.log(textStatus);
					console.log(xhr);
				}
			});

			
		},
		
		moveSingleMediaAjaxProcess: function(result,folderID,mediaID,currentFolder,mediaItem){
			var self	= this,
				terms 	= Array.from(result, v => v.term_id);
			//check if drag to owner folder

			if (terms.includes(parseInt(folderID))) {
				self.stopPreloader();
				return;
			}
			
			var attachments = {};

			attachments[mediaID] = { menu_order: 0 };
			
			var requestData = {
				action: 'categorifyAjaxMoveSingleMedia',
				attachments: attachments,
				mediaID: mediaID,
				folderID: folderID,
				security: self.nonce,
				
			};
			
			

			jQuery.ajax({
				type: 'POST',
				url: self.ajaxurl,
				cache: false,
				data: requestData,
				success: function(data) {
					var fnQueriedObj 	= jQuery.parseJSON(data);
					var error			= fnQueriedObj.error;

					
					
					if (error === 'no') {

						jQuery.each(terms, function (index, value) {
							self.updateCount(value, folderID);
							
						});
						
						//if attachment not in any terms (folder)
						if(currentFolder === 'all' && !terms.length) {
							self.updateCount(-1, folderID);
						}

						if(parseInt(currentFolder) === -1) {
							self.updateCount(-1, folderID);
						}

						if(currentFolder !== 'all') {
							mediaItem.detach(); // remove this media if not selected "all files"
						}

					}

					self.stopPreloader();
					
				},
				error: function(xhr, textStatus, errorThrown){
					console.log(errorThrown);
					console.log(textStatus);
					console.log(xhr);
				}
			});
		},
		
		
		
		/* начать preloader */
		startPreloader: function(){
			jQuery('.categorify_be_loader').addClass('active');
		},
		/* остановить preloader */
		stopPreloader: function(){
			jQuery('.categorify_be_loader').removeClass('active');
		},
		
		
		updateCount: function(from, to){
			
			from 	= parseInt(from);
			to 		= parseInt(to);
			
			
			if(from !== to){
				if(from){
					var countTermFrom 	= jQuery('ul li.category_item[data-id="' + from + '"] .cc_count').text();
					
					if(countTermFrom === ''){countTermFrom = 0;}
					countTermFrom 		= parseInt(countTermFrom) -1;
					
					if(countTermFrom){						
						
						jQuery('ul li.category_item[data-id="' + from + '"] .cc_count').text(countTermFrom);
					}else{
						jQuery('ul li.category_item[data-id="' + from + '"] .cc_count').text('');
					}
				}
				if(to){
					
					var countTermTo 	= jQuery('ul li.category_item[data-id="' + to + '"] .cc_count').text();
					if(countTermTo === ''){countTermTo = 0;}
					countTermTo 		= parseInt(countTermTo) +1;
					
					jQuery('ul li.category_item[data-id="' + to + '"] .cc_count').text(countTermTo);
				}
			}	
		},
		
		
    };
	
	jQuery(document).ready(function(){CategorifyFilter.init();});