(function(){
    tinymce.PluginManager.add('wideo', function(editor, url){
    	urlroot = url.replace("/js",""); 
        editor.addButton('wideo', {
            image : urlroot + '/images/wideo.png',
            tooltip: 'wideo视频播放器',
            onclick: function() {
                editor.windowManager.open({
                    title: '插入视频',
                    width : 500,
					height : 250,
					inline : 1,
                    body: [{
                        type: 'textbox',
                        subtype: 'hidden',
                        name: 'id',
                        id: 'hiddenID'
                    },
                    {
                        type: 'textbox',
                        name: 'text',
                        label: '视频地址：',
                        id: 'videoUrl'
                    },
                    {
                        type: 'button',
                        text: '点击上传',
                        label:'上传视频：',
                        subtype: 'primary',
                        onclick: function(e){
                            e.preventDefault();
                            var hidden = jQuery('#hiddenID');
                            var texte = jQuery('#videoUrl');
                            var custom_uploader = wp.media.frames.file_frame = wp.media({
                                title: '上传视频',
                                button: {text: '上传视频'},
                                multiple: false
                            });
                            custom_uploader.on('select', function() {
                                var attachment = custom_uploader.state().get('selection').first().toJSON();
                                hidden.val(attachment.id);
                                if(!texte.val()){
                                    if(attachment.url)
                                        texte.val(attachment.url);
                                    else
                                        texte.val('请查看视频');
                                }
                            });
                            custom_uploader.open();
                        }
                    }],
                    onsubmit: function(e){
                        var shortcode="[wideo]" + e.data.text + "[/wideo]";
                        editor.insertContent(shortcode);
                    }
                });
            }
        });
    });
})();