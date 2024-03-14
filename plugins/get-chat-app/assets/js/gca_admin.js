/**
 * Get Chat App Plugin Class
 *
 * Version: 1.2.02
 * 
*/

class Gcap{

	/**
	 * Constructor 
	*/
	constructor(options){

		this.init();

    $ = jQuery

		if(typeof $ === 'undefined'){
			console.error("jQuery is not present, Get Chat App Plugin Class could not be initialized");
			return;
		}

		$(document).ready(function(){
			setTimeout(function() {
				gcap.onReady()
			}, 500);
		})
	}



	/**
	 * Initialize defaults, state trackers and instance variables
	 * 
	 * @return void
	*/
	init(){
		this.state = {
			selectedPlatforms : [],
			pro : false,
			lastEditedPlatform : false
		};

		this.platforms = [
			'whatsapp',
			'facebook',
			'email',
			'instagram',
			'telegram',
			'x',
			'tiktok',
			'linkedin',
			'phone',
			'customLink'
		];
		
		this.data = {
			other : {
				status : true,
				position : 'right',
				platforms : [],
			},

			whatsapp : {
				mobileNumber : {
					value : '',
				},
				titleMessage : {
					value : '',
					demoElementSelector : '.gcapDemoCard[data-type="whatsapp"] .gcapMainCardInnerTopContent',
				},
				welcomeMessage : {
					value : '',
					demoElementSelector : '.gcapDemoCard[data-type="whatsapp"] .gcapMainCardInnerBodyMessage',
				},
			},
			
			facebook : {
				facebookPageId : {
					value : '',
				},
				facebookReplyTime : {
					value : '',
					demoElementSelector : '.gcapDemoCard[data-type="facebook"] .gcapMainCardInnerTopContentBottomReplyTime',
				},
				facebookMessage : {
					value : '',
					demoElementSelector : '.gcapDemoCard[data-type="facebook"] .gcapMainCardInnerBodyMessage',
				},
			},
			
			email : {
				gcaEmailAddress : {
					value : '',
				},
				gcaEmailSubject : {
					value : '',
				},
			},
			
			instagram : {
				gcaInstagramUsername : {
					value : '',
				},
			},
			
			telegram : {
				gcaTelegramUsername : {
					value : '',
				},
			},

			x : {
				gcaXUsername : {
					value : '',
				},
			},

			tiktok : {
				gcaTiktokUsername : {
					value : '',
				},
			},

			linkedin : {
				gcaLinkedinUsername : {
					value : '',
				},
			},

			phone : {
				gcaPhoneNumber : {
					value : '',
				},
			},

			customLink : {
				gcaCustomLink : {
					value : '',
				},
			},
		}

		this.events = {
			'demo_updated' : new Event('GCA_demoUpdated'),
			'settings_data_object_updated' : new Event('GCA_settingsDataObjectUpdated'),
			'platform_selected' : new Event('GCA_platformSelected'),
		}
	}


	/**
	 * OnReady delegate, completes the initialization
	 * 
	 * @return void
	*/
	onReady(){       
		this.findElements();
		this.bindEvents();

		this.updateSettingsDataObject();
		this.updateSettingFields();

		this.elements.saveSettingsButtonContainer.show();

		if(!this.state.pro && this.state.selectedPlatforms.length > 1){
			$('#platforms .platform-option').prop('checked', false);
			$(`#platforms .platform-option[value="${this.state.selectedPlatforms[0]}"]`).prop('checked', true).change();
		}
	}



	/**
	 * Find the relevant elements within the dom
	 * 
	 * @return void
	*/
	findElements(){
		this.elements = {};

		this.elements.demoButton = $('#gcap-show-demo-button');
		this.elements.closeDemoButton = $('.gcapCloseDemo');
		this.elements.demoContainer = $('#gcapDemo');

		this.elements.platformSelector = $('ul#platforms');

		this.elements.showProFeaturesButton = $('#showProFeatures');
		this.elements.hideProFeaturesButton = $('#hideProFeatures');
		this.elements.proFeaturesContainer = $('#gcapProFeatures');

		this.elements.saveSettingsButtonContainer = $('#gcapSaveSettingsContainer');
		this.elements.saveSettingsButton = $('#gcapSaveSettings');
		this.elements.savedNotice = $('#gcap-settings-saved-note');
	}



