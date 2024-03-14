var playerjs_com_wyswyg;

var pjs_edit_selection='';

var playerjs_com_params = ["file","poster","title","width","height","subtitle","thumbnails"];

jQuery("#playerjs_com_editor_button").click(function(e) {
    
    PlayerJScomCurtain();
    if(jQuery('.playerjs_com_editor_window').length==0){
        jQuery(document.body).append('<div class="playerjs_com_editor_window"><div class="playerjs_com_editor_logo"></div><div class="playerjs_com_editor_x" onclick="PlayerJScomCurtainX();"></div><div class="playerjs_com_clear"></div></div>');
        var x = jQuery(".playerjs_com_editor_window");
        x.append('<p>File<br><input type="text" class="playerjs_com_input" id="playerjs_com_input_file"> &nbsp;<input type="button" class="button" id="playerjs_com_input_file_but" value="Choose"/></p>');
        x.append('<p>Poster<br><input type="text" class="playerjs_com_input" id="playerjs_com_input_poster"/> &nbsp;<input type="button" class="button" id="playerjs_com_input_poster_but" value="Choose"/></p>');
        
        x.append('<p id="playerjs_com_p_subtitle" class="playerjs_com_hidden">Subtitles<br><input type="text" class="playerjs_com_input" id="playerjs_com_input_subtitle"/> &nbsp;<input type="button" class="button" id="playerjs_com_input_subtitle_but" value="Choose"/></p>');
        
        x.append('<p id="playerjs_com_p_thumbnails" class="playerjs_com_hidden">Thumbnails<br><input type="text" class="playerjs_com_input" id="playerjs_com_input_thumbnails"/> &nbsp;<input type="button" class="button" id="playerjs_com_input_thumbnails_but" value="Choose"/></p>');
        
        x.append('<p id="playerjs_com_more_but"><input type="button" value="Submit" class="button-primary" id="playerjs_com_submit_but" onclick="PlayerJScomSubmit();" /> <span class="playerjs_com_ajax" style="float:right" onclick="PlayerJScomMore();">More options</span></p>');
        
        x.append('<p id="playerjs_com_less_but" class="playerjs_com_hidden"><input type="button" value="Submit" class="button-primary" id="playerjs_com_submit_but" onclick="PlayerJScomSubmit();" /> <span class="playerjs_com_ajax" style="float:right" onclick="PlayerJScomLess();">Less options</span></p>');
        
        jQuery("#playerjs_com_input_file_but").on("click",PlayerJScomFileManager);
        jQuery("#playerjs_com_input_poster_but").on("click",PlayerJScomFileManager);
        jQuery("#playerjs_com_input_subtitle_but").on("click",PlayerJScomFileManager);
        jQuery("#playerjs_com_input_thumbnails_but").on("click",PlayerJScomFileManager);
    }else{
        for(var i = 0;i<playerjs_com_params.length;i++){
            jQuery("#playerjs_com_input_"+playerjs_com_params[i]).val('');
        }
        jQuery('.playerjs_com_editor_window').show(0);
    }
    if(tinymce !== 'undefined'){
        PlayerJScomSelection();
    }

});

function PlayerJScomMore(){
    jQuery("#playerjs_com_p_subtitle").show();
    jQuery("#playerjs_com_p_thumbnails").show();
    jQuery("#playerjs_com_more_but").hide();
    jQuery("#playerjs_com_less_but").show();
}
function PlayerJScomLess(){
    jQuery("#playerjs_com_input_subtitle").val()==''?jQuery("#playerjs_com_p_subtitle").hide():'';
    jQuery("#playerjs_com_input_thumbnails").val()==''?jQuery("#playerjs_com_p_thumbnails").hide():'';
    jQuery("#playerjs_com_more_but").show();
    jQuery("#playerjs_com_less_but").hide();
}

function PlayerJScomSelection(){
    
    if(typeof tinymce.activeEditor !== 'undefined' && tinymce.majorVersion >= 4){
        playerjs_com_wyswyg = tinymce.activeEditor;
        if(!tinymce.activeEditor.selection.isCollapsed()){
            var select = jQuery.trim(tinymce.activeEditor.selection.getContent());
            if(select.indexOf("[playerjs")==0 && select.substr(-1)=="]"){
                pjs_edit_selection = select;
                var attributes = PlayerJScomAttributes(select);
                for(var i = 0;i<playerjs_com_params.length;i++){
                    if(attributes[playerjs_com_params[i]]){
                        jQuery("#playerjs_com_input_"+playerjs_com_params[i]).val(attributes[playerjs_com_params[i]]);
                        jQuery("#playerjs_com_p_"+playerjs_com_params[i]).show();
                    }
                }
            }
        }
    }
    /*else{
        playerjs_com_wyswyg = tinyMCE.getInstanceById('content');
    }*/
}

function PlayerJScomAttributes(x){
    var attributes = {};
    x.match(/[\w-]+=".+?"/g).forEach(function(attribute) {
        attribute = attribute.match(/([\w-]+)="(.+?)"/);
        attributes[attribute[1]] = attribute[2];
    });
    return attributes;
}

function PlayerJScomCurtain(){
	if(jQuery('.playerjs_com_curtain').length==0){
		jQuery("body").append('<div class="playerjs_com_curtain"></div>');
		jQuery(".playerjs_com_curtain").css("height",jQuery(document).height());
		jQuery(".playerjs_com_curtain").on("click",PlayerJScomCurtainX);
		document.body.style.overflow = 'hidden';
	}
}
function PlayerJScomCurtainX(){
    jQuery(".playerjs_com_curtain").remove();
    document.body.style.overflow = 'auto';
    jQuery(".playerjs_com_editor_window").hide();
}

var playerjs_com_file_manager;

function PlayerJScomFileManager(e){
    e.preventDefault();
    jQuery('.playerjs_com_manager_input').removeClass('playerjs_com_manager_input');
    jQuery(this).siblings('input[type=text]').addClass('playerjs_com_manager_input');
    var target_input = jQuery(this).siblings('input[type=text]');

    if (playerjs_com_file_manager) {
        playerjs_com_file_manager.open();
        return;
    }
    
    playerjs_com_file_manager = wp.media.frames.file_frame = wp.media({
        title: 'Media',
        button: {
            text: 'Choose'
        },
        multiple: false
    });
    
    playerjs_com_file_manager.on('open', function() {
        jQuery('.media-router .media-menu-item').eq(0).click();
    });
    
    playerjs_com_file_manager.on('select', function() {
        attachment = playerjs_com_file_manager.state().get('selection').first().toJSON();
        jQuery(".playerjs_com_manager_input").val(attachment.url);
    });

    playerjs_com_file_manager.open();
}



function PlayerJScomSubmit(){
    if(jQuery("#playerjs_com_input_file").val()!=''){
        var code = '[playerjs ';
        
        for(var i = 0;i<playerjs_com_params.length;i++){
            var x = jQuery("#playerjs_com_input_"+playerjs_com_params[i]);
            if(x.length>0){
                if(x.val()!=''){
                    code += ' '+playerjs_com_params[i]+'="'+x.val()+'"';
                }
            }
        }
        if(pjs_edit_selection && pjs_edit_selection!=''){
            var attr = PlayerJScomAttributes(pjs_edit_selection);
            for(var i in attr){
                if(playerjs_com_params.indexOf(i)==-1){
                    code += ' '+i+'="'+attr[i]+'"';
                }
            }
        }
        code += ']';
        send_to_editor(code);
        PlayerJScomCurtainX();
    }
}
