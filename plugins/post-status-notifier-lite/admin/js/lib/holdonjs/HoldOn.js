
(function(window){'use strict';

    function HoldOnAction(){
        if("undefined"==typeof jQuery){
            throw new Error("HoldOn.js requires jQuery");
        }

        var HoldOn = {};

        HoldOn.open = function(properties){
            jQuery('#holdon-overlay').remove();//RemoveIfCalledBefore
            var theme = "sk-rect";
            var content = "";
            var message = "";

            if(properties){
                if(properties.hasOwnProperty("theme")){//Choose theme if given
                    theme = properties.theme;
                }

                if(properties.hasOwnProperty("message")){//Choose theme if given
                    message = properties.message;
                }
            }

            switch(theme){
                case "custom":
                    content = '<div style="text-align: center;">' + properties.content + "</div>";
                    break;
                case "sk-rect":
                    content = '<div class="sk-rect"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div>';
                    break;
                case "sk-circle":
                    content = '<div class="sk-circle"> <div class="sk-circle1 sk-child"></div> <div class="sk-circle2 sk-child"></div> <div class="sk-circle3 sk-child"></div> <div class="sk-circle4 sk-child"></div> <div class="sk-circle5 sk-child"></div> <div class="sk-circle6 sk-child"></div> <div class="sk-circle7 sk-child"></div> <div class="sk-circle8 sk-child"></div> <div class="sk-circle9 sk-child"></div> <div class="sk-circle10 sk-child"></div> <div class="sk-circle11 sk-child"></div> <div class="sk-circle12 sk-child"></div> </div>';
                    break;
                default:
                    content = '<div class="sk-rect"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div>';
                    console.warn(theme + " doesn't exist for HoldOn.js");
                    break;
            }

            var Holder    = '<div id="holdon-overlay" style="display: none;">\n\
                                    <div id="holdon-content-container">\n\
                                        <div id="holdon-content">'+content+'</div>\n\
                                        <div id="holdon-message">'+message+'</div>\n\
                                    </div>\n\
                                </div>';

            jQuery(Holder).appendTo('body').fadeIn(300);

            if(properties){
                if(properties.backgroundColor){
                    jQuery("#holdon-overlay").css("backgroundColor",properties.backgroundColor);
                }

                if(properties.backgroundColor){
                    jQuery("#holdon-message").css("color",properties.textColor);
                }
            }
        };

        HoldOn.close = function(){
            jQuery('#holdon-overlay').fadeOut(300, function(){
                jQuery(this).remove();
            });
        };

        return HoldOn;
    }

    if(typeof(HoldOn) === 'undefined'){
        window.HoldOn = HoldOnAction();
    }

})(window);