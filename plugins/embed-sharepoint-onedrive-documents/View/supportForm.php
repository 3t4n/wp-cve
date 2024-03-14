<?php

namespace MoSharePointObjectSync\View;

use MoSharePointObjectSync\Wrappers\pluginConstants;

class supportForm{
    private static $instance;  
    public static function getView(){
        if(!isset(self::$instance)){
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    public function mo_sps_display_support_form(){
    ?>
        <style>

            .support_container{ 
                display:right;
                justify-content:flex-start;
                align-items:center;
                flex-direction:column;
                /*width:32em;*/
                margin:55px 10px;
                background-color:#a6dee0;
                box-shadow: rgb(207,213,222) 1px 2px 4px;
                border: 1px solid rgb(216,216,216);
            }

            .support__telphone{
                width:27em;
            }

            .support_header{
                /* display: flex;
                justify-content: center;
                align-items: center; */
                width: 100%;
                height: 246px;
                background-image: url(<?php echo plugin_dir_url(__FILE__).'../images/support-header2.jpg';?>);
                background-color: #fff;
                background-size: cover;
            }

            @media only screen and (min-width: 1700px) {
                .support_container{
                /*width:37em;*/
                }
            }

            @media only screen and (max-width: 1400px) {
                .support_container{
                /*width:28em;*/
                }
                .support__telphone{
                width:24em;
                }
            }

            @media only screen and (max-width: 1229px) {
                .support_container{
                /*width:23em;*/
                }
                .support__telphone{
                width:19.5em;
                }
            }

        </style>


        <div style="width:40%;position:sticky;top: 0">
            <form method="post" action="">
                <input type="hidden" name="option" value="mo_sps_contact_us_query_option" >
                <div class="support_container">
                    <div class="support_header">
                    </div>
                    
			        <?php  wp_nonce_field('mo_sps_contact_us_query_option'); ?>
                    <div style="display:flex;justify-content:flex-start;align-items:center;width:90%;margin-top:8px;margin-left:12px;font-size:14px;font-weight:500;">Email:</div>
                    <input style="block-size:7px;padding:10px 10px;width:91%;border:none;margin-top:4px;margin-left:12px;background-color:#fff;" type="email" required name="mo_sps_contact_us_email" value="<?php echo ( get_option( 'mo_sps_admin_email' ) == '' ) ? get_option( 'admin_email' ) : get_option( 'mo_sps_admin_email' ); ?>" placeholder="Email"/>
                    <div style="display:flex;justify-content:flex-start;align-items:center;width:90%;margin-top:8px;font-size:14px;margin-left:12px;font-weight:500;">Contact No.:</div>
                    <input id="contact_us_phone" class="support__telphone" type="tel" style="block-size:7px;padding:10px 42px;width:91%;border:none;margin-top:4px;margin-left:12px;background-color:#fff;"  pattern="[\+]?[0-9]{1,4}[\s]?([0-9]{4,12})*" name="mo_sps_contact_us_phone" value="<?php echo get_option( 'mo_sps_admin_phone' ); ?>" placeholder="Enter your phone"/>
                   

                    <div style="display:flex;justify-content:flex-start;align-items:center;width:90%;margin-top:5px;font-size:14px;margin-left:12px;font-weight:500;">How can we help you?</div>
                    <textarea style="padding:10px 10px;width:91%;border:none;margin-top:5px;margin-left:12px;background-color:#fff;" onkeypress="mo_sps_valid_query(this)" onkeyup="mo_sps_valid_query(this)" onblur="mo_sps_valid_query(this)" required name="mo_sps_contact_us_query" rows="3" style="resize: vertical;" placeholder="You will get reply via email"></textarea>

                    <div style="text-align:center;">
                        <input type="submit" name="submit" style=" width:120px;margin:8px;background-color:#1B9BA1;border:none;color:white;font:bold;" class="button button-large"/>
                    </div>
                </div>
            </form>
        </div>
     
        <script>
            function mo_sps_valid_query(f) {
            !(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(
                /[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;
            }

            jQuery("#contact_us_phone").intlTelInput();
        </script>   
    <?php
    }
}