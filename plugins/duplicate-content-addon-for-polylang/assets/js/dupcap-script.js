jQuery(document).ready(function ($) {
     
    $("#dupcap-copy-button").click(function(){
        var url = document.location.href+"&copy_content=true";       
        document.location = url;      
        
    });
   
    if(window.location.href.indexOf("&copy_content=true")>-1){
        var from_lang = $("#dupcap-copy-button").data('from_lang');
        $("#dupcap-copy-button").prop("value", "Duplicated Content From "+from_lang);
    }

});