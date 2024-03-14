<?php
$setting_tab = get_option( 'arforms_current_tab' );
$setting_tab = ( ! isset( $setting_tab ) || empty( $setting_tab ) ) ? 'general_settings' : $setting_tab;

$selected_list_id      = '';
?>
<div id="autoresponder_settings"  class="<?php echo ( 'autoresponder_settings' != $setting_tab ) ? 'display-none-cls' : 'display-blck-cls'; ?>">
    <span class="fa-life-bouy-span">
        <a onclick="arf_help_doc_fun('arf_options_email_marketing');" target="_blank" title="" class="fas fa-life-bouy arf_adminhelp_icon">
            <svg width="30px" height="30px" viewBox="0 0 26 32" class="arfsvgposition arfhelptip tipso_style" data-tipso="help" title="help">
            <?php echo ARFLITE_LIFEBOUY_ICON; //phpcs:ignore ?>
            </svg>
        </a>
    </span>
    <table class="wp-list-table widefat post arflite-email-marketer-tbl2">
        <tr>
            <th class="email-marketer-img-th" width="18%">&nbsp;</th>
            <th class="email-marketer-img-wrapth" colspan="2"><img alt='' src="<?php echo esc_url( ARFLITEURL ); ?>/images/aweber.png" align="absmiddle" /></th>
        </tr>
        <tr>
            <th class="email-marketer-img-th" width="18%">&nbsp;</th>
            <th id="th_aweber" class="arf-email-marketer-radioth">
                <div class="arf_radio_wrapper">
                    <div class="arf_custom_radio_div" >
                        <div class="arf_custom_radio_wrapper">
                            <input type="radio" class="arf_submit_action arf_custom_radio arfemailmarkter-radioinput" checked="checked" id="aweber_1" name="aweber_type" value="1" onclick="arflite_show_api('aweber');"  />
                            <svg width="18px" height="18px">
                                <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
                                <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
                            </svg>
                        </div>
                    </div>
                    <span>
                        <label for="aweber_1"><?php echo esc_html__( 'Using API', 'arforms-form-builder' ); ?></label>
                    </span>
                </div>
                <div class="arf_radio_wrapper">
                    <div class="arf_custom_radio_div" >
                        <div class="arf_custom_radio_wrapper">
                            <input type="radio" class="arf_submit_action arf_custom_radio arfemailmarkter-radioinput" id="aweber_2" name="aweber_type" value="0" onclick="arflite_show_web_form('aweber');" />
                            <svg width="18px" height="18px">
                                <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
                                <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
                            </svg>
                        </div>
                    </div>
                    <span>
                        <label for="aweber_2"><?php echo esc_html__( 'Using Web-form', 'arforms-form-builder' ); ?></label>
                    </span>
                </div>
                <input type="hidden" name="aweber_status" id="aweber_status" />
            </th>
        </tr>

        <tr id="aweber_api_tr2" >
            <td class="tdclass" style="width:18%; padding-right:20px; padding-bottom:3px; text-align: left;">
                <label class="lblsubtitle"><?php esc_html__( 'Authorization Code', 'arforms-form-builder' ); ?></label>
            </td>
            <td style="padding-left: 4px;">
                <input type="text" name="aweber_oauth_code" class="txtmodal1" id="aweber_api" size="80" value="" />&nbsp;&nbsp;
                <span id="aweber_link"><a href="javascript:void(0);" class="arlinks arf_restricted_control"><?php esc_html__( 'Get Authorization Code', 'arforms-form-builder' ); ?></a></span>
                <input type="hidden" name="aweber_status" id="aweber_status" value="" />
                <div class="arferrmessage" id="aweber_api_error" style="display:none;"><?php echo addslashes(esc_html__('This field cannot be blank.', 'arforms-form-builder')); //phpcs:ignore ?></div></td>
            </td>
        </tr>
        <tr id="aweber_api_tr3">
            <td class="tdclass arfaweberapi_td">&nbsp;</td>
            <td class="arfaweber_auth_btn_td"><button class="rounded_button arf_btn_dark_blue arfaweber_auth_btn arf_restricted_control"  type="button" name="continue"><?php echo esc_html__( 'Authorize', 'arforms-form-builder' ); ?></button></td>
        </tr>
        <tr id="aweber_web_form_tr" class="display-none-cls">
            <td class="tdclass arfwebform-code-emailmarketer"><label class="lblsubtitle"><?php echo esc_html__( 'Webform code from Aweber', 'arforms-form-builder' ); ?></label></td>
            <td class="arfpadding-left5px">
                <textarea name="aweber_web_form" id="aweber_web_form" class="txtmultinew"></textarea>
            </td>
        </tr>
        <tr id="aweber_api_tr4" class="display-none-cls">
            <td class="tdclass arfwebform-code-emailmarketer"><label class="lblsubtitle"><?php echo esc_html__( 'AWEBER LIST', 'arforms-form-builder' ); ?></label></td>
            <td class="aweber_sellist_td">
                <span id="select_aweber">
                    <div class="sltstandard arfemail_marketer_list_div">												
                        <input name="responder_list" id="aweber_listid" value="<?php echo esc_attr( $selected_list_id ); ?>" type="hidden" class="frm-dropdown frm-pages-dropdown">
                        <dl class="arf_selectbox arfemailmar_width400px" data-name="aweber_listid" data-id="aweber_listid">
                            <dt>
                                <span><?php esc_html_e( 'Nothing Selected', 'arforms-form-builder' ); ?></span>
                                <svg viewBox="0 0 2000 1000" width="15px" height="15px">
                                    <g fill="#000">
                                    <path d="M1024 320q0 -26 -19 -45t-45 -19h-896q-26 0 -45 19t-19 45t19 45l448 448q19 19 45 19t45 -19l448 -448q19 -19 19 -45z"></path>
                                    </g>
                                </svg>
                            </dt>
                            <dd>
                                <ul class="field_dropdown_menu field_dropdown_list_menu display-none-cls"  data-id="aweber_listid">
                                </ul>
                            </dd>
                            <span id="aweber_loader2"><div class="arf_imageloader"></div></span>
                        </dl>
                    </div>
                </span>
                <div class="arlinks arfemailmarketer-delref-link-div">
                    <a href="javascript:void(0);" class="arf_restricted_control"><?php echo esc_html__( 'Refresh List', 'arforms-form-builder' ); ?></a>
                    &nbsp;  &nbsp;  &nbsp;  &nbsp;
                    <a href="javascript:void(0);" class="arf_restricted_control"><?php echo esc_html__( 'Delete Configuration', 'arforms-form-builder' ); ?></a>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="arfpadding-left5px"><div class="dotted_line dottedline-width96"></div></td>
        </tr>
    </table>

    <table class="wp-list-table widefat post arflite-email-marketer-tbl">
        <tr>
            <th class="email-marketer-img-th" width="18%">&nbsp;</th>
            <th class="email-marketer-img-wrapth" colspan="2"><img alt='' src="<?php echo ARFLITEURL; //phpcs:ignore ?>/images/mailchimp.png" align="absmiddle" /></th>
        </tr>
        <tr>
            <th class="email-marketer-img-th">&nbsp;</th>
            <th id="th_mailchimp" class="arf-email-marketer-radioth">
                <div class="arf_radio_wrapper">
                    <div class="arf_custom_radio_div" >
                        <div class="arf_custom_radio_wrapper">
                            <input type="radio" class="arf_submit_action arf_custom_radio arfemailmarkter-radioinput" checked="checked" id="mailchimp_1" name="mailchimp_type" value="1"  onclick="arflite_show_api('mailchimp');" />
                            <svg width="18px" height="18px">
                                <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
                                <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
                            </svg>
                        </div>
                    </div>
                    <span>
                        <label for="mailchimp_1"><?php echo esc_html__( 'Using API', 'arforms-form-builder' ); ?></label>
                    </span>
                </div>
                <div class="arf_radio_wrapper">
                    <div class="arf_custom_radio_div" >
                        <div class="arf_custom_radio_wrapper">
                            <input type="radio" class="arf_submit_action arf_custom_radio arfemailmarkter-radioinput" id="mailchimp_2" name="mailchimp_type" value="0" onclick="arflite_show_web_form('mailchimp');" />
                            <svg width="18px" height="18px">
                            <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
                            <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
                            </svg>
                        </div>
                    </div>
                    <span>
                        <label for="mailchimp_2"><?php echo esc_html__( 'Using Web-form', 'arforms-form-builder' ); ?></label>
                    </span>
                </div>
            </th>
        </tr>

        <tr id="mailchimp_api_tr1">

            <td class="tdclass arfemail-marketer-credential-lbl" ><label class="lblsubtitle"><?php echo esc_html__( 'API Key', 'arforms-form-builder' ); ?></label></td>

            <td class="arfemailinputtd"><input type="text" name="mailchimp_api" class="txtmodal1" id="mailchimp_api" size="80" onkeyup="arflite_show_verify_btn('mailchimp');" value="" /> &nbsp; &nbsp;
                <span id="mailchimp_link"><a href="javascript:void(0);" class="arlinks arf_restricted_control"><?php echo esc_html__( 'Verify', 'arforms-form-builder' ); ?></a></span>
                <span id="mailchimp_loader" class="display-none-cls"><div class="arf_imageloader arfemailmarketerloaderdiv"></div></span>
                <span id="mailchimp_verify" class="frm_verify_li display-none-cls"><?php echo esc_html__( 'Verified', 'arforms-form-builder' ); ?></span>
                <span id="mailchimp_error" class="frm_not_verify_li display-none-cls"><?php echo esc_html__( 'Not Verified', 'arforms-form-builder' ); ?></span>
                <input type="hidden" name="mailchimp_status" id="mailchimp_status" value="" />
                <div class="arferrmessage display-none-cls" id="mailchimp_api_error" ><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div>
            </td>

        </tr>

        <tr id="mailchimp_api_tr2">

            <td class="tdclass arflitelist-id-lbltd"><label class="lblsubtitle"><?php echo esc_html__( 'List ID', 'arforms-form-builder' ); ?></label></td>

            <td class="arfselect-email-marketer-list-td"><span id="select_mailchimp">
                    <div class="sltstandard arfemail_marketer_list_div">
                        <?php
                        $responder_list_option = '';
                        ?>
                        <input name="mailchimp_listid" id="mailchimp_listid" value="" type="hidden" class="frm-dropdown frm-pages-dropdown">
                        <dl class="arf_selectbox arfemailmar_width400px" data-name="mailchimp_listid" data-id="mailchimp_listid">
                            <dt><span><?php echo esc_html__( 'Nothing Selected', 'arforms-form-builder' ); ?></span>
                            <svg viewBox="0 0 2000 1000" width="15px" height="15px">
                            <g fill="#000">
                            <path d="M1024 320q0 -26 -19 -45t-45 -19h-896q-26 0 -45 19t-19 45t19 45l448 448q19 19 45 19t45 -19l448 -448q19 -19 19 -45z"></path>
                            </g>
                            </svg></dt>
                            <dd>
                                <ul class="field_dropdown_menu field_dropdown_list_menu display-none-cls" data-id="mailchimp_listid">
                                </ul>
                            </dd>
                        </dl>
                    </div>
                </span>
                <div id="mailchimp_del_link" class="arlinks arfemailmarketer-delref-link-div">
                    <a href="javascript:void(0);" class="arf_restricted_control"><?php echo esc_html__( 'Refresh List', 'arforms-form-builder' ); ?></a>
                    <a href="javascript:void(0);" class="arf_restricted_control"><?php echo esc_html__( 'Delete Configuration', 'arforms-form-builder' ); ?></a>
                </div>


            </td>

        </tr>

        <tr id="mailchimp_web_form_tr" class="display-none-cls">

            <td class="tdclass arfwebform-code-emailmarketer"><label class="lblsubtitle"><?php echo esc_html__( 'Webform code from Mailchimp', 'arforms-form-builder' ); ?></label></td>

            <td class="arfpadding-left5px">

                <textarea name="mailchimp_web_form" id="mailchimp_web_form" class="txtmultinew"></textarea>

            </td>

        </tr>

        <tr>
            <td colspan="2" class="arfpadding-left5px"><div class="dotted_line dottedline-width96"></div></td>
        </tr>

    </table>

    <table class="wp-list-table widefat post arflite-email-marketer-tbl" >

        <tr>
            <th class="email-marketer-img-th" width="18%">&nbsp;</th>
            <th colspan="2" class="email-marketer-img-wrapth"><img alt='' src="<?php echo esc_url( ARFLITEURL ); ?>/images/getresponse.png" align="absmiddle" /></th>

        </tr>

        <tr>
            <th class="email-marketer-img-th"></th>
            <th id="th_getresponse" class="arf-email-marketer-radioth">
        <div class="arf_radio_wrapper">
            <div class="arf_custom_radio_div" >
                <div class="arf_custom_radio_wrapper">
                    <input type="radio" class="arf_submit_action arf_custom_radio" checked="checked" id="getresponse_1" name="getresponse_type" value="1" onclick="arflite_show_api('getresponse');" />
                    <svg width="18px" height="18px">
                    <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
                    <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
                    </svg>
                </div>
            </div>
            <span>
                <label for="getresponse_1"><?php echo esc_html__( 'Using API', 'arforms-form-builder' ); ?></label>
            </span>
        </div>

        <div class="arf_radio_wrapper">
            <div class="arf_custom_radio_div" >
                <div class="arf_custom_radio_wrapper">
                    <input type="radio" class="arf_submit_action arf_custom_radio" id="getresponse_2" name="getresponse_type" value="0" onclick="arflite_show_web_form('getresponse');"/>
                    <svg width="18px" height="18px">
                    <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
                    <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
                    </svg>
                </div>
            </div>
            <span>
                <label for="getresponse_2"><?php echo esc_html__( 'Using Web-form', 'arforms-form-builder' ); ?></label>
            </span>
        </div>
        </th>
        </tr>

        <tr id="getresponse_api_tr1">


            <td class="tdclass arfemail-marketer-credential-lbl"><label class="lblsubtitle"><?php echo esc_html__( 'API Key', 'arforms-form-builder' ); ?></label></td>


            <td class="arfemailinputtd"><input type="text" name="getresponse_api" class="txtmodal1" id="getresponse_api" size="80" onkeyup="arflite_show_verify_btn('getresponse');" value="" /> &nbsp; &nbsp;

                <span id="getresponse_link"><a href="javascript:void(0);" class="arlinks arf_restricted_control"><?php echo esc_html__( 'Verify', 'arforms-form-builder' ); ?></a></span>
                <span id="getresponse_loader" class="display-none-cls"><div class="arf_imageloader arfemailmarketerloaderdiv"></div></span>
                <span id="getresponse_verify" class="frm_verify_li display-none-cls"><?php echo esc_html__( 'Verified', 'arforms-form-builder' ); ?></span>
                <span id="getresponse_error" class="frm_not_verify_li display-none-cls"><?php echo esc_html__( 'Not Verified', 'arforms-form-builder' ); ?></span>
                <input type="hidden" name="getresponse_status" id="getresponse_status" value="" />
                <div class="arferrmessage display-none-cls" id="getresponse_api_error" ><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div></td>


        </tr>


        <tr id="getresponse_api_tr2">


            <td class="tdclass arflitelist-id-lbltd"><label class="lblsubtitle"><?php echo esc_html__( 'Campaign Name', 'arforms-form-builder' ); ?></label></td>


            <td class="arfselect-email-marketer-list-td"><span id="select_getresponse">
                    <div class="sltstandard arfemail_marketer_list_div">
                        <?php
                        $responder_list_option = '';
                        ?>
                        <input name="getresponse_listid" id="getresponse_listid" value="" type="hidden" class="frm-dropdown frm-pages-dropdown">
                        <dl class="arf_selectbox arfemailmar_width400px" data-name="getresponse_listid" data-id="getresponse_listid">
                            <dt><span><?php echo esc_html__( 'Nothing Selected', 'arforms-form-builder' ); ?></span>
                            <svg viewBox="0 0 2000 1000" width="15px" height="15px">
                            <g fill="#000">
                            <path d="M1024 320q0 -26 -19 -45t-45 -19h-896q-26 0 -45 19t-19 45t19 45l448 448q19 19 45 19t45 -19l448 -448q19 -19 19 -45z"></path>
                            </g>
                            </svg></dt>
                            <dd>
                                <ul class="field_dropdown_menu field_dropdown_list_menu display-none-cls" data-id="getresponse_listid">
                                </ul>
                            </dd>
                        </dl>
                    </div></span>


                <div id="getresponse_del_link" class="arlinks arfemailmarketer-delref-link-div">

                    <a href="javascript:void(0);" class="arf_restricted_control"><?php echo esc_html__( 'Refresh List', 'arforms-form-builder' ); ?></a>
                    &nbsp;  &nbsp;  &nbsp;  &nbsp;
                    <a href="javascript:void(0);" class="arf_restricted_control"><?php echo esc_html__( 'Delete Configuration', 'arforms-form-builder' ); ?></a>
                </div>


            </td>


        </tr>

        <tr id="getresponse_web_form_tr" class="display-none-cls">

            <td class="tdclass arfwebform-code-emailmarketer"><label class="lblsubtitle"><?php echo esc_html__( 'Webform code from Getresponse', 'arforms-form-builder' ); ?></label></td>

            <td class="arfpadding-left5px">

                <textarea name="getresponse_web_form" id="getresponse_web_form" class="txtmultinew"></textarea>

            </td>

        </tr>

        <tr>
            <td colspan="2" class="arfpadding-left5px"><div class="dotted_line dottedline-width96"></div></td>
        </tr>

    </table>

    <table class="wp-list-table widefat post arflite-email-marketer-tbl">


        <tr>

            <th class="email-marketer-img-th" width="18%">&nbsp;</th>
            <th colspan="2" class="email-marketer-img-wrapth"><img alt='' src="<?php echo esc_url( ARFLITEURL ); ?>/images/icontact.png" align="absmiddle" /></th>

        </tr>

        <tr>
            <th class="email-marketer-img-th"></th>
            <th id="th_icontact" class="arf-email-marketer-radioth">
        <div class="arf_radio_wrapper">
            <div class="arf_custom_radio_div" >
                <div class="arf_custom_radio_wrapper">
                    <input type="radio" class="arf_submit_action arf_custom_radio" checked="checked"  id="icontact_1"  name="icontact_type" value="1"  onclick="arflite_show_api('icontact');" />
                    <svg width="18px" height="18px">
                    <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
                    <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
                    </svg>
                </div>
            </div>
            <span>
                <label for="icontact_1"><?php echo esc_html__( 'Using API', 'arforms-form-builder' ); ?></label>
            </span>
        </div>
        <div class="arf_radio_wrapper">
            <div class="arf_custom_radio_div" >
                <div class="arf_custom_radio_wrapper">
                    <input type="radio" class="arf_submit_action arf_custom_radio" id="icontact_2"  name="icontact_type" value="0" onclick="arflite_show_web_form('icontact');" />
                    <svg width="18px" height="18px">
                    <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
                    <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
                    </svg>
                </div>
            </div>
            <span>
                <label for="icontact_2"><?php echo esc_html__( 'Using Web-form', 'arforms-form-builder' ); ?></label>
            </span>
        </div>
        </th>

        </tr>

        <tr id="icontact_api_tr1">

            <td class="tdclass arfemail-marketer-credential-lbl"><label class="lblsubtitle"><?php echo esc_html__( 'APP ID', 'arforms-form-builder' ); ?></label></td>

            <td class="arfemailinputtd"><input type="text" name="icontact_api" class="txtmodal1" id="icontact_api" size="80" onkeyup="arflite_show_verify_btn('icontact');" value="" />
                <div class="arferrmessage display-none-cls" id="icontact_api_error"><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div></td>

        </tr>


        <tr id="icontact_api_tr2">

            <td class="tdclass arf_emilmarkter_list"><label class="lblsubtitle"><?php echo esc_html__( 'Username', 'arforms-form-builder' ); ?></label></td>

            <td class="arficontact-username-td"><input type="text" name="icontact_username" class="txtmodal1" id="icontact_username" onkeyup="arflite_show_verify_btn('icontact');" size="80" value="" />
                <div class="arferrmessage display-none-cls" id="icontact_username_error"><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div></td>

        </tr>

        <tr id="icontact_api_tr3">

            <td class="tdclass arf_emilmarkter_list"><label class="lblsubtitle"><?php echo esc_html__( 'Password', 'arforms-form-builder' ); ?></label></td>

            <td class="arficontact-username-td"><input type="password" name="icontact_password" class="txtmodal1" id="icontact_password" onkeyup="arflite_show_verify_btn('icontact');" size="80" value="" />
                <span id="icontact_link" >
                    <a href="javascript:void(0);" class="arf_restricted_control"><?php echo esc_html__( 'Verify', 'arforms-form-builder' ); ?></a>
                </span>
                <span id="icontact_loader" class="display-none-cls">
                    <div class="arf_imageloader arfemailmarketerloaderdiv"></div>
                </span>
                <span id="icontact_verify" class="frm_verify_li display-none-cls"><?php echo esc_html__( 'Verified', 'arforms-form-builder' ); ?></span>
                <span id="icontact_error" class="frm_not_verify_li display-none-cls"><?php echo esc_html__( 'Not Verified', 'arforms-form-builder' ); ?></span>
                <input type="hidden" name="icontact_status" id="icontact_status" value="" />
                <div class="arferrmessage display-none-cls" id="icontact_password_error"><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div></td>
        </tr>

        <tr id="icontact_api_tr4">

            <td class="tdclass arf_emilmarkter_list"><label class="lblsubtitle"><?php echo esc_html__( 'List Name', 'arforms-form-builder' ); ?></label></td>

            <td class="arfselect-email-marketer-list-td"><span id="select_icontact">
                    <div class="sltstandard arfemail_marketer_list_div" >
                        <?php
                        $responder_list_option = '';
                        ?>
                        <input name="icontact_listname" id="icontact_listname" value="" type="hidden" class="frm-dropdown frm-pages-dropdown">
                        <dl class="arf_selectbox arfemailmar_width400px" data-name="icontact_listname" data-id="icontact_listname">
                            <dt><span><?php echo esc_html__( 'Nothing Selected', 'arforms-form-builder' ); ?></span>
                            <svg viewBox="0 0 2000 1000" width="15px" height="15px">
                            <g fill="#000">
                            <path d="M1024 320q0 -26 -19 -45t-45 -19h-896q-26 0 -45 19t-19 45t19 45l448 448q19 19 45 19t45 -19l448 -448q19 -19 19 -45z"></path>
                            </g>
                            </svg></dt>
                            <dd>
                                <ul class="field_dropdown_menu field_dropdown_list_menu display-none-cls" data-id="icontact_listname">
                                </ul>
                            </dd>
                        </dl>
                    </div></span>


                <div id="icontact_del_link" class="arlinks arfemailmarketer-delref-link-div">

                    <a href="javascript:void(0);" class="arf_restricted_control"><?php echo esc_html__( 'Refresh List', 'arforms-form-builder' ); ?></a>
                    &nbsp;  &nbsp;  &nbsp;  &nbsp;
                    <a href="javascript:void(0);" class="arf_restricted_control"><?php echo esc_html__( 'Delete Configuration', 'arforms-form-builder' ); ?></a>
                </div>


            </td>


        </tr>

        <tr id="icontact_web_form_tr" class="display-none-cls">

            <td class="tdclass arfwebform-code-emailmarketer"><label class="lblsubtitle"><?php echo esc_html__( 'Webform code from Icontact', 'arforms-form-builder' ); ?></label></td>

            <td class="arfpadding-left5px">

                <textarea name="icontact_web_form" id="icontact_web_form" class="txtmultinew"></textarea>

            </td>

        </tr>

        <tr>
            <td colspan="2" class="arfpadding-left5px"><div class="dotted_line dottedline-width96"></div></td>
        </tr>

    </table>

    <table class="wp-list-table widefat post arflite-email-marketer-tbl" >

        <tr>
            <th class="email-marketer-img-th" width="18%">&nbsp;</th>
            <th colspan="2" class="email-marketer-img-wrapth"><img alt='' src="<?php echo esc_url( ARFLITEURL ); ?>/images/constant-contact.png" align="absmiddle" /></th>


        </tr>

        <tr>
            <th class="email-marketer-img-th">&nbsp;</th>
            <th id="th_constant" class="arf-email-marketer-radioth">
        <div class="arf_radio_wrapper">
            <div class="arf_custom_radio_div" >
                <div class="arf_custom_radio_wrapper">
                    <input type="radio" class="arf_submit_action arf_custom_radio" checked="checked" id="constant_contact_1" name="constant_type" value="1" onclick="arflite_show_api('constant');"/>
                    <svg width="18px" height="18px">
                    <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
                    <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
                    </svg>
                </div>
            </div>
            <span>
                <label for="constant_contact_1"><?php echo esc_html__( 'Using API', 'arforms-form-builder' ); ?></label>
            </span>
        </div>
        <div class="arf_radio_wrapper">
            <div class="arf_custom_radio_div" >
                <div class="arf_custom_radio_wrapper">
                    <input type="radio" class="arf_submit_action arf_custom_radio" id="constant_contact_2" name="constant_type" value="0"  onclick="arflite_show_web_form('constant');" />
                    <svg width="18px" height="18px">
                    <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
                    <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
                    </svg>
                </div>
            </div>
            <span>
                <label for="constant_contact_2"><?php echo esc_html__( 'Using Web-form', 'arforms-form-builder' ); ?></label>
            </span>
        </div>
        </th>
        </tr>

        <tr id="constant_api_tr1">

            <td class="tdclass arfemail-marketer-credential-lbl"><label class="lblsubtitle"><?php echo esc_html__( 'API Key', 'arforms-form-builder' ); ?></label></td>

            <td class="arfemailinputtd"><input type="text" name="constant_api" class="txtmodal1" onkeyup="arflite_show_verify_btn('constant');" id="constant_api" size="80" value="" />
                <div class="arferrmessage display-none-cls" id="constant_api_error" ><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div></td>
            </tr>

        <tr id="constant_api_tr2">

            <td class="tdclass arf_emilmarkter_list"><label class="lblsubtitle"><?php echo esc_html__( 'Access Token', 'arforms-form-builder' ); ?></label></td>

            <td class="arficontact-username-td"><input type="text" name="constant_access_token" onkeyup="arflite_show_verify_btn('constant');" class="txtmodal1" id="constant_access_token" size="80" value="" /> &nbsp; &nbsp;

                <span id="constant_link"><a href="javascript:void(0);" class="arlinks arf_restricted_control"><?php echo esc_html__( 'Verify', 'arforms-form-builder' ); ?></a></span>
                <span id="constant_loader" class="display-none-cls" ><div class="arf_imageloader arfemailmarketerloaderdiv"></div></span>
                <span id="constant_verify" class="frm_verify_li display-none-cls"><?php echo esc_html__( 'Verified', 'arforms-form-builder' ); ?></span>
                <span id="constant_error" class="frm_not_verify_li display-none-cls"><?php echo esc_html__( 'Not Verified', 'arforms-form-builder' ); ?></span>
                <input type="hidden" name="constant_status" id="constant_status" value="" />
                <div class="arferrmessage display-none-cls" id="constant_access_token_error" ><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div></td>

        </tr>

        <tr id="constant_api_tr3">

            <td class="tdclass arf_emilmarkter_list"><label class="lblsubtitle"><?php echo esc_html__( 'List Name', 'arforms-form-builder' ); ?></label></td>

            <td class="arfselect-email-marketer-list-td"><span id="select_constant">
                    <div class="sltstandard arfemail_marketer_list_div">
                        <?php
                        $responder_list_option = '';
                        ?>
                        <input name="constant_listname" id="constant_listname" value="" type="hidden" class="frm-dropdown frm-pages-dropdown">
                        <dl class="arf_selectbox arfemailmar_width400px" data-name="constant_listname" data-id="constant_listname">
                            <dt><span><?php echo esc_html__( 'Nothing Selected', 'arforms-form-builder' ); ?></span>
                            <svg viewBox="0 0 2000 1000" width="15px" height="15px">
                            <g fill="#000">
                            <path d="M1024 320q0 -26 -19 -45t-45 -19h-896q-26 0 -45 19t-19 45t19 45l448 448q19 19 45 19t45 -19l448 -448q19 -19 19 -45z"></path>
                            </g>
                            </svg></dt>
                            <dd>
                                <ul class="field_dropdown_menu field_dropdown_list_menu display-none-cls" data-id="constant_listname">
                                </ul>
                            </dd>
                        </dl>
                    </div></span>


                <div id="constant_del_link" class="arlinks arfemailmarketer-delref-link-div">

                    <a href="javascript:void(0);" class="arf_restricted_control"><?php echo esc_html__( 'Refresh List', 'arforms-form-builder' ); ?></a>
                    &nbsp;  &nbsp;  &nbsp;  &nbsp;
                    <a href="javascript:void(0);" class="arf_restricted_control"><?php echo esc_html__( 'Delete Configuration', 'arforms-form-builder' ); ?></a>
                </div>


            </td>

        </tr>

        <tr id="constant_web_form_tr" class="display-none-cls">

            <td class="tdclass arfwebform-code-emailmarketer"><label class="lblsubtitle"><?php echo esc_html__( 'Webform code from Constant Contact', 'arforms-form-builder' ); ?></label></td>

            <td class="arfpadding-left5px">

                <textarea name="constant_web_form" id="constant_web_form" class="txtmultinew"></textarea>

            </td>

        </tr>

        <tr>
            <td colspan="2" class="arfpadding-left5px"><div class="dotted_line dottedline-width96"></div></td>
        </tr>

    </table>

    <table class="wp-list-table widefat post arflite-email-marketer-tbl">

        <tr>
            <th class="email-marketer-img-th" width="18%">&nbsp;</th>
            <th class="email-marketer-img-wrapth" colspan="2"><img alt='' src="<?php echo esc_url( ARFLITEURL ); ?>/images/gvo.png" align="absmiddle" /></label></th>

        </tr>

        <tr>
            <th class="email-marketer-img-th"></th>
            <th class="arf-email-marketer-radioth" id="th_gvo">
        <div class="arf_radio_wrapper">
            <div class="arf_custom_radio_div" >
                <div class="arf_custom_radio_wrapper">
                    <input type="radio" class="arf_submit_action arf_custom_radio" checked="checked" id="gvo_1" name="gvo_type" value="0" onclick="arflite_show_web_form('gvo');" />
                    <svg width="18px" height="18px">
                    <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
                    <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
                    </svg>
                </div>
            </div>
            <span>
                <label for="gvo_1"><?php echo esc_html__( 'Using Web-form', 'arforms-form-builder' ); ?></label>
            </span>
        </div>
        </th>
        </tr>

        <tr id="gvo_web_form_tr">

            <td class="tdclass arfwebform-code-emailmarketer"><label class="lblsubtitle"><?php echo esc_html__( 'Webform code from GVO Campaign', 'arforms-form-builder' ); ?></label></td>

            <td class="arfpadding-left5px">

                <textarea name="gvo_api" id="gvo_api" class="txtmultinew"></textarea>

            </td>

        </tr>

        <tr>
            <td colspan="2" class="arfpadding-left5px"><div class="dotted_line dottedline-width96"></div></td>
        </tr>

    </table>

    <table class="wp-list-table widefat post arflite-email-marketer-tbl3">

        <tr>
            <th class="email-marketer-img-th" width="18%">&nbsp;</th>
            <th class="email-marketer-img-wrapth" colspan="2"><img alt='' src="<?php echo esc_url( ARFLITEURL ); ?>/images/ebizac.png" align="absmiddle" /></th>

        </tr>

        <tr>
            <th class="email-marketer-img-th"></th>
            <th id="th_ebizac" class="arf-email-marketer-radioth">
        <div class="arf_radio_wrapper">
            <div class="arf_custom_radio_div" >
                <div class="arf_custom_radio_wrapper">
                    <input type="radio" class="arf_submit_action arf_custom_radio" checked="checked" id="ebizac_1" name="ebizac_type" value="0" onclick="arflite_show_web_form('ebizac');" />
                    <svg width="18px" height="18px">
                    <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
                    <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
                    </svg>
                </div>
            </div>
            <span>
                <label for="ebizac_1"><?php echo esc_html__( 'Using Web-form', 'arforms-form-builder' ); ?></label>
            </span>
        </div>
        </th>

        </tr>

        <tr id="ebizac_web_form_tr" >

            <td class="tdclass arfwebform-code-emailmarketer"><label class="lblsubtitle"><?php echo esc_html__( 'Webform code from eBizac', 'arforms-form-builder' ); ?></label></td>

            <td class="eBizac_textarea-td">
                <textarea name="ebizac_api" id="ebizac_api" class="txtmultinew"></textarea>
            </td>

        </tr>
        <tr>
            <td colspan="2" class="arfpadding-left5px"><div class="dotted_line dottedline-width96" ></div></td>
        </tr>


    </table>
</div>