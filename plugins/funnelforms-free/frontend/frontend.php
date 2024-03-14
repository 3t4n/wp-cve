<?php

class Fnsf_FrontendView {

    private $frontend_id = 0;

    /**
     * Fnsf_FrontendView constructor
     */
    public function __construct() {
        //...
    }

    function fnsf_af2_generate_frontend($atts) {
        $atts = shortcode_atts( array(
            'id' => 0,
            'preview' => false
        ), $atts, 'anfrageformular2' );
        return $this->fnsf__af2_generate_frontend($atts);
    }
    function fnsf_af2_generate_frontend_($atts) {
        $atts = shortcode_atts( array(
            'id' => 0,
            'preview' => false
        ), $atts, 'funnelforms' );
        return $this->fnsf__af2_generate_frontend($atts);
    }

    /**
     * The function to generate the base construct of the frontend
     *
     * @param $atts
     * @return string
     */
    function fnsf__af2_generate_frontend($atts) {

        require_once FNSF_AF2_ADMIN_HELPER_PATH;
        $Admin = new Fnsf_Af2AdminHelper();
        $all_formular_posts = $Admin->fnsf_af2_get_posts( FNSF_FORMULAR_POST_TYPE );
        if(sizeof($all_formular_posts) > 5) return __('A maximum of 5 forms can be created in the Free Version!', 'funnelforms-free');

        require_once FNSF_AF2_RESOURCE_HANDLER_PATH;

        $dataid = $atts['id'];                                                            // The Dataid of the Formular
        $base_post = get_post($dataid);                                                   // The post of it out of DB
        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
        $base_json = fnsf_af2_get_post_content($base_post);             

        $base_json = json_decode(json_encode($base_json));

        $first_element_id = $base_json->sections[0]->contents[0]->data;
        $request_preload_ids = array($dataid);
        if($first_element_id != null && $first_element_id != '') {
            array_push($request_preload_ids, $first_element_id);
        }

        global $wp_locale_switcher;
        if(isset($base_json->fe_locale) && $base_json->fe_locale !== "default") {
            $wp_locale_switcher->switch_to_locale( $base_json->fe_locale );
        }
        else if(strpos(get_locale(), 'de_') !== false) {
            $wp_locale_switcher->switch_to_locale( 'de_DE' );
        }

        $type = 0;
        if(isset($base_json->adjust_containersize) && $base_json->adjust_containersize == 'true') $type = 2;
        if($atts['preview'] == true) $type = 1;
        else update_option('checklist_shortcode', 'true');

        af2_load_frontend_resources($type);

        $userid = get_current_user_id();

        $suported_types = fnsf_af2_get_supported_mime_types();
        $server_max_size = size_format(wp_max_upload_size());
        wp_localize_script('af2_frontend', 'af2_frontend_ajax', // Localizing the Script to use ajax loading
            array(
                'ajax_url' => admin_url('admin-ajax.php'),'nonce' => wp_create_nonce('af2_FE_nonce_'),
                'datas' => $this->fnsf_get_data_loadup_first($request_preload_ids),
                'supported_file_types' => implode(',', $suported_types),
                'server_max_size' => trim(str_replace("MB","", $server_max_size)),
                'supported_server_size' => 'AIzaSyBndbQcPBJHZyoqmdgexoTStZUk53dHRNw',
                'strings' => array(
                    'antworten_tag' => __('[ANSWERS]', 'funnelforms-free'),
                    'error_01' => __('ERROR - [01] Ajax error (caching error, please contact support)!', 'funnelforms-free'),
                    'fehler_admin' => __('An error has occurred! If you are an administrator of this website, you can find more information here:', 'funnelforms-free'),
                    'fehler_find' => __('An error has occurred! Please check your questions, contact forms and forms for completeness!', 'funnelforms-free'),
                    'help' => __('Get help', 'funnelforms-free'),
                    'help_url' => __('https://help.funnelforms.io/das-formular-wird-auf-der-website-nicht-angezeigt/', 'funnelforms-free'),
                    'erroroccured' => __('An error has occurred!', 'funnelforms-free'),
                    'sms' => __('SMS verification', 'funnelforms-free'),
                    'sms_sent' => __('A verification code was sent via SMS to the following number:', 'funnelforms-free'),
                    'sms_change' => __('Change number', 'funnelforms-free'),
                    'sms_repeat' => __('Send again', 'funnelforms-free'),
                    'sms_verify' => __('Verify', 'funnelforms-free'),
                    'country_search_placeholder' => __('Search for country or dial code', 'funnelforms-free'),
                    'dot' => __(',', 'funnelforms-free'),
                    'form_sent' => __('FORM SENT', 'funnelforms-free'),
                    'mr' => __('Mr.', 'funnelforms-free'),
                    'mrs' => __('Mrs.', 'funnelforms-free'),
                    'diverse' => __('Diverse', 'funnelforms-free'),
                    'company' => __('Company', 'funnelforms-free'),
                )
            )
        );

        load_basic_frontend_resources($type);

        
        $content = '';                         // Content to draw

        $global_font_name = $base_json->styling->global_font;
        if(isset($base_json->styling->global_font) && strpos($base_json->styling->global_font, '.') !== false) {
            $global_font_name = explode('.', $base_json->styling->global_font)[0];
            $upload_dir = wp_upload_dir();
            $af2_fonts_dir = $upload_dir['baseurl'] . '/af2_fonts';

            $content .= '<style>';
            $content .= '@font-face {';
                $content .= 'font-family: "'.$global_font_name.'";';
                $content .= 'src: url("'.$af2_fonts_dir.'/'.$base_json->styling->global_font.'") format("truetype");';
                $content .= 'font-weight: 100 900;';
            $content .= '}';
            $content .= '</style>';
        }
        $base_json->styling->global_font = $global_font_name;

        //$loading_path = plugins_url(AF2_LOADING_GIF, AF2F_PLUGIN);


        /** Check if license is active * */
            /** Fetching Data from the given "Formular" * */
            

            if ($base_json != null) {
                if(isset($base_json->error) && $base_json->error) return __('Your form has an error', 'funnelforms-free');
                $size = sizeof($base_json->sections);                  // The maximum amount of steps

                if($size > 6) return __('In the Free Version, a maximum of 6 elements can be arranged in a row!', 'funnelforms-free');

                /** If there are special fields -> the size will be one less * */
                if (strpos($base_json->sections[$size - 1]->contents[0]->data, 'redirect:') !== false || strpos($base_json->sections[$size - 1]->contents[0]->data, 'dealsnprojects:') !== false || strpos($base_json->sections[$size - 1]->contents[0]->data, 'activecampaign:') !== false || strpos($base_json->sections[$size - 1]->contents[0]->data, 'klicktipp:') !== false
                ) {
                    $size--;
                }

                /** Getting the Frontend-Title * */
                /*
                 * INCOMING -> Build migration!
                 * $fe_title = --> access_database -> get( $dataid ) -> get_frontend_name
                 */
                $fe_title = isset($base_json->styling->fe_title) ? $base_json->styling->fe_title : '';                 // Frontent-Title of Formular
                
                $loader_color = !empty($base_json->styling->form_loader_color)?$base_json->styling->form_loader_color:'rgba(0, 0, 0, 1)';
                
                $back_btn_style_class = '';
                $forward_btn_style_class = '';

                $rtl_layout = false;
                if(isset($base_json->rtl_layout) && $base_json->rtl_layout != '') {
                    $rtl_layout = $base_json->rtl_layout;
                }

                if(!$rtl_layout)
                {
                    if(!empty($base_json->styling->global_prev_text)){
                        $back_btn_text = $base_json->styling->global_prev_text;
                        $back_btn_style_class = 'special';
                    }else if(isset($base_json->showFontAwesome) && $base_json->showFontAwesome){
                        $back_btn_text = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg>';
                        //$back_btn_text = '<i class="fas fa-long-arrow-alt-left fa-lg"></i>';
                    }else{
                        $back_btn_text = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg>';
                    }
                    
                    if(!empty($base_json->styling->global_next_text)){
                        $forward_btn_text = $base_json->styling->global_next_text;
                        $forward_btn_style_class = 'special';
                    }else if(isset($base_json->showFontAwesome) && $base_json->showFontAwesome){
                        $forward_btn_text = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-right" class="svg-inline--fa fa-long-arrow-alt-right fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z"></path></svg>';
                    }else{
                        $forward_btn_text = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-right" class="svg-inline--fa fa-long-arrow-alt-right fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z"></path></svg>';
                    }
                }
                else {
                    if(!empty($base_json->styling->global_prev_text)){
                        $back_btn_text = $base_json->styling->global_prev_text;
                        $back_btn_style_class = 'special';
                    }else if(isset($base_json->showFontAwesome) && $base_json->showFontAwesome){
                        $back_btn_text = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-right" class="svg-inline--fa fa-long-arrow-alt-right fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z"></path></svg>';
                        //$back_btn_text = '<i class="fas fa-long-arrow-alt-left fa-lg"></i>';
                    }else{
                        $back_btn_text = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-right" class="svg-inline--fa fa-long-arrow-alt-right fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z"></path></svg>';
                    }
                    
                    if(!empty($base_json->styling->global_next_text)){
                        $forward_btn_text = $base_json->styling->global_next_text;
                        $forward_btn_style_class = 'special';
                    }else if(isset($base_json->showFontAwesome) && $base_json->showFontAwesome){
                        $forward_btn_text = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg>';
                    }else{
                        $forward_btn_text = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg>';
                    }
                }
                
                
                /** Getting the Preload-Part * */
                /*
                 * INCOMING -> Build migration!
                 *
                 */
                $preload = 2;

                $errormail = false;
                $aScrollToAnchor = true;
                $aShowSuccessScreen = true;

                /*if(!isset($base_json->send_error_mail) || $base_json->send_error_mail !== false) {
                    $errormail = true;
                }*/
                if(isset($base_json->activateScrollToAnchor) && ($base_json->activateScrollToAnchor === 'false' || $base_json->activateScrollToAnchor === false)) {
                    $aScrollToAnchor = false;
                }
                if(isset($base_json->showSuccessScreen) && ($base_json->showSuccessScreen === 'false' || $base_json->showSuccessScreen === 'false')) {
                    $aShowSuccessScreen = false;
                }

                /**$loading_delay = 0;
                if($base_json->loadingDelay != null && $base_json->loadingDelay != "") {
                    $loading_delay = $base_json->loadingDelay;
                }

                $popcssclass = "";
                if($base_json->popcssclass != null && $base_json->popcssclass != "") {
                    $popcssclass = $base_json->popcssclass;
                }**/

                // TODO

                $success_text = __('Thank you! The form was sent successfully!', 'funnelforms-free');
                if(isset($base_json->success_text) && $base_json->success_text != '') {
                    $success_text = $base_json->success_text;
                }

                $success_image = plugins_url("../res/images/success_standard", __FILE__);
                if(isset($base_json->success_image) && $base_json->success_image != '') {
                    $success_image = $base_json->success_image;
                }


                /** Building Content **/
                $content .= '<div id="af2_form_' . $this->frontend_id . '" class="af2_form_wrapper af2_form-type-'.$type.'"
								data-preload="' . $preload . '" data-size="' . $size . '" data-rtl="'. $rtl_layout .'" data-num="' . $this->frontend_id . '"
                                data-did="' . $dataid . '" data-errormail="' . $errormail . '" data-activatescrolltoanchor="' . $aScrollToAnchor . '" data-showsuccessscreen="'.$aShowSuccessScreen.'"
                                >';
                
                if(isset($base_json->showLoading) && $base_json->showLoading === true) {
                $content .= '<div class="af2_loading_overlay">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:transparent;display:block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
 <g transform="translate(50 50)">  <g transform="translate(-19 -19) scale(0.6)"> <g transform="rotate(22.5612)">
<animateTransform attributeName="transform" type="rotate" values="0;45" keyTimes="0;1" dur="0.2s" begin="0s" repeatCount="indefinite"></animateTransform><path style="fill:'.$loader_color.'" d="M31.359972760794346 21.46047782418268 L38.431040572659825 28.531545636048154 L28.531545636048154 38.431040572659825 L21.46047782418268 31.359972760794346 A38 38 0 0 1 7.0000000000000036 37.3496987939662 L7.0000000000000036 37.3496987939662 L7.000000000000004 47.3496987939662 L-6.999999999999999 47.3496987939662 L-7 37.3496987939662 A38 38 0 0 1 -21.46047782418268 31.35997276079435 L-21.46047782418268 31.35997276079435 L-28.531545636048154 38.431040572659825 L-38.43104057265982 28.531545636048158 L-31.359972760794346 21.460477824182682 A38 38 0 0 1 -37.3496987939662 7.000000000000007 L-37.3496987939662 7.000000000000007 L-47.3496987939662 7.000000000000008 L-47.3496987939662 -6.9999999999999964 L-37.3496987939662 -6.999999999999997 A38 38 0 0 1 -31.35997276079435 -21.460477824182675 L-31.35997276079435 -21.460477824182675 L-38.431040572659825 -28.531545636048147 L-28.53154563604818 -38.4310405726598 L-21.4604778241827 -31.35997276079433 A38 38 0 0 1 -6.999999999999992 -37.3496987939662 L-6.999999999999992 -37.3496987939662 L-6.999999999999994 -47.3496987939662 L6.999999999999977 -47.3496987939662 L6.999999999999979 -37.3496987939662 A38 38 0 0 1 21.460477824182686 -31.359972760794342 L21.460477824182686 -31.359972760794342 L28.531545636048158 -38.43104057265982 L38.4310405726598 -28.53154563604818 L31.35997276079433 -21.4604778241827 A38 38 0 0 1 37.3496987939662 -6.999999999999995 L37.3496987939662 -6.999999999999995 L47.3496987939662 -6.999999999999997 L47.349698793966205 6.999999999999973 L37.349698793966205 6.999999999999976 A38 38 0 0 1 31.359972760794346 21.460477824182686 M0 -23A23 23 0 1 0 0 23 A23 23 0 1 0 0 -23" fill="#070707"></path></g></g> <g transform="translate(19 19) scale(0.6)"> <g transform="rotate(44.9388)">
<animateTransform attributeName="transform" type="rotate" values="45;0" keyTimes="0;1" dur="0.2s" begin="-0.1s" repeatCount="indefinite"></animateTransform><path style="fill:'.$loader_color.'" d="M-31.35997276079435 -21.460477824182675 L-38.431040572659825 -28.531545636048147 L-28.53154563604818 -38.4310405726598 L-21.4604778241827 -31.35997276079433 A38 38 0 0 1 -6.999999999999992 -37.3496987939662 L-6.999999999999992 -37.3496987939662 L-6.999999999999994 -47.3496987939662 L6.999999999999977 -47.3496987939662 L6.999999999999979 -37.3496987939662 A38 38 0 0 1 21.460477824182686 -31.359972760794342 L21.460477824182686 -31.359972760794342 L28.531545636048158 -38.43104057265982 L38.4310405726598 -28.53154563604818 L31.35997276079433 -21.4604778241827 A38 38 0 0 1 37.3496987939662 -6.999999999999995 L37.3496987939662 -6.999999999999995 L47.3496987939662 -6.999999999999997 L47.349698793966205 6.999999999999973 L37.349698793966205 6.999999999999976 A38 38 0 0 1 31.359972760794346 21.460477824182686 L31.359972760794346 21.460477824182686 L38.431040572659825 28.531545636048158 L28.53154563604818 38.4310405726598 L21.460477824182703 31.35997276079433 A38 38 0 0 1 6.9999999999999964 37.3496987939662 L6.9999999999999964 37.3496987939662 L6.999999999999995 47.3496987939662 L-7.000000000000009 47.3496987939662 L-7.000000000000007 37.3496987939662 A38 38 0 0 1 -21.46047782418263 31.359972760794385 L-21.46047782418263 31.359972760794385 L-28.531545636048097 38.43104057265987 L-38.431040572659796 28.531545636048186 L-31.35997276079433 21.460477824182703 A38 38 0 0 1 -37.34969879396619 7.000000000000032 L-37.34969879396619 7.000000000000032 L-47.34969879396619 7.0000000000000355 L-47.3496987939662 -7.000000000000002 L-37.3496987939662 -7.000000000000005 A38 38 0 0 1 -31.359972760794346 -21.46047782418268 M0 -23A23 23 0 1 0 0 23 A23 23 0 1 0 0 -23" fill="#000000"></path></g></g></g>
</svg>
                        </div>';
                        
                    $content .= '<div class="af2_form" style="display: none;">';
                }
                else {
                    $content .= '<div class="af2_form" style="display: block;">';
                }

                $content .= '<div class="af2_success_message_screen desktop" style="display: none;">';
                    $content .= '<div class="af2_success_image">';
                    $content .= '<img src="'.$success_image.'" />';
                    $content .= '</div>';
                    $content .= '<div class="af2_success_text">';
                    $content .=  $success_text;
                    $content .= '</div>';
                $content .= '</div>';
                $content .= '<div class="af2_success_message_screen af2_mobile" style="display: none;">';
                    $content .= '<div class="af2_success_image">';
                    $content .= '<img src="'.$success_image.'" />';
                    $content .= '</div>';
                    $content .= '<div class="af2_success_text">';
                    $content .=  $success_text;
                    $content .= '</div>';
                $content .= '</div>';
                
                $content .= '<div class="af2_form_heading_wrapper">';
                $content .= '<div class="af2_form_heading desktop">' . $fe_title . '</div>';
                $content .= '<div class="af2_form_heading af2_mobile">' . $fe_title . '</div>';
                $content .= '</div>';
                $content .= '<div class="af2_form_carousel">';
                $content .= '</div>';
                $content .= '<div class="af2_form_bottombar">';
                $content .= '<button class="af2_form_back_button af2_form_button af2_disabled af2_mobile '.$back_btn_style_class.'">'.$back_btn_text.'</button>';
                $content .= '<button class="af2_form_back_button af2_form_button af2_disabled desktop '.$back_btn_style_class.'">'.$back_btn_text.'</button>';
                $content .= '<div class="af2_form_progress_bar"><div class="af2_form_progress"></div></div>';
                $content .= '<button class="af2_form_foward_button af2_form_button af2_disabled af2_mobile '.$forward_btn_style_class.'">'.$forward_btn_text.'</button>';
                $content .= '<button class="af2_form_foward_button af2_form_button af2_disabled desktop '.$forward_btn_style_class.'">'.$forward_btn_text.'</button>';
                $content .= '</div>';
                $content .= '</div>';
                
                
                $content .= '</div>';

                
            }

        /** RETURNING * */
        $this->frontend_id++;
        return $content;
    }