	/**
	 * Bind all the events
	 * 
	 * @return void
	*/
	bindEvents(){

		this.elements.demoButton.on('click', (event) => {
			this.updateDemo();
			this.toggleDemo();
		})

		this.elements.closeDemoButton.on('click', (event) => {
			this.elements.demoContainer.hide();
		})
		
		this.elements.platformSelector.find('input[type="checkbox"]').on('change', function(event){
			gcap.state.selectedPlatforms = [];

			let currentInput = $(event.currentTarget);
			let currentType = currentInput.val();

			let platformInputs = gcap.elements.platformSelector.find('input[type="checkbox"]');
			
			if(!gcapIsPro) {
				platformInputs.prop('checked', false);
				currentInput.prop('checked', true);
			}
			
			platformInputs.each(function(){
				let plaformInput = $(this);
				let type = plaformInput.val();

				if(plaformInput.prop('checked')){
					gcap.state.selectedPlatforms.push(type);
				}
			})

			gcap.updateSettingFields();
			gcap.updateDemo();

			if(currentInput.prop('checked')){
				$([document.documentElement, document.body]).animate({
					scrollTop: $(`.gcap-settings-container[data-type="${currentType}"]`).offset().top-50
				}, 500);
			}
		})

		$('.gcapInput, .gcapTextarea').on('keyup', (event) => {
			let field = $(event.target);
			let platform = field.closest('.gcap-settings-container').attr('data-type')
			
			this.updateSettingsDataObject();
			if(!field.hasClass('gcap-contactInfo')){
				this.updateDemo(platform);
			}

			field.removeClass('gcapFieldEmpty');
		})

		$('select, input[type="checkbox"], input[type="radio"]').on('click', (event) => {
			this.updateSettingsDataObject();

			this.updateDemo();
		})

		$(document).on('click', '.gcapMainCardCloseButton', (event) => {
			if(!this.state.pro){
				return;
			}
			$('.gcapDemoCard').hide();
			$(`.gcapDemoCard`).removeClass('gcapCardOpen');
		})

		this.elements.showProFeaturesButton.on('click', (event) => {
			this.elements.showProFeaturesButton.hide();
			this.elements.hideProFeaturesButton.show();

			this.elements.proFeaturesContainer.show(250);

			$([document.documentElement, document.body]).animate({
				scrollTop: this.elements.proFeaturesContainer.offset().top-50
			}, 500);
		})

		this.elements.hideProFeaturesButton.on('click', (event) => {
			this.elements.hideProFeaturesButton.hide();
			this.elements.showProFeaturesButton.show();

			this.elements.proFeaturesContainer.hide(250);
		})

		this.elements.saveSettingsButton.on('click', (event) => {
			this.saveSettings();
		})

		$(document).on('click', '.gcap-addContact', (event) => {
			if(!this.state.pro){
				this.elements.showProFeaturesButton.click();

				$('.gcapMultipleContactsUpsell').addClass("gcap-blink");
				$('.gcapMultipleContactsUpsell').addClass("gcap-highlight");

				setTimeout(function(){
					$('.gcapMultipleContactsUpsell').removeClass('gcap-blink');
					$('.gcapMultipleContactsUpsell').removeClass('gcap-highlight');
				},2000)
			}
		})

	}



