( function( blocks, components, i18n, element ) {

	var registerBlockType = wp.blocks.registerBlockType;
	var added_crossword = false;
        
  	registerBlockType( 'crossword/crossword-block', { // The name of our block. Must be a string with prefix. Example: my-plugin/my-custom-block.
		title: 'Crossword', // The title of our block.
		icon: 'welcome-learn-more', // Dashicon icon for our block. Custom icons can be added using inline SVGs.
		category: 'common', // The category of the block.
		edit: function(attributes) {
//console.log(">edit", attributes.isSelected,jQuery(".ccpuz_preview_img").length);                    
                    
                    var el0 = element.createElement('img', {
                        className:  'ccpuz_preview_img',
                        src: ajax_object.plugin_url + "modules/images/logo.svg"
                    });
                    var el1 = element.createElement('div', {
                        className:  'ccpuz_preview_div',
                        
                    }, ['Loading...']);
                    var el2 = element.createElement('div', {
                        className:  'ccpuz_preview_container'                        
                    }, [el0,el1]);
                    
                    
                    if( attributes.isSelected && ( jQuery(".ccpuz_preview_img").length <= 0 ) ) {                    
                        ccpuz_show_settings_dialog();
                    }
                    
                    if( jQuery("iframe.ccpuz_preview_iframe").length <= 0){
                        setTimeout(function(){

                            ccpuz_load_preview();
                            jQuery(".ccpuz_preview_img").on("click", function(e){
                                e.preventDefault();
                                e.stopPropagation();
                                ccpuz_show_settings_dialog();
                            });
                        }, 100); 
                    }
                    
                    return el2;
                },
            
            save( { attributes } ) {
                return element.createElement( 'p', { }, '[crossword]');
            },            
/*
	    save: function() {
                return <RawHTML>{ccpuz_get_preview_in_editor()}</RawHTML>;
	        //return el( 'p', { }, '[crossword]'+ccpuz_get_preview_in_editor() );
	    },
*/            
            
	} );
 } )(
	window.wp.blocks,
	window.wp.components,
	window.wp.i18n,
	window.wp.element,
);


var CCPUZ_LAST_SHOW = 0;
var CCPUZ_LAST_PREVIEW = 0;

function ccpuz_resizeIFrameToFitContent() {
//console.log(">ccpuz_resizeIFrameToFitContent",iFrame);    
    if (jQuery("iframe.ccpuz_preview_iframe").length <= 0){
//console.log("iframe not found");        
        setTimeout(function(){
            ccpuz_resizeIFrameToFitContent();
        }, 1000);
        return;
    }    
    var w = jQuery("iframe.ccpuz_preview_iframe").contents().find("div.entry-content").width();
    var h = jQuery("iframe.ccpuz_preview_iframe").contents().find("div.entry-content").height();    
//console.log("w = "+ w) ;       console.log("h = "+ h) ;       
    if (isNaN(parseInt(w))){
        setTimeout(function(){
            ccpuz_resizeIFrameToFitContent();
        }, 1000);
//console.log("retry....");        
        return;
    }
    try{
        jQuery("iframe.ccpuz_preview_iframe").width(w);
    } catch(e){}
    try{
        jQuery("iframe.ccpuz_preview_iframe").height(h);
    } catch(e){}
}

function ccpuz_load_preview(){
    if (new Date().getTime() - CCPUZ_LAST_PREVIEW <= 1500 ){
        return;
    }
    CCPUZ_LAST_PREVIEW = new Date().getTime();   
    jQuery(".ccpuz_preview_div").css({"overflow": "hidden"});
    jQuery(".ccpuz_preview_div").html('<iframe class="ccpuz_preview_iframe" style="overflow: hidden!important" src = "'+ajax_object.ajax_url+"?action=ccpuz_preview_local&id="+ccpuz_post_id+'" frameborder="0" srcolling="no" width="100%" />');
    ccpuz_resizeIFrameToFitContent();

    /*
console.log(">loading preview...");    
    jQuery.post(ajax_object.ajax_url,{
        "action": "ccpuz_preview_shortcode",
        "id":     ccpuz_post_id  
    }, function(res){
console.log("<loading preview...OK");    
console.log(res);        
        jQuery(".ccpuz_preview_div").html(res);
        //jQuery(".ccpuz_preview_div").html("some static content");
    });
    */
}

function ccpuz_show_settings_dialog(){
    if (new Date().getTime() - CCPUZ_LAST_SHOW <= 1500 ){
        return;
    }
    CCPUZ_LAST_SHOW = new Date().getTime();
    jQuery.ajax({
        url: ajax_object.ajax_url,
        type: 'POST',
        dataType: 'HTML',
        data: {
            action: 'ccpuz_get_crossword_mce_from',
            post_id: ccpuz_post_id
        }
    }).done(function(response){
        var e = jQuery(response);
        vex.dialog.open({
            message: '',
            input: [
                    e.html()
            ].join(''),
            afterOpen: function () {
                    var e = jQuery('.vex-dialog-input');
                    e.find('#crossword_method').on('change', function(){
                            if( jQuery(this).val() == 'url' ){
                                e.find('.ccpuz_file_class').hide();
                                e.find('.ccpuz_url_class').show();
                            }
                            if( jQuery(this).val() == 'local' ){
                                e.find('.ccpuz_url_class').hide();
                                e.find('.ccpuz_file_class').show();
                            }
                        });
                    e.find('#crossword_method').change();
            },
            onSubmit: function(event) {
                    var dialog = this;
                    event.preventDefault();
                    var e = jQuery('.vex-dialog-input');
                    jQuery('.vex-dialog-button').attr('disabled', 'disabled');

                    var formData = new FormData();
                    formData.append('crossword_method', e.find('#crossword_method').val());
                    formData.append('action', 'ccpuz_save_crossword_mce_from');
                    formData.append('editor', 'gutenberg');
                    formData.append('post_id', ccpuz_post_id);
                    if( e.find('#crossword_method').val() == 'url' ) {
                        formData.append( 'ccpuz_url_upload_field', e.find('#ccpuz_url_upload_field').val() )
                    } else if ( e.find('#crossword_method').val() == 'local' ) {
                        formData.append('ccpuz_html_file', e.find('#ccpuz_html_file')[0].files[0]); 
                        formData.append('ccpuz_js_file', e.find('#ccpuz_js_file')[0].files[0]); 
                    }
                    jQuery.ajax({
                        url: ccpuz_wpse72394_button_ajax_url,
                        data: formData,
                        type: 'POST',
                        dataType: 'html',
                        contentType: false,
                        processData: false
                    }).done(function(response){
                        ccpuz_load_preview();
                        dialog.close();
                    });
                    return false;
            }
        });
    });
    
}