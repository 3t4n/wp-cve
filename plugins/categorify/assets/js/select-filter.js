window.wp = window.wp || {};
var mediaConfig = categorifyConfig;

(function($){
	"use strict";
	var media = wp.media;

	var h = media.view.AttachmentFilters.extend({
		
		tagName:   'select',
		className: 'attachment-filters',
		id:        'categorify-attachment-filters',

		events: {
			change: 'change'
		},
		
		
		keys: [],
		

		initialize: function() {
			this.createFilters();
			_.extend( this.filters, this.options.filters );

			// Build `<option>` elements.
			this.$el.html( _.chain( this.filters ).map( function( filter, value ) {
				return {
					el: $( '<option></option>' ).val( value ).html( filter.text )[0],
					priority: filter.priority || 50
				};
			}, this ).sortBy('priority').pluck('el').value() );

			this.listenTo( this.model, 'change', this.select );
			this.select();
		},

		/**
		 * @abstract
		 */
		createFilters: function() {
			var filters = {};
		
			_.each(categorifyFolders || {}, function( term, key ) 
			{
				var folderID 		= term['folderID'];
				var folderName 		= $("<div/>").html(term['folderName']).text();
				filters[folderID] 	= {
					text: folderName,
					priority: key
				};
				
				filters[folderID]['props'] = {};
				filters[folderID]['props'][mediaConfig.categorifyFolder] = folderID;
			});
		
			// related to "All" only
			filters.all = {
				text: mediaConfig.categorifyAllTitle,
				priority: -1
			};
			filters['all']['props'] = {};
			filters['all']['props'][mediaConfig.categorifyFolder] = null;

			this.filters = filters;
		},

		/**
		 * When the selected filter changes, update the Attachment Query properties to match.
		 */
		change: function() {
			var filter = this.filters[ this.el.value ];
			if ( filter ) {
				this.model.set( filter.props );
			}
		},

		select: function() {
			var model = this.model,
				value = 'all',
				props = model.toJSON();

			_.find( this.filters, function( filter, id ) {
				var equal = _.all( filter.props, function( prop, key ) {
					return prop === ( _.isUndefined( props[ key ] ) ? null : props[ key ] );
				});

				if ( equal ) {
					return value = id;
				}
			});

			this.$el.val( value );
		}
		
	});
		
	var curAttachmentsBrowser 		= media.view.AttachmentsBrowser;
	media.view.AttachmentsBrowser 	= media.view.AttachmentsBrowser.extend({
		createToolbar: function() {

			//set backbone for attachment container
	        var treeLoaded = jQuery.Deferred();
	        this.$el.data("backboneView", this);
          
	        this._treeLoaded = treeLoaded;
	        //end set backbon for attachment container
			
			curAttachmentsBrowser.prototype.createToolbar.apply(this,arguments);

			var self = this;
			var myNewFilter = new h({
	        		className: 'wpmediacategory-filter attachment-filters',
    				controller: self.controller,
    				model:      self.collection.props,
    				priority:   -75
    			}).render();

			this.toolbar.set('categorify-filter', myNewFilter);
			myNewFilter.initialize();			

		}
	});
		
	
	
	// This code responds the sidebar to appear on popup media window
	
    if (typeof window.wp !== 'undefined' && typeof window.wp.Uploader === 'function' && mediaConfig.isPremium == 1) {
        var windowMedia = window.wp.media;
        var windowModal = windowMedia.view.Modal;
        windowMedia.view.Modal = windowMedia.view.Modal.extend({
            className: "categorify-modal",
            initialize: function () {
                windowModal.prototype.initialize.apply(this, arguments);
            }, open: function () {
                //$(".categorify-modal").removeClass("categorify-modal");
                if (windowModal.prototype.open.apply(this, arguments)) {
					
					// We need to add this for while re-open modal window without refresh
					if(!$(".categorify-modal").length) {
                        if($(".supports-drag-drop").length) {
                            $(".supports-drag-drop").each(function(){
                                if($(this).css("display") == "block" || $(this).css("display") == "inline-block") {
                                    console.log("class added");
                                    $(this).addClass("categorify-modal");
                                }
                            });
                        }
                    }
					

                    if($(".categorify-modal").length) {
						
						$(".categorify-custom-menu").remove();
						$(".categorify-modal .media-frame-menu").removeClass("has-categorify-menu");
						
						if($(".categorify-modal .media-frame").length) {
							if (!$(".categorify-custom-menu").length) {
								$('.categorify-modal .media-frame.mode-select').removeClass('hide-menu');
								$(".categorify-modal .media-frame-menu").addClass("has-categorify-menu");
								$(".categorify-modal .media-modal-content .media-frame-menu .media-menu").append("<div class='categorify-custom-menu'></div>");
								$(".categorify-modal .categorify-custom-menu").load(mediaConfig.uploadURL + " #categorify_sidebar", function () {
									$('.categorify-custom-menu #categorify_sidebar, .categorify-custom-menu .cc_categorify_sidebar_in, .categorify-custom-menu .cc_categorify_sidebar_bg').css('width', '300px');

									CategorifyCore.init();
								});
							}
						}
                        
                    } else {
                        setTimeout(function(){
							var selectedFolderMediaId = -1;
							
                            if(selectedFolderMediaId != -1) {
                                $("#media-attachment-taxonomy-filter").each(function () {
                                    $(this).val(selectedFolderMediaId);
                                    $(this).trigger("change");
                                });
                            }
                        }, 1000);
                    }
                }
            }, close: function () {
                windowModal.prototype.close.apply(this, arguments);
                //$(".categorify-modal").removeClass("categorify-modal");
            }
        });
    }
	
	
	
})( jQuery );