    function fnsf_get_data_loadup_first($request_preload_ids) {
        $dataids = $request_preload_ids;

        $returnarray = array("error" => null, "data" => array());

        /** Checking for bad input and fetching posts * */
        $base_posts = array();                       // The post of it out of DB
        $x = 0;                           // Loop
        foreach ($dataids as $dataid) {
            if (fnsf_sql_check_it($dataid) === 'ERROR') {
                $returnarray["error"] = 'ERRORX';
                return $returnarray;
            }

            $base_posts[$x] = $this->fnsf_af2_check_params_for_getting_content($dataid);

            /** Checking that no Errors are given * */
            if ($base_posts[$x] === 'ERROR') {
                $returnarray["error"] = __('ERROR - [02] Please contact support!', 'af2_multilanguage');
                return $returnarray;
            }

            $x++;
        }

        /** Fetching content out of Database * */
        $base_structures = array();                      // The json Strings
        $base_jsons = array();                       // The json-Objects of it
        $post_types = array();                       // The Types of the post
        $x = 0;                           // Loop
        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
        foreach ($base_posts as $base_post) {
            $base_jsons[$x] = fnsf_af2_get_post_content($base_post);


            $base_jsons[$x] = json_decode(json_encode($base_jsons[$x]));

            /** Checking that no Error is given * */
            $check = $this->fnsf_af2_check_for_errors($base_jsons[$x]);
            if ($check === 'ERROR') {
                $returnarray["error"] = __('ERROR - [03] There is an error in a form element!', 'af2_multilanguage');;
                return $returnarray;
            }

            $post_types[$x] = get_post_field('post_type', $base_post);

            $x++;
        }

        /** Cleaning the jsons for the frontend * */
        $new_jsons = json_decode('{}');                    // The clean Jsons
        $x = 0;                           // Loop
        foreach ($post_types as $post_type) {
            $data = strval($dataids[$x]);                   // Actual called Dataid
            switch ($post_type) {
                case 'af2_frage': {
                        $new_jsons->$data = $this->fnsf_af2_clean_frage_json($base_jsons[$x]);
                        break;
                    }
                case 'af2_kontaktformular': {
                        $new_jsons->$data = $this->fnsf_af2_clean_kontaktformular_json($base_jsons[$x]);
                        break;
                    }
                case 'af2_formular': {
                        $new_jsons->$data = $this->fnsf_af2_clean_formular_json($base_jsons[$x]);
                        break;
                    }
            }

            $x++;
        }

        $jsons = json_encode($new_jsons, JSON_UNESCAPED_UNICODE);              // Json to return

        $returnarray["data"] = $jsons;
        return $returnarray;
    }


    /**
     * Getting all data for the frontend to work with
     */
    function fnsf__af2_get_data() {
        // $af2_get_ids = sanitize_text_field();
        /** Check that all attributes are sent * */
        if (!isset($_GET['ids'])) {
                _e('ERRORX');
            die();
        }

        /** Get all attributes * */
        $dataids = rest_sanitize_array($_GET['ids']);                      // The Array of Dataids

        /** Checking for bad input and fetching posts * */
        $base_posts = array();                       // The post of it out of DB
        $x = 0;                           // Loop
        foreach ($dataids as $dataid) {
            if (fnsf_sql_check_it($dataid) === 'ERROR') {
                 _e('ERRORX');
                die();
            }

            $base_posts[$x] = $this->fnsf_af2_check_params_for_getting_content($dataid);

            /** Checking that no Errors are given * */
            if ($base_posts[$x] === 'ERROR') {
                 _e('ERROR - [02] Please contact support!', 'funnelforms-free');
                die();
            }

            $x++;
        }

        /** Fetching content out of Database * */
        $base_structures = array();                      // The json Strings
        $base_jsons = array();                       // The json-Objects of it
        $post_types = array();                       // The Types of the post
        $x = 0;                           // Loop
        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
        foreach ($base_posts as $base_post) {
            $base_jsons[$x] = fnsf_af2_get_post_content($base_post);


            $base_jsons[$x] = json_decode(json_encode($base_jsons[$x]));

            /** Checking that no Error is given * */
            $check = $this->fnsf_af2_check_for_errors($base_jsons[$x]);
            if ($check === 'ERROR') {
                _e('ERROR - [03] There is an error in a form element!', 'funnelforms-free');
                die();
            }

            $post_types[$x] = get_post_field('post_type', $base_post);

            $x++;
        }

        /** Cleaning the jsons for the frontend * */
        $new_jsons = json_decode('{}');                    // The clean Jsons
        $x = 0;                           // Loop
        foreach ($post_types as $post_type) {
            $data = strval($dataids[$x]);                   // Actual called Dataid
            switch ($post_type) {
                case 'af2_frage': {
                        $new_jsons->$data = $this->fnsf_af2_clean_frage_json($base_jsons[$x]);
                        break;
                    }
                case 'af2_kontaktformular': {
                        $new_jsons->$data = $this->fnsf_af2_clean_kontaktformular_json($base_jsons[$x]);
                        break;
                    }
                case 'af2_formular': {
                        $new_jsons->$data = $this->fnsf_af2_clean_formular_json($base_jsons[$x]);
                        break;
                    }
            }

            $x++;
        }

        $jsons = json_encode($new_jsons, JSON_UNESCAPED_UNICODE);              // Json to return

        _e($jsons);
        wp_die();
    }

    /**
     * Making the json usable for the frontend
     *
     * @param $base_json
     * @return string
     */
    function fnsf_af2_clean_frage_json($base_json) {
        $new_json = json_decode('{}');

        /** Processing... * */
        $new_json->frontend_name = $base_json->name;
        $new_json->frontend_description = $base_json->description == null ? '' : $base_json->description;
        $new_json->typ = $base_json->typ;
        $new_json->type_specifics = $this->fnsf_af2_process_type_specifics($base_json);
        $new_json->af2_type = 'frage';
        $new_json->tracking = $base_json->tracking_code;

        return $new_json;
    }

    /**
     * Making the json usable for the frontend
     *
     * @param $base_json
     * @return string
     */
    function fnsf_af2_clean_kontaktformular_json($base_json) {
        $new_json = json_decode('{}');

        /** Processing... * */
        $new_json->frontend_name = $base_json->cftitle;
        $new_json->frontend_description = $base_json->description == null ? '' : $base_json->description;
        $new_json->questions = $base_json->questions;
        $new_json->sendButtonLabel = $base_json->send_button;
        $new_json->af2_type = 'kontaktformular';
        $new_json->show_bottombar = $base_json->show_bottombar;
        $new_json->tracking_code = $base_json->tracking_code;

        return $new_json;
    }

    /**
     * Making the json usable for the frontend
     *
     * @param $base_json
     * @return string
     */
    function fnsf_af2_clean_formular_json($base_json) {
        $new_json = json_decode('{}');

        /** Processing... * */
        $new_json->sections = $this->fnsf_af2_process_connections($base_json->sections);/** TODO ERRORS * */
        $new_json->styling = $this->fnsf_af2_process_styling($base_json->styling);/** TODO ERRORS * */
        $new_json->af2_type = 'formular';

        return $new_json;
    }

    /*
     * INCOMING -> DELEDEABLE Part of the Method, WHEN ITS DONE IN BACKEND PERFECTLY
     */

    /**
     * Building the dataids of following into the connections
     *
     * @param $base_array
     * @return mixed
     */
    function fnsf_af2_process_connections($base_array) {
        $new_json = json_decode('{}');
        $new_json->sections = $base_array;                    // Puffer

        /** Iterating all Sections * */
        for ($x = sizeof($new_json->sections) - 1; $x >= 0; $x--) {
            $section = $new_json->sections[$x];                  // ForEach object

            /** Iterating all Contents * */
            for ($y = sizeof($section->contents) - 1; $y >= 0; $y--) {
                $content = $section->contents[$y];                 // ForEach object

                /** Check if content should be deleted because its an interface * */
                if ($this->fnsf_af2_check_data_type($content->data) === 'interface') {
                    /** SAFE THE OTHER ONES * */
                    /** Iterating all Contents * */
                    for ($z = sizeof($section->contents) - 1; $z >= 0; $z--) {
                        if ($z > $y) {
                            if ($this->fnsf_af2_check_data_type($base_array[$x]->contents[$z]) === 'redirect') {
                                foreach ($base_array[$x]->contents[$z]->incoming_connections as $inc) {
                                    $from_section = $inc->from_section;
                                    $from_content = $inc->from_content;

                                    $a = 0;
                                    foreach ($base_array[$from_section]->contents[$from_content]->connections as $con) {
                                        if ($con->to_section == $x && $con->to_content == $z) {
                                            $new_json->sections[$from_section]->contents[$from_content]->connections[$a]->to_content = $base_array[$from_section]->contents[$from_content]->connections[$a]->to_content - 1;
                                        }
                                        $a++;
                                    }
                                }
                            }
                        }
                    }

                    array_splice($new_json->sections[$x]->contents, $y, 1);
                    continue;
                }

                /** Iterating all Connections * */
                if(isset($content->connections)) {
                    for ($z = sizeof($content->connections) - 1; $z >= 0; $z--) {
                        $connection = $content->connections[$z];               // ForEach object

                        $to_section = $connection->to_section;                // The Section to go on
                        $to_content = $connection->to_content;                // The Content to go on
                        $to_dataid = $base_array[$to_section]->contents[$to_content]->data;        // The Dataid to go on

                        /** Check that dataid is an interface * */
                        if ($this->fnsf_af2_check_data_type($to_dataid) === 'undefined') {
                            array_splice($new_json->sections[$x]->contents[$y]->connections, $z, 1);
                        } else {
                            /** Correcting everything into numbers! * */
                            /*
                            * INCOMING -> DELETEABE, when its perfectly done in the backend!
                            */
                            $new_json->sections[$x]->contents[$y]->connections[$z]->from = intval($connection->from);
                            $new_json->sections[$x]->contents[$y]->connections[$z]->to_section = intval($connection->to_section);
                            $new_json->sections[$x]->contents[$y]->connections[$z]->to_content = intval($connection->to_content);

                            /** Adding the Dataid into the connections * */
                            $new_json->sections[$x]->contents[$y]->connections[$z]->to_dataid = $to_dataid;
                        }
                    } // ENDLOOP /** Iterating all Connections **/
                }
                /** Iterating all incoming connections * */
                if(isset($content->incoming_connections)) {
                    for ($z = sizeof($content->incoming_connections) - 1; $z >= 0; $z--) {
                        $incoming_connection = $content->incoming_connections[$z];          // ForEach object
                        /** Correcting everything into numbers! * */
                        /*
                        * INCOMING -> DELETEABE, when its perfectly done in the backend!
                        */
                        $new_json->sections[$x]->contents[$y]->incoming_connections[$z]->from_section = intval($incoming_connection->from_section);
                        $new_json->sections[$x]->contents[$y]->incoming_connections[$z]->from_content = intval($incoming_connection->from_content);
                    } // ENDLOOP /** Iterating all Connections **/
                }
            } // ENDLOOP /** Iterating all Contents **/
        } // ENDLOOP /** Iterating all Sections **/

        return $new_json->sections;
    }

