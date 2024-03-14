/**
 * @package: Remove_Footer_Links
 * @author: plugindeveloper
 * @version: 1.0.0
 * @author_uri: https://profiles.wordpress.org/plugindeveloper/
 * @since 1.0.0
 */
(function($){
    'use strict';
    var Remove_Footer_Links = {

        //Custom Snipits goes here
        Snipits: {

            remove_links: function( e ){
                var config = remove_footer_links_config;
                var theme = config.theme;
                var author_uri = theme.author_uri;
                var theme_uri = theme.theme_uri;
                var permalink = config.permalink;

                author_uri = author_uri.replace(/\/$/, '');
                theme_uri = theme_uri.replace(/\/$/, '');

                if(permalink.match(author_uri) || permalink.match(theme_uri)){
                    return;
                }
                if(config.auto_remove_links=="1"){
                    
                    $('footer a[href*="'+author_uri+'"]').parent().remove();
                    $('footer a[href*="'+theme_uri+'"]').parent().remove();
                }
                
            },

        },     

        Events: function(){

            var __this = Remove_Footer_Links;
            var snipits = __this.Snipits;
            snipits.remove_links();

        },

        Ready: function(){
            
            var __this = Remove_Footer_Links;
            var snipits = __this.Snipits;

            __this.Events();

        },

        Load: function(){

        },

        Resize: function(){

        },

        Scroll: function(){

        },

        Init: function(){

            var __this = Remove_Footer_Links;
            var docready = __this.Ready;
            var winload = __this.Load;
            var winresize = __this.Resize;
            var winscroll = __this.Scroll;
            $(document).ready(docready);
            $(window).load(winload);
            $(window).scroll(winscroll);
            $(window).resize(winresize);

        },

     };
     
     Remove_Footer_Links.Init();

})(jQuery);