jQuery(document).ready(function($){
	"use strict";

	var fetching = false;
	var $modal = $('#compare');
	function compareFetch( $btn, data, callback ){
		var $icon = $btn.find('i');

		data = $.extend({
			id: $btn.data('id'),
			action: 'directorypress_compare'
		}, data);

		if( fetching == false ){
			$icon.attr('class', 'fas fa-tachometer-alt');
			$.ajax({
				url: directorypress_js_instance.ajaxurl,
				method: 'POST',
				data: data,
				success: function(response){
					$modal.find('.modal-body').html( response );
					callback();
				},
				complete: function(){
					$icon.attr('class', 'fas fa-tachometer-alt');
				}
			})
		}
	}

	function responsiveTable(){
		if( $(window).width() > 414 ){
			var $images = $modal.find('img');
			var $table = $('.responsive-table table');
			var loaded_images_count = 0;

			$images.each(function(){
				$(this).load(function(){
					loaded_images_count++;	
					if (loaded_images_count == $images.length) {
						var $fixedColumn = $table.clone().insertBefore( $table ).addClass('fixed-column');
						$fixedColumn.find('th:not(:first-child),td:not(:first-child)').remove();
						$fixedColumn.width($table.find('th').outerWidth());

						$fixedColumn.find('tr').each(function (i, elem) {
							$(this).height($table.find('tr:eq(' + i + ')').height());
						});	
					}				
				});
			});
		}
	}

	$(document).on('click', '.compare-add', function(e){
		e.preventDefault();
		//alert('test');
		var $this = $(this);
		if( $this.hasClass('active') && $modal.find('.modal-body').html() ){
			$modal.modal('show');
		}
		else{
			$this.addClass('active');
			compareFetch( $this, { compare: 'add' }, function(){
				$modal.modal('show');
				responsiveTable();
			});
		}
		
	});

	$(document).on('click', '.compare-remove', function(e){
		e.preventDefault();
		var $this = $(this);
		compareFetch( $this, { compare: 'remove' }, function(){
			$('td[class="cad_'+$this.data('id')+'"]').remove();
			$('.compare-add[data-id="'+$this.data('id')+'"]').removeClass('active');
		});
	});

	$(document).on('click', '.compare-open', function(e){
		e.preventDefault();
		var $this = $(this);
		if( $modal.find('.modal-body').html() ){
			$modal.modal('show');
		}
		else{
			compareFetch( $this, { compare: 'open' }, function(){
				$modal.modal('show');
				responsiveTable();
			});
		}
	});
});