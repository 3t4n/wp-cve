
	var mediaConfig 		= categorifyConfig;
	
	var coreFilter 			= {
		categoryID:  '',
		page: 1,
	};
    var CategorifyCore 		= {
		
		// for iaoAlert jquery plugin
		iaoAlertTime:					'7000',
		iaoAlertPosition:				'top-right',
		alertSuccessIcon: 				'<span class="icon_holder success"></span>',
 		alertWarningIcon:				'<span class="icon_holder warning">!</span>',
		slugError:						'<span class="error_note">'+mediaConfig.slugError+'</span>',
		enterName:						'<span class="error_note">'+mediaConfig.enterName+'</span>',
		
		// get ajax URL
		ajaxurl:						mediaConfig.ajaxUrl,
		uploadURL:						mediaConfig.uploadURL,
		adminURL:						mediaConfig.adminURL,
		nonce:							mediaConfig.nonce,
		
		// get translated texts from interface.php file
		areYouSure:						mediaConfig.areYouSure,
		clearText:						mediaConfig.clearText,
		willBeMovedToUncategorized:		mediaConfig.willBeMovedToUncategorized,
		confirmText:					mediaConfig.confirmText,
		cancelText:						mediaConfig.cancelText,
		newFolderText:					mediaConfig.newFolderText,
		renameText:						mediaConfig.renameText,
		clearMediaText:					mediaConfig.clearMediaText,
		deleteText:						mediaConfig.deleteText,
		pluginURL:						mediaConfig.pluginURL,
		hasSubFolder:					mediaConfig.hasSubFolder,
		finishRename:					mediaConfig.finishRename,
		plugin:							mediaConfig.plugin,
		
		moveOneFile:					mediaConfig.moveOneFile,
		moveText:						mediaConfig.move,
		filesText:						mediaConfig.files,
		itemText:						mediaConfig.item,
		itemsText:						mediaConfig.items,
		noItemDOM:						mediaConfig.noItemDOM,
		
		
		tooltipForListMode:				null,
		
		// min and maximum width for sidebar
		minWidth:						250,
		maxWidth:						750,
		
		// global variables
		menuItemDepthPerLevel: 			18,
		globalMaxDepth: 				11,
        menuList: 						undefined,
        menusChanged: 					false,
        isRTL: 							!!("undefined" !== typeof isRtl && isRtl), 			// get true if RTL is activated, otherwise get false
        negateIfRTL: 					("undefined" !== typeof isRtl && isRtl) ? -1 : 1, 	// get -1 if RTL is activated, otherwise get 1
        currentParent: 					null,
        oldParent: 						null,
        categorifyCurrentFolder: 			localStorage.getItem('categorifyCurrentFolder') || 'all',
        categorifyState: 					(localStorage.getItem('categorifyTreeState')) ? localStorage.getItem('categorifyTreeState').split(',') : [],
		categorifyIsListNoFolder:			localStorage.getItem('categorifyIsListNoFolder')|| 0,
		
		closedCatsBeforeSearch:			[],
		stopAction:						0,
		isPremium:						mediaConfig.isPremium,

		
        init: function () {
			
			'use strict';
			
			
			if(!(jQuery('select[name="cc_categorify_folder"]').length)){
				localStorage.setItem('firstTime', 1);
			}
			
			
			// change iaoalert position if RTL mode is activated
			if(this.isRTL){
				this.iaoAlertPosition = 'top-left';
			}
			// change iaoalert success icon: add SVG
			this.alertSuccessIcon = '<span class="icon_holder success">'+this.getIcon('check')+'</span>';
			
			// add extra class to body for categorify
			jQuery('body').addClass('categorify-admin-panel');
			
			// remove duplicate IDs from tree in order to avoid any issues with opener (triangle)
			this.categorifyState = this.removeDuplicates(this.categorifyState);
			
			// get sidebar to body
			this.appendSidebarToBody();
			
			
			
			// if has sidebar 
			if(jQuery('.cc_categorify_sidebar').length){
				
				
				// remove all wordpress error or notice if has sidebar
				jQuery('.update-nag').remove();
				
				// define menu list after appending interface to body
				this.menuList = jQuery("#categorify_be_folder_list");

				// jQuery extensions
				this.extensionsForCategorify();

				// init sortable folders (categories) function
				if (this.menuList.length) {
					this.sortableCategories();
				}

				// update icons for parent folders
				this.addIcons();

				// append context menu
				this.appendMovingPopup();

				// init SVG magic function
				this.magicSVG();

				// call context menu by clicking on triple points
				this.callMovingPopup();

				// new folder click function for top button
				this.addNewFolderByTopButton();

				// new folder click function for context menu
				this.addNewFolderByContextMenu();

				// cancel adding new folder
				this.cancelAddingNewCategory();

				// change sidebar width with splitter
				this.splitter();
				
				// searches from folders
				this.startSearchFolder();
				
				this.detectAnyChange();
				
				this.setActiveCatOnInitialLoad();
				
			}
				
			
			
			// list mode functions
			if(this.isListMode()){
				// append moving tooltip "Move 1 file";
				this.appendDragger();
				
				// select this tooltip
				this.tooltipForListMode	 	= jQuery("#categorify-dragger");
				
				this.setFolderFilterForList();
				
				this.movingMediaForListMode();
				
			}
			
			
        },
		
		
		setActiveCatOnInitialLoad: function(){
			'use strict';
			
			// SET LAST ACTIVE FOLDER
			var self = this;
			
			if(self.isPremium != 1){return;}
			
			var activeCat = self.categorifyCurrentFolder || 'all';
			if(activeCat == 'all' || activeCat == -1){
				var button = jQuery('.cc_categorify_header .header_bottom ul li[data-id="'+activeCat+'"] a');
				self.setActiveCategory(button);
			}else{
				var button = jQuery('.cc_categorify_category_list li.category_item[data-id="'+activeCat+'"] a');
				self.setActiveCategory(button);
			}
			
		},
		
		
		setFolderFilterForList: function(){
			
			var self = this;
				
			// Add all folders to select
			jQuery.each(categorifyFolders, function (index, element) {
				jQuery('.wpmediacategory-filter').append('<option value="' + element.folderID + '">' + element.folderName + '</option>');
			});
			
			var url 	= new URL(window.location.href);
			var categorify_filter = url.searchParams.get("cc_categorify_folder");
			
			if(categorify_filter != null){
				self.defineAndSetActiveCategory();
			}			
			
		},
		
		
		detectAnyChange: function(){
			
			
			/*var mutationObserver = new MutationObserver(function(mutations) {
				mutations.forEach(function(mutation) {
					console.log(mutation);
				});
			});*/
			
			
			jQuery(window).on('click', function(){
				
				// we need to disable sidebar from popup window
				if(jQuery(".categorify-modal .media-frame").hasClass('hide-router')) {
					jQuery(".categorify-modal").addClass('closed');
				}else{
					jQuery(".categorify-modal").removeClass('closed');
				}
			});
		},
		
		
		
		appendDragger: function(){
			var self			= this;
			if(jQuery('#categorify-dragger').length === 0){
				jQuery("body").append('<div id="categorify-dragger" data-id="">' + self.moveOneFile + '</div>');
			}
		},
		movingMediaForListMode: function(){
			var self			= this;
			
			
			jQuery('.wpmediacategory-filter').val(self.categorifyCurrentFolder);
			jQuery('.wpmediacategory-filter').trigger('change');
			localStorage.setItem('categorifyCurrentFolder', self.categorifyCurrentFolder);

			self.dragDropListMode();
			
			
		},
		
		moveMultipleMediaForListMode: function(IDs,folderID){
			var self		= this;
			
			var requestData = {
				action: 'categorifyAjaxMoveMultipleMedia',
				IDs: IDs,
				folderID: folderID,
				security: self.nonce,
			};
			
			
			self.startPreloader();
			
			jQuery.each(IDs, function(i, e) {
				jQuery('#post-' + e).addClass('cc_opacity');
			});
			
			jQuery.ajax({
				type: 'POST',
				url: self.ajaxurl,
				cache: false,
				data: requestData,
				success: function(data) {
					var fnQueriedObj 	= JSON.parse(data);
					var result			= fnQueriedObj.result;
					
					
					
					result.forEach(function(item){
						self.updateCount(item.from, item.to);
					});
					
					if (jQuery('.wpmediacategory-filter').val() !== null) {
						jQuery.each(IDs, function(i, e) {
							jQuery('#post-' + e).remove();
						});
						var length = jQuery('.wp-list-table tbody tr').length;
						if(length === 0){
							jQuery('.wp-list-table tbody').append(self.noItemDOM);
							jQuery('.displaying-num').hide();
						}else{
							jQuery('.displaying-num').text(length + ' ' + (length === 1 ? self.itemText : self.itemsText));
						}
					}
					
					jQuery('.wp-list-table tbody tr').removeClass('cc_opacity');
					self.stopPreloader();
					
				},
				error: function(xhr, textStatus, errorThrown){
					console.log(errorThrown);
					console.log(textStatus);
					console.log(xhr);
				}
			});
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
		
		getSelectedFilesForListMode: function(){
			var selectedFiles 	= jQuery('.wp-list-table input[name="media[]"]:checked');
			var IDs 			= [];
			if(selectedFiles.length){
				selectedFiles.each(function (index, item) {
					IDs.push(jQuery(item).val());
				});
				return IDs;
			}

			return false;
		},
		
		moveSingleMediaForListMode: function(folderID){
			var self			= this;
			var mediaID			= self.tooltipForListMode.data("id");
			var mediaItem	 	= jQuery('.attachment[data-id="' + mediaID + '"]');
			var currentFolder 	= jQuery(".wpmediacategory-filter").val();
			currentFolder		= jQuery('.category_item.active').attr('data-id');
			
			
			
			// if selected All category OR moving going on into All category stop action
			if (folderID === 'all' || folderID 	=== currentFolder){
				jQuery('.wp-list-table tbody tr').removeClass('cc_opacity');
				return false;
			}
			
			
			self.startPreloader();
			jQuery('#post-' + mediaID).addClass('cc_opacity');
			
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
					var fnQueriedObj 	= JSON.parse(data);
					var error			= fnQueriedObj.error;
					if(error === 'no'){
						
						self.moveSingleMediaAjaxProcessForListMode(fnQueriedObj.terms,folderID,mediaID,currentFolder,mediaItem);
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
		
		moveSingleMediaAjaxProcessForListMode: function(result,folderID,mediaID,currentFolder,mediaItem){
			var self	= this;
			var terms 	= Array.from(result, v => v.term_id);
			//check if drag to owner folder

			if (terms.includes(parseInt(folderID))) {
				self.stopPreloader();
				jQuery('.wp-list-table tbody tr').removeClass('cc_opacity');
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
					
					
					var fnQueriedObj 	= JSON.parse(data);
					var error			= fnQueriedObj.error;
					
					if (error === 'no') {

						jQuery.each(terms, function (index, value) {
							self.updateCount(value, folderID);
						});
						
						//if attachment not in any terms (folder)
						if (currentFolder === 'all' && !terms.length) {
							self.updateCount(-1, folderID);
						}

						if (parseInt(currentFolder) === -1) {							
							self.updateCount(-1, folderID);
						}

						if (currentFolder !== 'all') {
							mediaItem.detach();
						}

					}
					
					self.stopPreloader();
					jQuery('.wp-list-table tbody tr').removeClass('cc_opacity');
					
					if(jQuery('.wpmediacategory-filter').val() !== null){
						jQuery('#post-' + mediaID).remove();
						var length = jQuery('.wp-list-table tbody tr').length;
						if(length === 0){
							jQuery('.wp-list-table tbody').append(self.noItemDOM);
							jQuery('.displaying-num').hide();
						}else{
							jQuery('.displaying-num').text(length + ' ' + (length === 1 ? self.itemText : self.itemsText));
						}
					}

					
				},
				error: function(xhr, textStatus, errorThrown){
					console.log(errorThrown);
					console.log(textStatus);
					console.log(xhr);
				}
			});
		},

		splitter: function(){
			var self = this;
			jQuery(".cc_categorify_sidebar").categorifyResize({
				handleSelector: ".categorify_splitter.active",
				resizeHeight: false,
				onDrag: function (e, jQueryel, newWidth) {
					jQueryel.css('overflow', 'initial');
				 	if((newWidth <= self.minWidth) || (newWidth >= self.maxWidth)){
				 		return false;
				 	}
				 	jQuery('.cc_categorify_sidebar_in, .cc_categorify_sidebar_bg').css({width: newWidth + 'px'});
				}
			});
		},
		
		
		defineAndSetActiveCategory: function(){
			var self 		= this;
			var folderID;
			
			folderID		= self.categorifyCurrentFolder;		
			
			jQuery('.cc_categorify_header .header_bottom ul li[data-id="all"]').removeClass('active');
			
			if(folderID == 'all'){
				jQuery('.cc_categorify_header .header_bottom ul li[data-id="all"]').addClass('active');
			}else if(parseInt(folderID) > 0){
				jQuery('#cc_category_item_' + folderID).addClass('active');
			}else if(parseInt(folderID) === -1){
				jQuery('.cc_categorify_header .header_bottom ul li[data-id="-1"]').addClass('active');
			}
			
			

			
			if(self.isListMode()){
				if((jQuery('ul.cc_categorify_category_list li.category_item').length === 0) && (parseInt(folderID) === -1)){
					self.categorifyCurrentFolder = 'all';
					folderID	= 'all';
					self.categorifyIsListNoFolder = -1;
					localStorage.setItem('categorifyIsListNoFolder', self.categorifyIsListNoFolder);
					
				}
				if((jQuery('ul.cc_categorify_category_list li.category_item').length === 0) && (parseInt(self.categorifyIsListNoFolder) === -1)){
					self.categorifyCurrentFolder = 'all';
					folderID	= 'all';
					self.categorifyIsListNoFolder = -1;
					localStorage.setItem('categorifyIsListNoFolder', self.categorifyIsListNoFolder);
				}
			}else{
				self.categorifyIsListNoFolder = 0;
				localStorage.setItem('categorifyIsListNoFolder', self.categorifyIsListNoFolder);
			}
			
			
			jQuery('.wpmediacategory-filter').val(self.categorifyCurrentFolder);
			jQuery('.wpmediacategory-filter').trigger('change');
			jQuery('.attachments').css('height', 'auto');
			
			
			if(jQuery('.categorify-upload-category-filter').length){
				jQuery('.categorify-upload-category-filter').val(self.categorifyCurrentFolder);
				jQuery('.categorify-upload-category-filter').trigger('change');
			}
		},
		
		
		dragDropListMode: function(){
			"use strict";
			var self					= this;
			var dragItem 				= jQuery("#categorify-dragger");
			var textDrag 				= self.moveOneFile;
			
			
			jQuery('table.wp-list-table tr').draggable({

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
					
					var selectedFiles = jQuery('.wp-list-table input[name="media[]"]:checked').length;
					if (selectedFiles > 0) {textDrag = self.moveText + ' ' + selectedFiles + ' ' + self.filesText;}

					jQuery('body').addClass('cc_draging');
					dragItem.html(textDrag);
					dragItem.addClass('active');
				},

				stop: function() {
					jQuery('body').removeClass('cc_draging');
					dragItem.removeClass('active');
					textDrag = self.moveOneFile;
				},

				drag: function(){
					var ID 				= jQuery(this).attr("id");
					ID 					= ID.match(/post-([\d]+)/)[1];
					dragItem.data("id", ID);

					dragItem.css({
						"top": event.clientY - 15,
						"left": event.clientX - 15,
					});
				}


			});
			
			
			setTimeout(function(){
				jQuery("li.category_item").droppable({
					accept: "table.wp-list-table tr",
					hoverClass: 'hover',
					classes: {
						"ui-droppable-active": "ui-state-highlight"
					},
					drop: function() {

						var folderID 	= jQuery(this).attr('data-id');
						var IDs 		= self.getSelectedFilesForListMode();

						if(IDs.length){
							self.moveMultipleMediaForListMode(IDs, folderID);
						}else{
							self.moveSingleMediaForListMode(folderID);
						}

					}
				});
			}, 500);
			
		},
		
		
		addIcons: function () {
			var self 	= this;
            var list 	= self.menuList.find('li.category_item');
            jQuery.each(list, function (index,element) {
                var depth 		= jQuery(element).menuItemDepth();
                var nextLi 		= jQuery(element).next();
                if (nextLi.hasClass('category_item')) {
					var depthNext 	= nextLi.menuItemDepth();
                    if (depthNext > depth) {
                        if (self.categorifyState.indexOf(jQuery(element).data('id').toString()) < 0) {
							var children = jQuery(element).childMenuItems();
							children.wrapAll('<li class="new-wrapper children_of_' + jQuery(element).attr('id') + '"><ul></ul></li>');
							jQuery(element).addClass('has_children').addClass('close');
                        }else{
                            jQuery(element).addClass('has_children').addClass('open');
                        }
                    }
                }
            });

			
            jQuery(document).on('click', '.has_children .cc_dropdown', function (event) {
                event.stopPropagation();
                event.preventDefault();
				var clickBtn	= jQuery(this);
				var clickedLi	= clickBtn.closest('li.category_item');
				var ID 			= clickedLi.data('id');
				var children;
				
				if(clickedLi.hasClass('open')) {
					children 	= clickedLi.childMenuItems();

					children.wrapAll('<li class="new-wrapper children_of_' + clickedLi.attr('id') + '"><ul></ul></li>');
					clickedLi.removeClass('open').addClass('close');

					self.categorifyState.splice(self.categorifyState.indexOf(ID.toString()), 1);
					localStorage.setItem("categorifyTreeState", self.categorifyState);

				}else if(clickedLi.hasClass('close')) {
					children 	= jQuery('.children_of_' + clickedLi.attr('id') + ' > ul > li.category_item');

					children.unwrap().unwrap();
					clickedLi.removeClass('close').addClass('open');
					if (self.categorifyState.indexOf(ID.toString()) < 0) {
						ID = ID.toString();
						self.categorifyState.push(ID);
						localStorage.setItem("categorifyTreeState", self.categorifyState);
					}

				}
				
				localStorage.setItem('categorifyCurrentFolder', ID);
				
				self.updateFolderPosition();

            });
        },
		refreshFolderPositionAjax: function(current, newParent){
			var self		= this;
			var requestData = {
				action: 'categorifyAjaxMoveCategory',
				current: current,
				parent: newParent,
				security: self.nonce,
			};

			jQuery.ajax({
				type: 'POST',
				url: self.ajaxurl,
				cache:true,
				data: requestData,
				success: function(data) {
					var fnQueriedObj 	= JSON.parse(data);
					if(fnQueriedObj.error === 'no'){
						self.updateFolderPosition();
					}
				},
				error: function(xhr, textStatus, errorThrown){
					console.log(errorThrown);
					console.log(textStatus);
					console.log(xhr);
				}
			});
		},
		updateFolderPosition: function(){
			var self		= this,
				data 		= '',
				i			= '';
			
			self.startPreloader();
			
			jQuery(".cc_categorify_sidebar .input_category_id").each(function () {
				var ID		= jQuery(this).val();i += '0';
				if(data !== '') {data = data + '#';}
				data 		= data + ID + ',' + i;
			});
			
			var requestData = {
				action: 'categorifyAjaxUpdateFolderPosition',
				data: data,
				security: self.nonce,
			};
			
			jQuery.ajax({
				type: 'POST',
				url: self.ajaxurl,
				cache:true,
				data: requestData,
				success: function() {
					self.stopPreloader();
				},
				error: function(xhr, textStatus, errorThrown){
					console.log(errorThrown);
					console.log(textStatus);
					console.log(xhr);
				}
			});
			
			
		},
		extensionsForCategorify: function(){
			var self = this;
            jQuery.fn.extend({
				ccMoveCursorToEnd: function(){
					var $input = this;
					$input.trigger('focus');
					var val = $input.val();
					$input.val('').val(val);
					return $input;
				},
				refreshFoldersPosition: function(){
					return this.each(function () {
						var item 		= jQuery(this),
							depth 		= parseInt(item.menuItemDepth(), 10),
							parentDepth = depth - 1,
							parent 		= item.prevAll('.category_item_depth_' + parentDepth).first(),
							newParent 	= 0,
							current 	= item.find('.input_category_id').val();
						if (depth !== 0) {
							newParent 	= parent.find('.input_category_id').val();
						}
						self.refreshFolderPositionAjax(current, newParent);
					});	
				},
                menuItemDepth: function () {
                    var margin = self.isRTL ? this.eq(0).css('margin-right') : this.eq(0).css('margin-left');
                    return self.pxToDepth(margin && -1 != margin.indexOf('px') ? margin.slice(0, -2) : 0);
                },
                updateDepthClass: function (current, prev) {
                    return this.each(function(){
                        var t 	= jQuery(this);
                        prev 	= prev || t.menuItemDepth();
                        jQuery(this).removeClass('category_item_depth_' + prev).addClass('category_item_depth_' + current);
                    });
                },
                shiftDepthClass: function(change) {
                    return this.each(function () {
                        var t 			= jQuery(this),
                            depth 		= t.menuItemDepth(),
                            newDepth 	= parseInt(depth + change);

                        t.removeClass('category_item_depth_' + depth).addClass('category_item_depth_' + (newDepth));

                        if(newDepth === 0){
                            t.find('.is-submenu').hide();
                        }
                    });
                },
                childMenuItems: function () {
                    var result = jQuery();
                    this.each(function () {
                        var t = jQuery(this), depth = t.menuItemDepth(), next = t.next('li');
                        while (next.length && next.menuItemDepth() > depth || next.hasClass('new-wrapper')) {
                            result	= result.add(next);
                            next 	= next.next('li');
                        }
                    });
                    return result;
                },
                updateParentMenuItemDBId: function () {
                    return this.each(function () {
                        var item 		= jQuery(this),
                            input 		= item.find('.input_parent_id'),
                            depth 		= parseInt(item.menuItemDepth(), 10),
                            parentDepth = depth - 1,
                            parent 		= item.prevAll('.category_item_depth_' + parentDepth).first();
                        var newParent 	= 0;
                        if(parseInt(depth) !== 0){
                            newParent 	= parent.find('.input_category_id').val();
                        }
                        input.val(newParent);
                        if(parseInt(newParent) !== self.currentParent) {
                            jQuery.event.trigger({
                                type: 'CategorifyCoreTree_parent_changed',
                                new_parent: newParent,
                                id: item.find('.input_category_id').val(),
                            });
                            self.categorifyState.push(newParent);
							self.categorifyState = self.removeDuplicates(self.categorifyState); // array unique
                            localStorage.setItem("categorifyTreeState", self.categorifyState);
                        }
                        self.currentParent = null;
                    });
                },
            });
        },
		
		
		
		sortableCategories: function () {
			// to call all functions in this file
			var self = this;
			
			// depth variables
            var currentDepth = 0,
				originalDepth,
				minDepth,
				maxDepth,
                prev,
				next,
				prevBottom,
				nextThreshold,
				helperHeight,
				transport,
                menuEdge = self.menuList.offset().left,
                body = jQuery('body'),
				maxChildDepth,
                menuMaxDepth = initialMenuMaxDepth();

			
            menuEdge += self.isRTL ? self.menuList.width() : 0;
			
            self.menuList.sortable({
                handle: '.cat_item a',
                placeholder: 'categorify-sortable-placeholder',
                items: '> *',
                start: function (e, ui) {
                    var height,
						width,
						parent,
						children,
						tempHolder;

                    // handle placement for rtl orientation
                    if (self.isRTL){
						ui.item[0].style.right = 'auto';
					}

                    transport 		= ui.item.children('.categorify_be_placeholder');

                    // Set depths. currentDepth must be set before children are located.
                    originalDepth 	= ui.item.menuItemDepth();
                    updateCurrentDepth(ui, originalDepth);

                    // Attach child elements to parent
                    // Skip the placeholder
                    parent 			= (ui.item.next()[0] == ui.placeholder[0]) ? ui.item.next() : ui.item;
                    children 		= parent.childMenuItems();
                   	
                    transport.append(children);

                    // Update the height of the placeholder to match the moving item.
                    height 			= transport.outerHeight();
                    // If there are children, account for distance between top of children and parent
                    height 			+= (height > 0) ? (ui.placeholder.css('margin-top').slice(0, -2) * 1) : 0;
                    height 			+= ui.helper.outerHeight();
                    helperHeight 	= height;
                    height 			-= 2; // Subtract 2 for borders
                    ui.placeholder.height(height);

                    // Update the width of the placeholder to match the moving item.
                    maxChildDepth 	= originalDepth;
                    children.each(function () {
                        var depth 		= jQuery(this).menuItemDepth();
                        maxChildDepth 	= (depth > maxChildDepth) ? depth : maxChildDepth;
                    });
                    width 			= ui.helper.find('.cat_item a').outerWidth(); // Get original width
                    width 			+= self.depthToPx(maxChildDepth - originalDepth); // Account for children
                    width 			-= 2; // Subtract 2 for borders
                    ui.placeholder.width(width);

                    // Update the list of menu items.
                    tempHolder 		= ui.placeholder.next('.category_item');
                    tempHolder.css('margin-top', helperHeight + 'px'); // Set the margin to absorb the placeholder
                    ui.placeholder.detach(); // detach or jQuery UI will think the placeholder is a menu item
                    jQuery(this).sortable('refresh'); // The children aren't sortable. We should let jQ UI know.
                    ui.item.after(ui.placeholder); // reattach the placeholder.
                    tempHolder.css('margin-top', 0); // reset the margin

                    // Now that the element is complete, we can update...
                    updateSharedVars(ui);
                    self.currentParent = ui.item.find('.input_parent_id').val();

                    self.oldParent 	= ui.item.prev();
                },
                stop: function (e, ui) {
                    var children,
						subMenuTitle,
                        depthChange 	= currentDepth - originalDepth;

                    // Return child elements to the list
                    if(jQuery('.children_of_' + ui.item.attr('id')).length) {
                        jQuery('.children_of_' + ui.item.attr('id')).insertAfter(ui.item);
                    }else{
                        children 		= transport.children().insertAfter(ui.item);
                    }
                    jQuery.each(jQuery('.new-wrapper'), function (index, el) {
                        jQuery(el).insertAfter('#cc_category_item_' + jQuery(el).attr('class').match(/children_of_cc_category_item_(\d)+/)[1]);
                    });
                    // Add "sub menu" description
                    subMenuTitle 		= ui.item.find('.item-title .is-submenu');
                    if(currentDepth > 0){
						subMenuTitle.show();
					}else{
						subMenuTitle.hide();
					}
                      
                    // Update depth classes
                    if(parseInt(depthChange) !== 0){
                        ui.item.updateDepthClass(currentDepth);

                        if(jQuery('.children_of_' + ui.item.attr('id')).length) {
                            children = jQuery('.children_of_' + ui.item.attr('id')).find('.category_item');
                            children.shiftDepthClass(depthChange);
                        }else{
                            children.shiftDepthClass(depthChange);
                        }
                        updateMenuMaxDepth(depthChange);
                    }

                    // Register a change
                    self.registerChange();
                    // Update the item data.
                    ui.item.updateParentMenuItemDBId();

                    // address sortable's incorrectly-calculated top in opera
                    ui.item[0].style.top = 0;

                    // handle drop placement for rtl orientation
                    if (self.isRTL) {
                        ui.item[0].style.left = 'auto';
                        ui.item[0].style.right = 0;
                    }

                    //finally, remove or add icon for oldParent
                    if(self.oldParent.childMenuItems().length === 0) {
                        self.oldParent.removeClass('has_children open');
                    }else{
                        self.oldParent.addClass('has_children open');
                    }
                    //remove or add icon for new_parent
                    var new_parent = jQuery('#cc_category_item_' + ui.item.find('.input_parent_id').val());
                    if (new_parent.childMenuItems().length > 0) {
                        new_parent.addClass('has_children open');
                    }
                    if (new_parent.hasClass('close')) {
                        new_parent.find('.cc_dropdown').trigger('click');
                    }
                    ui.item.refreshFoldersPosition();
					
                },
                change: function (e, ui) {
                    // Make sure the placeholder is inside the menu.
                    // Otherwise fix it, or we're in trouble.
                    if (!ui.placeholder.parent().hasClass('cc_categorify_category_list')){
						(prev.length) ? prev.after(ui.placeholder) : self.menuList.prepend(ui.placeholder);
					}
                    updateSharedVars(ui);
                },
                sort: function (e, ui) {
                    var offset 	= ui.helper.offset(),
                        edge 	= self.isRTL ? offset.left + ui.helper.width() : offset.left,
                        depth 	= self.negateIfRTL * self.pxToDepth(edge - menuEdge);

                    // Check and correct if depth is not within range.
                    // Also, if the dragged element is dragged upwards over
                    // an item, shift the placeholder to a child position.
                    if(depth > maxDepth || offset.top < prevBottom) {
                        depth 	= maxDepth;
                    }else if(depth < minDepth){
                        depth 	= minDepth;
                    }

                    if(depth != currentDepth){
						updateCurrentDepth(ui, depth);
					}
                        

                    // If we overlap the next element, manually shift downwards
                    if (nextThreshold && offset.top + helperHeight > nextThreshold) {
                        next.after(ui.placeholder);
                        updateSharedVars(ui);
                        jQuery(this).sortable('refreshPositions');
                    }
                }
            });

            function updateSharedVars(ui) {
                var depth;

                prev = ui.placeholder.prev('.category_item');
                next = ui.placeholder.next('.category_item');

                // Make sure we don't select the moving item.
                if (prev[0] == ui.item[0]){prev = prev.prev('.category_item');}
                if (next[0] == ui.item[0]){next = next.next('.category_item');}

                prevBottom 		= (prev.length) ? prev.offset().top + prev.height() : 0;
                nextThreshold 	= (next.length) ? next.offset().top + next.height() / 3 : 0;
                minDepth 		= (next.length) ? next.menuItemDepth() : 0;

                if (prev.length){
					maxDepth 	= ((depth = prev.menuItemDepth() + 1) > self.globalMaxDepth) ? self.globalMaxDepth : depth;
				}else{
					maxDepth 	= 0;
				}
            }

            function updateCurrentDepth(ui, depth) {
                ui.placeholder.updateDepthClass(depth, currentDepth);
                currentDepth 	= depth;
            }

            function initialMenuMaxDepth() {
                if (!body[0].className){return 0;}
                var match 		= body[0].className.match(/menu-max-depth-(\d+)/);
                return match && match[1] ? parseInt(match[1], 10) : 0;
            }

            function updateMenuMaxDepth(depthChange) {
                var depth,
					newDepth = menuMaxDepth;
                if (depthChange == 0) {
                    return;
                }else if(depthChange > 0) {
                    depth = maxChildDepth + depthChange;
                    if (depth > menuMaxDepth){
						newDepth = depth;
					}
                } else if (depthChange < 0 && maxChildDepth == menuMaxDepth) {
                    while (!jQuery('.category_item_depth_' + newDepth, self.menuList).length && newDepth > 0){
						newDepth--;
					}
                }
                // Update the depth class.
                body.removeClass('menu-max-depth-' + menuMaxDepth).addClass('menu-max-depth-' + newDepth);
                menuMaxDepth = newDepth;
            }
        },
		
		addNewFolderByContextMenu: function(){
			var self = this;
			jQuery('.cc_categorify_contextmenu ul li > span.cc_add').on('click',function(e){
				e.preventDefault();
				e.stopPropagation();
				jQuery('.cc_categorify_contextmenu').removeClass('popup_opened');
				jQuery('ul.cc_categorify_category_list li').removeClass('active_contextmenu');
				self.insertCategoryToActive();
				
				self.magicSVG();
				self.tripleActionsOnCategories();
				// commented
//				self.scrollToElement(jQuery('.folder-input'),800);
//				setTimeout(function(){
//					jQuery('.folder-input').ccMoveCursorToEnd();
//				},800);
				jQuery('.folder-input').ccMoveCursorToEnd();
			});
		},
		
		addNewFolderByTopButton: function(){
			var self			= this;
			jQuery('.cc_categorify_header .header_top a').on('click',function(e){
				e.preventDefault();
				e.stopPropagation();
				
				
				
				jQuery('.cc_categorify_contextmenu').removeClass('popup_opened');
				jQuery('ul.cc_categorify_category_list li').removeClass('active_contextmenu');
				
				// check if button already clicked
				if(jQuery('.folder-input').length) {
					
					jQuery('.folder-input').ccMoveCursorToEnd();
					return false;
				}else{
					
					jQuery('ul.cc_categorify_category_list li').removeClass('rename');
				}
				
				// if selected current folder remove it from localStorage
				if(!jQuery('#cc_category_item_' + self.categorifyCurrentFolder).length){
					self.categorifyCurrentFolder = null;
					localStorage.removeItem('categorifyCurrentFolder');
				}
				
				if(!jQuery('.active#cc_category_item_' + self.categorifyCurrentFolder).length){
					self.categorifyCurrentFolder = null;
				}
				
				if (self.categorifyCurrentFolder == null) {
					
					
					jQuery('#categorify_be_folder_list').append(self.newCategoryFormTemplate());
					self.magicSVG();
					self.tripleActionsOnCategories();
					
					
					
					// commented
//					self.scrollToElement(jQuery('.folder-input'),800); 
//					setTimeout(function(){
//						jQuery('.folder-input').ccMoveCursorToEnd();
//					},800);
					jQuery('.folder-input').ccMoveCursorToEnd();
					
					
				}else{
					self.insertCategoryToActive();	
					self.magicSVG();
					self.tripleActionsOnCategories();	
					// commented
//					self.scrollToElement(jQuery('.folder-input'),800);
//					setTimeout(function(){
//						jQuery('.folder-input').ccMoveCursorToEnd();
//					},800);
					
					jQuery('.folder-input').ccMoveCursorToEnd();
				}
				
				return false;
			});
		},
		
		insertCategoryToActive: function(){
			var self				= this,
				parentElement 		= jQuery('#cc_category_item_' + self.categorifyCurrentFolder),
				depth 				= parentElement.menuItemDepth(),
				parentDepth 		= depth;
				depth 				= parseInt(depth) + 1;
			
			if (depth >= (self.globalMaxDepth + 1)) {
				alert('The max Depth is: ' + self.globalMaxDepth);
			}else{
				// открыть папку, если она закрыта
				var item 			= jQuery('#cc_category_item_' + self.categorifyCurrentFolder);
				var openerButton 	= item.find('.cc_dropdown');
				if (item.hasClass('close')){
					openerButton.trigger('click');
				}
				// вставить после последнего child элемента
				var element 		= jQuery('[class="input_parent_id"][value="' + self.categorifyCurrentFolder + '"]');
				if (element.length === 0) {
					jQuery(self.newCategoryFormTemplate('category_item_depth_' + depth)).insertAfter(jQuery('#cc_category_item_' + self.categorifyCurrentFolder));
				}else{
					var li 			= jQuery('#cc_category_item_' + self.categorifyCurrentFolder),
                     allNextLi 		= li.childMenuItems();
					jQuery.each(allNextLi, function (i, e) {
						var depthh = jQuery(e).menuItemDepth();

						if (depthh <= parentDepth) {
							jQuery(self.newCategoryFormTemplate('category_item_depth_' + depth)).insertAfter(jQuery(e).prev());
						} else if (i === (allNextLi.length - 1)) {
							jQuery(self.newCategoryFormTemplate('category_item_depth_' + depth)).insertAfter(jQuery(e));
						}

						
					});
				}
			}
				
		},
		
		cancelAddingNewCategory: function(){
			var self	= this;
			jQuery('ul.cc_categorify_category_list .cat_item a').off().stop(true,true).on('click', function (event) {
                event.preventDefault();
                var button = jQuery(this);
				
                self.setActiveCategory(button);
            });
			
			jQuery('.cc_categorify_header .header_bottom ul li a').off().stop(true,true).on('click', function (event) {
                event.preventDefault();
                var button = jQuery(this);
                self.setActiveCategory(button);
				
				
				
            });
			
			 jQuery(document).on('click', '.add-new-folder-cancel', function (event) {
                event.preventDefault();
				 jQuery('li.new_category').fadeOut(300, function(){ jQuery(this).remove();});
            });	
		},
		
		
		addCategoryAjaxProcess: function(data,parent,depth,parentDepth){
			var self				= this,			
				fnQueriedObj 		= JSON.parse(data),
				termID				= fnQueriedObj.termID,
				termName			= fnQueriedObj.termName,
				newOption 			= jQuery("<option></option>").attr("value", termID).text(termName + (0)),
				newCategoryHTML 	= self.newCategoryTemplate(termID, termName, parent, depth);
			
			
			jQuery('li.category_item.new_category').remove();
			
			var currentFolder		= self.categorifyCurrentFolder;
			
			if(currentFolder === null) {
				// append into primary folder list as last child
				jQuery('#categorify_be_folder_list').append(newCategoryHTML);
			}else{
				// append to the last child
				var e = jQuery('[class="input_parent_id"][value="' + currentFolder + '"]');
				if(e.length === 0){
					jQuery(newCategoryHTML).insertAfter(jQuery('#cc_category_item_' + currentFolder));
					jQuery('#cc_category_item_' + currentFolder).addClass('has_children open');
				}else{
					var li 					= jQuery('#cc_category_item_' + currentFolder),
						allNextLi 			= li.nextAll(),
						penultimateLength 	= parseInt(allNextLi.length - 1);
					jQuery.each(allNextLi, function(i,e) {
						var depthh 	= jQuery(e).menuItemDepth();
						if(depthh <= parentDepth){
							jQuery(newCategoryHTML).insertAfter(jQuery(e).prev());
							return false;
						}else if(i === penultimateLength){
							jQuery(newCategoryHTML).insertAfter(jQuery(e));
						}
					});

				}
			}
			self.magicSVG();
			self.callMovingPopup();
			categorifyFolders.push({ folderID: termID, folderName: termName, term_count: 0 });
			self.refreshBackbone('add');
			jQuery(".wpmediacategory-filter").append(newOption);
			self.cancelAddingNewCategory();
			
			
			CategorifyFilter.dragAndDropMedia(); // GRID VIEW
			self.dragDropListMode(); 			 // LIST MODE
			
			// back

			self.updateFolderPosition();
			if (parent && self.categorifyState.indexOf(parent.toString()) < 0) {
				self.categorifyState.push(parent);
				localStorage.setItem("categorifyTreeState", self.categorifyState);
			}


			self.stopPreloader();
			self.stopAction = 0;
		},
		
		appendSidebarToBody: function(){
			'use strict';
			var html 	= '';
			if(jQuery(".cc_categorify_temporary").length){
				html += jQuery(".cc_categorify_temporary").html();
				jQuery(".cc_categorify_temporary").remove();
				jQuery("#wpbody .wrap").wrapAll('<div class="wrap-all"></div>');
			}
			
			if(jQuery('body').hasClass('post-type-attachment')){
				jQuery('body').addClass('categorify_ready');	
			}
			
			jQuery("#wpbody #wpbody-content").before(html);
			
		},
		
		
		
		callMovingPopup: function(){
			'use strict';
			
			var self 		= this;
			var content 	= jQuery('.cc_categorify_contextmenu');
			var li 			= jQuery('ul.cc_categorify_category_list li');
			
			
			li.on('contextmenu', function(e){
				
				e.preventDefault();
				e.stopPropagation();
				
				// if has new category action remove this section
				jQuery('li.new_category').remove();
				li.removeClass('active_contextmenu');
				
				// variables		
				var currentItem 	= jQuery(this);
				var ID 				= currentItem.data('id');
				currentItem.addClass('active_contextmenu');
				
				
				coreFilter.categoryID 			= ID;
				self.categorifyCurrentFolder 	= ID;

				
				var lastName	= currentItem.find('.cc_title').text();
				currentItem.find('input').val(lastName);
				
				var cH		= jQuery('.cc_categorify_contextmenu').outerHeight();
				var top		= e.pageY;
				var posX 	= e.pageY + cH;
				var h 		= jQuery(window).height();
				
				if(posX > h+document.documentElement.scrollTop){top = top - cH;}
				
				var topCoord		= top - document.documentElement.scrollTop;
				var leftCoord		= e.pageX;
				// open myPanel and set position
				content.addClass('popup_opened');
				
				content.css({left:leftCoord,top:topCoord});

				
				self.tripleActionsOnCategories(currentItem);
				
				
				
			});
			
			
			
			jQuery(window).on('click', function(){
				if(jQuery('li.new_category').length){
					jQuery('li.new_category').remove();
				}
				jQuery('.cc_categorify_contextmenu').removeClass('popup_opened');
				jQuery('ul.cc_categorify_category_list li').removeClass('active_contextmenu');
			});	
		},
		
		
	
		
		tripleActionsOnCategories: function(currentItem){
			'use strict';
			var content = jQuery('.cc_categorify_contextmenu');
			var self	= this;
			
			
			/* action #1: add new category via popup */
			content.find('.cc_add').on('click',function(e){
				e.preventDefault();
				e.stopPropagation();

			});
			/* action #2: rename existing category via popup */
			content.find('.cc_rename').off().on('click',function(e){
				e.preventDefault();
				e.stopPropagation();
				
				jQuery('ul.cc_categorify_category_list li').removeClass('rename');
				currentItem.addClass('rename');
				currentItem.find('input').ccMoveCursorToEnd();
				content.removeClass('popup_opened');
				jQuery('ul.cc_categorify_category_list li').removeClass('active_contextmenu');
				
				
			});
			/* action #3: delete existing category via popup */
			content.find('.cc_delete').off().on('click',function(e){
				e.preventDefault();
				e.stopPropagation();
				
				
				if(self.checkIfCategoryHasChildren(currentItem)){
					self.callDeleteErrorPopup();
					
				}else{	
					self.callDeleteConfirmPopup();
				}
				content.removeClass('popup_opened');
				jQuery('ul.cc_categorify_category_list li').removeClass('active_contextmenu');
			});
			/* action #4: clear existing category via popup */
			content.find('.cc_clear').off().on('click',function(e){
				e.preventDefault();
				e.stopPropagation();
				
				self.callClearConfirmPopup(currentItem);
				content.removeClass('popup_opened');
				jQuery('ul.cc_categorify_category_list li').removeClass('active_contextmenu');
			});
			
			/* action #2.2: cancel renaming category */
			jQuery('ul.cc_categorify_category_list .cc_cancel').off().on('click',function(e){
				e.preventDefault();
				e.stopPropagation();
				
				var renamedLi	= jQuery('ul.cc_categorify_category_list li.rename');
				if(!renamedLi.data('id')){
					renamedLi.remove();
				}else{
					var lastName	= renamedLi.find('.cc_title').text();
					renamedLi.find('input').val(lastName);
					jQuery('ul.cc_categorify_category_list li').removeClass('rename');
				}
			});
			
			
			/* action #2.3: apply renaming category */
			jQuery('ul.cc_categorify_category_list .cc_apply').off().on('click', function(e){

				e.preventDefault();
				e.stopPropagation();
				
				
				var renamedLi	= jQuery('ul.cc_categorify_category_list li.rename');
				var lastName	= renamedLi.find('.cc_title').text();
				var newName		= renamedLi.find('.cc_changer input').val();
				var ID 			= renamedLi.data('id');
				
				// check category name
				if(self.ifStringIsEmpty(newName)){
					jQuery.iaoAlert({
						msg: self.alertWarningIcon + self.enterName,
						type: "success",
						alertTime: self.iaoAlertTime,
						position: self.iaoAlertPosition,
					});
					self.magicSVG();
				}else if(newName === lastName){
					renamedLi.removeClass('rename');
				}else{
					if(ID){
						self.renameExistingCategory(ID,newName,renamedLi);
					}else{
						self.insertNewCategory(newName);
					}
				}
				
			});
			
			
			jQuery(window).on('click', function(event){
				if(!jQuery(event.target).closest('ul.cc_categorify_category_list li.rename').length){
					if(jQuery('ul.cc_categorify_category_list li.rename').length){
						var renamedLi	= jQuery('ul.cc_categorify_category_list li.rename');
						var lastName	= renamedLi.find('.cc_title').text();
						renamedLi.find('.cc_changer input').val(lastName);
					}
					jQuery('ul.cc_categorify_category_list li').removeClass('rename');
				}
			});
			
			if(self.isPremium == 1){
				jQuery(document).on('keyup', function(event) {
					if(event.which === 13 && jQuery('ul.cc_categorify_category_list li.rename').length && self.stopAction === 0) // enter
					{
						jQuery('ul.cc_categorify_category_list li.rename .cc_apply').trigger('click');
						self.stopAction = 1;
					}
					if(event.which === 27 && jQuery('ul.cc_categorify_category_list li.rename').length) // esc
					{
						jQuery('ul.cc_categorify_category_list .cc_cancel').trigger('click');
					}
				});
			}
			
			
		},
		insertNewCategory: function(categoryName){
			var parent			= 0;
			var self			= this;
			if(self.ifStringIsEmpty(categoryName)){
				jQuery.iaoAlert({
					msg: self.alertWarningIcon + self.enterName,
					type: "success",
					alertTime: self.iaoAlertTime,
					position: self.iaoAlertPosition,
				});
				self.magicSVG();
			}else{

				var depth 			= 0,
					parentElement 	= jQuery('#cc_category_item_' + parent),
					parentDepth 	= 0;
				if (self.categorifyCurrentFolder !== null) {
					parent 			= self.categorifyCurrentFolder;
					//find depth
					parentElement 	= jQuery('#cc_category_item_' + parent);
					depth 			= parentElement.menuItemDepth();
					parentDepth 	= depth;
					depth 			= parseInt(depth) + 1;
					//end finding depth
				}

				self.startPreloader();

				var requestData 	= {
					action: 'categorifyAjaxAddCategory',
					categoryName: categoryName,
					parent: parent,
					security: self.nonce,
				};

				jQuery.ajax({
					type: 'POST',
					url: self.ajaxurl,
					cache:true,
					data: requestData,
					success: function(data) {
						self.addCategoryAjaxProcess(data,parent,depth,parentDepth);
						self.stopPreloader();
						self.stopAction = 0;
					},
					error: function(xhr, textStatus, errorThrown){
						console.log(errorThrown);
						console.log(textStatus);
						console.log(xhr);
					}
				});
			}
			
		},
		
		renameExistingCategory: function(ID,title,li){
			var self 			= this;
			
			self.startPreloader();
			
			var requestData 	= {
				action: 'categorifyAjaxRenameCategory',
				categoryID: ID,
				categoryTitle: title,
				security: self.nonce,
			};

			jQuery.ajax({
				type: 'POST',
				url: self.ajaxurl,
				cache:true,
				data: requestData,
				success: function(data) {
					self.renameExistingCategoryAjaxProcess(data,li);
					self.stopAction = 0;
				},
				error: function(xhr, textStatus, errorThrown){
					console.log(errorThrown);
					console.log(textStatus);
					console.log(xhr);
					self.stopPreloader();
				}
			});	
		},
		
		renameExistingCategoryAjaxProcess: function(data,li){
			var self			= this;
			var fnQueriedObj 	= JSON.parse(data);
			if(fnQueriedObj.error === 'no'){
				li.find('.cc_title').text(fnQueriedObj.title);
				li.find('.cc_changer input').val(fnQueriedObj.title);
				li.removeClass('rename');
			}else{
				jQuery.iaoAlert({
					msg: self.alertWarningIcon + self.slugError,
					type: "success",
					alertTime: self.iaoAlertTime,
					position: self.iaoAlertPosition,
				});
				self.magicSVG();
			}
			self.stopPreloader();
			self.stopAction = 0;
		},
		
		
		callDeleteErrorPopup: function(){
			var self			= this;
			
			jQuery.iaoAlert({
				msg: self.alertWarningIcon + self.hasSubFolder,
				type: "success",
				alertTime: self.iaoAlertTime,
				position: self.iaoAlertPosition,
			});
			self.magicSVG();
		},
		
		callRenameErrorPopup: function(){
			var self			= this;
			
			jQuery.iaoAlert({
				msg: self.alertWarningIcon + self.finishRename,
				type: "success",
				alertTime: self.iaoAlertTime,
				position: self.iaoAlertPosition,
			});
			self.magicSVG();
		},
		
		
		callClearConfirmPopup: function(currentItem){
			var self			= this;
			var HTML			= self.confirmToClearDOM();
							
							
			jQuery('#categorify_be_confirm').remove();
			jQuery('body').prepend(HTML);
			
			var confirm 		= jQuery('#categorify_be_confirm');
			confirm.addClass('opened folder_clear');
			confirm				= jQuery('#categorify_be_confirm.folder_clear');
			var yes				= confirm.find('a.yes');
			var no				= confirm.find('a.no');

			
			yes.off().on('click', function(e){
				e.preventDefault();
				self.clearCategoryAjax(confirm, currentItem);
				return false;
			});
			
			no.on('click', function(){
				confirm.removeClass();
				confirm.remove();
				return false;
			});
		},
		
		clearCategoryAjax: function(confirm, currentItem){
			var self 		= this;
			var categoryID	= coreFilter.categoryID;
			self.startPreloader();
			
			var requestData = {
				action: 'categorifyAjaxClearCategory',
				categoryID: categoryID,
				security: self.nonce,
			};

			jQuery.ajax({
				type: 'POST',
				url: self.ajaxurl,
				cache: false,
				data: requestData,
				success: function(data) {
					var fnQueriedObj 	= JSON.parse(data),
						count			= fnQueriedObj.count,
						error			= fnQueriedObj.error;
					if(error === 'no'){
						self.clearCategoryAjaxProcess(categoryID,count,confirm, currentItem);
					}
					self.stopPreloader();
				},
				error: function(xhr, textStatus, errorThrown){
					console.log(errorThrown);
					console.log(textStatus);
					console.log(xhr);
					self.stopPreloader();
				}
			});
			
		},
		
		clearCategoryAjaxProcess: function(categoryID,count,confirm, currentItem){
			var self				= this;
			currentItem.find('.cc_count').text('');
			self.refreshUncategorizedCount(count);
			
			confirm.remove();
		},

		callDeleteConfirmPopup: function(){
			var self			= this;
			var html			= self.confirmToDeleteDOM();
							
							
			jQuery('#categorify_be_confirm').remove();
			jQuery('body').prepend(html);
			
			var confirm 		= jQuery('#categorify_be_confirm');
			confirm.addClass('opened folder_delete');
			var yes				= confirm.find('a.yes');
			var no				= confirm.find('a.no');

			
			yes.off().on('click', function (e) {
				e.preventDefault();
				self.deleteCategoryAjax(confirm);
				return false;
			});
			no.on('click', function () {
				confirm.removeClass();
				confirm.remove();
				return false;
			});
		},
		
		deleteCategoryAjax: function(confirm){
			var self = this;
			self.startPreloader();
			var categoryID	= coreFilter.categoryID;
			var requestData = {
				action: 'categorifyAjaxDeleteCategory',
				categoryID: categoryID,
				security: self.nonce,
			};

			jQuery.ajax({
				type: 'POST',
				url: self.ajaxurl,
				cache: true,
				data: requestData,
				success: function(data) {
					var fnQueriedObj = JSON.parse(data),
						count			= fnQueriedObj.count,
						error			= fnQueriedObj.error;
					if(error === 'no'){
						self.deleteCategoryAjaxProcess(categoryID,count);
						jQuery('ul.cc_categorify_category_list li.active').removeClass('active');
						confirm.remove();
					}
					self.stopPreloader();
				},
				error: function(xhr, textStatus, errorThrown){
					console.log(errorThrown);
					console.log(textStatus);
					console.log(xhr);
					self.stopPreloader();
				}
			});
			
		},
		deleteCategoryAjaxProcess: function(categoryID,count){
			var self		= this;
			self.refreshUncategorizedCount(count);
			localStorage.removeItem('categorifyCurrentFolder');
			var parentID 	= jQuery('#cc_category_item_' + categoryID).find('.input_parent_id').val();


			jQuery('#cc_category_item_' + categoryID).remove();
			
			// если есть родитель у удаленной категории, и если у него не осталось child элементы, убрать треугольник
			if(parentID){
				if(jQuery("#cc_category_item_" + parentID).childMenuItems().length === 0){
					jQuery("#cc_category_item_" + parentID).removeClass('open close');
				}
			}
			coreFilter.categoryID = '';
			self.categorifyCurrentFolder = 'all';
		},
		refreshUncategorizedCount: function(extraCount){
			var count 	= jQuery('.cc_categorify_header .cc_uncategorized a .cc_count').html();
			count 		= parseInt(count) + parseInt(extraCount);
			jQuery('.cc_categorify_header .cc_uncategorized a .cc_count').html(count);
		},
		/********************************************************************************************************/
		/************************************** Все вспомогательные функции *************************************/
		/********************************************************************************************************/
		confirmToClearDOM: function(){
			var self = this;
			var html = '<div id="categorify_be_confirm">';
					html += '<div class="confirm_inner">';
						html += '<div class="desc_holder">';
							html += '<h3>' + self.areYouSure + '</h3>';
							html += '<p>' + self.willBeMovedToUncategorized + '</p>';
						html += '</div>';
						html += '<div class="links_holder">';
							html += '<a class="yes" href="#">' + self.clearText + '</a>';
							html += '<a class="no" href="#">' + self.cancelText + '</a>';
						html += '</div>';
					html += '</div>';
				html += '</div>';
			return html;
		},
		confirmToDeleteDOM: function(){
			var self = this;
			var html = '<div id="categorify_be_confirm">';
					html += '<div class="confirm_inner">';
						html += '<div class="desc_holder">';
							html += '<h3>' + self.areYouSure + '</h3>';
							html += '<p>' + self.willBeMovedToUncategorized + '</p>';
						html += '</div>';
						html += '<div class="links_holder">';
							html += '<a class="yes" href="#">' + self.deleteText + '</a>';
							html += '<a class="no" href="#">' + self.cancelText + '</a>';
						html += '</div>';
					html += '</div>';
				html += '</div>';
			return html;
		},
		/* проверить, есть ли child элементы у выбранней категории */
		checkIfCategoryHasChildren: function(li){
			if(jQuery(li).next().find(".input_parent_id").length && jQuery(li).next().find(".input_parent_id").val() == coreFilter.categoryID){
				return true;
			}else{
				return false;
			}
		},
		/* преобразование изображений в SVG */
		magicSVG: function(){
			jQuery('img.categorify_be_svg').each(function(){
				var jQueryimg 		= jQuery(this);
				var imgClass	= jQueryimg.attr('class');
				var imgURL		= jQueryimg.attr('src');
				jQuery.get(imgURL, function(data) {
					var jQuerysvg = jQuery(data).find('svg');
					if(typeof imgClass !== 'undefined') {jQuerysvg = jQuerysvg.attr('class', imgClass+' replaced-svg');}
					jQuerysvg = jQuerysvg.removeAttr('xmlns:a');
					jQueryimg.replaceWith(jQuerysvg);
				}, 'xml');
			});
		},
		/* залить в body контекстнее меню (new folder, rename, delete) */
		appendMovingPopup: function(){
			var self = this;
			var html = '';
			html += '<div class="cc_categorify_contextmenu">';
				html += '<ul>';
					
			
				if(self.plugin == 'Categorify'){
					html += '<li><span class="cc_add"><span class="cc_text">'+self.newFolderText+'</span><span class="cc_icon"></span></span></li>';
					html += '<li><span class="cc_rename">'+self.renameText+'</span></li>';
					html += '<li><span class="cc_clear">'+self.clearMediaText+'</span></li>';
				}
					html += '<li><span class="cc_delete">'+self.deleteText+'</span></li>';
				html += '</ul>';
			html += '</div>';
			jQuery('body').append(html);
		},
		/* создание HTML структуру для новой категории */
		newCategoryTemplate: function(ID, title, parent, depth){
			var self = this;
			var html = '';
			html += '<li id="cc_category_item_'+ID+'" data-id="'+ID+'" class="category_item category_item_depth_' + depth + ' parent_'+ID+'" data-parent-id="parent_'+parent+'">';
					html += '<div class="cat_item">';
						html += '<span class="cc_dropdown"></span>';
						html += '<a href="#">';
							html += '<span class="cc_icon_wrap">';
								html += '<span class="cc_icon cc_icon_folder">'+self.getIcon('folder')+'</span>';
								html += '<span class="cc_icon cc_icon_subfolder">'+self.getIcon('folder-plus')+'</span>';
								html += '<span class="cc_icon cc_icon_subfolderopen">'+self.getIcon('down-arrow')+'</span>';
							html += '</span>';
							html += '<span class="cc_title">'+title+'</span>';
							html += '<span class="cc_count"></span>';
						html += '</a>';
						html += '<div class="cc_changer"><div><input type="text" value="'+title+'" /></div></div>';
						//html += self.dragButton();
						html += self.applyCancelButtons();
					html += '</div>';
					html += self.extraHTMLForNewCategory(ID,parent);
				html += '</li>';
			return html;
		},
		/* создание HMTL формы для заполнения новой категории */
		newCategoryFormTemplate: function (extraClass) {
			var self = this;
            if(typeof extraClass === 'undefined'){extraClass = '';}
			var html = '';
			html += '<li class="category_item active rename new_category ' + extraClass + '">';
					html += '<div class="cat_item">';
						html += '<span class="cc_dropdown"></span>';
						html += '<a href="#">';
							html += '<span class="cc_icon_wrap">';
								html += '<span class="cc_icon cc_icon_folder">'+self.getIcon('folder')+'</span>';
							html += '</span>';
							html += '<span class="cc_title"></span>';
							html += '<span class="cc_count"></span>';
						html += '</a>';
						html += '<div class="cc_changer"><div><input class="folder-input" type="text" value="" /></div></div>';
						//html += self.dragButton();
						html += self.applyCancelButtons();
					html += '</div>';
				html += '</li>';
			//html = '<br /> Hello';
			return html;
        },
		extraHTMLForNewCategory: function(ID,parentID){
			var html = '';
			html += '<ul class="categorify_be_placeholder"></ul>';
			html += '<input class="input_category_id" type="hidden" value="' + ID + '">';
			html += '<input class="input_parent_id" type="hidden" value="' + parentID + '">';
			return html;
		},
		dragButton: function(){
			return '<span class="cc_drag"><span></span></span>';
		},
		applyCancelButtons: function(){
			var self = this;
			var html = '';
			
			html += '<div class="cc_btns">';
				html += '<span class="cc_apply">'+self.getIcon('check')+'<span class="cc_tooltip">'+self.confirmText+'</span></span>';
				html += '<span class="cc_cancel">'+self.getIcon('close')+'<span class="cc_tooltip">'+self.cancelText+'</span></span>';
			html += '</div>';
			
			return html;
		},
		getIcon: function(icon){
			var self = this;
			return 	'<img class="categorify_be_svg" src="' + self.pluginURL + '/assets/img/'+icon+'.svg" />';
		},
		/* преобразование depth в пиксели */
		depthToPx: function (depth) {
            return depth * this.menuItemDepthPerLevel;
        },
		/* преобразование пиксели обратно в deoth */		
        pxToDepth: function (px) {
            return Math.floor(px / this.menuItemDepthPerLevel);
        },
		/* !!!!!!!!!!!! изменить регистр */
		registerChange: function () {
            this.menusChanged = true;
        },
		/* создать уникальный массив */
		removeDuplicates: function(array) {
			var uniqueNames = [];
			jQuery.each(array, function(i, el){
				el = el.toString();
				if(el !== '0' && (jQuery.inArray(el, uniqueNames) === -1)){uniqueNames.push(el);}
			});
			return uniqueNames;
		},
		/* начать preloader */
		startPreloader: function(){
			jQuery('.categorify_be_loader').addClass('active');
		},
		/* остановить preloader */
		stopPreloader: function(){
			jQuery('.categorify_be_loader').removeClass('active');
		},
		/* анимациооный скролл к элементу */
		scrollToElement: function(element,speed){
			jQuery([document.documentElement, document.body]).animate({
				scrollTop: element.offset().top
			}, speed);
		},
		
		setActiveCategory: function (element) {
			
			var self = this;
			
			jQuery('.cc_categorify_header .header_bottom ul li').removeClass('active');
			self.menuList.find('li').removeClass('active');
			element.closest('li').addClass('active');
			self.categorifyCurrentFolder 	= element.closest('li').attr('data-id');
			var currentFolder				= self.categorifyCurrentFolder;
			
				
			if(self.isListMode()){
				if(parseInt(self.categorifyCurrentFolder) !== -1){
					self.categorifyIsListNoFolder = 0;
					localStorage.setItem('categorifyIsListNoFolder', self.categorifyIsListNoFolder);
				}
				
				setTimeout(function(){
					var catIdInSelect = jQuery('.wpmediacategory-filter').val();
					jQuery('.wpmediacategory-filter').val(currentFolder);
					jQuery('.wpmediacategory-filter').trigger('change');
					
					var firstTime = localStorage.getItem('firstTime');

					if(catIdInSelect != currentFolder){firstTime = 1;}
					
					if(firstTime == 1)
					{
						if(currentFolder == 'all'){currentFolder = '';}
						jQuery('select[name="cc_categorify_folder"]').val(currentFolder);
						jQuery('select[name="cc_categorify_folder"]').trigger('change');
						jQuery('.wp-admin.upload-php #posts-filter .filter-items input[name="filter_action"]').trigger("click");
						localStorage.setItem('categorifyCurrentFolder', currentFolder);
						localStorage.setItem('firstTime', 0);	   
					}
					
				}, 10);
				
				
			}else{
				self.startPreloader();
				
				jQuery('.wpmediacategory-filter').val(currentFolder);
				jQuery('.wpmediacategory-filter').trigger('change');
				
				
				if(jQuery('.categorify-upload-category-filter').length){
					jQuery('.categorify-upload-category-filter').val(self.categorifyCurrentFolder);
					jQuery('.categorify-upload-category-filter').trigger('change');
				}
				
				
				// we need refresh backbone view in order to display images correctly in current folder
				self.refreshBackbone();
				
				
				jQuery('.attachments').css('height', 'auto');
                localStorage.setItem('categorifyCurrentFolder', currentFolder);
				self.stopPreloader();
			}

        },
		
		refreshBackbone: function(action){
			var self		= this;
			var sidebar 	= jQuery('.cc_categorify_sidebar');
			var backbone 	= self.getBackboneOfMedia(sidebar);
				
			if(action === 'add'){
				 if(typeof backbone.view === "object"){
					var folderFilter = backbone.view.toolbar.get("categorify-filter");
					if(typeof backbone.view === "object") {
						folderFilter.createFilters();
					}
				}
			}else{
		
				
				if (backbone.browser.length > 0 && typeof backbone.view === "object") {
					// Refresh the backbone view
					try {
						backbone.view.collection.props.set({ ignore: (+ new Date()) });
					} catch (e) { console.log(e); }
				}
			}
                       
		},
		
		isListMode: function(){
			if(jQuery('select[name="cc_categorify_folder"]').length){
				return true;
			}
			return false;
		},
		
		
		ifStringIsEmpty: function(string){
			return (!string || /^\s*jQuery/.test(string));
		},
		
		getBodyScrollTop: function(){
			return this.pageYOffset || (document.documentElement && document.documentElement.scrollTop) || (document.body && document.body.scrollTop);	
		},
		
		getBackboneOfMedia: function(obj) {
			// Get the attachments browser
			var browser,
				backboneView,
				parentModal = obj.parents(".media-modal");
			if (parentModal.length > 0) {
				browser 	= parentModal.find(".attachments-browser");
			}else{
				browser 	= jQuery("#wpbody-content .attachments-browser");
			}
			backboneView 	= browser.data("backboneView");
			return {
				browser: 	browser,
				view: 		backboneView
			};
		},
		
		
		executeSearch: function(){
			var self = this;
			var searchVal = jQuery.trim(jQuery("#categorify-search").val()).toLowerCase();
			var li = jQuery('li.category_item');
			
			
			if(searchVal != ''){
				li.addClass('hide-for-search');
				
				li.each(function(){
					var el 			= jQuery(this);
					var parent_ID 	= el.data('parent-id');
					var title 		= el.find('.cc_title').text().toLowerCase();
					
					searchVal = new RegExp(searchVal,"g");
					
					
					if(title.match(searchVal)){
						el.removeClass('hide-for-search');
						if(parent_ID != 'parent_0'){
							self.removeParentClass(li, parent_ID);
						}
					}
				});
			}else{
				li.removeClass('hide-for-search');
			}
			
		},
		
		
		removeParentClass: function(items, parent_id){
			var self = this;

			items.each(function(){
				var item 		= jQuery(this);
				if(item.hasClass(parent_id)){
					if(item.hasClass('close'))
					{
						item.find('.cc_dropdown').trigger('click');
						self.closedCatsBeforeSearch.push(item.data('id'));
					}
					item.removeClass('hide-for-search');
					var parent_ID = item.data('parent-id');
					
					if(parent_ID != 'parent_0'){
						self.removeParentClass(items, parent_ID);
					}
					
				}
			});
		},
		
		startSearchFolder: function(){
			var self = this;
			jQuery('#categorify-search').on("keyup", function(){
				self.executeSearch();
				if(jQuery(this).val() == ''){self.reverseOpenedCats(); self.closedCatsBeforeSearch = [];}
			});

			jQuery(document).on("change", "#categorify-search", function(){
				self.executeSearch();
				if(jQuery(this).val() == ''){self.reverseOpenedCats(); self.closedCatsBeforeSearch = [];}
			});

			jQuery(document).on("blur", "#categorify-search", function(){
				self.executeSearch();
				if(jQuery(this).val() == ''){self.reverseOpenedCats(); self.closedCatsBeforeSearch = [];}
			});
			
		},
		
		
		reverseOpenedCats: function()
		{
			var li = jQuery('li.category_item');
			
			for(var i = 0; this.closedCatsBeforeSearch.length > i; i++){
				jQuery('li.category_item.parent_'+this.closedCatsBeforeSearch[i]).find('.cc_dropdown').trigger('click');
			}
			
		}
		
		
		
    };




(function (jQuery){
	"use strict";
	jQuery(document).ready(function(){
		
		CategorifyCore.init();

	});
})(jQuery);

