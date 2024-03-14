<?php
$data = get_option( '_bwf_fb_templates' );
if ( ! is_array( $data ) || count( $data ) === 0 ) { ?>
    <div class="empty_template_error">
        <div class="bwf-c-global-error" style="display: flex; align-items: center; justify-content: center; height: 60vh;">
            <div class="bwf-c-global-error-center" style="text-align: center; background-color: rgb(255, 255, 255); width: 500px; padding: 50px;">
                <span class="dashicon dashicons dashicons-warning" style="font-size: 70px; height: 70px; width: 70px;"></span>
                <p><?php esc_html_e( 'It seems there are some technical difficulties. Press F12 to open console. Take Screenshot of the error and send it to support.', 'funnel-builder' ) ?></p>
                <a herf="#" class="button button-primary is-primary"><span class="dashicon dashicons dashicons-image-rotate"></span>&nbsp;<?php esc_html_e( 'Refresh', 'funnel-builder' ) ?></a>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="wfacp_tab_container" v-if="'no'==template_active" style="display: block">
        <div class="wfacp_template_header">
            <div class="wffn_template_header_item" v-for="(templates,type) in designs" v-if="(current_template_type==type) && (wfacp.tools.ol(templates)>0)">
                <div class="wfacp_filter_container" v-if="undefined!==wfacp_data.design.design_type_data[type]['filters']">
                    <div v-for="(name,i) in wfacp_data.design.design_type_data[type]['filters']" :data-filter-type="i" v-bind:class="'wfacp_filter_container_inner'+(1==i?' wfacp_selected_filter':'')">
                        <div class="wfacp_template_filters">{{name}}</div>
                    </div>
                </div>
            </div>
            <div class="wfacp_template_header_item wfacp_template_editor_wrap wfacp_ml_auto">
                <div class="wfacp_template_editor">
                    <span class="wfacp_editor_field_label"><?php _e( 'Page Builder:', 'funnel-builder' ) ?></span>
                    <div class="wfacp_editor_field wfacp_field_select_dropdown">
                    <span class="wfacp_editor_label wfacp_field_select_label" v-on:click="show_template_dropdown">
                        {{design_types[current_template_type]}}
                        <i class="dashicons dashicons-arrow-down-alt2"></i>
                    </span>
                        <div class="wfacp_field_dropdown wfacp-hide">
                            <div class="wfacp_dropdown_header">
                                <label class="wfacp_dropdown_header_label"><?php _e( 'Select Page Builder', 'funnel-builder' ) ?></label>
                            </div>
                            <div class="wfacp_dropdown_body">
                                <label v-for="(design_name,type) in design_types" v-if="wfacp.tools.ol(designs[type])>0" v-on:click="setTemplateType(type)" class="wfacp_dropdown_fields">
                                    <input type="radio" name="wfacp_tabs" v-bind:value="type" :checked="current_template_type==type"/>
                                    <span>{{design_name}}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <section id="wfacp_content1" class="wfacp_tab-content" style="display: block" v-for="(templates,type) in designs" v-if="(current_template_type==type) && (wfacp.tools.ol(templates)>0)">
            <div class="wfacp_pick_template">
                <div v-for="(template,slug) in templates" :data-slug="slug" :data-steps="template.no_steps" class="wfacp_temp_card wfacp_single_template" v-bind:class="{ wfacp_build_from_scratch: template.build_from_scratch }">
                    <div class="wfacp_template_sec wfacp_build_from_scratch" v-if="template.build_from_scratch">
                        <div class="wfacp_template_sec_design">
                            <div class="wfacp_temp_overlay">
                                <div class="wfacp_temp_middle_align">
                                    <div class="wfacp_add_tmp_se">
                                        <a href="javascript:void(0)" v-on:click="triggerImport(template,slug,type,$event)">
                                            <svg viewBox="0 0 24 24" width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect fill="white"></rect>
                                                <path d="M12 2C6.48566 2 2 6.48566 2 12C2 17.5143 6.48566 22 12 22C17.5143 22 22 17.5136 22 12C22 6.48645 17.5143 2 12 2ZM12 20.4508C7.34082 20.4508 3.54918 16.66 3.54918 12C3.54918 7.34004 7.34082 3.54918 12 3.54918C16.6592 3.54918 20.4508 7.34004 20.4508 12C20.4508 16.66 16.66 20.4508 12 20.4508Z" fill="#000000"></path>
                                                <path d="M15.873 11.1557H12.7746V8.05734C12.7746 7.62976 12.4284 7.28273 12 7.28273C11.5716 7.28273 11.2254 7.62976 11.2254 8.05734V11.1557H8.12703C7.69867 11.1557 7.35242 11.5027 7.35242 11.9303C7.35242 12.3579 7.69867 12.7049 8.12703 12.7049H11.2254V15.8033C11.2254 16.2309 11.5716 16.5779 12 16.5779C12.4284 16.5779 12.7746 16.2309 12.7746 15.8033V12.7049H15.873C16.3013 12.7049 16.6476 12.3579 16.6476 11.9303C16.6476 11.5027 16.3013 11.1557 15.873 11.1557Z" fill="#000000"></path>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="wfacp_p wfacp_import_template" v-on:click="triggerImport(template,slug,type,$event)">
                                        <span class="wfacp_import_text"><?php esc_html_e( 'Start from scratch', 'funnel-builder' ); ?></span>
                                        <span class="wfacp_importing_text"> <?php esc_html_e( 'Importing...', 'funnel-builder' ) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wfacp_template_sec" v-else>
                        <div class="wfacp_template_sec_ribbon wfacp_pro" v-if="`yes`===template.pro"><?php _e( 'PRO', 'woofunnels-aero-checkout' ); ?></div>
                        <div class="wfacp_template_sec_design">  <!-- USE THIS CLASS FOR PRO   and Use This Template btn will be Get Pro -->
                            <img v-bind:src="template.thumbnail" class="wfacp_img_temp">
                            <div class="wfacp_temp_overlay">
                                <div class="wfacp_temp_middle_align">
                                    <div class="wfacp_pro_template" v-if="template.pro && `no` === template.license_exist">
                                        <a href="javascript:void(0)" v-on:click="triggerPreview(template,slug,type)" class="wfacp_steps_btn wfacp_steps_btn_success"><?php _e( 'Preview', 'woofunnels-aero-checkout' ) ?></a>
                                        <a href="javascript:void(0)" v-on:click="wfacp.show_pro_message('custom_heading', '<?php _e( 'Templates', 'funnel-builder' ); ?>')" class="wfacp_steps_btn wfacp_steps_btn_danger"><?php _e( 'Import', 'woofunnels-aero-checkout' ) ?></a>
                                    </div>
                                    <div class="wfacp_pro_template" v-else>
                                        <a href="javascript:void(0)" v-on:click="triggerPreview(template,slug,type)" class="wfacp_steps_btn wfacp_steps_btn_success"><?php _e( 'Preview', 'woofunnels-aero-checkout' ) ?></a>
                                        <a href="javascript:void(0)" class="wfacp_steps_btn wfacp_import_template wfacp_btn_blue" v-on:click="triggerImport(template,slug,type,$event)"><span class="wfacp_import_text"><?php _e( 'Import', 'woofunnels-aero-checkout' ) ?></span><span class="wfacp_importing_text"><?php _e( 'Importing...', 'woofunnels-aero-checkout' ) ?></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wfacp_template_sec_meta" v-if="!template.build_from_scratch">
                            <div class="wfacp_template_meta_left">
                                <span>{{template.name}}</span>
                            </div>
                            <div class="wfacp_template_meta_right"></div>
                        </div>
                    </div>
                    <div v-if="true===ShouldPreview(slug,type)" class="wfacp-preview-overlay">
                        <div class="wfacp_template_preview_wrap">
                            <div class="wfacp_template_preview_header">
                                <div class="bwf_template_logo_title">
                                    <img src="<?php echo esc_url( plugin_dir_url( WooFunnel_Loader::$ultimate_path ) . 'woofunnels/assets/img/menu/funnelkit-logo.svg' ); ?>" alt="Funnel Builder for WordPress" width="148" class="bwf-brand-logo-only">
                                    <div class="bwf_preview_template_title">{{template.name}}</div>
                                </div>
                                <div class="wfacp_template_viewport">
                                    <div class="wfacp_template_viewport_inner">
                                    <span class="wfacp_viewport_icons active" v-on:click="setViewport('desktop', $event)" title="Desktop Viewport">
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.8128 0.5H1.18783C0.900326 0.5 0.666992 0.733333 0.666992 1.02083V11.4375C0.666992 11.725 0.900326 11.9583 1.18783 11.9583H8.47949V14.0417H6.39616C6.10866 14.0417 5.87533 14.275 5.87533 14.5625C5.87533 14.85 6.10866 15.0833 6.39616 15.0833H11.6045C11.892 15.0833 12.1253 14.85 12.1253 14.5625C12.1253 14.275 11.892 14.0417 11.6045 14.0417H9.52116V11.9583H16.8128C17.1003 11.9583 17.3337 11.725 17.3337 11.4375V1.02083C17.3337 0.733333 17.1003 0.5 16.8128 0.5ZM16.292 10.9167H1.70866V1.54167H16.292V10.9167Z" fill="#fff"></path></svg>
                                    </span>
                                        <span class="wfacp_viewport_icons" v-on:click="setViewport('tablet', $event)" title="Tablet Viewport">
                                        <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.1696 0H1.48758C0.838506 0 0.319336 0.471577 0.319336 1.06108V14.9389C0.319336 15.5285 0.838539 16 1.48758 16H13.1511C13.8002 16 14.3193 15.5284 14.3193 14.9389V1.06108C14.3193 0.471547 13.8001 0 13.1696 0H13.1696ZM7.32861 0.488359C7.56971 0.488359 7.75506 0.656828 7.75506 0.875696C7.75506 1.09468 7.56958 1.26303 7.32861 1.26303C7.08751 1.26303 6.90215 1.09456 6.90215 0.875696C6.90215 0.673627 7.08751 0.488359 7.32861 0.488359ZM7.32861 15.0904C6.90215 15.0904 6.5498 14.7704 6.5498 14.383C6.5498 13.9957 6.90215 13.6757 7.32861 13.6757C7.75506 13.6757 8.10741 13.9957 8.10741 14.383C8.10741 14.7872 7.75506 15.0904 7.32861 15.0904ZM12.9286 12.2104C12.9286 12.5304 12.6505 12.783 12.2982 12.783H2.35913C2.00678 12.783 1.7287 12.5304 1.7287 12.2104L1.72857 2.35777C1.72857 2.03774 2.00666 1.78517 2.359 1.78517H12.298C12.6504 1.78517 12.9285 2.03775 12.9285 2.35777L12.9286 12.2104Z" fill="#353030"></path></svg>
                                    </span>
                                        <span class="wfacp_viewport_icons" v-on:click="setViewport('mobile', $event)" title="Mobile Viewport">
                                        <svg width="12" height="18" viewBox="0 0 12 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.167 0.666504H1.50033C0.766988 0.666504 0.166992 1.2665 0.166992 1.99984V15.9998C0.166992 16.7365 0.766988 17.3332 1.50033 17.3332H10.167C10.9036 17.3332 11.5003 16.7365 11.5003 15.9998V1.99984C11.5003 1.2665 10.9036 0.666504 10.167 0.666504ZM5.83366 16.5132C5.46033 16.5132 5.15365 16.2098 5.15365 15.8332C5.15365 15.4565 5.46033 15.1532 5.83366 15.1532C6.21033 15.1532 6.51365 15.4565 6.51365 15.8332C6.51365 16.2098 6.21033 16.5132 5.83366 16.5132ZM10.167 13.9998C10.167 14.1832 10.0203 14.3332 9.83366 14.3332H1.83366C1.65031 14.3332 1.50033 14.1832 1.50033 13.9998V2.33317C1.50033 2.14984 1.65031 1.99984 1.83366 1.99984H9.83366C10.0203 1.99984 10.167 2.14984 10.167 2.33317V13.9998Z" fill="#353030"></path></svg>
                                    </span>
                                    </div>
                                </div>
                                <div class="bwf-t-center">
                                    <a href="javascript:void(0)" class="button button-primary wfacp_import_preview_template" v-if="template.pro && `no` === template.license_exist" v-on:click="wfacp.show_pro_message( 'custom_heading', '<?php _e( 'Templates', 'funnel-builder' ); ?>')">
                                    <span class="wfacp_import_text">
                                        <?php esc_html_e( 'Import This Template', 'funnel-builder' ) ?>
                                    </span>
                                    </a>
                                    <a href="javascript:void(0)" class="button button-primary wfacp_import_preview_template wfacp_import_template" v-on:click="triggerImport(template,slug,type,$event)" v-else>
                                    <span class="wfacp_import_text">
                                        <?php esc_html_e( 'Import This Template', 'funnel-builder' ) ?>
                                    </span>
                                        <span class="wfacp_importing_text">
                                        <?php esc_html_e( 'Importing...', 'funnel-builder' ) ?>
                                    </span>
                                    </a>
                                </div>
                                <div class="wfacp_template_preview_close">
                                    <button type="button" v-on:click="previewClosed()" class="components-button">
                                        <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="14" height="14">
                                            <path d="M9.46702 7.99987L15.6972 1.76948C16.1027 1.36422 16.1027 0.708964 15.6972 0.303702C15.292 -0.10156 14.6367 -0.10156 14.2315 0.303702L8.00106 6.5341L1.77084 0.303702C1.36539 -0.10156 0.710327 -0.10156 0.305065 0.303702C-0.100386 0.708964 -0.100386 1.36422 0.305065 1.76948L6.53528 7.99987L0.305065 14.2303C-0.100386 14.6355 -0.100386 15.2908 0.305065 15.696C0.507032 15.8982 0.772588 15.9998 1.03795 15.9998C1.30332 15.9998 1.56869 15.8982 1.77084 15.696L8.00106 9.46565L14.2315 15.696C14.4336 15.8982 14.699 15.9998 14.9643 15.9998C15.2297 15.9998 15.4951 15.8982 15.6972 15.696C16.1027 15.2908 16.1027 14.6355 15.6972 14.2303L9.46702 7.99987Z" fill="#353030"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="wfacp_template_preview_content">
                                <div class="wfacp_template_preview_inner wfacp_funnel_preview">
                                    <div class="woocommerce-web-preview wfacp_template_preview_frame">
                                        <div class="woocommerce-web-preview__iframe-wrapper">
                                            <div class="wfacp_global_loader">
                                                <div class="spinner"></div>
                                            </div>
                                            <iframe v-bind:src="getPreviewUrl(template.prevslug, type)" width="100%" height="100%"></iframe>
                                        </div>
                                    </div>
                                </div>
                                <div class="wfacp_template_preview_sidebar">
                                    <div v-for="(template,slug) in templates" v-on:data-slug="slug" v-if="! template.build_from_scratch && ((`undefined`=== typeof currentStepsFilter && template.no_steps === '1' ) ||(`undefined`!==typeof currentStepsFilter) && ( template.no_steps === currentStepsFilter))">
                                        <label class="wfacp_template_page_options" v-bind:pre_slug="template.slug" v-on:click="triggerPreview(template,slug,type)">
                                            <div class="wfacp_preview_thumbnail">
                                                <img v-bind:src="template.thumbnail">
                                            </div>
                                            <span class="wfacp_template_name">{{template.name}}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php } ?>