	/**
	 * Updates the class' data object values
	 */
	updateSettingsDataObject(){

		this.data.other.status = $('#gcapEnabled').val();
		this.data.other.position = $('#position1:checked').val();

		for(let platform of this.platforms){

			let platformSelectorButton = $(`#platforms .platform-option[value="${[platform]}"]`);
			if(platformSelectorButton.prop('checked')){
				this.state.selectedPlatforms.push(platform);
			}
			
			$(`.gcap-settings-container[data-type="${platform}"]`).find('.gcapInput').each(function(){
				let input = $(this);

				// if(input.hasClass('gcap-contactInfo') || input.hasClass('gcap-additionalContact')){
				// 	return;
				// }
				
				let id = input.attr('id');
				let value = input.val();
		
				if(typeof gcap.data[platform][id] == 'undefined'){
					gcap.data[platform][id] = {};
				}
				
				gcap.data[platform][id].value = value;
			})
		
			$(`.gcap-settings-container[data-type="${platform}"]`).find('.gcapTextarea').each(function(){
				let input = $(this);

				// if(input.hasClass('gcap-contactInfo') || input.hasClass('gcap-additionalContact')){
				// 	return;
				// }
				
				let id = input.attr('id');
				let value = input.val();
		
				if(typeof gcap.data[platform][id] == 'undefined'){
					gcap.data[platform][id] = {};
				}
				
				gcap.data[platform][id].value = value;
			})

		}

		this.dispatchGcaEvent(this.events.settings_data_object_updated);

	}
	


	/**
	 * Updates the settings fields based on selected platforms
	 * 
	 * @return void
	 */
	updateSettingFields(){
		
		this.elements.platformSelector.find('input[type="checkbox"]').each(function() {
			let input = $(this);
			let type = input.val();

			let settingsContainer = $(`.gcap-settings-container[data-type="${type}"]`);
			
			if(gcap.state.selectedPlatforms.includes((type))){
				settingsContainer.show(250);
			} else {
				settingsContainer.hide(250);
			}
		})

	}



	toggleDemo(){
		this.elements.demoContainer.toggle(150);

		setTimeout(function(){
			if(gcap.elements.demoContainer.is(':visible')){
				if(!gcap.state.pro || (gcap.state.pro && gcap.state.selectedPlatforms.length <= 1)){
					let platform = gcap.state.selectedPlatforms[0];
					$([document.documentElement, document.body]).animate({
						scrollTop: $(`.gcap-settings-container[data-type="${platform}"]`).offset().top-50
					}, 500);

					if(gcap.state.pro){
						gcapPro.handleMultipleContacts();
					}
					
				}
			}
		},200)
	}


	
	/**
	 * Updates the demo based on the selected platforms
	 * 
	 * @param string lastEditedPlatform
	 * 
	 * @return void
	 */
	updateDemo(lastEditedPlatform){

		$('.gcapDemoCard').hide();
		// $(`.gcapDemoCard`).removeClass('gcapCardOpen');
		$('.gcapDemoButton').hide();

		for(let platform of this.state.selectedPlatforms){
			$(`.gcapDemoCard[data-type="${platform}"]`).show();

			$(`.gcapDemoButton[data-type="${platform}"]`).show();

			for(let platformFieldID in this.data[platform]){

				let platformFieldObject = this.data[platform][platformFieldID];
				if(typeof platformFieldObject.demoElementSelector != 'undefined' && platformFieldObject.demoElementSelector != null && (platformFieldObject.demoElementSelector).trim() != ''){
					let demoElement = $(platformFieldObject.demoElementSelector);

					let content = platformFieldObject.value;
					demoElement.html(content);
				}
			}
		}

		if(typeof lastEditedPlatform != 'undefined' && lastEditedPlatform != null) {
			this.state.lastEditedPlatform = lastEditedPlatform;
		} else {
			this.state.lastEditedPlatform = false;
		}

		if(this.data.other.position == 'left'){
			this.elements.demoContainer.addClass('gcapLeft');
		} else {
			this.elements.demoContainer.removeClass('gcapLeft');
		}

		this.dispatchGcaEvent(this.events.demo_updated);

	}



