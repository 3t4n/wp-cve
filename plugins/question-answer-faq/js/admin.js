jQuery(document).ready(function($) {

    /**
     * переключение таба
     */
    var a = window.location.hash.replace("#", "");
    if ("" !== a && "#" !== a.charAt(0)) {
        $("#" + a).addClass("active");
        $("#" + a + "-tab").addClass("nav-tab-active").click();
    } else {
    	$('.nav-tab-wrapper a').first().addClass("nav-tab-active");
    	$('.wrap .midealfaqtab').first().addClass("active");
    }
    $('.nav-tab-wrapper a').click(function(event) {
    	$('.nav-tab-wrapper a').removeClass("nav-tab-active");
    	$(this).addClass("nav-tab-active");
    	$('.wrap .midealfaqtab').removeClass("active");
    	$($(this).attr("href")).addClass("active");
    });


    /**
     * Чекбокс срытие показ элементов
     */
    $('.qa-checkbox-show-el').change (function() {
        
        if(this.checked){
            $("."+$(this).data('hide')).show();
        }
        else{
            $("."+$(this).data('hide')).hide();
        }
    });

    $('.qa-checkbox-show-el').each(function(index, el) {
        if(!this.checked){
            $("."+$(this).data('hide')).hide();
        }
    });

    /**
     * Колорпикер
     */
    $('.colorpicker-component').colorpicker( );
        
});
