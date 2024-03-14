// JavaScript Document
    (function() {  
        tinymce.create('tinymce.plugins.plumwd_twitch_stream', {  
            init : function(ed, url) {  
			var newurl = url.substring(0, url.length -8);
                ed.addButton('plumwd_twitch_stream', {  
                    title : 'Insert Twitch Stream',  
                    image : newurl+'/images/tv.png',  
                    onclick : function() {  
                         ed.selection.setContent('[plumwd_twitch_stream]');        
                    }  
                }); 
				
				ed.addButton('plumwd_twitch_chat', {  
                    title : 'Insert Twitch Chat',  
                    image : newurl+'/images/chat.png',  
                    onclick : function() {  
                         ed.selection.setContent('[plumwd_twitch_chat]');        
                    }  
                });   
            },  
            createControl : function(n, cm) {  
                return null;  
            },  
        });  
        tinymce.PluginManager.add('plumwd_twitch_stream', tinymce.plugins.plumwd_twitch_stream);  
    })(); 