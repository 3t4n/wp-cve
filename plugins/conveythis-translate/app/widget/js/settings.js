jQuery(document).ready(function($) {

	$('#conveythis_api_key').on('input', async function() {

		var input = $(this);
		var inputValue = input.val();
		var validPattern = /^(pub_)?[a-zA-Z0-9]*$/;

		if (!validPattern.test(inputValue)) {
			inputValue = inputValue.substring(0, inputValue.length - 1);
			input.val(inputValue);
		}

		if(inputValue.length === 36) {
			var fullKeyPattern = /^pub_[a-zA-Z0-9]{32}$/;
			if (!fullKeyPattern.test(inputValue)) {
				alert('Error api key "pub_********************************"');
			}
		}

	});

	$('.conveythis_new_user').on('click', function() {

		jQuery.ajax({
			url: 'options.php',
			method: 'POST',
			data: {'ready_user': 1},
			success : function(){
				jQuery.ajax({
					url: 'options.php',
					method: 'POST',
					data: {'convey_event': true, 'convey_event_name': 'eventReadyUserSuccess'}
				})
				window.location.reload()
			},
			error: function(){
				jQuery.ajax({
					url: 'options.php',
					method: 'POST',
					data: {'convey_event': true, 'convey_event_name': 'eventReadyUserError'}
				})
			},
		})

	})

	$('.api-key-setting').on('click', function() {

		$('#login-form').css('display', 'none');
		$('#login-form-settings').css('display', 'block');

	})

	$('#login-form .signup-modal').on('click', function () {
		jQuery.ajax({
			url: 'options.php',
			method: 'POST',
			data: {'convey_event': true, 'convey_event_name': 'eventSignupModal'}
		})
	})

	$('#register_form').submit((e) => {
		e.preventDefault()
		var values = {};
		jQuery.each($('#register_form').serializeArray(), function(i, field) {
			values[field.name] = field.value;
		});
		if (!values['i-agree']){
			toastr.error('Agreeing to ConveyThis terms is required to register an account')
			return ;
		}
		$('#signUpModal').modal('hide');
		jQuery.ajax({
			method: 'POST',
			url: 'https://app.conveythis.com/api/signup',
			data: {
				email: values['email'],
				domain: window.location.hostname,
				timestamp: Math.floor(Date.now() / 1000)
			},
			beforeSend: () => {
				$('.loader').show()
			},
			success: (res) => {
				if (res.status == 'success'){
					iconType = 'success';
					textTitle = 'Check your email and click on confirmation link';
					textDescription = '<div class="loader-info"><p>You have successfully registered, please do not forget to confirm your email.<p/></div>';
					timeClose = 5000;

					newKey = res.data.pub_key;
					var eventName = 'eventRegisterFormSuccess';
					if(newKey){
						eventName = eventName + 'Key';
					}

					if(res.data.is_registered == true){
						iconType = 'warning';
						textTitle = 'Your account already exists.';
						textDescription = '<div class="loader-info"><p>Please go to <a target="_blank" href="https://app.conveythis.com/">ConveyThis</a> and log in. After that, you can copy API key for the plugin and apply it in the settings.<p/></div>';
						eventName = 'accountAlreadyExists';
					}

					if(res.data.duplication_domain == true){
						iconType = 'error';
						textTitle = 'Your domain has already been added.';
						textDescription = '<div class="loader-info"><p>Please go to <a target="_blank" href="https://app.conveythis.com/">ConveyThis</a> and log in. After that, you can copy API key for the plugin and apply it in the settings.<p/></div>';
						eventName = 'domainAlreadyExists';
					}

					jQuery.ajax({
						url: 'options.php',
						method: 'POST',
						data: {'convey_event': true, 'convey_event_name': eventName, 'ready_user': true}
					})

					if(newKey){
						toastr.success('Confirmation email was sent to your email')
						$('#conveythis_api_key').val(newKey)
						$('.loader').hide();
						$('.next').click();
					}

					Swal.fire({
						icon: iconType,
						title: textTitle,
						showCloseButton: false,
						showConfirmButton: false,
						html: textDescription,
						timer: timeClose,
					}).then(() => {

					})

					const pusher = new Pusher(res.data.pusher_app_key, {
						cluster: res.data.pusher_cluster,
						encrypted: true
					});

					const channel = pusher.subscribe('notification_channel');
					channel.bind('new_notification-'+res.data.tmp_pusher_id, function(data) {
						$('#conveythis_api_key').val(data.api_key)
						$('.loader').hide()
						$('#signUpModal').modal('hide')
						$('.next').click();
					});
		
					if(res.data.is_registered == false && !res.data.duplication_domain)
					{

						setTimeout(()=>{

							jQuery.ajax({
								url: 'options.php',
								method: 'POST',
								data: {'set_api_key': 1, 'api_key': res.data.pub_key},
								success : function(){
									jQuery.ajax({
										url: 'options.php',
										method: 'POST',
										data: {'convey_event': true, 'convey_event_name': 'eventSetApiKeySuccess'}
									})
									window.location.reload()
								},
								error: function(){
									jQuery.ajax({
										url: 'options.php',
										method: 'POST',
										data: {'convey_event': true, 'convey_event_name': 'eventSetApiKeyError'}
									})
								},
							})

						},timeClose)

					}


				} else {
					jQuery.ajax({
						url: 'options.php',
						method: 'POST',
						data: {'convey_event': true, 'convey_event_name': 'eventRegisterFormError'}
					})
					toastr.error(res.message)
				}
			},
			complete: () => {
				$('.loader').hide()
			}
		})
	})

	$('.signup-link a').click(() => {
		$('.login-link').show();
		$('.signup-link').hide()
		$('#register-div').show();
		$('#login-div').hide()
	})

	$('.login-link a').click(() => {
		$('.login-link').hide();
		$('.signup-link').show()
		$('#register-div').hide();
		$('#login-div').show()
	})
	var inputPassword = $('.input-psswd');
	$('.label-psswd').on('click', function () {
		if (inputPassword.attr('psswd-shown') == 'false') {
			$('.label-psswd .hide').show()
			$('.label-psswd .show').hide()
			inputPassword.removeAttr('type');
			inputPassword.attr('type', 'text');
			inputPassword.removeAttr('psswd-shown');
			inputPassword.attr('psswd-shown', 'true');
		} else {
			$('.label-psswd .hide').hide()
			$('.label-psswd .show').show()
			inputPassword.removeAttr('type');
			inputPassword.attr('type', 'password');
			inputPassword.removeAttr('psswd-shown');
			inputPassword.attr('psswd-shown', 'false');
		}
		$(this).toggleClass("active");
	});

	$('.signup-modal').click(() => {
		$('#signUpModal').modal('show')
	})

	var bootstrapButton = $.fn.dropdown.noConflict() // return $.fn.button to previously assigned value
	$.fn.bootstrapBtn = bootstrapButton // give $().bootstrapBtn the Bootstrap functionality
	var languages = {
        703:{'title_en':'English','title':'English','code2':'en','code3':'eng','flag':'R04', 'flag_codes': {'us': 'United States of America', 'gb': ' United Kingdom', 'ca': 'Canada'}},
        704:{'title_en':'Afrikaans','title':'Afrikaans','code2':'af','code3':'afr','flag':'7xS'},
        705:{'title_en':'Albanian','title':'Shqip','code2':'sq','code3':'sqi','flag':'5iM'},
        706:{'title_en':'Amharic','title':'አማርኛ','code2':'am','code3':'amh','flag':'ZH1'},
        707:{'title_en':'Arabic','title':'العربية','code2':'ar','code3':'ara','flag':'J06'},
        708:{'title_en':'Armenian','title':'Հայերեն','code2':'hy','code3':'hye','flag':'q9U'},
        709:{'title_en':'Azerbaijan','title':'Azərbaycanca','code2':'az','code3':'aze','flag':'Wg1'},
        710:{'title_en':'Bashkir','title':'Башҡортса','code2':'ba','code3':'bak','flag':'D1H'},
        711:{'title_en':'Basque','title':'Euskara','code2':'eu','code3':'eus','flag':''},
        712:{'title_en':'Belarusian','title':'Беларуская','code2':'be','code3':'bel','flag':'O8S'},
        713:{'title_en':'Bengali','title':'বাংলা','code2':'bn','code3':'ben','flag':'63A'},
        714:{'title_en':'Bosnian','title':'Bosanski','code2':'bs','code3':'bos','flag':'Z1t'},
        715:{'title_en':'Bulgarian','title':'Български','code2':'bg','code3':'bul','flag':'V3p'},
        716:{'title_en':'Burmese','title':'မြန်မာဘာသာ','code2':'my','code3':'mya','flag':'YB9'},
        717:{'title_en':'Catalan','title':'Català','code2':'ca','code3':'cat','flag':'Pw6'},
        718:{'title_en':'Cebuano','title':'Cebuano','code2':'ceb','code3':'ceb','flag':''},
        719:{'title_en':'Chinese (Simplified)','title':'简体','code2':'zh','code3':'zh-sim','flag':'Z1v'},
		796:{'title_en':'Chinese (Traditional)','title':'繁體','code2':'zh-tw','code3':'zh-tra','flag':'Z1v'},
        720:{'title_en':'Croatian','title':'Hrvatski','code2':'hr','code3':'hrv','flag':'7KQ'},
        721:{'title_en':'Czech','title':'Čeština','code2':'cs','code3':'cze','flag':'1ZY'},
        722:{'title_en':'Danish','title':'Dansk','code2':'da','code3':'dan','flag':'Ro2'},
        723:{'title_en':'Dutch','title':'Nederlands','code2':'nl','code3':'nld','flag':'8jV'},
        724:{'title_en':'Esperanto','title':'Esperanto','code2':'eo','code3':'epo','flag':'Dw0'},
        725:{'title_en':'Estonian','title':'Eesti','code2':'et','code3':'est','flag':'VJ8'},
        726:{'title_en':'Finnish','title':'Suomi','title':'Finnish','code2':'fi','code3':'fin','flag':'nM4'},
        727:{'title_en':'French','title':'Français','code2':'fr','code3':'fre','flag':'E77'},
        728:{'title_en':'Galician','title':'Galego','code2':'gl','code3':'glg','flag':'A5d'},
        729:{'title_en':'Georgian','title':'ქართული','code2':'ka','code3':'kat','flag':'8Ou'},
        730:{'title_en':'German','title':'Deutsch','code2':'de','code3':'ger','flag':'K7e'},
        731:{'title_en':'Greek','title':'Ελληνικά','code2':'el','code3':'ell','flag':'kY8'},
        732:{'title_en':'Gujarati','title':'ગુજરાતી','code2':'gu','code3':'guj','flag':'My6'},
        733:{'title_en':'Haitian','title':'Kreyòl Ayisyen','code2':'ht','code3':'hat','flag':''},
        734:{'title_en':'Hebrew','title':'עברית','code2':'he','code3':'heb','flag':'5KS'},
        735:{'title_en':'Hill Mari','title':'Курыкмарий','code2':'mrj','code3':'mrj','flag':''},
        736:{'title_en':'Hindi','title':'हिन्दी','code2':'hi','code3':'hin','flag':'My6'},
        737:{'title_en':'Hungarian','title':'Magyar','code2':'hu','code3':'hun','flag':'OU2'},
        738:{'title_en':'Icelandic','title':'Íslenska','code2':'is','code3':'isl','flag':'Ho8'},
        739:{'title_en':'Indonesian','title':'Bahasa Indonesia','code2':'id','code3':'ind','flag':'t0X'},
        740:{'title_en':'Irish','title':'Gaeilge','code2':'ga','code3':'gle','flag':'5Tr'},
        741:{'title_en':'Italian','title':'Italiano','code2':'it','code3':'ita','flag':'BW7'},
        742:{'title_en':'Japanese','title':'日本語','code2':'ja','code3':'jpn','flag':'4YX'},
        743:{'title_en':'Javanese','title':'Basa Jawa','code2':'jv','code3':'jav','flag':'C9k'},
        744:{'title_en':'Kannada','title':'ಕನ್ನಡ','code2':'kn','code3':'kan','flag':'My6'},
        745:{'title_en':'Kazakh','title':'Қазақша','code2':'kk','code3':'kaz','flag':'QA5'},
        746:{'title_en':'Khmer','title':'ភាសាខ្មែរ','code2':'km','code3':'khm','flag':'o8B'},
        747:{'title_en':'Korean','title':'한국어','code2':'ko','code3':'kor','flag':'0W3'},
        748:{'title_en':'Kyrgyz','title':'Кыргызча','code2':'ky','code3':'kir','flag':'uP6'},
        749:{'title_en':'Laotian','title':'ພາສາລາວ','code2':'lo','code3':'lao','flag':'Qy5'},
        750:{'title_en':'Latin','title':'Latina','code2':'la','code3':'lat','flag':'BW7'},
        751:{'title_en':'Latvian','title':'Latviešu','code2':'lv','code3':'lav','flag':'j1D'},
        752:{'title_en':'Lithuanian','title':'Lietuvių','code2':'lt','code3':'lit','flag':'uI6'},
        753:{'title_en':'Luxemb','title':'Lëtzebuergesch','code2':'lb','code3':'ltz','flag':'EV8'},
        754:{'title_en':'Macedonian','title':'Македонски','code2':'mk','code3':'mkd','flag':'6GV'},
        755:{'title_en':'Malagasy','title':'Malagasy','code2':'mg','code3':'mlg','flag':'4tE'},
        756:{'title_en':'Malay','title':'Bahasa Melayu','code2':'ms','code3':'msa','flag':'C9k'},
        757:{'title_en':'Malayalam','title':'മലയാളം','code2':'ml','code3':'mal','flag':'My6'},
        758:{'title_en':'Maltese','title':'Malti','code2':'mt','code3':'mlt','flag':'N11'},
        759:{'title_en':'Maori','title':'Māori','code2':'mi','code3':'mri','flag':'0Mi'},
        760:{'title_en':'Marathi','title':'मराठी','code2':'mr','code3':'mar','flag':'My6'},
        761:{'title_en':'Mari','title':'Мари́йский','code2':'mhr','code3':'chm','flag':''},
        762:{'title_en':'Mongolian','title':'Монгол','code2':'mn','code3':'mon','flag':'X8h'},
        763:{'title_en':'Nepali','title':'नेपाली','code2':'ne','code3':'nep','flag':'E0c'},
        764:{'title_en':'Norwegian','title':'Norsk','code2':'no','code3':'nor','flag':'4KE'},
        765:{'title_en':'Papiamento','title':'E Papiamento','code2':'pap','code3':'pap','flag':''},
        766:{'title_en':'Persian','title':'فارسی','code2':'fa','code3':'per','flag':'Vo7'},
        767:{'title_en':'Polish','title':'Polski','code2':'pl','code3':'pol','flag':'j0R'},
        768:{'title_en':'Portuguese','title':'Português','code2':'pt','code3':'por','flag':'1oU', 'flag_codes': {'br': 'Brazil', 'pt': 'Portugal'}},
        769:{'title_en':'Punjabi','title':'ਪੰਜਾਬੀ','code2':'pa','code3':'pan','flag':'n4T'},
        770:{'title_en':'Romanian','title':'Română','code2':'ro','code3':'rum','flag':'V5u'},
        771:{'title_en':'Russian','title':'Русский','code2':'ru','code3':'rus','flag':'D1H'},
        772:{'title_en':'Scottish','title':'Gàidhlig','code2':'gd','code3':'gla','flag':'9MI'},
        773:{'title_en':'Serbian','title':'Српски','code2':'sr','code3':'srp','flag':'GC6'},
        774:{'title_en':'Sinhala','title':'සිංහල','code2':'si','code3':'sin','flag':'9JL'},
        775:{'title_en':'Slovakian','title':'Slovenčina','code2':'sk','code3':'slk','flag':'Y2i'},
        776:{'title_en':'Slovenian','title':'Slovenščina','code2':'sl','code3':'slv','flag':'ZR1'},
        777:{'title_en':'Spanish','title':'Español','code2':'es','code3':'spa','flag':'A5d', 'flag_codes': {'mx': 'Mexico', 'ar': 'Argentina', 'co': 'Colombia', 'es': 'Spain'}},
        778:{'title_en':'Sundanese','title':'Basa Sunda','code2':'su','code3':'sun','flag':'Wh1'},
        779:{'title_en':'Swahili','title':'Kiswahili','code2':'sw','code3':'swa','flag':'X3y'},
        780:{'title_en':'Swedish','title':'Svenska','code2':'sv','code3':'swe','flag':'oZ3'},
        781:{'title_en':'Tagalog','title':'Tagalog','code2':'tl','code3':'tgl','flag':'2qL'},
        782:{'title_en':'Tajik','title':'Тоҷикӣ','code2':'tg','code3':'tgk','flag':'7Qa'},
        783:{'title_en':'Tamil','title':'தமிழ்','code2':'ta','code3':'tam','flag':'My6'},
        784:{'title_en':'Tatar','title':'Татарча','code2':'tt','code3':'tat','flag':'D1H'},
        785:{'title_en':'Telugu','title':'తెలుగు','code2':'te','code3':'tel','flag':'My6'},
        786:{'title_en':'Thai','title':'ภาษาไทย','code2':'th','code3':'tha','flag':'V6r'},
        787:{'title_en':'Turkish','title':'Türkçe','code2':'tr','code3':'tur','flag':'YZ9'},
        788:{'title_en':'Udmurt','title':'Удму́рт дунне́','code2':'udm','code3':'udm','flag':''},
        789:{'title_en':'Ukrainian','title':'Українська','code2':'uk','code3':'ukr','flag':'2Mg'},
        790:{'title_en':'Urdu','title':'اردو','code2':'ur','code3':'urd','flag':'n4T'},
        791:{'title_en':'Uzbek','title':'O‘zbek','code2':'uz','code3':'uzb','flag':'zJ3'},
        792:{'title_en':'Vietnamese','title':'Tiếng Việt','code2':'vi','code3':'vie','flag':'l2A'},
        793:{'title_en':'Welsh','title':'Cymraeg','code2':'cy','code3':'wel','flag':'D4b'},
        794:{'title_en':'Xhosa','title':'isiXhosa','code2':'xh','code3':'xho','flag':'7xS'},
        795:{'title_en':'Yiddish','title':'ייִדיש','code2':'yi','code3':'yid','flag':'5KS'},	
		//796:{'title_en':'Chinese (Traditional)','title':'繁體','code2':'zh-TW','code3':'zh-tra','flag':'Z1v'},		
		797:{'title_en':'Somali','title':'Soomaali','code2':'so','code3':'som','flag':'3fH'},
		798:{'title_en':'Corsican','title':'Corsu','code2':'co','code3':'cos','flag':'E77'},
		799:{'title_en':'Frisian','title':'Frysk','code2':'fy','code3':'fry','flag':'8jV'},
		800:{'title_en':'Hausa','title':'Hausa','code2':'ha','code3':'hau','flag':'8oM'},
		801:{'title_en':'Hawaiian','title':'Ōlelo Hawaiʻi','code2':'haw','code3':'haw','flag':'00P'},
		802:{'title_en':'Hmong','title':'Hmong','code2':'hmn','code3':'hmn','flag':'Z1v'},
		803:{'title_en':'Igbo','title':'Igbo','code2':'ig','code3':'ibo','flag':'8oM'},
		804:{'title_en':'Kinyarwanda','title':'Kinyarwanda','code2':'rw','code3':'kin','flag':'8UD'},
		805:{'title_en':'Kurdish','title':'Kurdî','code2':'ku','code3':'kur','flag':'YZ9'},
		806:{'title_en':'Chichewa','title':'Chichewa','code2':'ny','code3':'nya','flag':'O9C'},
		807:{'title_en':'Odia','title':'ଓଡିଆ','code2':'or','code3':'ori','flag':'My6'},
		808:{'title_en':'Samoan','title':'Faasamoa','code2':'sm','code3':'smo','flag':'54E'},
		809:{'title_en':'Sesotho','title':'Sesotho','code2':'st','code3':'sot','flag':'7xS'},
		810:{'title_en':'Shona','title':'Shona','code2':'sn','code3':'sna','flag':'80Y'},
		811:{'title_en':'Sindhi','title':'سنڌي','code2':'sd','code3':'snd','flag':'n4T'},
		812:{'title_en':'Turkmen','title':'Türkmenler','code2':'tk','code3':'tuk','flag':'Tm5'},
		813:{'title_en':'Uyghur','title':'ئۇيغۇر','code2':'ug','code3':'uig','flag':'Z1v'},
		814:{'title_en':'Yoruba','title':'Yoruba','code2':'yo','code3':'yor','flag':'8oM'},
		815:{'title_en':'Zulu','title':'Zulu','code2':'zu','code3':'zul','flag':'7xS'},
    }

	$("#range-style-indenting-vertical").slider({
		min: 0,
		max: 300,
		start: $("#display-style-indenting-vertical").text(),
		onMove: function(value) {
			$("#display-style-indenting-vertical").html( value );
			$("[name=style_indenting_vertical]").val( value );
		}
	});
	$("#range-style-indenting-horizontal").slider({
		min: 0,
		max: 300,
		start: $("#display-style-indenting-horizontal").text(),
		onMove: function(value) {
			$("#display-style-indenting-horizontal").html( value );
			$("[name=style_indenting_horizontal]").val( value );
		}
	});
	
	// Prevents the user from removing the flag and text from the widget. 
	$(".radio-block").on("click", function() {
		let withoutTextChecked = $("#without-text").is(":checked");
		let withoutFlagChecked = $("#without-flag").is(":checked");
	
		if (withoutTextChecked && withoutFlagChecked) {
			$(".notify").css("display", "block");
			$(".btn-primary").prop("disabled", true);
		} else {
			$(".notify").css("display", "none");
			$(".btn-primary").prop("disabled", false);
		}
	});	
	
	//

	$('.ui.dropdown').dropdown(
		{
			onChange: function(e) {
				if ($(this).hasClass('widget-trigger')) {
					hideTargetLanguage();
					conveythisSettings.view();
				}
				showDnsRecords();
			}
		}
	);

	conveythisSettings.effect(function(){
		$('#customize-view-button').transition('pulse');
	});
	conveythisSettings.view();

	
	$('.conveythis-widget-option-form input[name=style_corner_type]').on('change', function () {
		conveythisSettings.view();
	});
	
	$('.conveythis-widget-option-form .form-control-color').on('change', function () {
		conveythisSettings.view();
	});
	
	$('button.btn-default-color').click(function(){
		let colorInput = $(this).parent().find("input[type=color]");
		let defaultColor = colorInput.data("default");

		colorInput.val(defaultColor);
		conveythisSettings.view();			
	});
	
	$('.conveythis-reset').on('click', function(e) {
		e.preventDefault();
		$(this).parent().parent().find('.ui.dropdown').dropdown('clear');
	});
	
	$('.conveythis-delete-page').on('click', function(e) {
		//e.preventDefault();
		$(this).parent().remove();
		$(".autoSave").click();
	});

	$('#add_blockpage').on('click', function(e) {
		e.preventDefault();

		let blockpage = '<div class="blockpage position-relative w-100 pe-4">\n' +
			'                    <button class="conveythis-delete-page"></button>\n' +
			'                    <div class="ui input w-100"><input type="url" name="blockpages[]" class="ui input w-100" placeholder="https://example.com"></div>\n' +
			'                </div>';
		$("#blockpages_wrapper").append(blockpage);

		$(document).find('.conveythis-delete-page').on('click', function(e) {
			e.preventDefault();
			$(this).parent().remove();
		});
		
	});

	$('#add_exlusion').on('click', function(e) {
		e.preventDefault();
		let $exclusion = $('<div class="exclusion d-flex position-relative w-100 pe-4">\n' +
			'                    <button class="conveythis-delete-page"></button>\n' +
			'                    <div class="dropdown me-3">\n' +
			'                        <i class="dropdown icon"></i>\n' +
			'                        <select class="dropdown fluid ui form-control rule selection">\n' +
			'                            <option value="start">Start</option>\n' +
			'                            <option value="end">End</option>\n' +
			'                            <option value="contain">Contain</option>\n' +
			'                            <option value="equal">Equal</option>\n' +
			'                        </select>\n' +
			'                    </div>\n' +
			'                     <div class="ui input w-100"><input type="text" class="page_url w-100" placeholder="https://example.com" value=""></div>\n' +
			'                </div>');

		$("#exclusion_wrapper").append($exclusion);

		$(document).find('.conveythis-delete-page').on('click', function(e) {
			e.preventDefault();
			$(this).parent().remove();
		});

		$('.ui.dropdown').dropdown()
	});

	$('#add_flag_style').on('click', function(e) {
		e.preventDefault();
		
		if($(".style-language").length == 4) {
			$('#add_flag_style').prop("disable", true);
			return;
		}

		let $rule_style = $('.cloned').clone()

		$rule_style.removeClass('cloned')
		$rule_style.find('.change_language').prepend('<input type="hidden" name="style_change_language[]" value="">')
		$rule_style.find('.change_flag').prepend('<input type="hidden" name="style_change_flag[]"  value="">')
		$("#flag-style_wrapper").append($rule_style);

		$(document).find('.conveythis-delete-page').on('click', function(e) {
			e.preventDefault();
			$(this).parent().remove();
		});

		$('.ui.dropdown').dropdown();
		sortFlagsByLanguage();
	});

	sortFlagsByLanguage();

	$('#add_glossary').on('click', function(e) {

		e.preventDefault();
		let targetLanguages = $('input[name="target_languages"]').val().split(',');

		let $glossary = $('<div class="glossary position-relative w-100">\n' +
			'                        <button class="conveythis-delete-page" style="top:10px"></button>\n' +
			'                        <div class="row w-100 mb-2">\n' +
			'                            <div class="col-md-3">\n' +
			'                                <div class="ui input">\n' +
			'                                    <input type="text" class="source_text w-100 conveythis-input-text" placeholder="Enter Word" value="">\n' +
			'                                </div>\n' +
			'                            </div>\n' +
			'                            <div class="col-md-3">\n' +
			'                                <div class="dropdown fluid">\n' +
			'                                    <i class="dropdown icon"></i>\n' +
			'                                    <select class="dropdown fluid ui form-control rule w-100" required>\n' +
			'                                        <option value="prevent">Don\'t translate</option>\n' +
			'                                        <option value="replace">Translate as</option>\n' +
			'                                    </select>\n' +
			'                                </div>\n' +
			'                            </div>\n' +
			'                            <div class="col-md-3">\n' +
			'                                <div class="ui input">\n' +
			'                                    <input type="text" class="conveythis-input-text translate_text w-100" disabled>\n' +
			'                                </div>\n' +
			'                            </div>\n' +
			'                            <div class="col-md-3">\n' +
			'                                <div class="dropdown fluid">\n' +
			'                                    <i class="dropdown icon"></i>\n' +
			'                                    <select class="dropdown fluid ui form-control target_language w-100">\n' +
			'                                        <option value="">All languages</option>\n' +
			'                                    </select>\n' +
			'                                </div>\n' +
			'                            </div>\n' +
			'                        </div>\n' +
			'                    </div>');


		let $targetLanguages = $glossary.find('.target_language');
		for (let language_id in languages) {
			let language = languages[language_id];
			if (targetLanguages.includes(language.code2)) {
				$targetLanguages.append('<option value="' + language.code2 + '">' + language.title_en + '</option>');
			}
		}

		$glossary.find('.conveythis-delete-page').on('click', function(e) {
			e.preventDefault();
			$(this).parent().remove();
		});

		$("#glossary_wrapper").append($glossary);
		$('.ui.dropdown').dropdown()

		$(document).find('div.glossary .rule select').on('change', function(e) {
			e.preventDefault();
			let $rule = $(this).parent().closest('.glossary').find('.translate_text');
			if (this.value == 'prevent') {
				$rule.attr('disabled', 'disabled');
			}else {
				$rule.removeAttr('disabled');
			}
		});

	});

	$(document).on('input', '#link_enter', function() {
		var inputVal = $(this).val();
		var validPattern = /^\/(?!http:\/\/|https:\/\/).*$/;

		if (!validPattern.test(inputVal)) {
			$(this).val('');
			alert('Note: Please provide the link in the format (/404.html or /system/link) without using (https:// or http://), and also without the domain name (temp_domain.com).');
		}
	});

	$('#add_system_link').on('click', function(e) {

		e.preventDefault();

		let $systemLink = $(
			'<div class="system_link position-relative w-100"> ' +
			'<input type="hidden" class="system_link_id" value=""/> ' +
			'<button type="submit" name="submit" class="conveythis-delete-page"></button> ' +
			'<div class="row w-100 mb-2">' +
			'<div class="ui input w-100">' +
			'<input' +
			' id="link_enter" type="text" class="link_text w-100 conveythis-input-text" placeholder="Enter link (/404.html or /path/path...)" ' +
			' value="" >' +
			'</div></div></div>'
		);

		$systemLink.find('.conveythis-delete-page').on('click', function(e) {
			e.preventDefault();
			$(this).parent().remove();
		});

		$("#system_link_wrapper").append($systemLink);
		$('.ui.dropdown').dropdown()

	});

	$('#conveythis_clear_all_cache').on('click', function(e) {
		e.preventDefault()
		var ajax_url = $(this).data('href');
		var apiKeyVal = $('#conveythis_api_key').val()
		var data = {
			'api_key': apiKeyVal,
			'conveythis_clear_all_cache': true
		};

		$.ajax({
			url: ajax_url,
			type: 'post',
			data: data,
			dataType: 'json',
			success: function (response) {
				if(response.clear){
					$('#conveythis_confirmation_message_clear_all_cahce').hide();
				}
			}
		});
	});



	$('#conveythis_dismiss_all_cache').on('click', function(e) {
		e.preventDefault()
		var ajax_url = $(this).data('href');
		var data = {
			'dismiss': true
		};

		$.ajax({
			url: ajax_url,
			type: 'post',
			data: data,
			dataType: 'json',
			success: function (response) {
				if(response.clear){
					$('#conveythis_confirmation_message_clear_all_cahce').hide();
				}
			}
		});
	});

	$('#clear_translate_cache').on('click', function (e) {
		var btn = $(this);
		jQuery.ajax({
			url: 'options.php',
			method: 'POST',
			data: { 'clear_translate_cache': true },
			success: function(result){

				showNotification(result);

				function showNotification(result) {

					var button = document.getElementById('clear_translate_cache');

					if(result.clear_cache_translate) {

						button.classList.add('green-border');

						setTimeout(function() {
							button.classList.remove('green-border');
						}, 2000);

					} else {

						button.classList.add('red-border', 'shake');

						setTimeout(function() {
							button.classList.remove('red-border', 'shake');
						}, 2000);

					}

				}

			}
		})
	});

	$(document).find('div.glossary .rule select').on('change', function(e) {
		e.preventDefault();

		let $rule = $(this).parent().closest('.glossary').find('.translate_text');
		if (this.value == 'prevent') {
			$rule.attr('disabled', 'disabled');
		}else {
			$rule.removeAttr('disabled');
		}
	});

	$('#add_exlusion_block').on('click', function(e) {
		e.preventDefault();
		let $exclusion_block = $('<div class="exclusion_block position-relative w-100 pe-4">\n' +
			'                        <button class="conveythis-delete-page"></button>\n' +
			'                        <div class="ui input">\n' +
			'                            <input type="text" class="form-control id_value w-100"  placeholder="Enter id">\n' +
			'                        </div>\n' +
			'                    </div>');

		$exclusion_block.find('.conveythis-delete-page').on('click', function(e) {
			e.preventDefault();
			$(this).parent().remove();
		});

		$("#exclusion_block_wrapper").append($exclusion_block);

	});


	
	function showPositionType(type){

		if(type == 'custom'){
			$('#position-fixed').fadeOut();
			$('#position-custom').fadeIn();
		}else{
			$('#position-custom').fadeOut();
			$('#position-fixed').fadeIn();
		}
	}
	
	function showUrlStructureType(type){
	
		if(type == 'subdomain'){
			$('#dns-setup').fadeIn();
			showDnsRecords();
		}else{
			$('#dns-setup').fadeOut();
		}
	}
	
	function showDnsRecords(){

		let targetLanguages = $('input[name=target_languages]').val();
		if (targetLanguages) {
			targetLanguages = targetLanguages.split(",");
			$("#dns-setup-records").html("");
			for (language_id in languages) {
				if (targetLanguages.includes(languages[language_id].code2)) {
					let row = "<tr><td>" + languages[language_id].title_en + "</td><td>" + languages[language_id].code2 + "." + location.hostname + "</td><td>dns1.conveythis.com</td></tr>";
					$("#dns-setup-records").append(row);
				}
			}
		}
	}

	showDnsRecords();
	
	$('input[name=style_position_type]').change(function(){
		showPositionType(this.value);
	});
	
	$('input[name=url_structure]').change(function(){
		showUrlStructureType(this.value);
	});

	$('input[name=target_languages]').change(function(){
		let targetLanguagesTranslations = $('input[name="target_languages_translations"]').val();
		targetLanguagesTranslations = targetLanguagesTranslations?JSON.parse(targetLanguagesTranslations):{};
		let targetLanguages = this.value.split(",");
		$("#target_languages_translations").html("");
		$("#default_language_list").html('<div class="item" data-value="">No value</div>');
		for(language_id in languages){
			let langCode = languages[language_id].code2;
			if(targetLanguages.includes(langCode)) {
				let row = "<tr><td>" + "<div class='ui input'><input type='text' language_id='" + langCode + "' value='" + (targetLanguagesTranslations[langCode]?targetLanguagesTranslations[langCode]:langCode) + "' placeholder='Alias for " + languages[language_id].title_en + "' /></div>" + "</td></tr>";
				$("#target_languages_translations").append(row);
				$("#default_language_list").append('<div class="item" data-value="' + langCode + '">' + languages[language_id].title_en + '</div>');
			}
		}
	});

	function checkValidation(){

		let validation = true;
		let apiKey = $('#conveythis_api_key')
		let sourceLanguage = $('input[name="source_language"]')
		let targetLanguage = $('input[name="target_languages"]')
		if (!apiKey.val()) {
			$('#apiKey .validation-label').show()
			$('#apiKey .input').addClass('validation-icon')
			apiKey.addClass('validation-failed')
			validation = false;
		}
		else{
			$('#apiKey .validation-label').hide()
			$('#apiKey .input').removeClass('validation-icon')
			apiKey.removeClass('validation-failed')
		}

		if (!sourceLanguage.val()) {
			$('#sourceLanguage .dropdown').addClass('validation-icon')
			$('#sourceLanguage .validation-label').show()
			sourceLanguage.parent().addClass('validation-failed')
			validation = false;
		}
		else{
			$('#sourceLanguage .dropdown').removeClass('validation-icon')
			$('#sourceLanguage .validation-label').hide()
			sourceLanguage.parent().removeClass('validation-failed')
		}

		if (!targetLanguage.val()){
			$('#targetLanguages .dropdown').addClass('validation-icon')
			$('#targetLanguages .validation-label').show()
			targetLanguage.parent().addClass('validation-failed')
			validation = false;
		}
		else{
			$('#targetLanguages .dropdown').removeClass('validation-icon')
			$('#targetLanguages .validation-label').hide()
			targetLanguage.parent().removeClass('validation-failed')
		}

		return validation;
	}


	$('.conveythis-widget-option-form, #login-form-settings').submit( function(e){
		let apiKey = $("#conveythis_api_key").val();
		let validation = checkValidation();
		if (validation === false){
			e.preventDefault();
		}

		jQuery.ajax({
			url: 'options.php',
			method: 'POST',
			data: {'api_key': apiKey}
		})

		let targetLanguagesTranslations = {};
		let $tLangTranslations = $("#target_languages_translations input[language_id]");
		for (let t of $tLangTranslations) {
			let languageTranslation = t.value.trim();
			if (languageTranslation.indexOf('/') > -1) {
				alert('Translation cannot contain slash: ' + languageTranslation);
				return false;
			}
			if (languageTranslation) {
				targetLanguagesTranslations[t.getAttribute('language_id')] = languageTranslation;
			}
		}
		$('input[name="target_languages_translations"]').val(JSON.stringify(targetLanguagesTranslations));

		let exclusions = [];
		$('div.exclusion').each(function(){

            let rule = $(this).find('.rule select').val();
            let pageUrl = $(this).find('input.page_url').val().trim();
            if (rule && pageUrl) {
            	let ex = {rule: rule, page_url: pageUrl};
				let exclusionId = $(this).find('input.exclusion_id').val();
				if (exclusionId) {
					ex.id = exclusionId;
				}
				exclusions.push(ex);
			}
		});
		$('input[name="exclusions"]').val(JSON.stringify(exclusions));

		let glossaryRules = [];
		$('div.glossary').each(function(){
			let rule = $(this).find('.rule select').val();

			let sourceText = $(this).find('input.source_text').val().trim();
			let translateText = $(this).find('input.translate_text').val().trim();
			let targetLanguage = $(this).find('.target_language select').val().trim();
			if (rule && sourceText) {
				let gl = {rule: rule, source_text: sourceText, translate_text: translateText, target_language: targetLanguage};
				let glossaryId = $(this).find('input.glossary_id').val();
				if (glossaryId) {
					gl.glossary_id = glossaryId;
				}
				glossaryRules.push(gl);
			}
		});

		$('input[name="glossary"]').val(JSON.stringify(glossaryRules));

		let exclusion_blocks = [];
		$('div.exclusion_block').each(function(){
			let idValue = $(this).find('input.id_value').val().trim();
			if (idValue) {
				let exBlock = {id_value: idValue};
				let exclusionBlockId = $(this).find('input.exclusion_block_id').val();
				if (exclusionBlockId) {
					exBlock.id = exclusionBlockId;
				}
				exclusion_blocks.push(exBlock);
			}
		});
		$('input[name="exclusion_blocks"]').val(JSON.stringify(exclusion_blocks));

		let system_links = [];
		$('div.system_link').each(function(){

			let link = $(this).find('input.link_text').val();

			if (!!link) {
				let sl = { link: link };
				let linkId = $(this).find('input.system_link_id').val();
				if (!!linkId) {
					sl.link_id = linkId;
				}
				system_links.push(sl);
			}
		})
		$('input[name="conveythis_system_links"]').val(JSON.stringify(system_links));

	});



	$('input[name=target_languages]').change();


	function hideTargetLanguage(){
		$('.dropdown-target-languages .item').show();
		let currentLanguage = $('.dropdown-current-language').dropdown('get value');
		if(currentLanguage != ''){
			$('.target-language-'+currentLanguage).hide();
		}
	}

	var _alias = "";

	var inputElementClearCache = document.getElementById('conveythis_clear_cache');

	inputElementClearCache.addEventListener('input', function() {

		var value = this.value;

		if (value === '') return;

		var hour = 720;
		if(_alias == "grand")
			hour = 7200;

		if (!/^\d+$/.test(value) || (value < 0 || value > hour)) {
			this.value = this.dataset.lastValidValue || '';
		} else {
			this.dataset.lastValidValue = value;
		}
	});

	inputElementClearCache.addEventListener('blur', function() {
		if (this.value.trim() === '') {
			this.value = '0';
		} else if (/^0[1-9]+/.test(this.value)) {
			this.value = parseInt(this.value, 10).toString();
		}
	});

	// Sort flags by languages
	function sortFlagsByLanguage(){
		$('.ui.dropdown.change_language').dropdown({
			onChange: function(value) {

				let $dropdown = $(this).closest('.row').find('.ui.dropdown.change_flag');
				let flagCodes = languages[value]['flag_codes'];
		
				$dropdown.find('.menu').empty();
				$dropdown.find('.text').empty();

				$.each(flagCodes, function(code, title) {
					
					let newItem = $('<div class="item" data-value="' + code + '">\
										<div class="ui image" style="height: 28px; width: 30px; background-position: 50% 50%;\
																	background-size: contain; background-repeat: no-repeat;\
																	background-image: url(\'//cdn.conveythis.com/images/flags/svg/' + code + '.svg\')"></div>\
										' + title + '\
									</div>');
		
					$dropdown.find('.menu').append(newItem);
					
				});	
			},
		});
	};

	function getUserPlan(){
		try{
			let apiKey = $("#conveythis_api_key").val(); 
			
			if(apiKey){
				jQuery.ajax({
					url: "https://api.conveythis.com/25/admin/account/plan/api-key/"+apiKey+"/",
					success: function(result){
						if(result.data && result.data.languages){
							const maxLanguages = result.data.languages;
							$('.dropdown-target-languages').dropdown({
								maxSelections: maxLanguages,
								message: {
									maxSelections: 'Need more languages? <a href="//app.conveythis.com/dashboard/pricing" target="_blank">Upgrade your plan</a>.'
								},
								onChange: function() {
									conveythisSettings.view();
									showDnsRecords();
								}
							});
							hideTargetLanguage();
							let tempLanguages = $('.dropdown-target-languages').dropdown('get value');
							if(tempLanguages){
								try{
									let tempLanguagesArray = tempLanguages.split(",");
									if(tempLanguagesArray.length > maxLanguages){									
										let allowedLanguages = [];
										for(let i = 0; i < maxLanguages; i++)
											allowedLanguages.push(tempLanguagesArray[i]);
										let allowedLanguagesStr = allowedLanguages.join(",");
										$('.dropdown-target-languages').dropdown('set value', allowedLanguagesStr);
										
										setTimeout(function(){
											$('.dropdown-target-languages').dropdown('set selected', allowedLanguagesStr);
										},200);
									}
								}catch(e){}
							}

							if (!result.data.meta.alias.includes('free')){
								$('.hide-paid').remove();
							} else {
								$('.paid-function input').prop('disabled', true)
								$('.paid-function button').prop('disabled', true)
							}

							_alias = result.data.meta.alias;

							if (
								result.data.meta.alias != "pro" &&
								result.data.meta.alias != "pro_new" &&
								result.data.meta.alias != "pro_plus" &&
								result.data.meta.alias != "pro_new_plus" &&
								result.data.meta.alias != "enterprise")
							{
								$('input[name=url_structure][value=regular]').prop('checked', true);
								$('input[name=url_structure][value=subdomain]').prop('disabled', true);
								$('#dns-plan-error').show();
								$('#dns-setup').hide();
							}

							if(
								result.data.meta.alias != "enterprise" &&
								result.data.meta.alias != "grand"
							)
							{
								$('#conveythis_clear_cache').prop('disabled', true);
							}

							if(result.data.is_trial_expired == 1){
								$('#conveythis-trial-expired-message').show();
							}


							if ( typeof(result.data.is_confirmed) !== "undefined" && result.data.is_confirmed !== null
								&& typeof(result.data.activ_to) !== "undefined" && result.data.activ_to !== null){
								if(result.data.is_confirmed != 1 && result.data.activ_to > 0){
									activeTo = result.data.activ_to;
									if(Math.floor(Date.now() / 1000) < activeTo){
										documentLocale = document.querySelector('html').getAttribute('lang');
										active_to_time = new Date(activeTo * 1000).toLocaleString(documentLocale, { timeZone: 'UTC' });
										$("#conveythis_confirmation_message_warning > span").text(active_to_time);
										$('#conveythis_confirmation_message_warning').show();
									} else {
										$("#conveythis_confirmation_message_danger > span > b").text(result.data.email);
										$('#conveythis_confirmation_message_danger').show();
									}
								}
							}

							if(typeof(result.data.word_limit) !== "undefined" && result.data.word_limit){
								$('#conveythis_word_translation_exceeded_warning').show();
							}
						}
					}
				});
			}
		}catch(e){}
	}

	$('button#main-tab, button#widget-style-tab').on('shown.bs.tab', function(e) {
		let widget = document.querySelector('.widget-preview');
		let widgetPreviewStyle = document.getElementById('widget-preview-style');
		let widgetPreviewGeneral = document.getElementById('widget-preview-general');

		if (widget && widgetPreviewStyle && widgetPreviewGeneral) {
			if (this.id == 'widget-style-tab')
			{
				widgetPreviewStyle.appendChild(widget);
				widgetPreviewGeneral.innerHTML = '';
			}
			else
			{
				widgetPreviewGeneral.appendChild(widget);
				widgetPreviewStyle.innerHTML = '';
			}
		}
	});

	setTimeout(function(){
		// validateApiKey()
		getUserPlan();
	},1000);

});
