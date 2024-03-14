// <reference path="../../../../../typings/globals/jquery/index.d.ts" />
function sibg_addFormClass(){
		
	jQuery('.sibg_form_init').each(function(){

		var elm = jQuery(this),elem = jQuery(this).parents('form'), fclasses = elm.attr('data-class').split(',');

		for(const value of fclasses ){
			elem.addClass(value);
		}

		elem.attr('data-color',jQuery(this).attr('data-color'))
		elem.prop('id',jQuery(this).val())
		elem.attr('use_scroll',jQuery(this).attr('use_scroll'))
		elem.attr('scroll_pad',jQuery(this).attr('scroll_pad'))
		elem.attr('data-animation',jQuery(this).attr('data-animation'))
		elem.attr('is_rtl',jQuery(this).attr('is_rtl'))
		elem.attr('is_ux',jQuery(this).attr('is_ux'))
		
	})
}
		
		


function mutationCaller(){

    var targetNodes         = jQuery(".gfield_price .gfield_checkbox label,.gform_fileupload_multifile");
    var MutationObserver    = window.MutationObserver || window.WebKitMutationObserver;
    var myObserver          = new MutationObserver (mutationHandler);
    var obsConfig           = { childList: true, characterData: false, attributes: false, subtree: true };
    
    targetNodes.each ( function () {
        myObserver.observe (this, obsConfig);
    });
}

function mutationHandler (mutationRecords) {
  
    mutationRecords.forEach ( function (mutation) {
        if (typeof mutation.removedNodes == "object") {
            var jq = jQuery(mutation.removedNodes);
            if(jq.is("span.BG_check")){
                jQuery(mutation.target).append('<span class="BG_check"></span>')
            }
        }
        // if (typeof mutation.addedNodes == "object") {
        //     var jq = jQuery(mutation.addedNodes);
        //     if(jq.is("input")){
        //         jQuery(mutation.target).addClass('multiple-input-container')
        //     }
        // }
    });
}

function addFormClass(form){


    jQuery('.sibg_form_init').each(function(){

        var elm = jQuery(this),elem = jQuery(this).parents('form'), fclasses = elm.attr('data-class').split(',');

        for(const value of fclasses ){
            if(value=='BG_Material'){
                addBGLine(elem)
                
            }
            else if(value=='BG_Material_out' || value=='BG_Material_out_rnd'){
                addInputCont(elem)
                
            }
            addBGCheck(elem)
        }
        
    })

}

function addInputCont(elm){
    elm.find('input,select').each(function(){
        var elem = jQuery(this),condition=false;
        if (elem.prop('tagName').toLowerCase()=='select' && elem.prop('multiple')!==true){
            condition = true
        }
        var inputs = ["date","email","month","number","password","search","tel","text"]
        

        if (elem.prop('tagName').toLowerCase()=='input' && inputs.indexOf(elem.attr('type'))>=0){
            condition = true
        }
        if(!elem.siblings('.outline_container')[0] && condition ){
            elem.parent().append('<div class="outline_container" ><div class="outline_left"></div><div class="outline_middle" ></div><div class="outline_right" ></div></div>')
            elem.parent().css('position','relative')
            elem.prop('required',true)
        }
        if(elem.siblings('label')[0] && condition ){
            jQuery(elem.siblings('label').detach()).appendTo(elem.siblings('.outline_container').children('.outline_middle'));
        }
    })
}

function addBGCheck(elm){
    setTimeout(() => {
        elm.find('input[type=checkbox],input[type=radio]').each(function(){
            var elem = jQuery(this)
            // var inputs = ["date","email","month","number","password","search","tel","text"]
            if(!elem.siblings('label').children('.BG_check')[0] && !elem.parents('.image-choices-choice ')[0] ){
                elem.siblings('label').append('<span class="BG_check"></span>')
               if(!elem.parents('.BG_toggle')[0] && !elem.parents('.BG_Button')[0]){
                elem.parent().addClass('BG_default')
               }
            }
        })
    }, 500);
    
}