    /**
     * Process the specifics for every type
     *
     * @param $base_json
     * @return string
     */
    function fnsf_af2_process_type_specifics($base_json) {
        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
        $new_json = json_decode('{}');

        $type = $base_json->typ;                      // Typ of the actual Frage

        switch ($type) {
            case 'af2_select': {
                    $new_json->answers = $this->fnsf_af2_process_answers($base_json->answers);
                    $new_json->desktop_layout = $base_json->desktop_layout;
                    $new_json->mobile_layout = $base_json->mobile_layout;
                    $new_json->hide_icons = isset($base_json->hide_icons) ? $base_json->hide_icons : false;
                    break;
                }
            case 'af2_multiselect': {
                    $new_json->answers = $this->fnsf_af2_process_answers($base_json->answers);
                    $new_json->condition = $base_json->condition;
                    $new_json->desktop_layout = $base_json->desktop_layout;
                    $new_json->mobile_layout = $base_json->mobile_layout;
                    $new_json->hide_icons = isset($base_json->hide_icons) ? $base_json->hide_icons : false;
                    break;
                }
            case 'af2_textfeld': {
                    $new_json->placeholder = $base_json->textfeld;
                    $new_json->mandatory = $base_json->textfield_mandatory;
                    if($base_json->min_length != null) $new_json->min_length = $base_json->min_length;
                    if($base_json->max_length != null) $new_json->max_length = $base_json->max_length;

                    if($base_json->text_only_text != null) $new_json->text_only_text = $base_json->text_only_text;
                    if($base_json->text_only_numbers != null) $new_json->text_only_numbers = $base_json->text_only_numbers;
                    if($base_json->text_birthday != null) $new_json->text_birthday = $base_json->text_birthday;
                    if($base_json->text_only_numbers != null) $new_json->text_only_numbers = $base_json->text_only_numbers;
                    //if($base_json->text_plz != null) $new_json->text_plz = $base_json->text_plz;
                    break;
                }
            case 'af2_textbereich': {
                    $new_json->placeholder = $base_json->textarea;
                    $new_json->mandatory = $base_json->textarea_mandatory;
                    
                    if($base_json->min_length != null) $new_json->min_length = $base_json->min_length;
                    if($base_json->max_length != null) $new_json->max_length = $base_json->max_length;

                    if($base_json->text_only_text != null) $new_json->text_only_text = $base_json->text_only_text;
                    if($base_json->text_only_numbers != null) $new_json->text_only_numbers = $base_json->text_only_numbers;
                    if($base_json->text_birthday != null) $new_json->text_birthday = $base_json->text_birthday;
                    //if($base_json->text_plz != null) $new_json->text_plz = $base_json->text_plz;

                    break;
                }
        }

        return $new_json;
    }

    /**
     * Processing the answers, that all types in there are corredt
     *
     * @param $answers
     * @return array
     */
    function fnsf_af2_process_answers($answers) {
        $new_array = array();

        foreach ($answers as $answer) {
            $new_answer = json_decode('{}');                   // The new answer object
            $new_answer->text = $answer->text;                   // Text of the answer
            $new_answer->icon = $answer->img;                   // Actual Icon
            $new_answer->icon_type = '';                    // Type of the icon
            if (strpos($new_answer->icon, 'http') !== false) {
                $new_answer->icon_type = 'url';
            } else {
                $new_answer->icon_type = 'font-awesome';
            }

            array_push($new_array, $new_answer);
        }

        return $new_array;
    }

    /*
     * INCOMING -> DELEt´TEABLE METHOD, WHEN ITS DONE IN BACKEND PERFECTLY
     */

