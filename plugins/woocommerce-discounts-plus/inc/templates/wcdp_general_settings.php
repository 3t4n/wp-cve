<?php

    global $wdp_pro, $wdpp_obj;

    $wdpp_obj->init_form_fields('div.row');
    $wdpp_form_fields = $wdpp_obj->fields['plus_discount'];





?>


<div class="wdp_general">

    <div class="row mt-3 title_row">
        <div class="col-md-12">

            <div class="h5">
				<?php echo esc_html(__( 'Discounts Plus', "wcdp").($wdp_pro?'+':'')); ?>
            </div>

            <p>
				<?php _e( 'The following options are specific to product Discounts Plus.', "wcdp" ) ?>
            </p>

            <div class="alert alert-info">
				<?php

				echo '<i>' . __( 'After changing the settings, it is recommended to clear all sessions in WooCommerce', "wcdp").' &gt; <a href="admin.php?page=wc-status">'.__('System Status', "wcdp").'</a> &gt; <a href="admin.php?page=wc-status&tab=tools">'.__('Tools', "wcdp").'</a></i>'

				?>
            </div>

        </div>
    </div>


    <form action="" method="post">

        <div class="row">
            <div class="col-md-12">
	            <?php echo '<a class="wcdp-optional-wrappers button float-md-right">'.__('Click here to display optional/layout settings', "wcdp").'</a>'; ?>
            </div>
        </div>

        <?php wp_nonce_field( 'wdp_settings_action', 'wdp_settings_nonce_field' ); ?>



        <?php

            if(!empty($wdpp_form_fields)){

                $counter = 1;

                $wrapper_ids = array(

//                        'woocommerce_tiers',
						'woocommerce_discount_label',
                        'woocommerce_show_discounted_price_shop',
                        'woocommerce_show_discounted_price_sp',
                        'woocommerce_show_discounted_price',
                        'woocommerce_cart_info',
                        'woocommerce_css_old_price',
                        'woocommerce_css_new_price',
                        'woocommerce_show_on_subtotal',
                );

                foreach ($wdpp_form_fields as $form_field){

                            $field_type = isset($form_field['type']) ? $form_field['type'] : 'undefined' ;
                            $field_id = $form_field['id'];
                            $field_wrapper = in_array($form_field['id'], $wrapper_ids) ? $form_field['id'].'_wrapper' : '';
                            $field_name = isset($form_field['name']) ? $form_field['name'] : '' ;
                            $field_title = isset($form_field['title']) ? $form_field['title'] : '' ;
                            $field_desc = isset($form_field['desc']) ? $form_field['desc'] : "" ;
                            $field_desc_tip = isset($form_field['desc_tip']) ? $form_field['desc_tip'] : false ;
                            $field_css = isset($form_field['css']) ? $form_field['css'] : "" ;
                            $field_default = isset($form_field['default']) ? $form_field['default'] : "" ;
                            $field_class = isset($form_field['class']) ? $form_field['class'] : "" ;
	                        $field_option = isset($form_field['options']) ? $form_field['options'] : array() ;
                            $mt = $counter == 1 ? 'mt-5' : 'mt-2';
                            $field_option_value = get_option($field_id);
                            $checked = $field_default == 'yes' ? 'checked': '';
                            if($field_option_value !== false && is_string($field_option_value)){


                                if($field_option_value == 'yes'){

                                    $checked = 'checked';

                                }

                                if ($field_option_value == 'no'){

                                    $checked = 'notchecked';

                                }

                            }

                            $counter++;

                        switch ($field_type){

                            case 'checkbox':




	                            ?>

                                    <div class="row <?php echo esc_attr($mt.' '.$field_wrapper); ?>">
                                        <div class="col-md-5">
                                            <label for="">
                                                <?php echo esc_html($field_name); ?>
                                            </label>
                                        </div>

                                        <div class="col-md-7">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_id); ?>" value="1" <?php echo esc_attr($checked); ?>>
                                                <label class="custom-control-label" for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($field_desc); ?></label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div>

                                    </div>

	                            <?php

                                break;

                            case 'multiselect':

                                ?>

                                    <div class="row <?php echo esc_attr($mt.' '.$field_wrapper); ?>">
                                        <div class="col-md-5">
                                            <label for="<?php echo esc_attr($field_id); ?>">
                                                <?php echo esc_html($field_title); ?>
                                            </label>
                                        </div>

                                        <div class="col-md-7 ml-md-0 ml-3 position-relative">
	                                        <?php if($field_desc_tip): ?>
                                                <span class="position-absolute wcpd_help_tip dashicons dashicons-editor-help" title="<?php echo esc_attr($field_desc); ?>" data-toggle="tooltip" data-placement="bottom"></span>
	                                        <?php endif; ?>
                                            <select name="<?php echo esc_attr($field_id); ?>[]" class="custom-select-lg w-md-50 w-75 <?php echo esc_attr($field_class); ?>" multiple size="7" id="<?php echo esc_attr($field_id); ?>" style="">
                                                <?php if(function_exists('wdp_options_html')) wdp_options_html($field_option, (array) $field_option_value) ?>
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div>

                                    </div>

                                <?php

                                break;

                            case 'select':

                                ?>

                                    <div class="row <?php echo esc_attr($mt.' '.$field_wrapper); ?>">
                                        <div class="col-md-5">
                                            <label for="<?php echo esc_attr($field_id); ?>">
                                                <?php echo esc_attr($field_title); ?>
                                            </label>
                                        </div>

                                        <div class="col-md-7 ml-md-0 ml-3 position-relative">
	                                        <?php if($field_desc_tip): ?>
                                                <span class="position-absolute wcpd_help_tip dashicons dashicons-editor-help" title="<?php echo esc_attr($field_desc); ?>" data-toggle="tooltip" data-placement="bottom"></span>
	                                        <?php endif; ?>
                                            <div class="<?php echo esc_attr($field_id); ?>_select">
                                            <select name="<?php echo esc_attr($field_id); ?>" class="custom-select-lg w-md-50 w-75 <?php echo esc_attr($field_class); ?>" id="<?php echo esc_attr($field_id); ?>">
                                                <?php if(function_exists('wdp_options_html')) wdp_options_html($field_option, (array) $field_option_value) ?>
                                            </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div>

                                    </div>

                                <?php

                                break;

                            case 'textarea':

                                ?>

                                    <div class="row <?php echo esc_attr($mt.' '.$field_wrapper); ?>">
                                        <div class="col-md-5">
                                            <label for="<?php echo esc_attr($field_id); ?>">
	                                            <?php echo esc_html($field_name); ?>
                                            </label>
                                        </div>

                                        <div class="col-md-7">
                                            <?php if($field_desc): ?><label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($field_desc); ?></label><?php endif;?>
                                            <textarea name="<?php echo esc_attr($field_id); ?>" id="<?php echo esc_attr($field_id); ?>" class="form-control"><?php echo  esc_textarea($field_option_value !== false && is_string($field_option_value) && $field_option_value ? $field_option_value : $field_default);  ?></textarea>
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div>

                                    </div>

                                <?php

                                break;
								
                            case 'text':

                                ?>

                                    <div class="row <?php echo esc_attr($mt.' '.$field_wrapper); ?>">
                                        <div class="col-md-5">
                                            <label for="<?php echo esc_attr($field_id); ?>">
	                                            <?php echo esc_html($field_name); ?>
                                            </label>
                                        </div>

                                        <div class="col-md-7">
                                            <input title="<?php echo esc_attr($field_desc); ?>" type="text" name="<?php echo esc_attr($field_id); ?>" id="<?php echo esc_attr($field_id); ?>" class="form-control" value="<?php echo  esc_attr($field_option_value !== false && is_string($field_option_value) && $field_option_value ? $field_option_value : $field_default);  ?>" />
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div>

                                    </div>

                                <?php

                                break;								

                        }



                }
            }


        ?>


        <div class="row mt-2">
            <div class="col-md-12">
                <p>
                    <i>
                    <?php
                        echo ($wdp_pro?__('Discount Available Conditionally?', "wcdp").' <a href="'.admin_url().'admin.php?page=wc_wcdp&t=6" class="wcdp_error_message_link">'.__('Click here to define error messages', "wcdp").'</a><br />':'').__('If you find the', "wcdp").' <a target="_blank" href="https://wordpress.org/plugins/woocommerce-discounts-plus/screenshots/">'.$wcdp_data['Name'].'</a> '.__('extension useful, please visit our online store for more', "wcdp").' <a target="_blank" href="https://shop.androidbubbles.com/go/">'.__('premium products', "wcdp").'</a>.<br />'
                    ?>
                    </i>
                </p>
            </div>
        </div>



        <div class="row mt-3 wpdp_submit_button">

            <div class="col-md-2">

                <button class="btn btn-primary" type="submit" name="wpdp_general_save_changes"><?php _e( 'Save Changes', "wcdp" ) ?></button>


            </div>

        </div>

    </form>


</div>