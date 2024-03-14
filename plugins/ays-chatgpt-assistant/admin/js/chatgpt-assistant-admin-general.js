(function($) {
    'use strict';

	$(document).ready(function () {
		var toggle_ddmenu = $(document).find('.toggle_ddmenu');
    	toggle_ddmenu.on('click', function () {
    	    var ddmenu = $(this).next();
    	    var state = ddmenu.attr('data-expanded');
    	    switch (state) {
    	        case 'true':
    	            $(this).find('img').css({
    	                transform: 'rotate(0deg)'
    	            });
    	            ddmenu.attr('data-expanded', 'false');
    	            break;
    	        case 'false':
    	            $(this).find('img').css({
    	                transform: 'rotate(90deg)'
    	            });
    	            ddmenu.attr('data-expanded', 'true');
    	            break;
    	    }
    	});

        $(document).on('keydown', function(e){
			var saveButton = $(document).find('input.ays-chatgpt-assistant-general-settings-save');
			if ( saveButton.length > 0 ) {
                if (!(e.which == 83 && e.ctrlKey) && !(e.which == 19)){
                    return true;
                }
                saveButton.trigger("click");
                e.preventDefault();
                return false;
            }
		});

		// Submit buttons disableing with loader
        $(document).find('.ays-chatgpt-assistant-loader-banner').on('click', function () {        
            var $this = $(this);
            submitOnce($this);
        });

        var userRolesTab1 = $(document).find('#ays_user_roles');
        if (userRolesTab1.length > 0) {
            userRolesTab1.select2();
        }
        var userRolesTab2 = $(document).find('#ays-chatgpt-assistant-user-roles');
        if (userRolesTab2.length > 0) {
            userRolesTab2.select2();
        }

        $(document).find('.nav-tab-wrapper a.nav-tab').on('click', function (e) {
		    if(! $(this).hasClass('no-js')){
		        var elemenetID = $(this).attr('href');
		        var active_tab = $(this).attr('data-tab');
		        $(document).find('.nav-tab-wrapper a.nav-tab').each(function () {
		            if ($(this).hasClass('nav-tab-active')) {
		                $(this).removeClass('nav-tab-active');
		            }
		        });
		        $(this).addClass('nav-tab-active');
		        $(document).find('.ays-tab-content').each(function () {
		            $(this).css('display', 'none');
		        });
		        $(document).find("[name='ays_tab']").val(active_tab);
		        $('.ays-tab-content' + elemenetID).css('display', 'block');
		        e.preventDefault();
		    }
		});

        // Pro features start
            $(document).find(".ays-pro-features-v2-upgrade-button").hover(function() {
                // Code to execute when the mouse enters the element
                var unlockedImg = "Unlocked_24_24.svg";
                var imgBox = $(this).find(".ays-pro-features-v2-upgrade-icon");
                var imgUrl = imgBox.attr("data-img-src");
                var newString = imgUrl.replace("Locked_24x24.svg", unlockedImg);
                
                imgBox.css("background-image", 'url(' + newString + ')');
                imgBox.attr("data-img-src", newString);
            }, function() {
                
                var lockedImg = "Locked_24x24.svg";
                var imgBox = $(this).find(".ays-pro-features-v2-upgrade-icon");
                var imgUrl = imgBox.attr("data-img-src");
                var newString = imgUrl.replace("Unlocked_24_24.svg", lockedImg);
                
                imgBox.css("background-image", 'url(' + newString + ')');
                imgBox.attr("data-img-src", newString);
            });
            
        // Pro features end
        
        $(document).on('change', '.ays_toggle_checkbox', function (e) {
            var state = $(this).prop('checked');
            var parent = $(this).parents('.ays_toggle_parent');
            
            if($(this).hasClass('ays_toggle_slide')){
                switch (state) {
                    case true:
                        parent.find('.ays_toggle_target').slideDown(250);
                        break;
                    case false:
                        parent.find('.ays_toggle_target').slideUp(250);
                        break;
                }
            }else{
                switch (state) {
                    case true:
                        parent.find('.ays_toggle_target').show(250);
                        break;
                    case false:
                        parent.find('.ays_toggle_target').hide(250);
                        break;
                }
            }
        });

        $(document).on("click", "#ays-chatgpt-dismiss-buttons-content .ays-button, #ays-chatgpt-dismiss-buttons-content-helloween .ays-button-helloween, #ays-chatgpt-dismiss-buttons-content-black-friday .ays-button-black-friday", function(e){
			e.preventDefault();
	
			var $this = $(this);
			var thisParent  = $this.parents("#ays-chatgpt-dismiss-buttons-content");
            // var thisParent  = $this.parents("#ays-chatgpt-dismiss-buttons-content-helloween");
            // var thisParent  = $this.parents("#ays-chatgpt-dismiss-buttons-content-black-friday");
			var mainParent  = $this.parents("div.ays_chatgpt_dicount_info");
			var closeButton = mainParent.find("button.notice-dismiss");
	
			var attr_plugin = $this.attr('data-plugin');
			// var wp_nonce    = thisParent.find('#ays-chatgpt-assistant-gift-banner').val();
			var wp_nonce    = thisParent.find('#ays-chatgpt-assistant-sale-banner').val();
	
			var data = {
				action: 'ays_chatgpt_dismiss_button',
				_ajax_nonce: wp_nonce,
			};
	
			$.ajax({
				url: aysChatGptAssistantAdminSettings.ajaxUrl,
				method: 'post',
				dataType: 'json',
				data: data,
				success: function (response) {
					if( response.status ){
						closeButton.trigger('click');
					} else {
						swal.fire({
							type: 'info',
							html: "<h2>"+ aysChatGptAssistantAdminSettings.errorMsg +"</h2><br><h6>"+ aysChatGptAssistantAdminSettings.somethingWentWrong +"</h6>"
						}).then(function(res) {
							closeButton.trigger('click');
						});
					}
				},
				error: function(){
					swal.fire({
						type: 'info',
						html: "<h2>"+ aysChatGptAssistantAdminSettings.errorMsg +"</h2><br><h6>"+ aysChatGptAssistantAdminSettings.somethingWentWrong +"</h6>"
					}).then(function(res) {
						closeButton.trigger('click');
					});
				}
			});
		});

        function submitOnce(subButton){
            var subLoader = subButton.parents('div').find('.ays_chatgpt_assistant_loader_box');
            if ( subLoader.hasClass("display_none") ) {
                subLoader.removeClass("display_none");
            }
            subLoader.css({
                "padding-left": "8px",
                "display": "inline-block"
            });

            setTimeout(function() {
                $(document).find('.ays-chatgpt-assistant-loader-banner').attr('disabled', true);
            }, 10);

            setTimeout(function() {
                $(document).find('.ays-chatgpt-assistant-loader-banner').attr('disabled', false);
                subButton.parents('div').find('.ays_chatgpt_assistant_loader_box').css('display', 'none');
            }, 5000);

        }

        var checkCountdownIsExists = $(document).find('#ays-chatgpt-countdown-main-container');
        if ( checkCountdownIsExists.length > 0 ) {
            var second  = 1000,
                minute  = second * 60,
                hour    = minute * 60,
                day     = hour * 24;

            var countdownEndTime = aysChatGptAssistantAdminSettings.chatgptBannerDate,
            countDown = new Date(countdownEndTime).getTime(),
            x = setInterval(function() {

                var now = new Date().getTime(),
                    distance = countDown - now;

                var countDownDays    = document.getElementById("ays-chatgpt-countdown-days");
                var countDownHours   = document.getElementById("ays-chatgpt-countdown-hours");
                var countDownMinutes = document.getElementById("ays-chatgpt-countdown-minutes");
                var countDownSeconds = document.getElementById("ays-chatgpt-countdown-seconds");

                if(countDownDays !== null || countDownHours !== null || countDownMinutes !== null || countDownSeconds !== null){
                    countDownDays.innerText = Math.floor(distance / (day)),
                    countDownHours.innerText = Math.floor((distance % (day)) / (hour)),
                    countDownMinutes.innerText = Math.floor((distance % (hour)) / (minute)),
                    countDownSeconds.innerText = Math.floor((distance % (minute)) / second);

                }

                //do something later when date is reached
                if (distance < 0) {
                    var headline  = document.getElementById("ays-chatgpt-countdown-headline"),
                        countdown = document.getElementById("ays-chatgpt-countdown"),
                        content   = document.getElementById("ays-chatgpt-countdown-content");

                  // headline.innerText = "Sale is over!";
                  countdown.style.display = "none";
                  content.style.display = "block";

                  clearInterval(x);
                }
            }, 1000);
        }
	});

})(jQuery);