    /**
     * Building the right styling for the frontend to use
     *
     * @param $base_json
     * @return string
     */
    function fnsf_af2_process_styling($base_json) {
        $new_json = json_decode('{}');


        if(isset($base_json->global_font) && strpos($base_json->global_font, '.') !== false) {
            $base_json->global_font = str_replace('.ttf', '', $base_json->global_font);
        }
        
        /** Get all stylings * */
    
        /** COLORS * */
        $form_heading_color = json_decode('{"attribute": "color","value":"' . $base_json->form_heading_color . '"}');
        
        $form_question_heading_color = json_decode('{"attribute": "color","value":"' . $base_json->form_question_heading_color . '"}');
        
        $form_question_description_color = json_decode('{"attribute": "color","value":"' . $base_json->form_question_description_color . '"}');
        
        $form_answer_card_text_color = json_decode('{"attribute": "color","value":"' . $base_json->form_answer_card_text_color . '"}');
        
        $form_answer_card_icon_color = json_decode('{"attribute": "color","value":"' . $base_json->form_answer_card_icon_color . '"}');
        
        $form_background_color = json_decode('{"attribute": "background-color","value":"' . $base_json->form_background_color . '"}');
        $form_font = json_decode('{"attribute": "font-family","value":"' . $base_json->global_font . '"}');
        $form_fontfc = json_decode('{"attribute": "font-family","value":"' . $base_json->global_font . '", "special_class":"form_font"}');
        
        $form_answer_card_background_color = json_decode('{"attribute": "background-color","value":"' . $base_json->form_answer_card_background_color . '"}');
        
        $form_button_background_color = json_decode('{"attribute": "background-color","value":"' . $base_json->form_button_background_color . '"}');
        
        $form_button_disabled_background_color = json_decode('{"attribute":"background-color","value": "' . $base_json->form_button_disabled_background_color . '","special_class": "af2_disabled"}');
        
        $form_button_label_text_desktop = json_decode('{"attribute":"font-size","value": "' . $base_json->form_button_label_size_desktop . 'px","special_class": "desktop"}');
     
        $form_button_label_text_mobile = json_decode('{"attribute":"font-size","value": "' . $base_json->form_button_label_size_mobile . 'px","special_class": "af2_mobile"}');

        $iconsizedesktop_grid = $base_json->icon_size_desktop_grid == null ? 90 : $base_json->icon_size_desktop_grid;
        $iconsizedesktop_list_1 = $base_json->icon_size_desktop_list_1 == null ? 70 : $base_json->icon_size_desktop_list_1;
        $iconsizedesktop_list_2 = $base_json->icon_size_desktop_list_2 == null ? 60 : $base_json->icon_size_desktop_list_2;
        $iconsizemobile_grid = $base_json->icon_size_mobile_grid == null ? 25 : $base_json->icon_size_mobile_grid;
        $iconsizemobile_list = $base_json->icon_size_mobile_list == null ? 25 : $base_json->icon_size_mobile_list;
        $icon_size_desktop_grid = json_decode('{"attribute":"font-size","value": "' . $iconsizedesktop_grid . 'px","special_class": "desktop i"}');
        $icon_size_desktop_list_1 = json_decode('{"attribute":"font-size","value": "' . $iconsizedesktop_list_1 . 'px !important","special_class": "af2_answer_container .af2_answer.desktop .af2_answer_image_wrapper i"}');
        $icon_size_desktop_list_2 = json_decode('{"attribute":"font-size","value": "' . $iconsizedesktop_list_2 . 'px !important","special_class": "af2_answer_container .af2_answer.desktop .af2_answer_image_wrapper i"}');
        $icon_size_mobile_grid = json_decode('{"attribute":"font-size","value": "' . $iconsizemobile_grid . 'px !important","special_class": "af2_answer_container .af2_answer.af2_mobile .af2_answer_image_wrapper.af2_mobile i"}');
        $icon_size_mobile_list = json_decode('{"attribute":"font-size","value": "' . $iconsizemobile_list . 'px","special_class": "af2_mobile i"}');

    
        $answer_card_box_shadow_value = isset($base_json->form_box_shadow_color_answer_card) ? $base_json->form_box_shadow_color_answer_card : 'rgba(225,225,225,1)';
        $form_box_shadow_color_answer_card = json_decode('{"attribute": "box-shadow","value":" 5px 5px 15px 0 ' . $answer_card_box_shadow_value . '"}');

        $answer_card_box_shadow_value_unfocus = isset($base_json->form_box_shadow_color_unfocus) ? $base_json->form_box_shadow_color_unfocus : 'rgba(225,225,225,1)';
        
        $form_box_shadow_color_unfocus = json_decode('{"attribute": "box-shadow","value":" 0px 0px 10px 0 ' . $answer_card_box_shadow_value_unfocus . '"}');
        $form_box_shadow_color = json_decode('{"attribute": "box-shadow","value":" 0px 0px 10px 0 ' . $base_json->form_box_shadow_color . '", "special_state": "focus"}');
        $form_box_shadow_colorfc = json_decode('{"attribute": "box-shadow","value":" 0px 0px 10px 0 ' . $base_json->form_box_shadow_color . '", "special_state": "focus", "special_class":"form_class"}');
        
        $form_progress_bar_color = json_decode('{"attribute": "background-color","value":"' . $base_json->form_progress_bar_color . '"}');
        
        $form_progress_bar_unfilled_background_color = json_decode('{"attribute": "background-color","value":"' . $base_json->form_progress_bar_unfilled_background_color . '"}');
        
        $form_border_color = json_decode('{"attribute": "border","value":"1px solid ' . $base_json->form_border_color . '","special_state":"focus"}');
        $form_border_colorfc = json_decode('{"attribute": "border","value":"1px solid ' . $base_json->form_border_color . '","special_state":"focus", "special_class":"form_class"}');
        
        $form_slider_frage_bullet_color = json_decode('{"attribute": "color","value":"' . $base_json->form_slider_frage_bullet_color . '"}');
        
        $form_slider_frage_thumb_background_color = json_decode('{"attribute": "background-color","value":"' . $base_json->form_slider_frage_thumb_background_color . ' !important","special_extra":"-moz-range-thumb"}');
        
        $form_slider_frage_thumb_background_color2 = json_decode('{"attribute": "background-color","value":"' . $base_json->form_slider_frage_thumb_background_color . ' !important","special_extra":"-webkit-slider-thumb"}');
        $form_slider_frage_background_color = json_decode('{"attribute": "background-color","value":"' . $base_json->form_slider_frage_background_color . '"}');
        
        $form_input_background_color = json_decode('{"attribute": "background-color","value":"' . $base_json->form_input_background_color . '"}');
        
         $form_loader_color = json_decode('{"attribute": "color","value":"' . $base_json->form_loader_color . '"}');

        /** TEXT THINGS * */
        //form heading
        $form_heading_size_desktop = json_decode('{"attribute": "font-size",
								 						 	"value":"' . $base_json->form_heading_size_desktop . 'px",
								 						 	"special_class":"desktop"}');
        $form_heading_size_mobile = json_decode('{"attribute": "font-size",
								 						 	"value":"' . $base_json->form_heading_size_mobile . 'px",
								 						 	"special_class":"af2_mobile"}');
        $form_heading_weight = json_decode('{"attribute": "font-weight",
								 						 	"value":"' . $base_json->form_heading_weight . '"}');
        $form_heading_line_height_desktop = json_decode('{"attribute": "line-height",
								 						 	"value":"' . $base_json->form_heading_line_height_desktop . 'px",
								 						 	"special_class":"desktop"}');
        $form_heading_line_height_mobile = json_decode('{"attribute": "line-height",
								 						 	"value":"' . $base_json->form_heading_line_height_mobile . 'px",
								 						 	"special_class":"af2_mobile"}');

        //question heading
        $form_question_heading_size_desktop = json_decode('{"attribute": "font-size",
								 						 	"value":"' . $base_json->form_question_heading_size_desktop . 'px",
								 						 	"special_class":"desktop"}');
        $form_question_heading_size_mobile = json_decode('{"attribute": "font-size",
								 						 	"value":"' . $base_json->form_question_heading_size_mobile . 'px",
								 						 	"special_class":"af2_mobile"}');
        $form_question_heading_weight = json_decode('{"attribute": "font-weight",
								 						 	"value":"' . $base_json->form_question_heading_weight . '"}');
        $form_question_heading_line_height_desktop = json_decode('{"attribute": "line-height",
								 						 	"value":"' . $base_json->form_question_heading_line_height_desktop . 'px",
								 						 	"special_class":"desktop"}');
        $form_question_heading_line_height_mobile = json_decode('{"attribute": "line-height",
								 						 	"value":"' . $base_json->form_question_heading_line_height_mobile . 'px",
															  "special_class":"af2_mobile"}');

        //question description
        $form_question_description_size_desktop = json_decode('{"attribute": "font-size",
															"value":"' . $base_json->form_question_description_size_desktop . 'px",
															"special_class":"desktop"}');
        $form_question_description_size_mobile = json_decode('{"attribute": "font-size",
															"value":"' . $base_json->form_question_description_size_mobile . 'px",
															"special_class":"af2_mobile"}');
        $form_question_description_weight = json_decode('{"attribute": "font-weight",
															"value":"' . $base_json->form_question_description_weight . '"}');
        $form_question_description_line_height_desktop = json_decode('{"attribute": "line-height",
															"value":"' . $base_json->form_question_description_line_height_desktop . 'px",
															"special_class":"desktop"}');
        $form_question_description_line_height_mobile = json_decode('{"attribute": "line-height",
															"value":"' . $base_json->form_question_description_line_height_mobile . 'px",
															"special_class":"af2_mobile"}');

        //answers
        $form_answer_card_text_size_desktop = json_decode('{"attribute": "font-size",
								 						 	"value":"' . $base_json->form_answer_card_text_size_desktop . 'px",
								 						 	"special_class":"desktop"}');
        $form_answer_card_text_size_desktop_ = json_decode('{"attribute": "font-size",
								 						 	"value":"' . $base_json->form_answer_card_text_size_desktop . 'px"}');
        $form_answer_card_text_size_desktop_fc = json_decode('{"attribute": "font-size",
								 						 	"value":"' . $base_json->form_answer_card_text_size_desktop . 'px", "special_class":"form_class"}');
        $form_answer_card_text_size_mobile = json_decode('{"attribute": "font-size",
								 						 	"value":"' . $base_json->form_answer_card_text_size_mobile . 'px",
								 						 	"special_class":"af2_mobile"}');
        $form_answer_card_text_size_mobile_ = json_decode('{"attribute": "font-size",
								 						 	"value":"' . $base_json->form_answer_card_text_size_mobile . 'px"}');
        $form_answer_card_text_size_mobile_fc = json_decode('{"attribute": "font-size",
								 						 	"value":"' . $base_json->form_answer_card_text_size_mobile . 'px", "special_class":"form_class"}');
        $form_answer_card_text_weight = json_decode('{"attribute": "font-weight",
								 						 	"value":"' . $base_json->form_answer_card_text_weight . '"}');
        $form_answer_card_text_weightfc = json_decode('{"attribute": "font-weight",
								 						 	"value":"' . $base_json->form_answer_card_text_weight . '", "special_class":"form_class"}');
        $form_answer_card_text_line_height_desktop = json_decode('{"attribute": "line-height",
								 						 	"value":"' . $base_json->form_answer_card_text_line_height_desktop . 'px",
								 						 	"special_class":"desktop"}');
        $form_answer_card_text_line_height_mobile = json_decode('{"attribute": "line-height",
								 						 	"value":"' . $base_json->form_answer_card_text_line_height_mobile . 'px",
								 						 	"special_class":"af2_mobile"}');

        //input text sizes
        $form_text_input_size_desktop = json_decode('{"attribute": "font-size",
								 						 	"value":"' . $base_json->form_text_input_size_desktop . 'px",
								 						 	"special_class":"desktop"}');
        $form_text_input_size_mobile = json_decode('{"attribute": "font-size",
								 						 	"value":"' . $base_json->form_text_input_size_mobile . 'px",
								 						 	"special_class":"af2_mobile"}');
        $form_text_input_weight = json_decode('{"attribute": "font-weight",
								 						 	"value":"' . $base_json->form_text_input_text_weight . '"}');
        $form_text_input_line_height_desktop = json_decode('{"attribute": "line-height",
								 						 	"value":"' . $base_json->form_text_input_line_height_desktop . 'px",
								 						 	"special_class":"desktop"}');
        $form_text_input_line_height_mobile = json_decode('{"attribute": "line-height",
								 						 	"value":"' . $base_json->form_text_input_line_height_mobile . 'px",
								 						 	"special_class":"af2_mobile"}');

        /** BORDER RADIUS * */
        $form_answer_card_border_radius = json_decode('{"attribute": "border-radius",
								 						 	"value":"' . $base_json->form_answer_card_border_radius . 'px"}');
        $form_text_input_border_radius = json_decode('{"attribute": "border-radius",
								 						 	"value":"' . $base_json->form_text_input_border_radius . 'px"}');
        $form_text_input_border_radiusfc = json_decode('{"attribute": "border-radius",
								 						 	"value":"' . $base_json->form_text_input_border_radius . 'px", "special_class":"form_class"}');
        $form_text_input_border_radius_ = json_decode('{"attribute": "border-radius",
								 						 	"value":"' . $base_json->form_text_input_border_radius . 'px 0 0 ' . $base_json->form_text_input_border_radius . 'px"}');
        $form_text_input_border_radiuslr = json_decode('{"attribute": "border-radius",
                                                        "value":"0 ' . $base_json->form_text_input_border_radius . 'px ' . $base_json->form_text_input_border_radius . 'px 0"}');

        /** CONTACT FORM * */
        $form_contact_form_label_size = json_decode('{"attribute": "font-size",
															"value":"' . $base_json->form_contact_form_label_size . 'px"}');
        $form_contact_form_label_size_ = json_decode('{"attribute": "font-size",
															"value":"' . $base_json->form_contact_form_label_size . 'px", "special_class": "desktop"}');
        $form_contact_form_label_weight = json_decode('{"attribute": "font-weight",
															"value":"' . $base_json->form_contact_form_label_weight . '"}');
        $form_contact_form_input_size = json_decode('{"attribute": "font-size",
																	"value":"' . $base_json->form_contact_form_input_size . 'px"}');
        $form_contact_form_input_weight = json_decode('{"attribute": "font-weight",
																	"value":"' . $base_json->form_contact_form_input_weight . '"}');
        $form_contact_form_button_size = json_decode('{"attribute": "font-size",
																	"value":"' . $base_json->form_contact_form_button_size . 'px"}');
        $form_contact_form_button_weight = json_decode('{"attribute": "font-weight",
																	"value":"' . $base_json->form_contact_form_button_weight . '"}');
        $form_contact_form_button_padding_top_bottom = json_decode('{"attribute": "padding",
																	"value":"' . $base_json->form_contact_form_button_padding_top_bottom . 'px 0"}');
        
        $form_contact_form_button_padding_left = json_decode('{"attribute": "padding-left","value":"' . $base_json->form_contact_form_button_padding_left_right . 'px"}');
        $form_contact_form_button_padding_right = json_decode('{"attribute": "padding-right","value":"' . $base_json->form_contact_form_button_padding_left_right . 'px"}');
        
        $form_contact_form_cb_size = json_decode('{"attribute": "font-size",
																	"value":"' . $base_json->form_contact_form_cb_size . 'px"}');
        $form_contact_form_cb_weight = json_decode('{"attribute": "font-weight",
																	"value":"' . $base_json->form_contact_form_cb_weight . '"}');
        $form_contact_form_input_height = json_decode('{"attribute": "height",
																	"value":"' . $base_json->form_contact_form_input_height . 'px"}');
        $form_contact_form_input_border_radius = json_decode('{"attribute": "border-radius",
																	"value":"0 ' . $base_json->form_contact_form_input_border_radius . 'px '.$base_json->form_contact_form_input_border_radius.'px 0"}');
        $form_contact_form_input_border_radiuslr = json_decode('{"attribute": "border-radius",
																	"value":"' . $base_json->form_contact_form_input_border_radius . 'px 0 0 '.$base_json->form_contact_form_input_border_radius.'px"}');
        $form_contact_form_input_border_radius_ = json_decode('{"attribute": "border-radius",
																	"value":"' . $base_json->form_contact_form_input_border_radius . 'px"}');
        $form_contact_form_button_border_radius = json_decode('{"attribute": "border-radius",
																	"value":"' . $base_json->form_contact_form_button_border_radius . 'px"}');
        $form_contact_form_button_background_color = json_decode('{"attribute": "background-color",
																	"value":"' . $base_json->form_contact_form_button_background_color . '"}');
        $form_contact_form_button_background_color_ = json_decode('{"attribute": "color",
																	"value":"' . $base_json->form_contact_form_button_background_color . ' !important"}');
        $form_contact_form_button_background_color_fc = json_decode('{"attribute": "background-color",
																	"value":"' . $base_json->form_contact_form_button_background_color . ' !important", "special_class": "form_class"}');
        $form_contact_form_button_color = json_decode('{"attribute": "color",
																		"value":"' . $base_json->form_contact_form_button_color . '"}');
        
        $rgb = explode(',', explode('(', $base_json->form_contact_form_button_background_color)[1]);
        $form_contact_form_button_background_color_rgb = json_decode('{"attribute": "--rgb",
            "value":"' . $rgb[0].', '.$rgb[1].', '.$rgb[2] . '"}');

            $rgb = explode(',', explode('(', $base_json->form_contact_form_button_color)[1]);
            $form_contact_form_button_color_rgb = json_decode('{"attribute": "--rgbcol",
                "value":"' . $rgb[0].', '.$rgb[1].', '.$rgb[2] . '"}');




        $form_contact_form_input_padding_left_right = json_decode('{"attribute": "padding",
																		"value":"0 ' . $base_json->form_contact_form_input_padding_left_right . 'px"}');
        
        $form_contact_form_font_color = json_decode('{"attribute": "color","value":"' . $base_json->form_contact_form_font_color . '"}');
        $new_json->af2_answer_card = array($form_answer_card_icon_color, $form_answer_card_background_color,
        $form_answer_card_border_radius, $form_box_shadow_color_answer_card, $icon_size_desktop_grid, $icon_size_mobile_list);
        $new_json->af2_desktop_list = array($icon_size_desktop_list_1);
        $new_json->af2_desktop_list2 = array($icon_size_desktop_list_2);
        $new_json->af2_mobile_grid = array($icon_size_mobile_grid);
    $new_json->af2_slider_image_icon_wrapper = array($form_answer_card_icon_color);
    $new_json->af2_form_heading = array($form_heading_color,
        $form_heading_size_desktop, $form_heading_size_mobile, $form_heading_weight, $form_heading_line_height_desktop, $form_heading_line_height_mobile);
    $new_json->af2_question_heading = array($form_question_heading_color,
        $form_question_heading_size_desktop, $form_question_heading_size_mobile, $form_question_heading_weight, $form_question_heading_line_height_desktop, $form_question_heading_line_height_mobile);
    $new_json->af2_question_description = array($form_question_description_color,
        $form_question_description_size_desktop, $form_question_description_size_mobile, $form_question_description_weight, $form_question_description_line_height_desktop, $form_question_description_line_height_mobile);
    $new_json->af2_answer_text = array($form_answer_card_text_color,
        $form_answer_card_text_size_desktop, $form_answer_card_text_size_mobile, $form_answer_card_text_weight, $form_answer_card_text_line_height_desktop, $form_answer_card_text_line_height_mobile);
    $new_json->af2_form = array($form_answer_card_border_radius, $form_background_color,$form_font, $form_answer_card_border_radius);
    $new_json->af2_form_button = array($form_font, $form_answer_card_border_radius, $form_button_background_color, $form_button_disabled_background_color,$form_button_label_text_desktop,$form_button_label_text_mobile);
    $new_json->af2_form_progress = array($form_progress_bar_color);
    $new_json->af2_form_progress_bar = array($form_progress_bar_unfilled_background_color);
    $new_json->af2_textfeld_frage = array($form_box_shadow_color, $form_input_background_color,
        $form_text_input_size_desktop, $form_border_color, $form_text_input_size_mobile, $form_text_input_weight, $form_text_input_line_height_desktop, $form_text_input_line_height_mobile,
        $form_text_input_border_radius, $form_box_shadow_color_unfocus);
    $new_json->af2_textbereich_frage = array($form_box_shadow_color, $form_input_background_color,
        $form_text_input_size_desktop, $form_text_input_size_mobile, $form_text_input_weight, $form_text_input_line_height_desktop, $form_text_input_line_height_mobile,
        $form_text_input_border_radius, $form_border_color, $form_box_shadow_color_unfocus);
    
    $new_json->af2_datum_frage = array($form_box_shadow_color, $form_input_background_color,
        $form_text_input_size_desktop, $form_text_input_size_mobile, $form_text_input_weight, $form_text_input_line_height_desktop, $form_text_input_line_height_mobile,
        $form_text_input_border_radius, $form_border_color, $form_box_shadow_color_unfocus);
    
    // datepicker styling
    $datepicker_header_backgroud = json_decode('{"attribute": "background-color","value":"' . $base_json->form_datepicker_background_color . '", "special_class":"af2_datepicker","sub_class":"ui-datepicker-title"}');
    $datepicker_header_color = json_decode('{"attribute": "color","value":"' . $base_json->form_datepicker_color . '", "special_class":"af2_datepicker","sub_class":"ui-datepicker-title"}');
    $new_json->af2_datepicker_header = array($datepicker_header_backgroud,$datepicker_header_color);
    
    $new_json->{'ui-datepicker-title'} = array($form_font);
    $new_json->{'desktop .ui-datepicker-title'} = array($form_answer_card_text_size_desktop_);
    $new_json->{'af2_mobile .ui-datepicker-title'} = array($form_answer_card_text_size_mobile_);
    
    $datepicker_active_backgroud = json_decode('{"attribute": "background-color","value":"' . $base_json->form_datepicker_background_color . ' !important", "special_class":"af2_datepicker","sub_class":"ui-datepicker-current-day"}');
    $datepicker_active_color = json_decode('{"attribute": "color","value":"' . $base_json->form_datepicker_color . ' !important", "special_class":"af2_datepicker","sub_class":"ui-state-active"}');
    $new_json->af2_datepicker_active = array($datepicker_active_backgroud,$datepicker_active_color);
    
    $new_json->af2_question_wrapper = array($form_contact_form_font_color);
    $new_json->af2_text_type = array($form_contact_form_input_padding_left_right, $form_box_shadow_color, $form_border_color, $form_input_background_color, $form_contact_form_input_size, $form_contact_form_input_weight, $form_contact_form_input_height, $form_contact_form_input_border_radius,$form_contact_form_font_color, $form_box_shadow_color_unfocus);
    $new_json->{'af2_text_type.af2_rtl_layout'} = array($form_contact_form_input_border_radiuslr);
    $new_json->af2_text_type_ = array($form_contact_form_input_padding_left_right, $form_box_shadow_color, $form_border_color, $form_input_background_color, $form_contact_form_input_size, $form_contact_form_input_weight, $form_contact_form_input_height, $form_contact_form_input_border_radius_,$form_contact_form_font_color, $form_box_shadow_color_unfocus);
    $new_json->af2_slider_frage = array($form_box_shadow_color, $form_slider_frage_thumb_background_color, $form_slider_frage_thumb_background_color2,$form_slider_frage_background_color, $form_box_shadow_color_unfocus);
    $new_json->af2_slider_frage_bullet = array($form_question_heading_size_desktop, $form_question_heading_size_mobile, $form_question_heading_weight, $form_slider_frage_bullet_color);
    $new_json->af2_slider_frage_val = array($form_border_color, $form_box_shadow_color, $form_answer_card_text_size_desktop, $form_answer_card_text_size_mobile, $form_text_input_weight, $form_box_shadow_color_unfocus);
    $new_json->af2_slider_frage_val_after = array($form_text_input_border_radius_);
    $new_json->af2_slider_frage_val_before = array($form_text_input_border_radiuslr);

    //$new_json['select2-search__field'] = array($form_text_input_border_radius);

    // 2.0.9.2
    $new_json->af2_question_label = array($form_contact_form_label_size_, $form_text_input_size_mobile, $form_contact_form_label_weight);
    $new_json->af2_radio_label = array($form_contact_form_input_size, $form_contact_form_input_weight);
    $new_json->af2_submit_button = array($form_contact_form_button_background_color_rgb, $form_contact_form_button_color_rgb, $form_font,$form_contact_form_button_color, $form_contact_form_button_size, $form_contact_form_button_weight, $form_contact_form_button_padding_top_bottom,$form_contact_form_button_padding_left,$form_contact_form_button_padding_right, $form_contact_form_button_border_radius, $form_contact_form_button_background_color);
    $new_json->af2_question_cb_label = array($form_contact_form_cb_size, $form_contact_form_cb_weight);
    
    $new_json->af2_dateiupload_inner = array($form_answer_card_border_radius);
    
    
    $new_json->af2_form_html_content = array($form_contact_form_label_size, $form_contact_form_label_weight);
    
    // 2.1.2
    $new_json->af2_form_loader = array($form_loader_color);
    $new_json->af2_address_field_ = array($form_answer_card_border_radius);
    $new_json->af2_adress_map_input_wrapper = array($form_answer_card_border_radius);
    $new_json->af2_html_content_summary = array($form_answer_card_border_radius);

    $new_json->af2_ahref = array($form_contact_form_button_background_color_);

    //$new_json->af2_form_percentage 	= array( $buffer_main_background_color );
    /*         * }
      /** NEW VARIANT **
      else
      {
      //DO ANYTHING
      } */

      $new_json->af2_question_cf_text_type_icon = array($form_contact_form_button_background_color, $form_contact_form_input_height, $form_contact_form_input_border_radiuslr);
      $new_json->{'af2_question_cf_text_type_icon.af2_rtl_layout'} = array($form_contact_form_input_border_radius);
      $new_json->af2_ad_trans = array($form_font, $form_text_input_border_radius, $form_border_color, $form_box_shadow_color, $form_text_input_size_desktop, $form_text_input_size_mobile, $form_text_input_weight, $form_box_shadow_color_unfocus);
      $new_json->alternate_text_wrap_span = array($form_contact_form_button_background_color, $form_answer_card_text_size_desktop, $form_answer_card_text_size_mobile, $form_answer_card_text_weight);
      $new_json->alternate_text_wrap_span_before = array($form_text_input_border_radius_);
      $new_json->alternate_text_wrap_span_after = array($form_text_input_border_radiuslr);
      $new_json->af2_ad_trans_tabel = array($form_answer_card_text_weight, $form_answer_card_text_size_desktop, $form_answer_card_text_size_mobile);
      $new_json->af2_html_content_summary_object_title_ = array($form_answer_card_text_size_desktop, $form_answer_card_text_size_mobile);
      $new_json->af2_html_content_summary_object_answer_ = array($form_answer_card_text_weight, $form_answer_card_text_size_desktop, $form_answer_card_text_size_mobile);
      
          $new_json->range_text_box_label = array($form_answer_card_text_size_desktop, $form_answer_card_text_size_mobile, $form_answer_card_text_weight);
          
          $new_json->af2_response_error = array($form_contact_form_label_size, $form_contact_form_label_weight);

          $new_json->{'af2-select2-container input.select2-search__field'} = array($form_fontfc, $form_border_colorfc, $form_box_shadow_colorfc, $form_answer_card_text_weightfc, $form_text_input_border_radiusfc);
          $new_json->{'af2-select2-container.desktop input.select2-search__field'} = array($form_answer_card_text_size_desktop_fc);
          $new_json->{'af2-select2-container.af2_mobile input.select2-search__field'} = array($form_answer_card_text_size_mobile_fc);

          $new_json->{'af2-select2-container.select2-selection.select2-selection--single'} = array($form_fontfc, $form_border_colorfc, $form_box_shadow_colorfc, $form_answer_card_text_weightfc);
          $new_json->{'af2-select2-container.desktop.select2-selection.select2-selection--single'} = array($form_answer_card_text_size_desktop_fc);
          $new_json->{'af2-select2-container.af2_mobile.select2-selection.select2-selection--single'} = array($form_answer_card_text_size_mobile_fc);
          
          $new_json->{'select2-results__option.select2-results__option--selectable'} = array($form_fontfc, $form_answer_card_text_weightfc);
          $new_json->{'af2-select2-container.desktop .select2-results__option.select2-results__option--selectable'} = array($form_answer_card_text_size_desktop_fc);
          $new_json->{'af2-select2-container.af2_mobile .select2-results__option.select2-results__option--selectable'} = array($form_answer_card_text_size_mobile_fc);

          $new_json->{'af2-select2-container .select2-results__option.select2-results__option--selectable.select2-results__option--highlighted'} = array($form_contact_form_button_background_color_fc);
        
          $new_json->{'af2_notification'} = array($form_contact_form_button_color_rgb, $form_contact_form_button_background_color_rgb);

          return $new_json;
    }

