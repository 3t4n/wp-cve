
function translatable(selectors){
	var selector = selectors;
    var controles = {
        toggleTransliteration : ()=>{}
    };
    this.initialize = function(){
        var element = document.querySelector(selector);
        if(element === null){
            console.warn(`wrong element ${selector} selected`);
            return false;
        }
        var options = {
            sourceLanguage: google.elements.transliteration.LanguageCode.ENGLISH,
            destinationLanguage: [google.elements.transliteration.LanguageCode.URDU],
            transliterationEnabled: true,
        };

        // controles variable to access in functions.
        controles =
            new google.elements.transliteration.TransliterationControl(options);
        controles.makeTransliteratable([element]);
    }
    this.changeState = function(){
        controles.toggleTransliteration();
    }

}

var content_ifr = new translatable("#content_ifr");

if(jQuery("#title").length){
	var title = new translatable("#title");
	title.initialize();
}

if(jQuery("#content").length){
	var content = new translatable("#content");
	content.initialize();
}

if(jQuery(".wpurdu_save_status").val() == "no"){
	//by default in-active
	content.changeState();
	title.changeState();
} else {
	 jQuery("#content, #title").attr("dir", "rtl");
}


jQuery( document ).on('tinymce-editor-init',function(){
    content_ifr.initialize();
    if(jQuery(".wpurdu_save_status").val() == "no"){
	    setTimeout(function(){
		    jQuery("#content_ifr").contents().find("body").attr("dir", "");
			jQuery("#content_ifr").contents().find("body").attr("dir", "ltr");
	    }, 50)

	    jQuery("#content, #title").attr("dir", "");
	    jQuery("#content, #title").attr("dir", "ltr");
	    content_ifr.changeState();
	} else {
		jQuery("#content_ifr").contents().find("body").attr("dir", "rtl");
	}
});



jQuery( document ).on('click', '.media-button-wpurdu' ,function(e){
	e.preventDefault();
    content_ifr.changeState();
    content.changeState();
    title.changeState();

    if(jQuery(this).hasClass("active")){
	    jQuery(this).removeClass("active");
	    jQuery("#content, #title").attr("dir", "");
	    jQuery("#content_ifr").contents().find("body").attr("dir", "");
	    jQuery(this).find("strong").text(button_text.enable);
	    jQuery(this).find(".wpurdu_save_status").val("no");
    } else {
	    jQuery(this).addClass("active");
	    jQuery("#content, #title").attr("dir", "rtl");
	    jQuery("#content_ifr").contents().find("body").attr("dir", "rtl");
	    jQuery(this).find("strong").text(button_text.disable);
	    jQuery(this).find(".wpurdu_save_status").val("yes");
    }
});
