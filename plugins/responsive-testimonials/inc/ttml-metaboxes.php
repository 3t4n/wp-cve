<?php
/**
 * Register metaboxes for Testimonials.
 */
function ttml_register_group_metabox() {

        /* Custom sanitization call-back to allow HTML in most fields */
        function ttml_html_allowed_sani_cb($content) {
            return wp_kses_post( $content );
        }

        $prefix = '_ttml_';
        $main_group = new_cmb2_box( array(
            'id' => $prefix . 'testimonial_metabox',
            'title' => '<span style="font-weight:400;">'.__( 'Manage Testimonials', 'responsive-testimonials' ).'</span> <a target="_blank" class="wpd_free_pro" title="'.__( 'Unlock more features with Responsive Testimonials PRO!', 'responsive-testimonials' ).'" href="http://wpdarko.com/items/responsive-testimonials-pro"><span style="color:#8a7463;font-size:15px; font-weight:400; float:right; padding-right:14px;"><span class="dashicons dashicons-lock"></span> '.__( 'Free version', 'responsive-testimonials' ).'</span></a>',
            'object_types' => array( 'ttml' ),
            'priority' => 'high',
        ));

        $ttml_group = $main_group->add_field( array(
            'id' => $prefix . 'head',
            'type' => 'group',
            'options' => array(
                'group_title' => __( 'Testimonial {#}', 'responsive-testimonials' ),
                'add_button' => __( 'Add another testimonial', 'responsive-testimonials' ),
                'remove_button' => __( 'Remove testimonial', 'responsive-testimonials' ),
                'sortable' => true,
                'single' => false,
            ),
        ));

            $main_group->add_group_field( $ttml_group, array(
                'name' => __( 'Testimonial details', 'responsive-testimonials' ),
                'id' => $prefix . 'testimonial_header',
                'type' => 'title',
                'row_classes' => 'de_hundred de_heading',
            ));

                $main_group->add_group_field( $ttml_group, array(
                    'name' => __( 'Testimonial text', 'responsive-testimonials' ).' <a class="wpd_tooltip" title="'.__( 'Basic HTML allowed', 'responsive-testimonials' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
            				'id' => $prefix . 'text',
            				'type' => 'textarea',
                    'attributes'  => array(
                        'rows' => 5,
                    ),
                    'row_classes' => 'de_hundred de_textarea de_input',
                    'sanitization_cb' => 'ttml_html_allowed_sani_cb',
                ));

                $main_group->add_group_field( $ttml_group, array(
                    'name' => __( 'Author', 'responsive-testimonials' ),
                    'id' => $prefix . 'author',
                    'type' => 'text',
                    'row_classes' => 'de_first de_fifty de_text de_input',
                    'sanitization_cb' => 'ttml_html_allowed_sani_cb',
                ));

                $main_group->add_group_field( $ttml_group, array(
                    'name' => __( 'Job/company', 'responsive-testimonials' ),
                    'id' => $prefix . 'job',
                    'type' => 'text',
                    'row_classes' => 'de_fifty de_text de_input',
                    'sanitization_cb' => 'ttml_html_allowed_sani_cb',
                ));

                $main_group->add_group_field( $ttml_group, array(
                    'name' => __( 'Author\'s photo', 'responsive-testimonials' ),
                    'id' => $prefix . 'author_styling_header',
                    'type' => 'title',
                    'row_classes' => 'de_hundred de_heading',
                ));

                $main_group->add_group_field( $ttml_group, array(
                    'name' => __( 'Upload photo', 'responsive-testimonials' ).' <a class="wpd_tooltip" title="'.__( 'Recommended:', 'responsive-testimonials' ).' 250x250px"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
                    'id'   => $prefix . 'photo',
                    'type' => 'file',
                    'options' => array('add_upload_file_text' => __( 'Upload', 'responsive-testimonials' )),
                    'row_classes' => 'de_hundred de_upload de_input',
                ));

        // Settings group
        $side_group = new_cmb2_box( array(
            'id' => $prefix . 'settings_head',
            'title' => '<span style="font-weight:400;">'.__( 'Settings', 'responsive-testimonials' ).'</span>',
            'object_types' => array( 'ttml' ),
            'context' => 'side',
            'priority' => 'high',
        ));

            $side_group->add_field( array(
                'name' => __( 'General settings', 'responsive-testimonials' ),
                'id'   => $prefix . 'gene_settings_desc',
                'type' => 'title',
                'row_classes' => 'de_hundred de_heading_side',
            ));

            $side_group->add_field( array(
                'name' => __( 'Main color', 'responsive-testimonials' ),
                'id' => $prefix . 'color',
                'type' => 'colorpicker',
                'default' => '#2b99e2',
                'row_classes' => 'de_first de_hundred de_color de_input',
            ));

            $side_group->add_field( array(
                'name' => __( 'Testimonials layout', 'responsive-testimonials' ),
    		        'id'   => $prefix . 'layout',
    		        'type'    => 'select',
    			      'options' => array(
    			          'tb2'   => __( 'Text below, 2 columns', 'responsive-testimonials' ),
    			          'tb3'   => __( 'Text below, 3 columns', 'responsive-testimonials' ),
                          'tr2'   => __( 'Text on the right, 2 columns', 'responsive-testimonials' ),
    			      ),
                'default' => 'tb3',
                'row_classes' => 'de_hundred de_text_side',
            ));

            $side_group->add_field( array(
                'name' => __( 'Author block background', 'responsive-testimonials' ),
    		        'id'   => $prefix . 'author_bg',
    		        'type'    => 'select',
    			      'options' => array(
    			          'transparent'   => __( 'Transparent', 'responsive-testimonials' ),
    			          'whitesmoke'   => __( 'Light grey', 'responsive-testimonials' ),
    			      ),
                'row_classes' => 'de_hundred de_text_side',
            ));

            $side_group->add_field( array(
                'name' => __( 'Force original fonts', 'responsive-testimonials' ).' <a class="wpd_tooltip" title="'.__( 'Check this to use the plugin\'s font instead of your theme\'s', 'responsive-testimonials' ).'"><span class="wpd_help_icon dashicons dashicons-editor-help"></span></a>',
                'desc' => __( 'Check to enable', 'responsive-testimonials' ),
    		        'id'   => $prefix . 'original_font',
    		        'type' => 'checkbox',
                'row_classes' => 'de_hundred de_checkbox_side',
                'default' => false,
            ));

            $side_group->add_field( array(
                'name' => __( 'Text settings', 'responsive-testimonials' ),
                'id'   => $prefix . 'other_settings_text',
                'type' => 'title',
                'row_classes' => 'de_hundred de_heading_side',
            ));

            $side_group->add_field( array(
                'name' => '',
                    'desc' => '<a id="ttml_font_sett_button" style="margin-top:-10px; cursor:pointer;"><span class="dashicons dashicons-admin-settings"></span> '.__( 'Adjust text settings', 'responsive-testimonials' ).'</a>',
                    'id'   => $prefix . 'pro_desc',
                    'type' => 'title',
                    'row_classes' => 'de_hundred de_info de_info_side',
            ));

            $side_group->add_field( array(
                'name' => __( 'Author\'s font size', 'responsive-testimonials' ),
                'id'   => $prefix . 'author_size',
                'type'    => 'select',
                'options' => array(
                    '17'   => __( 'Big', 'responsive-testimonials' ),
                    '15'   => __( 'Medium big', 'responsive-testimonials' ),
                    '14'   => __( 'Medium', 'responsive-testimonials' ),
                    '13'   => __( 'Medium small', 'responsive-testimonials' ),
                    '12'   => __( 'Small', 'responsive-testimonials' ),
                ),
                'default' => '14',
                'row_classes' => 'de_hundred de_text_side ttml_font_sett',
            ));

            $side_group->add_field( array(
                'name' => __( 'Job\'s font size', 'responsive-testimonials' ),
                'id'   => $prefix . 'job_size',
                'type'    => 'select',
                'options' => array(
                    '17'   => __( 'Big', 'responsive-testimonials' ),
                    '15'   => __( 'Medium big', 'responsive-testimonials' ),
                    '14'   => __( 'Medium', 'responsive-testimonials' ),
                    '13'   => __( 'Medium small', 'responsive-testimonials' ),
                    '12'   => __( 'Small', 'responsive-testimonials' ),
                ),
                'default' => '13',
                'row_classes' => 'de_hundred de_text_side ttml_font_sett',
            ));

            $side_group->add_field( array(
                'name' => __( 'Text\'s font size', 'responsive-testimonials' ),
                'id'   => $prefix . 'text_size',
                'type'    => 'select',
                'options' => array(
                    '17'   => __( 'Big', 'responsive-testimonials' ),
                    '15'   => __( 'Medium big', 'responsive-testimonials' ),
                    '14'   => __( 'Medium', 'responsive-testimonials' ),
                    '13'   => __( 'Medium small', 'responsive-testimonials' ),
                    '12'   => __( 'Small', 'responsive-testimonials' ),
                ),
                'default' => '14',
                'row_classes' => 'de_hundred de_text_side ttml_font_sett',
            ));


            // PRO version
            $pro_group = new_cmb2_box( array(
                'id' => $prefix . 'pro_metabox',
                'title' => '<span style="font-weight:400;">Upgrade to <strong>PRO version</strong></span>',
                'object_types' => array( 'ttml' ),
                'context' => 'side',
                'priority' => 'low',
                'row_classes' => 'de_hundred de_heading',
            ));

                $pro_group->add_field( array(
                    'name' => '',
                        'desc' => '<div><span class="dashicons dashicons-yes"></span> More layout options<br/><span class="dashicons dashicons-yes"></span> Main color for author background<br/><span class="dashicons dashicons-yes"></span> Heading quotes<br/><span class="dashicons dashicons-yes"></span> Picture\'s size<br/><span class="dashicons dashicons-arrow-right"></span> And more...<br/><br/><a style="display:inline-block; background:#33b690; padding:8px 25px 8px; border-bottom:3px solid #33a583; border-radius:3px; color:white;" class="wpd_pro_btn" target="_blank" href="https://wpdarko.com/items/responsive-testimonials/">See all PRO features</a><br/><span style="display:block;margin-top:14px; font-size:13px; color:#0073AA; line-height:20px;"><span class="dashicons dashicons-tickets"></span> Code <strong>9224661</strong> (10% OFF)</span></div>',
                        'id'   => $prefix . 'pro_desc',
                        'type' => 'title',
                        'row_classes' => 'de_hundred de_info de_info_side',
                ));

            // Help
            $help_group = new_cmb2_box( array(
                'id' => $prefix . 'help_metabox',
                'title' => '<span style="font-weight:400;">'.__( 'Help & Support', 'responsive-testimonials' ).'</span>',
                'object_types' => array( 'ttml' ),
                'context' => 'side',
                'priority' => 'low',
                'row_classes' => 'de_hundred de_heading',
            ));

                $help_group->add_field( array(
                    'name' => '',
                        'desc' => '<span style="font-size:15px;">'.__( 'Display your Testimonials', 'responsive-testimonials' ).'</span><br/><br/>'.__( 'To display your Testimonials on your site, copy-paste the Testimonial set\'s <strong>[Shortcode]</strong> in your post/page. You can find this shortcode by clicking <strong>All Testimonials sets</strong> in the menu on the left.', 'responsive-testimonials' ).'<br/><br/><span style="font-size:15px;">'.__( 'Get support', 'responsive-testimonials' ).'</span><br/><br/><a style="font-size:13px !important;" target="_blank" href="https://wpdarko.com/support/submit-a-request/">— '.__( 'Submit a ticket', 'responsive-testimonials' ).'</a><br/><a style="font-size:13px !important;" target="_blank" href="https://wpdarko.com/support/docs/get-started-with-the-responsive-testimonials-plugin/">— '.__( 'View documentation', 'responsive-testimonials' ).'</a>',
                        'id'   => $prefix . 'help_desc',
                        'type' => 'title',
                        'row_classes' => 'de_hundred de_info de_info_side',
                ));

    }

    ?>