	dispatchGcaEvent(event){

		let body = document.querySelector('body');
		body.dispatchEvent(event);

	}


	/**
	 * Saves settings
	 * 
	 * @return void
	 */
	saveSettings(){
		
		this.elements.saveSettingsButton.html("Saving...");
		
		this.updateSettingsDataObject();

		let formData = new FormData();

		formData.append('action', 'gcapSaveSettings');
    formData.append('security', gcap_nonce);

		formData.append('status', this.data.other.status);
		
		let emptyFieldFound = false;
		for(let platform of this.platforms){
			if(this.state.selectedPlatforms.includes(platform)){
				formData.append(platform, platform);

				if((this.data[platform][Object.keys(this.data[platform])[0]]).value.trim() == ''){
					emptyFieldFound = true;
				}

				// Highlight empty fields
				$(`.gcap-settings-container[data-type="${platform}"]`).find('input, textarea').each(function(){
					let field = $(this);

					if(field.val().trim() == ''){
						field.addClass('gcapFieldEmpty');
					}
				})
			} else {
				formData.append(platform, '');
			}
		}

		if(emptyFieldFound && this.data.other.status == 1){
			this.elements.savedNotice.html("Please ensure that you have filled in all fields.");
			this.elements.savedNotice.show();
			this.elements.saveSettingsButton.html("Saving Settings");

			setTimeout(function(){
				gcap.elements.savedNotice.hide();
			}, 2500)

			return;
		}
		
		formData.append('mobileNumber', this.data.whatsapp.mobileNumber.value);
    formData.append('titleMessage', this.data.whatsapp.titleMessage.value);
    formData.append('welcomeMessage', this.data.whatsapp.welcomeMessage.value);
    
		formData.append('facebookPageId', this.data.facebook.facebookPageId.value);
    formData.append('facebookMessage', this.data.facebook.facebookMessage.value);
    formData.append('facebookReplyTime', this.data.facebook.facebookReplyTime.value);
    
		formData.append('gcaEmailAddress', this.data.email.gcaEmailAddress.value);
		formData.append('gcaEmailSubject', this.data.email.gcaEmailSubject.value);

    formData.append('gcaInstagramUsername', this.data.instagram.gcaInstagramUsername.value);
    
		formData.append('gcaTelegramUsername', this.data.telegram.gcaTelegramUsername.value);

		formData.append('gcaTiktokUsername', this.data.tiktok.gcaTiktokUsername.value);
		formData.append('gcaXUsername', this.data.x.gcaXUsername.value);
		formData.append('gcaLinkedinUsername', this.data.linkedin.gcaLinkedinUsername.value);
		formData.append('gcaPhoneNumber', this.data.phone.gcaPhoneNumber.value);
		formData.append('gcaCustomLink', this.data.customLink.gcaCustomLink.value);
    
    formData.append('position', this.data.other.position);

    if(this.state.pro) {
			formData = gcapPro.appendProSettings(formData);
		}

		$.ajax({
      url: gcap_ajaxurl,
      type: 'POST',
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success: function (response) {
				if(response == 'true'){
					gcap.elements.savedNotice.html("Settings saved successfully");
					gcap.elements.savedNotice.show();
					gcap.elements.saveSettingsButton.html("Saving Settings");
				}
				
				setTimeout(function(){
					gcap.elements.savedNotice.hide();
				}, 2500)
      },
      error: function (xhr, status, error) {
				gcap.elements.savedNotice.html(error);
				gcap.elements.savedNotice.show();
				
				setTimeout(function(){
					gcap.elements.saveSettingsButton.html("Saving Settings");
					gcap.elements.savedNotice.hide();
				}, 2500)
      }
    });
	}

}

let gcap = false;
jQuery(function($){
	/**
	 * Constructed in jQuery wrapper to allow the $ instance to be available in class
	 *
	 * The actual variable is defined globally, to expose it in the DOM
	*/
	gcap = new Gcap();
});	