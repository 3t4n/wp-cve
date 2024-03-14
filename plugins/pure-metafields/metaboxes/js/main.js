;(function( $ ){
    "use scrict";

    $(document).ready(function(){
        $('.tm-select-field.select2').each(function(){
            $(this).select2();
        });
        var cntrlIsPressed;

        $(document).keydown(function(event){
            if(event.which=="17"){
                cntrlIsPressed = true;
            }
            $('.tm-select-field option').each(function(indx, e){
                $(e).on('click', function(ev){
                    if($(e).attr('selected') != undefined){
                        $(e).removeAttr('selected')
                    }else{
                        console.log(cntrlIsPressed)
                        if(cntrlIsPressed){
                            $(e).attr('selected', 'selected')
                        }
                    }
                })
            });
        });
        
        $(document).keyup(function(){
            cntrlIsPressed = false;
        });

        $('.tm-select-field option').each(function(indx, e){
            $(e).on('click', function(ev){
                if($(e).attr('selected') != undefined){
                    $(e).removeAttr('selected')
                }
            })
        });

    });

    $('.tm-switch input').on('change', function(){
        var isHiddenField = $(this).parent().children('input[type="hidden"]');
        if($(this).is(':checked')){
            $(this).val('on');
            if(isHiddenField.length){
                isHiddenField.val('on');
            }
        }else{
            $(this).val('off');
            if(isHiddenField.length){
                isHiddenField.val('off');
            }
        }
    })

    $('.tm-datepicker-input').datepicker();

    $('.tm-colorpicker-input').wpColorPicker({});

    $(document).on('click', '.tp-metabox-repeater-collapse', function(x){
        x.preventDefault();
        $(this).parent().find('.tp-metabox-repeater-item-wrapper').slideToggle();
    });

    $(document).ready(function(){
        $('.tp-metabox-repeater-row .tp-metabox-repeater-item-wrapper').slideUp('300');
    });
    
})( jQuery );