/* global objwp2sl, jQuery*/
(function($) {
    "use strict";
 	$(function() {
		function OEPL_checkChange($this){
			var value = $this.val().trim();
			var storedval=$this.data("stored");
			if(value!=storedval) {
				$this.trigger("simpleChange");
			}
		}

		$(document).ready(function(){
			$("#_wpnonce").remove();
			
			$(this).data("stored",$(this).val());   
				$(".OEPL_custom_label").bind("keyup keydown keypress change blur",function(e){  
				OEPL_checkChange($(this));
			});
			
			$(".OEPL_custom_label").bind("simpleChange",function(e){
				$(this).siblings('.OEPL_small_button').show();
			});
				
			$(".OEPL_custom_label").each(function(){
				OEPL_checkChange($(this));	
			});
			
			$(this).data("stored",$(this).val());   
				$(".OEPL_custom_order").bind("keyup keydown keypress change blur",function(e){  
				OEPL_checkChange($(this));
			});
			
			$(".OEPL_custom_order").bind("simpleChange",function(e){
				$(this).siblings('.OEPL_small_button').show();
			});
			
			$(".OEPL_custom_order").each(function(){
				OEPL_checkChange($(this));	
			});
			
			$(".OEPL_grid_status").on("change", function() {
				var action = $(this).data("action");
				var pid = $(this).data("pid");

				if (action === 'OEPL_Change_Hidden_Status') {
					if ($(this).is(':checked')) {
						$(this).next('.OEPL_hidden_value').show();
					} else {
						$(this).next('.OEPL_hidden_value').hide();
					}
				}

				var data = {};
				data.action = 'WP2SL_Grid_Ajax_Action';
				data.OEPL_Action = action;
				data.pid = pid;

				if (action === 'OEPL_Change_Hidden_Status_Val') {
					data.hidden_field_value = $(this).val().trim();
				}

				$.post(objwp2sl.ajaxurl, data, function(response) {
				});
				return false;
			});
			
			$(".OEPL_save_custom_label").on("click", function(){
				var val = $(this).siblings('.OEPL_custom_label').val();
				var pid = $(this).data("pid");
				var oeplnonce 	= $('#oepl_nonce').val();
			
				var data = {};
				data.action = 'WP2SL_save_custom_label';
				data.pid = pid;
				data.label = val;
				data.oepl_nonce = oeplnonce;
				
				$(this).find('.fa').removeClass('fa-check-square');
				$(this).find('.fa').addClass('fa-spinner');
				$(this).find('.fa').addClass('fa-spin');
				$(this).hide();
				$.post(objwp2sl.ajaxurl, data, function(response) {
					$(this).find('.fa').addClass('fa-check-square');
					$(this).find('.fa').removeClass('fa-spinner');
					$(this).find('.fa').removeClass('fa-spin');
				});
				return false;
			});
			
			$(".OEPL_save_custom_order").on("click", function(){
				var val = $(this).siblings('.OEPL_custom_order').val();
				var pid = $(this).data("pid");
				var oeplnonce 	= $('#oepl_nonce').val();
				
				var data = {};
				data.action = 'WP2SL_save_custom_order';
				data.pid = pid;
				data.label = val;
				data.oepl_nonce = oeplnonce;
				
				$(this).find('.fa').removeClass('fa-check-square');
				$(this).find('.fa').addClass('fa-spinner');
				$(this).find('.fa').addClass('fa-spin');
				$(this).hide();

				$.post(objwp2sl.ajaxurl, data, function(response) {
					$(this).find('.fa').addClass('fa-check-square');
					$(this).find('.fa').removeClass('fa-spinner');
					$(this).find('.fa').removeClass('fa-spin');
				});
				return false;
			});
		});
	});
})(jQuery);