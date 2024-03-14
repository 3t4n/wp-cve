jQuery(document).ready(function($){

    // copy show tooltip

    $(document).on('mouseenter','.cg_tooltip',function () {

        if($(this).closest('.cg_shortcode_parent').attr('id')=='cgDeleteZipFileHintContainer'){
            $(this).append('<span class="cg_tooltiptext" style="width: 140px;margin-left:-71px;">Copy zip file link</span>');
        }else if($(this).hasClass('td_gallery_info_name_edit')){
            $(this).append('<span class="cg_tooltiptext">Edit gallery name</span>');
        }else if($(this).hasClass('td_gallery_translation_edit')){
            $(this).append('<span class="cg_tooltiptext">Copy translation</span>');
        }else if($(this).hasClass('td_gallery_info_shortcode_conf')){
            $(this).append('<span class="cg_tooltiptext">Shortcode interval configuration</span>');
        }else if($(this).hasClass('td_gallery_info_shortcode_conf_status_on')){
            $(this).append('<span class="cg_tooltiptext">Shortcode is on in interval</span>');
        }else if($(this).hasClass('td_gallery_info_shortcode_conf_status_off')){
            $(this).append('<span class="cg_tooltiptext">Shortcode is off out of interval</span>');
        }else{
            $(this).append('<span class="cg_tooltiptext">Copy shortcode</span>');
        }

    });

    $(document).on('mouseleave','.cg_tooltip',function () {
        $(this).find('.cg_tooltiptext').remove();
    });

    $(document).on('click','.cg_tooltip:not(.td_gallery_info_shortcode_conf)',function () {
        if($(this).hasClass('td_gallery_translation_edit')){
            var $containerWithToCopyValue = $(this).parent().clone();
            $containerWithToCopyValue.find('span,br').remove();
        } else if($(this).closest('.td_gallery_info_shortcode').length){
            var $containerWithToCopyValue = $(this).closest('.td_gallery_info_shortcode').find('.td_gallery_info_name_span');
        }else{
        var $containerWithToCopyValue = $(this).parent().find('.cg_shortcode_copy_text');
        }


        if($containerWithToCopyValue.is('input')){
            var copyText = $containerWithToCopyValue.val().trim();
        }else{
            var copyText = $containerWithToCopyValue.text().trim();
        }

        var el = document.createElement('textarea');
        el.value = copyText;
        el.setAttribute('readonly', '');
        el.style.position = 'absolute';
        el.style.left = '-9999px';
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);

        if(!$(this).hasClass('td_gallery_info_name_edit')){// otherwise will be forwarded to edit options page
        $(this).find('.cg_tooltiptext').text('Copied');
        }

    });


    // show cg-info

    $(document).on('mouseenter','.cg-info-icon',function () {
        $(this).parent().find('.cg-info-container').first().show();

    });

    $(document).on('mouseleave','.cg-info-icon',function () {

        $(this).parent().find('.cg-info-container').first().hide();

    });



});