    /**
     * Returns the type of the Dataid
     *
     * -> element = Frage / Kontaktformular
     * -> redirect = redirect
     * -> interface = klicktipp / Deals and Projects / ...
     *
     * @param $dataid
     * @return string
     */
    private function fnsf_af2_check_data_type($dataid) {
        return $dataid === null ? 'undefined' : ( is_numeric($dataid) ? 'element' :
                ( strpos($dataid, 'redirect') === false ? 'interface' : 'redirect' ) );
    }

    /**
     * Checking all input, that there is nothing bad in it
     * 	And returning the post, to reuse it.
     *
     * @param $dataid
     * @return string/post
     */
    private function fnsf_af2_check_params_for_getting_content($dataid) {
        /** Validate the Content * */
        if (!is_numeric($dataid)) {
            return 'ERROR';
        }

        /** SQL CHECK * */
        if (fnsf_sql_check_it($dataid) === 'ERROR') {
             _e('ERROR');
            die();
        }

        /** Getting the Post * */
        $base_post = get_post($dataid);

        /** Check that the post exists * */
        if ($base_post == null) {
            return 'ERROR';
        }

        /** Check that the post is really a Frage, a Kontaktformular, or a Formular * */
        $post_type = get_post_field('post_type', $base_post);             // Type of the Post

        if (( $post_type != 'af2_frage' ) && ( $post_type != 'af2_kontaktformular' ) && ( $post_type != 'af2_formular' )) {
            return 'ERROR';
        }

        return $base_post;
    }

    /**
     * Checking, if any error is given
     *
     * @param $json
     * @return string
     */
    private function fnsf_af2_check_for_errors($json) {
        /*
         * INCOMING
         *
         * READING OUT OF DB
         */

        if (isset($json->error) && $json->error == true) {
            return 'ERROR';
        }
        return '';
    }

}

$frontend_view = new Fnsf_FrontendView();

add_shortcode('anfrageformular2', array($frontend_view, 'fnsf_af2_generate_frontend'));/** SHORTCODE -> Generate Frontend */
add_shortcode('funnelforms', array($frontend_view, 'fnsf_af2_generate_frontend_'));/** SHORTCODE -> Generate Frontend */
add_action('wp_ajax_nopriv_af2_request_data', array($frontend_view, 'fnsf__af2_get_data'));/** ACTION -> Getting Data */
add_action('wp_ajax_af2_request_data', array($frontend_view, 'fnsf__af2_get_data'));

add_action('wp_ajax_nopriv_af2_send_error_mail', 'send_error_mail');
add_action('wp_ajax_af2_send_error_mail', 'send_error_mail');




add_action('wp_ajax_nopriv_af2_send_actual_post', 'af2_save_actual_post');
add_action('wp_ajax_af2_send_actual_post', 'af2_save_actual_post');

function send_error_mail() {
    wp_die();
}

function fnsf_af_create_timestamp() {
    $tz = 'Europe/Amsterdam';
    $timestamp = time();
    $dt = new DateTime("now", new DateTimeZone($tz));
    $dt->setTimestamp($timestamp);

    return $dt->format('YmdH');
}


/**
 * Function to do a check, if an sql injection is getting done;
 *
 * @param $var
 * @return string
 */
function fnsf_sql_check_it($var) {
    if (is_array($var)) {
        foreach ($var as $el) {
            if (fnsf_sql_check_it($el) === 'ERROR') {
                return 'ERROR';
            }
        }

        return '';
    }
    if (strpos(strtolower(strval($var)), 'select') !== false) {
        return 'ERROR';
    } else if (strpos(strtolower(strval($var)), 'update') !== false) {
        return 'ERROR';
    } else if (strpos(strtolower(strval($var)), 'insert') !== false) {
        return 'ERROR';
    } else if (strpos(strtolower(strval($var)), 'drop') !== false) {
        return 'ERROR';
    } else if (strpos(strtolower(strval($var)), 'delete') !== false) {
        return 'ERROR';
    }

    return '';
}

//////////////////////////////////////
/// ///////////////////////
///
///
///
///
///
///
///
///
///
/// /////////////////////////////
///
///
///
///

function fnsf_af2_sanitize_text_or_array_field($array_or_string) {
    if( is_string($array_or_string) ){
        $array_or_string = sanitize_text_field($array_or_string);
    }elseif( is_array($array_or_string) ){
        foreach ( $array_or_string as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = fnsf_af2_sanitize_text_or_array_field($value);
            }
            else {
                $value = sanitize_text_field( $value );
            }
        }
    }

    return $array_or_string;
}

