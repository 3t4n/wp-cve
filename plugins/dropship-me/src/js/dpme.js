jQuery(function($){
    var price_format;
    var convert_value;

    function convertPrice( price ) {
        price = parseFloat(price) * convert_value;
        return price.toFixed(2);
    }

    function dm_formatMoney(n, c, d, t) {

        c = isNaN(c = Math.abs(c)) ? 2 : c;
        d = d === undefined ? "." : d;
        t = t === undefined ? "," : t;

        var s = n < 0 ? "-" : "",
            i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
            j = (i.length) > 3 ? i.length % 3 : 0;

        var price = s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) +
            (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");

        return price_format.pos === 'before' ? price_format.symbol + '' + price : price + '' + price_format.symbol;
    }

    var pricesHandlebars = {
        init : function () {
            Handlebars.registerHelper( 'numberFormat', function( value, options ) {

                value = parseFloat(value);

                var dl = options.hash['decimalLength'] || 2,
                    ts = options.hash['thousandsSep'] || ',',
                    ds = options.hash['decimalSep'] || '.';

                var re = '\\d(?=(\\d{3})+' + (dl > 0 ? '\\D' : '$') + ')';
                var num = value.toFixed(Math.max(0, ~~dl));

                return (ds ? num.replace('.', ds) : num).replace(new RegExp(re, 'g'), '$&' + ts);
            });
            Handlebars.registerHelper( 'format_price', function( value, options ) {

                value = parseFloat(value);

                var dl = options.hash['decimalLength'] || 2,
                    ts = options.hash['thousandsSep'] || ',',
                    ds = options.hash['decimalSep'] || '.';

                var re = '\\d(?=(\\d{3})+' + (dl > 0 ? '\\D' : '$') + ')';
                var num = value.toFixed(Math.max(0, ~~dl));

                var price = (ds ? num.replace('.', ds) : num).replace(new RegExp(re, 'g'), '$&' + ts);

                return price_format.pos === 'before' ? price_format.symbol + '' + price : price + '' + price_format.symbol;
            });

            Handlebars.registerHelper("math_format", function(lvalue, operator, rvalue, options) {
                lvalue = parseFloat(lvalue);
                rvalue = parseFloat(rvalue);

                return {
                    "+": dm_formatMoney(lvalue + rvalue),
                    "-": dm_formatMoney(lvalue - rvalue),
                    "*": dm_formatMoney(lvalue * rvalue),
                    "/": dm_formatMoney(lvalue / rvalue)
                }[operator];
            });

            Handlebars.registerHelper( 'image', function ( url, option ) {

                if( option === 1 )
                    return url.replace('_640x640.jpg', '_50x50.jpg');
                else
                    return url.replace('_640x640.jpg', '');
            } );

            Handlebars.registerHelper( 'lovercase', function ( str ) {

                return str.toLowerCase();
            } );
        }
    };

    pricesHandlebars.init();

    var obj = {
            form : '#dm-container',
            results : '#dm-container-result'
        },
        tmpl = {
            form : '#tmpl-form',
            results : '#tmpl-search-result',
            notfound : '#tmpl-not-found',
            import : '#tmpl-form-import',
            reviews : '#tmpl-reviews-list'
        },
        el = {
            cat : '#categoryId',
            subcat : '#subCategoryId',
            page : '#page',
            sort : '#sort',
            keywords : '#keywords',
            item : '.product-item-list',
            free : '#free',
            warehouse : '#warehouse',
            to : '#to',
            company : '#company',
            originalPriceFrom : '#originalPriceFrom',
            originalPriceTo : '#originalPriceTo',
            volumeFrom : '#volumeFrom',
            volumeTo : '#volumeTo',
            free_e : '#free_e',
            warehouse_e : '#warehouse_e',
            to_e : '#to_e',
            company_e : '#company_e',
            originalPriceFrom_e : '#originalPriceFrom_e',
            originalPriceTo_e : '#originalPriceTo_e',
            volumeFrom_e : '#volumeFrom_e',
            volumeTo_e : '#volumeTo_e',
        },
        act = {
            details : '.js-details',
            btn : '.js-import-product',
            apply : '.js-import-selected',
            apply_range : '.js-apply-range'
        };
    var words;
    var tabs = '';
    var user_menu_collapse= '';
    var SearchForm = {
        request : function( action, args, callback ) {
            words = $('#keywords').val();
            if($(document.body).hasClass('folded')){
                user_menu_collapse ='1';
                $('.settings-block').show();
                $('#wpbody-content .wrap').addClass('setting_open')
            }
            if(window.matchMedia("screen and (max-width: 576px)").matches){
                $('body .import-settings-btn').html('SETTINGS');
                $('body .params-list-sku').addClass('collapse');
            }
            args = args !== '' && args instanceof jQuery ? window.ADS.serialize(args) : args;
            $(el.to).selectpicker('refresh');
            $.ajaxQueue( {
                url     : ajaxurl,
                data: { action: 'dm_alidropship_api', ads_action: action, args: args },
                type    : 'POST',
                dataType: 'json',
                success : callback
            });
        },

        searchFormRender : function ( response ) {

            var template = $(tmpl.form).html(),
                target = $(obj.form);
            if( response ) {

                if( response.hasOwnProperty( 'error' ) ) {
                    window.ADS.notify( response.error, 'danger' );
                    window.ADS.screenUnLock();
                } else {
                    convert_value = response.convert_value;

                    target.html( window.ADS.objTotmpl( template, response ) );
                    setTimeout( window.ADS.switchery( target ), 300 );

                    $(document).trigger('search:request');
                }
            }
        },

        searchForm : function () {

            window.ADS.screenLock();

            this.request( 'search_form', '', this.searchFormRender );
        },

        subcatRender : function ( response ) {

            var target = $(el.subcat);

            if( response ) {

                if( response.hasOwnProperty( 'error' ) ) {
                    window.ADS.notify( response.error, 'danger' );

                    target.closest('.sub-cat-col').hide();
                    window.ADS.screenUnLock();
                } else if( response.hasOwnProperty( 'success' ) ) {
                    target.closest('.sub-cat-col').hide();

                    $(document).trigger('search:request');
                } else {

                    var layout = '';

                    $.each( response, function( i, v ) {
                        layout += '<option value="'+v.key+'">'+v.val+'</option>';
                    } );

                    target.html(layout).each( function () {

                        if ( $( this ).find( 'option' ).length > 20 )
                            $( this ).data( 'live-search', 'true' );

                    } ).selectpicker('refresh');

                    target.closest('.sub-cat-col').show();

                    $(document).trigger('search:request');
                }
            }
        },

        subcat : function() {
            this.request( 'subcat', $(obj.form), this.subcatRender );
        },

        searchRender : function ( response ) {

            var target  = $(obj.results),
                found_text = 0;

            if( response ) {
                if( response.hasOwnProperty( 'error' ) ) {
                    window.ADS.notify( response.error, 'danger' );

                    if( response.hasOwnProperty('total') ) {
                        target.html( window.ADS.objTotmpl( $(tmpl.notfound).html(), response ) );
                        window.ADS.createJQPagination( obj.form, 0, 1, 40);
                        $(el.to).selectpicker('refresh');
                    }
                } else {

                    var f = $('.tab-nav-filters');
                    if( f.hasClass('d-none'))
                        f.removeClass('d-none');

                    found_text    = response.total;
                    price_format  = response.currency_format;

                    if( response.hasOwnProperty( 'notavailable' ) ) {
                        $( '#items-notfounded' ).html(response.notavailable);
                    }

                    target.html( window.ADS.objTotmpl( $(tmpl.results).html(), response ) );

                    if( target.find('.pagination-menu').length ){
                        window.ADS.createJQPagination( '#'+target.attr('id'), response.total, response.page, 40);
                        window.ADS.createJQPagination( obj.form, response.total, response.page, 40);
                    }
                    $('#sortby').val( $('#sort').val() ).selectpicker('refresh');
                    setTimeout( window.ADS.switchery( target ), 300 );

                    if( response.hasOwnProperty('breadcrumbs') && response.breadcrumbs ){
                        var c = response.breadcrumbs.length,
                            layout = $('.breadcrumb-list');

                        layout.html( '' );
                        $.each( response.breadcrumbs, function(i, v){
                            layout.append(' > ');

                            if( i+1 === c )
                                layout.append(v.title + ' ');
                            else {
                                var a = $('<a/>', {
                                    href: 'javascript:;',
                                    text: v.title
                                }).attr({
                                    'data-cat': v.id,
                                    'class': 'color-blue',
                                    'data-selector' : (i === 0) ? 'categoryId' : 'subCategoryId'
                                });

                                layout.append(a);
                            }
                        } );
                    } else {
                        $('.breadcrumb-list').html('');
                    }
                }
                var outfound_text = (found_text+'').replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1,');
                $('#items-founded').text(outfound_text);
                $('#has_deposit').text(response.deposit);
                window.ADS.screenUnLock();
            }
        },

        searchRequest : function() {
            window.ADS.screenLock();

            if( $('#categoryId').length ){
                this.request( 'search', $(obj.form), this.searchRender );
            }
            else {
                this.request( 'search_my', $(obj.form), this.searchRender );
            }        },

        showDetails : function ( product ) {

            var info = $('#supplier-'+product.data.id );

            $('#product-'+product.data.id).hide();
            var to = $('#to').val();

            info.html( window.ADS.objTotmpl( $( '#tmpl-view-details' ).html(), product.data ) );
            setTimeout( window.ADS.switchery( info ), 300 );
            setTimeout( function () {
                info.find('#ship_country_' + product.data.productId).val(to).trigger('change');
            }, 400 );
            info.find('.carousel').carousel().swipe({
                swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
                    if (direction === 'left') $(this).carousel('next');
                    if (direction === 'right') $(this).carousel('prev');
                },
                allowPageScroll:"vertical"
            });

            $('[data-id="'+product.data.id+'"]').addClass('opened');
            if(window.matchMedia("screen and (max-width: 576px)").matches){
                $('[data-id="'+product.data.id+'"]').addClass('opened_mobile');
            }
            window.ADS.screenUnLock();
            if(window.matchMedia("screen and (max-width: 576px)").matches){
                $('body [id*="dm_collapseVariants-"]').addClass('collapse');
                tabs = $('.mobile_dest').detach();
                $('.tab-pane.active.show').removeClass('active show');
            }
        },

        importFormRender : function ( response ) {

            if( response ) {

                if( response.hasOwnProperty( 'error' ) ) {
                    window.ADS.notify( response.error, 'danger' );
                } else {

                    var categoryImport = '';

                    $.each( response.values_categoryImport, function( i, v ) {
                        categoryImport += '<option value="'+v.value+'">'+v.title+'</option>';
                    } );

                    $('#categoryImport').html(categoryImport).each( function () {

                        if ( $( this ).find( 'option' ).length > 20 )
                            $( this ).data( 'live-search', 'true' );

                    } ).selectpicker('refresh');

                    window.ADS.notify( response.success, 'success' );
                }
                window.ADS.screenUnLock();
            }
        },

        checker : function () {

            var a = '#checkAll';

            $(document).on('change', a, function(){
                $(obj.results).find('[type="checkbox"]').prop('checked', $(this).prop("checked"));
                $.uniform.update();
                var count_checked = $(obj.results).find('[type="checkbox"]:checked').length;
                if (count_checked > 0){
                    $('#count_ckeck').html(' ('+count_checked+')');
                }
                else{
                    $('#count_ckeck').html('');
                }
            });

            $(obj.results).on('change', '[type="checkbox"]', function(){
                var count_checked = $(obj.results).find('[type="checkbox"]:checked').length;
                if (count_checked > 0){
                    $('#count_ckeck').html(' ('+count_checked+')');
                }
                else{
                    $('#count_ckeck').html('');
                }
                if(false === $(this).prop('checked')){
                    $(a).prop('checked', false).uniform.update();
                }

                if( $(obj.results).find('[type="checkbox"]:checked').length === $(obj.results).find('[type="checkbox"]').length ){
                    $(a).prop('checked', true).uniform.update();
                }
            });
        },

        reportRender: function( response ) {

            if( response.hasOwnProperty( 'error' ) ) {
                window.ADS.notify( response.error, 'danger' );
            }

            if( response.hasOwnProperty( 'success' ) ) {
                window.ADS.notify( response.success, 'success' );
                $('#reportModal').modal('hide');
            }
        },
        renderProgress: function( response ) {

            if( response.hasOwnProperty( 'error' ) ) {
                window.ADS.notify( response.error, 'danger' );
            }

            if( response.hasOwnProperty( 'success' ) ) {
                window.ADS.progress( '#checker-progress', response.total, response.current );
            }
        },

        checkNot : function(p) {
            this.request('check_not', 'page='+p, this.renderProgress);
        },

        handler : function() {

            var $this = this;

            $(document).on('search:request', function(){
                window.ADS.screenLock();

                var d = $(document).find('.jqpagination');
                if( d.find('a').length )
                    d.jqPagination('destroy');

                $this.searchRequest();
            });

            $(document).on('change', el.cat, function(){
                $(el.page).val(1);
                $this.subcat();
            });

            $(document).on('change', el.subcat, function(){
                $(el.page).val(1);
                $(document).trigger('search:request');
            });

            $(document).on('change', el.warehouse, function(){
                $(el.page).val(1);
                $(document).trigger('search:request');
            });

            $(document).on('change', el.to, function(){
                $(el.page).val(1);
                $(el.to).selectpicker('refresh');
                $(document).trigger('search:request');

            });

            $(document).on('change', el.company, function(){
                $(el.page).val(1);
                $(document).trigger('search:request');
            });

            $(document).on('change', el.free, function(){
                $(el.page).val(1);
                $(document).trigger('search:request');
            });

            $(document).on('click', act.apply_range, function(){
                $(el.page).val(1);
                $(document).trigger('search:request');
            });
            $(document).on('click', '.clear_all', function(){
                $(el.page).val(1);
                $(el.originalPriceFrom).val('');
                $(el.originalPriceTo).val('');
                $(el.volumeFrom).val('');
                $(el.volumeTo).val('');
                $(el.sort).val('volumeDown');
                $(el.warehouse).val('').selectpicker('refresh');
                $(el.to).val('US').selectpicker('refresh');
                $(el.company).val('9999').selectpicker('refresh');
                $(el.originalPriceFrom_e).val('');
                $(el.originalPriceTo_e).val('');
                $(el.volumeFrom_e).val('');
                $(el.volumeTo_e).val('');
                $(el.sort_e).val('');
                $(el.warehouse_e).val('').selectpicker('refresh');
                $(el.to_e).val('US').selectpicker('refresh');
                $(el.company_e).val('9999').selectpicker('refresh');
                $('#supplier').val('');
                $(document).trigger('search:request');
                $('#uniform-free span').removeClass('checked');
                $('#uniform-free_e span').removeClass('checked');

            });
            $(document).on('click', '#collapse-button', function(){
                if($('#wpbody-content .wrap').hasClass('setting_open')){
                    setUserSetting("fold",1);
                    setUserSetting("unfold",0);
                    setUserSetting("mfold","1");
                    $('.settings-block').hide();
                    $(document.body).removeClass('folded');
                    $('#wpbody-content .wrap').removeClass('setting_open')
                }
                else{
                    setUserSetting("unfold",1);
                    setUserSetting("fold",0);
                    setUserSetting("mfold","o");
                    $(document.body).addClass('folded');
                    $('.settings-block').show();
                    $('#wpbody-content .wrap').addClass('setting_open');
                }
            });
            $(document).on('click', '#settings_toggle', function(){
                if($('#wpbody-content .wrap').hasClass('setting_open')){
                    $('.settings-block').hide();
                    $('#wpbody-content .wrap').removeClass('setting_open');
                    $(document.body).removeClass('folded');
                }
                else{
                    $('#wpwrap').removeClass('wp-responsive-open');
                    $('.settings-block').show();
                    $('#wpbody-content .wrap').addClass('setting_open');
                    $(document.body).addClass('folded');
                }
            });
            $(document).on('click', '.ab-item', function(){
                if($('#wpbody-content .wrap').hasClass('setting_open')){
                    $('.settings-block').hide();
                    $('#wpbody-content .wrap').removeClass('setting_open');
                }
            });
            $(document).on('click', '#close_filters', function(){
                if($('#wpbody-content .wrap').hasClass('setting_open')){
                    $('.settings-block').hide();
                    $('#wpbody-content .wrap').removeClass('setting_open');
                    $(document.body).removeClass('folded');
                }
            });
            $(document).on('change', '#create_cat', function(){
                var create_cat = $('#create_cat').is(':checked') ? 1 : 0;
                $.ajaxQueue( {
                    url     : ajaxurl,
                    data: {
                        action: 'dm_alidropship_api',
                        ads_action: 'save_settings',
                        args: 'create_cat='+create_cat
                    },
                    type    : 'POST'
                });
            });
            $(document).on('change', '#cat_status', function(){
                var cat_status = $('#cat_status').val();
                $.ajaxQueue( {
                    url     : ajaxurl,
                    data: {
                        action: 'dm_alidropship_api',
                        ads_action: 'save_settings',
                        args: 'cat_status='+cat_status
                    },
                    type    : 'POST'
                });
            });
            $(document).on('change', '#attributes', function(){

                var attributes = $('#attributes').is(':checked') ? 1 : 0;

                $.ajaxQueue( {
                    url : ajaxurl,
                    data: {
                        action: 'dm_alidropship_api',
                        ads_action: 'save_settings',
                        args: 'attributes='+attributes
                    },
                    type    : 'POST'
                });
            });

            $(document).on('change', '#publish', function(){

                var publish = $('#publish').is(':checked') ? 1 : 0;

                $.ajaxQueue( {
                    url     : ajaxurl,
                    data: {
                        action: 'dm_alidropship_api',
                        ads_action: 'save_settings',
                        args: 'publish='+publish
                    },
                    type    : 'POST'
                });
            });

            $(document).on('change', '#recommended_price', function(){

                var recommended_price = $('#recommended_price').is(':checked') ? 1 : 0;

                $.ajaxQueue( {
                    url     : ajaxurl,
                    data: {
                        action: 'dm_alidropship_api',
                        ads_action: 'save_settings',
                        args: 'recommended_price='+recommended_price
                    },
                    type    : 'POST'
                });
            });

            $(document).on('click', '#create_cat', function(){

                var cat = $('#categoryImport').closest('.form-group'),
                    catm = $('#categoryImportMobile').closest('.form-group'),
                    dm  = $('#categoryImportDM'),
                    dmm  = $('#categoryImportDMMobile'),
                    cs  = $('#cat_status').closest('.form-group'),
                    cse  = $('#cat_status_e').closest('.form-group');

                if( ! $(this).is(':checked') ) {
                    cat.show();
                    cs.hide();
                    cse.hide();
                    catm.show();
                    dm.hide();
                    dmm.hide();
                } else {
                    cat.hide();
                    cs.show();
                    cse.show();
                    catm.hide();
                    dm.show();
                    dmm.show();
                }
            });

            $(document).on("keypress keyup blur", '#originalPriceFrom, #originalPriceTo',function (event) {
                $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });
            $(document).on("keypress keyup blur", '#volumeFrom, #volumeTo',function (event) {
                $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });
            $(document).on( 'keyup', '#originalPriceFrom, #originalPriceTo, #volumeFrom, #volumeTo', function (e) {

                var code;
                if (e.key !== undefined) {
                    code = e.key;
                } else if (e.keyIdentifier !== undefined) {
                    code = e.keyIdentifier;
                } else if (e.keyCode !== undefined) {
                    code = e.keyCode;
                }

                if( code !== 13 && code !== 'Enter' )
                    return false;
                $(el.page).val(1);
                $(document).trigger('search:request');
            });
            $(document).on('change', '#sortby', function(e){
                $(el.page).val(1);
                $(el.sort).val( $(this).val() );
                $(document).trigger('search:request');
            });

            $(document).on('click', act.details, function () {

                var p = $(this).closest( el.item ),
                    id = p.data('id');
                $('body').addClass('mobile-overflow');
                if( p.hasClass('opened') ) {
                    if( $(this).data('action') === 'show' ) {
                        $('#product-' +id).hide();
                        $('#supplier-' +id).show();
                        p.addClass('opened_mobile');
                    } else {
                        $('#product-' +id).show();
                        $('#supplier-' +id).hide();
                        p.removeClass('opened_mobile');
                        $('body').removeClass('mobile-overflow');
                    }
                } else {
                    window.ADS.screenLock();
                    $this.request( 'info', 'id='+id, $this.showDetails );
                }
            });

            $(document).on('click', '#search-btn', function() {

                if( $('#keywords').val().length <= 2 && $('#keywords').val() !== '') {
                    window.ADS.notify( $( '#errorKeyword' ).val(), 'danger' );
                    return false;
                }
                $(el.page).val(1);
                $(document).trigger('search:request');
            });
            $(document).on('click', '.js-set_supplier', function() {
                let supplier_id = $(this).data("supplier");
                $(el.page).val(1);
                $('#supplier').val(supplier_id);
                $(document).trigger('search:request');
            });
            $(document).on( 'focusout', '#keywords', function (e) {

                var key_val = $('#keywords').val();

                if( ( key_val === '' || key_val.length >= 3 ) && key_val !== words ) {
                    $(el.page).val(1);
                    $(document).trigger('search:request');
                }
                else if( key_val.length <= 2 && key_val !== '' ){
                    window.ADS.notify( $( '#errorKeyword' ).val(), 'danger' );
                    return false;
                }
            });
            $(document).on( 'keyup', '#keywords', function (e) {

                var code;
                if (e.key !== undefined) {
                    code = e.key;
                } else if (e.keyIdentifier !== undefined) {
                    code = e.keyIdentifier;
                } else if (e.keyCode !== undefined) {
                    code = e.keyCode;
                }

                if( code !== 13 && code !== 'Enter' )
                    return false;

                if( $(this).val().length <= 2 && $('#keywords').val() !== '') {
                    window.ADS.notify( $( '#errorKeyword' ).val(), 'danger' );
                    return false;
                }
                $(el.page).val(1);
                $(document).trigger('search:request');
            });

            $(document).on('pagination:click', function(e){

                var p = parseInt( $(el.page).val() );
                if( p !== parseInt(e.page) ) {
                    $(el.page).val(e.page);
                    $(document).trigger('search:request');
                }
            });

            $(document).on( 'click', '.breadcrumbs-content a', function(){
                var s = $(this).data('selector');
                if( s ) {
                    if( $(this).data('cat') )
                        $('#'+$(this).data('selector')).val($(this).data('cat')).trigger('change');
                    else
                        $(el.cat).val(0).trigger('change');

                    if( s === 'categoryId' ) {
                        $('#subCategoryId').val(0);
                    }
                }
            } );

            $(document).on( 'click', '.report-this a', function() {

                var $mod = $('#reportModal');
                $('#report-id').val($(this).data('id'));
                $mod.modal('show');
                $mod.on('shown.bs.modal', function () {
                    $('#report-message').val('').focus();
                })
            } );

            $('#reportModal').on('click', 'button', function() {

                var m = $('#report-message').val().trim();
                var rt = $('#report-type').val();
                if( m.length < 5 ) {
                    window.ADS.notify( 'Please enter a comment', 'danger' );
                }
                else if ( rt === '0' ) {
                    window.ADS.notify( 'Please select a reason', 'danger' );
                }
                else {
                    $this.request('send_report', $('#reportModal'), $this.reportRender);
                }
            });

            $('#js-startCheck').on('click', function() {
                $this.checkNot(1);
            });

        },

        init: function () {

            if( ! $('.maintenance').length ) {

                if( $('#dm-not-container').length ) {

                } else {
                    this.checker();
                    this.searchForm();
                }

                this.handler();
            }
        }
    };
    SearchForm.init();

    var ImportProducts = {

        request : function( action, args, callback ) {

            args = args !== '' && args instanceof jQuery ? window.ADS.serialize(args) : args;

            $.ajaxQueue( {
                url     : ajaxurl,
                data: { action: 'dm_alidropship_api', ads_action: action, args: args },
                type    : 'POST',
                dataType: 'json',
                success : callback
            });
        },

        importProduct : function( id, cat, cc, pp, r, at, cs ) {
            this.request(
                'import_product',
                'id='+id+'&cat_status='+cs+'&cat='+cat+'&create='+cc+'&publish='+pp+'&recommended_price='+r+'&attributes='+at,
                this.importRender
            );
        },

        importRender : function( response ) {

            if( response.hasOwnProperty( 'error' ) ) {
                window.ADS.notify( response.error, 'danger' );
                $(document).find( act.btn ).removeClass('animate-spinner').find('i').removeClass('infinite');
            } else {

                if( response.hasOwnProperty( 'success' ) ) {
                    window.ADS.notify( response.success, 'success' );
                }

                if( response.hasOwnProperty( 'deposit' ) ) {
                    $('#has_deposit').text( response.deposit );
                }

                ImportProducts.importImages( response );
            }
        },

        importImages : function( args ) {

            this.request( 'import_images', window.Base64.encode( JSON.stringify( args ) ), this.imagesRender );
        },

        imagesRender : function( response ) {

            if( response.hasOwnProperty( 'error' ) ) {
                window.ADS.notify( response.error, 'danger' );
            }

            if( response.hasOwnProperty( 'success' ) ) {
                window.ADS.notify( response.success, 'success' );

                if( response.hasOwnProperty( 'product' ) ) {
                    var item = $(obj.results).find('[data-id="'+response.product+'"]');

                    item.find(act.btn).removeClass('animate-spinner').attr('disabled', 'disabled').text('Imported');
                    item.find('.progress-bar').addClass('progress-bar-green').css( 'width', '100%');
                }
            }

            if( response.hasOwnProperty( 'message' ) ) {

                if( response.hasOwnProperty( 'product' ) ) {

                    var pi = $(obj.results).find('[data-id="'+response.product+'"]');
                    pi.find('.progress-bar').css( 'width', response.percent+'%');
                }

                setTimeout( function() {
                    ImportProducts.request( 'import_images', window.Base64.encode( JSON.stringify( response ) ), ImportProducts.imagesRender );
                }, 500 );
            }
        },

        handler : function() {

            var $this = this;

            $(document).on('click', act.btn, function(){
                if(window.matchMedia("screen and (max-width: 576px)").matches && $('#categoryImport').val()){
                    $('#categoryImport').val('default');
                    $('#categoryImport').selectpicker("refresh")
                }
                else if(window.matchMedia("screen and (min-width: 577px)").matches && $('#categoryImportMobile').val()){
                    $('#categoryImportMobile').val('default');
                    $('#categoryImportMobile').selectpicker("refresh")
                }
                var th  = $(this).closest(el.item),
                    id  = th.data('id'),
                    c   = $('#categoryImport'),
                    cm   = $('#categoryImportMobile'),
                    cc  = $('#create_cat').is(':checked') ? 1 : 0,
                    at  = $('#attributes').is(':checked') ? 1 : 0,
                    pp  = $('#publish').is(':checked') ? 1 : 0,
                    r   = $('#recommended_price').is(':checked') ? 1 : 0,
                    cs   = $('#cat_status').val(),
                    cat = c.val() ? c.val() : cm.val() ? cm.val() : 0;

                th.find(act.btn).addClass( 'disabled' ).find( 'i' ).addClass( 'infinite' );

                $this.importProduct(id, cat, cc, pp, r, at ,cs);
            });

            $(document).on( 'click', act.apply, function () {

                var a = $('#actions').val();

                //if( a !== 'bulkImport' ) return;

                var items = $(obj.results).find( el.item +' input:checkbox:checked');

                if ( items.length === 0 ) return;

                var c   = $('#categoryImport'),
                    cc  = $('#create_cat').is(':checked') ? 1 : 0,
                    at  = $('#attributes').is(':checked') ? 1 : 0,
                    pp  = $('#publish').is(':checked') ? 1 : 0,
                    r   = $('#recommended_price').is(':checked') ? 1 : 0,
                    cs   = $('#cat_status').val(),
                    cat = c.length ? c.val() : 0;

                items.each( function () {

                    var item = $( this ).closest(el.item),
                        btn = item.find( '.first-btn' );
                    title = item.find( '.product-title h3' ).html();
                    var attr = btn.attr('disabled');

                    if( btn.hasClass('disabled') || ( typeof attr !== typeof undefined && attr !== false ) ){
                        window.ADS.notify( '<strong>'+title+'</strong> is already imported', 'danger' );
                        return true;
                    }

                    item.find(act.btn).addClass( 'disabled' ).find( 'i' ).addClass( 'infinite' );

                    var id = item.data( 'id' );

                    $this.importProduct(id, cat, cc, pp, r, at, cs);

                } );

            });
        },
        init: function () {

            if( ! $('.maintenance').length ) {
                this.handler();
            }
        }
    };
    ImportProducts.init();

    function str_replace(search, replace, subject) {
        return subject.split(search).join(replace);
    }

    function parseUrl(url) {

        var chipsUrl = url.split('?'),
            hostName = chipsUrl[0],
            paramsUrl = chipsUrl[1],
            chipsParamsUrl = paramsUrl.split('&'),
            urlArray = {};

        $.each(chipsParamsUrl, function(i, value) {
            var tempChips = value.split('=');
            urlArray[tempChips[0]] = tempChips[1];
        });

        return {
            'hostName' : hostName,
            'urlArray' : urlArray
        };
    }

    function changeUrl(url, params) {

        if (typeof params === 'undefined') {
            return false;
        }

        var result = parseUrl(url);

        $.each(params, function(key, value) {
            result.urlArray[key] = value;
        });

        return buildUrl(result.hostName, result.urlArray);
    }

    function buildUrl(hostName, urlArray) {
        var url = hostName + '?';
        var urlParams = [];

        $.each(urlArray, function(index, value) {
            if (typeof value === 'undefined') {
                value = '';
            }
            urlParams.push(index + '=' + value);
        });

        url += urlParams.join('&');
        return url;
    }
    function sortDatasKeys( obj, sub_title, time ) {

        var dates = getDates( new Date(Date.now() - 1296000000), Date.now() - 172800000 );
        var foo = [],
            f = '',
            t = '';

        $.each( dates, function( i, v ) {
            var d = new Date(v);
            var formated_date =
                d.getFullYear() + '/' +
                ('0' + (d.getMonth() + 1)).slice(-2) + '/' +
                ('0' + d.getDate()).slice(-2);

            if( i === 0 )
                f = v;

            t = v;

            var item = ( obj.hasOwnProperty( v ) ) ? obj[v] : 0;

            foo.push({
                type  : 'value',
                date  : formated_date,
                value : item
            });
        });

        return {
            sub_title: sub_title,
            time : time,
            data : foo,
            from : f,
            to   : t
        };
    }

    var getDates = function(startDate, endDate) {

        var dates = [],
            currentDate = startDate,
            addDays = function(days) {
                var date = new Date(this.valueOf());
                date.setDate(date.getDate() + days);
                return date;
            };

        while (currentDate <= endDate) {

            var formated_date =
                currentDate.getFullYear() + '-' +
                ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' +
                ('0' + currentDate.getDate()).slice(-2);

            dates.push(formated_date);
            currentDate = addDays.call(currentDate, 1);
        }

        return dates;
    };

    var Review = {

        getStar : function(width) {
            var star;
            width = parseInt( width.replace( /[^0-9]/g, '' ) );

            star = 0;
            if (width > 0) {
                star = parseInt( 5 * width / 100 );
            }

            return star;
        },

        pastReview : function( target, response ){

            var $obj    = response,
                review  = {
                    'flag'     : '',
                    'author'   : '',
                    'star'     : '',
                    'feedback' : '',
                    'date'     : ''
                },
                $feedbackList = $obj.find( '.feedback-list-wrap .feedback-item' ),
                feedList = {
                    list : []
                };

            if ( $feedbackList.length !== 0 ) {

                $feedbackList.each( function ( i, e ) {

                    var images = [];

                    var ratePercent = parseFloat( Review.getStar($(this).find('.star-view span').attr('style')) );

                    review = {};

                    review.feedback = $(this).find('.buyer-feedback').text();
                    if($(this).find('.buyer-feedback .r-time-new').length){
                        review.feedback = $(this).find('.buyer-feedback span:not(.r-time-new)').text();
                    }
                    review.feedback = review.feedback.replace('seller', 'store');
                    review.flag     = $(this).find('.css_flag').text().toLowerCase();
                    review.author   = $(this).find('.user-name').text();
                    review.star     = ratePercent;
                    review.ratePercent = parseInt( ratePercent * 20 );

                    $(this).find('.pic-view-item').each(function(index, value) {
                        images.push($(value).data('src'));
                    });

                    let dateBox = $(this).find('.r-time');
                    if($(this).find('.r-time-new').length){
                        dateBox = $(this).find('.r-time-new');
                    }
                    review.date = dateBox.text();
                    review.images = images;
                    feedList.list.push(review);
                });

                if ( feedList.list.length !== 0 ) {
                    target.html( window.ADS.objTotmpl( $(tmpl.reviews).html(), feedList ) );
                } else {
                    target.html( 'No Feedback' );
                }
            } else {
                target.html( 'No Feedback' );
            }

            window.ADS.screenUnLock();
        },
        addTask : function ( feedbackUrl, target ) {

            window.ADS.screenLock();

            var url = changeUrl(
                'https:'+feedbackUrl,
                {
                    'evaStarFilterValue' :   'all+Stars',
                    'evaSortValue'           : 'sortdefault%40feedback',
                    'page'                   : 1,
                    'currentPage'            : 1,
                    'withPictures'           : false,
                    'withPersonalInfo'       : false,
                    'withAdditionalFeedback' : false,
                    'onlyFromMyCountry'      : false,
                    'version'                : '',
                    'v'                      : 2,
                    'isOpened'               : true,
                    'translate'              : '+Y+',
                    'jumpToTop'              : false

                }
            );

            url = str_replace('&amp;', '&', url);

            $.ajax({
                url: url,
                type: "GET",
                dataType: 'html',
                success: function ( data ) {

                    var div = $( '<div></div>' );

                    Review.pastReview( target, $( div ).append( data ) );
                },
                fail : function (jqXHR, textStatus) {
                    console.log(textStatus);
                }
            });
        },
        addAnalysisTask : function ( productId, target, sub_title, time ) {
            console.log('32432');
            window.DM.aliExtension
                .getPage('https://home.aliexpress.com/dropshipper/item_analysis_ajax.htm?productId='+productId)
                .then( function (value) {

                        if( typeof value.html === 'object') {

                            if( value.html.hasOwnProperty('data') && value.html.data.hasOwnProperty('saleVolume') ) {

                                var response = value.html.data;

                                $(target).find('.logistic-box').html( window.ADS.objTotmpl( $('#tmpl-analysis-table').html(), response ) );

                                var too = [];

                                if( Object.keys(response.saleVolume).length ) {
                                    too = response.saleVolume;
                                }

                                var foo = sortDatasKeys( too, sub_title, time );

                                window.dmChart.chartData( '#chart-'+productId, foo, 256 );
                            }
                        } else {
                            $(target).html( $('#tmpl-alert-nologin').html() );
                        }

                        window.ADS.screenUnLock();
                    }
                );
        },
        handler : function() {

            var $this = this;

            $(document).on('click', el.item + ' a.nav-link', function(){
                if( $(this).data('url') ) {
                    $this.addTask( $(this).data('url'), $( $(this).attr('href') ) );
                }else if( $(this).data('analysis') ) {

                    var tab = $( $(this).attr('href') );

                    if( $( 'body' ).hasClass('no-ali-extension') ) {
                        tab.html( $('#tmpl-alert-extension').html() );
                    } else {
                        if( ! tab.hasClass( 'has-data' ) ) {
                            window.ADS.screenLock();
                            tab.addClass( 'has-data' );
                        }

                        $this.addAnalysisTask(
                            $(this).data('analysis'),
                            $(this).attr('href'),
                            $(this).data('sub_title'),
                            $(this).data('time')
                        );
                    }
                }
            });

            $( 'body' ).on( 'test:extensions', function ( e ) {
                if ( e.active ) {} else {
                    $( 'body' ).addClass('no-ali-extension');
                }
            } );
        },
        init: function(){
            if( ! $('.maintenance').length ) {
                this.handler();
            }
        }
    };

    Review.init();
    function parseURL( variable, url ) {

        var vars = url.split('&');

        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split('=');
            if (decodeURIComponent(pair[0]) === variable) {
                return decodeURIComponent(pair[1]);
            }
        }
        return false;
    }

    var path = 'https://freight.aliexpress.com/ajaxFreightCalculateService.htm?currencyCode=USD&province=&city=&count=1&f=d';
    var pid;
    let Shipping = {

        requestShipping : function( product_id, country ) {

            window.DM.aliExtension.sendAliExtension('getShippingProduct', {
                price: 1,
                productid: product_id,
                countryList: [{value: country}]
            }).then(function (data) {
                console.log('getShippingProduct', data);
                let shipping = {};

                for (let i in data) {
                    let params = data[i];

                    if (!params) {
                        continue;
                    }

                    let response = params.response;
                    let country = params.country;

                    //console.log('freightResult', response);

                    shipping[country] = response.body && response.body.freightResult ? response.body.freightResult.map((item) => {
                        return {
                            company   : item.company,
                            country   : item.sendGoodsCountryFullName,
                            total     : convertPrice( item.standardFreightAmount.value ),
                            price     : convertPrice( item.standardFreightAmount.value ),
                            s         : false, //parseFloat( item.saveMoney ) === 0
                            time      : item.time,
                            isTracked : item.tracking,
                            free      : item.discount === 100,
                            processingTime : item.processingTime //время обработки
                        }
                    }) : [];
                }



                let args = [];

                args['list'] = [];
                $.each( shipping, function( i, item ) {

                    $.each( item, function (t, p) {
                        args['list'].push(p);
                    } )
                } );
                if( args['list'].length ) {

                    $('#list-shipping-'+pid).html( window.ADS.objTotmpl( $('#tmpl-shipping-view').html(), args ) );
                } else {
                    //вывод ошибки
                }

                return shipping;

            });

        },

        init : function () {

            var $this = this;

            if( ! $('.maintenance').length ) {
                $(document).on('click', el.item + ' a.nav-link', function(){
                    if( $(this).data('shipping') ) {
                        pid = $(this).data('shipping');
                        $this.requestShipping( pid, $('#ship_country_'+pid).val() );
                    }
                });

                $(document).on('change', '.shipping-list-tab .selectpicker', function () {
                    pid = $(this).parents('.shipping-list-tab').data('shipping');
                    $(this).selectpicker('refresh');
                    $this.requestShipping( pid, $(this).val() );
                });
            }
        }
    };

    Shipping.init();

    let body = $('body'),
        create_c = '#create_cat',
        attr = '#attributes',
        publish = '#publish',
        r_price = '#recommended_price',
        current_select = 'select[id=categoryImportMobile]',
        scI = 'select[id=categoryImport]',
        cI = '#categoryImport',
        current_originalPriceFrom_e = '#originalPriceFrom_e',
        oPF = '#originalPriceFrom',
        current_originalPriceTo_e = '#originalPriceTo_e',
        oPT = '#originalPriceTo',
        current_volumeFrom_e = '#volumeFrom_e',
        vf = '#volumeFrom',
        current_volumeTo_e = '#volumeTo_e',
        vt = '#volumeTo',
        current_warehouse_e = 'select[id=warehouse_e]',
        swh = 'select[id=warehouse]',
        wh = '#warehouse',
        current_to_e = 'select[id=to_e]',
        sto = 'select[id=to]',
        idto = '#to',
        current_company_e = 'select[id=company_e]',
        scom = 'select[id=company]',
        com = '#company',
        free = '#free',
        bisb = 'body .import-settings-btn',
        pls = 'body .params-list-sku',
        dt = '.description-tab',
        slt = '.shipping-list-tab',
        rtab = '.reviews-tab',
        msc = '.mobile-select-cat',
        cbd = '.category-id .bootstrap-select .dropdown-menu',
        dmdi = '.dropdown-menu.mobile div.inner',
        cdm = '.category-id .dropdown-menu.mobile',
        ifb = '.import-filters-btn',
        isb = '.import-settings-btn',
        om = '.opened_mobile',
        dm_cf = '#dm_collapseFilters',
        sbsd = '.subCategoryId .bootstrap-select .dropdown-menu',
        mmfgf = '.mobile-select-cat.mobile .form-group:first',
        dm_csm = '#dm_collapseSettingsM',
        dm_cc = '.category-create',
        shb = '.shipping-btn',
        descb = '.desc-btn',
        revb = '.reviews-btn',
        anb = '.analysis-btn',
        dm_cce = '#create_cat_e',
        dm_cse = '#cat_status_e',
        dm_cs = '#cat_status';

    let mobile_friendly = {

        emulatingImportSettings :  function() {

            body.on('click', dm_cce, function () {
                $(create_c).trigger('click');
            });
            body.on('change', dm_cse, function () {
                $(dm_cs).val($(dm_cse).val());
                $(dm_cs).selectpicker('refresh');
                $(dm_cs).trigger('change');
            });

            body.on('click', '#attributes_e', function () {
                $(attr).trigger('click');
            });

            body.on('click', '#publish_e', function () {
                $(publish).trigger('click');
            });

            body.on('click', '#recommended_price_e', function () {
                $(r_price).trigger('click');
            });

        },
        emulatingCategoryImport : function(){

            body.on('changed.bs.select', '#categoryImportMobile', function () {
                $(scI).val($(current_select).val());
                $(cI).selectpicker('refresh');
            });

        },
        emulatingFiltersSettings: function(){

            body.on('change', current_originalPriceFrom_e, function () {
                $(oPF).val($(current_originalPriceFrom_e).val());
            });

            body.on('change', current_originalPriceTo_e, function () {
                $(oPT).val($(current_originalPriceTo_e).val());
            });

            body.on('change', current_volumeFrom_e, function () {
                $(vf).val($(current_volumeFrom_e).val());
            });

            body.on('change', current_volumeTo_e, function () {
                $(vt).val($(current_volumeTo_e).val());
            });

            body.on('changed.bs.select', '#warehouse_e', function () {
                $(swh).val($(current_warehouse_e).val());
                $(wh).selectpicker('refresh');
            });

            body.on('changed.bs.select', '#to_e', function () {
                $(sto).val($(current_to_e).val());
                $(idto).selectpicker('refresh');
            });

            body.on('changed.bs.select', '#company_e', function () {
                $(scom).val($(current_company_e).val());
                $(com).selectpicker('refresh');
            });

            body.on('click', '#free_e', function () {
                $(free).trigger('click');
            });

        },
        categoryChanges: function(){

            $('body').on('show.bs.select', '.category-id', function () {
                body.addClass('mobile-overflow');
                $(msc).addClass('mobile');
                $(cbd).addClass('mobile');
                $(dmdi).css({'height' : $(window).height(), 'max-height' : $(window).height()-125});
                $(cdm).css({'height' : $(window).height(), 'max-height' : $(window).height()-48});
            });

            body.on('hide.bs.select', '.category-id', function () {
                $(body).removeClass('mobile-overflow');
                $(msc).removeClass('mobile');
                $(cbd).removeClass('mobile');
            });

            body.on('show.bs.select', '.subCategoryId', function () {
                $(body).addClass('mobile-overflow');
                $(msc).addClass('mobile');
                $(sbsd).addClass('mobile');
                $(mmfgf).addClass('hidden');
                $(dmdi).css({'height' : $(window).height(), 'max-height' : $(window).height()-48});
                $('.subCategoryId .dropdown-menu.mobile').css({'height' : $(window).height(), 'max-height' : $(window).height()-48});
            });

            body.on('hide.bs.select', '.subCategoryId', function () {
                $(body).removeClass('mobile-overflow');
                $(mmfgf).removeClass('hidden');
                $(msc).removeClass('mobile');
                $(sbsd).removeClass('mobile');
            });

        },
        settingsChanges: function(){

            body.on('show.bs.collapse', dm_csm, function () {
                $(body).addClass('mobile-overflow');
                $(isb).removeClass('btn-green');
                $(isb).addClass('mobile');
                $(dm_csm).addClass('mobile');
                $(dm_cc).css({'order':'-1'});
                $('#dm_collapseSettingsM.mobile .import-settings').css({'height' : $(window).height(), 'max-height' : $(window).height()});
            });
            body.on('hidden.bs.collapse', dm_csm, function () {
                body.removeClass('mobile-overflow');
                $(isb).removeClass('mobile');
                $(dm_csm).removeClass('mobile');
            });

        },
        settingsFilters: function(){

            body.on('show.bs.collapse', dm_cf, function () {
                $(body).addClass('mobile-overflow');
                $(ifb).removeClass('btn-green');
                $(ifb).addClass('mobile');
                $(dm_cf).addClass('mobile');
                $(dm_cc).css({'order':'-1'});
                $('#dm_collapseFilters.mobile').css({'height' : $(window).height(), 'max-height' : $(window).height()});
                $('#dm_collapseFilters.mobile .import-filters-btn').css({'height' : $(window).height(), 'max-height' : $(window).height()});
            });

            body.on('hidden.bs.collapse', dm_cf, function () {
                body.removeClass('mobile-overflow');
                $(ifb).removeClass('mobile');
                $(dm_cf).removeClass('mobile');
            });

        },
        productsInnerChanges: function(){

            //Product variations
            body.on('show.bs.collapse', '[id*="dm_collapseVariants-"]', function (e) {
                $(om).addClass('mobile-overflow');
                $('.variants-btn').addClass('expanded');
                $( e.target ).closest($('.params-list-sku')).addClass('expanded');
            });

            body.on('hide.bs.collapse', '[id*="dm_collapseVariants-"]', function (e) {
                $(om).removeClass('mobile-overflow');
                $('.variants-btn.expanded').removeClass('expanded');
                $( e.target ).closest($('[id*="dm_collapseVariants-"]')).removeClass('expanded');
            });
            //Product description
            body.on('click', descb, function (e) {
                $(om).addClass('mobile-overflow');
                let id_full = $( e.target ).attr('id');
                let id = id_full.substr(12);
                $(descb).addClass('expanded');
                $( '#description-tab-'+id ).addClass('expanded');
            });

            body.on('click', '.desc-btn.expanded', function (e) {
                $(om).removeClass('mobile-overflow');
                let id_full = $( e.target ).attr('id');
                let id = id_full.substr(12);
                $(descb).removeClass('expanded');
                $( '#description-tab-'+id ).removeClass('expanded');
            });

            //Product shipping
            body.on('click', shb, function (e) {
                $(om).addClass('mobile-overflow');
                let id_full = $( e.target ).attr('id');
                let id = id_full.substr(9);
                $(shb).addClass('expanded');
                $( '#shipping-tab-'+id ).addClass('expanded');
            });

            body.on('click', '.shipping-btn.expanded', function (e) {
                $(om).removeClass('mobile-overflow');
                let id_full = $( e.target ).attr('id');
                let id = id_full.substr(9);
                $(shb).removeClass('expanded');
                $( '#shipping-tab-'+id ).removeClass('expanded');
            });

            //Product reviews
            body.on('click', revb, function (e) {
                $(om).addClass('mobile-overflow');
                let id_full = $( e.target ).attr('id');
                let id = id_full.substr(8);
                $(revb).addClass('expanded');
                $( '#reviews-tab-'+id ).addClass('expanded show');
            });

            body.on('click', '.reviews-btn.expanded', function (e) {
                $(om).removeClass('mobile-overflow');
                let id_full = $( e.target ).attr('id');
                let id = id_full.substr(8);
                $(revb).removeClass('expanded');
                $( '#reviews-tab-'+id ).removeClass('expanded show');
            });

            //Product reviews
            body.on('click', anb, function (e) {
                $(om).addClass('mobile-overflow');
                let id_full = $( e.target ).attr('id');
                let id = id_full.substr(9);
                $(anb).addClass('expanded');
                $( '#analysis-tab-'+id ).addClass('expanded show');
            });

            body.on('click', '.analysis-btn.expanded', function (e) {
                $(om).removeClass('mobile-overflow');
                let id_full = $( e.target ).attr('id');
                let id = id_full.substr(9);
                $(anb).removeClass('expanded');
                $( '#analysis-tab-'+id ).removeClass('expanded show');
            });

        },
        reverseResolution: function(){

            //emulating import settings
            body.on('click', create_c, function () {
                $(dm_cce).trigger('click');
            });
            body.on('change', dm_cs, function () {
                $(dm_cse).val($(dm_cs).val());
                $(dm_cse).selectpicker('refresh');
            });
            body.on('click', attr, function () {
                $('#attributes_e').trigger('click');
            });
            body.on('click', publish, function () {
                $('#publish_e').trigger('click');
            });
            body.on('click', r_price, function () {
                $('#recommended_price_e').trigger('click');
            });
            //emulating category import
            body.on('changed.bs.select', cI, function () {
                let current_select_m = $(scI).val();
                $(current_select).val(current_select_m);
                $('#categoryImportMobile').selectpicker('refresh');
            });
            //emulating filters settings
            body.on('change', oPF, function () {
                let current_originalPriceFrom = $(oPF).val();
                $(current_originalPriceFrom_e).val(current_originalPriceFrom);
            });
            body.on('change', oPT, function () {
                let current_originalPriceTo = $(oPT).val();
                $(current_originalPriceTo_e).val(current_originalPriceTo);
            });
            body.on('change', vf, function () {
                let current_volumeFrom = $(vf).val();
                $(current_volumeFrom_e).val(current_volumeFrom);
            });
            body.on('change', vt, function () {
                let current_volumeTo = $(vt).val();
                $(current_volumeTo_e).val(current_volumeTo);
            });
            body.on('changed.bs.select', wh, function () {
                let current_warehouse = $(swh).val();
                $(current_warehouse_e).val(current_warehouse);
                $('#warehouse_e').selectpicker('refresh');
            });
            body.on('changed.bs.select', idto, function () {
                let current_to = $(sto).val();
                $(current_to_e).val(current_to);
                $('#to').selectpicker('refresh');
                $('#to_e').selectpicker('refresh');
            });
            body.on('changed.bs.select', com, function () {
                let current_company = $(scom).val();
                $(current_company_e).val(current_company);
                $('#company_e').selectpicker('refresh');
            });
            body.on('click', free, function () {
                $('#free_e').trigger('click');
            });
            //resize
            $(dm_csm).css({'height':'auto','max-height':'auto'});
            $('#dm_collapseSettingsM .import-settings').css({'height':'auto','max-height':'auto'});
            $(dm_csm).removeClass('mobile');
            if ($('.mobile_dest').length === 0) {
                if ($('.product-info .description-row').length > 0) {
                    tabs.prependTo('.description-row .col');
                    $(dt).addClass('active show');
                }
            }

        },
        init : function () {
            body.on('show.bs.select', $('#to'), function () {
                $('#to').selectpicker('refresh');
            });

            if(window.matchMedia("screen and (max-width: 576px)").matches){
                //basic changes
                $(bisb).html('SETTINGS');
                $(pls).addClass('collapse');
                $(dt).removeClass('active show');
                $(slt).removeClass('active show');
                $(rtab).removeClass('active show');
                //end basic changes
                this.emulatingImportSettings();
                this.emulatingCategoryImport();
                this.emulatingFiltersSettings();
                this.categoryChanges();
                this.settingsChanges();
                this.settingsFilters();
                this.productsInnerChanges();

            }
            else{
                this.reverseResolution();
            }

        }

    };

    mobile_friendly.init();

    window.addEventListener('resize', function(){
        mobile_friendly.init();
    }, true);

    var page_initialize = {
        init: function(){
            $.ajaxQueue( {
                url      : ajaxurl,
                data     : {action: 'dm_alidropship_api', ads_action: 'import_setting'},
                dataType : 'json',
                type     : 'POST',
                success  : function(response){

                    if( response.create_cat === 1 ) {

                        $(create_c).prop('checked', true);
                        $(dm_cce).prop('checked', true);

                        var cat  = $('#categoryImport').closest('.form-group'),
                            catm = $('#categoryImportMobile').closest('.form-group'),
                            dm   = $('#categoryImportDM'),
                            dmm  = $('#categoryImportDMMobile'),
                            cs  = $('#cat_status').closest('.form-group'),
                            cse  = $('#cat_status_e').closest('.form-group');

                        cat.hide();
                        catm.hide();
                        dm.show();
                        dmm.show();
                        cs.show();
                        cse.show();
                    }

                    if( response.attributes === 1 ) {

                        $(attr).prop('checked', true);
                        $('#attributes_e').prop('checked', true);
                    }
                    if( response.cat_status > 0 ) {

                        $('#cat_status').val(response.cat_status).selectpicker('refresh');
                        $('#cat_status_e').val(response.cat_status).selectpicker('refresh');
                    }
                    if( response.publish === 1 ) {

                        $(publish).prop('checked', true);
                        $('#publish_e').prop('checked', true);
                    }

                    if(response.recommended_price === 1){

                        $(r_price).prop('checked', true);
                        $('#recommended_price_e').attr('checked', 'checked');
                    }

                    $.uniform.update();
                }
            });
        }
    };
    page_initialize.init();
});