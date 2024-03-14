jQuery(document).ready(function($){
		
	function ViewModel() {
		var self = this;
		
		self.syncingDataWithServer = ko.observable(true);
		self.savingSettings = ko.observable(false);
		self.subscribing = ko.observable(false);
		self.deletingPlugin = ko.observable(false);
		
		self.settings = {
			email: ko.observable(),
			subscribed: ko.observable(),
			icon_color: ko.observable(),
			extended_language_support: ko.observable()
		}
		self.settings.cache = {
			email: ko.observable(),
			icon_color: ko.observable(),
			extended_language_support: ko.observable()
		}
		
		self.settings.cacheCurrentSettings = function(){
			
			self.settings.cache.email(self.settings.email());
			self.settings.cache.icon_color(self.settings.icon_color());
			self.settings.cache.extended_language_support(self.settings.extended_language_support());
			
		}
		
		self.settings.dirty = ko.computed(function(){
			
			if(self.settings.icon_color() != self.settings.cache.icon_color() ||
				self.settings.extended_language_support() != self.settings.cache.extended_language_support()
			){
				return true;
			}
			else{
				return false;
			}
			
		});
		
		self.settings.dirty.subscribe(function(newValue){
			if(newValue){
				self.dismissNotification();
			}
		});
		
		
		self.notification = ko.observable(false);
		self.pushNotification = function(text, type){
			self.notification({
				text: text,
				status: type
			});
		}
		self.dismissNotification = function(){
			self.notification(false);
		}
		
		self.destroyPluginData = function(){
			
			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: {
					action: 'mb_admin_ajax',
					endpoint: 'destroy_plugin_data',
					mb_admin_nonce: MB_GLOBALS.MB_ADMIN_NONCE
				},
				success: function(response){
					
					//navigate up a directory - not sure exactly how well this works but we have to do something...
					document.location.href="/";
					
				},
				dataType: 'json'
			});
			
		}
		
		self.saveSettings = function(callback){
			if(self.savingSettings()){
				return;
			}
			
			self.savingSettings(true);
			
			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: {
					action: 'mb_admin_ajax',
					endpoint: 'update_mb_settings',
					mb_admin_nonce: MB_GLOBALS.MB_ADMIN_NONCE,
					settings: {
						email: self.settings.email(),
						icon_color: self.settings.icon_color(),
						extended_language_support: self.settings.extended_language_support(),
						subscribed: self.settings.subscribed()
					}
				},
				success: function(response){

					self.settings.cacheCurrentSettings();
					
					self.savingSettings(false);
					
					if(typeof callback == 'function'){
						callback();
					}
				},
				dataType: 'json'
			});
		}
		
		self.subscribe = function(){
			//Get value current saved in email field
			$email_address = self.settings.email();
			
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
						
						//save subscribed status & current email address (if changed) to mango buttons
						self.settings.subscribed(true);
						
						self.saveSettings(function(){
							$('.mb-subscribe').css('textAlign', 'center');
							$('.mb-subscribe form').hide();
							$('.mb-subscribe-text').text('Thanks for subscribing! Confirm your email to receive your discount code.');
							self.subscribing(false);
						});
						
					}
					else{
						self.pushNotification('Couldn\'t subscribe at this time', 'failure');
					}
					
				},
				dataType: 'json'
			});
		}
		
		self.syncData = function(){
			self.syncingDataWithServer(true);
			
			//get settings straight from php localization
			
			self.settings.email(mb_settings.email);
			self.settings.icon_color(mb_settings.icon_color);
			self.settings.extended_language_support(mb_settings.extended_language_support);
			
			self.settings.subscribed(mb_settings.subscribed && mb_settings.subscribed !== "false");
			
			
			self.settings.cacheCurrentSettings();
			
			self.syncingDataWithServer(false);
			
			
			if(!self.settings.subscribed()){
				setTimeout(function(){
					$('.mb-subscribe').slideDown(500);
					$('#mb-settings').css('paddingTop', 1);//not sure why but this fixes the drop down "jumping" the header
				}, 350);
			}
		}
		
		self.init = function(){
			
		}
		
	}
	
	//initialize the view model
	viewModel = new ViewModel();
	ko.applyBindings(viewModel, $('#mb-settings')[0]);
	
	viewModel.init();
	
	viewModel.syncData();
	
});