function fnsf_af2_send_mail() {
    $sec = sanitize_text_field($_POST['sec']);
    $cont = sanitize_text_field($_POST['cont']);
    $dataid = sanitize_text_field($_POST['dataid']);
    $answers = rest_sanitize_array($_POST['answers']);

    $answers = fnsf_af2_sanitize_text_or_array_field($answers);

    $at_ids = sanitize_text_field($_POST['attachment_ids']);
    if(isset($at_ids)){
        $attachment_ids = $at_ids ;
    }else{
        $attachment_ids = null ;  
    }

    // SQL CHECKS
    $var = fnsf_sql_check_it($sec);
    $var2 = fnsf_sql_check_it($cont);
    $var3 = fnsf_sql_check_it($dataid);
    $var4 = fnsf_sql_check_it($answers);

    if ($var === 'ERROR' || $var2 === 'ERROR' || $var3 === 'ERROR' || $var4 === 'ERROR') {
        _e('ERROR');
        die();
    }

    // VALIDATE THE NUMERICS
    if (!(is_numeric($sec) && is_numeric($cont) && is_numeric($dataid) )) {
        _e('ERROR');
        die();
    } else {
        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
        $base_post_structure = get_post($dataid);
        $structure_base_json = fnsf_af2_get_post_content($base_post_structure);                         

        $jsonm = json_decode(json_encode($structure_base_json));

        $formular_id = $dataid;

        // CHECKING THAT POST IS A FORMULAR
        if ($base_post_structure == null) {
            _e('ERROR');
            die();
        }
        if (get_post_field('post_type', $base_post_structure) != 'af2_formular') {
            _e('ERROR');
            die();
        }

        $form_id = $jsonm->sections[$sec]->contents[$cont]->data;
        $form_post = get_post($form_id);
        $form_content = fnsf_af2_get_post_content($form_post);
        $form_content = json_decode(json_encode($form_content));

        $i = 0;
        foreach ($answers as $answer) {
            if ($i < sizeof($answers) - 1) {
                if (is_array($answer) && ($answer['date'] == null && $answer['time'] == null && $answer['duration'] == null)) {
                    foreach ($answer as $a) {
                        if (!is_numeric($a)) {
                            _e('ERROR');
                            die();
                        }
                    }
                }
            } else {
                // VALIDATING CONTACT FORM CONTENTS
                // VALIDATING REQUIREDS

                $form_questions = $form_content->questions;
                $answer_block = $answers[sizeof($answers) - 1];
                $x = 0;
                foreach ($form_questions as $form_question) {
                    
                    if ($form_question->typ == 'html_content')
                        continue;
                    
                    if ($form_question->required == 'true') {
                        $frontView = '';
                        if (is_numeric(strpos($form_question->typ, 'text_type_'))) {
                            if (trim($answer_block[$x]) == '') {
;
                                $frontView .= '<div class="af2_response_error" data-id="' . $x . '">';
                                $frontView .= __('This field is a required field!', 'funnelforms-free');
                                $frontView .= '</div>' ;
                                _e(wp_kses_post($frontView)) ;

                                die();
                            }
                        } else if (is_numeric(strpos($form_question->typ, 'salutation'))) {
                            if (trim($answer_block[$x]) == 'keine Angabe') {
                                $frontView_sec  .=  '<div class="af2_response_error" data-id="' . $x . '">';
                                $frontView_sec  .=  __('This field is a required field!', 'funnelforms-free');
                                $frontView_sec  .=  '</div>';
                                 _e(wp_kses_post($frontView_sec)) ;
                                die();
                            }
                        } else {
                            if (strval($answer_block[$x]) == 'false') {
                                $frontView_thr .= '<div class="af2_response_error" data-id="' . $x . '">' ;
                                $frontView_thr .= __('The checkbox must be active!', 'funnelforms-free') ;
                                $frontView_thr .= '</div>' ;
                                _e(wp_kses_post($frontView_thr)) ; 
                                die();
                            }
                        }
                    }

                    //VALIDATE PHONE
                    if ($form_question->typ == 'text_type_phone') {
                        if(!fnsf_validate_phone_number($answer_block[$x], $form_question->required) && $answer_block[$x] != '') {
                            $frontView_fr .= '<div class="af2_response_error" data-id="' . $x . '">' ;
                            $frontView_fr .= __('The phone number is not valid!', 'funnelforms-free') ;
                            $frontView_fr .= '</div>' ;
                             _e(wp_kses_post($frontView_fr)); 

                            die();
                        }
                    }
                    
                    //VALIDATE EMAIL
                    if ($form_question->typ == 'text_type_mail') {
                        $cf_mail = trim($answer_block[$x]);
                        if (!filter_var($cf_mail, FILTER_VALIDATE_EMAIL) && $cf_mail != '') {

                            $frontView_si .= '<div class="af2_response_error" data-id="' . $x . '">' ;
                            $frontView_si .= __('The e-mail address is not valid!', 'funnelforms-free');
                            $frontView_si .= '</div>' ;
                            _e(wp_kses_post($frontView_si));

                            die();
                        }
                        $cf_mail = trim($answer_block[$x]);

                        // IF BUSINESS ? 
                        // Check if the email address contains invalid domains
                        if(isset($form_question->b2bMailValidation) && ( $form_question->b2bMailValidation === 'true' || $form_question->b2bMailValidation === true )) {
                            $invalid_domains = array(
                                'hotmail.com',
                                'gmail.com',
                                'yahoo.co',
                                'yahoo.com',
                                'mailinator.com',
                                'gmail.co.in',
                                'aol.com',
                                'yandex.com',
                                'msn.com',
                                'gawab.com',
                                'inbox.com',
                                'gmx.com',
                                'outlook.de',
                                'web.de',
                                'rediffmail.com',
                                'in.com',
                                'live.com',
                                'hotmail.co.uk',
                                'hotmail.fr',
                                'yahoo.fr',
                                'wanadoo.fr',
                                'comcast.net',
                                'yahoo.co.uk',
                                'yahoo.com.br',
                                'yahoo.co.in',
                                'rediffmail.com',
                                'free.fr',
                                'gmx.de',
                                'yandex.ru',
                                'ymail.com',
                                'libero.it',
                                'outlook.com',
                                'uol.com.br',
                                'bol.com.br',
                                'mail.ru',
                                'cox.net',
                                'hotmail.it',
                                'sbcglobal.net',
                                'sfr.fr',
                                'live.fr',
                                'verizon.net',
                                'live.co.uk',
                                'googlemail.com',
                                'yahoo.es',
                                'ig.com.br',
                                'live.nl',
                                'bigpond.com',
                                'terra.com.br',
                                'yahoo.it',
                                'neuf.fr',
                                'yahoo.de',
                                'live.com',
                                'yahoo.de',
                                'rocketmail.com',
                                'att.net',
                                'laposte.net',
                                'facebook.com',
                                'bellsouth.net',
                                'yahoo.in',
                                'hotmail.es',
                                'charter.net',
                                'yahoo.ca',
                                'yahoo.com.au',
                                'rambler.ru',
                                'hotmail.de',
                                'tiscali.it',
                                'shaw.ca',
                                'yahoo.co.jp',
                                'sky.com',
                                'earthlink.net',
                                'optonline.net',
                                'freenet.de',
                                't-online.de',
                                'aliceadsl.fr',
                                'virgilio.it',
                                'home.nl',
                                'qq.com',
                                'telenet.be',
                                'me.com',
                                'yahoo.com.ar',
                                'tiscali.co.uk',
                                'yahoo.com.mx',
                                'gmx.net',
                                'mail.com',
                                'planet.nl',
                                'tin.it',
                                'live.it',
                                'ntlworld.com',
                                'arcor.de',
                                'yahoo.co.id',
                                'frontiernet.net',
                                'hetnet.nl',
                                'live.com.au',
                                'yahoo.com.sg',
                                'zonnet.nl',
                                'club-internet.fr',
                                'juno.com',
                                'optusnet.com.au',
                                'blueyonder.co.uk',
                                'bluewin.ch',
                                'skynet.be',
                                'sympatico.ca',
                                'windstream.net',
                                'mac.com',
                                'centurytel.net',
                                'chello.nl',
                                'live.ca',
                                'aim.com',
                                'bigpond.net.au'
                            );

                            $email_domain = strtolower(substr(strrchr($cf_mail, "@"), 1));

                            if (in_array($email_domain, $invalid_domains)) {
                                echo '<div class="af2_response_error" data-id="' . $x . '">'.__('Please enter a valid business email!', 'funnelforms-free').'</div>';
                                die();
                            }
                        }
                    }

                    $x++;
                }
            }

            $i++;
        }

        
        // BUILD UP ANSWERS AND PROECESS
        $m_answers = '';
        if(sizeof($answers) > 1) $m_answers = fnsf_af2_create_answers($m_answers, 0, 0, 0, $answers, $jsonm);
        $api_answers = '';
        if(sizeof($answers) > 1) $api_answers = fnsf_af2_create_api_answers($api_answers, 0, 0, 0, $answers, $jsonm);

        // Init the Zapier JSON
        $zapier_json = array('form_id' => $formular_id, 'questions' => array(), 'contact_form' => array());

        // Init the filling of questions and contact form
        if(sizeof($answers) > 1) $zapier_json = fnsf_af2_create_answers_json($zapier_json, 0, 0, 0, $answers, $jsonm, -1);
        $zapier_json = fnsf_af2_create_cf_json($zapier_json, $answers[sizeof($answers) - 1], $form_content);

        //finished zapier_json




        // CHECK FOR DNP INTERACE
        // BUILDING UP DNP INTERFACE
        // CHECK FOR REDIRECT
        // BUILD UP REDIRECT
        $redirect = 'false';
        $blank = 'false';

       

        $klicktipp_mail = 'false';
        $klicktipp_name = 'false';
        $klicktipp_vorname = 'false';
        $klicktipp_telefon = 'false';
        $klicktipp_firma = 'false';
        $klicktipp_tag = 'false';
        $klicktipp_process = 'false';
        $klicktipp_do = 'false';


        
        // SAVE DATA
        $querysData = sanitize_text_field($_POST['af2_queryString']);
        $queryStringData = array("id" => 'queryString', "value" => $querysData );
        $afUrl = sanitize_url($_POST['af2_url']);
        $urlData = array("id" => 'url', "value" => $afUrl);
        $zapier_json['analyticsData'] = array($queryStringData, $urlData);

        $save_json = urlencode(serialize($zapier_json));

        //$save_json = str_replace('\\"', '\\\\"', $save_json);



            $lead_id = wp_insert_post(array('post_content' => $save_json, 'post_type' => FNSF_REQUEST_POST_TYPE, 'post_status' => 'privat'));
        


        $conns = $jsonm->sections[$sec]->contents[$cont]->connections;
        
        foreach ($conns as $conn) {
            $to_sec = $conn->to_section;
            $to_cont = $conn->to_content;

            $dat = $jsonm->sections[$to_sec]->contents[$to_cont]->data;
            if (strpos($dat, 'redirect:') !== false) {
                $redirect = $dat;
                // APPLY REDIRECT PARAMS
                
                $blank = strval($jsonm->sections[$to_sec]->contents[$to_cont]->newtab);
            }

            $contact_form_answers = sanitize_text_field($_POST['contactFormAnswers']);

            
            if (strpos($dat, 'klicktipp:') !== false) {
                if (get_option('klicktipp_user') !== null && get_option('klicktipp_pw') !== null &&
                        get_option('klicktipp_user') !== '' && get_option('klicktipp_pw') !== '') {
                    $a = $answers[sizeof($answers) - 1];

                    $klicktipp_mail = '';
                    if ($jsonm->sections[$to_sec]->contents[$to_cont]->klicktipp_data->mail !== '') {
                        $klicktipp_mail = $a[$jsonm->sections[$to_sec]->contents[$to_cont]->klicktipp_data->mail];
                    }
                    $klicktipp_vorname = '';
                    if ($jsonm->sections[$to_sec]->contents[$to_cont]->klicktipp_data->vorname !== '') {
                        $klicktipp_vorname = $a[$jsonm->sections[$to_sec]->contents[$to_cont]->klicktipp_data->vorname];
                    }
                    $klicktipp_name = '';
                    if ($jsonm->sections[$to_sec]->contents[$to_cont]->klicktipp_data->name !== '') {
                        $klicktipp_name = $a[$jsonm->sections[$to_sec]->contents[$to_cont]->klicktipp_data->name];
                    }
                    $klicktipp_telefon = '';
                    if ($jsonm->sections[$to_sec]->contents[$to_cont]->klicktipp_data->telefon !== '') {
                        $klicktipp_telefon = $a[$jsonm->sections[$to_sec]->contents[$to_cont]->klicktipp_data->telefon];
                    }
                    $klicktipp_firma = '';
                    if ($jsonm->sections[$to_sec]->contents[$to_cont]->klicktipp_data->firma !== '') {
                        $klicktipp_firma = $a[$jsonm->sections[$to_sec]->contents[$to_cont]->klicktipp_data->firma];
                    }
                    $klicktipp_tag = '';

                    if ($jsonm->sections[$to_sec]->contents[$to_cont]->klicktipp_data->tag !== '') {
                        $klicktipp_tag = $jsonm->sections[$to_sec]->contents[$to_cont]->klicktipp_data->tag;
                    }
                    $klicktipp_process = '';
                    if ($jsonm->sections[$to_sec]->contents[$to_cont]->klicktipp_data->process !== '') {
                        $klicktipp_process = $jsonm->sections[$to_sec]->contents[$to_cont]->klicktipp_data->process;
                    }
                    if ($klicktipp_mail !== '') {
                        $klicktipp_do = 'true';
                    }
                }
            }
        }
		
		//executePushNotification($lead_id);

        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
        // CHECK FOR MAIL
        // BUILD UP MAIL
        $mailto = $form_content->mailto;
        $mailfrom = $form_content->mailfrom;
        $mailsubject = fnsf_af2_replace_tags($form_questions,$answer_block,$form_content->mailsubject);
        $mailtext = $form_content->mailtext;
        $mailfrom_name = $form_content->mailfrom_name;
        //$mail_sendtext = $form_content->mail_sendtext;
        $mailcc = $form_content->mailcc;
        $mailbcc = $form_content->mailbcc;
        if(!empty($form_content->mail_replyto)){
            $mail_replyto = fnsf_af2_replace_tags($form_questions,$answer_block,$form_content->mail_replyto);
            // check if its a valid email
            $mail_replyto = is_email($mail_replyto);
        }else{
            $mail_replyto = $form_content->mailfrom;
        }
        
        $mailtextString = sanitize_text_field($_POST['af2_queryString']);
        $mailtextUrl =   sanitize_url($_POST['af2_url']);

        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
        $translations = fnsf_af2GetAnswersTranslations();
        foreach($translations as $translation) {
            $mailtext = str_ireplace($translation, $m_answers, $mailtext);
        }
        $mailtext = str_ireplace('[queryString]', $mailtextString, $mailtext);
        $mailtext = str_ireplace('[url]', $mailtextUrl, $mailtext);
        $mailtext = str_ireplace('[ID]', $lead_id, $mailtext);



        $smtp_do = $form_content->use_smtp;
        $wp_mail_do = $form_content->use_wp_mail;

        $smtp_host = '';
        $smtp_username = '';
        $smtp_password = '';
        $smtp_port = '';
        $smtp_from_name = '';
        $smtp_type = '';

        if ($smtp_do === true || $smtp_do === 'true') {
            $smtp_host = $form_content->smtp_host;
            $smtp_username = $form_content->smtp_username;
            $smtp_password = $form_content->smtp_password;
            $smtp_port = $form_content->smtp_port;
            $smtp_type = $form_content->smtp_type;
        }

        if ($smtp_host === '' || $smtp_username === '' || $smtp_password === '' || $smtp_port === '' || $smtp_type === '') {
            $smtp_do = false;
        }


        $x = 0;
        foreach ($form_questions as $form_question) {
            
            if ($form_question->typ == 'html_content')
                continue;
            
            $mailtext = str_ireplace('[' . $form_question->id . ']', __($answer_block[$x], 'funnelforms-free'), $mailtext);

            

            $x++;
        }
        
        $headers = 'Content-Type: text/html; charset=UTF-8'. "\r\n";
        $headers .= 'From: ' . $mailfrom_name . ' <' . $mailfrom . '>' . "\r\n";
        $headers .= 'CC: ' . $mailcc . "\r\n";
        $headers .= 'BCC: ' . $mailbcc . "\r\n";
        $headers .= 'Reply-To: ' . $mail_replyto . "\r\n";

        // FILE SIZE PROBLEM
        $attachments = array();
        // CREATING ATTACHMENTS
        if( $attachment_ids != null && sizeof( $attachment_ids ) > 0 ) {
            foreach($attachment_ids as $attachment_id) {
                $attachment_url = wp_get_attachment_url( $attachment_id );
                $attachment_url_loc = explode(site_url(), $attachment_url)[1];
                $attachment_url_local = explode('wp-content', $attachment_url_loc)[1];
                $attachment_url_final = WP_CONTENT_DIR . $attachment_url_local;
                array_push( $attachments, $attachment_url_final );
            }
        }

        $sumSize = 0;
        foreach($attachments as $attachment) {
            $realSize = round( fnsf_realFileSize($attachment) / 1024 / 1024, 1, PHP_ROUND_HALF_UP );
            $sumSize = $sumSize + $realSize;
        }

        if($sumSize >= 10) {
            $attachments = false;
        }

        //check if the email address is invalid $secure_check
        $secure_check = fnsf_sanitize_my_email($mailfrom);
        if ($secure_check == false) {
           $frontView_se =  '<div class="af2_response_error">'.__('An error has occurred!', 'funnelforms-free').'</div>';
           _e(wp_kses_post($frontView_se))  ;

            die();
        } else { // SEND EMAIL
            // E-Mail
            //EMAIL TEXT FROM CF
            //$messag = '';
            //if($mail_sendtext === '' || $mail_sendtext === null || $mail_sendtext === 'undefined')
            //{ 
                $messag = __('FORM SENT', 'funnelforms-free'); 
            //}
            //else {
              //  $messag = $mail_sendtext;
            //}
            $mailtext =  nl2br($mailtext);
            if ($smtp_do === true || $smtp_do === 'true') {
                $cc_list = explode(',', $mailcc);
                $bcc_list = explode(',', $mailbcc);
                $res = smtp_mail($smtp_host, $smtp_username, $smtp_password, $smtp_port, $smtp_type, $mailto, $mailfrom, $mailfrom_name, $mailsubject, $mailtext, $cc_list, $bcc_list,$mail_replyto, $attachments);

                if ($res->status != 'Success') {
                    $messag = __('An error has occurred!', 'funnelforms-free');
                }
            } else {

                if ($wp_mail_do === true || $wp_mail_do === 'true') {
                    if($attachments === false) {
                        $mailtext = $mailtext . '<br><br>'.__('The files were not sent as e-mail attachments due to the file size!', 'funnelforms-free');
                        $m = wp_mail($mailto, $mailsubject, $mailtext, $headers);
                    }
                    else $m = wp_mail($mailto, $mailsubject, $mailtext, $headers, $attachments);
                } else {
                    $m = mail($mailto, $mailsubject, $mailtext, $headers);
                }
            }




            $frontView_eh =  '<div class="af2_response_success" data-redirect="' . $redirect . '" data-bl="' . $blank . '">' . $messag . '</div>';
            _e( wp_kses_post($frontView_eh));
            die();
        }
    }

    wp_die();
}





