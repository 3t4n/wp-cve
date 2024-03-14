var RAYSGRID = RAYSGRID || {};

(function($) { 
    
    "use strict";

    RAYSGRID.init = {

        globals: function(){
            $('body').append('<span class="rsgd_msg"></span><span class="rsgd_loading"><span class="rsgd_loader-in"></span></span><div id="boo-overlay"></div>');

            $('#alias').on('keyup input focus', function () {
                var vll = $(this).val();
                vll = vll.replace(/\s+/g, '-').toLowerCase();
                $('.shortcode_txt').val('[raysgrid alias="' + vll + '"]');
            });

            $('#rsgd_type_name').on('keyup input focus', function () {
                var vll = $(this).val();
                vll = vll.replace(/\s+/g, '-').toLowerCase();
                $(this).val(vll);
            });

            var colorOpts = {
                defaultColor: false,
                change: function(event, ui){
                    var hexcolor = $( this ).wpColorPicker( 'color' );
                    $(this).parent().parent().parent().find('.hexa-color').attr('value',hexcolor);
                },
                clear: function() {
                    $(this).parent().parent().parent().find('.hexa-color').attr('value','');
                },
                hide: true,
                palettes: true
            };
            $('.rsgd_color').wpColorPicker(colorOpts);

            $('.rsgd_select_boxes').each(function(){
                var th = $(this),
                    act = '';
                th.before('<ul class="rsgd_sel_opts"></ul>');
                var opts = th.parent().find('.rsgd_sel_opts');
                th.find('option').each(function(){
                    if(this.selected) {
                        opts.append('<li class="active" data-value="'+$(this).val()+'">'+$(this).text()+'</li>');
                    } else {
                        opts.append('<li data-value="'+$(this).val()+'">'+$(this).text()+'</li>');
                    }
                    
                });

                opts.find('li').click(function(){
                    th.val($(this).data('value')).trigger('change');
                    opts.find('li').removeClass('active');
                    $(this).addClass('active');
                });
                
            });

            $('.rsgd_hid_two_num').each(function(){
                var t = $(this),
                    input = $('.rsgd_num-txt'),
                    tFirst = t.parent().find('.rsgd_firstVL'),
                    tLast = t.parent().find('.rsgd_lastVL');
                
                input.on('input',function(){
                    var val1 = (isNaN(parseInt(tFirst.val()))) ? 0 : parseInt(tFirst.val());
                    var val2 = (isNaN(parseInt(tLast.val()))) ? 0 : parseInt(tLast.val());
                    t.val(val1+'|'+val2);
                });

            });

            $('.top_settings').on('click', function(e){
                e.preventDefault();
                $('#boo-overlay').fadeIn();
                $('.rsgd_popup_settings').fadeIn();
            });

            $('.rsgd_close_settings').on('click', function(){
                $(this).parent().parent().fadeOut();
                $('#boo-overlay').fadeOut();
            });

            $(".slidernum").each(function(){
                var th      = $(this),
                    thP     = th.next().attr('value'),
                    thMin   = th.data('min'),
                    thMax   = th.data('max');

                if(thMin == '' & thMax == ''){
                    
                    th.remove();

                }else {

                    th.slider({
                        range: "min",
                        value: thP,
                        step: 1,
                        min: thMin,
                        max: thMax,
                        slide: function (event, ui) {
                            var thPre = th.next();
                            thPre.val(ui.value);
                        }
                    });

                }
                
            });
            $('.num-txt:not(.no-slider)').on('input', function () {
                var value = this.value,
                selector = $(this).prev();
                selector.slider("value", value);
            });
        },

        rsgd_forms: function(){
            var admURL = $('.hidden.adm').text();

            $('.rsgd_form').each(function () {
                var th = $(this);
                th.submit(function (e) {
                    e.preventDefault();
                    var $thisAction = $(this).attr('action');
                    var submit = true;

                    var b = $(this).serialize();
                    var el = $('.rsgd_msg');

                    th.find('[required="required"]').each(function () {
                        var $attr = $(this).val(),
                            $txt = $(this).attr('id');
                        if($attr == '') {
                            submit = false;
                            $(this).focus();
                            $('.rsgd_error_list').html('').hide(0);
                            $('.rsgd_error_list').append('<b>Please Add Grid '+$txt+'</b>').show(0);
                            return false;
                        }
                    });

                    if (submit) {
                        $('.rsgd_loading').show();
                    }

                    $.post($thisAction, b).success(function () {

                        if (submit) {

                            $('.rsgd_msg').text('Seccessfuly saved').addClass('success').fadeIn();
                            $('.rsgd_loading').fadeOut(500);
                            setTimeout(function () {
                                $('.rsgd_msg').fadeOut();
                            }, 1500);

                            $(this).submit();

                            if (th.find('.rsgd_save_btn').length) {
                                window.location.href = admURL + 'admin.php?page=raysgrid';
                            }
                        } else {
                            $('.rsgd_loading').fadeOut(500);
                        }

                    }).error(function () {
                        el.text('Error saving data').addClass('error').fadeIn();
                        setTimeout(function () {
                            el.fadeOut();
                        }, 3000);
                    });

                    return false;
                });
            });

            var options = {
                beforeSubmit: RAYSGRID.init.beforeSubmit,
                success: RAYSGRID.init.afterSuccess,
                uploadProgress: RAYSGRID.init.OnProgress,
                resetForm: false
            };

            $('#import_gr button.btn-success').click(function () {
                $('.rsgd_import_form').ajaxSubmit(options);
                return false;
            });
        },

        beforeSubmit: function () {

            var submit = true,
                impf = $('#impFile');

            if (submit) {
                $('.rsgd_loading').show();
            } else {
                var $attr = impf.attr('data-valiation-text');
                return false;
            }
        },

        afterSuccess: function () {
            var admURL = $('.hidden.adm').text();
            $('.rsgd_msg').text('Seccessfuly Imported').addClass('success').fadeIn();
            $('.rsgd_loading').fadeOut(500);
            setTimeout(function () {
                $('.rsgd_msg').fadeOut();
                window.location.href = admURL + 'admin.php?page=raysgrid';
            }, 1500);
        },

        OnProgress: function () {
            
            $('.rsgd_loading').show();
        },

        clone_form: function(){
            var admURL = $('.hidden.adm').text();
            $('.inline-cell .clone_btn').each(function () {
                var th = $(this);
                th.on('click', function(e){
                    e.preventDefault();
                    
                    var $id = th.attr('id').replace('rg-clone-',''),
                        frm = $(this).parents('form.list_form'),
                        $thisAction = frm.attr('action')+'&do=clone&id='+$id,
                        parent = frm.parent().parent(),
                        b = frm.serialize();

                    $.post($thisAction, b).success(function () {

                        window.location.href = admURL + 'admin.php?page=raysgrid';
                        frm.submit();

                    }).error(function () {

                    });
                });
            });
        },

        delete_form: function(){
            $('.inline-cell .delete_btn').each(function () {
                var th = $(this);
                th.on('click', function(e){
                    e.preventDefault();
                    var $id = th.attr('id').replace('rg-delete-',''),
                        frm = $(this).parents('form.list_form'),
                        $thisAction = frm.attr('action')+'&do=delete&id='+$id,
                        parent = th.parent().parent(),
                        tbdy = parent.parent(),
                        datatbl = tbdy.parents('.dataTables_wrapper'),
                        submit = true;

                    var retVal = confirm("Do you want to Delete this grid ?");
                    if (retVal == true) {

                        var b = frm.serialize();
                        var el = $('.rsgd_msg');
                        if (submit) {
                            th.find('.cs-lod').show();
                            parent.css({'backgroundColor': '#fffad1'}, 500);
                        }
                        $.post($thisAction, b).success(function () {

                            if (submit) {
                                setTimeout(function () {
                                    parent.fadeOut(500, function () {
                                        parent.remove();
                                        th.find('.cs-lod').hide();
                                        if( tbdy.find('tr').length == 0 ){
                                            datatbl.find('.row').remove();
                                            datatbl.append('<div class="tbl no_grids"><i class="dashicons dashicons-no"></i>No Grids Were Found</div>');
                                        }
                                    });
                                }, 500);

                                $(this).submit();


                            } else {
                                tht.find('.cs-lod').hide();
                            }

                        }).error(function () {

                        });
                    } else {
                        return false;
                    }

                    return false;

                });
            });
        },

        itemdepend: function(){

            $('.dep-inp').each(function(){
                var t       = $(this),
                    vl      = t.val(),
                    deps    = t.attr('id');
                
                if( t.attr('type') == 'text' ) {
                    
                    if( t.val() == '' ){
                        $("[data-dep='"+deps+"']").hide(0);
                    } else {
                        $("[data-dep='"+deps+"']").show(0).css('display','table');
                    }

                    t.on('input',function(){
                        if(t.val() == ''){
                            $("[data-dep='"+deps+"']").fadeOut(200);
                        } else {
                            $("[data-dep='"+deps+"']").fadeIn(200).css('display','table');
                        }
                    });

                } else if( t.attr('type') == 'hidden' ) {
                    
                } else {                    
                    
                    $("[data-dep='"+deps+"']").hide(0);
                    $("[data-dep='"+deps+"'][data-vl*='"+vl+"']").show(0).css('display','table');

                    t.on('change',function(){
                        var vl2 = t.val();
                        $("[data-dep='"+deps+"']").fadeOut(200);
                        $("[data-dep='"+deps+"'][data-vl*='"+vl2+"']").fadeIn(200).css('display','table');
                    });

                }
            });
        },

        taxs_posts: function(){
            $('[data-nam="select_taxonomy"] option').hide();

            $('[data-nam="post_type"]').change(function () {
                var vlt = $(this).val();
                $('[data-nam="select_taxonomy"] option').hide();
                $('[data-nam="post_type"] option:selected').each(function() {
                    var t = $(this).val();
                    $('[data-nam="select_taxonomy"] option[data-type="' + t + '"]').show();
                });
                $(this).next().attr('value', vlt);
            });
            
            if($('#select_taxonomy').length){
                $('[data-nam="select_taxonomy"]').change(function(){
                    var vlt = $(this).val();
                    $(this).next().attr('value', vlt);
                });
                var txt_tax = $('#select_taxonomy').val();
                var temp = new Array();
                temp = txt_tax.split(",");
                for (var i = temp.length - 1; i >= 0; i--) {
                    $('[data-nam="select_taxonomy"]').find('option[value="' + temp[i] + '"]').attr('selected', 'selected');
                }

                var txt_type = $('#post_type').val();
                var temp2 = new Array();
                temp2 = txt_type.split(",");
                for (var g = temp2.length - 1; g >= 0; g--) {
                    $('[data-nam="post_type"]').find('option[value="' + temp2[g] + '"]').attr('selected', 'selected');
                    $('[data-nam="select_taxonomy"] option[data-type="' + temp2[g] + '"]').show();
                }
            }
        },

        tabs_func: function(){
            $("ul.rsgd_tabs li").eq(0).addClass("active");

            $("ul.rsgd_tabs li").click(function (e) {
                e.preventDefault();
                if (!$(this).hasClass("active")) {
                    var tabNum = $(this).index();
                    var nthChild = tabNum + 1;
                    $("ul.rsgd_tabs li.active").removeClass("active");
                    $(this).addClass("active");
                    $(".rsgd_tab_content .tab-pane.active").removeClass("active");
                    $(".rsgd_tab_content .tab-pane:nth-child(" + nthChild + ")").addClass("active");
                }
            });
        },

        portfolio_item: function(){
            
            $('#rsgd_skins > .item').not(':first').wrapAll('<div class="edit-sk"></div>');
            $('.edit-sk').prepend('<h3 class="titl">Edit Selected Skin Settings <a class="close-skin" href="#"><i class="dashicons dashicons-no"></i></a></h3>');

            $('.portfolio-item').each(function () {
            
                var that = $(this),
                    cl = that.find('input[type="radio"]').val(),
                    thName = that.find('input[type="radio"]').attr('data-name');

                that.addClass(cl);

                that.find('.radio-lbl').append('<a href="#" class="edit_skin" data-toggle="modal" data-target="#skin_edit" title="">Edit</a>');
                that.append('<div class="port-container"><div class="port-img"><div class="ov-div"></div><div class="icon-links"><a href="#" class="rsgd_link white-bg dashicons dashicons-admin-links"></a><a href="#" class="rsgd_zoom main-bg dashicons dashicons-move" title="GRID TITLE"></a></div></div><div class="port-captions"><h4 class="uppercase"><a href="#">GRID TITLE</a></h4><p class="description"><a href="#">Computers</a> , <a href="http:#">Development</a> , <a href="#">Just Food</a></p></div></div>');

                if (that.find('input[type="radio"]').attr('checked') == "checked") {
                    that.addClass('selected-skin');
                }

                that.on('click', function () {

                    let vl = that.find('input[type="radio"]').val();

                    $('.portfolio-item input[type="radio"]').removeAttr('checked');
                    that.find('input[type="radio"]').attr('checked', 'checked');

                    $('.portfolio-item').removeClass('selected-skin');
                    that.addClass('selected-skin');

                    $('.rsgd-choose-skin').attr('value', vl);

                });

                that.find('.edit_skin').click(function(){
                    var h = parseInt($('.edit-sk').outerHeight(),10)/2;
                    $('#boo-overlay').fadeIn();
                    $('.edit-sk').fadeIn().css('margin-top',-h+'px');
                    $('.edit-sk').find('.close-skin').click(function(){
                        $(this).parent().parent().fadeOut();
                        $('#boo-overlay').fadeOut();
                    });
                });


            });
        }

    }; 

    $(window).scroll(function(){
        var sticky = $('.top-btns'),
            scroll = $(window).scrollTop();

        if (scroll >= 100){
            sticky.addClass('fixed_btns');
        } else {
            sticky.removeClass('fixed_btns');
        }
    });

    RAYSGRID.docLoad = {
        init: function(){
            RAYSGRID.init.globals();
            RAYSGRID.init.rsgd_forms();
            RAYSGRID.init.clone_form();
            RAYSGRID.init.delete_form();
            RAYSGRID.init.itemdepend();
            RAYSGRID.init.portfolio_item();
            RAYSGRID.init.taxs_posts();
            RAYSGRID.init.tabs_func();
            $('.rsgd_chk').rgcheck();
            $('.rsgd_data_table').dataTable();
        }
    };

    $(window).on( 'load', RAYSGRID.docLoad.init );

})(jQuery);