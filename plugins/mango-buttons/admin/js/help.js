jQuery(document).ready(function($){
		
	function ViewModel() {
		var self = this;
		
		self.subscribing = ko.observable(false);
		self.email = ko.observable(mb_settings.email);
		self.subscribed = mb_settings.subscribed && mb_settings.subscribed !== "false";
		
		if(!self.subscribed){
			setTimeout(function(){
				$('.mb-subscribe').slideDown(500);
				$('#mb-settings').css('paddingTop', 1);//not sure why but this fixes the drop down "jumping" the header
			}, 350);
		}
		
		self.subscribe = function(){
			$email_address = self.email();
			
			if(self.subscribing()){
				return;
			}
			
			self.subscribing(true);
			
			//POST subscirbe form to mailchimp servers and handle response by updating page
			$.ajax({
				type: "POST",
				url: "//philbaylog.us6.list-manage.com/subscribe/post-json?u=e551001469dd03b8e20452a24&id=6071c4038b&c=?",
				data: {
					EMAIL: $email_address,
					FNAME: mb_settings.fname,
					WEBSITE: mb_settings.website
				},
				success: function(response){
					
					if(response.result == 'success'){
						
						self.subscribed = true;
						
						$.ajax({
							type: "POST",
							url: ajaxurl,
							data: {
								action: 'mb_admin_ajax',
								endpoint: 'update_mb_settings',
								mb_admin_nonce: MB_GLOBALS.MB_ADMIN_NONCE,
								settings: {
									email: self.email(),
									subscribed: true
								}
							},
							success: function(response){
								
								$('.mb-subscribe').css('textAlign', 'center');
								$('.mb-subscribe form').hide();
								$('.mb-subscribe-text').text('Thanks for subscribing! Confirm your email to receive your discount code.');
								self.subscribing(false);

							},
							dataType: 'json'
						});
						
					}
					else{
						self.pushNotification('Couldn\'t subscribe at this time', 'failure');
					}
					
				},
				dataType: 'json'
			});
		}
		
		self.init = function(){
			
		}
		
	}
	
	//initialize the view model
	viewModel = new ViewModel();
	ko.applyBindings(viewModel, $('#mb-help')[0]);
	
	viewModel.init();
	
});