/**
* Return file size (even for file > 2 Gb)
* For file size over PHP_INT_MAX (2 147 483 647), PHP filesize function loops from -PHP_INT_MAX to PHP_INT_MAX.
*
* @param string $path Path of the file
* @return mixed File size or false if error
*/
function fnsf_realFileSize($path)
{
    if (!file_exists($path))
        return false;

    $size = filesize($path);
   
    if (!($file = fopen($path, 'rb')))
        return false;
   
    if ($size >= 0)
    {//Check if it really is a small file (< 2 GB)
        if (fseek($file, 0, SEEK_END) === 0)
        {//It really is a small file
            fclose($file);
            return $size;
        }
    }
   
    //Quickly jump the first 2 GB with fseek. After that fseek is not working on 32 bit php (it uses int internally)
    $size = PHP_INT_MAX - 1;
    if (fseek($file, PHP_INT_MAX - 1) !== 0)
    {
        fclose($file);
        return false;
    }
   
    $length = 1024 * 1024;
    while (!feof($file))
    {//Read the file until end
        $read = fread($file, $length);
        $size = bcadd($size, $length);
    }
    $size = bcsub($size, $length);
    $size = bcadd($size, strlen($read));
   
    fclose($file);
    return $size;
}



function fnsf_af2_save_c_data($json, $answers, $form) {
    $json->contact = [];

    $i = 0;
    foreach ($answers[sizeof($answers) - 1] as $answer) {
        $json->contact[$i]->id = $form->questions[$i]->id;
        $json->contact[$i]->text = $answer;
        $i++;
    }

    return $json;
}


function fnsf_af2_create_cf_json($zapier_json, $array, $cf_json) {
    $i = 0;
    $questions = array();
    if(!empty($cf_json)){
        $restricted_types = array('html_content','google_recaptcha');
        foreach ($cf_json->questions as $question ){
            if(!in_array($question->typ, $restricted_types)){
                $questions[] = $question;
            }
        }
        $cf_json->questions = $questions;
    }
    foreach ($array as $answer) {
        $zapier_json['contact_form'][$i] = array();
        $zapier_json['contact_form'][$i]['id'] = $cf_json->questions[$i]->id;
        $zapier_json['contact_form'][$i]['label'] = $cf_json->questions[$i]->label;
        $zapier_json['contact_form'][$i]['input'] = trim($answer);
        $zapier_json['contact_form'][$i]['typ'] = $cf_json->questions[$i]->typ;

        $i++;
    }

    return $zapier_json;
}

function fnsf_validate_phone_number($phone, $mandatory) {
    $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
    // Remove "-" from number
    $phone_to_check = str_replace("-", "", $filtered_phone_number);
    // Check the lenght of number
    // This can be customized if you want phone number from a specific country
    if ($mandatory == 'true' && (strlen($phone_to_check) < 7 || strlen($phone_to_check) > 20)) return false;
    return true;
}

function fnsf_validate_phone_number_country($phone) {
    $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
    // Remove "-" from number
    $phone_to_check = str_replace("-", "", $filtered_phone_number);
    // Check the lenght of number
    // This can be customized if you want phone number from a specific country
    if (strlen($phone_to_check) < 7 || strlen($phone_to_check) > 20) return false;
    if(strpos($phone_to_check, '+') === false) return false;
    return true;
}

function fnsf_create_phone_number_country($phone) {
    $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
    // Remove "-" from number
    $phone_to_check = str_replace("-", "", $filtered_phone_number);

    return $phone_to_check;
}

// CREATING THE ANSWER JSON DEPENDING ON CONDITIONALS
function fnsf_af2_create_answers_json($answer_text, $section, $content, $answers, $answer_block, $jsonm, $z) {
    $z++;
    $sel = $jsonm->sections[$section]->contents[$content];
    $post_cont = get_post($sel->data);

    $post_json = fnsf_af2_get_post_content($post_cont);
    $post_json = json_decode(json_encode($post_json));

    if ($post_json->error == false) {
        /**
         * ER MUSS ERKENNEN, OB FRAGE ODER KONTAKTFORMULAR -> TRIGGERT NUR BEI FRAGE
         */
        if (!isset($post_json->use_autorespond)) {
            if ($post_json->typ == 'af2_select') {
                $answer_text['questions'][$z] = array();
                $answer_text['questions'][$z]['frage'] = addslashes(strip_tags($post_json->name));
                $answer_text['questions'][$z]['antwort'] = $post_json->answers[$answer_block[$answers]]->text;
            } else if ($post_json->typ == 'af2_textfeld') {
                $answer_text['questions'][$z] = array();
                $answer_text['questions'][$z]['frage'] = addslashes(strip_tags($post_json->name));
                $answer_text['questions'][$z]['antwort'] = $answer_block[$answers];
            } else if ($post_json->typ == 'af2_textbereich') {
                $answer_text['questions'][$z] = array();
                $answer_text['questions'][$z]['frage'] = addslashes(strip_tags($post_json->name));
                $ans = str_replace("\n", "<br>", $answer_block[$answers]);
                $answer_text['questions'][$z]['antwort'] = $ans;
            } else if ($post_json->typ == 'af2_multiselect') {
                $answer_text['questions'][$z] = array();
                $answer_text['questions'][$z]['frage'] = addslashes(strip_tags($post_json->name));
                $answer_text['questions'][$z]['antwort'] = array();
                $y = 0;
                foreach ($answer_block[$answers] as $ans) {
                    array_push($answer_text['questions'][$z]['antwort'], $post_json->answers[$ans]->text);
                    $y++;
                }
            } 
        }
        if ($answers == sizeof($answer_block) - 2) {
            return $answer_text;
        } else {
            $pos = false;
            $sec = false;
            $cont = false;
            if ($post_json->typ == 'af2_select') {
                $conns = $jsonm->sections[$section]->contents[$content]->connections;

                for ($i = 0; $i < sizeof($conns); $i++) {
                    if (strval($conns[$i]->from) == strval($answer_block[$answers])) {
                        $sec = $conns[$i]->to_section;
                        $cont = $conns[$i]->to_content;
                    } else if ($conns[$i]->from == '-1' || $conns[$i]->from == -1) {
                        $pos = $i;
                    }
                }

                if ($sec == false) {
                    $sec = $conns[$pos]->to_section;
                    $cont = $conns[$pos]->to_content;
                }
            }
            //Slider conditions
            else if ($post_json->typ == 'af2_slider') {
                $conns = $jsonm->sections[$section]->contents[$content]->connections; //Getting connections
                //Loop through all connections
                for ($i = 0; $i < sizeof($conns); $i++) {
                    if (strval($conns[$i]->from) == '-1') {    //Allgemein - SET
                        if ($sec === false) {                    //If nothing else found until yet
                            //Set contents
                            $sec = $conns[$i]->to_section;
                            $cont = $conns[$i]->to_content;
                            continue;
                        }
                    }



                    //Check that they are defined
                    if (isset($conns[$i]->operator) && isset($conns[$i]->number)) {
                        //Get Operators and values
                        $operator = $conns[$i]->operator;
                        $value = $conns[$i]->number;
                        //Switch the operator and fill all cases
                        switch ($operator) {
                            case '<': {
                                    if (intval($answer_block[$answers]) < intval($value)) {
                                        $sec = $conns[$i]->to_section;
                                        $cont = $conns[$i]->to_content;
                                    }
                                    break;
                                }
                            case '>': {
                                    if (intval($answer_block[$answers]) > intval($value)) {
                                        $sec = $conns[$i]->to_section;
                                        $cont = $conns[$i]->to_content;
                                    }
                                    break;
                                }
                            case '=': {
                                    if (intval($answer_block[$answers]) == intval($value)) {
                                        $sec = $conns[$i]->to_section;
                                        $cont = $conns[$i]->to_content;
                                    }
                                    break;
                                }
                        }
                    }
                }
            } else if($post_json->typ == 'af2_dropdown') {
                $conns = $jsonm->sections[$section]->contents[$content]->connections;

                for ($i = 0; $i < sizeof($conns); $i++) {
                    if (strval($conns[$i]->from) == strval($answer_block[$answers])) {
                        $sec = $conns[$i]->to_section;
                        $cont = $conns[$i]->to_content;
                    } else if ($conns[$i]->from == '-1' || $conns[$i]->from == -1) {
                        $pos = $i;
                    }
                }

                if ($sec == false) {
                    $sec = $conns[$pos]->to_section;
                    $cont = $conns[$pos]->to_content;
                }
            } 
            else {
                $sec = $jsonm->sections[$section]->contents[$content]->connections[0]->to_section;
                $cont = $jsonm->sections[$section]->contents[$content]->connections[0]->to_content;
            }

            return fnsf_af2_create_answers_json($answer_text, $sec, $cont, $answers + 1, $answer_block, $jsonm, $z);
        }
    } else {
        _e('<div class="af2_response_error">'.__('An error has occurred!', 'funnelforms-free').'</div>') ;
        die();
    }
}

function fnsf_af2_create_answers($answer_text, $section, $content, $answers, $answer_block, $jsonm) {
    $sel = $jsonm->sections[$section]->contents[$content];
    $post_cont = get_post($sel->data);

    $post_json = fnsf_af2_get_post_content($post_cont);
    $post_json = json_decode(json_encode($post_json));

    /**
     * TO DO CHECK FOR CF
     */
    if ($post_json->error == false) {
        $lines = '_________________________';
        if (!isset($post_json->use_autorespond)) {
            if($post_json->typ != 'af2_content')
                $answer_text .= '<b>'.$post_json->name . "</b>\n";
            if ($post_json->typ == 'af2_select') {
                $answer_text .= $post_json->answers[$answer_block[$answers]]->text . "\n";
            } else if ($post_json->typ == 'af2_textfeld') {
                $answer_text .= $answer_block[$answers] . "\n";
            } else if ($post_json->typ == 'af2_textbereich') {
                $answer_text .= $answer_block[$answers] . "\n";
            } else if ($post_json->typ == 'af2_multiselect') {
                foreach ($answer_block[$answers] as $ans) {
                    $answer_text .= $post_json->answers[$ans]->text . "\n";
                }
            } 
        }
        $answer_text .= $lines;
        $answer_text .= "\n";
        $answer_text .= "\n";

        if ($answers == sizeof($answer_block) - 2) {
            return $answer_text;
        } else {
            $pos = false;
            $sec = false;
            $cont = false;
            if ($post_json->typ == 'af2_select') {
                $conns = $jsonm->sections[$section]->contents[$content]->connections;

                for ($i = 0; $i < sizeof($conns); $i++) {
                    if (strval($conns[$i]->from) == strval($answer_block[$answers])) {
                        $sec = $conns[$i]->to_section;
                        $cont = $conns[$i]->to_content;
                    } else if ($conns[$i]->from == '-1' || $conns[$i]->from == -1) {
                        $pos = $i;
                    }
                }

                if ($sec == false) {
                    $sec = $conns[$pos]->to_section;
                    $cont = $conns[$pos]->to_content;
                }
            }
            //Slider conditions
            else if ($post_json->typ == 'af2_slider') {
                $conns = $jsonm->sections[$section]->contents[$content]->connections; //Getting connections
                //Loop through all connections
                for ($i = 0; $i < sizeof($conns); $i++) {
                    if (strval($conns[$i]->from) == '-1') { //Allgemein - SET
                        if ($sec === false) {     //If nothing else found until yet
                            //Set contents
                            $sec = $conns[$i]->to_section;
                            $cont = $conns[$i]->to_content;
                            continue;
                        }
                    }



                    //Check that they are defined
                    if (isset($conns[$i]->operator) && isset($conns[$i]->number)) {
                        //Get Operators and values
                        $operator = $conns[$i]->operator;
                        $value = $conns[$i]->number;
                        //Switch the operator and fill all cases
                        switch ($operator) {
                            case '<': {
                                    if (intval($answer_block[$answers]) < intval($value)) {
                                        $sec = $conns[$i]->to_section;
                                        $cont = $conns[$i]->to_content;
                                    }
                                    break;
                                }
                            case '>': {
                                    if (intval($answer_block[$answers]) > intval($value)) {
                                        $sec = $conns[$i]->to_section;
                                        $cont = $conns[$i]->to_content;
                                    }
                                    break;
                                }
                            case '=': {
                                    if (intval($answer_block[$answers]) == intval($value)) {
                                        $sec = $conns[$i]->to_section;
                                        $cont = $conns[$i]->to_content;
                                    }
                                    break;
                                }
                        }
                    }
                }
            } else if($post_json->typ == 'af2_dropdown') {
                $conns = $jsonm->sections[$section]->contents[$content]->connections;

                for ($i = 0; $i < sizeof($conns); $i++) {
                    if (strval($conns[$i]->from) == strval($answer_block[$answers])) {
                        $sec = $conns[$i]->to_section;
                        $cont = $conns[$i]->to_content;
                    } else if ($conns[$i]->from == '-1' || $conns[$i]->from == -1) {
                        $pos = $i;
                    }
                }

                if ($sec == false) {
                    $sec = $conns[$pos]->to_section;
                    $cont = $conns[$pos]->to_content;
                }
            }  
            else {
                $sec = $jsonm->sections[$section]->contents[$content]->connections[0]->to_section;
                $cont = $jsonm->sections[$section]->contents[$content]->connections[0]->to_content;
            }

            return fnsf_af2_create_answers($answer_text, $sec, $cont, $answers + 1, $answer_block, $jsonm);
        }
    } else {
        _e( '<div class="af2_response_error">'.__('An error has occurred!', 'funnelforms-free').'</div>');
        die();
    }
}

