tinymce.PluginManager.add('example', function(editor, url) { 
    // Add a button that opens a window
    editor.addButton('example', {
        text: 'LWS Affiliation',
        image: affiliationConfigWidgetImage,
        classes: 'affiliation-lws-button', 
        tooltip: 'Ouvre le widget Affiliation', 
        onclick: function() {
            
            // Open window
            editor.windowManager.open({
                title: 'Widget | LWS Affiliation',
                //url:affiliationConfigWidget,
                file: ajaxurl + "?action=load_banner_modal&_ajax_nonce=" + affiliationConfigWidgetN + "&url=" + ajaxurl + "&" + affiliationConfigWidgetQuery,
                width: 1000,
                height: 600,
                onclose: function(e) {
                    if (this.params.type == 'image') {
                        editor.insertContent(this.params.source);
                    }
                    else{
                        console.log(this.params.source);
                        if (this.params.hasOwnProperty('type')) {
                            var content = editor.getContent();
                            
                            if (content.indexOf('divWidgetDomainAffiliationLWS') == -1 && this.params.type == 'domain_search') {
                                editor.insertContent('<div id="divWidgetDomainAffiliationLWS" class="mceNonEditable" style="cursor:pointer;height: 100px;width: 100%;background-color: #e4e4e4;text-align:center;font-size:22px;font-weight:bold;line-height:100px;margin-bottom:5px;">'+this.params.source+'</div>');
                            } else if (content.indexOf('divWidgetTableAffiliationLWS') == -1 && this.params.type == 'table'){
                                editor.insertContent('<div id="divWidgetTableAffiliationLWS" class="mceNonEditable" style="cursor:pointer;height: 100px;width: 100%;background-color: #e4e4e4;text-align:center;font-size:22px;font-weight:bold;line-height:100px;margin-bottom:5px;">'+this.params.source+'</div>');
                            } else {
                                editor.windowManager.alert('Vous ne pouvez intégrer ce Widget qu\'une seule fois par page.');
                            }
                        }
                    }
                },
            }, {
                extension: function(){return 'com';},
                theme: function(){return 'default';},
                txtButton: function(){return 'Commander';},
                cible: function(){return 'blank';},
            });
        }
    });
});