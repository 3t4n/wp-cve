<?php

namespace MoSharePointObjectSync\View;
use MoSharePointObjectSync\Wrappers\wpWrapper;

class accountSetup
{
    private static $instance;

    public static function getView(){
        if(!isset(self::$instance)){
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    public function mo_sps_display__tab_details(){
        $flag=wpWrapper::mo_sps_is_customer_registered();
        if($flag){
            $this->mo_api_display_account_information();
        }
        else{
            if(get_option('mo_sps_registration_status')=='Login User'){
                $this->mo_api_final_view_login();
            }
            else{
                $this->mo_api_final_view_registration();
            }
        }
    }

    private function mo_api_display__show_login_page(){
        ?>
        <div class="mo-ms-tab-content" style="width:77rem; ">
            <h1 >Account Setup</h1>
            <div style="width: 92%">
            <div class="mo-ms-tab-content-left-border" >
                <form class="mo_msgraph_ajax_submit_form" id="mo_api_account_form" method="post">
                    <input type="hidden" name="option" value="mo_api_account_login_setup_option">
                    <input type="hidden" name="mo_sps_tab" value="account_setup">
                    <?php wp_nonce_field('mo_api_account_login_setup_option');?>
                    <div class="mo-ms-tab-content-tile">
                        <div class="mo-ms-tab-content-tile-content">
                            <?php $this->mo_api_display_why_login(); ?>
                            <div>
                                <table class="mo-ms-tab-content-app-config-table">
                                    <colgroup>
                                        <col span="1" style="width: 10%;">
                                        <col span="2" style="width: 50%;">
                                    </colgroup>
                                    <tr>
                                        <td><span>Email<sup style="color:red">*</sup></span></td>
                                        <td>
                                            <input type="email" required placeholder="person@example.com" name="account_email" value=''>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Password<sup style="color:red">*</sup></span></td>
                                        <td><input type="password" required placeholder="Enter your password" name="account_pwd" style="width: 100%"
                                                   minlength="6" pattern="^[(\w)*(!@#.$%^&*-_)*]+$"
                                                   title="Minimum 6 characters should be present. Maximum 15 characters should be present. Only following symbols (!@#.$%^&*-_) should be present"
                                            >
                                        </td>
                                    </tr>

                                    <tr><td></td></tr>

                                    <tr><td></td></tr>

                                    <tr>
                                    <td><span></span></td>
                                        <td>
                                            <input type="submit" class="button button-primary button-large" value="Login" style="float:left;"  href="?page=mo_azos&tab=account_setup">
                                            &nbsp;
                                            <input type="button" class="button button-primary button-large" value="Create an account ? Register" onclick="clickRegisHandler()"/>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td><span></span></td>
                                        <td>
                                            <div style=" padding-top: 1.5%;"><a  href="https://login.xecurify.com/moas/idp/resetpassword"  target="_blank">Click here if you forgot your password?</a></div>
                                            
                                        </td>

                                    </tr>

                                </table>


                                <script>
                                    function clickRegisHandler(){
                                        document.getElementById('check_regis').submit();
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </form>

                <form id="check_regis" method="post">
                    <input type="hidden" name="option" value="mo_api_is_regis"/>
                    <input type="hidden" name="mo_sps_tab" value="account_setup">
                    <?php wp_nonce_field('mo_api_is_regis');?>
                </form>
            </div>
        </div>
        </div>
        <?php
    }

    private function mo_api_display_account_information()
    {
        ?>
        <div class="mo-ms-tab-content" style="width: 77rem">
            <h1>Account Setup</h1>
            <div style="width: 92%">
            <div class="mo-ms-tab-content-left-border">
                <form class="mo_msgraph_ajax_submit_form" id="mo_api_remove_account_form" method="post">
                    <input type="hidden" name="option" value="mo_api_remove_account_option">
                    <input type="hidden" name="mo_sps_tab" value="account_setup">
                    <?php wp_nonce_field('mo_api_remove_account_option');?>
                    <div class="mo-ms-tab-content-tile">
                        <div class="mo-ms-tab-content-tile-content">
                            <span style="font-size: 18px;font-weight: 400;">Your Profile</span>
                            </br></br>
                            <span>Thank you for registering with <b>miniOrange</b>.</span>
                            </br>
                            </br>
                            <div >
                                <table border="1" style="background-color:#FFFFFF; border:1px solid #CCCCCC; border-collapse: collapse; padding:0px 0px 0px 10px; margin:2px; width:85%">
                                    <tr>
                                        <td style="width:45%; padding: 10px;">miniOrange Account Email</td>
                                        <td style="width:55%; padding: 10px;"><?php echo get_option('mo_sps_admin_email'); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="width:45%; padding: 10px;">Customer ID</td>
                                        <td style="width:55%; padding: 10px;"><?php echo get_option('mo_sps_admin_customer_key'); ?></td>
                                    </tr>
                                </table>
                                </br>
                                <input type="submit" class="button button-primary button-large" value="Remove Account">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
        <?php

    }



    private function mo_api_display_why_login()
    {
        ?>
        <div style="margin-bottom: 1%" >
            <h3 >Why should I login?</h3>
            <hr>
            <p style="text-align: justify ;">You should login so that you can easily reach out to us in case you face any issues while setting up the plugin.
                <b> You will also need a miniOrange account to upgrade to the premium version of the plugin.</b>
                We do not store any information except the email that you will use to register with us.
            </p>
        </div>
        <?php
    }

    private function mo_api_final_view_login(){
        $this->mo_api_display__show_login_page();
    }



    private function mo_api_display__show_registration_page(){
        ?>
        <div class="mo-ms-tab-content" style="width: 77rem">
            <h1 >Account Setup</h1>
            <div style="width: 92%">
            <div class="mo-ms-tab-content-left-border">
                <form class="mo_msgraph_ajax_submit_form" id="mo_api_account_form" method="post">
                    <input type="hidden" name="option" value="mo_api_account_registration_setup_option">
                    <input type="hidden" name="mo_sps_tab" value="account_setup">
                    <?php wp_nonce_field('mo_api_account_registration_setup_option');?>
                    <div class="mo-ms-tab-content-tile">
                        <div class="mo-ms-tab-content-tile-content">
                            <?php $this->mo_api_display_why_registration(); ?>
                            <div >
                                <table class="mo-ms-tab-content-app-config-table">
                                    <colgroup>
                                        <col span="1" style="width: 15%;">
                                        <col span="2" style="width: 50%;">
                                    </colgroup>
                                    <tr>
                                        <td><span>Email<sup style="color:red">*</sup></span></td>
                                        <td>
                                            <input type="email" required placeholder="person@example.com" name="account_email" value=''>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Password<sup style="color:red">*</sup></span></td>
                                        <td><input type="password" required placeholder="Enter your password" name="account_pwd" style="width: 100%"
                                                   minlength="6" pattern="^[(\w)*(!@#.$%^&*-_)*]+$"
                                                   title="Minimum 6 characters should be present. Maximum 15 characters should be present. Only following symbols (!@#.$%^&*-_) should be present"
                                            >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span >Confirm Password<sup style="color:red">*</sup></span></td>
                                        <td><input type="password" required placeholder="Confirm your password" name="confirm_account_pwd" style="width: 100%" minlength="6" pattern="^[(\w)*(!@#.$%^&*-_)*]+$" title="Minimum 6 characters should be present. Maximum 15 characters should be present. Only following symbols (!@#.$%^&*-_) should be present">
                                        </td>
                                    </tr>
                                    <tr><td></td></tr>

                                    <tr><td></td></tr>

                                    <tr>
                                        <td></td>
                                        <td>
                                            <input type="submit" class="button button-primary button-large" value="Register">
                                            <input type="button" style="border: 0px;background-color: white;color: #069;text-decoration: underline;cursor: pointer;padding-top: 1%;margin-left: 2%" value="Already have an account ? Login" onclick="clickHandler()"/>
                                        </td>
                                    </tr>
                                </table>
                                <script>
                                    function clickHandler(){
                                        document.getElementById('check_login').submit();
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </form>

                <form id="check_login" method="post">
                    <?php wp_nonce_field('mo_api_is_login');?>
                    <input type="hidden" name="option" value="mo_api_is_login"/>
                    <input type="hidden" name="mo_sps_tab" value="account_setup">
                </form>
            </div>
            </div>
        </div>


        <?php
    }


    private function mo_api_display_why_registration(){
        ?>
        <div >
            <h3 >Why should I register?</h3>
            <hr>
            <p  style="text-align: justify ;">You should register so that in case you need help, we can help you with step-by-step instructions.
                We support integrations with many WordPress plugins, you can also reach out in case you want a new integration or need help with setting up the plugin.
                <b>You will also need a miniOrange account to upgrade to the premium version of the plugins.</b>
                We do not store any information except the email that you will use to register with us.
            </p>
        </div>
        <?php
    }


    private function mo_api_final_view_registration(){
        $this->mo_api_display__show_registration_page();
    }


}