function fnsf_af2_create_api_answers($answer_text, $section, $content, $answers, $answer_block, $jsonm) {
    $sel = $jsonm->sections[$section]->contents[$content];
    $post_cont = get_post($sel->data);

    $post_json = fnsf_af2_get_post_content($post_cont);
    $post_json = json_decode(json_encode($post_json));

    /**
     * TO DO CHECK FOR CF
     */
    if ($post_json->error == false) {
        $lines = '_________________________';
        if (!isset($post_json->use_autorespond)) {
            if($post_json->typ != 'af2_content')
                $answer_text .= ''.$post_json->name . "\n";
            if ($post_json->typ == 'af2_select') {
                $answer_text .= $post_json->answers[$answer_block[$answers]]->text . "\n";
            } else if ($post_json->typ == 'af2_textfeld') {
                $answer_text .= $answer_block[$answers] . "\n";
            } else if ($post_json->typ == 'af2_textbereich') {
                $answer_text .= $answer_block[$answers] . "\n";
            }else if ($post_json->typ == 'af2_multiselect') {
                foreach ($answer_block[$answers] as $ans) {
                    $answer_text .= $post_json->answers[$ans]->text . "\n";
                }
            } 
        }
        $answer_text .= $lines;
        $answer_text .= "\n";
        $answer_text .= "\n";

        if ($answers == sizeof($answer_block) - 2) {
            return $answer_text;
        } else {
            $pos = false;
            $sec = false;
            $cont = false;
            if ($post_json->typ == 'af2_select') {
                $conns = $jsonm->sections[$section]->contents[$content]->connections;

                for ($i = 0; $i < sizeof($conns); $i++) {
                    if (strval($conns[$i]->from) == strval($answer_block[$answers])) {
                        $sec = $conns[$i]->to_section;
                        $cont = $conns[$i]->to_content;
                    } else if ($conns[$i]->from == '-1' || $conns[$i]->from == -1) {
                        $pos = $i;
                    }
                }

                if ($sec == false) {
                    $sec = $conns[$pos]->to_section;
                    $cont = $conns[$pos]->to_content;
                }
            }
            //Slider conditions
            else if ($post_json->typ == 'af2_slider') {
                $conns = $jsonm->sections[$section]->contents[$content]->connections; //Getting connections
                //Loop through all connections
                for ($i = 0; $i < sizeof($conns); $i++) {
                    if (strval($conns[$i]->from) == '-1') { //Allgemein - SET
                        if ($sec === false) {     //If nothing else found until yet
                            //Set contents
                            $sec = $conns[$i]->to_section;
                            $cont = $conns[$i]->to_content;
                            continue;
                        }
                    }



                    //Check that they are defined
                    if (isset($conns[$i]->operator) && isset($conns[$i]->number)) {
                        //Get Operators and values
                        $operator = $conns[$i]->operator;
                        $value = $conns[$i]->number;
                        //Switch the operator and fill all cases
                        switch ($operator) {
                            case '<': {
                                    if (intval($answer_block[$answers]) < intval($value)) {
                                        $sec = $conns[$i]->to_section;
                                        $cont = $conns[$i]->to_content;
                                    }
                                    break;
                                }
                            case '>': {
                                    if (intval($answer_block[$answers]) > intval($value)) {
                                        $sec = $conns[$i]->to_section;
                                        $cont = $conns[$i]->to_content;
                                    }
                                    break;
                                }
                            case '=': {
                                    if (intval($answer_block[$answers]) == intval($value)) {
                                        $sec = $conns[$i]->to_section;
                                        $cont = $conns[$i]->to_content;
                                    }
                                    break;
                                }
                        }
                    }
                }
            } else if($post_json->typ == 'af2_dropdown') {
                $conns = $jsonm->sections[$section]->contents[$content]->connections;

                for ($i = 0; $i < sizeof($conns); $i++) {
                    if (strval($conns[$i]->from) == strval($answer_block[$answers])) {
                        $sec = $conns[$i]->to_section;
                        $cont = $conns[$i]->to_content;
                    } else if ($conns[$i]->from == '-1' || $conns[$i]->from == -1) {
                        $pos = $i;
                    }
                }

                if ($sec == false) {
                    $sec = $conns[$pos]->to_section;
                    $cont = $conns[$pos]->to_content;
                }
            }  
            else {
                $sec = $jsonm->sections[$section]->contents[$content]->connections[0]->to_section;
                $cont = $jsonm->sections[$section]->contents[$content]->connections[0]->to_content;
            }

            return fnsf_af2_create_answers($answer_text, $sec, $cont, $answers + 1, $answer_block, $jsonm);
        }
    } else {
         _e('<div class="af2_response_error">'.__('An error has occurred!', 'funnelforms-free').'</div>');
        die();
    }
}

function fnsf_sanitize_my_email($field) {
    $field = filter_var($field, FILTER_SANITIZE_EMAIL);
    if (filter_var($field, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}


function fnsf_af2_random_code() {
    $digit1 = strval(random_int(0, 9));
    $digit2 = strval(random_int(0, 9));
    $digit3 = strval(random_int(0, 9));
    
    $digit4 = strval(random_int(0, 9));
    $digit5 = strval(random_int(0, 9));
    $digit6 = strval(random_int(0, 9));

    return $digit1.$digit2.$digit3.$digit4.$digit5.$digit6;
}


add_action('wp_ajax_nopriv_fnsf_af2_send_mail', 'fnsf_af2_send_mail');
add_action('wp_ajax_fnsf_af2_send_mail', 'fnsf_af2_send_mail');

function smtp_mail($host, $user, $password, $port, $type, $to, $from, $from_nam, $subject, $body, $cc, $bcc,$replyto,$attachments) {

    $errors = '';

    //$swpsmtp_options = get_option('twm_smtp_options');

    require_once( ABSPATH . WPINC . '/class-smtp.php' );
    require_once( ABSPATH . WPINC . '/class-phpmailer.php' );
    $mail = new \PHPMailer();

    $charset = get_bloginfo('charset');
    $mail->CharSet = $charset;
    $mail->Timeout = 10;

    $from_name = $from_nam;
    $from_email = $from;

    $mail->IsSMTP();

    $mail->SMTPAuth = true;
    $mail->Username = $user;
    $mail->Password = $password;

    $mail->SMTPSecure = $type;

    /* PHPMailer 5.2.10 introduced this option. However, this might cause issues if the server is advertising TLS with an invalid certificate. */
    $mail->SMTPAutoTLS = false;

    /* Set the other options */
    $mail->Host = $host;
    $mail->Port = $port;

    $mail->SetFrom($from_email, $from_name);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    //$mail->MsgHTML($body);
    if($attachments === false) {
        $mail->Body = $body . '<br><br>'.__('The files were not sent as e-mail attachments due to the file size!', 'funnelforms-free');
    }
    else $mail->Body = $body;
    $mail->AddAddress($to);

    foreach ($cc as $c) {
        $c = trim($c);

        $mail->addCC($c);
    }

    foreach ($bcc as $bc) {
        $bc = trim($bc);

        $mail->addBCC($bc);
    }
    
    if(!empty($replyto)){
        $mail->addReplyTo(trim($replyto));
    }

    if ($attachments !== false) {
        $k = 0;
        foreach($attachments as $attachment)
        {
            $name_arr = explode('/', $attachment);
            $name_size = count($name_arr);
            $name = $name_arr[$name_size-1];
            $mail->AddAttachment($attachment, $name);
            $k++;
        }
        
    }

    global $debugMSG;
    $debugMSG = '';
    $mail->Debugoutput = function($str, $level) {
        global $debugMSG;
        $debugMSG .= $str;
    };
    $mail->SMTPDebug = 4;

    /* Send mail and return result */

    //$error = $mail->Send();

    if (!$mail->Send())
        $errors = $mail->ErrorInfo;

    $mail->ClearAddresses();
    $mail->ClearAllRecipients();
    $mail->clearCCs();
    $mail->clearBCCs();

    $cl = new \stdClass();
    $cl->debug = $debugMSG;

    if (!empty($errors)) {
        $cl->msg = $errors;
        $cl->status = 'Fail';
    } else {
        $cl->msg = 'Test mail was sent';
        $cl->status = 'Success';
    }

    return $cl;

    //return $error;
}

function smtp_mail2($host, $user, $password, $port, $type, $to, $from, $from_nam, $subject, $body, $attachments) {

    $errors = '';

    //$swpsmtp_options = get_option('twm_smtp_options');

    require_once( ABSPATH . WPINC . '/class-smtp.php' );
    require_once( ABSPATH . WPINC . '/class-phpmailer.php' );
    $mail = new \PHPMailer();

    $charset = get_bloginfo('charset');
    $mail->CharSet = $charset;
    $mail->Timeout = 10;

    $from_name = $from_nam;
    $from_email = $from;

    $mail->IsSMTP();

    $mail->SMTPAuth = true;
    $mail->Username = $user;
    $mail->Password = $password;

    $mail->SMTPSecure = $type;

    /* PHPMailer 5.2.10 introduced this option. However, this might cause issues if the server is advertising TLS with an invalid certificate. */
    $mail->SMTPAutoTLS = false;

    /* Set the other options */
    $mail->Host = $host;
    $mail->Port = $port;

    $mail->SetFrom($from_email, $from_name);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    //$mail->MsgHTML($body);
    $mail->Body = $body;
    $mail->AddAddress($to);

    if ($attachments != array()) {
        $k = 0;
        foreach($attachments as $attachment)
        {
            $name_arr = explode('/', $attachment);
            $name_size = count($name_arr);
            $name = $name_arr[$name_size-1];
            $mail->AddAttachment($attachment, $name);
            $k++;
        }
    }

    global $debugMSG;
    $debugMSG = '';
    $mail->Debugoutput = function($str, $level) {
        global $debugMSG;
        $debugMSG .= $str;
    };
    $mail->SMTPDebug = 4;

    /* Send mail and return result */

    //$error = $mail->Send();

    if (!$mail->Send())
        $errors = $mail->ErrorInfo;

    $mail->ClearAddresses();
    $mail->ClearAllRecipients();
    $mail->clearCCs();
    $mail->clearBCCs();

    $cl = new \stdClass();
    $cl->debug = $debugMSG;

    if (!empty($errors)) {
        $cl->msg = $errors;
        $cl->status = 'Fail';
    } else {
        $cl->msg = 'Test mail was sent';
        $cl->status = 'Success';
    }

    return $cl;

    //return $error;
}



function fnsf_klicktipp_doit($email_address, $process, $tag_id, $fields) {
    /**
     * Add subscriber. Login required.
     *
     * @param mixed $email_email address
     * @param mixed $subscription_process (optional)
     * @param mixed $tag_id (optional)
     * @param mixed $fields (optional)
     * @param mixed $smsnumber (optional)
     *
     * @return subscriber object
     */
    require ('klicktipp.api.inc');

    $username = get_option('klicktipp_user'); // Replace with username
    $password = get_option('klicktipp_pw'); // Replace with password

    $connector = new KlicktippConnector();
    $connector->login($username, $password);
    $subscriber = $connector->subscribe($email_address, $process, $tag_id, $fields);
    $connector->logout();


    if ($subscriber) {
        return print_r($subscriber, true);
    } else {
        return $connector->get_last_error();
    }
}

function fnsf_af2_replace_tags($form_questions,$answer_block,$text){
    $x = 0;
    foreach ($form_questions as $form_question) {
        
        if ($form_question->typ == 'html_content')
            continue;
        
        $text = str_ireplace('[' . $form_question->id . ']', $answer_block[$x], $text);
        $x++;
    }
    return $text;
}

function fnsf_wpaf2_custom_upload_dir( $dir_data ) {
    // $dir_data already you might want to use
    $custom_dir = 'af2';
    return [
        'path' => $dir_data[ 'basedir' ] . '/' . $custom_dir,
        'url' => $dir_data[ 'url' ] . '/' . $custom_dir,
        'subdir' => '/' . $custom_dir,
        'basedir' => $dir_data[ 'error' ],
        'error' => $dir_data[ 'error' ],
    ];
}

add_action('wp_ajax_af2_handel_file_upload','fnsf_af2_handel_file_upload');
add_action('wp_ajax_nopriv_af2_handel_file_upload','fnsf_af2_handel_file_upload');
function fnsf_af2_handel_file_upload(){
    $resp = array();

    add_filter( 'upload_dir', 'fnsf_wpaf2_custom_upload_dir' );
    // uploading
    $media_id = media_handle_upload( 'af2_file', 0 );
    // remove so it doesn't apply to all uploads
    remove_filter( 'upload_dir', 'fnsf_wpaf2_custom_upload_dir' );

    if ( is_wp_error( $media_id ) ) {
        $resp['success'] = false;
        $resp['error'] = $media_id->get_error_message();
        $resp['media_id'] = 0;
    }else{
        $resp['success'] = true;
        $resp['media_id'] = $media_id;
        $resp['media_url'] = wp_get_attachment_url($media_id);
    }
    
    echo json_encode($resp);
    die();
}     

add_action('wp_ajax_af2_handel_file_remove','fnsf_af2_handel_file_remove');
add_action('wp_ajax_nopriv_af2_handel_file_remove','fnsf_af2_handel_file_remove');

function fnsf_af2_handel_file_remove(){
    $resp = array();
    $resp['success'] = false;
    if(isset($_POST['attachment_id'])){
        $resp['success'] = true;
        wp_delete_attachment(sanitize_text_field($_POST['attachment_id']),true);
    }
    _e(json_encode($resp));
    die();
}

function fnsf_af2_get_supported_mime_types(){
    $supported_types = array();
    $restricted_types = array('application/javascript','text/css','text/html','application/java','application/ttaf+xml','application/vnd.ms-access','application/vnd.ms-project','application/vnd.oasis.opendocument.database');
    $wp_mime_types = get_allowed_mime_types();
    foreach ($wp_mime_types as $ext=>$type){
        if(!in_array($type, $restricted_types)){
            $supported_types[] = $type;
        }
    }
    
    return $supported_types;
}


function fnsf_af2_dateTime_getDateTimeByStringArray($dateStringArray, $time, $defaultTimeZone) {
    date_default_timezone_set($defaultTimeZone);

    $date = $dateStringArray[2] . '-' . $dateStringArray[0] . '-' . $dateStringArray[1].'T'.$time.':00';
    return new DateTime($date);
}

function fnsf_af2_dateTime_getDateTimeFormattedStringByDateTime($dateTime) {
    return $dateTime->format('Y-m-d').'T'.$dateTime->format('H:i').':00Z';
}

function fnsf_af2_dateTime_convertDateTimeTimezone($dateTime, $timezoneString) {
    $valueDateTime = $dateTime;
    $valueDateTime = $valueDateTime->setTimeZone(new DateTimeZone($timezoneString));
    return $valueDateTime;
}; 

?>
