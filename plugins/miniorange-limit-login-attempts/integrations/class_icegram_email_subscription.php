<?php
	
	class Mo_Icegram_EmailSubscription{

        public static function recaptcha_for_email_sunscription() {  
            echo ' <script type="text/javascript">
                    var containsScript = false;
                    var flag = false;
                    var list = document.getElementsByTagName("script");
                    var i = list.length;
                    while (i--) {
                        if (list[i].src === "https://www.google.com/recaptcha/api.js") {
                            containsScript = true;
                            break;
                        }
                    }
                    
                    if (containsScript === false) {
                        var tag = document.createElement("script");
                        tag.src = "https://www.google.com/recaptcha/api.js";
                        document.getElementsByTagName("body")[0].appendChild(tag);
                    }
                    
                    if (document.contains(document.getElementById("mo_captcha"))) {
                        document.getElementById("mo_captcha").remove();
                    }
                    var bdy = document.getElementsByTagName("body")[0]; // body element
                    var newDiv = document.createElement("div");
                    newDiv.setAttribute("id", "mo_captcha");
                    bdy.appendChild(newDiv);
                
                    jQuery( window ).load(function() {
                        var es_widget_form = jQuery(".es_widget_form");
                        if (es_widget_form[0]) {
                            jQuery("#es_txt_email").onkeypress = null;
                            var es_txt_button = jQuery("#es_txt_button")[0];
                            var oldAction = es_txt_button.onclick;
                            
                            var captchaWidgetId = grecaptcha.render( "mo_captcha", {
                              "sitekey" : "'.esc_html(get_option('mo_lla_recaptcha_site_key')).'",
                              "callback": function() {
                                    flag = true;
                              } 
                            });
                            jQuery("#mo_captcha").insertBefore( es_txt_button);
                            jQuery("#es_txt_button").attr("onclick","").unbind("click");
                            es_txt_button.onclick = function () {
                                if(!flag) {
                                    alert("Invalid captcha. Please verify captcha again.");
                                } else {
                                    var res = oldAction();
                                    if (res === undefined) {
                                        grecaptcha.reset(captchaWidgetId);
                                        flag = false;
                                    }
                                }
                            };
                            document.getElementById("es_txt_email").onkeypress = null;
                        }
                    });
                
                </script>';
        }
	}

?>