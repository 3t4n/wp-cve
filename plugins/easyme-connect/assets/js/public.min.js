jQuery(window).on("em:init",function(b){window.easymeConnect.app.setConfig($cfg$)});jQuery(document).ready(function(){jQuery(window).on("em:ready",function(){var b=window.easymeConnect.app.modules,c=function(){return b.util.ensureLogin({callback:function(){window.location.reload(!0)}})};jQuery("a[href='https://ezme.io/wp/autologin']").each(function(a){0===a&&c()});jQuery("a[href='https://ezme.io/wp/login']").on("click",function(a){a.preventDefault();c();return!1});jQuery("a[href='https://ezme.io/wp/profile']").on("click",function(a){a.preventDefault();b.profile.open();return!1});jQuery("a[href='https://ezme.io/wp/logout']").on("click",function(a){a.preventDefault();b.oauth.logout();return!1})})});



