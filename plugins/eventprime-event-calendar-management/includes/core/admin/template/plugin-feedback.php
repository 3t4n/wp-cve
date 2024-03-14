<?php defined( 'ABSPATH' ) || exit;?>
<?php 
 $ep_admin_email = get_option('admin_email');
 if( ! empty( $ep_admin_email ) ){
    $ep_admin_email = $ep_admin_email;
 }else{
    $ep_admin_email = '';
 }
?>
<div class="emagic">
<div id="ep_plugin_feedback_form_modal" class="ep-modal-view" style="display: none;">
    <div class="ep-modal-overlay ep-modal-overlay-fade-in close-popup" data-id="ep_plugin_feedback_form_modal"></div>
    <div class="popup-content ep-modal-wrap ep-modal-xsm ep-modal-out"> 
        <div class="ep-modal-body">
            <div class="ep-modal-titlebar ep-d-flex ep-items-center ep-border-bottom-0 ep-ps-3">
                <div class="ep-modal-title ep-px-3 ep-lh-1">
                    <div class="ep-fs-4 ep-mt-2 ep-mb-1 ep-pb-2 ep-pt-2 ep-text-dark ep-pl-3"><?php esc_html_e( 'Uninstalling EventPrime?', 'eventprime-event-calendar-management' ); ?></div>
                    <div class="ep-fs-6 ep-text-dark ep-text-small ep-pl-3">Please let us know what went wrong.</div>
                </div>
              
                <a href="#" class="ep-modal-close ep-plugin-deactivation-modal-close close-popup" data-id="ep_plugin_feedback_form_modal"><span class="material-icons ep-text-dark ep-fs-5">close</span></a>
            </div> 
            <div class="ep-modal-content-wrap">
                <form id="ep-deactivate-feedback-dialog-form" method="post"> 
                   
                        <div class="ep-p-3 ep-settings-checkout-field-manager">
                            <input type="hidden" name="action" value="ep_deactivate_feedback" />
                           
                                <div id="ep-deactivate-feedback-dialog-form-caption" class=""></div>
                                <div class="ep-deactivate-feedback-wrap ep-px-3">
                                <div id="ep-deactivate-feedback-dialog-form-body" class="ep-box-row">
                                    <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-3 ep-box-col-6">
                                        <div class="ep-deactive-feedback-box">
                                            <input id="ep-deactivate-feedback-feature_not_available" class="ep-deactivate-feedback-dialog-input ep-d-none" type="radio" name="ep_feedback_key" value="feature_not_available">
                                            <label for="ep-deactivate-feedback-feature_not_available" class="ep-deactivate-feedback-dialog-label ep-lh-0 ep-border ep-border-dark ep-border-dark ep-rounded ep-p-3 ep-box-w-100 ep-di-flex ep-align-items-center"><span class="ep-feedback-emoji ep-mr-2 "><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#2271b1" xmlns:v="https://vecta.io/nano"><path d="M193.119 96.559h-6.743c-.002 24.814-10.048 47.246-26.307 63.51s-38.696 26.305-63.508 26.307-47.246-10.048-63.511-26.307-26.305-38.696-26.307-63.51 10.047-47.245 26.305-63.51C49.314 16.791 71.746 6.745 96.56 6.744s47.245 10.047 63.508 26.305c16.259 16.265 26.305 38.697 26.307 63.51h6.743C193.117 43.229 149.89.002 96.56 0S.002 43.229 0 96.559s43.229 96.557 96.56 96.56 96.557-43.23 96.559-96.56zm-146.79-21.69c0-4.546 3.686-8.23 8.232-8.23s8.232 3.684 8.232 8.23-3.686 8.232-8.232 8.232-8.232-3.686-8.232-8.232zm83.999 0a8.23 8.23 0 0 1 8.23-8.23c4.546 0 8.232 3.684 8.232 8.23s-3.686 8.232-8.232 8.232-8.23-3.686-8.23-8.232zm-71.589 44.979l2.975-1.585-12.409-23.289a3.37 3.37 0 0 0-2.975-1.787c-1.247 0-2.39.685-2.975 1.787l-12.409 23.289.011-.022-2.472 5.142c-.627 1.71-.963 3.568-.954 5.604.002 10.385 8.416 18.797 18.799 18.799a18.8 18.8 0 0 0 18.799-18.799 17.55 17.55 0 0 0-.2-2.804c-.213-1.352-.63-2.604-1.169-3.825-.543-1.231-1.2-2.472-2.031-4.068l-.014-.027-2.975 1.585-2.991 1.558c1.123 2.145 1.818 3.497 2.163 4.471.176.493.282.9.359 1.369s.116 1.014.117 1.741c-.002 3.34-1.345 6.332-3.532 8.525-2.193 2.186-5.185 3.529-8.525 3.53s-6.332-1.344-8.525-3.53c-2.186-2.193-3.529-5.185-3.53-8.525.008-1.39.175-2.242.552-3.315.381-1.065 1.054-2.372 2.058-4.217l.013-.022 9.433-17.704 9.433 17.704-.014-.027 2.991-1.558zm13.342-4.869a45.58 45.58 0 0 1 24.625-7.175 45.57 45.57 0 0 1 27.197 8.946c1.498 1.106 3.608.79 4.716-.708s.79-3.608-.706-4.716a52.33 52.33 0 0 0-31.207-10.266c-10.39-.002-20.1 3.023-28.255 8.236-1.569 1.003-2.029 3.088-1.027 4.657s3.088 2.028 4.657 1.025z"/></svg></span><?php esc_html_e("Doesn't have the feature I need","eventprime-event-calendar-management");?></label>
                                        </div>
                                       
                                    </div>
                                    
                                    <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-3 ep-box-col-6">
                                        <div class="ep-deactive-feedback-box">
                                            <input id="ep-deactivate-feedback-feature_not_working" class="ep-deactivate-feedback-dialog-input ep-d-none" type="radio" name="ep_feedback_key" value="feature_not_working" >
                                            <label for="ep-deactivate-feedback-feature_not_working" class="ep-deactivate-feedback-dialog-label ep-lh-0 ep-border ep-border-dark ep-rounded ep-p-3 ep-box-w-100 ep-di-flex ep-align-items-center"><span class="ep-feedback-emoji ep-mr-2"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#272525" xmlns:v="https://vecta.io/nano"><path d="M193.75 96.876h-6.764c-.002 24.893-10.081 47.399-26.393 63.717-16.318 16.312-38.824 26.39-63.717 26.391s-47.401-10.08-63.717-26.391C16.847 144.275 6.767 121.769 6.765 96.876s10.081-47.401 26.393-63.717S71.981 6.767 96.876 6.765s47.399 10.081 63.717 26.393c16.312 16.317 26.391 38.822 26.393 63.717h6.764C193.748 43.372 150.38.003 96.876 0 43.372.003.003 43.372 0 96.876c.003 53.504 43.372 96.873 96.876 96.874s96.873-43.37 96.874-96.874zM46.482 75.115a8.26 8.26 0 0 1 8.259-8.259 8.26 8.26 0 0 1 8.257 8.259 8.26 8.26 0 0 1-8.257 8.259 8.26 8.26 0 0 1-8.259-8.259zm84.272 0a8.26 8.26 0 0 1 8.259-8.259 8.26 8.26 0 0 1 8.259 8.259 8.26 8.26 0 0 1-8.259 8.259 8.26 8.26 0 0 1-8.259-8.259zm-76.059 61.364c3.529-8.142 9.357-15.072 16.659-19.959a45.71 45.71 0 0 1 25.522-7.733 45.72 45.72 0 0 1 25.517 7.73 46.15 46.15 0 0 1 16.659 19.951 3.38 3.38 0 0 0 4.449 1.757c1.714-.744 2.501-2.735 1.757-4.449a52.9 52.9 0 0 0-19.104-22.881 52.48 52.48 0 0 0-29.279-8.873 52.48 52.48 0 0 0-29.285 8.876 52.92 52.92 0 0 0-19.102 22.889c-.743 1.714.043 3.706 1.757 4.449s3.706-.043 4.449-1.757z"/></svg></span><?php esc_html_e("One of the features didn't work","eventprime-event-calendar-management");?></label>
                                        </div>
                                    </div>
                                    
                                    <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-3 ep-box-col-6">
                                        <div class="ep-deactive-feedback-box">
                                            <input id="ep-deactivate-feedback-plugin_difficult-to-use" class="ep-deactivate-feedback-dialog-input ep-d-none" type="radio" name="ep_feedback_key" value="plugin_difficult-to-use">
                                            <label for="ep-deactivate-feedback-plugin_difficult-to-use" class="ep-deactivate-feedback-dialog-label ep-lh-0 ep-border ep-border-dark ep-rounded ep-p-3 ep-box-w-100 ep-di-flex ep-align-items-center"><span class="ep-feedback-emoji ep-mr-2"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#272525" xmlns:v="https://vecta.io/nano"><path d="M193.743 96.87h-6.765c-.002 24.896-10.081 47.404-26.392 63.72-16.316 16.3-38.821 26.376-63.714 26.392-24.894-.016-47.399-10.092-63.717-26.392-16.311-16.316-26.389-38.824-26.391-63.72s10.079-47.399 26.391-63.717C49.473 16.842 71.978 6.765 96.872 6.763s47.398 10.079 63.714 26.391c16.311 16.318 26.391 38.823 26.392 63.717h6.765C193.742 43.367 150.375 0 96.872-.002S.002 43.367 0 96.87s43.369 96.861 96.873 96.877c53.502-.016 96.869-43.377 96.871-96.877zm-139.05 39.605c3.529-8.15 9.355-15.074 16.658-19.961s16.065-7.736 25.521-7.736a45.69 45.69 0 0 1 25.517 7.736 46.17 46.17 0 0 1 16.657 19.945c.743 1.719 2.736 2.499 4.451 1.767a3.39 3.39 0 0 0 1.757-4.457c-4.056-9.344-10.73-17.271-19.103-22.874s-18.459-8.882-29.278-8.882-20.913 3.279-29.284 8.882-15.049 13.53-19.103 22.89c-.743 1.703.045 3.709 1.759 4.441a3.37 3.37 0 0 0 4.449-1.751zM41.18 76.354c2.685 4.646 7.733 7.782 13.494 7.782 5.372 0 10.133-2.738 12.921-6.869a3.98 3.98 0 0 0-1.07-5.525A3.98 3.98 0 0 0 61 72.813a7.61 7.61 0 0 1-6.326 3.363c-2.827.002-5.277-1.528-6.606-3.812-1.102-1.901-3.537-2.55-5.439-1.449a3.98 3.98 0 0 0-1.449 5.439v-.002zm104.491-3.989c-1.329 2.284-3.779 3.814-6.606 3.812a7.61 7.61 0 0 1-6.326-3.363 3.98 3.98 0 0 0-5.525-1.071 3.98 3.98 0 0 0-1.07 5.525 15.59 15.59 0 0 0 12.921 6.869c5.761 0 10.808-3.136 13.494-7.782a3.98 3.98 0 0 0-6.887-3.989h-.002z"/></svg></span><?php esc_html_e("Difficult or confusing to use.","eventprime-event-calendar-management");?></label>
                                        </div>
                                    </div>
                                    
                                    <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-3 ep-box-col-6">
                                        <div class="ep-deactive-feedback-box">
                                            <input id="ep-deactivate-feedback-plugin_broke_site" class="ep-deactivate-feedback-dialog-input ep-d-none" type="radio" name="ep_feedback_key" value="plugin_broke_site">
                                            <label for="ep-deactivate-feedback-plugin_broke_site" class="ep-deactivate-feedback-dialog-label ep-lh-0 ep-border ep-border-dark ep-rounded ep-p-3 ep-box-w-100 ep-di-flex ep-align-items-center"><span class="ep-feedback-emoji ep-mr-2"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#272525" xmlns:v="https://vecta.io/nano"><path d="M193.75 96.874h-6.765c-.002 24.893-10.081 47.399-26.393 63.717-16.317 16.312-38.822 26.391-63.716 26.393s-47.401-10.081-63.719-26.393c-16.312-16.318-26.39-38.824-26.391-63.717s10.08-47.399 26.391-63.717C49.475 16.845 71.981 6.767 96.876 6.765s47.399 10.08 63.716 26.391c16.312 16.318 26.391 38.824 26.393 63.717h6.765C193.748 43.37 150.38.001 96.876 0S.001 43.37 0 96.874s43.37 96.873 96.876 96.876c53.504-.003 96.873-43.372 96.874-96.876zM46.481 81.027a8.26 8.26 0 0 1 8.259-8.259 8.26 8.26 0 0 1 8.259 8.259 8.26 8.26 0 0 1-8.259 8.257 8.26 8.26 0 0 1-8.259-8.257zm84.273 0a8.26 8.26 0 0 1 8.257-8.259 8.26 8.26 0 0 1 8.259 8.259 8.26 8.26 0 0 1-8.259 8.257 8.26 8.26 0 0 1-8.257-8.257zm-76.059 61.364a46.14 46.14 0 0 1 16.659-19.959 45.7 45.7 0 0 1 25.522-7.733c9.457-.001 18.212 2.846 25.517 7.73s13.128 11.812 16.657 19.951c.743 1.714 2.736 2.501 4.451 1.757a3.38 3.38 0 0 0 1.757-4.449 52.9 52.9 0 0 0-19.104-22.881c-8.372-5.603-18.459-8.875-29.279-8.873s-20.914 3.271-29.285 8.876-15.049 13.54-19.104 22.889c-.743 1.714.045 3.706 1.759 4.449s3.706-.045 4.449-1.757zM46 55.595l21.629 13.099c1.598.968 3.677.457 4.645-1.14s.457-3.679-1.141-4.647l-21.63-13.098A3.382 3.382 0 0 0 46 55.595zm98.246-5.786l-21.63 13.099c-1.597.968-2.108 3.048-1.141 4.647s3.048 2.108 4.647 1.14l21.629-13.099a3.382 3.382 0 1 0-3.504-5.786z"/></svg></span><?php esc_html_e("The plugin broke my site","eventprime-event-calendar-management");?></label>
                                        </div>
                                    </div>
                                    
                                    <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-3 ep-box-col-6">
                                        <div class="ep-deactive-feedback-box">
                                          <input id="ep-deactivate-feedback-temporary_deactivation" class="ep-deactivate-feedback-dialog-input ep-d-none" type="radio" name="ep_feedback_key" value="temporary_deactivation">
                                          <label for="ep-deactivate-feedback-temporary_deactivation" class="ep-deactivate-feedback-dialog-label ep-lh-0 ep-border ep-border-dark ep-rounded ep-p-3 ep-box-w-100 ep-di-flex ep-align-items-center"><span class="ep-feedback-emoji ep-mr-2"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#272525" xmlns:v="https://vecta.io/nano"><path d="M193.75 96.876h-6.765c-.002 24.893-10.081 47.399-26.393 63.717-16.317 16.312-38.822 26.39-63.717 26.391s-47.399-10.08-63.717-26.391c-16.312-16.318-26.39-38.824-26.391-63.717s10.08-47.401 26.391-63.717C49.475 16.846 71.981 6.767 96.875 6.765s47.401 10.081 63.717 26.393 26.391 38.822 26.393 63.717h6.765C193.749 43.372 150.38.003 96.875 0 43.371.003.002 43.372 0 96.876s43.37 96.873 96.874 96.874 96.874-43.37 96.876-96.874zM46.481 75.115a8.26 8.26 0 0 1 8.259-8.259 8.26 8.26 0 0 1 8.259 8.259 8.26 8.26 0 0 1-8.259 8.259 8.26 8.26 0 0 1-8.259-8.259zm84.273 0a8.26 8.26 0 0 1 8.257-8.259 8.26 8.26 0 0 1 8.259 8.259 8.26 8.26 0 0 1-8.259 8.259 8.26 8.26 0 0 1-8.257-8.259zm13.157 36.403c-3.114 10.018-9.351 18.674-17.569 24.822s-18.403 9.782-29.467 9.784-21.242-3.636-29.465-9.784-14.456-14.804-17.569-24.822c-.556-1.784-2.451-2.781-4.234-2.225a3.38 3.38 0 0 0-2.225 4.234c3.548 11.409 10.635 21.243 19.978 28.232a55.78 55.78 0 0 0 33.517 11.13 55.78 55.78 0 0 0 33.518-11.13c9.339-6.988 16.43-16.823 19.979-28.232.554-1.784-.443-3.68-2.227-4.234s-3.679.441-4.234 2.225z"/><path d="M49.141 107.177l-9.585 4.588c-1.686.805-2.397 2.826-1.592 4.511s2.827 2.397 4.513 1.59l9.585-4.586c1.686-.807 2.397-2.827 1.592-4.511s-2.827-2.399-4.513-1.592zm92.548 6.103l9.585 4.586a3.38 3.38 0 0 0 4.511-1.59c.807-1.686.094-3.706-1.59-4.511l-9.586-4.588c-1.684-.807-3.704-.094-4.511 1.592a3.38 3.38 0 0 0 1.592 4.511z"/></svg></span><?php esc_html_e("It's a temporary deactivation","eventprime-event-calendar-management");?></label>
                                        </div>
                                    </div>
                                    
                                    <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-3 ep-box-col-6">
                                        <div class="ep-deactive-feedback-box">
                                            <input id="ep-deactivate-feedback-plugin_has_design_issue" class="ep-deactivate-feedback-dialog-input ep-d-none" type="radio" name="ep_feedback_key" value="plugin_has_design_issue">
                                            <label for="ep-deactivate-feedback-plugin_has_design_issue" class="ep-deactivate-feedback-dialog-label ep-lh-0 ep-border ep-border-dark ep-rounded ep-p-3 ep-box-w-100 ep-di-flex ep-align-items-center"><span class="ep-feedback-emoji ep-mr-2"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#272525" xmlns:v="https://vecta.io/nano"><path d="M193.748 96.876h-6.765c-.002 24.893-10.081 47.399-26.393 63.716-16.318 16.312-38.822 26.391-63.716 26.393s-47.401-10.081-63.719-26.393C16.843 144.275 6.765 121.77 6.762 96.876c.003-24.895 10.081-47.401 26.393-63.717C49.473 16.847 71.979 6.767 96.874 6.766s47.398 10.081 63.716 26.393c16.312 16.317 26.391 38.822 26.393 63.717h6.765C193.746 43.372 150.376.002 96.874 0S0 43.372-.002 96.876s43.37 96.873 96.876 96.874 96.873-43.372 96.874-96.874zM47.791 94.455l17.921-9.709a4.23 4.23 0 0 0 2.214-3.719 4.24 4.24 0 0 0-2.214-3.719L47.791 67.6a4.23 4.23 0 0 0-5.732 1.703 4.23 4.23 0 0 0 1.703 5.732l11.059 5.992-11.059 5.992a4.23 4.23 0 0 0-1.703 5.732 4.23 4.23 0 0 0 5.732 1.703zm102.192-7.436l-11.059-5.992 11.059-5.992a4.23 4.23 0 0 0 1.705-5.732 4.23 4.23 0 0 0-5.732-1.703l-17.923 9.709c-1.364.74-2.214 2.168-2.214 3.719a4.23 4.23 0 0 0 2.214 3.719l17.923 9.709a4.23 4.23 0 0 0 5.732-1.703 4.23 4.23 0 0 0-1.703-5.732h-.002zm.225 39.816a7.9 7.9 0 0 1-2.958 4.136c-1.402 1.009-3.161 1.609-5.137 1.611a8.41 8.41 0 0 1-5.161-1.756 8.48 8.48 0 0 1-2.993-4.435l-3.259.904 1.113 3.195.021-.008a3.37 3.37 0 0 0 1.939-1.734c.39-.815.439-1.746.135-2.598-1.054-2.951-2.983-5.473-5.476-7.262a15.2 15.2 0 0 0-8.867-2.848c-3.421-.002-6.609 1.14-9.15 3.056s-4.467 4.608-5.419 7.738c-.522 1.711-1.558 3.122-2.959 4.136s-3.161 1.609-5.137 1.611a8.41 8.41 0 0 1-5.161-1.756c-1.431-1.105-2.499-2.657-2.991-4.435l-.086-.266a15.57 15.57 0 0 0-5.457-7.2c-2.474-1.797-5.536-2.884-8.832-2.884a15.17 15.17 0 0 0-9.15 3.056c-2.539 1.916-4.467 4.608-5.42 7.738a7.9 7.9 0 0 1-2.958 4.136c-1.402 1.009-3.161 1.609-5.137 1.611a8.41 8.41 0 0 1-5.161-1.756c-1.436-1.103-2.499-2.657-2.993-4.435a3.382 3.382 0 1 0-6.517 1.808 15.22 15.22 0 0 0 5.379 7.983 15.17 15.17 0 0 0 9.292 3.165c3.365.002 6.535-1.041 9.094-2.891a14.67 14.67 0 0 0 5.474-7.654 8.48 8.48 0 0 1 3.015-4.301 8.4 8.4 0 0 1 5.081-1.695c1.805.002 3.464.586 4.858 1.595a8.81 8.81 0 0 1 3.082 4.064l3.174-1.17-3.26.904c.895 3.222 2.813 6.003 5.38 7.983a15.17 15.17 0 0 0 9.292 3.165c3.365.002 6.535-1.041 9.094-2.891 2.561-1.843 4.521-4.507 5.476-7.654a8.48 8.48 0 0 1 3.013-4.301c1.418-1.068 3.161-1.694 5.081-1.695a8.4 8.4 0 0 1 4.922 1.579 8.5 8.5 0 0 1 3.048 4.039l3.187-1.137-1.113-3.195-.022.008a3.38 3.38 0 0 0-2.147 4.099c.895 3.222 2.813 6.003 5.38 7.983a15.17 15.17 0 0 0 9.292 3.165c3.365.002 6.535-1.041 9.094-2.891a14.67 14.67 0 0 0 5.474-7.654c.543-1.788-.466-3.677-2.252-4.22s-3.677.465-4.22 2.252h-.002z"/></svg></span><?php esc_html_e("It has design or layout issues.","eventprime-event-calendar-management");?></label>
                                        </div>
                                    </div>
                                    
                                      <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-3 ep-box-col-6">
                                        <div class="ep-deactive-feedback-box">
                                            <input id="ep-deactivate-feedback-plugin_missing-documentation" class="ep-deactivate-feedback-dialog-input ep-d-none" type="radio" name="ep_feedback_key" value="plugin_missing-documentation">
                                            <label for="ep-deactivate-feedback-plugin_missing-documentation" class="ep-deactivate-feedback-dialog-label ep-lh-0 ep-border ep-border-dark ep-rounded ep-p-3 ep-box-w-100 ep-di-flex ep-align-items-center"><span class="ep-feedback-emoji ep-mr-2"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#272525" xmlns:v="https://vecta.io/nano"><path d="M46.483 75.116a8.26 8.26 0 0 1 8.259-8.257 8.26 8.26 0 0 1 8.257 8.257 8.26 8.26 0 0 1-8.257 8.259 8.26 8.26 0 0 1-8.259-8.259zm84.276 0c0-4.561 3.693-8.257 8.262-8.257 4.553 0 8.246 3.696 8.246 8.257s-3.693 8.259-8.246 8.259a8.26 8.26 0 0 1-8.262-8.259zm62.992 21.761h-6.766c0 24.894-10.077 47.402-26.394 63.719s-38.811 26.392-63.708 26.394-47.406-10.082-63.724-26.394S6.768 121.772 6.765 96.877c.003-24.894 10.082-47.401 26.394-63.719C49.477 16.846 71.984 6.767 96.883 6.766s47.391 10.08 63.708 26.392c16.317 16.319 26.394 38.825 26.394 63.719h6.766C193.751 43.372 150.387.002 96.883 0 43.372.002.002 43.372.001 96.877s43.372 96.876 96.882 96.879c53.504-.003 96.868-43.373 96.868-96.879zM71.732 125.732h6.764c0-8.171 2.292-15.515 5.779-20.614 1.751-2.553 3.773-4.54 5.906-5.858 2.149-1.32 4.362-1.993 6.702-1.996 2.324.003 4.553.677 6.686 1.996 3.2 1.972 6.161 5.475 8.278 10.082 2.133 4.599 3.407 10.263 3.407 16.39.016 8.171-2.276 15.515-5.779 20.614-1.751 2.553-3.773 4.54-5.906 5.858s-4.362 1.993-6.686 1.998c-2.34-.005-4.553-.677-6.702-1.998-3.2-1.971-6.161-5.475-8.278-10.082-2.117-4.599-3.407-10.263-3.407-16.39h-6.764c.008 9.421 2.584 18.003 6.971 24.431 2.181 3.211 4.855 5.89 7.928 7.794 3.056 1.904 6.575 3.013 10.252 3.01 3.677.003 7.18-1.106 10.252-3.01 4.601-2.864 8.294-7.439 10.857-12.998 2.579-5.569 4.028-12.161 4.028-19.227 0-9.419-2.579-18.003-6.957-24.431-2.197-3.211-4.855-5.888-7.928-7.794s-6.575-3.012-10.252-3.009c-3.677-.003-7.195 1.106-10.252 3.009-4.617 2.864-8.294 7.439-10.87 13-2.568 5.567-4.028 12.161-4.029 19.226z"/></svg></span><?php esc_html_e("Missing documentation.","eventprime-event-calendar-management");?></label>
                                        </div>
                                    </div>
                                    
                                    <div class="ep-deactivate-feedback-dialog-input-wrapper ep-mb-3 ep-box-col-6">
                                        <div class="ep-deactive-feedback-box">
                                            <input id="ep-deactivate-feedback-other" class="ep-deactivate-feedback-dialog-input ep-d-none" type="radio" name="ep_feedback_key" value="other_reasons">
                                            <label for="ep-deactivate-feedback-other" class="ep-deactivate-feedback-dialog-label ep-lh-0 ep-border ep-border-dark ep-rounded ep-p-3 ep-box-w-100 ep-di-flex ep-align-items-center"><span class="ep-feedback-emoji ep-mr-2"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 194 194" fill-rule="nonzero" stroke-linejoin="round" stroke-miterlimit="2" fill="#272525" xmlns:v="https://vecta.io/nano"><path d="M46.482 75.118a8.26 8.26 0 0 1 8.259-8.257 8.26 8.26 0 0 1 8.257 8.257c0 4.561-3.696 8.254-8.257 8.254s-8.259-3.693-8.259-8.254zm84.275 0c0-4.561 3.693-8.257 8.262-8.257a8.27 8.27 0 0 1 8.262 8.257c0 4.561-3.709 8.254-8.262 8.254-4.569 0-8.262-3.693-8.262-8.254zm62.993 21.77h-6.766c0 24.882-10.077 47.392-26.394 63.709s-38.811 26.394-63.709 26.394-47.404-10.077-63.722-26.394S6.767 121.77 6.765 96.888c.002-24.902 10.082-47.409 26.394-63.728C49.477 16.847 71.983 6.769 96.881 6.767s47.392 10.08 63.709 26.393c16.317 16.319 26.394 38.825 26.394 63.728h6.766c0-53.514-43.364-96.885-96.869-96.886S.001 43.374 0 96.888c.002 53.505 43.372 96.869 96.881 96.869s96.869-43.364 96.869-96.869zM48.612 125.781h96.536a3.38 3.38 0 0 0 3.375-3.375c0-1.863-1.512-3.391-3.375-3.391H48.612c-1.869 0-3.383 1.528-3.383 3.391a3.38 3.38 0 0 0 3.383 3.375z"/></svg></span><?php esc_html_e("Other reasons.","eventprime-event-calendar-management");?></label>
                                        </div>
                                    </div>
                                                           
                                </div>
                                </div>
                         
                        </div>
                    
                    
                    <!-- feature_not_available Feedback Form 1 -->
                    <div class="ep-box-row ep-feedback-form-feature-box" id="feature_not_available" data-condition="feature_not_available" style="display: none;">
                        <div class="ep-box-col-12">
                            <div class="ep-feedback-form ep-feedback-form-input-bg">
                                <div class="ep-p-4">
                                    <lable class="ep-inline-block ep-pb-2">Please tell us about the missing feature:</lable>
                                    <textarea id="ep-plugin-feedback" name="ep-plugin-feedback" class="ep-plugin-feedback-textarea ep-box-w-100" rows="2" cols="50"></textarea>
                                    <div class="ep-pt-1 ep-plugin-feedback-email-check ep-d-flex ep-align-items-center ep-mt-1">
                                        <input type="checkbox" class="ep-my-0 ep-mr-2" id="ep-inform-email" name="ep-inform-email">
                                        <label for="ep-inform-email" class="ep-text-small"> Create a support ticket to request addition of this feature.</label>
                                    </div>
                                    <div class="ep-feedback-user-email ep-mt-2" style="display: none">
                                        <input type="email" name="ep_user_support_email" id="ep_user_support_email" value="<?php echo esc_html( $ep_admin_email ); ?>" placeholder="Your Email">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- feature_not_available Feedback Form 1 End -->
                    
                                  
                    <!-- feature_not_working Feedback Form 2 -->
                    
                    <div class="ep-box-row ep-feedback-form-feature-box" id="feature_not_working" data-condition="feature_not_working" style="display: none;">
                        <div class="ep-box-col-12">
                            <div class="ep-feedback-form ep-feedback-form-input-bg">
                                <div class="ep-p-4">
                                    <lable class="ep-inline-block ep-pb-2">Please tell us about the broken feature (optional)</lable>
                                    <textarea id="ep-plugin-feedback" name="ep-plugin-feedback" class="ep-plugin-feedback-textarea ep-box-w-100" rows="2" cols="50"></textarea>
                                    <div class="ep-pt-1 ep-plugin-feedback-email-check ep-d-flex ep-align-items-center ep-mt-1">
                                        <input type="checkbox" class="ep-my-0 ep-mr-2" id="ep-inform-email" name="ep-inform-email">
                                    <label for="ep-inform-email" class="ep-text-small">  Also create support ticket.</label>
                                    </div>
                                    <div class="ep-feedback-user-email ep-mt-2" style="display: none">
                                       <input type="email" name="ep_user_support_email" id="ep_user_support_email" value="<?php echo esc_html( $ep_admin_email ); ?>" placeholder="Your Email">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- feature_not_working Feedback Form 2 End --->
                    
                    
                    <!-- plugin_difficult-to-use Feedback Form 3 -->
                    
                    <div class="ep-box-row ep-feedback-form-feature-box" id="plugin_difficult-to-use" data-condition="plugin_difficult-to-use" style="display: none;">
                        <div class="ep-box-col-12">
                            <div class="ep-feedback-form ep-feedback-form-input-bg">
                                <div class="ep-p-4">
                                    <lable class="ep-inline-block ep-pb-2">Please tell us which part of the plugin was confusing (optional)</lable>
                                    <textarea id="ep-plugin-feedback" name="ep-plugin-feedback" class="ep-plugin-feedback-textarea ep-box-w-100" rows="2" cols="50"></textarea>
                              
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- plugin_difficult-to-use Feedback Form 3 End --->
                    
                    
                    <!-- plugin_broke_site Feedback Form 4 -->
                    
                    <div class="ep-box-row ep-feedback-form-feature-box" id="plugin_broke_site" data-condition="plugin_broke_site" style="display: none;">
                        <div class="ep-box-col-12">
                            <div class="ep-feedback-form ep-feedback-form-input-bg">
                                <div class="ep-p-4">
                                    <lable class="ep-inline-block ep-pb-2">Please paste any errors or warnings you see (optional)</lable>
                                    <textarea id="ep-plugin-feedback" name="ep-plugin-feedback" class="ep-plugin-feedback-textarea ep-box-w-100" rows="2" cols="50"></textarea>
                                    <div class="ep-pt-1 ep-plugin-feedback-email-check ep-d-flex ep-align-items-center ep-mt-1">
                                        <input type="checkbox" class="ep-my-0 ep-mr-2" id="ep-inform-email" name="ep-inform-email">
                                    <label for="ep-inform-email" class="ep-text-small">  Also create support ticket </label>
                                    </div>
                                    <div class="ep-feedback-user-email ep-mt-2" style="display: none">
                                        <input type="email" name="ep_user_support_email" id="ep_user_support_email" value="<?php echo esc_html( $ep_admin_email ); ?>" placeholder="Your Email">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- plugin_broke_site Feedback Form 4 End --->
                    
                    
                    
                    <!-- temporary_deactivation Feedback Form 5 -->
                    
                    <div class="ep-box-row ep-feedback-form-feature-box" id="temporary_deactivation" data-condition="temporary_deactivation" style="display: none;">
          
                    </div>
                    
                    <!-- temporary_deactivationg Feedback Form 5 End --->
                    
                    
                    
                    
                    <!-- plugin_has_design_issue Feedback Form 6 -->
                    
                    <div class="ep-box-row ep-feedback-form-feature-box" id="plugin_has_design_issue" data-condition="plugin_has_design_issue" style="display: none;">
                        <div class="ep-box-col-12">
                            <div class="ep-feedback-form ep-feedback-form-input-bg">
                                <div class="ep-p-4">
                                    <lable class="ep-inline-block ep-pb-2">Please tell us which page had design issues (optional)</lable>
                                    <textarea id="ep-plugin-feedback" name="ep-plugin-feedback" class="ep-plugin-feedback-textarea ep-box-w-100" rows="2" cols="50"></textarea>
                                    <div class="ep-pt-1 ep-plugin-feedback-email-check ep-d-flex ep-align-items-center ep-mt-1">
                                        <input type="checkbox" class="ep-my-0 ep-mr-2" id="ep-inform-email" name="ep-inform-email">
                                        <label for="ep-inform-email" class="ep-text-small">  Also create support ticket </label>
                                    </div>
                                    <div class="ep-feedback-user-email ep-mt-2" style="display: none">
                                        <input type="email" name="ep_user_support_email" id="ep_user_support_email" value="<?php echo esc_html( $ep_admin_email ); ?>" placeholder="Your Email">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- plugin_has_design_issue Feedback Form 6 End --->
                    

                    <!-- plugin_missing-documentation Feedback Form 7 -->

                    <div class="ep-box-row ep-feedback-form-feature-box" id="plugin_missing-documentation" data-condition="plugin_missing-documentation" style="display: none;">
                        <div class="ep-box-col-12">
                            <div class="ep-feedback-form ep-feedback-form-input-bg">
                                <div class="ep-p-4">
                                    <lable class="ep-inline-block ep-pb-2">Please tell us which feature lacked documentation (optional)</lable>
                                    <textarea id="ep-plugin-feedback" name="ep-plugin-feedback" class="ep-plugin-feedback-textarea ep-box-w-100" rows="2" cols="50"></textarea>
                                    <div class="ep-pt-1 ep-plugin-feedback-email-check ep-d-flex ep-align-items-center ep-mt-1">
                                            <input type="checkbox" class="ep-my-0 ep-mr-2" id="ep-inform-email" name="ep-inform-email" >
                                            <label for="ep-inform-email" class="ep-text-small">  Also create support ticket </label>
                                    </div>
                                    <div class="ep-feedback-user-email ep-mt-2" style="display: none">
                                        <input type="email" name="ep_user_support_email" id="ep_user_support_email" value="<?php echo esc_html( $ep_admin_email ); ?>" placeholder="Your Email">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- plugin_missing-documentation Feedback Form 7 End --->
                    
                    
                    <!-- other_reasons Feedback Form 8 -->

                    <div class="ep-box-row ep-feedback-form-feature-box" id="other_reasons" data-condition="other_reasons" style="display: none;">
                        <div class="ep-box-col-12">
                            <div class="ep-feedback-form ep-feedback-form-input-bg">
                                <div class="ep-p-4">
                                    <lable class="ep-inline-block ep-pb-2">You can add more details about the issue here (optional)</lable>
                                    <textarea id="ep-plugin-feedback" name="ep-plugin-feedback" class="ep-plugin-feedback-textarea ep-box-w-100" rows="2" cols="50"></textarea>
                                 
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- other_reasons Feedback Form 8 End --->
                    
                    
                    
                    <!-- Modal Wrap Ends: -->
                    <div class="ep-modal-footer ep-d-flex ep-items-end ep-justify-content-between ep-py-4" id="ep_modal_buttonset">
                        <a href="javascript:void(0);" class="ep-mr-3 button ep-feedback-skip-button" id="ep_save_plugin_feedback_direct_deactivation" title="<?php echo esc_attr( 'Skip & Deactivate', 'eventprime-event-calendar-management' ); ?>"><?php esc_html_e('Skip & Deactivate', 'eventprime-event-calendar-management'); ?></a>
                        <div class="ep-plugin-deactivation-message ep-mr-3 ep-text-danger" style="display:none"></div>
                        <div class="ep-plugin-deactivation-loader ep-mr-3 ep-text-danger" style="display:none">
                            <span class="spinner is-active"></span>
                            <span class=""><?php esc_html_e( 'Deactivating EventPrime...', 'eventprime-event-calendar-management' ); ?></span>
                        </div>
                        <button type="button" class="button button-primary button-large" id="ep_save_plugin_feedback_on_deactivation" title="<?php echo esc_attr( 'Submit & Deactivate', 'eventprime-event-calendar-management' ); ?>"><?php esc_html_e('Submit & Deactivate', 'eventprime-event-calendar-management'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>