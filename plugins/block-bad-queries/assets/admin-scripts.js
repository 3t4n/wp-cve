/* BBQ - Admin JS */

(function($) {
	
	$(document).ready(function() {
		
		$('.bbq-test-firewall').on('click', function(e) {
			
			e.preventDefault();
			
			$('.bbq-modal-dialog').dialog('destroy');
			
			var link = this;
			
			var button_names = {}
			
			button_names[alert_test_firewall_true] = function() { 
				window.open(link.href, '_blank');
				$(this).dialog('close');
			}
			
			button_names[alert_test_firewall_false] = function() {
				$(this).dialog('close');
			}
			
			$('<div class="bbq-modal-dialog">'+ alert_test_firewall_message +'</div>').dialog({
				title: alert_test_firewall_title,
				buttons: button_names,
				modal: true,
				width: 370,
				closeText: ''
			});
			
		});
		
	});
	
})(jQuery);