(function( $ ) {
	'use strict';
	$(document).ready(function () {

		var optionsDefaultValues = {
			model: 'gpt-3.5-turbo-16k',
			temperature: 0.8,
			topP: 1,
			maxToken: 1500,
			freuencyPenality: 0.01,
			presencePenality: 0.01,
			bestOf: 1,
		};

		var modal = $(document).find('.ays-chatgpt-assistant-api-key-modal');
		if (modal) {
			modal.fadeIn().css('display','flex');
		}

		$('.ays-settings-wrapper').on('click' ,'.ays-chatgpt-assistant-connect-button', function(){
            connectOpenAI($(this), false);
        });
		
		modal.on('click' ,'.ays-chatgpt-assistant-connect-button', function(){
            connectOpenAI($(this), true);
        });

        $(document).on('click', '.ays-chatgpt-assistant-skip-button', function () {
			$(document).find('.ays-chatgpt-assistant-api-key-modal').fadeOut( 'slow', function() {
				$(this).remove();
			});
        });
    
        $(document).on('click' , '.ays-chatgpt-assistant-disconnect-button',  function(){
            var thisButton = $(this);
            var thisParent = thisButton.parents('.ays-chatgpt-assistant-make-connection');
            var chatgptId = $(document).find('#ays-chatgpt-assistant-main-id').val();
            var apiKeyInput = thisParent.find('.ays-chatgpt-assistant-api-key-box');
            var messageBox = thisParent.find('.ays-chatgpt-assistant-api-key-connection-message');
            var chatGptdata = {
                ays_chatgpt_assistant_id : chatgptId,
                action : 'ays_chatgpt_admin_ajax',					
                function : 'ays_chatgpt_disconnect',
            }
            thisButton.prop('disabled', true);
            thisParent.find('.ays_chatgpt_assistant_loader_box_connection').removeClass('display_none');
                $.ajax({
                    url: aysChatGptAssistantGeneral.ajaxUrl,
                    method: 'post',
                    dataType: 'json',
                    data: chatGptdata,
                    success: function (response) {
                        if(typeof response != "undefined" && response){
                            thisButton.text("Connect");
                            thisButton.prop('disabled', false);
                            thisButton.removeClass('ays-chatgpt-assistant-disconnect-button').addClass('ays-chatgpt-assistant-connect-button');
                            messageBox.removeClass('ays-chatgpt-assistant-api-key-connection-success-message');
                            messageBox.text('Disconnected!');
                            thisParent.find('.ays_chatgpt_assistant_loader_box_connection').addClass('display_none');
                            apiKeyInput.val('');
                            $(document).find(".ays-assistant-chatbox").remove();
							$(document).find('.ays-chatgpt-assistant-empty-key-notice').show();
							apiKeyInput.attr('readonly', false);
                        }
                    }
                });
            
        });

		function connectOpenAI (thisButton, isPopup) {
            var thisParent = isPopup ? $('.ays-chatgpt-assistant-api-key-modal').find('.ays-chatgpt-assistant-make-connection') : thisButton.parents('.ays-chatgpt-assistant-make-connection');
            var apiKeyInput = thisParent.find('.ays-chatgpt-assistant-api-key-box');
            var chatgptId = $(document).find('#ays-chatgpt-assistant-main-id').val();
            var messageBox = thisParent.find('.ays-chatgpt-assistant-api-key-connection-message');
            var apiKey = apiKeyInput.val();
            var chatGptdata = {
                ays_chatgpt_assistant_api_key : apiKey,
                ays_chatgpt_assistant_id : chatgptId,
                rMethod : 'GET',
                request_type: 'models',
                action : 'ays_chatgpt_admin_ajax',					
                function : 'ays_chatgpt_connect',
            }
			// if (!isPopup) $('.ays-settings-wrapper').find('.ays-chatgpt-assistant-connect-button').prop('disabled', true);
            $(document).find('.ays_chatgpt_assistant_loader_box_connection').removeClass('display_none');
			$.ajax({
				url: aysChatGptAssistantGeneral.ajaxUrl,
				method: 'post',
				dataType: 'json',
				data: chatGptdata,
				success: function (response) {
					$(document).find('.ays_chatgpt_assistant_loader_box_connection').addClass('display_none');
					if(typeof response != "undefined" && response.status){
						$('.ays-settings-wrapper').find('.ays-chatgpt-assistant-connect-button').prop('disabled', false);
						if(response.openai_connection.openai_response_code == 200){
							if(response.openai_connection.openai_response_message == 'success'){
								$('.ays-settings-wrapper').find('.ays-chatgpt-assistant-connect-button').text("Disconnect");
								$('.ays-settings-wrapper').find('.ays-chatgpt-assistant-api-key-box').val(apiKey).attr('readonly', true);
								$('.ays-settings-wrapper').find('.ays-chatgpt-assistant-connect-button').removeClass('ays-chatgpt-assistant-connect-button').addClass('ays-chatgpt-assistant-disconnect-button');    
								if (isPopup) {
									messageBox.css('color', '#007d00');
									$('.ays-settings-wrapper').find('.ays-chatgpt-assistant-api-key-connection-message').removeClass('ays-chatgpt-assistant-api-key-connection-failed-message').addClass('ays-chatgpt-assistant-api-key-connection-success-message').text('Connected!');
								} else {
									messageBox.removeClass('ays-chatgpt-assistant-api-key-connection-failed-message').addClass('ays-chatgpt-assistant-api-key-connection-success-message');
								}
								messageBox.text('Connected!');
								if (isPopup) {
									setTimeout(() => {
										messageBox.text('');
									}, 6000);
								}
								var chatbotReadyBox = $(response.chatbot_html);
								chatbotReadyBox.find('.ays-assistant-chatbox-apikey').val(apiKey);
								$('body').append(chatbotReadyBox);
								chatbotReadyBox.AysChatGPTChatBoxMain();
								chatbotReadyBox.find(".ays-assistant-chatbox-closed-view").addClass("ays-assistant-chatbox-closed-view-new-connection");
								setTimeout(function(){
									chatbotReadyBox.find(".ays-assistant-chatbox-closed-view").removeClass("ays-assistant-chatbox-closed-view-new-connection");
								} , 2000);
								$(document).find('.ays-chatgpt-assistant-api-key-modal').fadeOut( 'slow', function() {
									$(this).remove();
								});

								$(document).find('.ays-chatgpt-assistant-empty-key-notice').hide();
								apiKeyInput.attr('readonly', true);
							}
						}
						else{
							if (!isPopup) messageBox.removeClass('ays-chatgpt-assistant-api-key-connection-success-message').addClass('ays-chatgpt-assistant-api-key-connection-failed-message').text(response.openai_connection.openai_response_message);
							else messageBox.text(response.openai_connection.openai_response_message);
							setTimeout(() => {
								messageBox.text('');
							}, 6000);
						}
					} else {
						if (!isPopup) messageBox.removeClass('ays-chatgpt-assistant-api-key-connection-success-message').addClass('ays-chatgpt-assistant-api-key-connection-failed-message').text(response.openai_connection.openai_response_message);
						else messageBox.text(response.openai_connection.openai_response_message);
						setTimeout(() => {
							messageBox.text('');
						}, 6000);
					}
				}
			});
		}

		$(document).find('[data-bs-toggle="tooltip"]').tooltip();

		$(document).find('#ays-chatgpt-assistant-chatbox-mode').on('click', function () {
			var chatboxTheme = $(document).find('.ays-chatgpt-assistant-themes-inp:checked').val();
			var chatboxMode = $(this).is(":checked") ? 'dark' : 'light';
			var inputColors = getInputColors(chatboxTheme, chatboxMode);
			setInputColors(inputColors);
			// var messageColor = $(document).find('#ays-chatgpt-assistant-message-bg-color');
			// var messageTextColor = $(document).find('#ays-chatgpt-assistant-message-text-color');
			// var responseColor = $(document).find('#ays-chatgpt-assistant-response-bg-color');
			// var responseTextColor = $(document).find('#ays-chatgpt-assistant-response-text-color');
			// var bGColor = $(document).find('#ays-chatgpt-assistant-chatbox-background-color');
			// if ($(this).is(":checked")) {
			// 	messageColor.val("#343541");
			// 	messageTextColor.val("#f1f1f1");
			// 	responseColor.val("#4b4d56");
			// 	responseTextColor.val("#f1f1f1");
			// 	bGColor.val("#343541");
			// } else {
			// 	messageColor.val("#4e426d");
			// 	messageTextColor.val("#ffffff");
			// 	responseColor.val("#d3d3d3");
			// 	responseTextColor.val("#000000");
			// 	bGColor.val("#ffffff");
			// }
		});

		$(document).find('.ays-chatgpt-assistant-theme-item').on('click', function () {
			var chatboxTheme = $(this).parent().find('.ays-chatgpt-assistant-themes-inp').val();
			var chatboxMode = $(document).find('#ays-chatgpt-assistant-chatbox-mode').is(":checked") ? 'dark' : 'light';
			var inputColors = getInputColors(chatboxTheme, chatboxMode);
			setInputColors(inputColors);
		});

		$(document).find('.ays-chatgpt-assistant-chat-setting-range').on('input', function () {
			var thisParent = $(this).parent();
			thisParent.find('.ays-chatgpt-assistant-chat-limit-text').text($(this).val())
		})

		$(document).find('.ays-chatgpt-assistant-reset-settings').on('click', function () {
			var type = $(this).attr('data-setting-type');
			var els = $(document).find('[data-setting="'+type+'"]');

			els.each((index, el) => {
				var defVal = optionsDefaultValues[$(el).attr('data-option')];
				$(el).val(defVal);
				$(el).parent().find('.ays-chatgpt-assistant-chat-limit-text').text(defVal);
			});
		});

		function getInputColors(theme, mode) {
			var themeColors = {
				'default' : {
					'light': {
						widgetColor: "#4e426d",
						messageColor: "#4e426d",
						messageTextColor: "#ffffff",
						responseColor: "#d3d3d3",
						responseTextColor: "#000000",
						bGColor: "#ffffff",
					},
					'dark': {
						widgetColor: "#4e426d",
						messageColor: "#343541",
						messageTextColor: "#f1f1f1",
						responseColor: "#4b4d56",
						responseTextColor: "#f1f1f1",
						bGColor: "#343541",
					}
				},
				'chatgpt' : {
					'light': {
						widgetColor: "#343541",
						messageColor: "#ffffff",
						messageTextColor: "#343541",
						responseColor: "#f7f7f8",
						responseTextColor: "#374151",
						bGColor: "#ffffff",
					},
					'dark': {
						widgetColor: "#343541",
						messageColor: "#343541",
						messageTextColor: "#ececf1",
						responseColor: "#4b4d56",
						responseTextColor: "#d1d5db",
						bGColor: "#343541",
					}
				}
			}

			return themeColors[theme][mode];
		}

		function setInputColors(colors) {
			var widgetColor = $(document).find('#ays-chatgpt-assistant-chatbox-color');
			var messageColor = $(document).find('#ays-chatgpt-assistant-message-bg-color');
			var messageTextColor = $(document).find('#ays-chatgpt-assistant-message-text-color');
			var responseColor = $(document).find('#ays-chatgpt-assistant-response-bg-color');
			var responseTextColor = $(document).find('#ays-chatgpt-assistant-response-text-color');
			var bGColor = $(document).find('#ays-chatgpt-assistant-chatbox-background-color');

			widgetColor.val(colors.widgetColor);
			messageColor.val(colors.messageColor);
			messageTextColor.val(colors.messageTextColor);
			responseColor.val(colors.responseColor);
			responseTextColor.val(colors.responseTextColor);
			bGColor.val(colors.bGColor);
		}

		$(document).find('.ays-chatgpt-assistant-rate-chat-actions-actions input[type=radio]').on('change', function () {
			var $this = $(this);
			
			var action = $this.attr('data-child-action');
			var container = $this.parents('.ays-chatgpt-assistant-rate-chat-container').find('div[data-child-container='+action+']')

			$this.parents('.ays-chatgpt-assistant-rate-chat-container').find('div[data-child-container]').hide();
			container.show();
		});

		autosize($(document).find('.ays-chatgpt-assistant-greeting-message-text'));
	});

})( jQuery );
