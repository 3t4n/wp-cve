jQuery(function () {
    var $ = jQuery,

    categories = {},

    /* Templates */
    dialog_tpl = `
		<div class="cff-form-library-cover">
			<div class="cff-form-library-container">
				<div class="cff-form-library-column-left">
					<div class="cff-form-library-search-box">
						<div class="cff-form-library-close"></div>
						<input type="search" placeholder="Search..." onkeyup="cff_filteringFormsByText(this)">
					</div>
					<div class="cff-form-library-categories">
						<ul>
							<li><a href="javascript:void(0);" onclick="cff_templatesInCategory(this);" class="cff-form-library-active-category">All Categories</a></li>
						</ul>
					</div>
				</div>
				<div class="cff-form-library-column-right">
					<div>
						<div class="cff-form-library-blank-form">
							<input type="text" placeholder="Form name..." id="cp_itemname_library">
							<input type="button" value="Create Basic Form" class="button-primary" onclick="cff_getTemplate(0);">
						</div>
						<div class="cff-form-library-close"></div>
						<div style="clear:both"></div>
					</div>
					<div class="cff-form-library-main">
						<div class="cff-form-library-no-form">No form meets the search criteria</div>
					</div>
				</div>
			</div>
		</div>
	`,

    form_tpl = `
		<div class="cff-form-library-form">
			<div class="cff-form-library-form-title"></div>
			<div class="cff-form-library-form-description"></div>
			<div class="cff-form-library-form-category"></div>
			<div>
				<input type="button" class="button-primary" value="Use It" />
			</div>
		</div>
	`,

	form_name_library_field;

    $.expr.pseudos.contains = $.expr.createPseudo(function (arg) {
        return function (elem) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });

    function openDialog(explicit) {

        var version = 'free',
			form_name_field = $('[id="cp_itemname"]'),
			form_tag = form_name_field.closest('form')[0],
			data = [];

		form_name_field.val(form_name_field.val().replace(/^\s*/, '').replace(/\s*$/, ''));

		if( ( typeof explicit == 'undefined' || !explicit ) && 'reportValidity' in form_tag && !form_tag.reportValidity()) return;

        if (!$('.cff-form-library-container').length) {
            $('body').append(dialog_tpl);

            if (typeof cpcff_forms_library_config != 'undefined' && 'version' in cpcff_forms_library_config) {
                version = cpcff_forms_library_config['version'];
            }

            if (typeof cff_forms_templates != 'undefined') {
                switch (version) {
                case 'free':
                    data = data.concat(cff_forms_templates['free']);
                    break;
                case 'pro':
                    data = data.concat(cff_forms_templates['free'], cff_forms_templates['pro']);
                    break;
                case 'dev':
                    data = data.concat(cff_forms_templates['free'], cff_forms_templates['pro'], cff_forms_templates['dev']);
                    break;
                case 'plat':
                    data = data.concat(cff_forms_templates['free'], cff_forms_templates['pro'], cff_forms_templates['dev'], cff_forms_templates['plat']);
                    break;
                }

                if (data.length) {
                    for (var i in data) {

                        categories[data[i]['category']] = '<li><a href="javascript:void(0);" onclick="cff_templatesInCategory(this,\'' + data[i]['category'] + '\')">' + data[i]['category'] + '</a></li>';

                        tmp = $(form_tpl);
                        tmp.attr('data-category', data[i]['category']);
                        tmp.find('.cff-form-library-form-title').text(data[i]['title']);
                        tmp.find('.cff-form-library-form-description').text(data[i]['description']);
                        tmp.find('.cff-form-library-form-category').text(data[i]['category']);
                        tmp.find('[type="button"]').on(
                            'click',
                            (function (id) {
                                return function () {
                                    cff_getTemplate(id);
                                };
                            })(data[i]['id']));
                        tmp.appendTo('.cff-form-library-main');
                    }

                    for (var i in categories) {
                        $(categories[i]).appendTo('.cff-form-library-categories ul');
                    }
                }
            }
        };

		$(document).on('keyup', '[id="cp_itemname_library"]', function(evt){
			var keycode = (evt.keyCode ? evt.keyCode : evt.which);
            if(keycode == 13){
                cff_getTemplate(0);
            }
		});

        // Initialize
        showNoFormMessage();
        $('.cff-form-library-search-box input').val('');
        $('.cff-form-library-categories ul>li:first-child a').trigger('click');
        $('.cff-form-library-cover').show();

		form_name_library_field = $('[id="cp_itemname_library"]');
		form_name_library_field.val(form_name_field.val());
    };

    function closeDialog() {
        $('.cff-form-library-cover').hide();
    };

    function showNoFormMessage() {
        $('.cff-form-library-no-form').show();
    };

    function hideNoFormMessage() {
        $('.cff-form-library-no-form').hide();
    };

    function displayTemplates(me, category) {
        hideNoFormMessage();
        $('.cff-form-library-search-box input').val('');
        $('.cff-form-library-active-category').removeClass('cff-form-library-active-category');
        $(me).addClass('cff-form-library-active-category');

        if (typeof category == 'undefined') {
            $('.cff-form-library-form').show();
        } else {
            $('.cff-form-library-form').hide();
            $('.cff-form-library-form[data-category="' + category + '"]').show();
        }
    };

    function formsByText(me) {

        var v = String(me.value).trim();

        $('.cff-form-library-active-category').removeClass('cff-form-library-active-category');

        $('.cff-form-library-form').hide();

        $('.cff-form-library-form:contains("' + v + '")').each(function () {
            $(this).show();
        });

        if ($('.cff-form-library-form:visible').length) {
            hideNoFormMessage();
        } else {
            showNoFormMessage();
        }
    };

    function getTemplate(id) {
        var form_name = encodeURIComponent(form_name_library_field.val() || ''),
        category_name = encodeURIComponent($('[id="calculated-fields-form-category"]').val() || ''),
        url;

        if (typeof cpcff_forms_library_config != 'undefined' && 'website_url' in cpcff_forms_library_config) {
            url = cpcff_forms_library_config['website_url'] + '&name=' + form_name + '&category=' + category_name;
            if (id) {
                url += '&ftpl=' + encodeURIComponent(id);
            }
            document.location.href = url;
            closeDialog();
            return;
        }

        if ('cp_addItem' in window)
            cp_addItem();
    };

	$(document).on('keyup', function(evt){ if ( evt.keyCode == 27 ) { cff_closeLibraryDialog(); } });
	$(document).on('click', '.cff-form-library-close', closeDialog);

    // Export
    window['cff_openLibraryDialog'] = openDialog;
    window['cff_closeLibraryDialog'] = closeDialog;
    window['cff_getTemplate'] = getTemplate;
    window['cff_templatesInCategory'] = displayTemplates;
    window['cff_filteringFormsByText'] = formsByText;
});