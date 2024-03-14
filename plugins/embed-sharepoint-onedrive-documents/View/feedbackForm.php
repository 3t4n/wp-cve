<?php

namespace MoSharePointObjectSync\View;

class feedbackForm{

    private static $instance;

    public static function getView(){
        if(!isset(self::$instance)){
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    public function mo_sps_display_feedback_form(){

        if ( 'plugins.php' != basename( $_SERVER['PHP_SELF'])) {
            return;
        }

        wp_enqueue_style( 'mo_sps_css_plugin', plugins_url('../includes/css/mo_sps_settings.css', __FILE__), array(), PLUGIN_VERSION);

        ?>

        <div id="sps_feedback_modal" class="mo_modal" style="width:90%;margin-left:12%; margin-top:5%; text-align:center;">
            <div class="mo_modal-content" style="width:40%;padding:5px;">
                <h3 style="margin: 2%; text-align:center;"><b><?php _e('Your feedback','Embed sharepoint onedrive documents');?></b><span class="mo_close" style="cursor: pointer">&times;</span>
                </h3>
                <hr style="width:75%;">
                <form name="f" method="post" action="" id="mo_feedback">
                    <?php wp_nonce_field("mo_sps_feedback");?>
                    <input type="hidden" name="option" value="mo_sps_feedback"/>
                    <div>
                        <p style="margin:2%">
                        <h4 style="margin: 2%; text-align:center;"><?php _e('Please help us to improve our plugin by giving your opinion.','Embed sharepoint onedrive documents');?><br></h4>
                        
                        <div id="smi_rate" style="text-align:center">
                            <div style="text-align: left;padding:2% 20%;">
                                <input type="checkbox" name="sps_reason[]" value="Missing Features" id="sps_feature"/>
                                <label for="sps_feature" class="mo_sps_feedback_option" > Does not have the features I'm looking for</label>
                                <br>

                                <input type="checkbox" name="sps_reason[]" value="Costly" id="sps_costly" class="mo_sps_feedback_radio" />
                                <label for="sps_costly" class="mo_sps_feedback_option">Do not want to upgrade - Too costly</label>
                                <br>

                                <input type="checkbox" name="sps_reason[]" value="Confusing" id="sps_confusing" class="mo_sps_feedback_radio"/>
                                <label for="sps_confusing" class="mo_sps_feedback_option">Confusing Interface</label>
                                <br>

                                <input type="checkbox" name="sps_reason[]" value="Bugs" id="sps_bugs" class="mo_sps_feedback_radio"/>
                                <label for="sps_bugs" class="mo_sps_feedback_option">Bugs in the plugin</label>
                                <br>

                                <input type="checkbox" name="sps_reason[]" value="other" id="sps_other" class="mo_sps_feedback_radio"/>
                                <label for="sps_other" class="mo_sps_feedback_option">Other Reasons</label>
                            </div>
                        </div>
                        
                        <hr style="width:75%;">
                        <?php $email = get_option("mo_saml_admin_email");
                            if(empty($email)){
                                $user = wp_get_current_user();
                                $email = $user->user_email;
                            }
                            ?>
                        <div style="display:inline-block; width:60%;">
                            <input type="email" id="query_mail" name="query_mail" style="text-align:center; border:0px solid black; border-style:solid; background:#f0f3f7; width:20vw;border-radius: 6px;"
                                placeholder="<?php _e('Please enter your email address','Embed sharepoint onedrive documents');?>" required value="<?php echo $email; ?>" readonly="readonly"/>
                            
                            <input type="radio" name="edit" id="edit" onclick="editName()" value=""/>
                            <label for="edit"><img class="editable" src="<?php echo plugin_dir_url( __FILE__ ) . '../images/61456.png'; ?>" />
                            </label>
                            
                            </div>
                            
                        <div style="text-align:center;">    
                            <input type="checkbox" name="get_reply" value="reply" checked><?php _e('Allow MiniOrange Team to connect via email for speedy issue resolution and usage statistics.','Embed sharepoint onedrive documents');?></input>
                        </div>
                        <br>
                        
                        <div style="text-align:center;">
                            
                            <textarea id="query_feedback" name="query_feedback" rows="4" style="width: 60%"
                                placeholder="<?php _e('Tell us what happened!','Embed sharepoint onedrive documents');?>"></textarea>
                            <br><br>
                        </div>
                        <div class="mo-modal-footer" style="text-align: center;margin-bottom: 2%">
                            <input type="submit" name="miniorange_feedback_submit"
                                class="button button-primary button-large" value="<?php _e('Send','Embed sharepoint onedrive documents');?>"/>
                            <span width="30%">&nbsp;&nbsp;</span>
                            <input type="submit" name="miniorange_skip_feedback"
                                class="button button-primary button-large" value="<?php _e('Skip','Embed sharepoint onedrive documents');?>" onclick="document.getElementById('mo_feedback').submit();"/>
                        </div>
                    </div>
                </form>


            </div>

        </div>

        <script>
            jQuery('a[aria-label="Deactivate Embed SharePoint OneDrive Documents"]').click(function () {

                var mo_modal = document.getElementById('sps_feedback_modal');

                var span = document.getElementsByClassName("mo_close")[0];

                mo_modal.style.display = "block";
                document.querySelector("#query_feedback").focus();
                span.onclick = function () {
                    mo_modal.style.display = "none";
                    jQuery('#mo_feedback_form_close').submit();
                };

                window.onclick = function (event) {
                    if (event.target === mo_modal) {
                        mo_modal.style.display = "none";
                    }
                };
                return false;

            });

            function editName(){

                document.querySelector('#query_mail').removeAttribute('readonly');
                document.querySelector('#query_mail').focus();
                return false;

            }
            
        </script><?php

    } 
}