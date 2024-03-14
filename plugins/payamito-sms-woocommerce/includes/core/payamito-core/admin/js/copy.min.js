

jQuery.fn.CopyToClipboard = function() {
    var textToCopy = false;
    if(this.is('select') || this.is('textarea') || this.is('input')){
        textToCopy = this.val();
    }else {
        textToCopy = this.text();
    }
    CopyToClipboard(textToCopy);
};

function CopyToClipboard( val ){
    var hiddenClipboard = jQuery('#_hiddenClipboard_');
    if(!hiddenClipboard.length){
        jQuery('body').append('<textarea readonly style="position:absolute;top: -9999px;" id="_hiddenClipboard_"></textarea>');
        hiddenClipboard = jQuery('#_hiddenClipboard_');
    }
    hiddenClipboard.html(val);
    hiddenClipboard.select();
    document.execCommand('copy');
    document.getSelection().removeAllRanges();
    hiddenClipboard.remove();
}

jQuery(function(){
    jQuery('[data-clipboard-target]').each(function(){
        jQuery(this).click(function() {
            jQuery(jQuery(this).data('clipboard-target')).CopyToClipboard();
        });
    });
    jQuery('[data-clipboard-text]').each(function(){
        jQuery(this).click(function(){
            CopyToClipboard(jQuery(this).data('clipboard-text'));
        });
    });
});