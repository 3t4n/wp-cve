
;( function( elementor, $, window ) {
    
	var qs_img_src_set = false;
	var items, qsRegex, buttonFilter;
	
	var $isotop_container = $('.element-ready-template-grid-wrapper').isotope({
		itemSelector: '.element-ready-template-single-item',
		layoutMode: 'fitRows',
		masonry: {
			gutter: 20
		},
		getSortData: {
			title: '.element-ready-tpl-title b',
			insert: '.er-template-import',
			pro: '.action-wrapper a.er-template-pro',
			publicationdate: '.publicationdate',
		},
		filter: function() {
		  var $this = $(this);
		  var searchResult = qsRegex ? $this.attr('data-category').match( qsRegex ) : true;
		  var buttonResult = buttonFilter ? $this.is( buttonFilter ) : true;
		  return searchResult && buttonResult;
		}  
	});

	$('.element-ready-temlpates-sorts-button-group').on('click', 'button', function() {
		/* Get the element name to sort */
		var sortValue = $(this).attr('data-sort-by');
		/* Get the sorting direction: asc||desc */
		var sortDirection = $(this).attr('data-sort-direction');
		/* convert it to a boolean */
		sortDirection = sortDirection == 'asc';
		/* pass it to isotope */
		$isotop_container.isotope({ sortBy: sortValue, sortAscending: sortDirection });
	});

	function debounce( fn, threshold ) {
		var timeout;
		threshold = threshold || 100;
		return function debounced() {
		  clearTimeout( timeout );
		  var args = arguments;
		  var _this = this;
		  function delayed() {
			fn.apply( _this, args );
		  }
		  timeout = setTimeout( delayed, threshold );
		};
	}
	  
	async function er_ready_doAjax(args) {
		let result = false;
		try {
			result = await $.ajax({
			_nonce : fe_templates_lib.ajax_nonce,
			url : wp.ajax.settings.url,
			type: 'get',
			data: args
		});

		} catch (error) {
			return false;
		}
		return result;
	}
	
	elementor.on('document:loaded', function() {
    
		var $previewContents = elementor.$previewContents;
	
		var $templateLibBtnStyle = $( '<style />' ),
			btnStyle = '';
			btnStyle += '.elementor-add-section-area-button.element-ready-add-template-button { margin-left: 8px; vertical-align: bottom; }';
			btnStyle += '.elementor-add-section-area-button.element-ready-add-template-button img { height: auto; }';

		$templateLibBtnStyle.html( btnStyle );
		var modal = document.getElementById('element-ready-template-lib');
		
		$previewContents.find('head').append( $templateLibBtnStyle );

		var $templateLibBtn = $('<div />');
		var $grid = null;
		$templateLibBtn.addClass( 'elementor-add-section-area-button element-ready-add-template-button' );
		$templateLibBtn.attr( 'title', 'Add Element Ready Template' );
		$templateLibBtn.html( '<img src="' + fe_templates_lib.logoUrl + '" />' );
		$templateLibBtn.insertAfter( $previewContents.find('.elementor-add-section-area-button.elementor-add-template-button') );
		$templateLibBtn.on( 'click', function(e) {
			modal.style.display = 'block';
			$isotop_container.isotope();
			
		});
	  

		$('.er-templates-category').on( 'change', function() {
			buttonFilter = this.value;
			$('.element-ready--tpl-search input').val('');
			$isotop_container.isotope();
		});
	
		var $quicksearch = $('.element-ready--tpl-search input').keyup( debounce( function() {
			qsRegex = new RegExp( $quicksearch.val(), 'gi' );
			$isotop_container.isotope();
		  }) );

		$('.element-ready--tpl-tag-filter > div').on('click', function(){

			$( '.element-ready--tpl-tag-filter > div' ).each(function( index ) {
				$(this).removeClass('element-ready-active-tags');
			});

			$(this).addClass('element-ready-active-tags');
            
			$quicksearch.val($(this).attr('data-title'));
		
			$quicksearch.focus();
			qsRegex = new RegExp( $quicksearch.val(), 'gi' );
			$isotop_container.isotope();
		});  

		if($('.body-import-active-overlay').length){
			$('.body-import-active-overlay').remove(); 
		}

	    $(document).on('click', '.element-ready-template-single-item .er-template-import', function(){
		
			$(this).closest(".element-ready-grid-item-inner-content").addClass('active').focus();	        
			$( '.er-template-inner-section' ).before( "<div class='body-import-active-overlay'></div>" ); 
			$(this).text('Importing Template');
				er_ready_doAjax({
					action: 'eready_get_library_data_single',
					tpl_id : $(this).data('template_id'),
					editor_post_id : ElementorConfig.initial_document.id,
					sync: true 
				}).then( (data) =>{ 
				
					var newWidget = {};
					data.data.cus.content.forEach(element => {
						newWidget = {};
						newWidget.elType = element.elType;
						newWidget.settings = element.settings;
						newWidget.elements = element.elements;

						$e.run( "document/elements/create", {
							model: element,
							container: elementor.getPreviewContainer()
						});
						
					});
				
					modal.style.display = 'none';
					elementor.notifications.showToast({
						message: elementor.translate('Content Pasted! ')
					});

					$(this).text('insert');
					$('.body-import-active-overlay').remove(); 
				});
		

		}); 

		
		jQuery('#er-ready-template-close-icon').on('click', function(event){
			modal.style.display = 'none';
		} ); 

		window.onclick = function(event) {
			if (event.target == modal) {
			  modal.style.display = "none";
			}
		}
	});

} )( elementor, jQuery, window );