function addBGLine(elm){

    elm.find('input:not([type=hidden]),select').each(function(){
        var elem = jQuery(this)
        if(!elem.siblings('.bg-line')[0]){
            elem.parent().append('<span class="bg-line"></span>')
        }
    })




}


function scrollForm(form_id) {
    var padd = jQuery("#gform_" + form_id).attr('scroll_pad')
        jQuery('html, body').animate({
            scrollTop: jQuery("#gform_" + form_id).offset().top - padd
        }, 500);
}

jQuery(document).on('gform_page_loaded', function (event, form_id) {
    addFormClass('gform_'+form_id);
	sibg_addFormClass()
    mutationCaller()
    set_tooltips_height()
    var isScroll = jQuery('#gform_' + form_id).attr('use_scroll')
    if (isScroll=='true') {
        scrollForm(form_id)
    }
})

jQuery(document).ready(function(){
	sibg_addFormClass()
	addFormClass('all');
    selectSizeFix();
    mutationCaller()
    buttonModeFix();
    fileInputFix();
    tooltipResp();
    errors();
    multipleUpload()
    noValidate()
    footerContainer()
	jQuery(document).trigger('sibg_doc_ready')
    set_tooltips_height()

    jQuery(document).on('gform_page_loaded', function () {
        selectSizeFix();
        buttonModeFix();
        fileInputFix();
        tooltipResp();
        errors();
        noValidate();
        footerContainer()
        multipleUpload()
    })


    function multipleUpload(){
        jQuery('.gform_fileupload_multifile').each(function(){
            var elm = jQuery(this)
            elm.parents('li').addClass('bg_multiple_upload')
            jQuery(elm.parents('li').children('div:not(.ginput_container)').detach()).appendTo(elm.find('.gform_drop_area'))
            // elm.find('input[type=file]').parent().addClass('multiple-upload-parent')
            // elm.find('input[type=file]').parent().css({left:'50%',top:'50%',transform:'translate(-50% , -50%',height:height,width:width})
        })
    }


    function selectSizeFix(){
        jQuery('select[multiple=multiple]').each(function(){
            var count = jQuery(this).children().length
            jQuery(this).attr('size',count)
            var multiple_height = jQuery(this).height()
            jQuery(this).css({"height":""+multiple_height+8+" !important"})
        })
    }
   
    function buttonModeFix(){

        jQuery('.BG_Button').each(function(){
            if(!jQuery(this).hasClass('BG_Hover')){
                jQuery(this).find('label').each(function(){
                    if(jQuery(this).siblings('.gf_tooltip_body')[0]){
                        if(jQuery('html').attr('dir')=="rtl"){
                            jQuery(this).css({"padding-left":"30px"})
                            jQuery(this).siblings('.gf_tooltip_body').css({left:"5px"})
                        }
                        else{
                            jQuery(this).css({"padding-right":"30px"})
                            jQuery(this).siblings('.gf_tooltip_body').css({right:"5px"})
                        }
                    }
                })
            }
        })
        
        jQuery('.BG_Button .gf_tooltip_body').on('mouseover',function(){
            var hover_color = jQuery(this).parents('form').attr('data-color'); 
            if(!jQuery(this).parents('.image-choices-choice')[0]){
                jQuery(this).siblings('label').css({"background":hover_color,"color": "white","border-color":hover_color})
                jQuery(this).find('i').css('color','white')
            }
        })

        
        
        jQuery('.BG_Button .gf_tooltip_body').on('mouseleave',function(){
            jQuery(this).siblings('label').removeAttr('style')
            if(!jQuery(this).parents('.BG_Hover')[0]){
                if(jQuery('html').attr('dir')=="rtl"){
                    jQuery(this).siblings('label').css({"padding-left":"30px"})
                }
                else{
                    jQuery(this).siblings('label').css({"padding-right":"30px"})
                }
            }
            jQuery(this).find('i').removeAttr('style');
        })
            
        jQuery('.BG_Button .gf_tooltip_body i').on('click',function(){
            jQuery(this).parent().siblings('label').trigger('click');
        })
    }


    function fileInputFix(){

        jQuery('.ginput_preview').each(function(){
                var elm = jQuery(this).parent();
                showName(elm);
        })
 
        function showName(elm){

            
            try{
                var text = elm[0].files[0].name;
            }
            catch(err){
                var text = elm.find('strong').text();
            }
            var validation_message = elm.siblings('.validation_message').text().split('-');

            if(validation_message.length>1){
                
                elm.parents('.ginput_container').find('.BG_fileupload_text').text(validation_message[validation_message.length-1]);
                elm.parents('.ginput_container').find('.BG_fileupload_text').css('color','red')
            }
            else{
                var color = jQuery('.BG_fileupload').css('background')
                elm.parents('.ginput_container').find('.BG_fileupload_text').css('color',color)
                
                var ext = text.split('.');
                 var validExts = []
                if(elm.parents('.ginput_container').hasClass('ginput_container_post_image')){
                    validExts=['jpg','gif','png']
                }
                else{
                    var validExt = elm.siblings('.screen-reader-text').text().replace('Accepted file types:','').slice(0,-1)
               
                    validExt = validExt.split(',')
                    
                    jQuery.each(validExt,function(i , item){
                        validExts.push(item.trim())
                    })
                }
                

                if(jQuery.inArray(ext[ext.length-1].toLowerCase(),validExts)<0 && text!="" && validExt !=""){
                    elm.parents('.ginput_container').find('.BG_fileupload_text').text('File extension must be '+validExts+'.');
                    elm.parents('.ginput_container').find('.BG_fileupload_text').css('color','red')
                }
                
                else if(text!=""){
                    
                    elm.parents('.ginput_container').find('.BG_fileupload_text').text(text);
                    if(!elm.parents('form').hasClass('BG_Bootstrap')){
                        elm.parents('.ginput_container').find('i').eq(0).addClass('BG_fileupload_icon_selected');
                        elm.parents('.ginput_container').find('i').eq(0).removeClass('BG_fileupload_icon');
                    }
                    
                    if(jQuery(window).width()>705 && !elm.parents('form').hasClass('BG_Bootstrap')){
                        elm.parents('.BG_fileupload').css('max-width','calc(50% - 35px)');
                    }else{
                        if(!elm.parents('form').hasClass('BG_Bootstrap')){
                        elm.parents('.BG_fileupload').css('max-width','calc(100% - 35px)');
                        }
                    }
                   
                    if(elm.parents('.ginput_container').find('.BG_filecancel_icon').length==0){
                        if(elm.parents('form').hasClass('BG_Android')){
                            elm.parents('.BG_fileupload').append('<i class="BG_filecancel_icon"></i>');
                        }
                        else{
                            elm.parents('.BG_fileupload').after('<i class="BG_filecancel_icon"></i>');
                        }
                    }
                }
                else{
                    elm.parents('.ginput_container').find('.BG_fileupload_text').text("No file chosen");
                    elm.parents('.ginput_container').find('i').eq(0).removeClass('BG_fileupload_icon_selected');
                    elm.parents('.ginput_container').find('i').eq(0).addClass('BG_fileupload_icon');
                    elm.parents('.ginput_container').find('.BG_filecancel_icon').remove();
                }
            }
        }

        jQuery('body').on('change','input[type="file"]',function(){
            var elm = jQuery(this);
            showName(elm);
        })

        jQuery('body').on('click','.BG_filecancel_icon',function(e){
            e.preventDefault();
            var target = jQuery(this).parents('.ginput_container').find('.gform_delete');
            var elm = jQuery(this).parents('.ginput_container').find('input[type="file"]');
            if(target.length>0){
                target.trigger('click');
            }
            else{
                elm.val("")
            }
            showName(elm);
        })
    }


    // tooltip responsive

    function tooltipResp(){
        
    var def_pos = "";
    jQuery('.gf_tooltip_body').on('mouseover',function(){
        def_pos = jQuery(this).attr('data-position');
        var rect = jQuery(this).children('span')[0].getBoundingClientRect();
        var container = jQuery(window).width();
        var elm = jQuery(this);
        var pos = ["TR","T","TL","R","L","BR","B","BL"];

       
        if(rect.x+rect.width>container || rect.x<0){
            var is_check = true;
            checkPos();

            if(is_check){
                elm.children('span').css('max-width','150px')
                checkPos();
            }

            function checkPos(){
                jQuery.each(pos,function(i,item){
                    if(is_check){
                        elm.attr('data-position',item);
                        var rect2 = elm.children('span')[0].getBoundingClientRect();
                        if (!(rect2.x+rect.width>container) && !(rect2.x<0)){
                            is_check = false;
                        }
                    }
                })
            }
        }
    })

    


    jQuery('.gf_tooltip_body').on('mouseleave',function(){

        setToDef(jQuery(this));
        
    })

    function setToDef(elm){
        setTimeout(function(){
            elm.attr('data-position',def_pos);
        },400)
    }
    }


    // show error 
     function errors(){
         jQuery('form:not(.bg_default_theme) .gfield_description.validation_message').each(function(i,item){

            
            var error_text = jQuery(this).text();
            var elm = jQuery(this).parents('li').find('.bg_error_message')
             if(!elm){
                 jQuery(this).parents('li').append('<div class="bg_error_message">'+error_text+'</div>')
             }


         })
    }

    //add gravity forms footer to div
    function footerContainer(){
        jQuery(".gform_page").find(".gform_page_footer").each(function () {
            var footerHTML = jQuery(this).html()
            if(!jQuery(this).find('.bg_footer_container')[0] && !jQuery(this).find('font')[0]){
                jQuery(this).html('<div class="bg_footer_container">' + footerHTML +'</div>')
            }
            
        })
    
        jQuery("form").find(".gform_footer").each(function () {
            var footerHTML = jQuery(this).html()
            if(!jQuery(this).find('.bg_footer_container')[0]  && !jQuery(this).find('font')[0]){
                jQuery(this).html('<div class="bg_footer_container">' + footerHTML +'</div>')
            }
        })
        footerNoFlex()
    }
   
    function footerNoFlex(){
        setTimeout(() => {
            jQuery("form").find(".gform_footer").each(function () {
                if(jQuery(this).find('font')[0]){
                    jQuery(this).find('.bg_footer_container').css('display','block')
                }
            })
        }, 1000);
    }

    

    function noValidate(){
        jQuery("form.BG_Material,form.BG_Material_out,form.BG_Material_out_rnd").attr('novalidate',true);
    }
	
	
	
	
	jQuery('body').on('hover','.BG_Hover',function(){
		jQuery(this).find('label').trigger('hover')
	})


})

