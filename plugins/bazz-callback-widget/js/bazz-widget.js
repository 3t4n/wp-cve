jQuery(document).ready(function(){
	jQuery(".bazz-widget").draggable({
		containment:"window",
		scroll:false,
		axis:"y",
		start: function(){
			jQuery(".bazz-widget-button").addClass("noclick");
		},
		stop: function(){
			setTimeout(function(){
				jQuery(".bazz-widget-button").removeClass("noclick");
			}, 100);
		}
	});
	setTimeout(function bazz_animation(){
		jQuery(".bazz-widget i:first").css("transform", "rotateY(180deg)");
		jQuery(".bazz-widget i:last").css("transform", "rotateY(0deg)");
		setTimeout(function(){
			jQuery(".bazz-widget i").removeAttr("style");
		}, 3000);
		setTimeout(bazz_animation, 6000);
	}, 6000);
	setTimeout(function bazz_circle_animation() {
		jQuery(".bazz-widget-inner-circle").css("height","85px").css("width","85px").css("top","-10px").css("right","-10px");
		setTimeout(function(){
			jQuery(".bazz-widget-inner-circle").removeAttr("style");
		}, 1000);
		setTimeout(bazz_circle_animation, 2000);
	}, 2000);
	setTimeout(function bazz_border_animation() {
		jQuery(".bazz-widget-inner-border").animate({
			"height": 125,
			"width": 125,
			"top": -30,
			"right" : -30,
			"opacity": 0
		}, 1500, function(){
			jQuery(".bazz-widget-inner-border").removeAttr("style");
		});
		setTimeout(bazz_border_animation, 2000);
	}, 2000);
	
	jQuery(".bazz-widget .bazz-widget-button").on("click", function(){
		if(!jQuery(".bazz-widget").hasClass("opened") && !jQuery(".bazz-widget-button").hasClass("noclick")) {
			jQuery(".bazz-widget").addClass("opened");
			jQuery(".bazz-widget-form #bazz-widget-phone").focus();
			setTimeout(function(){
				jQuery(".bazz-widget").css("background-color", "rgba(0,0,0,0.75)");
				jQuery(".bazz-widget-form").fadeIn();
			}, 500);
		}
	});
	jQuery(".bazz-widget-close").on("click", function(){
		if(jQuery(".bazz-widget").hasClass("opened")) {
			jQuery(".bazz-widget").removeClass("opened").css("background-color", bazz_options.bazz_color_scheme);
			jQuery(".bazz-widget-form").hide();
			jQuery(".bazz-widget-form-top").removeClass("overflow");
		}
	});
	//if RU make the tamplate
	if (bazz_options.currentLang == "RU" || bazz_options.currentLang == 'RU') {
		if (bazz_options.bazz_in_russia == '1') {
			jQuery("#bazz-widget-phone").mask("+7(999)999-99-99", {placeholder:"+7(___)___-__-__"});
			jQuery(".bazz-widget-form-info").text('Нажимая кнопку, вы даете согласие на обработку своих персональных данных.');
		} else {
			// If you ant to use masked input - uncomment section below
			/*
			jQuery.mask.definitions['~']='[ 1234567890+_]';
			jQuery("#bazz-widget-phone").mask("+~~~(999)999-99-99");
			*/
			
		}
		
	} else {
		jQuery("#bazz-widget-phone").addClass("grey-placeholder");
	}
	function countdown() {
		jQuery(".bazz-widget-form-submit").hide();
		if (jQuery(".bazz_time").length >= 1) {
			time = parseInt(jQuery(".bazz_time").text());
			jQuery(".bazz-widget-form-top").append('<label class="countdown" style="text-align: center; font-size: 20px;">00:<span>'+time+'</span>:<span>99</span></label>');
			current = parseInt(jQuery(".countdown span:first").text());
			millisec = 99;
			setInterval(function(){
				millisec = parseInt(millisec) - 1;
				if(millisec < 10) {
					millisec = '0' + millisec;
				}
				jQuery(".countdown span:last").text(millisec);
				if (parseInt(millisec) == 0) {
					millisec = 99;
				}
			}, 10);
			var cdw = setInterval(function(){
				current = parseInt(current) - 1;
				if(current < 10) {
					current = '0' + current;
				}
				jQuery(".countdown span:first").text(current);
				if (parseInt(current) == 0) {
					clearInterval(cdw);
				}
			}, 1000);
		} else {
			time = 0;
		}
		
	}
	jQuery(".bazz-widget-your-name").on("click", function(e){
		e.preventDefault();
		if(!jQuery(".bazz-widget-form-top").hasClass("overflow")) {
			jQuery(".bazz-widget-form-top").addClass("overflow");
		}
	})
	jQuery(".bazz-widget-name-close").on("click", function(e){
		e.preventDefault();
		if(jQuery(".bazz-widget-form-top").hasClass("overflow")) {
			jQuery(".bazz-widget-form-top").removeClass("overflow");
		}
	})
	jQuery("#bazz-widget-phone").on("keyup", function(){
		jQuery(this).removeAttr("style");
	});
	jQuery(".bazz-widget-form-submit").on("click", function(e){
		e.preventDefault();
		if (!jQuery(".bazz-widget-form-submit").hasClass("disabled")) {
			phone = jQuery("#bazz-widget-phone").val();
			if (phone == "") {
				jQuery("#bazz-widget-phone").css("border-color", "red");
				return false;
			} else {
				jQuery(".bazz-widget-form-submit").addClass("disabled").css("cursor", "wait");
				jQuery.ajax({
					type: 'POST',
					url: bazz_ajax.url,
					data: {
						action: 'bazz_widget_action',
						phone: jQuery("#bazz-widget-phone").val().replace(/[\s\(\)-]/g, ''),
						name: jQuery("#bazz-widget-name").val(),
                        check: jQuery("#bazz-widget-check").val(),
                        nonce: jQuery("#bazz-widget-nonce").val(),
                        refferer: jQuery("[name=\"_wp_http_referer\"]").val(),
					},
					success: function(data) {
					    var msgText = data.data;
					    if(data.success) {
							document.dispatchEvent(
								new CustomEvent( "bazzSuccessSubmit", {
									bubbles: true
								} )
							);
                            countdown();
                            setTimeout(function(){
                                jQuery(".bazz-widget-form").html(msgText);
                            }, time*1000);
                        } else {
                            jQuery(".bazz-widget-form").html(msgText);
                        }

					}
				});
			}
		}
	});
});