// IE label for input type file

if(/MSIE \d|Trident.*rv:/.test(navigator.userAgent)){

    
document.body.addEventListener('DOMSubtreeModified', function () {
    ieFix()
})
ieFix()


function ieFix(){
    
    var anchors = document.getElementsByClassName('BG_fileupload');
    for(var i = 0; i < anchors.length; i++) {
            var anchor = anchors[i];
            anchor.onmouseup = function(e) {
                if (e.which==1){
                    anchor.querySelectorAll('input[type=file]')[0].click();
                }
            }
        }
}


}

// fixed conflict between tooltips that its positions was top or bottom in hover mode
function set_tooltips_height(){
    jQuery(document).find('.BG_Hover .gf_tooltip_body').find('span').css("height","0")
}
jQuery(document).find('.BG_Hover .gf_tooltip_body').siblings('label').on('mouseleave',function(){
    var elm = jQuery(this)
    var height = jQuery(this).siblings('.gf_tooltip_body').find('span').outerHeight()
    jQuery(this).siblings('.gf_tooltip_body').find('span').css("height",height)
    setTimeout(function (){
        jQuery(elm).siblings('.gf_tooltip_body').find('span').css("height","0")
    },20)
}).on('mouseenter',function(){
    jQuery(this).siblings('.gf_tooltip_body').find('span').css("height","")
})





